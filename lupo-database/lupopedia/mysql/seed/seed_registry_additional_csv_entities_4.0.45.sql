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
INSERT INTO lupo_registry (registry_id, entity_type, entity_index_id, entity_index, federation_node_id, reserved_ymdhis, entity_key, entity_name, entity_table, created_ymdhis, updated_ymdhis, is_deleted, is_active, is_kernel, metadata_json)
VALUES (9000002, 'actor', 2, 2, 1, @now, 'windsurf-ide-system', 'Windsurf IDE', 'lupo_actors', @now, @now, 0, 1, 0, '{"actor_type":"system_tool","purpose":"ide_integration","client_id":"windsurf"}');

-- Test Actors (2000-2010 range)
INSERT INTO lupo_registry (registry_id, entity_type, entity_index_id, entity_index, federation_node_id, reserved_ymdhis, entity_key, entity_name, entity_table, created_ymdhis, updated_ymdhis, is_deleted, is_active, is_kernel, metadata_json)
VALUES 
(9020000, 'actor', 2000, 2000, 1, @now, 'cursor-test', 'Cursor', 'lupo_actors', @now, @now, 0, 1, 0, '{"actor_type":"agent","purpose":"test_agent","test_range":true}'),
(9020001, 'actor', 2001, 2001, 1, @now, 'user-2001', 'Admin Test', 'lupo_actors', @now, @now, 0, 1, 0, '{"actor_type":"user","purpose":"test_user","test_range":true}'),
(9020002, 'actor', 2002, 2002, 1, @now, 'user-2002', 'Jane Moderator', 'lupo_actors', @now, @now, 0, 1, 0, '{"actor_type":"user","purpose":"test_user","test_range":true}'),
(9020003, 'actor', 2003, 2003, 1, @now, 'user-2003', 'Bob Monitor', 'lupo_actors', @now, @now, 0, 1, 0, '{"actor_type":"user","purpose":"test_user","test_range":true}'),
(9020004, 'actor', 2004, 2004, 1, @now, 'user-2004', 'Alex Agent', 'lupo_actors', @now, @now, 0, 1, 0, '{"actor_type":"user","purpose":"test_user","test_range":true}'),
(9020005, 'actor', 2005, 2005, 1, @now, 'user-2005', 'Sam Support', 'lupo_actors', @now, @now, 0, 1, 0, '{"actor_type":"user","purpose":"test_user","test_range":true}'),
(9020006, 'actor', 2006, 2006, 1, @now, 'user-2006', 'Lee Viewer', 'lupo_actors', @now, @now, 0, 1, 0, '{"actor_type":"user","purpose":"test_user","test_range":true}'),
(9020007, 'actor', 2007, 2007, 1, @now, 'user-2007', 'Kim Readonly', 'lupo_actors', @now, @now, 0, 1, 0, '{"actor_type":"user","purpose":"test_user","test_range":true}'),
(9020008, 'actor', 2008, 2008, 1, @now, 'user-2008', 'Taylor Operator', 'lupo_actors', @now, @now, 0, 1, 0, '{"actor_type":"user","purpose":"test_user","test_range":true}'),
(9020009, 'actor', 2009, 2009, 1, @now, 'user-2009', 'Casey Support', 'lupo_actors', @now, @now, 0, 1, 0, '{"actor_type":"user","purpose":"test_user","test_range":true}'),
(9020010, 'actor', 2010, 2010, 1, @now, 'user-2010', 'Jordan CRM', 'lupo_actors', @now, @now, 0, 1, 0, '{"actor_type":"user","purpose":"test_user","test_range":true}');

-- Additional User Actors
INSERT INTO lupo_registry (registry_id, entity_type, entity_index_id, entity_index, federation_node_id, reserved_ymdhis, entity_key, entity_name, entity_table, created_ymdhis, updated_ymdhis, is_deleted, is_active, is_kernel, metadata_json)
VALUES
(9021001, 'actor', 10001, 10001, 1, @now, 'user-10001', 'Stoned Wolfie', 'lupo_actors', @now, @now, 0, 0, 0, '{"actor_type":"user","status":"inactive","purpose":"legacy_user"}'),
(90212150, 'actor', 12150, 12150, 1, @now, 'helen-at-lupopedia-com', 'helen', 'lupo_actors', @now, @now, 0, 1, 0, '{"actor_type":"user","email":"helen@lupopedia.com","purpose":"admin_user"}');

-- ============================================================================
-- MISSING AGENTS FROM CSV DATA
-- ============================================================================

-- Test Agents (2001-2006 range)
INSERT INTO lupo_registry (registry_id, entity_type, entity_index_id, entity_index, federation_node_id, reserved_ymdhis, entity_key, entity_name, entity_table, created_ymdhis, updated_ymdhis, is_deleted, is_active, is_kernel, metadata_json)
VALUES 
(9102001, 'agent', 2001, 2001, 1, @now, 'seed-router-01', 'Seed Router Agent', 'lupo_agents', @now, @now, 0, 0, 0, '{"agent_type":"router","purpose":"test_agent","test_range":true}'),
(9102002, 'agent', 2002, 2002, 1, @now, 'seed-support-01', 'Seed Support Agent', 'lupo_agents', @now, @now, 0, 0, 0, '{"agent_type":"support","purpose":"test_agent","test_range":true}'),
(9102003, 'agent', 2003, 2003, 1, @now, 'seed-crm-01', 'Seed CRM Agent', 'lupo_agents', @now, @now, 0, 0, 0, '{"agent_type":"crm","purpose":"test_agent","test_range":true}'),
(9102004, 'agent', 2004, 2004, 1, @now, 'seed-docs-01', 'Seed Docs Agent', 'lupo_agents', @now, @now, 0, 0, 0, '{"agent_type":"docs","purpose":"test_agent","test_range":true}'),
(9102005, 'agent', 2005, 2005, 1, @now, 'seed-analytics-01', 'Seed Analytics Agent', 'lupo_agents', @now, @now, 0, 0, 0, '{"agent_type":"analytics","purpose":"test_agent","test_range":true}'),
(9102006, 'agent', 2006, 2006, 1, @now, 'seed-mod-01', 'Seed Moderation Agent', 'lupo_agents', @now, @now, 0, 0, 0, '{"agent_type":"moderation","purpose":"test_agent","test_range":true}');

-- ============================================================================
-- MISSING CHANNELS FROM CSV DATA
-- ============================================================================

-- Additional Channels from CSV
INSERT INTO lupo_registry (registry_id, entity_type, entity_index_id, entity_index, federation_node_id, reserved_ymdhis, entity_key, entity_name, entity_table, created_ymdhis, updated_ymdhis, is_deleted, is_active, is_kernel, metadata_json)
VALUES
(9100666, 'channel', 666, 666, 1, @now, 'anubis-quarantine', 'ANUBIS Quarantine', 'lupo_channels', @now, @now, 0, 1, 0, '{"channel_type":"quarantine","purpose":"banned_content","anubis":true}'),
(9102001, 'channel', 2001, 2001, 1, @now, 'admin-test-system', 'Admin Test System', 'lupo_channels', @now, @now, 0, 1, 0, '{"channel_type":"system","purpose":"admin_testing"}'),
(9102002, 'channel', 2002, 2002, 1, @now, 'support-inbox', 'Support Inbox', 'lupo_channels', @now, @now, 0, 1, 0, '{"channel_type":"support","purpose":"customer_support"}'),
(9102003, 'channel', 2003, 2003, 1, @now, 'crm-leads', 'CRM Leads', 'lupo_channels', @now, @now, 0, 1, 0, '{"channel_type":"crm","purpose":"lead_pipeline"}'),
(9102004, 'channel', 2004, 2004, 1, @now, 'docs-internal', 'Internal Docs', 'lupo_channels', @now, @now, 0, 1, 0, '{"channel_type":"docs","purpose":"internal_documentation"}'),
(9102005, 'channel', 2005, 2005, 1, @now, 'eng-dev', 'Engineering Dev', 'lupo_channels', @now, @now, 0, 1, 0, '{"channel_type":"development","purpose":"engineering"}'),
(9102006, 'channel', 2006, 2006, 1, @now, 'mod-queue', 'Moderation Queue', 'lupo_channels', @now, @now, 0, 1, 0, '{"channel_type":"moderation","purpose":"content_moderation"}'),
(9102007, 'channel', 2007, 2007, 1, @now, 'support-archive', 'Support Archive', 'lupo_channels', @now, @now, 0, 1, 0, '{"channel_type":"support","purpose":"archived_threads"}'),
(9102008, 'channel', 2008, 2008, 1, @now, 'crm-campaigns', 'CRM Campaigns', 'lupo_channels', @now, @now, 0, 1, 0, '{"channel_type":"crm","purpose":"marketing_campaigns"}'),
(9102009, 'channel', 2009, 2009, 1, @now, 'helen-channel', 'helen\'s Channel', 'lupo_channels', @now, @now, 0, 1, 0, '{"channel_type":"personal","purpose":"user_channel","owner":"helen"}');

-- ============================================================================
-- MISSING DEPARTMENTS FROM CSV DATA
-- ============================================================================

-- Additional Department (ID: 1 - default already exists, but ensure proper metadata)
INSERT INTO lupo_registry (registry_id, entity_type, entity_index_id, entity_index, federation_node_id, reserved_ymdhis, entity_key, entity_name, entity_table, created_ymdhis, updated_ymdhis, is_deleted, is_active, is_kernel, metadata_json)
VALUES (9100001, 'department', 1, 1, 1, @now, 'default', 'Default Department', 'lupo_departments', @now, @now, 0, 1, 0, '{"department_type":"crafty","purpose":"default_department","default_actor_id":1}');

-- ============================================================================
-- EDGE TYPES AND RELATIONSHIPS
-- ============================================================================

-- Common Edge Types
INSERT INTO lupo_registry (registry_id, entity_type, entity_index_id, entity_index, federation_node_id, reserved_ymdhis, entity_key, entity_name, entity_table, created_ymdhis, updated_ymdhis, is_deleted, is_active, is_kernel, metadata_json)
VALUES
(9200001, 'edge_type', 1, 1, 1, @now, 'references', 'References', 'lupo_edge_types', @now, @now, 0, 1, 0, '{"edge_type":"reference","direction":"bidirectional","purpose":"cross_reference"}'),
(9200002, 'edge_type', 2, 2, 1, @now, 'implements', 'Implements', 'lupo_edge_types', @now, @now, 0, 1, 0, '{"edge_type":"implementation","direction":"unidirectional","purpose":"code_implementation"}'),
(9200003, 'edge_type', 3, 3, 1, @now, 'executes', 'Executes', 'lupo_edge_types', @now, @now, 0, 1, 0, '{"edge_type":"execution","direction":"unidirectional","purpose":"process_execution"}'),
(9200004, 'edge_type', 4, 4, 1, @now, 'depends_on', 'Depends On', 'lupo_edge_types', @now, @now, 0, 1, 0, '{"edge_type":"dependency","direction":"unidirectional","purpose":"dependency_relationship"}'),
(9200005, 'edge_type', 5, 5, 1, @now, 'includes', 'Includes', 'lupo_edge_types', @now, @now, 0, 1, 0, '{"edge_type":"inclusion","direction":"unidirectional","purpose":"content_inclusion"}'),
(9200006, 'edge_type', 6, 6, 1, @now, 'governs', 'Governs', 'lupo_edge_types', @now, @now, 0, 1, 0, '{"edge_type":"governance","direction":"unidirectional","purpose":"governance_relationship"}'),
(9200007, 'edge_type', 7, 7, 1, @now, 'full_documentation', 'Full Documentation', 'lupo_edge_types', @now, @now, 0, 1, 0, '{"edge_type":"documentation","direction":"unidirectional","purpose":"broadcast_to_full_doc"}'),
(9200008, 'edge_type', 8, 8, 1, @now, 'php_doctrine', 'PHP Doctrine', 'lupo_edge_types', @now, @now, 0, 1, 0, '{"edge_type":"doctrine","direction":"semantic","purpose":"php_compatibility_doctrine"}');

-- ============================================================================
-- ARTIFACT KINDS
-- ============================================================================

-- Artifact Kinds
INSERT INTO lupo_registry (registry_id, entity_type, entity_index_id, entity_index, federation_node_id, reserved_ymdhis, entity_key, entity_name, entity_table, created_ymdhis, updated_ymdhis, is_deleted, is_active, is_kernel, metadata_json)
VALUES
(9300001, 'artifact_kind', 1, 1, 1, @now, 'header', 'Header', 'lupo_artifact_kinds', @now, @now, 0, 1, 0, '{"artifact_kind":"header","purpose":"file_header_metadata"}'),
(9300002, 'artifact_kind', 2, 2, 1, @now, 'footer', 'Footer', 'lupo_artifact_kinds', @now, @now, 0, 1, 0, '{"artifact_kind":"footer","purpose":"file_footer_metadata"}'),
(9300003, 'artifact_kind', 3, 3, 1, @now, 'code', 'Code', 'lupo_artifact_kinds', @now, @now, 0, 1, 0, '{"artifact_kind":"code","purpose":"source_code"}'),
(9300004, 'artifact_kind', 4, 4, 1, @now, 'documentation', 'Documentation', 'lupo_artifact_kinds', @now, @now, 0, 1, 0, '{"artifact_kind":"documentation","purpose":"technical_documentation"}'),
(9300005, 'artifact_kind', 5, 5, 1, @now, 'broadcast', 'Broadcast', 'lupo_artifact_kinds', @now, @now, 0, 1, 0, '{"artifact_kind":"broadcast","purpose":"channel_broadcast_message"}'),
(9300006, 'artifact_kind', 6, 6, 1, @now, 'doctrine', 'Doctrine', 'lupo_artifact_kinds', @now, @now, 0, 1, 0, '{"artifact_kind":"doctrine","purpose":"system_doctrine"}'),
(9300007, 'artifact_kind', 7, 7, 1, @now, 'audit_report', 'Audit Report', 'lupo_artifact_kinds', @now, @now, 0, 1, 0, '{"artifact_kind":"audit_report","purpose":"audit_analysis"}');

-- ============================================================================
-- THREAD TYPES
-- ============================================================================

-- Thread Types
INSERT INTO lupo_registry (registry_id, entity_type, entity_index_id, entity_index, federation_node_id, reserved_ymdhis, entity_key, entity_name, entity_table, created_ymdhis, updated_ymdhis, is_deleted, is_active, is_kernel, metadata_json)
VALUES
(9400001, 'thread_type', 1, 1, 1, @now, 'chat', 'Chat Thread', 'lupo_thread_types', @now, @now, 0, 1, 0, '{"thread_type":"chat","purpose":"conversation_thread"}'),
(9400002, 'thread_type', 2, 2, 1, @now, 'support', 'Support Thread', 'lupo_thread_types', @now, @now, 0, 1, 0, '{"thread_type":"support","purpose":"customer_support_thread"}'),
(9400003, 'thread_type', 3, 3, 1, @now, 'crm', 'CRM Thread', 'lupo_thread_types', @now, @now, 0, 1, 0, '{"thread_type":"crm","purpose":"customer_relationship_thread"}'),
(9400004, 'thread_type', 4, 4, 1, @now, 'development', 'Development Thread', 'lupo_thread_types', @now, @now, 0, 1, 0, '{"thread_type":"development","purpose":"development_discussion"}'),
(9400005, 'thread_type', 5, 5, 1, @now, 'moderation', 'Moderation Thread', 'lupo_thread_types', @now, @now, 0, 1, 0, '{"thread_type":"moderation","purpose":"content_moderation"}');

-- ============================================================================
-- FLIP VERSIONS
-- ============================================================================

-- FLIP Header/Footer Versions
INSERT INTO lupo_registry (registry_id, entity_type, entity_index_id, entity_index, federation_node_id, reserved_ymdhis, entity_key, entity_name, entity_table, created_ymdhis, updated_ymdhis, is_deleted, is_active, is_kernel, metadata_json)
VALUES
(9500001, 'flip_version', 1, 1, 1, @now, 'v1.0', 'FLIP v1.0', 'lupo_flip_versions', @now, @now, 0, 1, 0, '{"flip_version":"1.0","format":"yaml","purpose":"original_flip_spec"}'),
(9500002, 'flip_version', 2, 2, 1, @now, 'v2.0', 'FLIP v2.0', 'lupo_flip_versions', @now, @now, 0, 1, 0, '{"flip_version":"2.0","format":"yaml","purpose":"enhanced_flip_spec"}'),
(9500003, 'flip_version', 3, 3, 1, @now, 'v3.0', 'FLIP v3.0', 'lupo_flip_versions', @now, @now, 0, 1, 0, '{"flip_version":"3.0","format":"yaml","purpose":"current_flip_spec"}');
