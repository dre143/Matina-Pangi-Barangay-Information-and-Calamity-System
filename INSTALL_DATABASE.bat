@echo off
cls
echo ========================================
echo DATABASE INSTALLATION SCRIPT
echo Barangay Matina Pangi System
echo ========================================
echo.
echo This script will:
echo 1. Check database connection
echo 2. Create all database tables
echo 3. Create default user accounts
echo.
echo ========================================
echo.

echo IMPORTANT: Before running this script
echo.
echo 1. Make sure XAMPP MySQL is RUNNING
echo 2. Create database named 'pangi' in phpMyAdmin
echo    (Go to http://localhost/phpmyadmin, click New, name it 'pangi')
echo 3. Make sure .env file exists with correct database settings
echo.
pause
echo.

echo ========================================
echo Step 1: Checking Laravel installation...
echo ========================================
php -v
if errorlevel 1 (
    echo ERROR: PHP is not installed or not in PATH
    pause
    exit /b 1
)
echo.

echo ========================================
echo Step 2: Clearing config cache...
echo ========================================
php artisan config:clear
php artisan cache:clear
echo.

echo ========================================
echo Step 3: Running database migrations...
echo ========================================
echo This will create all tables...
echo.
php artisan migrate --force
if errorlevel 1 (
    echo.
    echo ========================================
    echo ERROR: Migration failed!
    echo ========================================
    echo.
    echo Possible reasons:
    echo 1. Database 'pangi' doesn't exist
    echo 2. MySQL is not running
    echo 3. Wrong database credentials in .env file
    echo.
    echo SOLUTION:
    echo 1. Open phpMyAdmin: http://localhost/phpmyadmin
    echo 2. Create database named 'pangi'
    echo 3. Run this script again
    echo.
    echo OR use the manual SQL file:
    echo - Open: ALL_TABLES_MANUAL.sql
    echo - Run it in phpMyAdmin
    echo.
    pause
    exit /b 1
)
echo.

echo ========================================
echo Step 4: Creating default users...
echo ========================================
php artisan db:seed --force
if errorlevel 1 (
    echo.
    echo Warning: Seeding failed, but tables are created.
    echo You can create users manually using:
    echo - ALL_TABLES_MANUAL.sql (includes user creation)
    echo.
)
echo.

echo ========================================
echo SUCCESS! Installation Complete!
echo ========================================
echo.
echo Database tables created successfully!
echo.
echo LOGIN CREDENTIALS:
echo.
echo Secretary Account (Full Access):
echo   Email: secretary@pangi.gov
echo   Password: password
echo.
echo Staff Account (Limited Access):
echo   Email: staff@pangi.gov
echo   Password: password
echo.
echo ========================================
echo.
echo Access your system at:
echo http://127.0.0.1:8000/login
echo.
echo Or if using XAMPP:
echo http://localhost/pangi/public/login
echo.
echo ========================================
echo.
echo IMPORTANT: Change default passwords after first login!
echo.
pause
