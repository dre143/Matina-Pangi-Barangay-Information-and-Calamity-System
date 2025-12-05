<?php

namespace App\Http\Controllers;

use App\Models\Resident;
use App\Models\Household;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ResidentController extends Controller
{
    /**
     * Display a listing of residents
     */
    public function index(Request $request)
    {
        $query = Resident::with('household')->approved();

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('resident_id', 'like', "%{$search}%");
            });
        }

        // Filter by category
        if ($request->filled('category')) {
            switch ($request->category) {
                case 'pwd':
                    $query->pwd();
                    break;
                case 'senior':
                    $query->seniorCitizens();
                    break;
                case 'teen':
                    $query->teens();
                    break;
                case 'voter':
                    $query->voters();
                    break;
                case '4ps':
                    $query->where('is_4ps_beneficiary', true);
                    break;
                case 'head':
                    $query->householdHeads();
                    break;
            }
        }

        // Filter by gender
        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }

        // Filter by civil status
        if ($request->filled('civil_status')) {
            $query->where('civil_status', $request->civil_status);
        }

        // Filter by age range
        if ($request->filled('age_from')) {
            $query->where('age', '>=', $request->age_from);
        }
        if ($request->filled('age_to')) {
            $query->where('age', '<=', $request->age_to);
        }

        // Filter by purok
        if ($request->filled('purok')) {
            $query->where('purok', $request->purok);
        }

        // Filter by employment status
        if ($request->filled('employment')) {
            $query->where('employment_status', $request->employment);
        }

        $residents = $query->latest()->paginate(15)->appends($request->except('page'));

        return view('residents.index', compact('residents'));
    }

    /**
     * Show the form for creating a new resident
     */
    public function create()
    {
        $households = Household::with('head')->get();
        return view('residents.create', compact('households'));
    }

    /**
     * Store a newly created resident
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'household_id' => 'required|exists:households,id',
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'suffix' => 'nullable|string|max:50',
            'birthdate' => 'required|date|before:today',
            'sex' => 'required|in:male,female',
            'civil_status' => 'required|in:single,married,widowed,separated,divorced',
            'place_of_birth' => 'nullable|string|max:255',
            'nationality' => 'nullable|string|max:100',
            'religion' => 'nullable|string|max:100',
            'contact_number' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'household_role' => 'required|in:head,spouse,child,parent,sibling,relative,other',
            'is_household_head' => 'boolean',
            'is_pwd' => 'boolean',
            'pwd_id' => 'nullable|string|max:100',
            'disability_type' => 'nullable|string|max:255',
            'is_voter' => 'boolean',
            'precinct_number' => 'nullable|string|max:50',
            'is_4ps_beneficiary' => 'boolean',
            '4ps_id' => 'nullable|string|max:100',
            'occupation' => 'nullable|string|max:255',
            'employment_status' => 'nullable|in:employed,unemployed,self-employed,student,retired',
            'employer_name' => 'nullable|string|max:255',
            'monthly_income' => 'nullable|numeric|min:0',
            'educational_attainment' => 'nullable|string',
            'blood_type' => 'nullable|string|max:10',
            'medical_conditions' => 'nullable|string',
            'remarks' => 'nullable|string',
            'status' => 'nullable|in:active,reallocated,deceased',
        ]);

        $validated['created_by'] = auth()->id();
        $validated['updated_by'] = auth()->id();

        $nameInput = strtolower(trim(implode(' ', array_filter([
            trim($validated['first_name']),
            isset($validated['middle_name']) ? trim($validated['middle_name']) : '',
            trim($validated['last_name']),
            isset($validated['suffix']) ? trim($validated['suffix']) : '',
        ], fn($v) => $v !== ''))));

        $dupExists = Resident::query()
            ->whereRaw("LOWER(TRIM(CONCAT_WS(' ', first_name, middle_name, last_name, suffix))) = ?", [$nameInput])
            ->exists();
        if ($dupExists) {
            return back()->withErrors(['duplicate' => 'A resident with the same name already exists.'])->withInput();
        }

        if (isset($validated['status']) && $validated['status'] === 'deceased') {
            $validated['status_changed_at'] = now();
            $validated['status_changed_by'] = auth()->id();
        }
        $resident = Resident::create($validated);

        AuditLog::logAction(
            'create',
            'Resident',
            $resident->id,
            "Created resident: {$resident->full_name}",
            null,
            $validated
        );

        return redirect()->route('residents.show', $resident)
            ->with('success', 'Resident registered successfully!');
    }

    /**
     * Display the specified resident
     */
    public function show(Resident $resident)
    {
        $resident->load('household', 'creator', 'updater');
        return view('residents.show', compact('resident'));
    }

    /**
     * Show the form for editing the specified resident
     */
    public function edit(Resident $resident)
    {
        // Only secretary can edit
        if (!auth()->user()->isSecretary()) {
            abort(403, 'Unauthorized action.');
        }

        $households = Household::with('head')->get();
        return view('residents.edit', compact('resident', 'households'));
    }

    /**
     * Update the specified resident
     */
    public function update(Request $request, Resident $resident)
    {
        // Only secretary can update
        if (!auth()->user()->isSecretary()) {
            abort(403, 'Unauthorized action.');
        }

        $oldValues = $resident->toArray();

        $validated = $request->validate([
            'household_id' => 'required|exists:households,id',
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'suffix' => 'nullable|string|max:50',
            'birthdate' => 'required|date|before:today',
            'sex' => 'required|in:male,female',
            'civil_status' => 'required|in:single,married,widowed,separated,divorced',
            'place_of_birth' => 'nullable|string|max:255',
            'nationality' => 'nullable|string|max:100',
            'religion' => 'nullable|string|max:100',
            'contact_number' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'household_role' => 'required|in:head,spouse,child,parent,sibling,relative,other',
            'is_household_head' => 'boolean',
            'is_pwd' => 'boolean',
            'pwd_id' => 'nullable|string|max:100',
            'disability_type' => 'nullable|string|max:255',
            'is_voter' => 'boolean',
            'precinct_number' => 'nullable|string|max:50',
            'is_4ps_beneficiary' => 'boolean',
            '4ps_id' => 'nullable|string|max:100',
            'occupation' => 'nullable|string|max:255',
            'employment_status' => 'nullable|in:employed,unemployed,self-employed,student,retired',
            'employer_name' => 'nullable|string|max:255',
            'monthly_income' => 'nullable|numeric|min:0',
            'educational_attainment' => 'nullable|string',
            'blood_type' => 'nullable|string|max:10',
            'medical_conditions' => 'nullable|string',
            'remarks' => 'nullable|string',
            'status' => 'nullable|in:active,reallocated,deceased',
        ]);

        $validated['updated_by'] = auth()->id();

        if (isset($validated['status']) && $validated['status'] !== $resident->status) {
            $validated['status_changed_at'] = now();
            $validated['status_changed_by'] = auth()->id();
        }
        $resident->update($validated);

        AuditLog::logAction(
            'update',
            'Resident',
            $resident->id,
            "Updated resident: {$resident->full_name}",
            $oldValues,
            $validated
        );

        return redirect()->route('residents.show', $resident)
            ->with('success', 'Resident updated successfully!');
    }

    /**
     * Remove the specified resident
     */
    public function destroy(Resident $resident)
    {
        // Only secretary can delete
        if (!auth()->user()->isSecretary()) {
            abort(403, 'Unauthorized action.');
        }

        $residentName = $resident->full_name;
        $residentId = $resident->id;

        $resident->delete();

        AuditLog::logAction(
            'delete',
            'Resident',
            $residentId,
            "Deleted resident: {$residentName}"
        );

        return redirect()->route('residents.index')
            ->with('success', 'Resident deleted successfully!');
    }
}
