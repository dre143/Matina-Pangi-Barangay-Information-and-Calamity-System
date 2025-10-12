@echo off
echo ========================================
echo Barangay Matina Pangi Setup Script
echo ========================================
echo.

echo Step 1: Generating application key...
php artisan key:generate
echo.

echo Step 2: Running database migrations...
php artisan migrate
echo.

echo Step 3: Creating default user accounts...
php artisan db:seed
echo.

echo ========================================
echo Setup Complete!
echo ========================================
echo.
echo Default Login Credentials:
echo.
echo Secretary Account:
echo   Email: secretary@pangi.gov
echo   Password: password
echo.
echo Staff Account:
echo   Email: staff@pangi.gov
echo   Password: password
echo.
echo ========================================
echo.
echo You can now access the system at:
echo http://localhost/pangi/public/login
echo.
echo Or start the development server with:
echo php artisan serve
echo.
pause
