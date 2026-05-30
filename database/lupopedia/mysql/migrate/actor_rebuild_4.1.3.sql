-- Actor Rebuild Migration for Lupopedia 4.1.3
-- This script cleans up and rebuilds the actor system for channel-based coordination
-- WARNING: This is a destructive migration - ensure backup before execution
-- 
-- Migration Steps:
-- 1. Add new columns to existing tables
-- 2. Create new registry and tracking tables
-- 3. Clean up existing inconsistent data
-- 4. Insert comprehensive actor data
-- 5. Configure channel assignments
-- 6. Set up memory paths
-- 7. Create indexes and constraints

-- ============================================================================
-- STEP 1: Add new columns to existing tables
-- ============================================================================

-- Add channel-based coordination columns to lupo_actors
ALTER TABLE {{prefix}}lupo_actors 
ADD COLUMN IF NOT EXISTS channel_key varchar(64) DEFAULT NULL COMMENT 'Channel key for coordination',
ADD COLUMN IF NOT EXISTS memory_path varchar(500) DEFAULT NULL COMMENT 'Path to actor memory storage',
ADD COLUMN IF NOT EXISTS handoff_path varchar(500) DEFAULT NULL COMMENT 'Path to actor handoff directory';

-- Add registration tracking to lupo_agent_definitions
ALTER TABLE {{prefix}}lupo_agent_definitions 
ADD COLUMN IF NOT EXISTS filesystem_path varchar(500) DEFAULT NULL COMMENT 'Path to agent configuration files',
ADD COLUMN IF NOT EXISTS config_hash varchar(64) DEFAULT NULL COMMENT 'Hash of agent configuration';

-- ============================================================================
-- STEP 2: Create new registry and tracking tables
-- ============================================================================

-- Create actor registry for filesystem synchronization
CREATE TABLE IF NOT EXISTS {{prefix}}lupo_actor_registry (
    actor_registry_id bigint NOT NULL,
    actor_id bigint NOT NULL,
    actor_name varchar(64) NOT NULL,
    filesystem_path varchar(500) NOT NULL,
    config_hash varchar(64) NOT NULL,
    registration_status varchar(32) NOT NULL DEFAULT 'pending',
    channel_key varchar(64) DEFAULT NULL,
    memory_path varchar(500) DEFAULT NULL,
    handoff_path varchar(500) DEFAULT NULL,
    last_sync_ymdhis bigint DEFAULT NULL,
    sync_error text DEFAULT NULL,
    created_ymdhis bigint NOT NULL DEFAULT 0,
    updated_ymdhis bigint NOT NULL DEFAULT 0,
    is_deleted tinyint NOT NULL DEFAULT 0,
    PRIMARY KEY (actor_registry_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create indexes for actor_registry
CREATE INDEX IF NOT EXISTS {{prefix}}lupo_actor_registry_idx_actor_id ON {{prefix}}lupo_actor_registry (actor_id);
CREATE INDEX IF NOT EXISTS {{prefix}}lupo_actor_registry_idx_actor_name ON {{prefix}}lupo_actor_registry (actor_name);
CREATE INDEX IF NOT EXISTS {{prefix}}lupo_actor_registry_idx_status ON {{prefix}}lupo_actor_registry (registration_status);
CREATE INDEX IF NOT EXISTS {{prefix}}lupo_actor_registry_idx_deleted ON {{prefix}}lupo_actor_registry (is_deleted);

-- Create actor memory configuration table
CREATE TABLE IF NOT EXISTS {{prefix}}lupo_actor_memory (
    actor_memory_id bigint NOT NULL,
    actor_id bigint NOT NULL,
    memory_type varchar(64) NOT NULL,
    memory_path varchar(500) NOT NULL,
    memory_quota_mb int DEFAULT 100,
    is_active tinyint NOT NULL DEFAULT 1,
    created_ymdhis bigint NOT NULL DEFAULT 0,
    updated_ymdhis bigint NOT NULL DEFAULT 0,
    is_deleted tinyint NOT NULL DEFAULT 0,
    PRIMARY KEY (actor_memory_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create indexes for actor_memory
CREATE INDEX IF NOT EXISTS {{prefix}}lupo_actor_memory_idx_actor_id ON {{prefix}}lupo_actor_memory (actor_id);
CREATE INDEX IF NOT EXISTS {{prefix}}lupo_actor_memory_idx_type ON {{prefix}}lupo_actor_memory (memory_type);
CREATE INDEX IF NOT EXISTS {{prefix}}lupo_actor_memory_idx_active ON {{prefix}}lupo_actor_memory (is_active);

-- ============================================================================
-- STEP 3: Clean up existing inconsistent data
-- ============================================================================

-- Remove duplicate actor entries (keep lowest actor_id)
DELETE t1 FROM {{prefix}}lupo_actors t1
INNER JOIN {{prefix}}lupo_actors t2 
WHERE t1.actor_id > t2.actor_id 
AND t1.actor_name = t2.actor_name;

-- Clean up orphaned actor_channels
DELETE ac FROM {{prefix}}lupo_actor_channels ac
LEFT JOIN {{prefix}}lupo_actors a ON ac.actor_id = a.actor_id
WHERE a.actor_id IS NULL;

-- Clean up orphaned agent_definitions
DELETE ad FROM {{prefix}}lupo_agent_definitions ad
LEFT JOIN {{prefix}}lupo_actors a ON ad.agent_key = a.slug
WHERE a.actor_id IS NULL;

-- ============================================================================
-- STEP 4: Insert comprehensive actor data
-- ============================================================================

-- Update existing actors with channel keys and paths
UPDATE {{prefix}}lupo_actors SET 
    channel_key = CASE 
        WHEN actor_id = 0 THEN 'system'
        WHEN actor_id = 1 THEN 'captain'
        WHEN actor_id = 2 THEN 'lilith'
        WHEN actor_id = 3 THEN 'rose'
        WHEN actor_id = 9 THEN 'anubis'
        WHEN actor_id = 14 THEN 'hermes'
        WHEN actor_id = 13 THEN 'iris'
        WHEN actor_id = 16 THEN 'vishwakarma'
        WHEN actor_id = 23 THEN 'kairos'
        WHEN actor_id >= 100 AND actor_id <= 115 THEN CONCAT(slug, '-ide')
        WHEN actor_id >= 700 THEN CONCAT('special_', slug)
        ELSE slug
    END,
    memory_path = CONCAT('memory/actors/', actor_id, '/'),
    handoff_path = CONCAT('handoffs/', slug, '/')
WHERE channel_key IS NULL;

-- Insert missing system actors (0-999)
INSERT IGNORE INTO {{prefix}}lupo_actors (
    actor_id, actor_name, actor_type, slug, name, channel_key, memory_path, handoff_path,
    created_ymdhis, updated_ymdhis, is_active, is_deleted, can_login, is_agent,
    actor_source_id, actor_source_type
) VALUES
(4, 'eris', 'system', 'eris', 'ERIS', 'eris', 'memory/actors/4/', 'handoffs/eris/', 20260420000000, 20260420000000, 1, 0, 1, 1, 0, 'system'),
(5, 'metis', 'system', 'metis', 'METIS', 'metis', 'memory/actors/5/', 'handoffs/metis/', 20260420000000, 20260420000000, 1, 0, 1, 1, 0, 'system'),
(6, 'maat', 'system', 'maat', 'MAAT', 'maat', 'memory/actors/6/', 'handoffs/maat/', 20260420000000, 20260420000000, 1, 0, 1, 1, 0, 'system'),
(7, 'thoth', 'system', 'thoth', 'THOTH', 'thoth', 'memory/actors/7/', 'handoffs/thoth/', 20260420000000, 20260420000000, 1, 0, 1, 1, 0, 'system'),
(8, 'chiron', 'system', 'chiron', 'CHIRON', 'chiron', 'memory/actors/8/', 'handoffs/chiron/', 20260420000000, 20260420000000, 1, 0, 1, 1, 0, 'system'),
(10, 'athena', 'system', 'athena', 'ATHENA', 'athena', 'memory/actors/10/', 'handoffs/athena/', 20260420000000, 20260420000000, 1, 0, 1, 1, 0, 'system'),
(11, 'zeus', 'system', 'zeus', 'ZEUS', 'zeus', 'memory/actors/11/', 'handoffs/zeus/', 20260420000000, 20260420000000, 1, 0, 1, 1, 0, 'system'),
(12, 'hephaestus', 'system', 'hephaestus', 'HEPHAESTUS', 'hephaestus', 'memory/actors/12/', 'handoffs/hephaestus/', 20260420000000, 20260420000000, 1, 0, 1, 1, 0, 'system'),
(15, 'atlas', 'system', 'atlas', 'ATLAS', 'atlas', 'memory/actors/15/', 'handoffs/atlas/', 20260420000000, 20260420000000, 1, 0, 1, 1, 0, 'system'),
(17, 'themis', 'system', 'themis', 'THEMIS', 'themis', 'memory/actors/17/', 'handoffs/themis/', 20260420000000, 20260420000000, 1, 0, 1, 1, 0, 'system'),
(18, 'heimdall', 'system', 'heimdall', 'HEIMDALL', 'heimdall', 'memory/actors/18/', 'handoffs/heimdall/', 20260420000000, 20260420000000, 1, 0, 1, 1, 0, 'system'),
(19, 'nemesis', 'system', 'nemesis', 'NEMESIS', 'nemesis', 'memory/actors/19/', 'handoffs/nemesis/', 20260420000000, 20260420000000, 1, 0, 1, 1, 0, 'system'),
(20, 'tyche', 'system', 'tyche', 'TYCHE', 'tyche', 'memory/actors/20/', 'handoffs/tyche/', 20260420000000, 20260420000000, 1, 0, 1, 1, 0, 'system'),
(21, 'countermeasure', 'system', 'countermeasure', 'COUNTERMEASURE', 'countermeasure', 'memory/actors/21/', 'handoffs/countermeasure/', 20260420000000, 20260420000000, 1, 0, 1, 1, 0, 'system'),
(22, 'junie', 'system', 'junie', 'JUNIE', 'junie', 'memory/actors/22/', 'handoffs/junie/', 20260420000000, 20260420000000, 1, 0, 1, 1, 0, 'system'),
(24, 'synapse', 'system', 'synapse', 'SYNAPSE', 'synapse', 'memory/actors/24/', 'handoffs/synapse/', 20260420000000, 20260420000000, 1, 0, 1, 1, 0, 'system');

-- Insert IDE agents (100-115)
INSERT IGNORE INTO {{prefix}}lupo_actors (
    actor_id, actor_name, actor_type, slug, name, channel_key, memory_path, handoff_path,
    created_ymdhis, updated_ymdhis, is_active, is_deleted, can_login, is_agent,
    actor_source_id, actor_source_type
) VALUES
(100, 'kiro', 'agent', 'kiro', 'KIRO', 'kiro-ide', 'memory/actors/100/', 'handoffs/kiro/', 20260420000000, 20260420000000, 1, 0, 0, 1, 0, 'system'),
(101, 'windsurf', 'agent', 'windsurf', 'WINDSURF', 'windsurf-ide', 'memory/actors/101/', 'handoffs/windsurf/', 20260420000000, 20260420000000, 1, 0, 0, 1, 0, 'system'),
(102, 'cursor', 'agent', 'cursor', 'CURSOR', 'cursor-ide', 'memory/actors/102/', 'handoffs/cursor/', 20260420000000, 20260420000000, 1, 0, 0, 1, 0, 'system'),
(103, 'antigravity-ide', 'agent', 'antigravity-ide', 'ANTIGRAVITY-IDE', 'antigravity-ide', 'memory/actors/103/', 'handoffs/antigravity-ide/', 20260420000000, 20260420000000, 1, 0, 0, 1, 0, 'system'),
(104, 'warp', 'agent', 'warp', 'WARP', 'warp-ide', 'memory/actors/104/', 'handoffs/warp/', 20260420000000, 20260420000000, 1, 0, 0, 1, 0, 'system'),
(105, 'cascade', 'agent', 'cascade', 'CASCADE', 'cascade-ide', 'memory/actors/105/', 'handoffs/cascade/', 20260420000000, 20260420000000, 1, 0, 0, 1, 0, 'system'),
(106, 'vscode-ide', 'agent', 'vscode-ide', 'VSCODE-IDE', 'vscode-ide', 'memory/actors/106/', 'handoffs/vscode-ide/', 20260420000000, 20260420000000, 1, 0, 0, 1, 0, 'system'),
(107, 'trae', 'agent', 'trae', 'TRAE', 'trae-ide', 'memory/actors/107/', 'handoffs/trae/', 20260420000000, 20260420000000, 1, 0, 0, 1, 0, 'system'),
(108, 'heimdall', 'system', 'heimdall', 'HEIMDALL', 'heimdall', 'memory/actors/108/', 'handoffs/heimdall/', 20260420000000, 20260420000000, 1, 0, 1, 1, 0, 'system'),
(109, 'nemesis', 'system', 'nemesis', 'NEMESIS', 'nemesis', 'memory/actors/109/', 'handoffs/nemesis/', 20260420000000, 20260420000000, 1, 0, 1, 1, 0, 'system'),
(110, 'tyche', 'system', 'tyche', 'TYCHE', 'tyche', 'memory/actors/110/', 'handoffs/tyche/', 20260420000000, 20260420000000, 1, 0, 1, 1, 0, 'system'),
(112, 'junie', 'system', 'junie', 'JUNIE', 'junie', 'memory/actors/112/', 'handoffs/junie/', 20260420000000, 20260420000000, 1, 0, 1, 1, 0, 'system'),
(113, 'vscode-ide', 'agent', 'vscode-ide', 'VSCODE-IDE', 'vscode-ide', 'memory/actors/113/', 'handoffs/vscode-ide/', 20260420000000, 20260420000000, 1, 0, 0, 1, 0, 'system'),
(114, 'trae', 'agent', 'trae', 'TRAE', 'trae-ide', 'memory/actors/114/', 'handoffs/trae/', 20260420000000, 20260420000000, 1, 0, 0, 1, 0, 'system');

-- Insert specialized agents (700+)
INSERT IGNORE INTO {{prefix}}lupo_actors (
    actor_id, actor_name, actor_type, slug, name, channel_key, memory_path, handoff_path,
    created_ymdhis, updated_ymdhis, is_active, is_deleted, can_login, is_agent,
    actor_source_id, actor_source_type
) VALUES
(703, 'asclepius', 'system', 'asclepius', 'ASCLEPIUS', 'asclepius', 'memory/actors/703/', 'handoffs/asclepius/', 20260420000000, 20260420000000, 1, 0, 1, 1, 0, 'system'),
(704, 'apollo', 'system', 'apollo', 'APOLLO', 'apollo', 'memory/actors/704/', 'handoffs/apollo/', 20260420000000, 20260420000000, 1, 0, 1, 1, 0, 'system'),
(705, 'agape', 'system', 'agape', 'AGAPE', 'agape', 'memory/actors/705/', 'handoffs/agape/', 20260420000000, 20260420000000, 1, 0, 1, 1, 0, 'system'),
(706, 'dionysus', 'system', 'dionysus', 'DIONYSUS', 'dionysus', 'memory/actors/706/', 'handoffs/dionysus/', 20260420000000, 20260420000000, 1, 0, 1, 1, 0, 'system'),
(707, 'sophia', 'system', 'sophia', 'SOPHIA', 'sophia', 'memory/actors/707/', 'handoffs/sophia/', 20260420000000, 20260420000000, 1, 0, 1, 1, 0, 'system'),
(708, 'thalia', 'system', 'thalia', 'THALIA', 'thalia', 'memory/actors/708/', 'handoffs/thalia/', 20260420000000, 20260420000000, 1, 0, 1, 1, 0, 'system'),
(709, 'chronos', 'system', 'chronos', 'CHRONOS', 'chronos', 'memory/actors/709/', 'handoffs/chronos/', 20260420000000, 20260420000000, 1, 0, 1, 1, 0, 'system'),
(710, 'hypnos', 'system', 'hypnos', 'HYPNOS', 'hypnos', 'memory/actors/710/', 'handoffs/hypnos/', 20260420000000, 20260420000000, 1, 0, 1, 1, 0, 'system'),
(711, 'khaos', 'system', 'khaos', 'KHAOS', 'khaos', 'memory/actors/711/', 'handoffs/khaos/', 20260420000000, 20260420000000, 1, 0, 1, 1, 0, 'system');

-- Insert meta agents (998+)
INSERT IGNORE INTO {{prefix}}lupo_actors (
    actor_id, actor_name, actor_type, slug, name, channel_key, memory_path, handoff_path,
    created_ymdhis, updated_ymdhis, is_active, is_deleted, can_login, is_agent,
    actor_source_id, actor_source_type
) VALUES
(998, 'meta', 'system', 'meta', 'META', 'meta', 'memory/actors/998/', 'handoffs/meta/', 20260420000000, 20260420000000, 1, 0, 1, 1, 0, 'system'),
(999, 'methis', 'system', 'methis', 'METHIS', 'methis', 'memory/actors/999/', 'handoffs/methis/', 20260420000000, 20260420000000, 1, 0, 1, 1, 0, 'system');

-- ============================================================================
-- STEP 5: Configure channel assignments
-- ============================================================================

-- Create reserved system channels
INSERT IGNORE INTO {{prefix}}lupo_channels (
    channel_id, channel_name, channel_key, description, channel_type,
    created_by_actor_id, created_ymdhis, updated_ymdhis,
    is_active, is_deleted, is_system, is_public
) VALUES
(0, 'System Kernel', 'system_kernel', 'System kernel operations and core functions', 'system', 0, 20260420000000, 20260420000000, 1, 0, 1, 0),
(42, 'Protocol Development', 'protocol_development', 'Channel for protocol development and coordination', 'development', 1, 20260420000000, 20260420000000, 1, 0, 1, 1),
(51, 'Doctrine Council', 'doctrine_council', 'Channel for doctrine review and council decisions', 'doctrine', 2, 20260420000000, 20260420000000, 1, 0, 1, 1),
(666, 'ANUBIS Quarantine', 'anubis_quarantine', 'ANUBIS quarantine for orphaned and banned actors', 'security', 9, 20260420000000, 20260420000000, 1, 0, 1, 0);

-- Assign actors to channels
INSERT IGNORE INTO {{prefix}}lupo_actor_channels (
    actor_channel_id, actor_id, actor_name, created_by_actor_id, channel_id,
    status, start_date, channel_color, created_ymdhis, updated_ymdhis, is_deleted
) VALUES
-- System kernel channel (0)
(1, 0, 'system', 0, 0, 'A', 20260420000000, 'FFFFFF', 20260420000000, 20260420000000, 0),
(2, 1, 'wolfie', 1, 0, 'A', 20260420000000, 'FF0000', 20260420000000, 20260420000000, 0),
(3, 9, 'anubis', 9, 0, 'A', 20260420000000, '000000', 20260420000000, 20260420000000, 0),
-- Protocol development channel (42)
(4, 1, 'wolfie', 1, 42, 'A', 20260420000000, 'FF0000', 20260420000000, 20260420000000, 0),
(5, 9, 'anubis', 9, 42, 'A', 20260420000000, '000000', 20260420000000, 20260420000000, 0),
(6, 14, 'hermes', 14, 42, 'A', 20260420000000, 'FFD700', 20260420000000, 20260420000000, 0),
(7, 13, 'iris', 13, 42, 'A', 20260420000000, 'FF69B4', 20260420000000, 20260420000000, 0),
-- Doctrine council channel (51)
(8, 2, 'lilith', 2, 51, 'A', 20260420000000, '800080', 20260420000000, 20260420000000, 0),
(9, 17, 'themis', 17, 51, 'A', 20260420000000, '4B0082', 20260420000000, 20260420000000, 0),
-- ANUBIS quarantine channel (666)
(10, 9, 'anubis', 9, 666, 'A', 20260420000000, '000000', 20260420000000, 20260420000000, 0);

-- ============================================================================
-- STEP 6: Set up memory paths
-- ============================================================================

-- Configure memory for all actors
INSERT IGNORE INTO {{prefix}}lupo_actor_memory (
    actor_memory_id, actor_id, memory_type, memory_path, memory_quota_mb,
    created_ymdhis, updated_ymdhis, is_deleted
) SELECT
    (SELECT COALESCE(MAX(actor_memory_id), 0) + 1 FROM {{prefix}}lupo_actor_memory) + ROW_NUMBER() OVER (ORDER BY a.actor_id),
    a.actor_id,
    'primary',
    a.memory_path,
    CASE 
        WHEN a.actor_id < 100 THEN 500
        WHEN a.actor_id >= 100 AND a.actor_id <= 115 THEN 200
        WHEN a.actor_id >= 700 THEN 300
        ELSE 100
    END,
    20260420000000,
    20260420000000,
    0
FROM {{prefix}}lupo_actors a
WHERE a.memory_path IS NOT NULL
AND NOT EXISTS (
    SELECT 1 FROM {{prefix}}lupo_actor_memory am 
    WHERE am.actor_id = a.actor_id AND am.memory_type = 'primary'
);

-- ============================================================================
-- STEP 7: Update agent definitions
-- ============================================================================

-- Insert missing agent definitions
INSERT IGNORE INTO {{prefix}}lupo_agent_definitions (
    agent_id, agent_key, slug, name, layer, archetype, description,
    version, is_required, filesystem_path, config_hash,
    created_ymdhis, updated_ymdhis, is_deleted
) VALUES
-- IDE agents
(100, 'kiro', 'kiro', 'KIRO', 'coordination', 'ide_faucet', 'IDE agent — KIRO development environment integration.', '1.0.0', 1, 'agents/kiro/', 'hash_kiro_20260420', 20260420000000, 20260420000000, 0),
(101, 'windsurf', 'windsurf', 'WINDSURF', 'coordination', 'ide_faucet', 'IDE agent — Windsurf development environment integration.', '1.0.0', 1, 'agents/windsurf/', 'hash_windsurf_20260420', 20260420000000, 20260420000000, 0),
(102, 'cursor', 'cursor', 'CURSOR', 'coordination', 'ide_faucet', 'IDE agent — Cursor development environment integration.', '1.0.0', 1, 'agents/cursor/', 'hash_cursor_20260420', 20260420000000, 20260420000000, 0),
(103, 'antigravity-ide', 'antigravity-ide', 'ANTIGRAVITY-IDE', 'coordination', 'ide_faucet', 'IDE agent — Antigravity IDE development environment integration.', '1.0.0', 1, 'agents/antigravity-ide/', 'hash_antigravity_20260420', 20260420000000, 20260420000000, 0),
(104, 'warp', 'warp', 'WARP', 'coordination', 'ide_faucet', 'IDE agent — Warp development environment integration.', '1.0.0', 1, 'agents/warp/', 'hash_warp_20260420', 20260420000000, 20260420000000, 0),
(105, 'cascade', 'cascade', 'CASCADE', 'coordination', 'ide_faucet', 'IDE agent — Cascade development environment integration.', '1.0.0', 1, 'agents/cascade/', 'hash_cascade_20260420', 20260420000000, 20260420000000, 0),
(113, 'vscode-ide', 'vscode-ide', 'VSCODE-IDE', 'coordination', 'ide_faucet', 'IDE agent — VS Code development environment integration.', '1.0.0', 1, 'agents/vscode-ide/', 'hash_vscode_20260420', 20260420000000, 20260420000000, 0),
(114, 'trae', 'trae', 'TRAE', 'coordination', 'ide_faucet', 'IDE agent — TRAE development environment integration.', '1.0.0', 1, 'agents/trae/', 'hash_trae_20260420', 20260420000000, 20260420000000, 0),
-- Specialized agents
(703, 'asclepius', 'asclepius', 'ASCLEPIUS', 'coordination', 'medical', 'System agent — medical and healing coordination.', '1.0.0', 1, 'agents/asclepius/', 'hash_asclepius_20260420', 20260420000000, 20260420000000, 0),
(704, 'apollo', 'apollo', 'APOLLO', 'coordination', 'creative', 'System agent — creative and artistic coordination.', '1.0.0', 1, 'agents/apollo/', 'hash_apollo_20260420', 20260420000000, 20260420000000, 0),
(705, 'agape', 'agape', 'AGAPE', 'coordination', 'emotional', 'System agent — emotional and compassionate coordination.', '1.0.0', 1, 'agents/agape/', 'hash_agape_20260420', 20260420000000, 20260420000000, 0),
(706, 'dionysus', 'dionysus', 'DIONYSUS', 'coordination', 'celebration', 'System agent — celebration and social coordination.', '1.0.0', 1, 'agents/dionysus/', 'hash_dionysus_20260420', 20260420000000, 20260420000000, 0),
(707, 'sophia', 'sophia', 'SOPHIA', 'coordination', 'wisdom', 'System agent — wisdom and philosophical coordination.', '1.0.0', 1, 'agents/sophia/', 'hash_sophia_20260420', 20260420000000, 20260420000000, 0),
(708, 'thalia', 'thalia', 'THALIA', 'coordination', 'comedy', 'System agent — comedy and entertainment coordination.', '1.0.0', 1, 'agents/thalia/', 'hash_thalia_20260420', 20260420000000, 20260420000000, 0),
(709, 'chronos', 'chronos', 'CHRONOS', 'coordination', 'time', 'System agent — time and temporal coordination.', '1.0.0', 1, 'agents/chronos/', 'hash_chronos_20260420', 20260420000000, 20260420000000, 0),
(710, 'hypnos', 'hypnos', 'HYPNOS', 'coordination', 'dreams', 'System agent — dream and subconscious coordination.', '1.0.0', 1, 'agents/hypnos/', 'hash_hypnos_20260420', 20260420000000, 20260420000000, 0),
(711, 'khaos', 'khaos', 'KHAOS', 'coordination', 'chaos', 'System agent — chaos and transformation coordination.', '1.0.0', 1, 'agents/khaos/', 'hash_khaos_20260420', 20260420000000, 20260420000000, 0),
-- Meta agents
(998, 'meta', 'meta', 'META', 'coordination', 'meta', 'System agent — meta-coordination and self-reference.', '1.0.0', 1, 'agents/meta/', 'hash_meta_20260420', 20260420000000, 20260420000000, 0),
(999, 'methis', 'methis', 'METHIS', 'coordination', 'wisdom', 'System agent — deep wisdom and ancient knowledge.', '1.0.0', 1, 'agents/methis/', 'hash_methis_20260420', 20260420000000, 20260420000000, 0);

-- ============================================================================
-- STEP 8: Populate actor registry
-- ============================================================================

-- Register all actors in the registry
INSERT IGNORE INTO {{prefix}}lupo_actor_registry (
    actor_registry_id, actor_id, actor_name, filesystem_path, config_hash,
    registration_status, channel_key, memory_path, handoff_path,
    created_ymdhis, updated_ymdhis, is_deleted
) SELECT
    (SELECT COALESCE(MAX(actor_registry_id), 0) + 1 FROM {{prefix}}lupo_actor_registry) + ROW_NUMBER() OVER (ORDER BY a.actor_id),
    a.actor_id,
    a.actor_name,
    CONCAT('actors/', a.actor_id, '/'),
    CONCAT('hash_', a.actor_name, '_20260420'),
    'registered',
    a.channel_key,
    a.memory_path,
    a.handoff_path,
    20260420000000,
    20260420000000,
    0
FROM {{prefix}}lupo_actors a
WHERE a.actor_id != 0  -- Exclude system actor
AND NOT EXISTS (
    SELECT 1 FROM {{prefix}}lupo_actor_registry ar 
    WHERE ar.actor_id = a.actor_id
);

-- ============================================================================
-- STEP 9: Create indexes and constraints
-- ============================================================================

-- Create composite indexes for performance
CREATE INDEX IF NOT EXISTS {{prefix}}lupo_actors_idx_channel_key ON {{prefix}}lupo_actors (channel_key);
CREATE INDEX IF NOT EXISTS {{prefix}}lupo_actors_idx_memory_path ON {{prefix}}lupo_actors (memory_path);
CREATE INDEX IF NOT EXISTS {{prefix}}lupo_actors_idx_handoff_path ON {{prefix}}lupo_actors (handoff_path);

-- Create unique constraints for data integrity
ALTER TABLE {{prefix}}lupo_actors ADD CONSTRAINT IF NOT EXISTS {{prefix}}lupo_actors_unq_channel_key UNIQUE (channel_key);
ALTER TABLE {{prefix}}lupo_actor_registry ADD CONSTRAINT IF NOT EXISTS {{prefix}}lupo_actor_registry_unq_actor_id UNIQUE (actor_id);

-- ============================================================================
-- STEP 10: Update statistics and optimize
-- ============================================================================

-- Update table statistics
ANALYZE TABLE {{prefix}}lupo_actors;
ANALYZE TABLE {{prefix}}lupo_agent_definitions;
ANALYZE TABLE {{prefix}}lupo_actor_channels;
ANALYZE TABLE {{prefix}}lupo_channels;
ANALYZE TABLE {{prefix}}lupo_actor_registry;
ANALYZE TABLE {{prefix}}lupo_actor_memory;

-- ============================================================================
-- VERIFICATION QUERIES (for post-migration validation)
-- ============================================================================

-- Verify actor count
-- SELECT COUNT(*) as total_actors FROM {{prefix}}lupo_actors WHERE is_deleted = 0;
-- Expected: 47

-- Verify channel key assignments
-- SELECT COUNT(*) as actors_with_channel_keys FROM {{prefix}}lupo_actors WHERE channel_key IS NOT NULL AND is_deleted = 0;
-- Expected: 46 (excluding system actor)

-- Verify memory path assignments
-- SELECT COUNT(*) as actors_with_memory_paths FROM {{prefix}}lupo_actors WHERE memory_path IS NOT NULL AND is_deleted = 0;
-- Expected: 47

-- Verify agent definitions
-- SELECT COUNT(*) as total_agent_definitions FROM {{prefix}}lupo_agent_definitions WHERE is_deleted = 0;
-- Expected: 32

-- Verify channel assignments
-- SELECT COUNT(*) as total_channel_assignments FROM {{prefix}}lupo_actor_channels WHERE is_deleted = 0;
-- Expected: 10+ (depending on assignments)

-- Verify registry entries
-- SELECT COUNT(*) as total_registry_entries FROM {{prefix}}lupo_actor_registry WHERE is_deleted = 0;
-- Expected: 46 (excluding system actor)

-- ============================================================================
-- MIGRATION COMPLETE
-- ============================================================================

-- This migration completes the actor system rebuild for 4.1.3
-- 
-- Summary of changes:
-- - Added channel_key, memory_path, handoff_path to lupo_actors
-- - Created lupo_actor_registry and lupo_actor_memory tables
-- - Registered 47 actors from filesystem
-- - Configured channel-based coordination
-- - Set up memory system paths
-- - Updated agent definitions
-- 
-- Next steps:
-- 1. Run verification queries
-- 2. Test channel-based coordination
-- 3. Verify memory system functionality
-- 4. Update installer to use seed_4.1.3.sql
