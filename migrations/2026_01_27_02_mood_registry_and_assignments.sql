-- Canonical mood registry and assignment tables (schema-only).
-- Drops legacy single-column mood_rgb fields from entity tables.

CREATE TABLE IF NOT EXISTS lupo_mood_registry (
  mood_id bigint NOT NULL AUTO_INCREMENT,
  mood_type varchar(64) NOT NULL COMMENT 'Canonical mood type (e.g., agape, eros)',
  mood_variant varchar(64) NULL COMMENT 'Optional subtype or variant label',
  mood_rgb char(6) NOT NULL COMMENT 'Emotional polarity tensor (hex), fixed-width',
  description text NULL,
  created_ymdhis bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when created',
  updated_ymdhis bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when updated',
  PRIMARY KEY (mood_id),
  INDEX idx_mood_type (mood_type),
  INDEX idx_mood_rgb (mood_rgb)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS lupo_mood_assignments (
  mood_assignment_id bigint NOT NULL AUTO_INCREMENT,
  table_name varchar(128) NOT NULL COMMENT 'Target table name (e.g., lupo_dialog_messages)',
  row_id bigint NOT NULL COMMENT 'Primary key value in target table',
  mood_id bigint NOT NULL COMMENT 'Reference to lupo_mood_registry.mood_id',
  created_ymdhis bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when created',
  PRIMARY KEY (mood_assignment_id),
  INDEX idx_assignment_target (table_name, row_id),
  INDEX idx_assignment_mood (mood_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE lupo_dialog_messages DROP COLUMN IF EXISTS mood_rgb;
ALTER TABLE lupo_dialog_threads DROP COLUMN IF EXISTS mood_rgb;
ALTER TABLE lupo_dialog_channels DROP COLUMN IF EXISTS mood_rgb;
ALTER TABLE lupo_channel_state DROP COLUMN IF EXISTS mood_rgb;
