<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Resident;
use App\Models\HealthRecord;
use App\Models\MaternalHealth;
use App\Models\ChildHealth;
use App\Models\SeniorHealth;
use App\Models\PwdSupport;
use App\Models\User;
use Carbon\Carbon;
use Faker\Factory as Faker;

class SimpleSampleDataSeeder extends Seeder
{
    protected $faker;
    
    public function run()
    {
        $this->faker = Faker::create('en_PH');
        $secretary = User::where('role', 'secretary')->first();
        
        if (!$secretary) {
            $this->command->error('No secretary user found. Please run UserSeeder first.');
            return;
        }

        // Get some residents for testing
        $residents = Resident::approved()->active()->take(20)->get();
        
        if ($residents->isEmpty()) {
            $this->command->error('No residents found! Please add residents first.');
            return;
        }

        $this->command->info('Updating residents with sample data...');
        
        // Update basic resident information
        foreach ($residents as $index => $resident) {
            $updates = [
                'contact_number' => '09' . $this->faker->numerify('#########'),
                'occupation' => $this->faker->randomElement(['Vendor', 'Driver', 'Teacher', 'Housewife', 'Student', 'Fisherman', 'Farmer', 'Construction Worker']),
                'civil_status' => $this->faker->randomElement(['Single', 'Married', 'Widowed', 'Separated']),
                'blood_type' => $this->faker->randomElement(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-', null]),
            ];
            
            if (\Schema::hasColumn('residents', 'household_id')) {
                // Use integer values for household_id
                $updates['household_id'] = ($index % 5) + 1;
            }
            
            $resident->update($updates);
        }
        
        $this->command->info('Sample data updated successfully!');
        $this->command->info('Updated ' . $residents->count() . ' residents with sample data.');
        
        // Create health records
        $this->createHealthRecords($residents, $secretary);
        
        // Create maternal health records (for female residents aged 15-45)
        $this->createMaternalHealthRecords($residents, $secretary);
        
        // Create child health records (for residents aged 0-12)
        $this->createChildHealthRecords($residents, $secretary);
        
        // Create senior health records (for residents 60+ years old)
        $this->createSeniorHealthRecords($residents, $secretary);
        
        // Create PWD support records
        $this->createPwdSupportRecords($residents, $secretary);
        
        $this->command->info('All health management data has been seeded successfully!');
    }
    
    private function createHealthRecords($residents, $secretary)
    {
        $this->command->info('Creating health records...');
        $count = 0;
        
        // Check if the bmi column exists in the health_records table
        $hasBmiColumn = \Schema::hasColumn('health_records', 'bmi');
        
        foreach ($residents as $resident) {
            $bloodType = $resident->blood_type ?? $this->getRandomBloodType();
            $weight = $this->faker->numberBetween(45, 100);
            $height = $this->faker->numberBetween(150, 190);
            $bmi = round($weight / (($height/100) * ($height/100)), 2);
            
            $recordData = [
                'blood_type' => $bloodType,
                'weight' => $weight,
                'height' => $height,
                'health_conditions' => $this->getRandomMedicalHistory(),
                'allergies' => implode(', ', $this->getRandomAllergies()),
                'medications' => is_array($this->getRandomMedications()) ? 
                    implode(', ', $this->getRandomMedications()) : $this->getRandomMedications(),
                'last_checkup' => Carbon::now()->subMonths(rand(1, 12)),
                'assigned_worker' => 'Nurse ' . $this->faker->lastName,
                'remarks' => $this->faker->optional(0.7)->sentence(),
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ];
            
            // Only include bmi in the data if the column exists
            if ($hasBmiColumn) {
                $recordData['bmi'] = $bmi;
            }
            
            try {
                $record = HealthRecord::updateOrCreate(
                    ['resident_id' => $resident->id],
                    $recordData
                );
                $count++;
            } catch (\Exception $e) {
                $this->command->error('Error creating health record for resident ' . $resident->id . ': ' . $e->getMessage());
                // Log the full error for debugging
                \Log::error('Health record creation error: ' . $e->getMessage());
                \Log::error($e->getTraceAsString());
                
                // If there's an error, try again without the bmi field
                if (str_contains($e->getMessage(), 'bmi')) {
                    unset($recordData['bmi']);
                    try {
                        $record = HealthRecord::updateOrCreate(
                            ['resident_id' => $resident->id],
                            $recordData
                        );
                        $count++;
                        $this->command->info('Successfully created health record without BMI for resident ' . $resident->id);
                    } catch (\Exception $e2) {
                        $this->command->error('Second attempt failed for resident ' . $resident->id . ': ' . $e2->getMessage());
                    }
                }
            }
        }
        
        $this->command->info('Created/Updated ' . $count . ' health records.');
    }
    
    private function createMaternalHealthRecords($residents, $secretary)
    {
        $this->command->info('Creating maternal health records...');
        $count = 0;
        
        foreach ($residents as $resident) {
            // Only create for females between 15-45 years old (simplified for demo)
            if ($resident->gender === 'Female' && $resident->age >= 15 && $resident->age <= 45 && $count < 5) {
                $lmp = Carbon::now()->subWeeks(rand(8, 36));
                $edc = $lmp->copy()->addMonths(9);
                
                MaternalHealth::create([
                    'resident_id' => $resident->id,
                    'lmp' => $lmp,
                    'edc' => $edc,
                    'gravida' => rand(1, 5),
                    'para' => rand(0, 4),
                    'tt_status' => $this->getRandomTTStatus(),
                    'nutritional_status' => $this->getRandomNutritionalStatus(),
                    'risk_level' => $this->getRandomRiskLevel(),
                    'created_by' => $secretary->id,
                ]);
                
                $count++;
            }
        }
        
        $this->command->info('Created ' . $count . ' maternal health records.');
    }
    
    private function createChildHealthRecords($residents, $secretary)
    {
        $this->command->info('Creating child health records...');
        $count = 0;
        
        foreach ($residents as $resident) {
            // Only create for children 0-12 years old (simplified for demo)
            if ($resident->age <= 12 && $count < 5) {
                try {
                    $birthDate = Carbon::now()->subYears(rand(0, 12))->subMonths(rand(0, 11));
                    $birthWeight = rand(25, 45) / 10; // 2.5kg to 4.5kg
                    $birthHeight = rand(45, 55); // cm
                    
                    ChildHealth::create([
                        'resident_id' => $resident->id,
                        'birth_date' => $birthDate,
                        'birth_weight' => $birthWeight,
                        'birth_height' => $birthHeight,
                        'blood_type' => $this->getRandomBloodType(),
                        'immunization_history' => $this->getRandomImmunizationRecord(),
                        'development_notes' => $this->faker->optional(0.7)->paragraph(),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    
                    $count++;
                } catch (\Exception $e) {
                    $this->command->error('Error creating child health record for resident ' . $resident->id . ': ' . $e->getMessage());
                }
            }
        }
        
        $this->command->info('Created ' . $count . ' child health records.');
    }
    
    private function createSeniorHealthRecords($residents, $secretary)
    {
        $this->command->info('Creating senior health records...');
        $count = 0;
        
        $pensionTypes = ['GSIS', 'SSS', 'Private', 'None'];
        $mobilityStatuses = ['independent', 'assisted', 'wheelchair', 'bedridden'];
        
        foreach ($residents as $resident) {
            // Only create for seniors 60+ years old
            if ($resident->age >= 60 && $count < 10) {
                try {
                    $healthConditions = [];
                    if ($this->faker->boolean(60)) $healthConditions[] = 'Hypertension';
                    if ($this->faker->boolean(40)) $healthConditions[] = 'Diabetes';
                    if ($this->faker->boolean(50)) $healthConditions[] = 'Arthritis';
                    if ($this->faker->boolean(20)) $healthConditions[] = 'Asthma';
                    if ($this->faker->boolean(30)) $healthConditions[] = 'Heart Disease';
                    
                    $medications = [];
                    if (!empty($healthConditions)) {
                        $medications = $this->faker->randomElements(
                            ['Lisinopril', 'Metformin', 'Amlodipine', 'Simvastatin', 'Losartan', 'Metoprolol', 'Atorvastatin', 'Omeprazole'],
                            $this->faker->numberBetween(1, 3)
                        );
                    }
                    
                    SeniorHealth::create([
                        'resident_id' => $resident->id,
                        'senior_id_number' => 'SN' . $this->faker->unique()->numberBetween(1000, 9999),
                        'pension_type' => $this->faker->randomElement($pensionTypes),
                        'pension_amount' => $this->faker->optional(0.7)->randomFloat(2, 1000, 10000),
                        'health_conditions' => !empty($healthConditions) ? implode(', ', $healthConditions) : 'No significant health conditions',
                        'medications' => !empty($medications) ? implode(', ', $medications) : 'None',
                        'mobility_status' => $this->faker->randomElement($mobilityStatuses),
                        'emergency_medical_info' => $this->faker->optional(0.8)->sentence(),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    
                    $count++;
                } catch (\Exception $e) {
                    $this->command->error('Error creating senior health record for resident ' . $resident->id . ': ' . $e->getMessage());
                }
            }
        }
        
        $this->command->info('Created ' . $count . ' senior health records.');
    }
    
    private function createPwdSupportRecords($residents, $secretary)
    {
        $this->command->info('Creating PWD support records...');
        $count = 0;
        
        $disabilityTypes = [
            'Visual Impairment', 'Hearing Impairment', 'Physical Disability',
            'Intellectual Disability', 'Learning Disability', 'Autism Spectrum Disorder',
            'Mental Health Condition', 'Chronic Illness'
        ];
        
        $assistiveDevices = [
            'Wheelchair', 'Crutches', 'Walker', 'Cane', 'Prosthetic Limb',
            'Hearing Aid', 'White Cane', 'Glasses', 'Orthopedic Shoes', 'Communication Board'
        ];
        
        $supportServices = [
            'Physical Therapy', 'Occupational Therapy', 'Speech Therapy', 
            'Special Education', 'Vocational Training', 'Counseling',
            'Home Care Services', 'Transportation Assistance'
        ];
        
        foreach ($residents as $resident) {
            // Only create for some residents (5-10% chance)
            if ($this->faker->boolean(7) && $count < 5) {
                try {
                    $devicesNeeded = $this->faker->randomElements(
                        $assistiveDevices, 
                        $this->faker->numberBetween(0, 3)
                    );
                    
                    $servicesReceived = $this->faker->randomElements(
                        $supportServices,
                        $this->faker->numberBetween(0, 2)
                    );
                    
                    PwdSupport::create([
                        'resident_id' => $resident->id,
                        'disability_type' => $this->faker->randomElement($disabilityTypes),
                        'disability_level' => $this->faker->randomElement(['mild', 'moderate', 'severe']),
                        'pwd_id_number' => 'PWD-' . date('Y') . '-' . str_pad($count + 1, 4, '0', STR_PAD_LEFT),
                        'pwd_id_expiry' => now()->addYears(3),
                        'assistive_devices_needed' => !empty($devicesNeeded) ? implode(', ', $devicesNeeded) : 'None',
                        'support_services_received' => !empty($servicesReceived) ? implode(', ', $servicesReceived) : 'None',
                        'caregiver_name' => $this->faker->optional(0.7)->name,
                        'caregiver_contact' => $this->faker->optional(0.7)->phoneNumber,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    
                    $count++;
                } catch (\Exception $e) {
                    $this->command->error('Error creating PWD support record for resident ' . $resident->id . ': ' . $e->getMessage());
                }
            }
        }
        
        $this->command->info('Created ' . $count . ' PWD support records.');
    }
    
    // Helper methods for generating random health data
    private function getRandomBloodType()
    {
        return $this->faker->randomElement(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-']);
    }
    
    private function getRandomAllergies()
    {
        $allergies = [
            'None',
            'Penicillin',
            'Peanuts',
            'Dust',
            'Pollen',
            'Seafood',
            'Eggs',
            'Dairy',
            'Soy',
            'Latex'
        ];
        
        return $this->faker->randomElements($allergies, $this->faker->numberBetween(0, 3));
    }
    
    private function getRandomMedicalHistory()
    {
        $conditions = [
            'Hypertension, controlled with medication',
            'Type 2 Diabetes, diet controlled',
            'Mild asthma, uses inhaler as needed',
            'No significant medical history',
            'Arthritis, manages with physical therapy',
            'Hyperacidity, on maintenance medication',
            'Allergic rhinitis, seasonal',
            'Migraine, occasional',
            'GERD, on PPI',
            'Hypercholesterolemia, on statin'
        ];
        
        return $this->faker->randomElement($conditions);
    }
    
    private function getRandomImmunizationRecord()
    {
        $immunizations = [
            'Fully immunized according to age',
            'BCG, HepB, OPV, Pentavalent, PCV, MMR, MCV',
            'Incomplete immunization',
            'Up to date with all vaccines',
            'Needs catch-up vaccination',
            'No immunization record available'
        ];
        
        return $this->faker->randomElement($immunizations);
    }
    
    private function getRandomTTStatus()
    {
        return $this->faker->randomElement([
            'TT1', 'TT2', 'TT3', 'TT4', 'TT5', 'TT Booster', 'Not vaccinated'
        ]);
    }
    
    private function getRandomNutritionalStatus()
    {
        return $this->faker->randomElement([
            'Normal', 'Underweight', 'Overweight', 'Obese', 'Severely Wasted', 'Wasted', 'Stunted'
        ]);
    }
    
    private function getRandomRiskLevel()
    {
        return $this->faker->randomElement([
            'Low', 'Moderate', 'High', 'Very High'
        ]);
    }
    
    private function getRandomDeliveryType()
    {
        return $this->faker->randomElement([
            'Normal Spontaneous Delivery', 'Cesarean Section', 'Assisted Vaginal Delivery'
        ]);
    }
    
    private function getRandomFeedingPractice()
    {
        return $this->faker->randomElement([
            'Exclusive Breastfeeding', 'Mixed Feeding', 'Formula Feeding', 'Weaning'
        ]);
    }
    
    private function getRandomImmunizationStatus()
    {
        return $this->faker->randomElement([
            'Complete for age', 'Incomplete', 'Not vaccinated', 'Unknown'
        ]);
    }
    
    private function getRandomChildNutritionalStatus()
    {
        return $this->faker->randomElement([
            'Normal', 'Underweight', 'Severely Underweight', 'Overweight', 'Wasted', 'Stunted'
        ]);
    }
    
    private function getRandomDisabilityCause()
    {
        return $this->faker->randomElement([
            'Congenital', 'Illness', 'Accident', 'Aging', 'Unknown'
        ]);
    }
    
    private function getRandomMobilityAid()
    {
        return $this->faker->randomElement([
            'Wheelchair', 'Cane', 'Walker', 'Crutches', 'Prosthesis', 'Hearing Aid', 'None'
        ]);
    }
    
    private function getRandomMedications()
    {
        $medications = [
            'Lisinopril 10mg once daily for hypertension',
            'Metformin 500mg twice daily for diabetes',
            'Amlodipine 5mg once daily for high blood pressure',
            'Simvastatin 20mg at bedtime for high cholesterol',
            'Losartan 50mg once daily for hypertension',
            'Metoprolol 25mg twice daily for high blood pressure',
            'Atorvastatin 20mg at bedtime for high cholesterol',
            'Omeprazole 20mg once daily for acid reflux',
            'Levothyroxine 50mcg once daily for hypothyroidism',
            'Albuterol inhaler as needed for asthma',
            'None',
        ];
        
        return $this->faker->randomElements($medications, $this->faker->numberBetween(0, 3));
    }
}
