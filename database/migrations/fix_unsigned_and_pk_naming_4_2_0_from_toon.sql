-- ============================================================================
-- Lupopedia 4.2.0 Migration: Fix UNSIGNED Columns and Primary Key Naming
-- Generated from TOON file metadata
-- ============================================================================
-- Purpose: 
--   1. Remove all UNSIGNED keywords from columns (for PostgreSQL compatibility)
--   2. Rename primary keys to follow "singular table name + _id" convention
--   3. Create missing tables (e.g., lupo_collection_tab_paths)
--
-- Doctrine: 
--   - No UNSIGNED (PostgreSQL doesn't support it)
--   - Primary keys must be singular table name + _id (because no FK keys)
--   - All relationships are application-managed, so naming must be explicit
--
-- SCOPE: 
--   - ONLY lupo_* prefixed tables are included in this migration
--   - livehelp_* prefixed tables are OLD Crafty Syntax tables and are IGNORED
--
-- Generated: 2026-01-10 (from TOON files)
-- ============================================================================

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- ============================================================================
-- PART 1: REMOVE UNSIGNED FROM ALL COLUMNS (non-PK first)
-- ============================================================================
-- No UNSIGNED columns found (or all are handled in PK rename section)

-- ============================================================================
-- PART 2: RENAME PRIMARY KEYS AND REMOVE UNSIGNED FROM PKs
-- ============================================================================
-- No primary keys need renaming

-- ============================================================================
-- PART 3: VERIFICATION QUERIES
-- ============================================================================

-- Check for remaining UNSIGNED columns (should return empty)
SELECT 
    TABLE_NAME, 
    COLUMN_NAME, 
    COLUMN_TYPE
FROM INFORMATION_SCHEMA.COLUMNS
WHERE TABLE_SCHEMA = DATABASE()
  AND TABLE_NAME LIKE 'lupo_%'
  AND COLUMN_TYPE LIKE '%unsigned%'
ORDER BY TABLE_NAME, COLUMN_NAME;

-- Check primary key naming (should all follow "singular + _id" convention)
SELECT 
    TABLE_NAME,
    COLUMN_NAME as PRIMARY_KEY
FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
WHERE TABLE_SCHEMA = DATABASE()
  AND TABLE_NAME LIKE 'lupo_%'
  AND CONSTRAINT_NAME = 'PRIMARY'
ORDER BY TABLE_NAME;

COMMIT;
