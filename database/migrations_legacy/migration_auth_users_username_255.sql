-- One-time migration: extend lupo_auth_users.username to varchar(255) for email-length values.
-- Idempotent: safe to run on fresh install (column already 255) or existing install (30 -> 255).
-- Apply after install; do not run from wizard (wizard uses install_new_lupopedia.sql).

ALTER TABLE lupo_auth_users MODIFY COLUMN username varchar(255) NOT NULL;
