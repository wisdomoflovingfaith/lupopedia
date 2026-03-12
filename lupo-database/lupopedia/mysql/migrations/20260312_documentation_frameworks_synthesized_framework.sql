-- One-time migration: Synthesized Documentation Framework (4.0.71).
-- Adds lupo_documentation_frameworks for framework metadata. No FKs per doctrine.
-- Table is also in future_features_lupopedia.sql for fresh installs with feature enabled.
-- Run once if documentation framework feature is enabled. Idempotent: table created only if not exists.

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

-- Run once. If table already exists, skip index creation to avoid duplicate-index errors.
CREATE UNIQUE INDEX lupo_documentation_frameworks_uniq_key ON lupo_documentation_frameworks (framework_key);
CREATE INDEX lupo_documentation_frameworks_idx_namespace ON lupo_documentation_frameworks (namespace_key);
CREATE INDEX lupo_documentation_frameworks_idx_channel ON lupo_documentation_frameworks (channel_id);
CREATE INDEX lupo_documentation_frameworks_idx_collection ON lupo_documentation_frameworks (collection_key);
CREATE INDEX lupo_documentation_frameworks_idx_class ON lupo_documentation_frameworks (class_type);
CREATE INDEX lupo_documentation_frameworks_idx_is_deleted ON lupo_documentation_frameworks (is_deleted);
CREATE INDEX lupo_documentation_frameworks_idx_created ON lupo_documentation_frameworks (created_ymdhis);
