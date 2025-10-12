-- ============================================
-- CREATE ALL TABLES - COMPLETE DATABASE SETUP
-- Barangay Matina Pangi Information System
-- ============================================
--
-- INSTRUCTIONS:
-- 1. Open phpMyAdmin: http://localhost/phpmyadmin
-- 2. Select database 'pangi' (or create it first)
-- 3. Click "SQL" tab
-- 4. Copy and paste this ENTIRE file
-- 5. Click "Go"
-- 6. Wait for completion
--
-- This will create ALL tables and 2 user accounts
-- ============================================

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- ============================================
-- DROP EXISTING TABLES (Fix corrupted tables)
-- ============================================

SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS `audit_logs`;
DROP TABLE IF EXISTS `residents`;
DROP TABLE IF EXISTS `households`;
DROP TABLE IF EXISTS `password_reset_tokens`;
DROP TABLE IF EXISTS `users`;
DROP TABLE IF EXISTS `puroks`;
DROP TABLE IF EXISTS `migrations`;

SET FOREIGN_KEY_CHECKS = 1;

-- ============================================
-- 1. USERS TABLE
-- ============================================

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
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

-- ============================================
-- 2. PASSWORD RESET TOKENS TABLE
-- ============================================

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- 3. HOUSEHOLDS TABLE
-- ============================================

CREATE TABLE `households` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `household_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `purok` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `housing_type` enum('owned','rented','rent-free') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'owned',
  `has_electricity` tinyint(1) NOT NULL DEFAULT 1,
  `electric_account_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total_members` int(11) NOT NULL DEFAULT 1,
  `household_type` enum('solo','family','extended') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'family',
  `parent_household_id` bigint(20) UNSIGNED DEFAULT NULL,
  `approval_status` enum('pending','approved','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'approved',
  `approved_at` timestamp NULL DEFAULT NULL,
  `approved_by` bigint(20) UNSIGNED DEFAULT NULL,
  `rejection_reason` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `households_household_id_unique` (`household_id`),
  KEY `households_parent_household_id_foreign` (`parent_household_id`),
  KEY `households_approved_by_foreign` (`approved_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- 4. RESIDENTS TABLE
-- ============================================

CREATE TABLE `residents` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `resident_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `household_id` bigint(20) UNSIGNED NOT NULL,
  `first_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `middle_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `suffix` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `birthdate` date NOT NULL,
  `age` int(11) NOT NULL,
  `sex` enum('male','female') COLLATE utf8mb4_unicode_ci NOT NULL,
  `civil_status` enum('single','married','widowed','separated','divorced') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'single',
  `place_of_birth` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nationality` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Filipino',
  `religion` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `household_role` enum('head','spouse','child','parent','sibling','relative','other') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'head',
  `is_household_head` tinyint(1) NOT NULL DEFAULT 0,
  `status` enum('active','reallocated','deceased') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `approval_status` enum('pending','approved','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'approved',
  `approved_at` timestamp NULL DEFAULT NULL,
  `approved_by` bigint(20) UNSIGNED DEFAULT NULL,
  `rejection_reason` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status_notes` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status_changed_at` timestamp NULL DEFAULT NULL,
  `status_changed_by` bigint(20) UNSIGNED DEFAULT NULL,
  `is_pwd` tinyint(1) NOT NULL DEFAULT 0,
  `pwd_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `disability_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_senior_citizen` tinyint(1) NOT NULL DEFAULT 0,
  `senior_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_teen` tinyint(1) NOT NULL DEFAULT 0,
  `is_voter` tinyint(1) NOT NULL DEFAULT 0,
  `precinct_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_4ps_beneficiary` tinyint(1) NOT NULL DEFAULT 0,
  `4ps_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `occupation` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `employment_status` enum('employed','unemployed','self-employed','student','retired') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `employer_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `monthly_income` decimal(10,2) DEFAULT NULL,
  `educational_attainment` enum('no formal education','elementary level','elementary graduate','high school level','high school graduate','college level','college graduate','vocational','post graduate') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `blood_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `medical_conditions` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remarks` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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
  KEY `residents_status_changed_by_foreign` (`status_changed_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- 5. AUDIT LOGS TABLE
-- ============================================

CREATE TABLE `audit_logs` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `action` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint(20) UNSIGNED DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `old_values` json DEFAULT NULL,
  `new_values` json DEFAULT NULL,
  `ip_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `audit_logs_user_id_foreign` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- 6. MIGRATIONS TABLE
-- ============================================

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- 7. PUROKS TABLE
-- ============================================

CREATE TABLE `puroks` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total_households` int(11) NOT NULL DEFAULT 0,
  `total_residents` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- ADD FOREIGN KEY CONSTRAINTS
-- ============================================

ALTER TABLE `households`
  ADD CONSTRAINT `households_parent_household_id_foreign` FOREIGN KEY (`parent_household_id`) REFERENCES `households` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `households_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

ALTER TABLE `residents`
  ADD CONSTRAINT `residents_household_id_foreign` FOREIGN KEY (`household_id`) REFERENCES `households` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `residents_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `residents_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `residents_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `residents_status_changed_by_foreign` FOREIGN KEY (`status_changed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

ALTER TABLE `audit_logs`
  ADD CONSTRAINT `audit_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

-- ============================================
-- INSERT DEFAULT USERS
-- ============================================

-- Delete existing users if any
DELETE FROM `users` WHERE `email` IN ('secretary@pangi.gov', 'staff@pangi.gov');

-- Insert Secretary Account
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

COMMIT;

-- ============================================
-- VERIFY SETUP
-- ============================================

SELECT 'Setup Complete!' AS Status;
SELECT COUNT(*) AS 'Total Tables Created' FROM information_schema.tables WHERE table_schema = 'pangi';
SELECT id, name, email, role FROM users;

-- ============================================
-- SUCCESS!
-- ============================================
-- All tables have been created successfully!
--
-- LOGIN CREDENTIALS:
--
-- Secretary (Full Access):
--   Email: secretary@pangi.gov
--   Password: password
--
-- Staff (Limited Access):
--   Email: staff@pangi.gov
--   Password: password
--
-- Access your system at:
-- http://127.0.0.1:8000/login
-- ============================================
