-- Migration: Create Unified Authentication Tables
-- Version: 2026.1.0.8
-- Date: 2026-01-22
-- Module: Authentication System
-- Purpose: Create core tables for unified Lupopedia + Crafty Syntax authentication
-- HERITAGE-SAFE: Fully additive migration, no destructive changes

USE lupopedia;

-- Create lupo_crafty_user_mapping table for bi-directional user identity mapping
CREATE TABLE IF NOT EXISTS `lupo_crafty_user_mapping` (
    `id` bigint NOT NULL AUTO_INCREMENT COMMENT 'Primary key for mapping',
    `lupo_user_id` bigint NULL COMMENT 'Reference to lupo_auth_users.auth_user_id',
    `crafty_operator_id` int NULL COMMENT 'Reference to livehelp_operators.operatorid',
    `mapping_type` varchar(50) NOT NULL DEFAULT 'manual' COMMENT 'Type: manual, auto, imported',
    `notes` text NULL COMMENT 'Optional notes for mapping',
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Creation timestamp',
    `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Last update timestamp',
    PRIMARY KEY (`id`),
    INDEX `idx_lupo_user_id` (`lupo_user_id`),
    INDEX `idx_crafty_operator_id` (`crafty_operator_id`),
    INDEX `idx_mapping_type` (`mapping_type`),
    UNIQUE KEY `unique_lupo_user_mapping` (`lupo_user_id`),
    UNIQUE KEY `unique_crafty_operator_mapping` (`crafty_operator_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='User mapping between Lupopedia and Crafty Syntax systems';

-- Create unified_sessions table for cross-system session tracking
CREATE TABLE IF NOT EXISTS `unified_sessions` (
    `id` bigint NOT NULL AUTO_INCREMENT COMMENT 'Primary key for session',
    `session_id` varchar(255) NOT NULL COMMENT 'Session identifier',
    `user_id` bigint NULL COMMENT 'Reference to lupo_auth_users.auth_user_id',
    `system_context` varchar(50) NOT NULL COMMENT 'lupopedia, crafty_syntax, or unified',
    `session_data` json NULL COMMENT 'Session metadata and preferences',
    `expires_at` timestamp NOT NULL COMMENT 'Session expiration time',
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Creation timestamp',
    `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Last update timestamp',
    PRIMARY KEY (`id`),
    UNIQUE KEY `unique_session_id` (`session_id`),
    INDEX `idx_user_id` (`user_id`),
    INDEX `idx_system_context` (`system_context`),
    INDEX `idx_expires_at` (`expires_at`),
    INDEX `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Cross-system session management';

-- Create auth_audit_log table for authentication event tracking
CREATE TABLE IF NOT EXISTS `auth_audit_log` (
    `id` bigint NOT NULL AUTO_INCREMENT COMMENT 'Primary key for audit log entry',
    `user_id` bigint NULL COMMENT 'Reference to lupo_auth_users.auth_user_id',
    `crafty_operator_id` int NULL COMMENT 'Reference to livehelp_operators.operatorid',
    `event_type` varchar(50) NOT NULL COMMENT 'login, logout, session_created, session_destroyed, etc.',
    `system_context` varchar(50) NOT NULL COMMENT 'lupopedia, crafty_syntax, unified, admin',
    `ip_address` varchar(45) NULL COMMENT 'Client IP address',
    `user_agent` text NULL COMMENT 'Client user agent string',
    `event_data` json NULL COMMENT 'Additional event metadata',
    `success` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1=success, 0=failure',
    `error_message` text NULL COMMENT 'Error details if success=0',
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Event timestamp',
    `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Last update timestamp',
    PRIMARY KEY (`id`),
    INDEX `idx_user_id` (`user_id`),
    INDEX `idx_crafty_operator_id` (`crafty_operator_id`),
    INDEX `idx_event_type` (`event_type`),
    INDEX `idx_system_context` (`system_context`),
    INDEX `idx_success` (`success`),
    INDEX `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Authentication event audit trail';

-- Add crafty_operator_id column to existing users table if it doesn't exist
SET @exists = (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS 
                   WHERE TABLE_SCHEMA = 'lupopedia' 
                   AND TABLE_NAME = 'users' 
                   AND COLUMN_NAME = 'crafty_operator_id');

SET @sql = IF(@exists = 0, 
    'ALTER TABLE `users` ADD COLUMN `crafty_operator_id` int NULL COMMENT ''Reference to livehelp_operators.operatorid'' AFTER `id`',
    'SELECT ''Column crafty_operator_id already exists in users table''
);

PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Add index for crafty_operator_id if it doesn't exist
SET @index_exists = (SELECT COUNT(*) FROM INFORMATION_SCHEMA.STATISTICS 
                       WHERE TABLE_SCHEMA = 'lupopedia' 
                       AND TABLE_NAME = 'users' 
                       AND INDEX_NAME = 'idx_users_crafty_operator_id');

SET @index_sql = IF(@index_exists = 0,
    'CREATE INDEX `idx_users_crafty_operator_id` ON `users` (`crafty_operator_id`)',
    'SELECT ''Index idx_users_crafty_operator_id already exists on users table''
);

PREPARE index_stmt FROM @index_sql;
EXECUTE index_stmt;
DEALLOCATE PREPARE index_stmt;

-- Add foreign key constraint if it doesn't exist
SET @fk_exists = (SELECT COUNT(*) FROM INFORMATION_SCHEMA.TABLE_CONSTRAINTS 
                   WHERE TABLE_SCHEMA = 'lupopedia' 
                   AND TABLE_NAME = 'users' 
                   AND CONSTRAINT_NAME = 'fk_users_crafty_operator_id');

SET @fk_sql = IF(@fk_exists = 0,
    'ALTER TABLE `users` ADD CONSTRAINT `fk_users_crafty_operator_id` 
     FOREIGN KEY (`crafty_operator_id`) 
     REFERENCES `livehelp_operators`(`operatorid`) 
     ON DELETE SET NULL 
     ON UPDATE CASCADE',
    'SELECT ''Foreign key fk_users_crafty_operator_id already exists on users table''
);

PREPARE fk_stmt FROM @fk_sql;
EXECUTE fk_stmt;
DEALLOCATE PREPARE fk_stmt;

-- Add foreign key constraint for lupo_crafty_user_mapping if it doesn't exist
SET @mapping_fk_exists = (SELECT COUNT(*) FROM INFORMATION_SCHEMA.TABLE_CONSTRAINTS 
                         WHERE TABLE_SCHEMA = 'lupopedia' 
                         AND TABLE_NAME = 'lupo_crafty_user_mapping' 
                         AND CONSTRAINT_NAME = 'fk_mapping_lupo_user_id');

SET @mapping_fk_sql = IF(@mapping_fk_exists = 0,
    'ALTER TABLE `lupo_crafty_user_mapping` ADD CONSTRAINT `fk_mapping_lupo_user_id` 
         FOREIGN KEY (`lupo_user_id`) 
         REFERENCES `lupo_auth_users`(`auth_user_id`) 
         ON DELETE CASCADE 
         ON UPDATE CASCADE',
    'SELECT ''Foreign key fk_mapping_lupo_user_id already exists on lupo_crafty_user_mapping table''
);

PREPARE mapping_fk_stmt FROM @mapping_fk_sql;
EXECUTE mapping_fk_stmt;
DEALLOCATE PREPARE mapping_fk_stmt;

-- Add foreign key constraint for unified_sessions if it doesn't exist
SET @session_fk_exists = (SELECT COUNT(*) FROM INFORMATION_SCHEMA.TABLE_CONSTRAINTS 
                         WHERE TABLE_SCHEMA = 'lupopedia' 
                         AND TABLE_NAME = 'unified_sessions' 
                         AND CONSTRAINT_NAME = 'fk_sessions_user_id');

SET @session_fk_sql = IF(@session_fk_exists = 0,
    'ALTER TABLE `unified_sessions` ADD CONSTRAINT `fk_sessions_user_id` 
         FOREIGN KEY (`user_id`) 
         REFERENCES `lupo_auth_users`(`auth_user_id`) 
         ON DELETE CASCADE 
         ON UPDATE CASCADE',
    'SELECT ''Foreign key fk_sessions_user_id already exists on unified_sessions table''
);

PREPARE session_fk_stmt FROM @session_fk_sql;
EXECUTE session_fk_stmt;
DEALLOCATE PREPARE session_fk_stmt;

-- Add foreign key constraint for auth_audit_log if it doesn't exist
SET @audit_fk_exists = (SELECT COUNT(*) FROM INFORMATION_SCHEMA.TABLE_CONSTRAINTS 
                        WHERE TABLE_SCHEMA = 'lupopedia' 
                        AND TABLE_NAME = 'auth_audit_log' 
                        AND CONSTRAINT_NAME = 'fk_audit_user_id');

SET @audit_fk_sql = IF(@audit_fk_exists = 0,
    'ALTER TABLE `auth_audit_log` ADD CONSTRAINT `fk_audit_user_id` 
         FOREIGN KEY (`user_id`) 
         REFERENCES `lupo_auth_users`(`auth_user_id`) 
         ON DELETE SET NULL 
         ON UPDATE CASCADE',
    'SELECT ''Foreign key fk_audit_user_id already exists on auth_audit_log table''
);

PREPARE audit_fk_stmt FROM @audit_fk_sql;
EXECUTE audit_fk_stmt;
DEALLOCATE PREPARE audit_fk_stmt;

-- Migration completed successfully
-- All tables created with proper indexes and constraints
-- No existing data was modified or deleted
