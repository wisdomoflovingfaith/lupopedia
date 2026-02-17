-- Migration: Drop lupo_channel_roles table (4.0.6).
-- Identity doctrine: NO lupo_channel_roles. All role logic uses lupo_actor_channel_roles with role_key.
-- Run migration_operator_to_actor_channel_roles.sql first if upgrading from pre-4.0.5.
-- Idempotent: safe to run if table already dropped.

DROP TABLE IF EXISTS lupo_channel_roles;
