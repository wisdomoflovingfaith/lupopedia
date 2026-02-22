-- Migration: lupo_registry entity_id -> entity_index, drop dedicated_index_id.
--            lupo_registry_open add metadata_json (reference snapshot when index was freed).
-- Apply to existing Lupopedia installs that have the old columns.
-- Idempotent: run once. Doctrine: no FKs, BIGINT timestamps.

-- 1. lupo_registry: rename entity_id to entity_index
ALTER TABLE lupo_registry CHANGE COLUMN entity_id entity_index bigint NOT NULL;

-- 2. Drop the unique index on (entity_type, dedicated_index_id) before dropping the column
ALTER TABLE lupo_registry DROP INDEX lupo_registry_uniq_entity_type_dedicated_index;

-- 3. Drop the old uniq_entity index (was on entity_type, entity_id) and recreate on (entity_type, entity_index)
ALTER TABLE lupo_registry DROP INDEX lupo_registry_uniq_entity;
ALTER TABLE lupo_registry ADD UNIQUE INDEX lupo_registry_uniq_entity (entity_type, entity_index);

-- 4. Drop dedicated_index_id (redundant with entity_index)
ALTER TABLE lupo_registry DROP COLUMN dedicated_index_id;

-- 5. lupo_registry_open: add metadata_json for reference snapshot when index was freed (if table exists)
ALTER TABLE lupo_registry_open ADD COLUMN metadata_json json DEFAULT NULL;
