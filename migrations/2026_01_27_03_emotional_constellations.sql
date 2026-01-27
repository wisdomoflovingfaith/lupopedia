-- Pluralistic emotional architecture: stars, constellations, and agent experiences.
-- Schema-only migration; no data transformation.

CREATE TABLE IF NOT EXISTS lupo_emotional_stars (
  star_id char(26) NOT NULL COMMENT 'ULID',
  experience_hash char(64) NULL COMMENT 'Deduplication hash',
  experience_text text NOT NULL,
  cultural_context json NULL COMMENT 'Array of cultural tags',
  embodied_sensation json NULL COMMENT 'Array of somatic descriptors',
  created_by bigint NULL COMMENT 'agent_id or operator_id',
  created_in_context bigint NULL COMMENT 'thread_id or null',
  first_observed_ymdhis bigint NULL,
  observation_count int NOT NULL DEFAULT 1,
  PRIMARY KEY (star_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS lupo_emotional_constellations (
  constellation_id char(26) NOT NULL COMMENT 'ULID',
  framework_name varchar(255) NOT NULL,
  cultural_origin varchar(255) NULL,
  description text NULL,
  stars json NOT NULL COMMENT 'Array of star_ids',
  is_canonical boolean NOT NULL DEFAULT FALSE,
  canonical_for_culture varchar(255) NULL,
  created_ymdhis bigint NULL,
  deprecated_ymdhis bigint NULL,
  PRIMARY KEY (constellation_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS lupo_agent_experiences (
  link_id char(26) NOT NULL COMMENT 'ULID',
  agent_id bigint NOT NULL,
  star_id char(26) NOT NULL,
  intensity decimal(3,2) NULL COMMENT '0.00 to 1.00',
  context_id bigint NULL COMMENT 'thread/message/etc',
  observed_ymdhis bigint NULL,
  expressed_as_rgb char(6) NULL COMMENT 'Legacy mood_rgb expression',
  PRIMARY KEY (link_id),
  INDEX idx_agent (agent_id),
  INDEX idx_star (star_id),
  INDEX idx_context (context_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE lupo_dialog_messages DROP COLUMN IF EXISTS mood_rgb;
ALTER TABLE lupo_dialog_threads DROP COLUMN IF EXISTS mood_rgb;
ALTER TABLE lupo_dialog_channels DROP COLUMN IF EXISTS mood_rgb;
ALTER TABLE lupo_channel_state DROP COLUMN IF EXISTS mood_rgb;
