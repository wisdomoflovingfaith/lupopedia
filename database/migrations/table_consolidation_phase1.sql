-- Phase 1: Logging Consolidation
-- Target: Merge 10 core logging tables into lupo_unified_log
-- Reduced to ≤218 tables target.

-- Create Unified Log Table
CREATE TABLE IF NOT EXISTS lupo_unified_log (
    log_id BIGINT NOT NULL,
    actor_id BIGINT NOT NULL DEFAULT 0,
    channel_id BIGINT NOT NULL DEFAULT 0,
    log_type VARCHAR(64) NOT NULL,
    log_subtype VARCHAR(100),
    log_level VARCHAR(32) NOT NULL DEFAULT 'info',
    message TEXT NOT NULL,
    context_json TEXT,
    created_ymdhis BIGINT NOT NULL,
    is_deleted TINYINT NOT NULL DEFAULT 0,
    deleted_ymdhis BIGINT DEFAULT 0,
    PRIMARY KEY (log_id)
);

-- Index for performance
CREATE INDEX idx_unified_log_type ON lupo_unified_log (log_type, log_subtype);
CREATE INDEX idx_unified_log_created ON lupo_unified_log (created_ymdhis);
CREATE INDEX idx_unified_log_actor_channel ON lupo_unified_log (actor_id, channel_id);

-- Migration Data (INSERT SELECT)
-- Note: Mapping varied schemas into unified columns

-- 1. System Logs
INSERT INTO lupo_unified_log (log_id, actor_id, log_type, log_subtype, log_level, message, context_json, created_ymdhis)
SELECT log_id, 0, 'system', event_type, severity, message, context_json, created_ymdhis FROM lupo_system_logs;

-- 2. System Events
INSERT INTO lupo_unified_log (log_id, actor_id, log_type, log_subtype, log_level, message, context_json, created_ymdhis)
SELECT system_event_id, actor_id, 'system_event', event_type, 'info', event_message, event_context, created_ymdhis FROM lupo_system_events;

-- 3. Task Events
INSERT INTO lupo_unified_log (log_id, actor_id, log_type, log_subtype, log_level, message, context_json, created_ymdhis)
SELECT event_id, actor_id, 'task', event_type, 'info', event_type, context_data, created_ymdhis FROM lupo_task_events;

-- 4. Meta Log Events
INSERT INTO lupo_unified_log (log_id, actor_id, log_type, log_subtype, log_level, message, context_json, created_ymdhis)
SELECT event_id, actor_id, 'meta', event_type, 'info', event_type, context_data, created_ymdhis FROM lupo_meta_log_events;

-- 5. Session Events
INSERT INTO lupo_unified_log (log_id, actor_id, log_type, log_subtype, log_level, message, context_json, created_ymdhis)
SELECT event_id, actor_id, 'session', event_type, 'info', event_type, context_data, created_ymdhis FROM lupo_session_events;

-- 6. Memory Events
INSERT INTO lupo_unified_log (log_id, actor_id, log_type, log_subtype, log_level, message, context_json, created_ymdhis)
SELECT event_id, actor_id, 'memory', event_type, 'info', event_type, context_data, created_ymdhis FROM lupo_memory_events;

-- 7. Tab Events
INSERT INTO lupo_unified_log (log_id, actor_id, log_type, log_subtype, log_level, message, context_json, created_ymdhis)
SELECT event_id, actor_id, 'tab', event_type, 'info', event_type, context_data, created_ymdhis FROM lupo_tab_events;

-- 8. World Events
INSERT INTO lupo_unified_log (log_id, actor_id, log_type, log_subtype, log_level, message, context_json, created_ymdhis)
SELECT event_id, actor_id, 'world', event_type, 'info', event_type, context_data, created_ymdhis FROM lupo_world_events;

-- 9. Actor Events
INSERT INTO lupo_unified_log (log_id, actor_id, log_type, log_subtype, log_level, message, context_json, created_ymdhis)
SELECT event_id, actor_id, 'actor_event', event_type, 'info', event_type, context_data, created_ymdhis FROM lupo_actor_events;

-- 10. Event Log (fallback)
INSERT INTO lupo_unified_log (log_id, actor_id, log_type, log_subtype, log_level, message, context_json, created_ymdhis)
SELECT log_id, actor_id, 'audit', event_type, 'info', event_type, context_data, created_ymdhis FROM lupo_event_log;

-- DROP OLD TABLES (Execution Phase)
DROP TABLE IF EXISTS lupo_system_logs;
DROP TABLE IF EXISTS lupo_system_events;
DROP TABLE IF EXISTS lupo_task_events;
DROP TABLE IF EXISTS lupo_meta_log_events;
DROP TABLE IF EXISTS lupo_session_events;
DROP TABLE IF EXISTS lupo_memory_events;
DROP TABLE IF EXISTS lupo_tab_events;
DROP TABLE IF EXISTS lupo_world_events;
DROP TABLE IF EXISTS lupo_actor_events;
DROP TABLE IF EXISTS lupo_event_log;
