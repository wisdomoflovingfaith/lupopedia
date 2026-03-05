-- One-time migration: Index on file_path_from_root for path → content lookup (FLIP).
-- Required by: docs/doctrine/FLIP/FLIPPING_FILE_LEXA_LILITH.md (path → content_id → channel_id → actors).
-- Run once on existing databases. Replace {prefix} with LUPO_TABLE_PREFIX (e.g. lupo_) before execution.
-- Doctrine: no FKs, no triggers.

CREATE INDEX {prefix}contents_idx_file_path_from_root ON {prefix}contents (file_path_from_root);
