<?php

namespace App\Http\Controllers\Calamity;

use App\Http\Controllers\Controller;
use App\Http\Resources\Calamity\EvacuationCenterResource;
use App\Models\EvacuationCenter;
use Illuminate\Http\Request;

class EvacuationCenterController extends Controller
{
    public function index()
    {
        return EvacuationCenterResource::collection(EvacuationCenter::latest()->paginate(20));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'nullable|string',
            'capacity' => 'required|integer|min:0',
            'current_occupancy' => 'nullable|integer|min:0',
            'facilities' => 'nullable|array'
        ]);
        $center = EvacuationCenter::create($data);
        if ($request->expectsJson()) {
            return new EvacuationCenterResource($center);
        }
        return redirect()->route('web.evacuation-centers.index')
            ->with('success', 'Evacuation center added successfully');
    }

    public function show(EvacuationCenter $evacuation_center)
    {
        return new EvacuationCenterResource($evacuation_center);
    }

    public function update(Request $request, EvacuationCenter $evacuation_center)
    {
        $data = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'location' => 'nullable|string',
            'capacity' => 'nullable|integer|min:0',
            'current_occupancy' => 'nullable|integer|min:0',
            'facilities' => 'nullable|array'
        ]);
        $evacuation_center->update($data);
        if ($request->expectsJson()) {
            return new EvacuationCenterResource($evacuation_center);
        }
        return redirect()->route('web.evacuation-centers.index')
            ->with('success', 'Evacuation center updated successfully');
    }

    public function destroy(EvacuationCenter $evacuation_center)
    {
        $evacuation_center->delete();
        if (request()->expectsJson()) {
            return response()->json(null, 204);
        }
        return redirect()->route('web.evacuation-centers.index')
            ->with('success', 'Evacuation center deleted successfully');
    }
}
