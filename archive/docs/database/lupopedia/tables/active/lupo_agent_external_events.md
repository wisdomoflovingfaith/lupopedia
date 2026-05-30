---
lupopedia.headers:
  header_format_version: null
  lupopedia.schema: null
  when_updated: "20260328013000"
  file_path_from_root: "docs/database/lupopedia/tables/active/lupo_agent_external_events.md"
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
# file: lupo_agent_external_events.md

# lupo_agent_external_events

## Purpose
Canonical table documentation normalized from TOON JSON for `lupo_agent_external_events`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `external_event_id` | `bigint NOT NULL` |
| `agent_name` | `varchar(255) NOT NULL` |
| `source_system` | `varchar(255) NOT NULL` |
| `event_type` | `varchar(50) NOT NULL` |
| `event_payload_json` | `json` |
| `created_ymdhis` | `bigint NOT NULL DEFAULT 0` |

### Indexes

| Index | Columns | Unique |
|---|---|---|

## Doctrine
- Source of truth: `database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
