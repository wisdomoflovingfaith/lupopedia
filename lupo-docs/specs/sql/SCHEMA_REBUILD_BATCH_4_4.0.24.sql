-- ============================================================
-- Schema Rebuild Batch 4 4.0.24
-- Generated: 2026-02-21T03:08:58.766625Z
-- Tables in this batch: 50
-- ============================================================

-- lupo_event_log
CREATE TABLE `lupo_event_log` (
  `event_id` bigint NOT NULL,
  `event_type` varchar(100) NOT NULL,
  `event_data` json,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INDEX `lupo_event_log_idx_created_ymdhis` (`created_ymdhis`);
INDEX `lupo_event_log_idx_event_type` (`event_type`);

-- lupo_event_metadata
CREATE TABLE `lupo_event_metadata` (
  `metadata_id` bigint NOT NULL,
  `event_id` bigint NOT NULL,
  `metadata_key` varchar(100) NOT NULL,
  `metadata_value` text,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INDEX `lupo_event_metadata_idx_created_ymdhis` (`created_ymdhis`);
INDEX `lupo_event_metadata_idx_event_id` (`event_id`);
INDEX `lupo_event_metadata_idx_metadata_key` (`metadata_key`);

-- lupo_federation_categories
CREATE TABLE `lupo_federation_categories` (
  `federation_category_id` bigint NOT NULL,
  `category_name` varchar(255) NOT NULL,
  `category_slug` varchar(255) NOT NULL,
  `category_description` text,
  `meta_json` json,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint NOT NULL DEFAULT 0,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL DEFAULT 0,
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INDEX `lupo_federation_categories_idx_category_slug` (`category_slug`);
INDEX `lupo_federation_categories_idx_is_deleted` (`is_deleted`);

-- lupo_federation_category_map
CREATE TABLE `lupo_federation_category_map` (
  `federation_category_map_id` bigint NOT NULL,
  `federation_node_id` bigint NOT NULL,
  `federation_category_id` bigint NOT NULL,
  `meta_json` json,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint NOT NULL DEFAULT 0,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL DEFAULT 0,
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INDEX `lupo_federation_category_map_idx_category` (`federation_category_id`);
INDEX `lupo_federation_category_map_idx_is_deleted` (`is_deleted`);
INDEX `lupo_federation_category_map_idx_node` (`federation_node_id`);

-- lupo_federation_discovery
CREATE TABLE `lupo_federation_discovery` (
  `federation_discovery_id` bigint NOT NULL,
  `domain` varchar(255) NOT NULL,
  `install_url` varchar(500),
  `is_lupopedia` tinyint NOT NULL DEFAULT 0,
  `last_seen_ymdhis` bigint,
  `first_seen_ymdhis` bigint,
  `hashtag_count` bigint DEFAULT 0,
  `question_count` bigint DEFAULT 0,
  `atom_count` bigint DEFAULT 0,
  `context_count` bigint DEFAULT 0,
  `collection_count` bigint DEFAULT 0,
  `keywords` varchar(500),
  `description` text,
  `import_hashtags` tinyint NOT NULL DEFAULT 0,
  `import_questions` tinyint NOT NULL DEFAULT 0,
  `import_atoms` tinyint NOT NULL DEFAULT 0,
  `import_contexts` tinyint NOT NULL DEFAULT 0,
  `import_collections` tinyint NOT NULL DEFAULT 0,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL,
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INDEX `lupo_federation_discovery_idx_domain` (`domain`);

-- lupo_federation_nodes
CREATE TABLE `lupo_federation_nodes` (
  `federation_node_id` bigint NOT NULL,
  `node_base_url` varchar(500) NOT NULL,
  `default_department_id` bigint,
  `node_name` varchar(255),
  `node_description` text,
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
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INDEX `lupo_federation_nodes_idx_is_deleted` (`is_deleted`);
INDEX `lupo_federation_nodes_idx_node_base_url` (`node_base_url`);
INDEX `lupo_federation_nodes_idx_status` (`status`);
INDEX `lupo_federation_nodes_idx_trust_level` (`trust_level`);

-- lupo_governance_overrides
CREATE TABLE `lupo_governance_overrides` (
  `governance_overrid_id` bigint NOT NULL,
  `agent_id` bigint,
  `applied_by_agent` bigint,
  `override_type` varchar(100) NOT NULL,
  `target_key` varchar(150),
  `old_value` text,
  `new_value` text,
  `reason_text` text,
  `metadata_json` json,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `expires_ymdhis` bigint,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INDEX `lupo_governance_overrides_idx_agent` (`agent_id`);
INDEX `lupo_governance_overrides_idx_applied_by` (`applied_by_agent`);
INDEX `lupo_governance_overrides_idx_created` (`created_ymdhis`);
INDEX `lupo_governance_overrides_idx_target` (`target_key`);
INDEX `lupo_governance_overrides_idx_type` (`override_type`);

-- lupo_gov_events
CREATE TABLE `lupo_gov_events` (
  `gov_event_id` bigint NOT NULL,
  `utc_group_id` bigint NOT NULL,
  `semantic_utc_version` varchar(50) NOT NULL,
  `canonical_path` varchar(500) NOT NULL,
  `event_type` varchar(100) NOT NULL,
  `title` varchar(255) NOT NULL,
  `directive_block` text,
  `tldr_summary` text,
  `metadata_json` json,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint,
  `is_active` tinyint NOT NULL DEFAULT 1,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INDEX `lupo_gov_events_idx_created_ymdhis` (`created_ymdhis`);
INDEX `lupo_gov_events_idx_event_type` (`event_type`);
INDEX `lupo_gov_events_idx_is_active` (`is_active`);
INDEX `lupo_gov_events_idx_is_deleted` (`is_deleted`);
INDEX `lupo_gov_events_idx_semantic_version` (`semantic_utc_version`);
INDEX `lupo_gov_events_idx_utc_group` (`utc_group_id`);
UNIQUE INDEX `lupo_gov_events_unique_canonical_path` (`canonical_path`);

-- lupo_gov_event_actor_edges
CREATE TABLE `lupo_gov_event_actor_edges` (
  `edge_id` bigint NOT NULL,
  `gov_event_id` bigint NOT NULL,
  `actor_id` bigint NOT NULL,
  `edge_type` varchar(100) NOT NULL,
  `edge_properties` text,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INDEX `lupo_gov_event_actor_edges_idx_actor` (`actor_id`);
INDEX `lupo_gov_event_actor_edges_idx_created_ymdhis` (`created_ymdhis`);
INDEX `lupo_gov_event_actor_edges_idx_edge_type` (`edge_type`);
INDEX `lupo_gov_event_actor_edges_idx_gov_event` (`gov_event_id`);
INDEX `lupo_gov_event_actor_edges_idx_is_deleted` (`is_deleted`);
UNIQUE INDEX `lupo_gov_event_actor_edges_unique_gov_event_actor_edge` (`gov_event_id`, `actor_id`, `edge_type`);

-- lupo_gov_event_conflicts
CREATE TABLE `lupo_gov_event_conflicts` (
  `gov_event_conflict_id` bigint NOT NULL,
  `gov_event_id` bigint NOT NULL,
  `conflicts_with_event_id` bigint NOT NULL,
  `conflict_type` varchar(50) NOT NULL,
  `severity` varchar(20) NOT NULL,
  `notes` text,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INDEX `lupo_gov_event_conflicts_idx_conflicts_with_event_id` (`conflicts_with_event_id`);
INDEX `lupo_gov_event_conflicts_idx_gov_event_id` (`gov_event_id`);

-- lupo_gov_event_dependencies
CREATE TABLE `lupo_gov_event_dependencies` (
  `gov_event_dependency_id` bigint NOT NULL,
  `gov_event_id` bigint NOT NULL,
  `depends_on_event_id` bigint NOT NULL,
  `dependency_type` varchar(50) NOT NULL,
  `notes` text,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INDEX `lupo_gov_event_dependencies_idx_depends_on_event_id` (`depends_on_event_id`);
INDEX `lupo_gov_event_dependencies_idx_gov_event_id` (`gov_event_id`);

-- lupo_gov_event_references
CREATE TABLE `lupo_gov_event_references` (
  `reference_id` bigint NOT NULL,
  `gov_event_id` bigint NOT NULL,
  `reference_type` varchar(100) NOT NULL,
  `reference_title` varchar(255) NOT NULL,
  `reference_url` varchar(1000),
  `reference_content` text,
  `order_sequence` int NOT NULL DEFAULT 0,
  `metadata_json` json,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INDEX `lupo_gov_event_references_idx_created_ymdhis` (`created_ymdhis`);
INDEX `lupo_gov_event_references_idx_gov_event` (`gov_event_id`);
INDEX `lupo_gov_event_references_idx_is_deleted` (`is_deleted`);
INDEX `lupo_gov_event_references_idx_order_sequence` (`order_sequence`);
INDEX `lupo_gov_event_references_idx_reference_type` (`reference_type`);

-- lupo_gov_timeline_nodes
CREATE TABLE `lupo_gov_timeline_nodes` (
  `timeline_node_id` bigint NOT NULL,
  `gov_event_id` bigint NOT NULL,
  `node_type` varchar(100) NOT NULL,
  `node_title` varchar(255) NOT NULL,
  `node_description` text,
  `node_timestamp` bigint NOT NULL,
  `parent_node_id` bigint,
  `order_sequence` int NOT NULL DEFAULT 0,
  `metadata_json` json,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INDEX `lupo_gov_timeline_nodes_idx_created_ymdhis` (`created_ymdhis`);
INDEX `lupo_gov_timeline_nodes_idx_gov_event` (`gov_event_id`);
INDEX `lupo_gov_timeline_nodes_idx_is_deleted` (`is_deleted`);
INDEX `lupo_gov_timeline_nodes_idx_node_timestamp` (`node_timestamp`);
INDEX `lupo_gov_timeline_nodes_idx_node_type` (`node_type`);
INDEX `lupo_gov_timeline_nodes_idx_order_sequence` (`order_sequence`);
INDEX `lupo_gov_timeline_nodes_idx_parent_node` (`parent_node_id`);

-- lupo_gov_valuations
CREATE TABLE `lupo_gov_valuations` (
  `valuation_id` bigint NOT NULL,
  `gov_event_id` bigint NOT NULL,
  `valuation_type` varchar(100) NOT NULL,
  `valuation_metric` varchar(255) NOT NULL,
  `valuation_value` decimal(20,8),
  `valuation_text` text,
  `valuation_currency` varchar(10),
  `valuation_unit` varchar(50),
  `confidence_score` decimal(5,4),
  `metadata_json` json,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INDEX `lupo_gov_valuations_idx_created_ymdhis` (`created_ymdhis`);
INDEX `lupo_gov_valuations_idx_gov_event` (`gov_event_id`);
INDEX `lupo_gov_valuations_idx_is_deleted` (`is_deleted`);
INDEX `lupo_gov_valuations_idx_valuation_metric` (`valuation_metric`);
INDEX `lupo_gov_valuations_idx_valuation_type` (`valuation_type`);

-- lupo_hashtags
CREATE TABLE `lupo_hashtags` (
  `hashtag_id` bigint NOT NULL,
  `hashtag_slug` varchar(255) NOT NULL,
  `description` text,
  `meta_json` json,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint NOT NULL DEFAULT 0,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL DEFAULT 0,
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INDEX `lupo_hashtags_idx_hashtag_slug` (`hashtag_slug`);
INDEX `lupo_hashtags_idx_is_deleted` (`is_deleted`);

-- lupo_help_topics
CREATE TABLE `lupo_help_topics` (
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
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INDEX `lupo_help_topics_idx_author` (`author_actor_id`);
INDEX `lupo_help_topics_idx_category` (`category`);
INDEX `lupo_help_topics_idx_created` (`created_ymdhis`);
INDEX `lupo_help_topics_idx_parent` (`parent_slug`);
INDEX `lupo_help_topics_idx_slug` (`slug`);
UNIQUE INDEX `lupo_help_topics_slug` (`slug`);

-- lupo_help_tree
CREATE TABLE `lupo_help_tree` (
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
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INDEX `lupo_help_tree_idx_action` (`action_type`, `action_target`);
INDEX `lupo_help_tree_idx_content` (`content_id`);
INDEX `lupo_help_tree_idx_created` (`created_ymdhis`);
INDEX `lupo_help_tree_idx_department` (`department_id`);
INDEX `lupo_help_tree_idx_parent` (`parent_id`);
INDEX `lupo_help_tree_idx_sort` (`parent_id`, `sort_order`);
INDEX `lupo_help_tree_idx_updated` (`updated_ymdhis`);

-- lupo_hotfix_registry
CREATE TABLE `lupo_hotfix_registry` (
  `hotfix_id` int NOT NULL,
  `hotfix_version` varchar(20) NOT NULL,
  `applied_ymdhis` bigint NOT NULL,
  `applied_by_actor_id` int,
  `description` text,
  `metadata_json` json,
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- lupo_human_history_meta
CREATE TABLE `lupo_human_history_meta` (
  `meta_id` bigint NOT NULL,
  `event_key` varchar(255) NOT NULL,
  `tensor_mapping` varchar(32) NOT NULL,
  `philosophical_reference` varchar(255) NOT NULL,
  `system_impact` text NOT NULL,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL,
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- lupo_interface_translations
CREATE TABLE `lupo_interface_translations` (
  `interface_translation_id` bigint NOT NULL,
  `language_code` varchar(8) NOT NULL,
  `translation_key` varchar(128) NOT NULL,
  `translation_text` text NOT NULL,
  `context` varchar(64),
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint,
  `created_by` bigint,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  `version` int DEFAULT 1,
  `is_approved` tinyint DEFAULT 0,
  `approved_by` bigint,
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INDEX `lupo_interface_translations_idx_approved` (`is_approved`);
INDEX `lupo_interface_translations_idx_created` (`created_ymdhis`);
INDEX `lupo_interface_translations_idx_deleted` (`is_deleted`);
INDEX `lupo_interface_translations_idx_updated` (`updated_ymdhis`);
UNIQUE INDEX `lupo_interface_translations_unq_language_key` (`language_code`, `translation_key`);

-- lupo_interpretation_log
CREATE TABLE `lupo_interpretation_log` (
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
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INDEX `lupo_interpretation_log_idx_agent` (`agent_id`);
INDEX `lupo_interpretation_log_idx_confidence` (`confidence_score`);
INDEX `lupo_interpretation_log_idx_created` (`created_ymdhis`);
INDEX `lupo_interpretation_log_idx_deleted` (`is_deleted`);
INDEX `lupo_interpretation_log_idx_entity` (`entity_type`, `entity_id`);
INDEX `lupo_interpretation_log_idx_updated` (`updated_ymdhis`);

-- lupo_kapu_events
CREATE TABLE `lupo_kapu_events` (
  `kapu_id` bigint NOT NULL,
  `agent_id` varchar(255),
  `imposed_by_actor_id` varchar(255),
  `kapu_type` varchar(64),
  `restrictions` json,
  `restoration_plan` json,
  `kapakai_level` decimal(3,2),
  `review_schedule` json,
  `accepted_at` bigint,
  `appealed_at` bigint,
  `active` tinyint DEFAULT 1,
  `created_at` bigint,
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- lupo_kapu_restoration_paths
CREATE TABLE `lupo_kapu_restoration_paths` (
  `path_id` bigint NOT NULL,
  `agent_id` varchar(255),
  `kapu_reason_code` varchar(100),
  `learning_modules` json,
  `emotional_targets` json,
  `restoration_rituals` json,
  `kapu_companion_agent_id` varchar(255),
  `completed_at` bigint,
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- lupo_labs_declarations
CREATE TABLE `lupo_labs_declarations` (
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
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INDEX `lupo_labs_declarations_idx_actor_id` (`actor_id`);
INDEX `lupo_labs_declarations_idx_actor_status` (`actor_id`, `validation_status`, `is_deleted`);
INDEX `lupo_labs_declarations_idx_certificate_id` (`certificate_id`);
INDEX `lupo_labs_declarations_idx_next_revalidation` (`next_revalidation_ymdhis`);
INDEX `lupo_labs_declarations_idx_revalidation_due` (`next_revalidation_ymdhis`, `validation_status`, `is_deleted`);
INDEX `lupo_labs_declarations_idx_validation_status` (`validation_status`);

-- lupo_labs_violations
CREATE TABLE `lupo_labs_violations` (
  `labs_violation_id` bigint NOT NULL,
  `actor_id` bigint NOT NULL,
  `certificate_id` varchar(64) NOT NULL,
  `violation_code` varchar(64) NOT NULL,
  `violation_description` text,
  `violation_metadata` json,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INDEX `lupo_labs_violations_idx_actor` (`actor_id`);
INDEX `lupo_labs_violations_idx_certificate` (`certificate_id`);
INDEX `lupo_labs_violations_idx_created` (`created_ymdhis`);
INDEX `lupo_labs_violations_idx_deleted` (`is_deleted`);
INDEX `lupo_labs_violations_idx_violation_code` (`violation_code`);

-- lupo_legacy_content_mapping
CREATE TABLE `lupo_legacy_content_mapping` (
  `mapping_id` bigint NOT NULL,
  `legacy_url` varchar(255) NOT NULL,
  `semantic_url` varchar(255) NOT NULL,
  `content_type` varchar(64) NOT NULL,
  `content_id` bigint,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL,
  `is_active` tinyint NOT NULL DEFAULT 1,
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INDEX `lupo_legacy_content_mapping_idx_content_id` (`content_id`);
INDEX `lupo_legacy_content_mapping_idx_content_type` (`content_type`);
INDEX `lupo_legacy_content_mapping_idx_created` (`created_ymdhis`);
INDEX `lupo_legacy_content_mapping_idx_created_ymdhis` (`created_ymdhis`, `is_active`);
INDEX `lupo_legacy_content_mapping_idx_is_active` (`is_active`);
INDEX `lupo_legacy_content_mapping_idx_semantic_url` (`semantic_url`);
UNIQUE INDEX `lupo_legacy_content_mapping_uk_legacy_url` (`legacy_url`);

-- lupo_memory_events
CREATE TABLE `lupo_memory_events` (
  `memory_event_id` bigint NOT NULL,
  `actor_id` int NOT NULL,
  `event_type` varchar(64) NOT NULL,
  `content` text NOT NULL,
  `metadata` json,
  `token_count` int,
  `importance` tinyint DEFAULT 0,
  `embedding_status` varchar(64) DEFAULT 'none',
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INDEX `lupo_memory_events_idx_actor_created` (`actor_id`, `created_ymdhis`);
INDEX `lupo_memory_events_idx_actor_type` (`actor_id`, `event_type`);

-- lupo_memory_rollups
CREATE TABLE `lupo_memory_rollups` (
  `memory_rollup_id` bigint NOT NULL,
  `actor_id` int NOT NULL,
  `summary` text NOT NULL,
  `source_event_ids` text NOT NULL,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INDEX `lupo_memory_rollups_idx_actor_created` (`actor_id`, `created_ymdhis`);

-- lupo_meta_log_events
CREATE TABLE `lupo_meta_log_events` (
  `event_id` bigint NOT NULL,
  `depth` tinyint NOT NULL,
  `event_type` varchar(64) NOT NULL DEFAULT 'recursion',
  `actor_id` bigint,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INDEX `lupo_meta_log_events_idx_actor_id` (`actor_id`);
INDEX `lupo_meta_log_events_idx_created_ymdhis` (`created_ymdhis`);
INDEX `lupo_meta_log_events_idx_depth` (`depth`);
INDEX `lupo_meta_log_events_idx_is_deleted` (`is_deleted`);

-- lupo_metrics_archive_legacy
CREATE TABLE `lupo_metrics_archive_legacy` (
  `metric_id` int NOT NULL,
  `metric_key` varchar(255) NOT NULL,
  `metric_value` varchar(255),
  `recorded_at` bigint,
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- lupo_modules
CREATE TABLE `lupo_modules` (
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
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INDEX `lupo_modules_idx_installed` (`installed_ymdhis`);
INDEX `lupo_modules_idx_namespace` (`namespace`);
INDEX `lupo_modules_idx_status` (`is_active`, `is_deleted`);
INDEX `lupo_modules_idx_system` (`is_system`);
UNIQUE INDEX `lupo_modules_uq_module_key` (`module_key`);

-- lupo_modules_departments
CREATE TABLE `lupo_modules_departments` (
  `module_department_id` bigint NOT NULL,
  `module_id` bigint NOT NULL,
  `department_id` bigint NOT NULL,
  `is_enabled` tinyint NOT NULL DEFAULT 1,
  `sort_order` int DEFAULT 0,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

UNIQUE INDEX `lupo_modules_departments_uniq_mod_dept` (`module_id`, `department_id`);

-- lupo_mood_assignments
CREATE TABLE `lupo_mood_assignments` (
  `mood_assignment_id` bigint NOT NULL,
  `table_name` varchar(128) NOT NULL,
  `row_id` bigint NOT NULL,
  `mood_id` bigint NOT NULL,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INDEX `lupo_mood_assignments_idx_assignment_mood` (`mood_id`);
INDEX `lupo_mood_assignments_idx_assignment_target` (`table_name`, `row_id`);

-- lupo_mood_registry
CREATE TABLE `lupo_mood_registry` (
  `mood_id` bigint NOT NULL,
  `mood_type` varchar(64) NOT NULL,
  `mood_variant` varchar(64),
  `mood_rgb` char(6) NOT NULL,
  `description` text,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL,
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INDEX `lupo_mood_registry_idx_mood_rgb` (`mood_rgb`);
INDEX `lupo_mood_registry_idx_mood_type` (`mood_type`);

-- lupo_multi_agent_critique_sync
CREATE TABLE `lupo_multi_agent_critique_sync` (
  `multi_agent_critique_sync_id` bigint NOT NULL,
  `cip_event_id` bigint NOT NULL,
  `agent_id` varchar(100) NOT NULL,
  `sync_role` varchar(64) NOT NULL,
  `sync_status` varchar(64) DEFAULT 'pending',
  `agent_perspective_json` json,
  `consensus_contribution` decimal(5,4) DEFAULT 0.0000,
  `conflict_indicators_json` json,
  `resolution_strategy` varchar(255),
  `sync_started_ymdhis` bigint,
  `sync_completed_ymdhis` bigint,
  `sync_version` varchar(20) DEFAULT '3.0.0',
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INDEX `lupo_multi_agent_critique_sync_idx_consensus_contribution` (`consensus_contribution`);
INDEX `lupo_multi_agent_critique_sync_idx_event_agent` (`cip_event_id`, `agent_id`);
INDEX `lupo_multi_agent_critique_sync_idx_sync_role` (`sync_role`);
INDEX `lupo_multi_agent_critique_sync_idx_sync_status` (`sync_status`);

-- lupo_notifications
CREATE TABLE `lupo_notifications` (
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
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- lupo_pack_role_registry
CREATE TABLE `lupo_pack_role_registry` (
  `pack_role_registry_id` bigint NOT NULL,
  `agent_id` bigint NOT NULL,
  `role_key` varchar(255) NOT NULL,
  `discovery_method` text NOT NULL,
  `behavior` text NOT NULL,
  `reason` text NOT NULL,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL,
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INDEX `lupo_pack_role_registry_idx_agent_id` (`agent_id`);
INDEX `lupo_pack_role_registry_idx_role_key` (`role_key`);
UNIQUE INDEX `lupo_pack_role_registry_unique_agent_role` (`agent_id`);

-- lupo_permissions
CREATE TABLE `lupo_permissions` (
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
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INDEX `lupo_permissions_idx_created_ymdhis` (`created_ymdhis`);
INDEX `lupo_permissions_idx_deleted` (`is_deleted`, `deleted_ymdhis`);
INDEX `lupo_permissions_idx_department` (`department_id`);
INDEX `lupo_permissions_idx_permission` (`permission`);
INDEX `lupo_permissions_idx_target` (`target_type`, `target_id`);
INDEX `lupo_permissions_idx_user` (`user_id`);
UNIQUE INDEX `lupo_permissions_uniq_target_department` (`target_type`, `target_id`, `department_id`);
UNIQUE INDEX `lupo_permissions_uniq_target_user` (`target_type`, `target_id`, `user_id`);

-- lupo_persona_dialogue_patterns
CREATE TABLE `lupo_persona_dialogue_patterns` (
  `pattern_id` bigint NOT NULL,
  `persona_id` bigint NOT NULL,
  `pattern_type` varchar(100) NOT NULL,
  `pattern_name` varchar(255) NOT NULL,
  `pattern_triggers` json,
  `pattern_responses` json,
  `pattern_context` json,
  `pattern_frequency` decimal(5,2),
  `pattern_confidence` decimal(5,2),
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL,
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INDEX `lupo_persona_dialogue_patterns_idx_pattern_name` (`pattern_name`);
INDEX `lupo_persona_dialogue_patterns_idx_pattern_type` (`pattern_type`);
INDEX `lupo_persona_dialogue_patterns_idx_persona_id` (`persona_id`);

-- lupo_persona_profiles
CREATE TABLE `lupo_persona_profiles` (
  `persona_id` bigint NOT NULL,
  `persona_name` varchar(255) NOT NULL,
  `persona_type` varchar(100) NOT NULL,
  `persona_description` text,
  `persona_traits` json,
  `persona_preferences` json,
  `persona_capabilities` json,
  `persona_voice_style` varchar(100),
  `persona_interaction_style` varchar(100),
  `persona_emotional_profile` json,
  `persona_knowledge_domains` json,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL,
  `is_active` tinyint NOT NULL DEFAULT 1,
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INDEX `lupo_persona_profiles_idx_is_active` (`is_active`);
INDEX `lupo_persona_profiles_idx_persona_name` (`persona_name`);
INDEX `lupo_persona_profiles_idx_persona_type` (`persona_type`);

-- lupo_reference_cited_by
CREATE TABLE `lupo_reference_cited_by` (
  `reference_cited_by_id` bigint NOT NULL,
  `reference_object_id` bigint NOT NULL,
  `content_id` bigint NOT NULL,
  `section_anchor_slug` varchar(255),
  `section_order` int NOT NULL DEFAULT 0,
  `reference_type` varchar(50) NOT NULL,
  `raw_reference` text,
  `meta_json` json,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint NOT NULL DEFAULT 0,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL DEFAULT 0,
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INDEX `lupo_reference_cited_by_idx_content_id` (`content_id`);
INDEX `lupo_reference_cited_by_idx_is_deleted` (`is_deleted`);
INDEX `lupo_reference_cited_by_idx_reference_object` (`reference_object_id`);
INDEX `lupo_reference_cited_by_idx_reference_type` (`reference_type`);
INDEX `lupo_reference_cited_by_idx_section_anchor` (`section_anchor_slug`);

-- lupo_reference_objects
CREATE TABLE `lupo_reference_objects` (
  `reference_object_id` bigint NOT NULL,
  `object_type` varchar(50) NOT NULL,
  `object_slug` varchar(255) NOT NULL,
  `object_label` varchar(255),
  `meta_json` json,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint NOT NULL DEFAULT 0,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL DEFAULT 0,
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INDEX `lupo_reference_objects_idx_is_deleted` (`is_deleted`);
INDEX `lupo_reference_objects_idx_object_slug` (`object_slug`);
INDEX `lupo_reference_objects_idx_object_type` (`object_type`);
INDEX `lupo_reference_objects_idx_type_slug` (`object_type`, `object_slug`);

-- lupo_relationships
CREATE TABLE `lupo_relationships` (
  `relationship_id` bigint NOT NULL,
  `source_type` varchar(50),
  `source_id` bigint,
  `edge_type` varchar(50),
  `target_type` varchar(50),
  `target_id` bigint,
  `created_ymdhis` bigint,
  `updated_ymdhis` bigint,
  `is_deleted` tinyint DEFAULT 0,
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INDEX `lupo_relationships_idx_relationship_lookup` (`source_type`, `source_id`, `edge_type`, `is_deleted`);

-- lupo_search_index
CREATE TABLE `lupo_search_index` (
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
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INDEX `lupo_search_index_idx_domain_type` (`domain_id`, `entity_type`);
INDEX `lupo_search_index_idx_entity_reference` (`entity_type`, `entity_id`);
INDEX `lupo_search_index_idx_is_deleted` (`is_deleted`);
INDEX `lupo_search_index_idx_relevance` (`relevance_score`);
INDEX `lupo_search_index_idx_updated` (`updated_ymdhis`);
UNIQUE INDEX `lupo_search_index_unique_entity` (`domain_id`, `entity_type`, `entity_id`);

-- lupo_search_rebuild_log
CREATE TABLE `lupo_search_rebuild_log` (
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
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INDEX `lupo_search_rebuild_log_idx_created` (`created_ymdhis`);
INDEX `lupo_search_rebuild_log_idx_entity` (`entity_type`, `entity_id`);
INDEX `lupo_search_rebuild_log_idx_status_retry` (`status`, `next_attempt_ymdhis`);
UNIQUE INDEX `lupo_search_rebuild_log_unique_entity_operation` (`entity_type`, `entity_id`, `action`);

-- lupo_semantic_categories
CREATE TABLE `lupo_semantic_categories` (
  `category_id` bigint NOT NULL,
  `category_name` varchar(255) NOT NULL,
  `category_slug` varchar(255) NOT NULL,
  `description` text,
  `parent_category_id` bigint,
  `sort_order` int NOT NULL DEFAULT 0,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL,
  `is_active` tinyint NOT NULL DEFAULT 1,
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INDEX `lupo_semantic_categories_idx_created` (`created_ymdhis`);
INDEX `lupo_semantic_categories_idx_created_ymdhis` (`created_ymdhis`, `is_active`);
INDEX `lupo_semantic_categories_idx_is_active` (`is_active`);
INDEX `lupo_semantic_categories_idx_parent_category` (`parent_category_id`);
INDEX `lupo_semantic_categories_idx_sort_order` (`sort_order`);
UNIQUE INDEX `lupo_semantic_categories_uk_category_slug` (`category_slug`);

-- lupo_semantic_content_views
CREATE TABLE `lupo_semantic_content_views` (
  `semantic_view_id` bigint NOT NULL,
  `view_name` varchar(255) NOT NULL,
  `view_type` varchar(64) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text,
  `template_path` varchar(512) NOT NULL,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL,
  `is_active` tinyint NOT NULL DEFAULT 1,
  `is_default` tinyint NOT NULL DEFAULT 0,
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INDEX `lupo_semantic_content_views_idx_created_ymdhis` (`created_ymdhis`, `is_default`, `is_active`);
INDEX `lupo_semantic_content_views_idx_is_active` (`is_active`);
INDEX `lupo_semantic_content_views_idx_is_default` (`is_default`);
INDEX `lupo_semantic_content_views_idx_view_type` (`view_type`);
UNIQUE INDEX `lupo_semantic_content_views_uk_view_name` (`view_name`);

-- lupo_semantic_navigation_overview
CREATE TABLE `lupo_semantic_navigation_overview` (
  `navigation_id` bigint NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text,
  `navigation_tree` json NOT NULL,
  `content_categories` json NOT NULL,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INDEX `lupo_semantic_navigation_overview_idx_created` (`created_ymdhis`);
INDEX `lupo_semantic_navigation_overview_idx_created_ymdhis` (`created_ymdhis`, `is_deleted`);
INDEX `lupo_semantic_navigation_overview_idx_is_deleted` (`is_deleted`);

-- lupo_semantic_overlays
CREATE TABLE `lupo_semantic_overlays` (
  `semantic_overlay_id` int NOT NULL,
  `slug` varchar(255) NOT NULL,
  `overlay_key` varchar(255) NOT NULL,
  `overlay_value` text NOT NULL,
  `context` varchar(255),
  `created_at` bigint,
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INDEX `lupo_semantic_overlays_idx_context` (`context`);
INDEX `lupo_semantic_overlays_idx_slug` (`slug`);

-- lupo_semantic_paths
CREATE TABLE `lupo_semantic_paths` (
  `semantic_path_id` bigint NOT NULL,
  `source_page_id` bigint NOT NULL,
  `target_page_id` bigint NOT NULL,
  `layer` varchar(64) NOT NULL,
  `weight` float NOT NULL DEFAULT 0,
  `decay_factor` float NOT NULL DEFAULT 1,
  `trend_score` float NOT NULL DEFAULT 0,
  `timeframe` varchar(64) NOT NULL,
  `custom_start` bigint,
  `custom_end` bigint,
  `created_at` bigint,
  `updated_at` bigint,
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INDEX `lupo_semantic_paths_layer` (`layer`);
INDEX `lupo_semantic_paths_source_page_id` (`source_page_id`);
INDEX `lupo_semantic_paths_target_page_id` (`target_page_id`);
INDEX `lupo_semantic_paths_timeframe` (`timeframe`);


-- ============================================================
-- Batch 4 Complete
-- Total CREATE statements: 50
-- ============================================================
