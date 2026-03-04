-- v4.0.57 Database optimization: index additions and deduplication.
-- Idempotent: safe to run once; re-run may error on CREATE INDEX if index exists
-- (MySQL 5.7+: DROP INDEX IF EXISTS supported; no CREATE INDEX IF NOT EXISTS).
-- Doctrine: no FKs, triggers, or DB logic. Apply after install or on existing DB.
--
-- Engine variants: DROP INDEX IF EXISTS is supported in MySQL 5.7+, MariaDB 10.2.1+,
-- and PostgreSQL. No seed files are read or modified; no seed impact.

-- R2: Add index on lupo_sessions(channel_id) for channel-scoped session lookups
-- Idempotent: drop then create so re-run succeeds
DROP INDEX IF EXISTS lupo_sessions_idx_channel_id ON lupo_sessions;
CREATE INDEX lupo_sessions_idx_channel_id ON lupo_sessions (channel_id);

-- R3: Remove duplicate created_ymdhis index on lupo_unified_log (keep lupo_unified_log_idx_created_ymdhis)
DROP INDEX IF EXISTS idx_created_ymdhis ON lupo_unified_log;
