-- Migration: Replace lupo_collection_permissions with polymorphic lupo_permissions table
-- Version: 4.0.3
-- Date: 2026-01-11
-- 
-- This migration replaces the collection-specific permissions table with a polymorphic
-- permissions table that handles permissions for all object types: collections, departments,
-- modules, features, and future objects.
--
-- The new table uses target_type + target_id to determine which object the permission applies to.
-- All permission logic must use lupo_permissions - no separate permission tables per object type.

-- Step 1: Create the new polymorphic permissions table
-- Note: All ID columns use BIGINT NOT UNSIGNED per WOLFIE ID column standard
-- Note: No display widths per SQL Type Doctrine (BIGINT not BIGINT(20))
CREATE TABLE `lupo_permissions` (
  `permission_id` BIGINT NOT NULL AUTO_INCREMENT,
  `target_type` VARCHAR(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Type of object: collection, department, module, feature, etc.',
  `target_id` BIGINT NOT NULL COMMENT 'ID of the target object',
  `user_id` BIGINT DEFAULT NULL COMMENT 'User ID for user-based permissions (NULL for group-based)',
  `group_id` BIGINT DEFAULT NULL COMMENT 'Group ID for group-based permissions (NULL for user-based)',
  `permission` ENUM('read','write','owner') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'read' COMMENT 'Permission level',
  `created_ymdhis` BIGINT NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when permission was created',
  `updated_ymdhis` BIGINT DEFAULT NULL COMMENT 'UTC YYYYMMDDHHMMSS when permission was updated',
  `is_deleted` TINYINT NOT NULL DEFAULT '0' COMMENT '1 = deleted, 0 = active',
  `deleted_ymdhis` BIGINT DEFAULT NULL COMMENT 'UTC YYYYMMDDHHMMSS when permission was deleted',
  PRIMARY KEY (`permission_id`),
  UNIQUE KEY `uniq_target_user` (`target_type`, `target_id`, `user_id`),
  UNIQUE KEY `uniq_target_group` (`target_type`, `target_id`, `group_id`),
  KEY `idx_target` (`target_type`, `target_id`),
  KEY `idx_user` (`user_id`),
  KEY `idx_group` (`group_id`),
  KEY `idx_deleted` (`is_deleted`, `deleted_ymdhis`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Polymorphic permissions table for all object types (collections, departments, modules, features, etc.). Uses target_type + target_id to determine the object.';

-- Step 2: Migrate data from lupo_collection_permissions to lupo_permissions
-- Convert all collection permissions to use target_type='collection'
INSERT INTO `lupo_permissions` (
  `target_type`,
  `target_id`,
  `user_id`,
  `group_id`,
  `permission`,
  `created_ymdhis`,
  `updated_ymdhis`,
  `is_deleted`,
  `deleted_ymdhis`
)
SELECT 
  'collection' AS `target_type`,
  `collection_id` AS `target_id`,
  `user_id`,
  `group_id`,
  `permission`,
  UNIX_TIMESTAMP(NOW()) * 1000000 AS `created_ymdhis`,  -- Use current timestamp if not available
  NULL AS `updated_ymdhis`,
  0 AS `is_deleted`,
  NULL AS `deleted_ymdhis`
FROM `lupo_collection_permissions`
WHERE EXISTS (SELECT 1 FROM `lupo_collections` WHERE `lupo_collections`.`id` = `lupo_collection_permissions`.`collection_id`);

-- Step 3: Drop the old table
-- WARNING: This permanently removes the old table structure
-- Only run this after verifying data migration is successful
DROP TABLE IF EXISTS `lupo_collection_permissions`;

-- Notes:
-- 1. All code using lupo_collection_permissions must be updated to use lupo_permissions
-- 2. Queries must filter by target_type='collection' AND target_id=collection_id
-- 3. The unique indexes allow NULL values, so user-based and group-based permissions
--    are properly separated (one row per user OR group per target object)
-- 4. Application logic must ensure user_id and group_id are mutually exclusive
-- 5. Future object types can be added by simply using different target_type values
