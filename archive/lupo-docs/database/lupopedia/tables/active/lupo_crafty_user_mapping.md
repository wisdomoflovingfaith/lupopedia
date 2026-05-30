---
lupopedia.headers:
  header_format_version: null
  lupopedia.schema: null
  when_updated: "20260328013000"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_crafty_user_mapping.md"
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
# file: lupo_crafty_user_mapping.md

# lupo_crafty_user_mapping

## Purpose
Canonical table documentation normalized from TOON JSON for `lupo_crafty_user_mapping`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `crafty_user_mapping_id` | `bigint NOT NULL auto_increment` |
| `lupo_user_id` | `bigint` |
| `crafty_operator_id` | `int` |
| `mapping_type` | `varchar(50) NOT NULL DEFAULT 'manual'` |
| `notes` | `text` |
| `created_at` | `bigint NOT NULL DEFAULT 0` |
| `updated_at` | `bigint NOT NULL DEFAULT 0` |

### Indexes

| Index | Columns | Unique |
|---|---|---|
| `lupo_crafty_user_mapping_idx_crafty_operator_id` | `crafty_operator_id` | no |
| `lupo_crafty_user_mapping_idx_lupo_user_id` | `lupo_user_id` | no |
| `lupo_crafty_user_mapping_idx_mapping_type` | `mapping_type` | no |
| `lupo_crafty_user_mapping_unique_crafty_operator_mapping` | `crafty_operator_id` | yes |
| `lupo_crafty_user_mapping_unique_lupo_user_mapping` | `lupo_user_id` | yes |

## Doctrine
- Source of truth: `lupo-database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
