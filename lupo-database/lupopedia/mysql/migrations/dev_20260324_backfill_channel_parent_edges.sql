-- One-time: backfill channel_parent edges from structural column
-- File: lupo-database/lupopedia/mysql/migrations/dev_20260324_backfill_channel_parent_edges.sql

INSERT INTO lupo_edges
  (left_object_type, left_object_id, right_object_type, right_object_id, edge_type, domain_id, bidirectional, actor_id, flare_auto_generated, flare_db_source, flare_reason, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis)
SELECT
  'channel', channel_id, 'channel', parent_channel_id, 'channel_parent', 1, 0, 108, 1,
  'lupo_channels.parent_channel_id',
  'Backfilled from parent_channel_id structural column',
  20260324120000, 20260324120000, 0, 0
FROM lupo_channels
WHERE parent_channel_id IS NOT NULL AND is_deleted = 0;