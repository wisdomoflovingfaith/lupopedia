---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/database/lupopedia/tables/active/lupo_truth_answers.md
  web_path: https://www.lupopedia.com/lupopedia/docs/database/lupopedia/tables/active/lupo_truth_answers.md
  status: ''
  when_updated: '20260513033046'
  trust_tier: null
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: documentation
  artifact_kind: table
  channel_key: null
  federation_node_id: null
  thread_key: ''
  lupopedia.schema: null
  prd_cluster: null
  title: ''
  summary: ''
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
