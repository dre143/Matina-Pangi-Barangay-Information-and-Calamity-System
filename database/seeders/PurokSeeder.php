<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Purok;

class PurokSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $puroks = [
            [
                'purok_name' => 'Purok 1',
                'purok_code' => 'P1',
                'purok_leader_name' => 'Roberto Martinez',
                'purok_leader_contact' => '09171234567',
                'description' => 'Located at the northern part of the barangay',
                'boundaries' => 'North: National Highway, South: Purok 2, East: River, West: Mountain',
                'total_households' => 0,
                'total_population' => 0,
            ],
            [
                'purok_name' => 'Purok 2',
                'purok_code' => 'P2',
                'purok_leader_name' => 'Elena Reyes',
                'purok_leader_contact' => '09181234567',
                'description' => 'Central area near the barangay hall',
                'boundaries' => 'North: Purok 1, South: Purok 3, East: River, West: Mountain',
                'total_households' => 0,
                'total_population' => 0,
            ],
            [
                'purok_name' => 'Purok 3',
                'purok_code' => 'P3',
                'purok_leader_name' => 'Carlos Villanueva',
                'purok_leader_contact' => '09191234567',
                'description' => 'Near the elementary school',
                'boundaries' => 'North: Purok 2, South: Purok 4, East: River, West: Mountain',
                'total_households' => 0,
                'total_population' => 0,
            ],
            [
                'purok_name' => 'Purok 4',
                'purok_code' => 'P4',
                'purok_leader_name' => 'Luisa Garcia',
                'purok_leader_contact' => '09201234567',
                'description' => 'Residential area with many young families',
                'boundaries' => 'North: Purok 3, South: Purok 5, East: River, West: Mountain',
                'total_households' => 0,
                'total_population' => 0,
            ],
            [
                'purok_name' => 'Purok 5',
                'purok_code' => 'P5',
                'purok_leader_name' => 'Fernando Santos',
                'purok_leader_contact' => '09211234567',
                'description' => 'Agricultural area with rice fields',
                'boundaries' => 'North: Purok 4, South: Purok 6, East: River, West: Mountain',
                'total_households' => 0,
                'total_population' => 0,
            ],
            [
                'purok_name' => 'Purok 6',
                'purok_code' => 'P6',
                'purok_leader_name' => 'Rosa Mendoza',
                'purok_leader_contact' => '09221234567',
                'description' => 'Near the basketball court and chapel',
                'boundaries' => 'North: Purok 5, South: Purok 7, East: River, West: Mountain',
                'total_households' => 0,
                'total_population' => 0,
            ],
            [
                'purok_name' => 'Purok 7',
                'purok_code' => 'P7',
                'purok_leader_name' => 'Antonio Cruz',
                'purok_leader_contact' => '09231234567',
                'description' => 'Hillside area with scenic views',
                'boundaries' => 'North: Purok 6, South: Purok 8, East: River, West: Mountain',
                'total_households' => 0,
                'total_population' => 0,
            ],
            [
                'purok_name' => 'Purok 8',
                'purok_code' => 'P8',
                'purok_leader_name' => 'Gloria Ramos',
                'purok_leader_contact' => '09241234567',
                'description' => 'Near the health center',
                'boundaries' => 'North: Purok 7, South: Purok 9, East: River, West: Mountain',
                'total_households' => 0,
                'total_population' => 0,
            ],
            [
                'purok_name' => 'Purok 9',
                'purok_code' => 'P9',
                'purok_leader_name' => 'Miguel Torres',
                'purok_leader_contact' => '09251234567',
                'description' => 'Commercial area with small businesses',
                'boundaries' => 'North: Purok 8, South: Purok 10, East: River, West: Mountain',
                'total_households' => 0,
                'total_population' => 0,
            ],
            [
                'purok_name' => 'Purok 10',
                'purok_code' => 'P10',
                'purok_leader_name' => 'Carmen Flores',
                'purok_leader_contact' => '09261234567',
                'description' => 'Southern boundary near the neighboring barangay',
                'boundaries' => 'North: Purok 9, South: Barangay Boundary, East: River, West: Mountain',
                'total_households' => 0,
                'total_population' => 0,
            ],
        ];

        foreach ($puroks as $purok) {
            Purok::firstOrCreate(
                ['purok_code' => $purok['purok_code']],
                $purok
            );
        }

        $this->command->info('✓ 10 Puroks seeded successfully!');
    }
}
