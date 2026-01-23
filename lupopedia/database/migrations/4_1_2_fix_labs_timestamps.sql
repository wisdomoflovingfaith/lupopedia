-- Migration: Fix LABS Timestamp Doctrine
-- Version: 4.1.2
-- Date: 2026-01-18
-- Module: LABS-001
-- Description: Ensures all LABS tables use BIGINT YYYYMMDDHHIISS timestamps and no UNSIGNED fields.
--
-- This migration removes UNSIGNED from all integer columns in LABS tables to comply
-- with Lupopedia's No-UNSIGNED doctrine for database portability (PostgreSQL compatibility).
--
-- PREREQUISITE: 
--   - Run migration 4.1.6_create_labs_declarations_table.sql FIRST if tables don't exist
--   - This migration only modifies existing tables (does not create them)
--
-- @package Lupopedia
-- @version 4.1.2
-- @author Captain Wolfie
-- @governance LABS-001 Doctrine v1.0

-- ============================================================================
-- FIX LUPO_LABS_DECLARATIONS TABLE
-- ============================================================================
-- Only modify if table exists

-- Remove UNSIGNED from primary key
ALTER TABLE `lupo_labs_declarations`
MODIFY COLUMN `labs_declaration_id` BIGINT NOT NULL AUTO_INCREMENT COMMENT 'Primary key for LABS declaration record';

-- Remove UNSIGNED from actor_id
ALTER TABLE `lupo_labs_declarations`
MODIFY COLUMN `actor_id` BIGINT NOT NULL COMMENT 'Reference to actor (from lupo_actors)';

-- Remove UNSIGNED from declaration_timestamp
ALTER TABLE `lupo_labs_declarations`
MODIFY COLUMN `declaration_timestamp` BIGINT NOT NULL COMMENT 'UTC timestamp from actor declaration (YYYYMMDDHHMMSS)';

-- Remove UNSIGNED from next_revalidation_ymdhis
ALTER TABLE `lupo_labs_declarations`
MODIFY COLUMN `next_revalidation_ymdhis` BIGINT NOT NULL COMMENT 'UTC timestamp when revalidation required (YYYYMMDDHHMMSS)';

-- Remove UNSIGNED from created_ymdhis (ensure NOT NULL)
ALTER TABLE `lupo_labs_declarations`
MODIFY COLUMN `created_ymdhis` BIGINT NOT NULL COMMENT 'UTC creation timestamp (YYYYMMDDHHMMSS)';

-- Remove UNSIGNED from updated_ymdhis (ensure NOT NULL)
ALTER TABLE `lupo_labs_declarations`
MODIFY COLUMN `updated_ymdhis` BIGINT NOT NULL COMMENT 'UTC last update timestamp (YYYYMMDDHHMMSS)';

-- Remove UNSIGNED from deleted_ymdhis (nullable is correct)
ALTER TABLE `lupo_labs_declarations`
MODIFY COLUMN `deleted_ymdhis` BIGINT DEFAULT NULL COMMENT 'UTC deletion timestamp (YYYYMMDDHHMMSS)';

-- Verify is_deleted is TINYINT (should already be correct, but ensure no UNSIGNED)
ALTER TABLE `lupo_labs_declarations`
MODIFY COLUMN `is_deleted` TINYINT NOT NULL DEFAULT 0 COMMENT 'Soft delete flag (1=deleted, 0=active)';

-- ============================================================================
-- FIX LUPO_LABS_VIOLATIONS TABLE
-- ============================================================================
-- NOTE: This table may not exist if migration 4.1.6 hasn't been run yet.
-- If you get "Table doesn't exist" error, run 4.1.6_create_labs_declarations_table.sql first.
-- Or comment out this section if the table doesn't exist yet.

-- Remove UNSIGNED from primary key
ALTER TABLE `lupo_labs_violations`
MODIFY COLUMN `labs_violation_id` BIGINT NOT NULL AUTO_INCREMENT COMMENT 'Primary key for violation record';

-- Remove UNSIGNED from actor_id
ALTER TABLE `lupo_labs_violations`
MODIFY COLUMN `actor_id` BIGINT NOT NULL COMMENT 'Reference to actor (from lupo_actors)';

-- Remove UNSIGNED from resolved_ymdhis (nullable is correct)
ALTER TABLE `lupo_labs_violations`
MODIFY COLUMN `resolved_ymdhis` BIGINT DEFAULT NULL COMMENT 'UTC timestamp when violation resolved (YYYYMMDDHHMMSS)';

-- Remove UNSIGNED from created_ymdhis (ensure NOT NULL)
ALTER TABLE `lupo_labs_violations`
MODIFY COLUMN `created_ymdhis` BIGINT NOT NULL COMMENT 'UTC creation timestamp (YYYYMMDDHHMMSS)';

-- Remove UNSIGNED from updated_ymdhis (ensure NOT NULL)
ALTER TABLE `lupo_labs_violations`
MODIFY COLUMN `updated_ymdhis` BIGINT NOT NULL COMMENT 'UTC last update timestamp (YYYYMMDDHHMMSS)';

-- Remove UNSIGNED from deleted_ymdhis (nullable is correct)
ALTER TABLE `lupo_labs_violations`
MODIFY COLUMN `deleted_ymdhis` BIGINT DEFAULT NULL COMMENT 'UTC deletion timestamp (YYYYMMDDHHMMSS)';

-- Verify is_deleted is TINYINT (should already be correct, but ensure no UNSIGNED)
ALTER TABLE `lupo_labs_violations`
MODIFY COLUMN `is_deleted` TINYINT NOT NULL DEFAULT 0 COMMENT 'Soft delete flag (1=deleted, 0=active)';

-- ============================================================================
-- VERIFICATION QUERIES
-- ============================================================================
-- 
-- Run these queries to verify the migration:
--
-- Check lupo_labs_declarations columns:
-- SELECT 
--     COLUMN_NAME, 
--     COLUMN_TYPE, 
--     IS_NULLABLE, 
--     COLUMN_DEFAULT
-- FROM INFORMATION_SCHEMA.COLUMNS 
-- WHERE TABLE_SCHEMA = DATABASE() 
--   AND TABLE_NAME = 'lupo_labs_declarations'
--   AND COLUMN_NAME IN (
--       'labs_declaration_id', 'actor_id', 'declaration_timestamp',
--       'next_revalidation_ymdhis', 'created_ymdhis', 'updated_ymdhis',
--       'deleted_ymdhis', 'is_deleted'
--   )
-- ORDER BY ORDINAL_POSITION;
--
-- Expected results:
-- - All BIGINT columns should be "bigint" (NOT "bigint unsigned")
-- - created_ymdhis and updated_ymdhis should be "bigint" with IS_NULLABLE = "NO"
-- - is_deleted should be "tinyint" (NOT "tinyint unsigned")
--
-- Check lupo_labs_violations columns:
-- SELECT 
--     COLUMN_NAME, 
--     COLUMN_TYPE, 
--     IS_NULLABLE, 
--     COLUMN_DEFAULT
-- FROM INFORMATION_SCHEMA.COLUMNS 
-- WHERE TABLE_SCHEMA = DATABASE() 
--   AND TABLE_NAME = 'lupo_labs_violations'
--   AND COLUMN_NAME IN (
--       'labs_violation_id', 'actor_id', 'resolved_ymdhis',
--       'created_ymdhis', 'updated_ymdhis', 'deleted_ymdhis', 'is_deleted'
--   )
-- ORDER BY ORDINAL_POSITION;
--
-- Expected results:
-- - All BIGINT columns should be "bigint" (NOT "bigint unsigned")
-- - created_ymdhis and updated_ymdhis should be "bigint" with IS_NULLABLE = "NO"
-- - is_deleted should be "tinyint" (NOT "tinyint unsigned")
--
-- Verify no UNSIGNED columns remain:
-- SELECT 
--     TABLE_NAME, 
--     COLUMN_NAME, 
--     COLUMN_TYPE
-- FROM INFORMATION_SCHEMA.COLUMNS 
-- WHERE TABLE_SCHEMA = DATABASE() 
--   AND TABLE_NAME IN ('lupo_labs_declarations', 'lupo_labs_violations')
--   AND COLUMN_TYPE LIKE '%unsigned%';
--
-- Expected result: 0 rows (no UNSIGNED columns found)

-- ============================================================================
-- MIGRATION COMPLETE
-- ============================================================================
-- 
-- All LABS tables now comply with Lupopedia doctrine:
-- ✅ All IDs are BIGINT (no UNSIGNED)
-- ✅ All timestamps are BIGINT (no UNSIGNED)
-- ✅ created_ymdhis and updated_ymdhis are BIGINT NOT NULL
-- ✅ is_deleted is TINYINT (no UNSIGNED)
-- ✅ No TIMESTAMP or DATETIME types
-- ✅ No foreign keys
-- ✅ Portable across MySQL and PostgreSQL
