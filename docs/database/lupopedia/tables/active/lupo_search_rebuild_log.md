---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/database/lupopedia/tables/active/lupo_search_rebuild_log.md
  web_path: https://www.lupopedia.com/lupopedia/docs/database/lupopedia/tables/active/lupo_search_rebuild_log.md
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
# file: lupo_search_rebuild_log.md

# lupo_search_rebuild_log

## Purpose
Canonical table documentation normalized from TOON JSON for `lupo_search_rebuild_log`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `search_rebuild_log_id` | `bigint NOT NULL` |
| `entity_type` | `varchar(50) NOT NULL` |
| `entity_id` | `bigint NOT NULL` |
| `action` | `varchar(64) NOT NULL` |
| `status` | `varchar(64) NOT NULL DEFAULT 'pending'` |
| `attempts` | `tinyint NOT NULL DEFAULT 0` |
| `last_error` | `text` |
| `created_ymdhis` | `bigint NOT NULL DEFAULT 0` |
| `processed_ymdhis` | `bigint` |
| `next_attempt_ymdhis` | `bigint` |
| `is_deleted` | `tinyint NOT NULL DEFAULT 0` |

### Indexes

| Index | Columns | Unique |
|---|---|---|
| `lupo_search_rebuild_log_idx_created` | `created_ymdhis` | no |
| `lupo_search_rebuild_log_idx_entity` | `entity_type`, `entity_id` | no |
| `lupo_search_rebuild_log_idx_status_retry` | `status`, `next_attempt_ymdhis` | no |
| `lupo_search_rebuild_log_unique_entity_operation` | `entity_type`, `entity_id`, `action` | yes |

## Doctrine
- Source of truth: `database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
