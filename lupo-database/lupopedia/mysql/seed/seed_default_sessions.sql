-- SQL Seed: Default Sessions for Key Actors (0-5,19,1000-1007)
-- Run during install; uses placeholder UUIDs—gen real in PHP.
-- Assumes lupo_sessions table exists (no status column; use is_active, is_expired, is_revoked).

INSERT INTO lupo_sessions (session_id, actor_id, federation_node_id, last_seen_ymdhis, metadata, created_ymdhis, updated_ymdhis, is_active, is_expired, is_revoked, is_deleted)
VALUES
    ('L-lupo-0-00000000-0000-0000-0000-000000000000', 0, 0, 20260301134200, '{"session_type":"default","created_by":"system_init","actor_type":"system_agent","prefix_enforced":true}', 20260301134200, 20260301134200, 1, 0, 0, 0),
    ('L-lupo-1-00000000-0000-0000-0000-000000000000', 1, 0, 20260301134200, '{"session_type":"default","created_by":"system_init","actor_type":"leadership_agent","prefix_enforced":true}', 20260301134200, 20260301134200, 1, 0, 0, 0),
    ('L-lupo-2-00000000-0000-0000-0000-000000000000', 2, 0, 20260301134200, '{"session_type":"default","created_by":"system_init","actor_type":"reviewer_agent","prefix_enforced":true}', 20260301134200, 20260301134200, 1, 0, 0, 0),
    ('L-lupo-3-00000000-0000-0000-0000-000000000000', 3, 0, 20260301134200, '{"session_type":"default","created_by":"system_init","actor_type":"emotional_agent","prefix_enforced":true}', 20260301134200, 20260301134200, 1, 0, 0, 0),
    ('L-lupo-4-00000000-0000-0000-0000-000000000000', 4, 0, 20260301134200, '{"session_type":"default","created_by":"system_init","actor_type":"chaos_agent","prefix_enforced":true}', 20260301134200, 20260301134200, 1, 0, 0, 0),
    ('L-lupo-5-00000000-0000-0000-0000-000000000000', 5, 0, 20260301134200, '{"session_type":"default","created_by":"system_init","actor_type":"metis_agent","prefix_enforced":true}', 20260301134200, 20260301134200, 1, 0, 0, 0),
    ('L-lupo-19-00000000-0000-0000-0000-000000000000', 19, 0, 20260301134200, '{"session_type":"default","created_by":"system_init","actor_type":"custodial_agent","prefix_enforced":true}', 20260301134200, 20260301134200, 1, 0, 0, 0),
    ('L-lupo-1000-00000000-0000-0000-0000-000000000000', 1000, 0, 20260301134200, '{"session_type":"default","created_by":"system_init","actor_type":"ide_agent","prefix_enforced":true}', 20260301134200, 20260301134200, 1, 0, 0, 0),
    ('L-lupo-1001-00000000-0000-0000-0000-000000000000', 1001, 0, 20260301134200, '{"session_type":"default","created_by":"system_init","actor_type":"ide_agent","prefix_enforced":true}', 20260301134200, 20260301134200, 1, 0, 0, 0),
    ('L-lupo-1002-00000000-0000-0000-0000-000000000000', 1002, 0, 20260301134200, '{"session_type":"default","created_by":"system_init","actor_type":"ide_agent","prefix_enforced":true}', 20260301134200, 20260301134200, 1, 0, 0, 0),
    ('L-lupo-1003-00000000-0000-0000-0000-000000000000', 1003, 0, 20260301134200, '{"session_type":"default","created_by":"system_init","actor_type":"ide_agent","prefix_enforced":true}', 20260301134200, 20260301134200, 1, 0, 0, 0),
    ('L-lupo-1004-00000000-0000-0000-0000-000000000000', 1004, 0, 20260301134200, '{"session_type":"default","created_by":"system_init","actor_type":"ide_agent","prefix_enforced":true}', 20260301134200, 20260301134200, 1, 0, 0, 0),
    ('L-lupo-1005-00000000-0000-0000-0000-000000000000', 1005, 0, 20260301134200, '{"session_type":"default","created_by":"system_init","actor_type":"ide_agent","prefix_enforced":true}', 20260301134200, 20260301134200, 1, 0, 0, 0),
    ('L-lupo-1007-00000000-0000-0000-0000-000000000000', 1007, 0, 20260301134200, '{"session_type":"default","created_by":"system_init","actor_type":"ide_agent","prefix_enforced":true}', 20260301134200, 20260301134200, 1, 0, 0, 0)
ON DUPLICATE KEY UPDATE
    last_seen_ymdhis = VALUES(last_seen_ymdhis),
    metadata = VALUES(metadata),
    updated_ymdhis = VALUES(updated_ymdhis),
    is_active = 1,
    is_expired = 0,
    is_revoked = 0;
