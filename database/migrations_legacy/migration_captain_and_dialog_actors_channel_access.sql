-- One-time: Actor 10000 (captain@lupopedia.com) has access to channel 0 and channel 42,
-- and every actor that appears in dialog messages for those channels is a member of that channel and thread.
-- Channel 42 thread 1 = Lupopedia Development seed thread. Channel 0 = System Kernel.
-- Idempotent: safe to run multiple times.

SET @now_ymdhis = DATE_FORMAT(NOW(), '%Y%m%d%H%i%s');

-- -----------------------------------------------------------------------------
-- 1. Main admin: actor_id 10000, captain@lupopedia.com
-- -----------------------------------------------------------------------------
INSERT INTO lupo_actors (actor_id, actor_type, slug, name, created_ymdhis, updated_ymdhis, is_active, is_deleted, deleted_ymdhis, actor_source_id, actor_source_type)
SELECT 10000, 'user', 'user-10000', 'Captain', @now_ymdhis, @now_ymdhis, 1, 0, NULL, 10000, 'lupo_auth_users'
FROM (SELECT 1) AS _one
WHERE NOT EXISTS (SELECT 1 FROM lupo_actors WHERE actor_id = 10000 LIMIT 1);

-- Optional: ensure auth_users has captain@lupopedia.com for auth_user_id 10000 (link to actor 10000)
UPDATE lupo_auth_users SET email = 'captain@lupopedia.com', updated_ymdhis = @now_ymdhis
WHERE auth_user_id = 10000 AND (email IS NULL OR email = '' OR email != 'captain@lupopedia.com');

-- Actor 10000 on channel 0 and channel 42 (actor_channels)
INSERT INTO lupo_actor_channels (actor_channel_id, actor_id, channel_id, status, start_date, channel_color, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis)
SELECT 10000, 10000, 0, 'A', @now_ymdhis, 'F7FAFF', @now_ymdhis, @now_ymdhis, 0, NULL FROM (SELECT 1) AS _one
WHERE NOT EXISTS (SELECT 1 FROM lupo_actor_channels WHERE actor_id = 10000 AND channel_id = 0 AND (is_deleted = 0 OR is_deleted IS NULL) LIMIT 1);
INSERT INTO lupo_actor_channels (actor_channel_id, actor_id, channel_id, status, start_date, channel_color, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis)
SELECT 10002, 10000, 42, 'A', @now_ymdhis, 'F7FAFF', @now_ymdhis, @now_ymdhis, 0, NULL FROM (SELECT 1) AS _one
WHERE NOT EXISTS (SELECT 1 FROM lupo_actor_channels WHERE actor_id = 10000 AND channel_id = 42 AND (is_deleted = 0 OR is_deleted IS NULL) LIMIT 1);

-- Actor 10000 captain on channel 0 and channel 42 (actor_channel_roles)
INSERT INTO lupo_actor_channel_roles (actor_channel_role_id, actor_id, channel_id, role_key, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis)
SELECT 5000, 10000, 0, 'captain', @now_ymdhis, @now_ymdhis, 0, NULL FROM (SELECT 1) AS _one
WHERE NOT EXISTS (SELECT 1 FROM lupo_actor_channel_roles WHERE actor_id = 10000 AND channel_id = 0 AND (is_deleted = 0 OR is_deleted IS NULL) LIMIT 1);
INSERT INTO lupo_actor_channel_roles (actor_channel_role_id, actor_id, channel_id, role_key, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis)
SELECT 5002, 10000, 42, 'captain', @now_ymdhis, @now_ymdhis, 0, NULL FROM (SELECT 1) AS _one
WHERE NOT EXISTS (SELECT 1 FROM lupo_actor_channel_roles WHERE actor_id = 10000 AND channel_id = 42 AND (is_deleted = 0 OR is_deleted IS NULL) LIMIT 1);

-- -----------------------------------------------------------------------------
-- 2. All actors that appear in dialog messages (from_actor_id) for channel 0 and 42
--    must be members of that channel (actor_channels + actor_channel_roles).
--    Seed already has 1,2,...,24, 209, 1212, 1000 on channel 42. Only actor 0 is missing for channel 42.
-- -----------------------------------------------------------------------------

-- Actor 0 (System Kernel) on channel 42: seed has (0,0,0) but messages on channel 42 from 0, so add (0, 42)
INSERT INTO lupo_actor_channels (actor_channel_id, actor_id, channel_id, status, start_date, channel_color, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis)
SELECT 9998, 0, 42, 'A', @now_ymdhis, 'F7FAFF', @now_ymdhis, @now_ymdhis, 0, NULL FROM (SELECT 1) AS _one
WHERE NOT EXISTS (SELECT 1 FROM lupo_actor_channels WHERE actor_id = 0 AND channel_id = 42 AND (is_deleted = 0 OR is_deleted IS NULL) LIMIT 1);

INSERT INTO lupo_actor_channel_roles (actor_channel_role_id, actor_id, channel_id, role_key, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis)
SELECT 4998, 0, 42, 'admin', @now_ymdhis, @now_ymdhis, 0, NULL FROM (SELECT 1) AS _one
WHERE NOT EXISTS (SELECT 1 FROM lupo_actor_channel_roles WHERE actor_id = 0 AND channel_id = 42 AND (is_deleted = 0 OR is_deleted IS NULL) LIMIT 1);

-- -----------------------------------------------------------------------------
-- 3. Summary: Channel 42 thread 1 (Lupopedia Development) has messages from
--    actor_id 0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18,
--    19, 20, 22, 23, 24, 209, 1212, 1000. Seed already gives 1-24, 209, 1212, 1000
--    membership on channel 42. This migration adds: actor 0 on channel 42 (above)
--    and actor 10000 (captain@lupopedia.com) on channels 0 and 42. All dialog
--    message authors are now members of the channel (and thus the thread).
-- -----------------------------------------------------------------------------
