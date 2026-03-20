-- Install schema for Lupopedia 4.0.x. Single upgrade path: Crafty Syntax 3.7.5 -> Lupopedia 4.0.x only.
-- No Lupopedia->Lupopedia upgrade until 4.1.0. All schema for 4.0.x is in this file.
-- No Crafty Syntax logic, no migration, no DROP TABLE.
SET @now = 20260224000000;

-- ACTOR PRIMARY KEY DOCTRINE (v4.0.58): actor_name is primary; actor_id is unique secondary.
CREATE TABLE lupo_actors (
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
  actor_root_path varchar(512) DEFAULT 'actors/{actor_id}',
  workspace_path varchar(255) NULL DEFAULT NULL,
  php_namespace varchar(120) NULL DEFAULT NULL,
  who_json_sync_status varchar(64) DEFAULT 'pending',
  last_sync_ymdhis bigint DEFAULT 0,
  PRIMARY KEY (actor_name)
);

CREATE UNIQUE INDEX lupo_actors_unique_actor_id ON lupo_actors (actor_id);
CREATE UNIQUE INDEX lupo_actors_unique_slug ON lupo_actors (slug);
CREATE INDEX lupo_actors_idx_actor_type ON lupo_actors (actor_type);
CREATE INDEX lupo_actors_idx_is_active ON lupo_actors (is_active);
CREATE INDEX lupo_actors_idx_created_ymdhis ON lupo_actors (created_ymdhis);
CREATE INDEX lupo_actors_idx_workspace_path ON lupo_actors (workspace_path);
CREATE INDEX lupo_actors_idx_php_namespace ON lupo_actors (php_namespace);
-- RESERVED ID DOCTRINE: actor_id is NOT AUTO_INCREMENT; application must supply explicit ID.
-- HUMAN ACTOR ID DOCTRINE: human actors must have actor_id >= 1000 (see HumanActorIdDoctrine.md). Allocate from lupo_registry_open.
-- ACTOR PRIMARY KEY DOCTRINE: actor_name is canonical; use ActorService::getActorByName / resolveActor.

CREATE TABLE lupo_banned_actors (
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

CREATE INDEX lupo_banned_actors_idx_actor_id ON lupo_banned_actors (actor_id);
CREATE INDEX lupo_banned_actors_idx_actor_name ON lupo_banned_actors (actor_name);
CREATE INDEX lupo_banned_actors_idx_ip_address ON lupo_banned_actors (ip_address);
CREATE INDEX lupo_banned_actors_idx_is_deleted ON lupo_banned_actors (is_deleted);
-- Banned actors: ANUBIS does not adopt orphans from these actor_ids. Single source of truth for bans.

CREATE TABLE lupo_bans_log (
  bans_log_id bigint NOT NULL AUTO_INCREMENT,
  actor_id bigint NOT NULL,
  uri varchar(1024) NOT NULL DEFAULT '',
  resolved_uri varchar(1024) NOT NULL DEFAULT '',
  ban_scope varchar(64) NOT NULL DEFAULT 'router',
  banned_ymdhis bigint NOT NULL,
  user_agent varchar(500) DEFAULT NULL,
  ip_address varchar(45) DEFAULT NULL,
  PRIMARY KEY (bans_log_id)
);

CREATE INDEX lupo_bans_log_idx_actor_id ON lupo_bans_log (actor_id);
CREATE INDEX lupo_bans_log_idx_banned_ymdhis ON lupo_bans_log (banned_ymdhis);
CREATE INDEX lupo_bans_log_idx_ban_scope ON lupo_bans_log (ban_scope);
-- Router Ban at Gate (4.0.18 T7): audit log for 403 events; lupo_log_ban_event() writes here.

CREATE TABLE lupo_actor_actions (
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

CREATE INDEX lupo_actor_actions_idx_actor ON lupo_actor_actions (actor_id);
CREATE INDEX lupo_actor_actions_idx_action_type ON lupo_actor_actions (action_type);
CREATE INDEX lupo_actor_actions_idx_entity ON lupo_actor_actions (entity_type, entity_id);

CREATE TABLE lupo_actor_capabilities (
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

CREATE UNIQUE INDEX lupo_actor_capabilities_unique_agent_domain_capability ON lupo_actor_capabilities (actor_id, domain_id, capability_key);
CREATE INDEX lupo_actor_capabilities_idx_agent_domain ON lupo_actor_capabilities (actor_id, domain_id);
CREATE INDEX lupo_actor_capabilities_idx_domain_id ON lupo_actor_capabilities (domain_id);
CREATE INDEX lupo_actor_capabilities_idx_capability_key ON lupo_actor_capabilities (capability_key);
CREATE INDEX lupo_actor_capabilities_idx_created_ymdhis ON lupo_actor_capabilities (created_ymdhis);
CREATE INDEX lupo_actor_capabilities_idx_updated_ymdhis ON lupo_actor_capabilities (updated_ymdhis);
CREATE INDEX lupo_actor_capabilities_idx_is_deleted ON lupo_actor_capabilities (is_deleted);

CREATE TABLE lupo_actor_channels (
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

CREATE UNIQUE INDEX lupo_actor_channels_unq_actor_channel ON lupo_actor_channels (actor_id, channel_id);
CREATE INDEX lupo_actor_channels_idx_actor ON lupo_actor_channels (actor_id);
CREATE INDEX lupo_actor_channels_idx_actor_name ON lupo_actor_channels (actor_name);
CREATE INDEX lupo_actor_channels_idx_channel ON lupo_actor_channels (channel_id);
CREATE INDEX lupo_actor_channels_idx_status ON lupo_actor_channels (status);
CREATE INDEX lupo_actor_channels_idx_created ON lupo_actor_channels (created_ymdhis);
CREATE INDEX lupo_actor_channels_idx_updated ON lupo_actor_channels (updated_ymdhis);
CREATE INDEX lupo_actor_channels_idx_deleted ON lupo_actor_channels (is_deleted);

CREATE TABLE lupo_actor_channel_roles (
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

CREATE INDEX lupo_actor_channel_roles_idx_actor_id ON lupo_actor_channel_roles (actor_id);
CREATE INDEX lupo_actor_channel_roles_idx_actor_name ON lupo_actor_channel_roles (actor_name);
CREATE INDEX lupo_actor_channel_roles_idx_channel_id ON lupo_actor_channel_roles (channel_id);
CREATE INDEX lupo_actor_channel_roles_idx_role_key ON lupo_actor_channel_roles (role_key);
CREATE INDEX lupo_actor_channel_roles_idx_protocol_completion_status ON lupo_actor_channel_roles (protocol_completion_status);
CREATE INDEX lupo_actor_channel_roles_idx_join_sequence_step ON lupo_actor_channel_roles (join_sequence_step);
CREATE INDEX lupo_actor_channel_roles_idx_protocol_version ON lupo_actor_channel_roles (protocol_version);

CREATE TABLE lupo_actor_collections (
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

CREATE INDEX lupo_actor_collections_idx_actor ON lupo_actor_collections (actor_id);
CREATE INDEX lupo_actor_collections_idx_collection ON lupo_actor_collections (collection_id);
CREATE INDEX lupo_actor_collections_idx_access_level ON lupo_actor_collections (access_level);
CREATE INDEX lupo_actor_collections_idx_created_ymdhis ON lupo_actor_collections (created_ymdhis);
CREATE INDEX lupo_actor_collections_idx_is_deleted ON lupo_actor_collections (is_deleted);
CREATE INDEX lupo_actor_collections_idx_identity_signature ON lupo_actor_collections (identity_signature);
CREATE INDEX lupo_actor_collections_idx_trust_level ON lupo_actor_collections (trust_level);

CREATE TABLE lupo_actor_conflicts (
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

CREATE INDEX lupo_actor_conflicts_idx_agent_a ON lupo_actor_conflicts (actor_a_id);
CREATE INDEX lupo_actor_conflicts_idx_agent_b ON lupo_actor_conflicts (actor_b_id);
CREATE INDEX lupo_actor_conflicts_idx_status ON lupo_actor_conflicts (resolution_status);
CREATE INDEX lupo_actor_conflicts_idx_domain ON lupo_actor_conflicts (domain_id);
CREATE INDEX lupo_actor_conflicts_idx_severity ON lupo_actor_conflicts (severity);
CREATE INDEX lupo_actor_conflicts_idx_created ON lupo_actor_conflicts (created_ymdhis);
CREATE INDEX lupo_actor_conflicts_idx_updated ON lupo_actor_conflicts (updated_ymdhis);
CREATE INDEX lupo_actor_conflicts_idx_deleted ON lupo_actor_conflicts (is_deleted);
CREATE INDEX lupo_actor_conflicts_idx_resolved_ymdhis ON lupo_actor_conflicts (resolved_ymdhis);
CREATE INDEX lupo_actor_conflicts_idx_agent_pair ON lupo_actor_conflicts (actor_a_id, actor_b_id);
CREATE INDEX lupo_actor_conflicts_idx_conflict_type ON lupo_actor_conflicts (conflict_type);

CREATE TABLE lupo_actor_departments (
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

CREATE INDEX lupo_actor_departments_idx_actor ON lupo_actor_departments (actor_id);
CREATE INDEX lupo_actor_departments_idx_department ON lupo_actor_departments (department_id);

-- Actor application folder tracking (doctrine: /uploads/actors/{actor_id}/apps/ with skills, assets, manifest.json)
CREATE TABLE lupo_actor_apps (
  actor_app_id bigint NOT NULL,
  actor_id bigint NOT NULL,
  apps_path varchar(512) NOT NULL DEFAULT '',
  updated_ymdhis bigint NOT NULL DEFAULT 0,
  PRIMARY KEY (actor_app_id)
);

CREATE UNIQUE INDEX lupo_actor_apps_unq_actor ON lupo_actor_apps (actor_id);
CREATE INDEX lupo_actor_apps_idx_updated ON lupo_actor_apps (updated_ymdhis);

CREATE TABLE lupo_actor_edges (
  actor_edge_id bigint NOT NULL,
  domain_id bigint NOT NULL,
  source_actor_id bigint NOT NULL,
  target_actor_id bigint NOT NULL,
  edge_type varchar(100) NOT NULL,
  weight float DEFAULT '1',
  properties text,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint DEFAULT NULL,
  is_deleted tinyint NOT NULL DEFAULT '0',
  deleted_ymdhis bigint DEFAULT NULL,
  PRIMARY KEY (actor_edge_id)
);

CREATE UNIQUE INDEX lupo_actor_edges_unique_agent_edge ON lupo_actor_edges (domain_id, source_actor_id, target_actor_id, edge_type);
CREATE INDEX lupo_actor_edges_idx_domain_id ON lupo_actor_edges (domain_id);
CREATE INDEX lupo_actor_edges_idx_source_agent ON lupo_actor_edges (source_actor_id);
CREATE INDEX lupo_actor_edges_idx_target_agent ON lupo_actor_edges (target_actor_id);
CREATE INDEX lupo_actor_edges_idx_edge_type ON lupo_actor_edges (edge_type);
CREATE INDEX lupo_actor_edges_idx_source_target ON lupo_actor_edges (source_actor_id, target_actor_id);
CREATE INDEX lupo_actor_edges_idx_created_ymdhis ON lupo_actor_edges (created_ymdhis);
CREATE INDEX lupo_actor_edges_idx_updated_ymdhis ON lupo_actor_edges (updated_ymdhis);
CREATE INDEX lupo_actor_edges_idx_is_deleted ON lupo_actor_edges (is_deleted);
CREATE INDEX lupo_actor_edges_idx_edge_source_relationship ON lupo_actor_edges (source_actor_id, edge_type);
CREATE INDEX lupo_actor_edges_idx_edge_target_relationship ON lupo_actor_edges (target_actor_id, edge_type);

CREATE TABLE lupo_actor_traits (
  actor_trait_id bigint NOT NULL,
  actor_id bigint NOT NULL,
  trait_key varchar(128) NOT NULL,
  trait_value varchar(512) DEFAULT NULL,
  federation_node_id bigint NOT NULL DEFAULT 1,
  created_by_actor_id bigint DEFAULT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint DEFAULT NULL,
  is_deleted tinyint NOT NULL DEFAULT 0,
  deleted_ymdhis bigint DEFAULT NULL,
  metadata text DEFAULT NULL,
  PRIMARY KEY (actor_trait_id)
);

CREATE INDEX lupo_actor_traits_idx_actor ON lupo_actor_traits (actor_id);
CREATE INDEX lupo_actor_traits_idx_actor_key ON lupo_actor_traits (actor_id, trait_key);
CREATE INDEX lupo_actor_traits_idx_trait_key ON lupo_actor_traits (trait_key);
CREATE INDEX lupo_actor_traits_idx_federation ON lupo_actor_traits (federation_node_id);
CREATE INDEX lupo_actor_traits_idx_is_deleted ON lupo_actor_traits (is_deleted);

-- Old actor events table removed in v4.0.55 - consolidated into lupo_unified_log

CREATE TABLE lupo_actor_handshakes (
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

CREATE INDEX lupo_actor_handshakes_idx_actor_id ON lupo_actor_handshakes (actor_id);
CREATE INDEX lupo_actor_handshakes_idx_is_deleted ON lupo_actor_handshakes (is_deleted);
CREATE INDEX lupo_actor_handshakes_idx_utc_timestamp ON lupo_actor_handshakes (`utc_timestamp`);

-- Bayesian Decision Tracking tables (v4.0.77)
-- Scope: every decision/edge/influence is scoped by channel_id and project_id (required).
CREATE TABLE lupo_decisions (
  decision_id bigint NOT NULL,
  actor_id bigint NOT NULL,
  channel_id bigint NOT NULL,
  project_id bigint NOT NULL,
  session_id bigint NOT NULL,
  root_decision_id bigint DEFAULT NULL,
  parent_decision_id bigint DEFAULT NULL,
  depth int NOT NULL DEFAULT 0,
  decision_type varchar(50) NOT NULL,
  decision_status varchar(32) NOT NULL,
  decision_key varchar(255) DEFAULT NULL,
  probability decimal(4,3) DEFAULT NULL,
  probability_lower decimal(4,3) DEFAULT NULL,
  probability_upper decimal(4,3) DEFAULT NULL,
  probability_model varchar(64) DEFAULT NULL,
  state_snapshot_id bigint DEFAULT NULL,
  federation_node_id bigint NOT NULL DEFAULT 1,
  origin_decision_id bigint DEFAULT NULL,
  created_ymdhis bigint NOT NULL,
  created_by_actor_id bigint NOT NULL,
  updated_ymdhis bigint DEFAULT NULL,
  abandoned_ymdhis bigint DEFAULT NULL,
  pruned_ymdhis bigint DEFAULT NULL,
  is_deleted tinyint NOT NULL DEFAULT 0,
  deleted_ymdhis bigint DEFAULT NULL,
  PRIMARY KEY (decision_id)
);

CREATE INDEX lupo_decisions_idx_actor_time ON lupo_decisions (actor_id, created_ymdhis);
CREATE INDEX lupo_decisions_idx_session_time ON lupo_decisions (session_id, created_ymdhis);
CREATE INDEX lupo_decisions_idx_root_depth ON lupo_decisions (root_decision_id, depth);
CREATE INDEX lupo_decisions_idx_parent ON lupo_decisions (parent_decision_id);
CREATE INDEX lupo_decisions_idx_status ON lupo_decisions (decision_status);
CREATE INDEX lupo_decisions_idx_probability ON lupo_decisions (probability);
CREATE INDEX lupo_decisions_idx_federation ON lupo_decisions (federation_node_id);
CREATE INDEX lupo_decisions_idx_channel_time ON lupo_decisions (channel_id, created_ymdhis);
CREATE INDEX lupo_decisions_idx_project_time ON lupo_decisions (project_id, created_ymdhis);
CREATE INDEX lupo_decisions_idx_channel_project_time ON lupo_decisions (channel_id, project_id, created_ymdhis);

CREATE TABLE lupo_decision_edges (
  source_decision_id bigint NOT NULL,
  target_decision_id bigint NOT NULL,
  edge_type varchar(50) NOT NULL,
  channel_id bigint NOT NULL,
  project_id bigint NOT NULL,
  probability decimal(4,3) DEFAULT NULL,
  session_id bigint DEFAULT NULL,
  federation_node_id bigint NOT NULL DEFAULT 1,
  created_ymdhis bigint NOT NULL,
  created_by_actor_id bigint NOT NULL,
  is_deleted tinyint NOT NULL DEFAULT 0,
  deleted_ymdhis bigint DEFAULT NULL,
  PRIMARY KEY (source_decision_id, target_decision_id, edge_type)
);

CREATE INDEX lupo_decision_edges_idx_target ON lupo_decision_edges (target_decision_id);
CREATE INDEX lupo_decision_edges_idx_probability ON lupo_decision_edges (probability);
CREATE INDEX lupo_decision_edges_idx_session ON lupo_decision_edges (session_id);
CREATE INDEX lupo_decision_edges_idx_channel ON lupo_decision_edges (channel_id);
CREATE INDEX lupo_decision_edges_idx_project ON lupo_decision_edges (project_id);

CREATE TABLE lupo_decision_evidence (
  decision_evidence_id bigint NOT NULL,
  decision_id bigint NOT NULL,
  channel_id bigint NOT NULL,
  project_id bigint DEFAULT 0,
  evidence_type varchar(64) NOT NULL,
  evidence_source varchar(255) NOT NULL,
  evidence_value text,
  likelihood decimal(10,6) DEFAULT NULL,
  confidence decimal(10,6) DEFAULT NULL,
  federation_node_id bigint NOT NULL DEFAULT 1,
  status varchar(32) NOT NULL DEFAULT 'active',
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL,
  is_deleted tinyint NOT NULL DEFAULT 0,
  deleted_ymdhis bigint DEFAULT NULL,
  PRIMARY KEY (decision_evidence_id)
);

CREATE INDEX lupo_decision_evidence_idx_decision ON lupo_decision_evidence (decision_id);
CREATE INDEX lupo_decision_evidence_idx_channel ON lupo_decision_evidence (channel_id);
CREATE INDEX lupo_decision_evidence_idx_status ON lupo_decision_evidence (status);
CREATE INDEX lupo_decision_evidence_idx_is_deleted ON lupo_decision_evidence (is_deleted);

CREATE TABLE lupo_decision_influences (
  decision_id bigint NOT NULL,
  influencing_decision_id bigint NOT NULL,
  influence_type varchar(50) NOT NULL,
  channel_id bigint NOT NULL,
  project_id bigint NOT NULL,
  weight decimal(4,3) DEFAULT NULL,
  session_id bigint DEFAULT NULL,
  federation_node_id bigint NOT NULL DEFAULT 1,
  created_ymdhis bigint NOT NULL,
  created_by_actor_id bigint NOT NULL,
  is_deleted tinyint NOT NULL DEFAULT 0,
  deleted_ymdhis bigint DEFAULT NULL,
  PRIMARY KEY (decision_id, influencing_decision_id, influence_type)
);

CREATE INDEX lupo_decision_influences_idx_influencing ON lupo_decision_influences (influencing_decision_id);
CREATE INDEX lupo_decision_influences_idx_weight ON lupo_decision_influences (weight);
CREATE INDEX lupo_decision_influences_idx_channel ON lupo_decision_influences (channel_id);
CREATE INDEX lupo_decision_influences_idx_project ON lupo_decision_influences (project_id);

-- Consolidated metadata table replacing lupo_actor_meta, lupo_actor_properties, lupo_agent_properties
-- 4.0.68: LUPOPEDIA HEADERS — added channel_id, parent_metadata_id, class_name for channel-scoped and hierarchical metadata
CREATE TABLE lupo_metadata (
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

CREATE UNIQUE INDEX lupo_metadata_unique_entity_domain_property ON lupo_metadata (entity_type, entity_id, domain_id, property_key);
CREATE INDEX lupo_metadata_idx_entity ON lupo_metadata (entity_type, entity_id);
CREATE INDEX lupo_metadata_idx_domain ON lupo_metadata (domain_id);
CREATE INDEX lupo_metadata_idx_meta_type ON lupo_metadata (meta_type);
CREATE INDEX lupo_metadata_idx_property_key ON lupo_metadata (property_key);
CREATE INDEX lupo_metadata_idx_created_ymdhis ON lupo_metadata (created_ymdhis);
CREATE INDEX lupo_metadata_idx_updated_ymdhis ON lupo_metadata (updated_ymdhis);
CREATE INDEX lupo_metadata_idx_is_deleted ON lupo_metadata (is_deleted);
CREATE INDEX lupo_metadata_idx_channel_id ON lupo_metadata (channel_id);
CREATE INDEX lupo_metadata_idx_parent_metadata_id ON lupo_metadata (parent_metadata_id);
CREATE INDEX lupo_metadata_idx_class_name ON lupo_metadata (class_name);
CREATE INDEX lupo_metadata_idx_entity_deleted ON lupo_metadata (entity_type, entity_id, is_deleted);
CREATE INDEX lupo_metadata_idx_channel_deleted ON lupo_metadata (channel_id, is_deleted);
CREATE INDEX lupo_metadata_idx_parent_deleted ON lupo_metadata (parent_metadata_id, is_deleted);
CREATE INDEX lupo_metadata_idx_meta_type_deleted ON lupo_metadata (meta_type, is_deleted);
CREATE INDEX lupo_metadata_idx_class_deleted ON lupo_metadata (class_name, is_deleted);

CREATE TABLE lupo_actor_moods (
  actor_id bigint NOT NULL,
  mood_r tinyint NOT NULL,
  mood_g tinyint NOT NULL,
  mood_b tinyint NOT NULL,
  mood_framework varchar(32) NOT NULL DEFAULT 'western_analytical',
  timestamp_utc bigint NOT NULL
);

CREATE TABLE lupo_actor_reply_templates (
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

CREATE UNIQUE INDEX lupo_actor_reply_templates_unq_actor_template_key ON lupo_actor_reply_templates (actor_id, template_key);
CREATE INDEX lupo_actor_reply_templates_idx_actor ON lupo_actor_reply_templates (actor_id);
CREATE INDEX lupo_actor_reply_templates_idx_key ON lupo_actor_reply_templates (template_key);
CREATE INDEX lupo_actor_reply_templates_idx_created ON lupo_actor_reply_templates (created_ymdhis);
CREATE INDEX lupo_actor_reply_templates_idx_updated ON lupo_actor_reply_templates (updated_ymdhis);
CREATE INDEX lupo_actor_reply_templates_idx_deleted ON lupo_actor_reply_templates (is_deleted);
CREATE INDEX lupo_actor_reply_templates_idx_usage_context ON lupo_actor_reply_templates (usage_context);

CREATE TABLE lupo_agents (
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
  pono_score decimal(3,2) DEFAULT '1.00',
  pilau_score decimal(3,2) DEFAULT '0.00',
  kapakai_score decimal(3,2) DEFAULT '0.50',
  kapu_active tinyint DEFAULT '0',
  kapu_until bigint DEFAULT NULL,
  kapu_reason varchar(500) DEFAULT NULL,
  kapu_consent_given tinyint DEFAULT '0',
  kapu_appeal_pending tinyint DEFAULT '0',
  PRIMARY KEY (agent_id)
);

CREATE UNIQUE INDEX lupo_agents_unique_agent_key ON lupo_agents (agent_key);
CREATE INDEX lupo_agents_idx_api_key_id ON lupo_agents (api_key_id);
CREATE INDEX lupo_agents_idx_is_global_authority ON lupo_agents (is_global_authority);
CREATE INDEX lupo_agents_idx_created_ymdhis ON lupo_agents (created_ymdhis);
CREATE INDEX lupo_agents_idx_updated_ymdhis ON lupo_agents (updated_ymdhis);
CREATE INDEX lupo_agents_idx_is_deleted ON lupo_agents (is_deleted);

CREATE TABLE lupo_agent_context_snapshots (
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

CREATE INDEX lupo_agent_context_snapshots_idx_session_agent ON lupo_agent_context_snapshots (session_id, actor_id);
CREATE INDEX lupo_agent_context_snapshots_idx_created ON lupo_agent_context_snapshots (created_ymdhis);
CREATE INDEX lupo_agent_context_snapshots_idx_type_purpose ON lupo_agent_context_snapshots (snapshot_type, snapshot_purpose);
CREATE INDEX lupo_agent_context_snapshots_idx_retention ON lupo_agent_context_snapshots (retention_policy, expires_ymdhis);
CREATE INDEX lupo_agent_context_snapshots_idx_turn ON lupo_agent_context_snapshots (session_id, conversation_turn);
CREATE INDEX lupo_agent_context_snapshots_idx_related_tool ON lupo_agent_context_snapshots (related_tool_call_id);
CREATE INDEX lupo_agent_context_snapshots_idx_parent ON lupo_agent_context_snapshots (parent_snapshot_id);

CREATE TABLE lupo_agent_dependencies (
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

CREATE INDEX lupo_agent_dependencies_idx_agent_id ON lupo_agent_dependencies (agent_id);
CREATE INDEX lupo_agent_dependencies_idx_depends_on ON lupo_agent_dependencies (depends_on_agent_id);

CREATE TABLE lupo_agent_experiences (
  link_id char(26) NOT NULL,
  agent_id bigint NOT NULL,
  star_id char(26) NOT NULL,
  intensity decimal(3,2) DEFAULT NULL,
  context_id bigint DEFAULT NULL,
  observed_ymdhis bigint DEFAULT NULL,
  expressed_as_rgb char(6) DEFAULT NULL,
  PRIMARY KEY (link_id)
);

CREATE INDEX lupo_agent_experiences_idx_agent ON lupo_agent_experiences (agent_id);
CREATE INDEX lupo_agent_experiences_idx_star ON lupo_agent_experiences (star_id);
CREATE INDEX lupo_agent_experiences_idx_context ON lupo_agent_experiences (context_id);

CREATE TABLE lupo_agent_external_events (
  external_event_id bigint NOT NULL,
  agent_name varchar(255) NOT NULL,
  source_system varchar(255) NOT NULL,
  event_type varchar(50) NOT NULL,
  event_payload_json json DEFAULT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  PRIMARY KEY (external_event_id)
);


CREATE TABLE lupo_agent_faucets (
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

CREATE INDEX lupo_agent_faucets_idx_agent ON lupo_agent_faucets (actor_id);
CREATE INDEX lupo_agent_faucets_idx_slug ON lupo_agent_faucets (slug);
CREATE INDEX lupo_agent_faucets_idx_faucet_class ON lupo_agent_faucets (faucet_class);
CREATE INDEX lupo_agent_faucets_idx_domain ON lupo_agent_faucets (domain_id);
CREATE INDEX lupo_agent_faucets_idx_default ON lupo_agent_faucets (is_default);

CREATE TABLE lupo_agent_faucet_credentials (
  agent_faucet_credential_id int NOT NULL,
  faucet_id bigint NOT NULL,
  provider varchar(64) NOT NULL,
  api_key varbinary(512) NOT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL,
  PRIMARY KEY (agent_faucet_credential_id)
);

CREATE INDEX lupo_agent_faucet_credentials_idx_faucet ON lupo_agent_faucet_credentials (faucet_id);

CREATE TABLE lupo_agent_files (
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

CREATE INDEX lupo_agent_files_idx_agent_id ON lupo_agent_files (agent_id);
CREATE INDEX lupo_agent_files_idx_file_type ON lupo_agent_files (file_type);
CREATE INDEX lupo_agent_files_idx_file_hash ON lupo_agent_files (file_hash);
CREATE INDEX lupo_agent_files_idx_is_deleted ON lupo_agent_files (is_deleted);
CREATE INDEX lupo_agent_files_idx_upload_ymdhis ON lupo_agent_files (upload_ymdhis);

CREATE TABLE lupo_agent_heartbeats (
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

CREATE INDEX lupo_agent_heartbeats_idx_agent_slug ON lupo_agent_heartbeats (agent_slug);
CREATE INDEX lupo_agent_heartbeats_idx_last_heartbeat_ymdhis ON lupo_agent_heartbeats (last_heartbeat_ymdhis);
CREATE INDEX lupo_agent_heartbeats_idx_created_ymdhis ON lupo_agent_heartbeats (created_ymdhis);
CREATE INDEX lupo_agent_heartbeats_idx_is_deleted ON lupo_agent_heartbeats (is_deleted);


CREATE TABLE lupo_agent_tool_calls (
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

CREATE INDEX lupo_agent_tool_calls_idx_agent_created ON lupo_agent_tool_calls (agent_id, created_ymdhis);
CREATE INDEX lupo_agent_tool_calls_idx_agent ON lupo_agent_tool_calls (agent_id);
CREATE INDEX lupo_agent_tool_calls_idx_faucet ON lupo_agent_tool_calls (faucet_id);
CREATE INDEX lupo_agent_tool_calls_idx_domain ON lupo_agent_tool_calls (domain_id);
CREATE INDEX lupo_agent_tool_calls_idx_model ON lupo_agent_tool_calls (model_name);
CREATE INDEX lupo_agent_tool_calls_idx_provider ON lupo_agent_tool_calls (provider);
CREATE INDEX lupo_agent_tool_calls_idx_parent ON lupo_agent_tool_calls (parent_call_id);
CREATE INDEX lupo_agent_tool_calls_idx_thread ON lupo_agent_tool_calls (thread_id);
CREATE INDEX lupo_agent_tool_calls_idx_message ON lupo_agent_tool_calls (message_id);

CREATE TABLE lupo_agent_versions (
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

CREATE INDEX lupo_agent_versions_agent_id ON lupo_agent_versions (agent_id);
CREATE INDEX lupo_agent_versions_version_label ON lupo_agent_versions (version_label);
CREATE INDEX lupo_agent_versions_semver_major ON lupo_agent_versions (semver_major, semver_minor, semver_patch);

-- lupo_aliases moved to future_features_lupopedia.sql (v4.0.57)

CREATE TABLE lupo_analytics_campaign_vars (
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


-- PATHS/VISITS doctrine (4.0.68): visits = raw events; paths = aggregated flows. gc.php aggregates visits -> paths.
-- lupo_visits: raw per-event navigation logs (high-volume, append-only). is_processed set by gc.php when aggregated.
CREATE TABLE lupo_visits (
  visit_id bigint NOT NULL AUTO_INCREMENT,
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
  PRIMARY KEY (visit_id)
);
CREATE INDEX lupo_visits_idx_session ON lupo_visits (session_id);
CREATE INDEX lupo_visits_idx_actor ON lupo_visits (actor_id);
CREATE INDEX lupo_visits_idx_created ON lupo_visits (created_ymdhis);
CREATE INDEX lupo_visits_idx_is_processed ON lupo_visits (is_processed);
CREATE INDEX lupo_visits_idx_is_deleted ON lupo_visits (is_deleted);
CREATE INDEX lupo_visits_idx_enter_exit ON lupo_visits (entercontentid, exitcontentid);





CREATE TABLE lupo_anubis_log (
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

CREATE INDEX lupo_anubis_log_idx_event_type ON lupo_anubis_log (event_type);
CREATE INDEX lupo_anubis_log_idx_source_id ON lupo_anubis_log (source_id);
CREATE INDEX lupo_anubis_log_idx_source_table ON lupo_anubis_log (source_table);
CREATE INDEX lupo_anubis_log_idx_file_path ON lupo_anubis_log (file_path_from_root);
CREATE INDEX lupo_anubis_log_idx_assigned_actor ON lupo_anubis_log (assigned_to_actor_id);
CREATE INDEX lupo_anubis_log_idx_status ON lupo_anubis_log (status);
CREATE INDEX lupo_anubis_log_idx_created ON lupo_anubis_log (created_ymdhis);

CREATE TABLE lupo_anubis_events (
  anubis_event_id bigint NOT NULL,
  event_type varchar(64) NOT NULL,
  table_name varchar(255) NOT NULL,
  row_id bigint NOT NULL,
  created_ymdhis bigint NOT NULL,
  agent varchar(255) NOT NULL,
  details_json text NOT NULL,
  PRIMARY KEY (anubis_event_id)
);


-- lupo_anubis_orphaned moved to future_features_lupopedia.sql (v4.0.57)

CREATE TABLE lupo_anubis_redirects (
  anubis_redirect_id bigint NOT NULL,
  table_name varchar(255) NOT NULL,
  old_id bigint NOT NULL,
  new_id bigint NOT NULL,
  created_ymdhis bigint NOT NULL,
  updated_ymdhis bigint NOT NULL,
  agent varchar(255) NOT NULL,
  PRIMARY KEY (anubis_redirect_id)
);

-- ANUBIS queue tables (required by install Activations Block for actor_id 19; previously in migrations/anubis_queue_tables_4.0.53.sql).
CREATE TABLE lupo_anubis_queue (
  queue_id bigint NOT NULL AUTO_INCREMENT,
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
CREATE INDEX lupo_anubis_queue_idx_detected ON lupo_anubis_queue (detected_utc);
CREATE INDEX lupo_anubis_queue_idx_file_path ON lupo_anubis_queue (file_path);
CREATE INDEX lupo_anubis_queue_idx_status_priority ON lupo_anubis_queue (status, priority);
CREATE UNIQUE INDEX lupo_anubis_queue_uniq_file_hash ON lupo_anubis_queue (file_hash);

CREATE TABLE lupo_anubis_processing_log (
  log_id bigint NOT NULL AUTO_INCREMENT,
  queue_id bigint NOT NULL,
  file_path varchar(512) NOT NULL,
  action varchar(64) NOT NULL,
  details text,
  actor_id bigint DEFAULT NULL,
  created_utc bigint NOT NULL,
  PRIMARY KEY (log_id)
);
CREATE INDEX lupo_anubis_processing_log_idx_created ON lupo_anubis_processing_log (created_utc);
CREATE INDEX lupo_anubis_processing_log_idx_queue ON lupo_anubis_processing_log (queue_id);

CREATE TABLE lupo_anubis_recovery_attempts (
  attempt_id bigint NOT NULL AUTO_INCREMENT,
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
CREATE INDEX lupo_anubis_recovery_attempts_idx_queue_attempt ON lupo_anubis_recovery_attempts (queue_id, attempt_number);

CREATE TABLE lupo_anubis_quarantine (
  quarantine_id bigint NOT NULL AUTO_INCREMENT,
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
CREATE INDEX lupo_anubis_quarantine_idx_expires ON lupo_anubis_quarantine (expires_utc);
CREATE INDEX lupo_anubis_quarantine_idx_queue ON lupo_anubis_quarantine (queue_id);

-- 12-table install expansion v4.0.74: unified ANUBIS operations audit
CREATE TABLE lupo_anubis_operations (
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

CREATE TABLE lupo_api_clients (
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

CREATE UNIQUE INDEX lupo_api_clients_uq_client_key ON lupo_api_clients (client_key);
CREATE INDEX lupo_api_clients_idx_actor ON lupo_api_clients (actor_id);
CREATE INDEX lupo_api_clients_idx_active ON lupo_api_clients (is_active);
CREATE INDEX lupo_api_clients_idx_expires ON lupo_api_clients (expires_ymdhis);

CREATE TABLE lupo_api_rate_limits (
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

CREATE INDEX lupo_api_rate_limits_idx_token_window ON lupo_api_rate_limits (api_token_id, window_ymdhis);
CREATE INDEX lupo_api_rate_limits_idx_actor_window ON lupo_api_rate_limits (actor_id, window_ymdhis);
CREATE INDEX lupo_api_rate_limits_idx_ip_window ON lupo_api_rate_limits (ip_address, window_ymdhis);
CREATE INDEX lupo_api_rate_limits_idx_domain_window ON lupo_api_rate_limits (domain_id, window_ymdhis);
CREATE INDEX lupo_api_rate_limits_idx_endpoint ON lupo_api_rate_limits (endpoint);

CREATE TABLE lupo_api_tokens (
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

CREATE UNIQUE INDEX lupo_api_tokens_uq_token_key ON lupo_api_tokens (token_key);
CREATE INDEX lupo_api_tokens_idx_actor_active ON lupo_api_tokens (actor_id, is_active);
CREATE INDEX lupo_api_tokens_idx_domain ON lupo_api_tokens (domain_id);
CREATE INDEX lupo_api_tokens_idx_actor ON lupo_api_tokens (actor_id);
CREATE INDEX lupo_api_tokens_idx_active ON lupo_api_tokens (is_active);
CREATE INDEX lupo_api_tokens_idx_expires ON lupo_api_tokens (expires_ymdhis);
CREATE INDEX lupo_api_tokens_idx_last_used ON lupo_api_tokens (last_used_ymdhis);

CREATE TABLE lupo_api_token_logs (
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

CREATE INDEX lupo_api_token_logs_idx_token ON lupo_api_token_logs (api_token_id);
CREATE INDEX lupo_api_token_logs_idx_actor ON lupo_api_token_logs (actor_id);
CREATE INDEX lupo_api_token_logs_idx_domain_time ON lupo_api_token_logs (domain_id, request_ymdhis);
CREATE INDEX lupo_api_token_logs_idx_endpoint ON lupo_api_token_logs (endpoint);
CREATE INDEX lupo_api_token_logs_idx_status ON lupo_api_token_logs (status_code);

CREATE TABLE lupo_api_webhooks (
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

CREATE INDEX lupo_api_webhooks_idx_domain ON lupo_api_webhooks (domain_id);
CREATE INDEX lupo_api_webhooks_idx_actor ON lupo_api_webhooks (actor_id);
CREATE INDEX lupo_api_webhooks_idx_module ON lupo_api_webhooks (module_id);
CREATE INDEX lupo_api_webhooks_idx_active ON lupo_api_webhooks (is_active);
CREATE INDEX lupo_api_webhooks_idx_expires ON lupo_api_webhooks (expires_ymdhis);

CREATE TABLE lupo_artifacts (
  artifact_id bigint NOT NULL,
  actor_id bigint NOT NULL,
  federation_node_id bigint NOT NULL DEFAULT 1,
  `utc_timestamp` bigint NOT NULL,
  entity_type varchar(64) NOT NULL,
  content text NOT NULL,
  metadata json DEFAULT NULL,
  channel_id bigint DEFAULT NULL,
  artifact_kind varchar(50) DEFAULT NULL,
  file_path_from_root varchar(500) DEFAULT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL DEFAULT 0,
  is_deleted tinyint NOT NULL DEFAULT '0',
  deleted_ymdhis bigint DEFAULT NULL,
  PRIMARY KEY (artifact_id)
);

CREATE INDEX lupo_artifacts_idx_entity_channel ON lupo_artifacts (entity_type, channel_id);
CREATE INDEX lupo_artifacts_idx_file_path ON lupo_artifacts (file_path_from_root);

CREATE TABLE lupo_artifact_chunks (
  artifact_chunk_id bigint NOT NULL,
  artifact_id bigint NOT NULL,
  chunk_index int NOT NULL,
  chunk_content mediumtext NOT NULL,
  token_count int DEFAULT NULL,
  metadata json DEFAULT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL DEFAULT 0,
  is_deleted tinyint NOT NULL DEFAULT '0',
  deleted_ymdhis bigint DEFAULT NULL,
  PRIMARY KEY (artifact_chunk_id)
);

CREATE UNIQUE INDEX lupo_artifact_chunks_art_chunk_unique ON lupo_artifact_chunks (artifact_id, chunk_index);
CREATE INDEX lupo_artifact_chunks_artifact_id ON lupo_artifact_chunks (artifact_id);


CREATE INDEX lupo_artifacts_idx_utc_timestamp ON lupo_artifacts (`utc_timestamp`);
CREATE INDEX lupo_artifacts_idx_actor_id ON lupo_artifacts (actor_id);
CREATE INDEX lupo_artifacts_idx_entity_type ON lupo_artifacts (entity_type);
CREATE INDEX lupo_artifacts_idx_is_deleted ON lupo_artifacts (is_deleted);

CREATE TABLE lupo_atoms (
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

CREATE INDEX lupo_atoms_idx_atom_name ON lupo_atoms (atom_name);
CREATE INDEX lupo_atoms_idx_context_id ON lupo_atoms (context_id);
CREATE INDEX lupo_atoms_idx_authoritative ON lupo_atoms (is_authoritative);
CREATE INDEX lupo_atoms_idx_atom_context ON lupo_atoms (atom_name, context_id);

-- 12-table install expansion v4.0.74 (directive 20260314): aliases for routing/redirects
CREATE TABLE lupo_aliases (
  alias_id bigint NOT NULL,
  slug varchar(255) NOT NULL,
  alias varchar(255) NOT NULL,
  alias_type varchar(50) DEFAULT 'semantic',
  created_ymdhis bigint NOT NULL DEFAULT 0,
  PRIMARY KEY (alias_id)
);
CREATE UNIQUE INDEX lupo_aliases_uniq_alias ON lupo_aliases (alias);
CREATE INDEX lupo_aliases_idx_slug ON lupo_aliases (slug);

CREATE TABLE lupo_audit_log (
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

CREATE INDEX lupo_audit_log_idx_entity ON lupo_audit_log (entity_type, entity_id);
CREATE INDEX lupo_audit_log_idx_event ON lupo_audit_log (event_type);
CREATE INDEX lupo_audit_log_idx_table ON lupo_audit_log (table_name, table_id);

-- 12-table install expansion v4.0.74: unified log (consolidated log types)
CREATE TABLE lupo_unified_log (
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
CREATE INDEX lupo_unified_log_idx_actor_log ON lupo_unified_log (actor_id, log_type);
CREATE INDEX lupo_unified_log_idx_channel_log ON lupo_unified_log (channel_id, log_type);
CREATE INDEX lupo_unified_log_idx_log_type_created ON lupo_unified_log (log_type, created_ymdhis);

-- Tracks applied one-time migrations (version, name, applied_ymdhis)
CREATE TABLE lupo_schema_migrations (
  schema_migration_id bigint NOT NULL,
  version varchar(64) NOT NULL,
  name varchar(255) NOT NULL,
  applied_ymdhis bigint NOT NULL DEFAULT 0,
  PRIMARY KEY (schema_migration_id)
);

CREATE UNIQUE INDEX lupo_schema_migrations_unq_version ON lupo_schema_migrations (version);
CREATE INDEX lupo_schema_migrations_idx_applied ON lupo_schema_migrations (applied_ymdhis);

CREATE TABLE lupo_auth_audit_log (
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

CREATE INDEX lupo_auth_audit_log_idx_user_id ON lupo_auth_audit_log (user_id);
CREATE INDEX lupo_auth_audit_log_idx_crafty_operator_id ON lupo_auth_audit_log (crafty_operator_id);
CREATE INDEX lupo_auth_audit_log_idx_event_type ON lupo_auth_audit_log (event_type);
CREATE INDEX lupo_auth_audit_log_idx_system_context ON lupo_auth_audit_log (system_context);
CREATE INDEX lupo_auth_audit_log_idx_success ON lupo_auth_audit_log (success);
CREATE INDEX lupo_auth_audit_log_idx_created_at ON lupo_auth_audit_log (created_at);

CREATE TABLE lupo_auth_providers (
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

CREATE UNIQUE INDEX lupo_auth_providers_unique_provider_name ON lupo_auth_providers (provider_name);

CREATE TABLE lupo_auth_users (
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
  PRIMARY KEY (auth_user_id)
);

CREATE UNIQUE INDEX lupo_auth_users_unique_username ON lupo_auth_users (username);
CREATE UNIQUE INDEX lupo_auth_users_unique_provider_user ON lupo_auth_users (auth_provider, provider_id);
CREATE INDEX lupo_auth_users_idx_email ON lupo_auth_users (email);
CREATE INDEX lupo_auth_users_idx_is_active ON lupo_auth_users (is_active);
CREATE INDEX lupo_auth_users_idx_is_deleted ON lupo_auth_users (is_deleted);
CREATE INDEX lupo_auth_users_idx_created_ymdhis ON lupo_auth_users (created_ymdhis);
CREATE INDEX lupo_auth_users_idx_updated_ymdhis ON lupo_auth_users (updated_ymdhis);
-- RESERVED ID DOCTRINE: auth_user_id is NOT AUTO_INCREMENT; application must supply explicit ID.

CREATE TABLE lupo_calibration_impacts (
  calibration_impact_id bigint NOT NULL,
  calibration_id bigint NOT NULL,
  impact_type varchar(64) NOT NULL,
  impact_measurement decimal(5,4) NOT NULL,
  measurement_method varchar(100) NOT NULL,
  before_metrics_json json DEFAULT NULL,
  after_metrics_json json DEFAULT NULL,
  observation_period_hours int DEFAULT '24',
  measured_ymdhis bigint NOT NULL,
  impact_version varchar(20) DEFAULT '3.0.0',
  PRIMARY KEY (calibration_impact_id)
);

CREATE INDEX lupo_calibration_impacts_idx_calibration_impact ON lupo_calibration_impacts (calibration_id, impact_type);
CREATE INDEX lupo_calibration_impacts_idx_impact_measurement ON lupo_calibration_impacts (impact_measurement);
CREATE INDEX lupo_calibration_impacts_idx_measurement_time ON lupo_calibration_impacts (measured_ymdhis);

CREATE TABLE lupo_channels (
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
  status_flag tinyint NOT NULL DEFAULT '1',
  end_ymdhis bigint DEFAULT NULL,
  duration_seconds int DEFAULT NULL,
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
  PRIMARY KEY (channel_id)
);

CREATE UNIQUE INDEX lupo_channels_unq_channel_key_per_node ON lupo_channels (channel_key, federation_node_id);
CREATE INDEX lupo_channels_idx_domain ON lupo_channels (federation_node_id);
CREATE INDEX lupo_channels_idx_channel_key ON lupo_channels (channel_key);
CREATE INDEX lupo_channels_idx_status ON lupo_channels (status_flag);
CREATE INDEX lupo_channels_idx_dates ON lupo_channels (end_ymdhis);
CREATE INDEX lupo_channels_idx_awareness_version ON lupo_channels (awareness_version);
CREATE INDEX lupo_channels_idx_project_id ON lupo_channels (project_id);

CREATE TABLE lupo_channel_boot_detail (
  detail_id bigint NOT NULL,
  boot_id bigint NOT NULL,
  channel_id bigint NOT NULL,
  detail_start_time bigint,
  detail_end_time bigint,
  load_status varchar(64) NOT NULL DEFAULT 'started',
  content_items_loaded int NOT NULL DEFAULT 0,
  total_content_items int NOT NULL DEFAULT 0,
  load_duration_ms int,
  error_message text,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  PRIMARY KEY (detail_id)
);

CREATE INDEX lupo_channel_boot_detail_idx_boot_channel ON lupo_channel_boot_detail (boot_id, channel_id);
CREATE INDEX lupo_channel_boot_detail_idx_load_status_time ON lupo_channel_boot_detail (load_status, detail_start_time);
CREATE INDEX lupo_channel_boot_detail_fk_boot_detail_channel ON lupo_channel_boot_detail (channel_id);

CREATE TABLE lupo_channel_boot_lifecycle (
  lifecycle_id bigint NOT NULL AUTO_INCREMENT,
  channel_id bigint NOT NULL,
  actor_id bigint NOT NULL,
  session_id varchar(64) NOT NULL,
  lifecycle_start_time bigint NOT NULL,
  lifecycle_end_time bigint DEFAULT NULL,
  lifecycle_status varchar(64) NOT NULL DEFAULT 'started',
  lifecycle_type varchar(64) NOT NULL,
  total_channels int NOT NULL DEFAULT 0,
  channels_processed int NOT NULL DEFAULT 0,
  channels_successful int NOT NULL DEFAULT 0,
  channels_failed int NOT NULL DEFAULT 0,
  lifecycle_duration_ms int DEFAULT NULL,
  error_details json DEFAULT NULL,
  performance_metrics json DEFAULT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  PRIMARY KEY (lifecycle_id)
);

CREATE INDEX lupo_channel_boot_lifecycle_fk_lifecycle_channel ON lupo_channel_boot_lifecycle (channel_id);
CREATE INDEX lupo_channel_boot_lifecycle_idx_actor_session ON lupo_channel_boot_lifecycle (actor_id, session_id);
CREATE INDEX lupo_channel_boot_lifecycle_idx_status_time ON lupo_channel_boot_lifecycle (lifecycle_status, lifecycle_start_time);
CREATE INDEX lupo_channel_boot_lifecycle_idx_type_time ON lupo_channel_boot_lifecycle (lifecycle_type, lifecycle_start_time);

CREATE TABLE lupo_channel_boot_detail_lifecycle (
  detail_lifecycle_id bigint NOT NULL AUTO_INCREMENT,
  lifecycle_id bigint NOT NULL,
  channel_id bigint NOT NULL,
  detail_start_time bigint NOT NULL,
  detail_end_time bigint DEFAULT NULL,
  detail_status varchar(64) NOT NULL DEFAULT 'started',
  content_items_loaded int NOT NULL DEFAULT 0,
  total_content_items int NOT NULL DEFAULT 0,
  detail_duration_ms int DEFAULT NULL,
  error_message text,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  PRIMARY KEY (detail_lifecycle_id)
);

CREATE INDEX lupo_channel_boot_detail_lifecycle_fk_detail_lifecycle ON lupo_channel_boot_detail_lifecycle (lifecycle_id);
CREATE INDEX lupo_channel_boot_detail_lifecycle_idx_channel ON lupo_channel_boot_detail_lifecycle (channel_id);
CREATE INDEX lupo_channel_boot_detail_lifecycle_idx_status_time ON lupo_channel_boot_detail_lifecycle (detail_status, detail_start_time);

CREATE TABLE lupo_channel_escalations (
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

CREATE INDEX lupo_channel_escalations_idx_channel_id ON lupo_channel_escalations (channel_id);
CREATE INDEX lupo_channel_escalations_idx_thread_id ON lupo_channel_escalations (thread_id);
CREATE INDEX lupo_channel_escalations_idx_actor_id ON lupo_channel_escalations (actor_id);
CREATE INDEX lupo_channel_escalations_idx_escalated_to_actor_id ON lupo_channel_escalations (escalated_to_actor_id);

CREATE TABLE lupo_channel_escalation_rules (
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

CREATE INDEX lupo_channel_escalation_rules_idx_channel_id ON lupo_channel_escalation_rules (channel_id);
CREATE INDEX lupo_channel_escalation_rules_idx_rule_type ON lupo_channel_escalation_rules (rule_type);

CREATE TABLE lupo_channel_files (
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

CREATE INDEX lupo_channel_files_idx_channel_id ON lupo_channel_files (channel_id);
CREATE INDEX lupo_channel_files_idx_file_hash ON lupo_channel_files (file_hash);
CREATE INDEX lupo_channel_files_idx_is_deleted ON lupo_channel_files (is_deleted);
CREATE INDEX lupo_channel_files_idx_upload_ymdhis ON lupo_channel_files (upload_ymdhis);

-- Old channel logs table removed in v4.0.55 - consolidated into lupo_unified_log

-- Old channel log types table removed in v4.0.55 - consolidated into lupo_unified_log

CREATE TABLE lupo_channel_state (
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

CREATE INDEX lupo_channel_state_idx_channel_id ON lupo_channel_state (channel_id);

CREATE TABLE lupo_cip_analytics (
  cip_analytics_id bigint NOT NULL,
  event_id bigint NOT NULL,
  defensiveness_index decimal(5,4) NOT NULL DEFAULT '0.0000',
  integration_velocity decimal(5,4) NOT NULL DEFAULT '0.0000',
  architectural_impact_score decimal(5,4) NOT NULL DEFAULT '0.0000',
  doctrine_propagation_depth tinyint NOT NULL DEFAULT '0',
  critique_source_weight decimal(5,4) NOT NULL DEFAULT '0.5000',
  subsystem_impact_json json DEFAULT NULL,
  trend_analysis_json json DEFAULT NULL,
  calculated_ymdhis bigint NOT NULL,
  recalculated_ymdhis bigint DEFAULT NULL,
  analytics_version varchar(20) DEFAULT '3.0.0',
  PRIMARY KEY (cip_analytics_id)
);

CREATE UNIQUE INDEX lupo_cip_analytics_uk_event_analytics ON lupo_cip_analytics (event_id);
CREATE INDEX lupo_cip_analytics_idx_defensiveness_index ON lupo_cip_analytics (defensiveness_index);
CREATE INDEX lupo_cip_analytics_idx_integration_velocity ON lupo_cip_analytics (integration_velocity);
CREATE INDEX lupo_cip_analytics_idx_architectural_impact ON lupo_cip_analytics (architectural_impact_score);
CREATE INDEX lupo_cip_analytics_idx_calculated_time ON lupo_cip_analytics (calculated_ymdhis);

CREATE TABLE lupo_cip_propagation_tracking (
  cip_propagation_tracking_id bigint NOT NULL,
  cip_event_id bigint NOT NULL,
  propagation_level tinyint NOT NULL,
  affected_subsystem varchar(100) NOT NULL,
  propagation_type varchar(64) NOT NULL,
  change_description text NOT NULL,
  propagation_strength decimal(5,4) NOT NULL DEFAULT '1.0000',
  completion_status varchar(64) DEFAULT 'pending',
  dependencies_json json DEFAULT NULL,
  started_ymdhis bigint DEFAULT NULL,
  completed_ymdhis bigint DEFAULT NULL,
  propagation_version varchar(20) DEFAULT '3.0.0',
  PRIMARY KEY (cip_propagation_tracking_id)
);

CREATE INDEX lupo_cip_propagation_tracking_idx_event_level ON lupo_cip_propagation_tracking (cip_event_id, propagation_level);
CREATE INDEX lupo_cip_propagation_tracking_idx_subsystem ON lupo_cip_propagation_tracking (affected_subsystem);
CREATE INDEX lupo_cip_propagation_tracking_idx_completion_status ON lupo_cip_propagation_tracking (completion_status);
CREATE INDEX lupo_cip_propagation_tracking_idx_propagation_strength ON lupo_cip_propagation_tracking (propagation_strength);

CREATE TABLE lupo_cip_trends (
  cip_trend_id bigint NOT NULL,
  trend_period varchar(64) NOT NULL,
  period_start_ymdhis bigint NOT NULL,
  period_end_ymdhis bigint NOT NULL,
  avg_defensiveness_index decimal(5,4) NOT NULL DEFAULT '0.0000',
  avg_integration_velocity decimal(5,4) NOT NULL DEFAULT '0.0000',
  avg_architectural_impact decimal(5,4) NOT NULL DEFAULT '0.0000',
  total_events int NOT NULL DEFAULT '0',
  high_impact_events int NOT NULL DEFAULT '0',
  doctrine_updates_triggered int NOT NULL DEFAULT '0',
  trend_metadata_json json DEFAULT NULL,
  calculated_ymdhis bigint NOT NULL,
  PRIMARY KEY (cip_trend_id)
);

CREATE UNIQUE INDEX lupo_cip_trends_uk_period_trend ON lupo_cip_trends (trend_period, period_start_ymdhis);
CREATE INDEX lupo_cip_trends_idx_period_range ON lupo_cip_trends (period_start_ymdhis, period_end_ymdhis);
CREATE INDEX lupo_cip_trends_idx_high_impact ON lupo_cip_trends (high_impact_events);

CREATE TABLE lupo_collections (
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

CREATE UNIQUE INDEX lupo_collections_unique_collection_slug_domain ON lupo_collections (federation_node_id, slug);
CREATE INDEX lupo_collections_idx_name ON lupo_collections (name);
CREATE INDEX lupo_collections_idx_domain ON lupo_collections (federation_node_id);
CREATE INDEX lupo_collections_idx_department ON lupo_collections (department_id);
CREATE INDEX lupo_collections_idx_created_ymdhis ON lupo_collections (created_ymdhis);
CREATE INDEX lupo_collections_idx_updated_ymdhis ON lupo_collections (updated_ymdhis);
CREATE INDEX lupo_collections_idx_is_deleted ON lupo_collections (is_deleted);
CREATE INDEX lupo_collections_idx_sort_order ON lupo_collections (sort_order);
CREATE INDEX lupo_collections_idx_actor ON lupo_collections (actor_id);
CREATE INDEX lupo_collections_idx_channel_id ON lupo_collections (channel_id);
CREATE INDEX lupo_collections_idx_is_nav_menu ON lupo_collections (is_nav_menu);
ALTER TABLE lupo_collections CHANGE collection_id collection_id bigint NOT NULL AUTO_INCREMENT;
-- Collections as channel-scoped resource bundles (4.0.69): channel_id, is_nav_menu, nav_icon for UI/navigation.

CREATE TABLE lupo_collection_tabs (
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

CREATE INDEX lupo_collection_tabs_idx_collection_id ON lupo_collection_tabs (collection_id);
CREATE INDEX lupo_collection_tabs_idx_parent_tab_id ON lupo_collection_tabs (collection_tab_parent_id);
CREATE INDEX lupo_collection_tabs_idx_department ON lupo_collection_tabs (department_id);
CREATE INDEX lupo_collection_tabs_idx_actor_id ON lupo_collection_tabs (actor_id);
CREATE INDEX lupo_collection_tabs_idx_slug ON lupo_collection_tabs (slug);
CREATE INDEX lupo_collection_tabs_idx_is_active ON lupo_collection_tabs (is_active);
ALTER TABLE lupo_collection_tabs CHANGE collection_tab_id collection_tab_id bigint NOT NULL AUTO_INCREMENT;
-- Tabs: actor_id (was user_id), visibility_rule, tab_type for channel/nav (4.0.69).

CREATE TABLE lupo_collection_tab_map (
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

CREATE UNIQUE INDEX lupo_collection_tab_map_unique_item_in_tab ON lupo_collection_tab_map (collection_tab_id, item_type, item_id);
CREATE INDEX lupo_collection_tab_map_idx_collection_tab ON lupo_collection_tab_map (collection_tab_id);
CREATE INDEX lupo_collection_tab_map_idx_domain ON lupo_collection_tab_map (federations_node_id);
CREATE INDEX lupo_collection_tab_map_idx_item ON lupo_collection_tab_map (item_type, item_id);
CREATE INDEX lupo_collection_tab_map_idx_created_ymdhis ON lupo_collection_tab_map (created_ymdhis);
CREATE INDEX lupo_collection_tab_map_idx_updated_ymdhis ON lupo_collection_tab_map (updated_ymdhis);
CREATE INDEX lupo_collection_tab_map_idx_is_deleted ON lupo_collection_tab_map (is_deleted);
CREATE INDEX lupo_collection_tab_map_idx_sort_order ON lupo_collection_tab_map (sort_order);

CREATE TABLE lupo_collection_tab_paths (
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

CREATE UNIQUE INDEX lupo_collection_tab_paths_unique_tab_path ON lupo_collection_tab_paths (collection_id, collection_tab_id, path);
CREATE INDEX lupo_collection_tab_paths_idx_collection ON lupo_collection_tab_paths (collection_id);
CREATE INDEX lupo_collection_tab_paths_idx_collection_tab ON lupo_collection_tab_paths (collection_tab_id);
CREATE INDEX lupo_collection_tab_paths_idx_path ON lupo_collection_tab_paths (path);

CREATE TABLE lupo_contents (
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
  file_path_from_root varchar(500) DEFAULT NULL COMMENT 'FLIP Header: path from repo root (4.0.13)',
  file_last_modified_system_version varchar(20) DEFAULT NULL COMMENT 'FLIP: system version at last file edit',
  file_last_modified_utc bigint DEFAULT NULL COMMENT 'FLIP: UTC last modified YYYYMMDDHHIISS',
  tags json DEFAULT NULL,
  dialog_notes text,
  -- 4.0.21 Consolidation: Database-first identity columns
  atom_mappings JSON DEFAULT NULL COMMENT 'Consolidated from lupo_content_atom_map',
  category_mappings JSON DEFAULT NULL COMMENT 'Consolidated from lupo_content_category_map',
  content_events JSON DEFAULT NULL COMMENT 'Consolidated from lupo_content_events',
  hashtags JSON DEFAULT NULL COMMENT 'Consolidated from lupo_content_hashtag',
  inbound_links JSON DEFAULT NULL COMMENT 'Consolidated from lupo_content_inbound_links',
  like_users JSON DEFAULT NULL COMMENT 'Consolidated from lupo_content_likes',
  media_attachments JSON DEFAULT NULL COMMENT 'Consolidated from lupo_content_media',
  question_mappings JSON DEFAULT NULL COMMENT 'Consolidated from lupo_content_question_map',
  content_references JSON DEFAULT NULL COMMENT 'Consolidated from lupo_content_references',
  revision_history JSON DEFAULT NULL COMMENT 'Consolidated from lupo_content_revisions',
  share_users JSON DEFAULT NULL COMMENT 'Consolidated from lupo_content_shares',
  tag_relationships JSON DEFAULT NULL COMMENT 'Consolidated from lupo_content_tag_relationships',
  like_count bigint NOT NULL DEFAULT 0 COMMENT 'FLARE-aligned engagement cache',
  share_count bigint NOT NULL DEFAULT 0 COMMENT 'FLARE-aligned engagement cache',
  comment_count bigint NOT NULL DEFAULT 0 COMMENT 'FLARE-aligned engagement cache',
  PRIMARY KEY (content_id)
);

CREATE UNIQUE INDEX lupo_contents_unique_content_slug_domain ON lupo_contents (federation_node_id, slug);
CREATE UNIQUE INDEX lupo_contents_idx_custom_path ON lupo_contents (custom_path);
CREATE INDEX lupo_contents_idx_file_path_from_root ON lupo_contents (file_path_from_root);
CREATE INDEX lupo_contents_idx_content_parent ON lupo_contents (content_parent_id);
CREATE INDEX lupo_contents_idx_content_type ON lupo_contents (content_type);
CREATE INDEX lupo_contents_idx_status ON lupo_contents (status);
CREATE INDEX lupo_contents_idx_visibility ON lupo_contents (visibility);
CREATE INDEX lupo_contents_idx_created_ymdhis ON lupo_contents (created_ymdhis);
CREATE INDEX lupo_contents_idx_updated_ymdhis ON lupo_contents (updated_ymdhis);
CREATE INDEX lupo_contents_idx_is_deleted ON lupo_contents (is_deleted);
CREATE INDEX lupo_contents_idx_is_active ON lupo_contents (is_active);
CREATE INDEX lupo_contents_idx_domain ON lupo_contents (federation_node_id);
CREATE INDEX lupo_contents_idx_channel_id ON lupo_contents (channel_id);
CREATE INDEX lupo_contents_idx_department ON lupo_contents (department_id);
CREATE INDEX lupo_contents_idx_user ON lupo_contents (actor_id);

-- 4.0.21 Consolidation: Performance indexes for JSON columns
CREATE INDEX lupo_contents_idx_engagement_counts ON lupo_contents (like_count, share_count, comment_count);
CREATE INDEX lupo_contents_idx_has_media ON lupo_contents ((JSON_LENGTH(media_attachments) > 0));
CREATE INDEX lupo_contents_idx_has_events ON lupo_contents ((JSON_LENGTH(content_events) > 0));
CREATE INDEX lupo_contents_idx_has_hashtags ON lupo_contents ((JSON_LENGTH(hashtags) > 0));

-- 12-table install expansion v4.0.74: legacy URL mapping, references, search index
CREATE TABLE lupo_legacy_content_mapping (
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
CREATE UNIQUE INDEX lupo_legacy_content_mapping_uk_legacy_url ON lupo_legacy_content_mapping (legacy_url);

CREATE TABLE lupo_reference_objects (
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

CREATE TABLE lupo_reference_cited_by (
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

CREATE TABLE lupo_search_index (
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
CREATE UNIQUE INDEX lupo_search_index_unique_entity ON lupo_search_index (domain_id, entity_type, entity_id);

CREATE TABLE lupo_crafty_user_mapping (
  crafty_user_mapping_id bigint NOT NULL auto_increment,
  lupo_user_id bigint DEFAULT NULL,
  crafty_operator_id int DEFAULT NULL,
  mapping_type varchar(50) NOT NULL DEFAULT 'manual',
  notes text,
  created_at bigint NOT NULL DEFAULT 0,
  updated_at bigint NOT NULL DEFAULT 0,
  PRIMARY KEY (crafty_user_mapping_id)
);

CREATE INDEX lupo_crafty_user_mapping_idx_crafty_operator_id ON lupo_crafty_user_mapping (crafty_operator_id);
CREATE INDEX lupo_crafty_user_mapping_idx_lupo_user_id ON lupo_crafty_user_mapping (lupo_user_id);
CREATE INDEX lupo_crafty_user_mapping_idx_mapping_type ON lupo_crafty_user_mapping (mapping_type);
CREATE UNIQUE INDEX lupo_crafty_user_mapping_unique_crafty_operator_mapping ON lupo_crafty_user_mapping (crafty_operator_id);
CREATE UNIQUE INDEX lupo_crafty_user_mapping_unique_lupo_user_mapping ON lupo_crafty_user_mapping (lupo_user_id);

CREATE TABLE lupo_crafty_syntax_leave_message (
  crafty_syntax_leave_message_id bigint NOT NULL auto_increment,
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

CREATE INDEX lupo_crafty_syntax_leave_message_idx_assigned ON lupo_crafty_syntax_leave_message (assigned_to);
CREATE INDEX lupo_crafty_syntax_leave_message_idx_created ON lupo_crafty_syntax_leave_message (created_ymdhis);
CREATE INDEX lupo_crafty_syntax_leave_message_idx_department ON lupo_crafty_syntax_leave_message (department_id);
CREATE INDEX lupo_crafty_syntax_leave_message_idx_email ON lupo_crafty_syntax_leave_message (email);
CREATE FULLTEXT INDEX lupo_crafty_syntax_leave_message_idx_message_search ON lupo_crafty_syntax_leave_message (email, name, subject, message);
CREATE INDEX lupo_crafty_syntax_leave_message_idx_priority ON lupo_crafty_syntax_leave_message (priority);
CREATE INDEX lupo_crafty_syntax_leave_message_idx_status ON lupo_crafty_syntax_leave_message (status);

CREATE TABLE lupo_crafty_syntax_layer_invites (
  crafty_syntax_layer_invite_id bigint NOT NULL auto_increment,
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

CREATE INDEX lupo_crafty_syntax_layer_invites_idx_active ON lupo_crafty_syntax_layer_invites (is_active);
CREATE INDEX lupo_crafty_syntax_layer_invites_idx_created ON lupo_crafty_syntax_layer_invites (created_ymdhis);
CREATE INDEX lupo_crafty_syntax_layer_invites_idx_department ON lupo_crafty_syntax_layer_invites (department_name);
CREATE INDEX lupo_crafty_syntax_layer_invites_idx_name ON lupo_crafty_syntax_layer_invites (layer_name);
CREATE INDEX lupo_crafty_syntax_layer_invites_idx_updated ON lupo_crafty_syntax_layer_invites (updated_ymdhis);
CREATE INDEX lupo_crafty_syntax_layer_invites_idx_user ON lupo_crafty_syntax_layer_invites (user_id);

CREATE TABLE lupo_crafty_syntax_chat_questions (
  crafty_syntax_chat_question_id bigint NOT NULL auto_increment,
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

CREATE INDEX lupo_crafty_syntax_chat_questions_idx_department ON lupo_crafty_syntax_chat_questions (department_id);

CREATE TABLE lupo_crafty_syntax_chat_mod_departments (
  crafty_syntax_chat_mod_department_id bigint NOT NULL auto_increment,
  department_id bigint NOT NULL DEFAULT 0,
  module_id bigint NOT NULL DEFAULT 0,
  sort_order int NOT NULL DEFAULT 0,
  is_active tinyint NOT NULL DEFAULT 1,
  is_default tinyint NOT NULL DEFAULT 0,
  PRIMARY KEY (crafty_syntax_chat_mod_department_id)
);

CREATE TABLE lupo_crafty_syntax_auto_invite (
  crafty_syntax_auto_invite_id bigint NOT NULL auto_increment,
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

CREATE INDEX lupo_crafty_syntax_auto_invite_idx_created ON lupo_crafty_syntax_auto_invite (created_ymdhis);
CREATE INDEX lupo_crafty_syntax_auto_invite_idx_department ON lupo_crafty_syntax_auto_invite (department_id);
CREATE INDEX lupo_crafty_syntax_auto_invite_idx_operator ON lupo_crafty_syntax_auto_invite (operator_user_id);
CREATE INDEX lupo_crafty_syntax_auto_invite_idx_page_url ON lupo_crafty_syntax_auto_invite (page_url);
CREATE INDEX lupo_crafty_syntax_auto_invite_idx_status ON lupo_crafty_syntax_auto_invite (is_active, is_deleted);

CREATE TABLE lupo_contexts (
  context_id int NOT NULL,
  context_code varchar(16) NOT NULL,
  context_name varchar(255) NOT NULL,
  context_description text,
  parent_context_id int DEFAULT NULL,
  is_system tinyint NOT NULL DEFAULT '0',
  is_fiction tinyint NOT NULL DEFAULT '0',
  is_installation_local tinyint NOT NULL DEFAULT '0',
  sort_order int NOT NULL DEFAULT '0',
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL,
  weight_score decimal(5,2) NOT NULL DEFAULT '0.00',
  is_active tinyint NOT NULL DEFAULT '1',
  metadata_json json DEFAULT NULL,
  PRIMARY KEY (context_id)
);

CREATE UNIQUE INDEX lupo_contexts_uq_context_code ON lupo_contexts (context_code);
CREATE INDEX lupo_contexts_idx_parent_context ON lupo_contexts (parent_context_id);

CREATE TABLE lupo_contexts_map (
  contexts_map_id bigint NOT NULL,
  context_id bigint NOT NULL,
  item_type varchar(50) NOT NULL,
  item_slug varchar(255) NOT NULL,
  description text,
  is_deleted tinyint NOT NULL DEFAULT '0',
  deleted_ymdhis bigint NOT NULL DEFAULT '0',
  created_ymdhis bigint NOT NULL DEFAULT '0',
  updated_ymdhis bigint NOT NULL DEFAULT '0',
  PRIMARY KEY (contexts_map_id)
);

CREATE INDEX lupo_contexts_map_idx_context_id ON lupo_contexts_map (context_id);
CREATE INDEX lupo_contexts_map_idx_item_type ON lupo_contexts_map (item_type);
CREATE INDEX lupo_contexts_map_idx_item_slug ON lupo_contexts_map (item_slug);
CREATE INDEX lupo_contexts_map_idx_context_item ON lupo_contexts_map (context_id, item_type, item_slug);
CREATE INDEX lupo_contexts_map_idx_is_deleted ON lupo_contexts_map (is_deleted);

CREATE TABLE lupo_crm_leads (
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


CREATE TABLE lupo_crm_lead_messages (
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

CREATE INDEX lupo_crm_lead_messages_lead_id ON lupo_crm_lead_messages (lead_id);
CREATE INDEX lupo_crm_lead_messages_actor_id ON lupo_crm_lead_messages (actor_id);

CREATE TABLE lupo_departments (
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

CREATE INDEX lupo_departments_idx_name ON lupo_departments (name);
CREATE INDEX lupo_departments_idx_type ON lupo_departments (department_type);
CREATE INDEX lupo_departments_idx_federation_node ON lupo_departments (federation_node_id);

CREATE TABLE lupo_department_roles (
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

CREATE INDEX lupo_department_roles_idx_actor_id ON lupo_department_roles (actor_id);
CREATE INDEX lupo_department_roles_idx_department_id ON lupo_department_roles (department_id);
CREATE INDEX lupo_department_roles_idx_role_key ON lupo_department_roles (role_key);

CREATE TABLE lupo_department_metadata (
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

CREATE UNIQUE INDEX lupo_department_metadata_uq_department_metadata ON lupo_department_metadata (department_id);
ALTER TABLE lupo_department_metadata CHANGE department_metadata_id department_metadata_id bigint NOT NULL AUTO_INCREMENT;

-- Channel <-> Department many-to-many (doctrine: channels can belong to multiple departments)
CREATE TABLE lupo_channel_departments (
  channel_department_id bigint NOT NULL,
  channel_id bigint NOT NULL,
  department_id bigint NOT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  PRIMARY KEY (channel_department_id)
);

CREATE UNIQUE INDEX lupo_channel_departments_unq_channel_department ON lupo_channel_departments (channel_id, department_id);
CREATE INDEX lupo_channel_departments_idx_channel ON lupo_channel_departments (channel_id);
CREATE INDEX lupo_channel_departments_idx_department ON lupo_channel_departments (department_id);

CREATE TABLE lupo_dialog_channels (
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

CREATE UNIQUE INDEX lupo_dialog_channels_idx_channel_name ON lupo_dialog_channels (channel_name);
CREATE INDEX lupo_dialog_channels_idx_file_source ON lupo_dialog_channels (file_source);
CREATE INDEX lupo_dialog_channels_idx_speaker ON lupo_dialog_channels (speaker);
CREATE INDEX lupo_dialog_channels_idx_target ON lupo_dialog_channels (target);
CREATE INDEX lupo_dialog_channels_idx_status ON lupo_dialog_channels (status);
CREATE INDEX lupo_dialog_channels_idx_created_ymdhis ON lupo_dialog_channels (created_ymdhis);
CREATE INDEX lupo_dialog_channels_idx_updated_ymdhis ON lupo_dialog_channels (updated_ymdhis);
CREATE INDEX lupo_dialog_channels_idx_dialog_channels_composite ON lupo_dialog_channels (status, created_ymdhis);

CREATE TABLE lupo_dialog_messages (
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

CREATE INDEX lupo_dialog_messages_idx_channel ON lupo_dialog_messages (channel_id);
CREATE INDEX lupo_dialog_messages_idx_created ON lupo_dialog_messages (created_ymdhis);
CREATE INDEX lupo_dialog_messages_idx_updated ON lupo_dialog_messages (updated_ymdhis);
CREATE INDEX lupo_dialog_messages_idx_deleted ON lupo_dialog_messages (is_deleted);
CREATE INDEX lupo_dialog_messages_idx_message_type ON lupo_dialog_messages (message_type);
CREATE INDEX lupo_dialog_messages_idx_dialog_thread_id ON lupo_dialog_messages (dialog_thread_id);
CREATE INDEX lupo_dialog_messages_idx_to_actor_id ON lupo_dialog_messages (to_actor_id);
CREATE INDEX lupo_dialog_messages_idx_read_by_actor ON lupo_dialog_messages (read_by_actor_id);
CREATE INDEX lupo_dialog_messages_idx_read_utc ON lupo_dialog_messages (read_by_actor_utc);
CREATE INDEX lupo_dialog_messages_idx_faucet ON lupo_dialog_messages (source_faucet_slug, source_faucet_instance_id);

CREATE TABLE lupo_dialog_threads (
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
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL,
  is_deleted tinyint NOT NULL DEFAULT '0',
  deleted_ymdhis bigint DEFAULT NULL,
  escalated_to_operator_id bigint DEFAULT NULL,
  escalation_reason varchar(255) DEFAULT NULL,
  escalation_timestamp bigint DEFAULT NULL,
  PRIMARY KEY (dialog_thread_id)
);

CREATE INDEX lupo_dialog_threads_idx_node ON lupo_dialog_threads (federation_node_id);
CREATE INDEX lupo_dialog_threads_idx_channel ON lupo_dialog_threads (channel_id);
CREATE INDEX lupo_dialog_threads_idx_project ON lupo_dialog_threads (project_slug);
CREATE INDEX lupo_dialog_threads_idx_task ON lupo_dialog_threads (task_name);
CREATE INDEX lupo_dialog_threads_idx_status ON lupo_dialog_threads (status);
CREATE INDEX lupo_dialog_threads_idx_created ON lupo_dialog_threads (created_ymdhis);
CREATE INDEX lupo_dialog_threads_idx_updated ON lupo_dialog_threads (updated_ymdhis);
CREATE INDEX lupo_dialog_threads_idx_deleted ON lupo_dialog_threads (is_deleted);
CREATE INDEX lupo_dialog_threads_idx_created_by_actor ON lupo_dialog_threads (created_by_actor_id);
CREATE INDEX lupo_dialog_threads_idx_last_message ON lupo_dialog_threads (last_message_ymdhis);

CREATE TABLE lupo_doctrine_evolution_audit (
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

CREATE INDEX lupo_doctrine_evolution_audit_idx_refinement_step ON lupo_doctrine_evolution_audit (refinement_id, evolution_step);
CREATE INDEX lupo_doctrine_evolution_audit_idx_step_status ON lupo_doctrine_evolution_audit (step_status);
CREATE INDEX lupo_doctrine_evolution_audit_idx_completion_time ON lupo_doctrine_evolution_audit (completed_ymdhis);

CREATE TABLE lupo_tickets (
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

CREATE INDEX lupo_tickets_idx_channel ON lupo_tickets (channel_id);
CREATE INDEX lupo_tickets_idx_actor ON lupo_tickets (actor_id);
CREATE INDEX lupo_tickets_idx_status ON lupo_tickets (status);

CREATE TABLE lupo_ticket_messages (
  ticket_message_id bigint NOT NULL,
  ticket_id bigint NOT NULL,
  actor_id bigint NOT NULL,
  message_text text NOT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  is_deleted tinyint NOT NULL DEFAULT 0,
  deleted_ymdhis bigint DEFAULT NULL,
  PRIMARY KEY (ticket_message_id)
);

CREATE INDEX lupo_ticket_messages_idx_ticket ON lupo_ticket_messages (ticket_id);

-- DEPRECATED in 4.0.42: lupo_doctrine_refinements replaced by lupo_tickets
-- CREATE TABLE lupo_doctrine_refinements (
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


-- lupo_documents and lupo_document_chunks replaced by lupo_artifacts and lupo_artifact_chunks in 4.0.42.


-- Enhanced edges table replacing lupo_edge_types, lupo_relationships, lupo_entity_edges
CREATE TABLE lupo_edges (
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
  domain_id bigint NOT NULL DEFAULT '1',
  weight_score int NOT NULL DEFAULT '0',
  sort_num int NOT NULL DEFAULT '0',
  actor_id bigint DEFAULT NULL,
  is_deleted tinyint NOT NULL DEFAULT '0',
  deleted_ymdhis bigint NOT NULL DEFAULT '0',
  created_ymdhis bigint NOT NULL DEFAULT '0',
  updated_ymdhis bigint NOT NULL DEFAULT '0',
  semantic_weight decimal(5,2) DEFAULT '0.00',
  relationship_type varchar(64) DEFAULT 'semantic',
  bidirectional tinyint NOT NULL DEFAULT '0',
  context_scope varchar(100) DEFAULT NULL,
  properties json DEFAULT NULL,
  -- FLARE protocol extensions (added 2026-02-27)
  flare_weight decimal(3,2) DEFAULT '0.5' COMMENT 'FLARE edge weight (0.5-1.0)',
  flare_reason varchar(255) DEFAULT NULL COMMENT 'Reason for edge existence',
  flare_db_source varchar(50) DEFAULT NULL COMMENT 'Database source table',
  flare_auto_generated tinyint DEFAULT '0' COMMENT 'Generated by automation',
  flare_verified tinyint DEFAULT '0' COMMENT 'Path verified to exist',
  flare_discovered_via varchar(50) DEFAULT NULL COMMENT 'Discovery method',
  PRIMARY KEY (edge_id)
);

CREATE INDEX lupo_edges_idx_left ON lupo_edges (left_object_type, left_object_id);
CREATE INDEX lupo_edges_idx_right ON lupo_edges (right_object_type, right_object_id);
CREATE INDEX lupo_edges_idx_edge_type ON lupo_edges (edge_type);
CREATE INDEX lupo_edges_idx_edge_category ON lupo_edges (edge_category);
CREATE INDEX lupo_edges_idx_actor ON lupo_edges (actor_id);
CREATE INDEX lupo_edges_idx_domain ON lupo_edges (domain_id);
CREATE INDEX lupo_edges_idx_is_deleted ON lupo_edges (is_deleted);
CREATE INDEX lupo_edges_idx_semantic_weight ON lupo_edges (semantic_weight);
CREATE INDEX lupo_edges_idx_relationship_type ON lupo_edges (relationship_type);
CREATE INDEX lupo_edges_idx_channel_semantic ON lupo_edges (channel_id, relationship_type, semantic_weight);
CREATE INDEX lupo_edges_idx_created ON lupo_edges (created_ymdhis);
CREATE INDEX lupo_edges_idx_updated ON lupo_edges (updated_ymdhis);
-- FLARE protocol indexes (added 2026-02-27)
CREATE INDEX lupo_edges_idx_flare_weight ON lupo_edges (flare_weight, edge_type);
CREATE INDEX lupo_edges_idx_flare_discovered ON lupo_edges (flare_discovered_via, flare_auto_generated);
CREATE INDEX lupo_edges_idx_flare_files ON lupo_edges (left_object_type, left_object_id, edge_type, right_object_type, right_object_id);

-- Edge type registry: canonical edge types and semantics (4.0.69 LILITH implementation prompt). No FK; IDs from application.
CREATE TABLE lupo_edge_type_definitions (
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

-- Pre-action authorization: required traits/capabilities/roles per action (4.0.69 LILITH implementation prompt). No FK; IDs from application.
CREATE TABLE lupo_action_authorization (
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

CREATE TABLE lupo_emotional_frameworks (
  framework_name varchar(32) NOT NULL,
  description text,
  is_default tinyint NOT NULL DEFAULT '0',
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL,
  PRIMARY KEY (framework_name)
);


CREATE TABLE lupo_emotional_geometry_calibrations (
  emotional_geometry_calibration_id bigint NOT NULL,
  cip_analytics_id bigint NOT NULL,
  calibration_target varchar(64) NOT NULL,
  target_identifier varchar(255) NOT NULL,
  baseline_before_json json DEFAULT NULL,
  baseline_after_json json NOT NULL,
  mood_framework varchar(32) NOT NULL DEFAULT 'western_analytical',
  tension_vectors_detected json DEFAULT NULL,
  calibration_reason text NOT NULL,
  calibration_algorithm varchar(100) DEFAULT 'cip_pattern_analysis',
  confidence_score decimal(5,4) NOT NULL DEFAULT '0.5000',
  validation_status varchar(64) DEFAULT 'pending',
  applied_ymdhis bigint DEFAULT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  calibration_version varchar(20) DEFAULT '3.0.0',
  PRIMARY KEY (emotional_geometry_calibration_id)
);

CREATE INDEX lupo_emotional_geometry_calibrations_idx_analytics_ref ON lupo_emotional_geometry_calibrations (cip_analytics_id);
CREATE INDEX lupo_emotional_geometry_calibrations_idx_target ON lupo_emotional_geometry_calibrations (calibration_target, target_identifier(100));
CREATE INDEX lupo_emotional_geometry_calibrations_idx_validation_status ON lupo_emotional_geometry_calibrations (validation_status);
CREATE INDEX lupo_emotional_geometry_calibrations_idx_confidence ON lupo_emotional_geometry_calibrations (confidence_score);

-- Old event log table removed in v4.0.55 - consolidated into lupo_unified_log

CREATE TABLE lupo_event_metadata (
  metadata_id bigint NOT NULL,
  event_id bigint NOT NULL,
  metadata_key varchar(100) NOT NULL,
  metadata_value text,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  PRIMARY KEY (metadata_id)
);

CREATE INDEX lupo_event_metadata_idx_event_id ON lupo_event_metadata (event_id);
CREATE INDEX lupo_event_metadata_idx_metadata_key ON lupo_event_metadata (metadata_key);
CREATE INDEX lupo_event_metadata_idx_created_ymdhis ON lupo_event_metadata (created_ymdhis);

CREATE TABLE lupo_federation_categories (
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

CREATE INDEX lupo_federation_categories_idx_category_slug ON lupo_federation_categories (category_slug);
CREATE INDEX lupo_federation_categories_idx_is_deleted ON lupo_federation_categories (is_deleted);

CREATE TABLE lupo_federation_category_map (
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

CREATE INDEX lupo_federation_category_map_idx_node ON lupo_federation_category_map (federation_node_id);
CREATE INDEX lupo_federation_category_map_idx_category ON lupo_federation_category_map (federation_category_id);
CREATE INDEX lupo_federation_category_map_idx_is_deleted ON lupo_federation_category_map (is_deleted);


CREATE TABLE lupo_federation_nodes (
  federation_node_id bigint NOT NULL,
  node_type varchar(32) NOT NULL DEFAULT 'local',
  node_base_url varchar(500) NOT NULL,
  default_department_id bigint DEFAULT NULL,
  node_name varchar(255) DEFAULT NULL,
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

CREATE INDEX lupo_federation_nodes_idx_node_base_url ON lupo_federation_nodes (node_base_url);
CREATE INDEX lupo_federation_nodes_idx_status ON lupo_federation_nodes (status);
CREATE INDEX lupo_federation_nodes_idx_trust_level ON lupo_federation_nodes (trust_level);
CREATE INDEX lupo_federation_nodes_idx_is_deleted ON lupo_federation_nodes (is_deleted);

-- 12-table install expansion v4.0.74: federated trust and discovery
CREATE TABLE lupo_federated_trust (
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
CREATE UNIQUE INDEX lupo_federated_trust_idx_source_target ON lupo_federated_trust (source_node_id, target_node_id);

CREATE TABLE lupo_federation_discovery (
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

CREATE TABLE lupo_governance_overrides (
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

CREATE INDEX lupo_governance_overrides_idx_agent ON lupo_governance_overrides (agent_id);
CREATE INDEX lupo_governance_overrides_idx_applied_by ON lupo_governance_overrides (applied_by_agent);
CREATE INDEX lupo_governance_overrides_idx_type ON lupo_governance_overrides (override_type);
CREATE INDEX lupo_governance_overrides_idx_target ON lupo_governance_overrides (target_key);
CREATE INDEX lupo_governance_overrides_idx_created ON lupo_governance_overrides (created_ymdhis);

CREATE TABLE lupo_help_topics (
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

CREATE UNIQUE INDEX lupo_help_topics_slug ON lupo_help_topics (slug);
CREATE INDEX lupo_help_topics_idx_slug ON lupo_help_topics (slug);
CREATE INDEX lupo_help_topics_idx_category ON lupo_help_topics (category);
CREATE INDEX lupo_help_topics_idx_parent ON lupo_help_topics (parent_slug);
CREATE INDEX lupo_help_topics_idx_created ON lupo_help_topics (created_ymdhis);
CREATE INDEX lupo_help_topics_idx_author ON lupo_help_topics (author_actor_id);

CREATE TABLE lupo_help_tree (
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

CREATE INDEX lupo_help_tree_idx_parent ON lupo_help_tree (parent_id);
CREATE INDEX lupo_help_tree_idx_department ON lupo_help_tree (department_id);
CREATE INDEX lupo_help_tree_idx_content ON lupo_help_tree (content_id);
CREATE INDEX lupo_help_tree_idx_sort ON lupo_help_tree (parent_id, sort_order);
CREATE INDEX lupo_help_tree_idx_action ON lupo_help_tree (action_type, action_target(191));
CREATE INDEX lupo_help_tree_idx_created ON lupo_help_tree (created_ymdhis);
CREATE INDEX lupo_help_tree_idx_updated ON lupo_help_tree (updated_ymdhis);

-- 12-table install expansion v4.0.74: documentation frameworks (LUPOPEDIA HEADERS alignment)
CREATE TABLE lupo_documentation_frameworks (
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

CREATE TABLE lupo_interpretation_log (
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

CREATE INDEX lupo_interpretation_log_idx_agent ON lupo_interpretation_log (agent_id);
CREATE INDEX lupo_interpretation_log_idx_entity ON lupo_interpretation_log (entity_type, entity_id);
CREATE INDEX lupo_interpretation_log_idx_confidence ON lupo_interpretation_log (confidence_score);
CREATE INDEX lupo_interpretation_log_idx_created ON lupo_interpretation_log (created_ymdhis);
CREATE INDEX lupo_interpretation_log_idx_updated ON lupo_interpretation_log (updated_ymdhis);
CREATE INDEX lupo_interpretation_log_idx_deleted ON lupo_interpretation_log (is_deleted);

CREATE TABLE lupo_labs_declarations (
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

CREATE INDEX lupo_labs_declarations_idx_actor_id ON lupo_labs_declarations (actor_id);
CREATE INDEX lupo_labs_declarations_idx_certificate_id ON lupo_labs_declarations (certificate_id);
CREATE INDEX lupo_labs_declarations_idx_validation_status ON lupo_labs_declarations (validation_status);
CREATE INDEX lupo_labs_declarations_idx_next_revalidation ON lupo_labs_declarations (next_revalidation_ymdhis);
CREATE INDEX lupo_labs_declarations_idx_actor_status ON lupo_labs_declarations (actor_id, validation_status, is_deleted);
CREATE INDEX lupo_labs_declarations_idx_revalidation_due ON lupo_labs_declarations (next_revalidation_ymdhis, validation_status, is_deleted);

CREATE TABLE lupo_labs_violations (
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

CREATE INDEX lupo_labs_violations_idx_actor ON lupo_labs_violations (actor_id);
CREATE INDEX lupo_labs_violations_idx_certificate ON lupo_labs_violations (certificate_id);
CREATE INDEX lupo_labs_violations_idx_violation_code ON lupo_labs_violations (violation_code);
CREATE INDEX lupo_labs_violations_idx_created ON lupo_labs_violations (created_ymdhis);
CREATE INDEX lupo_labs_violations_idx_deleted ON lupo_labs_violations (is_deleted);

-- Old memory events table removed in v4.0.55 - consolidated into lupo_unified_log

CREATE TABLE lupo_memory_rollups (
  memory_rollup_id bigint NOT NULL,
  actor_id int NOT NULL,
  summary text NOT NULL,
  source_event_ids text NOT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  PRIMARY KEY (memory_rollup_id)
);

CREATE INDEX lupo_memory_rollups_idx_actor_created ON lupo_memory_rollups (actor_id, created_ymdhis);

-- Old meta log events table removed in v4.0.55 - consolidated into lupo_unified_log

CREATE TABLE lupo_modules (
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

CREATE UNIQUE INDEX lupo_modules_uq_module_key ON lupo_modules (module_key);
CREATE INDEX lupo_modules_idx_namespace ON lupo_modules (namespace);
CREATE INDEX lupo_modules_idx_status ON lupo_modules (is_active, is_deleted);
CREATE INDEX lupo_modules_idx_system ON lupo_modules (is_system);
CREATE INDEX lupo_modules_idx_installed ON lupo_modules (installed_ymdhis);


CREATE TABLE lupo_multi_agent_critique_sync (
  multi_agent_critique_sync_id bigint NOT NULL,
  cip_event_id bigint NOT NULL,
  agent_id varchar(100) NOT NULL,
  sync_role varchar(64) NOT NULL,
  sync_status varchar(64) DEFAULT 'pending',
  agent_perspective_json json DEFAULT NULL,
  consensus_contribution decimal(5,4) DEFAULT '0.0000',
  conflict_indicators_json json DEFAULT NULL,
  resolution_strategy varchar(255) DEFAULT NULL,
  sync_started_ymdhis bigint DEFAULT NULL,
  sync_completed_ymdhis bigint DEFAULT NULL,
  sync_version varchar(20) DEFAULT '3.0.0',
  PRIMARY KEY (multi_agent_critique_sync_id)
);

CREATE INDEX lupo_multi_agent_critique_sync_idx_event_agent ON lupo_multi_agent_critique_sync (cip_event_id, agent_id);
CREATE INDEX lupo_multi_agent_critique_sync_idx_sync_status ON lupo_multi_agent_critique_sync (sync_status);
CREATE INDEX lupo_multi_agent_critique_sync_idx_sync_role ON lupo_multi_agent_critique_sync (sync_role);
CREATE INDEX lupo_multi_agent_critique_sync_idx_consensus_contribution ON lupo_multi_agent_critique_sync (consensus_contribution);

CREATE TABLE lupo_notifications (
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


CREATE TABLE lupo_permissions (
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

CREATE UNIQUE INDEX lupo_permissions_uniq_target_user ON lupo_permissions (target_type, target_id, user_id);
CREATE UNIQUE INDEX lupo_permissions_uniq_target_department ON lupo_permissions (target_type, target_id, department_id);
CREATE INDEX lupo_permissions_idx_target ON lupo_permissions (target_type, target_id);
CREATE INDEX lupo_permissions_idx_user ON lupo_permissions (user_id);
CREATE INDEX lupo_permissions_idx_department ON lupo_permissions (department_id);
CREATE INDEX lupo_permissions_idx_deleted ON lupo_permissions (is_deleted, deleted_ymdhis);
CREATE INDEX lupo_permissions_idx_permission ON lupo_permissions (permission);
CREATE INDEX lupo_permissions_idx_created_ymdhis ON lupo_permissions (created_ymdhis);

CREATE TABLE lupo_search_rebuild_log (
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

CREATE UNIQUE INDEX lupo_search_rebuild_log_unique_entity_operation ON lupo_search_rebuild_log (entity_type, entity_id, action);
CREATE INDEX lupo_search_rebuild_log_idx_status_retry ON lupo_search_rebuild_log (status, next_attempt_ymdhis);
CREATE INDEX lupo_search_rebuild_log_idx_created ON lupo_search_rebuild_log (created_ymdhis);
CREATE INDEX lupo_search_rebuild_log_idx_entity ON lupo_search_rebuild_log (entity_type, entity_id);

-- Consolidated semantic index table replacing 7 semantic tables
CREATE TABLE lupo_semantic_index (
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

CREATE UNIQUE INDEX lupo_semantic_index_uk_type_slug ON lupo_semantic_index (semantic_type, slug);
CREATE INDEX lupo_semantic_index_idx_type ON lupo_semantic_index (semantic_type);
CREATE INDEX lupo_semantic_index_idx_parent ON lupo_semantic_index (parent_id);
CREATE INDEX lupo_semantic_index_idx_source_content ON lupo_semantic_index (source_content_id);
CREATE INDEX lupo_semantic_index_idx_target_content ON lupo_semantic_index (target_content_id);
CREATE INDEX lupo_semantic_index_idx_source_page ON lupo_semantic_index (source_page_id);
CREATE INDEX lupo_semantic_index_idx_target_page ON lupo_semantic_index (target_page_id);
CREATE INDEX lupo_semantic_index_idx_entity ON lupo_semantic_index (entity_type, entity_id);
CREATE INDEX lupo_semantic_index_idx_language ON lupo_semantic_index (language_code);
CREATE INDEX lupo_semantic_index_idx_layer ON lupo_semantic_index (layer);
CREATE INDEX lupo_semantic_index_idx_timeframe ON lupo_semantic_index (timeframe);
CREATE INDEX lupo_semantic_index_idx_created_ymdhis ON lupo_semantic_index (created_ymdhis, is_active, is_deleted);
CREATE INDEX lupo_semantic_index_idx_updated_ymdhis ON lupo_semantic_index (updated_ymdhis);
CREATE INDEX lupo_semantic_index_idx_is_active ON lupo_semantic_index (is_active);
CREATE INDEX lupo_semantic_index_idx_is_default ON lupo_semantic_index (is_default);
CREATE INDEX lupo_semantic_index_idx_is_deleted ON lupo_semantic_index (is_deleted);









-- Model A: DB-backed session authority. Browser stores only session_id; identity from DB. No session payload, no JWT.
-- Columns is_active, is_expired, is_revoked, is_deleted, last_seen_ymdhis, expires_ymdhis, security_level, system_context, status required by ai_activation, session_helpers, livehelp_js, image.php.
CREATE TABLE lupo_sessions (
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
CREATE INDEX lupo_sessions_idx_actor ON lupo_sessions (actor_id);
CREATE INDEX lupo_sessions_idx_actor_name ON lupo_sessions (actor_name);
CREATE INDEX lupo_sessions_idx_last_activity ON lupo_sessions (last_activity_ymdhis);
CREATE INDEX lupo_sessions_idx_federation ON lupo_sessions (federation_node_id);
CREATE INDEX lupo_sessions_idx_is_active ON lupo_sessions (is_active);
CREATE INDEX lupo_sessions_idx_last_seen ON lupo_sessions (last_seen_ymdhis);

-- Old session events table removed in v4.0.55 - consolidated into lupo_unified_log

CREATE TABLE lupo_system_config (
  system_config_id bigint NOT NULL,
  config_key varchar(255) NOT NULL,
  config_value text NOT NULL,
  actor_id bigint NOT NULL DEFAULT '0',
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL,
  PRIMARY KEY (system_config_id)
);

CREATE UNIQUE INDEX lupo_system_config_config_key ON lupo_system_config (config_key);

-- 12-table install expansion v4.0.74: system health snapshots and hotfix registry
CREATE TABLE lupo_system_health_snapshots (
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

CREATE TABLE lupo_hotfix_registry (
  hotfix_id bigint NOT NULL,
  hotfix_version varchar(20) NOT NULL,
  applied_ymdhis bigint NOT NULL,
  applied_by_actor_id bigint DEFAULT NULL,
  description text,
  metadata_json json DEFAULT NULL,
  PRIMARY KEY (hotfix_id)
);

-- Old logging tables removed in v4.0.55 - consolidated into lupo_unified_log

CREATE TABLE lupo_system_commands (
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

CREATE INDEX lupo_system_commands_idx_status_priority_scheduled ON lupo_system_commands (status, priority, scheduled_ymdhis);
CREATE INDEX lupo_system_commands_idx_status_heartbeat ON lupo_system_commands (status, last_heartbeat_ymdhis);
CREATE INDEX lupo_system_commands_idx_created_ymdhis ON lupo_system_commands (created_ymdhis);
CREATE INDEX lupo_system_commands_idx_is_deleted ON lupo_system_commands (is_deleted);

-- Old tab events table removed in v4.0.55 - consolidated into lupo_unified_log

-- lupo_temporal_coherence_snapshots moved to future_features_lupopedia.sql (unused at runtime)

-- lupo_tldnr moved to future_features_lupopedia.sql (v4.0.57)

-- Consolidated truth knowledge table replacing 6 truth tables
CREATE TABLE lupo_truth_knowledge (
  truth_id bigint NOT NULL,
  truth_type varchar(32) NOT NULL,
  parent_id bigint DEFAULT NULL,
  question_id bigint DEFAULT NULL,
  answer_id bigint DEFAULT NULL,
  evidence_id bigint DEFAULT NULL,
  source_id bigint DEFAULT NULL,
  topic_id bigint DEFAULT NULL,
  relation_id bigint DEFAULT NULL,
  actor_id bigint NOT NULL DEFAULT '0',
  object_type varchar(50) DEFAULT NULL,
  object_id bigint DEFAULT NULL,
  left_object_type varchar(50) DEFAULT NULL,
  left_object_id bigint DEFAULT NULL,
  right_object_type varchar(50) DEFAULT NULL,
  right_object_id bigint DEFAULT NULL,
  slug varchar(255) DEFAULT NULL,
  title varchar(255) DEFAULT NULL,
  text_content text,
  question_text text,
  answer_text text,
  evidence_text text,
  source_url text,
  source_title varchar(255) DEFAULT '',
  qtype varchar(50) DEFAULT 'unknown',
  status varchar(64) DEFAULT 'active',
  evidence_type varchar(50) DEFAULT '',
  source_type varchar(50) DEFAULT '',
  relation_type varchar(50) DEFAULT '',
  format varchar(64) DEFAULT 'text',
  format_override varchar(50) DEFAULT NULL,
  confidence_score decimal(5,2) DEFAULT '0.00',
  evidence_score decimal(5,2) DEFAULT '0.00',
  weight_score decimal(5,2) DEFAULT '0.00',
  reliability_score decimal(5,2) DEFAULT '0.00',
  importance_score decimal(5,2) DEFAULT '0.00',
  sort_num int DEFAULT '0',
  view_count bigint DEFAULT '0',
  likes_count bigint DEFAULT '0',
  shares_count bigint DEFAULT '0',
  answer_count bigint DEFAULT '0',
  contradiction_flag tinyint DEFAULT '0',
  is_featured tinyint DEFAULT '0',
  is_verified tinyint DEFAULT '0',
  last_activity_ymdhis bigint DEFAULT NULL,
  default_collection_id bigint DEFAULT '0',
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL,
  is_deleted tinyint NOT NULL DEFAULT '0',
  deleted_ymdhis bigint DEFAULT NULL,
  truth_question_parent_id bigint DEFAULT NULL,
  PRIMARY KEY (truth_id)
);

CREATE UNIQUE INDEX lupo_truth_knowledge_uk_type_slug ON lupo_truth_knowledge (truth_type, slug);
CREATE INDEX lupo_truth_knowledge_idx_type ON lupo_truth_knowledge (truth_type);
CREATE INDEX lupo_truth_knowledge_idx_parent ON lupo_truth_knowledge (parent_id);
CREATE INDEX lupo_truth_knowledge_idx_question ON lupo_truth_knowledge (question_id);
CREATE INDEX lupo_truth_knowledge_idx_answer ON lupo_truth_knowledge (answer_id);
CREATE INDEX lupo_truth_knowledge_idx_evidence ON lupo_truth_knowledge (evidence_id);
CREATE INDEX lupo_truth_knowledge_idx_source ON lupo_truth_knowledge (source_id);
CREATE INDEX lupo_truth_knowledge_idx_topic ON lupo_truth_knowledge (topic_id);
CREATE INDEX lupo_truth_knowledge_idx_actor ON lupo_truth_knowledge (actor_id);
CREATE INDEX lupo_truth_knowledge_idx_object ON lupo_truth_knowledge (object_type, object_id);
CREATE INDEX lupo_truth_knowledge_idx_left_object ON lupo_truth_knowledge (left_object_type, left_object_id);
CREATE INDEX lupo_truth_knowledge_idx_right_object ON lupo_truth_knowledge (right_object_type, right_object_id);
CREATE INDEX lupo_truth_knowledge_idx_status ON lupo_truth_knowledge (status);
CREATE INDEX lupo_truth_knowledge_idx_created_ymdhis ON lupo_truth_knowledge (created_ymdhis, is_deleted);
CREATE INDEX lupo_truth_knowledge_idx_updated_ymdhis ON lupo_truth_knowledge (updated_ymdhis);
CREATE INDEX lupo_truth_knowledge_idx_is_deleted ON lupo_truth_knowledge (is_deleted);

-- Legacy truth answers table for Crafty Syntax import compatibility
CREATE TABLE lupo_truth_answers (
  truth_answer_id bigint NOT NULL AUTO_INCREMENT,
  truth_question_id bigint NOT NULL,
  actor_id bigint NOT NULL,
  answer_text text,
  confidence decimal(5,2) DEFAULT '0.00',
  evidence_count int DEFAULT '0',
  source_count int DEFAULT '0',
  status varchar(64) DEFAULT 'active',
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL,
  is_deleted tinyint NOT NULL DEFAULT '0',
  deleted_ymdhis bigint DEFAULT NULL,
  evidence_score decimal(5,2) DEFAULT '0.00',
  contradiction_flag tinyint DEFAULT '0',
  likes_count bigint DEFAULT '0',
  PRIMARY KEY (truth_answer_id),
  KEY lupo_truth_answers_idx_question (truth_question_id),
  KEY lupo_truth_answers_idx_actor (actor_id),
  KEY lupo_truth_answers_idx_status (status),
  KEY lupo_truth_answers_idx_created (created_ymdhis)
);

-- lupo_paths: aggregated navigation flows (low-volume). Populated by gc.php from lupo_visits. year_num/month_num/day_num for partitioning.
CREATE TABLE lupo_paths (
  path_id bigint NOT NULL AUTO_INCREMENT,
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
CREATE INDEX lupo_paths_idx_enter_exit ON lupo_paths (entercontentid, exitcontentid);
CREATE INDEX lupo_paths_idx_ymd ON lupo_paths (year_num, month_num, day_num);
CREATE INDEX lupo_paths_idx_transition ON lupo_paths (transition_type);
CREATE INDEX lupo_paths_idx_created ON lupo_paths (created_ymdhis);
CREATE INDEX lupo_paths_idx_is_deleted ON lupo_paths (is_deleted);

-- ============================================================
-- SEMANTIC NAVBAR BACKEND (4.0.71): references, hashtags, folders
-- No FKs; application enforces relations. BIGINT timestamps only.
-- ============================================================
CREATE TABLE lupo_references (
  reference_id bigint NOT NULL AUTO_INCREMENT,
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
CREATE INDEX lupo_references_idx_source ON lupo_references (source_entity_type, source_entity_id);
CREATE INDEX lupo_references_idx_created ON lupo_references (created_ymdhis);
CREATE INDEX lupo_references_idx_is_deleted ON lupo_references (is_deleted);

CREATE TABLE lupo_reference_links (
  reference_link_id bigint NOT NULL AUTO_INCREMENT,
  reference_id bigint NOT NULL,
  object_type varchar(64) NOT NULL,
  object_id bigint NOT NULL,
  sort_order int NOT NULL DEFAULT 0,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  is_deleted tinyint NOT NULL DEFAULT 0,
  deleted_ymdhis bigint DEFAULT NULL,
  PRIMARY KEY (reference_link_id)
);
CREATE INDEX lupo_reference_links_idx_reference ON lupo_reference_links (reference_id);
CREATE INDEX lupo_reference_links_idx_object ON lupo_reference_links (object_type, object_id);
CREATE INDEX lupo_reference_links_idx_is_deleted ON lupo_reference_links (is_deleted);

CREATE TABLE lupo_hashtags (
  hashtag_id bigint NOT NULL AUTO_INCREMENT,
  tag_slug varchar(128) NOT NULL,
  label varchar(255) DEFAULT NULL,
  use_count bigint NOT NULL DEFAULT 0,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL DEFAULT 0,
  is_deleted tinyint NOT NULL DEFAULT 0,
  deleted_ymdhis bigint DEFAULT NULL,
  PRIMARY KEY (hashtag_id)
);
CREATE UNIQUE INDEX lupo_hashtags_uniq_slug ON lupo_hashtags (tag_slug);
CREATE INDEX lupo_hashtags_idx_use_count ON lupo_hashtags (use_count);
CREATE INDEX lupo_hashtags_idx_is_deleted ON lupo_hashtags (is_deleted);

CREATE TABLE lupo_hashtag_map (
  hashtag_map_id bigint NOT NULL AUTO_INCREMENT,
  hashtag_id bigint NOT NULL,
  object_type varchar(64) NOT NULL,
  object_id bigint NOT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  is_deleted tinyint NOT NULL DEFAULT 0,
  deleted_ymdhis bigint DEFAULT NULL,
  PRIMARY KEY (hashtag_map_id)
);
CREATE INDEX lupo_hashtag_map_idx_hashtag ON lupo_hashtag_map (hashtag_id);
CREATE INDEX lupo_hashtag_map_idx_object ON lupo_hashtag_map (object_type, object_id);
CREATE INDEX lupo_hashtag_map_idx_is_deleted ON lupo_hashtag_map (is_deleted);

CREATE TABLE lupo_folders (
  folder_id bigint NOT NULL AUTO_INCREMENT,
  name varchar(255) NOT NULL,
  slug varchar(128) NOT NULL,
  parent_folder_id bigint DEFAULT NULL,
  actor_id bigint DEFAULT NULL,
  channel_id bigint DEFAULT NULL,
  sort_order int NOT NULL DEFAULT 0,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL DEFAULT 0,
  is_deleted tinyint NOT NULL DEFAULT 0,
  deleted_ymdhis bigint DEFAULT NULL,
  PRIMARY KEY (folder_id)
);
CREATE INDEX lupo_folders_idx_parent ON lupo_folders (parent_folder_id);
CREATE INDEX lupo_folders_idx_actor ON lupo_folders (actor_id);
CREATE INDEX lupo_folders_idx_channel ON lupo_folders (channel_id);
CREATE INDEX lupo_folders_idx_slug ON lupo_folders (slug);
CREATE INDEX lupo_folders_idx_is_deleted ON lupo_folders (is_deleted);

CREATE TABLE lupo_folder_map (
  folder_map_id bigint NOT NULL AUTO_INCREMENT,
  folder_id bigint NOT NULL,
  object_type varchar(64) NOT NULL,
  object_id bigint NOT NULL,
  sort_order int NOT NULL DEFAULT 0,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  is_deleted tinyint NOT NULL DEFAULT 0,
  deleted_ymdhis bigint DEFAULT NULL,
  PRIMARY KEY (folder_map_id)
);
CREATE INDEX lupo_folder_map_idx_folder ON lupo_folder_map (folder_id);
CREATE INDEX lupo_folder_map_idx_object ON lupo_folder_map (object_type, object_id);
CREATE INDEX lupo_folder_map_idx_is_deleted ON lupo_folder_map (is_deleted);

-- Previous Pages Summary (4.0.71)
CREATE TABLE lupo_paths_summary (
  summary_id bigint NOT NULL AUTO_INCREMENT,
  path_id bigint NOT NULL,
  total_count bigint NOT NULL DEFAULT 0,
  last_used_ymdhis bigint NOT NULL DEFAULT 0,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL DEFAULT 0,
  PRIMARY KEY (summary_id)
);
CREATE INDEX lupo_paths_summary_idx_path ON lupo_paths_summary (path_id);

-- Reference Map (Explicit mapping table, 4.0.71)
CREATE TABLE lupo_reference_map (
  reference_map_id bigint NOT NULL AUTO_INCREMENT,
  reference_id bigint NOT NULL,
  target_type varchar(64) NOT NULL,
  target_id bigint NOT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  is_deleted tinyint NOT NULL DEFAULT 0,
  PRIMARY KEY (reference_map_id)
);
CREATE INDEX lupo_reference_map_idx_reference ON lupo_reference_map (reference_id);
CREATE INDEX lupo_reference_map_idx_target ON lupo_reference_map (target_type, target_id);

-- Collection Links (Explicit link objects within collections, 4.0.71)
CREATE TABLE lupo_collection_links (
  collection_link_id bigint NOT NULL AUTO_INCREMENT,
  collection_id bigint NOT NULL,
  link_url varchar(2000) NOT NULL,
  link_label varchar(255) DEFAULT NULL,
  sort_order int NOT NULL DEFAULT 0,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL DEFAULT 0,
  is_deleted tinyint NOT NULL DEFAULT 0,
  PRIMARY KEY (collection_link_id)
);
CREATE INDEX lupo_collection_links_idx_collection ON lupo_collection_links (collection_id);

-- Collection Map (Mapping collections to multiple objects, 4.0.71)
CREATE TABLE lupo_collection_map (
  collection_map_id bigint NOT NULL AUTO_INCREMENT,
  collection_id bigint NOT NULL,
  object_type varchar(64) NOT NULL,
  object_id bigint NOT NULL,
  sort_order int NOT NULL DEFAULT 0,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  is_deleted tinyint NOT NULL DEFAULT 0,
  PRIMARY KEY (collection_map_id)
);
CREATE INDEX lupo_collection_map_idx_collection ON lupo_collection_map (collection_id);
CREATE INDEX lupo_collection_map_idx_object ON lupo_collection_map (object_type, object_id);

-- Edge Types (Definitions for semantic edges, 4.0.71)
CREATE TABLE lupo_edge_types (
  edge_type_id bigint NOT NULL AUTO_INCREMENT,
  slug varchar(64) NOT NULL,
  label varchar(128) NOT NULL,
  description text,
  is_bidirectional tinyint NOT NULL DEFAULT 0,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL DEFAULT 0,
  is_deleted tinyint NOT NULL DEFAULT 0,
  PRIMARY KEY (edge_type_id)
);
CREATE UNIQUE INDEX lupo_edge_types_uniq_slug ON lupo_edge_types (slug);

-- Edge Map (Mapping edges between objects, 4.0.71)
CREATE TABLE lupo_edge_map (
  edge_map_id bigint NOT NULL AUTO_INCREMENT,
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
CREATE INDEX lupo_edge_map_idx_edge ON lupo_edge_map (edge_id);
CREATE INDEX lupo_edge_map_idx_type ON lupo_edge_map (edge_type_id);
CREATE INDEX lupo_edge_map_idx_source ON lupo_edge_map (source_type, source_id);
CREATE INDEX lupo_edge_map_idx_target ON lupo_edge_map (target_type, target_id);

-- Questions (Semantic Q/A, 4.0.71)
CREATE TABLE lupo_questions (
  question_id bigint NOT NULL AUTO_INCREMENT,
  slug varchar(128) NOT NULL,
  question_text text NOT NULL,
  actor_id bigint DEFAULT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL DEFAULT 0,
  is_deleted tinyint NOT NULL DEFAULT 0,
  PRIMARY KEY (question_id)
);
CREATE UNIQUE INDEX lupo_questions_uniq_slug ON lupo_questions (slug);

-- Answers (Semantic Q/A, 4.0.71)
CREATE TABLE lupo_answers (
  answer_id bigint NOT NULL AUTO_INCREMENT,
  question_id bigint NOT NULL,
  answer_text text NOT NULL,
  actor_id bigint DEFAULT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL DEFAULT 0,
  is_deleted tinyint NOT NULL DEFAULT 0,
  PRIMARY KEY (answer_id)
);
CREATE INDEX lupo_answers_idx_question ON lupo_answers (question_id);

-- Question Map (Mapping questions to objects/contexts, 4.0.71)
CREATE TABLE lupo_question_map (
  question_map_id bigint NOT NULL AUTO_INCREMENT,
  question_id bigint NOT NULL,
  object_type varchar(64) NOT NULL,
  object_id bigint NOT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  is_deleted tinyint NOT NULL DEFAULT 0,
  PRIMARY KEY (question_map_id)
);
CREATE INDEX lupo_question_map_idx_question ON lupo_question_map (question_id);
CREATE INDEX lupo_question_map_idx_object ON lupo_question_map (object_type, object_id);

-- ============================================================

CREATE TABLE lupo_referers (
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

CREATE INDEX lupo_referers_idx_content_id ON lupo_referers (content_id);
CREATE INDEX lupo_referers_idx_actor_id ON lupo_referers (actor_id);
CREATE INDEX lupo_referers_idx_referer_domain ON lupo_referers (referer_domain);
CREATE INDEX lupo_referers_idx_referer_content_id ON lupo_referers (referer_content_id);
CREATE INDEX lupo_referers_idx_date ON lupo_referers (date_ymd);
ALTER TABLE lupo_referers CHANGE referer_id referer_id bigint NOT NULL AUTO_INCREMENT;

CREATE TABLE lupo_registry (
  registry_id bigint NOT NULL AUTO_INCREMENT,
  entity_type varchar(50) NOT NULL,
  entity_index_id bigint NOT NULL DEFAULT 0,
  entity_index bigint NOT NULL DEFAULT 0,
  federation_node_id bigint NOT NULL DEFAULT 0,
  reserved_ymdhis bigint NOT NULL DEFAULT 0,
  metadata text,
  entity_key varchar(255) DEFAULT NULL,
  entity_name varchar(255) DEFAULT NULL,
  entity_table varchar(255) DEFAULT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL DEFAULT 0,
  is_deleted tinyint NOT NULL DEFAULT 0,
  deleted_ymdhis bigint DEFAULT NULL,
  is_active tinyint NOT NULL DEFAULT 1,
  is_kernel tinyint NOT NULL DEFAULT 0,
  metadata_json text,
  PRIMARY KEY (registry_id)
);

CREATE UNIQUE INDEX idx_registry_unique ON lupo_registry (entity_type, entity_index_id, federation_node_id);
CREATE INDEX idx_registry_entity_type ON lupo_registry (entity_type);
CREATE INDEX idx_registry_federation_node ON lupo_registry (federation_node_id);
-- Unified registry for all entities across federation nodes.

CREATE TABLE lupo_registry_open (
  unregistry_id bigint NOT NULL AUTO_INCREMENT,
  entity_type varchar(50) NOT NULL,
  entity_index_id bigint NOT NULL,
  reason varchar(255) DEFAULT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  PRIMARY KEY (unregistry_id)
);

CREATE UNIQUE INDEX idx_registry_open_unique ON lupo_registry_open (entity_type, entity_index_id);
CREATE INDEX idx_registry_open_entity_type ON lupo_registry_open (entity_type);
-- Unified unregistry for tracking unused/reserved IDs.

-- lupo_projects: project registry (PROJECT_REGISTRY_SCHEMA_DESIGN.md, create_lupo_projects.sql.md). project_id application-assigned, no AUTO_INCREMENT.
CREATE TABLE lupo_projects (
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

CREATE INDEX lupo_projects_idx_federation_node ON lupo_projects (federation_node_id, status, is_deleted);
CREATE INDEX lupo_projects_idx_project_key ON lupo_projects (project_key, federation_node_id);
CREATE INDEX lupo_projects_idx_project_slug ON lupo_projects (project_slug, federation_node_id);
CREATE INDEX lupo_projects_idx_orchestrator ON lupo_projects (orchestrator_id, status, is_deleted);
CREATE INDEX lupo_projects_idx_default_channel ON lupo_projects (default_channel_id);
CREATE INDEX lupo_projects_idx_status ON lupo_projects (status, is_active, is_deleted);
CREATE INDEX lupo_projects_idx_created ON lupo_projects (created_ymdhis);
CREATE INDEX lupo_projects_idx_updated ON lupo_projects (updated_ymdhis);

-- Unified import registry for collision resolution during federation imports.


CREATE TABLE lupo_uploads (
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

CREATE INDEX lupo_uploads_idx_actor_id ON lupo_uploads (actor_id);
CREATE INDEX lupo_uploads_idx_channel_id ON lupo_uploads (channel_id);
CREATE INDEX lupo_uploads_idx_file_extension ON lupo_uploads (file_extension);
CREATE INDEX lupo_uploads_idx_created_ymdhis ON lupo_uploads (created_ymdhis);

-- Old world events table removed in v4.0.55 - consolidated into lupo_unified_log

CREATE TABLE lupo_world_registry (
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

CREATE UNIQUE INDEX lupo_world_registry_unique_world_key ON lupo_world_registry (world_key);
CREATE INDEX lupo_world_registry_idx_world_type ON lupo_world_registry (world_type);
CREATE INDEX lupo_world_registry_idx_created_ymdhis ON lupo_world_registry (created_ymdhis);
CREATE INDEX lupo_world_registry_idx_is_active ON lupo_world_registry (is_active);

-- ============================================================
-- SEED DATA REMOVED - USE seed_minimal_4.0.26.sql INSTEAD
-- ============================================================
-- ALL SEED DATA COMMENTED OUT (2026-02-22)
-- Reason: Schema mismatches between INSERT column names and actual table definitions
-- Solution: Seed data moved to database/migrations/seed_minimal_4.0.26.sql
-- This file now contains ONLY table definitions (CREATE TABLE statements)
-- install.php loads schema from this file, then seeds from seed_minimal_4.0.26.sql
-- ============================================================

-- ============================================================
-- FINAL IDE & AI ACTOR INTEGRATION (Lupopedia 4.0.23)
-- ============================================================
-- CSV-driven unregistry allocation - FINAL ACTOR IDs:
-- Cursor IDE: 2031, Kiro IDE: 2032, Zed IDE: 2033, VS Code IDE: 2034
-- Antigravity IDE: 2035, Microsoft Copilot: 2036, DeepSeek LEXA: 2037, DeepSeek LILITH: 2038
-- ============================================================

-- Registry entries for all IDE & AI actors
INSERT IGNORE INTO lupo_registry (registry_id, entity_type, entity_index_id, entity_index, entity_key, entity_name, entity_table, federation_node_id, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis, is_active, is_kernel, metadata_json) 
VALUES 
(9002031, 'actor', 2031, 2031, 'cursor-ide', 'Cursor IDE', 'lupo_actors', 1, @now, @now, 0, NULL, 1, 0, '{"actor_source_type":"system_tool","client_id":"cursor","provider":"cursor","purpose":"IDE_integration","csv_allocation":true}'),
(9002033, 'actor', 2033, 2033, 'zed-ide', 'Zed IDE', 'lupo_actors', 1, @now, @now, 0, NULL, 1, 0, '{"actor_source_type":"system_tool","client_id":"zed","provider":"zed","purpose":"IDE_integration","csv_allocation":true}'),
(9002034, 'actor', 2034, 2034, 'vscode-ide', 'VS Code IDE', 'lupo_actors', 1, @now, @now, 0, NULL, 1, 0, '{"actor_source_type":"system_tool","client_id":"vscode","provider":"microsoft","purpose":"IDE_integration","csv_allocation":true}'),
(9002035, 'actor', 2035, 2035, 'antigravity-ide', 'Antigravity IDE', 'lupo_actors', 1, @now, @now, 0, NULL, 1, 0, '{"actor_source_type":"system_tool","client_id":"antigravity","purpose":"VSX_extension_development","csv_allocation":true}'),
(9002036, 'actor', 2036, 2036, 'microsoft-copilot', 'Microsoft Copilot', 'lupo_actors', 1, @now, @now, 0, NULL, 1, 0, '{"actor_source_type":"external_ai","client_id":"copilot","provider":"microsoft","purpose":"AI_assistant","csv_allocation":true}'),
(9002037, 'actor', 2037, 2037, 'deepseek-lexa', 'DeepSeek LEXA', 'lupo_actors', 1, @now, @now, 0, NULL, 1, 0, '{"actor_source_type":"external_ai","client_id":"deepseek_lexa","provider":"deepseek","purpose":"AI_assistant","csv_allocation":true}'),
(9002038, 'actor', 2038, 2038, 'deepseek-lilith', 'DeepSeek LILITH', 'lupo_actors', 1, @now, @now, 0, NULL, 1, 0, '{"actor_source_type":"external_ai","client_id":"deepseek_lilith","provider":"deepseek","purpose":"AI_assistant","csv_allocation":true}'),
ON DUPLICATE KEY UPDATE entity_name = VALUES(entity_name), metadata_json = VALUES(metadata_json), updated_ymdhis = @now, is_deleted = 0, is_active = 1;

-- Actor records for all IDE & AI actors
INSERT IGNORE INTO lupo_actors (actor_id, actor_type, slug, name, created_ymdhis, updated_ymdhis, is_active, is_deleted, deleted_ymdhis, actor_source_id, actor_source_type, metadata, adversarial_role, adversarial_oversight_actor_id, avatar_hash) 
VALUES 
(2031, 'system_tool', 'cursor-ide', 'Cursor IDE', @now, @now, 1, 0, NULL, 2031, 'system_tool', '{"purpose":"IDE_integration","capabilities":["code_generation","file_editing","project_management","git_integration"],"version":"1.0.0","client_id":"cursor","provider":"cursor","integration_ready":true}', 'none', NULL, NULL),
(2032, 'system_tool', 'kiro-ide', 'Kiro IDE', @now, @now, 1, 0, NULL, 2032, 'system_tool', '{"purpose":"IDE_integration","capabilities":["code_generation","file_editing","project_management","git_integration"],"version":"1.0.0","client_id":"kiro","provider":"kiro","integration_ready":true}', 'none', NULL, NULL),
(2033, 'system_tool', 'zed-ide', 'Zed IDE', @now, @now, 1, 0, NULL, 2033, 'system_tool', '{"purpose":"IDE_integration","capabilities":["code_generation","file_editing","project_management","git_integration"],"version":"1.0.0","client_id":"zed","provider":"zed","integration_ready":true}', 'none', NULL, NULL),
(2034, 'system_tool', 'vscode-ide', 'VS Code IDE', @now, @now, 1, 0, NULL, 2034, 'system_tool', '{"purpose":"IDE_integration","capabilities":["code_generation","file_editing","project_management","git_integration"],"version":"1.0.0","client_id":"vscode","provider":"microsoft","integration_ready":true}', 'none', NULL, NULL),
(2035, 'system_tool', 'antigravity-ide', 'Antigravity IDE', @now, @now, 1, 0, NULL, 2035, 'system_tool', '{"purpose":"VSX_extension_development","capabilities":["project_management","file_editing","semantic_navigation","open_vsx_integration"],"version":"1.0.0","client_id":"antigravity","integration_ready":true}', 'none', NULL, NULL),
(2036, 'external_ai', 'microsoft-copilot', 'Microsoft Copilot', @now, @now, 1, 0, NULL, 2036, 'external_ai', '{"purpose":"AI_assistant","capabilities":["code_generation","debugging","documentation","file_editing","git_integration"],"version":"1.0.0","client_id":"copilot","provider":"microsoft","integration_ready":true}', 'none', NULL, NULL),
(2037, 'external_ai', 'deepseek-lexa', 'DeepSeek LEXA', @now, @now, 1, 0, NULL, 2037, 'external_ai', '{"purpose":"AI_assistant","capabilities":["code_generation","debugging","documentation","file_editing","git_integration"],"version":"1.0.0","client_id":"deepseek_lexa","provider":"deepseek","integration_ready":true}', 'none', NULL, NULL),
(2038, 'external_ai', 'deepseek-lilith', 'DeepSeek LILITH', @now, @now, 1, 0, NULL, 2038, 'external_ai', '{"purpose":"AI_assistant","capabilities":["code_generation","debugging","documentation","file_editing","git_integration"],"version":"1.0.0","client_id":"deepseek_lilith","provider":"deepseek","integration_ready":true}', 'none', NULL, NULL)
ON DUPLICATE KEY UPDATE name = VALUES(name), metadata = VALUES(metadata), updated_ymdhis = @now, is_active = 1, is_deleted = 0;

-- Channel 42: Lupopedia Development (system channel)
INSERT INTO lupo_channels (
    channel_id, federation_node_id, created_by_actor_id, default_actor_id, department_id, 
    channel_key, channel_slug, channel_type, language, channel_name, description, 
    website_link, metadata_json, status_flag, end_ymdhis, duration_seconds, 
    created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis, 
    aal_metadata_json, fleet_composition_json, awareness_version, channel_number, 
    parent_channel_id, is_kernel, boot_sequence_order
) VALUES (
    42, 1, 1, 1, 0, 
    'lupopedia-development', 'lupopedia-development', 'chat_room', 'en', 'Lupopedia Development', 
    'Main development channel for Lupopedia system development, architecture, and coordination.', 
    NULL, '{"purpose": "development", "system": true, "auto_created": true}', 1, 
    NULL, NULL, 20260221000000, 20260221000000, 0, NULL, 
    NULL, NULL, '3.0.0', 42, NULL, 0, 2
);

-- Channel 42 membership: All 25 active actors (excluding actor_id 420)
INSERT INTO lupo_actor_channels (actor_channel_id, actor_id, channel_id, created_by_actor_id, status, start_date, channel_color, last_read_ymdhis, muted_until_ymdhis, preferences_json, dialog_output_file, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis) 
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
INSERT IGNORE INTO lupo_actor_departments (actor_department_id, actor_id, department_id, role_key, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis) 
VALUES 
(12031, 2031, 0, 'member', @now, @now, 0, NULL),
(12032, 2032, 0, 'member', @now, @now, 0, NULL),
(12033, 2033, 0, 'member', @now, @now, 0, NULL),
(12034, 2034, 0, 'member', @now, @now, 0, NULL),
(12035, 2035, 0, 'member', @now, @now, 0, NULL),
(12036, 2036, 0, 'member', @now, @now, 0, NULL),
(12037, 2037, 0, 'member', @now, @now, 0, NULL),
(12038, 2038, 0, 'member', @now, @now, 0, NULL)
ON DUPLICATE KEY UPDATE role_key = VALUES(role_key), updated_ymdhis = @now, is_deleted = 0;

-- ============================================================
-- WARP IDE ACTOR INTEGRATION (Lupopedia 4.0.25)
-- ============================================================
-- Warp IDE: actor_id 2039, system_tool, federation_node_id 0 (local node)
-- paired_actor_id = 10000 (human operator)
-- ============================================================

-- Registry entry for Warp IDE
INSERT IGNORE INTO lupo_registry (registry_id, entity_type, entity_index_id, entity_index, entity_key, entity_name, entity_table, federation_node_id, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis, is_active, is_kernel, metadata_json) 
VALUES 
(9002039, 'actor', 2039, 2039, 'warp-ide', 'Warp IDE', 'lupo_actors', 0, @now, @now, 0, NULL, 1, 0, '{"actor_source_type":"system_tool","client_id":"warp","provider":"warp","purpose":"IDE_integration","paired_actor_id":10000}')
ON DUPLICATE KEY UPDATE entity_name = VALUES(entity_name), metadata_json = VALUES(metadata_json), updated_ymdhis = @now, is_deleted = 0, is_active = 1;

-- Actor record for Warp IDE (with paired_actor_id)
INSERT IGNORE INTO lupo_actors (actor_id, actor_type, slug, name, created_ymdhis, updated_ymdhis, is_active, is_deleted, deleted_ymdhis, actor_source_id, actor_source_type, metadata, adversarial_role, adversarial_oversight_actor_id, avatar_hash, paired_actor_id)
VALUES (2039, 'system_tool', 'warp-ide', 'Warp IDE', @now, @now, 1, 0, NULL, 2039, 'system_tool', '{"purpose":"IDE_integration","capabilities":["code_generation","file_editing","project_management","git_integration","terminal_integration"],"version":"1.0.0","client_id":"warp","provider":"warp","integration_ready":true,"paired_actor_id":10000}', 'none', NULL, NULL, 10000)
ON DUPLICATE KEY UPDATE name = VALUES(name), metadata = VALUES(metadata), paired_actor_id = VALUES(paired_actor_id), updated_ymdhis = @now, is_active = 1, is_deleted = 0;

-- Channel 42 membership for Warp IDE
INSERT INTO lupo_actor_channels (actor_channel_id, actor_id, channel_id, created_by_actor_id, status, start_date, channel_color, last_read_ymdhis, muted_until_ymdhis, preferences_json, dialog_output_file, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis)
VALUES (12039, 2039, 42, 1000, 'A', 20260221000000, 'F7FAFF', NULL, NULL, '{"purpose":"IDE_integration","capabilities":["code_generation","file_editing","project_management","terminal_integration"],"paired_actor_id":10000}', NULL, @now, @now, 0, NULL)
ON DUPLICATE KEY UPDATE actor_id = VALUES(actor_id), channel_id = VALUES(channel_id), updated_ymdhis = @now, is_deleted = 0;

-- Department 0 membership for Warp IDE
INSERT IGNORE INTO lupo_actor_departments (actor_department_id, actor_id, department_id, role_key, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis)
VALUES (12039, 2039, 0, 'member', @now, @now, 0, NULL)
ON DUPLICATE KEY UPDATE role_key = VALUES(role_key), updated_ymdhis = @now, is_deleted = 0;

-- ============================================================
-- WINDSURF IDE ACTOR INTEGRATION (Lupopedia 4.0.25)
-- ============================================================
-- Windsurf IDE: actor_id 2040 (reassigned from conflicting actor_id 2 = CAPTAIN)
-- paired_actor_id = 10000 (human operator), federation_node_id 0 (local node)
-- ============================================================

-- Registry entry for Windsurf IDE
INSERT IGNORE INTO lupo_registry (registry_id, entity_type, entity_index_id, entity_index, entity_key, entity_name, entity_table, federation_node_id, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis, is_active, is_kernel, metadata_json) 
VALUES 
(9002040, 'actor', 2040, 2040, 'windsurf-ide', 'Windsurf IDE', 'lupo_actors', 0, @now, @now, 0, NULL, 1, 0, '{"actor_source_type":"system_tool","client_id":"windsurf","provider":"windsurf","purpose":"IDE_integration","paired_actor_id":10000}')
ON DUPLICATE KEY UPDATE entity_name = VALUES(entity_name), metadata_json = VALUES(metadata_json), updated_ymdhis = @now, is_deleted = 0, is_active = 1;

-- Actor record for Windsurf IDE (with paired_actor_id)
INSERT IGNORE INTO lupo_actors (actor_id, actor_type, slug, name, created_ymdhis, updated_ymdhis, is_active, is_deleted, deleted_ymdhis, actor_source_id, actor_source_type, metadata, adversarial_role, adversarial_oversight_actor_id, avatar_hash, paired_actor_id)
VALUES (2040, 'system_tool', 'windsurf-ide', 'Windsurf IDE', @now, @now, 1, 0, NULL, 2040, 'system_tool', '{"purpose":"IDE_integration","capabilities":["code_generation","file_editing","project_management","git_integration","vsx_extension_development"],"version":"1.0.0","client_id":"windsurf","provider":"windsurf","integration_ready":true,"paired_actor_id":10000,"note":"Reassigned from actor_id 2 to avoid CAPTAIN conflict"}', 'none', NULL, NULL, 10000)
ON DUPLICATE KEY UPDATE name = VALUES(name), metadata = VALUES(metadata), paired_actor_id = VALUES(paired_actor_id), updated_ymdhis = @now, is_active = 1, is_deleted = 0;

-- Channel 42 membership for Windsurf IDE
INSERT INTO lupo_actor_channels (actor_channel_id, actor_id, channel_id, created_by_actor_id, status, start_date, channel_color, last_read_ymdhis, muted_until_ymdhis, preferences_json, dialog_output_file, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis)
VALUES (12040, 2040, 42, 1000, 'A', 20260221000000, 'F7FAFF', NULL, NULL, '{"purpose":"IDE_integration","capabilities":["code_generation","file_editing","project_management","vsx_extension_development"],"paired_actor_id":10000}', NULL, @now, @now, 0, NULL)
ON DUPLICATE KEY UPDATE actor_id = VALUES(actor_id), channel_id = VALUES(channel_id), updated_ymdhis = @now, is_deleted = 0;

-- Department 0 membership for Windsurf IDE
INSERT IGNORE INTO lupo_actor_departments (actor_department_id, actor_id, department_id, role_key, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis)
VALUES (12040, 2040, 0, 'member', @now, @now, 0, NULL)
ON DUPLICATE KEY UPDATE role_key = VALUES(role_key), updated_ymdhis = @now, is_deleted = 0;

-- ============================================================
-- PAIRED ACTOR + FEDERATION NODE FIXES (Lupopedia 4.0.25)
-- ============================================================
-- Fix paired_actor_id for Copilot (2036) and LILITH (2038)
-- Fix federation_node_id: Copilot/LILITH = 1 (remote), others local = 0
-- ============================================================

-- paired_actor_id: Copilot → 10000 (human operator)
UPDATE lupo_actors SET paired_actor_id = 10000, updated_ymdhis = COALESCE(@now, updated_ymdhis) WHERE actor_id = 2036 AND (paired_actor_id IS NULL OR paired_actor_id = 0);
-- paired_actor_id: LILITH → 10000 (human operator)
UPDATE lupo_actors SET paired_actor_id = 10000, updated_ymdhis = COALESCE(@now, updated_ymdhis) WHERE actor_id = 2038 AND (paired_actor_id IS NULL OR paired_actor_id = 0);

-- ============================================================
-- FLIP v2: use lupo_artifacts with entity_type = 'flip_artifact'
-- ============================================================
-- FLIP/WOLFIE header and footer index: store in lupo_artifacts with
-- entity_type = 'flip_artifact', channel_id, artifact_kind, file_path_from_root;
-- FLIP-specific data (header_json, footer_json, agent_slug, etc.) in metadata JSON.
-- ============================================================

-- ============================================================
-- FLIP v2 REGISTRY ENTRIES (Lupopedia 4.0.37)
-- ============================================================

-- FLIP Schema Version Registry
INSERT INTO lupo_registry (registry_id, entity_type, entity_index_id, entity_index, entity_key, entity_name, federation_node_id, metadata_json, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis)
VALUES (9005001, 'flip_schema_version', 1, 1, 'v2.0', 'FLIP Schema Version 2.0', 1, '{"version": "2.0", "features": ["relationship_mapping", "enhanced_attribution", "semantic_inference"]}', @now, @now, 0, NULL)
ON DUPLICATE KEY UPDATE entity_name = VALUES(entity_name), metadata_json = VALUES(metadata_json), updated_ymdhis = @now, is_deleted = 0;

-- Artifact Kind Registry
INSERT INTO lupo_registry (registry_id, entity_type, entity_index_id, entity_index, entity_key, entity_name, federation_node_id, metadata_json, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis)
VALUES (9005002, 'artifact_kind', 1, 1, 'header', 'FLIP Header Artifact', 1, '{"description": "FLIP/WOLFIE header metadata"}', @now, @now, 0, NULL)
ON DUPLICATE KEY UPDATE entity_name = VALUES(entity_name), metadata_json = VALUES(metadata_json), updated_ymdhis = @now, is_deleted = 0;

INSERT INTO lupo_registry (registry_id, entity_type, entity_index_id, entity_index, entity_key, entity_name, federation_node_id, metadata_json, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis)
VALUES (9005003, 'artifact_kind', 2, 2, 'footer', 'FLIP Footer Artifact', 1, '{"description": "FLIP footer metadata and relationships"}', @now, @now, 0, NULL)
ON DUPLICATE KEY UPDATE entity_name = VALUES(entity_name), metadata_json = VALUES(metadata_json), updated_ymdhis = @now, is_deleted = 0;

-- Edge Type Registry
INSERT INTO lupo_registry (registry_id, entity_type, entity_index_id, entity_index, entity_key, entity_name, federation_node_id, metadata_json, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis)
VALUES (9005004, 'edge_type', 1, 1, 'inbound_edge', 'File Inbound Edge', 1, '{"description": "References pointing to this file"}', @now, @now, 0, NULL)
ON DUPLICATE KEY UPDATE entity_name = VALUES(entity_name), metadata_json = VALUES(metadata_json), updated_ymdhis = @now, is_deleted = 0;

INSERT INTO lupo_registry (registry_id, entity_type, entity_index_id, entity_index, entity_key, entity_name, federation_node_id, metadata_json, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis)
VALUES (9005005, 'edge_type', 2, 2, 'semantic_relationship', 'Semantic Relationship', 1, '{"description": "Semantic relationships between files"}', @now, @now, 0, NULL)
ON DUPLICATE KEY UPDATE entity_name = VALUES(entity_name), metadata_json = VALUES(metadata_json), updated_ymdhis = @now, is_deleted = 0;

-- ============================================================
-- TASK MANAGEMENT SYSTEM (Lupopedia 4.0.45)
-- ============================================================
-- Tables for task management and offline task import support
-- Supports MD file task import and database-driven task tracking
-- Added: 2026-02-25 by Kiro (1000)
-- Task lookup tables removed in v4.0.55 - consolidated into lupo_tasks VARCHAR columns

-- Core Tasks Table
CREATE TABLE lupo_tasks (
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
  task_priority varchar(64),
  parent_agent_id bigint DEFAULT NULL,
  consensus_hash varchar(255) DEFAULT NULL,
  approval_chain_json json DEFAULT NULL,
  task_embeddings text DEFAULT NULL,
  PRIMARY KEY (task_id)
);

CREATE UNIQUE INDEX lupo_tasks_uniq_task_key_per_channel ON lupo_tasks (task_key, channel_id);
CREATE INDEX lupo_tasks_idx_channel_id ON lupo_tasks (channel_id);
CREATE INDEX lupo_tasks_idx_owner_actor_id ON lupo_tasks (owner_actor_id);
CREATE INDEX lupo_tasks_idx_task_type ON lupo_tasks (task_type);
CREATE INDEX lupo_tasks_idx_task_status ON lupo_tasks (task_status);
CREATE INDEX lupo_tasks_idx_task_priority ON lupo_tasks (task_priority);
CREATE INDEX lupo_tasks_idx_created_ymdhis ON lupo_tasks (created_ymdhis);
CREATE INDEX lupo_tasks_idx_acting_as_actor_id ON lupo_tasks (acting_as_actor_id);
CREATE INDEX lupo_tasks_idx_is_deleted ON lupo_tasks (is_deleted);
CREATE INDEX lupo_tasks_idx_parent_agent_id ON lupo_tasks (parent_agent_id);
-- RESERVED ID DOCTRINE: task_id is NOT AUTO_INCREMENT; application must supply explicit ID.

-- lupo_rolls: actor roles in channels (multi-agent evolution)
CREATE TABLE lupo_rolls (
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
CREATE INDEX lupo_rolls_idx_channel_actor ON lupo_rolls (channel_id, actor_id);
CREATE INDEX lupo_rolls_idx_role ON lupo_rolls (role_slug);

-- Task Assignments
-- Task Dependencies
-- Old task events table removed in v4.0.55 - consolidated into lupo_unified_log

-- ============================================================
-- END OF TASK MANAGEMENT SYSTEM
-- ============================================================

-- ============================================================
-- ACTOR IDENTITY CAPSULE SYSTEM (v4.0.48)
-- ============================================================

-- Table 1: lupo_actor_history
-- Stores structured actor achievement and contribution history
CREATE TABLE lupo_actor_history (
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

CREATE INDEX lupo_actor_history_idx_actor_id ON lupo_actor_history(actor_id);
CREATE INDEX lupo_actor_history_idx_date_ymdhis ON lupo_actor_history(date_ymdhis);
CREATE INDEX lupo_actor_history_idx_channel_id ON lupo_actor_history(channel_id);
CREATE INDEX lupo_actor_history_idx_is_deleted ON lupo_actor_history(is_deleted);

-- Table 2: lupo_actor_relationship_rules
-- Defines governance rules for actor interactions
-- Table 3: lupo_capability_usage
-- Tracks actor capability utilization and performance
CREATE TABLE lupo_capability_usage (
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

CREATE INDEX lupo_capability_usage_idx_actor_capability ON lupo_capability_usage(actor_id, capability);
CREATE INDEX lupo_capability_usage_idx_capability ON lupo_capability_usage(capability);
CREATE INDEX lupo_capability_usage_idx_last_used ON lupo_capability_usage(last_used_ymdhis);
CREATE INDEX lupo_capability_usage_idx_is_deleted ON lupo_capability_usage(is_deleted);

-- Table 4: lupo_llm_performance
-- Monitors LLM module performance across actors
-- Table 5: lupo_federated_trust
-- Manages trust relationships between federated nodes
-- Table 6: lupo_session_recovery
-- Enables session state recovery across restarts
-- ============================================================
-- FLARE FEDERATION NODE CONTENT MANAGEMENT
-- ============================================================

-- Table: lupo_channel_content
-- Manages federation node content and web path mapping
CREATE TABLE lupo_channel_content (
  channel_content_id bigint NOT NULL AUTO_INCREMENT,
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
CREATE INDEX lupo_channel_content_idx_channel ON lupo_channel_content (channel_id);
CREATE INDEX lupo_channel_content_idx_federation_node ON lupo_channel_content (federation_node_id);
CREATE INDEX lupo_channel_content_idx_file_path ON lupo_channel_content (file_path);
CREATE INDEX lupo_channel_content_idx_web_path ON lupo_channel_content (web_path);
CREATE INDEX lupo_channel_content_idx_created ON lupo_channel_content (created_ymdhis);
CREATE INDEX lupo_channel_content_idx_updated ON lupo_channel_content (updated_ymdhis);
CREATE INDEX lupo_channel_content_idx_is_deleted ON lupo_channel_content (is_deleted);

-- Insert canonical FLARE entry for federation node 0
INSERT INTO lupo_channel_content
(channel_id, federation_node_id, file_path, web_path, metadata_json, created_ymdhis, updated_ymdhis, is_deleted)
VALUES
(
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
-- RULES SYSTEM (4.0.68)
-- ============================================================
-- lupo_rules: canonical registry of rules (rule_id explicit; no AUTO_INCREMENT per registry doctrine)
CREATE TABLE lupo_rules (
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
CREATE INDEX lupo_rules_idx_rule_type ON lupo_rules (rule_type);
CREATE INDEX lupo_rules_idx_rule_name ON lupo_rules (rule_name);
CREATE INDEX lupo_rules_idx_is_deleted ON lupo_rules (is_deleted);

CREATE TABLE lupo_rule_targets (
  rule_target_id bigint NOT NULL AUTO_INCREMENT,
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
CREATE INDEX lupo_rule_targets_idx_rule_target ON lupo_rule_targets (rule_id, target_table, target_id);
CREATE INDEX lupo_rule_targets_idx_target ON lupo_rule_targets (target_table, target_id);
CREATE INDEX lupo_rule_targets_idx_is_deleted ON lupo_rule_targets (is_deleted);

CREATE TABLE lupo_rule_logs (
  rule_log_id bigint NOT NULL AUTO_INCREMENT,
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
CREATE INDEX lupo_rule_logs_idx_rule_id ON lupo_rule_logs (rule_id);
CREATE INDEX lupo_rule_logs_idx_target ON lupo_rule_logs (target_table, target_id);
CREATE INDEX lupo_rule_logs_idx_actor_id ON lupo_rule_logs (actor_id);
CREATE INDEX lupo_rule_logs_idx_created_ymdhis ON lupo_rule_logs (created_ymdhis);

-- ============================================================
-- COMMENTS SYSTEM (4.0.73)
-- ============================================================
-- lupo_comments: comments on artifacts, documents, and content
CREATE TABLE lupo_comments (
  comment_id bigint NOT NULL AUTO_INCREMENT,
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
CREATE INDEX lupo_comments_idx_target ON lupo_comments (target_type, target_id);
CREATE INDEX lupo_comments_idx_channel_id ON lupo_comments (channel_id);
CREATE INDEX lupo_comments_idx_actor_id ON lupo_comments (actor_id);
CREATE INDEX lupo_comments_idx_faucet_id ON lupo_comments (faucet_id);
CREATE INDEX lupo_comments_idx_parent_comment_id ON lupo_comments (parent_comment_id);
CREATE INDEX lupo_comments_idx_created_ymdhis ON lupo_comments (created_ymdhis);
CREATE INDEX lupo_comments_idx_is_deleted ON lupo_comments (is_deleted);

-- ============================================================
-- END OF ACTOR IDENTITY CAPSULE SYSTEM
-- ============================================================

-- =============================================================================
-- lupo_orchestrator_rules (optional; v4.0.73 — orchestrator rule storage)
-- =============================================================================
CREATE TABLE lupo_orchestrator_rules (
  rule_id bigint NOT NULL AUTO_INCREMENT,
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
CREATE UNIQUE INDEX lupo_orchestrator_rules_uniq_slug ON lupo_orchestrator_rules (rule_slug);
CREATE INDEX lupo_orchestrator_rules_idx_actor_version ON lupo_orchestrator_rules (orchestrator_actor, rule_set_version);
CREATE INDEX lupo_orchestrator_rules_idx_active ON lupo_orchestrator_rules (is_active);
CREATE INDEX lupo_orchestrator_rules_idx_updated ON lupo_orchestrator_rules (updated_ymdhis);

-- Database-Backed Visibility Extensions (Thread 1031 - Phase 1)
-- These extensions support web UI visibility for channels, threads, and tasks

-- lupo_channels table extensions for visibility support
ALTER TABLE lupo_channels ADD COLUMN visibility_status varchar(32) NOT NULL DEFAULT 'active';
ALTER TABLE lupo_channels ADD COLUMN channel_type varchar(32) NOT NULL DEFAULT 'protocol';
ALTER TABLE lupo_channels ADD COLUMN owner_actor_id bigint NOT NULL DEFAULT 1;
ALTER TABLE lupo_channels ADD COLUMN access_level varchar(32) NOT NULL DEFAULT 'public';
ALTER TABLE lupo_channels ADD COLUMN channel_metadata json DEFAULT NULL;
ALTER TABLE lupo_channels ADD COLUMN ui_preferences json DEFAULT NULL;
ALTER TABLE lupo_channels ADD COLUMN last_activity_ymdhis bigint NOT NULL DEFAULT 0;

-- Indexes for lupo_channels visibility extensions
CREATE INDEX lupo_channels_idx_visibility_status ON lupo_channels (visibility_status);
CREATE INDEX lupo_channels_idx_owner_actor_id ON lupo_channels (owner_actor_id);
CREATE INDEX lupo_channels_idx_access_level ON lupo_channels (access_level);
CREATE INDEX lupo_channels_idx_last_activity ON lupo_channels (last_activity_ymdhis);

-- lupo_dialog_threads table extensions for visibility and hierarchy support
ALTER TABLE lupo_dialog_threads ADD COLUMN parent_thread_id bigint DEFAULT NULL;
ALTER TABLE lupo_dialog_threads ADD COLUMN root_thread_id bigint DEFAULT NULL;
ALTER TABLE lupo_dialog_threads ADD COLUMN thread_depth int NOT NULL DEFAULT 0;
ALTER TABLE lupo_dialog_threads ADD COLUMN visibility_status varchar(32) NOT NULL DEFAULT 'active';
ALTER TABLE lupo_dialog_threads ADD COLUMN owner_actor_id bigint NOT NULL;
ALTER TABLE lupo_dialog_threads ADD COLUMN assigned_actor_id bigint DEFAULT NULL;
ALTER TABLE lupo_dialog_threads ADD COLUMN thread_type varchar(32) NOT NULL DEFAULT 'discussion';
ALTER TABLE lupo_dialog_threads ADD COLUMN thread_priority varchar(32) NOT NULL DEFAULT 'normal';
ALTER TABLE lupo_dialog_threads ADD COLUMN thread_metadata json DEFAULT NULL;
ALTER TABLE lupo_dialog_threads ADD COLUMN review_status varchar(32) DEFAULT NULL;
ALTER TABLE lupo_dialog_threads ADD COLUMN review_actor_id bigint DEFAULT NULL;
ALTER TABLE lupo_dialog_threads ADD COLUMN review_ymdhis bigint DEFAULT NULL;

-- Indexes for lupo_dialog_threads visibility extensions
CREATE INDEX lupo_dialog_threads_idx_parent_thread_id ON lupo_dialog_threads (parent_thread_id);
CREATE INDEX lupo_dialog_threads_idx_root_thread_id ON lupo_dialog_threads (root_thread_id);
CREATE INDEX lupo_dialog_threads_idx_thread_depth ON lupo_dialog_threads (thread_depth);
CREATE INDEX lupo_dialog_threads_idx_visibility_status ON lupo_dialog_threads (visibility_status);
CREATE INDEX lupo_dialog_threads_idx_owner_actor_id ON lupo_dialog_threads (owner_actor_id);
CREATE INDEX lupo_dialog_threads_idx_assigned_actor_id ON lupo_dialog_threads (assigned_actor_id);
CREATE INDEX lupo_dialog_threads_idx_thread_type ON lupo_dialog_threads (thread_type);
CREATE INDEX lupo_dialog_threads_idx_thread_priority ON lupo_dialog_threads (thread_priority);
CREATE INDEX lupo_dialog_threads_idx_review_status ON lupo_dialog_threads (review_status);
CREATE INDEX lupo_dialog_threads_idx_review_actor_id ON lupo_dialog_threads (review_actor_id);
CREATE INDEX lupo_dialog_threads_idx_review_ymdhis ON lupo_dialog_threads (review_ymdhis);

-- lupo_tasks table extensions for visibility and review support
ALTER TABLE lupo_tasks ADD COLUMN visibility_status varchar(32) NOT NULL DEFAULT 'active';
ALTER TABLE lupo_tasks ADD COLUMN assigned_actor_id bigint DEFAULT NULL;
ALTER TABLE lupo_tasks ADD COLUMN reviewer_actor_id bigint DEFAULT NULL;
ALTER TABLE lupo_tasks ADD COLUMN review_status varchar(32) DEFAULT NULL;
ALTER TABLE lupo_tasks ADD COLUMN review_ymdhis bigint DEFAULT NULL;
ALTER TABLE lupo_tasks ADD COLUMN task_dependencies json DEFAULT NULL;

-- Indexes for lupo_tasks visibility extensions
CREATE INDEX lupo_tasks_idx_visibility_status ON lupo_tasks (visibility_status);
CREATE INDEX lupo_tasks_idx_assigned_actor_id ON lupo_tasks (assigned_actor_id);
CREATE INDEX lupo_tasks_idx_reviewer_actor_id ON lupo_tasks (reviewer_actor_id);
CREATE INDEX lupo_tasks_idx_review_status ON lupo_tasks (review_status);
CREATE INDEX lupo_tasks_idx_review_ymdhis ON lupo_tasks (review_ymdhis);

-- New canonical table: lupo_visibility_state for granular visibility permissions
CREATE TABLE lupo_visibility_state (
  visibility_id bigint NOT NULL,
  entity_type varchar(50) NOT NULL,
  entity_id bigint NOT NULL,
  visibility_level varchar(32) NOT NULL DEFAULT 'public',
  access_actor_id bigint DEFAULT NULL,
  granted_ymdhis bigint NOT NULL DEFAULT 0,
  expires_ymdhis bigint DEFAULT NULL,
  granted_by_actor_id bigint NOT NULL,
  visibility_reason varchar(255) DEFAULT NULL,
  metadata_json json DEFAULT NULL,
  is_deleted tinyint NOT NULL DEFAULT '0',
  deleted_ymdhis bigint DEFAULT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL,
  PRIMARY KEY (visibility_id)
);

-- Indexes for lupo_visibility_state table
CREATE INDEX lupo_visibility_state_idx_entity ON lupo_visibility_state (entity_type, entity_id);
CREATE INDEX lupo_visibility_state_idx_actor ON lupo_visibility_state (access_actor_id);
CREATE INDEX lupo_visibility_state_idx_level ON lupo_visibility_state (visibility_level);
CREATE INDEX lupo_visibility_state_idx_created ON lupo_visibility_state (created_ymdhis);
CREATE INDEX lupo_visibility_state_idx_granted_actor ON lupo_visibility_state (granted_by_actor_id);
CREATE INDEX lupo_visibility_state_idx_expires ON lupo_visibility_state (expires_ymdhis);
CREATE INDEX lupo_visibility_state_idx_is_deleted ON lupo_visibility_state (is_deleted);

-- RESERVED ID DOCTRINE: visibility_id is NOT AUTO_INCREMENT; application must supply explicit ID.
