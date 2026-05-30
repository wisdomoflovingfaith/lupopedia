-- Lupopedia 4.1.2 SQL Migration — Orchestration Layer (Aligned with Live JSON)
-- Version: 4.1.2
-- Purpose: Implement Orchestration Layer tables based on live database state.
-- Mandatory: Aligns with lupo-database/lupopedia/json definitions.

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- 1. Operator Scratchpad
CREATE TABLE IF NOT EXISTS `lupo_operator_scratchpad` (
    `scratchpad_id` BIGINT NOT NULL PRIMARY KEY COMMENT 'YYYYMMDDHHIISS or Auto-Inc',
    `actor_id` BIGINT NOT NULL COMMENT 'Owner persona',
    `content_body` TEXT NOT NULL,
    `last_saved_ymdhis` BIGINT NOT NULL COMMENT 'YYYYMMDDHHIISS',
    `is_promoted` TINYINT(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Routing Events
CREATE TABLE IF NOT EXISTS `lupo_routing_events` (
    `routing_event_id` BIGINT NOT NULL PRIMARY KEY COMMENT 'PK following RULE 93.PK_NAMING',
    `source_message_id` BIGINT NOT NULL,
    `source_channel_id` BIGINT NOT NULL,
    `source_actor_id` BIGINT NOT NULL,
    `destination_channel_id` BIGINT NOT NULL,
    `destination_actor_id` BIGINT NOT NULL,
    `routing_explanation` TEXT NULL,
    `routed_by_actor_id` BIGINT NOT NULL,
    `created_ymdhis` BIGINT NOT NULL COMMENT 'YYYYMMDDHHIISS'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. Agent Status
CREATE TABLE IF NOT EXISTS `lupo_agent_status` (
    `agent_status_id` BIGINT NOT NULL PRIMARY KEY COMMENT 'PK following RULE 93.PK_NAMING',
    `actor_id` BIGINT NOT NULL COMMENT 'FK lupo_actors',
    `status_code` ENUM('ACTIVE','IDLE','SLEEPING','THROTTLED','FAILED','UNKNOWN','MANUAL') DEFAULT 'UNKNOWN',
    `heartbeat_ymdhis` BIGINT NOT NULL COMMENT 'YYYYMMDDHHIISS',
    `status_note` VARCHAR(255) NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. Digital Sticky Notes
CREATE TABLE IF NOT EXISTS `lupo_sticky_notes` (
    `sticky_note_id` BIGINT NOT NULL PRIMARY KEY COMMENT 'PK following RULE 93.PK_NAMING',
    `channel_id` BIGINT NOT NULL,
    `actor_id` BIGINT NOT NULL,
    `note_content` TEXT NOT NULL,
    `note_color` VARCHAR(7) DEFAULT '#ffff00',
    `is_pinned` TINYINT(1) DEFAULT 1,
    `created_ymdhis` BIGINT NOT NULL COMMENT 'YYYYMMDDHHIISS'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. External Web Agents (Actor Registry Seeding)
-- Aligns with 2000-2999 range reserved for external actors.
INSERT IGNORE INTO `lupo_actors` 
(`actor_id`, `actor_name`, `slug`, `name`, `actor_type`, `is_agent`, `is_active`, `created_ymdhis`, `updated_ymdhis`)
VALUES 
(2001, 'CHATGPT-WEB', 'chatgpt-web', 'ChatGPT (Web)', 'external_web', 1, 1, 20260415000000, 20260415000000),
(2002, 'GROK-WEB', 'grok-web', 'Grok (Web)', 'external_web', 1, 1, 20260415000000, 20260415000000),
(2003, 'GEMINI-WEB', 'gemini-web', 'Gemini (Web)', 'external_web', 1, 1, 20260415000000, 20260415000000),
(2004, 'DEEPSEEK-WEB', 'deepseek-web', 'DeepSeek (Web)', 'external_web', 1, 1, 20260415000000, 20260415000000),
(2005, 'COPILOT-WEB', 'copilot-web', 'Copilot (Web)', 'external_web', 1, 1, 20260415000000, 20260415000000);

SET FOREIGN_KEY_CHECKS = 1;
