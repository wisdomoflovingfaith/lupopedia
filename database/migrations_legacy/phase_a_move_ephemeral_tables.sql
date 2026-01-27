/* 
======================================================================
   PHASE A: SCHEMA FEDERATION - MOVE EPHEMERAL TABLES
   Moves 12 ephemeral tables from core schema to lupopedia_ephemeral.
   
   Ephemeral tables are those used for:
   - Session management
   - Temporary data storage
   - Cache tables
   - Rolling analytics (daily aggregations that get purged)
   - Temporary page views

   This migration:
   - Moves 12 ephemeral tables using RENAME TABLE
   - Preserves all data and structure
   - Maintains referential integrity (no FKs, so safe)
   - Updates table locations for application layer

   Version: 4.0.3
   Status: PRE_MIGRATION → STABLE
======================================================================

 by LUPOPEDIA LLC 2026 - CAPTAIN WOLFIE 
   ====================================================================== */

-- ======================================================================
-- MOVE EPHEMERAL TABLES TO lupopedia_ephemeral SCHEMA
-- ======================================================================

-- Session Management
RENAME TABLE lupopedia.lupo_sessions TO lupopedia_ephemeral.lupo_sessions;

-- Analytics Daily Tables (ephemeral - aggregated and purged)
RENAME TABLE lupopedia.lupo_analytics_campaign_vars_daily TO lupopedia_ephemeral.lupo_analytics_campaign_vars_daily;
RENAME TABLE lupopedia.lupo_analytics_paths_daily TO lupopedia_ephemeral.lupo_analytics_paths_daily;
RENAME TABLE lupopedia.lupo_analytics_referers_daily TO lupopedia_ephemeral.lupo_analytics_referers_daily;
RENAME TABLE lupopedia.lupo_analytics_visits_daily TO lupopedia_ephemeral.lupo_analytics_visits_daily;

-- Temporary Page Views (rolling table)
RENAME TABLE lupopedia.lupo_analytics_visits TO lupopedia_ephemeral.lupo_analytics_visits;

-- API Session/Token Management (ephemeral)
RENAME TABLE lupopedia.lupo_api_tokens TO lupopedia_ephemeral.lupo_api_tokens;
RENAME TABLE lupopedia.lupo_api_clients TO lupopedia_ephemeral.lupo_api_clients;
RENAME TABLE lupopedia.lupo_api_webhooks TO lupopedia_ephemeral.lupo_api_webhooks;

-- Temporary Content Processing
RENAME TABLE lupopedia.lupo_narrative_fragments TO lupopedia_ephemeral.lupo_narrative_fragments;

-- Temporary Document Processing
RENAME TABLE lupopedia.lupo_document_chunks TO lupopedia_ephemeral.lupo_document_chunks;

-- ======================================================================
-- VERIFICATION: Count tables in ephemeral schema
-- ======================================================================

-- Expected: 12 tables in lupopedia_ephemeral schema
-- SELECT COUNT(*) as ephemeral_table_count 
-- FROM information_schema.tables 
-- WHERE table_schema = 'lupopedia_ephemeral';

-- ======================================================================
-- END OF PHASE A EPHEMERAL TABLE MIGRATION
-- ======================================================================
