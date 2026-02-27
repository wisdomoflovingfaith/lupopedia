-- ============================================================
-- ACTOR IDENTITY CAPSULE SEEDING (v4.0.48)
-- ============================================================
-- Filesystem-to-Database Mapping for Actor Directory System

-- NOTE: This seeding logic reads from actors/ directory as source of truth
-- Installation wizard (PHP) can override these values during setup

-- Insert System Kernel (actor_id: 0)
INSERT INTO lupo_actors (
    actor_id, actor_type, slug, name, created_ymdhis, updated_ymdhis,
    is_active, is_deleted, is_kernel, can_login, is_agent,
    actor_root_path, who_json_sync_status
) VALUES (
    0, 'system', 'system-kernel', 'System', 20260101000000, 20260227000000,
    1, 0, 1, 0, 0,
    'actors/0', 'synced'
);

-- Insert Captain Wolfie (actor_id: 10000) - Human with Auth
INSERT INTO lupo_actors (
    actor_id, actor_type, slug, name, created_ymdhis, updated_ymdhis,
    is_active, is_deleted, is_kernel, can_login, is_agent,
    metadata_json, actor_root_path, who_json_sync_status
) VALUES (
    10000, 'human', 'root-captain-10000', 'Eric Robin Gerdes', 20260101000000, 20260227000000,
    1, 0, 0, 1, 0,
    '{"whoami":{"identity":"human","role":"root-captain-10000","persona":"The root-captain-10000 actor in the Lupopedia system.","capabilities":["basic_operations"],"status":"active"}}',
    'actors/10000', 'synced'
);

-- Insert Captain Wolfie into Auth Users (human actors get auth entries)
INSERT INTO lupo_auth_users (
    user_id, actor_id, email, provider, display_name, created_ymdhis, updated_ymdhis,
    is_active, is_deleted, email_verified
) VALUES (
    10000, 10000, 'wisdomoflovingfaith@gmail.com', 'google', 'Wolfie', 20260101000000, 20260227000000,
    1, 0, 1
);

-- Insert IDE Agents (actor_id: 1000-1006) - Agents with LLM configs
INSERT INTO lupo_actors (
    actor_id, actor_type, slug, name, created_ymdhis, updated_ymdhis,
    is_active, is_deleted, is_kernel, can_login, is_agent,
    metadata_json, actor_root_path, who_json_sync_status
) VALUES 
(1000, 'agent', 'kiro-ide', 'Kiro IDE', 20260101000000, 20260227000000,
 1, 0, 0, 0, 1,
 '{"whoami":{"identity":"agent","role":"kiro-ide","persona":"The kiro-ide actor in the Lupopedia system.","capabilities":["basic_operations"],"status":"active"}}',
 'actors/1000', 'synced'),
(1001, 'agent', 'windsurf-ide', 'Windsurf IDE', 20260101000000, 20260227000000,
 1, 0, 0, 0, 1,
 '{"whoami":{"identity":"agent","role":"windsurf-ide","persona":"The windsurf-ide actor in the Lupopedia system.","capabilities":["basic_operations"],"status":"active"}}',
 'actors/1001', 'synced'),
(1002, 'agent', 'cursor-ide', 'Cursor IDE', 20260101000000, 20260227000000,
 1, 0, 0, 0, 1,
 '{"whoami":{"identity":"agent","role":"cursor-ide","persona":"The cursor-ide actor in the Lupopedia system.","capabilities":["basic_operations"],"status":"active"}}',
 'actors/1002', 'synced'),
(1003, 'agent', 'antigravity-ide', 'Antigravity IDE', 20260101000000, 20260227000000,
 1, 0, 0, 0, 1,
 '{"whoami":{"identity":"agent","role":"antigravity-ide","persona":"The antigravity-ide actor in the Lupopedia system.","capabilities":["basic_operations"],"status":"active"}}',
 'actors/1003', 'synced'),
(1004, 'agent', 'warp-ide', 'Warp IDE', 20260101000000, 20260227000000,
 1, 0, 0, 0, 1,
 '{"whoami":{"identity":"agent","role":"warp-ide","persona":"The warp-ide actor in the Lupopedia system.","capabilities":["basic_operations"],"status":"active"}}',
 'actors/1004', 'synced'),
(1005, 'agent', 'cascade-ide', 'Cascade IDE', 20260101000000, 20260227000000,
 1, 0, 0, 0, 1,
 '{"whoami":{"identity":"agent","role":"cascade-ide","persona":"The cascade-ide actor in the Lupopedia system.","capabilities":["basic_operations"],"status":"active"}}',
 'actors/1005', 'synced'),
(1006, 'agent', 'gemini-cli', 'Gemini CLI', 20260101000000, 20260227000000,
 1, 0, 0, 0, 1,
 '{"whoami":{"identity":"agent","role":"gemini-cli","persona":"The gemini-cli actor in the Lupopedia system.","capabilities":["basic_operations"],"status":"active"}}',
 'actors/1006', 'synced');

-- Insert Agent Configurations (agent actors get lupo_agents entries)
INSERT INTO lupo_agents (
    agent_id, agent_key, agent_name, archetype, description, version,
    created_ymdhis, updated_ymdhis, is_deleted, provider
) VALUES 
(1000, 'kiro-ide', 'Kiro IDE', 'Installation', 'Installation and verification specialist', '4.0.47',
 20260101000000, 20260227000000, 0, 'internal'),
(1001, 'windsurf-ide', 'Windsurf IDE', 'File Operations', 'File operations and validation specialist', '4.0.47',
 20260101000000, 20260227000000, 0, 'internal'),
(1002, 'cursor-ide', 'Cursor IDE', 'Code Generation', 'Code generation and debugging specialist', '4.0.47',
 20260101000000, 20260227000000, 0, 'internal'),
(1003, 'antigravity-ide', 'Antigravity IDE', 'Token Constrained', 'Token-constrained specialist', '4.0.47',
 20260101000000, 20260227000000, 0, 'internal'),
(1004, 'warp-ide', 'Warp IDE', 'Terminal', 'Terminal and command-line specialist', '4.0.47',
 20260101000000, 20260227000000, 0, 'internal'),
(1005, 'cascade-ide', 'Cascade IDE', 'Coordination', 'Multi-agent coordination specialist', '4.0.47',
 20260101000000, 20260227000000, 0, 'internal'),
(1006, 'gemini-cli', 'Gemini CLI', 'CLI Interface', 'Command-line interface specialist', '4.0.47',
 20260101000000, 20260227000000, 0, 'google');

-- Insert Legacy AI Agents (actor_id: 1-5, 19, 25, 420)
INSERT INTO lupo_actors (
    actor_id, actor_type, slug, name, created_ymdhis, updated_ymdhis,
    is_active, is_deleted, is_kernel, can_login, is_agent,
    metadata_json, actor_root_path, who_json_sync_status
) VALUES 
(1, 'agent', 'captain-wolfie', 'Captain Wolfie Agent', 20260101000000, 20260227000000,
 1, 0, 0, 0, 1,
 '{"whoami":{"identity":"agent","role":"captain-wolfie","persona":"The captain-wolfie actor in the Lupopedia system.","capabilities":["basic_operations"],"status":"active"}}',
 'actors/1', 'synced'),
(2, 'agent', 'lilith', 'Lilith Agent', 20260101000000, 20260227000000,
 1, 0, 0, 0, 1,
 '{"whoami":{"identity":"agent","role":"lilith","persona":"The lilith actor in the Lupopedia system.","capabilities":["basic_operations"],"status":"active"}}',
 'actors/2', 'synced'),
(3, 'agent', 'rose-dialog', 'Rose Dialog Agent', 20260101000000, 20260227000000,
 1, 0, 0, 0, 1,
 '{"whoami":{"identity":"agent","role":"rose-dialog","persona":"The rose-dialog actor in the Lupopedia system.","capabilities":["basic_operations"],"status":"active"}}',
 'actors/3', 'synced'),
(4, 'agent', 'eris', 'Eris Agent', 20260101000000, 20260227000000,
 1, 0, 0, 0, 1,
 '{"whoami":{"identity":"agent","role":"eris","persona":"The eris actor in the Lupopedia system.","capabilities":["basic_operations"],"status":"active"}}',
 'actors/4', 'synced'),
(5, 'agent', 'metis', 'Metis Agent', 20260101000000, 20260227000000,
 1, 0, 0, 0, 1,
 '{"whoami":{"identity":"agent","role":"metis","persona":"The metis actor in the Lupopedia system.","capabilities":["basic_operations"],"status":"active"}}',
 'actors/5', 'synced'),
(19, 'agent', 'anubis', 'Anubis Agent', 20260101000000, 20260227000000,
 1, 0, 0, 0, 1,
 '{"whoami":{"identity":"agent","role":"anubis","persona":"The anubis actor in the Lupopedia system.","capabilities":["basic_operations"],"status":"active"}}',
 'actors/19', 'synced'),
(25, 'agent', 'vishwakarma', 'Vishwakarma Agent', 20260101000000, 20260227000000,
 1, 0, 0, 0, 1,
 '{"whoami":{"identity":"agent","role":"vishwakarma","persona":"The vishwakarma actor in the Lupopedia system.","capabilities":["basic_operations"],"status":"active"}}',
 'actors/25', 'synced'),
(420, 'agent', 'stoned-wolfie', 'Stoned Wolfie Agent', 20260101000000, 20260227000000,
 1, 0, 0, 0, 1,
 '{"whoami":{"identity":"agent","role":"stoned-wolfie","persona":"The stoned-wolfie actor in the Lupopedia system.","capabilities":["basic_operations"],"status":"active"}}',
 'actors/420', 'synced');

-- Insert Test Banned User (actor_id: 10420) - Human with banned status
INSERT INTO lupo_actors (
    actor_id, actor_type, slug, name, created_ymdhis, updated_ymdhis,
    is_active, is_deleted, is_kernel, can_login, is_agent,
    metadata_json, actor_root_path, who_json_sync_status
) VALUES (
    10420, 'human', 'test-banned-user', 'Test Banned User', 20260101000000, 20260227000000,
    0, 0, 0, 0, 0,
    '{"whoami":{"identity":"human","role":"test-banned-user","persona":"The test-banned-user actor in the Lupopedia system.","capabilities":["basic_operations"],"status":"active"}}',
    'actors/10420', 'synced'
);

-- Sample Actor History Entry (from actors/1005/history/resume.json)
INSERT INTO lupo_actor_history (
    history_id, actor_id, achievement_id, title, description, impact,
    date_ymdhis, channel_id, tags, metrics, created_ymdhis, updated_ymdhis
) VALUES (
    1, 1005, 'A47-001', 'WHO.json Implementation', 
    'Created comprehensive identity documentation for all 8 actors in the registry',
    'Established semantic OS actor foundation with IDE-specific personas, LLM modules, and capability mapping',
    20260227062000, 42,
    '["identity", "semantic_os", "documentation", "actor_directory"]',
    '{"actors_enhanced": 8, "identity_fields_created": 48, "llm_modules_mapped": 15, "capabilities_defined": 32}',
    20260227062000, 20260227062000
);

-- ============================================================
-- NOTE: Installation Wizard Override Capability
-- ============================================================
-- The PHP installation wizard can modify these seeded values:
-- 1. Captain Wolfie's identity (name, email, provider)
-- 2. Default actor configurations
-- 3. Initial relationship mappings
-- 4. System-wide settings
-- 
-- All values above serve as defaults based on filesystem structure
-- ============================================================
