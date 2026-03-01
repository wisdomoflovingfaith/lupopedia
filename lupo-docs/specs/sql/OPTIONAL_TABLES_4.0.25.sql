-- ============================================================
-- Optional Tables for Version 4.0.25
-- These tables are NOT canonical for 4.0.24
-- Can be added in 4.0.25 to extend from 185 to 187 tables
-- ============================================================

-- Optional Table 1: System Health Monitoring
CREATE TABLE IF NOT EXISTS `lupo_system_health` (
    `health_id` BIGINT(14) NOT NULL AUTO_INCREMENT,
    `check_name` VARCHAR(100) NOT NULL,
    `status` VARCHAR(50),
    `last_run` BIGINT(14),
    `last_result` JSON,
    `created_ymdhis` BIGINT(14) NOT NULL,
    `updated_ymdhis` BIGINT(14) NOT NULL,
    PRIMARY KEY (`health_id`),
    KEY `idx_check_name` (`check_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='System health monitoring and status checks';

-- Optional Table 2: AI Agents Registry
CREATE TABLE IF NOT EXISTS `lupo_ai_agents` (
    `agent_id` BIGINT(14) NOT NULL,
    `agent_name` VARCHAR(100) NOT NULL,
    `agent_type` VARCHAR(50),
    `provider` VARCHAR(100),
    `status` VARCHAR(50),
    `capabilities` JSON,
    `last_active` BIGINT(14),
    `created_ymdhis` BIGINT(14) NOT NULL,
    `updated_ymdhis` BIGINT(14) NOT NULL,
    PRIMARY KEY (`agent_id`),
    KEY `idx_agent_name` (`agent_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='AI agents registry and status tracking';

-- ============================================================
-- Version Update for 4.0.25
-- ============================================================

-- Update system events to reflect version 4.0.25
UPDATE `lupo_system_events` 
SET metadata_json = JSON_SET(metadata_json, '$.version', '4.0.25')
WHERE event_type = 'system_startup';

-- ============================================================
-- Summary
-- ============================================================
-- Canonical tables (4.0.24): 185
-- Optional tables (4.0.25): 2
-- Total tables (4.0.25): 187
-- Status: Ready for expansion in 4.0.25
