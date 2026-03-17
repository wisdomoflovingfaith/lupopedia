-- ============================================================================
-- Lilith (actor_id 2) — critic role on channel 42 (4.0.79)
-- ============================================================================
-- Purpose: Assign role_key 'critic' to Lilith on channel 42 for non-interfering
-- reviewer participation. Run after install/seed that creates lupo_actors and
-- lupo_actor_channels (Lilith already has channel 42 membership in install).
-- Doctrine: lupo-rules/root/lilith-noninterference-doctrine.md (LIL001)
-- ============================================================================

SET @now = 20260317000000;

-- Lilith (actor_id 2) as critic on channel 42
INSERT INTO lupo_actor_channel_roles (actor_channel_role_id, actor_id, actor_name, channel_id, role_key, created_ymdhis, updated_ymdhis, is_deleted, protocol_completion_status, protocol_version)
VALUES (7, 2, 'lilith', 42, 'critic', @now, @now, 0, 'completed', '3.0.0')
ON DUPLICATE KEY UPDATE role_key = 'critic', updated_ymdhis = @now, is_deleted = 0;
