-- Migration: Drop unused columns from lupo_registry.
-- Canonical identity is (entity_type, entity_index) + entity_table; entity_key for lookup.
-- Apply after migration_unified_registry_entity_index_drop_dedicated_index.sql. Run once.

ALTER TABLE lupo_registry DROP COLUMN agent_registry_parent_id;
ALTER TABLE lupo_registry DROP COLUMN code;
ALTER TABLE lupo_registry DROP COLUMN name;
ALTER TABLE lupo_registry DROP COLUMN layer;
ALTER TABLE lupo_registry DROP COLUMN is_required;
ALTER TABLE lupo_registry DROP COLUMN classification_json;
ALTER TABLE lupo_registry DROP COLUMN agent_class;
ALTER TABLE lupo_registry DROP COLUMN can_use_humor;
ALTER TABLE lupo_registry DROP COLUMN can_use_emotion;
