-- One-time migration: consolidate FLIP artifact storage into lupo_artifacts.
-- FLIP v2 index now uses lupo_artifacts with entity_type = 'flip_artifact';
-- optional columns channel_id, artifact_kind, file_path_from_root; FLIP data in metadata JSON.
-- Run once on existing DBs that have lupo_flip_artifacts.

-- Add optional FLIP columns to lupo_artifacts (run once; omit if columns already exist)
ALTER TABLE lupo_artifacts ADD COLUMN channel_id bigint DEFAULT NULL;
ALTER TABLE lupo_artifacts ADD COLUMN artifact_kind varchar(50) DEFAULT NULL;
ALTER TABLE lupo_artifacts ADD COLUMN file_path_from_root varchar(500) DEFAULT NULL;

CREATE INDEX lupo_artifacts_idx_entity_channel ON lupo_artifacts (entity_type, channel_id);
CREATE INDEX lupo_artifacts_idx_file_path ON lupo_artifacts (file_path_from_root);

DROP TABLE IF EXISTS lupo_flip_artifacts;
