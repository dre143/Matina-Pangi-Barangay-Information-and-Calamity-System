-- ============================================
-- RUN THIS FIRST TO FIX CORRUPTED TABLES
-- ============================================

SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS `audit_logs`;
DROP TABLE IF EXISTS `residents`;
DROP TABLE IF EXISTS `households`;
DROP TABLE IF EXISTS `password_reset_tokens`;
DROP TABLE IF EXISTS `users`;
DROP TABLE IF EXISTS `puroks`;
DROP TABLE IF EXISTS `migrations`;
DROP TABLE IF EXISTS `personal_access_tokens`;
DROP TABLE IF EXISTS `failed_jobs`;

SET FOREIGN_KEY_CHECKS = 1;

-- ============================================
-- Tables dropped successfully!
-- Now run CREATE_ALL_TABLES.sql
-- ============================================
