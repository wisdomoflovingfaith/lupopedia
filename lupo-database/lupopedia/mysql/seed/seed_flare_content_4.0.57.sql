-- Seed: /FLARE → content row so content_handle_slug('flare') serves FLARE doc (4.0.57)
-- URL http://www.lupopedia.com/FLARE → slug lowercased to 'flare' → content by slug (no resolver).
-- Run after install_new_lupopedia.sql; idempotent via ON DUPLICATE KEY UPDATE.
-- federation_node_id = 0 (www.lupopedia.com = main site).

SET @now = 20260304120000;

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
