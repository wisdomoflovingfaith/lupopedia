-- Lupopedia Demo Operators Migration
-- Date: 2026-01-30
-- Description: Adds demo users operatortest@lupopedia.com and helen@lupopedia.com as actors.
-- Operator/role data now uses lupo_channel_roles (channel-scoped roles). To grant these
-- actors operator or captain rights, insert into lupo_channel_roles (channel_id, actor_id, role_type)
-- e.g. for default channel_id = 1 with role_type IN ('captain', 'administrator', 'monitor').
-- The old lupo_operators and lupo_operator_status tables are deprecated; do not use them.

-- 1. Insert into lupo_auth_users
INSERT INTO `lupo_auth_users` (`username`, `display_name`, `email`, `password_hash`, `created_ymdhis`, `updated_ymdhis`, `is_active`) 
VALUES 
('operatortest', 'Operator Test', 'operatortest@lupopedia.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 20260130000000, 20260130000000, 1),
('helen-at-lupopedia-com', 'Helen', 'helen@lupopedia.com', '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', 20260130000000, 20260130000000, 1);
-- Passwords: 'password' (temporary) for operatortest, 'Lucy52!' for helen (hash is bcrypt)

-- 2. Insert into lupo_actors (one for each user)
-- Note: actor_source_id should match auth_user_id from above. Assuming IDs 1 and 2 for simplicity, but in a real migration we'd use variables.
-- For this script, we'll use a subquery to be safe.

INSERT INTO `lupo_actors` (`actor_type`, `slug`, `name`, `created_ymdhis`, `updated_ymdhis`, `is_active`, `actor_source_id`, `actor_source_type`)
SELECT 'user', 'operatortest', 'Operator Test', 20260130000000, 20260130000000, 1, auth_user_id, 'lupo_auth_users'
FROM `lupo_auth_users` WHERE `email` = 'operatortest@lupopedia.com';

INSERT INTO `lupo_actors` (`actor_type`, `slug`, `name`, `created_ymdhis`, `updated_ymdhis`, `is_active`, `actor_source_id`, `actor_source_type`)
SELECT 'user', 'helen', 'Helen', 20260130000000, 20260130000000, 1, auth_user_id, 'lupo_auth_users'
FROM `lupo_auth_users` WHERE `email` = 'helen@lupopedia.com';

-- 3. Roles: Use lupo_channel_roles to grant channel roles (e.g. channel_id = 1, role_type = 'captain' or 'administrator').
--    Example (run after channel_role_id sequence is known): INSERT INTO lupo_channel_roles (channel_role_id, channel_id, actor_id, role_type, created_ymdhis, updated_ymdhis, is_deleted) SELECT ... FROM lupo_actors a JOIN lupo_auth_users u ON ... WHERE u.email IN ('operatortest@lupopedia.com', 'helen@lupopedia.com');
--    The old lupo_operators and lupo_operator_status tables are deprecated and have been removed from the schema.
