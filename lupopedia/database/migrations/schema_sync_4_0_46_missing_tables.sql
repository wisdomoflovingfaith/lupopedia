-- Schema Synchronization Migration - Version 4.0.46
-- Adds missing tables from TOON file definitions to match schema
-- 
-- Current Status: 111 tables in SQL, 120 TOON files
-- Missing Tables: 2 core schema tables (lupo_actor_collections, lupo_permissions)
-- Migration orchestrator tables (8 tables) are in separate lupopedia_orchestration schema
--
-- Table Budget: 120 current / 180 maximum (60 tables headroom remaining)
--
-- Doctrine Compliance:
-- - No foreign keys
-- - No triggers  
-- - No stored procedures
-- - BIGINT for IDs (not BIGINT(20) UNSIGNED - matches existing pattern)
-- - Soft deletes (is_deleted, deleted_ymdhis)
-- - UTC timestamps (YYYYMMDDHHMMSS format)

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- --------------------------------------------------------

--
-- Table structure for table `lupo_actor_collections`
-- Maps actors (users, groups, agents) to collections with access levels
--

CREATE TABLE IF NOT EXISTS `lupo_actor_collections` (
  `actor_collection_id` bigint NOT NULL,
  `actor_id` bigint NOT NULL COMMENT 'User, group, agent, or persona',
  `collection_id` bigint NOT NULL COMMENT 'Collection the actor has access to',
  `access_level` enum('owner','write','read') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'read',
  `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when granted',
  `updated_ymdhis` bigint DEFAULT NULL COMMENT 'UTC YYYYMMDDHHMMSS when updated',
  `is_deleted` tinyint NOT NULL DEFAULT '0',
  `deleted_ymdhis` bigint DEFAULT NULL COMMENT 'UTC YYYYMMDDHHMMSS when deleted'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Maps actors to collections with access level permissions. Supports many-to-many relationship between actors and collections.';

-- --------------------------------------------------------

--
-- Table structure for table `lupo_permissions`
-- Generic permission system for collections, departments, modules, and features
--

CREATE TABLE IF NOT EXISTS `lupo_permissions` (
  `permission_id` bigint NOT NULL,
  `target_type` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Type of object: collection, department, module, feature, etc.',
  `target_id` bigint NOT NULL COMMENT 'ID of the target object',
  `user_id` bigint DEFAULT NULL COMMENT 'User ID for user-based permissions (NULL for group-based)',
  `group_id` bigint DEFAULT NULL COMMENT 'Group ID for group-based permissions (NULL for user-based)',
  `permission` enum('read','write','owner') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'read' COMMENT 'Permission level',
  `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when permission was created',
  `updated_ymdhis` bigint DEFAULT NULL COMMENT 'UTC YYYYMMDDHHMMSS when permission was updated',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1 = deleted, 0 = active',
  `deleted_ymdhis` bigint DEFAULT NULL COMMENT 'UTC YYYYMMDDHHMMSS when permission was deleted'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Generic permission system supporting user-based and group-based permissions for collections, departments, modules, and features.';

-- --------------------------------------------------------

--
-- Indexes for table `lupo_actor_collections`
-- Only add indexes if table exists and they don't already exist
--

SET @table_exists = (SELECT COUNT(*) FROM information_schema.tables 
  WHERE table_schema = DATABASE() 
  AND table_name = 'lupo_actor_collections');

SET @pk_exists = (SELECT COUNT(*) FROM information_schema.table_constraints 
  WHERE table_schema = DATABASE() 
  AND table_name = 'lupo_actor_collections' 
  AND constraint_type = 'PRIMARY KEY');

SET @idx_actor_exists = (SELECT COUNT(*) FROM information_schema.statistics 
  WHERE table_schema = DATABASE() 
  AND table_name = 'lupo_actor_collections' 
  AND index_name = 'idx_actor');

SET @idx_collection_exists = (SELECT COUNT(*) FROM information_schema.statistics 
  WHERE table_schema = DATABASE() 
  AND table_name = 'lupo_actor_collections' 
  AND index_name = 'idx_collection');

SET @idx_access_level_exists = (SELECT COUNT(*) FROM information_schema.statistics 
  WHERE table_schema = DATABASE() 
  AND table_name = 'lupo_actor_collections' 
  AND index_name = 'idx_access_level');

SET @idx_created_exists = (SELECT COUNT(*) FROM information_schema.statistics 
  WHERE table_schema = DATABASE() 
  AND table_name = 'lupo_actor_collections' 
  AND index_name = 'idx_created_ymdhis');

SET @idx_deleted_exists = (SELECT COUNT(*) FROM information_schema.statistics 
  WHERE table_schema = DATABASE() 
  AND table_name = 'lupo_actor_collections' 
  AND index_name = 'idx_is_deleted');

-- Add primary key only if table exists and PK doesn't exist
SET @sql = IF(@table_exists > 0 AND @pk_exists = 0, 
  'ALTER TABLE `lupo_actor_collections` ADD PRIMARY KEY (`actor_collection_id`)', 
  'SET @skip = 1');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Add indexes only if they don't exist
SET @sql = IF(@table_exists > 0 AND @idx_actor_exists = 0, 
  'ALTER TABLE `lupo_actor_collections` ADD KEY `idx_actor` (`actor_id`)', 
  'SET @skip = 1');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

SET @sql = IF(@table_exists > 0 AND @idx_collection_exists = 0, 
  'ALTER TABLE `lupo_actor_collections` ADD KEY `idx_collection` (`collection_id`)', 
  'SET @skip = 1');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

SET @sql = IF(@table_exists > 0 AND @idx_access_level_exists = 0, 
  'ALTER TABLE `lupo_actor_collections` ADD KEY `idx_access_level` (`access_level`)', 
  'SET @skip = 1');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

SET @sql = IF(@table_exists > 0 AND @idx_created_exists = 0, 
  'ALTER TABLE `lupo_actor_collections` ADD KEY `idx_created_ymdhis` (`created_ymdhis`)', 
  'SET @skip = 1');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

SET @sql = IF(@table_exists > 0 AND @idx_deleted_exists = 0, 
  'ALTER TABLE `lupo_actor_collections` ADD KEY `idx_is_deleted` (`is_deleted`)', 
  'SET @skip = 1');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- --------------------------------------------------------

--
-- Indexes for table `lupo_permissions`
-- Only add indexes if table exists and they don't already exist
--

SET @table_exists_perms = (SELECT COUNT(*) FROM information_schema.tables 
  WHERE table_schema = DATABASE() 
  AND table_name = 'lupo_permissions');

SET @pk_exists_perms = (SELECT COUNT(*) FROM information_schema.table_constraints 
  WHERE table_schema = DATABASE() 
  AND table_name = 'lupo_permissions' 
  AND constraint_type = 'PRIMARY KEY');

SET @uniq_user_exists = (SELECT COUNT(*) FROM information_schema.table_constraints 
  WHERE table_schema = DATABASE() 
  AND table_name = 'lupo_permissions' 
  AND constraint_name = 'uniq_target_user');

SET @uniq_group_exists = (SELECT COUNT(*) FROM information_schema.table_constraints 
  WHERE table_schema = DATABASE() 
  AND table_name = 'lupo_permissions' 
  AND constraint_name = 'uniq_target_group');

SET @idx_target_exists = (SELECT COUNT(*) FROM information_schema.statistics 
  WHERE table_schema = DATABASE() 
  AND table_name = 'lupo_permissions' 
  AND index_name = 'idx_target');

SET @idx_user_exists = (SELECT COUNT(*) FROM information_schema.statistics 
  WHERE table_schema = DATABASE() 
  AND table_name = 'lupo_permissions' 
  AND index_name = 'idx_user');

SET @idx_group_exists = (SELECT COUNT(*) FROM information_schema.statistics 
  WHERE table_schema = DATABASE() 
  AND table_name = 'lupo_permissions' 
  AND index_name = 'idx_group');

SET @idx_deleted_perms_exists = (SELECT COUNT(*) FROM information_schema.statistics 
  WHERE table_schema = DATABASE() 
  AND table_name = 'lupo_permissions' 
  AND index_name = 'idx_deleted');

SET @idx_permission_exists = (SELECT COUNT(*) FROM information_schema.statistics 
  WHERE table_schema = DATABASE() 
  AND table_name = 'lupo_permissions' 
  AND index_name = 'idx_permission');

SET @idx_created_perms_exists = (SELECT COUNT(*) FROM information_schema.statistics 
  WHERE table_schema = DATABASE() 
  AND table_name = 'lupo_permissions' 
  AND index_name = 'idx_created_ymdhis');

-- Add primary key only if table exists and PK doesn't exist
SET @sql = IF(@table_exists_perms > 0 AND @pk_exists_perms = 0, 
  'ALTER TABLE `lupo_permissions` ADD PRIMARY KEY (`permission_id`)', 
  'SET @skip = 1');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Add unique constraints only if they don't exist
SET @sql = IF(@table_exists_perms > 0 AND @uniq_user_exists = 0, 
  'ALTER TABLE `lupo_permissions` ADD UNIQUE KEY `uniq_target_user` (`target_type`,`target_id`,`user_id`)', 
  'SET @skip = 1');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

SET @sql = IF(@table_exists_perms > 0 AND @uniq_group_exists = 0, 
  'ALTER TABLE `lupo_permissions` ADD UNIQUE KEY `uniq_target_group` (`target_type`,`target_id`,`group_id`)', 
  'SET @skip = 1');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Add indexes only if they don't exist
SET @sql = IF(@table_exists_perms > 0 AND @idx_target_exists = 0, 
  'ALTER TABLE `lupo_permissions` ADD KEY `idx_target` (`target_type`,`target_id`)', 
  'SET @skip = 1');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

SET @sql = IF(@table_exists_perms > 0 AND @idx_user_exists = 0, 
  'ALTER TABLE `lupo_permissions` ADD KEY `idx_user` (`user_id`)', 
  'SET @skip = 1');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

SET @sql = IF(@table_exists_perms > 0 AND @idx_group_exists = 0, 
  'ALTER TABLE `lupo_permissions` ADD KEY `idx_group` (`group_id`)', 
  'SET @skip = 1');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

SET @sql = IF(@table_exists_perms > 0 AND @idx_deleted_perms_exists = 0, 
  'ALTER TABLE `lupo_permissions` ADD KEY `idx_deleted` (`is_deleted`,`deleted_ymdhis`)', 
  'SET @skip = 1');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

SET @sql = IF(@table_exists_perms > 0 AND @idx_permission_exists = 0, 
  'ALTER TABLE `lupo_permissions` ADD KEY `idx_permission` (`permission`)', 
  'SET @skip = 1');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

SET @sql = IF(@table_exists_perms > 0 AND @idx_created_perms_exists = 0, 
  'ALTER TABLE `lupo_permissions` ADD KEY `idx_created_ymdhis` (`created_ymdhis`)', 
  'SET @skip = 1');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- --------------------------------------------------------

--
-- AUTO_INCREMENT for table `lupo_actor_collections`
-- Only modify if table exists
--

SET @table_exists_ac = (SELECT COUNT(*) FROM information_schema.tables 
  WHERE table_schema = DATABASE() 
  AND table_name = 'lupo_actor_collections');

SET @sql = IF(@table_exists_ac > 0, 
  'ALTER TABLE `lupo_actor_collections` MODIFY `actor_collection_id` bigint NOT NULL AUTO_INCREMENT', 
  'SET @skip = 1');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

--
-- AUTO_INCREMENT for table `lupo_permissions`
-- Only modify if table exists
--

SET @table_exists_perm = (SELECT COUNT(*) FROM information_schema.tables 
  WHERE table_schema = DATABASE() 
  AND table_name = 'lupo_permissions');

SET @sql = IF(@table_exists_perm > 0, 
  'ALTER TABLE `lupo_permissions` MODIFY `permission_id` bigint NOT NULL AUTO_INCREMENT', 
  'SET @skip = 1');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- --------------------------------------------------------

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
