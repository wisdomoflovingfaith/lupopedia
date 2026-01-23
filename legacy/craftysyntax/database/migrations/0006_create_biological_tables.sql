-- ============================================================================
-- Crafty Syntax 3.8.0 - Database Migration
-- ============================================================================
-- Migration: 0006
-- Description: Create biological-inspired tables for agent genome system
-- Date: 2025-11-20
-- Author: Captain WOLFIE (Eric Robin Gerdes)
-- ============================================================================
-- PURPOSE:
-- - Create biological-inspired live-help system where agents have a "genome"
-- - Agents literally have DNA that determines behavior, personality, capabilities
-- - Enables evolution, reproduction, and mutation of agents
-- - Maps real molecular biology to computer biology model
-- ============================================================================
-- BIOLOGICAL MAPPING:
-- - DNA → Agent Genome (livehelp_agents.dna_sequence)
-- - Genes → Functional units (livehelp_genes)
-- - mRNA → Temporary working copy (livehelp_transcripts)
-- - Proteins → Actual functional molecules/behaviors (livehelp_proteins)
-- - Mutations → Evolution log (livehelp_mutations)
-- ============================================================================

-- ============================================================================
-- STEP 1: Create livehelp_genes Table (Gene Annotation Database)
-- ============================================================================
-- Defines what each gene does - the "gene annotation database"
-- Each gene represents a functional unit in the DNA sequence

CREATE TABLE IF NOT EXISTS `livehelp_genes` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `livehelp_id` BIGINT(20) UNSIGNED NOT NULL DEFAULT 1 COMMENT 'Instance ID',
  `gene_id` VARCHAR(50) NOT NULL COMMENT 'Gene identifier (e.g., RESP_SPEED, EMPATHY, HUMOR, TYPING_WPM)',
  `description` VARCHAR(255) DEFAULT NULL COMMENT 'What this gene controls',
  `locus_start` INT UNSIGNED DEFAULT NULL COMMENT 'Position in DNA sequence where gene starts',
  `locus_end` INT UNSIGNED DEFAULT NULL COMMENT 'Position in DNA sequence where gene ends',
  `default_allele` VARCHAR(100) DEFAULT NULL COMMENT 'Fallback value if gene missing/mutated',
  `value_type` ENUM('int','float','enum','string','json') DEFAULT 'float' COMMENT 'Data type for gene value',
  `min_value` FLOAT DEFAULT 0 COMMENT 'Minimum allowed value',
  `max_value` FLOAT DEFAULT 100 COMMENT 'Maximum allowed value',
  `is_active` TINYINT(1) NOT NULL DEFAULT 1 COMMENT 'Whether this gene definition is active',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` TIMESTAMP NULL DEFAULT NULL COMMENT 'Soft delete',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_gene_instance` (`livehelp_id`, `gene_id`) COMMENT 'One gene definition per instance',
  KEY `livehelp_id` (`livehelp_id`),
  KEY `gene_id` (`gene_id`),
  KEY `is_active` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================================
-- STEP 2: Create livehelp_transcripts Table (mRNA - Active Gene Expression)
-- ============================================================================
-- Temporary working copy of a gene - what genes are currently expressed
-- This is the "mRNA" equivalent - active expression of a gene at a given moment

CREATE TABLE IF NOT EXISTS `livehelp_transcripts` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `livehelp_id` BIGINT(20) UNSIGNED NOT NULL DEFAULT 1 COMMENT 'Instance ID',
  `agent_id` BIGINT(20) UNSIGNED NOT NULL COMMENT 'Agent ID - which agent this transcript belongs to',
  `gene_id` VARCHAR(50) NOT NULL COMMENT 'Gene identifier being expressed',
  `expressed_value` VARCHAR(255) DEFAULT NULL COMMENT 'The decoded/expressed value right now',
  `expressed_at` DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT 'When this gene was expressed',
  `expires_at` DATETIME NULL DEFAULT NULL COMMENT 'When this expression expires (some traits are temporary, e.g., caffeine boost)',
  `is_active` TINYINT(1) NOT NULL DEFAULT 1 COMMENT 'Whether this transcript is currently active',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` TIMESTAMP NULL DEFAULT NULL COMMENT 'Soft delete',
  PRIMARY KEY (`id`),
  KEY `livehelp_id` (`livehelp_id`),
  KEY `agent_id` (`agent_id`),
  KEY `gene_id` (`gene_id`),
  KEY `is_active` (`is_active`),
  KEY `expires_at` (`expires_at`),
  KEY `idx_agent_gene` (`agent_id`, `gene_id`) COMMENT 'For agent-gene lookup',
  KEY `idx_livehelp_agent` (`livehelp_id`, `agent_id`) COMMENT 'For multi-instance queries'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================================
-- STEP 3: Create livehelp_proteins Table (Actual Functional Molecules)
-- ============================================================================
-- Real behaviors/skills/personality traits that actually run
-- This is what your chat code reads to determine agent behavior
-- The "ribosome" (PHP engine) reads transcripts → proteins

CREATE TABLE IF NOT EXISTS `livehelp_proteins` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `livehelp_id` BIGINT(20) UNSIGNED NOT NULL DEFAULT 1 COMMENT 'Instance ID',
  `agent_id` BIGINT(20) UNSIGNED NOT NULL COMMENT 'Agent ID - which agent this protein belongs to',
  `response_speed_ms` INT UNSIGNED DEFAULT 800 COMMENT 'Response speed in milliseconds (faster = better)',
  `empathy_level` FLOAT DEFAULT 50.0 COMMENT 'Empathy level (0-100)',
  `humor_level` FLOAT DEFAULT 30.0 COMMENT 'Humor level (0-100)',
  `typing_wpm` INT UNSIGNED DEFAULT 65 COMMENT 'Typing speed in words per minute',
  `greeting_style` ENUM('formal','friendly','witty','minimal','custom') DEFAULT 'friendly' COMMENT 'Greeting style',
  `max_concurrent_chats` INT UNSIGNED DEFAULT 4 COMMENT 'Maximum concurrent chat sessions',
  `language_fluency` LONGTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'JSON language fluency (e.g., {"en":100, "es":70, "fr":20})',
  `specialty_tags` LONGTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'JSON specialty tags (e.g., ["tech", "billing", "support"])',
  `mood` VARCHAR(50) DEFAULT 'neutral' COMMENT 'Current mood (affects tone)',
  `is_active` TINYINT(1) NOT NULL DEFAULT 1 COMMENT 'Whether this protein profile is active',
  `last_updated` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'When protein was last updated',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` TIMESTAMP NULL DEFAULT NULL COMMENT 'Soft delete',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_agent_protein` (`livehelp_id`, `agent_id`) COMMENT 'One protein profile per agent per instance',
  KEY `livehelp_id` (`livehelp_id`),
  KEY `agent_id` (`agent_id`),
  KEY `is_active` (`is_active`),
  KEY `response_speed_ms` (`response_speed_ms`),
  KEY `idx_livehelp_agent` (`livehelp_id`, `agent_id`) COMMENT 'For multi-instance queries'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================================
-- STEP 4: Create livehelp_mutations Table (Evolution/Mutation Log)
-- ============================================================================
-- Tracks mutations and evolution of agents
-- Records DNA changes, fitness impacts, and evolution history

CREATE TABLE IF NOT EXISTS `livehelp_mutations` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `livehelp_id` BIGINT(20) UNSIGNED NOT NULL DEFAULT 1 COMMENT 'Instance ID',
  `agent_id` BIGINT(20) UNSIGNED NOT NULL COMMENT 'Agent ID that was mutated',
  `parent_agent_id` BIGINT(20) UNSIGNED DEFAULT NULL COMMENT 'Parent agent ID (if reproduction)',
  `mutation_type` ENUM('point','insertion','deletion','recombination','reproduction') DEFAULT 'point' COMMENT 'Type of mutation',
  `mutation_position` INT UNSIGNED DEFAULT NULL COMMENT 'Position in DNA sequence where mutation occurred',
  `old_base` VARCHAR(10) DEFAULT NULL COMMENT 'Old base pair(s) before mutation',
  `new_base` VARCHAR(10) DEFAULT NULL COMMENT 'New base pair(s) after mutation',
  `parent_dna` TEXT DEFAULT NULL COMMENT 'Parent DNA sequence before mutation',
  `new_dna` TEXT DEFAULT NULL COMMENT 'New DNA sequence after mutation',
  `fitness_before` DECIMAL(8,4) DEFAULT NULL COMMENT 'Fitness score before mutation',
  `fitness_after` DECIMAL(8,4) DEFAULT NULL COMMENT 'Fitness score after mutation',
  `fitness_change` DECIMAL(8,4) DEFAULT NULL COMMENT 'Change in fitness (positive = improvement)',
  `mutation_reason` VARCHAR(255) DEFAULT NULL COMMENT 'Reason for mutation (e.g., "high performance", "reproduction", "manual")',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'When mutation occurred',
  PRIMARY KEY (`id`),
  KEY `livehelp_id` (`livehelp_id`),
  KEY `agent_id` (`agent_id`),
  KEY `parent_agent_id` (`parent_agent_id`),
  KEY `mutation_type` (`mutation_type`),
  KEY `created_at` (`created_at`),
  KEY `idx_livehelp_agent` (`livehelp_id`, `agent_id`) COMMENT 'For multi-instance queries',
  KEY `idx_fitness_change` (`fitness_change`) COMMENT 'For tracking beneficial mutations'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================================
-- STEP 5: Insert Default Gene Definitions
-- ============================================================================
-- Insert common gene definitions that agents can use
-- These define what each gene controls in the DNA sequence

INSERT INTO `livehelp_genes` (`livehelp_id`, `gene_id`, `description`, `value_type`, `min_value`, `max_value`, `default_allele`) VALUES
(1, 'RESP_SPEED', 'Response speed in milliseconds (lower = faster)', 'int', 100, 5000, '800'),
(1, 'EMPATHY', 'Empathy level (0-100)', 'float', 0, 100, '50.0'),
(1, 'HUMOR', 'Humor level (0-100)', 'float', 0, 100, '30.0'),
(1, 'TYPING_WPM', 'Typing speed in words per minute', 'int', 20, 200, '65'),
(1, 'GREETING_STYLE', 'Greeting style preference', 'enum', 0, 0, 'friendly'),
(1, 'MAX_CONCURRENT', 'Maximum concurrent chat sessions', 'int', 1, 20, '4'),
(1, 'MOOD', 'Current mood affecting tone', 'string', 0, 0, 'neutral')
ON DUPLICATE KEY UPDATE `description` = VALUES(`description`);

-- ============================================================================
-- MIGRATION COMPLETE
-- ============================================================================
-- 
-- Verification:
-- 1. Check that all 4 biological tables exist:
--    - livehelp_genes (gene annotation database)
--    - livehelp_transcripts (active gene expression - mRNA)
--    - livehelp_proteins (actual behaviors/skills - functional molecules)
--    - livehelp_mutations (evolution/mutation log)
-- 2. Verify all indexes are created
-- 3. Verify default gene definitions are inserted
-- 4. Test queries with livehelp_id filter for multi-instance support
-- 5. Test agent-gene-protein relationships
--
-- BIOLOGICAL WORKFLOW:
-- 1. Agent logs in → PHP code runs "transcription + translation"
-- 2. Reads dna_sequence from livehelp_agents
-- 3. Interprets genome using livehelp_genes table
-- 4. Creates transcripts in livehelp_transcripts (active expressions)
-- 5. Updates livehelp_proteins (actual running traits)
-- 6. Chat engine reads from livehelp_proteins for behavior decisions
-- 7. Periodically: mutate DNA, reproduce high-fitness agents
--
-- ============================================================================

