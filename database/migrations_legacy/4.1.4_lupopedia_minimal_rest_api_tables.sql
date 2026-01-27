-- Migration: Lupopedia Minimal REST API tables (Canonical Draft)
-- Version: 4.1.4
-- Date: 2026-01-19
--
-- Creates lupo_artifacts and lupo_actor_handshakes for the minimal REST API.
-- Doctrine: no FK, no triggers, BIGINT IDs, UTC-driven, stateless storage.
--
-- NOTE: Table count. If at ceiling (135), run after table reduction per TABLE_REDUCTION_PLAN.
--
-- @package Lupopedia
-- @version 4.1.4

-- ============================================================================
-- lupo_artifacts — Artifact registry (every change is an artifact)
-- ============================================================================
CREATE TABLE IF NOT EXISTS `lupo_artifacts` (
  `artifact_id` bigint NOT NULL AUTO_INCREMENT,
  `actor_id` bigint NOT NULL,
  `utc_timestamp` bigint NOT NULL COMMENT 'YYYYMMDDHHMMSS',
  `type` varchar(64) NOT NULL COMMENT 'dialog|changelog|schema|lore|humor|protocol',
  `content` longtext NOT NULL,
  `created_ymdhis` bigint NOT NULL,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint DEFAULT NULL,
  PRIMARY KEY (`artifact_id`),
  KEY `idx_utc_timestamp` (`utc_timestamp`),
  KEY `idx_actor_id` (`actor_id`),
  KEY `idx_type` (`type`),
  KEY `idx_is_deleted` (`is_deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Artifact registry for Lupopedia minimal REST API. No FK.';

-- ============================================================================
-- lupo_actor_handshakes — Actor handshake (identity, timeline, constraints)
-- ============================================================================
CREATE TABLE IF NOT EXISTS `lupo_actor_handshakes` (
  `handshake_id` bigint NOT NULL AUTO_INCREMENT,
  `actor_id` bigint NOT NULL,
  `actor_type` varchar(32) NOT NULL COMMENT 'human|ai|system',
  `utc_timestamp` bigint NOT NULL COMMENT 'YYYYMMDDHHMMSS',
  `purpose` varchar(500) DEFAULT NULL,
  `constraints_json` json DEFAULT NULL,
  `forbidden_actions_json` json DEFAULT NULL,
  `context` text DEFAULT NULL,
  `expires_utc` bigint DEFAULT NULL COMMENT 'YYYYMMDDHHMMSS',
  `created_ymdhis` bigint NOT NULL,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint DEFAULT NULL,
  PRIMARY KEY (`handshake_id`),
  KEY `idx_actor_id` (`actor_id`),
  KEY `idx_utc_timestamp` (`utc_timestamp`),
  KEY `idx_is_deleted` (`is_deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Actor handshakes for Lupopedia minimal REST API. No FK.';
