---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/database/lupopedia/tables/active/lupo_crm_leads.md
  web_path: https://www.lupopedia.com/lupopedia/docs/database/lupopedia/tables/active/lupo_crm_leads.md
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
# file: lupo_crm_leads.md

# lupo_crm_leads

## Purpose
Canonical table documentation normalized from TOON JSON for `lupo_crm_leads`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `crm_lead_id` | `bigint NOT NULL` |
| `email` | `varchar(255)` |
| `phone` | `varchar(45)` |
| `first_name` | `varchar(100)` |
| `last_name` | `varchar(100)` |
| `source` | `varchar(100)` |
| `status` | `varchar(50) NOT NULL DEFAULT 'new'` |
| `lead_score` | `int NOT NULL DEFAULT 0` |
| `assigned_to` | `bigint` |
| `lead_data` | `text` |
| `created_ymdhis` | `bigint NOT NULL DEFAULT 0` |
| `updated_ymdhis` | `bigint NOT NULL` |
| `is_deleted` | `tinyint NOT NULL DEFAULT 0` |
| `deleted_ymdhis` | `bigint` |

### Indexes

| Index | Columns | Unique |
|---|---|---|

## Doctrine
- Source of truth: `database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
