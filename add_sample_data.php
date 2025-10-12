<?php

// Quick script to add sample data
// Run with: php add_sample_data.php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Resident;
use App\Models\Household;
use App\Models\User;
use App\Models\SeniorHealth;
use App\Models\PwdSupport;
use App\Models\GovernmentAssistance;
use App\Models\Calamity;
use App\Models\CalamityAffectedHousehold;

echo "Adding sample data...\n\n";

$secretary = User::where('role', 'secretary')->first();
$residents = Resident::approved()->active()->get();

if ($residents->isEmpty()) {
    die("No residents found! Please add residents first.\n");
}

// 1. SENIOR HEALTH
echo "Adding Senior Health records...\n";
$seniors = Resident::approved()->active()
    ->whereRaw('TIMESTAMPDIFF(YEAR, birthdate, CURDATE()) >= 60')
    ->take(5)
    ->get();

$count = 0;
foreach ($seniors as $senior) {
    try {
        SeniorHealth::create([
            'resident_id' => $senior->id,
            'health_conditions' => ['Hypertension, Arthritis', 'Diabetes', 'Heart condition', 'Osteoporosis', 'Memory issues'][$count % 5],
            'medications' => ['Amlodipine 5mg', 'Insulin', 'Aspirin', 'Calcium supplements', 'Memory supplements'][$count % 5],
            'mobility_status' => ['independent', 'assisted', 'wheelchair', 'independent', 'assisted'][$count % 5],
            'last_checkup_date' => now()->subDays(rand(7, 90)),
            'notes' => $count % 2 == 0 ? 'Regular monitoring needed' : null,
        ]);
        $count++;
        echo "  ✓ Added senior health record for {$senior->full_name}\n";
    } catch (\Exception $e) {
        echo "  ✗ Error: {$e->getMessage()}\n";
    }
}
echo "Added {$count} senior health records\n\n";

// 2. PWD SUPPORT
echo "Adding PWD Support records...\n";
$disabilityTypes = ['visual', 'hearing', 'mobility', 'mental', 'psychosocial', 'multiple'];
$count = 0;

foreach ($residents->take(6) as $index => $resident) {
    try {
        $type = $disabilityTypes[$index % 6];
        PwdSupport::create([
            'resident_id' => $resident->id,
            'pwd_id_number' => 'PWD-2025-' . str_pad($index + 1, 5, '0', STR_PAD_LEFT),
            'disability_type' => $type,
            'disability_description' => ucfirst($type) . ' impairment',
            'date_registered' => now()->subMonths(rand(1, 12)),
            'assistance_received' => 'Monthly cash assistance, Free medicines',
            'medical_needs' => 'Regular therapy',
            'notes' => null,
        ]);
        $count++;
        echo "  ✓ Added PWD record for {$resident->full_name}\n";
    } catch (\Exception $e) {
        echo "  ✗ Error: {$e->getMessage()}\n";
    }
}
echo "Added {$count} PWD support records\n\n";

// 3. GOVERNMENT ASSISTANCE
echo "Adding Government Assistance records...\n";
$programs = [
    ['type' => '4ps', 'name' => 'Pantawid Pamilyang Pilipino Program', 'amount' => 1400],
    ['type' => 'sss', 'name' => 'SSS Pension', 'amount' => 3000],
    ['type' => 'philhealth', 'name' => 'PhilHealth Coverage', 'amount' => null],
    ['type' => 'ayuda', 'name' => 'COVID-19 Ayuda', 'amount' => 5000],
    ['type' => 'scholarship', 'name' => 'Educational Scholarship', 'amount' => 10000],
    ['type' => 'livelihood', 'name' => 'Livelihood Program', 'amount' => 15000],
];

$count = 0;
foreach ($residents->take(12) as $index => $resident) {
    try {
        $program = $programs[$index % 6];
        GovernmentAssistance::create([
            'resident_id' => $resident->id,
            'program_name' => $program['name'],
            'program_type' => $program['type'],
            'amount' => $program['amount'],
            'start_date' => now()->subDays(rand(1, 180)),
            'date_received' => now()->subDays(rand(1, 180)),
            'status' => ['active', 'completed', 'active'][$index % 3],
            'description' => 'Beneficiary of ' . $program['name'],
            'notes' => null,
        ]);
        $count++;
        echo "  ✓ Added assistance record for {$resident->full_name}\n";
    } catch (\Exception $e) {
        echo "  ✗ Error: {$e->getMessage()}\n";
    }
}
echo "Added {$count} government assistance records\n\n";

// 4. CALAMITIES
echo "Adding Calamity records...\n";
$calamities = [
    [
        'name' => 'Typhoon Odette',
        'type' => 'typhoon',
        'date' => now()->subMonths(6),
        'severity' => 'catastrophic',
        'areas' => 'Purok 1, Purok 2, Purok 3',
        'description' => 'Super typhoon with winds up to 195 km/h',
        'response' => 'Evacuation centers opened, relief goods distributed',
        'status' => 'resolved',
    ],
    [
        'name' => 'Flash Flood July 2024',
        'type' => 'flood',
        'date' => now()->subMonths(3),
        'severity' => 'severe',
        'areas' => 'Purok 1, Low-lying areas',
        'description' => 'Heavy rainfall caused flash flooding',
        'response' => 'Rescue operations, families evacuated',
        'status' => 'monitoring',
    ],
    [
        'name' => 'House Fire Purok 2',
        'type' => 'fire',
        'date' => now()->subMonths(1),
        'severity' => 'moderate',
        'areas' => 'Purok 2',
        'description' => 'Residential fire affected 3 houses',
        'response' => 'Fire department responded, temporary shelter provided',
        'status' => 'resolved',
    ],
];

$count = 0;
foreach ($calamities as $calamityData) {
    try {
        $calamity = Calamity::create([
            'calamity_name' => $calamityData['name'],
            'calamity_type' => $calamityData['type'],
            'date_occurred' => $calamityData['date'],
            'severity_level' => $calamityData['severity'],
            'affected_areas' => $calamityData['areas'],
            'description' => $calamityData['description'],
            'response_actions' => $calamityData['response'],
            'status' => $calamityData['status'],
            'reported_by' => $secretary->id,
        ]);
        
        // Add affected households
        $households = Household::approved()->take(rand(3, 5))->get();
        $damageLevels = ['minor', 'moderate', 'severe', 'total'];
        
        foreach ($households as $hIndex => $household) {
            CalamityAffectedHousehold::create([
                'calamity_id' => $calamity->id,
                'household_id' => $household->id,
                'damage_level' => $damageLevels[$hIndex % 4],
                'estimated_damage_cost' => rand(5000, 100000),
                'assistance_needed' => 'Roofing materials, Food supplies',
                'assistance_provided' => $hIndex % 2 == 0 ? 'Relief goods, Cash ₱5,000' : 'Pending',
                'notes' => null,
            ]);
        }
        
        $count++;
        echo "  ✓ Added calamity: {$calamityData['name']}\n";
    } catch (\Exception $e) {
        echo "  ✗ Error: {$e->getMessage()}\n";
    }
}
echo "Added {$count} calamity records\n\n";

echo "========================================\n";
echo "✓ SAMPLE DATA ADDED SUCCESSFULLY!\n";
echo "========================================\n";
echo "\nRefresh your browser to see the data!\n";
