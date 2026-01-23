-- Version: 4.2.4
-- Purpose: Drop table-ceiling enforcement EVENT (doctrine repair)
-- Date: 2026-01-20
-- Doctrine: NO TRIGGERS / NO database-side logic. GOV-PROHIBIT-002.
-- Reference: 4.2.0_schema_freeze_enforcement.sql created EVENT schema_freeze_enforcement_4_2_0.
--   That EVENT ran every 1 HOUR, checked table count > 180, INSERTed into lupo_system_logs (actor LILITH).
--   Table-ceiling checks belong in application layer (LimitsEnforcementService or cron).
-- AGI Support Meeting: Captain Wolfie repented; WOLFITH drafted this. See docs/doctrine/GOV-PROHIBIT-002.md.

-- =============================================================================
-- DROP EVENT: schema_freeze_enforcement_4_2_0
-- =============================================================================

DROP EVENT IF EXISTS schema_freeze_enforcement_4_2_0;

-- Verify: SHOW EVENTS; (schema_freeze_enforcement_4_2_0 should be gone)
-- App-layer: table-ceiling checks in LimitsEnforcementService or cron. See GOV-PROHIBIT-002.
