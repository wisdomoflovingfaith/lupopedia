-- FILE: database/migrations/dev_20260222_edge_resolution_headers.sql
-- Purpose: Add edge resolution headers for comprehensive FLIP routing
-- Type: Migration (schema enhancement)
-- Risk Level: LOW - adds optional columns for edge-based routing

-- =============================================================================
-- EDGE RESOLUTION HEADERS - 4.0.30
-- =============================================================================

-- Step 1: Add edge resolution columns to lupo_files (if table exists)
-- Note: lupo_files may not exist in current schema, adding for future compatibility

-- Step 2: Add edge resolution to lupo_contents (primary content table)
ALTER TABLE lupo_contents 
ADD COLUMN edge_id BIGINT DEFAULT NULL AFTER collection_id,
ADD COLUMN edge_type VARCHAR(50) DEFAULT NULL AFTER edge_id,
ADD COLUMN source_node_id BIGINT DEFAULT NULL AFTER edge_type,
ADD COLUMN target_node_id BIGINT DEFAULT NULL AFTER source_node_id;

-- Step 3: Add security classification columns
ALTER TABLE lupo_contents 
ADD COLUMN security_level VARCHAR(20) DEFAULT 'standard' AFTER target_node_id,
ADD COLUMN content_hash VARCHAR(64) DEFAULT NULL AFTER security_level,
ADD COLUMN access_required VARCHAR(20) DEFAULT 'read' AFTER content_hash;

-- Step 4: Add fallback routing columns
ALTER TABLE lupo_contents 
ADD COLUMN fallback_channel_id BIGINT DEFAULT NULL AFTER access_required,
ADD COLUMN routing_priority INT DEFAULT 100 AFTER fallback_channel_id,
ADD COLUMN routing_context TEXT DEFAULT NULL AFTER routing_priority;

-- Step 5: Create indexes for performance
CREATE INDEX idx_contents_edge_id ON lupo_contents (edge_id);
CREATE INDEX idx_contents_edge_type ON lupo_contents (edge_type);
CREATE INDEX idx_contents_source_node ON lupo_contents (source_node_id);
CREATE INDEX idx_contents_target_node ON lupo_contents (target_node_id);
CREATE INDEX idx_contents_security_level ON lupo_contents (security_level);
CREATE INDEX idx_contents_fallback_channel ON lupo_contents (fallback_channel_id);
CREATE INDEX idx_contents_routing_priority ON lupo_contents (routing_priority);

-- Step 6: Add edge resolution support to lupo_edges (if exists)
-- Check if lupo_edges table exists and add compatibility columns
-- This is a placeholder for future edge table enhancement

-- Step 7: Verify schema changes
DESCRIBE lupo_contents;

-- =============================================================================
-- MIGRATION VERIFICATION
-- =============================================================================
-- This migration ensures:
-- 1. Edge resolution headers available for FLIP routing
-- 2. Security classification for risk assessment
-- 3. Fallback routing mechanisms
-- 4. Performance indexes for efficient queries
-- 5. Backwards compatibility (all columns are optional)

-- Migration completed: 2026-02-22
-- Risk Level: LOW (adds optional columns, no destructive changes)
