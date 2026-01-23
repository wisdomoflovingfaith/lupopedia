-- =====================================================
-- LupoPedia Migration - Part 5: Actor Relationship Tables
-- Based on TOON schema only - HERITAGE-SAFE MODE
-- =====================================================

CREATE TABLE IF NOT EXISTS `lupo_actor_actions` (
    `actor_action_id` bigint NOT NULL AUTO_INCREMENT,
    `actor_id` bigint NOT NULL,
    `action_type` varchar(64) NOT NULL,
    `entity_type` varchar(64),
    `entity_id` bigint,
    `description` text,
    `metadata_json` json,
    `created_ymdhis` char(14) NOT NULL,
    PRIMARY KEY (`actor_action_id`),
    KEY `idx_action_type` (`action_type`),
    KEY `idx_actor` (`actor_id`),
    KEY `idx_entity` (`entity_type`, `entity_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `lupo_actor_capabilities` (
    `actor_capability_id` bigint NOT NULL AUTO_INCREMENT,
    `actor_id` bigint NOT NULL,
    `domain_id` bigint NOT NULL COMMENT 'Domain scope for this capability',
    `capability_key` varchar(100) NOT NULL COMMENT 'Unique capability identifier (e.g., "edit_content", "manage_users")',
    `capability_description` text COMMENT 'Detailed description of capability',
    `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when created',
    `updated_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when last updated',
    `is_deleted` tinyint NOT NULL DEFAULT 0 COMMENT '1 = deleted, 0 = not deleted',
    `deleted_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when deleted',
    `scope_limitation` varchar(50) DEFAULT 'unrestricted' COMMENT 'domain, session, user, content',
    `max_calls_per_hour` int DEFAULT 0 COMMENT '0 = unlimited',
    `requires_approval` tinyint DEFAULT 0 COMMENT '1 = needs manual approval',
    `approval_agent_id` bigint COMMENT 'Which agent must approve',
    PRIMARY KEY (`actor_capability_id`),
    UNIQUE KEY `unique_agent_domain_capability` (`actor_id`, `domain_id`, `capability_key`),
    KEY `idx_agent_domain` (`actor_id`, `domain_id`),
    KEY `idx_capability_key` (`capability_key`),
    KEY `idx_created_ymdhis` (`created_ymdhis`),
    KEY `idx_domain_id` (`domain_id`),
    KEY `idx_is_deleted` (`is_deleted`),
    KEY `idx_updated_ymdhis` (`updated_ymdhis`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `lupo_actor_conflicts` (
    `actor_conflict_id` bigint NOT NULL AUTO_INCREMENT COMMENT 'Primary key for conflict record',
    `domain_id` bigint NOT NULL DEFAULT 1 COMMENT 'Domain/tenant this conflict belongs to',
    `actor_a_id` bigint NOT NULL,
    `actor_b_id` bigint NOT NULL,
    `conflict_type` varchar(64) NOT NULL COMMENT 'Type/category of conflict',
    `conflict_summary` text NOT NULL COMMENT 'Detailed description of conflict',
    `resolution_status` enum('unresolved','resolved','ignored') NOT NULL DEFAULT 'unresolved' COMMENT 'Current status of conflict resolution',
    `resolution_summary` text COMMENT 'How conflict was resolved (if applicable)',
    `resolved_by` bigint COMMENT 'Actor who resolved the conflict (if applicable)',
    `resolved_ymdhis` bigint COMMENT 'When conflict was resolved (YYYYMMDDHHMMSS)',
    `severity` enum('low','medium','high','critical') NOT NULL DEFAULT 'medium' COMMENT 'Severity level of conflict',
    `context_json` json COMMENT 'Additional context about conflict in JSON format',
    `created_ymdhis` bigint NOT NULL COMMENT 'Creation timestamp (YYYYMMDDHHMMSS)',
    `updated_ymdhis` bigint NOT NULL COMMENT 'Last update timestamp (YYYYMMDDHHMMSS)',
    `is_deleted` tinyint NOT NULL DEFAULT 0 COMMENT 'Soft delete flag (1=deleted, 0=active)',
    `deleted_ymdhis` bigint COMMENT 'Deletion timestamp (YYYYMMDDHHMMSS)',
    PRIMARY KEY (`actor_conflict_id`),
    KEY `idx_agent_a` (`actor_a_id`),
    KEY `idx_agent_b` (`actor_b_id`),
    KEY `idx_agent_pair` (`actor_a_id`, `actor_b_id`),
    KEY `idx_conflict_type` (`conflict_type`),
    KEY `idx_created` (`created_ymdhis`),
    KEY `idx_deleted` (`is_deleted`),
    KEY `idx_domain` (`domain_id`),
    KEY `idx_resolved_ymdhis` (`resolved_ymdhis`),
    KEY `idx_severity` (`severity`),
    KEY `idx_status` (`resolution_status`),
    KEY `idx_updated` (`updated_ymdhis`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `lupo_actor_departments` (
    `actor_department_id` bigint NOT NULL AUTO_INCREMENT,
    `actor_id` bigint NOT NULL,
    `department_id` bigint NOT NULL,
    `title` varchar(64),
    `created_ymdhis` char(14) NOT NULL,
    `updated_ymdhis` char(14) NOT NULL,
    `is_deleted` tinyint NOT NULL DEFAULT 0,
    `deleted_ymdhis` char(14),
    PRIMARY KEY (`actor_department_id`),
    KEY `idx_actor` (`actor_id`),
    KEY `idx_department` (`department_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `lupo_actor_edges` (
    `actor_edge_id` bigint NOT NULL AUTO_INCREMENT,
    `domain_id` bigint NOT NULL COMMENT 'Domain scope for this relationship',
    `source_actor_id` bigint NOT NULL COMMENT 'Source agent of relationship',
    `target_actor_id` bigint NOT NULL COMMENT 'Target agent of relationship',
    `edge_type` varchar(100) NOT NULL COMMENT 'Type of relationship (e.g., "collaborates_with", "critiques", "balances")',
    `weight` float DEFAULT 1 COMMENT 'Strength or weight of relationship',
    `properties` text COMMENT 'JSON or TOON formatted metadata for relationship',
    `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when created',
    `updated_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when last updated',
    `is_deleted` tinyint NOT NULL DEFAULT 0 COMMENT '1 = deleted, 0 = not deleted',
    `deleted_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when deleted',
    PRIMARY KEY (`actor_edge_id`),
    UNIQUE KEY `unique_agent_edge` (`domain_id`, `source_actor_id`, `target_actor_id`, `edge_type`),
    KEY `idx_created_ymdhis` (`created_ymdhis`),
    KEY `idx_domain_id` (`domain_id`),
    KEY `idx_edge_source_relationship` (`source_actor_id`, `edge_type`),
    KEY `idx_edge_target_relationship` (`target_actor_id`, `edge_type`),
    KEY `idx_edge_type` (`edge_type`),
    KEY `idx_is_deleted` (`is_deleted`),
    KEY `idx_source_agent` (`source_actor_id`),
    KEY `idx_source_target` (`source_actor_id`, `target_actor_id`),
    KEY `idx_target_agent` (`target_actor_id`),
    KEY `idx_updated_ymdhis` (`updated_ymdhis`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `lupo_actor_events` (
    `actor_event_id` bigint NOT NULL AUTO_INCREMENT COMMENT 'Primary key for actor event',
    `actor_id` bigint NOT NULL COMMENT 'Actor ID from lupo_actors',
    `session_id` varchar(255) COMMENT 'Session identifier',
    `tab_id` varchar(255) COMMENT 'Tab identifier',
    `world_id` bigint COMMENT 'World context ID',
    `world_key` varchar(255) COMMENT 'World context key',
    `world_type` varchar(50) COMMENT 'World context type',
    `event_type` varchar(100) NOT NULL COMMENT 'Type of actor event',
    `event_data` json COMMENT 'Event-specific data',
    `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS',
    PRIMARY KEY (`actor_event_id`),
    KEY `idx_actor_event_type` (`actor_id`, `event_type`),
    KEY `idx_actor_id` (`actor_id`),
    KEY `idx_created_ymdhis` (`created_ymdhis`),
    KEY `idx_event_type` (`event_type`),
    KEY `idx_session_id` (`session_id`),
    KEY `idx_tab_id` (`tab_id`),
    KEY `idx_world_id` (`world_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
