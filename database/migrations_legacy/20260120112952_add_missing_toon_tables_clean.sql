-- WOLFIE Migration: Add Missing TOON Tables (Clean Version)
-- Generated: 2026-01-20T11:29:52.371992
-- Tables to create: 51
-- Source: TOON files analysis with doctrine compliance

-- Doctrine Compliance Enforced:
--   ✅ No foreign keys or constraints
--   ✅ BIGINT UTC timestamps (YYYYMMDDHHIISS)
--   ✅ Application-managed relationships
--   ✅ Federation-safe schema
--   ✅ Proper primary key naming

SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';
START TRANSACTION;
SET time_zone = '+00:00';

-- ============================================
-- Table: dreaming_observer_relationships
-- Fields: 7
-- Primary Key: relationship_id
-- ============================================

CREATE TABLE `dreaming_observer_relationships` (
  `relationship_id` bigint NOT NULL auto_increment,
  `observer_a` varchar(64) NOT NULL,
  `observer_b` varchar(64) NOT NULL,
  `relationship_type` varchar(64) NOT NULL,
  `coherence_delta` float,
  `created_ymdhis` bigint NOT NULL,
  `is_deleted` tinyint DEFAULT 0,
  PRIMARY KEY (`relationship_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Table: dreaming_observer_states
-- Fields: 9
-- Primary Key: observer_state_id
-- ============================================

CREATE TABLE `dreaming_observer_states` (
  `observer_state_id` bigint NOT NULL auto_increment,
  `gov_event_id` bigint NOT NULL,
  `observer_agent` varchar(64) NOT NULL,
  `narrative_thread_id` varchar(255),
  `coherence_score` float,
  `dream_depth` int,
  `interpretation` text,
  `created_ymdhis` bigint NOT NULL,
  `is_deleted` tinyint DEFAULT 0,
  PRIMARY KEY (`observer_state_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Table: dreaming_observer_summary
-- Fields: 6
-- Primary Key: AUTO-DETECTED
-- ============================================

CREATE TABLE `dreaming_observer_summary` (
  `observer_agent` varchar(64) NOT NULL,
  `last_seen_ymdhis` bigint,
  `total_interpretations` bigint,
  `avg_coherence` float,
  `dominant_thread` varchar(255),
  `is_deleted` tinyint DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Table: integration_test_results
-- Fields: 9
-- Primary Key: test_result_id
-- ============================================

CREATE TABLE `integration_test_results` (
  `test_result_id` bigint NOT NULL auto_increment,
  `test_suite` varchar(64) NOT NULL,
  `test_case` varchar(128) NOT NULL,
  `expected_result` varchar(255),
  `actual_result` varchar(255),
  `status` enum('PASS','FAIL','SKIP','ERROR') NOT NULL,
  `error_message` text,
  `execution_time_ms` int,
  `created_ymdhis` bigint NOT NULL,
  PRIMARY KEY (`test_result_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Table: lupo_actor_handshakes
-- Fields: 12
-- Primary Key: actor_handshake_id
-- ============================================

CREATE TABLE `lupo_actor_handshakes` (
  `actor_handshake_id` bigint NOT NULL auto_increment,
  `actor_id` bigint NOT NULL,
  `actor_type` varchar(32) NOT NULL COMMENT 'human|ai|system',
  `utc_timestamp` bigint NOT NULL COMMENT 'YYYYMMDDHHMMSS',
  `purpose` varchar(500),
  `constraints_json` json,
  `forbidden_actions_json` json,
  `context` text,
  `expires_utc` bigint COMMENT 'YYYYMMDDHHMMSS',
  `created_ymdhis` bigint NOT NULL,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  PRIMARY KEY (`actor_handshake_id`),
  KEY `idx_actor_id` (`actor_id`),
  KEY `idx_is_deleted` (`is_deleted`),
  KEY `idx_utc_timestamp` (`utc_timestamp`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Table: lupo_actor_meta
-- Fields: 7
-- Primary Key: actor_meta_id
-- ============================================

CREATE TABLE `lupo_actor_meta` (
  `actor_meta_id` bigint unsigned NOT NULL auto_increment,
  `actor_id` bigint unsigned NOT NULL,
  `meta_type` varchar(64) NOT NULL,
  `meta_key` varchar(255) NOT NULL,
  `meta_value` text NOT NULL,
  `created_ymdhis` bigint NOT NULL,
  `updated_ymdhis` bigint NOT NULL,
  PRIMARY KEY (`actor_meta_id`),
  KEY `actor_id` (`actor_id`),
  KEY `meta_key` (`meta_key`),
  KEY `meta_type` (`meta_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Table: lupo_agent_heartbeats
-- Fields: 7
-- Primary Key: heartbeat_id
-- ============================================

CREATE TABLE `lupo_agent_heartbeats` (
  `heartbeat_id` bigint NOT NULL auto_increment,
  `agent_slug` varchar(64) NOT NULL COMMENT 'CURSOR|CASCADE|LILITH|MONDAY_WOLFIE|etc',
  `status` varchar(32) NOT NULL DEFAULT 'unknown' COMMENT 'operational|idle|error|unknown',
  `last_heartbeat_ymdhis` bigint NOT NULL COMMENT 'YYYYMMDDHHMMSS',
  `created_ymdhis` bigint NOT NULL COMMENT 'YYYYMMDDHHMMSS',
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  PRIMARY KEY (`heartbeat_id`),
  KEY `idx_agent_slug` (`agent_slug`),
  KEY `idx_created_ymdhis` (`created_ymdhis`),
  KEY `idx_is_deleted` (`is_deleted`),
  KEY `idx_last_heartbeat_ymdhis` (`last_heartbeat_ymdhis`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Table: lupo_artifacts
-- Fields: 8
-- Primary Key: artifact_id
-- ============================================

CREATE TABLE `lupo_artifacts` (
  `artifact_id` bigint NOT NULL auto_increment,
  `actor_id` bigint NOT NULL,
  `utc_timestamp` bigint NOT NULL COMMENT 'YYYYMMDDHHMMSS',
  `type` varchar(64) NOT NULL COMMENT 'dialog|changelog|schema|lore|humor|protocol|fork_justification',
  `content` longtext NOT NULL,
  `created_ymdhis` bigint NOT NULL,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  PRIMARY KEY (`artifact_id`),
  KEY `idx_actor_id` (`actor_id`),
  KEY `idx_is_deleted` (`is_deleted`),
  KEY `idx_type` (`type`),
  KEY `idx_utc_timestamp` (`utc_timestamp`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Table: lupo_calibration_impacts
-- Fields: 10
-- Primary Key: id
-- ============================================

CREATE TABLE `lupo_calibration_impacts` (
  `id` bigint unsigned NOT NULL auto_increment,
  `calibration_id` bigint unsigned NOT NULL COMMENT 'Reference to lupo_emotional_geometry_calibrations.id',
  `impact_type` enum('agent_behavior','communication_tone','system_harmony','conflict_reduction') NOT NULL,
  `impact_measurement` decimal(5,4) NOT NULL COMMENT 'Quantified impact (0.0000-1.0000)',
  `measurement_method` varchar(100) NOT NULL COMMENT 'How impact was measured',
  `before_metrics_json` json COMMENT 'Metrics before calibration',
  `after_metrics_json` json COMMENT 'Metrics after calibration',
  `observation_period_hours` int unsigned DEFAULT 24 COMMENT 'Observation period length',
  `measured_ymdhis` bigint NOT NULL COMMENT 'When impact was measured',
  `impact_version` varchar(20) DEFAULT '4.0.75' COMMENT 'Impact tracking version',
  PRIMARY KEY (`id`),
  KEY `idx_calibration_impact` (`calibration_id`, `impact_type`),
  KEY `idx_impact_measurement` (`impact_measurement`),
  KEY `idx_measurement_time` (`measured_ymdhis`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Table: lupo_cip_analytics
-- Fields: 12
-- Primary Key: id
-- ============================================

CREATE TABLE `lupo_cip_analytics` (
  `id` bigint unsigned NOT NULL auto_increment,
  `event_id` bigint unsigned NOT NULL COMMENT 'Reference to lupo_cip_events.id',
  `defensiveness_index` decimal(5,4) NOT NULL DEFAULT 0.0000 COMMENT 'DI: 0.0000-1.0000 scale',
  `integration_velocity` decimal(5,4) NOT NULL DEFAULT 0.0000 COMMENT 'IV: 0.0000-1.0000 scale',
  `architectural_impact_score` decimal(5,4) NOT NULL DEFAULT 0.0000 COMMENT 'AIS: 0.0000-1.0000 scale',
  `doctrine_propagation_depth` tinyint unsigned NOT NULL DEFAULT 0 COMMENT 'DPD: 0-10 depth levels',
  `critique_source_weight` decimal(5,4) NOT NULL DEFAULT 0.5000 COMMENT 'Source credibility weight',
  `subsystem_impact_json` json COMMENT 'Impact analysis per subsystem',
  `trend_analysis_json` json COMMENT 'Historical trend data',
  `calculated_ymdhis` bigint NOT NULL COMMENT 'When analytics were calculated',
  `recalculated_ymdhis` bigint COMMENT 'Last recalculation timestamp',
  `analytics_version` varchar(20) DEFAULT '4.0.75' COMMENT 'Analytics engine version',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_event_analytics` (`event_id`),
  KEY `idx_architectural_impact` (`architectural_impact_score`),
  KEY `idx_calculated_time` (`calculated_ymdhis`),
  KEY `idx_defensiveness_index` (`defensiveness_index`),
  KEY `idx_integration_velocity` (`integration_velocity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Table: lupo_cip_propagation_tracking
-- Fields: 12
-- Primary Key: id
-- ============================================

CREATE TABLE `lupo_cip_propagation_tracking` (
  `id` bigint unsigned NOT NULL auto_increment,
  `cip_event_id` bigint unsigned NOT NULL COMMENT 'Root CIP event',
  `propagation_level` tinyint unsigned NOT NULL COMMENT 'Depth level (0-10)',
  `affected_subsystem` varchar(100) NOT NULL COMMENT 'Subsystem affected at this level',
  `propagation_type` enum('doctrine','emotional_geometry','agent_behavior','system_config') NOT NULL,
  `change_description` text NOT NULL COMMENT 'What changed at this level',
  `propagation_strength` decimal(5,4) NOT NULL DEFAULT 1.0000 COMMENT 'Strength of propagation',
  `completion_status` enum('pending','in_progress','completed','blocked','failed') DEFAULT 'pending',
  `dependencies_json` json COMMENT 'Dependencies for this propagation step',
  `started_ymdhis` bigint COMMENT 'When propagation started',
  `completed_ymdhis` bigint COMMENT 'When propagation completed',
  `propagation_version` varchar(20) DEFAULT '4.0.75' COMMENT 'Propagation tracking version',
  PRIMARY KEY (`id`),
  KEY `idx_completion_status` (`completion_status`),
  KEY `idx_event_level` (`cip_event_id`, `propagation_level`),
  KEY `idx_propagation_strength` (`propagation_strength`),
  KEY `idx_subsystem` (`affected_subsystem`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Table: lupo_cip_trends
-- Fields: 12
-- Primary Key: id
-- ============================================

CREATE TABLE `lupo_cip_trends` (
  `id` bigint unsigned NOT NULL auto_increment,
  `trend_period` enum('hourly','daily','weekly','monthly') NOT NULL,
  `period_start_ymdhis` bigint NOT NULL COMMENT 'Start of aggregation period',
  `period_end_ymdhis` bigint NOT NULL COMMENT 'End of aggregation period',
  `avg_defensiveness_index` decimal(5,4) NOT NULL DEFAULT 0.0000,
  `avg_integration_velocity` decimal(5,4) NOT NULL DEFAULT 0.0000,
  `avg_architectural_impact` decimal(5,4) NOT NULL DEFAULT 0.0000,
  `total_events` int unsigned NOT NULL DEFAULT 0,
  `high_impact_events` int unsigned NOT NULL DEFAULT 0 COMMENT 'AIS > 0.7000',
  `doctrine_updates_triggered` int unsigned NOT NULL DEFAULT 0,
  `trend_metadata_json` json COMMENT 'Additional trend analysis',
  `calculated_ymdhis` bigint NOT NULL COMMENT 'When trend was calculated',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_period_trend` (`trend_period`, `period_start_ymdhis`),
  KEY `idx_high_impact` (`high_impact_events`),
  KEY `idx_period_range` (`period_start_ymdhis`, `period_end_ymdhis`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Table: lupo_content_tag_relationships
-- Fields: 5
-- Primary Key: relationship_id
-- ============================================

CREATE TABLE `lupo_content_tag_relationships` (
  `relationship_id` bigint NOT NULL auto_increment COMMENT 'Primary key for content-tag relationship',
  `content_id` bigint NOT NULL COMMENT 'Reference to content table',
  `tag_id` bigint NOT NULL COMMENT 'Reference to tag table',
  `relationship_type` enum('category','topic','label') NOT NULL COMMENT 'Type of relationship',
  `created_ymdhis` bigint NOT NULL COMMENT 'Creation timestamp',
  PRIMARY KEY (`relationship_id`),
  KEY `idx_content_id` (`content_id`),
  KEY `idx_created` (`created_ymdhis`),
  KEY `idx_created_ymdhis` (`created_ymdhis`, `relationship_type`, `content_id`, `tag_id`),
  KEY `idx_relationship_type` (`relationship_type`),
  KEY `idx_tag_id` (`tag_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Table: lupo_dialog_channels
-- Fields: 19
-- Primary Key: channel_id
-- ============================================

CREATE TABLE `lupo_dialog_channels` (
  `channel_id` bigint unsigned NOT NULL auto_increment,
  `channel_name` varchar(255) NOT NULL,
  `file_source` varchar(255) NOT NULL COMMENT 'Original .md filename',
  `title` varchar(500),
  `description` text,
  `speaker` varchar(100),
  `target` varchar(100),
  `mood_rgb` varchar(7) COMMENT 'Emotional polarity tensor encoded as hex (abstract axes: strife (R), harmony (G), memory depth (B); not literal RGB color channels)',
  `categories` json COMMENT 'Array of category strings',
  `collections` json COMMENT 'Array of collection strings',
  `channels` json COMMENT 'Array of channel strings',
  `tags` json COMMENT 'Additional tag metadata',
  `version` varchar(20) COMMENT 'System version when created',
  `status` enum('draft','published','archived') DEFAULT 'published',
  `author` varchar(100),
  `created_timestamp` bigint unsigned NOT NULL COMMENT 'YYYYMMDDHHIISS format',
  `modified_timestamp` bigint unsigned NOT NULL COMMENT 'YYYYMMDDHHIISS format',
  `message_count` int unsigned DEFAULT 0 COMMENT 'Cached count of messages',
  `metadata_json` json COMMENT 'Additional metadata from WOLFIE headers',
  PRIMARY KEY (`channel_id`),
  UNIQUE KEY `idx_channel_name` (`channel_name`),
  KEY `idx_created_timestamp` (`created_timestamp`),
  KEY `idx_dialog_channels_composite` (`status`, `created_timestamp`),
  KEY `idx_file_source` (`file_source`),
  KEY `idx_modified_timestamp` (`modified_timestamp`),
  KEY `idx_speaker` (`speaker`),
  KEY `idx_status` (`status`),
  KEY `idx_target` (`target`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Table: lupo_dialog_messages
-- Fields: 14
-- Primary Key: dialog_message_id
-- ============================================

CREATE TABLE `lupo_dialog_messages` (
  `dialog_message_id` bigint NOT NULL auto_increment COMMENT 'Primary key for the dialog message',
  `dialog_thread_id` bigint COMMENT 'Optional thread grouping for related dialogs',
  `channel_id` bigint COMMENT 'Optional channel identifier',
  `from_actor_id` bigint COMMENT 'Actor ID of the message sender',
  `to_actor_id` bigint COMMENT 'Agent ID if message is from an AI agent',
  `message_text` varchar(1000) NOT NULL COMMENT 'The message under 1000 chars ',
  `message_type` enum('text','command','system','error') NOT NULL DEFAULT 'text' COMMENT 'Type of message',
  `metadata_json` json COMMENT 'Additional message metadata',
  `mood_rgb` char(6) NOT NULL DEFAULT '666666' COMMENT 'Emotional polarity tensor encoded as hex (abstract axes: strife (R), harmony (G), memory depth (B); not literal RGB color channels)',
  `weight` decimal(3,2) NOT NULL DEFAULT 0.00,
  `created_ymdhis` bigint NOT NULL COMMENT 'Creation timestamp (YYYYMMDDHHMMSS)',
  `updated_ymdhis` bigint NOT NULL COMMENT 'Last update timestamp (YYYYMMDDHHMMSS)',
  `is_deleted` tinyint NOT NULL DEFAULT 0 COMMENT 'Soft delete flag (1=deleted)',
  `deleted_ymdhis` bigint COMMENT 'Deletion timestamp (YYYYMMDDHHMMSS)',
  PRIMARY KEY (`dialog_message_id`),
  KEY `idx_channel` (`channel_id`),
  KEY `idx_created` (`created_ymdhis`),
  KEY `idx_deleted` (`is_deleted`),
  KEY `idx_dialog_thread_id` (`dialog_thread_id`),
  KEY `idx_message_type` (`message_type`),
  KEY `idx_to_actor_id` (`to_actor_id`),
  KEY `idx_updated` (`updated_ymdhis`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Table: lupo_doctrine_evolution_audit
-- Fields: 9
-- Primary Key: id
-- ============================================

CREATE TABLE `lupo_doctrine_evolution_audit` (
  `id` bigint unsigned NOT NULL auto_increment,
  `refinement_id` bigint unsigned NOT NULL COMMENT 'Reference to lupo_doctrine_refinements.id',
  `evolution_step` tinyint unsigned NOT NULL COMMENT 'Step in evolution process (1-10)',
  `step_description` varchar(255) NOT NULL COMMENT 'Description of evolution step',
  `step_status` enum('pending','in_progress','completed','failed','skipped') DEFAULT 'pending',
  `step_metadata_json` json COMMENT 'Step-specific metadata',
  `started_ymdhis` bigint COMMENT 'When step started',
  `completed_ymdhis` bigint COMMENT 'When step completed',
  `audit_version` varchar(20) DEFAULT '4.0.75' COMMENT 'Audit system version',
  PRIMARY KEY (`id`),
  KEY `idx_completion_time` (`completed_ymdhis`),
  KEY `idx_refinement_step` (`refinement_id`, `evolution_step`),
  KEY `idx_step_status` (`step_status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Table: lupo_doctrine_refinements
-- Fields: 13
-- Primary Key: id
-- ============================================

CREATE TABLE `lupo_doctrine_refinements` (
  `id` bigint unsigned NOT NULL auto_increment,
  `cip_event_id` bigint unsigned NOT NULL COMMENT 'Triggering CIP event',
  `doctrine_file_path` varchar(500) NOT NULL COMMENT 'Path to doctrine file updated',
  `refinement_type` enum('addition','modification','removal','restructure') NOT NULL,
  `change_description` text NOT NULL COMMENT 'Description of doctrine change',
  `before_content_hash` varchar(64) COMMENT 'SHA256 of content before change',
  `after_content_hash` varchar(64) NOT NULL COMMENT 'SHA256 of content after change',
  `impact_assessment_json` json COMMENT 'Assessment of change impact',
  `approval_status` enum('pending','approved','rejected','auto_approved') DEFAULT 'pending',
  `approved_by` varchar(100) COMMENT 'Who approved the change',
  `applied_ymdhis` bigint COMMENT 'When change was applied',
  `created_ymdhis` bigint NOT NULL COMMENT 'When refinement was proposed',
  `refinement_version` varchar(20) DEFAULT '4.0.75' COMMENT 'Refinement module version',
  PRIMARY KEY (`id`),
  KEY `idx_applied_time` (`applied_ymdhis`),
  KEY `idx_approval_status` (`approval_status`),
  KEY `idx_cip_event` (`cip_event_id`),
  KEY `idx_doctrine_file` (`doctrine_file_path`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Table: lupo_emotional_geometry_calibrations
-- Fields: 14
-- Primary Key: id
-- ============================================

CREATE TABLE `lupo_emotional_geometry_calibrations` (
  `id` bigint unsigned NOT NULL auto_increment,
  `cip_analytics_id` bigint unsigned NOT NULL COMMENT 'Reference to lupo_cip_analytics.id',
  `calibration_target` enum('agent','subsystem','global') NOT NULL,
  `target_identifier` varchar(255) NOT NULL COMMENT 'Agent ID, subsystem name, or "global"',
  `baseline_before_json` json COMMENT 'R/G/B vectors before calibration',
  `baseline_after_json` json NOT NULL COMMENT 'R/G/B vectors after calibration',
  `tension_vectors_detected` json COMMENT 'Detected tension patterns',
  `calibration_reason` text NOT NULL COMMENT 'Why calibration was needed',
  `calibration_algorithm` varchar(100) DEFAULT 'cip_pattern_analysis' COMMENT 'Algorithm used',
  `confidence_score` decimal(5,4) NOT NULL DEFAULT 0.5000 COMMENT 'Calibration confidence',
  `validation_status` enum('pending','validated','rejected','needs_review') DEFAULT 'pending',
  `applied_ymdhis` bigint COMMENT 'When calibration was applied',
  `created_ymdhis` bigint NOT NULL COMMENT 'When calibration was calculated',
  `calibration_version` varchar(20) DEFAULT '4.0.75' COMMENT 'Calibration system version',
  PRIMARY KEY (`id`),
  KEY `idx_analytics_ref` (`cip_analytics_id`),
  KEY `idx_confidence` (`confidence_score`),
  KEY `idx_target` (`calibration_target`, `target_identifier`),
  KEY `idx_validation_status` (`validation_status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Table: lupo_gov_event_actor_edges
-- Fields: 9
-- Primary Key: edge_id
-- ============================================

CREATE TABLE `lupo_gov_event_actor_edges` (
  `edge_id` bigint NOT NULL auto_increment COMMENT 'Primary key for edge',
  `gov_event_id` bigint NOT NULL COMMENT 'Governance event',
  `actor_id` bigint NOT NULL COMMENT 'Actor',
  `edge_type` varchar(100) NOT NULL COMMENT 'Type of relationship',
  `edge_properties` text COMMENT 'JSON or TOON formatted metadata',
  `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when created',
  `updated_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when last updated',
  `is_deleted` tinyint NOT NULL DEFAULT 0 COMMENT 'Soft delete flag',
  `deleted_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when deleted',
  PRIMARY KEY (`edge_id`),
  UNIQUE KEY `unique_gov_event_actor_edge` (`gov_event_id`, `actor_id`, `edge_type`),
  KEY `idx_actor` (`actor_id`),
  KEY `idx_created_ymdhis` (`created_ymdhis`),
  KEY `idx_edge_type` (`edge_type`),
  KEY `idx_gov_event` (`gov_event_id`),
  KEY `idx_is_deleted` (`is_deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Table: lupo_gov_event_conflicts
-- Fields: 9
-- Primary Key: id
-- ============================================

CREATE TABLE `lupo_gov_event_conflicts` (
  `id` bigint NOT NULL auto_increment COMMENT 'Primary key',
  `gov_event_id` bigint NOT NULL COMMENT 'The event declaring a conflict',
  `conflicts_with_event_id` bigint NOT NULL COMMENT 'The event it conflicts with',
  `conflict_type` varchar(50) NOT NULL COMMENT 'schema, doctrine, branch, timestamp, identity',
  `severity` varchar(20) NOT NULL COMMENT 'warning, error, fatal',
  `notes` text COMMENT 'Optional explanation of the conflict',
  `created_ymdhis` bigint NOT NULL COMMENT 'UTC timestamp of creation',
  `is_deleted` tinyint NOT NULL DEFAULT 0 COMMENT 'Soft delete flag',
  `deleted_ymdhis` bigint COMMENT 'UTC timestamp of deletion',
  PRIMARY KEY (`id`),
  KEY `idx_conflicts_with_event_id` (`conflicts_with_event_id`),
  KEY `idx_gov_event_id` (`gov_event_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Table: lupo_gov_event_dependencies
-- Fields: 8
-- Primary Key: id
-- ============================================

CREATE TABLE `lupo_gov_event_dependencies` (
  `id` bigint NOT NULL auto_increment COMMENT 'Primary key',
  `gov_event_id` bigint NOT NULL COMMENT 'The event declaring a dependency',
  `depends_on_event_id` bigint NOT NULL COMMENT 'The event it depends on',
  `dependency_type` varchar(50) NOT NULL COMMENT 'hard, soft, branch, schema, doctrine',
  `notes` text COMMENT 'Optional explanation',
  `created_ymdhis` bigint NOT NULL COMMENT 'UTC timestamp of creation',
  `is_deleted` tinyint NOT NULL DEFAULT 0 COMMENT 'Soft delete flag',
  `deleted_ymdhis` bigint COMMENT 'UTC timestamp of deletion',
  PRIMARY KEY (`id`),
  KEY `idx_depends_on_event_id` (`depends_on_event_id`),
  KEY `idx_gov_event_id` (`gov_event_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Table: lupo_gov_event_references
-- Fields: 12
-- Primary Key: reference_id
-- ============================================

CREATE TABLE `lupo_gov_event_references` (
  `reference_id` bigint NOT NULL auto_increment COMMENT 'Primary key for reference',
  `gov_event_id` bigint NOT NULL COMMENT 'Associated governance event',
  `reference_type` varchar(100) NOT NULL COMMENT 'Type of reference (document, link, etc.)',
  `reference_title` varchar(255) NOT NULL COMMENT 'Reference title',
  `reference_url` varchar(1000) COMMENT 'URL if applicable',
  `reference_content` text COMMENT 'Reference content or excerpt',
  `order_sequence` int NOT NULL DEFAULT 0 COMMENT 'Display order',
  `metadata_json` json COMMENT 'Additional reference metadata',
  `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when created',
  `updated_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when last updated',
  `is_deleted` tinyint NOT NULL DEFAULT 0 COMMENT 'Soft delete flag',
  `deleted_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when deleted',
  PRIMARY KEY (`reference_id`),
  KEY `idx_created_ymdhis` (`created_ymdhis`),
  KEY `idx_gov_event` (`gov_event_id`),
  KEY `idx_is_deleted` (`is_deleted`),
  KEY `idx_order_sequence` (`order_sequence`),
  KEY `idx_reference_type` (`reference_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Table: lupo_gov_events
-- Fields: 14
-- Primary Key: gov_event_id
-- ============================================

CREATE TABLE `lupo_gov_events` (
  `gov_event_id` bigint NOT NULL auto_increment COMMENT 'Primary key for governance event',
  `utc_group_id` bigint NOT NULL COMMENT 'UTC group identifier',
  `semantic_utc_version` varchar(50) NOT NULL COMMENT 'Semantic UTC version string',
  `canonical_path` varchar(500) NOT NULL COMMENT 'Canonical path for the event',
  `event_type` varchar(100) NOT NULL COMMENT 'Type of governance event',
  `title` varchar(255) NOT NULL COMMENT 'Event title',
  `directive_block` text COMMENT 'Captain Wolfie directive content',
  `tldr_summary` text COMMENT 'TL;DR summary of the event',
  `metadata_json` json COMMENT 'Additional event metadata',
  `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when created',
  `updated_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when last updated',
  `is_active` tinyint NOT NULL DEFAULT 1 COMMENT 'Active flag',
  `is_deleted` tinyint NOT NULL DEFAULT 0 COMMENT 'Soft delete flag',
  `deleted_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when deleted',
  PRIMARY KEY (`gov_event_id`),
  UNIQUE KEY `unique_canonical_path` (`canonical_path`),
  KEY `idx_created_ymdhis` (`created_ymdhis`),
  KEY `idx_event_type` (`event_type`),
  KEY `idx_is_active` (`is_active`),
  KEY `idx_is_deleted` (`is_deleted`),
  KEY `idx_semantic_version` (`semantic_utc_version`),
  KEY `idx_utc_group` (`utc_group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Table: lupo_gov_timeline_nodes
-- Fields: 13
-- Primary Key: timeline_node_id
-- ============================================

CREATE TABLE `lupo_gov_timeline_nodes` (
  `timeline_node_id` bigint NOT NULL auto_increment COMMENT 'Primary key for timeline node',
  `gov_event_id` bigint NOT NULL COMMENT 'Associated governance event',
  `node_type` varchar(100) NOT NULL COMMENT 'Type of timeline node',
  `node_title` varchar(255) NOT NULL COMMENT 'Timeline node title',
  `node_description` text COMMENT 'Timeline node description',
  `node_timestamp` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS for the node',
  `parent_node_id` bigint COMMENT 'Parent node for hierarchical timelines',
  `order_sequence` int NOT NULL DEFAULT 0 COMMENT 'Display order',
  `metadata_json` json COMMENT 'Additional node metadata',
  `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when created',
  `updated_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when last updated',
  `is_deleted` tinyint NOT NULL DEFAULT 0 COMMENT 'Soft delete flag',
  `deleted_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when deleted',
  PRIMARY KEY (`timeline_node_id`),
  KEY `idx_created_ymdhis` (`created_ymdhis`),
  KEY `idx_gov_event` (`gov_event_id`),
  KEY `idx_is_deleted` (`is_deleted`),
  KEY `idx_node_timestamp` (`node_timestamp`),
  KEY `idx_node_type` (`node_type`),
  KEY `idx_order_sequence` (`order_sequence`),
  KEY `idx_parent_node` (`parent_node_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Table: lupo_gov_valuations
-- Fields: 14
-- Primary Key: valuation_id
-- ============================================

CREATE TABLE `lupo_gov_valuations` (
  `valuation_id` bigint NOT NULL auto_increment COMMENT 'Primary key for valuation',
  `gov_event_id` bigint NOT NULL COMMENT 'Associated governance event',
  `valuation_type` varchar(100) NOT NULL COMMENT 'Type of valuation',
  `valuation_metric` varchar(255) NOT NULL COMMENT 'Metric being valued',
  `valuation_value` decimal(20,8) COMMENT 'Numeric valuation value',
  `valuation_text` text COMMENT 'Text-based valuation',
  `valuation_currency` varchar(10) COMMENT 'Currency if applicable',
  `valuation_unit` varchar(50) COMMENT 'Unit of measurement',
  `confidence_score` decimal(5,4) COMMENT 'Confidence in valuation (0.0000-1.0000)',
  `metadata_json` json COMMENT 'Additional valuation metadata',
  `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when created',
  `updated_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when last updated',
  `is_deleted` tinyint NOT NULL DEFAULT 0 COMMENT 'Soft delete flag',
  `deleted_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when deleted',
  PRIMARY KEY (`valuation_id`),
  KEY `idx_created_ymdhis` (`created_ymdhis`),
  KEY `idx_gov_event` (`gov_event_id`),
  KEY `idx_is_deleted` (`is_deleted`),
  KEY `idx_valuation_metric` (`valuation_metric`),
  KEY `idx_valuation_type` (`valuation_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Table: lupo_help_topics
-- Fields: 14
-- Primary Key: help_topic_id
-- ============================================

CREATE TABLE `lupo_help_topics` (
  `help_topic_id` bigint NOT NULL auto_increment COMMENT 'Primary key for help topic',
  `slug` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content_html` longtext,
  `content_markdown` longtext,
  `category` varchar(100),
  `parent_slug` varchar(255),
  `view_count` bigint DEFAULT 0,
  `helpful_count` bigint DEFAULT 0,
  `not_helpful_count` bigint DEFAULT 0,
  `created_ymdhis` bigint NOT NULL COMMENT 'UTC creation timestamp (YYYYMMDDHHMMSS)',
  `updated_ymdhis` bigint NOT NULL COMMENT 'UTC last update timestamp (YYYYMMDDHHMMSS)',
  `author_actor_id` bigint COMMENT 'Author actor ID',
  `is_deleted` tinyint NOT NULL DEFAULT 0 COMMENT 'Soft delete flag (1=deleted, 0=active)',
  PRIMARY KEY (`help_topic_id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `idx_author` (`author_actor_id`),
  KEY `idx_category` (`category`),
  KEY `idx_created` (`created_ymdhis`),
  KEY `idx_parent` (`parent_slug`),
  KEY `idx_slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Table: lupo_help_topics_old
-- Fields: 8
-- Primary Key: help_topics_old_id
-- ============================================

CREATE TABLE `lupo_help_topics_old` (
  `help_topics_old_id` bigint NOT NULL auto_increment,
  `slug` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content_html` longtext,
  `category` varchar(100),
  `created_ymdhis` bigint NOT NULL,
  `updated_ymdhis` bigint NOT NULL,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  PRIMARY KEY (`help_topics_old_id`),
  UNIQUE KEY `uniq_slug` (`slug`),
  KEY `idx_category` (`category`),
  KEY `idx_is_deleted` (`is_deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Table: lupo_hotfix_registry
-- Fields: 6
-- Primary Key: hotfix_id
-- ============================================

CREATE TABLE `lupo_hotfix_registry` (
  `hotfix_id` int NOT NULL auto_increment,
  `hotfix_version` varchar(20) NOT NULL,
  `applied_ymdhis` bigint NOT NULL,
  `applied_by_actor_id` int,
  `description` text,
  `metadata_json` json,
  PRIMARY KEY (`hotfix_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Table: lupo_human_history_meta
-- Fields: 7
-- Primary Key: meta_id
-- ============================================

CREATE TABLE `lupo_human_history_meta` (
  `meta_id` bigint unsigned NOT NULL auto_increment,
  `event_key` varchar(255) NOT NULL,
  `tensor_mapping` varchar(32) NOT NULL,
  `philosophical_reference` varchar(255) NOT NULL,
  `system_impact` text NOT NULL,
  `created_ymdhis` bigint NOT NULL,
  `updated_ymdhis` bigint NOT NULL,
  PRIMARY KEY (`meta_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Table: lupo_labs_declarations
-- Fields: 13
-- Primary Key: labs_declaration_id
-- ============================================

CREATE TABLE `lupo_labs_declarations` (
  `labs_declaration_id` bigint NOT NULL auto_increment COMMENT 'Primary key for LABS declaration record',
  `actor_id` bigint NOT NULL COMMENT 'Reference to actor (from lupo_actors)',
  `certificate_id` varchar(64) NOT NULL COMMENT 'Unique certificate ID (LABS-CERT-{UNIQUE_ID})',
  `declaration_timestamp` bigint NOT NULL COMMENT 'UTC timestamp from actor declaration (YYYYMMDDHHMMSS)',
  `declarations_json` json NOT NULL COMMENT 'Complete LABS declaration set (all 10 declarations)',
  `validation_status` enum('valid','invalid','expired','quarantined') NOT NULL DEFAULT 'valid' COMMENT 'Current validation status',
  `labs_version` varchar(16) NOT NULL DEFAULT '1.0' COMMENT 'LABS doctrine version',
  `next_revalidation_ymdhis` bigint NOT NULL COMMENT 'UTC timestamp when revalidation required (YYYYMMDDHHMMSS)',
  `validation_log_json` json COMMENT 'Validation log entries (violations, errors)',
  `created_ymdhis` bigint NOT NULL COMMENT 'UTC creation timestamp (YYYYMMDDHHMMSS)',
  `updated_ymdhis` bigint NOT NULL COMMENT 'UTC last update timestamp (YYYYMMDDHHMMSS)',
  `is_deleted` tinyint NOT NULL DEFAULT 0 COMMENT 'Soft delete flag (1=deleted, 0=active)',
  `deleted_ymdhis` bigint COMMENT 'UTC deletion timestamp (YYYYMMDDHHMMSS)',
  PRIMARY KEY (`labs_declaration_id`),
  KEY `idx_actor_id` (`actor_id`),
  KEY `idx_actor_status` (`actor_id`, `validation_status`, `is_deleted`),
  KEY `idx_certificate_id` (`certificate_id`),
  KEY `idx_next_revalidation` (`next_revalidation_ymdhis`),
  KEY `idx_revalidation_due` (`next_revalidation_ymdhis`, `validation_status`, `is_deleted`),
  KEY `idx_validation_status` (`validation_status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Table: lupo_labs_violations
-- Fields: 9
-- Primary Key: labs_violation_id
-- ============================================

CREATE TABLE `lupo_labs_violations` (
  `labs_violation_id` bigint NOT NULL auto_increment COMMENT 'Primary key for violation record',
  `actor_id` bigint NOT NULL COMMENT 'Reference to actor (from lupo_actors)',
  `certificate_id` varchar(64) NOT NULL,
  `violation_code` varchar(64) NOT NULL,
  `violation_description` text,
  `violation_metadata` json,
  `created_ymdhis` bigint NOT NULL COMMENT 'UTC creation timestamp (YYYYMMDDHHMMSS)',
  `updated_ymdhis` bigint NOT NULL COMMENT 'UTC last update timestamp (YYYYMMDDHHMMSS)',
  `is_deleted` tinyint NOT NULL DEFAULT 0 COMMENT 'Soft delete flag (1=deleted, 0=active)',
  PRIMARY KEY (`labs_violation_id`),
  KEY `idx_actor` (`actor_id`),
  KEY `idx_certificate` (`certificate_id`),
  KEY `idx_created` (`created_ymdhis`),
  KEY `idx_deleted` (`is_deleted`),
  KEY `idx_violation_code` (`violation_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Table: lupo_legacy_content_mapping
-- Fields: 8
-- Primary Key: mapping_id
-- ============================================

CREATE TABLE `lupo_legacy_content_mapping` (
  `mapping_id` bigint NOT NULL auto_increment COMMENT 'Primary key for content mapping',
  `legacy_url` varchar(255) NOT NULL COMMENT 'Original Crafty Syntax URL',
  `semantic_url` varchar(255) NOT NULL COMMENT 'New semantic URL',
  `content_type` enum('page','topic','collection') NOT NULL COMMENT 'Type of content',
  `content_id` bigint COMMENT 'Reference to semantic content',
  `created_ymdhis` bigint NOT NULL COMMENT 'Mapping creation timestamp',
  `updated_ymdhis` bigint NOT NULL COMMENT 'Mapping update timestamp',
  `is_active` tinyint NOT NULL DEFAULT 1 COMMENT 'Mapping active flag',
  PRIMARY KEY (`mapping_id`),
  UNIQUE KEY `uk_legacy_url` (`legacy_url`),
  KEY `idx_content_id` (`content_id`),
  KEY `idx_content_type` (`content_type`),
  KEY `idx_created` (`created_ymdhis`),
  KEY `idx_created_ymdhis` (`created_ymdhis`, `is_active`),
  KEY `idx_is_active` (`is_active`),
  KEY `idx_semantic_url` (`semantic_url`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Table: lupo_meta_log_events
-- Fields: 7
-- Primary Key: event_id
-- ============================================

CREATE TABLE `lupo_meta_log_events` (
  `event_id` bigint NOT NULL auto_increment,
  `depth` tinyint NOT NULL COMMENT '2=observation, 3=meta_observation',
  `event_type` varchar(64) NOT NULL DEFAULT 'recursion' COMMENT 'recursion|ceiling_near|auto_collapse',
  `actor_id` bigint,
  `created_ymdhis` bigint NOT NULL COMMENT 'YYYYMMDDHHMMSS',
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  PRIMARY KEY (`event_id`),
  KEY `idx_actor_id` (`actor_id`),
  KEY `idx_created_ymdhis` (`created_ymdhis`),
  KEY `idx_depth` (`depth`),
  KEY `idx_is_deleted` (`is_deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Table: lupo_migration_log
-- Fields: 5
-- Primary Key: id
-- ============================================

CREATE TABLE `lupo_migration_log` (
  `id` bigint NOT NULL auto_increment COMMENT 'Primary key',
  `executed_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when migration was attempted',
  `sql_snippet` text COMMENT 'First 2000 chars of migration SQL',
  `status` varchar(20) NOT NULL COMMENT 'success or blocked',
  `reason` text COMMENT 'If blocked, validation or execution reason',
  PRIMARY KEY (`id`),
  KEY `idx_executed_ymdhis` (`executed_ymdhis`),
  KEY `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Table: lupo_multi_agent_critique_sync
-- Fields: 12
-- Primary Key: id
-- ============================================

CREATE TABLE `lupo_multi_agent_critique_sync` (
  `id` bigint unsigned NOT NULL auto_increment,
  `cip_event_id` bigint unsigned NOT NULL COMMENT 'Root CIP event',
  `agent_id` varchar(100) NOT NULL COMMENT 'Agent participating in sync',
  `sync_role` enum('initiator','participant','observer','validator') NOT NULL,
  `sync_status` enum('pending','synchronized','out_of_sync','conflict','resolved') DEFAULT 'pending',
  `agent_perspective_json` json COMMENT 'Agent-specific view of critique',
  `consensus_contribution` decimal(5,4) DEFAULT 0.0000 COMMENT 'Contribution to consensus (0-1)',
  `conflict_indicators_json` json COMMENT 'Detected conflicts with other agents',
  `resolution_strategy` varchar(255) COMMENT 'Strategy for resolving conflicts',
  `sync_started_ymdhis` bigint COMMENT 'When sync process started',
  `sync_completed_ymdhis` bigint COMMENT 'When sync was completed',
  `sync_version` varchar(20) DEFAULT '4.0.75' COMMENT 'Sync protocol version',
  PRIMARY KEY (`id`),
  KEY `idx_consensus_contribution` (`consensus_contribution`),
  KEY `idx_event_agent` (`cip_event_id`, `agent_id`),
  KEY `idx_sync_role` (`sync_role`),
  KEY `idx_sync_status` (`sync_status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Table: lupo_pack_role_registry
-- Fields: 8
-- Primary Key: id
-- ============================================

CREATE TABLE `lupo_pack_role_registry` (
  `id` bigint unsigned NOT NULL auto_increment,
  `agent_id` bigint unsigned NOT NULL COMMENT 'Reference to lupo_agent_registry.agent_registry_id',
  `role` varchar(255) NOT NULL COMMENT 'Discovered role name',
  `discovery_method` text NOT NULL COMMENT 'How this role was discovered',
  `behavior` text NOT NULL COMMENT 'Observed behavior that defines the role',
  `reason` text NOT NULL COMMENT 'Why this agent has this role',
  `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when role was discovered',
  `updated_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when role was last updated',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_agent_role` (`agent_id`),
  KEY `idx_agent_id` (`agent_id`),
  KEY `idx_role` (`role`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Table: lupo_relationships
-- Fields: 9
-- Primary Key: relationship_id
-- ============================================

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
  PRIMARY KEY (`relationship_id`),
  KEY `idx_relationship_lookup` (`source_type`, `source_id`, `edge_type`, `is_deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Table: lupo_semantic_categories
-- Fields: 9
-- Primary Key: category_id
-- ============================================

CREATE TABLE `lupo_semantic_categories` (
  `category_id` bigint NOT NULL auto_increment COMMENT 'Primary key for semantic category',
  `category_name` varchar(255) NOT NULL COMMENT 'Category name',
  `category_slug` varchar(255) NOT NULL COMMENT 'URL-friendly category slug',
  `description` text COMMENT 'Category description',
  `parent_category_id` bigint COMMENT 'Parent category ID',
  `sort_order` int NOT NULL DEFAULT 0 COMMENT 'Display order',
  `created_ymdhis` bigint NOT NULL COMMENT 'Creation timestamp',
  `updated_ymdhis` bigint NOT NULL COMMENT 'Last update timestamp',
  `is_active` tinyint NOT NULL DEFAULT 1 COMMENT 'Category active flag',
  PRIMARY KEY (`category_id`),
  UNIQUE KEY `uk_category_slug` (`category_slug`),
  KEY `idx_created` (`created_ymdhis`),
  KEY `idx_created_ymdhis` (`created_ymdhis`, `is_active`),
  KEY `idx_is_active` (`is_active`),
  KEY `idx_parent_category` (`parent_category_id`),
  KEY `idx_sort_order` (`sort_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Table: lupo_semantic_content_views
-- Fields: 10
-- Primary Key: semantic_view_id
-- ============================================

CREATE TABLE `lupo_semantic_content_views` (
  `semantic_view_id` bigint NOT NULL auto_increment COMMENT 'Primary key for semantic content view',
  `view_name` varchar(255) NOT NULL COMMENT 'View name identifier',
  `view_type` enum('navigation','content','search','collection') NOT NULL COMMENT 'Type of semantic view',
  `title` varchar(255) NOT NULL COMMENT 'View title',
  `description` text COMMENT 'View description',
  `template_path` varchar(512) NOT NULL COMMENT 'Template file path',
  `created_ymdhis` bigint NOT NULL COMMENT 'Creation timestamp',
  `updated_ymdhis` bigint NOT NULL COMMENT 'Last update timestamp',
  `is_active` tinyint NOT NULL DEFAULT 1 COMMENT 'View active flag',
  `is_default` tinyint NOT NULL DEFAULT 0 COMMENT 'Default view flag',
  PRIMARY KEY (`semantic_view_id`),
  UNIQUE KEY `uk_view_name` (`view_name`),
  KEY `idx_created_ymdhis` (`created_ymdhis`, `is_default`, `is_active`),
  KEY `idx_is_active` (`is_active`),
  KEY `idx_is_default` (`is_default`),
  KEY `idx_view_type` (`view_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Table: lupo_semantic_navigation_overview
-- Fields: 9
-- Primary Key: navigation_id
-- ============================================

CREATE TABLE `lupo_semantic_navigation_overview` (
  `navigation_id` bigint NOT NULL auto_increment COMMENT 'Primary key for semantic navigation',
  `title` varchar(255) NOT NULL COMMENT 'Navigation title',
  `description` text COMMENT 'Navigation description',
  `navigation_tree` json NOT NULL COMMENT 'Hierarchical navigation structure',
  `content_categories` json NOT NULL COMMENT 'Content categories included',
  `created_ymdhis` bigint NOT NULL COMMENT 'Creation timestamp',
  `updated_ymdhis` bigint NOT NULL COMMENT 'Last update timestamp',
  `is_deleted` tinyint NOT NULL DEFAULT 0 COMMENT 'Soft delete flag',
  `deleted_ymdhis` bigint COMMENT 'Deletion timestamp',
  PRIMARY KEY (`navigation_id`),
  KEY `idx_created` (`created_ymdhis`),
  KEY `idx_created_ymdhis` (`created_ymdhis`, `is_deleted`),
  KEY `idx_is_deleted` (`is_deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Table: lupo_semantic_relationships
-- Fields: 6
-- Primary Key: relationship_id
-- ============================================

CREATE TABLE `lupo_semantic_relationships` (
  `relationship_id` bigint NOT NULL auto_increment COMMENT 'Primary key for semantic relationship',
  `source_content_id` bigint NOT NULL COMMENT 'Source content ID',
  `target_content_id` bigint COMMENT 'Target content ID',
  `relationship_type` enum('related','series','hierarchy') NOT NULL COMMENT 'Type of semantic relationship',
  `relationship_strength` decimal(3,2) NOT NULL DEFAULT 1.00 COMMENT 'Relationship strength (0.0-1.0)',
  `created_ymdhis` bigint NOT NULL COMMENT 'Creation timestamp',
  PRIMARY KEY (`relationship_id`),
  KEY `idx_created` (`created_ymdhis`),
  KEY `idx_created_ymdhis` (`created_ymdhis`, `relationship_type`, `source_content_id`, `target_content_id`),
  KEY `idx_relationship_type` (`relationship_type`),
  KEY `idx_source_content` (`source_content_id`),
  KEY `idx_target_content` (`target_content_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Table: lupo_semantic_search_index
-- Fields: 8
-- Primary Key: search_index_id
-- ============================================

CREATE TABLE `lupo_semantic_search_index` (
  `search_index_id` bigint NOT NULL auto_increment COMMENT 'Primary key for search index',
  `index_name` varchar(255) NOT NULL COMMENT 'Search index name',
  `index_type` enum('content','legacy_mapping','views') NOT NULL COMMENT 'Type of search index',
  `description` text COMMENT 'Search index description',
  `index_data` json NOT NULL COMMENT 'Search index data',
  `created_ymdhis` bigint NOT NULL COMMENT 'Creation timestamp',
  `updated_ymdhis` bigint NOT NULL COMMENT 'Last update timestamp',
  `is_active` tinyint NOT NULL DEFAULT 1 COMMENT 'Search index active flag',
  PRIMARY KEY (`search_index_id`),
  UNIQUE KEY `uk_index_name` (`index_name`),
  KEY `idx_created` (`created_ymdhis`),
  KEY `idx_created_ymdhis` (`created_ymdhis`, `is_active`),
  KEY `idx_index_type` (`index_type`),
  KEY `idx_is_active` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Table: lupo_semantic_tags
-- Fields: 8
-- Primary Key: tag_id
-- ============================================

CREATE TABLE `lupo_semantic_tags` (
  `tag_id` bigint NOT NULL auto_increment COMMENT 'Primary key for semantic tag',
  `tag_name` varchar(255) NOT NULL COMMENT 'Tag name',
  `tag_slug` varchar(255) NOT NULL COMMENT 'URL-friendly tag slug',
  `description` text COMMENT 'Tag description',
  `color` varchar(7) NOT NULL DEFAULT '#666666' COMMENT 'Tag color hex',
  `created_ymdhis` bigint NOT NULL COMMENT 'Creation timestamp',
  `updated_ymdhis` bigint NOT NULL COMMENT 'Last update timestamp',
  `is_active` tinyint NOT NULL DEFAULT 1 COMMENT 'Tag active flag',
  PRIMARY KEY (`tag_id`),
  UNIQUE KEY `uk_tag_slug` (`tag_slug`),
  KEY `idx_created` (`created_ymdhis`),
  KEY `idx_created_ymdhis` (`created_ymdhis`, `is_active`),
  KEY `idx_is_active` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Table: lupo_system_health_snapshots
-- Fields: 12
-- Primary Key: health_id
-- ============================================

CREATE TABLE `lupo_system_health_snapshots` (
  `health_id` bigint NOT NULL auto_increment,
  `table_count` int NOT NULL,
  `table_ceiling` int NOT NULL,
  `schema_state` varchar(64) NOT NULL DEFAULT 'unknown' COMMENT 'frozen|active|migrating',
  `sync_integrity` varchar(32) NOT NULL DEFAULT 'unknown' COMMENT 'clean|drift|unknown',
  `emotional_r` decimal(3,2) COMMENT 'strife -1..1',
  `emotional_g` decimal(3,2) COMMENT 'harmony -1..1',
  `emotional_b` decimal(3,2) COMMENT 'memory -1..1',
  `emotional_t` decimal(3,2) COMMENT 'temporal -1..1',
  `created_ymdhis` bigint NOT NULL COMMENT 'YYYYMMDDHHMMSS',
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  PRIMARY KEY (`health_id`),
  KEY `idx_created_ymdhis` (`created_ymdhis`),
  KEY `idx_is_deleted` (`is_deleted`),
  KEY `idx_table_count` (`table_count`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Table: lupo_system_logs
-- Fields: 12
-- Primary Key: log_id
-- ============================================

CREATE TABLE `lupo_system_logs` (
  `log_id` bigint NOT NULL auto_increment,
  `event_type` varchar(64) NOT NULL COMMENT 'system|agent|error|security|migration|doctrine|heartbeat|temporal',
  `severity` varchar(16) NOT NULL DEFAULT 'info' COMMENT 'info|warning|error|critical',
  `actor_slug` varchar(64) COMMENT 'SYSTEM|LILITH|CURSOR|CASCADE|CAPTAIN_WOLFIE|etc',
  `message` text NOT NULL COMMENT 'Human-readable event description',
  `context_json` json COMMENT 'Optional structured metadata',
  `created_ymdhis` bigint NOT NULL COMMENT 'YYYYMMDDHHMMSS',
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  `recursion_depth` tinyint DEFAULT 1,
  `observation_latency_ms` int,
  `temporal_anomaly_score` decimal(3,2),
  PRIMARY KEY (`log_id`),
  KEY `idx_actor_slug` (`actor_slug`),
  KEY `idx_created_ymdhis` (`created_ymdhis`),
  KEY `idx_event_type` (`event_type`),
  KEY `idx_is_deleted` (`is_deleted`),
  KEY `idx_severity` (`severity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Table: lupo_temporal_coherence_snapshots
-- Fields: 9
-- Primary Key: snapshot_id
-- ============================================

CREATE TABLE `lupo_temporal_coherence_snapshots` (
  `snapshot_id` bigint NOT NULL auto_increment,
  `utc_anchor` bigint NOT NULL COMMENT 'YYYYMMDDHHMMSS of anchor',
  `observation_latency_ms` int NOT NULL DEFAULT 0,
  `recursion_depth` tinyint NOT NULL DEFAULT 0 COMMENT '1=action, 2=observation, 3=meta; max 3',
  `self_awareness_score` decimal(3,2) COMMENT '0-1 scale',
  `timestamp_integrity` varchar(32) NOT NULL DEFAULT 'unknown' COMMENT 'monotonic|gaps|anomalies',
  `created_ymdhis` bigint NOT NULL COMMENT 'YYYYMMDDHHMMSS',
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  PRIMARY KEY (`snapshot_id`),
  KEY `idx_created_ymdhis` (`created_ymdhis`),
  KEY `idx_is_deleted` (`is_deleted`),
  KEY `idx_utc_anchor` (`utc_anchor`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Table: lupo_tldnr
-- Fields: 12
-- Primary Key: tldnr_id
-- ============================================

CREATE TABLE `lupo_tldnr` (
  `tldnr_id` bigint NOT NULL auto_increment COMMENT 'Primary key for TL;DR record',
  `slug` varchar(255) NOT NULL COMMENT 'URL-friendly unique identifier',
  `title` varchar(255) NOT NULL COMMENT 'TL;DR title (e.g., "Lupopedia Overview", "Collection Doctrine")',
  `content_text` longtext NOT NULL COMMENT 'TL;DR content (plain text or markdown)',
  `topic_type` varchar(100) COMMENT 'Type of topic (e.g., "system", "doctrine", "module", "concept")',
  `topic_reference` varchar(255) COMMENT 'Reference to what this summarizes (e.g., "Lupopedia", "Collection Doctrine", "LABS-001")',
  `system_version` varchar(20) COMMENT 'System version this TL;DR applies to (e.g., "4.1.6")',
  `category` varchar(100) COMMENT 'Category for grouping (e.g., "Core", "Doctrine", "Module")',
  `created_ymdhis` bigint NOT NULL COMMENT 'UTC creation timestamp (YYYYMMDDHHIISS)',
  `updated_ymdhis` bigint NOT NULL COMMENT 'UTC last update timestamp (YYYYMMDDHHIISS)',
  `is_deleted` tinyint NOT NULL DEFAULT 0 COMMENT 'Soft delete flag (1=deleted, 0=active)',
  `deleted_ymdhis` bigint COMMENT 'UTC deletion timestamp (YYYYMMDDHHIISS)',
  PRIMARY KEY (`tldnr_id`),
  UNIQUE KEY `uniq_slug` (`slug`),
  KEY `idx_category` (`category`),
  KEY `idx_created` (`created_ymdhis`),
  KEY `idx_is_deleted` (`is_deleted`),
  KEY `idx_system_version` (`system_version`),
  KEY `idx_topic_reference` (`topic_reference`),
  KEY `idx_topic_type` (`topic_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Table: test_performance_metrics
-- Fields: 9
-- Primary Key: test_id
-- ============================================

CREATE TABLE `test_performance_metrics` (
  `test_id` bigint NOT NULL auto_increment,
  `test_category` varchar(64) NOT NULL,
  `test_name` varchar(128) NOT NULL,
  `execution_time_ms` int NOT NULL,
  `memory_usage_mb` decimal(10,2),
  `cpu_usage_percent` decimal(5,2),
  `success_rate` decimal(5,2),
  `error_count` int DEFAULT 0,
  `created_ymdhis` bigint NOT NULL,
  PRIMARY KEY (`test_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Table: lupo_unified_analytics_paths
-- Fields: 6
-- Primary Key: analytics_path_id
-- ============================================

CREATE TABLE `lupo_unified_analytics_paths` (
  `analytics_path_id` bigint NOT NULL,
  `period` enum('daily','monthly') NOT NULL,
  `visit_content_id` bigint,
  `exit_content_id` bigint,
  `metadata_json` json,
  `created_ymdhis` bigint,
  PRIMARY KEY (`analytics_path_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Table: lupo_unified_dialog_messages
-- Fields: 7
-- Primary Key: dialog_message_id
-- ============================================

CREATE TABLE `lupo_unified_dialog_messages` (
  `dialog_message_id` bigint NOT NULL,
  `thread_id` bigint,
  `actor_id` bigint,
  `created_ymdhis` bigint,
  `updated_ymdhis` bigint,
  `metadata_json` json,
  `body_text` longtext,
  PRIMARY KEY (`dialog_message_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Table: lupo_unified_truth_items
-- Fields: 7
-- Primary Key: truth_item_id
-- ============================================

CREATE TABLE `lupo_unified_truth_items` (
  `truth_item_id` bigint NOT NULL,
  `item_type` enum('question','answer') NOT NULL,
  `name` varchar(255),
  `slug` varchar(255),
  `body_text` longtext,
  `metadata_json` json,
  `created_ymdhis` bigint,
  PRIMARY KEY (`truth_item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Migration Completion Summary
-- ============================================
-- Successfully created 51 new tables
-- All tables follow Lupopedia WOLFIE doctrine:
--   • No foreign key constraints (application-managed relationships)
--   • BIGINT UTC timestamps in YYYYMMDDHHIISS format
--   • Proper primary key naming conventions
--   • InnoDB engine with utf8mb4 charset
--   • Federation-safe schema design

COMMIT;

-- End of migration: 20260120112952_add_missing_toon_tables_clean.sql