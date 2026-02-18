-- One-time: fix main admin (auth_user_id/actor_id 10000) so admin.php allows access.
-- 1) actor_source_type = 'user' so AuthService::getCurrentUser() finds the user
-- 2) Point any session using another actor for auth 10000 (e.g. 12151) to actor_id 10000
-- 3) Ensure admin module exists (module_id 9)
-- 4) Grant owner on admin module to user_id 10000 (lupo_permissions)
-- Run once per environment. Idempotent.

-- 1) Main admin actor must have actor_source_type = 'user'
UPDATE lupo_actors
SET actor_source_type = 'user'
WHERE actor_id = 10000 AND (actor_source_type IS NULL OR actor_source_type != 'user');

-- 2) Sessions that belong to auth 10000 via another actor (e.g. duplicate actor 12151) -> use canonical actor 10000
UPDATE lupo_sessions s
INNER JOIN lupo_actors a ON a.actor_id = s.actor_id
SET s.actor_id = 10000
WHERE a.actor_source_id = 10000 AND a.actor_id != 10000;

-- 3) Ensure admin module exists (module_id 9, module_key 'admin'). Seed may not have run.
INSERT INTO lupo_modules (module_id, module_key, module_name, namespace, version, version_code, minimum_core_version, user_path, admin_path, api_path, route_params, description, author, website, icon, dependencies, conflicts, config_json, is_system, is_active, federation_node_id, settings, installed_ymdhis, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis)
VALUES (9, 'admin', 'Admin', 'Admin', '4.0.17', 40017, '4.0', '/admin.php', '/admin.php', NULL, NULL, 'Global admin interface (admin.php). Owner on this module grants global admin access.', 'Eric Gerdes', 'https://lupopedia.com', 'cog', NULL, NULL, '{}', 1, 1, 1, '{}', 20260217000000, 20260217000000, NULL, 0, NULL)
ON DUPLICATE KEY UPDATE module_key = 'admin', module_name = 'Admin', is_active = 1, is_deleted = 0, deleted_ymdhis = NULL;

-- 4) Grant owner on admin module to auth_user_id 10000 (fallback for AuthRoleResolver::hasAdminViaPermissions)
INSERT INTO lupo_permissions (permission_id, target_type, target_id, user_id, department_id, permission, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis)
SELECT (SELECT COALESCE(MAX(p.permission_id), 0) + 1 FROM lupo_permissions p), 'module', 9, 10000, NULL, 'owner', 20260217000000, NULL, 0, NULL
FROM (SELECT 1) t
WHERE NOT EXISTS (SELECT 1 FROM lupo_permissions WHERE target_type = 'module' AND target_id = 9 AND user_id = 10000 AND (is_deleted = 0 OR is_deleted IS NULL));
