-- ============================================================================
-- Migration: 4.4.1 - Create actor_moods table
-- Description: Creates actor_moods table for 2-actor RGB mood model logging
-- Version: 4.4.1
-- Date: 2026-01-20
-- Author: CURSOR
-- ============================================================================
--
-- Purpose:
-- Creates the actor_moods table to support ActorMoodService and
-- PackMoodCoherenceService for the canonical 2-actor RGB mood model.
--
-- Mood values are discrete: R, G, B ∈ {-1, 0, 1}
-- Timestamps are BIGINT UNSIGNED in YYYYMMDDHHIISS format (UTC)
--
-- See: doctrine/EMOTIONAL_GEOMETRY_DOCTRINE.md for 2-actor RGB mood model
-- See: docs/services/ACTOR_MOOD_SERVICE.md for service documentation
-- ============================================================================

-- Create actor_moods table
CREATE TABLE IF NOT EXISTS actor_moods (
    actor_id BIGINT UNSIGNED NOT NULL COMMENT 'Actor ID from lupo_actor_registry',
    mood_r TINYINT NOT NULL COMMENT 'Red axis: Strife/Conflict (-1, 0, 1)',
    mood_g TINYINT NOT NULL COMMENT 'Green axis: Harmony/Cohesion (-1, 0, 1)',
    mood_b TINYINT NOT NULL COMMENT 'Blue axis: Memory/Persistence (-1, 0, 1)',
    timestamp_utc BIGINT UNSIGNED NOT NULL COMMENT 'UTC timestamp in YYYYMMDDHHIISS format',
    INDEX idx_actor_timestamp (actor_id, timestamp_utc DESC)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Actor mood logging table for 2-actor RGB mood model';

-- ============================================================================
-- Notes:
-- - No primary key (this is a log table)
-- - No auto-increment
-- - No foreign keys (application-layer relationships only)
-- - mood_r, mood_g, mood_b must be -1, 0, or 1 (validated in application layer)
-- - timestamp_utc is BIGINT UNSIGNED in YYYYMMDDHHIISS format
-- - Index optimizes getLatestMood() and getMoodHistory() queries
-- ============================================================================
