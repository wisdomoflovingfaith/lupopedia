-- Optional / future-features tables moved from install_new_lupopedia.sql (v4.0.57).
-- Do NOT run during standard install; run only if these features are enabled.
-- Canonical install: install_new_lupopedia.sql only. No FKs, no triggers, BIGINT timestamps.
-- See lupo-docs/versions/REQUIRED_TABLES_4.0.21.md (optional → future-features).

-- =============================================================================
-- lupo_aliases (optional; moved 4.0.57)
-- =============================================================================
CREATE TABLE lupo_aliases (
  alias_id int NOT NULL,
  slug varchar(255) NOT NULL,
  alias varchar(255) NOT NULL,
  alias_type varchar(50) DEFAULT 'semantic',
  created_at bigint,
  PRIMARY KEY (alias_id)
);

CREATE UNIQUE INDEX lupo_aliases_uniq_alias ON lupo_aliases (alias);
CREATE INDEX lupo_aliases_idx_slug ON lupo_aliases (slug);

-- =============================================================================
-- lupo_anubis_orphaned (optional; moved 4.0.57)
-- =============================================================================
CREATE TABLE lupo_anubis_orphaned (
  anubis_orphaned_id bigint NOT NULL,
  table_name varchar(255) NOT NULL,
  orphan_id bigint NOT NULL,
  created_ymdhis bigint NOT NULL,
  updated_ymdhis bigint NOT NULL,
  reason varchar(255) NOT NULL,
  is_deleted tinyint NOT NULL DEFAULT 0,
  deleted_ymdhis bigint DEFAULT NULL,
  PRIMARY KEY (anubis_orphaned_id)
);

-- =============================================================================
-- lupo_tldnr (optional; moved 4.0.57)
-- =============================================================================
CREATE TABLE lupo_tldnr (
  tldnr_id bigint NOT NULL,
  slug varchar(255) NOT NULL,
  title varchar(255) NOT NULL,
  content_text text NOT NULL,
  topic_type varchar(100) DEFAULT NULL,
  topic_reference varchar(255) DEFAULT NULL,
  system_version varchar(20) DEFAULT NULL,
  category varchar(100) DEFAULT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL,
  is_deleted tinyint NOT NULL DEFAULT '0',
  deleted_ymdhis bigint DEFAULT NULL,
  PRIMARY KEY (tldnr_id)
);

CREATE UNIQUE INDEX lupo_tldnr_uniq_slug ON lupo_tldnr (slug);
CREATE INDEX lupo_tldnr_idx_topic_type ON lupo_tldnr (topic_type);
CREATE INDEX lupo_tldnr_idx_topic_reference ON lupo_tldnr (topic_reference);
CREATE INDEX lupo_tldnr_idx_category ON lupo_tldnr (category);
CREATE INDEX lupo_tldnr_idx_system_version ON lupo_tldnr (system_version);
CREATE INDEX lupo_tldnr_idx_is_deleted ON lupo_tldnr (is_deleted);
CREATE INDEX lupo_tldnr_idx_created ON lupo_tldnr (created_ymdhis);
