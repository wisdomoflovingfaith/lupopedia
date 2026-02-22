-- One-time migration: align lupo_sessions with TOON (name_key, is_named, channel_id, is_authenticated, system_context)
-- and ensure lupo_analytics_paths exists (repair partial install).
-- Run once after a partial or failed install. Doctrine: no FKs, no triggers, application-side logic only.
-- Table prefix: lupo_ (change if your LUPO_TABLE_PREFIX differs).
-- On re-run, ignore "Duplicate column name" for any column already present.

-- 1. Add missing columns to lupo_sessions (match TOON and Session.php)

ALTER TABLE lupo_sessions ADD COLUMN channel_id bigint NOT NULL DEFAULT 1 AFTER actor_id;
ALTER TABLE lupo_sessions ADD COLUMN name_key varchar(100) DEFAULT NULL AFTER security_level;
ALTER TABLE lupo_sessions ADD COLUMN is_named tinyint NOT NULL DEFAULT 0 AFTER name_key;
ALTER TABLE lupo_sessions ADD COLUMN is_authenticated tinyint NOT NULL DEFAULT 0 AFTER is_named;
ALTER TABLE lupo_sessions ADD COLUMN system_context varchar(50) DEFAULT NULL AFTER session_data;

-- Widen session_id if still varchar(100) to match TOON (varchar(255))
ALTER TABLE lupo_sessions MODIFY COLUMN session_id varchar(255) NOT NULL;

-- 2. Create lupo_analytics_paths if missing (e.g. install failed before reaching this table)
CREATE TABLE IF NOT EXISTS lupo_analytics_paths (
  analytics_path_id bigint NOT NULL AUTO_INCREMENT,
  from_page_id bigint DEFAULT NULL,
  to_page_id bigint DEFAULT NULL,
  year_month char(6) NOT NULL,
  transition_type varchar(64) NOT NULL,
  transition_count int NOT NULL DEFAULT 0,
  metadata_json json DEFAULT NULL,
  created_ymdhis bigint NOT NULL,
  updated_ymdhis bigint NOT NULL,
  is_deleted tinyint NOT NULL DEFAULT 0,
  deleted_ymdhis bigint DEFAULT NULL,
  PRIMARY KEY (analytics_path_id)
);
