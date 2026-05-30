---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/database/lupopedia/tables/active/lupo_channel_escalations.md
  web_path: https://www.lupopedia.com/lupopedia/docs/database/lupopedia/tables/active/lupo_channel_escalations.md
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
# file: lupo_channel_escalations.md

# lupo_channel_escalations

## Purpose
Canonical table documentation normalized from TOON JSON for `lupo_channel_escalations`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `escalation_id` | `bigint NOT NULL` |
| `channel_id` | `bigint NOT NULL` |
| `thread_id` | `bigint` |
| `actor_id` | `bigint` |
| `escalated_to_actor_id` | `bigint` |
| `escalation_reason` | `varchar(512)` |
| `metadata_json` | `json` |
| `created_ymdhis` | `bigint NOT NULL DEFAULT 0` |
| `updated_ymdhis` | `bigint NOT NULL` |
| `is_deleted` | `tinyint NOT NULL DEFAULT 0` |
| `deleted_ymdhis` | `bigint` |

### Indexes

| Index | Columns | Unique |
|---|---|---|
| `lupo_channel_escalations_idx_actor_id` | `actor_id` | no |
| `lupo_channel_escalations_idx_channel_id` | `channel_id` | no |
| `lupo_channel_escalations_idx_escalated_to_actor_id` | `escalated_to_actor_id` | no |
| `lupo_channel_escalations_idx_thread_id` | `thread_id` | no |

## Doctrine
- Source of truth: `database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
