-- Migration: Filesystem to Database Migration for Agents and Channels
-- Version: 2026.3.9.0
-- Date: 2026-01-31
-- Purpose: Prepare database for migrating agent and channel metadata from filesystem directories to database tables
--
-- IMPORTANT: This migration does NOT populate data.
-- Run the companion PHP script: scripts/migrate_filesystem_to_db.php to populate data.
--
-- Doctrine Compliance:
-- - No foreign keys
-- - No triggers
-- - No procedures
-- - YMDHIS timestamps (YYYYMMDDHHMMSS format)

-- ============================================================================
-- AGENT FILE ATTACHMENTS TABLE
-- ============================================================================
-- Stores file references for agents (previously in agents/NNNN/ directories)

CREATE TABLE IF NOT EXISTS lupo_agent_files (
    file_id BIGINT NOT NULL AUTO_INCREMENT,
    agent_id BIGINT NOT NULL COMMENT 'References lupo_agents.agent_id',
    file_type VARCHAR(50) NOT NULL COMMENT 'metadata, system_prompt, readme, config, etc',
    file_name VARCHAR(255) NOT NULL COMMENT 'Original filename',
    file_path VARCHAR(500) NOT NULL COMMENT 'Path relative to uploads root',
    file_hash VARCHAR(64) NOT NULL COMMENT 'SHA256 hash of file content',
    file_size BIGINT NOT NULL COMMENT 'File size in bytes',
    mime_type VARCHAR(100) COMMENT 'MIME type',
    upload_ymdhis CHAR(14) NOT NULL COMMENT 'Upload timestamp YYYYMMDDHHMMSS',
    created_ymdhis CHAR(14) NOT NULL COMMENT 'Record creation timestamp',
    updated_ymdhis CHAR(14) NOT NULL COMMENT 'Record update timestamp',
    is_deleted TINYINT NOT NULL DEFAULT 0 COMMENT '1 = soft deleted',
    deleted_ymdhis CHAR(14) COMMENT 'Deletion timestamp',
    migrated_from_directory VARCHAR(255) COMMENT 'Original directory path for migration tracking',
    PRIMARY KEY (file_id),
    INDEX idx_agent_id (agent_id),
    INDEX idx_file_type (file_type),
    INDEX idx_file_hash (file_hash),
    INDEX idx_is_deleted (is_deleted),
    INDEX idx_upload_ymdhis (upload_ymdhis)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- CHANNEL FILE ATTACHMENTS TABLE
-- ============================================================================
-- Stores file references for channels (previously in channels/NNNN/ directories)

CREATE TABLE IF NOT EXISTS lupo_channel_files (
    file_id BIGINT NOT NULL AUTO_INCREMENT,
    channel_id BIGINT NOT NULL COMMENT 'References lupo_channels.channel_id',
    file_type VARCHAR(50) NOT NULL COMMENT 'metadata, contents, actors, context, threads, etc',
    file_name VARCHAR(255) NOT NULL COMMENT 'Original filename',
    file_path VARCHAR(500) NOT NULL COMMENT 'Path relative to uploads root',
    file_hash VARCHAR(64) NOT NULL COMMENT 'SHA256 hash of file content',
    file_size BIGINT NOT NULL COMMENT 'File size in bytes',
    mime_type VARCHAR(100) COMMENT 'MIME type',
    upload_ymdhis CHAR(14) NOT NULL COMMENT 'Upload timestamp YYYYMMDDHHMMSS',
    created_ymdhis CHAR(14) NOT NULL COMMENT 'Record creation timestamp',
    updated_ymdhis CHAR(14) NOT NULL COMMENT 'Record update timestamp',
    is_deleted TINYINT NOT NULL DEFAULT 0 COMMENT '1 = soft deleted',
    deleted_ymdhis CHAR(14) COMMENT 'Deletion timestamp',
    migrated_from_directory VARCHAR(255) COMMENT 'Original directory path for migration tracking',
    PRIMARY KEY (file_id),
    INDEX idx_channel_id (channel_id),
    INDEX idx_file_type (file_type),
    INDEX idx_file_hash (file_hash),
    INDEX idx_is_deleted (is_deleted),
    INDEX idx_upload_ymdhis (upload_ymdhis)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- MIGRATION TRACKING TABLE
-- ============================================================================
-- Tracks migration progress and allows rollback if needed

CREATE TABLE IF NOT EXISTS lupo_filesystem_migration_log (
    log_id BIGINT NOT NULL AUTO_INCREMENT,
    migration_type VARCHAR(50) NOT NULL COMMENT 'agents or channels',
    directory_path VARCHAR(500) NOT NULL COMMENT 'Original directory path',
    entity_type VARCHAR(50) NOT NULL COMMENT 'agent or channel',
    entity_id BIGINT COMMENT 'ID in lupo_agents or lupo_channels',
    status VARCHAR(50) NOT NULL COMMENT 'pending, success, failed, rolled_back',
    files_migrated INT DEFAULT 0 COMMENT 'Number of files migrated',
    error_message TEXT COMMENT 'Error details if failed',
    started_ymdhis CHAR(14) NOT NULL,
    completed_ymdhis CHAR(14),
    PRIMARY KEY (log_id),
    INDEX idx_migration_type (migration_type),
    INDEX idx_status (status),
    INDEX idx_entity (entity_type, entity_id),
    INDEX idx_directory (directory_path(255))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- END OF MIGRATION
-- ============================================================================
-- Next steps:
-- 1. Run this SQL migration
-- 2. Execute PHP script: php scripts/migrate_filesystem_to_db.php
-- 3. Verify data integrity
-- 4. Run cleanup script to remove old directories
