-- Temporary database column rename for canonical mood_vector identifier.
-- Do not execute automatically. Run through the safe migration wrapper when captain approves.
-- MySQL/MariaDB dialect follows existing project CHANGE syntax.
-- Scope: active install schema only. Future-feature tables are intentionally excluded.

ALTER TABLE lupo_dialog_messages CHANGE mood_rgb mood_vector char(6) DEFAULT NULL;
