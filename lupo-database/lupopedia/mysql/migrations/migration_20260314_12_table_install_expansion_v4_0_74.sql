-- One-time migration: add 12 tables to an existing Lupopedia 4.0.x install (v4.0.74).
-- For fresh installs, these tables are already in install_new_lupopedia.sql; do not run this.
-- Idempotent: uses CREATE TABLE IF NOT EXISTS. Run once per existing database.
-- No FKs, no triggers; BIGINT UTC timestamps per doctrine.

-- 1. lupo_aliases
CREATE TABLE IF NOT EXISTS lupo_aliases (
  alias_id bigint NOT NULL,
  slug varchar(255) NOT NULL,
  alias varchar(255) NOT NULL,
  alias_type varchar(50) DEFAULT 'semantic',
  created_ymdhis bigint NOT NULL DEFAULT 0,
  PRIMARY KEY (alias_id)
);
CREATE UNIQUE INDEX lupo_aliases_uniq_alias ON lupo_aliases (alias);
CREATE INDEX lupo_aliases_idx_slug ON lupo_aliases (slug);

-- 2. lupo_legacy_content_mapping
CREATE TABLE IF NOT EXISTS lupo_legacy_content_mapping (
  mapping_id bigint NOT NULL,
  legacy_url varchar(255) NOT NULL,
  semantic_url varchar(255) NOT NULL,
  content_type varchar(64) NOT NULL,
  content_id bigint DEFAULT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL,
  is_active tinyint NOT NULL DEFAULT 1,
  PRIMARY KEY (mapping_id)
);
CREATE INDEX lupo_legacy_content_mapping_idx_semantic_url ON lupo_legacy_content_mapping (semantic_url);
CREATE INDEX lupo_legacy_content_mapping_idx_content_type ON lupo_legacy_content_mapping (content_type);
CREATE INDEX lupo_legacy_content_mapping_idx_content_id ON lupo_legacy_content_mapping (content_id);
CREATE INDEX lupo_legacy_content_mapping_idx_is_active ON lupo_legacy_content_mapping (is_active);
CREATE INDEX lupo_legacy_content_mapping_idx_created ON lupo_legacy_content_mapping (created_ymdhis);
-- Unique index: run only if not exists (MySQL 8.0+ may support IF NOT EXISTS for unique index; else skip if error)
-- CREATE UNIQUE INDEX lupo_legacy_content_mapping_uk_legacy_url ON lupo_legacy_content_mapping (legacy_url);

-- 3. lupo_reference_objects
CREATE TABLE IF NOT EXISTS lupo_reference_objects (
  reference_object_id bigint NOT NULL,
  object_type varchar(50) NOT NULL,
  object_slug varchar(255) NOT NULL,
  object_label varchar(255) DEFAULT NULL,
  meta_json json DEFAULT NULL,
  is_deleted tinyint NOT NULL DEFAULT 0,
  deleted_ymdhis bigint DEFAULT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL DEFAULT 0,
  PRIMARY KEY (reference_object_id)
);
CREATE INDEX lupo_reference_objects_idx_object_slug ON lupo_reference_objects (object_slug);
CREATE INDEX lupo_reference_objects_idx_type_slug ON lupo_reference_objects (object_type, object_slug);
CREATE INDEX lupo_reference_objects_idx_is_deleted ON lupo_reference_objects (is_deleted);

-- 4. lupo_reference_cited_by
CREATE TABLE IF NOT EXISTS lupo_reference_cited_by (
  reference_cited_by_id bigint NOT NULL,
  reference_object_id bigint NOT NULL,
  content_id bigint NOT NULL,
  section_anchor_slug varchar(255) DEFAULT NULL,
  section_order int NOT NULL DEFAULT 0,
  reference_type varchar(50) NOT NULL,
  raw_reference text,
  meta_json json DEFAULT NULL,
  is_deleted tinyint NOT NULL DEFAULT 0,
  deleted_ymdhis bigint DEFAULT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL DEFAULT 0,
  PRIMARY KEY (reference_cited_by_id)
);
CREATE INDEX lupo_reference_cited_by_idx_content_id ON lupo_reference_cited_by (content_id);
CREATE INDEX lupo_reference_cited_by_idx_section_anchor ON lupo_reference_cited_by (section_anchor_slug);
CREATE INDEX lupo_reference_cited_by_idx_reference_type ON lupo_reference_cited_by (reference_type);
CREATE INDEX lupo_reference_cited_by_idx_reference_object ON lupo_reference_cited_by (reference_object_id);
CREATE INDEX lupo_reference_cited_by_idx_is_deleted ON lupo_reference_cited_by (is_deleted);

-- 5. lupo_search_index
CREATE TABLE IF NOT EXISTS lupo_search_index (
  search_index_id bigint NOT NULL,
  domain_id bigint NOT NULL,
  entity_type varchar(50) NOT NULL,
  entity_id bigint NOT NULL,
  title_text text,
  body_text text,
  keywords_text text,
  search_metadata text,
  relevance_score float DEFAULT 1,
  is_deleted tinyint NOT NULL DEFAULT 0,
  deleted_ymdhis bigint DEFAULT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL,
  PRIMARY KEY (search_index_id)
);
CREATE INDEX lupo_search_index_idx_domain_type ON lupo_search_index (domain_id, entity_type);
CREATE INDEX lupo_search_index_idx_entity_reference ON lupo_search_index (entity_type, entity_id);
CREATE INDEX lupo_search_index_idx_updated ON lupo_search_index (updated_ymdhis);
CREATE INDEX lupo_search_index_idx_is_deleted ON lupo_search_index (is_deleted);
CREATE INDEX lupo_search_index_idx_relevance ON lupo_search_index (relevance_score);

-- 6. lupo_documentation_frameworks
CREATE TABLE IF NOT EXISTS lupo_documentation_frameworks (
  documentation_framework_id bigint NOT NULL,
  framework_key varchar(64) NOT NULL,
  framework_name varchar(255) NOT NULL,
  class_type varchar(64) NOT NULL DEFAULT 'documentation',
  namespace_key varchar(255) NOT NULL,
  channel_id bigint NOT NULL DEFAULT 1,
  collection_key varchar(64) NOT NULL DEFAULT 'active',
  orchestrator_actor_id bigint DEFAULT NULL,
  facet_slug varchar(64) DEFAULT NULL,
  agent_key varchar(64) DEFAULT NULL,
  role_key varchar(64) DEFAULT NULL,
  task_scope varchar(255) DEFAULT NULL,
  database_table varchar(255) DEFAULT NULL,
  runtime_min_php varchar(20) DEFAULT '5.6',
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL DEFAULT 0,
  is_deleted tinyint NOT NULL DEFAULT 0,
  deleted_ymdhis bigint DEFAULT NULL,
  properties_json json DEFAULT NULL,
  PRIMARY KEY (documentation_framework_id)
);
CREATE UNIQUE INDEX lupo_documentation_frameworks_uniq_key ON lupo_documentation_frameworks (framework_key);
CREATE INDEX lupo_documentation_frameworks_idx_namespace ON lupo_documentation_frameworks (namespace_key);
CREATE INDEX lupo_documentation_frameworks_idx_channel ON lupo_documentation_frameworks (channel_id);
CREATE INDEX lupo_documentation_frameworks_idx_collection ON lupo_documentation_frameworks (collection_key);
CREATE INDEX lupo_documentation_frameworks_idx_class ON lupo_documentation_frameworks (class_type);
CREATE INDEX lupo_documentation_frameworks_idx_is_deleted ON lupo_documentation_frameworks (is_deleted);
CREATE INDEX lupo_documentation_frameworks_idx_created ON lupo_documentation_frameworks (created_ymdhis);

-- 7. lupo_federated_trust
CREATE TABLE IF NOT EXISTS lupo_federated_trust (
  trust_id bigint NOT NULL,
  source_node_id bigint NOT NULL,
  target_node_id bigint NOT NULL,
  trust_level float DEFAULT 0.5,
  trust_type varchar(50) NOT NULL,
  capabilities json DEFAULT NULL,
  restrictions json DEFAULT NULL,
  last_verified_ymdhis bigint DEFAULT NULL,
  verification_method varchar(100) DEFAULT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint DEFAULT NULL,
  is_deleted tinyint NOT NULL DEFAULT 0,
  deleted_ymdhis bigint DEFAULT NULL,
  PRIMARY KEY (trust_id)
);
CREATE INDEX lupo_federated_trust_idx_trust_type ON lupo_federated_trust (trust_type);
CREATE INDEX lupo_federated_trust_idx_last_verified ON lupo_federated_trust (last_verified_ymdhis);
CREATE INDEX lupo_federated_trust_idx_is_deleted ON lupo_federated_trust (is_deleted);

-- 8. lupo_federation_discovery
CREATE TABLE IF NOT EXISTS lupo_federation_discovery (
  federation_discovery_id bigint NOT NULL,
  domain varchar(255) NOT NULL,
  install_url varchar(500) DEFAULT NULL,
  is_lupopedia tinyint NOT NULL DEFAULT 0,
  last_seen_ymdhis bigint DEFAULT NULL,
  first_seen_ymdhis bigint DEFAULT NULL,
  hashtag_count bigint DEFAULT NULL,
  question_count bigint DEFAULT NULL,
  atom_count bigint DEFAULT NULL,
  context_count bigint DEFAULT NULL,
  collection_count bigint DEFAULT NULL,
  keywords varchar(500) DEFAULT NULL,
  description text,
  import_hashtags tinyint NOT NULL DEFAULT 0,
  import_questions tinyint NOT NULL DEFAULT 0,
  import_atoms tinyint NOT NULL DEFAULT 0,
  import_contexts tinyint NOT NULL DEFAULT 0,
  import_collections tinyint NOT NULL DEFAULT 0,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL,
  PRIMARY KEY (federation_discovery_id)
);
CREATE INDEX lupo_federation_discovery_idx_domain ON lupo_federation_discovery (domain);

-- 9. lupo_unified_log
CREATE TABLE IF NOT EXISTS lupo_unified_log (
  log_id bigint NOT NULL AUTO_INCREMENT,
  log_type varchar(64) NOT NULL,
  log_level varchar(32) NOT NULL DEFAULT 'info',
  log_message text NOT NULL,
  log_context json,
  actor_id bigint DEFAULT NULL,
  channel_id bigint DEFAULT NULL,
  session_id varchar(128) DEFAULT NULL,
  ip_address varchar(45) DEFAULT NULL,
  user_agent text,
  created_ymdhis bigint NOT NULL,
  PRIMARY KEY (log_id)
);
CREATE INDEX lupo_unified_log_idx_actor_id ON lupo_unified_log (actor_id);
CREATE INDEX lupo_unified_log_idx_channel_id ON lupo_unified_log (channel_id);
CREATE INDEX lupo_unified_log_idx_created_ymdhis ON lupo_unified_log (created_ymdhis);
CREATE INDEX lupo_unified_log_idx_log_level ON lupo_unified_log (log_level);
CREATE INDEX lupo_unified_log_idx_log_type ON lupo_unified_log (log_type);
CREATE INDEX lupo_unified_log_idx_session_id ON lupo_unified_log (session_id);

-- 10. lupo_anubis_operations
CREATE TABLE IF NOT EXISTS lupo_anubis_operations (
  operation_id bigint NOT NULL AUTO_INCREMENT,
  operation_type varchar(64) NOT NULL,
  target_type varchar(64) NOT NULL,
  target_id bigint NOT NULL,
  channel_id bigint NOT NULL DEFAULT 42,
  actor_id bigint NOT NULL,
  faucet_id bigint DEFAULT NULL,
  details_json text DEFAULT NULL,
  created_ymdhis bigint NOT NULL,
  PRIMARY KEY (operation_id)
);
CREATE INDEX lupo_anubis_operations_idx_target ON lupo_anubis_operations (target_type, target_id);
CREATE INDEX lupo_anubis_operations_idx_type ON lupo_anubis_operations (operation_type);
CREATE INDEX lupo_anubis_operations_idx_created ON lupo_anubis_operations (created_ymdhis);

-- 11. lupo_system_health_snapshots
CREATE TABLE IF NOT EXISTS lupo_system_health_snapshots (
  snapshot_id bigint NOT NULL AUTO_INCREMENT,
  snapshot_type varchar(64) NOT NULL,
  actor_id bigint NOT NULL,
  table_count bigint DEFAULT NULL,
  schema_hash varchar(255) DEFAULT NULL,
  utc_anchor varchar(14) DEFAULT NULL,
  metadata_json text DEFAULT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  is_deleted tinyint NOT NULL DEFAULT 0,
  deleted_ymdhis bigint DEFAULT NULL,
  PRIMARY KEY (snapshot_id)
);
CREATE INDEX lupo_system_health_snapshots_idx_created ON lupo_system_health_snapshots (created_ymdhis);
CREATE INDEX lupo_system_health_snapshots_idx_type ON lupo_system_health_snapshots (snapshot_type);
CREATE INDEX lupo_system_health_snapshots_idx_is_deleted ON lupo_system_health_snapshots (is_deleted);

-- 12. lupo_hotfix_registry
CREATE TABLE IF NOT EXISTS lupo_hotfix_registry (
  hotfix_id bigint NOT NULL,
  hotfix_version varchar(20) NOT NULL,
  applied_ymdhis bigint NOT NULL,
  applied_by_actor_id bigint DEFAULT NULL,
  description text,
  metadata_json json DEFAULT NULL,
  PRIMARY KEY (hotfix_id)
);

-- End of 12-table expansion migration (v4.0.74).
