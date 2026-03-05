-- ============================================================
-- REGISTRY CONSOLIDATION MIGRATION
-- Version: 4.0.34
-- Date: 20260223
-- Purpose: Consolidate lupo_unified_registry → lupo_registry
-- Author: KIRO IDE (actor_id 1001)
-- ============================================================

-- SAFETY: This migration is designed to be run in a transaction
-- with comprehensive rollback capability.

-- Prerequisites:
-- 1. Database backup created
-- 2. Application in maintenance mode
-- 3. No active writes to registry tables

-- ============================================================
-- STEP 1: PRE-MIGRATION VALIDATION
-- ============================================================

-- Check if both tables exist
SELECT 'Checking table existence...' AS status;

SELECT 
    CASE 
        WHEN COUNT(*) = 2 THEN 'PASS: Both tables exist'
        ELSE 'FAIL: Missing tables'
    END AS validation_result
FROM information_schema.tables
WHERE table_schema = DATABASE()
AND table_name IN ('lupo_registry', 'lupo_unified_registry');

-- Count entries in both tables
SELECT 'Counting entries in both tables...' AS status;

SELECT 
    'lupo_registry' AS table_name,
    COUNT(*) AS entry_count
FROM lupo_registry
WHERE is_deleted = 0

UNION ALL

SELECT 
    'lupo_unified_registry' AS table_name,
    COUNT(*) AS entry_count
FROM lupo_unified_registry
WHERE is_deleted = 0;

-- ============================================================
-- STEP 2: ORPHAN DETECTION
-- ============================================================

SELECT 'Detecting orphaned entries...' AS status;

-- Find entries in lupo_unified_registry not in lupo_registry
SELECT 
    'Orphans in lupo_unified_registry' AS orphan_type,
    COUNT(*) AS orphan_count
FROM lupo_unified_registry ur
LEFT JOIN lupo_registry r 
    ON ur.entity_type = r.entity_type 
    AND ur.entity_index = r.entity_index
WHERE r.registry_id IS NULL
AND ur.is_deleted = 0;

-- Find entries in lupo_registry not in lupo_unified_registry
SELECT 
    'Orphans in lupo_registry' AS orphan_type,
    COUNT(*) AS orphan_count
FROM lupo_registry r
LEFT JOIN lupo_unified_registry ur 
    ON r.entity_type = ur.entity_type 
    AND r.entity_index = ur.entity_index
WHERE ur.registry_id IS NULL
AND r.is_deleted = 0;

-- ============================================================
-- STEP 3: CONFLICT DETECTION
-- ============================================================

SELECT 'Detecting conflicts...' AS status;

-- Find entries with same entity_type + entity_index but different data
SELECT 
    'Data conflicts' AS conflict_type,
    COUNT(*) AS conflict_count
FROM lupo_unified_registry ur
INNER JOIN lupo_registry r 
    ON ur.entity_type = r.entity_type 
    AND ur.entity_index = r.entity_index
WHERE (
    ur.entity_name != r.entity_name
    OR ur.entity_key != r.entity_key
    OR ur.metadata_json != r.metadata_json
)
AND ur.is_deleted = 0
AND r.is_deleted = 0;

-- ============================================================
-- STEP 4: MIGRATION EXECUTION (TRANSACTION)
-- ============================================================

-- NOTE: Uncomment the following section when ready to execute migration
-- This is commented out for safety during metadata-only phase

/*
START TRANSACTION;

-- Set timestamp for migration
SET @migration_timestamp = CAST(DATE_FORMAT(UTC_TIMESTAMP(), '%Y%m%d%H%i%s') AS UNSIGNED);

-- ============================================================
-- STEP 4A: MIGRATE ORPHANS FROM lupo_unified_registry
-- ============================================================

-- Insert orphaned entries from lupo_unified_registry into lupo_registry
INSERT INTO lupo_registry (
    registry_id,
    entity_type,
    entity_index,
    entity_key,
    entity_name,
    entity_table,
    federation_node_id,
    created_ymdhis,
    updated_ymdhis,
    is_deleted,
    deleted_ymdhis,
    is_active,
    is_kernel,
    metadata_json
)
SELECT 
    ur.registry_id,
    ur.entity_type,
    ur.entity_index,
    ur.entity_key,
    ur.entity_name,
    ur.entity_table,
    ur.federation_node_id,
    ur.created_ymdhis,
    @migration_timestamp AS updated_ymdhis,
    ur.is_deleted,
    ur.deleted_ymdhis,
    ur.is_active,
    ur.is_kernel,
    ur.metadata_json
FROM lupo_unified_registry ur
LEFT JOIN lupo_registry r 
    ON ur.entity_type = r.entity_type 
    AND ur.entity_index = r.entity_index
WHERE r.registry_id IS NULL
AND ur.is_deleted = 0;

-- Log orphan adoptions to ANUBIS
SET @log_id_seq := @migration_timestamp * 1000;
INSERT INTO lupo_anubis_log (
    anubis_log_id,
    event_type,
    severity,
    source_table,
    source_id,
    entity_type,
    entity_index,
    context_json,
    status,
    assigned_to_actor_id,
    created_ymdhis,
    updated_ymdhis
)
SELECT 
    @log_id_seq := @log_id_seq + 1,
    'REGISTRY_ADOPTION' AS event_type,
    'normal' AS severity,
    'lupo_registry' AS source_table,
    ur.registry_id AS source_id,
    ur.entity_type,
    ur.entity_index,
    JSON_OBJECT('reason', 'Orphan entry migrated from lupo_unified_registry', 'action', 'Adopted to lupo_registry') AS context_json,
    'Resolved' AS status,
    19 AS assigned_to_actor_id,
    @migration_timestamp AS created_ymdhis,
    @migration_timestamp AS updated_ymdhis
FROM lupo_unified_registry ur
LEFT JOIN lupo_registry r 
    ON ur.entity_type = r.entity_type 
    AND ur.entity_index = r.entity_index
WHERE r.registry_id IS NULL
AND ur.is_deleted = 0;

-- ============================================================
-- STEP 4B: RESOLVE CONFLICTS
-- ============================================================

-- Update lupo_registry with newer data from lupo_unified_registry
-- (if lupo_unified_registry has more recent updated_ymdhis)
UPDATE lupo_registry r
INNER JOIN lupo_unified_registry ur 
    ON r.entity_type = ur.entity_type 
    AND r.entity_index = ur.entity_index
SET 
    r.entity_name = ur.entity_name,
    r.entity_key = ur.entity_key,
    r.metadata_json = ur.metadata_json,
    r.updated_ymdhis = @migration_timestamp
WHERE ur.updated_ymdhis > r.updated_ymdhis
AND ur.is_deleted = 0
AND r.is_deleted = 0;

-- Log conflict resolutions to ANUBIS
INSERT INTO lupo_anubis_log (
    anubis_log_id,
    event_type,
    severity,
    source_table,
    source_id,
    entity_type,
    entity_index,
    context_json,
    status,
    assigned_to_actor_id,
    created_ymdhis,
    updated_ymdhis
)
SELECT 
    @log_id_seq := @log_id_seq + 1,
    'REGISTRY_DEDUPLICATION' AS event_type,
    'normal' AS severity,
    'lupo_registry' AS source_table,
    r.registry_id AS source_id,
    r.entity_type,
    r.entity_index,
    JSON_OBJECT('reason', 'Conflict resolved - newer data from lupo_unified_registry', 'action', 'Updated lupo_registry with newer data') AS context_json,
    'Resolved' AS status,
    19 AS assigned_to_actor_id,
    @migration_timestamp AS created_ymdhis,
    @migration_timestamp AS updated_ymdhis
FROM lupo_registry r
INNER JOIN lupo_unified_registry ur 
    ON r.entity_type = ur.entity_type 
    AND r.entity_index = ur.entity_index
WHERE ur.updated_ymdhis > r.updated_ymdhis
AND ur.is_deleted = 0
AND r.is_deleted = 0;

-- ============================================================
-- STEP 4C: VALIDATION
-- ============================================================

-- Verify no data loss
SELECT 
    CASE 
        WHEN (SELECT COUNT(*) FROM lupo_registry WHERE is_deleted = 0) >= 
             (SELECT COUNT(*) FROM lupo_unified_registry WHERE is_deleted = 0)
        THEN 'PASS: No data loss detected'
        ELSE 'FAIL: Data loss detected - ROLLBACK REQUIRED'
    END AS validation_result;

-- Verify no orphans remain
SELECT 
    CASE 
        WHEN (
            SELECT COUNT(*) 
            FROM lupo_unified_registry ur
            LEFT JOIN lupo_registry r 
                ON ur.entity_type = r.entity_type 
                AND ur.entity_index = r.entity_index
            WHERE r.registry_id IS NULL
            AND ur.is_deleted = 0
        ) = 0
        THEN 'PASS: No orphans remain'
        ELSE 'FAIL: Orphans detected - ROLLBACK REQUIRED'
    END AS validation_result;

-- ============================================================
-- STEP 4D: COMMIT OR ROLLBACK
-- ============================================================

-- If all validations pass, commit the transaction
-- If any validation fails, rollback the transaction

-- Manual decision point:
-- COMMIT;   -- Uncomment to commit migration
-- ROLLBACK; -- Uncomment to rollback migration

*/

-- ============================================================
-- STEP 5: POST-MIGRATION CLEANUP (AFTER COMMIT)
-- ============================================================

-- NOTE: Execute these steps ONLY after successful migration and verification
-- This is commented out for safety

/*
-- Rename legacy table for safety (don't drop immediately)
RENAME TABLE lupo_unified_registry TO lupo_unified_registry_backup_20260223;

-- After 30 days of successful operation, drop the backup table
-- DROP TABLE IF EXISTS lupo_unified_registry_backup_20260223;
*/

-- ============================================================
-- STEP 6: VERIFICATION QUERIES
-- ============================================================

-- Run these queries after migration to verify success

SELECT 'Post-migration verification queries...' AS status;

-- Count entries in lupo_registry
SELECT 
    'lupo_registry entry count' AS metric,
    COUNT(*) AS value
FROM lupo_registry
WHERE is_deleted = 0;

-- Verify all entity types present
SELECT 
    entity_type,
    COUNT(*) AS entry_count
FROM lupo_registry
WHERE is_deleted = 0
GROUP BY entity_type
ORDER BY entity_type;

-- Check for duplicate registry_id
SELECT 
    'Duplicate registry_id check' AS metric,
    CASE 
        WHEN COUNT(*) = COUNT(DISTINCT registry_id)
        THEN 'PASS: No duplicates'
        ELSE 'FAIL: Duplicates detected'
    END AS result
FROM lupo_registry
WHERE is_deleted = 0;

-- ============================================================
-- ROLLBACK PROCEDURE
-- ============================================================

-- If migration fails, execute the following:

/*
ROLLBACK;

-- Verify rollback success
SELECT 
    'lupo_unified_registry' AS table_name,
    COUNT(*) AS entry_count
FROM lupo_unified_registry
WHERE is_deleted = 0

UNION ALL

SELECT 
    'lupo_registry' AS table_name,
    COUNT(*) AS entry_count
FROM lupo_registry
WHERE is_deleted = 0;

-- Investigate failure cause
-- Fix issues
-- Retry migration
*/

-- ============================================================
-- END OF MIGRATION SCRIPT
-- ============================================================

-- NOTES:
-- 1. This script is designed for safety with comprehensive validation
-- 2. All operations are in a transaction for rollback capability
-- 3. ANUBIS logging provides complete audit trail
-- 4. Legacy table is renamed (not dropped) for safety
-- 5. Verification queries confirm migration success

-- EXECUTION CHECKLIST:
-- [ ] Database backup created
-- [ ] Application in maintenance mode
-- [ ] No active writes to registry tables
-- [ ] Uncomment transaction section
-- [ ] Execute migration
-- [ ] Verify results
-- [ ] Commit or rollback
-- [ ] Update code references
-- [ ] Remove maintenance mode
-- [ ] Monitor for 30 days
-- [ ] Drop backup table

-- ============================================================
