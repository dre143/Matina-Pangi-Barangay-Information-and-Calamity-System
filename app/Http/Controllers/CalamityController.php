<?php

namespace App\Http\Controllers;

use App\Models\Calamity;
use App\Models\CalamityAffectedHousehold;
use App\Models\Household;
use Illuminate\Http\Request;

class CalamityController extends Controller
{
    /**
     * Display a listing of calamities
     */
    public function index()
    {
        $calamities = Calamity::withCount('affectedHouseholds')->latest()->paginate(20);
        return view('calamities.index', compact('calamities'));
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
        ]);

        $validated['reported_by'] = auth()->id();

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
        ]);

        $calamity->update($validated);

        return redirect()->route('calamities.show', $calamity)
                        ->with('success', 'Calamity record updated successfully!');
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
}
