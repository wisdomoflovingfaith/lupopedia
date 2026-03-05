-- Migration: Create lupo_bans_log table (4.0.18 T7)
-- Purpose: Audit log for router Ban at Gate 403 events; lupo_log_ban_event() writes here.
-- Idempotent: CREATE TABLE IF NOT EXISTS.
-- Run once per existing DB; fresh installs get table from install_new_lupopedia.sql.

CREATE TABLE IF NOT EXISTS lupo_bans_log (
  bans_log_id bigint NOT NULL AUTO_INCREMENT,
  actor_id bigint NOT NULL,
  uri varchar(1024) NOT NULL DEFAULT '',
  resolved_uri varchar(1024) NOT NULL DEFAULT '',
  ban_scope varchar(64) NOT NULL DEFAULT 'router',
  banned_ymdhis bigint NOT NULL,
  user_agent varchar(500) DEFAULT NULL,
  ip_address varchar(45) DEFAULT NULL,
  PRIMARY KEY (bans_log_id),
  KEY lupo_bans_log_idx_actor_id (actor_id),
  KEY lupo_bans_log_idx_banned_ymdhis (banned_ymdhis),
  KEY lupo_bans_log_idx_ban_scope (ban_scope)
);
