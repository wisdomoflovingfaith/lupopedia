-- ============================================================
-- ACTOR REGISTRY VERIFICATION - LUPOPEDIA 4.0.26
-- ============================================================
-- Purpose: Verify all active IDE and AI agents after minimal seed
-- Run this after: seed_minimal_4.0.26.sql
-- ============================================================

SET @now = 20260222000000;

-- ============================================================
-- 1. VERIFY WARP IDE (actor 2039)
-- ============================================================
SELECT 'Warp IDE Verification' AS check_name;
SELECT actor_id, name, actor_type, is_active, paired_actor_id, created_ymdhis
FROM lupo_actors
WHERE actor_id = 2039;

-- ============================================================
-- 2. VERIFY ALL LINKED AGENTS (paired_actor_id = 10000)
-- ============================================================
SELECT 'All Agents Linked to Human 10000' AS check_name;
SELECT actor_id, name, actor_type, paired_actor_id
FROM lupo_actors
WHERE paired_actor_id = 10000
ORDER BY actor_id;

-- ============================================================
-- 3. VERIFY CHANNEL 42 MEMBERSHIP (for actor 2039)
-- ============================================================
SELECT 'Warp IDE Channel Memberships' AS check_name;
SELECT ac.actor_channel_id, ac.actor_id, ac.channel_id, c.channel_name, ac.status
FROM lupo_actor_channels ac
JOIN lupo_channels c ON ac.channel_id = c.channel_id
WHERE ac.actor_id = 2039
ORDER BY ac.channel_id;

-- ============================================================
-- 4. COMPLETE ACTOR VERIFICATION REPORT
-- ============================================================
SELECT 'Actor Summary Report' AS check_name;
SELECT
    COUNT(*) AS total_actors,
    SUM(CASE WHEN actor_type = 'system_tool' AND is_active = 1 THEN 1 ELSE 0 END) AS active_ides,
    SUM(CASE WHEN actor_type = 'external_ai' AND is_active = 1 THEN 1 ELSE 0 END) AS active_ais,
    SUM(CASE WHEN paired_actor_id = 10000 THEN 1 ELSE 0 END) AS linked_to_10000,
    SUM(CASE WHEN actor_id = 2036 THEN 1 ELSE 0 END) AS copilot_present,
    SUM(CASE WHEN actor_id = 2037 THEN 1 ELSE 0 END) AS lexa_present,
    SUM(CASE WHEN actor_id = 2038 THEN 1 ELSE 0 END) AS lilith_present,
    SUM(CASE WHEN actor_id = 2039 THEN 1 ELSE 0 END) AS warp_present,
    SUM(CASE WHEN actor_id = 2040 THEN 1 ELSE 0 END) AS windsurf_present
FROM lupo_actors;

-- ============================================================
-- 5. VERIFY ALL CHANNELS
-- ============================================================
SELECT 'All Channels' AS check_name;
SELECT channel_id, channel_name, channel_type, status_flag, is_kernel
FROM lupo_channels
WHERE is_deleted = 0
ORDER BY channel_id;

-- ============================================================
-- 6. VERIFY REGISTRY ENTRIES
-- ============================================================
SELECT 'Registry Entries for Active Agents' AS check_name;
SELECT entity_type, entity_index_id, federation_node_id, reserved_ymdhis
FROM lupo_registry
WHERE entity_type = 'actor' 
  AND entity_index_id IN (0, 1, 2, 2036, 2037, 2038, 2039, 2040)
ORDER BY entity_index_id;

-- ============================================================
-- 7. VERIFY DEPARTMENT MEMBERSHIPS
-- ============================================================
SELECT 'Department Memberships for Active Agents' AS check_name;
SELECT ad.actor_department_id, ad.actor_id, a.name, ad.department_id, ad.title
FROM lupo_actor_departments ad
JOIN lupo_actors a ON ad.actor_id = a.actor_id
WHERE ad.actor_id IN (0, 1, 2, 2036, 2037, 2038, 2039, 2040)
  AND ad.is_deleted = 0
ORDER BY ad.actor_id;

-- ============================================================
-- 8. VERIFY DEPARTMENT ROLES
-- ============================================================
SELECT 'Department Roles for Active Agents' AS check_name;
SELECT dr.department_role_id, dr.actor_id, a.name, dr.department_id, dr.role_key
FROM lupo_department_roles dr
JOIN lupo_actors a ON dr.actor_id = a.actor_id
WHERE dr.actor_id IN (0, 1, 2, 2036, 2037, 2038, 2039, 2040)
  AND dr.is_deleted = 0
ORDER BY dr.actor_id;

-- ============================================================
-- 9. CHANNEL MEMBERSHIP COUNTS
-- ============================================================
SELECT 'Channel Membership Counts' AS check_name;
SELECT 
    c.channel_id,
    c.channel_name,
    COUNT(ac.actor_channel_id) AS member_count
FROM lupo_channels c
LEFT JOIN lupo_actor_channels ac ON c.channel_id = ac.channel_id AND ac.is_deleted = 0
WHERE c.is_deleted = 0
GROUP BY c.channel_id, c.channel_name
ORDER BY c.channel_id;

-- ============================================================
-- 10. EXPECTED RESULTS SUMMARY
-- ============================================================
-- Expected values for seed_minimal_4.0.26.sql:
-- total_actors: 8 (0, 1, 2, 2036, 2037, 2038, 2039, 2040)
-- active_ides: 2 (2039 Warp, 2040 Windsurf)
-- active_ais: 3 (2036 Copilot, 2037 LEXA, 2038 LILITH)
-- linked_to_10000: 5 (2036, 2037, 2038, 2039, 2040)
-- All *_present: 1 for each agent
-- Channels: 6 channels (0, 1, 42, 51, 420, 666)
-- Registry entries: 8 actor registrations
-- Department memberships: 8 (all in Department 0)
-- Department roles: 8 (admin, admin, captain, member x5)
-- ============================================================
