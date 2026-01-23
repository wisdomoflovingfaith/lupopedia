-- =====================================================
-- LupoPedia Migration - Part 7: Remaining Tables (Analytics, API, etc.)
-- Based on TOON schema only - HERITAGE-SAFE MODE
-- =====================================================

-- Migration System Tables
CREATE TABLE IF NOT EXISTS `migration_alerts` (
    `alert_id` bigint NOT NULL AUTO_INCREMENT,
    `alert_type` varchar(100) NOT NULL,
    `alert_message` text NOT NULL,
    `severity` enum('info','warning','error','critical') DEFAULT 'info',
    `created_ymdhis` bigint NOT NULL,
    `resolved_ymdhis` bigint,
    PRIMARY KEY (`alert_id`),
    KEY `idx_alert_type` (`alert_type`),
    KEY `idx_severity` (`severity`),
    KEY `idx_created_ymdhis` (`created_ymdhis`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `migration_batches` (
    `batch_id` bigint NOT NULL AUTO_INCREMENT,
    `batch_name` varchar(100) NOT NULL,
    `batch_status` enum('pending','running','completed','failed') DEFAULT 'pending',
    `total_items` int DEFAULT 0,
    `processed_items` int DEFAULT 0,
    `started_ymdhis` bigint,
    `completed_ymdhis` bigint,
    `created_ymdhis` bigint NOT NULL,
    PRIMARY KEY (`batch_id`),
    KEY `idx_batch_status` (`batch_status`),
    KEY `idx_created_ymdhis` (`created_ymdhis`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `migration_dependencies` (
    `dependency_id` bigint NOT NULL AUTO_INCREMENT,
    `dependency_name` varchar(100) NOT NULL,
    `dependency_type` varchar(50) NOT NULL,
    `required_version` varchar(50),
    `is_satisfied` tinyint NOT NULL DEFAULT 0,
    `created_ymdhis` bigint NOT NULL,
    PRIMARY KEY (`dependency_id`),
    KEY `idx_dependency_name` (`dependency_name`),
    KEY `idx_dependency_type` (`dependency_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `migration_files` (
    `file_id` bigint NOT NULL AUTO_INCREMENT,
    `file_name` varchar(255) NOT NULL,
    `file_path` varchar(500) NOT NULL,
    `file_hash` varchar(64),
    `file_size` bigint,
    `migration_status` enum('pending','processing','completed','failed') DEFAULT 'pending',
    `created_ymdhis` bigint NOT NULL,
    `processed_ymdhis` bigint,
    PRIMARY KEY (`file_id`),
    KEY `idx_file_name` (`file_name`),
    KEY `idx_migration_status` (`migration_status`),
    KEY `idx_created_ymdhis` (`created_ymdhis`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `migration_progress` (
    `progress_id` bigint NOT NULL AUTO_INCREMENT,
    `migration_name` varchar(100) NOT NULL,
    `current_step` varchar(100),
    `progress_percentage` decimal(5,2) DEFAULT 0.00,
    `status_message` text,
    `created_ymdhis` bigint NOT NULL,
    `updated_ymdhis` bigint NOT NULL,
    PRIMARY KEY (`progress_id`),
    KEY `idx_migration_name` (`migration_name`),
    KEY `idx_updated_ymdhis` (`updated_ymdhis`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `migration_rollback_log` (
    `rollback_id` bigint NOT NULL AUTO_INCREMENT,
    `migration_name` varchar(100) NOT NULL,
    `rollback_reason` text NOT NULL,
    `rollback_data` json,
    `rollback_status` enum('pending','in_progress','completed','failed') DEFAULT 'pending',
    `created_ymdhis` bigint NOT NULL,
    `completed_ymdhis` bigint,
    PRIMARY KEY (`rollback_id`),
    KEY `idx_migration_name` (`migration_name`),
    KEY `idx_rollback_status` (`rollback_status`),
    KEY `idx_created_ymdhis` (`created_ymdhis`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `migration_system_state` (
    `state_id` bigint NOT NULL AUTO_INCREMENT,
    `state_key` varchar(100) NOT NULL,
    `state_value` text,
    `state_type` varchar(50) DEFAULT 'string',
    `created_ymdhis` bigint NOT NULL,
    `updated_ymdhis` bigint NOT NULL,
    PRIMARY KEY (`state_id`),
    UNIQUE KEY `unique_state_key` (`state_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `migration_validation_log` (
    `validation_id` bigint NOT NULL AUTO_INCREMENT,
    `validation_type` varchar(100) NOT NULL,
    `validation_result` enum('pass','fail','warning') NOT NULL,
    `validation_message` text,
    `validation_data` json,
    `created_ymdhis` bigint NOT NULL,
    PRIMARY KEY (`validation_id`),
    KEY `idx_validation_type` (`validation_type`),
    KEY `idx_validation_result` (`validation_result`),
    KEY `idx_created_ymdhis` (`created_ymdhis`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `test_performance_metrics` (
    `metric_id` bigint NOT NULL AUTO_INCREMENT,
    `test_name` varchar(100) NOT NULL,
    `metric_type` varchar(50) NOT NULL,
    `metric_value` decimal(10,4),
    `metric_unit` varchar(20),
    `test_timestamp` bigint NOT NULL,
    PRIMARY KEY (`metric_id`),
    KEY `idx_test_name` (`test_name`),
    KEY `idx_metric_type` (`metric_type`),
    KEY `idx_test_timestamp` (`test_timestamp`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Unified Tables (duplicates)
CREATE TABLE IF NOT EXISTS `unified_sessions` (
    `unified_session_id` bigint NOT NULL AUTO_INCREMENT,
    `session_key` varchar(255) NOT NULL,
    `session_data` json,
    `created_ymdhis` bigint NOT NULL,
    `updated_ymdhis` bigint NOT NULL,
    `expires_ymdhis` bigint,
    PRIMARY KEY (`unified_session_id`),
    KEY `idx_session_key` (`session_key`),
    KEY `idx_expires_ymdhis` (`expires_ymdhis`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `unified_dialog_messages` (
    `unified_message_id` bigint NOT NULL AUTO_INCREMENT,
    `message_key` varchar(255) NOT NULL,
    `channel_id` bigint,
    `actor_id` bigint,
    `message_content` text,
    `message_metadata` json,
    `created_ymdhis` bigint NOT NULL,
    PRIMARY KEY (`unified_message_id`),
    KEY `idx_message_key` (`message_key`),
    KEY `idx_channel_id` (`channel_id`),
    KEY `idx_actor_id` (`actor_id`),
    KEY `idx_created_ymdhis` (`created_ymdhis`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `unified_truth_items` (
    `unified_truth_id` bigint NOT NULL AUTO_INCREMENT,
    `truth_key` varchar(255) NOT NULL,
    `truth_value` text,
    `truth_type` varchar(50),
    `confidence_score` decimal(3,2) DEFAULT 1.00,
    `source_actor_id` bigint,
    `created_ymdhis` bigint NOT NULL,
    `updated_ymdhis` bigint NOT NULL,
    PRIMARY KEY (`unified_truth_id`),
    KEY `idx_truth_key` (`truth_key`),
    KEY `idx_truth_type` (`truth_type`),
    KEY `idx_source_actor_id` (`source_actor_id`),
    KEY `idx_created_ymdhis` (`created_ymdhis`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `unified_analytics_paths` (
    `path_id` bigint NOT NULL AUTO_INCREMENT,
    `path_pattern` varchar(500) NOT NULL,
    `path_type` varchar(50),
    `access_count` int DEFAULT 0,
    `last_accessed` bigint,
    `created_ymdhis` bigint NOT NULL,
    PRIMARY KEY (`path_id`),
    KEY `idx_path_pattern` (`path_pattern`),
    KEY `idx_path_type` (`path_type`),
    KEY `idx_access_count` (`access_count`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
