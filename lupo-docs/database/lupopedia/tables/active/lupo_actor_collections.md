---
lupopedia.headers:
  when_updated: "20260328013000"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_actor_collections.md"
  last_modified_utc: "20260328013000"
  channel_id: 42
  actor_id: 23
  actor_name: "hephaestus"
  delegation_chain: "wolfie:hephaestus"
  artifact_type: "documentation"
  artifact_kind: "table"
  namespace: "core"
  purpose: "Normalized table documentation for lupo_actor_collections from TOON JSON"
  tags:
  - database
  - table
  - normalized
  - 4.0.88
lupopedia.edges:
  comment: "static placeholder edges for stage3 normalization"
  outbound_edges:
  - to: "lupo-database/lupopedia/json/lupo_actor_collections.json"
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
# file: lupo_actor_collections.md

# lupo_actor_collections

## Purpose
Canonical table documentation normalized from TOON JSON for `lupo_actor_collections`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `actor_collection_id` | `bigint NOT NULL` |
| `actor_id` | `bigint NOT NULL` |
| `collection_id` | `bigint NOT NULL` |
| `access_level` | `varchar(64) NOT NULL DEFAULT 'read'` |
| `created_ymdhis` | `bigint NOT NULL DEFAULT 0` |
| `updated_ymdhis` | `bigint` |
| `is_deleted` | `tinyint NOT NULL DEFAULT 0` |
| `deleted_ymdhis` | `bigint` |
| `persistent_identity_json` | `json` |
| `identity_signature` | `varchar(255)` |
| `trust_level` | `varchar(64) DEFAULT 'standard'` |
| `emotional_geometry_baseline` | `json` |
| `doctrine_alignment_version` | `varchar(20) DEFAULT '3.0.0'` |

### Indexes

| Index | Columns | Unique |
|---|---|---|
| `lupo_actor_collections_idx_access_level` | `access_level` | no |
| `lupo_actor_collections_idx_actor` | `actor_id` | no |
| `lupo_actor_collections_idx_collection` | `collection_id` | no |
| `lupo_actor_collections_idx_created_ymdhis` | `created_ymdhis` | no |
| `lupo_actor_collections_idx_identity_signature` | `identity_signature` | no |
| `lupo_actor_collections_idx_is_deleted` | `is_deleted` | no |
| `lupo_actor_collections_idx_trust_level` | `trust_level` | no |

## Doctrine
- Source of truth: `lupo-database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
