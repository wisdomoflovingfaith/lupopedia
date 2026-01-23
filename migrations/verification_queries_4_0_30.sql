-- Migration Verification Queries - Version 4.0.30
-- Run these queries after executing all migrations to verify success

-- ========================================
-- 1. Verify Orchestration Schema Tables
-- ========================================
USE lupopedia_orchestration;

SELECT 
    TABLE_NAME as 'Orchestration Tables',
    TABLE_ROWS as 'Row Count',
    DATA_LENGTH as 'Data Size (bytes)'
FROM information_schema.TABLES 
WHERE TABLE_SCHEMA = 'lupopedia_orchestration'
ORDER BY TABLE_NAME;

-- Verify specific orchestrator tables exist
SELECT 'migration_batches' as table_name, COUNT(*) as count FROM migration_batches
UNION ALL
SELECT 'migration_files' as table_name, COUNT(*) as count FROM migration_files
UNION ALL
SELECT 'migration_validation_log' as table_name, COUNT(*) as count FROM migration_validation_log
UNION ALL
SELECT 'migration_rollback_log' as table_name, COUNT(*) as count FROM migration_rollback_log
UNION ALL
SELECT 'migration_dependencies' as table_name, COUNT(*) as count FROM migration_dependencies
UNION ALL
SELECT 'migration_system_state' as table_name, COUNT(*) as count FROM migration_system_state
UNION ALL
SELECT 'migration_progress' as table_name, COUNT(*) as count FROM migration_progress
UNION ALL
SELECT 'migration_alerts' as table_name, COUNT(*) as count FROM migration_alerts;

-- ========================================
-- 2. Verify Ephemeral Schema Tables
-- ========================================
USE lupopedia_ephemeral;

SELECT 
    TABLE_NAME as 'Ephemeral Tables',
    TABLE_ROWS as 'Row Count',
    DATA_LENGTH as 'Data Size (bytes)'
FROM information_schema.TABLES 
WHERE TABLE_SCHEMA = 'lupopedia_ephemeral'
ORDER BY TABLE_NAME;

-- Verify specific ephemeral tables exist
SELECT 'lupo_sessions' as table_name, COUNT(*) as count FROM lupo_sessions
UNION ALL
SELECT 'lupo_cache' as table_name, COUNT(*) as count FROM lupo_cache
UNION ALL
SELECT 'lupo_temp_data' as table_name, COUNT(*) as count FROM lupo_temp_data
UNION ALL
SELECT 'lupo_job_queue' as table_name, COUNT(*) as count FROM lupo_job_queue
UNION ALL
SELECT 'lupo_locks' as table_name, COUNT(*) as count FROM lupo_locks;

-- ========================================
-- 3. Verify Doctrine Tab Mappings
-- ========================================
USE lupopedia;

-- Verify agent doctrine mapping (tab 13)
SELECT 
    'Agent Doctrine (tab 13)' as mapping_type,
    COUNT(*) as total_mapped,
    SUM(CASE WHEN is_deleted = 0 THEN 1 ELSE 0 END) as active_mappings
FROM lupo_collection_tab_map 
WHERE tab_id = 13 AND item_type = 'content';

-- List agent doctrine files mapped
SELECT 
    c.content_name,
    c.content_slug,
    ctm.sort_order
FROM lupo_collection_tab_map ctm
JOIN lupo_content c ON ctm.item_id = c.content_id
WHERE ctm.tab_id = 13 
  AND ctm.item_type = 'content' 
  AND ctm.is_deleted = 0
ORDER BY ctm.sort_order;

-- Verify versioning doctrine mapping (tab 21)
SELECT 
    'Versioning Doctrine (tab 21)' as mapping_type,
    COUNT(*) as total_mapped,
    SUM(CASE WHEN is_deleted = 0 THEN 1 ELSE 0 END) as active_mappings
FROM lupo_collection_tab_map 
WHERE tab_id = 21 AND item_type = 'content';

-- List versioning doctrine files mapped
SELECT 
    c.content_name,
    c.content_slug,
    ctm.sort_order
FROM lupo_collection_tab_map ctm
JOIN lupo_content c ON ctm.item_id = c.content_id
WHERE ctm.tab_id = 21 
  AND ctm.item_type = 'content' 
  AND ctm.is_deleted = 0
ORDER BY ctm.sort_order;

-- Verify SQL doctrine mapping (tab 11)
SELECT 
    'SQL Doctrine (tab 11)' as mapping_type,
    COUNT(*) as total_mapped,
    SUM(CASE WHEN is_deleted = 0 THEN 1 ELSE 0 END) as active_mappings
FROM lupo_collection_tab_map 
WHERE tab_id = 11 AND item_type = 'content';

-- List SQL doctrine files mapped
SELECT 
    c.content_name,
    c.content_slug,
    ctm.sort_order
FROM lupo_collection_tab_map ctm
JOIN lupo_content c ON ctm.item_id = c.content_id
WHERE ctm.tab_id = 11 
  AND ctm.item_type = 'content' 
  AND ctm.is_deleted = 0
ORDER BY ctm.sort_order;

-- ========================================
-- 4. Verify No Duplicate Mappings
-- ========================================
-- Check for any duplicate active mappings
SELECT 
    COUNT(*) as duplicate_count,
    GROUP_CONCAT(DISTINCT CONCAT(content_slug, ' → tab ', tab_id)) as duplicates
FROM (
    SELECT 
        c.content_slug,
        ctm.tab_id,
        COUNT(*) as cnt
    FROM lupo_collection_tab_map ctm
    JOIN lupo_content c ON ctm.item_id = c.content_id
    WHERE ctm.is_deleted = 0 AND ctm.item_type = 'content'
    GROUP BY c.content_slug, ctm.tab_id
    HAVING cnt > 1
) dup_check;

-- ========================================
-- 5. Verify Schema Counts
-- ========================================
SELECT 
    'Schema Summary' as summary_type,
    COUNT(*) as table_count,
    SUM(TABLE_ROWS) as total_rows,
    SUM(DATA_LENGTH + INDEX_LENGTH) as total_size_bytes
FROM information_schema.TABLES 
WHERE TABLE_SCHEMA IN ('lupopedia', 'lupopedia_orchestration', 'lupopedia_ephemeral');

-- Schema breakdown
SELECT 
    TABLE_SCHEMA as schema_name,
    COUNT(*) as table_count,
    SUM(TABLE_ROWS) as total_rows,
    SUM(DATA_LENGTH + INDEX_LENGTH) as total_size_bytes
FROM information_schema.TABLES 
WHERE TABLE_SCHEMA IN ('lupopedia', 'lupopedia_orchestration', 'lupopedia_ephemeral')
GROUP BY TABLE_SCHEMA
ORDER BY TABLE_SCHEMA;

-- ========================================
-- 6. Verify Migration Status
-- ========================================
-- Check if any migration failed (this would be in application logs)
SELECT 'Migration Verification Complete' as status,
       NOW() as verification_timestamp,
       'All queries executed successfully' as result;
