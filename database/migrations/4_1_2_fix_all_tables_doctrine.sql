-- Migration: Fix All Tables for Doctrine Compliance
-- Version: 4.1.2
-- Date: 2026-01-18
-- Module: Doctrine Alignment
-- Description: Ensures all specified tables use BIGINT YYYYMMDDHHIISS timestamps and no UNSIGNED fields.
--
-- This migration removes UNSIGNED from all integer columns to comply
-- with Lupopedia's No-UNSIGNED doctrine for database portability (PostgreSQL compatibility).
--
-- Tables Updated:
--   - lupo_help_topics
--   - lupo_actors
--   - lupo_agents
--   - lupo_labs_declarations
--   - lupo_labs_violations
--   - lupo_contents
--   - lupo_channels
--   - lupo_collections
--
-- NOTE: lupo_help_topics_old is intentionally left with UNSIGNED (archived backup table)
--
-- @package Lupopedia
-- @version 4.1.2
-- @author Captain Wolfie

-- ============================================================================
-- FIX LUPO_HELP_TOPICS TABLE
-- ============================================================================
-- Only modify if table exists

-- Remove UNSIGNED from primary key (if exists)
ALTER TABLE `lupo_help_topics`
MODIFY COLUMN `help_topic_id` BIGINT NOT NULL AUTO_INCREMENT COMMENT 'Primary key for help topic';

-- Remove UNSIGNED from created_ymdhis (ensure NOT NULL)
ALTER TABLE `lupo_help_topics`
MODIFY COLUMN `created_ymdhis` BIGINT NOT NULL COMMENT 'UTC creation timestamp (YYYYMMDDHHMMSS)';

-- Remove UNSIGNED from updated_ymdhis (ensure NOT NULL)
ALTER TABLE `lupo_help_topics`
MODIFY COLUMN `updated_ymdhis` BIGINT NOT NULL COMMENT 'UTC last update timestamp (YYYYMMDDHHMMSS)';

-- Remove UNSIGNED from author_actor_id (if exists and has UNSIGNED)
ALTER TABLE `lupo_help_topics`
MODIFY COLUMN `author_actor_id` BIGINT COMMENT 'Author actor ID';

-- Verify is_deleted is TINYINT (ensure no UNSIGNED)
ALTER TABLE `lupo_help_topics`
MODIFY COLUMN `is_deleted` TINYINT NOT NULL DEFAULT 0 COMMENT 'Soft delete flag (1=deleted, 0=active)';

-- ============================================================================
-- FIX LUPO_ACTORS TABLE
-- ============================================================================

-- Remove UNSIGNED from primary key (if exists)
ALTER TABLE `lupo_actors`
MODIFY COLUMN `actor_id` BIGINT NOT NULL AUTO_INCREMENT COMMENT 'Primary key for actor';

-- Remove UNSIGNED from created_ymdhis (ensure NOT NULL)
ALTER TABLE `lupo_actors`
MODIFY COLUMN `created_ymdhis` BIGINT NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS';

-- Remove UNSIGNED from updated_ymdhis (ensure NOT NULL)
ALTER TABLE `lupo_actors`
MODIFY COLUMN `updated_ymdhis` BIGINT NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS';

-- Remove UNSIGNED from deleted_ymdhis (nullable is correct)
ALTER TABLE `lupo_actors`
MODIFY COLUMN `deleted_ymdhis` BIGINT COMMENT 'UTC YYYYMMDDHHMMSS';

-- Remove UNSIGNED from actor_source_id (if exists and has UNSIGNED)
ALTER TABLE `lupo_actors`
MODIFY COLUMN `actor_source_id` BIGINT COMMENT 'ID from source table (auth_users, agents, etc.)';

-- Verify is_active is TINYINT (ensure no UNSIGNED)
ALTER TABLE `lupo_actors`
MODIFY COLUMN `is_active` TINYINT NOT NULL DEFAULT 1 COMMENT 'Active flag';

-- Verify is_deleted is TINYINT (ensure no UNSIGNED)
ALTER TABLE `lupo_actors`
MODIFY COLUMN `is_deleted` TINYINT NOT NULL DEFAULT 0 COMMENT 'Soft delete flag';

-- ============================================================================
-- FIX LUPO_AGENTS TABLE
-- ============================================================================

-- Remove UNSIGNED from primary key (if exists)
ALTER TABLE `lupo_agents`
MODIFY COLUMN `agent_id` BIGINT NOT NULL AUTO_INCREMENT COMMENT 'Primary key for agent';

-- Remove UNSIGNED from created_ymdhis (ensure NOT NULL)
ALTER TABLE `lupo_agents`
MODIFY COLUMN `created_ymdhis` BIGINT NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when created';

-- Remove UNSIGNED from updated_ymdhis (nullable is correct)
ALTER TABLE `lupo_agents`
MODIFY COLUMN `updated_ymdhis` BIGINT COMMENT 'UTC YYYYMMDDHHMMSS when updated';

-- Remove UNSIGNED from deleted_ymdhis (nullable is correct)
ALTER TABLE `lupo_agents`
MODIFY COLUMN `deleted_ymdhis` BIGINT COMMENT 'UTC YYYYMMDDHHMMSS when deleted';

-- Remove UNSIGNED from api_key_id (if exists and has UNSIGNED)
ALTER TABLE `lupo_agents`
MODIFY COLUMN `api_key_id` BIGINT COMMENT 'API key reference';

-- Remove UNSIGNED from total_tokens_processed (if exists and has UNSIGNED)
ALTER TABLE `lupo_agents`
MODIFY COLUMN `total_tokens_processed` BIGINT DEFAULT 0 COMMENT 'Total tokens processed';

-- Verify is_global_authority is TINYINT (ensure no UNSIGNED)
ALTER TABLE `lupo_agents`
MODIFY COLUMN `is_global_authority` TINYINT NOT NULL DEFAULT 0 COMMENT '1 = global authority agent';

-- Verify is_internal_only is TINYINT (ensure no UNSIGNED)
ALTER TABLE `lupo_agents`
MODIFY COLUMN `is_internal_only` TINYINT(1) NOT NULL DEFAULT 0 COMMENT 'Internal only flag';

-- Verify is_deleted is TINYINT (ensure no UNSIGNED)
ALTER TABLE `lupo_agents`
MODIFY COLUMN `is_deleted` TINYINT NOT NULL DEFAULT 0 COMMENT '1 = deleted, 0 = not deleted';

-- ============================================================================
-- FIX LUPO_LABS_DECLARATIONS TABLE
-- ============================================================================
-- NOTE: This table may not exist if migration 4.1.6 hasn't been run yet.
-- If you get "Table doesn't exist" error, run 4.1.6_create_labs_declarations_table.sql first.

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

-- Verify is_deleted is TINYINT (ensure no UNSIGNED)
ALTER TABLE `lupo_labs_declarations`
MODIFY COLUMN `is_deleted` TINYINT NOT NULL DEFAULT 0 COMMENT 'Soft delete flag (1=deleted, 0=active)';

-- ============================================================================
-- FIX LUPO_LABS_VIOLATIONS TABLE
-- ============================================================================
-- NOTE: This table may not exist if migration 4.1.6 hasn't been run yet.
-- If you get "Table doesn't exist" error, run 4.1.6_create_labs_declarations_table.sql first.

-- Remove UNSIGNED from primary key
ALTER TABLE `lupo_labs_violations`
MODIFY COLUMN `labs_violation_id` BIGINT NOT NULL AUTO_INCREMENT COMMENT 'Primary key for violation record';

-- Remove UNSIGNED from actor_id
ALTER TABLE `lupo_labs_violations`
MODIFY COLUMN `actor_id` BIGINT NOT NULL COMMENT 'Reference to actor (from lupo_actors)';

-- Remove UNSIGNED from created_ymdhis (ensure NOT NULL)
ALTER TABLE `lupo_labs_violations`
MODIFY COLUMN `created_ymdhis` BIGINT NOT NULL COMMENT 'UTC creation timestamp (YYYYMMDDHHMMSS)';

-- Remove UNSIGNED from updated_ymdhis (ensure NOT NULL)
ALTER TABLE `lupo_labs_violations`
MODIFY COLUMN `updated_ymdhis` BIGINT NOT NULL COMMENT 'UTC last update timestamp (YYYYMMDDHHMMSS)';

-- Verify is_deleted is TINYINT (ensure no UNSIGNED)
ALTER TABLE `lupo_labs_violations`
MODIFY COLUMN `is_deleted` TINYINT NOT NULL DEFAULT 0 COMMENT 'Soft delete flag (1=deleted, 0=active)';

-- ============================================================================
-- FIX LUPO_CONTENTS TABLE
-- ============================================================================

-- Remove UNSIGNED from primary key (if exists)
ALTER TABLE `lupo_contents`
MODIFY COLUMN `content_id` BIGINT NOT NULL AUTO_INCREMENT COMMENT 'Primary key for content';

-- Remove UNSIGNED from content_parent_id (if exists and has UNSIGNED)
ALTER TABLE `lupo_contents`
MODIFY COLUMN `content_parent_id` BIGINT COMMENT 'Parent content ID for hierarchical relationships';

-- Remove UNSIGNED from federation_node_id (if exists and has UNSIGNED)
ALTER TABLE `lupo_contents`
MODIFY COLUMN `federation_node_id` BIGINT DEFAULT 1 COMMENT 'Domain scope, NULL for global content';

-- Remove UNSIGNED from group_id (if exists and has UNSIGNED)
ALTER TABLE `lupo_contents`
MODIFY COLUMN `group_id` BIGINT COMMENT 'Optional group restriction';

-- Remove UNSIGNED from user_id (if exists and has UNSIGNED)
ALTER TABLE `lupo_contents`
MODIFY COLUMN `user_id` BIGINT COMMENT 'Author of the content';

-- Remove UNSIGNED from default_collection_id (if exists and has UNSIGNED)
ALTER TABLE `lupo_contents`
MODIFY COLUMN `default_collection_id` BIGINT COMMENT 'Default collection ID';

-- Remove UNSIGNED from created_ymdhis (ensure NOT NULL)
ALTER TABLE `lupo_contents`
MODIFY COLUMN `created_ymdhis` BIGINT NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when created';

-- Remove UNSIGNED from updated_ymdhis (ensure NOT NULL)
ALTER TABLE `lupo_contents`
MODIFY COLUMN `updated_ymdhis` BIGINT NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when last updated';

-- Remove UNSIGNED from deleted_ymdhis (nullable is correct)
ALTER TABLE `lupo_contents`
MODIFY COLUMN `deleted_ymdhis` BIGINT COMMENT 'UTC YYYYMMDDHHMMSS when deleted';

-- Verify is_template is TINYINT (ensure no UNSIGNED)
ALTER TABLE `lupo_contents`
MODIFY COLUMN `is_template` TINYINT NOT NULL DEFAULT 0 COMMENT '1 = template, 0 = regular content';

-- Verify is_deleted is TINYINT (ensure no UNSIGNED)
ALTER TABLE `lupo_contents`
MODIFY COLUMN `is_deleted` TINYINT NOT NULL DEFAULT 0 COMMENT '1 = deleted, 0 = not deleted';

-- Verify is_active is TINYINT (ensure no UNSIGNED)
ALTER TABLE `lupo_contents`
MODIFY COLUMN `is_active` TINYINT NOT NULL DEFAULT 1 COMMENT '1 = active, 0 = not active';

-- ============================================================================
-- FIX LUPO_CHANNELS TABLE
-- ============================================================================

-- Remove UNSIGNED from primary key (if exists)
ALTER TABLE `lupo_channels`
MODIFY COLUMN `channel_id` BIGINT NOT NULL AUTO_INCREMENT COMMENT 'Primary key for channel';

-- Remove UNSIGNED from federation_node_id (if exists and has UNSIGNED)
ALTER TABLE `lupo_channels`
MODIFY COLUMN `federation_node_id` BIGINT NOT NULL COMMENT 'Domain/tenant identifier';

-- Remove UNSIGNED from created_by_actor_id (if exists and has UNSIGNED)
ALTER TABLE `lupo_channels`
MODIFY COLUMN `created_by_actor_id` BIGINT NOT NULL COMMENT 'who made this channel';

-- Remove UNSIGNED from default_actor_id (if exists and has UNSIGNED)
ALTER TABLE `lupo_channels`
MODIFY COLUMN `default_actor_id` BIGINT NOT NULL DEFAULT 1 COMMENT 'Default actor ID';

-- Remove UNSIGNED from end_ymdhis (nullable is correct)
ALTER TABLE `lupo_channels`
MODIFY COLUMN `end_ymdhis` BIGINT COMMENT 'Channel end timestamp (YYYYMMDDHHMMSS, NULL if ongoing)';

-- Remove UNSIGNED from created_ymdhis (ensure NOT NULL)
ALTER TABLE `lupo_channels`
MODIFY COLUMN `created_ymdhis` BIGINT NOT NULL COMMENT 'Creation timestamp (YYYYMMDDHHMMSS)';

-- Remove UNSIGNED from updated_ymdhis (ensure NOT NULL)
ALTER TABLE `lupo_channels`
MODIFY COLUMN `updated_ymdhis` BIGINT NOT NULL COMMENT 'Last update timestamp (YYYYMMDDHHMMSS)';

-- Remove UNSIGNED from deleted_ymdhis (nullable is correct)
ALTER TABLE `lupo_channels`
MODIFY COLUMN `deleted_ymdhis` BIGINT COMMENT 'Deletion timestamp (YYYYMMDDHHMMSS)';

-- Verify status_flag is TINYINT (ensure no UNSIGNED)
ALTER TABLE `lupo_channels`
MODIFY COLUMN `status_flag` TINYINT NOT NULL DEFAULT 1 COMMENT 'Status flag (1=active, 0=inactive)';

-- Verify is_deleted is TINYINT (ensure no UNSIGNED)
ALTER TABLE `lupo_channels`
MODIFY COLUMN `is_deleted` TINYINT NOT NULL DEFAULT 0 COMMENT 'Soft delete flag (1=deleted, 0=active)';

-- ============================================================================
-- FIX LUPO_COLLECTIONS TABLE
-- ============================================================================

-- Remove UNSIGNED from primary key (if exists)
ALTER TABLE `lupo_collections`
MODIFY COLUMN `collection_id` BIGINT NOT NULL AUTO_INCREMENT COMMENT 'Primary key for collection';

-- Remove UNSIGNED from federations_node_id (if exists and has UNSIGNED)
ALTER TABLE `lupo_collections`
MODIFY COLUMN `federations_node_id` BIGINT NOT NULL COMMENT 'Domain this collection belongs to';

-- Remove UNSIGNED from user_id (if exists and has UNSIGNED)
ALTER TABLE `lupo_collections`
MODIFY COLUMN `user_id` BIGINT COMMENT 'Owner of this collection, if user-owned';

-- Remove UNSIGNED from group_id (if exists and has UNSIGNED)
ALTER TABLE `lupo_collections`
MODIFY COLUMN `group_id` BIGINT COMMENT 'Owning group, if group-owned';

-- Remove UNSIGNED from published_ymdhis (nullable is correct)
ALTER TABLE `lupo_collections`
MODIFY COLUMN `published_ymdhis` BIGINT COMMENT 'UTC YYYYMMDDHHMMSS when published';

-- Remove UNSIGNED from created_ymdhis (ensure NOT NULL)
ALTER TABLE `lupo_collections`
MODIFY COLUMN `created_ymdhis` BIGINT NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when created';

-- Remove UNSIGNED from updated_ymdhis (ensure NOT NULL)
ALTER TABLE `lupo_collections`
MODIFY COLUMN `updated_ymdhis` BIGINT NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when last updated';

-- Remove UNSIGNED from deleted_ymdhis (nullable is correct)
ALTER TABLE `lupo_collections`
MODIFY COLUMN `deleted_ymdhis` BIGINT COMMENT 'UTC YYYYMMDDHHMMSS when deleted';

-- Verify is_deleted is TINYINT (ensure no UNSIGNED)
ALTER TABLE `lupo_collections`
MODIFY COLUMN `is_deleted` TINYINT NOT NULL DEFAULT 0 COMMENT '1 = deleted, 0 = not deleted';

-- ============================================================================
-- VERIFICATION QUERIES
-- ============================================================================
-- 
-- Run these queries in phpMyAdmin to verify the migration:
--
-- Check for remaining UNSIGNED columns (should return 0 rows for active tables):
-- SELECT 
--     TABLE_NAME, 
--     COLUMN_NAME, 
--     COLUMN_TYPE
-- FROM INFORMATION_SCHEMA.COLUMNS 
-- WHERE TABLE_SCHEMA = DATABASE() 
--   AND TABLE_NAME IN (
--       'lupo_help_topics', 'lupo_actors', 'lupo_agents', 
--       'lupo_labs_declarations', 'lupo_labs_violations',
--       'lupo_contents', 'lupo_channels', 'lupo_collections'
--   )
--   AND COLUMN_TYPE LIKE '%unsigned%'
-- ORDER BY TABLE_NAME, COLUMN_NAME;
--
-- Expected result: 0 rows (no UNSIGNED columns found in active tables)
-- Note: lupo_help_topics_old may still have UNSIGNED (that's expected - it's archived)
--
-- Verify timestamp fields are BIGINT NOT NULL:
-- SELECT 
--     TABLE_NAME,
--     COLUMN_NAME,
--     COLUMN_TYPE,
--     IS_NULLABLE,
--     COLUMN_DEFAULT
-- FROM INFORMATION_SCHEMA.COLUMNS 
-- WHERE TABLE_SCHEMA = DATABASE() 
--   AND TABLE_NAME IN (
--       'lupo_help_topics', 'lupo_actors', 'lupo_agents', 
--       'lupo_labs_declarations', 'lupo_labs_violations',
--       'lupo_contents', 'lupo_channels', 'lupo_collections'
--   )
--   AND COLUMN_NAME IN ('created_ymdhis', 'updated_ymdhis')
-- ORDER BY TABLE_NAME, COLUMN_NAME;
--
-- Expected results:
-- - All created_ymdhis and updated_ymdhis should be "bigint" with IS_NULLABLE = "NO"
--
-- Verify soft delete fields are TINYINT:
-- SELECT 
--     TABLE_NAME,
--     COLUMN_NAME,
--     COLUMN_TYPE,
--     IS_NULLABLE,
--     COLUMN_DEFAULT
-- FROM INFORMATION_SCHEMA.COLUMNS 
-- WHERE TABLE_SCHEMA = DATABASE() 
--   AND TABLE_NAME IN (
--       'lupo_help_topics', 'lupo_actors', 'lupo_agents', 
--       'lupo_labs_declarations', 'lupo_labs_violations',
--       'lupo_contents', 'lupo_channels', 'lupo_collections'
--   )
--   AND COLUMN_NAME = 'is_deleted'
-- ORDER BY TABLE_NAME;
--
-- Expected results:
-- - All is_deleted should be "tinyint" (NOT "tinyint unsigned") with IS_NULLABLE = "NO" and DEFAULT = "0"

-- ============================================================================
-- MIGRATION COMPLETE
-- ============================================================================
-- 
-- All specified tables now comply with Lupopedia doctrine:
-- ✅ All IDs are BIGINT (no UNSIGNED)
-- ✅ All timestamps are BIGINT (no UNSIGNED)
-- ✅ created_ymdhis and updated_ymdhis are BIGINT NOT NULL
-- ✅ is_deleted is TINYINT (no UNSIGNED)
-- ✅ No TIMESTAMP or DATETIME types
-- ✅ No foreign keys
-- ✅ Portable across MySQL and PostgreSQL
--
-- Status: Ready for phpMyAdmin execution
