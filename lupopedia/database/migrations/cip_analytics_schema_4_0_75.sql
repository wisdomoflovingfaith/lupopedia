-- CIP Analytics & Self-Correcting Architecture Schema
-- Implements CIP Analytics Engine, Doctrine Refinement Module, and Emotional Geometry Calibration
-- as defined in version 4.0.75
-- 
-- @package Lupopedia
-- @version 4.0.75
-- @author kiro (AI Assistant)

-- ============================================================================
-- CIP ANALYTICS ENGINE TABLES
-- ============================================================================

-- CIP Events Analytics Table
CREATE TABLE IF NOT EXISTS `lupo_cip_analytics` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `event_id` BIGINT UNSIGNED NOT NULL COMMENT 'Reference to lupo_cip_events.id',
  `defensiveness_index` DECIMAL(5,4) NOT NULL DEFAULT 0.0000 COMMENT 'DI: 0.0000-1.0000 scale',
  `integration_velocity` DECIMAL(5,4) NOT NULL DEFAULT 0.0000 COMMENT 'IV: 0.0000-1.0000 scale',
  `architectural_impact_score` DECIMAL(5,4) NOT NULL DEFAULT 0.0000 COMMENT 'AIS: 0.0000-1.0000 scale',
  `doctrine_propagation_depth` TINYINT UNSIGNED NOT NULL DEFAULT 0 COMMENT 'DPD: 0-10 depth levels',
  `critique_source_weight` DECIMAL(5,4) NOT NULL DEFAULT 0.5000 COMMENT 'Source credibility weight',
  `subsystem_impact_json` JSON DEFAULT NULL COMMENT 'Impact analysis per subsystem',
  `trend_analysis_json` JSON DEFAULT NULL COMMENT 'Historical trend data',
  `calculated_ymdhis` BIGINT NOT NULL COMMENT 'When analytics were calculated',
  `recalculated_ymdhis` BIGINT DEFAULT NULL COMMENT 'Last recalculation timestamp',
  `analytics_version` VARCHAR(20) DEFAULT '4.0.75' COMMENT 'Analytics engine version',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_event_analytics` (`event_id`),
  KEY `idx_defensiveness_index` (`defensiveness_index`),
  KEY `idx_integration_velocity` (`integration_velocity`),
  KEY `idx_architectural_impact` (`architectural_impact_score`),
  KEY `idx_calculated_time` (`calculated_ymdhis`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci 
COMMENT='CIP Analytics Engine - Aggregated metrics and trend analysis';

-- CIP Trends Aggregation Table
CREATE TABLE IF NOT EXISTS `lupo_cip_trends` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `trend_period` ENUM('hourly', 'daily', 'weekly', 'monthly') NOT NULL,
  `period_start_ymdhis` BIGINT NOT NULL COMMENT 'Start of aggregation period',
  `period_end_ymdhis` BIGINT NOT NULL COMMENT 'End of aggregation period',
  `avg_defensiveness_index` DECIMAL(5,4) NOT NULL DEFAULT 0.0000,
  `avg_integration_velocity` DECIMAL(5,4) NOT NULL DEFAULT 0.0000,
  `avg_architectural_impact` DECIMAL(5,4) NOT NULL DEFAULT 0.0000,
  `total_events` INT UNSIGNED NOT NULL DEFAULT 0,
  `high_impact_events` INT UNSIGNED NOT NULL DEFAULT 0 COMMENT 'AIS > 0.7000',
  `doctrine_updates_triggered` INT UNSIGNED NOT NULL DEFAULT 0,
  `trend_metadata_json` JSON DEFAULT NULL COMMENT 'Additional trend analysis',
  `calculated_ymdhis` BIGINT NOT NULL COMMENT 'When trend was calculated',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_period_trend` (`trend_period`, `period_start_ymdhis`),
  KEY `idx_period_range` (`period_start_ymdhis`, `period_end_ymdhis`),
  KEY `idx_high_impact` (`high_impact_events`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci 
COMMENT='CIP Trends - Aggregated analytics over time periods';

-- ============================================================================
-- DOCTRINE REFINEMENT MODULE TABLES
-- ============================================================================

-- Doctrine Refinement Events
CREATE TABLE IF NOT EXISTS `lupo_doctrine_refinements` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `cip_event_id` BIGINT UNSIGNED NOT NULL COMMENT 'Triggering CIP event',
  `doctrine_file_path` VARCHAR(500) NOT NULL COMMENT 'Path to doctrine file updated',
  `refinement_type` ENUM('addition', 'modification', 'removal', 'restructure') NOT NULL,
  `change_description` TEXT NOT NULL COMMENT 'Description of doctrine change',
  `before_content_hash` VARCHAR(64) DEFAULT NULL COMMENT 'SHA256 of content before change',
  `after_content_hash` VARCHAR(64) NOT NULL COMMENT 'SHA256 of content after change',
  `impact_assessment_json` JSON DEFAULT NULL COMMENT 'Assessment of change impact',
  `approval_status` ENUM('pending', 'approved', 'rejected', 'auto_approved') DEFAULT 'pending',
  `approved_by` VARCHAR(100) DEFAULT NULL COMMENT 'Who approved the change',
  `applied_ymdhis` BIGINT DEFAULT NULL COMMENT 'When change was applied',
  `created_ymdhis` BIGINT NOT NULL COMMENT 'When refinement was proposed',
  `refinement_version` VARCHAR(20) DEFAULT '4.0.75' COMMENT 'Refinement module version',
  PRIMARY KEY (`id`),
  KEY `idx_cip_event` (`cip_event_id`),
  KEY `idx_doctrine_file` (`doctrine_file_path`(255)),
  KEY `idx_approval_status` (`approval_status`),
  KEY `idx_applied_time` (`applied_ymdhis`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci 
COMMENT='Doctrine Refinement Module - CIP-driven doctrine evolution tracking';

-- Doctrine Evolution Audit Trail
CREATE TABLE IF NOT EXISTS `lupo_doctrine_evolution_audit` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `refinement_id` BIGINT UNSIGNED NOT NULL COMMENT 'Reference to lupo_doctrine_refinements.id',
  `evolution_step` TINYINT UNSIGNED NOT NULL COMMENT 'Step in evolution process (1-10)',
  `step_description` VARCHAR(255) NOT NULL COMMENT 'Description of evolution step',
  `step_status` ENUM('pending', 'in_progress', 'completed', 'failed', 'skipped') DEFAULT 'pending',
  `step_metadata_json` JSON DEFAULT NULL COMMENT 'Step-specific metadata',
  `started_ymdhis` BIGINT DEFAULT NULL COMMENT 'When step started',
  `completed_ymdhis` BIGINT DEFAULT NULL COMMENT 'When step completed',
  `audit_version` VARCHAR(20) DEFAULT '4.0.75' COMMENT 'Audit system version',
  PRIMARY KEY (`id`),
  KEY `idx_refinement_step` (`refinement_id`, `evolution_step`),
  KEY `idx_step_status` (`step_status`),
  KEY `idx_completion_time` (`completed_ymdhis`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci 
COMMENT='Doctrine Evolution Audit - Detailed tracking of doctrine change process';

-- ============================================================================
-- EMOTIONAL GEOMETRY CALIBRATION TABLES
-- ============================================================================

-- Emotional Geometry Calibration Events
CREATE TABLE IF NOT EXISTS `lupo_emotional_geometry_calibrations` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `cip_analytics_id` BIGINT UNSIGNED NOT NULL COMMENT 'Reference to lupo_cip_analytics.id',
  `calibration_target` ENUM('agent', 'subsystem', 'global') NOT NULL,
  `target_identifier` VARCHAR(255) NOT NULL COMMENT 'Agent ID, subsystem name, or "global"',
  `baseline_before_json` JSON DEFAULT NULL COMMENT 'R/G/B vectors before calibration',
  `baseline_after_json` JSON NOT NULL COMMENT 'R/G/B vectors after calibration',
  `tension_vectors_detected` JSON DEFAULT NULL COMMENT 'Detected tension patterns',
  `calibration_reason` TEXT NOT NULL COMMENT 'Why calibration was needed',
  `calibration_algorithm` VARCHAR(100) DEFAULT 'cip_pattern_analysis' COMMENT 'Algorithm used',
  `confidence_score` DECIMAL(5,4) NOT NULL DEFAULT 0.5000 COMMENT 'Calibration confidence',
  `validation_status` ENUM('pending', 'validated', 'rejected', 'needs_review') DEFAULT 'pending',
  `applied_ymdhis` BIGINT DEFAULT NULL COMMENT 'When calibration was applied',
  `created_ymdhis` BIGINT NOT NULL COMMENT 'When calibration was calculated',
  `calibration_version` VARCHAR(20) DEFAULT '4.0.75' COMMENT 'Calibration system version',
  PRIMARY KEY (`id`),
  KEY `idx_analytics_ref` (`cip_analytics_id`),
  KEY `idx_target` (`calibration_target`, `target_identifier`(100)),
  KEY `idx_validation_status` (`validation_status`),
  KEY `idx_confidence` (`confidence_score`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci 
COMMENT='Emotional Geometry Calibration - CIP-driven emotional baseline adjustments';

-- Calibration Impact Tracking
CREATE TABLE IF NOT EXISTS `lupo_calibration_impacts` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `calibration_id` BIGINT UNSIGNED NOT NULL COMMENT 'Reference to lupo_emotional_geometry_calibrations.id',
  `impact_type` ENUM('agent_behavior', 'communication_tone', 'system_harmony', 'conflict_reduction') NOT NULL,
  `impact_measurement` DECIMAL(5,4) NOT NULL COMMENT 'Quantified impact (0.0000-1.0000)',
  `measurement_method` VARCHAR(100) NOT NULL COMMENT 'How impact was measured',
  `before_metrics_json` JSON DEFAULT NULL COMMENT 'Metrics before calibration',
  `after_metrics_json` JSON DEFAULT NULL COMMENT 'Metrics after calibration',
  `observation_period_hours` INT UNSIGNED DEFAULT 24 COMMENT 'Observation period length',
  `measured_ymdhis` BIGINT NOT NULL COMMENT 'When impact was measured',
  `impact_version` VARCHAR(20) DEFAULT '4.0.75' COMMENT 'Impact tracking version',
  PRIMARY KEY (`id`),
  KEY `idx_calibration_impact` (`calibration_id`, `impact_type`),
  KEY `idx_impact_measurement` (`impact_measurement`),
  KEY `idx_measurement_time` (`measured_ymdhis`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci 
COMMENT='Calibration Impact Tracking - Measuring effectiveness of emotional geometry adjustments';

-- ============================================================================
-- CIP PROPAGATION DEPTH VISUALIZER TABLES
-- ============================================================================

-- CIP Propagation Tracking
CREATE TABLE IF NOT EXISTS `lupo_cip_propagation_tracking` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `cip_event_id` BIGINT UNSIGNED NOT NULL COMMENT 'Root CIP event',
  `propagation_level` TINYINT UNSIGNED NOT NULL COMMENT 'Depth level (0-10)',
  `affected_subsystem` VARCHAR(100) NOT NULL COMMENT 'Subsystem affected at this level',
  `propagation_type` ENUM('doctrine', 'emotional_geometry', 'agent_behavior', 'system_config') NOT NULL,
  `change_description` TEXT NOT NULL COMMENT 'What changed at this level',
  `propagation_strength` DECIMAL(5,4) NOT NULL DEFAULT 1.0000 COMMENT 'Strength of propagation',
  `completion_status` ENUM('pending', 'in_progress', 'completed', 'blocked', 'failed') DEFAULT 'pending',
  `dependencies_json` JSON DEFAULT NULL COMMENT 'Dependencies for this propagation step',
  `started_ymdhis` BIGINT DEFAULT NULL COMMENT 'When propagation started',
  `completed_ymdhis` BIGINT DEFAULT NULL COMMENT 'When propagation completed',
  `propagation_version` VARCHAR(20) DEFAULT '4.0.75' COMMENT 'Propagation tracking version',
  PRIMARY KEY (`id`),
  KEY `idx_event_level` (`cip_event_id`, `propagation_level`),
  KEY `idx_subsystem` (`affected_subsystem`),
  KEY `idx_completion_status` (`completion_status`),
  KEY `idx_propagation_strength` (`propagation_strength`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci 
COMMENT='CIP Propagation Tracking - Visualizing depth and breadth of critique integration';

-- ============================================================================
-- MULTI-AGENT CRITIQUE SYNCHRONIZATION TABLES
-- ============================================================================

-- Multi-Agent Critique Synchronization
CREATE TABLE IF NOT EXISTS `lupo_multi_agent_critique_sync` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `cip_event_id` BIGINT UNSIGNED NOT NULL COMMENT 'Root CIP event',
  `agent_id` VARCHAR(100) NOT NULL COMMENT 'Agent participating in sync',
  `sync_role` ENUM('initiator', 'participant', 'observer', 'validator') NOT NULL,
  `sync_status` ENUM('pending', 'synchronized', 'out_of_sync', 'conflict', 'resolved') DEFAULT 'pending',
  `agent_perspective_json` JSON DEFAULT NULL COMMENT 'Agent-specific view of critique',
  `consensus_contribution` DECIMAL(5,4) DEFAULT 0.0000 COMMENT 'Contribution to consensus (0-1)',
  `conflict_indicators_json` JSON DEFAULT NULL COMMENT 'Detected conflicts with other agents',
  `resolution_strategy` VARCHAR(255) DEFAULT NULL COMMENT 'Strategy for resolving conflicts',
  `sync_started_ymdhis` BIGINT DEFAULT NULL COMMENT 'When sync process started',
  `sync_completed_ymdhis` BIGINT DEFAULT NULL COMMENT 'When sync was completed',
  `sync_version` VARCHAR(20) DEFAULT '4.0.75' COMMENT 'Sync protocol version',
  PRIMARY KEY (`id`),
  KEY `idx_event_agent` (`cip_event_id`, `agent_id`),
  KEY `idx_sync_status` (`sync_status`),
  KEY `idx_sync_role` (`sync_role`),
  KEY `idx_consensus_contribution` (`consensus_contribution`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci 
COMMENT='Multi-Agent Critique Synchronization - Coordinating critique integration across agents';

-- ============================================================================
-- EXTEND EXISTING TABLES FOR CIP ANALYTICS
-- ============================================================================

-- Extend lupo_cip_events for analytics integration
ALTER TABLE `lupo_cip_events` 
ADD COLUMN IF NOT EXISTS `analytics_processed` BOOLEAN DEFAULT FALSE COMMENT 'Whether analytics have been calculated',
ADD COLUMN IF NOT EXISTS `analytics_processed_ymdhis` BIGINT DEFAULT NULL COMMENT 'When analytics were processed',
ADD COLUMN IF NOT EXISTS `self_correction_triggered` BOOLEAN DEFAULT FALSE COMMENT 'Whether self-correction was triggered',
ADD COLUMN IF NOT EXISTS `correction_cascade_depth` TINYINT UNSIGNED DEFAULT 0 COMMENT 'Depth of correction cascade (0-10)';

-- Extend lupo_actor_collections for emotional geometry baselines
ALTER TABLE `lupo_actor_collections`
ADD COLUMN IF NOT EXISTS `emotional_geometry_calibrated_ymdhis` BIGINT DEFAULT NULL COMMENT 'Last calibration timestamp',
ADD COLUMN IF NOT EXISTS `calibration_trigger_count` INT UNSIGNED DEFAULT 0 COMMENT 'Number of calibrations performed',
ADD COLUMN IF NOT EXISTS `baseline_stability_score` DECIMAL(5,4) DEFAULT 0.5000 COMMENT 'Stability of emotional baseline';

-- ============================================================================
-- CREATE INDEXES FOR PERFORMANCE
-- ============================================================================

-- Analytics performance indexes
CREATE INDEX IF NOT EXISTS `idx_cip_analytics_processed` ON `lupo_cip_events` (`analytics_processed`, `analytics_processed_ymdhis`);
CREATE INDEX IF NOT EXISTS `idx_self_correction_triggered` ON `lupo_cip_events` (`self_correction_triggered`);
CREATE INDEX IF NOT EXISTS `idx_correction_cascade_depth` ON `lupo_cip_events` (`correction_cascade_depth`);

-- Emotional geometry calibration indexes
CREATE INDEX IF NOT EXISTS `idx_emotional_calibration_time` ON `lupo_actor_collections` (`emotional_geometry_calibrated_ymdhis`);
CREATE INDEX IF NOT EXISTS `idx_baseline_stability` ON `lupo_actor_collections` (`baseline_stability_score`);

-- ============================================================================
-- COMMENTS AND DOCUMENTATION
-- ============================================================================

ALTER TABLE `lupo_cip_analytics` COMMENT = 'CIP Analytics Engine - Comprehensive critique event analysis and trend computation';
ALTER TABLE `lupo_cip_trends` COMMENT = 'CIP Trends - Time-series aggregation of critique integration metrics';
ALTER TABLE `lupo_doctrine_refinements` COMMENT = 'Doctrine Refinement Module - CIP-driven doctrine evolution and change management';
ALTER TABLE `lupo_doctrine_evolution_audit` COMMENT = 'Doctrine Evolution Audit - Detailed process tracking for doctrine changes';
ALTER TABLE `lupo_emotional_geometry_calibrations` COMMENT = 'Emotional Geometry Calibration - CIP-driven emotional baseline adjustments';
ALTER TABLE `lupo_calibration_impacts` COMMENT = 'Calibration Impact Tracking - Measuring effectiveness of emotional adjustments';
ALTER TABLE `lupo_cip_propagation_tracking` COMMENT = 'CIP Propagation Tracking - Visualizing critique integration depth and breadth';
ALTER TABLE `lupo_multi_agent_critique_sync` COMMENT = 'Multi-Agent Critique Synchronization - Coordinating critique integration across agent fleet';