/* 
======================================================================
   PHASE A: SCHEMA FEDERATION - ROLLBACK MIGRATION
   Rolls back Phase A schema federation by moving all tables back
   to core schema and dropping the orchestration and ephemeral schemas.
   
   This rollback:
   - Moves 22 orchestration tables back to core schema
   - Moves 12 ephemeral tables back to core schema
   - Drops lupopedia_orchestration schema
   - Drops lupopedia_ephemeral schema
   - Restores original table locations

   Version: 4.0.3
   Status: ROLLBACK
======================================================================

 by LUPOPEDIA LLC 2026 - CAPTAIN WOLFIE 
   ====================================================================== */

-- ======================================================================
-- ROLLBACK: MOVE ORCHESTRATION TABLES BACK TO CORE SCHEMA
-- ======================================================================

RENAME TABLE lupopedia_orchestration.lupo_audit_log TO lupopedia.lupo_audit_log;
RENAME TABLE lupopedia_orchestration.lupo_search_rebuild_log TO lupopedia.lupo_search_rebuild_log;
RENAME TABLE lupopedia_orchestration.lupo_memory_debug_log TO lupopedia.lupo_memory_debug_log;
RENAME TABLE lupopedia_orchestration.lupo_interpretation_log TO lupopedia.lupo_interpretation_log;
RENAME TABLE lupopedia_orchestration.lupo_system_events TO lupopedia.lupo_system_events;
RENAME TABLE lupopedia_orchestration.lupo_system_config TO lupopedia.lupo_system_config;
RENAME TABLE lupopedia_orchestration.lupo_agent_context_snapshots TO lupopedia.lupo_agent_context_snapshots;
RENAME TABLE lupopedia_orchestration.lupo_agent_dependencies TO lupopedia.lupo_agent_dependencies;
RENAME TABLE lupopedia_orchestration.lupo_agent_external_events TO lupopedia.lupo_agent_external_events;
RENAME TABLE lupopedia_orchestration.lupo_agent_tool_calls TO lupopedia.lupo_agent_tool_calls;
RENAME TABLE lupopedia_orchestration.lupo_agent_versions TO lupopedia.lupo_agent_versions;
RENAME TABLE lupopedia_orchestration.lupo_memory_events TO lupopedia.lupo_memory_events;
RENAME TABLE lupopedia_orchestration.lupo_memory_rollups TO lupopedia.lupo_memory_rollups;
RENAME TABLE lupopedia_orchestration.lupo_anibus_events TO lupopedia.lupo_anibus_events;
RENAME TABLE lupopedia_orchestration.lupo_anibus_orphans TO lupopedia.lupo_anibus_orphans;
RENAME TABLE lupopedia_orchestration.lupo_anibus_redirects TO lupopedia.lupo_anibus_redirects;
RENAME TABLE lupopedia_orchestration.lupo_api_rate_limits TO lupopedia.lupo_api_rate_limits;
RENAME TABLE lupopedia_orchestration.lupo_api_token_logs TO lupopedia.lupo_api_token_logs;
RENAME TABLE lupopedia_orchestration.lupo_notifications TO lupopedia.lupo_notifications;
RENAME TABLE lupopedia_orchestration.lupo_governance_overrides TO lupopedia.lupo_governance_overrides;

-- ======================================================================
-- ROLLBACK: MOVE EPHEMERAL TABLES BACK TO CORE SCHEMA
-- ======================================================================

RENAME TABLE lupopedia_ephemeral.lupo_sessions TO lupopedia.lupo_sessions;
RENAME TABLE lupopedia_ephemeral.lupo_analytics_campaign_vars_daily TO lupopedia.lupo_analytics_campaign_vars_daily;
RENAME TABLE lupopedia_ephemeral.lupo_analytics_paths_daily TO lupopedia.lupo_analytics_paths_daily;
RENAME TABLE lupopedia_ephemeral.lupo_analytics_referers_daily TO lupopedia.lupo_analytics_referers_daily;
RENAME TABLE lupopedia_ephemeral.lupo_analytics_visits_daily TO lupopedia.lupo_analytics_visits_daily;
RENAME TABLE lupopedia_ephemeral.lupo_analytics_visits TO lupopedia.lupo_analytics_visits;
RENAME TABLE lupopedia_ephemeral.lupo_api_tokens TO lupopedia.lupo_api_tokens;
RENAME TABLE lupopedia_ephemeral.lupo_api_clients TO lupopedia.lupo_api_clients;
RENAME TABLE lupopedia_ephemeral.lupo_api_webhooks TO lupopedia.lupo_api_webhooks;
RENAME TABLE lupopedia_ephemeral.lupo_narrative_fragments TO lupopedia.lupo_narrative_fragments;
RENAME TABLE lupopedia_ephemeral.lupo_document_chunks TO lupopedia.lupo_document_chunks;

-- ======================================================================
-- ROLLBACK: DROP FEDERATED SCHEMAS
-- ======================================================================

DROP SCHEMA IF EXISTS lupopedia_orchestration;
DROP SCHEMA IF EXISTS lupopedia_ephemeral;

-- ======================================================================
-- END OF PHASE A ROLLBACK
-- ======================================================================
