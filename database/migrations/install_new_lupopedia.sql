-- FILE: database/migrations/install_new_lupopedia.sql
-- TYPE: sql
-- Purpose: Install brand-new Lupopedia database from scratch. DB-agnostic (MySQL + PostgreSQL).
-- Doctrine 17: no FKs, no triggers, BIGINT timestamps, no display widths, no UNSIGNED.
-- No Crafty Syntax logic, no migration, no DROP TABLE.

CREATE TABLE lupo_actors (
  actor_id bigint NOT NULL,
  actor_type varchar(64) NOT NULL,
  slug varchar(255) NOT NULL,
  name varchar(255) NOT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL,
  is_active tinyint NOT NULL DEFAULT '1',
  is_deleted tinyint NOT NULL DEFAULT '0',
  deleted_ymdhis bigint DEFAULT NULL,
  actor_source_id bigint DEFAULT NULL,
  actor_source_type varchar(50) DEFAULT NULL,
  metadata text,
  adversarial_role varchar(64) DEFAULT 'none',
  adversarial_oversight_actor_id bigint DEFAULT NULL,
  avatar_hash varchar(64) DEFAULT NULL,
  paired_actor_id bigint NOT NULL DEFAULT 0,
  PRIMARY KEY (actor_id)
);

CREATE UNIQUE INDEX lupo_actors_unique_slug ON lupo_actors (slug);
CREATE INDEX lupo_actors_idx_actor_type ON lupo_actors (actor_type);
CREATE INDEX lupo_actors_idx_is_active ON lupo_actors (is_active);
CREATE INDEX lupo_actors_idx_created_ymdhis ON lupo_actors (created_ymdhis);
-- RESERVED ID DOCTRINE: actor_id is NOT AUTO_INCREMENT; application must supply explicit ID.

CREATE TABLE lupo_banned_actors (
  banned_actor_id bigint NOT NULL,
  actor_id bigint NOT NULL,
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
CREATE INDEX lupo_actor_channels_idx_channel ON lupo_actor_channels (channel_id);
CREATE INDEX lupo_actor_channels_idx_status ON lupo_actor_channels (status);
CREATE INDEX lupo_actor_channels_idx_created ON lupo_actor_channels (created_ymdhis);
CREATE INDEX lupo_actor_channels_idx_updated ON lupo_actor_channels (updated_ymdhis);
CREATE INDEX lupo_actor_channels_idx_deleted ON lupo_actor_channels (is_deleted);

CREATE TABLE lupo_actor_channel_roles (
  actor_channel_role_id bigint NOT NULL,
  actor_id bigint NOT NULL,
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
  title varchar(64) DEFAULT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL,
  is_deleted tinyint NOT NULL DEFAULT '0',
  deleted_ymdhis bigint DEFAULT NULL,
  PRIMARY KEY (actor_department_id)
);

CREATE INDEX lupo_actor_departments_idx_actor ON lupo_actor_departments (actor_id);
CREATE INDEX lupo_actor_departments_idx_department ON lupo_actor_departments (department_id);

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

CREATE TABLE lupo_actor_events (
  actor_event_id bigint NOT NULL,
  actor_id bigint NOT NULL,
  session_id varchar(255) DEFAULT NULL,
  tab_id varchar(255) DEFAULT NULL,
  world_id bigint DEFAULT NULL,
  world_key varchar(255) DEFAULT NULL,
  world_type varchar(50) DEFAULT NULL,
  event_type varchar(100) NOT NULL,
  event_data json DEFAULT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  PRIMARY KEY (actor_event_id)
);

CREATE INDEX lupo_actor_events_idx_actor_id ON lupo_actor_events (actor_id);
CREATE INDEX lupo_actor_events_idx_session_id ON lupo_actor_events (session_id);
CREATE INDEX lupo_actor_events_idx_tab_id ON lupo_actor_events (tab_id);
CREATE INDEX lupo_actor_events_idx_world_id ON lupo_actor_events (world_id);
CREATE INDEX lupo_actor_events_idx_event_type ON lupo_actor_events (event_type);
CREATE INDEX lupo_actor_events_idx_created_ymdhis ON lupo_actor_events (created_ymdhis);
CREATE INDEX lupo_actor_events_idx_actor_event_type ON lupo_actor_events (actor_id, event_type);

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

CREATE TABLE lupo_actor_meta (
  actor_meta_id bigint NOT NULL,
  actor_id bigint NOT NULL,
  meta_type varchar(64) NOT NULL,
  meta_key varchar(255) NOT NULL,
  meta_value text NOT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL,
  PRIMARY KEY (actor_meta_id)
);

CREATE INDEX lupo_actor_meta_actor_id ON lupo_actor_meta (actor_id);
CREATE INDEX lupo_actor_meta_meta_type ON lupo_actor_meta (meta_type);
CREATE INDEX lupo_actor_meta_meta_key ON lupo_actor_meta (meta_key);

CREATE TABLE lupo_actor_moods (
  actor_id bigint NOT NULL,
  mood_r tinyint NOT NULL,
  mood_g tinyint NOT NULL,
  mood_b tinyint NOT NULL,
  mood_framework varchar(32) NOT NULL DEFAULT 'western_analytical',
  timestamp_utc bigint NOT NULL
);

CREATE TABLE lupo_actor_object_edges (
  actor_object_edge_id bigint NOT NULL,
  actor_id bigint NOT NULL,
  target_table varchar(100) NOT NULL,
  target_id bigint NOT NULL,
  edge_type varchar(50) NOT NULL,
  properties_json json DEFAULT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL,
  PRIMARY KEY (actor_object_edge_id)
);

CREATE UNIQUE INDEX lupo_actor_object_edges_uniq_actor_target_type ON lupo_actor_object_edges (actor_id, target_table, target_id, edge_type);
CREATE INDEX lupo_actor_object_edges_idx_actor_edge_type ON lupo_actor_object_edges (actor_id, edge_type);
CREATE INDEX lupo_actor_object_edges_idx_target_lookup ON lupo_actor_object_edges (target_table, target_id);

CREATE TABLE lupo_actor_persona_relationships (
  relationship_id bigint NOT NULL,
  actor_id bigint NOT NULL,
  persona_id bigint NOT NULL,
  relationship_type varchar(100) NOT NULL,
  relationship_strength decimal(5,2) DEFAULT NULL,
  relationship_context json DEFAULT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL,
  PRIMARY KEY (relationship_id)
);

CREATE INDEX lupo_actor_persona_relationships_idx_actor_id ON lupo_actor_persona_relationships (actor_id);
CREATE INDEX lupo_actor_persona_relationships_idx_persona_id ON lupo_actor_persona_relationships (persona_id);
CREATE INDEX lupo_actor_persona_relationships_idx_relationship_type ON lupo_actor_persona_relationships (relationship_type);

CREATE TABLE lupo_actor_properties (
  actor_property_id bigint NOT NULL,
  actor_type varchar(32) NOT NULL,
  actor_id bigint NOT NULL,
  property_key varchar(64) NOT NULL,
  property_value text,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL,
  is_deleted tinyint NOT NULL DEFAULT '0',
  deleted_ymdhis bigint DEFAULT NULL,
  PRIMARY KEY (actor_property_id)
);

CREATE INDEX lupo_actor_properties_idx_entity ON lupo_actor_properties (actor_type, actor_id);
CREATE INDEX lupo_actor_properties_idx_property ON lupo_actor_properties (property_key);

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

CREATE TABLE lupo_actor_truth_edges (
  actor_truth_edge_id bigint NOT NULL,
  actor_id bigint NOT NULL,
  truth_item_id bigint NOT NULL,
  edge_type varchar(64) NOT NULL,
  properties_json json DEFAULT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL,
  PRIMARY KEY (actor_truth_edge_id)
);

CREATE UNIQUE INDEX lupo_actor_truth_edges_uniq_actor_truth_type ON lupo_actor_truth_edges (actor_id, truth_item_id, edge_type);
CREATE INDEX lupo_actor_truth_edges_idx_actor_edge_type ON lupo_actor_truth_edges (actor_id, edge_type);
CREATE INDEX lupo_actor_truth_edges_idx_truth_item ON lupo_actor_truth_edges (truth_item_id);

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
  is_deleted tinyint NOT NULL DEFAULT '0',
  deleted_ymdhis bigint DEFAULT NULL,
  PRIMARY KEY (heartbeat_id)
);

CREATE INDEX lupo_agent_heartbeats_idx_agent_slug ON lupo_agent_heartbeats (agent_slug);
CREATE INDEX lupo_agent_heartbeats_idx_last_heartbeat_ymdhis ON lupo_agent_heartbeats (last_heartbeat_ymdhis);
CREATE INDEX lupo_agent_heartbeats_idx_created_ymdhis ON lupo_agent_heartbeats (created_ymdhis);
CREATE INDEX lupo_agent_heartbeats_idx_is_deleted ON lupo_agent_heartbeats (is_deleted);

CREATE TABLE lupo_agent_properties (
  agent_property_id bigint NOT NULL,
  actor_id bigint NOT NULL,
  domain_id bigint NOT NULL,
  property_key varchar(100) NOT NULL,
  property_value text,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint DEFAULT NULL,
  is_deleted tinyint NOT NULL DEFAULT '0',
  deleted_ymdhis bigint DEFAULT NULL,
  PRIMARY KEY (agent_property_id)
);

CREATE UNIQUE INDEX lupo_agent_properties_unique_agent_domain_property ON lupo_agent_properties (actor_id, domain_id, property_key);
CREATE INDEX lupo_agent_properties_idx_agent_domain ON lupo_agent_properties (actor_id, domain_id);
CREATE INDEX lupo_agent_properties_idx_domain_id ON lupo_agent_properties (domain_id);
CREATE INDEX lupo_agent_properties_idx_property_key ON lupo_agent_properties (property_key);
CREATE INDEX lupo_agent_properties_idx_created_ymdhis ON lupo_agent_properties (created_ymdhis);
CREATE INDEX lupo_agent_properties_idx_updated_ymdhis ON lupo_agent_properties (updated_ymdhis);
CREATE INDEX lupo_agent_properties_idx_is_deleted ON lupo_agent_properties (is_deleted);

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
  completed_ymdhis bigint DEFAULT NULL,
  PRIMARY KEY (agent_tool_call_id)
);

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

CREATE TABLE lupo_aliases (
  alias_id int NOT NULL,
  slug varchar(255) NOT NULL,
  alias varchar(255) NOT NULL,
  alias_type varchar(50) DEFAULT 'semantic',
  created_at bigint,
  PRIMARY KEY (alias_id)
);

CREATE UNIQUE INDEX lupo_aliases_uniq_alias ON lupo_aliases (alias);
CREATE INDEX lupo_aliases_idx_slug ON lupo_aliases (slug);

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


CREATE TABLE lupo_analytics_referers_periods (
  analytics_referers_period_id bigint NOT NULL,
  content_id bigint NOT NULL DEFAULT '0',
  url_path varchar(500) NOT NULL DEFAULT '',
  referer_content_id bigint NOT NULL DEFAULT '0',
  referer_url_path varchar(500) NOT NULL DEFAULT '',
  parent_id bigint NOT NULL DEFAULT '0',
  level int NOT NULL DEFAULT '1',
  department_id bigint NOT NULL DEFAULT '1',
  period_type varchar(64) NOT NULL,
  period_date bigint NOT NULL,
  visits int NOT NULL DEFAULT '0',
  direct_visits int NOT NULL DEFAULT '0',
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL,
  PRIMARY KEY (analytics_referers_period_id)
);

CREATE UNIQUE INDEX lupo_analytics_referers_periods_uq_referer_period ON lupo_analytics_referers_periods (content_id, referer_content_id, period_type, period_date);
CREATE INDEX lupo_analytics_referers_periods_idx_period_date ON lupo_analytics_referers_periods (period_date);
CREATE INDEX lupo_analytics_referers_periods_idx_content ON lupo_analytics_referers_periods (content_id, period_date);
CREATE INDEX lupo_analytics_referers_periods_idx_referer ON lupo_analytics_referers_periods (referer_content_id, period_date);
CREATE INDEX lupo_analytics_referers_periods_idx_department ON lupo_analytics_referers_periods (department_id, period_date);
CREATE INDEX lupo_analytics_referers_periods_idx_level ON lupo_analytics_referers_periods (level, period_date);

CREATE TABLE lupo_analytics_visits (
  analytics_visit_id bigint NOT NULL,
  session_id varchar(100) NOT NULL,
  actor_id bigint NOT NULL DEFAULT '0',
  content_id bigint DEFAULT NULL,
  federations_node_id bigint NOT NULL,
  url_path varchar(500) NOT NULL DEFAULT '',
  referer_url varchar(500) DEFAULT NULL,
  referer_domain varchar(255) DEFAULT NULL,
  referer_path varchar(500) DEFAULT NULL,
  came_from varchar(500) DEFAULT NULL,
  first_seen_ymdhis bigint NOT NULL,
  last_seen_ymdhis bigint NOT NULL,
  view_count int NOT NULL DEFAULT '1',
  seconds_active int NOT NULL DEFAULT '0',
  user_agent varchar(255) DEFAULT NULL,
  ip_address varchar(45) DEFAULT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL,
  PRIMARY KEY (analytics_visit_id)
);


CREATE TABLE lupo_analytics_visits_daily (
  analytics_visits_daily_id bigint NOT NULL,
  content_id bigint NOT NULL DEFAULT '0',
  url_path varchar(500) NOT NULL DEFAULT '',
  department_id bigint NOT NULL DEFAULT '1',
  date_ymd bigint NOT NULL,
  visits int NOT NULL DEFAULT '0',
  unique_sessions int NOT NULL DEFAULT '0',
  unique_actors int NOT NULL DEFAULT '0',
  direct_visits int NOT NULL DEFAULT '0',
  internal_visits int NOT NULL DEFAULT '0',
  entry_count int NOT NULL DEFAULT '0',
  exit_count int NOT NULL DEFAULT '0',
  total_seconds int NOT NULL DEFAULT '0',
  avg_seconds int NOT NULL DEFAULT '0',
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL,
  PRIMARY KEY (analytics_visits_daily_id)
);

CREATE UNIQUE INDEX lupo_analytics_visits_daily_uq_visits_daily ON lupo_analytics_visits_daily (content_id, date_ymd);
CREATE INDEX lupo_analytics_visits_daily_idx_date_ymd ON lupo_analytics_visits_daily (date_ymd);
CREATE INDEX lupo_analytics_visits_daily_idx_content ON lupo_analytics_visits_daily (content_id, date_ymd);
CREATE INDEX lupo_analytics_visits_daily_idx_department ON lupo_analytics_visits_daily (department_id, date_ymd);
CREATE INDEX lupo_analytics_visits_daily_idx_created ON lupo_analytics_visits_daily (created_ymdhis);
CREATE INDEX lupo_analytics_visits_daily_idx_updated ON lupo_analytics_visits_daily (updated_ymdhis);

CREATE TABLE lupo_analytics_visits_monthly (
  analytics_visits_monthly_id bigint NOT NULL,
  content_id bigint NOT NULL DEFAULT '0',
  url_path varchar(500) NOT NULL DEFAULT '',
  department_id bigint NOT NULL DEFAULT '1',
  date_ym bigint NOT NULL,
  visits int NOT NULL DEFAULT '0',
  unique_sessions int NOT NULL DEFAULT '0',
  unique_actors int NOT NULL DEFAULT '0',
  direct_visits int NOT NULL DEFAULT '0',
  internal_visits int NOT NULL DEFAULT '0',
  entry_count int NOT NULL DEFAULT '0',
  exit_count int NOT NULL DEFAULT '0',
  total_seconds int NOT NULL DEFAULT '0',
  avg_seconds int NOT NULL DEFAULT '0',
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL,
  PRIMARY KEY (analytics_visits_monthly_id)
);

CREATE UNIQUE INDEX lupo_analytics_visits_monthly_uq_visits_monthly ON lupo_analytics_visits_monthly (content_id, date_ym);
CREATE INDEX lupo_analytics_visits_monthly_idx_content ON lupo_analytics_visits_monthly (content_id, date_ym);
CREATE INDEX lupo_analytics_visits_monthly_idx_department ON lupo_analytics_visits_monthly (department_id, date_ym);
CREATE INDEX lupo_analytics_visits_monthly_idx_created ON lupo_analytics_visits_monthly (created_ymdhis);
CREATE INDEX lupo_analytics_visits_monthly_idx_updated ON lupo_analytics_visits_monthly (updated_ymdhis);

CREATE TABLE lupo_analytics_visits_periods (
  analytics_visits_period_id bigint NOT NULL,
  content_id bigint NOT NULL DEFAULT '0',
  url_path varchar(500) NOT NULL DEFAULT '',
  department_id bigint NOT NULL DEFAULT '1',
  period_type varchar(64) NOT NULL,
  period_date bigint NOT NULL,
  visits int NOT NULL DEFAULT '0',
  unique_sessions int NOT NULL DEFAULT '0',
  unique_actors int NOT NULL DEFAULT '0',
  direct_visits int NOT NULL DEFAULT '0',
  internal_visits int NOT NULL DEFAULT '0',
  entry_count int NOT NULL DEFAULT '0',
  exit_count int NOT NULL DEFAULT '0',
  total_seconds int NOT NULL DEFAULT '0',
  avg_seconds int NOT NULL DEFAULT '0',
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL,
  PRIMARY KEY (analytics_visits_period_id)
);

CREATE UNIQUE INDEX lupo_analytics_visits_periods_uq_visits_period ON lupo_analytics_visits_periods (content_id, period_type, period_date);
CREATE INDEX lupo_analytics_visits_periods_idx_period_date ON lupo_analytics_visits_periods (period_date);
CREATE INDEX lupo_analytics_visits_periods_idx_content ON lupo_analytics_visits_periods (content_id, period_date);
CREATE INDEX lupo_analytics_visits_periods_idx_department ON lupo_analytics_visits_periods (department_id, period_date);

CREATE TABLE lupo_anubis_deletion_log (
  anubis_deletion_id bigint NOT NULL,
  table_name varchar(255) NOT NULL,
  record_id bigint NOT NULL,
  deleted_ymdhis bigint NOT NULL,
  deletion_type varchar(64) NOT NULL,
  replacement_table varchar(255) DEFAULT NULL,
  replacement_id bigint DEFAULT NULL,
  anubis_operator varchar(255) NOT NULL,
  context_json json DEFAULT NULL,
  notes text,
  PRIMARY KEY (anubis_deletion_id)
);

CREATE INDEX lupo_anubis_deletion_log_idx_table_record ON lupo_anubis_deletion_log (table_name, record_id);
CREATE INDEX lupo_anubis_deletion_log_idx_deleted_time ON lupo_anubis_deletion_log (deleted_ymdhis);

CREATE TABLE lupo_anubis_events (
  anubis_event_id bigint NOT NULL,
  event_type varchar(64) NOT NULL,
  table_name varchar(255) NOT NULL,
  row_id bigint NOT NULL,
  timestamp_utc bigint NOT NULL,
  agent varchar(255) NOT NULL,
  details_json text NOT NULL,
  PRIMARY KEY (anubis_event_id)
);


CREATE TABLE lupo_anubis_mirrored (
  anubis_mirrored_id bigint NOT NULL,
  table_name varchar(255) NOT NULL,
  original_id bigint NOT NULL,
  mirrored_json text NOT NULL,
  timestamp_utc bigint NOT NULL,
  agent varchar(255) NOT NULL,
  reason varchar(255) NOT NULL,
  lineage_chain varchar(255) DEFAULT NULL,
  PRIMARY KEY (anubis_mirrored_id)
);


CREATE TABLE lupo_anubis_orphaned (
  anubis_orphaned_id bigint NOT NULL,
  table_name varchar(255) NOT NULL,
  orphan_id bigint NOT NULL,
  timestamp_utc bigint NOT NULL,
  reason varchar(255) NOT NULL,
  PRIMARY KEY (anubis_orphaned_id)
);


CREATE TABLE lupo_anubis_redirects (
  anubis_redirect_id bigint NOT NULL,
  table_name varchar(255) NOT NULL,
  old_id bigint NOT NULL,
  new_id bigint NOT NULL,
  timestamp_utc bigint NOT NULL,
  agent varchar(255) NOT NULL,
  PRIMARY KEY (anubis_redirect_id)
);


CREATE TABLE lupo_anubis_revised (
  anubis_revised_id bigint NOT NULL,
  table_name varchar(255) NOT NULL,
  row_id bigint NOT NULL,
  timestamp_utc bigint NOT NULL,
  agent varchar(255) NOT NULL,
  revision_json text NOT NULL,
  PRIMARY KEY (anubis_revised_id)
);


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
  expires_ymdhis bigint DEFAULT NULL,
  last_used_ymdhis bigint DEFAULT NULL,
  created_ip varchar(45) DEFAULT NULL,
  last_used_ip varchar(45) DEFAULT NULL,
  notes text,
  PRIMARY KEY (api_token_id)
);

CREATE UNIQUE INDEX lupo_api_tokens_uq_token_key ON lupo_api_tokens (token_key);
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
  `utc_timestamp` bigint NOT NULL,
  entity_type varchar(64) NOT NULL,
  content text NOT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  is_deleted tinyint NOT NULL DEFAULT '0',
  deleted_ymdhis bigint DEFAULT NULL,
  PRIMARY KEY (artifact_id)
);

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

CREATE TABLE lupo_channel_boot_detail (
  detail_id bigint NOT NULL,
  boot_id bigint NOT NULL,
  channel_id bigint NOT NULL,
  load_start_time bigint,
  load_end_time bigint,
  load_status varchar(64) NOT NULL DEFAULT 'started',
  content_items_loaded int NOT NULL DEFAULT '0',
  total_content_items int NOT NULL DEFAULT '0',
  load_duration_ms int DEFAULT NULL,
  error_message text,
  created_ymdhis bigint NOT NULL DEFAULT '0',
  PRIMARY KEY (detail_id)
);

CREATE INDEX lupo_channel_boot_detail_idx_boot_channel ON lupo_channel_boot_detail (boot_id, channel_id);
CREATE INDEX lupo_channel_boot_detail_idx_load_status_time ON lupo_channel_boot_detail (load_status, load_start_time);
CREATE INDEX lupo_channel_boot_detail_fk_boot_detail_channel ON lupo_channel_boot_detail (channel_id);

CREATE TABLE lupo_channel_boot_log (
  boot_id bigint NOT NULL,
  actor_id bigint DEFAULT NULL,
  session_id varchar(64) DEFAULT NULL,
  boot_start_time bigint,
  boot_end_time bigint,
  boot_status varchar(64) NOT NULL DEFAULT 'started',
  channels_loaded int NOT NULL DEFAULT '0',
  total_channels int NOT NULL DEFAULT '0',
  error_details json DEFAULT NULL,
  performance_metrics json DEFAULT NULL,
  created_ymdhis bigint NOT NULL DEFAULT '0',
  PRIMARY KEY (boot_id)
);

CREATE INDEX lupo_channel_boot_log_idx_actor_session ON lupo_channel_boot_log (actor_id, session_id);
CREATE INDEX lupo_channel_boot_log_idx_boot_status_time ON lupo_channel_boot_log (boot_status, boot_start_time);

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
CREATE INDEX lupo_channel_files_idx_file_type ON lupo_channel_files (file_type);
CREATE INDEX lupo_channel_files_idx_file_hash ON lupo_channel_files (file_hash);
CREATE INDEX lupo_channel_files_idx_is_deleted ON lupo_channel_files (is_deleted);
CREATE INDEX lupo_channel_files_idx_upload_ymdhis ON lupo_channel_files (upload_ymdhis);

CREATE TABLE lupo_channel_logs (
  channel_log_id bigint NOT NULL,
  channel_id bigint NOT NULL,
  actor_id bigint NOT NULL,
  role_type varchar(64) NOT NULL,
  log_type_id bigint NOT NULL,
  log_text text NOT NULL,
  metadata_json json DEFAULT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL,
  is_deleted tinyint NOT NULL DEFAULT '0',
  pinned tinyint NOT NULL DEFAULT '0',
  deleted_ymdhis bigint DEFAULT NULL,
  PRIMARY KEY (channel_log_id)
);

CREATE INDEX lupo_channel_logs_idx_channel_id ON lupo_channel_logs (channel_id);
CREATE INDEX lupo_channel_logs_idx_actor_id ON lupo_channel_logs (actor_id);
CREATE INDEX lupo_channel_logs_idx_role_type ON lupo_channel_logs (role_type);
CREATE INDEX lupo_channel_logs_idx_log_type_id ON lupo_channel_logs (log_type_id);
CREATE INDEX lupo_channel_logs_idx_created_ymdhis ON lupo_channel_logs (created_ymdhis);

CREATE TABLE lupo_channel_log_types (
  log_type_id bigint NOT NULL,
  type_key varchar(64) NOT NULL,
  type_label varchar(255) NOT NULL,
  description text,
  is_system tinyint NOT NULL DEFAULT '0',
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL,
  is_deleted tinyint NOT NULL DEFAULT '0',
  deleted_ymdhis bigint DEFAULT NULL,
  PRIMARY KEY (log_type_id)
);

CREATE UNIQUE INDEX lupo_channel_log_types_uniq_type_key ON lupo_channel_log_types (type_key);

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
  federations_node_id bigint NOT NULL,
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
  PRIMARY KEY (collection_id)
);

CREATE UNIQUE INDEX lupo_collections_unique_collection_slug_domain ON lupo_collections (federations_node_id, slug);
CREATE INDEX lupo_collections_idx_name ON lupo_collections (name);
CREATE INDEX lupo_collections_idx_domain ON lupo_collections (federations_node_id);
CREATE INDEX lupo_collections_idx_department ON lupo_collections (department_id);
CREATE INDEX lupo_collections_idx_created_ymdhis ON lupo_collections (created_ymdhis);
CREATE INDEX lupo_collections_idx_updated_ymdhis ON lupo_collections (updated_ymdhis);
CREATE INDEX lupo_collections_idx_is_deleted ON lupo_collections (is_deleted);
CREATE INDEX lupo_collections_idx_sort_order ON lupo_collections (sort_order);
CREATE INDEX lupo_collections_idx_actor ON lupo_collections (actor_id);
ALTER TABLE lupo_collections CHANGE collection_id collection_id bigint NOT NULL AUTO_INCREMENT;

CREATE TABLE lupo_collection_tabs (
  collection_tab_id bigint NOT NULL,
  collection_tab_parent_id bigint DEFAULT NULL,
  collection_id bigint NOT NULL,
  federations_node_id bigint NOT NULL,
  department_id bigint DEFAULT NULL,
  user_id bigint DEFAULT NULL,
  sort_order int DEFAULT '0',
  name varchar(255) NOT NULL,
  slug varchar(100) NOT NULL,
  color char(6) DEFAULT '4caf50',
  description text,
  is_hidden tinyint NOT NULL DEFAULT '0',
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
CREATE INDEX lupo_collection_tabs_idx_slug ON lupo_collection_tabs (slug);
CREATE INDEX lupo_collection_tabs_idx_is_active ON lupo_collection_tabs (is_active);
ALTER TABLE lupo_collection_tabs CHANGE collection_tab_id collection_tab_id bigint NOT NULL AUTO_INCREMENT;

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
  department_id bigint DEFAULT NULL,
  actor_id bigint DEFAULT NULL,
  title varchar(255) NOT NULL,
  slug varchar(255) NOT NULL,
  custom_path varchar(255) DEFAULT NULL,
  description text,
  seo_keywords varchar(500) DEFAULT NULL,
  body text,
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
  share_count int DEFAULT '0',
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
  likes_total int DEFAULT 0 COMMENT 'Consolidated from lupo_content_engagement_summary',
  shares_total int DEFAULT 0 COMMENT 'Consolidated from lupo_content_engagement_summary',
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
CREATE INDEX lupo_contents_idx_department ON lupo_contents (department_id);
CREATE INDEX lupo_contents_idx_user ON lupo_contents (actor_id);

-- 4.0.21 Consolidation: Performance indexes for JSON columns
CREATE INDEX lupo_contents_idx_has_likes_shares ON lupo_contents (likes_total, shares_total);
CREATE INDEX lupo_contents_idx_has_media ON lupo_contents ((JSON_LENGTH(media_attachments) > 0));
CREATE INDEX lupo_contents_idx_has_events ON lupo_contents ((JSON_LENGTH(content_events) > 0));
CREATE INDEX lupo_contents_idx_has_hashtags ON lupo_contents ((JSON_LENGTH(hashtags) > 0));

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
  created_timestamp bigint NOT NULL,
  modified_timestamp bigint NOT NULL,
  message_count int DEFAULT '0',
  metadata_json json DEFAULT NULL,
  PRIMARY KEY (channel_id)
);

CREATE UNIQUE INDEX lupo_dialog_channels_idx_channel_name ON lupo_dialog_channels (channel_name);
CREATE INDEX lupo_dialog_channels_idx_file_source ON lupo_dialog_channels (file_source);
CREATE INDEX lupo_dialog_channels_idx_speaker ON lupo_dialog_channels (speaker);
CREATE INDEX lupo_dialog_channels_idx_target ON lupo_dialog_channels (target);
CREATE INDEX lupo_dialog_channels_idx_status ON lupo_dialog_channels (status);
CREATE INDEX lupo_dialog_channels_idx_created_timestamp ON lupo_dialog_channels (created_timestamp);
CREATE INDEX lupo_dialog_channels_idx_modified_timestamp ON lupo_dialog_channels (modified_timestamp);
CREATE INDEX lupo_dialog_channels_idx_dialog_channels_composite ON lupo_dialog_channels (status, created_timestamp);

CREATE TABLE lupo_dialog_messages (
  dialog_message_id bigint NOT NULL,
  dialog_thread_id bigint DEFAULT NULL,
  channel_id bigint DEFAULT NULL,
  from_actor_id bigint DEFAULT NULL,
  to_actor_id bigint DEFAULT NULL,
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

CREATE TABLE lupo_dialog_threads (
  dialog_thread_id bigint NOT NULL,
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

CREATE TABLE lupo_doctrine_refinements (
  doctrine_refinement_id bigint NOT NULL,
  cip_event_id bigint NOT NULL,
  doctrine_file_path varchar(500) NOT NULL,
  refinement_type varchar(64) NOT NULL,
  change_description text NOT NULL,
  before_content_hash varchar(64) DEFAULT NULL,
  after_content_hash varchar(64) NOT NULL,
  impact_assessment_json json DEFAULT NULL,
  approval_status varchar(64) DEFAULT 'pending',
  approved_by varchar(100) DEFAULT NULL,
  applied_ymdhis bigint DEFAULT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  refinement_version varchar(20) DEFAULT '3.0.0',
  PRIMARY KEY (doctrine_refinement_id)
);

CREATE INDEX lupo_doctrine_refinements_idx_cip_event ON lupo_doctrine_refinements (cip_event_id);
CREATE INDEX lupo_doctrine_refinements_idx_doctrine_file ON lupo_doctrine_refinements (doctrine_file_path(255));
CREATE INDEX lupo_doctrine_refinements_idx_approval_status ON lupo_doctrine_refinements (approval_status);
CREATE INDEX lupo_doctrine_refinements_idx_applied_time ON lupo_doctrine_refinements (applied_ymdhis);

CREATE TABLE lupo_documents (
  document_id bigint NOT NULL,
  domain_id int NOT NULL DEFAULT '1',
  document_name varchar(256) NOT NULL,
  source_type varchar(64) NOT NULL,
  source_url text,
  mime_type varchar(128) DEFAULT NULL,
  file_size_bytes int DEFAULT NULL,
  checksum_sha256 varchar(64) DEFAULT NULL,
  metadata json DEFAULT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL,
  is_deleted tinyint NOT NULL DEFAULT '0',
  deleted_ymdhis bigint DEFAULT NULL,
  PRIMARY KEY (document_id)
);


CREATE TABLE lupo_document_chunks (
  document_chunk_id bigint NOT NULL,
  document_id bigint NOT NULL,
  chunk_index int NOT NULL,
  chunk_content mediumtext NOT NULL,
  token_count int DEFAULT NULL,
  metadata json DEFAULT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL,
  is_deleted tinyint NOT NULL DEFAULT '0',
  deleted_ymdhis bigint DEFAULT NULL,
  PRIMARY KEY (document_chunk_id)
);

CREATE UNIQUE INDEX lupo_document_chunks_doc_chunk_unique ON lupo_document_chunks (document_id, chunk_index);
CREATE INDEX lupo_document_chunks_document_id ON lupo_document_chunks (document_id);

CREATE TABLE lupo_document_embeddings (
  document_embedding_id bigint NOT NULL,
  chunk_id bigint NOT NULL,
  embedding_json json NOT NULL,
  embedding_model varchar(128) NOT NULL,
  embedding_version varchar(64) DEFAULT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  is_deleted tinyint NOT NULL DEFAULT '0',
  deleted_ymdhis bigint DEFAULT NULL,
  PRIMARY KEY (document_embedding_id)
);

CREATE INDEX lupo_document_embeddings_chunk_id ON lupo_document_embeddings (chunk_id);
CREATE INDEX lupo_document_embeddings_embedding_model ON lupo_document_embeddings (embedding_model);

CREATE TABLE lupo_edges (
  edge_id bigint NOT NULL,
  left_object_type varchar(50) NOT NULL,
  left_object_id bigint NOT NULL,
  right_object_type varchar(50) NOT NULL,
  right_object_id bigint NOT NULL,
  edge_type varchar(100) NOT NULL,
  channel_id bigint DEFAULT NULL,
  channel_key varchar(64) DEFAULT NULL,
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
  PRIMARY KEY (edge_id)
);

CREATE INDEX lupo_edges_idx_left ON lupo_edges (left_object_type, left_object_id);
CREATE INDEX lupo_edges_idx_right ON lupo_edges (right_object_type, right_object_id);
CREATE INDEX lupo_edges_idx_edge_type ON lupo_edges (edge_type);
CREATE INDEX lupo_edges_idx_actor ON lupo_edges (actor_id);
CREATE INDEX lupo_edges_idx_is_deleted ON lupo_edges (is_deleted);
CREATE INDEX lupo_edges_idx_semantic_weight ON lupo_edges (semantic_weight);
CREATE INDEX lupo_edges_idx_relationship_type ON lupo_edges (relationship_type);
CREATE INDEX lupo_edges_idx_channel_semantic ON lupo_edges (channel_id, relationship_type, semantic_weight);

CREATE TABLE lupo_edge_types (
  edge_type_id bigint NOT NULL,
  edge_type varchar(100) NOT NULL,
  description text,
  category varchar(100) DEFAULT NULL,
  created_ymd bigint NOT NULL DEFAULT '0',
  updated_ymd bigint NOT NULL DEFAULT '0',
  PRIMARY KEY (edge_type_id)
);

CREATE INDEX lupo_edge_types_idx_edge_type ON lupo_edge_types (edge_type);

CREATE TABLE lupo_emotional_constellations (
  constellation_id char(26) NOT NULL,
  framework_name varchar(255) NOT NULL,
  cultural_origin varchar(255) DEFAULT NULL,
  description text,
  stars json NOT NULL,
  is_canonical tinyint NOT NULL DEFAULT '0',
  canonical_for_culture varchar(255) DEFAULT NULL,
  created_ymdhis bigint DEFAULT NULL,
  deprecated_ymdhis bigint DEFAULT NULL,
  PRIMARY KEY (constellation_id)
);


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

CREATE TABLE lupo_emotional_stars (
  star_id char(26) NOT NULL,
  experience_hash char(64) DEFAULT NULL,
  experience_text text NOT NULL,
  cultural_context json DEFAULT NULL,
  embodied_sensation json DEFAULT NULL,
  created_by bigint DEFAULT NULL,
  created_in_context bigint DEFAULT NULL,
  first_observed_ymdhis bigint DEFAULT NULL,
  observation_count int NOT NULL DEFAULT '1',
  PRIMARY KEY (star_id)
);


CREATE TABLE lupo_emotional_translations (
  translation_id bigint NOT NULL,
  source_framework varchar(32) NOT NULL,
  source_state text NOT NULL,
  target_framework varchar(32) NOT NULL,
  target_state text NOT NULL,
  loss_score decimal(3,2) NOT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  last_used_ymdhis bigint NOT NULL,
  PRIMARY KEY (translation_id)
);


CREATE TABLE lupo_entity_edges (
  entity_edge_id bigint NOT NULL,
  source_entity_type varchar(64) NOT NULL,
  source_entity_id bigint NOT NULL,
  target_entity_type varchar(64) NOT NULL,
  target_entity_id bigint NOT NULL,
  edge_type varchar(50) NOT NULL,
  domain_id bigint NOT NULL DEFAULT '1',
  properties json DEFAULT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint DEFAULT NULL,
  is_deleted tinyint NOT NULL DEFAULT '0',
  deleted_ymdhis bigint DEFAULT NULL,
  PRIMARY KEY (entity_edge_id)
);

CREATE INDEX lupo_entity_edges_idx_source ON lupo_entity_edges (source_entity_type, source_entity_id);
CREATE INDEX lupo_entity_edges_idx_target ON lupo_entity_edges (target_entity_type, target_entity_id);
CREATE INDEX lupo_entity_edges_idx_edge_type ON lupo_entity_edges (edge_type);
CREATE INDEX lupo_entity_edges_idx_domain ON lupo_entity_edges (domain_id);
CREATE INDEX lupo_entity_edges_idx_created ON lupo_entity_edges (created_ymdhis);
CREATE INDEX lupo_entity_edges_idx_is_deleted ON lupo_entity_edges (is_deleted);

CREATE TABLE lupo_entity_properties (
  entity_property_id bigint NOT NULL,
  entity_type varchar(64) NOT NULL,
  entity_id bigint NOT NULL,
  domain_id bigint NOT NULL DEFAULT '1',
  property_key varchar(100) NOT NULL,
  property_value text,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint DEFAULT NULL,
  is_deleted tinyint NOT NULL DEFAULT '0',
  deleted_ymdhis bigint DEFAULT NULL,
  PRIMARY KEY (entity_property_id)
);

CREATE UNIQUE INDEX lupo_entity_properties_unique_entity_domain_property ON lupo_entity_properties (entity_type, entity_id, domain_id, property_key);
CREATE INDEX lupo_entity_properties_idx_entity ON lupo_entity_properties (entity_type, entity_id);
CREATE INDEX lupo_entity_properties_idx_domain ON lupo_entity_properties (domain_id);
CREATE INDEX lupo_entity_properties_idx_property_key ON lupo_entity_properties (property_key);
CREATE INDEX lupo_entity_properties_idx_created ON lupo_entity_properties (created_ymdhis);
CREATE INDEX lupo_entity_properties_idx_updated ON lupo_entity_properties (updated_ymdhis);
CREATE INDEX lupo_entity_properties_idx_is_deleted ON lupo_entity_properties (is_deleted);

CREATE TABLE lupo_event_log (
  event_id bigint NOT NULL,
  event_type varchar(100) NOT NULL,
  event_data json DEFAULT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  PRIMARY KEY (event_id)
);

CREATE INDEX lupo_event_log_idx_event_type ON lupo_event_log (event_type);
CREATE INDEX lupo_event_log_idx_created_ymdhis ON lupo_event_log (created_ymdhis);

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

CREATE TABLE lupo_federation_discovery (
  federation_discovery_id bigint NOT NULL,
  domain varchar(255) NOT NULL,
  install_url varchar(500) DEFAULT NULL,
  is_lupopedia tinyint NOT NULL DEFAULT '0',
  last_seen_ymdhis bigint DEFAULT NULL,
  first_seen_ymdhis bigint DEFAULT NULL,
  hashtag_count bigint DEFAULT '0',
  question_count bigint DEFAULT '0',
  atom_count bigint DEFAULT '0',
  context_count bigint DEFAULT '0',
  collection_count bigint DEFAULT '0',
  keywords varchar(500) DEFAULT NULL,
  description text,
  import_hashtags tinyint NOT NULL DEFAULT '0',
  import_questions tinyint NOT NULL DEFAULT '0',
  import_atoms tinyint NOT NULL DEFAULT '0',
  import_contexts tinyint NOT NULL DEFAULT '0',
  import_collections tinyint NOT NULL DEFAULT '0',
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL,
  PRIMARY KEY (federation_discovery_id)
);

CREATE INDEX lupo_federation_discovery_idx_domain ON lupo_federation_discovery (domain);

CREATE TABLE lupo_federation_nodes (
  federation_node_id bigint NOT NULL,
  node_base_url varchar(500) NOT NULL,
  default_department_id bigint DEFAULT NULL,
  node_name varchar(255) DEFAULT NULL,
  node_description text,
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

CREATE TABLE lupo_gov_events (
  gov_event_id bigint NOT NULL,
  utc_group_id bigint NOT NULL,
  semantic_utc_version varchar(50) NOT NULL,
  canonical_path varchar(500) NOT NULL,
  event_type varchar(100) NOT NULL,
  title varchar(255) NOT NULL,
  directive_block text,
  tldr_summary text,
  metadata_json json DEFAULT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint DEFAULT NULL,
  is_active tinyint NOT NULL DEFAULT '1',
  is_deleted tinyint NOT NULL DEFAULT '0',
  deleted_ymdhis bigint DEFAULT NULL,
  PRIMARY KEY (gov_event_id)
);

CREATE UNIQUE INDEX lupo_gov_events_unique_canonical_path ON lupo_gov_events (canonical_path);
CREATE INDEX lupo_gov_events_idx_utc_group ON lupo_gov_events (utc_group_id);
CREATE INDEX lupo_gov_events_idx_semantic_version ON lupo_gov_events (semantic_utc_version);
CREATE INDEX lupo_gov_events_idx_event_type ON lupo_gov_events (event_type);
CREATE INDEX lupo_gov_events_idx_created_ymdhis ON lupo_gov_events (created_ymdhis);
CREATE INDEX lupo_gov_events_idx_is_active ON lupo_gov_events (is_active);
CREATE INDEX lupo_gov_events_idx_is_deleted ON lupo_gov_events (is_deleted);

CREATE TABLE lupo_gov_event_actor_edges (
  edge_id bigint NOT NULL,
  gov_event_id bigint NOT NULL,
  actor_id bigint NOT NULL,
  edge_type varchar(100) NOT NULL,
  edge_properties text,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint DEFAULT NULL,
  is_deleted tinyint NOT NULL DEFAULT '0',
  deleted_ymdhis bigint DEFAULT NULL,
  PRIMARY KEY (edge_id)
);

CREATE UNIQUE INDEX lupo_gov_event_actor_edges_unique_gov_event_actor_edge ON lupo_gov_event_actor_edges (gov_event_id, actor_id, edge_type);
CREATE INDEX lupo_gov_event_actor_edges_idx_gov_event ON lupo_gov_event_actor_edges (gov_event_id);
CREATE INDEX lupo_gov_event_actor_edges_idx_actor ON lupo_gov_event_actor_edges (actor_id);
CREATE INDEX lupo_gov_event_actor_edges_idx_edge_type ON lupo_gov_event_actor_edges (edge_type);
CREATE INDEX lupo_gov_event_actor_edges_idx_created_ymdhis ON lupo_gov_event_actor_edges (created_ymdhis);
CREATE INDEX lupo_gov_event_actor_edges_idx_is_deleted ON lupo_gov_event_actor_edges (is_deleted);

CREATE TABLE lupo_gov_event_conflicts (
  gov_event_conflict_id bigint NOT NULL,
  gov_event_id bigint NOT NULL,
  conflicts_with_event_id bigint NOT NULL,
  conflict_type varchar(50) NOT NULL,
  severity varchar(20) NOT NULL,
  notes text,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  is_deleted tinyint NOT NULL DEFAULT '0',
  deleted_ymdhis bigint DEFAULT NULL,
  PRIMARY KEY (gov_event_conflict_id)
);

CREATE INDEX lupo_gov_event_conflicts_idx_gov_event_id ON lupo_gov_event_conflicts (gov_event_id);
CREATE INDEX lupo_gov_event_conflicts_idx_conflicts_with_event_id ON lupo_gov_event_conflicts (conflicts_with_event_id);

CREATE TABLE lupo_gov_event_dependencies (
  gov_event_dependency_id bigint NOT NULL,
  gov_event_id bigint NOT NULL,
  depends_on_event_id bigint NOT NULL,
  dependency_type varchar(50) NOT NULL,
  notes text,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  is_deleted tinyint NOT NULL DEFAULT '0',
  deleted_ymdhis bigint DEFAULT NULL,
  PRIMARY KEY (gov_event_dependency_id)
);

CREATE INDEX lupo_gov_event_dependencies_idx_gov_event_id ON lupo_gov_event_dependencies (gov_event_id);
CREATE INDEX lupo_gov_event_dependencies_idx_depends_on_event_id ON lupo_gov_event_dependencies (depends_on_event_id);

CREATE TABLE lupo_gov_event_references (
  reference_id bigint NOT NULL,
  gov_event_id bigint NOT NULL,
  reference_type varchar(100) NOT NULL,
  reference_title varchar(255) NOT NULL,
  reference_url varchar(1000) DEFAULT NULL,
  reference_content text,
  order_sequence int NOT NULL DEFAULT '0',
  metadata_json json DEFAULT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint DEFAULT NULL,
  is_deleted tinyint NOT NULL DEFAULT '0',
  deleted_ymdhis bigint DEFAULT NULL,
  PRIMARY KEY (reference_id)
);

CREATE INDEX lupo_gov_event_references_idx_gov_event ON lupo_gov_event_references (gov_event_id);
CREATE INDEX lupo_gov_event_references_idx_reference_type ON lupo_gov_event_references (reference_type);
CREATE INDEX lupo_gov_event_references_idx_order_sequence ON lupo_gov_event_references (order_sequence);
CREATE INDEX lupo_gov_event_references_idx_created_ymdhis ON lupo_gov_event_references (created_ymdhis);
CREATE INDEX lupo_gov_event_references_idx_is_deleted ON lupo_gov_event_references (is_deleted);

CREATE TABLE lupo_gov_timeline_nodes (
  timeline_node_id bigint NOT NULL,
  gov_event_id bigint NOT NULL,
  node_type varchar(100) NOT NULL,
  node_title varchar(255) NOT NULL,
  node_description text,
  node_timestamp bigint NOT NULL,
  parent_node_id bigint DEFAULT NULL,
  order_sequence int NOT NULL DEFAULT '0',
  metadata_json json DEFAULT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint DEFAULT NULL,
  is_deleted tinyint NOT NULL DEFAULT '0',
  deleted_ymdhis bigint DEFAULT NULL,
  PRIMARY KEY (timeline_node_id)
);

CREATE INDEX lupo_gov_timeline_nodes_idx_gov_event ON lupo_gov_timeline_nodes (gov_event_id);
CREATE INDEX lupo_gov_timeline_nodes_idx_node_type ON lupo_gov_timeline_nodes (node_type);
CREATE INDEX lupo_gov_timeline_nodes_idx_node_timestamp ON lupo_gov_timeline_nodes (node_timestamp);
CREATE INDEX lupo_gov_timeline_nodes_idx_parent_node ON lupo_gov_timeline_nodes (parent_node_id);
CREATE INDEX lupo_gov_timeline_nodes_idx_order_sequence ON lupo_gov_timeline_nodes (order_sequence);
CREATE INDEX lupo_gov_timeline_nodes_idx_created_ymdhis ON lupo_gov_timeline_nodes (created_ymdhis);
CREATE INDEX lupo_gov_timeline_nodes_idx_is_deleted ON lupo_gov_timeline_nodes (is_deleted);

CREATE TABLE lupo_gov_valuations (
  valuation_id bigint NOT NULL,
  gov_event_id bigint NOT NULL,
  valuation_type varchar(100) NOT NULL,
  valuation_metric varchar(255) NOT NULL,
  valuation_value decimal(20,8) DEFAULT NULL,
  valuation_text text,
  valuation_currency varchar(10) DEFAULT NULL,
  valuation_unit varchar(50) DEFAULT NULL,
  confidence_score decimal(5,4) DEFAULT NULL,
  metadata_json json DEFAULT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint DEFAULT NULL,
  is_deleted tinyint NOT NULL DEFAULT '0',
  deleted_ymdhis bigint DEFAULT NULL,
  PRIMARY KEY (valuation_id)
);

CREATE INDEX lupo_gov_valuations_idx_gov_event ON lupo_gov_valuations (gov_event_id);
CREATE INDEX lupo_gov_valuations_idx_valuation_type ON lupo_gov_valuations (valuation_type);
CREATE INDEX lupo_gov_valuations_idx_valuation_metric ON lupo_gov_valuations (valuation_metric);
CREATE INDEX lupo_gov_valuations_idx_created_ymdhis ON lupo_gov_valuations (created_ymdhis);
CREATE INDEX lupo_gov_valuations_idx_is_deleted ON lupo_gov_valuations (is_deleted);

CREATE TABLE lupo_hashtags (
  hashtag_id bigint NOT NULL,
  hashtag_slug varchar(255) NOT NULL,
  description text,
  meta_json json DEFAULT NULL,
  is_deleted tinyint NOT NULL DEFAULT '0',
  deleted_ymdhis bigint NOT NULL DEFAULT '0',
  created_ymdhis bigint NOT NULL DEFAULT '0',
  updated_ymdhis bigint NOT NULL DEFAULT '0',
  PRIMARY KEY (hashtag_id)
);

CREATE INDEX lupo_hashtags_idx_hashtag_slug ON lupo_hashtags (hashtag_slug);
CREATE INDEX lupo_hashtags_idx_is_deleted ON lupo_hashtags (is_deleted);

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

CREATE TABLE lupo_hotfix_registry (
  hotfix_id int NOT NULL,
  hotfix_version varchar(20) NOT NULL,
  applied_ymdhis bigint NOT NULL,
  applied_by_actor_id int DEFAULT NULL,
  description text,
  metadata_json json DEFAULT NULL,
  PRIMARY KEY (hotfix_id)
);


CREATE TABLE lupo_human_history_meta (
  meta_id bigint NOT NULL,
  event_key varchar(255) NOT NULL,
  tensor_mapping varchar(32) NOT NULL,
  philosophical_reference varchar(255) NOT NULL,
  system_impact text NOT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL,
  PRIMARY KEY (meta_id)
);


CREATE TABLE lupo_interface_translations (
  interface_translation_id bigint NOT NULL,
  language_code varchar(8) NOT NULL,
  translation_key varchar(128) NOT NULL,
  translation_text text NOT NULL,
  context varchar(64) DEFAULT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint DEFAULT NULL,
  created_by bigint DEFAULT NULL,
  is_deleted tinyint NOT NULL DEFAULT '0',
  deleted_ymdhis bigint DEFAULT NULL,
  version int DEFAULT '1',
  is_approved tinyint DEFAULT '0',
  approved_by bigint DEFAULT NULL,
  PRIMARY KEY (interface_translation_id)
);

CREATE UNIQUE INDEX lupo_interface_translations_unq_language_key ON lupo_interface_translations (language_code, translation_key);
CREATE INDEX lupo_interface_translations_idx_created ON lupo_interface_translations (created_ymdhis);
CREATE INDEX lupo_interface_translations_idx_updated ON lupo_interface_translations (updated_ymdhis);
CREATE INDEX lupo_interface_translations_idx_deleted ON lupo_interface_translations (is_deleted);
CREATE INDEX lupo_interface_translations_idx_approved ON lupo_interface_translations (is_approved);

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

CREATE TABLE lupo_kapu_events (
  kapu_id bigint NOT NULL,
  agent_id varchar(255) DEFAULT NULL,
  imposed_by_actor_id varchar(255) DEFAULT NULL,
  kapu_type varchar(64) DEFAULT NULL,
  restrictions json DEFAULT NULL,
  restoration_plan json DEFAULT NULL,
  kapakai_level decimal(3,2) DEFAULT NULL,
  review_schedule json DEFAULT NULL,
  accepted_at bigint DEFAULT NULL,
  appealed_at bigint DEFAULT NULL,
  active tinyint DEFAULT '1',
  created_at bigint DEFAULT NULL,
  PRIMARY KEY (kapu_id)
);


CREATE TABLE lupo_kapu_restoration_paths (
  path_id bigint NOT NULL,
  agent_id varchar(255) DEFAULT NULL,
  kapu_reason_code varchar(100) DEFAULT NULL,
  learning_modules json DEFAULT NULL,
  emotional_targets json DEFAULT NULL,
  restoration_rituals json DEFAULT NULL,
  kapu_companion_agent_id varchar(255) DEFAULT NULL,
  completed_at bigint DEFAULT NULL,
  PRIMARY KEY (path_id)
);


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

CREATE TABLE lupo_legacy_content_mapping (
  mapping_id bigint NOT NULL,
  legacy_url varchar(255) NOT NULL,
  semantic_url varchar(255) NOT NULL,
  content_type varchar(64) NOT NULL,
  content_id bigint DEFAULT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL,
  is_active tinyint NOT NULL DEFAULT '1',
  PRIMARY KEY (mapping_id)
);

CREATE UNIQUE INDEX lupo_legacy_content_mapping_uk_legacy_url ON lupo_legacy_content_mapping (legacy_url);
CREATE INDEX lupo_legacy_content_mapping_idx_semantic_url ON lupo_legacy_content_mapping (semantic_url);
CREATE INDEX lupo_legacy_content_mapping_idx_content_type ON lupo_legacy_content_mapping (content_type);
CREATE INDEX lupo_legacy_content_mapping_idx_content_id ON lupo_legacy_content_mapping (content_id);
CREATE INDEX lupo_legacy_content_mapping_idx_is_active ON lupo_legacy_content_mapping (is_active);
CREATE INDEX lupo_legacy_content_mapping_idx_created ON lupo_legacy_content_mapping (created_ymdhis);
CREATE INDEX lupo_legacy_content_mapping_idx_created_ymdhis ON lupo_legacy_content_mapping (created_ymdhis, is_active);

CREATE TABLE lupo_memory_events (
  memory_event_id bigint NOT NULL,
  actor_id int NOT NULL,
  event_type varchar(64) NOT NULL,
  content text NOT NULL,
  metadata json DEFAULT NULL,
  token_count int DEFAULT NULL,
  importance tinyint DEFAULT '0',
  embedding_status varchar(64) DEFAULT 'none',
  created_ymdhis bigint NOT NULL DEFAULT 0,
  PRIMARY KEY (memory_event_id)
);

CREATE INDEX lupo_memory_events_idx_actor_created ON lupo_memory_events (actor_id, created_ymdhis);
CREATE INDEX lupo_memory_events_idx_actor_type ON lupo_memory_events (actor_id, event_type);

CREATE TABLE lupo_memory_rollups (
  memory_rollup_id bigint NOT NULL,
  actor_id int NOT NULL,
  summary text NOT NULL,
  source_event_ids text NOT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  PRIMARY KEY (memory_rollup_id)
);

CREATE INDEX lupo_memory_rollups_idx_actor_created ON lupo_memory_rollups (actor_id, created_ymdhis);

CREATE TABLE lupo_meta_log_events (
  event_id bigint NOT NULL,
  depth tinyint NOT NULL,
  event_type varchar(64) NOT NULL DEFAULT 'recursion',
  actor_id bigint DEFAULT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  is_deleted tinyint NOT NULL DEFAULT '0',
  deleted_ymdhis bigint DEFAULT NULL,
  PRIMARY KEY (event_id)
);

CREATE INDEX lupo_meta_log_events_idx_created_ymdhis ON lupo_meta_log_events (created_ymdhis);
CREATE INDEX lupo_meta_log_events_idx_depth ON lupo_meta_log_events (depth);
CREATE INDEX lupo_meta_log_events_idx_actor_id ON lupo_meta_log_events (actor_id);
CREATE INDEX lupo_meta_log_events_idx_is_deleted ON lupo_meta_log_events (is_deleted);

CREATE TABLE lupo_metrics_archive_legacy (
  metric_id int NOT NULL,
  metric_key varchar(255) NOT NULL,
  metric_value varchar(255) DEFAULT NULL,
  recorded_at bigint,
  PRIMARY KEY (metric_id)
);


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

CREATE TABLE lupo_modules_departments (
  module_department_id bigint NOT NULL,
  module_id bigint NOT NULL,
  department_id bigint NOT NULL,
  is_enabled tinyint NOT NULL DEFAULT '1',
  sort_order int DEFAULT '0',
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL,
  is_deleted tinyint NOT NULL DEFAULT '0',
  deleted_ymdhis bigint DEFAULT NULL,
  PRIMARY KEY (module_department_id)
);

CREATE UNIQUE INDEX lupo_modules_departments_uniq_mod_dept ON lupo_modules_departments (module_id, department_id);

CREATE TABLE lupo_mood_assignments (
  mood_assignment_id bigint NOT NULL,
  table_name varchar(128) NOT NULL,
  row_id bigint NOT NULL,
  mood_id bigint NOT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  PRIMARY KEY (mood_assignment_id)
);

CREATE INDEX lupo_mood_assignments_idx_assignment_target ON lupo_mood_assignments (table_name, row_id);
CREATE INDEX lupo_mood_assignments_idx_assignment_mood ON lupo_mood_assignments (mood_id);

CREATE TABLE lupo_mood_registry (
  mood_id bigint NOT NULL,
  mood_type varchar(64) NOT NULL,
  mood_variant varchar(64) DEFAULT NULL,
  mood_rgb char(6) NOT NULL,
  description text,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL,
  PRIMARY KEY (mood_id)
);

CREATE INDEX lupo_mood_registry_idx_mood_type ON lupo_mood_registry (mood_type);
CREATE INDEX lupo_mood_registry_idx_mood_rgb ON lupo_mood_registry (mood_rgb);

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


CREATE TABLE lupo_pack_role_registry (
  pack_role_registry_id bigint NOT NULL,
  agent_id bigint NOT NULL,
  role_key varchar(255) NOT NULL,
  discovery_method text NOT NULL,
  behavior text NOT NULL,
  reason text NOT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL,
  PRIMARY KEY (pack_role_registry_id)
);

CREATE UNIQUE INDEX lupo_pack_role_registry_unique_agent_role ON lupo_pack_role_registry (agent_id);
CREATE INDEX lupo_pack_role_registry_idx_agent_id ON lupo_pack_role_registry (agent_id);
CREATE INDEX lupo_pack_role_registry_idx_role_key ON lupo_pack_role_registry (role_key);

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

CREATE TABLE lupo_persona_dialogue_patterns (
  pattern_id bigint NOT NULL,
  persona_id bigint NOT NULL,
  pattern_type varchar(100) NOT NULL,
  pattern_name varchar(255) NOT NULL,
  pattern_triggers json DEFAULT NULL,
  pattern_responses json DEFAULT NULL,
  pattern_context json DEFAULT NULL,
  pattern_frequency decimal(5,2) DEFAULT NULL,
  pattern_confidence decimal(5,2) DEFAULT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL,
  PRIMARY KEY (pattern_id)
);

CREATE INDEX lupo_persona_dialogue_patterns_idx_persona_id ON lupo_persona_dialogue_patterns (persona_id);
CREATE INDEX lupo_persona_dialogue_patterns_idx_pattern_type ON lupo_persona_dialogue_patterns (pattern_type);
CREATE INDEX lupo_persona_dialogue_patterns_idx_pattern_name ON lupo_persona_dialogue_patterns (pattern_name);

CREATE TABLE lupo_persona_profiles (
  persona_id bigint NOT NULL,
  persona_name varchar(255) NOT NULL,
  persona_type varchar(100) NOT NULL,
  persona_description text,
  persona_traits json DEFAULT NULL,
  persona_preferences json DEFAULT NULL,
  persona_capabilities json DEFAULT NULL,
  persona_voice_style varchar(100) DEFAULT NULL,
  persona_interaction_style varchar(100) DEFAULT NULL,
  persona_emotional_profile json DEFAULT NULL,
  persona_knowledge_domains json DEFAULT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL,
  is_active tinyint NOT NULL DEFAULT '1',
  PRIMARY KEY (persona_id)
);

CREATE INDEX lupo_persona_profiles_idx_persona_type ON lupo_persona_profiles (persona_type);
CREATE INDEX lupo_persona_profiles_idx_persona_name ON lupo_persona_profiles (persona_name);
CREATE INDEX lupo_persona_profiles_idx_is_active ON lupo_persona_profiles (is_active);

CREATE TABLE lupo_reference_cited_by (
  reference_cited_by_id bigint NOT NULL,
  reference_object_id bigint NOT NULL,
  content_id bigint NOT NULL,
  section_anchor_slug varchar(255) DEFAULT NULL,
  section_order int NOT NULL DEFAULT '0',
  reference_type varchar(50) NOT NULL,
  raw_reference text,
  meta_json json DEFAULT NULL,
  is_deleted tinyint NOT NULL DEFAULT '0',
  deleted_ymdhis bigint NOT NULL DEFAULT '0',
  created_ymdhis bigint NOT NULL DEFAULT '0',
  updated_ymdhis bigint NOT NULL DEFAULT '0',
  PRIMARY KEY (reference_cited_by_id)
);

CREATE INDEX lupo_reference_cited_by_idx_reference_object ON lupo_reference_cited_by (reference_object_id);
CREATE INDEX lupo_reference_cited_by_idx_content_id ON lupo_reference_cited_by (content_id);
CREATE INDEX lupo_reference_cited_by_idx_section_anchor ON lupo_reference_cited_by (section_anchor_slug);
CREATE INDEX lupo_reference_cited_by_idx_reference_type ON lupo_reference_cited_by (reference_type);
CREATE INDEX lupo_reference_cited_by_idx_is_deleted ON lupo_reference_cited_by (is_deleted);

CREATE TABLE lupo_reference_objects (
  reference_object_id bigint NOT NULL,
  object_type varchar(50) NOT NULL,
  object_slug varchar(255) NOT NULL,
  object_label varchar(255) DEFAULT NULL,
  meta_json json DEFAULT NULL,
  is_deleted tinyint NOT NULL DEFAULT '0',
  deleted_ymdhis bigint NOT NULL DEFAULT '0',
  created_ymdhis bigint NOT NULL DEFAULT '0',
  updated_ymdhis bigint NOT NULL DEFAULT '0',
  PRIMARY KEY (reference_object_id)
);

CREATE INDEX lupo_reference_objects_idx_object_type ON lupo_reference_objects (object_type);
CREATE INDEX lupo_reference_objects_idx_object_slug ON lupo_reference_objects (object_slug);
CREATE INDEX lupo_reference_objects_idx_type_slug ON lupo_reference_objects (object_type, object_slug);
CREATE INDEX lupo_reference_objects_idx_is_deleted ON lupo_reference_objects (is_deleted);

CREATE TABLE lupo_relationships (
  relationship_id bigint NOT NULL,
  source_type varchar(50) DEFAULT NULL,
  source_id bigint DEFAULT NULL,
  edge_type varchar(50) DEFAULT NULL,
  target_type varchar(50) DEFAULT NULL,
  target_id bigint DEFAULT NULL,
  created_ymdhis bigint DEFAULT NULL,
  updated_ymdhis bigint DEFAULT NULL,
  is_deleted tinyint DEFAULT '0',
  PRIMARY KEY (relationship_id)
);

CREATE INDEX lupo_relationships_idx_relationship_lookup ON lupo_relationships (source_type, source_id, edge_type, is_deleted);

CREATE TABLE lupo_search_index (
  search_index_id bigint NOT NULL,
  domain_id bigint NOT NULL,
  entity_type varchar(50) NOT NULL,
  entity_id bigint NOT NULL,
  title_text text,
  body_text text,
  keywords_text text,
  search_metadata text,
  relevance_score float DEFAULT '1',
  is_deleted tinyint NOT NULL DEFAULT '0',
  deleted_ymdhis bigint DEFAULT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL,
  PRIMARY KEY (search_index_id)
);

CREATE UNIQUE INDEX lupo_search_index_unique_entity ON lupo_search_index (domain_id, entity_type, entity_id);
CREATE INDEX lupo_search_index_idx_domain_type ON lupo_search_index (domain_id, entity_type);
CREATE INDEX lupo_search_index_idx_entity_reference ON lupo_search_index (entity_type, entity_id);
CREATE INDEX lupo_search_index_idx_updated ON lupo_search_index (updated_ymdhis);
CREATE INDEX lupo_search_index_idx_is_deleted ON lupo_search_index (is_deleted);
CREATE INDEX lupo_search_index_idx_relevance ON lupo_search_index (relevance_score);

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

CREATE TABLE lupo_semantic_categories (
  category_id bigint NOT NULL,
  category_name varchar(255) NOT NULL,
  category_slug varchar(255) NOT NULL,
  description text,
  parent_category_id bigint DEFAULT NULL,
  sort_order int NOT NULL DEFAULT '0',
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL,
  is_active tinyint NOT NULL DEFAULT '1',
  PRIMARY KEY (category_id)
);

CREATE UNIQUE INDEX lupo_semantic_categories_uk_category_slug ON lupo_semantic_categories (category_slug);
CREATE INDEX lupo_semantic_categories_idx_parent_category ON lupo_semantic_categories (parent_category_id);
CREATE INDEX lupo_semantic_categories_idx_sort_order ON lupo_semantic_categories (sort_order);
CREATE INDEX lupo_semantic_categories_idx_is_active ON lupo_semantic_categories (is_active);
CREATE INDEX lupo_semantic_categories_idx_created ON lupo_semantic_categories (created_ymdhis);
CREATE INDEX lupo_semantic_categories_idx_created_ymdhis ON lupo_semantic_categories (created_ymdhis, is_active);

CREATE TABLE lupo_semantic_content_views (
  semantic_view_id bigint NOT NULL,
  view_name varchar(255) NOT NULL,
  view_type varchar(64) NOT NULL,
  title varchar(255) NOT NULL,
  description text,
  template_path varchar(512) NOT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL,
  is_active tinyint NOT NULL DEFAULT '1',
  is_default tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (semantic_view_id)
);

CREATE UNIQUE INDEX lupo_semantic_content_views_uk_view_name ON lupo_semantic_content_views (view_name);
CREATE INDEX lupo_semantic_content_views_idx_view_type ON lupo_semantic_content_views (view_type);
CREATE INDEX lupo_semantic_content_views_idx_is_active ON lupo_semantic_content_views (is_active);
CREATE INDEX lupo_semantic_content_views_idx_is_default ON lupo_semantic_content_views (is_default);
CREATE INDEX lupo_semantic_content_views_idx_created_ymdhis ON lupo_semantic_content_views (created_ymdhis, is_default, is_active);

CREATE TABLE lupo_semantic_navigation_overview (
  navigation_id bigint NOT NULL,
  title varchar(255) NOT NULL,
  description text,
  navigation_tree json NOT NULL,
  content_categories json NOT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL,
  is_deleted tinyint NOT NULL DEFAULT '0',
  deleted_ymdhis bigint DEFAULT NULL,
  PRIMARY KEY (navigation_id)
);

CREATE INDEX lupo_semantic_navigation_overview_idx_created ON lupo_semantic_navigation_overview (created_ymdhis);
CREATE INDEX lupo_semantic_navigation_overview_idx_is_deleted ON lupo_semantic_navigation_overview (is_deleted);
CREATE INDEX lupo_semantic_navigation_overview_idx_created_ymdhis ON lupo_semantic_navigation_overview (created_ymdhis, is_deleted);

CREATE TABLE lupo_semantic_overlays (
  semantic_overlay_id int NOT NULL,
  slug varchar(255) NOT NULL,
  overlay_key varchar(255) NOT NULL,
  overlay_value text NOT NULL,
  context varchar(255) DEFAULT NULL,
  created_at bigint,
  PRIMARY KEY (semantic_overlay_id)
);

CREATE INDEX lupo_semantic_overlays_idx_slug ON lupo_semantic_overlays (slug);
CREATE INDEX lupo_semantic_overlays_idx_context ON lupo_semantic_overlays (context);

CREATE TABLE lupo_semantic_paths (
  semantic_path_id bigint NOT NULL,
  source_page_id bigint NOT NULL,
  target_page_id bigint NOT NULL,
  layer varchar(64) NOT NULL,
  weight float NOT NULL DEFAULT '0',
  decay_factor float NOT NULL DEFAULT '1',
  trend_score float NOT NULL DEFAULT '0',
  timeframe varchar(64) NOT NULL,
  custom_start bigint,
  custom_end bigint,
  created_at bigint,
  updated_at bigint,
  PRIMARY KEY (semantic_path_id)
);

CREATE INDEX lupo_semantic_paths_source_page_id ON lupo_semantic_paths (source_page_id);
CREATE INDEX lupo_semantic_paths_target_page_id ON lupo_semantic_paths (target_page_id);
CREATE INDEX lupo_semantic_paths_layer ON lupo_semantic_paths (layer);
CREATE INDEX lupo_semantic_paths_timeframe ON lupo_semantic_paths (timeframe);

CREATE TABLE lupo_semantic_relationships (
  relationship_id bigint NOT NULL,
  source_content_id bigint NOT NULL,
  target_content_id bigint DEFAULT NULL,
  relationship_type varchar(64) NOT NULL,
  relationship_strength decimal(3,2) NOT NULL DEFAULT '1.00',
  created_ymdhis bigint NOT NULL DEFAULT 0,
  PRIMARY KEY (relationship_id)
);

CREATE INDEX lupo_semantic_relationships_idx_source_content ON lupo_semantic_relationships (source_content_id);
CREATE INDEX lupo_semantic_relationships_idx_target_content ON lupo_semantic_relationships (target_content_id);
CREATE INDEX lupo_semantic_relationships_idx_relationship_type ON lupo_semantic_relationships (relationship_type);
CREATE INDEX lupo_semantic_relationships_idx_created ON lupo_semantic_relationships (created_ymdhis);
CREATE INDEX lupo_semantic_relationships_idx_created_ymdhis ON lupo_semantic_relationships (created_ymdhis, relationship_type, source_content_id, target_content_id);

CREATE TABLE lupo_semantic_search_index (
  search_index_id bigint NOT NULL,
  index_name varchar(255) NOT NULL,
  index_type varchar(64) NOT NULL,
  description text,
  index_data json NOT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL,
  is_active tinyint NOT NULL DEFAULT '1',
  PRIMARY KEY (search_index_id)
);

CREATE UNIQUE INDEX lupo_semantic_search_index_uk_index_name ON lupo_semantic_search_index (index_name);
CREATE INDEX lupo_semantic_search_index_idx_index_type ON lupo_semantic_search_index (index_type);
CREATE INDEX lupo_semantic_search_index_idx_is_active ON lupo_semantic_search_index (is_active);
CREATE INDEX lupo_semantic_search_index_idx_created ON lupo_semantic_search_index (created_ymdhis);
CREATE INDEX lupo_semantic_search_index_idx_created_ymdhis ON lupo_semantic_search_index (created_ymdhis, is_active);

CREATE TABLE lupo_semantic_tags (
  tag_id bigint NOT NULL,
  tag_name varchar(255) NOT NULL,
  tag_slug varchar(255) NOT NULL,
  description text,
  color varchar(7) NOT NULL DEFAULT '#666666',
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL,
  is_active tinyint NOT NULL DEFAULT '1',
  PRIMARY KEY (tag_id)
);

CREATE UNIQUE INDEX lupo_semantic_tags_uk_tag_slug ON lupo_semantic_tags (tag_slug);
CREATE INDEX lupo_semantic_tags_idx_is_active ON lupo_semantic_tags (is_active);
CREATE INDEX lupo_semantic_tags_idx_created ON lupo_semantic_tags (created_ymdhis);
CREATE INDEX lupo_semantic_tags_idx_created_ymdhis ON lupo_semantic_tags (created_ymdhis, is_active);

CREATE TABLE lupo_semantic_translations (
  semantic_translation_id bigint NOT NULL,
  language_code varchar(8) NOT NULL,
  entity_type varchar(32) NOT NULL,
  entity_id bigint NOT NULL,
  translated_text text NOT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint DEFAULT NULL,
  created_by bigint DEFAULT NULL,
  is_deleted tinyint NOT NULL DEFAULT '0',
  deleted_ymdhis bigint DEFAULT NULL,
  PRIMARY KEY (semantic_translation_id)
);

CREATE UNIQUE INDEX lupo_semantic_translations_unq_translation ON lupo_semantic_translations (entity_type, entity_id, language_code);
CREATE INDEX lupo_semantic_translations_idx_entity_lookup ON lupo_semantic_translations (entity_type, entity_id, language_code);
CREATE INDEX lupo_semantic_translations_idx_language_entity ON lupo_semantic_translations (language_code, entity_type, entity_id);
CREATE INDEX lupo_semantic_translations_idx_created ON lupo_semantic_translations (created_ymdhis);
CREATE INDEX lupo_semantic_translations_idx_updated ON lupo_semantic_translations (updated_ymdhis);
CREATE INDEX lupo_semantic_translations_idx_deleted ON lupo_semantic_translations (is_deleted);

CREATE TABLE lupo_sessions (
  session_id varchar(255) NOT NULL,
  federation_node_id bigint NOT NULL DEFAULT 1,
  actor_id bigint NOT NULL DEFAULT 0,
  channel_id bigint NOT NULL DEFAULT 1,
  ip_address varchar(45) NOT NULL DEFAULT '',
  user_agent varchar(255) NOT NULL DEFAULT '',
  device_id varchar(100) DEFAULT NULL,
  device_type varchar(64) DEFAULT NULL,
  auth_method varchar(30) DEFAULT NULL,
  auth_provider varchar(50) DEFAULT NULL,
  security_level varchar(64) NOT NULL DEFAULT 'medium',
  name_key varchar(100) DEFAULT NULL,
  is_named tinyint NOT NULL DEFAULT 0,
  is_authenticated tinyint NOT NULL DEFAULT 0,
  is_active tinyint NOT NULL DEFAULT 1,
  is_expired tinyint NOT NULL DEFAULT 0,
  is_revoked tinyint NOT NULL DEFAULT 0,
  session_data text,
  system_context varchar(50) DEFAULT NULL,
  metadata json DEFAULT NULL,
  login_ymdhis bigint DEFAULT NULL,
  last_seen_ymdhis bigint NOT NULL,
  expires_ymdhis bigint DEFAULT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL,
  is_deleted tinyint NOT NULL DEFAULT 0,
  deleted_ymdhis bigint DEFAULT NULL,
  PRIMARY KEY (session_id)
);

CREATE INDEX lupo_sessions_idx_domain ON lupo_sessions (federation_node_id);
CREATE INDEX lupo_sessions_idx_actor ON lupo_sessions (actor_id);
CREATE INDEX lupo_sessions_idx_last_seen ON lupo_sessions (last_seen_ymdhis);
CREATE INDEX lupo_sessions_idx_expires ON lupo_sessions (expires_ymdhis);
CREATE INDEX lupo_sessions_idx_device ON lupo_sessions (device_id);
CREATE INDEX lupo_sessions_idx_security ON lupo_sessions (security_level);
CREATE INDEX lupo_sessions_idx_status ON lupo_sessions (is_active, is_expired, is_revoked);
CREATE INDEX lupo_sessions_idx_cleanup ON lupo_sessions (is_deleted, last_seen_ymdhis);
CREATE INDEX lupo_sessions_idx_created ON lupo_sessions (created_ymdhis);

CREATE TABLE lupo_session_events (
  session_event_id bigint NOT NULL,
  session_id varchar(255) NOT NULL,
  actor_id bigint DEFAULT NULL,
  tab_id varchar(255) DEFAULT NULL,
  world_id bigint DEFAULT NULL,
  world_key varchar(255) DEFAULT NULL,
  world_type varchar(50) DEFAULT NULL,
  event_type varchar(100) NOT NULL,
  event_data json DEFAULT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  PRIMARY KEY (session_event_id)
);

CREATE INDEX lupo_session_events_idx_session_id ON lupo_session_events (session_id);
CREATE INDEX lupo_session_events_idx_actor_id ON lupo_session_events (actor_id);
CREATE INDEX lupo_session_events_idx_tab_id ON lupo_session_events (tab_id);
CREATE INDEX lupo_session_events_idx_world_id ON lupo_session_events (world_id);
CREATE INDEX lupo_session_events_idx_event_type ON lupo_session_events (event_type);
CREATE INDEX lupo_session_events_idx_created_ymdhis ON lupo_session_events (created_ymdhis);
CREATE INDEX lupo_session_events_idx_session_event_type ON lupo_session_events (session_id, event_type);

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

CREATE TABLE lupo_system_events (
  system_event_id bigint NOT NULL,
  event_type varchar(100) NOT NULL,
  event_message text NOT NULL,
  event_context text,
  actor_id bigint NOT NULL DEFAULT '0',
  created_ymdhis bigint NOT NULL DEFAULT 0,
  PRIMARY KEY (system_event_id)
);

CREATE INDEX lupo_system_events_event_type ON lupo_system_events (event_type);
CREATE INDEX lupo_system_events_actor_id ON lupo_system_events (actor_id);

CREATE TABLE lupo_system_health_snapshots (
  health_id bigint NOT NULL,
  table_count int NOT NULL,
  table_ceiling int NOT NULL,
  schema_state varchar(64) NOT NULL DEFAULT 'unknown',
  sync_integrity varchar(32) NOT NULL DEFAULT 'unknown',
  emotional_r decimal(3,2) DEFAULT NULL,
  emotional_g decimal(3,2) DEFAULT NULL,
  emotional_b decimal(3,2) DEFAULT NULL,
  emotional_t decimal(3,2) DEFAULT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  is_deleted tinyint NOT NULL DEFAULT '0',
  deleted_ymdhis bigint DEFAULT NULL,
  PRIMARY KEY (health_id)
);

CREATE INDEX lupo_system_health_snapshots_idx_created_ymdhis ON lupo_system_health_snapshots (created_ymdhis);
CREATE INDEX lupo_system_health_snapshots_idx_table_count ON lupo_system_health_snapshots (table_count);
CREATE INDEX lupo_system_health_snapshots_idx_is_deleted ON lupo_system_health_snapshots (is_deleted);

CREATE TABLE lupo_system_logs (
  log_id bigint NOT NULL,
  event_type varchar(64) NOT NULL,
  severity varchar(16) NOT NULL DEFAULT 'info',
  actor_slug varchar(64) DEFAULT NULL,
  message text NOT NULL,
  context_json json DEFAULT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  is_deleted tinyint NOT NULL DEFAULT '0',
  deleted_ymdhis bigint DEFAULT NULL,
  recursion_depth tinyint DEFAULT '1',
  observation_latency_ms int DEFAULT NULL,
  temporal_anomaly_score decimal(3,2) DEFAULT NULL,
  PRIMARY KEY (log_id)
);

CREATE INDEX lupo_system_logs_idx_event_type ON lupo_system_logs (event_type);
CREATE INDEX lupo_system_logs_idx_severity ON lupo_system_logs (severity);
CREATE INDEX lupo_system_logs_idx_actor_slug ON lupo_system_logs (actor_slug);
CREATE INDEX lupo_system_logs_idx_created_ymdhis ON lupo_system_logs (created_ymdhis);
CREATE INDEX lupo_system_logs_idx_is_deleted ON lupo_system_logs (is_deleted);

CREATE TABLE lupo_tab_events (
  tab_event_id bigint NOT NULL,
  tab_id varchar(255) NOT NULL,
  session_id varchar(255) DEFAULT NULL,
  actor_id bigint DEFAULT NULL,
  world_id bigint DEFAULT NULL,
  world_key varchar(255) DEFAULT NULL,
  world_type varchar(50) DEFAULT NULL,
  event_type varchar(100) NOT NULL,
  event_data json DEFAULT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  PRIMARY KEY (tab_event_id)
);

CREATE INDEX lupo_tab_events_idx_tab_id ON lupo_tab_events (tab_id);
CREATE INDEX lupo_tab_events_idx_session_id ON lupo_tab_events (session_id);
CREATE INDEX lupo_tab_events_idx_actor_id ON lupo_tab_events (actor_id);
CREATE INDEX lupo_tab_events_idx_world_id ON lupo_tab_events (world_id);
CREATE INDEX lupo_tab_events_idx_event_type ON lupo_tab_events (event_type);
CREATE INDEX lupo_tab_events_idx_created_ymdhis ON lupo_tab_events (created_ymdhis);
CREATE INDEX lupo_tab_events_idx_tab_event_type ON lupo_tab_events (tab_id, event_type);

CREATE TABLE lupo_temporal_coherence_snapshots (
  snapshot_id bigint NOT NULL,
  utc_anchor bigint NOT NULL,
  observation_latency_ms int NOT NULL DEFAULT '0',
  recursion_depth tinyint NOT NULL DEFAULT '0',
  self_awareness_score decimal(3,2) DEFAULT NULL,
  timestamp_integrity varchar(32) NOT NULL DEFAULT 'unknown',
  created_ymdhis bigint NOT NULL DEFAULT 0,
  is_deleted tinyint NOT NULL DEFAULT '0',
  deleted_ymdhis bigint DEFAULT NULL,
  PRIMARY KEY (snapshot_id)
);

CREATE INDEX lupo_temporal_coherence_snapshots_idx_created_ymdhis ON lupo_temporal_coherence_snapshots (created_ymdhis);
CREATE INDEX lupo_temporal_coherence_snapshots_idx_utc_anchor ON lupo_temporal_coherence_snapshots (utc_anchor);
CREATE INDEX lupo_temporal_coherence_snapshots_idx_is_deleted ON lupo_temporal_coherence_snapshots (is_deleted);

CREATE TABLE lupo_tldnr (
  tldnr_id bigint NOT NULL,
  slug varchar(255) NOT NULL,
  title varchar(255) NOT NULL,
  content_text text NOT NULL,
  topic_type varchar(100) DEFAULT NULL,
  topic_reference varchar(255) DEFAULT NULL,
  system_version varchar(20) DEFAULT NULL,
  category varchar(100) DEFAULT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL,
  is_deleted tinyint NOT NULL DEFAULT '0',
  deleted_ymdhis bigint DEFAULT NULL,
  PRIMARY KEY (tldnr_id)
);

CREATE UNIQUE INDEX lupo_tldnr_uniq_slug ON lupo_tldnr (slug);
CREATE INDEX lupo_tldnr_idx_topic_type ON lupo_tldnr (topic_type);
CREATE INDEX lupo_tldnr_idx_topic_reference ON lupo_tldnr (topic_reference);
CREATE INDEX lupo_tldnr_idx_category ON lupo_tldnr (category);
CREATE INDEX lupo_tldnr_idx_system_version ON lupo_tldnr (system_version);
CREATE INDEX lupo_tldnr_idx_is_deleted ON lupo_tldnr (is_deleted);
CREATE INDEX lupo_tldnr_idx_created ON lupo_tldnr (created_ymdhis);

CREATE TABLE lupo_truth_answers (
  truth_answer_id bigint NOT NULL,
  truth_question_id bigint NOT NULL,
  actor_id bigint NOT NULL DEFAULT '0',
  answer_text text NOT NULL,
  confidence_score decimal(5,2) NOT NULL DEFAULT '0.00',
  evidence_score decimal(5,2) NOT NULL DEFAULT '0.00',
  contradiction_flag tinyint NOT NULL DEFAULT '0',
  likes_count bigint NOT NULL DEFAULT '0',
  shares_count bigint NOT NULL DEFAULT '0',
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL,
  is_deleted tinyint NOT NULL DEFAULT '0',
  deleted_ymdhis bigint DEFAULT NULL,
  PRIMARY KEY (truth_answer_id)
);

CREATE INDEX lupo_truth_answers_idx_question ON lupo_truth_answers (truth_question_id);
ALTER TABLE lupo_truth_answers CHANGE truth_answer_id truth_answer_id bigint NOT NULL AUTO_INCREMENT;

CREATE TABLE lupo_truth_evidence (
  truth_evidence_id bigint NOT NULL,
  truth_answer_id bigint NOT NULL,
  actor_id bigint NOT NULL,
  evidence_text text NOT NULL,
  evidence_type varchar(50) NOT NULL DEFAULT '',
  weight_score decimal(5,2) NOT NULL DEFAULT '0.00',
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL,
  is_deleted tinyint NOT NULL DEFAULT '0',
  deleted_ymdhis bigint DEFAULT NULL,
  PRIMARY KEY (truth_evidence_id)
);

CREATE INDEX lupo_truth_evidence_truth_answer_id ON lupo_truth_evidence (truth_answer_id);
CREATE INDEX lupo_truth_evidence_actor_id ON lupo_truth_evidence (actor_id);

CREATE TABLE lupo_truth_questions (
  truth_question_id bigint NOT NULL,
  truth_question_parent_id bigint DEFAULT NULL,
  actor_id bigint NOT NULL DEFAULT '0',
  qtype varchar(50) NOT NULL DEFAULT 'unknown',
  status varchar(64) NOT NULL DEFAULT 'active',
  sort_num int NOT NULL DEFAULT '0',
  slug varchar(255) NOT NULL,
  question_text text NOT NULL,
  format varchar(64) NOT NULL DEFAULT 'text',
  format_override varchar(50) DEFAULT NULL,
  view_count bigint NOT NULL DEFAULT '0',
  likes_count bigint NOT NULL DEFAULT '0',
  shares_count bigint NOT NULL DEFAULT '0',
  answer_count bigint NOT NULL DEFAULT '0',
  last_activity_ymdhis bigint DEFAULT NULL,
  is_featured tinyint NOT NULL DEFAULT '0',
  is_verified tinyint NOT NULL DEFAULT '0',
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL,
  is_deleted tinyint NOT NULL DEFAULT '0',
  deleted_ymdhis bigint DEFAULT NULL,
  default_collection_id bigint NOT NULL DEFAULT '0',
  PRIMARY KEY (truth_question_id)
);

CREATE INDEX lupo_truth_questions_idx_parent ON lupo_truth_questions (truth_question_parent_id);
CREATE INDEX lupo_truth_questions_idx_slug ON lupo_truth_questions (slug);

CREATE TABLE lupo_truth_questions_map (
  truth_questions_map_id bigint NOT NULL,
  truth_question_id bigint NOT NULL,
  object_type varchar(50) NOT NULL,
  object_id bigint NOT NULL,
  actor_id bigint NOT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL,
  is_deleted tinyint NOT NULL DEFAULT '0',
  deleted_ymdhis bigint DEFAULT NULL,
  PRIMARY KEY (truth_questions_map_id)
);

CREATE INDEX lupo_truth_questions_map_truth_question_id ON lupo_truth_questions_map (truth_question_id);
CREATE INDEX lupo_truth_questions_map_object_type ON lupo_truth_questions_map (object_type);
CREATE INDEX lupo_truth_questions_map_object_id ON lupo_truth_questions_map (object_id);
CREATE INDEX lupo_truth_questions_map_actor_id ON lupo_truth_questions_map (actor_id);

CREATE TABLE lupo_truth_relations (
  truth_relation_id bigint NOT NULL,
  left_object_type varchar(50) NOT NULL,
  left_object_id bigint NOT NULL,
  right_object_type varchar(50) NOT NULL,
  right_object_id bigint NOT NULL,
  relation_type varchar(50) NOT NULL DEFAULT '',
  actor_id bigint NOT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL,
  is_deleted tinyint NOT NULL DEFAULT '0',
  deleted_ymdhis bigint DEFAULT NULL,
  PRIMARY KEY (truth_relation_id)
);

CREATE INDEX lupo_truth_relations_left_object_type ON lupo_truth_relations (left_object_type);
CREATE INDEX lupo_truth_relations_right_object_type ON lupo_truth_relations (right_object_type);
CREATE INDEX lupo_truth_relations_relation_type ON lupo_truth_relations (relation_type);

CREATE TABLE lupo_truth_sources (
  truth_sourc_id bigint NOT NULL,
  truth_evidence_id bigint NOT NULL,
  actor_id bigint NOT NULL,
  source_url text,
  source_title varchar(255) NOT NULL DEFAULT '',
  source_type varchar(50) NOT NULL DEFAULT '',
  reliability_score decimal(5,2) NOT NULL DEFAULT '0.00',
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL,
  is_deleted tinyint NOT NULL DEFAULT '0',
  deleted_ymdhis bigint DEFAULT NULL,
  PRIMARY KEY (truth_sourc_id)
);

CREATE INDEX lupo_truth_sources_truth_evidence_id ON lupo_truth_sources (truth_evidence_id);
CREATE INDEX lupo_truth_sources_actor_id ON lupo_truth_sources (actor_id);

CREATE TABLE lupo_truth_topics (
  truth_topic_id bigint NOT NULL,
  topic_name varchar(255) NOT NULL DEFAULT '',
  slug varchar(255) NOT NULL DEFAULT '',
  topic_description text,
  actor_id bigint NOT NULL DEFAULT '0',
  weight_score decimal(5,2) NOT NULL DEFAULT '0.00',
  importance_score decimal(5,2) NOT NULL DEFAULT '0.00',
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL,
  is_deleted tinyint NOT NULL DEFAULT '0',
  deleted_ymdhis bigint DEFAULT NULL,
  PRIMARY KEY (truth_topic_id)
);

CREATE INDEX lupo_truth_topics_slug ON lupo_truth_topics (slug);
CREATE INDEX lupo_truth_topics_actor_id ON lupo_truth_topics (actor_id);
CREATE INDEX lupo_truth_topics_topic_name ON lupo_truth_topics (topic_name);

CREATE TABLE lupo_unified_analytics_paths (
  unified_analytics_path_id bigint NOT NULL,
  from_page_id bigint DEFAULT NULL,
  to_page_id bigint DEFAULT NULL,
  year_month_yyyymm char(6) NOT NULL,
  transition_type varchar(64) NOT NULL,
  transition_count int NOT NULL DEFAULT '0',
  metadata_json json DEFAULT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL,
  is_deleted tinyint NOT NULL DEFAULT '0',
  deleted_ymdhis bigint DEFAULT NULL,
  PRIMARY KEY (unified_analytics_path_id)
);
ALTER TABLE lupo_unified_analytics_paths CHANGE unified_analytics_path_id unified_analytics_path_id bigint NOT NULL AUTO_INCREMENT;

CREATE TABLE lupo_unified_referers (
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

CREATE INDEX lupo_unified_referers_idx_content_id ON lupo_unified_referers (content_id);
CREATE INDEX lupo_unified_referers_idx_actor_id ON lupo_unified_referers (actor_id);
CREATE INDEX lupo_unified_referers_idx_referer_domain ON lupo_unified_referers (referer_domain);
CREATE INDEX lupo_unified_referers_idx_referer_content_id ON lupo_unified_referers (referer_content_id);
CREATE INDEX lupo_unified_referers_idx_date ON lupo_unified_referers (date_ymd);
ALTER TABLE lupo_unified_referers CHANGE referer_id referer_id bigint NOT NULL AUTO_INCREMENT;

CREATE TABLE lupo_registry (
  registry_id bigint NOT NULL AUTO_INCREMENT,
  entity_type varchar(50) NOT NULL,
  entity_index_id bigint NOT NULL,
  federation_node_id bigint NOT NULL DEFAULT 0,
  reserved_ymdhis bigint NOT NULL DEFAULT 0,
  metadata text,
  PRIMARY KEY (registry_id)
);

CREATE UNIQUE INDEX idx_unified_registry_unique ON lupo_registry (entity_type, entity_index_id, federation_node_id);
CREATE INDEX idx_unified_registry_entity_type ON lupo_registry (entity_type);
CREATE INDEX idx_unified_registry_federation_node ON lupo_registry (federation_node_id);
-- Unified registry for all entities across federation nodes.

CREATE TABLE lupo_registry_open (
  unregistry_id bigint NOT NULL AUTO_INCREMENT,
  entity_type varchar(50) NOT NULL,
  entity_index_id bigint NOT NULL,
  reason varchar(255) DEFAULT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  PRIMARY KEY (unregistry_id)
);

CREATE UNIQUE INDEX idx_unun_registry_unique ON lupo_registry_open (entity_type, entity_index_id);
CREATE INDEX idx_unun_registry_entity_type ON lupo_registry_open (entity_type);
-- Unified unregistry for tracking unused/reserved IDs.

CREATE TABLE lupo_registry_import (
  import_registry_id bigint NOT NULL AUTO_INCREMENT,
  entity_type varchar(50) NOT NULL,
  entity_index_id bigint NOT NULL,
  source_federation_node_id bigint NOT NULL,
  imported_at bigint NOT NULL,
  resolved_to_local_id bigint NOT NULL DEFAULT 0,
  notes text,
  PRIMARY KEY (import_registry_id)
);

CREATE INDEX idx_import_entity_type ON lupo_registry_import (entity_type);
CREATE INDEX idx_import_entity_index_id ON lupo_registry_import (entity_index_id);
CREATE INDEX idx_import_source_node ON lupo_registry_import (source_federation_node_id);
CREATE INDEX idx_import_resolved_local_id ON lupo_registry_import (resolved_to_local_id);
-- Unified import registry for collision resolution during federation imports.


CREATE TABLE lupo_unified_visits (
  unified_visits_id bigint NOT NULL,
  content_id bigint NOT NULL DEFAULT '0',
  actor_id bigint NOT NULL DEFAULT '0',
  page_url varchar(500) NOT NULL,
  page_domain varchar(255) NOT NULL,
  page_path varchar(500) NOT NULL,
  date_ymd int NOT NULL,
  visits int NOT NULL DEFAULT '0',
  depth int NOT NULL DEFAULT '0',
  metadata_json json DEFAULT NULL,
  created_ymdhis bigint NOT NULL DEFAULT '0',
  updated_ymdhis bigint NOT NULL DEFAULT '0',
  PRIMARY KEY (unified_visits_id)
);

CREATE INDEX lupo_unified_visits_page_domain ON lupo_unified_visits (page_domain);
CREATE INDEX lupo_unified_visits_date_ymd ON lupo_unified_visits (date_ymd);
CREATE INDEX lupo_unified_visits_content_id ON lupo_unified_visits (content_id);
ALTER TABLE lupo_unified_visits CHANGE unified_visits_id unified_visits_id bigint NOT NULL AUTO_INCREMENT;

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

CREATE TABLE lupo_user_comments (
  user_comment_id bigint NOT NULL,
  domain_id bigint NOT NULL,
  user_id bigint NOT NULL,
  content_id bigint NOT NULL,
  parent_comment_id bigint DEFAULT NULL,
  comment_text text NOT NULL,
  user_agent varchar(255) DEFAULT NULL,
  ip_hash char(64) DEFAULT NULL,
  is_deleted tinyint NOT NULL DEFAULT '0',
  deleted_ymdhis bigint DEFAULT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint DEFAULT NULL,
  PRIMARY KEY (user_comment_id)
);

CREATE INDEX lupo_user_comments_idx_domain_id ON lupo_user_comments (domain_id);
CREATE INDEX lupo_user_comments_idx_user_id ON lupo_user_comments (user_id);
CREATE INDEX lupo_user_comments_idx_content_id ON lupo_user_comments (content_id);
CREATE INDEX lupo_user_comments_idx_parent_comment_id ON lupo_user_comments (parent_comment_id);
CREATE INDEX lupo_user_comments_idx_created_ymdhis ON lupo_user_comments (created_ymdhis);
CREATE INDEX lupo_user_comments_idx_updated_ymdhis ON lupo_user_comments (updated_ymdhis);
CREATE INDEX lupo_user_comments_idx_is_deleted ON lupo_user_comments (is_deleted);
CREATE INDEX lupo_user_comments_idx_ip_hash ON lupo_user_comments (ip_hash);

CREATE TABLE lupo_world_events (
  world_event_id bigint NOT NULL,
  world_id bigint NOT NULL,
  actor_id bigint NOT NULL,
  event_type varchar(100) NOT NULL,
  event_data json DEFAULT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  PRIMARY KEY (world_event_id)
);

CREATE INDEX lupo_world_events_idx_world_id ON lupo_world_events (world_id);
CREATE INDEX lupo_world_events_idx_actor_id ON lupo_world_events (actor_id);
CREATE INDEX lupo_world_events_idx_event_type ON lupo_world_events (event_type);
CREATE INDEX lupo_world_events_idx_created_ymdhis ON lupo_world_events (created_ymdhis);

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

CREATE TABLE `lupo_actor_aliases` (
    `alias_id` BIGINT NOT NULL AUTO_INCREMENT,
    `actor_id` BIGINT NOT NULL,
    `alias_name` VARCHAR(255) NOT NULL,
    `created_ymdhis` BIGINT NOT NULL,
    `updated_ymdhis` BIGINT NOT NULL,
    PRIMARY KEY (`alias_id`)
);

-- Required seed atoms (minimal bootstrap). Expand via database/install/ seed scripts.
-- INSERT lupo_atoms for GLOBAL_CURRENT_LUPOPEDIA_VERSION and kernel actors/channels as needed.
--
-- Human operator (captain@lupopedia.com): actor_id 1000, full access. Full seed in seed_lupopedia.sql.
SET @now = 20260220000000;
INSERT INTO lupo_actors (actor_id, actor_type, slug, name, created_ymdhis, updated_ymdhis, is_active, is_deleted, deleted_ymdhis, actor_source_id, actor_source_type, metadata, adversarial_role, adversarial_oversight_actor_id, avatar_hash) VALUES (1000, 'human', 'captain', 'CAPTAIN', @now, @now, 1, 0, NULL, NULL, 'human', '{"email":"captain@lupopedia.com","status":"A"}', 'none', NULL, NULL) ON DUPLICATE KEY UPDATE name = VALUES(name), updated_ymdhis = @now, is_active = 1, is_deleted = 0;
INSERT INTO lupo_registry (unified_registry_id, entity_type, entity_index, entity_key, entity_name, entity_table, federation_node_id, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis, is_active, is_kernel, metadata_json) VALUES (9001000, 'actor', 1000, 'captain', 'CAPTAIN', 'lupo_actors', 1, @now, @now, 0, NULL, 1, 0, '{"email":"captain@lupopedia.com","actor_source_type":"human"}') ON DUPLICATE KEY UPDATE entity_name = VALUES(entity_name), updated_ymdhis = @now, is_deleted = 0, is_active = 1;

-- ============================================================
-- FINAL IDE & AI ACTOR INTEGRATION (Lupopedia 4.0.23)
-- ============================================================
-- CSV-driven unregistry allocation - FINAL ACTOR IDs:
-- Cursor IDE: 2031, Kiro IDE: 2032, Zed IDE: 2033, VS Code IDE: 2034
-- Antigravity IDE: 2035, Microsoft Copilot: 2036, DeepSeek LEXA: 2037, DeepSeek LILITH: 2038
-- ============================================================

-- Registry entries for all IDE & AI actors
INSERT IGNORE INTO lupo_registry (unified_registry_id, entity_type, entity_index, entity_key, entity_name, entity_table, federation_node_id, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis, is_active, is_kernel, metadata_json) 
VALUES 
(9002031, 'actor', 2031, 'cursor-ide', 'Cursor IDE', 'lupo_actors', 1, @now, @now, 0, NULL, 1, 0, '{"actor_source_type":"system_tool","client_id":"cursor","provider":"cursor","purpose":"IDE_integration","csv_allocation":true}'),
(9002032, 'actor', 2032, 'kiro-ide', 'Kiro IDE', 'lupo_actors', 1, @now, @now, 0, NULL, 1, 0, '{"actor_source_type":"system_tool","client_id":"kiro","provider":"kiro","purpose":"IDE_integration","csv_allocation":true}'),
(9002033, 'actor', 2033, 'zed-ide', 'Zed IDE', 'lupo_actors', 1, @now, @now, 0, NULL, 1, 0, '{"actor_source_type":"system_tool","client_id":"zed","provider":"zed","purpose":"IDE_integration","csv_allocation":true}'),
(9002034, 'actor', 2034, 'vscode-ide', 'VS Code IDE', 'lupo_actors', 1, @now, @now, 0, NULL, 1, 0, '{"actor_source_type":"system_tool","client_id":"vscode","provider":"microsoft","purpose":"IDE_integration","csv_allocation":true}'),
(9002035, 'actor', 2035, 'antigravity-ide', 'Antigravity IDE', 'lupo_actors', 1, @now, @now, 0, NULL, 1, 0, '{"actor_source_type":"system_tool","client_id":"antigravity","purpose":"VSX_extension_development","csv_allocation":true}'),
(9002036, 'actor', 2036, 'microsoft-copilot', 'Microsoft Copilot', 'lupo_actors', 1, @now, @now, 0, NULL, 1, 0, '{"actor_source_type":"external_ai","client_id":"copilot","provider":"microsoft","purpose":"AI_assistant","csv_allocation":true}'),
(9002037, 'actor', 2037, 'deepseek-lexa', 'DeepSeek LEXA', 'lupo_actors', 1, @now, @now, 0, NULL, 1, 0, '{"actor_source_type":"external_ai","client_id":"deepseek_lexa","provider":"deepseek","purpose":"AI_assistant","csv_allocation":true}'),
(9002038, 'actor', 2038, 'deepseek-lilith', 'DeepSeek LILITH', 'lupo_actors', 1, @now, @now, 0, NULL, 1, 0, '{"actor_source_type":"external_ai","client_id":"deepseek_lilith","provider":"deepseek","purpose":"AI_assistant","csv_allocation":true}')
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
INSERT INTO lupo_actor_channels (actor_channel_id, actor_id, channel_id, created_by_actor_id, default_actor_id, department_id, channel_key, channel_slug, channel_type, language, channel_name, description, website_link, metadata_json, status_flag, end_ymdhis, duration_seconds, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis, is_kernel, boot_sequence_order) 
VALUES 
(12001, 1, 42, 1, 1, 0, 'lupopedia-development', 'lupopedia-development', 'chat_room', 'en', 'Lupopedia Development', 'Main development channel for Lupopedia system development, architecture, and coordination.', NULL, '{"purpose":"development","system":true}', 1, NULL, NULL, 20260221000000, 20260221000000, 0, NULL, 0, 1),
(12002, 2, 42, 1, 2, 0, 'lupopedia-development', 'lupopedia-development', 'chat_room', 'en', 'Lupopedia Development', 'Main development channel for Lupopedia system development, architecture, and coordination.', NULL, '{"purpose":"development","system":true}', 1, NULL, NULL, 20260221000000, 20260221000000, 0, NULL, 0, 2),
(12003, 3, 42, 1, 3, 0, 'lupopedia-development', 'lupopedia-development', 'chat_room', 'en', 'Lupopedia Development', 'Main development channel for Lupopedia system development, architecture, and coordination.', NULL, '{"purpose":"development","system":true}', 1, NULL, NULL, 20260221000000, 20260221000000, 0, NULL, 0, 3),
(12004, 4, 42, 1, 4, 0, 'lupopedia-development', 'lupopedia-development', 'chat_room', 'en', 'Lupopedia Development', 'Main development channel for Lupopedia system development, architecture, and coordination.', NULL, '{"purpose":"development","system":true}', 1, NULL, NULL, 20260221000000, 20260221000000, 0, NULL, 0, 4),
(12005, 5, 42, 1, 5, 0, 'lupopedia-development', 'lupopedia-development', 'chat_room', 'en', 'Lupopedia Development', 'Main development channel for Lupopedia system development, architecture, and coordination.', NULL, '{"purpose":"development","system":true}', 1, NULL, NULL, 20260221000000, 20260221000000, 0, NULL, 0, 5),
(12006, 6, 42, 1, 6, 0, 'lupopedia-development', 'lupopedia-development', 'chat_room', 'en', 'Lupopedia Development', 'Main development channel for Lupopedia system development, architecture, and coordination.', NULL, '{"purpose":"development","system":true}', 1, NULL, NULL, 20260221000000, 20260221000000, 0, NULL, 0, 6),
(12007, 7, 42, 1, 7, 0, 'lupopedia-development', 'lupopedia-development', 'chat_room', 'en', 'Lupopedia Development', 'Main development channel for Lupopedia system development, architecture, and coordination.', NULL, '{"purpose":"development","system":true}', 1, NULL, NULL, 20260221000000, 20260221000000, 0, NULL, 0, 7),
(12008, 8, 42, 1, 8, 0, 'lupopedia-development', 'lupopedia-development', 'chat_room', 'en', 'Lupopedia Development', 'Main development channel for Lupopedia system development, architecture, and coordination.', NULL, '{"purpose":"development","system":true}', 1, NULL, NULL, 20260221000000, 20260221000000, 0, NULL, 0, 8),
(12009, 9, 42, 1, 9, 0, 'lupopedia-development', 'lupopedia-development', 'chat_room', 'en', 'Lupopedia Development', 'Main development channel for Lupopedia system development, architecture, and coordination.', NULL, '{"purpose":"development","system":true}', 1, NULL, NULL, 20260221000000, 20260221000000, 0, NULL, 0, 9),
(12010, 10, 42, 1, 10, 0, 'lupopedia-development', 'lupopedia-development', 'chat_room', 'en', 'Lupopedia Development', 'Main development channel for Lupopedia system development, architecture, and coordination.', NULL, '{"purpose":"development","system":true}', 1, NULL, NULL, 20260221000000, 20260221000000, 0, NULL, 0, 10),
(12011, 11, 42, 1, 11, 0, 'lupopedia-development', 'lupopedia-development', 'chat_room', 'en', 'Lupopedia Development', 'Main development channel for Lupopedia system development, architecture, and coordination.', NULL, '{"purpose":"development","system":true}', 1, NULL, NULL, 20260221000000, 20260221000000, 0, NULL, 0, 11),
(12012, 12, 42, 1, 12, 0, 'lupopedia-development', 'lupopedia-development', 'chat_room', 'en', 'Lupopedia Development', 'Main development channel for Lupopedia system development, architecture, and coordination.', NULL, '{"purpose":"development","system":true}', 1, NULL, NULL, 20260221000000, 20260221000000, 0, NULL, 0, 12),
(12013, 13, 42, 1, 13, 0, 'lupopedia-development', 'lupopedia-development', 'chat_room', 'en', 'Lupopedia Development', 'Main development channel for Lupopedia system development, architecture, and coordination.', NULL, '{"purpose":"development","system":true}', 1, NULL, NULL, 20260221000000, 20260221000000, 0, NULL, 0, 13),
(12014, 14, 42, 1, 14, 0, 'lupopedia-development', 'lupopedia-development', 'chat_room', 'en', 'Lupopedia Development', 'Main development channel for Lupopedia system development, architecture, and coordination.', NULL, '{"purpose":"development","system":true}', 1, NULL, NULL, 20260221000000, 20260221000000, 0, NULL, 0, 14),
(12015, 15, 42, 1, 15, 0, 'lupopedia-development', 'lupopedia-development', 'chat_room', 'en', 'Lupopedia Development', 'Main development channel for Lupopedia system development, architecture, and coordination.', NULL, '{"purpose":"development","system":true}', 1, NULL, NULL, 20260221000000, 20260221000000, 0, NULL, 0, 15),
(12016, 16, 42, 1, 16, 0, 'lupopedia-development', 'lupopedia-development', 'chat_room', 'en', 'Lupopedia Development', 'Main development channel for Lupopedia system development, architecture, and coordination.', NULL, '{"purpose":"development","system":true}', 1, NULL, NULL, 20260221000000, 20260221000000, 0, NULL, 0, 16),
(12017, 17, 42, 1, 17, 0, 'lupopedia-development', 'lupopedia-development', 'chat_room', 'en', 'Lupopedia Development', 'Main development channel for Lupopedia system development, architecture, and coordination.', NULL, '{"purpose":"development","system":true}', 1, NULL, NULL, 20260221000000, 20260221000000, 0, NULL, 0, 17),
(12018, 18, 42, 1, 18, 0, 'lupopedia-development', 'lupopedia-development', 'chat_room', 'en', 'Lupopedia Development', 'Main development channel for Lupopedia system development, architecture, and coordination.', NULL, '{"purpose":"development","system":true}', 1, NULL, NULL, 20260221000000, 20260221000000, 0, NULL, 0, 18),
(12019, 19, 42, 1, 19, 0, 'lupopedia-development', 'lupopedia-development', 'chat_room', 'en', 'Lupopedia Development', 'Main development channel for Lupopedia system development, architecture, and coordination.', NULL, '{"purpose":"development","system":true}', 1, NULL, NULL, 20260221000000, 20260221000000, 0, NULL, 0, 19),
(12020, 20, 42, 1, 20, 0, 'lupopedia-development', 'lupopedia-development', 'chat_room', 'en', 'Lupopedia Development', 'Main development channel for Lupopedia system development, architecture, and coordination.', NULL, '{"purpose":"development","system":true}', 1, NULL, NULL, 20260221000000, 20260221000000, 0, NULL, 0, 20),
(12021, 21, 42, 1, 21, 0, 'lupopedia-development', 'lupopedia-development', 'chat_room', 'en', 'Lupopedia Development', 'Main development channel for Lupopedia system development, architecture, and coordination.', NULL, '{"purpose":"development","system":true}', 1, NULL, NULL, 20260221000000, 20260221000000, 0, NULL, 0, 21),
(12022, 22, 42, 1, 22, 0, 'lupopedia-development', 'lupopedia-development', 'chat_room', 'en', 'Lupopedia Development', 'Main development channel for Lupopedia system development, architecture, and coordination.', NULL, '{"purpose":"development","system":true}', 1, NULL, NULL, 20260221000000, 20260221000000, 0, NULL, 0, 22),
(12023, 23, 42, 1, 23, 0, 'lupopedia-development', 'lupopedia-development', 'chat_room', 'en', 'Lupopedia Development', 'Main development channel for Lupopedia system development, architecture, and coordination.', NULL, '{"purpose":"development","system":true}', 1, NULL, NULL, 20260221000000, 20260221000000, 0, NULL, 0, 23),
(12024, 24, 42, 1, 24, 0, 'lupopedia-development', 'lupopedia-development', 'chat_room', 'en', 'Lupopedia Development', 'Main development channel for Lupopedia system development, architecture, and coordination.', NULL, '{"purpose":"development","system":true}', 1, NULL, NULL, 20260221000000, 20260221000000, 0, NULL, 0, 24),
(12025, 25, 42, 1, 25, 0, 'lupopedia-development', 'lupopedia-development', 'chat_room', 'en', 'Lupopedia Development', 'Main development channel for Lupopedia system development, architecture, and coordination.', NULL, '{"purpose":"development","system":true}', 1, NULL, NULL, 20260221000000, 20260221000000, 0, NULL, 0, 25)
ON DUPLICATE KEY UPDATE 
    actor_id = VALUES(actor_id),
    channel_id = VALUES(channel_id),
    role_key = VALUES(role_key),
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
INSERT IGNORE INTO lupo_registry (unified_registry_id, entity_type, entity_index, entity_key, entity_name, entity_table, federation_node_id, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis, is_active, is_kernel, metadata_json)
VALUES (9002039, 'actor', 2039, 'warp-ide', 'Warp IDE', 'lupo_actors', 0, @now, @now, 0, NULL, 1, 0, '{"actor_source_type":"system_tool","client_id":"warp","provider":"warp","purpose":"IDE_integration","paired_actor_id":10000}')
ON DUPLICATE KEY UPDATE entity_name = VALUES(entity_name), metadata_json = VALUES(metadata_json), updated_ymdhis = @now, is_deleted = 0, is_active = 1;

-- Actor record for Warp IDE (with paired_actor_id)
INSERT IGNORE INTO lupo_actors (actor_id, actor_type, slug, name, created_ymdhis, updated_ymdhis, is_active, is_deleted, deleted_ymdhis, actor_source_id, actor_source_type, metadata, adversarial_role, adversarial_oversight_actor_id, avatar_hash, paired_actor_id)
VALUES (2039, 'system_tool', 'warp-ide', 'Warp IDE', @now, @now, 1, 0, NULL, 2039, 'system_tool', '{"purpose":"IDE_integration","capabilities":["code_generation","file_editing","project_management","git_integration","terminal_integration"],"version":"1.0.0","client_id":"warp","provider":"warp","integration_ready":true,"paired_actor_id":10000}', 'none', NULL, NULL, 10000)
ON DUPLICATE KEY UPDATE name = VALUES(name), metadata = VALUES(metadata), paired_actor_id = VALUES(paired_actor_id), updated_ymdhis = @now, is_active = 1, is_deleted = 0;

-- Channel 42 membership for Warp IDE
INSERT INTO lupo_actor_channels (actor_channel_id, actor_id, channel_id, created_by_actor_id, default_actor_id, department_id, channel_key, channel_slug, channel_type, language, channel_name, description, website_link, metadata_json, status_flag, end_ymdhis, duration_seconds, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis, is_kernel, boot_sequence_order)
VALUES (12039, 2039, 42, 1000, 2039, 0, 'warp-dev', 'warp-dev', 'chat_room', 'en', 'Warp IDE Development', 'Development channel for Warp IDE', NULL, '{"purpose":"IDE_integration","capabilities":["code_generation","file_editing","project_management","terminal_integration"],"paired_actor_id":10000}', 1, NULL, NULL, @now, @now, 0, NULL, 0, 208)
ON DUPLICATE KEY UPDATE channel_name = VALUES(channel_name), description = VALUES(description), metadata_json = VALUES(metadata_json), updated_ymdhis = @now, is_deleted = 0;

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
INSERT IGNORE INTO lupo_registry (unified_registry_id, entity_type, entity_index, entity_key, entity_name, entity_table, federation_node_id, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis, is_active, is_kernel, metadata_json)
VALUES (9002040, 'actor', 2040, 'windsurf-ide', 'Windsurf IDE', 'lupo_actors', 0, @now, @now, 0, NULL, 1, 0, '{"actor_source_type":"system_tool","client_id":"windsurf","provider":"windsurf","purpose":"IDE_integration","paired_actor_id":10000}')
ON DUPLICATE KEY UPDATE entity_name = VALUES(entity_name), metadata_json = VALUES(metadata_json), updated_ymdhis = @now, is_deleted = 0, is_active = 1;

-- Actor record for Windsurf IDE (with paired_actor_id)
INSERT IGNORE INTO lupo_actors (actor_id, actor_type, slug, name, created_ymdhis, updated_ymdhis, is_active, is_deleted, deleted_ymdhis, actor_source_id, actor_source_type, metadata, adversarial_role, adversarial_oversight_actor_id, avatar_hash, paired_actor_id)
VALUES (2040, 'system_tool', 'windsurf-ide', 'Windsurf IDE', @now, @now, 1, 0, NULL, 2040, 'system_tool', '{"purpose":"IDE_integration","capabilities":["code_generation","file_editing","project_management","git_integration","vsx_extension_development"],"version":"1.0.0","client_id":"windsurf","provider":"windsurf","integration_ready":true,"paired_actor_id":10000,"note":"Reassigned from actor_id 2 to avoid CAPTAIN conflict"}', 'none', NULL, NULL, 10000)
ON DUPLICATE KEY UPDATE name = VALUES(name), metadata = VALUES(metadata), paired_actor_id = VALUES(paired_actor_id), updated_ymdhis = @now, is_active = 1, is_deleted = 0;

-- Channel 42 membership for Windsurf IDE
INSERT INTO lupo_actor_channels (actor_channel_id, actor_id, channel_id, created_by_actor_id, default_actor_id, department_id, channel_key, channel_slug, channel_type, language, channel_name, description, website_link, metadata_json, status_flag, end_ymdhis, duration_seconds, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis, is_kernel, boot_sequence_order)
VALUES (12040, 2040, 42, 1000, 2040, 0, 'windsurf-dev', 'windsurf-dev', 'chat_room', 'en', 'Windsurf IDE Development', 'Development channel for Windsurf IDE', NULL, '{"purpose":"IDE_integration","capabilities":["code_generation","file_editing","project_management","vsx_extension_development"],"paired_actor_id":10000}', 1, NULL, NULL, @now, @now, 0, NULL, 0, 209)
ON DUPLICATE KEY UPDATE channel_name = VALUES(channel_name), description = VALUES(description), metadata_json = VALUES(metadata_json), updated_ymdhis = @now, is_deleted = 0;

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
UPDATE lupo_actors SET paired_actor_id = 10000, updated_ymdhis = @now WHERE actor_id = 2036 AND (paired_actor_id IS NULL OR paired_actor_id = 0);
-- paired_actor_id: LILITH → 10000 (human operator)
UPDATE lupo_actors SET paired_actor_id = 10000, updated_ymdhis = @now WHERE actor_id = 2038 AND (paired_actor_id IS NULL OR paired_actor_id = 0);
