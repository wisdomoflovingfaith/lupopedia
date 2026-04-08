-- ============================================================================
-- LUPOPEDIA CORRECTED CORE SCHEMA
-- Reviewer: claude-code (actor_id 102)
-- Date: 20260406
-- Constitutional basis: LUPOPEDIA_CONSTITUTIONAL_ROOT_RULES.md
-- All timestamps: BIGINT YYYYMMDDHHIISS UTC (ymdhis suffix)
-- No FKs, no triggers, no stored procedures, no AUTO_INCREMENT on registries
-- PKs: application-supplied explicit bigint NOT NULL
-- Soft delete: is_deleted tinyint NOT NULL DEFAULT 0 + deleted_ymdhis bigint DEFAULT NULL
-- ============================================================================

-- ============================================================================
-- SECTION 1: ACTORS (runtime instances)
-- Fixes: C1 (PK actor_id), C2 (actor_id NOT NULL), NV1 (decompose), NV2 (remove metadata text),
--        NV3 (remove department_id), NV4 (remove template default), NV5 (remove paired_actor_id),
--        NV6 (remove adversarial columns), N4 (timestamp naming)
-- ============================================================================

CREATE TABLE {{prefix}}actors (
  actor_id         bigint        NOT NULL,
  actor_name       varchar(64)   NOT NULL,
  slug             varchar(255)  NOT NULL,
  name             varchar(255)  NOT NULL,
  actor_type       varchar(64)   NOT NULL,
  agent_key        varchar(100)  DEFAULT NULL,
  is_kernel        tinyint       NOT NULL DEFAULT 0,
  is_required      tinyint       NOT NULL DEFAULT 0,
  can_login        tinyint       NOT NULL DEFAULT 0,
  is_agent         tinyint       NOT NULL DEFAULT 0,
  is_active        tinyint       NOT NULL DEFAULT 1,
  actor_tier       tinyint       DEFAULT 3,
  auth_user_id     bigint        DEFAULT NULL,
  actor_source_id  bigint        DEFAULT NULL,
  actor_source_type varchar(64)  DEFAULT NULL,
  avatar_hash      varchar(64)   DEFAULT NULL,
  primary_federation_node_id bigint NOT NULL DEFAULT 1,
  web_restrict_act_as_creator_or_root tinyint NOT NULL DEFAULT 0,
  identity_provider_config json  DEFAULT NULL,
  metadata_json    json          DEFAULT NULL,
  created_ymdhis   bigint        NOT NULL DEFAULT 0,
  updated_ymdhis   bigint        NOT NULL DEFAULT 0,
  is_deleted       tinyint       NOT NULL DEFAULT 0,
  deleted_ymdhis   bigint        DEFAULT NULL,
  PRIMARY KEY (actor_id)
);

-- actor_id is the PK; actor_name and slug are alternate unique lookup keys
CREATE UNIQUE INDEX {{prefix}}actors_unq_actor_name ON {{prefix}}actors (actor_name);
CREATE UNIQUE INDEX {{prefix}}actors_unq_slug ON {{prefix}}actors (slug);
CREATE INDEX {{prefix}}actors_idx_actor_type ON {{prefix}}actors (actor_type);
CREATE INDEX {{prefix}}actors_idx_agent_key ON {{prefix}}actors (agent_key);
CREATE INDEX {{prefix}}actors_idx_is_active ON {{prefix}}actors (is_active);
CREATE INDEX {{prefix}}actors_idx_is_deleted ON {{prefix}}actors (is_deleted);
CREATE INDEX {{prefix}}actors_idx_is_kernel ON {{prefix}}actors (is_kernel);
CREATE INDEX {{prefix}}actors_idx_created_ymdhis ON {{prefix}}actors (created_ymdhis);

-- ============================================================================
-- SECTION 2: ACTOR SATELLITE TABLES (decomposed from lupo_actors per NV1-NV6)
-- ============================================================================

-- Actor filesystem paths (extracted from lupo_actors per NV1)
-- actor_root_path is computed at runtime by ActorService — no template default
CREATE TABLE {{prefix}}actor_filesystem (
  actor_filesystem_id bigint    NOT NULL,
  actor_id            bigint    NOT NULL,
  actor_root_path     varchar(512) DEFAULT NULL,
  workspace_path      varchar(255) DEFAULT NULL,
  php_namespace       varchar(120) DEFAULT NULL,
  created_ymdhis      bigint    NOT NULL DEFAULT 0,
  updated_ymdhis      bigint    NOT NULL DEFAULT 0,
  PRIMARY KEY (actor_filesystem_id)
);

CREATE UNIQUE INDEX {{prefix}}actor_filesystem_unq_actor ON {{prefix}}actor_filesystem (actor_id);
CREATE INDEX {{prefix}}actor_filesystem_idx_workspace ON {{prefix}}actor_filesystem (workspace_path);
CREATE INDEX {{prefix}}actor_filesystem_idx_php_namespace ON {{prefix}}actor_filesystem (php_namespace);

-- Actor WHO.json sync state (extracted from lupo_actors per NV1)
CREATE TABLE {{prefix}}actor_sync_state (
  actor_sync_state_id bigint     NOT NULL,
  actor_id            bigint     NOT NULL,
  sync_target         varchar(64) NOT NULL DEFAULT 'who_json',
  sync_status         varchar(64) NOT NULL DEFAULT 'pending',
  last_sync_ymdhis    bigint     NOT NULL DEFAULT 0,
  sync_error_message  text       DEFAULT NULL,
  created_ymdhis      bigint     NOT NULL DEFAULT 0,
  updated_ymdhis      bigint     NOT NULL DEFAULT 0,
  PRIMARY KEY (actor_sync_state_id)
);

CREATE UNIQUE INDEX {{prefix}}actor_sync_state_unq_actor_target ON {{prefix}}actor_sync_state (actor_id, sync_target);
CREATE INDEX {{prefix}}actor_sync_state_idx_status ON {{prefix}}actor_sync_state (sync_status);
CREATE INDEX {{prefix}}actor_sync_state_idx_last_sync ON {{prefix}}actor_sync_state (last_sync_ymdhis);

-- Actor pairing relationships (extracted from lupo_actors per NV5)
-- Replaces paired_actor_id column in main actors table
CREATE TABLE {{prefix}}actor_pairing (
  actor_pairing_id    bigint     NOT NULL,
  actor_id            bigint     NOT NULL,
  paired_actor_id     bigint     NOT NULL,
  pairing_role        varchar(64) NOT NULL DEFAULT 'peer',
  pairing_type        varchar(64) NOT NULL DEFAULT 'operational',
  is_primary          tinyint    NOT NULL DEFAULT 1,
  notes               text       DEFAULT NULL,
  created_ymdhis      bigint     NOT NULL DEFAULT 0,
  updated_ymdhis      bigint     NOT NULL DEFAULT 0,
  is_deleted          tinyint    NOT NULL DEFAULT 0,
  deleted_ymdhis      bigint     DEFAULT NULL,
  PRIMARY KEY (actor_pairing_id)
);

CREATE UNIQUE INDEX {{prefix}}actor_pairing_unq_pair ON {{prefix}}actor_pairing (actor_id, paired_actor_id, pairing_role);
CREATE INDEX {{prefix}}actor_pairing_idx_actor ON {{prefix}}actor_pairing (actor_id);
CREATE INDEX {{prefix}}actor_pairing_idx_paired ON {{prefix}}actor_pairing (paired_actor_id);
CREATE INDEX {{prefix}}actor_pairing_idx_type ON {{prefix}}actor_pairing (pairing_type);
CREATE INDEX {{prefix}}actor_pairing_idx_deleted ON {{prefix}}actor_pairing (is_deleted);

-- Actor inter-agent relationships: adversarial, coordination, oversight (extracted per NV6)
-- Replaces adversarial_role + adversarial_oversight_actor_id columns in lupo_actors
CREATE TABLE {{prefix}}actor_relationships (
  actor_relationship_id bigint   NOT NULL,
  actor_a_id            bigint   NOT NULL,
  actor_b_id            bigint   NOT NULL,
  relationship_type     varchar(64) NOT NULL,
  -- relationship_type values: adversarial_oversight, coordination, peer, mentor, delegate
  authority_direction   varchar(32) NOT NULL DEFAULT 'a_over_b',
  -- authority_direction: a_over_b | b_over_a | bidirectional | none
  is_active             tinyint  NOT NULL DEFAULT 1,
  notes                 text     DEFAULT NULL,
  created_ymdhis        bigint   NOT NULL DEFAULT 0,
  updated_ymdhis        bigint   NOT NULL DEFAULT 0,
  is_deleted            tinyint  NOT NULL DEFAULT 0,
  deleted_ymdhis        bigint   DEFAULT NULL,
  PRIMARY KEY (actor_relationship_id)
);

CREATE UNIQUE INDEX {{prefix}}actor_relationships_unq ON {{prefix}}actor_relationships (actor_a_id, actor_b_id, relationship_type);
CREATE INDEX {{prefix}}actor_relationships_idx_a ON {{prefix}}actor_relationships (actor_a_id);
CREATE INDEX {{prefix}}actor_relationships_idx_b ON {{prefix}}actor_relationships (actor_b_id);
CREATE INDEX {{prefix}}actor_relationships_idx_type ON {{prefix}}actor_relationships (relationship_type);
CREATE INDEX {{prefix}}actor_relationships_idx_deleted ON {{prefix}}actor_relationships (is_deleted);

-- ============================================================================
-- SECTION 3: AGENT DEFINITIONS (doctrine identity — split from lupo_agents per C3, AS3)
-- Replaces the LLM-config-contaminated lupo_agents table with proper doctrine identity
-- ============================================================================

CREATE TABLE {{prefix}}agent_definitions (
  agent_id              bigint        NOT NULL,
  agent_key             varchar(100)  NOT NULL,
  slug                  varchar(255)  NOT NULL,
  name                  varchar(255)  NOT NULL,
  layer                 varchar(64)   NOT NULL DEFAULT 'application',
  -- layer values: kernel | coordination | application | emotional | reserved
  role                  varchar(500)  DEFAULT NULL,
  agent_class           varchar(100)  DEFAULT NULL,
  archetype             varchar(150)  DEFAULT NULL,
  description           text          DEFAULT NULL,
  is_kernel             tinyint       NOT NULL DEFAULT 0,
  is_required           tinyint       NOT NULL DEFAULT 0,
  department_id         bigint        DEFAULT NULL,
  learning_boundary     varchar(255)  DEFAULT NULL,
  -- learning_boundary: 'Department 0 auth_users only (core system actor)' or 'General ...'
  lineage_json          json          DEFAULT NULL,
  capabilities_json     json          DEFAULT NULL,
  system_prompt_path    varchar(512)  DEFAULT NULL,
  -- system_prompt_path: filesystem path to lupo-agents/{slug}/system_prompt.txt
  -- NOT inline text blob (prevents sync drift between file and database)
  version               varchar(50)   NOT NULL DEFAULT '1.0.0',
  status                varchar(32)   NOT NULL DEFAULT 'active',
  -- status values: active | reserved | deprecated | inactive
  metadata_json         json          DEFAULT NULL,
  created_ymdhis        bigint        NOT NULL DEFAULT 0,
  updated_ymdhis        bigint        NOT NULL DEFAULT 0,
  is_deleted            tinyint       NOT NULL DEFAULT 0,
  deleted_ymdhis        bigint        DEFAULT NULL,
  PRIMARY KEY (agent_id)
);

CREATE UNIQUE INDEX {{prefix}}agent_definitions_unq_key ON {{prefix}}agent_definitions (agent_key);
CREATE UNIQUE INDEX {{prefix}}agent_definitions_unq_slug ON {{prefix}}agent_definitions (slug);
CREATE INDEX {{prefix}}agent_definitions_idx_layer ON {{prefix}}agent_definitions (layer);
CREATE INDEX {{prefix}}agent_definitions_idx_is_kernel ON {{prefix}}agent_definitions (is_kernel);
CREATE INDEX {{prefix}}agent_definitions_idx_is_required ON {{prefix}}agent_definitions (is_required);
CREATE INDEX {{prefix}}agent_definitions_idx_department ON {{prefix}}agent_definitions (department_id);
CREATE INDEX {{prefix}}agent_definitions_idx_status ON {{prefix}}agent_definitions (status);
CREATE INDEX {{prefix}}agent_definitions_idx_deleted ON {{prefix}}agent_definitions (is_deleted);

-- ============================================================================
-- SECTION 4: AGENT LLM CONFIGS (runtime provider config — split from lupo_agents per C3)
-- Only agents that invoke LLMs need this. CHRONOS, LILITH, etc. may have no LLM config.
-- ============================================================================

CREATE TABLE {{prefix}}agent_llm_configs (
  agent_llm_config_id   bigint        NOT NULL,
  agent_id              bigint        NOT NULL,
  config_name           varchar(100)  NOT NULL DEFAULT 'default',
  provider              varchar(50)   NOT NULL DEFAULT 'anthropic',
  model_name            varchar(100)  DEFAULT NULL,
  api_key_id            bigint        DEFAULT NULL,
  temperature           float         DEFAULT 0.7,
  top_p                 float         DEFAULT 1.0,
  max_tokens            int           DEFAULT 2048,
  presence_penalty      float         DEFAULT 0.0,
  frequency_penalty     float         DEFAULT 0.0,
  timeout_ms            int           DEFAULT 20000,
  cost_per_1k_tokens    decimal(10,4) DEFAULT 0.0000,
  safety_json           json          DEFAULT NULL,
  response_format       varchar(50)   DEFAULT NULL,
  is_active             tinyint       NOT NULL DEFAULT 1,
  created_ymdhis        bigint        NOT NULL DEFAULT 0,
  updated_ymdhis        bigint        NOT NULL DEFAULT 0,
  is_deleted            tinyint       NOT NULL DEFAULT 0,
  deleted_ymdhis        bigint        DEFAULT NULL,
  PRIMARY KEY (agent_llm_config_id)
);

CREATE UNIQUE INDEX {{prefix}}agent_llm_configs_unq_agent_config ON {{prefix}}agent_llm_configs (agent_id, config_name);
CREATE INDEX {{prefix}}agent_llm_configs_idx_agent ON {{prefix}}agent_llm_configs (agent_id);
CREATE INDEX {{prefix}}agent_llm_configs_idx_provider ON {{prefix}}agent_llm_configs (provider);
CREATE INDEX {{prefix}}agent_llm_configs_idx_api_key ON {{prefix}}agent_llm_configs (api_key_id);
CREATE INDEX {{prefix}}agent_llm_configs_idx_active ON {{prefix}}agent_llm_configs (is_active);
CREATE INDEX {{prefix}}agent_llm_configs_idx_deleted ON {{prefix}}agent_llm_configs (is_deleted);

-- ============================================================================
-- SECTION 5: AGENT PERFORMANCE STATS (runtime metrics — split from lupo_agents per C3)
-- Separated because these are rolling runtime metrics, not identity fields
-- ============================================================================

CREATE TABLE {{prefix}}agent_performance_stats (
  agent_perf_id         bigint        NOT NULL,
  agent_id              bigint        NOT NULL,
  stat_window           varchar(32)   NOT NULL DEFAULT 'all_time',
  -- stat_window: all_time | rolling_24h | rolling_7d | rolling_30d
  avg_response_time_ms  int           DEFAULT 0,
  total_tokens_processed bigint       DEFAULT 0,
  success_rate          float         DEFAULT 1.0,
  total_calls           bigint        DEFAULT 0,
  last_called_ymdhis    bigint        DEFAULT 0,
  updated_ymdhis        bigint        NOT NULL DEFAULT 0,
  PRIMARY KEY (agent_perf_id)
);

CREATE UNIQUE INDEX {{prefix}}agent_perf_unq_agent_window ON {{prefix}}agent_performance_stats (agent_id, stat_window);
CREATE INDEX {{prefix}}agent_perf_idx_agent ON {{prefix}}agent_performance_stats (agent_id);
CREATE INDEX {{prefix}}agent_perf_idx_window ON {{prefix}}agent_performance_stats (stat_window);

-- ============================================================================
-- SECTION 6: AGENT CAPABILITIES (template-level — per agent_definition, not per actor)
-- ============================================================================

CREATE TABLE {{prefix}}agent_capabilities (
  agent_capability_id   bigint        NOT NULL,
  agent_id              bigint        NOT NULL,
  capability_key        varchar(100)  NOT NULL,
  capability_category   varchar(64)   DEFAULT NULL,
  capability_description text         DEFAULT NULL,
  is_out_of_scope       tinyint       NOT NULL DEFAULT 0,
  -- is_out_of_scope = 1 means this capability is explicitly NOT owned by this agent
  out_of_scope_owner    varchar(100)  DEFAULT NULL,
  -- out_of_scope_owner: the agent_key that owns this domain (e.g. 'kairos', 'hermes')
  created_ymdhis        bigint        NOT NULL DEFAULT 0,
  updated_ymdhis        bigint        NOT NULL DEFAULT 0,
  is_deleted            tinyint       NOT NULL DEFAULT 0,
  deleted_ymdhis        bigint        DEFAULT NULL,
  PRIMARY KEY (agent_capability_id)
);

CREATE UNIQUE INDEX {{prefix}}agent_capabilities_unq ON {{prefix}}agent_capabilities (agent_id, capability_key);
CREATE INDEX {{prefix}}agent_capabilities_idx_agent ON {{prefix}}agent_capabilities (agent_id);
CREATE INDEX {{prefix}}agent_capabilities_idx_key ON {{prefix}}agent_capabilities (capability_key);
CREATE INDEX {{prefix}}agent_capabilities_idx_category ON {{prefix}}agent_capabilities (capability_category);
CREATE INDEX {{prefix}}agent_capabilities_idx_scope ON {{prefix}}agent_capabilities (is_out_of_scope);
CREATE INDEX {{prefix}}agent_capabilities_idx_deleted ON {{prefix}}agent_capabilities (is_deleted);

-- ============================================================================
-- SECTION 7: AGENT TOOLS (template-level — per agent_definition)
-- ============================================================================

CREATE TABLE {{prefix}}agent_tools (
  agent_tool_id         bigint        NOT NULL,
  agent_id              bigint        NOT NULL,
  tool_id_key           varchar(200)  NOT NULL,
  -- tool_id_key: namespaced, e.g. 'chronos.analyze_dependency_graph'
  tool_name             varchar(100)  NOT NULL,
  tool_category         varchar(64)   DEFAULT NULL,
  tool_description      text          DEFAULT NULL,
  input_schema_json     json          DEFAULT NULL,
  output_schema_json    json          DEFAULT NULL,
  constraints_json      json          DEFAULT NULL,
  -- constraints_json: no_system_calls, no_db_writes, advisory_only, etc.
  is_advisory_only      tinyint       NOT NULL DEFAULT 0,
  created_ymdhis        bigint        NOT NULL DEFAULT 0,
  updated_ymdhis        bigint        NOT NULL DEFAULT 0,
  is_deleted            tinyint       NOT NULL DEFAULT 0,
  deleted_ymdhis        bigint        DEFAULT NULL,
  PRIMARY KEY (agent_tool_id)
);

CREATE UNIQUE INDEX {{prefix}}agent_tools_unq ON {{prefix}}agent_tools (agent_id, tool_id_key);
CREATE INDEX {{prefix}}agent_tools_idx_agent ON {{prefix}}agent_tools (agent_id);
CREATE INDEX {{prefix}}agent_tools_idx_key ON {{prefix}}agent_tools (tool_id_key);
CREATE INDEX {{prefix}}agent_tools_idx_category ON {{prefix}}agent_tools (tool_category);
CREATE INDEX {{prefix}}agent_tools_idx_advisory ON {{prefix}}agent_tools (is_advisory_only);
CREATE INDEX {{prefix}}agent_tools_idx_deleted ON {{prefix}}agent_tools (is_deleted);

-- ============================================================================
-- SECTION 8: AGENT BOUNDARIES (domain boundary definitions per agent)
-- ============================================================================

CREATE TABLE {{prefix}}agent_boundaries (
  agent_boundary_id     bigint        NOT NULL,
  agent_id              bigint        NOT NULL,
  boundary_type         varchar(64)   NOT NULL,
  -- boundary_type: owns | cannot_claim | yields_to | encroachment_forbidden
  domain_key            varchar(100)  NOT NULL,
  owner_agent_key       varchar(100)  DEFAULT NULL,
  boundary_description  text          DEFAULT NULL,
  created_ymdhis        bigint        NOT NULL DEFAULT 0,
  updated_ymdhis        bigint        NOT NULL DEFAULT 0,
  PRIMARY KEY (agent_boundary_id)
);

CREATE UNIQUE INDEX {{prefix}}agent_boundaries_unq ON {{prefix}}agent_boundaries (agent_id, boundary_type, domain_key);
CREATE INDEX {{prefix}}agent_boundaries_idx_agent ON {{prefix}}agent_boundaries (agent_id);
CREATE INDEX {{prefix}}agent_boundaries_idx_type ON {{prefix}}agent_boundaries (boundary_type);
CREATE INDEX {{prefix}}agent_boundaries_idx_domain ON {{prefix}}agent_boundaries (domain_key);
CREATE INDEX {{prefix}}agent_boundaries_idx_owner ON {{prefix}}agent_boundaries (owner_agent_key);

-- ============================================================================
-- SECTION 9: ACTOR DEPARTMENTS — fix I2 (missing UNIQUE constraint)
-- Corrected from existing schema: add UNIQUE on (actor_id, department_id)
-- ============================================================================

CREATE TABLE {{prefix}}actor_departments (
  actor_department_id   bigint        NOT NULL,
  actor_id              bigint        NOT NULL,
  department_id         bigint        NOT NULL,
  role_key              varchar(64)   DEFAULT NULL,
  title                 varchar(64)   DEFAULT NULL,
  is_primary            tinyint       NOT NULL DEFAULT 0,
  created_ymdhis        bigint        NOT NULL DEFAULT 0,
  updated_ymdhis        bigint        NOT NULL DEFAULT 0,
  is_deleted            tinyint       NOT NULL DEFAULT 0,
  deleted_ymdhis        bigint        DEFAULT NULL,
  PRIMARY KEY (actor_department_id)
);

CREATE UNIQUE INDEX {{prefix}}actor_departments_unq ON {{prefix}}actor_departments (actor_id, department_id);
CREATE INDEX {{prefix}}actor_departments_idx_actor ON {{prefix}}actor_departments (actor_id);
CREATE INDEX {{prefix}}actor_departments_idx_department ON {{prefix}}actor_departments (department_id);
CREATE INDEX {{prefix}}actor_departments_idx_primary ON {{prefix}}actor_departments (actor_id, is_primary);
CREATE INDEX {{prefix}}actor_departments_idx_deleted ON {{prefix}}actor_departments (is_deleted);

-- ============================================================================
-- SECTION 10: ACTOR MOODS — fix I4 (no PK, no is_deleted, bad timestamp name)
-- ============================================================================

CREATE TABLE {{prefix}}actor_moods (
  actor_mood_id         bigint        NOT NULL,
  actor_id              bigint        NOT NULL,
  mood_r                tinyint       NOT NULL,
  mood_g                tinyint       NOT NULL,
  mood_b                tinyint       NOT NULL,
  mood_framework        varchar(32)   NOT NULL DEFAULT 'western_analytical',
  recorded_ymdhis       bigint        NOT NULL,
  created_ymdhis        bigint        NOT NULL DEFAULT 0,
  is_deleted            tinyint       NOT NULL DEFAULT 0,
  deleted_ymdhis        bigint        DEFAULT NULL,
  PRIMARY KEY (actor_mood_id)
);

CREATE INDEX {{prefix}}actor_moods_idx_actor ON {{prefix}}actor_moods (actor_id);
CREATE INDEX {{prefix}}actor_moods_idx_recorded ON {{prefix}}actor_moods (recorded_ymdhis);
CREATE INDEX {{prefix}}actor_moods_idx_deleted ON {{prefix}}actor_moods (is_deleted);

-- ============================================================================
-- SECTION 11: ACTOR FAUCETS — rename from lupo_agent_faucets per N3
-- Faucet Proxy Pattern (v4.0.90+): all IDE faucets execute as HEPHAESTUS actor_id 102
-- ============================================================================

-- NOTE: Rename lupo_agent_faucets → lupo_actor_faucets
-- The existing table structure is retained; only name and actor semantics corrected.
-- The executing actor is always an actor_id (e.g. HEPHAESTUS = 102), not an agent.
CREATE TABLE {{prefix}}actor_faucets (
  actor_faucet_id       bigint        NOT NULL,
  actor_id              bigint        NOT NULL,
  -- actor_id: the actor executing via this faucet (HEPHAESTUS = 102 by doctrine)
  faucet_key            varchar(100)  NOT NULL,
  faucet_type           varchar(64)   NOT NULL DEFAULT 'ide',
  -- faucet_type: ide | api | webhook | cli
  target_actor_id       bigint        DEFAULT NULL,
  -- target_actor_id: the IDE or source actor being proxied (e.g. cursor=104, junie=106)
  is_active             tinyint       NOT NULL DEFAULT 1,
  config_json           json          DEFAULT NULL,
  created_ymdhis        bigint        NOT NULL DEFAULT 0,
  updated_ymdhis        bigint        NOT NULL DEFAULT 0,
  is_deleted            tinyint       NOT NULL DEFAULT 0,
  deleted_ymdhis        bigint        DEFAULT NULL,
  PRIMARY KEY (actor_faucet_id)
);

CREATE UNIQUE INDEX {{prefix}}actor_faucets_unq ON {{prefix}}actor_faucets (actor_id, faucet_key);
CREATE INDEX {{prefix}}actor_faucets_idx_actor ON {{prefix}}actor_faucets (actor_id);
CREATE INDEX {{prefix}}actor_faucets_idx_target ON {{prefix}}actor_faucets (target_actor_id);
CREATE INDEX {{prefix}}actor_faucets_idx_type ON {{prefix}}actor_faucets (faucet_type);
CREATE INDEX {{prefix}}actor_faucets_idx_active ON {{prefix}}actor_faucets (is_active);
CREATE INDEX {{prefix}}actor_faucets_idx_deleted ON {{prefix}}actor_faucets (is_deleted);

-- ============================================================================
-- SECTION 12: ACTOR VERSIONS
-- ============================================================================

CREATE TABLE {{prefix}}actor_versions (
  actor_version_id      bigint        NOT NULL,
  actor_id              bigint        NOT NULL,
  version               varchar(50)   NOT NULL,
  version_notes         text          DEFAULT NULL,
  changed_by_actor_id   bigint        DEFAULT NULL,
  snapshot_json         json          DEFAULT NULL,
  created_ymdhis        bigint        NOT NULL DEFAULT 0,
  PRIMARY KEY (actor_version_id)
);

CREATE INDEX {{prefix}}actor_versions_idx_actor ON {{prefix}}actor_versions (actor_id);
CREATE INDEX {{prefix}}actor_versions_idx_version ON {{prefix}}actor_versions (version);
CREATE INDEX {{prefix}}actor_versions_idx_created ON {{prefix}}actor_versions (created_ymdhis);

-- ============================================================================
-- SECTION 13: AGENT DEFINITION VERSIONS
-- ============================================================================

CREATE TABLE {{prefix}}agent_definition_versions (
  agent_def_version_id  bigint        NOT NULL,
  agent_id              bigint        NOT NULL,
  version               varchar(50)   NOT NULL,
  version_notes         text          DEFAULT NULL,
  changed_by_actor_id   bigint        DEFAULT NULL,
  snapshot_json         json          DEFAULT NULL,
  created_ymdhis        bigint        NOT NULL DEFAULT 0,
  PRIMARY KEY (agent_def_version_id)
);

CREATE INDEX {{prefix}}agent_def_versions_idx_agent ON {{prefix}}agent_definition_versions (agent_id);
CREATE INDEX {{prefix}}agent_def_versions_idx_version ON {{prefix}}agent_definition_versions (version);
CREATE INDEX {{prefix}}agent_def_versions_idx_created ON {{prefix}}agent_definition_versions (created_ymdhis);

-- ============================================================================
-- SECTION 14: SYSTEM VERSIONS
-- ============================================================================

CREATE TABLE {{prefix}}versions (
  version_id            bigint        NOT NULL,
  version               varchar(50)   NOT NULL,
  component             varchar(100)  NOT NULL DEFAULT 'schema',
  -- component: schema | api | agents | actors | doctrine
  release_notes         text          DEFAULT NULL,
  is_current            tinyint       NOT NULL DEFAULT 0,
  released_ymdhis       bigint        NOT NULL DEFAULT 0,
  deployed_by_actor_id  bigint        DEFAULT NULL,
  PRIMARY KEY (version_id)
);

CREATE UNIQUE INDEX {{prefix}}versions_unq_component_version ON {{prefix}}versions (component, version);
CREATE INDEX {{prefix}}versions_idx_component ON {{prefix}}versions (component);
CREATE INDEX {{prefix}}versions_idx_current ON {{prefix}}versions (is_current);
CREATE INDEX {{prefix}}versions_idx_released ON {{prefix}}versions (released_ymdhis);

-- ============================================================================
-- SECTION 15: ACTOR AUTH USERS — fix I3 (over-engineered 7-column indexes)
-- Retain table; replace 7-column indexes with targeted 2-3 column indexes
-- ============================================================================

CREATE TABLE {{prefix}}actor_auth_users (
  actor_auth_user_id    bigint        NOT NULL,
  actor_id              bigint        NOT NULL,
  auth_user_id          bigint        NOT NULL,
  relationship_role     varchar(64)   NOT NULL DEFAULT 'supporting_human',
  -- relationship_role: primary_owner | supporting_human | delegate | observer
  is_primary            tinyint       NOT NULL DEFAULT 0,
  routing_priority      smallint      NOT NULL DEFAULT 100,
  status                varchar(32)   NOT NULL DEFAULT 'active',
  -- status: active | inactive | disabled
  metadata_json         json          DEFAULT NULL,
  created_ymdhis        bigint        NOT NULL DEFAULT 0,
  updated_ymdhis        bigint        NOT NULL DEFAULT 0,
  is_deleted            tinyint       NOT NULL DEFAULT 0,
  deleted_ymdhis        bigint        DEFAULT NULL,
  PRIMARY KEY (actor_auth_user_id)
);

-- Targeted indexes replacing the 7-column over-engineered composites
CREATE UNIQUE INDEX {{prefix}}actor_auth_users_unq ON {{prefix}}actor_auth_users (actor_id, auth_user_id, relationship_role);
CREATE INDEX {{prefix}}actor_auth_users_idx_actor_status ON {{prefix}}actor_auth_users (actor_id, status);
CREATE INDEX {{prefix}}actor_auth_users_idx_auth_user ON {{prefix}}actor_auth_users (auth_user_id, status);
CREATE INDEX {{prefix}}actor_auth_users_idx_primary ON {{prefix}}actor_auth_users (actor_id, is_primary);
CREATE INDEX {{prefix}}actor_auth_users_idx_routing ON {{prefix}}actor_auth_users (actor_id, routing_priority);
CREATE INDEX {{prefix}}actor_auth_users_idx_deleted ON {{prefix}}actor_auth_users (is_deleted);

-- ============================================================================
-- SECTION 16: GOVERNANCE OVERRIDES — fix N2 (typo: governance_overrid_id)
-- ============================================================================

-- NOTE: The existing table lupo_governance_overrides has typo column 'governance_overrid_id'.
-- Corrected column name: governance_override_id
-- Full table definition (corrected):
CREATE TABLE {{prefix}}governance_overrides (
  governance_override_id bigint       NOT NULL,
  actor_id              bigint        NOT NULL,
  override_type         varchar(64)   NOT NULL,
  override_scope        varchar(64)   DEFAULT NULL,
  override_reason       text          DEFAULT NULL,
  granted_by_actor_id   bigint        DEFAULT NULL,
  expires_ymdhis        bigint        DEFAULT NULL,
  is_active             tinyint       NOT NULL DEFAULT 1,
  metadata_json         json          DEFAULT NULL,
  created_ymdhis        bigint        NOT NULL DEFAULT 0,
  updated_ymdhis        bigint        NOT NULL DEFAULT 0,
  is_deleted            tinyint       NOT NULL DEFAULT 0,
  deleted_ymdhis        bigint        DEFAULT NULL,
  PRIMARY KEY (governance_override_id)
);

CREATE INDEX {{prefix}}governance_overrides_idx_actor ON {{prefix}}governance_overrides (actor_id);
CREATE INDEX {{prefix}}governance_overrides_idx_type ON {{prefix}}governance_overrides (override_type);
CREATE INDEX {{prefix}}governance_overrides_idx_active ON {{prefix}}governance_overrides (is_active);
CREATE INDEX {{prefix}}governance_overrides_idx_expires ON {{prefix}}governance_overrides (expires_ymdhis);
CREATE INDEX {{prefix}}governance_overrides_idx_deleted ON {{prefix}}governance_overrides (is_deleted);

-- ============================================================================
-- SECTION 17: ACTOR HANDSHAKES — fix C6 (reserved keyword utc_timestamp)
-- ============================================================================

CREATE TABLE {{prefix}}actor_handshakes (
  actor_handshake_id    bigint        NOT NULL,
  actor_id              bigint        NOT NULL,
  actor_type            varchar(32)   NOT NULL,
  handshake_ymdhis      bigint        NOT NULL,
  -- Renamed from utc_timestamp (reserved MySQL function name)
  purpose               varchar(500)  DEFAULT NULL,
  constraints_json      json          DEFAULT NULL,
  forbidden_actions_json json         DEFAULT NULL,
  context               text          DEFAULT NULL,
  expires_ymdhis        bigint        DEFAULT NULL,
  -- Renamed from expires_utc
  created_ymdhis        bigint        NOT NULL DEFAULT 0,
  is_deleted            tinyint       NOT NULL DEFAULT 0,
  deleted_ymdhis        bigint        DEFAULT NULL,
  PRIMARY KEY (actor_handshake_id)
);

CREATE INDEX {{prefix}}actor_handshakes_idx_actor ON {{prefix}}actor_handshakes (actor_id);
CREATE INDEX {{prefix}}actor_handshakes_idx_ymdhis ON {{prefix}}actor_handshakes (handshake_ymdhis);
CREATE INDEX {{prefix}}actor_handshakes_idx_expires ON {{prefix}}actor_handshakes (expires_ymdhis);
CREATE INDEX {{prefix}}actor_handshakes_idx_deleted ON {{prefix}}actor_handshakes (is_deleted);

-- ============================================================================
-- SECTION 18: ANUBIS TABLES — fix N4 (timestamp column naming)
-- Corrected: created_utc → created_ymdhis, updated_utc → updated_ymdhis,
--            attempt_utc → attempt_ymdhis, quarantined_utc → quarantined_ymdhis
-- ============================================================================

-- NOTE: All ANUBIS tables (lupo_anubis_*) must have their UTC-suffix timestamp
-- columns renamed to ymdhis-suffix. The table structures are otherwise retained.
-- This is a naming-only correction across the ANUBIS table group.
-- Apply rename: s/_utc/_ymdhis/ on created_utc, updated_utc, attempt_utc, quarantined_utc

-- ============================================================================
-- SECTION 19: AUTH AUDIT LOG — fix N4 (created_at → created_ymdhis)
-- ============================================================================

-- NOTE: lupo_auth_audit_log.created_at bigint → created_ymdhis bigint
-- NOTE: lupo_crafty_user_mapping.created_at, updated_at → created_ymdhis, updated_ymdhis

-- ============================================================================
-- SECTION 20: DEPRECATED / REMOVE
-- ============================================================================

-- The following tables are redundant and should be removed from the install script:
-- lupo_edge_map              — redundant with lupo_edges
-- lupo_questions             — subsumed by truth/assertion system (lupo_truths)
-- lupo_answers               — subsumed by truth/assertion system
-- lupo_question_map          — subsumed by truth/assertion system
-- lupo_event_metadata        — orphaned (parent lupo_events removed in 4.0.86)
-- lupo_edge_type_definitions — merge into lupo_edge_types

-- ============================================================================
-- SECTION 21: EDGE_TYPES CONSOLIDATION
-- ============================================================================

-- Merge lupo_edge_types + lupo_edge_type_definitions into single authoritative table
CREATE TABLE {{prefix}}edge_types (
  edge_type_id          bigint        NOT NULL,
  edge_type_key         varchar(100)  NOT NULL,
  edge_category         varchar(100)  DEFAULT NULL,
  display_name          varchar(255)  DEFAULT NULL,
  description           text          DEFAULT NULL,
  is_bidirectional      tinyint       NOT NULL DEFAULT 0,
  is_system             tinyint       NOT NULL DEFAULT 0,
  valid_left_types      json          DEFAULT NULL,
  valid_right_types     json          DEFAULT NULL,
  metadata_json         json          DEFAULT NULL,
  created_ymdhis        bigint        NOT NULL DEFAULT 0,
  updated_ymdhis        bigint        NOT NULL DEFAULT 0,
  is_deleted            tinyint       NOT NULL DEFAULT 0,
  deleted_ymdhis        bigint        DEFAULT NULL,
  PRIMARY KEY (edge_type_id)
);

CREATE UNIQUE INDEX {{prefix}}edge_types_unq_key ON {{prefix}}edge_types (edge_type_key);
CREATE INDEX {{prefix}}edge_types_idx_category ON {{prefix}}edge_types (edge_category);
CREATE INDEX {{prefix}}edge_types_idx_system ON {{prefix}}edge_types (is_system);
CREATE INDEX {{prefix}}edge_types_idx_deleted ON {{prefix}}edge_types (is_deleted);

-- ============================================================================
-- END CORRECTED CORE SCHEMA
-- See schema_corrected_missing.sql for new tables to be added
-- See schema_review_20260406.md for full analysis
-- ============================================================================
