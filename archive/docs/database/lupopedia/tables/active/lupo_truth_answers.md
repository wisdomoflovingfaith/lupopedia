---
lupopedia.headers:
  header_format_version: null
  lupopedia.schema: null
  when_updated: "20260328013000"
  file_path_from_root: "docs/database/lupopedia/tables/active/lupo_truth_answers.md"
  web_path: null
  questions_toon: null
  federation_node_id: null
  channel_key: null
  trust_tier: null
  memory_key: null
  artifact_type: documentation
  artifact_kind: table
  thread_id: ""
  content_id: null
  pk_id: null
  pk_slug: ""
  title: ""
  status: ""
  parent_pk_id: ""
  summary: ""
  module: null
  dialog_transcript: null
---
# file: lupo_truth_answers.md

# lupo_truth_answers

## Purpose
Canonical table documentation normalized from TOON JSON for `lupo_truth_answers`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `truth_answer_id` | `bigint NOT NULL auto_increment` |
| `truth_question_id` | `bigint NOT NULL` |
| `actor_id` | `bigint NOT NULL` |
| `answer_text` | `text` |
| `confidence` | `decimal(5,2) DEFAULT 0.00` |
| `evidence_count` | `int DEFAULT 0` |
| `source_count` | `int DEFAULT 0` |
| `status` | `varchar(64) DEFAULT 'active'` |
| `created_ymdhis` | `bigint NOT NULL DEFAULT 0` |
| `updated_ymdhis` | `bigint NOT NULL` |
| `is_deleted` | `tinyint NOT NULL DEFAULT 0` |
| `deleted_ymdhis` | `bigint` |
| `evidence_score` | `decimal(5,2) DEFAULT 0.00` |
| `contradiction_flag` | `tinyint DEFAULT 0` |
| `likes_count` | `bigint DEFAULT 0` |

### Indexes

| Index | Columns | Unique |
|---|---|---|
| `lupo_truth_answers_idx_actor` | `actor_id` | no |
| `lupo_truth_answers_idx_created` | `created_ymdhis` | no |
| `lupo_truth_answers_idx_question` | `truth_question_id` | no |
| `lupo_truth_answers_idx_status` | `status` | no |

## Doctrine
- Source of truth: `database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
