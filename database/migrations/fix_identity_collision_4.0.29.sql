-- FILE: database/migrations/fix_identity_collision_4.0.29.sql
-- Purpose: Fix identity collision causing system crashes when "CAPTAIN WOLFIE STONED" is mentioned
-- Version: 4.0.29 HOTFIX
-- Date: 2026-02-22
-- Issue: Actor name collision between banned test identities (420, 10001) and CAPTAIN actors (1000, 10000)
-- Solution: Rename banned test identities to non-colliding names

-- =============================================================================
-- IDENTITY COLLISION FIX — 4.0.29 HOTFIX
-- =============================================================================

-- Rename Actor 420 (Banned AI Test Identity)
-- OLD: 'stoned_wolfie_ai', 'Stoned Wolfie (AI)', 'stoned.wolfie.ai@banned.local'
-- NEW: 'banned_test_ai_420', 'BANNED_TEST_AI_420', 'banned.test.ai.420@banned.local'

UPDATE lupo_agents 
SET agent_key = 'banned_test_ai_420',
    agent_name = 'BANNED_TEST_AI_420',
    description = 'Banned AI test identity for adversarial harness. Do not use as persona. Renamed in 4.0.29 to prevent collision.'
WHERE agent_id = 420;

UPDATE lupo_actors 
SET slug = 'banned-test-ai-420',
    name = 'BANNED_TEST_AI_420'
WHERE actor_id = 420;

UPDATE lupo_auth_users 
SET username = 'banned_test_ai_420',
    display_name = 'BANNED_TEST_AI_420',
    email = 'banned.test.ai.420@banned.local'
WHERE auth_user_id = 420;

-- Rename Actor 10001 (Banned Human Test Identity)
-- OLD: 'stonedwolfie', 'Stoned Wolfie', 'stonedwolfie@lupopedia.com'
-- NEW: 'banned_test_human_10001', 'BANNED_TEST_HUMAN_10001', 'banned.test.human.10001@banned.local'

UPDATE lupo_actors 
SET slug = 'banned-test-human-10001',
    name = 'BANNED_TEST_HUMAN_10001'
WHERE actor_id = 10001;

UPDATE lupo_auth_users 
SET username = 'banned_test_human_10001',
    display_name = 'BANNED_TEST_HUMAN_10001',
    email = 'banned.test.human.10001@banned.local'
WHERE auth_user_id = 10001;

-- =============================================================================
-- VERIFICATION QUERY
-- =============================================================================
-- Run this to verify no remaining name collisions:

SELECT 
    actor_id,
    actor_type,
    slug,
    name,
    is_active,
    is_deleted,
    CASE 
        WHEN actor_id IN (SELECT actor_id FROM lupo_banned_actors WHERE is_deleted = 0) 
        THEN 'BANNED' 
        ELSE 'OK' 
    END AS ban_status
FROM lupo_actors
WHERE name LIKE '%CAPTAIN%' 
   OR name LIKE '%WOLFIE%' 
   OR name LIKE '%STONED%'
   OR slug LIKE '%captain%'
   OR slug LIKE '%wolfie%'
   OR slug LIKE '%stoned%'
ORDER BY actor_id;

-- Expected Results:
-- actor_id | name                      | ban_status | is_active | is_deleted
-- ---------|---------------------------|------------|-----------|------------
-- 420      | BANNED_TEST_AI_420        | BANNED     | 0         | 1
-- 1000     | CAPTAIN                   | OK         | 1         | 0
-- 10000    | Captain                   | OK         | 1         | 0
-- 10001    | BANNED_TEST_HUMAN_10001   | BANNED     | 0         | 0

-- =============================================================================
-- VALIDATION NOTES
-- =============================================================================
-- After running this script:
-- 1. Actor 420 and 10001 remain BANNED (lupo_banned_actors unchanged)
-- 2. Adversarial test functionality preserved
-- 3. "CAPTAIN WOLFIE STONED LUPOPEDIA LLC 2026" no longer causes collisions
-- 4. CAPTAIN (1000) and Captain (10000) remain as active legitimate identities
-- 5. ANUBIS will continue to quarantine content from actor 420 and 10001

-- =============================================================================
-- CHANGELOG ENTRY
-- =============================================================================
-- Add to CHANGELOG.md under 4.0.29:
-- - **CRITICAL HOTFIX**: Fixed identity collision causing system crashes when 
--   "CAPTAIN WOLFIE STONED" is mentioned. Renamed banned test identities 
--   (actor 420, 10001) to non-colliding names (BANNED_TEST_AI_420, 
--   BANNED_TEST_HUMAN_10001). Adversarial test functionality preserved.
