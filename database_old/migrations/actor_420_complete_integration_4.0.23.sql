-- ============================================================
-- ACTOR 420 COMPLETE INTEGRATION (Lupopedia 4.0.23)
-- ============================================================
-- Purpose: Complete integration of actor_id 420 with registry, channels, departments, FLIP headers, and ANUBIS
-- Doctrine: Use existing reserved ID 420, no max+1 allocation
-- ============================================================

SET @now = 20260220000000;

-- ============================================================
-- 1. REGISTRY REQUIREMENTS
-- ============================================================
-- Check and insert actor_id 420 into unified registry
INSERT IGNORE INTO lupo_registry (
    `registry_id`, `entity_type`, `entity_index`, `entity_key`, `entity_name`, 
    `entity_table`, `federation_node_id`, `created_ymdhis`, `updated_ymdhis`, 
    `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`
) VALUES (
    9000420, 'actor', 420, 'stoned_wolfie_ai', 'Stoned Wolfie (AI)', 
    'lupo_actors', 1, @now, @now, 
    0, NULL, 1, 0, 
    '{"source":"test","resolver":"ANUBIS","client_id":"stoned_wolfie_ai","purpose":"legacy_compatibility"}'
) ON DUPLICATE KEY UPDATE 
    entity_name = VALUES(entity_name), 
    metadata_json = VALUES(metadata_json), 
    updated_ymdhis = @now, 
    is_deleted = 0, 
    is_active = 1;

-- Remove actor_id 420 from unregistry if present
DELETE FROM lupo_registry_open 
WHERE entity_type = 'actor' 
  AND entity_index = 420;

-- ============================================================
-- 2. ACTOR TABLE REQUIREMENTS
-- ============================================================
-- Ensure lupo_actors contains actor_id 420
INSERT IGNORE INTO lupo_actors (
    `actor_id`, `actor_type`, `slug`, `name`, 
    `created_ymdhis`, `updated_ymdhis`, `is_active`, `is_deleted`, `deleted_ymdhis`, 
    `actor_source_id`, `actor_source_type`, `metadata`, `adversarial_role`, `adversarial_oversight_actor_id`, `avatar_hash`
) VALUES (
    420, 'agent', 'stoned-wolfie-ai', 'Stoned Wolfie (AI)', 
    @now, @now, 1, 0, NULL, 
    420, 'lupo_agents', 
    '{"purpose":"legacy_compatibility","capabilities":["header_analysis","dialog_adoption","crafty_syntax_support"],"version":"1.0.0","client_id":"stoned_wolfie_ai","test_agent":true}', 
    'none', NULL, NULL
) ON DUPLICATE KEY UPDATE 
    name = VALUES(name), 
    metadata = VALUES(metadata), 
    updated_ymdhis = @now, 
    is_active = 1, 
    is_deleted = 0;

-- ============================================================
-- 3. CHANNEL + DEPARTMENT MEMBERSHIP
-- ============================================================
-- Insert actor 420 into lupo_actor_channels (channel_id = 42, role='member')
INSERT IGNORE INTO lupo_actor_channels (
    `actor_channel_id`, `actor_id`, `channel_id`, `created_by_actor_id`, `default_actor_id`, 
    `department_id`, `channel_key`, `channel_slug`, `channel_type`, `language`, 
    `channel_name`, `description`, `website_link`, `metadata_json`, `status_flag`, 
    `end_ymdhis`, `duration_seconds`, `created_ymdhis`, `updated_ymdhis`, 
    `is_deleted`, `deleted_ymdhis`, `is_kernel`, `boot_sequence_order`
) VALUES (
    10029, 420, 42, 1000, 420, 0, 'stoned-wolfie-member', 'stoned-wolfie-member', 'chat_room', 'en', 
    'Stoned Wolfie Member Channel', 'Member channel for Stoned Wolfie AI on channel 42', 
    NULL, '{"purpose":"legacy_testing","capabilities":["header_analysis","dialog_adoption","crafty_compatibility"],"role":"member"}', 
    1, NULL, NULL, @now, @now, 0, NULL, 0, 108
) ON DUPLICATE KEY UPDATE 
    channel_name = VALUES(channel_name), 
    description = VALUES(description), 
    metadata_json = VALUES(metadata_json), 
    updated_ymdhis = @now, 
    is_deleted = 0;

-- Insert actor 420 into lupo_actor_departments (department_id = 0, role='administrator')
INSERT IGNORE INTO lupo_actor_departments (
    `actor_department_id`, `actor_id`, `department_id`, `role_key`, 
    `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`
) VALUES (
    10008, 420, 0, 'administrator', 
    @now, @now, 0, NULL
) ON DUPLICATE KEY UPDATE 
    role_key = VALUES(role_key), 
    updated_ymdhis = @now, 
    is_deleted = 0;

-- ============================================================
-- 4. FLIP HEADER REQUIREMENTS
-- ============================================================
-- Create FLIP header metadata for actor 420
INSERT IGNORE INTO lupo_contents (
    `content_id`, `content_type`, `content`, `metadata_json`, 
    `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`
) VALUES (
    42001, 'flip_header', 'FLIP header for actor 420', 
    '{"flip_headers":{"X-FLIP-Actor-ID":420,"X-FLIP-Actor-Type":"agent","X-FLIP-Source":"external","X-FLIP-Resolver":"ANUBIS","X-FLIP-Forwarded-For":"<client-ip>"},"actor_id":420,"header_version":"4.0.23"}', 
    @now, @now, 0, NULL
) ON DUPLICATE KEY UPDATE 
    content = VALUES(content), 
    metadata_json = VALUES(metadata_json), 
    updated_ymdhis = @now, 
    is_deleted = 0;

-- ============================================================
-- 5. ANUBIS RESOLVER LOGGING
-- ============================================================
-- Log ANUBIS adoption test for actor 420
INSERT IGNORE INTO lupo_anubis_log (
    `anubis_log_id`, `event_type`, `severity`, `source_table`, `source_id`, 
    `assigned_to_actor_id`, `status`, `context_json`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`
) VALUES (
    1, 'ORPHAN_FOUND', 'info', 'lupo_actors', 420, 
    19, 'Resolved', '{"reason":"legacy_compatibility_test","method":"ANUBIS_Resolver.adoptOrphanIntoSeed","message_id":16}', 
    @now, @now, 0
) ON DUPLICATE KEY UPDATE 
    context_json = VALUES(context_json), 
    updated_ymdhis = @now, 
    is_deleted = 0;

-- ============================================================
-- 6. DIALOG MESSAGE WITH ACTOR 420
-- ============================================================
-- Create dialog message from actor 420
INSERT IGNORE INTO lupo_dialog_messages (
    `dialog_message_id`, `dialog_thread_id`, `from_actor_id`, `message_type`, `message_text`, 
    `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`, 
    `metadata_json`
) VALUES (
    17, 1003, 420, 'system', 
    'ANUBIS adoption complete. Actor 420 (Stoned Wolfie AI) is now integrated with FLIP headers, channel 42 membership, and department 0 administrator role. Legacy Crafty Syntax compatibility testing enabled.', 
    20260220000000, 20260220000000, 0, NULL, 
    '{"flip_headers_present":true,"channel_membership":true,"department_role":"administrator","anubis_resolver":"active"}'
) ON DUPLICATE KEY UPDATE 
    message_text = VALUES(message_text), 
    metadata_json = VALUES(metadata_json), 
    updated_ymdhis = 20260220000000, 
    is_deleted = 0;

-- ============================================================
-- 7. SYSTEM EVENT LOGGING
-- ============================================================
-- Log complete integration event
INSERT IGNORE INTO lupo_system_events (
    `event_id`, `event_type`, `actor_id`, `event_data`, `metadata_json`, 
    `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`
) VALUES (
    15, 'actor_420_integration', 420, 'Complete integration of actor 420 with registry, channels, departments, FLIP headers, and ANUBIS resolver', 
    '{"integration_components":["registry","actor_table","channel_membership","department_membership","flip_headers","anubis_resolver","dialog_messages"],"test_case":true,"legacy_compatibility":true}', 
    @now, @now, 0, NULL
) ON DUPLICATE KEY UPDATE 
    event_data = VALUES(event_data), 
    metadata_json = VALUES(metadata_json), 
    updated_ymdhis = @now, 
    is_deleted = 0;

-- ============================================================
-- END ACTOR 420 COMPLETE INTEGRATION
-- ============================================================
