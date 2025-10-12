<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Certificate;
use App\Models\HealthRecord;
use App\Models\SeniorHealth;
use App\Models\PwdSupport;
use App\Models\GovernmentAssistance;
use App\Models\Calamity;
use App\Models\CalamityAffectedHousehold;
use App\Models\Resident;
use App\Models\Household;
use App\Models\User;

class NewModulesSampleDataSeeder extends Seeder
{
    public function run()
    {
        $secretary = User::where('role', 'secretary')->first();
        
        // Get some residents for testing
        $residents = Resident::approved()->active()->take(20)->get();
        
        if ($residents->isEmpty()) {
            $this->command->error('No residents found! Please add residents first.');
            return;
        }

        $this->command->info('Creating sample data for new modules...');

        // 1. CERTIFICATES
        $this->command->info('Creating Certificates...');
        
        $certificateTypes = [
            'barangay_clearance' => 'Employment purposes',
            'certificate_of_indigency' => 'Medical assistance',
            'certificate_of_residency' => 'Bank requirements',
            'business_clearance' => 'Starting a sari-sari store',
            'good_moral' => 'School requirements',
            'travel_permit' => 'Traveling to Manila',
        ];

        $certIndex = 0;
        foreach ($certificateTypes as $type => $purpose) {
            if ($certIndex >= 10) break;
            
            $resident = $residents[$certIndex % $residents->count()];
            
            try {
                Certificate::create([
                    'resident_id' => $resident->id,
                    'certificate_type' => $type,
                    'purpose' => $purpose,
                    'or_number' => 'OR-2025-' . str_pad($certIndex + 1, 4, '0', STR_PAD_LEFT),
                    'amount_paid' => rand(0, 100),
                    'issued_by' => $secretary->id,
                    'issued_date' => now()->subDays(rand(1, 30)),
                    'valid_until' => now()->addMonths(6),
                    'status' => ['issued', 'claimed', 'issued'][$certIndex % 3],
                    'remarks' => $certIndex % 3 == 0 ? 'Urgent request' : null,
                ]);
                $certIndex++;
            } catch (\Exception $e) {
                $this->command->warn('Could not create certificate: ' . $e->getMessage());
            }
        }
        
        // Create more certificates with different types
        for ($i = $certIndex; $i < 10; $i++) {
            $types = array_keys($certificateTypes);
            $type = $types[$i % count($types)];
            $resident = $residents[$i % $residents->count()];
            
            try {
                Certificate::create([
                    'resident_id' => $resident->id,
                    'certificate_type' => $type,
                    'purpose' => $certificateTypes[$type],
                    'or_number' => 'OR-2025-' . str_pad($i + 1, 4, '0', STR_PAD_LEFT),
                    'amount_paid' => rand(0, 100),
                    'issued_by' => $secretary->id,
                    'issued_date' => now()->subDays(rand(1, 30)),
                    'valid_until' => now()->addMonths(6),
                    'status' => ['issued', 'claimed', 'issued'][$i % 3],
                    'remarks' => null,
                ]);
            } catch (\Exception $e) {
                // Skip if error
            }
        }
        $this->command->info('✓ Created 10 certificates');

        // 2. HEALTH RECORDS
        $this->command->info('Creating Health Records...');
        
        $bloodTypes = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];
        $conditions = [
            'Hypertension',
            'Diabetes Type 2',
            'Asthma',
            'None',
            'Arthritis',
            'Heart disease',
        ];

        foreach ($residents->take(12) as $index => $resident) {
            HealthRecord::create([
                'resident_id' => $resident->id,
                'blood_type' => $bloodTypes[$index % 8],
                'height' => rand(150, 180),
                'weight' => rand(50, 90),
                'medical_conditions' => $conditions[$index % 6],
                'allergies' => $index % 3 == 0 ? 'Penicillin, Seafood' : null,
                'medications' => $index % 2 == 0 ? 'Losartan 50mg, Metformin 500mg' : null,
                'emergency_contact' => 'Juan Dela Cruz',
                'emergency_contact_number' => '0912-345-' . str_pad($index, 4, '0', STR_PAD_LEFT),
                'philhealth_number' => '12-' . rand(100000000, 999999999) . '-' . rand(1, 9),
                'notes' => $index % 4 == 0 ? 'Regular checkup needed' : null,
            ]);
        }
        $this->command->info('✓ Created 12 health records');

        // 3. SENIOR HEALTH
        $this->command->info('Creating Senior Health Records...');
        
        // Get seniors (60+)
        $seniors = Resident::approved()->active()
            ->whereRaw('TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) >= 60')
            ->take(8)
            ->get();

        if ($seniors->isNotEmpty()) {
            $mobilityStatuses = ['independent', 'assisted', 'wheelchair', 'bedridden'];
            
            foreach ($seniors as $index => $senior) {
                SeniorHealth::create([
                    'resident_id' => $senior->id,
                    'health_conditions' => [
                        'Hypertension, Arthritis',
                        'Diabetes, Poor eyesight',
                        'Heart condition',
                        'Osteoporosis',
                        'Memory loss',
                    ][$index % 5],
                    'medications' => [
                        'Amlodipine 5mg, Glucosamine',
                        'Insulin, Metformin',
                        'Aspirin, Beta blockers',
                        'Calcium supplements',
                        'Memory supplements',
                    ][$index % 5],
                    'mobility_status' => $mobilityStatuses[$index % 4],
                    'last_checkup_date' => now()->subDays(rand(7, 90)),
                    'notes' => $index % 2 == 0 ? 'Needs regular monitoring' : null,
                ]);
            }
            $this->command->info('✓ Created ' . $seniors->count() . ' senior health records');
        } else {
            $this->command->warn('⚠ No senior citizens found (60+ years old)');
        }

        // 4. PWD SUPPORT
        $this->command->info('Creating PWD Support Records...');
        
        $disabilityTypes = ['visual', 'hearing', 'mobility', 'mental', 'psychosocial', 'multiple'];
        $descriptions = [
            'visual' => 'Partial blindness in left eye',
            'hearing' => 'Deaf in both ears',
            'mobility' => 'Difficulty walking, uses cane',
            'mental' => 'Intellectual disability',
            'psychosocial' => 'Bipolar disorder',
            'multiple' => 'Visual and mobility impairment',
        ];

        foreach ($residents->take(6) as $index => $resident) {
            $type = $disabilityTypes[$index % 6];
            PwdSupport::create([
                'resident_id' => $resident->id,
                'pwd_id_number' => 'PWD-2025-' . str_pad($index + 1, 5, '0', STR_PAD_LEFT),
                'disability_type' => $type,
                'disability_description' => $descriptions[$type],
                'date_registered' => now()->subMonths(rand(1, 24)),
                'assistance_received' => 'Monthly cash assistance, Free medicines',
                'medical_needs' => $index % 2 == 0 ? 'Wheelchair, Hearing aid' : 'Regular therapy sessions',
                'notes' => $index % 3 == 0 ? 'Requires home visit' : null,
            ]);
        }
        $this->command->info('✓ Created 6 PWD support records');

        // 5. GOVERNMENT ASSISTANCE
        $this->command->info('Creating Government Assistance Records...');
        
        $programs = [
            ['type' => '4ps', 'name' => 'Pantawid Pamilyang Pilipino Program', 'amount' => 1400],
            ['type' => 'sss', 'name' => 'SSS Pension', 'amount' => 3000],
            ['type' => 'philhealth', 'name' => 'PhilHealth Coverage', 'amount' => null],
            ['type' => 'ayuda', 'name' => 'COVID-19 Ayuda', 'amount' => 5000],
            ['type' => 'scholarship', 'name' => 'Educational Scholarship', 'amount' => 10000],
            ['type' => 'livelihood', 'name' => 'Livelihood Program', 'amount' => 15000],
            ['type' => 'housing', 'name' => 'Housing Assistance', 'amount' => 50000],
        ];

        foreach ($residents->take(14) as $index => $resident) {
            $program = $programs[$index % 7];
            GovernmentAssistance::create([
                'resident_id' => $resident->id,
                'program_name' => $program['name'],
                'program_type' => $program['type'],
                'amount' => $program['amount'],
                'date_received' => now()->subDays(rand(1, 180)),
                'status' => ['active', 'completed', 'active'][$index % 3],
                'description' => 'Beneficiary of ' . $program['name'],
                'notes' => $index % 4 == 0 ? 'Renewal needed next month' : null,
            ]);
        }
        $this->command->info('✓ Created 14 government assistance records');

        // 6. CALAMITIES
        $this->command->info('Creating Calamity Records...');
        
        $calamities = [
            [
                'name' => 'Typhoon Odette',
                'type' => 'typhoon',
                'date' => now()->subMonths(6),
                'severity' => 'catastrophic',
                'areas' => 'Purok 1, Purok 2, Purok 3',
                'description' => 'Super typhoon with winds up to 195 km/h. Caused widespread damage to houses and infrastructure.',
                'response' => 'Evacuation centers opened, relief goods distributed, temporary shelters provided.',
                'status' => 'resolved',
            ],
            [
                'name' => 'Flash Flood - July 2024',
                'type' => 'flood',
                'date' => now()->subMonths(3),
                'severity' => 'severe',
                'areas' => 'Purok 1, Low-lying areas',
                'description' => 'Heavy rainfall caused flash flooding in low-lying areas. Water reached up to 3 feet.',
                'response' => 'Rescue operations conducted, families evacuated, food packs distributed.',
                'status' => 'monitoring',
            ],
            [
                'name' => 'House Fire - Purok 2',
                'type' => 'fire',
                'date' => now()->subMonths(1),
                'severity' => 'moderate',
                'areas' => 'Purok 2',
                'description' => 'Residential fire affected 3 houses. No casualties reported.',
                'response' => 'Fire department responded, affected families given temporary shelter and assistance.',
                'status' => 'resolved',
            ],
        ];

        foreach ($calamities as $calamityData) {
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
            $households = Household::approved()->take(rand(3, 8))->get();
            $damageLevels = ['minor', 'moderate', 'severe', 'total'];
            
            foreach ($households as $hIndex => $household) {
                CalamityAffectedHousehold::create([
                    'calamity_id' => $calamity->id,
                    'household_id' => $household->id,
                    'damage_level' => $damageLevels[$hIndex % 4],
                    'estimated_damage_cost' => rand(5000, 100000),
                    'assistance_needed' => 'Roofing materials, Food supplies, Financial assistance',
                    'assistance_provided' => $hIndex % 2 == 0 ? 'Relief goods, Cash assistance ₱5,000' : 'Pending',
                    'notes' => $hIndex % 3 == 0 ? 'Priority case - elderly residents' : null,
                ]);
            }
        }
        $this->command->info('✓ Created 3 calamity records with affected households');

        $this->command->info('');
        $this->command->info('========================================');
        $this->command->info('✓ SAMPLE DATA CREATED SUCCESSFULLY!');
        $this->command->info('========================================');
        $this->command->info('');
        $this->command->info('Summary:');
        $this->command->info('- 10 Certificates');
        $this->command->info('- 12 Health Records');
        $this->command->info('- ' . ($seniors->count() ?? 0) . ' Senior Health Records');
        $this->command->info('- 6 PWD Support Records');
        $this->command->info('- 14 Government Assistance Records');
        $this->command->info('- 3 Calamity Records with affected households');
        $this->command->info('');
        $this->command->info('You can now explore all the new modules!');
    }
}
