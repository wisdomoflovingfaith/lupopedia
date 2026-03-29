---
lupopedia.headers:
  when_updated: "20260328013000"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_crafty_syntax_layer_invites.md"
  last_modified_utc: "20260328013000"
  channel_id: 42
  actor_id: 23
  actor_name: "hephaestus"
  delegation_chain: "wolfie:hephaestus"
  artifact_type: "documentation"
  artifact_kind: "table"
  namespace: "legacy"
  purpose: "Normalized table documentation for lupo_crafty_syntax_layer_invites from TOON JSON"
  tags:
  - database
  - table
  - normalized
  - 4.0.88
lupopedia.edges:
  comment: "static placeholder edges for stage3 normalization"
  outbound_edges:
  - to: "lupo-database/lupopedia/json/lupo_crafty_syntax_layer_invites.json"
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
- Source of truth: `lupo-database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
