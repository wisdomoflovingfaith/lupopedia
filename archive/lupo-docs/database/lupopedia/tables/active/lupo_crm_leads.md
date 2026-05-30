---
lupopedia.headers:
  header_format_version: null
  lupopedia.schema: null
  when_updated: "20260328013000"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_crm_leads.md"
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
- Source of truth: `lupo-database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
