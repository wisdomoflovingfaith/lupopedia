---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/database/lupopedia/tables/active/lupo_hashtags.md
  web_path: https://www.lupopedia.com/lupopedia/docs/database/lupopedia/tables/active/lupo_hashtags.md
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
- Source of truth: `database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
