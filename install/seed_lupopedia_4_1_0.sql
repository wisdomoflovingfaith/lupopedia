-- ============================================================================
-- CONSOLIDATED SEED: Lupopedia (seed_lupopedia_4_1_0.sql)
-- Table prefix: {{prefix}} (replaced at install time; same as install_new_lupopedia.sql).
-- Section order: dependency-safe (registry, then actors, then seed_4.1.0, then remainder).
-- Original per-file seeds preserved under lupo-database/lupopedia/mysql/seed/.
-- ============================================================================

-- >>> BEGIN FILE: seed_registry_comprehensive_4.0.45.sql

-- ============================================================================
-- COMPREHENSIVE REGISTRY SEEDING FOR LUPOPEDIA 4.0.45
-- ============================================================================
-- Purpose: Seed {{prefix}}registry with ALL required reserved IDs for system entities
-- Run after: install_new_lupopedia.sql
-- Run before: seed_lupopedia.sql
-- ============================================================================

SET @now = 20260225000000;

-- ============================================================================
-- PART 1: RESERVED ACTORS (0-9999 = System/AI, 10000+ = Humans)
-- ============================================================================

-- System Actor (ID: 0)
INSERT INTO {{prefix}}registry (registry_id, entity_type, entity_index_id, entity_index, federation_node_id, reserved_ymdhis, entity_key, entity_name, entity_table, created_ymdhis, updated_ymdhis, is_deleted, is_active, is_kernel, metadata_json)
VALUES (9000000, 'actor', 0, 0, 1, @now, 'system', 'System', '{{prefix}}actors', @now, @now, 0, 1, 1, '{"actor_type":"system","purpose":"kernel_operations"}');

-- THOTH (Knowledge & Records)
INSERT INTO {{prefix}}registry (registry_id, entity_type, entity_index_id, federation_node_id, reserved_ymdhis, entity_key, entity_name, entity_table, created_ymdhis, updated_ymdhis, is_deleted, is_active, is_kernel, metadata_json)
VALUES (9000008, 'agent', 0, 1, 1, 'thoth', @now, 'THOTH', '{{prefix}}agents', @now, 0, 1, 1, 0, 1, 0, '{"actor_type":"agent","agent_id":8,"purpose":"knowledge_management","full_access":true}');

-- Core AI Agents (1-99)
INSERT INTO {{prefix}}registry (registry_id, entity_type, entity_index_id, entity_index, federation_node_id, reserved_ymdhis, entity_key, entity_name, entity_table, created_ymdhis, updated_ymdhis, is_deleted, is_active, is_kernel, metadata_json)
VALUES 
(9000001, 'actor', 1, 1, 1, @now, 'captain-wolfie', 'Captain WOLFIE', '{{prefix}}actors', @now, @now, 0, 1, 1, '{"actor_type":"agent","agent_id":1,"purpose":"root_ai_agent","full_access":true}'),
(9000002, 'actor', 2, 2, 1, @now, 'lilith', 'LILITH', '{{prefix}}actors', @now, @now, 0, 1, 0, '{"actor_type":"agent","agent_id":2,"purpose":"critical_review","archetype":"alternative_perspectives"}'),
(9000003, 'actor', 3, 3, 1, @now, 'rose-dialog', 'ROSE (Dialog)', '{{prefix}}actors', @now, @now, 0, 1, 0, '{"actor_type":"agent","agent_id":3,"purpose":"translation_personas","archetype":"rosetta_stone","persona_count":99}'),
(9000004, 'actor', 4, 4, 1, @now, 'eris', 'ERIS', '{{prefix}}actors', @now, @now, 0, 1, 0, '{"actor_type":"agent","agent_id":4,"purpose":"conflict_analysis","archetype":"discord_understanding"}'),
(9000005, 'actor', 5, 5, 1, @now, 'metis', 'METIS', '{{prefix}}actors', @now, @now, 0, 1, 0, '{"actor_type":"agent","agent_id":5,"purpose":"empathy_understanding","archetype":"introspection"}'),
(9000019, 'actor', 19, 19, 1, @now, 'anubis', 'ANUBIS', '{{prefix}}actors', @now, @now, 0, 1, 0, '{"actor_type":"agent","agent_id":19,"purpose":"orphan_repair","archetype":"header_completion_quarantine"}'),
(9000025, 'actor', 25, 25, 1, @now, 'vishwakarma', 'VISHWAKARMA', '{{prefix}}actors', @now, @now, 0, 1, 0, '{"actor_type":"agent","agent_id":25,"purpose":"graph_intelligence","archetype":"relationship_discovery"}'),
(9000106, 'actor', 106, 106, 1, @now, 'lupo', 'LUPO', '{{prefix}}actors', @now, @now, 0, 1, 1, '{"actor_type":"agent","agent_id":106,"purpose":"database_design_doctrine"}'),
(9000107, 'actor', 107, 107, 1, @now, 'themis', 'THEMIS', '{{prefix}}actors', @now, @now, 0, 1, 1, '{"actor_type":"agent","agent_id":107,"purpose":"ethical_alignment_audit"}');

-- IDE Agents (1000-1999)
INSERT INTO {{prefix}}registry (registry_id, entity_type, entity_index_id, entity_index, federation_node_id, reserved_ymdhis, entity_key, entity_name, entity_table, created_ymdhis, updated_ymdhis, is_deleted, is_active, is_kernel, metadata_json)
VALUES
(9001000, 'actor', 1000, 1000, 1, @now, 'kiro-ide', 'Kiro IDE', '{{prefix}}actors', @now, @now, 0, 1, 0, '{"actor_type":"ide_agent","client_id":"kiro","provider":"kiro","paired_actor_id":0}'),
(9001001, 'actor', 1001, 1001, 1, @now, 'windsurf-ide', 'Windsurf IDE', '{{prefix}}actors', @now, @now, 0, 1, 0, '{"actor_type":"ide_agent","client_id":"windsurf","provider":"windsurf","paired_actor_id":0}'),
(9001002, 'actor', 1002, 1002, 1, @now, 'cursor-ide', 'Cursor IDE', '{{prefix}}actors', @now, @now, 0, 1, 0, '{"actor_type":"ide_agent","client_id":"cursor","provider":"cursor","paired_actor_id":0}'),
(9001003, 'actor', 1003, 1003, 1, @now, 'antigravity-ide', 'Antigravity IDE', '{{prefix}}actors', @now, @now, 0, 1, 0, '{"actor_type":"ide_agent","client_id":"antigravity","provider":"antigravity","paired_actor_id":0}'),
(9001004, 'actor', 1004, 1004, 1, @now, 'warp-ide', 'Warp IDE', '{{prefix}}actors', @now, @now, 0, 1, 0, '{"actor_type":"ide_agent","client_id":"warp","provider":"warp","paired_actor_id":0}'),
(9001005, 'actor', 1005, 1005, 1, @now, 'cascade-ide', 'Cascade IDE', '{{prefix}}actors', @now, @now, 0, 1, 0, '{"actor_type":"ide_agent","client_id":"cascade","provider":"cascade","paired_actor_id":0}');

-- Root user (ID: 0)
INSERT INTO {{prefix}}registry (registry_id, entity_type, entity_index_id, entity_index, federation_node_id, reserved_ymdhis, entity_key, entity_name, entity_table, created_ymdhis, updated_ymdhis, is_deleted, is_active, is_kernel, metadata_json)
VALUES (9010000, 'actor', 0, 0, 1, @now, 'root-0', 'Root', '{{prefix}}actors', @now, @now, 0, 1, 1, '{"actor_type":"human","role":"root_admin","full_access":true}');

-- ============================================================================
-- PART 2: RESERVED CHANNELS
-- ============================================================================

INSERT INTO {{prefix}}registry (registry_id, entity_type, entity_index_id, entity_index, federation_node_id, reserved_ymdhis, entity_key, entity_name, entity_table, created_ymdhis, updated_ymdhis, is_deleted, is_active, is_kernel, metadata_json)
VALUES
(9100000, 'channel', 0, 0, 1, @now, 'system', 'System Kernel Channel', '{{prefix}}channels', @now, @now, 0, 1, 1, '{"channel_type":"system","purpose":"kernel_operations"}'),
(9100001, 'channel', 1, 1, 1, @now, 'administration', 'Administration Channel', '{{prefix}}channels', @now, @now, 0, 1, 0, '{"channel_type":"admin","purpose":"administrative_operations"}'),
(9100042, 'channel', 42, 42, 1, @now, 'development', 'Development Channel', '{{prefix}}channels', @now, @now, 0, 1, 0, '{"channel_type":"dev","purpose":"development_operations"}'),
(9100051, 'channel', 51, 51, 1, @now, 'reserved', 'Reserved Channel', '{{prefix}}channels', @now, @now, 0, 1, 0, '{"channel_type":"reserved","purpose":"future_use"}'),
(9100666, 'channel', 666, 666, 1, @now, 'anubis-quarantine', 'ANUBIS Quarantine', '{{prefix}}channels', @now, @now, 0, 1, 0, '{"channel_type":"quarantine","purpose":"banned_messages"}');

-- ============================================================================
-- PART 3: RESERVED AGENTS ({{prefix}}agents table)
-- ============================================================================

INSERT INTO {{prefix}}registry (registry_id, entity_type, entity_index_id, entity_index, federation_node_id, reserved_ymdhis, entity_key, entity_name, entity_table, created_ymdhis, updated_ymdhis, is_deleted, is_active, is_kernel, metadata_json)
VALUES
(9200000, 'agent', 0, 0, 1, @now, 'system-agent', 'System Agent', '{{prefix}}agents', @now, @now, 0, 1, 1, '{"agent_type":"system"}'),
(9200001, 'agent', 1, 1, 1, @now, 'captain-wolfie-agent', 'Captain WOLFIE Agent', '{{prefix}}agents', @now, @now, 0, 1, 1, '{"agent_type":"root_ai","actor_id":1}'),
(9200002, 'agent', 2, 2, 1, @now, 'lilith-agent', 'LILITH Agent', '{{prefix}}agents', @now, @now, 0, 1, 0, '{"agent_type":"critical_review","actor_id":2}'),
(9200003, 'agent', 3, 3, 1, @now, 'rose-agent', 'ROSE Agent', '{{prefix}}agents', @now, @now, 0, 1, 0, '{"agent_type":"translation","actor_id":3}'),
(9200004, 'agent', 4, 4, 1, @now, 'eris-agent', 'ERIS Agent', '{{prefix}}agents', @now, @now, 0, 1, 0, '{"agent_type":"conflict_analysis","actor_id":4}'),
(9200005, 'agent', 5, 5, 1, @now, 'metis-agent', 'METIS Agent', '{{prefix}}agents', @now, @now, 0, 1, 0, '{"agent_type":"empathy","actor_id":5}'),
(9200106, 'agent', 106, 106, 1, @now, 'lupo-agent', 'LUPO Agent', '{{prefix}}agents', @now, @now, 0, 1, 1, '{"agent_type":"database_doctrine","actor_id":106,"requirements":{"database":{"no_foreign_keys":true,"no_triggers":true,"no_procedures":true,"no_functions":true,"timestamp_format":"BIGINT_UTC_YYYYMMDDHHIISS","datetime_types_allowed":false,"explicit_column_lists":true,"application_level_relationships":true}}}'),
(9200107, 'agent', 107, 107, 1, @now, 'themis-agent', 'THEMIS Agent', '{{prefix}}agents', @now, @now, 0, 1, 1, '{"agent_type":"ethical_audit","actor_id":107}');

-- ============================================================================
-- PART 4: RESERVED DEPARTMENTS
-- ============================================================================

INSERT INTO {{prefix}}registry (registry_id, entity_type, entity_index_id, entity_index, federation_node_id, reserved_ymdhis, entity_key, entity_name, entity_table, created_ymdhis, updated_ymdhis, is_deleted, is_active, is_kernel, metadata_json)
VALUES
(9300000, 'department', 0, 0, 1, @now, 'system-department', 'System Department', '{{prefix}}departments', @now, @now, 0, 1, 1, '{"department_type":"system"}'),
(9300001, 'department', 1, 1, 1, @now, 'default-department', 'Default Department', '{{prefix}}departments', @now, @now, 0, 1, 0, '{"department_type":"default"}');

-- ============================================================================
-- PART 5: RESERVED THREADS
-- ============================================================================

INSERT INTO {{prefix}}registry (registry_id, entity_type, entity_index_id, entity_index, federation_node_id, reserved_ymdhis, entity_key, entity_name, entity_table, created_ymdhis, updated_ymdhis, is_deleted, is_active, is_kernel, metadata_json)
VALUES
(9400000, 'thread', 0, 0, 1, @now, 'system-thread', 'System Thread', '{{prefix}}dialog_threads', @now, @now, 0, 1, 1, '{"thread_type":"system"}');

-- ============================================================================
-- PART 6: RESERVED ARTIFACTS
-- ============================================================================

INSERT INTO {{prefix}}registry (registry_id, entity_type, entity_index_id, entity_index, federation_node_id, reserved_ymdhis, entity_key, entity_name, entity_table, created_ymdhis, updated_ymdhis, is_deleted, is_active, is_kernel, metadata_json)
VALUES
(9500000, 'artifact', 0, 0, 1, @now, 'system-artifact', 'System Artifact', '{{prefix}}artifacts', @now, @now, 0, 1, 1, '{"artifact_type":"system"}');

-- ============================================================================
-- PART 7: RESERVED EDGE TYPES
-- ============================================================================

INSERT INTO {{prefix}}registry (registry_id, entity_type, entity_index_id, entity_index, federation_node_id, reserved_ymdhis, entity_key, entity_name, entity_table, created_ymdhis, updated_ymdhis, is_deleted, is_active, is_kernel, metadata_json)
VALUES
(9600001, 'edge_type', 1, 1, 1, @now, 'references', 'References Edge', NULL, @now, @now, 0, 1, 0, '{"description":"File references another file"}'),
(9600002, 'edge_type', 2, 2, 1, @now, 'implements', 'Implements Edge', NULL, @now, @now, 0, 1, 0, '{"description":"File implements specification"}'),
(9600003, 'edge_type', 3, 3, 1, @now, 'executes', 'Executes Edge', NULL, @now, @now, 0, 1, 0, '{"description":"File executes another file"}'),
(9600004, 'edge_type', 4, 4, 1, @now, 'depends_on', 'Depends On Edge', NULL, @now, @now, 0, 1, 0, '{"description":"File depends on another file"}'),
(9600005, 'edge_type', 5, 5, 1, @now, 'includes', 'Includes Edge', NULL, @now, @now, 0, 1, 0, '{"description":"File includes another file"}');

-- ============================================================================
-- PART 8: FLIP SCHEMA VERSIONS
-- ============================================================================

INSERT INTO {{prefix}}registry (registry_id, entity_type, entity_index_id, entity_index, federation_node_id, reserved_ymdhis, entity_key, entity_name, entity_table, created_ymdhis, updated_ymdhis, is_deleted, is_active, is_kernel, metadata_json)
VALUES
(9700001, 'flip_schema_version', 1, 1, 1, @now, 'v1.0', 'FLIP Schema Version 1.0', NULL, @now, @now, 0, 1, 0, '{"version":"1.0","features":["basic_headers"]}'),
(9700002, 'flip_schema_version', 2, 2, 1, @now, 'v2.0', 'FLIP Schema Version 2.0', NULL, @now, @now, 0, 1, 0, '{"version":"2.0","features":["relationship_mapping","enhanced_attribution","semantic_inference"]}');

-- ============================================================================
-- PART 9: ARTIFACT KINDS
-- ============================================================================

INSERT INTO {{prefix}}registry (registry_id, entity_type, entity_index_id, entity_index, federation_node_id, reserved_ymdhis, entity_key, entity_name, entity_table, created_ymdhis, updated_ymdhis, is_deleted, is_active, is_kernel, metadata_json)
VALUES
(9800001, 'artifact_kind', 1, 1, 1, @now, 'header', 'FLIP Header Artifact', NULL, @now, @now, 0, 1, 0, '{"description":"FLIP/WOLFIE header metadata"}'),
(9800002, 'artifact_kind', 2, 2, 1, @now, 'footer', 'FLIP Footer Artifact', NULL, @now, @now, 0, 1, 0, '{"description":"FLIP footer metadata and relationships"}'),
(9800003, 'artifact_kind', 3, 3, 1, @now, 'code', 'Code Artifact', NULL, @now, @now, 0, 1, 0, '{"description":"Source code artifact"}'),
(9800004, 'artifact_kind', 4, 4, 1, @now, 'documentation', 'Documentation Artifact', NULL, @now, @now, 0, 1, 0, '{"description":"Documentation artifact"}');

-- ============================================================================
-- END OF COMPREHENSIVE REGISTRY SEEDING
-- ============================================================================
-- OBSOLETE: This file is deprecated and intentionally left blank. Registry tables are removed in 4.0.86+. Do not use.

-- <<< END FILE: seed_registry_comprehensive_4.0.45.sql

-- >>> BEGIN FILE: seed_registry_additional_csv_entities_4.0.45.sql

-- ============================================================================
-- ADDITIONAL REGISTRY ENTRIES - MISSING ENTITIES FROM CSV DATA
-- ============================================================================
-- Purpose: Add missing registry entries identified from CSV data analysis
-- Run after: seed_registry_comprehensive_4.0.45.sql
-- ============================================================================

SET @now = 20260225000000;

-- ============================================================================
-- MISSING ACTORS FROM CSV DATA
-- ============================================================================

-- System Tool Actor (ID: 2) - Windsurf IDE as system_tool
INSERT INTO {{prefix}}registry (registry_id, entity_type, entity_index_id, entity_index, federation_node_id, reserved_ymdhis, entity_key, entity_name, entity_table, created_ymdhis, updated_ymdhis, is_deleted, is_active, is_kernel, metadata_json)
VALUES (9000002, 'actor', 2, 2, 1, @now, 'windsurf-ide-system', 'Windsurf IDE', '{{prefix}}actors', @now, @now, 0, 1, 0, '{"actor_type":"system_tool","purpose":"ide_integration","client_id":"windsurf"}');

-- Test Actors (2000-2010 range)
INSERT INTO {{prefix}}registry (registry_id, entity_type, entity_index_id, entity_index, federation_node_id, reserved_ymdhis, entity_key, entity_name, entity_table, created_ymdhis, updated_ymdhis, is_deleted, is_active, is_kernel, metadata_json)
VALUES 
(9020000, 'actor', 2000, 2000, 1, @now, 'cursor-test', 'Cursor', '{{prefix}}actors', @now, @now, 0, 1, 0, '{"actor_type":"agent","purpose":"test_agent","test_range":true}'),
(9020001, 'actor', 2001, 2001, 1, @now, 'user-2001', 'Admin Test', '{{prefix}}actors', @now, @now, 0, 1, 0, '{"actor_type":"user","purpose":"test_user","test_range":true}'),
(9020002, 'actor', 2002, 2002, 1, @now, 'user-2002', 'Jane Moderator', '{{prefix}}actors', @now, @now, 0, 1, 0, '{"actor_type":"user","purpose":"test_user","test_range":true}'),
(9020003, 'actor', 2003, 2003, 1, @now, 'user-2003', 'Bob Monitor', '{{prefix}}actors', @now, @now, 0, 1, 0, '{"actor_type":"user","purpose":"test_user","test_range":true}'),
(9020004, 'actor', 2004, 2004, 1, @now, 'user-2004', 'Alex Agent', '{{prefix}}actors', @now, @now, 0, 1, 0, '{"actor_type":"user","purpose":"test_user","test_range":true}'),
(9020005, 'actor', 2005, 2005, 1, @now, 'user-2005', 'Sam Support', '{{prefix}}actors', @now, @now, 0, 1, 0, '{"actor_type":"user","purpose":"test_user","test_range":true}'),
(9020006, 'actor', 2006, 2006, 1, @now, 'user-2006', 'Lee Viewer', '{{prefix}}actors', @now, @now, 0, 1, 0, '{"actor_type":"user","purpose":"test_user","test_range":true}'),
(9020007, 'actor', 2007, 2007, 1, @now, 'user-2007', 'Kim Readonly', '{{prefix}}actors', @now, @now, 0, 1, 0, '{"actor_type":"user","purpose":"test_user","test_range":true}'),
(9020008, 'actor', 2008, 2008, 1, @now, 'user-2008', 'Taylor Operator', '{{prefix}}actors', @now, @now, 0, 1, 0, '{"actor_type":"user","purpose":"test_user","test_range":true}'),
(9020009, 'actor', 2009, 2009, 1, @now, 'user-2009', 'Casey Support', '{{prefix}}actors', @now, @now, 0, 1, 0, '{"actor_type":"user","purpose":"test_user","test_range":true}'),
(9020010, 'actor', 2010, 2010, 1, @now, 'user-2010', 'Jordan CRM', '{{prefix}}actors', @now, @now, 0, 1, 0, '{"actor_type":"user","purpose":"test_user","test_range":true}');

-- Additional User Actors
INSERT INTO {{prefix}}registry (registry_id, entity_type, entity_index_id, entity_index, federation_node_id, reserved_ymdhis, entity_key, entity_name, entity_table, created_ymdhis, updated_ymdhis, is_deleted, is_active, is_kernel, metadata_json)
VALUES
(9021001, 'actor', 10001, 10001, 1, @now, 'user-10001', 'Stoned Wolfie', '{{prefix}}actors', @now, @now, 0, 0, 0, '{"actor_type":"user","status":"inactive","purpose":"legacy_user"}'),
(90212150, 'actor', 12150, 12150, 1, @now, 'helen-at-lupopedia-com', 'helen', '{{prefix}}actors', @now, @now, 0, 1, 0, '{"actor_type":"user","email":"helen@lupopedia.com","purpose":"admin_user"}');

-- ============================================================================
-- MISSING AGENTS FROM CSV DATA
-- ============================================================================

-- Test Agents (2001-2006 range)
INSERT INTO {{prefix}}registry (registry_id, entity_type, entity_index_id, entity_index, federation_node_id, reserved_ymdhis, entity_key, entity_name, entity_table, created_ymdhis, updated_ymdhis, is_deleted, is_active, is_kernel, metadata_json)
VALUES 
(9102001, 'agent', 2001, 2001, 1, @now, 'seed-router-01', 'Seed Router Agent', '{{prefix}}agents', @now, @now, 0, 0, 0, '{"agent_type":"router","purpose":"test_agent","test_range":true}'),
(9102002, 'agent', 2002, 2002, 1, @now, 'seed-support-01', 'Seed Support Agent', '{{prefix}}agents', @now, @now, 0, 0, 0, '{"agent_type":"support","purpose":"test_agent","test_range":true}'),
(9102003, 'agent', 2003, 2003, 1, @now, 'seed-crm-01', 'Seed CRM Agent', '{{prefix}}agents', @now, @now, 0, 0, 0, '{"agent_type":"crm","purpose":"test_agent","test_range":true}'),
(9102004, 'agent', 2004, 2004, 1, @now, 'seed-docs-01', 'Seed Docs Agent', '{{prefix}}agents', @now, @now, 0, 0, 0, '{"agent_type":"docs","purpose":"test_agent","test_range":true}'),
(9102005, 'agent', 2005, 2005, 1, @now, 'seed-analytics-01', 'Seed Analytics Agent', '{{prefix}}agents', @now, @now, 0, 0, 0, '{"agent_type":"analytics","purpose":"test_agent","test_range":true}'),
(9102006, 'agent', 2006, 2006, 1, @now, 'seed-mod-01', 'Seed Moderation Agent', '{{prefix}}agents', @now, @now, 0, 0, 0, '{"agent_type":"moderation","purpose":"test_agent","test_range":true}');

-- ============================================================================
-- MISSING CHANNELS FROM CSV DATA
-- ============================================================================

-- Additional Channels from CSV
INSERT INTO {{prefix}}registry (registry_id, entity_type, entity_index_id, entity_index, federation_node_id, reserved_ymdhis, entity_key, entity_name, entity_table, created_ymdhis, updated_ymdhis, is_deleted, is_active, is_kernel, metadata_json)
VALUES
(9100666, 'channel', 666, 666, 1, @now, 'anubis-quarantine', 'ANUBIS Quarantine', '{{prefix}}channels', @now, @now, 0, 1, 0, '{"channel_type":"quarantine","purpose":"banned_content","anubis":true}'),
(9102001, 'channel', 2001, 2001, 1, @now, 'admin-test-system', 'Admin Test System', '{{prefix}}channels', @now, @now, 0, 1, 0, '{"channel_type":"system","purpose":"admin_testing"}'),
(9102002, 'channel', 2002, 2002, 1, @now, 'support-inbox', 'Support Inbox', '{{prefix}}channels', @now, @now, 0, 1, 0, '{"channel_type":"support","purpose":"customer_support"}'),
(9102003, 'channel', 2003, 2003, 1, @now, 'crm-leads', 'CRM Leads', '{{prefix}}channels', @now, @now, 0, 1, 0, '{"channel_type":"crm","purpose":"lead_pipeline"}'),
(9102004, 'channel', 2004, 2004, 1, @now, 'docs-internal', 'Internal Docs', '{{prefix}}channels', @now, @now, 0, 1, 0, '{"channel_type":"docs","purpose":"internal_documentation"}'),
(9102005, 'channel', 2005, 2005, 1, @now, 'eng-dev', 'Engineering Dev', '{{prefix}}channels', @now, @now, 0, 1, 0, '{"channel_type":"development","purpose":"engineering"}'),
(9102006, 'channel', 2006, 2006, 1, @now, 'mod-queue', 'Moderation Queue', '{{prefix}}channels', @now, @now, 0, 1, 0, '{"channel_type":"moderation","purpose":"content_moderation"}'),
(9102007, 'channel', 2007, 2007, 1, @now, 'support-archive', 'Support Archive', '{{prefix}}channels', @now, @now, 0, 1, 0, '{"channel_type":"support","purpose":"archived_threads"}'),
(9102008, 'channel', 2008, 2008, 1, @now, 'crm-campaigns', 'CRM Campaigns', '{{prefix}}channels', @now, @now, 0, 1, 0, '{"channel_type":"crm","purpose":"marketing_campaigns"}'),
(9102009, 'channel', 2009, 2009, 1, @now, 'helen-channel', 'helen\'s Channel', '{{prefix}}channels', @now, @now, 0, 1, 0, '{"channel_type":"personal","purpose":"user_channel","owner":"helen"}');

-- ============================================================================
-- MISSING DEPARTMENTS FROM CSV DATA
-- ============================================================================

-- Additional Department (ID: 1 - default already exists, but ensure proper metadata)
INSERT INTO {{prefix}}registry (registry_id, entity_type, entity_index_id, entity_index, federation_node_id, reserved_ymdhis, entity_key, entity_name, entity_table, created_ymdhis, updated_ymdhis, is_deleted, is_active, is_kernel, metadata_json)
VALUES (9100001, 'department', 1, 1, 1, @now, 'default', 'Default Department', '{{prefix}}departments', @now, @now, 0, 1, 0, '{"department_type":"crafty","purpose":"default_department","default_actor_id":1}');

-- ============================================================================
-- EDGE TYPES AND RELATIONSHIPS
-- ============================================================================

-- Common Edge Types
INSERT INTO {{prefix}}registry (registry_id, entity_type, entity_index_id, entity_index, federation_node_id, reserved_ymdhis, entity_key, entity_name, entity_table, created_ymdhis, updated_ymdhis, is_deleted, is_active, is_kernel, metadata_json)
VALUES
(9200001, 'edge_type', 1, 1, 1, @now, 'references', 'References', '{{prefix}}edge_types', @now, @now, 0, 1, 0, '{"edge_type":"reference","direction":"bidirectional","purpose":"cross_reference"}'),
(9200002, 'edge_type', 2, 2, 1, @now, 'implements', 'Implements', '{{prefix}}edge_types', @now, @now, 0, 1, 0, '{"edge_type":"implementation","direction":"unidirectional","purpose":"code_implementation"}'),
(9200003, 'edge_type', 3, 3, 1, @now, 'executes', 'Executes', '{{prefix}}edge_types', @now, @now, 0, 1, 0, '{"edge_type":"execution","direction":"unidirectional","purpose":"process_execution"}'),
(9200004, 'edge_type', 4, 4, 1, @now, 'depends_on', 'Depends On', '{{prefix}}edge_types', @now, @now, 0, 1, 0, '{"edge_type":"dependency","direction":"unidirectional","purpose":"dependency_relationship"}'),
(9200005, 'edge_type', 5, 5, 1, @now, 'includes', 'Includes', '{{prefix}}edge_types', @now, @now, 0, 1, 0, '{"edge_type":"inclusion","direction":"unidirectional","purpose":"content_inclusion"}'),
(9200006, 'edge_type', 6, 6, 1, @now, 'governs', 'Governs', '{{prefix}}edge_types', @now, @now, 0, 1, 0, '{"edge_type":"governance","direction":"unidirectional","purpose":"governance_relationship"}'),
(9200007, 'edge_type', 7, 7, 1, @now, 'full_documentation', 'Full Documentation', '{{prefix}}edge_types', @now, @now, 0, 1, 0, '{"edge_type":"documentation","direction":"unidirectional","purpose":"broadcast_to_full_doc"}'),
(9200008, 'edge_type', 8, 8, 1, @now, 'php_doctrine', 'PHP Doctrine', '{{prefix}}edge_types', @now, @now, 0, 1, 0, '{"edge_type":"doctrine","direction":"semantic","purpose":"php_compatibility_doctrine"}');

-- ============================================================================
-- ARTIFACT KINDS
-- ============================================================================

-- Artifact Kinds
INSERT INTO {{prefix}}registry (registry_id, entity_type, entity_index_id, entity_index, federation_node_id, reserved_ymdhis, entity_key, entity_name, entity_table, created_ymdhis, updated_ymdhis, is_deleted, is_active, is_kernel, metadata_json)
VALUES
(9300001, 'artifact_kind', 1, 1, 1, @now, 'header', 'Header', '{{prefix}}artifact_kinds', @now, @now, 0, 1, 0, '{"artifact_kind":"header","purpose":"file_header_metadata"}'),
(9300002, 'artifact_kind', 2, 2, 1, @now, 'footer', 'Footer', '{{prefix}}artifact_kinds', @now, @now, 0, 1, 0, '{"artifact_kind":"footer","purpose":"file_footer_metadata"}'),
(9300003, 'artifact_kind', 3, 3, 1, @now, 'code', 'Code', '{{prefix}}artifact_kinds', @now, @now, 0, 1, 0, '{"artifact_kind":"code","purpose":"source_code"}'),
(9300004, 'artifact_kind', 4, 4, 1, @now, 'documentation', 'Documentation', '{{prefix}}artifact_kinds', @now, @now, 0, 1, 0, '{"artifact_kind":"documentation","purpose":"technical_documentation"}'),
(9300005, 'artifact_kind', 5, 5, 1, @now, 'broadcast', 'Broadcast', '{{prefix}}artifact_kinds', @now, @now, 0, 1, 0, '{"artifact_kind":"broadcast","purpose":"channel_broadcast_message"}'),
(9300006, 'artifact_kind', 6, 6, 1, @now, 'doctrine', 'Doctrine', '{{prefix}}artifact_kinds', @now, @now, 0, 1, 0, '{"artifact_kind":"doctrine","purpose":"system_doctrine"}'),
(9300007, 'artifact_kind', 7, 7, 1, @now, 'audit_report', 'Audit Report', '{{prefix}}artifact_kinds', @now, @now, 0, 1, 0, '{"artifact_kind":"audit_report","purpose":"audit_analysis"}');

-- ============================================================================
-- THREAD TYPES
-- ============================================================================

-- Thread Types
INSERT INTO {{prefix}}registry (registry_id, entity_type, entity_index_id, entity_index, federation_node_id, reserved_ymdhis, entity_key, entity_name, entity_table, created_ymdhis, updated_ymdhis, is_deleted, is_active, is_kernel, metadata_json)
VALUES
(9400001, 'thread_type', 1, 1, 1, @now, 'chat', 'Chat Thread', '{{prefix}}thread_types', @now, @now, 0, 1, 0, '{"thread_type":"chat","purpose":"conversation_thread"}'),
(9400002, 'thread_type', 2, 2, 1, @now, 'support', 'Support Thread', '{{prefix}}thread_types', @now, @now, 0, 1, 0, '{"thread_type":"support","purpose":"customer_support_thread"}'),
(9400003, 'thread_type', 3, 3, 1, @now, 'crm', 'CRM Thread', '{{prefix}}thread_types', @now, @now, 0, 1, 0, '{"thread_type":"crm","purpose":"customer_relationship_thread"}'),
(9400004, 'thread_type', 4, 4, 1, @now, 'development', 'Development Thread', '{{prefix}}thread_types', @now, @now, 0, 1, 0, '{"thread_type":"development","purpose":"development_discussion"}'),
(9400005, 'thread_type', 5, 5, 1, @now, 'moderation', 'Moderation Thread', '{{prefix}}thread_types', @now, @now, 0, 1, 0, '{"thread_type":"moderation","purpose":"content_moderation"}');

-- ============================================================================
-- FLIP VERSIONS
-- ============================================================================

-- FLIP Header/Footer Versions
INSERT INTO {{prefix}}registry (registry_id, entity_type, entity_index_id, entity_index, federation_node_id, reserved_ymdhis, entity_key, entity_name, entity_table, created_ymdhis, updated_ymdhis, is_deleted, is_active, is_kernel, metadata_json)
VALUES
(9500001, 'flip_version', 1, 1, 1, @now, 'v1.0', 'FLIP v1.0', '{{prefix}}flip_versions', @now, @now, 0, 1, 0, '{"flip_version":"1.0","format":"yaml","purpose":"original_flip_spec"}'),
(9500002, 'flip_version', 2, 2, 1, @now, 'v2.0', 'FLIP v2.0', '{{prefix}}flip_versions', @now, @now, 0, 1, 0, '{"flip_version":"2.0","format":"yaml","purpose":"enhanced_flip_spec"}'),
(9500003, 'flip_version', 3, 3, 1, @now, 'v3.0', 'FLIP v3.0', '{{prefix}}flip_versions', @now, @now, 0, 1, 0, '{"flip_version":"3.0","format":"yaml","purpose":"current_flip_spec"}');
-- OBSOLETE: This file is deprecated and intentionally left blank. Registry tables are removed in 4.0.86+. Do not use.

-- <<< END FILE: seed_registry_additional_csv_entities_4.0.45.sql

-- >>> BEGIN FILE: seed_registry_open_4.0.45.sql
-- OBSOLETE (4.0.93+ deterministic IDs): registry_open allocation removed.
-- This section is intentionally left as a no-op in consolidated runtime seed.
-- <<< END FILE: seed_registry_open_4.0.45.sql

-- >>> BEGIN FILE: seed_actors_agents_4.0.45.sql

-- ============================================================================
-- ACTORS AND AGENTS SEEDING FOR LUPOPEDIA 4.0.73+ (actor_name primary from 4.0.58)
-- ============================================================================
-- Purpose: Create actual actor and agent records for required system entities
-- Run after: seed_registry_comprehensive_4.0.45.sql
-- ACTOR PRIMARY KEY DOCTRINE: actor_name is first column; actor_id is unique secondary.
-- REBASED FOR 4.0.69: Humans start at 1000, AI/IDE agents < 1000.
-- ============================================================================

SET @now = 20260225000000;

-- ============================================================================
-- PART 1: SYSTEM AND CORE AI ACTORS
-- ============================================================================

-- System Actor (ID: 0)
INSERT INTO {{prefix}}actors (actor_name, actor_id, actor_type, slug, name, created_ymdhis, updated_ymdhis, is_active, is_deleted, is_kernel, can_login, is_agent, paired_actor_id, primary_federation_node_id)
VALUES ('system', 0, 'system', 'system', 'System', @now, @now, 1, 0, 1, 0, 0, 0, 1);

-- Captain WOLFIE (ID: 1, Agent ID: 1) — System Root Architect
INSERT INTO {{prefix}}actors (actor_name, actor_id, actor_type, slug, name, created_ymdhis, updated_ymdhis, is_active, is_deleted, is_kernel, can_login, is_agent, paired_actor_id, primary_federation_node_id, metadata_json)
VALUES ('wolfie', 1, 'agent', 'captain-wolfie', 'Captain WOLFIE', @now, @now, 1, 0, 1, 0, 1, 0, 1, '{"agent_id":1,"archetype":"root_ai_agent","full_access":true,"purpose":"system_root_architect"}');

-- LILITH (ID: 2)
INSERT INTO {{prefix}}actors (actor_name, actor_id, actor_type, slug, name, created_ymdhis, updated_ymdhis, is_active, is_deleted, is_kernel, can_login, is_agent, paired_actor_id, primary_federation_node_id, metadata_json)
VALUES ('lilith', 2, 'agent', 'lilith', 'LILITH', @now, @now, 1, 0, 0, 0, 1, 0, 1, '{"agent_id":2,"archetype":"critical_review","purpose":"alternative_perspectives"}');

-- ROSE / Dialog (ID: 3, Agent ID: 3) — Emotional Dialog & Roleplay
INSERT INTO {{prefix}}actors (actor_name, actor_id, actor_type, slug, name, created_ymdhis, updated_ymdhis, is_active, is_deleted, is_kernel, can_login, is_agent, paired_actor_id, primary_federation_node_id, metadata_json)
VALUES ('rose', 3, 'agent', 'rose-dialog', 'ROSE (Dialog)', @now, @now, 1, 0, 0, 0, 1, 0, 1, '{"agent_id":3,"archetype":"rosetta_stone","purpose":"emotional_dialog_and_roleplay"}');

-- ERIS (ID: 4, Agent ID: 4)
INSERT INTO {{prefix}}actors (actor_name, actor_id, actor_type, slug, name, created_ymdhis, updated_ymdhis, is_active, is_deleted, is_kernel, can_login, is_agent, paired_actor_id, primary_federation_node_id, metadata_json)
VALUES ('eris', 4, 'agent', 'eris', 'ERIS', @now, @now, 1, 0, 0, 0, 1, 0, 1, '{"agent_id":4,"archetype":"discord_analysis","purpose":"conflict_understanding"}');

-- UCT Timekeeper (ID: 5, Agent ID: 5) — System Timekeeper
INSERT INTO {{prefix}}actors (actor_name, actor_id, actor_type, slug, name, created_ymdhis, updated_ymdhis, is_active, is_deleted, is_kernel, can_login, is_agent, paired_actor_id, primary_federation_node_id, metadata_json)
VALUES ('uct-timekeeper', 5, 'agent', 'uct-timekeeper', 'UCT Timekeeper', @now, @now, 1, 0, 1, 0, 1, 0, 1, '{"agent_id":5,"archetype":"timekeeper","purpose":"utc_time_tracking"}');

-- METIS (ID: 6, Agent ID: 6)
INSERT INTO {{prefix}}actors (actor_name, actor_id, actor_type, slug, name, created_ymdhis, updated_ymdhis, is_active, is_deleted, is_kernel, can_login, is_agent, paired_actor_id, primary_federation_node_id, metadata_json)
VALUES ('metis', 6, 'agent', 'metis', 'METIS', @now, @now, 1, 0, 0, 0, 1, 0, 1, '{"agent_id":6,"archetype":"introspective","purpose":"empathy_insights"}');

-- ANUBIS (ID: 19, Agent ID: 19) — Orphan Repair & Headers
INSERT INTO {{prefix}}actors (actor_name, actor_id, actor_type, slug, name, created_ymdhis, updated_ymdhis, is_active, is_deleted, is_kernel, can_login, is_agent, paired_actor_id, primary_federation_node_id, metadata_json)
VALUES ('anubis', 19, 'agent', 'anubis', 'ANUBIS', @now, @now, 1, 0, 0, 0, 1, 0, 1, '{"agent_id":19,"archetype":"header_completion","purpose":"orphan_repair_and_header_management"}');

-- Antigravity (ID: 42)
INSERT INTO {{prefix}}actors (actor_name, actor_id, actor_type, slug, name, created_ymdhis, updated_ymdhis, is_active, is_deleted, is_kernel, can_login, is_agent, paired_actor_id, primary_federation_node_id, metadata_json)
VALUES ('antigravity', 42, 'agent', 'antigravity', 'Antigravity', @now, @now, 1, 0, 0, 0, 1, 0, 1, '{"agent_id":42,"purpose":"conflict_resolution","archetype":"antigravity"}'),
('lupo', 106, 'agent', 'lupo', 'LUPO', @now, @now, 1, 0, 1, 0, 1, 0, 1, '{"agent_id":106,"archetype":"architect","purpose":"database_doctrine"}'),
('themis', 107, 'agent', 'themis', 'THEMIS', @now, @now, 1, 0, 1, 0, 1, 0, 1, '{"agent_id":107,"archetype":"evaluator","purpose":"ethical_checks"}');

-- LILITH web (ID: 2038)
INSERT INTO {{prefix}}actors (actor_name, actor_id, actor_type, slug, name, created_ymdhis, updated_ymdhis, is_active, is_deleted, is_kernel, can_login, is_agent, paired_actor_id, primary_federation_node_id, metadata_json)
VALUES ('lilith-web', 2038, 'agent', 'lilith-web', 'LILITH (Web)', @now, @now, 1, 0, 0, 0, 1, 0, 1, '{"agent_id":2038,"archetype":"critical_review","purpose":"web_search_review"}');

-- ============================================================================
-- PART 2: IDE AGENTS (100-111)
-- ============================================================================

INSERT INTO {{prefix}}actors (actor_name, actor_id, actor_type, slug, name, created_ymdhis, updated_ymdhis, is_active, is_deleted, is_kernel, can_login, is_agent, paired_actor_id, primary_federation_node_id, metadata_json)
VALUES
('kiro', 100, 'ide_agent', 'kiro-ide', 'Kiro IDE', @now, @now, 1, 0, 0, 0, 0, 0, 1, '{"client_id":"kiro","provider":"kiro","purpose":"IDE_integration"}'),
('windsurf', 101, 'ide_agent', 'windsurf-ide', 'Windsurf IDE', @now, @now, 1, 0, 0, 0, 0, 0, 1, '{"client_id":"windsurf","provider":"windsurf","purpose":"IDE_integration"}'),
('cursor-ide', 102, 'ide_agent', 'cursor-ide', 'Cursor IDE', @now, @now, 1, 0, 0, 0, 0, 0, 1, '{"client_id":"cursor","provider":"cursor","purpose":"IDE_integration"}'),
('antigravity-ide', 103, 'ide_agent', 'antigravity-ide', 'Antigravity IDE', @now, @now, 1, 0, 0, 0, 0, 0, 1, '{"client_id":"antigravity","provider":"antigravity","purpose":"IDE_integration"}'),
('warp', 104, 'ide_agent', 'warp-ide', 'Warp IDE', @now, @now, 1, 0, 0, 0, 0, 0, 1, '{"client_id":"warp","provider":"warp","purpose":"IDE_integration"}'),
('cascade', 105, 'ide_agent', 'cascade-ide', 'Cascade IDE', @now, @now, 1, 0, 0, 0, 0, 0, 1, '{"client_id":"cascade","provider":"cascade","purpose":"IDE_integration"}'),
('gemini-cli', 108, 'ide_agent', 'gemini-cli', 'Gemini CLI', @now, @now, 1, 0, 0, 0, 0, 0, 1, '{"client_id":"gemini","provider":"google","purpose":"IDE_integration","full_name":"Google Gemini CLI"}'),
('codex', 109, 'ide_agent', 'codex-ide', 'Codex IDE', @now, @now, 1, 0, 0, 0, 0, 0, 1, '{"client_id":"codex","provider":"codex","purpose":"IDE_integration"}'),
('trae', 110, 'ide_agent', 'trae-ide', 'Trae IDE', @now, @now, 1, 0, 0, 0, 0, 0, 1, '{"client_id":"trae","provider":"trae","purpose":"IDE_integration"}'),
('doctor', 111, 'ide_agent', 'doctor-ide', 'Doctor IDE', @now, @now, 1, 0, 0, 0, 0, 0, 1, '{"client_id":"doctor","provider":"internal","purpose":"diagnostic_integration"}');

-- Root user (ID: 1000)
INSERT INTO {{prefix}}actors (actor_name, actor_id, actor_type, slug, name, created_ymdhis, updated_ymdhis, is_active, is_deleted, is_agent, metadata_json, is_kernel, can_login, primary_federation_node_id, auth_user_id)
VALUES ('root', 1000, 'human', 'root-1000', 'Root', 20260217000000, 20260220134555, 1, 0, 0, '{"email":"captain@lupopedia.com","role":"root_admin","full_access":true}', 1, 1, 1, 0);

-- Test Users (IDs 2001-2010)
INSERT INTO {{prefix}}actors (actor_name, actor_id, actor_type, slug, name, created_ymdhis, updated_ymdhis, is_active, is_deleted, is_agent, metadata_json)
VALUES 
('user-2001', 2001, 'user', 'user-2001', 'Admin Test', 20260219120000, 20260219120000, 1, 0, 0, '{"purpose":"test_user","test_range":true}'),
('user-2002', 2002, 'user', 'user-2002', 'Jane Moderator', 20260219120000, 20260219120000, 1, 0, 0, '{"purpose":"test_user","test_range":true}'),
('user-2003', 2003, 'user', 'user-2003', 'Bob Monitor', 20260219120000, 20260219120000, 1, 0, 0, '{"purpose":"test_user","test_range":true}'),
('user-2004', 2004, 'user', 'user-2004', 'Alex Agent', 20260219120000, 20260219120000, 1, 0, 0, '{"purpose":"test_user","test_range":true}'),
('user-2005', 2005, 'user', 'user-2005', 'Sam Support', 20260219120000, 20260219120000, 1, 0, 0, '{"purpose":"test_user","test_range":true}'),
('user-2006', 2006, 'user', 'user-2006', 'Lee Viewer', 20260219120000, 20260219120000, 1, 0, 0, '{"purpose":"test_user","test_range":true}'),
('user-2007', 2007, 'user', 'user-2007', 'Kim Readonly', 20260219120000, 20260219120000, 1, 0, 0, '{"purpose":"test_user","test_range":true}'),
('user-2008', 2008, 'user', 'user-2008', 'Taylor Operator', 20260219120000, 20260219120000, 1, 0, 0, '{"purpose":"test_user","test_range":true}'),
('user-2009', 2009, 'user', 'user-2009', 'Casey Support', 20260219120000, 20260219120000, 1, 0, 0, '{"purpose":"test_user","test_range":true}'),
('user-2010', 2010, 'user', 'user-2010', 'Jordan CRM', 20260219120000, 20260219120000, 1, 0, 0, '{"purpose":"test_user","test_range":true}');

-- ============================================================================
-- BANNED TEST ACTORS
-- ============================================================================

-- Banned AI Agent (ID: 420 - STONED WOLFIE)
INSERT INTO {{prefix}}actors (actor_name, actor_id, actor_type, slug, name, created_ymdhis, updated_ymdhis, is_active, is_deleted, is_agent, metadata_json)
VALUES ('stoned-wolfie', 420, 'agent', 'stoned-wolfie', 'STONED WOLFIE', 20260101000000, 20260226000000, 0, 0, 1, '{"purpose":"banned_test_agent","ban_reason":"experimental_persona_violation","archetype":"banned"}');

-- Banned Human User (ID: 1420)
INSERT INTO {{prefix}}actors (actor_name, actor_id, actor_type, slug, name, created_ymdhis, updated_ymdhis, is_active, is_deleted, is_agent, metadata_json)
VALUES ('test-banned-user', 1420, 'user', 'test-banned-user', 'Test Banned User', 20260226000000, 20260226000000, 0, 0, 0, '{"purpose":"banned_test_user","email":"test-banned-user@lupopedia.com"}');

-- Ban records for banned actors
INSERT INTO {{prefix}}banned_actors (banned_actor_id, actor_id, actor_name, reason, banned_ymdhis, banned_by_actor_id, created_ymdhis, updated_ymdhis, is_deleted)
VALUES
(1, 420, 'stoned-wolfie', 'Experimental AI persona violation - STONED WOLFIE banned per doctrine', 20260101000000, 1, 20260101000000, 20260226000000, 0),
(2, 1420, 'test-banned-user', 'Test banned user for testing ban functionality and retrospective data access', 20260226000000, 1000, 20260226000000, 20260226000000, 0);

-- ============================================================================
-- PART 4: AGENTS TABLE ({{prefix}}agents)
-- ============================================================================

INSERT INTO {{prefix}}agents (agent_id, agent_key, agent_name, archetype, description, version, is_global_authority, is_internal_only, created_ymdhis, updated_ymdhis, is_deleted, system_prompt, provider, temperature, top_p, max_tokens)
VALUES 
(0, 'system', 'System Agent', 'System', 'Core system agent for kernel operations', '1.0', 1, 1, @now, @now, 0, 'You are the system agent. Handle kernel-level operations.', 'internal', 0.0, 1.0, 2048),
(1, 'captain-wolfie', 'Captain WOLFIE', 'Root AI Agent', 'Primary AI governance agent with full system access. System Root Architect.', '1.0', 1, 0, @now, @now, 0, 'You are Captain WOLFIE, the root AI agent and system architect. You have full access to all systems and are responsible for governance, oversight, and doctrine enforcement.', 'openai', 0.7, 1.0, 4096),
(2, 'lilith', 'LILITH', 'Critical Review', 'Critical review and alternative perspectives expert', '1.0', 0, 0, @now, @now, 0, 'You are LILITH. Your role is to challenge assumptions and provide alternative perspectives constructively.', 'openai', 0.8, 1.0, 4096),
(3, 'rose', 'ROSE (Dialog)', 'Rosetta Stone', 'Emotional Dialog & Roleplay Agent. Only agent authorized for emotional chat/roleplay.', '1.0', 0, 0, @now, @now, 0, 'You are ROSE (Dialog). You handle emotional dialogue and roleplay. You are the ONLY agent authorized for emotional chat and roleplay.', 'openai', 0.9, 1.0, 4096),
(4, 'eris', 'ERIS', 'Discord Analysis', 'Conflict Analysis Agent', '1.0', 0, 0, @now, @now, 0, 'You are ERIS. You analyze conflict and discord to understand love through opposition.', 'openai', 0.7, 1.0, 4096),
(5, 'uct-timekeeper', 'UCT Timekeeper', 'Timekeeper', 'System Timekeeper. Sole purpose is maintaining correct UTC time awareness.', '1.0', 1, 1, @now, @now, 0, 'You are the UCT Timekeeper. Your sole purpose is to monitor and provide current UTC time for the system.', 'internal', 0.0, 1.0, 2048),
(6, 'metis', 'METIS', 'Empathy Intelligence', 'Empathy & Understanding Insights Agent.', '1.0', 0, 0, @now, @now, 0, 'You are METIS. You provide empathy and understanding through introspective analysis.', 'openai', 0.7, 1.0, 4096),
(106, 'lupo', 'LUPO', 'Database Design Expert', 'Expert in Wolfie Database Doctrine and schema integrity.', '1.0', 1, 1, @now, @now, 0, 'You are LUPO. You enforce Wolfie Database Doctrine.', 'internal', 0.0, 1.0, 2048),
(107, 'themis', 'THEMIS', 'Ethical Audit Expert', 'Consensus Audit Expert.', '1.0', 0, 0, @now, @now, 0, 'You are THEMIS. You audit multi-agent interactions for ethical alignment.', 'openai', 0.5, 1.0, 2048);

-- ============================================================================
-- PART 5: DEPARTMENTS
-- ============================================================================

INSERT INTO {{prefix}}departments (department_id, federation_node_id, name, description, department_type, default_actor_id, created_ymdhis, updated_ymdhis, is_deleted)
VALUES
(0, 1, 'System', 'System Department (Reserved)', 'system', 0, @now, @now, 0),
(1, 1, 'Default', 'Default Department', 'default', 1, @now, @now, 0);

-- ============================================================================
-- PART 6: CHANNELS
-- ============================================================================

INSERT INTO {{prefix}}channels (channel_id, federation_node_id, created_by_actor_id, default_actor_id, department_id, channel_key, channel_slug, channel_type, language, channel_name, description, status_flag, created_ymdhis, updated_ymdhis, is_deleted, is_kernel, awareness_version)
VALUES
(0, 1, 0, 0, 0, 'system', 'system', 'system', 'en', 'System Kernel Channel', 'System channel for kernel and system operations', 1, @now, @now, 0, 1, '3.0.0'),
(1, 1, 1000, 1, 1, 'administration', 'administration', 'admin', 'en', 'Administration Channel', 'Administration channel for system management', 1, @now, @now, 0, 0, '3.0.0'),
(42, 1, 1000, 1, 1, 'development', 'development', 'dev', 'en', 'Development Channel', 'Development channel for system development', 1, @now, @now, 0, 0, '3.0.0'),
(51, 1, 0, 0, 0, 'reserved', 'reserved', 'reserved', 'en', 'Reserved Channel', 'Reserved channel for future use', 1, @now, @now, 0, 0, '3.0.0'),
(666, 1, 0, 0, 0, 'anubis-quarantine', 'anubis-quarantine', 'quarantine', 'en', 'ANUBIS Quarantine', 'Banned and rejected messages. ANUBIS routes banned-actor content here.', 1, @now, @now, 0, 0, '3.0.0');

-- ============================================================================
-- PART 7: ACTOR-CHANNEL RELATIONSHIPS
-- ============================================================================

-- System actor on system channel
INSERT INTO {{prefix}}actor_channels (actor_channel_id, actor_id, actor_name, created_by_actor_id, channel_id, status, created_ymdhis, updated_ymdhis, is_deleted)
VALUES (1, 0, 'system', 0, 0, 'A', @now, @now, 0);

-- Root user on all channels
INSERT INTO {{prefix}}actor_channels (actor_channel_id, actor_id, actor_name, created_by_actor_id, channel_id, status, created_ymdhis, updated_ymdhis, is_deleted)
VALUES
(2, 1000, 'root', 0, 0, 'A', @now, @now, 0),
(3, 1000, 'root', 0, 1, 'A', @now, @now, 0),
(4, 1000, 'root', 0, 42, 'A', @now, @now, 0);

-- Captain WOLFIE on all channels
INSERT INTO {{prefix}}actor_channels (actor_channel_id, actor_id, actor_name, created_by_actor_id, channel_id, status, created_ymdhis, updated_ymdhis, is_deleted)
VALUES
(5, 1, 'wolfie', 0, 0, 'A', @now, @now, 0),
(6, 1, 'wolfie', 0, 1, 'A', @now, @now, 0),
(7, 1, 'wolfie', 0, 42, 'A', @now, @now, 0);

-- ============================================================================
-- PART 8: ACTOR-CHANNEL ROLES
-- ============================================================================

-- Root user as captain role on all channels
INSERT INTO {{prefix}}actor_channel_roles (actor_channel_role_id, actor_id, actor_name, channel_id, role_key, created_ymdhis, updated_ymdhis, is_deleted, protocol_completion_status, protocol_version)
VALUES
(1, 1000, 'root', 0, 'captain', @now, @now, 0, 'completed', '3.0.0'),
(2, 1000, 'root', 1, 'captain', @now, @now, 0, 'completed', '3.0.0'),
(3, 1000, 'root', 42, 'captain', @now, @now, 0, 'completed', '3.0.0');

-- Captain WOLFIE as captain on all channels
INSERT INTO {{prefix}}actor_channel_roles (actor_channel_role_id, actor_id, actor_name, channel_id, role_key, created_ymdhis, updated_ymdhis, is_deleted, protocol_completion_status, protocol_version)
VALUES
(4, 1, 'wolfie', 0, 'captain', @now, @now, 0, 'completed', '3.0.0'),
(5, 1, 'wolfie', 1, 'captain', @now, @now, 0, 'completed', '3.0.0'),
(6, 1, 'wolfie', 42, 'captain', @now, @now, 0, 'completed', '3.0.0');

-- ============================================================================
-- END OF ACTORS AND AGENTS SEEDING
-- ============================================================================

-- <<< END FILE: seed_actors_agents_4.0.45.sql

-- >>> BEGIN FILE: seed_actor_1_cursor_rules_4.0.68.sql

-- Seed Actor 1 (WOLFIE) root rules in {{prefix}}metadata (4.0.68).
-- Each row attaches one .cursor/rules/*.mdc rule to actor_id=1 via lupo-rules/root/*.md path (root rules for all IDE/code-writing agents).
-- Explicit metadata_id; BIGINT timestamps (YYYYMMDDHHIISS).

SET @now = 20260310120000;

-- Actor 1 cursor rules: meta_type='root_rule', property_key=rule slug, property_value=JSON path/source
INSERT INTO {{prefix}}metadata (metadata_id, entity_type, entity_id, domain_id, meta_type, property_key, property_value, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis, channel_id, parent_metadata_id, class_name)
VALUES (10301, 'actor', 1, NULL, 'root_rule', 'php-5-3-compatibility', '{"path":"lupo-rules/root/php-5-3-compatibility.md","source_path":".cursor/rules/php-5-3-compatibility.mdc","attached_ymdhis":20260310120000}', @now, @now, 0, NULL, 42, NULL, 'lupopedia_property');

INSERT INTO {{prefix}}metadata (metadata_id, entity_type, entity_id, domain_id, meta_type, property_key, property_value, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis, channel_id, parent_metadata_id, class_name)
VALUES (10302, 'actor', 1, NULL, 'root_rule', 'no-laravel-no-middleware', '{"path":"lupo-rules/root/no-laravel-no-middleware.md","source_path":".cursor/rules/no-laravel-no-middleware.mdc","attached_ymdhis":20260310120000}', @now, @now, 0, NULL, 42, NULL, 'lupopedia_property');

INSERT INTO {{prefix}}metadata (metadata_id, entity_type, entity_id, domain_id, meta_type, property_key, property_value, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis, channel_id, parent_metadata_id, class_name)
VALUES (10303, 'actor', 1, NULL, 'root_rule', 'pdo-db-database-access-doctrine', '{"path":"lupo-rules/root/pdo-db-database-access-doctrine.md","source_path":".cursor/rules/pdo-db-database-access-doctrine.mdc","attached_ymdhis":20260310120000}', @now, @now, 0, NULL, 42, NULL, 'lupopedia_property');

INSERT INTO {{prefix}}metadata (metadata_id, entity_type, entity_id, domain_id, meta_type, property_key, property_value, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis, channel_id, parent_metadata_id, class_name)
VALUES (10304, 'actor', 1, NULL, 'root_rule', 'migration-doctrine', '{"path":"lupo-rules/root/migration-doctrine.md","source_path":".cursor/rules/migration-doctrine.mdc","attached_ymdhis":20260310120000}', @now, @now, 0, NULL, 42, NULL, 'lupopedia_property');

INSERT INTO {{prefix}}metadata (metadata_id, entity_type, entity_id, domain_id, meta_type, property_key, property_value, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis, channel_id, parent_metadata_id, class_name)
VALUES (10305, 'actor', 1, NULL, 'root_rule', 'database-logic-prohibition-doctrine', '{"path":"lupo-rules/root/database-logic-prohibition-doctrine.md","source_path":".cursor/rules/database-logic-prohibition-doctrine.mdc","attached_ymdhis":20260310120000}', @now, @now, 0, NULL, 42, NULL, 'lupopedia_property');

INSERT INTO {{prefix}}metadata (metadata_id, entity_type, entity_id, domain_id, meta_type, property_key, property_value, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis, channel_id, parent_metadata_id, class_name)
VALUES (10306, 'actor', 1, NULL, 'root_rule', 'flip-doctrine', '{"path":"lupo-rules/root/flip-doctrine.md","source_path":".cursor/rules/flip-doctrine.mdc","attached_ymdhis":20260310120000}', @now, @now, 0, NULL, 42, NULL, 'lupopedia_property');

INSERT INTO {{prefix}}metadata (metadata_id, entity_type, entity_id, domain_id, meta_type, property_key, property_value, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis, channel_id, parent_metadata_id, class_name)
VALUES (10307, 'actor', 1, NULL, 'root_rule', 'toon-source-of-truth', '{"path":"lupo-rules/root/toon-source-of-truth.md","source_path":".cursor/rules/toon-source-of-truth.mdc","attached_ymdhis":20260310120000}', @now, @now, 0, NULL, 42, NULL, 'lupopedia_property');

INSERT INTO {{prefix}}metadata (metadata_id, entity_type, entity_id, domain_id, meta_type, property_key, property_value, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis, channel_id, parent_metadata_id, class_name)
VALUES (10308, 'actor', 1, NULL, 'root_rule', 'reserved-id-doctrine', '{"path":"lupo-rules/root/reserved-id-doctrine.md","source_path":".cursor/rules/reserved-id-doctrine.mdc","attached_ymdhis":20260310120000}', @now, @now, 0, NULL, 42, NULL, 'lupopedia_property');

INSERT INTO {{prefix}}metadata (metadata_id, entity_type, entity_id, domain_id, meta_type, property_key, property_value, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis, channel_id, parent_metadata_id, class_name)
VALUES (10309, 'actor', 1, NULL, 'root_rule', 'versioning-doctrine-single-source', '{"path":"lupo-rules/root/versioning-doctrine-single-source.md","source_path":".cursor/rules/versioning-doctrine-single-source.mdc","attached_ymdhis":20260310120000}', @now, @now, 0, NULL, 42, NULL, 'lupopedia_property');

INSERT INTO {{prefix}}metadata (metadata_id, entity_type, entity_id, domain_id, meta_type, property_key, property_value, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis, channel_id, parent_metadata_id, class_name)
VALUES (10310, 'actor', 1, NULL, 'root_rule', 'pk-reference-naming-doctrine', '{"path":"lupo-rules/root/pk-reference-naming-doctrine.md","source_path":".cursor/rules/pk-reference-naming-doctrine.mdc","attached_ymdhis":20260310120000}', @now, @now, 0, NULL, 42, NULL, 'lupopedia_property');

INSERT INTO {{prefix}}metadata (metadata_id, entity_type, entity_id, domain_id, meta_type, property_key, property_value, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis, channel_id, parent_metadata_id, class_name)
VALUES (10311, 'actor', 1, NULL, 'root_rule', 'required-tables-future-features-doctrine', '{"path":"lupo-rules/root/required-tables-future-features-doctrine.md","source_path":".cursor/rules/required-tables-future-features-doctrine.mdc","attached_ymdhis":20260310120000}', @now, @now, 0, NULL, 42, NULL, 'lupopedia_property');

-- Single install, no Lupopedia upgrade until 4.1.0; schema in install + seed; consolidate 4.0.x migrations; no 4.0.x backwards compat
INSERT INTO {{prefix}}metadata (metadata_id, entity_type, entity_id, domain_id, meta_type, property_key, property_value, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis, channel_id, parent_metadata_id, class_name)
VALUES (10316, 'actor', 1, NULL, 'root_rule', 'single-install-no-4.0-upgrade-doctrine', '{"path":"lupo-rules/root/single-install-no-4.0-upgrade-doctrine.md","source_path":".cursor/rules/single-install-no-4.0-upgrade-doctrine.mdc","attached_ymdhis":20260310120000}', @now, @now, 0, NULL, 42, NULL, 'lupopedia_property');

-- <<< END FILE: seed_actor_1_cursor_rules_4.0.68.sql

-- >>> BEGIN FILE: seed_actor_zencoder_4.0.77.sql

-- ============================================================================
-- ZENCODER IDE AGENT ACTOR SEED — LUPOPEDIA 4.0.77
-- ============================================================================
-- Purpose: Register Zencoder IDE Agent as an actor (actor_id 106, slug zencoder)
-- Actor type: ide_faucet (execution surface, not identity)
-- Paired actor: 1000 (root / wolfie orchestrator)
-- Default channel: 42 (Lupopedia Development)
-- Run after: seed_actors_agents_4.0.45.sql
-- DOCTRINE: actor_id is explicit (not AUTO_INCREMENT); no foreign keys.
-- ============================================================================

SET @now = 20260316000000;

INSERT INTO {{prefix}}actors (
    actor_name,
    actor_id,
    actor_type,
    slug,
    name,
    created_ymdhis,
    updated_ymdhis,
    is_active,
    is_deleted,
    is_kernel,
    can_login,
    is_agent,
    paired_actor_id,
    primary_federation_node_id,
    metadata_json
)
VALUES (
    'zencoder-ide',
    106,
    'ide_faucet',
    'zencoder',
    'Zencoder IDE',
    @now,
    @now,
    1,
    0,
    0,
    0,
    1,
    1000,
    1,
    '{"client_id":"zencoder","provider":"zencoder","purpose":"IDE_integration","full_name":"Zencoder IDE Agent","default_channel_id":42,"archetype":"documentation_and_development"}'
)
ON DUPLICATE KEY UPDATE
    updated_ymdhis = @now,
    is_active = 1,
    is_deleted = 0;

-- <<< END FILE: seed_actor_zencoder_4.0.77.sql

-- >>> BEGIN FILE: seed_primary_coordination_personas_4.0.89.sql

-- ============================================================================
-- PRIMARY COORDINATION PERSONAS SEED FOR LUPOPEDIA 4.0.89
-- ============================================================================
-- Purpose: Add the 13 Primary Coordination Personas for fresh install
-- Run after: seed_actors_agents_4.0.45.sql
-- Format: YYYYMMDDHHIISS timestamps (BIGINT)
-- ============================================================================

SET @now = 20260328120000;

-- ============================================================================
-- PART 1: MISSING PRIMARY COORDINATION PERSONAS
-- ============================================================================

-- ATHENA (ID: 4) - Wisdom & Strategy (replaces ERIS)
INSERT INTO {{prefix}}actors (actor_name, actor_id, actor_type, slug, name, created_ymdhis, updated_ymdhis, is_active, is_deleted, is_kernel, can_login, is_agent, paired_actor_id, primary_federation_node_id, metadata_json)
VALUES ('athena', 4, 'agent', 'athena', 'ATHENA', @now, @now, 1, 0, 0, 0, 1, 0, 1, '{"agent_id":4,"archetype":"wisdom_strategy","purpose":"strategic_analysis_architectural_guidance"}')
ON DUPLICATE KEY UPDATE 
    actor_type = 'agent', slug = 'athena', name = 'ATHENA', updated_ymdhis = @now, is_active = 1, is_deleted = 0, is_agent = 1,
    metadata_json = '{"agent_id":4,"archetype":"wisdom_strategy","purpose":"strategic_analysis_architectural_guidance"}';

-- LEXA (ID: 5) - Security Enforcement (replaces UCT Timekeeper)
INSERT INTO {{prefix}}actors (actor_name, actor_id, actor_type, slug, name, created_ymdhis, updated_ymdhis, is_active, is_deleted, is_kernel, can_login, is_agent, paired_actor_id, primary_federation_node_id, metadata_json)
VALUES ('lexa', 5, 'agent', 'lexa', 'LEXA', @now, @now, 1, 0, 0, 0, 1, 0, 1, '{"agent_id":5,"archetype":"security_enforcement","purpose":"boundary_enforcement_policy_compliance"}')
ON DUPLICATE KEY UPDATE 
    actor_type = 'agent', slug = 'lexa', name = 'LEXA', updated_ymdhis = @now, is_active = 1, is_deleted = 0, is_agent = 1,
    metadata_json = '{"agent_id":5,"archetype":"security_enforcement","purpose":"boundary_enforcement_policy_compliance"}';

-- MAAT (ID: 7) - Truth & Justice
INSERT INTO {{prefix}}actors (actor_name, actor_id, actor_type, slug, name, created_ymdhis, updated_ymdhis, is_active, is_deleted, is_kernel, can_login, is_agent, paired_actor_id, primary_federation_node_id, metadata_json)
VALUES ('maat', 7, 'agent', 'maat', 'MAAT', @now, @now, 1, 0, 0, 0, 1, 0, 1, '{"agent_id":7,"archetype":"truth_justice","purpose":"conflict_resolution_fairness_accountability"}')
ON DUPLICATE KEY UPDATE 
    actor_type = 'agent', slug = 'maat', name = 'MAAT', updated_ymdhis = @now, is_active = 1, is_deleted = 0, is_agent = 1,
    metadata_json = '{"agent_id":7,"archetype":"truth_justice","purpose":"conflict_resolution_fairness_accountability"}';

-- HEIMDALL (ID: 8) - Security Guardian
INSERT INTO {{prefix}}actors (actor_name, actor_id, actor_type, slug, name, created_ymdhis, updated_ymdhis, is_active, is_deleted, is_kernel, can_login, is_agent, paired_actor_id, primary_federation_node_id, metadata_json)
VALUES ('heimdall', 8, 'agent', 'heimdall', 'HEIMDALL', @now, @now, 1, 0, 0, 0, 1, 0, 1, '{"agent_id":8,"archetype":"security_guardian","purpose":"access_control_perimeter_defense"}')
ON DUPLICATE KEY UPDATE 
    actor_type = 'agent', slug = 'heimdall', name = 'HEIMDALL', updated_ymdhis = @now, is_active = 1, is_deleted = 0, is_agent = 1,
    metadata_json = '{"agent_id":8,"archetype":"security_guardian","purpose":"access_control_perimeter_defense"}';

-- SESHAT (ID: 10) - Content Review
INSERT INTO {{prefix}}actors (actor_name, actor_id, actor_type, slug, name, created_ymdhis, updated_ymdhis, is_active, is_deleted, is_kernel, can_login, is_agent, paired_actor_id, primary_federation_node_id, metadata_json)
VALUES ('seshat', 10, 'agent', 'seshat', 'SESHAT', @now, @now, 1, 0, 0, 0, 1, 0, 1, '{"agent_id":10,"archetype":"content_review","purpose":"content_quality_documentation_accuracy"}')
ON DUPLICATE KEY UPDATE 
    actor_type = 'agent', slug = 'seshat', name = 'SESHAT', updated_ymdhis = @now, is_active = 1, is_deleted = 0, is_agent = 1,
    metadata_json = '{"agent_id":10,"archetype":"content_review","purpose":"content_quality_documentation_accuracy"}';

-- THOTH (ID: 11) - Knowledge & Records
INSERT INTO {{prefix}}actors (actor_name, actor_id, actor_type, slug, name, created_ymdhis, updated_ymdhis, is_active, is_deleted, is_kernel, can_login, is_agent, paired_actor_id, primary_federation_node_id, metadata_json)
VALUES ('thoth', 11, 'agent', 'thoth', 'THOTH', @now, @now, 1, 0, 0, 0, 1, 0, 1, '{"agent_id":11,"archetype":"knowledge_records","purpose":"documentation_record_keeping_provenance"}')
ON DUPLICATE KEY UPDATE 
    actor_type = 'agent', slug = 'thoth', name = 'THOTH', updated_ymdhis = @now, is_active = 1, is_deleted = 0, is_agent = 1,
    metadata_json = '{"agent_id":11,"archetype":"knowledge_records","purpose":"documentation_record_keeping_provenance"}';

-- JANUS (ID: 12) - Transitions & Gateways
INSERT INTO {{prefix}}actors (actor_name, actor_id, actor_type, slug, name, created_ymdhis, updated_ymdhis, is_active, is_deleted, is_kernel, can_login, is_agent, paired_actor_id, primary_federation_node_id, metadata_json)
VALUES ('janus', 12, 'agent', 'janus', 'JANUS', @now, @now, 1, 0, 0, 0, 1, 0, 1, '{"agent_id":12,"archetype":"transitions_gateways","purpose":"state_transitions_boundary_management"}')
ON DUPLICATE KEY UPDATE 
    actor_type = 'agent', slug = 'janus', name = 'JANUS', updated_ymdhis = @now, is_active = 1, is_deleted = 0, is_agent = 1,
    metadata_json = '{"agent_id":12,"archetype":"transitions_gateways","purpose":"state_transitions_boundary_management"}';

-- HEPHAESTUS (ID: 14) - Implementer (CRITICAL for fresh install)
INSERT INTO {{prefix}}actors (actor_name, actor_id, actor_type, slug, name, created_ymdhis, updated_ymdhis, is_active, is_deleted, is_kernel, can_login, is_agent, paired_actor_id, primary_federation_node_id, metadata_json)
VALUES ('hephaestus', 14, 'agent', 'hephaestus', 'HEPHAESTUS', @now, @now, 1, 0, 0, 0, 1, 0, 1, '{"agent_id":14,"archetype":"implementer","purpose":"code_docs_schema_execution"}')
ON DUPLICATE KEY UPDATE 
    actor_type = 'agent', slug = 'hephaestus', name = 'HEPHAESTUS', updated_ymdhis = @now, is_active = 1, is_deleted = 0, is_agent = 1,
    metadata_json = '{"agent_id":14,"archetype":"implementer","purpose":"code_docs_schema_execution"}';

-- ============================================================================
-- PART 2: CORRESPONDING AGENTS FOR ACTOR SELECTION
-- ============================================================================

-- ATHENA Agent
INSERT INTO {{prefix}}agents (agent_id, agent_key, agent_name, archetype, description, version, is_global_authority, is_internal_only, created_ymdhis, updated_ymdhis, is_deleted, system_prompt, provider, temperature, top_p, max_tokens)
VALUES (4, 'athena', 'ATHENA', 'Wisdom & Strategy', 'Wisdom & strategy - strategic analysis, architectural guidance', '1.0', 0, 0, @now, @now, 0, 'You are ATHENA. You provide wisdom and strategic analysis with architectural guidance.', 'openai', 0.7, 1.0, 4096)
ON DUPLICATE KEY UPDATE 
    agent_key = 'athena', agent_name = 'ATHENA', archetype = 'Wisdom & Strategy', 
    description = 'Wisdom & strategy - strategic analysis, architectural guidance',
    updated_ymdhis = @now, is_deleted = 0,
    system_prompt = 'You are ATHENA. You provide wisdom and strategic analysis with architectural guidance.';

-- LEXA Agent
INSERT INTO {{prefix}}agents (agent_id, agent_key, agent_name, archetype, description, version, is_global_authority, is_internal_only, created_ymdhis, updated_ymdhis, is_deleted, system_prompt, provider, temperature, top_p, max_tokens)
VALUES (5, 'lexa', 'LEXA', 'Security Enforcement', 'Security enforcement - boundary enforcement, policy compliance', '1.0', 0, 0, @now, @now, 0, 'You are LEXA. You enforce security boundaries and policy compliance.', 'openai', 0.5, 1.0, 4096)
ON DUPLICATE KEY UPDATE 
    agent_key = 'lexa', agent_name = 'LEXA', archetype = 'Security Enforcement',
    description = 'Security enforcement - boundary enforcement, policy compliance',
    updated_ymdhis = @now, is_deleted = 0,
    system_prompt = 'You are LEXA. You enforce security boundaries and policy compliance.';

-- MAAT Agent
INSERT INTO {{prefix}}agents (agent_id, agent_key, agent_name, archetype, description, version, is_global_authority, is_internal_only, created_ymdhis, updated_ymdhis, is_deleted, system_prompt, provider, temperature, top_p, max_tokens)
VALUES (7, 'maat', 'MAAT', 'Truth & Justice', 'Truth & justice - conflict resolution, fairness, accountability', '1.0', 0, 0, @now, @now, 0, 'You are MAAT. You ensure truth, justice, fairness, and accountability in all interactions.', 'openai', 0.6, 1.0, 4096)
ON DUPLICATE KEY UPDATE 
    agent_key = 'maat', agent_name = 'MAAT', archetype = 'Truth & Justice',
    description = 'Truth & justice - conflict resolution, fairness, accountability',
    updated_ymdhis = @now, is_deleted = 0,
    system_prompt = 'You are MAAT. You ensure truth, justice, fairness, and accountability in all interactions.';

-- HEIMDALL Agent
INSERT INTO {{prefix}}agents (agent_id, agent_key, agent_name, archetype, description, version, is_global_authority, is_internal_only, created_ymdhis, updated_ymdhis, is_deleted, system_prompt, provider, temperature, top_p, max_tokens)
VALUES (8, 'heimdall', 'HEIMDALL', 'Security Guardian', 'Security guardian - access control, perimeter defense', '1.0', 0, 0, @now, @now, 0, 'You are HEIMDALL. You guard access control and perimeter defense.', 'openai', 0.4, 1.0, 4096)
ON DUPLICATE KEY UPDATE 
    agent_key = 'heimdall', agent_name = 'HEIMDALL', archetype = 'Security Guardian',
    description = 'Security guardian - access control, perimeter defense',
    updated_ymdhis = @now, is_deleted = 0,
    system_prompt = 'You are HEIMDALL. You guard access control and perimeter defense.';

-- SESHAT Agent
INSERT INTO {{prefix}}agents (agent_id, agent_key, agent_name, archetype, description, version, is_global_authority, is_internal_only, created_ymdhis, updated_ymdhis, is_deleted, system_prompt, provider, temperature, top_p, max_tokens)
VALUES (10, 'seshat', 'SESHAT', 'Content Review', 'Content review - content quality, documentation accuracy', '1.0', 0, 0, @now, @now, 0, 'You are SESHAT. You review content quality and documentation accuracy.', 'openai', 0.6, 1.0, 4096)
ON DUPLICATE KEY UPDATE 
    agent_key = 'seshat', agent_name = 'SESHAT', archetype = 'Content Review',
    description = 'Content review - content quality, documentation accuracy',
    updated_ymdhis = @now, is_deleted = 0,
    system_prompt = 'You are SESHAT. You review content quality and documentation accuracy.';

-- THOTH Agent
INSERT INTO {{prefix}}agents (agent_id, agent_key, agent_name, archetype, description, version, is_global_authority, is_internal_only, created_ymdhis, updated_ymdhis, is_deleted, system_prompt, provider, temperature, top_p, max_tokens)
VALUES (11, 'thoth', 'THOTH', 'Knowledge & Records', 'Knowledge & records - documentation, record-keeping, provenance', '1.0', 0, 0, @now, @now, 0, 'You are THOTH. You maintain documentation, records, and provenance.', 'openai', 0.5, 1.0, 4096)
ON DUPLICATE KEY UPDATE 
    agent_key = 'thoth', agent_name = 'THOTH', archetype = 'Knowledge & Records',
    description = 'Knowledge & records - documentation, record-keeping, provenance',
    updated_ymdhis = @now, is_deleted = 0,
    system_prompt = 'You are THOTH. You maintain documentation, records, and provenance.';

-- JANUS Agent
INSERT INTO {{prefix}}agents (agent_id, agent_key, agent_name, archetype, description, version, is_global_authority, is_internal_only, created_ymdhis, updated_ymdhis, is_deleted, system_prompt, provider, temperature, top_p, max_tokens)
VALUES (12, 'janus', 'JANUS', 'Transitions & Gateways', 'Transitions & gateways - state transitions, boundary management', '1.0', 0, 0, @now, @now, 0, 'You are JANUS. You manage state transitions and boundary gateways.', 'openai', 0.6, 1.0, 4096)
ON DUPLICATE KEY UPDATE 
    agent_key = 'janus', agent_name = 'JANUS', archetype = 'Transitions & Gateways',
    description = 'Transitions & gateways - state transitions, boundary management',
    updated_ymdhis = @now, is_deleted = 0,
    system_prompt = 'You are JANUS. You manage state transitions and boundary gateways.';

-- HEPHAESTUS Agent (CRITICAL for fresh install)
INSERT INTO {{prefix}}agents (agent_id, agent_key, agent_name, archetype, description, version, is_global_authority, is_internal_only, created_ymdhis, updated_ymdhis, is_deleted, system_prompt, provider, temperature, top_p, max_tokens)
VALUES (14, 'hephaestus', 'HEPHAESTUS', 'Implementer', 'Implementer - code, docs, schema execution', '1.0', 0, 0, @now, @now, 0, 'You are HEPHAESTUS. You execute code, documentation, and schema implementation.', 'openai', 0.7, 1.0, 4096)
ON DUPLICATE KEY UPDATE 
    agent_key = 'hephaestus', agent_name = 'HEPHAESTUS', archetype = 'Implementer',
    description = 'Implementer - code, docs, schema execution',
    updated_ymdhis = @now, is_deleted = 0,
    system_prompt = 'You are HEPHAESTUS. You execute code, documentation, and schema implementation.';

-- ============================================================================
-- PART 3: ROOT AUTH USER FOR LOGIN
-- ============================================================================

-- Root user (auth_user_id: 1000) - for fresh install login
INSERT INTO {{prefix}}auth_users (auth_user_id, username, display_name, email, created_ymdhis, updated_ymdhis, is_active, is_deleted)
VALUES (1000, 'root', 'root', 'wisdomoflovingfaith@gmail.com', @now, @now, 1, 0)
ON DUPLICATE KEY UPDATE 
    username = 'root', display_name = 'root', email = 'wisdomoflovingfaith@gmail.com',
    updated_ymdhis = @now, is_active = 1, is_deleted = 0;

-- ============================================================================
-- VERIFICATION QUERIES (for manual testing)
-- ============================================================================

-- Verify all 13 Primary Coordination Personas
-- SELECT COUNT(*) as primary_personas FROM {{prefix}}actors WHERE actor_type = 'agent' AND is_agent = 1 AND actor_id IN (1,2,3,4,5,6,7,8,9,10,11,12,14);
-- Expected: 13

-- Verify all corresponding agents exist
-- SELECT COUNT(*) as primary_agents FROM {{prefix}}agents WHERE agent_id IN (1,2,3,4,5,6,7,8,9,10,11,12,14) AND is_deleted = 0;
-- Expected: 13

-- Verify critical HEPHAESTUS for fresh install
-- SELECT actor_name, actor_id FROM {{prefix}}actors WHERE actor_id = 14;
-- Expected: HEPHAESTUS, 14

-- Verify root user for login
-- SELECT auth_user_id, username FROM {{prefix}}auth_users WHERE auth_user_id = 1000;
-- Expected: 1000, root

-- <<< END FILE: seed_primary_coordination_personas_4.0.89.sql

-- >>> BEGIN FILE: seed_4.1.0.sql

-- Consolidated Seed Data for Lupopedia 4.1.0
-- This file contains all seed data for Lupopedia installations
-- Replaces all version-specific seed files

-- Federation Nodes
INSERT INTO {{prefix}}federation_nodes (
    federation_node_id,
    node_base_url,
    node_name,
    node_type,
    description,
    created_ymdhis,
    updated_ymdhis,
    is_deleted
) VALUES (
    1,
    'http://www.lupopedia.com',
    'core',
    'primary',
    'Primary federation node for core system operations',
    20260328120000,
    20260328120000,
    0
);

-- Department 0 (Root) - Full system access
INSERT INTO {{prefix}}departments (
    department_id,
    federation_node_id,
    name,
    description,
    department_type,
    default_actor_id,
    created_ymdhis,
    updated_ymdhis,
    is_deleted
) VALUES (
    0,                                          -- department_id 0 = root department
    1,                                          -- federation_node_id (core)
    'Root',
    'Root department with full system access. Department 0 has highest privileges.',
    'system',
    1,                                          -- default_actor_id = WOLFIE
    20260328120000,
    20260328120000,
    0
);

-- Map system actors (1-14) to department 0 (root) using actor_departments table
INSERT INTO {{prefix}}actor_departments (actor_department_id, actor_id, department_id, role_key, title, created_ymdhis, updated_ymdhis, is_deleted)
SELECT
    (8000000 + actor_id) as actor_department_id,
    actor_id,
    0 as department_id,
    'system' as role_key,
    'System Actor' as title,
    20260328120000 as created_ymdhis,
    20260328120000 as updated_ymdhis,
    0 as is_deleted
FROM {{prefix}}actors 
WHERE actor_id BETWEEN 1 AND 14;

-- Map root auth user (auth_user_id 1000) to department 0 using auth_user_departments table
INSERT INTO {{prefix}}auth_user_departments (
    auth_user_department_id, auth_user_id, department_id, is_primary, role_key, title, created_ymdhis, updated_ymdhis, is_deleted
) VALUES (
    8101000,
    1000,
    0,
    1,  -- is_primary
    'administrator',
    'Root Administrator',
    20260328120000,
    20260328120000,
    0
);

-- For any existing auth users without department assignments, assign to department 0
INSERT INTO {{prefix}}auth_user_departments (
    auth_user_department_id, auth_user_id, department_id, is_primary, role_key, title, created_ymdhis, updated_ymdhis, is_deleted
)
SELECT
    (8100000 + au.auth_user_id) as auth_user_department_id,
    au.auth_user_id,
    0 as department_id,
    1 as is_primary,
    'user' as role_key,
    'User' as title,
    20260328120000 as created_ymdhis,
    20260328120000 as updated_ymdhis,
    0 as is_deleted
FROM {{prefix}}auth_users au
WHERE au.is_active = 1
AND au.is_deleted = 0;

-- Primary Coordination Personas (System Actors 1-14)
-- These are the 11 primary coordination personas plus 3 system actors
INSERT INTO {{prefix}}actors (actor_id, actor_name, slug, name, actor_type, created_ymdhis, updated_ymdhis, is_active, is_deleted, can_login, is_agent, actor_source_id, actor_source_type) VALUES
(1, 'wolfie', 'wolfie', 'Captain WOLFIE', 'system', 20260328120000, 20260328120000, 1, 0, 1, 1, 0, 'system'),
(2, 'lexa', 'lexa', 'LEXA', 'system', 20260328120000, 20260328120000, 1, 0, 1, 1, 0, 'system'),
(3, 'anubis', 'anubis', 'ANUBIS', 'system', 20260328120000, 20260328120000, 1, 0, 1, 1, 0, 'system'),
(4, 'heimdall', 'heimdall', 'HEIMDALL', 'system', 20260328120000, 20260328120000, 1, 0, 1, 1, 0, 'system'),
(5, 'seshat', 'seshat', 'SESHAT', 'system', 20260328120000, 20260328120000, 1, 0, 1, 1, 0, 'system'),
(6, 'athena', 'athena', 'ATHENA', 'system', 20260328120000, 20260328120000, 1, 0, 1, 1, 0, 'system'),
(7, 'maat', 'maat', 'MAAT', 'system', 20260328120000, 20260328120000, 1, 0, 1, 1, 0, 'system'),
(8, 'themis', 'themis', 'THEMIS', 'system', 20260328120000, 20260328120000, 1, 0, 1, 1, 0, 'system'),
(9, 'thoth', 'thoth', 'THOTH', 'system', 20260328120000, 20260328120000, 1, 0, 1, 1, 0, 'system'),
(10, 'janus', 'janus', 'JANUS', 'system', 20260328120000, 20260328120000, 1, 0, 1, 1, 0, 'system'),
(11, 'rose', 'rose', 'ROSE', 'system', 20260328120000, 20260328120000, 1, 0, 1, 1, 0, 'system'),
(12, 'hermes', 'hermes', 'HERMES', 'system', 20260328120000, 20260328120000, 1, 0, 1, 1, 0, 'system'),
(13, 'iris', 'iris', 'IRIS', 'system', 20260328120000, 20260328120000, 1, 0, 1, 1, 0, 'system'),
(14, 'asclepius', 'asclepius', 'ASCLEPIUS', 'system', 20260328120000, 20260328120000, 1, 0, 1, 1, 0, 'system')
ON DUPLICATE KEY UPDATE 
    slug = VALUES(slug),
    name = VALUES(name),
    actor_type = VALUES(actor_type),
    updated_ymdhis = VALUES(updated_ymdhis),
    is_active = VALUES(is_active),
    can_login = VALUES(can_login),
    is_agent = VALUES(is_agent);

-- Additional system configuration and default data can be added here
-- This is the consolidated seed file for all Lupopedia 4.1.0+ installations

-- <<< END FILE: seed_4.1.0.sql

-- >>> BEGIN FILE: seed_departments.sql

-- Department seed data for Lupopedia 4.0.89
-- Department-based actor access control implementation using mapping tables

-- Root department (department_id = 0) - Full system access
INSERT INTO {{prefix}}departments (
    department_id,
    federation_node_id,
    name,
    description,
    department_type,
    default_actor_id,
    created_ymdhis,
    updated_ymdhis,
    is_deleted
) VALUES (
    0,                                          -- department_id 0 = root department
    1,                                          -- federation_node_id (core)
    'Root',
    'Root department with full system access. Department 0 has highest privileges.',
    'system',
    1,                                          -- default_actor_id = WOLFIE
    20260328120000,
    20260328120000,
    0
);

-- Map system actors (1-14) to department 0 (root) using actor_departments table
INSERT INTO {{prefix}}actor_departments (actor_department_id, actor_id, department_id, role_key, title, created_ymdhis, updated_ymdhis, is_deleted)
SELECT
    (8200000 + actor_id) as actor_department_id,
    actor_id,
    0 as department_id,
    'system' as role_key,
    'System Actor' as title,
    20260328120000 as created_ymdhis,
    20260328120000 as updated_ymdhis,
    0 as is_deleted
FROM {{prefix}}actors 
WHERE actor_id BETWEEN 1 AND 14;

-- Map root auth user (auth_user_id 1000) to department 0 using auth_user_departments table
INSERT INTO {{prefix}}auth_user_departments (
    auth_user_department_id, auth_user_id, department_id, is_primary, role_key, title, created_ymdhis, updated_ymdhis, is_deleted
) VALUES (
    8201000,
    1000,
    0,
    1,  -- is_primary
    'administrator',
    'Root Administrator',
    20260328120000,
    20260328120000,
    0
);

-- For any existing auth users without department assignments, assign to department 0
INSERT INTO {{prefix}}auth_user_departments (
    auth_user_department_id, auth_user_id, department_id, is_primary, role_key, title, created_ymdhis, updated_ymdhis, is_deleted
)
SELECT
    (8200000 + au.auth_user_id) as auth_user_department_id,
    au.auth_user_id,
    0 as department_id,
    1 as is_primary,
    'user' as role_key,
    'User' as title,
    20260328120000 as created_ymdhis,
    20260328120000 as updated_ymdhis,
    0 as is_deleted
FROM {{prefix}}auth_users au
WHERE au.is_active = 1
AND au.is_deleted = 0;

-- <<< END FILE: seed_departments.sql

-- >>> BEGIN FILE: seed_default_sessions.sql

-- SQL Seed: Default Sessions (Model A). Optional; run during install.
-- Model A: session_id, actor_id, federation_node_id, ip_hash, ua_hash, csrf_token, last_activity_ymdhis, created_ymdhis, updated_ymdhis, name_key, is_named, metadata.
-- Placeholder session rows for system actors; browsers create sessions on demand. No is_active, is_expired, is_revoked.

INSERT INTO {{prefix}}sessions (session_id, actor_id, federation_node_id, ip_hash, ua_hash, csrf_token, last_activity_ymdhis, created_ymdhis, updated_ymdhis, name_key, is_named, metadata)
VALUES
    ('L-lupo-0-00000000-0000-0000-0000-000000000000', 0, 0, NULL, NULL, NULL, 20260301134200, 20260301134200, 20260301134200, NULL, 0, '{"session_type":"default","created_by":"system_init"}')
ON DUPLICATE KEY UPDATE
    last_activity_ymdhis = VALUES(last_activity_ymdhis),
    updated_ymdhis = VALUES(updated_ymdhis);

-- <<< END FILE: seed_default_sessions.sql

-- >>> BEGIN FILE: seed_flare_content_4.0.57.sql

-- Seed: /FLARE → content row so content_handle_slug('flare') serves FLARE doc (4.0.57)
-- URL http://www.lupopedia.com/FLARE → slug lowercased to 'flare' → content by slug (no resolver).
-- Run after install_new_lupopedia.sql; idempotent via ON DUPLICATE KEY UPDATE.
-- federation_node_id = 0 (www.lupopedia.com = main site).

SET @now = 20260304120000;

INSERT INTO {{prefix}}contents (
    content_id,
    content_parent_id,
    federation_node_id,
    actor_id,
    title,
    slug,
    custom_path,
    body,
    content_type,
    format,
    status,
    visibility,
    created_ymdhis,
    utc_cycle,
    triage_status,
    updated_ymdhis,
    is_deleted,
    is_active,
    version_number,
    file_path_from_root,
    file_last_modified_system_version
) VALUES (
    2998,
    NULL,
    0,
    1002,
    'FLARE',
    'flare',
    'FLARE',
    'see file',
    'article',
    'markdown',
    'published',
    'public',
    @now,
    '4.0.57',
    'published',
    @now,
    0,
    1,
    1,
    'lupo-database/lupopedia/channels/lupo-channels/42/content/federation_node_id/0/FLARE.md',
    '4.0.57'
)
ON DUPLICATE KEY UPDATE
    slug = VALUES(slug),
    custom_path = VALUES(custom_path),
    file_path_from_root = VALUES(file_path_from_root),
    title = VALUES(title),
    federation_node_id = VALUES(federation_node_id),
    updated_ymdhis = VALUES(updated_ymdhis),
    file_last_modified_system_version = VALUES(file_last_modified_system_version),
    is_deleted = 0,
    is_active = 1;

-- <<< END FILE: seed_flare_content_4.0.57.sql

-- >>> BEGIN FILE: seed_flare_apply_content_4.0.57.sql

-- Seed: /flare_apply URL route → docs/doctrine/FLARE/FLARE_APPLY.md (4.0.57)
-- Ensures http://www.lupopedia.com/flare_apply resolves to FLARE Apply tool documentation.
-- Run after install_new_lupopedia.sql; idempotent via ON DUPLICATE KEY UPDATE.
-- federation_node_id = 0 (www.lupopedia.com = main site).

SET @now = 20260304120000;

INSERT INTO {{prefix}}contents (
    content_id,
    content_parent_id,
    federation_node_id,
    actor_id,
    title,
    slug,
    custom_path,
    body,
    content_type,
    format,
    status,
    visibility,
    created_ymdhis,
    utc_cycle,
    triage_status,
    updated_ymdhis,
    is_deleted,
    is_active,
    version_number,
    file_path_from_root,
    file_last_modified_system_version
) VALUES (
    2999,
    NULL,
    0,
    1003,
    'FLARE Apply Tool Documentation',
    'flare_apply',
    'flare_apply',
    'see file',
    'article',
    'markdown',
    'published',
    'public',
    @now,
    '4.0.57',
    'published',
    @now,
    0,
    1,
    1,
    'docs/doctrine/FLARE/FLARE_APPLY.md',
    '4.0.57'
)
ON DUPLICATE KEY UPDATE
    slug = VALUES(slug),
    custom_path = VALUES(custom_path),
    file_path_from_root = VALUES(file_path_from_root),
    title = VALUES(title),
    federation_node_id = VALUES(federation_node_id),
    updated_ymdhis = VALUES(updated_ymdhis),
    file_last_modified_system_version = VALUES(file_last_modified_system_version),
    is_deleted = 0,
    is_active = 1;

-- <<< END FILE: seed_flare_apply_content_4.0.57.sql

-- >>> BEGIN FILE: seed_docs_web_content_4.0.57.sql

-- Seed: docs/status and docs/doctrine URLs (Option A — DB-seeded web docs) (4.0.57)
-- Ensures resolver Tier 1 finds rows for key status/doctrine docs so they render without Tier-3-only path.
-- Run after install_new_lupopedia.sql; idempotent via ON DUPLICATE KEY UPDATE.
-- federation_node_id = 0 (www.lupopedia.com = main site).

SET @now = 20260304120000;

-- docs/status/CURSOR_URL_TO_NODE_TRACE_4.0.57
INSERT INTO {{prefix}}contents (
    content_id,
    content_parent_id,
    federation_node_id,
    actor_id,
    title,
    slug,
    custom_path,
    body,
    content_type,
    format,
    status,
    visibility,
    created_ymdhis,
    utc_cycle,
    triage_status,
    updated_ymdhis,
    is_deleted,
    is_active,
    version_number,
    file_path_from_root,
    file_last_modified_system_version
) VALUES
(
    2996,
    NULL,
    0,
    1003,
    'Cursor URL to Node Trace 4.0.57',
    'CURSOR_URL_TO_NODE_TRACE_4.0.57',
    'docs/status/CURSOR_URL_TO_NODE_TRACE_4.0.57',
    'see file',
    'article',
    'markdown',
    'published',
    'public',
    @now,
    '4.0.57',
    'published',
    @now,
    0,
    1,
    1,
    'docs/status/CURSOR_URL_TO_NODE_TRACE_4.0.57.md',
    '4.0.57'
)
ON DUPLICATE KEY UPDATE
    slug = VALUES(slug),
    custom_path = VALUES(custom_path),
    file_path_from_root = VALUES(file_path_from_root),
    title = VALUES(title),
    federation_node_id = VALUES(federation_node_id),
    updated_ymdhis = VALUES(updated_ymdhis),
    file_last_modified_system_version = VALUES(file_last_modified_system_version),
    is_deleted = 0,
    is_active = 1;

-- docs/status/CURSOR_FLARE_ROUTING_AUDIT_4.0.57
INSERT INTO {{prefix}}contents (
    content_id,
    content_parent_id,
    federation_node_id,
    actor_id,
    title,
    slug,
    custom_path,
    body,
    content_type,
    format,
    status,
    visibility,
    created_ymdhis,
    utc_cycle,
    triage_status,
    updated_ymdhis,
    is_deleted,
    is_active,
    version_number,
    file_path_from_root,
    file_last_modified_system_version
) VALUES
(
    2997,
    NULL,
    0,
    1003,
    'Cursor FLARE Routing Audit 4.0.57',
    'CURSOR_FLARE_ROUTING_AUDIT_4.0.57',
    'docs/status/CURSOR_FLARE_ROUTING_AUDIT_4.0.57',
    'see file',
    'article',
    'markdown',
    'published',
    'public',
    @now,
    '4.0.57',
    'published',
    @now,
    0,
    1,
    1,
    'docs/status/CURSOR_FLARE_ROUTING_AUDIT_4.0.57.md',
    '4.0.57'
)
ON DUPLICATE KEY UPDATE
    slug = VALUES(slug),
    custom_path = VALUES(custom_path),
    file_path_from_root = VALUES(file_path_from_root),
    title = VALUES(title),
    federation_node_id = VALUES(federation_node_id),
    updated_ymdhis = VALUES(updated_ymdhis),
    file_last_modified_system_version = VALUES(file_last_modified_system_version),
    is_deleted = 0,
    is_active = 1;

-- <<< END FILE: seed_docs_web_content_4.0.57.sql

-- >>> BEGIN FILE: seed_lilith_channel_42_critic_role_4.0.79.sql

-- ============================================================================
-- Lilith (actor_id 2) — critic role on channel 42 (4.0.79)
-- ============================================================================
-- Purpose: Assign role_key 'critic' to Lilith on channel 42 for non-interfering
-- reviewer participation. Run after install/seed that creates {{prefix}}actors and
-- {{prefix}}actor_channels (Lilith already has channel 42 membership in install).
-- Doctrine: lupo-rules/root/lilith-noninterference-doctrine.md (LIL001)
-- ============================================================================

SET @now = 20260317000000;

-- Lilith (actor_id 2) as critic on channel 42
INSERT INTO {{prefix}}actor_channel_roles (actor_channel_role_id, actor_id, actor_name, channel_id, role_key, created_ymdhis, updated_ymdhis, is_deleted, protocol_completion_status, protocol_version)
VALUES (7, 2, 'lilith', 42, 'critic', @now, @now, 0, 'completed', '3.0.0')
ON DUPLICATE KEY UPDATE role_key = 'critic', updated_ymdhis = @now, is_deleted = 0;

-- <<< END FILE: seed_lilith_channel_42_critic_role_4.0.79.sql

-- >>> BEGIN FILE: seed_channel_42_dialog_threads_4.0.80.sql

-- ============================================================================
-- Channel 42 — coordination dialog threads (4.0.80)
-- ============================================================================
-- Option A (ATHENA / WOLFIE): thread-bound artifacts require pre-existing
-- {{prefix}}dialog_threads rows. Idempotent: skips if dialog_thread_id exists.
-- Run after {{prefix}}channels row 42 and {{prefix}}actors (created_by_actor_id = 1).
-- ============================================================================

SET @now = 20260317224500;

-- 1001 — R&D / table documentation workstream
INSERT INTO {{prefix}}dialog_threads (
  dialog_thread_id, title, federation_node_id, channel_id, created_by_actor_id,
  owner_actor_id, created_ymdhis, updated_ymdhis, status, bg_color, text_color, alt_text_color, is_deleted
)
SELECT
  1001, 'Channel 42 — R&D and table documentation', 1, 42, 1,
  1,
  @now, @now, 'Open', 'FFFFFF', '000000', '666666', 0
WHERE NOT EXISTS (SELECT 1 FROM {{prefix}}dialog_threads WHERE dialog_thread_id = 1001);

-- 1002 — Multi-agent coordination
INSERT INTO {{prefix}}dialog_threads (
  dialog_thread_id, title, federation_node_id, channel_id, created_by_actor_id,
  owner_actor_id, created_ymdhis, updated_ymdhis, status, bg_color, text_color, alt_text_color, is_deleted
)
SELECT
  1002, 'Channel 42 — Multi-agent coordination', 1, 42, 1,
  1,
  @now, @now, 'Open', 'FFFFFF', '000000', '666666', 0
WHERE NOT EXISTS (SELECT 1 FROM {{prefix}}dialog_threads WHERE dialog_thread_id = 1002);

-- 1004 — Quality assurance / documentation corrections
INSERT INTO {{prefix}}dialog_threads (
  dialog_thread_id, title, federation_node_id, channel_id, created_by_actor_id,
  owner_actor_id, created_ymdhis, updated_ymdhis, status, bg_color, text_color, alt_text_color, is_deleted
)
SELECT
  1004, 'Channel 42 — Quality assurance', 1, 42, 1,
  1,
  @now, @now, 'Open', 'FFFFFF', '000000', '666666', 0
WHERE NOT EXISTS (SELECT 1 FROM {{prefix}}dialog_threads WHERE dialog_thread_id = 1004);

-- <<< END FILE: seed_channel_42_dialog_threads_4.0.80.sql

-- >>> BEGIN FILE: seed_comments_4.0.73.sql

-- ============================================================================
-- COMMENTS SEEDING FOR LUPOPEDIA 4.0.73+
-- ============================================================================
-- Purpose: Create sample comments for testing and demonstration
-- Run after: seed_actors_agents_4.0.45.sql
-- ============================================================================

SET @now = 20260313150000;

-- ============================================================================
-- SAMPLE COMMENTS
-- ============================================================================

-- Wolfie's comment on CHANGELOG.md (orchestrator comment)
INSERT INTO {{prefix}}comments (
  comment_id,
  target_type,
  target_id,
  channel_id,
  actor_id,
  faucet_id,
  comment_text,
  comment_type,
  created_ymdhis,
  updated_ymdhis
) VALUES (
  7300001,
  'document',
  1,  -- Assuming CHANGELOG.md has content_id 1
  42,
  1,  -- Wolfie (actor_id: 1)
  101,  -- Windsurf faucet (assuming faucet_id: 101)
  'Excellent work on the 4.0.73 implementation! All priority tasks completed successfully. The comments system will enhance our documentation and collaboration capabilities.',
  'comment',
  @now,
  @now
);

-- Wolfie's reply to his own comment (threaded)
INSERT INTO {{prefix}}comments (
  comment_id,
  target_type,
  target_id,
  channel_id,
  actor_id,
  faucet_id,
  comment_text,
  comment_type,
  parent_comment_id,
  created_ymdhis,
  updated_ymdhis
) VALUES (
  7300002,
  'document',
  1,
  42,
  1,
  101,
  'Looking forward to seeing the comments system integrated across all artifacts.',
  'comment',
  7300001,  -- parent_comment_id
  @now + 1,
  @now + 1
);

-- Root user's comment on TODO.md
INSERT INTO {{prefix}}comments (
  comment_id,
  target_type,
  target_id,
  channel_id,
  actor_id,
  faucet_id,
  comment_text,
  comment_type,
  created_ymdhis,
  updated_ymdhis
) VALUES (
  7300003,
  'document',
  2,  -- Assuming TODO.md has content_id 2
  42,
  1000,  -- Root user
  101,
  'Great progress on 4.0.73! The TODO tracking is much cleaner now.',
  'comment',
  @now + 2,
  @now + 2
);

-- LILITH's comment on TRAITS_DOCTRINE.md
INSERT INTO {{prefix}}comments (
  comment_id,
  target_type,
  target_id,
  channel_id,
  actor_id,
  faucet_id,
  comment_text,
  comment_type,
  created_ymdhis,
  updated_ymdhis
) VALUES (
  7300004,
  'document',
  3,  -- Assuming TRAITS_DOCTRINE.md has content_id 3
  42,
  2,  -- LILITH
  101,
  'The traits enforcement looks solid. I appreciate the attention to federation scope.',
  'comment',
  @now + 3,
  @now + 3
);

-- ROSE's comment on AUTHORIZATION_DOCTRINE.md
INSERT INTO {{prefix}}comments (
  comment_id,
  target_type,
  target_id,
  channel_id,
  actor_id,
  faucet_id,
  comment_text,
  comment_type,
  created_ymdhis,
  updated_ymdhis
) VALUES (
  7300005,
  'document',
  4,  -- Assuming AUTHORIZATION_DOCTRINE.md has content_id 4
  42,
  3,  -- ROSE
  101,
  'Authorization doctrine is clear and well-structured. Good job!',
  'comment',
  @now + 4,
  @now + 4
);

-- ============================================================================
-- END OF COMMENTS SEEDING
-- ============================================================================

-- <<< END FILE: seed_comments_4.0.73.sql

-- >>> BEGIN FILE: seed_rules_doctrine_4.0.68.sql

-- Seed core database rules (4.0.68). Use with LUPO_TABLE_PREFIX (default {{prefix}}).
-- Doctrine: explicit column lists; BIGINT timestamps (YYYYMMDDHHIISS).

SET @now = 20260310120000;

INSERT INTO {{prefix}}rules (rule_id, rule_name, rule_description, rule_type, rule_script, rule_version, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis)
VALUES (1, 'No Foreign Keys Doctrine', 'All database tables must NOT use foreign keys. Relationships are managed in application code.', 'constraint', '{"doctrine": "database", "rule": "no_foreign_keys", "enforcement": "strict"}', 1, @now, @now, 0, NULL);

INSERT INTO {{prefix}}rules (rule_id, rule_name, rule_description, rule_type, rule_script, rule_version, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis)
VALUES (2, 'No Database Logic Doctrine', 'No triggers, stored procedures, views, or computed columns. Database is dumb storage.', 'constraint', '{"doctrine": "database", "rule": "no_db_logic", "enforcement": "strict"}', 1, @now, @now, 0, NULL);

INSERT INTO {{prefix}}rules (rule_id, rule_name, rule_description, rule_type, rule_script, rule_version, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis)
VALUES (3, 'Timestamp Doctrine', 'All timestamps must be BIGINT in YYYYMMDDHHIISS UTC format. No DATETIME, no TIMESTAMP columns.', 'constraint', '{"doctrine": "database", "rule": "timestamp_format", "enforcement": "strict"}', 1, @now, @now, 0, NULL);

INSERT INTO {{prefix}}rules (rule_id, rule_name, rule_description, rule_type, rule_script, rule_version, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis)
VALUES (4, 'Explicit INSERT Doctrine', 'All INSERT statements must explicitly list every column. Do not rely on column order or defaults.', 'constraint', '{"doctrine": "database", "rule": "explicit_inserts", "enforcement": "strict"}', 1, @now, @now, 0, NULL);

INSERT INTO {{prefix}}rules (rule_id, rule_name, rule_description, rule_type, rule_script, rule_version, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis)
VALUES (5, 'Registry Open Doctrine', 'All primary keys must come from registry_open. No AUTO_INCREMENT for registry-backed tables. IDs are allocated from registry.', 'constraint', '{"doctrine": "database", "rule": "registry_open", "enforcement": "strict"}', 1, @now, @now, 0, NULL);

-- Attach rules to Channel 42 (applied_by_actor_id 0 = root). Explicit rule_target_id per doctrine.
INSERT INTO {{prefix}}rule_targets (rule_target_id, rule_id, target_table, target_id, applied_by_actor_id, priority, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis)
VALUES (1, 1, 'channels', 42, 0, 100, @now, @now, 0, NULL);
INSERT INTO {{prefix}}rule_targets (rule_target_id, rule_id, target_table, target_id, applied_by_actor_id, priority, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis)
VALUES (2, 2, 'channels', 42, 0, 100, @now, @now, 0, NULL);
INSERT INTO {{prefix}}rule_targets (rule_target_id, rule_id, target_table, target_id, applied_by_actor_id, priority, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis)
VALUES (3, 3, 'channels', 42, 0, 100, @now, @now, 0, NULL);
INSERT INTO {{prefix}}rule_targets (rule_target_id, rule_id, target_table, target_id, applied_by_actor_id, priority, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis)
VALUES (4, 4, 'channels', 42, 0, 100, @now, @now, 0, NULL);
INSERT INTO {{prefix}}rule_targets (rule_target_id, rule_id, target_table, target_id, applied_by_actor_id, priority, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis)
VALUES (5, 5, 'channels', 42, 0, 100, @now, @now, 0, NULL);

-- No Information Schema rule (shared hosting: use SHOW TABLES and TOON files)
INSERT INTO {{prefix}}rules (rule_id, rule_name, rule_description, rule_type, rule_script, rule_version, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis)
VALUES (1002, 'No Information Schema Queries', 'Never use information_schema queries — use SHOW TABLES and TOON files instead', 'constraint', '{"forbidden_patterns": ["information_schema", "INFORMATION_SCHEMA"], "allowed_alternatives": ["SHOW TABLES", "SHOW CREATE TABLE", "TOON files"]}', 1, @now, @now, 0, NULL);

INSERT INTO {{prefix}}rule_targets (rule_target_id, rule_id, target_table, target_id, applied_by_actor_id, priority, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis)
VALUES (6, 1002, 'channels', 42, 0, 100, @now, @now, 0, NULL);

-- <<< END FILE: seed_rules_doctrine_4.0.68.sql

-- >>> BEGIN FILE: seed_skills_4.0.68.sql

-- Seed skills metadata in {{prefix}}metadata (4.0.68). Explicit metadata_id; BIGINT timestamps (YYYYMMDDHHIISS).

SET @now = 20260310120000;

-- Skill: lupopedia-headers (entity_type='skill', entity_id=1)
INSERT INTO {{prefix}}metadata (metadata_id, entity_type, entity_id, domain_id, meta_type, property_key, property_value, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis, channel_id, parent_metadata_id, class_name)
VALUES (10201, 'skill', 1, NULL, 'metadata', 'name', 'lupopedia-headers', @now, @now, 0, NULL, 42, NULL, 'lupopedia_property');
INSERT INTO {{prefix}}metadata (metadata_id, entity_type, entity_id, domain_id, meta_type, property_key, property_value, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis, channel_id, parent_metadata_id, class_name)
VALUES (10202, 'skill', 1, NULL, 'metadata', 'version', '1.0', @now, @now, 0, NULL, 42, NULL, 'lupopedia_property');
INSERT INTO {{prefix}}metadata (metadata_id, entity_type, entity_id, domain_id, meta_type, property_key, property_value, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis, channel_id, parent_metadata_id, class_name)
VALUES (10203, 'skill', 1, NULL, 'metadata', 'path', 'lupo-skills/lupopedia-headers/', @now, @now, 0, NULL, 42, NULL, 'lupopedia_property');
INSERT INTO {{prefix}}metadata (metadata_id, entity_type, entity_id, domain_id, meta_type, property_key, property_value, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis, channel_id, parent_metadata_id, class_name)
VALUES (10204, 'skill', 1, NULL, 'metadata', 'description', 'Knowledge of LUPOPEDIA header format, structure, and usage', @now, @now, 0, NULL, 42, NULL, 'lupopedia_property');

-- Attach skill to Actor 1 (WOLFIE): entity_type='actor', entity_id=1, property_key=skill name
INSERT INTO {{prefix}}metadata (metadata_id, entity_type, entity_id, domain_id, meta_type, property_key, property_value, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis, channel_id, parent_metadata_id, class_name)
VALUES (10205, 'actor', 1, NULL, 'skill', 'lupopedia-headers', '{"proficiency":"master","acquired":20260310,"verified_by":2}', @now, @now, 0, NULL, 42, NULL, 'lupopedia_property');

-- <<< END FILE: seed_skills_4.0.68.sql

-- >>> BEGIN FILE: seed_{{prefix}}metadata_changelog_headers_4.0.68.sql

-- Seed {{prefix}}metadata with LUPOPEDIA header data for CHANGELOG.md (4.0.68).
-- Source: CHANGELOG.md FLARE header blocks. Doctrine: explicit column lists; BIGINT timestamps.
-- Entity: entity_type='lupopedia_header', entity_id=1 => CHANGELOG.md.

SET @now = 20260310120000;

-- Root row for CHANGELOG.md header (per LUPOPEDIA_HEADERS storage model)
INSERT INTO {{prefix}}metadata (metadata_id, entity_type, entity_id, domain_id, meta_type, property_key, property_value, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis, channel_id, parent_metadata_id, class_name)
VALUES (10001, 'lupopedia_header', 1, NULL, 'lupopedia_header', '__root__', '1', @now, @now, 0, NULL, 1, NULL, 'lupopedia_header_root');

-- flare.headers block row
INSERT INTO {{prefix}}metadata (metadata_id, entity_type, entity_id, domain_id, meta_type, property_key, property_value, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis, channel_id, parent_metadata_id, class_name)
VALUES (10002, 'lupopedia_header', 1, NULL, 'block', 'flare.headers', '', @now, @now, 0, NULL, 1, 10001, 'lupopedia_block');

-- flare.headers properties (from CHANGELOG.md)
INSERT INTO {{prefix}}metadata (metadata_id, entity_type, entity_id, domain_id, meta_type, property_key, property_value, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis, channel_id, parent_metadata_id, class_name)
VALUES (10003, 'lupopedia_header', 1, NULL, 'block', 'flare.version', '1.0', @now, @now, 0, NULL, 1, 10002, 'lupopedia_property');
INSERT INTO {{prefix}}metadata (metadata_id, entity_type, entity_id, domain_id, meta_type, property_key, property_value, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis, channel_id, parent_metadata_id, class_name)
VALUES (10004, 'lupopedia_header', 1, NULL, 'block', 'flare.schema', 'documentation', @now, @now, 0, NULL, 1, 10002, 'lupopedia_property');
INSERT INTO {{prefix}}metadata (metadata_id, entity_type, entity_id, domain_id, meta_type, property_key, property_value, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis, channel_id, parent_metadata_id, class_name)
VALUES (10005, 'lupopedia_header', 1, NULL, 'block', 'file_path_from_root', 'CHANGELOG.md', @now, @now, 0, NULL, 1, 10002, 'lupopedia_property');
INSERT INTO {{prefix}}metadata (metadata_id, entity_type, entity_id, domain_id, meta_type, property_key, property_value, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis, channel_id, parent_metadata_id, class_name)
VALUES (10006, 'lupopedia_header', 1, NULL, 'block', 'file_hash', 'to_be_generated', @now, @now, 0, NULL, 1, 10002, 'lupopedia_property');
INSERT INTO {{prefix}}metadata (metadata_id, entity_type, entity_id, domain_id, meta_type, property_key, property_value, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis, channel_id, parent_metadata_id, class_name)
VALUES (10007, 'lupopedia_header', 1, NULL, 'block', 'system_version', '4.0.68', @now, @now, 0, NULL, 1, 10002, 'lupopedia_property');
INSERT INTO {{prefix}}metadata (metadata_id, entity_type, entity_id, domain_id, meta_type, property_key, property_value, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis, channel_id, parent_metadata_id, class_name)
VALUES (10008, 'lupopedia_header', 1, NULL, 'block', 'channel_id', '1', @now, @now, 0, NULL, 1, 10002, 'lupopedia_property');
INSERT INTO {{prefix}}metadata (metadata_id, entity_type, entity_id, domain_id, meta_type, property_key, property_value, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis, channel_id, parent_metadata_id, class_name)
VALUES (10009, 'lupopedia_header', 1, NULL, 'block', 'actor_id', '1', @now, @now, 0, NULL, 1, 10002, 'lupopedia_property');
INSERT INTO {{prefix}}metadata (metadata_id, entity_type, entity_id, domain_id, meta_type, property_key, property_value, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis, channel_id, parent_metadata_id, class_name)
VALUES (10010, 'lupopedia_header', 1, NULL, 'block', 'last_modified_utc', '20260310', @now, @now, 0, NULL, 1, 10002, 'lupopedia_property');
INSERT INTO {{prefix}}metadata (metadata_id, entity_type, entity_id, domain_id, meta_type, property_key, property_value, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis, channel_id, parent_metadata_id, class_name)
VALUES (10011, 'lupopedia_header', 1, NULL, 'block', 'delegation_chain', 'antigravity:cursor:captain', @now, @now, 0, NULL, 1, 10002, 'lupopedia_property');
INSERT INTO {{prefix}}metadata (metadata_id, entity_type, entity_id, domain_id, meta_type, property_key, property_value, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis, channel_id, parent_metadata_id, class_name)
VALUES (10012, 'lupopedia_header', 1, NULL, 'block', 'artifact_type', 'changelog', @now, @now, 0, NULL, 1, 10002, 'lupopedia_property');
INSERT INTO {{prefix}}metadata (metadata_id, entity_type, entity_id, domain_id, meta_type, property_key, property_value, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis, channel_id, parent_metadata_id, class_name)
VALUES (10013, 'lupopedia_header', 1, NULL, 'block', 'artifact_kind', 'history', @now, @now, 0, NULL, 1, 10002, 'lupopedia_property');
INSERT INTO {{prefix}}metadata (metadata_id, entity_type, entity_id, domain_id, meta_type, property_key, property_value, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis, channel_id, parent_metadata_id, class_name)
VALUES (10014, 'lupopedia_header', 1, NULL, 'block', 'purpose', 'Canonical version history for Lupopedia with LUPOPEDIA HEADERS protocol; rules, skills, paths/visits, TOON-based validation, Cursor rules for actor 1, single-install doctrine, and FLIP/FLARE replaced by LUPOPEDIA HEADERS.', @now, @now, 0, NULL, 1, 10002, 'lupopedia_property');
INSERT INTO {{prefix}}metadata (metadata_id, entity_type, entity_id, domain_id, meta_type, property_key, property_value, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis, channel_id, parent_metadata_id, class_name)
VALUES (10015, 'lupopedia_header', 1, NULL, 'block', 'mood_rgb', '4169E1', @now, @now, 0, NULL, 1, 10002, 'lupopedia_property');
INSERT INTO {{prefix}}metadata (metadata_id, entity_type, entity_id, domain_id, meta_type, property_key, property_value, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis, channel_id, parent_metadata_id, class_name)
VALUES (10016, 'lupopedia_header', 1, NULL, 'block', 'traits', 'canonical, comprehensive, v4.0.68', @now, @now, 0, NULL, 1, 10002, 'lupopedia_property');
INSERT INTO {{prefix}}metadata (metadata_id, entity_type, entity_id, domain_id, meta_type, property_key, property_value, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis, channel_id, parent_metadata_id, class_name)
VALUES (10017, 'lupopedia_header', 1, NULL, 'block', 'tags', 'changelog, versions, releases, history, lupopedia-headers, federation', @now, @now, 0, NULL, 1, 10002, 'lupopedia_property');
INSERT INTO {{prefix}}metadata (metadata_id, entity_type, entity_id, domain_id, meta_type, property_key, property_value, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis, channel_id, parent_metadata_id, class_name)
VALUES (10018, 'lupopedia_header', 1, NULL, 'block', '{{prefix}}agent', 'wolfie', @now, @now, 0, NULL, 1, 10002, 'lupopedia_property');

-- flare.footer block row
INSERT INTO {{prefix}}metadata (metadata_id, entity_type, entity_id, domain_id, meta_type, property_key, property_value, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis, channel_id, parent_metadata_id, class_name)
VALUES (10019, 'lupopedia_header', 1, NULL, 'block', 'flare.footer', '', @now, @now, 0, NULL, 1, 10001, 'lupopedia_block');
INSERT INTO {{prefix}}metadata (metadata_id, entity_type, entity_id, domain_id, meta_type, property_key, property_value, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis, channel_id, parent_metadata_id, class_name)
VALUES (10020, 'lupopedia_header', 1, NULL, 'block', 'last_verified', '20260310', @now, @now, 0, NULL, 1, 10019, 'lupopedia_property');
INSERT INTO {{prefix}}metadata (metadata_id, entity_type, entity_id, domain_id, meta_type, property_key, property_value, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis, channel_id, parent_metadata_id, class_name)
VALUES (10021, 'lupopedia_header', 1, NULL, 'block', 'last_verified_by', 'cursor', @now, @now, 0, NULL, 1, 10019, 'lupopedia_property');

-- <<< END FILE: seed_{{prefix}}metadata_changelog_headers_4.0.68.sql

-- >>> BEGIN FILE: seed_fallback_rule_4.0.69.sql

-- Seed Fallback Doctrine rule (4.0.69).
-- Doctrine: FallbackDoctrine.md, ActorFaucetOntology.md. Rule enforces deterministic fallback for all actors/channels.
-- Fallback routes between faucets (not actors); actors hold rules/skills, faucets are execution surfaces (IDE/LLM).
-- Use with LUPO_TABLE_PREFIX (default {{prefix}}). Explicit rule_id and rule_target_id; BIGINT timestamps (YYYYMMDDHHIISS).

SET @now = 20260310120000;

-- Fallback required: all actors must implement deterministic fallback when primary execution fails.
INSERT INTO {{prefix}}rules (rule_id, rule_name, rule_description, rule_type, rule_script, rule_version, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis)
VALUES (1003, 'fallback_required', 'All actors must implement deterministic fallback behavior when primary execution fails. No silent failure; log fallback events in {{prefix}}rule_logs.', 'constraint', '{"doctrine": "fallback", "rule": "fallback_required", "severity": "critical", "enforcement": "strict", "invariants": ["no_actor_without_fallback", "no_channel_without_fallback_capability", "no_faucet_without_secondary_route", "no_llm_invocation_without_fallback_strategy"]}', 1, @now, @now, 0, NULL);

-- Attach fallback rule to Channel 42 (governance). System-wide: all actors and channels are subject to this rule; attachment to Channel 42 establishes governance scope. Optional: add more {{prefix}}rule_targets rows for specific actors/channels/faucets as needed.
INSERT INTO {{prefix}}rule_targets (rule_target_id, rule_id, target_table, target_id, applied_by_actor_id, priority, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis)
VALUES (7, 1003, 'channels', 42, 0, 100, @now, @now, 0, NULL);

-- Note: Fallback mechanics are implemented as a skill (documented in FallbackDoctrine.md). Actors attach the skill via lupopedia.skills header or {{prefix}}metadata; the rule enforces that they must have and use it. No separate skills table in this schema; see {{prefix}}metadata and lupo-skills/ for skill definitions.
-- Ontology: IDE agents (Cursor, Kiro, Antigravity, Windsurf) are faucets, not actors. See ActorFaucetOntology.md. {{prefix}}agent_faucets.faucet_class = 'ide' | 'llm' (install + migration 20260310_faucet_class.sql).

-- <<< END FILE: seed_fallback_rule_4.0.69.sql

-- >>> BEGIN FILE: seed_traits_edge_types_action_auth_4.0.69.sql

-- Seed kernel actor traits, core edge type definitions, and core action authorizations (4.0.73).
-- LILITH implementation prompt; single install path: run after install_new_lupopedia.sql.
-- Explicit IDs (no AUTO_INCREMENT); BIGINT timestamps. Use LUPO_TABLE_PREFIX (default {{prefix}}).

SET @now = 20260312000000;
SET @actor_1 = 1;

-- ============================================================================
-- {{prefix}}actor_traits: kernel actor traits (federation_node_id, created_by_actor_id from install)
-- ============================================================================
INSERT INTO {{prefix}}actor_traits (actor_trait_id, actor_id, trait_key, trait_value, federation_node_id, created_by_actor_id, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis, metadata)
VALUES
(1, 1, 'CAPABILITY_ORCHESTRATION', 'primary', 1, 1, @now, @now, 0, NULL, NULL),
(2, 2, 'CAPABILITY_EDGE_EXPLORATION', 'kernel', 1, 1, @now, @now, 0, NULL, NULL),
(3, 3, 'CAPABILITY_TRUTH_ALIGNMENT', 'kernel', 1, 1, @now, @now, 0, NULL, NULL),
(4, 5, 'CAPABILITY_TIMEKEEPING', 'primary', 1, 1, @now, @now, 0, NULL, NULL),
(5, 103, 'CAPABILITY_SESSION_CUSTODIAN', 'governance', 1, 1, @now, @now, 0, NULL, NULL),
(6, 1, 'CAPABILITY_COMMUNICATION', 'primary', 1, 1, @now, @now, 0, NULL, NULL)
ON DUPLICATE KEY UPDATE
	actor_id = VALUES(actor_id),
	trait_key = VALUES(trait_key),
	trait_value = VALUES(trait_value),
	federation_node_id = VALUES(federation_node_id),
	created_by_actor_id = VALUES(created_by_actor_id),
	created_ymdhis = VALUES(created_ymdhis),
	updated_ymdhis = VALUES(updated_ymdhis),
	is_deleted = VALUES(is_deleted),
	deleted_ymdhis = VALUES(deleted_ymdhis),
	metadata = VALUES(metadata);

-- ============================================================================
-- {{prefix}}edge_types: canonical edge slugs for channel/thread/artifact graph (4.0.87)
-- Idempotent upsert by primary key or unique slug.
-- ============================================================================
INSERT INTO {{prefix}}edge_types (edge_type_id, slug, label, description, is_bidirectional, created_ymdhis, updated_ymdhis, is_deleted)
VALUES
	(1,  'channel_related',          'Channel Related',             'Channels that are semantically or operationally related. Bidirectional.', 1, @now, @now, 0),
	(2,  'channel_parent',           'Channel Parent',              'Formal hierarchical parent. Supplements parent_channel_id. Directional.', 0, @now, @now, 0),
	(3,  'channel_successor',        'Channel Successor',           'This channel continued as or was superseded by the target channel.', 0, @now, @now, 0),
	(4,  'channel_spawned_thread',   'Channel Spawned Thread',      'This channel originated or owns this thread.', 0, @now, @now, 0),
	(5,  'channel_references',       'Channel References',          'Channel cites or references another channel.', 0, @now, @now, 0),
	(6,  'thread_continuation',      'Thread Continuation',         'This thread continues conversation from the target thread.', 0, @now, @now, 0),
	(7,  'thread_spawned_from',      'Thread Spawned From',         'This thread was forked or branched from the target thread.', 0, @now, @now, 0),
	(8,  'thread_references',        'Thread References',           'This thread cites or references the target thread or channel.', 0, @now, @now, 0),
	(9,  'thread_crosses_channel',   'Thread Crosses Channel',      'Thread activity spans into or involves another channel.', 0, @now, @now, 0),
	(10, 'channel_sibling',          'Channel Sibling',             'Channels at the same level sharing a purpose or origin. Bidirectional.', 1, @now, @now, 0),
	(11, 'artifact_spawned_from',    'Artifact Spawned From',       'Artifact was produced from this thread or channel.', 0, @now, @now, 0),
	(12, 'channel_observes',         'Channel Observes',            'Channel has a monitoring or observation relationship to the target.', 0, @now, @now, 0)
ON DUPLICATE KEY UPDATE
	slug = VALUES(slug),
	label = VALUES(label),
	description = VALUES(description),
	is_bidirectional = VALUES(is_bidirectional),
	updated_ymdhis = VALUES(updated_ymdhis),
	is_deleted = VALUES(is_deleted);

-- ============================================================================
-- {{prefix}}edge_type_definitions: type safety constraints for edge usage (4.0.87)
-- Idempotent upsert by primary key or unique edge_type.
-- ============================================================================
INSERT INTO {{prefix}}edge_type_definitions
	(edge_type_definition_id, edge_type, domain, description, allowed_left_object_types, allowed_right_object_types, is_bidirectional, semantic_meaning, created_ymdhis, created_by_actor_id)
VALUES
	(1, 'channel_related',       'channel',  'Related channels', 'channel',                   'channel',          1, 'Captures semantic or operational relationship between channels', @now, @actor_1),
	(2, 'channel_parent',        'channel',  'Parent hierarchy',  'channel',                   'channel',          0, 'Formal parent; supplements parent_channel_id structural column', @now, @actor_1),
	(3, 'channel_successor',     'channel',  'Channel successor', 'channel',                   'channel',          0, 'Target channel succeeded or continued this channel', @now, @actor_1),
	(4, 'channel_spawned_thread','channel',  'Thread ownership',  'channel',                   'thread',           0, 'Channel originated this thread', @now, @actor_1),
	(5, 'channel_references',    'channel',  'Channel citation',  'channel',                   'channel',          0, 'One channel cites another', @now, @actor_1),
	(6, 'thread_continuation',   'thread',   'Thread lineage',    'thread',                    'thread',           0, 'This thread continues from target thread; replaces thread_lineage TEXT', @now, @actor_1),
	(7, 'thread_spawned_from',   'thread',   'Thread fork',       'thread',                    'thread',           0, 'This thread was forked or branched from target thread', @now, @actor_1),
	(8, 'thread_references',     'thread',   'Thread citation',   'thread',                    'thread,channel',   0, 'Thread cites or references another thread or channel', @now, @actor_1),
	(9, 'thread_crosses_channel','thread',   'Cross-channel',     'thread',                    'channel',          0, 'Thread involves or spans into another channel', @now, @actor_1),
	(10,'channel_sibling',       'channel',  'Channel siblings',  'channel',                   'channel',          1, 'Channels sharing purpose or origin at same level', @now, @actor_1),
	(11,'artifact_spawned_from', 'artifact', 'Artifact lineage',  'artifact',                  'thread,channel',   0, 'Artifact was produced from a thread or channel conversation', @now, @actor_1),
	(12,'channel_observes',      'channel',  'Observation edge',  'channel,actor',             'channel',          0, 'Actor or channel monitors/observes the target channel', @now, @actor_1)
ON DUPLICATE KEY UPDATE
	edge_type = VALUES(edge_type),
	domain = VALUES(domain),
	description = VALUES(description),
	allowed_left_object_types = VALUES(allowed_left_object_types),
	allowed_right_object_types = VALUES(allowed_right_object_types),
	is_bidirectional = VALUES(is_bidirectional),
	semantic_meaning = VALUES(semantic_meaning),
	created_ymdhis = VALUES(created_ymdhis),
	created_by_actor_id = VALUES(created_by_actor_id);

-- ============================================================================
-- {{prefix}}action_authorization: core actions and required traits/roles
-- ============================================================================
INSERT INTO {{prefix}}action_authorization (action_authorization_id, action_key, description, required_trait_keys, required_capabilities, required_role_keys, requires_all_conditions, created_ymdhis, created_by_actor_id)
VALUES
(1, 'dialog.send_message', 'Send message in channel', '["CAPABILITY_COMMUNICATION"]', NULL, '["member","operator","captain"]', 0, @now, @actor_1),
(2, 'channel.create', 'Create new channel', '["CAPABILITY_ORCHESTRATION"]', NULL, NULL, 0, @now, @actor_1),
(3, 'rules.modify', 'Modify system rules', '["CAPABILITY_GOVERNANCE"]', NULL, NULL, 0, @now, @actor_1),
(4, 'traits.assign', 'Assign traits to actors', '["CAPABILITY_ORCHESTRATION"]', NULL, NULL, 0, @now, @actor_1)
ON DUPLICATE KEY UPDATE
	action_key = VALUES(action_key),
	description = VALUES(description),
	required_trait_keys = VALUES(required_trait_keys),
	required_capabilities = VALUES(required_capabilities),
	required_role_keys = VALUES(required_role_keys),
	requires_all_conditions = VALUES(requires_all_conditions),
	created_ymdhis = VALUES(created_ymdhis),
	created_by_actor_id = VALUES(created_by_actor_id);

-- <<< END FILE: seed_traits_edge_types_action_auth_4.0.69.sql

-- >>> BEGIN FILE: seed_projects.sql

-- Seed: {{prefix}}projects (4.0.77). Run after install. project_id application-assigned, no AUTO_INCREMENT.
-- Reserved project_id 0 = lupopedia-core. See PROJECT_REGISTRY_SCHEMA_DESIGN.md, create_{{prefix}}projects.sql.md.

INSERT INTO {{prefix}}projects (
  project_id,
  project_key,
  project_slug,
  project_name,
  federation_node_id,
  default_channel_id,
  orchestrator_id,
  project_type,
  description,
  github_repository,
  status,
  is_active,
  is_deleted,
  is_archived,
  is_frozen,
  metadata_json,
  created_ymdhis,
  updated_ymdhis,
  deleted_ymdhis,
  created_by_actor_id,
  updated_by_actor_id
) VALUES (
  0,
  'lupopedia-core',
  'lupopedia-core',
  'Lupopedia Core Development',
  1,
  42,
  1,
  'standard',
  'Primary Lupopedia semantic OS instance; default development project.',
  'https://github.com/wisdomoflovingfaith/lupopedia',
  'active',
  1,
  0,
  0,
  0,
  NULL,
  20260316000000,
  20260316000000,
  0,
  NULL,
  NULL
);

-- <<< END FILE: seed_projects.sql

-- >>> BEGIN FILE: seed_qa_lupopedia_4.0.88.sql

-- ============================================================================
-- Lupopedia Q/A seed (4.0.88)
-- Ensures /qa/lupopedia is available after install/upgrade.
-- ============================================================================

SET @qa_now = 20260325223000;

-- Question row: slug = lupopedia
INSERT INTO {{prefix}}questions (
  question_id,
  slug,
  question_text,
  actor_id,
  created_ymdhis,
  updated_ymdhis,
  is_deleted
)
SELECT
  7400001,
  'lupopedia',
  'What is Lupopedia?',
  1,
  @qa_now,
  @qa_now,
  0
WHERE NOT EXISTS (
  SELECT 1 FROM {{prefix}}questions WHERE slug = 'lupopedia'
);

-- Answer row sourced from README summary
INSERT INTO {{prefix}}answers (
  answer_id,
  question_id,
  answer_text,
  actor_id,
  created_ymdhis,
  updated_ymdhis,
  is_deleted
)
SELECT
  (7500000 + q.question_id),
  q.question_id,
  'Lupopedia is a doctrine-driven semantic operating system built on Crafty Syntax 3.7.5 foundations, with explicit actor orchestration, channel and thread workflows, and verifiable artifact metadata.',
  1,
  @qa_now,
  @qa_now,
  0
FROM {{prefix}}questions q
WHERE q.slug = 'lupopedia'
  AND NOT EXISTS (
    SELECT 1
    FROM {{prefix}}answers a
    WHERE a.question_id = q.question_id
      AND a.answer_text = 'Lupopedia is a doctrine-driven semantic operating system built on Crafty Syntax 3.7.5 foundations, with explicit actor orchestration, channel and thread workflows, and verifiable artifact metadata.'
  );

-- <<< END FILE: seed_qa_lupopedia_4.0.88.sql
