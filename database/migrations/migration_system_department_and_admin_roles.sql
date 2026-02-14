-- Migration: System department (department_id = 0) and global administrative roles.
-- Apply to existing Lupopedia installs that predate this feature.
-- Idempotent. Run once.
-- Doctrine: no FKs, BIGINT UTC timestamps. MySQL/MariaDB.

-- 1. Create lupo_department_roles if missing (indexes included; no-op if table exists)
CREATE TABLE IF NOT EXISTS lupo_department_roles (
  department_role_id bigint NOT NULL,
  actor_id bigint NOT NULL,
  department_id bigint NOT NULL,
  role_key varchar(64) NOT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL,
  is_deleted tinyint NOT NULL DEFAULT 0,
  deleted_ymdhis bigint DEFAULT NULL,
  PRIMARY KEY (department_role_id),
  INDEX lupo_department_roles_idx_actor_id (actor_id),
  INDEX lupo_department_roles_idx_department_id (department_id),
  INDEX lupo_department_roles_idx_role_key (role_key)
);

-- 2. Insert system department (department_id = 0) if missing
INSERT INTO lupo_departments (department_id, federation_node_id, name, description, department_type, default_actor_id, settings_json, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis)
VALUES (0, 1, 'System', 'System Department (Reserved)', 'system', 0, NULL, CAST(DATE_FORMAT(UTC_TIMESTAMP(), '%Y%m%d%H%i%S') AS SIGNED), CAST(DATE_FORMAT(UTC_TIMESTAMP(), '%Y%m%d%H%i%S') AS SIGNED), 0, NULL)
ON DUPLICATE KEY UPDATE name = 'System', description = 'System Department (Reserved)', department_type = 'system', default_actor_id = 0, updated_ymdhis = CAST(DATE_FORMAT(UTC_TIMESTAMP(), '%Y%m%d%H%i%S') AS SIGNED);

-- 2b. Insert default department (department_id = 1) if missing (channels reference it)
INSERT IGNORE INTO lupo_departments (department_id, federation_node_id, name, description, department_type, default_actor_id, settings_json, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis)
VALUES (1, 1, 'General', 'Default department for channels', 'general', 0, NULL, CAST(DATE_FORMAT(UTC_TIMESTAMP(), '%Y%m%d%H%i%S') AS SIGNED), CAST(DATE_FORMAT(UTC_TIMESTAMP(), '%Y%m%d%H%i%S') AS SIGNED), 0, NULL);

-- 3. Assign existing admins to department 0
-- Admins = actors with channel 1 captain/administrator OR permission owner on admin module.

-- 3a. lupo_actor_departments: actors with channel 1 captain/administrator
INSERT INTO lupo_actor_departments (actor_department_id, actor_id, department_id, title, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis)
SELECT base + rn, actor_id, 0, 'System Administrator', ts, ts, 0, NULL
FROM (
    SELECT r.actor_id, @arn1 := @arn1 + 1 AS rn
    FROM lupo_actor_channel_roles r
    CROSS JOIN (SELECT @arn1 := 0) v
    WHERE r.channel_id = 1 AND r.role_key IN ('captain', 'administrator')
      AND (r.is_deleted = 0 OR r.is_deleted IS NULL)
      AND NOT EXISTS (SELECT 1 FROM lupo_actor_departments ad WHERE ad.actor_id = r.actor_id AND ad.department_id = 0 AND (ad.is_deleted = 0 OR ad.is_deleted IS NULL))
    ORDER BY r.actor_id
) t
CROSS JOIN (SELECT COALESCE(MAX(actor_department_id), 0) AS base FROM lupo_actor_departments) m
CROSS JOIN (SELECT CAST(DATE_FORMAT(UTC_TIMESTAMP(), '%Y%m%d%H%i%S') AS SIGNED) AS ts) ts;

-- 3b. lupo_actor_departments: actors from lupo_permissions (admin module owner by user_id)
INSERT INTO lupo_actor_departments (actor_department_id, actor_id, department_id, title, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis)
SELECT base + rn, actor_id, 0, 'System Administrator', ts, ts, 0, NULL
FROM (
    SELECT a.actor_id, @arn2 := @arn2 + 1 AS rn
    FROM lupo_permissions p
    INNER JOIN lupo_modules m ON m.module_key = 'admin' AND m.is_active = 1 AND (m.is_deleted = 0 OR m.is_deleted IS NULL)
    INNER JOIN lupo_actors a ON a.actor_source_id = p.user_id AND a.actor_source_type = 'user' AND (a.is_deleted = 0 OR a.is_deleted IS NULL)
    CROSS JOIN (SELECT @arn2 := 0) v
    WHERE p.target_type = 'module' AND p.target_id = m.module_id
      AND p.permission = 'owner' AND (p.is_deleted = 0 OR p.is_deleted IS NULL)
      AND NOT EXISTS (SELECT 1 FROM lupo_actor_departments ad WHERE ad.actor_id = a.actor_id AND ad.department_id = 0 AND (ad.is_deleted = 0 OR ad.is_deleted IS NULL))
    ORDER BY a.actor_id
) t
CROSS JOIN (SELECT COALESCE(MAX(actor_department_id), 0) AS base FROM lupo_actor_departments) m
CROSS JOIN (SELECT CAST(DATE_FORMAT(UTC_TIMESTAMP(), '%Y%m%d%H%i%S') AS SIGNED) AS ts) ts;

-- 3c. lupo_department_roles: role_key='administrator' for those actors
INSERT INTO lupo_department_roles (department_role_id, actor_id, department_id, role_key, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis)
SELECT base + rn, actor_id, 0, 'administrator', ts, ts, 0, NULL
FROM (
    SELECT r.actor_id, @drn1 := @drn1 + 1 AS rn
    FROM lupo_actor_channel_roles r
    CROSS JOIN (SELECT @drn1 := 0) v
    WHERE r.channel_id = 1 AND r.role_key IN ('captain', 'administrator')
      AND (r.is_deleted = 0 OR r.is_deleted IS NULL)
      AND NOT EXISTS (SELECT 1 FROM lupo_department_roles dr WHERE dr.actor_id = r.actor_id AND dr.department_id = 0 AND dr.role_key = 'administrator' AND (dr.is_deleted = 0 OR dr.is_deleted IS NULL))
    ORDER BY r.actor_id
) t
CROSS JOIN (SELECT COALESCE(MAX(department_role_id), 0) AS base FROM lupo_department_roles) m
CROSS JOIN (SELECT CAST(DATE_FORMAT(UTC_TIMESTAMP(), '%Y%m%d%H%i%S') AS SIGNED) AS ts) ts;

-- 3d. lupo_department_roles: from lupo_permissions
INSERT INTO lupo_department_roles (department_role_id, actor_id, department_id, role_key, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis)
SELECT base + rn, actor_id, 0, 'administrator', ts, ts, 0, NULL
FROM (
    SELECT a.actor_id, @drn2 := @drn2 + 1 AS rn
    FROM lupo_permissions p
    INNER JOIN lupo_modules m ON m.module_key = 'admin' AND m.is_active = 1 AND (m.is_deleted = 0 OR m.is_deleted IS NULL)
    INNER JOIN lupo_actors a ON a.actor_source_id = p.user_id AND a.actor_source_type = 'user' AND (a.is_deleted = 0 OR a.is_deleted IS NULL)
    CROSS JOIN (SELECT @drn2 := 0) v
    WHERE p.target_type = 'module' AND p.target_id = m.module_id
      AND p.permission = 'owner' AND (p.is_deleted = 0 OR p.is_deleted IS NULL)
      AND NOT EXISTS (SELECT 1 FROM lupo_department_roles dr WHERE dr.actor_id = a.actor_id AND dr.department_id = 0 AND dr.role_key = 'administrator' AND (dr.is_deleted = 0 OR dr.is_deleted IS NULL))
    ORDER BY a.actor_id
) t
CROSS JOIN (SELECT COALESCE(MAX(department_role_id), 0) AS base FROM lupo_department_roles) m
CROSS JOIN (SELECT CAST(DATE_FORMAT(UTC_TIMESTAMP(), '%Y%m%d%H%i%S') AS SIGNED) AS ts) ts;
