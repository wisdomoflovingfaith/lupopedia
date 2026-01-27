-- ============================================================
-- Migration: Fix agent_registry_id to BIGINT and FK naming
-- Date: 2026-01-11
-- Purpose: 
--   1. Change agent_registry_id from INT to BIGINT (per ID standard - NOT UNSIGNED)
--   2. Fix dialog_threads_id to dialog_thread_id (singular naming convention)
--   3. Remove UNSIGNED from external_event_id (all IDs must be BIGINT NOT UNSIGNED)
-- ============================================================

-- ============================================================
-- PART 1: Fix agent_registry_id from INT to BIGINT
-- ============================================================

ALTER TABLE `lupo_agent_registry` 
MODIFY COLUMN `agent_registry_id` bigint NOT NULL auto_increment;

-- Verify the change
-- SELECT COLUMN_TYPE FROM INFORMATION_SCHEMA.COLUMNS 
-- WHERE TABLE_SCHEMA = DATABASE() 
--   AND TABLE_NAME = 'lupo_agent_registry' 
--   AND COLUMN_NAME = 'agent_registry_id';
-- Expected: bigint(20)

-- ============================================================
-- PART 2: Fix dialog_threads_id to dialog_thread_id
-- ============================================================

-- Drop the existing index on the old column name
DROP INDEX `idx_thread` ON `lupo_dialog_messages`;

-- Rename the column in lupo_dialog_messages
ALTER TABLE `lupo_dialog_messages` 
CHANGE COLUMN `dialog_threads_id` `dialog_thread_id` bigint COMMENT 'Optional thread grouping for related dialogs';

-- Recreate the index with the new column name
CREATE INDEX `idx_dialog_thread_id` ON `lupo_dialog_messages` (`dialog_thread_id`);

-- Verify the change
-- SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS 
-- WHERE TABLE_SCHEMA = DATABASE() 
--   AND TABLE_NAME = 'lupo_dialog_messages' 
--   AND COLUMN_NAME LIKE 'dialog_thread%';
-- Expected: dialog_thread_id (not dialog_threads_id)

-- ============================================================
-- Verification Queries
-- ============================================================

-- Verify agent_registry_id is now BIGINT
SELECT 
    COLUMN_NAME,
    COLUMN_TYPE,
    IS_NULLABLE,
    COLUMN_KEY,
    EXTRA
FROM INFORMATION_SCHEMA.COLUMNS 
WHERE TABLE_SCHEMA = DATABASE() 
  AND TABLE_NAME = 'lupo_agent_registry' 
  AND COLUMN_NAME = 'agent_registry_id';

-- Verify dialog_thread_id column exists (not dialog_threads_id)
SELECT 
    COLUMN_NAME,
    COLUMN_TYPE,
    IS_NULLABLE
FROM INFORMATION_SCHEMA.COLUMNS 
WHERE TABLE_SCHEMA = DATABASE() 
  AND TABLE_NAME = 'lupo_dialog_messages' 
  AND COLUMN_NAME LIKE 'dialog_thread%';

-- ============================================================
-- PART 3: Remove UNSIGNED from external_event_id
-- ============================================================

-- Change external_event_id from bigint unsigned to bigint (NOT UNSIGNED)
ALTER TABLE `lupo_agent_external_events` 
MODIFY COLUMN `external_event_id` bigint NOT NULL auto_increment;

-- Verify the change
-- SELECT COLUMN_TYPE FROM INFORMATION_SCHEMA.COLUMNS 
-- WHERE TABLE_SCHEMA = DATABASE() 
--   AND TABLE_NAME = 'lupo_agent_external_events' 
--   AND COLUMN_NAME = 'external_event_id';
-- Expected: bigint(20) (NOT bigint(20) unsigned)

-- ============================================================
-- End of Migration
-- ============================================================
