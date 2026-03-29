---
lupopedia.headers:
  when_updated: "20260328013000"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_collection_tabs.md"
  last_modified_utc: "20260328013000"
  channel_id: 42
  actor_id: 23
  actor_name: "hephaestus"
  delegation_chain: "wolfie:hephaestus"
  artifact_type: "documentation"
  artifact_kind: "table"
  namespace: "core"
  purpose: "Normalized table documentation for lupo_collection_tabs from TOON JSON"
  tags:
  - database
  - table
  - normalized
  - 4.0.88
lupopedia.edges:
  comment: "static placeholder edges for stage3 normalization"
  outbound_edges:
  - to: "lupo-database/lupopedia/json/lupo_collection_tabs.json"
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
# file: lupo_collection_tabs.md

# lupo_collection_tabs

## Purpose
Canonical table documentation normalized from TOON JSON for `lupo_collection_tabs`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `collection_tab_id` | `bigint NOT NULL auto_increment` |
| `collection_tab_parent_id` | `bigint` |
| `collection_id` | `bigint NOT NULL` |
| `federations_node_id` | `bigint NOT NULL` |
| `department_id` | `bigint` |
| `actor_id` | `bigint` |
| `sort_order` | `int DEFAULT 0` |
| `name` | `varchar(255) NOT NULL` |
| `slug` | `varchar(100) NOT NULL` |
| `color` | `char(6) DEFAULT '4caf50'` |
| `description` | `text` |
| `is_hidden` | `tinyint NOT NULL DEFAULT 0` |
| `visibility_rule` | `text` |
| `tab_type` | `varchar(32)` |
| `created_ymdhis` | `bigint NOT NULL DEFAULT 0` |
| `updated_ymdhis` | `bigint NOT NULL` |
| `is_active` | `tinyint NOT NULL DEFAULT 1` |
| `is_deleted` | `tinyint NOT NULL DEFAULT 0` |
| `deleted_ymdhis` | `bigint` |

### Indexes

| Index | Columns | Unique |
|---|---|---|
| `lupo_collection_tabs_idx_actor_id` | `actor_id` | no |
| `lupo_collection_tabs_idx_collection_id` | `collection_id` | no |
| `lupo_collection_tabs_idx_department` | `department_id` | no |
| `lupo_collection_tabs_idx_is_active` | `is_active` | no |
| `lupo_collection_tabs_idx_parent_tab_id` | `collection_tab_parent_id` | no |
| `lupo_collection_tabs_idx_slug` | `slug` | no |

## Doctrine
- Source of truth: `lupo-database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
