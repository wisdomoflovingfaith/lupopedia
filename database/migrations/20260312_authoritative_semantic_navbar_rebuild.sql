-- Authoritative Semantic Navbar Backend Rebuild (4.0.71)
-- Adds missing tables identified in audit: lupo_paths_summary, lupo_reference_map, lupo_collection_links, lupo_collection_map, lupo_edge_types, lupo_edge_map, lupo_questions, lupo_answers, lupo_question_map
-- Enforces doctrine: BIGINT timestamps, no FKs, application-level logic.

-- Previous Pages Summary
CREATE TABLE IF NOT EXISTS lupo_paths_summary (
  summary_id bigint NOT NULL AUTO_INCREMENT,
  path_id bigint NOT NULL,
  total_count bigint NOT NULL DEFAULT 0,
  last_used_ymdhis bigint NOT NULL DEFAULT 0,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL DEFAULT 0,
  PRIMARY KEY (summary_id)
);
CREATE INDEX lupo_paths_summary_idx_path ON lupo_paths_summary (path_id);

-- Reference Map (Explicit mapping table)
CREATE TABLE IF NOT EXISTS lupo_reference_map (
  reference_map_id bigint NOT NULL AUTO_INCREMENT,
  reference_id bigint NOT NULL,
  target_type varchar(64) NOT NULL,
  target_id bigint NOT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  is_deleted tinyint NOT NULL DEFAULT 0,
  PRIMARY KEY (reference_map_id)
);
CREATE INDEX lupo_reference_map_idx_reference ON lupo_reference_map (reference_id);
CREATE INDEX lupo_reference_map_idx_target ON lupo_reference_map (target_type, target_id);

-- Collection Links (Explicit link objects within collections)
CREATE TABLE IF NOT EXISTS lupo_collection_links (
  collection_link_id bigint NOT NULL AUTO_INCREMENT,
  collection_id bigint NOT NULL,
  link_url varchar(2000) NOT NULL,
  link_label varchar(255) DEFAULT NULL,
  sort_order int NOT NULL DEFAULT 0,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL DEFAULT 0,
  is_deleted tinyint NOT NULL DEFAULT 0,
  PRIMARY KEY (collection_link_id)
);
CREATE INDEX lupo_collection_links_idx_collection ON lupo_collection_links (collection_id);

-- Collection Map (Mapping collections to multiple objects)
CREATE TABLE IF NOT EXISTS lupo_collection_map (
  collection_map_id bigint NOT NULL AUTO_INCREMENT,
  collection_id bigint NOT NULL,
  object_type varchar(64) NOT NULL,
  object_id bigint NOT NULL,
  sort_order int NOT NULL DEFAULT 0,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  is_deleted tinyint NOT NULL DEFAULT 0,
  PRIMARY KEY (collection_map_id)
);
CREATE INDEX lupo_collection_map_idx_collection ON lupo_collection_map (collection_id);
CREATE INDEX lupo_collection_map_idx_object ON lupo_collection_map (object_type, object_id);

-- Edge Types (Definitions for semantic edges)
CREATE TABLE IF NOT EXISTS lupo_edge_types (
  edge_type_id bigint NOT NULL AUTO_INCREMENT,
  slug varchar(64) NOT NULL,
  label varchar(128) NOT NULL,
  description text,
  is_bidirectional tinyint NOT NULL DEFAULT 0,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL DEFAULT 0,
  is_deleted tinyint NOT NULL DEFAULT 0,
  PRIMARY KEY (edge_type_id)
);
CREATE UNIQUE INDEX lupo_edge_types_uniq_slug ON lupo_edge_types (slug);

-- Edge Map (Mapping edges between objects)
CREATE TABLE IF NOT EXISTS lupo_edge_map (
  edge_map_id bigint NOT NULL AUTO_INCREMENT,
  edge_id bigint NOT NULL,
  edge_type_id bigint NOT NULL,
  source_type varchar(64) NOT NULL,
  source_id bigint NOT NULL,
  target_type varchar(64) NOT NULL,
  target_id bigint NOT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  is_deleted tinyint NOT NULL DEFAULT 0,
  PRIMARY KEY (edge_map_id)
);
CREATE INDEX lupo_edge_map_idx_edge ON lupo_edge_map (edge_id);
CREATE INDEX lupo_edge_map_idx_type ON lupo_edge_map (edge_type_id);
CREATE INDEX lupo_edge_map_idx_source ON lupo_edge_map (source_type, source_id);
CREATE INDEX lupo_edge_map_idx_target ON lupo_edge_map (target_type, target_id);

-- Questions (Semantic Q/A)
CREATE TABLE IF NOT EXISTS lupo_questions (
  question_id bigint NOT NULL AUTO_INCREMENT,
  slug varchar(128) NOT NULL,
  question_text text NOT NULL,
  actor_id bigint DEFAULT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL DEFAULT 0,
  is_deleted tinyint NOT NULL DEFAULT 0,
  PRIMARY KEY (question_id)
);
CREATE UNIQUE INDEX lupo_questions_uniq_slug ON lupo_questions (slug);

-- Answers (Semantic Q/A)
CREATE TABLE IF NOT EXISTS lupo_answers (
  answer_id bigint NOT NULL AUTO_INCREMENT,
  question_id bigint NOT NULL,
  answer_text text NOT NULL,
  actor_id bigint DEFAULT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL DEFAULT 0,
  is_deleted tinyint NOT NULL DEFAULT 0,
  PRIMARY KEY (answer_id)
);
CREATE INDEX lupo_answers_idx_question ON lupo_answers (question_id);

-- Question Map (Mapping questions to objects/contexts)
CREATE TABLE IF NOT EXISTS lupo_question_map (
  question_map_id bigint NOT NULL AUTO_INCREMENT,
  question_id bigint NOT NULL,
  object_type varchar(64) NOT NULL,
  object_id bigint NOT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  is_deleted tinyint NOT NULL DEFAULT 0,
  PRIMARY KEY (question_map_id)
);
CREATE INDEX lupo_question_map_idx_question ON lupo_question_map (question_id);
CREATE INDEX lupo_question_map_idx_object ON lupo_question_map (object_type, object_id);
