-- ============================================================
-- Schema Rebuild Batch 2 4.0.24
-- Generated: 2026-02-21T03:08:58.737660Z
-- Tables in this batch: 50
-- ============================================================

-- lupo_actors
CREATE TABLE `lupo_actors` (
  `actor_id` bigint NOT NULL,
  `actor_type` varchar(64) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL,
  `is_active` tinyint NOT NULL DEFAULT 1,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  `actor_source_id` bigint,
  `actor_source_type` varchar(50),
  `metadata` text,
  `adversarial_role` varchar(64) DEFAULT 'none',
  `adversarial_oversight_actor_id` bigint,
  `avatar_hash` varchar(64),
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INDEX `lupo_actors_idx_actor_type` (`actor_type`);
INDEX `lupo_actors_idx_created_ymdhis` (`created_ymdhis`);
INDEX `lupo_actors_idx_is_active` (`is_active`);
UNIQUE INDEX `lupo_actors_unique_slug` (`slug`);

-- lupo_actor_actions
CREATE TABLE `lupo_actor_actions` (
  `actor_action_id` bigint NOT NULL,
  `actor_id` bigint NOT NULL,
  `action_type` varchar(64) NOT NULL,
  `entity_type` varchar(64),
  `entity_id` bigint,
  `description` text,
  `metadata_json` json,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INDEX `lupo_actor_actions_idx_action_type` (`action_type`);
INDEX `lupo_actor_actions_idx_actor` (`actor_id`);
INDEX `lupo_actor_actions_idx_entity` (`entity_type`, `entity_id`);

-- lupo_actor_aliases
CREATE TABLE `lupo_actor_aliases` (
  `alias_id` bigint NOT NULL auto_increment,
  `actor_id` bigint NOT NULL,
  `alias_name` varchar(255) NOT NULL,
  `created_ymdhis` bigint NOT NULL,
  `updated_ymdhis` bigint NOT NULL,
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- lupo_actor_capabilities
CREATE TABLE `lupo_actor_capabilities` (
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
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INDEX `lupo_actor_capabilities_idx_agent_domain` (`actor_id`, `domain_id`);
INDEX `lupo_actor_capabilities_idx_capability_key` (`capability_key`);
INDEX `lupo_actor_capabilities_idx_created_ymdhis` (`created_ymdhis`);
INDEX `lupo_actor_capabilities_idx_domain_id` (`domain_id`);
INDEX `lupo_actor_capabilities_idx_is_deleted` (`is_deleted`);
INDEX `lupo_actor_capabilities_idx_updated_ymdhis` (`updated_ymdhis`);
UNIQUE INDEX `lupo_actor_capabilities_unique_agent_domain_capability` (`actor_id`, `domain_id`, `capability_key`);

-- lupo_actor_channel_roles
CREATE TABLE `lupo_actor_channel_roles` (
  `actor_channel_role_id` bigint NOT NULL,
  `actor_id` bigint NOT NULL,
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
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INDEX `lupo_actor_channel_roles_idx_actor_id` (`actor_id`);
INDEX `lupo_actor_channel_roles_idx_channel_id` (`channel_id`);
INDEX `lupo_actor_channel_roles_idx_join_sequence_step` (`join_sequence_step`);
INDEX `lupo_actor_channel_roles_idx_protocol_completion_status` (`protocol_completion_status`);
INDEX `lupo_actor_channel_roles_idx_protocol_version` (`protocol_version`);
INDEX `lupo_actor_channel_roles_idx_role_key` (`role_key`);

-- lupo_actor_collections
CREATE TABLE `lupo_actor_collections` (
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
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INDEX `lupo_actor_collections_idx_access_level` (`access_level`);
INDEX `lupo_actor_collections_idx_actor` (`actor_id`);
INDEX `lupo_actor_collections_idx_collection` (`collection_id`);
INDEX `lupo_actor_collections_idx_created_ymdhis` (`created_ymdhis`);
INDEX `lupo_actor_collections_idx_identity_signature` (`identity_signature`);
INDEX `lupo_actor_collections_idx_is_deleted` (`is_deleted`);
INDEX `lupo_actor_collections_idx_trust_level` (`trust_level`);

-- lupo_actor_conflicts
CREATE TABLE `lupo_actor_conflicts` (
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
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INDEX `lupo_actor_conflicts_idx_agent_a` (`actor_a_id`);
INDEX `lupo_actor_conflicts_idx_agent_b` (`actor_b_id`);
INDEX `lupo_actor_conflicts_idx_agent_pair` (`actor_a_id`, `actor_b_id`);
INDEX `lupo_actor_conflicts_idx_conflict_type` (`conflict_type`);
INDEX `lupo_actor_conflicts_idx_created` (`created_ymdhis`);
INDEX `lupo_actor_conflicts_idx_deleted` (`is_deleted`);
INDEX `lupo_actor_conflicts_idx_domain` (`domain_id`);
INDEX `lupo_actor_conflicts_idx_resolved_ymdhis` (`resolved_ymdhis`);
INDEX `lupo_actor_conflicts_idx_severity` (`severity`);
INDEX `lupo_actor_conflicts_idx_status` (`resolution_status`);
INDEX `lupo_actor_conflicts_idx_updated` (`updated_ymdhis`);

-- lupo_actor_edges
CREATE TABLE `lupo_actor_edges` (
  `actor_edge_id` bigint NOT NULL,
  `domain_id` bigint NOT NULL,
  `source_actor_id` bigint NOT NULL,
  `target_actor_id` bigint NOT NULL,
  `edge_type` varchar(100) NOT NULL,
  `weight` float DEFAULT 1,
  `properties` text,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INDEX `lupo_actor_edges_idx_created_ymdhis` (`created_ymdhis`);
INDEX `lupo_actor_edges_idx_domain_id` (`domain_id`);
INDEX `lupo_actor_edges_idx_edge_source_relationship` (`source_actor_id`, `edge_type`);
INDEX `lupo_actor_edges_idx_edge_target_relationship` (`target_actor_id`, `edge_type`);
INDEX `lupo_actor_edges_idx_edge_type` (`edge_type`);
INDEX `lupo_actor_edges_idx_is_deleted` (`is_deleted`);
INDEX `lupo_actor_edges_idx_source_agent` (`source_actor_id`);
INDEX `lupo_actor_edges_idx_source_target` (`source_actor_id`, `target_actor_id`);
INDEX `lupo_actor_edges_idx_target_agent` (`target_actor_id`);
INDEX `lupo_actor_edges_idx_updated_ymdhis` (`updated_ymdhis`);
UNIQUE INDEX `lupo_actor_edges_unique_agent_edge` (`domain_id`, `source_actor_id`, `target_actor_id`, `edge_type`);

-- lupo_actor_events
CREATE TABLE `lupo_actor_events` (
  `actor_event_id` bigint NOT NULL,
  `actor_id` bigint NOT NULL,
  `session_id` varchar(255),
  `tab_id` varchar(255),
  `world_id` bigint,
  `world_key` varchar(255),
  `world_type` varchar(50),
  `event_type` varchar(100) NOT NULL,
  `event_data` json,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INDEX `lupo_actor_events_idx_actor_event_type` (`actor_id`, `event_type`);
INDEX `lupo_actor_events_idx_actor_id` (`actor_id`);
INDEX `lupo_actor_events_idx_created_ymdhis` (`created_ymdhis`);
INDEX `lupo_actor_events_idx_event_type` (`event_type`);
INDEX `lupo_actor_events_idx_session_id` (`session_id`);
INDEX `lupo_actor_events_idx_tab_id` (`tab_id`);
INDEX `lupo_actor_events_idx_world_id` (`world_id`);

-- lupo_actor_handshakes
CREATE TABLE `lupo_actor_handshakes` (
  `actor_handshake_id` bigint NOT NULL,
  `actor_id` bigint NOT NULL,
  `actor_type` varchar(32) NOT NULL,
  `utc_timestamp` bigint NOT NULL,
  `purpose` varchar(500),
  `constraints_json` json,
  `forbidden_actions_json` json,
  `context` text,
  `expires_utc` bigint,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INDEX `lupo_actor_handshakes_idx_actor_id` (`actor_id`);
INDEX `lupo_actor_handshakes_idx_is_deleted` (`is_deleted`);
INDEX `lupo_actor_handshakes_idx_utc_timestamp` (`utc_timestamp`);

-- lupo_actor_meta
CREATE TABLE `lupo_actor_meta` (
  `actor_meta_id` bigint NOT NULL,
  `actor_id` bigint NOT NULL,
  `meta_type` varchar(64) NOT NULL,
  `meta_key` varchar(255) NOT NULL,
  `meta_value` text NOT NULL,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL,
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INDEX `lupo_actor_meta_actor_id` (`actor_id`);
INDEX `lupo_actor_meta_meta_key` (`meta_key`);
INDEX `lupo_actor_meta_meta_type` (`meta_type`);

-- lupo_actor_moods
CREATE TABLE `lupo_actor_moods` (
  `actor_id` bigint NOT NULL,
  `mood_r` tinyint NOT NULL,
  `mood_g` tinyint NOT NULL,
  `mood_b` tinyint NOT NULL,
  `mood_framework` varchar(32) NOT NULL DEFAULT 'western_analytical',
  `timestamp_utc` bigint NOT NULL,
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- lupo_actor_object_edges
CREATE TABLE `lupo_actor_object_edges` (
  `actor_object_edge_id` bigint NOT NULL,
  `actor_id` bigint NOT NULL,
  `target_table` varchar(100) NOT NULL,
  `target_id` bigint NOT NULL,
  `edge_type` varchar(50) NOT NULL,
  `properties_json` json,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL,
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INDEX `lupo_actor_object_edges_idx_actor_edge_type` (`actor_id`, `edge_type`);
INDEX `lupo_actor_object_edges_idx_target_lookup` (`target_table`, `target_id`);
UNIQUE INDEX `lupo_actor_object_edges_uniq_actor_target_type` (`actor_id`, `target_table`, `target_id`, `edge_type`);

-- lupo_actor_persona_relationships
CREATE TABLE `lupo_actor_persona_relationships` (
  `relationship_id` bigint NOT NULL,
  `actor_id` bigint NOT NULL,
  `persona_id` bigint NOT NULL,
  `relationship_type` varchar(100) NOT NULL,
  `relationship_strength` decimal(5,2),
  `relationship_context` json,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL,
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INDEX `lupo_actor_persona_relationships_idx_actor_id` (`actor_id`);
INDEX `lupo_actor_persona_relationships_idx_persona_id` (`persona_id`);
INDEX `lupo_actor_persona_relationships_idx_relationship_type` (`relationship_type`);

-- lupo_actor_properties
CREATE TABLE `lupo_actor_properties` (
  `actor_property_id` bigint NOT NULL,
  `actor_type` varchar(32) NOT NULL,
  `actor_id` bigint NOT NULL,
  `property_key` varchar(64) NOT NULL,
  `property_value` text,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INDEX `lupo_actor_properties_idx_entity` (`actor_type`, `actor_id`);
INDEX `lupo_actor_properties_idx_property` (`property_key`);

-- lupo_actor_reply_templates
CREATE TABLE `lupo_actor_reply_templates` (
  `actor_reply_template_id` bigint NOT NULL,
  `actor_id` bigint NOT NULL,
  `template_key` varchar(64) NOT NULL,
  `template_text` text NOT NULL,
  `usage_context` varchar(64),
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INDEX `lupo_actor_reply_templates_idx_actor` (`actor_id`);
INDEX `lupo_actor_reply_templates_idx_created` (`created_ymdhis`);
INDEX `lupo_actor_reply_templates_idx_deleted` (`is_deleted`);
INDEX `lupo_actor_reply_templates_idx_key` (`template_key`);
INDEX `lupo_actor_reply_templates_idx_updated` (`updated_ymdhis`);
INDEX `lupo_actor_reply_templates_idx_usage_context` (`usage_context`);
UNIQUE INDEX `lupo_actor_reply_templates_unq_actor_template_key` (`actor_id`, `template_key`);

-- lupo_actor_truth_edges
CREATE TABLE `lupo_actor_truth_edges` (
  `actor_truth_edge_id` bigint NOT NULL,
  `actor_id` bigint NOT NULL,
  `truth_item_id` bigint NOT NULL,
  `edge_type` varchar(64) NOT NULL,
  `properties_json` json,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL,
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INDEX `lupo_actor_truth_edges_idx_actor_edge_type` (`actor_id`, `edge_type`);
INDEX `lupo_actor_truth_edges_idx_truth_item` (`truth_item_id`);
UNIQUE INDEX `lupo_actor_truth_edges_uniq_actor_truth_type` (`actor_id`, `truth_item_id`, `edge_type`);

-- lupo_agents
CREATE TABLE `lupo_agents` (
  `agent_id` bigint NOT NULL,
  `agent_key` varchar(100) NOT NULL,
  `agent_name` varchar(150) NOT NULL,
  `archetype` varchar(150),
  `description` text,
  `version` varchar(50) DEFAULT '1.0',
  `model_name` varchar(100),
  `is_global_authority` tinyint NOT NULL DEFAULT 0,
  `is_internal_only` tinyint NOT NULL DEFAULT 0,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  `avg_response_time_ms` int DEFAULT 0,
  `total_tokens_processed` bigint DEFAULT 0,
  `success_rate` float DEFAULT 1,
  `cost_per_1k_tokens` decimal(10,4) DEFAULT 0.0000,
  `temperature` float DEFAULT 0.7,
  `top_p` float DEFAULT 1,
  `max_tokens` int DEFAULT 2048,
  `presence_penalty` float DEFAULT 0,
  `frequency_penalty` float DEFAULT 0,
  `system_prompt` text,
  `provider` varchar(50) DEFAULT 'openai',
  `api_key_id` bigint,
  `timeout_ms` int DEFAULT 20000,
  `safety_json` json,
  `response_format` varchar(50),
  `pono_score` decimal(3,2) DEFAULT 1.00,
  `pilau_score` decimal(3,2) DEFAULT 0.00,
  `kapakai_score` decimal(3,2) DEFAULT 0.50,
  `kapu_active` tinyint DEFAULT 0,
  `kapu_until` bigint,
  `kapu_reason` varchar(500),
  `kapu_consent_given` tinyint DEFAULT 0,
  `kapu_appeal_pending` tinyint DEFAULT 0,
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INDEX `lupo_agents_idx_created_ymdhis` (`created_ymdhis`);
INDEX `lupo_agents_idx_is_deleted` (`is_deleted`);
INDEX `lupo_agents_idx_is_global_authority` (`is_global_authority`);
INDEX `lupo_agents_idx_updated_ymdhis` (`updated_ymdhis`);
UNIQUE INDEX `lupo_agents_unique_agent_key` (`agent_key`);

-- lupo_agent_context_snapshots
CREATE TABLE `lupo_agent_context_snapshots` (
  `agent_context_snapshot_id` bigint NOT NULL,
  `session_id` varchar(100) NOT NULL,
  `actor_id` bigint NOT NULL,
  `parent_snapshot_id` bigint,
  `snapshot_type` varchar(64) NOT NULL DEFAULT 'full',
  `snapshot_purpose` varchar(50),
  `context_data` text NOT NULL,
  `context_summary` text,
  `context_metadata` json,
  `token_count` int,
  `character_count` int,
  `compressed_size` int,
  `compression_ratio` float,
  `compression_method` varchar(64) DEFAULT 'gzip',
  `serialization_time_ms` int,
  `compression_time_ms` int,
  `related_tool_call_id` bigint,
  `conversation_turn` int,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `expires_ymdhis` bigint,
  `is_corrupt` tinyint DEFAULT 0,
  `retention_policy` varchar(64) DEFAULT 'temporary',
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INDEX `lupo_agent_context_snapshots_idx_created` (`created_ymdhis`);
INDEX `lupo_agent_context_snapshots_idx_parent` (`parent_snapshot_id`);
INDEX `lupo_agent_context_snapshots_idx_related_tool` (`related_tool_call_id`);
INDEX `lupo_agent_context_snapshots_idx_retention` (`retention_policy`, `expires_ymdhis`);
INDEX `lupo_agent_context_snapshots_idx_session_agent` (`session_id`, `actor_id`);
INDEX `lupo_agent_context_snapshots_idx_turn` (`session_id`, `conversation_turn`);
INDEX `lupo_agent_context_snapshots_idx_type_purpose` (`snapshot_type`, `snapshot_purpose`);

-- lupo_agent_dependencies
CREATE TABLE `lupo_agent_dependencies` (
  `agent_dependency_id` bigint NOT NULL,
  `agent_id` bigint NOT NULL,
  `depends_on_agent_id` bigint NOT NULL,
  `depends_on_agent_code` varchar(50) NOT NULL,
  `is_required` tinyint NOT NULL DEFAULT 1,
  `notes` text,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint,
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INDEX `lupo_agent_dependencies_idx_agent_id` (`agent_id`);
INDEX `lupo_agent_dependencies_idx_depends_on` (`depends_on_agent_id`);

-- lupo_agent_experiences
CREATE TABLE `lupo_agent_experiences` (
  `link_id` char(26) NOT NULL,
  `agent_id` bigint NOT NULL,
  `star_id` char(26) NOT NULL,
  `intensity` decimal(3,2),
  `context_id` bigint,
  `observed_ymdhis` bigint,
  `expressed_as_rgb` char(6),
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INDEX `lupo_agent_experiences_idx_agent` (`agent_id`);
INDEX `lupo_agent_experiences_idx_context` (`context_id`);
INDEX `lupo_agent_experiences_idx_star` (`star_id`);

-- lupo_agent_external_events
CREATE TABLE `lupo_agent_external_events` (
  `external_event_id` bigint NOT NULL,
  `agent_name` varchar(255) NOT NULL,
  `source_system` varchar(255) NOT NULL,
  `event_type` varchar(50) NOT NULL,
  `event_payload_json` json,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- lupo_agent_faucets
CREATE TABLE `lupo_agent_faucets` (
  `agent_faucet_id` bigint NOT NULL,
  `actor_id` bigint NOT NULL,
  `name` varchar(100) NOT NULL,
  `alias_name` varchar(100),
  `slug` varchar(100) NOT NULL,
  `description` text,
  `style_preset` varchar(100),
  `model_name` varchar(100),
  `provider` varchar(50),
  `temperature` float,
  `top_p` float,
  `max_tokens` int,
  `presence_penalty` float,
  `frequency_penalty` float,
  `system_prompt` text,
  `safety_json` json,
  `response_format` varchar(50),
  `capabilities_json` text,
  `is_default` tinyint NOT NULL DEFAULT 0,
  `domain_id` bigint NOT NULL DEFAULT 1,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL,
  `deleted_ymdhis` bigint,
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INDEX `lupo_agent_faucets_idx_agent` (`actor_id`);
INDEX `lupo_agent_faucets_idx_default` (`is_default`);
INDEX `lupo_agent_faucets_idx_domain` (`domain_id`);
INDEX `lupo_agent_faucets_idx_slug` (`slug`);

-- lupo_agent_faucet_credentials
CREATE TABLE `lupo_agent_faucet_credentials` (
  `agent_faucet_credential_id` int NOT NULL,
  `faucet_id` bigint NOT NULL,
  `provider` varchar(64) NOT NULL,
  `api_key` varbinary(512) NOT NULL,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL,
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INDEX `lupo_agent_faucet_credentials_idx_faucet` (`faucet_id`);

-- lupo_agent_files
CREATE TABLE `lupo_agent_files` (
  `file_id` bigint NOT NULL,
  `agent_id` bigint NOT NULL,
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
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INDEX `lupo_agent_files_idx_agent_id` (`agent_id`);
INDEX `lupo_agent_files_idx_file_hash` (`file_hash`);
INDEX `lupo_agent_files_idx_file_type` (`file_type`);
INDEX `lupo_agent_files_idx_is_deleted` (`is_deleted`);
INDEX `lupo_agent_files_idx_upload_ymdhis` (`upload_ymdhis`);

-- lupo_agent_heartbeats
CREATE TABLE `lupo_agent_heartbeats` (
  `heartbeat_id` bigint NOT NULL,
  `agent_slug` varchar(64) NOT NULL,
  `status` varchar(32) NOT NULL DEFAULT 'unknown',
  `last_heartbeat_ymdhis` bigint NOT NULL,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INDEX `lupo_agent_heartbeats_idx_agent_slug` (`agent_slug`);
INDEX `lupo_agent_heartbeats_idx_created_ymdhis` (`created_ymdhis`);
INDEX `lupo_agent_heartbeats_idx_is_deleted` (`is_deleted`);
INDEX `lupo_agent_heartbeats_idx_last_heartbeat_ymdhis` (`last_heartbeat_ymdhis`);

-- lupo_agent_properties
CREATE TABLE `lupo_agent_properties` (
  `agent_property_id` bigint NOT NULL,
  `actor_id` bigint NOT NULL,
  `domain_id` bigint NOT NULL,
  `property_key` varchar(100) NOT NULL,
  `property_value` text,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INDEX `lupo_agent_properties_idx_agent_domain` (`actor_id`, `domain_id`);
INDEX `lupo_agent_properties_idx_created_ymdhis` (`created_ymdhis`);
INDEX `lupo_agent_properties_idx_domain_id` (`domain_id`);
INDEX `lupo_agent_properties_idx_is_deleted` (`is_deleted`);
INDEX `lupo_agent_properties_idx_property_key` (`property_key`);
INDEX `lupo_agent_properties_idx_updated_ymdhis` (`updated_ymdhis`);
UNIQUE INDEX `lupo_agent_properties_unique_agent_domain_property` (`actor_id`, `domain_id`, `property_key`);

-- lupo_agent_tool_calls
CREATE TABLE `lupo_agent_tool_calls` (
  `agent_tool_call_id` bigint NOT NULL,
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
  `completed_ymdhis` bigint,
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INDEX `lupo_agent_tool_calls_idx_agent` (`agent_id`);
INDEX `lupo_agent_tool_calls_idx_domain` (`domain_id`);
INDEX `lupo_agent_tool_calls_idx_faucet` (`faucet_id`);
INDEX `lupo_agent_tool_calls_idx_message` (`message_id`);
INDEX `lupo_agent_tool_calls_idx_model` (`model_name`);
INDEX `lupo_agent_tool_calls_idx_parent` (`parent_call_id`);
INDEX `lupo_agent_tool_calls_idx_provider` (`provider`);
INDEX `lupo_agent_tool_calls_idx_thread` (`thread_id`);

-- lupo_agent_versions
CREATE TABLE `lupo_agent_versions` (
  `agent_version_id` bigint NOT NULL,
  `agent_id` bigint NOT NULL,
  `version_label` varchar(64) NOT NULL,
  `semver_major` int DEFAULT 0,
  `semver_minor` int DEFAULT 0,
  `semver_patch` int DEFAULT 0,
  `version_notes` text,
  `version_hash` varchar(128),
  `previous_version_id` bigint,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL,
  `is_deleted` smallint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INDEX `lupo_agent_versions_agent_id` (`agent_id`);
INDEX `lupo_agent_versions_semver_major` (`semver_major`, `semver_minor`, `semver_patch`);
INDEX `lupo_agent_versions_version_label` (`version_label`);

-- lupo_aliases
CREATE TABLE `lupo_aliases` (
  `alias_id` int NOT NULL,
  `slug` varchar(255) NOT NULL,
  `alias` varchar(255) NOT NULL,
  `alias_type` varchar(50) DEFAULT 'semantic',
  `created_at` bigint,
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INDEX `lupo_aliases_idx_slug` (`slug`);
UNIQUE INDEX `lupo_aliases_uniq_alias` (`alias`);

-- lupo_analytics_campaign_vars
CREATE TABLE `lupo_analytics_campaign_vars` (
  `campaign_var_id` bigint NOT NULL,
  `period` varchar(64) NOT NULL,
  `date_ymd` bigint,
  `yearmonth` int,
  `year` int,
  `campaign_key` varchar(255) NOT NULL,
  `campaign_value` varchar(500),
  `metadata_json` json,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- lupo_analytics_referers_periods
CREATE TABLE `lupo_analytics_referers_periods` (
  `analytics_referers_period_id` bigint NOT NULL,
  `content_id` bigint NOT NULL DEFAULT 0,
  `url_path` varchar(500) NOT NULL DEFAULT '',
  `referer_content_id` bigint NOT NULL DEFAULT 0,
  `referer_url_path` varchar(500) NOT NULL DEFAULT '',
  `parent_id` bigint NOT NULL DEFAULT 0,
  `level` int NOT NULL DEFAULT 1,
  `department_id` bigint NOT NULL DEFAULT 1,
  `period_type` varchar(64) NOT NULL,
  `period_date` bigint NOT NULL,
  `visits` int NOT NULL DEFAULT 0,
  `direct_visits` int NOT NULL DEFAULT 0,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL,
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INDEX `lupo_analytics_referers_periods_idx_content` (`content_id`, `period_date`);
INDEX `lupo_analytics_referers_periods_idx_department` (`department_id`, `period_date`);
INDEX `lupo_analytics_referers_periods_idx_level` (`level`, `period_date`);
INDEX `lupo_analytics_referers_periods_idx_period_date` (`period_date`);
INDEX `lupo_analytics_referers_periods_idx_referer` (`referer_content_id`, `period_date`);
UNIQUE INDEX `lupo_analytics_referers_periods_uq_referer_period` (`content_id`, `referer_content_id`, `period_type`, `period_date`);

-- lupo_analytics_visits
CREATE TABLE `lupo_analytics_visits` (
  `analytics_visit_id` bigint NOT NULL,
  `session_id` varchar(100) NOT NULL,
  `actor_id` bigint NOT NULL DEFAULT 0,
  `content_id` bigint,
  `federations_node_id` bigint NOT NULL,
  `url_path` varchar(500) NOT NULL DEFAULT '',
  `referer_url` varchar(500),
  `referer_domain` varchar(255),
  `referer_path` varchar(500),
  `came_from` varchar(500),
  `first_seen_ymdhis` bigint NOT NULL,
  `last_seen_ymdhis` bigint NOT NULL,
  `view_count` int NOT NULL DEFAULT 1,
  `seconds_active` int NOT NULL DEFAULT 0,
  `user_agent` varchar(255),
  `ip_address` varchar(45),
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL,
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- lupo_analytics_visits_daily
CREATE TABLE `lupo_analytics_visits_daily` (
  `analytics_visits_daily_id` bigint NOT NULL,
  `content_id` bigint NOT NULL DEFAULT 0,
  `url_path` varchar(500) NOT NULL DEFAULT '',
  `department_id` bigint NOT NULL DEFAULT 1,
  `date_ymd` bigint NOT NULL,
  `visits` int NOT NULL DEFAULT 0,
  `unique_sessions` int NOT NULL DEFAULT 0,
  `unique_actors` int NOT NULL DEFAULT 0,
  `direct_visits` int NOT NULL DEFAULT 0,
  `internal_visits` int NOT NULL DEFAULT 0,
  `entry_count` int NOT NULL DEFAULT 0,
  `exit_count` int NOT NULL DEFAULT 0,
  `total_seconds` int NOT NULL DEFAULT 0,
  `avg_seconds` int NOT NULL DEFAULT 0,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL,
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INDEX `lupo_analytics_visits_daily_idx_content` (`content_id`, `date_ymd`);
INDEX `lupo_analytics_visits_daily_idx_created` (`created_ymdhis`);
INDEX `lupo_analytics_visits_daily_idx_date_ymd` (`date_ymd`);
INDEX `lupo_analytics_visits_daily_idx_department` (`department_id`, `date_ymd`);
INDEX `lupo_analytics_visits_daily_idx_updated` (`updated_ymdhis`);
UNIQUE INDEX `lupo_analytics_visits_daily_uq_visits_daily` (`content_id`, `date_ymd`);

-- lupo_analytics_visits_monthly
CREATE TABLE `lupo_analytics_visits_monthly` (
  `analytics_visits_monthly_id` bigint NOT NULL,
  `content_id` bigint NOT NULL DEFAULT 0,
  `url_path` varchar(500) NOT NULL DEFAULT '',
  `department_id` bigint NOT NULL DEFAULT 1,
  `date_ym` bigint NOT NULL,
  `visits` int NOT NULL DEFAULT 0,
  `unique_sessions` int NOT NULL DEFAULT 0,
  `unique_actors` int NOT NULL DEFAULT 0,
  `direct_visits` int NOT NULL DEFAULT 0,
  `internal_visits` int NOT NULL DEFAULT 0,
  `entry_count` int NOT NULL DEFAULT 0,
  `exit_count` int NOT NULL DEFAULT 0,
  `total_seconds` int NOT NULL DEFAULT 0,
  `avg_seconds` int NOT NULL DEFAULT 0,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL,
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INDEX `lupo_analytics_visits_monthly_idx_content` (`content_id`, `date_ym`);
INDEX `lupo_analytics_visits_monthly_idx_created` (`created_ymdhis`);
INDEX `lupo_analytics_visits_monthly_idx_department` (`department_id`, `date_ym`);
INDEX `lupo_analytics_visits_monthly_idx_updated` (`updated_ymdhis`);
UNIQUE INDEX `lupo_analytics_visits_monthly_uq_visits_monthly` (`content_id`, `date_ym`);

-- lupo_analytics_visits_periods
CREATE TABLE `lupo_analytics_visits_periods` (
  `analytics_visits_period_id` bigint NOT NULL,
  `content_id` bigint NOT NULL DEFAULT 0,
  `url_path` varchar(500) NOT NULL DEFAULT '',
  `department_id` bigint NOT NULL DEFAULT 1,
  `period_type` varchar(64) NOT NULL,
  `period_date` bigint NOT NULL,
  `visits` int NOT NULL DEFAULT 0,
  `unique_sessions` int NOT NULL DEFAULT 0,
  `unique_actors` int NOT NULL DEFAULT 0,
  `direct_visits` int NOT NULL DEFAULT 0,
  `internal_visits` int NOT NULL DEFAULT 0,
  `entry_count` int NOT NULL DEFAULT 0,
  `exit_count` int NOT NULL DEFAULT 0,
  `total_seconds` int NOT NULL DEFAULT 0,
  `avg_seconds` int NOT NULL DEFAULT 0,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL,
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INDEX `lupo_analytics_visits_periods_idx_content` (`content_id`, `period_date`);
INDEX `lupo_analytics_visits_periods_idx_department` (`department_id`, `period_date`);
INDEX `lupo_analytics_visits_periods_idx_period_date` (`period_date`);
UNIQUE INDEX `lupo_analytics_visits_periods_uq_visits_period` (`content_id`, `period_type`, `period_date`);

-- lupo_anubis_deletion_log
CREATE TABLE `lupo_anubis_deletion_log` (
  `anubis_deletion_id` bigint NOT NULL,
  `table_name` varchar(255) NOT NULL,
  `record_id` bigint NOT NULL,
  `deleted_ymdhis` bigint NOT NULL,
  `deletion_type` varchar(64) NOT NULL,
  `replacement_table` varchar(255),
  `replacement_id` bigint,
  `anubis_operator` varchar(255) NOT NULL,
  `context_json` json,
  `notes` text,
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INDEX `lupo_anubis_deletion_log_idx_deleted_time` (`deleted_ymdhis`);
INDEX `lupo_anubis_deletion_log_idx_table_record` (`table_name`, `record_id`);

-- lupo_anubis_events
CREATE TABLE `lupo_anubis_events` (
  `anubis_event_id` bigint NOT NULL,
  `event_type` varchar(64) NOT NULL,
  `table_name` varchar(255) NOT NULL,
  `row_id` bigint NOT NULL,
  `timestamp_utc` bigint NOT NULL,
  `agent` varchar(255) NOT NULL,
  `details_json` text NOT NULL,
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- lupo_anubis_mirrored
CREATE TABLE `lupo_anubis_mirrored` (
  `anubis_mirrored_id` bigint NOT NULL,
  `table_name` varchar(255) NOT NULL,
  `original_id` bigint NOT NULL,
  `mirrored_json` text NOT NULL,
  `timestamp_utc` bigint NOT NULL,
  `agent` varchar(255) NOT NULL,
  `reason` varchar(255) NOT NULL,
  `lineage_chain` varchar(255),
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- lupo_anubis_orphaned
CREATE TABLE `lupo_anubis_orphaned` (
  `anubis_orphaned_id` bigint NOT NULL,
  `table_name` varchar(255) NOT NULL,
  `orphan_id` bigint NOT NULL,
  `timestamp_utc` bigint NOT NULL,
  `reason` varchar(255) NOT NULL,
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- lupo_anubis_redirects
CREATE TABLE `lupo_anubis_redirects` (
  `anubis_redirect_id` bigint NOT NULL,
  `table_name` varchar(255) NOT NULL,
  `old_id` bigint NOT NULL,
  `new_id` bigint NOT NULL,
  `timestamp_utc` bigint NOT NULL,
  `agent` varchar(255) NOT NULL,
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- lupo_anubis_revised
CREATE TABLE `lupo_anubis_revised` (
  `anubis_revised_id` bigint NOT NULL,
  `table_name` varchar(255) NOT NULL,
  `row_id` bigint NOT NULL,
  `timestamp_utc` bigint NOT NULL,
  `agent` varchar(255) NOT NULL,
  `revision_json` text NOT NULL,
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- lupo_api_clients
CREATE TABLE `lupo_api_clients` (
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
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INDEX `lupo_api_clients_idx_active` (`is_active`);
INDEX `lupo_api_clients_idx_actor` (`actor_id`);
INDEX `lupo_api_clients_idx_expires` (`expires_ymdhis`);
UNIQUE INDEX `lupo_api_clients_uq_client_key` (`client_key`);

-- lupo_api_rate_limits
CREATE TABLE `lupo_api_rate_limits` (
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
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INDEX `lupo_api_rate_limits_idx_actor_window` (`actor_id`, `window_ymdhis`);
INDEX `lupo_api_rate_limits_idx_domain_window` (`domain_id`, `window_ymdhis`);
INDEX `lupo_api_rate_limits_idx_endpoint` (`endpoint`);
INDEX `lupo_api_rate_limits_idx_ip_window` (`ip_address`, `window_ymdhis`);
INDEX `lupo_api_rate_limits_idx_token_window` (`api_token_id`, `window_ymdhis`);

-- lupo_api_tokens
CREATE TABLE `lupo_api_tokens` (
  `api_token_id` bigint NOT NULL,
  `domain_id` bigint NOT NULL DEFAULT 1,
  `actor_id` bigint NOT NULL DEFAULT 0,
  `token_key` varchar(255) NOT NULL,
  `token_label` varchar(150),
  `scopes` text,
  `is_active` tinyint NOT NULL DEFAULT 1,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `expires_ymdhis` bigint,
  `last_used_ymdhis` bigint,
  `created_ip` varchar(45),
  `last_used_ip` varchar(45),
  `notes` text,
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INDEX `lupo_api_tokens_idx_active` (`is_active`);
INDEX `lupo_api_tokens_idx_actor` (`actor_id`);
INDEX `lupo_api_tokens_idx_domain` (`domain_id`);
INDEX `lupo_api_tokens_idx_expires` (`expires_ymdhis`);
INDEX `lupo_api_tokens_idx_last_used` (`last_used_ymdhis`);
UNIQUE INDEX `lupo_api_tokens_uq_token_key` (`token_key`);

-- lupo_api_token_logs
CREATE TABLE `lupo_api_token_logs` (
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
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INDEX `lupo_api_token_logs_idx_actor` (`actor_id`);
INDEX `lupo_api_token_logs_idx_domain_time` (`domain_id`, `request_ymdhis`);
INDEX `lupo_api_token_logs_idx_endpoint` (`endpoint`);
INDEX `lupo_api_token_logs_idx_status` (`status_code`);
INDEX `lupo_api_token_logs_idx_token` (`api_token_id`);

-- lupo_api_webhooks
CREATE TABLE `lupo_api_webhooks` (
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
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INDEX `lupo_api_webhooks_idx_active` (`is_active`);
INDEX `lupo_api_webhooks_idx_actor` (`actor_id`);
INDEX `lupo_api_webhooks_idx_domain` (`domain_id`);
INDEX `lupo_api_webhooks_idx_expires` (`expires_ymdhis`);
INDEX `lupo_api_webhooks_idx_module` (`module_id`);

-- lupo_artifacts
CREATE TABLE `lupo_artifacts` (
  `artifact_id` bigint NOT NULL,
  `actor_id` bigint NOT NULL,
  `utc_timestamp` bigint NOT NULL,
  `entity_type` varchar(64) NOT NULL,
  `content` text NOT NULL,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INDEX `lupo_artifacts_idx_actor_id` (`actor_id`);
INDEX `lupo_artifacts_idx_entity_type` (`entity_type`);
INDEX `lupo_artifacts_idx_is_deleted` (`is_deleted`);
INDEX `lupo_artifacts_idx_utc_timestamp` (`utc_timestamp`);

-- lupo_atoms
CREATE TABLE `lupo_atoms` (
  `atom_id` bigint NOT NULL,
  `atom_name` varchar(255) NOT NULL,
  `context_id` bigint NOT NULL,
  `is_authoritative` tinyint NOT NULL DEFAULT 0,
  `value_json` json,
  `summary` text,
  `tags` varchar(255),
  `created_ymd` bigint NOT NULL DEFAULT 0,
  `updated_ymd` bigint NOT NULL DEFAULT 0,
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INDEX `lupo_atoms_idx_atom_context` (`atom_name`, `context_id`);
INDEX `lupo_atoms_idx_atom_name` (`atom_name`);
INDEX `lupo_atoms_idx_authoritative` (`is_authoritative`);
INDEX `lupo_atoms_idx_context_id` (`context_id`);

-- lupo_audit_log
CREATE TABLE `lupo_audit_log` (
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
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INDEX `lupo_audit_log_idx_entity` (`entity_type`, `entity_id`);
INDEX `lupo_audit_log_idx_event` (`event_type`);
INDEX `lupo_audit_log_idx_table` (`table_name`, `table_id`);


-- ============================================================
-- Batch 2 Complete
-- Total CREATE statements: 50
-- ============================================================
