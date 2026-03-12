-- One-time migration: Session Model A (DB-backed sessions). No Lupopedia->Lupopedia upgrade support.
-- Drops legacy session tables and creates canonical lupo_sessions. All users logged out; no data migration.

DROP TABLE IF EXISTS unified_sessions;
DROP TABLE IF EXISTS sessions;
DROP TABLE IF EXISTS session_data;
DROP TABLE IF EXISTS lupo_sessions;

CREATE TABLE lupo_sessions (
  session_id varchar(128) NOT NULL,
  actor_id bigint NOT NULL,
  federation_node_id bigint NOT NULL DEFAULT 0,
  ip_hash varchar(128) DEFAULT NULL,
  ua_hash varchar(255) DEFAULT NULL,
  csrf_token varchar(128) DEFAULT NULL,
  last_activity_ymdhis bigint NOT NULL,
  created_ymdhis bigint NOT NULL,
  updated_ymdhis bigint NOT NULL,
  name_key varchar(100) DEFAULT NULL,
  is_named tinyint NOT NULL DEFAULT 0,
  metadata json DEFAULT NULL,
  PRIMARY KEY (session_id)
);

CREATE INDEX lupo_sessions_idx_actor ON lupo_sessions (actor_id);
CREATE INDEX lupo_sessions_idx_last_activity ON lupo_sessions (last_activity_ymdhis);
CREATE INDEX lupo_sessions_idx_federation ON lupo_sessions (federation_node_id);
