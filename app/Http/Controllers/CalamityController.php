<?php

namespace App\Http\Controllers;

use App\Models\Calamity;
use App\Models\CalamityAffectedHousehold;
use App\Models\Household;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CalamitiesExport;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CalamityController extends Controller
{
    /**
     * Display a listing of calamities
     */
    public function index(Request $request)
    {
        $query = Calamity::query()->withCount('affectedHouseholds');

        $type = $request->get('type');
        $severity = $request->get('severity');
        $area = $request->get('area');
        $from = $request->get('from');
        $to = $request->get('to');
        $sort = $request->get('sort', 'date_desc');

        if ($type) {
            $query->where('calamity_type', $type);
        }
        if ($severity) {
            $query->where('severity_level', $severity);
        }
        if ($from && $to) {
            $query->whereBetween('date_occurred', [$from, $to]);
        } elseif ($from) {
            $query->whereDate('date_occurred', '>=', $from);
        } elseif ($to) {
            $query->whereDate('date_occurred', '<=', $to);
        }
        if ($area) {
            $query->where(function ($q) use ($area) {
                $q->where('affected_areas', 'like', "%$area%");
                if (method_exists($q->getModel()->newQuery(), 'whereJsonContains')) {
                    $q->orWhereJsonContains('affected_puroks', $area);
                }
            });
        }

        switch ($sort) {
            case 'type_asc':
                $query->orderBy('calamity_type', 'asc');
                break;
            case 'type_desc':
                $query->orderBy('calamity_type', 'desc');
                break;
            case 'severity_asc':
                $query->orderBy('severity_level', 'asc');
                break;
            case 'severity_desc':
                $query->orderBy('severity_level', 'desc');
                break;
            case 'date_asc':
                $query->orderBy('date_occurred', 'asc');
                break;
            case 'date_desc':
            default:
                $query->orderBy('date_occurred', 'desc');
        }

        $calamities = $query->paginate(20)->appends($request->query());

        $filters = [
            'type' => $type,
            'severity' => $severity,
            'area' => $area,
            'from' => $from,
            'to' => $to,
            'sort' => $sort,
        ];

        return view('calamities.index', compact('calamities', 'filters'));
    }

    /**
     * Show the form for creating a new calamity
     */
    public function create()
    {
        return view('calamities.create');
    }

    /**
     * Store a newly created calamity
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'calamity_name' => 'required|string|max:255',
            'calamity_type' => 'required|in:typhoon,flood,earthquake,fire,landslide,drought,other',
            'date_occurred' => 'required|date',
            'severity_level' => 'required|in:minor,moderate,severe,catastrophic',
            'affected_areas' => 'nullable|string',
            'description' => 'nullable|string',
            'response_actions' => 'nullable|string',
            'status' => 'required|in:ongoing,resolved,monitoring',
            'photos' => 'nullable|array',
            'photos.*' => 'file|mimes:jpeg,png,jpg|max:5120',
        ]);

        $validated['reported_by'] = auth()->id();

        $photos = [];
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $file) {
                $filename = Str::uuid()->toString() . '-' . time() . '.' . $file->getClientOriginalExtension();
                $file->storeAs('public/calamity_incident_photos', $filename);
                $photos[] = $filename;
            }
        }
        if (!empty($photos)) {
            $validated['photos'] = $photos;
        }

        $calamity = Calamity::create($validated);

        return redirect()->route('calamities.show', $calamity)
                        ->with('success', 'Calamity record created successfully!');
    }

    /**
     * Display the specified calamity
     */
    public function show(Calamity $calamity)
    {
        $calamity->load(['affectedHouseholds.household']);
        
        // Load reporter if exists
        if ($calamity->reported_by) {
            $calamity->load('reporter');
        }
        
        return view('calamities.show', compact('calamity'));
    }

    /**
     * Show the form for editing the specified calamity
     */
    public function edit(Calamity $calamity)
    {
        return view('calamities.edit', compact('calamity'));
    }

    /**
     * Update the specified calamity
     */
    public function update(Request $request, Calamity $calamity)
    {
        $validated = $request->validate([
            'calamity_name' => 'required|string|max:255',
            'calamity_type' => 'required|in:typhoon,flood,earthquake,fire,landslide,drought,other',
            'date_occurred' => 'required|date',
            'severity_level' => 'required|in:minor,moderate,severe,catastrophic',
            'affected_areas' => 'nullable|string',
            'description' => 'nullable|string',
            'response_actions' => 'nullable|string',
            'status' => 'required|in:ongoing,resolved,monitoring',
            'photos' => 'nullable|array',
            'photos.*' => 'file|mimes:jpeg,png,jpg|max:5120',
        ]);

        $existing = is_array($calamity->photos) ? $calamity->photos : [];
        $added = [];
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $file) {
                $filename = Str::uuid()->toString() . '-' . time() . '.' . $file->getClientOriginalExtension();
                $file->storeAs('public/calamity_incident_photos', $filename);
                $added[] = $filename;
            }
        }
        if (!empty($added)) {
            $validated['photos'] = array_values(array_unique(array_merge($existing, $added)));
        }

        $calamity->update($validated);

        return redirect()->route('calamities.show', $calamity)
                        ->with('success', 'Calamity record updated successfully!');
    }

    public function destroy(Calamity $calamity)
    {
        $calamity->delete();
        return redirect()->route('calamities.index')
            ->with('success', 'Calamity record archived successfully!');
    }

    /**
     * Add affected household
     */
    public function addAffectedHousehold(Request $request, Calamity $calamity)
    {
        $validated = $request->validate([
            'household_id' => 'required|exists:households,id',
            'damage_level' => 'required|in:minor,moderate,severe,total',
            'estimated_damage_cost' => 'nullable|numeric|min:0',
            'assistance_needed' => 'nullable|string',
            'assistance_provided' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $validated['calamity_id'] = $calamity->id;

        CalamityAffectedHousehold::create($validated);

        return redirect()->back()
                        ->with('success', 'Affected household added successfully!');
    }

    /**
     * Show form to add affected households
     */
    public function showAddHouseholds(Calamity $calamity)
    {
        $households = Household::approved()->with('purok')->orderBy('household_number')->get();
        return view('calamities.add-households', compact('calamity', 'households'));
    }

    public function exportPdf(Request $request)
    {
        $query = $this->buildFilteredQuery($request);
        $calamities = $query->get();
        $pdf = Pdf::loadView('calamities.export_pdf', compact('calamities'));
        return $pdf->download('calamities-'.date('Y-m-d').'.pdf');
    }

    public function exportExcel(Request $request)
    {
        $query = $this->buildFilteredQuery($request);
        $calamities = $query->get();
        return Excel::download(new CalamitiesExport($calamities), 'calamities-'.date('Y-m-d').'.xlsx');
    }

    private function buildFilteredQuery(Request $request)
    {
        $query = Calamity::query()->withCount('affectedHouseholds');
        if ($request->filled('type')) {
            $query->where('calamity_type', $request->get('type'));
        }
        if ($request->filled('severity')) {
            $query->where('severity_level', $request->get('severity'));
        }
        $from = $request->get('from');
        $to = $request->get('to');
        if ($from && $to) {
            $query->whereBetween('date_occurred', [$from, $to]);
        } elseif ($from) {
            $query->whereDate('date_occurred', '>=', $from);
        } elseif ($to) {
            $query->whereDate('date_occurred', '<=', $to);
        }
        if ($request->filled('area')) {
            $area = $request->get('area');
            $query->where(function ($q) use ($area) {
                $q->where('affected_areas', 'like', "%$area%");
                if (method_exists($q->getModel()->newQuery(), 'whereJsonContains')) {
                    $q->orWhereJsonContains('affected_puroks', $area);
                }
            });
        }
        return $query->orderBy('date_occurred', 'desc');
    }

    public function seedSamples()
    {
        $samples = [
            [
                'calamity_name' => 'Fire at Riverside',
                'calamity_type' => 'fire',
                'date_occurred' => Carbon::parse('2025-01-15'),
                'severity_level' => 'moderate',
                'affected_areas' => 'Riverside, Purok 3',
                'affected_puroks' => ['Purok 3'],
                'description' => 'Residential fire contained within 2 hours.',
                'status' => 'resolved',
            ],
            [
                'calamity_name' => 'Flood due to heavy rain',
                'calamity_type' => 'flood',
                'date_occurred' => Carbon::parse('2025-03-02'),
                'severity_level' => 'severe',
                'affected_areas' => 'Lower Matina, Purok 5',
                'affected_puroks' => ['Purok 5'],
                'description' => 'River overflow affecting low-lying areas.',
                'status' => 'resolved',
            ],
            [
                'calamity_name' => 'Typhoon Agila landfall',
                'calamity_type' => 'typhoon',
                'date_occurred' => Carbon::parse('2025-06-20'),
                'severity_level' => 'catastrophic',
                'affected_areas' => 'All puroks',
                'affected_puroks' => ['Purok 1','Purok 2','Purok 3','Purok 4','Purok 5','Purok 6','Purok 7'],
                'description' => 'Widespread damage from strong winds and rain.',
                'status' => 'ongoing',
            ],
            [
                'calamity_name' => 'Earthquake tremor',
                'calamity_type' => 'earthquake',
                'date_occurred' => Carbon::parse('2025-09-05'),
                'severity_level' => 'moderate',
                'affected_areas' => 'Hilltop area, Purok 2',
                'affected_puroks' => ['Purok 2'],
                'description' => 'Minor structural cracks reported.',
                'status' => 'monitoring',
            ],
            [
                'calamity_name' => 'Landslide after continuous rain',
                'calamity_type' => 'landslide',
                'date_occurred' => Carbon::parse('2025-11-12'),
                'severity_level' => 'severe',
                'affected_areas' => 'Hillside, Purok 7',
                'affected_puroks' => ['Purok 7'],
                'description' => 'Road blockage and two houses affected.',
                'status' => 'ongoing',
            ],
            [
                'calamity_name' => 'Drought affecting water supply',
                'calamity_type' => 'drought',
                'date_occurred' => Carbon::parse('2024-04-10'),
                'severity_level' => 'minor',
                'affected_areas' => 'Upper Matina',
                'affected_puroks' => ['Purok 4'],
                'description' => 'Water rationing implemented.',
                'status' => 'resolved',
            ],
            [
                'calamity_name' => 'Residential fire at Purok 1',
                'calamity_type' => 'fire',
                'date_occurred' => Carbon::parse('2025-02-10'),
                'severity_level' => 'minor',
                'affected_areas' => 'Purok 1',
                'affected_puroks' => ['Purok 1'],
                'description' => 'Quickly contained by response team.',
                'status' => 'resolved',
            ],
            [
                'calamity_name' => 'Flash flood after typhoon',
                'calamity_type' => 'flood',
                'date_occurred' => Carbon::parse('2025-07-01'),
                'severity_level' => 'moderate',
                'affected_areas' => 'Riverside areas',
                'affected_puroks' => ['Purok 3','Purok 5'],
                'description' => 'Cleanup ongoing; relief distribution initiated.',
                'status' => 'monitoring',
            ],
        ];

        foreach ($samples as $data) {
            Calamity::firstOrCreate(
                [
                    'calamity_name' => $data['calamity_name'],
                    'date_occurred' => $data['date_occurred'],
                ],
                array_merge($data, [
                    'severity' => $data['severity_level'],
                    'reported_by' => auth()->id(),
                ])
            );
        }

        return redirect()->route('calamities.index')
            ->with('success', 'Sample incidents added. You can now test filtering.');
    }
    public function archived()
    {
        $calamities = Calamity::onlyTrashed()->latest('deleted_at')->paginate(20);
        return view('calamities.archived', compact('calamities'));
    }

    public function restore($id)
    {
        $calamity = Calamity::onlyTrashed()->findOrFail($id);
        $calamity->restore();
        return back()->with('success', 'Calamity record restored successfully!');
    }

    public function forceDelete($id)
    {
        $calamity = Calamity::onlyTrashed()->findOrFail($id);
        $calamity->forceDelete();
        return back()->with('success', 'Calamity record permanently deleted!');
    }
}
