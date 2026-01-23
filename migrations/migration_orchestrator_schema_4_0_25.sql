-- Migration Orchestrator Schema - Version 4.0.25
-- Creates 8 tables for migration management in lupopedia_orchestration schema

-- Create orchestration schema if not exists
CREATE DATABASE IF NOT EXISTS lupopedia_orchestration;

USE lupopedia_orchestration;

-- migration_batches - Batch tracking with epoch management
CREATE TABLE migration_batches (
    batch_id BIGINT(14) PRIMARY KEY DEFAULT 0,
    batch_name VARCHAR(255) NOT NULL,
    batch_status ENUM('pending', 'running', 'completed', 'failed', 'rolled_back') NOT NULL DEFAULT 'pending',
    epoch_from BIGINT(14) NOT NULL DEFAULT 0,
    epoch_to BIGINT(14) NOT NULL DEFAULT 0,
    total_files INT NOT NULL DEFAULT 0,
    processed_files INT NOT NULL DEFAULT 0,
    failed_files INT NOT NULL DEFAULT 0,
    started_ymdhis BIGINT(14) NOT NULL DEFAULT 0,
    completed_ymdhis BIGINT(14) NOT NULL DEFAULT 0,
    properties JSON,
    created_ymdhis BIGINT(14) NOT NULL DEFAULT 0,
    updated_ymdhis BIGINT(14) NOT NULL DEFAULT 0,
    is_deleted TINYINT(1) NOT NULL DEFAULT 0,
    deleted_ymdhis BIGINT(14) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- migration_files - File-level migration status and dependencies
CREATE TABLE migration_files (
    file_id BIGINT(14) PRIMARY KEY DEFAULT 0,
    batch_id BIGINT(14) NOT NULL DEFAULT 0,
    file_path VARCHAR(512) NOT NULL,
    file_type ENUM('doctrine', 'module', 'agent', 'documentation') NOT NULL,
    file_status ENUM('pending', 'processing', 'completed', 'failed', 'rolled_back') NOT NULL DEFAULT 'pending',
    file_hash VARCHAR(64),
    epoch_from BIGINT(14) NOT NULL DEFAULT 0,
    epoch_to BIGINT(14) NOT NULL DEFAULT 0,
    processing_time_ms INT NOT NULL DEFAULT 0,
    error_message TEXT,
    retry_count INT NOT NULL DEFAULT 0,
    sort_order INT NOT NULL DEFAULT 0,
    properties JSON,
    created_ymdhis BIGINT(14) NOT NULL DEFAULT 0,
    updated_ymdhis BIGINT(14) NOT NULL DEFAULT 0,
    is_deleted TINYINT(1) NOT NULL DEFAULT 0,
    deleted_ymdhis BIGINT(14) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- migration_validation_log - Validation check tracking
CREATE TABLE migration_validation_log (
    validation_id BIGINT(14) PRIMARY KEY DEFAULT 0,
    batch_id BIGINT(14) NOT NULL DEFAULT 0,
    file_id BIGINT(14) NOT NULL DEFAULT 0,
    validation_phase ENUM('pre', 'during', 'post') NOT NULL,
    validation_type VARCHAR(100) NOT NULL,
    validation_status ENUM('passed', 'failed', 'warning') NOT NULL,
    validation_result TEXT,
    execution_time_ms INT NOT NULL DEFAULT 0,
    properties JSON,
    created_ymdhis BIGINT(14) NOT NULL DEFAULT 0,
    updated_ymdhis BIGINT(14) NOT NULL DEFAULT 0,
    is_deleted TINYINT(1) NOT NULL DEFAULT 0,
    deleted_ymdhis BIGINT(14) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- migration_rollback_log - Rollback execution and history
CREATE TABLE migration_rollback_log (
    rollback_id BIGINT(14) PRIMARY KEY DEFAULT 0,
    batch_id BIGINT(14) NOT NULL DEFAULT 0,
    file_id BIGINT(14) NOT NULL DEFAULT 0,
    rollback_classification ENUM('A', 'B', 'C', 'D') NOT NULL,
    rollback_reason VARCHAR(255) NOT NULL,
    rollback_status ENUM('pending', 'running', 'completed', 'failed') NOT NULL DEFAULT 'pending',
    files_affected INT NOT NULL DEFAULT 0,
    rollback_time_ms INT NOT NULL DEFAULT 0,
    error_message TEXT,
    properties JSON,
    created_ymdhis BIGINT(14) NOT NULL DEFAULT 0,
    updated_ymdhis BIGINT(14) NOT NULL DEFAULT 0,
    is_deleted TINYINT(1) NOT NULL DEFAULT 0,
    deleted_ymdhis BIGINT(14) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- migration_dependencies - Cross-file dependency mapping
CREATE TABLE migration_dependencies (
    dependency_id BIGINT(14) PRIMARY KEY DEFAULT 0,
    file_id BIGINT(14) NOT NULL DEFAULT 0,
    depends_on_file_id BIGINT(14) NOT NULL DEFAULT 0,
    dependency_type ENUM('required', 'optional', 'conflict') NOT NULL,
    dependency_description TEXT,
    sort_order INT NOT NULL DEFAULT 0,
    properties JSON,
    created_ymdhis BIGINT(14) NOT NULL DEFAULT 0,
    updated_ymdhis BIGINT(14) NOT NULL DEFAULT 0,
    is_deleted TINYINT(1) NOT NULL DEFAULT 0,
    deleted_ymdhis BIGINT(14) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- migration_system_state - System freeze/thaw management
CREATE TABLE migration_system_state (
    state_id BIGINT(14) PRIMARY KEY DEFAULT 0,
    batch_id BIGINT(14) NOT NULL DEFAULT 0,
    system_status ENUM('normal', 'frozen', 'thawing', 'emergency') NOT NULL DEFAULT 'normal',
    freeze_reason VARCHAR(255),
    affected_components JSON,
    user_message TEXT,
    properties JSON,
    created_ymdhis BIGINT(14) NOT NULL DEFAULT 0,
    updated_ymdhis BIGINT(14) NOT NULL DEFAULT 0,
    is_deleted TINYINT(1) NOT NULL DEFAULT 0,
    deleted_ymdhis BIGINT(14) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- migration_progress - Real-time progress tracking
CREATE TABLE migration_progress (
    progress_id BIGINT(14) PRIMARY KEY DEFAULT 0,
    batch_id BIGINT(14) NOT NULL DEFAULT 0,
    current_phase VARCHAR(100) NOT NULL,
    total_phases INT NOT NULL DEFAULT 0,
    current_phase_index INT NOT NULL DEFAULT 0,
    files_in_phase INT NOT NULL DEFAULT 0,
    files_completed_in_phase INT NOT NULL DEFAULT 0,
    percentage_complete DECIMAL(5,2) NOT NULL DEFAULT 0.00,
    estimated_remaining_seconds INT NOT NULL DEFAULT 0,
    properties JSON,
    created_ymdhis BIGINT(14) NOT NULL DEFAULT 0,
    updated_ymdhis BIGINT(14) NOT NULL DEFAULT 0,
    is_deleted TINYINT(1) NOT NULL DEFAULT 0,
    deleted_ymdhis BIGINT(14) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- migration_alerts - Failure notification and escalation
CREATE TABLE migration_alerts (
    alert_id BIGINT(14) PRIMARY KEY DEFAULT 0,
    batch_id BIGINT(14) NOT NULL DEFAULT 0,
    file_id BIGINT(14) NOT NULL DEFAULT 0,
    alert_type ENUM('error', 'warning', 'info', 'critical') NOT NULL,
    alert_title VARCHAR(255) NOT NULL,
    alert_message TEXT NOT NULL,
    alert_status ENUM('new', 'acknowledged', 'resolved', 'escalated') NOT NULL DEFAULT 'new',
    escalation_level INT NOT NULL DEFAULT 0,
    properties JSON,
    created_ymdhis BIGINT(14) NOT NULL DEFAULT 0,
    updated_ymdhis BIGINT(14) NOT NULL DEFAULT 0,
    is_deleted TINYINT(1) NOT NULL DEFAULT 0,
    deleted_ymdhis BIGINT(14) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
