-- One-time migration: FLIP Header support for Lupopedia 4.0.13
-- Required by: FLIP doctrine (docs/doctrine/FLIP/), loader (scripts/import_os.py)
-- Table: {prefix}contents. Column: file_path_from_root (path from repo root; used by FLIP Header ingestion).
-- Run once on existing databases created before this column was in the canonical install.
-- Replace {prefix} with LUPO_TABLE_PREFIX (e.g. lupo_) before execution.
-- Doctrine: no triggers, no defaults, no DB-side logic; application writes values.

ALTER TABLE {prefix}contents ADD COLUMN file_path_from_root varchar(500) DEFAULT NULL;
