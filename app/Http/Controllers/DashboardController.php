<?php

namespace App\Http\Controllers;

use App\Models\Resident;
use App\Models\Household;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display dashboard
     */
    public function index()
    {
        $stats = [
            'total_residents' => Resident::approved()->count(),
            'total_households' => Household::approved()->count(),
            'total_pwd' => Resident::approved()->active()->pwd()->count(),
            'total_senior_citizens' => Resident::approved()->active()->seniorCitizens()->count(),
            'total_teens' => Resident::approved()->active()->teens()->count(),
            'total_voters' => Resident::approved()->active()->voters()->count(),
            'total_4ps' => Resident::approved()->active()->where('is_4ps_beneficiary', true)->count(),
            'male_count' => Resident::approved()->active()->where('sex', 'male')->count(),
            'female_count' => Resident::approved()->active()->where('sex', 'female')->count(),
            'average_household_size' => Household::approved()->avg('total_members'),
            'total_household_income' => Resident::approved()->active()->sum('monthly_income'),
        ];

        // Recent residents
        $recentResidents = Resident::with('household')
            ->approved()
            ->latest()
            ->take(5)
            ->get();

        // Age distribution
        $ageDistribution = [
            'children' => Resident::approved()->active()->where('age', '<', 13)->count(),
            'teens' => Resident::approved()->active()->teens()->count(),
            'adults' => Resident::approved()->active()->whereBetween('age', [20, 59])->count(),
            'seniors' => Resident::approved()->active()->seniorCitizens()->count(),
        ];

        return view('dashboard', compact('stats', 'recentResidents', 'ageDistribution'));
    }
}
