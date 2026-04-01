-- ============================================================================
-- CONSOLIDATED SEED: Lupopedia (seed_lupopedia_4_1_0.sql)
-- ============================================================================

-- Purpose: Consolidated seed file for Lupopedia 4.0.93 installation
-- NOTE: Agent definitions moved to filesystem - lupo_agents table now runtime-only

-- ============================================================================
-- CLEANUP SUMMARY
-- ============================================================================

-- REMOVED AGENT ENTRIES FROM lupo_agents TABLE:
-- All agent definitions now exist in filesystem (lupo-agents/{agent_key}/)
-- lupo_agents table should only contain runtime tracking data

-- REMOVED ENTRIES (now filesystem-based only):
-- WOLFIE (ID: 1) → lupo-agents/wolfie/
-- LILITH (ID: 2) → lupo-agents/lilith/
-- ROSE (ID: 3) → lupo-agents/rose/
-- ERIS (ID: 4) → lupo-agents/eris/
-- METIS (ID: 5) → lupo-agents/metis/
-- UCT Timekeeper (ID: 5) → lupo-agents/uct-timekeeper/
-- ATHENA (ID: 4) → lupo-agents/athena/
-- METIS (ID: 6) → lupo-agents/metis/
-- HEPHAESTUS (ID: 14) → lupo-agents/hephaestus/
-- ANUBIS (ID: 19) → lupo-agents/anubis/
-- LUPO (ID: 106) → lupo-agents/lupo/
-- THEMIS (ID: 107) → lupo-agents/themis/

-- ============================================================================
-- CLEANED FILE CONTENTS BELOW
-- ============================================================================

-- All actor and system entity entries preserved
-- All IDE agent entries preserved  
-- All registry entries preserved
-- All department and channel entries preserved
-- Only agent definition entries removed from lupo_agents table

-- ============================================================================
-- BEGIN FILE: seed_registry_comprehensive_4.0.45.sql
