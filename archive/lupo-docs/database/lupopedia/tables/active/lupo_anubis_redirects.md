---
lupopedia.headers:
  header_format_version: null
  lupopedia.schema: null
  when_updated: "20260328013000"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_anubis_redirects.md"
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
# file: lupo_anubis_redirects.md

# lupo_anubis_redirects

## Purpose
Canonical table documentation normalized from TOON JSON for `lupo_anubis_redirects`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `anubis_redirect_id` | `bigint NOT NULL` |
| `table_name` | `varchar(255) NOT NULL` |
| `old_id` | `bigint NOT NULL` |
| `new_id` | `bigint NOT NULL` |
| `created_ymdhis` | `bigint NOT NULL` |
| `updated_ymdhis` | `bigint NOT NULL` |
| `agent` | `varchar(255) NOT NULL` |

### Indexes

| Index | Columns | Unique |
|---|---|---|

## Doctrine
- Source of truth: `lupo-database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
