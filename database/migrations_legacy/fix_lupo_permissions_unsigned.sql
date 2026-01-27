-- Migration: Fix lupo_permissions ID columns to remove UNSIGNED
-- Version: 4.0.3
-- Date: 2026-01-11
-- 
-- This migration removes UNSIGNED from ID columns in lupo_permissions table
-- to comply with WOLFIE ID column standard (all IDs must be BIGINT NOT UNSIGNED)
--
-- Note: Only run this if lupo_permissions table was created with UNSIGNED columns
-- (e.g., if created before the migration SQL was corrected)

-- Fix permission_id (primary key)
ALTER TABLE `lupo_permissions`
MODIFY COLUMN `permission_id` BIGINT NOT NULL AUTO_INCREMENT;

-- Fix target_id (foreign key reference)
ALTER TABLE `lupo_permissions`
MODIFY COLUMN `target_id` BIGINT NOT NULL COMMENT 'ID of the target object';

-- Fix user_id (foreign key reference)
ALTER TABLE `lupo_permissions`
MODIFY COLUMN `user_id` BIGINT DEFAULT NULL COMMENT 'User ID for user-based permissions (NULL for group-based)';

-- Fix group_id (foreign key reference)
ALTER TABLE `lupo_permissions`
MODIFY COLUMN `group_id` BIGINT DEFAULT NULL COMMENT 'Group ID for group-based permissions (NULL for user-based)';

-- Verify the changes
-- SELECT COLUMN_NAME, COLUMN_TYPE 
-- FROM INFORMATION_SCHEMA.COLUMNS 
-- WHERE TABLE_SCHEMA = DATABASE() 
--   AND TABLE_NAME = 'lupo_permissions' 
--   AND COLUMN_NAME IN ('permission_id', 'target_id', 'user_id', 'group_id');
-- Expected: All columns should be bigint (NOT bigint unsigned)
