<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\Calamity;
use App\Models\ReliefItem;
use App\Models\ReliefDistribution;
use App\Models\EvacuationCenter;
use App\Models\DamageAssessment;
use App\Models\Notification;
use App\Models\ResponseTeamMember;
use App\Models\CalamityAffectedHousehold;
use App\Models\Household;
use App\Models\Resident;
use App\Models\Purok;
use App\Models\User;

class CalamityModuleSeeder extends Seeder
{
    public function run(): void
    {
        $secretary = User::first();
        if (!$secretary) {
            $secretary = User::factory()->create([
                'name' => 'Barangay Secretary',
                'email' => 'secretary@pangi.gov',
                'password' => bcrypt('password'),
            ]);
        }

        $purokNames = Purok::pluck('purok_name')->all();
        if (empty($purokNames)) {
            $purokNames = ['Purok 1','Purok 2','Purok 3'];
        }

        $centers = collect([
            ['name' => 'Matina Gym', 'location' => 'Matina Pangi', 'capacity' => 300],
            ['name' => 'Barangay Hall', 'location' => 'Matina Pangi', 'capacity' => 150],
            ['name' => 'Elementary School', 'location' => 'Purok 2', 'capacity' => 250],
        ])->map(function($d){ return EvacuationCenter::firstOrCreate(['name'=>$d['name']], $d); });

        $items = collect([
            ['name' => 'Rice (10kg)', 'quantity' => 200, 'unit' => 'sack'],
            ['name' => 'Canned Goods', 'quantity' => 500, 'unit' => 'box'],
            ['name' => 'Bottled Water', 'quantity' => 800, 'unit' => 'case'],
            ['name' => 'Blankets', 'quantity' => 150, 'unit' => 'piece'],
        ])->map(function($d){ return ReliefItem::firstOrCreate(['name'=>$d['name']], $d); });

        $calamities = [];
        $calamities[] = Calamity::create([
            'calamity_name' => 'Flooding in Purok 2',
            'calamity_type' => 'flood',
            'severity' => 'moderate',
            'date_occurred' => Carbon::now()->subDays(15)->toDateString(),
            'affected_puroks' => json_encode([$purokNames[1] ?? 'Purok 2']),
            'description' => 'Heavy rain caused river to overflow.',
        ]);
        $calamities[] = Calamity::create([
            'calamity_name' => 'Fire Incident at Purok 1',
            'calamity_type' => 'fire',
            'severity' => 'minor',
            'date_occurred' => Carbon::now()->subDays(30)->toDateString(),
            'affected_puroks' => json_encode([$purokNames[0] ?? 'Purok 1']),
            'description' => 'Small residential fire contained.',
        ]);

        $households = Household::inRandomOrder()->take(40)->get();
        if ($households->isEmpty()) {
            $households = collect();
        }

        foreach ($calamities as $calamity) {
            $selectedHouseholds = $households->shuffle()->take(20);
            foreach ($selectedHouseholds as $hh) {
                CalamityAffectedHousehold::create([
                    'calamity_id' => $calamity->id,
                    'household_id' => $hh->id,
                    'damage_level' => ['minor','moderate','severe'][rand(0,2)],
                    'needs' => 'Assessed during rapid survey.',
                ]);
            }

            DamageAssessment::create([
                'calamity_id' => $calamity->id,
                'assessed_by' => $secretary->id,
                'damage_level' => ['minor','moderate','severe'][rand(0,2)],
                'estimated_cost' => rand(50000,250000),
                'description' => 'Initial damage assessment prepared.',
            ]);

            Notification::create([
                'title' => 'Relief Operation',
                'message' => 'Relief distribution scheduled for affected households.',
                'calamity_id' => $calamity->id,
                'type' => 'system',
                'status' => 'sent',
                'sent_at' => now(),
            ]);

            $teamNames = ['Team Alpha','Team Bravo','Team Charlie'];
            foreach ($teamNames as $tn) {
                ResponseTeamMember::create([
                    'name' => $tn.' Member '.Str::random(4),
                    'role' => 'Volunteer',
                    'calamity_id' => $calamity->id,
                    'evacuation_center_id' => $centers->random()->id,
                ]);
            }

            $targets = $selectedHouseholds->take(8);
            foreach ($targets as $hh) {
                $item = $items->random();
                ReliefDistribution::create([
                    'calamity_id' => $calamity->id,
                    'household_id' => $hh->id,
                    'relief_item_id' => $item->id,
                    'quantity' => rand(1,3),
                    'staff_in_charge' => $secretary->id,
                    'distributed_at' => Carbon::now()->subDays(rand(1,7))->toDateTimeString(),
                ]);
            }
        }
    }
}
