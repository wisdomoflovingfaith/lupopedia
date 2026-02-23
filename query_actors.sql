-- Query all actors to find IDE agents
-- Run this in MySQL/phpMyAdmin
-- X-Lupo-Forwarded: 1001:10000
-- Version: 4.0.31

-- All actors
SELECT 
    actor_id,
    actor_type,
    slug,
    name,
    created_ymdhis,
    updated_ymdhis,
    is_active
FROM lupo_actors
WHERE is_deleted = 0
ORDER BY actor_id;

-- Activity today (2026-02-23)
SELECT 
    actor_id,
    name,
    updated_ymdhis
FROM lupo_actors
WHERE updated_ymdhis >= 20260223000000
  AND updated_ymdhis < 20260224000000
  AND is_deleted = 0
ORDER BY updated_ymdhis DESC;

-- All AI agents
SELECT 
    a.agent_id,
    a.agent_key,
    a.agent_name,
    a.updated_ymdhis
FROM lupo_agents a
WHERE a.is_deleted = 0
ORDER BY a.agent_id;

-- Check Channel 42
SELECT * FROM lupo_channels WHERE channel_id = 42 OR channel_name LIKE '%42%';
