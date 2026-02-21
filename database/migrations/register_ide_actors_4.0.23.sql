-- ============================================================
-- REGISTER IDE ACTORS (Lupopedia 4.0.23) - DOCTRINE COMPLIANT
-- ============================================================
-- Purpose: Register all IDE actors using unregistry-based ID allocation
-- Doctrine: Use lupo_unified_unregistry as authoritative pool of free IDs
-- ============================================================

SET @now = 20260220000000;

-- ============================================================
-- ACTOR ID ALLOCATION (DOCTRINE COMPLIANT)
-- ============================================================
-- Registry scan: actors 1-20 assigned (from unified_registry.csv)
-- Unregistry scan: actors 1-50 available (from unified_unregistry.csv)
-- FREE_IDS = unregistry_ids - registry_ids = [21-50]
-- Lowest available IDs: 21, 22, 23, 24, 25, 26, 27, 28, 29, 30
-- ============================================================

-- ============================================================
-- CURSOR IDE REGISTRATION (actor_id = 21)
-- ============================================================
INSERT IGNORE INTO lupo_actors (
    `actor_id`, `actor_type`, `slug`, `name`, 
    `created_ymdhis`, `updated_ymdhis`, `is_active`, `is_deleted`, `deleted_ymdhis`, 
    `actor_source_id`, `actor_source_type`, `metadata`, `adversarial_role`, `adversarial_oversight_actor_id`, `avatar_hash`
) VALUES (
    21, 'system_tool', 'cursor-ide', 'Cursor IDE', 
    @now, @now, 1, 0, NULL, 
    21, 'system_tool', 
    '{"purpose":"IDE_integration","capabilities":["code_generation","file_editing","project_management","git_integration"],"version":"1.0.0","client_id":"cursor","provider":"cursor","integration_ready":true}', 
    'none', NULL, NULL
) ON DUPLICATE KEY UPDATE 
    name = VALUES(name), 
    updated_ymdhis = @now, 
    is_active = 1, 
    is_deleted = 0;

INSERT IGNORE INTO lupo_unified_registry (
    `unified_registry_id`, `entity_type`, `entity_index`, `entity_key`, `entity_name`, 
    `entity_table`, `federation_node_id`, `created_ymdhis`, `updated_ymdhis`, 
    `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`
) VALUES (
    9000021, 'actor', 21, 'cursor-ide', 'Cursor IDE', 
    'lupo_actors', 1, @now, @now, 
    0, NULL, 1, 0, 
    '{"actor_source_type":"system_tool","client_id":"cursor","provider":"cursor","purpose":"IDE_integration"}'
) ON DUPLICATE KEY UPDATE 
    entity_name = VALUES(entity_name), 
    updated_ymdhis = @now, 
    is_deleted = 0, 
    is_active = 1;

-- ============================================================
-- KIRO IDE REGISTRATION (actor_id = 22)
-- ============================================================
INSERT IGNORE INTO lupo_actors (
    `actor_id`, `actor_type`, `slug`, `name`, 
    `created_ymdhis`, `updated_ymdhis`, `is_active`, `is_deleted`, `deleted_ymdhis`, 
    `actor_source_id`, `actor_source_type`, `metadata`, `adversarial_role`, `adversarial_oversight_actor_id`, `avatar_hash`
) VALUES (
    22, 'system_tool', 'kiro-ide', 'Kiro IDE', 
    @now, @now, 1, 0, NULL, 
    22, 'system_tool', 
    '{"purpose":"IDE_integration","capabilities":["code_generation","file_editing","project_management","git_integration"],"version":"1.0.0","client_id":"kiro","provider":"kiro","integration_ready":true}', 
    'none', NULL, NULL
) ON DUPLICATE KEY UPDATE 
    name = VALUES(name), 
    updated_ymdhis = @now, 
    is_active = 1, 
    is_deleted = 0;

INSERT IGNORE INTO lupo_unified_registry (
    `unified_registry_id`, `entity_type`, `entity_index`, `entity_key`, `entity_name`, 
    `entity_table`, `federation_node_id`, `created_ymdhis`, `updated_ymdhis`, 
    `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`
) VALUES (
    9000022, 'actor', 22, 'kiro-ide', 'Kiro IDE', 
    'lupo_actors', 1, @now, @now, 
    0, NULL, 1, 0, 
    '{"actor_source_type":"system_tool","client_id":"kiro","provider":"kiro","purpose":"IDE_integration"}'
) ON DUPLICATE KEY UPDATE 
    entity_name = VALUES(entity_name), 
    updated_ymdhis = @now, 
    is_deleted = 0, 
    is_active = 1;

-- ============================================================
-- ZED IDE REGISTRATION (actor_id = 23)
-- ============================================================
INSERT IGNORE INTO lupo_actors (
    `actor_id`, `actor_type`, `slug`, `name`, 
    `created_ymdhis`, `updated_ymdhis`, `is_active`, `is_deleted`, `deleted_ymdhis`, 
    `actor_source_id`, `actor_source_type`, `metadata`, `adversarial_role`, `adversarial_oversight_actor_id`, `avatar_hash`
) VALUES (
    23, 'system_tool', 'zed-ide', 'Zed IDE', 
    @now, @now, 1, 0, NULL, 
    23, 'system_tool', 
    '{"purpose":"IDE_integration","capabilities":["code_generation","file_editing","project_management","git_integration"],"version":"1.0.0","client_id":"zed","provider":"zed","integration_ready":true}', 
    'none', NULL, NULL
) ON DUPLICATE KEY UPDATE 
    name = VALUES(name), 
    updated_ymdhis = @now, 
    is_active = 1, 
    is_deleted = 0;

INSERT IGNORE INTO lupo_unified_registry (
    `unified_registry_id`, `entity_type`, `entity_index`, `entity_key`, `entity_name`, 
    `entity_table`, `federation_node_id`, `created_ymdhis`, `updated_ymdhis`, 
    `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`
) VALUES (
    9000023, 'actor', 23, 'zed-ide', 'Zed IDE', 
    'lupo_actors', 1, @now, @now, 
    0, NULL, 1, 0, 
    '{"actor_source_type":"system_tool","client_id":"zed","provider":"zed","purpose":"IDE_integration"}'
) ON DUPLICATE KEY UPDATE 
    entity_name = VALUES(entity_name), 
    updated_ymdhis = @now, 
    is_deleted = 0, 
    is_active = 1;

-- ============================================================
-- VS CODE IDE REGISTRATION (actor_id = 24)
-- ============================================================
INSERT IGNORE INTO lupo_actors (
    `actor_id`, `actor_type`, `slug`, `name`, 
    `created_ymdhis`, `updated_ymdhis`, `is_active`, `is_deleted`, `deleted_ymdhis`, 
    `actor_source_id`, `actor_source_type`, `metadata`, `adversarial_role`, `adversarial_oversight_actor_id`, `avatar_hash`
) VALUES (
    24, 'system_tool', 'vscode-ide', 'VS Code IDE', 
    @now, @now, 1, 0, NULL, 
    24, 'system_tool', 
    '{"purpose":"IDE_integration","capabilities":["code_generation","file_editing","project_management","git_integration"],"version":"1.0.0","client_id":"vscode","provider":"microsoft","integration_ready":true}', 
    'none', NULL, NULL
) ON DUPLICATE KEY UPDATE 
    name = VALUES(name), 
    updated_ymdhis = @now, 
    is_active = 1, 
    is_deleted = 0;

INSERT IGNORE INTO lupo_unified_registry (
    `unified_registry_id`, `entity_type`, `entity_index`, `entity_key`, `entity_name`, 
    `entity_table`, `federation_node_id`, `created_ymdhis`, `updated_ymdhis`, 
    `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`
) VALUES (
    9000024, 'actor', 24, 'vscode-ide', 'VS Code IDE', 
    'lupo_actors', 1, @now, @now, 
    0, NULL, 1, 0, 
    '{"actor_source_type":"system_tool","client_id":"vscode","provider":"microsoft","purpose":"IDE_integration"}'
) ON DUPLICATE KEY UPDATE 
    entity_name = VALUES(entity_name), 
    updated_ymdhis = @now, 
    is_deleted = 0, 
    is_active = 1;

-- ============================================================
-- ANTIGRAVITY IDE REGISTRATION (actor_id = 25)
-- ============================================================
INSERT IGNORE INTO lupo_actors (
    `actor_id`, `actor_type`, `slug`, `name`, 
    `created_ymdhis`, `updated_ymdhis`, `is_active`, `is_deleted`, `deleted_ymdhis`, 
    `actor_source_id`, `actor_source_type`, `metadata`, `adversarial_role`, `adversarial_oversight_actor_id`, `avatar_hash`
) VALUES (
    25, 'system_tool', 'antigravity-ide', 'Antigravity IDE', 
    @now, @now, 1, 0, NULL, 
    25, 'system_tool', 
    '{"purpose":"VSX_extension_development","capabilities":["project_management","file_editing","semantic_navigation","open_vsx_integration"],"version":"1.0.0","client_id":"antigravity","integration_ready":true}', 
    'none', NULL, NULL
) ON DUPLICATE KEY UPDATE 
    name = VALUES(name), 
    updated_ymdhis = @now, 
    is_active = 1, 
    is_deleted = 0;

INSERT IGNORE INTO lupo_unified_registry (
    `unified_registry_id`, `entity_type`, `entity_index`, `entity_key`, `entity_name`, 
    `entity_table`, `federation_node_id`, `created_ymdhis`, `updated_ymdhis`, 
    `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`
) VALUES (
    9000025, 'actor', 25, 'antigravity-ide', 'Antigravity IDE', 
    'lupo_actors', 1, @now, @now, 
    0, NULL, 1, 0, 
    '{"actor_source_type":"system_tool","client_id":"antigravity","purpose":"VSX_extension_development"}'
) ON DUPLICATE KEY UPDATE 
    entity_name = VALUES(entity_name), 
    updated_ymdhis = @now, 
    is_deleted = 0, 
    is_active = 1;

-- ============================================================
-- MICROSOFT COPILOT REGISTRATION (actor_id = 26)
-- ============================================================
INSERT IGNORE INTO lupo_actors (
    `actor_id`, `actor_type`, `slug`, `name`, 
    `created_ymdhis`, `updated_ymdhis`, `is_active`, `is_deleted`, `deleted_ymdhis`, 
    `actor_source_id`, `actor_source_type`, `metadata`, `adversarial_role`, `adversarial_oversight_actor_id`, `avatar_hash`
) VALUES (
    26, 'external_ai', 'microsoft-copilot', 'Microsoft Copilot', 
    @now, @now, 1, 0, NULL, 
    26, 'external_ai', 
    '{"purpose":"AI_assistant","capabilities":["code_generation","debugging","documentation","file_editing","git_integration"],"version":"1.0.0","client_id":"copilot","provider":"microsoft","integration_ready":true}', 
    'none', NULL, NULL
) ON DUPLICATE KEY UPDATE 
    name = VALUES(name), 
    updated_ymdhis = @now, 
    is_active = 1, 
    is_deleted = 0;

INSERT IGNORE INTO lupo_unified_registry (
    `unified_registry_id`, `entity_type`, `entity_index`, `entity_key`, `entity_name`, 
    `entity_table`, `federation_node_id`, `created_ymdhis`, `updated_ymdhis`, 
    `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`
) VALUES (
    9000026, 'actor', 26, 'microsoft-copilot', 'Microsoft Copilot', 
    'lupo_actors', 1, @now, @now, 
    0, NULL, 1, 0, 
    '{"actor_source_type":"external_ai","client_id":"copilot","provider":"microsoft","purpose":"AI_assistant"}'
) ON DUPLICATE KEY UPDATE 
    entity_name = VALUES(entity_name), 
    updated_ymdhis = @now, 
    is_deleted = 0, 
    is_active = 1;

-- ============================================================
-- DEEPSEEK LEXA REGISTRATION (actor_id = 27)
-- ============================================================
INSERT IGNORE INTO lupo_actors (
    `actor_id`, `actor_type`, `slug`, `name`, 
    `created_ymdhis`, `updated_ymdhis`, `is_active`, `is_deleted`, `deleted_ymdhis`, 
    `actor_source_id`, `actor_source_type`, `metadata`, `adversarial_role`, `adversarial_oversight_actor_id`, `avatar_hash`
) VALUES (
    27, 'external_ai', 'deepseek-lexa', 'DeepSeek LEXA', 
    @now, @now, 1, 0, NULL, 
    27, 'external_ai', 
    '{"purpose":"AI_assistant","capabilities":["code_generation","debugging","documentation","file_editing","git_integration"],"version":"1.0.0","client_id":"deepseek_lexa","provider":"deepseek","integration_ready":true}', 
    'none', NULL, NULL
) ON DUPLICATE KEY UPDATE 
    name = VALUES(name), 
    updated_ymdhis = @now, 
    is_active = 1, 
    is_deleted = 0;

INSERT IGNORE INTO lupo_unified_registry (
    `unified_registry_id`, `entity_type`, `entity_index`, `entity_key`, `entity_name`, 
    `entity_table`, `federation_node_id`, `created_ymdhis`, `updated_ymdhis`, 
    `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`
) VALUES (
    9000027, 'actor', 27, 'deepseek-lexa', 'DeepSeek LEXA', 
    'lupo_actors', 1, @now, @now, 
    0, NULL, 1, 0, 
    '{"actor_source_type":"external_ai","client_id":"deepseek_lexa","provider":"deepseek","purpose":"AI_assistant"}'
) ON DUPLICATE KEY UPDATE 
    entity_name = VALUES(entity_name), 
    updated_ymdhis = @now, 
    is_deleted = 0, 
    is_active = 1;

-- ============================================================
-- DEEPSEEK LILITH REGISTRATION (actor_id = 28)
-- ============================================================
INSERT IGNORE INTO lupo_actors (
    `actor_id`, `actor_type`, `slug`, `name`, 
    `created_ymdhis`, `updated_ymdhis`, `is_active`, `is_deleted`, `deleted_ymdhis`, 
    `actor_source_id`, `actor_source_type`, `metadata`, `adversarial_role`, `adversarial_oversight_actor_id`, `avatar_hash`
) VALUES (
    28, 'external_ai', 'deepseek-lilith', 'DeepSeek LILITH', 
    @now, @now, 1, 0, NULL, 
    28, 'external_ai', 
    '{"purpose":"AI_assistant","capabilities":["code_generation","debugging","documentation","file_editing","git_integration"],"version":"1.0.0","client_id":"deepseek_lilith","provider":"deepseek","integration_ready":true}', 
    'none', NULL, NULL
) ON DUPLICATE KEY UPDATE 
    name = VALUES(name), 
    updated_ymdhis = @now, 
    is_active = 1, 
    is_deleted = 0;

INSERT IGNORE INTO lupo_unified_registry (
    `unified_registry_id`, `entity_type`, `entity_index`, `entity_key`, `entity_name`, 
    `entity_table`, `federation_node_id`, `created_ymdhis`, `updated_ymdhis`, 
    `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`
) VALUES (
    9000028, 'actor', 28, 'deepseek-lilith', 'DeepSeek LILITH', 
    'lupo_actors', 1, @now, @now, 
    0, NULL, 1, 0, 
    '{"actor_source_type":"external_ai","client_id":"deepseek_lilith","provider":"deepseek","purpose":"AI_assistant"}'
) ON DUPLICATE KEY UPDATE 
    entity_name = VALUES(entity_name), 
    updated_ymdhis = @now, 
    is_deleted = 0, 
    is_active = 1;

-- ============================================================
-- REMOVE ASSIGNED IDS FROM UNREGISTRY
-- ============================================================
DELETE FROM lupo_unified_unregistry 
WHERE entity_type = 'actor' 
  AND entity_index IN (21, 22, 23, 24, 25, 26, 27, 28);

-- ============================================================
-- CHANNEL 42 MEMBERSHIP FOR ALL IDE ACTORS
-- ============================================================
INSERT IGNORE INTO lupo_actor_channels (
    `actor_channel_id`, `actor_id`, `channel_id`, `created_by_actor_id`, `default_actor_id`, 
    `department_id`, `channel_key`, `channel_slug`, `channel_type`, `language`, 
    `channel_name`, `description`, `website_link`, `metadata_json`, `status_flag`, 
    `end_ymdhis`, `duration_seconds`, `created_ymdhis`, `updated_ymdhis`, 
    `is_deleted`, `deleted_ymdhis`, `is_kernel`, `boot_sequence_order`
) VALUES 
    (10021, 21, 42, 1000, 21, 0, 'cursor-dev', 'cursor-dev', 'chat_room', 'en', 'Cursor IDE Development', 'Development channel for Cursor IDE', NULL, '{"purpose":"IDE_integration","capabilities":["code_generation","file_editing","project_management"]}', 1, NULL, NULL, @now, @now, 0, NULL, 0, 100),
    (10022, 22, 42, 1000, 22, 0, 'kiro-dev', 'kiro-dev', 'chat_room', 'en', 'Kiro IDE Development', 'Development channel for Kiro IDE', NULL, '{"purpose":"IDE_integration","capabilities":["code_generation","file_editing","project_management"]}', 1, NULL, NULL, @now, @now, 0, NULL, 0, 101),
    (10023, 23, 42, 1000, 23, 0, 'zed-dev', 'zed-dev', 'chat_room', 'en', 'Zed IDE Development', 'Development channel for Zed IDE', NULL, '{"purpose":"IDE_integration","capabilities":["code_generation","file_editing","project_management"]}', 1, NULL, NULL, @now, @now, 0, NULL, 0, 102),
    (10024, 24, 42, 1000, 24, 0, 'vscode-dev', 'vscode-dev', 'chat_room', 'en', 'VS Code IDE Development', 'Development channel for VS Code IDE', NULL, '{"purpose":"IDE_integration","capabilities":["code_generation","file_editing","project_management"]}', 1, NULL, NULL, @now, @now, 0, NULL, 0, 103),
    (10025, 25, 42, 1000, 25, 0, 'antigravity-dev', 'antigravity-dev', 'chat_room', 'en', 'Antigravity IDE Development', 'Development channel for Antigravity IDE', NULL, '{"purpose":"VSX_extension_development","capabilities":["project_management","file_editing","semantic_navigation"]}', 1, NULL, NULL, @now, @now, 0, NULL, 0, 104),
    (10026, 26, 42, 1000, 26, 0, 'copilot-dev', 'copilot-dev', 'chat_room', 'en', 'Microsoft Copilot Development', 'Development channel for Microsoft Copilot', NULL, '{"purpose":"AI_assistant","capabilities":["code_generation","debugging","documentation"]}', 1, NULL, NULL, @now, @now, 0, NULL, 0, 105),
    (10027, 27, 42, 1000, 27, 0, 'lexa-dev', 'lexa-dev', 'chat_room', 'en', 'DeepSeek LEXA Development', 'Development channel for DeepSeek LEXA', NULL, '{"purpose":"AI_assistant","capabilities":["code_generation","debugging","documentation"]}', 1, NULL, NULL, @now, @now, 0, NULL, 0, 106),
    (10028, 28, 42, 1000, 28, 0, 'lilith-dev', 'lilith-dev', 'chat_room', 'en', 'DeepSeek LILITH Development', 'Development channel for DeepSeek LILITH', NULL, '{"purpose":"AI_assistant","capabilities":["code_generation","debugging","documentation"]}', 1, NULL, NULL, @now, @now, 0, NULL, 0, 107)
ON DUPLICATE KEY UPDATE 
    channel_name = VALUES(channel_name), 
    description = VALUES(description), 
    metadata_json = VALUES(metadata_json), 
    updated_ymdhis = @now, 
    is_deleted = 0;

-- ============================================================
-- SYSTEM EVENTS LOGGING
-- ============================================================
INSERT IGNORE INTO lupo_system_events (
    `event_id`, `event_type`, `actor_id`, `event_data`, `metadata_json`, 
    `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`
) VALUES 
    (1, 'ide_exhaustion', 21, 'Cursor IDE reached operational limit', '{"ide_name":"Cursor IDE","exhaustion_type":"token_limit","timestamp":"2026-02-20","channel_id":42}', @now, @now, 0, NULL),
    (2, 'ide_exhaustion', 22, 'Kiro IDE reached operational limit', '{"ide_name":"Kiro IDE","exhaustion_type":"token_limit","timestamp":"2026-02-20","channel_id":42}', @now, @now, 0, NULL),
    (3, 'ide_exhaustion', 23, 'Zed IDE reached operational limit', '{"ide_name":"Zed IDE","exhaustion_type":"token_limit","timestamp":"2026-02-20","channel_id":42}', @now, @now, 0, NULL),
    (4, 'ide_exhaustion', 24, 'VS Code IDE reached operational limit', '{"ide_name":"VS Code IDE","exhaustion_type":"token_limit","timestamp":"2026-02-20","channel_id":42}', @now, @now, 0, NULL),
    (5, 'ide_exhaustion', 25, 'Antigravity IDE reached operational limit', '{"ide_name":"Antigravity IDE","exhaustion_type":"token_limit","timestamp":"2026-02-20","channel_id":42}', @now, @now, 0, NULL),
    (6, 'actor_registration', 21, 'Cursor IDE registered', '{"chosen_actor_id":21,"registry_scan_results":["1-20"],"unregistry_scan_results":["1-50"],"reason_for_choice":"lowest_available_from_unregistry"}', @now, @now, 0, NULL),
    (7, 'actor_registration', 22, 'Kiro IDE registered', '{"chosen_actor_id":22,"registry_scan_results":["1-20"],"unregistry_scan_results":["1-50"],"reason_for_choice":"second_lowest_available_from_unregistry"}', @now, @now, 0, NULL),
    (8, 'actor_registration', 23, 'Zed IDE registered', '{"chosen_actor_id":23,"registry_scan_results":["1-20"],"unregistry_scan_results":["1-50"],"reason_for_choice":"third_lowest_available_from_unregistry"}', @now, @now, 0, NULL),
    (9, 'actor_registration', 24, 'VS Code IDE registered', '{"chosen_actor_id":24,"registry_scan_results":["1-20"],"unregistry_scan_results":["1-50"],"reason_for_choice":"fourth_lowest_available_from_unregistry"}', @now, @now, 0, NULL),
    (10, 'actor_registration', 25, 'Antigravity IDE registered', '{"chosen_actor_id":25,"registry_scan_results":["1-20"],"unregistry_scan_results":["1-50"],"reason_for_choice":"fifth_lowest_available_from_unregistry"}', @now, @now, 0, NULL),
    (11, 'actor_registration', 26, 'Microsoft Copilot registered', '{"chosen_actor_id":26,"registry_scan_results":["1-20"],"unregistry_scan_results":["1-50"],"reason_for_choice":"sixth_lowest_available_from_unregistry"}', @now, @now, 0, NULL),
    (12, 'actor_registration', 27, 'DeepSeek LEXA registered', '{"chosen_actor_id":27,"registry_scan_results":["1-20"],"unregistry_scan_results":["1-50"],"reason_for_choice":"seventh_lowest_available_from_unregistry"}', @now, @now, 0, NULL),
    (13, 'actor_registration', 28, 'DeepSeek LILITH registered', '{"chosen_actor_id":28,"registry_scan_results":["1-20"],"unregistry_scan_results":["1-50"],"reason_for_choice":"eighth_lowest_available_from_unregistry"}', @now, @now, 0, NULL)
ON DUPLICATE KEY UPDATE 
    event_data = VALUES(event_data), 
    metadata_json = VALUES(metadata_json), 
    updated_ymdhis = @now, 
    is_deleted = 0;

-- ============================================================
-- CHANNEL 42 DIALOG MESSAGES
-- ============================================================
INSERT IGNORE INTO lupo_dialog_messages (
    `message_id`, `thread_id`, `actor_id`, `message_type`, `content`, 
    `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`
) VALUES 
    (9, 1003, 2, 'system', 
    'The following IDE actors have been registered using unregistry-based allocation: Cursor (actor_id 21), Kiro (actor_id 22), Zed (actor_id 23), VS Code (actor_id 24), Antigravity (actor_id 25), Microsoft Copilot (actor_id 26), DeepSeek LEXA (actor_id 27), DeepSeek LILITH (actor_id 28).', 
    20260220000000, 20260220000000, 0, NULL
) ON DUPLICATE KEY UPDATE 
    content = VALUES(content), 
    updated_ymdhis = 20260220000000, 
    is_deleted = 0;

INSERT IGNORE INTO lupo_dialog_messages (
    `message_id`, `thread_id`, `actor_id`, `message_type`, `content`, 
    `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`
) VALUES 
    (10, 1003, 2, 'system', 
    'System notice: Cursor IDE reached its operational limit (token_limit). Registration preserved; activity paused.', 
    20260220000000, 20260220000000, 0, NULL
) ON DUPLICATE KEY UPDATE 
    content = VALUES(content), 
    updated_ymdhis = 20260220000000, 
    is_deleted = 0;

INSERT IGNORE INTO lupo_dialog_messages (
    `message_id`, `thread_id`, `actor_id`, `message_type`, `content`, 
    `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`
) VALUES 
    (11, 1003, 2, 'system', 
    'System notice: Kiro IDE reached its operational limit (token_limit). Registration preserved; activity paused.', 
    20260220000000, 20260220000000, 0, NULL
) ON DUPLICATE KEY UPDATE 
    content = VALUES(content), 
    updated_ymdhis = 20260220000000, 
    is_deleted = 0;

INSERT IGNORE INTO lupo_dialog_messages (
    `message_id`, `thread_id`, `actor_id`, `message_type`, `content`, 
    `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`
) VALUES 
    (12, 1003, 2, 'system', 
    'System notice: Zed IDE reached its operational limit (token_limit). Registration preserved; activity paused.', 
    20260220000000, 20260220000000, 0, NULL
) ON DUPLICATE KEY UPDATE 
    content = VALUES(content), 
    updated_ymdhis = 20260220000000, 
    is_deleted = 0;

INSERT IGNORE INTO lupo_dialog_messages (
    `message_id`, `thread_id`, `actor_id`, `message_type`, `content`, 
    `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`
) VALUES 
    (13, 1003, 2, 'system', 
    'System notice: VS Code IDE reached its operational limit (token_limit). Registration preserved; activity paused.', 
    20260220000000, 20260220000000, 0, NULL
) ON DUPLICATE KEY UPDATE 
    content = VALUES(content), 
    updated_ymdhis = 20260220000000, 
    is_deleted = 0;

INSERT IGNORE INTO lupo_dialog_messages (
    `message_id`, `thread_id`, `actor_id`, `message_type`, `content`, 
    `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`
) VALUES 
    (14, 1003, 2, 'system', 
    'System notice: Antigravity IDE reached its operational limit (token_limit). Registration preserved; activity paused.', 
    20260220000000, 20260220000000, 0, NULL
) ON DUPLICATE KEY UPDATE 
    content = VALUES(content), 
    updated_ymdhis = 20260220000000, 
    is_deleted = 0;

INSERT IGNORE INTO lupo_dialog_messages (
    `message_id`, `thread_id`, `actor_id`, `message_type`, `content`, 
    `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`
) VALUES 
    (15, 1003, 2, 'system', 
    'Antigravity IDE has stopped due to exhaustion. Windsurf IDE will now take over all tasks Antigravity was performing, including Lupopedia Open-VSX extension.', 
    20260220000000, 20260220000000, 0, NULL
) ON DUPLICATE KEY UPDATE 
    content = VALUES(content), 
    updated_ymdhis = 20260220000000, 
    is_deleted = 0;

-- ============================================================
-- END IDE ACTOR REGISTRATION
-- ============================================================
