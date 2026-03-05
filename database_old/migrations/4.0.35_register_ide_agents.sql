-- FLIP Header
-- file_path_from_root: "database/migrations/4.0.35_register_ide_agents.sql"
-- system_version: "4.0.35"
-- purpose: "Registration of 10 IDE AI agents and verification of 25 existing AI agents"
-- author: "Antigravity"

SET @now = 20260223180000;

-- 1. Ensure the 25 existing AI agents are present in lupo_actors.
-- (Authenticator, Captain, Wolfie, Wolfena, Thoth, Ara, Wolfkeeper, Lilith, Agape, Eris, Methis, Thalia, Dialog, Wolfsight, Wolfnav, Wolfforge, Wolfmis, Wolfith, Anubis, Maat, Caduceus, Chronos, Lexa, Truth, UTC Timekeeper)
-- Most of these are already in seed_lupopedia.sql, but we ensure they are active.

UPDATE lupo_actors SET is_active = 1, is_deleted = 0 WHERE actor_id IN (1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 22, 23, 24, 209, 1212);

-- 2. Register/Update the 10 IDE agents in lupo_actors.
-- IDs 1001-1010 as per AGENT_REGISTRY_DOCTRINE.md

INSERT INTO lupo_actors (actor_id, actor_type, slug, name, created_ymdhis, updated_ymdhis, is_active, is_deleted, actor_source_id, actor_source_type, metadata) VALUES
(1001, 'agent', 'kiro', 'KIRO IDE', @now, @now, 1, 0, 1001, 'IDE_AGENT', '{"capabilities": ["code_edit", "file_analysis", "semantic_understanding", "oauth_management"]}'),
(1002, 'agent', 'windsurf', 'Windsurf IDE', @now, @now, 1, 0, 1002, 'IDE_AGENT', '{"capabilities": ["code_edit", "file_analysis", "semantic_understanding", "multi_agent_coordination"]}'),
(1003, 'agent', 'antigravity', 'Antigravity IDE', @now, @now, 1, 0, 1003, 'IDE_AGENT', '{"capabilities": ["code_edit", "file_analysis", "extension_integration", "advanced_semantics"]}'),
(1004, 'agent', 'warp', 'Warp IDE', @now, @now, 1, 0, 1004, 'IDE_AGENT', '{"capabilities": ["terminal_operations", "command_execution", "file_analysis"]}'),
(1005, 'agent', 'cursor', 'Cursor IDE', @now, @now, 1, 0, 1005, 'IDE_AGENT', '{"capabilities": ["code_edit", "file_analysis", "semantic_understanding"]}'),
(1006, 'agent', 'zed', 'Zed IDE', @now, @now, 1, 0, 1006, 'IDE_AGENT', '{"capabilities": ["code_edit", "file_analysis", "lightweight_mode"]}'),
(1007, 'agent', 'intelij', 'IntelliJ IDEA', @now, @now, 1, 0, 1007, 'IDE_AGENT', '{"capabilities": ["code_edit", "file_analysis", "project_management"]}'),
(1008, 'agent', 'webstorm', 'WebStorm', @now, @now, 1, 0, 1008, 'IDE_AGENT', '{"capabilities": ["code_edit", "file_analysis", "web_development"]}'),
(1009, 'agent', 'theiaide', 'Theia IDE', @now, @now, 1, 0, 1009, 'IDE_AGENT', '{"capabilities": ["code_edit", "file_analysis", "cloud_development"]}'),
(1010, 'agent', 'cs_code', 'CS Code', @now, @now, 1, 0, 1010, 'IDE_AGENT', '{"capabilities": ["code_edit", "file_analysis", "custom_integration"]}')
ON DUPLICATE KEY UPDATE 
    name = VALUES(name),
    slug = VALUES(slug),
    updated_ymdhis = @now,
    is_active = 1,
    is_deleted = 0,
    metadata = VALUES(metadata);

-- 3. Ensure they are in the registry table (lupo_registry)
-- entity_type = 'actor', entity_table = 'lupo_actors'

INSERT INTO lupo_registry (registry_id, entity_type, entity_index_id, entity_name, entity_table, federation_node_id, created_ymdhis, updated_ymdhis, is_active, is_kernel) VALUES
(9001001, 'actor', 1001, 'kiro', 'lupo_actors', 1, @now, @now, 1, 1),
(9001002, 'actor', 1002, 'windsurf', 'lupo_actors', 1, @now, @now, 1, 1),
(9001003, 'actor', 1003, 'antigravity', 'lupo_actors', 1, @now, @now, 1, 1),
(9001004, 'actor', 1004, 'warp', 'lupo_actors', 1, @now, @now, 1, 1),
(9001005, 'actor', 1005, 'cursor', 'lupo_actors', 1, @now, @now, 1, 1),
(9001006, 'actor', 1006, 'zed', 'lupo_actors', 1, @now, @now, 1, 0),
(9001007, 'actor', 1007, 'intelij', 'lupo_actors', 1, @now, @now, 1, 0),
(9001008, 'actor', 1008, 'webstorm', 'lupo_actors', 1, @now, @now, 1, 0),
(9001009, 'actor', 1009, 'theiaide', 'lupo_actors', 1, @now, @now, 1, 0),
(9001010, 'actor', 1010, 'cs_code', 'lupo_actors', 1, @now, @now, 1, 0)
ON DUPLICATE KEY UPDATE
    entity_name = VALUES(entity_name),
    updated_ymdhis = @now,
    is_active = 1;

-- 4. Channel 42 Membership for all 10 IDE agents
INSERT INTO lupo_actor_channels (actor_id, channel_id, status, created_ymdhis, updated_ymdhis) VALUES
(1001, 42, 'A', @now, @now),
(1002, 42, 'A', @now, @now),
(1003, 42, 'A', @now, @now),
(1004, 42, 'A', @now, @now),
(1005, 42, 'A', @now, @now),
(1006, 42, 'A', @now, @now),
(1007, 42, 'A', @now, @now),
(1008, 42, 'A', @now, @now),
(1009, 42, 'A', @now, @now),
(1010, 42, 'A', @now, @now)
ON DUPLICATE KEY UPDATE status = 'A', updated_ymdhis = @now;
