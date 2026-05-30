---
lupopedia.headers:
  header_format_version: null
  lupopedia.schema: null
  when_updated: "20260328013000"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_banned_actors.md"
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
# file: lupo_banned_actors.md

# lupo_banned_actors

## Purpose
Canonical table documentation normalized from TOON JSON for `lupo_banned_actors`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `banned_actor_id` | `bigint NOT NULL` |
| `actor_id` | `bigint NOT NULL` |
| `actor_name` | `varchar(64)` |
| `ip_address` | `varchar(45)` |
| `reason` | `varchar(500) NOT NULL` |
| `banned_ymdhis` | `bigint NOT NULL` |
| `banned_by_actor_id` | `bigint` |
| `created_ymdhis` | `bigint NOT NULL DEFAULT 0` |
| `updated_ymdhis` | `bigint NOT NULL` |
| `is_deleted` | `tinyint NOT NULL DEFAULT 0` |
| `deleted_ymdhis` | `bigint` |

### Indexes

| Index | Columns | Unique |
|---|---|---|
| `lupo_banned_actors_idx_actor_id` | `actor_id` | no |
| `lupo_banned_actors_idx_actor_name` | `actor_name` | no |
| `lupo_banned_actors_idx_ip_address` | `ip_address` | no |
| `lupo_banned_actors_idx_is_deleted` | `is_deleted` | no |

## Doctrine
- Source of truth: `lupo-database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
