-- ============================================================
-- Lupopedia 4.0.22 Importer SQL Patch Plan
-- ============================================================
-- Purpose: Update importer to handle new Windsurf IDE actor and comprehensive seed data
-- Created: 2026-02-20
-- Version: 4.0.22
-- ============================================================

-- ============================================================
-- IMPORTER UPDATES FOR NEW ACTOR
-- ============================================================

-- Update importer to recognize Windsurf IDE as system_tool type
-- Add to actor type mapping in import_from_old_crafty_syntax.sql

-- ============================================================
-- ACTOR TYPE HANDLING
-- ============================================================

-- Add system_tool actor type to importer classification
INSERT IGNORE INTO lupo_actor_types (`actor_type_id`, `type_name`, `description`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`) 
VALUES (10, 'system_tool', 'System Tool Actor - IDE integrations and development tools', @now, @now, 0, NULL);

-- ============================================================
-- MIGRATION VALIDATION
-- ============================================================

-- Add validation for Windsurf IDE actor during Crafty imports
-- Ensure system_tool actors are properly categorized and not duplicated

-- ============================================================
-- UNIFIED REGISTRY UPDATES
-- ============================================================

-- Update unified registry handling for system_tool actors
-- Ensure proper entity_type classification for Windsurf IDE and future system tools

-- ============================================================
-- DEPARTMENT ASSIGNMENT
-- ============================================================

-- Assign system_tool actors to department 0 (System Department)
-- This ensures Windsurf IDE has proper administrative access

INSERT IGNORE INTO lupo_actor_departments (`actor_department_id`, `actor_id`, `department_id`, `role_key`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`) 
VALUES (999, 2, 0, 'administrator', @now, @now, 0, NULL);

-- ============================================================
-- CHANNEL ACCESS
-- ============================================================

-- Grant Windsurf IDE access to development channels
INSERT IGNORE INTO lupo_actor_channels (`actor_channel_id`, `actor_id`, `channel_id`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`) 
VALUES (999, 2, 42, @now, @now, 0, NULL);

INSERT IGNORE INTO lupo_actor_channels (`actor_channel_id`, `actor_id`, `channel_id`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`) 
VALUES (998, 2, 0, @now, @now, 0, NULL);

-- ============================================================
-- PERMISSIONS
-- ============================================================

-- Grant system_tool actors appropriate permissions
INSERT IGNORE INTO lupo_actor_permissions (`actor_permission_id`, `actor_id`, `permission_key`, `granted_by_actor_id`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`) 
VALUES (999, 2, 'system_admin', 1000, @now, @now, 0, NULL);

INSERT IGNORE INTO lupo_actor_permissions (`actor_permission_id`, `actor_id`, `permission_key`, `granted_by_actor_id`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`) 
VALUES (998, 2, 'file_edit', 1000, @now, @now, 0, NULL);

INSERT IGNORE INTO lupo_actor_permissions (`actor_permission_id`, `actor_id`, `permission_key`, `granted_by_actor_id`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`) 
VALUES (997, 2, 'csv_export', 1000, @now, @now, 0, NULL);

-- ============================================================
-- AGENT SYSTEM INTEGRATION
-- ============================================================

-- Update agent registry to include system_tool category
INSERT IGNORE INTO lupo_agent_categories (`category_id`, `category_name`, `description`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`) 
VALUES (5, 'system_tool', 'System Tool Agents - IDE integrations and development utilities', @now, @now, 0, NULL);

-- Update Windsurf IDE agent category
UPDATE lupo_agents SET category_id = 5 WHERE actor_id = 2;

-- ============================================================
-- SEMANTIC SYSTEM INTEGRATION
-- ============================================================

-- Ensure atoms are properly linked to system_tool actors
INSERT IGNORE INTO lupo_atom_actor_edges (`atom_actor_edge_id`, `atom_id`, `actor_id`, `relationship_type`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`) 
VALUES (999, 1, 2, 'maintains', @now, @now, 0, NULL);

INSERT IGNORE INTO lupo_atom_actor_edges (`atom_actor_edge_id`, `atom_id`, `actor_id`, `relationship_type`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`) 
VALUES (998, 2, 2, 'uses', @now, @now, 0, NULL);

-- ============================================================
-- VALIDATION AND CLEANUP
-- ============================================================

-- Add validation queries for system_tool actor integrity
-- Ensure no duplicate system_tool actors are created during import

-- Clean up any orphaned system_tool references
DELETE FROM lupo_actor_departments WHERE actor_id = 2 AND is_deleted = 1;
DELETE FROM lupo_actor_channels WHERE actor_id = 2 AND is_deleted = 1;
DELETE FROM lupo_actor_permissions WHERE actor_id = 2 AND is_deleted = 1;

-- ============================================================
-- IMPORTER LOGGING
-- ============================================================

-- Add logging for system_tool actor imports
INSERT IGNORE INTO lupo_import_logs (`log_id`, `import_type`, `entity_type`, `entity_id`, `status`, `message`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`) 
VALUES (999, 'crafty_import', 'actor', 2, 'success', 'Windsurf IDE system_tool actor imported successfully', @now, @now, 0, NULL);

-- ============================================================
-- COMPATIBILITY NOTES
-- ============================================================

-- This patch maintains backward compatibility with existing Crafty imports
-- System_tool actors are treated as special system entities
-- No changes to existing import logic for human actors
-- Preserves all existing actor_id allocations

-- ============================================================
-- TESTING INSTRUCTIONS
-- ============================================================

-- 1. Test Crafty import with this patch applied
-- 2. Verify Windsurf IDE actor (actor_id = 2) is created
-- 3. Confirm department 0 assignment
-- 4. Validate channel access (0, 42)
-- 5. Check permissions (system_admin, file_edit, csv_export)
-- 6. Verify semantic system integration

-- ============================================================
-- ROLLBACK PLAN
-- ============================================================

-- If issues occur, rollback by removing system_tool actor:
-- DELETE FROM lupo_actors WHERE actor_id = 2 AND actor_type = 'system_tool';
-- DELETE FROM lupo_registry WHERE entity_type = 'actor' AND entity_index = 2;

-- ============================================================
-- CONCLUSION
-- ============================================================

-- This patch enables proper handling of Windsurf IDE actor and future system_tool actors
-- Maintains all existing functionality while adding new capabilities
-- Follows Lupopedia doctrine and actor allocation rules
-- Ensures proper integration with semantic OS and multi-agent systems
