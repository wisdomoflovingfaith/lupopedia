-- FILE: database/migrations/seed_lupopedia.sql
-- Generated from docs/toons/*.toon.json and live DB. DO NOT EDIT BY HAND.
-- Purpose: Seed data for fresh Lupopedia 4.0.0 install. Run after install_new_lupopedia.sql.
-- No Crafty Syntax data. No schema. INSERT only.

-- =============================================================================
-- SEED LUPOPEDIA — CANONICAL BIRTH-STATE
-- =============================================================================

-- -----------------------------------------------------------------------------
-- Unified registry (lupo_registry)
-- -----------------------------------------------------------------------------
INSERT INTO lupo_registry (`registry_id`, `entity_type`, `entity_index_id`, `entity_index`, `federation_node_id`, `reserved_ymdhis`, `metadata`, `entity_key`, `entity_name`, `entity_table`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`) VALUES (9002039, 'actor', 2039, 2039, 0, 0, NULL, 'warp-ide', 'Warp IDE', 'lupo_actors', 20260224000000, 20260224000000, 0, NULL, 1, 0, '{"actor_source_type":"system_tool","client_id":"warp","provider":"warp","purpose":"IDE_integration","paired_actor_id":10000}');

INSERT INTO lupo_registry (`registry_id`, `entity_type`, `entity_index_id`, `entity_index`, `federation_node_id`, `reserved_ymdhis`, `metadata`, `entity_key`, `entity_name`, `entity_table`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`) VALUES (9002040, 'actor', 2040, 2040, 0, 0, NULL, 'windsurf-ide', 'Windsurf IDE', 'lupo_actors', 20260224000000, 20260224000000, 0, NULL, 1, 0, '{"actor_source_type":"system_tool","client_id":"windsurf","provider":"windsurf","purpose":"IDE_integration","paired_actor_id":10000}');

INSERT INTO lupo_registry (`registry_id`, `entity_type`, `entity_index_id`, `entity_index`, `federation_node_id`, `reserved_ymdhis`, `metadata`, `entity_key`, `entity_name`, `entity_table`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`) VALUES (9005001, 'flip_schema_version', 1, 1, 1, 0, NULL, 'v2.0', 'FLIP Schema Version 2.0', NULL, 20260224000000, 20260224000000, 0, NULL, 1, 0, '{"version": "2.0", "features": ["relationship_mapping", "enhanced_attribution", "semantic_inference"]}');

INSERT INTO lupo_registry (`registry_id`, `entity_type`, `entity_index_id`, `entity_index`, `federation_node_id`, `reserved_ymdhis`, `metadata`, `entity_key`, `entity_name`, `entity_table`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`) VALUES (9005002, 'artifact_kind', 1, 1, 1, 0, NULL, 'header', 'FLIP Header Artifact', NULL, 20260224000000, 20260224000000, 0, NULL, 1, 0, '{"description": "FLIP/WOLFIE header metadata"}');

INSERT INTO lupo_registry (`registry_id`, `entity_type`, `entity_index_id`, `entity_index`, `federation_node_id`, `reserved_ymdhis`, `metadata`, `entity_key`, `entity_name`, `entity_table`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`) VALUES (9005003, 'artifact_kind', 2, 2, 1, 0, NULL, 'footer', 'FLIP Footer Artifact', NULL, 20260224000000, 20260224000000, 0, NULL, 1, 0, '{"description": "FLIP footer metadata and relationships"}');

INSERT INTO lupo_registry (`registry_id`, `entity_type`, `entity_index_id`, `entity_index`, `federation_node_id`, `reserved_ymdhis`, `metadata`, `entity_key`, `entity_name`, `entity_table`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`) VALUES (9005004, 'edge_type', 1, 1, 1, 0, NULL, 'inbound_edge', 'File Inbound Edge', NULL, 20260224000000, 20260224000000, 0, NULL, 1, 0, '{"description": "References pointing to this file"}');

INSERT INTO lupo_registry (`registry_id`, `entity_type`, `entity_index_id`, `entity_index`, `federation_node_id`, `reserved_ymdhis`, `metadata`, `entity_key`, `entity_name`, `entity_table`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`) VALUES (9005005, 'edge_type', 2, 2, 1, 0, NULL, 'semantic_relationship', 'Semantic Relationship', NULL, 20260224000000, 20260224000000, 0, NULL, 1, 0, '{"description": "Semantic relationships between files"}');

-- -----------------------------------------------------------------------------
-- Active agents as actors (lupo_actors) — is_active=1 in unified registry
-- -----------------------------------------------------------------------------
-- (none)

-- -----------------------------------------------------------------------------
-- PK=0 / collection-type rows
-- -----------------------------------------------------------------------------
INSERT INTO lupo_channels (`channel_id`, `federation_node_id`, `created_by_actor_id`, `default_actor_id`, `department_id`, `channel_key`, `channel_slug`, `channel_type`, `language`, `channel_name`, `description`, `website_link`, `metadata_json`, `status_flag`, `end_ymdhis`, `duration_seconds`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`, `aal_metadata_json`, `fleet_composition_json`, `awareness_version`, `channel_number`, `parent_channel_id`, `is_kernel`, `boot_sequence_order`) VALUES (0, 1, 0, 0, 1, 'system', 'system', 'system', 'en', 'System Kernel Channel', 'System channel (kernel/system operations).', NULL, NULL, 1, NULL, NULL, 20260224143059, 20260224143059, 0, NULL, NULL, NULL, '3.0.0', NULL, NULL, 1, NULL);

INSERT INTO lupo_departments (`department_id`, `federation_node_id`, `name`, `description`, `department_type`, `default_actor_id`, `settings_json`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`) VALUES (0, 1, 'System', 'System Department (Reserved)', 'system', 0, NULL, 20260224143107, 20260224143107, 0, NULL);

INSERT INTO lupo_sessions (`session_id`, `federation_node_id`, `actor_id`, `channel_id`, `ip_address`, `user_agent`, `device_id`, `device_type`, `auth_method`, `auth_provider`, `security_level`, `name_key`, `is_named`, `is_authenticated`, `is_active`, `is_expired`, `is_revoked`, `session_data`, `system_context`, `metadata`, `login_ymdhis`, `last_seen_ymdhis`, `expires_ymdhis`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`) VALUES ('msib03k38jd25n4ivj48j304nv', 1, 10000, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36 Edg/145.0.0.0', NULL, 'desktop', 'password', 'local', 'high', NULL, 0, 0, 1, 0, 0, NULL, NULL, NULL, 20260224143147, 20260224143159, 20260225143147, 20260224143147, 20260224143159, 0, NULL);

-- -----------------------------------------------------------------------------
-- TOON-defined canonical rows (from "data" array)
-- -----------------------------------------------------------------------------
INSERT INTO lupo_channels (`channel_id`, `federation_node_id`, `created_by_actor_id`, `default_actor_id`, `department_id`, `channel_key`, `channel_slug`, `channel_type`, `language`, `channel_name`, `description`, `website_link`, `metadata_json`, `status_flag`, `end_ymdhis`, `duration_seconds`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`, `aal_metadata_json`, `fleet_composition_json`, `awareness_version`, `channel_number`, `parent_channel_id`, `is_kernel`, `boot_sequence_order`) VALUES (0, 1, 0, 0, 1, 'system', 'system', 'system', 'en', 'System Kernel Channel', 'System channel (kernel/system operations).', NULL, NULL, 1, NULL, NULL, 20260224143059, 20260224143059, 0, NULL, NULL, NULL, '3.0.0', NULL, NULL, 1, NULL);

INSERT INTO lupo_departments (`department_id`, `federation_node_id`, `name`, `description`, `department_type`, `default_actor_id`, `settings_json`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`) VALUES (0, 1, 'System', 'System Department (Reserved)', 'system', 0, NULL, 20260224143107, 20260224143107, 0, NULL);

INSERT INTO lupo_registry (`registry_id`, `entity_type`, `entity_index_id`, `entity_index`, `federation_node_id`, `reserved_ymdhis`, `metadata`, `entity_key`, `entity_name`, `entity_table`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`) VALUES (9002039, 'actor', 2039, 2039, 0, 0, NULL, 'warp-ide', 'Warp IDE', 'lupo_actors', 20260224000000, 20260224000000, 0, NULL, 1, 0, '{"actor_source_type":"system_tool","client_id":"warp","provider":"warp","purpose":"IDE_integration","paired_actor_id":10000}');

INSERT INTO lupo_registry (`registry_id`, `entity_type`, `entity_index_id`, `entity_index`, `federation_node_id`, `reserved_ymdhis`, `metadata`, `entity_key`, `entity_name`, `entity_table`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`) VALUES (9002040, 'actor', 2040, 2040, 0, 0, NULL, 'windsurf-ide', 'Windsurf IDE', 'lupo_actors', 20260224000000, 20260224000000, 0, NULL, 1, 0, '{"actor_source_type":"system_tool","client_id":"windsurf","provider":"windsurf","purpose":"IDE_integration","paired_actor_id":10000}');

INSERT INTO lupo_registry (`registry_id`, `entity_type`, `entity_index_id`, `entity_index`, `federation_node_id`, `reserved_ymdhis`, `metadata`, `entity_key`, `entity_name`, `entity_table`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`) VALUES (9005001, 'flip_schema_version', 1, 1, 1, 0, NULL, 'v2.0', 'FLIP Schema Version 2.0', NULL, 20260224000000, 20260224000000, 0, NULL, 1, 0, '{"version": "2.0", "features": ["relationship_mapping", "enhanced_attribution", "semantic_inference"]}');

INSERT INTO lupo_registry (`registry_id`, `entity_type`, `entity_index_id`, `entity_index`, `federation_node_id`, `reserved_ymdhis`, `metadata`, `entity_key`, `entity_name`, `entity_table`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`) VALUES (9005002, 'artifact_kind', 1, 1, 1, 0, NULL, 'header', 'FLIP Header Artifact', NULL, 20260224000000, 20260224000000, 0, NULL, 1, 0, '{"description": "FLIP/WOLFIE header metadata"}');

INSERT INTO lupo_registry (`registry_id`, `entity_type`, `entity_index_id`, `entity_index`, `federation_node_id`, `reserved_ymdhis`, `metadata`, `entity_key`, `entity_name`, `entity_table`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`) VALUES (9005003, 'artifact_kind', 2, 2, 1, 0, NULL, 'footer', 'FLIP Footer Artifact', NULL, 20260224000000, 20260224000000, 0, NULL, 1, 0, '{"description": "FLIP footer metadata and relationships"}');

INSERT INTO lupo_registry (`registry_id`, `entity_type`, `entity_index_id`, `entity_index`, `federation_node_id`, `reserved_ymdhis`, `metadata`, `entity_key`, `entity_name`, `entity_table`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`) VALUES (9005004, 'edge_type', 1, 1, 1, 0, NULL, 'inbound_edge', 'File Inbound Edge', NULL, 20260224000000, 20260224000000, 0, NULL, 1, 0, '{"description": "References pointing to this file"}');

INSERT INTO lupo_registry (`registry_id`, `entity_type`, `entity_index_id`, `entity_index`, `federation_node_id`, `reserved_ymdhis`, `metadata`, `entity_key`, `entity_name`, `entity_table`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`) VALUES (9005005, 'edge_type', 2, 2, 1, 0, NULL, 'semantic_relationship', 'Semantic Relationship', NULL, 20260224000000, 20260224000000, 0, NULL, 1, 0, '{"description": "Semantic relationships between files"}');

INSERT INTO lupo_sessions (`session_id`, `federation_node_id`, `actor_id`, `channel_id`, `ip_address`, `user_agent`, `device_id`, `device_type`, `auth_method`, `auth_provider`, `security_level`, `name_key`, `is_named`, `is_authenticated`, `is_active`, `is_expired`, `is_revoked`, `session_data`, `system_context`, `metadata`, `login_ymdhis`, `last_seen_ymdhis`, `expires_ymdhis`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`) VALUES ('msib03k38jd25n4ivj48j304nv', 1, 10000, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36 Edg/145.0.0.0', NULL, 'desktop', 'password', 'local', 'high', NULL, 0, 0, 1, 0, 0, NULL, NULL, NULL, 20260224143147, 20260224143159, 20260225143147, 20260224143147, 20260224143159, 0, NULL);

-- -----------------------------------------------------------------------------
-- Actor/agent doctrine: ALTER lupo_actors AUTO_INCREMENT = 10000
-- -----------------------------------------------------------------------------
ALTER TABLE lupo_actors AUTO_INCREMENT = 10000;

-- =============================================================================
-- END SEED
-- =============================================================================
