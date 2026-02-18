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
    'docs/doctrine/FLIP/FLP_COUNCILS_AS_CHANNELS.md', '4.0.13', 0, NULL, NULL
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
    'docs/doctrine/FLIP/FLP_DOCTRINE_BOUNDARIES.md', '4.0.13', 0, NULL, NULL
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
    'docs/doctrine/FLIP/FLP_EMOTIONAL_AGGREGATION.md', '4.0.13', 0, NULL, NULL
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
    'docs/doctrine/FLIP/FLP_EMOTIONAL_GEOMETRY.md', '4.0.13', 0, NULL, NULL
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
    'docs/doctrine/FLIP/FLP_ESCROW_AND_FUND_LAYER.md', '4.0.13', 0, NULL, NULL
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
    'docs/doctrine/FLIP/FLP_HETERODOX_REVIEWERS.md', '4.0.13', 0, NULL, NULL
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
    'docs/doctrine/FLIP/FLP_LUPOPEDIA_COUNCIL_SEAT.md', '4.0.13', 0, NULL, NULL
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
    'docs/doctrine/FLIP/FLP_OVERVIEW.md', '4.0.13', 0, NULL, NULL
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
    'docs/doctrine/FLIP/NOTE_HEADER_VERSION_AND_MERGE.md', '4.0.13', 0, NULL, NULL
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
    'docs/doctrine/FLIP/README.md', '4.0.13', 0, NULL, NULL
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
