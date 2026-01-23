-- ============================================================================
-- Crafty Syntax 3.8.0 - Database Migration
-- ============================================================================
-- Migration: 0002
-- Description: Create Agent DNA System tables (A, C, G, T) with _logs, _collections, and _tags
-- Date: 2025-11-18
-- Author: Captain WOLFIE (Eric Robin Gerdes)
-- ============================================================================
-- PURPOSE:
-- - Create 4 DNA base tables (livehelp_A, livehelp_C, livehelp_G, livehelp_T)
-- - Create 12 associated tables (4 _logs, 4 _collections, 4 _tags)
-- - Enable Agent DNA System for LUPOPEDIA Platform
-- - Total: 16 tables
-- ============================================================================
-- IMPORTANT: This is COMPUTER GENETICS, NOT Human Genetics
-- DNA bases (A, C, G, T) are metadata markers for agent behavior
-- Context-dependent: same marker means different things per channel/agent
-- ============================================================================

-- ============================================================================
-- STEP 1: Create livehelp_A Table (Actions)
-- ============================================================================

CREATE TABLE IF NOT EXISTS `livehelp_A` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `livehelp_id` BIGINT(20) UNSIGNED NOT NULL DEFAULT 1,
  `channel_id` BIGINT(20) UNSIGNED NOT NULL DEFAULT 1 COMMENT 'Channel ID (000-999) - CRITICAL: Context for metadata lookup',
  `agent_id` BIGINT(20) UNSIGNED NOT NULL DEFAULT 1 COMMENT 'Agent ID - CRITICAL: Which agent interprets this',
  `agent_name` VARCHAR(255) NOT NULL DEFAULT 'unknown' COMMENT 'Agent name (denormalized) - CRITICAL: Agent-specific interpretation',
  `name` VARCHAR(255) NOT NULL COMMENT 'Action name (e.g., execute, query, build, archive)',
  `description` TEXT DEFAULT NULL,
  `action_type` ENUM('execute', 'query', 'build', 'archive', 'other') DEFAULT 'execute',
  `priority` INT UNSIGNED DEFAULT 0 COMMENT 'Priority level (0-100)',
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `metadata` LONGTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'JSON metadata',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `livehelp_id` (`livehelp_id`),
  KEY `channel_id` (`channel_id`),
  KEY `agent_id` (`agent_id`),
  KEY `agent_name` (`agent_name`),
  KEY `name` (`name`),
  KEY `action_type` (`action_type`),
  KEY `is_active` (`is_active`),
  KEY `idx_channel_agent` (`channel_id`, `agent_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================================
-- STEP 2: Create livehelp_A_logs Table
-- ============================================================================

CREATE TABLE IF NOT EXISTS `livehelp_A_logs` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `livehelp_id` BIGINT(20) UNSIGNED NOT NULL DEFAULT 1,
  `A_id` BIGINT(20) UNSIGNED NOT NULL DEFAULT 1 COMMENT 'References livehelp_A.id',
  `agent_id` BIGINT(20) UNSIGNED NOT NULL DEFAULT 1 COMMENT 'Agent ID that made the change',
  `agent_name` VARCHAR(255) NOT NULL DEFAULT 'unknown' COMMENT 'Agent name (denormalized)',
  `channel_id` BIGINT(20) UNSIGNED NOT NULL DEFAULT 1 COMMENT 'Channel ID (000-999)',
  `metadata` LONGTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'Change metadata JSON',
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `A_id` (`A_id`),
  KEY `livehelp_id` (`livehelp_id`),
  KEY `agent_id` (`agent_id`),
  KEY `channel_id` (`channel_id`),
  KEY `idx_A_agent` (`A_id`, `agent_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================================
-- STEP 3: Create livehelp_A_collections Table
-- ============================================================================

CREATE TABLE IF NOT EXISTS `livehelp_A_collections` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `livehelp_id` BIGINT(20) UNSIGNED NOT NULL DEFAULT 1,
  `A_id` BIGINT(20) UNSIGNED NOT NULL DEFAULT 1 COMMENT 'References livehelp_A.id',
  `collection_name` VARCHAR(255) NOT NULL,
  `collection_type` VARCHAR(100) DEFAULT NULL,
  `description` TEXT DEFAULT NULL,
  `metadata` LONGTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'JSON metadata',
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `A_id` (`A_id`),
  KEY `livehelp_id` (`livehelp_id`),
  KEY `collection_name` (`collection_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================================
-- STEP 4: Create livehelp_A_tags Table
-- ============================================================================

CREATE TABLE IF NOT EXISTS `livehelp_A_tags` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `livehelp_id` BIGINT(20) UNSIGNED NOT NULL DEFAULT 1,
  `A_id` BIGINT(20) UNSIGNED NOT NULL DEFAULT 1 COMMENT 'References livehelp_A.id',
  `tag_name` VARCHAR(255) NOT NULL,
  `tag_type` VARCHAR(100) DEFAULT NULL,
  `description` TEXT DEFAULT NULL,
  `metadata` LONGTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'JSON metadata',
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `A_id` (`A_id`),
  KEY `livehelp_id` (`livehelp_id`),
  KEY `tag_name` (`tag_name`),
  KEY `tag_type` (`tag_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================================
-- STEP 5: Create livehelp_C Table (Context)
-- ============================================================================

CREATE TABLE IF NOT EXISTS `livehelp_C` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `livehelp_id` BIGINT(20) UNSIGNED NOT NULL DEFAULT 1,
  `channel_id` BIGINT(20) UNSIGNED NOT NULL DEFAULT 1 COMMENT 'Channel ID (000-999) - CRITICAL: Context for metadata lookup',
  `agent_id` BIGINT(20) UNSIGNED NOT NULL DEFAULT 1 COMMENT 'Agent ID - CRITICAL: Which agent interprets this',
  `agent_name` VARCHAR(255) NOT NULL DEFAULT 'unknown' COMMENT 'Agent name (denormalized) - CRITICAL: Agent-specific interpretation',
  `name` VARCHAR(255) NOT NULL COMMENT 'Context name (e.g., channel, bridge, archive)',
  `description` TEXT DEFAULT NULL,
  `context_type` ENUM('channel', 'bridge', 'archive', 'scope', 'other') DEFAULT 'channel',
  `scope` VARCHAR(255) DEFAULT NULL,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `metadata` LONGTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'JSON metadata',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `livehelp_id` (`livehelp_id`),
  KEY `channel_id` (`channel_id`),
  KEY `agent_id` (`agent_id`),
  KEY `agent_name` (`agent_name`),
  KEY `name` (`name`),
  KEY `context_type` (`context_type`),
  KEY `idx_channel_agent` (`channel_id`, `agent_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================================
-- STEP 6: Create livehelp_C_logs Table
-- ============================================================================

CREATE TABLE IF NOT EXISTS `livehelp_C_logs` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `livehelp_id` BIGINT(20) UNSIGNED NOT NULL DEFAULT 1,
  `C_id` BIGINT(20) UNSIGNED NOT NULL DEFAULT 1 COMMENT 'References livehelp_C.id',
  `agent_id` BIGINT(20) UNSIGNED NOT NULL DEFAULT 1 COMMENT 'Agent ID that made the change',
  `agent_name` VARCHAR(255) NOT NULL DEFAULT 'unknown' COMMENT 'Agent name (denormalized)',
  `channel_id` BIGINT(20) UNSIGNED NOT NULL DEFAULT 1 COMMENT 'Channel ID (000-999)',
  `metadata` LONGTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'Change metadata JSON',
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `C_id` (`C_id`),
  KEY `livehelp_id` (`livehelp_id`),
  KEY `agent_id` (`agent_id`),
  KEY `channel_id` (`channel_id`),
  KEY `idx_C_agent` (`C_id`, `agent_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================================
-- STEP 7: Create livehelp_C_collections Table
-- ============================================================================

CREATE TABLE IF NOT EXISTS `livehelp_C_collections` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `livehelp_id` BIGINT(20) UNSIGNED NOT NULL DEFAULT 1,
  `C_id` BIGINT(20) UNSIGNED NOT NULL DEFAULT 1 COMMENT 'References livehelp_C.id',
  `collection_name` VARCHAR(255) NOT NULL,
  `collection_type` VARCHAR(100) DEFAULT NULL,
  `description` TEXT DEFAULT NULL,
  `metadata` LONGTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'JSON metadata',
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `C_id` (`C_id`),
  KEY `livehelp_id` (`livehelp_id`),
  KEY `collection_name` (`collection_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================================
-- STEP 8: Create livehelp_C_tags Table
-- ============================================================================

CREATE TABLE IF NOT EXISTS `livehelp_C_tags` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `livehelp_id` BIGINT(20) UNSIGNED NOT NULL DEFAULT 1,
  `C_id` BIGINT(20) UNSIGNED NOT NULL DEFAULT 1 COMMENT 'References livehelp_C.id',
  `tag_name` VARCHAR(255) NOT NULL,
  `tag_type` VARCHAR(100) DEFAULT NULL,
  `description` TEXT DEFAULT NULL,
  `metadata` LONGTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'JSON metadata',
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `C_id` (`C_id`),
  KEY `livehelp_id` (`livehelp_id`),
  KEY `tag_name` (`tag_name`),
  KEY `tag_type` (`tag_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================================
-- STEP 9: Create livehelp_G Table (Governance)
-- ============================================================================

CREATE TABLE IF NOT EXISTS `livehelp_G` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `livehelp_id` BIGINT(20) UNSIGNED NOT NULL DEFAULT 1,
  `channel_id` BIGINT(20) UNSIGNED NOT NULL DEFAULT 1 COMMENT 'Channel ID (000-999) - CRITICAL: Context for metadata lookup',
  `agent_id` BIGINT(20) UNSIGNED NOT NULL DEFAULT 1 COMMENT 'Agent ID - CRITICAL: Which agent interprets this',
  `agent_name` VARCHAR(255) NOT NULL DEFAULT 'unknown' COMMENT 'Agent name (denormalized) - CRITICAL: Agent-specific interpretation',
  `name` VARCHAR(255) NOT NULL COMMENT 'Governance rule name (e.g., validation, error_handling, ritual_protocol)',
  `description` TEXT DEFAULT NULL,
  `governance_type` ENUM('validation', 'error_handling', 'ritual_protocol', 'oversight', 'other') DEFAULT 'validation',
  `rule_level` ENUM('strict', 'moderate', 'flexible') DEFAULT 'moderate',
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `metadata` LONGTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'JSON metadata',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `livehelp_id` (`livehelp_id`),
  KEY `channel_id` (`channel_id`),
  KEY `agent_id` (`agent_id`),
  KEY `agent_name` (`agent_name`),
  KEY `name` (`name`),
  KEY `governance_type` (`governance_type`),
  KEY `rule_level` (`rule_level`),
  KEY `idx_channel_agent` (`channel_id`, `agent_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================================
-- STEP 10: Create livehelp_G_logs Table
-- ============================================================================

CREATE TABLE IF NOT EXISTS `livehelp_G_logs` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `livehelp_id` BIGINT(20) UNSIGNED NOT NULL DEFAULT 1,
  `G_id` BIGINT(20) UNSIGNED NOT NULL DEFAULT 1 COMMENT 'References livehelp_G.id',
  `agent_id` BIGINT(20) UNSIGNED NOT NULL DEFAULT 1 COMMENT 'Agent ID that made the change',
  `agent_name` VARCHAR(255) NOT NULL DEFAULT 'unknown' COMMENT 'Agent name (denormalized)',
  `channel_id` BIGINT(20) UNSIGNED NOT NULL DEFAULT 1 COMMENT 'Channel ID (000-999)',
  `metadata` LONGTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'Change metadata JSON',
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `G_id` (`G_id`),
  KEY `livehelp_id` (`livehelp_id`),
  KEY `agent_id` (`agent_id`),
  KEY `channel_id` (`channel_id`),
  KEY `idx_G_agent` (`G_id`, `agent_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================================
-- STEP 11: Create livehelp_G_collections Table
-- ============================================================================

CREATE TABLE IF NOT EXISTS `livehelp_G_collections` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `livehelp_id` BIGINT(20) UNSIGNED NOT NULL DEFAULT 1,
  `G_id` BIGINT(20) UNSIGNED NOT NULL DEFAULT 1 COMMENT 'References livehelp_G.id',
  `collection_name` VARCHAR(255) NOT NULL,
  `collection_type` VARCHAR(100) DEFAULT NULL,
  `description` TEXT DEFAULT NULL,
  `metadata` LONGTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'JSON metadata',
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `G_id` (`G_id`),
  KEY `livehelp_id` (`livehelp_id`),
  KEY `collection_name` (`collection_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================================
-- STEP 12: Create livehelp_G_tags Table
-- ============================================================================

CREATE TABLE IF NOT EXISTS `livehelp_G_tags` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `livehelp_id` BIGINT(20) UNSIGNED NOT NULL DEFAULT 1,
  `G_id` BIGINT(20) UNSIGNED NOT NULL DEFAULT 1 COMMENT 'References livehelp_G.id',
  `tag_name` VARCHAR(255) NOT NULL,
  `tag_type` VARCHAR(100) DEFAULT NULL,
  `description` TEXT DEFAULT NULL,
  `metadata` LONGTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'JSON metadata',
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `G_id` (`G_id`),
  KEY `livehelp_id` (`livehelp_id`),
  KEY `tag_name` (`tag_name`),
  KEY `tag_type` (`tag_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================================
-- STEP 13: Create livehelp_T Table (Tactic)
-- ============================================================================

CREATE TABLE IF NOT EXISTS `livehelp_T` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `livehelp_id` BIGINT(20) UNSIGNED NOT NULL DEFAULT 1,
  `channel_id` BIGINT(20) UNSIGNED NOT NULL DEFAULT 1 COMMENT 'Channel ID (000-999) - CRITICAL: Context for metadata lookup',
  `agent_id` BIGINT(20) UNSIGNED NOT NULL DEFAULT 1 COMMENT 'Agent ID - CRITICAL: Which agent interprets this',
  `agent_name` VARCHAR(255) NOT NULL DEFAULT 'unknown' COMMENT 'Agent name (denormalized) - CRITICAL: Agent-specific interpretation',
  `name` VARCHAR(255) NOT NULL COMMENT 'Tactic name (e.g., parallel, recursive, brittle, chaotic)',
  `description` TEXT DEFAULT NULL,
  `tactic_type` ENUM('parallel', 'recursive', 'brittle', 'chaotic', 'sequential', 'other') DEFAULT 'parallel',
  `efficiency_score` DECIMAL(3,2) DEFAULT 0.00 COMMENT 'Efficiency score (0.00-1.00)',
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `metadata` LONGTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'JSON metadata',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `livehelp_id` (`livehelp_id`),
  KEY `channel_id` (`channel_id`),
  KEY `agent_id` (`agent_id`),
  KEY `agent_name` (`agent_name`),
  KEY `name` (`name`),
  KEY `tactic_type` (`tactic_type`),
  KEY `idx_channel_agent` (`channel_id`, `agent_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================================
-- STEP 14: Create livehelp_T_logs Table
-- ============================================================================

CREATE TABLE IF NOT EXISTS `livehelp_T_logs` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `livehelp_id` BIGINT(20) UNSIGNED NOT NULL DEFAULT 1,
  `T_id` BIGINT(20) UNSIGNED NOT NULL DEFAULT 1 COMMENT 'References livehelp_T.id',
  `agent_id` BIGINT(20) UNSIGNED NOT NULL DEFAULT 1 COMMENT 'Agent ID that made the change',
  `agent_name` VARCHAR(255) NOT NULL DEFAULT 'unknown' COMMENT 'Agent name (denormalized)',
  `channel_id` BIGINT(20) UNSIGNED NOT NULL DEFAULT 1 COMMENT 'Channel ID (000-999)',
  `metadata` LONGTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'Change metadata JSON',
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `T_id` (`T_id`),
  KEY `livehelp_id` (`livehelp_id`),
  KEY `agent_id` (`agent_id`),
  KEY `channel_id` (`channel_id`),
  KEY `idx_T_agent` (`T_id`, `agent_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================================
-- STEP 15: Create livehelp_T_collections Table
-- ============================================================================

CREATE TABLE IF NOT EXISTS `livehelp_T_collections` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `livehelp_id` BIGINT(20) UNSIGNED NOT NULL DEFAULT 1,
  `T_id` BIGINT(20) UNSIGNED NOT NULL DEFAULT 1 COMMENT 'References livehelp_T.id',
  `collection_name` VARCHAR(255) NOT NULL,
  `collection_type` VARCHAR(100) DEFAULT NULL,
  `description` TEXT DEFAULT NULL,
  `metadata` LONGTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'JSON metadata',
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `T_id` (`T_id`),
  KEY `livehelp_id` (`livehelp_id`),
  KEY `collection_name` (`collection_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================================
-- STEP 16: Create livehelp_T_tags Table
-- ============================================================================

CREATE TABLE IF NOT EXISTS `livehelp_T_tags` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `livehelp_id` BIGINT(20) UNSIGNED NOT NULL DEFAULT 1,
  `T_id` BIGINT(20) UNSIGNED NOT NULL DEFAULT 1 COMMENT 'References livehelp_T.id',
  `tag_name` VARCHAR(255) NOT NULL,
  `tag_type` VARCHAR(100) DEFAULT NULL,
  `description` TEXT DEFAULT NULL,
  `metadata` LONGTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'JSON metadata',
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `T_id` (`T_id`),
  KEY `livehelp_id` (`livehelp_id`),
  KEY `tag_name` (`tag_name`),
  KEY `tag_type` (`tag_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================================
-- MIGRATION COMPLETE
-- ============================================================================
-- 
-- Verification:
-- 1. Check that all 16 tables exist:
--    - 4 main DNA tables: livehelp_A, livehelp_C, livehelp_G, livehelp_T
--    - 4 _logs tables: livehelp_A_logs, livehelp_C_logs, livehelp_G_logs, livehelp_T_logs
--    - 4 _collections tables: livehelp_A_collections, livehelp_C_collections, livehelp_G_collections, livehelp_T_collections
--    - 4 _tags tables: livehelp_A_tags, livehelp_C_tags, livehelp_G_tags, livehelp_T_tags
-- 2. Verify all indexes are created
-- 3. Test queries with livehelp_id filter
-- 4. Test context-dependent metadata lookup (channel_id + agent_name)
--
-- ============================================================================

