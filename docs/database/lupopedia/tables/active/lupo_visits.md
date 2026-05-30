---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/database/lupopedia/tables/active/lupo_visits.md
  web_path: https://www.lupopedia.com/lupopedia/docs/database/lupopedia/tables/active/lupo_visits.md
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
# file: lupo_visits.md

# lupo_visits

## Purpose
Canonical table documentation normalized from TOON JSON for `lupo_visits`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `visit_id` | `bigint NOT NULL auto_increment` |
| `session_id` | `bigint` |
| `actor_id` | `bigint` |
| `instance_id` | `bigint` |
| `path_url` | `text` |
| `entercontentid` | `bigint` |
| `exitcontentid` | `bigint` |
| `enter_table` | `varchar(255)` |
| `exit_table` | `varchar(255)` |
| `transition_type` | `varchar(64)` |
| `transition_metadata` | `text` |
| `created_ymdhis` | `bigint NOT NULL DEFAULT 0` |
| `is_processed` | `tinyint NOT NULL DEFAULT 0` |
| `is_deleted` | `tinyint NOT NULL DEFAULT 0` |
| `deleted_ymdhis` | `bigint` |

### Indexes

| Index | Columns | Unique |
|---|---|---|
| `lupo_visits_idx_actor` | `actor_id` | no |
| `lupo_visits_idx_created` | `created_ymdhis` | no |
| `lupo_visits_idx_enter_exit` | `entercontentid`, `exitcontentid` | no |
| `lupo_visits_idx_is_deleted` | `is_deleted` | no |
| `lupo_visits_idx_is_processed` | `is_processed` | no |
| `lupo_visits_idx_session` | `session_id` | no |

## Doctrine
- Source of truth: `database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
