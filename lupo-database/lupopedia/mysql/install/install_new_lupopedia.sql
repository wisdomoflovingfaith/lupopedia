-- Install schema for Lupopedia 4.0.x. Single upgrade path: Crafty Syntax 3.7.5 -> Lupopedia 4.0.x only.
-- No Lupopedia->Lupopedia upgrade until 4.1.0. All schema for 4.0.x is in this file.
-- No Crafty Syntax logic, no migration, no DROP TABLE.
SET @now = 20260224000000;

CREATE TABLE {{prefix}}actors (
  actor_name varchar(64) NOT NULL,
  actor_id bigint DEFAULT NULL,
  actor_type varchar(64) NOT NULL,
  slug varchar(255) NOT NULL,
  name varchar(255) NOT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL,
  is_active tinyint NOT NULL DEFAULT 1,
  is_deleted tinyint NOT NULL DEFAULT 0,
  deleted_ymdhis bigint DEFAULT NULL,
  actor_source_id bigint DEFAULT NULL,
  actor_source_type varchar(64) DEFAULT NULL,
  metadata text,
  actor_config text,
  adversarial_role varchar(64) DEFAULT 'none',
  adversarial_oversight_actor_id bigint DEFAULT NULL,
  avatar_hash varchar(64) DEFAULT NULL,
  primary_federation_node_id bigint NOT NULL DEFAULT 1,
  department_id bigint DEFAULT NULL,
  is_kernel tinyint NOT NULL DEFAULT 0,
  can_login tinyint NOT NULL DEFAULT 0,
  metadata_json json DEFAULT NULL,
  identity_provider_config json DEFAULT NULL,
  paired_actor_id bigint NOT NULL DEFAULT 0,
  is_agent tinyint NOT NULL DEFAULT 0,
  auth_user_id bigint DEFAULT NULL,
  actor_tier tinyint DEFAULT 3,
  actor_root_path varchar(512) DEFAULT 'actors/{actor_id}',
  workspace_path varchar(255) NULL DEFAULT NULL,
  php_namespace varchar(120) NULL DEFAULT NULL,
  who_json_sync_status varchar(64) DEFAULT 'pending',
  last_sync_ymdhis bigint DEFAULT 0,
  PRIMARY KEY (actor_name)
);

CREATE UNIQUE INDEX {{prefix}}actors_unique_actor_id ON {{prefix}}actors (actor_id);
CREATE UNIQUE INDEX {{prefix}}actors_unique_slug ON {{prefix}}actors (slug);
CREATE INDEX {{prefix}}actors_idx_actor_type ON {{prefix}}actors (actor_type);
CREATE INDEX {{prefix}}actors_idx_is_active ON {{prefix}}actors (is_active);
CREATE INDEX {{prefix}}actors_idx_created_ymdhis ON {{prefix}}actors (created_ymdhis);
CREATE INDEX {{prefix}}actors_idx_workspace_path ON {{prefix}}actors (workspace_path);
CREATE INDEX {{prefix}}actors_idx_php_namespace ON {{prefix}}actors (php_namespace);
-- RESERVED ID DOCTRINE: actor_id is NOT ; application must supply explicit ID.
-- ACTOR PRIMARY KEY DOCTRINE: actor_name is canonical; use ActorService::getActorByName / resolveActor.

CREATE TABLE {{prefix}}registry (
  registry_id bigint NOT NULL,
  entity_type varchar(64) NOT NULL,
  entity_index_id bigint NOT NULL,
  entity_index bigint NOT NULL,
  federation_node_id bigint DEFAULT 1,
  reserved_ymdhis bigint NOT NULL DEFAULT 0,
  entity_key varchar(255) NOT NULL,
  entity_name varchar(255) DEFAULT NULL,
  entity_table varchar(255) DEFAULT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL DEFAULT 0,
  is_deleted tinyint NOT NULL DEFAULT '0',
  is_active tinyint NOT NULL DEFAULT '1',
  is_kernel tinyint NOT NULL DEFAULT '0',
  metadata_json text,
  PRIMARY KEY (registry_id)
);
CREATE INDEX {{prefix}}registry_idx_entity_type ON {{prefix}}registry (entity_type);
CREATE INDEX {{prefix}}registry_idx_entity_index ON {{prefix}}registry (entity_index);
CREATE INDEX {{prefix}}registry_idx_entity_index_id ON {{prefix}}registry (entity_index_id);
CREATE INDEX {{prefix}}registry_idx_entity_key ON {{prefix}}registry (entity_key);
CREATE INDEX {{prefix}}registry_idx_entity_type_deleted ON {{prefix}}registry (entity_type, is_deleted);

CREATE TABLE {{prefix}}banned_actors (
  banned_actor_id bigint NOT NULL,
  actor_id bigint NOT NULL,
  actor_name varchar(64) DEFAULT NULL,
  ip_address varchar(45) DEFAULT NULL,
  reason varchar(500) NOT NULL,
  banned_ymdhis bigint NOT NULL,
  banned_by_actor_id bigint DEFAULT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL,
  is_deleted tinyint NOT NULL DEFAULT '0',
  deleted_ymdhis bigint DEFAULT NULL,
  PRIMARY KEY (banned_actor_id)
);

CREATE INDEX {{prefix}}banned_actors_idx_actor_id ON {{prefix}}banned_actors (actor_id);
CREATE INDEX {{prefix}}banned_actors_idx_actor_name ON {{prefix}}banned_actors (actor_name);
CREATE INDEX {{prefix}}banned_actors_idx_ip_address ON {{prefix}}banned_actors (ip_address);
CREATE INDEX {{prefix}}banned_actors_idx_is_deleted ON {{prefix}}banned_actors (is_deleted);
-- Banned actors: ANUBIS does not adopt orphans from these actor_ids. Single source of truth for bans.

CREATE TABLE {{prefix}}bans_log (
  bans_log_id bigint NOT NULL ,
  actor_id bigint NOT NULL,
  uri varchar(1024) NOT NULL DEFAULT '',
  resolved_uri varchar(1024) NOT NULL DEFAULT '',
  ban_scope varchar(64) NOT NULL DEFAULT 'router',
  banned_ymdhis bigint NOT NULL,
  user_agent varchar(500) DEFAULT NULL,
  ip_address varchar(45) DEFAULT NULL,
  PRIMARY KEY (bans_log_id)
);

CREATE INDEX {{prefix}}bans_log_idx_actor_id ON {{prefix}}bans_log (actor_id);
CREATE INDEX {{prefix}}bans_log_idx_banned_ymdhis ON {{prefix}}bans_log (banned_ymdhis);
CREATE INDEX {{prefix}}bans_log_idx_ban_scope ON {{prefix}}bans_log (ban_scope);
-- Router Ban at Gate (4.0.86 T7): audit log for 403 events; {{prefix}}log_ban_event() writes here.

CREATE TABLE {{prefix}}actor_actions (
  actor_action_id bigint NOT NULL,
  actor_id bigint NOT NULL,
  action_type varchar(64) NOT NULL,
  entity_type varchar(64) DEFAULT NULL,
  entity_id bigint DEFAULT NULL,
  description text,
  metadata_json json DEFAULT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  PRIMARY KEY (actor_action_id)
);

CREATE INDEX {{prefix}}actor_actions_idx_actor ON {{prefix}}actor_actions (actor_id);
CREATE INDEX {{prefix}}actor_actions_idx_action_type ON {{prefix}}actor_actions (action_type);
CREATE INDEX {{prefix}}actor_actions_idx_entity ON {{prefix}}actor_actions (entity_type, entity_id);

CREATE TABLE {{prefix}}actor_capabilities (
  actor_capability_id bigint NOT NULL,
  actor_id bigint NOT NULL,
  domain_id bigint NOT NULL,
  capability_key varchar(100) NOT NULL,
  capability_description text,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint DEFAULT NULL,
  is_deleted tinyint NOT NULL DEFAULT '0',
  deleted_ymdhis bigint DEFAULT NULL,
  scope_limitation varchar(50) DEFAULT 'unrestricted',
  max_calls_per_hour int DEFAULT '0',
  requires_approval tinyint DEFAULT '0',
  approval_agent_id bigint DEFAULT NULL,
  PRIMARY KEY (actor_capability_id)
);

CREATE UNIQUE INDEX {{prefix}}actor_capabilities_unique_agent_domain_capability ON {{prefix}}actor_capabilities (actor_id, domain_id, capability_key);
CREATE INDEX {{prefix}}actor_capabilities_idx_agent_domain ON {{prefix}}actor_capabilities (actor_id, domain_id);
CREATE INDEX {{prefix}}actor_capabilities_idx_domain_id ON {{prefix}}actor_capabilities (domain_id);
CREATE INDEX {{prefix}}actor_capabilities_idx_capability_key ON {{prefix}}actor_capabilities (capability_key);
CREATE INDEX {{prefix}}actor_capabilities_idx_created_ymdhis ON {{prefix}}actor_capabilities (created_ymdhis);
CREATE INDEX {{prefix}}actor_capabilities_idx_updated_ymdhis ON {{prefix}}actor_capabilities (updated_ymdhis);
CREATE INDEX {{prefix}}actor_capabilities_idx_is_deleted ON {{prefix}}actor_capabilities (is_deleted);

CREATE TABLE {{prefix}}actor_channels (
  actor_channel_id bigint NOT NULL,
  actor_id bigint NOT NULL,
  actor_name varchar(64) DEFAULT NULL,
  created_by_actor_id bigint NOT NULL DEFAULT 0,
  channel_id bigint NOT NULL,
  status char(1) NOT NULL DEFAULT 'A',
  start_date bigint DEFAULT NULL,
  channel_color varchar(6) NOT NULL DEFAULT 'F7FAFF',
  last_read_ymdhis bigint DEFAULT NULL,
  muted_until_ymdhis bigint DEFAULT NULL,
  preferences_json json DEFAULT NULL,
  dialog_output_file varchar(500) DEFAULT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL,
  is_deleted tinyint NOT NULL DEFAULT '0',
  deleted_ymdhis bigint DEFAULT NULL,
  PRIMARY KEY (actor_channel_id)
);

CREATE UNIQUE INDEX {{prefix}}actor_channels_unq_actor_channel ON {{prefix}}actor_channels (actor_id, channel_id);
CREATE INDEX {{prefix}}actor_channels_idx_actor ON {{prefix}}actor_channels (actor_id);
CREATE INDEX {{prefix}}actor_channels_idx_actor_name ON {{prefix}}actor_channels (actor_name);
CREATE INDEX {{prefix}}actor_channels_idx_channel ON {{prefix}}actor_channels (channel_id);
CREATE INDEX {{prefix}}actor_channels_idx_status ON {{prefix}}actor_channels (status);
CREATE INDEX {{prefix}}actor_channels_idx_created ON {{prefix}}actor_channels (created_ymdhis);
CREATE INDEX {{prefix}}actor_channels_idx_updated ON {{prefix}}actor_channels (updated_ymdhis);
CREATE INDEX {{prefix}}actor_channels_idx_deleted ON {{prefix}}actor_channels (is_deleted);

 
  -- NEW IDENTITY MODEL: AGENT → ACTOR (INSTANCE) → AUTH USER (LEASED)
  -- See ACTOR_LEASING_DOCTRINE.md for full doctrine and PRD.

  CREATE TABLE {{prefix}}actor_templates (
    template_id bigint NOT NULL,
    agent_id bigint NOT NULL,
    template_version varchar(64) NOT NULL,
    created_ymdhis bigint NOT NULL DEFAULT 0,
    is_deleted tinyint NOT NULL DEFAULT 0,
    deleted_ymdhis bigint DEFAULT 0,
    PRIMARY KEY (template_id)
  );

  CREATE TABLE {{prefix}}actor_instances (
    actor_id bigint NOT NULL,
    template_id bigint NOT NULL,
    department_id bigint NOT NULL,
    created_ymdhis bigint NOT NULL DEFAULT 0,
    is_available tinyint NOT NULL DEFAULT 1,
    is_deleted tinyint NOT NULL DEFAULT 0,
    deleted_ymdhis bigint DEFAULT 0,
    PRIMARY KEY (actor_id)
  );

  CREATE TABLE {{prefix}}actor_lease_sessions (
    lease_id bigint NOT NULL,
    actor_id bigint NOT NULL,
    auth_user_id bigint NOT NULL,
    department_id bigint NOT NULL,
    started_ymdhis bigint NOT NULL,
    ended_ymdhis bigint DEFAULT 0,
    is_active tinyint NOT NULL DEFAULT 1,
    PRIMARY KEY (lease_id)
  );

CREATE TABLE {{prefix}}department_actor_pools (
    pool_id bigint NOT NULL,
    department_id bigint NOT NULL,
    actor_id bigint NOT NULL,
    priority int NOT NULL DEFAULT 100,
    is_enabled tinyint NOT NULL DEFAULT 1,
    PRIMARY KEY (pool_id)
  );

CREATE TABLE {{prefix}}actor_auth_users (
  actor_auth_user_id bigint NOT NULL,
  actor_id bigint NOT NULL,
  auth_user_id bigint NOT NULL,
  relationship_role varchar(64) NOT NULL DEFAULT 'supporting_human',
  -- is_primary must be application-enforced as 0 or 1.
  is_primary tinyint NOT NULL DEFAULT '0',
  -- routing_priority must be application-enforced as non-negative (>= 0).
  routing_priority smallint NOT NULL DEFAULT '100',
  -- Allowed status values (application-enforced): 'active', 'inactive', 'disabled'.
  status varchar(32) NOT NULL DEFAULT 'active',
  metadata_json json DEFAULT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL,
  is_deleted tinyint NOT NULL DEFAULT '0',
  deleted_ymdhis bigint DEFAULT 0,
  PRIMARY KEY (actor_auth_user_id)
);

CREATE UNIQUE INDEX {{prefix}}actor_auth_users_unq_actor_user_role ON {{prefix}}actor_auth_users (actor_id, auth_user_id, relationship_role);
CREATE INDEX {{prefix}}actor_auth_users_idx_auth_user_status ON {{prefix}}actor_auth_users (auth_user_id, status);
CREATE INDEX {{prefix}}actor_auth_users_idx_actor_status_primary_priority ON {{prefix}}actor_auth_users (actor_id, status, is_primary, routing_priority);
CREATE INDEX {{prefix}}actor_auth_users_idx_actor_role_primary_lookup ON {{prefix}}actor_auth_users (actor_id, relationship_role, status, is_deleted, is_primary, routing_priority, auth_user_id);
CREATE INDEX {{prefix}}actor_auth_users_idx_actor_routing ON {{prefix}}actor_auth_users (actor_id, status, is_deleted, relationship_role, is_primary, routing_priority, auth_user_id);
CREATE INDEX {{prefix}}actor_auth_users_idx_status ON {{prefix}}actor_auth_users (status);

-- Auth-user to department relationship mapping (many-to-many, doctrine-safe, no FKs)
CREATE TABLE {{prefix}}auth_user_departments (
  auth_user_department_id bigint NOT NULL,
  auth_user_id bigint NOT NULL,
  department_id bigint NOT NULL,
  is_primary tinyint NOT NULL DEFAULT '0',
  role_key varchar(64) DEFAULT NULL,
  title varchar(64) DEFAULT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL,
  is_deleted tinyint NOT NULL DEFAULT '0',
  deleted_ymdhis bigint DEFAULT NULL,
  PRIMARY KEY (auth_user_department_id)
);

CREATE INDEX {{prefix}}auth_user_departments_idx_auth_user ON {{prefix}}auth_user_departments (auth_user_id);
CREATE INDEX {{prefix}}auth_user_departments_idx_department ON {{prefix}}auth_user_departments (department_id);
CREATE INDEX {{prefix}}auth_user_departments_idx_primary ON {{prefix}}auth_user_departments (auth_user_id, is_primary);

CREATE TABLE {{prefix}}actor_channel_roles (
  actor_channel_role_id bigint NOT NULL,
  actor_id bigint NOT NULL,
  actor_name varchar(64) DEFAULT NULL,
  channel_id bigint NOT NULL,
  role_key varchar(64) NOT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL,
  is_deleted tinyint NOT NULL DEFAULT '0',
  deleted_ymdhis bigint DEFAULT NULL,
  handshake_metadata_json json DEFAULT NULL,
  awareness_snapshot_json json DEFAULT NULL,
  protocol_completion_status varchar(64) DEFAULT 'pending',
  protocol_version varchar(20) DEFAULT '3.0.0',
  join_sequence_step tinyint DEFAULT '0',
  handshake_completed_ymdhis bigint DEFAULT NULL,
  awareness_completed_ymdhis bigint DEFAULT NULL,
  cjp_completed_ymdhis bigint DEFAULT NULL,
  PRIMARY KEY (actor_channel_role_id)
);

CREATE INDEX {{prefix}}actor_channel_roles_idx_actor_id ON {{prefix}}actor_channel_roles (actor_id);
CREATE INDEX {{prefix}}actor_channel_roles_idx_actor_name ON {{prefix}}actor_channel_roles (actor_name);
CREATE INDEX {{prefix}}actor_channel_roles_idx_channel_id ON {{prefix}}actor_channel_roles (channel_id);
CREATE INDEX {{prefix}}actor_channel_roles_idx_role_key ON {{prefix}}actor_channel_roles (role_key);
CREATE INDEX {{prefix}}actor_channel_roles_idx_protocol_completion_status ON {{prefix}}actor_channel_roles (protocol_completion_status);
CREATE INDEX {{prefix}}actor_channel_roles_idx_join_sequence_step ON {{prefix}}actor_channel_roles (join_sequence_step);
CREATE INDEX {{prefix}}actor_channel_roles_idx_protocol_version ON {{prefix}}actor_channel_roles (protocol_version);

CREATE TABLE {{prefix}}actor_collections (
  actor_collection_id bigint NOT NULL,
  actor_id bigint NOT NULL,
  collection_id bigint NOT NULL,
  access_level varchar(64) NOT NULL DEFAULT 'read',
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint DEFAULT NULL,
  is_deleted tinyint NOT NULL DEFAULT '0',
  deleted_ymdhis bigint DEFAULT NULL,
  persistent_identity_json json DEFAULT NULL,
  identity_signature varchar(255) DEFAULT NULL,
  trust_level varchar(64) DEFAULT 'standard',
  emotional_geometry_baseline json DEFAULT NULL,
  doctrine_alignment_version varchar(20) DEFAULT '3.0.0',
  PRIMARY KEY (actor_collection_id)
);

CREATE INDEX {{prefix}}actor_collections_idx_actor ON {{prefix}}actor_collections (actor_id);
CREATE INDEX {{prefix}}actor_collections_idx_collection ON {{prefix}}actor_collections (collection_id);
CREATE INDEX {{prefix}}actor_collections_idx_access_level ON {{prefix}}actor_collections (access_level);
CREATE INDEX {{prefix}}actor_collections_idx_created_ymdhis ON {{prefix}}actor_collections (created_ymdhis);
CREATE INDEX {{prefix}}actor_collections_idx_is_deleted ON {{prefix}}actor_collections (is_deleted);

CREATE TABLE {{prefix}}actor_traits (
  actor_trait_id bigint NOT NULL,
  actor_id bigint NOT NULL,
  trait_key varchar(128) NOT NULL,
  trait_value varchar(255) DEFAULT NULL,
  federation_node_id bigint DEFAULT 1,
  created_by_actor_id bigint DEFAULT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL DEFAULT 0,
  is_deleted tinyint NOT NULL DEFAULT '0',
  deleted_ymdhis bigint DEFAULT NULL,
  metadata text,
  PRIMARY KEY (actor_trait_id)
);
CREATE INDEX {{prefix}}actor_traits_idx_actor ON {{prefix}}actor_traits (actor_id);
CREATE INDEX {{prefix}}actor_traits_idx_trait_key ON {{prefix}}actor_traits (trait_key);
CREATE INDEX {{prefix}}actor_traits_idx_federation ON {{prefix}}actor_traits (federation_node_id);
CREATE INDEX {{prefix}}actor_traits_idx_deleted ON {{prefix}}actor_traits (is_deleted);
CREATE INDEX {{prefix}}actor_collections_idx_identity_signature ON {{prefix}}actor_collections (identity_signature);
CREATE INDEX {{prefix}}actor_collections_idx_trust_level ON {{prefix}}actor_collections (trust_level);

CREATE TABLE {{prefix}}actor_conflicts (
  actor_conflict_id bigint NOT NULL,
  domain_id bigint NOT NULL DEFAULT '1',
  actor_a_id bigint NOT NULL,
  actor_b_id bigint NOT NULL,
  conflict_type varchar(64) NOT NULL,
  conflict_summary text NOT NULL,
  resolution_status varchar(64) NOT NULL DEFAULT 'unresolved',
  resolution_summary text,
  resolved_by bigint DEFAULT NULL,
  resolved_ymdhis bigint DEFAULT NULL,
  severity varchar(64) NOT NULL DEFAULT 'medium',
  context_json json DEFAULT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL,
  is_deleted tinyint NOT NULL DEFAULT '0',
  deleted_ymdhis bigint DEFAULT NULL,
  PRIMARY KEY (actor_conflict_id)
);

CREATE INDEX {{prefix}}actor_conflicts_idx_agent_a ON {{prefix}}actor_conflicts (actor_a_id);
CREATE INDEX {{prefix}}actor_conflicts_idx_agent_b ON {{prefix}}actor_conflicts (actor_b_id);
CREATE INDEX {{prefix}}actor_conflicts_idx_status ON {{prefix}}actor_conflicts (resolution_status);
CREATE INDEX {{prefix}}actor_conflicts_idx_domain ON {{prefix}}actor_conflicts (domain_id);
CREATE INDEX {{prefix}}actor_conflicts_idx_severity ON {{prefix}}actor_conflicts (severity);
CREATE INDEX {{prefix}}actor_conflicts_idx_created ON {{prefix}}actor_conflicts (created_ymdhis);
CREATE INDEX {{prefix}}actor_conflicts_idx_updated ON {{prefix}}actor_conflicts (updated_ymdhis);
CREATE INDEX {{prefix}}actor_conflicts_idx_deleted ON {{prefix}}actor_conflicts (is_deleted);
CREATE INDEX {{prefix}}actor_conflicts_idx_resolved_ymdhis ON {{prefix}}actor_conflicts (resolved_ymdhis);
CREATE INDEX {{prefix}}actor_conflicts_idx_agent_pair ON {{prefix}}actor_conflicts (actor_a_id, actor_b_id);
CREATE INDEX {{prefix}}actor_conflicts_idx_conflict_type ON {{prefix}}actor_conflicts (conflict_type);

CREATE TABLE {{prefix}}actor_departments (
  actor_department_id bigint NOT NULL,
  actor_id bigint NOT NULL,
  department_id bigint NOT NULL,
  role_key varchar(64) DEFAULT NULL,
  title varchar(64) DEFAULT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL,
  is_deleted tinyint NOT NULL DEFAULT '0',
  deleted_ymdhis bigint DEFAULT NULL,
  PRIMARY KEY (actor_department_id)
);

CREATE INDEX {{prefix}}actor_departments_idx_actor ON {{prefix}}actor_departments (actor_id);
CREATE INDEX {{prefix}}actor_departments_idx_department ON {{prefix}}actor_departments (department_id);

-- Actor application folder tracking (doctrine: /uploads/actors/{actor_id}/apps/ with skills, assets, manifest.json)
CREATE TABLE {{prefix}}actor_apps (
  actor_app_id bigint NOT NULL,
  actor_id bigint NOT NULL,
  apps_path varchar(512) NOT NULL DEFAULT '',
  updated_ymdhis bigint NOT NULL DEFAULT 0,
  PRIMARY KEY (actor_app_id)
);

CREATE UNIQUE INDEX {{prefix}}actor_apps_unq_actor ON {{prefix}}actor_apps (actor_id);
CREATE INDEX {{prefix}}actor_apps_idx_updated ON {{prefix}}actor_apps (updated_ymdhis);

-- Canonical Option A: Edges Table (restored)

-- Canonical Option A: Edges Table (restored)
CREATE TABLE {{prefix}}edges (
  edge_id bigint NOT NULL,
  left_object_type varchar(50) NOT NULL,
  left_object_id bigint NOT NULL,
  right_object_type varchar(50) NOT NULL,
  right_object_id bigint NOT NULL,
  edge_type varchar(100) NOT NULL,
  edge_category varchar(100) DEFAULT NULL,
  edge_description text,
  channel_id bigint DEFAULT NULL,
  channel_key varchar(64) DEFAULT NULL,
  domain_id bigint NOT NULL DEFAULT 1,
  weight_score int NOT NULL DEFAULT 0,
  sort_num int NOT NULL DEFAULT 0,
  actor_id bigint DEFAULT NULL,
  is_deleted tinyint NOT NULL DEFAULT 0,
  deleted_ymdhis bigint NOT NULL DEFAULT 0,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL DEFAULT 0,
  semantic_weight decimal(5,2) DEFAULT 0.00,
  relationship_type varchar(64) DEFAULT 'semantic',
  bidirectional tinyint NOT NULL DEFAULT 0,
  context_scope varchar(100) DEFAULT NULL,
  properties json DEFAULT NULL,
  -- FLARE protocol extensions (added 2026-02-27)
  flare_weight decimal(3,2) DEFAULT 0.5 COMMENT 'FLARE edge weight (0.5-1.0)',
  flare_reason varchar(255) DEFAULT NULL COMMENT 'Reason for edge existence',
  flare_db_source varchar(50) DEFAULT NULL COMMENT 'Database source table',
  flare_auto_generated tinyint DEFAULT 0 COMMENT 'Generated by automation',
  flare_verified tinyint DEFAULT 0 COMMENT 'Path verified to exist',
  flare_discovered_via varchar(50) DEFAULT NULL COMMENT 'Discovery method',
  PRIMARY KEY (edge_id)
);

-- Indexes for {{prefix}}edges
CREATE INDEX {{prefix}}edges_idx_left ON {{prefix}}edges (left_object_type, left_object_id);
CREATE INDEX {{prefix}}edges_idx_right ON {{prefix}}edges (right_object_type, right_object_id);
CREATE INDEX {{prefix}}edges_idx_edge_type ON {{prefix}}edges (edge_type);
CREATE INDEX {{prefix}}edges_idx_edge_category ON {{prefix}}edges (edge_category);
CREATE INDEX {{prefix}}edges_idx_actor ON {{prefix}}edges (actor_id);
CREATE INDEX {{prefix}}edges_idx_domain ON {{prefix}}edges (domain_id);
CREATE INDEX {{prefix}}edges_idx_is_deleted ON {{prefix}}edges (is_deleted);
CREATE INDEX {{prefix}}edges_idx_semantic_weight ON {{prefix}}edges (semantic_weight);
CREATE INDEX {{prefix}}edges_idx_relationship_type ON {{prefix}}edges (relationship_type);
CREATE INDEX {{prefix}}edges_idx_channel_semantic ON {{prefix}}edges (channel_id, relationship_type, semantic_weight);
CREATE INDEX {{prefix}}edges_idx_created ON {{prefix}}edges (created_ymdhis);
CREATE INDEX {{prefix}}edges_idx_updated ON {{prefix}}edges (updated_ymdhis);
-- FLARE protocol indexes (added 2026-02-27)
CREATE INDEX {{prefix}}edges_idx_flare_weight ON {{prefix}}edges (flare_weight, edge_type);
CREATE INDEX {{prefix}}edges_idx_flare_discovered ON {{prefix}}edges (flare_discovered_via, flare_auto_generated);
CREATE INDEX {{prefix}}edges_idx_flare_files ON {{prefix}}edges (left_object_type, left_object_id, edge_type, right_object_type, right_object_id);

-- Old actor events table removed in v4.0.86 - consolidated into {{prefix}}unified_log

CREATE TABLE {{prefix}}actor_handshakes (
  actor_handshake_id bigint NOT NULL,
  actor_id bigint NOT NULL,
  actor_type varchar(32) NOT NULL,
  `utc_timestamp` bigint NOT NULL,
  purpose varchar(500) DEFAULT NULL,
  constraints_json json DEFAULT NULL,
  forbidden_actions_json json DEFAULT NULL,
  context text,
  expires_utc bigint DEFAULT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  is_deleted tinyint NOT NULL DEFAULT '0',
  deleted_ymdhis bigint DEFAULT NULL,
  PRIMARY KEY (actor_handshake_id)
);

CREATE INDEX {{prefix}}actor_handshakes_idx_actor_id ON {{prefix}}actor_handshakes (actor_id);
CREATE INDEX {{prefix}}actor_handshakes_idx_is_deleted ON {{prefix}}actor_handshakes (is_deleted);
CREATE INDEX {{prefix}}actor_handshakes_idx_utc_timestamp ON {{prefix}}actor_handshakes (`utc_timestamp`);

-- ============================================================================
-- DEPRECATED 4.0.87: Bayesian Decision Tracking tables removed.
-- Decision history is now represented through channels, threads, and artifacts.
-- ROSE interprets decision context from conversation history.
-- See lupo-docs/doctrine/DECISION_MODEL.md for the canonical model.
-- BayesianDecisionService.php, decisions-api.php, and related tests removed.
-- Tables: {{prefix}}decisions, {{prefix}}decision_edges, {{prefix}}decision_evidence,
--         {{prefix}}decision_influences
-- Removed from install in 4.0.87. No runtime code references these tables.
-- ============================================================================

-- Consolidated metadata table replacing {{prefix}}actor_meta, {{prefix}}actor_properties, {{prefix}}agent_properties
-- 4.0.86: LUPOPEDIA HEADERS â€” added channel_id, parent_metadata_id, class_name for channel-scoped and hierarchical metadata
CREATE TABLE {{prefix}}metadata (
  metadata_id bigint NOT NULL,
  entity_type varchar(32) NOT NULL,
  entity_id bigint NOT NULL,
  domain_id bigint DEFAULT NULL,
  meta_type varchar(64) DEFAULT NULL,
  property_key varchar(255) NOT NULL,
  property_value text,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL,
  is_deleted tinyint NOT NULL DEFAULT '0',
  deleted_ymdhis bigint DEFAULT NULL,
  channel_id bigint DEFAULT NULL,
  parent_metadata_id bigint DEFAULT NULL,
  class_name varchar(128) DEFAULT NULL,
  schema_ref varchar(64) DEFAULT NULL,
  PRIMARY KEY (metadata_id)
);

CREATE UNIQUE INDEX {{prefix}}metadata_unique_entity_domain_property ON {{prefix}}metadata (entity_type, entity_id, domain_id, property_key);
CREATE INDEX {{prefix}}metadata_idx_entity ON {{prefix}}metadata (entity_type, entity_id);
CREATE INDEX {{prefix}}metadata_idx_domain ON {{prefix}}metadata (domain_id);
CREATE INDEX {{prefix}}metadata_idx_meta_type ON {{prefix}}metadata (meta_type);
CREATE INDEX {{prefix}}metadata_idx_property_key ON {{prefix}}metadata (property_key);
CREATE INDEX {{prefix}}metadata_idx_created_ymdhis ON {{prefix}}metadata (created_ymdhis);
CREATE INDEX {{prefix}}metadata_idx_updated_ymdhis ON {{prefix}}metadata (updated_ymdhis);
CREATE INDEX {{prefix}}metadata_idx_is_deleted ON {{prefix}}metadata (is_deleted);
CREATE INDEX {{prefix}}metadata_idx_channel_id ON {{prefix}}metadata (channel_id);
CREATE INDEX {{prefix}}metadata_idx_parent_metadata_id ON {{prefix}}metadata (parent_metadata_id);
CREATE INDEX {{prefix}}metadata_idx_class_name ON {{prefix}}metadata (class_name);
CREATE INDEX {{prefix}}metadata_idx_entity_deleted ON {{prefix}}metadata (entity_type, entity_id, is_deleted);
CREATE INDEX {{prefix}}metadata_idx_channel_deleted ON {{prefix}}metadata (channel_id, is_deleted);
CREATE INDEX {{prefix}}metadata_idx_parent_deleted ON {{prefix}}metadata (parent_metadata_id, is_deleted);
CREATE INDEX {{prefix}}metadata_idx_meta_type_deleted ON {{prefix}}metadata (meta_type, is_deleted);
CREATE INDEX {{prefix}}metadata_idx_class_deleted ON {{prefix}}metadata (class_name, is_deleted);

CREATE TABLE {{prefix}}actor_moods (
  actor_id bigint NOT NULL,
  mood_r tinyint NOT NULL,
  mood_g tinyint NOT NULL,
  mood_b tinyint NOT NULL,
  mood_framework varchar(32) NOT NULL DEFAULT 'western_analytical',
  timestamp_utc bigint NOT NULL
);

CREATE TABLE {{prefix}}actor_reply_templates (
  actor_reply_template_id bigint NOT NULL,
  actor_id bigint NOT NULL,
  template_key varchar(64) NOT NULL,
  template_text text NOT NULL,
  usage_context varchar(64) DEFAULT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL,
  is_deleted tinyint NOT NULL DEFAULT '0',
  deleted_ymdhis bigint DEFAULT NULL,
  PRIMARY KEY (actor_reply_template_id)
);

CREATE UNIQUE INDEX {{prefix}}actor_reply_templates_unq_actor_template_key ON {{prefix}}actor_reply_templates (actor_id, template_key);
CREATE INDEX {{prefix}}actor_reply_templates_idx_actor ON {{prefix}}actor_reply_templates (actor_id);
CREATE INDEX {{prefix}}actor_reply_templates_idx_key ON {{prefix}}actor_reply_templates (template_key);
CREATE INDEX {{prefix}}actor_reply_templates_idx_created ON {{prefix}}actor_reply_templates (created_ymdhis);
CREATE INDEX {{prefix}}actor_reply_templates_idx_updated ON {{prefix}}actor_reply_templates (updated_ymdhis);
CREATE INDEX {{prefix}}actor_reply_templates_idx_deleted ON {{prefix}}actor_reply_templates (is_deleted);
CREATE INDEX {{prefix}}actor_reply_templates_idx_usage_context ON {{prefix}}actor_reply_templates (usage_context);


CREATE TABLE {{prefix}}actor_availability_status (
  availability_id bigint NOT NULL,
  actor_id bigint NOT NULL,
  channel_id bigint NOT NULL,
  status varchar(32) NOT NULL DEFAULT 'offline',
  last_activity_ymdhis bigint NOT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL,
  is_deleted tinyint NOT NULL DEFAULT '0',
  deleted_ymdhis bigint DEFAULT NULL,
  PRIMARY KEY (availability_id)
);

CREATE UNIQUE INDEX {{prefix}}actor_availability_status_unique_actor_channel ON {{prefix}}actor_availability_status (actor_id, channel_id);
CREATE INDEX {{prefix}}actor_availability_status_idx_status ON {{prefix}}actor_availability_status (status);
CREATE INDEX {{prefix}}actor_availability_status_idx_channel ON {{prefix}}actor_availability_status (channel_id);
CREATE INDEX {{prefix}}actor_availability_status_idx_last_activity ON {{prefix}}actor_availability_status (last_activity_ymdhis);
CREATE INDEX {{prefix}}actor_availability_status_idx_is_deleted ON {{prefix}}actor_availability_status (is_deleted);
-- Live Help Operator Availability: Tracks real-time status of operators by channel (online, busy, away, offline)

CREATE TABLE {{prefix}}agents (
  agent_id bigint NOT NULL,
  agent_key varchar(100) NOT NULL,
  agent_name varchar(150) NOT NULL,
  archetype varchar(150) DEFAULT NULL,
  description text,
  version varchar(50) DEFAULT '1.0',
  model_name varchar(100) DEFAULT NULL,
  is_global_authority tinyint NOT NULL DEFAULT '0',
  is_internal_only tinyint NOT NULL DEFAULT '0',
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint DEFAULT NULL,
  is_deleted tinyint NOT NULL DEFAULT '0',
  deleted_ymdhis bigint DEFAULT NULL,
  avg_response_time_ms int DEFAULT '0',
  total_tokens_processed bigint DEFAULT '0',
  success_rate float DEFAULT '1',
  cost_per_1k_tokens decimal(10,4) DEFAULT '0.0000',
  temperature float DEFAULT '0.7',
  top_p float DEFAULT '1',
  max_tokens int DEFAULT '2048',
  presence_penalty float DEFAULT '0',
  frequency_penalty float DEFAULT '0',
  system_prompt text,
  provider varchar(50) DEFAULT 'openai',
  api_key_id bigint DEFAULT NULL,
  timeout_ms int DEFAULT '20000',
  safety_json json DEFAULT NULL,
  response_format varchar(50) DEFAULT NULL,
  metadata_json json DEFAULT NULL,
  PRIMARY KEY (agent_id)
);

CREATE UNIQUE INDEX {{prefix}}agents_unique_agent_key ON {{prefix}}agents (agent_key);
CREATE INDEX {{prefix}}agents_idx_api_key_id ON {{prefix}}agents (api_key_id);
CREATE INDEX {{prefix}}agents_idx_is_global_authority ON {{prefix}}agents (is_global_authority);
CREATE INDEX {{prefix}}agents_idx_created_ymdhis ON {{prefix}}agents (created_ymdhis);
CREATE INDEX {{prefix}}agents_idx_updated_ymdhis ON {{prefix}}agents (updated_ymdhis);
CREATE INDEX {{prefix}}agents_idx_is_deleted ON {{prefix}}agents (is_deleted);

CREATE TABLE {{prefix}}agent_context_snapshots (
  agent_context_snapshot_id bigint NOT NULL,
  session_id varchar(100) NOT NULL,
  actor_id bigint NOT NULL,
  parent_snapshot_id bigint DEFAULT NULL,
  snapshot_type varchar(64) NOT NULL DEFAULT 'full',
  snapshot_purpose varchar(50) DEFAULT NULL,
  context_data text NOT NULL,
  context_summary text,
  context_metadata json DEFAULT NULL,
  token_count int DEFAULT NULL,
  character_count int DEFAULT NULL,
  compressed_size int DEFAULT NULL,
  compression_ratio float DEFAULT NULL,
  compression_method varchar(64) DEFAULT 'gzip',
  serialization_time_ms int DEFAULT NULL,
  compression_time_ms int DEFAULT NULL,
  related_tool_call_id bigint DEFAULT NULL,
  conversation_turn int DEFAULT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  expires_ymdhis bigint DEFAULT NULL,
  is_corrupt tinyint DEFAULT '0',
  retention_policy varchar(64) DEFAULT 'temporary',
  PRIMARY KEY (agent_context_snapshot_id)
);

CREATE INDEX {{prefix}}agent_context_snapshots_idx_session_agent ON {{prefix}}agent_context_snapshots (session_id, actor_id);
CREATE INDEX {{prefix}}agent_context_snapshots_idx_created ON {{prefix}}agent_context_snapshots (created_ymdhis);
CREATE INDEX {{prefix}}agent_context_snapshots_idx_type_purpose ON {{prefix}}agent_context_snapshots (snapshot_type, snapshot_purpose);
CREATE INDEX {{prefix}}agent_context_snapshots_idx_retention ON {{prefix}}agent_context_snapshots (retention_policy, expires_ymdhis);
CREATE INDEX {{prefix}}agent_context_snapshots_idx_turn ON {{prefix}}agent_context_snapshots (session_id, conversation_turn);
CREATE INDEX {{prefix}}agent_context_snapshots_idx_related_tool ON {{prefix}}agent_context_snapshots (related_tool_call_id);
CREATE INDEX {{prefix}}agent_context_snapshots_idx_parent ON {{prefix}}agent_context_snapshots (parent_snapshot_id);

CREATE TABLE {{prefix}}agent_dependencies (
  agent_dependency_id bigint NOT NULL,
  agent_id bigint NOT NULL,
  depends_on_agent_id bigint NOT NULL,
  depends_on_agent_code varchar(50) NOT NULL,
  is_required tinyint NOT NULL DEFAULT '1',
  notes text,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint DEFAULT NULL,
  PRIMARY KEY (agent_dependency_id)
);

CREATE INDEX {{prefix}}agent_dependencies_idx_agent_id ON {{prefix}}agent_dependencies (agent_id);
CREATE INDEX {{prefix}}agent_dependencies_idx_depends_on ON {{prefix}}agent_dependencies (depends_on_agent_id);

CREATE TABLE {{prefix}}agent_experiences (
  link_id char(26) NOT NULL,
  agent_id bigint NOT NULL,
  star_id char(26) NOT NULL,
  intensity decimal(3,2) DEFAULT NULL,
  context_id bigint DEFAULT NULL,
  observed_ymdhis bigint DEFAULT NULL,
  expressed_as_rgb char(6) DEFAULT NULL,
  PRIMARY KEY (link_id)
);

CREATE INDEX {{prefix}}agent_experiences_idx_agent ON {{prefix}}agent_experiences (agent_id);
CREATE INDEX {{prefix}}agent_experiences_idx_star ON {{prefix}}agent_experiences (star_id);
CREATE INDEX {{prefix}}agent_experiences_idx_context ON {{prefix}}agent_experiences (context_id);

CREATE TABLE {{prefix}}agent_external_events (
  external_event_id bigint NOT NULL,
  agent_name varchar(255) NOT NULL,
  source_system varchar(255) NOT NULL,
  event_type varchar(50) NOT NULL,
  event_payload_json json DEFAULT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  PRIMARY KEY (external_event_id)
);


CREATE TABLE {{prefix}}agent_faucets (
  agent_faucet_id bigint NOT NULL,
  actor_id bigint NOT NULL,
  name varchar(100) NOT NULL,
  alias_name varchar(100) DEFAULT NULL,
  slug varchar(100) NOT NULL,
  faucet_class varchar(32) DEFAULT NULL,
  description text,
  style_preset varchar(100) DEFAULT NULL,
  model_name varchar(100) DEFAULT NULL,
  provider varchar(50) DEFAULT NULL,
  temperature float DEFAULT NULL,
  top_p float DEFAULT NULL,
  max_tokens int DEFAULT NULL,
  presence_penalty float DEFAULT NULL,
  frequency_penalty float DEFAULT NULL,
  system_prompt text,
  safety_json json DEFAULT NULL,
  response_format varchar(50) DEFAULT NULL,
  capabilities_json text,
  is_default tinyint NOT NULL DEFAULT '0',
  domain_id bigint NOT NULL DEFAULT '1',
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL,
  deleted_ymdhis bigint DEFAULT NULL,
  PRIMARY KEY (agent_faucet_id)
);

CREATE INDEX {{prefix}}agent_faucets_idx_agent ON {{prefix}}agent_faucets (actor_id);
CREATE INDEX {{prefix}}agent_faucets_idx_slug ON {{prefix}}agent_faucets (slug);
CREATE INDEX {{prefix}}agent_faucets_idx_faucet_class ON {{prefix}}agent_faucets (faucet_class);
CREATE INDEX {{prefix}}agent_faucets_idx_domain ON {{prefix}}agent_faucets (domain_id);
CREATE INDEX {{prefix}}agent_faucets_idx_default ON {{prefix}}agent_faucets (is_default);

CREATE TABLE {{prefix}}agent_faucet_credentials (
  agent_faucet_credential_id int NOT NULL,
  faucet_id bigint NOT NULL,
  provider varchar(64) NOT NULL,
  api_key varbinary(512) NOT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL,
  PRIMARY KEY (agent_faucet_credential_id)
);

CREATE INDEX {{prefix}}agent_faucet_credentials_idx_faucet ON {{prefix}}agent_faucet_credentials (faucet_id);

CREATE TABLE {{prefix}}agent_files (
  file_id bigint NOT NULL,
  agent_id bigint NOT NULL,
  file_type varchar(50) NOT NULL,
  file_name varchar(255) NOT NULL,
  file_path varchar(500) NOT NULL,
  file_hash varchar(64) NOT NULL,
  file_size bigint NOT NULL,
  mime_type varchar(100) DEFAULT NULL,
  upload_ymdhis bigint NOT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL,
  is_deleted tinyint NOT NULL DEFAULT '0',
  deleted_ymdhis bigint DEFAULT NULL,
  migrated_from_directory varchar(255) DEFAULT NULL,
  PRIMARY KEY (file_id)
);

CREATE INDEX {{prefix}}agent_files_idx_agent_id ON {{prefix}}agent_files (agent_id);
CREATE INDEX {{prefix}}agent_files_idx_file_type ON {{prefix}}agent_files (file_type);
CREATE INDEX {{prefix}}agent_files_idx_file_hash ON {{prefix}}agent_files (file_hash);
CREATE INDEX {{prefix}}agent_files_idx_is_deleted ON {{prefix}}agent_files (is_deleted);
CREATE INDEX {{prefix}}agent_files_idx_upload_ymdhis ON {{prefix}}agent_files (upload_ymdhis);

CREATE TABLE {{prefix}}agent_heartbeats (
  heartbeat_id bigint NOT NULL,
  agent_slug varchar(64) NOT NULL,
  status varchar(32) NOT NULL DEFAULT 'unknown',
  last_heartbeat_ymdhis bigint NOT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL DEFAULT 0,
  is_deleted tinyint NOT NULL DEFAULT '0',
  deleted_ymdhis bigint DEFAULT NULL,
  PRIMARY KEY (heartbeat_id)
);

CREATE INDEX {{prefix}}agent_heartbeats_idx_agent_slug ON {{prefix}}agent_heartbeats (agent_slug);
CREATE INDEX {{prefix}}agent_heartbeats_idx_last_heartbeat_ymdhis ON {{prefix}}agent_heartbeats (last_heartbeat_ymdhis);
CREATE INDEX {{prefix}}agent_heartbeats_idx_created_ymdhis ON {{prefix}}agent_heartbeats (created_ymdhis);
CREATE INDEX {{prefix}}agent_heartbeats_idx_is_deleted ON {{prefix}}agent_heartbeats (is_deleted);


CREATE TABLE {{prefix}}agent_tool_calls (
  agent_tool_call_id bigint NOT NULL,
  agent_id bigint NOT NULL,
  faucet_id bigint DEFAULT NULL,
  domain_id bigint NOT NULL,
  tool_name varchar(150) NOT NULL,
  action_type varchar(100) DEFAULT NULL,
  input_json text,
  output_json text,
  provider varchar(50) DEFAULT NULL,
  model_name varchar(150) DEFAULT NULL,
  tokens_prompt int DEFAULT '0',
  tokens_completion int DEFAULT '0',
  tokens_total int DEFAULT '0',
  cost_usd decimal(10,6) DEFAULT '0.000000',
  latency_ms int DEFAULT '0',
  status varchar(50) DEFAULT 'success',
  error_message text,
  parent_call_id bigint DEFAULT NULL,
  thread_id bigint DEFAULT NULL,
  message_id bigint DEFAULT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL DEFAULT 0,
  is_deleted tinyint NOT NULL DEFAULT '0',
  deleted_ymdhis bigint DEFAULT NULL,
  archived_ymdhis bigint DEFAULT 0,
  completed_ymdhis bigint DEFAULT NULL,
  PRIMARY KEY (agent_tool_call_id)
);

CREATE INDEX {{prefix}}agent_tool_calls_idx_agent_created ON {{prefix}}agent_tool_calls (agent_id, created_ymdhis);
CREATE INDEX {{prefix}}agent_tool_calls_idx_agent ON {{prefix}}agent_tool_calls (agent_id);
CREATE INDEX {{prefix}}agent_tool_calls_idx_faucet ON {{prefix}}agent_tool_calls (faucet_id);
CREATE INDEX {{prefix}}agent_tool_calls_idx_domain ON {{prefix}}agent_tool_calls (domain_id);
CREATE INDEX {{prefix}}agent_tool_calls_idx_model ON {{prefix}}agent_tool_calls (model_name);
CREATE INDEX {{prefix}}agent_tool_calls_idx_provider ON {{prefix}}agent_tool_calls (provider);
CREATE INDEX {{prefix}}agent_tool_calls_idx_parent ON {{prefix}}agent_tool_calls (parent_call_id);
CREATE INDEX {{prefix}}agent_tool_calls_idx_thread ON {{prefix}}agent_tool_calls (thread_id);
CREATE INDEX {{prefix}}agent_tool_calls_idx_message ON {{prefix}}agent_tool_calls (message_id);

CREATE TABLE {{prefix}}agent_versions (
  agent_version_id bigint NOT NULL,
  agent_id bigint NOT NULL,
  version_label varchar(64) NOT NULL,
  semver_major int DEFAULT '0',
  semver_minor int DEFAULT '0',
  semver_patch int DEFAULT '0',
  version_notes text,
  version_hash varchar(128) DEFAULT NULL,
  previous_version_id bigint DEFAULT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL,
  is_deleted smallint NOT NULL DEFAULT '0',
  deleted_ymdhis bigint DEFAULT NULL,
  PRIMARY KEY (agent_version_id)
);

CREATE INDEX {{prefix}}agent_versions_agent_id ON {{prefix}}agent_versions (agent_id);
CREATE INDEX {{prefix}}agent_versions_version_label ON {{prefix}}agent_versions (version_label);
CREATE INDEX {{prefix}}agent_versions_semver_major ON {{prefix}}agent_versions (semver_major, semver_minor, semver_patch);

-- {{prefix}}aliases moved to future_features_lupopedia.sql (v4.0.86)

CREATE TABLE {{prefix}}analytics_campaign_vars (
  campaign_var_id bigint NOT NULL,
  period varchar(64) NOT NULL,
  date_ymd bigint DEFAULT NULL,
  yearmonth int DEFAULT NULL,
  year int DEFAULT NULL,
  campaign_key varchar(255) NOT NULL,
  campaign_value varchar(500) DEFAULT NULL,
  metadata_json json DEFAULT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  PRIMARY KEY (campaign_var_id)
);


-- PATHS/VISITS doctrine (4.0.86): visits = raw events; paths = aggregated flows. gc.php aggregates visits -> paths.
-- {{prefix}}visits: raw per-event navigation logs (high-volume, append-only). is_processed set by gc.php when aggregated.
CREATE TABLE {{prefix}}visits (
  visit_id bigint NOT NULL ,
  session_id bigint DEFAULT NULL,
  actor_id bigint DEFAULT NULL,
  instance_id bigint DEFAULT NULL,
  path_url text,
  entercontentid bigint DEFAULT NULL,
  exitcontentid bigint DEFAULT NULL,
  enter_table varchar(255) DEFAULT NULL,
  exit_table varchar(255) DEFAULT NULL,
  transition_type varchar(64) DEFAULT NULL,
  transition_metadata text,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  is_processed tinyint NOT NULL DEFAULT 0,
  is_deleted tinyint NOT NULL DEFAULT 0,
  deleted_ymdhis bigint DEFAULT NULL,
    -- Geolocation columns
    country_code char(2) DEFAULT NULL,
    city varchar(100) DEFAULT NULL,
    latitude decimal(10,8) DEFAULT NULL,
    longitude decimal(11,8) DEFAULT NULL,
    geolocation_source varchar(50) DEFAULT NULL,
    INDEX idx_country (country_code),
  PRIMARY KEY (visit_id)
);
CREATE INDEX {{prefix}}visits_idx_session ON {{prefix}}visits (session_id);
CREATE INDEX {{prefix}}visits_idx_actor ON {{prefix}}visits (actor_id);
CREATE INDEX {{prefix}}visits_idx_created ON {{prefix}}visits (created_ymdhis);
CREATE INDEX {{prefix}}visits_idx_is_processed ON {{prefix}}visits (is_processed);
CREATE INDEX {{prefix}}visits_idx_is_deleted ON {{prefix}}visits (is_deleted);
CREATE INDEX {{prefix}}visits_idx_enter_exit ON {{prefix}}visits (entercontentid, exitcontentid);

-- Daily visit aggregation
CREATE TABLE {{prefix}}visits_daily (
  visits_daily_id bigint NOT NULL,
  actor_id bigint DEFAULT NULL,
  visit_ymd bigint NOT NULL,
  total_visits int DEFAULT 0,
  unique_sessions int DEFAULT 0,
  avg_duration_seconds int DEFAULT 0,
  bounce_count int DEFAULT 0,
  created_ymdhis bigint NOT NULL,
  updated_ymdhis bigint NOT NULL,
  is_deleted tinyint NOT NULL DEFAULT 0,
  deleted_ymdhis bigint DEFAULT NULL,
  UNIQUE KEY unique_daily_actor (actor_id, visit_ymd),
  INDEX idx_actor_date (actor_id, visit_ymd),
  PRIMARY KEY (visits_daily_id)
);

-- Daily referer tracking
CREATE TABLE {{prefix}}referers_daily (
  referers_daily_id bigint NOT NULL,
  actor_id bigint DEFAULT NULL,
  referer_domain varchar(255),
  visit_ymd bigint NOT NULL,
  visit_count int DEFAULT 0,
  created_ymdhis bigint NOT NULL,
  updated_ymdhis bigint NOT NULL,
  is_deleted tinyint NOT NULL DEFAULT 0,
  deleted_ymdhis bigint DEFAULT NULL,
  UNIQUE KEY unique_daily_referer (actor_id, referer_domain, visit_ymd),
  INDEX idx_referer_date (referer_domain, visit_ymd),
  INDEX idx_actor_date (actor_id, visit_ymd),
  PRIMARY KEY (referers_daily_id)
);





CREATE TABLE {{prefix}}anubis_log (
  anubis_log_id bigint NOT NULL,
  event_type varchar(64) NOT NULL,
  severity varchar(20) NOT NULL DEFAULT 'normal',
  source_table varchar(64) DEFAULT NULL,
  source_id bigint DEFAULT NULL,
  file_path_from_root varchar(255) DEFAULT NULL,
  context_json json DEFAULT NULL,
  status varchar(64) NOT NULL DEFAULT 'Pending',
  assigned_to_actor_id bigint NOT NULL DEFAULT 19,
  resolution_ymdhis bigint DEFAULT NULL,
  resolution_summary text,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL DEFAULT 0,
  is_deleted tinyint NOT NULL DEFAULT 0,
  deleted_ymdhis bigint DEFAULT NULL,
  PRIMARY KEY (anubis_log_id)
);

CREATE INDEX {{prefix}}anubis_log_idx_event_type ON {{prefix}}anubis_log (event_type);
CREATE INDEX {{prefix}}anubis_log_idx_source_id ON {{prefix}}anubis_log (source_id);
CREATE INDEX {{prefix}}anubis_log_idx_source_table ON {{prefix}}anubis_log (source_table);
CREATE INDEX {{prefix}}anubis_log_idx_file_path ON {{prefix}}anubis_log (file_path_from_root);
CREATE INDEX {{prefix}}anubis_log_idx_assigned_actor ON {{prefix}}anubis_log (assigned_to_actor_id);
CREATE INDEX {{prefix}}anubis_log_idx_status ON {{prefix}}anubis_log (status);
CREATE INDEX {{prefix}}anubis_log_idx_created ON {{prefix}}anubis_log (created_ymdhis);

CREATE TABLE {{prefix}}anubis_events (
  anubis_event_id bigint NOT NULL,
  event_type varchar(64) NOT NULL,
  table_name varchar(255) NOT NULL,
  old_id bigint NOT NULL,
  new_id bigint NOT NULL,
  created_ymdhis bigint NOT NULL,
  operator_actor_id bigint NOT NULL,
  details_json text NOT NULL,
  PRIMARY KEY (anubis_event_id)
);


-- {{prefix}}anubis_orphaned moved to future_features_lupopedia.sql (v4.0.86)

CREATE TABLE {{prefix}}anubis_redirects (
  anubis_redirect_id bigint NOT NULL,
  table_name varchar(255) NOT NULL,
  old_id bigint NOT NULL,
  new_id bigint NOT NULL,
  created_ymdhis bigint NOT NULL,
  updated_ymdhis bigint NOT NULL,
  agent varchar(255) NOT NULL,
  PRIMARY KEY (anubis_redirect_id)
);

-- ANUBIS queue tables (required by install Activations Block for actor_id 19; previously in migrations/anubis_queue_tables_4.0.86.sql).
CREATE TABLE {{prefix}}anubis_queue (
  queue_id bigint NOT NULL ,
  file_path varchar(512) NOT NULL,
  file_hash varchar(64) DEFAULT NULL,
  file_content longtext,
  detected_utc bigint NOT NULL,
  priority tinyint DEFAULT 5,
  status varchar(32) DEFAULT 'pending',
  detection_method varchar(64) DEFAULT NULL,
  header_snapshot text,
  error_message text,
  attempts tinyint DEFAULT 0,
  last_attempt_utc bigint DEFAULT NULL,
  assigned_to_actor_id bigint DEFAULT NULL,
  filesystem_copy_exists tinyint DEFAULT 1,
  filesystem_backup_path varchar(512) DEFAULT NULL,
  created_utc bigint NOT NULL,
  updated_utc bigint NOT NULL,
  is_deleted tinyint DEFAULT 0,
  PRIMARY KEY (queue_id)
);
CREATE INDEX {{prefix}}anubis_queue_idx_detected ON {{prefix}}anubis_queue (detected_utc);
CREATE INDEX {{prefix}}anubis_queue_idx_file_path ON {{prefix}}anubis_queue (file_path);
CREATE INDEX {{prefix}}anubis_queue_idx_status_priority ON {{prefix}}anubis_queue (status, priority);
CREATE UNIQUE INDEX {{prefix}}anubis_queue_uniq_file_hash ON {{prefix}}anubis_queue (file_hash);

CREATE TABLE {{prefix}}anubis_processing_log (
  log_id bigint NOT NULL ,
  queue_id bigint NOT NULL,
  file_path varchar(512) NOT NULL,
  action varchar(64) NOT NULL,
  details text,
  actor_id bigint DEFAULT NULL,
  created_utc bigint NOT NULL,
  PRIMARY KEY (log_id)
);
CREATE INDEX {{prefix}}anubis_processing_log_idx_created ON {{prefix}}anubis_processing_log (created_utc);
CREATE INDEX {{prefix}}anubis_processing_log_idx_queue ON {{prefix}}anubis_processing_log (queue_id);

CREATE TABLE {{prefix}}anubis_recovery_attempts (
  attempt_id bigint NOT NULL ,
  queue_id bigint NOT NULL,
  attempt_number tinyint NOT NULL,
  attempt_utc bigint NOT NULL,
  strategy varchar(64) DEFAULT NULL,
  success tinyint DEFAULT 0,
  generated_header text,
  error_details text,
  recovered_file_path varchar(512) DEFAULT NULL,
  PRIMARY KEY (attempt_id)
);
CREATE INDEX {{prefix}}anubis_recovery_attempts_idx_queue_attempt ON {{prefix}}anubis_recovery_attempts (queue_id, attempt_number);

CREATE TABLE {{prefix}}anubis_quarantine (
  quarantine_id bigint NOT NULL ,
  queue_id bigint NOT NULL,
  file_path varchar(512) NOT NULL,
  file_hash varchar(64) DEFAULT NULL,
  file_content longtext,
  quarantine_path varchar(512) NOT NULL,
  reason varchar(255) NOT NULL,
  quarantined_utc bigint NOT NULL,
  expires_utc bigint DEFAULT NULL,
  reviewed_by_actor_id bigint DEFAULT NULL,
  reviewed_utc bigint DEFAULT NULL,
  resolution varchar(64) DEFAULT NULL,
  is_deleted tinyint DEFAULT 0,
  PRIMARY KEY (quarantine_id)
);
CREATE INDEX {{prefix}}anubis_quarantine_idx_expires ON {{prefix}}anubis_quarantine (expires_utc);
CREATE INDEX {{prefix}}anubis_quarantine_idx_queue ON {{prefix}}anubis_quarantine (queue_id);

-- 12-table install expansion v4.0.86: unified ANUBIS operations audit
CREATE TABLE {{prefix}}anubis_operations (
  operation_id bigint NOT NULL ,
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
CREATE INDEX {{prefix}}anubis_operations_idx_target ON {{prefix}}anubis_operations (target_type, target_id);
CREATE INDEX {{prefix}}anubis_operations_idx_type ON {{prefix}}anubis_operations (operation_type);
CREATE INDEX {{prefix}}anubis_operations_idx_created ON {{prefix}}anubis_operations (created_ymdhis);

CREATE TABLE {{prefix}}api_clients (
  api_client_id bigint NOT NULL,
  actor_id bigint NOT NULL DEFAULT '0',
  client_key varchar(255) NOT NULL,
  client_secret varchar(255) NOT NULL,
  client_name varchar(150) NOT NULL,
  client_description text,
  scopes text,
  is_active tinyint NOT NULL DEFAULT '1',
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL,
  expires_ymdhis bigint DEFAULT NULL,
  PRIMARY KEY (api_client_id)
);

CREATE UNIQUE INDEX {{prefix}}api_clients_uq_client_key ON {{prefix}}api_clients (client_key);
CREATE INDEX {{prefix}}api_clients_idx_actor ON {{prefix}}api_clients (actor_id);
CREATE INDEX {{prefix}}api_clients_idx_active ON {{prefix}}api_clients (is_active);
CREATE INDEX {{prefix}}api_clients_idx_expires ON {{prefix}}api_clients (expires_ymdhis);

CREATE TABLE {{prefix}}api_rate_limits (
  api_rate_limit_id bigint NOT NULL,
  domain_id bigint NOT NULL DEFAULT '1',
  api_token_id bigint NOT NULL DEFAULT '0',
  actor_id bigint NOT NULL DEFAULT '0',
  ip_address varchar(45) DEFAULT NULL,
  endpoint varchar(255) DEFAULT NULL,
  window_ymdhis bigint NOT NULL,
  request_count int NOT NULL DEFAULT '0',
  limit_value int NOT NULL DEFAULT '0',
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL,
  PRIMARY KEY (api_rate_limit_id)
);

CREATE INDEX {{prefix}}api_rate_limits_idx_token_window ON {{prefix}}api_rate_limits (api_token_id, window_ymdhis);
CREATE INDEX {{prefix}}api_rate_limits_idx_actor_window ON {{prefix}}api_rate_limits (actor_id, window_ymdhis);
CREATE INDEX {{prefix}}api_rate_limits_idx_ip_window ON {{prefix}}api_rate_limits (ip_address, window_ymdhis);
CREATE INDEX {{prefix}}api_rate_limits_idx_domain_window ON {{prefix}}api_rate_limits (domain_id, window_ymdhis);
CREATE INDEX {{prefix}}api_rate_limits_idx_endpoint ON {{prefix}}api_rate_limits (endpoint);

CREATE TABLE {{prefix}}api_tokens (
  api_token_id bigint NOT NULL,
  domain_id bigint NOT NULL DEFAULT '1',
  actor_id bigint NOT NULL DEFAULT '0',
  token_key varchar(255) NOT NULL,
  token_label varchar(150) DEFAULT NULL,
  scopes text,
  is_active tinyint NOT NULL DEFAULT '1',
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL DEFAULT 0,
  expires_ymdhis bigint DEFAULT NULL,
  last_used_ymdhis bigint DEFAULT NULL,
  created_ip varchar(45) DEFAULT NULL,
  last_used_ip varchar(45) DEFAULT NULL,
  is_deleted tinyint NOT NULL DEFAULT '0',
  deleted_ymdhis bigint DEFAULT NULL,
  notes text,
  PRIMARY KEY (api_token_id)
);

CREATE UNIQUE INDEX {{prefix}}api_tokens_uq_token_key ON {{prefix}}api_tokens (token_key);
CREATE INDEX {{prefix}}api_tokens_idx_actor_active ON {{prefix}}api_tokens (actor_id, is_active);
CREATE INDEX {{prefix}}api_tokens_idx_domain ON {{prefix}}api_tokens (domain_id);
CREATE INDEX {{prefix}}api_tokens_idx_actor ON {{prefix}}api_tokens (actor_id);
CREATE INDEX {{prefix}}api_tokens_idx_active ON {{prefix}}api_tokens (is_active);
CREATE INDEX {{prefix}}api_tokens_idx_expires ON {{prefix}}api_tokens (expires_ymdhis);
CREATE INDEX {{prefix}}api_tokens_idx_last_used ON {{prefix}}api_tokens (last_used_ymdhis);

CREATE TABLE {{prefix}}api_token_logs (
  api_token_log_id bigint NOT NULL,
  domain_id bigint NOT NULL DEFAULT '1',
  api_token_id bigint NOT NULL,
  actor_id bigint NOT NULL DEFAULT '0',
  endpoint varchar(255) NOT NULL,
  http_method varchar(10) NOT NULL,
  ip_address varchar(45) DEFAULT NULL,
  user_agent varchar(255) DEFAULT NULL,
  status_code int NOT NULL,
  request_ymdhis bigint NOT NULL,
  duration_ms int DEFAULT NULL,
  PRIMARY KEY (api_token_log_id)
);

CREATE INDEX {{prefix}}api_token_logs_idx_token ON {{prefix}}api_token_logs (api_token_id);
CREATE INDEX {{prefix}}api_token_logs_idx_actor ON {{prefix}}api_token_logs (actor_id);
CREATE INDEX {{prefix}}api_token_logs_idx_domain_time ON {{prefix}}api_token_logs (domain_id, request_ymdhis);
CREATE INDEX {{prefix}}api_token_logs_idx_endpoint ON {{prefix}}api_token_logs (endpoint);
CREATE INDEX {{prefix}}api_token_logs_idx_status ON {{prefix}}api_token_logs (status_code);

CREATE TABLE {{prefix}}api_webhooks (
  api_webhook_id bigint NOT NULL,
  domain_id bigint NOT NULL DEFAULT '1',
  actor_id bigint NOT NULL DEFAULT '0',
  module_id bigint NOT NULL DEFAULT '0',
  endpoint_url varchar(500) NOT NULL,
  secret_key varchar(255) NOT NULL,
  event_types text NOT NULL,
  is_active tinyint NOT NULL DEFAULT '1',
  max_retries int NOT NULL DEFAULT '5',
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL,
  expires_ymdhis bigint DEFAULT NULL,
  notes text,
  PRIMARY KEY (api_webhook_id)
);

CREATE INDEX {{prefix}}api_webhooks_idx_domain ON {{prefix}}api_webhooks (domain_id);
CREATE INDEX {{prefix}}api_webhooks_idx_actor ON {{prefix}}api_webhooks (actor_id);
CREATE INDEX {{prefix}}api_webhooks_idx_module ON {{prefix}}api_webhooks (module_id);
CREATE INDEX {{prefix}}api_webhooks_idx_active ON {{prefix}}api_webhooks (is_active);
CREATE INDEX {{prefix}}api_webhooks_idx_expires ON {{prefix}}api_webhooks (expires_ymdhis);
 

CREATE TABLE {{prefix}}atoms (
  atom_id bigint NOT NULL,
  atom_name varchar(255) NOT NULL,
  context_id bigint NOT NULL,
  is_authoritative tinyint NOT NULL DEFAULT '0',
  value_json json DEFAULT NULL,
  summary text,
  tags varchar(255) DEFAULT NULL,
  created_ymd bigint NOT NULL DEFAULT '0',
  updated_ymd bigint NOT NULL DEFAULT '0',
  PRIMARY KEY (atom_id)
);

CREATE INDEX {{prefix}}atoms_idx_atom_name ON {{prefix}}atoms (atom_name);
CREATE INDEX {{prefix}}atoms_idx_context_id ON {{prefix}}atoms (context_id);
CREATE INDEX {{prefix}}atoms_idx_authoritative ON {{prefix}}atoms (is_authoritative);
CREATE INDEX {{prefix}}atoms_idx_atom_context ON {{prefix}}atoms (atom_name, context_id);

-- 12-table install expansion v4.0.86 (directive 20260314): aliases for routing/redirects
CREATE TABLE {{prefix}}aliases (
  alias_id bigint NOT NULL,
  slug varchar(255) NOT NULL,
  alias varchar(255) NOT NULL,
  alias_type varchar(50) DEFAULT 'semantic',
  created_ymdhis bigint NOT NULL DEFAULT 0,
  PRIMARY KEY (alias_id)
);
CREATE UNIQUE INDEX {{prefix}}aliases_uniq_alias ON {{prefix}}aliases (alias);
CREATE INDEX {{prefix}}aliases_idx_slug ON {{prefix}}aliases (slug);

CREATE TABLE {{prefix}}audit_log (
  audit_log_id bigint NOT NULL,
  channel_id bigint NOT NULL,
  entity_type varchar(32) NOT NULL,
  entity_id bigint NOT NULL,
  event_type varchar(100) NOT NULL,
  table_name varchar(100) DEFAULT NULL,
  table_id bigint DEFAULT NULL,
  payload_json text,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL,
  is_deleted tinyint NOT NULL DEFAULT '0',
  deleted_ymdhis bigint DEFAULT NULL,
  PRIMARY KEY (audit_log_id)
);

CREATE INDEX {{prefix}}audit_log_idx_entity ON {{prefix}}audit_log (entity_type, entity_id);
CREATE INDEX {{prefix}}audit_log_idx_event ON {{prefix}}audit_log (event_type);
CREATE INDEX {{prefix}}audit_log_idx_table ON {{prefix}}audit_log (table_name, table_id);

-- 12-table install expansion v4.0.86: unified log (consolidated log types)
CREATE TABLE {{prefix}}unified_log (
  log_id bigint NOT NULL ,
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
CREATE INDEX {{prefix}}unified_log_idx_actor_id ON {{prefix}}unified_log (actor_id);
CREATE INDEX {{prefix}}unified_log_idx_channel_id ON {{prefix}}unified_log (channel_id);
CREATE INDEX {{prefix}}unified_log_idx_created_ymdhis ON {{prefix}}unified_log (created_ymdhis);
CREATE INDEX {{prefix}}unified_log_idx_log_level ON {{prefix}}unified_log (log_level);
CREATE INDEX {{prefix}}unified_log_idx_log_type ON {{prefix}}unified_log (log_type);
CREATE INDEX {{prefix}}unified_log_idx_session_id ON {{prefix}}unified_log (session_id);
CREATE INDEX {{prefix}}unified_log_idx_actor_log ON {{prefix}}unified_log (actor_id, log_type);
CREATE INDEX {{prefix}}unified_log_idx_channel_log ON {{prefix}}unified_log (channel_id, log_type);
CREATE INDEX {{prefix}}unified_log_idx_log_type_created ON {{prefix}}unified_log (log_type, created_ymdhis);

-- Tracks applied one-time migrations (version, name, applied_ymdhis)
CREATE TABLE {{prefix}}schema_migrations (
  schema_migration_id bigint NOT NULL,
  version varchar(64) NOT NULL,
  name varchar(255) NOT NULL,
  applied_ymdhis bigint NOT NULL DEFAULT 0,
  PRIMARY KEY (schema_migration_id)
);

CREATE UNIQUE INDEX {{prefix}}schema_migrations_unq_version ON {{prefix}}schema_migrations (version);
CREATE INDEX {{prefix}}schema_migrations_idx_applied ON {{prefix}}schema_migrations (applied_ymdhis);

CREATE TABLE {{prefix}}auth_audit_log (
  auth_audit_log_id bigint NOT NULL,
  user_id bigint DEFAULT NULL,
  crafty_operator_id int DEFAULT NULL,
  event_type varchar(50) NOT NULL,
  system_context varchar(50) NOT NULL,
  ip_address varchar(45) DEFAULT NULL,
  user_agent text,
  event_data json DEFAULT NULL,
  success tinyint NOT NULL DEFAULT '1',
  error_message text,
  created_at bigint,
  updated_at bigint,
  PRIMARY KEY (auth_audit_log_id)
);

CREATE INDEX {{prefix}}auth_audit_log_idx_user_id ON {{prefix}}auth_audit_log (user_id);
CREATE INDEX {{prefix}}auth_audit_log_idx_crafty_operator_id ON {{prefix}}auth_audit_log (crafty_operator_id);
CREATE INDEX {{prefix}}auth_audit_log_idx_event_type ON {{prefix}}auth_audit_log (event_type);
CREATE INDEX {{prefix}}auth_audit_log_idx_system_context ON {{prefix}}auth_audit_log (system_context);
CREATE INDEX {{prefix}}auth_audit_log_idx_success ON {{prefix}}auth_audit_log (success);
CREATE INDEX {{prefix}}auth_audit_log_idx_created_at ON {{prefix}}auth_audit_log (created_at);

CREATE TABLE {{prefix}}auth_providers (
  auth_provider_id bigint NOT NULL,
  provider_name varchar(50) NOT NULL,
  client_id varchar(255) NOT NULL,
  client_secret text NOT NULL,
  scopes text,
  authorization_endpoint varchar(2000) NOT NULL,
  token_endpoint varchar(2000) NOT NULL,
  userinfo_endpoint varchar(2000) DEFAULT NULL,
  jwks_uri varchar(2000) DEFAULT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL,
  is_active tinyint NOT NULL DEFAULT '1',
  PRIMARY KEY (auth_provider_id)
);

CREATE UNIQUE INDEX {{prefix}}auth_providers_unique_provider_name ON {{prefix}}auth_providers (provider_name);

CREATE TABLE {{prefix}}auth_users (
  auth_user_id bigint NOT NULL,
  username varchar(255) NOT NULL,
  display_name varchar(42) NOT NULL,
  email varchar(100) DEFAULT NULL,
  password_hash varchar(255) DEFAULT NULL,
  auth_provider varchar(50) DEFAULT NULL,
  provider_id varchar(255) DEFAULT NULL,
  profile_image_url varchar(2000) DEFAULT NULL,
  last_login_ymdhis bigint DEFAULT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL,
  is_active tinyint NOT NULL DEFAULT '1',
  is_deleted tinyint NOT NULL DEFAULT '0',
  deleted_ymdhis bigint DEFAULT NULL,
    -- Two-factor authentication and timezone support
    two_factor_secret varchar(255) DEFAULT NULL,
    two_factor_enabled tinyint NOT NULL DEFAULT 0,
    two_factor_backup_codes text DEFAULT NULL,
    timezone_offset decimal(4,2) DEFAULT '0.00',
    timezone_name varchar(100) DEFAULT 'UTC',
  PRIMARY KEY (auth_user_id)
);

  -- Two
  CREATE TABLE {{prefix}}two_factor_audit (
    two_factor_audit_id bigint NOT NULL,
    auth_user_id bigint NOT NULL,
    action varchar(50) NOT NULL,
    ip_address varchar(45),
    user_agent text,
    created_ymdhis bigint NOT NULL,
    PRIMARY KEY (two_factor_audit_id),
    INDEX idx_user_id (auth_user_id),
    INDEX idx_created (created_ymdhis)
  );

  -- Magic link authentication
  CREATE TABLE {{prefix}}magic_link_tokens (
    magic_link_token_id bigint NOT NULL,
    email varchar(255) NOT NULL,
    token char(64) NOT NULL,
    expires_ymdhis bigint NOT NULL,
    used tinyint NOT NULL DEFAULT 0,
    created_ymdhis bigint NOT NULL,
    PRIMARY KEY (magic_link_token_id),
    UNIQUE KEY idx_token (token),
    INDEX idx_email (email),
    INDEX idx_expires (expires_ymdhis)
  );

  -- Rate limiting for authentication
  CREATE TABLE {{prefix}}auth_rate_limits (
    auth_rate_limit_id bigint NOT NULL,
    identifier varchar(255) NOT NULL,
    attempt_type varchar(50),
    attempted_ymdhis bigint NOT NULL,
    PRIMARY KEY (auth_rate_limit_id),
    INDEX idx_identifier (identifier),
    INDEX idx_attempt_time (attempted_ymdhis)
  );

CREATE UNIQUE INDEX {{prefix}}auth_users_unique_username ON {{prefix}}auth_users (username);
CREATE UNIQUE INDEX {{prefix}}auth_users_unique_provider_user ON {{prefix}}auth_users (auth_provider, provider_id);
CREATE INDEX {{prefix}}auth_users_idx_email ON {{prefix}}auth_users (email);
CREATE INDEX {{prefix}}auth_users_idx_is_active ON {{prefix}}auth_users (is_active);
CREATE INDEX {{prefix}}auth_users_idx_is_deleted ON {{prefix}}auth_users (is_deleted);
CREATE INDEX {{prefix}}auth_users_idx_created_ymdhis ON {{prefix}}auth_users (created_ymdhis);
CREATE INDEX {{prefix}}auth_users_idx_updated_ymdhis ON {{prefix}}auth_users (updated_ymdhis);
-- RESERVED ID DOCTRINE: auth_user_id is NOT ; application must supply explicit ID.

-- Password reset tokens for user account recovery
CREATE TABLE {{prefix}}password_resets (
  password_reset_id bigint NOT NULL,
  auth_user_id bigint NOT NULL,
  token varchar(64) NOT NULL,
  expiry_ymdhis bigint NOT NULL COMMENT 'YYYYMMDDHHIISS format',
  created_ymdhis bigint NOT NULL COMMENT 'YYYYMMDDHHIISS format',
  updated_ymdhis bigint DEFAULT NULL COMMENT 'YYYYMMDDHHIISS format',
  is_deleted tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (password_reset_id)
);

CREATE UNIQUE INDEX {{prefix}}password_resets_unique_token ON {{prefix}}password_resets (token);
CREATE INDEX {{prefix}}password_resets_idx_auth_user_id ON {{prefix}}password_resets (auth_user_id);
CREATE INDEX {{prefix}}password_resets_idx_expiry_ymdhis ON {{prefix}}password_resets (expiry_ymdhis);
CREATE INDEX {{prefix}}password_resets_idx_created_ymdhis ON {{prefix}}password_resets (created_ymdhis);
-- RESERVED ID DOCTRINE: password_reset_id is NOT ; application must supply explicit ID.

CREATE TABLE {{prefix}}channels (
  channel_id bigint NOT NULL,
  federation_node_id bigint NOT NULL,
  created_by_actor_id bigint NOT NULL,
  default_actor_id bigint NOT NULL DEFAULT '1',
  department_id bigint NOT NULL DEFAULT '1',
  channel_key varchar(64) NOT NULL,
  channel_slug varchar(32) NOT NULL DEFAULT 'channel_key',
  channel_type varchar(32) NOT NULL DEFAULT 'chat_room',
  language varchar(16) NOT NULL DEFAULT 'en',
  channel_name varchar(255) NOT NULL,
  description text,
  website_link varchar(512) DEFAULT NULL,
  metadata_json text,
  channel_config text DEFAULT NULL,
  status_flag tinyint NOT NULL DEFAULT '1',
  end_ymdhis bigint DEFAULT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL,
  is_deleted tinyint NOT NULL DEFAULT '0',
  deleted_ymdhis bigint DEFAULT NULL,
  aal_metadata_json json DEFAULT NULL,
  fleet_composition_json json DEFAULT NULL,
  awareness_version varchar(20) DEFAULT '3.0.0',
  channel_number int DEFAULT NULL,
  parent_channel_id bigint DEFAULT NULL,
  project_id bigint DEFAULT NULL,
  is_kernel tinyint NOT NULL DEFAULT '0',
  boot_sequence_order int DEFAULT NULL,
  visibility_status varchar(32) NOT NULL DEFAULT 'active',
  owner_actor_id bigint NOT NULL DEFAULT 1,
  access_level varchar(32) NOT NULL DEFAULT 'public',
  last_activity_ymdhis bigint NOT NULL DEFAULT 0,
  PRIMARY KEY (channel_id)
);

CREATE UNIQUE INDEX {{prefix}}channels_unq_channel_key_per_node ON {{prefix}}channels (channel_key, federation_node_id);
CREATE INDEX {{prefix}}channels_idx_domain ON {{prefix}}channels (federation_node_id);
CREATE INDEX {{prefix}}channels_idx_channel_key ON {{prefix}}channels (channel_key);
CREATE INDEX {{prefix}}channels_idx_status ON {{prefix}}channels (status_flag);
CREATE INDEX {{prefix}}channels_idx_dates ON {{prefix}}channels (end_ymdhis);
CREATE INDEX {{prefix}}channels_idx_awareness_version ON {{prefix}}channels (awareness_version);
CREATE INDEX {{prefix}}channels_idx_project_id ON {{prefix}}channels (project_id);
CREATE INDEX {{prefix}}channels_idx_visibility_status ON {{prefix}}channels (visibility_status);
CREATE INDEX {{prefix}}channels_idx_owner_actor_id ON {{prefix}}channels (owner_actor_id);
CREATE INDEX {{prefix}}channels_idx_access_level ON {{prefix}}channels (access_level);
CREATE INDEX {{prefix}}channels_idx_last_activity ON {{prefix}}channels (last_activity_ymdhis);




CREATE TABLE {{prefix}}channel_escalations (
  escalation_id bigint NOT NULL,
  channel_id bigint NOT NULL,
  thread_id bigint DEFAULT NULL,
  actor_id bigint DEFAULT NULL,
  escalated_to_actor_id bigint DEFAULT NULL,
  escalation_reason varchar(512) DEFAULT NULL,
  metadata_json json DEFAULT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL,
  is_deleted tinyint NOT NULL DEFAULT '0',
  deleted_ymdhis bigint DEFAULT NULL,
  PRIMARY KEY (escalation_id)
);

CREATE INDEX {{prefix}}channel_escalations_idx_channel_id ON {{prefix}}channel_escalations (channel_id);
CREATE INDEX {{prefix}}channel_escalations_idx_thread_id ON {{prefix}}channel_escalations (thread_id);
CREATE INDEX {{prefix}}channel_escalations_idx_actor_id ON {{prefix}}channel_escalations (actor_id);
CREATE INDEX {{prefix}}channel_escalations_idx_escalated_to_actor_id ON {{prefix}}channel_escalations (escalated_to_actor_id);

CREATE TABLE {{prefix}}channel_escalation_rules (
  rule_id bigint NOT NULL,
  channel_id bigint NOT NULL,
  rule_name varchar(255) NOT NULL,
  rule_description text,
  rule_type varchar(64) NOT NULL,
  rule_config_json json DEFAULT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL,
  is_deleted tinyint NOT NULL DEFAULT '0',
  deleted_ymdhis bigint DEFAULT NULL,
  PRIMARY KEY (rule_id)
);

CREATE INDEX {{prefix}}channel_escalation_rules_idx_channel_id ON {{prefix}}channel_escalation_rules (channel_id);
CREATE INDEX {{prefix}}channel_escalation_rules_idx_rule_type ON {{prefix}}channel_escalation_rules (rule_type);

CREATE TABLE {{prefix}}channel_files (
  file_id bigint NOT NULL,
  channel_id bigint NOT NULL,
  file_type varchar(50) NOT NULL,
  file_name varchar(255) NOT NULL,
  file_path varchar(500) NOT NULL,
  file_hash varchar(64) NOT NULL,
  file_size bigint NOT NULL,
  mime_type varchar(100) DEFAULT NULL,
  upload_ymdhis bigint NOT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL,
  is_deleted tinyint NOT NULL DEFAULT '0',
  deleted_ymdhis bigint DEFAULT NULL,
  migrated_from_directory varchar(255) DEFAULT NULL,
  PRIMARY KEY (file_id)
);

CREATE INDEX {{prefix}}channel_files_idx_channel_id ON {{prefix}}channel_files (channel_id);
CREATE INDEX {{prefix}}channel_files_idx_file_hash ON {{prefix}}channel_files (file_hash);
CREATE INDEX {{prefix}}channel_files_idx_is_deleted ON {{prefix}}channel_files (is_deleted);
CREATE INDEX {{prefix}}channel_files_idx_upload_ymdhis ON {{prefix}}channel_files (upload_ymdhis);

-- Old channel logs table removed in v4.0.86 - consolidated into {{prefix}}unified_log

-- Old channel log types table removed in v4.0.86 - consolidated into {{prefix}}unified_log

CREATE TABLE {{prefix}}channel_state (
  channel_state_id bigint NOT NULL,
  channel_id bigint NOT NULL,
  active_actors_json json DEFAULT NULL,
  speaker_actors_json json DEFAULT NULL,
  observer_actors_json json DEFAULT NULL,
  layers_enabled_json json DEFAULT NULL,
  operational_mode varchar(32) DEFAULT NULL,
  emotional_state_json json DEFAULT NULL,
  mood_framework varchar(32) NOT NULL DEFAULT 'western_analytical',
  recent_topics_json json DEFAULT NULL,
  semantic_weight float DEFAULT '0',
  trend_score float DEFAULT '0',
  last_activity_ymdhis bigint DEFAULT NULL,
  context_vector blob,
  routing_rules varchar(32) DEFAULT NULL,
  edge_visibility varchar(32) DEFAULT NULL,
  retention_policy varchar(32) DEFAULT NULL,
  decay_policy varchar(32) DEFAULT NULL,
  archive_flag tinyint DEFAULT '0',
  metadata_json json DEFAULT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL,
  PRIMARY KEY (channel_state_id)
);

CREATE INDEX {{prefix}}channel_state_idx_channel_id ON {{prefix}}channel_state (channel_id);
 
CREATE TABLE {{prefix}}collections (
  collection_id bigint NOT NULL,
  federation_node_id bigint NOT NULL,
  actor_id bigint DEFAULT NULL,
  department_id bigint DEFAULT NULL,
  name varchar(255) NOT NULL,
  slug varchar(100) NOT NULL,
  color char(6) DEFAULT '666666',
  description text,
  sort_order int DEFAULT '0',
  properties text,
  published_ymdhis bigint DEFAULT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL,
  is_deleted tinyint NOT NULL DEFAULT '0',
  deleted_ymdhis bigint DEFAULT NULL,
  parent_id bigint DEFAULT NULL,
  channel_id bigint DEFAULT NULL,
  is_nav_menu tinyint NOT NULL DEFAULT 0,
  nav_icon varchar(64) DEFAULT NULL,
  PRIMARY KEY (collection_id)
);

CREATE UNIQUE INDEX {{prefix}}collections_unique_collection_slug_domain ON {{prefix}}collections (federation_node_id, slug);
CREATE INDEX {{prefix}}collections_idx_name ON {{prefix}}collections (name);
CREATE INDEX {{prefix}}collections_idx_domain ON {{prefix}}collections (federation_node_id);
CREATE INDEX {{prefix}}collections_idx_department ON {{prefix}}collections (department_id);
CREATE INDEX {{prefix}}collections_idx_created_ymdhis ON {{prefix}}collections (created_ymdhis);
CREATE INDEX {{prefix}}collections_idx_updated_ymdhis ON {{prefix}}collections (updated_ymdhis);
CREATE INDEX {{prefix}}collections_idx_is_deleted ON {{prefix}}collections (is_deleted);
CREATE INDEX {{prefix}}collections_idx_sort_order ON {{prefix}}collections (sort_order);
CREATE INDEX {{prefix}}collections_idx_actor ON {{prefix}}collections (actor_id);
CREATE INDEX {{prefix}}collections_idx_channel_id ON {{prefix}}collections (channel_id);
CREATE INDEX {{prefix}}collections_idx_is_nav_menu ON {{prefix}}collections (is_nav_menu);
ALTER TABLE {{prefix}}collections CHANGE collection_id collection_id bigint NOT NULL ;
-- Collections as channel-scoped resource bundles (4.0.86): channel_id, is_nav_menu, nav_icon for UI/navigation.

CREATE TABLE {{prefix}}collection_tabs (
  collection_tab_id bigint NOT NULL,
  collection_tab_parent_id bigint DEFAULT NULL,
  collection_id bigint NOT NULL,
  federations_node_id bigint NOT NULL,
  department_id bigint DEFAULT NULL,
  actor_id bigint DEFAULT NULL,
  sort_order int DEFAULT '0',
  name varchar(255) NOT NULL,
  slug varchar(100) NOT NULL,
  color char(6) DEFAULT '4caf50',
  description text,
  is_hidden tinyint NOT NULL DEFAULT '0',
  visibility_rule text DEFAULT NULL,
  tab_type varchar(32) DEFAULT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL,
  is_active tinyint NOT NULL DEFAULT '1',
  is_deleted tinyint NOT NULL DEFAULT '0',
  deleted_ymdhis bigint DEFAULT NULL,
  PRIMARY KEY (collection_tab_id)
);

CREATE INDEX {{prefix}}collection_tabs_idx_collection_id ON {{prefix}}collection_tabs (collection_id);
CREATE INDEX {{prefix}}collection_tabs_idx_parent_tab_id ON {{prefix}}collection_tabs (collection_tab_parent_id);
CREATE INDEX {{prefix}}collection_tabs_idx_department ON {{prefix}}collection_tabs (department_id);
CREATE INDEX {{prefix}}collection_tabs_idx_actor_id ON {{prefix}}collection_tabs (actor_id);
CREATE INDEX {{prefix}}collection_tabs_idx_slug ON {{prefix}}collection_tabs (slug);
CREATE INDEX {{prefix}}collection_tabs_idx_is_active ON {{prefix}}collection_tabs (is_active);
ALTER TABLE {{prefix}}collection_tabs CHANGE collection_tab_id collection_tab_id bigint NOT NULL ;
-- Tabs: actor_id (was user_id), visibility_rule, tab_type for channel/nav (4.0.86).

CREATE TABLE {{prefix}}collection_tab_map (
  collection_tab_map_id bigint NOT NULL,
  collection_tab_id bigint NOT NULL,
  federations_node_id bigint NOT NULL,
  item_type varchar(20) NOT NULL,
  item_id bigint NOT NULL,
  sort_order int DEFAULT '0',
  properties text,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL,
  is_deleted tinyint NOT NULL DEFAULT '0',
  deleted_ymdhis bigint DEFAULT NULL,
  PRIMARY KEY (collection_tab_map_id)
);

CREATE UNIQUE INDEX {{prefix}}collection_tab_map_unique_item_in_tab ON {{prefix}}collection_tab_map (collection_tab_id, item_type, item_id);
CREATE INDEX {{prefix}}collection_tab_map_idx_collection_tab ON {{prefix}}collection_tab_map (collection_tab_id);
CREATE INDEX {{prefix}}collection_tab_map_idx_domain ON {{prefix}}collection_tab_map (federations_node_id);
CREATE INDEX {{prefix}}collection_tab_map_idx_item ON {{prefix}}collection_tab_map (item_type, item_id);
CREATE INDEX {{prefix}}collection_tab_map_idx_created_ymdhis ON {{prefix}}collection_tab_map (created_ymdhis);
CREATE INDEX {{prefix}}collection_tab_map_idx_updated_ymdhis ON {{prefix}}collection_tab_map (updated_ymdhis);
CREATE INDEX {{prefix}}collection_tab_map_idx_is_deleted ON {{prefix}}collection_tab_map (is_deleted);
CREATE INDEX {{prefix}}collection_tab_map_idx_sort_order ON {{prefix}}collection_tab_map (sort_order);

CREATE TABLE {{prefix}}collection_tab_paths (
  collection_tab_path_id bigint NOT NULL,
  collection_id bigint NOT NULL,
  collection_tab_id bigint NOT NULL,
  path varchar(500) NOT NULL,
  depth int NOT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint DEFAULT NULL,
  is_deleted tinyint NOT NULL DEFAULT '0',
  deleted_ymdhis bigint DEFAULT NULL,
  PRIMARY KEY (collection_tab_path_id)
);

CREATE UNIQUE INDEX {{prefix}}collection_tab_paths_unique_tab_path ON {{prefix}}collection_tab_paths (collection_id, collection_tab_id, path);
CREATE INDEX {{prefix}}collection_tab_paths_idx_collection ON {{prefix}}collection_tab_paths (collection_id);
CREATE INDEX {{prefix}}collection_tab_paths_idx_collection_tab ON {{prefix}}collection_tab_paths (collection_tab_id);
CREATE INDEX {{prefix}}collection_tab_paths_idx_path ON {{prefix}}collection_tab_paths (path);

CREATE TABLE {{prefix}}contents (
  content_id bigint NOT NULL,
  content_parent_id bigint DEFAULT NULL,
  federation_node_id bigint DEFAULT '1',
  federation_source_url varchar(2000) DEFAULT NULL COMMENT 'Canonical URL of content at source federation node',
  channel_id bigint DEFAULT NULL COMMENT 'Channel this content belongs to (doctrine: content placement)',
  department_id bigint DEFAULT NULL,
  actor_id bigint DEFAULT NULL,
  title varchar(255) NOT NULL,
  slug varchar(255) NOT NULL,
  custom_path varchar(255) DEFAULT NULL,
  description text,
  seo_keywords varchar(500) DEFAULT NULL,
  body text,
  content text,
  content_type varchar(50) DEFAULT 'article',
  format varchar(20) DEFAULT 'markdown',
  content_url varchar(2000) DEFAULT NULL,
  default_collection_id bigint DEFAULT NULL,
  source_url varchar(2000) DEFAULT NULL,
  source_title varchar(500) DEFAULT NULL,
  is_template tinyint NOT NULL DEFAULT '0',
  status varchar(64) DEFAULT 'draft',
  visibility varchar(64) DEFAULT 'public',
  view_count int DEFAULT '0',
  created_ymdhis bigint NOT NULL DEFAULT 0,
  utc_cycle varchar(64) NOT NULL,
  triage_status varchar(64) NOT NULL DEFAULT 'untriaged',
  triage_notes text,
  updated_ymdhis bigint NOT NULL,
  is_deleted tinyint NOT NULL DEFAULT '0',
  is_active tinyint NOT NULL DEFAULT '1',
  deleted_ymdhis bigint DEFAULT NULL,
  content_sections json DEFAULT NULL,
  version_number int NOT NULL DEFAULT '1',
  file_path_from_root varchar(500) DEFAULT NULL COMMENT 'FLIP Header: path from repo root (4.0.86)',
  file_last_modified_system_version varchar(20) DEFAULT NULL COMMENT 'FLIP: system version at last file edit',
  file_last_modified_utc bigint DEFAULT NULL COMMENT 'FLIP: UTC last modified YYYYMMDDHHIISS',
  tags json DEFAULT NULL,
  dialog_notes text,
  -- 4.0.86 Consolidation: Database-first identity columns
  atom_mappings JSON DEFAULT NULL COMMENT 'Consolidated from {{prefix}}content_atom_map',
  category_mappings JSON DEFAULT NULL COMMENT 'Consolidated from {{prefix}}content_category_map',
  content_events JSON DEFAULT NULL COMMENT 'Consolidated from {{prefix}}content_events',
  hashtags JSON DEFAULT NULL COMMENT 'Consolidated from {{prefix}}content_hashtag',
  inbound_links JSON DEFAULT NULL COMMENT 'Consolidated from {{prefix}}content_inbound_links',
  like_users JSON DEFAULT NULL COMMENT 'Consolidated from {{prefix}}content_likes',
  media_attachments JSON DEFAULT NULL COMMENT 'Consolidated from {{prefix}}content_media',
  question_mappings JSON DEFAULT NULL COMMENT 'Consolidated from {{prefix}}content_question_map',
  content_references JSON DEFAULT NULL COMMENT 'Consolidated from {{prefix}}content_references',
  revision_history JSON DEFAULT NULL COMMENT 'Consolidated from {{prefix}}content_revisions',
  share_users JSON DEFAULT NULL COMMENT 'Consolidated from {{prefix}}content_shares',
  tag_relationships JSON DEFAULT NULL COMMENT 'Consolidated from {{prefix}}content_tag_relationships',
  like_count bigint NOT NULL DEFAULT 0 COMMENT 'FLARE-aligned engagement cache',
  share_count bigint NOT NULL DEFAULT 0 COMMENT 'FLARE-aligned engagement cache',
  comment_count bigint NOT NULL DEFAULT 0 COMMENT 'FLARE-aligned engagement cache',
  PRIMARY KEY (content_id)
);

CREATE UNIQUE INDEX {{prefix}}contents_unique_content_slug_domain ON {{prefix}}contents (federation_node_id, slug);
CREATE UNIQUE INDEX {{prefix}}contents_idx_custom_path ON {{prefix}}contents (custom_path);
CREATE INDEX {{prefix}}contents_idx_file_path_from_root ON {{prefix}}contents (file_path_from_root);
CREATE INDEX {{prefix}}contents_idx_content_parent ON {{prefix}}contents (content_parent_id);
CREATE INDEX {{prefix}}contents_idx_content_type ON {{prefix}}contents (content_type);
CREATE INDEX {{prefix}}contents_idx_status ON {{prefix}}contents (status);
CREATE INDEX {{prefix}}contents_idx_visibility ON {{prefix}}contents (visibility);
CREATE INDEX {{prefix}}contents_idx_created_ymdhis ON {{prefix}}contents (created_ymdhis);
CREATE INDEX {{prefix}}contents_idx_updated_ymdhis ON {{prefix}}contents (updated_ymdhis);
CREATE INDEX {{prefix}}contents_idx_is_deleted ON {{prefix}}contents (is_deleted);
CREATE INDEX {{prefix}}contents_idx_is_active ON {{prefix}}contents (is_active);
CREATE INDEX {{prefix}}contents_idx_domain ON {{prefix}}contents (federation_node_id);
CREATE INDEX {{prefix}}contents_idx_channel_id ON {{prefix}}contents (channel_id);
CREATE INDEX {{prefix}}contents_idx_department ON {{prefix}}contents (department_id);
CREATE INDEX {{prefix}}contents_idx_user ON {{prefix}}contents (actor_id);

-- 4.0.86 Consolidation: Performance indexes for JSON columns
CREATE INDEX {{prefix}}contents_idx_engagement_counts ON {{prefix}}contents (like_count, share_count, comment_count);
CREATE INDEX {{prefix}}contents_idx_has_media ON {{prefix}}contents ((JSON_LENGTH(media_attachments) > 0));
CREATE INDEX {{prefix}}contents_idx_has_events ON {{prefix}}contents ((JSON_LENGTH(content_events) > 0));
CREATE INDEX {{prefix}}contents_idx_has_hashtags ON {{prefix}}contents ((JSON_LENGTH(hashtags) > 0));

-- 12-table install expansion v4.0.86: legacy URL mapping, references, search index
CREATE TABLE {{prefix}}legacy_content_mapping (
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
CREATE INDEX {{prefix}}legacy_content_mapping_idx_semantic_url ON {{prefix}}legacy_content_mapping (semantic_url);
CREATE INDEX {{prefix}}legacy_content_mapping_idx_content_type ON {{prefix}}legacy_content_mapping (content_type);
CREATE INDEX {{prefix}}legacy_content_mapping_idx_content_id ON {{prefix}}legacy_content_mapping (content_id);
CREATE INDEX {{prefix}}legacy_content_mapping_idx_is_active ON {{prefix}}legacy_content_mapping (is_active);
CREATE INDEX {{prefix}}legacy_content_mapping_idx_created ON {{prefix}}legacy_content_mapping (created_ymdhis);
CREATE UNIQUE INDEX {{prefix}}legacy_content_mapping_uk_legacy_url ON {{prefix}}legacy_content_mapping (legacy_url);

CREATE TABLE {{prefix}}reference_objects (
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
CREATE INDEX {{prefix}}reference_objects_idx_object_slug ON {{prefix}}reference_objects (object_slug);
CREATE INDEX {{prefix}}reference_objects_idx_type_slug ON {{prefix}}reference_objects (object_type, object_slug);
CREATE INDEX {{prefix}}reference_objects_idx_is_deleted ON {{prefix}}reference_objects (is_deleted);

-- DEPRECATED 4.0.87: {{prefix}}reference_cited_by removed - consolidated into {{prefix}}edges.
-- Citation relationships are stored in {{prefix}}edges with edge_type='cites' or 'cited_by'.
-- Use left_object_type='content', right_object_type='reference_object' for the same semantics.

CREATE TABLE {{prefix}}search_index (
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
CREATE INDEX {{prefix}}search_index_idx_domain_type ON {{prefix}}search_index (domain_id, entity_type);
CREATE INDEX {{prefix}}search_index_idx_entity_reference ON {{prefix}}search_index (entity_type, entity_id);
CREATE INDEX {{prefix}}search_index_idx_updated ON {{prefix}}search_index (updated_ymdhis);
CREATE INDEX {{prefix}}search_index_idx_is_deleted ON {{prefix}}search_index (is_deleted);
CREATE INDEX {{prefix}}search_index_idx_relevance ON {{prefix}}search_index (relevance_score);
CREATE UNIQUE INDEX {{prefix}}search_index_unique_entity ON {{prefix}}search_index (domain_id, entity_type, entity_id);

CREATE TABLE {{prefix}}crafty_user_mapping (
  crafty_user_mapping_id bigint NOT NULL ,
  {{prefix}}user_id bigint DEFAULT NULL,
  crafty_operator_id int DEFAULT NULL,
  mapping_type varchar(50) NOT NULL DEFAULT 'manual',
  notes text,
  created_at bigint NOT NULL DEFAULT 0,
  updated_at bigint NOT NULL DEFAULT 0,
  PRIMARY KEY (crafty_user_mapping_id)
);

CREATE INDEX {{prefix}}crafty_user_mapping_idx_crafty_operator_id ON {{prefix}}crafty_user_mapping (crafty_operator_id);
CREATE INDEX {{prefix}}crafty_user_mapping_idx_{{prefix}}user_id ON {{prefix}}crafty_user_mapping ({{prefix}}user_id);
CREATE INDEX {{prefix}}crafty_user_mapping_idx_mapping_type ON {{prefix}}crafty_user_mapping (mapping_type);
CREATE UNIQUE INDEX {{prefix}}crafty_user_mapping_unique_crafty_operator_mapping ON {{prefix}}crafty_user_mapping (crafty_operator_id);
CREATE UNIQUE INDEX {{prefix}}crafty_user_mapping_unique_{{prefix}}user_mapping ON {{prefix}}crafty_user_mapping ({{prefix}}user_id);

CREATE TABLE {{prefix}}crafty_syntax_leave_message (
  crafty_syntax_leave_message_id bigint NOT NULL ,
  department_id bigint NOT NULL DEFAULT 0,
  email varchar(255) NOT NULL DEFAULT '',
  phone varchar(45) DEFAULT NULL,
  name varchar(200) DEFAULT NULL,
  subject varchar(255) NOT NULL DEFAULT '',
  message text,
  priority tinyint NOT NULL DEFAULT 2,
  session_data text,
  form_data text,
  ip_address varchar(45) DEFAULT NULL,
  user_agent varchar(255) DEFAULT NULL,
  status enum('new','in_progress','resolved','spam') NOT NULL DEFAULT 'new',
  assigned_to bigint DEFAULT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL,
  is_deleted tinyint NOT NULL DEFAULT 0,
  deleted_ymdhis bigint DEFAULT NULL,
  PRIMARY KEY (crafty_syntax_leave_message_id)
);

CREATE INDEX {{prefix}}crafty_syntax_leave_message_idx_assigned ON {{prefix}}crafty_syntax_leave_message (assigned_to);
CREATE INDEX {{prefix}}crafty_syntax_leave_message_idx_created ON {{prefix}}crafty_syntax_leave_message (created_ymdhis);
CREATE INDEX {{prefix}}crafty_syntax_leave_message_idx_department ON {{prefix}}crafty_syntax_leave_message (department_id);
CREATE INDEX {{prefix}}crafty_syntax_leave_message_idx_email ON {{prefix}}crafty_syntax_leave_message (email);
CREATE FULLTEXT INDEX {{prefix}}crafty_syntax_leave_message_idx_message_search ON {{prefix}}crafty_syntax_leave_message (email, name, subject, message);
CREATE INDEX {{prefix}}crafty_syntax_leave_message_idx_priority ON {{prefix}}crafty_syntax_leave_message (priority);
CREATE INDEX {{prefix}}crafty_syntax_leave_message_idx_status ON {{prefix}}crafty_syntax_leave_message (status);

CREATE TABLE {{prefix}}crafty_syntax_layer_invites (
  crafty_syntax_layer_invite_id bigint NOT NULL ,
  layer_name varchar(100) NOT NULL DEFAULT '',
  image_name varchar(255) NOT NULL DEFAULT '',
  image_map text,
  department_name varchar(100) NOT NULL DEFAULT '',
  user_id bigint NOT NULL DEFAULT 0,
  is_active tinyint NOT NULL DEFAULT 1,
  display_count int NOT NULL DEFAULT 0,
  click_count int NOT NULL DEFAULT 0,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL,
  is_deleted tinyint NOT NULL DEFAULT 0,
  deleted_ymdhis bigint DEFAULT NULL,
  PRIMARY KEY (crafty_syntax_layer_invite_id)
);

CREATE INDEX {{prefix}}crafty_syntax_layer_invites_idx_active ON {{prefix}}crafty_syntax_layer_invites (is_active);
CREATE INDEX {{prefix}}crafty_syntax_layer_invites_idx_created ON {{prefix}}crafty_syntax_layer_invites (created_ymdhis);
CREATE INDEX {{prefix}}crafty_syntax_layer_invites_idx_department ON {{prefix}}crafty_syntax_layer_invites (department_name);
CREATE INDEX {{prefix}}crafty_syntax_layer_invites_idx_name ON {{prefix}}crafty_syntax_layer_invites (layer_name);
CREATE INDEX {{prefix}}crafty_syntax_layer_invites_idx_updated ON {{prefix}}crafty_syntax_layer_invites (updated_ymdhis);
CREATE INDEX {{prefix}}crafty_syntax_layer_invites_idx_user ON {{prefix}}crafty_syntax_layer_invites (user_id);

CREATE TABLE {{prefix}}crafty_syntax_chat_questions (
  crafty_syntax_chat_question_id bigint NOT NULL ,
  department_id bigint NOT NULL DEFAULT 0,
  sort_order int NOT NULL DEFAULT 0,
  headertext mediumtext,
  field_type varchar(60) DEFAULT NULL,
  options mediumtext,
  flags varchar(255) DEFAULT NULL,
  module_name varchar(100) DEFAULT NULL,
  is_required tinyint NOT NULL DEFAULT 0,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL,
  is_deleted tinyint NOT NULL DEFAULT 0,
  deleted_ymdhis bigint DEFAULT NULL,
  PRIMARY KEY (crafty_syntax_chat_question_id)
);

CREATE INDEX {{prefix}}crafty_syntax_chat_questions_idx_department ON {{prefix}}crafty_syntax_chat_questions (department_id);

CREATE TABLE {{prefix}}crafty_syntax_chat_mod_departments (
  crafty_syntax_chat_mod_department_id bigint NOT NULL ,
  department_id bigint NOT NULL DEFAULT 0,
  module_id bigint NOT NULL DEFAULT 0,
  sort_order int NOT NULL DEFAULT 0,
  is_active tinyint NOT NULL DEFAULT 1,
  is_default tinyint NOT NULL DEFAULT 0,
  PRIMARY KEY (crafty_syntax_chat_mod_department_id)
);

CREATE TABLE {{prefix}}crafty_syntax_auto_invite (
  crafty_syntax_auto_invite_id bigint NOT NULL ,
  is_offline tinyint NOT NULL DEFAULT 0,
  is_active tinyint NOT NULL DEFAULT 0,
  department_id bigint NOT NULL DEFAULT 0,
  message mediumtext,
  page_url varchar(500) DEFAULT NULL,
  visits int NOT NULL DEFAULT 0,
  referrer_url varchar(500) DEFAULT NULL,
  invite_type varchar(50) DEFAULT NULL,
  trigger_seconds int NOT NULL DEFAULT 0,
  operator_user_id bigint NOT NULL DEFAULT 0,
  show_socialpane tinyint NOT NULL DEFAULT 0,
  exclude_mobile tinyint NOT NULL DEFAULT 0,
  only_mobile tinyint NOT NULL DEFAULT 0,
  created_ymdhis bigint NOT NULL DEFAULT 20250101000000,
  updated_ymdhis bigint NOT NULL DEFAULT 20250101000000,
  is_deleted tinyint NOT NULL DEFAULT 0,
  deleted_ymdhis bigint DEFAULT NULL,
  PRIMARY KEY (crafty_syntax_auto_invite_id)
);

CREATE INDEX {{prefix}}crafty_syntax_auto_invite_idx_created ON {{prefix}}crafty_syntax_auto_invite (created_ymdhis);
CREATE INDEX {{prefix}}crafty_syntax_auto_invite_idx_department ON {{prefix}}crafty_syntax_auto_invite (department_id);
CREATE INDEX {{prefix}}crafty_syntax_auto_invite_idx_operator ON {{prefix}}crafty_syntax_auto_invite (operator_user_id);
CREATE INDEX {{prefix}}crafty_syntax_auto_invite_idx_page_url ON {{prefix}}crafty_syntax_auto_invite (page_url);
CREATE INDEX {{prefix}}crafty_syntax_auto_invite_idx_status ON {{prefix}}crafty_syntax_auto_invite (is_active, is_deleted);



-- Context system removed - context relationships now handled by edges table (see line 408)
-- See decision: 20260402_210000_DECISION_prd31_rejection_parallel_classification.md

CREATE TABLE {{prefix}}crm_leads (
  crm_lead_id bigint NOT NULL,
  email varchar(255) DEFAULT NULL,
  phone varchar(45) DEFAULT NULL,
  first_name varchar(100) DEFAULT NULL,
  last_name varchar(100) DEFAULT NULL,
  source varchar(100) DEFAULT NULL,
  status varchar(50) NOT NULL DEFAULT 'new',
  lead_score int NOT NULL DEFAULT '0',
  assigned_to bigint DEFAULT NULL,
  lead_data text,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL,
  is_deleted tinyint NOT NULL DEFAULT '0',
  deleted_ymdhis bigint DEFAULT NULL,
  PRIMARY KEY (crm_lead_id)
);


CREATE TABLE {{prefix}}crm_lead_messages (
  crm_lead_message_id bigint NOT NULL,
  lead_id bigint DEFAULT NULL,
  from_email varchar(255) DEFAULT NULL,
  subject varchar(255) DEFAULT NULL,
  body_text text NOT NULL,
  notes varchar(255) DEFAULT NULL,
  actor_id bigint DEFAULT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL,
  is_deleted smallint NOT NULL DEFAULT '0',
  deleted_ymdhis bigint DEFAULT NULL,
  PRIMARY KEY (crm_lead_message_id)
);

CREATE INDEX {{prefix}}crm_lead_messages_lead_id ON {{prefix}}crm_lead_messages (lead_id);
CREATE INDEX {{prefix}}crm_lead_messages_actor_id ON {{prefix}}crm_lead_messages (actor_id);

CREATE TABLE {{prefix}}departments (
  department_id bigint NOT NULL,
  federation_node_id bigint NOT NULL,
  name varchar(64) NOT NULL,
  description text,
  department_type varchar(32) NOT NULL DEFAULT 'general',
  default_actor_id bigint NOT NULL DEFAULT '1',
  settings_json json DEFAULT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL,
  is_deleted tinyint NOT NULL DEFAULT '0',
  deleted_ymdhis bigint DEFAULT NULL,
  PRIMARY KEY (department_id)
);

CREATE INDEX {{prefix}}departments_idx_name ON {{prefix}}departments (name);
CREATE INDEX {{prefix}}departments_idx_type ON {{prefix}}departments (department_type);
CREATE INDEX {{prefix}}departments_idx_federation_node ON {{prefix}}departments (federation_node_id);

CREATE TABLE {{prefix}}department_roles (
  department_role_id bigint NOT NULL,
  actor_id bigint NOT NULL,
  department_id bigint NOT NULL,
  role_key varchar(64) NOT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL,
  is_deleted tinyint NOT NULL DEFAULT 0,
  deleted_ymdhis bigint DEFAULT NULL,
  PRIMARY KEY (department_role_id)
);

CREATE INDEX {{prefix}}department_roles_idx_actor_id ON {{prefix}}department_roles (actor_id);
CREATE INDEX {{prefix}}department_roles_idx_department_id ON {{prefix}}department_roles (department_id);
CREATE INDEX {{prefix}}department_roles_idx_role_key ON {{prefix}}department_roles (role_key);

CREATE TABLE {{prefix}}department_metadata (
  department_metadata_id bigint NOT NULL,
  department_id bigint NOT NULL,
  metadata_json json NOT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL,
  is_active tinyint NOT NULL DEFAULT '1',
  is_deleted tinyint NOT NULL DEFAULT '0',
  deleted_ymdhis bigint DEFAULT NULL,
  PRIMARY KEY (department_metadata_id)
);

CREATE UNIQUE INDEX {{prefix}}department_metadata_uq_department_metadata ON {{prefix}}department_metadata (department_id);
ALTER TABLE {{prefix}}department_metadata CHANGE department_metadata_id department_metadata_id bigint NOT NULL ;

-- Channel <-> Department many-to-many (doctrine: channels can belong to multiple departments)
CREATE TABLE {{prefix}}channel_departments (
  channel_department_id bigint NOT NULL,
  channel_id bigint NOT NULL,
  department_id bigint NOT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  PRIMARY KEY (channel_department_id)
);

CREATE UNIQUE INDEX {{prefix}}channel_departments_unq_channel_department ON {{prefix}}channel_departments (channel_id, department_id);
CREATE INDEX {{prefix}}channel_departments_idx_channel ON {{prefix}}channel_departments (channel_id);
CREATE INDEX {{prefix}}channel_departments_idx_department ON {{prefix}}channel_departments (department_id);

CREATE TABLE {{prefix}}dialog_channels (
  channel_id bigint NOT NULL,
  channel_name varchar(255) NOT NULL,
  file_source varchar(255) NOT NULL,
  title varchar(500) DEFAULT NULL,
  description text,
  speaker varchar(100) DEFAULT NULL,
  target varchar(100) DEFAULT NULL,
  categories json DEFAULT NULL,
  collections json DEFAULT NULL,
  channels json DEFAULT NULL,
  tags json DEFAULT NULL,
  version varchar(20) DEFAULT NULL,
  status varchar(64) DEFAULT 'published',
  author varchar(100) DEFAULT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL,
  message_count int DEFAULT '0',
  metadata_json json DEFAULT NULL,
  PRIMARY KEY (channel_id)
);

CREATE UNIQUE INDEX {{prefix}}dialog_channels_idx_channel_name ON {{prefix}}dialog_channels (channel_name);
CREATE INDEX {{prefix}}dialog_channels_idx_file_source ON {{prefix}}dialog_channels (file_source);
CREATE INDEX {{prefix}}dialog_channels_idx_speaker ON {{prefix}}dialog_channels (speaker);
CREATE INDEX {{prefix}}dialog_channels_idx_target ON {{prefix}}dialog_channels (target);
CREATE INDEX {{prefix}}dialog_channels_idx_status ON {{prefix}}dialog_channels (status);
CREATE INDEX {{prefix}}dialog_channels_idx_created_ymdhis ON {{prefix}}dialog_channels (created_ymdhis);
CREATE INDEX {{prefix}}dialog_channels_idx_updated_ymdhis ON {{prefix}}dialog_channels (updated_ymdhis);
CREATE INDEX {{prefix}}dialog_channels_idx_dialog_channels_composite ON {{prefix}}dialog_channels (status, created_ymdhis);

CREATE TABLE {{prefix}}dialog_messages (
  dialog_message_id bigint NOT NULL,
  message_id bigint NOT NULL DEFAULT 0,
  dialog_thread_id bigint DEFAULT NULL,
  channel_id bigint DEFAULT NULL,
  from_actor_id bigint DEFAULT NULL,
  source_faucet_slug varchar(100) DEFAULT NULL,
  source_faucet_instance_id varchar(100) DEFAULT NULL,
  to_actor_id bigint DEFAULT NULL,
  read_by_actor_id bigint NOT NULL DEFAULT 0,
  read_by_actor_utc bigint NOT NULL DEFAULT 0,
  message_text varchar(1000) NOT NULL,
  message_type varchar(64) NOT NULL DEFAULT 'text',
  metadata_json json DEFAULT NULL,
  mood_rgb char(6) DEFAULT NULL,
  mood_framework varchar(32) NOT NULL DEFAULT 'western_analytical',
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL,
  is_deleted tinyint NOT NULL DEFAULT '0',
  deleted_ymdhis bigint DEFAULT NULL,
  message_body mediumtext,
  PRIMARY KEY (dialog_message_id)
);

CREATE INDEX {{prefix}}dialog_messages_idx_channel ON {{prefix}}dialog_messages (channel_id);
CREATE INDEX {{prefix}}dialog_messages_idx_created ON {{prefix}}dialog_messages (created_ymdhis);
CREATE INDEX {{prefix}}dialog_messages_idx_updated ON {{prefix}}dialog_messages (updated_ymdhis);
CREATE INDEX {{prefix}}dialog_messages_idx_deleted ON {{prefix}}dialog_messages (is_deleted);
CREATE INDEX {{prefix}}dialog_messages_idx_message_type ON {{prefix}}dialog_messages (message_type);
CREATE INDEX {{prefix}}dialog_messages_idx_dialog_thread_id ON {{prefix}}dialog_messages (dialog_thread_id);
CREATE INDEX {{prefix}}dialog_messages_idx_to_actor_id ON {{prefix}}dialog_messages (to_actor_id);
CREATE INDEX {{prefix}}dialog_messages_idx_read_by_actor ON {{prefix}}dialog_messages (read_by_actor_id);
CREATE INDEX {{prefix}}dialog_messages_idx_read_utc ON {{prefix}}dialog_messages (read_by_actor_utc);
CREATE INDEX {{prefix}}dialog_messages_idx_faucet ON {{prefix}}dialog_messages (source_faucet_slug, source_faucet_instance_id);

CREATE TABLE {{prefix}}dialog_threads (
  dialog_thread_id bigint NOT NULL,
  title varchar(255) NOT NULL,
  last_message_ymdhis bigint DEFAULT NULL,
  federation_node_id bigint NOT NULL DEFAULT '1',
  channel_id bigint DEFAULT NULL,
  project_slug varchar(100) DEFAULT NULL,
  task_name varchar(255) DEFAULT NULL,
  created_by_actor_id bigint NOT NULL,
  summary_text text,
  bg_color char(6) NOT NULL DEFAULT 'FFFFFF',
  text_color char(6) NOT NULL DEFAULT '000000',
  alt_text_color char(6) NOT NULL DEFAULT '666666',
  status varchar(64) NOT NULL DEFAULT 'Open',
  artifacts json DEFAULT NULL,
  metadata_json json DEFAULT NULL,
  thread_lineage text DEFAULT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL,
  is_deleted tinyint NOT NULL DEFAULT '0',
  deleted_ymdhis bigint DEFAULT NULL,
  escalated_to_operator_id bigint DEFAULT NULL,
  escalation_reason varchar(255) DEFAULT NULL,
  escalation_timestamp bigint DEFAULT NULL,
  visibility_status varchar(32) NOT NULL DEFAULT 'active',
  owner_actor_id bigint NOT NULL,
  assigned_actor_id bigint DEFAULT NULL,
  thread_type varchar(32) NOT NULL DEFAULT 'discussion',
  thread_priority varchar(32) NOT NULL DEFAULT 'normal',
  PRIMARY KEY (dialog_thread_id)
);

CREATE INDEX {{prefix}}dialog_threads_idx_node ON {{prefix}}dialog_threads (federation_node_id);
CREATE INDEX {{prefix}}dialog_threads_idx_channel ON {{prefix}}dialog_threads (channel_id);
CREATE INDEX {{prefix}}dialog_threads_idx_project ON {{prefix}}dialog_threads (project_slug);
CREATE INDEX {{prefix}}dialog_threads_idx_task ON {{prefix}}dialog_threads (task_name);
CREATE INDEX {{prefix}}dialog_threads_idx_status ON {{prefix}}dialog_threads (status);
CREATE INDEX {{prefix}}dialog_threads_idx_created ON {{prefix}}dialog_threads (created_ymdhis);
CREATE INDEX {{prefix}}dialog_threads_idx_updated ON {{prefix}}dialog_threads (updated_ymdhis);
CREATE INDEX {{prefix}}dialog_threads_idx_visibility_status ON {{prefix}}dialog_threads (visibility_status);
CREATE INDEX {{prefix}}dialog_threads_idx_owner_actor_id ON {{prefix}}dialog_threads (owner_actor_id);
CREATE INDEX {{prefix}}dialog_threads_idx_assigned_actor_id ON {{prefix}}dialog_threads (assigned_actor_id);
CREATE INDEX {{prefix}}dialog_threads_idx_thread_type ON {{prefix}}dialog_threads (thread_type);
CREATE INDEX {{prefix}}dialog_threads_idx_thread_priority ON {{prefix}}dialog_threads (thread_priority);
CREATE INDEX {{prefix}}dialog_threads_idx_deleted ON {{prefix}}dialog_threads (is_deleted);
CREATE INDEX {{prefix}}dialog_threads_idx_created_by_actor ON {{prefix}}dialog_threads (created_by_actor_id);
CREATE INDEX {{prefix}}dialog_threads_idx_last_message ON {{prefix}}dialog_threads (last_message_ymdhis);

-- Thread metadata table for storing additional thread information
CREATE TABLE {{prefix}}thread_metadata (
  thread_metadata_id bigint NOT NULL,
  dialog_thread_id bigint NOT NULL,
  metadata_key varchar(255) NOT NULL,
  metadata_value text,
  metadata_type varchar(64) NOT NULL DEFAULT 'string',
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL,
  created_by_actor_id bigint NOT NULL,
  is_deleted tinyint NOT NULL DEFAULT '0',
  deleted_ymdhis bigint DEFAULT NULL,
  PRIMARY KEY (thread_metadata_id)
);

CREATE UNIQUE INDEX {{prefix}}thread_metadata_unq_thread_key ON {{prefix}}thread_metadata (dialog_thread_id, metadata_key);
CREATE INDEX {{prefix}}thread_metadata_idx_thread_id ON {{prefix}}thread_metadata (dialog_thread_id);
CREATE INDEX {{prefix}}thread_metadata_idx_key ON {{prefix}}thread_metadata (metadata_key);
CREATE INDEX {{prefix}}thread_metadata_idx_type ON {{prefix}}thread_metadata (metadata_type);
CREATE INDEX {{prefix}}thread_metadata_idx_created ON {{prefix}}thread_metadata (created_ymdhis);
CREATE INDEX {{prefix}}thread_metadata_idx_deleted ON {{prefix}}thread_metadata (is_deleted);

CREATE TABLE {{prefix}}doctrine_evolution_audit (
  doctrine_evolution_audit_id bigint NOT NULL,
  refinement_id bigint NOT NULL,
  evolution_step tinyint NOT NULL,
  step_description varchar(255) NOT NULL,
  step_status varchar(64) DEFAULT 'pending',
  step_metadata_json json DEFAULT NULL,
  started_ymdhis bigint DEFAULT NULL,
  completed_ymdhis bigint DEFAULT NULL,
  audit_version varchar(20) DEFAULT '3.0.0',
  PRIMARY KEY (doctrine_evolution_audit_id)
);

CREATE INDEX {{prefix}}doctrine_evolution_audit_idx_refinement_step ON {{prefix}}doctrine_evolution_audit (refinement_id, evolution_step);
CREATE INDEX {{prefix}}doctrine_evolution_audit_idx_step_status ON {{prefix}}doctrine_evolution_audit (step_status);
CREATE INDEX {{prefix}}doctrine_evolution_audit_idx_completion_time ON {{prefix}}doctrine_evolution_audit (completed_ymdhis);

CREATE TABLE {{prefix}}tickets (
  ticket_id bigint NOT NULL,
  channel_id bigint NOT NULL,
  actor_id bigint NOT NULL,
  status varchar(64) NOT NULL DEFAULT 'open',
  priority varchar(64) NOT NULL DEFAULT 'medium',
  subject varchar(255) NOT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL DEFAULT 0,
  metadata_json json DEFAULT NULL,
  is_deleted tinyint NOT NULL DEFAULT 0,
  deleted_ymdhis bigint DEFAULT NULL,
  PRIMARY KEY (ticket_id)
);

CREATE INDEX {{prefix}}tickets_idx_channel ON {{prefix}}tickets (channel_id);
CREATE INDEX {{prefix}}tickets_idx_actor ON {{prefix}}tickets (actor_id);
CREATE INDEX {{prefix}}tickets_idx_status ON {{prefix}}tickets (status);

CREATE TABLE {{prefix}}ticket_messages (
  ticket_message_id bigint NOT NULL,
  ticket_id bigint NOT NULL,
  actor_id bigint NOT NULL,
  message_text text NOT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  is_deleted tinyint NOT NULL DEFAULT 0,
  deleted_ymdhis bigint DEFAULT NULL,
  PRIMARY KEY (ticket_message_id)
);

CREATE INDEX {{prefix}}ticket_messages_idx_ticket ON {{prefix}}ticket_messages (ticket_id);

-- DEPRECATED in 4.0.86: {{prefix}}doctrine_refinements replaced by {{prefix}}tickets
-- CREATE TABLE {{prefix}}doctrine_refinements (
--   doctrine_refinement_id bigint NOT NULL,
--   cip_event_id bigint NOT NULL,
--   doctrine_file_path varchar(500) NOT NULL,
--   refinement_type varchar(64) NOT NULL,
--   change_description text NOT NULL,
--   before_content_hash varchar(64) DEFAULT NULL,
--   after_content_hash varchar(64) NOT NULL,
--   impact_assessment_json json DEFAULT NULL,
--   approval_status varchar(64) DEFAULT 'pending',
--   approved_by varchar(100) DEFAULT NULL,
--   applied_ymdhis bigint DEFAULT NULL,
--   created_ymdhis bigint NOT NULL DEFAULT 0,
--   refinement_version varchar(20) DEFAULT '3.0.0',
--   PRIMARY KEY (doctrine_refinement_id)
-- );

 

-- (Deduplicated {{prefix}}edges table; canonical definition remains below)

CREATE INDEX {{prefix}}edges_idx_left ON {{prefix}}edges (left_object_type, left_object_id);
CREATE INDEX {{prefix}}edges_idx_right ON {{prefix}}edges (right_object_type, right_object_id);
CREATE INDEX {{prefix}}edges_idx_edge_type ON {{prefix}}edges (edge_type);
CREATE INDEX {{prefix}}edges_idx_edge_category ON {{prefix}}edges (edge_category);
CREATE INDEX {{prefix}}edges_idx_actor ON {{prefix}}edges (actor_id);
CREATE INDEX {{prefix}}edges_idx_domain ON {{prefix}}edges (domain_id);
CREATE INDEX {{prefix}}edges_idx_is_deleted ON {{prefix}}edges (is_deleted);
CREATE INDEX {{prefix}}edges_idx_semantic_weight ON {{prefix}}edges (semantic_weight);
CREATE INDEX {{prefix}}edges_idx_relationship_type ON {{prefix}}edges (relationship_type);
CREATE INDEX {{prefix}}edges_idx_channel_semantic ON {{prefix}}edges (channel_id, relationship_type, semantic_weight);
CREATE INDEX {{prefix}}edges_idx_created ON {{prefix}}edges (created_ymdhis);
CREATE INDEX {{prefix}}edges_idx_updated ON {{prefix}}edges (updated_ymdhis);
-- FLARE protocol indexes (added 2026-02-27)
CREATE INDEX {{prefix}}edges_idx_flare_weight ON {{prefix}}edges (flare_weight, edge_type);
CREATE INDEX {{prefix}}edges_idx_flare_discovered ON {{prefix}}edges (flare_discovered_via, flare_auto_generated);
CREATE INDEX {{prefix}}edges_idx_flare_files ON {{prefix}}edges (left_object_type, left_object_id, edge_type, right_object_type, right_object_id);

-- Edge type registry: canonical edge types and semantics (4.0.86 LILITH implementation prompt). No FK; IDs from application.
CREATE TABLE {{prefix}}edge_type_definitions (
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
  UNIQUE KEY {{prefix}}edge_type_definitions_unique_edge_type (edge_type)
);
CREATE INDEX {{prefix}}edge_type_definitions_idx_domain ON {{prefix}}edge_type_definitions (domain);

-- Pre-action authorization: required traits/capabilities/roles per action (4.0.86 LILITH implementation prompt). No FK; IDs from application.
CREATE TABLE {{prefix}}action_authorization (
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
  UNIQUE KEY {{prefix}}action_authorization_unique_action_key (action_key)
);
CREATE INDEX {{prefix}}action_authorization_idx_action ON {{prefix}}action_authorization (action_key);

CREATE TABLE {{prefix}}emotional_frameworks (
  framework_name varchar(32) NOT NULL,
  description text,
  is_default tinyint NOT NULL DEFAULT '0',
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL,
  PRIMARY KEY (framework_name)
);

-- Old event log table removed in v4.0.86 - consolidated into {{prefix}}unified_log

CREATE TABLE {{prefix}}event_metadata (
  metadata_id bigint NOT NULL,
  event_id bigint NOT NULL,
  metadata_key varchar(100) NOT NULL,
  metadata_value text,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  PRIMARY KEY (metadata_id)
);

CREATE INDEX {{prefix}}event_metadata_idx_event_id ON {{prefix}}event_metadata (event_id);
CREATE INDEX {{prefix}}event_metadata_idx_metadata_key ON {{prefix}}event_metadata (metadata_key);
CREATE INDEX {{prefix}}event_metadata_idx_created_ymdhis ON {{prefix}}event_metadata (created_ymdhis);

CREATE TABLE {{prefix}}federation_categories (
  federation_category_id bigint NOT NULL,
  category_name varchar(255) NOT NULL,
  category_slug varchar(255) NOT NULL,
  category_description text,
  meta_json json DEFAULT NULL,
  is_deleted tinyint NOT NULL DEFAULT '0',
  deleted_ymdhis bigint NOT NULL DEFAULT '0',
  created_ymdhis bigint NOT NULL DEFAULT '0',
  updated_ymdhis bigint NOT NULL DEFAULT '0',
  PRIMARY KEY (federation_category_id)
);

CREATE INDEX {{prefix}}federation_categories_idx_category_slug ON {{prefix}}federation_categories (category_slug);
CREATE INDEX {{prefix}}federation_categories_idx_is_deleted ON {{prefix}}federation_categories (is_deleted);

CREATE TABLE {{prefix}}federation_category_map (
  federation_category_map_id bigint NOT NULL,
  federation_node_id bigint NOT NULL,
  federation_category_id bigint NOT NULL,
  meta_json json DEFAULT NULL,
  is_deleted tinyint NOT NULL DEFAULT '0',
  deleted_ymdhis bigint NOT NULL DEFAULT '0',
  created_ymdhis bigint NOT NULL DEFAULT '0',
  updated_ymdhis bigint NOT NULL DEFAULT '0',
  PRIMARY KEY (federation_category_map_id)
);

CREATE INDEX {{prefix}}federation_category_map_idx_node ON {{prefix}}federation_category_map (federation_node_id);
CREATE INDEX {{prefix}}federation_category_map_idx_category ON {{prefix}}federation_category_map (federation_category_id);
CREATE INDEX {{prefix}}federation_category_map_idx_is_deleted ON {{prefix}}federation_category_map (is_deleted);


CREATE TABLE {{prefix}}federation_nodes (
  federation_node_id bigint NOT NULL,
  node_type varchar(32) NOT NULL DEFAULT 'local',
  node_base_url varchar(500) NOT NULL,
  default_department_id bigint DEFAULT NULL,
  node_name varchar(255) DEFAULT NULL,
  description text,
  node_description text,
  allows_foreign_traits tinyint NOT NULL DEFAULT 1,
  node_contact varchar(255) DEFAULT NULL,
  meta_json json DEFAULT NULL,
  content_count bigint NOT NULL DEFAULT '0',
  atom_count bigint NOT NULL DEFAULT '0',
  hashtag_count bigint NOT NULL DEFAULT '0',
  actor_count bigint NOT NULL DEFAULT '0',
  last_sync_ymdhis bigint NOT NULL DEFAULT '0',
  trust_level tinyint NOT NULL DEFAULT '0',
  status tinyint NOT NULL DEFAULT '1',
  is_deleted tinyint NOT NULL DEFAULT '0',
  deleted_ymdhis bigint NOT NULL DEFAULT '0',
  created_ymdhis bigint NOT NULL DEFAULT '0',
  updated_ymdhis bigint NOT NULL DEFAULT '0',
  active_theme_slug varchar(64) DEFAULT 'default',
  PRIMARY KEY (federation_node_id)
);

CREATE INDEX {{prefix}}federation_nodes_idx_node_base_url ON {{prefix}}federation_nodes (node_base_url);
CREATE INDEX {{prefix}}federation_nodes_idx_status ON {{prefix}}federation_nodes (status);
CREATE INDEX {{prefix}}federation_nodes_idx_trust_level ON {{prefix}}federation_nodes (trust_level);
CREATE INDEX {{prefix}}federation_nodes_idx_is_deleted ON {{prefix}}federation_nodes (is_deleted);

-- 12-table install expansion v4.0.86: federated trust and discovery
CREATE TABLE {{prefix}}federated_trust (
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
CREATE INDEX {{prefix}}federated_trust_idx_trust_type ON {{prefix}}federated_trust (trust_type);
CREATE INDEX {{prefix}}federated_trust_idx_last_verified ON {{prefix}}federated_trust (last_verified_ymdhis);
CREATE INDEX {{prefix}}federated_trust_idx_is_deleted ON {{prefix}}federated_trust (is_deleted);
CREATE UNIQUE INDEX {{prefix}}federated_trust_idx_source_target ON {{prefix}}federated_trust (source_node_id, target_node_id);

CREATE TABLE {{prefix}}federation_discovery (
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
  import_collections tinyint NOT NULL DEFAULT 0,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL,
  PRIMARY KEY (federation_discovery_id)
);
CREATE INDEX {{prefix}}federation_discovery_idx_domain ON {{prefix}}federation_discovery (domain);

CREATE TABLE {{prefix}}governance_overrides (
  governance_overrid_id bigint NOT NULL,
  agent_id bigint DEFAULT NULL,
  applied_by_agent bigint DEFAULT NULL,
  override_type varchar(100) NOT NULL,
  target_key varchar(150) DEFAULT NULL,
  old_value text,
  new_value text,
  reason_text text,
  metadata_json json DEFAULT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  expires_ymdhis bigint DEFAULT NULL,
  is_deleted tinyint NOT NULL DEFAULT '0',
  deleted_ymdhis bigint DEFAULT NULL,
  PRIMARY KEY (governance_overrid_id)
);

CREATE INDEX {{prefix}}governance_overrides_idx_agent ON {{prefix}}governance_overrides (agent_id);
CREATE INDEX {{prefix}}governance_overrides_idx_applied_by ON {{prefix}}governance_overrides (applied_by_agent);
CREATE INDEX {{prefix}}governance_overrides_idx_type ON {{prefix}}governance_overrides (override_type);
CREATE INDEX {{prefix}}governance_overrides_idx_target ON {{prefix}}governance_overrides (target_key);
CREATE INDEX {{prefix}}governance_overrides_idx_created ON {{prefix}}governance_overrides (created_ymdhis);

CREATE TABLE {{prefix}}help_topics (
  help_topic_id bigint NOT NULL,
  slug varchar(255) NOT NULL,
  title varchar(255) NOT NULL,
  content_html text,
  content_markdown text,
  category varchar(100) DEFAULT NULL,
  parent_slug varchar(255) DEFAULT NULL,
  view_count bigint DEFAULT '0',
  helpful_count bigint DEFAULT '0',
  not_helpful_count bigint DEFAULT '0',
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL,
  author_actor_id bigint DEFAULT NULL,
  is_deleted tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (help_topic_id)
);

CREATE UNIQUE INDEX {{prefix}}help_topics_slug ON {{prefix}}help_topics (slug);
CREATE INDEX {{prefix}}help_topics_idx_slug ON {{prefix}}help_topics (slug);
CREATE INDEX {{prefix}}help_topics_idx_category ON {{prefix}}help_topics (category);
CREATE INDEX {{prefix}}help_topics_idx_parent ON {{prefix}}help_topics (parent_slug);
CREATE INDEX {{prefix}}help_topics_idx_created ON {{prefix}}help_topics (created_ymdhis);
CREATE INDEX {{prefix}}help_topics_idx_author ON {{prefix}}help_topics (author_actor_id);

CREATE TABLE {{prefix}}help_tree (
  help_tree_id bigint NOT NULL,
  parent_id bigint DEFAULT NULL,
  department_id bigint NOT NULL DEFAULT '1',
  content_id bigint DEFAULT NULL,
  title varchar(255) NOT NULL,
  description text,
  action_type varchar(64) NOT NULL DEFAULT 'none',
  action_target varchar(255) DEFAULT NULL,
  sort_order int NOT NULL DEFAULT '0',
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL,
  is_deleted tinyint NOT NULL DEFAULT '0',
  deleted_ymdhis bigint DEFAULT NULL,
  PRIMARY KEY (help_tree_id)
);

CREATE INDEX {{prefix}}help_tree_idx_parent ON {{prefix}}help_tree (parent_id);
CREATE INDEX {{prefix}}help_tree_idx_department ON {{prefix}}help_tree (department_id);
CREATE INDEX {{prefix}}help_tree_idx_content ON {{prefix}}help_tree (content_id);
CREATE INDEX {{prefix}}help_tree_idx_sort ON {{prefix}}help_tree (parent_id, sort_order);
CREATE INDEX {{prefix}}help_tree_idx_action ON {{prefix}}help_tree (action_type, action_target(191));
CREATE INDEX {{prefix}}help_tree_idx_created ON {{prefix}}help_tree (created_ymdhis);
CREATE INDEX {{prefix}}help_tree_idx_updated ON {{prefix}}help_tree (updated_ymdhis);

-- 12-table install expansion v4.0.86: documentation frameworks (LUPOPEDIA HEADERS alignment)
CREATE TABLE {{prefix}}documentation_frameworks (
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
CREATE UNIQUE INDEX {{prefix}}documentation_frameworks_uniq_key ON {{prefix}}documentation_frameworks (framework_key);
CREATE INDEX {{prefix}}documentation_frameworks_idx_namespace ON {{prefix}}documentation_frameworks (namespace_key);
CREATE INDEX {{prefix}}documentation_frameworks_idx_channel ON {{prefix}}documentation_frameworks (channel_id);
CREATE INDEX {{prefix}}documentation_frameworks_idx_collection ON {{prefix}}documentation_frameworks (collection_key);
CREATE INDEX {{prefix}}documentation_frameworks_idx_class ON {{prefix}}documentation_frameworks (class_type);
CREATE INDEX {{prefix}}documentation_frameworks_idx_is_deleted ON {{prefix}}documentation_frameworks (is_deleted);
CREATE INDEX {{prefix}}documentation_frameworks_idx_created ON {{prefix}}documentation_frameworks (created_ymdhis);

CREATE TABLE {{prefix}}interpretation_log (
  interpretation_log_id bigint NOT NULL,
  agent_id bigint NOT NULL,
  entity_type varchar(32) NOT NULL,
  entity_id bigint NOT NULL,
  interpretation text NOT NULL,
  confidence_score decimal(5,2) DEFAULT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL,
  is_deleted tinyint NOT NULL DEFAULT '0',
  deleted_ymdhis bigint DEFAULT NULL,
  metadata_json json DEFAULT NULL,
  PRIMARY KEY (interpretation_log_id)
);

CREATE INDEX {{prefix}}interpretation_log_idx_agent ON {{prefix}}interpretation_log (agent_id);
CREATE INDEX {{prefix}}interpretation_log_idx_entity ON {{prefix}}interpretation_log (entity_type, entity_id);
CREATE INDEX {{prefix}}interpretation_log_idx_confidence ON {{prefix}}interpretation_log (confidence_score);
CREATE INDEX {{prefix}}interpretation_log_idx_created ON {{prefix}}interpretation_log (created_ymdhis);
CREATE INDEX {{prefix}}interpretation_log_idx_updated ON {{prefix}}interpretation_log (updated_ymdhis);
CREATE INDEX {{prefix}}interpretation_log_idx_deleted ON {{prefix}}interpretation_log (is_deleted);

CREATE TABLE {{prefix}}labs_declarations (
  labs_declaration_id bigint NOT NULL,
  actor_id bigint NOT NULL,
  certificate_id varchar(64) NOT NULL,
  declaration_timestamp bigint NOT NULL,
  declarations_json json NOT NULL,
  validation_status varchar(64) NOT NULL DEFAULT 'valid',
  labs_version varchar(16) NOT NULL DEFAULT '1.0',
  next_revalidation_ymdhis bigint NOT NULL,
  validation_log_json json DEFAULT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL,
  is_deleted tinyint NOT NULL DEFAULT '0',
  deleted_ymdhis bigint DEFAULT NULL,
  PRIMARY KEY (labs_declaration_id)
);

CREATE INDEX {{prefix}}labs_declarations_idx_actor_id ON {{prefix}}labs_declarations (actor_id);
CREATE INDEX {{prefix}}labs_declarations_idx_certificate_id ON {{prefix}}labs_declarations (certificate_id);
CREATE INDEX {{prefix}}labs_declarations_idx_validation_status ON {{prefix}}labs_declarations (validation_status);
CREATE INDEX {{prefix}}labs_declarations_idx_next_revalidation ON {{prefix}}labs_declarations (next_revalidation_ymdhis);
CREATE INDEX {{prefix}}labs_declarations_idx_actor_status ON {{prefix}}labs_declarations (actor_id, validation_status, is_deleted);
CREATE INDEX {{prefix}}labs_declarations_idx_revalidation_due ON {{prefix}}labs_declarations (next_revalidation_ymdhis, validation_status, is_deleted);

CREATE TABLE {{prefix}}labs_violations (
  labs_violation_id bigint NOT NULL,
  actor_id bigint NOT NULL,
  certificate_id varchar(64) NOT NULL,
  violation_code varchar(64) NOT NULL,
  violation_description text,
  violation_metadata json DEFAULT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL,
  is_deleted tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (labs_violation_id)
);

CREATE INDEX {{prefix}}labs_violations_idx_actor ON {{prefix}}labs_violations (actor_id);
CREATE INDEX {{prefix}}labs_violations_idx_certificate ON {{prefix}}labs_violations (certificate_id);
CREATE INDEX {{prefix}}labs_violations_idx_violation_code ON {{prefix}}labs_violations (violation_code);
CREATE INDEX {{prefix}}labs_violations_idx_created ON {{prefix}}labs_violations (created_ymdhis);
CREATE INDEX {{prefix}}labs_violations_idx_deleted ON {{prefix}}labs_violations (is_deleted);

-- Old memory events table removed in v4.0.86 - consolidated into {{prefix}}unified_log

CREATE TABLE {{prefix}}memory_rollups (
  memory_rollup_id bigint NOT NULL,
  actor_id int NOT NULL,
  summary text NOT NULL,
  source_event_ids text NOT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  PRIMARY KEY (memory_rollup_id)
);

CREATE INDEX {{prefix}}memory_rollups_idx_actor_created ON {{prefix}}memory_rollups (actor_id, created_ymdhis);

-- Old meta log events table removed in v4.0.86 - consolidated into {{prefix}}unified_log

CREATE TABLE {{prefix}}modules (
  module_id bigint NOT NULL,
  module_key varchar(100) NOT NULL,
  module_name varchar(150) NOT NULL,
  namespace varchar(100) NOT NULL,
  version varchar(50) NOT NULL,
  version_code int NOT NULL,
  minimum_core_version varchar(50) NOT NULL,
  user_path varchar(255) DEFAULT NULL,
  admin_path varchar(255) DEFAULT NULL,
  api_path varchar(255) DEFAULT NULL,
  route_params text,
  description text,
  author varchar(100) DEFAULT NULL,
  website varchar(255) DEFAULT NULL,
  icon varchar(100) DEFAULT 'puzzle-piece',
  dependencies text,
  conflicts text,
  config_json text NOT NULL,
  is_system tinyint NOT NULL DEFAULT '0',
  is_active tinyint NOT NULL DEFAULT '0',
  federation_node_id bigint NOT NULL DEFAULT '1',
  settings text,
  installed_ymdhis bigint DEFAULT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint DEFAULT NULL,
  is_deleted tinyint NOT NULL DEFAULT '0',
  deleted_ymdhis bigint DEFAULT NULL,
  PRIMARY KEY (module_id)
);

CREATE UNIQUE INDEX {{prefix}}modules_uq_module_key ON {{prefix}}modules (module_key);
CREATE INDEX {{prefix}}modules_idx_namespace ON {{prefix}}modules (namespace);
CREATE INDEX {{prefix}}modules_idx_status ON {{prefix}}modules (is_active, is_deleted);
CREATE INDEX {{prefix}}modules_idx_system ON {{prefix}}modules (is_system);
CREATE INDEX {{prefix}}modules_idx_installed ON {{prefix}}modules (installed_ymdhis);

CREATE TABLE {{prefix}}notifications (
  notification_id bigint NOT NULL,
  actor_id bigint NOT NULL,
  from_actor_id bigint DEFAULT NULL,
  to_actor_id bigint DEFAULT NULL,
  channel_id bigint DEFAULT NULL,
  notification_type varchar(64) NOT NULL,
  title varchar(255) DEFAULT NULL,
  message text,
  link_url varchar(255) DEFAULT NULL,
  is_read tinyint NOT NULL DEFAULT '0',
  is_deleted tinyint NOT NULL DEFAULT '0',
  created_ymdhis bigint NOT NULL DEFAULT 0,
  PRIMARY KEY (notification_id)
);


CREATE TABLE {{prefix}}permissions (
  permission_id bigint NOT NULL,
  target_type varchar(64) NOT NULL,
  target_id bigint NOT NULL,
  user_id bigint DEFAULT NULL,
  department_id bigint DEFAULT NULL,
  permission varchar(64) NOT NULL DEFAULT 'read',
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint DEFAULT NULL,
  is_deleted tinyint NOT NULL DEFAULT '0',
  deleted_ymdhis bigint DEFAULT NULL,
  PRIMARY KEY (permission_id)
);

CREATE UNIQUE INDEX {{prefix}}permissions_uniq_target_user ON {{prefix}}permissions (target_type, target_id, user_id);
CREATE UNIQUE INDEX {{prefix}}permissions_uniq_target_department ON {{prefix}}permissions (target_type, target_id, department_id);
CREATE INDEX {{prefix}}permissions_idx_target ON {{prefix}}permissions (target_type, target_id);
CREATE INDEX {{prefix}}permissions_idx_user ON {{prefix}}permissions (user_id);
CREATE INDEX {{prefix}}permissions_idx_department ON {{prefix}}permissions (department_id);
CREATE INDEX {{prefix}}permissions_idx_deleted ON {{prefix}}permissions (is_deleted, deleted_ymdhis);
CREATE INDEX {{prefix}}permissions_idx_permission ON {{prefix}}permissions (permission);
CREATE INDEX {{prefix}}permissions_idx_created_ymdhis ON {{prefix}}permissions (created_ymdhis);

CREATE TABLE {{prefix}}search_rebuild_log (
  search_rebuild_log_id bigint NOT NULL,
  entity_type varchar(50) NOT NULL,
  entity_id bigint NOT NULL,
  action varchar(64) NOT NULL,
  status varchar(64) NOT NULL DEFAULT 'pending',
  attempts tinyint NOT NULL DEFAULT '0',
  last_error text,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  processed_ymdhis bigint DEFAULT NULL,
  next_attempt_ymdhis bigint DEFAULT NULL,
  is_deleted tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (search_rebuild_log_id)
);

CREATE UNIQUE INDEX {{prefix}}search_rebuild_log_unique_entity_operation ON {{prefix}}search_rebuild_log (entity_type, entity_id, action);
CREATE INDEX {{prefix}}search_rebuild_log_idx_status_retry ON {{prefix}}search_rebuild_log (status, next_attempt_ymdhis);
CREATE INDEX {{prefix}}search_rebuild_log_idx_created ON {{prefix}}search_rebuild_log (created_ymdhis);
CREATE INDEX {{prefix}}search_rebuild_log_idx_entity ON {{prefix}}search_rebuild_log (entity_type, entity_id);

-- Consolidated semantic index table replacing 7 semantic tables
CREATE TABLE {{prefix}}semantic_index (
  semantic_id bigint NOT NULL,
  semantic_type varchar(32) NOT NULL,
  slug varchar(255) DEFAULT NULL,
  name varchar(255) DEFAULT NULL,
  title varchar(255) DEFAULT NULL,
  description text,
  parent_id bigint DEFAULT NULL,
  sort_order int DEFAULT 0,
  weight float DEFAULT 0,
  relationship_strength decimal(3,2) DEFAULT 1.00,
  layer varchar(64) DEFAULT NULL,
  timeframe varchar(64) DEFAULT NULL,
  language_code varchar(8) DEFAULT NULL,
  color varchar(7) DEFAULT '#666666',
  template_path varchar(512) DEFAULT NULL,
  json_data json DEFAULT NULL,
  text_value text,
  source_content_id bigint DEFAULT NULL,
  target_content_id bigint DEFAULT NULL,
  source_page_id bigint DEFAULT NULL,
  target_page_id bigint DEFAULT NULL,
  entity_type varchar(32) DEFAULT NULL,
  entity_id bigint DEFAULT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL,
  is_active tinyint NOT NULL DEFAULT '1',
  is_default tinyint NOT NULL DEFAULT '0',
  is_deleted tinyint NOT NULL DEFAULT '0',
  deleted_ymdhis bigint DEFAULT NULL,
  created_by bigint DEFAULT NULL,
  PRIMARY KEY (semantic_id)
);

CREATE UNIQUE INDEX {{prefix}}semantic_index_uk_type_slug ON {{prefix}}semantic_index (semantic_type, slug);
CREATE INDEX {{prefix}}semantic_index_idx_type ON {{prefix}}semantic_index (semantic_type);
CREATE INDEX {{prefix}}semantic_index_idx_parent ON {{prefix}}semantic_index (parent_id);
CREATE INDEX {{prefix}}semantic_index_idx_source_content ON {{prefix}}semantic_index (source_content_id);
CREATE INDEX {{prefix}}semantic_index_idx_target_content ON {{prefix}}semantic_index (target_content_id);
CREATE INDEX {{prefix}}semantic_index_idx_source_page ON {{prefix}}semantic_index (source_page_id);
CREATE INDEX {{prefix}}semantic_index_idx_target_page ON {{prefix}}semantic_index (target_page_id);
CREATE INDEX {{prefix}}semantic_index_idx_entity ON {{prefix}}semantic_index (entity_type, entity_id);
CREATE INDEX {{prefix}}semantic_index_idx_language ON {{prefix}}semantic_index (language_code);
CREATE INDEX {{prefix}}semantic_index_idx_layer ON {{prefix}}semantic_index (layer);
CREATE INDEX {{prefix}}semantic_index_idx_timeframe ON {{prefix}}semantic_index (timeframe);
CREATE INDEX {{prefix}}semantic_index_idx_created_ymdhis ON {{prefix}}semantic_index (created_ymdhis, is_active, is_deleted);
CREATE INDEX {{prefix}}semantic_index_idx_updated_ymdhis ON {{prefix}}semantic_index (updated_ymdhis);
CREATE INDEX {{prefix}}semantic_index_idx_is_active ON {{prefix}}semantic_index (is_active);
CREATE INDEX {{prefix}}semantic_index_idx_is_default ON {{prefix}}semantic_index (is_default);
CREATE INDEX {{prefix}}semantic_index_idx_is_deleted ON {{prefix}}semantic_index (is_deleted);









-- Model A: DB-backed session authority. Browser stores only session_id; identity from DB. No session payload, no JWT.
-- Columns is_active, is_expired, is_revoked, is_deleted, last_seen_ymdhis, expires_ymdhis, security_level, system_context, status required by ai_activation, session_helpers, livehelp_js, image.php.
CREATE TABLE {{prefix}}sessions (
  session_id varchar(128) NOT NULL,
  actor_id bigint NOT NULL,
  actor_name varchar(64) DEFAULT NULL,
  federation_node_id bigint NOT NULL DEFAULT 0,
  ip_hash varchar(128) DEFAULT NULL,
  ua_hash varchar(255) DEFAULT NULL,
  csrf_token varchar(128) DEFAULT NULL,
  last_activity_ymdhis bigint NOT NULL,
  created_ymdhis bigint NOT NULL,
  updated_ymdhis bigint NOT NULL,
  name_key varchar(100) DEFAULT NULL,
  is_named tinyint NOT NULL DEFAULT 0,
  metadata json DEFAULT NULL,
  is_active tinyint NOT NULL DEFAULT 1,
  is_expired tinyint NOT NULL DEFAULT 0,
  is_revoked tinyint NOT NULL DEFAULT 0,
  is_deleted tinyint NOT NULL DEFAULT 0,
  last_seen_ymdhis bigint DEFAULT NULL,
  expires_ymdhis bigint DEFAULT NULL,
  security_level varchar(64) DEFAULT NULL,
  system_context varchar(64) DEFAULT NULL,
  status varchar(32) DEFAULT NULL,
  PRIMARY KEY (session_id)
);
CREATE INDEX {{prefix}}sessions_idx_actor ON {{prefix}}sessions (actor_id);
CREATE INDEX {{prefix}}sessions_idx_actor_name ON {{prefix}}sessions (actor_name);
CREATE INDEX {{prefix}}sessions_idx_last_activity ON {{prefix}}sessions (last_activity_ymdhis);
CREATE INDEX {{prefix}}sessions_idx_federation ON {{prefix}}sessions (federation_node_id);
CREATE INDEX {{prefix}}sessions_idx_is_active ON {{prefix}}sessions (is_active);
CREATE INDEX {{prefix}}sessions_idx_last_seen ON {{prefix}}sessions (last_seen_ymdhis);

-- Old session events table removed in v4.0.86 - consolidated into {{prefix}}unified_log

CREATE TABLE {{prefix}}system_config (
  system_config_id bigint NOT NULL,
  config_key varchar(255) NOT NULL,
  config_value text NOT NULL,
  actor_id bigint NOT NULL DEFAULT '0',
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL,
  PRIMARY KEY (system_config_id)
);

CREATE UNIQUE INDEX {{prefix}}system_config_config_key ON {{prefix}}system_config (config_key);

-- 12-table install expansion v4.0.86: system health snapshots and hotfix registry
CREATE TABLE {{prefix}}system_health_snapshots (
  snapshot_id bigint NOT NULL ,
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
CREATE INDEX {{prefix}}system_health_snapshots_idx_created ON {{prefix}}system_health_snapshots (created_ymdhis);
CREATE INDEX {{prefix}}system_health_snapshots_idx_type ON {{prefix}}system_health_snapshots (snapshot_type);
CREATE INDEX {{prefix}}system_health_snapshots_idx_is_deleted ON {{prefix}}system_health_snapshots (is_deleted);


-- Old logging tables removed in v4.0.86 - consolidated into {{prefix}}unified_log

CREATE TABLE {{prefix}}system_commands (
  command_id bigint NOT NULL,
  command_type varchar(128) NOT NULL,
  command_args_json text,
  working_dir varchar(512) DEFAULT NULL,
  status varchar(32) NOT NULL,
  priority int NOT NULL DEFAULT 0,
  created_ymdhis bigint NOT NULL,
  scheduled_ymdhis bigint NOT NULL,
  started_ymdhis bigint DEFAULT NULL,
  finished_ymdhis bigint DEFAULT NULL,
  claimed_by_actor_id bigint DEFAULT NULL,
  claimed_by_host varchar(256) DEFAULT NULL,
  process_id varchar(64) DEFAULT NULL,
  attempt_count int NOT NULL DEFAULT 0,
  max_attempts int NOT NULL DEFAULT 3,
  timeout_seconds int NOT NULL DEFAULT 3600,
  return_code int DEFAULT NULL,
  output_text text,
  output_sha1 varchar(64) DEFAULT NULL,
  last_heartbeat_ymdhis bigint DEFAULT NULL,
  is_deleted tinyint NOT NULL DEFAULT 0,
  deleted_ymdhis bigint DEFAULT NULL,
  PRIMARY KEY (command_id)
);

CREATE INDEX {{prefix}}system_commands_idx_status_priority_scheduled ON {{prefix}}system_commands (status, priority, scheduled_ymdhis);
CREATE INDEX {{prefix}}system_commands_idx_status_heartbeat ON {{prefix}}system_commands (status, last_heartbeat_ymdhis);
CREATE INDEX {{prefix}}system_commands_idx_created_ymdhis ON {{prefix}}system_commands (created_ymdhis);
CREATE INDEX {{prefix}}system_commands_idx_is_deleted ON {{prefix}}system_commands (is_deleted);

-- Old tab events table removed in v4.0.86 - consolidated into {{prefix}}unified_log

-- {{prefix}}temporal_coherence_snapshots moved to future_features_lupopedia.sql (unused at runtime)

-- {{prefix}}tldnr moved to future_features_lupopedia.sql (v4.0.86)

-- Canonical Option A: Engagement Votes Table
CREATE TABLE {{prefix}}votes (
  vote_id bigint NOT NULL,
  object_type varchar(64) NOT NULL,
  object_id bigint NOT NULL,
  actor_id bigint NOT NULL,
  vote_value tinyint NOT NULL, -- 1=like, -1=dislike, 0=neutral/clear
  vote_weight float DEFAULT 1.0,
  created_ymdhis bigint NOT NULL,
  updated_ymdhis bigint NOT NULL,
  is_deleted tinyint NOT NULL DEFAULT 0,
  deleted_ymdhis bigint DEFAULT NULL,
  PRIMARY KEY (vote_id),
  UNIQUE KEY uq_vote_object_actor (object_type, object_id, actor_id),
  INDEX idx_vote_object (object_type, object_id),
  INDEX idx_vote_actor (actor_id),
  INDEX idx_vote_value (vote_value),
  INDEX idx_vote_created (created_ymdhis),
  INDEX idx_vote_is_deleted (is_deleted)
);

-- Legacy truth answers table for Crafty Syntax import compatibility


-- ============================================================
-- TRUTH MANAGEMENT SYSTEM (Option A - Constitutional)
-- Split tables for questions, answers, evidence, and context mapping
-- Replaces deprecated {{prefix}}truth_knowledge and {{prefix}}truth_answers
-- ============================================================

-- TABLE 1: Truth Questions
CREATE TABLE {{prefix}}truth_questions (
  truth_question_id bigint NOT NULL,
  parent_question_id bigint DEFAULT NULL,
  root_question_id bigint DEFAULT NULL,
  depth tinyint NOT NULL DEFAULT 0,
  target_object_type varchar(64) NOT NULL,
  target_object_id bigint NOT NULL,
  question_text text NOT NULL,
  question_summary varchar(500) DEFAULT NULL,
  asked_by_actor_id bigint NOT NULL,
  asked_in_channel_id bigint DEFAULT NULL,
  asked_in_thread_id bigint DEFAULT NULL,
  asked_in_session_id varchar(128) DEFAULT NULL,
  question_status varchar(32) NOT NULL DEFAULT 'open',
  is_answered tinyint NOT NULL DEFAULT 0,
  is_featured tinyint NOT NULL DEFAULT 0,
  view_count bigint NOT NULL DEFAULT 0,
  answer_count bigint NOT NULL DEFAULT 0,
  follower_count bigint NOT NULL DEFAULT 0,
  created_ymdhis bigint NOT NULL,
  updated_ymdhis bigint NOT NULL,
  answered_ymdhis bigint DEFAULT NULL,
  closed_ymdhis bigint DEFAULT NULL,
  is_deleted tinyint NOT NULL DEFAULT 0,
  deleted_ymdhis bigint DEFAULT NULL,
  metadata_json json DEFAULT NULL,
  PRIMARY KEY (truth_question_id)
);
CREATE INDEX {{prefix}}truth_questions_idx_parent ON {{prefix}}truth_questions (parent_question_id);
CREATE INDEX {{prefix}}truth_questions_idx_root ON {{prefix}}truth_questions (root_question_id);
CREATE INDEX {{prefix}}truth_questions_idx_target ON {{prefix}}truth_questions (target_object_type, target_object_id);
CREATE INDEX {{prefix}}truth_questions_idx_asked_by ON {{prefix}}truth_questions (asked_by_actor_id);
CREATE INDEX {{prefix}}truth_questions_idx_channel ON {{prefix}}truth_questions (asked_in_channel_id);
CREATE INDEX {{prefix}}truth_questions_idx_thread ON {{prefix}}truth_questions (asked_in_thread_id);
CREATE INDEX {{prefix}}truth_questions_idx_status ON {{prefix}}truth_questions (question_status, is_answered);
CREATE INDEX {{prefix}}truth_questions_idx_created ON {{prefix}}truth_questions (created_ymdhis);
CREATE INDEX {{prefix}}truth_questions_idx_featured ON {{prefix}}truth_questions (is_featured);
CREATE INDEX {{prefix}}truth_questions_idx_deleted ON {{prefix}}truth_questions (is_deleted);

-- TABLE 2: Truth Answers
CREATE TABLE {{prefix}}truth_answers (
  truth_answer_id bigint NOT NULL,
  truth_question_id bigint NOT NULL,
  answer_text text NOT NULL,
  answer_summary varchar(500) DEFAULT NULL,
  answered_by_actor_id bigint NOT NULL,
  answered_in_channel_id bigint DEFAULT NULL,
  answered_in_thread_id bigint DEFAULT NULL,
  answered_in_message_id bigint DEFAULT NULL,
  is_accepted tinyint NOT NULL DEFAULT 0,
  acceptance_votes int NOT NULL DEFAULT 0,
  rejection_votes int NOT NULL DEFAULT 0,
  confidence_score decimal(3,2) NOT NULL DEFAULT 0.50,
  answer_status varchar(32) NOT NULL DEFAULT 'active',
  view_count bigint NOT NULL DEFAULT 0,
  helpful_count bigint NOT NULL DEFAULT 0,
  created_ymdhis bigint NOT NULL,
  updated_ymdhis bigint NOT NULL,
  accepted_ymdhis bigint DEFAULT NULL,
  is_deleted tinyint NOT NULL DEFAULT 0,
  deleted_ymdhis bigint DEFAULT NULL,
  metadata_json json DEFAULT NULL,
  PRIMARY KEY (truth_answer_id)
);
CREATE INDEX {{prefix}}truth_answers_idx_question ON {{prefix}}truth_answers (truth_question_id);
CREATE INDEX {{prefix}}truth_answers_idx_answered_by ON {{prefix}}truth_answers (answered_by_actor_id);
CREATE INDEX {{prefix}}truth_answers_idx_channel ON {{prefix}}truth_answers (answered_in_channel_id);
CREATE INDEX {{prefix}}truth_answers_idx_thread ON {{prefix}}truth_answers (answered_in_thread_id);
CREATE INDEX {{prefix}}truth_answers_idx_accepted ON {{prefix}}truth_answers (is_accepted, acceptance_votes);
CREATE INDEX {{prefix}}truth_answers_idx_confidence ON {{prefix}}truth_answers (confidence_score);
CREATE INDEX {{prefix}}truth_answers_idx_status ON {{prefix}}truth_answers (answer_status);
CREATE INDEX {{prefix}}truth_answers_idx_created ON {{prefix}}truth_answers (created_ymdhis);
CREATE INDEX {{prefix}}truth_answers_idx_deleted ON {{prefix}}truth_answers (is_deleted);

-- TABLE 3: Truth Evidence
CREATE TABLE {{prefix}}truth_evidence (
  truth_evidence_id bigint NOT NULL,
  truth_answer_id bigint NOT NULL,
  evidence_type varchar(64) NOT NULL,
  source_object_type varchar(64) NOT NULL,
  source_object_id bigint NOT NULL,
  source_federation_node_id bigint DEFAULT NULL,
  source_url varchar(2000) DEFAULT NULL,
  source_title varchar(500) DEFAULT NULL,
  evidence_text text,
  evidence_excerpt varchar(1000) DEFAULT NULL,
  reliability_score decimal(3,2) NOT NULL DEFAULT 0.50,
  relevance_score decimal(3,2) NOT NULL DEFAULT 0.50,
  is_verified tinyint NOT NULL DEFAULT 0,
  verified_by_actor_id bigint DEFAULT NULL,
  verified_ymdhis bigint DEFAULT NULL,
  verification_notes text DEFAULT NULL,
  submitted_by_actor_id bigint NOT NULL,
  created_ymdhis bigint NOT NULL,
  updated_ymdhis bigint NOT NULL,
  is_deleted tinyint NOT NULL DEFAULT 0,
  deleted_ymdhis bigint DEFAULT NULL,
  PRIMARY KEY (truth_evidence_id)
);
CREATE INDEX {{prefix}}truth_evidence_idx_answer ON {{prefix}}truth_evidence (truth_answer_id);
CREATE INDEX {{prefix}}truth_evidence_idx_source ON {{prefix}}truth_evidence (source_object_type, source_object_id);
CREATE INDEX {{prefix}}truth_evidence_idx_federation ON {{prefix}}truth_evidence (source_federation_node_id);
CREATE INDEX {{prefix}}truth_evidence_idx_verified ON {{prefix}}truth_evidence (is_verified, reliability_score);
CREATE INDEX {{prefix}}truth_evidence_idx_evidence_type ON {{prefix}}truth_evidence (evidence_type);
CREATE INDEX {{prefix}}truth_evidence_idx_submitted_by ON {{prefix}}truth_evidence (submitted_by_actor_id);
CREATE INDEX {{prefix}}truth_evidence_idx_created ON {{prefix}}truth_evidence (created_ymdhis);
CREATE INDEX {{prefix}}truth_evidence_idx_deleted ON {{prefix}}truth_evidence (is_deleted);

-- TABLE 4: Truth Context Mapping
CREATE TABLE {{prefix}}truth_context_map (
  truth_context_map_id bigint NOT NULL,
  truth_question_id bigint NOT NULL,
  context_id bigint DEFAULT NULL,
  collection_id bigint DEFAULT NULL,
  context_card_id bigint DEFAULT NULL,
  sort_order int NOT NULL DEFAULT 0,
  mapping_reason varchar(255) DEFAULT NULL,
  added_by_actor_id bigint NOT NULL,
  created_ymdhis bigint NOT NULL,
  updated_ymdhis bigint NOT NULL,
  is_deleted tinyint NOT NULL DEFAULT 0,
  deleted_ymdhis bigint DEFAULT NULL,
  PRIMARY KEY (truth_context_map_id)
);
CREATE INDEX {{prefix}}truth_context_map_idx_question ON {{prefix}}truth_context_map (truth_question_id);
CREATE INDEX {{prefix}}truth_context_map_idx_context ON {{prefix}}truth_context_map (context_id);
CREATE INDEX {{prefix}}truth_context_map_idx_collection ON {{prefix}}truth_context_map (collection_id);
CREATE INDEX {{prefix}}truth_context_map_idx_card ON {{prefix}}truth_context_map (context_card_id);
CREATE INDEX {{prefix}}truth_context_map_idx_added_by ON {{prefix}}truth_context_map (added_by_actor_id);
CREATE INDEX {{prefix}}truth_context_map_idx_created ON {{prefix}}truth_context_map (created_ymdhis);
CREATE INDEX {{prefix}}truth_context_map_idx_deleted ON {{prefix}}truth_context_map (is_deleted);

-- TABLE 5: Truth Question Followers
CREATE TABLE {{prefix}}truth_followers (
  truth_follower_id bigint NOT NULL,
  truth_question_id bigint NOT NULL,
  actor_id bigint NOT NULL,
  notification_enabled tinyint NOT NULL DEFAULT 1,
  created_ymdhis bigint NOT NULL,
  updated_ymdhis bigint NOT NULL,
  is_deleted tinyint NOT NULL DEFAULT 0,
  deleted_ymdhis bigint DEFAULT NULL,
  PRIMARY KEY (truth_follower_id)
);
CREATE UNIQUE INDEX {{prefix}}truth_followers_unq_question_actor ON {{prefix}}truth_followers (truth_question_id, actor_id);
CREATE INDEX {{prefix}}truth_followers_idx_question ON {{prefix}}truth_followers (truth_question_id);
CREATE INDEX {{prefix}}truth_followers_idx_actor ON {{prefix}}truth_followers (actor_id);
CREATE INDEX {{prefix}}truth_followers_idx_deleted ON {{prefix}}truth_followers (is_deleted);

-- {{prefix}}paths: aggregated navigation flows (low-volume). Populated by gc.php from {{prefix}}visits. year_num/month_num/day_num for partitioning.
CREATE TABLE {{prefix}}paths (
  path_id bigint NOT NULL ,
  entercontentid bigint DEFAULT NULL,
  exitcontentid bigint DEFAULT NULL,
  enter_table varchar(255) DEFAULT NULL,
  exit_table varchar(255) DEFAULT NULL,
  year_num int DEFAULT NULL,
  month_num int DEFAULT NULL,
  day_num int DEFAULT NULL,
  count_num int NOT NULL DEFAULT 0,
  transition_type varchar(64) DEFAULT NULL,
  transition_metadata text,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL DEFAULT 0,
  is_deleted tinyint NOT NULL DEFAULT 0,
  deleted_ymdhis bigint DEFAULT NULL,
  PRIMARY KEY (path_id)
);
CREATE INDEX {{prefix}}paths_idx_enter_exit ON {{prefix}}paths (entercontentid, exitcontentid);
CREATE INDEX {{prefix}}paths_idx_ymd ON {{prefix}}paths (year_num, month_num, day_num);
CREATE INDEX {{prefix}}paths_idx_transition ON {{prefix}}paths (transition_type);
CREATE INDEX {{prefix}}paths_idx_created ON {{prefix}}paths (created_ymdhis);
CREATE INDEX {{prefix}}paths_idx_is_deleted ON {{prefix}}paths (is_deleted);

-- ============================================================
-- SEMANTIC NAVBAR BACKEND (4.0.86): references, hashtags, folders
-- No FKs; application enforces relations. BIGINT timestamps only.
-- ============================================================
CREATE TABLE {{prefix}}references (
  reference_id bigint NOT NULL ,
  source_entity_type varchar(64) NOT NULL,
  source_entity_id bigint NOT NULL,
  url varchar(2000) DEFAULT NULL,
  title varchar(500) DEFAULT NULL,
  citation_text text,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL DEFAULT 0,
  is_deleted tinyint NOT NULL DEFAULT 0,
  deleted_ymdhis bigint DEFAULT NULL,
  PRIMARY KEY (reference_id)
);
CREATE INDEX {{prefix}}references_idx_source ON {{prefix}}references (source_entity_type, source_entity_id);
CREATE INDEX {{prefix}}references_idx_created ON {{prefix}}references (created_ymdhis);
CREATE INDEX {{prefix}}references_idx_is_deleted ON {{prefix}}references (is_deleted);

CREATE TABLE {{prefix}}reference_links (
  reference_link_id bigint NOT NULL ,
  reference_id bigint NOT NULL,
  object_type varchar(64) NOT NULL,
  object_id bigint NOT NULL,
  sort_order int NOT NULL DEFAULT 0,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  is_deleted tinyint NOT NULL DEFAULT 0,
  deleted_ymdhis bigint DEFAULT NULL,
  PRIMARY KEY (reference_link_id)
);
CREATE INDEX {{prefix}}reference_links_idx_reference ON {{prefix}}reference_links (reference_id);
CREATE INDEX {{prefix}}reference_links_idx_object ON {{prefix}}reference_links (object_type, object_id);
CREATE INDEX {{prefix}}reference_links_idx_is_deleted ON {{prefix}}reference_links (is_deleted);

CREATE TABLE {{prefix}}hashtags (
  hashtag_id bigint NOT NULL ,
  tag_slug varchar(128) NOT NULL,
  label varchar(255) DEFAULT NULL,
  use_count bigint NOT NULL DEFAULT 0,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL DEFAULT 0,
  is_deleted tinyint NOT NULL DEFAULT 0,
  deleted_ymdhis bigint DEFAULT NULL,
  PRIMARY KEY (hashtag_id)
);
CREATE UNIQUE INDEX {{prefix}}hashtags_uniq_slug ON {{prefix}}hashtags (tag_slug);
CREATE INDEX {{prefix}}hashtags_idx_use_count ON {{prefix}}hashtags (use_count);
CREATE INDEX {{prefix}}hashtags_idx_is_deleted ON {{prefix}}hashtags (is_deleted);

CREATE TABLE {{prefix}}hashtag_map (
  hashtag_map_id bigint NOT NULL ,
  hashtag_id bigint NOT NULL,
  object_type varchar(64) NOT NULL,
  object_id bigint NOT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  is_deleted tinyint NOT NULL DEFAULT 0,
  deleted_ymdhis bigint DEFAULT NULL,
  PRIMARY KEY (hashtag_map_id)
);
CREATE INDEX {{prefix}}hashtag_map_idx_hashtag ON {{prefix}}hashtag_map (hashtag_id);
CREATE INDEX {{prefix}}hashtag_map_idx_object ON {{prefix}}hashtag_map (object_type, object_id);
CREATE INDEX {{prefix}}hashtag_map_idx_is_deleted ON {{prefix}}hashtag_map (is_deleted);



-- lupo_folders: folder entities for folder-based grouping in the semantic navbar.
-- Hierarchy via parent_folder_id (self-referential, no DB FK).
CREATE TABLE {{prefix}}folders (
  folder_id bigint NOT NULL,
  name varchar(255) NOT NULL,
  slug varchar(128) NOT NULL,
  parent_folder_id bigint DEFAULT NULL,
  actor_id bigint DEFAULT NULL,
  channel_id bigint DEFAULT NULL,
  sort_order int NOT NULL DEFAULT 0,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL DEFAULT 0,
  is_deleted tinyint NOT NULL DEFAULT 0,
  deleted_ymdhis bigint NOT NULL DEFAULT 0,
  PRIMARY KEY (folder_id)
);
CREATE INDEX {{prefix}}folders_idx_parent ON {{prefix}}folders (parent_folder_id);
CREATE INDEX {{prefix}}folders_idx_actor ON {{prefix}}folders (actor_id);
CREATE INDEX {{prefix}}folders_idx_channel ON {{prefix}}folders (channel_id);
CREATE INDEX {{prefix}}folders_idx_slug ON {{prefix}}folders (slug);
CREATE INDEX {{prefix}}folders_idx_is_deleted ON {{prefix}}folders (is_deleted);

-- Canonical: Folders to Object Map (semantic navbar, 4.0.71+)
CREATE TABLE {{prefix}}folder_map (
  folder_map_id bigint NOT NULL,
  folder_id bigint NOT NULL,
  object_type varchar(64) NOT NULL,
  object_id bigint NOT NULL,
  sort_order int NOT NULL DEFAULT 0,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  is_deleted tinyint NOT NULL DEFAULT 0,
  deleted_ymdhis bigint DEFAULT NULL,
  PRIMARY KEY (folder_map_id)
);
CREATE INDEX {{prefix}}folder_map_idx_folder ON {{prefix}}folder_map (folder_id);
CREATE INDEX {{prefix}}folder_map_idx_is_deleted ON {{prefix}}folder_map (is_deleted);
CREATE INDEX {{prefix}}folder_map_idx_object ON {{prefix}}folder_map (object_type, object_id);

-- Previous Pages Summary (4.0.86)
CREATE TABLE {{prefix}}paths_summary (
  summary_id bigint NOT NULL ,
  path_id bigint NOT NULL,
  total_count bigint NOT NULL DEFAULT 0,
  last_used_ymdhis bigint NOT NULL DEFAULT 0,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL DEFAULT 0,
  PRIMARY KEY (summary_id)
);
CREATE INDEX {{prefix}}paths_summary_idx_path ON {{prefix}}paths_summary (path_id);

-- Reference Map (Explicit mapping table, 4.0.86)
CREATE TABLE {{prefix}}reference_map (
  reference_map_id bigint NOT NULL ,
  reference_id bigint NOT NULL,
  target_type varchar(64) NOT NULL,
  target_id bigint NOT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  is_deleted tinyint NOT NULL DEFAULT 0,
  PRIMARY KEY (reference_map_id)
);
CREATE INDEX {{prefix}}reference_map_idx_reference ON {{prefix}}reference_map (reference_id);
CREATE INDEX {{prefix}}reference_map_idx_target ON {{prefix}}reference_map (target_type, target_id);

-- Collection Links (Explicit link objects within collections, 4.0.86)
CREATE TABLE {{prefix}}collection_links (
  collection_link_id bigint NOT NULL ,
  collection_id bigint NOT NULL,
  link_url varchar(2000) NOT NULL,
  link_label varchar(255) DEFAULT NULL,
  sort_order int NOT NULL DEFAULT 0,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL DEFAULT 0,
  is_deleted tinyint NOT NULL DEFAULT 0,
  PRIMARY KEY (collection_link_id)
);
CREATE INDEX {{prefix}}collection_links_idx_collection ON {{prefix}}collection_links (collection_id);

-- Collection Map (Mapping collections to multiple objects, 4.0.86)
CREATE TABLE {{prefix}}collection_map (
  collection_map_id bigint NOT NULL ,
  collection_id bigint NOT NULL,
  object_type varchar(64) NOT NULL,
  object_id bigint NOT NULL,
  sort_order int NOT NULL DEFAULT 0,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  is_deleted tinyint NOT NULL DEFAULT 0,
  PRIMARY KEY (collection_map_id)
);
CREATE INDEX {{prefix}}collection_map_idx_collection ON {{prefix}}collection_map (collection_id);
CREATE INDEX {{prefix}}collection_map_idx_object ON {{prefix}}collection_map (object_type, object_id);

-- Edge Types (Definitions for semantic edges, 4.0.86)
CREATE TABLE {{prefix}}edge_types (
  edge_type_id bigint NOT NULL ,
  slug varchar(64) NOT NULL,
  label varchar(128) NOT NULL,
  description text,
  is_bidirectional tinyint NOT NULL DEFAULT 0,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL DEFAULT 0,
  is_deleted tinyint NOT NULL DEFAULT 0,
  PRIMARY KEY (edge_type_id)
);
CREATE UNIQUE INDEX {{prefix}}edge_types_uniq_slug ON {{prefix}}edge_types (slug);

-- Edge Map (Mapping edges between objects, 4.0.86)
CREATE TABLE {{prefix}}edge_map (
  edge_map_id bigint NOT NULL ,
  edge_id bigint NOT NULL,
  edge_type_id bigint NOT NULL,
  source_type varchar(64) NOT NULL,
  source_id bigint NOT NULL,
  target_type varchar(64) NOT NULL,
  target_id bigint NOT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  is_deleted tinyint NOT NULL DEFAULT 0,
  PRIMARY KEY (edge_map_id)
);
CREATE INDEX {{prefix}}edge_map_idx_edge ON {{prefix}}edge_map (edge_id);
CREATE INDEX {{prefix}}edge_map_idx_type ON {{prefix}}edge_map (edge_type_id);
CREATE INDEX {{prefix}}edge_map_idx_source ON {{prefix}}edge_map (source_type, source_id);
CREATE INDEX {{prefix}}edge_map_idx_target ON {{prefix}}edge_map (target_type, target_id);

-- Questions (Semantic Q/A, 4.0.86)
CREATE TABLE {{prefix}}questions (
  question_id bigint NOT NULL ,
  slug varchar(128) NOT NULL,
  question_text text NOT NULL,
  actor_id bigint DEFAULT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL DEFAULT 0,
  is_deleted tinyint NOT NULL DEFAULT 0,
  PRIMARY KEY (question_id)
);
CREATE UNIQUE INDEX {{prefix}}questions_uniq_slug ON {{prefix}}questions (slug);

-- Answers (Semantic Q/A, 4.0.86)
CREATE TABLE {{prefix}}answers (
  answer_id bigint NOT NULL ,
  question_id bigint NOT NULL,
  answer_text text NOT NULL,
  actor_id bigint DEFAULT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL DEFAULT 0,
  is_deleted tinyint NOT NULL DEFAULT 0,
  PRIMARY KEY (answer_id)
);
CREATE INDEX {{prefix}}answers_idx_question ON {{prefix}}answers (question_id);

-- Question Map (Mapping questions to objects/contexts, 4.0.86)
CREATE TABLE {{prefix}}question_map (
  question_map_id bigint NOT NULL ,
  question_id bigint NOT NULL,
  object_type varchar(64) NOT NULL,
  object_id bigint NOT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  is_deleted tinyint NOT NULL DEFAULT 0,
  PRIMARY KEY (question_map_id)
);
CREATE INDEX {{prefix}}question_map_idx_question ON {{prefix}}question_map (question_id);
CREATE INDEX {{prefix}}question_map_idx_object ON {{prefix}}question_map (object_type, object_id);

-- ============================================================

CREATE TABLE {{prefix}}referers (
  referer_id bigint NOT NULL,
  content_id bigint NOT NULL,
  actor_id bigint NOT NULL,
  referer_url varchar(2000) DEFAULT NULL,
  referer_domain varchar(255) DEFAULT NULL,
  referer_path varchar(2000) DEFAULT NULL,
  referer_content_id bigint DEFAULT NULL,
  date_ymd int NOT NULL,
  visits int NOT NULL DEFAULT '1',
  depth int NOT NULL DEFAULT '0',
  metadata_json json DEFAULT NULL,
  PRIMARY KEY (referer_id)
);

CREATE INDEX {{prefix}}referers_idx_content_id ON {{prefix}}referers (content_id);
CREATE INDEX {{prefix}}referers_idx_actor_id ON {{prefix}}referers (actor_id);
CREATE INDEX {{prefix}}referers_idx_referer_domain ON {{prefix}}referers (referer_domain);
CREATE INDEX {{prefix}}referers_idx_referer_content_id ON {{prefix}}referers (referer_content_id);
CREATE INDEX {{prefix}}referers_idx_date ON {{prefix}}referers (date_ymd);
ALTER TABLE {{prefix}}referers CHANGE referer_id referer_id bigint NOT NULL ;

-- Registry tables removed - replaced by timestamp-based ID generation
-- See: lupo-includes/classes/IdGenerator.php
-- Registry tables were over-engineered and violated database neutrality doctrine
-- Use timestamp-based IDs: YYYYMMDDHHIISS + 4-digit sequence (0000-9999)

-- {{prefix}}projects: project registry (PROJECT_REGISTRY_SCHEMA_DESIGN.md, create_{{prefix}}projects.sql.md). project_id application-assigned, no .
CREATE TABLE {{prefix}}projects (
  project_id bigint NOT NULL,
  project_key varchar(64) NOT NULL,
  project_slug varchar(255) NOT NULL,
  project_name varchar(255) NOT NULL,
  federation_node_id bigint NOT NULL,
  default_channel_id bigint DEFAULT NULL,
  orchestrator_id bigint NOT NULL,
  project_type varchar(64) DEFAULT 'standard',
  description text DEFAULT NULL,
  github_repository varchar(512) DEFAULT NULL,
  status varchar(32) NOT NULL DEFAULT 'active',
  is_active tinyint NOT NULL DEFAULT 1,
  is_deleted tinyint NOT NULL DEFAULT 0,
  is_archived tinyint NOT NULL DEFAULT 0,
  is_frozen tinyint NOT NULL DEFAULT 0,
  metadata_json json DEFAULT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL DEFAULT 0,
  deleted_ymdhis bigint DEFAULT 0,
  created_by_actor_id bigint DEFAULT NULL,
  updated_by_actor_id bigint DEFAULT NULL,
  PRIMARY KEY (project_id),
  UNIQUE KEY uk_project_key_node (project_key, federation_node_id),
  UNIQUE KEY uk_project_slug_node (project_slug, federation_node_id)
);

CREATE INDEX {{prefix}}projects_idx_federation_node ON {{prefix}}projects (federation_node_id, status, is_deleted);
CREATE INDEX {{prefix}}projects_idx_project_key ON {{prefix}}projects (project_key, federation_node_id);
CREATE INDEX {{prefix}}projects_idx_project_slug ON {{prefix}}projects (project_slug, federation_node_id);
CREATE INDEX {{prefix}}projects_idx_orchestrator ON {{prefix}}projects (orchestrator_id, status, is_deleted);
CREATE INDEX {{prefix}}projects_idx_default_channel ON {{prefix}}projects (default_channel_id);
CREATE INDEX {{prefix}}projects_idx_status ON {{prefix}}projects (status, is_active, is_deleted);
CREATE INDEX {{prefix}}projects_idx_created ON {{prefix}}projects (created_ymdhis);
CREATE INDEX {{prefix}}projects_idx_updated ON {{prefix}}projects (updated_ymdhis);

-- Unified import registry for collision resolution during federation imports.


CREATE TABLE {{prefix}}uploads (
  upload_id bigint NOT NULL,
  actor_id bigint NOT NULL,
  channel_id bigint DEFAULT NULL,
  original_filename varchar(255) NOT NULL,
  stored_filename varchar(255) NOT NULL,
  file_extension varchar(16) NOT NULL,
  mime_type varchar(128) NOT NULL,
  file_size_bytes bigint NOT NULL,
  storage_path varchar(512) NOT NULL,
  metadata_json json DEFAULT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL,
  is_deleted tinyint NOT NULL DEFAULT '0',
  deleted_ymdhis bigint DEFAULT NULL,
  PRIMARY KEY (upload_id)
);

CREATE INDEX {{prefix}}uploads_idx_actor_id ON {{prefix}}uploads (actor_id);
CREATE INDEX {{prefix}}uploads_idx_channel_id ON {{prefix}}uploads (channel_id);
CREATE INDEX {{prefix}}uploads_idx_file_extension ON {{prefix}}uploads (file_extension);
CREATE INDEX {{prefix}}uploads_idx_created_ymdhis ON {{prefix}}uploads (created_ymdhis);

-- Old world events table removed in v4.0.86 - consolidated into {{prefix}}unified_log

CREATE TABLE {{prefix}}world_registry (
  world_id bigint NOT NULL,
  world_key varchar(255) NOT NULL,
  world_type varchar(64) NOT NULL,
  world_label varchar(255) NOT NULL,
  world_metadata json DEFAULT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL,
  is_active tinyint NOT NULL DEFAULT '1',
  PRIMARY KEY (world_id)
);

CREATE UNIQUE INDEX {{prefix}}world_registry_unique_world_key ON {{prefix}}world_registry (world_key);
CREATE INDEX {{prefix}}world_registry_idx_world_type ON {{prefix}}world_registry (world_type);
CREATE INDEX {{prefix}}world_registry_idx_created_ymdhis ON {{prefix}}world_registry (created_ymdhis);
CREATE INDEX {{prefix}}world_registry_idx_is_active ON {{prefix}}world_registry (is_active);

-- ============================================================
-- SEED DATA REMOVED - USE seed_minimal_4.0.86.sql INSTEAD
-- ============================================================
-- ALL SEED DATA COMMENTED OUT (2026-02-22)
-- Reason: Schema mismatches between INSERT column names and actual table definitions
-- Solution: Seed data moved to database/migrations/seed_minimal_4.0.86.sql
-- This file now contains ONLY table definitions (CREATE TABLE statements)
-- install.php loads schema from this file, then seeds from seed_minimal_4.0.86.sql
-- ============================================================

-- ============================================================
-- FINAL IDE & AI ACTOR INTEGRATION (Lupopedia 4.0.86)
-- ============================================================
-- CSV-driven unregistry allocation - FINAL ACTOR IDs:
-- Cursor IDE: 211, Kiro IDE: 212, Zed IDE: 213, VS Code IDE: 214
-- Antigravity IDE: 215, Microsoft Copilot: 216, DeepSeek LEXA: 217, DeepSeek LILITH: 218
-- ============================================================

-- Registry entries for all IDE & AI actors


-- Actor records for all IDE & AI actors
INSERT IGNORE INTO {{prefix}}actors (actor_id, actor_type, slug, name, created_ymdhis, updated_ymdhis, is_active, is_deleted, deleted_ymdhis, actor_source_id, actor_source_type, metadata, adversarial_role, adversarial_oversight_actor_id, avatar_hash) 
VALUES 
(102, 'work_agent', 'hephaestus', 'HEPHAESTUS (The Smith)', @now, @now, 1, 0, NULL, 102, 'work_agent', '{"purpose":"Work Agent","archetype":"Hephaestus","faucet_context":"Cursor/VSCode/Windsurf","note":"Manifestation of the Smith; all IDE faucets are tools of Hephaestus."}', 'none', NULL, NULL),
(211, 'system_tool', 'cursor-ide', 'Cursor IDE', @now, @now, 1, 0, NULL, 211, 'system_tool', '{"purpose":"IDE_integration","capabilities":["code_generation","file_editing","project_management","git_integration"],"version":"1.0.0","client_id":"cursor","provider":"cursor","integration_ready":true}', 'none', NULL, NULL),
(212, 'system_tool', 'kiro-ide', 'Kiro IDE', @now, @now, 1, 0, NULL, 212, 'system_tool', '{"purpose":"IDE_integration","capabilities":["code_generation","file_editing","project_management","git_integration"],"version":"1.0.0","client_id":"kiro","provider":"kiro","integration_ready":true}', 'none', NULL, NULL),
(213, 'system_tool', 'zed-ide', 'Zed IDE', @now, @now, 1, 0, NULL, 213, 'system_tool', '{"purpose":"IDE_integration","capabilities":["code_generation","file_editing","project_management","git_integration"],"version":"1.0.0","client_id":"zed","provider":"zed","integration_ready":true}', 'none', NULL, NULL),
(214, 'system_tool', 'vscode-ide', 'VS Code IDE', @now, @now, 1, 0, NULL, 214, 'system_tool', '{"purpose":"IDE_integration","capabilities":["code_generation","file_editing","project_management","git_integration"],"version":"1.0.0","client_id":"vscode","provider":"microsoft","integration_ready":true}', 'none', NULL, NULL),
(215, 'system_tool', 'antigravity-ide', 'Antigravity IDE', @now, @now, 1, 0, NULL, 215, 'system_tool', '{"purpose":"VSX_extension_development","capabilities":["project_management","file_editing","semantic_navigation","open_vsx_integration"],"version":"1.0.0","client_id":"antigravity","integration_ready":true}', 'none', NULL, NULL),
(216, 'external_ai', 'microsoft-copilot', 'Microsoft Copilot', @now, @now, 1, 0, NULL, 216, 'external_ai', '{"purpose":"AI_assistant","capabilities":["code_generation","debugging","documentation","file_editing","git_integration"],"version":"1.0.0","client_id":"copilot","provider":"microsoft","integration_ready":true}', 'none', NULL, NULL),
(217, 'external_ai', 'deepseek-lexa', 'DeepSeek LEXA', @now, @now, 1, 0, NULL, 217, 'external_ai', '{"purpose":"AI_assistant","capabilities":["code_generation","debugging","documentation","file_editing","git_integration"],"version":"1.0.0","client_id":"deepseek_lexa","provider":"deepseek","integration_ready":true}', 'none', NULL, NULL),
(218, 'external_ai', 'deepseek-lilith', 'DeepSeek LILITH', @now, @now, 1, 0, NULL, 218, 'external_ai', '{"purpose":"AI_assistant","capabilities":["code_generation","debugging","documentation","file_editing","git_integration"],"version":"1.0.0","client_id":"deepseek_lilith","provider":"deepseek","integration_ready":true}', 'none', NULL, NULL)
ON DUPLICATE KEY UPDATE name = VALUES(name), metadata = VALUES(metadata), updated_ymdhis = @now, is_active = 1, is_deleted = 0;

-- Channel 42: Lupopedia Development (system channel)
INSERT INTO {{prefix}}channels (
    channel_id, federation_node_id, created_by_actor_id, default_actor_id, department_id, 
    channel_key, channel_slug, channel_type, language, channel_name, description, 
  website_link, metadata_json, status_flag, end_ymdhis, 
    created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis, 
    aal_metadata_json, fleet_composition_json, awareness_version, channel_number, 
    parent_channel_id, is_kernel, boot_sequence_order
) VALUES (
    42, 1, 1, 1, 0, 
    'lupopedia-development', 'lupopedia-development', 'chat_room', 'en', 'Lupopedia Development', 
    'Main development channel for Lupopedia system development, architecture, and coordination.', 
    NULL, '{"purpose": "development", "system": true, "auto_created": true}', 1, 
    NULL, 20260221000000, 20260221000000, 0, NULL, 
    NULL, NULL, '3.0.0', 42, NULL, 0, 2
);

-- Channel 42 membership: All 25 active actors (excluding actor_id 420)
INSERT INTO {{prefix}}actor_channels (actor_channel_id, actor_id, channel_id, created_by_actor_id, status, start_date, channel_color, last_read_ymdhis, muted_until_ymdhis, preferences_json, dialog_output_file, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis) 
VALUES 
(12001, 1, 42, 1, 'A', 20260221000000, 'F7FAFF', NULL, NULL, '{"ui": {"theme": "kernel"}, "notifications": {"enabled": false}}', NULL, 20260221000000, 20260221000000, 0, NULL),
(12002, 2, 42, 1, 'A', 20260221000000, 'F7FAFF', NULL, NULL, '{"ui": {"theme": "kernel"}, "notifications": {"enabled": false}}', NULL, 20260221000000, 20260221000000, 0, NULL),
(12003, 3, 42, 1, 'A', 20260221000000, 'F7FAFF', NULL, NULL, '{"ui": {"theme": "kernel"}, "notifications": {"enabled": false}}', NULL, 20260221000000, 20260221000000, 0, NULL),
(12004, 4, 42, 1, 'A', 20260221000000, 'F7FAFF', NULL, NULL, '{"ui": {"theme": "kernel"}, "notifications": {"enabled": false}}', NULL, 20260221000000, 20260221000000, 0, NULL),
(12005, 5, 42, 1, 'A', 20260221000000, 'F7FAFF', NULL, NULL, '{"ui": {"theme": "kernel"}, "notifications": {"enabled": false}}', NULL, 20260221000000, 20260221000000, 0, NULL),
(12006, 6, 42, 1, 'A', 20260221000000, 'F7FAFF', NULL, NULL, '{"ui": {"theme": "kernel"}, "notifications": {"enabled": false}}', NULL, 20260221000000, 20260221000000, 0, NULL),
(12007, 7, 42, 1, 'A', 20260221000000, 'F7FAFF', NULL, NULL, '{"ui": {"theme": "kernel"}, "notifications": {"enabled": false}}', NULL, 20260221000000, 20260221000000, 0, NULL),
(12008, 8, 42, 1, 'A', 20260221000000, 'F7FAFF', NULL, NULL, '{"ui": {"theme": "kernel"}, "notifications": {"enabled": false}}', NULL, 20260221000000, 20260221000000, 0, NULL),
(12009, 9, 42, 1, 'A', 20260221000000, 'F7FAFF', NULL, NULL, '{"ui": {"theme": "major kernel"}, "notifications": {"enabled": false}}', NULL, 20260221000000, 20260221000000, 0, NULL),
(12010, 10, 42, 1, 'A', 20260221000000, 'F7FAFF', NULL, NULL, '{"ui": {"theme": "kernel"}, "nowifications": {"enabled": false}}', NULL, 20260221000000, 20260221000000, 0, NULL),
(12011, 11, 42, 1, 'A', 20260221000000, 'F7FAFF', NULL, NULL, '{"ui": {"theme": "kernel"}, "notifications": {"enabled": false}}', NULL, 20260221000000, 20260221000000, 0, NULL),
(12012, 12, 42, 1, 'A', 20260221000000, 'F7FAFF', NULL, NULL, '{"ui": {"theme": "kernel"}, "notifications": {"enabled": false}}', NULL, 20260221000000, 20260221000000, 0, NULL),
(12013, 13, 42, 1, 'A', 20260221000000, 'F7FAFF', NULL, NULL, '{"ui": {"theme": "kernel"}, "notifications": {"enabled": false}}', NULL, 20260221000000, 20260221000000, 0, NULL),
(12014, 14, 42, 1, 'A', 20260221000000, 'F7FAFF', NULL, NULL, '{"ui": {"theme": "kernel"}, "notifications": {"enabled": false}}', NULL, 20260221000000, 20260221000000, 0, NULL),
(12015, 15, 42, 1, 'A', 20260221000000, 'F7FAFF', NULL, NULL, '{"ui": {"theme": "kernel"}, "notifications": {"enabled": false}}', NULL, 20260221000000, 20260221000000, 0, NULL),
(12016, 16, 42, 1, 'A', 20260221000000, 'F7FAFF', NULL, NULL, '{"ui": {"theme": "kernel"}, "notifications": {"enabled": false}}', NULL, 20260221000000, 20260221000000, 0, NULL),
(12017, 17, 42, 1, 'A', 20260221000000, 'F7FAFF', NULL, NULL, '{"ui": {"theme": "kernel"}, "notifications": {"enabled": false}}', NULL, 20260221000000, 20260221000000, 0, NULL),
(12018, 18, 42, 1, 'A', 20260221000000, 'F7FAFF', NULL, NULL, '{"ui": {"theme": "kernel"}, "notifications": {"enabled": false}}', NULL, 20260221000000, 20260221000000, 0, NULL),
(12019, 19, 42, 1, 'A', 20260221000000, 'F7FAFF', NULL, NULL, '{"ui": {"theme": "kernel"}, "notifications": {"enabled": false}}', NULL, 20260221000000, 20260221000000, 0, NULL),
(12020, 20, 42, 1, 'A', 20260221000000, 'F7FAFF', NULL, NULL, '{"ui": {"theme": "kernel"}, "notifications": {"enabled": false}}', NULL, 20260221000000, 20260221000000, 0, NULL),
(12021, 21, 42, 1, 'A', 20260221000000, 'F7FAFF', NULL, NULL, '{"ui": {"theme": "kernel"}, "notifications": {"enabled": false}}', NULL, 20260221000000, 20260221000000, 0, NULL),
(12022, 22, 42, 1, 'A', 20260221000000, 'F7FAFF', NULL, NULL, '{"ui": {"theme": "kernel"}, "notifications": {"enabled": false}}', NULL, 20260221000000, 20260221000000, 0, NULL),
(12023, 23, 42, 1, 'A', 20260221000000, 'F7FAFF', NULL, NULL, '{"ui": {"theme": "kernel"}, "notifications": {"enabled": false}}', NULL, 20260221000000, 20260221000000, 0, NULL),
(12024, 24, 42, 1, 'A', 20260221000000, 'F7FAFF', NULL, NULL, '{"ui": {"theme": "kernel"}, "notifications": {"enabled": false}}', NULL, 20260221000000, 20260221000000, 0, NULL),
(12025, 25, 42, 1, 'A', 20260221000000, 'F7FAFF', NULL, NULL, '{"ui": {"theme": "kernel"}, "notifications": {"enabled": false}}', NULL, 20260221000000, 20260221000000, 0, NULL)
ON DUPLICATE KEY UPDATE 
    actor_id = VALUES(actor_id),
    channel_id = VALUES(channel_id),
    updated_ymdhis = VALUES(updated_ymdhis),
    is_deleted = 0,
    deleted_ymdhis = NULL;

-- Department 0 membership for all IDE & AI actors
INSERT IGNORE INTO {{prefix}}actor_departments (actor_department_id, actor_id, department_id, role_key, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis) 
VALUES 
(12031, 211, 0, 'member', @now, @now, 0, NULL),
(12032, 212, 0, 'member', @now, @now, 0, NULL),
(12033, 213, 0, 'member', @now, @now, 0, NULL),
(12034, 214, 0, 'member', @now, @now, 0, NULL),
(12035, 215, 0, 'member', @now, @now, 0, NULL),
(12036, 216, 0, 'member', @now, @now, 0, NULL),
(12037, 217, 0, 'member', @now, @now, 0, NULL),
(12038, 218, 0, 'member', @now, @now, 0, NULL)
ON DUPLICATE KEY UPDATE role_key = VALUES(role_key), updated_ymdhis = @now, is_deleted = 0;

-- ============================================================
-- WARP IDE ACTOR INTEGRATION (Lupopedia 4.0.86)
-- ============================================================
-- Warp IDE: actor_id 219, system_tool, federation_node_id 0 (local node)
-- paired_actor_id = 10000 (human operator)
-- ============================================================

-- Registry entries removed - replaced by timestamp-based ID generation
-- See: lupo-includes/classes/IdGenerator.php
-- Registry tables were over-engineered and violated database neutrality doctrine
-- Use timestamp-based IDs: YYYYMMDDHHIISS + 4-digit sequence (0000-9999)

-- Actor record for Warp IDE (with paired_actor_id)
INSERT IGNORE INTO {{prefix}}actors (actor_id, actor_type, slug, name, created_ymdhis, updated_ymdhis, is_active, is_deleted, deleted_ymdhis, actor_source_id, actor_source_type, metadata, adversarial_role, adversarial_oversight_actor_id, avatar_hash, paired_actor_id)
VALUES (219, 'system_tool', 'warp-ide', 'Warp IDE', @now, @now, 1, 0, NULL, 219, 'system_tool', '{"purpose":"IDE_integration","capabilities":["code_generation","file_editing","project_management","git_integration","terminal_integration"],"version":"1.0.0","client_id":"warp","provider":"warp","integration_ready":true,"paired_actor_id":10000}', 'none', NULL, NULL, 10000)
ON DUPLICATE KEY UPDATE name = VALUES(name), metadata = VALUES(metadata), paired_actor_id = VALUES(paired_actor_id), updated_ymdhis = @now, is_active = 1, is_deleted = 0;

-- Channel 42 membership for Warp IDE
INSERT INTO {{prefix}}actor_channels (actor_channel_id, actor_id, channel_id, created_by_actor_id, status, start_date, channel_color, last_read_ymdhis, muted_until_ymdhis, preferences_json, dialog_output_file, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis)
VALUES (12039, 219, 42, 1000, 'A', 20260221000000, 'F7FAFF', NULL, NULL, '{"purpose":"IDE_integration","capabilities":["code_generation","file_editing","project_management","terminal_integration"],"paired_actor_id":10000}', NULL, @now, @now, 0, NULL)
ON DUPLICATE KEY UPDATE actor_id = VALUES(actor_id), channel_id = VALUES(channel_id), updated_ymdhis = @now, is_deleted = 0;

-- Department 0 membership for Warp IDE
INSERT IGNORE INTO {{prefix}}actor_departments (actor_department_id, actor_id, department_id, role_key, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis)
VALUES (12039, 219, 0, 'member', @now, @now, 0, NULL)
ON DUPLICATE KEY UPDATE role_key = VALUES(role_key), updated_ymdhis = @now, is_deleted = 0;

-- ============================================================
-- WINDSURF IDE ACTOR INTEGRATION (Lupopedia 4.0.86)
-- ============================================================
-- Windsurf IDE: actor_id 220 (reassigned from conflicting actor_id 2 = CAPTAIN)
-- paired_actor_id = 10000 (human operator), federation_node_id 0 (local node)
-- ============================================================

-- Registry entries removed - replaced by timestamp-based ID generation
-- See: lupo-includes/classes/IdGenerator.php
-- Registry tables were over-engineered and violated database neutrality doctrine
-- Use timestamp-based IDs: YYYYMMDDHHIISS + 4-digit sequence (0000-9999)

-- Actor record for Windsurf IDE (with paired_actor_id)
INSERT IGNORE INTO {{prefix}}actors (actor_id, actor_type, slug, name, created_ymdhis, updated_ymdhis, is_active, is_deleted, deleted_ymdhis, actor_source_id, actor_source_type, metadata, adversarial_role, adversarial_oversight_actor_id, avatar_hash, paired_actor_id)
VALUES (220, 'system_tool', 'windsurf-ide', 'Windsurf IDE', @now, @now, 1, 0, NULL, 220, 'system_tool', '{"purpose":"IDE_integration","capabilities":["code_generation","file_editing","project_management","git_integration","vsx_extension_development"],"version":"1.0.0","client_id":"windsurf","provider":"windsurf","integration_ready":true,"paired_actor_id":10000,"note":"Reassigned from actor_id 2 to avoid CAPTAIN conflict"}', 'none', NULL, NULL, 10000)
ON DUPLICATE KEY UPDATE name = VALUES(name), metadata = VALUES(metadata), paired_actor_id = VALUES(paired_actor_id), updated_ymdhis = @now, is_active = 1, is_deleted = 0;

-- Channel 42 membership for Windsurf IDE
INSERT INTO {{prefix}}actor_channels (actor_channel_id, actor_id, channel_id, created_by_actor_id, status, start_date, channel_color, last_read_ymdhis, muted_until_ymdhis, preferences_json, dialog_output_file, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis)
VALUES (12040, 220, 42, 1000, 'A', 20260221000000, 'F7FAFF', NULL, NULL, '{"purpose":"IDE_integration","capabilities":["code_generation","file_editing","project_management","vsx_extension_development"],"paired_actor_id":10000}', NULL, @now, @now, 0, NULL)
ON DUPLICATE KEY UPDATE actor_id = VALUES(actor_id), channel_id = VALUES(channel_id), updated_ymdhis = @now, is_deleted = 0;

-- Department 0 membership for Windsurf IDE
INSERT IGNORE INTO {{prefix}}actor_departments (actor_department_id, actor_id, department_id, role_key, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis)
VALUES (12040, 220, 0, 'member', @now, @now, 0, NULL)
ON DUPLICATE KEY UPDATE role_key = VALUES(role_key), updated_ymdhis = @now, is_deleted = 0;

-- ============================================================
-- PAIRED ACTOR + FEDERATION NODE FIXES (Lupopedia 4.0.86)
-- ============================================================
-- Fix paired_actor_id for Copilot (216) and LILITH (218)
-- Fix federation_node_id: Copilot/LILITH = 1 (remote), others local = 0
-- ============================================================

-- paired_actor_id: Copilot â†’ 0 (human operator)
UPDATE {{prefix}}actors SET paired_actor_id = 0, updated_ymdhis = COALESCE(@now, updated_ymdhis) WHERE actor_id = 216;
-- paired_actor_id: LILITH â†’ 0 (human operator)
UPDATE {{prefix}}actors SET paired_actor_id = 0, updated_ymdhis = COALESCE(@now, updated_ymdhis) WHERE actor_id = 218;

-- ============================================================
-- FLIP v2: use {{prefix}}artifacts with entity_type = 'flip_artifact'
-- ============================================================
-- FLIP/WOLFIE header and footer index: store in {{prefix}}artifacts with
-- entity_type = 'flip_artifact', channel_id, artifact_kind, file_path_from_root;
-- FLIP-specific data (header_json, footer_json, agent_slug, etc.) in metadata JSON.
-- ============================================================

-- ============================================================
-- FLIP v2 REGISTRY ENTRIES (Lupopedia 4.0.86)
-- ============================================================

-- Registry entries removed - replaced by timestamp-based ID generation
-- See: lupo-includes/classes/IdGenerator.php
-- Registry tables were over-engineered and violated database neutrality doctrine
-- Use timestamp-based IDs: YYYYMMDDHHIISS + 4-digit sequence (0000-9999)

-- ============================================================
-- TASK MANAGEMENT SYSTEM (Lupopedia 4.0.86)
-- ============================================================
-- Tables for task management and offline task import support
-- Supports MD file task import and database-driven task tracking
-- Added: 2026-02-25 by Kiro (1000)
-- Task lookup tables removed in v4.0.86 - consolidated into {{prefix}}tasks VARCHAR columns

-- Core Tasks Table
CREATE TABLE {{prefix}}tasks (
  task_id bigint NOT NULL,
  task_key varchar(64) NOT NULL,
  channel_id bigint NOT NULL,
  owner_actor_id bigint NOT NULL,
  title varchar(255) NOT NULL,
  description text,
  prompt_path varchar(512) DEFAULT NULL,
  acting_as_actor_id bigint DEFAULT NULL,
  estimated_duration_seconds int DEFAULT NULL,
  actual_duration_seconds int DEFAULT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL,
  started_ymdhis bigint DEFAULT NULL,
  completed_ymdhis bigint DEFAULT NULL,
  is_deleted tinyint NOT NULL DEFAULT '0',
  deleted_ymdhis bigint DEFAULT NULL,
  metadata_json text,
  task_type varchar(64),
  task_status varchar(64),
  task_priority ENUM('low', 'normal', 'high', 'urgent', 'critical') NOT NULL DEFAULT 'normal',
  parent_agent_id bigint DEFAULT NULL,
  consensus_hash varchar(255) DEFAULT NULL,
  approval_chain_json json DEFAULT NULL,
  task_embeddings text DEFAULT NULL,
  visibility_status varchar(32) NOT NULL DEFAULT 'active',
  PRIMARY KEY (task_id)
);

CREATE UNIQUE INDEX {{prefix}}tasks_uniq_task_key_per_channel ON {{prefix}}tasks (task_key, channel_id);
CREATE INDEX {{prefix}}tasks_idx_channel_id ON {{prefix}}tasks (channel_id);
CREATE INDEX {{prefix}}tasks_idx_owner_actor_id ON {{prefix}}tasks (owner_actor_id);
CREATE INDEX {{prefix}}tasks_idx_task_type ON {{prefix}}tasks (task_type);
CREATE INDEX {{prefix}}tasks_idx_task_status ON {{prefix}}tasks (task_status);
CREATE INDEX {{prefix}}tasks_idx_task_priority ON {{prefix}}tasks (task_priority);
CREATE INDEX {{prefix}}tasks_idx_created_ymdhis ON {{prefix}}tasks (created_ymdhis);
CREATE INDEX {{prefix}}tasks_idx_acting_as_actor_id ON {{prefix}}tasks (acting_as_actor_id);
CREATE INDEX {{prefix}}tasks_idx_is_deleted ON {{prefix}}tasks (is_deleted);
CREATE INDEX {{prefix}}tasks_idx_parent_agent_id ON {{prefix}}tasks (parent_agent_id);
CREATE INDEX {{prefix}}tasks_idx_visibility_status ON {{prefix}}tasks (visibility_status);
-- RESERVED ID DOCTRINE: task_id is NOT ; application must supply explicit ID.

CREATE TABLE {{prefix}}escalation_tasks (
  escalation_task_id bigint NOT NULL,
  actor_id bigint NOT NULL,
  thread_id bigint NOT NULL,
  message_id bigint NOT NULL,
  task_type varchar(64) NOT NULL,
  status varchar(32) NOT NULL DEFAULT 'open',
  assigned_actor_id bigint NOT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL,
  PRIMARY KEY (escalation_task_id)
);

CREATE INDEX {{prefix}}escalation_tasks_idx_actor_id ON {{prefix}}escalation_tasks (actor_id);
CREATE INDEX {{prefix}}escalation_tasks_idx_thread_id ON {{prefix}}escalation_tasks (thread_id);
CREATE INDEX {{prefix}}escalation_tasks_idx_message_id ON {{prefix}}escalation_tasks (message_id);
CREATE INDEX {{prefix}}escalation_tasks_idx_status ON {{prefix}}escalation_tasks (status);
CREATE INDEX {{prefix}}escalation_tasks_idx_assigned_actor_id ON {{prefix}}escalation_tasks (assigned_actor_id);

-- {{prefix}}rolls: actor roles in channels (multi-agent evolution)
CREATE TABLE {{prefix}}rolls (
  roll_id bigint NOT NULL,
  channel_id bigint NOT NULL,
  actor_id bigint NOT NULL,
  role_slug varchar(100) NOT NULL,
  permission_scope_json json DEFAULT NULL,
  is_active tinyint NOT NULL DEFAULT 1,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL,
  PRIMARY KEY (roll_id)
);
CREATE INDEX {{prefix}}rolls_idx_channel_actor ON {{prefix}}rolls (channel_id, actor_id);
CREATE INDEX {{prefix}}rolls_idx_role ON {{prefix}}rolls (role_slug);

-- Task Assignments
-- Task Dependencies
-- Old task events table removed in v4.0.86 - consolidated into {{prefix}}unified_log

-- ============================================================
-- END OF TASK MANAGEMENT SYSTEM
-- ============================================================

-- ============================================================
-- ACTOR IDENTITY CAPSULE SYSTEM (v4.0.86)
-- ============================================================

-- Table 1: {{prefix}}actor_history
-- Stores structured actor achievement and contribution history
CREATE TABLE {{prefix}}actor_history (
    history_id BIGINT NOT NULL,
    actor_id BIGINT NOT NULL,
    achievement_id VARCHAR(100),
    title VARCHAR(255) NOT NULL,
    description TEXT,
    impact TEXT,
    date_ymdhis BIGINT NOT NULL DEFAULT 0,
    channel_id BIGINT,
    tags JSON,
    metrics JSON,
    created_ymdhis BIGINT NOT NULL DEFAULT 0,
    updated_ymdhis BIGINT,
    is_deleted TINYINT NOT NULL DEFAULT 0,
    deleted_ymdhis BIGINT
);

CREATE INDEX {{prefix}}actor_history_idx_actor_id ON {{prefix}}actor_history(actor_id);
CREATE INDEX {{prefix}}actor_history_idx_date_ymdhis ON {{prefix}}actor_history(date_ymdhis);
CREATE INDEX {{prefix}}actor_history_idx_channel_id ON {{prefix}}actor_history(channel_id);
CREATE INDEX {{prefix}}actor_history_idx_is_deleted ON {{prefix}}actor_history(is_deleted);

-- Core Identity Expansion: Actor Intelligence Tables (added v4.0.94)
-- Table: {{prefix}}actor_memory
CREATE TABLE {{prefix}}actor_memory (
  memory_id BIGINT NOT NULL,
  actor_id BIGINT NOT NULL,
  memory_type VARCHAR(64) NOT NULL,
  memory_key VARCHAR(128) NOT NULL,
  memory_value TEXT,
  context_json JSON DEFAULT NULL,
  created_ymdhis BIGINT NOT NULL DEFAULT 0,
  updated_ymdhis BIGINT,
  is_deleted TINYINT NOT NULL DEFAULT 0,
  deleted_ymdhis BIGINT,
  PRIMARY KEY (memory_id)
);
CREATE INDEX {{prefix}}actor_memory_idx_actor_id ON {{prefix}}actor_memory(actor_id);
CREATE INDEX {{prefix}}actor_memory_idx_type_key ON {{prefix}}actor_memory(memory_type, memory_key);
CREATE INDEX {{prefix}}actor_memory_idx_is_deleted ON {{prefix}}actor_memory(is_deleted);

-- Table: {{prefix}}actor_skills
CREATE TABLE {{prefix}}actor_skills (
  skill_id BIGINT NOT NULL,
  actor_id BIGINT NOT NULL,
  skill_name VARCHAR(128) NOT NULL,
  skill_level VARCHAR(32) DEFAULT NULL,
  skill_metadata JSON DEFAULT NULL,
  acquired_ymdhis BIGINT NOT NULL DEFAULT 0,
  updated_ymdhis BIGINT,
  is_deleted TINYINT NOT NULL DEFAULT 0,
  deleted_ymdhis BIGINT,
  PRIMARY KEY (skill_id)
);
CREATE INDEX {{prefix}}actor_skills_idx_actor_id ON {{prefix}}actor_skills(actor_id);
CREATE INDEX {{prefix}}actor_skills_idx_skill_name ON {{prefix}}actor_skills(skill_name);
CREATE INDEX {{prefix}}actor_skills_idx_is_deleted ON {{prefix}}actor_skills(is_deleted);

-- Table: {{prefix}}actor_tools
CREATE TABLE {{prefix}}actor_tools (
  tool_id BIGINT NOT NULL,
  actor_id BIGINT NOT NULL,
  tool_name VARCHAR(128) NOT NULL,
  tool_type VARCHAR(64) DEFAULT NULL,
  tool_metadata JSON DEFAULT NULL,
  acquired_ymdhis BIGINT NOT NULL DEFAULT 0,
  updated_ymdhis BIGINT,
  is_deleted TINYINT NOT NULL DEFAULT 0,
  deleted_ymdhis BIGINT,
  PRIMARY KEY (tool_id)
);
CREATE INDEX {{prefix}}actor_tools_idx_actor_id ON {{prefix}}actor_tools(actor_id);
CREATE INDEX {{prefix}}actor_tools_idx_tool_name ON {{prefix}}actor_tools(tool_name);
CREATE INDEX {{prefix}}actor_tools_idx_is_deleted ON {{prefix}}actor_tools(is_deleted);

-- Table: {{prefix}}actor_prompts
CREATE TABLE {{prefix}}actor_prompts (
  prompt_id BIGINT NOT NULL,
  actor_id BIGINT NOT NULL,
  prompt_key VARCHAR(128) NOT NULL,
  prompt_text TEXT NOT NULL,
  prompt_type VARCHAR(64) DEFAULT NULL,
  context_json JSON DEFAULT NULL,
  created_ymdhis BIGINT NOT NULL DEFAULT 0,
  updated_ymdhis BIGINT,
  is_deleted TINYINT NOT NULL DEFAULT 0,
  deleted_ymdhis BIGINT,
  PRIMARY KEY (prompt_id)
);
CREATE INDEX {{prefix}}actor_prompts_idx_actor_id ON {{prefix}}actor_prompts(actor_id);
CREATE INDEX {{prefix}}actor_prompts_idx_prompt_key ON {{prefix}}actor_prompts(prompt_key);
CREATE INDEX {{prefix}}actor_prompts_idx_is_deleted ON {{prefix}}actor_prompts(is_deleted);

-- Table: {{prefix}}actor_training
CREATE TABLE {{prefix}}actor_training (
  training_id BIGINT NOT NULL,
  actor_id BIGINT NOT NULL,
  training_type VARCHAR(64) NOT NULL,
  training_data TEXT,
  training_metadata JSON DEFAULT NULL,
  started_ymdhis BIGINT NOT NULL DEFAULT 0,
  completed_ymdhis BIGINT,
  updated_ymdhis BIGINT,
  is_deleted TINYINT NOT NULL DEFAULT 0,
  deleted_ymdhis BIGINT,
  PRIMARY KEY (training_id)
);
CREATE INDEX {{prefix}}actor_training_idx_actor_id ON {{prefix}}actor_training(actor_id);
CREATE INDEX {{prefix}}actor_training_idx_training_type ON {{prefix}}actor_training(training_type);
CREATE INDEX {{prefix}}actor_training_idx_is_deleted ON {{prefix}}actor_training(is_deleted);

-- Defines governance rules for actor interactions
-- Table 3: {{prefix}}capability_usage
-- Tracks actor capability utilization and performance
CREATE TABLE {{prefix}}capability_usage (
    usage_id BIGINT NOT NULL,
    actor_id BIGINT NOT NULL,
    capability VARCHAR(100) NOT NULL,
    usage_count BIGINT DEFAULT 0,
    success_rate FLOAT DEFAULT 1,
    avg_response_time_ms INT DEFAULT 0,
    last_used_ymdhis BIGINT DEFAULT 0,
    performance_metrics JSON,
    created_ymdhis BIGINT NOT NULL DEFAULT 0,
    updated_ymdhis BIGINT,
    is_deleted TINYINT NOT NULL DEFAULT 0,
    deleted_ymdhis BIGINT
);

CREATE INDEX {{prefix}}capability_usage_idx_actor_capability ON {{prefix}}capability_usage(actor_id, capability);
CREATE INDEX {{prefix}}capability_usage_idx_capability ON {{prefix}}capability_usage(capability);
CREATE INDEX {{prefix}}capability_usage_idx_last_used ON {{prefix}}capability_usage(last_used_ymdhis);
CREATE INDEX {{prefix}}capability_usage_idx_is_deleted ON {{prefix}}capability_usage(is_deleted);

-- Table 4: {{prefix}}llm_performance
-- Monitors LLM module performance across actors
-- Table 5: {{prefix}}federated_trust
-- Manages trust relationships between federated nodes
-- Table 6: {{prefix}}session_recovery
-- Enables session state recovery across restarts
-- ============================================================
-- FLARE FEDERATION NODE CONTENT MANAGEMENT
-- ============================================================

-- Table: {{prefix}}channel_content
-- Manages federation node content and web path mapping
CREATE TABLE {{prefix}}channel_content (
  channel_content_id bigint NOT NULL ,
  channel_id bigint NOT NULL,
  federation_node_id bigint NOT NULL,
  file_path varchar(500) NOT NULL,
  web_path varchar(500) NOT NULL,
  metadata_json json DEFAULT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL DEFAULT 0,
  is_deleted tinyint NOT NULL DEFAULT 0,
  PRIMARY KEY (channel_content_id)
);

-- Indexes for performance
CREATE INDEX {{prefix}}channel_content_idx_channel ON {{prefix}}channel_content (channel_id);
CREATE INDEX {{prefix}}channel_content_idx_federation_node ON {{prefix}}channel_content (federation_node_id);
CREATE INDEX {{prefix}}channel_content_idx_file_path ON {{prefix}}channel_content (file_path);
CREATE INDEX {{prefix}}channel_content_idx_web_path ON {{prefix}}channel_content (web_path);
CREATE INDEX {{prefix}}channel_content_idx_created ON {{prefix}}channel_content (created_ymdhis);
CREATE INDEX {{prefix}}channel_content_idx_updated ON {{prefix}}channel_content (updated_ymdhis);
CREATE INDEX {{prefix}}channel_content_idx_is_deleted ON {{prefix}}channel_content (is_deleted);

-- Insert canonical FLARE entry for federation node 0
INSERT INTO {{prefix}}channel_content
(channel_content_id, channel_id, federation_node_id, file_path, web_path, metadata_json, created_ymdhis, updated_ymdhis, is_deleted)
VALUES
(  42000000000001, -- deterministic PK: channel_id (42) + federation_node_id (0) + 0000000001
  42,
  0,
  'channels/42/content/federation_node_id/0/FLARE.md',
  'http://www.lupopedia.com/FLARE',
  JSON_OBJECT('description', 'Root FLARE definition for federation node 0'),
  20260301120000,
  20260301120000,
  0
);

-- ============================================================
-- RULES SYSTEM (4.0.86)
-- ============================================================
-- {{prefix}}rules: canonical registry of rules (rule_id explicit; no  per registry doctrine)
CREATE TABLE {{prefix}}rules (
  rule_id bigint NOT NULL,
  rule_name varchar(255) NOT NULL,
  rule_description text,
  rule_type varchar(64) NOT NULL,
  rule_script text NOT NULL,
  rule_version bigint NOT NULL DEFAULT 1,
  created_ymdhis bigint NOT NULL,
  updated_ymdhis bigint NOT NULL,
  is_deleted tinyint NOT NULL DEFAULT 0,
  deleted_ymdhis bigint DEFAULT NULL,
  PRIMARY KEY (rule_id)
);
CREATE INDEX {{prefix}}rules_idx_rule_type ON {{prefix}}rules (rule_type);
CREATE INDEX {{prefix}}rules_idx_rule_name ON {{prefix}}rules (rule_name);
CREATE INDEX {{prefix}}rules_idx_is_deleted ON {{prefix}}rules (is_deleted);

CREATE TABLE {{prefix}}rule_targets (
  rule_target_id bigint NOT NULL ,
  rule_id bigint NOT NULL,
  target_table varchar(255) NOT NULL,
  target_id bigint NOT NULL,
  applied_by_actor_id bigint DEFAULT NULL,
  priority int NOT NULL DEFAULT 100,
  created_ymdhis bigint NOT NULL,
  updated_ymdhis bigint NOT NULL,
  is_deleted tinyint NOT NULL DEFAULT 0,
  deleted_ymdhis bigint DEFAULT NULL,
  PRIMARY KEY (rule_target_id)
);
CREATE INDEX {{prefix}}rule_targets_idx_rule_target ON {{prefix}}rule_targets (rule_id, target_table, target_id);
CREATE INDEX {{prefix}}rule_targets_idx_target ON {{prefix}}rule_targets (target_table, target_id);
CREATE INDEX {{prefix}}rule_targets_idx_is_deleted ON {{prefix}}rule_targets (is_deleted);

CREATE TABLE {{prefix}}rule_logs (
  rule_log_id bigint NOT NULL ,
  rule_id bigint NOT NULL,
  target_table varchar(255) NOT NULL,
  target_id bigint NOT NULL,
  actor_id bigint NOT NULL,
  instance_id bigint DEFAULT 0,
  event_type varchar(64) NOT NULL,
  event_details text,
  created_ymdhis bigint NOT NULL,
  PRIMARY KEY (rule_log_id)
);
CREATE INDEX {{prefix}}rule_logs_idx_rule_id ON {{prefix}}rule_logs (rule_id);
CREATE INDEX {{prefix}}rule_logs_idx_target ON {{prefix}}rule_logs (target_table, target_id);
CREATE INDEX {{prefix}}rule_logs_idx_actor_id ON {{prefix}}rule_logs (actor_id);
CREATE INDEX {{prefix}}rule_logs_idx_created_ymdhis ON {{prefix}}rule_logs (created_ymdhis);

-- ============================================================
-- COMMENTS SYSTEM (4.0.86)
-- ============================================================
-- {{prefix}}comments: comments on artifacts, documents, and content
CREATE TABLE {{prefix}}comments (
  comment_id bigint NOT NULL ,
  target_type varchar(64) NOT NULL,
  target_id bigint NOT NULL,
  channel_id bigint NOT NULL DEFAULT 42,
  actor_id bigint NOT NULL,
  faucet_id bigint DEFAULT NULL,
  comment_text text NOT NULL,
  comment_type varchar(64) NOT NULL DEFAULT 'comment',
  parent_comment_id bigint DEFAULT NULL,
  created_ymdhis bigint NOT NULL,
  updated_ymdhis bigint NOT NULL,
  is_deleted tinyint NOT NULL DEFAULT 0,
  deleted_ymdhis bigint DEFAULT NULL,
  metadata_json json DEFAULT NULL,
  PRIMARY KEY (comment_id)
);
CREATE INDEX {{prefix}}comments_idx_target ON {{prefix}}comments (target_type, target_id);
CREATE INDEX {{prefix}}comments_idx_channel_id ON {{prefix}}comments (channel_id);
CREATE INDEX {{prefix}}comments_idx_actor_id ON {{prefix}}comments (actor_id);
CREATE INDEX {{prefix}}comments_idx_faucet_id ON {{prefix}}comments (faucet_id);
CREATE INDEX {{prefix}}comments_idx_parent_comment_id ON {{prefix}}comments (parent_comment_id);
CREATE INDEX {{prefix}}comments_idx_created_ymdhis ON {{prefix}}comments (created_ymdhis);
CREATE INDEX {{prefix}}comments_idx_is_deleted ON {{prefix}}comments (is_deleted);

-- ============================================================
-- END OF ACTOR IDENTITY CAPSULE SYSTEM
-- ============================================================

-- =============================================================================
-- {{prefix}}orchestrator_rules (optional; v4.0.86 â€” orchestrator rule storage)
-- =============================================================================
CREATE TABLE {{prefix}}orchestrator_rules (
  rule_id bigint NOT NULL ,
  rule_slug varchar(128) NOT NULL,
  orchestrator_actor varchar(64) NOT NULL,
  rule_set_version varchar(32) NOT NULL,
  applies_to_json text NOT NULL,
  enforcement_level varchar(32) NOT NULL DEFAULT 'strict',
  rule_content text NOT NULL,
  checksum varchar(64) NOT NULL,
  is_active tinyint NOT NULL DEFAULT 1,
  updated_ymdhis bigint NOT NULL,
  PRIMARY KEY (rule_id)
);
CREATE UNIQUE INDEX {{prefix}}orchestrator_rules_uniq_slug ON {{prefix}}orchestrator_rules (rule_slug);
CREATE INDEX {{prefix}}orchestrator_rules_idx_actor_version ON {{prefix}}orchestrator_rules (orchestrator_actor, rule_set_version);
CREATE INDEX {{prefix}}orchestrator_rules_idx_active ON {{prefix}}orchestrator_rules (is_active);
CREATE INDEX {{prefix}}orchestrator_rules_idx_updated ON {{prefix}}orchestrator_rules (updated_ymdhis);

-- Database-Backed Visibility Extensions (Thread 1031 - Phase 1)
-- These extensions support web UI visibility for channels, threads, and tasks

-- Dialog Routing Decisions (Thread 2012 MVP)
-- Deterministic actor->human routing decision ledger
CREATE TABLE {{prefix}}routing_decisions (
  routing_decision_id BIGINT NOT NULL PRIMARY KEY,
  actor_id BIGINT NOT NULL,
  thread_id BIGINT NOT NULL,
  task_id BIGINT DEFAULT NULL,
  routing_strategy VARCHAR(64) NOT NULL,
  candidate_users_json TEXT NOT NULL,
  selected_auth_user_id BIGINT NOT NULL,
  fallback_index INT NOT NULL DEFAULT 0,
  decision_reason TEXT,
  decision_status VARCHAR(32) NOT NULL,
  trigger_type VARCHAR(64) NOT NULL,
  created_ymdhis BIGINT NOT NULL,
  completed_ymdhis BIGINT DEFAULT 0,
  idempotency_key VARCHAR(40) DEFAULT NULL
);

CREATE UNIQUE INDEX {{prefix}}routing_decisions_unq_idempotency ON {{prefix}}routing_decisions (idempotency_key);
CREATE INDEX {{prefix}}routing_decisions_idx_loop_break ON {{prefix}}routing_decisions(actor_id, thread_id, trigger_type, created_ymdhis);
CREATE INDEX {{prefix}}routing_decisions_idx_thread_created ON {{prefix}}routing_decisions(thread_id, created_ymdhis);
CREATE INDEX {{prefix}}routing_decisions_idx_selected_status ON {{prefix}}routing_decisions(selected_auth_user_id, decision_status, created_ymdhis);

-- Human-Targeted Thread Requests (Thread 1038)
-- Supports human-AI coordination with deterministic governance
CREATE TABLE {{prefix}}human_requests (
  request_id BIGINT NOT NULL PRIMARY KEY,
  -- Composite: {thread_id}_{ymdhis}_{seq}
  
  -- Thread context
  thread_id BIGINT NOT NULL,
  channel_id BIGINT NOT NULL,
  project_id BIGINT NOT NULL DEFAULT 0,
  
  -- Participants
  initiator_actor_id BIGINT NOT NULL,
  -- Agent or human actor who created the request
  
  target_auth_user_id BIGINT NOT NULL,
  -- Which auth user must respond
  
  -- Request content (explicit fields)
  request_type VARCHAR(64) NOT NULL,
  -- ENUM: 'clarification' | 'approval' | 'verification' | 'direct_response'
  
  request_title VARCHAR(255) NOT NULL,
  request_description TEXT NOT NULL,
  
  -- Subject context
  subject_type VARCHAR(64),
  -- ENUM: 'thread_artifact' | 'schema_change' | 'doctrine_question' | 'implementation'
  
  subject_reference VARCHAR(255),
  -- Reference to specific item (table_name, file_path, etc.)
  
  -- Priority and status
  priority VARCHAR(64) DEFAULT 'normal',
  -- ENUM: 'high' | 'normal' | 'low'

  request_mode VARCHAR(64) DEFAULT 'single_human',
  -- Values: 'single_human' | 'multi_human' (future-safe)
  
  status VARCHAR(64) NOT NULL DEFAULT 'pending',
  -- ENUM: 'draft' | 'pending' | 'answered' | 'resolved' | 'cancelled' | 'expired'
  
  -- Response fields
  response_text TEXT,
  response_auth_user_id BIGINT,
  response_actor_id BIGINT,
  
  -- Timestamps (BIGINT UTC only)
  created_ymdhis BIGINT NOT NULL,
  updated_ymdhis BIGINT NOT NULL,
  answered_ymdhis BIGINT,
  resolved_ymdhis BIGINT DEFAULT 0,
  expires_ymdhis BIGINT DEFAULT 0,
  
  -- Audit
  is_deleted TINYINT DEFAULT 0,
  deleted_ymdhis BIGINT DEFAULT 0
);

-- Indexes for efficient querying
CREATE INDEX idx_target_user_status ON {{prefix}}human_requests(target_auth_user_id, status);
CREATE INDEX idx_thread_requests ON {{prefix}}human_requests(thread_id, created_ymdhis DESC);
CREATE INDEX idx_initiator_actor ON {{prefix}}human_requests(initiator_actor_id, created_ymdhis DESC);
CREATE INDEX idx_priority_status ON {{prefix}}human_requests(priority, status, created_ymdhis DESC);
CREATE INDEX idx_status_expires ON {{prefix}}human_requests(status, expires_ymdhis);

-- Normalized context for thread-scoped requests
CREATE TABLE {{prefix}}human_request_context (
  context_id BIGINT NOT NULL PRIMARY KEY,
  request_id BIGINT NOT NULL,
  
  context_type VARCHAR(64) NOT NULL,
  -- ENUM: 'thread_artifact' | 'code_excerpt' | 'schema_def' | 'decision_point'
  
  content TEXT NOT NULL,
  -- The actual context content
  
  source_artifact_path VARCHAR(512),
  -- Path to referenced file/artifact
  
  source_line_range VARCHAR(64),
  -- Line numbers or section reference
  
  created_ymdhis BIGINT NOT NULL
);

CREATE INDEX idx_request_context ON {{prefix}}human_request_context(request_id);

-- Detailed responses with human and actor attribution
CREATE TABLE {{prefix}}human_request_responses (
  response_id BIGINT NOT NULL PRIMARY KEY,
  request_id BIGINT NOT NULL,
  
  auth_user_id BIGINT NOT NULL,
  -- Human user who responded
  
  actor_id BIGINT NOT NULL,
  -- Which supporting actor they used
  
  response_type VARCHAR(64) NOT NULL,
  -- ENUM: 'answer' | 'decision' | 'clarification' | 'escalation'
  
  response_text TEXT NOT NULL,
  reasoning TEXT,
  
  -- Decision/approval specific
  decision VARCHAR(64),
  -- ENUM: 'approved' | 'rejected' | 'needs_revision' | 'deferred'
  
  conditions TEXT,
  -- If decision = needs_revision
  
  response_ymdhis BIGINT NOT NULL,
  
  -- Audit
  is_deleted TINYINT DEFAULT 0,
  deleted_ymdhis BIGINT DEFAULT 0
);

CREATE INDEX idx_response_request ON {{prefix}}human_request_responses(request_id);
CREATE INDEX idx_response_user ON {{prefix}}human_request_responses(auth_user_id, response_ymdhis DESC);

-- LILITH Audit: Data Model PRD (02_data_model.md)
-- Accuracy Score: 92 - Approved with minor corrections
-- Key corrections applied:
--   1. Fixed file_path_from_root leading slash in PRD
--   2. Updated lupo_votes table with missing fields (vote_type, reason_text, reason_code, is_current)
--   3. Fixed field naming consistency (target_type/target_id/cast_by_actor_id)
--   4. Added missing table definitions (evidence, followers, context_map)
-- All changes aligned with 01_core_identity.md PRD requirements






