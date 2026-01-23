-- =====================================================
-- LupoPedia Migration - Part 2: Agent & AI Tables
-- Based on TOON schema only - HERITAGE-SAFE MODE
-- =====================================================

CREATE TABLE IF NOT EXISTS `lupo_agents` (
    `agent_id` bigint NOT NULL AUTO_INCREMENT COMMENT 'Primary key for agent',
    `agent_key` varchar(100) NOT NULL COMMENT 'Canonical identifier (wolfie, lilith, maat, etc.)',
    `agent_name` varchar(150) NOT NULL COMMENT 'Human-readable name',
    `archetype` varchar(150) COMMENT 'Mythic or symbolic identity',
    `description` text COMMENT 'Agent description and purpose',
    `version` varchar(50) DEFAULT '1.0' COMMENT 'Agent version',
    `model_name` varchar(100),
    `is_global_authority` tinyint NOT NULL DEFAULT 0 COMMENT '1 = global authority agent',
    `is_internal_only` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Internal only flag',
    `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when created',
    `updated_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when updated',
    `is_deleted` tinyint NOT NULL DEFAULT 0 COMMENT '1 = deleted, 0 = not deleted',
    `deleted_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when deleted',
    `avg_response_time_ms` int DEFAULT 0,
    `total_tokens_processed` bigint DEFAULT 0 COMMENT 'Total tokens processed',
    `success_rate` float DEFAULT 1 COMMENT '0.0 to 1.0',
    `cost_per_1k_tokens` decimal(10,4) DEFAULT 0.0000 COMMENT 'For cost tracking',
    `temperature` float DEFAULT 0.7,
    `top_p` float DEFAULT 1,
    `max_tokens` int DEFAULT 2048,
    `presence_penalty` float DEFAULT 0,
    `frequency_penalty` float DEFAULT 0,
    `system_prompt` text,
    `provider` varchar(50) DEFAULT 'openai',
    `api_key_id` bigint COMMENT 'API key reference',
    `timeout_ms` int DEFAULT 20000,
    `safety_json` json,
    `response_format` varchar(50),
    PRIMARY KEY (`agent_id`),
    UNIQUE KEY `unique_agent_key` (`agent_key`),
    KEY `idx_created_ymdhis` (`created_ymdhis`),
    KEY `idx_is_deleted` (`is_deleted`),
    KEY `idx_is_global_authority` (`is_global_authority`),
    KEY `idx_updated_ymdhis` (`updated_ymdhis`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `lupo_agent_registry` (
    `registry_id` bigint NOT NULL AUTO_INCREMENT,
    `agent_id` bigint NOT NULL,
    `registry_key` varchar(100) NOT NULL,
    `registry_value` text,
    `registry_type` varchar(50),
    `created_ymdhis` bigint NOT NULL,
    `updated_ymdhis` bigint NOT NULL,
    `is_deleted` tinyint NOT NULL DEFAULT 0,
    PRIMARY KEY (`registry_id`),
    KEY `idx_agent_id` (`agent_id`),
    KEY `idx_registry_key` (`registry_key`),
    KEY `idx_is_deleted` (`is_deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `lupo_agent_properties` (
    `property_id` bigint NOT NULL AUTO_INCREMENT,
    `agent_id` bigint NOT NULL,
    `property_key` varchar(100) NOT NULL,
    `property_value` text,
    `property_type` varchar(50),
    `created_ymdhis` bigint NOT NULL,
    `updated_ymdhis` bigint NOT NULL,
    `is_deleted` tinyint NOT NULL DEFAULT 0,
    PRIMARY KEY (`property_id`),
    KEY `idx_agent_id` (`agent_id`),
    KEY `idx_property_key` (`property_key`),
    KEY `idx_is_deleted` (`is_deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `lupo_agent_versions` (
    `version_id` bigint NOT NULL AUTO_INCREMENT,
    `agent_id` bigint NOT NULL,
    `version_number` varchar(50) NOT NULL,
    `version_description` text,
    `config_snapshot` json,
    `created_ymdhis` bigint NOT NULL,
    `is_active` tinyint NOT NULL DEFAULT 1,
    PRIMARY KEY (`version_id`),
    KEY `idx_agent_id` (`agent_id`),
    KEY `idx_version_number` (`version_number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `lupo_agent_dependencies` (
    `dependency_id` bigint NOT NULL AUTO_INCREMENT,
    `agent_id` bigint NOT NULL,
    `depends_on_agent_id` bigint NOT NULL,
    `dependency_type` varchar(50),
    `dependency_description` text,
    `created_ymdhis` bigint NOT NULL,
    `is_deleted` tinyint NOT NULL DEFAULT 0,
    PRIMARY KEY (`dependency_id`),
    KEY `idx_agent_id` (`agent_id`),
    KEY `idx_depends_on` (`depends_on_agent_id`),
    KEY `idx_dependency_type` (`dependency_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `lupo_agent_context_snapshots` (
    `snapshot_id` bigint NOT NULL AUTO_INCREMENT,
    `agent_id` bigint NOT NULL,
    `context_key` varchar(100) NOT NULL,
    `context_data` json,
    `context_type` varchar(50),
    `created_ymdhis` bigint NOT NULL,
    `expires_ymdhis` bigint,
    `is_deleted` tinyint NOT NULL DEFAULT 0,
    PRIMARY KEY (`snapshot_id`),
    KEY `idx_agent_id` (`agent_id`),
    KEY `idx_context_key` (`context_key`),
    KEY `idx_expires` (`expires_ymdhis`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `lupo_agent_external_events` (
    `event_id` bigint NOT NULL AUTO_INCREMENT,
    `agent_id` bigint NOT NULL,
    `event_type` varchar(100) NOT NULL,
    `event_data` json,
    `external_source` varchar(100),
    `created_ymdhis` bigint NOT NULL,
    `processed_ymdhis` bigint,
    `is_deleted` tinyint NOT NULL DEFAULT 0,
    PRIMARY KEY (`event_id`),
    KEY `idx_agent_id` (`agent_id`),
    KEY `idx_event_type` (`event_type`),
    KEY `idx_created_ymdhis` (`created_ymdhis`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
