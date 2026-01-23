-- ============================================================================
-- Crafty Syntax 3.8.0 - Database Migration
-- ============================================================================
-- Migration: 0002 (REVISED - Simplified DNA System)
-- Description: Create livehelp_dna table for Agent DNA System
-- Date: 2025-11-19
-- Author: Captain WOLFIE (Eric Robin Gerdes)
-- ============================================================================
-- PURPOSE:
-- - Create livehelp_dna table for all DNA metadata
-- - Create livehelp_dna_logs table for DNA change tracking
-- - Create livehelp_dna_collections table for DNA collections
-- - Create livehelp_dna_tags table for DNA tags
-- - Simplified from 16 tables (4 base + 12 associated) to 4 tables
-- - DNA string format: channel-agent_name-DNA_bases (e.g., 007-captain-ACGT)
-- - Each DNA base (A, T, C, G) stored as separate row with metadata JSON field
-- ============================================================================
-- DNA STRING FORMAT:
-- Format: [channel]-[agent_name]-[sequence]
-- Example: "007-captain-ACGT 001-unknown-TTAA"
-- 
-- When parsing "007-captain-ACGT":
-- - channel_id = 7
-- - agent_name = 'captain'
-- - DNA_bases = 'ACGT' (each character is a base identifier)
-- 
-- Query for each base in sequence:
-- - For "A": SELECT * FROM livehelp_dna WHERE channel_id = 7 AND agent_name = 'captain' AND dna_base = 'A'
-- - For "C": SELECT * FROM livehelp_dna WHERE channel_id = 7 AND agent_name = 'captain' AND dna_base = 'C'
-- - For "G": SELECT * FROM livehelp_dna WHERE channel_id = 7 AND agent_name = 'captain' AND dna_base = 'G'
-- - For "T": SELECT * FROM livehelp_dna WHERE channel_id = 7 AND agent_name = 'captain' AND dna_base = 'T'
-- 
-- Each row contains metadata for ONE base (A, T, C, or G) in that channel/agent context.
-- The metadata JSON field contains interpretation of that specific base:
-- {
--   "action_type": "execute",
--   "priority": 85,
--   "description": "Execute action on channel"
-- }
-- ============================================================================

-- ============================================================================
-- STEP 1: Create livehelp_dna Table
-- ============================================================================

CREATE TABLE IF NOT EXISTS `livehelp_dna` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `livehelp_id` BIGINT(20) UNSIGNED NOT NULL DEFAULT 1 COMMENT 'Instance ID',
  `channel_id` BIGINT(20) UNSIGNED NOT NULL DEFAULT 1 COMMENT 'Channel ID (000-999) - CRITICAL: Context for metadata lookup',
  `agent_id` BIGINT(20) UNSIGNED NOT NULL DEFAULT 1 COMMENT 'Agent ID - CRITICAL: Which agent interprets this',
  `agent_name` VARCHAR(255) NOT NULL DEFAULT 'unknown' COMMENT 'Agent name - CRITICAL: Agent-specific interpretation',
  `dna_base` ENUM('A', 'T', 'C', 'G') NOT NULL COMMENT 'DNA base identifier (A=Action, T=Tactic, C=Context, G=Governance)',
  `is_active` TINYINT(1) NOT NULL DEFAULT 1 COMMENT 'Whether this DNA entry is active',
  `metadata` LONGTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'JSON metadata - interpretation of this DNA base for this channel/agent context',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` TIMESTAMP NULL DEFAULT NULL COMMENT 'Soft delete',
  PRIMARY KEY (`id`),
  KEY `livehelp_id` (`livehelp_id`),
  KEY `channel_id` (`channel_id`),
  KEY `agent_id` (`agent_id`),
  KEY `agent_name` (`agent_name`),
  KEY `dna_base` (`dna_base`),
  KEY `is_active` (`is_active`),
  KEY `idx_channel_agent_base` (`channel_id`, `agent_name`, `dna_base`) COMMENT 'CRITICAL: For DNA string lookup by base',
  KEY `idx_livehelp_channel_agent` (`livehelp_id`, `channel_id`, `agent_name`) COMMENT 'CRITICAL: Multi-instance DNA lookup',
  UNIQUE KEY `unique_dna_entry` (`livehelp_id`, `channel_id`, `agent_name`, `dna_base`) COMMENT 'CRITICAL: One entry per base per channel/agent'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================================
-- STEP 2: Example Metadata Structure
-- ============================================================================
-- Each row represents ONE DNA base (A, T, C, or G) for a specific channel/agent.
-- The metadata JSON field contains interpretation of that specific base:
-- 
-- Example row 1 (dna_base = 'A'):
-- {
--   "action_type": "execute",
--   "priority": 85,
--   "description": "Execute action on channel"
-- }
-- 
-- Example row 2 (dna_base = 'T'):
-- {
--   "tactic_type": "parallel",
--   "efficiency_score": 0.92,
--   "description": "Parallel processing tactic"
-- }
-- 
-- Example row 3 (dna_base = 'C'):
-- {
--   "context_type": "channel",
--   "scope": "operations",
--   "description": "Channel operations context"
-- }
-- 
-- Example row 4 (dna_base = 'G'):
-- {
--   "governance_type": "validation",
--   "rule_level": "moderate",
--   "description": "Moderate validation rules"
-- }
-- 
-- This allows the same DNA base (e.g., "A") to mean different things
-- based on channel_id and agent_name context (different rows, same base).
-- ============================================================================

-- ============================================================================
-- STEP 3: Create livehelp_dna_collections Table
-- ============================================================================

CREATE TABLE IF NOT EXISTS `livehelp_dna_collections` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `livehelp_id` BIGINT(20) UNSIGNED NOT NULL DEFAULT 1 COMMENT 'Instance ID',
  `channel_id` BIGINT(20) UNSIGNED NOT NULL DEFAULT 1 COMMENT 'Channel ID (000-999)',
  `agent_id` BIGINT(20) UNSIGNED NOT NULL DEFAULT 1 COMMENT 'Agent ID',
  `agent_name` VARCHAR(255) NOT NULL DEFAULT 'unknown' COMMENT 'Agent name',
  `dna_id` BIGINT(20) UNSIGNED NOT NULL DEFAULT 1 COMMENT 'Reference to livehelp_dna.id',
  `collection_name` VARCHAR(255) NOT NULL COMMENT 'Collection name',
  `collection_type` VARCHAR(100) DEFAULT NULL COMMENT 'Collection type',
  `description` TEXT DEFAULT NULL COMMENT 'Collection description',
  `metadata` LONGTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'JSON metadata',
  `is_active` TINYINT(1) NOT NULL DEFAULT 1 COMMENT 'Whether this collection is active',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` TIMESTAMP NULL DEFAULT NULL COMMENT 'Soft delete',
  PRIMARY KEY (`id`),
  KEY `livehelp_id` (`livehelp_id`),
  KEY `channel_id` (`channel_id`),
  KEY `agent_id` (`agent_id`),
  KEY `agent_name` (`agent_name`),
  KEY `dna_id` (`dna_id`),
  KEY `collection_name` (`collection_name`),
  KEY `is_active` (`is_active`),
  KEY `idx_channel_agent` (`channel_id`, `agent_name`),
  KEY `idx_livehelp_dna` (`livehelp_id`, `dna_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================================
-- STEP 3: Create livehelp_dna_logs Table
-- ============================================================================

CREATE TABLE IF NOT EXISTS `livehelp_dna_logs` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `livehelp_id` BIGINT(20) UNSIGNED NOT NULL DEFAULT 1 COMMENT 'Instance ID',
  `channel_id` BIGINT(20) UNSIGNED NOT NULL DEFAULT 1 COMMENT 'Channel ID (000-999)',
  `agent_id` BIGINT(20) UNSIGNED NOT NULL DEFAULT 1 COMMENT 'Agent ID that made the change',
  `agent_name` VARCHAR(255) NOT NULL DEFAULT 'unknown' COMMENT 'Agent name (denormalized)',
  `dna_id` BIGINT(20) UNSIGNED NOT NULL DEFAULT 1 COMMENT 'Reference to livehelp_dna.id',
  `metadata` LONGTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'Change metadata JSON',
  `is_active` TINYINT(1) NOT NULL DEFAULT 1 COMMENT 'Whether this log entry is active',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` TIMESTAMP NULL DEFAULT NULL COMMENT 'Soft delete',
  PRIMARY KEY (`id`),
  KEY `livehelp_id` (`livehelp_id`),
  KEY `channel_id` (`channel_id`),
  KEY `agent_id` (`agent_id`),
  KEY `agent_name` (`agent_name`),
  KEY `dna_id` (`dna_id`),
  KEY `is_active` (`is_active`),
  KEY `idx_channel_agent` (`channel_id`, `agent_name`),
  KEY `idx_livehelp_dna` (`livehelp_id`, `dna_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================================
-- STEP 4: Create livehelp_dna_collections Table
-- ============================================================================

CREATE TABLE IF NOT EXISTS `livehelp_dna_tags` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `livehelp_id` BIGINT(20) UNSIGNED NOT NULL DEFAULT 1 COMMENT 'Instance ID',
  `channel_id` BIGINT(20) UNSIGNED NOT NULL DEFAULT 1 COMMENT 'Channel ID (000-999)',
  `agent_id` BIGINT(20) UNSIGNED NOT NULL DEFAULT 1 COMMENT 'Agent ID',
  `agent_name` VARCHAR(255) NOT NULL DEFAULT 'unknown' COMMENT 'Agent name',
  `dna_id` BIGINT(20) UNSIGNED NOT NULL DEFAULT 1 COMMENT 'Reference to livehelp_dna.id',
  `tag_name` VARCHAR(255) NOT NULL COMMENT 'Tag name',
  `tag_type` VARCHAR(100) DEFAULT NULL COMMENT 'Tag type',
  `description` TEXT DEFAULT NULL COMMENT 'Tag description',
  `metadata` LONGTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'JSON metadata',
  `is_active` TINYINT(1) NOT NULL DEFAULT 1 COMMENT 'Whether this tag is active',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` TIMESTAMP NULL DEFAULT NULL COMMENT 'Soft delete',
  PRIMARY KEY (`id`),
  KEY `livehelp_id` (`livehelp_id`),
  KEY `channel_id` (`channel_id`),
  KEY `agent_id` (`agent_id`),
  KEY `agent_name` (`agent_name`),
  KEY `dna_id` (`dna_id`),
  KEY `tag_name` (`tag_name`),
  KEY `is_active` (`is_active`),
  KEY `idx_channel_agent` (`channel_id`, `agent_name`),
  KEY `idx_livehelp_dna` (`livehelp_id`, `dna_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================================
-- MIGRATION COMPLETE
-- ============================================================================
-- 
-- Verification:
-- 1. Check that 'livehelp_dna' table exists
-- 2. Check that 'livehelp_dna_logs' table exists
-- 3. Check that 'livehelp_dna_collections' table exists
-- 4. Check that 'livehelp_dna_tags' table exists
-- 5. Verify all indexes are created
-- 5. Test queries with channel_id, agent_name, and dna_base filters
-- 6. Test DNA string parsing: "007-captain-ACGT"
--    - Parse: channel_id=7, agent_name='captain', sequence='ACGT'
--    - For each base in sequence:
--      - Query: SELECT * FROM livehelp_dna WHERE channel_id=7 AND agent_name='captain' AND dna_base='A'
--      - Query: SELECT * FROM livehelp_dna WHERE channel_id=7 AND agent_name='captain' AND dna_base='C'
--      - Query: SELECT * FROM livehelp_dna WHERE channel_id=7 AND agent_name='captain' AND dna_base='G'
--      - Query: SELECT * FROM livehelp_dna WHERE channel_id=7 AND agent_name='captain' AND dna_base='T'
--    - Use metadata JSON from each row to interpret that specific base
-- 7. Test logs: SELECT * FROM livehelp_dna_logs WHERE dna_id = [dna_id]
-- 8. Test collections: SELECT * FROM livehelp_dna_collections WHERE dna_id = [dna_id]
-- 9. Test tags: SELECT * FROM livehelp_dna_tags WHERE dna_id = [dna_id]
-- 
-- ============================================================================

