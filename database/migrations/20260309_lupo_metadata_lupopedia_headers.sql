-- One-time migration: extend lupo_metadata for LUPOPEDIA HEADERS (4.0.68).
-- Adds channel_id, parent_metadata_id, class_name only. No presentation columns.
-- Table prefix: apply same to your prefix (e.g. lupo_metadata → myprefix_metadata).

ALTER TABLE lupo_metadata ADD COLUMN channel_id bigint DEFAULT NULL;
ALTER TABLE lupo_metadata ADD COLUMN parent_metadata_id bigint DEFAULT NULL;
ALTER TABLE lupo_metadata ADD COLUMN class_name varchar(128) DEFAULT NULL;

CREATE INDEX lupo_metadata_idx_channel_id ON lupo_metadata (channel_id);
CREATE INDEX lupo_metadata_idx_parent_metadata_id ON lupo_metadata (parent_metadata_id);
CREATE INDEX lupo_metadata_idx_class_name ON lupo_metadata (class_name);
CREATE INDEX lupo_metadata_idx_entity_deleted ON lupo_metadata (entity_type, entity_id, is_deleted);
CREATE INDEX lupo_metadata_idx_channel_deleted ON lupo_metadata (channel_id, is_deleted);
CREATE INDEX lupo_metadata_idx_parent_deleted ON lupo_metadata (parent_metadata_id, is_deleted);
CREATE INDEX lupo_metadata_idx_meta_type_deleted ON lupo_metadata (meta_type, is_deleted);
CREATE INDEX lupo_metadata_idx_class_deleted ON lupo_metadata (class_name, is_deleted);
