---
lupopedia.headers:
  header_format_version: null
  lupopedia.schema: null
  when_updated: "20260328013000"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_legacy_content_mapping.md"
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
# file: lupo_legacy_content_mapping.md

# lupo_legacy_content_mapping

## Purpose
Canonical table documentation normalized from TOON JSON for `lupo_legacy_content_mapping`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `mapping_id` | `bigint NOT NULL` |
| `legacy_url` | `varchar(255) NOT NULL` |
| `semantic_url` | `varchar(255) NOT NULL` |
| `content_type` | `varchar(64) NOT NULL` |
| `content_id` | `bigint` |
| `created_ymdhis` | `bigint NOT NULL DEFAULT 0` |
| `updated_ymdhis` | `bigint NOT NULL` |
| `is_active` | `tinyint NOT NULL DEFAULT 1` |

### Indexes

| Index | Columns | Unique |
|---|---|---|
| `lupo_legacy_content_mapping_idx_content_id` | `content_id` | no |
| `lupo_legacy_content_mapping_idx_content_type` | `content_type` | no |
| `lupo_legacy_content_mapping_idx_created` | `created_ymdhis` | no |
| `lupo_legacy_content_mapping_idx_is_active` | `is_active` | no |
| `lupo_legacy_content_mapping_idx_semantic_url` | `semantic_url` | no |
| `lupo_legacy_content_mapping_uk_legacy_url` | `legacy_url` | yes |

## Doctrine
- Source of truth: `lupo-database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
