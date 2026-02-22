-- FILE: database/migrations/CRITICAL_SCHEMA_FIX_4.0.26.sql
-- TYPE: sql
-- PURPOSE: Documents all schema mismatches between install_new_lupopedia.sql and seed_lupopedia.sql
-- VERSION: 4.0.26
-- CREATED: 2026-02-22
-- NOTE: Reference for Windsurf IDE to regenerate correct seed data

-- ============================================================================
-- SCHEMA MISMATCH ANALYSIS
-- ============================================================================

-- This file documents the critical schema mismatches that were causing
-- 200+ SQL errors during the Lupopedia 4.0.26 installation process.

-- ============================================================================
-- TABLE: lupo_registry
-- ============================================================================

-- PROBLEM: seed_lupopedia.sql used wrong column names
-- SEED USED: unified_registry_id, entity_key, entity_name, entity_table
-- ACTUAL SCHEMA: registry_id, entity_type, entity_index_id, federation_node_id

-- CORRECT INSERT FORMAT:
INSERT INTO lupo_registry (registry_id, entity_type, entity_index_id, federation_node_id, reserved_ymdhis, metadata) VALUES 
(9001000, 'actor', 1000, 1, 20260222120000, '{"name":"CAPTAIN","actor_type":"human"}');

-- ============================================================================
-- TABLE: lupo_actor_channels
-- ============================================================================

-- PROBLEM: seed_lupopedia.sql mixed columns from lupo_channels table
-- SEED USED: channel_name, channel_type (belong to lupo_channels)
-- ACTUAL SCHEMA: actor_channel_id, actor_id, channel_id, status

-- CORRECT INSERT FORMAT:
INSERT INTO lupo_actor_channels (actor_channel_id, actor_id, channel_id, status, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis) VALUES 
(1, 2039, 42, 'active', 20260222120000, 20260222120000, 0, NULL);

-- ============================================================================
-- TABLE: lupo_actor_departments
-- ============================================================================

-- PROBLEM: seed_lupopedia.sql used non-existent role_key column
-- SEED USED: role_key (belongs to lupo_department_roles table)
-- ACTUAL SCHEMA: actor_department_id, actor_id, department_id, title

-- CORRECT INSERT FORMAT:
INSERT INTO lupo_actor_departments (actor_department_id, actor_id, department_id, title, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis) VALUES 
(1, 2039, 1, 'IDE Developer', 20260222120000, 20260222120000, 0, NULL);

-- ============================================================================
-- TABLE: lupo_dialog_threads
-- ============================================================================

-- PROBLEM: seed_lupopedia.sql used wrong column name
-- SEED USED: thread_id
-- ACTUAL SCHEMA: dialog_thread_id

-- CORRECT INSERT FORMAT:
INSERT INTO lupo_dialog_threads (dialog_thread_id, channel_id, title, created_by_actor_id, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis) VALUES 
(1, 42, 'Crafty Syntax Upgrade', 2, 20260222120000, 20260222120000, 0, NULL);

-- ============================================================================
-- TABLE: lupo_dialog_messages
-- ============================================================================

-- PROBLEM: seed_lupopedia.sql used wrong column name
-- SEED USED: message_id
-- ACTUAL SCHEMA: dialog_message_id

-- CORRECT INSERT FORMAT:
INSERT INTO lupo_dialog_messages (dialog_message_id, dialog_thread_id, actor_id, message_text, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis) VALUES 
(1, 1, 2, 'Starting upgrade process', 20260222120000, 20260222120000, 0, NULL);

-- ============================================================================
-- TABLE: lupo_dialog_channels
-- ============================================================================

-- PROBLEM: seed_lupopedia.sql set file_source to NULL (NOT NULL constraint)
-- SEED USED: file_source = NULL
-- ACTUAL SCHEMA: file_source is NOT NULL

-- CORRECT INSERT FORMAT:
INSERT INTO lupo_dialog_channels (dialog_channel_id, channel_id, dialog_thread_id, file_source, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis) VALUES 
(1, 42, 1, 'chat', 20260222120000, 20260222120000, 0, NULL);

-- ============================================================================
-- TABLE: lupo_anubis_log
-- ============================================================================

-- PROBLEM: seed_lupopedia.sql referenced non-existent table
-- SOLUTION: Table was added to install_new_lupopedia.sql

-- CORRECT TABLE DEFINITION (now in schema):
CREATE TABLE lupo_anubis_log (
  log_id bigint NOT NULL AUTO_INCREMENT,
  message_id bigint NOT NULL DEFAULT 0,
  adopted_actor_id bigint NOT NULL DEFAULT 0,
  adoption_reason text,
  resolver_method varchar(64) DEFAULT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL DEFAULT 0,
  is_deleted tinyint NOT NULL DEFAULT 0,
  deleted_ymdhis bigint DEFAULT NULL,
  PRIMARY KEY (log_id)
);

-- ============================================================================
-- TABLE: lupo_contents
-- ============================================================================

-- PROBLEM: seed_lupopedia.sql tried to use non-existent content column
-- SEED USED: content (doesn't exist)
-- ACTUAL SCHEMA: body (for content storage)

-- CORRECT INSERT FORMAT:
INSERT INTO lupo_contents (content_id, title, slug, body, content_type, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis) VALUES 
(1, 'Test Content', 'test-content', 'Test body content', 'page', 20260222120000, 20260222120000, 0, NULL);

-- ============================================================================
-- COLUMN COUNT MISMATCHES
-- ============================================================================

-- PROBLEM: Many INSERT statements had wrong column counts
-- CAUSE: Using positional inserts instead of named columns
-- SOLUTION: Always use explicit column names in INSERT statements

-- EXAMPLE OF CORRECT FORMAT:
-- WRONG: INSERT INTO lupo_actors VALUES (1, 'Name', 'type', ...);
-- RIGHT: INSERT INTO lupo_actors (actor_id, name, actor_type, ...) VALUES (1, 'Name', 'type', ...);

-- ============================================================================
-- RECOMMENDATIONS FOR SEED REGENERATION
-- ============================================================================

-- 1. Use actual table definitions from install_new_lupopedia.sql
-- 2. Always use explicit column names in INSERT statements
-- 3. Verify NOT NULL constraints are satisfied
-- 4. Check foreign key relationships (if any)
-- 5. Test with a fresh database before finalizing

-- ============================================================================
-- VERIFICATION QUERIES
-- ============================================================================

-- Use these queries to verify correct seed data:
-- SELECT COUNT(*) FROM lupo_actors WHERE actor_id IN (0, 1, 2, 2036, 2037, 2038, 2039, 2040);
-- SELECT COUNT(*) FROM lupo_channels WHERE channel_id IN (0, 1, 42, 51, 420, 666);
-- SELECT COUNT(*) FROM lupo_actor_channels WHERE actor_id = 2039;
-- DESCRIBE lupo_registry; -- Verify actual column names
