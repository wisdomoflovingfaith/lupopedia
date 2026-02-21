-- ============================================================
-- Schema Rebuild Batch 3 4.0.24
-- Generated: 2026-02-21T03:08:58.755976Z
-- Tables in this batch: 50
-- ============================================================

-- lupo_auth_audit_log
CREATE TABLE `lupo_auth_audit_log` (
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
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INDEX `lupo_auth_audit_log_idx_crafty_operator_id` (`crafty_operator_id`);
INDEX `lupo_auth_audit_log_idx_created_at` (`created_at`);
INDEX `lupo_auth_audit_log_idx_event_type` (`event_type`);
INDEX `lupo_auth_audit_log_idx_success` (`success`);
INDEX `lupo_auth_audit_log_idx_system_context` (`system_context`);
INDEX `lupo_auth_audit_log_idx_user_id` (`user_id`);

-- lupo_auth_providers
CREATE TABLE `lupo_auth_providers` (
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
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

UNIQUE INDEX `lupo_auth_providers_unique_provider_name` (`provider_name`);

-- lupo_auth_users
CREATE TABLE `lupo_auth_users` (
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
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INDEX `lupo_auth_users_idx_created_ymdhis` (`created_ymdhis`);
INDEX `lupo_auth_users_idx_email` (`email`);
INDEX `lupo_auth_users_idx_is_active` (`is_active`);
INDEX `lupo_auth_users_idx_is_deleted` (`is_deleted`);
INDEX `lupo_auth_users_idx_updated_ymdhis` (`updated_ymdhis`);
UNIQUE INDEX `lupo_auth_users_unique_provider_user` (`auth_provider`, `provider_id`);
UNIQUE INDEX `lupo_auth_users_unique_username` (`username`);

-- lupo_bans_log
CREATE TABLE `lupo_bans_log` (
  `bans_log_id` bigint NOT NULL auto_increment,
  `actor_id` bigint NOT NULL,
  `uri` varchar(1024) NOT NULL DEFAULT '',
  `resolved_uri` varchar(1024) NOT NULL DEFAULT '',
  `ban_scope` varchar(64) NOT NULL DEFAULT 'router',
  `banned_ymdhis` bigint NOT NULL,
  `user_agent` varchar(500),
  `ip_address` varchar(45),
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INDEX `lupo_bans_log_idx_actor_id` (`actor_id`);
INDEX `lupo_bans_log_idx_ban_scope` (`ban_scope`);
INDEX `lupo_bans_log_idx_banned_ymdhis` (`banned_ymdhis`);

-- lupo_calibration_impacts
CREATE TABLE `lupo_calibration_impacts` (
  `calibration_impact_id` bigint NOT NULL,
  `calibration_id` bigint NOT NULL,
  `impact_type` varchar(64) NOT NULL,
  `impact_measurement` decimal(5,4) NOT NULL,
  `measurement_method` varchar(100) NOT NULL,
  `before_metrics_json` json,
  `after_metrics_json` json,
  `observation_period_hours` int DEFAULT 24,
  `measured_ymdhis` bigint NOT NULL,
  `impact_version` varchar(20) DEFAULT '3.0.0',
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INDEX `lupo_calibration_impacts_idx_calibration_impact` (`calibration_id`, `impact_type`);
INDEX `lupo_calibration_impacts_idx_impact_measurement` (`impact_measurement`);
INDEX `lupo_calibration_impacts_idx_measurement_time` (`measured_ymdhis`);

-- lupo_channels
CREATE TABLE `lupo_channels` (
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
  `status_flag` tinyint NOT NULL DEFAULT 1,
  `end_ymdhis` bigint,
  `duration_seconds` int,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  `aal_metadata_json` json,
  `fleet_composition_json` json,
  `awareness_version` varchar(20) DEFAULT '3.0.0',
  `channel_number` int,
  `parent_channel_id` bigint,
  `is_kernel` tinyint NOT NULL DEFAULT 0,
  `boot_sequence_order` int,
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INDEX `lupo_channels_idx_awareness_version` (`awareness_version`);
INDEX `lupo_channels_idx_channel_key` (`channel_key`);
INDEX `lupo_channels_idx_dates` (`end_ymdhis`);
INDEX `lupo_channels_idx_domain` (`federation_node_id`);
INDEX `lupo_channels_idx_status` (`status_flag`);
UNIQUE INDEX `lupo_channels_unq_channel_key_per_node` (`channel_key`, `federation_node_id`);

-- lupo_channel_boot_detail
CREATE TABLE `lupo_channel_boot_detail` (
  `detail_id` bigint NOT NULL,
  `boot_id` bigint NOT NULL,
  `channel_id` bigint NOT NULL,
  `load_start_time` bigint,
  `load_end_time` bigint,
  `load_status` varchar(64) NOT NULL DEFAULT 'started',
  `content_items_loaded` int NOT NULL DEFAULT 0,
  `total_content_items` int NOT NULL DEFAULT 0,
  `load_duration_ms` int,
  `error_message` text,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INDEX `lupo_channel_boot_detail_fk_boot_detail_channel` (`channel_id`);
INDEX `lupo_channel_boot_detail_idx_boot_channel` (`boot_id`, `channel_id`);
INDEX `lupo_channel_boot_detail_idx_load_status_time` (`load_status`, `load_start_time`);

-- lupo_channel_boot_log
CREATE TABLE `lupo_channel_boot_log` (
  `boot_id` bigint NOT NULL,
  `actor_id` bigint,
  `session_id` varchar(64),
  `boot_start_time` bigint,
  `boot_end_time` bigint,
  `boot_status` varchar(64) NOT NULL DEFAULT 'started',
  `channels_loaded` int NOT NULL DEFAULT 0,
  `total_channels` int NOT NULL DEFAULT 0,
  `error_details` json,
  `performance_metrics` json,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INDEX `lupo_channel_boot_log_idx_actor_session` (`actor_id`, `session_id`);
INDEX `lupo_channel_boot_log_idx_boot_status_time` (`boot_status`, `boot_start_time`);

-- lupo_channel_escalations
CREATE TABLE `lupo_channel_escalations` (
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
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INDEX `lupo_channel_escalations_idx_actor_id` (`actor_id`);
INDEX `lupo_channel_escalations_idx_channel_id` (`channel_id`);
INDEX `lupo_channel_escalations_idx_escalated_to_actor_id` (`escalated_to_actor_id`);
INDEX `lupo_channel_escalations_idx_thread_id` (`thread_id`);

-- lupo_channel_escalation_rules
CREATE TABLE `lupo_channel_escalation_rules` (
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
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INDEX `lupo_channel_escalation_rules_idx_channel_id` (`channel_id`);
INDEX `lupo_channel_escalation_rules_idx_rule_type` (`rule_type`);

-- lupo_channel_files
CREATE TABLE `lupo_channel_files` (
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
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INDEX `lupo_channel_files_idx_channel_id` (`channel_id`);
INDEX `lupo_channel_files_idx_file_hash` (`file_hash`);
INDEX `lupo_channel_files_idx_file_type` (`file_type`);
INDEX `lupo_channel_files_idx_is_deleted` (`is_deleted`);
INDEX `lupo_channel_files_idx_upload_ymdhis` (`upload_ymdhis`);

-- lupo_channel_logs
CREATE TABLE `lupo_channel_logs` (
  `channel_log_id` bigint NOT NULL,
  `channel_id` bigint NOT NULL,
  `actor_id` bigint NOT NULL,
  `role_type` varchar(64) NOT NULL,
  `log_type_id` bigint NOT NULL,
  `log_text` text NOT NULL,
  `metadata_json` json,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `pinned` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INDEX `lupo_channel_logs_idx_actor_id` (`actor_id`);
INDEX `lupo_channel_logs_idx_channel_id` (`channel_id`);
INDEX `lupo_channel_logs_idx_created_ymdhis` (`created_ymdhis`);
INDEX `lupo_channel_logs_idx_log_type_id` (`log_type_id`);
INDEX `lupo_channel_logs_idx_role_type` (`role_type`);

-- lupo_channel_log_types
CREATE TABLE `lupo_channel_log_types` (
  `log_type_id` bigint NOT NULL,
  `type_key` varchar(64) NOT NULL,
  `type_label` varchar(255) NOT NULL,
  `description` text,
  `is_system` tinyint NOT NULL DEFAULT 0,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

UNIQUE INDEX `lupo_channel_log_types_uniq_type_key` (`type_key`);

-- lupo_channel_state
CREATE TABLE `lupo_channel_state` (
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
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INDEX `lupo_channel_state_idx_channel_id` (`channel_id`);

-- lupo_cip_analytics
CREATE TABLE `lupo_cip_analytics` (
  `cip_analytics_id` bigint NOT NULL,
  `event_id` bigint NOT NULL,
  `defensiveness_index` decimal(5,4) NOT NULL DEFAULT 0.0000,
  `integration_velocity` decimal(5,4) NOT NULL DEFAULT 0.0000,
  `architectural_impact_score` decimal(5,4) NOT NULL DEFAULT 0.0000,
  `doctrine_propagation_depth` tinyint NOT NULL DEFAULT 0,
  `critique_source_weight` decimal(5,4) NOT NULL DEFAULT 0.5000,
  `subsystem_impact_json` json,
  `trend_analysis_json` json,
  `calculated_ymdhis` bigint NOT NULL,
  `recalculated_ymdhis` bigint,
  `analytics_version` varchar(20) DEFAULT '3.0.0',
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INDEX `lupo_cip_analytics_idx_architectural_impact` (`architectural_impact_score`);
INDEX `lupo_cip_analytics_idx_calculated_time` (`calculated_ymdhis`);
INDEX `lupo_cip_analytics_idx_defensiveness_index` (`defensiveness_index`);
INDEX `lupo_cip_analytics_idx_integration_velocity` (`integration_velocity`);
UNIQUE INDEX `lupo_cip_analytics_uk_event_analytics` (`event_id`);

-- lupo_cip_propagation_tracking
CREATE TABLE `lupo_cip_propagation_tracking` (
  `cip_propagation_tracking_id` bigint NOT NULL,
  `cip_event_id` bigint NOT NULL,
  `propagation_level` tinyint NOT NULL,
  `affected_subsystem` varchar(100) NOT NULL,
  `propagation_type` varchar(64) NOT NULL,
  `change_description` text NOT NULL,
  `propagation_strength` decimal(5,4) NOT NULL DEFAULT 1.0000,
  `completion_status` varchar(64) DEFAULT 'pending',
  `dependencies_json` json,
  `started_ymdhis` bigint,
  `completed_ymdhis` bigint,
  `propagation_version` varchar(20) DEFAULT '3.0.0',
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INDEX `lupo_cip_propagation_tracking_idx_completion_status` (`completion_status`);
INDEX `lupo_cip_propagation_tracking_idx_event_level` (`cip_event_id`, `propagation_level`);
INDEX `lupo_cip_propagation_tracking_idx_propagation_strength` (`propagation_strength`);
INDEX `lupo_cip_propagation_tracking_idx_subsystem` (`affected_subsystem`);

-- lupo_cip_trends
CREATE TABLE `lupo_cip_trends` (
  `cip_trend_id` bigint NOT NULL,
  `trend_period` varchar(64) NOT NULL,
  `period_start_ymdhis` bigint NOT NULL,
  `period_end_ymdhis` bigint NOT NULL,
  `avg_defensiveness_index` decimal(5,4) NOT NULL DEFAULT 0.0000,
  `avg_integration_velocity` decimal(5,4) NOT NULL DEFAULT 0.0000,
  `avg_architectural_impact` decimal(5,4) NOT NULL DEFAULT 0.0000,
  `total_events` int NOT NULL DEFAULT 0,
  `high_impact_events` int NOT NULL DEFAULT 0,
  `doctrine_updates_triggered` int NOT NULL DEFAULT 0,
  `trend_metadata_json` json,
  `calculated_ymdhis` bigint NOT NULL,
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INDEX `lupo_cip_trends_idx_high_impact` (`high_impact_events`);
INDEX `lupo_cip_trends_idx_period_range` (`period_start_ymdhis`, `period_end_ymdhis`);
UNIQUE INDEX `lupo_cip_trends_uk_period_trend` (`trend_period`, `period_start_ymdhis`);

-- lupo_collections
CREATE TABLE `lupo_collections` (
  `collection_id` bigint NOT NULL auto_increment,
  `federations_node_id` bigint NOT NULL,
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
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INDEX `lupo_collections_idx_actor` (`actor_id`);
INDEX `lupo_collections_idx_created_ymdhis` (`created_ymdhis`);
INDEX `lupo_collections_idx_department` (`department_id`);
INDEX `lupo_collections_idx_domain` (`federations_node_id`);
INDEX `lupo_collections_idx_is_deleted` (`is_deleted`);
INDEX `lupo_collections_idx_name` (`name`);
INDEX `lupo_collections_idx_sort_order` (`sort_order`);
INDEX `lupo_collections_idx_updated_ymdhis` (`updated_ymdhis`);
UNIQUE INDEX `lupo_collections_unique_collection_slug_domain` (`federations_node_id`, `slug`);

-- lupo_collection_tabs
CREATE TABLE `lupo_collection_tabs` (
  `collection_tab_id` bigint NOT NULL auto_increment,
  `collection_tab_parent_id` bigint,
  `collection_id` bigint NOT NULL,
  `federations_node_id` bigint NOT NULL,
  `department_id` bigint,
  `user_id` bigint,
  `sort_order` int DEFAULT 0,
  `name` varchar(255) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `color` char(6) DEFAULT '4caf50',
  `description` text,
  `is_hidden` tinyint NOT NULL DEFAULT 0,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL,
  `is_active` tinyint NOT NULL DEFAULT 1,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INDEX `lupo_collection_tabs_idx_collection_id` (`collection_id`);
INDEX `lupo_collection_tabs_idx_department` (`department_id`);
INDEX `lupo_collection_tabs_idx_is_active` (`is_active`);
INDEX `lupo_collection_tabs_idx_parent_tab_id` (`collection_tab_parent_id`);
INDEX `lupo_collection_tabs_idx_slug` (`slug`);

-- lupo_collection_tab_map
CREATE TABLE `lupo_collection_tab_map` (
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
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INDEX `lupo_collection_tab_map_idx_collection_tab` (`collection_tab_id`);
INDEX `lupo_collection_tab_map_idx_created_ymdhis` (`created_ymdhis`);
INDEX `lupo_collection_tab_map_idx_domain` (`federations_node_id`);
INDEX `lupo_collection_tab_map_idx_is_deleted` (`is_deleted`);
INDEX `lupo_collection_tab_map_idx_item` (`item_type`, `item_id`);
INDEX `lupo_collection_tab_map_idx_sort_order` (`sort_order`);
INDEX `lupo_collection_tab_map_idx_updated_ymdhis` (`updated_ymdhis`);
UNIQUE INDEX `lupo_collection_tab_map_unique_item_in_tab` (`collection_tab_id`, `item_type`, `item_id`);

-- lupo_collection_tab_paths
CREATE TABLE `lupo_collection_tab_paths` (
  `collection_tab_path_id` bigint NOT NULL,
  `collection_id` bigint NOT NULL,
  `collection_tab_id` bigint NOT NULL,
  `path` varchar(500) NOT NULL,
  `depth` int NOT NULL,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INDEX `lupo_collection_tab_paths_idx_collection` (`collection_id`);
INDEX `lupo_collection_tab_paths_idx_collection_tab` (`collection_tab_id`);
INDEX `lupo_collection_tab_paths_idx_path` (`path`);
UNIQUE INDEX `lupo_collection_tab_paths_unique_tab_path` (`collection_id`, `collection_tab_id`, `path`);

-- lupo_contents
CREATE TABLE `lupo_contents` (
  `content_id` bigint NOT NULL,
  `content_parent_id` bigint,
  `federation_node_id` bigint DEFAULT 1,
  `department_id` bigint,
  `actor_id` bigint,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `custom_path` varchar(255),
  `description` text,
  `seo_keywords` varchar(500),
  `body` text,
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
  `share_count` int DEFAULT 0,
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
  `file_path_from_root` varchar(500) COMMENT 'FLIP Header: path from repo root (4.0.13)',
  `file_last_modified_system_version` varchar(20) COMMENT 'FLIP: system version at last file edit',
  `file_last_modified_utc` bigint COMMENT 'FLIP: UTC last modified YYYYMMDDHHIISS',
  `tags` json,
  `dialog_notes` text,
  `atom_mappings` json COMMENT 'Consolidated from lupo_content_atom_map',
  `category_mappings` json COMMENT 'Consolidated from lupo_content_category_map',
  `likes_total` int DEFAULT 0 COMMENT 'Consolidated from lupo_content_engagement_summary',
  `shares_total` int DEFAULT 0 COMMENT 'Consolidated from lupo_content_engagement_summary',
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
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INDEX `lupo_contents_idx_content_parent` (`content_parent_id`);
INDEX `lupo_contents_idx_content_type` (`content_type`);
INDEX `lupo_contents_idx_created_ymdhis` (`created_ymdhis`);
UNIQUE INDEX `lupo_contents_idx_custom_path` (`custom_path`);
INDEX `lupo_contents_idx_department` (`department_id`);
INDEX `lupo_contents_idx_domain` (`federation_node_id`);
INDEX `lupo_contents_idx_file_path_from_root` (`file_path_from_root`);
INDEX `lupo_contents_idx_has_events` (`None`);
INDEX `lupo_contents_idx_has_hashtags` (`None`);
INDEX `lupo_contents_idx_has_likes_shares` (`likes_total`, `shares_total`);
INDEX `lupo_contents_idx_has_media` (`None`);
INDEX `lupo_contents_idx_is_active` (`is_active`);
INDEX `lupo_contents_idx_is_deleted` (`is_deleted`);
INDEX `lupo_contents_idx_status` (`status`);
INDEX `lupo_contents_idx_updated_ymdhis` (`updated_ymdhis`);
INDEX `lupo_contents_idx_user` (`actor_id`);
INDEX `lupo_contents_idx_visibility` (`visibility`);
UNIQUE INDEX `lupo_contents_unique_content_slug_domain` (`federation_node_id`, `slug`);

-- lupo_contexts
CREATE TABLE `lupo_contexts` (
  `context_id` int NOT NULL,
  `context_code` varchar(16) NOT NULL,
  `context_name` varchar(255) NOT NULL,
  `context_description` text,
  `parent_context_id` int,
  `is_system` tinyint NOT NULL DEFAULT 0,
  `is_fiction` tinyint NOT NULL DEFAULT 0,
  `is_installation_local` tinyint NOT NULL DEFAULT 0,
  `sort_order` int NOT NULL DEFAULT 0,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL,
  `weight_score` decimal(5,2) NOT NULL DEFAULT 0.00,
  `is_active` tinyint NOT NULL DEFAULT 1,
  `metadata_json` json,
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INDEX `lupo_contexts_idx_parent_context` (`parent_context_id`);
UNIQUE INDEX `lupo_contexts_uq_context_code` (`context_code`);

-- lupo_contexts_map
CREATE TABLE `lupo_contexts_map` (
  `contexts_map_id` bigint NOT NULL,
  `context_id` bigint NOT NULL,
  `item_type` varchar(50) NOT NULL,
  `item_slug` varchar(255) NOT NULL,
  `description` text,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint NOT NULL DEFAULT 0,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL DEFAULT 0,
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INDEX `lupo_contexts_map_idx_context_id` (`context_id`);
INDEX `lupo_contexts_map_idx_context_item` (`context_id`, `item_type`, `item_slug`);
INDEX `lupo_contexts_map_idx_is_deleted` (`is_deleted`);
INDEX `lupo_contexts_map_idx_item_slug` (`item_slug`);
INDEX `lupo_contexts_map_idx_item_type` (`item_type`);

-- lupo_crafty_syntax_auto_invite
CREATE TABLE `lupo_crafty_syntax_auto_invite` (
  `crafty_syntax_auto_invite_id` bigint NOT NULL auto_increment,
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
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INDEX `lupo_crafty_syntax_auto_invite_idx_created` (`created_ymdhis`);
INDEX `lupo_crafty_syntax_auto_invite_idx_department` (`department_id`);
INDEX `lupo_crafty_syntax_auto_invite_idx_operator` (`operator_user_id`);
INDEX `lupo_crafty_syntax_auto_invite_idx_page_url` (`page_url`);
INDEX `lupo_crafty_syntax_auto_invite_idx_status` (`is_active`, `is_deleted`);

-- lupo_crafty_syntax_chat_mod_departments
CREATE TABLE `lupo_crafty_syntax_chat_mod_departments` (
  `crafty_syntax_chat_mod_department_id` bigint NOT NULL auto_increment,
  `department_id` bigint NOT NULL DEFAULT 0,
  `module_id` bigint NOT NULL DEFAULT 0,
  `sort_order` int NOT NULL DEFAULT 0,
  `is_active` tinyint NOT NULL DEFAULT 1,
  `is_default` tinyint NOT NULL DEFAULT 0,
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- lupo_crafty_syntax_chat_questions
CREATE TABLE `lupo_crafty_syntax_chat_questions` (
  `crafty_syntax_chat_question_id` bigint NOT NULL auto_increment,
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
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INDEX `lupo_crafty_syntax_chat_questions_idx_department` (`department_id`);

-- lupo_crafty_syntax_layer_invites
CREATE TABLE `lupo_crafty_syntax_layer_invites` (
  `crafty_syntax_layer_invite_id` bigint NOT NULL auto_increment,
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
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INDEX `lupo_crafty_syntax_layer_invites_idx_active` (`is_active`);
INDEX `lupo_crafty_syntax_layer_invites_idx_created` (`created_ymdhis`);
INDEX `lupo_crafty_syntax_layer_invites_idx_department` (`department_name`);
INDEX `lupo_crafty_syntax_layer_invites_idx_name` (`layer_name`);
INDEX `lupo_crafty_syntax_layer_invites_idx_updated` (`updated_ymdhis`);
INDEX `lupo_crafty_syntax_layer_invites_idx_user` (`user_id`);

-- lupo_crafty_syntax_leave_message
CREATE TABLE `lupo_crafty_syntax_leave_message` (
  `crafty_syntax_leave_message_id` bigint NOT NULL auto_increment,
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
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INDEX `lupo_crafty_syntax_leave_message_idx_assigned` (`assigned_to`);
INDEX `lupo_crafty_syntax_leave_message_idx_created` (`created_ymdhis`);
INDEX `lupo_crafty_syntax_leave_message_idx_department` (`department_id`);
INDEX `lupo_crafty_syntax_leave_message_idx_email` (`email`);
INDEX `lupo_crafty_syntax_leave_message_idx_message_search` (`email`, `name`, `subject`, `message`);
INDEX `lupo_crafty_syntax_leave_message_idx_priority` (`priority`);
INDEX `lupo_crafty_syntax_leave_message_idx_status` (`status`);

-- lupo_crafty_user_mapping
CREATE TABLE `lupo_crafty_user_mapping` (
  `crafty_user_mapping_id` bigint NOT NULL auto_increment,
  `lupo_user_id` bigint,
  `crafty_operator_id` int,
  `mapping_type` varchar(50) NOT NULL DEFAULT 'manual',
  `notes` text,
  `created_at` bigint NOT NULL DEFAULT 0,
  `updated_at` bigint NOT NULL DEFAULT 0,
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INDEX `lupo_crafty_user_mapping_idx_crafty_operator_id` (`crafty_operator_id`);
INDEX `lupo_crafty_user_mapping_idx_lupo_user_id` (`lupo_user_id`);
INDEX `lupo_crafty_user_mapping_idx_mapping_type` (`mapping_type`);
UNIQUE INDEX `lupo_crafty_user_mapping_unique_crafty_operator_mapping` (`crafty_operator_id`);
UNIQUE INDEX `lupo_crafty_user_mapping_unique_lupo_user_mapping` (`lupo_user_id`);

-- lupo_crm_leads
CREATE TABLE `lupo_crm_leads` (
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
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- lupo_crm_lead_messages
CREATE TABLE `lupo_crm_lead_messages` (
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
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INDEX `lupo_crm_lead_messages_actor_id` (`actor_id`);
INDEX `lupo_crm_lead_messages_lead_id` (`lead_id`);

-- lupo_departments
CREATE TABLE `lupo_departments` (
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
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INDEX `lupo_departments_idx_federation_node` (`federation_node_id`);
INDEX `lupo_departments_idx_name` (`name`);
INDEX `lupo_departments_idx_type` (`department_type`);

-- lupo_department_metadata
CREATE TABLE `lupo_department_metadata` (
  `department_metadata_id` bigint NOT NULL auto_increment,
  `department_id` bigint NOT NULL,
  `metadata_json` json NOT NULL,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL,
  `is_active` tinyint NOT NULL DEFAULT 1,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

UNIQUE INDEX `lupo_department_metadata_uq_department_metadata` (`department_id`);

-- lupo_department_roles
CREATE TABLE `lupo_department_roles` (
  `department_role_id` bigint NOT NULL,
  `actor_id` bigint NOT NULL,
  `department_id` bigint NOT NULL,
  `role_key` varchar(64) NOT NULL,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INDEX `lupo_department_roles_idx_actor_id` (`actor_id`);
INDEX `lupo_department_roles_idx_department_id` (`department_id`);
INDEX `lupo_department_roles_idx_role_key` (`role_key`);

-- lupo_dialog_threads
CREATE TABLE `lupo_dialog_threads` (
  `dialog_thread_id` bigint NOT NULL,
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
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  `escalated_to_operator_id` bigint,
  `escalation_reason` varchar(255),
  `escalation_timestamp` bigint,
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INDEX `lupo_dialog_threads_idx_channel` (`channel_id`);
INDEX `lupo_dialog_threads_idx_created` (`created_ymdhis`);
INDEX `lupo_dialog_threads_idx_created_by_actor` (`created_by_actor_id`);
INDEX `lupo_dialog_threads_idx_deleted` (`is_deleted`);
INDEX `lupo_dialog_threads_idx_node` (`federation_node_id`);
INDEX `lupo_dialog_threads_idx_project` (`project_slug`);
INDEX `lupo_dialog_threads_idx_status` (`status`);
INDEX `lupo_dialog_threads_idx_task` (`task_name`);
INDEX `lupo_dialog_threads_idx_updated` (`updated_ymdhis`);

-- lupo_doctrine_evolution_audit
CREATE TABLE `lupo_doctrine_evolution_audit` (
  `doctrine_evolution_audit_id` bigint NOT NULL,
  `refinement_id` bigint NOT NULL,
  `evolution_step` tinyint NOT NULL,
  `step_description` varchar(255) NOT NULL,
  `step_status` varchar(64) DEFAULT 'pending',
  `step_metadata_json` json,
  `started_ymdhis` bigint,
  `completed_ymdhis` bigint,
  `audit_version` varchar(20) DEFAULT '3.0.0',
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INDEX `lupo_doctrine_evolution_audit_idx_completion_time` (`completed_ymdhis`);
INDEX `lupo_doctrine_evolution_audit_idx_refinement_step` (`refinement_id`, `evolution_step`);
INDEX `lupo_doctrine_evolution_audit_idx_step_status` (`step_status`);

-- lupo_doctrine_refinements
CREATE TABLE `lupo_doctrine_refinements` (
  `doctrine_refinement_id` bigint NOT NULL,
  `cip_event_id` bigint NOT NULL,
  `doctrine_file_path` varchar(500) NOT NULL,
  `refinement_type` varchar(64) NOT NULL,
  `change_description` text NOT NULL,
  `before_content_hash` varchar(64),
  `after_content_hash` varchar(64) NOT NULL,
  `impact_assessment_json` json,
  `approval_status` varchar(64) DEFAULT 'pending',
  `approved_by` varchar(100),
  `applied_ymdhis` bigint,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `refinement_version` varchar(20) DEFAULT '3.0.0',
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INDEX `lupo_doctrine_refinements_idx_applied_time` (`applied_ymdhis`);
INDEX `lupo_doctrine_refinements_idx_approval_status` (`approval_status`);
INDEX `lupo_doctrine_refinements_idx_cip_event` (`cip_event_id`);
INDEX `lupo_doctrine_refinements_idx_doctrine_file` (`doctrine_file_path`);

-- lupo_documents
CREATE TABLE `lupo_documents` (
  `document_id` bigint NOT NULL,
  `domain_id` int NOT NULL DEFAULT 1,
  `document_name` varchar(256) NOT NULL,
  `source_type` varchar(64) NOT NULL,
  `source_url` text,
  `mime_type` varchar(128),
  `file_size_bytes` int,
  `checksum_sha256` varchar(64),
  `metadata` json,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- lupo_document_chunks
CREATE TABLE `lupo_document_chunks` (
  `document_chunk_id` bigint NOT NULL,
  `document_id` bigint NOT NULL,
  `chunk_index` int NOT NULL,
  `chunk_content` mediumtext NOT NULL,
  `token_count` int,
  `metadata` json,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

UNIQUE INDEX `lupo_document_chunks_doc_chunk_unique` (`document_id`, `chunk_index`);
INDEX `lupo_document_chunks_document_id` (`document_id`);

-- lupo_document_embeddings
CREATE TABLE `lupo_document_embeddings` (
  `document_embedding_id` bigint NOT NULL,
  `chunk_id` bigint NOT NULL,
  `embedding_json` json NOT NULL,
  `embedding_model` varchar(128) NOT NULL,
  `embedding_version` varchar(64),
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INDEX `lupo_document_embeddings_chunk_id` (`chunk_id`);
INDEX `lupo_document_embeddings_embedding_model` (`embedding_model`);

-- lupo_edges
CREATE TABLE `lupo_edges` (
  `edge_id` bigint NOT NULL,
  `left_object_type` varchar(50) NOT NULL,
  `left_object_id` bigint NOT NULL,
  `right_object_type` varchar(50) NOT NULL,
  `right_object_id` bigint NOT NULL,
  `edge_type` varchar(100) NOT NULL,
  `channel_id` bigint,
  `channel_key` varchar(64),
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
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INDEX `lupo_edges_idx_actor` (`actor_id`);
INDEX `lupo_edges_idx_channel_semantic` (`channel_id`, `relationship_type`, `semantic_weight`);
INDEX `lupo_edges_idx_edge_type` (`edge_type`);
INDEX `lupo_edges_idx_is_deleted` (`is_deleted`);
INDEX `lupo_edges_idx_left` (`left_object_type`, `left_object_id`);
INDEX `lupo_edges_idx_relationship_type` (`relationship_type`);
INDEX `lupo_edges_idx_right` (`right_object_type`, `right_object_id`);
INDEX `lupo_edges_idx_semantic_weight` (`semantic_weight`);

-- lupo_edge_types
CREATE TABLE `lupo_edge_types` (
  `edge_type_id` bigint NOT NULL,
  `edge_type` varchar(100) NOT NULL,
  `description` text,
  `category` varchar(100),
  `created_ymd` bigint NOT NULL DEFAULT 0,
  `updated_ymd` bigint NOT NULL DEFAULT 0,
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INDEX `lupo_edge_types_idx_edge_type` (`edge_type`);

-- lupo_emotional_constellations
CREATE TABLE `lupo_emotional_constellations` (
  `constellation_id` char(26) NOT NULL,
  `framework_name` varchar(255) NOT NULL,
  `cultural_origin` varchar(255),
  `description` text,
  `stars` json NOT NULL,
  `is_canonical` tinyint NOT NULL DEFAULT 0,
  `canonical_for_culture` varchar(255),
  `created_ymdhis` bigint,
  `deprecated_ymdhis` bigint,
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- lupo_emotional_frameworks
CREATE TABLE `lupo_emotional_frameworks` (
  `framework_name` varchar(32) NOT NULL,
  `description` text,
  `is_default` tinyint NOT NULL DEFAULT 0,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL,
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- lupo_emotional_geometry_calibrations
CREATE TABLE `lupo_emotional_geometry_calibrations` (
  `emotional_geometry_calibration_id` bigint NOT NULL,
  `cip_analytics_id` bigint NOT NULL,
  `calibration_target` varchar(64) NOT NULL,
  `target_identifier` varchar(255) NOT NULL,
  `baseline_before_json` json,
  `baseline_after_json` json NOT NULL,
  `mood_framework` varchar(32) NOT NULL DEFAULT 'western_analytical',
  `tension_vectors_detected` json,
  `calibration_reason` text NOT NULL,
  `calibration_algorithm` varchar(100) DEFAULT 'cip_pattern_analysis',
  `confidence_score` decimal(5,4) NOT NULL DEFAULT 0.5000,
  `validation_status` varchar(64) DEFAULT 'pending',
  `applied_ymdhis` bigint,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `calibration_version` varchar(20) DEFAULT '3.0.0',
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INDEX `lupo_emotional_geometry_calibrations_idx_analytics_ref` (`cip_analytics_id`);
INDEX `lupo_emotional_geometry_calibrations_idx_confidence` (`confidence_score`);
INDEX `lupo_emotional_geometry_calibrations_idx_target` (`calibration_target`, `target_identifier`);
INDEX `lupo_emotional_geometry_calibrations_idx_validation_status` (`validation_status`);

-- lupo_emotional_stars
CREATE TABLE `lupo_emotional_stars` (
  `star_id` char(26) NOT NULL,
  `experience_hash` char(64),
  `experience_text` text NOT NULL,
  `cultural_context` json,
  `embodied_sensation` json,
  `created_by` bigint,
  `created_in_context` bigint,
  `first_observed_ymdhis` bigint,
  `observation_count` int NOT NULL DEFAULT 1,
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- lupo_emotional_translations
CREATE TABLE `lupo_emotional_translations` (
  `translation_id` bigint NOT NULL,
  `source_framework` varchar(32) NOT NULL,
  `source_state` text NOT NULL,
  `target_framework` varchar(32) NOT NULL,
  `target_state` text NOT NULL,
  `loss_score` decimal(3,2) NOT NULL,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `last_used_ymdhis` bigint NOT NULL,
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- lupo_entity_edges
CREATE TABLE `lupo_entity_edges` (
  `entity_edge_id` bigint NOT NULL,
  `source_entity_type` varchar(64) NOT NULL,
  `source_entity_id` bigint NOT NULL,
  `target_entity_type` varchar(64) NOT NULL,
  `target_entity_id` bigint NOT NULL,
  `edge_type` varchar(50) NOT NULL,
  `domain_id` bigint NOT NULL DEFAULT 1,
  `properties` json,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INDEX `lupo_entity_edges_idx_created` (`created_ymdhis`);
INDEX `lupo_entity_edges_idx_domain` (`domain_id`);
INDEX `lupo_entity_edges_idx_edge_type` (`edge_type`);
INDEX `lupo_entity_edges_idx_is_deleted` (`is_deleted`);
INDEX `lupo_entity_edges_idx_source` (`source_entity_type`, `source_entity_id`);
INDEX `lupo_entity_edges_idx_target` (`target_entity_type`, `target_entity_id`);

-- lupo_entity_properties
CREATE TABLE `lupo_entity_properties` (
  `entity_property_id` bigint NOT NULL,
  `entity_type` varchar(64) NOT NULL,
  `entity_id` bigint NOT NULL,
  `domain_id` bigint NOT NULL DEFAULT 1,
  `property_key` varchar(100) NOT NULL,
  `property_value` text,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INDEX `lupo_entity_properties_idx_created` (`created_ymdhis`);
INDEX `lupo_entity_properties_idx_domain` (`domain_id`);
INDEX `lupo_entity_properties_idx_entity` (`entity_type`, `entity_id`);
INDEX `lupo_entity_properties_idx_is_deleted` (`is_deleted`);
INDEX `lupo_entity_properties_idx_property_key` (`property_key`);
INDEX `lupo_entity_properties_idx_updated` (`updated_ymdhis`);
UNIQUE INDEX `lupo_entity_properties_unique_entity_domain_property` (`entity_type`, `entity_id`, `domain_id`, `property_key`);


-- ============================================================
-- Batch 3 Complete
-- Total CREATE statements: 50
-- ============================================================
