-- Migration: dev_20260227_dbdoc_schema_updates
-- Purpose: Cross-DB migration for DBDOC-recommended schema updates (4.0.49)
-- Date: 2026-02-27
-- Agent: Codex (1007)
-- Notes: No IF EXISTS/AFTER/CHANGE COLUMN/ON DUPLICATE KEY to preserve cross-DB compatibility.

-- =============================================================================
-- FEDERATION NODE NAMING STANDARDIZATION
-- =============================================================================

ALTER TABLE lupo_collections RENAME COLUMN federations_node_id TO federation_node_id;
ALTER TABLE lupo_analytics_visits RENAME COLUMN federations_node_id TO federation_node_id;

-- =============================================================================
-- ADD updated_ymdhis WHERE MISSING
-- =============================================================================

ALTER TABLE lupo_document_embeddings ADD COLUMN updated_ymdhis BIGINT NOT NULL DEFAULT 0;
ALTER TABLE lupo_agent_heartbeats ADD COLUMN updated_ymdhis BIGINT NOT NULL DEFAULT 0;
ALTER TABLE lupo_agent_tool_calls ADD COLUMN updated_ymdhis BIGINT NOT NULL DEFAULT 0;
ALTER TABLE lupo_api_tokens ADD COLUMN updated_ymdhis BIGINT NOT NULL DEFAULT 0;

-- =============================================================================
-- ADD SOFT-DELETE FIELDS WHERE MISSING
-- =============================================================================

ALTER TABLE lupo_agent_tool_calls ADD COLUMN is_deleted TINYINT NOT NULL DEFAULT 0;
ALTER TABLE lupo_agent_tool_calls ADD COLUMN deleted_ymdhis BIGINT DEFAULT 0;

ALTER TABLE lupo_api_tokens ADD COLUMN is_deleted TINYINT NOT NULL DEFAULT 0;
ALTER TABLE lupo_api_tokens ADD COLUMN deleted_ymdhis BIGINT DEFAULT 0;

ALTER TABLE lupo_analytics_visits ADD COLUMN is_deleted TINYINT NOT NULL DEFAULT 0;
ALTER TABLE lupo_analytics_visits ADD COLUMN deleted_ymdhis BIGINT DEFAULT 0;

-- =============================================================================
-- ADD OPERATIONAL CLEANUP MARKERS
-- =============================================================================

ALTER TABLE lupo_agent_tool_calls ADD COLUMN archived_ymdhis BIGINT DEFAULT 0;
ALTER TABLE lupo_analytics_visits ADD COLUMN archived_ymdhis BIGINT DEFAULT 0;

-- =============================================================================
-- ADD INDEX COVERAGE
-- =============================================================================

CREATE INDEX lupo_agents_idx_api_key_id ON lupo_agents (api_key_id);
CREATE INDEX lupo_agent_tool_calls_idx_agent_created ON lupo_agent_tool_calls (agent_id, created_ymdhis);
CREATE INDEX lupo_api_tokens_idx_actor_active ON lupo_api_tokens (actor_id, is_active);

-- =============================================================================
-- OPTIONAL DATA NORMALIZATION (run if desired)
-- =============================================================================
-- UPDATE lupo_document_embeddings SET updated_ymdhis = created_ymdhis WHERE updated_ymdhis = 0;
-- UPDATE lupo_agent_heartbeats SET updated_ymdhis = created_ymdhis WHERE updated_ymdhis = 0;
-- UPDATE lupo_agent_tool_calls SET updated_ymdhis = created_ymdhis WHERE updated_ymdhis = 0;
-- UPDATE lupo_api_tokens SET updated_ymdhis = created_ymdhis WHERE updated_ymdhis = 0;
