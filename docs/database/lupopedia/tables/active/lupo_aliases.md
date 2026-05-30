---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/database/lupopedia/tables/active/lupo_aliases.md
  web_path: https://www.lupopedia.com/lupopedia/docs/database/lupopedia/tables/active/lupo_aliases.md
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
# file: lupo_aliases.md

# lupo_aliases

## Purpose
Canonical table documentation normalized from TOON JSON for `lupo_aliases`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `alias_id` | `bigint NOT NULL` |
| `slug` | `varchar(255) NOT NULL` |
| `alias` | `varchar(255) NOT NULL` |
| `alias_type` | `varchar(50) DEFAULT 'semantic'` |
| `created_ymdhis` | `bigint NOT NULL DEFAULT 0` |

### Indexes

| Index | Columns | Unique |
|---|---|---|
| `lupo_aliases_idx_slug` | `slug` | no |
| `lupo_aliases_uniq_alias` | `alias` | yes |

## Doctrine
- Source of truth: `database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
