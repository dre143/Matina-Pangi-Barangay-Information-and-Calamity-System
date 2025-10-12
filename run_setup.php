<?php

/**
 * Direct Database Setup Script
 * This will create all tables and users directly
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

echo "\n╔════════════════════════════════════════════════════════════════╗\n";
echo "║          DIRECT DATABASE SETUP - CREATING TABLES               ║\n";
echo "╚════════════════════════════════════════════════════════════════╝\n\n";

try {
    // Test database connection
    echo "Testing database connection...\n";
    DB::connection()->getPdo();
    echo "✓ Database connected successfully!\n\n";

    // Drop existing tables if they exist (in correct order due to foreign keys)
    echo "Dropping existing tables (if any)...\n";
    Schema::dropIfExists('audit_logs');
    Schema::dropIfExists('residents');
    Schema::dropIfExists('households');
    Schema::dropIfExists('password_reset_tokens');
    Schema::dropIfExists('users');
    Schema::dropIfExists('puroks');
    Schema::dropIfExists('migrations');
    echo "✓ Old tables dropped\n\n";

    // Create migrations table
    echo "Creating migrations table...\n";
    DB::statement("CREATE TABLE `migrations` (
        `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
        `migration` varchar(255) NOT NULL,
        `batch` int(11) NOT NULL,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    echo "✓ Migrations table created\n\n";

    // Create users table
    echo "Creating users table...\n";
    DB::statement("CREATE TABLE `users` (
        `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
        `name` varchar(255) NOT NULL,
        `email` varchar(255) NOT NULL,
        `email_verified_at` timestamp NULL DEFAULT NULL,
        `password` varchar(255) NOT NULL,
        `role` enum('secretary','staff') NOT NULL DEFAULT 'staff',
        `remember_token` varchar(100) DEFAULT NULL,
        `created_at` timestamp NULL DEFAULT NULL,
        `updated_at` timestamp NULL DEFAULT NULL,
        PRIMARY KEY (`id`),
        UNIQUE KEY `users_email_unique` (`email`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    echo "✓ Users table created\n\n";

    // Create password_reset_tokens table
    echo "Creating password_reset_tokens table...\n";
    DB::statement("CREATE TABLE `password_reset_tokens` (
        `email` varchar(255) NOT NULL,
        `token` varchar(255) NOT NULL,
        `created_at` timestamp NULL DEFAULT NULL,
        PRIMARY KEY (`email`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    echo "✓ Password reset tokens table created\n\n";

    // Create households table
    echo "Creating households table...\n";
    DB::statement("CREATE TABLE `households` (
        `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
        `household_id` varchar(255) NOT NULL,
        `address` varchar(255) NOT NULL,
        `purok` varchar(255) DEFAULT NULL,
        `housing_type` enum('owned','rented','rent-free') NOT NULL DEFAULT 'owned',
        `has_electricity` tinyint(1) NOT NULL DEFAULT 1,
        `electric_account_number` varchar(255) DEFAULT NULL,
        `total_members` int(11) NOT NULL DEFAULT 1,
        `household_type` enum('solo','family','extended') NOT NULL DEFAULT 'family',
        `parent_household_id` bigint(20) UNSIGNED DEFAULT NULL,
        `approval_status` enum('pending','approved','rejected') NOT NULL DEFAULT 'approved',
        `approved_at` timestamp NULL DEFAULT NULL,
        `approved_by` bigint(20) UNSIGNED DEFAULT NULL,
        `rejection_reason` text DEFAULT NULL,
        `created_at` timestamp NULL DEFAULT NULL,
        `updated_at` timestamp NULL DEFAULT NULL,
        `deleted_at` timestamp NULL DEFAULT NULL,
        PRIMARY KEY (`id`),
        UNIQUE KEY `households_household_id_unique` (`household_id`),
        KEY `households_parent_household_id_foreign` (`parent_household_id`),
        KEY `households_approved_by_foreign` (`approved_by`),
        CONSTRAINT `households_parent_household_id_foreign` FOREIGN KEY (`parent_household_id`) REFERENCES `households` (`id`) ON DELETE SET NULL,
        CONSTRAINT `households_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    echo "✓ Households table created\n\n";

    // Create residents table
    echo "Creating residents table...\n";
    DB::statement("CREATE TABLE `residents` (
        `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
        `resident_id` varchar(255) NOT NULL,
        `household_id` bigint(20) UNSIGNED NOT NULL,
        `first_name` varchar(255) NOT NULL,
        `middle_name` varchar(255) DEFAULT NULL,
        `last_name` varchar(255) NOT NULL,
        `suffix` varchar(255) DEFAULT NULL,
        `birthdate` date NOT NULL,
        `age` int(11) NOT NULL,
        `sex` enum('male','female') NOT NULL,
        `civil_status` enum('single','married','widowed','separated','divorced') NOT NULL DEFAULT 'single',
        `place_of_birth` varchar(255) DEFAULT NULL,
        `nationality` varchar(255) NOT NULL DEFAULT 'Filipino',
        `religion` varchar(255) DEFAULT NULL,
        `contact_number` varchar(255) DEFAULT NULL,
        `email` varchar(255) DEFAULT NULL,
        `household_role` enum('head','spouse','child','parent','sibling','relative','other') NOT NULL DEFAULT 'head',
        `is_household_head` tinyint(1) NOT NULL DEFAULT 0,
        `status` enum('active','reallocated','deceased') NOT NULL DEFAULT 'active',
        `approval_status` enum('pending','approved','rejected') NOT NULL DEFAULT 'approved',
        `approved_at` timestamp NULL DEFAULT NULL,
        `approved_by` bigint(20) UNSIGNED DEFAULT NULL,
        `rejection_reason` text DEFAULT NULL,
        `status_notes` text DEFAULT NULL,
        `status_changed_at` timestamp NULL DEFAULT NULL,
        `status_changed_by` bigint(20) UNSIGNED DEFAULT NULL,
        `is_pwd` tinyint(1) NOT NULL DEFAULT 0,
        `pwd_id` varchar(255) DEFAULT NULL,
        `disability_type` varchar(255) DEFAULT NULL,
        `is_senior_citizen` tinyint(1) NOT NULL DEFAULT 0,
        `senior_id` varchar(255) DEFAULT NULL,
        `is_teen` tinyint(1) NOT NULL DEFAULT 0,
        `is_voter` tinyint(1) NOT NULL DEFAULT 0,
        `precinct_number` varchar(255) DEFAULT NULL,
        `is_4ps_beneficiary` tinyint(1) NOT NULL DEFAULT 0,
        `4ps_id` varchar(255) DEFAULT NULL,
        `occupation` varchar(255) DEFAULT NULL,
        `employment_status` enum('employed','unemployed','self-employed','student','retired') DEFAULT NULL,
        `employer_name` varchar(255) DEFAULT NULL,
        `monthly_income` decimal(10,2) DEFAULT NULL,
        `educational_attainment` enum('no formal education','elementary level','elementary graduate','high school level','high school graduate','college level','college graduate','vocational','post graduate') DEFAULT NULL,
        `blood_type` varchar(255) DEFAULT NULL,
        `medical_conditions` text DEFAULT NULL,
        `remarks` text DEFAULT NULL,
        `created_by` bigint(20) UNSIGNED DEFAULT NULL,
        `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
        `created_at` timestamp NULL DEFAULT NULL,
        `updated_at` timestamp NULL DEFAULT NULL,
        `deleted_at` timestamp NULL DEFAULT NULL,
        PRIMARY KEY (`id`),
        UNIQUE KEY `residents_resident_id_unique` (`resident_id`),
        KEY `residents_household_id_foreign` (`household_id`),
        KEY `residents_created_by_foreign` (`created_by`),
        KEY `residents_updated_by_foreign` (`updated_by`),
        KEY `residents_approved_by_foreign` (`approved_by`),
        KEY `residents_status_changed_by_foreign` (`status_changed_by`),
        CONSTRAINT `residents_household_id_foreign` FOREIGN KEY (`household_id`) REFERENCES `households` (`id`) ON DELETE CASCADE,
        CONSTRAINT `residents_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
        CONSTRAINT `residents_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
        CONSTRAINT `residents_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
        CONSTRAINT `residents_status_changed_by_foreign` FOREIGN KEY (`status_changed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    echo "✓ Residents table created\n\n";

    // Create audit_logs table
    echo "Creating audit_logs table...\n";
    DB::statement("CREATE TABLE `audit_logs` (
        `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
        `user_id` bigint(20) UNSIGNED DEFAULT NULL,
        `action` varchar(255) NOT NULL,
        `model_type` varchar(255) NOT NULL,
        `model_id` bigint(20) UNSIGNED DEFAULT NULL,
        `description` text NOT NULL,
        `old_values` json DEFAULT NULL,
        `new_values` json DEFAULT NULL,
        `ip_address` varchar(255) DEFAULT NULL,
        `created_at` timestamp NULL DEFAULT NULL,
        `updated_at` timestamp NULL DEFAULT NULL,
        PRIMARY KEY (`id`),
        KEY `audit_logs_user_id_foreign` (`user_id`),
        CONSTRAINT `audit_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    echo "✓ Audit logs table created\n\n";

    // Create puroks table
    echo "Creating puroks table...\n";
    DB::statement("CREATE TABLE `puroks` (
        `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
        `name` varchar(255) NOT NULL,
        `description` text DEFAULT NULL,
        `total_households` int(11) NOT NULL DEFAULT 0,
        `total_residents` int(11) NOT NULL DEFAULT 0,
        `created_at` timestamp NULL DEFAULT NULL,
        `updated_at` timestamp NULL DEFAULT NULL,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    echo "✓ Puroks table created\n\n";

    // Insert users
    echo "Creating user accounts...\n";
    DB::table('users')->insert([
        [
            'name' => 'Barangay Secretary',
            'email' => 'secretary@pangi.gov',
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
            'role' => 'secretary',
            'created_at' => now(),
            'updated_at' => now(),
        ],
        [
            'name' => 'Barangay Staff',
            'email' => 'staff@pangi.gov',
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
            'role' => 'staff',
            'created_at' => now(),
            'updated_at' => now(),
        ]
    ]);
    echo "✓ User accounts created\n\n";

    // Verify
    echo "Verifying setup...\n";
    $userCount = DB::table('users')->count();
    echo "✓ Users created: $userCount\n";

    $tables = DB::select('SHOW TABLES');
    echo "✓ Total tables created: " . count($tables) . "\n\n";

    echo "╔════════════════════════════════════════════════════════════════╗\n";
    echo "║                    SETUP COMPLETE! ✓                           ║\n";
    echo "╚════════════════════════════════════════════════════════════════╝\n\n";

    echo "LOGIN CREDENTIALS:\n\n";
    echo "Secretary Account (Full Access):\n";
    echo "  Email: secretary@pangi.gov\n";
    echo "  Password: password\n\n";

    echo "Staff Account (Limited Access):\n";
    echo "  Email: staff@pangi.gov\n";
    echo "  Password: password\n\n";

    echo "Access your system at: http://127.0.0.1:8000/login\n\n";

} catch (\Exception $e) {
    echo "\n✗ ERROR: " . $e->getMessage() . "\n\n";
    echo "Please make sure:\n";
    echo "1. MySQL is running in XAMPP\n";
    echo "2. Database 'pangi' exists\n";
    echo "3. Database credentials in .env are correct\n\n";
    echo "Or use the manual SQL file: CREATE_ALL_TABLES.sql\n\n";
}
