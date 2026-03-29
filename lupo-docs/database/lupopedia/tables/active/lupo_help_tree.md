---
lupopedia.headers:
  when_updated: "20260328013000"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_help_tree.md"
  last_modified_utc: "20260328013000"
  channel_id: 42
  actor_id: 23
  actor_name: "hephaestus"
  delegation_chain: "wolfie:hephaestus"
  artifact_type: "documentation"
  artifact_kind: "table"
  namespace: "core"
  purpose: "Normalized table documentation for lupo_help_tree from TOON JSON"
  tags:
  - database
  - table
  - normalized
  - 4.0.88
lupopedia.edges:
  comment: "static placeholder edges for stage3 normalization"
  outbound_edges:
  - to: "lupo-database/lupopedia/json/lupo_help_tree.json"
    type: "references"
    weight: 1.0
    reason: "authoritative TOON JSON source"
lupopedia.footer:
  last_verified: "20260328013000"
  last_verified_by: "hephaestus"
  last_verified_by_actor_id: 23
  generated: true
  provenance: "stage3_track_c_normalization"
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
- Source of truth: `lupo-database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
