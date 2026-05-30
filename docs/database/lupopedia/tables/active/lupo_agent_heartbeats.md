---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/database/lupopedia/tables/active/lupo_agent_heartbeats.md
  web_path: https://www.lupopedia.com/lupopedia/docs/database/lupopedia/tables/active/lupo_agent_heartbeats.md
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
