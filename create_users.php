<?php

/**
 * Simple script to create default users
 * Run this file directly: php create_users.php
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

echo "\n=== Creating Default Users ===\n\n";

try {
    // Create Secretary Account
    $secretary = User::where('email', 'secretary@pangi.gov')->first();
    if (!$secretary) {
        User::create([
            'name' => 'Barangay Secretary',
            'email' => 'secretary@pangi.gov',
            'password' => Hash::make('password'),
            'role' => 'secretary',
        ]);
        echo "✓ Secretary account created\n";
    } else {
        echo "⚠ Secretary account already exists\n";
    }

    // Create Staff Account
    $staff = User::where('email', 'staff@pangi.gov')->first();
    if (!$staff) {
        User::create([
            'name' => 'Barangay Staff',
            'email' => 'staff@pangi.gov',
            'password' => Hash::make('password'),
            'role' => 'staff',
        ]);
        echo "✓ Staff account created\n";
    } else {
        echo "⚠ Staff account already exists\n";
    }

    echo "\n=== LOGIN CREDENTIALS ===\n";
    echo "Secretary Email: secretary@pangi.gov\n";
    echo "Secretary Password: password\n";
    echo "\n";
    echo "Staff Email: staff@pangi.gov\n";
    echo "Staff Password: password\n";
    echo "=========================\n\n";
    echo "✓ Done! You can now login at: http://localhost/pangi/public/login\n\n";

} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n\n";
    echo "Please make sure:\n";
    echo "1. XAMPP MySQL is running\n";
    echo "2. Database is created\n";
    echo "3. Migrations have been run (php artisan migrate)\n\n";
}
