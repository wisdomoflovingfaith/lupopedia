---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/database/lupopedia/tables/active/lupo_crafty_syntax_layer_invites.md
  web_path: https://www.lupopedia.com/lupopedia/docs/database/lupopedia/tables/active/lupo_crafty_syntax_layer_invites.md
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
