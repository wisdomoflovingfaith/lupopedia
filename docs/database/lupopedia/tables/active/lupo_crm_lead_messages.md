---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/database/lupopedia/tables/active/lupo_crm_lead_messages.md
  web_path: https://www.lupopedia.com/lupopedia/docs/database/lupopedia/tables/active/lupo_crm_lead_messages.md
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
