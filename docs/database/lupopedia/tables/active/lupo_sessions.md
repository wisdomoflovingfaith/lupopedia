---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/database/lupopedia/tables/active/lupo_sessions.md
  web_path: https://www.lupopedia.com/lupopedia/docs/database/lupopedia/tables/active/lupo_sessions.md
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
# file: lupo_sessions.md

# lupo_sessions

## Purpose
Canonical table documentation normalized from TOON JSON for `lupo_sessions`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `session_id` | `varchar(128) NOT NULL` |
| `actor_id` | `bigint NOT NULL` |
| `actor_name` | `varchar(64)` |
| `federation_node_id` | `bigint NOT NULL DEFAULT 0` |
| `ip_hash` | `varchar(128)` |
| `ua_hash` | `varchar(255)` |
| `csrf_token` | `varchar(128)` |
| `last_activity_ymdhis` | `bigint NOT NULL` |
| `created_ymdhis` | `bigint NOT NULL` |
| `updated_ymdhis` | `bigint NOT NULL` |
| `name_key` | `varchar(100)` |
| `is_named` | `tinyint NOT NULL DEFAULT 0` |
| `metadata` | `json` |
| `is_active` | `tinyint NOT NULL DEFAULT 1` |
| `is_expired` | `tinyint NOT NULL DEFAULT 0` |
| `is_revoked` | `tinyint NOT NULL DEFAULT 0` |
| `is_deleted` | `tinyint NOT NULL DEFAULT 0` |
| `last_seen_ymdhis` | `bigint` |
| `expires_ymdhis` | `bigint` |
| `security_level` | `varchar(64)` |
| `system_context` | `varchar(64)` |
| `status` | `varchar(32)` |

### Indexes

| Index | Columns | Unique |
|---|---|---|
| `lupo_sessions_idx_actor` | `actor_id` | no |
| `lupo_sessions_idx_actor_name` | `actor_name` | no |
| `lupo_sessions_idx_federation` | `federation_node_id` | no |
| `lupo_sessions_idx_is_active` | `is_active` | no |
| `lupo_sessions_idx_last_activity` | `last_activity_ymdhis` | no |
| `lupo_sessions_idx_last_seen` | `last_seen_ymdhis` | no |

## Doctrine
- Source of truth: `database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
