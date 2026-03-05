-- Seed: docs/status and docs/doctrine URLs (Option A — DB-seeded web docs) (4.0.57)
-- Ensures resolver Tier 1 finds rows for key status/doctrine docs so they render without Tier-3-only path.
-- Run after install_new_lupopedia.sql; idempotent via ON DUPLICATE KEY UPDATE.
-- federation_node_id = 0 (www.lupopedia.com = main site).

SET @now = 20260304120000;

-- docs/status/CURSOR_URL_TO_NODE_TRACE_4.0.57
INSERT INTO lupo_contents (
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
INSERT INTO lupo_contents (
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
