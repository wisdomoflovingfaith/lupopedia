-- One-time: ensure main admin (actor_id 10000) has channel membership and roles for channels 0, 1, 42.
-- Run this if you already installed before seed included actor 10000 and see "not part of any channels" after login.
-- Idempotent: safe to run multiple times.

SET @now_ymdhis = DATE_FORMAT(NOW(), '%Y%m%d%H%i%s');

-- Ensure actor 10000 exists (minimal row; wizard or seed may have created it)
INSERT INTO lupo_actors (actor_id, actor_type, slug, name, created_ymdhis, updated_ymdhis, is_active, is_deleted, deleted_ymdhis, actor_source_id, actor_source_type)
SELECT 10000, 'user', 'user-10000', 'Captain', @now_ymdhis, @now_ymdhis, 1, 0, NULL, 10000, 'lupo_auth_users'
FROM (SELECT 1) AS _one
WHERE NOT EXISTS (SELECT 1 FROM lupo_actors WHERE actor_id = 10000 LIMIT 1);

-- lupo_actor_channels: 10000 on channels 0, 1, 42
INSERT INTO lupo_actor_channels (actor_channel_id, actor_id, channel_id, status, start_date, channel_color, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis)
SELECT 10000, 10000, 0, 'A', @now_ymdhis, 'F7FAFF', @now_ymdhis, @now_ymdhis, 0, NULL FROM (SELECT 1) AS _one
WHERE NOT EXISTS (SELECT 1 FROM lupo_actor_channels WHERE actor_id = 10000 AND channel_id = 0 AND (is_deleted = 0 OR is_deleted IS NULL) LIMIT 1);
INSERT INTO lupo_actor_channels (actor_channel_id, actor_id, channel_id, status, start_date, channel_color, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis)
SELECT 10001, 10000, 1, 'A', @now_ymdhis, 'F7FAFF', @now_ymdhis, @now_ymdhis, 0, NULL FROM (SELECT 1) AS _one
WHERE NOT EXISTS (SELECT 1 FROM lupo_actor_channels WHERE actor_id = 10000 AND channel_id = 1 AND (is_deleted = 0 OR is_deleted IS NULL) LIMIT 1);
INSERT INTO lupo_actor_channels (actor_channel_id, actor_id, channel_id, status, start_date, channel_color, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis)
SELECT 10002, 10000, 42, 'A', @now_ymdhis, 'F7FAFF', @now_ymdhis, @now_ymdhis, 0, NULL FROM (SELECT 1) AS _one
WHERE NOT EXISTS (SELECT 1 FROM lupo_actor_channels WHERE actor_id = 10000 AND channel_id = 42 AND (is_deleted = 0 OR is_deleted IS NULL) LIMIT 1);

-- lupo_actor_channel_roles: captain on 0, 1, 42
INSERT INTO lupo_actor_channel_roles (actor_channel_role_id, actor_id, channel_id, role_key, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis)
SELECT 5000, 10000, 0, 'captain', @now_ymdhis, @now_ymdhis, 0, NULL FROM (SELECT 1) AS _one
WHERE NOT EXISTS (SELECT 1 FROM lupo_actor_channel_roles WHERE actor_id = 10000 AND channel_id = 0 AND (is_deleted = 0 OR is_deleted IS NULL) LIMIT 1);
INSERT INTO lupo_actor_channel_roles (actor_channel_role_id, actor_id, channel_id, role_key, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis)
SELECT 5001, 10000, 1, 'captain', @now_ymdhis, @now_ymdhis, 0, NULL FROM (SELECT 1) AS _one
WHERE NOT EXISTS (SELECT 1 FROM lupo_actor_channel_roles WHERE actor_id = 10000 AND channel_id = 1 AND (is_deleted = 0 OR is_deleted IS NULL) LIMIT 1);
INSERT INTO lupo_actor_channel_roles (actor_channel_role_id, actor_id, channel_id, role_key, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis)
SELECT 5002, 10000, 42, 'captain', @now_ymdhis, @now_ymdhis, 0, NULL FROM (SELECT 1) AS _one
WHERE NOT EXISTS (SELECT 1 FROM lupo_actor_channel_roles WHERE actor_id = 10000 AND channel_id = 42 AND (is_deleted = 0 OR is_deleted IS NULL) LIMIT 1);
