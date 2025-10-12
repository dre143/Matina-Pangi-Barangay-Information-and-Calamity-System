@echo off
echo ========================================
echo FIXING DATABASE - DROPPING CORRUPTED TABLES
echo ========================================
echo.

cd C:\xampp\mysql\bin

echo Dropping corrupted tables...
mysql -u root pangi < "C:\xampp\htdocs\pangi\DROP_TABLES_FIRST.sql"
if errorlevel 1 (
    echo Error dropping tables!
    pause
    exit /b 1
)
echo ✓ Tables dropped successfully!
echo.

echo ========================================
echo CREATING ALL TABLES
echo ========================================
echo.

mysql -u root pangi < "C:\xampp\htdocs\pangi\CREATE_ALL_TABLES.sql"
if errorlevel 1 (
    echo Error creating tables!
    pause
    exit /b 1
)

echo.
echo ========================================
echo SUCCESS! ALL TABLES CREATED!
echo ========================================
echo.
echo Login credentials:
echo.
echo Secretary: secretary@pangi.gov / password
echo Staff: staff@pangi.gov / password
echo.
echo Access at: http://127.0.0.1:8000/login
echo.
pause
