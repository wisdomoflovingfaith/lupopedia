---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/database/lupopedia/tables/active/lupo_banned_actors.md
  web_path: https://www.lupopedia.com/lupopedia/docs/database/lupopedia/tables/active/lupo_banned_actors.md
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
- Source of truth: `database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
