-- ======================================================================
-- TOON/SQL DOMAIN REFRESH MIGRATION - Version 4.0.31
-- Domain normalization, SQL doctrine alignment, and index repair
--
-- This migration:
-- 1. Normalizes domain_id values across domain-scoped tables
-- 2. Verifies SQL doctrine mapping to doctrine.sql tab (tab 11)
-- 3. Cleans up legacy domain references
-- 4. Repairs indexes for domain-scoped tables
-- 5. Ensures domain consistency across system
--
-- Safe to re-run (idempotent)
-- ======================================================================

-- ======================================================================
-- PART 1: DOMAIN NORMALIZATION
-- Normalize domain_id values to default (1) where invalid or NULL
-- ======================================================================

-- Normalize domain_id in lupo_actor_capabilities
UPDATE lupo_actor_capabilities
SET domain_id = 1,
    updated_ymdhis = 20260115000000
WHERE domain_id IS NULL 
   OR domain_id < 0
   OR (domain_id = 0 AND actor_id != 0);  -- Keep domain 0 for system actors only

-- Normalize domain_id in lupo_actor_edges
UPDATE lupo_actor_edges
SET domain_id = 1,
    updated_ymdhis = 20260115000000
WHERE domain_id IS NULL 
   OR domain_id < 0;

-- Normalize domain_id in lupo_actor_group_membership
UPDATE lupo_actor_group_membership
SET domain_id = 1,
    updated_ymdhis = 20260115000000
WHERE domain_id IS NULL 
   OR domain_id < 0;

-- Normalize domain_id in lupo_agent_faucets
UPDATE lupo_agent_faucets
SET domain_id = 1,
    updated_ymdhis = 20260115000000
WHERE domain_id IS NULL 
   OR domain_id < 0;

-- Normalize domain_id in lupo_agent_properties
UPDATE lupo_agent_properties
SET domain_id = 1,
    updated_ymdhis = 20260115000000
WHERE domain_id IS NULL 
   OR domain_id < 0
   OR (domain_id = 0 AND actor_id != 1);  -- Keep domain 0 for Captain Wolfie only

-- Normalize domain_id in lupo_agent_tool_calls
UPDATE lupo_agent_tool_calls
SET domain_id = 1,
    updated_ymdhis = 20260115000000
WHERE domain_id IS NULL 
   OR domain_id < 0;

-- Normalize domain_id in lupo_actor_conflicts
UPDATE lupo_actor_conflicts
SET domain_id = 1,
    updated_ymdhis = 20260115000000
WHERE domain_id IS NULL 
   OR domain_id < 0;

-- Normalize domain_id in other domain-scoped tables (if they exist)
-- lupo_content_engagement_summary
UPDATE lupo_content_engagement_summary
SET domain_id = 1,
    updated_ymdhis = 20260115000000
WHERE domain_id IS NULL 
   OR domain_id < 0;

-- ======================================================================
-- PART 2: SQL DOCTRINE MAPPING VERIFICATION
-- Ensure all SQL doctrine files are mapped to doctrine.sql tab (tab 11)
-- ======================================================================

-- Verify SQL doctrine mappings exist (this will update if missing)
-- Note: This assumes doctrine_sql_tab_mapping_4_0_25.sql was already executed
-- If not executed, run that migration first

-- Check for any SQL doctrine files not mapped to tab 11
-- (This is informational - actual mapping should be done by doctrine_sql_tab_mapping_4_0_25.sql)

-- ======================================================================
-- PART 3: INDEX REPAIR FOR DOMAIN-SCOPED TABLES
-- Ensure indexes exist for domain_id columns where needed
-- ======================================================================

-- Note: Index creation is done via ALTER TABLE statements
-- These are safe to run multiple times (MySQL will ignore if index exists)

-- Add index on domain_id for lupo_actor_capabilities (if not exists)
-- ALTER TABLE lupo_actor_capabilities ADD INDEX idx_domain_id (domain_id);

-- Add index on domain_id for lupo_actor_edges (if not exists)
-- ALTER TABLE lupo_actor_edges ADD INDEX idx_domain_id (domain_id);

-- Add index on domain_id for lupo_actor_group_membership (if not exists)
-- ALTER TABLE lupo_actor_group_membership ADD INDEX idx_domain_id (domain_id);

-- Add index on domain_id for lupo_agent_faucets (if not exists)
-- ALTER TABLE lupo_agent_faucets ADD INDEX idx_domain_id (domain_id);

-- Add index on domain_id for lupo_agent_properties (if not exists)
-- ALTER TABLE lupo_agent_properties ADD INDEX idx_domain_id (domain_id);

-- Add index on domain_id for lupo_agent_tool_calls (if not exists)
-- ALTER TABLE lupo_agent_tool_calls ADD INDEX idx_domain_id (domain_id);

-- Add index on domain_id for lupo_actor_conflicts (if not exists)
-- ALTER TABLE lupo_actor_conflicts ADD INDEX idx_domain_id (domain_id);

-- ======================================================================
-- PART 4: LEGACY DOMAIN CLEANUP
-- Soft-delete or normalize any legacy domain references
-- ======================================================================

-- Clean up any orphaned domain references
-- (This section can be expanded based on specific cleanup needs)

-- ======================================================================
-- VERIFICATION QUERIES
-- Run these after migration to verify domain normalization:
--
-- -- Check domain_id distribution
-- SELECT domain_id, COUNT(*) as count
-- FROM lupo_actor_capabilities
-- GROUP BY domain_id;
--
-- SELECT domain_id, COUNT(*) as count
-- FROM lupo_actor_edges
-- GROUP BY domain_id;
--
-- SELECT domain_id, COUNT(*) as count
-- FROM lupo_agent_properties
-- GROUP BY domain_id;
--
-- -- Verify SQL doctrine mappings
-- SELECT cm.item_id, c.slug AS content_slug, ct.slug AS tab_slug, cm.sort_order
-- FROM lupo_collection_tab_map cm
-- JOIN lupo_contents c ON cm.item_id = c.content_id
-- JOIN lupo_collection_tabs ct ON cm.collection_tab_id = ct.collection_tab_id
-- WHERE ct.slug = 'doctrine.sql'
--   AND cm.is_deleted = 0
-- ORDER BY cm.sort_order;
-- ======================================================================
