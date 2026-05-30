---
lupopedia.headers:
  header_format_version: null
  lupopedia.schema: null
  when_updated: "20260328013000"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_tickets.md"
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
# file: lupo_tickets.md

# lupo_tickets

## Purpose
Canonical table documentation normalized from TOON JSON for `lupo_tickets`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `ticket_id` | `bigint NOT NULL` |
| `channel_id` | `bigint NOT NULL` |
| `actor_id` | `bigint NOT NULL` |
| `status` | `varchar(64) NOT NULL DEFAULT 'open'` |
| `priority` | `varchar(64) NOT NULL DEFAULT 'medium'` |
| `subject` | `varchar(255) NOT NULL` |
| `created_ymdhis` | `bigint NOT NULL DEFAULT 0` |
| `updated_ymdhis` | `bigint NOT NULL DEFAULT 0` |
| `metadata_json` | `json` |
| `is_deleted` | `tinyint NOT NULL DEFAULT 0` |
| `deleted_ymdhis` | `bigint` |

### Indexes

| Index | Columns | Unique |
|---|---|---|
| `lupo_tickets_idx_actor` | `actor_id` | no |
| `lupo_tickets_idx_channel` | `channel_id` | no |
| `lupo_tickets_idx_status` | `status` | no |

## Doctrine
- Source of truth: `lupo-database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
