-- ======================================================================
-- Channel roles and escalations: TOON-aligned structure
-- ======================================================================
-- Purpose: Ensure lupo_channel_roles, lupo_channel_escalations, and
--          lupo_channel_escalation_rules exist and match TOON definitions.
-- Doctrine: No foreign keys; no unsigned display widths; CHAR(14) timestamps;
--           soft-delete (is_deleted, deleted_ymdhis); JSON where appropriate.
-- Safe to run: Uses CREATE TABLE IF NOT EXISTS; no data changes.
-- ======================================================================

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

-- ======================================================================
-- lupo_channel_roles
-- Maps actors to roles within a channel (captain, administrator, monitor).
-- TOON: docs/toons/lupo_channel_roles.toon.json
-- ======================================================================
CREATE TABLE IF NOT EXISTS `lupo_channel_roles` (
  `channel_role_id` BIGINT NOT NULL AUTO_INCREMENT,
  `channel_id` BIGINT NOT NULL,
  `actor_id` BIGINT NOT NULL,
  `role_type` ENUM('captain','administrator','monitor') NOT NULL,
  `metadata_json` JSON NULL,
  `created_ymdhis` CHAR(14) NOT NULL,
  `updated_ymdhis` CHAR(14) NOT NULL,
  `is_deleted` TINYINT NOT NULL DEFAULT 0,
  `deleted_ymdhis` CHAR(14) NULL,
  PRIMARY KEY (`channel_role_id`),
  KEY `idx_channel_id` (`channel_id`),
  KEY `idx_actor_id` (`actor_id`),
  KEY `idx_role_type` (`role_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Channel role assignments: captain, administrator, monitor';

-- ======================================================================
-- lupo_channel_escalations
-- Records escalation events (thread/actor escalated to another actor).
-- TOON: docs/toons/lupo_channel_escalations.toon.json
-- ======================================================================
CREATE TABLE IF NOT EXISTS `lupo_channel_escalations` (
  `escalation_id` BIGINT NOT NULL AUTO_INCREMENT,
  `channel_id` BIGINT NOT NULL,
  `thread_id` BIGINT NULL,
  `actor_id` BIGINT NULL,
  `escalated_to_actor_id` BIGINT NULL,
  `escalation_reason` VARCHAR(512) NULL,
  `metadata_json` JSON NULL,
  `created_ymdhis` CHAR(14) NOT NULL,
  `updated_ymdhis` CHAR(14) NOT NULL,
  `is_deleted` TINYINT NOT NULL DEFAULT 0,
  `deleted_ymdhis` CHAR(14) NULL,
  PRIMARY KEY (`escalation_id`),
  KEY `idx_channel_id` (`channel_id`),
  KEY `idx_thread_id` (`thread_id`),
  KEY `idx_actor_id` (`actor_id`),
  KEY `idx_escalated_to_actor_id` (`escalated_to_actor_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Channel escalation events';

-- ======================================================================
-- lupo_channel_escalation_rules
-- Per-channel escalation rule definitions (rule_type, rule_config_json).
-- TOON: docs/toons/lupo_channel_escalation_rules.toon.json
-- ======================================================================
CREATE TABLE IF NOT EXISTS `lupo_channel_escalation_rules` (
  `rule_id` BIGINT NOT NULL AUTO_INCREMENT,
  `channel_id` BIGINT NOT NULL,
  `rule_name` VARCHAR(255) NOT NULL,
  `rule_description` TEXT NULL,
  `rule_type` VARCHAR(64) NOT NULL,
  `rule_config_json` JSON NULL,
  `created_ymdhis` CHAR(14) NOT NULL,
  `updated_ymdhis` CHAR(14) NOT NULL,
  `is_deleted` TINYINT NOT NULL DEFAULT 0,
  `deleted_ymdhis` CHAR(14) NULL,
  PRIMARY KEY (`rule_id`),
  KEY `idx_channel_id` (`channel_id`),
  KEY `idx_rule_type` (`rule_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Channel escalation rule definitions';

-- ======================================================================
-- End of migration
-- ======================================================================
