---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/database/lupopedia/tables/active/lupo_ticket_messages.md
  web_path: https://www.lupopedia.com/lupopedia/docs/database/lupopedia/tables/active/lupo_ticket_messages.md
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
# file: lupo_ticket_messages.md

# lupo_ticket_messages

## Purpose
Canonical table documentation normalized from TOON JSON for `lupo_ticket_messages`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `ticket_message_id` | `bigint NOT NULL` |
| `ticket_id` | `bigint NOT NULL` |
| `actor_id` | `bigint NOT NULL` |
| `message_text` | `text NOT NULL` |
| `created_ymdhis` | `bigint NOT NULL DEFAULT 0` |
| `is_deleted` | `tinyint NOT NULL DEFAULT 0` |
| `deleted_ymdhis` | `bigint` |

### Indexes

| Index | Columns | Unique |
|---|---|---|
| `lupo_ticket_messages_idx_ticket` | `ticket_id` | no |

## Doctrine
- Source of truth: `database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
