-- ======================================================================
-- ORCHESTRATOR SCHEMA DEPLOYMENT - Version 4.0.25
-- Creates core tables in lupopedia_orchestration schema for migration
-- orchestration, schema management, and system coordination.
--
-- This migration:
-- - Creates lupopedia_orchestration schema (if not exists)
-- - Creates core orchestration tables (migration tracking, schema versioning)
-- - Establishes foundation for migration orchestrator subsystem
-- - Maintains schema federation integrity
--
-- Version: 4.0.25
-- Status: STABLE
-- ======================================================================

-- ======================================================================
-- CREATE ORCHESTRATION SCHEMA (if not exists)
-- ======================================================================

CREATE SCHEMA IF NOT EXISTS lupopedia_orchestration 
  CHARACTER SET utf8mb4 
  COLLATE utf8mb4_unicode_ci
  COMMENT 'Orchestration and migration management tables';

-- ======================================================================
-- CORE ORCHESTRATOR TABLES
-- ======================================================================

-- Migration tracking table
CREATE TABLE IF NOT EXISTS lupopedia_orchestration.lupo_migration_tracking (
  `migration_tracking_id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `migration_name` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Migration filename or identifier',
  `migration_version` VARCHAR(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Version this migration belongs to (e.g., 4.0.25)',
  `migration_type` ENUM('schema', 'data', 'rollback', 'validation') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'schema' COMMENT 'Type of migration',
  `execution_status` ENUM('pending', 'running', 'completed', 'failed', 'rolled_back') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending' COMMENT 'Current execution status',
  `execution_order` INT NOT NULL DEFAULT 0 COMMENT 'Order in which migrations should execute',
  `schema_name` VARCHAR(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Target schema name (null = default lupopedia schema)',
  `tables_affected` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'JSON array of table names affected by this migration',
  `execution_start_ymdhis` BIGINT DEFAULT NULL COMMENT 'UTC YYYYMMDDHHMMSS when execution started',
  `execution_end_ymdhis` BIGINT DEFAULT NULL COMMENT 'UTC YYYYMMDDHHMMSS when execution completed',
  `execution_duration_ms` INT DEFAULT NULL COMMENT 'Execution duration in milliseconds',
  `error_message` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'Error message if execution failed',
  `rollback_migration_name` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Name of rollback migration if available',
  `executed_by_actor_id` BIGINT(20) UNSIGNED DEFAULT 1 COMMENT 'Actor who executed this migration',
  `created_ymdhis` BIGINT NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when record created',
  `updated_ymdhis` BIGINT NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when record last updated',
  `is_deleted` TINYINT NOT NULL DEFAULT 0 COMMENT '1 = deleted, 0 = not deleted',
  `deleted_ymdhis` BIGINT DEFAULT NULL COMMENT 'UTC YYYYMMDDHHMMSS when deleted',
  INDEX `idx_migration_version` (`migration_version`),
  INDEX `idx_execution_status` (`execution_status`),
  INDEX `idx_execution_order` (`execution_order`),
  INDEX `idx_migration_name` (`migration_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Tracks migration execution history and status';

-- Schema version tracking
CREATE TABLE IF NOT EXISTS lupopedia_orchestration.lupo_schema_versions (
  `schema_version_id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `schema_name` VARCHAR(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Schema name (lupopedia, lupopedia_orchestration, lupopedia_ephemeral)',
  `version_string` VARCHAR(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Schema version (e.g., 4.0.25)',
  `version_number` INT NOT NULL COMMENT 'Numeric version for comparisons (e.g., 40025)',
  `table_count` INT NOT NULL DEFAULT 0 COMMENT 'Number of tables in this schema',
  `last_migration_name` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Last migration that updated this schema',
  `last_migration_ymdhis` BIGINT DEFAULT NULL COMMENT 'UTC YYYYMMDDHHMMSS when last migration ran',
  `created_ymdhis` BIGINT NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when record created',
  `updated_ymdhis` BIGINT NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when record last updated',
  `is_deleted` TINYINT NOT NULL DEFAULT 0 COMMENT '1 = deleted, 0 = not deleted',
  `deleted_ymdhis` BIGINT DEFAULT NULL COMMENT 'UTC YYYYMMDDHHMMSS when deleted',
  UNIQUE KEY `unique_schema_version` (`schema_name`, `version_string`),
  INDEX `idx_schema_name` (`schema_name`),
  INDEX `idx_version_number` (`version_number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Tracks schema versions across all federated schemas';

-- Table classification registry
CREATE TABLE IF NOT EXISTS lupopedia_orchestration.lupo_table_classifications (
  `table_classification_id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `schema_name` VARCHAR(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Schema containing the table',
  `table_name` VARCHAR(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Table name',
  `classification` ENUM('core', 'orchestration', 'ephemeral', 'legacy', 'deprecated') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Table classification',
  `category` VARCHAR(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Functional category (e.g., actors, content, analytics)',
  `migration_priority` INT NOT NULL DEFAULT 0 COMMENT 'Priority for migration (higher = migrate first)',
  `target_schema` VARCHAR(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Target schema for migration (if applicable)',
  `migration_status` ENUM('unmigrated', 'pending', 'migrated', 'skipped') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'unmigrated' COMMENT 'Migration status',
  `notes` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'Additional notes about this table',
  `created_ymdhis` BIGINT NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when record created',
  `updated_ymdhis` BIGINT NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when record last updated',
  `is_deleted` TINYINT NOT NULL DEFAULT 0 COMMENT '1 = deleted, 0 = not deleted',
  `deleted_ymdhis` BIGINT DEFAULT NULL COMMENT 'UTC YYYYMMDDHHMMSS when deleted',
  UNIQUE KEY `unique_schema_table` (`schema_name`, `table_name`),
  INDEX `idx_classification` (`classification`),
  INDEX `idx_migration_status` (`migration_status`),
  INDEX `idx_target_schema` (`target_schema`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Classifies tables for migration planning and orchestration';

-- Migration state machine tracking
CREATE TABLE IF NOT EXISTS lupopedia_orchestration.lupo_migration_states (
  `migration_state_id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `migration_tracking_id` BIGINT(20) UNSIGNED NOT NULL COMMENT 'Reference to migration_tracking',
  `state` ENUM('pre_migration', 'validation', 'backup', 'execution', 'verification', 'post_migration', 'completed', 'failed', 'rolled_back') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Current state in migration lifecycle',
  `previous_state` ENUM('pre_migration', 'validation', 'backup', 'execution', 'verification', 'post_migration', 'completed', 'failed', 'rolled_back') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Previous state',
  `state_entered_ymdhis` BIGINT NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when state was entered',
  `state_duration_ms` INT DEFAULT NULL COMMENT 'Duration in milliseconds spent in this state',
  `state_data_json` JSON DEFAULT NULL COMMENT 'State-specific data in JSON format',
  `created_ymdhis` BIGINT NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when record created',
  `updated_ymdhis` BIGINT NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when record last updated',
  `is_deleted` TINYINT NOT NULL DEFAULT 0 COMMENT '1 = deleted, 0 = not deleted',
  `deleted_ymdhis` BIGINT DEFAULT NULL COMMENT 'UTC YYYYMMDDHHMMSS when deleted',
  INDEX `idx_migration_tracking_id` (`migration_tracking_id`),
  INDEX `idx_state` (`state`),
  INDEX `idx_state_entered` (`state_entered_ymdhis`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Tracks state machine transitions for migration execution';

-- Migration validation results
CREATE TABLE IF NOT EXISTS lupopedia_orchestration.lupo_migration_validations (
  `migration_validation_id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `migration_tracking_id` BIGINT(20) UNSIGNED NOT NULL COMMENT 'Reference to migration_tracking',
  `validation_type` VARCHAR(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Type of validation (e.g., schema_check, data_integrity, constraint_check)',
  `validation_status` ENUM('pending', 'passed', 'failed', 'warning') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending' COMMENT 'Validation result',
  `validation_message` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'Validation result message',
  `validation_data_json` JSON DEFAULT NULL COMMENT 'Validation details in JSON format',
  `validated_ymdhis` BIGINT NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when validation ran',
  `created_ymdhis` BIGINT NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when record created',
  `updated_ymdhis` BIGINT NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when record last updated',
  `is_deleted` TINYINT NOT NULL DEFAULT 0 COMMENT '1 = deleted, 0 = not deleted',
  `deleted_ymdhis` BIGINT DEFAULT NULL COMMENT 'UTC YYYYMMDDHHMMSS when deleted',
  INDEX `idx_migration_tracking_id` (`migration_tracking_id`),
  INDEX `idx_validation_status` (`validation_status`),
  INDEX `idx_validation_type` (`validation_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Stores validation results for migrations';

-- ======================================================================
-- INITIALIZE SCHEMA VERSION
-- ======================================================================

INSERT INTO lupopedia_orchestration.lupo_schema_versions (
  schema_name,
  version_string,
  version_number,
  table_count,
  created_ymdhis,
  updated_ymdhis,
  is_deleted
) VALUES (
  'lupopedia_orchestration',
  '4.0.25',
  40025,
  5,
  20260115000000,
  20260115000000,
  0
) ON DUPLICATE KEY UPDATE
  version_string = '4.0.25',
  version_number = 40025,
  table_count = 5,
  updated_ymdhis = 20260115000000,
  is_deleted = 0;

-- ======================================================================
-- END OF ORCHESTRATOR SCHEMA DEPLOYMENT
-- ======================================================================
