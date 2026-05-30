---
lupopedia.headers:
  header_format_version: null
  lupopedia.schema: null
  when_updated: "20260328013000"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_channel_escalations.md"
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
- Source of truth: `lupo-database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
