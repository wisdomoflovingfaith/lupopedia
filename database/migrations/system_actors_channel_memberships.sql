-- ======================================================================
-- System Actors Channel Memberships
-- Generated: 2026-01-25
-- Purpose: Add channel memberships for system actors
-- Must run after system_actors_manifest_corrected.sql
-- Assumes actor_ids: WOLFIE (4), LILITH (5), LUPOPEDIA (6) - adjust if different
-- ======================================================================

-- Note: Run this after the actors are inserted and you know their actual actor_ids
-- Use SELECT actor_id, slug FROM lupo_actors WHERE slug IN ('wolfie', 'lilith', 'lupopedia');

-- Placeholder variables - replace with actual actor_ids after running the actor insert
SET @wolfie_actor_id = 4;  -- Replace with actual WOLFIE actor_id
SET @lilith_actor_id = 5;  -- Replace with actual LILITH actor_id  
SET @lupopedia_actor_id = 6; -- Replace with actual LUPOPEDIA actor_id

-- ======================================================================
-- Method 1: Using lupo_actor_channels table (if it exists)
-- ======================================================================

/*
INSERT IGNORE INTO lupo_actor_channels (
    actor_id,
    channel_number,
    role,
    joined_ymdhis,
    is_active,
    created_ymdhis,
    updated_ymdhis
)
VALUES 
-- WOLFIE default channels: [5100, 5101, 5103, 5104, 5109, 5112, 5114]
(@wolfie_actor_id, 5100, 'architect', 20260125194100, 1, 20260125194100, 20260125194100),
(@wolfie_actor_id, 5101, 'architect', 20260125194100, 1, 20260125194100, 20260125194100),
(@wolfie_actor_id, 5103, 'architect', 20260125194100, 1, 20260125194100, 20260125194100),
(@wolfie_actor_id, 5104, 'architect', 20260125194100, 1, 20260125194100, 20260125194100),
(@wolfie_actor_id, 5109, 'architect', 20260125194100, 1, 20260125194100, 20260125194100),
(@wolfie_actor_id, 5112, 'architect', 20260125194100, 1, 20260125194100, 20260125194100),
(@wolfie_actor_id, 5114, 'architect', 20260125194100, 1, 20260125194100, 20260125194100),

-- LILITH default channels: [5101, 5105, 5113, 5119, 5129]
(@lilith_actor_id, 5101, 'interrogator', 20260125194100, 1, 20260125194100, 20260125194100),
(@lilith_actor_id, 5105, 'interrogator', 20260125194100, 1, 20260125194100, 20260125194100),
(@lilith_actor_id, 5113, 'interrogator', 20260125194100, 1, 20260125194100, 20260125194100),
(@lilith_actor_id, 5119, 'interrogator', 20260125194100, 1, 20260125194100, 20260125194100),
(@lilith_actor_id, 5129, 'interrogator', 20260125194100, 1, 20260125194100, 20260125194100),

-- LUPOPEDIA default channels: [5100, 5102, 5106, 5107, 5114, 5121, 5126, 5130]
(@lupopedia_actor_id, 5100, 'system', 20260125194100, 1, 20260125194100, 20260125194100),
(@lupopedia_actor_id, 5102, 'system', 20260125194100, 1, 20260125194100, 20260125194100),
(@lupopedia_actor_id, 5106, 'system', 20260125194100, 1, 20260125194100, 20260125194100),
(@lupopedia_actor_id, 5107, 'system', 20260125194100, 1, 20260125194100, 20260125194100),
(@lupopedia_actor_id, 5114, 'system', 20260125194100, 1, 20260125194100, 20260125194100),
(@lupopedia_actor_id, 5121, 'system', 20260125194100, 1, 20260125194100, 20260125194100),
(@lupopedia_actor_id, 5126, 'system', 20260125194100, 1, 20260125194100, 20260125194100),
(@lupopedia_actor_id, 5130, 'system', 20260125194100, 1, 20260125194100, 20260125194100);
*/

-- ======================================================================
-- Method 2: Using lupo_actor_channel_roles table (if it exists)
-- ======================================================================

/*
INSERT IGNORE INTO lupo_actor_channel_roles (
    actor_id,
    channel_number,
    role,
    created_ymdhis,
    updated_ymdhis
)
VALUES 
-- Same channel assignments as above, using this table structure
-- (Field names may vary - check actual schema after TOON generation)
*/

-- ======================================================================
-- Method 3: Update actor metadata with channel assignments (fallback)
-- ======================================================================

-- This approach stores channel assignments in the actor's metadata JSON
-- Already done in the actor insert, but included here for completeness

UPDATE lupo_actors 
SET metadata = JSON_SET(
    metadata, 
    '$.channels', 
    JSON_ARRAY(5100, 5101, 5103, 5104, 5109, 5112, 5114)
)
WHERE slug = 'wolfie';

UPDATE lupo_actors 
SET metadata = JSON_SET(
    metadata, 
    '$.channels', 
    JSON_ARRAY(5101, 5105, 5113, 5119, 5129)
)
WHERE slug = 'lilith';

UPDATE lupo_actors 
SET metadata = JSON_SET(
    metadata, 
    '$.channels', 
    JSON_ARRAY(5100, 5102, 5106, 5107, 5114, 5121, 5126, 5130)
)
WHERE slug = 'lupopedia';

-- ======================================================================
-- Instructions:
-- 1. First run: python regenerate_toons_docs.py to generate current TOON files
-- 2. Check actual table schemas in the generated TOON files
-- 3. Run system_actors_manifest_corrected.sql to create actors
-- 4. Get actual actor_ids from: SELECT actor_id, slug FROM lupo_actors WHERE slug IN ('wolfie', 'lilith', 'lupopedia')
-- 5. Update the @wolfie_actor_id, @lilith_actor_id, @lupopedia_actor_id variables above
-- 6. Uncomment and run the appropriate INSERT statements based on actual table structure
-- ======================================================================
