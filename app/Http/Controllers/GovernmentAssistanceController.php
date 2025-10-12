<?php

namespace App\Http\Controllers;

use App\Models\GovernmentAssistance;
use App\Models\Resident;
use Illuminate\Http\Request;

class GovernmentAssistanceController extends Controller
{
    /**
     * Display a listing of government assistance
     */
    public function index(Request $request)
    {
        $query = GovernmentAssistance::with('resident');

        // Filter by program type
        if ($request->filled('program_type')) {
            $query->where('program_type', $request->program_type);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('program_name', 'like', "%{$search}%")
                  ->orWhereHas('resident', function($q) use ($search) {
                      $q->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%");
                  });
            });
        }

        $assistanceRecords = $query->latest()->paginate(20);

        return view('government-assistance.index', compact('assistanceRecords'));
    }

    /**
     * Show the form for creating a new assistance record
     */
    public function create()
    {
        $residents = Resident::approved()->active()->orderBy('last_name')->get();
        return view('government-assistance.create', compact('residents'));
    }

    /**
     * Store a newly created assistance record
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'resident_id' => 'required|exists:residents,id',
            'program_name' => 'required|string|max:255',
            'program_type' => 'required|in:4ps,sss,philhealth,ayuda,scholarship,livelihood,housing,other',
            'amount' => 'nullable|numeric|min:0',
            'date_received' => 'required|date',
            'status' => 'required|in:active,completed,cancelled',
            'description' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $assistance = GovernmentAssistance::create($validated);

        return redirect()->route('government-assistance.show', $assistance)
                        ->with('success', 'Assistance record created successfully!');
    }

    /**
     * Display the specified assistance record
     */
    public function show(GovernmentAssistance $governmentAssistance)
    {
        $governmentAssistance->load('resident');
        return view('government-assistance.show', compact('governmentAssistance'));
    }

    /**
     * Show the form for editing the specified assistance record
     */
    public function edit(GovernmentAssistance $governmentAssistance)
    {
        $residents = Resident::approved()->active()->orderBy('last_name')->get();
        return view('government-assistance.edit', compact('governmentAssistance', 'residents'));
    }

    /**
     * Update the specified assistance record
     */
    public function update(Request $request, GovernmentAssistance $governmentAssistance)
    {
        $validated = $request->validate([
            'program_name' => 'required|string|max:255',
            'program_type' => 'required|in:4ps,sss,philhealth,ayuda,scholarship,livelihood,housing,other',
            'amount' => 'nullable|numeric|min:0',
            'date_received' => 'required|date',
            'status' => 'required|in:active,completed,cancelled',
            'description' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $governmentAssistance->update($validated);

        return redirect()->route('government-assistance.show', $governmentAssistance)
                        ->with('success', 'Assistance record updated successfully!');
    }

    /**
     * Remove the specified assistance record
     */
    public function destroy(GovernmentAssistance $governmentAssistance)
    {
        $governmentAssistance->delete();

        return redirect()->route('government-assistance.index')
                        ->with('success', 'Assistance record deleted successfully!');
    }
}
