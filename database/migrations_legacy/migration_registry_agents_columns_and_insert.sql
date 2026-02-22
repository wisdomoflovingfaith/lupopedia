-- =============================================================================
-- ONE-TIME MIGRATION: lupo_registry — add columns required to store
-- agents (from lupo_agent_registry TOON) and insert all agents from
-- lupo_agent_registry into lupo_registry.
--
-- Doctrine: TOONs are the only source of truth. No install SQL or TOON files
-- are modified. No UNSIGNED, no display widths, no FK/triggers.
-- Idempotent: INSERT avoids duplicates (WHERE NOT EXISTS). ALTER is run-once.
-- =============================================================================

-- -----------------------------------------------------------------------------
-- 1. Missing columns (from TOON comparison)
-- -----------------------------------------------------------------------------
-- lupo_registry already has: registry_id, entity_type, entity_id,
-- entity_key, entity_name, dedicated_index_id, entity_table, federation_node_id,
-- created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis, is_active, is_kernel,
-- metadata_json.
--
-- lupo_agent_registry has (TOON): agent_registry_id, agent_registry_parent_id,
-- code, name, layer, is_required, is_active, is_kernel, dedicated_slot,
-- created_ymdhis, classification_json, metadata, agent_class, can_use_humor,
-- can_use_emotion.
--
-- Columns in agent_registry that do NOT exist in REGISTRY (by name):
--   agent_registry_parent_id, code, name, layer, is_required, classification_json,
--   agent_class, can_use_humor, can_use_emotion.
-- We do NOT add dedicated_slot (same concept as dedicated_index_id) or metadata
-- (same concept as metadata_json); use dedicated_index_id and metadata_json only.

-- -----------------------------------------------------------------------------
-- 2a. Cleanup: remove duplicate columns (run if you already ran the original
--     migration that added dedicated_slot and metadata)
-- -----------------------------------------------------------------------------
-- dedicated_index_id = same concept as dedicated_slot; metadata_json = same as metadata.
-- If you already ran the original migration and have dedicated_slot/metadata, run the DROPs.
-- If you get "Unknown column" on either DROP, that column was never added; skip that line.

ALTER TABLE lupo_registry DROP COLUMN dedicated_slot;
ALTER TABLE lupo_registry DROP COLUMN metadata;

-- -----------------------------------------------------------------------------
-- 2b. ALTER TABLE: add missing columns (run once)
-- -----------------------------------------------------------------------------
-- Doctrine: no stored procedures. MySQL has no ADD COLUMN IF NOT EXISTS.
-- We add only columns that are not duplicates: no dedicated_slot (use dedicated_index_id),
-- no metadata (use metadata_json).

ALTER TABLE lupo_registry ADD COLUMN agent_registry_parent_id bigint DEFAULT NULL;
ALTER TABLE lupo_registry ADD COLUMN code varchar(64) DEFAULT NULL;
ALTER TABLE lupo_registry ADD COLUMN name varchar(255) DEFAULT NULL;
ALTER TABLE lupo_registry ADD COLUMN layer varchar(64) DEFAULT NULL;
ALTER TABLE lupo_registry ADD COLUMN is_required tinyint NOT NULL DEFAULT 0;
ALTER TABLE lupo_registry ADD COLUMN classification_json json DEFAULT NULL;
ALTER TABLE lupo_registry ADD COLUMN agent_class varchar(64) NOT NULL DEFAULT 'production';
ALTER TABLE lupo_registry ADD COLUMN can_use_humor tinyint NOT NULL DEFAULT 0;
ALTER TABLE lupo_registry ADD COLUMN can_use_emotion tinyint NOT NULL DEFAULT 0;

-- -----------------------------------------------------------------------------
-- 3. INSERT agents from lupo_agent_registry; ON DUPLICATE KEY UPDATE overwrites
-- -----------------------------------------------------------------------------
-- Unique key (entity_type, entity_id) triggers overwrite when agent already exists.
-- Map: entity_type = 'agent', entity_id = agent_registry_id; metadata in metadata_json.
-- registry_id: next available ID for new rows (unchanged on duplicate).

SET @uid = (SELECT COALESCE(MAX(registry_id), 0) FROM lupo_registry);

INSERT INTO lupo_registry (
  registry_id,
  entity_type,
  entity_id,
  entity_key,
  entity_name,
  dedicated_index_id,
  entity_table,
  federation_node_id,
  created_ymdhis,
  updated_ymdhis,
  is_deleted,
  deleted_ymdhis,
  is_active,
  is_kernel,
  metadata_json,
  agent_registry_parent_id,
  code,
  name,
  layer,
  is_required,
  classification_json,
  agent_class,
  can_use_humor,
  can_use_emotion
)
SELECT
  @uid := @uid + 1,
  'agent',
  a.agent_registry_id,
  a.code,
  a.name,
  a.agent_registry_id,
  'lupo_agent_registry',
  1,
  a.created_ymdhis,
  a.created_ymdhis,
  0,
  NULL,
  COALESCE(a.is_active, 0),
  COALESCE(a.is_kernel, 0),
  a.metadata,
  a.agent_registry_parent_id,
  a.code,
  a.name,
  a.layer,
  COALESCE(a.is_required, 0),
  a.classification_json,
  COALESCE(a.agent_class, 'production'),
  COALESCE(a.can_use_humor, 0),
  COALESCE(a.can_use_emotion, 0)
FROM lupo_agent_registry a
ON DUPLICATE KEY UPDATE
  entity_key = VALUES(entity_key),
  entity_name = VALUES(entity_name),
  dedicated_index_id = VALUES(dedicated_index_id),
  entity_table = VALUES(entity_table),
  federation_node_id = VALUES(federation_node_id),
  created_ymdhis = VALUES(created_ymdhis),
  updated_ymdhis = VALUES(updated_ymdhis),
  is_deleted = VALUES(is_deleted),
  deleted_ymdhis = VALUES(deleted_ymdhis),
  is_active = VALUES(is_active),
  is_kernel = VALUES(is_kernel),
  metadata_json = VALUES(metadata_json),
  agent_registry_parent_id = VALUES(agent_registry_parent_id),
  code = VALUES(code),
  name = VALUES(name),
  layer = VALUES(layer),
  is_required = VALUES(is_required),
  classification_json = VALUES(classification_json),
  agent_class = VALUES(agent_class),
  can_use_humor = VALUES(can_use_humor),
  can_use_emotion = VALUES(can_use_emotion);

-- -----------------------------------------------------------------------------
-- 4. Agent identity mapping (TOON-based)
-- -----------------------------------------------------------------------------
-- Unified registry identity for agents is defined by (entity_type, entity_id).
-- entity_type = 'agent' identifies the row as an agent.
-- entity_id = agent_registry_id (same value as in lupo_agent_registry PK).
-- dedicated_index_id = agent_registry_id (dedicated slot/index for the agent).
-- entity_key = code, entity_name = name (human key and display name).
-- entity_table = 'lupo_agent_registry' (source table for the entity).
-- Existing unified rows (channels, modules, actors) are unchanged; only new
-- rows with entity_type = 'agent' are inserted from lupo_agent_registry.
