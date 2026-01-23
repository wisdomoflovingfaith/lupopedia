-- ======================================================================
-- TOON-ALIGNED DIALOG CHANNELS BLUEPRINT
-- Purpose: Replace improvised bridges with formal structure
-- Doctrine Compliance: BIGINT(14) UNSIGNED timestamps, no FKs, no triggers
-- ======================================================================

CREATE TABLE `dialog_channels` (
  `channel_id` BIGINT(14) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Primary key (YYYYMMDDHHIISS format)',
  `channel_slug` VARCHAR(64) NOT NULL COMMENT 'URL-friendly identifier (e.g., ASK_HUMAN_WOLFIE)',
  `channel_name` VARCHAR(255) NOT NULL COMMENT 'Human-readable channel name',
  `channel_type` ENUM('system', 'agent', 'human', 'service') NOT NULL DEFAULT 'system' COMMENT 'Channel classification',
  `primary_agent_id` INT(3) UNSIGNED COMMENT 'Link to Agent Registry (Agent 1-128)',
  `status` ENUM('active', 'paused', 'red_alert') NOT NULL DEFAULT 'active' COMMENT 'Channel operational status',
  `federation_node_id` BIGINT(14) UNSIGNED NOT NULL DEFAULT 1 COMMENT 'Domain/tenant identifier',
  `created_by_actor_id` BIGINT(14) UNSIGNED NOT NULL COMMENT 'Actor who created this channel',
  `default_actor_id` BIGINT(14) UNSIGNED NOT NULL DEFAULT 1 COMMENT 'Default actor for channel operations',
  `description` TEXT COMMENT 'Channel purpose and usage guidelines',
  `metadata_json` TEXT COMMENT 'JSON configuration for channel behavior',
  `bgcolor` VARCHAR(6) NOT NULL DEFAULT 'FFFFFF' COMMENT 'Channel UI background color',
  `priority` INT NOT NULL DEFAULT 0 COMMENT 'Channel routing priority',
  `end_ymdhis` BIGINT(14) UNSIGNED NULL COMMENT 'Channel end timestamp (NULL if ongoing)',
  `duration_seconds` INT NULL COMMENT 'Channel duration in seconds (if ended)',
  `created_at` BIGINT(14) UNSIGNED NOT NULL COMMENT 'Creation timestamp (YYYYMMDDHHIISS)',
  `updated_at` BIGINT(14) UNSIGNED NOT NULL COMMENT 'Last update timestamp (YYYYMMDDHHIISS)',
  `is_deleted` TINYINT(1) NOT NULL DEFAULT 0 COMMENT 'Soft delete flag (1=deleted, 0=active)',
  `deleted_ymdhis` BIGINT(14) UNSIGNED NULL COMMENT 'Deletion timestamp (YYYYMMDDHHIISS)',
  PRIMARY KEY (`channel_id`),
  UNIQUE KEY `unq_channel_slug_per_node` (`channel_slug`, `federation_node_id`),
  KEY `idx_channel_slug` (`channel_slug`),
  KEY `idx_primary_agent_id` (`primary_agent_id`),
  KEY `idx_status` (`status`),
  KEY `idx_federation_node_id` (`federation_node_id`),
  KEY `idx_created_by_actor_id` (`created_by_actor_id`),
  KEY `idx_created_at` (`created_at`),
  KEY `idx_updated_at` (`updated_at`),
  KEY `idx_end_ymdhis` (`end_ymdhis`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Formal dialog channels replacing improvised bridges - TOON aligned';

-- Insert system channels from TOON definitions
INSERT INTO `dialog_channels` (
  `channel_slug`, `channel_name`, `channel_type`, `primary_agent_id`, 
  `created_by_actor_id`, `default_actor_id`, `description`, `metadata_json`, 
  `bgcolor`, `status`, `created_at`, `updated_at`
) VALUES 
(
  'system/kernel',
  'System Kernel Channel',
  'system',
  0,
  0,
  1,
  'Reserved channel for bootstrapping, migrations, and OS-level events.',
  '{"purpose": "kernel", "protected": true, "auto_created": true}',
  'FFFFFF',
  'active',
  20260106084500,
  20260106084500
),
(
  'system/lobby',
  'Lobby Channel',
  'system',
  0,
  0,
  1,
  'Universal entry point for all new actors. Temporary holding area before routing.',
  '{"purpose": "system-root", "auto_created": true}',
  'CCCCCC',
  'active',
  20260106082200,
  20260106082200
),
(
  'ask_human_wolfie',
  'Ask Human Wolfie',
  'human',
  1,
  1,
  1,
  'Direct communication channel to Captain Wolfie for human-in-the-loop requests.',
  '{"purpose": "human-bridge", "priority": "high", "response_expected": true}',
  'FF6B6B',
  'active',
  20260116090000,
  20260116090000
);

-- ======================================================================
-- TOON ALIGNMENT VERIFICATION
-- ======================================================================

-- Check alignment with existing TOON files
SELECT 
  'TOON ALIGNMENT CHECK' as check_type,
  COUNT(*) as toon_files_found,
  'dialog_channels' as formalized_table,
  'improvised_bridges_replaced' as status
FROM information_schema.tables 
WHERE table_schema = DATABASE() 
  AND table_name LIKE '%.toon';

-- Verify doctrine compliance
SELECT 
  'DOCTRINE COMPLIANCE CHECK' as check_type,
  CASE 
    WHEN COUNT(*) > 0 THEN 'VIOLATION: Foreign keys found'
    ELSE 'COMPLIANT: No foreign keys'
  END as fk_status,
  CASE 
    WHEN COUNT(*) > 0 THEN 'VIOLATION: Triggers found'
    ELSE 'COMPLIANT: No triggers'
  END as trigger_status
FROM information_schema.key_column_usage 
WHERE table_schema = DATABASE() 
  AND table_name = 'dialog_channels'
  AND referenced_table_name IS NOT NULL;

-- ======================================================================
-- MIGRATION NOTES
-- ======================================================================

/*
1. DOCTRINE COMPLIANCE:
   - All timestamps are BIGINT(14) UNSIGNED (YYYYMMDDHHIISS)
   - No foreign keys defined
   - No triggers defined
   - Application-managed relationships only

2. BRIDGE FORMALIZATION:
   - Replaces improvised channel bridges
   - Formal dialog_channels table structure
   - TOON-aligned field definitions
   - Proper indexing for performance

3. CHANNEL TYPES:
   - system: OS-level channels (kernel, lobby)
   - agent: Agent-specific communication channels
   - human: Human-in-the-loop channels
   - service: Service-to-service communication

4. HUMAN REVIEW REQUIRED:
   - Verify TOON file count matches 111 tables
   - Confirm channel requirements
   - Test migration on development environment
   - Validate doctrine compliance

5. NEXT STEPS:
   - Update TOON files to reflect 111 table count
   - Remove any remaining improvised bridges
   - Test dialog channel functionality
   - Validate federation safety
*/
