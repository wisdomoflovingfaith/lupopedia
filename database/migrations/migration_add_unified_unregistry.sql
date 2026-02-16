-- Migration: Add lupo_unified_unregistry (polyphonic free-index registry).
-- Apply to existing Lupopedia installs that predate this table.
-- Idempotent. Run once.
-- Doctrine: no FKs, no triggers, BIGINT timestamps, no UNSIGNED.

-- Polyphonic free-index registry for all entity types.
-- Stores freed/recycled indexes per entity_type for allocation (channel, node, agent, edge, etc.).
CREATE TABLE IF NOT EXISTS lupo_unified_unregistry (
  entity_type varchar(64) NOT NULL,
  entity_index int NOT NULL,
  federation_node_id bigint NOT NULL DEFAULT 1,
  created_utc bigint NOT NULL,
  metadata_json json DEFAULT NULL,
  PRIMARY KEY (entity_type, entity_index),
  INDEX lupo_unified_unregistry_idx_entity_type_created_utc (entity_type, created_utc)
);
