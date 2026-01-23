-- ======================================================================
-- EPHEMERAL SCHEMA DEPLOYMENT - Version 4.0.25
-- Creates core tables in lupopedia_ephemeral schema for session data,
-- cache, and temporary storage that can be safely purged.
--
-- This migration:
-- - Creates lupopedia_ephemeral schema (if not exists)
-- - Creates core ephemeral tables (sessions, cache, temp data)
-- - Establishes foundation for ephemeral data management
-- - Maintains schema federation integrity
--
-- Version: 4.0.25
-- Status: STABLE
-- ======================================================================

-- ======================================================================
-- CREATE EPHEMERAL SCHEMA (if not exists)
-- ======================================================================

CREATE SCHEMA IF NOT EXISTS lupopedia_ephemeral 
  CHARACTER SET utf8mb4 
  COLLATE utf8mb4_unicode_ci
  COMMENT 'Ephemeral session, cache, and temporary tables';

-- ======================================================================
-- CORE EPHEMERAL TABLES
-- ======================================================================

-- Session storage
CREATE TABLE IF NOT EXISTS lupopedia_ephemeral.lupo_sessions (
  `session_id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `session_key` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Unique session identifier',
  `actor_id` BIGINT(20) UNSIGNED DEFAULT NULL COMMENT 'Actor associated with this session',
  `session_data_json` JSON DEFAULT NULL COMMENT 'Session data in JSON format',
  `ip_address` VARCHAR(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Client IP address',
  `user_agent` VARCHAR(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'User agent string',
  `last_activity_ymdhis` BIGINT NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS of last activity',
  `expires_ymdhis` BIGINT NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when session expires',
  `created_ymdhis` BIGINT NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when session created',
  `updated_ymdhis` BIGINT NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when session last updated',
  `is_deleted` TINYINT NOT NULL DEFAULT 0 COMMENT '1 = deleted, 0 = not deleted',
  `deleted_ymdhis` BIGINT DEFAULT NULL COMMENT 'UTC YYYYMMDDHHMMSS when deleted',
  UNIQUE KEY `unique_session_key` (`session_key`),
  INDEX `idx_actor_id` (`actor_id`),
  INDEX `idx_expires_ymdhis` (`expires_ymdhis`),
  INDEX `idx_last_activity` (`last_activity_ymdhis`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Stores ephemeral session data';

-- Cache storage
CREATE TABLE IF NOT EXISTS lupopedia_ephemeral.lupo_cache (
  `cache_id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `cache_key` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Cache key identifier',
  `cache_group` VARCHAR(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Cache group/category',
  `cache_value` LONGTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'Cached value (can be JSON, serialized data, etc.)',
  `cache_type` VARCHAR(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'json' COMMENT 'Type of cached data (json, serialized, text)',
  `expires_ymdhis` BIGINT DEFAULT NULL COMMENT 'UTC YYYYMMDDHHMMSS when cache expires (null = no expiration)',
  `hit_count` INT NOT NULL DEFAULT 0 COMMENT 'Number of times this cache entry was accessed',
  `last_hit_ymdhis` BIGINT DEFAULT NULL COMMENT 'UTC YYYYMMDDHHMMSS of last cache hit',
  `created_ymdhis` BIGINT NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when cache entry created',
  `updated_ymdhis` BIGINT NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when cache entry last updated',
  `is_deleted` TINYINT NOT NULL DEFAULT 0 COMMENT '1 = deleted, 0 = not deleted',
  `deleted_ymdhis` BIGINT DEFAULT NULL COMMENT 'UTC YYYYMMDDHHMMSS when deleted',
  UNIQUE KEY `unique_cache_key_group` (`cache_key`, `cache_group`),
  INDEX `idx_cache_group` (`cache_group`),
  INDEX `idx_expires_ymdhis` (`expires_ymdhis`),
  INDEX `idx_last_hit` (`last_hit_ymdhis`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Stores ephemeral cache data';

-- Temporary data storage
CREATE TABLE IF NOT EXISTS lupopedia_ephemeral.lupo_temp_data (
  `temp_data_id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `temp_key` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Temporary data key identifier',
  `temp_type` VARCHAR(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Type of temporary data',
  `temp_data_json` JSON DEFAULT NULL COMMENT 'Temporary data in JSON format',
  `temp_data_text` LONGTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'Temporary data as text',
  `created_by_actor_id` BIGINT(20) UNSIGNED DEFAULT NULL COMMENT 'Actor who created this temp data',
  `expires_ymdhis` BIGINT DEFAULT NULL COMMENT 'UTC YYYYMMDDHHMMSS when temp data expires',
  `created_ymdhis` BIGINT NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when temp data created',
  `updated_ymdhis` BIGINT NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when temp data last updated',
  `is_deleted` TINYINT NOT NULL DEFAULT 0 COMMENT '1 = deleted, 0 = not deleted',
  `deleted_ymdhis` BIGINT DEFAULT NULL COMMENT 'UTC YYYYMMDDHHMMSS when deleted',
  INDEX `idx_temp_key` (`temp_key`),
  INDEX `idx_temp_type` (`temp_type`),
  INDEX `idx_expires_ymdhis` (`expires_ymdhis`),
  INDEX `idx_created_by` (`created_by_actor_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Stores temporary data that can be safely purged';

-- Queue/job storage (for background tasks)
CREATE TABLE IF NOT EXISTS lupopedia_ephemeral.lupo_job_queue (
  `job_queue_id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `job_type` VARCHAR(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Type of job',
  `job_status` ENUM('pending', 'running', 'completed', 'failed', 'cancelled') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending' COMMENT 'Job execution status',
  `job_priority` INT NOT NULL DEFAULT 0 COMMENT 'Job priority (higher = execute first)',
  `job_data_json` JSON DEFAULT NULL COMMENT 'Job parameters and data in JSON format',
  `scheduled_ymdhis` BIGINT DEFAULT NULL COMMENT 'UTC YYYYMMDDHHMMSS when job should execute',
  `started_ymdhis` BIGINT DEFAULT NULL COMMENT 'UTC YYYYMMDDHHMMSS when job started',
  `completed_ymdhis` BIGINT DEFAULT NULL COMMENT 'UTC YYYYMMDDHHMMSS when job completed',
  `error_message` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'Error message if job failed',
  `retry_count` INT NOT NULL DEFAULT 0 COMMENT 'Number of retry attempts',
  `max_retries` INT NOT NULL DEFAULT 3 COMMENT 'Maximum retry attempts',
  `created_by_actor_id` BIGINT(20) UNSIGNED DEFAULT NULL COMMENT 'Actor who created this job',
  `created_ymdhis` BIGINT NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when job created',
  `updated_ymdhis` BIGINT NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when job last updated',
  `is_deleted` TINYINT NOT NULL DEFAULT 0 COMMENT '1 = deleted, 0 = not deleted',
  `deleted_ymdhis` BIGINT DEFAULT NULL COMMENT 'UTC YYYYMMDDHHMMSS when deleted',
  INDEX `idx_job_status` (`job_status`),
  INDEX `idx_job_type` (`job_type`),
  INDEX `idx_scheduled_ymdhis` (`scheduled_ymdhis`),
  INDEX `idx_priority` (`job_priority`, `job_status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Stores ephemeral job queue data';

-- Lock storage (for distributed locking)
CREATE TABLE IF NOT EXISTS lupopedia_ephemeral.lupo_locks (
  `lock_id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `lock_key` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Lock identifier',
  `lock_type` VARCHAR(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Type of lock',
  `locked_by_actor_id` BIGINT(20) UNSIGNED DEFAULT NULL COMMENT 'Actor who holds the lock',
  `acquired_ymdhis` BIGINT NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when lock acquired',
  `expires_ymdhis` BIGINT NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when lock expires',
  `lock_data_json` JSON DEFAULT NULL COMMENT 'Lock metadata in JSON format',
  `created_ymdhis` BIGINT NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when lock record created',
  `updated_ymdhis` BIGINT NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when lock record last updated',
  `is_deleted` TINYINT NOT NULL DEFAULT 0 COMMENT '1 = deleted, 0 = not deleted',
  `deleted_ymdhis` BIGINT DEFAULT NULL COMMENT 'UTC YYYYMMDDHHMMSS when deleted',
  UNIQUE KEY `unique_lock_key` (`lock_key`),
  INDEX `idx_expires_ymdhis` (`expires_ymdhis`),
  INDEX `idx_locked_by` (`locked_by_actor_id`),
  INDEX `idx_lock_type` (`lock_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Stores ephemeral distributed lock data';

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
  'lupopedia_ephemeral',
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
-- END OF EPHEMERAL SCHEMA DEPLOYMENT
-- ======================================================================
