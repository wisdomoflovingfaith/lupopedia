-- ============================================================================
-- Migration: Kapu Protocol Tables
-- Date: 2026-01-30
-- Purpose: Add support for operator self-care (kapu) protocol
-- ============================================================================

-- Table: lupo_operator_kapu_log
-- Logs kapu invocations (operator self-care requests)
CREATE TABLE IF NOT EXISTS lupo_operator_kapu_log (
    kapu_log_id BIGINT NOT NULL AUTO_INCREMENT,
    operator_id BIGINT NOT NULL,
    reason TEXT,
    invoked_ymdhis CHAR(14) NOT NULL,
    released_ymdhis CHAR(14),
    created_ymdhis CHAR(14) NOT NULL,
    updated_ymdhis CHAR(14) NOT NULL,
    PRIMARY KEY (kapu_log_id),
    INDEX idx_operator (operator_id),
    INDEX idx_invoked (invoked_ymdhis)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: lupo_operator_escalations
-- Tracks chat escalations (including kapu-triggered escalations)
CREATE TABLE IF NOT EXISTS lupo_operator_escalations (
    escalation_id BIGINT NOT NULL AUTO_INCREMENT,
    operator_id BIGINT NOT NULL,
    thread_id BIGINT NOT NULL,
    escalation_reason VARCHAR(64) NOT NULL,
    priority VARCHAR(16) NOT NULL DEFAULT 'medium',
    status VARCHAR(16) NOT NULL DEFAULT 'pending',
    assigned_to_operator_id BIGINT,
    created_ymdhis CHAR(14) NOT NULL,
    updated_ymdhis CHAR(14) NOT NULL,
    resolved_ymdhis CHAR(14),
    PRIMARY KEY (escalation_id),
    INDEX idx_operator (operator_id),
    INDEX idx_thread (thread_id),
    INDEX idx_status (status),
    INDEX idx_priority (priority)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Update operator_status to allow 'kapu' status
-- (No schema change needed - status is VARCHAR, already supports arbitrary values)

-- ============================================================================
-- End of Migration
-- ============================================================================
