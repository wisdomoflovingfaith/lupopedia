-- Migration: 4.0.49 Schema Updates
-- Purpose: Implement DBDOC-recommended schema changes for federation node naming, soft-delete fields, and index coverage
-- Date: 2026-02-27
-- Agent: Windsurf (1001)

-- =============================================================================
-- FEDERATION NODE NAMING STANDARDIZATION
-- =============================================================================

-- Rename federations_node_id to federation_node_id in lupo_collections
ALTER TABLE lupo_collections 
CHANGE COLUMN federations_node_id federation_node_id BIGINT NOT NULL;

-- Update index references for lupo_collections
DROP INDEX lupo_collections_idx_domain ON lupo_collections;
CREATE INDEX lupo_collections_idx_domain ON lupo_collections (federation_node_id);

DROP INDEX lupo_collections_unique_collection_slug_domain ON lupo_collections;
CREATE UNIQUE INDEX lupo_collections_unique_collection_slug_domain ON lupo_collections (federation_node_id, slug);

-- Rename federations_node_id to federation_node_id in lupo_analytics_visits
ALTER TABLE lupo_analytics_visits 
CHANGE COLUMN federations_node_id federation_node_id BIGINT NOT NULL;

-- =============================================================================
-- ADD updated_ymdhis WHERE MISSING
-- =============================================================================

-- Add updated_ymdhis to lupo_document_embeddings
ALTER TABLE lupo_document_embeddings 
ADD COLUMN updated_ymdhis BIGINT NOT NULL DEFAULT 0 AFTER created_ymdhis;

-- Add updated_ymdhis to lupo_agent_heartbeats
ALTER TABLE lupo_agent_heartbeats 
ADD COLUMN updated_ymdhis BIGINT NOT NULL DEFAULT 0 AFTER created_ymdhis;

-- Add updated_ymdhis to lupo_agent_tool_calls
ALTER TABLE lupo_agent_tool_calls 
ADD COLUMN updated_ymdhis BIGINT NOT NULL DEFAULT 0 AFTER created_ymdhis;

-- Add updated_ymdhis to lupo_api_tokens
ALTER TABLE lupo_api_tokens 
ADD COLUMN updated_ymdhis BIGINT NOT NULL DEFAULT 0 AFTER created_ymdhis;

-- =============================================================================
-- ADD SOFT-DELETE FIELDS WHERE MISSING
-- =============================================================================

-- Add soft-delete fields to lupo_agent_tool_calls
ALTER TABLE lupo_agent_tool_calls 
ADD COLUMN is_deleted TINYINT NOT NULL DEFAULT 0 AFTER updated_ymdhis,
ADD COLUMN deleted_ymdhis BIGINT DEFAULT NULL AFTER is_deleted;

-- Add soft-delete fields to lupo_api_tokens
ALTER TABLE lupo_api_tokens 
ADD COLUMN is_deleted TINYINT NOT NULL DEFAULT 0 AFTER notes,
ADD COLUMN deleted_ymdhis BIGINT DEFAULT NULL AFTER is_deleted;

-- Add soft-delete fields to lupo_analytics_visits
ALTER TABLE lupo_analytics_visits 
ADD COLUMN is_deleted TINYINT NOT NULL DEFAULT 0 AFTER updated_ymdhis,
ADD COLUMN deleted_ymdhis BIGINT DEFAULT NULL AFTER is_deleted;

-- =============================================================================
-- ADD OPERATIONAL CLEANUP MARKERS
-- =============================================================================

-- Add archived_ymdhis to lupo_agent_tool_calls
ALTER TABLE lupo_agent_tool_calls 
ADD COLUMN archived_ymdhis BIGINT DEFAULT 0 AFTER deleted_ymdhis;

-- Add archived_ymdhis to lupo_analytics_visits
ALTER TABLE lupo_analytics_visits 
ADD COLUMN archived_ymdhis BIGINT DEFAULT 0 AFTER deleted_ymdhis;

-- =============================================================================
-- ADD INDEX COVERAGE
-- =============================================================================

-- Add index on api_key_id in lupo_agents
CREATE INDEX lupo_agents_idx_api_key_id ON lupo_agents (api_key_id);

-- Add composite index on (agent_id, created_ymdhis) in lupo_agent_tool_calls
CREATE INDEX lupo_agent_tool_calls_idx_agent_created ON lupo_agent_tool_calls (agent_id, created_ymdhis);

-- Add composite index on (actor_id, is_active) in lupo_api_tokens
CREATE INDEX lupo_api_tokens_idx_actor_active ON lupo_api_tokens (actor_id, is_active);

-- =============================================================================
-- MIGRATION COMPLETION LOG
-- =============================================================================

-- Log migration completion
INSERT INTO lupo_system_events (event_type, event_message, created_ymdhis, actor_id) 
VALUES ('migration', '4.0.49 schema updates completed: federation naming, soft-delete fields, and index coverage', 20260227124500, 1001)
ON DUPLICATE KEY UPDATE event_message = '4.0.49 schema updates completed: federation naming, soft-delete fields, and index coverage';

-- =============================================================================
-- VALIDATION QUERIES (for verification)
-- =============================================================================

-- Verify federation_node_id column exists
-- SELECT column_name FROM information_schema.columns 
-- WHERE table_name IN ('lupo_collections', 'lupo_analytics_visits') 
-- AND column_name = 'federation_node_id';

-- Verify updated_ymdhis columns were added
-- SELECT table_name, column_name FROM information_schema.columns 
-- WHERE table_name IN ('lupo_document_embeddings', 'lupo_agent_heartbeats', 'lupo_agent_tool_calls', 'lupo_api_tokens') 
-- AND column_name = 'updated_ymdhis';

-- Verify soft-delete fields were added
-- SELECT table_name, column_name FROM information_schema.columns 
-- WHERE table_name IN ('lupo_agent_tool_calls', 'lupo_api_tokens', 'lupo_analytics_visits') 
-- AND column_name IN ('is_deleted', 'deleted_ymdhis');

-- Verify archived_ymdhis fields were added
-- SELECT table_name, column_name FROM information_schema.columns 
-- WHERE table_name IN ('lupo_agent_tool_calls', 'lupo_analytics_visits') 
-- AND column_name = 'archived_ymdhis';

-- Verify new indexes were created
-- SELECT index_name, table_name, column_name FROM information_schema.statistics 
-- WHERE index_name IN ('lupo_agents_idx_api_key_id', 'lupo_agent_tool_calls_idx_agent_created', 'lupo_api_tokens_idx_actor_active')
-- ORDER BY table_name, index_name;
