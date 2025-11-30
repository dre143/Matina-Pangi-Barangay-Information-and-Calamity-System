<?php

namespace App\Http\Controllers\Calamity;

use App\Http\Controllers\Controller;
use App\Models\Calamity;
use App\Models\CalamityAffectedHousehold;
use App\Models\DamageAssessment;
use App\Models\ReliefDistribution;
use App\Models\RescueOperation;
use Barryvdh\DomPDF\Facade\Pdf;

class CalamityReportController extends Controller
{
    public function index()
    {
        $calamities = Calamity::latest('date_occurred')->paginate(15);
        return view('calamity.reports.index', compact('calamities'));
    }

    public function show(Calamity $calamity)
    {
        $data = $this->buildReportData($calamity);
        return view('calamity.reports.show', $data);
    }

    public function pdf(Calamity $calamity)
    {
        $data = $this->buildReportData($calamity);
        $data['exporter'] = auth()->user()->name ?? 'System';
        $data['barangayCaptain'] = config('app.barangay_captain') ?? env('BARANGAY_CAPTAIN_NAME', 'Barangay Captain');
        $pdf = Pdf::loadView('calamity.reports.pdf', $data);
        $filename = 'Calamity_Report_'.$calamity->id.'_'.$calamity->date_occurred?->format('Ymd').'.pdf';
        return $pdf->download($filename);
    }

    private function buildReportData(Calamity $calamity): array
    {
        $calamity->load(['affectedHouseholds.household', 'reporter']);

        $affectedHouseholds = $calamity->affectedHouseholds;
        $totalAffectedHouseholds = $affectedHouseholds->count();
        $totalAffectedResidents = $affectedHouseholds->sum(fn($ah) => $ah->household?->residents()->count() ?? 0);

        $evacuatedHouseholds = $affectedHouseholds->where('evacuation_status', 'evacuated');
        $totalFamiliesEvacuated = $evacuatedHouseholds->count();
        $totalEvacuees = $evacuatedHouseholds->sum(fn($ah) => $ah->household?->residents()->count() ?? 0);

        $damageAssessments = DamageAssessment::where('calamity_id', $calamity->id)->get();
        $partiallyDamaged = $affectedHouseholds->whereIn('damage_level', ['minor', 'moderate', 'severe'])->count();
        $totallyDamaged = $affectedHouseholds->where('damage_level', 'total')->count();
        $estimatedDamageCost = $damageAssessments->sum('estimated_cost');

        $reliefDistributions = ReliefDistribution::with(['item', 'household'])
            ->where('calamity_id', $calamity->id)
            ->get();
        $totalReliefDistributed = $reliefDistributions->sum('quantity');
        $reliefSummaryPerHousehold = $reliefDistributions->groupBy('household_id');

        $rescueOperations = RescueOperation::with(['affectedHousehold.household','rescuer','evacuationCenter'])
            ->whereHas('affectedHousehold', fn($q) => $q->where('calamity_id', $calamity->id))
            ->get();
        $totalRescues = $rescueOperations->count();
        $rescueSummaryByHousehold = $rescueOperations->groupBy('calamity_affected_household_id');
        $evacuationCenterOccupancy = $rescueOperations->whereNotNull('evacuation_center_id')
            ->groupBy('evacuation_center_id')
            ->map->count();

        return compact(
            'calamity',
            'affectedHouseholds',
            'totalAffectedHouseholds',
            'totalAffectedResidents',
            'totalFamiliesEvacuated',
            'totalEvacuees',
            'partiallyDamaged',
            'totallyDamaged',
            'estimatedDamageCost',
            'reliefSummaryPerHousehold',
            'totalReliefDistributed',
            'rescueOperations',
            'totalRescues',
            'rescueSummaryByHousehold',
            'evacuationCenterOccupancy'
        );
    }
}
