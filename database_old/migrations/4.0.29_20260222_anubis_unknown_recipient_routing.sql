-- FILE: database/migrations/dev_20260222_anubis_unknown_recipient_routing.sql
-- Purpose: Add ANUBIS unknown recipient routing infrastructure
-- Type: Migration (schema + seed)
-- Risk Level: LOW - adds logging and validation functions

-- =============================================================================
-- ANUBIS UNKNOWN RECIPIENT ROUTING - 4.0.29
-- =============================================================================

-- Step 1: Ensure ANUBIS actor exists (actor_id 19)
INSERT INTO lupo_actors (
    actor_id, actor_type, slug, name, created_ymdhis, updated_ymdhis,
    is_active, is_deleted, actor_source_id, actor_source_type,
    metadata, adversarial_role, primary_federation_node_id,
    department_id, is_kernel, can_login, paired_actor_id
) VALUES (
    19, 'system', 'anubis', 'ANUBIS', 20260222000000, 20260222000000,
    1, 0, 19, 'lupo_agent_registry',
    'Orphan Resolution System - Unknown Recipient Protocol Handler', 'none', 1,
    0, 1, 0, 0
) ON DUPLICATE KEY UPDATE 
    name = VALUES(name),
    metadata = VALUES(metadata),
    updated_ymdhis = VALUES(updated_ymdhis),
    is_active = 1;

-- Step 2: Create ANUBIS log table for orphan tracking
CREATE TABLE IF NOT EXISTS lupo_anubis_log (
  anubis_log_id bigint NOT NULL,
  event_type varchar(64) NOT NULL,
  severity varchar(20) NOT NULL DEFAULT 'normal',
  source_table varchar(64) DEFAULT NULL,
  source_id bigint DEFAULT NULL,
  file_path_from_root varchar(255) DEFAULT NULL,
  context_json json DEFAULT NULL,
  status varchar(64) NOT NULL DEFAULT 'Pending',
  assigned_to_actor_id bigint NOT NULL DEFAULT 19,
  resolution_ymdhis bigint DEFAULT NULL,
  resolution_summary text,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL DEFAULT 0,
  is_deleted tinyint NOT NULL DEFAULT 0,
  deleted_ymdhis bigint DEFAULT NULL,
  PRIMARY KEY (anubis_log_id)
);

CREATE INDEX lupo_anubis_log_idx_event_type ON lupo_anubis_log (event_type);
CREATE INDEX lupo_anubis_log_idx_source_id ON lupo_anubis_log (source_id);
CREATE INDEX lupo_anubis_log_idx_source_table ON lupo_anubis_log (source_table);
CREATE INDEX lupo_anubis_log_idx_file_path ON lupo_anubis_log (file_path_from_root);
CREATE INDEX lupo_anubis_log_idx_assigned_actor ON lupo_anubis_log (assigned_to_actor_id);
CREATE INDEX lupo_anubis_log_idx_status ON lupo_anubis_log (status);
CREATE INDEX lupo_anubis_log_idx_created ON lupo_anubis_log (created_ymdhis);

-- Step 3: Add ANUBIS to channel 42 (Protocol Development)
INSERT INTO lupo_actor_channels (
    actor_channel_id, actor_id, channel_id, created_by_actor_id,
    created_ymdhis, updated_ymdhis, is_deleted
) VALUES (
    19, 19, 42, 1, 20260222000000, 20260222000000, 0
) ON DUPLICATE KEY UPDATE 
    updated_ymdhis = VALUES(updated_ymdhis),
    is_deleted = 0;

-- Step 4: Create dialog thread for ANUBIS adoptions (thread 1 if not exists)
INSERT INTO lupo_dialog_threads (
    dialog_thread_id, channel_id, title, status,
    created_by_actor_id, created_ymdhis, updated_ymdhis,
    is_deleted, deleted_ymdhis
) VALUES (
    1, 42, 'ANUBIS Orphan Processing', 'Open',
    19, 20260222000000, 20260222000000, 0, NULL
) ON DUPLICATE KEY UPDATE 
    updated_ymdhis = VALUES(updated_ymdhis);

-- Step 5: Verify ANUBIS actor exists
SELECT 
    actor_id, actor_type, slug, name, is_active, is_deleted,
    metadata, created_ymdhis, updated_ymdhis
FROM lupo_actors 
WHERE actor_id = 19;

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
-- 1. ANUBIS actor (19) exists and is operational
-- 2. Logging infrastructure for orphan tracking
-- 3. ANUBIS has access to channel 42
-- 4. Dialog thread exists for adoption messages
-- 5. All indexes for performance optimization

-- Migration completed: 2026-02-22
-- Risk Level: LOW (adds infrastructure, no destructive changes)
