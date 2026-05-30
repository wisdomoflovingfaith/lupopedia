---
lupopedia.headers:
  header_format_version: null
  lupopedia.schema: null
  when_updated: "20260328013000"
  file_path_from_root: "docs/database/lupopedia/tables/active/lupo_bans_log.md"
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
# file: lupo_bans_log.md

# lupo_bans_log

## Purpose
Canonical table documentation normalized from TOON JSON for `lupo_bans_log`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `bans_log_id` | `bigint NOT NULL auto_increment` |
| `actor_id` | `bigint NOT NULL` |
| `uri` | `varchar(1024) NOT NULL DEFAULT ''` |
| `resolved_uri` | `varchar(1024) NOT NULL DEFAULT ''` |
| `ban_scope` | `varchar(64) NOT NULL DEFAULT 'router'` |
| `banned_ymdhis` | `bigint NOT NULL` |
| `user_agent` | `varchar(500)` |
| `ip_address` | `varchar(45)` |

### Indexes

| Index | Columns | Unique |
|---|---|---|
| `lupo_bans_log_idx_actor_id` | `actor_id` | no |
| `lupo_bans_log_idx_ban_scope` | `ban_scope` | no |
| `lupo_bans_log_idx_banned_ymdhis` | `banned_ymdhis` | no |

## Doctrine
- Source of truth: `database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
