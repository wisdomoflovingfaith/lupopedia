---
lupopedia.headers:
  header_format_version: null
  lupopedia.schema: null
  when_updated: "20260328013000"
  file_path_from_root: "docs/database/lupopedia/tables/active/lupo_actor_handshakes.md"
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
# file: lupo_actor_handshakes.md

# lupo_actor_handshakes

## Purpose
Canonical table documentation normalized from TOON JSON for `lupo_actor_handshakes`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `actor_handshake_id` | `bigint NOT NULL` |
| `actor_id` | `bigint NOT NULL` |
| `actor_type` | `varchar(32) NOT NULL` |
| `utc_timestamp` | `bigint NOT NULL` |
| `purpose` | `varchar(500)` |
| `constraints_json` | `json` |
| `forbidden_actions_json` | `json` |
| `context` | `text` |
| `expires_utc` | `bigint` |
| `created_ymdhis` | `bigint NOT NULL DEFAULT 0` |
| `is_deleted` | `tinyint NOT NULL DEFAULT 0` |
| `deleted_ymdhis` | `bigint` |

### Indexes

| Index | Columns | Unique |
|---|---|---|
| `lupo_actor_handshakes_idx_actor_id` | `actor_id` | no |
| `lupo_actor_handshakes_idx_is_deleted` | `is_deleted` | no |
| `lupo_actor_handshakes_idx_utc_timestamp` | `utc_timestamp` | no |

## Doctrine
- Source of truth: `database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
