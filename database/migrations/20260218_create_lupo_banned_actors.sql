-- Migration: Create lupo_banned_actors table (4.0.16)
-- Purpose: Single source of truth for banned actor_ids. ANUBIS reads from this table.
-- Idempotent: CREATE TABLE IF NOT EXISTS.
-- Run once per existing DB; fresh installs get table from install_new_lupopedia.sql.

-- Run once on existing DBs. Idempotent: CREATE TABLE IF NOT EXISTS.
CREATE TABLE IF NOT EXISTS lupo_banned_actors (
  banned_actor_id bigint NOT NULL,
  actor_id bigint NOT NULL,
  ip_address varchar(45) DEFAULT NULL,
  reason varchar(500) NOT NULL,
  banned_ymdhis bigint NOT NULL,
  banned_by_actor_id bigint DEFAULT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL,
  is_deleted tinyint NOT NULL DEFAULT '0',
  deleted_ymdhis bigint DEFAULT NULL,
  PRIMARY KEY (banned_actor_id),
  KEY lupo_banned_actors_idx_actor_id (actor_id),
  KEY lupo_banned_actors_idx_ip_address (ip_address),
  KEY lupo_banned_actors_idx_is_deleted (is_deleted)
);
