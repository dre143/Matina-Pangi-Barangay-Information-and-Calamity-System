<?php

namespace App\Http\Controllers;

use App\Models\PwdSupport;
use App\Models\Resident;
use Illuminate\Http\Request;

class PwdSupportController extends Controller
{
    /**
     * Display a listing of PWD records
     */
    public function index(Request $request)
    {
        $query = PwdSupport::with('resident');

        // Filter by disability type
        if ($request->filled('disability_type')) {
            $query->where('disability_type', $request->disability_type);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('pwd_id_number', 'like', "%{$search}%")
                  ->orWhereHas('resident', function($q) use ($search) {
                      $q->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%");
                  });
            });
        }

        $pwdRecords = $query->latest()->paginate(20);

        return view('pwd-support.index', compact('pwdRecords'));
    }

    /**
     * Show the form for creating a new PWD record
     */
    public function create()
    {
        $residents = Resident::approved()->active()->orderBy('last_name')->get();
        return view('pwd-support.create', compact('residents'));
    }

    /**
     * Store a newly created PWD record
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'resident_id' => 'required|exists:residents,id|unique:pwd_support,resident_id',
            'pwd_id_number' => 'required|string|max:50|unique:pwd_support,pwd_id_number',
            'disability_type' => 'required|in:visual,hearing,mobility,mental,psychosocial,multiple,other',
            'disability_description' => 'nullable|string',
            'date_registered' => 'required|date',
            'assistance_received' => 'nullable|string',
            'medical_needs' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $pwdRecord = PwdSupport::create($validated);

        return redirect()->route('pwd-support.show', $pwdRecord)
                        ->with('success', 'PWD record created successfully!');
    }

    /**
     * Display the specified PWD record
     */
    public function show(PwdSupport $pwdSupport)
    {
        $pwdSupport->load('resident');
        return view('pwd-support.show', compact('pwdSupport'));
    }

    /**
     * Show the form for editing the specified PWD record
     */
    public function edit(PwdSupport $pwdSupport)
    {
        $residents = Resident::approved()->active()->orderBy('last_name')->get();
        return view('pwd-support.edit', compact('pwdSupport', 'residents'));
    }

    /**
     * Update the specified PWD record
     */
    public function update(Request $request, PwdSupport $pwdSupport)
    {
        $validated = $request->validate([
            'pwd_id_number' => [
                'required',
                'string',
                'max:50',
                'unique:pwd_support,pwd_id_number,' . $pwdSupport->id . ',id'
            ],
            'disability_type' => 'required|in:visual,hearing,mobility,mental,psychosocial,multiple,other',
            'disability_description' => 'nullable|string',
            'date_registered' => 'required|date',
            'assistance_received' => 'nullable|string',
            'medical_needs' => 'nullable|string',
            'notes' => 'nullable|string',
        ], [
            'pwd_id_number.unique' => 'This PWD ID number is already being used by another resident.',
            'date_registered.required' => 'The date registered field is required.',
            'date_registered.date' => 'Please enter a valid date.',
        ]);

        $pwdSupport->update($validated);
        $pwdSupport->refresh();

        return redirect()->route('pwd-support.show', $pwdSupport)
                        ->with('success', 'PWD record updated successfully!');
    }

    /**
     * Remove the specified PWD record
     */
    public function destroy(PwdSupport $pwdSupport)
    {
        $pwdSupport->delete();

        return redirect()->route('pwd-support.index')
                        ->with('success', 'PWD record deleted successfully!');
    }
}
