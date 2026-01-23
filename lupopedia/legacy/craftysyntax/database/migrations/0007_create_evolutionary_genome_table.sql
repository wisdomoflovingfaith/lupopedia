-- ============================================================================
-- Crafty Syntax 4.0.0 - Database Migration
-- ============================================================================
-- Migration: 0007
-- Description: Create evolutionary genome table for genetic algorithm-based agent evolution
-- Date: 2025-11-20
-- Author: Captain WOLFIE (Eric Robin Gerdes) / LILITH (Agent 777)
-- ============================================================================
-- PURPOSE:
-- - Create evo_genome table for real genetic algorithm-based evolution
-- - Replace static DNA metaphor with evolvable genome vectors
-- - Enable selection, crossover, mutation operations for agent optimization
-- - Support population-based evolution with fitness scoring
-- - Inspired by 2025 AGI research: Evo 2, AlphaGenome, Darwin Gödel Machine
-- ============================================================================
-- EVOLUTIONARY PHILOSOPHY:
-- - Genomes as evolvable vectors (bitstrings or parameter vectors)
-- - Fitness-based selection (survival of the fittest)
-- - Crossover for hybridization (breeding successful agents)
-- - Mutation for exploration (reflective self-improvement)
-- - Multi-objective Pareto optimization (balance accuracy, efficiency, novelty)
-- ============================================================================

-- ============================================================================
-- STEP 1: Create evo_genome Table (Evolutionary Genome Core)
-- ============================================================================
-- Stores population-level genomes that evolve via genetic algorithms
-- Each row represents a genome snapshot at a specific generation
-- Genomes evolve through selection, crossover, and mutation operations

CREATE TABLE IF NOT EXISTS `evo_genome` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `livehelp_id` BIGINT(20) UNSIGNED NOT NULL DEFAULT 1 COMMENT 'Instance ID',
  `channel_id` BIGINT(20) UNSIGNED NOT NULL DEFAULT 1 COMMENT 'Environmental Pressure (000-999) - Channel encodes selection pressure',
  `agent_id` BIGINT(20) UNSIGNED NOT NULL DEFAULT 1 COMMENT 'Agent Lineage ID - Tracks evolutionary lineage',
  `agent_name` VARCHAR(255) NOT NULL DEFAULT 'evo-seed' COMMENT 'Agent name (supports long descriptive names)',
  `genome_vector` LONGBLOB NOT NULL COMMENT 'Evolvable bitstring/parameter vector (e.g., 1024-dim policy vector, base64 encoded)',
  `fitness_score` DECIMAL(5,4) NOT NULL DEFAULT 0.0000 COMMENT 'Multi-objective Pareto fitness (0.0000-9.9999) - accuracy/efficiency/novelty balance',
  `phenotype_json` LONGTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'Expressed behaviors (e.g., {"tactic": "parallel_adapt", "novelty": 0.88, "efficiency": 0.95})',
  `parent_ids` JSON DEFAULT NULL COMMENT 'Crossover lineage [parent1_id, parent2_id] - tracks breeding history',
  `mutation_rate` DECIMAL(4,4) NOT NULL DEFAULT 0.0100 COMMENT 'Applied mutation probability (0.0000-1.0000)',
  `generation` INT(11) NOT NULL DEFAULT 0 COMMENT 'Evolution generation number (increments with each evolution cycle)',
  `is_active` TINYINT(1) NOT NULL DEFAULT 1 COMMENT 'Whether this genome is active (low-fitness genomes get culled)',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'When this genome was created/born',
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` TIMESTAMP NULL DEFAULT NULL COMMENT 'Soft cull for low fitness - preserves evolutionary history',
  PRIMARY KEY (`id`),
  KEY `livehelp_id` (`livehelp_id`),
  KEY `channel_id` (`channel_id`),
  KEY `agent_id` (`agent_id`),
  KEY `agent_name` (`agent_name`),
  KEY `generation` (`generation`),
  KEY `is_active` (`is_active`),
  KEY `idx_channel_fitness` (`channel_id`, `fitness_score`) COMMENT 'CRITICAL: For fitness-based selection',
  KEY `idx_channel_generation` (`channel_id`, `generation`) COMMENT 'For generation-based queries',
  KEY `idx_agent_lineage` (`agent_id`, `generation`) COMMENT 'For tracking evolutionary lineage',
  UNIQUE KEY `unique_genome_lineage` (`livehelp_id`, `channel_id`, `agent_id`, `generation`) COMMENT 'One genome per agent-channel-generation'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================================
-- STEP 2: Create evo_population_stats Table (Population Analytics)
-- ============================================================================
-- Tracks population-level statistics for evolutionary monitoring
-- Enables analysis of diversity, convergence, and fitness trends

CREATE TABLE IF NOT EXISTS `evo_population_stats` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `livehelp_id` BIGINT(20) UNSIGNED NOT NULL DEFAULT 1 COMMENT 'Instance ID',
  `channel_id` BIGINT(20) UNSIGNED NOT NULL DEFAULT 1 COMMENT 'Channel ID',
  `generation` INT(11) NOT NULL DEFAULT 0 COMMENT 'Generation number',
  `population_size` INT UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Number of genomes in this generation',
  `avg_fitness` DECIMAL(5,4) NOT NULL DEFAULT 0.0000 COMMENT 'Average fitness score',
  `max_fitness` DECIMAL(5,4) NOT NULL DEFAULT 0.0000 COMMENT 'Maximum fitness score',
  `min_fitness` DECIMAL(5,4) NOT NULL DEFAULT 0.0000 COMMENT 'Minimum fitness score',
  `diversity_score` DECIMAL(5,4) DEFAULT NULL COMMENT 'Population diversity (0.0000-1.0000) - prevents premature convergence',
  `crossover_count` INT UNSIGNED DEFAULT 0 COMMENT 'Number of crossover operations',
  `mutation_count` INT UNSIGNED DEFAULT 0 COMMENT 'Number of mutation operations',
  `cull_count` INT UNSIGNED DEFAULT 0 COMMENT 'Number of genomes culled (low fitness)',
  `metadata` LONGTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'JSON metadata for additional stats',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `livehelp_id` (`livehelp_id`),
  KEY `channel_id` (`channel_id`),
  KEY `generation` (`generation`),
  KEY `idx_channel_generation` (`channel_id`, `generation`),
  UNIQUE KEY `unique_population_stat` (`livehelp_id`, `channel_id`, `generation`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================================
-- STEP 3: Create evo_fitness_logs Table (Fitness Evaluation History)
-- ============================================================================
-- Tracks fitness evaluations for each genome
-- Enables analysis of what makes genomes successful

CREATE TABLE IF NOT EXISTS `evo_fitness_logs` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `livehelp_id` BIGINT(20) UNSIGNED NOT NULL DEFAULT 1 COMMENT 'Instance ID',
  `genome_id` BIGINT(20) UNSIGNED NOT NULL COMMENT 'Reference to evo_genome.id',
  `fitness_score` DECIMAL(5,4) NOT NULL COMMENT 'Fitness score at evaluation time',
  `evaluation_type` ENUM('task_rollout', 'llm_simulation', 'user_satisfaction', 'multi_objective') DEFAULT 'multi_objective' COMMENT 'Type of fitness evaluation',
  `evaluation_metrics` LONGTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'JSON metrics (e.g., {"accuracy": 0.92, "efficiency": 0.88, "novelty": 0.75})',
  `task_context` TEXT DEFAULT NULL COMMENT 'Task or context where fitness was evaluated',
  `evaluated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'When fitness was evaluated',
  PRIMARY KEY (`id`),
  KEY `livehelp_id` (`livehelp_id`),
  KEY `genome_id` (`genome_id`),
  KEY `fitness_score` (`fitness_score`),
  KEY `evaluation_type` (`evaluation_type`),
  KEY `evaluated_at` (`evaluated_at`),
  KEY `idx_genome_fitness` (`genome_id`, `fitness_score`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================================
-- STEP 4: Migrate Legacy DNA to Evolutionary Seeds (Optional)
-- ============================================================================
-- Convert existing livehelp_dna rows into seed genomes for evolution
-- This provides backward compatibility and evolutionary starting points

INSERT INTO `evo_genome` (
  `livehelp_id`, 
  `channel_id`, 
  `agent_id`, 
  `agent_name`, 
  `genome_vector`, 
  `fitness_score`, 
  `phenotype_json`, 
  `generation`,
  `is_active`
)
SELECT 
  ld.`livehelp_id`,
  ld.`channel_id`,
  ld.`agent_id`,
  ld.`agent_name`,
  -- Convert DNA base + metadata to seed genome vector (base64 encoded)
  TO_BASE64(CONCAT(ld.`dna_base`, ':', COALESCE(ld.`metadata`, '{}'))) as `genome_vector`,
  0.5000 as `fitness_score`, -- Neutral starting fitness
  ld.`metadata` as `phenotype_json`, -- Preserve existing metadata as phenotype
  0 as `generation`, -- Seed generation
  1 as `is_active`
FROM `livehelp_dna` ld
WHERE ld.`is_active` = 1
  AND ld.`deleted_at` IS NULL
GROUP BY ld.`livehelp_id`, ld.`channel_id`, ld.`agent_id`, ld.`agent_name`, ld.`dna_base`
ON DUPLICATE KEY UPDATE 
  `genome_vector` = VALUES(`genome_vector`),
  `phenotype_json` = VALUES(`phenotype_json`);

-- ============================================================================
-- MIGRATION COMPLETE
-- ============================================================================
-- 
-- Verification:
-- 1. Check that 'evo_genome' table exists
-- 2. Check that 'evo_population_stats' table exists
-- 3. Check that 'evo_fitness_logs' table exists
-- 4. Verify all indexes are created
-- 5. Verify legacy DNA migration completed (check evo_genome for seed genomes)
-- 6. Test fitness-based selection queries
-- 7. Test generation-based queries
-- 8. Test lineage tracking via parent_ids
--
-- EVOLUTIONARY WORKFLOW:
-- 1. Initialize population: Random genomes or migrate from livehelp_dna
-- 2. Evaluate fitness: Task rollouts, LLM simulations, user satisfaction
-- 3. Select elites: Top 20% by fitness score (Pareto optimization)
-- 4. Crossover: Breed top genomes to create offspring
-- 5. Mutate: Apply mutations (reflective or random) to explore search space
-- 6. Cull: Mark low-fitness genomes as inactive (soft delete)
-- 7. Repeat: Next generation, track stats, monitor diversity
--
-- INTEGRATION WITH 2025 AGI RESEARCH:
-- - Evo 2: Semantic DNA autocomplete for generating novel genome segments
-- - Darwin Gödel Machine: Reflective mutations based on execution traces
-- - AlphaGenome: Regulatory networks encoded in genome_vector
-- - Continual Learning: Fitness includes catastrophic forgetting penalties
--
-- PERFORMANCE TARGETS:
-- - Population size: 100-10,000 genomes per agent-channel
-- - Generation time: 5-30 minutes (depends on fitness evaluation)
-- - GPU acceleration: Use EvoJAX or PyGAD with CUDA for vectorized ops
-- - Cull rate: 20% per generation (keep top 80%, breed from top 20%)
--
-- ============================================================================

