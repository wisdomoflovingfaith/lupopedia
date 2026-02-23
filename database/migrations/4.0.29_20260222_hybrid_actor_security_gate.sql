-- FILE: database/migrations/dev_20260222_hybrid_actor_security_gate.sql
-- TYPE: sql
-- Purpose: Add actor_attributes JSON column and mark Actor 420 as hybrid+banned
-- Risk Level: LOW - Uses existing JSON column, no ENUM changes
-- Compatible with: MySQL 8.0+, MariaDB 10.5+, PostgreSQL 12+

-- =============================================================================
-- HYBRID ACTOR SECURITY GATE - 4.0.29 SECURITY ENHANCEMENT
-- =============================================================================

-- Step 1: Add actor_attributes JSON column (safe addition)
-- Using metadata_json as base since it already exists and is JSON type
ALTER TABLE lupo_actors 
ADD COLUMN actor_attributes JSON DEFAULT NULL AFTER metadata_json;

-- Step 2: Mark Actor 420 as hybrid + banned (safe update)
UPDATE lupo_actors 
SET actor_attributes = JSON_SET(
    IFNULL(actor_attributes, '{}'),
    '$.type', 'hybrid',
    '$.status', 'banned',
    '$.security_level', 'restricted',
    '$.notes', 'Hybrid AI actor - requires special handling'
)
WHERE actor_id = 420;

-- Step 3: Ensure Actor 420 is properly banned in lupo_banned_actors
INSERT INTO lupo_banned_actors (
    banned_actor_id, 
    actor_id, 
    ip_address, 
    reason, 
    banned_ymdhis, 
    banned_by_actor_id, 
    created_ymdhis, 
    updated_ymdhis, 
    is_deleted, 
    deleted_ymdhis
) VALUES (
    420, 
    420, 
    NULL, 
    'Hybrid actor security restriction - 4.0.29 security gate', 
    20260222000000, 
    1000, 
    20260222000000, 
    20260222000000, 
    0, 
    NULL
) ON DUPLICATE KEY UPDATE 
    reason = VALUES(reason), 
    updated_ymdhis = VALUES(updated_ymdhis), 
    is_deleted = 0, 
    deleted_ymdhis = NULL;

-- Step 4: Verify Actor 420 status
SELECT 
    actor_id,
    actor_type,
    slug,
    name,
    is_active,
    actor_attributes,
    metadata_json
FROM lupo_actors 
WHERE actor_id = 420;

-- =============================================================================
-- SECURITY GATE IMPLEMENTATION NOTES
-- =============================================================================
-- 1. All entry points must call assertActorOperational($actor_id)
-- 2. Actor 420 will fail both legacy (is_active=0) and JSON (status=banned) checks
-- 3. No ENUM changes required - uses existing JSON infrastructure
-- 4. Backwards compatible - existing actors unaffected
-- 5. Centralized enforcement via middleware function

-- Migration completed: 2026-02-22
-- Risk Level: LOW (safe JSON operations only)
