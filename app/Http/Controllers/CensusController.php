<?php

namespace App\Http\Controllers;

use App\Models\Resident;
use App\Models\Household;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CensusExport;
use App\Exports\ResidentsExport;
use App\Exports\HouseholdsExport;

class CensusController extends Controller
{
    /**
     * Display census dashboard
     */
    public function index()
    {
        $census = [
            // Population Statistics (All approved residents)
            'total_population' => Resident::approved()->count(),
            'total_households' => Household::approved()->count(),
            'average_household_size' => round(Household::approved()->avg('total_members'), 2),
            
            // Status Breakdown
            'active_residents' => Resident::approved()->active()->count(),
            'reallocated_residents' => Resident::approved()->reallocated()->count(),
            'deceased_residents' => Resident::approved()->deceased()->count(),
            
            // Gender Distribution (Active only)
            'male_count' => Resident::approved()->active()->where('sex', 'male')->count(),
            'female_count' => Resident::approved()->active()->where('sex', 'female')->count(),
            
            // Age Groups (Active only)
            'children_count' => Resident::approved()->active()->where('age', '<', 13)->count(),
            'teens_count' => Resident::approved()->active()->teens()->count(),
            'adults_count' => Resident::approved()->active()->whereBetween('age', [20, 59])->count(),
            'seniors_count' => Resident::approved()->active()->seniorCitizens()->count(),
            
            // Special Categories (Active only)
            'pwd_count' => Resident::approved()->active()->pwd()->count(),
            'senior_citizens_count' => Resident::approved()->active()->seniorCitizens()->count(),
            'voters_count' => Resident::approved()->active()->voters()->count(),
            'non_voters_count' => Resident::approved()->active()->where('is_voter', false)->count(),
            'fourps_count' => Resident::approved()->active()->where('is_4ps_beneficiary', true)->count(),
            
            // Civil Status (Active only)
            'single_count' => Resident::approved()->active()->where('civil_status', 'single')->count(),
            'married_count' => Resident::approved()->active()->where('civil_status', 'married')->count(),
            'widowed_count' => Resident::approved()->active()->where('civil_status', 'widowed')->count(),
            'separated_count' => Resident::approved()->active()->where('civil_status', 'separated')->count(),
            
            // Employment (Active only)
            'employed_count' => Resident::approved()->active()->where('employment_status', 'employed')->count(),
            'unemployed_count' => Resident::approved()->active()->where('employment_status', 'unemployed')->count(),
            'self_employed_count' => Resident::approved()->active()->where('employment_status', 'self-employed')->count(),
            'students_count' => Resident::approved()->active()->where('employment_status', 'student')->count(),
            'retired_count' => Resident::approved()->active()->where('employment_status', 'retired')->count(),
            
            // Housing (Approved only)
            'owned_houses' => Household::approved()->where('housing_type', 'owned')->count(),
            'rented_houses' => Household::approved()->where('housing_type', 'rented')->count(),
            'rent_free_houses' => Household::approved()->where('housing_type', 'rent-free')->count(),
            'with_electricity' => Household::approved()->where('has_electricity', true)->count(),
            'without_electricity' => Household::approved()->where('has_electricity', false)->count(),
            
            // Income (Active only)
            'total_income' => Resident::approved()->active()->sum('monthly_income'),
            'average_income' => round(Resident::approved()->active()->avg('monthly_income'), 2),
            
            // Household Types (Approved only)
            'solo_households' => Household::approved()->where('household_type', 'solo')->count(),
            'family_households' => Household::approved()->where('household_type', 'family')->count(),
            'extended_households' => Household::approved()->where('household_type', 'extended')->count(),
        ];

        // Age distribution for chart
        $ageDistribution = [
            'age_0_12' => Resident::approved()->active()->where('age', '<', 13)->count(),
            'age_13_19' => Resident::approved()->active()->whereBetween('age', [13, 19])->count(),
            'age_20_29' => Resident::approved()->active()->whereBetween('age', [20, 29])->count(),
            'age_30_39' => Resident::approved()->active()->whereBetween('age', [30, 39])->count(),
            'age_40_49' => Resident::approved()->active()->whereBetween('age', [40, 49])->count(),
            'age_50_59' => Resident::approved()->active()->whereBetween('age', [50, 59])->count(),
            'age_60_plus' => Resident::approved()->active()->where('age', '>=', 60)->count(),
        ];

        return view('census.index', compact('census', 'ageDistribution'));
    }

    /**
     * Export census snapshot to PDF
     */
    public function exportPdf()
    {
        // Only secretary can export
        if (!auth()->user()->isSecretary()) {
            abort(403, 'Unauthorized action.');
        }

        $census = $this->getCensusData();
        
        $pdf = Pdf::loadView('census.pdf', compact('census'));
        
        AuditLog::logAction(
            'export',
            'Census',
            null,
            'Exported census snapshot to PDF'
        );

        return $pdf->download('census-snapshot-' . date('Y-m-d') . '.pdf');
    }

    /**
     * Export census snapshot to Excel
     */
    public function exportExcel()
    {
        // Only secretary can export
        if (!auth()->user()->isSecretary()) {
            abort(403, 'Unauthorized action.');
        }

        AuditLog::logAction(
            'export',
            'Census',
            null,
            'Exported census snapshot to Excel'
        );

        return Excel::download(new CensusExport, 'census-snapshot-' . date('Y-m-d') . '.xlsx');
    }

    /**
     * Export residents list to PDF
     */
    public function exportResidentsPdf()
    {
        // Only secretary can export
        if (!auth()->user()->isSecretary()) {
            abort(403, 'Unauthorized action.');
        }

        $residents = Resident::with('household')->get();
        
        $pdf = Pdf::loadView('residents.pdf', compact('residents'))
            ->setPaper('a4', 'landscape');
        
        AuditLog::logAction(
            'export',
            'Resident',
            null,
            'Exported residents list to PDF'
        );

        return $pdf->download('residents-list-' . date('Y-m-d') . '.pdf');
    }

    /**
     * Export residents list to Excel
     */
    public function exportResidentsExcel()
    {
        // Only secretary can export
        if (!auth()->user()->isSecretary()) {
            abort(403, 'Unauthorized action.');
        }

        AuditLog::logAction(
            'export',
            'Resident',
            null,
            'Exported residents list to Excel'
        );

        return Excel::download(new ResidentsExport, 'residents-list-' . date('Y-m-d') . '.xlsx');
    }

    /**
     * Export households list to PDF
     */
    public function exportHouseholdsPdf()
    {
        // Only secretary can export
        if (!auth()->user()->isSecretary()) {
            abort(403, 'Unauthorized action.');
        }

        $households = Household::with(['head', 'residents'])->get();
        
        $pdf = Pdf::loadView('households.pdf', compact('households'))
            ->setPaper('a4', 'landscape');
        
        AuditLog::logAction(
            'export',
            'Household',
            null,
            'Exported households list to PDF'
        );

        return $pdf->download('households-list-' . date('Y-m-d') . '.pdf');
    }

    /**
     * Export households list to Excel
     */
    public function exportHouseholdsExcel()
    {
        // Only secretary can export
        if (!auth()->user()->isSecretary()) {
            abort(403, 'Unauthorized action.');
        }

        AuditLog::logAction(
            'export',
            'Household',
            null,
            'Exported households list to Excel'
        );

        return Excel::download(new HouseholdsExport, 'households-list-' . date('Y-m-d') . '.xlsx');
    }

    /**
     * Get census data
     */
    private function getCensusData()
    {
        return [
            'total_population' => Resident::count(),
            'total_households' => Household::count(),
            'average_household_size' => round(Household::avg('total_members'), 2),
            'male_count' => Resident::where('sex', 'male')->count(),
            'female_count' => Resident::where('sex', 'female')->count(),
            'children_count' => Resident::where('age', '<', 13)->count(),
            'teens_count' => Resident::teens()->count(),
            'adults_count' => Resident::whereBetween('age', [20, 59])->count(),
            'seniors_count' => Resident::seniorCitizens()->count(),
            'pwd_count' => Resident::pwd()->count(),
            'voters_count' => Resident::voters()->count(),
            'non_voters_count' => Resident::where('is_voter', false)->count(),
            'fourps_count' => Resident::where('is_4ps_beneficiary', true)->count(),
            'single_count' => Resident::where('civil_status', 'single')->count(),
            'married_count' => Resident::where('civil_status', 'married')->count(),
            'widowed_count' => Resident::where('civil_status', 'widowed')->count(),
            'employed_count' => Resident::where('employment_status', 'employed')->count(),
            'unemployed_count' => Resident::where('employment_status', 'unemployed')->count(),
            'owned_houses' => Household::where('housing_type', 'owned')->count(),
            'rented_houses' => Household::where('housing_type', 'rented')->count(),
            'with_electricity' => Household::where('has_electricity', true)->count(),
            'total_income' => Resident::sum('monthly_income'),
            'average_income' => round(Resident::avg('monthly_income'), 2),
            'generated_at' => now()->format('F d, Y h:i A'),
            'generated_by' => auth()->user()->name,
        ];
    }
}
