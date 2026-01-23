-- ======================================================================
-- MIGRATION SQL: Update lupo_ tables for Crafty Syntax Integration
-- Version: 4.0.3
-- Purpose: Apply database updates based on TOON analysis and migration mapping
-- ======================================================================

-- NOTE: This SQL file applies updates to existing lupo_ tables
-- based on the authoritative mapping in CRAFTY_SYNTAX_TO_LUPOPEDIA_STRUCTURED_MAPPING.md
-- and TOON file analysis. Use ONLY for updating existing Lupopedia tables.

-- ======================================================================
-- 1. Update lupo_users table for Crafty Syntax compatibility
-- ======================================================================

-- Add missing columns identified in TOON analysis
ALTER TABLE lupo_users 
ADD COLUMN IF NOT EXISTS `lastaction` bigint DEFAULT 0 COMMENT 'Last action timestamp',
ADD COLUMN IF NOT EXISTS `username` varchar(30) NOT NULL DEFAULT '' COMMENT 'Legacy username field',
ADD COLUMN IF NOT EXISTS `displayname` varchar(42) NOT NULL DEFAULT '' COMMENT 'Display name for operator',
ADD COLUMN IF NOT EXISTS `timezone_offset` decimal(4,2) NOT NULL DEFAULT 0.00 COMMENT 'Offset in hours from UTC',
ADD COLUMN IF NOT EXISTS `auth_provider` varchar(20) COMMENT 'OAuth provider name if using OAuth',
ADD COLUMN IF NOT EXISTS `provider_id` varchar(255) COMMENT 'OAuth provider user ID',
ADD COLUMN IF NOT EXISTS `isonline` char(1) NOT NULL DEFAULT '' COMMENT 'Online status flag',
ADD COLUMN IF NOT EXISTS `isoperator` char(1) NOT NULL DEFAULT 'N' COMMENT 'Operator flag',
ADD COLUMN IF NOT EXISTS `onchannel` int NOT NULL DEFAULT 0 COMMENT 'Current channel ID',
ADD COLUMN IF NOT EXISTS `isadmin` char(1) NOT NULL DEFAULT 'N' COMMENT 'Admin flag',
ADD COLUMN IF NOT EXISTS `department` int NOT NULL DEFAULT 0 COMMENT 'Department ID',
ADD COLUMN IF NOT EXISTS `identity` varchar(255) NOT NULL DEFAULT '' COMMENT 'Identity string',
ADD COLUMN IF NOT EXISTS `status` varchar(30) NOT NULL DEFAULT '' COMMENT 'User status',
ADD COLUMN IF NOT EXISTS `isnamed` char(1) NOT NULL DEFAULT 'N' COMMENT 'Named user flag',
ADD COLUMN IF NOT EXISTS `showedup` bigint COMMENT 'First appearance timestamp',
ADD COLUMN IF NOT EXISTS `email_verified` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Whether email is verified',
ADD COLUMN IF NOT EXISTS `verification_token` varchar(64) COMMENT 'For email verification',
ADD COLUMN IF NOT EXISTS `verification_token_expires` bigint COMMENT 'Expiration timestamp for verification token',
ADD COLUMN IF NOT EXISTS `password_reset_token` varchar(64) COMMENT 'For password reset',
ADD COLUMN IF NOT EXISTS `password_reset_expires` bigint COMMENT 'Expiration timestamp for password reset',
ADD COLUMN IF NOT EXISTS `login_token` varchar(64) COMMENT 'For passwordless login',
ADD COLUMN IF NOT EXISTS `login_token_expires` bigint COMMENT 'Expiration timestamp for login token',
ADD COLUMN IF NOT EXISTS `last_login_at` bigint COMMENT 'Last login timestamp';

-- ======================================================================
-- 2. Update lupo_channels table for Crafty Syntax compatibility
-- ======================================================================

-- Add channel-specific columns for legacy compatibility
ALTER TABLE lupo_channels 
ADD COLUMN IF NOT EXISTS `channel_key` varchar(100) NOT NULL DEFAULT '' COMMENT 'Channel key for legacy compatibility',
ADD COLUMN IF NOT EXISTS `bgcolor` varchar(6) DEFAULT 'CCCCCC' COMMENT 'Background color',
ADD COLUMN IF NOT EXISTS `status_flag` int NOT NULL DEFAULT 1 COMMENT 'Status flag',
ADD COLUMN IF NOT EXISTS `awareness_version` varchar(20) DEFAULT '2026.1.0.0' COMMENT 'Awareness version';

-- Add indexes for performance
CREATE INDEX IF NOT EXISTS `idx_lupo_channels_key` ON lupo_channels(`channel_key`);
CREATE INDEX IF NOT EXISTS `idx_lupo_channels_status` ON lupo_channels(`status_flag`);

-- ======================================================================
-- 3. Update lupo_departments table for Crafty Syntax compatibility
-- ======================================================================

-- Add department-specific columns
ALTER TABLE lupo_departments 
ADD COLUMN IF NOT EXISTS `department_type` varchar(20) NOT NULL DEFAULT 'crafty' COMMENT 'Department type',
ADD COLUMN IF NOT EXISTS `default_actor_id` int NOT NULL DEFAULT 1 COMMENT 'Default actor ID';

-- ======================================================================
-- 4. Update luo_modules table for Crafty Syntax configuration
-- ======================================================================

-- Ensure module_id 1 exists for Crafty Syntax configuration
INSERT IGNORE INTO lupo_modules (module_id, federations_node_id, user_id, group_id, name, slug, color, description, sort_order, properties, published_ymdhis, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis)
VALUES (1, 1, NULL, NULL, 'Crafty Syntax Live Help', 'crafty-syntax-live-help', '4caf50', 'Legacy Crafty Syntax Live Help system configuration', 0, NULL, NULL, 20250101000000, 20250101000000, 0, NULL);

-- ======================================================================
-- 5. Create Crafty Syntax specific tables (if not exists)
-- ======================================================================

-- Auto-invite system
CREATE TABLE IF NOT EXISTS `lupo_crafty_syntax_auto_invite` (
    `crafty_syntax_auto_invite_id` bigint unsigned NOT NULL AUTO_INCREMENT,
    `is_offline` tinyint(1) NOT NULL DEFAULT 0,
    `is_active` tinyint(1) NOT NULL DEFAULT 1,
    `department_id` int NOT NULL DEFAULT 0,
    `message` text,
    `page_url` varchar(255),
    `visits` int NOT NULL DEFAULT 0,
    `referrer_url` varchar(255),
    `invite_type` varchar(50),
    `trigger_seconds` int NOT NULL DEFAULT 0,
    `operator_user_id` int,
    `show_socialpane` tinyint(1) NOT NULL DEFAULT 0,
    `exclude_mobile` tinyint(1) NOT NULL DEFAULT 0,
    `only_mobile` tinyint(1) NOT NULL DEFAULT 0,
    `created_ymdhis` bigint NOT NULL,
    `updated_ymdhis` bigint NOT NULL,
    `is_deleted` tinyint(1) NOT NULL DEFAULT 0,
    `deleted_ymdhis` bigint,
    PRIMARY KEY (`crafty_syntax_auto_invite_id`),
    INDEX `idx_crafty_auto_invite_department` (`department_id`),
    INDEX `idx_crafty_auto_invite_active` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Layer invites
CREATE TABLE IF NOT EXISTS `lupo_crafty_syntax_layer_invites` (
    `layer_name` varchar(100) NOT NULL,
    `image_name` varchar(255),
    `image_map` text,
    `department_name` varchar(100),
    `user_id` int,
    `is_active` tinyint(1) NOT NULL DEFAULT 1,
    `display_count` int NOT NULL DEFAULT 0,
    `click_count` int NOT NULL DEFAULT 0,
    `created_ymdhis` bigint NOT NULL,
    `updated_ymdhis` bigint NOT NULL,
    `is_deleted` tinyint(1) NOT NULL DEFAULT 0,
    `deleted_ymdhis` bigint,
    PRIMARY KEY (`layer_name`),
    INDEX `idx_crafty_layer_invite_department` (`department_name`),
    INDEX `idx_crafty_layer_invite_active` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Leave messages
CREATE TABLE IF NOT EXISTS `lupo_crafty_syntax_leave_message` (
    `crafty_syntax_leave_message_id` bigint unsigned NOT NULL AUTO_INCREMENT,
    `department_id` int NOT NULL DEFAULT 0,
    `email` varchar(255),
    `phone` varchar(50),
    `name` varchar(100),
    `subject` varchar(255),
    `message` text,
    `priority` int NOT NULL DEFAULT 2,
    `session_data` text,
    `form_data` text,
    `ip_address` varchar(45),
    `user_agent` text,
    `status` varchar(20) NOT NULL DEFAULT 'new',
    `assigned_to` int,
    `created_ymdhis` bigint NOT NULL,
    `updated_ymdhis` bigint NOT NULL,
    `is_deleted` tinyint(1) NOT NULL DEFAULT 0,
    `deleted_ymdhis` bigint,
    PRIMARY KEY (`crafty_syntax_leave_message_id`),
    INDEX `idx_crafty_leave_message_department` (`department_id`),
    INDEX `idx_crafty_leave_message_status` (`status`),
    INDEX `idx_crafty_leave_message_created` (`created_ymdhis`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ======================================================================
-- 6. Update existing tables with foreign key relationships
-- ======================================================================

-- Add foreign key for departments in users table
ALTER TABLE lupo_users 
ADD CONSTRAINT IF NOT EXISTS `fk_lupo_users_department` 
FOREIGN KEY (`department`) REFERENCES `lupo_departments`(`department_id`) 
ON DELETE SET NULL ON UPDATE CASCADE;

-- Add foreign key for default_actor in departments
ALTER TABLE lupo_departments 
ADD CONSTRAINT IF NOT EXISTS `fk_lupo_departments_default_actor` 
FOREIGN KEY (`default_actor_id`) REFERENCES `lupo_users`(`user_id`) 
ON DELETE SET NULL ON UPDATE CASCADE;

-- ======================================================================
-- 7. Insert initial data for Crafty Syntax integration
-- ======================================================================

-- Insert default department if not exists
INSERT IGNORE INTO lupo_departments (department_id, federation_node_id, name, description, department_type, default_actor_id, settings_json, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis)
VALUES (1, 1, 'Default Department', 'Default Crafty Syntax department', 'crafty', 1, NULL, 20250101000000, 20250101000000, 0, NULL);

-- ======================================================================
-- 8. Create views for legacy compatibility (optional)
-- ======================================================================

-- Create view for livehelp_users compatibility
CREATE OR REPLACE VIEW `v_livehelp_users` AS
SELECT 
    `user_id`,
    `livehelp_id`,
    `lastaction`,
    `username`,
    `displayname`,
    `password`,
    `timezone_offset`,
    `auth_provider`,
    `provider_id`,
    `isonline`,
    `isoperator`,
    `onchannel`,
    `isadmin`,
    `department`,
    `identity`,
    `status`,
    `isnamed`,
    `showedup`,
    `email`,
    `email_verified`,
    `verification_token`,
    `verification_token_expires`,
    `password_reset_token`,
    `password_reset_expires`,
    `login_token`,
    `login_token_expires`,
    `last_login_at`,
    `created_ymdhis`,
    `updated_ymdhis`,
    `is_deleted`,
    `deleted_ymdhis`
FROM `lupo_users`;

-- ======================================================================
-- 9. Final validation and cleanup
-- ======================================================================

-- Update table comments
ALTER TABLE lupo_users COMMENT = 'User accounts table with Crafty Syntax compatibility fields';
ALTER TABLE lupo_channels COMMENT = 'Channel management with legacy Crafty Syntax support';
ALTER TABLE lupo_departments COMMENT = 'Department management with Crafty Syntax integration';
ALTER TABLE lupo_modules COMMENT = 'System modules including Crafty Syntax configuration';

-- ======================================================================
-- MIGRATION COMPLETE
-- ======================================================================

-- This migration script updates the Lupopedia database to support
-- Crafty Syntax Live Help integration while maintaining full compatibility
-- with the existing Lupopedia architecture.

-- All changes are based on:
-- 1. TOON file analysis for schema validation
-- 2. CRAFTY_SYNTAX_TO_LUPOPEDIA_STRUCTURED_MAPPING.md for table mappings
-- 3. HERITAGE-SAFE MODE preservation requirements

-- Run this script after the main migration to ensure all necessary
-- columns and tables exist for Crafty Syntax functionality.
