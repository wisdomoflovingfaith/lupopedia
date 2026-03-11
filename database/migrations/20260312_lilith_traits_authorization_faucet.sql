-- One-time migration: LILITH traits, edge type definitions, action authorization, faucet columns (4.0.69).
-- For existing 4.0.x databases only. New installs get schema from install_new_lupopedia.sql.
-- No Lupopedia->Lupopedia upgrade until 4.1.0; this applies to DBs that were installed before these changes.
-- Run once; then run seed_traits_edge_types_action_auth_4.0.69.sql if needed.
-- Use LUPO_TABLE_PREFIX (default lupo_). Record in lupo_schema_migrations (columns: schema_migration_id, version, name, applied_ymdhis).
-- After applying: run "python scripts/generate_toon_files.py" so lupo-database/lupopedia/toon/ matches the new schema (if script uses live DB).

-- 1. lupo_actor_traits: add federation_node_id, created_by_actor_id (run once; ignore duplicate column if re-run)
ALTER TABLE lupo_actor_traits
  ADD COLUMN federation_node_id bigint NOT NULL DEFAULT 1 AFTER trait_value,
  ADD COLUMN created_by_actor_id bigint DEFAULT NULL AFTER federation_node_id;
CREATE INDEX lupo_actor_traits_idx_federation ON lupo_actor_traits (federation_node_id);

-- 2. lupo_edge_type_definitions: create if not exists
CREATE TABLE IF NOT EXISTS lupo_edge_type_definitions (
  edge_type_definition_id bigint NOT NULL,
  edge_type varchar(100) NOT NULL,
  domain varchar(100) NOT NULL,
  description text NOT NULL,
  allowed_left_object_types text NOT NULL,
  allowed_right_object_types text NOT NULL,
  is_bidirectional tinyint NOT NULL DEFAULT 0,
  semantic_meaning text DEFAULT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  created_by_actor_id bigint NOT NULL,
  PRIMARY KEY (edge_type_definition_id),
  UNIQUE KEY lupo_edge_type_definitions_unique_edge_type (edge_type)
);
CREATE INDEX lupo_edge_type_definitions_idx_domain ON lupo_edge_type_definitions (domain);

-- 3. lupo_action_authorization: create if not exists
CREATE TABLE IF NOT EXISTS lupo_action_authorization (
  action_authorization_id bigint NOT NULL,
  action_key varchar(100) NOT NULL,
  description text NOT NULL,
  required_trait_keys text DEFAULT NULL,
  required_capabilities text DEFAULT NULL,
  required_role_keys text DEFAULT NULL,
  requires_all_conditions tinyint NOT NULL DEFAULT 0,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  created_by_actor_id bigint NOT NULL,
  PRIMARY KEY (action_authorization_id),
  UNIQUE KEY lupo_action_authorization_unique_action_key (action_key)
);
CREATE INDEX lupo_action_authorization_idx_action ON lupo_action_authorization (action_key);

-- 4. lupo_dialog_messages: add faucet columns (run once)
ALTER TABLE lupo_dialog_messages
  ADD COLUMN source_faucet_slug varchar(100) DEFAULT NULL AFTER from_actor_id,
  ADD COLUMN source_faucet_instance_id varchar(100) DEFAULT NULL AFTER source_faucet_slug;
CREATE INDEX lupo_dialog_messages_idx_faucet ON lupo_dialog_messages (source_faucet_slug, source_faucet_instance_id);

-- 5. lupo_sessions: add faucet columns (run once)
ALTER TABLE lupo_sessions
  ADD COLUMN faucet_slug varchar(100) DEFAULT NULL AFTER actor_id,
  ADD COLUMN faucet_instance_id varchar(100) DEFAULT NULL AFTER faucet_slug;
CREATE INDEX lupo_sessions_idx_faucet ON lupo_sessions (faucet_slug, faucet_instance_id);

-- 6. lupo_federation_nodes: add node_type, allows_foreign_traits (run once; node_description exists)
ALTER TABLE lupo_federation_nodes
  ADD COLUMN node_type varchar(32) NOT NULL DEFAULT 'local' AFTER federation_node_id,
  ADD COLUMN allows_foreign_traits tinyint NOT NULL DEFAULT 1 AFTER node_description;

-- 7. Record migration (columns from install: schema_migration_id, version, name, applied_ymdhis)
INSERT INTO lupo_schema_migrations (schema_migration_id, version, name, applied_ymdhis)
VALUES (20260312001, '20260312', '20260312_lilith_traits_authorization_faucet', 20260312000000);
