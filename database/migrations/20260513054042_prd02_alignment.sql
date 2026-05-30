-- =============================================================================
-- ONE-TIME PRD 02 alignment (UTC filename stamp 20260513054042).
-- Source: docs/prd/02_A-i_CHANNELS_DB_DESIGN.md (Agent Colors; Recent Files).
-- Apply through the validated migration runner per DB009 / SAFE_MIGRATION_DOCTRINE.
-- Intended to run once on a database that predates install_new_lupopedia.sql
-- containing {{prefix}}agent_colors and {{prefix}}dialog_recent_files.
-- Re-running after success will error on CREATE TABLE (by design).
-- Replace {{prefix}} with your LUPO_TABLE_PREFIX (for example lupo_) before execute.
-- No FOREIGN KEY clauses (constitutional DB=storage only).
-- =============================================================================

-- PRD 02_A-i_CHANNELS_DB_DESIGN -- Agent-Specific Color Override -- Agent Colors Table Schema
CREATE TABLE {{prefix}}agent_colors (
  actor_id bigint NOT NULL,
  background_color varchar(7) NOT NULL COMMENT 'Hex color, e.g. #1E88E5',
  text_color varchar(7) NOT NULL DEFAULT '#FFFFFF',
  last_used_ymdhis bigint NOT NULL COMMENT 'YYYYMMDDHHIISS UTC',
  PRIMARY KEY (actor_id)
);

-- PRD 02_A-i_CHANNELS_DB_DESIGN -- Recent Files Browser/Table
CREATE TABLE {{prefix}}dialog_recent_files (
  recent_file_id bigint NOT NULL,
  file_path_from_root varchar(512) NOT NULL,
  content_id bigint DEFAULT NULL COMMENT 'Links to contents if imported',
  accessed_by_actor_id bigint NOT NULL,
  accessed_ymdhis bigint NOT NULL COMMENT 'YYYYMMDDHHIISS UTC',
  file_size bigint NOT NULL DEFAULT 0,
  is_deleted tinyint NOT NULL DEFAULT 0,
  deleted_ymdhis bigint DEFAULT NULL COMMENT 'soft delete; aligns with dialog_messages pattern',
  PRIMARY KEY (recent_file_id)
);

CREATE INDEX {{prefix}}dialog_recent_files_idx_accessed ON {{prefix}}dialog_recent_files (accessed_ymdhis DESC);
CREATE INDEX {{prefix}}dialog_recent_files_idx_actor ON {{prefix}}dialog_recent_files (accessed_by_actor_id);
CREATE UNIQUE INDEX {{prefix}}dialog_recent_files_uk_actor_file ON {{prefix}}dialog_recent_files (accessed_by_actor_id, file_path_from_root(255));
