-- Seed: /flare_apply URL route → docs/doctrine/FLARE/FLARE_APPLY.md (4.0.57)
-- Ensures http://www.lupopedia.com/flare_apply resolves to FLARE Apply tool documentation.
-- Run after install_new_lupopedia.sql; idempotent via ON DUPLICATE KEY UPDATE.

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
    2999,
    NULL,
    1,
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
    custom_path = VALUES(custom_path),
    file_path_from_root = VALUES(file_path_from_root),
    title = VALUES(title),
    updated_ymdhis = VALUES(updated_ymdhis),
    file_last_modified_system_version = VALUES(file_last_modified_system_version),
    is_deleted = 0,
    is_active = 1;
