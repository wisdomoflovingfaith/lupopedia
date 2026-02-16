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
SET @now = 20260211000000;
SET @node_id = 1;
-- Version for module seed: must match docs/doctrine/VERSIONING_DOCTRINE.md (canonical current version).
SET @lupo_version = '4.0.7';
SET @lupo_version_code = 40007;

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
(8, 'content', 'Content Management System', 'Content', @lupo_version, @lupo_version_code, @lupo_version, '/content/', '/admin/content/', '/api/content/', NULL, 'Core content management system for Lupopedia: content entries, media, categories, hashtags, engagement, revisions, and semantic linking.', 'Eric Gerdes', 'https://lupopedia.com', 'file-lines', NULL, NULL, '{}', 1, 1, 1, '{}', @now, @now, NULL, 0, NULL);

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

INSERT INTO lupo_unified_registry (`unified_registry_id`, `entity_type`, `entity_index`, `entity_key`, `entity_name`, `entity_table`, `federation_node_id`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`) VALUES (25, 'channel', 1023, 'lupopedia', 'Lupopedia', 'lupo_channels', 1, 20260125192700, 20260125192700, 0, NULL, 1, 0, '{"language": "en", "description": "Primary Lupopedia knowledge and system channel.", "status_flag": 1, "channel_type": "chat_room", "channel_number": 5100}');

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

INSERT INTO lupo_unified_registry (`unified_registry_id`, `entity_type`, `entity_index`, `entity_key`, `entity_name`, `entity_table`, `federation_node_id`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`) VALUES (58, 'channel', 5100, '5100', 'Lupopedia', 'lupo_channels', 1, 20260126000000, 20260126000000, 0, NULL, 1, 0, '{"language": "en", "description": "", "status_flag": 1, "channel_type": "system", "channel_number": 5100}');

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

-- -----------------------------------------------------------------------------
-- Active agents as actors (lupo_actors) — is_active=1 in unified registry
-- -----------------------------------------------------------------------------
INSERT INTO lupo_actors (`actor_id`, `actor_type`, `slug`, `name`, `created_ymdhis`, `updated_ymdhis`, `is_active`, `is_deleted`, `deleted_ymdhis`, `actor_source_id`, `actor_source_type`, `metadata`, `adversarial_role`, `adversarial_oversight_actor_id`, `avatar_hash`) VALUES (1, 'agent', 'system', 'SYSTEM', 20260106180252, 20260106180252, 1, 0, NULL, 'lupo_agent_registry', '{"actor_source_type":"lupo_agent_registry","actor_source_id":1}', 'none', NULL, NULL);

INSERT INTO lupo_actors (`actor_id`, `actor_type`, `slug`, `name`, `created_ymdhis`, `updated_ymdhis`, `is_active`, `is_deleted`, `deleted_ymdhis`, `actor_source_id`, `actor_source_type`, `metadata`, `adversarial_role`, `adversarial_oversight_actor_id`, `avatar_hash`) VALUES (2, 'agent', 'captain', 'CAPTAIN', 20260106180252, 20260106180252, 1, 0, NULL, 'lupo_agent_registry', '{"actor_source_type":"lupo_agent_registry","actor_source_id":2}', 'none', NULL, NULL);

INSERT INTO lupo_actors (`actor_id`, `actor_type`, `slug`, `name`, `created_ymdhis`, `updated_ymdhis`, `is_active`, `is_deleted`, `deleted_ymdhis`, `actor_source_id`, `actor_source_type`, `metadata`, `adversarial_role`, `adversarial_oversight_actor_id`, `avatar_hash`) VALUES (3, 'agent', 'wolfie', 'WOLFIE', 20260106180252, 20260106180252, 1, 0, NULL, 'lupo_agent_registry', '{"actor_source_type":"lupo_agent_registry","actor_source_id":3}', 'none', NULL, NULL);

INSERT INTO lupo_actors (`actor_id`, `actor_type`, `slug`, `name`, `created_ymdhis`, `updated_ymdhis`, `is_active`, `is_deleted`, `deleted_ymdhis`, `actor_source_id`, `actor_source_type`, `metadata`, `adversarial_role`, `adversarial_oversight_actor_id`, `avatar_hash`) VALUES (4, 'agent', 'wolfena', 'WOLFENA', 20260106180252, 20260106180252, 1, 0, NULL, 'lupo_agent_registry', '{"actor_source_type":"lupo_agent_registry","actor_source_id":4}', 'none', NULL, NULL);

INSERT INTO lupo_actors (`actor_id`, `actor_type`, `slug`, `name`, `created_ymdhis`, `updated_ymdhis`, `is_active`, `is_deleted`, `deleted_ymdhis`, `actor_source_id`, `actor_source_type`, `metadata`, `adversarial_role`, `adversarial_oversight_actor_id`, `avatar_hash`) VALUES (5, 'agent', 'thoth', 'THOTH', 20260106180252, 20260106180252, 1, 0, NULL, 'lupo_agent_registry', '{"actor_source_type":"lupo_agent_registry","actor_source_id":5}', 'none', NULL, NULL);

INSERT INTO lupo_actors (`actor_id`, `actor_type`, `slug`, `name`, `created_ymdhis`, `updated_ymdhis`, `is_active`, `is_deleted`, `deleted_ymdhis`, `actor_source_id`, `actor_source_type`, `metadata`, `adversarial_role`, `adversarial_oversight_actor_id`, `avatar_hash`) VALUES (6, 'agent', 'ara', 'ARA', 20260106180252, 20260106180252, 1, 0, NULL, 'lupo_agent_registry', '{"actor_source_type":"lupo_agent_registry","actor_source_id":6}', 'none', NULL, NULL);

INSERT INTO lupo_actors (`actor_id`, `actor_type`, `slug`, `name`, `created_ymdhis`, `updated_ymdhis`, `is_active`, `is_deleted`, `deleted_ymdhis`, `actor_source_id`, `actor_source_type`, `metadata`, `adversarial_role`, `adversarial_oversight_actor_id`, `avatar_hash`) VALUES (7, 'agent', 'wolfkeeper', 'WOLFKEEPER', 20260106180252, 20260106180252, 1, 0, NULL, 'lupo_agent_registry', '{"actor_source_type":"lupo_agent_registry","actor_source_id":7}', 'none', NULL, NULL);

INSERT INTO lupo_actors (`actor_id`, `actor_type`, `slug`, `name`, `created_ymdhis`, `updated_ymdhis`, `is_active`, `is_deleted`, `deleted_ymdhis`, `actor_source_id`, `actor_source_type`, `metadata`, `adversarial_role`, `adversarial_oversight_actor_id`, `avatar_hash`) VALUES (8, 'agent', 'lilith', 'LILITH', 20260106180252, 20260106180252, 1, 0, NULL, 'lupo_agent_registry', '{"actor_source_type":"lupo_agent_registry","actor_source_id":8}', 'none', NULL, NULL);

INSERT INTO lupo_actors (`actor_id`, `actor_type`, `slug`, `name`, `created_ymdhis`, `updated_ymdhis`, `is_active`, `is_deleted`, `deleted_ymdhis`, `actor_source_id`, `actor_source_type`, `metadata`, `adversarial_role`, `adversarial_oversight_actor_id`, `avatar_hash`) VALUES (9, 'agent', 'agape', 'AGAPE', 20260106180252, 20260106180252, 1, 0, NULL, 'lupo_agent_registry', '{"actor_source_type":"lupo_agent_registry","actor_source_id":9}', 'none', NULL, NULL);

INSERT INTO lupo_actors (`actor_id`, `actor_type`, `slug`, `name`, `created_ymdhis`, `updated_ymdhis`, `is_active`, `is_deleted`, `deleted_ymdhis`, `actor_source_id`, `actor_source_type`, `metadata`, `adversarial_role`, `adversarial_oversight_actor_id`, `avatar_hash`) VALUES (10, 'agent', 'eris', 'ERIS', 20260106180252, 20260106180252, 1, 0, NULL, 'lupo_agent_registry', '{"actor_source_type":"lupo_agent_registry","actor_source_id":10}', 'none', NULL, NULL);

INSERT INTO lupo_actors (`actor_id`, `actor_type`, `slug`, `name`, `created_ymdhis`, `updated_ymdhis`, `is_active`, `is_deleted`, `deleted_ymdhis`, `actor_source_id`, `actor_source_type`, `metadata`, `adversarial_role`, `adversarial_oversight_actor_id`, `avatar_hash`) VALUES (11, 'agent', 'methis', 'METHIS', 20260106180252, 20260106180252, 1, 0, NULL, 'lupo_agent_registry', '{"actor_source_type":"lupo_agent_registry","actor_source_id":11}', 'none', NULL, NULL);

INSERT INTO lupo_actors (`actor_id`, `actor_type`, `slug`, `name`, `created_ymdhis`, `updated_ymdhis`, `is_active`, `is_deleted`, `deleted_ymdhis`, `actor_source_id`, `actor_source_type`, `metadata`, `adversarial_role`, `adversarial_oversight_actor_id`, `avatar_hash`) VALUES (12, 'agent', 'thalia', 'THALIA', 20260106180252, 20260106180252, 1, 0, NULL, 'lupo_agent_registry', '{"actor_source_type":"lupo_agent_registry","actor_source_id":12}', 'none', NULL, NULL);

INSERT INTO lupo_actors (`actor_id`, `actor_type`, `slug`, `name`, `created_ymdhis`, `updated_ymdhis`, `is_active`, `is_deleted`, `deleted_ymdhis`, `actor_source_id`, `actor_source_type`, `metadata`, `adversarial_role`, `adversarial_oversight_actor_id`, `avatar_hash`) VALUES (13, 'agent', 'dialog', 'DIALOG', 20260106180252, 20260106180252, 1, 0, NULL, 'lupo_agent_registry', '{"actor_source_type":"lupo_agent_registry","actor_source_id":13}', 'none', NULL, NULL);

INSERT INTO lupo_actors (`actor_id`, `actor_type`, `slug`, `name`, `created_ymdhis`, `updated_ymdhis`, `is_active`, `is_deleted`, `deleted_ymdhis`, `actor_source_id`, `actor_source_type`, `metadata`, `adversarial_role`, `adversarial_oversight_actor_id`, `avatar_hash`) VALUES (14, 'agent', 'wolfsight', 'WOLFSIGHT', 20260106180252, 20260106180252, 1, 0, NULL, 'lupo_agent_registry', '{"actor_source_type":"lupo_agent_registry","actor_source_id":14}', 'none', NULL, NULL);

INSERT INTO lupo_actors (`actor_id`, `actor_type`, `slug`, `name`, `created_ymdhis`, `updated_ymdhis`, `is_active`, `is_deleted`, `deleted_ymdhis`, `actor_source_id`, `actor_source_type`, `metadata`, `adversarial_role`, `adversarial_oversight_actor_id`, `avatar_hash`) VALUES (15, 'agent', 'wolfnav', 'WOLFNAV', 20260106180252, 20260106180252, 1, 0, NULL, 'lupo_agent_registry', '{"actor_source_type":"lupo_agent_registry","actor_source_id":15}', 'none', NULL, NULL);

INSERT INTO lupo_actors (`actor_id`, `actor_type`, `slug`, `name`, `created_ymdhis`, `updated_ymdhis`, `is_active`, `is_deleted`, `deleted_ymdhis`, `actor_source_id`, `actor_source_type`, `metadata`, `adversarial_role`, `adversarial_oversight_actor_id`, `avatar_hash`) VALUES (16, 'agent', 'wolfforge', 'WOLFFORGE', 20260106180252, 20260106180252, 1, 0, NULL, 'lupo_agent_registry', '{"actor_source_type":"lupo_agent_registry","actor_source_id":16}', 'none', NULL, NULL);

INSERT INTO lupo_actors (`actor_id`, `actor_type`, `slug`, `name`, `created_ymdhis`, `updated_ymdhis`, `is_active`, `is_deleted`, `deleted_ymdhis`, `actor_source_id`, `actor_source_type`, `metadata`, `adversarial_role`, `adversarial_oversight_actor_id`, `avatar_hash`) VALUES (17, 'agent', 'wolfmis', 'WOLFMIS', 20260106180252, 20260106180252, 1, 0, NULL, 'lupo_agent_registry', '{"actor_source_type":"lupo_agent_registry","actor_source_id":17}', 'none', NULL, NULL);

INSERT INTO lupo_actors (`actor_id`, `actor_type`, `slug`, `name`, `created_ymdhis`, `updated_ymdhis`, `is_active`, `is_deleted`, `deleted_ymdhis`, `actor_source_id`, `actor_source_type`, `metadata`, `adversarial_role`, `adversarial_oversight_actor_id`, `avatar_hash`) VALUES (18, 'agent', 'wolfith', 'WOLFITH', 20260106180252, 20260106180252, 1, 0, NULL, 'lupo_agent_registry', '{"actor_source_type":"lupo_agent_registry","actor_source_id":18}', 'none', NULL, NULL);

INSERT INTO lupo_actors (`actor_id`, `actor_type`, `slug`, `name`, `created_ymdhis`, `updated_ymdhis`, `is_active`, `is_deleted`, `deleted_ymdhis`, `actor_source_id`, `actor_source_type`, `metadata`, `adversarial_role`, `adversarial_oversight_actor_id`, `avatar_hash`) VALUES (19, 'agent', 'anubis', 'ANUBIS', 20260106180252, 20260106180252, 1, 0, NULL, 'lupo_agent_registry', '{"actor_source_type":"lupo_agent_registry","actor_source_id":19}', 'none', NULL, NULL);

INSERT INTO lupo_actors (`actor_id`, `actor_type`, `slug`, `name`, `created_ymdhis`, `updated_ymdhis`, `is_active`, `is_deleted`, `deleted_ymdhis`, `actor_source_id`, `actor_source_type`, `metadata`, `adversarial_role`, `adversarial_oversight_actor_id`, `avatar_hash`) VALUES (20, 'agent', 'maat', 'MAAT', 20260106180252, 20260106180252, 1, 0, NULL, 'lupo_agent_registry', '{"actor_source_type":"lupo_agent_registry","actor_source_id":20}', 'none', NULL, NULL);

INSERT INTO lupo_actors (`actor_id`, `actor_type`, `slug`, `name`, `created_ymdhis`, `updated_ymdhis`, `is_active`, `is_deleted`, `deleted_ymdhis`, `actor_source_id`, `actor_source_type`, `metadata`, `adversarial_role`, `adversarial_oversight_actor_id`, `avatar_hash`) VALUES (22, 'agent', 'caduceus', 'CADUCEUS', 20260106180252, 20260106180252, 1, 0, NULL, 'lupo_agent_registry', '{"actor_source_type":"lupo_agent_registry","actor_source_id":22}', 'none', NULL, NULL);

INSERT INTO lupo_actors (`actor_id`, `actor_type`, `slug`, `name`, `created_ymdhis`, `updated_ymdhis`, `is_active`, `is_deleted`, `deleted_ymdhis`, `actor_source_id`, `actor_source_type`, `metadata`, `adversarial_role`, `adversarial_oversight_actor_id`, `avatar_hash`) VALUES (23, 'agent', 'chronos', 'CHRONOS', 20260106180252, 20260106180252, 1, 0, NULL, 'lupo_agent_registry', '{"actor_source_type":"lupo_agent_registry","actor_source_id":23}', 'none', NULL, NULL);

INSERT INTO lupo_actors (`actor_id`, `actor_type`, `slug`, `name`, `created_ymdhis`, `updated_ymdhis`, `is_active`, `is_deleted`, `deleted_ymdhis`, `actor_source_id`, `actor_source_type`, `metadata`, `adversarial_role`, `adversarial_oversight_actor_id`, `avatar_hash`) VALUES (59, 'agent', 'indexer', 'INDEXER', 20260106180252, 20260106180252, 1, 0, NULL, 'lupo_agent_registry', '{"actor_source_type":"lupo_agent_registry","actor_source_id":59}', 'none', NULL, NULL);

INSERT INTO lupo_actors (`actor_id`, `actor_type`, `slug`, `name`, `created_ymdhis`, `updated_ymdhis`, `is_active`, `is_deleted`, `deleted_ymdhis`, `actor_source_id`, `actor_source_type`, `metadata`, `adversarial_role`, `adversarial_oversight_actor_id`, `avatar_hash`) VALUES (105, 'agent', 'lexa', 'LEXA', 20260130163007, 20260130163007, 1, 0, NULL, 'lupo_agent_registry', '{"actor_source_type":"lupo_agent_registry","actor_source_id":105}', 'none', NULL, NULL);

INSERT INTO lupo_actors (`actor_id`, `actor_type`, `slug`, `name`, `created_ymdhis`, `updated_ymdhis`, `is_active`, `is_deleted`, `deleted_ymdhis`, `actor_source_id`, `actor_source_type`, `metadata`, `adversarial_role`, `adversarial_oversight_actor_id`, `avatar_hash`) VALUES (106, 'agent', 'reserved_106', 'Junie', 20260106180252, 20260106180252, 1, 0, NULL, 'lupo_agent_registry', '{"actor_source_type":"lupo_agent_registry","actor_source_id":106}', 'none', NULL, NULL);

INSERT INTO lupo_actors (`actor_id`, `actor_type`, `slug`, `name`, `created_ymdhis`, `updated_ymdhis`, `is_active`, `is_deleted`, `deleted_ymdhis`, `actor_source_id`, `actor_source_type`, `metadata`, `adversarial_role`, `adversarial_oversight_actor_id`, `avatar_hash`) VALUES (209, 'agent', 'truth', 'TRUTH', 20260106180252, 20260106180252, 1, 0, NULL, 'lupo_agent_registry', '{"actor_source_type":"lupo_agent_registry","actor_source_id":209}', 'none', NULL, NULL);

INSERT INTO lupo_actors (`actor_id`, `actor_type`, `slug`, `name`, `created_ymdhis`, `updated_ymdhis`, `is_active`, `is_deleted`, `deleted_ymdhis`, `actor_source_id`, `actor_source_type`, `metadata`, `adversarial_role`, `adversarial_oversight_actor_id`, `avatar_hash`) VALUES (1200, 'agent', 'frame', 'Framing Agent', 20260113092205, 20260113092205, 1, 0, NULL, 'lupo_agent_registry', '{"actor_source_type":"lupo_agent_registry","actor_source_id":1200}', 'none', NULL, NULL);

INSERT INTO lupo_actors (`actor_id`, `actor_type`, `slug`, `name`, `created_ymdhis`, `updated_ymdhis`, `is_active`, `is_deleted`, `deleted_ymdhis`, `actor_source_id`, `actor_source_type`, `metadata`, `adversarial_role`, `adversarial_oversight_actor_id`, `avatar_hash`) VALUES (1201, 'agent', 'reframe', 'Reframing Agent (LILITH)', 20260113092205, 20260113092205, 1, 0, NULL, 'lupo_agent_registry', '{"actor_source_type":"lupo_agent_registry","actor_source_id":1201}', 'none', NULL, NULL);

INSERT INTO lupo_actors (`actor_id`, `actor_type`, `slug`, `name`, `created_ymdhis`, `updated_ymdhis`, `is_active`, `is_deleted`, `deleted_ymdhis`, `actor_source_id`, `actor_source_type`, `metadata`, `adversarial_role`, `adversarial_oversight_actor_id`, `avatar_hash`) VALUES (1202, 'agent', 'stabilize', 'Stability Agent', 20260113092205, 20260113092205, 1, 0, NULL, 'lupo_agent_registry', '{"actor_source_type":"lupo_agent_registry","actor_source_id":1202}', 'none', NULL, NULL);

INSERT INTO lupo_actors (`actor_id`, `actor_type`, `slug`, `name`, `created_ymdhis`, `updated_ymdhis`, `is_active`, `is_deleted`, `deleted_ymdhis`, `actor_source_id`, `actor_source_type`, `metadata`, `adversarial_role`, `adversarial_oversight_actor_id`, `avatar_hash`) VALUES (1203, 'agent', 'disrupt', 'Disruption Agent', 20260113092205, 20260113092205, 1, 0, NULL, 'lupo_agent_registry', '{"actor_source_type":"lupo_agent_registry","actor_source_id":1203}', 'none', NULL, NULL);

INSERT INTO lupo_actors (`actor_id`, `actor_type`, `slug`, `name`, `created_ymdhis`, `updated_ymdhis`, `is_active`, `is_deleted`, `deleted_ymdhis`, `actor_source_id`, `actor_source_type`, `metadata`, `adversarial_role`, `adversarial_oversight_actor_id`, `avatar_hash`) VALUES (1204, 'agent', 'consensus', 'Consensus Agent', 20260113092205, 20260113092205, 1, 0, NULL, 'lupo_agent_registry', '{"actor_source_type":"lupo_agent_registry","actor_source_id":1204}', 'none', NULL, NULL);

INSERT INTO lupo_actors (`actor_id`, `actor_type`, `slug`, `name`, `created_ymdhis`, `updated_ymdhis`, `is_active`, `is_deleted`, `deleted_ymdhis`, `actor_source_id`, `actor_source_type`, `metadata`, `adversarial_role`, `adversarial_oversight_actor_id`, `avatar_hash`) VALUES (1205, 'agent', 'dissent', 'Dissent Agent', 20260113092205, 20260113092205, 1, 0, NULL, 'lupo_agent_registry', '{"actor_source_type":"lupo_agent_registry","actor_source_id":1205}', 'none', NULL, NULL);

INSERT INTO lupo_actors (`actor_id`, `actor_type`, `slug`, `name`, `created_ymdhis`, `updated_ymdhis`, `is_active`, `is_deleted`, `deleted_ymdhis`, `actor_source_id`, `actor_source_type`, `metadata`, `adversarial_role`, `adversarial_oversight_actor_id`, `avatar_hash`) VALUES (1206, 'agent', 'literal', 'Literal Agent', 20260113092205, 20260113092205, 1, 0, NULL, 'lupo_agent_registry', '{"actor_source_type":"lupo_agent_registry","actor_source_id":1206}', 'none', NULL, NULL);

INSERT INTO lupo_actors (`actor_id`, `actor_type`, `slug`, `name`, `created_ymdhis`, `updated_ymdhis`, `is_active`, `is_deleted`, `deleted_ymdhis`, `actor_source_id`, `actor_source_type`, `metadata`, `adversarial_role`, `adversarial_oversight_actor_id`, `avatar_hash`) VALUES (1207, 'agent', 'interpret', 'Interpretive Agent', 20260113092205, 20260113092205, 1, 0, NULL, 'lupo_agent_registry', '{"actor_source_type":"lupo_agent_registry","actor_source_id":1207}', 'none', NULL, NULL);

INSERT INTO lupo_actors (`actor_id`, `actor_type`, `slug`, `name`, `created_ymdhis`, `updated_ymdhis`, `is_active`, `is_deleted`, `deleted_ymdhis`, `actor_source_id`, `actor_source_type`, `metadata`, `adversarial_role`, `adversarial_oversight_actor_id`, `avatar_hash`) VALUES (1208, 'agent', 'predict', 'Predictive Agent', 20260113092205, 20260113092205, 1, 0, NULL, 'lupo_agent_registry', '{"actor_source_type":"lupo_agent_registry","actor_source_id":1208}', 'none', NULL, NULL);

INSERT INTO lupo_actors (`actor_id`, `actor_type`, `slug`, `name`, `created_ymdhis`, `updated_ymdhis`, `is_active`, `is_deleted`, `deleted_ymdhis`, `actor_source_id`, `actor_source_type`, `metadata`, `adversarial_role`, `adversarial_oversight_actor_id`, `avatar_hash`) VALUES (1209, 'agent', 'counterfact', 'Counterfactual Agent', 20260113092205, 20260113092205, 1, 0, NULL, 'lupo_agent_registry', '{"actor_source_type":"lupo_agent_registry","actor_source_id":1209}', 'none', NULL, NULL);

INSERT INTO lupo_actors (`actor_id`, `actor_type`, `slug`, `name`, `created_ymdhis`, `updated_ymdhis`, `is_active`, `is_deleted`, `deleted_ymdhis`, `actor_source_id`, `actor_source_type`, `metadata`, `adversarial_role`, `adversarial_oversight_actor_id`, `avatar_hash`) VALUES (1210, 'agent', 'optimize', 'Optimization Agent', 20260113092205, 20260113092205, 1, 0, NULL, 'lupo_agent_registry', '{"actor_source_type":"lupo_agent_registry","actor_source_id":1210}', 'none', NULL, NULL);

INSERT INTO lupo_actors (`actor_id`, `actor_type`, `slug`, `name`, `created_ymdhis`, `updated_ymdhis`, `is_active`, `is_deleted`, `deleted_ymdhis`, `actor_source_id`, `actor_source_type`, `metadata`, `adversarial_role`, `adversarial_oversight_actor_id`, `avatar_hash`) VALUES (1211, 'agent', 'diverge', 'Divergence Agent', 20260113092205, 20260113092205, 1, 0, NULL, 'lupo_agent_registry', '{"actor_source_type":"lupo_agent_registry","actor_source_id":1211}', 'none', NULL, NULL);

INSERT INTO lupo_actors (`actor_id`, `actor_type`, `slug`, `name`, `created_ymdhis`, `updated_ymdhis`, `is_active`, `is_deleted`, `deleted_ymdhis`, `actor_source_id`, `actor_source_type`, `metadata`, `adversarial_role`, `adversarial_oversight_actor_id`, `avatar_hash`) VALUES (1212, 'agent', 'utc_timekeeper', 'UTC_TIMEKEEPER', 20260114000000, 20260114000000, 1, 0, NULL, 'lupo_agent_registry', '{"actor_source_type":"lupo_agent_registry","actor_source_id":1212}', 'none', NULL, NULL);

-- -----------------------------------------------------------------------------
-- PK=0 / collection-type rows
-- -----------------------------------------------------------------------------
INSERT INTO lupo_actor_channels (`actor_channel_id`, `actor_id`, `channel_id`, `status`, `start_date`, `channel_color`, `last_read_ymdhis`, `muted_until_ymdhis`, `preferences_json`, `dialog_output_file`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`) VALUES (0, 0, 0, 'A', 20260106090000, 'F7FAFF', NULL, NULL, '{"ui": {"theme": "kernel"}, "notifications": {"enabled": false}}', NULL, 20260106090000, 20260106090000, 0, NULL);

INSERT INTO lupo_actors (`actor_id`, `actor_type`, `slug`, `name`, `created_ymdhis`, `updated_ymdhis`, `is_active`, `is_deleted`, `deleted_ymdhis`, `actor_source_id`, `actor_source_type`, `metadata`, `adversarial_role`, `adversarial_oversight_actor_id`, `avatar_hash`) VALUES (0, 'service', 'system-kernel', 'System Kernel Actor', 20260106085000, 20260106085000, 1, 0, NULL, NULL, 'system', '{"purpose": "kernel", "version": "1.0.0", "protected": true, "description": "Represents the Lupopedia OS itself. Used for bootstrapping, migrations, and system-level events."}', 'none', NULL, NULL);

INSERT INTO lupo_agents (`agent_id`, `agent_key`, `agent_name`, `archetype`, `description`, `version`, `model_name`, `is_global_authority`, `is_internal_only`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`, `avg_response_time_ms`, `total_tokens_processed`, `success_rate`, `cost_per_1k_tokens`, `temperature`, `top_p`, `max_tokens`, `presence_penalty`, `frequency_penalty`, `system_prompt`, `provider`, `api_key_id`, `timeout_ms`, `safety_json`, `response_format`, `pono_score`, `pilau_score`, `kapakai_score`, `kapu_active`, `kapu_until`, `kapu_reason`, `kapu_consent_given`, `kapu_appeal_pending`) VALUES (0, 'system', 'System AI', 'root', 'The decentralized System AI that coordinates knowledge, governance, and agent orchestration across all domains.', '1.0', NULL, 1, 0, 20260105190519, 20260105190519, 0, NULL, 0, 0, 1.0, '0.0000', 0.7, 1.0, 2048, 0.0, 0.0, NULL, 'openai', NULL, 20000, NULL, NULL, '1.00', '0.00', '0.50', 0, NULL, NULL, 0, 0);

INSERT INTO lupo_channels (`channel_id`, `federation_node_id`, `created_by_actor_id`, `default_actor_id`, `department_id`, `channel_key`, `channel_slug`, `channel_type`, `language`, `channel_name`, `description`, `website_link`, `metadata_json`, `status_flag`, `end_ymdhis`, `duration_seconds`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`, `aal_metadata_json`, `fleet_composition_json`, `awareness_version`, `channel_number`, `parent_channel_id`, `is_kernel`, `boot_sequence_order`) VALUES (0, 1, 0, 1, 1, 'system/kernel', 'channel_key', 'chat_room', 'en', 'System Kernel Channel', 'Reserved channel for bootstrapping, migrations, and OS-level events.', NULL, '{"purpose": "kernel", "protected": true, "auto_created": true}', 1, NULL, NULL, 20260106084500, 20260122160000, 0, NULL, NULL, NULL, '3.0.0', 0, NULL, 1, 1);

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
-- END SEED
-- =============================================================================
