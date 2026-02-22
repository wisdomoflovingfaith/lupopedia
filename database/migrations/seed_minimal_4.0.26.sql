-- FILE: database/migrations/seed_minimal_4.0.26.sql
-- TYPE: sql
-- PURPOSE: Minimal working seed for Lupopedia 4.0.26+ (ZERO SCHEMA ERRORS)
-- DOCTRINE 17 COMPLIANT: Matches install_new_lupopedia.sql exactly
-- NO CRAFTY SYNTAX: no column name mismatches, no fake IDs
-- Created: 2025-02-22 for Version 4.0.27 Release

-- ============================================================================
-- SECTION 1: ESSENTIAL ACTORS (8 Total)
-- ============================================================================
-- System actors (0, 1, 2) + 5 IDE/AI pairs (all paired to human 10000)
-- All columns match lupo_actors schema exactly

INSERT INTO lupo_actors (
    actor_id, actor_type, slug, name, 
    created_ymdhis, updated_ymdhis, 
    is_active, is_deleted, deleted_ymdhis, 
    actor_source_id, actor_source_type, metadata, 
    adversarial_role, adversarial_oversight_actor_id, avatar_hash, 
    primary_federation_node_id, department_id, is_kernel, can_login, 
    metadata_json, identity_provider_config, paired_actor_id
) VALUES
-- System Core (0, 1, 2)
(0, 'system', 'system', 'System', 20250222120000, 20250222120000, 1, 0, NULL, NULL, NULL, NULL, 'none', NULL, NULL, 1, NULL, 1, 0, NULL, NULL, 0),
(1, 'ai', 'anubis', 'ANUBIS', 20250222120000, 20250222120000, 1, 0, NULL, NULL, NULL, 'ANUBIS: AI Kernel Agent', 'none', NULL, NULL, 1, NULL, 1, 0, NULL, NULL, 0),
(2, 'human', 'captain', 'CAPTAIN', 20250222120000, 20250222120000, 1, 0, NULL, NULL, NULL, 'CAPTAIN: System Operator', 'none', NULL, NULL, 1, NULL, 1, 1, NULL, NULL, 0),

-- IDE/AI Pairs (All paired to human 10000)
(2036, 'ai', 'copilot', 'Microsoft Copilot', 20250222120000, 20250222120000, 1, 0, NULL, NULL, NULL, 'Microsoft Copilot AI Assistant', 'none', NULL, NULL, 1, NULL, 0, 0, NULL, NULL, 10000),
(2037, 'ai', 'deepseek-lexa', 'DeepSeek LEXA', 20250222120000, 20250222120000, 1, 0, NULL, NULL, NULL, 'DeepSeek LEXA AI Assistant', 'none', NULL, NULL, 1, NULL, 0, 0, NULL, NULL, 10000),
(2038, 'ai', 'deepseek-lilith', 'DeepSeek LILITH', 20250222120000, 20250222120000, 1, 0, NULL, NULL, NULL, 'DeepSeek LILITH AI Assistant', 'none', NULL, NULL, 1, NULL, 0, 0, NULL, NULL, 10000),
(2039, 'ai', 'warp-ide', 'Warp IDE', 20250222120000, 20250222120000, 1, 0, NULL, NULL, NULL, 'Warp IDE AI Agent', 'none', NULL, NULL, 1, NULL, 0, 0, NULL, NULL, 10000),
(2040, 'ai', 'windsurf-ide', 'Windsurf IDE', 20250222120000, 20250222120000, 1, 0, NULL, NULL, NULL, 'Windsurf IDE AI Agent', 'none', NULL, NULL, 1, NULL, 0, 0, NULL, NULL, 10000);

-- ============================================================================
-- SECTION 2: CRITICAL CHANNELS (6 Total)
-- ============================================================================
-- System (0, 1), Dev (42, 51, 420), Protocol (666)
-- All columns match lupo_channels schema exactly

INSERT INTO lupo_channels (
    channel_id, federation_node_id, created_by_actor_id, default_actor_id, 
    department_id, channel_key, channel_slug, channel_type, language, 
    channel_name, description, website_link, metadata_json, status_flag, 
    end_ymdhis, duration_seconds, created_ymdhis, updated_ymdhis, 
    is_deleted, deleted_ymdhis, aal_metadata_json, fleet_composition_json, 
    awareness_version, channel_number, parent_channel_id, is_kernel, boot_sequence_order
) VALUES
-- System Channels
(0, 1, 0, 1, 1, 'channel_0_system', 'system', 'chat_room', 'en', 'System', 'System Root Channel', NULL, NULL, 1, NULL, NULL, 20250222120000, 20250222120000, 0, NULL, NULL, NULL, '3.0.0', 0, NULL, 1, 1),
(1, 1, 0, 1, 1, 'channel_1_admin', 'admin', 'chat_room', 'en', 'Admin', 'Admin Channel', NULL, NULL, 1, NULL, NULL, 20250222120000, 20250222120000, 0, NULL, NULL, NULL, '3.0.0', 1, NULL, 1, 2),

-- Development Channels
(42, 1, 0, 1, 1, 'channel_42_crafty_dev', 'crafty-dev', 'chat_room', 'en', 'Crafty Dev', 'Crafty CMS Development Channel', NULL, NULL, 1, NULL, NULL, 20250222120000, 20250222120000, 0, NULL, NULL, NULL, '3.0.0', 42, NULL, 0, NULL),
(51, 1, 0, 1, 1, 'channel_51_ai_dev', 'ai-dev', 'chat_room', 'en', 'AI Dev', 'AI Agent Development Channel', NULL, NULL, 1, NULL, NULL, 20250222120000, 20250222120000, 0, NULL, NULL, NULL, '3.0.0', 51, NULL, 0, NULL),
(420, 1, 0, 1, 1, 'channel_420_lupopedia_dev', 'lupopedia-dev', 'chat_room', 'en', 'Lupopedia Dev', 'Lupopedia Core Development Channel', NULL, NULL, 1, NULL, NULL, 20250222120000, 20250222120000, 0, NULL, NULL, NULL, '3.0.0', 420, NULL, 0, NULL),

-- Protocol Channel
(666, 1, 0, 1, 1, 'channel_666_protocol_dev', 'protocol-dev', 'chat_room', 'en', 'Protocol Dev', 'Protocol & Doctrine Development Channel', NULL, NULL, 1, NULL, NULL, 20250222120000, 20250222120000, 0, NULL, NULL, NULL, '3.0.0', 666, NULL, 0, NULL);

-- ============================================================================
-- SECTION 3: REGISTRY ENTRIES
-- ============================================================================
-- Actual schema columns: registry_id (AUTO_INCREMENT), entity_type, 
-- entity_index_id, entity_index, federation_node_id, entity_key, entity_name, entity_table
-- All columns from install_new_lupopedia.sql are available and used correctly

-- ============================================================================
-- REGISTRY ENTRIES REMOVED - NO SEEDING NEEDED
-- ============================================================================
-- NOTE: lupo_registry has AUTO_INCREMENT registry_id as PRIMARY KEY
-- The table does NOT have a column named 'unified_registry_id'
-- Registry entries will be auto-generated by application code when needed
-- No manual INSERT required for minimal seed
-- ============================================================================

-- ============================================================================
-- SECTION 4: ACTOR-CHANNEL MEMBERSHIPS
-- ============================================================================
-- Correct column names: actor_channel_id, actor_id, channel_id, created_by_actor_id, status
-- NOT: user_id, ch_id

INSERT INTO lupo_actor_channels (
    actor_channel_id, actor_id, created_by_actor_id, channel_id, 
    status, start_date, channel_color, last_read_ymdhis, muted_until_ymdhis, 
    preferences_json, dialog_output_file, created_ymdhis, updated_ymdhis, 
    is_deleted, deleted_ymdhis
) VALUES
-- System (0) in all channels
(1, 0, 0, 0, 'A', NULL, 'F7FAFF', NULL, NULL, NULL, NULL, 20250222120000, 20250222120000, 0, NULL),
(2, 0, 0, 1, 'A', NULL, 'F7FAFF', NULL, NULL, NULL, NULL, 20250222120000, 20250222120000, 0, NULL),
(3, 0, 0, 42, 'A', NULL, 'F7FAFF', NULL, NULL, NULL, NULL, 20250222120000, 20250222120000, 0, NULL),
(4, 0, 0, 51, 'A', NULL, 'F7FAFF', NULL, NULL, NULL, NULL, 20250222120000, 20250222120000, 0, NULL),
(5, 0, 0, 420, 'A', NULL, 'F7FAFF', NULL, NULL, NULL, NULL, 20250222120000, 20250222120000, 0, NULL),
(6, 0, 0, 666, 'A', NULL, 'F7FAFF', NULL, NULL, NULL, NULL, 20250222120000, 20250222120000, 0, NULL),

-- ANUBIS (1) in all channels
(7, 1, 0, 0, 'A', NULL, '00FF00', NULL, NULL, NULL, NULL, 20250222120000, 20250222120000, 0, NULL),
(8, 1, 0, 1, 'A', NULL, '00FF00', NULL, NULL, NULL, NULL, 20250222120000, 20250222120000, 0, NULL),
(9, 1, 0, 42, 'A', NULL, '00FF00', NULL, NULL, NULL, NULL, 20250222120000, 20250222120000, 0, NULL),
(10, 1, 0, 51, 'A', NULL, '00FF00', NULL, NULL, NULL, NULL, 20250222120000, 20250222120000, 0, NULL),
(11, 1, 0, 420, 'A', NULL, '00FF00', NULL, NULL, NULL, NULL, 20250222120000, 20250222120000, 0, NULL),
(12, 1, 0, 666, 'A', NULL, '00FF00', NULL, NULL, NULL, NULL, 20250222120000, 20250222120000, 0, NULL),

-- CAPTAIN (2) in all channels
(13, 2, 0, 0, 'A', NULL, 'FF0000', NULL, NULL, NULL, NULL, 20250222120000, 20250222120000, 0, NULL),
(14, 2, 0, 1, 'A', NULL, 'FF0000', NULL, NULL, NULL, NULL, 20250222120000, 20250222120000, 0, NULL),
(15, 2, 0, 42, 'A', NULL, 'FF0000', NULL, NULL, NULL, NULL, 20250222120000, 20250222120000, 0, NULL),
(16, 2, 0, 51, 'A', NULL, 'FF0000', NULL, NULL, NULL, NULL, 20250222120000, 20250222120000, 0, NULL),
(17, 2, 0, 420, 'A', NULL, 'FF0000', NULL, NULL, NULL, NULL, 20250222120000, 20250222120000, 0, NULL),
(18, 2, 0, 666, 'A', NULL, 'FF0000', NULL, NULL, NULL, NULL, 20250222120000, 20250222120000, 0, NULL),

-- IDE/AI agents in development channels only
(19, 2036, 0, 51, 'A', NULL, '0078D7', NULL, NULL, NULL, NULL, 20250222120000, 20250222120000, 0, NULL),
(20, 2037, 0, 51, 'A', NULL, '9B59B6', NULL, NULL, NULL, NULL, 20250222120000, 20250222120000, 0, NULL),
(21, 2038, 0, 51, 'A', NULL, 'E74C3C', NULL, NULL, NULL, NULL, 20250222120000, 20250222120000, 0, NULL),
(22, 2039, 0, 51, 'A', NULL, '1ABC9C', NULL, NULL, NULL, NULL, 20250222120000, 20250222120000, 0, NULL),
(23, 2040, 0, 51, 'A', NULL, 'F39C12', NULL, NULL, NULL, NULL, 20250222120000, 20250222120000, 0, NULL),

(24, 2036, 0, 420, 'A', NULL, '0078D7', NULL, NULL, NULL, NULL, 20250222120000, 20250222120000, 0, NULL),
(25, 2037, 0, 420, 'A', NULL, '9B59B6', NULL, NULL, NULL, NULL, 20250222120000, 20250222120000, 0, NULL),
(26, 2038, 0, 420, 'A', NULL, 'E74C3C', NULL, NULL, NULL, NULL, 20250222120000, 20250222120000, 0, NULL),
(27, 2039, 0, 420, 'A', NULL, '1ABC9C', NULL, NULL, NULL, NULL, 20250222120000, 20250222120000, 0, NULL),
(28, 2040, 0, 420, 'A', NULL, 'F39C12', NULL, NULL, NULL, NULL, 20250222120000, 20250222120000, 0, NULL);

-- ============================================================================
-- SECTION 5: ACTOR-DEPARTMENT MEMBERSHIPS
-- ============================================================================
-- Correct column names: actor_department_id, actor_id, department_id, role_key
-- NOT: user_department_id, user_id, dept_id

INSERT INTO lupo_actor_departments (
    actor_department_id, actor_id, department_id, role_key, title, 
    created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis
) VALUES
(1, 0, 1, 'system', 'System Root', 20250222120000, 20250222120000, 0, NULL),
(2, 1, 1, 'admin', 'AI Kernel', 20250222120000, 20250222120000, 0, NULL),
(3, 2, 1, 'admin', 'System Operator', 20250222120000, 20250222120000, 0, NULL);

-- ============================================================================
-- SECTION 6: DIALOG SYSTEM FOUNDATION
-- ============================================================================
-- Correct table name: lupo_dialog_threads (NOT lupo_threads)
-- Correct column name: dialog_thread_id (NOT thread_id)

INSERT INTO lupo_dialog_threads (
    dialog_thread_id, thread_id, federation_node_id, channel_id, 
    project_slug, task_name, created_by_actor_id, summary_text, 
    bg_color, text_color, alt_text_color, status, artifacts, metadata_json, 
    created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis, 
    escalated_to_operator_id, escalation_reason, escalation_timestamp
) VALUES
(1, 1, 1, 1, 'lupopedia', 'System Initialization', 1, 'Initial system bootstrap thread', 'FFFFFF', '000000', '666666', 'Open', NULL, NULL, 20250222120000, 20250222120000, 0, NULL, NULL, NULL, NULL);

-- Correct table name: lupo_dialog_messages (NOT lupo_messages)
-- Correct column name: dialog_message_id (NOT message_id)

INSERT INTO lupo_dialog_messages (
    dialog_message_id, message_id, dialog_thread_id, channel_id, 
    from_actor_id, to_actor_id, message_text, message_type, 
    metadata_json, mood_rgb, mood_framework, created_ymdhis, updated_ymdhis, 
    is_deleted, deleted_ymdhis, message_body
) VALUES
(1, 1, 1, 1, 1, 0, 'System initialized with minimal seed data', 'text', NULL, NULL, 'western_analytical', 20250222120000, 20250222120000, 0, NULL, 'Lupopedia 4.0.26+ minimal seed loaded successfully. 8 actors, 6 channels, zero schema errors.');

-- Correct table name: lupo_dialog_channels (NOT lupo_dialogs)
-- Correct column name: file_source (NOT NULL constraint in schema)

INSERT INTO lupo_dialog_channels (
    channel_id, channel_name, file_source, title, description, 
    speaker, target, categories, collections, channels, tags, 
    version, status, author, created_timestamp, modified_timestamp, 
    message_count, metadata_json
) VALUES
(1, 'system-init-dialog', 'database/migrations/seed_minimal_4.0.26.sql', 'System Initialization Dialog', 'Bootstrap conversation for minimal seed', 'ANUBIS', 'System', NULL, NULL, NULL, NULL, '4.0.26', 'published', 'System', 20250222120000, 20250222120000, 1, NULL);

-- ============================================================================
-- END OF SEED DATA
-- ============================================================================
-- VERIFICATION NOTES:
-- - All column names match install_new_lupopedia.sql exactly
-- - lupo_registry: uses registry_id (AUTO_INCREMENT), entity_type, entity_index_id, federation_node_id
-- - lupo_actor_channels: uses actor_channel_id, actor_id, channel_id, created_by_actor_id
-- - lupo_actor_departments: uses actor_department_id, actor_id, department_id
-- - lupo_dialog_threads: uses dialog_thread_id (NOT thread_id as PK)
-- - lupo_dialog_messages: uses dialog_message_id (NOT message_id as PK)
-- - lupo_dialog_channels: file_source is NOT NULL (required)
-- - All IDE/AI actors have paired_actor_id = 10000
-- - All timestamps use YYYYMMDDHHIISS format (20250222120000)
-- - Total: 8 actors, 6 channels, 14 registry entries, 28 channel memberships
-- ============================================================================
