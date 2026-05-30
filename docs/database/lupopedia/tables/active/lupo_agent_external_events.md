---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/database/lupopedia/tables/active/lupo_agent_external_events.md
  web_path: https://www.lupopedia.com/lupopedia/docs/database/lupopedia/tables/active/lupo_agent_external_events.md
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
