---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/database/lupopedia/tables/active/lupo_tickets.md
  web_path: https://www.lupopedia.com/lupopedia/docs/database/lupopedia/tables/active/lupo_tickets.md
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
- Source of truth: `database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
