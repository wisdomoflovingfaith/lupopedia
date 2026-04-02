-- Consolidated Seed Data for Lupopedia 4.1.0
-- Aligned with install_new_lupopedia.sql (lupo_federation_nodes, lupo_departments, lupo_actors PK actor_name, etc.)
-- MySQL 8+ / MariaDB 10.4+ friendly: no INSERT...VALUES(subquery on target table); minimal window use in derived tables only.

-- Federation node 1 (core)
INSERT INTO lupo_federation_nodes (
    federation_node_id,
    node_type,
    node_base_url,
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

-- Core + coordination actors. Registry: system=0, wolfie=1, lilith=2, anubis=19 (install.php Activations Block requires 0,1,2,19).
-- PK is actor_name; slug captain-wolfie matches lupo-database/lupopedia/actors/registry.json.
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
('wolfie', 1, 'system', 'captain-wolfie', 'Captain WOLFIE', 20260328120000, 20260328120000, 1, 0, 1, 1, 0, 'system'),
('lilith', 2, 'system', 'lilith', 'LILITH', 20260328120000, 20260328120000, 1, 0, 1, 1, 0, 'system'),
('lexa', 3, 'system', 'lexa', 'LEXA', 20260328120000, 20260328120000, 1, 0, 1, 1, 0, 'system'),
('heimdall', 4, 'system', 'heimdall', 'HEIMDALL', 20260328120000, 20260328120000, 1, 0, 1, 1, 0, 'system'),
('seshat', 5, 'system', 'seshat', 'SESHAT', 20260328120000, 20260328120000, 1, 0, 1, 1, 0, 'system'),
('athena', 6, 'system', 'athena', 'ATHENA', 20260328120000, 20260328120000, 1, 0, 1, 1, 0, 'system'),
('maat', 7, 'system', 'maat', 'MAAT', 20260328120000, 20260328120000, 1, 0, 1, 1, 0, 'system'),
('themis', 8, 'system', 'themis', 'THEMIS', 20260328120000, 20260328120000, 1, 0, 1, 1, 0, 'system'),
('thoth', 9, 'system', 'thoth', 'THOTH', 20260328120000, 20260328120000, 1, 0, 1, 1, 0, 'system'),
('janus', 10, 'system', 'janus', 'JANUS', 20260328120000, 20260328120000, 1, 0, 1, 1, 0, 'system'),
('rose', 11, 'system', 'rose', 'ROSE', 20260328120000, 20260328120000, 1, 0, 1, 1, 0, 'system'),
('hermes', 12, 'system', 'hermes', 'HERMES', 20260328120000, 20260328120000, 1, 0, 1, 1, 0, 'system'),
('iris', 13, 'system', 'iris', 'IRIS', 20260328120000, 20260328120000, 1, 0, 1, 1, 0, 'system'),
('asclepius', 14, 'system', 'asclepius', 'ASCLEPIUS', 20260328120000, 20260328120000, 1, 0, 1, 1, 0, 'system'),
('anubis', 19, 'system', 'anubis', 'ANUBIS', 20260328120000, 20260328120000, 1, 0, 1, 1, 0, 'system')
ON DUPLICATE KEY UPDATE
    name = VALUES(name),
    actor_type = VALUES(actor_type),
    updated_ymdhis = VALUES(updated_ymdhis),
    is_active = VALUES(is_active),
    can_login = VALUES(can_login),
    is_agent = VALUES(is_agent);

-- Map seeded actors to department 0 (actor_department_id explicit; includes system 0 and ANUBIS 19)
INSERT INTO lupo_actor_departments (actor_department_id, actor_id, department_id, role_key, title, created_ymdhis, updated_ymdhis, is_deleted) VALUES
(1, 0, 0, 'system', 'System Actor', 20260328120000, 20260328120000, 0),
(2, 1, 0, 'system', 'System Actor', 20260328120000, 20260328120000, 0),
(3, 2, 0, 'system', 'System Actor', 20260328120000, 20260328120000, 0),
(4, 3, 0, 'system', 'System Actor', 20260328120000, 20260328120000, 0),
(5, 4, 0, 'system', 'System Actor', 20260328120000, 20260328120000, 0),
(6, 5, 0, 'system', 'System Actor', 20260328120000, 20260328120000, 0),
(7, 6, 0, 'system', 'System Actor', 20260328120000, 20260328120000, 0),
(8, 7, 0, 'system', 'System Actor', 20260328120000, 20260328120000, 0),
(9, 8, 0, 'system', 'System Actor', 20260328120000, 20260328120000, 0),
(10, 9, 0, 'system', 'System Actor', 20260328120000, 20260328120000, 0),
(11, 10, 0, 'system', 'System Actor', 20260328120000, 20260328120000, 0),
(12, 11, 0, 'system', 'System Actor', 20260328120000, 20260328120000, 0),
(13, 12, 0, 'system', 'System Actor', 20260328120000, 20260328120000, 0),
(14, 13, 0, 'system', 'System Actor', 20260328120000, 20260328120000, 0),
(15, 14, 0, 'system', 'System Actor', 20260328120000, 20260328120000, 0),
(16, 19, 0, 'system', 'System Actor', 20260328120000, 20260328120000, 0);

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
