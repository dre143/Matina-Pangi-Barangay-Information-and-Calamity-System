-- ============================================
-- Manual User Creation Script
-- Barangay Matina Pangi Information System
-- ============================================
-- 
-- Instructions:
-- 1. Open phpMyAdmin (http://localhost/phpmyadmin)
-- 2. Select your database (pangi_db or whatever you named it)
-- 3. Click on "SQL" tab
-- 4. Copy and paste this entire script
-- 5. Click "Go" button
-- 
-- ============================================

-- Check if users table exists
-- If this fails, you need to run migrations first

-- Delete existing test users (if any)
DELETE FROM users WHERE email IN ('secretary@pangi.gov', 'staff@pangi.gov');

-- Create Secretary Account
-- Email: secretary@pangi.gov
-- Password: password
INSERT INTO users (name, email, email_verified_at, password, role, created_at, updated_at) 
VALUES (
    'Barangay Secretary',
    'secretary@pangi.gov',
    NOW(),
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    'secretary',
    NOW(),
    NOW()
);

-- Create Staff Account  
-- Email: staff@pangi.gov
-- Password: password
INSERT INTO users (name, email, email_verified_at, password, role, created_at, updated_at) 
VALUES (
    'Barangay Staff',
    'staff@pangi.gov',
    NOW(),
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    'staff',
    NOW(),
    NOW()
);

-- Verify users were created
SELECT id, name, email, role, created_at FROM users;

-- ============================================
-- Expected Result:
-- You should see 2 rows:
-- 1. Barangay Secretary | secretary@pangi.gov | secretary
-- 2. Barangay Staff     | staff@pangi.gov     | staff
-- ============================================

-- ============================================
-- LOGIN CREDENTIALS:
-- 
-- Secretary:
--   Email: secretary@pangi.gov
--   Password: password
-- 
-- Staff:
--   Email: staff@pangi.gov  
--   Password: password
-- ============================================
