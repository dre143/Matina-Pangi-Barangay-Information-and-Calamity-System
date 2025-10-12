<?php

namespace App\Http\Controllers;

use App\Models\HealthRecord;
use App\Models\Resident;
use Illuminate\Http\Request;

class HealthRecordController extends Controller
{
    /**
     * Display a listing of health records
     */
    public function index(Request $request)
    {
        $query = HealthRecord::with('resident');

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('resident', function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%");
            });
        }

        $healthRecords = $query->latest()->paginate(20);

        return view('health-records.index', compact('healthRecords'));
    }

    /**
     * Show the form for creating a new health record
     */
    public function create()
    {
        $residents = Resident::approved()->active()->orderBy('last_name')->get();
        return view('health-records.create', compact('residents'));
    }

    /**
     * Store a newly created health record
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'resident_id' => 'required|exists:residents,id',
            'blood_type' => 'nullable|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'height' => 'nullable|numeric|min:0',
            'weight' => 'nullable|numeric|min:0',
            'medical_conditions' => 'nullable|string',
            'allergies' => 'nullable|string',
            'medications' => 'nullable|string',
            'emergency_contact' => 'nullable|string|max:255',
            'emergency_contact_number' => 'nullable|string|max:20',
            'philhealth_number' => 'nullable|string|max:50',
            'notes' => 'nullable|string',
        ]);

        $healthRecord = HealthRecord::create($validated);

        return redirect()->route('health-records.show', $healthRecord)
                        ->with('success', 'Health record created successfully!');
    }

    /**
     * Display the specified health record
     */
    public function show(HealthRecord $healthRecord)
    {
        $healthRecord->load('resident');
        return view('health-records.show', compact('healthRecord'));
    }

    /**
     * Show the form for editing the specified health record
     */
    public function edit(HealthRecord $healthRecord)
    {
        $residents = Resident::approved()->active()->orderBy('last_name')->get();
        return view('health-records.edit', compact('healthRecord', 'residents'));
    }

    /**
     * Update the specified health record
     */
    public function update(Request $request, HealthRecord $healthRecord)
    {
        $validated = $request->validate([
            'blood_type' => 'nullable|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'height' => 'nullable|numeric|min:0',
            'weight' => 'nullable|numeric|min:0',
            'medical_conditions' => 'nullable|string',
            'allergies' => 'nullable|string',
            'medications' => 'nullable|string',
            'emergency_contact' => 'nullable|string|max:255',
            'emergency_contact_number' => 'nullable|string|max:20',
            'philhealth_number' => 'nullable|string|max:50',
            'notes' => 'nullable|string',
        ]);

        $healthRecord->update($validated);
        
        // Refresh the model to get latest data
        $healthRecord->refresh();

        return redirect()->route('health-records.show', $healthRecord)
                        ->with('success', 'Health record updated successfully!');
    }

    /**
     * Remove the specified health record
     */
    public function destroy(HealthRecord $healthRecord)
    {
        $healthRecord->delete();

        return redirect()->route('health-records.index')
                        ->with('success', 'Health record deleted successfully!');
    }
}
