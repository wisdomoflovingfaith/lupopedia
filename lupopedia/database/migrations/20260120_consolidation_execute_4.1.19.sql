-- Version: 4.1.19
-- Purpose: Execute schema consolidation (dialog, analytics, truth, collections)
-- Date: 2026-01-20
-- Doctrine Table Ceiling: 180 (comment only; not enforced in SQL)

-- ======================================================================
-- A) DIALOG SYSTEM CONSOLIDATION
-- ======================================================================

CREATE TABLE lupo_unified_dialog_messages (
  dialog_message_id BIGINT NOT NULL,
  thread_id BIGINT,
  actor_id BIGINT,
  created_ymdhis BIGINT,
  updated_ymdhis BIGINT,
  metadata_json JSON,
  body_text LONGTEXT,
  PRIMARY KEY (dialog_message_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO lupo_unified_dialog_messages (
  dialog_message_id,
  thread_id,
  actor_id,
  created_ymdhis,
  updated_ymdhis,
  metadata_json,
  body_text
)
SELECT
  m.dialog_message_id,
  m.dialog_thread_id AS thread_id,
  m.from_actor_id AS actor_id,
  m.created_ymdhis,
  m.updated_ymdhis,
  m.metadata_json,
  b.full_text AS body_text
FROM lupo_dialog_messages m
LEFT JOIN lupo_dialog_message_bodies b ON m.dialog_message_id = b.dialog_message_id;

DROP TABLE IF EXISTS lupo_dialog_message_bodies;

-- ======================================================================
-- B) ANALYTICS CONSOLIDATION
-- ======================================================================

CREATE TABLE lupo_unified_analytics_paths (
  analytics_path_id BIGINT NOT NULL,
  period ENUM('daily','monthly') NOT NULL,
  visit_content_id BIGINT,
  exit_content_id BIGINT,
  metadata_json JSON,
  created_ymdhis BIGINT,
  PRIMARY KEY (analytics_path_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO lupo_unified_analytics_paths (
  analytics_path_id,
  period,
  visit_content_id,
  exit_content_id,
  metadata_json,
  created_ymdhis
)
SELECT
  analytics_paths_daily_id,
  'daily',
  visit_content_id,
  exit_content_id,
  NULL AS metadata_json,
  created_ymdhis
FROM lupo_analytics_paths_daily;

INSERT INTO lupo_unified_analytics_paths (
  analytics_path_id,
  period,
  visit_content_id,
  exit_content_id,
  metadata_json,
  created_ymdhis
)
SELECT
  analytics_paths_monthly_id + 1000000000,
  'monthly',
  visit_content_id,
  exit_content_id,
  NULL AS metadata_json,
  created_ymdhis
FROM lupo_analytics_paths_monthly;

DROP TABLE IF EXISTS lupo_analytics_paths_daily;
DROP TABLE IF EXISTS lupo_analytics_paths_monthly;

-- ======================================================================
-- C) TRUTH SYSTEM CONSOLIDATION
-- ======================================================================

CREATE TABLE lupo_unified_truth_items (
  truth_item_id BIGINT NOT NULL,
  item_type ENUM('question','answer') NOT NULL,
  name VARCHAR(255),
  slug VARCHAR(255),
  body_text LONGTEXT,
  metadata_json JSON,
  created_ymdhis BIGINT,
  PRIMARY KEY (truth_item_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO lupo_unified_truth_items (
  truth_item_id,
  item_type,
  name,
  slug,
  body_text,
  metadata_json,
  created_ymdhis
)
SELECT
  truth_question_id,
  'question',
  slug AS name,
  slug,
  question_text AS body_text,
  NULL AS metadata_json,
  created_ymdhis
FROM lupo_truth_questions;

INSERT INTO lupo_unified_truth_items (
  truth_item_id,
  item_type,
  name,
  slug,
  body_text,
  metadata_json,
  created_ymdhis
)
SELECT
  truth_answer_id + 2000000000,
  'answer',
  NULL AS name,
  NULL AS slug,
  answer_text AS body_text,
  NULL AS metadata_json,
  created_ymdhis
FROM lupo_truth_answers;

DROP TABLE IF EXISTS lupo_truth_questions;
DROP TABLE IF EXISTS lupo_truth_answers;

-- ======================================================================
-- D) COLLECTIONS CONSOLIDATION
-- ======================================================================

ALTER TABLE lupo_collections ADD COLUMN parent_id BIGINT NULL;

INSERT INTO lupo_collections (
  collection_id,
  federations_node_id,
  name,
  slug,
  parent_id,
  created_ymdhis,
  updated_ymdhis
)
SELECT
  collection_tab_id + 10000,
  federations_node_id,
  name,
  slug,
  IF(collection_tab_parent_id IS NOT NULL, collection_tab_parent_id + 10000, NULL) AS parent_id,
  created_ymdhis,
  updated_ymdhis
FROM lupo_collection_tabs;

DROP TABLE IF EXISTS lupo_collection_tabs;
