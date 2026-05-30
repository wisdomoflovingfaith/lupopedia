---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/database/lupopedia/tables/active/lupo_interpretation_log.md
  web_path: https://www.lupopedia.com/lupopedia/docs/database/lupopedia/tables/active/lupo_interpretation_log.md
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
# file: lupo_interpretation_log.md

# lupo_interpretation_log

## Purpose
Canonical table documentation normalized from TOON JSON for `lupo_interpretation_log`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `interpretation_log_id` | `bigint NOT NULL` |
| `agent_id` | `bigint NOT NULL` |
| `entity_type` | `varchar(32) NOT NULL` |
| `entity_id` | `bigint NOT NULL` |
| `interpretation` | `text NOT NULL` |
| `confidence_score` | `decimal(5,2)` |
| `created_ymdhis` | `bigint NOT NULL DEFAULT 0` |
| `updated_ymdhis` | `bigint NOT NULL` |
| `is_deleted` | `tinyint NOT NULL DEFAULT 0` |
| `deleted_ymdhis` | `bigint` |
| `metadata_json` | `json` |

### Indexes

| Index | Columns | Unique |
|---|---|---|
| `lupo_interpretation_log_idx_agent` | `agent_id` | no |
| `lupo_interpretation_log_idx_confidence` | `confidence_score` | no |
| `lupo_interpretation_log_idx_created` | `created_ymdhis` | no |
| `lupo_interpretation_log_idx_deleted` | `is_deleted` | no |
| `lupo_interpretation_log_idx_entity` | `entity_type`, `entity_id` | no |
| `lupo_interpretation_log_idx_updated` | `updated_ymdhis` | no |

## Doctrine
- Source of truth: `database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
