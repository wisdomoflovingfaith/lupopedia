-- ============================================================
-- Lupopedia Genesis Doctrine Implementation Tables
-- Version: 4.2.0
-- Purpose: Support Genesis Doctrine validation and emergent role discovery
-- Doctrine: Genesis Doctrine v1.0.0 - Five Pillars Implementation
-- ============================================================

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- --------------------------------------------------------
-- Table structure for lupo_doctrine_validations
-- Tracks Genesis Doctrine compliance for all features
-- --------------------------------------------------------

CREATE TABLE `lupo_doctrine_validations` (
  `validation_id` bigint NOT NULL AUTO_INCREMENT,
  `feature_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Name of feature being validated',
  `validation_result` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'JSON-encoded validation results including pillar compliance and litmus test results',
  `actor_id` bigint NOT NULL COMMENT 'Actor who performed the validation',
  `feature_version` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Version of feature being validated',
  `validation_type` enum('initial','update','compliance_check','pressure_test') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'initial' COMMENT 'Type of validation performed',
  `overall_status` enum('passed','failed','warning') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Overall validation status',
  `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when validation was performed',
  PRIMARY KEY (`validation_id`),
  KEY `idx_feature_name` (`feature_name`),
  KEY `idx_actor_id` (`actor_id`),
  KEY `idx_created_ymdhis` (`created_ymdhis`),
  KEY `idx_overall_status` (`overall_status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Tracks Genesis Doctrine compliance validations for all system features';

-- --------------------------------------------------------
-- Table structure for lupo_emergent_role_discoveries
-- Records emergent role discoveries based on interaction patterns
-- Implements the Emergence Pillar: Roles are discovered, not assigned
-- --------------------------------------------------------

CREATE TABLE `lupo_emergent_role_discoveries` (
  `discovery_id` bigint NOT NULL AUTO_INCREMENT,
  `actor_id` bigint NOT NULL COMMENT 'Actor whose emergent roles were discovered',
  `emergent_roles` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'JSON array of discovered emergent roles',
  `confidence_scores` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'JSON object mapping roles to confidence scores',
  `interaction_patterns` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'JSON object detailing interaction patterns analyzed',
  `pressure_response` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'JSON object describing response under pressure conditions',
  `discovery_method` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pattern_analysis' COMMENT 'Method used for role discovery',
  `pressure_context_id` bigint DEFAULT NULL COMMENT 'Reference to pressure context that triggered discovery',
  `confidence_threshold` decimal(3,2) DEFAULT '0.70' COMMENT 'Threshold used for role confidence',
  `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when discovery was made',
  PRIMARY KEY (`discovery_id`),
  KEY `idx_actor_id` (`actor_id`),
  KEY `idx_created_ymdhis` (`created_ymdhis`),
  KEY `idx_discovery_method` (`discovery_method`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Records emergent role discoveries based on agent interaction patterns and pressure responses';

-- --------------------------------------------------------
-- Table structure for lupo_pressure_contexts
-- Defines pressure contexts that trigger emergent behavior analysis
-- --------------------------------------------------------

CREATE TABLE `lupo_pressure_contexts` (
  `pressure_context_id` bigint NOT NULL AUTO_INCREMENT,
  `context_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Human-readable name of pressure context',
  `pressure_type` enum('system_load','conflict_resolution','innovation_demand','security_threat','resource_scarcity','complexity_overload','unknown') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Type of pressure applied',
  `domain_id` bigint NOT NULL COMMENT 'Domain scope for this pressure context',
  `intensity_level` decimal(3,2) DEFAULT '1.00' COMMENT 'Intensity of pressure (0.00-1.00)',
  `start_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when pressure started',
  `end_ymdhis` bigint DEFAULT NULL COMMENT 'UTC YYYYMMDDHHMMSS when pressure ended (NULL = ongoing)',
  `trigger_conditions` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'JSON conditions that triggered this pressure context',
  `affected_actors` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'JSON array of actor IDs affected by this pressure',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'Description of pressure context and expected behaviors',
  `created_by` bigint NOT NULL COMMENT 'Actor who created this pressure context',
  `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when context was created',
  `is_active` tinyint NOT NULL DEFAULT '1' COMMENT '1 = active, 0 = inactive',
  PRIMARY KEY (`pressure_context_id`),
  KEY `idx_domain_id` (`domain_id`),
  KEY `idx_pressure_type` (`pressure_type`),
  KEY `idx_start_ymdhis` (`start_ymdhis`),
  KEY `idx_is_active` (`is_active`),
  KEY `idx_created_by` (`created_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Defines pressure contexts that trigger emergent behavior analysis and role discovery';

-- --------------------------------------------------------
-- Table structure for lupo_expansion_principle_validations
-- Tracks First Expansion Principle compliance for new features
-- --------------------------------------------------------

CREATE TABLE `lupo_expansion_principle_validations` (
  `expansion_validation_id` bigint NOT NULL AUTO_INCREMENT,
  `feature_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Feature being validated',
  `polymorphism_score` decimal(3,2) DEFAULT '0.00' COMMENT 'Score for polymorphic support (0.00-1.00)',
  `non_interference_score` decimal(3,2) DEFAULT '0.00' COMMENT 'Score for non-interference (0.00-1.00)',
  `self_description_score` decimal(3,2) DEFAULT '0.00' COMMENT 'Score for self-description (0.00-1.00)',
  `temporal_integrity_score` decimal(3,2) DEFAULT '0.00' COMMENT 'Score for temporal integrity (0.00-1.00)',
  `reversibility_score` decimal(3,2) DEFAULT '0.00' COMMENT 'Score for reversibility (0.00-1.00)',
  `overall_score` decimal(3,2) DEFAULT '0.00' COMMENT 'Overall expansion principle compliance score',
  `validation_details` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'JSON details of validation results',
  `fragility_risk_assessment` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'JSON assessment of fragility risks',
  `meaning_increase_assessment` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'JSON assessment of meaning increase',
  `status` enum('passed','failed','warning','requires_review') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'requires_review' COMMENT 'Validation status',
  `validated_by` bigint NOT NULL COMMENT 'Actor who performed validation',
  `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when validation was performed',
  PRIMARY KEY (`expansion_validation_id`),
  KEY `idx_feature_name` (`feature_name`),
  KEY `idx_overall_score` (`overall_score`),
  KEY `idx_status` (`status`),
  KEY `idx_validated_by` (`validated_by`),
  KEY `idx_created_ymdhis` (`created_ymdhis`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Tracks First Expansion Principle compliance for new system features';

-- --------------------------------------------------------
-- Table structure for lupo_litmus_test_results
-- Records results of the Three Litmus Tests for features
-- --------------------------------------------------------

CREATE TABLE `lupo_litmus_test_results` (
  `litmus_test_id` bigint NOT NULL AUTO_INCREMENT,
  `feature_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Feature being tested',
  `actor_test_passed` tinyint NOT NULL DEFAULT '0' COMMENT '1 = Actor Test passed, 0 = failed',
  `actor_test_reason` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Reason for Actor Test result',
  `temporal_test_passed` tinyint NOT NULL DEFAULT '0' COMMENT '1 = Temporal Test passed, 0 = failed',
  `temporal_test_reason` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Reason for Temporal Test result',
  `doctrine_test_passed` tinyint NOT NULL DEFAULT '0' COMMENT '1 = Doctrine Test passed, 0 = failed',
  `doctrine_test_reason` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Reason for Doctrine Test result',
  `overall_passed` tinyint NOT NULL DEFAULT '0' COMMENT '1 = All tests passed, 0 = any test failed',
  `test_details` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'JSON details of test execution',
  `tested_by` bigint NOT NULL COMMENT 'Actor who performed tests',
  `feature_version` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Version of feature tested',
  `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when tests were performed',
  PRIMARY KEY (`litmus_test_id`),
  KEY `idx_feature_name` (`feature_name`),
  KEY `idx_overall_passed` (`overall_passed`),
  KEY `idx_tested_by` (`tested_by`),
  KEY `idx_created_ymdhis` (`created_ymdhis`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Records results of the Three Litmus Tests (Actor, Temporal, Doctrine) for all features';

-- --------------------------------------------------------
-- Insert initial pressure context examples
-- --------------------------------------------------------

INSERT INTO `lupo_pressure_contexts` (
  `context_name`, 
  `pressure_type`, 
  `domain_id`, 
  `intensity_level`, 
  `start_ymdhis`, 
  `end_ymdhis`, 
  `trigger_conditions`, 
  `affected_actors`, 
  `description`, 
  `created_by`, 
  `created_ymdhis`
) VALUES 
(
  'System Load Stress Test', 
  'system_load', 
  1, 
  0.8, 
  20260118000000, 
  20260118010000, 
  '{"cpu_threshold": 0.9, "memory_threshold": 0.85, "concurrent_users": 1000}', 
  '[1, 2, 3]', 
  'High system load conditions to test agent coordination and role emergence under stress', 
  1, 
  20260118000000
),
(
  'Conflict Resolution Scenario', 
  'conflict_resolution', 
  1, 
  0.7, 
  20260118010000, 
  20260118020000, 
  '{"conflicting_agents": 2, "resolution_required": true, "mediation_needed": true}', 
  '[1, 2]', 
  'Simulated conflict between agents to test emergent mediator roles', 
  1, 
  20260118010000
),
(
  'Innovation Demand Challenge', 
  'innovation_demand', 
  1, 
  0.6, 
  20260118020000, 
  20260118030000, 
  '{"problem_complexity": "high", "solution_novelty_required": true, "time_constraint": "tight"}', 
  '[1, 3]', 
  'Complex problem requiring innovative solutions to test emergent creative roles', 
  1, 
  20260118020000
);

-- --------------------------------------------------------
-- Insert sample Genesis Doctrine validation records
-- --------------------------------------------------------

INSERT INTO `lupo_doctrine_validations` (
  `feature_name`, 
  `validation_result`, 
  `actor_id`, 
  `feature_version`, 
  `validation_type`, 
  `overall_status`, 
  `created_ymdhis`
) VALUES 
(
  'EmergentRoleDiscovery', 
  '{"valid": true, "errors": [], "warnings": [], "pillar_compliance": {"actor": {"compliant": true}, "temporal": {"compliant": true}, "edge": {"compliant": true}, "doctrine": {"compliant": true}, "emergence": {"compliant": true}}, "litmus_results": {"passed": true}, "expansion_validation": {"valid": true}}', 
  1, 
  '1.0.0', 
  'initial', 
  'passed', 
  20260118000000
),
(
  'GenesisDoctrineValidator', 
  '{"valid": true, "errors": [], "warnings": [], "pillar_compliance": {"actor": {"compliant": true}, "temporal": {"compliant": true}, "edge": {"compliant": true}, "doctrine": {"compliant": true}, "emergence": {"compliant": true}}, "litmus_results": {"passed": true}, "expansion_validation": {"valid": true}}', 
  1, 
  '1.0.0', 
  'initial', 
  'passed', 
  20260118000000
);

COMMIT;
