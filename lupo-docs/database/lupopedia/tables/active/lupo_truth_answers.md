---
lupopedia.headers:
  when_updated: "20260328013000"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_truth_answers.md"
  last_modified_utc: "20260328013000"
  channel_id: 42
  actor_id: 23
  actor_name: "hephaestus"
  delegation_chain: "wolfie:hephaestus"
  artifact_type: "documentation"
  artifact_kind: "table"
  namespace: "core"
  purpose: "Normalized table documentation for lupo_truth_answers from TOON JSON"
  tags:
  - database
  - table
  - normalized
  - 4.0.88
lupopedia.edges:
  comment: "static placeholder edges for stage3 normalization"
  outbound_edges:
  - to: "lupo-database/lupopedia/json/lupo_truth_answers.json"
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
- Source of truth: `lupo-database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
