-- Add columns required for unified session handling on lupo_sessions.
-- Doctrine: all timestamps BIGINT(14) YmdHis UTC set in PHP; no DB-generated timestamps.
-- Idempotent: safe to run once; if column exists, alter may error (ignore or run once).

-- 1) Widen session_id to varchar(255) for longer session IDs (e.g. from PHP session_id())
ALTER TABLE lupo_sessions MODIFY COLUMN session_id varchar(255) NOT NULL;

-- 2) Add system_context for unified auth (lupopedia / crafty_syntax / unified). Stored in PHP as YmdHis.
ALTER TABLE lupo_sessions ADD COLUMN system_context varchar(50) DEFAULT NULL AFTER session_data;
