-- ANUBIS Queue Tables
-- Version: 4.0.53
-- Purpose: Queue management for orphaned files awaiting header processing
-- Note: Follows Lupopedia doctrine (no foreign keys, BIGINT timestamps)

-- Main queue table
CREATE TABLE IF NOT EXISTS `lupo_anubis_queue` (
    `queue_id` BIGINT NOT NULL AUTO_INCREMENT,
    `file_path` VARCHAR(512) NOT NULL,
    `file_hash` VARCHAR(64),
    `detected_utc` BIGINT NOT NULL COMMENT 'YYYYMMDDHHIISS',
    `priority` TINYINT DEFAULT 5 COMMENT '1-10, lower = higher priority',
    `status` VARCHAR(32) DEFAULT 'pending' COMMENT 'pending, processing, recovered, failed, quarantined',
    `detection_method` VARCHAR(64) COMMENT 'missing_header, malformed_header, invalid_actor, etc.',
    `header_snapshot` TEXT COMMENT 'Partial header if any',
    `error_message` TEXT,
    `attempts` TINYINT DEFAULT 0,
    `last_attempt_utc` BIGINT,
    `assigned_to_actor_id` BIGINT COMMENT 'ANUBIS instance processing this',
    `created_utc` BIGINT NOT NULL,
    `updated_utc` BIGINT NOT NULL,
    `is_deleted` TINYINT DEFAULT 0,
    PRIMARY KEY (`queue_id`),
    INDEX `idx_status_priority` (`status`, `priority`),
    INDEX `idx_detected` (`detected_utc`),
    INDEX `idx_file_path` (`file_path`(255)),
    UNIQUE KEY `uniq_file_hash` (`file_hash`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Processing log table
CREATE TABLE IF NOT EXISTS `lupo_anubis_processing_log` (
    `log_id` BIGINT NOT NULL AUTO_INCREMENT,
    `queue_id` BIGINT NOT NULL,
    `file_path` VARCHAR(512) NOT NULL,
    `action` VARCHAR(64) NOT NULL COMMENT 'recovered, failed, quarantined, retry',
    `details` TEXT COMMENT 'JSON content',
    `actor_id` BIGINT COMMENT 'ANUBIS instance',
    `created_utc` BIGINT NOT NULL,
    PRIMARY KEY (`log_id`),
    INDEX `idx_queue` (`queue_id`),
    INDEX `idx_created` (`created_utc`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Recovery attempts table
CREATE TABLE IF NOT EXISTS `lupo_anubis_recovery_attempts` (
    `attempt_id` BIGINT NOT NULL AUTO_INCREMENT,
    `queue_id` BIGINT NOT NULL,
    `attempt_number` TINYINT NOT NULL,
    `attempt_utc` BIGINT NOT NULL,
    `strategy` VARCHAR(64) COMMENT 'template_generation, actor_inference, channel_guess',
    `success` TINYINT DEFAULT 0,
    `generated_header` TEXT,
    `error_details` TEXT COMMENT 'JSON content',
    `recovered_file_path` VARCHAR(512),
    PRIMARY KEY (`attempt_id`),
    INDEX `idx_queue_attempt` (`queue_id`, `attempt_number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Quarantine table
CREATE TABLE IF NOT EXISTS `lupo_anubis_quarantine` (
    `quarantine_id` BIGINT NOT NULL AUTO_INCREMENT,
    `queue_id` BIGINT NOT NULL,
    `file_path` VARCHAR(512) NOT NULL,
    `file_hash` VARCHAR(64),
    `quarantine_path` VARCHAR(512) NOT NULL,
    `reason` VARCHAR(255) NOT NULL,
    `quarantined_utc` BIGINT NOT NULL,
    `expires_utc` BIGINT COMMENT 'When to auto-delete',
    `reviewed_by_actor_id` BIGINT,
    `reviewed_utc` BIGINT,
    `resolution` VARCHAR(64),
    `is_deleted` TINYINT DEFAULT 0,
    PRIMARY KEY (`quarantine_id`),
    INDEX `idx_expires` (`expires_utc`),
    INDEX `idx_queue` (`queue_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
