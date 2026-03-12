-- SQL Seed: Default Sessions (Model A). Optional; run during install.
-- Model A: session_id, actor_id, federation_node_id, ip_hash, ua_hash, csrf_token, last_activity_ymdhis, created_ymdhis, updated_ymdhis, name_key, is_named, metadata.
-- Placeholder session rows for system actors; browsers create sessions on demand. No is_active, is_expired, is_revoked.

INSERT INTO lupo_sessions (session_id, actor_id, federation_node_id, ip_hash, ua_hash, csrf_token, last_activity_ymdhis, created_ymdhis, updated_ymdhis, name_key, is_named, metadata)
VALUES
    ('L-lupo-0-00000000-0000-0000-0000-000000000000', 0, 0, NULL, NULL, NULL, 20260301134200, 20260301134200, 20260301134200, NULL, 0, '{"session_type":"default","created_by":"system_init"}')
ON DUPLICATE KEY UPDATE
    last_activity_ymdhis = VALUES(last_activity_ymdhis),
    updated_ymdhis = VALUES(updated_ymdhis);
