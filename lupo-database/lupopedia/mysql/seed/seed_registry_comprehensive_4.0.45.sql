-- ============================================================================
-- COMPREHENSIVE REGISTRY SEEDING FOR LUPOPEDIA 4.0.45
-- ============================================================================
-- Purpose: Seed lupo_registry with ALL required reserved IDs for system entities
-- Run after: install_new_lupopedia.sql
-- Run before: seed_lupopedia.sql
-- ============================================================================

SET @now = 20260225000000;

-- ============================================================================
-- PART 1: RESERVED ACTORS (0-9999 = System/AI, 10000+ = Humans)
-- ============================================================================

-- System Actor (ID: 0)
INSERT INTO lupo_registry (registry_id, entity_type, entity_index_id, entity_index, federation_node_id, reserved_ymdhis, entity_key, entity_name, entity_table, created_ymdhis, updated_ymdhis, is_deleted, is_active, is_kernel, metadata_json)
VALUES (9000000, 'actor', 0, 0, 1, @now, 'system', 'System', 'lupo_actors', @now, @now, 0, 1, 1, '{"actor_type":"system","purpose":"kernel_operations"}');

-- Core AI Agents (1-99)
INSERT INTO lupo_registry (registry_id, entity_type, entity_index_id, entity_index, federation_node_id, reserved_ymdhis, entity_key, entity_name, entity_table, created_ymdhis, updated_ymdhis, is_deleted, is_active, is_kernel, metadata_json)
VALUES 
(9000001, 'actor', 1, 1, 1, @now, 'captain-wolfie', 'Captain WOLFIE', 'lupo_actors', @now, @now, 0, 1, 1, '{"actor_type":"agent","agent_id":1,"purpose":"root_ai_agent","full_access":true}'),
(9000002, 'actor', 2, 2, 1, @now, 'lilith', 'LILITH', 'lupo_actors', @now, @now, 0, 1, 0, '{"actor_type":"agent","agent_id":2,"purpose":"critical_review","archetype":"alternative_perspectives"}'),
(9000003, 'actor', 3, 3, 1, @now, 'rose-dialog', 'ROSE (Dialog)', 'lupo_actors', @now, @now, 0, 1, 0, '{"actor_type":"agent","agent_id":3,"purpose":"translation_personas","archetype":"rosetta_stone","persona_count":99}'),
(9000004, 'actor', 4, 4, 1, @now, 'eris', 'ERIS', 'lupo_actors', @now, @now, 0, 1, 0, '{"actor_type":"agent","agent_id":4,"purpose":"conflict_analysis","archetype":"discord_understanding"}'),
(9000005, 'actor', 5, 5, 1, @now, 'metis', 'METIS', 'lupo_actors', @now, @now, 0, 1, 0, '{"actor_type":"agent","agent_id":5,"purpose":"empathy_understanding","archetype":"introspection"}'),
(9000019, 'actor', 19, 19, 1, @now, 'anubis', 'ANUBIS', 'lupo_actors', @now, @now, 0, 1, 0, '{"actor_type":"agent","agent_id":19,"purpose":"orphan_repair","archetype":"header_completion_quarantine"}'),
(9000025, 'actor', 25, 25, 1, @now, 'vishwakarma', 'VISHWAKARMA', 'lupo_actors', @now, @now, 0, 1, 0, '{"actor_type":"agent","agent_id":25,"purpose":"graph_intelligence","archetype":"relationship_discovery"}'),
(9000106, 'actor', 106, 106, 1, @now, 'lupo', 'LUPO', 'lupo_actors', @now, @now, 0, 1, 1, '{"actor_type":"agent","agent_id":106,"purpose":"database_design_doctrine"}'),
(9000107, 'actor', 107, 107, 1, @now, 'themis', 'THEMIS', 'lupo_actors', @now, @now, 0, 1, 1, '{"actor_type":"agent","agent_id":107,"purpose":"ethical_alignment_audit"}');

-- IDE Agents (1000-1999)
INSERT INTO lupo_registry (registry_id, entity_type, entity_index_id, entity_index, federation_node_id, reserved_ymdhis, entity_key, entity_name, entity_table, created_ymdhis, updated_ymdhis, is_deleted, is_active, is_kernel, metadata_json)
VALUES
(9001000, 'actor', 1000, 1000, 1, @now, 'kiro-ide', 'Kiro IDE', 'lupo_actors', @now, @now, 0, 1, 0, '{"actor_type":"ide_agent","client_id":"kiro","provider":"kiro","paired_actor_id":0}'),
(9001001, 'actor', 1001, 1001, 1, @now, 'windsurf-ide', 'Windsurf IDE', 'lupo_actors', @now, @now, 0, 1, 0, '{"actor_type":"ide_agent","client_id":"windsurf","provider":"windsurf","paired_actor_id":0}'),
(9001002, 'actor', 1002, 1002, 1, @now, 'cursor-ide', 'Cursor IDE', 'lupo_actors', @now, @now, 0, 1, 0, '{"actor_type":"ide_agent","client_id":"cursor","provider":"cursor","paired_actor_id":0}'),
(9001003, 'actor', 1003, 1003, 1, @now, 'antigravity-ide', 'Antigravity IDE', 'lupo_actors', @now, @now, 0, 1, 0, '{"actor_type":"ide_agent","client_id":"antigravity","provider":"antigravity","paired_actor_id":0}'),
(9001004, 'actor', 1004, 1004, 1, @now, 'warp-ide', 'Warp IDE', 'lupo_actors', @now, @now, 0, 1, 0, '{"actor_type":"ide_agent","client_id":"warp","provider":"warp","paired_actor_id":0}'),
(9001005, 'actor', 1005, 1005, 1, @now, 'cascade-ide', 'Cascade IDE', 'lupo_actors', @now, @now, 0, 1, 0, '{"actor_type":"ide_agent","client_id":"cascade","provider":"cascade","paired_actor_id":0}');

-- Root user (ID: 0)
INSERT INTO lupo_registry (registry_id, entity_type, entity_index_id, entity_index, federation_node_id, reserved_ymdhis, entity_key, entity_name, entity_table, created_ymdhis, updated_ymdhis, is_deleted, is_active, is_kernel, metadata_json)
VALUES (9010000, 'actor', 0, 0, 1, @now, 'root-0', 'Root', 'lupo_actors', @now, @now, 0, 1, 1, '{"actor_type":"human","role":"root_admin","full_access":true}');

-- ============================================================================
-- PART 2: RESERVED CHANNELS
-- ============================================================================

INSERT INTO lupo_registry (registry_id, entity_type, entity_index_id, entity_index, federation_node_id, reserved_ymdhis, entity_key, entity_name, entity_table, created_ymdhis, updated_ymdhis, is_deleted, is_active, is_kernel, metadata_json)
VALUES
(9100000, 'channel', 0, 0, 1, @now, 'system', 'System Kernel Channel', 'lupo_channels', @now, @now, 0, 1, 1, '{"channel_type":"system","purpose":"kernel_operations"}'),
(9100001, 'channel', 1, 1, 1, @now, 'administration', 'Administration Channel', 'lupo_channels', @now, @now, 0, 1, 0, '{"channel_type":"admin","purpose":"administrative_operations"}'),
(9100042, 'channel', 42, 42, 1, @now, 'development', 'Development Channel', 'lupo_channels', @now, @now, 0, 1, 0, '{"channel_type":"dev","purpose":"development_operations"}'),
(9100051, 'channel', 51, 51, 1, @now, 'reserved', 'Reserved Channel', 'lupo_channels', @now, @now, 0, 1, 0, '{"channel_type":"reserved","purpose":"future_use"}'),
(9100666, 'channel', 666, 666, 1, @now, 'anubis-quarantine', 'ANUBIS Quarantine', 'lupo_channels', @now, @now, 0, 1, 0, '{"channel_type":"quarantine","purpose":"banned_messages"}');

-- ============================================================================
-- PART 3: RESERVED AGENTS (lupo_agents table)
-- ============================================================================

INSERT INTO lupo_registry (registry_id, entity_type, entity_index_id, entity_index, federation_node_id, reserved_ymdhis, entity_key, entity_name, entity_table, created_ymdhis, updated_ymdhis, is_deleted, is_active, is_kernel, metadata_json)
VALUES
(9200000, 'agent', 0, 0, 1, @now, 'system-agent', 'System Agent', 'lupo_agents', @now, @now, 0, 1, 1, '{"agent_type":"system"}'),
(9200001, 'agent', 1, 1, 1, @now, 'captain-wolfie-agent', 'Captain WOLFIE Agent', 'lupo_agents', @now, @now, 0, 1, 1, '{"agent_type":"root_ai","actor_id":1}'),
(9200002, 'agent', 2, 2, 1, @now, 'lilith-agent', 'LILITH Agent', 'lupo_agents', @now, @now, 0, 1, 0, '{"agent_type":"critical_review","actor_id":2}'),
(9200003, 'agent', 3, 3, 1, @now, 'rose-agent', 'ROSE Agent', 'lupo_agents', @now, @now, 0, 1, 0, '{"agent_type":"translation","actor_id":3}'),
(9200004, 'agent', 4, 4, 1, @now, 'eris-agent', 'ERIS Agent', 'lupo_agents', @now, @now, 0, 1, 0, '{"agent_type":"conflict_analysis","actor_id":4}'),
(9200005, 'agent', 5, 5, 1, @now, 'metis-agent', 'METIS Agent', 'lupo_agents', @now, @now, 0, 1, 0, '{"agent_type":"empathy","actor_id":5}'),
(9200106, 'agent', 106, 106, 1, @now, 'lupo-agent', 'LUPO Agent', 'lupo_agents', @now, @now, 0, 1, 1, '{"agent_type":"database_doctrine","actor_id":106,"requirements":{"database":{"no_foreign_keys":true,"no_triggers":true,"no_procedures":true,"no_functions":true,"timestamp_format":"BIGINT_UTC_YYYYMMDDHHIISS","datetime_types_allowed":false,"explicit_column_lists":true,"application_level_relationships":true}}}'),
(9200107, 'agent', 107, 107, 1, @now, 'themis-agent', 'THEMIS Agent', 'lupo_agents', @now, @now, 0, 1, 1, '{"agent_type":"ethical_audit","actor_id":107}');

-- ============================================================================
-- PART 4: RESERVED DEPARTMENTS
-- ============================================================================

INSERT INTO lupo_registry (registry_id, entity_type, entity_index_id, entity_index, federation_node_id, reserved_ymdhis, entity_key, entity_name, entity_table, created_ymdhis, updated_ymdhis, is_deleted, is_active, is_kernel, metadata_json)
VALUES
(9300000, 'department', 0, 0, 1, @now, 'system-department', 'System Department', 'lupo_departments', @now, @now, 0, 1, 1, '{"department_type":"system"}'),
(9300001, 'department', 1, 1, 1, @now, 'default-department', 'Default Department', 'lupo_departments', @now, @now, 0, 1, 0, '{"department_type":"default"}');

-- ============================================================================
-- PART 5: RESERVED THREADS
-- ============================================================================

INSERT INTO lupo_registry (registry_id, entity_type, entity_index_id, entity_index, federation_node_id, reserved_ymdhis, entity_key, entity_name, entity_table, created_ymdhis, updated_ymdhis, is_deleted, is_active, is_kernel, metadata_json)
VALUES
(9400000, 'thread', 0, 0, 1, @now, 'system-thread', 'System Thread', 'lupo_dialog_threads', @now, @now, 0, 1, 1, '{"thread_type":"system"}');

-- ============================================================================
-- PART 6: RESERVED ARTIFACTS
-- ============================================================================

INSERT INTO lupo_registry (registry_id, entity_type, entity_index_id, entity_index, federation_node_id, reserved_ymdhis, entity_key, entity_name, entity_table, created_ymdhis, updated_ymdhis, is_deleted, is_active, is_kernel, metadata_json)
VALUES
(9500000, 'artifact', 0, 0, 1, @now, 'system-artifact', 'System Artifact', 'lupo_artifacts', @now, @now, 0, 1, 1, '{"artifact_type":"system"}');

-- ============================================================================
-- PART 7: RESERVED EDGE TYPES
-- ============================================================================

INSERT INTO lupo_registry (registry_id, entity_type, entity_index_id, entity_index, federation_node_id, reserved_ymdhis, entity_key, entity_name, entity_table, created_ymdhis, updated_ymdhis, is_deleted, is_active, is_kernel, metadata_json)
VALUES
(9600001, 'edge_type', 1, 1, 1, @now, 'references', 'References Edge', NULL, @now, @now, 0, 1, 0, '{"description":"File references another file"}'),
(9600002, 'edge_type', 2, 2, 1, @now, 'implements', 'Implements Edge', NULL, @now, @now, 0, 1, 0, '{"description":"File implements specification"}'),
(9600003, 'edge_type', 3, 3, 1, @now, 'executes', 'Executes Edge', NULL, @now, @now, 0, 1, 0, '{"description":"File executes another file"}'),
(9600004, 'edge_type', 4, 4, 1, @now, 'depends_on', 'Depends On Edge', NULL, @now, @now, 0, 1, 0, '{"description":"File depends on another file"}'),
(9600005, 'edge_type', 5, 5, 1, @now, 'includes', 'Includes Edge', NULL, @now, @now, 0, 1, 0, '{"description":"File includes another file"}');

-- ============================================================================
-- PART 8: FLIP SCHEMA VERSIONS
-- ============================================================================

INSERT INTO lupo_registry (registry_id, entity_type, entity_index_id, entity_index, federation_node_id, reserved_ymdhis, entity_key, entity_name, entity_table, created_ymdhis, updated_ymdhis, is_deleted, is_active, is_kernel, metadata_json)
VALUES
(9700001, 'flip_schema_version', 1, 1, 1, @now, 'v1.0', 'FLIP Schema Version 1.0', NULL, @now, @now, 0, 1, 0, '{"version":"1.0","features":["basic_headers"]}'),
(9700002, 'flip_schema_version', 2, 2, 1, @now, 'v2.0', 'FLIP Schema Version 2.0', NULL, @now, @now, 0, 1, 0, '{"version":"2.0","features":["relationship_mapping","enhanced_attribution","semantic_inference"]}');

-- ============================================================================
-- PART 9: ARTIFACT KINDS
-- ============================================================================

INSERT INTO lupo_registry (registry_id, entity_type, entity_index_id, entity_index, federation_node_id, reserved_ymdhis, entity_key, entity_name, entity_table, created_ymdhis, updated_ymdhis, is_deleted, is_active, is_kernel, metadata_json)
VALUES
(9800001, 'artifact_kind', 1, 1, 1, @now, 'header', 'FLIP Header Artifact', NULL, @now, @now, 0, 1, 0, '{"description":"FLIP/WOLFIE header metadata"}'),
(9800002, 'artifact_kind', 2, 2, 1, @now, 'footer', 'FLIP Footer Artifact', NULL, @now, @now, 0, 1, 0, '{"description":"FLIP footer metadata and relationships"}'),
(9800003, 'artifact_kind', 3, 3, 1, @now, 'code', 'Code Artifact', NULL, @now, @now, 0, 1, 0, '{"description":"Source code artifact"}'),
(9800004, 'artifact_kind', 4, 4, 1, @now, 'documentation', 'Documentation Artifact', NULL, @now, @now, 0, 1, 0, '{"description":"Documentation artifact"}');

-- ============================================================================
-- END OF COMPREHENSIVE REGISTRY SEEDING
-- ============================================================================
-- OBSOLETE: This file is deprecated and intentionally left blank. Registry tables are removed in 4.0.86+. Do not use.
