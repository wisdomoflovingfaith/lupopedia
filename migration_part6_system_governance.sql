-- =====================================================
-- LupoPedia Migration - Part 6: System & Governance Tables
-- Based on TOON schema only - HERITAGE-SAFE MODE
-- =====================================================

CREATE TABLE IF NOT EXISTS `lupo_system_config` (
    `config_id` bigint NOT NULL AUTO_INCREMENT,
    `config_key` varchar(100) NOT NULL,
    `config_value` text,
    `config_type` varchar(50) DEFAULT 'string',
    `description` text,
    `created_ymdhis` bigint NOT NULL,
    `updated_ymdhis` bigint NOT NULL,
    `is_deleted` tinyint NOT NULL DEFAULT 0,
    PRIMARY KEY (`config_id`),
    UNIQUE KEY `unique_config_key` (`config_key`),
    KEY `idx_config_type` (`config_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `lupo_system_events` (
    `event_id` bigint NOT NULL AUTO_INCREMENT,
    `event_type` varchar(100) NOT NULL,
    `event_data` json,
    `actor_id` bigint,
    `severity` enum('info','warning','error','critical') DEFAULT 'info',
    `created_ymdhis` bigint NOT NULL,
    `processed_ymdhis` bigint,
    PRIMARY KEY (`event_id`),
    KEY `idx_event_type` (`event_type`),
    KEY `idx_actor_id` (`actor_id`),
    KEY `idx_severity` (`severity`),
    KEY `idx_created_ymdhis` (`created_ymdhis`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `lupo_system_logs` (
    `log_id` bigint NOT NULL AUTO_INCREMENT,
    `log_level` varchar(20) NOT NULL,
    `log_message` text NOT NULL,
    `context_data` json,
    `actor_id` bigint,
    `channel_id` bigint,
    `created_ymdhis` bigint NOT NULL,
    PRIMARY KEY (`log_id`),
    KEY `idx_log_level` (`log_level`),
    KEY `idx_actor_id` (`actor_id`),
    KEY `idx_channel_id` (`channel_id`),
    KEY `idx_created_ymdhis` (`created_ymdhis`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `lupo_system_health_snapshots` (
    `snapshot_id` bigint NOT NULL AUTO_INCREMENT,
    `metric_name` varchar(100) NOT NULL,
    `metric_value` decimal(10,4),
    `metric_unit` varchar(20),
    `health_status` enum('healthy','warning','critical') DEFAULT 'healthy',
    `created_ymdhis` bigint NOT NULL,
    PRIMARY KEY (`snapshot_id`),
    KEY `idx_metric_name` (`metric_name`),
    KEY `idx_health_status` (`health_status`),
    KEY `idx_created_ymdhis` (`created_ymdhis`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `lupo_gov_events` (
    `gov_event_id` bigint NOT NULL AUTO_INCREMENT,
    `event_key` varchar(100) NOT NULL,
    `event_title` varchar(255) NOT NULL,
    `event_description` text,
    `event_type` varchar(50) NOT NULL,
    `status` enum('proposed','active','completed','cancelled') DEFAULT 'proposed',
    `priority` enum('low','medium','high','critical') DEFAULT 'medium',
    `created_by_actor_id` bigint NOT NULL,
    `assigned_to_actor_id` bigint,
    `created_ymdhis` bigint NOT NULL,
    `updated_ymdhis` bigint NOT NULL,
    `completed_ymdhis` bigint,
    `is_deleted` tinyint NOT NULL DEFAULT 0,
    PRIMARY KEY (`gov_event_id`),
    KEY `idx_event_key` (`event_key`),
    KEY `idx_event_type` (`event_type`),
    KEY `idx_status` (`status`),
    KEY `idx_priority` (`priority`),
    KEY `idx_created_by` (`created_by_actor_id`),
    KEY `idx_assigned_to` (`assigned_to_actor_id`),
    KEY `idx_created_ymdhis` (`created_ymdhis`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `lupo_gov_event_actor_edges` (
    `edge_id` bigint NOT NULL AUTO_INCREMENT,
    `gov_event_id` bigint NOT NULL,
    `actor_id` bigint NOT NULL,
    `edge_type` varchar(50) NOT NULL,
    `edge_weight` float DEFAULT 1.0,
    `edge_data` json,
    `created_ymdhis` bigint NOT NULL,
    `is_deleted` tinyint NOT NULL DEFAULT 0,
    PRIMARY KEY (`edge_id`),
    KEY `idx_gov_event_id` (`gov_event_id`),
    KEY `idx_actor_id` (`actor_id`),
    KEY `idx_edge_type` (`edge_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `lupo_gov_event_conflicts` (
    `conflict_id` bigint NOT NULL AUTO_INCREMENT,
    `gov_event_id` bigint NOT NULL,
    `conflict_type` varchar(100) NOT NULL,
    `conflict_description` text NOT NULL,
    `resolution_status` enum('unresolved','resolved','escalated') DEFAULT 'unresolved',
    `resolved_by_actor_id` bigint,
    `resolution_notes` text,
    `created_ymdhis` bigint NOT NULL,
    `resolved_ymdhis` bigint,
    PRIMARY KEY (`conflict_id`),
    KEY `idx_gov_event_id` (`gov_event_id`),
    KEY `idx_conflict_type` (`conflict_type`),
    KEY `idx_resolution_status` (`resolution_status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `lupo_gov_event_dependencies` (
    `dependency_id` bigint NOT NULL AUTO_INCREMENT,
    `gov_event_id` bigint NOT NULL,
    `depends_on_event_id` bigint NOT NULL,
    `dependency_type` varchar(50) NOT NULL,
    `dependency_description` text,
    `created_ymdhis` bigint NOT NULL,
    `is_deleted` tinyint NOT NULL DEFAULT 0,
    PRIMARY KEY (`dependency_id`),
    KEY `idx_gov_event_id` (`gov_event_id`),
    KEY `idx_depends_on` (`depends_on_event_id`),
    KEY `idx_dependency_type` (`dependency_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
