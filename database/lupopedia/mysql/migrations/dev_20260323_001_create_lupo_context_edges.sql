-- dev_20260323_001_create_lupo_context_edges.sql
-- Purpose: create canonical edge storage table for context graph relationships
-- Doctrine constraints: no AUTO_INCREMENT, no ENUM, BIGINT timestamps only, soft delete only,
-- no foreign keys, no triggers, no DB-side logic
-- Reference: ATHENA plan (Channel 60 / thread agent-system-design / TG-1)

CREATE TABLE lupo_context_edges (
  edge_id BIGINT PRIMARY KEY,

  source_type VARCHAR(64) NOT NULL,
  source_id BIGINT NOT NULL,

  target_type VARCHAR(64) NOT NULL,
  target_id BIGINT NOT NULL,

  edge_type VARCHAR(64) NOT NULL,

  metadata_json TEXT,

  created_ymdhis BIGINT NOT NULL,
  updated_ymdhis BIGINT NOT NULL,

  is_deleted TINYINT DEFAULT 0,
  deleted_ymdhis BIGINT DEFAULT 0
);

CREATE INDEX idx_source ON lupo_context_edges (source_type, source_id);
CREATE INDEX idx_target ON lupo_context_edges (target_type, target_id);
CREATE INDEX idx_type ON lupo_context_edges (edge_type);
CREATE INDEX idx_created ON lupo_context_edges (created_ymdhis);
