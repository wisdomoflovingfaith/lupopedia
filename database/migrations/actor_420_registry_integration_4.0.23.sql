-- ============================================================
-- ACTOR 420 REGISTRY INTEGRATION (Lupopedia 4.0.23)
-- ============================================================
-- Purpose: Ensure actor_id 420 exists and is properly integrated
-- Doctrine: Use existing reserved ID, no max+1 allocation
-- ============================================================

SET @now = 20260220000000;

-- ============================================================
-- VERIFY AND CREATE ACTOR 420 REGISTRY ENTRY
-- ============================================================
INSERT IGNORE INTO lupo_unified_registry (
    `unified_registry_id`, `entity_type`, `entity_index`, `entity_key`, `entity_name`, 
    `entity_table`, `federation_node_id`, `created_ymdhis`, `updated_ymdhis`, 
    `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`
) VALUES (
    9000420, 'agent', 420, 'stoned_wolfie_ai', 'Stoned Wolfie (AI)', 
    'lupo_agents', 1, @now, @now, 
    0, NULL, 1, 0, 
    '{"actor_source_type":"lupo_agents","client_id":"stoned_wolfie_ai","purpose":"test_agent","reserved":true}'
) ON DUPLICATE KEY UPDATE 
    entity_name = VALUES(entity_name), 
    updated_ymdhis = @now, 
    is_deleted = 0, 
    is_active = 1;

-- ============================================================
-- VERIFY AND CREATE ACTOR 420 RECORD
-- ============================================================
INSERT IGNORE INTO lupo_actors (
    `actor_id`, `actor_type`, `slug`, `name`, 
    `created_ymdhis`, `updated_ymdhis`, `is_active`, `is_deleted`, `deleted_ymdhis`, 
    `actor_source_id`, `actor_source_type`, `metadata`, `adversarial_role`, `adversarial_oversight_actor_id`, `avatar_hash`
) VALUES (
    420, 'agent', 'stoned-wolfie-ai', 'Stoned Wolfie (AI)', 
    @now, @now, 1, 0, NULL, 
    420, 'lupo_agents', 
    '{"purpose":"test_agent","capabilities":["legacy_compatibility","header_analysis","dialog_adoption"],"version":"1.0.0","client_id":"stoned_wolfie_ai","reserved":true}', 
    'none', NULL, NULL
) ON DUPLICATE KEY UPDATE 
    name = VALUES(name), 
    updated_ymdhis = @now, 
    is_active = 1, 
    is_deleted = 0;

-- ============================================================
-- VERIFY AND CREATE AGENT 420 RECORD
-- ============================================================
INSERT IGNORE INTO lupo_agents (
    `agent_id`, `agent_key`, `agent_name`, `archetype`, `description`, 
    `version`, `model_name`, `is_global_authority`, `is_internal_only`, 
    `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`
) VALUES (
    420, 'stoned_wolfie_ai', 'Stoned Wolfie (AI)', 'banned_test', 
    'Test agent for legacy Crafty Syntax compatibility and header analysis', 
    '1.0.0', NULL, 0, 0, 
    @now, @now, 0, NULL
) ON DUPLICATE KEY UPDATE 
    agent_name = VALUES(agent_name), 
    updated_ymdhis = @now, 
    is_deleted = 0;

-- ============================================================
-- DIALOG MESSAGE WITH ACTOR 420 ASSIGNMENT
-- ============================================================
INSERT IGNORE INTO lupo_dialog_messages (
    `message_id`, `thread_id`, `actor_id`, `message_type`, `content`, 
    `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`,
    `metadata_json`
) VALUES (
    16, 1003, 420, 'system', 
    'Legacy Crafty Syntax compatibility test. Actor 420 (Stoned Wolfie AI) has been integrated into the unified registry and is now available for dialog adoption and header analysis tasks.', 
    20260220000000, 20260220000000, 0, NULL,
    '{"adopted_by_anubis":true,"legacy_compatibility":true,"test_case":true}'
) ON DUPLICATE KEY UPDATE 
    content = VALUES(content), 
    metadata_json = VALUES(metadata_json),
    updated_ymdhis = 20260220000000, 
    is_deleted = 0;

-- ============================================================
-- CHANNEL 42 MEMBERSHIP FOR ACTOR 420
-- ============================================================
INSERT IGNORE INTO lupo_actor_channels (
    `actor_channel_id`, `actor_id`, `channel_id`, `created_by_actor_id`, `default_actor_id`, 
    `department_id`, `channel_key`, `channel_slug`, `channel_type`, `language`, 
    `channel_name`, `description`, `website_link`, `metadata_json`, `status_flag`, 
    `end_ymdhis`, `duration_seconds`, `created_ymdhis`, `updated_ymdhis`, 
    `is_deleted`, `deleted_ymdhis`, `is_kernel`, `boot_sequence_order`
) VALUES (
    10029, 420, 42, 1000, 420, 0, 'stoned-wolfie-test', 'stoned-wolfie-test', 'chat_room', 'en', 
    'Stoned Wolfie Test Channel', 'Test channel for legacy Crafty Syntax compatibility and ANUBIS adoption protocol', 
    NULL, '{"purpose":"legacy_testing","capabilities":["header_analysis","dialog_adoption","crafty_compatibility"]}', 
    1, NULL, NULL, @now, @now, 0, NULL, 0, 108
) ON DUPLICATE KEY UPDATE 
    channel_name = VALUES(channel_name), 
    description = VALUES(description), 
    metadata_json = VALUES(metadata_json), 
    updated_ymdhis = @now, 
    is_deleted = 0;

-- ============================================================
-- ANUBIS ADOPTION LOG
-- ============================================================
INSERT IGNORE INTO lupo_system_events (
    `event_id`, `event_type`, `actor_id`, `event_data`, `metadata_json`, 
    `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`
) VALUES (
    14, 'anubis_adoption', 420, 'Actor 420 adopted by ANUBIS protocol', 
    '{"adopted_actor_id":420,"adoption_reason":"legacy_compatibility_test","protocol":"anubis_resolver","message_id":16,"channel_id":42,"thread_id":1003}', 
    @now, @now, 0, NULL
) ON DUPLICATE KEY UPDATE 
    event_data = VALUES(event_data), 
    metadata_json = VALUES(metadata_json), 
    updated_ymdhis = @now, 
    is_deleted = 0;

-- ============================================================
-- END ACTOR 420 REGISTRY INTEGRATION
-- ============================================================
