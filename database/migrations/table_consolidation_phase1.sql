-- Table Consolidation Phase 1 - Logging Tables
-- v4.0.55 Table Optimization
-- Target: Reduce 10 logging tables to 1 unified table (-9 tables)

-- Current logging tables to consolidate:
-- lupo_anubis_deletion_log
-- lupo_anubis_log  
-- lupo_anubis_processing_log
-- lupo_audit_log
-- lupo_auth_audit_log
-- lupo_bans_log
-- lupo_channel_boot_log
-- lupo_event_log
-- lupo_interpretation_log
-- lupo_search_rebuild_log

-- Step 1: Create unified logging table
CREATE TABLE lupo_unified_log (
    log_id BIGINT PRIMARY KEY AUTO_INCREMENT,
    log_type ENUM('anubis_deletion', 'anubis_general', 'anubis_processing', 'audit', 'auth_audit', 'bans', 'channel_boot', 'event', 'interpretation', 'search_rebuild') NOT NULL,
    log_level ENUM('debug', 'info', 'warning', 'error', 'critical') DEFAULT 'info',
    log_message TEXT NOT NULL,
    log_context JSON,
    actor_id INT DEFAULT NULL,
    channel_id INT DEFAULT NULL,
    session_id VARCHAR(128) DEFAULT NULL,
    ip_address VARCHAR(45) DEFAULT NULL,
    user_agent TEXT DEFAULT NULL,
    created_ymdhis BIGINT NOT NULL,
    INDEX idx_log_type_created (log_type, created_ymdhis),
    INDEX idx_actor_log (actor_id, log_type),
    INDEX idx_channel_log (channel_id, log_type),
    INDEX idx_session_log (session_id, log_type),
    INDEX idx_created_ymdhis (created_ymdhis)
);

-- Step 2: Migrate data from existing log tables
-- Note: These migrations should be run in order with proper error handling

-- Migrate lupo_anubis_deletion_log
INSERT INTO lupo_unified_log 
(log_type, log_level, log_message, log_context, actor_id, created_ymdhis)
SELECT 
    'anubis_deletion' as log_type,
    'info' as log_level,
    CONCAT('Deleted file: ', file_path) as log_message,
    JSON_OBJECT('file_path', file_path, 'file_size', file_size, 'deletion_reason', deletion_reason) as log_context,
    actor_id,
    created_ymdhis
FROM lupo_anubis_deletion_log;

-- Migrate lupo_anubis_log
INSERT INTO lupo_unified_log 
(log_type, log_level, log_message, log_context, actor_id, created_ymdhis)
SELECT 
    'anubis_general' as log_type,
    log_level,
    log_message,
    JSON_OBJECT('operation', operation, 'details', details) as log_context,
    actor_id,
    created_ymdhis
FROM lupo_anubis_log;

-- Migrate lupo_anubis_processing_log
INSERT INTO lupo_unified_log 
(log_type, log_level, log_message, log_context, actor_id, created_ymdhis)
SELECT 
    'anubis_processing' as log_type,
    log_level,
    log_message,
    JSON_OBJECT('file_id', file_id, 'processing_step', processing_step, 'status', status) as log_context,
    actor_id,
    created_ymdhis
FROM lupo_anubis_processing_log;

-- Migrate lupo_audit_log
INSERT INTO lupo_unified_log 
(log_type, log_level, log_message, log_context, actor_id, created_ymdhis)
SELECT 
    'audit' as log_type,
    log_level,
    log_message,
    JSON_OBJECT('action', action, 'table_name', table_name, 'record_id', record_id) as log_context,
    actor_id,
    created_ymdhis
FROM lupo_audit_log;

-- Migrate lupo_auth_audit_log
INSERT INTO lupo_unified_log 
(log_type, log_level, log_message, log_context, actor_id, session_id, ip_address, user_agent, created_ymdhis)
SELECT 
    'auth_audit' as log_type,
    log_level,
    log_message,
    JSON_OBJECT('auth_action', auth_action, 'username', username, 'success', success) as log_context,
    actor_id,
    session_id,
    ip_address,
    user_agent,
    created_ymdhis
FROM lupo_auth_audit_log;

-- Migrate lupo_bans_log
INSERT INTO lupo_unified_log 
(log_type, log_level, log_message, log_context, actor_id, ip_address, created_ymdhis)
SELECT 
    'bans' as log_type,
    log_level,
    log_message,
    JSON_OBJECT('ban_type', ban_type, 'reason', reason, 'duration', duration) as log_context,
    actor_id,
    ip_address,
    created_ymdhis
FROM lupo_bans_log;

-- Migrate lupo_channel_boot_log
INSERT INTO lupo_unified_log 
(log_type, log_level, log_message, log_context, actor_id, created_ymdhis)
SELECT 
    'channel_boot' as log_type,
    log_level,
    log_message,
    JSON_OBJECT('boot_step', boot_step, 'channel_id', channel_id, 'status', status) as log_context,
    actor_id,
    created_ymdhis
FROM lupo_channel_boot_log;

-- Migrate lupo_event_log
INSERT INTO lupo_unified_log 
(log_type, log_level, log_message, log_context, actor_id, created_ymdhis)
SELECT 
    'event' as log_type,
    log_level,
    log_message,
    JSON_OBJECT('event_type', event_type, 'event_data', event_data) as log_context,
    actor_id,
    created_ymdhis
FROM lupo_event_log;

-- Migrate lupo_interpretation_log
INSERT INTO lupo_unified_log 
(log_type, log_level, log_message, log_context, actor_id, created_ymdhis)
SELECT 
    'interpretation' as log_type,
    log_level,
    log_message,
    JSON_OBJECT('interpretation_type', interpretation_type, 'input_data', input_data) as log_context,
    actor_id,
    created_ymdhis
FROM lupo_interpretation_log;

-- Migrate lupo_search_rebuild_log
INSERT INTO lupo_unified_log 
(log_type, log_level, log_message, log_context, actor_id, created_ymdhis)
SELECT 
    'search_rebuild' as log_type,
    log_level,
    log_message,
    JSON_OBJECT('rebuild_step', rebuild_step, 'records_processed', records_processed) as log_context,
    actor_id,
    created_ymdhis
FROM lupo_search_rebuild_log;

-- Step 3: Drop old tables (after successful migration verification)
-- Uncomment these lines only after verifying data migration success
/*
DROP TABLE lupo_anubis_deletion_log;
DROP TABLE lupo_anubis_log;
DROP TABLE lupo_anubis_processing_log;
DROP TABLE lupo_audit_log;
DROP TABLE lupo_auth_audit_log;
DROP TABLE lupo_bans_log;
DROP TABLE lupo_channel_boot_log;
DROP TABLE lupo_event_log;
DROP TABLE lupo_interpretation_log;
DROP TABLE lupo_search_rebuild_log;
*/

-- Step 4: Verification query
SELECT 
    log_type,
    COUNT(*) as record_count,
    MIN(created_ymdhis) as earliest_record,
    MAX(created_ymdhis) as latest_record
FROM lupo_unified_log 
GROUP BY log_type 
ORDER BY log_type;

-- Expected result: 10 log types with data from all migrated tables
-- Table reduction: 10 tables → 1 table = -9 tables
