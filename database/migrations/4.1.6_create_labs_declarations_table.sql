-- Migration: Create LABS Declarations Table
-- Version: 4.1.6
-- Date: 2026-01-19
--
-- Creates lupo_labs_declarations table for storing Lupopedia Actor Baseline State
-- (LABS-001) declarations. This table stores all mandatory pre-interaction
-- declarations required of every actor before system participation.
--
-- @package Lupopedia
-- @version 4.1.6
-- @author CAPTAIN_WOLFIE
-- @governance LABS-001 Doctrine v1.0

-- ============================================================================
-- CREATE LUPO_LABS_DECLARATIONS TABLE
-- ============================================================================
-- Stores LABS declarations with validation certificates and revalidation tracking

CREATE TABLE IF NOT EXISTS `lupo_labs_declarations` (
    `labs_declaration_id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT 'Primary key for LABS declaration record',
    `actor_id` BIGINT(20) UNSIGNED NOT NULL COMMENT 'Reference to actor (from lupo_actors)',
    `certificate_id` VARCHAR(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Unique certificate ID (LABS-CERT-{UNIQUE_ID})',
    `declaration_timestamp` BIGINT(14) UNSIGNED NOT NULL COMMENT 'UTC timestamp from actor declaration (YYYYMMDDHHMMSS)',
    `declarations_json` JSON NOT NULL COMMENT 'Complete LABS declaration set (all 10 declarations)',
    `validation_status` ENUM('valid', 'invalid', 'expired', 'quarantined') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'valid' COMMENT 'Current validation status',
    `labs_version` VARCHAR(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1.0' COMMENT 'LABS doctrine version',
    `next_revalidation_ymdhis` BIGINT(14) UNSIGNED NOT NULL COMMENT 'UTC timestamp when revalidation required (YYYYMMDDHHMMSS)',
    `validation_log_json` JSON DEFAULT NULL COMMENT 'Validation log entries (violations, errors)',
    `created_ymdhis` BIGINT(14) UNSIGNED NOT NULL COMMENT 'UTC creation timestamp (YYYYMMDDHHMMSS)',
    `updated_ymdhis` BIGINT(14) UNSIGNED NOT NULL COMMENT 'UTC last update timestamp (YYYYMMDDHHMMSS)',
    `is_deleted` TINYINT(1) NOT NULL DEFAULT '0' COMMENT 'Soft delete flag (1=deleted, 0=active)',
    `deleted_ymdhis` BIGINT(14) UNSIGNED DEFAULT NULL COMMENT 'UTC deletion timestamp (YYYYMMDDHHMMSS)',
    
    INDEX `idx_actor_id` (`actor_id`),
    INDEX `idx_certificate_id` (`certificate_id`),
    INDEX `idx_validation_status` (`validation_status`),
    INDEX `idx_next_revalidation` (`next_revalidation_ymdhis`),
    INDEX `idx_actor_status` (`actor_id`, `validation_status`, `is_deleted`),
    INDEX `idx_revalidation_due` (`next_revalidation_ymdhis`, `validation_status`, `is_deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Stores Lupopedia Actor Baseline State (LABS-001) declarations and validation certificates';

-- ============================================================================
-- CREATE LUPO_LABS_VIOLATIONS TABLE
-- ============================================================================
-- Tracks LABS violations for audit and compliance monitoring

CREATE TABLE IF NOT EXISTS `lupo_labs_violations` (
    `labs_violation_id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT 'Primary key for violation record',
    `actor_id` BIGINT(20) UNSIGNED NOT NULL COMMENT 'Reference to actor (from lupo_actors)',
    `violation_code` VARCHAR(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Violation code (e.g., TEMPORAL_MISALIGNMENT)',
    `violation_message` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Detailed violation message',
    `violation_severity` ENUM('critical', 'major', 'minor') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'major' COMMENT 'Violation severity level',
    `declaration_attempt_json` JSON DEFAULT NULL COMMENT 'Declaration data that caused violation',
    `action_taken` VARCHAR(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'QUARANTINE_ACTIVATED' COMMENT 'Action taken in response to violation',
    `resolved_ymdhis` BIGINT(14) UNSIGNED DEFAULT NULL COMMENT 'UTC timestamp when violation resolved (YYYYMMDDHHMMSS)',
    `created_ymdhis` BIGINT(14) UNSIGNED NOT NULL COMMENT 'UTC creation timestamp (YYYYMMDDHHMMSS)',
    `updated_ymdhis` BIGINT(14) UNSIGNED NOT NULL COMMENT 'UTC last update timestamp (YYYYMMDDHHMMSS)',
    `is_deleted` TINYINT(1) NOT NULL DEFAULT '0' COMMENT 'Soft delete flag (1=deleted, 0=active)',
    `deleted_ymdhis` BIGINT(14) UNSIGNED DEFAULT NULL COMMENT 'UTC deletion timestamp (YYYYMMDDHHMMSS)',
    
    INDEX `idx_actor_id` (`actor_id`),
    INDEX `idx_violation_code` (`violation_code`),
    INDEX `idx_severity` (`violation_severity`),
    INDEX `idx_resolved` (`resolved_ymdhis`),
    INDEX `idx_actor_unresolved` (`actor_id`, `resolved_ymdhis`, `is_deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Tracks LABS violations for audit and compliance monitoring';

-- ============================================================================
-- MIGRATION NOTES
-- ============================================================================
-- This migration creates the infrastructure for LABS-001 (Lupopedia Actor
-- Baseline State) protocol implementation.
--
-- Tables Created:
-- 1. lupo_labs_declarations - Stores LABS declarations and validation certificates
-- 2. lupo_labs_violations - Tracks violations for audit and compliance
--
-- Key Features:
-- - Certificate-based validation system
-- - Automatic revalidation tracking (24-hour default)
-- - Soft delete support (is_deleted, deleted_ymdhis)
-- - JSON storage for flexible declaration structure
-- - Comprehensive indexing for performance
--
-- Integration Points:
-- - References lupo_actors.actor_id
-- - Uses UTC_TIMEKEEPER for canonical timestamps
-- - Integrates with LABS_Validator class
-- - Supports governor notification system
--
-- Governance:
-- - LABS-001 Doctrine v1.0
-- - UTC_TIMEKEEPER Doctrine (temporal alignment)
-- - Actor Honesty Requirement
-- - No Hidden Logic Principle
