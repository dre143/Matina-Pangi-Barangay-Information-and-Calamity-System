@echo off
cls
color 0A
echo ╔════════════════════════════════════════════════════════════════╗
echo ║     BARANGAY MATINA PANGI - COMPLETE SETUP WIZARD             ║
echo ╚════════════════════════════════════════════════════════════════╝
echo.
echo This wizard will set up your entire system automatically.
echo.
echo ════════════════════════════════════════════════════════════════
echo.

:check_xampp
echo [1/6] Checking XAMPP MySQL...
echo.
echo Please make sure:
echo  - XAMPP Control Panel is open
echo  - MySQL is STARTED (green light)
echo.
echo Is MySQL running? (Y/N)
set /p mysql_running=
if /i "%mysql_running%" NEQ "Y" (
    echo.
    echo Please start MySQL in XAMPP Control Panel first!
    pause
    exit /b 1
)
echo ✓ MySQL is running
echo.

:create_database
echo ════════════════════════════════════════════════════════════════
echo [2/6] Database Setup
echo ════════════════════════════════════════════════════════════════
echo.
echo Opening phpMyAdmin in your browser...
echo Please create a database named: pangi
echo.
echo Steps:
echo  1. Click "New" in the left sidebar
echo  2. Database name: pangi
echo  3. Collation: utf8mb4_unicode_ci
echo  4. Click "Create"
echo.
start http://localhost/phpmyadmin
echo.
echo Have you created the 'pangi' database? (Y/N)
set /p db_created=
if /i "%db_created%" NEQ "Y" (
    echo.
    echo Please create the database first!
    pause
    exit /b 1
)
echo ✓ Database created
echo.

:setup_env
echo ════════════════════════════════════════════════════════════════
echo [3/6] Setting up environment file...
echo ════════════════════════════════════════════════════════════════
echo.
if exist ".env" (
    echo .env file already exists
    echo Do you want to overwrite it? (Y/N)
    set /p overwrite_env=
    if /i "%overwrite_env%" EQU "Y" (
        copy /Y ".env.pangi" ".env" >nul
        echo ✓ .env file updated
    ) else (
        echo ✓ Using existing .env file
    )
) else (
    copy ".env.pangi" ".env" >nul
    echo ✓ .env file created
)
echo.

:generate_key
echo ════════════════════════════════════════════════════════════════
echo [4/6] Generating application key...
echo ════════════════════════════════════════════════════════════════
echo.
php artisan key:generate --force
if errorlevel 1 (
    echo ✗ Failed to generate key
    echo Make sure PHP is installed and in your PATH
    pause
    exit /b 1
)
echo ✓ Application key generated
echo.

:run_migrations
echo ════════════════════════════════════════════════════════════════
echo [5/6] Creating database tables...
echo ════════════════════════════════════════════════════════════════
echo.
echo This may take a minute...
echo.
php artisan migrate --force
if errorlevel 1 (
    echo.
    echo ✗ Migration failed!
    echo.
    echo This usually means:
    echo  - Database 'pangi' doesn't exist
    echo  - MySQL is not running
    echo  - Wrong credentials in .env file
    echo.
    echo MANUAL FIX:
    echo  1. Make sure database 'pangi' exists in phpMyAdmin
    echo  2. Check .env file has correct database settings
    echo  3. Run this script again
    echo.
    pause
    exit /b 1
)
echo ✓ All tables created successfully
echo.

:seed_users
echo ════════════════════════════════════════════════════════════════
echo [6/6] Creating default user accounts...
echo ════════════════════════════════════════════════════════════════
echo.
php artisan db:seed --force
if errorlevel 1 (
    echo ✗ Failed to create users
    echo You can create them manually later
) else (
    echo ✓ User accounts created
)
echo.

:success
cls
color 0A
echo ╔════════════════════════════════════════════════════════════════╗
echo ║                    SETUP COMPLETE! ✓                           ║
echo ╚════════════════════════════════════════════════════════════════╝
echo.
echo Your Barangay Matina Pangi system is ready to use!
echo.
echo ════════════════════════════════════════════════════════════════
echo                      LOGIN CREDENTIALS
echo ════════════════════════════════════════════════════════════════
echo.
echo SECRETARY ACCOUNT (Full Access):
echo   Email:    secretary@pangi.gov
echo   Password: password
echo.
echo STAFF ACCOUNT (Limited Access):
echo   Email:    staff@pangi.gov
echo   Password: password
echo.
echo ════════════════════════════════════════════════════════════════
echo                      ACCESS YOUR SYSTEM
echo ════════════════════════════════════════════════════════════════
echo.
echo Option 1: Using PHP Built-in Server
echo   Run: php artisan serve
echo   Then go to: http://127.0.0.1:8000/login
echo.
echo Option 2: Using XAMPP
echo   Go to: http://localhost/pangi/public/login
echo.
echo ════════════════════════════════════════════════════════════════
echo.
echo IMPORTANT NOTES:
echo  • Change default passwords after first login!
echo  • Read APPROVAL_SYSTEM_GUIDE.md to understand the system
echo  • Secretary can approve/reject staff registrations
echo  • All documentation is in the project folder
echo.
echo ════════════════════════════════════════════════════════════════
echo.
echo Would you like to start the development server now? (Y/N)
set /p start_server=
if /i "%start_server%" EQU "Y" (
    echo.
    echo Starting server...
    echo Press Ctrl+C to stop the server
    echo.
    php artisan serve
) else (
    echo.
    echo Setup complete! You can start the server anytime with:
    echo php artisan serve
    echo.
)

pause
