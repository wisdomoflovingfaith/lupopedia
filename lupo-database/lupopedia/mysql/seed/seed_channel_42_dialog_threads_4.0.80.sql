-- ============================================================================
-- Channel 42 — coordination dialog threads (4.0.80)
-- ============================================================================
-- Option A (ATHENA / WOLFIE): thread-bound artifacts require pre-existing
-- lupo_dialog_threads rows. Idempotent: skips if dialog_thread_id exists.
-- Run after lupo_channels row 42 and lupo_actors (created_by_actor_id = 1).
-- ============================================================================

SET @now = 20260317224500;

-- 1001 — R&D / table documentation workstream
INSERT INTO lupo_dialog_threads (
  dialog_thread_id, title, federation_node_id, channel_id, created_by_actor_id,
  created_ymdhis, updated_ymdhis, status, bg_color, text_color, alt_text_color, is_deleted
)
SELECT
  1001, 'Channel 42 — R&D and table documentation', 1, 42, 1,
  @now, @now, 'Open', 'FFFFFF', '000000', '666666', 0
WHERE NOT EXISTS (SELECT 1 FROM lupo_dialog_threads WHERE dialog_thread_id = 1001);

-- 1002 — Multi-agent coordination
INSERT INTO lupo_dialog_threads (
  dialog_thread_id, title, federation_node_id, channel_id, created_by_actor_id,
  created_ymdhis, updated_ymdhis, status, bg_color, text_color, alt_text_color, is_deleted
)
SELECT
  1002, 'Channel 42 — Multi-agent coordination', 1, 42, 1,
  @now, @now, 'Open', 'FFFFFF', '000000', '666666', 0
WHERE NOT EXISTS (SELECT 1 FROM lupo_dialog_threads WHERE dialog_thread_id = 1002);

-- 1004 — Quality assurance / documentation corrections
INSERT INTO lupo_dialog_threads (
  dialog_thread_id, title, federation_node_id, channel_id, created_by_actor_id,
  created_ymdhis, updated_ymdhis, status, bg_color, text_color, alt_text_color, is_deleted
)
SELECT
  1004, 'Channel 42 — Quality assurance', 1, 42, 1,
  @now, @now, 'Open', 'FFFFFF', '000000', '666666', 0
WHERE NOT EXISTS (SELECT 1 FROM lupo_dialog_threads WHERE dialog_thread_id = 1004);
