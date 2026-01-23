-- ============================================================================
-- Crafty Syntax 3.8.0 - Database Migration
-- ============================================================================
-- Migration: 0001
-- Description: Create livehelp master table and add livehelp_id to all tables
-- Date: 2025-11-18
-- Author: Captain WOLFIE (Eric Robin Gerdes)
-- ============================================================================
-- PURPOSE:
-- - Create master 'livehelp' table for multi-instance support (serves as agents table in LUPOPEDIA)
-- - Add 'livehelp_id' column to all 34 original livehelp_* tables
-- - Enable data isolation between instances
-- - Maintain backward compatibility (default livehelp_id = 1)
-- - Add agent/channel/DNA structure columns for LUPOPEDIA integration
-- ============================================================================

-- ============================================================================
-- STEP 1: Create livehelp Master Table
-- ============================================================================

CREATE TABLE IF NOT EXISTS `livehelp` (
  `livehelp_id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Instance ID - Primary Key (this IS the master table)',
  `agent_id` BIGINT(20) UNSIGNED NOT NULL DEFAULT 1 COMMENT 'Agent ID (000-999) - CRITICAL: Direct mapping to channel number',
  `agent_name` VARCHAR(255) NOT NULL DEFAULT 'unknown' COMMENT 'Agent name (e.g., WOLFIE, CAPTAIN, SECURITY, HELP)',
  `channel_id` BIGINT(20) UNSIGNED NOT NULL DEFAULT 1 COMMENT 'Primary channel ID (Agent ID = Channel Number)',
  `agent_type` ENUM('primary', 'secondary', 'coordinator', 'specialized') DEFAULT 'primary' COMMENT 'Agent type',
  `status` ENUM('active', 'inactive', 'maintenance') DEFAULT 'active' COMMENT 'Agent status',
  `dna_string` VARCHAR(255) DEFAULT NULL COMMENT 'Agent DNA string format: channel-agent_name-DNA_bases (e.g., 007-unknown-ATCG 001-unknown-TTAA)',
  `dna_sequence` TEXT DEFAULT NULL COMMENT 'Full genetic code - biological-inspired genome (e.g., "ATGCAGCTAGCT..." or JSON gene-tagged format like "GREET:friendly|HUMOR:80|SPEED:96")',
  `generation` INT UNSIGNED NOT NULL DEFAULT 1 COMMENT 'Evolution generation (increments on reproduction/mutation)',
  `birth_date` DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT 'When this agent was created/born',
  `fitness_score` DECIMAL(8,4) DEFAULT 0.0000 COMMENT 'Performance score - how well this agent performs (0.0000-9999.9999)',
  `capabilities` LONGTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'JSON capabilities',
  `metadata` LONGTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'JSON metadata',
  `version` VARCHAR(25) NOT NULL DEFAULT '3.8.0' COMMENT 'Crafty Syntax version',
  `config` JSON NULL COMMENT 'Instance configuration',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` TIMESTAMP NULL DEFAULT NULL COMMENT 'Soft delete',
  PRIMARY KEY (`livehelp_id`),
  KEY `agent_id` (`agent_id`),
  KEY `agent_name` (`agent_name`),
  KEY `channel_id` (`channel_id`),
  KEY `agent_type` (`agent_type`),
  KEY `status` (`status`),
  KEY `generation` (`generation`),
  KEY `fitness_score` (`fitness_score`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================================
-- STEP 2: Insert Default Instance
-- ============================================================================

INSERT INTO `livehelp` (`livehelp_id`, `agent_id`, `agent_name`, `channel_id`, `agent_type`, `status`, `dna_string`, `version`) 
VALUES (1, 1, 'DEFAULT', 1, 'primary', 'active', '001_unknown_ACGT', '3.8.0')
ON DUPLICATE KEY UPDATE `agent_name` = 'DEFAULT', `status` = 'active', `version` = '3.8.0';

-- ============================================================================
-- STEP 3: Add livehelp_id Column to All livehelp_* Tables
-- ============================================================================

-- Core Chat Tables (5 tables)
ALTER TABLE `livehelp_messages` 
ADD COLUMN `livehelp_id` BIGINT(20) UNSIGNED NOT NULL DEFAULT 1 AFTER `id_num`,
ADD INDEX `livehelp_id` (`livehelp_id`);

ALTER TABLE `livehelp_users` 
ADD COLUMN `livehelp_id` BIGINT(20) UNSIGNED NOT NULL DEFAULT 1 AFTER `user_id`,
ADD INDEX `livehelp_id` (`livehelp_id`);

ALTER TABLE `livehelp_operator_channels` 
ADD COLUMN `livehelp_id` BIGINT(20) UNSIGNED NOT NULL DEFAULT 1 AFTER `id`,
ADD INDEX `livehelp_id` (`livehelp_id`);

ALTER TABLE `livehelp_channels` 
ADD COLUMN `livehelp_id` BIGINT(20) UNSIGNED NOT NULL DEFAULT 1 AFTER `id`,
ADD INDEX `livehelp_id` (`livehelp_id`);

ALTER TABLE `livehelp_transcripts` 
ADD COLUMN `livehelp_id` BIGINT(20) UNSIGNED NOT NULL DEFAULT 1 AFTER `recno`,
ADD INDEX `livehelp_id` (`livehelp_id`);

-- Routing & Organization Tables (3 tables)
ALTER TABLE `livehelp_departments` 
ADD COLUMN `livehelp_id` BIGINT(20) UNSIGNED NOT NULL DEFAULT 1 AFTER `recno`,
ADD INDEX `livehelp_id` (`livehelp_id`);

ALTER TABLE `livehelp_operator_departments` 
ADD COLUMN `livehelp_id` BIGINT(20) UNSIGNED NOT NULL DEFAULT 1 AFTER `recno`,
ADD INDEX `livehelp_id` (`livehelp_id`);

ALTER TABLE `livehelp_websites` 
ADD COLUMN `livehelp_id` BIGINT(20) UNSIGNED NOT NULL DEFAULT 1 AFTER `id`,
ADD INDEX `livehelp_id` (`livehelp_id`);

-- Visitor Tracking Tables (10 tables)
ALTER TABLE `livehelp_visit_track` 
ADD COLUMN `livehelp_id` BIGINT(20) UNSIGNED NOT NULL DEFAULT 1 AFTER `recno`,
ADD INDEX `livehelp_id` (`livehelp_id`);

ALTER TABLE `livehelp_identity_daily` 
ADD COLUMN `livehelp_id` BIGINT(20) UNSIGNED NOT NULL DEFAULT 1 AFTER `id`,
ADD INDEX `livehelp_id` (`livehelp_id`);

ALTER TABLE `livehelp_identity_monthly` 
ADD COLUMN `livehelp_id` BIGINT(20) UNSIGNED NOT NULL DEFAULT 1 AFTER `id`,
ADD INDEX `livehelp_id` (`livehelp_id`);

ALTER TABLE `livehelp_visits_daily` 
ADD COLUMN `livehelp_id` BIGINT(20) UNSIGNED NOT NULL DEFAULT 1 AFTER `recno`,
ADD INDEX `livehelp_id` (`livehelp_id`);

ALTER TABLE `livehelp_visits_monthly` 
ADD COLUMN `livehelp_id` BIGINT(20) UNSIGNED NOT NULL DEFAULT 1 AFTER `recno`,
ADD INDEX `livehelp_id` (`livehelp_id`);

ALTER TABLE `livehelp_referers_daily` 
ADD COLUMN `livehelp_id` BIGINT(20) UNSIGNED NOT NULL DEFAULT 1 AFTER `recno`,
ADD INDEX `livehelp_id` (`livehelp_id`);

ALTER TABLE `livehelp_referers_monthly` 
ADD COLUMN `livehelp_id` BIGINT(20) UNSIGNED NOT NULL DEFAULT 1 AFTER `recno`,
ADD INDEX `livehelp_id` (`livehelp_id`);

ALTER TABLE `livehelp_keywords_daily` 
ADD COLUMN `livehelp_id` BIGINT(20) UNSIGNED NOT NULL DEFAULT 1 AFTER `recno`,
ADD INDEX `livehelp_id` (`livehelp_id`);

ALTER TABLE `livehelp_keywords_monthly` 
ADD COLUMN `livehelp_id` BIGINT(20) UNSIGNED NOT NULL DEFAULT 1 AFTER `recno`,
ADD INDEX `livehelp_id` (`livehelp_id`);

ALTER TABLE `livehelp_paths_firsts` 
ADD COLUMN `livehelp_id` BIGINT(20) UNSIGNED NOT NULL DEFAULT 1 AFTER `id`,
ADD INDEX `livehelp_id` (`livehelp_id`);

ALTER TABLE `livehelp_paths_monthly` 
ADD COLUMN `livehelp_id` BIGINT(20) UNSIGNED NOT NULL DEFAULT 1 AFTER `id`,
ADD INDEX `livehelp_id` (`livehelp_id`);

-- Operator Productivity Tables (1 table)
ALTER TABLE `livehelp_operator_history` 
ADD COLUMN `livehelp_id` BIGINT(20) UNSIGNED NOT NULL DEFAULT 1 AFTER `id`,
ADD INDEX `livehelp_id` (`livehelp_id`);

-- Forms & Offline Messages Tables (3 tables)
ALTER TABLE `livehelp_leavemessage` 
ADD COLUMN `livehelp_id` BIGINT(20) UNSIGNED NOT NULL DEFAULT 1 AFTER `id`,
ADD INDEX `livehelp_id` (`livehelp_id`);

ALTER TABLE `livehelp_questions` 
ADD COLUMN `livehelp_id` BIGINT(20) UNSIGNED NOT NULL DEFAULT 1 AFTER `id`,
ADD INDEX `livehelp_id` (`livehelp_id`);

ALTER TABLE `livehelp_qa` 
ADD COLUMN `livehelp_id` BIGINT(20) UNSIGNED NOT NULL DEFAULT 1 AFTER `recno`,
ADD INDEX `livehelp_id` (`livehelp_id`);

-- UI Customization Tables (5 tables)
ALTER TABLE `livehelp_quick` 
ADD COLUMN `livehelp_id` BIGINT(20) UNSIGNED NOT NULL DEFAULT 1 AFTER `id`,
ADD INDEX `livehelp_id` (`livehelp_id`);

ALTER TABLE `livehelp_smilies` 
ADD COLUMN `livehelp_id` BIGINT(20) UNSIGNED NOT NULL DEFAULT 1 AFTER `smilies_id`,
ADD INDEX `livehelp_id` (`livehelp_id`);

ALTER TABLE `livehelp_layerinvites` 
ADD COLUMN `livehelp_id` BIGINT(20) UNSIGNED NOT NULL DEFAULT 1 AFTER `layerid`,
ADD INDEX `livehelp_id` (`livehelp_id`);

ALTER TABLE `livehelp_autoinvite` 
ADD COLUMN `livehelp_id` BIGINT(20) UNSIGNED NOT NULL DEFAULT 1 AFTER `idnum`,
ADD INDEX `livehelp_id` (`livehelp_id`);

ALTER TABLE `livehelp_modules` 
ADD COLUMN `livehelp_id` BIGINT(20) UNSIGNED NOT NULL DEFAULT 1 AFTER `id`,
ADD INDEX `livehelp_id` (`livehelp_id`);

ALTER TABLE `livehelp_modules_dep` 
ADD COLUMN `livehelp_id` BIGINT(20) UNSIGNED NOT NULL DEFAULT 1 AFTER `rec`,
ADD INDEX `livehelp_id` (`livehelp_id`);

-- System Configuration Tables (2 tables)
ALTER TABLE `livehelp_config` 
ADD COLUMN `livehelp_id` BIGINT(20) UNSIGNED NOT NULL DEFAULT 1 AFTER `version`,
ADD INDEX `livehelp_id` (`livehelp_id`);

ALTER TABLE `livehelp_sessions` 
ADD COLUMN `livehelp_id` BIGINT(20) UNSIGNED NOT NULL DEFAULT 1 AFTER `session_id`,
ADD INDEX `livehelp_id` (`livehelp_id`);

-- CRM / Leads Tables (3 tables)
ALTER TABLE `livehelp_leads` 
ADD COLUMN `livehelp_id` BIGINT(20) UNSIGNED NOT NULL DEFAULT 1 AFTER `id`,
ADD INDEX `livehelp_id` (`livehelp_id`);

ALTER TABLE `livehelp_emails` 
ADD COLUMN `livehelp_id` BIGINT(20) UNSIGNED NOT NULL DEFAULT 1 AFTER `id`,
ADD INDEX `livehelp_id` (`livehelp_id`);

ALTER TABLE `livehelp_emailque` 
ADD COLUMN `livehelp_id` BIGINT(20) UNSIGNED NOT NULL DEFAULT 1 AFTER `id`,
ADD INDEX `livehelp_id` (`livehelp_id`);

-- ============================================================================
-- STEP 4: Update Existing Data (Set all rows to livehelp_id = 1)
-- ============================================================================

-- Note: Since DEFAULT 1 is set, existing rows already have livehelp_id = 1
-- This step is for explicit verification/update if needed

UPDATE `livehelp_messages` SET `livehelp_id` = 1 WHERE `livehelp_id` = 0 OR `livehelp_id` IS NULL;
UPDATE `livehelp_users` SET `livehelp_id` = 1 WHERE `livehelp_id` = 0 OR `livehelp_id` IS NULL;
UPDATE `livehelp_operator_channels` SET `livehelp_id` = 1 WHERE `livehelp_id` = 0 OR `livehelp_id` IS NULL;
UPDATE `livehelp_channels` SET `livehelp_id` = 1 WHERE `livehelp_id` = 0 OR `livehelp_id` IS NULL;
UPDATE `livehelp_transcripts` SET `livehelp_id` = 1 WHERE `livehelp_id` = 0 OR `livehelp_id` IS NULL;
UPDATE `livehelp_departments` SET `livehelp_id` = 1 WHERE `livehelp_id` = 0 OR `livehelp_id` IS NULL;
UPDATE `livehelp_operator_departments` SET `livehelp_id` = 1 WHERE `livehelp_id` = 0 OR `livehelp_id` IS NULL;
UPDATE `livehelp_websites` SET `livehelp_id` = 1 WHERE `livehelp_id` = 0 OR `livehelp_id` IS NULL;
UPDATE `livehelp_visit_track` SET `livehelp_id` = 1 WHERE `livehelp_id` = 0 OR `livehelp_id` IS NULL;
UPDATE `livehelp_identity_daily` SET `livehelp_id` = 1 WHERE `livehelp_id` = 0 OR `livehelp_id` IS NULL;
UPDATE `livehelp_identity_monthly` SET `livehelp_id` = 1 WHERE `livehelp_id` = 0 OR `livehelp_id` IS NULL;
UPDATE `livehelp_visits_daily` SET `livehelp_id` = 1 WHERE `livehelp_id` = 0 OR `livehelp_id` IS NULL;
UPDATE `livehelp_visits_monthly` SET `livehelp_id` = 1 WHERE `livehelp_id` = 0 OR `livehelp_id` IS NULL;
UPDATE `livehelp_referers_daily` SET `livehelp_id` = 1 WHERE `livehelp_id` = 0 OR `livehelp_id` IS NULL;
UPDATE `livehelp_referers_monthly` SET `livehelp_id` = 1 WHERE `livehelp_id` = 0 OR `livehelp_id` IS NULL;
UPDATE `livehelp_keywords_daily` SET `livehelp_id` = 1 WHERE `livehelp_id` = 0 OR `livehelp_id` IS NULL;
UPDATE `livehelp_keywords_monthly` SET `livehelp_id` = 1 WHERE `livehelp_id` = 0 OR `livehelp_id` IS NULL;
UPDATE `livehelp_paths_firsts` SET `livehelp_id` = 1 WHERE `livehelp_id` = 0 OR `livehelp_id` IS NULL;
UPDATE `livehelp_paths_monthly` SET `livehelp_id` = 1 WHERE `livehelp_id` = 0 OR `livehelp_id` IS NULL;
UPDATE `livehelp_operator_history` SET `livehelp_id` = 1 WHERE `livehelp_id` = 0 OR `livehelp_id` IS NULL;
UPDATE `livehelp_leavemessage` SET `livehelp_id` = 1 WHERE `livehelp_id` = 0 OR `livehelp_id` IS NULL;
UPDATE `livehelp_questions` SET `livehelp_id` = 1 WHERE `livehelp_id` = 0 OR `livehelp_id` IS NULL;
UPDATE `livehelp_qa` SET `livehelp_id` = 1 WHERE `livehelp_id` = 0 OR `livehelp_id` IS NULL;
UPDATE `livehelp_quick` SET `livehelp_id` = 1 WHERE `livehelp_id` = 0 OR `livehelp_id` IS NULL;
UPDATE `livehelp_smilies` SET `livehelp_id` = 1 WHERE `livehelp_id` = 0 OR `livehelp_id` IS NULL;
UPDATE `livehelp_layerinvites` SET `livehelp_id` = 1 WHERE `livehelp_id` = 0 OR `livehelp_id` IS NULL;
UPDATE `livehelp_autoinvite` SET `livehelp_id` = 1 WHERE `livehelp_id` = 0 OR `livehelp_id` IS NULL;
UPDATE `livehelp_modules` SET `livehelp_id` = 1 WHERE `livehelp_id` = 0 OR `livehelp_id` IS NULL;
UPDATE `livehelp_modules_dep` SET `livehelp_id` = 1 WHERE `livehelp_id` = 0 OR `livehelp_id` IS NULL;
UPDATE `livehelp_config` SET `livehelp_id` = 1 WHERE `livehelp_id` = 0 OR `livehelp_id` IS NULL;
UPDATE `livehelp_sessions` SET `livehelp_id` = 1 WHERE `livehelp_id` = 0 OR `livehelp_id` IS NULL;
UPDATE `livehelp_leads` SET `livehelp_id` = 1 WHERE `livehelp_id` = 0 OR `livehelp_id` IS NULL;
UPDATE `livehelp_emails` SET `livehelp_id` = 1 WHERE `livehelp_id` = 0 OR `livehelp_id` IS NULL;
UPDATE `livehelp_emailque` SET `livehelp_id` = 1 WHERE `livehelp_id` = 0 OR `livehelp_id` IS NULL;

-- ============================================================================
-- MIGRATION COMPLETE
-- ============================================================================
-- 
-- Verification:
-- 1. Check that 'livehelp' table exists
-- 2. Check that default instance (livehelp_id = 1) exists
-- 3. Verify all 34 original tables have 'livehelp_id' column
-- 4. Verify all indexes are created
-- 5. Test queries with livehelp_id filter
--
-- ============================================================================

