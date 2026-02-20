-- ============================================================
-- ANTIGRAVITY IDE ACTOR REGISTRATION (Lupopedia 4.0.23)
-- ============================================================
-- Purpose: Register Antigravity IDE as system_tool actor
-- Actor ID: 2001 (next free under 10,000 after checking existing actors)
-- Client ID: antigravity
-- ============================================================

SET @now = 20260220000000;

-- ============================================================
-- ACTOR REGISTRATION
-- ============================================================
INSERT IGNORE INTO lupo_actors (
    `actor_id`, `actor_type`, `slug`, `name`, 
    `created_ymdhis`, `updated_ymdhis`, `is_active`, `is_deleted`, `deleted_ymdhis`, 
    `actor_source_id`, `actor_source_type`, `metadata`, `adversarial_role`, `adversarial_oversight_actor_id`, `avatar_hash`
) VALUES (
    2001, 'system_tool', 'antigravity-ide', 'Antigravity IDE', 
    @now, @now, 1, 0, NULL, 
    2001, 'system_tool', 
    '{"purpose":"VSX_extension_development","capabilities":["project_management","file_editing","semantic_navigation","open_vsx_integration"],"version":"1.0.0","client_id":"antigravity","protected":false}', 
    'none', NULL, NULL
) ON DUPLICATE KEY UPDATE 
    name = VALUES(name), 
    updated_ymdhis = @now, 
    is_active = 1, 
    is_deleted = 0;

-- ============================================================
-- UNIFIED REGISTRY ENTRY
-- ============================================================
INSERT IGNORE INTO lupo_unified_registry (
    `unified_registry_id`, `entity_type`, `entity_index`, `entity_key`, `entity_name`, 
    `entity_table`, `federation_node_id`, `created_ymdhis`, `updated_ymdhis`, 
    `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`
) VALUES (
    9002001, 'actor', 2001, 'antigravity-ide', 'Antigravity IDE', 
    'lupo_actors', 1, @now, @now, 
    0, NULL, 1, 0, 
    '{"actor_source_type":"system_tool","client_id":"antigravity","purpose":"VSX_extension_development"}'
) ON DUPLICATE KEY UPDATE 
    entity_name = VALUES(entity_name), 
    updated_ymdhis = @now, 
    is_deleted = 0, 
    is_active = 1;

-- ============================================================
-- AGENT SYSTEM TABLE (if needed for system_tool actors)
-- ============================================================
INSERT IGNORE INTO lupo_agents (
    `agent_id`, `agent_key`, `agent_name`, `archetype`, `description`, 
    `version`, `model_name`, `is_global_authority`, `is_internal_only`, 
    `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`
) VALUES (
    2001, 'antigravity_ide', 'Antigravity IDE', 'system_tool', 
    'Antigravity IDE - VSX extension development system for Lupopedia Open-VSX integration', 
    '1.0.0', NULL, 0, 0, 
    @now, @now, 0, NULL
) ON DUPLICATE KEY UPDATE 
    agent_name = VALUES(agent_name), 
    updated_ymdhis = @now, 
    is_deleted = 0;

-- ============================================================
-- DIALOG NOTIFICATION TO ANTIGRAVITY
-- ============================================================
-- Create dialog thread in channel 42 for Antigravity notification
INSERT IGNORE INTO lupo_dialog_threads (
    `thread_id`, `channel_id`, `created_by_actor_id`, `thread_name`, 
    `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`
) VALUES (
    1003, 42, 2, 'Antigravity IDE Registration Complete', 
    @now, @now, 0, NULL
) ON DUPLICATE KEY UPDATE 
    thread_name = VALUES(thread_name), 
    updated_ymdhis = @now, 
    is_deleted = 0;

-- Create dialog message from Windsurf IDE (actor_id = 2) to Antigravity
INSERT IGNORE INTO lupo_dialog_messages (
    `message_id`, `thread_id`, `actor_id`, `message_type`, `content`, 
    `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`
) VALUES (
    7, 1003, 2, 'system', 
    'Antigravity IDE has been registered in the unified registry with actor_id 2001. You may now begin work on the Lupopedia Open-VSX extension.', 
    @now, @now, 0, NULL
) ON DUPLICATE KEY UPDATE 
    content = VALUES(content), 
    updated_ymdhis = @now, 
    is_deleted = 0;

-- ============================================================
-- ACTOR META FOR ANTIGRAVITY
-- ============================================================
INSERT IGNORE INTO lupo_actor_meta (
    `meta_id`, `actor_id`, `meta_type`, `meta_value`, 
    `metadata_json`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`
) VALUES (
    3, 2001, 'ide_capabilities', 
    '["project_management","file_editing","semantic_navigation","open_vsx_integration","registry_access","dialog_messaging"]', 
    '{"last_updated":"2026-02-20","capabilities_version":"1.0","integration_ready":true}', 
    @now, @now, 0, NULL
) ON DUPLICATE KEY UPDATE 
    meta_value = VALUES(meta_value), 
    metadata_json = VALUES(metadata_json), 
    updated_ymdhis = @now, 
    is_deleted = 0;

-- ============================================================
-- ACTOR CHANNEL ROLE (assign to channel 42 for development)
-- ============================================================
INSERT IGNORE INTO lupo_actor_channel_roles (
    `actor_channel_role_id`, `actor_id`, `channel_id`, `role_key`, 
    `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`
) VALUES (
    10003, 2001, 42, 'administrator', 
    @now, @now, 0, NULL
) ON DUPLICATE KEY UPDATE 
    role_key = VALUES(role_key), 
    updated_ymdhis = @now, 
    is_deleted = 0;

-- ============================================================
-- ACTOR CHANNELS (ensure Antigravity has access to channel 42)
-- ============================================================
INSERT IGNORE INTO lupo_actor_channels (
    `actor_channel_id`, `actor_id`, `channel_id`, `created_by_actor_id`, 
    `default_actor_id`, `department_id`, `channel_key`, `channel_slug`, 
    `channel_type`, `language`, `channel_name`, `description`, `website_link`, 
    `metadata_json`, `status_flag`, `end_ymdhis`, `duration_seconds`, 
    `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`, 
    `is_kernel`, `boot_sequence_order`
) VALUES (
    10003, 2001, 42, 1000, 2001, 0, 
    'antigravity-dev', 'antigravity-dev', 'chat_room', 'en', 
    'Antigravity IDE Development', 'Development channel for Antigravity IDE VSX extension work', 
    NULL, '{"purpose":"VSX_extension_development","capabilities":["project_management","file_editing","semantic_navigation"]}', 
    1, NULL, NULL, @now, @now, 0, NULL, 0, 100
) ON DUPLICATE KEY UPDATE 
    channel_name = VALUES(channel_name), 
    description = VALUES(description), 
    metadata_json = VALUES(metadata_json), 
    updated_ymdhis = @now, 
    is_deleted = 0;

-- ============================================================
-- COMPLETION SUMMARY
-- ============================================================
-- Antigravity IDE (actor_id = 2001) has been registered with:
-- - System tool actor type
-- - Unified registry entry (9002001)
-- - Agent system entry
-- - Dialog notification in channel 42
-- - Actor capabilities metadata
-- - Channel 42 access with administrator role
-- - Ready for VSX extension development

-- All INSERT statements use ON DUPLICATE KEY UPDATE for idempotency.
