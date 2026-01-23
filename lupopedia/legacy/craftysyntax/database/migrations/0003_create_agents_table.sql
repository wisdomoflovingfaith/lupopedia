-- ============================================================================
-- Crafty Syntax 3.8.0 - Database Migration
-- ============================================================================
-- Migration: 0003
-- Description: Create livehelp_agents core system table
-- Date: 2025-11-18
-- Author: Captain WOLFIE (Eric Robin Gerdes)
-- ============================================================================
-- PURPOSE:
-- - Create livehelp_agents table for agent definitions
-- - Enable LUPOPEDIA Platform agent coordination
-- - Agent ID directly maps to channel number (000-999)
-- - Supports DNA strings, capabilities, and metadata
-- - Biological-inspired system: agents have a genome (dna_sequence) that determines behavior
-- - Supports evolution: generation, fitness_score, birth_date for reproduction/mutation
-- ============================================================================
-- CRITICAL: Agent ID = Channel Number (direct mapping, no lookup tables)
-- This "brittleness" is intentional for clarity and performance
-- ============================================================================

-- ============================================================================
-- STEP 1: Create livehelp_agents Table
-- ============================================================================

CREATE TABLE IF NOT EXISTS `livehelp_agents` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `livehelp_id` BIGINT(20) UNSIGNED NOT NULL DEFAULT 1 COMMENT 'Instance ID',
  `agent_id` BIGINT(20) UNSIGNED NOT NULL COMMENT 'Agent ID (000-999) - CRITICAL: Direct mapping to channel number',
  `agent_name` VARCHAR(255) NOT NULL COMMENT 'Agent name (e.g., WOLFIE, CAPTAIN, SECURITY, HELP)',
  `channel_id` BIGINT(20) UNSIGNED NOT NULL COMMENT 'Primary channel ID (Agent ID = Channel Number)',
  `agent_type` ENUM('primary', 'secondary', 'coordinator', 'specialized') DEFAULT 'primary' COMMENT 'Agent type',
  `status` ENUM('active', 'inactive', 'maintenance') DEFAULT 'active' COMMENT 'Agent status',
  `dna_string` VARCHAR(255) DEFAULT NULL COMMENT 'Agent DNA string format: channel-agent_name-DNA_bases (e.g., 007-unknown-ATCG 001-unknown-TTAA)',
  `dna_sequence` TEXT DEFAULT NULL COMMENT 'Full genetic code - biological-inspired genome (e.g., "ATGCAGCTAGCT..." or JSON gene-tagged format like "GREET:friendly|HUMOR:80|SPEED:96")',
  `generation` INT UNSIGNED NOT NULL DEFAULT 1 COMMENT 'Evolution generation (increments on reproduction/mutation)',
  `birth_date` DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT 'When this agent was created/born',
  `fitness_score` DECIMAL(8,4) DEFAULT 0.0000 COMMENT 'Performance score - how well this agent performs (0.0000-9999.9999)',
  `capabilities` LONGTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'JSON capabilities',
  `metadata` LONGTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'JSON metadata',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` TIMESTAMP NULL DEFAULT NULL COMMENT 'Soft delete',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_agent_instance` (`livehelp_id`, `agent_id`) COMMENT 'CRITICAL: One agent per instance',
  KEY `livehelp_id` (`livehelp_id`),
  KEY `agent_id` (`agent_id`),
  KEY `agent_name` (`agent_name`),
  KEY `channel_id` (`channel_id`),
  KEY `status` (`status`),
  KEY `generation` (`generation`),
  KEY `fitness_score` (`fitness_score`),
  KEY `idx_livehelp_agent` (`livehelp_id`, `agent_id`) COMMENT 'CRITICAL: For multi-instance queries',
  KEY `idx_channel_agent` (`channel_id`, `agent_id`) COMMENT 'CRITICAL: For channel-agent lookup'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================================
-- STEP 2: Insert Default Agent (Optional - for testing/initialization)
-- ============================================================================
-- Note: This is optional. Agents should be created through the application.
-- Uncomment if you want a default agent for testing:

-- INSERT INTO `livehelp_agents` (`livehelp_id`, `agent_id`, `agent_name`, `channel_id`, `agent_type`, `status`)
-- VALUES (1, 1, 'DEFAULT', 1, 'primary', 'active')
-- ON DUPLICATE KEY UPDATE `agent_name` = 'DEFAULT', `status` = 'active';

-- ============================================================================
-- MIGRATION COMPLETE
-- ============================================================================
-- 
-- Verification:
-- 1. Check that 'livehelp_agents' table exists
-- 2. Verify unique constraint on (livehelp_id, agent_id)
-- 3. Verify all indexes are created
-- 4. Test queries with livehelp_id filter
-- 5. Test channel-agent lookup queries
--
-- ============================================================================

