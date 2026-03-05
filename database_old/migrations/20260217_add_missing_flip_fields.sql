-- One-time migration: Missing FLIP Header fields for Lupopedia 4.0.13
-- Required by: FLIP doctrine (docs/doctrine/FLIP/FLIP_DOCTRINE.md, NOTE_HEADER_VERSION_AND_MERGE.md)
-- Table: {prefix}contents. Enables full FLIP/Wolfie header reconstruction from DB.
-- Run once on existing databases. Replace {prefix} with LUPO_TABLE_PREFIX (e.g. lupo_) before execution.
-- Doctrine: no triggers, no FKs; timestamps BIGINT; application writes values.

ALTER TABLE {prefix}contents ADD COLUMN file_last_modified_system_version varchar(20) DEFAULT NULL;
ALTER TABLE {prefix}contents ADD COLUMN file_last_modified_utc bigint DEFAULT NULL;
