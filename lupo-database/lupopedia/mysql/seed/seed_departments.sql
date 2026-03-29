-- Department seed data for Lupopedia 4.0.89
-- Department-based actor access control implementation using mapping tables

-- Root department (department_id = 0) - Full system access
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
    0,                                          -- department_id 0 = root department
    1,                                          -- federation_node_id (core)
    'Root',
    'Root department with full system access. Department 0 has highest privileges.',
    'system',
    1,                                          -- default_actor_id = WOLFIE
    20260328120000,
    20260328120000,
    0
);

-- Map system actors (1-14) to department 0 (root) using actor_departments table
INSERT INTO lupo_actor_departments (actor_department_id, actor_id, department_id, role_key, title, created_ymdhis, updated_ymdhis, is_deleted)
SELECT 
    (SELECT COALESCE(MAX(actor_department_id), 0) + 1 + ROW_NUMBER() OVER (ORDER BY actor_id) FROM lupo_actor_departments) as actor_department_id,
    actor_id,
    0 as department_id,
    'system' as role_key,
    'System Actor' as title,
    20260328120000 as created_ymdhis,
    20260328120000 as updated_ymdhis,
    0 as is_deleted
FROM lupo_actors 
WHERE actor_id BETWEEN 1 AND 14;

-- Map root auth user (auth_user_id 1000) to department 0 using auth_user_departments table
INSERT INTO lupo_auth_user_departments (
    auth_user_department_id, auth_user_id, department_id, is_primary, role_key, title, created_ymdhis, updated_ymdhis, is_deleted
) VALUES (
    (SELECT COALESCE(MAX(auth_user_department_id), 0) + 1 FROM lupo_auth_user_departments),
    1000,
    0,
    1,  -- is_primary
    'administrator',
    'Root Administrator',
    20260328120000,
    20260328120000,
    0
);

-- For any existing auth users without department assignments, assign to department 0
INSERT INTO lupo_auth_user_departments (
    auth_user_department_id, auth_user_id, department_id, is_primary, role_key, title, created_ymdhis, updated_ymdhis, is_deleted
)
SELECT 
    (SELECT COALESCE(MAX(aud2.auth_user_department_id), 0) + ROW_NUMBER() OVER (ORDER BY au.auth_user_id) FROM lupo_auth_user_departments aud2) as auth_user_department_id,
    au.auth_user_id,
    0 as department_id,
    1 as is_primary,
    'user' as role_key,
    'User' as title,
    20260328120000 as created_ymdhis,
    20260328120000 as updated_ymdhis,
    0 as is_deleted
FROM lupo_auth_users au
WHERE au.auth_user_id NOT IN (
    SELECT aud.auth_user_id FROM lupo_auth_user_departments aud WHERE aud.is_deleted = 0
)
AND au.is_active = 1
AND au.is_deleted = 0;
