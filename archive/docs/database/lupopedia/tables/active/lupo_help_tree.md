---
lupopedia.headers:
  header_format_version: null
  lupopedia.schema: null
  when_updated: "20260328013000"
  file_path_from_root: "docs/database/lupopedia/tables/active/lupo_help_tree.md"
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
# file: lupo_help_tree.md

# lupo_help_tree

## Purpose
Canonical table documentation normalized from TOON JSON for `lupo_help_tree`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `help_tree_id` | `bigint NOT NULL` |
| `parent_id` | `bigint` |
| `department_id` | `bigint NOT NULL DEFAULT 1` |
| `content_id` | `bigint` |
| `title` | `varchar(255) NOT NULL` |
| `description` | `text` |
| `action_type` | `varchar(64) NOT NULL DEFAULT 'none'` |
| `action_target` | `varchar(255)` |
| `sort_order` | `int NOT NULL DEFAULT 0` |
| `created_ymdhis` | `bigint NOT NULL DEFAULT 0` |
| `updated_ymdhis` | `bigint NOT NULL` |
| `is_deleted` | `tinyint NOT NULL DEFAULT 0` |
| `deleted_ymdhis` | `bigint` |

### Indexes

| Index | Columns | Unique |
|---|---|---|
| `lupo_help_tree_idx_action` | `action_type`, `action_target` | no |
| `lupo_help_tree_idx_content` | `content_id` | no |
| `lupo_help_tree_idx_created` | `created_ymdhis` | no |
| `lupo_help_tree_idx_department` | `department_id` | no |
| `lupo_help_tree_idx_parent` | `parent_id` | no |
| `lupo_help_tree_idx_sort` | `parent_id`, `sort_order` | no |
| `lupo_help_tree_idx_updated` | `updated_ymdhis` | no |

## Doctrine
- Source of truth: `database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
