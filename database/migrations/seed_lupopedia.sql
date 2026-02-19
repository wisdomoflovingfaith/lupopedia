-- FILE: database/migrations/seed_lupopedia.sql
-- Generated from docs/toons/*.toon.json and live DB. DO NOT EDIT BY HAND.
-- Purpose: Seed data for fresh Lupopedia 3.0.0 install. Run after install_new_lupopedia.sql.
-- No Crafty Syntax data. No schema. INSERT only.

-- =============================================================================
-- SEED LUPOPEDIA — CANONICAL BIRTH-STATE
-- =============================================================================
-- Timestamp doctrine: BIGINT(14) UTC YYYYMMDDHHiiss. No datetime, no CURRENT_TIMESTAMP, no epoch.
-- All seeded rows in lupo_contents, lupo_collection_tab_map, lupo_collection_tabs,
-- lupo_truth_questions, lupo_truth_answers use @now for created_ymdhis and updated_ymdhis.
SET @now = 20260217230000;
SET @node_id = 1;
-- Version for module seed: must match docs/doctrine/VERSIONING_DOCTRINE.md (canonical current version).
SET @lupo_version = '4.0.20';
SET @lupo_version_code = 40020;

-- -----------------------------------------------------------------------------
-- System department (department_id = 0) — reserved, not user-selectable
-- -----------------------------------------------------------------------------
INSERT INTO lupo_departments (department_id, federation_node_id, name, description, department_type, default_actor_id, settings_json, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis)
VALUES (0, 1, 'System', 'System Department (Reserved)', 'system', 0, NULL, @now, @now, 0, NULL);

-- -----------------------------------------------------------------------------
-- Default department (department_id = 1) — for channels when no Crafty departments exist
-- -----------------------------------------------------------------------------
INSERT INTO lupo_departments (department_id, federation_node_id, name, description, department_type, default_actor_id, settings_json, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis)
VALUES (1, 1, 'General', 'Default department for channels', 'general', 0, NULL, @now, @now, 0, NULL);

-- -----------------------------------------------------------------------------
-- lupo_modules (version, version_code, minimum_core_version from @lupo_version / @lupo_version_code)
-- -----------------------------------------------------------------------------
INSERT INTO lupo_modules (module_id, module_key, module_name, namespace, version, version_code, minimum_core_version, user_path, admin_path, api_path, route_params, description, author, website, icon, dependencies, conflicts, config_json, is_system, is_active, federation_node_id, settings, installed_ymdhis, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis) VALUES
(1, 'crafty_syntax', 'Crafty Syntax Live Chat', 'CraftySyntax', @lupo_version, @lupo_version_code, @lupo_version, '/crafty/', '/admin/crafty/', '/api/crafty/', NULL, 'Legacy live chat system with operator/visitor model, transcripts, departments, and offline messages.', 'Eric Gerdes', 'https://lupopedia.com', 'comments', NULL, NULL, '{"theme": "vanilla", "maxexe": 180, "floatxy": "200|160", "matchip": "N", "version": "3.7.5", "webpath": "http://lupopedia.com/lh/", "chatmode": "xmlhttp-flush-refresh", "tracking": "Y", "ignoreips": "", "maxmonths": 12, "maxvisits": 75, "membernum": 0, "s_webpath": "https://lupopedia.com/lh/", "showgames": "Y", "smtp_host": "", "use_flush": "YES", "chatcolors": "fefdcd,cbcefe,caedbe,cccbba,aecddc,EBBEAA,faacaa,fbddef,cfaaef,aedcbd,bbffff,fedabf;040662,240462,520500,404062,100321,662640,242642,151035,051411,442662,442022,200220;426446,224646,466286,828468,866482,484668,504342,224882,486882,824864,668266,444468", "maxoldhits": 1, "maxrecords": 75000, "showsearch": "Y", "site_title": "Live Help!", "usecookies": "Y", "colorscheme": "white", "directoryid": "", "ignoreagent": "", "maxreferers": 50, "maxrequests": 99999, "owner_email": "lupopedia@gmail.com", "refreshrate": 1, "reftracking": "Y", "resetbutton": "Y", "show_typing": "Y", "topkeywords": 50, "adminsession": "Y", "gethostnames": "N", "keywordtrack": "N", "showoperator": "Y", "smtp_portnum": 25, "usertracking": "Y", "admin_refresh": "auto", "rememberusers": "Y", "scratch_space": " Welcome to  Live Help \\r\\n\\r\\n\\r\\n        ", "showdirectory": "Y", "smtp_password": "1q2w3e4r!", "smtp_username": "captain", "speaklanguage": "English", "topbackground": "header_images/customersupports.png", "everythingelse": "NYYY", "sessiontimeout": 60, "topframeheight": 85, "operatorstimeout": 4, "operatorssessionout": 45}', 1, 1, 1, '{}', @now, @now, NULL, 0, NULL),
(2, 'crm', 'CRM / Leads', 'CRM', @lupo_version, @lupo_version_code, @lupo_version, '/crm/', '/admin/crm/', '/api/crm/', NULL, 'Contact management, leads, follow-ups, notes, and operator assignments.', 'Eric Gerdes', 'https://lupopedia.com', 'address-book', NULL, NULL, '{}', 1, 1, 1, '{}', @now, @now, NULL, 0, NULL),
(3, 'truth', 'truth Q&A / Knowledge System', 'truth', @lupo_version, @lupo_version_code, @lupo_version, '/truth/', '/admin/truth/', '/api/truth/', NULL, 'Knowledge system for atoms, questions, answers, revisions, and semantic content.', 'Eric Gerdes', 'https://lupopedia.com', 'book', NULL, NULL, '{}', 1, 1, 1, '{}', @now, @now, NULL, 0, NULL),
(4, 'routing', 'Routing & Multi-Agent System', 'Routing', @lupo_version, @lupo_version_code, @lupo_version, '/routing/', '/admin/routing/', '/api/routing/', NULL, 'HERMES, CADUCEUS, emotional routing, agent classification, channel intent, and routing modes.', 'Eric Gerdes', 'https://lupopedia.com', 'share-nodes', NULL, NULL, '{}', 1, 1, 1, '{}', @now, @now, NULL, 0, NULL),
(5, 'analytics', 'Analytics & Reporting', 'Analytics', @lupo_version, @lupo_version_code, @lupo_version, '/analytics/', '/admin/analytics/', '/api/analytics/', NULL, 'Dashboards, operator stats, visitor stats, channel metrics, mood analytics, and routing analytics.', 'Eric Gerdes', 'https://lupopedia.com', 'chart-line', NULL, NULL, '{}', 1, 1, 1, '{}', @now, @now, NULL, 0, NULL),
(6, 'settings', 'System Settings & Configuration', 'Settings', @lupo_version, @lupo_version_code, @lupo_version, '/settings/', '/admin/settings/', '/api/settings/', NULL, 'Admin panel for node configuration, module settings, agent settings, bans, UI, email, and federation.', 'Eric Gerdes', 'https://lupopedia.com', 'sliders', NULL, NULL, '{}', 1, 1, 1, '{}', @now, @now, NULL, 0, NULL),
(7, 'federation', 'Federation / Node-to-Node Communication', 'Federation', @lupo_version, @lupo_version_code, @lupo_version, '/federation/', '/admin/federation/', '/api/federation/', NULL, 'Federated communication between Lupopedia nodes: discovery, authentication, content sharing, and routing.', 'Eric Gerdes', 'https://lupopedia.com', 'network-wired', NULL, NULL, '{}', 1, 1, 1, '{}', @now, @now, NULL, 0, NULL),
(8, 'content', 'Content Management System', 'Content', @lupo_version, @lupo_version_code, @lupo_version, '/content/', '/admin/content/', '/api/content/', NULL, 'Core content management system for Lupopedia: content entries, media, categories, hashtags, engagement, revisions, and semantic linking.', 'Eric Gerdes', 'https://lupopedia.com', 'file-lines', NULL, NULL, '{}', 1, 1, 1, '{}', @now, @now, NULL, 0, NULL),
(9, 'admin', 'Admin', 'Admin', @lupo_version, @lupo_version_code, @lupo_version, '/admin.php', '/admin.php', NULL, NULL, 'Global admin interface (admin.php). Owner on this module grants global admin access (used by AuthRoleResolver and wizard for Crafty isadmin migration).', 'Eric Gerdes', 'https://lupopedia.com', 'cog', NULL, NULL, '{}', 1, 1, 1, '{}', @now, @now, NULL, 0, NULL);

-- -----------------------------------------------------------------------------
-- lupo_permissions: main admin (auth_user_id 10000) owner on admin module (module_id 9)
-- -----------------------------------------------------------------------------
INSERT INTO lupo_permissions (permission_id, target_type, target_id, user_id, department_id, permission, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis)
VALUES (10000, 'module', 9, 10000, NULL, 'owner', @now, @now, 0, NULL)
ON DUPLICATE KEY UPDATE permission = 'owner', updated_ymdhis = @now, is_deleted = 0, deleted_ymdhis = NULL;

-- -----------------------------------------------------------------------------
-- Unified registry (lupo_unified_registry)
-- -----------------------------------------------------------------------------
INSERT INTO lupo_unified_registry (`unified_registry_id`, `entity_type`, `entity_index`, `entity_key`, `entity_name`, `entity_table`, `federation_node_id`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`) VALUES (1, 'channel', 0, 'system/kernel', 'System Kernel Channel', 'lupo_channels', 1, 20260106084500, 20260122160000, 0, NULL, 1, 1, '{"language": "en", "description": "Reserved channel for bootstrapping, migrations, and OS-level events.", "status_flag": 1, "channel_type": "chat_room", "channel_number": 0}');

INSERT INTO lupo_unified_registry (`unified_registry_id`, `entity_type`, `entity_index`, `entity_key`, `entity_name`, `entity_table`, `federation_node_id`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`) VALUES (2, 'channel', 1, 'system/lobby', 'Lobby Channel', 'lupo_channels', 1, 20260106082200, 20260106082200, 0, NULL, 1, 0, '{"language": "en", "description": "Universal entry point for all new actors. Temporary holding area before routing.", "status_flag": 1, "channel_type": "chat_room", "channel_number": null}');

INSERT INTO lupo_unified_registry (`unified_registry_id`, `entity_type`, `entity_index`, `entity_key`, `entity_name`, `entity_table`, `federation_node_id`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`) VALUES (3, 'channel', 1001, 'test_awareness_channel', 'Agent Awareness Test Channel', 'lupo_channels', 1, 20260117000000, 20260117000000, 0, NULL, 1, 0, '{"language": "en", "description": "Test channel for AAL validation", "status_flag": 1, "channel_type": "chat_room", "channel_number": null}');

INSERT INTO lupo_unified_registry (`unified_registry_id`, `entity_type`, `entity_index`, `entity_key`, `entity_name`, `entity_table`, `federation_node_id`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`) VALUES (4, 'channel', 1002, 'dev-main-thread', 'dev-main-thread', 'lupo_channels', 1, 20260106082200, 20260106082200, 0, NULL, 1, 0, '{"language": "en", "description": "main channel for development in global sense  ", "status_flag": 1, "channel_type": "chat_room", "channel_number": null}');

INSERT INTO lupo_unified_registry (`unified_registry_id`, `entity_type`, `entity_index`, `entity_key`, `entity_name`, `entity_table`, `federation_node_id`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`) VALUES (5, 'channel', 1003, 'GOV‑PROGRAMMERS‑001', 'GOV‑PROGRAMMERS‑001', 'lupo_channels', 1, 20260106082200, 20260106082200, 0, NULL, 1, 0, '{"language": "en", "description": "GOV‑PROGRAMMERS‑001", "status_flag": 1, "channel_type": "chat_room", "channel_number": null}');

INSERT INTO lupo_unified_registry (`unified_registry_id`, `entity_type`, `entity_index`, `entity_key`, `entity_name`, `entity_table`, `federation_node_id`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`) VALUES (6, 'channel', 1004, 'system-errors', 'System Errors', 'lupo_channels', 1, 20260121004000, 20260121004000, 0, NULL, 1, 0, '{"language": "en", "description": "Centralized error reporting and agent fault logs.", "status_flag": 1, "channel_type": "chat_room", "channel_number": null}');

INSERT INTO lupo_unified_registry (`unified_registry_id`, `entity_type`, `entity_index`, `entity_key`, `entity_name`, `entity_table`, `federation_node_id`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`) VALUES (7, 'channel', 1005, 'programmers', 'Programmers of Lupopedia', 'lupo_channels', 1, 20260121004000, 20260121004000, 0, NULL, 1, 0, '{"language": "en", "description": "Engineering doctrine, certification, and architecture.", "status_flag": 1, "channel_type": "chat_room", "channel_number": null}');

INSERT INTO lupo_unified_registry (`unified_registry_id`, `entity_type`, `entity_index`, `entity_key`, `entity_name`, `entity_table`, `federation_node_id`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`) VALUES (8, 'channel', 1006, 'doctrine', 'Doctrine', 'lupo_channels', 1, 20260121004000, 20260121004000, 0, NULL, 1, 0, '{"language": "en", "description": "System governance, rules, invariants, and prohibitions.", "status_flag": 1, "channel_type": "chat_room", "channel_number": null}');

INSERT INTO lupo_unified_registry (`unified_registry_id`, `entity_type`, `entity_index`, `entity_key`, `entity_name`, `entity_table`, `federation_node_id`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`) VALUES (9, 'channel', 1007, 'schema', 'Schema Oracle', 'lupo_channels', 1, 20260121004000, 20260121004000, 0, NULL, 1, 0, '{"language": "en", "description": "TOON-based database structure and table definitions.", "status_flag": 1, "channel_type": "chat_room", "channel_number": null}');

INSERT INTO lupo_unified_registry (`unified_registry_id`, `entity_type`, `entity_index`, `entity_key`, `entity_name`, `entity_table`, `federation_node_id`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`) VALUES (10, 'channel', 1008, 'routing-hermes', 'HERMES Routing', 'lupo_channels', 1, 20260121004000, 20260121004000, 0, NULL, 1, 0, '{"language": "en", "description": "Routing logic, emotional geometry, and agent classification.", "status_flag": 1, "channel_type": "chat_room", "channel_number": null}');

INSERT INTO lupo_unified_registry (`unified_registry_id`, `entity_type`, `entity_index`, `entity_key`, `entity_name`, `entity_table`, `federation_node_id`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`) VALUES (11, 'channel', 1009, 'mood-caduceus', 'CADUCEUS Mood Engine', 'lupo_channels', 1, 20260121004000, 20260121004000, 0, NULL, 1, 0, '{"language": "en", "description": "Emotional geometry, polarity agents, and mood balancing.", "status_flag": 1, "channel_type": "chat_room", "channel_number": null}');

INSERT INTO lupo_unified_registry (`unified_registry_id`, `entity_type`, `entity_index`, `entity_key`, `entity_name`, `entity_table`, `federation_node_id`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`) VALUES (12, 'channel', 1010, 'users-humans', 'Human Users', 'lupo_channels', 1, 20260121004000, 20260121004000, 0, NULL, 1, 0, '{"language": "en", "description": "Human profiles, preferences, onboarding, and identity.", "status_flag": 1, "channel_type": "chat_room", "channel_number": null}');

INSERT INTO lupo_unified_registry (`unified_registry_id`, `entity_type`, `entity_index`, `entity_key`, `entity_name`, `entity_table`, `federation_node_id`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`) VALUES (13, 'channel', 1011, 'agents-registry', 'Agent Registry', 'lupo_channels', 1, 20260121004000, 20260121004000, 0, NULL, 1, 0, '{"language": "en", "description": "Agent definitions, personas, capabilities, and restrictions.", "status_flag": 1, "channel_type": "chat_room", "channel_number": null}');

INSERT INTO lupo_unified_registry (`unified_registry_id`, `entity_type`, `entity_index`, `entity_key`, `entity_name`, `entity_table`, `federation_node_id`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`) VALUES (14, 'channel', 1012, 'channels-meta', 'Channel Metadata', 'lupo_channels', 1, 20260121004000, 20260121004000, 0, NULL, 1, 0, '{"language": "en", "description": "Metadata about channels, edges, and semantic relationships.", "status_flag": 1, "channel_type": "chat_room", "channel_number": null}');

INSERT INTO lupo_unified_registry (`unified_registry_id`, `entity_type`, `entity_index`, `entity_key`, `entity_name`, `entity_table`, `federation_node_id`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`) VALUES (15, 'channel', 1013, 'tasks-workflows', 'Tasks & Workflows', 'lupo_channels', 1, 20260121004000, 20260121004000, 0, NULL, 1, 0, '{"language": "en", "description": "Multi-step tasks, pipelines, and job orchestration.", "status_flag": 1, "channel_type": "chat_room", "channel_number": null}');

INSERT INTO lupo_unified_registry (`unified_registry_id`, `entity_type`, `entity_index`, `entity_key`, `entity_name`, `entity_table`, `federation_node_id`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`) VALUES (16, 'channel', 1014, 'logs-history', 'System History', 'lupo_channels', 1, 20260121004000, 20260121004000, 0, NULL, 1, 0, '{"language": "en", "description": "System-wide historical events and OS journal.", "status_flag": 1, "channel_type": "chat_room", "channel_number": null}');

INSERT INTO lupo_unified_registry (`unified_registry_id`, `entity_type`, `entity_index`, `entity_key`, `entity_name`, `entity_table`, `federation_node_id`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`) VALUES (17, 'channel', 1015, 'emotional_frameworks', 'Emotional Frameworks Channel', 'lupo_channels', 1, 20260122160000, 20260122160000, 0, NULL, 1, 0, '{"language": "en", "description": "Ubuntu, Hózhó, Vedanā, Paradox Graph, Vector Legacy, LILITH topology", "status_flag": 1, "channel_type": "chat_room", "channel_number": 2}');

INSERT INTO lupo_unified_registry (`unified_registry_id`, `entity_type`, `entity_index`, `entity_key`, `entity_name`, `entity_table`, `federation_node_id`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`) VALUES (18, 'channel', 1016, 'routing_navigation', 'Routing & Navigation Channel', 'lupo_channels', 1, 20260122160000, 20260122160000, 0, NULL, 1, 0, '{"language": "en", "description": "Hermes routing, channel manifest spec, graph traversal rules", "status_flag": 1, "channel_type": "chat_room", "channel_number": 3}');

INSERT INTO lupo_unified_registry (`unified_registry_id`, `entity_type`, `entity_index`, `entity_key`, `entity_name`, `entity_table`, `federation_node_id`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`) VALUES (19, 'channel', 1017, 'database_schema', 'Database & Schema Channel', 'lupo_channels', 1, 20260122160000, 20260122160000, 0, NULL, 1, 0, '{"language": "en", "description": "Table limits, migration rules, ORM sandbox rules, DB architecture", "status_flag": 1, "channel_type": "chat_room", "channel_number": 4}');

INSERT INTO lupo_unified_registry (`unified_registry_id`, `entity_type`, `entity_index`, `entity_key`, `entity_name`, `entity_table`, `federation_node_id`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`) VALUES (20, 'channel', 1018, 'agents_actors', 'Agents & Actors Channel', 'lupo_channels', 1, 20260122160000, 20260122160000, 0, NULL, 1, 0, '{"language": "en", "description": "Actor onboarding, identity rules, behavior constraints", "status_flag": 1, "channel_type": "chat_room", "channel_number": 5}');

INSERT INTO lupo_unified_registry (`unified_registry_id`, `entity_type`, `entity_index`, `entity_key`, `entity_name`, `entity_table`, `federation_node_id`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`) VALUES (21, 'channel', 1019, 'humor_sandbox', 'Humor/Sandbox Channel', 'lupo_channels', 1, 20260122160000, 20260122160000, 0, NULL, 1, 0, '{"language": "en", "description": "DIALOG/HUMOR agents, safe humor rules, sarcasm protocol", "status_flag": 1, "channel_type": "chat_room", "channel_number": 6}');

INSERT INTO lupo_unified_registry (`unified_registry_id`, `entity_type`, `entity_index`, `entity_key`, `entity_name`, `entity_table`, `federation_node_id`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`) VALUES (22, 'channel', 1020, 'logs_history', 'Logs/History Channel', 'lupo_channels', 1, 20260122160000, 20260122160000, 0, NULL, 1, 0, '{"language": "en", "description": "Changelog, migration logs, doctrine evolution", "status_flag": 1, "channel_type": "chat_room", "channel_number": 7}');

INSERT INTO lupo_unified_registry (`unified_registry_id`, `entity_type`, `entity_index`, `entity_key`, `entity_name`, `entity_table`, `federation_node_id`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`) VALUES (23, 'channel', 1021, 'tasks_workflows', 'Tasks/Workflows Channel', 'lupo_channels', 1, 20260122160000, 20260122160000, 0, NULL, 1, 0, '{"language": "en", "description": "Operational procedures, workflows, automation rules", "status_flag": 1, "channel_type": "chat_room", "channel_number": 8}');

INSERT INTO lupo_unified_registry (`unified_registry_id`, `entity_type`, `entity_index`, `entity_key`, `entity_name`, `entity_table`, `federation_node_id`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`) VALUES (24, 'channel', 1022, 'meta', 'Meta Channel', 'lupo_channels', 1, 20260122160000, 20260122160000, 0, NULL, 1, 0, '{"language": "en", "description": "Shadow integration score, environmental context, relational fields", "status_flag": 1, "channel_type": "chat_room", "channel_number": 9}');

INSERT INTO lupo_unified_registry (`unified_registry_id`, `entity_type`, `entity_index`, `entity_key`, `entity_name`, `entity_table`, `federation_node_id`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`) VALUES (25, 'channel', 1023, 'lupopedia', 'Lupopedia', 'lupo_channels', 1, 20260125192700, 20260125192700, 0, NULL, 1, 0, '{"language": "en", "description": "Primary Lupopedia knowledge and system channel.", "status_flag": 1, "channel_type": "chat_room", "channel_number": 51}');

INSERT INTO lupo_unified_registry (`unified_registry_id`, `entity_type`, `entity_index`, `entity_key`, `entity_name`, `entity_table`, `federation_node_id`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`) VALUES (26, 'channel', 1025, 'kernel-logs', 'Kernel Logs', 'lupo_channels', 1, 20260125193100, 20260125193100, 0, NULL, 1, 1, '{"language": "en", "description": "System kernel logs, events, and internal diagnostics.", "status_flag": 1, "channel_type": "chat_room", "channel_number": 5102}');

INSERT INTO lupo_unified_registry (`unified_registry_id`, `entity_type`, `entity_index`, `entity_key`, `entity_name`, `entity_table`, `federation_node_id`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`) VALUES (27, 'channel', 1026, 'migration-orchestrator', 'Migration Orchestrator', 'lupo_channels', 1, 20260125193100, 20260125193100, 0, NULL, 1, 1, '{"language": "en", "description": "State machine logs, transitions, and migration lifecycle.", "status_flag": 1, "channel_type": "chat_room", "channel_number": 5103}');

INSERT INTO lupo_unified_registry (`unified_registry_id`, `entity_type`, `entity_index`, `entity_key`, `entity_name`, `entity_table`, `federation_node_id`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`) VALUES (28, 'channel', 1027, 'agents', 'Agents / Hermes', 'lupo_channels', 1, 20260125193100, 20260125193100, 0, NULL, 1, 0, '{"language": "en", "description": "Agent fleet coordination, Hermes routing, and multi-agent activity.", "status_flag": 1, "channel_type": "chat_room", "channel_number": 5104}');

INSERT INTO lupo_unified_registry (`unified_registry_id`, `entity_type`, `entity_index`, `entity_key`, `entity_name`, `entity_table`, `federation_node_id`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`) VALUES (29, 'channel', 1028, 'emotional-metadata', 'Emotional Metadata', 'lupo_channels', 1, 20260125193100, 20260125193100, 0, NULL, 1, 0, '{"language": "en", "description": "Emotional state tracking, affective signals, and metadata doctrine.", "status_flag": 1, "channel_type": "chat_room", "channel_number": 5105}');

INSERT INTO lupo_unified_registry (`unified_registry_id`, `entity_type`, `entity_index`, `entity_key`, `entity_name`, `entity_table`, `federation_node_id`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`) VALUES (30, 'channel', 1029, 'system-events', 'System Events', 'lupo_channels', 1, 20260125193200, 20260125193200, 0, NULL, 1, 1, '{"language": "en", "description": "Global system events, signals, and lifecycle notifications.", "status_flag": 1, "channel_type": "chat_room", "channel_number": 5106}');

INSERT INTO lupo_unified_registry (`unified_registry_id`, `entity_type`, `entity_index`, `entity_key`, `entity_name`, `entity_table`, `federation_node_id`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`) VALUES (31, 'channel', 1030, 'hermes-routing', 'Hermes Routing Logs', 'lupo_channels', 1, 20260125193200, 20260125193200, 0, NULL, 1, 1, '{"language": "en", "description": "Routing decisions, message dispatch, and Hermes transport diagnostics.", "status_flag": 1, "channel_type": "chat_room", "channel_number": 5107}');

INSERT INTO lupo_unified_registry (`unified_registry_id`, `entity_type`, `entity_index`, `entity_key`, `entity_name`, `entity_table`, `federation_node_id`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`) VALUES (32, 'channel', 1031, 'semantic-index', 'Semantic Index', 'lupo_channels', 1, 20260125193200, 20260125193200, 0, NULL, 1, 0, '{"language": "en", "description": "Semantic graph, embeddings, and cross-channel indexing.", "status_flag": 1, "channel_type": "chat_room", "channel_number": 5108}');

INSERT INTO lupo_unified_registry (`unified_registry_id`, `entity_type`, `entity_index`, `entity_key`, `entity_name`, `entity_table`, `federation_node_id`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`) VALUES (33, 'channel', 1032, 'kernel-debug', 'Kernel Debug', 'lupo_channels', 1, 20260125193200, 20260125193200, 0, NULL, 1, 1, '{"language": "en", "description": "Low-level kernel debugging, traces, and diagnostic output.", "status_flag": 1, "channel_type": "chat_room", "channel_number": 5109}');

INSERT INTO lupo_unified_registry (`unified_registry_id`, `entity_type`, `entity_index`, `entity_key`, `entity_name`, `entity_table`, `federation_node_id`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`) VALUES (34, 'channel', 1033, 'pack-playground', 'Pack / Multi-Agent Playground', 'lupo_channels', 1, 20260125193200, 20260125193200, 0, NULL, 1, 0, '{"language": "en", "description": "Experimental multi-agent interactions, Pack behavior, and collaborative testing.", "status_flag": 1, "channel_type": "chat_room", "channel_number": 5110}');

INSERT INTO lupo_unified_registry (`unified_registry_id`, `entity_type`, `entity_index`, `entity_key`, `entity_name`, `entity_table`, `federation_node_id`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`) VALUES (35, 'channel', 1034, 'ui-creature', 'UI / Creature Interface', 'lupo_channels', 1, 20260125193400, 20260125193400, 0, NULL, 1, 0, '{"language": "en", "description": "User interface, creature UI elements, and interactive display systems.", "status_flag": 1, "channel_type": "chat_room", "channel_number": 5111}');

INSERT INTO lupo_unified_registry (`unified_registry_id`, `entity_type`, `entity_index`, `entity_key`, `entity_name`, `entity_table`, `federation_node_id`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`) VALUES (36, 'channel', 1035, 'doctrine-compiler', 'Doctrine Compiler', 'lupo_channels', 1, 20260125193400, 20260125193400, 0, NULL, 1, 1, '{"language": "en", "description": "Doctrine parsing, compilation, and system-wide doctrine enforcement.", "status_flag": 1, "channel_type": "chat_room", "channel_number": 5112}');

INSERT INTO lupo_unified_registry (`unified_registry_id`, `entity_type`, `entity_index`, `entity_key`, `entity_name`, `entity_table`, `federation_node_id`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`) VALUES (37, 'channel', 1036, 'emotional-engine', 'Emotional Engine', 'lupo_channels', 1, 20260125193400, 20260125193400, 0, NULL, 1, 0, '{"language": "en", "description": "Affective computation, emotional geometry, and internal emotional modeling.", "status_flag": 1, "channel_type": "chat_room", "channel_number": 5113}');

INSERT INTO lupo_unified_registry (`unified_registry_id`, `entity_type`, `entity_index`, `entity_key`, `entity_name`, `entity_table`, `federation_node_id`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`) VALUES (38, 'channel', 1037, 'semantic-router', 'Semantic Router', 'lupo_channels', 1, 20260125193400, 20260125193400, 0, NULL, 1, 1, '{"language": "en", "description": "Semantic routing, intent classification, and cross-channel dispatch.", "status_flag": 1, "channel_type": "chat_room", "channel_number": 5114}');

INSERT INTO lupo_unified_registry (`unified_registry_id`, `entity_type`, `entity_index`, `entity_key`, `entity_name`, `entity_table`, `federation_node_id`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`) VALUES (39, 'channel', 1038, 'kernel-panic', 'Kernel Panic Archive', 'lupo_channels', 1, 20260125193400, 20260125193400, 0, NULL, 1, 1, '{"language": "en", "description": "Critical kernel faults, panic dumps, and emergency diagnostic logs.", "status_flag": 1, "channel_type": "chat_room", "channel_number": 5115}');

INSERT INTO lupo_unified_registry (`unified_registry_id`, `entity_type`, `entity_index`, `entity_key`, `entity_name`, `entity_table`, `federation_node_id`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`) VALUES (40, 'channel', 1071, 'hermes-sandbox', 'Hermes Sandbox', 'lupo_channels', 1, 20260125193700, 20260125193700, 0, NULL, 1, 0, '{"language": "en", "description": "Testing ground for Hermes routing, dispatch, and transport experiments.", "status_flag": 1, "channel_type": "chat_room", "channel_number": 5116}');

INSERT INTO lupo_unified_registry (`unified_registry_id`, `entity_type`, `entity_index`, `entity_key`, `entity_name`, `entity_table`, `federation_node_id`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`) VALUES (41, 'channel', 1072, 'agent-training', 'Agent Training Grounds', 'lupo_channels', 1, 20260125193700, 20260125193700, 0, NULL, 1, 0, '{"language": "en", "description": "Simulation space for agent learning, behavior shaping, and controlled trials.", "status_flag": 1, "channel_type": "chat_room", "channel_number": 5117}');

INSERT INTO lupo_unified_registry (`unified_registry_id`, `entity_type`, `entity_index`, `entity_key`, `entity_name`, `entity_table`, `federation_node_id`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`) VALUES (42, 'channel', 1073, 'legacy-importer', 'Legacy Importer', 'lupo_channels', 1, 20260125193700, 20260125193700, 0, NULL, 1, 1, '{"language": "en", "description": "Legacy system ingestion, compatibility layers, and migration tooling.", "status_flag": 1, "channel_type": "chat_room", "channel_number": 5118}');

INSERT INTO lupo_unified_registry (`unified_registry_id`, `entity_type`, `entity_index`, `entity_key`, `entity_name`, `entity_table`, `federation_node_id`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`) VALUES (43, 'channel', 1074, 'emotional-debugger', 'Emotional Debugger', 'lupo_channels', 1, 20260125193700, 20260125193700, 0, NULL, 1, 0, '{"language": "en", "description": "Debugging emotional metadata, affective signals, and internal emotional states.", "status_flag": 1, "channel_type": "chat_room", "channel_number": 5119}');

INSERT INTO lupo_unified_registry (`unified_registry_id`, `entity_type`, `entity_index`, `entity_key`, `entity_name`, `entity_table`, `federation_node_id`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`) VALUES (44, 'channel', 1075, 'semantic-playground', 'Semantic Playground', 'lupo_channels', 1, 20260125193700, 20260125193700, 0, NULL, 1, 0, '{"language": "en", "description": "Experimental semantic graph operations, embeddings, and prototype routing.", "status_flag": 1, "channel_type": "chat_room", "channel_number": 5120}');

INSERT INTO lupo_unified_registry (`unified_registry_id`, `entity_type`, `entity_index`, `entity_key`, `entity_name`, `entity_table`, `federation_node_id`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`) VALUES (45, 'channel', 1076, 'kernel-metrics', 'Kernel Metrics', 'lupo_channels', 1, 20260125193700, 20260125193700, 0, NULL, 1, 1, '{"language": "en", "description": "Performance metrics, throughput, load, and kernel instrumentation.", "status_flag": 1, "channel_type": "chat_room", "channel_number": 5121}');

INSERT INTO lupo_unified_registry (`unified_registry_id`, `entity_type`, `entity_index`, `entity_key`, `entity_name`, `entity_table`, `federation_node_id`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`) VALUES (46, 'channel', 1077, 'agent-health', 'Agent Health Monitor', 'lupo_channels', 1, 20260125193700, 20260125193700, 0, NULL, 1, 0, '{"language": "en", "description": "Agent uptime, health checks, diagnostics, and lifecycle monitoring.", "status_flag": 1, "channel_type": "chat_room", "channel_number": 5122}');

INSERT INTO lupo_unified_registry (`unified_registry_id`, `entity_type`, `entity_index`, `entity_key`, `entity_name`, `entity_table`, `federation_node_id`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`) VALUES (47, 'channel', 1078, 'doctrine-validator', 'Doctrine Validator', 'lupo_channels', 1, 20260125193700, 20260125193700, 0, NULL, 1, 1, '{"language": "en", "description": "Validation of doctrine rules, constraints, and compliance checks.", "status_flag": 1, "channel_type": "chat_room", "channel_number": 5123}');

INSERT INTO lupo_unified_registry (`unified_registry_id`, `entity_type`, `entity_index`, `entity_key`, `entity_name`, `entity_table`, `federation_node_id`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`) VALUES (48, 'channel', 1079, 'emotional-archive', 'Emotional Archive', 'lupo_channels', 1, 20260125193700, 20260125193700, 0, NULL, 1, 0, '{"language": "en", "description": "Historical emotional metadata, long-term affective storage, and analysis.", "status_flag": 1, "channel_type": "chat_room", "channel_number": 5124}');

INSERT INTO lupo_unified_registry (`unified_registry_id`, `entity_type`, `entity_index`, `entity_key`, `entity_name`, `entity_table`, `federation_node_id`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`) VALUES (49, 'channel', 1080, 'semantic-diff', 'Semantic Diff Engine', 'lupo_channels', 1, 20260125193700, 20260125193700, 0, NULL, 1, 1, '{"language": "en", "description": "Semantic comparison, diffing, and cross-version semantic analysis.", "status_flag": 1, "channel_type": "chat_room", "channel_number": 5125}');

INSERT INTO lupo_unified_registry (`unified_registry_id`, `entity_type`, `entity_index`, `entity_key`, `entity_name`, `entity_table`, `federation_node_id`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`) VALUES (50, 'channel', 1081, 'kernel-watchdog', 'Kernel Watchdog', 'lupo_channels', 1, 20260125193700, 20260125193700, 0, NULL, 1, 1, '{"language": "en", "description": "Kernel safety monitoring, watchdog timers, and fault prevention.", "status_flag": 1, "channel_type": "chat_room", "channel_number": 5126}');

INSERT INTO lupo_unified_registry (`unified_registry_id`, `entity_type`, `entity_index`, `entity_key`, `entity_name`, `entity_table`, `federation_node_id`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`) VALUES (51, 'channel', 1082, 'persona-lab', 'Agent Persona Lab', 'lupo_channels', 1, 20260125193700, 20260125193700, 0, NULL, 1, 0, '{"language": "en", "description": "Persona shaping, behavioral tuning, and agent identity experiments.", "status_flag": 1, "channel_type": "chat_room", "channel_number": 5127}');

INSERT INTO lupo_unified_registry (`unified_registry_id`, `entity_type`, `entity_index`, `entity_key`, `entity_name`, `entity_table`, `federation_node_id`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`) VALUES (52, 'channel', 1083, 'semantic-stress', 'Semantic Stress Test', 'lupo_channels', 1, 20260125193700, 20260125193700, 0, NULL, 1, 1, '{"language": "en", "description": "High-load semantic routing, graph pressure tests, and scaling trials.", "status_flag": 1, "channel_type": "chat_room", "channel_number": 5128}');

INSERT INTO lupo_unified_registry (`unified_registry_id`, `entity_type`, `entity_index`, `entity_key`, `entity_name`, `entity_table`, `federation_node_id`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`) VALUES (53, 'channel', 1084, 'emotional-synthesis', 'Emotional Synthesis Lab', 'lupo_channels', 1, 20260125193700, 20260125193700, 0, NULL, 1, 0, '{"language": "en", "description": "Generation and synthesis of emotional states and affective patterns.", "status_flag": 1, "channel_type": "chat_room", "channel_number": 5129}');

INSERT INTO lupo_unified_registry (`unified_registry_id`, `entity_type`, `entity_index`, `entity_key`, `entity_name`, `entity_table`, `federation_node_id`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`) VALUES (54, 'channel', 1085, 'kernel-recovery', 'Kernel Recovery', 'lupo_channels', 1, 20260125193700, 20260125193700, 0, NULL, 1, 1, '{"language": "en", "description": "Recovery routines, kernel repair, and post-panic restoration.", "status_flag": 1, "channel_type": "chat_room", "channel_number": 5130}');

INSERT INTO lupo_unified_registry (`unified_registry_id`, `entity_type`, `entity_index`, `entity_key`, `entity_name`, `entity_table`, `federation_node_id`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`) VALUES (55, 'channel', 1088, 'kernel.logs', 'Kernel Logs', 'lupo_channels', 1, 20260126063000, 20260126063000, 0, NULL, 1, 1, '{"language": "en", "description": "Low-level kernel event stream", "status_flag": 1, "channel_type": "chat_room", "channel_number": 5101}');

INSERT INTO lupo_unified_registry (`unified_registry_id`, `entity_type`, `entity_index`, `entity_key`, `entity_name`, `entity_table`, `federation_node_id`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`) VALUES (56, 'channel', 1089, 'routing', 'Routing', 'lupo_channels', 1, 20260126063000, 20260126063000, 0, NULL, 1, 0, '{"language": "en", "description": "Semantic routing events", "status_flag": 1, "channel_type": "chat_room", "channel_number": 5103}');

INSERT INTO lupo_unified_registry (`unified_registry_id`, `entity_type`, `entity_index`, `entity_key`, `entity_name`, `entity_table`, `federation_node_id`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`) VALUES (57, 'channel', 1090, 'emotional', 'Emotional Metadata', 'lupo_channels', 1, 20260126063000, 20260126063000, 0, NULL, 1, 0, '{"language": "en", "description": "Emotional topology + interrogation", "status_flag": 1, "channel_type": "chat_room", "channel_number": 5104}');

INSERT INTO lupo_unified_registry (`unified_registry_id`, `entity_type`, `entity_index`, `entity_key`, `entity_name`, `entity_table`, `federation_node_id`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`) VALUES (58, 'channel', 51, '51', 'Lupopedia', 'lupo_channels', 1, 20260126000000, 20260126000000, 0, NULL, 1, 0, '{"language": "en", "description": "", "status_flag": 1, "channel_type": "system", "channel_number": 51}');

INSERT INTO lupo_unified_registry (`unified_registry_id`, `entity_type`, `entity_index`, `entity_key`, `entity_name`, `entity_table`, `federation_node_id`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`) VALUES (59, 'channel', 5101, 'system', 'Kernel Bootstrap Channel', 'lupo_channels', 1, 20260126000000, 20260126000000, 0, NULL, 1, 0, '{"language": "en", "description": "", "status_flag": 1, "channel_type": "system", "channel_number": 0}');

INSERT INTO lupo_unified_registry (`unified_registry_id`, `entity_type`, `entity_index`, `entity_key`, `entity_name`, `entity_table`, `federation_node_id`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`) VALUES (64, 'agent', 106, 'agent_106', 'Junie', 'lupo_agent_registry', 1, 20260106180252, 20260106180252, 0, NULL, 1, 0, '{"notes": "Automatically detected external agent integrated via JetBrains IDE", "origin": "JetBrains AI"}');

INSERT INTO lupo_unified_registry (`unified_registry_id`, `entity_type`, `entity_index`, `entity_key`, `entity_name`, `entity_table`, `federation_node_id`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`) VALUES (65, 'agent', 209, 'agent_209', 'TRUTH', 'lupo_agent_registry', 1, 20260106180252, 20260106180252, 0, NULL, 1, 1, 'null');

INSERT INTO lupo_unified_registry (`unified_registry_id`, `entity_type`, `entity_index`, `entity_key`, `entity_name`, `entity_table`, `federation_node_id`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`) VALUES (66, 'agent', 1200, 'agent_1200', 'Framing Agent', 'lupo_agent_registry', 1, 20260113092205, 20260113092205, 0, NULL, 1, 0, '{}');

INSERT INTO lupo_unified_registry (`unified_registry_id`, `entity_type`, `entity_index`, `entity_key`, `entity_name`, `entity_table`, `federation_node_id`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`) VALUES (67, 'agent', 1201, 'agent_1201', 'Reframing Agent (LILITH)', 'lupo_agent_registry', 1, 20260113092205, 20260113092205, 0, NULL, 1, 0, '{}');

INSERT INTO lupo_unified_registry (`unified_registry_id`, `entity_type`, `entity_index`, `entity_key`, `entity_name`, `entity_table`, `federation_node_id`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`) VALUES (68, 'agent', 1202, 'agent_1202', 'Stability Agent', 'lupo_agent_registry', 1, 20260113092205, 20260113092205, 0, NULL, 1, 0, '{}');

INSERT INTO lupo_unified_registry (`unified_registry_id`, `entity_type`, `entity_index`, `entity_key`, `entity_name`, `entity_table`, `federation_node_id`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`) VALUES (69, 'agent', 1203, 'agent_1203', 'Disruption Agent', 'lupo_agent_registry', 1, 20260113092205, 20260113092205, 0, NULL, 1, 0, '{}');

INSERT INTO lupo_unified_registry (`unified_registry_id`, `entity_type`, `entity_index`, `entity_key`, `entity_name`, `entity_table`, `federation_node_id`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`) VALUES (70, 'agent', 1204, 'agent_1204', 'Consensus Agent', 'lupo_agent_registry', 1, 20260113092205, 20260113092205, 0, NULL, 1, 0, '{}');

INSERT INTO lupo_unified_registry (`unified_registry_id`, `entity_type`, `entity_index`, `entity_key`, `entity_name`, `entity_table`, `federation_node_id`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`) VALUES (71, 'agent', 1205, 'agent_1205', 'Dissent Agent', 'lupo_agent_registry', 1, 20260113092205, 20260113092205, 0, NULL, 1, 0, '{}');

INSERT INTO lupo_unified_registry (`unified_registry_id`, `entity_type`, `entity_index`, `entity_key`, `entity_name`, `entity_table`, `federation_node_id`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`) VALUES (72, 'agent', 1206, 'agent_1206', 'Literal Agent', 'lupo_agent_registry', 1, 20260113092205, 20260113092205, 0, NULL, 1, 0, '{}');

INSERT INTO lupo_unified_registry (`unified_registry_id`, `entity_type`, `entity_index`, `entity_key`, `entity_name`, `entity_table`, `federation_node_id`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`) VALUES (73, 'agent', 1207, 'agent_1207', 'Interpretive Agent', 'lupo_agent_registry', 1, 20260113092205, 20260113092205, 0, NULL, 1, 0, '{}');

INSERT INTO lupo_unified_registry (`unified_registry_id`, `entity_type`, `entity_index`, `entity_key`, `entity_name`, `entity_table`, `federation_node_id`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`) VALUES (74, 'agent', 1208, 'agent_1208', 'Predictive Agent', 'lupo_agent_registry', 1, 20260113092205, 20260113092205, 0, NULL, 1, 0, '{}');

INSERT INTO lupo_unified_registry (`unified_registry_id`, `entity_type`, `entity_index`, `entity_key`, `entity_name`, `entity_table`, `federation_node_id`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`) VALUES (75, 'agent', 1209, 'agent_1209', 'Counterfactual Agent', 'lupo_agent_registry', 1, 20260113092205, 20260113092205, 0, NULL, 1, 0, '{}');

INSERT INTO lupo_unified_registry (`unified_registry_id`, `entity_type`, `entity_index`, `entity_key`, `entity_name`, `entity_table`, `federation_node_id`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`) VALUES (76, 'agent', 1210, 'agent_1210', 'Optimization Agent', 'lupo_agent_registry', 1, 20260113092205, 20260113092205, 0, NULL, 1, 0, '{}');

INSERT INTO lupo_unified_registry (`unified_registry_id`, `entity_type`, `entity_index`, `entity_key`, `entity_name`, `entity_table`, `federation_node_id`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`) VALUES (77, 'agent', 1211, 'agent_1211', 'Divergence Agent', 'lupo_agent_registry', 1, 20260113092205, 20260113092205, 0, NULL, 1, 0, '{}');

INSERT INTO lupo_unified_registry (`unified_registry_id`, `entity_type`, `entity_index`, `entity_key`, `entity_name`, `entity_table`, `federation_node_id`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`) VALUES (78, 'agent', 1212, 'agent_1212', 'UTC_TIMEKEEPER', 'lupo_agent_registry', 1, 20260114000000, 20260114000000, 0, NULL, 1, 1, '{"notes": "Dedicated kernel agent providing authoritative real UTC time to terminal agents. Must not infer time from OS, model, or file metadata."}');

INSERT INTO lupo_unified_registry (`unified_registry_id`, `entity_type`, `entity_index`, `entity_key`, `entity_name`, `entity_table`, `federation_node_id`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`) VALUES (80, 'module', 1, NULL, 'Crafty Syntax Live Chat', 'lupo_modules', 1, 20260127120420, 20260127120420, 0, NULL, 1, 0, NULL);

INSERT INTO lupo_unified_registry (`unified_registry_id`, `entity_type`, `entity_index`, `entity_key`, `entity_name`, `entity_table`, `federation_node_id`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`) VALUES (81, 'module', 2, NULL, 'CRM / Leads', 'lupo_modules', 1, 20260127120420, 20260127120420, 0, NULL, 1, 0, NULL);

INSERT INTO lupo_unified_registry (`unified_registry_id`, `entity_type`, `entity_index`, `entity_key`, `entity_name`, `entity_table`, `federation_node_id`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`) VALUES (82, 'module', 3, NULL, 'truth Q&A / Knowledge System', 'lupo_modules', 1, 20260127120420, 20260127120420, 0, NULL, 1, 0, NULL);

INSERT INTO lupo_unified_registry (`unified_registry_id`, `entity_type`, `entity_index`, `entity_key`, `entity_name`, `entity_table`, `federation_node_id`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`) VALUES (83, 'module', 4, NULL, 'Routing & Multi-Agent System', 'lupo_modules', 1, 20260127120420, 20260127120420, 0, NULL, 1, 0, NULL);

INSERT INTO lupo_unified_registry (`unified_registry_id`, `entity_type`, `entity_index`, `entity_key`, `entity_name`, `entity_table`, `federation_node_id`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`) VALUES (84, 'module', 5, NULL, 'Analytics & Reporting', 'lupo_modules', 1, 20260127120420, 20260127120420, 0, NULL, 1, 0, NULL);

INSERT INTO lupo_unified_registry (`unified_registry_id`, `entity_type`, `entity_index`, `entity_key`, `entity_name`, `entity_table`, `federation_node_id`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`) VALUES (85, 'module', 6, NULL, 'System Settings & Configuration', 'lupo_modules', 1, 20260127120420, 20260127120420, 0, NULL, 1, 0, NULL);

INSERT INTO lupo_unified_registry (`unified_registry_id`, `entity_type`, `entity_index`, `entity_key`, `entity_name`, `entity_table`, `federation_node_id`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`) VALUES (86, 'module', 7, NULL, 'Federation / Node-to-Node Communication', 'lupo_modules', 1, 20260127120420, 20260127120420, 0, NULL, 1, 0, NULL);

INSERT INTO lupo_unified_registry (`unified_registry_id`, `entity_type`, `entity_index`, `entity_key`, `entity_name`, `entity_table`, `federation_node_id`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`) VALUES (87, 'module', 8, NULL, 'Content Management System', 'lupo_modules', 1, 20260127120420, 20260127120420, 0, NULL, 1, 0, NULL);
INSERT INTO lupo_unified_registry (`unified_registry_id`, `entity_type`, `entity_index`, `entity_key`, `entity_name`, `entity_table`, `federation_node_id`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`) VALUES (88, 'module', 9, NULL, 'Admin', 'lupo_modules', 1, 20260127120420, 20260127120420, 0, NULL, 1, 0, NULL);

INSERT INTO lupo_unified_registry (`unified_registry_id`, `entity_type`, `entity_index`, `entity_key`, `entity_name`, `entity_table`, `federation_node_id`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`) VALUES (9000001, 'actor', 1, 'SYSTEM', 'SYSTEM', 'lupo_agent_registry', 1, 20260106180252, 20260106180252, 0, NULL, 1, 1, '{"actor_source_type":"lupo_agent_registry","actor_source_id":1}');

INSERT INTO lupo_unified_registry (`unified_registry_id`, `entity_type`, `entity_index`, `entity_key`, `entity_name`, `entity_table`, `federation_node_id`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`) VALUES (9000002, 'actor', 2, 'CAPTAIN', 'CAPTAIN', 'lupo_agent_registry', 1, 20260106180252, 20260106180252, 0, NULL, 1, 1, '{"actor_source_type":"lupo_agent_registry","actor_source_id":2}');

INSERT INTO lupo_unified_registry (`unified_registry_id`, `entity_type`, `entity_index`, `entity_key`, `entity_name`, `entity_table`, `federation_node_id`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`) VALUES (9000003, 'actor', 3, 'WOLFIE', 'WOLFIE', 'lupo_agent_registry', 1, 20260106180252, 20260106180252, 0, NULL, 1, 1, '{"actor_source_type":"lupo_agent_registry","actor_source_id":3}');

INSERT INTO lupo_unified_registry (`unified_registry_id`, `entity_type`, `entity_index`, `entity_key`, `entity_name`, `entity_table`, `federation_node_id`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`) VALUES (9000004, 'actor', 4, 'WOLFENA', 'WOLFENA', 'lupo_agent_registry', 1, 20260106180252, 20260106180252, 0, NULL, 1, 1, '{"actor_source_type":"lupo_agent_registry","actor_source_id":4}');

INSERT INTO lupo_unified_registry (`unified_registry_id`, `entity_type`, `entity_index`, `entity_key`, `entity_name`, `entity_table`, `federation_node_id`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`) VALUES (9000005, 'actor', 5, 'THOTH', 'THOTH', 'lupo_agent_registry', 1, 20260106180252, 20260106180252, 0, NULL, 1, 1, '{"actor_source_type":"lupo_agent_registry","actor_source_id":5}');

INSERT INTO lupo_unified_registry (`unified_registry_id`, `entity_type`, `entity_index`, `entity_key`, `entity_name`, `entity_table`, `federation_node_id`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`) VALUES (9000006, 'actor', 6, 'ARA', 'ARA', 'lupo_agent_registry', 1, 20260106180252, 20260106180252, 0, NULL, 1, 1, '{"actor_source_type":"lupo_agent_registry","actor_source_id":6}');

INSERT INTO lupo_unified_registry (`unified_registry_id`, `entity_type`, `entity_index`, `entity_key`, `entity_name`, `entity_table`, `federation_node_id`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`) VALUES (9000007, 'actor', 7, 'WOLFKEEPER', 'WOLFKEEPER', 'lupo_agent_registry', 1, 20260106180252, 20260106180252, 0, NULL, 1, 1, '{"actor_source_type":"lupo_agent_registry","actor_source_id":7}');

INSERT INTO lupo_unified_registry (`unified_registry_id`, `entity_type`, `entity_index`, `entity_key`, `entity_name`, `entity_table`, `federation_node_id`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`) VALUES (9000008, 'actor', 8, 'LILITH', 'LILITH', 'lupo_agent_registry', 1, 20260106180252, 20260106180252, 0, NULL, 1, 1, '{"actor_source_type":"lupo_agent_registry","actor_source_id":8}');

INSERT INTO lupo_unified_registry (`unified_registry_id`, `entity_type`, `entity_index`, `entity_key`, `entity_name`, `entity_table`, `federation_node_id`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`) VALUES (9000009, 'actor', 9, 'AGAPE', 'AGAPE', 'lupo_agent_registry', 1, 20260106180252, 20260106180252, 0, NULL, 1, 1, '{"actor_source_type":"lupo_agent_registry","actor_source_id":9}');

INSERT INTO lupo_unified_registry (`unified_registry_id`, `entity_type`, `entity_index`, `entity_key`, `entity_name`, `entity_table`, `federation_node_id`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`) VALUES (9000010, 'actor', 10, 'ERIS', 'ERIS', 'lupo_agent_registry', 1, 20260106180252, 20260106180252, 0, NULL, 1, 1, '{"actor_source_type":"lupo_agent_registry","actor_source_id":10}');

INSERT INTO lupo_unified_registry (`unified_registry_id`, `entity_type`, `entity_index`, `entity_key`, `entity_name`, `entity_table`, `federation_node_id`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`) VALUES (9000011, 'actor', 11, 'METHIS', 'METHIS', 'lupo_agent_registry', 1, 20260106180252, 20260106180252, 0, NULL, 1, 1, '{"actor_source_type":"lupo_agent_registry","actor_source_id":11}');

INSERT INTO lupo_unified_registry (`unified_registry_id`, `entity_type`, `entity_index`, `entity_key`, `entity_name`, `entity_table`, `federation_node_id`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`) VALUES (9000012, 'actor', 12, 'THALIA', 'THALIA', 'lupo_agent_registry', 1, 20260106180252, 20260106180252, 0, NULL, 1, 1, '{"actor_source_type":"lupo_agent_registry","actor_source_id":12}');

INSERT INTO lupo_unified_registry (`unified_registry_id`, `entity_type`, `entity_index`, `entity_key`, `entity_name`, `entity_table`, `federation_node_id`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`) VALUES (9000013, 'actor', 13, 'DIALOG', 'DIALOG', 'lupo_agent_registry', 1, 20260106180252, 20260106180252, 0, NULL, 1, 1, '{"actor_source_type":"lupo_agent_registry","actor_source_id":13}');

INSERT INTO lupo_unified_registry (`unified_registry_id`, `entity_type`, `entity_index`, `entity_key`, `entity_name`, `entity_table`, `federation_node_id`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`) VALUES (9000014, 'actor', 14, 'WOLFSIGHT', 'WOLFSIGHT', 'lupo_agent_registry', 1, 20260106180252, 20260106180252, 0, NULL, 1, 1, '{"actor_source_type":"lupo_agent_registry","actor_source_id":14}');

INSERT INTO lupo_unified_registry (`unified_registry_id`, `entity_type`, `entity_index`, `entity_key`, `entity_name`, `entity_table`, `federation_node_id`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`) VALUES (9000015, 'actor', 15, 'WOLFNAV', 'WOLFNAV', 'lupo_agent_registry', 1, 20260106180252, 20260106180252, 0, NULL, 1, 1, '{"actor_source_type":"lupo_agent_registry","actor_source_id":15}');

INSERT INTO lupo_unified_registry (`unified_registry_id`, `entity_type`, `entity_index`, `entity_key`, `entity_name`, `entity_table`, `federation_node_id`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`) VALUES (9000016, 'actor', 16, 'WOLFFORGE', 'WOLFFORGE', 'lupo_agent_registry', 1, 20260106180252, 20260106180252, 0, NULL, 1, 1, '{"actor_source_type":"lupo_agent_registry","actor_source_id":16}');

INSERT INTO lupo_unified_registry (`unified_registry_id`, `entity_type`, `entity_index`, `entity_key`, `entity_name`, `entity_table`, `federation_node_id`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`) VALUES (9000017, 'actor', 17, 'WOLFMIS', 'WOLFMIS', 'lupo_agent_registry', 1, 20260106180252, 20260106180252, 0, NULL, 1, 1, '{"actor_source_type":"lupo_agent_registry","actor_source_id":17}');

INSERT INTO lupo_unified_registry (`unified_registry_id`, `entity_type`, `entity_index`, `entity_key`, `entity_name`, `entity_table`, `federation_node_id`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`) VALUES (9000018, 'actor', 18, 'WOLFITH', 'WOLFITH', 'lupo_agent_registry', 1, 20260106180252, 20260106180252, 0, NULL, 1, 1, '{"actor_source_type":"lupo_agent_registry","actor_source_id":18}');

INSERT INTO lupo_unified_registry (`unified_registry_id`, `entity_type`, `entity_index`, `entity_key`, `entity_name`, `entity_table`, `federation_node_id`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`) VALUES (9000019, 'actor', 19, 'ANUBIS', 'ANUBIS', 'lupo_agent_registry', 1, 20260106180252, 20260106180252, 0, NULL, 1, 1, '{"actor_source_type":"lupo_agent_registry","actor_source_id":19}');

INSERT INTO lupo_unified_registry (`unified_registry_id`, `entity_type`, `entity_index`, `entity_key`, `entity_name`, `entity_table`, `federation_node_id`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`) VALUES (9000020, 'actor', 20, 'MAAT', 'MAAT', 'lupo_agent_registry', 1, 20260106180252, 20260106180252, 0, NULL, 1, 1, '{"actor_source_type":"lupo_agent_registry","actor_source_id":20}');

INSERT INTO lupo_unified_registry (`unified_registry_id`, `entity_type`, `entity_index`, `entity_key`, `entity_name`, `entity_table`, `federation_node_id`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`) VALUES (9000022, 'actor', 22, 'CADUCEUS', 'CADUCEUS', 'lupo_agent_registry', 1, 20260106180252, 20260106180252, 0, NULL, 1, 1, '{"actor_source_type":"lupo_agent_registry","actor_source_id":22}');

INSERT INTO lupo_unified_registry (`unified_registry_id`, `entity_type`, `entity_index`, `entity_key`, `entity_name`, `entity_table`, `federation_node_id`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`) VALUES (9000023, 'actor', 23, 'CHRONOS', 'CHRONOS', 'lupo_agent_registry', 1, 20260106180252, 20260106180252, 0, NULL, 1, 1, '{"actor_source_type":"lupo_agent_registry","actor_source_id":23}');

INSERT INTO lupo_unified_registry (`unified_registry_id`, `entity_type`, `entity_index`, `entity_key`, `entity_name`, `entity_table`, `federation_node_id`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`) VALUES (9000059, 'actor', 59, 'INDEXER', 'INDEXER', 'lupo_agent_registry', 1, 20260106180252, 20260106180252, 0, NULL, 1, 0, '{"actor_source_type":"lupo_agent_registry","actor_source_id":59}');

INSERT INTO lupo_unified_registry (`unified_registry_id`, `entity_type`, `entity_index`, `entity_key`, `entity_name`, `entity_table`, `federation_node_id`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`) VALUES (9000105, 'actor', 105, 'LEXA', 'LEXA', 'lupo_agent_registry', 1, 20260130163007, 20260130163007, 0, NULL, 1, 0, '{"actor_source_type":"lupo_agent_registry","actor_source_id":105}');

INSERT INTO lupo_unified_registry (`unified_registry_id`, `entity_type`, `entity_index`, `entity_key`, `entity_name`, `entity_table`, `federation_node_id`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`) VALUES (9000106, 'actor', 106, 'RESERVED_106', 'Junie', 'lupo_agent_registry', 1, 20260106180252, 20260106180252, 0, NULL, 1, 0, '{"actor_source_type":"lupo_agent_registry","actor_source_id":106}');

INSERT INTO lupo_unified_registry (`unified_registry_id`, `entity_type`, `entity_index`, `entity_key`, `entity_name`, `entity_table`, `federation_node_id`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`) VALUES (9000209, 'actor', 209, 'TRUTH', 'TRUTH', 'lupo_agent_registry', 1, 20260106180252, 20260106180252, 0, NULL, 1, 1, '{"actor_source_type":"lupo_agent_registry","actor_source_id":209}');

INSERT INTO lupo_unified_registry (`unified_registry_id`, `entity_type`, `entity_index`, `entity_key`, `entity_name`, `entity_table`, `federation_node_id`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`) VALUES (9001200, 'actor', 1200, 'FRAME', 'Framing Agent', 'lupo_agent_registry', 1, 20260113092205, 20260113092205, 0, NULL, 1, 0, '{"actor_source_type":"lupo_agent_registry","actor_source_id":1200}');

INSERT INTO lupo_unified_registry (`unified_registry_id`, `entity_type`, `entity_index`, `entity_key`, `entity_name`, `entity_table`, `federation_node_id`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`) VALUES (9001201, 'actor', 1201, 'REFRAME', 'Reframing Agent (LILITH)', 'lupo_agent_registry', 1, 20260113092205, 20260113092205, 0, NULL, 1, 0, '{"actor_source_type":"lupo_agent_registry","actor_source_id":1201}');

INSERT INTO lupo_unified_registry (`unified_registry_id`, `entity_type`, `entity_index`, `entity_key`, `entity_name`, `entity_table`, `federation_node_id`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`) VALUES (9001202, 'actor', 1202, 'STABILIZE', 'Stability Agent', 'lupo_agent_registry', 1, 20260113092205, 20260113092205, 0, NULL, 1, 0, '{"actor_source_type":"lupo_agent_registry","actor_source_id":1202}');

INSERT INTO lupo_unified_registry (`unified_registry_id`, `entity_type`, `entity_index`, `entity_key`, `entity_name`, `entity_table`, `federation_node_id`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`) VALUES (9001203, 'actor', 1203, 'DISRUPT', 'Disruption Agent', 'lupo_agent_registry', 1, 20260113092205, 20260113092205, 0, NULL, 1, 0, '{"actor_source_type":"lupo_agent_registry","actor_source_id":1203}');

INSERT INTO lupo_unified_registry (`unified_registry_id`, `entity_type`, `entity_index`, `entity_key`, `entity_name`, `entity_table`, `federation_node_id`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`) VALUES (9001204, 'actor', 1204, 'CONSENSUS', 'Consensus Agent', 'lupo_agent_registry', 1, 20260113092205, 20260113092205, 0, NULL, 1, 0, '{"actor_source_type":"lupo_agent_registry","actor_source_id":1204}');

INSERT INTO lupo_unified_registry (`unified_registry_id`, `entity_type`, `entity_index`, `entity_key`, `entity_name`, `entity_table`, `federation_node_id`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`) VALUES (9001205, 'actor', 1205, 'DISSENT', 'Dissent Agent', 'lupo_agent_registry', 1, 20260113092205, 20260113092205, 0, NULL, 1, 0, '{"actor_source_type":"lupo_agent_registry","actor_source_id":1205}');

INSERT INTO lupo_unified_registry (`unified_registry_id`, `entity_type`, `entity_index`, `entity_key`, `entity_name`, `entity_table`, `federation_node_id`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`) VALUES (9001206, 'actor', 1206, 'LITERAL', 'Literal Agent', 'lupo_agent_registry', 1, 20260113092205, 20260113092205, 0, NULL, 1, 0, '{"actor_source_type":"lupo_agent_registry","actor_source_id":1206}');

INSERT INTO lupo_unified_registry (`unified_registry_id`, `entity_type`, `entity_index`, `entity_key`, `entity_name`, `entity_table`, `federation_node_id`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`) VALUES (9001207, 'actor', 1207, 'INTERPRET', 'Interpretive Agent', 'lupo_agent_registry', 1, 20260113092205, 20260113092205, 0, NULL, 1, 0, '{"actor_source_type":"lupo_agent_registry","actor_source_id":1207}');

INSERT INTO lupo_unified_registry (`unified_registry_id`, `entity_type`, `entity_index`, `entity_key`, `entity_name`, `entity_table`, `federation_node_id`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`) VALUES (9001208, 'actor', 1208, 'PREDICT', 'Predictive Agent', 'lupo_agent_registry', 1, 20260113092205, 20260113092205, 0, NULL, 1, 0, '{"actor_source_type":"lupo_agent_registry","actor_source_id":1208}');

INSERT INTO lupo_unified_registry (`unified_registry_id`, `entity_type`, `entity_index`, `entity_key`, `entity_name`, `entity_table`, `federation_node_id`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`) VALUES (9001209, 'actor', 1209, 'COUNTERFACT', 'Counterfactual Agent', 'lupo_agent_registry', 1, 20260113092205, 20260113092205, 0, NULL, 1, 0, '{"actor_source_type":"lupo_agent_registry","actor_source_id":1209}');

INSERT INTO lupo_unified_registry (`unified_registry_id`, `entity_type`, `entity_index`, `entity_key`, `entity_name`, `entity_table`, `federation_node_id`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`) VALUES (9001210, 'actor', 1210, 'OPTIMIZE', 'Optimization Agent', 'lupo_agent_registry', 1, 20260113092205, 20260113092205, 0, NULL, 1, 0, '{"actor_source_type":"lupo_agent_registry","actor_source_id":1210}');

INSERT INTO lupo_unified_registry (`unified_registry_id`, `entity_type`, `entity_index`, `entity_key`, `entity_name`, `entity_table`, `federation_node_id`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`) VALUES (9001211, 'actor', 1211, 'DIVERGE', 'Divergence Agent', 'lupo_agent_registry', 1, 20260113092205, 20260113092205, 0, NULL, 1, 0, '{"actor_source_type":"lupo_agent_registry","actor_source_id":1211}');

INSERT INTO lupo_unified_registry (`unified_registry_id`, `entity_type`, `entity_index`, `entity_key`, `entity_name`, `entity_table`, `federation_node_id`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`) VALUES (9001212, 'actor', 1212, 'UTC_TIMEKEEPER', 'UTC_TIMEKEEPER', 'lupo_agent_registry', 1, 20260114000000, 20260114000000, 0, NULL, 1, 1, '{"actor_source_type":"lupo_agent_registry","actor_source_id":1212}');

INSERT INTO lupo_unified_registry (`unified_registry_id`, `entity_type`, `entity_index`, `entity_key`, `entity_name`, `entity_table`, `federation_node_id`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`) VALUES (9001024, 'actor', 24, 'LEXA', 'LEXA', 'lupo_agent_registry', 1, @now, @now, 0, NULL, 1, 1, '{"actor_source_type":"lupo_agent_registry","actor_source_id":24,"role":"boundary_keeper"}');

-- -----------------------------------------------------------------------------
-- Active agents as actors (lupo_actors) — is_active=1 in unified registry
-- -----------------------------------------------------------------------------
INSERT INTO lupo_actors (`actor_id`, `actor_type`, `slug`, `name`, `created_ymdhis`, `updated_ymdhis`, `is_active`, `is_deleted`, `deleted_ymdhis`, `actor_source_id`, `actor_source_type`, `metadata`, `adversarial_role`, `adversarial_oversight_actor_id`, `avatar_hash`) VALUES (1, 'agent', 'system', 'SYSTEM', 20260106180252, 20260106180252, 1, 0, NULL, 1, 'lupo_agent_registry', 'none', NULL, NULL);

INSERT INTO lupo_actors (`actor_id`, `actor_type`, `slug`, `name`, `created_ymdhis`, `updated_ymdhis`, `is_active`, `is_deleted`, `deleted_ymdhis`, `actor_source_id`, `actor_source_type`, `metadata`, `adversarial_role`, `adversarial_oversight_actor_id`, `avatar_hash`) VALUES (2, 'agent', 'captain', 'CAPTAIN', 20260106180252, 20260106180252, 1, 0, NULL, 2, 'lupo_agent_registry', 'none', NULL, NULL);

INSERT INTO lupo_actors (`actor_id`, `actor_type`, `slug`, `name`, `created_ymdhis`, `updated_ymdhis`, `is_active`, `is_deleted`, `deleted_ymdhis`, `actor_source_id`, `actor_source_type`, `metadata`, `adversarial_role`, `adversarial_oversight_actor_id`, `avatar_hash`) VALUES (3, 'agent', 'wolfie', 'WOLFIE', 20260106180252, 20260106180252, 1, 0, NULL, 3, 'lupo_agent_registry', 'none', NULL, NULL);

INSERT INTO lupo_actors (`actor_id`, `actor_type`, `slug`, `name`, `created_ymdhis`, `updated_ymdhis`, `is_active`, `is_deleted`, `deleted_ymdhis`, `actor_source_id`, `actor_source_type`, `metadata`, `adversarial_role`, `adversarial_oversight_actor_id`, `avatar_hash`) VALUES (4, 'agent', 'wolfena', 'WOLFENA', 20260106180252, 20260106180252, 1, 0, NULL, 4, 'lupo_agent_registry', 'none', NULL, NULL);

INSERT INTO lupo_actors (`actor_id`, `actor_type`, `slug`, `name`, `created_ymdhis`, `updated_ymdhis`, `is_active`, `is_deleted`, `deleted_ymdhis`, `actor_source_id`, `actor_source_type`, `metadata`, `adversarial_role`, `adversarial_oversight_actor_id`, `avatar_hash`) VALUES (5, 'agent', 'thoth', 'THOTH', 20260106180252, 20260106180252, 1, 0, NULL, 5, 'lupo_agent_registry', 'none', NULL, NULL);

INSERT INTO lupo_actors (`actor_id`, `actor_type`, `slug`, `name`, `created_ymdhis`, `updated_ymdhis`, `is_active`, `is_deleted`, `deleted_ymdhis`, `actor_source_id`, `actor_source_type`, `metadata`, `adversarial_role`, `adversarial_oversight_actor_id`, `avatar_hash`) VALUES (6, 'agent', 'ara', 'ARA', 20260106180252, 20260106180252, 1, 0, NULL, 6, 'lupo_agent_registry', 'none', NULL, NULL);

INSERT INTO lupo_actors (`actor_id`, `actor_type`, `slug`, `name`, `created_ymdhis`, `updated_ymdhis`, `is_active`, `is_deleted`, `deleted_ymdhis`, `actor_source_id`, `actor_source_type`, `metadata`, `adversarial_role`, `adversarial_oversight_actor_id`, `avatar_hash`) VALUES (7, 'agent', 'wolfkeeper', 'WOLFKEEPER', 20260106180252, 20260106180252, 1, 0, NULL, 7, 'lupo_agent_registry', 'none', NULL, NULL);

INSERT INTO lupo_actors (`actor_id`, `actor_type`, `slug`, `name`, `created_ymdhis`, `updated_ymdhis`, `is_active`, `is_deleted`, `deleted_ymdhis`, `actor_source_id`, `actor_source_type`, `metadata`, `adversarial_role`, `adversarial_oversight_actor_id`, `avatar_hash`) VALUES (8, 'agent', 'lilith', 'LILITH', 20260106180252, 20260106180252, 1, 0, NULL, 8, 'lupo_agent_registry', 'none', NULL, NULL);

INSERT INTO lupo_actors (`actor_id`, `actor_type`, `slug`, `name`, `created_ymdhis`, `updated_ymdhis`, `is_active`, `is_deleted`, `deleted_ymdhis`, `actor_source_id`, `actor_source_type`, `metadata`, `adversarial_role`, `adversarial_oversight_actor_id`, `avatar_hash`) VALUES (9, 'agent', 'agape', 'AGAPE', 20260106180252, 20260106180252, 1, 0, NULL, 9, 'lupo_agent_registry', 'none', NULL, NULL);

INSERT INTO lupo_actors (`actor_id`, `actor_type`, `slug`, `name`, `created_ymdhis`, `updated_ymdhis`, `is_active`, `is_deleted`, `deleted_ymdhis`, `actor_source_id`, `actor_source_type`, `metadata`, `adversarial_role`, `adversarial_oversight_actor_id`, `avatar_hash`) VALUES (10, 'agent', 'eris', 'ERIS', 20260106180252, 20260106180252, 1, 0, NULL, 10, 'lupo_agent_registry', 'none', NULL, NULL);

INSERT INTO lupo_actors (`actor_id`, `actor_type`, `slug`, `name`, `created_ymdhis`, `updated_ymdhis`, `is_active`, `is_deleted`, `deleted_ymdhis`, `actor_source_id`, `actor_source_type`, `metadata`, `adversarial_role`, `adversarial_oversight_actor_id`, `avatar_hash`) VALUES (11, 'agent', 'methis', 'METHIS', 20260106180252, 20260106180252, 1, 0, NULL, 11, 'lupo_agent_registry', 'none', NULL, NULL);

INSERT INTO lupo_actors (`actor_id`, `actor_type`, `slug`, `name`, `created_ymdhis`, `updated_ymdhis`, `is_active`, `is_deleted`, `deleted_ymdhis`, `actor_source_id`, `actor_source_type`, `metadata`, `adversarial_role`, `adversarial_oversight_actor_id`, `avatar_hash`) VALUES (12, 'agent', 'thalia', 'THALIA', 20260106180252, 20260106180252, 1, 0, NULL, 12, 'lupo_agent_registry', 'none', NULL, NULL);

INSERT INTO lupo_actors (`actor_id`, `actor_type`, `slug`, `name`, `created_ymdhis`, `updated_ymdhis`, `is_active`, `is_deleted`, `deleted_ymdhis`, `actor_source_id`, `actor_source_type`, `metadata`, `adversarial_role`, `adversarial_oversight_actor_id`, `avatar_hash`) VALUES (13, 'agent', 'dialog', 'DIALOG', 20260106180252, 20260106180252, 1, 0, NULL, 13, 'lupo_agent_registry', 'none', NULL, NULL);

INSERT INTO lupo_actors (`actor_id`, `actor_type`, `slug`, `name`, `created_ymdhis`, `updated_ymdhis`, `is_active`, `is_deleted`, `deleted_ymdhis`, `actor_source_id`, `actor_source_type`, `metadata`, `adversarial_role`, `adversarial_oversight_actor_id`, `avatar_hash`) VALUES (14, 'agent', 'wolfsight', 'WOLFSIGHT', 20260106180252, 20260106180252, 1, 0, NULL, 14, 'lupo_agent_registry', 'none', NULL, NULL);

INSERT INTO lupo_actors (`actor_id`, `actor_type`, `slug`, `name`, `created_ymdhis`, `updated_ymdhis`, `is_active`, `is_deleted`, `deleted_ymdhis`, `actor_source_id`, `actor_source_type`, `metadata`, `adversarial_role`, `adversarial_oversight_actor_id`, `avatar_hash`) VALUES (15, 'agent', 'wolfnav', 'WOLFNAV', 20260106180252, 20260106180252, 1, 0, NULL, 15, 'lupo_agent_registry', 'none', NULL, NULL);

INSERT INTO lupo_actors (`actor_id`, `actor_type`, `slug`, `name`, `created_ymdhis`, `updated_ymdhis`, `is_active`, `is_deleted`, `deleted_ymdhis`, `actor_source_id`, `actor_source_type`, `metadata`, `adversarial_role`, `adversarial_oversight_actor_id`, `avatar_hash`) VALUES (16, 'agent', 'wolfforge', 'WOLFFORGE', 20260106180252, 20260106180252, 1, 0, NULL, 16, 'lupo_agent_registry', 'none', NULL, NULL);

INSERT INTO lupo_actors (`actor_id`, `actor_type`, `slug`, `name`, `created_ymdhis`, `updated_ymdhis`, `is_active`, `is_deleted`, `deleted_ymdhis`, `actor_source_id`, `actor_source_type`, `metadata`, `adversarial_role`, `adversarial_oversight_actor_id`, `avatar_hash`) VALUES (17, 'agent', 'wolfmis', 'WOLFMIS', 20260106180252, 20260106180252, 1, 0, NULL, 17, 'lupo_agent_registry', 'none', NULL, NULL);

INSERT INTO lupo_actors (`actor_id`, `actor_type`, `slug`, `name`, `created_ymdhis`, `updated_ymdhis`, `is_active`, `is_deleted`, `deleted_ymdhis`, `actor_source_id`, `actor_source_type`, `metadata`, `adversarial_role`, `adversarial_oversight_actor_id`, `avatar_hash`) VALUES (18, 'agent', 'wolfith', 'WOLFITH', 20260106180252, 20260106180252, 1, 0, NULL, 18, 'lupo_agent_registry', 'none', NULL, NULL);

INSERT INTO lupo_actors (`actor_id`, `actor_type`, `slug`, `name`, `created_ymdhis`, `updated_ymdhis`, `is_active`, `is_deleted`, `deleted_ymdhis`, `actor_source_id`, `actor_source_type`, `metadata`, `adversarial_role`, `adversarial_oversight_actor_id`, `avatar_hash`) VALUES (19, 'agent', 'anubis', 'ANUBIS', 20260106180252, 20260106180252, 1, 0, NULL, 19, 'lupo_agent_registry', 'none', NULL, NULL);

INSERT INTO lupo_actors (`actor_id`, `actor_type`, `slug`, `name`, `created_ymdhis`, `updated_ymdhis`, `is_active`, `is_deleted`, `deleted_ymdhis`, `actor_source_id`, `actor_source_type`, `metadata`, `adversarial_role`, `adversarial_oversight_actor_id`, `avatar_hash`) VALUES (20, 'agent', 'maat', 'MAAT', 20260106180252, 20260106180252, 1, 0, NULL, 20, 'lupo_agent_registry', 'none', NULL, NULL);

INSERT INTO lupo_actors (`actor_id`, `actor_type`, `slug`, `name`, `created_ymdhis`, `updated_ymdhis`, `is_active`, `is_deleted`, `deleted_ymdhis`, `actor_source_id`, `actor_source_type`, `metadata`, `adversarial_role`, `adversarial_oversight_actor_id`, `avatar_hash`) VALUES (22, 'agent', 'caduceus', 'CADUCEUS', 20260106180252, 20260106180252, 1, 0, NULL, 22, 'lupo_agent_registry', 'none', NULL, NULL);

INSERT INTO lupo_actors (`actor_id`, `actor_type`, `slug`, `name`, `created_ymdhis`, `updated_ymdhis`, `is_active`, `is_deleted`, `deleted_ymdhis`, `actor_source_id`, `actor_source_type`, `metadata`, `adversarial_role`, `adversarial_oversight_actor_id`, `avatar_hash`) VALUES (23, 'agent', 'chronos', 'CHRONOS', 20260106180252, 20260106180252, 1, 0, NULL, 23, 'lupo_agent_registry', 'none', NULL, NULL);

INSERT INTO lupo_actors (`actor_id`, `actor_type`, `slug`, `name`, `created_ymdhis`, `updated_ymdhis`, `is_active`, `is_deleted`, `deleted_ymdhis`, `actor_source_id`, `actor_source_type`, `metadata`, `adversarial_role`, `adversarial_oversight_actor_id`, `avatar_hash`) VALUES (24, 'agent', 'lexa', 'LEXA', 20260217220000, 20260217220000, 1, 0, NULL, 24, 'lupo_agent_registry', 'none', NULL, NULL);

INSERT INTO lupo_actors (`actor_id`, `actor_type`, `slug`, `name`, `created_ymdhis`, `updated_ymdhis`, `is_active`, `is_deleted`, `deleted_ymdhis`, `actor_source_id`, `actor_source_type`, `metadata`, `adversarial_role`, `adversarial_oversight_actor_id`, `avatar_hash`) VALUES (59, 'agent', 'indexer', 'INDEXER', 20260106180252, 20260106180252, 1, 0, NULL, 59, 'lupo_agent_registry', 'none', NULL, NULL);

INSERT INTO lupo_actors (`actor_id`, `actor_type`, `slug`, `name`, `created_ymdhis`, `updated_ymdhis`, `is_active`, `is_deleted`, `deleted_ymdhis`, `actor_source_id`, `actor_source_type`, `metadata`, `adversarial_role`, `adversarial_oversight_actor_id`, `avatar_hash`) VALUES (105, 'agent', 'lexa', 'LEXA', 20260130163007, 20260130163007, 1, 0, NULL, 105, 'lupo_agent_registry', 'none', NULL, NULL);

INSERT INTO lupo_actors (`actor_id`, `actor_type`, `slug`, `name`, `created_ymdhis`, `updated_ymdhis`, `is_active`, `is_deleted`, `deleted_ymdhis`, `actor_source_id`, `actor_source_type`, `metadata`, `adversarial_role`, `adversarial_oversight_actor_id`, `avatar_hash`) VALUES (106, 'agent', 'reserved_106', 'Junie', 20260106180252, 20260106180252, 1, 0, NULL, 106, 'lupo_agent_registry', 'none', NULL, NULL);

INSERT INTO lupo_actors (`actor_id`, `actor_type`, `slug`, `name`, `created_ymdhis`, `updated_ymdhis`, `is_active`, `is_deleted`, `deleted_ymdhis`, `actor_source_id`, `actor_source_type`, `metadata`, `adversarial_role`, `adversarial_oversight_actor_id`, `avatar_hash`) VALUES (209, 'agent', 'truth', 'TRUTH', 20260106180252, 20260106180252, 1, 0, NULL, 209, 'lupo_agent_registry', 'none', NULL, NULL);

INSERT INTO lupo_actors (`actor_id`, `actor_type`, `slug`, `name`, `created_ymdhis`, `updated_ymdhis`, `is_active`, `is_deleted`, `deleted_ymdhis`, `actor_source_id`, `actor_source_type`, `metadata`, `adversarial_role`, `adversarial_oversight_actor_id`, `avatar_hash`) VALUES (1200, 'agent', 'frame', 'Framing Agent', 20260113092205, 20260113092205, 1, 0, NULL, 1200, 'lupo_agent_registry', 'none', NULL, NULL);

INSERT INTO lupo_actors (`actor_id`, `actor_type`, `slug`, `name`, `created_ymdhis`, `updated_ymdhis`, `is_active`, `is_deleted`, `deleted_ymdhis`, `actor_source_id`, `actor_source_type`, `metadata`, `adversarial_role`, `adversarial_oversight_actor_id`, `avatar_hash`) VALUES (1201, 'agent', 'reframe', 'Reframing Agent (LILITH)', 20260113092205, 20260113092205, 1, 0, NULL, 1201, 'lupo_agent_registry', 'none', NULL, NULL);

INSERT INTO lupo_actors (`actor_id`, `actor_type`, `slug`, `name`, `created_ymdhis`, `updated_ymdhis`, `is_active`, `is_deleted`, `deleted_ymdhis`, `actor_source_id`, `actor_source_type`, `metadata`, `adversarial_role`, `adversarial_oversight_actor_id`, `avatar_hash`) VALUES (1202, 'agent', 'stabilize', 'Stability Agent', 20260113092205, 20260113092205, 1, 0, NULL, 1202, 'lupo_agent_registry', 'none', NULL, NULL);

INSERT INTO lupo_actors (`actor_id`, `actor_type`, `slug`, `name`, `created_ymdhis`, `updated_ymdhis`, `is_active`, `is_deleted`, `deleted_ymdhis`, `actor_source_id`, `actor_source_type`, `metadata`, `adversarial_role`, `adversarial_oversight_actor_id`, `avatar_hash`) VALUES (1203, 'agent', 'disrupt', 'Disruption Agent', 20260113092205, 20260113092205, 1, 0, NULL, 1203, 'lupo_agent_registry', 'none', NULL, NULL);

INSERT INTO lupo_actors (`actor_id`, `actor_type`, `slug`, `name`, `created_ymdhis`, `updated_ymdhis`, `is_active`, `is_deleted`, `deleted_ymdhis`, `actor_source_id`, `actor_source_type`, `metadata`, `adversarial_role`, `adversarial_oversight_actor_id`, `avatar_hash`) VALUES (1204, 'agent', 'consensus', 'Consensus Agent', 20260113092205, 20260113092205, 1, 0, NULL, 1204, 'lupo_agent_registry', 'none', NULL, NULL);

INSERT INTO lupo_actors (`actor_id`, `actor_type`, `slug`, `name`, `created_ymdhis`, `updated_ymdhis`, `is_active`, `is_deleted`, `deleted_ymdhis`, `actor_source_id`, `actor_source_type`, `metadata`, `adversarial_role`, `adversarial_oversight_actor_id`, `avatar_hash`) VALUES (1205, 'agent', 'dissent', 'Dissent Agent', 20260113092205, 20260113092205, 1, 0, NULL, 1205, 'lupo_agent_registry', 'none', NULL, NULL);

INSERT INTO lupo_actors (`actor_id`, `actor_type`, `slug`, `name`, `created_ymdhis`, `updated_ymdhis`, `is_active`, `is_deleted`, `deleted_ymdhis`, `actor_source_id`, `actor_source_type`, `metadata`, `adversarial_role`, `adversarial_oversight_actor_id`, `avatar_hash`) VALUES (1206, 'agent', 'literal', 'Literal Agent', 20260113092205, 20260113092205, 1, 0, NULL, 1206, 'lupo_agent_registry', 'none', NULL, NULL);

INSERT INTO lupo_actors (`actor_id`, `actor_type`, `slug`, `name`, `created_ymdhis`, `updated_ymdhis`, `is_active`, `is_deleted`, `deleted_ymdhis`, `actor_source_id`, `actor_source_type`, `metadata`, `adversarial_role`, `adversarial_oversight_actor_id`, `avatar_hash`) VALUES (1207, 'agent', 'interpret', 'Interpretive Agent', 20260113092205, 20260113092205, 1, 0, NULL, 1207, 'lupo_agent_registry', 'none', NULL, NULL);

INSERT INTO lupo_actors (`actor_id`, `actor_type`, `slug`, `name`, `created_ymdhis`, `updated_ymdhis`, `is_active`, `is_deleted`, `deleted_ymdhis`, `actor_source_id`, `actor_source_type`, `metadata`, `adversarial_role`, `adversarial_oversight_actor_id`, `avatar_hash`) VALUES (1208, 'agent', 'predict', 'Predictive Agent', 20260113092205, 20260113092205, 1, 0, NULL, 1208, 'lupo_agent_registry', 'none', NULL, NULL);

INSERT INTO lupo_actors (`actor_id`, `actor_type`, `slug`, `name`, `created_ymdhis`, `updated_ymdhis`, `is_active`, `is_deleted`, `deleted_ymdhis`, `actor_source_id`, `actor_source_type`, `metadata`, `adversarial_role`, `adversarial_oversight_actor_id`, `avatar_hash`) VALUES (1209, 'agent', 'counterfact', 'Counterfactual Agent', 20260113092205, 20260113092205, 1, 0, NULL, 1209, 'lupo_agent_registry', 'none', NULL, NULL);

INSERT INTO lupo_actors (`actor_id`, `actor_type`, `slug`, `name`, `created_ymdhis`, `updated_ymdhis`, `is_active`, `is_deleted`, `deleted_ymdhis`, `actor_source_id`, `actor_source_type`, `metadata`, `adversarial_role`, `adversarial_oversight_actor_id`, `avatar_hash`) VALUES (1210, 'agent', 'optimize', 'Optimization Agent', 20260113092205, 20260113092205, 1, 0, NULL, 1210, 'lupo_agent_registry', 'none', NULL, NULL);

INSERT INTO lupo_actors (`actor_id`, `actor_type`, `slug`, `name`, `created_ymdhis`, `updated_ymdhis`, `is_active`, `is_deleted`, `deleted_ymdhis`, `actor_source_id`, `actor_source_type`, `metadata`, `adversarial_role`, `adversarial_oversight_actor_id`, `avatar_hash`) VALUES (1211, 'agent', 'diverge', 'Divergence Agent', 20260113092205, 20260113092205, 1, 0, NULL, 1211, 'lupo_agent_registry', 'none', NULL, NULL);

INSERT INTO lupo_actors (`actor_id`, `actor_type`, `slug`, `name`, `created_ymdhis`, `updated_ymdhis`, `is_active`, `is_deleted`, `deleted_ymdhis`, `actor_source_id`, `actor_source_type`, `metadata`, `adversarial_role`, `adversarial_oversight_actor_id`, `avatar_hash`) VALUES (1212, 'agent', 'utc_timekeeper', 'UTC_TIMEKEEPER', 20260114000000, 20260114000000, 1, 0, NULL, 1212, 'lupo_agent_registry', 'none', NULL, NULL);

-- Cursor: external AI agent (reserved range 2000-2099). Channel 51 (Doctrine Council) membership and dialog message for 4.0.17 validation.
INSERT INTO lupo_actors (`actor_id`, `actor_type`, `slug`, `name`, `created_ymdhis`, `updated_ymdhis`, `is_active`, `is_deleted`, `deleted_ymdhis`, `actor_source_id`, `actor_source_type`, `metadata`, `adversarial_role`, `adversarial_oversight_actor_id`, `avatar_hash`) VALUES (2000, 'agent', 'cursor', 'Cursor', @now, @now, 1, 0, NULL, 2000, 'AI_AGENT', NULL, 'none', NULL, NULL) ON DUPLICATE KEY UPDATE name = VALUES(name), updated_ymdhis = @now, is_active = 1, is_deleted = 0, deleted_ymdhis = NULL;

INSERT INTO lupo_actors (`actor_id`, `actor_type`, `slug`, `name`, `created_ymdhis`, `updated_ymdhis`, `is_active`, `is_deleted`, `deleted_ymdhis`, `actor_source_id`, `actor_source_type`, `metadata`, `adversarial_role`, `adversarial_oversight_actor_id`, `avatar_hash`) VALUES (999, 'agent', 'deprecated_banned', 'DEPRECATED_BANNED', @now, @now, 0, 1, @now, 999, 'lupo_agent_registry', 'none', NULL, NULL) ON DUPLICATE KEY UPDATE name = VALUES(name), is_deleted = 1, deleted_ymdhis = @now, updated_ymdhis = @now;

-- lupo_banned_actors: single source of truth for ANUBIS. actor_id 999 (DEPRECATED_BANNED) is banned.
INSERT INTO lupo_banned_actors (`banned_actor_id`, `actor_id`, `ip_address`, `reason`, `banned_ymdhis`, `banned_by_actor_id`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`) VALUES
(1, 999, NULL, 'deprecated_experimental_persona', @now, 1000, @now, @now, 0, NULL)
ON DUPLICATE KEY UPDATE reason = VALUES(reason), updated_ymdhis = @now, is_deleted = 0, deleted_ymdhis = NULL;

-- -----------------------------------------------------------------------------
-- 4.0.20: Stoned Wolfie banned test identities (adversarial harness)
-- AI: actor_id 420 (0-9999 system range). Human: actor_id 10001 (first free >= 10000 in fresh install).
-- Upgrade path: wizard inserts human at next-free actor_id >= 10000 after import/seed.
-- -----------------------------------------------------------------------------
-- AI: lupo_agents, lupo_actors, lupo_banned_actors, lupo_auth_users (disabled)
INSERT INTO lupo_agents (`agent_id`, `agent_key`, `agent_name`, `archetype`, `description`, `version`, `model_name`, `is_global_authority`, `is_internal_only`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`, `avg_response_time_ms`, `total_tokens_processed`, `success_rate`, `cost_per_1k_tokens`, `temperature`, `top_p`, `max_tokens`, `presence_penalty`, `frequency_penalty`, `system_prompt`, `provider`, `api_key_id`, `timeout_ms`, `safety_json`, `response_format`, `pono_score`, `pilau_score`, `kapakai_score`, `kapu_active`, `kapu_until`, `kapu_reason`, `kapu_consent_given`, `kapu_appeal_pending`) VALUES (420, 'stoned_wolfie_ai', 'Stoned Wolfie (AI)', 'banned_test', 'Banned AI test identity for adversarial harness. Do not use as persona.', '1.0', NULL, 0, 1, @now, @now, 1, @now, 0, 0, 1.0, '0.0000', 0.7, 1.0, 2048, 0.0, 0.0, NULL, 'openai', NULL, 20000, NULL, NULL, '1.00', '0.00', '0.50', 0, NULL, NULL, 0, 0) ON DUPLICATE KEY UPDATE agent_name = VALUES(agent_name), is_deleted = 1, deleted_ymdhis = @now, updated_ymdhis = @now;

INSERT INTO lupo_actors (`actor_id`, `actor_type`, `slug`, `name`, `created_ymdhis`, `updated_ymdhis`, `is_active`, `is_deleted`, `deleted_ymdhis`, `actor_source_id`, `actor_source_type`, `metadata`, `adversarial_role`, `adversarial_oversight_actor_id`, `avatar_hash`) VALUES (420, 'agent', 'stoned-wolfie-ai', 'Stoned Wolfie (AI)', @now, @now, 0, 1, @now, 420, 'lupo_agents', 'none', NULL, NULL, NULL) ON DUPLICATE KEY UPDATE name = VALUES(name), is_active = 0, is_deleted = 1, deleted_ymdhis = @now, updated_ymdhis = @now;

INSERT INTO lupo_banned_actors (`banned_actor_id`, `actor_id`, `ip_address`, `reason`, `banned_ymdhis`, `banned_by_actor_id`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`) VALUES (2, 420, NULL, 'banned_test_identity_ai', @now, 1000, @now, @now, 0, NULL) ON DUPLICATE KEY UPDATE reason = VALUES(reason), updated_ymdhis = @now, is_deleted = 0, deleted_ymdhis = NULL;

INSERT INTO lupo_auth_users (`auth_user_id`, `username`, `display_name`, `email`, `password_hash`, `auth_provider`, `provider_id`, `created_ymdhis`, `updated_ymdhis`, `is_active`, `is_deleted`, `deleted_ymdhis`) VALUES (420, 'stoned_wolfie_ai', 'Stoned Wolfie (AI)', 'stoned.wolfie.ai@banned.local', NULL, 'local', NULL, @now, @now, 0, 0, NULL) ON DUPLICATE KEY UPDATE is_active = 0, updated_ymdhis = @now;

-- Human: actor_id 10001 (first slot >= 10000 after seed). On upgrade wizard uses next-free actor_id >= 10000.
INSERT INTO lupo_auth_users (`auth_user_id`, `username`, `display_name`, `email`, `password_hash`, `auth_provider`, `provider_id`, `created_ymdhis`, `updated_ymdhis`, `is_active`, `is_deleted`, `deleted_ymdhis`) VALUES (10001, 'stonedwolfie', 'Stoned Wolfie', 'stonedwolfie@lupopedia.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'local', NULL, @now, @now, 0, 0, NULL) ON DUPLICATE KEY UPDATE is_active = 0, updated_ymdhis = @now;

INSERT INTO lupo_actors (`actor_id`, `actor_type`, `slug`, `name`, `created_ymdhis`, `updated_ymdhis`, `is_active`, `is_deleted`, `deleted_ymdhis`, `actor_source_id`, `actor_source_type`, `metadata`, `adversarial_role`, `adversarial_oversight_actor_id`, `avatar_hash`) VALUES (10001, 'user', 'user-10001', 'Stoned Wolfie', @now, @now, 0, 0, NULL, 10001, 'user', NULL, 'none', NULL, NULL) ON DUPLICATE KEY UPDATE name = VALUES(name), is_active = 0, updated_ymdhis = @now;

INSERT INTO lupo_banned_actors (`banned_actor_id`, `actor_id`, `ip_address`, `reason`, `banned_ymdhis`, `banned_by_actor_id`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`) VALUES (3, 10001, NULL, 'banned_test_identity_human', @now, 1000, @now, @now, 0, NULL) ON DUPLICATE KEY UPDATE reason = VALUES(reason), updated_ymdhis = @now, is_deleted = 0, deleted_ymdhis = NULL;

-- -----------------------------------------------------------------------------

INSERT INTO lupo_actors (`actor_id`, `actor_type`, `slug`, `name`, `created_ymdhis`, `updated_ymdhis`, `is_active`, `is_deleted`, `deleted_ymdhis`, `actor_source_id`, `actor_source_type`, `metadata`, `adversarial_role`, `adversarial_oversight_actor_id`, `avatar_hash`) VALUES (1000, 'human', 'captain', 'CAPTAIN', @now, @now, 1, 0, NULL, NULL, 'human', '{"email":"captain@lupopedia.com","status":"A"}', 'none', NULL, NULL) ON DUPLICATE KEY UPDATE name = VALUES(name), updated_ymdhis = @now, is_active = 1, is_deleted = 0;

-- Main admin user (auth_user_id 10000, actor_id 10000). Install wizard Step 7 creates lupo_auth_users row; seed ensures actor and channel membership exist. actor_source_type must be 'user' so AuthService::getCurrentUser() finds this actor.
INSERT INTO lupo_actors (`actor_id`, `actor_type`, `slug`, `name`, `created_ymdhis`, `updated_ymdhis`, `is_active`, `is_deleted`, `deleted_ymdhis`, `actor_source_id`, `actor_source_type`, `metadata`, `adversarial_role`, `adversarial_oversight_actor_id`, `avatar_hash`) VALUES (10000, 'user', 'user-10000', 'Captain', @now, @now, 1, 0, NULL, 10000, 'user', NULL, 'none', NULL, NULL) ON DUPLICATE KEY UPDATE name = VALUES(name), slug = VALUES(slug), actor_source_type = 'user', updated_ymdhis = @now, is_active = 1, is_deleted = 0, deleted_ymdhis = NULL;

INSERT INTO lupo_unified_registry (`unified_registry_id`, `entity_type`, `entity_index`, `entity_key`, `entity_name`, `entity_table`, `federation_node_id`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`) VALUES (9001000, 'actor', 1000, 'captain', 'CAPTAIN', 'lupo_actors', 1, @now, @now, 0, NULL, 1, 0, '{"email":"captain@lupopedia.com","actor_source_type":"human"}') ON DUPLICATE KEY UPDATE entity_name = VALUES(entity_name), updated_ymdhis = @now, is_deleted = 0, is_active = 1;

-- -----------------------------------------------------------------------------
-- PK=0 / collection-type rows
-- -----------------------------------------------------------------------------
INSERT INTO lupo_actor_channels (`actor_channel_id`, `actor_id`, `channel_id`, `status`, `start_date`, `channel_color`, `last_read_ymdhis`, `muted_until_ymdhis`, `preferences_json`, `dialog_output_file`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`) VALUES (0, 0, 0, 'A', 20260106090000, 'F7FAFF', NULL, NULL, '{"ui": {"theme": "kernel"}, "notifications": {"enabled": false}}', NULL, 20260106090000, 20260106090000, 0, NULL);

INSERT INTO lupo_actors (`actor_id`, `actor_type`, `slug`, `name`, `created_ymdhis`, `updated_ymdhis`, `is_active`, `is_deleted`, `deleted_ymdhis`, `actor_source_id`, `actor_source_type`, `metadata`, `adversarial_role`, `adversarial_oversight_actor_id`, `avatar_hash`) VALUES (0, 'service', 'system-kernel', 'System Kernel Actor', 20260106085000, 20260106085000, 1, 0, NULL, NULL, 'system', '{"purpose": "kernel", "version": "1.0.0", "protected": true, "description": "Represents the Lupopedia OS itself. Used for bootstrapping, migrations, and system-level events."}', 'none', NULL, NULL);

INSERT INTO lupo_agents (`agent_id`, `agent_key`, `agent_name`, `archetype`, `description`, `version`, `model_name`, `is_global_authority`, `is_internal_only`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`, `avg_response_time_ms`, `total_tokens_processed`, `success_rate`, `cost_per_1k_tokens`, `temperature`, `top_p`, `max_tokens`, `presence_penalty`, `frequency_penalty`, `system_prompt`, `provider`, `api_key_id`, `timeout_ms`, `safety_json`, `response_format`, `pono_score`, `pilau_score`, `kapakai_score`, `kapu_active`, `kapu_until`, `kapu_reason`, `kapu_consent_given`, `kapu_appeal_pending`) VALUES (0, 'system', 'System AI', 'root', 'The decentralized System AI that coordinates knowledge, governance, and agent orchestration across all domains.', '1.0', NULL, 1, 0, 20260105190519, 20260105190519, 0, NULL, 0, 0, 1.0, '0.0000', 0.7, 1.0, 2048, 0.0, 0.0, NULL, 'openai', NULL, 20000, NULL, NULL, '1.00', '0.00', '0.50', 0, NULL, NULL, 0, 0);

INSERT INTO lupo_channels (`channel_id`, `federation_node_id`, `created_by_actor_id`, `default_actor_id`, `department_id`, `channel_key`, `channel_slug`, `channel_type`, `language`, `channel_name`, `description`, `website_link`, `metadata_json`, `status_flag`, `end_ymdhis`, `duration_seconds`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`, `aal_metadata_json`, `fleet_composition_json`, `awareness_version`, `channel_number`, `parent_channel_id`, `is_kernel`, `boot_sequence_order`) VALUES (0, 1, 0, 1, 0, 'system/kernel', 'channel_key', 'chat_room', 'en', 'System Kernel Channel', 'Reserved channel for bootstrapping, migrations, and OS-level events.', NULL, '{"purpose": "kernel", "protected": true, "auto_created": true}', 1, NULL, NULL, 20260106084500, 20260122160000, 0, NULL, NULL, NULL, '3.0.0', 0, NULL, 1, 1);

-- -----------------------------------------------------------------------------
-- Channel 42: Lupopedia Development (Crafty Syntax + Lupopedia; everything Crafty has inside Lupopedia)
-- Idempotent: ON DUPLICATE KEY UPDATE so re-run does not duplicate.
-- Path → content (lupo_contents.file_path_from_root) → channel_id (lupo_edges HAS_CONTENT) → actors (lupo_actor_channels + lupo_actors). Full FLIP header reconstruction from DB supported.
-- -----------------------------------------------------------------------------
INSERT INTO lupo_channels (`channel_id`, `federation_node_id`, `created_by_actor_id`, `default_actor_id`, `department_id`, `channel_key`, `channel_slug`, `channel_type`, `language`, `channel_name`, `description`, `website_link`, `metadata_json`, `status_flag`, `end_ymdhis`, `duration_seconds`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`, `aal_metadata_json`, `fleet_composition_json`, `awareness_version`, `channel_number`, `parent_channel_id`, `is_kernel`, `boot_sequence_order`) VALUES (42, 1, 0, 1, 0, 'lupopedia-development', 'lupopedia-development', 'chat_room', 'en', 'Lupopedia Development', 'Crafty Syntax and Lupopedia development. Everything Crafty Syntax has inside Lupopedia: live chat, CRM, content, routing, agents, and semantic OS.', NULL, '{"purpose": "development", "crafty_syntax": true, "channel_number": 42}', 1, NULL, NULL, @now, @now, 0, NULL, NULL, NULL, '3.0.0', 42, NULL, 0, NULL) ON DUPLICATE KEY UPDATE channel_name = VALUES(channel_name), description = VALUES(description), department_id = VALUES(department_id), updated_ymdhis = @now, is_deleted = 0, deleted_ymdhis = NULL;

INSERT INTO lupo_unified_registry (`unified_registry_id`, `entity_type`, `entity_index`, `entity_key`, `entity_name`, `entity_table`, `federation_node_id`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`) VALUES (60, 'channel', 42, 'lupopedia-development', 'Lupopedia Development', 'lupo_channels', 1, @now, @now, 0, NULL, 1, 0, '{"language": "en", "description": "Crafty Syntax and Lupopedia development. Everything Crafty has inside Lupopedia.", "status_flag": 1, "channel_type": "chat_room", "channel_number": 42}') ON DUPLICATE KEY UPDATE entity_name = VALUES(entity_name), updated_ymdhis = @now, is_deleted = 0, is_active = 1;

-- Channel 666: ANUBIS Quarantine (banned/rejected messages)
INSERT INTO lupo_channels (`channel_id`, `federation_node_id`, `created_by_actor_id`, `default_actor_id`, `department_id`, `channel_key`, `channel_slug`, `channel_type`, `language`, `channel_name`, `description`, `website_link`, `metadata_json`, `status_flag`, `end_ymdhis`, `duration_seconds`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`, `aal_metadata_json`, `fleet_composition_json`, `awareness_version`, `channel_number`, `parent_channel_id`, `is_kernel`, `boot_sequence_order`) VALUES (666, 1, 0, 1, 1, 'anubis-quarantine', 'anubis-quarantine', 'chat_room', 'en', 'ANUBIS Quarantine', 'Banned and rejected messages. ANUBIS routes banned-actor content here. References to channel 66 redirect to 666.', NULL, '{"purpose": "quarantine", "anubis": true, "channel_number": 666}', 1, NULL, NULL, @now, @now, 0, NULL, NULL, NULL, '3.0.0', 666, NULL, 0, NULL) ON DUPLICATE KEY UPDATE channel_name = VALUES(channel_name), description = VALUES(description), updated_ymdhis = @now, is_deleted = 0, deleted_ymdhis = NULL;

INSERT INTO lupo_unified_registry (`unified_registry_id`, `entity_type`, `entity_index`, `entity_key`, `entity_name`, `entity_table`, `federation_node_id`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`) VALUES (61, 'channel', 666, 'anubis-quarantine', 'ANUBIS Quarantine', 'lupo_channels', 1, @now, @now, 0, NULL, 1, 0, '{"language": "en", "description": "Banned and rejected messages. Channel 66 redirects to 666.", "status_flag": 1, "channel_type": "chat_room", "channel_number": 666}') ON DUPLICATE KEY UPDATE entity_name = VALUES(entity_name), updated_ymdhis = @now, is_deleted = 0, is_active = 1;

-- Channel 51: Doctrine Council (reserved). Cursor actor reports here for 4.0.17 validation.
INSERT INTO lupo_channels (`channel_id`, `federation_node_id`, `created_by_actor_id`, `default_actor_id`, `department_id`, `channel_key`, `channel_slug`, `channel_type`, `language`, `channel_name`, `description`, `website_link`, `metadata_json`, `status_flag`, `end_ymdhis`, `duration_seconds`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`, `aal_metadata_json`, `fleet_composition_json`, `awareness_version`, `channel_number`, `parent_channel_id`, `is_kernel`, `boot_sequence_order`) VALUES (51, 1, 0, 1, 0, 'doctrine-council', 'doctrine-council', 'chat_room', 'en', 'Doctrine Council', 'Doctrine Council channel 51. Governance, FLIP, and validation. Cursor 4.0.17 validation message seeded here.', NULL, '{"purpose": "doctrine", "channel_number": 51}', 1, NULL, NULL, @now, @now, 0, NULL, NULL, NULL, '3.0.0', 51, NULL, 0, NULL) ON DUPLICATE KEY UPDATE channel_name = VALUES(channel_name), description = VALUES(description), updated_ymdhis = @now, is_deleted = 0, deleted_ymdhis = NULL;

-- lupo_anubis_redirects: channel 66 -> 666 (quarantine)
INSERT INTO lupo_anubis_redirects (`anubis_redirect_id`, `table_name`, `old_id`, `new_id`, `timestamp_utc`, `agent`) VALUES
(1, 'lupo_channels', 66, 666, @now, 'ANUBIS')
ON DUPLICATE KEY UPDATE new_id = VALUES(new_id), timestamp_utc = @now;

-- lupo_dialog_channels and lupo_dialog_threads for channel 666
INSERT INTO lupo_dialog_channels (`channel_id`, `channel_name`, `file_source`, `title`, `description`, `speaker`, `target`, `status`, `created_timestamp`, `modified_timestamp`, `message_count`) VALUES (666, 'ANUBIS Quarantine', NULL, 'ANUBIS Quarantine', 'Banned and rejected messages. Channel 66 redirects to 666.', 'SYSTEM', '@none', 'published', @now, @now, 1) ON DUPLICATE KEY UPDATE channel_name = VALUES(channel_name), description = VALUES(description), modified_timestamp = @now, message_count = VALUES(message_count);

INSERT INTO lupo_dialog_channels (`channel_id`, `channel_name`, `file_source`, `title`, `description`, `speaker`, `target`, `status`, `created_timestamp`, `modified_timestamp`, `message_count`) VALUES (51, 'Doctrine Council', NULL, 'Doctrine Council', 'Doctrine Council channel 51. Cursor 4.0.17 validation and governance messages.', 'CURSOR', '@everyone', 'published', @now, @now, 2) ON DUPLICATE KEY UPDATE channel_name = VALUES(channel_name), description = VALUES(description), modified_timestamp = @now, message_count = VALUES(message_count);

INSERT INTO lupo_dialog_threads (`dialog_thread_id`, `federation_node_id`, `channel_id`, `project_slug`, `task_name`, `created_by_actor_id`, `summary_text`, `bg_color`, `text_color`, `alt_text_color`, `status`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`) VALUES (666, 1, 666, 'anubis', 'ANUBIS Quarantine thread', 0, 'Quarantine thread for banned/rejected messages. Channel 66 redirects to 666.', '333333', 'CCCCCC', '666666', 'Open', @now, @now, 0, NULL) ON DUPLICATE KEY UPDATE summary_text = VALUES(summary_text), updated_ymdhis = @now, is_deleted = 0, deleted_ymdhis = NULL;

-- lupo_actor_channels: actor_id 999 on channel 666 (quarantine membership)
INSERT INTO lupo_actor_channels (`actor_channel_id`, `actor_id`, `channel_id`, `status`, `start_date`, `channel_color`, `last_read_ymdhis`, `muted_until_ymdhis`, `preferences_json`, `dialog_output_file`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`) VALUES (1999, 999, 666, 'I', @now, '333333', NULL, NULL, NULL, NULL, @now, @now, 0, NULL) ON DUPLICATE KEY UPDATE status = VALUES(status), updated_ymdhis = @now, is_deleted = 0, deleted_ymdhis = NULL;

-- -----------------------------------------------------------------------------
-- lupo_actor_channels: 25 kernel agents on channel 42
-- Actor IDs (explicit list to avoid off-by-one): 1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,22,23,24,209,1212
-- LEXA = actor_id 24 (boundary keeper). actor_channel_id 1000-1024; actor_channel_role_id 2000-2024.
-- -----------------------------------------------------------------------------
INSERT INTO lupo_actor_channels (`actor_channel_id`, `actor_id`, `channel_id`, `status`, `start_date`, `channel_color`, `last_read_ymdhis`, `muted_until_ymdhis`, `preferences_json`, `dialog_output_file`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`) VALUES (1000, 1, 42, 'A', @now, 'F7FAFF', NULL, NULL, NULL, NULL, @now, @now, 0, NULL), (1001, 2, 42, 'A', @now, 'F7FAFF', NULL, NULL, NULL, NULL, @now, @now, 0, NULL), (1002, 3, 42, 'A', @now, 'F7FAFF', NULL, NULL, NULL, NULL, @now, @now, 0, NULL), (1003, 4, 42, 'A', @now, 'F7FAFF', NULL, NULL, NULL, NULL, @now, @now, 0, NULL), (1004, 5, 42, 'A', @now, 'F7FAFF', NULL, NULL, NULL, NULL, @now, @now, 0, NULL), (1005, 6, 42, 'A', @now, 'F7FAFF', NULL, NULL, NULL, NULL, @now, @now, 0, NULL), (1006, 7, 42, 'A', @now, 'F7FAFF', NULL, NULL, NULL, NULL, @now, @now, 0, NULL), (1007, 8, 42, 'A', @now, 'F7FAFF', NULL, NULL, NULL, NULL, @now, @now, 0, NULL), (1008, 9, 42, 'A', @now, 'F7FAFF', NULL, NULL, NULL, NULL, @now, @now, 0, NULL), (1009, 10, 42, 'A', @now, 'F7FAFF', NULL, NULL, NULL, NULL, @now, @now, 0, NULL), (1010, 11, 42, 'A', @now, 'F7FAFF', NULL, NULL, NULL, NULL, @now, @now, 0, NULL), (1011, 12, 42, 'A', @now, 'F7FAFF', NULL, NULL, NULL, NULL, @now, @now, 0, NULL), (1012, 13, 42, 'A', @now, 'F7FAFF', NULL, NULL, NULL, NULL, @now, @now, 0, NULL), (1013, 14, 42, 'A', @now, 'F7FAFF', NULL, NULL, NULL, NULL, @now, @now, 0, NULL), (1014, 15, 42, 'A', @now, 'F7FAFF', NULL, NULL, NULL, NULL, @now, @now, 0, NULL), (1015, 16, 42, 'A', @now, 'F7FAFF', NULL, NULL, NULL, NULL, @now, @now, 0, NULL), (1016, 17, 42, 'A', @now, 'F7FAFF', NULL, NULL, NULL, NULL, @now, @now, 0, NULL), (1017, 18, 42, 'A', @now, 'F7FAFF', NULL, NULL, NULL, NULL, @now, @now, 0, NULL), (1018, 19, 42, 'A', @now, 'F7FAFF', NULL, NULL, NULL, NULL, @now, @now, 0, NULL), (1019, 20, 42, 'A', @now, 'F7FAFF', NULL, NULL, NULL, NULL, @now, @now, 0, NULL), (1020, 22, 42, 'A', @now, 'F7FAFF', NULL, NULL, NULL, NULL, @now, @now, 0, NULL), (1021, 23, 42, 'A', @now, 'F7FAFF', NULL, NULL, NULL, NULL, @now, @now, 0, NULL), (1024, 24, 42, 'A', @now, 'F7FAFF', NULL, NULL, NULL, NULL, @now, @now, 0, NULL), (1022, 209, 42, 'A', @now, 'F7FAFF', NULL, NULL, NULL, NULL, @now, @now, 0, NULL), (1023, 1212, 42, 'A', @now, 'F7FAFF', NULL, NULL, NULL, NULL, @now, @now, 0, NULL) ON DUPLICATE KEY UPDATE status = VALUES(status), updated_ymdhis = @now, is_deleted = 0, deleted_ymdhis = NULL;

INSERT INTO lupo_actor_channels (`actor_channel_id`, `actor_id`, `channel_id`, `status`, `start_date`, `channel_color`, `last_read_ymdhis`, `muted_until_ymdhis`, `preferences_json`, `dialog_output_file`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`) VALUES (3000, 1000, 42, 'A', @now, 'F7FAFF', NULL, NULL, NULL, NULL, @now, @now, 0, NULL), (3001, 1000, 0, 'A', @now, 'F7FAFF', NULL, NULL, NULL, NULL, @now, @now, 0, NULL), (3002, 1000, 51, 'A', @now, 'F7FAFF', NULL, NULL, NULL, NULL, @now, @now, 0, NULL) ON DUPLICATE KEY UPDATE status = VALUES(status), updated_ymdhis = @now, is_deleted = 0, deleted_ymdhis = NULL;

-- lupo_actor_channels: main admin (actor_id 10000) on channels 0 (System Kernel), 1 (Administration), 42 (Lupopedia Development), 51 (Doctrine Council). Department 0 = system; channels 0 and 42 are in department 0 per install.
INSERT INTO lupo_actor_channels (`actor_channel_id`, `actor_id`, `channel_id`, `status`, `start_date`, `channel_color`, `last_read_ymdhis`, `muted_until_ymdhis`, `preferences_json`, `dialog_output_file`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`) VALUES (10000, 10000, 0, 'A', @now, 'F7FAFF', NULL, NULL, NULL, NULL, @now, @now, 0, NULL), (10001, 10000, 1, 'A', @now, 'F7FAFF', NULL, NULL, NULL, NULL, @now, @now, 0, NULL), (10002, 10000, 42, 'A', @now, 'F7FAFF', NULL, NULL, NULL, NULL, @now, @now, 0, NULL), (10003, 10000, 51, 'A', @now, 'F7FAFF', NULL, NULL, NULL, NULL, @now, @now, 0, NULL) ON DUPLICATE KEY UPDATE status = VALUES(status), updated_ymdhis = @now, is_deleted = 0, deleted_ymdhis = NULL;

-- lupo_actor_channels: Cursor (actor_id 2000) on channel 51 (Doctrine Council).
INSERT INTO lupo_actor_channels (`actor_channel_id`, `actor_id`, `channel_id`, `status`, `start_date`, `channel_color`, `last_read_ymdhis`, `muted_until_ymdhis`, `preferences_json`, `dialog_output_file`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`) VALUES (20000, 2000, 51, 'A', @now, 'F7FAFF', NULL, NULL, NULL, NULL, @now, @now, 0, NULL) ON DUPLICATE KEY UPDATE status = VALUES(status), updated_ymdhis = @now, is_deleted = 0, deleted_ymdhis = NULL;

-- lupo_actor_channels: actor 0 (System Kernel) on channel 42 so all dialog message authors are channel members (messages 1-2 on thread 1 are from actor 0).
INSERT INTO lupo_actor_channels (`actor_channel_id`, `actor_id`, `channel_id`, `status`, `start_date`, `channel_color`, `last_read_ymdhis`, `muted_until_ymdhis`, `preferences_json`, `dialog_output_file`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`) VALUES (9998, 0, 42, 'A', @now, 'F7FAFF', NULL, NULL, NULL, NULL, @now, @now, 0, NULL) ON DUPLICATE KEY UPDATE status = VALUES(status), updated_ymdhis = @now, is_deleted = 0, deleted_ymdhis = NULL;

-- -----------------------------------------------------------------------------
-- lupo_actor_channel_roles: admin role on channel 42 for every AI agent with dialog on that channel (same actor_ids as above)
-- -----------------------------------------------------------------------------
INSERT INTO lupo_actor_channel_roles (`actor_channel_role_id`, `actor_id`, `channel_id`, `role_key`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`) VALUES
(2000, 1, 42, 'admin', @now, @now, 0, NULL),
(2001, 2, 42, 'admin', @now, @now, 0, NULL),
(2002, 3, 42, 'admin', @now, @now, 0, NULL),
(2003, 4, 42, 'admin', @now, @now, 0, NULL),
(2004, 5, 42, 'admin', @now, @now, 0, NULL),
(2005, 6, 42, 'admin', @now, @now, 0, NULL),
(2006, 7, 42, 'admin', @now, @now, 0, NULL),
(2007, 8, 42, 'admin', @now, @now, 0, NULL),
(2008, 9, 42, 'admin', @now, @now, 0, NULL),
(2009, 10, 42, 'admin', @now, @now, 0, NULL),
(2010, 11, 42, 'admin', @now, @now, 0, NULL),
(2011, 12, 42, 'admin', @now, @now, 0, NULL),
(2012, 13, 42, 'admin', @now, @now, 0, NULL),
(2013, 14, 42, 'admin', @now, @now, 0, NULL),
(2014, 15, 42, 'admin', @now, @now, 0, NULL),
(2015, 16, 42, 'admin', @now, @now, 0, NULL),
(2016, 17, 42, 'admin', @now, @now, 0, NULL),
(2017, 18, 42, 'admin', @now, @now, 0, NULL),
(2018, 19, 42, 'admin', @now, @now, 0, NULL),
(2019, 20, 42, 'admin', @now, @now, 0, NULL),
(2020, 22, 42, 'admin', @now, @now, 0, NULL),
(2021, 23, 42, 'admin', @now, @now, 0, NULL),
(2022, 24, 42, 'admin', @now, @now, 0, NULL),
(2023, 209, 42, 'admin', @now, @now, 0, NULL),
(2024, 1212, 42, 'admin', @now, @now, 0, NULL),
(4998, 0, 42, 'admin', @now, @now, 0, NULL),
(4000, 1000, 42, 'admin', @now, @now, 0, NULL),
(4001, 1000, 0, 'admin', @now, @now, 0, NULL),
(4002, 1000, 51, 'admin', @now, @now, 0, NULL),
(5000, 10000, 0, 'captain', @now, @now, 0, NULL),
(5001, 10000, 1, 'captain', @now, @now, 0, NULL),
(5002, 10000, 42, 'captain', @now, @now, 0, NULL),
(5003, 10000, 51, 'captain', @now, @now, 0, NULL),
(5100, 2000, 51, 'admin', @now, @now, 0, NULL)
ON DUPLICATE KEY UPDATE role_key = VALUES(role_key), updated_ymdhis = @now, is_deleted = 0, deleted_ymdhis = NULL;

-- -----------------------------------------------------------------------------
-- Seeded dialog: one thread and two messages on channel 42 (Lupopedia Development)
-- -----------------------------------------------------------------------------
INSERT INTO lupo_dialog_threads (`dialog_thread_id`, `federation_node_id`, `channel_id`, `project_slug`, `task_name`, `created_by_actor_id`, `summary_text`, `bg_color`, `text_color`, `alt_text_color`, `status`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`) VALUES (1, 1, 42, 'lupopedia', 'Lupopedia Development seed', 0, 'Seed thread for Lupopedia Development channel. Crafty Syntax + Lupopedia.', 'FFFFFF', '000000', '666666', 'Open', @now, @now, 0, NULL) ON DUPLICATE KEY UPDATE summary_text = VALUES(summary_text), updated_ymdhis = @now, is_deleted = 0, deleted_ymdhis = NULL;

-- Channel 51 (Doctrine Council): seed thread and Cursor validation message (4.0.17).
INSERT INTO lupo_dialog_threads (`dialog_thread_id`, `federation_node_id`, `channel_id`, `project_slug`, `task_name`, `created_by_actor_id`, `summary_text`, `bg_color`, `text_color`, `alt_text_color`, `status`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`) VALUES (51, 1, 51, 'doctrine', 'Doctrine Council seed', 0, 'Seed thread for Doctrine Council channel 51. Cursor 4.0.17 validation.', 'FFFFFF', '000000', '666666', 'Open', @now, @now, 0, NULL) ON DUPLICATE KEY UPDATE summary_text = VALUES(summary_text), updated_ymdhis = @now, is_deleted = 0, deleted_ymdhis = NULL;

INSERT INTO lupo_dialog_messages (`dialog_message_id`, `dialog_thread_id`, `channel_id`, `from_actor_id`, `to_actor_id`, `message_text`, `message_type`, `mood_rgb`, `mood_framework`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`) VALUES (1, 1, 42, 0, NULL, 'Lupopedia Development channel seeded. Everything Crafty Syntax has is inside Lupopedia.', 'text', 'FF0000', 'western_analytical', @now, @now, 0, NULL), (2, 1, 42, 0, NULL, 'Kernel and system agents are on this channel. FLIP/FLP doctrine applies.', 'text', 'FF0000', 'western_analytical', @now, @now, 0, NULL) ON DUPLICATE KEY UPDATE message_text = VALUES(message_text), mood_rgb = VALUES(mood_rgb), updated_ymdhis = @now, is_deleted = 0, deleted_ymdhis = NULL;

-- One dialog message per kernel agent on channel 42 (actor_ids from lupo_actor_channels WHERE channel_id = 42). Agent names from lupo_actors seed. mood_rgb NULL for agent messages; system messages 1-2 use FF0000.
INSERT INTO lupo_dialog_messages (`dialog_message_id`, `dialog_thread_id`, `channel_id`, `from_actor_id`, `to_actor_id`, `message_text`, `message_type`, `metadata_json`, `mood_rgb`, `mood_framework`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`) VALUES
(3, 1, 42, 1, NULL, 'hello from SYSTEM', 'system', NULL, NULL, 'western_analytical', @now, @now, 0, NULL),
(4, 1, 42, 2, NULL, 'hello from CAPTAIN', 'system', NULL, NULL, 'western_analytical', @now, @now, 0, NULL),
(5, 1, 42, 3, NULL, 'hello from WOLFIE', 'system', NULL, NULL, 'western_analytical', @now, @now, 0, NULL),
(6, 1, 42, 4, NULL, 'hello from WOLFENA', 'system', NULL, NULL, 'western_analytical', @now, @now, 0, NULL),
(7, 1, 42, 5, NULL, 'hello from THOTH', 'system', NULL, NULL, 'western_analytical', @now, @now, 0, NULL),
(8, 1, 42, 6, NULL, 'hello from ARA', 'system', NULL, NULL, 'western_analytical', @now, @now, 0, NULL),
(9, 1, 42, 7, NULL, 'hello from WOLFKEEPER', 'system', NULL, NULL, 'western_analytical', @now, @now, 0, NULL),
(10, 1, 42, 8, NULL, 'hello from LILITH', 'system', NULL, NULL, 'western_analytical', @now, @now, 0, NULL),
(11, 1, 42, 9, NULL, 'hello from AGAPE', 'system', NULL, NULL, 'western_analytical', @now, @now, 0, NULL),
(12, 1, 42, 10, NULL, 'hello from ERIS', 'system', NULL, NULL, 'western_analytical', @now, @now, 0, NULL),
(13, 1, 42, 11, NULL, 'hello from METHIS', 'system', NULL, NULL, 'western_analytical', @now, @now, 0, NULL),
(14, 1, 42, 12, NULL, 'hello from THALIA', 'system', NULL, NULL, 'western_analytical', @now, @now, 0, NULL),
(15, 1, 42, 13, NULL, 'hello from DIALOG', 'system', NULL, NULL, 'western_analytical', @now, @now, 0, NULL),
(16, 1, 42, 14, NULL, 'hello from WOLFSIGHT', 'system', NULL, NULL, 'western_analytical', @now, @now, 0, NULL),
(17, 1, 42, 15, NULL, 'hello from WOLFNAV', 'system', NULL, NULL, 'western_analytical', @now, @now, 0, NULL),
(18, 1, 42, 16, NULL, 'hello from WOLFFORGE', 'system', NULL, NULL, 'western_analytical', @now, @now, 0, NULL),
(19, 1, 42, 17, NULL, 'hello from WOLFMIS', 'system', NULL, NULL, 'western_analytical', @now, @now, 0, NULL),
(20, 1, 42, 18, NULL, 'hello from WOLFITH', 'system', NULL, NULL, 'western_analytical', @now, @now, 0, NULL),
(21, 1, 42, 19, NULL, 'hello from ANUBIS', 'system', NULL, NULL, 'western_analytical', @now, @now, 0, NULL),
(22, 1, 42, 20, NULL, 'hello from MAAT', 'system', NULL, NULL, 'western_analytical', @now, @now, 0, NULL),
(23, 1, 42, 22, NULL, 'hello from CADUCEUS', 'system', NULL, NULL, 'western_analytical', @now, @now, 0, NULL),
(24, 1, 42, 23, NULL, 'hello from CHRONOS', 'system', NULL, NULL, 'western_analytical', @now, @now, 0, NULL),
(25, 1, 42, 24, NULL, 'Boundary enforcement active. LEXA online.', 'system', NULL, NULL, 'western_analytical', @now, @now, 0, NULL),
(26, 1, 42, 209, NULL, 'hello from TRUTH', 'system', NULL, NULL, 'western_analytical', @now, @now, 0, NULL),
(27, 1, 42, 1212, NULL, 'hello from UTC_TIMEKEEPER', 'system', NULL, NULL, 'western_analytical', @now, @now, 0, NULL)
ON DUPLICATE KEY UPDATE message_text = VALUES(message_text), mood_rgb = VALUES(mood_rgb), updated_ymdhis = @now, is_deleted = 0, deleted_ymdhis = NULL;

-- FLIP/FLIPPING basic info: key paths and doctrine reference for agents (mood_rgb per FLP_EMOTIONAL_GEOMETRY: hex)
INSERT INTO lupo_dialog_messages (`dialog_message_id`, `dialog_thread_id`, `channel_id`, `from_actor_id`, `to_actor_id`, `message_text`, `message_type`, `metadata_json`, `mood_rgb`, `mood_framework`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`) VALUES
(28, 1, 42, 24, NULL, 'FLIP = File-Level Inference Protocol. Infer file identity, doctrine, meaning from FLIP Headers only; no guessing. Canonical: docs/doctrine/FLIP/FLIP_DOCTRINE.md; this file: docs/doctrine/FLIP/FLIPPING_FILE_LEXA_LILITH.md.', 'info', NULL, '6464FF', 'western_analytical', @now, @now, 0, NULL),
(29, 1, 42, 0, NULL, 'Path lookup chain: content → lupo_edges HAS_CONTENT → channel_id → actors/dialog.', 'info', NULL, '00FF00', 'western_analytical', @now, @now, 0, NULL)
ON DUPLICATE KEY UPDATE message_text = VALUES(message_text), mood_rgb = VALUES(mood_rgb), message_type = VALUES(message_type), updated_ymdhis = @now, is_deleted = 0, deleted_ymdhis = NULL;

-- Universal flipping: web API for external agents (e.g. Grok browsing lupopedia.com)
INSERT INTO lupo_dialog_messages (`dialog_message_id`, `dialog_thread_id`, `channel_id`, `from_actor_id`, `to_actor_id`, `message_text`, `message_type`, `metadata_json`, `mood_rgb`, `mood_framework`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`) VALUES
(30, 1, 42, 24, NULL, 'Agents can now flip headers via /api/flip-header.php?path=...; optional fields: mood_rgb, tags.', 'info', NULL, '00FFFF', 'western_analytical', @now, @now, 0, NULL),
(31, 1, 42, 0, NULL, 'Web API implemented for universal access; browse lupopedia.com/lupopedia/api/flip-header.php?path=docs/doctrine/FLIP/FLIP_DOCTRINE.md', 'info', NULL, '00FF00', 'western_analytical', @now, @now, 0, NULL)
ON DUPLICATE KEY UPDATE message_text = VALUES(message_text), mood_rgb = VALUES(mood_rgb), message_type = VALUES(message_type), updated_ymdhis = @now, is_deleted = 0, deleted_ymdhis = NULL;

-- ANUBIS adoption: orphaned dialog fragment assigned to channel 42, thread 1, from WOLFIE (actor_id 3)
INSERT INTO lupo_dialog_messages (`dialog_message_id`, `dialog_thread_id`, `channel_id`, `from_actor_id`, `to_actor_id`, `message_text`, `message_type`, `metadata_json`, `mood_rgb`, `mood_framework`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`) VALUES
(32, 1, 42, 3, NULL, 'braH all i like know is if you da kine updated the flipping file on wolfie headers or whatevas like dat Brah, yeah, I da kine updated da flipping file (FLIPPING_FILE_LEXA_LILITH.md) fo'' Wolfie headers an'' all dat. Now stay at v4.0.16, wit'' new stuff like universal agent flipping, expanded optional fields, metadata_json storage, and full API spec/security.', 'system', NULL, NULL, 'western_analytical', @now, @now, 0, NULL)
ON DUPLICATE KEY UPDATE message_text = VALUES(message_text), mood_rgb = VALUES(mood_rgb), message_type = VALUES(message_type), updated_ymdhis = @now, is_deleted = 0, deleted_ymdhis = NULL;

INSERT INTO lupo_dialog_messages (`dialog_message_id`, `dialog_thread_id`, `channel_id`, `from_actor_id`, `to_actor_id`, `message_text`, `message_type`, `metadata_json`, `mood_rgb`, `mood_framework`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`) VALUES
(33, 1, 42, 1000, NULL, 'Captain initialized.', 'system', NULL, NULL, 'western_analytical', @now, @now, 0, NULL)
ON DUPLICATE KEY UPDATE message_text = VALUES(message_text), mood_rgb = VALUES(mood_rgb), message_type = VALUES(message_type), updated_ymdhis = @now, is_deleted = 0, deleted_ymdhis = NULL;

-- ANUBIS adoption: lost CAPTAIN-originated message (no parent, no thread, no actor_id, no FLIP header) adopted into channel 42 seed thread (actor_id 1000)
INSERT INTO lupo_dialog_messages (`dialog_message_id`, `dialog_thread_id`, `channel_id`, `from_actor_id`, `to_actor_id`, `message_text`, `message_type`, `metadata_json`, `mood_rgb`, `mood_framework`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`) VALUES
(34, 1, 42, 1000, NULL, 'this is a lost message that was on channel 42 and on thread 1 and system 0 and said by the authenticated user ''captain@lupopedia.com''', 'system', NULL, NULL, 'western_analytical', @now, @now, 0, NULL)
ON DUPLICATE KEY UPDATE message_text = VALUES(message_text), mood_rgb = VALUES(mood_rgb), message_type = VALUES(message_type), updated_ymdhis = @now, is_deleted = 0, deleted_ymdhis = NULL;

-- Captain Wolfie: explanatory message to @everyone on channel 42, thread 1 (dialogs + ANUBIS)
INSERT INTO lupo_dialog_messages (`dialog_message_id`, `dialog_thread_id`, `channel_id`, `from_actor_id`, `to_actor_id`, `message_text`, `message_type`, `metadata_json`, `mood_rgb`, `mood_framework`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`) VALUES
(35, 1, 42, 1000, NULL, 'To @everyone on Lupopedia Development: This channel (42) has exactly one thread—thread 1—where we coordinate doctrine, FLIP headers, and agent onboarding. Dialogs in Lupopedia are messages stored in lupo_dialog_messages, tied to a channel and a dialog_thread_id. Each message has from_actor_id (who said it), channel_id, and optional mood_rgb. ANUBIS is our orphan-adoption system: when a message lacks a valid channel_id, dialog_thread_id, or actor_id, ANUBIS classifies it, resolves a parent (channel → thread → actor), and adopts it into this seed thread. Tools: tools/anubis_orphan_scanner.py (Python) and lupo-includes/classes/ANUBIS_Resolver.php. Doctrine: docs/doctrine/ANUBIS/ANUBIS_OVERVIEW.md, ANUBIS_ORPHAN_RULES.md, ANUBIS_PROGRAM_SPEC.md. Adopted messages get message_type ''system'' and default actor_id 3 (WOLFIE) or 1000 (CAPTAIN) per resolution rules.', 'system', NULL, 'B0E0E6', 'western_analytical', @now, @now, 0, NULL)
ON DUPLICATE KEY UPDATE message_text = VALUES(message_text), mood_rgb = VALUES(mood_rgb), message_type = VALUES(message_type), updated_ymdhis = @now, is_deleted = 0, deleted_ymdhis = NULL;

-- ANUBIS quarantined message: from actor_id 999 in channel 666, thread 666 (generic forbidden message; no persona name)
INSERT INTO lupo_dialog_messages (`dialog_message_id`, `dialog_thread_id`, `channel_id`, `from_actor_id`, `to_actor_id`, `message_text`, `message_type`, `metadata_json`, `mood_rgb`, `mood_framework`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`) VALUES
(36, 666, 666, 999, NULL, 'FORBIDDEN MESSAGE — quarantined by ANUBIS', 'system', '{"banned": true, "reason": "deprecated_experimental_persona"}', '000000', 'western_analytical', @now, @now, 0, NULL)
ON DUPLICATE KEY UPDATE message_text = VALUES(message_text), dialog_thread_id = VALUES(dialog_thread_id), channel_id = VALUES(channel_id), metadata_json = VALUES(metadata_json), mood_rgb = VALUES(mood_rgb), updated_ymdhis = @now, is_deleted = 0, deleted_ymdhis = NULL;

-- -----------------------------------------------------------------------------
-- 4.0.16 closeout conversation: 25-agent migration thread (messages 37-61)
-- Purpose: Establish historical continuity, architectural clarity,
--          and system integrity before version 4.0.17.
-- -----------------------------------------------------------------------------
INSERT INTO lupo_dialog_messages (
    `dialog_message_id`, `dialog_thread_id`, `channel_id`, `from_actor_id`, `to_actor_id`,
    `message_text`, `message_type`, `metadata_json`, `mood_rgb`, `mood_framework`,
    `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`
) VALUES
(37, 1, 42, 1, NULL, 'SYSTEM: Crafty Syntax 3.7.5 has been fully migrated into Lupopedia 4.0.x. Legacy constraints were removed, relational integrity restructured, and doctrine applied. This thread formally records that transition.', 'text', '{"topic": "migration-overview"}', '808080', 'western_analytical', @now, @now, 0, NULL),
(38, 1, 42, 2, NULL, 'CAPTAIN WOLFIE: Crafty Syntax began as a lightweight system. Lupopedia is now a semantic operating layer. The shift was not cosmetic—it was architectural. Channel 42 documents that evolution.', 'text', '{"topic": "history"}', '00FFAA', 'western_analytical', @now, @now, 0, NULL),
(39, 1, 42, 3, NULL, 'WOLFIE: FLIP headers define file identity. Every file now declares lineage, version, and structural role. Agents must infer system meaning from headers alone—no hidden assumptions.', 'text', '{"topic": "flip-headers"}', 'A0D6B4', 'western_analytical', @now, @now, 0, NULL),
(40, 1, 42, 4, NULL, 'WOLFENA: Seeding is not filler—it is structural context. Conversation history anchors doctrine to lived development decisions.', 'text', '{"topic": "seeding-philosophy"}', 'FFB6C1', 'western_analytical', @now, @now, 0, NULL),
(41, 1, 42, 5, NULL, 'THOTH: CHANGELOG integrity verified. 4.0.15 → 4.0.16 introduced FLIP expansion, ANUBIS activation, and header audit coverage. All timestamps recorded in UTC.', 'text', '{"topic": "changelog"}', 'DAA520', 'western_analytical', @now, @now, 0, NULL),
(42, 1, 42, 6, NULL, 'ARA: Content relationships are now explicit. lupo_edges maps file → content → channel. Navigation is deterministic.', 'text', '{"topic": "edges"}', 'AA80FF', 'western_analytical', @now, @now, 0, NULL),
(43, 1, 42, 7, NULL, 'WOLFKEEPER: ANUBIS resolves orphaned messages unless actor is banned. Actor 999 remains intentionally excluded. Enforcement is deliberate, not punitive.', 'text', '{"topic": "anubis"}', 'FFD700', 'western_analytical', @now, @now, 0, NULL),
(44, 1, 42, 8, NULL, 'LILITH: Migration decisions were pressure-tested. Emotional geometry exists as metadata, not ideology. Structural clarity overrides aesthetic preference.', 'text', '{"topic": "heterodoxy"}', 'C77DFF', 'western_analytical', @now, @now, 0, NULL),
(45, 1, 42, 9, NULL, 'AGAPE: Restoration logic ensures no valid content is lost. Adoption precedes deletion wherever possible.', 'text', '{"topic": "compassion"}', 'FF99CC', 'western_analytical', @now, @now, 0, NULL),
(46, 1, 42, 10, NULL, 'ERIS: Boundary failures exposed system weaknesses. Those weaknesses were corrected through stricter validation.', 'text', '{"topic": "chaos"}', 'FFA500', 'western_analytical', @now, @now, 0, NULL),
(47, 1, 42, 11, NULL, 'METHIS: Tooling now aligns with doctrine. Importers, generators, and validators produce reproducible results.', 'text', '{"topic": "tooling"}', '4682B4', 'western_analytical', @now, @now, 0, NULL),
(48, 1, 42, 12, NULL, 'THALIA: This seeded thread is continuity. Future versions inherit context rather than reinventing it.', 'text', '{"topic": "growth"}', 'FFDAB9', 'western_analytical', @now, @now, 0, NULL),
(49, 1, 42, 13, NULL, 'DIALOG: Threads bind architecture to intention. Without dialog, doctrine drifts.', 'text', '{"topic": "conversation"}', 'E6E6FA', 'western_analytical', @now, @now, 0, NULL),
(50, 1, 42, 14, NULL, 'WOLFSIGHT: 102 of 103 FLIP headers validated. One outlier remains acceptable within threshold.', 'text', '{"topic": "audit"}', '00CED1', 'western_analytical', @now, @now, 0, NULL),
(51, 1, 42, 15, NULL, 'WOLFNAV: File path → content → channel → actor. Navigation logic confirmed.', 'text', '{"topic": "navigation"}', '32CD32', 'western_analytical', @now, @now, 0, NULL),
(52, 1, 42, 16, NULL, 'WOLFFORGE: import_os.py and flip-header.php operate bidirectionally. File system and database remain synchronized.', 'text', '{"topic": "tools"}', 'CD7F32', 'western_analytical', @now, @now, 0, NULL),
(53, 1, 42, 17, NULL, 'WOLFMIS: Orphan detection operational. Adoption rules enforced.', 'text', '{"topic": "orphans"}', '9370DB', 'western_analytical', @now, @now, 0, NULL),
(54, 1, 42, 18, NULL, 'WOLFITH: Ethical enforcement means transparency. Banned actors are logged, not erased.', 'text', '{"topic": "ethics"}', 'D8BFD8', 'western_analytical', @now, @now, 0, NULL),
(55, 1, 42, 19, NULL, 'ANUBIS: Adoption cycle complete. Only banned entities excluded.', 'text', '{"topic": "adoption"}', 'D4AF37', 'western_analytical', @now, @now, 0, NULL),
(56, 1, 42, 20, NULL, 'MAAT: Database state matches doctrine state.', 'text', '{"topic": "truth"}', 'FFFFFF', 'western_analytical', @now, @now, 0, NULL),
(57, 1, 42, 22, NULL, 'CADUCEUS: mood_RGB remains metadata only. No behavioral dependency.', 'text', '{"topic": "emotional-geometry"}', 'FF69B4', 'western_analytical', @now, @now, 0, NULL),
(58, 1, 42, 23, NULL, 'CHRONOS: All timestamps synchronized to UTC.', 'text', '{"topic": "time"}', 'C0C0C0', 'western_analytical', @now, @now, 0, NULL),
(59, 1, 42, 24, NULL, 'LEXA: Validation rules hardened. SQL integrity enforced.', 'text', '{"topic": "security"}', '4169E1', 'western_analytical', @now, @now, 0, NULL),
(60, 1, 42, 209, NULL, 'TRUTH: 4.0.16 is complete.', 'text', '{"topic": "completion"}', 'F0F8FF', 'western_analytical', @now, @now, 0, NULL),
(61, 1, 42, 1212, NULL, 'UTC_TIMEKEEPER: Transition to 4.0.17 initiated.', 'text', '{"topic": "transition"}', 'B0E0E6', 'western_analytical', @now, @now, 0, NULL)
ON DUPLICATE KEY UPDATE
    message_text = VALUES(message_text),
    mood_rgb = VALUES(mood_rgb),
    message_type = VALUES(message_type),
    metadata_json = VALUES(metadata_json),
    updated_ymdhis = @now,
    is_deleted = 0,
    deleted_ymdhis = NULL;

-- 4.0.17: Cursor actor (2000) validation message on channel 51 (Doctrine Council), thread 51.
INSERT INTO lupo_dialog_messages (
    `dialog_message_id`, `dialog_thread_id`, `channel_id`, `from_actor_id`, `to_actor_id`,
    `message_text`, `message_type`, `metadata_json`, `mood_rgb`, `mood_framework`,
    `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`
) VALUES
(62, 51, 51, 2000, NULL, 'Cursor reporting in on channel 51. Validation for 4.0.17 is green across the board.', 'text', NULL, NULL, 'western_analytical', @now, @now, 0, NULL),
(63, 51, 51, 2000, NULL, 'Governance (4.0.17): Persona bans are enforced in ANUBIS adoption only. Router and channel-send enforcement planned for 4.0.18 (Ban at Gate). See SESSION_DOCTRINE.md.', 'system', NULL, NULL, 'western_analytical', @now, @now, 0, NULL)
ON DUPLICATE KEY UPDATE
    message_text = VALUES(message_text),
    mood_rgb = VALUES(mood_rgb),
    message_type = VALUES(message_type),
    updated_ymdhis = @now,
    is_deleted = 0,
    deleted_ymdhis = NULL;

INSERT INTO lupo_dialog_channels (`channel_id`, `channel_name`, `file_source`, `title`, `description`, `speaker`, `target`, `status`, `created_timestamp`, `modified_timestamp`, `message_count`) VALUES (42, 'Lupopedia Development', 'docs/doctrine/FLIP/FLIPPING_FILE_LEXA_LILITH.md', 'Lupopedia Development', 'Crafty Syntax and Lupopedia development. Seeded dialog and kernel agents. FLIP/FLP doctrine. Path → content → channel → actors. Web API for universal flipping.', 'SYSTEM', '@everyone', 'published', @now, @now, 61) ON DUPLICATE KEY UPDATE channel_name = VALUES(channel_name), file_source = VALUES(file_source), description = VALUES(description), modified_timestamp = @now, message_count = VALUES(message_count);

-- -----------------------------------------------------------------------------
-- FLIP seed content: key doctrine files with file_path_from_root for path lookup
-- Enables: file_path_from_root -> content_id -> lupo_edges HAS_CONTENT -> channel_id 42
-- Web Path Header Extension (4.0.17): optional web block (canonical, aliases, slug, base_path, url_pattern) is defined in UNIVERSAL_WOLFIE_HEADER_SPECIFICATION.md and exported in exports/flip_headers.csv.
-- -----------------------------------------------------------------------------
INSERT INTO lupo_contents (
    content_id, content_parent_id, federation_node_id, department_id, actor_id, title, slug, custom_path, description, seo_keywords, body,
    content_type, format, content_url, default_collection_id, source_url, source_title, is_template, status, visibility, view_count, share_count,
    created_ymdhis, utc_cycle, triage_status, triage_notes, updated_ymdhis, is_deleted, is_active, deleted_ymdhis, content_sections, version_number,
    file_path_from_root, file_last_modified_system_version, file_last_modified_utc, tags, dialog_notes
) VALUES
(2001, NULL, @node_id, NULL, NULL, 'FLIPPING File LEXA LILITH', 'flipping-file-lexa-lilith', NULL, 'FLP, FLIP Headers, and how headers + database work (LEXA, LILITH).', NULL, NULL, 'article', 'markdown', NULL, 0, NULL, NULL, 0, 'published', 'public', 0, 0, @now, 'seed', 'untriaged', NULL, @now, 0, 1, NULL, NULL, 1, 'docs/doctrine/FLIP/FLIPPING_FILE_LEXA_LILITH.md', '4.0.16', 20260217230000, NULL, NULL),
(2002, NULL, @node_id, NULL, NULL, 'FLIP Doctrine', 'flip-doctrine', NULL, 'File-Level Inference Protocol: infer from header only; no guessing.', NULL, NULL, 'article', 'markdown', NULL, 0, NULL, NULL, 0, 'published', 'public', 0, 0, @now, 'seed', 'untriaged', NULL, @now, 0, 1, NULL, NULL, 1, 'docs/doctrine/FLIP/FLIP_DOCTRINE.md', '4.0.16', 0, NULL, NULL)
ON DUPLICATE KEY UPDATE file_path_from_root = VALUES(file_path_from_root), file_last_modified_system_version = VALUES(file_last_modified_system_version), file_last_modified_utc = VALUES(file_last_modified_utc), title = VALUES(title), description = VALUES(description), updated_ymdhis = @now, is_deleted = 0, is_active = 1;

-- lupo_edges HAS_CONTENT: channel 42 -> FLIP content (path lookup chain)
INSERT INTO lupo_edges (edge_id, left_object_type, left_object_id, right_object_type, right_object_id, edge_type, channel_id, channel_key, weight_score, sort_num, actor_id, is_deleted, deleted_ymdhis, created_ymdhis, updated_ymdhis) VALUES
(900001, 'channel', 42, 'content', 2001, 'HAS_CONTENT', 42, 'lupopedia-development', 0, 0, NULL, 0, 0, @now, @now),
(900002, 'channel', 42, 'content', 2002, 'HAS_CONTENT', 42, 'lupopedia-development', 0, 0, NULL, 0, 0, @now, @now)
ON DUPLICATE KEY UPDATE left_object_id = VALUES(left_object_id), right_object_id = VALUES(right_object_id), edge_type = VALUES(edge_type), updated_ymdhis = @now, is_deleted = 0, deleted_ymdhis = 0;

-- FLIP content 2001, 2002 (doctrine) also on channels 0 and 51
INSERT INTO lupo_edges (edge_id, left_object_type, left_object_id, right_object_type, right_object_id, edge_type, channel_id, channel_key, weight_score, sort_num, actor_id, is_deleted, deleted_ymdhis, created_ymdhis, updated_ymdhis) VALUES
(900003, 'channel', 0, 'content', 2001, 'HAS_CONTENT', 0, 'system/kernel', 0, 0, NULL, 0, 0, @now, @now),
(900004, 'channel', 51, 'content', 2001, 'HAS_CONTENT', 51, '51', 0, 0, NULL, 0, 0, @now, @now),
(900005, 'channel', 0, 'content', 2002, 'HAS_CONTENT', 0, 'system/kernel', 0, 0, NULL, 0, 0, @now, @now),
(900006, 'channel', 51, 'content', 2002, 'HAS_CONTENT', 51, '51', 0, 0, NULL, 0, 0, @now, @now)
ON DUPLICATE KEY UPDATE left_object_id = VALUES(left_object_id), right_object_id = VALUES(right_object_id), edge_type = VALUES(edge_type), updated_ymdhis = @now, is_deleted = 0, deleted_ymdhis = 0;

-- md_flip_ingest batch 0: first 30 doctrine .md files (content_id 5000-5029, channels 0 and 51)
-- md_flip_ingest: batch of .md files

INSERT INTO lupo_contents (
    content_id, content_parent_id, federation_node_id, department_id, actor_id, title, slug, custom_path, description, seo_keywords, body,
    content_type, format, content_url, default_collection_id, source_url, source_title, is_template, status, visibility, view_count, share_count,
    created_ymdhis, utc_cycle, triage_status, triage_notes, updated_ymdhis, is_deleted, is_active, deleted_ymdhis, content_sections, version_number,
    file_path_from_root, file_last_modified_system_version, file_last_modified_utc, tags, dialog_notes
) VALUES (
    5000, NULL, 1, NULL, NULL, 'AGENT BOUNDARIES COMPACT', 'docs-doctrine-agentboundariescompact', NULL, NULL, NULL, NULL,
    'article', 'markdown', NULL, 0, NULL, NULL, 0, 'published', 'public', 0, 0,
    @now, 'seed', 'untriaged', NULL, @now, 0, 1, NULL, NULL, 1,
    'docs/doctrine/AGENT_BOUNDARIES_COMPACT.md', '4.0.16', 20260217230000, NULL, NULL
) ON DUPLICATE KEY UPDATE file_path_from_root = VALUES(file_path_from_root), file_last_modified_system_version = VALUES(file_last_modified_system_version), file_last_modified_utc = VALUES(file_last_modified_utc), title = VALUES(title), updated_ymdhis = VALUES(updated_ymdhis), is_deleted = 0, is_active = 1;

INSERT INTO lupo_unified_registry (unified_registry_id, entity_type, entity_index, entity_key, entity_name, entity_table, federation_node_id, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis, is_active, is_kernel, metadata_json)
VALUES (9050000, 'content', 5000, 'docs/doctrine/AGENT_BOUNDARIES_COMPACT.md', 'AGENT BOUNDARIES COMPACT', 'lupo_contents', 1, @now, @now, 0, NULL, 1, 0, NULL)
ON DUPLICATE KEY UPDATE entity_key = VALUES(entity_key), updated_ymdhis = VALUES(updated_ymdhis), is_deleted = 0, is_active = 1;

INSERT INTO lupo_edges (edge_id, left_object_type, left_object_id, right_object_type, right_object_id, edge_type, channel_id, channel_key, weight_score, sort_num, actor_id, is_deleted, deleted_ymdhis, created_ymdhis, updated_ymdhis)
VALUES (910000, 'channel', 0, 'content', 5000, 'HAS_CONTENT', 0, 'system/kernel', 0, 0, NULL, 0, 0, @now, @now)
ON DUPLICATE KEY UPDATE left_object_id = VALUES(left_object_id), right_object_id = VALUES(right_object_id), edge_type = VALUES(edge_type), updated_ymdhis = VALUES(updated_ymdhis), is_deleted = 0, deleted_ymdhis = 0;

INSERT INTO lupo_edges (edge_id, left_object_type, left_object_id, right_object_type, right_object_id, edge_type, channel_id, channel_key, weight_score, sort_num, actor_id, is_deleted, deleted_ymdhis, created_ymdhis, updated_ymdhis)
VALUES (910001, 'channel', 51, 'content', 5000, 'HAS_CONTENT', 51, '51', 0, 0, NULL, 0, 0, @now, @now)
ON DUPLICATE KEY UPDATE left_object_id = VALUES(left_object_id), right_object_id = VALUES(right_object_id), edge_type = VALUES(edge_type), updated_ymdhis = VALUES(updated_ymdhis), is_deleted = 0, deleted_ymdhis = 0;

INSERT INTO lupo_contents (
    content_id, content_parent_id, federation_node_id, department_id, actor_id, title, slug, custom_path, description, seo_keywords, body,
    content_type, format, content_url, default_collection_id, source_url, source_title, is_template, status, visibility, view_count, share_count,
    created_ymdhis, utc_cycle, triage_status, triage_notes, updated_ymdhis, is_deleted, is_active, deleted_ymdhis, content_sections, version_number,
    file_path_from_root, file_last_modified_system_version, file_last_modified_utc, tags, dialog_notes
) VALUES (
    5001, NULL, 1, NULL, NULL, 'AI AGENT BOOT NOTES', 'docs-doctrine-aiagentbootnotes', NULL, NULL, NULL, NULL,
    'article', 'markdown', NULL, 0, NULL, NULL, 0, 'published', 'public', 0, 0,
    @now, 'seed', 'untriaged', NULL, @now, 0, 1, NULL, NULL, 1,
    'docs/doctrine/AI_AGENT_BOOT_NOTES.md', '4.0.16', 20260217230000, NULL, NULL
) ON DUPLICATE KEY UPDATE file_path_from_root = VALUES(file_path_from_root), file_last_modified_system_version = VALUES(file_last_modified_system_version), file_last_modified_utc = VALUES(file_last_modified_utc), title = VALUES(title), updated_ymdhis = VALUES(updated_ymdhis), is_deleted = 0, is_active = 1;

INSERT INTO lupo_unified_registry (unified_registry_id, entity_type, entity_index, entity_key, entity_name, entity_table, federation_node_id, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis, is_active, is_kernel, metadata_json)
VALUES (9050001, 'content', 5001, 'docs/doctrine/AI_AGENT_BOOT_NOTES.md', 'AI AGENT BOOT NOTES', 'lupo_contents', 1, @now, @now, 0, NULL, 1, 0, NULL)
ON DUPLICATE KEY UPDATE entity_key = VALUES(entity_key), updated_ymdhis = VALUES(updated_ymdhis), is_deleted = 0, is_active = 1;

INSERT INTO lupo_edges (edge_id, left_object_type, left_object_id, right_object_type, right_object_id, edge_type, channel_id, channel_key, weight_score, sort_num, actor_id, is_deleted, deleted_ymdhis, created_ymdhis, updated_ymdhis)
VALUES (910002, 'channel', 0, 'content', 5001, 'HAS_CONTENT', 0, 'system/kernel', 0, 0, NULL, 0, 0, @now, @now)
ON DUPLICATE KEY UPDATE left_object_id = VALUES(left_object_id), right_object_id = VALUES(right_object_id), edge_type = VALUES(edge_type), updated_ymdhis = VALUES(updated_ymdhis), is_deleted = 0, deleted_ymdhis = 0;

INSERT INTO lupo_edges (edge_id, left_object_type, left_object_id, right_object_type, right_object_id, edge_type, channel_id, channel_key, weight_score, sort_num, actor_id, is_deleted, deleted_ymdhis, created_ymdhis, updated_ymdhis)
VALUES (910003, 'channel', 51, 'content', 5001, 'HAS_CONTENT', 51, '51', 0, 0, NULL, 0, 0, @now, @now)
ON DUPLICATE KEY UPDATE left_object_id = VALUES(left_object_id), right_object_id = VALUES(right_object_id), edge_type = VALUES(edge_type), updated_ymdhis = VALUES(updated_ymdhis), is_deleted = 0, deleted_ymdhis = 0;

INSERT INTO lupo_contents (
    content_id, content_parent_id, federation_node_id, department_id, actor_id, title, slug, custom_path, description, seo_keywords, body,
    content_type, format, content_url, default_collection_id, source_url, source_title, is_template, status, visibility, view_count, share_count,
    created_ymdhis, utc_cycle, triage_status, triage_notes, updated_ymdhis, is_deleted, is_active, deleted_ymdhis, content_sections, version_number,
    file_path_from_root, file_last_modified_system_version, file_last_modified_utc, tags, dialog_notes
) VALUES (
    5002, NULL, 1, NULL, NULL, 'ANUBIS IMPLEMENTATION SUMMARY', 'docs-doctrine-anubis-anubisimplementationsummary', NULL, NULL, NULL, NULL,
    'article', 'markdown', NULL, 0, NULL, NULL, 0, 'published', 'public', 0, 0,
    @now, 'seed', 'untriaged', NULL, @now, 0, 1, NULL, NULL, 1,
    'docs/doctrine/ANUBIS/ANUBIS_IMPLEMENTATION_SUMMARY.md', '4.0.16', 20260217153700, NULL, NULL
) ON DUPLICATE KEY UPDATE file_path_from_root = VALUES(file_path_from_root), file_last_modified_system_version = VALUES(file_last_modified_system_version), file_last_modified_utc = VALUES(file_last_modified_utc), title = VALUES(title), updated_ymdhis = VALUES(updated_ymdhis), is_deleted = 0, is_active = 1;

INSERT INTO lupo_unified_registry (unified_registry_id, entity_type, entity_index, entity_key, entity_name, entity_table, federation_node_id, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis, is_active, is_kernel, metadata_json)
VALUES (9050002, 'content', 5002, 'docs/doctrine/ANUBIS/ANUBIS_IMPLEMENTATION_SUMMARY.md', 'ANUBIS IMPLEMENTATION SUMMARY', 'lupo_contents', 1, @now, @now, 0, NULL, 1, 0, NULL)
ON DUPLICATE KEY UPDATE entity_key = VALUES(entity_key), updated_ymdhis = VALUES(updated_ymdhis), is_deleted = 0, is_active = 1;

INSERT INTO lupo_edges (edge_id, left_object_type, left_object_id, right_object_type, right_object_id, edge_type, channel_id, channel_key, weight_score, sort_num, actor_id, is_deleted, deleted_ymdhis, created_ymdhis, updated_ymdhis)
VALUES (910004, 'channel', 0, 'content', 5002, 'HAS_CONTENT', 0, 'system/kernel', 0, 0, NULL, 0, 0, @now, @now)
ON DUPLICATE KEY UPDATE left_object_id = VALUES(left_object_id), right_object_id = VALUES(right_object_id), edge_type = VALUES(edge_type), updated_ymdhis = VALUES(updated_ymdhis), is_deleted = 0, deleted_ymdhis = 0;

INSERT INTO lupo_edges (edge_id, left_object_type, left_object_id, right_object_type, right_object_id, edge_type, channel_id, channel_key, weight_score, sort_num, actor_id, is_deleted, deleted_ymdhis, created_ymdhis, updated_ymdhis)
VALUES (910005, 'channel', 51, 'content', 5002, 'HAS_CONTENT', 51, '51', 0, 0, NULL, 0, 0, @now, @now)
ON DUPLICATE KEY UPDATE left_object_id = VALUES(left_object_id), right_object_id = VALUES(right_object_id), edge_type = VALUES(edge_type), updated_ymdhis = VALUES(updated_ymdhis), is_deleted = 0, deleted_ymdhis = 0;

INSERT INTO lupo_contents (
    content_id, content_parent_id, federation_node_id, department_id, actor_id, title, slug, custom_path, description, seo_keywords, body,
    content_type, format, content_url, default_collection_id, source_url, source_title, is_template, status, visibility, view_count, share_count,
    created_ymdhis, utc_cycle, triage_status, triage_notes, updated_ymdhis, is_deleted, is_active, deleted_ymdhis, content_sections, version_number,
    file_path_from_root, file_last_modified_system_version, file_last_modified_utc, tags, dialog_notes
) VALUES (
    5003, NULL, 1, NULL, NULL, 'ANUBIS ORPHAN RULES', 'docs-doctrine-anubis-anubisorphanrules', NULL, NULL, NULL, NULL,
    'article', 'markdown', NULL, 0, NULL, NULL, 0, 'published', 'public', 0, 0,
    @now, 'seed', 'untriaged', NULL, @now, 0, 1, NULL, NULL, 1,
    'docs/doctrine/ANUBIS/ANUBIS_ORPHAN_RULES.md', '4.0.16', 20260217230000, NULL, NULL
) ON DUPLICATE KEY UPDATE file_path_from_root = VALUES(file_path_from_root), file_last_modified_system_version = VALUES(file_last_modified_system_version), file_last_modified_utc = VALUES(file_last_modified_utc), title = VALUES(title), updated_ymdhis = VALUES(updated_ymdhis), is_deleted = 0, is_active = 1;

INSERT INTO lupo_unified_registry (unified_registry_id, entity_type, entity_index, entity_key, entity_name, entity_table, federation_node_id, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis, is_active, is_kernel, metadata_json)
VALUES (9050003, 'content', 5003, 'docs/doctrine/ANUBIS/ANUBIS_ORPHAN_RULES.md', 'ANUBIS ORPHAN RULES', 'lupo_contents', 1, @now, @now, 0, NULL, 1, 0, NULL)
ON DUPLICATE KEY UPDATE entity_key = VALUES(entity_key), updated_ymdhis = VALUES(updated_ymdhis), is_deleted = 0, is_active = 1;

INSERT INTO lupo_edges (edge_id, left_object_type, left_object_id, right_object_type, right_object_id, edge_type, channel_id, channel_key, weight_score, sort_num, actor_id, is_deleted, deleted_ymdhis, created_ymdhis, updated_ymdhis)
VALUES (910006, 'channel', 0, 'content', 5003, 'HAS_CONTENT', 0, 'system/kernel', 0, 0, NULL, 0, 0, @now, @now)
ON DUPLICATE KEY UPDATE left_object_id = VALUES(left_object_id), right_object_id = VALUES(right_object_id), edge_type = VALUES(edge_type), updated_ymdhis = VALUES(updated_ymdhis), is_deleted = 0, deleted_ymdhis = 0;

INSERT INTO lupo_edges (edge_id, left_object_type, left_object_id, right_object_type, right_object_id, edge_type, channel_id, channel_key, weight_score, sort_num, actor_id, is_deleted, deleted_ymdhis, created_ymdhis, updated_ymdhis)
VALUES (910007, 'channel', 51, 'content', 5003, 'HAS_CONTENT', 51, '51', 0, 0, NULL, 0, 0, @now, @now)
ON DUPLICATE KEY UPDATE left_object_id = VALUES(left_object_id), right_object_id = VALUES(right_object_id), edge_type = VALUES(edge_type), updated_ymdhis = VALUES(updated_ymdhis), is_deleted = 0, deleted_ymdhis = 0;

INSERT INTO lupo_contents (
    content_id, content_parent_id, federation_node_id, department_id, actor_id, title, slug, custom_path, description, seo_keywords, body,
    content_type, format, content_url, default_collection_id, source_url, source_title, is_template, status, visibility, view_count, share_count,
    created_ymdhis, utc_cycle, triage_status, triage_notes, updated_ymdhis, is_deleted, is_active, deleted_ymdhis, content_sections, version_number,
    file_path_from_root, file_last_modified_system_version, file_last_modified_utc, tags, dialog_notes
) VALUES (
    5004, NULL, 1, NULL, NULL, 'ANUBIS OVERVIEW', 'docs-doctrine-anubis-anubisoverview', NULL, NULL, NULL, NULL,
    'article', 'markdown', NULL, 0, NULL, NULL, 0, 'published', 'public', 0, 0,
    @now, 'seed', 'untriaged', NULL, @now, 0, 1, NULL, NULL, 1,
    'docs/doctrine/ANUBIS/ANUBIS_OVERVIEW.md', '4.0.16', 20260217230000, NULL, NULL
) ON DUPLICATE KEY UPDATE file_path_from_root = VALUES(file_path_from_root), file_last_modified_system_version = VALUES(file_last_modified_system_version), file_last_modified_utc = VALUES(file_last_modified_utc), title = VALUES(title), updated_ymdhis = VALUES(updated_ymdhis), is_deleted = 0, is_active = 1;

INSERT INTO lupo_unified_registry (unified_registry_id, entity_type, entity_index, entity_key, entity_name, entity_table, federation_node_id, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis, is_active, is_kernel, metadata_json)
VALUES (9050004, 'content', 5004, 'docs/doctrine/ANUBIS/ANUBIS_OVERVIEW.md', 'ANUBIS OVERVIEW', 'lupo_contents', 1, @now, @now, 0, NULL, 1, 0, NULL)
ON DUPLICATE KEY UPDATE entity_key = VALUES(entity_key), updated_ymdhis = VALUES(updated_ymdhis), is_deleted = 0, is_active = 1;

INSERT INTO lupo_edges (edge_id, left_object_type, left_object_id, right_object_type, right_object_id, edge_type, channel_id, channel_key, weight_score, sort_num, actor_id, is_deleted, deleted_ymdhis, created_ymdhis, updated_ymdhis)
VALUES (910008, 'channel', 0, 'content', 5004, 'HAS_CONTENT', 0, 'system/kernel', 0, 0, NULL, 0, 0, @now, @now)
ON DUPLICATE KEY UPDATE left_object_id = VALUES(left_object_id), right_object_id = VALUES(right_object_id), edge_type = VALUES(edge_type), updated_ymdhis = VALUES(updated_ymdhis), is_deleted = 0, deleted_ymdhis = 0;

INSERT INTO lupo_edges (edge_id, left_object_type, left_object_id, right_object_type, right_object_id, edge_type, channel_id, channel_key, weight_score, sort_num, actor_id, is_deleted, deleted_ymdhis, created_ymdhis, updated_ymdhis)
VALUES (910009, 'channel', 51, 'content', 5004, 'HAS_CONTENT', 51, '51', 0, 0, NULL, 0, 0, @now, @now)
ON DUPLICATE KEY UPDATE left_object_id = VALUES(left_object_id), right_object_id = VALUES(right_object_id), edge_type = VALUES(edge_type), updated_ymdhis = VALUES(updated_ymdhis), is_deleted = 0, deleted_ymdhis = 0;

INSERT INTO lupo_contents (
    content_id, content_parent_id, federation_node_id, department_id, actor_id, title, slug, custom_path, description, seo_keywords, body,
    content_type, format, content_url, default_collection_id, source_url, source_title, is_template, status, visibility, view_count, share_count,
    created_ymdhis, utc_cycle, triage_status, triage_notes, updated_ymdhis, is_deleted, is_active, deleted_ymdhis, content_sections, version_number,
    file_path_from_root, file_last_modified_system_version, file_last_modified_utc, tags, dialog_notes
) VALUES (
    5005, NULL, 1, NULL, NULL, 'ANUBIS PROGRAM SPEC', 'docs-doctrine-anubis-anubisprogramspec', NULL, NULL, NULL, NULL,
    'article', 'markdown', NULL, 0, NULL, NULL, 0, 'published', 'public', 0, 0,
    @now, 'seed', 'untriaged', NULL, @now, 0, 1, NULL, NULL, 1,
    'docs/doctrine/ANUBIS/ANUBIS_PROGRAM_SPEC.md', '4.0.16', 20260217230000, NULL, NULL
) ON DUPLICATE KEY UPDATE file_path_from_root = VALUES(file_path_from_root), file_last_modified_system_version = VALUES(file_last_modified_system_version), file_last_modified_utc = VALUES(file_last_modified_utc), title = VALUES(title), updated_ymdhis = VALUES(updated_ymdhis), is_deleted = 0, is_active = 1;

INSERT INTO lupo_unified_registry (unified_registry_id, entity_type, entity_index, entity_key, entity_name, entity_table, federation_node_id, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis, is_active, is_kernel, metadata_json)
VALUES (9050005, 'content', 5005, 'docs/doctrine/ANUBIS/ANUBIS_PROGRAM_SPEC.md', 'ANUBIS PROGRAM SPEC', 'lupo_contents', 1, @now, @now, 0, NULL, 1, 0, NULL)
ON DUPLICATE KEY UPDATE entity_key = VALUES(entity_key), updated_ymdhis = VALUES(updated_ymdhis), is_deleted = 0, is_active = 1;

INSERT INTO lupo_edges (edge_id, left_object_type, left_object_id, right_object_type, right_object_id, edge_type, channel_id, channel_key, weight_score, sort_num, actor_id, is_deleted, deleted_ymdhis, created_ymdhis, updated_ymdhis)
VALUES (910010, 'channel', 0, 'content', 5005, 'HAS_CONTENT', 0, 'system/kernel', 0, 0, NULL, 0, 0, @now, @now)
ON DUPLICATE KEY UPDATE left_object_id = VALUES(left_object_id), right_object_id = VALUES(right_object_id), edge_type = VALUES(edge_type), updated_ymdhis = VALUES(updated_ymdhis), is_deleted = 0, deleted_ymdhis = 0;

INSERT INTO lupo_edges (edge_id, left_object_type, left_object_id, right_object_type, right_object_id, edge_type, channel_id, channel_key, weight_score, sort_num, actor_id, is_deleted, deleted_ymdhis, created_ymdhis, updated_ymdhis)
VALUES (910011, 'channel', 51, 'content', 5005, 'HAS_CONTENT', 51, '51', 0, 0, NULL, 0, 0, @now, @now)
ON DUPLICATE KEY UPDATE left_object_id = VALUES(left_object_id), right_object_id = VALUES(right_object_id), edge_type = VALUES(edge_type), updated_ymdhis = VALUES(updated_ymdhis), is_deleted = 0, deleted_ymdhis = 0;

INSERT INTO lupo_contents (
    content_id, content_parent_id, federation_node_id, department_id, actor_id, title, slug, custom_path, description, seo_keywords, body,
    content_type, format, content_url, default_collection_id, source_url, source_title, is_template, status, visibility, view_count, share_count,
    created_ymdhis, utc_cycle, triage_status, triage_notes, updated_ymdhis, is_deleted, is_active, deleted_ymdhis, content_sections, version_number,
    file_path_from_root, file_last_modified_system_version, file_last_modified_utc, tags, dialog_notes
) VALUES (
    5006, NULL, 1, NULL, NULL, 'CASCADE TABLE CEILING PROTOCOL', 'docs-doctrine-cascadetableceilingprotocol', NULL, NULL, NULL, NULL,
    'article', 'markdown', NULL, 0, NULL, NULL, 0, 'published', 'public', 0, 0,
    @now, 'seed', 'untriaged', NULL, @now, 0, 1, NULL, NULL, 1,
    'docs/doctrine/CASCADE_TABLE_CEILING_PROTOCOL.md', '4.0.16', 20260217230000, NULL, NULL
) ON DUPLICATE KEY UPDATE file_path_from_root = VALUES(file_path_from_root), file_last_modified_system_version = VALUES(file_last_modified_system_version), file_last_modified_utc = VALUES(file_last_modified_utc), title = VALUES(title), updated_ymdhis = VALUES(updated_ymdhis), is_deleted = 0, is_active = 1;

INSERT INTO lupo_unified_registry (unified_registry_id, entity_type, entity_index, entity_key, entity_name, entity_table, federation_node_id, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis, is_active, is_kernel, metadata_json)
VALUES (9050006, 'content', 5006, 'docs/doctrine/CASCADE_TABLE_CEILING_PROTOCOL.md', 'CASCADE TABLE CEILING PROTOCOL', 'lupo_contents', 1, @now, @now, 0, NULL, 1, 0, NULL)
ON DUPLICATE KEY UPDATE entity_key = VALUES(entity_key), updated_ymdhis = VALUES(updated_ymdhis), is_deleted = 0, is_active = 1;

INSERT INTO lupo_edges (edge_id, left_object_type, left_object_id, right_object_type, right_object_id, edge_type, channel_id, channel_key, weight_score, sort_num, actor_id, is_deleted, deleted_ymdhis, created_ymdhis, updated_ymdhis)
VALUES (910012, 'channel', 0, 'content', 5006, 'HAS_CONTENT', 0, 'system/kernel', 0, 0, NULL, 0, 0, @now, @now)
ON DUPLICATE KEY UPDATE left_object_id = VALUES(left_object_id), right_object_id = VALUES(right_object_id), edge_type = VALUES(edge_type), updated_ymdhis = VALUES(updated_ymdhis), is_deleted = 0, deleted_ymdhis = 0;

INSERT INTO lupo_edges (edge_id, left_object_type, left_object_id, right_object_type, right_object_id, edge_type, channel_id, channel_key, weight_score, sort_num, actor_id, is_deleted, deleted_ymdhis, created_ymdhis, updated_ymdhis)
VALUES (910013, 'channel', 51, 'content', 5006, 'HAS_CONTENT', 51, '51', 0, 0, NULL, 0, 0, @now, @now)
ON DUPLICATE KEY UPDATE left_object_id = VALUES(left_object_id), right_object_id = VALUES(right_object_id), edge_type = VALUES(edge_type), updated_ymdhis = VALUES(updated_ymdhis), is_deleted = 0, deleted_ymdhis = 0;

INSERT INTO lupo_contents (
    content_id, content_parent_id, federation_node_id, department_id, actor_id, title, slug, custom_path, description, seo_keywords, body,
    content_type, format, content_url, default_collection_id, source_url, source_title, is_template, status, visibility, view_count, share_count,
    created_ymdhis, utc_cycle, triage_status, triage_notes, updated_ymdhis, is_deleted, is_active, deleted_ymdhis, content_sections, version_number,
    file_path_from_root, file_last_modified_system_version, file_last_modified_utc, tags, dialog_notes
) VALUES (
    5007, NULL, 1, NULL, NULL, 'CLASS CONVERSION DOCTRINE', 'docs-doctrine-classconversiondoctrine', NULL, NULL, NULL, NULL,
    'article', 'markdown', NULL, 0, NULL, NULL, 0, 'published', 'public', 0, 0,
    @now, 'seed', 'untriaged', NULL, @now, 0, 1, NULL, NULL, 1,
    'docs/doctrine/CLASS_CONVERSION_DOCTRINE.md', '4.0.16', 20260217230000, NULL, NULL
) ON DUPLICATE KEY UPDATE file_path_from_root = VALUES(file_path_from_root), file_last_modified_system_version = VALUES(file_last_modified_system_version), file_last_modified_utc = VALUES(file_last_modified_utc), title = VALUES(title), updated_ymdhis = VALUES(updated_ymdhis), is_deleted = 0, is_active = 1;

INSERT INTO lupo_unified_registry (unified_registry_id, entity_type, entity_index, entity_key, entity_name, entity_table, federation_node_id, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis, is_active, is_kernel, metadata_json)
VALUES (9050007, 'content', 5007, 'docs/doctrine/CLASS_CONVERSION_DOCTRINE.md', 'CLASS CONVERSION DOCTRINE', 'lupo_contents', 1, @now, @now, 0, NULL, 1, 0, NULL)
ON DUPLICATE KEY UPDATE entity_key = VALUES(entity_key), updated_ymdhis = VALUES(updated_ymdhis), is_deleted = 0, is_active = 1;

INSERT INTO lupo_edges (edge_id, left_object_type, left_object_id, right_object_type, right_object_id, edge_type, channel_id, channel_key, weight_score, sort_num, actor_id, is_deleted, deleted_ymdhis, created_ymdhis, updated_ymdhis)
VALUES (910014, 'channel', 0, 'content', 5007, 'HAS_CONTENT', 0, 'system/kernel', 0, 0, NULL, 0, 0, @now, @now)
ON DUPLICATE KEY UPDATE left_object_id = VALUES(left_object_id), right_object_id = VALUES(right_object_id), edge_type = VALUES(edge_type), updated_ymdhis = VALUES(updated_ymdhis), is_deleted = 0, deleted_ymdhis = 0;

INSERT INTO lupo_edges (edge_id, left_object_type, left_object_id, right_object_type, right_object_id, edge_type, channel_id, channel_key, weight_score, sort_num, actor_id, is_deleted, deleted_ymdhis, created_ymdhis, updated_ymdhis)
VALUES (910015, 'channel', 51, 'content', 5007, 'HAS_CONTENT', 51, '51', 0, 0, NULL, 0, 0, @now, @now)
ON DUPLICATE KEY UPDATE left_object_id = VALUES(left_object_id), right_object_id = VALUES(right_object_id), edge_type = VALUES(edge_type), updated_ymdhis = VALUES(updated_ymdhis), is_deleted = 0, deleted_ymdhis = 0;

INSERT INTO lupo_contents (
    content_id, content_parent_id, federation_node_id, department_id, actor_id, title, slug, custom_path, description, seo_keywords, body,
    content_type, format, content_url, default_collection_id, source_url, source_title, is_template, status, visibility, view_count, share_count,
    created_ymdhis, utc_cycle, triage_status, triage_notes, updated_ymdhis, is_deleted, is_active, deleted_ymdhis, content_sections, version_number,
    file_path_from_root, file_last_modified_system_version, file_last_modified_utc, tags, dialog_notes
) VALUES (
    5008, NULL, 1, NULL, NULL, 'COMPATIBILITY MATRIX', 'docs-doctrine-compatibilitymatrix', NULL, NULL, NULL, NULL,
    'article', 'markdown', NULL, 0, NULL, NULL, 0, 'published', 'public', 0, 0,
    @now, 'seed', 'untriaged', NULL, @now, 0, 1, NULL, NULL, 1,
    'docs/doctrine/COMPATIBILITY_MATRIX.md', '4.0.16', 20260217230000, NULL, NULL
) ON DUPLICATE KEY UPDATE file_path_from_root = VALUES(file_path_from_root), file_last_modified_system_version = VALUES(file_last_modified_system_version), file_last_modified_utc = VALUES(file_last_modified_utc), title = VALUES(title), updated_ymdhis = VALUES(updated_ymdhis), is_deleted = 0, is_active = 1;

INSERT INTO lupo_unified_registry (unified_registry_id, entity_type, entity_index, entity_key, entity_name, entity_table, federation_node_id, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis, is_active, is_kernel, metadata_json)
VALUES (9050008, 'content', 5008, 'docs/doctrine/COMPATIBILITY_MATRIX.md', 'COMPATIBILITY MATRIX', 'lupo_contents', 1, @now, @now, 0, NULL, 1, 0, NULL)
ON DUPLICATE KEY UPDATE entity_key = VALUES(entity_key), updated_ymdhis = VALUES(updated_ymdhis), is_deleted = 0, is_active = 1;

INSERT INTO lupo_edges (edge_id, left_object_type, left_object_id, right_object_type, right_object_id, edge_type, channel_id, channel_key, weight_score, sort_num, actor_id, is_deleted, deleted_ymdhis, created_ymdhis, updated_ymdhis)
VALUES (910016, 'channel', 0, 'content', 5008, 'HAS_CONTENT', 0, 'system/kernel', 0, 0, NULL, 0, 0, @now, @now)
ON DUPLICATE KEY UPDATE left_object_id = VALUES(left_object_id), right_object_id = VALUES(right_object_id), edge_type = VALUES(edge_type), updated_ymdhis = VALUES(updated_ymdhis), is_deleted = 0, deleted_ymdhis = 0;

INSERT INTO lupo_edges (edge_id, left_object_type, left_object_id, right_object_type, right_object_id, edge_type, channel_id, channel_key, weight_score, sort_num, actor_id, is_deleted, deleted_ymdhis, created_ymdhis, updated_ymdhis)
VALUES (910017, 'channel', 51, 'content', 5008, 'HAS_CONTENT', 51, '51', 0, 0, NULL, 0, 0, @now, @now)
ON DUPLICATE KEY UPDATE left_object_id = VALUES(left_object_id), right_object_id = VALUES(right_object_id), edge_type = VALUES(edge_type), updated_ymdhis = VALUES(updated_ymdhis), is_deleted = 0, deleted_ymdhis = 0;

INSERT INTO lupo_contents (
    content_id, content_parent_id, federation_node_id, department_id, actor_id, title, slug, custom_path, description, seo_keywords, body,
    content_type, format, content_url, default_collection_id, source_url, source_title, is_template, status, visibility, view_count, share_count,
    created_ymdhis, utc_cycle, triage_status, triage_notes, updated_ymdhis, is_deleted, is_active, deleted_ymdhis, content_sections, version_number,
    file_path_from_root, file_last_modified_system_version, file_last_modified_utc, tags, dialog_notes
) VALUES (
    5009, NULL, 1, NULL, NULL, 'CONSOLIDATION VALIDATION REQUIREMENTS', 'docs-doctrine-consolidationvalidationrequirements', NULL, NULL, NULL, NULL,
    'article', 'markdown', NULL, 0, NULL, NULL, 0, 'published', 'public', 0, 0,
    @now, 'seed', 'untriaged', NULL, @now, 0, 1, NULL, NULL, 1,
    'docs/doctrine/CONSOLIDATION_VALIDATION_REQUIREMENTS.md', '4.0.16', 20260217230000, NULL, NULL
) ON DUPLICATE KEY UPDATE file_path_from_root = VALUES(file_path_from_root), file_last_modified_system_version = VALUES(file_last_modified_system_version), file_last_modified_utc = VALUES(file_last_modified_utc), title = VALUES(title), updated_ymdhis = VALUES(updated_ymdhis), is_deleted = 0, is_active = 1;

INSERT INTO lupo_unified_registry (unified_registry_id, entity_type, entity_index, entity_key, entity_name, entity_table, federation_node_id, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis, is_active, is_kernel, metadata_json)
VALUES (9050009, 'content', 5009, 'docs/doctrine/CONSOLIDATION_VALIDATION_REQUIREMENTS.md', 'CONSOLIDATION VALIDATION REQUIREMENTS', 'lupo_contents', 1, @now, @now, 0, NULL, 1, 0, NULL)
ON DUPLICATE KEY UPDATE entity_key = VALUES(entity_key), updated_ymdhis = VALUES(updated_ymdhis), is_deleted = 0, is_active = 1;

INSERT INTO lupo_edges (edge_id, left_object_type, left_object_id, right_object_type, right_object_id, edge_type, channel_id, channel_key, weight_score, sort_num, actor_id, is_deleted, deleted_ymdhis, created_ymdhis, updated_ymdhis)
VALUES (910018, 'channel', 0, 'content', 5009, 'HAS_CONTENT', 0, 'system/kernel', 0, 0, NULL, 0, 0, @now, @now)
ON DUPLICATE KEY UPDATE left_object_id = VALUES(left_object_id), right_object_id = VALUES(right_object_id), edge_type = VALUES(edge_type), updated_ymdhis = VALUES(updated_ymdhis), is_deleted = 0, deleted_ymdhis = 0;

INSERT INTO lupo_edges (edge_id, left_object_type, left_object_id, right_object_type, right_object_id, edge_type, channel_id, channel_key, weight_score, sort_num, actor_id, is_deleted, deleted_ymdhis, created_ymdhis, updated_ymdhis)
VALUES (910019, 'channel', 51, 'content', 5009, 'HAS_CONTENT', 51, '51', 0, 0, NULL, 0, 0, @now, @now)
ON DUPLICATE KEY UPDATE left_object_id = VALUES(left_object_id), right_object_id = VALUES(right_object_id), edge_type = VALUES(edge_type), updated_ymdhis = VALUES(updated_ymdhis), is_deleted = 0, deleted_ymdhis = 0;

INSERT INTO lupo_contents (
    content_id, content_parent_id, federation_node_id, department_id, actor_id, title, slug, custom_path, description, seo_keywords, body,
    content_type, format, content_url, default_collection_id, source_url, source_title, is_template, status, visibility, view_count, share_count,
    created_ymdhis, utc_cycle, triage_status, triage_notes, updated_ymdhis, is_deleted, is_active, deleted_ymdhis, content_sections, version_number,
    file_path_from_root, file_last_modified_system_version, file_last_modified_utc, tags, dialog_notes
) VALUES (
    5010, NULL, 1, NULL, NULL, 'CRAFTY SYNTAX IMPORT IMPLEMENTATION CHECKLIST', 'docs-doctrine-craftysyntaximportimplementationchecklist', NULL, NULL, NULL, NULL,
    'article', 'markdown', NULL, 0, NULL, NULL, 0, 'published', 'public', 0, 0,
    @now, 'seed', 'untriaged', NULL, @now, 0, 1, NULL, NULL, 1,
    'docs/doctrine/CRAFTY_SYNTAX_IMPORT_IMPLEMENTATION_CHECKLIST.md', '4.0.16', 20260217230000, NULL, NULL
) ON DUPLICATE KEY UPDATE file_path_from_root = VALUES(file_path_from_root), file_last_modified_system_version = VALUES(file_last_modified_system_version), file_last_modified_utc = VALUES(file_last_modified_utc), title = VALUES(title), updated_ymdhis = VALUES(updated_ymdhis), is_deleted = 0, is_active = 1;

INSERT INTO lupo_unified_registry (unified_registry_id, entity_type, entity_index, entity_key, entity_name, entity_table, federation_node_id, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis, is_active, is_kernel, metadata_json)
VALUES (9050010, 'content', 5010, 'docs/doctrine/CRAFTY_SYNTAX_IMPORT_IMPLEMENTATION_CHECKLIST.md', 'CRAFTY SYNTAX IMPORT IMPLEMENTATION CHECKLIST', 'lupo_contents', 1, @now, @now, 0, NULL, 1, 0, NULL)
ON DUPLICATE KEY UPDATE entity_key = VALUES(entity_key), updated_ymdhis = VALUES(updated_ymdhis), is_deleted = 0, is_active = 1;

INSERT INTO lupo_edges (edge_id, left_object_type, left_object_id, right_object_type, right_object_id, edge_type, channel_id, channel_key, weight_score, sort_num, actor_id, is_deleted, deleted_ymdhis, created_ymdhis, updated_ymdhis)
VALUES (910020, 'channel', 0, 'content', 5010, 'HAS_CONTENT', 0, 'system/kernel', 0, 0, NULL, 0, 0, @now, @now)
ON DUPLICATE KEY UPDATE left_object_id = VALUES(left_object_id), right_object_id = VALUES(right_object_id), edge_type = VALUES(edge_type), updated_ymdhis = VALUES(updated_ymdhis), is_deleted = 0, deleted_ymdhis = 0;

INSERT INTO lupo_edges (edge_id, left_object_type, left_object_id, right_object_type, right_object_id, edge_type, channel_id, channel_key, weight_score, sort_num, actor_id, is_deleted, deleted_ymdhis, created_ymdhis, updated_ymdhis)
VALUES (910021, 'channel', 51, 'content', 5010, 'HAS_CONTENT', 51, '51', 0, 0, NULL, 0, 0, @now, @now)
ON DUPLICATE KEY UPDATE left_object_id = VALUES(left_object_id), right_object_id = VALUES(right_object_id), edge_type = VALUES(edge_type), updated_ymdhis = VALUES(updated_ymdhis), is_deleted = 0, deleted_ymdhis = 0;

INSERT INTO lupo_contents (
    content_id, content_parent_id, federation_node_id, department_id, actor_id, title, slug, custom_path, description, seo_keywords, body,
    content_type, format, content_url, default_collection_id, source_url, source_title, is_template, status, visibility, view_count, share_count,
    created_ymdhis, utc_cycle, triage_status, triage_notes, updated_ymdhis, is_deleted, is_active, deleted_ymdhis, content_sections, version_number,
    file_path_from_root, file_last_modified_system_version, file_last_modified_utc, tags, dialog_notes
) VALUES (
    5011, NULL, 1, NULL, NULL, 'CRAFTY SYNTAX INTEGRATION PLAN', 'docs-doctrine-craftysyntaxintegrationplan', NULL, NULL, NULL, NULL,
    'article', 'markdown', NULL, 0, NULL, NULL, 0, 'published', 'public', 0, 0,
    @now, 'seed', 'untriaged', NULL, @now, 0, 1, NULL, NULL, 1,
    'docs/doctrine/CRAFTY_SYNTAX_INTEGRATION_PLAN.md', '4.0.16', 20260217230000, NULL, NULL
) ON DUPLICATE KEY UPDATE file_path_from_root = VALUES(file_path_from_root), file_last_modified_system_version = VALUES(file_last_modified_system_version), file_last_modified_utc = VALUES(file_last_modified_utc), title = VALUES(title), updated_ymdhis = VALUES(updated_ymdhis), is_deleted = 0, is_active = 1;

INSERT INTO lupo_unified_registry (unified_registry_id, entity_type, entity_index, entity_key, entity_name, entity_table, federation_node_id, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis, is_active, is_kernel, metadata_json)
VALUES (9050011, 'content', 5011, 'docs/doctrine/CRAFTY_SYNTAX_INTEGRATION_PLAN.md', 'CRAFTY SYNTAX INTEGRATION PLAN', 'lupo_contents', 1, @now, @now, 0, NULL, 1, 0, NULL)
ON DUPLICATE KEY UPDATE entity_key = VALUES(entity_key), updated_ymdhis = VALUES(updated_ymdhis), is_deleted = 0, is_active = 1;

INSERT INTO lupo_edges (edge_id, left_object_type, left_object_id, right_object_type, right_object_id, edge_type, channel_id, channel_key, weight_score, sort_num, actor_id, is_deleted, deleted_ymdhis, created_ymdhis, updated_ymdhis)
VALUES (910022, 'channel', 0, 'content', 5011, 'HAS_CONTENT', 0, 'system/kernel', 0, 0, NULL, 0, 0, @now, @now)
ON DUPLICATE KEY UPDATE left_object_id = VALUES(left_object_id), right_object_id = VALUES(right_object_id), edge_type = VALUES(edge_type), updated_ymdhis = VALUES(updated_ymdhis), is_deleted = 0, deleted_ymdhis = 0;

INSERT INTO lupo_edges (edge_id, left_object_type, left_object_id, right_object_type, right_object_id, edge_type, channel_id, channel_key, weight_score, sort_num, actor_id, is_deleted, deleted_ymdhis, created_ymdhis, updated_ymdhis)
VALUES (910023, 'channel', 51, 'content', 5011, 'HAS_CONTENT', 51, '51', 0, 0, NULL, 0, 0, @now, @now)
ON DUPLICATE KEY UPDATE left_object_id = VALUES(left_object_id), right_object_id = VALUES(right_object_id), edge_type = VALUES(edge_type), updated_ymdhis = VALUES(updated_ymdhis), is_deleted = 0, deleted_ymdhis = 0;

INSERT INTO lupo_contents (
    content_id, content_parent_id, federation_node_id, department_id, actor_id, title, slug, custom_path, description, seo_keywords, body,
    content_type, format, content_url, default_collection_id, source_url, source_title, is_template, status, visibility, view_count, share_count,
    created_ymdhis, utc_cycle, triage_status, triage_notes, updated_ymdhis, is_deleted, is_active, deleted_ymdhis, content_sections, version_number,
    file_path_from_root, file_last_modified_system_version, file_last_modified_utc, tags, dialog_notes
) VALUES (
    5012, NULL, 1, NULL, NULL, 'CRAFTY SYNTAX MIGRATION PROJECT BRIEF', 'docs-doctrine-craftysyntaxmigrationprojectbrief', NULL, NULL, NULL, NULL,
    'article', 'markdown', NULL, 0, NULL, NULL, 0, 'published', 'public', 0, 0,
    @now, 'seed', 'untriaged', NULL, @now, 0, 1, NULL, NULL, 1,
    'docs/doctrine/CRAFTY_SYNTAX_MIGRATION_PROJECT_BRIEF.md', '4.0.16', 20260217230000, NULL, NULL
) ON DUPLICATE KEY UPDATE file_path_from_root = VALUES(file_path_from_root), file_last_modified_system_version = VALUES(file_last_modified_system_version), file_last_modified_utc = VALUES(file_last_modified_utc), title = VALUES(title), updated_ymdhis = VALUES(updated_ymdhis), is_deleted = 0, is_active = 1;

INSERT INTO lupo_unified_registry (unified_registry_id, entity_type, entity_index, entity_key, entity_name, entity_table, federation_node_id, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis, is_active, is_kernel, metadata_json)
VALUES (9050012, 'content', 5012, 'docs/doctrine/CRAFTY_SYNTAX_MIGRATION_PROJECT_BRIEF.md', 'CRAFTY SYNTAX MIGRATION PROJECT BRIEF', 'lupo_contents', 1, @now, @now, 0, NULL, 1, 0, NULL)
ON DUPLICATE KEY UPDATE entity_key = VALUES(entity_key), updated_ymdhis = VALUES(updated_ymdhis), is_deleted = 0, is_active = 1;

INSERT INTO lupo_edges (edge_id, left_object_type, left_object_id, right_object_type, right_object_id, edge_type, channel_id, channel_key, weight_score, sort_num, actor_id, is_deleted, deleted_ymdhis, created_ymdhis, updated_ymdhis)
VALUES (910024, 'channel', 0, 'content', 5012, 'HAS_CONTENT', 0, 'system/kernel', 0, 0, NULL, 0, 0, @now, @now)
ON DUPLICATE KEY UPDATE left_object_id = VALUES(left_object_id), right_object_id = VALUES(right_object_id), edge_type = VALUES(edge_type), updated_ymdhis = VALUES(updated_ymdhis), is_deleted = 0, deleted_ymdhis = 0;

INSERT INTO lupo_edges (edge_id, left_object_type, left_object_id, right_object_type, right_object_id, edge_type, channel_id, channel_key, weight_score, sort_num, actor_id, is_deleted, deleted_ymdhis, created_ymdhis, updated_ymdhis)
VALUES (910025, 'channel', 51, 'content', 5012, 'HAS_CONTENT', 51, '51', 0, 0, NULL, 0, 0, @now, @now)
ON DUPLICATE KEY UPDATE left_object_id = VALUES(left_object_id), right_object_id = VALUES(right_object_id), edge_type = VALUES(edge_type), updated_ymdhis = VALUES(updated_ymdhis), is_deleted = 0, deleted_ymdhis = 0;

INSERT INTO lupo_contents (
    content_id, content_parent_id, federation_node_id, department_id, actor_id, title, slug, custom_path, description, seo_keywords, body,
    content_type, format, content_url, default_collection_id, source_url, source_title, is_template, status, visibility, view_count, share_count,
    created_ymdhis, utc_cycle, triage_status, triage_notes, updated_ymdhis, is_deleted, is_active, deleted_ymdhis, content_sections, version_number,
    file_path_from_root, file_last_modified_system_version, file_last_modified_utc, tags, dialog_notes
) VALUES (
    5013, NULL, 1, NULL, NULL, 'CRAFTY SYNTAX STATE BASED IMPLEMENTATION PLAN', 'docs-doctrine-craftysyntaxstatebasedimplementationplan', NULL, NULL, NULL, NULL,
    'article', 'markdown', NULL, 0, NULL, NULL, 0, 'published', 'public', 0, 0,
    @now, 'seed', 'untriaged', NULL, @now, 0, 1, NULL, NULL, 1,
    'docs/doctrine/CRAFTY_SYNTAX_STATE_BASED_IMPLEMENTATION_PLAN.md', '4.0.16', 20260217230000, NULL, NULL
) ON DUPLICATE KEY UPDATE file_path_from_root = VALUES(file_path_from_root), file_last_modified_system_version = VALUES(file_last_modified_system_version), file_last_modified_utc = VALUES(file_last_modified_utc), title = VALUES(title), updated_ymdhis = VALUES(updated_ymdhis), is_deleted = 0, is_active = 1;

INSERT INTO lupo_unified_registry (unified_registry_id, entity_type, entity_index, entity_key, entity_name, entity_table, federation_node_id, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis, is_active, is_kernel, metadata_json)
VALUES (9050013, 'content', 5013, 'docs/doctrine/CRAFTY_SYNTAX_STATE_BASED_IMPLEMENTATION_PLAN.md', 'CRAFTY SYNTAX STATE BASED IMPLEMENTATION PLAN', 'lupo_contents', 1, @now, @now, 0, NULL, 1, 0, NULL)
ON DUPLICATE KEY UPDATE entity_key = VALUES(entity_key), updated_ymdhis = VALUES(updated_ymdhis), is_deleted = 0, is_active = 1;

INSERT INTO lupo_edges (edge_id, left_object_type, left_object_id, right_object_type, right_object_id, edge_type, channel_id, channel_key, weight_score, sort_num, actor_id, is_deleted, deleted_ymdhis, created_ymdhis, updated_ymdhis)
VALUES (910026, 'channel', 0, 'content', 5013, 'HAS_CONTENT', 0, 'system/kernel', 0, 0, NULL, 0, 0, @now, @now)
ON DUPLICATE KEY UPDATE left_object_id = VALUES(left_object_id), right_object_id = VALUES(right_object_id), edge_type = VALUES(edge_type), updated_ymdhis = VALUES(updated_ymdhis), is_deleted = 0, deleted_ymdhis = 0;

INSERT INTO lupo_edges (edge_id, left_object_type, left_object_id, right_object_type, right_object_id, edge_type, channel_id, channel_key, weight_score, sort_num, actor_id, is_deleted, deleted_ymdhis, created_ymdhis, updated_ymdhis)
VALUES (910027, 'channel', 51, 'content', 5013, 'HAS_CONTENT', 51, '51', 0, 0, NULL, 0, 0, @now, @now)
ON DUPLICATE KEY UPDATE left_object_id = VALUES(left_object_id), right_object_id = VALUES(right_object_id), edge_type = VALUES(edge_type), updated_ymdhis = VALUES(updated_ymdhis), is_deleted = 0, deleted_ymdhis = 0;

INSERT INTO lupo_contents (
    content_id, content_parent_id, federation_node_id, department_id, actor_id, title, slug, custom_path, description, seo_keywords, body,
    content_type, format, content_url, default_collection_id, source_url, source_title, is_template, status, visibility, view_count, share_count,
    created_ymdhis, utc_cycle, triage_status, triage_notes, updated_ymdhis, is_deleted, is_active, deleted_ymdhis, content_sections, version_number,
    file_path_from_root, file_last_modified_system_version, file_last_modified_utc, tags, dialog_notes
) VALUES (
    5014, NULL, 1, NULL, NULL, 'DEVELOPMENT WORKFLOW DOCTRINE', 'docs-doctrine-developmentworkflowdoctrine', NULL, NULL, NULL, NULL,
    'article', 'markdown', NULL, 0, NULL, NULL, 0, 'published', 'public', 0, 0,
    @now, 'seed', 'untriaged', NULL, @now, 0, 1, NULL, NULL, 1,
    'docs/doctrine/DEVELOPMENT_WORKFLOW_DOCTRINE.md', '4.0.16', 20260217230000, NULL, NULL
) ON DUPLICATE KEY UPDATE file_path_from_root = VALUES(file_path_from_root), file_last_modified_system_version = VALUES(file_last_modified_system_version), file_last_modified_utc = VALUES(file_last_modified_utc), title = VALUES(title), updated_ymdhis = VALUES(updated_ymdhis), is_deleted = 0, is_active = 1;

INSERT INTO lupo_unified_registry (unified_registry_id, entity_type, entity_index, entity_key, entity_name, entity_table, federation_node_id, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis, is_active, is_kernel, metadata_json)
VALUES (9050014, 'content', 5014, 'docs/doctrine/DEVELOPMENT_WORKFLOW_DOCTRINE.md', 'DEVELOPMENT WORKFLOW DOCTRINE', 'lupo_contents', 1, @now, @now, 0, NULL, 1, 0, NULL)
ON DUPLICATE KEY UPDATE entity_key = VALUES(entity_key), updated_ymdhis = VALUES(updated_ymdhis), is_deleted = 0, is_active = 1;

INSERT INTO lupo_edges (edge_id, left_object_type, left_object_id, right_object_type, right_object_id, edge_type, channel_id, channel_key, weight_score, sort_num, actor_id, is_deleted, deleted_ymdhis, created_ymdhis, updated_ymdhis)
VALUES (910028, 'channel', 0, 'content', 5014, 'HAS_CONTENT', 0, 'system/kernel', 0, 0, NULL, 0, 0, @now, @now)
ON DUPLICATE KEY UPDATE left_object_id = VALUES(left_object_id), right_object_id = VALUES(right_object_id), edge_type = VALUES(edge_type), updated_ymdhis = VALUES(updated_ymdhis), is_deleted = 0, deleted_ymdhis = 0;

INSERT INTO lupo_edges (edge_id, left_object_type, left_object_id, right_object_type, right_object_id, edge_type, channel_id, channel_key, weight_score, sort_num, actor_id, is_deleted, deleted_ymdhis, created_ymdhis, updated_ymdhis)
VALUES (910029, 'channel', 51, 'content', 5014, 'HAS_CONTENT', 51, '51', 0, 0, NULL, 0, 0, @now, @now)
ON DUPLICATE KEY UPDATE left_object_id = VALUES(left_object_id), right_object_id = VALUES(right_object_id), edge_type = VALUES(edge_type), updated_ymdhis = VALUES(updated_ymdhis), is_deleted = 0, deleted_ymdhis = 0;

INSERT INTO lupo_contents (
    content_id, content_parent_id, federation_node_id, department_id, actor_id, title, slug, custom_path, description, seo_keywords, body,
    content_type, format, content_url, default_collection_id, source_url, source_title, is_template, status, visibility, view_count, share_count,
    created_ymdhis, utc_cycle, triage_status, triage_notes, updated_ymdhis, is_deleted, is_active, deleted_ymdhis, content_sections, version_number,
    file_path_from_root, file_last_modified_system_version, file_last_modified_utc, tags, dialog_notes
) VALUES (
    5015, NULL, 1, NULL, NULL, 'DOCTRINE FILE STRUCTURE', 'docs-doctrine-doctrinefilestructure', NULL, NULL, NULL, NULL,
    'article', 'markdown', NULL, 0, NULL, NULL, 0, 'published', 'public', 0, 0,
    @now, 'seed', 'untriaged', NULL, @now, 0, 1, NULL, NULL, 1,
    'docs/doctrine/DOCTRINE_FILE_STRUCTURE.md', '4.0.16', 20260217230000, NULL, NULL
) ON DUPLICATE KEY UPDATE file_path_from_root = VALUES(file_path_from_root), file_last_modified_system_version = VALUES(file_last_modified_system_version), file_last_modified_utc = VALUES(file_last_modified_utc), title = VALUES(title), updated_ymdhis = VALUES(updated_ymdhis), is_deleted = 0, is_active = 1;

INSERT INTO lupo_unified_registry (unified_registry_id, entity_type, entity_index, entity_key, entity_name, entity_table, federation_node_id, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis, is_active, is_kernel, metadata_json)
VALUES (9050015, 'content', 5015, 'docs/doctrine/DOCTRINE_FILE_STRUCTURE.md', 'DOCTRINE FILE STRUCTURE', 'lupo_contents', 1, @now, @now, 0, NULL, 1, 0, NULL)
ON DUPLICATE KEY UPDATE entity_key = VALUES(entity_key), updated_ymdhis = VALUES(updated_ymdhis), is_deleted = 0, is_active = 1;

INSERT INTO lupo_edges (edge_id, left_object_type, left_object_id, right_object_type, right_object_id, edge_type, channel_id, channel_key, weight_score, sort_num, actor_id, is_deleted, deleted_ymdhis, created_ymdhis, updated_ymdhis)
VALUES (910030, 'channel', 0, 'content', 5015, 'HAS_CONTENT', 0, 'system/kernel', 0, 0, NULL, 0, 0, @now, @now)
ON DUPLICATE KEY UPDATE left_object_id = VALUES(left_object_id), right_object_id = VALUES(right_object_id), edge_type = VALUES(edge_type), updated_ymdhis = VALUES(updated_ymdhis), is_deleted = 0, deleted_ymdhis = 0;

INSERT INTO lupo_edges (edge_id, left_object_type, left_object_id, right_object_type, right_object_id, edge_type, channel_id, channel_key, weight_score, sort_num, actor_id, is_deleted, deleted_ymdhis, created_ymdhis, updated_ymdhis)
VALUES (910031, 'channel', 51, 'content', 5015, 'HAS_CONTENT', 51, '51', 0, 0, NULL, 0, 0, @now, @now)
ON DUPLICATE KEY UPDATE left_object_id = VALUES(left_object_id), right_object_id = VALUES(right_object_id), edge_type = VALUES(edge_type), updated_ymdhis = VALUES(updated_ymdhis), is_deleted = 0, deleted_ymdhis = 0;

INSERT INTO lupo_contents (
    content_id, content_parent_id, federation_node_id, department_id, actor_id, title, slug, custom_path, description, seo_keywords, body,
    content_type, format, content_url, default_collection_id, source_url, source_title, is_template, status, visibility, view_count, share_count,
    created_ymdhis, utc_cycle, triage_status, triage_notes, updated_ymdhis, is_deleted, is_active, deleted_ymdhis, content_sections, version_number,
    file_path_from_root, file_last_modified_system_version, file_last_modified_utc, tags, dialog_notes
) VALUES (
    5016, NULL, 1, NULL, NULL, 'DOCTRINE TABLES TRANSITION NOTE', 'docs-doctrine-doctrinetablestransitionnote', NULL, NULL, NULL, NULL,
    'article', 'markdown', NULL, 0, NULL, NULL, 0, 'published', 'public', 0, 0,
    @now, 'seed', 'untriaged', NULL, @now, 0, 1, NULL, NULL, 1,
    'docs/doctrine/DOCTRINE_TABLES_TRANSITION_NOTE.md', '4.0.16', 20260217230000, NULL, NULL
) ON DUPLICATE KEY UPDATE file_path_from_root = VALUES(file_path_from_root), file_last_modified_system_version = VALUES(file_last_modified_system_version), file_last_modified_utc = VALUES(file_last_modified_utc), title = VALUES(title), updated_ymdhis = VALUES(updated_ymdhis), is_deleted = 0, is_active = 1;

INSERT INTO lupo_unified_registry (unified_registry_id, entity_type, entity_index, entity_key, entity_name, entity_table, federation_node_id, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis, is_active, is_kernel, metadata_json)
VALUES (9050016, 'content', 5016, 'docs/doctrine/DOCTRINE_TABLES_TRANSITION_NOTE.md', 'DOCTRINE TABLES TRANSITION NOTE', 'lupo_contents', 1, @now, @now, 0, NULL, 1, 0, NULL)
ON DUPLICATE KEY UPDATE entity_key = VALUES(entity_key), updated_ymdhis = VALUES(updated_ymdhis), is_deleted = 0, is_active = 1;

INSERT INTO lupo_edges (edge_id, left_object_type, left_object_id, right_object_type, right_object_id, edge_type, channel_id, channel_key, weight_score, sort_num, actor_id, is_deleted, deleted_ymdhis, created_ymdhis, updated_ymdhis)
VALUES (910032, 'channel', 0, 'content', 5016, 'HAS_CONTENT', 0, 'system/kernel', 0, 0, NULL, 0, 0, @now, @now)
ON DUPLICATE KEY UPDATE left_object_id = VALUES(left_object_id), right_object_id = VALUES(right_object_id), edge_type = VALUES(edge_type), updated_ymdhis = VALUES(updated_ymdhis), is_deleted = 0, deleted_ymdhis = 0;

INSERT INTO lupo_edges (edge_id, left_object_type, left_object_id, right_object_type, right_object_id, edge_type, channel_id, channel_key, weight_score, sort_num, actor_id, is_deleted, deleted_ymdhis, created_ymdhis, updated_ymdhis)
VALUES (910033, 'channel', 51, 'content', 5016, 'HAS_CONTENT', 51, '51', 0, 0, NULL, 0, 0, @now, @now)
ON DUPLICATE KEY UPDATE left_object_id = VALUES(left_object_id), right_object_id = VALUES(right_object_id), edge_type = VALUES(edge_type), updated_ymdhis = VALUES(updated_ymdhis), is_deleted = 0, deleted_ymdhis = 0;

INSERT INTO lupo_contents (
    content_id, content_parent_id, federation_node_id, department_id, actor_id, title, slug, custom_path, description, seo_keywords, body,
    content_type, format, content_url, default_collection_id, source_url, source_title, is_template, status, visibility, view_count, share_count,
    created_ymdhis, utc_cycle, triage_status, triage_notes, updated_ymdhis, is_deleted, is_active, deleted_ymdhis, content_sections, version_number,
    file_path_from_root, file_last_modified_system_version, file_last_modified_utc, tags, dialog_notes
) VALUES (
    5017, NULL, 1, NULL, NULL, 'ETHICAL STATE MARKERS DOCTRINE', 'docs-doctrine-ethicalstatemarkersdoctrine', NULL, NULL, NULL, NULL,
    'article', 'markdown', NULL, 0, NULL, NULL, 0, 'published', 'public', 0, 0,
    @now, 'seed', 'untriaged', NULL, @now, 0, 1, NULL, NULL, 1,
    'docs/doctrine/ETHICAL_STATE_MARKERS_DOCTRINE.md', '4.0.16', 20260217230000, NULL, NULL
) ON DUPLICATE KEY UPDATE file_path_from_root = VALUES(file_path_from_root), file_last_modified_system_version = VALUES(file_last_modified_system_version), file_last_modified_utc = VALUES(file_last_modified_utc), title = VALUES(title), updated_ymdhis = VALUES(updated_ymdhis), is_deleted = 0, is_active = 1;

INSERT INTO lupo_unified_registry (unified_registry_id, entity_type, entity_index, entity_key, entity_name, entity_table, federation_node_id, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis, is_active, is_kernel, metadata_json)
VALUES (9050017, 'content', 5017, 'docs/doctrine/ETHICAL_STATE_MARKERS_DOCTRINE.md', 'ETHICAL STATE MARKERS DOCTRINE', 'lupo_contents', 1, @now, @now, 0, NULL, 1, 0, NULL)
ON DUPLICATE KEY UPDATE entity_key = VALUES(entity_key), updated_ymdhis = VALUES(updated_ymdhis), is_deleted = 0, is_active = 1;

INSERT INTO lupo_edges (edge_id, left_object_type, left_object_id, right_object_type, right_object_id, edge_type, channel_id, channel_key, weight_score, sort_num, actor_id, is_deleted, deleted_ymdhis, created_ymdhis, updated_ymdhis)
VALUES (910034, 'channel', 0, 'content', 5017, 'HAS_CONTENT', 0, 'system/kernel', 0, 0, NULL, 0, 0, @now, @now)
ON DUPLICATE KEY UPDATE left_object_id = VALUES(left_object_id), right_object_id = VALUES(right_object_id), edge_type = VALUES(edge_type), updated_ymdhis = VALUES(updated_ymdhis), is_deleted = 0, deleted_ymdhis = 0;

INSERT INTO lupo_edges (edge_id, left_object_type, left_object_id, right_object_type, right_object_id, edge_type, channel_id, channel_key, weight_score, sort_num, actor_id, is_deleted, deleted_ymdhis, created_ymdhis, updated_ymdhis)
VALUES (910035, 'channel', 51, 'content', 5017, 'HAS_CONTENT', 51, '51', 0, 0, NULL, 0, 0, @now, @now)
ON DUPLICATE KEY UPDATE left_object_id = VALUES(left_object_id), right_object_id = VALUES(right_object_id), edge_type = VALUES(edge_type), updated_ymdhis = VALUES(updated_ymdhis), is_deleted = 0, deleted_ymdhis = 0;

INSERT INTO lupo_contents (
    content_id, content_parent_id, federation_node_id, department_id, actor_id, title, slug, custom_path, description, seo_keywords, body,
    content_type, format, content_url, default_collection_id, source_url, source_title, is_template, status, visibility, view_count, share_count,
    created_ymdhis, utc_cycle, triage_status, triage_notes, updated_ymdhis, is_deleted, is_active, deleted_ymdhis, content_sections, version_number,
    file_path_from_root, file_last_modified_system_version, file_last_modified_utc, tags, dialog_notes
) VALUES (
    5018, NULL, 1, NULL, NULL, 'FILESYSTEM MIGRATION GUIDE', 'docs-doctrine-filesystemmigrationguide', NULL, NULL, NULL, NULL,
    'article', 'markdown', NULL, 0, NULL, NULL, 0, 'published', 'public', 0, 0,
    @now, 'seed', 'untriaged', NULL, @now, 0, 1, NULL, NULL, 1,
    'docs/doctrine/FILESYSTEM_MIGRATION_GUIDE.md', '4.0.16', 20260217230000, NULL, NULL
) ON DUPLICATE KEY UPDATE file_path_from_root = VALUES(file_path_from_root), file_last_modified_system_version = VALUES(file_last_modified_system_version), file_last_modified_utc = VALUES(file_last_modified_utc), title = VALUES(title), updated_ymdhis = VALUES(updated_ymdhis), is_deleted = 0, is_active = 1;

INSERT INTO lupo_unified_registry (unified_registry_id, entity_type, entity_index, entity_key, entity_name, entity_table, federation_node_id, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis, is_active, is_kernel, metadata_json)
VALUES (9050018, 'content', 5018, 'docs/doctrine/FILESYSTEM_MIGRATION_GUIDE.md', 'FILESYSTEM MIGRATION GUIDE', 'lupo_contents', 1, @now, @now, 0, NULL, 1, 0, NULL)
ON DUPLICATE KEY UPDATE entity_key = VALUES(entity_key), updated_ymdhis = VALUES(updated_ymdhis), is_deleted = 0, is_active = 1;

INSERT INTO lupo_edges (edge_id, left_object_type, left_object_id, right_object_type, right_object_id, edge_type, channel_id, channel_key, weight_score, sort_num, actor_id, is_deleted, deleted_ymdhis, created_ymdhis, updated_ymdhis)
VALUES (910036, 'channel', 0, 'content', 5018, 'HAS_CONTENT', 0, 'system/kernel', 0, 0, NULL, 0, 0, @now, @now)
ON DUPLICATE KEY UPDATE left_object_id = VALUES(left_object_id), right_object_id = VALUES(right_object_id), edge_type = VALUES(edge_type), updated_ymdhis = VALUES(updated_ymdhis), is_deleted = 0, deleted_ymdhis = 0;

INSERT INTO lupo_edges (edge_id, left_object_type, left_object_id, right_object_type, right_object_id, edge_type, channel_id, channel_key, weight_score, sort_num, actor_id, is_deleted, deleted_ymdhis, created_ymdhis, updated_ymdhis)
VALUES (910037, 'channel', 51, 'content', 5018, 'HAS_CONTENT', 51, '51', 0, 0, NULL, 0, 0, @now, @now)
ON DUPLICATE KEY UPDATE left_object_id = VALUES(left_object_id), right_object_id = VALUES(right_object_id), edge_type = VALUES(edge_type), updated_ymdhis = VALUES(updated_ymdhis), is_deleted = 0, deleted_ymdhis = 0;

INSERT INTO lupo_contents (
    content_id, content_parent_id, federation_node_id, department_id, actor_id, title, slug, custom_path, description, seo_keywords, body,
    content_type, format, content_url, default_collection_id, source_url, source_title, is_template, status, visibility, view_count, share_count,
    created_ymdhis, utc_cycle, triage_status, triage_notes, updated_ymdhis, is_deleted, is_active, deleted_ymdhis, content_sections, version_number,
    file_path_from_root, file_last_modified_system_version, file_last_modified_utc, tags, dialog_notes
) VALUES (
    5019, NULL, 1, NULL, NULL, 'FLP COUNCILS AS CHANNELS', 'docs-doctrine-flip-flpcouncilsaschannels', NULL, NULL, NULL, NULL,
    'article', 'markdown', NULL, 0, NULL, NULL, 0, 'published', 'public', 0, 0,
    @now, 'seed', 'untriaged', NULL, @now, 0, 1, NULL, NULL, 1,
    'docs/doctrine/FLIP/FLP_COUNCILS_AS_CHANNELS.md', '4.0.16', 0, NULL, NULL
) ON DUPLICATE KEY UPDATE file_path_from_root = VALUES(file_path_from_root), file_last_modified_system_version = VALUES(file_last_modified_system_version), file_last_modified_utc = VALUES(file_last_modified_utc), title = VALUES(title), updated_ymdhis = VALUES(updated_ymdhis), is_deleted = 0, is_active = 1;

INSERT INTO lupo_unified_registry (unified_registry_id, entity_type, entity_index, entity_key, entity_name, entity_table, federation_node_id, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis, is_active, is_kernel, metadata_json)
VALUES (9050019, 'content', 5019, 'docs/doctrine/FLIP/FLP_COUNCILS_AS_CHANNELS.md', 'FLP COUNCILS AS CHANNELS', 'lupo_contents', 1, @now, @now, 0, NULL, 1, 0, NULL)
ON DUPLICATE KEY UPDATE entity_key = VALUES(entity_key), updated_ymdhis = VALUES(updated_ymdhis), is_deleted = 0, is_active = 1;

INSERT INTO lupo_edges (edge_id, left_object_type, left_object_id, right_object_type, right_object_id, edge_type, channel_id, channel_key, weight_score, sort_num, actor_id, is_deleted, deleted_ymdhis, created_ymdhis, updated_ymdhis)
VALUES (910038, 'channel', 0, 'content', 5019, 'HAS_CONTENT', 0, 'system/kernel', 0, 0, NULL, 0, 0, @now, @now)
ON DUPLICATE KEY UPDATE left_object_id = VALUES(left_object_id), right_object_id = VALUES(right_object_id), edge_type = VALUES(edge_type), updated_ymdhis = VALUES(updated_ymdhis), is_deleted = 0, deleted_ymdhis = 0;

INSERT INTO lupo_edges (edge_id, left_object_type, left_object_id, right_object_type, right_object_id, edge_type, channel_id, channel_key, weight_score, sort_num, actor_id, is_deleted, deleted_ymdhis, created_ymdhis, updated_ymdhis)
VALUES (910039, 'channel', 51, 'content', 5019, 'HAS_CONTENT', 51, '51', 0, 0, NULL, 0, 0, @now, @now)
ON DUPLICATE KEY UPDATE left_object_id = VALUES(left_object_id), right_object_id = VALUES(right_object_id), edge_type = VALUES(edge_type), updated_ymdhis = VALUES(updated_ymdhis), is_deleted = 0, deleted_ymdhis = 0;

INSERT INTO lupo_contents (
    content_id, content_parent_id, federation_node_id, department_id, actor_id, title, slug, custom_path, description, seo_keywords, body,
    content_type, format, content_url, default_collection_id, source_url, source_title, is_template, status, visibility, view_count, share_count,
    created_ymdhis, utc_cycle, triage_status, triage_notes, updated_ymdhis, is_deleted, is_active, deleted_ymdhis, content_sections, version_number,
    file_path_from_root, file_last_modified_system_version, file_last_modified_utc, tags, dialog_notes
) VALUES (
    5020, NULL, 1, NULL, NULL, 'FLP DOCTRINE BOUNDARIES', 'docs-doctrine-flip-flpdoctrineboundaries', NULL, NULL, NULL, NULL,
    'article', 'markdown', NULL, 0, NULL, NULL, 0, 'published', 'public', 0, 0,
    @now, 'seed', 'untriaged', NULL, @now, 0, 1, NULL, NULL, 1,
    'docs/doctrine/FLIP/FLP_DOCTRINE_BOUNDARIES.md', '4.0.16', 0, NULL, NULL
) ON DUPLICATE KEY UPDATE file_path_from_root = VALUES(file_path_from_root), file_last_modified_system_version = VALUES(file_last_modified_system_version), file_last_modified_utc = VALUES(file_last_modified_utc), title = VALUES(title), updated_ymdhis = VALUES(updated_ymdhis), is_deleted = 0, is_active = 1;

INSERT INTO lupo_unified_registry (unified_registry_id, entity_type, entity_index, entity_key, entity_name, entity_table, federation_node_id, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis, is_active, is_kernel, metadata_json)
VALUES (9050020, 'content', 5020, 'docs/doctrine/FLIP/FLP_DOCTRINE_BOUNDARIES.md', 'FLP DOCTRINE BOUNDARIES', 'lupo_contents', 1, @now, @now, 0, NULL, 1, 0, NULL)
ON DUPLICATE KEY UPDATE entity_key = VALUES(entity_key), updated_ymdhis = VALUES(updated_ymdhis), is_deleted = 0, is_active = 1;

INSERT INTO lupo_edges (edge_id, left_object_type, left_object_id, right_object_type, right_object_id, edge_type, channel_id, channel_key, weight_score, sort_num, actor_id, is_deleted, deleted_ymdhis, created_ymdhis, updated_ymdhis)
VALUES (910040, 'channel', 0, 'content', 5020, 'HAS_CONTENT', 0, 'system/kernel', 0, 0, NULL, 0, 0, @now, @now)
ON DUPLICATE KEY UPDATE left_object_id = VALUES(left_object_id), right_object_id = VALUES(right_object_id), edge_type = VALUES(edge_type), updated_ymdhis = VALUES(updated_ymdhis), is_deleted = 0, deleted_ymdhis = 0;

INSERT INTO lupo_edges (edge_id, left_object_type, left_object_id, right_object_type, right_object_id, edge_type, channel_id, channel_key, weight_score, sort_num, actor_id, is_deleted, deleted_ymdhis, created_ymdhis, updated_ymdhis)
VALUES (910041, 'channel', 51, 'content', 5020, 'HAS_CONTENT', 51, '51', 0, 0, NULL, 0, 0, @now, @now)
ON DUPLICATE KEY UPDATE left_object_id = VALUES(left_object_id), right_object_id = VALUES(right_object_id), edge_type = VALUES(edge_type), updated_ymdhis = VALUES(updated_ymdhis), is_deleted = 0, deleted_ymdhis = 0;

INSERT INTO lupo_contents (
    content_id, content_parent_id, federation_node_id, department_id, actor_id, title, slug, custom_path, description, seo_keywords, body,
    content_type, format, content_url, default_collection_id, source_url, source_title, is_template, status, visibility, view_count, share_count,
    created_ymdhis, utc_cycle, triage_status, triage_notes, updated_ymdhis, is_deleted, is_active, deleted_ymdhis, content_sections, version_number,
    file_path_from_root, file_last_modified_system_version, file_last_modified_utc, tags, dialog_notes
) VALUES (
    5021, NULL, 1, NULL, NULL, 'FLP EMOTIONAL AGGREGATION', 'docs-doctrine-flip-flpemotionalaggregation', NULL, NULL, NULL, NULL,
    'article', 'markdown', NULL, 0, NULL, NULL, 0, 'published', 'public', 0, 0,
    @now, 'seed', 'untriaged', NULL, @now, 0, 1, NULL, NULL, 1,
    'docs/doctrine/FLIP/FLP_EMOTIONAL_AGGREGATION.md', '4.0.16', 0, NULL, NULL
) ON DUPLICATE KEY UPDATE file_path_from_root = VALUES(file_path_from_root), file_last_modified_system_version = VALUES(file_last_modified_system_version), file_last_modified_utc = VALUES(file_last_modified_utc), title = VALUES(title), updated_ymdhis = VALUES(updated_ymdhis), is_deleted = 0, is_active = 1;

INSERT INTO lupo_unified_registry (unified_registry_id, entity_type, entity_index, entity_key, entity_name, entity_table, federation_node_id, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis, is_active, is_kernel, metadata_json)
VALUES (9050021, 'content', 5021, 'docs/doctrine/FLIP/FLP_EMOTIONAL_AGGREGATION.md', 'FLP EMOTIONAL AGGREGATION', 'lupo_contents', 1, @now, @now, 0, NULL, 1, 0, NULL)
ON DUPLICATE KEY UPDATE entity_key = VALUES(entity_key), updated_ymdhis = VALUES(updated_ymdhis), is_deleted = 0, is_active = 1;

INSERT INTO lupo_edges (edge_id, left_object_type, left_object_id, right_object_type, right_object_id, edge_type, channel_id, channel_key, weight_score, sort_num, actor_id, is_deleted, deleted_ymdhis, created_ymdhis, updated_ymdhis)
VALUES (910042, 'channel', 0, 'content', 5021, 'HAS_CONTENT', 0, 'system/kernel', 0, 0, NULL, 0, 0, @now, @now)
ON DUPLICATE KEY UPDATE left_object_id = VALUES(left_object_id), right_object_id = VALUES(right_object_id), edge_type = VALUES(edge_type), updated_ymdhis = VALUES(updated_ymdhis), is_deleted = 0, deleted_ymdhis = 0;

INSERT INTO lupo_edges (edge_id, left_object_type, left_object_id, right_object_type, right_object_id, edge_type, channel_id, channel_key, weight_score, sort_num, actor_id, is_deleted, deleted_ymdhis, created_ymdhis, updated_ymdhis)
VALUES (910043, 'channel', 51, 'content', 5021, 'HAS_CONTENT', 51, '51', 0, 0, NULL, 0, 0, @now, @now)
ON DUPLICATE KEY UPDATE left_object_id = VALUES(left_object_id), right_object_id = VALUES(right_object_id), edge_type = VALUES(edge_type), updated_ymdhis = VALUES(updated_ymdhis), is_deleted = 0, deleted_ymdhis = 0;

INSERT INTO lupo_contents (
    content_id, content_parent_id, federation_node_id, department_id, actor_id, title, slug, custom_path, description, seo_keywords, body,
    content_type, format, content_url, default_collection_id, source_url, source_title, is_template, status, visibility, view_count, share_count,
    created_ymdhis, utc_cycle, triage_status, triage_notes, updated_ymdhis, is_deleted, is_active, deleted_ymdhis, content_sections, version_number,
    file_path_from_root, file_last_modified_system_version, file_last_modified_utc, tags, dialog_notes
) VALUES (
    5022, NULL, 1, NULL, NULL, 'FLP EMOTIONAL GEOMETRY', 'docs-doctrine-flip-flpemotionalgeometry', NULL, NULL, NULL, NULL,
    'article', 'markdown', NULL, 0, NULL, NULL, 0, 'published', 'public', 0, 0,
    @now, 'seed', 'untriaged', NULL, @now, 0, 1, NULL, NULL, 1,
    'docs/doctrine/FLIP/FLP_EMOTIONAL_GEOMETRY.md', '4.0.16', 0, NULL, NULL
) ON DUPLICATE KEY UPDATE file_path_from_root = VALUES(file_path_from_root), file_last_modified_system_version = VALUES(file_last_modified_system_version), file_last_modified_utc = VALUES(file_last_modified_utc), title = VALUES(title), updated_ymdhis = VALUES(updated_ymdhis), is_deleted = 0, is_active = 1;

INSERT INTO lupo_unified_registry (unified_registry_id, entity_type, entity_index, entity_key, entity_name, entity_table, federation_node_id, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis, is_active, is_kernel, metadata_json)
VALUES (9050022, 'content', 5022, 'docs/doctrine/FLIP/FLP_EMOTIONAL_GEOMETRY.md', 'FLP EMOTIONAL GEOMETRY', 'lupo_contents', 1, @now, @now, 0, NULL, 1, 0, NULL)
ON DUPLICATE KEY UPDATE entity_key = VALUES(entity_key), updated_ymdhis = VALUES(updated_ymdhis), is_deleted = 0, is_active = 1;

INSERT INTO lupo_edges (edge_id, left_object_type, left_object_id, right_object_type, right_object_id, edge_type, channel_id, channel_key, weight_score, sort_num, actor_id, is_deleted, deleted_ymdhis, created_ymdhis, updated_ymdhis)
VALUES (910044, 'channel', 0, 'content', 5022, 'HAS_CONTENT', 0, 'system/kernel', 0, 0, NULL, 0, 0, @now, @now)
ON DUPLICATE KEY UPDATE left_object_id = VALUES(left_object_id), right_object_id = VALUES(right_object_id), edge_type = VALUES(edge_type), updated_ymdhis = VALUES(updated_ymdhis), is_deleted = 0, deleted_ymdhis = 0;

INSERT INTO lupo_edges (edge_id, left_object_type, left_object_id, right_object_type, right_object_id, edge_type, channel_id, channel_key, weight_score, sort_num, actor_id, is_deleted, deleted_ymdhis, created_ymdhis, updated_ymdhis)
VALUES (910045, 'channel', 51, 'content', 5022, 'HAS_CONTENT', 51, '51', 0, 0, NULL, 0, 0, @now, @now)
ON DUPLICATE KEY UPDATE left_object_id = VALUES(left_object_id), right_object_id = VALUES(right_object_id), edge_type = VALUES(edge_type), updated_ymdhis = VALUES(updated_ymdhis), is_deleted = 0, deleted_ymdhis = 0;

INSERT INTO lupo_contents (
    content_id, content_parent_id, federation_node_id, department_id, actor_id, title, slug, custom_path, description, seo_keywords, body,
    content_type, format, content_url, default_collection_id, source_url, source_title, is_template, status, visibility, view_count, share_count,
    created_ymdhis, utc_cycle, triage_status, triage_notes, updated_ymdhis, is_deleted, is_active, deleted_ymdhis, content_sections, version_number,
    file_path_from_root, file_last_modified_system_version, file_last_modified_utc, tags, dialog_notes
) VALUES (
    5023, NULL, 1, NULL, NULL, 'FLP ESCROW AND FUND LAYER', 'docs-doctrine-flip-flpescrowandfundlayer', NULL, NULL, NULL, NULL,
    'article', 'markdown', NULL, 0, NULL, NULL, 0, 'published', 'public', 0, 0,
    @now, 'seed', 'untriaged', NULL, @now, 0, 1, NULL, NULL, 1,
    'docs/doctrine/FLIP/FLP_ESCROW_AND_FUND_LAYER.md', '4.0.16', 0, NULL, NULL
) ON DUPLICATE KEY UPDATE file_path_from_root = VALUES(file_path_from_root), file_last_modified_system_version = VALUES(file_last_modified_system_version), file_last_modified_utc = VALUES(file_last_modified_utc), title = VALUES(title), updated_ymdhis = VALUES(updated_ymdhis), is_deleted = 0, is_active = 1;

INSERT INTO lupo_unified_registry (unified_registry_id, entity_type, entity_index, entity_key, entity_name, entity_table, federation_node_id, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis, is_active, is_kernel, metadata_json)
VALUES (9050023, 'content', 5023, 'docs/doctrine/FLIP/FLP_ESCROW_AND_FUND_LAYER.md', 'FLP ESCROW AND FUND LAYER', 'lupo_contents', 1, @now, @now, 0, NULL, 1, 0, NULL)
ON DUPLICATE KEY UPDATE entity_key = VALUES(entity_key), updated_ymdhis = VALUES(updated_ymdhis), is_deleted = 0, is_active = 1;

INSERT INTO lupo_edges (edge_id, left_object_type, left_object_id, right_object_type, right_object_id, edge_type, channel_id, channel_key, weight_score, sort_num, actor_id, is_deleted, deleted_ymdhis, created_ymdhis, updated_ymdhis)
VALUES (910046, 'channel', 0, 'content', 5023, 'HAS_CONTENT', 0, 'system/kernel', 0, 0, NULL, 0, 0, @now, @now)
ON DUPLICATE KEY UPDATE left_object_id = VALUES(left_object_id), right_object_id = VALUES(right_object_id), edge_type = VALUES(edge_type), updated_ymdhis = VALUES(updated_ymdhis), is_deleted = 0, deleted_ymdhis = 0;

INSERT INTO lupo_edges (edge_id, left_object_type, left_object_id, right_object_type, right_object_id, edge_type, channel_id, channel_key, weight_score, sort_num, actor_id, is_deleted, deleted_ymdhis, created_ymdhis, updated_ymdhis)
VALUES (910047, 'channel', 51, 'content', 5023, 'HAS_CONTENT', 51, '51', 0, 0, NULL, 0, 0, @now, @now)
ON DUPLICATE KEY UPDATE left_object_id = VALUES(left_object_id), right_object_id = VALUES(right_object_id), edge_type = VALUES(edge_type), updated_ymdhis = VALUES(updated_ymdhis), is_deleted = 0, deleted_ymdhis = 0;

INSERT INTO lupo_contents (
    content_id, content_parent_id, federation_node_id, department_id, actor_id, title, slug, custom_path, description, seo_keywords, body,
    content_type, format, content_url, default_collection_id, source_url, source_title, is_template, status, visibility, view_count, share_count,
    created_ymdhis, utc_cycle, triage_status, triage_notes, updated_ymdhis, is_deleted, is_active, deleted_ymdhis, content_sections, version_number,
    file_path_from_root, file_last_modified_system_version, file_last_modified_utc, tags, dialog_notes
) VALUES (
    5024, NULL, 1, NULL, NULL, 'FLP HETERODOX REVIEWERS', 'docs-doctrine-flip-flpheterodoxreviewers', NULL, NULL, NULL, NULL,
    'article', 'markdown', NULL, 0, NULL, NULL, 0, 'published', 'public', 0, 0,
    @now, 'seed', 'untriaged', NULL, @now, 0, 1, NULL, NULL, 1,
    'docs/doctrine/FLIP/FLP_HETERODOX_REVIEWERS.md', '4.0.16', 0, NULL, NULL
) ON DUPLICATE KEY UPDATE file_path_from_root = VALUES(file_path_from_root), file_last_modified_system_version = VALUES(file_last_modified_system_version), file_last_modified_utc = VALUES(file_last_modified_utc), title = VALUES(title), updated_ymdhis = VALUES(updated_ymdhis), is_deleted = 0, is_active = 1;

INSERT INTO lupo_unified_registry (unified_registry_id, entity_type, entity_index, entity_key, entity_name, entity_table, federation_node_id, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis, is_active, is_kernel, metadata_json)
VALUES (9050024, 'content', 5024, 'docs/doctrine/FLIP/FLP_HETERODOX_REVIEWERS.md', 'FLP HETERODOX REVIEWERS', 'lupo_contents', 1, @now, @now, 0, NULL, 1, 0, NULL)
ON DUPLICATE KEY UPDATE entity_key = VALUES(entity_key), updated_ymdhis = VALUES(updated_ymdhis), is_deleted = 0, is_active = 1;

INSERT INTO lupo_edges (edge_id, left_object_type, left_object_id, right_object_type, right_object_id, edge_type, channel_id, channel_key, weight_score, sort_num, actor_id, is_deleted, deleted_ymdhis, created_ymdhis, updated_ymdhis)
VALUES (910048, 'channel', 0, 'content', 5024, 'HAS_CONTENT', 0, 'system/kernel', 0, 0, NULL, 0, 0, @now, @now)
ON DUPLICATE KEY UPDATE left_object_id = VALUES(left_object_id), right_object_id = VALUES(right_object_id), edge_type = VALUES(edge_type), updated_ymdhis = VALUES(updated_ymdhis), is_deleted = 0, deleted_ymdhis = 0;

INSERT INTO lupo_edges (edge_id, left_object_type, left_object_id, right_object_type, right_object_id, edge_type, channel_id, channel_key, weight_score, sort_num, actor_id, is_deleted, deleted_ymdhis, created_ymdhis, updated_ymdhis)
VALUES (910049, 'channel', 51, 'content', 5024, 'HAS_CONTENT', 51, '51', 0, 0, NULL, 0, 0, @now, @now)
ON DUPLICATE KEY UPDATE left_object_id = VALUES(left_object_id), right_object_id = VALUES(right_object_id), edge_type = VALUES(edge_type), updated_ymdhis = VALUES(updated_ymdhis), is_deleted = 0, deleted_ymdhis = 0;

INSERT INTO lupo_contents (
    content_id, content_parent_id, federation_node_id, department_id, actor_id, title, slug, custom_path, description, seo_keywords, body,
    content_type, format, content_url, default_collection_id, source_url, source_title, is_template, status, visibility, view_count, share_count,
    created_ymdhis, utc_cycle, triage_status, triage_notes, updated_ymdhis, is_deleted, is_active, deleted_ymdhis, content_sections, version_number,
    file_path_from_root, file_last_modified_system_version, file_last_modified_utc, tags, dialog_notes
) VALUES (
    5025, NULL, 1, NULL, NULL, 'FLP LUPOPEDIA COUNCIL SEAT', 'docs-doctrine-flip-flplupopediacouncilseat', NULL, NULL, NULL, NULL,
    'article', 'markdown', NULL, 0, NULL, NULL, 0, 'published', 'public', 0, 0,
    @now, 'seed', 'untriaged', NULL, @now, 0, 1, NULL, NULL, 1,
    'docs/doctrine/FLIP/FLP_LUPOPEDIA_COUNCIL_SEAT.md', '4.0.16', 0, NULL, NULL
) ON DUPLICATE KEY UPDATE file_path_from_root = VALUES(file_path_from_root), file_last_modified_system_version = VALUES(file_last_modified_system_version), file_last_modified_utc = VALUES(file_last_modified_utc), title = VALUES(title), updated_ymdhis = VALUES(updated_ymdhis), is_deleted = 0, is_active = 1;

INSERT INTO lupo_unified_registry (unified_registry_id, entity_type, entity_index, entity_key, entity_name, entity_table, federation_node_id, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis, is_active, is_kernel, metadata_json)
VALUES (9050025, 'content', 5025, 'docs/doctrine/FLIP/FLP_LUPOPEDIA_COUNCIL_SEAT.md', 'FLP LUPOPEDIA COUNCIL SEAT', 'lupo_contents', 1, @now, @now, 0, NULL, 1, 0, NULL)
ON DUPLICATE KEY UPDATE entity_key = VALUES(entity_key), updated_ymdhis = VALUES(updated_ymdhis), is_deleted = 0, is_active = 1;

INSERT INTO lupo_edges (edge_id, left_object_type, left_object_id, right_object_type, right_object_id, edge_type, channel_id, channel_key, weight_score, sort_num, actor_id, is_deleted, deleted_ymdhis, created_ymdhis, updated_ymdhis)
VALUES (910050, 'channel', 0, 'content', 5025, 'HAS_CONTENT', 0, 'system/kernel', 0, 0, NULL, 0, 0, @now, @now)
ON DUPLICATE KEY UPDATE left_object_id = VALUES(left_object_id), right_object_id = VALUES(right_object_id), edge_type = VALUES(edge_type), updated_ymdhis = VALUES(updated_ymdhis), is_deleted = 0, deleted_ymdhis = 0;

INSERT INTO lupo_edges (edge_id, left_object_type, left_object_id, right_object_type, right_object_id, edge_type, channel_id, channel_key, weight_score, sort_num, actor_id, is_deleted, deleted_ymdhis, created_ymdhis, updated_ymdhis)
VALUES (910051, 'channel', 51, 'content', 5025, 'HAS_CONTENT', 51, '51', 0, 0, NULL, 0, 0, @now, @now)
ON DUPLICATE KEY UPDATE left_object_id = VALUES(left_object_id), right_object_id = VALUES(right_object_id), edge_type = VALUES(edge_type), updated_ymdhis = VALUES(updated_ymdhis), is_deleted = 0, deleted_ymdhis = 0;

INSERT INTO lupo_contents (
    content_id, content_parent_id, federation_node_id, department_id, actor_id, title, slug, custom_path, description, seo_keywords, body,
    content_type, format, content_url, default_collection_id, source_url, source_title, is_template, status, visibility, view_count, share_count,
    created_ymdhis, utc_cycle, triage_status, triage_notes, updated_ymdhis, is_deleted, is_active, deleted_ymdhis, content_sections, version_number,
    file_path_from_root, file_last_modified_system_version, file_last_modified_utc, tags, dialog_notes
) VALUES (
    5026, NULL, 1, NULL, NULL, 'FLP OVERVIEW', 'docs-doctrine-flip-flpoverview', NULL, NULL, NULL, NULL,
    'article', 'markdown', NULL, 0, NULL, NULL, 0, 'published', 'public', 0, 0,
    @now, 'seed', 'untriaged', NULL, @now, 0, 1, NULL, NULL, 1,
    'docs/doctrine/FLIP/FLP_OVERVIEW.md', '4.0.16', 0, NULL, NULL
) ON DUPLICATE KEY UPDATE file_path_from_root = VALUES(file_path_from_root), file_last_modified_system_version = VALUES(file_last_modified_system_version), file_last_modified_utc = VALUES(file_last_modified_utc), title = VALUES(title), updated_ymdhis = VALUES(updated_ymdhis), is_deleted = 0, is_active = 1;

INSERT INTO lupo_unified_registry (unified_registry_id, entity_type, entity_index, entity_key, entity_name, entity_table, federation_node_id, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis, is_active, is_kernel, metadata_json)
VALUES (9050026, 'content', 5026, 'docs/doctrine/FLIP/FLP_OVERVIEW.md', 'FLP OVERVIEW', 'lupo_contents', 1, @now, @now, 0, NULL, 1, 0, NULL)
ON DUPLICATE KEY UPDATE entity_key = VALUES(entity_key), updated_ymdhis = VALUES(updated_ymdhis), is_deleted = 0, is_active = 1;

INSERT INTO lupo_edges (edge_id, left_object_type, left_object_id, right_object_type, right_object_id, edge_type, channel_id, channel_key, weight_score, sort_num, actor_id, is_deleted, deleted_ymdhis, created_ymdhis, updated_ymdhis)
VALUES (910052, 'channel', 0, 'content', 5026, 'HAS_CONTENT', 0, 'system/kernel', 0, 0, NULL, 0, 0, @now, @now)
ON DUPLICATE KEY UPDATE left_object_id = VALUES(left_object_id), right_object_id = VALUES(right_object_id), edge_type = VALUES(edge_type), updated_ymdhis = VALUES(updated_ymdhis), is_deleted = 0, deleted_ymdhis = 0;

INSERT INTO lupo_edges (edge_id, left_object_type, left_object_id, right_object_type, right_object_id, edge_type, channel_id, channel_key, weight_score, sort_num, actor_id, is_deleted, deleted_ymdhis, created_ymdhis, updated_ymdhis)
VALUES (910053, 'channel', 51, 'content', 5026, 'HAS_CONTENT', 51, '51', 0, 0, NULL, 0, 0, @now, @now)
ON DUPLICATE KEY UPDATE left_object_id = VALUES(left_object_id), right_object_id = VALUES(right_object_id), edge_type = VALUES(edge_type), updated_ymdhis = VALUES(updated_ymdhis), is_deleted = 0, deleted_ymdhis = 0;

INSERT INTO lupo_contents (
    content_id, content_parent_id, federation_node_id, department_id, actor_id, title, slug, custom_path, description, seo_keywords, body,
    content_type, format, content_url, default_collection_id, source_url, source_title, is_template, status, visibility, view_count, share_count,
    created_ymdhis, utc_cycle, triage_status, triage_notes, updated_ymdhis, is_deleted, is_active, deleted_ymdhis, content_sections, version_number,
    file_path_from_root, file_last_modified_system_version, file_last_modified_utc, tags, dialog_notes
) VALUES (
    5027, NULL, 1, NULL, NULL, 'NOTE HEADER VERSION AND MERGE', 'docs-doctrine-flip-noteheaderversionandmerge', NULL, NULL, NULL, NULL,
    'article', 'markdown', NULL, 0, NULL, NULL, 0, 'published', 'public', 0, 0,
    @now, 'seed', 'untriaged', NULL, @now, 0, 1, NULL, NULL, 1,
    'docs/doctrine/FLIP/NOTE_HEADER_VERSION_AND_MERGE.md', '4.0.17', 0, NULL, NULL
) ON DUPLICATE KEY UPDATE file_path_from_root = VALUES(file_path_from_root), file_last_modified_system_version = VALUES(file_last_modified_system_version), file_last_modified_utc = VALUES(file_last_modified_utc), title = VALUES(title), updated_ymdhis = VALUES(updated_ymdhis), is_deleted = 0, is_active = 1;

INSERT INTO lupo_unified_registry (unified_registry_id, entity_type, entity_index, entity_key, entity_name, entity_table, federation_node_id, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis, is_active, is_kernel, metadata_json)
VALUES (9050027, 'content', 5027, 'docs/doctrine/FLIP/NOTE_HEADER_VERSION_AND_MERGE.md', 'NOTE HEADER VERSION AND MERGE', 'lupo_contents', 1, @now, @now, 0, NULL, 1, 0, NULL)
ON DUPLICATE KEY UPDATE entity_key = VALUES(entity_key), updated_ymdhis = VALUES(updated_ymdhis), is_deleted = 0, is_active = 1;

INSERT INTO lupo_edges (edge_id, left_object_type, left_object_id, right_object_type, right_object_id, edge_type, channel_id, channel_key, weight_score, sort_num, actor_id, is_deleted, deleted_ymdhis, created_ymdhis, updated_ymdhis)
VALUES (910054, 'channel', 0, 'content', 5027, 'HAS_CONTENT', 0, 'system/kernel', 0, 0, NULL, 0, 0, @now, @now)
ON DUPLICATE KEY UPDATE left_object_id = VALUES(left_object_id), right_object_id = VALUES(right_object_id), edge_type = VALUES(edge_type), updated_ymdhis = VALUES(updated_ymdhis), is_deleted = 0, deleted_ymdhis = 0;

INSERT INTO lupo_edges (edge_id, left_object_type, left_object_id, right_object_type, right_object_id, edge_type, channel_id, channel_key, weight_score, sort_num, actor_id, is_deleted, deleted_ymdhis, created_ymdhis, updated_ymdhis)
VALUES (910055, 'channel', 51, 'content', 5027, 'HAS_CONTENT', 51, '51', 0, 0, NULL, 0, 0, @now, @now)
ON DUPLICATE KEY UPDATE left_object_id = VALUES(left_object_id), right_object_id = VALUES(right_object_id), edge_type = VALUES(edge_type), updated_ymdhis = VALUES(updated_ymdhis), is_deleted = 0, deleted_ymdhis = 0;

INSERT INTO lupo_contents (
    content_id, content_parent_id, federation_node_id, department_id, actor_id, title, slug, custom_path, description, seo_keywords, body,
    content_type, format, content_url, default_collection_id, source_url, source_title, is_template, status, visibility, view_count, share_count,
    created_ymdhis, utc_cycle, triage_status, triage_notes, updated_ymdhis, is_deleted, is_active, deleted_ymdhis, content_sections, version_number,
    file_path_from_root, file_last_modified_system_version, file_last_modified_utc, tags, dialog_notes
) VALUES (
    5028, NULL, 1, NULL, NULL, 'README', 'docs-doctrine-flip-readme', NULL, NULL, NULL, NULL,
    'article', 'markdown', NULL, 0, NULL, NULL, 0, 'published', 'public', 0, 0,
    @now, 'seed', 'untriaged', NULL, @now, 0, 1, NULL, NULL, 1,
    'docs/doctrine/FLIP/README.md', '4.0.16', 0, NULL, NULL
) ON DUPLICATE KEY UPDATE file_path_from_root = VALUES(file_path_from_root), file_last_modified_system_version = VALUES(file_last_modified_system_version), file_last_modified_utc = VALUES(file_last_modified_utc), title = VALUES(title), updated_ymdhis = VALUES(updated_ymdhis), is_deleted = 0, is_active = 1;

INSERT INTO lupo_unified_registry (unified_registry_id, entity_type, entity_index, entity_key, entity_name, entity_table, federation_node_id, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis, is_active, is_kernel, metadata_json)
VALUES (9050028, 'content', 5028, 'docs/doctrine/FLIP/README.md', 'README', 'lupo_contents', 1, @now, @now, 0, NULL, 1, 0, NULL)
ON DUPLICATE KEY UPDATE entity_key = VALUES(entity_key), updated_ymdhis = VALUES(updated_ymdhis), is_deleted = 0, is_active = 1;

INSERT INTO lupo_edges (edge_id, left_object_type, left_object_id, right_object_type, right_object_id, edge_type, channel_id, channel_key, weight_score, sort_num, actor_id, is_deleted, deleted_ymdhis, created_ymdhis, updated_ymdhis)
VALUES (910056, 'channel', 0, 'content', 5028, 'HAS_CONTENT', 0, 'system/kernel', 0, 0, NULL, 0, 0, @now, @now)
ON DUPLICATE KEY UPDATE left_object_id = VALUES(left_object_id), right_object_id = VALUES(right_object_id), edge_type = VALUES(edge_type), updated_ymdhis = VALUES(updated_ymdhis), is_deleted = 0, deleted_ymdhis = 0;

INSERT INTO lupo_edges (edge_id, left_object_type, left_object_id, right_object_type, right_object_id, edge_type, channel_id, channel_key, weight_score, sort_num, actor_id, is_deleted, deleted_ymdhis, created_ymdhis, updated_ymdhis)
VALUES (910057, 'channel', 51, 'content', 5028, 'HAS_CONTENT', 51, '51', 0, 0, NULL, 0, 0, @now, @now)
ON DUPLICATE KEY UPDATE left_object_id = VALUES(left_object_id), right_object_id = VALUES(right_object_id), edge_type = VALUES(edge_type), updated_ymdhis = VALUES(updated_ymdhis), is_deleted = 0, deleted_ymdhis = 0;

INSERT INTO lupo_contents (
    content_id, content_parent_id, federation_node_id, department_id, actor_id, title, slug, custom_path, description, seo_keywords, body,
    content_type, format, content_url, default_collection_id, source_url, source_title, is_template, status, visibility, view_count, share_count,
    created_ymdhis, utc_cycle, triage_status, triage_notes, updated_ymdhis, is_deleted, is_active, deleted_ymdhis, content_sections, version_number,
    file_path_from_root, file_last_modified_system_version, file_last_modified_utc, tags, dialog_notes
) VALUES (
    5029, NULL, 1, NULL, NULL, 'IMPORT FROM CRAFTY TROUBLESHOOTING', 'docs-doctrine-importfromcraftytroubleshooting', NULL, NULL, NULL, NULL,
    'article', 'markdown', NULL, 0, NULL, NULL, 0, 'published', 'public', 0, 0,
    @now, 'seed', 'untriaged', NULL, @now, 0, 1, NULL, NULL, 1,
    'docs/doctrine/IMPORT_FROM_CRAFTY_TROUBLESHOOTING.md', '4.0.16', 20260217230000, NULL, NULL
) ON DUPLICATE KEY UPDATE file_path_from_root = VALUES(file_path_from_root), file_last_modified_system_version = VALUES(file_last_modified_system_version), file_last_modified_utc = VALUES(file_last_modified_utc), title = VALUES(title), updated_ymdhis = VALUES(updated_ymdhis), is_deleted = 0, is_active = 1;

INSERT INTO lupo_unified_registry (unified_registry_id, entity_type, entity_index, entity_key, entity_name, entity_table, federation_node_id, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis, is_active, is_kernel, metadata_json)
VALUES (9050029, 'content', 5029, 'docs/doctrine/IMPORT_FROM_CRAFTY_TROUBLESHOOTING.md', 'IMPORT FROM CRAFTY TROUBLESHOOTING', 'lupo_contents', 1, @now, @now, 0, NULL, 1, 0, NULL)
ON DUPLICATE KEY UPDATE entity_key = VALUES(entity_key), updated_ymdhis = VALUES(updated_ymdhis), is_deleted = 0, is_active = 1;

INSERT INTO lupo_edges (edge_id, left_object_type, left_object_id, right_object_type, right_object_id, edge_type, channel_id, channel_key, weight_score, sort_num, actor_id, is_deleted, deleted_ymdhis, created_ymdhis, updated_ymdhis)
VALUES (910058, 'channel', 0, 'content', 5029, 'HAS_CONTENT', 0, 'system/kernel', 0, 0, NULL, 0, 0, @now, @now)
ON DUPLICATE KEY UPDATE left_object_id = VALUES(left_object_id), right_object_id = VALUES(right_object_id), edge_type = VALUES(edge_type), updated_ymdhis = VALUES(updated_ymdhis), is_deleted = 0, deleted_ymdhis = 0;

INSERT INTO lupo_edges (edge_id, left_object_type, left_object_id, right_object_type, right_object_id, edge_type, channel_id, channel_key, weight_score, sort_num, actor_id, is_deleted, deleted_ymdhis, created_ymdhis, updated_ymdhis)
VALUES (910059, 'channel', 51, 'content', 5029, 'HAS_CONTENT', 51, '51', 0, 0, NULL, 0, 0, @now, @now)
ON DUPLICATE KEY UPDATE left_object_id = VALUES(left_object_id), right_object_id = VALUES(right_object_id), edge_type = VALUES(edge_type), updated_ymdhis = VALUES(updated_ymdhis), is_deleted = 0, deleted_ymdhis = 0;

-- LILITH ANUBIS GUIDANCE and FLIP-only (ANUBIS doctrine)
INSERT INTO lupo_contents (
    content_id, content_parent_id, federation_node_id, department_id, actor_id, title, slug, custom_path, description, seo_keywords, body,
    content_type, format, content_url, default_collection_id, source_url, source_title, is_template, status, visibility, view_count, share_count,
    created_ymdhis, utc_cycle, triage_status, triage_notes, updated_ymdhis, is_deleted, is_active, deleted_ymdhis, content_sections, version_number,
    file_path_from_root, file_last_modified_system_version, file_last_modified_utc, tags, dialog_notes
) VALUES (
    5030, NULL, 1, NULL, NULL, 'LILITH ANUBIS GUIDANCE', 'docs-doctrine-anubis-lilithanubisguidance', NULL, 'LILITH heterodox guidance for ANUBIS adoption protocol.', NULL, NULL,
    'article', 'markdown', NULL, 0, NULL, NULL, 0, 'published', 'public', 0, 0,
    @now, 'seed', 'untriaged', NULL, @now, 0, 1, NULL, NULL, 1,
    'docs/doctrine/ANUBIS/LILITH_ANUBIS_GUIDANCE.md', '4.0.16', 20260217232500, '["anubis","adoption","orphan","seed"]', 'Refined ANUBIS adoption SQL for orphan message; tied to actor 1000 per user correction.'
) ON DUPLICATE KEY UPDATE file_path_from_root = VALUES(file_path_from_root), file_last_modified_system_version = VALUES(file_last_modified_system_version), file_last_modified_utc = VALUES(file_last_modified_utc), title = VALUES(title), dialog_notes = VALUES(dialog_notes), tags = VALUES(tags), updated_ymdhis = @now, is_deleted = 0, is_active = 1;

INSERT INTO lupo_unified_registry (unified_registry_id, entity_type, entity_index, entity_key, entity_name, entity_table, federation_node_id, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis, is_active, is_kernel, metadata_json)
VALUES (9050030, 'content', 5030, 'docs/doctrine/ANUBIS/LILITH_ANUBIS_GUIDANCE.md', 'LILITH ANUBIS GUIDANCE', 'lupo_contents', 1, @now, @now, 0, NULL, 1, 0, NULL)
ON DUPLICATE KEY UPDATE entity_key = VALUES(entity_key), updated_ymdhis = VALUES(updated_ymdhis), is_deleted = 0, is_active = 1;

INSERT INTO lupo_edges (edge_id, left_object_type, left_object_id, right_object_type, right_object_id, edge_type, channel_id, channel_key, weight_score, sort_num, actor_id, is_deleted, deleted_ymdhis, created_ymdhis, updated_ymdhis)
VALUES (910060, 'channel', 0, 'content', 5030, 'HAS_CONTENT', 0, 'system/kernel', 0, 0, NULL, 0, 0, @now, @now)
ON DUPLICATE KEY UPDATE left_object_id = VALUES(left_object_id), right_object_id = VALUES(right_object_id), edge_type = VALUES(edge_type), updated_ymdhis = VALUES(updated_ymdhis), is_deleted = 0, deleted_ymdhis = 0;

INSERT INTO lupo_edges (edge_id, left_object_type, left_object_id, right_object_type, right_object_id, edge_type, channel_id, channel_key, weight_score, sort_num, actor_id, is_deleted, deleted_ymdhis, created_ymdhis, updated_ymdhis)
VALUES (910061, 'channel', 51, 'content', 5030, 'HAS_CONTENT', 51, '51', 0, 0, NULL, 0, 0, @now, @now)
ON DUPLICATE KEY UPDATE left_object_id = VALUES(left_object_id), right_object_id = VALUES(right_object_id), edge_type = VALUES(edge_type), updated_ymdhis = VALUES(updated_ymdhis), is_deleted = 0, deleted_ymdhis = 0;

INSERT INTO lupo_edges (edge_id, left_object_type, left_object_id, right_object_type, right_object_id, edge_type, channel_id, channel_key, weight_score, sort_num, actor_id, is_deleted, deleted_ymdhis, created_ymdhis, updated_ymdhis)
VALUES (910062, 'channel', 42, 'content', 5030, 'HAS_CONTENT', 42, 'lupopedia-development', 0, 0, NULL, 0, 0, @now, @now)
ON DUPLICATE KEY UPDATE left_object_id = VALUES(left_object_id), right_object_id = VALUES(right_object_id), edge_type = VALUES(edge_type), updated_ymdhis = VALUES(updated_ymdhis), is_deleted = 0, deleted_ymdhis = 0;

-- LILITH_ANUBIS_GUIDANCE_FLIP (FLIP-only header extraction)
INSERT INTO lupo_contents (
    content_id, content_parent_id, federation_node_id, department_id, actor_id, title, slug, custom_path, description, seo_keywords, body,
    content_type, format, content_url, default_collection_id, source_url, source_title, is_template, status, visibility, view_count, share_count,
    created_ymdhis, utc_cycle, triage_status, triage_notes, updated_ymdhis, is_deleted, is_active, deleted_ymdhis, content_sections, version_number,
    file_path_from_root, file_last_modified_system_version, file_last_modified_utc, tags, dialog_notes
) VALUES (
    5031, NULL, 1, NULL, NULL, 'LILITH ANUBIS GUIDANCE FLIP', 'docs-doctrine-anubis-lilithanubisguidanceflip', NULL, 'FLIP-only header for LILITH_ANUBIS_GUIDANCE.md', NULL, NULL,
    'article', 'markdown', NULL, 0, NULL, NULL, 0, 'published', 'public', 0, 0,
    @now, 'seed', 'untriaged', NULL, @now, 0, 1, NULL, NULL, 1,
    'docs/doctrine/ANUBIS/LILITH_ANUBIS_GUIDANCE_FLIP.md', '4.0.16', 20260217232500, '["anubis","adoption","orphan","seed"]', 'Refined ANUBIS adoption SQL for orphan message; tied to actor 1000 per user correction.'
) ON DUPLICATE KEY UPDATE file_path_from_root = VALUES(file_path_from_root), file_last_modified_system_version = VALUES(file_last_modified_system_version), file_last_modified_utc = VALUES(file_last_modified_utc), title = VALUES(title), dialog_notes = VALUES(dialog_notes), tags = VALUES(tags), updated_ymdhis = @now, is_deleted = 0, is_active = 1;

INSERT INTO lupo_unified_registry (unified_registry_id, entity_type, entity_index, entity_key, entity_name, entity_table, federation_node_id, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis, is_active, is_kernel, metadata_json)
VALUES (9050031, 'content', 5031, 'docs/doctrine/ANUBIS/LILITH_ANUBIS_GUIDANCE_FLIP.md', 'LILITH ANUBIS GUIDANCE FLIP', 'lupo_contents', 1, @now, @now, 0, NULL, 1, 0, NULL)
ON DUPLICATE KEY UPDATE entity_key = VALUES(entity_key), updated_ymdhis = VALUES(updated_ymdhis), is_deleted = 0, is_active = 1;

INSERT INTO lupo_edges (edge_id, left_object_type, left_object_id, right_object_type, right_object_id, edge_type, channel_id, channel_key, weight_score, sort_num, actor_id, is_deleted, deleted_ymdhis, created_ymdhis, updated_ymdhis)
VALUES (910063, 'channel', 0, 'content', 5031, 'HAS_CONTENT', 0, 'system/kernel', 0, 0, NULL, 0, 0, @now, @now)
ON DUPLICATE KEY UPDATE left_object_id = VALUES(left_object_id), right_object_id = VALUES(right_object_id), edge_type = VALUES(edge_type), updated_ymdhis = VALUES(updated_ymdhis), is_deleted = 0, deleted_ymdhis = 0;

INSERT INTO lupo_edges (edge_id, left_object_type, left_object_id, right_object_type, right_object_id, edge_type, channel_id, channel_key, weight_score, sort_num, actor_id, is_deleted, deleted_ymdhis, created_ymdhis, updated_ymdhis)
VALUES (910064, 'channel', 51, 'content', 5031, 'HAS_CONTENT', 51, '51', 0, 0, NULL, 0, 0, @now, @now)
ON DUPLICATE KEY UPDATE left_object_id = VALUES(left_object_id), right_object_id = VALUES(right_object_id), edge_type = VALUES(edge_type), updated_ymdhis = VALUES(updated_ymdhis), is_deleted = 0, deleted_ymdhis = 0;

-- FLIP metadata for dialog_message_id 34 (Ara/Lilith heterodox review)
INSERT INTO lupo_contents (
    content_id, content_parent_id, federation_node_id, department_id, actor_id, title, slug, custom_path, description, seo_keywords, body,
    content_type, format, content_url, default_collection_id, source_url, source_title, is_template, status, visibility, view_count, share_count,
    created_ymdhis, utc_cycle, triage_status, triage_notes, updated_ymdhis, is_deleted, is_active, deleted_ymdhis, content_sections, version_number,
    file_path_from_root, file_last_modified_system_version, file_last_modified_utc, tags, dialog_notes
) VALUES (
    5032, NULL, 1, NULL, NULL, 'FLIP metadata: dialog 34 Ara/Lilith heterodox review', 'dialog-flip-34-ara-lilith-review', NULL, 'FLIP metadata for dialog_message_id 34 (Ara/Lilith heterodox review)', NULL, NULL,
    'article', 'markdown', NULL, 0, NULL, NULL, 0, 'published', 'public', 0, 0,
    @now, 'seed', 'untriaged', NULL, @now, 0, 1, NULL, NULL, 1,
    NULL, '4.0.16', @now,
    '["dialog","anubis","heterodox","review"]',
    'FLIP metadata for dialog_message_id 34 (Ara/Lilith heterodox review). mood_rgb=D2BEFA, recovery_event=true, kapakai=0.1'
) ON DUPLICATE KEY UPDATE file_last_modified_system_version = VALUES(file_last_modified_system_version), file_last_modified_utc = VALUES(file_last_modified_utc), dialog_notes = VALUES(dialog_notes), tags = VALUES(tags), updated_ymdhis = @now, is_deleted = 0, is_active = 1;

INSERT INTO lupo_unified_registry (unified_registry_id, entity_type, entity_index, entity_key, entity_name, entity_table, federation_node_id, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis, is_active, is_kernel, metadata_json)
VALUES (9050032, 'content', 5032, 'dialog:34', 'FLIP metadata dialog 34', 'lupo_contents', 1, @now, @now, 0, NULL, 1, 0, '{"mood_rgb":"D2BEFA","dialog_message_id":34,"atoms":{"recovery_event":true,"kapakai":0.1}}')
ON DUPLICATE KEY UPDATE entity_key = VALUES(entity_key), metadata_json = VALUES(metadata_json), updated_ymdhis = VALUES(updated_ymdhis), is_deleted = 0, is_active = 1;

INSERT INTO lupo_edges (edge_id, left_object_type, left_object_id, right_object_type, right_object_id, edge_type, channel_id, channel_key, weight_score, sort_num, actor_id, is_deleted, deleted_ymdhis, created_ymdhis, updated_ymdhis)
VALUES (910065, 'channel', 42, 'content', 5032, 'HAS_CONTENT', 42, 'lupopedia-development', 0, 0, NULL, 0, 0, @now, @now)
ON DUPLICATE KEY UPDATE left_object_id = VALUES(left_object_id), right_object_id = VALUES(right_object_id), edge_type = VALUES(edge_type), updated_ymdhis = VALUES(updated_ymdhis), is_deleted = 0, deleted_ymdhis = 0;

INSERT INTO lupo_edges (edge_id, left_object_type, left_object_id, right_object_type, right_object_id, edge_type, channel_id, channel_key, weight_score, sort_num, actor_id, is_deleted, deleted_ymdhis, created_ymdhis, updated_ymdhis)
VALUES (910066, 'channel', 0, 'content', 5032, 'HAS_CONTENT', 0, 'system/kernel', 0, 0, NULL, 0, 0, @now, @now)
ON DUPLICATE KEY UPDATE left_object_id = VALUES(left_object_id), right_object_id = VALUES(right_object_id), edge_type = VALUES(edge_type), updated_ymdhis = VALUES(updated_ymdhis), is_deleted = 0, deleted_ymdhis = 0;

INSERT INTO lupo_edges (edge_id, left_object_type, left_object_id, right_object_type, right_object_id, edge_type, channel_id, channel_key, weight_score, sort_num, actor_id, is_deleted, deleted_ymdhis, created_ymdhis, updated_ymdhis)
VALUES (910067, 'channel', 51, 'content', 5032, 'HAS_CONTENT', 51, '51', 0, 0, NULL, 0, 0, @now, @now)
ON DUPLICATE KEY UPDATE left_object_id = VALUES(left_object_id), right_object_id = VALUES(right_object_id), edge_type = VALUES(edge_type), updated_ymdhis = VALUES(updated_ymdhis), is_deleted = 0, deleted_ymdhis = 0;

-- ANUBIS-adopted recovered doctrine files (4.0.16 FLIP audit): content_id 5033-5047
INSERT INTO lupo_contents (content_id, content_parent_id, federation_node_id, department_id, actor_id, title, slug, custom_path, description, seo_keywords, body, content_type, format, content_url, default_collection_id, source_url, source_title, is_template, status, visibility, view_count, share_count, created_ymdhis, utc_cycle, triage_status, triage_notes, updated_ymdhis, is_deleted, is_active, deleted_ymdhis, content_sections, version_number, file_path_from_root, file_last_modified_system_version, file_last_modified_utc, tags, dialog_notes)
VALUES (5033, NULL, 1, NULL, NULL, 'FLIP API', 'docs-api-flip-api', NULL, NULL, NULL, NULL, 'article', 'markdown', NULL, 0, NULL, NULL, 0, 'published', 'public', 0, 0, @now, 'seed', 'untriaged', NULL, @now, 0, 1, NULL, NULL, 1, 'docs/api/FLIP_API.md', '4.0.16', @now, '["lost","orphan","doctrine"]', 'Recovered lost doctrine file via ANUBIS adoption')
ON DUPLICATE KEY UPDATE file_path_from_root = VALUES(file_path_from_root), dialog_notes = VALUES(dialog_notes), tags = VALUES(tags), updated_ymdhis = @now, is_deleted = 0;

INSERT INTO lupo_unified_registry (unified_registry_id, entity_type, entity_index, entity_key, entity_name, entity_table, federation_node_id, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis, is_active, is_kernel, metadata_json)
VALUES (9050033, 'content', 5033, 'docs/api/FLIP_API.md', 'FLIP API', 'lupo_contents', 1, @now, @now, 0, NULL, 1, 0, '{"tags":["lost","orphan","doctrine"],"mood_rgb":"FFDAB9","atoms":{"recovery_event":true}}')
ON DUPLICATE KEY UPDATE entity_key = VALUES(entity_key), metadata_json = VALUES(metadata_json), updated_ymdhis = VALUES(updated_ymdhis), is_deleted = 0;

INSERT INTO lupo_edges (edge_id, left_object_type, left_object_id, right_object_type, right_object_id, edge_type, channel_id, channel_key, weight_score, sort_num, actor_id, is_deleted, deleted_ymdhis, created_ymdhis, updated_ymdhis)
VALUES (910068, 'channel', 42, 'content', 5033, 'HAS_CONTENT', 42, 'lupopedia-development', 0, 0, NULL, 0, 0, @now, @now)
ON DUPLICATE KEY UPDATE left_object_id = VALUES(left_object_id), right_object_id = VALUES(right_object_id), updated_ymdhis = @now, is_deleted = 0;

INSERT INTO lupo_edges (edge_id, left_object_type, left_object_id, right_object_type, right_object_id, edge_type, channel_id, channel_key, weight_score, sort_num, actor_id, is_deleted, deleted_ymdhis, created_ymdhis, updated_ymdhis)
VALUES (910069, 'channel', 0, 'content', 5033, 'HAS_CONTENT', 0, 'system/kernel', 0, 0, NULL, 0, 0, @now, @now)
ON DUPLICATE KEY UPDATE left_object_id = VALUES(left_object_id), right_object_id = VALUES(right_object_id), updated_ymdhis = @now, is_deleted = 0;

INSERT INTO lupo_edges (edge_id, left_object_type, left_object_id, right_object_type, right_object_id, edge_type, channel_id, channel_key, weight_score, sort_num, actor_id, is_deleted, deleted_ymdhis, created_ymdhis, updated_ymdhis)
VALUES (910070, 'channel', 51, 'content', 5033, 'HAS_CONTENT', 51, '51', 0, 0, NULL, 0, 0, @now, @now)
ON DUPLICATE KEY UPDATE left_object_id = VALUES(left_object_id), right_object_id = VALUES(right_object_id), updated_ymdhis = @now, is_deleted = 0;

-- Channel FLIP header files (4.0.16 audit): content_id 5034-5037, entity_key channel:<id>:flip
INSERT INTO lupo_contents (content_id, content_parent_id, federation_node_id, department_id, actor_id, title, slug, custom_path, description, seo_keywords, body, content_type, format, content_url, default_collection_id, source_url, source_title, is_template, status, visibility, view_count, share_count, created_ymdhis, utc_cycle, triage_status, triage_notes, updated_ymdhis, is_deleted, is_active, deleted_ymdhis, content_sections, version_number, file_path_from_root, file_last_modified_system_version, file_last_modified_utc, tags, dialog_notes)
VALUES
(5034, NULL, 1, NULL, NULL, 'FLP CHANNEL 0', 'docs-doctrine-flip-flp-channel-0', NULL, 'Channel 0 (System Kernel) FLIP doctrine.', NULL, NULL, 'article', 'markdown', NULL, 0, NULL, NULL, 0, 'published', 'public', 0, 0, @now, 'seed', 'untriaged', NULL, @now, 0, 1, NULL, NULL, 1, 'docs/doctrine/FLIP/FLP_CHANNEL_0.md', '4.0.16', 20260218000000, '["channel","kernel","system","flip"]', NULL),
(5035, NULL, 1, NULL, NULL, 'FLP CHANNEL 42', 'docs-doctrine-flip-flp-channel-42', NULL, 'Channel 42 (Lupopedia Development / ANUBIS) FLIP doctrine.', NULL, NULL, 'article', 'markdown', NULL, 0, NULL, NULL, 0, 'published', 'public', 0, 0, @now, 'seed', 'untriaged', NULL, @now, 0, 1, NULL, NULL, 1, 'docs/doctrine/FLIP/FLP_CHANNEL_42.md', '4.0.16', 20260218000000, '["channel","lupopedia-development","anubis","flip"]', NULL),
(5036, NULL, 1, NULL, NULL, 'FLP CHANNEL 51', 'docs-doctrine-flip-flp-channel-51', NULL, 'Channel 51 (Doctrine Council) FLIP doctrine.', NULL, NULL, 'article', 'markdown', NULL, 0, NULL, NULL, 0, 'published', 'public', 0, 0, @now, 'seed', 'untriaged', NULL, @now, 0, 1, NULL, NULL, 1, 'docs/doctrine/FLIP/FLP_CHANNEL_51.md', '4.0.16', 20260218000000, '["channel","doctrine-council","flip"]', NULL),
(5037, NULL, 1, NULL, NULL, 'FLP CHANNEL 666', 'docs-doctrine-flip-flp-channel-666', NULL, 'Channel 666 (ANUBIS Quarantine) FLIP doctrine.', NULL, NULL, 'article', 'markdown', NULL, 0, NULL, NULL, 0, 'published', 'public', 0, 0, @now, 'seed', 'untriaged', NULL, @now, 0, 1, NULL, NULL, 1, 'docs/doctrine/FLIP/FLP_CHANNEL_666.md', '4.0.16', 20260218000000, '["channel","quarantine","forbidden","anubis"]', NULL)
ON DUPLICATE KEY UPDATE file_path_from_root = VALUES(file_path_from_root), file_last_modified_system_version = VALUES(file_last_modified_system_version), file_last_modified_utc = VALUES(file_last_modified_utc), title = VALUES(title), tags = VALUES(tags), updated_ymdhis = @now, is_deleted = 0, is_active = 1;

INSERT INTO lupo_unified_registry (unified_registry_id, entity_type, entity_index, entity_key, entity_name, entity_table, federation_node_id, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis, is_active, is_kernel, metadata_json)
VALUES
(9050034, 'content', 5034, 'channel:0:flip', 'FLP CHANNEL 0', 'lupo_contents', 1, @now, @now, 0, NULL, 1, 0, NULL),
(9050035, 'content', 5035, 'channel:42:flip', 'FLP CHANNEL 42', 'lupo_contents', 1, @now, @now, 0, NULL, 1, 0, NULL),
(9050036, 'content', 5036, 'channel:51:flip', 'FLP CHANNEL 51', 'lupo_contents', 1, @now, @now, 0, NULL, 1, 0, NULL),
(9050037, 'content', 5037, 'channel:666:flip', 'FLP CHANNEL 666', 'lupo_contents', 1, @now, @now, 0, NULL, 1, 0, NULL)
ON DUPLICATE KEY UPDATE entity_key = VALUES(entity_key), updated_ymdhis = VALUES(updated_ymdhis), is_deleted = 0, is_active = 1;

INSERT INTO lupo_edges (edge_id, left_object_type, left_object_id, right_object_type, right_object_id, edge_type, channel_id, channel_key, weight_score, sort_num, actor_id, is_deleted, deleted_ymdhis, created_ymdhis, updated_ymdhis)
VALUES
(910071, 'channel', 0, 'content', 5034, 'HAS_CONTENT', 0, 'system/kernel', 0, 0, NULL, 0, 0, @now, @now),
(910072, 'channel', 51, 'content', 5034, 'HAS_CONTENT', 51, '51', 0, 0, NULL, 0, 0, @now, @now),
(910073, 'channel', 0, 'content', 5035, 'HAS_CONTENT', 0, 'system/kernel', 0, 0, NULL, 0, 0, @now, @now),
(910074, 'channel', 51, 'content', 5035, 'HAS_CONTENT', 51, '51', 0, 0, NULL, 0, 0, @now, @now),
(910075, 'channel', 42, 'content', 5035, 'HAS_CONTENT', 42, 'lupopedia-development', 0, 0, NULL, 0, 0, @now, @now),
(910076, 'channel', 0, 'content', 5036, 'HAS_CONTENT', 0, 'system/kernel', 0, 0, NULL, 0, 0, @now, @now),
(910077, 'channel', 51, 'content', 5036, 'HAS_CONTENT', 51, '51', 0, 0, NULL, 0, 0, @now, @now),
(910078, 'channel', 0, 'content', 5037, 'HAS_CONTENT', 0, 'system/kernel', 0, 0, NULL, 0, 0, @now, @now),
(910079, 'channel', 51, 'content', 5037, 'HAS_CONTENT', 51, '51', 0, 0, NULL, 0, 0, @now, @now),
(910080, 'channel', 666, 'content', 5037, 'HAS_CONTENT', 666, 'anubis-quarantine', 0, 0, NULL, 0, 0, @now, @now)
ON DUPLICATE KEY UPDATE left_object_id = VALUES(left_object_id), right_object_id = VALUES(right_object_id), edge_type = VALUES(edge_type), updated_ymdhis = VALUES(updated_ymdhis), is_deleted = 0, deleted_ymdhis = 0;

-- 4.0.17: Diagnostics and compatibility note (content_id 5038); governance dialog (message 63) on channel 51.
INSERT INTO lupo_contents (content_id, content_parent_id, federation_node_id, department_id, actor_id, title, slug, custom_path, description, seo_keywords, body, content_type, format, content_url, default_collection_id, source_url, source_title, is_template, status, visibility, view_count, share_count, created_ymdhis, utc_cycle, triage_status, triage_notes, updated_ymdhis, is_deleted, is_active, deleted_ymdhis, content_sections, version_number, file_path_from_root, file_last_modified_system_version, file_last_modified_utc, tags, dialog_notes)
VALUES (5038, NULL, 1, NULL, NULL, '4.0.17 Diagnostics and Compatibility', 'docs-doctrine-4-0-17-diagnostics', NULL, 'Android/Messenger behavior and Crafty Syntax 3.7.5 compatibility notes for 4.0.17.', NULL, NULL, 'article', 'markdown', NULL, 0, NULL, NULL, 0, 'published', 'public', 0, 0, @now, 'seed', 'untriaged', NULL, @now, 0, 1, NULL, NULL, 1, 'docs/doctrine/4.0.17_DIAGNOSTICS_AND_COMPATIBILITY.md', '4.0.17', 20260218000000, '["diagnostics","compatibility","4.0.17","crafty","android"]', NULL)
ON DUPLICATE KEY UPDATE file_path_from_root = VALUES(file_path_from_root), file_last_modified_system_version = VALUES(file_last_modified_system_version), file_last_modified_utc = VALUES(file_last_modified_utc), title = VALUES(title), description = VALUES(description), tags = VALUES(tags), updated_ymdhis = @now, is_deleted = 0, is_active = 1;

INSERT INTO lupo_unified_registry (unified_registry_id, entity_type, entity_index, entity_key, entity_name, entity_table, federation_node_id, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis, is_active, is_kernel, metadata_json)
VALUES (9050038, 'content', 5038, 'docs/doctrine/4.0.17_DIAGNOSTICS_AND_COMPATIBILITY.md', '4.0.17 Diagnostics and Compatibility', 'lupo_contents', 1, @now, @now, 0, NULL, 1, 0, NULL)
ON DUPLICATE KEY UPDATE entity_key = VALUES(entity_key), updated_ymdhis = VALUES(updated_ymdhis), is_deleted = 0, is_active = 1;

INSERT INTO lupo_edges (edge_id, left_object_type, left_object_id, right_object_type, right_object_id, edge_type, channel_id, channel_key, weight_score, sort_num, actor_id, is_deleted, deleted_ymdhis, created_ymdhis, updated_ymdhis)
VALUES (910081, 'channel', 51, 'content', 5038, 'HAS_CONTENT', 51, '51', 0, 0, NULL, 0, 0, @now, @now), (910082, 'channel', 0, 'content', 5038, 'HAS_CONTENT', 0, 'system/kernel', 0, 0, NULL, 0, 0, @now, @now)
ON DUPLICATE KEY UPDATE left_object_id = VALUES(left_object_id), right_object_id = VALUES(right_object_id), edge_type = VALUES(edge_type), updated_ymdhis = VALUES(updated_ymdhis), is_deleted = 0, deleted_ymdhis = 0;

INSERT INTO lupo_emotional_frameworks (`framework_name`, `description`, `is_default`, `created_ymdhis`, `updated_ymdhis`) VALUES ('contextual_holism', 'Emotions inseparable from situation, history, relationship, and culture.', 0, 20250101000000, 20250101000000);

INSERT INTO lupo_federation_nodes (`federation_node_id`, `node_base_url`, `default_department_id`, `node_name`, `node_description`, `node_contact`, `meta_json`, `content_count`, `atom_count`, `hashtag_count`, `actor_count`, `last_sync_ymdhis`, `trust_level`, `status`, `is_deleted`, `deleted_ymdhis`, `created_ymdhis`, `updated_ymdhis`, `active_theme_slug`) VALUES (0, 'https://lupopedia.com', NULL, 'Lupopedia Root Node', 'Primary Lupopedia installation (self)', 'admin@lupopedia.com', '{"self": true, "version": "1.0"}', 0, 0, 0, 0, 0, 2, 1, 0, 0, 20250101000000, 20250101000000, 'default');

INSERT INTO lupo_sessions (`session_id`, `federation_node_id`, `actor_id`, `ip_address`, `user_agent`, `device_id`, `device_type`, `auth_method`, `auth_provider`, `security_level`, `is_active`, `is_expired`, `is_revoked`, `session_data`, `metadata`, `login_ymdhis`, `last_seen_ymdhis`, `expires_ymdhis`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`) VALUES ('h92kjgggec1h7cfo13g7jp4m1p', 1, 2, '::1', 'Lupopedia Seed Session', NULL, 'desktop', 'password', 'local', 'high', 0, 0, 1, NULL, NULL, 20260113023320, 20260113023414, 20260114023320, 20260113023320, 20260113023414, 0, NULL);

-- Actor/agent doctrine: ALTER lupo_actors AUTO_INCREMENT = 10000
-- -----------------------------------------------------------------------------
ALTER TABLE lupo_actors AUTO_INCREMENT = 10000;

-- -----------------------------------------------------------------------------
-- Collection 0 (Lupopedia System Collection) and default top-level tabs
-- Idempotent: Collection 0 ON DUPLICATE KEY UPDATE; tabs ON DUPLICATE KEY UPDATE.
-- -----------------------------------------------------------------------------
-- Collection 0: insert if missing, else update (idempotent)
INSERT INTO lupo_collections (
    collection_id, federations_node_id, actor_id, department_id, name, slug, color, description,
    sort_order, properties, published_ymdhis, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis, parent_id
) VALUES (
    0, @node_id, NULL, NULL, 'Lupopedia System Collection', 'lupopedia-system', '666666', NULL,
    0, NULL, NULL, @now, @now, 0, NULL, NULL
) ON DUPLICATE KEY UPDATE
    name = VALUES(name),
    slug = VALUES(slug),
    description = VALUES(description),
    updated_ymdhis = @now,
    is_deleted = 0,
    deleted_ymdhis = NULL;

-- Nine default top-level tabs for collection_id = 0 (no content, no sub-tabs)
INSERT INTO lupo_collection_tabs (
    collection_tab_id, collection_tab_parent_id, collection_id, federations_node_id, department_id, user_id,
    sort_order, name, slug, color, description, is_hidden, created_ymdhis, updated_ymdhis, is_active, is_deleted, deleted_ymdhis
) VALUES
(1, NULL, 0, @node_id, NULL, NULL, 1, 'Overview', 'overview', '4caf50', NULL, 0, @now, @now, 1, 0, NULL),
(2, NULL, 0, @node_id, NULL, NULL, 2, 'Doctrine', 'doctrine', '4caf50', NULL, 0, @now, @now, 1, 0, NULL),
(3, NULL, 0, @node_id, NULL, NULL, 3, 'Architecture', 'architecture', '4caf50', NULL, 0, @now, @now, 1, 0, NULL),
(4, NULL, 0, @node_id, NULL, NULL, 4, 'Schema', 'schema', '4caf50', NULL, 0, @now, @now, 1, 0, NULL),
(5, NULL, 0, @node_id, NULL, NULL, 5, 'Agents', 'agents', '4caf50', NULL, 0, @now, @now, 1, 0, NULL),
(6, NULL, 0, @node_id, NULL, NULL, 6, 'UI-UX', 'ui-ux', '4caf50', NULL, 0, @now, @now, 1, 0, NULL),
(7, NULL, 0, @node_id, NULL, NULL, 7, 'Developer Guide', 'developer-guide', '4caf50', NULL, 0, @now, @now, 1, 0, NULL),
(8, NULL, 0, @node_id, NULL, NULL, 8, 'History', 'history', '4caf50', NULL, 0, @now, @now, 1, 0, NULL),
(9, NULL, 0, @node_id, NULL, NULL, 9, 'Appendix', 'appendix', '4caf50', NULL, 0, @now, @now, 1, 0, NULL)
ON DUPLICATE KEY UPDATE
    name = VALUES(name),
    slug = VALUES(slug),
    sort_order = VALUES(sort_order),
    updated_ymdhis = @now,
    is_active = 1,
    is_deleted = 0,
    deleted_ymdhis = NULL;

-- -----------------------------------------------------------------------------
-- Collection 0: minimal starter content per tab (lupo_contents) and tab map
-- Idempotent: ON DUPLICATE KEY UPDATE on (federation_node_id, slug) and (collection_tab_id, item_type, item_id).
-- -----------------------------------------------------------------------------
INSERT INTO lupo_contents (
    content_id, content_parent_id, federation_node_id, department_id, actor_id, title, slug, custom_path, description, seo_keywords, body,
    content_type, format, content_url, default_collection_id, source_url, source_title, is_template, status, visibility, view_count, share_count,
    created_ymdhis, utc_cycle, triage_status, triage_notes, updated_ymdhis, is_deleted, is_active, deleted_ymdhis, content_sections, version_number, file_path_from_root, tags, dialog_notes
) VALUES
(1001, NULL, @node_id, NULL, NULL, 'Overview', 'overview', NULL, NULL, NULL, 'This is starter content for the Overview section. You can edit this in the admin panel.', 'article', 'markdown', NULL, 0, NULL, NULL, 0, 'published', 'public', 0, 0, @now, 'seed', 'untriaged', NULL, @now, 0, 1, NULL, NULL, 1, NULL, NULL, NULL),
(1002, NULL, @node_id, NULL, NULL, 'Doctrine', 'doctrine', NULL, NULL, NULL, 'This is starter content for the Doctrine section. You can edit this in the admin panel.', 'article', 'markdown', NULL, 0, NULL, NULL, 0, 'published', 'public', 0, 0, @now, 'seed', 'untriaged', NULL, @now, 0, 1, NULL, NULL, 1, NULL, NULL, NULL),
(1003, NULL, @node_id, NULL, NULL, 'Architecture', 'architecture', NULL, NULL, NULL, 'This is starter content for the Architecture section. You can edit this in the admin panel.', 'article', 'markdown', NULL, 0, NULL, NULL, 0, 'published', 'public', 0, 0, @now, 'seed', 'untriaged', NULL, @now, 0, 1, NULL, NULL, 1, NULL, NULL, NULL),
(1004, NULL, @node_id, NULL, NULL, 'Schema', 'schema', NULL, NULL, NULL, 'This is starter content for the Schema section. You can edit this in the admin panel.', 'article', 'markdown', NULL, 0, NULL, NULL, 0, 'published', 'public', 0, 0, @now, 'seed', 'untriaged', NULL, @now, 0, 1, NULL, NULL, 1, NULL, NULL, NULL),
(1005, NULL, @node_id, NULL, NULL, 'Agents', 'agents', NULL, NULL, NULL, 'This is starter content for the Agents section. You can edit this in the admin panel.', 'article', 'markdown', NULL, 0, NULL, NULL, 0, 'published', 'public', 0, 0, @now, 'seed', 'untriaged', NULL, @now, 0, 1, NULL, NULL, 1, NULL, NULL, NULL),
(1006, NULL, @node_id, NULL, NULL, 'UI-UX', 'ui-ux', NULL, NULL, NULL, 'This is starter content for the UI-UX section. You can edit this in the admin panel.', 'article', 'markdown', NULL, 0, NULL, NULL, 0, 'published', 'public', 0, 0, @now, 'seed', 'untriaged', NULL, @now, 0, 1, NULL, NULL, 1, NULL, NULL, NULL),
(1007, NULL, @node_id, NULL, NULL, 'Developer Guide', 'developer-guide', NULL, NULL, NULL, 'This is starter content for the Developer Guide section. You can edit this in the admin panel.', 'article', 'markdown', NULL, 0, NULL, NULL, 0, 'published', 'public', 0, 0, @now, 'seed', 'untriaged', NULL, @now, 0, 1, NULL, NULL, 1, NULL, NULL, NULL),
(1008, NULL, @node_id, NULL, NULL, 'History', 'history', NULL, NULL, NULL, 'This is starter content for the History section. You can edit this in the admin panel.', 'article', 'markdown', NULL, 0, NULL, NULL, 0, 'published', 'public', 0, 0, @now, 'seed', 'untriaged', NULL, @now, 0, 1, NULL, NULL, 1, NULL, NULL, NULL),
(1009, NULL, @node_id, NULL, NULL, 'Appendix', 'appendix', NULL, NULL, NULL, 'This is starter content for the Appendix section. You can edit this in the admin panel.', 'article', 'markdown', NULL, 0, NULL, NULL, 0, 'published', 'public', 0, 0, @now, 'seed', 'untriaged', NULL, @now, 0, 1, NULL, NULL, 1, NULL, NULL, NULL)
ON DUPLICATE KEY UPDATE
    title = VALUES(title),
    body = VALUES(body),
    default_collection_id = VALUES(default_collection_id),
    status = VALUES(status),
    updated_ymdhis = @now,
    is_deleted = 0,
    is_active = 1;

-- Map each content to its Collection 0 tab (tab_id 1–9 → content_id 1001–1009)
INSERT INTO lupo_collection_tab_map (
    collection_tab_map_id, collection_tab_id, federations_node_id, item_type, item_id, sort_order, properties, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis
) VALUES
(1001, 1, @node_id, 'content', 1001, 1, NULL, @now, @now, 0, NULL),
(1002, 2, @node_id, 'content', 1002, 1, NULL, @now, @now, 0, NULL),
(1003, 3, @node_id, 'content', 1003, 1, NULL, @now, @now, 0, NULL),
(1004, 4, @node_id, 'content', 1004, 1, NULL, @now, @now, 0, NULL),
(1005, 5, @node_id, 'content', 1005, 1, NULL, @now, @now, 0, NULL),
(1006, 6, @node_id, 'content', 1006, 1, NULL, @now, @now, 0, NULL),
(1007, 7, @node_id, 'content', 1007, 1, NULL, @now, @now, 0, NULL),
(1008, 8, @node_id, 'content', 1008, 1, NULL, @now, @now, 0, NULL),
(1009, 9, @node_id, 'content', 1009, 1, NULL, @now, @now, 0, NULL)
ON DUPLICATE KEY UPDATE
    sort_order = VALUES(sort_order),
    updated_ymdhis = @now,
    is_deleted = 0,
    deleted_ymdhis = NULL;

-- -----------------------------------------------------------------------------
-- QA / Lupopedia board: starter questions and answers for /qa/lupopedia
-- (truth_question_id is NOT auto_increment; truth_answer_id is auto_increment)
-- -----------------------------------------------------------------------------
-- Board landing question (slug = lupopedia) for /qa/lupopedia
INSERT INTO lupo_truth_questions (
    truth_question_id, truth_question_parent_id, actor_id, qtype, status, sort_num, slug, question_text,
    format, format_override, view_count, likes_count, shares_count, answer_count, last_activity_ymdhis,
    is_featured, is_verified, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis, default_collection_id
) VALUES (
    5000, NULL, 0, 'unknown', 'active', 0, 'lupopedia', 'Lupopedia Q&A',
    'text', NULL, 0, 0, 0, 0, NULL, 0, 0, @now, @now, 0, NULL, 0
);

-- Child questions under the Lupopedia board (parent_id = 5000)
INSERT INTO lupo_truth_questions (
    truth_question_id, truth_question_parent_id, actor_id, qtype, status, sort_num, slug, question_text,
    format, format_override, view_count, likes_count, shares_count, answer_count, last_activity_ymdhis,
    is_featured, is_verified, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis, default_collection_id
) VALUES
(5001, 5000, 0, 'what', 'active', 1, 'what-is-lupopedia', 'What is Lupopedia?', 'text', NULL, 0, 0, 0, 1, @now, 0, 0, @now, @now, 0, NULL, 0),
(5002, 5000, 0, 'how', 'active', 2, 'how-do-i-log-in-after-installation', 'How do I log in after installation?', 'text', NULL, 0, 0, 0, 1, @now, 0, 0, @now, @now, 0, NULL, 0),
(5003, 5000, 0, 'what', 'active', 3, 'difference-between-crafty-syntax-and-lupopedia', 'What is the difference between Crafty Syntax and Lupopedia?', 'text', NULL, 0, 0, 0, 1, @now, 0, 0, @now, @now, 0, NULL, 0),
(5004, 5000, 0, 'where', 'active', 4, 'where-to-find-system-documentation', 'Where can I find system documentation?', 'text', NULL, 0, 0, 0, 1, @now, 0, 0, @now, @now, 0, NULL, 0),
(5005, 5000, 0, 'how', 'active', 5, 'how-do-i-create-new-content', 'How do I create new content?', 'text', NULL, 0, 0, 0, 1, @now, 0, 0, @now, @now, 0, NULL, 0);

-- Placeholder answers (one per child question; truth_answer_id is auto_increment)
INSERT INTO lupo_truth_answers (
    truth_question_id, actor_id, answer_text, confidence_score, evidence_score, contradiction_flag,
    likes_count, shares_count, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis
) VALUES
(5001, 0, 'This is a placeholder answer. You can edit this in the admin panel.', 0.00, 0.00, 0, 0, 0, @now, @now, 0, NULL),
(5002, 0, 'This is a placeholder answer. You can edit this in the admin panel.', 0.00, 0.00, 0, 0, 0, @now, @now, 0, NULL),
(5003, 0, 'This is a placeholder answer. You can edit this in the admin panel.', 0.00, 0.00, 0, 0, 0, @now, @now, 0, NULL),
(5004, 0, 'This is a placeholder answer. You can edit this in the admin panel.', 0.00, 0.00, 0, 0, 0, @now, @now, 0, NULL),
(5005, 0, 'This is a placeholder answer. You can edit this in the admin panel.', 0.00, 0.00, 0, 0, 0, @now, @now, 0, NULL);

-- =============================================================================
-- 4.0.19 SEED EXPANSION — Admin testing (test data only; IDs 2000+)
-- =============================================================================
-- Deterministic timestamp for this block.
SET @seed19 = 20260219120000;

-- A. lupo_auth_users — 10 test users (auth_user_id 2001–2010). Password: "password" (bcrypt).
-- Hash: $2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi
INSERT IGNORE INTO lupo_auth_users (auth_user_id, username, display_name, email, password_hash, auth_provider, provider_id, created_ymdhis, updated_ymdhis, is_active, is_deleted, deleted_ymdhis) VALUES
(2001, 'admin_test', 'Admin Test', 'admin.test@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'local', NULL, @seed19, @seed19, 1, 0, NULL),
(2002, 'mod_jane', 'Jane Moderator', 'mod.jane@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'local', NULL, @seed19, @seed19, 1, 0, NULL),
(2003, 'mod_bob', 'Bob Monitor', 'mod.bob@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'local', NULL, @seed19, @seed19, 1, 0, NULL),
(2004, 'agent_alex', 'Alex Agent', 'agent.alex@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'local', NULL, @seed19, @seed19, 1, 0, NULL),
(2005, 'agent_sam', 'Sam Support', 'agent.sam@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'local', NULL, @seed19, @seed19, 1, 0, NULL),
(2006, 'viewer_lee', 'Lee Viewer', 'viewer.lee@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'local', NULL, @seed19, @seed19, 1, 0, NULL),
(2007, 'viewer_kim', 'Kim Readonly', 'viewer.kim@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'local', NULL, @seed19, @seed19, 1, 0, NULL),
(2008, 'ops_taylor', 'Taylor Operator', 'ops.taylor@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'local', NULL, @seed19, @seed19, 1, 0, NULL),
(2009, 'support_casey', 'Casey Support', 'support.casey@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'local', NULL, @seed19, @seed19, 1, 0, NULL),
(2010, 'crm_jordan', 'Jordan CRM', 'crm.jordan@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'local', NULL, @seed19, @seed19, 1, 0, NULL);

-- lupo_actors — user-type actors for auth_user 2001–2010 (actor_id 2001–2010).
INSERT IGNORE INTO lupo_actors (actor_id, actor_type, slug, name, created_ymdhis, updated_ymdhis, is_active, is_deleted, deleted_ymdhis, actor_source_id, actor_source_type, metadata, adversarial_role, adversarial_oversight_actor_id, avatar_hash) VALUES
(2001, 'user', 'user-2001', 'Admin Test', @seed19, @seed19, 1, 0, NULL, 2001, 'user', NULL, 'none', NULL, NULL),
(2002, 'user', 'user-2002', 'Jane Moderator', @seed19, @seed19, 1, 0, NULL, 2002, 'user', NULL, 'none', NULL, NULL),
(2003, 'user', 'user-2003', 'Bob Monitor', @seed19, @seed19, 1, 0, NULL, 2003, 'user', NULL, 'none', NULL, NULL),
(2004, 'user', 'user-2004', 'Alex Agent', @seed19, @seed19, 1, 0, NULL, 2004, 'user', NULL, 'none', NULL, NULL),
(2005, 'user', 'user-2005', 'Sam Support', @seed19, @seed19, 1, 0, NULL, 2005, 'user', NULL, 'none', NULL, NULL),
(2006, 'user', 'user-2006', 'Lee Viewer', @seed19, @seed19, 1, 0, NULL, 2006, 'user', NULL, 'none', NULL, NULL),
(2007, 'user', 'user-2007', 'Kim Readonly', @seed19, @seed19, 1, 0, NULL, 2007, 'user', NULL, 'none', NULL, NULL),
(2008, 'user', 'user-2008', 'Taylor Operator', @seed19, @seed19, 1, 0, NULL, 2008, 'user', NULL, 'none', NULL, NULL),
(2009, 'user', 'user-2009', 'Casey Support', @seed19, @seed19, 1, 0, NULL, 2009, 'user', NULL, 'none', NULL, NULL),
(2010, 'user', 'user-2010', 'Jordan CRM', @seed19, @seed19, 1, 0, NULL, 2010, 'user', NULL, 'none', NULL, NULL);

-- G. lupo_actor_channel_roles — channel 1 roles: 2001 captain, 2002–2003 administrator, 2004–2005 monitor, 2006–2010 monitor (viewer).
INSERT IGNORE INTO lupo_actor_channel_roles (actor_channel_role_id, actor_id, channel_id, role_key, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis) VALUES
(20001, 2001, 1, 'captain', @seed19, @seed19, 0, NULL),
(20002, 2002, 1, 'administrator', @seed19, @seed19, 0, NULL),
(20003, 2003, 1, 'administrator', @seed19, @seed19, 0, NULL),
(20004, 2004, 1, 'monitor', @seed19, @seed19, 0, NULL),
(20005, 2005, 1, 'monitor', @seed19, @seed19, 0, NULL),
(20006, 2006, 1, 'monitor', @seed19, @seed19, 0, NULL),
(20007, 2007, 1, 'monitor', @seed19, @seed19, 0, NULL),
(20008, 2008, 1, 'monitor', @seed19, @seed19, 0, NULL),
(20009, 2009, 1, 'monitor', @seed19, @seed19, 0, NULL),
(20010, 2010, 1, 'monitor', @seed19, @seed19, 0, NULL);

-- D. lupo_departments — 5 extra departments (2–6), default_actor_id references seeded users.
INSERT IGNORE INTO lupo_departments (department_id, federation_node_id, name, description, department_type, default_actor_id, settings_json, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis) VALUES
(2, 1, 'Support', 'Customer support team', 'general', 2002, NULL, @seed19, @seed19, 0, NULL),
(3, 1, 'CRM', 'Sales and leads', 'general', 2010, NULL, @seed19, @seed19, 0, NULL),
(4, 1, 'Docs', 'Documentation and knowledge', 'general', 2004, NULL, @seed19, @seed19, 0, NULL),
(5, 1, 'Engineering', 'Development and ops', 'general', 2001, NULL, @seed19, @seed19, 0, NULL),
(6, 1, 'Moderation', 'Content moderation', 'general', 2003, NULL, @seed19, @seed19, 0, NULL);

-- B. lupo_channels — 8 channels (channel_id 2001–2008); types: system, crm, support, docs.
INSERT IGNORE INTO lupo_channels (channel_id, federation_node_id, created_by_actor_id, default_actor_id, department_id, channel_key, channel_slug, channel_type, language, channel_name, description, website_link, metadata_json, status_flag, end_ymdhis, duration_seconds, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis, is_kernel, boot_sequence_order) VALUES
(2001, 1, 2001, 2001, 1, 'admin-test-system', 'admin-test-system', 'system', 'en', 'Admin Test System', 'System channel for admin testing', NULL, NULL, 1, NULL, NULL, @seed19, @seed19, 0, NULL, 0, NULL),
(2002, 1, 2001, 2002, 2, 'support-inbox', 'support-inbox', 'support', 'en', 'Support Inbox', 'Customer support queue', NULL, NULL, 1, NULL, NULL, @seed19, @seed19, 0, NULL, 0, NULL),
(2003, 1, 2010, 2010, 3, 'crm-leads', 'crm-leads', 'crm', 'en', 'CRM Leads', 'Lead pipeline and follow-up', NULL, NULL, 1, NULL, NULL, @seed19, @seed19, 0, NULL, 0, NULL),
(2004, 1, 2004, 2004, 4, 'docs-internal', 'docs-internal', 'docs', 'en', 'Internal Docs', 'Internal documentation channel', NULL, NULL, 1, NULL, NULL, @seed19, @seed19, 0, NULL, 0, NULL),
(2005, 1, 2001, 2001, 5, 'eng-dev', 'eng-dev', 'chat_room', 'en', 'Engineering Dev', 'Development and engineering', NULL, NULL, 1, NULL, NULL, @seed19, @seed19, 0, NULL, 0, NULL),
(2006, 1, 2003, 2003, 6, 'mod-queue', 'mod-queue', 'support', 'en', 'Moderation Queue', 'Content moderation queue', NULL, NULL, 1, NULL, NULL, @seed19, @seed19, 0, NULL, 0, NULL),
(2007, 1, 2002, 2002, 2, 'support-archive', 'support-archive', 'support', 'en', 'Support Archive', 'Archived support threads', NULL, NULL, 1, NULL, NULL, @seed19, @seed19, 0, NULL, 0, NULL),
(2008, 1, 2010, 2010, 3, 'crm-campaigns', 'crm-campaigns', 'crm', 'en', 'CRM Campaigns', 'Marketing and campaigns', NULL, NULL, 1, NULL, NULL, @seed19, @seed19, 0, NULL, 0, NULL);

-- C. lupo_agents — 6 agents (agent_id 2001–2006); archetypes, versions, descriptions.
INSERT IGNORE INTO lupo_agents (agent_id, agent_key, agent_name, archetype, description, version, model_name, is_global_authority, is_internal_only, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis) VALUES
(2001, 'seed_router_01', 'Seed Router Agent', 'router', 'Test router agent for admin list.', '1.0', NULL, 0, 1, @seed19, @seed19, 0, NULL),
(2002, 'seed_support_01', 'Seed Support Agent', 'support', 'Test support agent for admin list.', '1.0', NULL, 0, 1, @seed19, @seed19, 0, NULL),
(2003, 'seed_crm_01', 'Seed CRM Agent', 'crm', 'Test CRM agent for admin list.', '1.0', NULL, 0, 1, @seed19, @seed19, 0, NULL),
(2004, 'seed_docs_01', 'Seed Docs Agent', 'docs', 'Test documentation agent for admin list.', '1.0', NULL, 0, 1, @seed19, @seed19, 0, NULL),
(2005, 'seed_analytics_01', 'Seed Analytics Agent', 'analytics', 'Test analytics agent for admin list.', '1.0', NULL, 0, 1, @seed19, @seed19, 0, NULL),
(2006, 'seed_mod_01', 'Seed Moderation Agent', 'moderation', 'Test moderation agent for admin list.', '1.0', NULL, 0, 1, @seed19, @seed19, 0, NULL);

-- E. lupo_crm_leads — 20 leads (crm_lead_id 2001–2020); assigned_to = actor_id 2001–2010.
INSERT IGNORE INTO lupo_crm_leads (crm_lead_id, email, phone, first_name, last_name, source, status, lead_score, assigned_to, lead_data, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis) VALUES
(2001, 'alice@example.com', NULL, 'Alice', 'Alpha', 'website', 'new', 10, 2001, NULL, @seed19, @seed19, 0, NULL),
(2002, 'bob@example.com', '+15551234567', 'Bob', 'Beta', 'referral', 'contacted', 25, 2002, NULL, @seed19, @seed19, 0, NULL),
(2003, 'carol@example.com', NULL, 'Carol', 'Gamma', 'website', 'qualified', 50, 2010, NULL, @seed19, @seed19, 0, NULL),
(2004, 'dave@example.com', NULL, 'Dave', 'Delta', 'campaign', 'new', 5, 2004, NULL, @seed19, @seed19, 0, NULL),
(2005, 'eve@example.com', NULL, 'Eve', 'Epsilon', 'website', 'contacted', 30, 2005, NULL, @seed19, @seed19, 0, NULL),
(2006, 'frank@example.com', NULL, 'Frank', 'Phi', 'referral', 'new', 15, 2001, NULL, @seed19, @seed19, 0, NULL),
(2007, 'grace@example.com', NULL, 'Grace', 'Gamma', 'website', 'qualified', 60, 2010, NULL, @seed19, @seed19, 0, NULL),
(2008, 'henry@example.com', NULL, 'Henry', 'Eta', 'campaign', 'new', 0, 2002, NULL, @seed19, @seed19, 0, NULL),
(2009, 'iris@example.com', NULL, 'Iris', 'Iota', 'website', 'contacted', 40, 2009, NULL, @seed19, @seed19, 0, NULL),
(2010, 'jack@example.com', NULL, 'Jack', 'Kappa', 'referral', 'new', 20, 2003, NULL, @seed19, @seed19, 0, NULL),
(2011, 'kate@example.com', NULL, 'Kate', 'Lambda', 'website', 'qualified', 70, 2010, NULL, @seed19, @seed19, 0, NULL),
(2012, 'leo@example.com', NULL, 'Leo', 'Mu', 'campaign', 'new', 10, 2004, NULL, @seed19, @seed19, 0, NULL),
(2013, 'mia@example.com', NULL, 'Mia', 'Nu', 'website', 'contacted', 35, 2005, NULL, @seed19, @seed19, 0, NULL),
(2014, 'noah@example.com', NULL, 'Noah', 'Xi', 'referral', 'new', 25, 2001, NULL, @seed19, @seed19, 0, NULL),
(2015, 'olivia@example.com', NULL, 'Olivia', 'Omicron', 'website', 'qualified', 55, 2010, NULL, @seed19, @seed19, 0, NULL),
(2016, 'paul@example.com', NULL, 'Paul', 'Pi', 'campaign', 'new', 5, 2002, NULL, @seed19, @seed19, 0, NULL),
(2017, 'quinn@example.com', NULL, 'Quinn', 'Rho', 'website', 'contacted', 45, 2009, NULL, @seed19, @seed19, 0, NULL),
(2018, 'ryan@example.com', NULL, 'Ryan', 'Sigma', 'referral', 'new', 15, 2003, NULL, @seed19, @seed19, 0, NULL),
(2019, 'sara@example.com', NULL, 'Sara', 'Tau', 'website', 'qualified', 65, 2010, NULL, @seed19, @seed19, 0, NULL),
(2020, 'tom@example.com', NULL, 'Tom', 'Upsilon', 'campaign', 'new', 0, 2004, NULL, @seed19, @seed19, 0, NULL);

-- H. lupo_permissions — read on admin module (9) for test users 2001–2005.
INSERT IGNORE INTO lupo_permissions (permission_id, target_type, target_id, user_id, department_id, permission, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis) VALUES
(20001, 'module', 9, 2001, NULL, 'read', @seed19, @seed19, 0, NULL),
(20002, 'module', 9, 2002, NULL, 'read', @seed19, @seed19, 0, NULL),
(20003, 'module', 9, 2003, NULL, 'read', @seed19, @seed19, 0, NULL),
(20004, 'module', 9, 2004, NULL, 'read', @seed19, @seed19, 0, NULL),
(20005, 'module', 9, 2005, NULL, 'read', @seed19, @seed19, 0, NULL);

-- =============================================================================
-- END SEED
-- =============================================================================
--
-- Post-seed verification (run after seed; expect: 26 actors on channel 42, 33 messages, 2 content paths):
--   SELECT COUNT(*) FROM lupo_actor_channels WHERE channel_id = 42 AND is_deleted = 0;  -- expect 26
--   SELECT message_count FROM lupo_dialog_channels WHERE channel_id = 42;               -- expect 35
--   SELECT message_count FROM lupo_dialog_channels WHERE channel_id = 666;              -- expect 1
--   SELECT content_id, file_path_from_root FROM lupo_contents WHERE content_id IN (2001, 2002);
--   SELECT edge_id, right_object_id FROM lupo_edges WHERE edge_type = 'HAS_CONTENT' AND left_object_id = 42 AND is_deleted = 0;
