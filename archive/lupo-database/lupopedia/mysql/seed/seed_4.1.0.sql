-- Consolidated Seed Data for Lupopedia 4.1.0
-- Aligned with install_new_lupopedia.sql (lupo_federation_nodes, lupo_departments, lupo_actors PK actor_name, etc.)
-- MySQL 8+ / MariaDB 10.4+ friendly: no INSERT...VALUES(subquery on target table); minimal window use in derived tables only.
-- [20260414] THOTH actor_id corrected: 9 -> 26 (WOLFIE decision; AGT-001 resolved).
-- [20260414] HERMES added to lupo_actors at actor_id=27 (was PHP-only attribution; now full actor row).
-- [20260414] VISHWAKARMA added to lupo_actors at actor_id=28 and lupo_agent_definitions at agent_id=106.

-- Federation node 1 (core)
INSERT INTO lupo_federation_nodes (
    federation_node_id,
    node_type,
    base_url,
    default_department_id,
    node_name,
    description,
    created_ymdhis,
    updated_ymdhis,
    is_deleted
) VALUES (
    1,
    'primary',
    '/',
    NULL,
    'core',
    'Primary federation node for core system operations',
    20260328120000,
    20260328120000,
    0
);

-- Department 0 (Root)
INSERT INTO lupo_departments (
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
    20260328120000,
    20260328120000,
    0
);

-- Core + coordination actors (human-facing / hybrid operators plus required system actors in this seed).
-- Required system actors seeded in lupo_actors include ANUBIS (actor_id=9) and THOTH (actor_id=26).
-- HERMES (actor_id=27) and VISHWAKARMA (actor_id=28) are now seeded into lupo_actors [20260414].
-- Root department (0) hybrid operators (web act-as via lupo_actor_departments): captain=wolfie hybrid, lilith, countermeasure.
-- PK is actor_name (wolfie/lilith immutable per convergence doctrine); display name/slug carry captain/Lilith labels.
-- Other personas (lexa..asclepius) remain in lupo_actors but are not seeded into department 0 — add lupo_actor_departments rows when a department scope exists.
INSERT INTO lupo_actors (
    actor_name,
    actor_id,
    actor_type,
    slug,
    name,
    created_ymdhis,
    updated_ymdhis,
    is_active,
    is_deleted,
    can_login,
    is_agent,
    actor_source_id,
    actor_source_type
) VALUES
('system', 0, 'system', 'system', 'System', 20260328120000, 20260328120000, 1, 0, 0, 0, 0, 'system'),
('wolfie', 1, 'system', 'captain', 'Captain', 20260328120000, 20260328120000, 1, 0, 1, 1, 0, 'system'),
('lilith', 2, 'system', 'lilith', 'Lilith', 20260328120000, 20260328120000, 1, 0, 1, 1, 0, 'system'),
('lexa', 3, 'system', 'lexa', 'LEXA', 20260328120000, 20260328120000, 1, 0, 1, 1, 0, 'system'),
('seshat', 5, 'system', 'seshat', 'SESHAT', 20260328120000, 20260328120000, 1, 0, 1, 1, 0, 'system'),
('athena', 6, 'system', 'athena', 'ATHENA', 20260328120000, 20260328120000, 1, 0, 1, 1, 0, 'system'),
('maat', 7, 'system', 'maat', 'MAAT', 20260328120000, 20260328120000, 1, 0, 1, 1, 0, 'system'),
('themis', 8, 'system', 'themis', 'THEMIS', 20260328120000, 20260328120000, 1, 0, 1, 1, 0, 'system'),
('anubis', 9, 'system', 'anubis', 'ANUBIS', 20260415120000, 20260415120000, 1, 0, 0, 0, 0, 'system'),
('thoth', 26, 'system', 'thoth', 'THOTH', 20260414120000, 20260414120000, 1, 0, 1, 1, 0, 'system'),
('janus', 10, 'system', 'janus', 'JANUS', 20260328120000, 20260328120000, 1, 0, 1, 1, 0, 'system'),
('asclepius', 14, 'system', 'asclepius', 'ASCLEPIUS', 20260328120000, 20260328120000, 1, 0, 1, 1, 0, 'system'),
('hermes', 27, 'system', 'hermes', 'HERMES', 20260414120000, 20260414120000, 1, 0, 0, 1, 0, 'system'),
('vishwakarma', 28, 'system', 'vishwakarma', 'VISHWAKARMA', 20260414120000, 20260414120000, 1, 0, 0, 1, 0, 'system'),
('countermeasure', 111, 'system', 'countermeasure', 'COUNTERMEASURE', 20260328120000, 20260328120000, 1, 0, 1, 1, 0, 'system'),
('kairos', 115, 'system', 'kairos', 'KAIROS', 20260328120000, 20260328120000, 1, 0, 0, 1, 0, 'system')
ON DUPLICATE KEY UPDATE
    name = VALUES(name),
    actor_type = VALUES(actor_type),
    updated_ymdhis = VALUES(updated_ymdhis),
    is_active = VALUES(is_active),
    can_login = VALUES(can_login),
    is_agent = VALUES(is_agent);

-- Adversarial oversight: countermeasure (actor_id=111) is supervised by lilith (actor_id=2).
-- adversarial_role + adversarial_oversight_actor_id were removed from lupo_actors (corrected schema NV2/NV3);
-- relationship now lives in lupo_actor_relationships.
-- [EJECTED 4.1.2] actor_relationships removed - seed data also removed

-- Root department (0): system + three operator hybrids (captain/wolfie, lilith, countermeasure). System agents (ANUBIS, IRIS, etc.) are not department-scoped actors in seed.
-- Web "act as" lists are scoped by lupo_actor_departments (see AuthSessionManager); multiple auth users share the same actor when their departments overlap.
INSERT INTO lupo_actor_departments (actor_department_id, actor_id, department_id, role_key, title, created_ymdhis, updated_ymdhis, is_deleted) VALUES
(1, 0, 0, 'system', 'System', 20260328120000, 20260328120000, 0),
(2, 1, 0, 'hybrid', 'Captain (WOLFIE hybrid)', 20260328120000, 20260328120000, 0),
(3, 2, 0, 'hybrid', 'Lilith (LILITH hybrid)', 20260328120000, 20260328120000, 0),
(4, 111, 0, 'hybrid', 'COUNTERMEASURE hybrid', 20260328120000, 20260328120000, 0);

-- Root operator mapping (auth_user_id 1000): SET then VALUES (avoids MySQL 1093 on INSERT...same-table subquery)
SET @lupo_root_aud_id := (SELECT COALESCE(MAX(auth_user_department_id), 0) + 1 FROM lupo_auth_user_departments);
INSERT INTO lupo_auth_user_departments (auth_user_department_id, auth_user_id, department_id, is_primary, role_key, title, created_ymdhis, updated_ymdhis, is_deleted)
VALUES (@lupo_root_aud_id, 1000, 0, 1, 'administrator', 'Root Administrator', 20260328120000, 20260328120000, 0);

-- Assign department 0 to auth users still missing a department (upgrade / partial seeds).
-- User-variable sequence avoids INSERT...SELECT reading the target table in a subquery (MySQL 1093).
SET @lupo_aud_next := (SELECT COALESCE(MAX(auth_user_department_id), 0) FROM lupo_auth_user_departments);
INSERT INTO lupo_auth_user_departments (auth_user_department_id, auth_user_id, department_id, is_primary, role_key, title, created_ymdhis, updated_ymdhis, is_deleted)
SELECT @lupo_aud_next := @lupo_aud_next + 1, au.auth_user_id, 0, 1, 'user', 'User', 20260328120000, 20260328120000, 0
FROM lupo_auth_users au
LEFT JOIN lupo_auth_user_departments aud ON aud.auth_user_id = au.auth_user_id AND aud.is_deleted = 0
WHERE aud.auth_user_department_id IS NULL
AND au.is_active = 1
AND au.is_deleted = 0
ORDER BY au.auth_user_id;

-- lupo_agent_definitions: system coordination agents (is_required=1 — never listed as user actor templates).
-- agent_id values match lupo-database/lupopedia/actors/actor_id/registry.json agents map.
-- Note: HERMES (agent_id=15) and VISHWAKARMA (agent_id=106) now also have lupo_actors rows [20260414].
-- Note: LILITH (actor_id=2) is a hybrid operator in lupo_actors only; no lupo_agent_definitions row by design.
INSERT INTO lupo_agent_definitions (
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
(3,   'rose',         'rose',         'ROSE',         'coordination', 'coordination',    'System agent — dialogue tooling; PHP-first; not a user actor template.', '1.0.0', 1, 20260328120000, 20260328120000, 0),
(15,  'hermes',       'hermes',       'HERMES',       'coordination', 'routing',         'System agent — event routing and messaging; PHP-first.',                  '1.0.0', 1, 20260328120000, 20260328120000, 0),
(16,  'iris',         'iris',         'IRIS',         'coordination', 'integration',     'System agent — interface routing and integration; PHP-first.',            '1.0.0', 1, 20260328120000, 20260328120000, 0),
(19,  'anubis',       'anubis',       'ANUBIS',       'coordination', 'custodian',       'System agent — orphan and header custodian; PHP-first.',                  '1.0.0', 1, 20260328120000, 20260328120000, 0),
(106, 'vishwakarma',  'vishwakarma',  'VISHWAKARMA',  'kernel',       'schema_management', 'System agent — schema and collection management; kernel-level.',       '1.0.0', 1, 20260414120000, 20260414120000, 0),
(108, 'heimdall',     'heimdall',     'HEIMDALL',     'coordination', 'security',        'System agent — security guardian; PHP-first.',                            '1.0.0', 1, 20260328120000, 20260328120000, 0),
(115, 'kairos',       'kairos',       'KAIROS',       'coordination', 'knowledge',       'System agent — memory consolidation; PHP-first.',                         '1.0.0', 1, 20260328120000, 20260328120000, 0)
(2, 'lilith', 'lilith', 'LILITH', 'kernel', 'constitutional_audit', 'Non-interfering constitutional auditor; observes, reviews, reports, escalates. LIL001: never modifies files or overrides other agents except when explicitly tasked.', '1.0.0', 1, 20260414120000, 20260414120000, 0),
ON DUPLICATE KEY UPDATE
    is_required = 1,
    updated_ymdhis = VALUES(updated_ymdhis),
    name = VALUES(name),
    archetype = VALUES(archetype),
    description = VALUES(description);
