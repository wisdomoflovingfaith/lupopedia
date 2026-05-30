-- Migration: Backfill channel_parent edges from lupo_channels.parent_channel_id
-- Version: 4.0.87
-- Created: 2026-03-24 19:40:00 UTC
-- Purpose: Make channel hierarchy queryable through lupo_edges
-- Track: 3c - Backfill parent_channel_id → channel_parent edges
-- Author: ATHENA (from ATHENA_STRATEGY_20260324_120000)

INSERT INTO lupo_edges
  (left_object_type, left_object_id, right_object_type, right_object_id, edge_type, channel_id, domain_id, bidirectional, actor_id, flare_auto_generated, flare_db_source, flare_reason, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis)
SELECT
  'channel' AS left_object_type,
  c.channel_id AS left_object_id,
  'channel' AS right_object_type,
  c.parent_channel_id AS right_object_id,
  'channel_parent' AS edge_type,
  c.channel_id AS channel_id,
  1 AS domain_id,
  0 AS bidirectional,
  12 AS actor_id,
  1 AS flare_auto_generated,
  'lupo_channels.parent_channel_id' AS flare_db_source,
  'Backfilled from parent_channel_id structural column' AS flare_reason,
  20260324194000 AS created_ymdhis,
  20260324194000 AS updated_ymdhis,
  0 AS is_deleted,
  0 AS deleted_ymdhis
FROM lupo_channels c
WHERE c.parent_channel_id IS NOT NULL 
  AND c.parent_channel_id > 0
  AND c.is_deleted = 0;
