---
lupopedia.headers:
  header_format_version: null
  lupopedia.schema: null
  when_updated: "20260328013000"
  file_path_from_root: "docs/database/lupopedia/tables/active/lupo_crm_lead_messages.md"
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
# file: lupo_crm_lead_messages.md

# lupo_crm_lead_messages

## Purpose
Canonical table documentation normalized from TOON JSON for `lupo_crm_lead_messages`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `crm_lead_message_id` | `bigint NOT NULL` |
| `lead_id` | `bigint` |
| `from_email` | `varchar(255)` |
| `subject` | `varchar(255)` |
| `body_text` | `text NOT NULL` |
| `notes` | `varchar(255)` |
| `actor_id` | `bigint` |
| `created_ymdhis` | `bigint NOT NULL DEFAULT 0` |
| `updated_ymdhis` | `bigint NOT NULL` |
| `is_deleted` | `smallint NOT NULL DEFAULT 0` |
| `deleted_ymdhis` | `bigint` |

### Indexes

| Index | Columns | Unique |
|---|---|---|
| `lupo_crm_lead_messages_actor_id` | `actor_id` | no |
| `lupo_crm_lead_messages_lead_id` | `lead_id` | no |

## Doctrine
- Source of truth: `database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
