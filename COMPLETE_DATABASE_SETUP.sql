-- ============================================
-- COMPLETE DATABASE SETUP
-- Barangay Matina Pangi Information System
-- ============================================
-- 
-- INSTRUCTIONS:
-- 1. Open phpMyAdmin: http://localhost/phpmyadmin
-- 2. Create database named 'pangi' (if not exists)
-- 3. Select the 'pangi' database
-- 4. Click "SQL" tab
-- 5. Copy and paste this ENTIRE file
-- 6. Click "Go"
-- 7. Wait for completion
-- 
-- This will create the users table and 2 default accounts
-- ============================================

-- Drop existing users table if exists (CAUTION: This deletes all users!)
-- Comment out the next line if you want to keep existing data
-- DROP TABLE IF EXISTS `users`;

-- Create users table
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('secretary','staff') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'staff',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Delete existing test users if they exist
DELETE FROM `users` WHERE `email` IN ('secretary@pangi.gov', 'staff@pangi.gov');

-- Insert Secretary Account
-- Email: secretary@pangi.gov
-- Password: password
INSERT INTO `users` (`name`, `email`, `email_verified_at`, `password`, `role`, `created_at`, `updated_at`) 
VALUES (
  'Barangay Secretary',
  'secretary@pangi.gov',
  NOW(),
  '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
  'secretary',
  NOW(),
  NOW()
);

-- Insert Staff Account
-- Email: staff@pangi.gov
-- Password: password
INSERT INTO `users` (`name`, `email`, `email_verified_at`, `password`, `role`, `created_at`, `updated_at`) 
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
SELECT 
  id,
  name,
  email,
  role,
  created_at
FROM `users`
ORDER BY id;

-- ============================================
-- EXPECTED RESULT:
-- You should see 2 rows:
-- 
-- ID | Name                | Email                 | Role      | Created At
-- ---|---------------------|-----------------------|-----------|------------------
-- 1  | Barangay Secretary  | secretary@pangi.gov   | secretary | [timestamp]
-- 2  | Barangay Staff      | staff@pangi.gov       | staff     | [timestamp]
-- ============================================

-- ============================================
-- LOGIN CREDENTIALS:
-- 
-- SECRETARY (Full Access):
--   Email: secretary@pangi.gov
--   Password: password
-- 
-- STAFF (Limited Access):
--   Email: staff@pangi.gov
--   Password: password
-- 
-- Login URL: http://127.0.0.1:8000/login
-- ============================================

-- ============================================
-- NEXT STEPS:
-- 
-- 1. Go to: http://127.0.0.1:8000/login
-- 2. Login with secretary credentials
-- 3. Explore the system!
-- 
-- IMPORTANT: Change these default passwords after first login!
-- ============================================
