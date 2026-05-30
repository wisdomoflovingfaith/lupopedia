---
lupopedia.headers:
  header_format_version: null
  lupopedia.schema: null
  when_updated: "20260328013000"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_visits.md"
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
- Source of truth: `lupo-database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
