-- One-time: set main admin actor (10000) actor_source_type to 'user' so AuthService::getCurrentUser() finds the user and admin access works.
-- Run once per environment. Idempotent. Set updated_ymdhis in app if needed.

UPDATE lupo_actors
SET actor_source_type = 'user'
WHERE actor_id = 10000 AND (actor_source_type IS NULL OR actor_source_type != 'user');
