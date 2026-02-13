-- Migration: Grant captain@lupopedia.com full admin access
-- Ensures the user with email captain@lupopedia.com has captain role on channel 1 (default channel),
-- so isAdmin() returns true and admin.php allows access.
-- Run once. Idempotent: creates actor if missing, adds channel role only if not already present.
-- Table prefix: lupo_ (change if your LUPO_TABLE_PREFIX differs).
--
-- Prerequisite: lupo_auth_users must contain a row with email = 'captain@lupopedia.com'.
-- Create that user via install wizard or registration first if needed.

-- Step 1: Ensure an actor exists for the auth user with email captain@lupopedia.com
-- (Actor may already exist from login/registration.)
INSERT INTO lupo_actors (actor_type, slug, name, created_ymdhis, updated_ymdhis, is_active, is_deleted, actor_source_id, actor_source_type)
SELECT 'user', CONCAT('user-', u.auth_user_id), COALESCE(NULLIF(TRIM(u.display_name), ''), u.username, 'Captain'), 20260213120000, 20260213120000, 1, 0, u.auth_user_id, 'user'
FROM lupo_auth_users u
WHERE u.email = 'captain@lupopedia.com'
  AND (u.is_deleted = 0 OR u.is_deleted IS NULL)
  AND NOT EXISTS (SELECT 1 FROM lupo_actors a WHERE a.actor_source_type = 'user' AND a.actor_source_id = u.auth_user_id AND (a.is_deleted = 0 OR a.is_deleted IS NULL))
LIMIT 1;

-- Step 2: Grant captain role on channel 1 (system default channel) so this user is admin
-- Only insert if no active captain/administrator role already exists for this actor on channel 1
INSERT INTO lupo_channel_roles (channel_role_id, channel_id, actor_id, role_type, created_ymdhis, updated_ymdhis, is_deleted)
SELECT
    (SELECT COALESCE(MAX(cr.channel_role_id), 0) + 1 FROM lupo_channel_roles cr),
    1,
    a.actor_id,
    'captain',
    20260213120000,
    20260213120000,
    0
FROM lupo_actors a
INNER JOIN lupo_auth_users u ON u.auth_user_id = a.actor_source_id AND a.actor_source_type = 'user'
WHERE u.email = 'captain@lupopedia.com'
  AND (a.is_deleted = 0 OR a.is_deleted IS NULL)
  AND (u.is_deleted = 0 OR u.is_deleted IS NULL)
  AND NOT EXISTS (
    SELECT 1 FROM lupo_channel_roles cr2
    WHERE cr2.actor_id = a.actor_id AND cr2.channel_id = 1
      AND cr2.role_type IN ('captain', 'administrator')
      AND (cr2.is_deleted = 0 OR cr2.is_deleted IS NULL)
  )
LIMIT 1;
