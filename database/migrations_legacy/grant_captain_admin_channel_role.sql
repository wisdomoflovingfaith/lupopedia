-- Migration: Grant captain@lupopedia.com full admin access (4.0.6: uses lupo_actor_channel_roles).
-- Ensures the user with email captain@lupopedia.com has captain role on channel 1 (default channel),
-- so isAdmin() returns true and admin.php allows access.
-- Run once. Idempotent: creates actor if missing, adds channel role only if not already present.
-- Table prefix: lupo_ (change if your LUPO_TABLE_PREFIX differs).
--
-- Prerequisite: lupo_auth_users must contain a row with email = 'captain@lupopedia.com'.
-- Create that user via install wizard or registration first if needed.

-- Step 1: Ensure an actor exists for the auth user with email captain@lupopedia.com
-- (Actor may already exist from login/registration.)
-- Note: actor_id must be supplied; uses COALESCE(MAX(actor_id),0)+1 for new insert.
INSERT INTO lupo_actors (actor_id, actor_type, slug, name, created_ymdhis, updated_ymdhis, is_active, is_deleted, actor_source_id, actor_source_type)
SELECT
    (SELECT COALESCE(MAX(actor_id), 0) + 1 FROM lupo_actors),
    'user',
    CONCAT('user-', u.auth_user_id),
    COALESCE(NULLIF(TRIM(u.display_name), ''), u.username, 'Captain'),
    20260213120000,
    20260213120000,
    1,
    0,
    u.auth_user_id,
    'lupo_auth_users'
FROM lupo_auth_users u
WHERE u.email = 'captain@lupopedia.com'
  AND (u.is_deleted = 0 OR u.is_deleted IS NULL)
  AND NOT EXISTS (SELECT 1 FROM lupo_actors a WHERE a.actor_source_type = 'lupo_auth_users' AND a.actor_source_id = u.auth_user_id AND (a.is_deleted = 0 OR a.is_deleted IS NULL))
LIMIT 1;

-- Step 2: Grant captain role on channel 1 (lupo_actor_channel_roles; role_key)
-- Only insert if no active captain/administrator role already exists for this actor on channel 1
INSERT INTO lupo_actor_channel_roles (actor_channel_role_id, actor_id, channel_id, role_key, created_ymdhis, updated_ymdhis, is_deleted)
SELECT
    (SELECT COALESCE(MAX(acr.actor_channel_role_id), 0) + 1 FROM lupo_actor_channel_roles acr),
    a.actor_id,
    1,
    'captain',
    20260213120000,
    20260213120000,
    0
FROM lupo_actors a
INNER JOIN lupo_auth_users u ON u.auth_user_id = a.actor_source_id AND a.actor_source_type = 'lupo_auth_users'
WHERE u.email = 'captain@lupopedia.com'
  AND (a.is_deleted = 0 OR a.is_deleted IS NULL)
  AND (u.is_deleted = 0 OR u.is_deleted IS NULL)
  AND NOT EXISTS (
    SELECT 1 FROM lupo_actor_channel_roles acr2
    WHERE acr2.actor_id = a.actor_id AND acr2.channel_id = 1
      AND acr2.role_key IN ('captain', 'administrator')
      AND (acr2.is_deleted = 0 OR acr2.is_deleted IS NULL)
  )
LIMIT 1;
