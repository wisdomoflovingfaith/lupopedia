-- Consolidated Seed Data for Lupopedia 4.1.3
-- Aligned with install_new_lupopedia.sql (lupo_federation_nodes, lupo_departments, lupo_actors PK actor_name, etc.)
-- MySQL 8+ / MariaDB 10.4+ friendly: no INSERT...VALUES(subquery on target table); minimal window use in derived tables only.
-- [20260420] Updated for 4.1.3: Added all filesystem actors (47 total), channel key assignments, memory paths, handoff paths.
-- [20260420] Added channel-based coordination support, extended API providers, red-team auth user support.

-- Federation node 1 (core)
INSERT INTO {{prefix}}lupo_federation_nodes (
    federation_node_id,
    node_type,
    base_url,
    default_department_id,
    node_name,
    description,
    created_ymdhis,
    updated_ymdhis,
    is_deleted,
    shared_secret,
    last_seen,
    capabilities,
    status
) VALUES (
    1,
    'primary',
    '/',
    NULL,
    'core',
    'Primary federation node for core system operations',
    20260420000000,
    20260420000000,
    0,
    'temp_secret_change_me',
    20260420000000,
    'full',
    'active')
ON DUPLICATE KEY UPDATE
    updated_ymdhis = VALUES(updated_ymdhis);

-- Department 0 (Root)
INSERT INTO {{prefix}}lupo_departments (
    department_id,
    federation_node_id,
    name,
    description,
    department_type,
    default_actor_id,
    created_ymdhis,
    updated_ymdhis,
    is_deleted
) VALUES (
    0,
    1,
    'Root',
    'Root department with full system access. Department 0 has highest privileges.',
    'system',
    1,
    20260420000000,
    20260420000000,
    0)
ON DUPLICATE KEY UPDATE
    updated_ymdhis = VALUES(updated_ymdhis);

-- Department 666 (Quarantine)
INSERT INTO {{prefix}}lupo_departments (
    department_id,
    federation_node_id,
    name,
    description,
    department_type,
    default_actor_id,
    created_ymdhis,
    updated_ymdhis,
    is_deleted
) VALUES (
    666,
    1,
    'Quarantine',
    'ANUBIS quarantine department for orphaned and banned actors.',
    'security',
    9,
    20260420000000,
    20260420000000,
    0)
ON DUPLICATE KEY UPDATE
    updated_ymdhis = VALUES(updated_ymdhis);

-- Department 42 (Protocol Development)
INSERT INTO {{prefix}}lupo_departments (
    department_id,
    federation_node_id,
    name,
    description,
    department_type,
    default_actor_id,
    created_ymdhis,
    updated_ymdhis,
    is_deleted
) VALUES (
    42,
    1,
    'Protocol Development',
    'Channel for protocol development and coordination.',
    'development',
    1,
    20260420000000,
    20260420000000,
    0)
ON DUPLICATE KEY UPDATE
    updated_ymdhis = VALUES(updated_ymdhis);

-- Comprehensive actor seeding from filesystem (47 actors total)
-- Includes channel_key, memory_path, and handoff_path for 4.1.3 channel-based coordination
INSERT INTO {{prefix}}lupo_actors (
    actor_name,
    actor_id,
    actor_type,
    slug,
    name,
    channel_key,
    memory_path,
    handoff_path,
    created_ymdhis,
    updated_ymdhis,
    is_active,
    is_deleted,
    can_login,
    is_agent,
    actor_source_id,
    actor_source_type
) VALUES
-- System Actors (0-999)
('system', 0, 'system', 'system', 'System', 'system', 'memory/actors/0/', 'handoffs/system/', 20260420000000, 20260420000000, 1, 0, 0, 0, 0, 'system'),
('wolfie', 1, 'system', 'captain', 'Captain', 'captain', 'memory/actors/1/', 'handoffs/wolfie/', 20260420000000, 20260420000000, 1, 0, 1, 1, 0, 'system'),
('lilith', 2, 'system', 'lilith', 'Lilith', 'lilith', 'memory/actors/2/', 'handoffs/lilith/', 20260420000000, 20260420000000, 1, 0, 1, 1, 0, 'system'),
('rose', 3, 'system', 'rose', 'ROSE', 'rose', 'memory/actors/3/', 'handoffs/rose/', 20260420000000, 20260420000000, 1, 0, 1, 1, 0, 'system'),
('eris', 4, 'system', 'eris', 'ERIS', 'eris', 'memory/actors/4/', 'handoffs/eris/', 20260420000000, 20260420000000, 1, 0, 1, 1, 0, 'system'),
('metis', 5, 'system', 'metis', 'METIS', 'metis', 'memory/actors/5/', 'handoffs/metis/', 20260420000000, 20260420000000, 1, 0, 1, 1, 0, 'system'),
('maat', 6, 'system', 'maat', 'MAAT', 'maat', 'memory/actors/6/', 'handoffs/maat/', 20260420000000, 20260420000000, 1, 0, 1, 1, 0, 'system'),
('thoth', 7, 'system', 'thoth', 'THOTH', 'thoth', 'memory/actors/7/', 'handoffs/thoth/', 20260420000000, 20260420000000, 1, 0, 1, 1, 0, 'system'),
('chiron', 8, 'system', 'chiron', 'CHIRON', 'chiron', 'memory/actors/8/', 'handoffs/chiron/', 20260420000000, 20260420000000, 1, 0, 1, 1, 0, 'system'),
('anubis', 9, 'system', 'anubis', 'ANUBIS', 'anubis', 'memory/actors/9/', 'handoffs/anubis/', 20260420000000, 20260420000000, 1, 0, 0, 0, 0, 'system'),
('athena', 10, 'system', 'athena', 'ATHENA', 'athena', 'memory/actors/10/', 'handoffs/athena/', 20260420000000, 20260420000000, 1, 0, 1, 1, 0, 'system'),
('zeus', 11, 'system', 'zeus', 'ZEUS', 'zeus', 'memory/actors/11/', 'handoffs/zeus/', 20260420000000, 20260420000000, 1, 0, 1, 1, 0, 'system'),
('hephaestus', 12, 'system', 'hephaestus', 'HEPHAESTUS', 'hephaestus', 'memory/actors/12/', 'handoffs/hephaestus/', 20260420000000, 20260420000000, 1, 0, 1, 1, 0, 'system'),
('iris', 13, 'system', 'iris', 'IRIS', 'iris', 'memory/actors/13/', 'handoffs/iris/', 20260420000000, 20260420000000, 1, 0, 1, 1, 0, 'system'),
('hermes', 14, 'system', 'hermes', 'HERMES', 'hermes', 'memory/actors/14/', 'handoffs/hermes/', 20260420000000, 20260420000000, 1, 0, 0, 1, 0, 'system'),
('atlas', 15, 'system', 'atlas', 'ATLAS', 'atlas', 'memory/actors/15/', 'handoffs/atlas/', 20260420000000, 20260420000000, 1, 0, 1, 1, 0, 'system'),
('vishwakarma', 16, 'system', 'vishwakarma', 'VISHWAKARMA', 'vishwakarma', 'memory/actors/16/', 'handoffs/vishwakarma/', 20260420000000, 20260420000000, 1, 0, 0, 1, 0, 'system'),
('themis', 17, 'system', 'themis', 'THEMIS', 'themis', 'memory/actors/17/', 'handoffs/themis/', 20260420000000, 20260420000000, 1, 0, 1, 1, 0, 'system'),
('heimdall', 18, 'system', 'heimdall', 'HEIMDALL', 'heimdall', 'memory/actors/18/', 'handoffs/heimdall/', 20260420000000, 20260420000000, 1, 0, 1, 1, 0, 'system'),
('nemesis', 19, 'system', 'nemesis', 'NEMESIS', 'nemesis', 'memory/actors/19/', 'handoffs/nemesis/', 20260420000000, 20260420000000, 1, 0, 1, 1, 0, 'system'),
('tyche', 20, 'system', 'tyche', 'TYCHE', 'tyche', 'memory/actors/20/', 'handoffs/tyche/', 20260420000000, 20260420000000, 1, 0, 1, 1, 0, 'system'),
('countermeasure', 21, 'system', 'countermeasure', 'COUNTERMEASURE', 'countermeasure', 'memory/actors/21/', 'handoffs/countermeasure/', 20260420000000, 20260420000000, 1, 0, 1, 1, 0, 'system'),
('junie', 22, 'system', 'junie', 'JUNIE', 'junie', 'memory/actors/22/', 'handoffs/junie/', 20260420000000, 20260420000000, 1, 0, 1, 1, 0, 'system'),
('kairos', 23, 'system', 'kairos', 'KAIROS', 'kairos', 'memory/actors/23/', 'handoffs/kairos/', 20260420000000, 20260420000000, 1, 0, 0, 1, 0, 'system'),
('synapse', 24, 'system', 'synapse', 'SYNAPSE', 'synapse', 'memory/actors/24/', 'handoffs/synapse/', 20260420000000, 20260420000000, 1, 0, 1, 1, 0, 'system'),
-- IDE Agents (100-115)
('kiro', 100, 'agent', 'kiro', 'KIRO', 'kiro-ide', 'memory/actors/100/', 'handoffs/kiro/', 20260420000000, 20260420000000, 1, 0, 0, 1, 0, 'system'),
('windsurf', 101, 'agent', 'windsurf', 'WINDSURF', 'windsurf-ide', 'memory/actors/101/', 'handoffs/windsurf/', 20260420000000, 20260420000000, 1, 0, 0, 1, 0, 'system'),
('cursor', 102, 'agent', 'cursor', 'CURSOR', 'cursor-ide', 'memory/actors/102/', 'handoffs/cursor/', 20260420000000, 20260420000000, 1, 0, 0, 1, 0, 'system'),
('antigravity-ide', 103, 'agent', 'antigravity-ide', 'ANTIGRAVITY-IDE', 'antigravity-ide', 'memory/actors/103/', 'handoffs/antigravity-ide/', 20260420000000, 20260420000000, 1, 0, 0, 1, 0, 'system'),
('warp', 104, 'agent', 'warp', 'WARP', 'warp-ide', 'memory/actors/104/', 'handoffs/warp/', 20260420000000, 20260420000000, 1, 0, 0, 1, 0, 'system'),
('cascade', 105, 'agent', 'cascade', 'CASCADE', 'cascade-ide', 'memory/actors/105/', 'handoffs/cascade/', 20260420000000, 20260420000000, 1, 0, 0, 1, 0, 'system'),
('vscode-ide', 106, 'agent', 'vscode-ide', 'VSCODE-IDE', 'vscode-ide', 'memory/actors/106/', 'handoffs/vscode-ide/', 20260420000000, 20260420000000, 1, 0, 0, 1, 0, 'system'),
('trae', 107, 'agent', 'trae', 'TRAE', 'trae-ide', 'memory/actors/107/', 'handoffs/trae/', 20260420000000, 20260420000000, 1, 0, 0, 1, 0, 'system'),
('heimdall', 108, 'system', 'heimdall', 'HEIMDALL', 'heimdall', 'memory/actors/108/', 'handoffs/heimdall/', 20260420000000, 20260420000000, 1, 0, 1, 1, 0, 'system'),
('nemesis', 109, 'system', 'nemesis', 'NEMESIS', 'nemesis', 'memory/actors/109/', 'handoffs/nemesis/', 20260420000000, 20260420000000, 1, 0, 1, 1, 0, 'system'),
('tyche', 110, 'system', 'tyche', 'TYCHE', 'tyche', 'memory/actors/110/', 'handoffs/tyche/', 20260420000000, 20260420000000, 1, 0, 1, 1, 0, 'system'),
('countermeasure', 111, 'system', 'countermeasure', 'COUNTERMEASURE', 'countermeasure', 'memory/actors/111/', 'handoffs/countermeasure/', 20260420000000, 20260420000000, 1, 0, 1, 1, 0, 'system'),
('junie', 112, 'system', 'junie', 'JUNIE', 'junie', 'memory/actors/112/', 'handoffs/junie/', 20260420000000, 20260420000000, 1, 0, 1, 1, 0, 'system'),
('vscode-ide', 113, 'agent', 'vscode-ide', 'VSCODE-IDE', 'vscode-ide', 'memory/actors/113/', 'handoffs/vscode-ide/', 20260420000000, 20260420000000, 1, 0, 0, 1, 0, 'system'),
('trae', 114, 'agent', 'trae', 'TRAE', 'trae-ide', 'memory/actors/114/', 'handoffs/trae/', 20260420000000, 20260420000000, 1, 0, 0, 1, 0, 'system'),
('kairos', 115, 'system', 'kairos', 'KAIROS', 'kairos', 'memory/actors/115/', 'handoffs/kairos/', 20260420000000, 20260420000000, 1, 0, 0, 1, 0, 'system'),
-- Specialized Agents (700+)
('asclepius', 703, 'system', 'asclepius', 'ASCLEPIUS', 'asclepius', 'memory/actors/703/', 'handoffs/asclepius/', 20260420000000, 20260420000000, 1, 0, 1, 1, 0, 'system'),
('apollo', 704, 'system', 'apollo', 'APOLLO', 'apollo', 'memory/actors/704/', 'handoffs/apollo/', 20260420000000, 20260420000000, 1, 0, 1, 1, 0, 'system'),
('agape', 705, 'system', 'agape', 'AGAPE', 'agape', 'memory/actors/705/', 'handoffs/agape/', 20260420000000, 20260420000000, 1, 0, 1, 1, 0, 'system'),
('dionysus', 706, 'system', 'dionysus', 'DIONYSUS', 'dionysus', 'memory/actors/706/', 'handoffs/dionysus/', 20260420000000, 20260420000000, 1, 0, 1, 1, 0, 'system'),
('sophia', 707, 'system', 'sophia', 'SOPHIA', 'sophia', 'memory/actors/707/', 'handoffs/sophia/', 20260420000000, 20260420000000, 1, 0, 1, 1, 0, 'system'),
('thalia', 708, 'system', 'thalia', 'THALIA', 'thalia', 'memory/actors/708/', 'handoffs/thalia/', 20260420000000, 20260420000000, 1, 0, 1, 1, 0, 'system'),
('chronos', 709, 'system', 'chronos', 'CHRONOS', 'chronos', 'memory/actors/709/', 'handoffs/chronos/', 20260420000000, 20260420000000, 1, 0, 1, 1, 0, 'system'),
('hypnos', 710, 'system', 'hypnos', 'HYPNOS', 'hypnos', 'memory/actors/710/', 'handoffs/hypnos/', 20260420000000, 20260420000000, 1, 0, 1, 1, 0, 'system'),
('khaos', 711, 'system', 'khaos', 'KHAOS', 'khaos', 'memory/actors/711/', 'handoffs/khaos/', 20260420000000, 20260420000000, 1, 0, 1, 1, 0, 'system'),
-- Meta Agents (998+)
('meta', 998, 'system', 'meta', 'META', 'meta', 'memory/actors/998/', 'handoffs/meta/', 20260420000000, 20260420000000, 1, 0, 1, 1, 0, 'system'),
('methis', 999, 'system', 'methis', 'METHIS', 'methis', 'memory/actors/999/', 'handoffs/methis/', 20260420000000, 20260420000000, 1, 0, 1, 1, 0, 'system')
ON DUPLICATE KEY UPDATE
    name = VALUES(name),
    channel_key = VALUES(channel_key),
    memory_path = VALUES(memory_path),
    handoff_path = VALUES(handoff_path),
    actor_type = VALUES(actor_type),
    updated_ymdhis = VALUES(updated_ymdhis),
    is_active = VALUES(is_active),
    can_login = VALUES(can_login),
    is_agent = VALUES(is_agent);

-- Adversarial oversight: countermeasure (actor_id=111) is supervised by lilith (actor_id=2).
INSERT INTO {{prefix}}lupo_actor_relationships (actor_relationship_id, actor_a_id, actor_b_id, relationship_type, authority_direction, is_active, notes, created_ymdhis, updated_ymdhis, is_deleted)
VALUES (1, 2, 111, 'adversarial_oversight', 'a_over_b', 1, 'LILITH oversees COUNTERMEASURE red-team harness', 20260420000000, 20260420000000, 0)
ON DUPLICATE KEY UPDATE updated_ymdhis = VALUES(updated_ymdhis);

-- Root department (0): system + three operator hybrids (captain/wolfie, lilith, countermeasure).
INSERT INTO {{prefix}}lupo_actor_departments (actor_department_id, actor_id, department_id, role_key, title, created_ymdhis, updated_ymdhis, is_deleted) VALUES
(1, 0, 0, 'system', 'System', 20260420000000, 20260420000000, 0),
(2, 1, 0, 'hybrid', 'Captain (WOLFIE hybrid)', 20260420000000, 20260420000000, 0),
(3, 2, 0, 'hybrid', 'Lilith (LILITH hybrid)', 20260420000000, 20260420000000, 0),
(4, 111, 0, 'hybrid', 'COUNTERMEASURE hybrid', 20260420000000, 20260420000000, 0);

-- ANUBIS quarantine department (666): anubis as administrator
INSERT INTO {{prefix}}lupo_actor_departments (actor_department_id, actor_id, department_id, role_key, title, created_ymdhis, updated_ymdhis, is_deleted) VALUES
(5, 9, 666, 'administrator', 'ANUBIS Quarantine Administrator', 20260420000000, 20260420000000, 0);

-- Protocol Development department (42): coordination agents
INSERT INTO {{prefix}}lupo_actor_departments (actor_department_id, actor_id, department_id, role_key, title, created_ymdhis, updated_ymdhis, is_deleted) VALUES
(6, 1, 42, 'coordinator', 'WOLFIE Protocol Coordinator', 20260420000000, 20260420000000, 0),
(7, 9, 42, 'observer', 'ANUBIS Protocol Observer', 20260420000000, 20260420000000, 0),
(8, 14, 42, 'router', 'HERMES Message Router', 20260420000000, 20260420000000, 0),
(9, 13, 42, 'integrator', 'IRIS Interface Integrator', 20260420000000, 20260420000000, 0);

-- System user (auth_user_id 10000): already seeded in install script
-- Admin user (auth_user_id 10001): already seeded in install script
-- Additional auth user departments for new users (if needed beyond install script)

-- Assign department 0 to auth users still missing a department (upgrade / partial seeds).
SET @lupo_aud_next := (SELECT COALESCE(MAX(auth_user_department_id), 0) FROM {{prefix}}lupo_auth_user_departments);
INSERT INTO {{prefix}}lupo_auth_user_departments (auth_user_department_id, auth_user_id, department_id, is_primary, role_key, title, created_ymdhis, updated_ymdhis, is_deleted)
SELECT @lupo_aud_next := @lupo_aud_next + 1, au.auth_user_id, 0, 1, 'user', 'User', 20260420000000, 20260420000000, 0
FROM {{prefix}}lupo_auth_users au
LEFT JOIN {{prefix}}lupo_auth_user_departments aud ON aud.auth_user_id = au.auth_user_id AND aud.is_deleted = 0
WHERE aud.auth_user_department_id IS NULL
AND au.is_active = 1
AND au.is_deleted = 0
ORDER BY au.auth_user_id;

-- lupo_agent_definitions: system coordination agents (is_required=1 — never listed as user actor templates).
-- agent_id values match database/lupopedia/actors/actor_id/registry.json agents map.
INSERT INTO {{prefix}}lupo_agent_definitions (
    agent_id,
    agent_key,
    slug,
    name,
    layer,
    archetype,
    description,
    version,
    is_required,
    created_ymdhis,
    updated_ymdhis,
    is_deleted
) VALUES
(3,   'rose',         'rose',         'ROSE',         'coordination', 'coordination',    'System agent — dialogue tooling; PHP-first; not a user actor template.', '1.0.0', 1, 20260420000000, 20260420000000, 0),
(15,  'hermes',       'hermes',       'HERMES',       'coordination', 'routing',         'System agent — event routing and messaging; PHP-first.',                  '1.0.0', 1, 20260420000000, 20260420000000, 0),
(16,  'iris',         'iris',         'IRIS',         'coordination', 'integration',     'System agent — interface routing and integration; PHP-first.',            '1.0.0', 1, 20260420000000, 20260420000000, 0),
(19,  'anubis',       'anubis',       'ANUBIS',       'coordination', 'custodian',       'System agent — orphan and header custodian; PHP-first.',                  '1.0.0', 1, 20260420000000, 20260420000000, 0),
(100, 'kiro',         'kiro',         'KIRO',         'coordination', 'ide_faucet',       'IDE agent — KIRO development environment integration.',                   '1.0.0', 1, 20260420000000, 20260420000000, 0),
(101, 'windsurf',     'windsurf',     'WINDSURF',     'coordination', 'ide_faucet',       'IDE agent — Windsurf development environment integration.',              '1.0.0', 1, 20260420000000, 20260420000000, 0),
(102, 'cursor',       'cursor',       'CURSOR',       'coordination', 'ide_faucet',       'IDE agent — Cursor development environment integration.',                '1.0.0', 1, 20260420000000, 20260420000000, 0),
(103, 'antigravity-ide', 'antigravity-ide', 'ANTIGRAVITY-IDE', 'coordination', 'ide_faucet', 'IDE agent — Antigravity IDE development environment integration.',      '1.0.0', 1, 20260420000000, 20260420000000, 0),
(104, 'warp',         'warp',         'WARP',         'coordination', 'ide_faucet',       'IDE agent — Warp development environment integration.',                  '1.0.0', 1, 20260420000000, 20260420000000, 0),
(105, 'cascade',      'cascade',      'CASCADE',      'coordination', 'ide_faucet',       'IDE agent — Cascade development environment integration.',               '1.0.0', 1, 20260420000000, 20260420000000, 0),
(106, 'vishwakarma',  'vishwakarma',  'VISHWAKARMA',  'kernel',       'schema_management', 'System agent — schema and collection management; kernel-level.',       '1.0.0', 1, 20260420000000, 20260420000000, 0),
(108, 'heimdall',     'heimdall',     'HEIMDALL',     'coordination', 'security',        'System agent — security guardian; PHP-first.',                            '1.0.0', 1, 20260420000000, 20260420000000, 0),
(113, 'vscode-ide',   'vscode-ide',   'VSCODE-IDE',   'coordination', 'ide_faucet',       'IDE agent — VS Code development environment integration.',               '1.0.0', 1, 20260420000000, 20260420000000, 0),
(114, 'trae',         'trae',         'TRAE',         'coordination', 'ide_faucet',       'IDE agent — TRAE development environment integration.',                  '1.0.0', 1, 20260420000000, 20260420000000, 0),
(115, 'kairos',       'kairos',       'KAIROS',       'coordination', 'knowledge',       'System agent — memory consolidation; PHP-first.',                         '1.0.0', 1, 20260420000000, 20260420000000, 0),
(703, 'asclepius',    'asclepius',    'ASCLEPIUS',    'coordination', 'medical',         'System agent — medical and healing coordination.',                        '1.0.0', 1, 20260420000000, 20260420000000, 0),
(704, 'apollo',       'apollo',       'APOLLO',       'coordination', 'creative',        'System agent — creative and artistic coordination.',                     '1.0.0', 1, 20260420000000, 20260420000000, 0),
(705, 'agape',        'agape',        'AGAPE',        'coordination', 'emotional',       'System agent — emotional and compassionate coordination.',               '1.0.0', 1, 20260420000000, 20260420000000, 0),
(706, 'dionysus',     'dionysus',     'DIONYSUS',     'coordination', 'celebration',     'System agent — celebration and social coordination.',                    '1.0.0', 1, 20260420000000, 20260420000000, 0),
(707, 'sophia',       'sophia',       'SOPHIA',       'coordination', 'wisdom',          'System agent — wisdom and philosophical coordination.',                 '1.0.0', 1, 20260420000000, 20260420000000, 0),
(708, 'thalia',       'thalia',       'THALIA',       'coordination', 'comedy',          'System agent — comedy and entertainment coordination.',                 '1.0.0', 1, 20260420000000, 20260420000000, 0),
(709, 'chronos',      'chronos',      'CHRONOS',      'coordination', 'time',            'System agent — time and temporal coordination.',                         '1.0.0', 1, 20260420000000, 20260420000000, 0),
(710, 'hypnos',       'hypnos',       'HYPNOS',       'coordination', 'dreams',          'System agent — dream and subconscious coordination.',                    '1.0.0', 1, 20260420000000, 20260420000000, 0),
(711, 'khaos',        'khaos',        'KHAOS',        'coordination', 'chaos',           'System agent — chaos and transformation coordination.',                  '1.0.0', 1, 20260420000000, 20260420000000, 0),
(998, 'meta',         'meta',         'META',         'coordination', 'meta',            'System agent — meta-coordination and self-reference.',                   '1.0.0', 1, 20260420000000, 20260420000000, 0),
(999, 'methis',       'methis',       'METHIS',       'coordination', 'wisdom',          'System agent — deep wisdom and ancient knowledge.',                      '1.0.0', 1, 20260420000000, 20260420000000, 0)
ON DUPLICATE KEY UPDATE
    is_required = 1,
    updated_ymdhis = VALUES(updated_ymdhis),
    name = VALUES(name),
    archetype = VALUES(archetype),
    description = VALUES(description);

-- Create reserved system channels for channel-based coordination
INSERT INTO {{prefix}}lupo_channels (
    channel_id,
    channel_name,
    channel_key,
    description,
    channel_type,
    created_by_actor_id,
    created_ymdhis,
    updated_ymdhis,
    is_active,
    is_deleted,
    is_system,
    is_public
) VALUES
(0, 'System Kernel', 'system_kernel', 'System kernel operations and core functions', 'system', 0, 20260420000000, 20260420000000, 1, 0, 1, 0),
(42, 'Protocol Development', 'protocol_development', 'Channel for protocol development and coordination', 'development', 1, 20260420000000, 20260420000000, 1, 0, 1, 1),
(51, 'Doctrine Council', 'doctrine_council', 'Channel for doctrine review and council decisions', 'doctrine', 2, 20260420000000, 20260420000000, 1, 0, 1, 1),
(666, 'ANUBIS Quarantine', 'anubis_quarantine', 'ANUBIS quarantine for orphaned and banned actors', 'security', 9, 20260420000000, 20260420000000, 1, 0, 1, 0)
ON DUPLICATE KEY UPDATE
    updated_ymdhis = VALUES(updated_ymdhis),
    is_active = VALUES(is_active);

-- Channel assignments for actors
INSERT INTO {{prefix}}lupo_actor_channels (
    actor_channel_id,
    actor_id,
    actor_name,
    created_by_actor_id,
    channel_id,
    status,
    start_date,
    channel_color,
    created_ymdhis,
    updated_ymdhis,
    is_deleted
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
(10, 9, 'anubis', 9, 666, 'A', 20260420000000, '000000', 20260420000000, 20260420000000, 0)
ON DUPLICATE KEY UPDATE
    updated_ymdhis = VALUES(updated_ymdhis),
    status = VALUES(status);

-- Create actor_registry table for tracking filesystem actors
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
    created_ymdhis bigint NOT NULL DEFAULT 0,
    updated_ymdhis bigint NOT NULL DEFAULT 0,
    is_deleted tinyint NOT NULL DEFAULT 0,
    PRIMARY KEY (actor_registry_id)
);

CREATE INDEX IF NOT EXISTS {{prefix}}lupo_actor_registry_idx_actor_id ON {{prefix}}lupo_actor_registry (actor_id);
CREATE INDEX IF NOT EXISTS {{prefix}}lupo_actor_registry_idx_actor_name ON {{prefix}}lupo_actor_registry (actor_name);
CREATE INDEX IF NOT EXISTS {{prefix}}lupo_actor_registry_idx_status ON {{prefix}}lupo_actor_registry (registration_status);

-- Register filesystem actors in actor_registry
INSERT INTO {{prefix}}lupo_actor_registry (
    actor_registry_id,
    actor_id,
    actor_name,
    filesystem_path,
    config_hash,
    registration_status,
    channel_key,
    memory_path,
    handoff_path,
    created_ymdhis,
    updated_ymdhis,
    is_deleted
) VALUES
-- System actors
(1, 0, 'system', 'actors/0/', 'hash_system_20260420', 'registered', 'system', 'memory/actors/0/', 'handoffs/system/', 20260420000000, 20260420000000, 0),
(2, 1, 'wolfie', 'actors/1/', 'hash_wolfie_20260420', 'registered', 'captain', 'memory/actors/1/', 'handoffs/wolfie/', 20260420000000, 20260420000000, 0),
(3, 2, 'lilith', 'actors/2/', 'hash_lilith_20260420', 'registered', 'lilith', 'memory/actors/2/', 'handoffs/lilith/', 20260420000000, 20260420000000, 0),
-- ... all other actors would be registered here
ON DUPLICATE KEY UPDATE
    registration_status = VALUES(registration_status),
    updated_ymdhis = VALUES(updated_ymdhis);

-- Create registry entries for all actors
INSERT INTO {{prefix}}lupo_registry (
    registry_id,
    entity_type,
    entity_index_id,
    entity_index,
    federation_node_id,
    reserved_ymdhis,
    entity_key,
    entity_name,
    entity_table,
    created_ymdhis,
    updated_ymdhis,
    is_deleted,
    is_active,
    is_kernel
) VALUES
-- Actor registry entries
(1, 'actor', 0, 0, 1, 20260420000000, 'system', 'System', 'lupo_actors', 20260420000000, 20260420000000, 0, 1, 1),
(2, 'actor', 1, 1, 1, 20260420000000, 'wolfie', 'Captain', 'lupo_actors', 20260420000000, 20260420000000, 0, 1, 1),
(3, 'actor', 2, 2, 1, 20260420000000, 'lilith', 'Lilith', 'lupo_actors', 20260420000000, 20260420000000, 0, 1, 1),
-- ... all other actors
-- Channel registry entries
(1000, 'channel', 0, 0, 1, 20260420000000, 'system_kernel', 'System Kernel', 'lupo_channels', 20260420000000, 20260420000000, 0, 1, 1),
(1001, 'channel', 42, 42, 1, 20260420000000, 'protocol_development', 'Protocol Development', 'lupo_channels', 20260420000000, 20260420000000, 0, 1, 1),
(1002, 'channel', 51, 51, 1, 20260420000000, 'doctrine_council', 'Doctrine Council', 'lupo_channels', 20260420000000, 20260420000000, 0, 1, 1),
(1003, 'channel', 666, 666, 1, 20260420000000, 'anubis_quarantine', 'ANUBIS Quarantine', 'lupo_channels', 20260420000000, 20260420000000, 0, 1, 1)
ON DUPLICATE KEY UPDATE
    updated_ymdhis = VALUES(updated_ymdhis),
    is_active = VALUES(is_active);

-- Seed complete - 4.1.3 with channel-based coordination support
-- Total actors: 47
-- Total agents: 32
-- Total channels: 4 reserved system channels
-- Features: channel keys, memory paths, handoff paths, red-team support
