<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "\n╔════════════════════════════════════════════════════════════════╗\n";
echo "║                    VERIFY DATABASE SETUP                       ║\n";
echo "╚════════════════════════════════════════════════════════════════╝\n\n";

try {
    // Check connection
    echo "Checking database connection...\n";
    $pdo = DB::connection()->getPdo();
    $dbName = DB::connection()->getDatabaseName();
    echo "✓ Connected to database: $dbName\n\n";

    // Check if users table exists
    echo "Checking if users table exists...\n";
    $users = DB::table('users')->count();
    echo "✓ Users table exists with $users users\n\n";

    if ($users > 0) {
        echo "Users in database:\n";
        $allUsers = DB::table('users')->select('id', 'name', 'email', 'role')->get();
        foreach ($allUsers as $user) {
            echo "  - {$user->name} ({$user->email}) - Role: {$user->role}\n";
        }
        echo "\n";
        
        echo "╔════════════════════════════════════════════════════════════════╗\n";
        echo "║                    SETUP SUCCESSFUL! ✓                         ║\n";
        echo "╚════════════════════════════════════════════════════════════════╝\n\n";
        
        echo "You can now login at: http://127.0.0.1:8000/login\n";
        echo "Email: secretary@pangi.gov\n";
        echo "Password: password\n\n";
    } else {
        echo "⚠ Users table exists but is empty!\n";
        echo "Run the CREATE_ALL_TABLES.sql file in phpMyAdmin\n\n";
    }

} catch (\Exception $e) {
    echo "\n✗ ERROR: " . $e->getMessage() . "\n\n";
    
    echo "Troubleshooting:\n";
    echo "1. Make sure database 'matina' exists in phpMyAdmin\n";
    echo "2. Make sure .env file has DB_DATABASE=matina\n";
    echo "3. Run: php artisan config:clear\n";
    echo "4. Run CREATE_ALL_TABLES.sql in phpMyAdmin\n\n";
}
