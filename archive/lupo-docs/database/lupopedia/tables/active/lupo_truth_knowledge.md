---
lupopedia.schema: table_documentation
file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_truth_knowledge.md"
web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/database/lupopedia/tables/active/lupo_truth_knowledge.md"
last_modified_utc: "20260329080000"
actor_id: 2
actor_name: "lilith"
purpose: "DEPRECATED: See lupo_truth_questions, lupo_truth_answers, lupo_truth_evidence, lupo_truth_context_map, lupo_truth_followers."
tags:
  - "database"
  - "schema"
  - "truth_system"
  - "deprecated"
  - "lilith_audited"
---

# DEPRECATED: lupo_truth_knowledge

> **This table is deprecated.**
>
> Please see the new split truth schema:
> - lupo_truth_questions
> - lupo_truth_answers
> - lupo_truth_evidence
> - lupo_truth_context_map
> - lupo_truth_followers

All new truth data is stored in the new tables. This file is retained for migration reference only.

---
lupopedia.headers:
  when_updated: "20260328013000"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_truth_knowledge.md"
  last_modified_utc: "20260328013000"
  channel_id: 42
  actor_id: 23
  actor_name: "hephaestus"
  delegation_chain: "wolfie:hephaestus"
  artifact_type: "documentation"
  artifact_kind: "table"
  namespace: "core"
  purpose: "Normalized table documentation for lupo_truth_knowledge from TOON JSON"
  tags:
  - database
  - table
  - normalized
  - 4.0.88
lupopedia.edges:
  comment: "static placeholder edges for stage3 normalization"
  outbound_edges:
  - to: "lupo-database/lupopedia/json/lupo_truth_knowledge.json"
    type: "references"
    weight: 1.0
    reason: "authoritative TOON JSON source"
lupopedia.footer:
  last_verified: "20260328013000"
  last_verified_by: "hephaestus"
  last_verified_by_actor_id: 23
  generated: true
  provenance: "stage3_track_c_normalization"
---
# file: lupo_truth_knowledge.md

# lupo_truth_knowledge

## Purpose
Canonical table documentation normalized from TOON JSON for `lupo_truth_knowledge`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `truth_id` | `bigint NOT NULL` |
| `truth_type` | `varchar(32) NOT NULL` |
| `parent_id` | `bigint` |
| `question_id` | `bigint` |
| `answer_id` | `bigint` |
| `evidence_id` | `bigint` |
| `source_id` | `bigint` |
| `topic_id` | `bigint` |
| `relation_id` | `bigint` |
| `actor_id` | `bigint NOT NULL DEFAULT 0` |
| `object_type` | `varchar(50)` |
| `object_id` | `bigint` |
| `left_object_type` | `varchar(50)` |
| `left_object_id` | `bigint` |
| `right_object_type` | `varchar(50)` |
| `right_object_id` | `bigint` |
| `slug` | `varchar(255)` |
| `title` | `varchar(255)` |
| `text_content` | `text` |
| `question_text` | `text` |
| `answer_text` | `text` |
| `evidence_text` | `text` |
| `source_url` | `text` |
| `source_title` | `varchar(255) DEFAULT ''` |
| `qtype` | `varchar(50) DEFAULT 'unknown'` |
| `status` | `varchar(64) DEFAULT 'active'` |
| `evidence_type` | `varchar(50) DEFAULT ''` |
| `source_type` | `varchar(50) DEFAULT ''` |
| `relation_type` | `varchar(50) DEFAULT ''` |
| `format` | `varchar(64) DEFAULT 'text'` |
| `format_override` | `varchar(50)` |
| `confidence_score` | `decimal(5,2) DEFAULT 0.00` |
| `evidence_score` | `decimal(5,2) DEFAULT 0.00` |
| `weight_score` | `decimal(5,2) DEFAULT 0.00` |
| `reliability_score` | `decimal(5,2) DEFAULT 0.00` |
| `importance_score` | `decimal(5,2) DEFAULT 0.00` |
| `sort_num` | `int DEFAULT 0` |
| `view_count` | `bigint DEFAULT 0` |
| `likes_count` | `bigint DEFAULT 0` |
| `shares_count` | `bigint DEFAULT 0` |
| `answer_count` | `bigint DEFAULT 0` |
| `contradiction_flag` | `tinyint DEFAULT 0` |
| `is_featured` | `tinyint DEFAULT 0` |
| `is_verified` | `tinyint DEFAULT 0` |
| `last_activity_ymdhis` | `bigint` |
| `default_collection_id` | `bigint DEFAULT 0` |
| `created_ymdhis` | `bigint NOT NULL DEFAULT 0` |
| `updated_ymdhis` | `bigint NOT NULL` |
| `is_deleted` | `tinyint NOT NULL DEFAULT 0` |
| `deleted_ymdhis` | `bigint` |
| `truth_question_parent_id` | `bigint` |

### Indexes

| Index | Columns | Unique |
|---|---|---|
| `lupo_truth_knowledge_idx_actor` | `actor_id` | no |
| `lupo_truth_knowledge_idx_answer` | `answer_id` | no |
| `lupo_truth_knowledge_idx_created_ymdhis` | `created_ymdhis`, `is_deleted` | no |
| `lupo_truth_knowledge_idx_evidence` | `evidence_id` | no |
| `lupo_truth_knowledge_idx_is_deleted` | `is_deleted` | no |
| `lupo_truth_knowledge_idx_left_object` | `left_object_type`, `left_object_id` | no |
| `lupo_truth_knowledge_idx_object` | `object_type`, `object_id` | no |
| `lupo_truth_knowledge_idx_parent` | `parent_id` | no |
| `lupo_truth_knowledge_idx_question` | `question_id` | no |
| `lupo_truth_knowledge_idx_right_object` | `right_object_type`, `right_object_id` | no |
| `lupo_truth_knowledge_idx_source` | `source_id` | no |
| `lupo_truth_knowledge_idx_status` | `status` | no |
| `lupo_truth_knowledge_idx_topic` | `topic_id` | no |
| `lupo_truth_knowledge_idx_type` | `truth_type` | no |
| `lupo_truth_knowledge_idx_updated_ymdhis` | `updated_ymdhis` | no |
| `lupo_truth_knowledge_uk_type_slug` | `truth_type`, `slug` | yes |

## Doctrine
- Source of truth: `lupo-database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
