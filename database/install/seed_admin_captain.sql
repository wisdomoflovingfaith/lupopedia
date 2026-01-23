-- ============================================================
-- Lupopedia 4.0.9 — Canonical Admin Seed
-- Identity: email-only login
-- Actor slug: derived from email (local + -at- + domain)
-- Timestamp doctrine: BIGINT(14) UTC YYYYMMDDHHMMSS
-- Idempotent: safe to run multiple times
-- ============================================================

-- 1. Set deterministic timestamp for seed operations
SET @now = 20260112195500;

-- 2. Canonical admin identity
SET @admin_email = 'lupopedia@gmail.com';
SET @admin_slug  = 'lupopedia-at-gmail-com';
SET @admin_name  = 'Captain';
SET @admin_username = 'captain';  -- Legacy field (NOT NULL constraint), derived from email local part

-- ============================================================
-- AUTH USER
-- ============================================================

INSERT INTO `lupo_auth_users` (
    `auth_user_id`,
    `username`,          -- deprecated, kept for archaeology only
    `display_name`,
    `email`,
    `password_hash`,     -- MD5 legacy hash (will be upgraded on login)
    `auth_provider`,
    `provider_id`,
    `profile_image_url`,
    `last_login_ymdhis`,
    `created_ymdhis`,
    `updated_ymdhis`,
    `is_active`,
    `is_deleted`,
    `deleted_ymdhis`
)
VALUES (
    1,
    @admin_username,                       -- Legacy field (NOT NULL), kept for archaeology
    @admin_name,
    @admin_email,
    'ab334feeb31c05124cb73fa12571c2f6',     -- MD5("captain")
    NULL,
    NULL,
    NULL,
    NULL,
    @now,
    @now,
    1,
    0,
    NULL
)
ON DUPLICATE KEY UPDATE
    `username`       = VALUES(`username`),
    `display_name`   = VALUES(`display_name`),
    `email`          = VALUES(`email`),
    `password_hash`  = VALUES(`password_hash`),
    `updated_ymdhis` = @now,
    `is_active`      = 1,
    `is_deleted`     = 0;

-- ============================================================
-- ACTOR
-- ============================================================

-- 3. Lookup existing actor by slug or source reference
SELECT actor_id INTO @captain_actor_id
FROM `lupo_actors`
WHERE (
        `slug` = @admin_slug
        OR (`actor_source_id` = 1 AND `actor_source_type` = 'user')
      )
  AND `is_deleted` = 0
LIMIT 1;

-- 4. Determine next actor_id if none exists
SET @next_actor_id = COALESCE(
    @captain_actor_id,
    (SELECT COALESCE(MAX(actor_id), 0) + 1 FROM `lupo_actors`)
);

-- 5. Insert or update actor
INSERT INTO `lupo_actors` (
    `actor_id`,
    `actor_type`,
    `slug`,
    `name`,
    `created_ymdhis`,
    `updated_ymdhis`,
    `is_active`,
    `is_deleted`,
    `deleted_ymdhis`,
    `actor_source_id`,
    `actor_source_type`,
    `metadata`
)
VALUES (
    @next_actor_id,
    'user',
    @admin_slug,
    @admin_name,
    @now,
    @now,
    1,
    0,
    NULL,
    1,
    'user',
    NULL
)
ON DUPLICATE KEY UPDATE
    `actor_type`        = VALUES(`actor_type`),
    `slug`              = VALUES(`slug`),
    `name`              = VALUES(`name`),
    `updated_ymdhis`    = @now,
    `is_active`         = 1,
    `is_deleted`        = 0,
    `deleted_ymdhis`    = NULL,
    `actor_source_id`   = VALUES(`actor_source_id`),
    `actor_source_type` = VALUES(`actor_source_type`);

-- 6. Re-fetch actor_id (in case it was created)
SELECT actor_id INTO @captain_actor_id
FROM `lupo_actors`
WHERE `slug` = @admin_slug
  AND `is_deleted` = 0
LIMIT 1;

-- ============================================================
-- ADMIN ROLE
-- ============================================================

-- 7. Determine next actor_role_id
SET @new_role_id = (SELECT COALESCE(MAX(actor_role_id), 0) + 1 FROM `lupo_actor_roles`);

-- 8. Cleanup any existing admin role for this actor
DELETE FROM `lupo_actor_roles`
WHERE `actor_id` = @captain_actor_id
  AND `role_key` = 'admin'
  AND `is_deleted` = 0;

-- 9. Insert admin role
INSERT INTO `lupo_actor_roles` (
    `actor_role_id`,
    `actor_id`,
    `context_id`,
    `department_id`,
    `role_key`,
    `role_description`,
    `weight`,
    `created_ymdhis`,
    `updated_ymdhis`,
    `is_deleted`,
    `deleted_ymdhis`
)
VALUES (
    @new_role_id,
    @captain_actor_id,
    0,
    NULL,
    'admin',
    'System Administrator - Full access to all admin functions',
    1.0,
    @now,
    @now,
    0,
    NULL
);
