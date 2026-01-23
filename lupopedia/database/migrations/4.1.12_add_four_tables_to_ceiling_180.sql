-- ============================================================
-- Migration: Add 4 tables to reach TABLE_COUNT_DOCTRINE ceiling (180)
-- Version: 4.1.12
-- Purpose: 176 + 4 = 180 tables. Doctrine-aligned: no FK, no triggers,
--          BIGINT timestamps (created_ymdhis), soft delete where used.
-- TABLE_COUNT_DOCTRINE: ceiling 181 (raised 2026-01-20); legacy livehelp_ exempt.
-- ============================================================
-- Run after: generate_toon_files.py (or equivalent) to refresh TOON layer.
-- ============================================================

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- --------------------------------------------------------
-- 1. lupo_temporal_coherence_snapshots
-- LILITH High-Speed Changelog review: temporal coherence metrics.
-- observation_latency_ms, recursion_depth, self_awareness_score, etc.
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `lupo_temporal_coherence_snapshots` (
  `snapshot_id` bigint NOT NULL AUTO_INCREMENT,
  `utc_anchor` bigint NOT NULL COMMENT 'YYYYMMDDHHMMSS of anchor',
  `observation_latency_ms` int NOT NULL DEFAULT 0,
  `recursion_depth` tinyint NOT NULL DEFAULT 0 COMMENT '1=action, 2=observation, 3=meta; max 3',
  `self_awareness_score` decimal(3,2) DEFAULT NULL COMMENT '0-1 scale',
  `timestamp_integrity` varchar(32) NOT NULL DEFAULT 'unknown' COMMENT 'monotonic|gaps|anomalies',
  `created_ymdhis` bigint NOT NULL COMMENT 'YYYYMMDDHHMMSS',
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint DEFAULT NULL,
  PRIMARY KEY (`snapshot_id`),
  KEY `idx_created_ymdhis` (`created_ymdhis`),
  KEY `idx_utc_anchor` (`utc_anchor`),
  KEY `idx_is_deleted` (`is_deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Temporal coherence metrics per LILITH observer-paradox review. No FK.';

-- --------------------------------------------------------
-- 2. lupo_system_health_snapshots
-- SYSTEM_STATUS_dialog-style health: table_count, ceiling, R/G/B/T, schema_state.
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `lupo_system_health_snapshots` (
  `health_id` bigint NOT NULL AUTO_INCREMENT,
  `table_count` int NOT NULL,
  `table_ceiling` int NOT NULL,
  `schema_state` varchar(64) NOT NULL DEFAULT 'unknown' COMMENT 'frozen|active|migrating',
  `sync_integrity` varchar(32) NOT NULL DEFAULT 'unknown' COMMENT 'clean|drift|unknown',
  `emotional_r` decimal(3,2) DEFAULT NULL COMMENT 'strife -1..1',
  `emotional_g` decimal(3,2) DEFAULT NULL COMMENT 'harmony -1..1',
  `emotional_b` decimal(3,2) DEFAULT NULL COMMENT 'memory -1..1',
  `emotional_t` decimal(3,2) DEFAULT NULL COMMENT 'temporal -1..1',
  `created_ymdhis` bigint NOT NULL COMMENT 'YYYYMMDDHHMMSS',
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint DEFAULT NULL,
  PRIMARY KEY (`health_id`),
  KEY `idx_created_ymdhis` (`created_ymdhis`),
  KEY `idx_table_count` (`table_count`),
  KEY `idx_is_deleted` (`is_deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='System health snapshots for status dialogs. No FK.';

-- --------------------------------------------------------
-- 3. lupo_meta_log_events
-- Meta-logging ceiling: when recursion hits depth 2 or 3 (no fourth-order).
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `lupo_meta_log_events` (
  `event_id` bigint NOT NULL AUTO_INCREMENT,
  `depth` tinyint NOT NULL COMMENT '2=observation, 3=meta_observation',
  `event_type` varchar(64) NOT NULL DEFAULT 'recursion' COMMENT 'recursion|ceiling_near|auto_collapse',
  `actor_id` bigint DEFAULT NULL,
  `created_ymdhis` bigint NOT NULL COMMENT 'YYYYMMDDHHMMSS',
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint DEFAULT NULL,
  PRIMARY KEY (`event_id`),
  KEY `idx_created_ymdhis` (`created_ymdhis`),
  KEY `idx_depth` (`depth`),
  KEY `idx_actor_id` (`actor_id`),
  KEY `idx_is_deleted` (`is_deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Meta-logging recursion events; depth max 3. No FK.';

-- --------------------------------------------------------
-- 4. lupo_agent_heartbeats
-- Agent heartbeat/status (LILITH: Agent Heartbeat System).
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `lupo_agent_heartbeats` (
  `heartbeat_id` bigint NOT NULL AUTO_INCREMENT,
  `agent_slug` varchar(64) NOT NULL COMMENT 'CURSOR|CASCADE|LILITH|MONDAY_WOLFIE|etc',
  `status` varchar(32) NOT NULL DEFAULT 'unknown' COMMENT 'operational|idle|error|unknown',
  `last_heartbeat_ymdhis` bigint NOT NULL COMMENT 'YYYYMMDDHHMMSS',
  `created_ymdhis` bigint NOT NULL COMMENT 'YYYYMMDDHHMMSS',
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint DEFAULT NULL,
  PRIMARY KEY (`heartbeat_id`),
  KEY `idx_agent_slug` (`agent_slug`),
  KEY `idx_last_heartbeat_ymdhis` (`last_heartbeat_ymdhis`),
  KEY `idx_created_ymdhis` (`created_ymdhis`),
  KEY `idx_is_deleted` (`is_deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Agent heartbeat/status. No FK.';

COMMIT;

-- ============================================================
-- Post-migration: run database/generate_toon_files.py (or equivalent)
-- to regenerate .toon and .txt for the 4 new tables.
-- Expected table count after: 180. (Ceiling later raised to 181.)
-- ============================================================
