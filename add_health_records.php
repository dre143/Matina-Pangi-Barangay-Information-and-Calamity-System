<?php

// Quick script to add health records sample data
// Run with: php add_health_records.php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Resident;
use App\Models\HealthRecord;
use App\Models\User;

echo "Adding Health Records sample data...\n\n";

$secretary = User::where('role', 'secretary')->first();
$residents = Resident::approved()->active()->take(15)->get();

if ($residents->isEmpty()) {
    die("No residents found! Please add residents first.\n");
}

$recordTypes = ['immunization', 'checkup', 'medication', 'condition'];
$vaccines = ['BCG', 'Hepatitis B', 'DPT', 'Polio', 'MMR', 'COVID-19'];
$conditions = ['Hypertension', 'Diabetes Type 2', 'Asthma', 'Arthritis', 'Heart disease'];
$medications = [
    'Amlodipine 5mg once daily',
    'Metformin 500mg twice daily',
    'Salbutamol inhaler as needed',
    'Paracetamol 500mg for pain',
    'Losartan 50mg once daily',
];

$count = 0;

foreach ($residents as $index => $resident) {
    $type = $recordTypes[$index % 4];
    
    try {
        $data = [
            'resident_id' => $resident->id,
            'record_type' => $type,
            'recorded_by' => $secretary->id,
        ];
        
        // Add type-specific data
        if ($type === 'immunization') {
            $data['vaccine_name'] = $vaccines[$index % count($vaccines)];
            $data['date_administered'] = now()->subDays(rand(30, 365));
            $data['doctor_notes'] = 'Vaccination completed successfully';
        } elseif ($type === 'checkup') {
            $data['doctor_notes'] = 'Regular health checkup. Patient is in good condition.';
            $data['next_checkup_date'] = now()->addMonths(rand(3, 6));
        } elseif ($type === 'medication') {
            $data['medication'] = $medications[$index % count($medications)];
            $data['doctor_notes'] = 'Prescribed medication for ongoing treatment';
        } else { // condition
            $data['health_condition'] = $conditions[$index % count($conditions)];
            $data['medication'] = $medications[$index % count($medications)];
            $data['doctor_notes'] = 'Patient diagnosed with condition. Regular monitoring required.';
            $data['next_checkup_date'] = now()->addMonths(1);
        }
        
        HealthRecord::create($data);
        $count++;
        echo "  ✓ Added {$type} record for {$resident->full_name}\n";
    } catch (\Exception $e) {
        echo "  ✗ Error: {$e->getMessage()}\n";
    }
}

echo "\n========================================\n";
echo "✓ Added {$count} health records!\n";
echo "========================================\n";
echo "\nRefresh your browser to see the data!\n";
