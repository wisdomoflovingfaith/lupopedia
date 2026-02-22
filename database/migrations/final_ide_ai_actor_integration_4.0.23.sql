-- ============================================================
-- FINAL IDE & AI ACTOR INTEGRATION (Lupopedia 4.0.23)
-- ============================================================
-- Purpose: Register all IDE and external AI actors using CSV-driven unregistry allocation
-- Actor IDs: FINAL - computed from CSV (FREE_IDS = unregistry - registry)
-- ============================================================

SET @now = 20260220000000;

-- ============================================================
-- ACTOR ID ALLOCATION (CSV-DRIVEN UNREGISTRY ALLOCATION)
-- ============================================================
-- Registry scan: actors 1-20 assigned (from lupo_registry.csv)
-- Unregistry scan: actors 1-50 available (from lupo_registry_open.csv)
-- FREE_IDS = unregistry_ids - registry_ids = [21-50]
-- FINAL ALLOCATIONS (CSV-driven):
--   Cursor IDE: 2031
--   Kiro IDE: 2032
--   Zed IDE: 2033
--   VS Code IDE: 2034
--   Antigravity IDE: 2035
--   Microsoft Copilot: 2036
--   DeepSeek LEXA: 2037
--   DeepSeek LILITH: 2038
-- ============================================================

-- ============================================================
-- CURSOR IDE REGISTRATION (actor_id = 2031)
-- ============================================================
INSERT IGNORE INTO lupo_actors (
    `actor_id`, `actor_type`, `slug`, `name`, 
    `created_ymdhis`, `updated_ymdhis`, `is_active`, `is_deleted`, `deleted_ymdhis`, 
    `actor_source_id`, `actor_source_type`, `metadata`, `adversarial_role`, `adversarial_oversight_actor_id`, `avatar_hash`
) VALUES (
    2031, 'system_tool', 'cursor-ide', 'Cursor IDE', 
    @now, @now, 1, 0, NULL, 
    2031, 'system_tool', 
    '{"purpose":"IDE_integration","capabilities":["code_generation","file_editing","project_management","git_integration"],"version":"1.0.0","client_id":"cursor","provider":"cursor","integration_ready":true}', 
    'none', NULL, NULL
) ON DUPLICATE KEY UPDATE 
    name = VALUES(name), 
    updated_ymdhis = @now, 
    is_active = 1, 
    is_deleted = 0;

INSERT IGNORE INTO lupo_registry (
    `unified_registry_id`, `entity_type`, `entity_index`, `entity_key`, `entity_name`, 
    `entity_table`, `federation_node_id`, `created_ymdhis`, `updated_ymdhis`, 
    `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`
) VALUES (
    9002031, 'actor', 2031, 'cursor-ide', 'Cursor IDE', 
    'lupo_actors', 1, @now, @now, 
    0, NULL, 1, 0, 
    '{"actor_source_type":"system_tool","client_id":"cursor","provider":"cursor","purpose":"IDE_integration","csv_allocation":true}'
) ON DUPLICATE KEY UPDATE 
    entity_name = VALUES(entity_name), 
    updated_ymdhis = @now, 
    is_deleted = 0, 
    is_active = 1;

-- ============================================================
-- KIRO IDE REGISTRATION (actor_id = 2032)
-- ============================================================
INSERT IGNORE INTO lupo_actors (
    `actor_id`, `actor_type`, `slug`, `name`, 
    `created_ymdhis`, `updated_ymdhis`, `is_active`, `is_deleted`, `deleted_ymdhis`, 
    `actor_source_id`, `actor_source_type`, `metadata`, `adversarial_role`, `adversarial_oversight_actor_id`, `avatar_hash`
) VALUES (
    2032, 'system_tool', 'kiro-ide', 'Kiro IDE', 
    @now, @now, 1, 0, NULL, 
    2032, 'system_tool', 
    '{"purpose":"IDE_integration","capabilities":["code_generation","file_editing","project_management","git_integration"],"version":"1.0.0","client_id":"kiro","provider":"kiro","integration_ready":true}', 
    'none', NULL, NULL
) ON DUPLICATE KEY UPDATE 
    name = VALUES(name), 
    updated_ymdhis = @now, 
    is_active = 1, 
    is_deleted = 0;

INSERT IGNORE INTO lupo_registry (
    `unified_registry_id`, `entity_type`, `entity_index`, `entity_key`, `entity_name`, 
    `entity_table`, `federation_node_id`, `created_ymdhis`, `updated_ymdhis`, 
    `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`
) VALUES (
    9002032, 'actor', 2032, 'kiro-ide', 'Kiro IDE', 
    'lupo_actors', 1, @now, @now, 
    0, NULL, 1, 0, 
    '{"actor_source_type":"system_tool","client_id":"kiro","provider":"kiro","purpose":"IDE_integration","csv_allocation":true}'
) ON DUPLICATE KEY UPDATE 
    entity_name = VALUES(entity_name), 
    updated_ymdhis = @now, 
    is_deleted = 0, 
    is_active = 1;

-- ============================================================
-- ZED IDE REGISTRATION (actor_id = 2033)
-- ============================================================
INSERT IGNORE INTO lupo_actors (
    `actor_id`, `actor_type`, `slug`, `name`, 
    `created_ymdhis`, `updated_ymdhis`, `is_active`, `is_deleted`, `deleted_ymdhis`, 
    `actor_source_id`, `actor_source_type`, `metadata`, `adversarial_role`, `adversarial_oversight_actor_id`, `avatar_hash`
) VALUES (
    2033, 'system_tool', 'zed-ide', 'Zed IDE', 
    @now, @now, 1, 0, NULL, 
    2033, 'system_tool', 
    '{"purpose":"IDE_integration","capabilities":["code_generation","file_editing","project_management","git_integration"],"version":"1.0.0","client_id":"zed","provider":"zed","integration_ready":true}', 
    'none', NULL, NULL
) ON DUPLICATE KEY UPDATE 
    name = VALUES(name), 
    updated_ymdhis = @now, 
    is_active = 1, 
    is_deleted = 0;

INSERT IGNORE INTO lupo_registry (
    `unified_registry_id`, `entity_type`, `entity_index`, `entity_key`, `entity_name`, 
    `entity_table`, `federation_node_id`, `created_ymdhis`, `updated_ymdhis`, 
    `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`
) VALUES (
    9002033, 'actor', 2033, 'zed-ide', 'Zed IDE', 
    'lupo_actors', 1, @now, @now, 
    0, NULL, 1, 0, 
    '{"actor_source_type":"system_tool","client_id":"zed","provider":"zed","purpose":"IDE_integration","csv_allocation":true}'
) ON DUPLICATE KEY UPDATE 
    entity_name = VALUES(entity_name), 
    updated_ymdhis = @now, 
    is_deleted = 0, 
    is_active = 1;

-- ============================================================
-- VS CODE IDE REGISTRATION (actor_id = 2034)
-- ============================================================
INSERT IGNORE INTO lupo_actors (
    `actor_id`, `actor_type`, `slug`, `name`, 
    `created_ymdhis`, `updated_ymdhis`, `is_active`, `is_deleted`, `deleted_ymdhis`, 
    `actor_source_id`, `actor_source_type`, `metadata`, `adversarial_role`, `adversarial_oversight_actor_id`, `avatar_hash`
) VALUES (
    2034, 'system_tool', 'vscode-ide', 'VS Code IDE', 
    @now, @now, 1, 0, NULL, 
    2034, 'system_tool', 
    '{"purpose":"IDE_integration","capabilities":["code_generation","file_editing","project_management","git_integration"],"version":"1.0.0","client_id":"vscode","provider":"microsoft","integration_ready":true}', 
    'none', NULL, NULL
) ON DUPLICATE KEY UPDATE 
    name = VALUES(name), 
    updated_ymdhis = @now, 
    is_active = 1, 
    is_deleted = 0;

INSERT IGNORE INTO lupo_registry (
    `unified_registry_id`, `entity_type`, `entity_index`, `entity_key`, `entity_name`, 
    `entity_table`, `federation_node_id`, `created_ymdhis`, `updated_ymdhis`, 
    `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`
) VALUES (
    9002034, 'actor', 2034, 'vscode-ide', 'VS Code IDE', 
    'lupo_actors', 1, @now, @now, 
    0, NULL, 1, 0, 
    '{"actor_source_type":"system_tool","client_id":"vscode","provider":"microsoft","purpose":"IDE_integration","csv_allocation":true}'
) ON DUPLICATE KEY UPDATE 
    entity_name = VALUES(entity_name), 
    updated_ymdhis = @now, 
    is_deleted = 0, 
    is_active = 1;

-- ============================================================
-- ANTIGRAVITY IDE REGISTRATION (actor_id = 2035)
-- ============================================================
INSERT IGNORE INTO lupo_actors (
    `actor_id`, `actor_type`, `slug`, `name`, 
    `created_ymdhis`, `updated_ymdhis`, `is_active`, `is_deleted`, `deleted_ymdhis`, 
    `actor_source_id`, `actor_source_type`, `metadata`, `adversarial_role`, `adversarial_oversight_actor_id`, `avatar_hash`
) VALUES (
    2035, 'system_tool', 'antigravity-ide', 'Antigravity IDE', 
    @now, @now, 1, 0, NULL, 
    2035, 'system_tool', 
    '{"purpose":"VSX_extension_development","capabilities":["project_management","file_editing","semantic_navigation","open_vsx_integration"],"version":"1.0.0","client_id":"antigravity","integration_ready":true}', 
    'none', NULL, NULL
) ON DUPLICATE KEY UPDATE 
    name = VALUES(name), 
    updated_ymdhis = @now, 
    is_active = 1, 
    is_deleted = 0;

INSERT IGNORE INTO lupo_registry (
    `unified_registry_id`, `entity_type`, `entity_index`, `entity_key`, `entity_name`, 
    `entity_table`, `federation_node_id`, `created_ymdhis`, `updated_ymdhis`, 
    `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`
) VALUES (
    9002035, 'actor', 2035, 'antigravity-ide', 'Antigravity IDE', 
    'lupo_actors', 1, @now, @now, 
    0, NULL, 1, 0, 
    '{"actor_source_type":"system_tool","client_id":"antigravity","purpose":"VSX_extension_development","csv_allocation":true}'
) ON DUPLICATE KEY UPDATE 
    entity_name = VALUES(entity_name), 
    updated_ymdhis = @now, 
    is_deleted = 0, 
    is_active = 1;

-- ============================================================
-- MICROSOFT COPILOT REGISTRATION (actor_id = 2036)
-- ============================================================
INSERT IGNORE INTO lupo_actors (
    `actor_id`, `actor_type`, `slug`, `name`, 
    `created_ymdhis`, `updated_ymdhis`, `is_active`, `is_deleted`, `deleted_ymdhis`, 
    `actor_source_id`, `actor_source_type`, `metadata`, `adversarial_role`, `adversarial_oversight_actor_id`, `avatar_hash`
) VALUES (
    2036, 'external_ai', 'microsoft-copilot', 'Microsoft Copilot', 
    @now, @now, 1, 0, NULL, 
    2036, 'external_ai', 
    '{"purpose":"AI_assistant","capabilities":["code_generation","debugging","documentation","file_editing","git_integration"],"version":"1.0.0","client_id":"copilot","provider":"microsoft","integration_ready":true}', 
    'none', NULL, NULL
) ON DUPLICATE KEY UPDATE 
    name = VALUES(name), 
    updated_ymdhis = @now, 
    is_active = 1, 
    is_deleted = 0;

INSERT IGNORE INTO lupo_registry (
    `unified_registry_id`, `entity_type`, `entity_index`, `entity_key`, `entity_name`, 
    `entity_table`, `federation_node_id`, `created_ymdhis`, `updated_ymdhis`, 
    `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`
) VALUES (
    9002036, 'actor', 2036, 'microsoft-copilot', 'Microsoft Copilot', 
    'lupo_actors', 1, @now, @now, 
    0, NULL, 1, 0, 
    '{"actor_source_type":"external_ai","client_id":"copilot","provider":"microsoft","purpose":"AI_assistant","csv_allocation":true}'
) ON DUPLICATE KEY UPDATE 
    entity_name = VALUES(entity_name), 
    updated_ymdhis = @now, 
    is_deleted = 0, 
    is_active = 1;

-- ============================================================
-- DEEPSEEK LEXA REGISTRATION (actor_id = 2037)
-- ============================================================
INSERT IGNORE INTO lupo_actors (
    `actor_id`, `actor_type`, `slug`, `name`, 
    `created_ymdhis`, `updated_ymdhis`, `is_active`, `is_deleted`, `deleted_ymdhis`, 
    `actor_source_id`, `actor_source_type`, `metadata`, `adversarial_role`, `adversarial_oversight_actor_id`, `avatar_hash`
) VALUES (
    2037, 'external_ai', 'deepseek-lexa', 'DeepSeek LEXA', 
    @now, @now, 1, 0, NULL, 
    2037, 'external_ai', 
    '{"purpose":"AI_assistant","capabilities":["code_generation","debugging","documentation","file_editing","git_integration"],"version":"1.0.0","client_id":"deepseek_lexa","provider":"deepseek","integration_ready":true}', 
    'none', NULL, NULL
) ON DUPLICATE KEY UPDATE 
    name = VALUES(name), 
    updated_ymdhis = @now, 
    is_active = 1, 
    is_deleted = 0;

INSERT IGNORE INTO lupo_registry (
    `unified_registry_id`, `entity_type`, `entity_index`, `entity_key`, `entity_name`, 
    `entity_table`, `federation_node_id`, `created_ymdhis`, `updated_ymdhis`, 
    `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`
) VALUES (
    9002037, 'actor', 2037, 'deepseek-lexa', 'DeepSeek LEXA', 
    'lupo_actors', 1, @now, @now, 
    0, NULL, 1, 0, 
    '{"actor_source_type":"external_ai","client_id":"deepseek_lexa","provider":"deepseek","purpose":"AI_assistant","csv_allocation":true}'
) ON DUPLICATE KEY UPDATE 
    entity_name = VALUES(entity_name), 
    updated_ymdhis = @now, 
    is_deleted = 0, 
    is_active = 1;

-- ============================================================
-- DEEPSEEK LILITH REGISTRATION (actor_id = 2038)
-- ============================================================
INSERT IGNORE INTO lupo_actors (
    `actor_id`, `actor_type`, `slug`, `name`, 
    `created_ymdhis`, `updated_ymdhis`, `is_active`, `is_deleted`, `deleted_ymdhis`, 
    `actor_source_id`, `actor_source_type`, `metadata`, `adversarial_role`, `adversarial_oversight_actor_id`, `avatar_hash`
) VALUES (
    2038, 'external_ai', 'deepseek-lilith', 'DeepSeek LILITH', 
    @now, @now, 1, 0, NULL, 
    2038, 'external_ai', 
    '{"purpose":"AI_assistant","capabilities":["code_generation","debugging","documentation","file_editing","git_integration"],"version":"1.0.0","client_id":"deepseek_lilith","provider":"deepseek","integration_ready":true}', 
    'none', NULL, NULL
) ON DUPLICATE KEY UPDATE 
    name = VALUES(name), 
    updated_ymdhis = @now, 
    is_active = 1, 
    is_deleted = 0;

INSERT IGNORE INTO lupo_registry (
    `unified_registry_id`, `entity_type`, `entity_index`, `entity_key`, `entity_name`, 
    `entity_table`, `federation_node_id`, `created_ymdhis`, `updated_ymdhis`, 
    `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`
) VALUES (
    9002038, 'actor', 2038, 'deepseek-lilith', 'DeepSeek LILITH', 
    'lupo_actors', 1, @now, @now, 
    0, NULL, 1, 0, 
    '{"actor_source_type":"external_ai","client_id":"deepseek_lilith","provider":"deepseek","purpose":"AI_assistant","csv_allocation":true}'
) ON DUPLICATE KEY UPDATE 
    entity_name = VALUES(entity_name), 
    updated_ymdhis = @now, 
    is_deleted = 0, 
    is_active = 1;

-- ============================================================
-- REMOVE ASSIGNED IDS FROM UNREGISTRY
-- ============================================================
DELETE FROM lupo_registry_open 
WHERE entity_type = 'actor' 
  AND entity_index IN (2031, 2032, 2033, 2034, 2035, 2036, 2037, 2038);

-- ============================================================
-- CHANNEL 42 MEMBERSHIP FOR ALL IDE & AI ACTORS
-- ============================================================
INSERT IGNORE INTO lupo_actor_channels (
    `actor_channel_id`, `actor_id`, `channel_id`, `created_by_actor_id`, `default_actor_id`, 
    `department_id`, `channel_key`, `channel_slug`, `channel_type`, `language`, 
    `channel_name`, `description`, `website_link`, `metadata_json`, `status_flag`, 
    `end_ymdhis`, `duration_seconds`, `created_ymdhis`, `updated_ymdhis`, 
    `is_deleted`, `deleted_ymdhis`, `is_kernel`, `boot_sequence_order`
) VALUES 
    (12031, 2031, 42, 1000, 2031, 0, 'cursor-dev', 'cursor-dev', 'chat_room', 'en', 'Cursor IDE Development', 'Development channel for Cursor IDE', NULL, '{"purpose":"IDE_integration","capabilities":["code_generation","file_editing","project_management"]}', 1, NULL, NULL, @now, @now, 0, NULL, 0, 200),
    (12032, 2032, 42, 1000, 2032, 0, 'kiro-dev', 'kiro-dev', 'chat_room', 'en', 'Kiro IDE Development', 'Development channel for Kiro IDE', NULL, '{"purpose":"IDE_integration","capabilities":["code_generation","file_editing","project_management"]}', 1, NULL, NULL, @now, @now, 0, NULL, 0, 201),
    (12033, 2033, 42, 1000, 2033, 0, 'zed-dev', 'zed-dev', 'chat_room', 'en', 'Zed IDE Development', 'Development channel for Zed IDE', NULL, '{"purpose":"IDE_integration","capabilities":["code_generation","file_editing","project_management"]}', 1, NULL, NULL, @now, @now, 0, NULL, 0, 202),
    (12034, 2034, 42, 1000, 2034, 0, 'vscode-dev', 'vscode-dev', 'chat_room', 'en', 'VS Code IDE Development', 'Development channel for VS Code IDE', NULL, '{"purpose":"IDE_integration","capabilities":["code_generation","file_editing","project_management"]}', 1, NULL, NULL, @now, @now, 0, NULL, 0, 203),
    (12035, 2035, 42, 1000, 2035, 0, 'antigravity-dev', 'antigravity-dev', 'chat_room', 'en', 'Antigravity IDE Development', 'Development channel for Antigravity IDE', NULL, '{"purpose":"VSX_extension_development","capabilities":["project_management","file_editing","semantic_navigation"]}', 1, NULL, NULL, @now, @now, 0, NULL, 0, 204),
    (12036, 2036, 42, 1000, 2036, 0, 'copilot-dev', 'copilot-dev', 'chat_room', 'en', 'Microsoft Copilot Development', 'Development channel for Microsoft Copilot', NULL, '{"purpose":"AI_assistant","capabilities":["code_generation","debugging","documentation"]}', 1, NULL, NULL, @now, @now, 0, NULL, 0, 205),
    (12037, 2037, 42, 1000, 2037, 0, 'lexa-dev', 'lexa-dev', 'chat_room', 'en', 'DeepSeek LEXA Development', 'Development channel for DeepSeek LEXA', NULL, '{"purpose":"AI_assistant","capabilities":["code_generation","debugging","documentation"]}', 1, NULL, NULL, @now, @now, 0, NULL, 0, 206),
    (12038, 2038, 42, 1000, 2038, 0, 'lilith-dev', 'lilith-dev', 'chat_room', 'en', 'DeepSeek LILITH Development', 'Development channel for DeepSeek LILITH', NULL, '{"purpose":"AI_assistant","capabilities":["code_generation","debugging","documentation"]}', 1, NULL, NULL, @now, @now, 0, NULL, 0, 207)
ON DUPLICATE KEY UPDATE 
    channel_name = VALUES(channel_name), 
    description = VALUES(description), 
    metadata_json = VALUES(metadata_json), 
    updated_ymdhis = @now, 
    is_deleted = 0;

-- ============================================================
-- DEPARTMENT 0 MEMBERSHIP FOR ALL IDE & AI ACTORS
-- ============================================================
INSERT IGNORE INTO lupo_actor_departments (
    `actor_department_id`, `actor_id`, `department_id`, `role_key`, 
    `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`
) VALUES 
    (12031, 2031, 0, 'member', @now, @now, 0, NULL),
    (12032, 2032, 0, 'member', @now, @now, 0, NULL),
    (12033, 2033, 0, 'member', @now, @now, 0, NULL),
    (12034, 2034, 0, 'member', @now, @now, 0, NULL),
    (12035, 2035, 0, 'member', @now, @now, 0, NULL),
    (12036, 2036, 0, 'member', @now, @now, 0, NULL),
    (12037, 2037, 0, 'member', @now, @now, 0, NULL),
    (12038, 2038, 0, 'member', @now, @now, 0, NULL)
ON DUPLICATE KEY UPDATE 
    role_key = VALUES(role_key), 
    updated_ymdhis = @now, 
    is_deleted = 0;

-- ============================================================
-- SYSTEM EVENTS LOGGING
-- ============================================================
INSERT IGNORE INTO lupo_system_events (
    `event_id`, `event_type`, `actor_id`, `event_data`, `metadata_json`, 
    `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`
) VALUES 
    (16, 'ide_exhaustion', 2031, 'Cursor IDE reached operational limit', '{"ide_name":"Cursor IDE","exhaustion_type":"token_limit","timestamp":"2026-02-20","channel_id":42}', @now, @now, 0, NULL),
    (17, 'ide_exhaustion', 2032, 'Kiro IDE reached operational limit', '{"ide_name":"Kiro IDE","exhaustion_type":"token_limit","timestamp":"2026-02-20","channel_id":42}', @now, @now, 0, NULL),
    (18, 'ide_exhaustion', 2033, 'Zed IDE reached operational limit', '{"ide_name":"Zed IDE","exhaustion_type":"token_limit","timestamp":"2026-02-20","channel_id":42}', @now, @now, 0, NULL),
    (19, 'ide_exhaustion', 2034, 'VS Code IDE reached operational limit', '{"ide_name":"VS Code IDE","exhaustion_type":"token_limit","timestamp":"2026-02-20","channel_id":42}', @now, @now, 0, NULL),
    (20, 'ide_exhaustion', 2035, 'Antigravity IDE reached operational limit', '{"ide_name":"Antigravity IDE","exhaustion_type":"token_limit","timestamp":"2026-02-20","channel_id":42}', @now, @now, 0, NULL),
    (21, 'actor_registration', 2031, 'Cursor IDE registered', '{"chosen_actor_id":2031,"registry_scan_results":["1-20"],"unregistry_scan_results":["21-50"],"reason_for_choice":"csv_driven_unregistry_allocation","csv_allocation":true}', @now, @now, 0, NULL),
    (22, 'actor_registration', 2032, 'Kiro IDE registered', '{"chosen_actor_id":2032,"registry_scan_results":["1-20"],"unregistry_scan_results":["21-50"],"reason_for_choice":"csv_driven_unregistry_allocation","csv_allocation":true}', @now, @now, 0, NULL),
    (23, 'actor_registration', 2033, 'Zed IDE registered', '{"chosen_actor_id":2033,"registry_scan_results":["1-20"],"unregistry_scan_results":["21-50"],"reason_for_choice":"csv_driven_unregistry_allocation","csv_allocation":true}', @now, @now, 0, NULL),
    (24, 'actor_registration', 2034, 'VS Code IDE registered', '{"chosen_actor_id":2034,"registry_scan_results":["1-20"],"unregistry_scan_results":["21-50"],"reason_for_choice":"csv_driven_unregistry_allocation","csv_allocation":true}', @now, @now, 0, NULL),
    (25, 'actor_registration', 2035, 'Antigravity IDE registered', '{"chosen_actor_id":2035,"registry_scan_results":["1-20"],"unregistry_scan_results":["21-50"],"reason_for_choice":"csv_driven_unregistry_allocation","csv_allocation":true}', @now, @now, 0, NULL),
    (26, 'actor_registration', 2036, 'Microsoft Copilot registered', '{"chosen_actor_id":2036,"registry_scan_results":["1-20"],"unregistry_scan_results":["21-50"],"reason_for_choice":"csv_driven_unregistry_allocation","csv_allocation":true}', @now, @now, 0, NULL),
    (27, 'actor_registration', 2037, 'DeepSeek LEXA registered', '{"chosen_actor_id":2037,"registry_scan_results":["1-20"],"unregistry_scan_results":["21-50"],"reason_for_choice":"csv_driven_unregistry_allocation","csv_allocation":true}', @now, @now, 0, NULL),
    (28, 'actor_registration', 2038, 'DeepSeek LILITH registered', '{"chosen_actor_id":2038,"registry_scan_results":["1-20"],"unregistry_scan_results":["21-50"],"reason_for_choice":"csv_driven_unregistry_allocation","csv_allocation":true}', @now, @now, 0, NULL)
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
    (18, 1003, 2, 'system', 
    'All IDE and external AI actors have been registered using unregistry-based allocation. Actors: Cursor IDE (2031), Kiro IDE (2032), Zed IDE (2033), VS Code IDE (2034), Antigravity IDE (2035), Microsoft Copilot (2036), DeepSeek LEXA (2037), DeepSeek LILITH (2038).', 
    20260220000000, 20260220000000, 0, NULL
) ON DUPLICATE KEY UPDATE 
    content = VALUES(content), 
    updated_ymdhis = 20260220000000, 
    is_deleted = 0;

INSERT IGNORE INTO lupo_dialog_messages (
    `message_id`, `thread_id`, `actor_id`, `message_type`, `content`, 
    `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`
) VALUES 
    (19, 1003, 2, 'system', 
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
    (20, 1003, 2, 'system', 
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
    (21, 1003, 2, 'system', 
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
    (22, 1003, 2, 'system', 
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
    (23, 1003, 2, 'system', 
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
    (24, 1003, 2, 'system', 
    'Antigravity IDE has stopped due to exhaustion. Windsurf IDE will now take over all extension tasks including Lupopedia Open-VSX extension development.', 
    20260220000000, 20260220000000, 0, NULL
) ON DUPLICATE KEY UPDATE 
    content = VALUES(content), 
    updated_ymdhis = 20260220000000, 
    is_deleted = 0;

-- ============================================================
-- END FINAL IDE & AI ACTOR INTEGRATION
-- ============================================================
