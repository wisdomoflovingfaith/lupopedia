---
lupopedia.headers:
  when_updated: "20260328013000"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_crafty_user_mapping.md"
  last_modified_utc: "20260328013000"
  channel_id: 42
  actor_id: 23
  actor_name: "hephaestus"
  delegation_chain: "wolfie:hephaestus"
  artifact_type: "documentation"
  artifact_kind: "table"
  namespace: "legacy"
  purpose: "Normalized table documentation for lupo_crafty_user_mapping from TOON JSON"
  tags:
  - database
  - table
  - normalized
  - 4.0.88
lupopedia.edges:
  comment: "static placeholder edges for stage3 normalization"
  outbound_edges:
  - to: "lupo-database/lupopedia/json/lupo_crafty_user_mapping.json"
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
