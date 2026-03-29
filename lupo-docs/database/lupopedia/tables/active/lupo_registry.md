---
lupopedia.headers:
  when_updated: "20260328013000"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_registry.md"
  last_modified_utc: "20260328013000"
  channel_id: 42
  actor_id: 23
  actor_name: "hephaestus"
  delegation_chain: "wolfie:hephaestus"
  artifact_type: "documentation"
  artifact_kind: "table"
  namespace: "core"
  purpose: "Normalized table documentation for lupo_registry from TOON JSON"
  tags:
  - database
  - table
  - normalized
  - 4.0.88
lupopedia.edges:
  comment: "static placeholder edges for stage3 normalization"
  outbound_edges:
  - to: "lupo-database/lupopedia/json/lupo_registry.json"
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
# file: lupo_registry.md

# lupo_registry

## Purpose
Canonical table documentation normalized from TOON JSON for `lupo_registry`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `registry_id` | `bigint NOT NULL auto_increment` |
| `entity_type` | `varchar(50) NOT NULL` |
| `entity_index_id` | `bigint NOT NULL DEFAULT 0` |
| `entity_index` | `bigint NOT NULL DEFAULT 0` |
| `federation_node_id` | `bigint NOT NULL DEFAULT 0` |
| `reserved_ymdhis` | `bigint NOT NULL DEFAULT 0` |
| `metadata` | `text` |
| `entity_key` | `varchar(255)` |
| `entity_name` | `varchar(255)` |
| `entity_table` | `varchar(255)` |
| `created_ymdhis` | `bigint NOT NULL DEFAULT 0` |
| `updated_ymdhis` | `bigint NOT NULL DEFAULT 0` |
| `is_deleted` | `tinyint NOT NULL DEFAULT 0` |
| `deleted_ymdhis` | `bigint` |
| `is_active` | `tinyint NOT NULL DEFAULT 1` |
| `is_kernel` | `tinyint NOT NULL DEFAULT 0` |
| `metadata_json` | `text` |

### Indexes

| Index | Columns | Unique |
|---|---|---|
| `idx_registry_entity_type` | `entity_type` | no |
| `idx_registry_federation_node` | `federation_node_id` | no |
| `idx_registry_unique` | `entity_type`, `entity_index_id`, `federation_node_id` | yes |

## Doctrine
- Source of truth: `lupo-database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
