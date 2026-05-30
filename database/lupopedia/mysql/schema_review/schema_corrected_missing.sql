-- ============================================================================
-- LUPOPEDIA MISSING TABLES — NEW ADDITIONS
-- Reviewer: claude-code (actor_id 102)
-- Date: 20260406
-- Constitutional basis: LUPOPEDIA_CONSTITUTIONAL_ROOT_RULES.md
-- These tables do not exist in install_new_lupopedia.sql but are required
-- by doctrine, referenced by agents, or needed for structural completeness.
-- ============================================================================

-- ============================================================================
-- SECTION 1: KAIROS MEMORY SYSTEM
-- KAIROS is a defined kernel agent with memory consolidation capabilities.
-- No backing tables exist in the current schema. These are required.
-- ============================================================================

-- Raw KAIROS observations (input to consolidation)
CREATE TABLE {{prefix}}kairos_observations (
  kairos_observation_id  bigint       NOT NULL,
  actor_id               bigint       NOT NULL,
  channel_id             bigint       DEFAULT NULL,
  session_id             varchar(100) DEFAULT NULL,
  observation_type       varchar(64)  NOT NULL DEFAULT 'event',
  -- observation_type: event | assertion | interaction | outcome | correction
  subject_entity_type    varchar(64)  DEFAULT NULL,
  subject_entity_id      bigint       DEFAULT NULL,
  observation_text       text         NOT NULL,
  confidence_score       decimal(3,2) DEFAULT 1.00,
  source_actor_id        bigint       DEFAULT NULL,
  observed_ymdhis        bigint       NOT NULL,
  created_ymdhis         bigint       NOT NULL DEFAULT 0,
  is_consolidated        tinyint      NOT NULL DEFAULT 0,
  consolidated_ymdhis    bigint       DEFAULT NULL,
  is_deleted             tinyint      NOT NULL DEFAULT 0,
  deleted_ymdhis         bigint       DEFAULT NULL,
  PRIMARY KEY (kairos_observation_id)
);

CREATE INDEX {{prefix}}kairos_observations_idx_actor ON {{prefix}}kairos_observations (actor_id);
CREATE INDEX {{prefix}}kairos_observations_idx_type ON {{prefix}}kairos_observations (observation_type);
CREATE INDEX {{prefix}}kairos_observations_idx_subject ON {{prefix}}kairos_observations (subject_entity_type, subject_entity_id);
CREATE INDEX {{prefix}}kairos_observations_idx_observed ON {{prefix}}kairos_observations (observed_ymdhis);
CREATE INDEX {{prefix}}kairos_observations_idx_consolidated ON {{prefix}}kairos_observations (is_consolidated);
CREATE INDEX {{prefix}}kairos_observations_idx_deleted ON {{prefix}}kairos_observations (is_deleted);

-- Consolidated KAIROS memory entries (output of consolidation)
CREATE TABLE {{prefix}}kairos_memory (
  kairos_memory_id       bigint       NOT NULL,
  actor_id               bigint       NOT NULL,
  memory_type            varchar(64)  NOT NULL DEFAULT 'fact',
  -- memory_type: fact | preference | correction | pattern | identity | temporal
  memory_key             varchar(255) DEFAULT NULL,
  memory_text            text         NOT NULL,
  confidence_score       decimal(3,2) DEFAULT 1.00,
  source_observation_ids json         DEFAULT NULL,
  -- JSON array of kairos_observation_id values that produced this memory
  valid_from_ymdhis      bigint       NOT NULL DEFAULT 0,
  valid_until_ymdhis     bigint       DEFAULT NULL,
  -- NULL = no expiry
  superseded_by_id       bigint       DEFAULT NULL,
  -- points to newer kairos_memory_id that replaces this entry
  created_ymdhis         bigint       NOT NULL DEFAULT 0,
  updated_ymdhis         bigint       NOT NULL DEFAULT 0,
  is_deleted             tinyint      NOT NULL DEFAULT 0,
  deleted_ymdhis         bigint       DEFAULT NULL,
  PRIMARY KEY (kairos_memory_id)
);

CREATE INDEX {{prefix}}kairos_memory_idx_actor ON {{prefix}}kairos_memory (actor_id);
CREATE INDEX {{prefix}}kairos_memory_idx_type ON {{prefix}}kairos_memory (memory_type);
CREATE INDEX {{prefix}}kairos_memory_idx_key ON {{prefix}}kairos_memory (memory_key);
CREATE INDEX {{prefix}}kairos_memory_idx_valid_from ON {{prefix}}kairos_memory (valid_from_ymdhis);
CREATE INDEX {{prefix}}kairos_memory_idx_valid_until ON {{prefix}}kairos_memory (valid_until_ymdhis);
CREATE INDEX {{prefix}}kairos_memory_idx_superseded ON {{prefix}}kairos_memory (superseded_by_id);
CREATE INDEX {{prefix}}kairos_memory_idx_deleted ON {{prefix}}kairos_memory (is_deleted);

-- ============================================================================
-- SECTION 2: RUNTIME STATE
-- Current runtime state per actor. Single row per actor, replaced on update.
-- ============================================================================

CREATE TABLE {{prefix}}actor_runtime_state (
  actor_runtime_state_id bigint      NOT NULL,
  actor_id               bigint      NOT NULL,
  current_session_id     varchar(100) DEFAULT NULL,
  current_channel_id     bigint      DEFAULT NULL,
  current_task_id        bigint      DEFAULT NULL,
  last_tool_call_id      bigint      DEFAULT NULL,
  state_key              varchar(64) NOT NULL DEFAULT 'active',
  -- state_key: active | idle | busy | suspended | error | offline
  state_metadata_json    json        DEFAULT NULL,
  state_entered_ymdhis   bigint      NOT NULL DEFAULT 0,
  updated_ymdhis         bigint      NOT NULL DEFAULT 0,
  PRIMARY KEY (actor_runtime_state_id)
);

CREATE UNIQUE INDEX {{prefix}}actor_runtime_state_unq_actor ON {{prefix}}actor_runtime_state (actor_id);
CREATE INDEX {{prefix}}actor_runtime_state_idx_state ON {{prefix}}actor_runtime_state (state_key);
CREATE INDEX {{prefix}}actor_runtime_state_idx_session ON {{prefix}}actor_runtime_state (current_session_id);
CREATE INDEX {{prefix}}actor_runtime_state_idx_updated ON {{prefix}}actor_runtime_state (updated_ymdhis);

-- Runtime lifecycle events (activate, deactivate, handoff, error, recover)
CREATE TABLE {{prefix}}actor_runtime_events (
  actor_runtime_event_id bigint      NOT NULL,
  actor_id               bigint      NOT NULL,
  event_type             varchar(64) NOT NULL,
  -- event_type: activate | deactivate | handoff | error | recover | suspend | resume
  event_details_json     json        DEFAULT NULL,
  triggered_by_actor_id  bigint      DEFAULT NULL,
  session_id             varchar(100) DEFAULT NULL,
  occurred_ymdhis        bigint      NOT NULL,
  created_ymdhis         bigint      NOT NULL DEFAULT 0,
  PRIMARY KEY (actor_runtime_event_id)
);

CREATE INDEX {{prefix}}actor_runtime_events_idx_actor ON {{prefix}}actor_runtime_events (actor_id);
CREATE INDEX {{prefix}}actor_runtime_events_idx_type ON {{prefix}}actor_runtime_events (event_type);
CREATE INDEX {{prefix}}actor_runtime_events_idx_occurred ON {{prefix}}actor_runtime_events (occurred_ymdhis);
CREATE INDEX {{prefix}}actor_runtime_events_idx_session ON {{prefix}}actor_runtime_events (session_id);

-- ============================================================================
-- SECTION 3: FAUCET RULES
-- Rules governing which actor_id a faucet executes as (Faucet Proxy Pattern v4.0.90+)
-- ============================================================================

CREATE TABLE {{prefix}}faucet_rules (
  faucet_rule_id         bigint       NOT NULL,
  rule_key               varchar(100) NOT NULL,
  faucet_type            varchar(64)  NOT NULL,
  -- faucet_type: ide | api | webhook | cli
  source_actor_id        bigint       DEFAULT NULL,
  -- source_actor_id: the IDE actor triggering this rule (cursor=104, junie=106, etc.)
  executing_actor_id     bigint       NOT NULL,
  -- executing_actor_id: the actor that runs all actions (HEPHAESTUS = 102 by doctrine)
  condition_json         json         DEFAULT NULL,
  -- condition: when this rule applies
  priority               int          NOT NULL DEFAULT 100,
  is_active              tinyint      NOT NULL DEFAULT 1,
  notes                  text         DEFAULT NULL,
  created_ymdhis         bigint       NOT NULL DEFAULT 0,
  updated_ymdhis         bigint       NOT NULL DEFAULT 0,
  is_deleted             tinyint      NOT NULL DEFAULT 0,
  deleted_ymdhis         bigint       DEFAULT NULL,
  PRIMARY KEY (faucet_rule_id)
);

CREATE UNIQUE INDEX {{prefix}}faucet_rules_unq_key ON {{prefix}}faucet_rules (rule_key);
CREATE INDEX {{prefix}}faucet_rules_idx_type ON {{prefix}}faucet_rules (faucet_type);
CREATE INDEX {{prefix}}faucet_rules_idx_source ON {{prefix}}faucet_rules (source_actor_id);
CREATE INDEX {{prefix}}faucet_rules_idx_executing ON {{prefix}}faucet_rules (executing_actor_id);
CREATE INDEX {{prefix}}faucet_rules_idx_active ON {{prefix}}faucet_rules (is_active);
CREATE INDEX {{prefix}}faucet_rules_idx_deleted ON {{prefix}}faucet_rules (is_deleted);

-- ============================================================================
-- SECTION 4: PAIRING RULES
-- Rules governing actor pairing assignment and reassignment
-- ============================================================================

CREATE TABLE {{prefix}}pairing_rules (
  pairing_rule_id        bigint       NOT NULL,
  rule_key               varchar(100) NOT NULL,
  rule_type              varchar(64)  NOT NULL,
  -- rule_type: auto_pair | require_approval | prevent_pair | default_pair
  actor_type_a           varchar(64)  DEFAULT NULL,
  actor_type_b           varchar(64)  DEFAULT NULL,
  condition_json         json         DEFAULT NULL,
  priority               int          NOT NULL DEFAULT 100,
  is_active              tinyint      NOT NULL DEFAULT 1,
  notes                  text         DEFAULT NULL,
  created_ymdhis         bigint       NOT NULL DEFAULT 0,
  updated_ymdhis         bigint       NOT NULL DEFAULT 0,
  is_deleted             tinyint      NOT NULL DEFAULT 0,
  deleted_ymdhis         bigint       DEFAULT NULL,
  PRIMARY KEY (pairing_rule_id)
);

CREATE UNIQUE INDEX {{prefix}}pairing_rules_unq_key ON {{prefix}}pairing_rules (rule_key);
CREATE INDEX {{prefix}}pairing_rules_idx_type ON {{prefix}}pairing_rules (rule_type);
CREATE INDEX {{prefix}}pairing_rules_idx_active ON {{prefix}}pairing_rules (is_active);
CREATE INDEX {{prefix}}pairing_rules_idx_deleted ON {{prefix}}pairing_rules (is_deleted);

-- ============================================================================
-- SECTION 5: DEPARTMENT CAPABILITIES
-- Capability grants at the department level (supplements actor-level grants)
-- ============================================================================

CREATE TABLE {{prefix}}department_capabilities (
  dept_capability_id     bigint       NOT NULL,
  department_id          bigint       NOT NULL,
  capability_key         varchar(100) NOT NULL,
  capability_description text         DEFAULT NULL,
  domain_id              bigint       DEFAULT NULL,
  scope_limitation       varchar(50)  NOT NULL DEFAULT 'unrestricted',
  granted_by_actor_id    bigint       DEFAULT NULL,
  created_ymdhis         bigint       NOT NULL DEFAULT 0,
  updated_ymdhis         bigint       NOT NULL DEFAULT 0,
  is_deleted             tinyint      NOT NULL DEFAULT 0,
  deleted_ymdhis         bigint       DEFAULT NULL,
  PRIMARY KEY (dept_capability_id)
);

CREATE UNIQUE INDEX {{prefix}}dept_capabilities_unq ON {{prefix}}department_capabilities (department_id, capability_key);
CREATE INDEX {{prefix}}dept_capabilities_idx_dept ON {{prefix}}department_capabilities (department_id);
CREATE INDEX {{prefix}}dept_capabilities_idx_key ON {{prefix}}department_capabilities (capability_key);
CREATE INDEX {{prefix}}dept_capabilities_idx_deleted ON {{prefix}}department_capabilities (is_deleted);

-- ============================================================================
-- SECTION 6: IDENTITY LAYERS
-- Two-layer identity model: template (agent_definition) vs runtime (actor)
-- ============================================================================

CREATE TABLE {{prefix}}identity_layers (
  identity_layer_id      bigint       NOT NULL,
  layer_key              varchar(64)  NOT NULL,
  layer_name             varchar(255) NOT NULL,
  layer_type             varchar(32)  NOT NULL,
  -- layer_type: template | runtime
  description            text         DEFAULT NULL,
  is_mutable             tinyint      NOT NULL DEFAULT 1,
  -- template layer is_mutable = 0; runtime layer is_mutable = 1
  notes                  text         DEFAULT NULL,
  created_ymdhis         bigint       NOT NULL DEFAULT 0,
  PRIMARY KEY (identity_layer_id)
);

CREATE UNIQUE INDEX {{prefix}}identity_layers_unq_key ON {{prefix}}identity_layers (layer_key);

-- ============================================================================
-- SECTION 7: IDENTITY CONTEXT
-- Active identity context per session/channel (which layer is currently operative)
-- ============================================================================

CREATE TABLE {{prefix}}identity_context (
  identity_context_id    bigint       NOT NULL,
  actor_id               bigint       NOT NULL,
  session_id             varchar(100) DEFAULT NULL,
  channel_id             bigint       DEFAULT NULL,
  active_layer_key       varchar(64)  NOT NULL DEFAULT 'runtime',
  context_snapshot_json  json         DEFAULT NULL,
  activated_ymdhis       bigint       NOT NULL,
  expires_ymdhis         bigint       DEFAULT NULL,
  created_ymdhis         bigint       NOT NULL DEFAULT 0,
  is_deleted             tinyint      NOT NULL DEFAULT 0,
  deleted_ymdhis         bigint       DEFAULT NULL,
  PRIMARY KEY (identity_context_id)
);

CREATE INDEX {{prefix}}identity_context_idx_actor ON {{prefix}}identity_context (actor_id);
CREATE INDEX {{prefix}}identity_context_idx_session ON {{prefix}}identity_context (session_id);
CREATE INDEX {{prefix}}identity_context_idx_channel ON {{prefix}}identity_context (channel_id);
CREATE INDEX {{prefix}}identity_context_idx_layer ON {{prefix}}identity_context (active_layer_key);
CREATE INDEX {{prefix}}identity_context_idx_activated ON {{prefix}}identity_context (activated_ymdhis);
CREATE INDEX {{prefix}}identity_context_idx_deleted ON {{prefix}}identity_context (is_deleted);

-- ============================================================================
-- SECTION 8: AGENT MEMORY CONFIG
-- Memory configuration per agent (KAIROS rollup settings, retention, consolidation)
-- ============================================================================

CREATE TABLE {{prefix}}agent_memory_config (
  agent_memory_config_id bigint       NOT NULL,
  agent_id               bigint       NOT NULL,
  memory_enabled         tinyint      NOT NULL DEFAULT 1,
  rollup_strategy        varchar(64)  NOT NULL DEFAULT 'session',
  -- rollup_strategy: session | daily | threshold | none
  rollup_threshold       int          DEFAULT NULL,
  -- max observations before triggering consolidation (NULL = no threshold)
  retention_days         int          DEFAULT NULL,
  -- NULL = indefinite retention
  consolidation_agent_key varchar(100) DEFAULT 'kairos',
  -- which agent performs consolidation (default: KAIROS)
  config_json            json         DEFAULT NULL,
  created_ymdhis         bigint       NOT NULL DEFAULT 0,
  updated_ymdhis         bigint       NOT NULL DEFAULT 0,
  PRIMARY KEY (agent_memory_config_id)
);

CREATE UNIQUE INDEX {{prefix}}agent_memory_config_unq ON {{prefix}}agent_memory_config (agent_id);
CREATE INDEX {{prefix}}agent_memory_config_idx_strategy ON {{prefix}}agent_memory_config (rollup_strategy);

-- ============================================================================
-- SECTION 9: AGENT TOOL CALLS — fix AS1 (add actor_id as primary execution identity)
-- Corrected from existing lupo_agent_tool_calls to add actor_id
-- ============================================================================

-- NOTE: The existing lupo_agent_tool_calls table should be altered to add actor_id bigint NOT NULL.
-- The executing entity is an actor (runtime instance), not an agent (template).
-- actor_id = which actor executed the call
-- agent_id = which agent definition was being operated under
-- New column: actor_id bigint NOT NULL
-- New index: CREATE INDEX ON lupo_agent_tool_calls (actor_id)

-- ============================================================================
-- SECTION 10: SEED DATA — Identity Layers
-- ============================================================================

-- Canonical two-layer identity model entries
-- Application must insert these at install time (application-supplied IDs)
-- INSERT INTO {{prefix}}identity_layers VALUES
--   (1, 'template', 'Agent Template Layer', 'template', 'Immutable filesystem blueprint. Lives in agents/{slug}/', 0, NULL, 20260406000000),
--   (2, 'runtime',  'Actor Runtime Layer',  'runtime',  'Living runtime instance. Lives in actors/{actor_id}/', 1, NULL, 20260406000000);

-- ============================================================================
-- END MISSING TABLES
-- See schema_corrected_core.sql for corrected existing tables
-- See schema_review_20260406.md for full analysis
-- ============================================================================
