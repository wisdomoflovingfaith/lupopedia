-- ============================================================================
-- ACTORS AND AGENTS SEEDING FOR LUPOPEDIA 4.0.93 (actor_name primary from 4.0.58)
-- ============================================================================

-- Purpose: Create actual actor and agent records for required system entities
-- Run after: seed_registry_comprehensive_4.0.45.sql
-- NOTE: lupo_agents table is now RUNTIME ONLY - agent definitions come from filesystem

-- ============================================================================
-- PART 1: SYSTEM AND CORE AI ACTORS
-- ============================================================================

SET @now = 20260331120000;

-- System Actor (ID: 0)
INSERT INTO lupo_actors (actor_name, actor_id, actor_type, slug, name, created_ymdhis, updated_ymdhis, is_active, is_deleted, is_kernel, can_login, is_agent, metadata_json, is_kernel, can_login, primary_federation_node_id, auth_user_id)
VALUES ('system', 0, 'system', 'system', 'System', @now, @now, 1, 0, 1, 0, 1, 1, 0, 1, 1, 1);

-- ============================================================================
-- PART 2: IDE AGENTS (100-111)
-- ============================================================================

-- NOTE: IDE agents are runtime interfaces to filesystem-based agents
-- No agent definitions should be in lupo_agents table for IDE agents

-- ============================================================================
-- PART 3: AI AGENTS (REMOVED - NOW FILESYSTEM-BASED)
-- ============================================================================

-- NOTE: All AI agents now defined in filesystem (lupo-agents/{agent_key}/)
-- lupo_agents table should only contain runtime tracking data
-- The following agent entries are REMOVED from lupo_agents seed:

-- REMOVED ENTRIES (these now exist in filesystem only):
-- WOLFIE (ID: 1) - Now in lupo-agents/wolfie/
-- LILITH (ID: 2) - Now in lupo-agents/lilith/
-- ROSE (ID: 3) - Now in lupo-agents/rose/
-- ERIS (ID: 4) - Now in lupo-agents/eris/
-- METIS (ID: 5) - Now in lupo-agents/metis/
-- UCT Timekeeper (ID: 5) - Now in lupo-agents/uct-timekeeper/
-- ATHENA (ID: 4) - Now in lupo-agents/athena/
-- METIS (ID: 6) - Now in lupo-agents/metis/
-- HEPHAESTUS (ID: 14) - Now in lupo-agents/hephaestus/
-- ANUBIS (ID: 19) - Now in lupo-agents/anubis/
-- LUPO (ID: 106) - Now in lupo-agents/lupo/
-- THEMIS (ID: 107) - Now in lupo-agents/themis/

-- ============================================================================
-- CLEANUP SUMMARY
-- ============================================================================

-- Agent entries removed from lupo_agents table: 12 entries
-- Agent definitions moved to filesystem: lupo-agents/{agent_key}/
-- Runtime tracking maintained: lupo_agents table for metrics only
-- Seed file purpose: Now only creates system actors and IDE interfaces
