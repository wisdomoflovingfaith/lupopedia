---
lupopedia.headers:
  header_format_version: null
  lupopedia.schema: null
  when_updated: "20260328013000"
  file_path_from_root: "docs/database/lupopedia/tables/active/lupo_crafty_syntax_layer_invites.md"
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
# file: lupo_crafty_syntax_layer_invites.md

# lupo_crafty_syntax_layer_invites

## Purpose
Canonical table documentation normalized from TOON JSON for `lupo_crafty_syntax_layer_invites`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `crafty_syntax_layer_invite_id` | `bigint NOT NULL auto_increment` |
| `layer_name` | `varchar(100) NOT NULL DEFAULT ''` |
| `image_name` | `varchar(255) NOT NULL DEFAULT ''` |
| `image_map` | `text` |
| `department_name` | `varchar(100) NOT NULL DEFAULT ''` |
| `user_id` | `bigint NOT NULL DEFAULT 0` |
| `is_active` | `tinyint NOT NULL DEFAULT 1` |
| `display_count` | `int NOT NULL DEFAULT 0` |
| `click_count` | `int NOT NULL DEFAULT 0` |
| `created_ymdhis` | `bigint NOT NULL DEFAULT 0` |
| `updated_ymdhis` | `bigint NOT NULL` |
| `is_deleted` | `tinyint NOT NULL DEFAULT 0` |
| `deleted_ymdhis` | `bigint` |

### Indexes

| Index | Columns | Unique |
|---|---|---|
| `lupo_crafty_syntax_layer_invites_idx_active` | `is_active` | no |
| `lupo_crafty_syntax_layer_invites_idx_created` | `created_ymdhis` | no |
| `lupo_crafty_syntax_layer_invites_idx_department` | `department_name` | no |
| `lupo_crafty_syntax_layer_invites_idx_name` | `layer_name` | no |
| `lupo_crafty_syntax_layer_invites_idx_updated` | `updated_ymdhis` | no |
| `lupo_crafty_syntax_layer_invites_idx_user` | `user_id` | no |

## Doctrine
- Source of truth: `database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
