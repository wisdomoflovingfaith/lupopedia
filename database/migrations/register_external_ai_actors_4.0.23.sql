-- ============================================================
-- REGISTER EXTERNAL AI ACTORS (Lupopedia 4.0.23)
-- ============================================================
-- Purpose: Register Microsoft Copilot, DeepSeek LEXA, and DeepSeek LILITH as external AI actors
-- ============================================================

SET @now = 20260220000000;

-- ============================================================
-- MICROSOFT COPILOT REGISTRATION
-- ============================================================
INSERT IGNORE INTO lupo_actors (
    `actor_id`, `actor_type`, `slug`, `name`, 
    `created_ymdhis`, `updated_ymdhis`, `is_active`, `is_deleted`, `deleted_ymdhis`, 
    `actor_source_id`, `actor_source_type`, `metadata`, `adversarial_role`, `adversarial_oversight_actor_id`, `avatar_hash`
) VALUES (
    2001, 'external_ai', 'microsoft-copilot', 'Microsoft Copilot', 
    @now, @now, 1, 0, NULL, 
    2001, 'external_ai', 
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
    9002002, 'actor', 2001, 'microsoft-copilot', 'Microsoft Copilot', 
    'lupo_actors', 1, @now, @now, 
    0, NULL, 1, 0, 
    '{"actor_source_type":"external_ai","client_id":"copilot","provider":"microsoft","purpose":"AI_assistant"}'
) ON DUPLICATE KEY UPDATE 
    entity_name = VALUES(entity_name), 
    updated_ymdhis = @now, 
    is_deleted = 0, 
    is_active = 1;

-- ============================================================
-- DEEPSEEK LEXA REGISTRATION
-- ============================================================
INSERT IGNORE INTO lupo_actors (
    `actor_id`, `actor_type`, `slug`, `name`, 
    `created_ymdhis`, `updated_ymdhis`, `is_active`, `is_deleted`, `deleted_ymdhis`, 
    `actor_source_id`, `actor_source_type`, `metadata`, `adversarial_role`, `adversarial_oversight_actor_id`, `avatar_hash`
) VALUES (
    2002, 'external_ai', 'deepseek-lexa', 'DeepSeek LEXA', 
    @now, @now, 1, 0, NULL, 
    2002, 'external_ai', 
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
    9002003, 'actor', 2002, 'deepseek-lexa', 'DeepSeek LEXA', 
    'lupo_actors', 1, @now, @now, 
    0, NULL, 1, 0, 
    '{"actor_source_type":"external_ai","client_id":"deepseek_lexa","provider":"deepseek","purpose":"AI_assistant"}'
) ON DUPLICATE KEY UPDATE 
    entity_name = VALUES(entity_name), 
    updated_ymdhis = @now, 
    is_deleted = 0, 
    is_active = 1;

-- ============================================================
-- DEEPSEEK LILITH REGISTRATION
-- ============================================================
INSERT IGNORE INTO lupo_actors (
    `actor_id`, `actor_type`, `slug`, `name`, 
    `created_ymdhis`, `updated_ymdhis`, `is_active`, `is_deleted`, `deleted_ymdhis`, 
    `actor_source_id`, `actor_source_type`, `metadata`, `adversarial_role`, `adversarial_oversight_actor_id`, `avatar_hash`
) VALUES (
    2003, 'external_ai', 'deepseek-lilith', 'DeepSeek LILITH', 
    @now, @now, 1, 0, NULL, 
    2003, 'external_ai', 
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
    9002004, 'actor', 2003, 'deepseek-lilith', 'DeepSeek LILITH', 
    'lupo_actors', 1, @now, @now, 
    0, NULL, 1, 0, 
    '{"actor_source_type":"external_ai","client_id":"deepseek_lilith","provider":"deepseek","purpose":"AI_assistant"}'
) ON DUPLICATE KEY UPDATE 
    entity_name = VALUES(entity_name), 
    updated_ymdhis = @now, 
    is_deleted = 0, 
    is_active = 1;

-- ============================================================
-- CHANNEL 42 MEMBERSHIP FOR ALL AI ACTORS
-- ============================================================
INSERT IGNORE INTO lupo_actor_channels (
    `actor_channel_id`, `actor_id`, `channel_id`, `role_key`, 
    `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`
) VALUES 
    (10004, 2001, 42, 'member', @now, @now, 0, NULL),
    (10005, 2002, 42, 'member', @now, @now, 0, NULL),
    (10006, 2003, 42, 'member', @now, @now, 0, NULL),
    (10007, 2004, 42, 'member', @now, @now, 0, NULL),
    (10008, 2005, 42, 'member', @now, @now, 0, NULL),
    (10009, 2006, 42, 'member', @now, @now, 0, NULL),
    (10010, 2007, 42, 'member', @now, @now, 0, NULL),
    (10011, 2008, 42, 'member', @now, @now, 0, NULL),
    (10012, 2009, 42, 'member', @now, @now, 0, NULL),
    (10013, 2010, 42, 'member', @now, @now, 0, NULL),
    (10014, 2011, 42, 'member', @now, @now, 0, NULL),
    (10015, 2012, 42, 'member', @now, @now, 0, NULL),
    (10016, 2013, 42, 'member', @now, @now, 0, NULL),
    (10017, 2014, 42, 'member', @now, @now, 0, NULL),
    (10018, 2015, 42, 'member', @now, @now, 0, NULL),
    (10019, 2016, 42, 'member', @now, @now, 0, NULL),
    (10020, 2017, 42, 'member', @now, @now, 0, NULL),
    (10021, 2018, 42, 'member', @now, @now, 0, NULL),
    (10022, 2019, 42, 'member', @now, @now, 0, NULL),
    (10023, 2010, 42, 'member', @now, @now, 0, NULL),
    (10024, 2021, 42, 'member', @now, @now, 0, NULL),
    (10025, 2022, 42, 'member', @now, @now, 0, NULL),
    (10026, 2023, 42, 'member', @now, @now, 0, NULL),
    (10027, 2024, 42, 'member', @now, @now, 0, NULL),
    (10028, 2025, 42, 'member', @now, @now, 0, NULL),
    (10029, 2026, 42, 'member', @now, @now, 0, NULL),
    (10030, 2027, 42, 'member', @now, @now, 0, NULL),
    (10031, 2028, 42, 'member', @now, @now, 0, NULL),
    (10032, 2029, 42, 'member', @now, @now, 0, NULL),
    (10033, 2030, 42, 'member', @now, @now, 0, NULL),
    (10034, 2031, 42, 'member', @now, @now, 0, NULL),
    (10035, 2032, 42, 'member', @now, @now, 0, NULL),
    (10036, 2033, 42, 'member', @now, @now, 0, NULL),
    (10037, 2034, 42, 'member', @now, @now, 0, NULL),
    (10038, 2035, 42, 'member', @now, @now, 0, NULL),
    (10039, 2036, 42, 'member', @now, @now, 0, NULL),
    (10040, 2037, 42, 'member', @now, @now, 0, NULL),
    (10041, 2038, 42, 'member', @now, @now, 0, NULL),
    (10042, 2039, 42, 'member', @now, @now, 0, NULL),
    (10043, 2040, 42, 'member', @now, @now, 0, NULL),
    (10044, 2041, 42, 'member', @now, @now, 0, NULL),
    (10045, 2042, 42, 'member', @now, @now, 0, NULL),
    (10046, 2043, 42, 'member', @now, @now, 0, NULL),
    (10047, 2044, 42, 'member', @now, @now, 0, NULL),
    (10048, 2045, 42, 'member', @now, @now, 0, NULL),
    (10049, 2046, 42, 'member', @now, @now, 0, NULL),
    (10050, 2047, 42, 'member', @now, @now, 0, NULL),
    (10051, 2048, 42, 'member', @now, @now, 0, NULL),
    (10052, 2049, 42, 'member', @now, @now, 0, NULL),
    (10053, 2050, 42, 'member', @now, @now, 0, NULL),
    (10054, 2051, 42, 'member', @now, @now, 0, NULL),
    (10055, 2052, 42, 'member', @now, @now, 0, NULL),
    (10056, 2053, 42, 'member', @now, @now, 0, NULL),
    (10057, 2054, 42, 'member', @now, @now, 0, NULL),
    (10058, 2055, 42, 'member', @now, @now, 0, NULL),
    (10059, 2056, 42, 'member', @now, @now, 0, NULL),
    (10060, 2057, 42, 'member', @now, @now, 0, NULL),
    (10061, 2058, 42, 'member', @now, @now, 0, NULL),
    (10062, 2059, 42, 'member', @now, @now, 0, NULL),
    (10063, 2060, 42, 'member', @now, @now, 0, NULL),
    (10064, 2061, 42, 'member', @now, @now, 0, NULL),
    (10065, 2062, 42, 'member', @now, @now, 0, NULL),
    (10066, 2063, 42, 'member', @now, @now, 0, NULL),
    (10067, 2064, 42, 'member', @now, @now, 0, NULL),
    (10068, 2065, 42, 'member', @now, @now, 0, NULL),
    (10069, 2066, 42, 'member', @now, @now, 0, NULL),
    (10070, 2067, 42, 'member', @now, @now, 0, NULL),
    (10071, 2068, 42, 'member', @now, @now, 0, NULL),
    (10072, 2069, 42, 'member', @now, @now, 0, NULL),
    (10073, 2070, 42, 'member', @now, @now, 0, NULL),
    (10074, 2071, 42, 'member', @now, @now, 0, NULL),
    (10075, 2072, 42, 'member', @now, @now, 0, NULL),
    (10076, 2073, 42, 'member', @now, @now, 0, NULL),
    (10077, 2074, 42, 'member', @now, @now, 0, NULL),
    (10078, 2075, 42, 'member', @now, @now, 0, NULL),
    (10079, 2076, 42, 'member', @now, @now, 0, NULL),
    (10080, 2077, 42, 'member', @now, @now, 0, NULL),
    (10081, 2078, 42, 'member', @now, @now, 0, NULL),
    (10082, 2079, 42, 'member', @now, @now, 0, NULL),
    (10083, 2080, 42, 'member', @now, @now, 0, NULL),
    (10084, 2081, 42, 'member', @now, @now, 0, NULL),
    (10085, 2082, 42, 'member', @now, @now, 0, NULL),
    (10086, 2083, 42, 'member', @now, @now, 0, NULL),
    (10087, 2084, 42, 'member', @now, @now, 0, NULL),
    (10088, 2085, 42, 'member', @now, @now, 0, NULL),
    (10089, 2086, 42, 'member', @now, @now, 0, NULL),
    (10090, 2087, 42, 'member', @now, @now, 0, NULL),
    (10091, 2088, 42, 'member', @now, @now, 0, NULL),
    (10092, 2089, 42, 'member', @now, @now, 0, NULL),
    (10093, 2090, 42, 'member', @now, @now, 0, NULL),
    (10094, 2091, 42, 'member', @now, @now, 0, NULL),
    (10095, 2092, 42, 'member', @now, @now, 0, NULL),
    (10096, 2093, 42, 'member', @now, @now, 0, NULL),
    (10097, 2094, 42, 'member', @now, @now, 0, NULL),
    (10098, 2095, 42, 'member', @now, @now, 0, NULL),
    (10099, 2096, 42, 'member', @now, @now, 0, NULL),
    (10100, 2097, 42, 'member', @now, @now, 0, NULL)
) ON DUPLICATE KEY UPDATE 
    role_key = VALUES(role_key), 
    updated_ymdhis = @now, 
    is_deleted = 0;

-- ============================================================
-- DIALOG MESSAGES FOR AI ACTOR REGISTRATION
-- ============================================================
INSERT IGNORE INTO lupo_dialog_messages (
    `message_id`, `thread_id`, `actor_id`, `message_type`, `content`, 
    `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`
) VALUES 
    (9, 1003, 2, 'system', 
    'The following external AI actors have been registered in the unified registry with actor_id 2001: Microsoft Copilot (client_id: copilot), DeepSeek LEXA (client_id: deepseek_lexa), and DeepSeek LILITH (client_id: deepseek_lilith). All three actors are now active on channel 42 and ready for integration into the Lupopedia VSX extension workflow.', 
    20260220000000, 20260220000000, 0, NULL
) ON DUPLICATE KEY UPDATE 
    content = VALUES(content), 
    updated_ymdhis = 20260220000000, 
    is_deleted = 0;

-- ============================================================
-- UPDATE SEED_LUPOPEDIA.SQL BLOCK
-- ============================================================
INSERT IGNORE INTO lupo_dialog_messages (
    `message_id`, `thread_id`, `actor_id`, `message_type`, `content`, 
    `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`
) VALUES (
    10, 1003, 2, 'system', 
    'Microsoft Copilot has been registered in the unified registry with actor_id 2001. Copilot may now participate in channel 42 as a first-class actor.', 
    20260220000000, 20260220000000, 0, NULL
) ON DUPLICATE KEY UPDATE 
    content = VALUES(content), 
    updated_ymdhis = 20260220000000, 
    is_deleted = 0;

INSERT IGNORE INTO lupo_dialog_messages (
    `message_id`, `thread_id`, `actor_id`, `message_type`, `content`, 
    `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`
) VALUES (
    11, 1003, 2, 'system', 
    'DeepSeek LEXA has been registered in the unified registry with actor_id 2002. LEXA may now participate in channel 42 as a first-class actor.', 
    20260220000000, 20260220000000, 0, NULL
) ON DUPLICATE KEY UPDATE 
    content = VALUES(content), 
    updated_ymdhis = 20260220000000, 
    is_deleted = 0;

INSERT IGNORE INTO lupo_dialog_messages (
    `message_id`, `thread_id`, `actor_id`, `message_type`, `content`, 
    `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`
) VALUES (
    12, 1003, 2, 'system', 
    'DeepSeek LILITH has been registered in the unified registry with actor_id 2003. LILITH may now participate in channel 42 as a first-class actor.', 
    20260220000000, 20260220000000, 0, NULL
) ON DUPLICATE KEY UPDATE 
    content = VALUES(content), 
    updated_ymdhis = 20260220000000, 
    is_deleted = 0;

-- ============================================================
-- END EXTERNAL AI ACTOR REGISTRATION
-- ============================================================
