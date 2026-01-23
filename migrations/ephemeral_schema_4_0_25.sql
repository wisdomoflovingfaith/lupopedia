-- Ephemeral Schema - Version 4.0.25
-- Creates 5 tables for ephemeral data management in lupopedia_ephemeral schema

-- Create ephemeral schema if not exists
CREATE DATABASE IF NOT EXISTS lupopedia_ephemeral;

USE lupopedia_ephemeral;

-- lupo_sessions - Stores ephemeral session data
CREATE TABLE lupo_sessions (
    session_id BIGINT(14) PRIMARY KEY DEFAULT 0,
    session_key VARCHAR(255) NOT NULL,
    session_data JSON,
    user_agent TEXT,
    ip_address VARCHAR(45),
    expires_ymdhis BIGINT(14) NOT NULL DEFAULT 0,
    last_accessed_ymdhis BIGINT(14) NOT NULL DEFAULT 0,
    properties JSON,
    created_ymdhis BIGINT(14) NOT NULL DEFAULT 0,
    updated_ymdhis BIGINT(14) NOT NULL DEFAULT 0,
    is_deleted TINYINT(1) NOT NULL DEFAULT 0,
    deleted_ymdhis BIGINT(14) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- lupo_cache - Stores ephemeral cache data
CREATE TABLE lupo_cache (
    cache_id BIGINT(14) PRIMARY KEY DEFAULT 0,
    cache_key VARCHAR(255) NOT NULL,
    cache_group VARCHAR(100),
    cache_data JSON,
    cache_size INT NOT NULL DEFAULT 0,
    expires_ymdhis BIGINT(14) NOT NULL DEFAULT 0,
    hit_count INT NOT NULL DEFAULT 0,
    last_hit_ymdhis BIGINT(14) NOT NULL DEFAULT 0,
    properties JSON,
    created_ymdhis BIGINT(14) NOT NULL DEFAULT 0,
    updated_ymdhis BIGINT(14) NOT NULL DEFAULT 0,
    is_deleted TINYINT(1) NOT NULL DEFAULT 0,
    deleted_ymdhis BIGINT(14) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- lupo_temp_data - Stores temporary data that can be safely purged
CREATE TABLE lupo_temp_data (
    temp_id BIGINT(14) PRIMARY KEY DEFAULT 0,
    temp_key VARCHAR(255) NOT NULL,
    temp_type VARCHAR(100),
    temp_data JSON,
    temp_size INT NOT NULL DEFAULT 0,
    expires_ymdhis BIGINT(14) NOT NULL DEFAULT 0,
    purge_priority ENUM('low', 'medium', 'high', 'critical') NOT NULL DEFAULT 'medium',
    properties JSON,
    created_ymdhis BIGINT(14) NOT NULL DEFAULT 0,
    updated_ymdhis BIGINT(14) NOT NULL DEFAULT 0,
    is_deleted TINYINT(1) NOT NULL DEFAULT 0,
    deleted_ymdhis BIGINT(14) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- lupo_job_queue - Stores ephemeral job queue data for background tasks
CREATE TABLE lupo_job_queue (
    job_id BIGINT(14) PRIMARY KEY DEFAULT 0,
    job_type VARCHAR(100) NOT NULL,
    job_status ENUM('pending', 'running', 'completed', 'failed', 'cancelled') NOT NULL DEFAULT 'pending',
    job_data JSON,
    job_priority ENUM('low', 'medium', 'high', 'critical') NOT NULL DEFAULT 'medium',
    max_retries INT NOT NULL DEFAULT 3,
    retry_count INT NOT NULL DEFAULT 0,
    scheduled_ymdhis BIGINT(14) NOT NULL DEFAULT 0,
    started_ymdhis BIGINT(14) NOT NULL DEFAULT 0,
    completed_ymdhis BIGINT(14) NOT NULL DEFAULT 0,
    error_message TEXT,
    properties JSON,
    created_ymdhis BIGINT(14) NOT NULL DEFAULT 0,
    updated_ymdhis BIGINT(14) NOT NULL DEFAULT 0,
    is_deleted TINYINT(1) NOT NULL DEFAULT 0,
    deleted_ymdhis BIGINT(14) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- lupo_locks - Stores ephemeral distributed lock data
CREATE TABLE lupo_locks (
    lock_id BIGINT(14) PRIMARY KEY DEFAULT 0,
    lock_key VARCHAR(255) NOT NULL,
    lock_type VARCHAR(100) NOT NULL,
    lock_status ENUM('locked', 'unlocked', 'expired') NOT NULL DEFAULT 'locked',
    lock_holder VARCHAR(100),
    lock_expires_ymdhis BIGINT(14) NOT NULL DEFAULT 0,
    lock_timeout_seconds INT NOT NULL DEFAULT 300,
    properties JSON,
    created_ymdhis BIGINT(14) NOT NULL DEFAULT 0,
    updated_ymdhis BIGINT(14) NOT NULL DEFAULT 0,
    is_deleted TINYINT(1) NOT NULL DEFAULT 0,
    deleted_ymdhis BIGINT(14) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
