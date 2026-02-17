-- Migration: Operator system to role-based (lupo_actor_channel_roles).
-- Apply to live DB after codebase sweep. Ensures channel_id=1 is Administration and
-- copies existing lupo_channel_roles into lupo_actor_channel_roles so permission
-- checks (now using lupo_actor_channel_roles) continue to work.
-- Doctrine: no UNSIGNED; timestamps BIGINT UTC YYYYMMDDHHIISS. Run once.

-- 1. Ensure channel_id = 1 is the global Administration channel
UPDATE lupo_channels
SET channel_key = 'administration',
    channel_slug = 'administration',
    channel_name = 'Administration',
    description = 'Global admin channel (channel_id = 1).',
    updated_ymdhis = CAST(DATE_FORMAT(UTC_TIMESTAMP(), '%Y%m%d%H%i%S') AS SIGNED)
WHERE channel_id = 1;

-- 2. Copy existing lupo_channel_roles into lupo_actor_channel_roles (idempotent: skip if already present)
-- Generates actor_channel_role_id as base + row number. MySQL 5.7/8/MariaDB.
INSERT INTO lupo_actor_channel_roles (
    actor_channel_role_id,
    actor_id,
    channel_id,
    role_key,
    created_ymdhis,
    updated_ymdhis,
    is_deleted
)
SELECT
    (SELECT COALESCE(MAX(actor_channel_role_id), 0) FROM lupo_actor_channel_roles) + row_num,
    actor_id,
    channel_id,
    role_type,
    created_ymdhis,
    updated_ymdhis,
    is_deleted
FROM (
    SELECT
        r.actor_id,
        r.channel_id,
        r.role_type,
        r.created_ymdhis,
        r.updated_ymdhis,
        r.is_deleted,
        @rn := @rn + 1 AS row_num
    FROM lupo_channel_roles r
    CROSS JOIN (SELECT @rn := 0) v
    WHERE NOT EXISTS (
        SELECT 1 FROM lupo_actor_channel_roles a
        WHERE a.actor_id = r.actor_id
          AND a.channel_id = r.channel_id
          AND a.role_key = r.role_type
          AND (a.is_deleted = 0 OR a.is_deleted IS NULL)
    )
    ORDER BY r.channel_role_id
) t;

-- Note: If the subquery for actor_channel_role_id causes duplicates (e.g. concurrent insert),
-- run this migration during low traffic or run step 2 in a loop in application code.

-- Optional: soft-delete legacy rows so they are not used (code now reads lupo_actor_channel_roles only)
-- Uncomment if you want to mark lupo_channel_roles as deprecated in place:
-- UPDATE lupo_channel_roles SET is_deleted = 1, deleted_ymdhis = CAST(DATE_FORMAT(UTC_TIMESTAMP(), '%Y%m%d%H%i%S') AS SIGNED);
