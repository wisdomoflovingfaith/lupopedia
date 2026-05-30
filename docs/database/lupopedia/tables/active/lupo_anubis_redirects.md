---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/database/lupopedia/tables/active/lupo_anubis_redirects.md
  web_path: https://www.lupopedia.com/lupopedia/docs/database/lupopedia/tables/active/lupo_anubis_redirects.md
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
- Source of truth: `database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
