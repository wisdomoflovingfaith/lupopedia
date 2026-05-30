---
lupopedia.headers:
  header_format_version: null
  lupopedia.schema: null
  when_updated: "20260328013000"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_hashtags.md"
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
# file: lupo_hashtags.md

# lupo_hashtags

## Purpose
Canonical table documentation normalized from TOON JSON for `lupo_hashtags`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `hashtag_id` | `bigint NOT NULL auto_increment` |
| `tag_slug` | `varchar(128) NOT NULL` |
| `label` | `varchar(255)` |
| `use_count` | `bigint NOT NULL DEFAULT 0` |
| `created_ymdhis` | `bigint NOT NULL DEFAULT 0` |
| `updated_ymdhis` | `bigint NOT NULL DEFAULT 0` |
| `is_deleted` | `tinyint NOT NULL DEFAULT 0` |
| `deleted_ymdhis` | `bigint` |

### Indexes

| Index | Columns | Unique |
|---|---|---|
| `lupo_hashtags_idx_is_deleted` | `is_deleted` | no |
| `lupo_hashtags_idx_use_count` | `use_count` | no |
| `lupo_hashtags_uniq_slug` | `tag_slug` | yes |

## Doctrine
- Source of truth: `lupo-database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
