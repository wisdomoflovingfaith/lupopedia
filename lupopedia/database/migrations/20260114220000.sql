-- ============================================================================
-- Lupopedia Database Migration
-- Version: 4.0.16
-- Timestamp: 2026-01-14 22:00:00 UTC
-- Channel: lupopedia 4.0.16 - channels/documentation/database refinement
-- ============================================================================
--
-- Purpose: Schema refinement for channel and dialog system tables
--
-- This migration addresses 7 schema issues identified during documentation
-- review for version 4.0.16:
--
-- 1. lupo_channels — Add unique constraint on channel_key per federation_node_id
-- 2. lupo_dialog_messages — Fix incorrect comment on from_actor_id column
-- 3. lupo_dialog_threads — Rename misnamed index idx_agent_name to idx_created_by_actor
-- 4. lupo_dialog_message_bodies — Add unique constraint on dialog_message_id
-- 5. lupo_dialog_message_bodies — Fix nullability inconsistency on metadata_json
-- 6. lupo_dialog_message_bodies — Add soft delete fields (deleted_flag, deleted_ymdhis)
-- 7. lupo_actor_channels — Fix default value for start_date (NULL instead of 0)
--
-- All changes are non-destructive and follow Lupopedia doctrine:
-- - No foreign keys
-- - No triggers
-- - No stored procedures
-- - Application logic first, database logic second
--
-- ============================================================================

-- ----------------------------------------------------------------------------
-- PATCH 1: lupo_channels — Add unique constraint on channel_key per federation_node_id
-- ----------------------------------------------------------------------------
-- Ensures that channel_key is unique within each federation node.
-- This prevents duplicate channel slugs within the same node while allowing
-- the same channel_key across different federation nodes.

ALTER TABLE `lupo_channels`
ADD UNIQUE KEY `unq_channel_key_per_node` (`channel_key`, `federation_node_id`);

-- ----------------------------------------------------------------------------
-- PATCH 2: lupo_dialog_messages — Fix incorrect comment on from_actor_id column
-- ----------------------------------------------------------------------------
-- Current comment incorrectly says "recipient" when it should say "sender".
-- This column stores the actor_id of the message sender, not the recipient.

ALTER TABLE `lupo_dialog_messages`
MODIFY COLUMN `from_actor_id` bigint COMMENT 'Actor ID of the message sender';

-- ----------------------------------------------------------------------------
-- PATCH 3: lupo_dialog_threads — Rename misnamed index idx_agent_name to idx_created_by_actor
-- ----------------------------------------------------------------------------
-- The index name idx_agent_name is misleading because it indexes created_by_actor_id,
-- not an agent name. Renaming to idx_created_by_actor for clarity.

ALTER TABLE `lupo_dialog_threads`
DROP INDEX `idx_agent_name`;

ALTER TABLE `lupo_dialog_threads`
ADD INDEX `idx_created_by_actor` (`created_by_actor_id`);

-- ----------------------------------------------------------------------------
-- PATCH 4: lupo_dialog_message_bodies — Add unique constraint on dialog_message_id
-- ----------------------------------------------------------------------------
-- Enforces one-to-one relationship between lupo_dialog_messages and lupo_dialog_message_bodies.
-- Each dialog_message_id can only have one corresponding message body entry.

ALTER TABLE `lupo_dialog_message_bodies`
ADD UNIQUE KEY `unq_dialog_message_id` (`dialog_message_id`);

-- ----------------------------------------------------------------------------
-- PATCH 5: lupo_dialog_message_bodies — Fix nullability inconsistency on metadata_json
-- ----------------------------------------------------------------------------
-- Parent table lupo_dialog_messages allows NULL for metadata_json.
-- Child table lupo_dialog_message_bodies should match this behavior.

ALTER TABLE `lupo_dialog_message_bodies`
MODIFY COLUMN `metadata_json` json NULL COMMENT 'Additional message metadata';

-- ----------------------------------------------------------------------------
-- PATCH 6: lupo_dialog_message_bodies — Add soft delete fields
-- ----------------------------------------------------------------------------
-- Adds deleted_flag and deleted_ymdhis columns to support soft delete pattern
-- consistent with other Lupopedia tables.

ALTER TABLE `lupo_dialog_message_bodies`
ADD COLUMN `deleted_flag` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Soft delete flag (1=deleted, 0=active)' AFTER `updated_ymdhis`;

ALTER TABLE `lupo_dialog_message_bodies`
ADD COLUMN `deleted_ymdhis` bigint NULL COMMENT 'Deletion timestamp (YYYYMMDDHHMMSS)' AFTER `deleted_flag`;

-- ----------------------------------------------------------------------------
-- PATCH 7: lupo_actor_channels — Fix default value for start_date
-- ----------------------------------------------------------------------------
-- start_date should allow NULL instead of defaulting to 0 to properly represent
-- "no start date set" vs "started at timestamp 0".

ALTER TABLE `lupo_actor_channels`
MODIFY COLUMN `start_date` bigint NULL DEFAULT NULL COMMENT 'Timestamp when actor joined the channel (YYYYMMDDHHMMSS)';

-- ============================================================================
-- END OF MIGRATION
-- ============================================================================
--
-- Post-Migration Steps:
-- 1. Run this SQL in phpMyAdmin or MySQL client
-- 2. Regenerate TOON files using: python database/generate_toon_files.py
-- 3. Verify schema changes in database/toon_data/*.toon files
-- 4. Update documentation if needed
--
-- ============================================================================
