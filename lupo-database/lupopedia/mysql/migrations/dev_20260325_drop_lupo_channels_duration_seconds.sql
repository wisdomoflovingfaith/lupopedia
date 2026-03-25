-- Migration: dev_20260325_drop_lupo_channels_duration_seconds
-- Purpose: Remove deprecated channel-level duration_seconds from lupo_channels.
-- Rationale: Duration belongs to thread/message lifecycle, not channel identity metadata.
-- Run once.

ALTER TABLE lupo_channels DROP COLUMN duration_seconds;
