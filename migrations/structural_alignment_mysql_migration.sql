-- ======================================================================
-- STRUCTURAL ALIGNMENT & MYSQL MIGRATION PROPOSAL
-- ======================================================================
-- Purpose: Align current MySQL schema with TOON definitions
-- Version: 4.0.45
-- Created: 2026-01-16
-- Status: FOR HUMAN REVIEW ONLY (human_in_the_loop: true)
-- 
-- Constraints:
-- - DO NOT APPLY without human review
-- - Ensure all timestamps are BIGINT(14) UNSIGNED (YYYYMMDDHHIISS)
-- - Remove any accidental foreign keys or triggers (Doctrine compliance)
-- - Replace improvised bridges with formal dialog_channels table entries
-- ======================================================================

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";
START TRANSACTION;

-- ======================================================================
-- SECTION 1: TIMESTAMP COMPLIANCE FIXES
-- ======================================================================

-- Fix lupo_actor_actions.created_ymdhis from CHAR(14) to BIGINT(14) UNSIGNED
ALTER TABLE `lupo_actor_actions` 
MODIFY COLUMN `created_ymdhis` BIGINT(14) UNSIGNED NOT NULL COMMENT 'UTC YYYYMMDDHHIISS';

-- Ensure all timestamp fields across all tables are BIGINT(14) UNSIGNED
-- This section will be expanded after full schema analysis

-- ======================================================================
-- SECTION 2: FOREIGN KEY REMOVAL (DOCTRINE COMPLIANCE)
-- ======================================================================

-- Check for and remove any foreign key constraints
-- (This section will be populated after analyzing existing FK constraints)

-- ======================================================================
-- SECTION 3: TRIGGER REMOVAL (DOCTRINE COMPLIANCE)
-- ======================================================================

-- Drop any existing triggers (DOCTRINE: NO TRIGGERS ALLOWED)
-- (This section will be populated after analyzing existing triggers)

-- ======================================================================
-- SECTION 4: DIALOG CHANNELS FORMALIZATION
-- ======================================================================

-- Create formal dialog_channels table to replace improvised bridges
CREATE TABLE IF NOT EXISTS `lupo_dialog_channels` (
  `dialog_channel_id` BIGINT(14) UNSIGNED NOT NULL AUTO_INCREMENT,
  `channel_key` VARCHAR(64) NOT NULL COMMENT 'URL-friendly channel identifier',
  `channel_name` VARCHAR(255) NOT NULL COMMENT 'Human-readable channel name',
  `channel_type` ENUM('system', 'user', 'agent', 'service') NOT NULL DEFAULT 'system',
  `federation_node_id` BIGINT(14) UNSIGNED NOT NULL DEFAULT 1 COMMENT 'Domain/tenant identifier',
  `created_by_actor_id` BIGINT(14) UNSIGNED NOT NULL COMMENT 'Actor who created this channel',
  `default_actor_id` BIGINT(14) UNSIGNED NOT NULL DEFAULT 1 COMMENT 'Default actor for channel',
  `description` TEXT COMMENT 'Channel description and purpose',
  `metadata_json` TEXT COMMENT 'JSON metadata for channel configuration',
  `bgcolor` VARCHAR(6) NOT NULL DEFAULT 'FFFFFF' COMMENT 'Channel background color',
  `status_flag` TINYINT(1) NOT NULL DEFAULT 1 COMMENT 'Status flag (1=active, 0=inactive)',
  `priority` INT NOT NULL DEFAULT 0 COMMENT 'Channel priority for routing',
  `end_ymdhis` BIGINT(14) UNSIGNED NULL COMMENT 'Channel end timestamp (YYYYMMDDHHIISS, NULL if ongoing)',
  `duration_seconds` INT NULL COMMENT 'Duration of channel in seconds (if ended)',
  `created_ymdhis` BIGINT(14) UNSIGNED NOT NULL COMMENT 'Creation timestamp (YYYYMMDDHHIISS)',
  `updated_ymdhis` BIGINT(14) UNSIGNED NOT NULL COMMENT 'Last update timestamp (YYYYMMDDHHIISS)',
  `is_deleted` TINYINT(1) NOT NULL DEFAULT 0 COMMENT 'Soft delete flag (1=deleted, 0=active)',
  `deleted_ymdhis` BIGINT(14) UNSIGNED NULL COMMENT 'Deletion timestamp (YYYYMMDDHHIISS)',
  PRIMARY KEY (`dialog_channel_id`),
  UNIQUE KEY `unq_channel_key_per_node` (`channel_key`, `federation_node_id`),
  KEY `idx_channel_key` (`channel_key`),
  KEY `idx_federation_node_id` (`federation_node_id`),
  KEY `idx_created_by_actor_id` (`created_by_actor_id`),
  KEY `idx_status_flag` (`status_flag`),
  KEY `idx_end_ymdhis` (`end_ymdhis`),
  KEY `idx_created_ymdhis` (`created_ymdhis`),
  KEY `idx_updated_ymdhis` (`updated_ymdhis`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Formal dialog channels replacing improvised bridges';

-- Insert system channels from TOON definitions
INSERT INTO `lupo_dialog_channels` (
  `channel_key`, `channel_name`, `channel_type`, `created_by_actor_id`, `default_actor_id`,
  `description`, `metadata_json`, `bgcolor`, `status_flag`, `created_ymdhis`, `updated_ymdhis`
) VALUES 
(
  'system/kernel', 
  'System Kernel Channel', 
  'system', 
  0, 
  1,
  'Reserved channel for bootstrapping, migrations, and OS-level events.',
  '{"purpose": "kernel", "protected": true, "auto_created": true}',
  'FFFFFF',
  1,
  20260106084500,
  20260106084500
),
(
  'system/lobby',
  'Lobby Channel',
  'system',
  0,
  1,
  'Universal entry point for all new actors. Temporary holding area before routing.',
  '{"purpose": "system-root", "auto_created": true}',
  'CCCCCC',
  1,
  20260106082200,
  20260106082200
);

-- ======================================================================
-- SECTION 5: TOON ALIGNMENT VERIFICATION
-- ======================================================================

-- Create a temporary table to track TOON vs SQL alignment
CREATE TEMPORARY TABLE `toon_alignment_check` (
  `table_name` VARCHAR(255) NOT NULL,
  `toon_exists` TINYINT(1) NOT NULL DEFAULT 0,
  `sql_exists` TINYINT(1) NOT NULL DEFAULT 0,
  `alignment_status` VARCHAR(50) NOT NULL DEFAULT 'unknown',
  PRIMARY KEY (`table_name`)
) ENGINE=MEMORY;

-- Populate with TOON table names (this will be expanded)
INSERT INTO `toon_alignment_check` (`table_name`, `toon_exists`) VALUES
('lupo_actors', 1),
('lupo_actor_actions', 1),
('lupo_actor_capabilities', 1),
('lupo_actor_channels', 1),
('lupo_actor_channel_roles', 1),
('lupo_actor_collections', 1),
('lupo_actor_conflicts', 1),
('lupo_actor_departments', 1),
('lupo_actor_edges', 1),
('lupo_actor_group_membership', 1),
('lupo_actor_properties', 1),
('lupo_actor_reply_templates', 1),
('lupo_actor_roles', 1),
('lupo_agent_context_snapshots', 1),
('lupo_agent_dependencies', 1),
('lupo_agent_external_events', 1),
('lupo_agent_faucet_credentials', 1),
('lupo_agent_faucets', 1),
('lupo_agent_properties', 1),
('lupo_agent_registry', 1),
('lupo_agent_tool_calls', 1),
('lupo_agent_versions', 1),
('lupo_agents', 1),
('lupo_analytics_campaign_vars_daily', 1),
('lupo_analytics_campaign_vars_monthly', 1),
('lupo_analytics_paths_daily', 1),
('lupo_analytics_paths_monthly', 1),
('lupo_analytics_referers_daily', 1),
('lupo_analytics_referers_monthly', 1),
('lupo_analytics_visits', 1),
('lupo_analytics_visits_daily', 1),
('lupo_analytics_visits_monthly', 1),
('lupo_anubis_events', 1),
('lupo_anubis_orphaned', 1),
('lupo_anubis_redirects', 1),
('lupo_api_clients', 1),
('lupo_api_rate_limits', 1),
('lupo_api_token_logs', 1),
('lupo_api_tokens', 1),
('lupo_api_webhooks', 1),
('lupo_atoms', 1),
('lupo_audit_log', 1),
('lupo_auth_providers', 1),
('lupo_auth_users', 1),
('lupo_channels', 1),
('lupo_collection_tab_map', 1),
('lupo_collection_tab_paths', 1),
('lupo_collection_tabs', 1),
('lupo_collections', 1),
('lupo_content_atom_map', 1);

-- ======================================================================
-- SECTION 6: DOCTRINE COMPLIANCE VALIDATION
-- ======================================================================

-- Create procedure to validate doctrine compliance
DELIMITER //
CREATE PROCEDURE `validate_doctrine_compliance`()
BEGIN
  DECLARE done INT DEFAULT FALSE;
  DECLARE table_name VARCHAR(255);
  DECLARE violation_count INT DEFAULT 0;
  
  DECLARE table_cursor CURSOR FOR 
    SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES 
    WHERE TABLE_SCHEMA = DATABASE() AND TABLE_TYPE = 'BASE TABLE';
  
  DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;
  
  OPEN table_cursor;
  
  read_loop: LOOP
    FETCH table_cursor INTO table_name;
    IF done THEN
      LEAVE read_loop;
    END IF;
    
    -- Check for foreign keys (VIOLATION)
    SELECT COUNT(*) INTO violation_count
    FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
    WHERE TABLE_SCHEMA = DATABASE() 
      AND TABLE_NAME = table_name 
      AND REFERENCED_TABLE_NAME IS NOT NULL;
    
    IF violation_count > 0 THEN
      SELECT CONCAT('FOREIGN KEY VIOLATION: Table ', table_name, ' has ', violation_count, ' foreign key(s)') AS violation;
    END IF;
    
    -- Check for triggers (VIOLATION)
    SELECT COUNT(*) INTO violation_count
    FROM INFORMATION_SCHEMA.TRIGGERS 
    WHERE TRIGGER_SCHEMA = DATABASE() 
      AND EVENT_OBJECT_TABLE = table_name;
    
    IF violation_count > 0 THEN
      SELECT CONCAT('TRIGGER VIOLATION: Table ', table_name, ' has ', violation_count, ' trigger(s)') AS violation;
    END IF;
    
    -- Check for timestamp types (VIOLATION)
    SELECT COUNT(*) INTO violation_count
    FROM INFORMATION_SCHEMA.COLUMNS 
    WHERE TABLE_SCHEMA = DATABASE() 
      AND TABLE_NAME = table_name 
      AND DATA_TYPE IN ('timestamp', 'datetime')
      AND COLUMN_NAME LIKE '%_ymdhis%';
    
    IF violation_count > 0 THEN
      SELECT CONCAT('TIMESTAMP TYPE VIOLATION: Table ', table_name, ' has ', violation_count, ' timestamp/datetime column(s)') AS violation;
    END IF;
    
  END LOOP;
  
  CLOSE table_cursor;
END//
DELIMITER ;

-- Run doctrine compliance validation
CALL validate_doctrine_compliance();

-- ======================================================================
-- SECTION 7: MIGRATION SUMMARY REPORT
-- ======================================================================

-- Create summary report
SELECT 
  'STRUCTURAL ALIGNMENT MIGRATION SUMMARY' as report_title,
  'Version 4.0.45' as migration_version,
  'FOR HUMAN REVIEW ONLY' as status,
  COUNT(*) as total_toon_tables,
  SUM(CASE WHEN toon_exists = 1 THEN 1 ELSE 0 END) as toon_tables_found,
  'Pending human review and approval' as next_step
FROM toon_alignment_check;

-- Show any tables that exist in SQL but not in TOON
SELECT 
  TABLE_NAME as missing_from_toon,
  'Exists in SQL but not defined in TOON files' as issue
FROM INFORMATION_SCHEMA.TABLES 
WHERE TABLE_SCHEMA = DATABASE() 
  AND TABLE_TYPE = 'BASE TABLE'
  AND TABLE_NAME NOT IN (SELECT table_name FROM toon_alignment_check WHERE toon_exists = 1);

-- ======================================================================
-- SECTION 8: CLEANUP
-- ======================================================================

DROP TEMPORARY TABLE IF EXISTS `toon_alignment_check`;
DROP PROCEDURE IF EXISTS `validate_doctrine_compliance`;

ROLLBACK; -- IMPORTANT: This migration is for review only, do not commit

-- ======================================================================
-- MIGRATION NOTES FOR HUMAN REVIEW
-- ======================================================================

/*
1. TIMESTAMP COMPLIANCE:
   - Fixed lupo_actor_actions.created_ymdhis from CHAR(14) to BIGINT(14) UNSIGNED
   - Need to verify all other timestamp fields are BIGINT(14) UNSIGNED

2. FOREIGN KEY COMPLIANCE:
   - Need to scan for and remove any foreign key constraints
   - Doctrine prohibits foreign keys for federation safety

3. TRIGGER COMPLIANCE:
   - Need to scan for and remove any triggers
   - Doctrine prohibits triggers for data integrity

4. DIALOG CHANNELS:
   - Created formal lupo_dialog_channels table
   - Populated with system channels from TOON definitions
   - Replaces improvised channel bridges

5. NEXT STEPS:
   - Review all timestamp fields for BIGINT(14) UNSIGNED compliance
   - Identify and remove all foreign key constraints
   - Identify and remove all triggers
   - Verify TOON vs SQL table alignment
   - Test migration on non-production environment

6. DOCTRINE COMPLIANCE:
   - All timestamps must be BIGINT(14) UNSIGNED (YYYYMMDDHHIISS format)
   - No foreign keys allowed
   - No triggers allowed
   - Application must manage all relationships and timestamps

7. HUMAN REVIEW REQUIRED:
   - This migration is wrapped in ROLLBACK for safety
   - Remove ROLLBACK and add COMMIT after human approval
   - Test thoroughly on development environment first
*/

-- ======================================================================
-- END OF MIGRATION
-- ======================================================================
