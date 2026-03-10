-- ============================================================
-- Schema Rebuild Batch 5 4.0.24
-- Generated: 2026-02-21T03:08:58.776837Z
-- Tables in this batch: 28
-- ============================================================

-- lupo_semantic_relationships
CREATE TABLE `lupo_semantic_relationships` (
  `relationship_id` bigint NOT NULL,
  `source_content_id` bigint NOT NULL,
  `target_content_id` bigint,
  `relationship_type` varchar(64) NOT NULL,
  `relationship_strength` decimal(3,2) NOT NULL DEFAULT 1.00,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INDEX `lupo_semantic_relationships_idx_created` (`created_ymdhis`);
INDEX `lupo_semantic_relationships_idx_created_ymdhis` (`created_ymdhis`, `relationship_type`, `source_content_id`, `target_content_id`);
INDEX `lupo_semantic_relationships_idx_relationship_type` (`relationship_type`);
INDEX `lupo_semantic_relationships_idx_source_content` (`source_content_id`);
INDEX `lupo_semantic_relationships_idx_target_content` (`target_content_id`);

-- lupo_semantic_search_index
CREATE TABLE `lupo_semantic_search_index` (
  `search_index_id` bigint NOT NULL,
  `index_name` varchar(255) NOT NULL,
  `index_type` varchar(64) NOT NULL,
  `description` text,
  `index_data` json NOT NULL,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL,
  `is_active` tinyint NOT NULL DEFAULT 1,
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INDEX `lupo_semantic_search_index_idx_created` (`created_ymdhis`);
INDEX `lupo_semantic_search_index_idx_created_ymdhis` (`created_ymdhis`, `is_active`);
INDEX `lupo_semantic_search_index_idx_index_type` (`index_type`);
INDEX `lupo_semantic_search_index_idx_is_active` (`is_active`);
UNIQUE INDEX `lupo_semantic_search_index_uk_index_name` (`index_name`);

-- lupo_semantic_tags
CREATE TABLE `lupo_semantic_tags` (
  `tag_id` bigint NOT NULL,
  `tag_name` varchar(255) NOT NULL,
  `tag_slug` varchar(255) NOT NULL,
  `description` text,
  `color` varchar(7) NOT NULL DEFAULT '#666666',
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL,
  `is_active` tinyint NOT NULL DEFAULT 1,
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INDEX `lupo_semantic_tags_idx_created` (`created_ymdhis`);
INDEX `lupo_semantic_tags_idx_created_ymdhis` (`created_ymdhis`, `is_active`);
INDEX `lupo_semantic_tags_idx_is_active` (`is_active`);
UNIQUE INDEX `lupo_semantic_tags_uk_tag_slug` (`tag_slug`);

-- lupo_semantic_translations
CREATE TABLE `lupo_semantic_translations` (
  `semantic_translation_id` bigint NOT NULL,
  `language_code` varchar(8) NOT NULL,
  `entity_type` varchar(32) NOT NULL,
  `entity_id` bigint NOT NULL,
  `translated_text` text NOT NULL,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint,
  `created_by` bigint,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INDEX `lupo_semantic_translations_idx_created` (`created_ymdhis`);
INDEX `lupo_semantic_translations_idx_deleted` (`is_deleted`);
INDEX `lupo_semantic_translations_idx_entity_lookup` (`entity_type`, `entity_id`, `language_code`);
INDEX `lupo_semantic_translations_idx_language_entity` (`language_code`, `entity_type`, `entity_id`);
INDEX `lupo_semantic_translations_idx_updated` (`updated_ymdhis`);
UNIQUE INDEX `lupo_semantic_translations_unq_translation` (`entity_type`, `entity_id`, `language_code`);

-- lupo_sessions
CREATE TABLE `lupo_sessions` (
  `session_id` varchar(255) NOT NULL,
  `federation_node_id` bigint NOT NULL DEFAULT 1,
  `actor_id` bigint NOT NULL DEFAULT 0,
  `channel_id` bigint NOT NULL DEFAULT 1,
  `ip_address` varchar(45) NOT NULL DEFAULT '',
  `user_agent` varchar(255) NOT NULL DEFAULT '',
  `device_id` varchar(100),
  `device_type` varchar(64),
  `auth_method` varchar(30),
  `auth_provider` varchar(50),
  `security_level` varchar(64) NOT NULL DEFAULT 'medium',
  `name_key` varchar(100),
  `is_named` tinyint NOT NULL DEFAULT 0,
  `is_authenticated` tinyint NOT NULL DEFAULT 0,
  `is_active` tinyint NOT NULL DEFAULT 1,
  `is_expired` tinyint NOT NULL DEFAULT 0,
  `is_revoked` tinyint NOT NULL DEFAULT 0,
  `session_data` text,
  `system_context` varchar(50),
  `metadata` json,
  `login_ymdhis` bigint,
  `last_seen_ymdhis` bigint NOT NULL,
  `expires_ymdhis` bigint,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INDEX `lupo_sessions_idx_actor` (`actor_id`);
INDEX `lupo_sessions_idx_cleanup` (`is_deleted`, `last_seen_ymdhis`);
INDEX `lupo_sessions_idx_created` (`created_ymdhis`);
INDEX `lupo_sessions_idx_device` (`device_id`);
INDEX `lupo_sessions_idx_domain` (`federation_node_id`);
INDEX `lupo_sessions_idx_expires` (`expires_ymdhis`);
INDEX `lupo_sessions_idx_last_seen` (`last_seen_ymdhis`);
INDEX `lupo_sessions_idx_security` (`security_level`);
INDEX `lupo_sessions_idx_status` (`is_active`, `is_expired`, `is_revoked`);

-- lupo_session_events
CREATE TABLE `lupo_session_events` (
  `session_event_id` bigint NOT NULL,
  `session_id` varchar(255) NOT NULL,
  `actor_id` bigint,
  `tab_id` varchar(255),
  `world_id` bigint,
  `world_key` varchar(255),
  `world_type` varchar(50),
  `event_type` varchar(100) NOT NULL,
  `event_data` json,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INDEX `lupo_session_events_idx_actor_id` (`actor_id`);
INDEX `lupo_session_events_idx_created_ymdhis` (`created_ymdhis`);
INDEX `lupo_session_events_idx_event_type` (`event_type`);
INDEX `lupo_session_events_idx_session_event_type` (`session_id`, `event_type`);
INDEX `lupo_session_events_idx_session_id` (`session_id`);
INDEX `lupo_session_events_idx_tab_id` (`tab_id`);
INDEX `lupo_session_events_idx_world_id` (`world_id`);

-- lupo_system_config
CREATE TABLE `lupo_system_config` (
  `system_config_id` bigint NOT NULL,
  `config_key` varchar(255) NOT NULL,
  `config_value` text NOT NULL,
  `actor_id` bigint NOT NULL DEFAULT 0,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL,
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

UNIQUE INDEX `lupo_system_config_config_key` (`config_key`);

-- lupo_system_logs
CREATE TABLE `lupo_system_logs` (
  `log_id` bigint NOT NULL,
  `event_type` varchar(64) NOT NULL,
  `severity` varchar(16) NOT NULL DEFAULT 'info',
  `actor_slug` varchar(64),
  `message` text NOT NULL,
  `context_json` json,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  `recursion_depth` tinyint DEFAULT 1,
  `observation_latency_ms` int,
  `temporal_anomaly_score` decimal(3,2),
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INDEX `lupo_system_logs_idx_actor_slug` (`actor_slug`);
INDEX `lupo_system_logs_idx_created_ymdhis` (`created_ymdhis`);
INDEX `lupo_system_logs_idx_event_type` (`event_type`);
INDEX `lupo_system_logs_idx_is_deleted` (`is_deleted`);
INDEX `lupo_system_logs_idx_severity` (`severity`);

-- lupo_tab_events
CREATE TABLE `lupo_tab_events` (
  `tab_event_id` bigint NOT NULL,
  `tab_id` varchar(255) NOT NULL,
  `session_id` varchar(255),
  `actor_id` bigint,
  `world_id` bigint,
  `world_key` varchar(255),
  `world_type` varchar(50),
  `event_type` varchar(100) NOT NULL,
  `event_data` json,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INDEX `lupo_tab_events_idx_actor_id` (`actor_id`);
INDEX `lupo_tab_events_idx_created_ymdhis` (`created_ymdhis`);
INDEX `lupo_tab_events_idx_event_type` (`event_type`);
INDEX `lupo_tab_events_idx_session_id` (`session_id`);
INDEX `lupo_tab_events_idx_tab_event_type` (`tab_id`, `event_type`);
INDEX `lupo_tab_events_idx_tab_id` (`tab_id`);
INDEX `lupo_tab_events_idx_world_id` (`world_id`);

-- lupo_temporal_coherence_snapshots
CREATE TABLE `lupo_temporal_coherence_snapshots` (
  `snapshot_id` bigint NOT NULL,
  `utc_anchor` bigint NOT NULL,
  `observation_latency_ms` int NOT NULL DEFAULT 0,
  `recursion_depth` tinyint NOT NULL DEFAULT 0,
  `self_awareness_score` decimal(3,2),
  `timestamp_integrity` varchar(32) NOT NULL DEFAULT 'unknown',
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INDEX `lupo_temporal_coherence_snapshots_idx_created_ymdhis` (`created_ymdhis`);
INDEX `lupo_temporal_coherence_snapshots_idx_is_deleted` (`is_deleted`);
INDEX `lupo_temporal_coherence_snapshots_idx_utc_anchor` (`utc_anchor`);

-- lupo_tldnr
CREATE TABLE `lupo_tldnr` (
  `tldnr_id` bigint NOT NULL,
  `slug` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content_text` text NOT NULL,
  `topic_type` varchar(100),
  `topic_reference` varchar(255),
  `system_version` varchar(20),
  `category` varchar(100),
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INDEX `lupo_tldnr_idx_category` (`category`);
INDEX `lupo_tldnr_idx_created` (`created_ymdhis`);
INDEX `lupo_tldnr_idx_is_deleted` (`is_deleted`);
INDEX `lupo_tldnr_idx_system_version` (`system_version`);
INDEX `lupo_tldnr_idx_topic_reference` (`topic_reference`);
INDEX `lupo_tldnr_idx_topic_type` (`topic_type`);
UNIQUE INDEX `lupo_tldnr_uniq_slug` (`slug`);

-- lupo_truth_answers
CREATE TABLE `lupo_truth_answers` (
  `truth_answer_id` bigint NOT NULL auto_increment,
  `truth_question_id` bigint NOT NULL,
  `actor_id` bigint NOT NULL DEFAULT 0,
  `answer_text` text NOT NULL,
  `confidence_score` decimal(5,2) NOT NULL DEFAULT 0.00,
  `evidence_score` decimal(5,2) NOT NULL DEFAULT 0.00,
  `contradiction_flag` tinyint NOT NULL DEFAULT 0,
  `likes_count` bigint NOT NULL DEFAULT 0,
  `shares_count` bigint NOT NULL DEFAULT 0,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INDEX `lupo_truth_answers_idx_question` (`truth_question_id`);

-- lupo_truth_evidence
CREATE TABLE `lupo_truth_evidence` (
  `truth_evidence_id` bigint NOT NULL,
  `truth_answer_id` bigint NOT NULL,
  `actor_id` bigint NOT NULL,
  `evidence_text` text NOT NULL,
  `evidence_type` varchar(50) NOT NULL DEFAULT '',
  `weight_score` decimal(5,2) NOT NULL DEFAULT 0.00,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INDEX `lupo_truth_evidence_actor_id` (`actor_id`);
INDEX `lupo_truth_evidence_truth_answer_id` (`truth_answer_id`);

-- lupo_truth_questions
CREATE TABLE `lupo_truth_questions` (
  `truth_question_id` bigint NOT NULL,
  `truth_question_parent_id` bigint,
  `actor_id` bigint NOT NULL DEFAULT 0,
  `qtype` varchar(50) NOT NULL DEFAULT 'unknown',
  `status` varchar(64) NOT NULL DEFAULT 'active',
  `sort_num` int NOT NULL DEFAULT 0,
  `slug` varchar(255) NOT NULL,
  `question_text` text NOT NULL,
  `format` varchar(64) NOT NULL DEFAULT 'text',
  `format_override` varchar(50),
  `view_count` bigint NOT NULL DEFAULT 0,
  `likes_count` bigint NOT NULL DEFAULT 0,
  `shares_count` bigint NOT NULL DEFAULT 0,
  `answer_count` bigint NOT NULL DEFAULT 0,
  `last_activity_ymdhis` bigint,
  `is_featured` tinyint NOT NULL DEFAULT 0,
  `is_verified` tinyint NOT NULL DEFAULT 0,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  `default_collection_id` bigint NOT NULL DEFAULT 0,
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INDEX `lupo_truth_questions_idx_parent` (`truth_question_parent_id`);
INDEX `lupo_truth_questions_idx_slug` (`slug`);

-- lupo_truth_questions_map
CREATE TABLE `lupo_truth_questions_map` (
  `truth_questions_map_id` bigint NOT NULL,
  `truth_question_id` bigint NOT NULL,
  `object_type` varchar(50) NOT NULL,
  `object_id` bigint NOT NULL,
  `actor_id` bigint NOT NULL,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INDEX `lupo_truth_questions_map_actor_id` (`actor_id`);
INDEX `lupo_truth_questions_map_object_id` (`object_id`);
INDEX `lupo_truth_questions_map_object_type` (`object_type`);
INDEX `lupo_truth_questions_map_truth_question_id` (`truth_question_id`);

-- lupo_truth_relations
CREATE TABLE `lupo_truth_relations` (
  `truth_relation_id` bigint NOT NULL,
  `left_object_type` varchar(50) NOT NULL,
  `left_object_id` bigint NOT NULL,
  `right_object_type` varchar(50) NOT NULL,
  `right_object_id` bigint NOT NULL,
  `relation_type` varchar(50) NOT NULL DEFAULT '',
  `actor_id` bigint NOT NULL,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INDEX `lupo_truth_relations_left_object_type` (`left_object_type`);
INDEX `lupo_truth_relations_relation_type` (`relation_type`);
INDEX `lupo_truth_relations_right_object_type` (`right_object_type`);

-- lupo_truth_sources
CREATE TABLE `lupo_truth_sources` (
  `truth_sourc_id` bigint NOT NULL,
  `truth_evidence_id` bigint NOT NULL,
  `actor_id` bigint NOT NULL,
  `source_url` text,
  `source_title` varchar(255) NOT NULL DEFAULT '',
  `source_type` varchar(50) NOT NULL DEFAULT '',
  `reliability_score` decimal(5,2) NOT NULL DEFAULT 0.00,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INDEX `lupo_truth_sources_actor_id` (`actor_id`);
INDEX `lupo_truth_sources_truth_evidence_id` (`truth_evidence_id`);

-- lupo_truth_topics
CREATE TABLE `lupo_truth_topics` (
  `truth_topic_id` bigint NOT NULL,
  `topic_name` varchar(255) NOT NULL DEFAULT '',
  `slug` varchar(255) NOT NULL DEFAULT '',
  `topic_description` text,
  `actor_id` bigint NOT NULL DEFAULT 0,
  `weight_score` decimal(5,2) NOT NULL DEFAULT 0.00,
  `importance_score` decimal(5,2) NOT NULL DEFAULT 0.00,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INDEX `lupo_truth_topics_actor_id` (`actor_id`);
INDEX `lupo_truth_topics_slug` (`slug`);
INDEX `lupo_truth_topics_topic_name` (`topic_name`);

-- lupo_analytics_paths
CREATE TABLE `lupo_analytics_paths` (
  `analytics_path_id` bigint NOT NULL auto_increment,
  `from_page_id` bigint,
  `to_page_id` bigint,
  `year_month_yyyymm` char(6) NOT NULL,
  `transition_type` varchar(64) NOT NULL,
  `transition_count` int NOT NULL DEFAULT 0,
  `metadata_json` json,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- lupo_referers
CREATE TABLE `lupo_referers` (
  `referer_id` bigint NOT NULL auto_increment,
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
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INDEX `lupo_referers_idx_actor_id` (`actor_id`);
INDEX `lupo_referers_idx_content_id` (`content_id`);
INDEX `lupo_referers_idx_date` (`date_ymd`);
INDEX `lupo_referers_idx_referer_content_id` (`referer_content_id`);
INDEX `lupo_referers_idx_referer_domain` (`referer_domain`);

-- lupo_truth_items
CREATE TABLE `lupo_truth_items` (
  `truth_item_id` bigint NOT NULL,
  `item_type` varchar(64) NOT NULL,
  `name` varchar(255),
  `slug` varchar(255),
  `body_text` text,
  `metadata_json` json,
  `created_ymdhis` bigint,
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- lupo_registry_open
CREATE TABLE `lupo_registry_open` (
  `entity_type` varchar(64) NOT NULL,
  `entity_index` int NOT NULL,
  `federation_node_id` bigint NOT NULL DEFAULT 1,
  `created_utc` bigint NOT NULL,
  `metadata_json` json,
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INDEX `lupo_registry_open_idx_entity_type_created_utc` (`entity_type`, `created_utc`);

-- lupo_visits
CREATE TABLE `lupo_visits` (
  `visit_id` bigint NOT NULL auto_increment,
  `content_id` bigint NOT NULL DEFAULT 0,
  `actor_id` bigint NOT NULL DEFAULT 0,
  `page_url` varchar(500) NOT NULL,
  `page_domain` varchar(255) NOT NULL,
  `page_path` varchar(500) NOT NULL,
  `date_ymd` int NOT NULL,
  `visits` int NOT NULL DEFAULT 0,
  `depth` int NOT NULL DEFAULT 0,
  `metadata_json` json,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL DEFAULT 0,
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INDEX `lupo_visits_content_id` (`content_id`);
INDEX `lupo_visits_date_ymd` (`date_ymd`);
INDEX `lupo_visits_page_domain` (`page_domain`);

-- lupo_uploads
CREATE TABLE `lupo_uploads` (
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
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INDEX `lupo_uploads_idx_actor_id` (`actor_id`);
INDEX `lupo_uploads_idx_channel_id` (`channel_id`);
INDEX `lupo_uploads_idx_created_ymdhis` (`created_ymdhis`);
INDEX `lupo_uploads_idx_file_extension` (`file_extension`);

-- lupo_user_comments
CREATE TABLE `lupo_user_comments` (
  `user_comment_id` bigint NOT NULL,
  `domain_id` bigint NOT NULL,
  `user_id` bigint NOT NULL,
  `content_id` bigint NOT NULL,
  `parent_comment_id` bigint,
  `comment_text` text NOT NULL,
  `user_agent` varchar(255),
  `ip_hash` char(64),
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint,
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INDEX `lupo_user_comments_idx_content_id` (`content_id`);
INDEX `lupo_user_comments_idx_created_ymdhis` (`created_ymdhis`);
INDEX `lupo_user_comments_idx_domain_id` (`domain_id`);
INDEX `lupo_user_comments_idx_ip_hash` (`ip_hash`);
INDEX `lupo_user_comments_idx_is_deleted` (`is_deleted`);
INDEX `lupo_user_comments_idx_parent_comment_id` (`parent_comment_id`);
INDEX `lupo_user_comments_idx_updated_ymdhis` (`updated_ymdhis`);
INDEX `lupo_user_comments_idx_user_id` (`user_id`);

-- lupo_world_events
CREATE TABLE `lupo_world_events` (
  `world_event_id` bigint NOT NULL,
  `world_id` bigint NOT NULL,
  `actor_id` bigint NOT NULL,
  `event_type` varchar(100) NOT NULL,
  `event_data` json,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INDEX `lupo_world_events_idx_actor_id` (`actor_id`);
INDEX `lupo_world_events_idx_created_ymdhis` (`created_ymdhis`);
INDEX `lupo_world_events_idx_event_type` (`event_type`);
INDEX `lupo_world_events_idx_world_id` (`world_id`);

-- lupo_world_registry
CREATE TABLE `lupo_world_registry` (
  `world_id` bigint NOT NULL,
  `world_key` varchar(255) NOT NULL,
  `world_type` varchar(64) NOT NULL,
  `world_label` varchar(255) NOT NULL,
  `world_metadata` json,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL,
  `is_active` tinyint NOT NULL DEFAULT 1,
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INDEX `lupo_world_registry_idx_created_ymdhis` (`created_ymdhis`);
INDEX `lupo_world_registry_idx_is_active` (`is_active`);
INDEX `lupo_world_registry_idx_world_type` (`world_type`);
UNIQUE INDEX `lupo_world_registry_unique_world_key` (`world_key`);


-- ============================================================
-- Batch 5 Complete
-- Total CREATE statements: 28
-- ============================================================
