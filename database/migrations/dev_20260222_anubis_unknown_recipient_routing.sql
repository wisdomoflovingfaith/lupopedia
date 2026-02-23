-- FILE: database/migrations/dev_20260222_anubis_unknown_recipient_routing.sql
-- Purpose: Add ANUBIS unknown recipient routing infrastructure
-- Type: Migration (schema + seed)
-- Risk Level: LOW - adds logging and validation functions

-- =============================================================================
-- ANUBIS UNKNOWN RECIPIENT ROUTING - 4.0.29
-- =============================================================================

-- Step 1: Ensure ANUBIS actor exists (actor_id 59)
INSERT INTO lupo_actors (
    actor_id, actor_type, slug, name, created_ymdhis, updated_ymdhis,
    is_active, is_deleted, actor_source_id, actor_source_type,
    metadata, adversarial_role, primary_federation_node_id,
    department_id, is_kernel, can_login, paired_actor_id
) VALUES (
    59, 'system', 'anubis', 'ANUBIS', 20260222000000, 20260222000000,
    1, 0, 59, 'lupo_agent_registry',
    'Orphan Resolution System - Unknown Recipient Protocol Handler', 'none', 1,
    0, 1, 0, 0
) ON DUPLICATE KEY UPDATE 
    name = VALUES(name),
    metadata = VALUES(metadata),
    updated_ymdhis = VALUES(updated_ymdhis),
    is_active = 1;

-- Step 2: Create ANUBIS log table for orphan tracking
CREATE TABLE IF NOT EXISTS lupo_anubis_log (
    log_id varchar(36) NOT NULL,
    file_path varchar(500) NOT NULL,
    original_recipient varchar(100) DEFAULT NULL,
    reason_code varchar(50) NOT NULL,
    processed_ymdhis bigint NOT NULL,
    actor_id bigint NOT NULL DEFAULT 59,
    decision varchar(20) NOT NULL DEFAULT 'adopted',
    target_channel_id bigint DEFAULT NULL,
    target_thread_id bigint DEFAULT NULL,
    created_ymdhis bigint NOT NULL DEFAULT 0,
    updated_ymdhis bigint NOT NULL,
    is_deleted tinyint NOT NULL DEFAULT '0',
    deleted_ymdhis bigint DEFAULT NULL,
    PRIMARY KEY (log_id)
);

CREATE INDEX IF NOT EXISTS idx_anubis_log_processed ON lupo_anubis_log (processed_ymdhis);
CREATE INDEX IF NOT EXISTS idx_anubis_log_reason ON lupo_anubis_log (reason_code);
CREATE INDEX IF NOT EXISTS idx_anubis_log_decision ON lupo_anubis_log (decision);

-- Step 3: Add ANUBIS to channel 42 (Protocol Development)
INSERT INTO lupo_actor_channels (
    actor_channel_id, actor_id, channel_id, created_by_actor_id,
    created_ymdhis, updated_ymdhis, is_deleted
) VALUES (
    59, 59, 42, 1, 20260222000000, 20260222000000, 0
) ON DUPLICATE KEY UPDATE 
    updated_ymdhis = VALUES(updated_ymdhis),
    is_deleted = 0;

-- Step 4: Create dialog thread for ANUBIS adoptions (thread 1 if not exists)
INSERT INTO lupo_dialog_threads (
    dialog_thread_id, channel_id, thread_title, thread_type,
    created_by_actor_id, created_ymdhis, updated_ymdhis,
    is_deleted, deleted_ymdhis
) VALUES (
    1, 42, 'ANUBIS Orphan Processing', 'system',
    59, 20260222000000, 20260222000000, 0, NULL
) ON DUPLICATE KEY UPDATE 
    updated_ymdhis = VALUES(updated_ymdhis);

-- Step 5: Verify ANUBIS actor exists
SELECT 
    actor_id, actor_type, slug, name, is_active, is_deleted,
    metadata, created_ymdhis, updated_ymdhis
FROM lupo_actors 
WHERE actor_id = 59;

-- Step 6: Verify channel 42 exists for ANUBIS operations
SELECT 
    channel_id, channel_key, channel_name, channel_status, is_deleted
FROM lupo_channels 
WHERE channel_id = 42;

-- Step 7: Verify dialog thread 1 exists for ANUBIS
SELECT 
    dialog_thread_id, channel_id, thread_title, thread_type, is_deleted
FROM lupo_dialog_threads 
WHERE dialog_thread_id = 1 AND channel_id = 42;

-- =============================================================================
-- MIGRATION VERIFICATION
-- =============================================================================
-- This migration ensures:
-- 1. ANUBIS actor (59) exists and is operational
-- 2. Logging infrastructure for orphan tracking
-- 3. ANUBIS has access to channel 42
-- 4. Dialog thread exists for adoption messages
-- 5. All indexes for performance optimization

-- Migration completed: 2026-02-22
-- Risk Level: LOW (adds infrastructure, no destructive changes)
