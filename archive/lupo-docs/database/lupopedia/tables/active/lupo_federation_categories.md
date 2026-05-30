---
lupopedia.headers:
  header_format_version: null
  lupopedia.schema: null
  when_updated: "20260328013000"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_federation_categories.md"
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
# file: lupo_federation_categories.md

# lupo_federation_categories

## Purpose
Canonical table documentation normalized from TOON JSON for `lupo_federation_categories`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `federation_category_id` | `bigint NOT NULL` |
| `category_name` | `varchar(255) NOT NULL` |
| `category_slug` | `varchar(255) NOT NULL` |
| `category_description` | `text` |
| `meta_json` | `json` |
| `is_deleted` | `tinyint NOT NULL DEFAULT 0` |
| `deleted_ymdhis` | `bigint NOT NULL DEFAULT 0` |
| `created_ymdhis` | `bigint NOT NULL DEFAULT 0` |
| `updated_ymdhis` | `bigint NOT NULL DEFAULT 0` |

### Indexes

| Index | Columns | Unique |
|---|---|---|
| `lupo_federation_categories_idx_category_slug` | `category_slug` | no |
| `lupo_federation_categories_idx_is_deleted` | `is_deleted` | no |

## Doctrine
- Source of truth: `lupo-database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
