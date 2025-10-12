<?php

namespace App\Http\Controllers;

use App\Models\SeniorHealth;
use App\Models\Resident;
use Illuminate\Http\Request;

class SeniorHealthController extends Controller
{
    /**
     * Display a listing of senior health records
     */
    public function index(Request $request)
    {
        $query = SeniorHealth::with('resident');

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('resident', function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%");
            });
        }

        $seniorHealthRecords = $query->latest()->paginate(20);

        return view('senior-health.index', compact('seniorHealthRecords'));
    }

    /**
     * Show the form for creating a new senior health record
     */
    public function create()
    {
        // Get only senior citizens (60+)
        $residents = Resident::approved()->active()
                            ->whereRaw('TIMESTAMPDIFF(YEAR, birthdate, CURDATE()) >= 60')
                            ->orderBy('last_name')
                            ->get();
        return view('senior-health.create', compact('residents'));
    }

    /**
     * Store a newly created senior health record
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'resident_id' => 'required|exists:residents,id|unique:senior_health,resident_id',
            'health_conditions' => 'nullable|string',
            'medications' => 'nullable|string',
            'mobility_status' => 'nullable|in:independent,assisted,wheelchair,bedridden',
            'last_checkup_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        $seniorHealth = SeniorHealth::create($validated);

        return redirect()->route('senior-health.show', $seniorHealth)
                        ->with('success', 'Senior health record created successfully!');
    }

    /**
     * Display the specified senior health record
     */
    public function show(SeniorHealth $seniorHealth)
    {
        $seniorHealth->load('resident');
        return view('senior-health.show', compact('seniorHealth'));
    }

    /**
     * Show the form for editing the specified senior health record
     */
    public function edit(SeniorHealth $seniorHealth)
    {
        $residents = Resident::approved()->active()
                            ->whereRaw('TIMESTAMPDIFF(YEAR, birthdate, CURDATE()) >= 60')
                            ->orderBy('last_name')
                            ->get();
        return view('senior-health.edit', compact('seniorHealth', 'residents'));
    }

    /**
     * Update the specified senior health record
     */
    public function update(Request $request, SeniorHealth $seniorHealth)
    {
        $validated = $request->validate([
            'health_conditions' => 'nullable|string',
            'medications' => 'nullable|string',
            'mobility_status' => 'nullable|in:independent,assisted,wheelchair,bedridden',
            'last_checkup_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        $seniorHealth->update($validated);

        return redirect()->route('senior-health.show', $seniorHealth)
                        ->with('success', 'Senior health record updated successfully!');
    }

    /**
     * Remove the specified senior health record
     */
    public function destroy(SeniorHealth $seniorHealth)
    {
        $seniorHealth->delete();

        return redirect()->route('senior-health.index')
                        ->with('success', 'Senior health record deleted successfully!');
    }
}
