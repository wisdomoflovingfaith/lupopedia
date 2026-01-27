/* 
======================================================================
   PHASE A: SCHEMA FEDERATION - MOVE ORCHESTRATION TABLES
   Moves 22 orchestration tables from core schema to lupopedia_orchestration.
   
   Orchestration tables are those used for:
   - Migration management
   - System monitoring
   - Audit logging
   - Progress tracking
   - Agent orchestration
   - Search rebuild operations
   - Memory debugging
   - Interpretation logging

   This migration:
   - Moves 22 orchestration tables using RENAME TABLE
   - Preserves all data and structure
   - Maintains referential integrity (no FKs, so safe)
   - Updates table locations for application layer

   Version: 4.0.3
   Status: PRE_MIGRATION → STABLE
======================================================================

 by LUPOPEDIA LLC 2026 - CAPTAIN WOLFIE 
   ====================================================================== */

-- ======================================================================
-- MOVE ORCHESTRATION TABLES TO lupopedia_orchestration SCHEMA
-- ======================================================================

-- Migration Management Tables
RENAME TABLE lupopedia.lupo_audit_log TO lupopedia_orchestration.lupo_audit_log;
RENAME TABLE lupopedia.lupo_search_rebuild_log TO lupopedia_orchestration.lupo_search_rebuild_log;
RENAME TABLE lupopedia.lupo_memory_debug_log TO lupopedia_orchestration.lupo_memory_debug_log;
RENAME TABLE lupopedia.lupo_interpretation_log TO lupopedia_orchestration.lupo_interpretation_log;
RENAME TABLE lupopedia.lupo_system_events TO lupopedia_orchestration.lupo_system_events;
RENAME TABLE lupopedia.lupo_system_config TO lupopedia_orchestration.lupo_system_config;

-- Agent Orchestration Tables
RENAME TABLE lupopedia.lupo_agent_context_snapshots TO lupopedia_orchestration.lupo_agent_context_snapshots;
RENAME TABLE lupopedia.lupo_agent_dependencies TO lupopedia_orchestration.lupo_agent_dependencies;
RENAME TABLE lupopedia.lupo_agent_external_events TO lupopedia_orchestration.lupo_agent_external_events;
RENAME TABLE lupopedia.lupo_agent_tool_calls TO lupopedia_orchestration.lupo_agent_tool_calls;
RENAME TABLE lupopedia.lupo_agent_versions TO lupopedia_orchestration.lupo_agent_versions;

-- Memory and Event Management
RENAME TABLE lupopedia.lupo_memory_events TO lupopedia_orchestration.lupo_memory_events;
RENAME TABLE lupopedia.lupo_memory_rollups TO lupopedia_orchestration.lupo_memory_rollups;
RENAME TABLE lupopedia.lupo_anubis_events TO lupopedia_orchestration.lupo_anubis_events;
RENAME TABLE lupopedia.lupo_anubis_orphaned TO lupopedia_orchestration.lupo_anubis_orphaned;
RENAME TABLE lupopedia.lupo_anubis_redirects TO lupopedia_orchestration.lupo_anubis_redirects;

-- API and Rate Limiting
RENAME TABLE lupopedia.lupo_api_rate_limits TO lupopedia_orchestration.lupo_api_rate_limits;
RENAME TABLE lupopedia.lupo_api_token_logs TO lupopedia_orchestration.lupo_api_token_logs;

-- Notifications and Governance
RENAME TABLE lupopedia.lupo_notifications TO lupopedia_orchestration.lupo_notifications;
RENAME TABLE lupopedia.lupo_governance_overrides TO lupopedia_orchestration.lupo_governance_overrides;

-- ======================================================================
-- VERIFICATION: Count tables in orchestration schema
-- ======================================================================

-- Expected: 22 tables in lupopedia_orchestration schema
-- SELECT COUNT(*) as orchestration_table_count 
-- FROM information_schema.tables 
-- WHERE table_schema = 'lupopedia_orchestration';

-- ======================================================================
-- END OF PHASE A ORCHESTRATION TABLE MIGRATION
-- ======================================================================

