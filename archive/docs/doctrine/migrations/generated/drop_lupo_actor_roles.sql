-- One-time migration: DROP lupo_actor_roles (table is DROPPED; actor roles do not exist).
-- Roles are channel-scoped only. The only role table is lupo_channel_roles (actor_id + channel_id -> role_type).
-- Default channel for global permissions = channel_id = 1.
-- Run manually (e.g. phpMyAdmin). Idempotent: safe if table already dropped.

DROP TABLE IF EXISTS lupo_actor_roles;
