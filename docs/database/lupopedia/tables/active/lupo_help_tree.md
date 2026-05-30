---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/database/lupopedia/tables/active/lupo_help_tree.md
  web_path: https://www.lupopedia.com/lupopedia/docs/database/lupopedia/tables/active/lupo_help_tree.md
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
