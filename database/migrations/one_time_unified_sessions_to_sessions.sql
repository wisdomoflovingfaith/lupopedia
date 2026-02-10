-- One-time migration: consolidate lupo_unified_sessions into lupo_sessions.
-- Run after install. Idempotent: safe to run once; no-op if lupo_unified_sessions already dropped.
-- Doctrine: single sessions table (prefix + 'sessions'); no separate unified_sessions.
-- Assumes lupo_unified_sessions stores created_at/updated_at/expires_at as Unix timestamps (seconds).

-- 1) Add system_context to lupo_sessions (skip if column already exists)
ALTER TABLE lupo_sessions ADD COLUMN system_context varchar(50) DEFAULT NULL AFTER session_data;

-- 2) Widen session_id to varchar(255) to match Laravel session IDs
ALTER TABLE lupo_sessions MODIFY COLUMN session_id varchar(255) NOT NULL;

-- 3) Migrate rows from lupo_unified_sessions into lupo_sessions (ignore duplicates on session_id)
--    Converts Unix timestamp -> YmdHis bigint for created_ymdhis, updated_ymdhis, expires_ymdhis, last_seen_ymdhis
INSERT IGNORE INTO lupo_sessions (
    session_id,
    federation_node_id,
    actor_id,
    ip_address,
    user_agent,
    session_data,
    system_context,
    last_seen_ymdhis,
    expires_ymdhis,
    created_ymdhis,
    updated_ymdhis
)
SELECT
    u.session_id,
    1,
    COALESCE(u.user_id, 0),
    '',
    '',
    CAST(u.session_data AS CHAR),
    u.system_context,
    COALESCE(
        CAST(DATE_FORMAT(FROM_UNIXTIME(u.updated_at), '%Y%m%d%H%i%s') AS UNSIGNED),
        CAST(DATE_FORMAT(FROM_UNIXTIME(u.created_at), '%Y%m%d%H%i%s') AS UNSIGNED),
        CAST(DATE_FORMAT(NOW(), '%Y%m%d%H%i%s') AS UNSIGNED)
    ),
    CASE
        WHEN u.expires_at IS NOT NULL AND u.expires_at > 0
        THEN CAST(DATE_FORMAT(FROM_UNIXTIME(u.expires_at), '%Y%m%d%H%i%s') AS UNSIGNED)
        ELSE NULL
    END,
    COALESCE(
        CAST(DATE_FORMAT(FROM_UNIXTIME(u.created_at), '%Y%m%d%H%i%s') AS UNSIGNED),
        CAST(DATE_FORMAT(NOW(), '%Y%m%d%H%i%s') AS UNSIGNED)
    ),
    COALESCE(
        CAST(DATE_FORMAT(FROM_UNIXTIME(u.updated_at), '%Y%m%d%H%i%s') AS UNSIGNED),
        CAST(DATE_FORMAT(NOW(), '%Y%m%d%H%i%s') AS UNSIGNED)
    )
FROM lupo_unified_sessions u
WHERE u.session_id IS NOT NULL AND TRIM(u.session_id) != '';

-- 4) Drop the legacy table
DROP TABLE IF EXISTS lupo_unified_sessions;
