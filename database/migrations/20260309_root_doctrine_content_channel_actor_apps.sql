-- Lupopedia one-time migration
-- 20260309_root_doctrine_content_channel_actor_apps.sql
-- Source: ROOT/captain wolfie emails (canonical content, channel-departments, actor apps, schema_migrations)
-- Run once against existing DB. Install SQL already includes these changes for fresh installs.
-- Doctrine: no FKs, no triggers; BIGINT timestamps; PK naming <table>_id.

-- ========== 1. lupo_contents: channel placement and federation source URL ==========
-- Add channel_id so content can declare which channel it belongs to.
-- Add federation_source_url for canonical URL at source federation node.
ALTER TABLE lupo_contents
  ADD COLUMN federation_source_url varchar(2000) DEFAULT NULL COMMENT 'Canonical URL of content at source federation node' AFTER federation_node_id,
  ADD COLUMN channel_id bigint DEFAULT NULL COMMENT 'Channel this content belongs to (doctrine: content placement)' AFTER federation_source_url;

CREATE INDEX lupo_contents_idx_channel_id ON lupo_contents (channel_id);

-- ========== 2. lupo_channel_departments (channels <-> departments many-to-many) ==========
CREATE TABLE IF NOT EXISTS lupo_channel_departments (
  channel_department_id bigint NOT NULL,
  channel_id bigint NOT NULL,
  department_id bigint NOT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  PRIMARY KEY (channel_department_id)
);

CREATE UNIQUE INDEX lupo_channel_departments_unq_channel_department ON lupo_channel_departments (channel_id, department_id);
CREATE INDEX lupo_channel_departments_idx_channel ON lupo_channel_departments (channel_id);
CREATE INDEX lupo_channel_departments_idx_department ON lupo_channel_departments (department_id);

-- ========== 3. lupo_schema_migrations (track applied migrations) ==========
CREATE TABLE IF NOT EXISTS lupo_schema_migrations (
  schema_migration_id bigint NOT NULL,
  version varchar(64) NOT NULL,
  name varchar(255) NOT NULL,
  applied_ymdhis bigint NOT NULL DEFAULT 0,
  PRIMARY KEY (schema_migration_id)
);

CREATE UNIQUE INDEX lupo_schema_migrations_unq_version ON lupo_schema_migrations (version);
CREATE INDEX lupo_schema_migrations_idx_applied ON lupo_schema_migrations (applied_ymdhis);

-- ========== 4. lupo_actor_apps (actor application folder tracking) ==========
CREATE TABLE IF NOT EXISTS lupo_actor_apps (
  actor_app_id bigint NOT NULL,
  actor_id bigint NOT NULL,
  apps_path varchar(512) NOT NULL DEFAULT '',
  updated_ymdhis bigint NOT NULL DEFAULT 0,
  PRIMARY KEY (actor_app_id)
);

CREATE UNIQUE INDEX lupo_actor_apps_unq_actor ON lupo_actor_apps (actor_id);
CREATE INDEX lupo_actor_apps_idx_updated ON lupo_actor_apps (updated_ymdhis);

-- Record this migration (application must allocate schema_migration_id if using explicit IDs)
-- INSERT INTO lupo_schema_migrations (schema_migration_id, version, name, applied_ymdhis)
-- VALUES (1, '20260309', 'root_doctrine_content_channel_actor_apps', YYYYMMDDHHIISS);
