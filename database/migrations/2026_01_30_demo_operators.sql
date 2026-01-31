-- Lupopedia Demo Operators Migration
-- Date: 2026-01-30
-- Description: Adds demo operators operatortest@lupopedia.com and helen@lupopedia.com

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

-- 3. Insert into lupo_operators
INSERT INTO `lupo_operators` (`auth_user_id`, `actor_id`, `department_id`, `is_active`, `availability_status`, `created_ymdhis`, `updated_ymdhis`, `pono_score`, `pilau_score`, `kapakai_score`)
SELECT u.auth_user_id, a.actor_id, 1, 1, 'online', 20260130000000, 20260130000000, 1.00, 0.00, 0.50
FROM `lupo_auth_users` u
JOIN `lupo_actors` a ON a.actor_source_id = u.auth_user_id AND a.actor_source_type = 'lupo_auth_users'
WHERE u.email = 'operatortest@lupopedia.com';

INSERT INTO `lupo_operators` (`auth_user_id`, `actor_id`, `department_id`, `is_active`, `availability_status`, `created_ymdhis`, `updated_ymdhis`, `pono_score`, `pilau_score`, `kapakai_score`)
SELECT u.auth_user_id, a.actor_id, 1, 1, 'online', 20260130000000, 20260130000000, 1.00, 0.00, 0.50
FROM `lupo_auth_users` u
JOIN `lupo_actors` a ON a.actor_source_id = u.auth_user_id AND a.actor_source_type = 'lupo_auth_users'
WHERE u.email = 'helen@lupopedia.com';

-- 4. Insert into lupo_operator_status
INSERT INTO `lupo_operator_status` (`operator_id`, `status`, `last_seen_ymdhis`, `active_chat_count`, `max_chat_capacity`, `created_ymdhis`, `updated_ymdhis`)
SELECT operator_id, 'online', 20260130000000, 0, 5, 20260130000000, 20260130000000
FROM `lupo_operators` o
JOIN `lupo_auth_users` u ON o.auth_user_id = u.auth_user_id
WHERE u.email IN ('operatortest@lupopedia.com', 'helen@lupopedia.com');
