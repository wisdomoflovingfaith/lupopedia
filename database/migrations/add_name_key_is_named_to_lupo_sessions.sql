-- Add name_key and is_named to lupo_sessions (after security_level per TOON).
-- Run once per environment. If columns already exist you will get duplicate column errors; ignore or skip.
-- name_key: VARCHAR(100) NULL. is_named: TINYINT default 0.

ALTER TABLE lupo_sessions ADD COLUMN name_key varchar(100) DEFAULT NULL AFTER security_level;
ALTER TABLE lupo_sessions ADD COLUMN is_named tinyint NOT NULL DEFAULT 0 AFTER name_key;
