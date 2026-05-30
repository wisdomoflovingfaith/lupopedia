-- ============================================================
-- Lupopedia 4.0.99 Install SQL -- DDL Only
-- Generated from: lupo-database/lupopedia/json/*.json
-- Date: 2026-04-14
-- Doctrine: No AUTO_INCREMENT, No FOREIGN KEY, No ENGINE/COLLATE,
--           BIGINT timestamps, signed integers only, ASCII-only.
-- NOTE: Seed INSERT data not included. Append separately.
-- ============================================================

SET @now = 20260414000000;

-- lupo_action_authorization
CREATE TABLE {{prefix}}action_authorization (
  `action_authorization_id` bigint NOT NULL,
  `action_key` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `required_trait_keys` text,
  `required_capabilities` text,
  `required_role_keys` text,
  `requires_all_conditions` tinyint NOT NULL DEFAULT 0,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `created_by_actor_id` bigint NOT NULL,
  PRIMARY KEY (action_authorization_id)
);

CREATE INDEX {{prefix}}lupo_action_authorization_idx_action ON {{prefix}}action_authorization (action_key);
CREATE UNIQUE INDEX {{prefix}}lupo_action_authorization_unique_action_key ON {{prefix}}action_authorization (action_key);

-- lupo_actor_actions
CREATE TABLE {{prefix}}actor_actions (
  `actor_action_id` bigint NOT NULL,
  `actor_id` bigint NOT NULL,
  `action_type` varchar(64) NOT NULL,
  `entity_type` varchar(64),
  `entity_id` bigint,
  `description` text,
  `metadata_json` json,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  PRIMARY KEY (actor_action_id)
);

CREATE INDEX {{prefix}}lupo_actor_actions_idx_action_type ON {{prefix}}actor_actions (action_type);
CREATE INDEX {{prefix}}lupo_actor_actions_idx_actor ON {{prefix}}actor_actions (actor_id);
CREATE INDEX {{prefix}}lupo_actor_actions_idx_entity ON {{prefix}}actor_actions (entity_type, entity_id);

-- lupo_actor_apps
CREATE TABLE {{prefix}}actor_apps (
  `actor_app_id` bigint NOT NULL,
  `actor_id` bigint NOT NULL,
  `apps_path` varchar(512) NOT NULL DEFAULT '',
  `updated_ymdhis` bigint NOT NULL DEFAULT 0,
  PRIMARY KEY (actor_app_id)
);

CREATE INDEX {{prefix}}lupo_actor_apps_idx_updated ON {{prefix}}actor_apps (updated_ymdhis);
CREATE UNIQUE INDEX {{prefix}}lupo_actor_apps_unq_actor ON {{prefix}}actor_apps (actor_id);

-- lupo_actor_auth_users
CREATE TABLE {{prefix}}actor_auth_users (
  `actor_auth_user_id` bigint NOT NULL,
  `actor_id` bigint NOT NULL,
  `auth_user_id` bigint NOT NULL,
  `relationship_role` varchar(64) NOT NULL DEFAULT 'supporting_human',
  `is_primary` tinyint NOT NULL DEFAULT 0,
  `routing_priority` smallint NOT NULL DEFAULT 100,
  `status` varchar(32) NOT NULL DEFAULT 'active',
  `metadata_json` json,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL DEFAULT 0,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  PRIMARY KEY (actor_auth_user_id)
);

CREATE INDEX {{prefix}}lupo_actor_auth_users_idx_actor_status ON {{prefix}}actor_auth_users (actor_id, status);
CREATE INDEX {{prefix}}lupo_actor_auth_users_idx_auth_user ON {{prefix}}actor_auth_users (auth_user_id, status);
CREATE INDEX {{prefix}}lupo_actor_auth_users_idx_deleted ON {{prefix}}actor_auth_users (is_deleted);
CREATE INDEX {{prefix}}lupo_actor_auth_users_idx_primary ON {{prefix}}actor_auth_users (actor_id, is_primary);
CREATE INDEX {{prefix}}lupo_actor_auth_users_idx_routing ON {{prefix}}actor_auth_users (actor_id, routing_priority);
CREATE UNIQUE INDEX {{prefix}}lupo_actor_auth_users_unq ON {{prefix}}actor_auth_users (actor_id, auth_user_id, relationship_role);

-- lupo_actor_availability_status
CREATE TABLE {{prefix}}actor_availability_status (
  `availability_id` bigint NOT NULL,
  `actor_id` bigint NOT NULL,
  `channel_id` bigint NOT NULL,
  `status` varchar(32) NOT NULL DEFAULT 'offline',
  `last_activity_ymdhis` bigint NOT NULL,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  PRIMARY KEY (availability_id)
);

CREATE INDEX {{prefix}}lupo_actor_availability_status_idx_channel ON {{prefix}}actor_availability_status (channel_id);
CREATE INDEX {{prefix}}lupo_actor_availability_status_idx_is_deleted ON {{prefix}}actor_availability_status (is_deleted);
CREATE INDEX {{prefix}}lupo_actor_availability_status_idx_last_activity ON {{prefix}}actor_availability_status (last_activity_ymdhis);
CREATE INDEX {{prefix}}lupo_actor_availability_status_idx_status ON {{prefix}}actor_availability_status (status);
CREATE UNIQUE INDEX {{prefix}}lupo_actor_availability_status_unique_actor_channel ON {{prefix}}actor_availability_status (actor_id, channel_id);

-- lupo_actor_capabilities
CREATE TABLE {{prefix}}actor_capabilities (
  `actor_capability_id` bigint NOT NULL,
  `actor_id` bigint NOT NULL,
  `domain_id` bigint NOT NULL,
  `capability_key` varchar(100) NOT NULL,
  `capability_description` text,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  `scope_limitation` varchar(50) DEFAULT 'unrestricted',
  `max_calls_per_hour` int DEFAULT 0,
  `requires_approval` tinyint DEFAULT 0,
  `approval_agent_id` bigint,
  PRIMARY KEY (actor_capability_id)
);

CREATE INDEX {{prefix}}lupo_actor_capabilities_idx_agent_domain ON {{prefix}}actor_capabilities (actor_id, domain_id);
CREATE INDEX {{prefix}}lupo_actor_capabilities_idx_capability_key ON {{prefix}}actor_capabilities (capability_key);
CREATE INDEX {{prefix}}lupo_actor_capabilities_idx_created_ymdhis ON {{prefix}}actor_capabilities (created_ymdhis);
CREATE INDEX {{prefix}}lupo_actor_capabilities_idx_domain_id ON {{prefix}}actor_capabilities (domain_id);
CREATE INDEX {{prefix}}lupo_actor_capabilities_idx_is_deleted ON {{prefix}}actor_capabilities (is_deleted);
CREATE INDEX {{prefix}}lupo_actor_capabilities_idx_updated_ymdhis ON {{prefix}}actor_capabilities (updated_ymdhis);
CREATE UNIQUE INDEX {{prefix}}lupo_actor_capabilities_unique_agent_domain_capability ON {{prefix}}actor_capabilities (actor_id, domain_id, capability_key);

-- lupo_actor_channel_roles
CREATE TABLE {{prefix}}actor_channel_roles (
  `actor_channel_role_id` bigint NOT NULL,
  `actor_id` bigint NOT NULL,
  `actor_name` varchar(64),
  `channel_id` bigint NOT NULL,
  `role_key` varchar(64) NOT NULL,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  `handshake_metadata_json` json,
  `awareness_snapshot_json` json,
  `protocol_completion_status` varchar(64) DEFAULT 'pending',
  `protocol_version` varchar(20) DEFAULT '3.0.0',
  `join_sequence_step` tinyint DEFAULT 0,
  `handshake_completed_ymdhis` bigint,
  `awareness_completed_ymdhis` bigint,
  `cjp_completed_ymdhis` bigint,
  PRIMARY KEY (actor_channel_role_id)
);

CREATE INDEX {{prefix}}lupo_actor_channel_roles_idx_actor_id ON {{prefix}}actor_channel_roles (actor_id);
CREATE INDEX {{prefix}}lupo_actor_channel_roles_idx_actor_name ON {{prefix}}actor_channel_roles (actor_name);
CREATE INDEX {{prefix}}lupo_actor_channel_roles_idx_channel_id ON {{prefix}}actor_channel_roles (channel_id);
CREATE INDEX {{prefix}}lupo_actor_channel_roles_idx_join_sequence_step ON {{prefix}}actor_channel_roles (join_sequence_step);
CREATE INDEX {{prefix}}lupo_actor_channel_roles_idx_protocol_completion_status ON {{prefix}}actor_channel_roles (protocol_completion_status);
CREATE INDEX {{prefix}}lupo_actor_channel_roles_idx_protocol_version ON {{prefix}}actor_channel_roles (protocol_version);
CREATE INDEX {{prefix}}lupo_actor_channel_roles_idx_role_key ON {{prefix}}actor_channel_roles (role_key);

-- lupo_actor_channels
CREATE TABLE {{prefix}}actor_channels (
  `actor_channel_id` bigint NOT NULL,
  `actor_id` bigint NOT NULL,
  `actor_name` varchar(64),
  `created_by_actor_id` bigint NOT NULL DEFAULT 0,
  `channel_id` bigint NOT NULL,
  `status` char(1) NOT NULL DEFAULT 'A',
  `start_date` bigint,
  `channel_color` varchar(6) NOT NULL DEFAULT 'F7FAFF',
  `last_read_ymdhis` bigint,
  `muted_until_ymdhis` bigint,
  `preferences_json` json,
  `dialog_output_file` varchar(500),
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  PRIMARY KEY (actor_channel_id)
);

CREATE INDEX {{prefix}}lupo_actor_channels_idx_actor ON {{prefix}}actor_channels (actor_id);
CREATE INDEX {{prefix}}lupo_actor_channels_idx_actor_name ON {{prefix}}actor_channels (actor_name);
CREATE INDEX {{prefix}}lupo_actor_channels_idx_channel ON {{prefix}}actor_channels (channel_id);
CREATE INDEX {{prefix}}lupo_actor_channels_idx_created ON {{prefix}}actor_channels (created_ymdhis);
CREATE INDEX {{prefix}}lupo_actor_channels_idx_deleted ON {{prefix}}actor_channels (is_deleted);
CREATE INDEX {{prefix}}lupo_actor_channels_idx_status ON {{prefix}}actor_channels (status);
CREATE INDEX {{prefix}}lupo_actor_channels_idx_updated ON {{prefix}}actor_channels (updated_ymdhis);
CREATE UNIQUE INDEX {{prefix}}lupo_actor_channels_unq_actor_channel ON {{prefix}}actor_channels (actor_id, channel_id);

-- lupo_actor_collections
CREATE TABLE {{prefix}}actor_collections (
  `actor_collection_id` bigint NOT NULL,
  `actor_id` bigint NOT NULL,
  `collection_id` bigint NOT NULL,
  `access_level` varchar(64) NOT NULL DEFAULT 'read',
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  `persistent_identity_json` json,
  `identity_signature` varchar(255),
  `trust_level` varchar(64) DEFAULT 'standard',
  `emotional_geometry_baseline` json,
  `doctrine_alignment_version` varchar(20) DEFAULT '3.0.0',
  PRIMARY KEY (actor_collection_id)
);

CREATE INDEX {{prefix}}lupo_actor_collections_idx_access_level ON {{prefix}}actor_collections (access_level);
CREATE INDEX {{prefix}}lupo_actor_collections_idx_actor ON {{prefix}}actor_collections (actor_id);
CREATE INDEX {{prefix}}lupo_actor_collections_idx_collection ON {{prefix}}actor_collections (collection_id);
CREATE INDEX {{prefix}}lupo_actor_collections_idx_created_ymdhis ON {{prefix}}actor_collections (created_ymdhis);
CREATE INDEX {{prefix}}lupo_actor_collections_idx_identity_signature ON {{prefix}}actor_collections (identity_signature);
CREATE INDEX {{prefix}}lupo_actor_collections_idx_is_deleted ON {{prefix}}actor_collections (is_deleted);
CREATE INDEX {{prefix}}lupo_actor_collections_idx_trust_level ON {{prefix}}actor_collections (trust_level);

-- lupo_actor_conflicts
CREATE TABLE {{prefix}}actor_conflicts (
  `actor_conflict_id` bigint NOT NULL,
  `domain_id` bigint NOT NULL DEFAULT 1,
  `actor_a_id` bigint NOT NULL,
  `actor_b_id` bigint NOT NULL,
  `conflict_type` varchar(64) NOT NULL,
  `conflict_summary` text NOT NULL,
  `resolution_status` varchar(64) NOT NULL DEFAULT 'unresolved',
  `resolution_summary` text,
  `resolved_by` bigint,
  `resolved_ymdhis` bigint,
  `severity` varchar(64) NOT NULL DEFAULT 'medium',
  `context_json` json,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  PRIMARY KEY (actor_conflict_id)
);

CREATE INDEX {{prefix}}lupo_actor_conflicts_idx_agent_a ON {{prefix}}actor_conflicts (actor_a_id);
CREATE INDEX {{prefix}}lupo_actor_conflicts_idx_agent_b ON {{prefix}}actor_conflicts (actor_b_id);
CREATE INDEX {{prefix}}lupo_actor_conflicts_idx_agent_pair ON {{prefix}}actor_conflicts (actor_a_id, actor_b_id);
CREATE INDEX {{prefix}}lupo_actor_conflicts_idx_conflict_type ON {{prefix}}actor_conflicts (conflict_type);
CREATE INDEX {{prefix}}lupo_actor_conflicts_idx_created ON {{prefix}}actor_conflicts (created_ymdhis);
CREATE INDEX {{prefix}}lupo_actor_conflicts_idx_deleted ON {{prefix}}actor_conflicts (is_deleted);
CREATE INDEX {{prefix}}lupo_actor_conflicts_idx_domain ON {{prefix}}actor_conflicts (domain_id);
CREATE INDEX {{prefix}}lupo_actor_conflicts_idx_resolved_ymdhis ON {{prefix}}actor_conflicts (resolved_ymdhis);
CREATE INDEX {{prefix}}lupo_actor_conflicts_idx_severity ON {{prefix}}actor_conflicts (severity);
CREATE INDEX {{prefix}}lupo_actor_conflicts_idx_status ON {{prefix}}actor_conflicts (resolution_status);
CREATE INDEX {{prefix}}lupo_actor_conflicts_idx_updated ON {{prefix}}actor_conflicts (updated_ymdhis);

-- lupo_actor_departments
CREATE TABLE {{prefix}}actor_departments (
  `actor_department_id` bigint NOT NULL,
  `actor_id` bigint NOT NULL,
  `department_id` bigint NOT NULL,
  `role_key` varchar(64),
  `title` varchar(64),
  `is_primary` tinyint NOT NULL DEFAULT 0,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL DEFAULT 0,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  PRIMARY KEY (actor_department_id)
);

CREATE INDEX {{prefix}}lupo_actor_departments_idx_actor ON {{prefix}}actor_departments (actor_id);
CREATE INDEX {{prefix}}lupo_actor_departments_idx_deleted ON {{prefix}}actor_departments (is_deleted);
CREATE INDEX {{prefix}}lupo_actor_departments_idx_department ON {{prefix}}actor_departments (department_id);
CREATE INDEX {{prefix}}lupo_actor_departments_idx_primary ON {{prefix}}actor_departments (actor_id, is_primary);
CREATE UNIQUE INDEX {{prefix}}lupo_actor_departments_unq ON {{prefix}}actor_departments (actor_id, department_id);

-- lupo_actor_faucets
CREATE TABLE {{prefix}}actor_faucets (
  `actor_faucet_id` bigint NOT NULL,
  `actor_id` bigint NOT NULL,
  `faucet_key` varchar(100) NOT NULL,
  `faucet_type` varchar(64) NOT NULL DEFAULT 'ide',
  `target_actor_id` bigint,
  `is_active` tinyint NOT NULL DEFAULT 1,
  `config_json` json,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL DEFAULT 0,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  PRIMARY KEY (actor_faucet_id)
);

CREATE INDEX {{prefix}}lupo_actor_faucets_idx_active ON {{prefix}}actor_faucets (is_active);
CREATE INDEX {{prefix}}lupo_actor_faucets_idx_actor ON {{prefix}}actor_faucets (actor_id);
CREATE INDEX {{prefix}}lupo_actor_faucets_idx_deleted ON {{prefix}}actor_faucets (is_deleted);
CREATE INDEX {{prefix}}lupo_actor_faucets_idx_target ON {{prefix}}actor_faucets (target_actor_id);
CREATE INDEX {{prefix}}lupo_actor_faucets_idx_type ON {{prefix}}actor_faucets (faucet_type);
CREATE UNIQUE INDEX {{prefix}}lupo_actor_faucets_unq ON {{prefix}}actor_faucets (actor_id, faucet_key);

-- lupo_actor_filesystem
CREATE TABLE {{prefix}}actor_filesystem (
  `actor_filesystem_id` bigint NOT NULL,
  `actor_id` bigint NOT NULL,
  `actor_root_path` varchar(512),
  `workspace_path` varchar(255),
  `php_namespace` varchar(120),
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL DEFAULT 0,
  PRIMARY KEY (actor_filesystem_id)
);

CREATE INDEX {{prefix}}lupo_actor_filesystem_idx_php_namespace ON {{prefix}}actor_filesystem (php_namespace);
CREATE INDEX {{prefix}}lupo_actor_filesystem_idx_workspace ON {{prefix}}actor_filesystem (workspace_path);
CREATE UNIQUE INDEX {{prefix}}lupo_actor_filesystem_unq_actor ON {{prefix}}actor_filesystem (actor_id);

-- lupo_actor_handshakes
CREATE TABLE {{prefix}}actor_handshakes (
  `actor_handshake_id` bigint NOT NULL,
  `actor_id` bigint NOT NULL,
  `actor_type` varchar(32) NOT NULL,
  `handshake_ymdhis` bigint NOT NULL,
  `purpose` varchar(500),
  `constraints_json` json,
  `forbidden_actions_json` json,
  `context` text,
  `expires_ymdhis` bigint,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  PRIMARY KEY (actor_handshake_id)
);

CREATE INDEX {{prefix}}lupo_actor_handshakes_idx_actor ON {{prefix}}actor_handshakes (actor_id);
CREATE INDEX {{prefix}}lupo_actor_handshakes_idx_deleted ON {{prefix}}actor_handshakes (is_deleted);
CREATE INDEX {{prefix}}lupo_actor_handshakes_idx_expires ON {{prefix}}actor_handshakes (expires_ymdhis);
CREATE INDEX {{prefix}}lupo_actor_handshakes_idx_ymdhis ON {{prefix}}actor_handshakes (handshake_ymdhis);

-- lupo_actor_history
CREATE TABLE {{prefix}}actor_history (
  `history_id` bigint NOT NULL,
  `actor_id` bigint NOT NULL,
  `achievement_id` varchar(100),
  `title` varchar(255) NOT NULL,
  `description` text,
  `impact` text,
  `date_ymdhis` bigint NOT NULL DEFAULT 0,
  `channel_id` bigint,
  `tags` json,
  `metrics` json,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint
);

CREATE INDEX {{prefix}}lupo_actor_history_idx_actor_id ON {{prefix}}actor_history (actor_id);
CREATE INDEX {{prefix}}lupo_actor_history_idx_channel_id ON {{prefix}}actor_history (channel_id);
CREATE INDEX {{prefix}}lupo_actor_history_idx_date_ymdhis ON {{prefix}}actor_history (date_ymdhis);
CREATE INDEX {{prefix}}lupo_actor_history_idx_is_deleted ON {{prefix}}actor_history (is_deleted);

-- lupo_actor_moods
CREATE TABLE {{prefix}}actor_moods (
  `actor_mood_id` bigint NOT NULL,
  `actor_id` bigint NOT NULL,
  `mood_r` tinyint NOT NULL,
  `mood_g` tinyint NOT NULL,
  `mood_b` tinyint NOT NULL,
  `mood_framework` varchar(32) NOT NULL DEFAULT 'western_analytical',
  `recorded_ymdhis` bigint NOT NULL,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  PRIMARY KEY (actor_mood_id)
);

CREATE INDEX {{prefix}}lupo_actor_moods_idx_actor ON {{prefix}}actor_moods (actor_id);
CREATE INDEX {{prefix}}lupo_actor_moods_idx_deleted ON {{prefix}}actor_moods (is_deleted);
CREATE INDEX {{prefix}}lupo_actor_moods_idx_recorded ON {{prefix}}actor_moods (recorded_ymdhis);

-- lupo_actor_pairing
CREATE TABLE {{prefix}}actor_pairing (
  `actor_pairing_id` bigint NOT NULL,
  `actor_id` bigint NOT NULL,
  `paired_actor_id` bigint NOT NULL,
  `pairing_role` varchar(64) NOT NULL DEFAULT 'peer',
  `pairing_type` varchar(64) NOT NULL DEFAULT 'operational',
  `is_primary` tinyint NOT NULL DEFAULT 1,
  `notes` text,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL DEFAULT 0,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  PRIMARY KEY (actor_pairing_id)
);

CREATE INDEX {{prefix}}lupo_actor_pairing_idx_actor ON {{prefix}}actor_pairing (actor_id);
CREATE INDEX {{prefix}}lupo_actor_pairing_idx_deleted ON {{prefix}}actor_pairing (is_deleted);
CREATE INDEX {{prefix}}lupo_actor_pairing_idx_paired ON {{prefix}}actor_pairing (paired_actor_id);
CREATE INDEX {{prefix}}lupo_actor_pairing_idx_type ON {{prefix}}actor_pairing (pairing_type);
CREATE UNIQUE INDEX {{prefix}}lupo_actor_pairing_unq_pair ON {{prefix}}actor_pairing (actor_id, paired_actor_id, pairing_role);

-- lupo_actor_prompts
CREATE TABLE {{prefix}}actor_prompts (
  `prompt_id` bigint NOT NULL,
  `actor_id` bigint NOT NULL,
  `prompt_key` varchar(128) NOT NULL,
  `prompt_text` text NOT NULL,
  `prompt_type` varchar(64),
  `context_json` json,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  PRIMARY KEY (prompt_id)
);

CREATE INDEX {{prefix}}lupo_actor_prompts_idx_actor_id ON {{prefix}}actor_prompts (actor_id);
CREATE INDEX {{prefix}}lupo_actor_prompts_idx_is_deleted ON {{prefix}}actor_prompts (is_deleted);
CREATE INDEX {{prefix}}lupo_actor_prompts_idx_prompt_key ON {{prefix}}actor_prompts (prompt_key);

-- lupo_actor_relationships
CREATE TABLE {{prefix}}actor_relationships (
  `actor_relationship_id` bigint NOT NULL,
  `actor_a_id` bigint NOT NULL,
  `actor_b_id` bigint NOT NULL,
  `relationship_type` varchar(64) NOT NULL,
  `authority_direction` varchar(32) NOT NULL DEFAULT 'a_over_b',
  `is_active` tinyint NOT NULL DEFAULT 1,
  `notes` text,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL DEFAULT 0,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  PRIMARY KEY (actor_relationship_id)
);

CREATE INDEX {{prefix}}lupo_actor_relationships_idx_a ON {{prefix}}actor_relationships (actor_a_id);
CREATE INDEX {{prefix}}lupo_actor_relationships_idx_b ON {{prefix}}actor_relationships (actor_b_id);
CREATE INDEX {{prefix}}lupo_actor_relationships_idx_deleted ON {{prefix}}actor_relationships (is_deleted);
CREATE INDEX {{prefix}}lupo_actor_relationships_idx_type ON {{prefix}}actor_relationships (relationship_type);
CREATE UNIQUE INDEX {{prefix}}lupo_actor_relationships_unq ON {{prefix}}actor_relationships (actor_a_id, actor_b_id, relationship_type);

-- lupo_actor_reply_templates
CREATE TABLE {{prefix}}actor_reply_templates (
  `actor_reply_template_id` bigint NOT NULL,
  `actor_id` bigint NOT NULL,
  `template_key` varchar(64) NOT NULL,
  `template_text` text NOT NULL,
  `usage_context` varchar(64),
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  PRIMARY KEY (actor_reply_template_id)
);

CREATE INDEX {{prefix}}lupo_actor_reply_templates_idx_actor ON {{prefix}}actor_reply_templates (actor_id);
CREATE INDEX {{prefix}}lupo_actor_reply_templates_idx_created ON {{prefix}}actor_reply_templates (created_ymdhis);
CREATE INDEX {{prefix}}lupo_actor_reply_templates_idx_deleted ON {{prefix}}actor_reply_templates (is_deleted);
CREATE INDEX {{prefix}}lupo_actor_reply_templates_idx_key ON {{prefix}}actor_reply_templates (template_key);
CREATE INDEX {{prefix}}lupo_actor_reply_templates_idx_updated ON {{prefix}}actor_reply_templates (updated_ymdhis);
CREATE INDEX {{prefix}}lupo_actor_reply_templates_idx_usage_context ON {{prefix}}actor_reply_templates (usage_context);
CREATE UNIQUE INDEX {{prefix}}lupo_actor_reply_templates_unq_actor_template_key ON {{prefix}}actor_reply_templates (actor_id, template_key);

-- lupo_actor_runtime_events
CREATE TABLE {{prefix}}actor_runtime_events (
  `actor_runtime_event_id` bigint NOT NULL,
  `actor_id` bigint NOT NULL,
  `event_type` varchar(64) NOT NULL,
  `event_details_json` json,
  `triggered_by_actor_id` bigint,
  `session_id` varchar(100),
  `occurred_ymdhis` bigint NOT NULL,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  PRIMARY KEY (actor_runtime_event_id)
);

CREATE INDEX {{prefix}}lupo_actor_runtime_events_idx_actor ON {{prefix}}actor_runtime_events (actor_id);
CREATE INDEX {{prefix}}lupo_actor_runtime_events_idx_occurred ON {{prefix}}actor_runtime_events (occurred_ymdhis);
CREATE INDEX {{prefix}}lupo_actor_runtime_events_idx_session ON {{prefix}}actor_runtime_events (session_id);
CREATE INDEX {{prefix}}lupo_actor_runtime_events_idx_type ON {{prefix}}actor_runtime_events (event_type);

-- lupo_actor_runtime_state
CREATE TABLE {{prefix}}actor_runtime_state (
  `actor_runtime_state_id` bigint NOT NULL,
  `actor_id` bigint NOT NULL,
  `current_session_id` varchar(100),
  `current_channel_id` bigint,
  `current_task_id` bigint,
  `last_tool_call_id` bigint,
  `state_key` varchar(64) NOT NULL DEFAULT 'active',
  `state_metadata_json` json,
  `state_entered_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL DEFAULT 0,
  PRIMARY KEY (actor_runtime_state_id)
);

CREATE INDEX {{prefix}}lupo_actor_runtime_state_idx_session ON {{prefix}}actor_runtime_state (current_session_id);
CREATE INDEX {{prefix}}lupo_actor_runtime_state_idx_state ON {{prefix}}actor_runtime_state (state_key);
CREATE INDEX {{prefix}}lupo_actor_runtime_state_idx_updated ON {{prefix}}actor_runtime_state (updated_ymdhis);
CREATE UNIQUE INDEX {{prefix}}lupo_actor_runtime_state_unq_actor ON {{prefix}}actor_runtime_state (actor_id);

-- lupo_actor_skills
CREATE TABLE {{prefix}}actor_skills (
  `skill_id` bigint NOT NULL,
  `actor_id` bigint NOT NULL,
  `skill_name` varchar(128) NOT NULL,
  `skill_level` varchar(32),
  `skill_metadata` json,
  `acquired_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  PRIMARY KEY (skill_id)
);

CREATE INDEX {{prefix}}lupo_actor_skills_idx_actor_id ON {{prefix}}actor_skills (actor_id);
CREATE INDEX {{prefix}}lupo_actor_skills_idx_is_deleted ON {{prefix}}actor_skills (is_deleted);
CREATE INDEX {{prefix}}lupo_actor_skills_idx_skill_name ON {{prefix}}actor_skills (skill_name);

-- lupo_actor_sync_state
CREATE TABLE {{prefix}}actor_sync_state (
  `actor_sync_state_id` bigint NOT NULL,
  `actor_id` bigint NOT NULL,
  `sync_target` varchar(64) NOT NULL DEFAULT 'who_json',
  `sync_status` varchar(64) NOT NULL DEFAULT 'pending',
  `last_sync_ymdhis` bigint NOT NULL DEFAULT 0,
  `sync_error_message` text,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL DEFAULT 0,
  PRIMARY KEY (actor_sync_state_id)
);

CREATE INDEX {{prefix}}lupo_actor_sync_state_idx_last_sync ON {{prefix}}actor_sync_state (last_sync_ymdhis);
CREATE INDEX {{prefix}}lupo_actor_sync_state_idx_status ON {{prefix}}actor_sync_state (sync_status);
CREATE UNIQUE INDEX {{prefix}}lupo_actor_sync_state_unq_actor_target ON {{prefix}}actor_sync_state (actor_id, sync_target);

-- lupo_actor_tools
CREATE TABLE {{prefix}}actor_tools (
  `tool_id` bigint NOT NULL,
  `actor_id` bigint NOT NULL,
  `tool_name` varchar(128) NOT NULL,
  `tool_type` varchar(64),
  `tool_metadata` json,
  `acquired_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  PRIMARY KEY (tool_id)
);

CREATE INDEX {{prefix}}lupo_actor_tools_idx_actor_id ON {{prefix}}actor_tools (actor_id);
CREATE INDEX {{prefix}}lupo_actor_tools_idx_is_deleted ON {{prefix}}actor_tools (is_deleted);
CREATE INDEX {{prefix}}lupo_actor_tools_idx_tool_name ON {{prefix}}actor_tools (tool_name);

-- lupo_actor_training
CREATE TABLE {{prefix}}actor_training (
  `training_id` bigint NOT NULL,
  `actor_id` bigint NOT NULL,
  `training_type` varchar(64) NOT NULL,
  `training_data` text,
  `training_metadata` json,
  `started_ymdhis` bigint NOT NULL DEFAULT 0,
  `completed_ymdhis` bigint,
  `updated_ymdhis` bigint,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  PRIMARY KEY (training_id)
);

CREATE INDEX {{prefix}}lupo_actor_training_idx_actor_id ON {{prefix}}actor_training (actor_id);
CREATE INDEX {{prefix}}lupo_actor_training_idx_is_deleted ON {{prefix}}actor_training (is_deleted);
CREATE INDEX {{prefix}}lupo_actor_training_idx_training_type ON {{prefix}}actor_training (training_type);

-- lupo_actor_traits
CREATE TABLE {{prefix}}actor_traits (
  `actor_trait_id` bigint NOT NULL,
  `actor_id` bigint NOT NULL,
  `trait_key` varchar(128) NOT NULL,
  `trait_value` varchar(255),
  `federation_node_id` bigint DEFAULT 1,
  `created_by_actor_id` bigint,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL DEFAULT 0,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  `metadata` text,
  PRIMARY KEY (actor_trait_id)
);

CREATE INDEX {{prefix}}lupo_actor_traits_idx_actor ON {{prefix}}actor_traits (actor_id);
CREATE INDEX {{prefix}}lupo_actor_traits_idx_deleted ON {{prefix}}actor_traits (is_deleted);
CREATE INDEX {{prefix}}lupo_actor_traits_idx_federation ON {{prefix}}actor_traits (federation_node_id);
CREATE INDEX {{prefix}}lupo_actor_traits_idx_trait_key ON {{prefix}}actor_traits (trait_key);

-- lupo_actor_versions
CREATE TABLE {{prefix}}actor_versions (
  `actor_version_id` bigint NOT NULL,
  `actor_id` bigint NOT NULL,
  `version` varchar(50) NOT NULL,
  `version_notes` text,
  `changed_by_actor_id` bigint,
  `snapshot_json` json,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  PRIMARY KEY (actor_version_id)
);

CREATE INDEX {{prefix}}lupo_actor_versions_idx_actor ON {{prefix}}actor_versions (actor_id);
CREATE INDEX {{prefix}}lupo_actor_versions_idx_created ON {{prefix}}actor_versions (created_ymdhis);
CREATE INDEX {{prefix}}lupo_actor_versions_idx_version ON {{prefix}}actor_versions (version);

-- lupo_actors
CREATE TABLE {{prefix}}actors (
  `actor_id` bigint NOT NULL,
  `actor_name` varchar(64) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `actor_type` varchar(64) NOT NULL,
  `agent_key` varchar(100),
  `is_kernel` tinyint NOT NULL DEFAULT 0,
  `is_required` tinyint NOT NULL DEFAULT 0,
  `can_login` tinyint NOT NULL DEFAULT 0,
  `is_agent` tinyint NOT NULL DEFAULT 0,
  `is_active` tinyint NOT NULL DEFAULT 1,
  `actor_tier` tinyint DEFAULT 3,
  `auth_user_id` bigint,
  `actor_source_id` bigint,
  `actor_source_type` varchar(64),
  `avatar_hash` varchar(64),
  `primary_federation_node_id` bigint NOT NULL DEFAULT 1,
  `web_restrict_act_as_creator_or_root` tinyint NOT NULL DEFAULT 0,
  `identity_provider_config` json,
  `metadata_json` json,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL DEFAULT 0,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  PRIMARY KEY (actor_id)
);

CREATE INDEX {{prefix}}lupo_actors_idx_actor_type ON {{prefix}}actors (actor_type);
CREATE INDEX {{prefix}}lupo_actors_idx_agent_key ON {{prefix}}actors (agent_key);
CREATE INDEX {{prefix}}lupo_actors_idx_created_ymdhis ON {{prefix}}actors (created_ymdhis);
CREATE INDEX {{prefix}}lupo_actors_idx_is_active ON {{prefix}}actors (is_active);
CREATE INDEX {{prefix}}lupo_actors_idx_is_deleted ON {{prefix}}actors (is_deleted);
CREATE INDEX {{prefix}}lupo_actors_idx_is_kernel ON {{prefix}}actors (is_kernel);
CREATE UNIQUE INDEX {{prefix}}lupo_actors_unq_actor_name ON {{prefix}}actors (actor_name);
CREATE UNIQUE INDEX {{prefix}}lupo_actors_unq_slug ON {{prefix}}actors (slug);

-- lupo_agent_boundaries
CREATE TABLE {{prefix}}agent_boundaries (
  `agent_boundary_id` bigint NOT NULL,
  `agent_id` bigint NOT NULL,
  `boundary_type` varchar(64) NOT NULL,
  `domain_key` varchar(100) NOT NULL,
  `owner_agent_key` varchar(100),
  `boundary_description` text,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL DEFAULT 0,
  PRIMARY KEY (agent_boundary_id)
);

CREATE INDEX {{prefix}}lupo_agent_boundaries_idx_agent ON {{prefix}}agent_boundaries (agent_id);
CREATE INDEX {{prefix}}lupo_agent_boundaries_idx_domain ON {{prefix}}agent_boundaries (domain_key);
CREATE INDEX {{prefix}}lupo_agent_boundaries_idx_owner ON {{prefix}}agent_boundaries (owner_agent_key);
CREATE INDEX {{prefix}}lupo_agent_boundaries_idx_type ON {{prefix}}agent_boundaries (boundary_type);
CREATE UNIQUE INDEX {{prefix}}lupo_agent_boundaries_unq ON {{prefix}}agent_boundaries (agent_id, boundary_type, domain_key);

-- lupo_agent_capabilities
CREATE TABLE {{prefix}}agent_capabilities (
  `agent_capability_id` bigint NOT NULL,
  `agent_id` bigint NOT NULL,
  `capability_key` varchar(100) NOT NULL,
  `capability_category` varchar(64),
  `capability_description` text,
  `is_out_of_scope` tinyint NOT NULL DEFAULT 0,
  `out_of_scope_owner` varchar(100),
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL DEFAULT 0,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  PRIMARY KEY (agent_capability_id)
);

CREATE INDEX {{prefix}}lupo_agent_capabilities_idx_agent ON {{prefix}}agent_capabilities (agent_id);
CREATE INDEX {{prefix}}lupo_agent_capabilities_idx_category ON {{prefix}}agent_capabilities (capability_category);
CREATE INDEX {{prefix}}lupo_agent_capabilities_idx_deleted ON {{prefix}}agent_capabilities (is_deleted);
CREATE INDEX {{prefix}}lupo_agent_capabilities_idx_key ON {{prefix}}agent_capabilities (capability_key);
CREATE INDEX {{prefix}}lupo_agent_capabilities_idx_scope ON {{prefix}}agent_capabilities (is_out_of_scope);
CREATE UNIQUE INDEX {{prefix}}lupo_agent_capabilities_unq ON {{prefix}}agent_capabilities (agent_id, capability_key);

-- lupo_agent_definition_versions
CREATE TABLE {{prefix}}agent_definition_versions (
  `agent_def_version_id` bigint NOT NULL,
  `agent_id` bigint NOT NULL,
  `version` varchar(50) NOT NULL,
  `version_notes` text,
  `changed_by_actor_id` bigint,
  `snapshot_json` json,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  PRIMARY KEY (agent_def_version_id)
);

CREATE INDEX {{prefix}}lupo_agent_def_versions_idx_agent ON {{prefix}}agent_definition_versions (agent_id);
CREATE INDEX {{prefix}}lupo_agent_def_versions_idx_created ON {{prefix}}agent_definition_versions (created_ymdhis);
CREATE INDEX {{prefix}}lupo_agent_def_versions_idx_version ON {{prefix}}agent_definition_versions (version);

-- lupo_agent_definitions
CREATE TABLE {{prefix}}agent_definitions (
  `agent_id` bigint NOT NULL,
  `agent_key` varchar(100) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `layer` varchar(64) NOT NULL DEFAULT 'application',
  `role` varchar(500),
  `agent_class` varchar(100),
  `archetype` varchar(150),
  `description` text,
  `is_kernel` tinyint NOT NULL DEFAULT 0,
  `is_required` tinyint NOT NULL DEFAULT 0,
  `department_id` bigint,
  `learning_boundary` varchar(255),
  `lineage_json` json,
  `capabilities_json` json,
  `system_prompt_path` varchar(512),
  `version` varchar(50) NOT NULL DEFAULT '1.0.0',
  `status` varchar(32) NOT NULL DEFAULT 'active',
  `metadata_json` json,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL DEFAULT 0,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  PRIMARY KEY (agent_id)
);

CREATE INDEX {{prefix}}lupo_agent_definitions_idx_deleted ON {{prefix}}agent_definitions (is_deleted);
CREATE INDEX {{prefix}}lupo_agent_definitions_idx_department ON {{prefix}}agent_definitions (department_id);
CREATE INDEX {{prefix}}lupo_agent_definitions_idx_is_kernel ON {{prefix}}agent_definitions (is_kernel);
CREATE INDEX {{prefix}}lupo_agent_definitions_idx_is_required ON {{prefix}}agent_definitions (is_required);
CREATE INDEX {{prefix}}lupo_agent_definitions_idx_layer ON {{prefix}}agent_definitions (layer);
CREATE INDEX {{prefix}}lupo_agent_definitions_idx_status ON {{prefix}}agent_definitions (status);
CREATE UNIQUE INDEX {{prefix}}lupo_agent_definitions_unq_key ON {{prefix}}agent_definitions (agent_key);
CREATE UNIQUE INDEX {{prefix}}lupo_agent_definitions_unq_slug ON {{prefix}}agent_definitions (slug);

-- lupo_agent_llm_configs
CREATE TABLE {{prefix}}agent_llm_configs (
  `agent_llm_config_id` bigint NOT NULL,
  `agent_id` bigint NOT NULL,
  `config_name` varchar(100) NOT NULL DEFAULT 'default',
  `provider` varchar(50) NOT NULL DEFAULT 'anthropic',
  `model_name` varchar(100),
  `api_key_id` bigint,
  `temperature` float DEFAULT 0.7,
  `top_p` float DEFAULT 1,
  `max_tokens` int DEFAULT 2048,
  `presence_penalty` float DEFAULT 0,
  `frequency_penalty` float DEFAULT 0,
  `timeout_ms` int DEFAULT 20000,
  `cost_per_1k_tokens` decimal(10,4) DEFAULT 0.0000,
  `safety_json` json,
  `response_format` varchar(50),
  `is_active` tinyint NOT NULL DEFAULT 1,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL DEFAULT 0,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  PRIMARY KEY (agent_llm_config_id)
);

CREATE INDEX {{prefix}}lupo_agent_llm_configs_idx_active ON {{prefix}}agent_llm_configs (is_active);
CREATE INDEX {{prefix}}lupo_agent_llm_configs_idx_agent ON {{prefix}}agent_llm_configs (agent_id);
CREATE INDEX {{prefix}}lupo_agent_llm_configs_idx_api_key ON {{prefix}}agent_llm_configs (api_key_id);
CREATE INDEX {{prefix}}lupo_agent_llm_configs_idx_deleted ON {{prefix}}agent_llm_configs (is_deleted);
CREATE INDEX {{prefix}}lupo_agent_llm_configs_idx_provider ON {{prefix}}agent_llm_configs (provider);
CREATE UNIQUE INDEX {{prefix}}lupo_agent_llm_configs_unq_agent_config ON {{prefix}}agent_llm_configs (agent_id, config_name);

-- lupo_agent_memory_config
CREATE TABLE {{prefix}}agent_memory_config (
  `agent_memory_config_id` bigint NOT NULL,
  `agent_id` bigint NOT NULL,
  `memory_enabled` tinyint NOT NULL DEFAULT 1,
  `rollup_strategy` varchar(64) NOT NULL DEFAULT 'session',
  `rollup_threshold` int,
  `retention_days` int,
  `consolidation_agent_key` varchar(100) DEFAULT 'kairos',
  `config_json` json,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL DEFAULT 0,
  PRIMARY KEY (agent_memory_config_id)
);

CREATE INDEX {{prefix}}lupo_agent_memory_config_idx_strategy ON {{prefix}}agent_memory_config (rollup_strategy);
CREATE UNIQUE INDEX {{prefix}}lupo_agent_memory_config_unq ON {{prefix}}agent_memory_config (agent_id);

-- lupo_agent_performance_stats
CREATE TABLE {{prefix}}agent_performance_stats (
  `agent_perf_id` bigint NOT NULL,
  `agent_id` bigint NOT NULL,
  `stat_window` varchar(32) NOT NULL DEFAULT 'all_time',
  `avg_response_time_ms` int DEFAULT 0,
  `total_tokens_processed` bigint DEFAULT 0,
  `success_rate` float DEFAULT 1,
  `total_calls` bigint DEFAULT 0,
  `last_called_ymdhis` bigint DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL DEFAULT 0,
  PRIMARY KEY (agent_perf_id)
);

CREATE INDEX {{prefix}}lupo_agent_perf_idx_agent ON {{prefix}}agent_performance_stats (agent_id);
CREATE INDEX {{prefix}}lupo_agent_perf_idx_window ON {{prefix}}agent_performance_stats (stat_window);
CREATE UNIQUE INDEX {{prefix}}lupo_agent_perf_unq_agent_window ON {{prefix}}agent_performance_stats (agent_id, stat_window);

-- lupo_agent_tool_calls
CREATE TABLE {{prefix}}agent_tool_calls (
  `agent_tool_call_id` bigint NOT NULL,
  `actor_id` bigint NOT NULL,
  `agent_id` bigint NOT NULL,
  `faucet_id` bigint,
  `domain_id` bigint NOT NULL,
  `tool_name` varchar(150) NOT NULL,
  `action_type` varchar(100),
  `input_json` text,
  `output_json` text,
  `provider` varchar(50),
  `model_name` varchar(150),
  `tokens_prompt` int DEFAULT 0,
  `tokens_completion` int DEFAULT 0,
  `tokens_total` int DEFAULT 0,
  `cost_usd` decimal(10,6) DEFAULT 0.000000,
  `latency_ms` int DEFAULT 0,
  `status` varchar(50) DEFAULT 'success',
  `error_message` text,
  `parent_call_id` bigint,
  `thread_id` bigint,
  `message_id` bigint,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL DEFAULT 0,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  `archived_ymdhis` bigint DEFAULT 0,
  `completed_ymdhis` bigint,
  PRIMARY KEY (agent_tool_call_id)
);

CREATE INDEX {{prefix}}lupo_agent_tool_calls_idx_actor ON {{prefix}}agent_tool_calls (actor_id);
CREATE INDEX {{prefix}}lupo_agent_tool_calls_idx_actor_created ON {{prefix}}agent_tool_calls (actor_id, created_ymdhis);
CREATE INDEX {{prefix}}lupo_agent_tool_calls_idx_agent ON {{prefix}}agent_tool_calls (agent_id);
CREATE INDEX {{prefix}}lupo_agent_tool_calls_idx_deleted ON {{prefix}}agent_tool_calls (is_deleted);
CREATE INDEX {{prefix}}lupo_agent_tool_calls_idx_domain ON {{prefix}}agent_tool_calls (domain_id);
CREATE INDEX {{prefix}}lupo_agent_tool_calls_idx_faucet ON {{prefix}}agent_tool_calls (faucet_id);
CREATE INDEX {{prefix}}lupo_agent_tool_calls_idx_message ON {{prefix}}agent_tool_calls (message_id);
CREATE INDEX {{prefix}}lupo_agent_tool_calls_idx_model ON {{prefix}}agent_tool_calls (model_name);
CREATE INDEX {{prefix}}lupo_agent_tool_calls_idx_parent ON {{prefix}}agent_tool_calls (parent_call_id);
CREATE INDEX {{prefix}}lupo_agent_tool_calls_idx_provider ON {{prefix}}agent_tool_calls (provider);
CREATE INDEX {{prefix}}lupo_agent_tool_calls_idx_thread ON {{prefix}}agent_tool_calls (thread_id);

-- lupo_agent_tools
CREATE TABLE {{prefix}}agent_tools (
  `agent_tool_id` bigint NOT NULL,
  `agent_id` bigint NOT NULL,
  `tool_id_key` varchar(200) NOT NULL,
  `tool_name` varchar(100) NOT NULL,
  `tool_category` varchar(64),
  `tool_description` text,
  `input_schema_json` json,
  `output_schema_json` json,
  `constraints_json` json,
  `is_advisory_only` tinyint NOT NULL DEFAULT 0,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL DEFAULT 0,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  PRIMARY KEY (agent_tool_id)
);

CREATE INDEX {{prefix}}lupo_agent_tools_idx_advisory ON {{prefix}}agent_tools (is_advisory_only);
CREATE INDEX {{prefix}}lupo_agent_tools_idx_agent ON {{prefix}}agent_tools (agent_id);
CREATE INDEX {{prefix}}lupo_agent_tools_idx_category ON {{prefix}}agent_tools (tool_category);
CREATE INDEX {{prefix}}lupo_agent_tools_idx_deleted ON {{prefix}}agent_tools (is_deleted);
CREATE INDEX {{prefix}}lupo_agent_tools_idx_key ON {{prefix}}agent_tools (tool_id_key);
CREATE UNIQUE INDEX {{prefix}}lupo_agent_tools_unq ON {{prefix}}agent_tools (agent_id, tool_id_key);

-- lupo_aliases
CREATE TABLE {{prefix}}aliases (
  `alias_id` bigint NOT NULL,
  `slug` varchar(255) NOT NULL,
  `alias` varchar(255) NOT NULL,
  `alias_type` varchar(50) DEFAULT 'semantic',
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  PRIMARY KEY (alias_id)
);

CREATE INDEX {{prefix}}lupo_aliases_idx_slug ON {{prefix}}aliases (slug);
CREATE UNIQUE INDEX {{prefix}}lupo_aliases_uniq_alias ON {{prefix}}aliases (alias);

-- lupo_analytics_campaign_vars
CREATE TABLE {{prefix}}analytics_campaign_vars (
  `campaign_var_id` bigint NOT NULL,
  `period` varchar(64) NOT NULL,
  `date_ymd` bigint,
  `yearmonth` int,
  `year` int,
  `campaign_key` varchar(255) NOT NULL,
  `campaign_value` varchar(500),
  `metadata_json` json,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  PRIMARY KEY (campaign_var_id)
);


-- lupo_anubis_events
CREATE TABLE {{prefix}}anubis_events (
  `anubis_event_id` bigint NOT NULL,
  `event_type` varchar(64) NOT NULL,
  `table_name` varchar(255) NOT NULL,
  `old_id` bigint NOT NULL,
  `new_id` bigint NOT NULL,
  `created_ymdhis` bigint NOT NULL,
  `operator_actor_id` bigint NOT NULL,
  `details_json` text NOT NULL,
  PRIMARY KEY (anubis_event_id)
);


-- lupo_anubis_log
CREATE TABLE {{prefix}}anubis_log (
  `anubis_log_id` bigint NOT NULL,
  `event_type` varchar(64) NOT NULL,
  `severity` varchar(20) NOT NULL DEFAULT 'normal',
  `source_table` varchar(64),
  `source_id` bigint,
  `file_path_from_root` varchar(255),
  `context_json` json,
  `status` varchar(64) NOT NULL DEFAULT 'Pending',
  `assigned_to_actor_id` bigint NOT NULL DEFAULT 19,
  `resolution_ymdhis` bigint,
  `resolution_summary` text,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL DEFAULT 0,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  PRIMARY KEY (anubis_log_id)
);

CREATE INDEX {{prefix}}lupo_anubis_log_idx_assigned_actor ON {{prefix}}anubis_log (assigned_to_actor_id);
CREATE INDEX {{prefix}}lupo_anubis_log_idx_created ON {{prefix}}anubis_log (created_ymdhis);
CREATE INDEX {{prefix}}lupo_anubis_log_idx_event_type ON {{prefix}}anubis_log (event_type);
CREATE INDEX {{prefix}}lupo_anubis_log_idx_file_path ON {{prefix}}anubis_log (file_path_from_root);
CREATE INDEX {{prefix}}lupo_anubis_log_idx_source_id ON {{prefix}}anubis_log (source_id);
CREATE INDEX {{prefix}}lupo_anubis_log_idx_source_table ON {{prefix}}anubis_log (source_table);
CREATE INDEX {{prefix}}lupo_anubis_log_idx_status ON {{prefix}}anubis_log (status);

-- lupo_anubis_operations
CREATE TABLE {{prefix}}anubis_operations (
  `operation_id` bigint NOT NULL,
  `operation_type` varchar(64) NOT NULL,
  `target_type` varchar(64) NOT NULL,
  `target_id` bigint NOT NULL,
  `channel_id` bigint NOT NULL DEFAULT 42,
  `actor_id` bigint NOT NULL,
  `faucet_id` bigint,
  `details_json` text,
  `created_ymdhis` bigint NOT NULL,
  PRIMARY KEY (operation_id)
);

CREATE INDEX {{prefix}}lupo_anubis_operations_idx_created ON {{prefix}}anubis_operations (created_ymdhis);
CREATE INDEX {{prefix}}lupo_anubis_operations_idx_target ON {{prefix}}anubis_operations (target_type, target_id);
CREATE INDEX {{prefix}}lupo_anubis_operations_idx_type ON {{prefix}}anubis_operations (operation_type);

-- lupo_anubis_processing_log
CREATE TABLE {{prefix}}anubis_processing_log (
  `log_id` bigint NOT NULL,
  `queue_id` bigint NOT NULL,
  `file_path` varchar(512) NOT NULL,
  `action` varchar(64) NOT NULL,
  `details` text,
  `actor_id` bigint,
  `created_utc` bigint NOT NULL,
  PRIMARY KEY (log_id)
);

CREATE INDEX {{prefix}}lupo_anubis_processing_log_idx_created ON {{prefix}}anubis_processing_log (created_utc);
CREATE INDEX {{prefix}}lupo_anubis_processing_log_idx_queue ON {{prefix}}anubis_processing_log (queue_id);

-- lupo_anubis_quarantine
CREATE TABLE {{prefix}}anubis_quarantine (
  `quarantine_id` bigint NOT NULL,
  `queue_id` bigint NOT NULL,
  `file_path` varchar(512) NOT NULL,
  `file_hash` varchar(64),
  `file_content` longtext,
  `quarantine_path` varchar(512) NOT NULL,
  `reason` varchar(255) NOT NULL,
  `quarantined_utc` bigint NOT NULL,
  `expires_utc` bigint,
  `reviewed_by_actor_id` bigint,
  `reviewed_utc` bigint,
  `resolution` varchar(64),
  `is_deleted` tinyint DEFAULT 0,
  PRIMARY KEY (quarantine_id)
);

CREATE INDEX {{prefix}}lupo_anubis_quarantine_idx_expires ON {{prefix}}anubis_quarantine (expires_utc);
CREATE INDEX {{prefix}}lupo_anubis_quarantine_idx_queue ON {{prefix}}anubis_quarantine (queue_id);

-- lupo_anubis_queue
CREATE TABLE {{prefix}}anubis_queue (
  `queue_id` bigint NOT NULL,
  `file_path` varchar(512) NOT NULL,
  `file_hash` varchar(64),
  `file_content` longtext,
  `detected_utc` bigint NOT NULL,
  `priority` tinyint DEFAULT 5,
  `status` varchar(32) DEFAULT 'pending',
  `detection_method` varchar(64),
  `header_snapshot` text,
  `error_message` text,
  `attempts` tinyint DEFAULT 0,
  `last_attempt_utc` bigint,
  `assigned_to_actor_id` bigint,
  `filesystem_copy_exists` tinyint DEFAULT 1,
  `filesystem_backup_path` varchar(512),
  `created_utc` bigint NOT NULL,
  `updated_utc` bigint NOT NULL,
  `is_deleted` tinyint DEFAULT 0,
  PRIMARY KEY (queue_id)
);

CREATE INDEX {{prefix}}lupo_anubis_queue_idx_detected ON {{prefix}}anubis_queue (detected_utc);
CREATE INDEX {{prefix}}lupo_anubis_queue_idx_file_path ON {{prefix}}anubis_queue (file_path);
CREATE INDEX {{prefix}}lupo_anubis_queue_idx_status_priority ON {{prefix}}anubis_queue (status, priority);
CREATE UNIQUE INDEX {{prefix}}lupo_anubis_queue_uniq_file_hash ON {{prefix}}anubis_queue (file_hash);

-- lupo_anubis_recovery_attempts
CREATE TABLE {{prefix}}anubis_recovery_attempts (
  `attempt_id` bigint NOT NULL,
  `queue_id` bigint NOT NULL,
  `attempt_number` tinyint NOT NULL,
  `attempt_utc` bigint NOT NULL,
  `strategy` varchar(64),
  `success` tinyint DEFAULT 0,
  `generated_header` text,
  `error_details` text,
  `recovered_file_path` varchar(512),
  PRIMARY KEY (attempt_id)
);

CREATE INDEX {{prefix}}lupo_anubis_recovery_attempts_idx_queue_attempt ON {{prefix}}anubis_recovery_attempts (queue_id, attempt_number);

-- lupo_anubis_redirects
CREATE TABLE {{prefix}}anubis_redirects (
  `anubis_redirect_id` bigint NOT NULL,
  `table_name` varchar(255) NOT NULL,
  `old_id` bigint NOT NULL,
  `new_id` bigint NOT NULL,
  `created_ymdhis` bigint NOT NULL,
  `updated_ymdhis` bigint NOT NULL,
  `agent` varchar(255) NOT NULL,
  PRIMARY KEY (anubis_redirect_id)
);


-- lupo_api_clients
CREATE TABLE {{prefix}}api_clients (
  `api_client_id` bigint NOT NULL,
  `actor_id` bigint NOT NULL DEFAULT 0,
  `client_key` varchar(255) NOT NULL,
  `client_secret` varchar(255) NOT NULL,
  `client_name` varchar(150) NOT NULL,
  `client_description` text,
  `scopes` text,
  `is_active` tinyint NOT NULL DEFAULT 1,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL,
  `expires_ymdhis` bigint,
  PRIMARY KEY (api_client_id)
);

CREATE INDEX {{prefix}}lupo_api_clients_idx_active ON {{prefix}}api_clients (is_active);
CREATE INDEX {{prefix}}lupo_api_clients_idx_actor ON {{prefix}}api_clients (actor_id);
CREATE INDEX {{prefix}}lupo_api_clients_idx_expires ON {{prefix}}api_clients (expires_ymdhis);
CREATE UNIQUE INDEX {{prefix}}lupo_api_clients_uq_client_key ON {{prefix}}api_clients (client_key);

-- lupo_api_rate_limits
CREATE TABLE {{prefix}}api_rate_limits (
  `api_rate_limit_id` bigint NOT NULL,
  `domain_id` bigint NOT NULL DEFAULT 1,
  `api_token_id` bigint NOT NULL DEFAULT 0,
  `actor_id` bigint NOT NULL DEFAULT 0,
  `ip_address` varchar(45),
  `endpoint` varchar(255),
  `window_ymdhis` bigint NOT NULL,
  `request_count` int NOT NULL DEFAULT 0,
  `limit_value` int NOT NULL DEFAULT 0,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL,
  PRIMARY KEY (api_rate_limit_id)
);

CREATE INDEX {{prefix}}lupo_api_rate_limits_idx_actor_window ON {{prefix}}api_rate_limits (actor_id, window_ymdhis);
CREATE INDEX {{prefix}}lupo_api_rate_limits_idx_domain_window ON {{prefix}}api_rate_limits (domain_id, window_ymdhis);
CREATE INDEX {{prefix}}lupo_api_rate_limits_idx_endpoint ON {{prefix}}api_rate_limits (endpoint);
CREATE INDEX {{prefix}}lupo_api_rate_limits_idx_ip_window ON {{prefix}}api_rate_limits (ip_address, window_ymdhis);
CREATE INDEX {{prefix}}lupo_api_rate_limits_idx_token_window ON {{prefix}}api_rate_limits (api_token_id, window_ymdhis);

-- lupo_api_token_logs
CREATE TABLE {{prefix}}api_token_logs (
  `api_token_log_id` bigint NOT NULL,
  `domain_id` bigint NOT NULL DEFAULT 1,
  `api_token_id` bigint NOT NULL,
  `actor_id` bigint NOT NULL DEFAULT 0,
  `endpoint` varchar(255) NOT NULL,
  `http_method` varchar(10) NOT NULL,
  `ip_address` varchar(45),
  `user_agent` varchar(255),
  `status_code` int NOT NULL,
  `request_ymdhis` bigint NOT NULL,
  `duration_ms` int,
  PRIMARY KEY (api_token_log_id)
);

CREATE INDEX {{prefix}}lupo_api_token_logs_idx_actor ON {{prefix}}api_token_logs (actor_id);
CREATE INDEX {{prefix}}lupo_api_token_logs_idx_domain_time ON {{prefix}}api_token_logs (domain_id, request_ymdhis);
CREATE INDEX {{prefix}}lupo_api_token_logs_idx_endpoint ON {{prefix}}api_token_logs (endpoint);
CREATE INDEX {{prefix}}lupo_api_token_logs_idx_status ON {{prefix}}api_token_logs (status_code);
CREATE INDEX {{prefix}}lupo_api_token_logs_idx_token ON {{prefix}}api_token_logs (api_token_id);

-- lupo_api_tokens
CREATE TABLE {{prefix}}api_tokens (
  `api_token_id` bigint NOT NULL,
  `domain_id` bigint NOT NULL DEFAULT 1,
  `actor_id` bigint NOT NULL DEFAULT 0,
  `token_key` varchar(255) NOT NULL,
  `token_label` varchar(150),
  `scopes` text,
  `is_active` tinyint NOT NULL DEFAULT 1,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL DEFAULT 0,
  `expires_ymdhis` bigint,
  `last_used_ymdhis` bigint,
  `created_ip` varchar(45),
  `last_used_ip` varchar(45),
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  `notes` text,
  PRIMARY KEY (api_token_id)
);

CREATE INDEX {{prefix}}lupo_api_tokens_idx_active ON {{prefix}}api_tokens (is_active);
CREATE INDEX {{prefix}}lupo_api_tokens_idx_actor ON {{prefix}}api_tokens (actor_id);
CREATE INDEX {{prefix}}lupo_api_tokens_idx_actor_active ON {{prefix}}api_tokens (actor_id, is_active);
CREATE INDEX {{prefix}}lupo_api_tokens_idx_domain ON {{prefix}}api_tokens (domain_id);
CREATE INDEX {{prefix}}lupo_api_tokens_idx_expires ON {{prefix}}api_tokens (expires_ymdhis);
CREATE INDEX {{prefix}}lupo_api_tokens_idx_last_used ON {{prefix}}api_tokens (last_used_ymdhis);
CREATE UNIQUE INDEX {{prefix}}lupo_api_tokens_uq_token_key ON {{prefix}}api_tokens (token_key);

-- lupo_api_webhooks
CREATE TABLE {{prefix}}api_webhooks (
  `api_webhook_id` bigint NOT NULL,
  `domain_id` bigint NOT NULL DEFAULT 1,
  `actor_id` bigint NOT NULL DEFAULT 0,
  `module_id` bigint NOT NULL DEFAULT 0,
  `endpoint_url` varchar(500) NOT NULL,
  `secret_key` varchar(255) NOT NULL,
  `event_types` text NOT NULL,
  `is_active` tinyint NOT NULL DEFAULT 1,
  `max_retries` int NOT NULL DEFAULT 5,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL,
  `expires_ymdhis` bigint,
  `notes` text,
  PRIMARY KEY (api_webhook_id)
);

CREATE INDEX {{prefix}}lupo_api_webhooks_idx_active ON {{prefix}}api_webhooks (is_active);
CREATE INDEX {{prefix}}lupo_api_webhooks_idx_actor ON {{prefix}}api_webhooks (actor_id);
CREATE INDEX {{prefix}}lupo_api_webhooks_idx_domain ON {{prefix}}api_webhooks (domain_id);
CREATE INDEX {{prefix}}lupo_api_webhooks_idx_expires ON {{prefix}}api_webhooks (expires_ymdhis);
CREATE INDEX {{prefix}}lupo_api_webhooks_idx_module ON {{prefix}}api_webhooks (module_id);

-- lupo_atoms
CREATE TABLE {{prefix}}atoms (
  `atom_id` bigint NOT NULL,
  `atom_name` varchar(255) NOT NULL,
  `context_id` bigint NOT NULL,
  `is_authoritative` tinyint NOT NULL DEFAULT 0,
  `value_json` json,
  `summary` text,
  `tags` varchar(255),
  `created_ymd` bigint NOT NULL DEFAULT 0,
  `updated_ymd` bigint NOT NULL DEFAULT 0,
  PRIMARY KEY (atom_id)
);

CREATE INDEX {{prefix}}lupo_atoms_idx_atom_context ON {{prefix}}atoms (atom_name, context_id);
CREATE INDEX {{prefix}}lupo_atoms_idx_atom_name ON {{prefix}}atoms (atom_name);
CREATE INDEX {{prefix}}lupo_atoms_idx_authoritative ON {{prefix}}atoms (is_authoritative);
CREATE INDEX {{prefix}}lupo_atoms_idx_context_id ON {{prefix}}atoms (context_id);

-- lupo_audit_log
CREATE TABLE {{prefix}}audit_log (
  `audit_log_id` bigint NOT NULL,
  `channel_id` bigint NOT NULL,
  `entity_type` varchar(32) NOT NULL,
  `entity_id` bigint NOT NULL,
  `event_type` varchar(100) NOT NULL,
  `table_name` varchar(100),
  `table_id` bigint,
  `payload_json` text,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  PRIMARY KEY (audit_log_id)
);

CREATE INDEX {{prefix}}lupo_audit_log_idx_entity ON {{prefix}}audit_log (entity_type, entity_id);
CREATE INDEX {{prefix}}lupo_audit_log_idx_event ON {{prefix}}audit_log (event_type);
CREATE INDEX {{prefix}}lupo_audit_log_idx_table ON {{prefix}}audit_log (table_name, table_id);

-- lupo_auth_audit_log
CREATE TABLE {{prefix}}auth_audit_log (
  `auth_audit_log_id` bigint NOT NULL,
  `user_id` bigint,
  `crafty_operator_id` int,
  `event_type` varchar(50) NOT NULL,
  `system_context` varchar(50) NOT NULL,
  `ip_address` varchar(45),
  `user_agent` text,
  `event_data` json,
  `success` tinyint NOT NULL DEFAULT 1,
  `error_message` text,
  `created_at` bigint,
  `updated_at` bigint,
  PRIMARY KEY (auth_audit_log_id)
);

CREATE INDEX {{prefix}}lupo_auth_audit_log_idx_crafty_operator_id ON {{prefix}}auth_audit_log (crafty_operator_id);
CREATE INDEX {{prefix}}lupo_auth_audit_log_idx_created_at ON {{prefix}}auth_audit_log (created_at);
CREATE INDEX {{prefix}}lupo_auth_audit_log_idx_event_type ON {{prefix}}auth_audit_log (event_type);
CREATE INDEX {{prefix}}lupo_auth_audit_log_idx_success ON {{prefix}}auth_audit_log (success);
CREATE INDEX {{prefix}}lupo_auth_audit_log_idx_system_context ON {{prefix}}auth_audit_log (system_context);
CREATE INDEX {{prefix}}lupo_auth_audit_log_idx_user_id ON {{prefix}}auth_audit_log (user_id);

-- lupo_auth_providers
CREATE TABLE {{prefix}}auth_providers (
  `auth_provider_id` bigint NOT NULL,
  `provider_name` varchar(50) NOT NULL,
  `client_id` varchar(255) NOT NULL,
  `client_secret` text NOT NULL,
  `scopes` text,
  `authorization_endpoint` varchar(2000) NOT NULL,
  `token_endpoint` varchar(2000) NOT NULL,
  `userinfo_endpoint` varchar(2000),
  `jwks_uri` varchar(2000),
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL,
  `is_active` tinyint NOT NULL DEFAULT 1,
  PRIMARY KEY (auth_provider_id)
);

CREATE UNIQUE INDEX {{prefix}}lupo_auth_providers_unique_provider_name ON {{prefix}}auth_providers (provider_name);

-- lupo_auth_rate_limits
CREATE TABLE {{prefix}}auth_rate_limits (
  `auth_rate_limit_id` bigint NOT NULL,
  `identifier` varchar(255) NOT NULL,
  `attempt_type` varchar(50),
  `attempted_ymdhis` bigint NOT NULL,
  PRIMARY KEY (auth_rate_limit_id)
);

CREATE INDEX {{prefix}}idx_attempt_time ON {{prefix}}auth_rate_limits (attempted_ymdhis);
CREATE INDEX {{prefix}}idx_identifier ON {{prefix}}auth_rate_limits (identifier);

-- lupo_auth_user_departments
CREATE TABLE {{prefix}}auth_user_departments (
  `auth_user_department_id` bigint NOT NULL,
  `auth_user_id` bigint NOT NULL,
  `department_id` bigint NOT NULL,
  `is_primary` tinyint NOT NULL DEFAULT 0,
  `role_key` varchar(64),
  `title` varchar(64),
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  PRIMARY KEY (auth_user_department_id)
);

CREATE INDEX {{prefix}}lupo_auth_user_departments_idx_auth_user ON {{prefix}}auth_user_departments (auth_user_id);
CREATE INDEX {{prefix}}lupo_auth_user_departments_idx_department ON {{prefix}}auth_user_departments (department_id);
CREATE INDEX {{prefix}}lupo_auth_user_departments_idx_primary ON {{prefix}}auth_user_departments (auth_user_id, is_primary);

-- lupo_auth_users
CREATE TABLE {{prefix}}auth_users (
  `auth_user_id` bigint NOT NULL,
  `username` varchar(255) NOT NULL,
  `display_name` varchar(42) NOT NULL,
  `email` varchar(100),
  `password_hash` varchar(255),
  `auth_provider` varchar(50),
  `provider_id` varchar(255),
  `profile_image_url` varchar(2000),
  `last_login_ymdhis` bigint,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL,
  `is_active` tinyint NOT NULL DEFAULT 1,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  `two_factor_secret` varchar(255),
  `two_factor_enabled` tinyint NOT NULL DEFAULT 0,
  `two_factor_backup_codes` text,
  `otp_code_hash` varchar(255) NOT NULL DEFAULT '',
  `otp_issued_ymdhis` bigint NOT NULL DEFAULT 0,
  `otp_attempts` tinyint NOT NULL DEFAULT 0,
  `timezone_offset` decimal(4,2) DEFAULT 0.00,
  `timezone_name` varchar(100) DEFAULT 'UTC',
  PRIMARY KEY (auth_user_id)
);

CREATE INDEX {{prefix}}lupo_auth_users_idx_created_ymdhis ON {{prefix}}auth_users (created_ymdhis);
CREATE INDEX {{prefix}}lupo_auth_users_idx_email ON {{prefix}}auth_users (email);
CREATE INDEX {{prefix}}lupo_auth_users_idx_is_active ON {{prefix}}auth_users (is_active);
CREATE INDEX {{prefix}}lupo_auth_users_idx_is_deleted ON {{prefix}}auth_users (is_deleted);
CREATE INDEX {{prefix}}lupo_auth_users_idx_updated_ymdhis ON {{prefix}}auth_users (updated_ymdhis);
CREATE UNIQUE INDEX {{prefix}}lupo_auth_users_unique_provider_user ON {{prefix}}auth_users (auth_provider, provider_id);
CREATE UNIQUE INDEX {{prefix}}lupo_auth_users_unique_username ON {{prefix}}auth_users (username);

-- lupo_banned_actors
CREATE TABLE {{prefix}}banned_actors (
  `banned_actor_id` bigint NOT NULL,
  `actor_id` bigint NOT NULL,
  `actor_name` varchar(64),
  `ip_address` varchar(45),
  `reason` varchar(500) NOT NULL,
  `banned_ymdhis` bigint NOT NULL,
  `banned_by_actor_id` bigint,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  PRIMARY KEY (banned_actor_id)
);

CREATE INDEX {{prefix}}lupo_banned_actors_idx_actor_id ON {{prefix}}banned_actors (actor_id);
CREATE INDEX {{prefix}}lupo_banned_actors_idx_actor_name ON {{prefix}}banned_actors (actor_name);
CREATE INDEX {{prefix}}lupo_banned_actors_idx_ip_address ON {{prefix}}banned_actors (ip_address);
CREATE INDEX {{prefix}}lupo_banned_actors_idx_is_deleted ON {{prefix}}banned_actors (is_deleted);

-- lupo_bans_log
CREATE TABLE {{prefix}}bans_log (
  `bans_log_id` bigint NOT NULL,
  `actor_id` bigint NOT NULL,
  `uri` varchar(1024) NOT NULL DEFAULT '',
  `resolved_uri` varchar(1024) NOT NULL DEFAULT '',
  `ban_scope` varchar(64) NOT NULL DEFAULT 'router',
  `banned_ymdhis` bigint NOT NULL,
  `user_agent` varchar(500),
  `ip_address` varchar(45),
  PRIMARY KEY (bans_log_id)
);

CREATE INDEX {{prefix}}lupo_bans_log_idx_actor_id ON {{prefix}}bans_log (actor_id);
CREATE INDEX {{prefix}}lupo_bans_log_idx_ban_scope ON {{prefix}}bans_log (ban_scope);
CREATE INDEX {{prefix}}lupo_bans_log_idx_banned_ymdhis ON {{prefix}}bans_log (banned_ymdhis);

-- lupo_capability_usage
CREATE TABLE {{prefix}}capability_usage (
  `usage_id` bigint NOT NULL,
  `actor_id` bigint NOT NULL,
  `capability` varchar(100) NOT NULL,
  `usage_count` bigint DEFAULT 0,
  `success_rate` float DEFAULT 1,
  `avg_response_time_ms` int DEFAULT 0,
  `last_used_ymdhis` bigint DEFAULT 0,
  `performance_metrics` json,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint
);

CREATE INDEX {{prefix}}lupo_capability_usage_idx_actor_capability ON {{prefix}}capability_usage (actor_id, capability);
CREATE INDEX {{prefix}}lupo_capability_usage_idx_capability ON {{prefix}}capability_usage (capability);
CREATE INDEX {{prefix}}lupo_capability_usage_idx_is_deleted ON {{prefix}}capability_usage (is_deleted);
CREATE INDEX {{prefix}}lupo_capability_usage_idx_last_used ON {{prefix}}capability_usage (last_used_ymdhis);

-- lupo_channel_content
CREATE TABLE {{prefix}}channel_content (
  `channel_content_id` bigint NOT NULL,
  `channel_id` bigint NOT NULL,
  `federation_node_id` bigint NOT NULL,
  `file_path` varchar(500) NOT NULL,
  `web_path` varchar(500) NOT NULL,
  `metadata_json` json,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL DEFAULT 0,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  PRIMARY KEY (channel_content_id)
);

CREATE INDEX {{prefix}}lupo_channel_content_idx_channel ON {{prefix}}channel_content (channel_id);
CREATE INDEX {{prefix}}lupo_channel_content_idx_created ON {{prefix}}channel_content (created_ymdhis);
CREATE INDEX {{prefix}}lupo_channel_content_idx_federation_node ON {{prefix}}channel_content (federation_node_id);
CREATE INDEX {{prefix}}lupo_channel_content_idx_file_path ON {{prefix}}channel_content (file_path);
CREATE INDEX {{prefix}}lupo_channel_content_idx_is_deleted ON {{prefix}}channel_content (is_deleted);
CREATE INDEX {{prefix}}lupo_channel_content_idx_updated ON {{prefix}}channel_content (updated_ymdhis);
CREATE INDEX {{prefix}}lupo_channel_content_idx_web_path ON {{prefix}}channel_content (web_path);

-- lupo_channel_departments
CREATE TABLE {{prefix}}channel_departments (
  `channel_department_id` bigint NOT NULL,
  `channel_id` bigint NOT NULL,
  `department_id` bigint NOT NULL,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  PRIMARY KEY (channel_department_id)
);

CREATE INDEX {{prefix}}lupo_channel_departments_idx_channel ON {{prefix}}channel_departments (channel_id);
CREATE INDEX {{prefix}}lupo_channel_departments_idx_department ON {{prefix}}channel_departments (department_id);
CREATE UNIQUE INDEX {{prefix}}lupo_channel_departments_unq_channel_department ON {{prefix}}channel_departments (channel_id, department_id);

-- lupo_channel_escalation_rules
CREATE TABLE {{prefix}}channel_escalation_rules (
  `rule_id` bigint NOT NULL,
  `channel_id` bigint NOT NULL,
  `rule_name` varchar(255) NOT NULL,
  `rule_description` text,
  `rule_type` varchar(64) NOT NULL,
  `rule_config_json` json,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  PRIMARY KEY (rule_id)
);

CREATE INDEX {{prefix}}lupo_channel_escalation_rules_idx_channel_id ON {{prefix}}channel_escalation_rules (channel_id);
CREATE INDEX {{prefix}}lupo_channel_escalation_rules_idx_rule_type ON {{prefix}}channel_escalation_rules (rule_type);

-- lupo_channel_escalations
CREATE TABLE {{prefix}}channel_escalations (
  `escalation_id` bigint NOT NULL,
  `channel_id` bigint NOT NULL,
  `thread_id` bigint,
  `actor_id` bigint,
  `escalated_to_actor_id` bigint,
  `escalation_reason` varchar(512),
  `metadata_json` json,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  PRIMARY KEY (escalation_id)
);

CREATE INDEX {{prefix}}lupo_channel_escalations_idx_actor_id ON {{prefix}}channel_escalations (actor_id);
CREATE INDEX {{prefix}}lupo_channel_escalations_idx_channel_id ON {{prefix}}channel_escalations (channel_id);
CREATE INDEX {{prefix}}lupo_channel_escalations_idx_escalated_to_actor_id ON {{prefix}}channel_escalations (escalated_to_actor_id);
CREATE INDEX {{prefix}}lupo_channel_escalations_idx_thread_id ON {{prefix}}channel_escalations (thread_id);

-- lupo_channel_files
CREATE TABLE {{prefix}}channel_files (
  `file_id` bigint NOT NULL,
  `channel_id` bigint NOT NULL,
  `file_type` varchar(50) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_path` varchar(500) NOT NULL,
  `file_hash` varchar(64) NOT NULL,
  `file_size` bigint NOT NULL,
  `mime_type` varchar(100),
  `upload_ymdhis` bigint NOT NULL,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  `migrated_from_directory` varchar(255),
  PRIMARY KEY (file_id)
);

CREATE INDEX {{prefix}}lupo_channel_files_idx_channel_id ON {{prefix}}channel_files (channel_id);
CREATE INDEX {{prefix}}lupo_channel_files_idx_file_hash ON {{prefix}}channel_files (file_hash);
CREATE INDEX {{prefix}}lupo_channel_files_idx_is_deleted ON {{prefix}}channel_files (is_deleted);
CREATE INDEX {{prefix}}lupo_channel_files_idx_upload_ymdhis ON {{prefix}}channel_files (upload_ymdhis);

-- lupo_channel_state
CREATE TABLE {{prefix}}channel_state (
  `channel_state_id` bigint NOT NULL,
  `channel_id` bigint NOT NULL,
  `active_actors_json` json,
  `speaker_actors_json` json,
  `observer_actors_json` json,
  `layers_enabled_json` json,
  `operational_mode` varchar(32),
  `emotional_state_json` json,
  `mood_framework` varchar(32) NOT NULL DEFAULT 'western_analytical',
  `recent_topics_json` json,
  `semantic_weight` float DEFAULT 0,
  `trend_score` float DEFAULT 0,
  `last_activity_ymdhis` bigint,
  `context_vector` blob,
  `routing_rules` varchar(32),
  `edge_visibility` varchar(32),
  `retention_policy` varchar(32),
  `decay_policy` varchar(32),
  `archive_flag` tinyint DEFAULT 0,
  `metadata_json` json,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL,
  PRIMARY KEY (channel_state_id)
);

CREATE INDEX {{prefix}}lupo_channel_state_idx_channel_id ON {{prefix}}channel_state (channel_id);

-- lupo_channel_typing_previews
CREATE TABLE {{prefix}}channel_typing_previews (
  `channel_typing_preview_id` bigint NOT NULL,
  `channel_id` bigint NOT NULL,
  `dialog_thread_id` bigint NOT NULL,
  `from_actor_id` bigint NOT NULL DEFAULT 0,
  `actor_name` varchar(255) NOT NULL DEFAULT '',
  `preview_text` varchar(1000) NOT NULL DEFAULT '',
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint NOT NULL DEFAULT 0,
  PRIMARY KEY (channel_typing_preview_id)
);

CREATE INDEX {{prefix}}lupo_channel_typing_idx_channel_updated ON {{prefix}}channel_typing_previews (channel_id, updated_ymdhis);
CREATE INDEX {{prefix}}lupo_channel_typing_idx_deleted ON {{prefix}}channel_typing_previews (is_deleted);
CREATE UNIQUE INDEX {{prefix}}lupo_channel_typing_uq_channel_thread ON {{prefix}}channel_typing_previews (channel_id, dialog_thread_id);

-- lupo_channels
CREATE TABLE {{prefix}}channels (
  `channel_id` bigint NOT NULL,
  `federation_node_id` bigint NOT NULL,
  `created_by_actor_id` bigint NOT NULL,
  `default_actor_id` bigint NOT NULL DEFAULT 1,
  `department_id` bigint NOT NULL DEFAULT 1,
  `channel_key` varchar(64) NOT NULL,
  `channel_slug` varchar(32) NOT NULL DEFAULT 'channel_key',
  `channel_type` varchar(32) NOT NULL DEFAULT 'chat_room',
  `language` varchar(16) NOT NULL DEFAULT 'en',
  `channel_name` varchar(255) NOT NULL,
  `description` text,
  `website_link` varchar(512),
  `metadata_json` text,
  `channel_config` text,
  `status_flag` tinyint NOT NULL DEFAULT 1,
  `end_ymdhis` bigint,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  `aal_metadata_json` json,
  `fleet_composition_json` json,
  `awareness_version` varchar(20) DEFAULT '3.0.0',
  `channel_number` int,
  `parent_channel_id` bigint,
  `project_id` bigint,
  `is_kernel` tinyint NOT NULL DEFAULT 0,
  `boot_sequence_order` int,
  `visibility_status` varchar(32) NOT NULL DEFAULT 'active',
  `owner_actor_id` bigint NOT NULL DEFAULT 1,
  `access_level` varchar(32) NOT NULL DEFAULT 'public',
  `last_activity_ymdhis` bigint NOT NULL DEFAULT 0,
  PRIMARY KEY (channel_id)
);

CREATE INDEX {{prefix}}lupo_channels_idx_access_level ON {{prefix}}channels (access_level);
CREATE INDEX {{prefix}}lupo_channels_idx_awareness_version ON {{prefix}}channels (awareness_version);
CREATE INDEX {{prefix}}lupo_channels_idx_channel_key ON {{prefix}}channels (channel_key);
CREATE INDEX {{prefix}}lupo_channels_idx_dates ON {{prefix}}channels (end_ymdhis);
CREATE INDEX {{prefix}}lupo_channels_idx_domain ON {{prefix}}channels (federation_node_id);
CREATE INDEX {{prefix}}lupo_channels_idx_last_activity ON {{prefix}}channels (last_activity_ymdhis);
CREATE INDEX {{prefix}}lupo_channels_idx_owner_actor_id ON {{prefix}}channels (owner_actor_id);
CREATE INDEX {{prefix}}lupo_channels_idx_project_id ON {{prefix}}channels (project_id);
CREATE INDEX {{prefix}}lupo_channels_idx_status ON {{prefix}}channels (status_flag);
CREATE INDEX {{prefix}}lupo_channels_idx_visibility_status ON {{prefix}}channels (visibility_status);
CREATE UNIQUE INDEX {{prefix}}lupo_channels_unq_channel_key_per_node ON {{prefix}}channels (channel_key, federation_node_id);

-- lupo_collection_links
CREATE TABLE {{prefix}}collection_links (
  `collection_link_id` bigint NOT NULL,
  `collection_id` bigint NOT NULL,
  `link_url` varchar(2000) NOT NULL,
  `link_label` varchar(255),
  `sort_order` int NOT NULL DEFAULT 0,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL DEFAULT 0,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  PRIMARY KEY (collection_link_id)
);

CREATE INDEX {{prefix}}lupo_collection_links_idx_collection ON {{prefix}}collection_links (collection_id);

-- lupo_collection_map
CREATE TABLE {{prefix}}collection_map (
  `collection_map_id` bigint NOT NULL,
  `collection_id` bigint NOT NULL,
  `object_type` varchar(64) NOT NULL,
  `object_id` bigint NOT NULL,
  `sort_order` int NOT NULL DEFAULT 0,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  PRIMARY KEY (collection_map_id)
);

CREATE INDEX {{prefix}}lupo_collection_map_idx_collection ON {{prefix}}collection_map (collection_id);
CREATE INDEX {{prefix}}lupo_collection_map_idx_object ON {{prefix}}collection_map (object_type, object_id);

-- lupo_collection_tab_map
CREATE TABLE {{prefix}}collection_tab_map (
  `collection_tab_map_id` bigint NOT NULL,
  `collection_tab_id` bigint NOT NULL,
  `federations_node_id` bigint NOT NULL,
  `item_type` varchar(20) NOT NULL,
  `item_id` bigint NOT NULL,
  `sort_order` int DEFAULT 0,
  `properties` text,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  PRIMARY KEY (collection_tab_map_id)
);

CREATE INDEX {{prefix}}lupo_collection_tab_map_idx_collection_tab ON {{prefix}}collection_tab_map (collection_tab_id);
CREATE INDEX {{prefix}}lupo_collection_tab_map_idx_created_ymdhis ON {{prefix}}collection_tab_map (created_ymdhis);
CREATE INDEX {{prefix}}lupo_collection_tab_map_idx_domain ON {{prefix}}collection_tab_map (federations_node_id);
CREATE INDEX {{prefix}}lupo_collection_tab_map_idx_is_deleted ON {{prefix}}collection_tab_map (is_deleted);
CREATE INDEX {{prefix}}lupo_collection_tab_map_idx_item ON {{prefix}}collection_tab_map (item_type, item_id);
CREATE INDEX {{prefix}}lupo_collection_tab_map_idx_sort_order ON {{prefix}}collection_tab_map (sort_order);
CREATE INDEX {{prefix}}lupo_collection_tab_map_idx_updated_ymdhis ON {{prefix}}collection_tab_map (updated_ymdhis);
CREATE UNIQUE INDEX {{prefix}}lupo_collection_tab_map_unique_item_in_tab ON {{prefix}}collection_tab_map (collection_tab_id, item_type, item_id);

-- lupo_collection_tab_paths
CREATE TABLE {{prefix}}collection_tab_paths (
  `collection_tab_path_id` bigint NOT NULL,
  `collection_id` bigint NOT NULL,
  `collection_tab_id` bigint NOT NULL,
  `path` varchar(500) NOT NULL,
  `depth` int NOT NULL,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  PRIMARY KEY (collection_tab_path_id)
);

CREATE INDEX {{prefix}}lupo_collection_tab_paths_idx_collection ON {{prefix}}collection_tab_paths (collection_id);
CREATE INDEX {{prefix}}lupo_collection_tab_paths_idx_collection_tab ON {{prefix}}collection_tab_paths (collection_tab_id);
CREATE INDEX {{prefix}}lupo_collection_tab_paths_idx_path ON {{prefix}}collection_tab_paths (path);
CREATE UNIQUE INDEX {{prefix}}lupo_collection_tab_paths_unique_tab_path ON {{prefix}}collection_tab_paths (collection_id, collection_tab_id, path);

-- lupo_collection_tabs
CREATE TABLE {{prefix}}collection_tabs (
  `collection_tab_id` bigint NOT NULL,
  `collection_tab_parent_id` bigint,
  `collection_id` bigint NOT NULL,
  `federations_node_id` bigint NOT NULL,
  `department_id` bigint,
  `actor_id` bigint,
  `sort_order` int DEFAULT 0,
  `name` varchar(255) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `color` char(6) DEFAULT '4caf50',
  `description` text,
  `is_hidden` tinyint NOT NULL DEFAULT 0,
  `visibility_rule` text,
  `tab_type` varchar(32),
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL,
  `is_active` tinyint NOT NULL DEFAULT 1,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  PRIMARY KEY (collection_tab_id)
);

CREATE INDEX {{prefix}}lupo_collection_tabs_idx_actor_id ON {{prefix}}collection_tabs (actor_id);
CREATE INDEX {{prefix}}lupo_collection_tabs_idx_collection_id ON {{prefix}}collection_tabs (collection_id);
CREATE INDEX {{prefix}}lupo_collection_tabs_idx_department ON {{prefix}}collection_tabs (department_id);
CREATE INDEX {{prefix}}lupo_collection_tabs_idx_is_active ON {{prefix}}collection_tabs (is_active);
CREATE INDEX {{prefix}}lupo_collection_tabs_idx_parent_tab_id ON {{prefix}}collection_tabs (collection_tab_parent_id);
CREATE INDEX {{prefix}}lupo_collection_tabs_idx_slug ON {{prefix}}collection_tabs (slug);

-- lupo_collections
CREATE TABLE {{prefix}}collections (
  `collection_id` bigint NOT NULL,
  `federation_node_id` bigint NOT NULL,
  `actor_id` bigint,
  `department_id` bigint,
  `name` varchar(255) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `color` char(6) DEFAULT '666666',
  `description` text,
  `sort_order` int DEFAULT 0,
  `properties` text,
  `published_ymdhis` bigint,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  `parent_id` bigint,
  `channel_id` bigint,
  `is_nav_menu` tinyint NOT NULL DEFAULT 0,
  `nav_icon` varchar(64),
  PRIMARY KEY (collection_id)
);

CREATE INDEX {{prefix}}lupo_collections_idx_actor ON {{prefix}}collections (actor_id);
CREATE INDEX {{prefix}}lupo_collections_idx_channel_id ON {{prefix}}collections (channel_id);
CREATE INDEX {{prefix}}lupo_collections_idx_created_ymdhis ON {{prefix}}collections (created_ymdhis);
CREATE INDEX {{prefix}}lupo_collections_idx_department ON {{prefix}}collections (department_id);
CREATE INDEX {{prefix}}lupo_collections_idx_domain ON {{prefix}}collections (federation_node_id);
CREATE INDEX {{prefix}}lupo_collections_idx_is_deleted ON {{prefix}}collections (is_deleted);
CREATE INDEX {{prefix}}lupo_collections_idx_is_nav_menu ON {{prefix}}collections (is_nav_menu);
CREATE INDEX {{prefix}}lupo_collections_idx_name ON {{prefix}}collections (name);
CREATE INDEX {{prefix}}lupo_collections_idx_sort_order ON {{prefix}}collections (sort_order);
CREATE INDEX {{prefix}}lupo_collections_idx_updated_ymdhis ON {{prefix}}collections (updated_ymdhis);
CREATE UNIQUE INDEX {{prefix}}lupo_collections_unique_collection_slug_domain ON {{prefix}}collections (federation_node_id, slug);

-- lupo_comments
CREATE TABLE {{prefix}}comments (
  `comment_id` bigint NOT NULL,
  `target_type` varchar(64) NOT NULL,
  `target_id` bigint NOT NULL,
  `channel_id` bigint NOT NULL DEFAULT 42,
  `actor_id` bigint NOT NULL,
  `faucet_id` bigint,
  `comment_text` text NOT NULL,
  `comment_type` varchar(64) NOT NULL DEFAULT 'comment',
  `parent_comment_id` bigint,
  `created_ymdhis` bigint NOT NULL,
  `updated_ymdhis` bigint NOT NULL,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  `metadata_json` json,
  PRIMARY KEY (comment_id)
);

CREATE INDEX {{prefix}}lupo_comments_idx_actor_id ON {{prefix}}comments (actor_id);
CREATE INDEX {{prefix}}lupo_comments_idx_channel_id ON {{prefix}}comments (channel_id);
CREATE INDEX {{prefix}}lupo_comments_idx_created_ymdhis ON {{prefix}}comments (created_ymdhis);
CREATE INDEX {{prefix}}lupo_comments_idx_faucet_id ON {{prefix}}comments (faucet_id);
CREATE INDEX {{prefix}}lupo_comments_idx_is_deleted ON {{prefix}}comments (is_deleted);
CREATE INDEX {{prefix}}lupo_comments_idx_parent_comment_id ON {{prefix}}comments (parent_comment_id);
CREATE INDEX {{prefix}}lupo_comments_idx_target ON {{prefix}}comments (target_type, target_id);

-- lupo_contents
CREATE TABLE {{prefix}}contents (
  `content_id` bigint NOT NULL,
  `content_parent_id` bigint,
  `federation_node_id` bigint DEFAULT 1,
  `federation_source_url` varchar(2000) COMMENT 'Canonical URL of content at source federation node',
  `channel_id` bigint COMMENT 'Channel this content belongs to (doctrine: content placement)',
  `department_id` bigint,
  `actor_id` bigint,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `custom_path` varchar(255),
  `description` text,
  `seo_keywords` varchar(500),
  `body` text,
  `content` text,
  `content_type` varchar(50) DEFAULT 'article',
  `format` varchar(20) DEFAULT 'markdown',
  `content_url` varchar(2000),
  `default_collection_id` bigint,
  `source_url` varchar(2000),
  `source_title` varchar(500),
  `is_template` tinyint NOT NULL DEFAULT 0,
  `status` varchar(64) DEFAULT 'draft',
  `visibility` varchar(64) DEFAULT 'public',
  `view_count` int DEFAULT 0,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `utc_cycle` varchar(64) NOT NULL,
  `triage_status` varchar(64) NOT NULL DEFAULT 'untriaged',
  `triage_notes` text,
  `updated_ymdhis` bigint NOT NULL,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `is_active` tinyint NOT NULL DEFAULT 1,
  `deleted_ymdhis` bigint,
  `content_sections` json,
  `version_number` int NOT NULL DEFAULT 1,
  `storage_type` varchar(16) NOT NULL DEFAULT 'database',
  `file_path_from_root` varchar(1024) COMMENT 'Canonical path from repo root. Set when storage_type=file_backed.',
  `file_last_modified_system_version` varchar(20) COMMENT 'FLIP: system version at last file edit',
  `file_last_modified_utc` bigint COMMENT 'FLIP: UTC last modified YYYYMMDDHHIISS',
  `tags` json,
  `dialog_notes` text,
  `atom_mappings` json COMMENT 'Consolidated from lupo_content_atom_map',
  `category_mappings` json COMMENT 'Consolidated from lupo_content_category_map',
  `content_events` json COMMENT 'Consolidated from lupo_content_events',
  `hashtags` json COMMENT 'Consolidated from lupo_content_hashtag',
  `inbound_links` json COMMENT 'Consolidated from lupo_content_inbound_links',
  `like_users` json COMMENT 'Consolidated from lupo_content_likes',
  `media_attachments` json COMMENT 'Consolidated from lupo_content_media',
  `question_mappings` json COMMENT 'Consolidated from lupo_content_question_map',
  `content_references` json COMMENT 'Consolidated from lupo_content_references',
  `revision_history` json COMMENT 'Consolidated from lupo_content_revisions',
  `share_users` json COMMENT 'Consolidated from lupo_content_shares',
  `tag_relationships` json COMMENT 'Consolidated from lupo_content_tag_relationships',
  `like_count` bigint NOT NULL DEFAULT 0 COMMENT 'FLARE-aligned engagement cache',
  `share_count` bigint NOT NULL DEFAULT 0 COMMENT 'FLARE-aligned engagement cache',
  `comment_count` bigint NOT NULL DEFAULT 0 COMMENT 'FLARE-aligned engagement cache',
  PRIMARY KEY (content_id)
);

CREATE INDEX {{prefix}}lupo_contents_idx_channel_id ON {{prefix}}contents (channel_id);
CREATE INDEX {{prefix}}lupo_contents_idx_content_parent ON {{prefix}}contents (content_parent_id);
CREATE INDEX {{prefix}}lupo_contents_idx_content_type ON {{prefix}}contents (content_type);
CREATE INDEX {{prefix}}lupo_contents_idx_created_ymdhis ON {{prefix}}contents (created_ymdhis);
CREATE UNIQUE INDEX {{prefix}}lupo_contents_idx_custom_path ON {{prefix}}contents (custom_path);
CREATE INDEX {{prefix}}lupo_contents_idx_department ON {{prefix}}contents (department_id);
CREATE INDEX {{prefix}}lupo_contents_idx_domain ON {{prefix}}contents (federation_node_id);
CREATE INDEX {{prefix}}lupo_contents_idx_engagement_counts ON {{prefix}}contents (like_count, share_count, comment_count);
CREATE INDEX {{prefix}}lupo_contents_idx_file_path_from_root ON {{prefix}}contents (file_path_from_root);
CREATE INDEX {{prefix}}lupo_contents_idx_is_active ON {{prefix}}contents (is_active);
CREATE INDEX {{prefix}}lupo_contents_idx_is_deleted ON {{prefix}}contents (is_deleted);
CREATE UNIQUE INDEX {{prefix}}lupo_contents_idx_slug_deleted ON {{prefix}}contents (slug, is_deleted);
CREATE INDEX {{prefix}}lupo_contents_idx_status ON {{prefix}}contents (status);
CREATE INDEX {{prefix}}lupo_contents_idx_updated_ymdhis ON {{prefix}}contents (updated_ymdhis);
CREATE INDEX {{prefix}}lupo_contents_idx_user ON {{prefix}}contents (actor_id);
CREATE INDEX {{prefix}}lupo_contents_idx_visibility ON {{prefix}}contents (visibility);
CREATE UNIQUE INDEX {{prefix}}lupo_contents_unique_content_slug_domain ON {{prefix}}contents (federation_node_id, slug);

-- lupo_crafty_syntax_auto_invite
CREATE TABLE {{prefix}}crafty_syntax_auto_invite (
  `crafty_syntax_auto_invite_id` bigint NOT NULL,
  `is_offline` tinyint NOT NULL DEFAULT 0,
  `is_active` tinyint NOT NULL DEFAULT 0,
  `department_id` bigint NOT NULL DEFAULT 0,
  `message` mediumtext,
  `page_url` varchar(500),
  `visits` int NOT NULL DEFAULT 0,
  `referrer_url` varchar(500),
  `invite_type` varchar(50),
  `trigger_seconds` int NOT NULL DEFAULT 0,
  `operator_user_id` bigint NOT NULL DEFAULT 0,
  `show_socialpane` tinyint NOT NULL DEFAULT 0,
  `exclude_mobile` tinyint NOT NULL DEFAULT 0,
  `only_mobile` tinyint NOT NULL DEFAULT 0,
  `created_ymdhis` bigint NOT NULL DEFAULT 20250101000000,
  `updated_ymdhis` bigint NOT NULL DEFAULT 20250101000000,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  PRIMARY KEY (crafty_syntax_auto_invite_id)
);

CREATE INDEX {{prefix}}lupo_crafty_syntax_auto_invite_idx_created ON {{prefix}}crafty_syntax_auto_invite (created_ymdhis);
CREATE INDEX {{prefix}}lupo_crafty_syntax_auto_invite_idx_department ON {{prefix}}crafty_syntax_auto_invite (department_id);
CREATE INDEX {{prefix}}lupo_crafty_syntax_auto_invite_idx_operator ON {{prefix}}crafty_syntax_auto_invite (operator_user_id);
CREATE INDEX {{prefix}}lupo_crafty_syntax_auto_invite_idx_page_url ON {{prefix}}crafty_syntax_auto_invite (page_url);
CREATE INDEX {{prefix}}lupo_crafty_syntax_auto_invite_idx_status ON {{prefix}}crafty_syntax_auto_invite (is_active, is_deleted);

-- lupo_crafty_syntax_chat_mod_departments
CREATE TABLE {{prefix}}crafty_syntax_chat_mod_departments (
  `crafty_syntax_chat_mod_department_id` bigint NOT NULL,
  `department_id` bigint NOT NULL DEFAULT 0,
  `module_id` bigint NOT NULL DEFAULT 0,
  `sort_order` int NOT NULL DEFAULT 0,
  `is_active` tinyint NOT NULL DEFAULT 1,
  `is_default` tinyint NOT NULL DEFAULT 0,
  PRIMARY KEY (crafty_syntax_chat_mod_department_id)
);


-- lupo_crafty_syntax_chat_questions
CREATE TABLE {{prefix}}crafty_syntax_chat_questions (
  `crafty_syntax_chat_question_id` bigint NOT NULL,
  `department_id` bigint NOT NULL DEFAULT 0,
  `sort_order` int NOT NULL DEFAULT 0,
  `headertext` mediumtext,
  `field_type` varchar(60),
  `options` mediumtext,
  `flags` varchar(255),
  `module_name` varchar(100),
  `is_required` tinyint NOT NULL DEFAULT 0,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  PRIMARY KEY (crafty_syntax_chat_question_id)
);

CREATE INDEX {{prefix}}lupo_crafty_syntax_chat_questions_idx_department ON {{prefix}}crafty_syntax_chat_questions (department_id);

-- lupo_crafty_syntax_layer_invites
CREATE TABLE {{prefix}}crafty_syntax_layer_invites (
  `crafty_syntax_layer_invite_id` bigint NOT NULL,
  `layer_name` varchar(100) NOT NULL DEFAULT '',
  `image_name` varchar(255) NOT NULL DEFAULT '',
  `image_map` text,
  `department_name` varchar(100) NOT NULL DEFAULT '',
  `user_id` bigint NOT NULL DEFAULT 0,
  `is_active` tinyint NOT NULL DEFAULT 1,
  `display_count` int NOT NULL DEFAULT 0,
  `click_count` int NOT NULL DEFAULT 0,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  PRIMARY KEY (crafty_syntax_layer_invite_id)
);

CREATE INDEX {{prefix}}lupo_crafty_syntax_layer_invites_idx_active ON {{prefix}}crafty_syntax_layer_invites (is_active);
CREATE INDEX {{prefix}}lupo_crafty_syntax_layer_invites_idx_created ON {{prefix}}crafty_syntax_layer_invites (created_ymdhis);
CREATE INDEX {{prefix}}lupo_crafty_syntax_layer_invites_idx_department ON {{prefix}}crafty_syntax_layer_invites (department_name);
CREATE INDEX {{prefix}}lupo_crafty_syntax_layer_invites_idx_name ON {{prefix}}crafty_syntax_layer_invites (layer_name);
CREATE INDEX {{prefix}}lupo_crafty_syntax_layer_invites_idx_updated ON {{prefix}}crafty_syntax_layer_invites (updated_ymdhis);
CREATE INDEX {{prefix}}lupo_crafty_syntax_layer_invites_idx_user ON {{prefix}}crafty_syntax_layer_invites (user_id);

-- lupo_crafty_syntax_leave_message
CREATE TABLE {{prefix}}crafty_syntax_leave_message (
  `crafty_syntax_leave_message_id` bigint NOT NULL,
  `department_id` bigint NOT NULL DEFAULT 0,
  `email` varchar(255) NOT NULL DEFAULT '',
  `phone` varchar(45),
  `name` varchar(200),
  `subject` varchar(255) NOT NULL DEFAULT '',
  `message` text,
  `priority` tinyint NOT NULL DEFAULT 2,
  `session_data` text,
  `form_data` text,
  `ip_address` varchar(45),
  `user_agent` varchar(255),
  `status` enum('new','in_progress','resolved','spam') NOT NULL DEFAULT 'new',
  `assigned_to` bigint,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  PRIMARY KEY (crafty_syntax_leave_message_id)
);

CREATE INDEX {{prefix}}lupo_crafty_syntax_leave_message_idx_assigned ON {{prefix}}crafty_syntax_leave_message (assigned_to);
CREATE INDEX {{prefix}}lupo_crafty_syntax_leave_message_idx_created ON {{prefix}}crafty_syntax_leave_message (created_ymdhis);
CREATE INDEX {{prefix}}lupo_crafty_syntax_leave_message_idx_department ON {{prefix}}crafty_syntax_leave_message (department_id);
CREATE INDEX {{prefix}}lupo_crafty_syntax_leave_message_idx_email ON {{prefix}}crafty_syntax_leave_message (email);
CREATE INDEX {{prefix}}lupo_crafty_syntax_leave_message_idx_message_search ON {{prefix}}crafty_syntax_leave_message (email, name, subject, message);
CREATE INDEX {{prefix}}lupo_crafty_syntax_leave_message_idx_priority ON {{prefix}}crafty_syntax_leave_message (priority);
CREATE INDEX {{prefix}}lupo_crafty_syntax_leave_message_idx_status ON {{prefix}}crafty_syntax_leave_message (status);

-- lupo_crafty_user_mapping
CREATE TABLE {{prefix}}crafty_user_mapping (
  `crafty_user_mapping_id` bigint NOT NULL,
  `lupo_user_id` bigint,
  `crafty_operator_id` int,
  `mapping_type` varchar(50) NOT NULL DEFAULT 'manual',
  `notes` text,
  `created_at` bigint NOT NULL DEFAULT 0,
  `updated_at` bigint NOT NULL DEFAULT 0,
  PRIMARY KEY (crafty_user_mapping_id)
);

CREATE INDEX {{prefix}}lupo_crafty_user_mapping_idx_crafty_operator_id ON {{prefix}}crafty_user_mapping (crafty_operator_id);
CREATE INDEX {{prefix}}lupo_crafty_user_mapping_idx_lupo_user_id ON {{prefix}}crafty_user_mapping (lupo_user_id);
CREATE INDEX {{prefix}}lupo_crafty_user_mapping_idx_mapping_type ON {{prefix}}crafty_user_mapping (mapping_type);
CREATE UNIQUE INDEX {{prefix}}lupo_crafty_user_mapping_unique_crafty_operator_mapping ON {{prefix}}crafty_user_mapping (crafty_operator_id);
CREATE UNIQUE INDEX {{prefix}}lupo_crafty_user_mapping_unique_lupo_user_mapping ON {{prefix}}crafty_user_mapping (lupo_user_id);

-- lupo_crm_lead_messages
CREATE TABLE {{prefix}}crm_lead_messages (
  `crm_lead_message_id` bigint NOT NULL,
  `lead_id` bigint,
  `from_email` varchar(255),
  `subject` varchar(255),
  `body_text` text NOT NULL,
  `notes` varchar(255),
  `actor_id` bigint,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL,
  `is_deleted` smallint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  PRIMARY KEY (crm_lead_message_id)
);

CREATE INDEX {{prefix}}lupo_crm_lead_messages_actor_id ON {{prefix}}crm_lead_messages (actor_id);
CREATE INDEX {{prefix}}lupo_crm_lead_messages_lead_id ON {{prefix}}crm_lead_messages (lead_id);

-- lupo_crm_leads
CREATE TABLE {{prefix}}crm_leads (
  `crm_lead_id` bigint NOT NULL,
  `email` varchar(255),
  `phone` varchar(45),
  `first_name` varchar(100),
  `last_name` varchar(100),
  `source` varchar(100),
  `status` varchar(50) NOT NULL DEFAULT 'new',
  `lead_score` int NOT NULL DEFAULT 0,
  `assigned_to` bigint,
  `lead_data` text,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  PRIMARY KEY (crm_lead_id)
);


-- lupo_department_capabilities
CREATE TABLE {{prefix}}department_capabilities (
  `dept_capability_id` bigint NOT NULL,
  `department_id` bigint NOT NULL,
  `capability_key` varchar(100) NOT NULL,
  `capability_description` text,
  `domain_id` bigint,
  `scope_limitation` varchar(50) NOT NULL DEFAULT 'unrestricted',
  `granted_by_actor_id` bigint,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL DEFAULT 0,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  PRIMARY KEY (dept_capability_id)
);

CREATE INDEX {{prefix}}lupo_dept_capabilities_idx_deleted ON {{prefix}}department_capabilities (is_deleted);
CREATE INDEX {{prefix}}lupo_dept_capabilities_idx_dept ON {{prefix}}department_capabilities (department_id);
CREATE INDEX {{prefix}}lupo_dept_capabilities_idx_key ON {{prefix}}department_capabilities (capability_key);
CREATE UNIQUE INDEX {{prefix}}lupo_dept_capabilities_unq ON {{prefix}}department_capabilities (department_id, capability_key);

-- lupo_department_metadata
CREATE TABLE {{prefix}}department_metadata (
  `department_metadata_id` bigint NOT NULL,
  `department_id` bigint NOT NULL,
  `metadata_json` json NOT NULL,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL,
  `is_active` tinyint NOT NULL DEFAULT 1,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  PRIMARY KEY (department_metadata_id)
);

CREATE UNIQUE INDEX {{prefix}}lupo_department_metadata_uq_department_metadata ON {{prefix}}department_metadata (department_id);

-- lupo_department_roles
CREATE TABLE {{prefix}}department_roles (
  `department_role_id` bigint NOT NULL,
  `actor_id` bigint NOT NULL,
  `department_id` bigint NOT NULL,
  `role_key` varchar(64) NOT NULL,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  PRIMARY KEY (department_role_id)
);

CREATE INDEX {{prefix}}lupo_department_roles_idx_actor_id ON {{prefix}}department_roles (actor_id);
CREATE INDEX {{prefix}}lupo_department_roles_idx_department_id ON {{prefix}}department_roles (department_id);
CREATE INDEX {{prefix}}lupo_department_roles_idx_role_key ON {{prefix}}department_roles (role_key);

-- lupo_departments
CREATE TABLE {{prefix}}departments (
  `department_id` bigint NOT NULL,
  `federation_node_id` bigint NOT NULL,
  `name` varchar(64) NOT NULL,
  `description` text,
  `department_type` varchar(32) NOT NULL DEFAULT 'general',
  `default_actor_id` bigint NOT NULL DEFAULT 1,
  `settings_json` json,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  PRIMARY KEY (department_id)
);

CREATE INDEX {{prefix}}lupo_departments_idx_federation_node ON {{prefix}}departments (federation_node_id);
CREATE INDEX {{prefix}}lupo_departments_idx_name ON {{prefix}}departments (name);
CREATE INDEX {{prefix}}lupo_departments_idx_type ON {{prefix}}departments (department_type);

-- lupo_dialog_channels
CREATE TABLE {{prefix}}dialog_channels (
  `channel_id` bigint NOT NULL,
  `channel_name` varchar(255) NOT NULL,
  `file_source` varchar(255) NOT NULL,
  `title` varchar(500),
  `description` text,
  `speaker` varchar(100),
  `target` varchar(100),
  `categories` json,
  `collections` json,
  `channels` json,
  `tags` json,
  `version` varchar(20),
  `status` varchar(64) DEFAULT 'published',
  `author` varchar(100),
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL,
  `message_count` int DEFAULT 0,
  `metadata_json` json,
  PRIMARY KEY (channel_id)
);

CREATE UNIQUE INDEX {{prefix}}lupo_dialog_channels_idx_channel_name ON {{prefix}}dialog_channels (channel_name);
CREATE INDEX {{prefix}}lupo_dialog_channels_idx_created_ymdhis ON {{prefix}}dialog_channels (created_ymdhis);
CREATE INDEX {{prefix}}lupo_dialog_channels_idx_dialog_channels_composite ON {{prefix}}dialog_channels (status, created_ymdhis);
CREATE INDEX {{prefix}}lupo_dialog_channels_idx_file_source ON {{prefix}}dialog_channels (file_source);
CREATE INDEX {{prefix}}lupo_dialog_channels_idx_speaker ON {{prefix}}dialog_channels (speaker);
CREATE INDEX {{prefix}}lupo_dialog_channels_idx_status ON {{prefix}}dialog_channels (status);
CREATE INDEX {{prefix}}lupo_dialog_channels_idx_target ON {{prefix}}dialog_channels (target);
CREATE INDEX {{prefix}}lupo_dialog_channels_idx_updated_ymdhis ON {{prefix}}dialog_channels (updated_ymdhis);

-- lupo_dialog_messages
CREATE TABLE {{prefix}}dialog_messages (
  `dialog_message_id` bigint NOT NULL,
  `dialog_thread_id` bigint,
  `channel_id` bigint,
  `channel_key` varchar(255) NOT NULL,
  `from_actor_id` bigint,
  `source_faucet_slug` varchar(100),
  `source_faucet_instance_id` varchar(100),
  `to_actor_id` bigint,
  `read_by_actor_id` bigint NOT NULL DEFAULT 0,
  `read_by_actor_utc` bigint NOT NULL DEFAULT 0,
  `message_text` mediumtext NOT NULL,
  `message_type` varchar(64) NOT NULL DEFAULT 'text',
  `metadata_json` json,
  `mood_vector` char(6),
  `mood_framework` varchar(32) NOT NULL DEFAULT 'western_analytical',
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  PRIMARY KEY (dialog_message_id)
);

CREATE INDEX {{prefix}}lupo_dialog_messages_idx_channel ON {{prefix}}dialog_messages (channel_id);
CREATE INDEX {{prefix}}lupo_dialog_messages_idx_created ON {{prefix}}dialog_messages (created_ymdhis);
CREATE INDEX {{prefix}}lupo_dialog_messages_idx_deleted ON {{prefix}}dialog_messages (is_deleted);
CREATE INDEX {{prefix}}lupo_dialog_messages_idx_dialog_thread_id ON {{prefix}}dialog_messages (dialog_thread_id);
CREATE INDEX {{prefix}}lupo_dialog_messages_idx_faucet ON {{prefix}}dialog_messages (source_faucet_slug, source_faucet_instance_id);
CREATE INDEX {{prefix}}lupo_dialog_messages_idx_message_type ON {{prefix}}dialog_messages (message_type);
CREATE INDEX {{prefix}}lupo_dialog_messages_idx_read_by_actor ON {{prefix}}dialog_messages (read_by_actor_id);
CREATE INDEX {{prefix}}lupo_dialog_messages_idx_read_utc ON {{prefix}}dialog_messages (read_by_actor_utc);
CREATE INDEX {{prefix}}lupo_dialog_messages_idx_to_actor_id ON {{prefix}}dialog_messages (to_actor_id);
CREATE INDEX {{prefix}}lupo_dialog_messages_idx_updated ON {{prefix}}dialog_messages (updated_ymdhis);

-- lupo_dialog_pending_tasks
CREATE TABLE {{prefix}}dialog_pending_tasks (
  `task_id` bigint NOT NULL,
  `message_id` bigint NOT NULL,
  `channel_id` bigint NOT NULL,
  `assignee_actor_id` bigint NOT NULL,
  `creator_actor_id` bigint NOT NULL,
  `task_body` text NOT NULL,
  `status` varchar(32) DEFAULT 'pending',
  `priority` int DEFAULT 1,
  `created_ymdhis` bigint NOT NULL,
  `updated_ymdhis` bigint NOT NULL,
  `completed_ymdhis` bigint,
  PRIMARY KEY (task_id)
);

CREATE INDEX {{prefix}}lupo_pending_tasks_idx_assignee ON {{prefix}}dialog_pending_tasks (assignee_actor_id, status);
CREATE INDEX {{prefix}}lupo_pending_tasks_idx_channel ON {{prefix}}dialog_pending_tasks (channel_id, status);

-- lupo_dialog_read_log
CREATE TABLE {{prefix}}dialog_read_log (
  `read_log_id` bigint NOT NULL,
  `actor_id` bigint NOT NULL,
  `channel_id` bigint NOT NULL,
  `thread_id` bigint NOT NULL,
  `last_read_message_id` bigint NOT NULL,
  `updated_ymdhis` bigint NOT NULL,
  PRIMARY KEY (read_log_id)
);

CREATE INDEX {{prefix}}lupo_read_log_idx_actor_context ON {{prefix}}dialog_read_log (actor_id, channel_id, thread_id);

-- lupo_dialog_recent_files
CREATE TABLE {{prefix}}dialog_recent_files (
  `recent_file_id` bigint NOT NULL,
  `channel_id` bigint NOT NULL,
  `file_path` varchar(512) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `artifact_kind` varchar(64),
  `last_referenced_ymdhis` bigint NOT NULL,
  `actor_id` bigint NOT NULL,
  `federation_node_id` bigint NOT NULL DEFAULT 0,
  PRIMARY KEY (recent_file_id)
);

CREATE INDEX {{prefix}}lupo_recent_files_idx_channel ON {{prefix}}dialog_recent_files (channel_id, last_referenced_ymdhis);

-- lupo_dialog_threads
CREATE TABLE {{prefix}}dialog_threads (
  `dialog_thread_id` bigint NOT NULL,
  `title` varchar(255) NOT NULL,
  `thread_key` varchar(255) NOT NULL,
  `last_message_ymdhis` bigint,
  `federation_node_id` bigint NOT NULL DEFAULT 1,
  `channel_id` bigint,
  `project_slug` varchar(100),
  `task_name` varchar(255),
  `created_by_actor_id` bigint NOT NULL,
  `summary_text` text,
  `bg_color` char(6) NOT NULL DEFAULT 'FFFFFF',
  `text_color` char(6) NOT NULL DEFAULT '000000',
  `alt_text_color` char(6) NOT NULL DEFAULT '666666',
  `status` varchar(64) NOT NULL DEFAULT 'Open',
  `artifacts` json,
  `metadata_json` json,
  `thread_lineage` text,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  `escalated_to_operator_id` bigint,
  `escalation_reason` varchar(255),
  `escalation_timestamp` bigint,
  `visibility_status` varchar(32) NOT NULL DEFAULT 'active',
  `owner_actor_id` bigint NOT NULL,
  `assigned_actor_id` bigint,
  `thread_type` varchar(32) NOT NULL DEFAULT 'discussion',
  `thread_priority` varchar(32) NOT NULL DEFAULT 'normal',
  PRIMARY KEY (dialog_thread_id)
);

CREATE INDEX {{prefix}}lupo_dialog_threads_idx_assigned_actor_id ON {{prefix}}dialog_threads (assigned_actor_id);
CREATE INDEX {{prefix}}lupo_dialog_threads_idx_channel ON {{prefix}}dialog_threads (channel_id);
CREATE INDEX {{prefix}}lupo_dialog_threads_idx_created ON {{prefix}}dialog_threads (created_ymdhis);
CREATE INDEX {{prefix}}lupo_dialog_threads_idx_created_by_actor ON {{prefix}}dialog_threads (created_by_actor_id);
CREATE INDEX {{prefix}}lupo_dialog_threads_idx_deleted ON {{prefix}}dialog_threads (is_deleted);
CREATE INDEX {{prefix}}lupo_dialog_threads_idx_last_message ON {{prefix}}dialog_threads (last_message_ymdhis);
CREATE INDEX {{prefix}}lupo_dialog_threads_idx_node ON {{prefix}}dialog_threads (federation_node_id);
CREATE INDEX {{prefix}}lupo_dialog_threads_idx_owner_actor_id ON {{prefix}}dialog_threads (owner_actor_id);
CREATE INDEX {{prefix}}lupo_dialog_threads_idx_project ON {{prefix}}dialog_threads (project_slug);
CREATE INDEX {{prefix}}lupo_dialog_threads_idx_status ON {{prefix}}dialog_threads (status);
CREATE INDEX {{prefix}}lupo_dialog_threads_idx_task ON {{prefix}}dialog_threads (task_name);
CREATE INDEX {{prefix}}lupo_dialog_threads_idx_thread_priority ON {{prefix}}dialog_threads (thread_priority);
CREATE INDEX {{prefix}}lupo_dialog_threads_idx_thread_type ON {{prefix}}dialog_threads (thread_type);
CREATE INDEX {{prefix}}lupo_dialog_threads_idx_updated ON {{prefix}}dialog_threads (updated_ymdhis);
CREATE INDEX {{prefix}}lupo_dialog_threads_idx_visibility_status ON {{prefix}}dialog_threads (visibility_status);

-- lupo_edge_types
CREATE TABLE {{prefix}}edge_types (
  `edge_type_id` bigint NOT NULL,
  `edge_type_key` varchar(100) NOT NULL,
  `edge_category` varchar(100),
  `display_name` varchar(255),
  `description` text,
  `is_bidirectional` tinyint NOT NULL DEFAULT 0,
  `is_system` tinyint NOT NULL DEFAULT 0,
  `valid_left_types` json,
  `valid_right_types` json,
  `metadata_json` json,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL DEFAULT 0,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  PRIMARY KEY (edge_type_id)
);

CREATE INDEX {{prefix}}lupo_edge_types_idx_category ON {{prefix}}edge_types (edge_category);
CREATE INDEX {{prefix}}lupo_edge_types_idx_deleted ON {{prefix}}edge_types (is_deleted);
CREATE INDEX {{prefix}}lupo_edge_types_idx_system ON {{prefix}}edge_types (is_system);
CREATE UNIQUE INDEX {{prefix}}lupo_edge_types_unq_key ON {{prefix}}edge_types (edge_type_key);

-- lupo_edges
CREATE TABLE {{prefix}}edges (
  `edge_id` bigint NOT NULL,
  `left_object_type` varchar(50) NOT NULL,
  `left_object_id` bigint NOT NULL,
  `right_object_type` varchar(50) NOT NULL,
  `right_object_id` bigint NOT NULL,
  `edge_type` varchar(100) NOT NULL,
  `edge_category` varchar(100),
  `edge_description` text,
  `channel_id` bigint,
  `channel_key` varchar(64),
  `domain_id` bigint NOT NULL DEFAULT 1,
  `weight_score` int NOT NULL DEFAULT 0,
  `sort_num` int NOT NULL DEFAULT 0,
  `actor_id` bigint,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint NOT NULL DEFAULT 0,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL DEFAULT 0,
  `semantic_weight` decimal(5,2) DEFAULT 0.00,
  `relationship_type` varchar(64) DEFAULT 'semantic',
  `bidirectional` tinyint NOT NULL DEFAULT 0,
  `context_scope` varchar(100),
  `properties` json,
  `flare_weight` decimal(3,2) DEFAULT 0.50 COMMENT 'FLARE edge weight (0.5-1.0)',
  `flare_reason` varchar(255) COMMENT 'Reason for edge existence',
  `flare_db_source` varchar(50) COMMENT 'Database source table',
  `flare_auto_generated` tinyint DEFAULT 0 COMMENT 'Generated by automation',
  `flare_verified` tinyint DEFAULT 0 COMMENT 'Path verified to exist',
  `flare_discovered_via` varchar(50) COMMENT 'Discovery method',
  `edge_context` varchar(64) COMMENT 'Contextual scope: temporal, spatial, semantic, causal, etc.',
  `edge_status` varchar(32) DEFAULT 'active' COMMENT 'active | pending | deprecated | review',
  `direction` enum('uni','bi','restricted') DEFAULT 'uni' COMMENT 'Edge directionality: uni=A?B only, bi=A?B, restricted=access-controlled',
  `review_reason` varchar(64) COMMENT 'Option C review classification; required when edge_status=review',
  PRIMARY KEY (edge_id)
);

CREATE INDEX {{prefix}}lupo_edges_idx_actor ON {{prefix}}edges (actor_id);
CREATE INDEX {{prefix}}lupo_edges_idx_channel_semantic ON {{prefix}}edges (channel_id, relationship_type, semantic_weight);
CREATE INDEX {{prefix}}lupo_edges_idx_created ON {{prefix}}edges (created_ymdhis);
CREATE INDEX {{prefix}}lupo_edges_idx_direction ON {{prefix}}edges (direction);
CREATE INDEX {{prefix}}lupo_edges_idx_domain ON {{prefix}}edges (domain_id);
CREATE INDEX {{prefix}}lupo_edges_idx_edge_category ON {{prefix}}edges (edge_category);
CREATE INDEX {{prefix}}lupo_edges_idx_edge_context ON {{prefix}}edges (edge_context);
CREATE INDEX {{prefix}}lupo_edges_idx_edge_type ON {{prefix}}edges (edge_type);
CREATE INDEX {{prefix}}lupo_edges_idx_flare_discovered ON {{prefix}}edges (flare_discovered_via, flare_auto_generated);
CREATE INDEX {{prefix}}lupo_edges_idx_flare_files ON {{prefix}}edges (left_object_type, left_object_id, edge_type, right_object_type, right_object_id);
CREATE INDEX {{prefix}}lupo_edges_idx_flare_weight ON {{prefix}}edges (flare_weight, edge_type);
CREATE INDEX {{prefix}}lupo_edges_idx_is_deleted ON {{prefix}}edges (is_deleted);
CREATE INDEX {{prefix}}lupo_edges_idx_left ON {{prefix}}edges (left_object_type, left_object_id);
CREATE INDEX {{prefix}}lupo_edges_idx_relationship_type ON {{prefix}}edges (relationship_type);
CREATE INDEX {{prefix}}lupo_edges_idx_right ON {{prefix}}edges (right_object_type, right_object_id);
CREATE INDEX {{prefix}}lupo_edges_idx_semantic_weight ON {{prefix}}edges (semantic_weight);
CREATE INDEX {{prefix}}lupo_edges_idx_status_review ON {{prefix}}edges (edge_status, review_reason);
CREATE INDEX {{prefix}}lupo_edges_idx_updated ON {{prefix}}edges (updated_ymdhis);

-- lupo_emotional_frameworks
CREATE TABLE {{prefix}}emotional_frameworks (
  `framework_name` varchar(32) NOT NULL,
  `description` text,
  `is_default` tinyint NOT NULL DEFAULT 0,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL,
  PRIMARY KEY (framework_name)
);


-- lupo_escalation_tasks
CREATE TABLE {{prefix}}escalation_tasks (
  `escalation_task_id` bigint NOT NULL,
  `actor_id` bigint NOT NULL,
  `thread_id` bigint NOT NULL,
  `message_id` bigint NOT NULL,
  `task_type` varchar(64) NOT NULL,
  `status` varchar(32) NOT NULL DEFAULT 'open',
  `assigned_actor_id` bigint NOT NULL,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL,
  PRIMARY KEY (escalation_task_id)
);

CREATE INDEX {{prefix}}lupo_escalation_tasks_idx_actor_id ON {{prefix}}escalation_tasks (actor_id);
CREATE INDEX {{prefix}}lupo_escalation_tasks_idx_assigned_actor_id ON {{prefix}}escalation_tasks (assigned_actor_id);
CREATE INDEX {{prefix}}lupo_escalation_tasks_idx_message_id ON {{prefix}}escalation_tasks (message_id);
CREATE INDEX {{prefix}}lupo_escalation_tasks_idx_status ON {{prefix}}escalation_tasks (status);
CREATE INDEX {{prefix}}lupo_escalation_tasks_idx_thread_id ON {{prefix}}escalation_tasks (thread_id);

-- lupo_faucet_rules
CREATE TABLE {{prefix}}faucet_rules (
  `faucet_rule_id` bigint NOT NULL,
  `rule_key` varchar(100) NOT NULL,
  `faucet_type` varchar(64) NOT NULL,
  `source_actor_id` bigint,
  `executing_actor_id` bigint NOT NULL,
  `condition_json` json,
  `priority` int NOT NULL DEFAULT 100,
  `is_active` tinyint NOT NULL DEFAULT 1,
  `notes` text,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL DEFAULT 0,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  PRIMARY KEY (faucet_rule_id)
);

CREATE INDEX {{prefix}}lupo_faucet_rules_idx_active ON {{prefix}}faucet_rules (is_active);
CREATE INDEX {{prefix}}lupo_faucet_rules_idx_deleted ON {{prefix}}faucet_rules (is_deleted);
CREATE INDEX {{prefix}}lupo_faucet_rules_idx_executing ON {{prefix}}faucet_rules (executing_actor_id);
CREATE INDEX {{prefix}}lupo_faucet_rules_idx_source ON {{prefix}}faucet_rules (source_actor_id);
CREATE INDEX {{prefix}}lupo_faucet_rules_idx_type ON {{prefix}}faucet_rules (faucet_type);
CREATE UNIQUE INDEX {{prefix}}lupo_faucet_rules_unq_key ON {{prefix}}faucet_rules (rule_key);

-- lupo_federated_trust
CREATE TABLE {{prefix}}federated_trust (
  `trust_id` bigint NOT NULL,
  `source_node_id` bigint NOT NULL,
  `target_node_id` bigint NOT NULL,
  `trust_level` float DEFAULT 0.5,
  `trust_type` varchar(50) NOT NULL,
  `capabilities` json,
  `restrictions` json,
  `last_verified_ymdhis` bigint,
  `verification_method` varchar(100),
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  PRIMARY KEY (trust_id)
);

CREATE INDEX {{prefix}}lupo_federated_trust_idx_is_deleted ON {{prefix}}federated_trust (is_deleted);
CREATE INDEX {{prefix}}lupo_federated_trust_idx_last_verified ON {{prefix}}federated_trust (last_verified_ymdhis);
CREATE UNIQUE INDEX {{prefix}}lupo_federated_trust_idx_source_target ON {{prefix}}federated_trust (source_node_id, target_node_id);
CREATE INDEX {{prefix}}lupo_federated_trust_idx_trust_type ON {{prefix}}federated_trust (trust_type);

-- lupo_federation_categories
CREATE TABLE {{prefix}}federation_categories (
  `federation_category_id` bigint NOT NULL,
  `category_name` varchar(255) NOT NULL,
  `category_slug` varchar(255) NOT NULL,
  `category_description` text,
  `meta_json` json,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint NOT NULL DEFAULT 0,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL DEFAULT 0,
  PRIMARY KEY (federation_category_id)
);

CREATE INDEX {{prefix}}lupo_federation_categories_idx_category_slug ON {{prefix}}federation_categories (category_slug);
CREATE INDEX {{prefix}}lupo_federation_categories_idx_is_deleted ON {{prefix}}federation_categories (is_deleted);

-- lupo_federation_category_map
CREATE TABLE {{prefix}}federation_category_map (
  `federation_category_map_id` bigint NOT NULL,
  `federation_node_id` bigint NOT NULL,
  `federation_category_id` bigint NOT NULL,
  `meta_json` json,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint NOT NULL DEFAULT 0,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL DEFAULT 0,
  PRIMARY KEY (federation_category_map_id)
);

CREATE INDEX {{prefix}}lupo_federation_category_map_idx_category ON {{prefix}}federation_category_map (federation_category_id);
CREATE INDEX {{prefix}}lupo_federation_category_map_idx_is_deleted ON {{prefix}}federation_category_map (is_deleted);
CREATE INDEX {{prefix}}lupo_federation_category_map_idx_node ON {{prefix}}federation_category_map (federation_node_id);

-- lupo_federation_discovery
CREATE TABLE {{prefix}}federation_discovery (
  `federation_discovery_id` bigint NOT NULL,
  `domain` varchar(255) NOT NULL,
  `install_url` varchar(500),
  `is_lupopedia` tinyint NOT NULL DEFAULT 0,
  `last_seen_ymdhis` bigint,
  `first_seen_ymdhis` bigint,
  `hashtag_count` bigint,
  `question_count` bigint,
  `atom_count` bigint,
  `context_count` bigint,
  `collection_count` bigint,
  `keywords` varchar(500),
  `description` text,
  `import_hashtags` tinyint NOT NULL DEFAULT 0,
  `import_questions` tinyint NOT NULL DEFAULT 0,
  `import_atoms` tinyint NOT NULL DEFAULT 0,
  `import_collections` tinyint NOT NULL DEFAULT 0,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL,
  PRIMARY KEY (federation_discovery_id)
);

CREATE INDEX {{prefix}}lupo_federation_discovery_idx_domain ON {{prefix}}federation_discovery (domain);

-- lupo_federation_nodes
CREATE TABLE {{prefix}}federation_nodes (
  `federation_node_id` bigint NOT NULL,
  `node_type` varchar(32) NOT NULL DEFAULT 'local',
  `node_base_url` varchar(500) NOT NULL,
  `default_department_id` bigint,
  `node_name` varchar(255),
  `description` text,
  `node_description` text,
  `allows_foreign_traits` tinyint NOT NULL DEFAULT 1,
  `node_contact` varchar(255),
  `meta_json` json,
  `content_count` bigint NOT NULL DEFAULT 0,
  `atom_count` bigint NOT NULL DEFAULT 0,
  `hashtag_count` bigint NOT NULL DEFAULT 0,
  `actor_count` bigint NOT NULL DEFAULT 0,
  `last_sync_ymdhis` bigint NOT NULL DEFAULT 0,
  `trust_level` tinyint NOT NULL DEFAULT 0,
  `status` tinyint NOT NULL DEFAULT 1,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint NOT NULL DEFAULT 0,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL DEFAULT 0,
  `active_theme_slug` varchar(64) DEFAULT 'default',
  PRIMARY KEY (federation_node_id)
);

CREATE INDEX {{prefix}}lupo_federation_nodes_idx_is_deleted ON {{prefix}}federation_nodes (is_deleted);
CREATE INDEX {{prefix}}lupo_federation_nodes_idx_node_base_url ON {{prefix}}federation_nodes (node_base_url);
CREATE INDEX {{prefix}}lupo_federation_nodes_idx_status ON {{prefix}}federation_nodes (status);
CREATE INDEX {{prefix}}lupo_federation_nodes_idx_trust_level ON {{prefix}}federation_nodes (trust_level);

-- lupo_folder_map
CREATE TABLE {{prefix}}folder_map (
  `folder_map_id` bigint NOT NULL,
  `folder_id` bigint NOT NULL,
  `object_type` varchar(64) NOT NULL,
  `object_id` bigint NOT NULL,
  `sort_order` int NOT NULL DEFAULT 0,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  PRIMARY KEY (folder_map_id)
);

CREATE INDEX {{prefix}}lupo_folder_map_idx_folder ON {{prefix}}folder_map (folder_id);
CREATE INDEX {{prefix}}lupo_folder_map_idx_is_deleted ON {{prefix}}folder_map (is_deleted);
CREATE INDEX {{prefix}}lupo_folder_map_idx_object ON {{prefix}}folder_map (object_type, object_id);

-- lupo_folders
CREATE TABLE {{prefix}}folders (
  `folder_id` bigint NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(128) NOT NULL,
  `description` text,
  `parent_folder_id` bigint,
  `actor_id` bigint,
  `channel_id` bigint,
  `sort_order` int NOT NULL DEFAULT 0,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL DEFAULT 0,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint NOT NULL DEFAULT 0,
  PRIMARY KEY (folder_id)
);

CREATE INDEX {{prefix}}lupo_folders_idx_actor ON {{prefix}}folders (actor_id);
CREATE INDEX {{prefix}}lupo_folders_idx_channel ON {{prefix}}folders (channel_id);
CREATE INDEX {{prefix}}lupo_folders_idx_is_deleted ON {{prefix}}folders (is_deleted);
CREATE INDEX {{prefix}}lupo_folders_idx_parent ON {{prefix}}folders (parent_folder_id);
CREATE INDEX {{prefix}}lupo_folders_idx_slug ON {{prefix}}folders (slug);

-- lupo_governance_overrides
CREATE TABLE {{prefix}}governance_overrides (
  `governance_override_id` bigint NOT NULL,
  `actor_id` bigint NOT NULL,
  `override_type` varchar(64) NOT NULL,
  `override_scope` varchar(64),
  `override_reason` text,
  `granted_by_actor_id` bigint,
  `expires_ymdhis` bigint,
  `is_active` tinyint NOT NULL DEFAULT 1,
  `metadata_json` json,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL DEFAULT 0,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  PRIMARY KEY (governance_override_id)
);

CREATE INDEX {{prefix}}lupo_governance_overrides_idx_active ON {{prefix}}governance_overrides (is_active);
CREATE INDEX {{prefix}}lupo_governance_overrides_idx_actor ON {{prefix}}governance_overrides (actor_id);
CREATE INDEX {{prefix}}lupo_governance_overrides_idx_deleted ON {{prefix}}governance_overrides (is_deleted);
CREATE INDEX {{prefix}}lupo_governance_overrides_idx_expires ON {{prefix}}governance_overrides (expires_ymdhis);
CREATE INDEX {{prefix}}lupo_governance_overrides_idx_type ON {{prefix}}governance_overrides (override_type);

-- lupo_hashtag_map
CREATE TABLE {{prefix}}hashtag_map (
  `hashtag_map_id` bigint NOT NULL,
  `hashtag_id` bigint NOT NULL,
  `object_type` varchar(64) NOT NULL,
  `object_id` bigint NOT NULL,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  PRIMARY KEY (hashtag_map_id)
);

CREATE INDEX {{prefix}}lupo_hashtag_map_idx_hashtag ON {{prefix}}hashtag_map (hashtag_id);
CREATE INDEX {{prefix}}lupo_hashtag_map_idx_is_deleted ON {{prefix}}hashtag_map (is_deleted);
CREATE INDEX {{prefix}}lupo_hashtag_map_idx_object ON {{prefix}}hashtag_map (object_type, object_id);

-- lupo_hashtags
CREATE TABLE {{prefix}}hashtags (
  `hashtag_id` bigint NOT NULL,
  `tag_slug` varchar(128) NOT NULL,
  `label` varchar(255),
  `use_count` bigint NOT NULL DEFAULT 0,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL DEFAULT 0,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  PRIMARY KEY (hashtag_id)
);

CREATE INDEX {{prefix}}lupo_hashtags_idx_is_deleted ON {{prefix}}hashtags (is_deleted);
CREATE INDEX {{prefix}}lupo_hashtags_idx_use_count ON {{prefix}}hashtags (use_count);
CREATE UNIQUE INDEX {{prefix}}lupo_hashtags_uniq_slug ON {{prefix}}hashtags (tag_slug);

-- lupo_help_topics
CREATE TABLE {{prefix}}help_topics (
  `help_topic_id` bigint NOT NULL,
  `slug` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content_html` text,
  `content_markdown` text,
  `category` varchar(100),
  `parent_slug` varchar(255),
  `view_count` bigint DEFAULT 0,
  `helpful_count` bigint DEFAULT 0,
  `not_helpful_count` bigint DEFAULT 0,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL,
  `author_actor_id` bigint,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  PRIMARY KEY (help_topic_id)
);

CREATE INDEX {{prefix}}lupo_help_topics_idx_author ON {{prefix}}help_topics (author_actor_id);
CREATE INDEX {{prefix}}lupo_help_topics_idx_category ON {{prefix}}help_topics (category);
CREATE INDEX {{prefix}}lupo_help_topics_idx_created ON {{prefix}}help_topics (created_ymdhis);
CREATE INDEX {{prefix}}lupo_help_topics_idx_parent ON {{prefix}}help_topics (parent_slug);
CREATE INDEX {{prefix}}lupo_help_topics_idx_slug ON {{prefix}}help_topics (slug);
CREATE UNIQUE INDEX {{prefix}}lupo_help_topics_slug ON {{prefix}}help_topics (slug);

-- lupo_help_tree
CREATE TABLE {{prefix}}help_tree (
  `help_tree_id` bigint NOT NULL,
  `parent_id` bigint,
  `department_id` bigint NOT NULL DEFAULT 1,
  `content_id` bigint,
  `title` varchar(255) NOT NULL,
  `description` text,
  `action_type` varchar(64) NOT NULL DEFAULT 'none',
  `action_target` varchar(255),
  `sort_order` int NOT NULL DEFAULT 0,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  PRIMARY KEY (help_tree_id)
);

CREATE INDEX {{prefix}}lupo_help_tree_idx_action ON {{prefix}}help_tree (action_type, action_target);
CREATE INDEX {{prefix}}lupo_help_tree_idx_content ON {{prefix}}help_tree (content_id);
CREATE INDEX {{prefix}}lupo_help_tree_idx_created ON {{prefix}}help_tree (created_ymdhis);
CREATE INDEX {{prefix}}lupo_help_tree_idx_department ON {{prefix}}help_tree (department_id);
CREATE INDEX {{prefix}}lupo_help_tree_idx_parent ON {{prefix}}help_tree (parent_id);
CREATE INDEX {{prefix}}lupo_help_tree_idx_sort ON {{prefix}}help_tree (parent_id, sort_order);
CREATE INDEX {{prefix}}lupo_help_tree_idx_updated ON {{prefix}}help_tree (updated_ymdhis);

-- lupo_human_request_context
CREATE TABLE {{prefix}}human_request_context (
  `context_id` bigint NOT NULL,
  `request_id` bigint NOT NULL,
  `context_type` varchar(64) NOT NULL,
  `content` text NOT NULL,
  `source_artifact_path` varchar(512),
  `source_line_range` varchar(64),
  `created_ymdhis` bigint NOT NULL,
  PRIMARY KEY (context_id)
);

CREATE INDEX {{prefix}}idx_request_context ON {{prefix}}human_request_context (request_id);

-- lupo_human_request_responses
CREATE TABLE {{prefix}}human_request_responses (
  `response_id` bigint NOT NULL,
  `request_id` bigint NOT NULL,
  `auth_user_id` bigint NOT NULL,
  `actor_id` bigint NOT NULL,
  `response_type` varchar(64) NOT NULL,
  `response_text` text NOT NULL,
  `reasoning` text,
  `decision` varchar(64),
  `conditions` text,
  `response_ymdhis` bigint NOT NULL,
  `is_deleted` tinyint DEFAULT 0,
  `deleted_ymdhis` bigint DEFAULT 0,
  PRIMARY KEY (response_id)
);

CREATE INDEX {{prefix}}idx_response_request ON {{prefix}}human_request_responses (request_id);
CREATE INDEX {{prefix}}idx_response_user ON {{prefix}}human_request_responses (auth_user_id, response_ymdhis);

-- lupo_human_requests
CREATE TABLE {{prefix}}human_requests (
  `request_id` bigint NOT NULL,
  `thread_id` bigint NOT NULL,
  `channel_id` bigint NOT NULL,
  `project_id` bigint NOT NULL DEFAULT 0,
  `initiator_actor_id` bigint NOT NULL,
  `target_auth_user_id` bigint NOT NULL,
  `request_type` varchar(64) NOT NULL,
  `request_title` varchar(255) NOT NULL,
  `request_description` text NOT NULL,
  `subject_type` varchar(64),
  `subject_reference` varchar(255),
  `priority` varchar(64) DEFAULT 'normal',
  `request_mode` varchar(64) DEFAULT 'single_human',
  `status` varchar(64) NOT NULL DEFAULT 'pending',
  `response_text` text,
  `response_auth_user_id` bigint,
  `response_actor_id` bigint,
  `created_ymdhis` bigint NOT NULL,
  `updated_ymdhis` bigint NOT NULL,
  `answered_ymdhis` bigint,
  `resolved_ymdhis` bigint DEFAULT 0,
  `expires_ymdhis` bigint DEFAULT 0,
  `is_deleted` tinyint DEFAULT 0,
  `deleted_ymdhis` bigint DEFAULT 0,
  PRIMARY KEY (request_id)
);

CREATE INDEX {{prefix}}idx_initiator_actor ON {{prefix}}human_requests (initiator_actor_id, created_ymdhis);
CREATE INDEX {{prefix}}idx_priority_status ON {{prefix}}human_requests (priority, status, created_ymdhis);
CREATE INDEX {{prefix}}idx_status_expires ON {{prefix}}human_requests (status, expires_ymdhis);
CREATE INDEX {{prefix}}idx_target_user_status ON {{prefix}}human_requests (target_auth_user_id, status);
CREATE INDEX {{prefix}}idx_thread_requests ON {{prefix}}human_requests (thread_id, created_ymdhis);

-- lupo_identity_context
CREATE TABLE {{prefix}}identity_context (
  `identity_context_id` bigint NOT NULL,
  `actor_id` bigint NOT NULL,
  `session_id` varchar(100),
  `channel_id` bigint,
  `active_layer_key` varchar(64) NOT NULL DEFAULT 'runtime',
  `context_snapshot_json` json,
  `activated_ymdhis` bigint NOT NULL,
  `expires_ymdhis` bigint,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  PRIMARY KEY (identity_context_id)
);

CREATE INDEX {{prefix}}lupo_identity_context_idx_activated ON {{prefix}}identity_context (activated_ymdhis);
CREATE INDEX {{prefix}}lupo_identity_context_idx_actor ON {{prefix}}identity_context (actor_id);
CREATE INDEX {{prefix}}lupo_identity_context_idx_channel ON {{prefix}}identity_context (channel_id);
CREATE INDEX {{prefix}}lupo_identity_context_idx_deleted ON {{prefix}}identity_context (is_deleted);
CREATE INDEX {{prefix}}lupo_identity_context_idx_layer ON {{prefix}}identity_context (active_layer_key);
CREATE INDEX {{prefix}}lupo_identity_context_idx_session ON {{prefix}}identity_context (session_id);

-- lupo_identity_layers
CREATE TABLE {{prefix}}identity_layers (
  `identity_layer_id` bigint NOT NULL,
  `layer_key` varchar(64) NOT NULL,
  `layer_name` varchar(255) NOT NULL,
  `layer_type` varchar(32) NOT NULL,
  `description` text,
  `is_mutable` tinyint NOT NULL DEFAULT 1,
  `notes` text,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  PRIMARY KEY (identity_layer_id)
);

CREATE UNIQUE INDEX {{prefix}}lupo_identity_layers_unq_key ON {{prefix}}identity_layers (layer_key);

-- lupo_interpretation_log
CREATE TABLE {{prefix}}interpretation_log (
  `interpretation_log_id` bigint NOT NULL,
  `agent_id` bigint NOT NULL,
  `entity_type` varchar(32) NOT NULL,
  `entity_id` bigint NOT NULL,
  `interpretation` text NOT NULL,
  `confidence_score` decimal(5,2),
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  `metadata_json` json,
  PRIMARY KEY (interpretation_log_id)
);

CREATE INDEX {{prefix}}lupo_interpretation_log_idx_agent ON {{prefix}}interpretation_log (agent_id);
CREATE INDEX {{prefix}}lupo_interpretation_log_idx_confidence ON {{prefix}}interpretation_log (confidence_score);
CREATE INDEX {{prefix}}lupo_interpretation_log_idx_created ON {{prefix}}interpretation_log (created_ymdhis);
CREATE INDEX {{prefix}}lupo_interpretation_log_idx_deleted ON {{prefix}}interpretation_log (is_deleted);
CREATE INDEX {{prefix}}lupo_interpretation_log_idx_entity ON {{prefix}}interpretation_log (entity_type, entity_id);
CREATE INDEX {{prefix}}lupo_interpretation_log_idx_updated ON {{prefix}}interpretation_log (updated_ymdhis);

-- lupo_labs_declarations
CREATE TABLE {{prefix}}labs_declarations (
  `labs_declaration_id` bigint NOT NULL,
  `actor_id` bigint NOT NULL,
  `certificate_id` varchar(64) NOT NULL,
  `declaration_timestamp` bigint NOT NULL,
  `declarations_json` json NOT NULL,
  `validation_status` varchar(64) NOT NULL DEFAULT 'valid',
  `labs_version` varchar(16) NOT NULL DEFAULT '1.0',
  `next_revalidation_ymdhis` bigint NOT NULL,
  `validation_log_json` json,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  PRIMARY KEY (labs_declaration_id)
);

CREATE INDEX {{prefix}}lupo_labs_declarations_idx_actor_id ON {{prefix}}labs_declarations (actor_id);
CREATE INDEX {{prefix}}lupo_labs_declarations_idx_actor_status ON {{prefix}}labs_declarations (actor_id, validation_status, is_deleted);
CREATE INDEX {{prefix}}lupo_labs_declarations_idx_certificate_id ON {{prefix}}labs_declarations (certificate_id);
CREATE INDEX {{prefix}}lupo_labs_declarations_idx_next_revalidation ON {{prefix}}labs_declarations (next_revalidation_ymdhis);
CREATE INDEX {{prefix}}lupo_labs_declarations_idx_revalidation_due ON {{prefix}}labs_declarations (next_revalidation_ymdhis, validation_status, is_deleted);
CREATE INDEX {{prefix}}lupo_labs_declarations_idx_validation_status ON {{prefix}}labs_declarations (validation_status);

-- lupo_labs_violations
CREATE TABLE {{prefix}}labs_violations (
  `labs_violation_id` bigint NOT NULL,
  `actor_id` bigint NOT NULL,
  `certificate_id` varchar(64) NOT NULL,
  `violation_code` varchar(64) NOT NULL,
  `violation_description` text,
  `violation_metadata` json,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  PRIMARY KEY (labs_violation_id)
);

CREATE INDEX {{prefix}}lupo_labs_violations_idx_actor ON {{prefix}}labs_violations (actor_id);
CREATE INDEX {{prefix}}lupo_labs_violations_idx_certificate ON {{prefix}}labs_violations (certificate_id);
CREATE INDEX {{prefix}}lupo_labs_violations_idx_created ON {{prefix}}labs_violations (created_ymdhis);
CREATE INDEX {{prefix}}lupo_labs_violations_idx_deleted ON {{prefix}}labs_violations (is_deleted);
CREATE INDEX {{prefix}}lupo_labs_violations_idx_violation_code ON {{prefix}}labs_violations (violation_code);

-- lupo_legacy_content_mapping
CREATE TABLE {{prefix}}legacy_content_mapping (
  `mapping_id` bigint NOT NULL,
  `legacy_url` varchar(255) NOT NULL,
  `semantic_url` varchar(255) NOT NULL,
  `content_type` varchar(64) NOT NULL,
  `content_id` bigint,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL,
  `is_active` tinyint NOT NULL DEFAULT 1,
  PRIMARY KEY (mapping_id)
);

CREATE INDEX {{prefix}}lupo_legacy_content_mapping_idx_content_id ON {{prefix}}legacy_content_mapping (content_id);
CREATE INDEX {{prefix}}lupo_legacy_content_mapping_idx_content_type ON {{prefix}}legacy_content_mapping (content_type);
CREATE INDEX {{prefix}}lupo_legacy_content_mapping_idx_created ON {{prefix}}legacy_content_mapping (created_ymdhis);
CREATE INDEX {{prefix}}lupo_legacy_content_mapping_idx_is_active ON {{prefix}}legacy_content_mapping (is_active);
CREATE INDEX {{prefix}}lupo_legacy_content_mapping_idx_semantic_url ON {{prefix}}legacy_content_mapping (semantic_url);
CREATE UNIQUE INDEX {{prefix}}lupo_legacy_content_mapping_uk_legacy_url ON {{prefix}}legacy_content_mapping (legacy_url);

-- lupo_magic_link_tokens
CREATE TABLE {{prefix}}magic_link_tokens (
  `magic_link_token_id` bigint NOT NULL,
  `email` varchar(255) NOT NULL,
  `token` char(64) NOT NULL,
  `expires_ymdhis` bigint NOT NULL,
  `used` tinyint NOT NULL DEFAULT 0,
  `created_ymdhis` bigint NOT NULL,
  PRIMARY KEY (magic_link_token_id)
);

CREATE INDEX {{prefix}}idx_email ON {{prefix}}magic_link_tokens (email);
CREATE INDEX {{prefix}}idx_expires ON {{prefix}}magic_link_tokens (expires_ymdhis);
CREATE UNIQUE INDEX {{prefix}}idx_token ON {{prefix}}magic_link_tokens (token);

-- lupo_memory_edges
CREATE TABLE {{prefix}}memory_edges (
  `memory_edge_id` bigint NOT NULL,
  `from_memory_node_id` bigint NOT NULL,
  `to_memory_node_id` bigint NOT NULL,
  `edge_type` varchar(64) NOT NULL,
  `edge_context` varchar(32) NOT NULL DEFAULT 'system_generated',
  `edge_status` varchar(32) NOT NULL DEFAULT 'supported',
  `edge_direction` varchar(16) NOT NULL DEFAULT 'unidirectional',
  `weight_hundredths` int NOT NULL DEFAULT 100,
  `provenance_actor_id` bigint NOT NULL,
  `provenance_tool` varchar(64) NOT NULL,
  `review_reason` varchar(64),
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL DEFAULT 0,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint NOT NULL DEFAULT 0,
  PRIMARY KEY (memory_edge_id)
);

CREATE INDEX {{prefix}}lupo_memory_edges_idx_from ON {{prefix}}memory_edges (from_memory_node_id, is_deleted);
CREATE INDEX {{prefix}}lupo_memory_edges_idx_to ON {{prefix}}memory_edges (to_memory_node_id, is_deleted);
CREATE INDEX {{prefix}}lupo_memory_edges_idx_type ON {{prefix}}memory_edges (edge_type, edge_context, edge_status);

-- lupo_memory_nodes
CREATE TABLE {{prefix}}memory_nodes (
  `memory_node_id` bigint NOT NULL,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `owner_actor_id` bigint NOT NULL,
  `owner_type` varchar(32) NOT NULL DEFAULT 'actor',
  `memory_type` varchar(32) NOT NULL,
  `memory_key` varchar(255) NOT NULL,
  `memory_value` text,
  `context` varchar(32) NOT NULL DEFAULT 'experiential',
  `status` varchar(32) NOT NULL DEFAULT 'unsupported',
  `review_reason` varchar(64),
  `content_hash` char(64) NOT NULL,
  `context_json` json,
  `updated_ymdhis` bigint NOT NULL DEFAULT 0,
  `expires_ymdhis` bigint NOT NULL DEFAULT 0,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint NOT NULL DEFAULT 0,
  `embedding_vector` json COMMENT 'Vector embedding for similarity search (JSON array of floats or binary)',
  `has_vector_index` tinyint NOT NULL DEFAULT 0 COMMENT 'Flag for vector index existence',
  PRIMARY KEY (memory_node_id)
);

CREATE INDEX {{prefix}}lupo_memory_nodes_idx_created ON {{prefix}}memory_nodes (created_ymdhis, is_deleted);
CREATE INDEX {{prefix}}lupo_memory_nodes_idx_expires ON {{prefix}}memory_nodes (expires_ymdhis, is_deleted);
CREATE INDEX {{prefix}}lupo_memory_nodes_idx_key ON {{prefix}}memory_nodes (memory_key, owner_actor_id);
CREATE INDEX {{prefix}}lupo_memory_nodes_idx_owner ON {{prefix}}memory_nodes (owner_actor_id, owner_type, is_deleted);
CREATE INDEX {{prefix}}lupo_memory_nodes_idx_type ON {{prefix}}memory_nodes (memory_type, status, is_deleted);
CREATE INDEX {{prefix}}lupo_memory_nodes_idx_updated ON {{prefix}}memory_nodes (updated_ymdhis, is_deleted);

-- lupo_memory_rollups
CREATE TABLE {{prefix}}memory_rollups (
  `memory_rollup_id` bigint NOT NULL,
  `actor_id` int NOT NULL,
  `summary` text NOT NULL,
  `source_event_ids` text NOT NULL,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  PRIMARY KEY (memory_rollup_id)
);

CREATE INDEX {{prefix}}lupo_memory_rollups_idx_actor_created ON {{prefix}}memory_rollups (actor_id, created_ymdhis);

-- lupo_metadata
CREATE TABLE {{prefix}}metadata (
  `metadata_id` bigint NOT NULL,
  `entity_type` varchar(32) NOT NULL,
  `entity_id` bigint NOT NULL,
  `domain_id` bigint,
  `meta_type` varchar(64),
  `property_key` varchar(255) NOT NULL,
  `property_value` text,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  `channel_id` bigint,
  `parent_metadata_id` bigint,
  `class_name` varchar(128),
  `schema_ref` varchar(64),
  PRIMARY KEY (metadata_id)
);

CREATE INDEX {{prefix}}lupo_metadata_idx_channel_deleted ON {{prefix}}metadata (channel_id, is_deleted);
CREATE INDEX {{prefix}}lupo_metadata_idx_channel_id ON {{prefix}}metadata (channel_id);
CREATE INDEX {{prefix}}lupo_metadata_idx_class_deleted ON {{prefix}}metadata (class_name, is_deleted);
CREATE INDEX {{prefix}}lupo_metadata_idx_class_name ON {{prefix}}metadata (class_name);
CREATE INDEX {{prefix}}lupo_metadata_idx_created_ymdhis ON {{prefix}}metadata (created_ymdhis);
CREATE INDEX {{prefix}}lupo_metadata_idx_domain ON {{prefix}}metadata (domain_id);
CREATE INDEX {{prefix}}lupo_metadata_idx_entity ON {{prefix}}metadata (entity_type, entity_id);
CREATE INDEX {{prefix}}lupo_metadata_idx_entity_deleted ON {{prefix}}metadata (entity_type, entity_id, is_deleted);
CREATE INDEX {{prefix}}lupo_metadata_idx_is_deleted ON {{prefix}}metadata (is_deleted);
CREATE INDEX {{prefix}}lupo_metadata_idx_meta_type ON {{prefix}}metadata (meta_type);
CREATE INDEX {{prefix}}lupo_metadata_idx_meta_type_deleted ON {{prefix}}metadata (meta_type, is_deleted);
CREATE INDEX {{prefix}}lupo_metadata_idx_parent_deleted ON {{prefix}}metadata (parent_metadata_id, is_deleted);
CREATE INDEX {{prefix}}lupo_metadata_idx_parent_metadata_id ON {{prefix}}metadata (parent_metadata_id);
CREATE INDEX {{prefix}}lupo_metadata_idx_property_key ON {{prefix}}metadata (property_key);
CREATE INDEX {{prefix}}lupo_metadata_idx_updated_ymdhis ON {{prefix}}metadata (updated_ymdhis);
CREATE UNIQUE INDEX {{prefix}}lupo_metadata_unique_entity_domain_property ON {{prefix}}metadata (entity_type, entity_id, domain_id, property_key);

-- lupo_modules
CREATE TABLE {{prefix}}modules (
  `module_id` bigint NOT NULL,
  `module_key` varchar(100) NOT NULL,
  `module_name` varchar(150) NOT NULL,
  `namespace` varchar(100) NOT NULL,
  `version` varchar(50) NOT NULL,
  `version_code` int NOT NULL,
  `minimum_core_version` varchar(50) NOT NULL,
  `user_path` varchar(255),
  `admin_path` varchar(255),
  `api_path` varchar(255),
  `route_params` text,
  `description` text,
  `author` varchar(100),
  `website` varchar(255),
  `icon` varchar(100) DEFAULT 'puzzle-piece',
  `dependencies` text,
  `conflicts` text,
  `config_json` text NOT NULL,
  `is_system` tinyint NOT NULL DEFAULT 0,
  `is_active` tinyint NOT NULL DEFAULT 0,
  `federation_node_id` bigint NOT NULL DEFAULT 1,
  `settings` text,
  `installed_ymdhis` bigint,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  PRIMARY KEY (module_id)
);

CREATE INDEX {{prefix}}lupo_modules_idx_installed ON {{prefix}}modules (installed_ymdhis);
CREATE INDEX {{prefix}}lupo_modules_idx_namespace ON {{prefix}}modules (namespace);
CREATE INDEX {{prefix}}lupo_modules_idx_status ON {{prefix}}modules (is_active, is_deleted);
CREATE INDEX {{prefix}}lupo_modules_idx_system ON {{prefix}}modules (is_system);
CREATE UNIQUE INDEX {{prefix}}lupo_modules_uq_module_key ON {{prefix}}modules (module_key);

-- lupo_notifications
CREATE TABLE {{prefix}}notifications (
  `notification_id` bigint NOT NULL,
  `actor_id` bigint NOT NULL,
  `from_actor_id` bigint,
  `to_actor_id` bigint,
  `channel_id` bigint,
  `notification_type` varchar(64) NOT NULL,
  `title` varchar(255),
  `message` text,
  `link_url` varchar(255),
  `is_read` tinyint NOT NULL DEFAULT 0,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  PRIMARY KEY (notification_id)
);


-- lupo_orchestrator_rules
CREATE TABLE {{prefix}}orchestrator_rules (
  `rule_id` bigint NOT NULL,
  `rule_slug` varchar(128) NOT NULL,
  `orchestrator_actor` varchar(64) NOT NULL,
  `rule_set_version` varchar(32) NOT NULL,
  `applies_to_json` text NOT NULL,
  `enforcement_level` varchar(32) NOT NULL DEFAULT 'strict',
  `rule_content` text NOT NULL,
  `checksum` varchar(64) NOT NULL,
  `is_active` tinyint NOT NULL DEFAULT 1,
  `updated_ymdhis` bigint NOT NULL,
  PRIMARY KEY (rule_id)
);

CREATE INDEX {{prefix}}lupo_orchestrator_rules_idx_active ON {{prefix}}orchestrator_rules (is_active);
CREATE INDEX {{prefix}}lupo_orchestrator_rules_idx_actor_version ON {{prefix}}orchestrator_rules (orchestrator_actor, rule_set_version);
CREATE INDEX {{prefix}}lupo_orchestrator_rules_idx_updated ON {{prefix}}orchestrator_rules (updated_ymdhis);
CREATE UNIQUE INDEX {{prefix}}lupo_orchestrator_rules_uniq_slug ON {{prefix}}orchestrator_rules (rule_slug);

-- lupo_pairing_rules
CREATE TABLE {{prefix}}pairing_rules (
  `pairing_rule_id` bigint NOT NULL,
  `rule_key` varchar(100) NOT NULL,
  `rule_type` varchar(64) NOT NULL,
  `actor_type_a` varchar(64),
  `actor_type_b` varchar(64),
  `condition_json` json,
  `priority` int NOT NULL DEFAULT 100,
  `is_active` tinyint NOT NULL DEFAULT 1,
  `notes` text,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL DEFAULT 0,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  PRIMARY KEY (pairing_rule_id)
);

CREATE INDEX {{prefix}}lupo_pairing_rules_idx_active ON {{prefix}}pairing_rules (is_active);
CREATE INDEX {{prefix}}lupo_pairing_rules_idx_deleted ON {{prefix}}pairing_rules (is_deleted);
CREATE INDEX {{prefix}}lupo_pairing_rules_idx_type ON {{prefix}}pairing_rules (rule_type);
CREATE UNIQUE INDEX {{prefix}}lupo_pairing_rules_unq_key ON {{prefix}}pairing_rules (rule_key);

-- lupo_password_resets
CREATE TABLE {{prefix}}password_resets (
  `password_reset_id` bigint NOT NULL,
  `auth_user_id` bigint NOT NULL,
  `token` varchar(64) NOT NULL,
  `expiry_ymdhis` bigint NOT NULL COMMENT 'YYYYMMDDHHIISS format',
  `created_ymdhis` bigint NOT NULL COMMENT 'YYYYMMDDHHIISS format',
  `updated_ymdhis` bigint COMMENT 'YYYYMMDDHHIISS format',
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  PRIMARY KEY (password_reset_id)
);

CREATE INDEX {{prefix}}lupo_password_resets_idx_auth_user_id ON {{prefix}}password_resets (auth_user_id);
CREATE INDEX {{prefix}}lupo_password_resets_idx_created_ymdhis ON {{prefix}}password_resets (created_ymdhis);
CREATE INDEX {{prefix}}lupo_password_resets_idx_expiry_ymdhis ON {{prefix}}password_resets (expiry_ymdhis);
CREATE UNIQUE INDEX {{prefix}}lupo_password_resets_unique_token ON {{prefix}}password_resets (token);

-- lupo_paths
CREATE TABLE {{prefix}}paths (
  `path_id` bigint NOT NULL,
  `entercontentid` bigint,
  `exitcontentid` bigint,
  `enter_table` varchar(255),
  `exit_table` varchar(255),
  `year_num` int,
  `month_num` int,
  `day_num` int,
  `count_num` int NOT NULL DEFAULT 0,
  `transition_type` varchar(64),
  `transition_metadata` text,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL DEFAULT 0,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  PRIMARY KEY (path_id)
);

CREATE INDEX {{prefix}}lupo_paths_idx_created ON {{prefix}}paths (created_ymdhis);
CREATE INDEX {{prefix}}lupo_paths_idx_enter_exit ON {{prefix}}paths (entercontentid, exitcontentid);
CREATE INDEX {{prefix}}lupo_paths_idx_is_deleted ON {{prefix}}paths (is_deleted);
CREATE INDEX {{prefix}}lupo_paths_idx_transition ON {{prefix}}paths (transition_type);
CREATE INDEX {{prefix}}lupo_paths_idx_ymd ON {{prefix}}paths (year_num, month_num, day_num);

-- lupo_paths_daily
CREATE TABLE {{prefix}}paths_daily (
  `path_daily_id` bigint NOT NULL,
  `from_url` varchar(2000),
  `to_url` varchar(2000),
  `from_content_id` bigint,
  `to_content_id` bigint,
  `count_num` int NOT NULL DEFAULT 0,
  `date_ymd` int NOT NULL,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL DEFAULT 0,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  `hit_count` int NOT NULL DEFAULT 1,
  `unique_actors` int NOT NULL DEFAULT 0,
  PRIMARY KEY (path_daily_id)
);

CREATE INDEX {{prefix}}lupo_paths_daily_idx_date ON {{prefix}}paths_daily (date_ymd);
CREATE INDEX {{prefix}}lupo_paths_daily_idx_from_content ON {{prefix}}paths_daily (from_content_id, date_ymd);
CREATE INDEX {{prefix}}lupo_paths_daily_idx_is_deleted ON {{prefix}}paths_daily (is_deleted);
CREATE INDEX {{prefix}}lupo_paths_daily_idx_to_content ON {{prefix}}paths_daily (to_content_id, date_ymd);

-- lupo_paths_monthly
CREATE TABLE {{prefix}}paths_monthly (
  `path_monthly_id` bigint NOT NULL,
  `from_url` varchar(2000),
  `to_url` varchar(2000),
  `from_content_id` bigint,
  `to_content_id` bigint,
  `count_num` int NOT NULL DEFAULT 0,
  `date_ym` int NOT NULL,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL DEFAULT 0,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  PRIMARY KEY (path_monthly_id)
);

CREATE INDEX {{prefix}}lupo_paths_monthly_idx_date_ym ON {{prefix}}paths_monthly (date_ym);
CREATE INDEX {{prefix}}lupo_paths_monthly_idx_from_content ON {{prefix}}paths_monthly (from_content_id, date_ym);
CREATE INDEX {{prefix}}lupo_paths_monthly_idx_is_deleted ON {{prefix}}paths_monthly (is_deleted);
CREATE INDEX {{prefix}}lupo_paths_monthly_idx_to_content ON {{prefix}}paths_monthly (to_content_id, date_ym);

-- lupo_paths_raw
CREATE TABLE {{prefix}}paths_raw (
  `path_raw_id` bigint NOT NULL,
  `from_url` varchar(2000),
  `to_url` varchar(2000),
  `session_id` bigint,
  `actor_id` bigint,
  `referrer_url` varchar(2000),
  `user_agent` varchar(500),
  `ip_hash` varchar(128),
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `is_aggregated` tinyint NOT NULL DEFAULT 0,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  PRIMARY KEY (path_raw_id)
);

CREATE INDEX {{prefix}}lupo_paths_raw_idx_created_agg ON {{prefix}}paths_raw (created_ymdhis, is_aggregated);
CREATE INDEX {{prefix}}lupo_paths_raw_idx_is_deleted ON {{prefix}}paths_raw (is_deleted);
CREATE INDEX {{prefix}}lupo_paths_raw_idx_session ON {{prefix}}paths_raw (session_id);

-- lupo_paths_summary
CREATE TABLE {{prefix}}paths_summary (
  `summary_id` bigint NOT NULL,
  `path_id` bigint NOT NULL,
  `total_count` bigint NOT NULL DEFAULT 0,
  `last_used_ymdhis` bigint NOT NULL DEFAULT 0,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL DEFAULT 0,
  PRIMARY KEY (summary_id)
);

CREATE INDEX {{prefix}}lupo_paths_summary_idx_path ON {{prefix}}paths_summary (path_id);

-- lupo_permissions
CREATE TABLE {{prefix}}permissions (
  `permission_id` bigint NOT NULL,
  `target_type` varchar(64) NOT NULL,
  `target_id` bigint NOT NULL,
  `user_id` bigint,
  `department_id` bigint,
  `permission` varchar(64) NOT NULL DEFAULT 'read',
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  PRIMARY KEY (permission_id)
);

CREATE INDEX {{prefix}}lupo_permissions_idx_created_ymdhis ON {{prefix}}permissions (created_ymdhis);
CREATE INDEX {{prefix}}lupo_permissions_idx_deleted ON {{prefix}}permissions (is_deleted, deleted_ymdhis);
CREATE INDEX {{prefix}}lupo_permissions_idx_department ON {{prefix}}permissions (department_id);
CREATE INDEX {{prefix}}lupo_permissions_idx_permission ON {{prefix}}permissions (permission);
CREATE INDEX {{prefix}}lupo_permissions_idx_target ON {{prefix}}permissions (target_type, target_id);
CREATE INDEX {{prefix}}lupo_permissions_idx_user ON {{prefix}}permissions (user_id);
CREATE UNIQUE INDEX {{prefix}}lupo_permissions_uniq_target_department ON {{prefix}}permissions (target_type, target_id, department_id);
CREATE UNIQUE INDEX {{prefix}}lupo_permissions_uniq_target_user ON {{prefix}}permissions (target_type, target_id, user_id);

-- lupo_projects
CREATE TABLE {{prefix}}projects (
  `project_id` bigint NOT NULL,
  `project_key` varchar(64) NOT NULL,
  `project_slug` varchar(255) NOT NULL,
  `project_name` varchar(255) NOT NULL,
  `federation_node_id` bigint NOT NULL,
  `default_channel_id` bigint,
  `orchestrator_id` bigint NOT NULL,
  `project_type` varchar(64) DEFAULT 'standard',
  `description` text,
  `github_repository` varchar(512),
  `status` varchar(32) NOT NULL DEFAULT 'active',
  `is_active` tinyint NOT NULL DEFAULT 1,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `is_archived` tinyint NOT NULL DEFAULT 0,
  `is_frozen` tinyint NOT NULL DEFAULT 0,
  `metadata_json` json,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint DEFAULT 0,
  `created_by_actor_id` bigint,
  `updated_by_actor_id` bigint,
  PRIMARY KEY (project_id)
);

CREATE INDEX {{prefix}}lupo_projects_idx_created ON {{prefix}}projects (created_ymdhis);
CREATE INDEX {{prefix}}lupo_projects_idx_default_channel ON {{prefix}}projects (default_channel_id);
CREATE INDEX {{prefix}}lupo_projects_idx_federation_node ON {{prefix}}projects (federation_node_id, status, is_deleted);
CREATE INDEX {{prefix}}lupo_projects_idx_orchestrator ON {{prefix}}projects (orchestrator_id, status, is_deleted);
CREATE INDEX {{prefix}}lupo_projects_idx_project_key ON {{prefix}}projects (project_key, federation_node_id);
CREATE INDEX {{prefix}}lupo_projects_idx_project_slug ON {{prefix}}projects (project_slug, federation_node_id);
CREATE INDEX {{prefix}}lupo_projects_idx_status ON {{prefix}}projects (status, is_active, is_deleted);
CREATE INDEX {{prefix}}lupo_projects_idx_updated ON {{prefix}}projects (updated_ymdhis);
CREATE UNIQUE INDEX {{prefix}}uk_project_key_node ON {{prefix}}projects (project_key, federation_node_id);
CREATE UNIQUE INDEX {{prefix}}uk_project_slug_node ON {{prefix}}projects (project_slug, federation_node_id);

-- lupo_reference_links
CREATE TABLE {{prefix}}reference_links (
  `reference_link_id` bigint NOT NULL,
  `reference_id` bigint NOT NULL,
  `object_type` varchar(64) NOT NULL,
  `object_id` bigint NOT NULL,
  `sort_order` int NOT NULL DEFAULT 0,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  PRIMARY KEY (reference_link_id)
);

CREATE INDEX {{prefix}}lupo_reference_links_idx_is_deleted ON {{prefix}}reference_links (is_deleted);
CREATE INDEX {{prefix}}lupo_reference_links_idx_object ON {{prefix}}reference_links (object_type, object_id);
CREATE INDEX {{prefix}}lupo_reference_links_idx_reference ON {{prefix}}reference_links (reference_id);

-- lupo_reference_map
CREATE TABLE {{prefix}}reference_map (
  `reference_map_id` bigint NOT NULL,
  `reference_id` bigint NOT NULL,
  `target_type` varchar(64) NOT NULL,
  `target_id` bigint NOT NULL,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  PRIMARY KEY (reference_map_id)
);

CREATE INDEX {{prefix}}lupo_reference_map_idx_reference ON {{prefix}}reference_map (reference_id);
CREATE INDEX {{prefix}}lupo_reference_map_idx_target ON {{prefix}}reference_map (target_type, target_id);

-- lupo_reference_objects
CREATE TABLE {{prefix}}reference_objects (
  `reference_object_id` bigint NOT NULL,
  `object_type` varchar(50) NOT NULL,
  `object_slug` varchar(255) NOT NULL,
  `object_label` varchar(255),
  `meta_json` json,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL DEFAULT 0,
  PRIMARY KEY (reference_object_id)
);

CREATE INDEX {{prefix}}lupo_reference_objects_idx_is_deleted ON {{prefix}}reference_objects (is_deleted);
CREATE INDEX {{prefix}}lupo_reference_objects_idx_object_slug ON {{prefix}}reference_objects (object_slug);
CREATE INDEX {{prefix}}lupo_reference_objects_idx_type_slug ON {{prefix}}reference_objects (object_type, object_slug);

-- lupo_references
CREATE TABLE {{prefix}}references (
  `reference_id` bigint NOT NULL,
  `source_entity_type` varchar(64) NOT NULL,
  `source_entity_id` bigint NOT NULL,
  `url` varchar(2000),
  `title` varchar(500),
  `citation_text` text,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL DEFAULT 0,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  PRIMARY KEY (reference_id)
);

CREATE INDEX {{prefix}}lupo_references_idx_created ON {{prefix}}references (created_ymdhis);
CREATE INDEX {{prefix}}lupo_references_idx_is_deleted ON {{prefix}}references (is_deleted);
CREATE INDEX {{prefix}}lupo_references_idx_source ON {{prefix}}references (source_entity_type, source_entity_id);

-- lupo_referers
CREATE TABLE {{prefix}}referers (
  `referer_id` bigint NOT NULL,
  `content_id` bigint NOT NULL,
  `actor_id` bigint NOT NULL,
  `referer_url` varchar(2000),
  `referer_domain` varchar(255),
  `referer_path` varchar(2000),
  `referer_content_id` bigint,
  `date_ymd` int NOT NULL,
  `visits` int NOT NULL DEFAULT 1,
  `depth` int NOT NULL DEFAULT 0,
  `metadata_json` json,
  PRIMARY KEY (referer_id)
);

CREATE INDEX {{prefix}}lupo_referers_idx_actor_id ON {{prefix}}referers (actor_id);
CREATE INDEX {{prefix}}lupo_referers_idx_content_id ON {{prefix}}referers (content_id);
CREATE INDEX {{prefix}}lupo_referers_idx_date ON {{prefix}}referers (date_ymd);
CREATE INDEX {{prefix}}lupo_referers_idx_referer_content_id ON {{prefix}}referers (referer_content_id);
CREATE INDEX {{prefix}}lupo_referers_idx_referer_domain ON {{prefix}}referers (referer_domain);

-- lupo_referers_daily
CREATE TABLE {{prefix}}referers_daily (
  `referers_daily_id` bigint NOT NULL,
  `actor_id` bigint,
  `referer_domain` varchar(255),
  `visit_ymd` bigint NOT NULL,
  `visit_count` int DEFAULT 0,
  `created_ymdhis` bigint NOT NULL,
  `updated_ymdhis` bigint NOT NULL,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  `hit_count` int NOT NULL DEFAULT 1,
  `unique_actors` int NOT NULL DEFAULT 0,
  PRIMARY KEY (referers_daily_id)
);

CREATE INDEX {{prefix}}idx_actor_date ON {{prefix}}referers_daily (actor_id, visit_ymd);
CREATE INDEX {{prefix}}idx_referer_date ON {{prefix}}referers_daily (referer_domain, visit_ymd);
CREATE UNIQUE INDEX {{prefix}}unique_daily_referer ON {{prefix}}referers_daily (actor_id, referer_domain, visit_ymd);

-- lupo_referers_raw
CREATE TABLE {{prefix}}referers_raw (
  `referer_raw_id` bigint NOT NULL,
  `content_id` bigint,
  `page_url` varchar(2000),
  `referer_url` varchar(2000),
  `referer_domain` varchar(255),
  `session_id` bigint,
  `actor_id` bigint,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `is_aggregated` tinyint NOT NULL DEFAULT 0,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  PRIMARY KEY (referer_raw_id)
);

CREATE INDEX {{prefix}}lupo_referers_raw_idx_created_agg ON {{prefix}}referers_raw (created_ymdhis, is_aggregated);
CREATE INDEX {{prefix}}lupo_referers_raw_idx_domain ON {{prefix}}referers_raw (referer_domain);
CREATE INDEX {{prefix}}lupo_referers_raw_idx_is_deleted ON {{prefix}}referers_raw (is_deleted);
CREATE INDEX {{prefix}}lupo_referers_raw_idx_session ON {{prefix}}referers_raw (session_id);

-- lupo_registry
CREATE TABLE {{prefix}}registry (
  `registry_id` bigint NOT NULL,
  `entity_type` varchar(64) NOT NULL,
  `entity_index_id` bigint NOT NULL,
  `entity_index` bigint NOT NULL,
  `federation_node_id` bigint DEFAULT 1,
  `reserved_ymdhis` bigint NOT NULL DEFAULT 0,
  `entity_key` varchar(255) NOT NULL,
  `entity_name` varchar(255),
  `entity_table` varchar(255),
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL DEFAULT 0,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `is_active` tinyint NOT NULL DEFAULT 1,
  `is_kernel` tinyint NOT NULL DEFAULT 0,
  `metadata_json` text,
  PRIMARY KEY (registry_id)
);

CREATE INDEX {{prefix}}lupo_registry_idx_entity_index ON {{prefix}}registry (entity_index);
CREATE INDEX {{prefix}}lupo_registry_idx_entity_index_id ON {{prefix}}registry (entity_index_id);
CREATE INDEX {{prefix}}lupo_registry_idx_entity_key ON {{prefix}}registry (entity_key);
CREATE INDEX {{prefix}}lupo_registry_idx_entity_type ON {{prefix}}registry (entity_type);
CREATE INDEX {{prefix}}lupo_registry_idx_entity_type_deleted ON {{prefix}}registry (entity_type, is_deleted);

-- lupo_rolls
CREATE TABLE {{prefix}}rolls (
  `roll_id` bigint NOT NULL,
  `channel_id` bigint NOT NULL,
  `actor_id` bigint NOT NULL,
  `role_slug` varchar(100) NOT NULL,
  `permission_scope_json` json,
  `is_active` tinyint NOT NULL DEFAULT 1,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL,
  PRIMARY KEY (roll_id)
);

CREATE INDEX {{prefix}}lupo_rolls_idx_channel_actor ON {{prefix}}rolls (channel_id, actor_id);
CREATE INDEX {{prefix}}lupo_rolls_idx_role ON {{prefix}}rolls (role_slug);

-- lupo_routing_decisions
CREATE TABLE {{prefix}}routing_decisions (
  `routing_decision_id` bigint NOT NULL,
  `actor_id` bigint NOT NULL,
  `thread_id` bigint NOT NULL,
  `task_id` bigint,
  `routing_strategy` varchar(64) NOT NULL,
  `candidate_users_json` text NOT NULL,
  `selected_auth_user_id` bigint NOT NULL,
  `fallback_index` int NOT NULL DEFAULT 0,
  `decision_reason` text,
  `decision_status` varchar(32) NOT NULL,
  `trigger_type` varchar(64) NOT NULL,
  `created_ymdhis` bigint NOT NULL,
  `completed_ymdhis` bigint DEFAULT 0,
  `idempotency_key` varchar(40),
  PRIMARY KEY (routing_decision_id)
);

CREATE INDEX {{prefix}}lupo_routing_decisions_idx_loop_break ON {{prefix}}routing_decisions (actor_id, thread_id, trigger_type, created_ymdhis);
CREATE INDEX {{prefix}}lupo_routing_decisions_idx_selected_status ON {{prefix}}routing_decisions (selected_auth_user_id, decision_status, created_ymdhis);
CREATE INDEX {{prefix}}lupo_routing_decisions_idx_thread_created ON {{prefix}}routing_decisions (thread_id, created_ymdhis);
CREATE UNIQUE INDEX {{prefix}}lupo_routing_decisions_unq_idempotency ON {{prefix}}routing_decisions (idempotency_key);

-- lupo_rule_logs
CREATE TABLE {{prefix}}rule_logs (
  `rule_log_id` bigint NOT NULL,
  `rule_id` bigint NOT NULL,
  `target_table` varchar(255) NOT NULL,
  `target_id` bigint NOT NULL,
  `actor_id` bigint NOT NULL,
  `instance_id` bigint DEFAULT 0,
  `event_type` varchar(64) NOT NULL,
  `event_details` text,
  `created_ymdhis` bigint NOT NULL,
  PRIMARY KEY (rule_log_id)
);

CREATE INDEX {{prefix}}lupo_rule_logs_idx_actor_id ON {{prefix}}rule_logs (actor_id);
CREATE INDEX {{prefix}}lupo_rule_logs_idx_created_ymdhis ON {{prefix}}rule_logs (created_ymdhis);
CREATE INDEX {{prefix}}lupo_rule_logs_idx_rule_id ON {{prefix}}rule_logs (rule_id);
CREATE INDEX {{prefix}}lupo_rule_logs_idx_target ON {{prefix}}rule_logs (target_table, target_id);

-- lupo_rule_targets
CREATE TABLE {{prefix}}rule_targets (
  `rule_target_id` bigint NOT NULL,
  `rule_id` bigint NOT NULL,
  `target_table` varchar(255) NOT NULL,
  `target_id` bigint NOT NULL,
  `applied_by_actor_id` bigint,
  `priority` int NOT NULL DEFAULT 100,
  `created_ymdhis` bigint NOT NULL,
  `updated_ymdhis` bigint NOT NULL,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  PRIMARY KEY (rule_target_id)
);

CREATE INDEX {{prefix}}lupo_rule_targets_idx_is_deleted ON {{prefix}}rule_targets (is_deleted);
CREATE INDEX {{prefix}}lupo_rule_targets_idx_rule_target ON {{prefix}}rule_targets (rule_id, target_table, target_id);
CREATE INDEX {{prefix}}lupo_rule_targets_idx_target ON {{prefix}}rule_targets (target_table, target_id);

-- lupo_rules
CREATE TABLE {{prefix}}rules (
  `rule_id` bigint NOT NULL,
  `rule_name` varchar(255) NOT NULL,
  `rule_description` text,
  `rule_type` varchar(64) NOT NULL,
  `rule_script` text NOT NULL,
  `rule_version` bigint NOT NULL DEFAULT 1,
  `created_ymdhis` bigint NOT NULL,
  `updated_ymdhis` bigint NOT NULL,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  PRIMARY KEY (rule_id)
);

CREATE INDEX {{prefix}}lupo_rules_idx_is_deleted ON {{prefix}}rules (is_deleted);
CREATE INDEX {{prefix}}lupo_rules_idx_rule_name ON {{prefix}}rules (rule_name);
CREATE INDEX {{prefix}}lupo_rules_idx_rule_type ON {{prefix}}rules (rule_type);

-- lupo_schema_migrations
CREATE TABLE {{prefix}}schema_migrations (
  `schema_migration_id` bigint NOT NULL,
  `version` varchar(64) NOT NULL,
  `name` varchar(255) NOT NULL,
  `applied_ymdhis` bigint NOT NULL DEFAULT 0,
  PRIMARY KEY (schema_migration_id)
);

CREATE INDEX {{prefix}}lupo_schema_migrations_idx_applied ON {{prefix}}schema_migrations (applied_ymdhis);
CREATE UNIQUE INDEX {{prefix}}lupo_schema_migrations_unq_version ON {{prefix}}schema_migrations (version);

-- lupo_search_index
CREATE TABLE {{prefix}}search_index (
  `search_index_id` bigint NOT NULL,
  `domain_id` bigint NOT NULL,
  `entity_type` varchar(50) NOT NULL,
  `entity_id` bigint NOT NULL,
  `title_text` text,
  `body_text` text,
  `keywords_text` text,
  `search_metadata` text,
  `relevance_score` float DEFAULT 1,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL,
  PRIMARY KEY (search_index_id)
);

CREATE INDEX {{prefix}}lupo_search_index_idx_domain_type ON {{prefix}}search_index (domain_id, entity_type);
CREATE INDEX {{prefix}}lupo_search_index_idx_entity_reference ON {{prefix}}search_index (entity_type, entity_id);
CREATE INDEX {{prefix}}lupo_search_index_idx_is_deleted ON {{prefix}}search_index (is_deleted);
CREATE INDEX {{prefix}}lupo_search_index_idx_relevance ON {{prefix}}search_index (relevance_score);
CREATE INDEX {{prefix}}lupo_search_index_idx_updated ON {{prefix}}search_index (updated_ymdhis);
CREATE UNIQUE INDEX {{prefix}}lupo_search_index_unique_entity ON {{prefix}}search_index (domain_id, entity_type, entity_id);

-- lupo_search_rebuild_log
CREATE TABLE {{prefix}}search_rebuild_log (
  `search_rebuild_log_id` bigint NOT NULL,
  `entity_type` varchar(50) NOT NULL,
  `entity_id` bigint NOT NULL,
  `action` varchar(64) NOT NULL,
  `status` varchar(64) NOT NULL DEFAULT 'pending',
  `attempts` tinyint NOT NULL DEFAULT 0,
  `last_error` text,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `processed_ymdhis` bigint,
  `next_attempt_ymdhis` bigint,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  PRIMARY KEY (search_rebuild_log_id)
);

CREATE INDEX {{prefix}}lupo_search_rebuild_log_idx_created ON {{prefix}}search_rebuild_log (created_ymdhis);
CREATE INDEX {{prefix}}lupo_search_rebuild_log_idx_entity ON {{prefix}}search_rebuild_log (entity_type, entity_id);
CREATE INDEX {{prefix}}lupo_search_rebuild_log_idx_status_retry ON {{prefix}}search_rebuild_log (status, next_attempt_ymdhis);
CREATE UNIQUE INDEX {{prefix}}lupo_search_rebuild_log_unique_entity_operation ON {{prefix}}search_rebuild_log (entity_type, entity_id, action);

-- lupo_semantic_index
CREATE TABLE {{prefix}}semantic_index (
  `semantic_id` bigint NOT NULL,
  `semantic_type` varchar(32) NOT NULL,
  `slug` varchar(255),
  `name` varchar(255),
  `title` varchar(255),
  `description` text,
  `parent_id` bigint,
  `sort_order` int DEFAULT 0,
  `weight` float DEFAULT 0,
  `relationship_strength` decimal(3,2) DEFAULT 1.00,
  `layer` varchar(64),
  `timeframe` varchar(64),
  `language_code` varchar(8),
  `color` varchar(7) DEFAULT '#666666',
  `template_path` varchar(512),
  `json_data` json,
  `text_value` text,
  `source_content_id` bigint,
  `target_content_id` bigint,
  `source_page_id` bigint,
  `target_page_id` bigint,
  `entity_type` varchar(32),
  `entity_id` bigint,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL,
  `is_active` tinyint NOT NULL DEFAULT 1,
  `is_default` tinyint NOT NULL DEFAULT 0,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  `created_by` bigint,
  PRIMARY KEY (semantic_id)
);

CREATE INDEX {{prefix}}lupo_semantic_index_idx_created_ymdhis ON {{prefix}}semantic_index (created_ymdhis, is_active, is_deleted);
CREATE INDEX {{prefix}}lupo_semantic_index_idx_entity ON {{prefix}}semantic_index (entity_type, entity_id);
CREATE INDEX {{prefix}}lupo_semantic_index_idx_is_active ON {{prefix}}semantic_index (is_active);
CREATE INDEX {{prefix}}lupo_semantic_index_idx_is_default ON {{prefix}}semantic_index (is_default);
CREATE INDEX {{prefix}}lupo_semantic_index_idx_is_deleted ON {{prefix}}semantic_index (is_deleted);
CREATE INDEX {{prefix}}lupo_semantic_index_idx_language ON {{prefix}}semantic_index (language_code);
CREATE INDEX {{prefix}}lupo_semantic_index_idx_layer ON {{prefix}}semantic_index (layer);
CREATE INDEX {{prefix}}lupo_semantic_index_idx_parent ON {{prefix}}semantic_index (parent_id);
CREATE INDEX {{prefix}}lupo_semantic_index_idx_source_content ON {{prefix}}semantic_index (source_content_id);
CREATE INDEX {{prefix}}lupo_semantic_index_idx_source_page ON {{prefix}}semantic_index (source_page_id);
CREATE INDEX {{prefix}}lupo_semantic_index_idx_target_content ON {{prefix}}semantic_index (target_content_id);
CREATE INDEX {{prefix}}lupo_semantic_index_idx_target_page ON {{prefix}}semantic_index (target_page_id);
CREATE INDEX {{prefix}}lupo_semantic_index_idx_timeframe ON {{prefix}}semantic_index (timeframe);
CREATE INDEX {{prefix}}lupo_semantic_index_idx_type ON {{prefix}}semantic_index (semantic_type);
CREATE INDEX {{prefix}}lupo_semantic_index_idx_updated_ymdhis ON {{prefix}}semantic_index (updated_ymdhis);
CREATE UNIQUE INDEX {{prefix}}lupo_semantic_index_uk_type_slug ON {{prefix}}semantic_index (semantic_type, slug);

-- lupo_sessions
CREATE TABLE {{prefix}}sessions (
  `session_id` varchar(128) NOT NULL,
  `actor_id` bigint NOT NULL,
  `actor_name` varchar(64),
  `federation_node_id` bigint NOT NULL DEFAULT 0,
  `ip_hash` varchar(128),
  `ua_hash` varchar(255),
  `session_identity_hash` varchar(128) NOT NULL,
  `csrf_token` varchar(128),
  `last_activity_ymdhis` bigint NOT NULL,
  `created_ymdhis` bigint NOT NULL,
  `updated_ymdhis` bigint NOT NULL,
  `name_key` varchar(100),
  `is_named` tinyint NOT NULL DEFAULT 0,
  `metadata` json,
  `is_active` tinyint NOT NULL DEFAULT 1,
  `is_expired` tinyint NOT NULL DEFAULT 0,
  `is_revoked` tinyint NOT NULL DEFAULT 0,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `last_seen_ymdhis` bigint,
  `expires_ymdhis` bigint,
  `security_level` varchar(64),
  `system_context` varchar(64),
  `status` varchar(32),
  PRIMARY KEY (session_id)
);

CREATE INDEX {{prefix}}lupo_sessions_idx_actor ON {{prefix}}sessions (actor_id);
CREATE INDEX {{prefix}}lupo_sessions_idx_actor_name ON {{prefix}}sessions (actor_name);
CREATE INDEX {{prefix}}lupo_sessions_idx_federation ON {{prefix}}sessions (federation_node_id);
CREATE INDEX {{prefix}}lupo_sessions_idx_is_active ON {{prefix}}sessions (is_active);
CREATE INDEX {{prefix}}lupo_sessions_idx_last_activity ON {{prefix}}sessions (last_activity_ymdhis);
CREATE INDEX {{prefix}}lupo_sessions_idx_last_seen ON {{prefix}}sessions (last_seen_ymdhis);

-- lupo_system_commands
CREATE TABLE {{prefix}}system_commands (
  `command_id` bigint NOT NULL,
  `command_type` varchar(128) NOT NULL,
  `command_args_json` text,
  `working_dir` varchar(512),
  `status` varchar(32) NOT NULL,
  `priority` int NOT NULL DEFAULT 0,
  `created_ymdhis` bigint NOT NULL,
  `scheduled_ymdhis` bigint NOT NULL,
  `started_ymdhis` bigint,
  `finished_ymdhis` bigint,
  `claimed_by_actor_id` bigint,
  `claimed_by_host` varchar(256),
  `process_id` varchar(64),
  `attempt_count` int NOT NULL DEFAULT 0,
  `max_attempts` int NOT NULL DEFAULT 3,
  `timeout_seconds` int NOT NULL DEFAULT 3600,
  `return_code` int,
  `output_text` text,
  `output_sha1` varchar(64),
  `last_heartbeat_ymdhis` bigint,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  PRIMARY KEY (command_id)
);

CREATE INDEX {{prefix}}lupo_system_commands_idx_created_ymdhis ON {{prefix}}system_commands (created_ymdhis);
CREATE INDEX {{prefix}}lupo_system_commands_idx_is_deleted ON {{prefix}}system_commands (is_deleted);
CREATE INDEX {{prefix}}lupo_system_commands_idx_status_heartbeat ON {{prefix}}system_commands (status, last_heartbeat_ymdhis);
CREATE INDEX {{prefix}}lupo_system_commands_idx_status_priority_scheduled ON {{prefix}}system_commands (status, priority, scheduled_ymdhis);

-- lupo_system_config
CREATE TABLE {{prefix}}system_config (
  `system_config_id` bigint NOT NULL,
  `config_key` varchar(255) NOT NULL,
  `config_value` text NOT NULL,
  `actor_id` bigint NOT NULL DEFAULT 0,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL,
  PRIMARY KEY (system_config_id)
);

CREATE UNIQUE INDEX {{prefix}}lupo_system_config_config_key ON {{prefix}}system_config (config_key);

-- lupo_system_health_snapshots
CREATE TABLE {{prefix}}system_health_snapshots (
  `snapshot_id` bigint NOT NULL,
  `snapshot_type` varchar(64) NOT NULL,
  `actor_id` bigint NOT NULL,
  `table_count` bigint,
  `schema_hash` varchar(255),
  `utc_anchor` varchar(14),
  `metadata_json` text,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  PRIMARY KEY (snapshot_id)
);

CREATE INDEX {{prefix}}lupo_system_health_snapshots_idx_created ON {{prefix}}system_health_snapshots (created_ymdhis);
CREATE INDEX {{prefix}}lupo_system_health_snapshots_idx_is_deleted ON {{prefix}}system_health_snapshots (is_deleted);
CREATE INDEX {{prefix}}lupo_system_health_snapshots_idx_type ON {{prefix}}system_health_snapshots (snapshot_type);

-- lupo_tasks
CREATE TABLE {{prefix}}tasks (
  `task_id` bigint NOT NULL,
  `task_key` varchar(64) NOT NULL,
  `channel_id` bigint NOT NULL,
  `owner_actor_id` bigint NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text,
  `prompt_path` varchar(512),
  `acting_as_actor_id` bigint,
  `estimated_duration_seconds` int,
  `actual_duration_seconds` int,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL,
  `started_ymdhis` bigint,
  `completed_ymdhis` bigint,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  `metadata_json` text,
  `task_type` varchar(64),
  `task_status` varchar(64),
  `task_priority` enum('low','normal','high','urgent','critical') NOT NULL DEFAULT 'normal',
  `parent_agent_id` bigint,
  `consensus_hash` varchar(255),
  `approval_chain_json` json,
  `task_embeddings` text,
  `visibility_status` varchar(32) NOT NULL DEFAULT 'active',
  PRIMARY KEY (task_id)
);

CREATE INDEX {{prefix}}lupo_tasks_idx_acting_as_actor_id ON {{prefix}}tasks (acting_as_actor_id);
CREATE INDEX {{prefix}}lupo_tasks_idx_channel_id ON {{prefix}}tasks (channel_id);
CREATE INDEX {{prefix}}lupo_tasks_idx_created_ymdhis ON {{prefix}}tasks (created_ymdhis);
CREATE INDEX {{prefix}}lupo_tasks_idx_is_deleted ON {{prefix}}tasks (is_deleted);
CREATE INDEX {{prefix}}lupo_tasks_idx_owner_actor_id ON {{prefix}}tasks (owner_actor_id);
CREATE INDEX {{prefix}}lupo_tasks_idx_parent_agent_id ON {{prefix}}tasks (parent_agent_id);
CREATE INDEX {{prefix}}lupo_tasks_idx_task_priority ON {{prefix}}tasks (task_priority);
CREATE INDEX {{prefix}}lupo_tasks_idx_task_status ON {{prefix}}tasks (task_status);
CREATE INDEX {{prefix}}lupo_tasks_idx_task_type ON {{prefix}}tasks (task_type);
CREATE INDEX {{prefix}}lupo_tasks_idx_visibility_status ON {{prefix}}tasks (visibility_status);
CREATE UNIQUE INDEX {{prefix}}lupo_tasks_uniq_task_key_per_channel ON {{prefix}}tasks (task_key, channel_id);

-- lupo_thread_metadata
CREATE TABLE {{prefix}}thread_metadata (
  `thread_metadata_id` bigint NOT NULL,
  `dialog_thread_id` bigint NOT NULL,
  `metadata_key` varchar(255) NOT NULL,
  `metadata_value` text,
  `metadata_type` varchar(64) NOT NULL DEFAULT 'string',
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL,
  `created_by_actor_id` bigint NOT NULL,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  PRIMARY KEY (thread_metadata_id)
);

CREATE INDEX {{prefix}}lupo_thread_metadata_idx_created ON {{prefix}}thread_metadata (created_ymdhis);
CREATE INDEX {{prefix}}lupo_thread_metadata_idx_deleted ON {{prefix}}thread_metadata (is_deleted);
CREATE INDEX {{prefix}}lupo_thread_metadata_idx_key ON {{prefix}}thread_metadata (metadata_key);
CREATE INDEX {{prefix}}lupo_thread_metadata_idx_thread_id ON {{prefix}}thread_metadata (dialog_thread_id);
CREATE INDEX {{prefix}}lupo_thread_metadata_idx_type ON {{prefix}}thread_metadata (metadata_type);
CREATE UNIQUE INDEX {{prefix}}lupo_thread_metadata_unq_thread_key ON {{prefix}}thread_metadata (dialog_thread_id, metadata_key);

-- lupo_ticket_messages
CREATE TABLE {{prefix}}ticket_messages (
  `ticket_message_id` bigint NOT NULL,
  `ticket_id` bigint NOT NULL,
  `actor_id` bigint NOT NULL,
  `message_text` text NOT NULL,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  PRIMARY KEY (ticket_message_id)
);

CREATE INDEX {{prefix}}lupo_ticket_messages_idx_ticket ON {{prefix}}ticket_messages (ticket_id);

-- lupo_tickets
CREATE TABLE {{prefix}}tickets (
  `ticket_id` bigint NOT NULL,
  `channel_id` bigint NOT NULL,
  `actor_id` bigint NOT NULL,
  `status` varchar(64) NOT NULL DEFAULT 'open',
  `priority` varchar(64) NOT NULL DEFAULT 'medium',
  `subject` varchar(255) NOT NULL,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL DEFAULT 0,
  `metadata_json` json,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  PRIMARY KEY (ticket_id)
);

CREATE INDEX {{prefix}}lupo_tickets_idx_actor ON {{prefix}}tickets (actor_id);
CREATE INDEX {{prefix}}lupo_tickets_idx_channel ON {{prefix}}tickets (channel_id);
CREATE INDEX {{prefix}}lupo_tickets_idx_status ON {{prefix}}tickets (status);

-- lupo_trust_ladder_registry
CREATE TABLE {{prefix}}trust_ladder_registry (
  `registry_id` bigint NOT NULL,
  `table_name` varchar(128) NOT NULL,
  `archetype` varchar(16) NOT NULL DEFAULT 'child',
  `participates` varchar(32) NOT NULL DEFAULT 'not_ladder',
  `seed_required` tinyint NOT NULL DEFAULT 0,
  `canonical_lineage_edge` varchar(64),
  `promotion_target` varchar(32),
  `is_active` tinyint NOT NULL DEFAULT 1,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL DEFAULT 0,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint NOT NULL DEFAULT 0,
  `canonical_id` bigint,
  `seed_range_override` varchar(32),
  `reference_target` varchar(16) NOT NULL DEFAULT 'canonical',
  `sequential_id` bigint,
  `notes` text,
  PRIMARY KEY (registry_id)
);

CREATE INDEX {{prefix}}lupo_trust_ladder_registry_idx_archetype ON {{prefix}}trust_ladder_registry (archetype);
CREATE INDEX {{prefix}}lupo_trust_ladder_registry_idx_is_active ON {{prefix}}trust_ladder_registry (is_active);
CREATE INDEX {{prefix}}lupo_trust_ladder_registry_idx_is_deleted ON {{prefix}}trust_ladder_registry (is_deleted);
CREATE INDEX {{prefix}}lupo_trust_ladder_registry_idx_participates ON {{prefix}}trust_ladder_registry (participates);
CREATE UNIQUE INDEX {{prefix}}lupo_trust_ladder_registry_unq_table ON {{prefix}}trust_ladder_registry (table_name);

-- lupo_truth_answers
CREATE TABLE {{prefix}}truth_answers (
  `truth_answer_id` bigint NOT NULL,
  `truth_question_id` bigint NOT NULL,
  `answer_text` text NOT NULL,
  `answer_summary` varchar(500),
  `answered_by_actor_id` bigint NOT NULL,
  `answered_in_channel_id` bigint,
  `answered_in_thread_id` bigint,
  `answered_in_message_id` bigint,
  `is_accepted` tinyint NOT NULL DEFAULT 0,
  `acceptance_votes` int NOT NULL DEFAULT 0,
  `rejection_votes` int NOT NULL DEFAULT 0,
  `confidence_score` decimal(3,2) NOT NULL DEFAULT 0.50,
  `answer_status` varchar(32) NOT NULL DEFAULT 'active',
  `view_count` bigint NOT NULL DEFAULT 0,
  `helpful_count` bigint NOT NULL DEFAULT 0,
  `created_ymdhis` bigint NOT NULL,
  `updated_ymdhis` bigint NOT NULL,
  `accepted_ymdhis` bigint,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  `metadata_json` json,
  PRIMARY KEY (truth_answer_id)
);

CREATE INDEX {{prefix}}lupo_truth_answers_idx_accepted ON {{prefix}}truth_answers (is_accepted, acceptance_votes);
CREATE INDEX {{prefix}}lupo_truth_answers_idx_answered_by ON {{prefix}}truth_answers (answered_by_actor_id);
CREATE INDEX {{prefix}}lupo_truth_answers_idx_channel ON {{prefix}}truth_answers (answered_in_channel_id);
CREATE INDEX {{prefix}}lupo_truth_answers_idx_confidence ON {{prefix}}truth_answers (confidence_score);
CREATE INDEX {{prefix}}lupo_truth_answers_idx_created ON {{prefix}}truth_answers (created_ymdhis);
CREATE INDEX {{prefix}}lupo_truth_answers_idx_deleted ON {{prefix}}truth_answers (is_deleted);
CREATE INDEX {{prefix}}lupo_truth_answers_idx_question ON {{prefix}}truth_answers (truth_question_id);
CREATE INDEX {{prefix}}lupo_truth_answers_idx_status ON {{prefix}}truth_answers (answer_status);
CREATE INDEX {{prefix}}lupo_truth_answers_idx_thread ON {{prefix}}truth_answers (answered_in_thread_id);

-- lupo_truth_context_map
CREATE TABLE {{prefix}}truth_context_map (
  `truth_context_map_id` bigint NOT NULL,
  `truth_question_id` bigint NOT NULL,
  `context_id` bigint,
  `collection_id` bigint,
  `context_card_id` bigint,
  `sort_order` int NOT NULL DEFAULT 0,
  `mapping_reason` varchar(255),
  `added_by_actor_id` bigint NOT NULL,
  `created_ymdhis` bigint NOT NULL,
  `updated_ymdhis` bigint NOT NULL,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  PRIMARY KEY (truth_context_map_id)
);

CREATE INDEX {{prefix}}lupo_truth_context_map_idx_added_by ON {{prefix}}truth_context_map (added_by_actor_id);
CREATE INDEX {{prefix}}lupo_truth_context_map_idx_card ON {{prefix}}truth_context_map (context_card_id);
CREATE INDEX {{prefix}}lupo_truth_context_map_idx_collection ON {{prefix}}truth_context_map (collection_id);
CREATE INDEX {{prefix}}lupo_truth_context_map_idx_context ON {{prefix}}truth_context_map (context_id);
CREATE INDEX {{prefix}}lupo_truth_context_map_idx_created ON {{prefix}}truth_context_map (created_ymdhis);
CREATE INDEX {{prefix}}lupo_truth_context_map_idx_deleted ON {{prefix}}truth_context_map (is_deleted);
CREATE INDEX {{prefix}}lupo_truth_context_map_idx_question ON {{prefix}}truth_context_map (truth_question_id);

-- lupo_truth_evidence
CREATE TABLE {{prefix}}truth_evidence (
  `truth_evidence_id` bigint NOT NULL,
  `truth_answer_id` bigint NOT NULL,
  `evidence_type` varchar(64) NOT NULL,
  `source_object_type` varchar(64) NOT NULL,
  `source_object_id` bigint NOT NULL,
  `source_federation_node_id` bigint,
  `source_url` varchar(2000),
  `source_title` varchar(500),
  `evidence_text` text,
  `evidence_excerpt` varchar(1000),
  `reliability_score` decimal(3,2) NOT NULL DEFAULT 0.50,
  `relevance_score` decimal(3,2) NOT NULL DEFAULT 0.50,
  `is_verified` tinyint NOT NULL DEFAULT 0,
  `verified_by_actor_id` bigint,
  `verified_ymdhis` bigint,
  `verification_notes` text,
  `submitted_by_actor_id` bigint NOT NULL,
  `created_ymdhis` bigint NOT NULL,
  `updated_ymdhis` bigint NOT NULL,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  PRIMARY KEY (truth_evidence_id)
);

CREATE INDEX {{prefix}}lupo_truth_evidence_idx_answer ON {{prefix}}truth_evidence (truth_answer_id);
CREATE INDEX {{prefix}}lupo_truth_evidence_idx_created ON {{prefix}}truth_evidence (created_ymdhis);
CREATE INDEX {{prefix}}lupo_truth_evidence_idx_deleted ON {{prefix}}truth_evidence (is_deleted);
CREATE INDEX {{prefix}}lupo_truth_evidence_idx_evidence_type ON {{prefix}}truth_evidence (evidence_type);
CREATE INDEX {{prefix}}lupo_truth_evidence_idx_federation ON {{prefix}}truth_evidence (source_federation_node_id);
CREATE INDEX {{prefix}}lupo_truth_evidence_idx_source ON {{prefix}}truth_evidence (source_object_type, source_object_id);
CREATE INDEX {{prefix}}lupo_truth_evidence_idx_submitted_by ON {{prefix}}truth_evidence (submitted_by_actor_id);
CREATE INDEX {{prefix}}lupo_truth_evidence_idx_verified ON {{prefix}}truth_evidence (is_verified, reliability_score);

-- lupo_truth_followers
CREATE TABLE {{prefix}}truth_followers (
  `truth_follower_id` bigint NOT NULL,
  `truth_question_id` bigint NOT NULL,
  `actor_id` bigint NOT NULL,
  `notification_enabled` tinyint NOT NULL DEFAULT 1,
  `created_ymdhis` bigint NOT NULL,
  `updated_ymdhis` bigint NOT NULL,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  PRIMARY KEY (truth_follower_id)
);

CREATE INDEX {{prefix}}lupo_truth_followers_idx_actor ON {{prefix}}truth_followers (actor_id);
CREATE INDEX {{prefix}}lupo_truth_followers_idx_deleted ON {{prefix}}truth_followers (is_deleted);
CREATE INDEX {{prefix}}lupo_truth_followers_idx_question ON {{prefix}}truth_followers (truth_question_id);
CREATE UNIQUE INDEX {{prefix}}lupo_truth_followers_unq_question_actor ON {{prefix}}truth_followers (truth_question_id, actor_id);

-- lupo_truth_questions
CREATE TABLE {{prefix}}truth_questions (
  `truth_question_id` bigint NOT NULL,
  `parent_question_id` bigint,
  `root_question_id` bigint,
  `depth` tinyint NOT NULL DEFAULT 0,
  `target_object_type` varchar(64) NOT NULL,
  `target_object_id` bigint NOT NULL,
  `question_text` text NOT NULL,
  `question_summary` varchar(500),
  `asked_by_actor_id` bigint NOT NULL,
  `asked_in_channel_id` bigint,
  `asked_in_thread_id` bigint,
  `asked_in_session_id` varchar(128),
  `question_status` varchar(32) NOT NULL DEFAULT 'open',
  `is_answered` tinyint NOT NULL DEFAULT 0,
  `is_featured` tinyint NOT NULL DEFAULT 0,
  `view_count` bigint NOT NULL DEFAULT 0,
  `answer_count` bigint NOT NULL DEFAULT 0,
  `follower_count` bigint NOT NULL DEFAULT 0,
  `created_ymdhis` bigint NOT NULL,
  `updated_ymdhis` bigint NOT NULL,
  `answered_ymdhis` bigint,
  `closed_ymdhis` bigint,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  `metadata_json` json,
  PRIMARY KEY (truth_question_id)
);

CREATE INDEX {{prefix}}lupo_truth_questions_idx_asked_by ON {{prefix}}truth_questions (asked_by_actor_id);
CREATE INDEX {{prefix}}lupo_truth_questions_idx_channel ON {{prefix}}truth_questions (asked_in_channel_id);
CREATE INDEX {{prefix}}lupo_truth_questions_idx_created ON {{prefix}}truth_questions (created_ymdhis);
CREATE INDEX {{prefix}}lupo_truth_questions_idx_deleted ON {{prefix}}truth_questions (is_deleted);
CREATE INDEX {{prefix}}lupo_truth_questions_idx_featured ON {{prefix}}truth_questions (is_featured);
CREATE INDEX {{prefix}}lupo_truth_questions_idx_parent ON {{prefix}}truth_questions (parent_question_id);
CREATE INDEX {{prefix}}lupo_truth_questions_idx_root ON {{prefix}}truth_questions (root_question_id);
CREATE INDEX {{prefix}}lupo_truth_questions_idx_status ON {{prefix}}truth_questions (question_status, is_answered);
CREATE INDEX {{prefix}}lupo_truth_questions_idx_target ON {{prefix}}truth_questions (target_object_type, target_object_id);
CREATE INDEX {{prefix}}lupo_truth_questions_idx_thread ON {{prefix}}truth_questions (asked_in_thread_id);

-- lupo_two_factor_audit
CREATE TABLE {{prefix}}two_factor_audit (
  `two_factor_audit_id` bigint NOT NULL,
  `auth_user_id` bigint NOT NULL,
  `action` varchar(50) NOT NULL,
  `ip_address` varchar(45),
  `user_agent` text,
  `created_ymdhis` bigint NOT NULL,
  PRIMARY KEY (two_factor_audit_id)
);

CREATE INDEX {{prefix}}idx_created ON {{prefix}}two_factor_audit (created_ymdhis);
CREATE INDEX {{prefix}}idx_user_id ON {{prefix}}two_factor_audit (auth_user_id);

-- lupo_unified_log
CREATE TABLE {{prefix}}unified_log (
  `log_id` bigint NOT NULL,
  `log_type` varchar(64) NOT NULL,
  `log_level` varchar(32) NOT NULL DEFAULT 'info',
  `log_message` text NOT NULL,
  `log_context` json,
  `actor_id` bigint,
  `channel_id` bigint,
  `session_id` varchar(128),
  `ip_address` varchar(45),
  `user_agent` text,
  `created_ymdhis` bigint NOT NULL,
  PRIMARY KEY (log_id)
);

CREATE INDEX {{prefix}}lupo_unified_log_idx_actor_id ON {{prefix}}unified_log (actor_id);
CREATE INDEX {{prefix}}lupo_unified_log_idx_actor_log ON {{prefix}}unified_log (actor_id, log_type);
CREATE INDEX {{prefix}}lupo_unified_log_idx_channel_id ON {{prefix}}unified_log (channel_id);
CREATE INDEX {{prefix}}lupo_unified_log_idx_channel_log ON {{prefix}}unified_log (channel_id, log_type);
CREATE INDEX {{prefix}}lupo_unified_log_idx_created_ymdhis ON {{prefix}}unified_log (created_ymdhis);
CREATE INDEX {{prefix}}lupo_unified_log_idx_log_level ON {{prefix}}unified_log (log_level);
CREATE INDEX {{prefix}}lupo_unified_log_idx_log_type ON {{prefix}}unified_log (log_type);
CREATE INDEX {{prefix}}lupo_unified_log_idx_log_type_created ON {{prefix}}unified_log (log_type, created_ymdhis);
CREATE INDEX {{prefix}}lupo_unified_log_idx_session_id ON {{prefix}}unified_log (session_id);

-- lupo_uploads
CREATE TABLE {{prefix}}uploads (
  `upload_id` bigint NOT NULL,
  `actor_id` bigint NOT NULL,
  `channel_id` bigint,
  `original_filename` varchar(255) NOT NULL,
  `stored_filename` varchar(255) NOT NULL,
  `file_extension` varchar(16) NOT NULL,
  `mime_type` varchar(128) NOT NULL,
  `file_size_bytes` bigint NOT NULL,
  `storage_path` varchar(512) NOT NULL,
  `metadata_json` json,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  PRIMARY KEY (upload_id)
);

CREATE INDEX {{prefix}}lupo_uploads_idx_actor_id ON {{prefix}}uploads (actor_id);
CREATE INDEX {{prefix}}lupo_uploads_idx_channel_id ON {{prefix}}uploads (channel_id);
CREATE INDEX {{prefix}}lupo_uploads_idx_created_ymdhis ON {{prefix}}uploads (created_ymdhis);
CREATE INDEX {{prefix}}lupo_uploads_idx_file_extension ON {{prefix}}uploads (file_extension);

-- lupo_versions
CREATE TABLE {{prefix}}versions (
  `version_id` bigint NOT NULL,
  `version` varchar(50) NOT NULL,
  `component` varchar(100) NOT NULL DEFAULT 'schema',
  `release_notes` text,
  `is_current` tinyint NOT NULL DEFAULT 0,
  `released_ymdhis` bigint NOT NULL DEFAULT 0,
  `deployed_by_actor_id` bigint,
  PRIMARY KEY (version_id)
);

CREATE INDEX {{prefix}}lupo_versions_idx_component ON {{prefix}}versions (component);
CREATE INDEX {{prefix}}lupo_versions_idx_current ON {{prefix}}versions (is_current);
CREATE INDEX {{prefix}}lupo_versions_idx_released ON {{prefix}}versions (released_ymdhis);
CREATE UNIQUE INDEX {{prefix}}lupo_versions_unq_component_version ON {{prefix}}versions (component, version);

-- lupo_visits
CREATE TABLE {{prefix}}visits (
  `visit_id` bigint NOT NULL,
  `session_id` bigint,
  `actor_id` bigint,
  `instance_id` bigint,
  `path_url` text,
  `entercontentid` bigint,
  `exitcontentid` bigint,
  `enter_table` varchar(255),
  `exit_table` varchar(255),
  `transition_type` varchar(64),
  `transition_metadata` text,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `is_processed` tinyint NOT NULL DEFAULT 0,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  `country_code` char(2),
  `city` varchar(100),
  `latitude` decimal(10,8),
  `longitude` decimal(11,8),
  `geolocation_source` varchar(50),
  PRIMARY KEY (visit_id)
);

CREATE INDEX {{prefix}}idx_country ON {{prefix}}visits (country_code);
CREATE INDEX {{prefix}}lupo_visits_idx_actor ON {{prefix}}visits (actor_id);
CREATE INDEX {{prefix}}lupo_visits_idx_created ON {{prefix}}visits (created_ymdhis);
CREATE INDEX {{prefix}}lupo_visits_idx_enter_exit ON {{prefix}}visits (entercontentid, exitcontentid);
CREATE INDEX {{prefix}}lupo_visits_idx_is_deleted ON {{prefix}}visits (is_deleted);
CREATE INDEX {{prefix}}lupo_visits_idx_is_processed ON {{prefix}}visits (is_processed);
CREATE INDEX {{prefix}}lupo_visits_idx_session ON {{prefix}}visits (session_id);

-- lupo_visits_daily
CREATE TABLE {{prefix}}visits_daily (
  `visits_daily_id` bigint NOT NULL,
  `actor_id` bigint,
  `visit_ymd` bigint NOT NULL,
  `total_visits` int DEFAULT 0,
  `unique_sessions` int DEFAULT 0,
  `avg_duration_seconds` int DEFAULT 0,
  `bounce_count` int DEFAULT 0,
  `created_ymdhis` bigint NOT NULL,
  `updated_ymdhis` bigint NOT NULL,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  PRIMARY KEY (visits_daily_id)
);

CREATE INDEX {{prefix}}idx_actor_date ON {{prefix}}visits_daily (actor_id, visit_ymd);
CREATE UNIQUE INDEX {{prefix}}unique_daily_actor ON {{prefix}}visits_daily (actor_id, visit_ymd);

-- lupo_votes
CREATE TABLE {{prefix}}votes (
  `vote_id` bigint NOT NULL,
  `target_type` varchar(64) NOT NULL,
  `target_id` bigint NOT NULL,
  `cast_by_actor_id` bigint NOT NULL,
  `vote_value` tinyint NOT NULL,
  `vote_weight` float DEFAULT 1,
  `created_ymdhis` bigint NOT NULL,
  `updated_ymdhis` bigint NOT NULL,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  `vote_type` varchar(32) NOT NULL COMMENT 'up / down / heart / flag / etc',
  `reason_text` text COMMENT 'Optional human-readable reason',
  `reason_code` varchar(64) COMMENT 'Standardized reason code (e.g. SPAM, OFFTOPIC, MISINFO)',
  `is_current` tinyint NOT NULL DEFAULT 1 COMMENT 'Whether this is the latest vote by this actor on this target',
  PRIMARY KEY (vote_id)
);

CREATE INDEX {{prefix}}idx_lupo_votes_target_current ON {{prefix}}votes (target_type, target_id, cast_by_actor_id, is_current);
CREATE INDEX {{prefix}}idx_vote_actor ON {{prefix}}votes (cast_by_actor_id);
CREATE INDEX {{prefix}}idx_vote_created ON {{prefix}}votes (created_ymdhis);
CREATE INDEX {{prefix}}idx_vote_is_deleted ON {{prefix}}votes (is_deleted);
CREATE INDEX {{prefix}}idx_vote_object ON {{prefix}}votes (target_type, target_id);
CREATE INDEX {{prefix}}idx_vote_value ON {{prefix}}votes (vote_value);
CREATE UNIQUE INDEX {{prefix}}uq_vote_object_actor ON {{prefix}}votes (target_type, target_id, cast_by_actor_id);

-- lupo_world_registry
CREATE TABLE {{prefix}}world_registry (
  `world_id` bigint NOT NULL,
  `world_key` varchar(255) NOT NULL,
  `world_type` varchar(64) NOT NULL,
  `world_label` varchar(255) NOT NULL,
  `world_metadata` json,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL,
  `is_active` tinyint NOT NULL DEFAULT 1,
  PRIMARY KEY (world_id)
);

CREATE INDEX {{prefix}}lupo_world_registry_idx_created_ymdhis ON {{prefix}}world_registry (created_ymdhis);
CREATE INDEX {{prefix}}lupo_world_registry_idx_is_active ON {{prefix}}world_registry (is_active);
CREATE INDEX {{prefix}}lupo_world_registry_idx_world_type ON {{prefix}}world_registry (world_type);
CREATE UNIQUE INDEX {{prefix}}lupo_world_registry_unique_world_key ON {{prefix}}world_registry (world_key);

