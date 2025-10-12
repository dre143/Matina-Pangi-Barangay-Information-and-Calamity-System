<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->command->info('');
        $this->command->info('╔════════════════════════════════════════════════════════╗');
        $this->command->info('║   BARANGAY MATINA PANGI INFORMATION SYSTEM SEEDER    ║');
        $this->command->info('╚════════════════════════════════════════════════════════╝');
        $this->command->info('');

        $this->command->info('🌱 Starting database seeding...');
        $this->command->info('');

        // Seed in proper order to maintain referential integrity
        $this->call([
            UserSeeder::class,
            PurokSeeder::class,
            HouseholdAndResidentSeeder::class,
        ]);

        $this->command->info('');
        $this->command->info('╔════════════════════════════════════════════════════════╗');
        $this->command->info('║              SEEDING COMPLETED SUCCESSFULLY!          ║');
        $this->command->info('╚════════════════════════════════════════════════════════╝');
        $this->command->info('');
        $this->command->info('📊 DATABASE SUMMARY:');
        $this->command->info('   • Users: ' . \App\Models\User::count());
        $this->command->info('   • Puroks: ' . \App\Models\Purok::count());
        $this->command->info('   • Households: ' . \App\Models\Household::count());
        $this->command->info('   • Sub-Families: ' . \App\Models\SubFamily::count());
        $this->command->info('   • Residents: ' . \App\Models\Resident::count());
        $this->command->info('');
        $this->command->info('👥 RESIDENT BREAKDOWN:');
        $this->command->info('   • Senior Citizens (60+): ' . \App\Models\Resident::where('is_senior_citizen', true)->count());
        $this->command->info('   • PWD: ' . \App\Models\Resident::where('is_pwd', true)->count());
        $this->command->info('   • Teens (13-19): ' . \App\Models\Resident::where('is_teen', true)->count());
        $this->command->info('   • Voters: ' . \App\Models\Resident::where('is_voter', true)->count());
        $this->command->info('   • 4Ps Beneficiaries: ' . \App\Models\Resident::where('is_4ps_beneficiary', true)->count());
        $this->command->info('');
        $this->command->info('🔐 LOGIN CREDENTIALS:');
        $this->command->info('   Secretary: secretary@pangi.gov / password');
        $this->command->info('   Staff 1: maria.santos@pangi.gov / password');
        $this->command->info('   Staff 2: juan.delacruz@pangi.gov / password');
        $this->command->info('');
        $this->command->info('🚀 You can now test the system at: http://127.0.0.1:8000');
        $this->command->info('');
    }
}
