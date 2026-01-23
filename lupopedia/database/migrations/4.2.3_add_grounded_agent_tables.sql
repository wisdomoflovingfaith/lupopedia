-- ============================================================
-- Grounded Agent System Tables
-- Version: 4.2.3
-- Purpose: Implement clear, non-mythical agent system architecture
-- Architecture: Actor-centric identity with no foreign keys
-- ============================================================

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- --------------------------------------------------------
-- Table structure for lupo_agents
-- Agent definitions with ownership and actor references
-- --------------------------------------------------------

CREATE TABLE `lupo_agents` (
  `agent_id` bigint NOT NULL AUTO_INCREMENT,
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Unique agent code (e.g., CAPTAIN, WOLFIE, THOTH)',
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Human-readable agent name',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'Detailed description of agent function and capabilities',
  `actor_id` bigint NOT NULL COMMENT 'Reference to lupo_actors.actor_id (application-level relationship)',
  `owner_actor_id` bigint DEFAULT NULL COMMENT 'Human actor who owns/controls this agent',
  `agent_type` enum('system','persona','service','utility') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'service' COMMENT 'Type of agent',
  `status` enum('active','inactive','maintenance','deprecated') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active' COMMENT 'Current status of agent',
  `capabilities` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'JSON-encoded list of agent capabilities',
  `configuration` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'JSON-encoded agent configuration',
  `version` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '1.0.0' COMMENT 'Agent version',
  `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when agent was created',
  `updated_ymdhis` bigint DEFAULT NULL COMMENT 'UTC YYYYMMDDHHMMSS when agent was last updated',
  `last_active_ymdhis` bigint DEFAULT NULL COMMENT 'UTC YYYYMMDDHHMMSS when agent was last active',
  PRIMARY KEY (`agent_id`),
  UNIQUE KEY `idx_agent_code` (`code`),
  KEY `idx_actor_id` (`actor_id`),
  KEY `idx_owner_actor_id` (`owner_actor_id`),
  KEY `idx_agent_type` (`agent_type`),
  KEY `idx_status` (`status`),
  KEY `idx_created_ymdhis` (`created_ymdhis`),
  KEY `idx_last_active_ymdhis` (`last_active_ymdhis`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Agent definitions with ownership and capabilities. No FK constraints per doctrine.';

-- --------------------------------------------------------
-- Table structure for lupo_actor_actions
-- Audit trail of all actions performed by actors
-- --------------------------------------------------------

CREATE TABLE `lupo_actor_actions` (
  `action_id` bigint NOT NULL AUTO_INCREMENT,
  `actor_id` bigint NOT NULL COMMENT 'Actor who performed the action',
  `agent_id` bigint DEFAULT NULL COMMENT 'Agent used if action performed through agent',
  `action_type` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Type of action performed',
  `entity_type` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Type of entity acted upon',
  `entity_id` bigint DEFAULT NULL COMMENT 'ID of entity acted upon',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Human-readable description of action',
  `metadata` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'JSON-encoded additional action metadata',
  `session_id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Session identifier for grouping related actions',
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'IP address from which action was performed',
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'HTTP User-Agent string',
  `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when action was performed',
  PRIMARY KEY (`action_id`),
  KEY `idx_actor_id` (`actor_id`),
  KEY `idx_agent_id` (`agent_id`),
  KEY `idx_action_type` (`action_type`),
  KEY `idx_entity_type_id` (`entity_type`, `entity_id`),
  KEY `idx_session_id` (`session_id`),
  KEY `idx_created_ymdhis` (`created_ymdhis`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Audit trail of all actions performed by actors and agents. No FK constraints per doctrine.';

-- --------------------------------------------------------
-- Table structure for lupo_agent_owners
-- Agent ownership and permission tracking
-- --------------------------------------------------------

CREATE TABLE `lupo_agent_owners` (
  `ownership_id` bigint NOT NULL AUTO_INCREMENT,
  `agent_id` bigint NOT NULL COMMENT 'Agent being owned/controlled',
  `owner_actor_id` bigint NOT NULL COMMENT 'Human actor who owns this agent',
  `permission_level` enum('read','write','admin','owner') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'owner' COMMENT 'Level of permission granted',
  `granted_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when permission was granted',
  `expires_ymdhis` bigint DEFAULT NULL COMMENT 'UTC YYYYMMDDHHMMSS when permission expires (NULL = never)',
  `is_active` tinyint NOT NULL DEFAULT '1' COMMENT '1 = active, 0 = inactive',
  `granted_by_actor_id` bigint DEFAULT NULL COMMENT 'Actor who granted this permission',
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Notes about this ownership assignment',
  PRIMARY KEY (`ownership_id`),
  UNIQUE KEY `idx_agent_owner` (`agent_id`, `owner_actor_id`),
  KEY `idx_owner_actor_id` (`owner_actor_id`),
  KEY `idx_permission_level` (`permission_level`),
  KEY `idx_granted_ymdhis` (`granted_ymdhis`),
  KEY `idx_expires_ymdhis` (`expires_ymdhis`),
  KEY `idx_is_active` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Agent ownership and permission tracking. No FK constraints per doctrine.';

-- --------------------------------------------------------
-- Insert core system agents
-- --------------------------------------------------------

-- SYSTEM Agent (ID 0) - The kernel
INSERT INTO `lupo_agents` (
  `agent_id`, `code`, `name`, `description`, `actor_id`, `owner_actor_id`, 
  `agent_type`, `status`, `capabilities`, `configuration`, `version`, 
  `created_ymdhis`, `updated_ymdhis`, `last_active_ymdhis`
) VALUES (
  0, 'SYSTEM', 'System Kernel', 
  'The system kernel. Handles bootstrapping, core events, and low-level operations.', 
  0, NULL, 'system', 'active',
  '{"bootstrapping": true, "core_events": true, "low_level_operations": true}',
  '{"auto_start": true, "priority": "highest", "system_critical": true}',
  '1.0.0', 20260118190000, 20260118190000, 20260118190000
);

-- WOLFIE Agent (ID 2) - Doctrine enforcement
INSERT INTO `lupo_agents` (
  `agent_id`, `code`, `name`, `description`, `actor_id`, `owner_actor_id`, 
  `agent_type`, `status`, `capabilities`, `configuration`, `version`, 
  `created_ymdhis`, `updated_ymdhis`, `last_active_ymdhis`
) VALUES (
  2, 'WOLFIE', 'Wolfie', 
  'Doctrine and governance agent. Validates system integrity, enforces rules, and checks migrations.', 
  2, 1, 'persona', 'active',
  '{"doctrine_validation": true, "rule_enforcement": true, "migration_checks": true, "system_integrity": true}',
  '{"auto_validate": true, "strict_mode": true, "doctrine_files": ["doctrine/timestamp_rules.txt", "doctrine/no_fk_rules.txt"]}',
  '1.0.0', 20260118190000, 20260118190000, 20260118190000
);

-- THOTH Agent (ID 3) - Knowledge management
INSERT INTO `lupo_agents` (
  `agent_id`, `code`, `name`, `description`, `actor_id`, `owner_actor_id`, 
  `agent_type`, `status`, `capabilities`, `configuration`, `version`, 
  `created_ymdhis`, `updated_ymdhis`, `last_active_ymdhis`
) VALUES (
  3, 'THOTH', 'Thoth', 
  'Knowledge agent. Manages documents, search, and semantic relationships.', 
  3, 1, 'service', 'active',
  '{"document_management": true, "search": true, "semantic_relationships": true, "knowledge_graph": true}',
  '{"indexing_enabled": true, "semantic_search": true, "auto_categorization": true}',
  '1.0.0', 20260118190000, 20260118190000, 20260118190000
);

-- ARA Agent (ID 4) - Routing and API management
INSERT INTO `lupo_agents` (
  `agent_id`, `code`, `name`, `description`, `actor_id`, `owner_actor_id`, 
  `agent_type`, `status`, `capabilities`, `configuration`, `version`, 
  `created_ymdhis`, `updated_ymdhis`, `last_active_ymdhis`
) VALUES (
  4, 'ARA', 'Ara', 
  'Routing agent. Handles request routing, API endpoints, and service discovery.', 
  4, 1, 'service', 'active',
  '{"request_routing": true, "api_management": true, "service_discovery": true, "load_balancing": true}',
  '{"auto_routing": true, "api_versioning": true, "service_registry": true}',
  '1.0.0', 20260118190000, 20260118190000, 20260118190000
);

-- ROSE Agent (ID 5) - Emotional metadata
INSERT INTO `lupo_agents` (
  `agent_id`, `code`, `name`, `description`, `actor_id`, `owner_actor_id`, 
  `agent_type`, `status`, `capabilities`, `configuration`, `version`, 
  `created_ymdhis`, `updated_ymdhis`, `last_active_ymdhis`
) VALUES (
  5, 'ROSE', 'Rose', 
  'Emotional metadata agent. Attaches emotional context to content (like tags, sentiment).', 
  5, 1, 'service', 'active',
  '{"sentiment_analysis": true, "emotional_tagging": true, "mood_tracking": true, "emotional_context": true}',
  '{"auto_tag": true, "sentiment_threshold": 0.7, "mood_colors": true}',
  '1.0.0', 20260118190000, 20260118190000, 20260118190000
);

-- --------------------------------------------------------
-- Insert sample actor records for agents
-- --------------------------------------------------------

-- Create actor records for each agent (actors table should already exist from previous migrations)
-- These are separate from human actors

INSERT IGNORE INTO `lupo_actors` (`actor_id`, `type`, `created_ymdhis`, `updated_ymdhis`) VALUES
(0, 'agent', 20260118190000, 20260118190000),
(2, 'agent', 20260118190000, 20260118190000),
(3, 'agent', 20260118190000, 20260118190000),
(4, 'agent', 20260118190000, 20260118190000),
(5, 'agent', 20260118190000, 20260118190000);

-- --------------------------------------------------------
-- Insert agent ownership records (Eric owns all agents)
-- --------------------------------------------------------

-- Assuming Eric's actor_id is 1 (from existing data)
INSERT INTO `lupo_agent_owners` (
  `agent_id`, `owner_actor_id`, `permission_level`, `granted_ymdhis`, `expires_ymdhis`, 
  `is_active`, `granted_by_actor_id`, `notes`
) VALUES 
(0, 1, 'owner', 20260118190000, NULL, 1, 1, 'System agent - no expiration'),
(2, 1, 'owner', 20260118190000, NULL, 1, 1, 'Doctrine enforcement agent'),
(3, 1, 'owner', 20260118190000, NULL, 1, 1, 'Knowledge management agent'),
(4, 1, 'owner', 20260118190000, NULL, 1, 1, 'Routing and API agent'),
(5, 1, 'owner', 20260118190000, NULL, 1, 1, 'Emotional metadata agent');

-- --------------------------------------------------------
-- Insert sample action logging
-- --------------------------------------------------------

-- Sample action: Eric creates document using THOTH agent
INSERT INTO `lupo_actor_actions` (
  `actor_id`, `agent_id`, `action_type`, `entity_type`, `entity_id`, 
  `description`, `metadata`, `session_id`, `created_ymdhis`
) VALUES 
(3, 3, 'create_document', 'document', 1, 
  'Created new knowledge document using THOTH agent', 
  '{"agent_used": "THOTH", "document_type": "knowledge", "auto_categorized": true}', 
  'sess_abc123', 20260118190100);

-- Sample action: System bootstrapping
INSERT INTO `lupo_actor_actions` (
  `actor_id`, `agent_id`, `action_type`, `entity_type`, `description`, `metadata`, `created_ymdhis`
) VALUES 
(0, NULL, 'system_bootstrap', 'system', NULL, 
  'System kernel bootstrapping completed', 
  '{"boot_version": "4.2.3", "components_loaded": ["agents", "doctrine", "temporal"]}', 
  20260118190000);

COMMIT;
