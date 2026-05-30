---
lupopedia.headers:
  header_format_version: null
  lupopedia.schema: null
  when_updated: "20260328013000"
  file_path_from_root: "docs/database/lupopedia/tables/active/lupo_agent_heartbeats.md"
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
# file: lupo_agent_heartbeats.md

# lupo_agent_heartbeats

## Purpose
Canonical table documentation normalized from TOON JSON for `lupo_agent_heartbeats`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `heartbeat_id` | `bigint NOT NULL` |
| `agent_slug` | `varchar(64) NOT NULL` |
| `status` | `varchar(32) NOT NULL DEFAULT 'unknown'` |
| `last_heartbeat_ymdhis` | `bigint NOT NULL` |
| `created_ymdhis` | `bigint NOT NULL DEFAULT 0` |
| `updated_ymdhis` | `bigint NOT NULL DEFAULT 0` |
| `is_deleted` | `tinyint NOT NULL DEFAULT 0` |
| `deleted_ymdhis` | `bigint` |

### Indexes

| Index | Columns | Unique |
|---|---|---|
| `lupo_agent_heartbeats_idx_agent_slug` | `agent_slug` | no |
| `lupo_agent_heartbeats_idx_created_ymdhis` | `created_ymdhis` | no |
| `lupo_agent_heartbeats_idx_is_deleted` | `is_deleted` | no |
| `lupo_agent_heartbeats_idx_last_heartbeat_ymdhis` | `last_heartbeat_ymdhis` | no |

## Doctrine
- Source of truth: `database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
