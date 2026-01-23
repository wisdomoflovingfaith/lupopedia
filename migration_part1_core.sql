-- =====================================================
-- LupoPedia Migration - Part 1: Core System Tables
-- Based on TOON schema only - HERITAGE-SAFE MODE
-- =====================================================

-- Core Authentication & Audit Tables
CREATE TABLE IF NOT EXISTS `auth_audit_log` (
    `id` bigint NOT NULL AUTO_INCREMENT COMMENT 'Primary key for audit log entry',
    `user_id` bigint COMMENT 'Reference to lupo_auth_users.auth_user_id',
    `crafty_operator_id` int COMMENT 'Reference to livehelp_operators.operatorid',
    `event_type` varchar(50) NOT NULL COMMENT 'login, logout, session_created, session_destroyed, etc.',
    `system_context` varchar(50) NOT NULL COMMENT 'lupopedia, crafty_syntax, unified, admin',
    `ip_address` varchar(45) COMMENT 'Client IP address',
    `user_agent` text COMMENT 'Client user agent string',
    `event_data` json COMMENT 'Additional event metadata',
    `success` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1=success, 0=failure',
    `error_message` text COMMENT 'Error details if success=0',
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Event timestamp',
    `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Last update timestamp',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `integration_test_results` (
    `test_result_id` bigint NOT NULL AUTO_INCREMENT,
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

-- Actor System Tables
CREATE TABLE IF NOT EXISTS `lupo_actors` (
    `actor_id` bigint NOT NULL AUTO_INCREMENT COMMENT 'Primary key for actor',
    `actor_type` enum('user','ai_agent','service') NOT NULL COMMENT 'Type of actor',
    `slug` varchar(255) NOT NULL COMMENT 'Stable unique identifier',
    `name` varchar(255) NOT NULL COMMENT 'Human-readable name',
    `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS',
    `updated_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS',
    `is_active` tinyint NOT NULL DEFAULT 1 COMMENT 'Active flag',
    `is_deleted` tinyint NOT NULL DEFAULT 0 COMMENT 'Soft delete flag',
    `deleted_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS',
    `actor_source_id` bigint COMMENT 'ID from source table (auth_users, agents, etc.)',
    `actor_source_type` varchar(20) COMMENT 'Source type: user, agent, system',
    `metadata` text COMMENT 'Optional JSON for additional actor attributes',
    `adversarial_role` enum('none','structural_stress','protocol_break','doctrine_test') DEFAULT 'none',
    `adversarial_oversight_actor_id` bigint COMMENT 'e.g., LILITH actor_id',
    PRIMARY KEY (`actor_id`),
    UNIQUE KEY `unique_slug` (`slug`),
    KEY `idx_actor_type` (`actor_type`),
    KEY `idx_created_ymdhis` (`created_ymdhis`),
    KEY `idx_is_active` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `lupo_auth_users` (
    `auth_user_id` bigint NOT NULL AUTO_INCREMENT,
    `username` varchar(30) NOT NULL,
    `display_name` varchar(42) NOT NULL,
    `email` varchar(100),
    `password_hash` varchar(255) COMMENT 'NULL for OAuth users',
    `auth_provider` varchar(50) COMMENT 'NULL for local users',
    `provider_id` varchar(255) COMMENT 'Provider-specific user ID',
    `profile_image_url` varchar(2000),
    `last_login_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS',
    `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS',
    `updated_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS',
    `is_active` tinyint NOT NULL DEFAULT 1 COMMENT '1 = active, 0 = inactive',
    `is_deleted` tinyint NOT NULL DEFAULT 0 COMMENT '1 = deleted, 0 = not deleted',
    `deleted_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when user was deleted',
    PRIMARY KEY (`auth_user_id`),
    UNIQUE KEY `unique_username` (`username`),
    UNIQUE KEY `unique_provider_user` (`auth_provider`, `provider_id`),
    KEY `idx_created_ymdhis` (`created_ymdhis`),
    KEY `idx_email` (`email`),
    KEY `idx_is_active` (`is_active`),
    KEY `idx_is_deleted` (`is_deleted`),
    KEY `idx_updated_ymdhis` (`updated_ymdhis`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `lupo_auth_providers` (
    `provider_id` bigint NOT NULL AUTO_INCREMENT,
    `provider_name` varchar(50) NOT NULL,
    `provider_type` varchar(50) NOT NULL,
    `config_json` json,
    `is_active` tinyint NOT NULL DEFAULT 1,
    `created_ymdhis` bigint NOT NULL,
    `updated_ymdhis` bigint NOT NULL,
    PRIMARY KEY (`provider_id`),
    UNIQUE KEY `unique_provider_name` (`provider_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `lupo_sessions` (
    `session_id` varchar(255) NOT NULL,
    `actor_id` bigint NOT NULL,
    `session_data` json,
    `ip_address` varchar(45),
    `user_agent` text,
    `created_ymdhis` bigint NOT NULL,
    `updated_ymdhis` bigint NOT NULL,
    `expires_ymdhis` bigint NOT NULL,
    `is_active` tinyint NOT NULL DEFAULT 1,
    PRIMARY KEY (`session_id`),
    KEY `idx_actor_id` (`actor_id`),
    KEY `idx_expires` (`expires_ymdhis`),
    KEY `idx_is_active` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
