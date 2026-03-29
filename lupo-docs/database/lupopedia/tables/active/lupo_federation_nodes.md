---
lupopedia.headers:
  when_updated: "20260328013000"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_federation_nodes.md"
  last_modified_utc: "20260328013000"
  channel_id: 42
  actor_id: 23
  actor_name: "hephaestus"
  delegation_chain: "wolfie:hephaestus"
  artifact_type: "documentation"
  artifact_kind: "table"
  namespace: "federation"
  purpose: "Normalized table documentation for lupo_federation_nodes from TOON JSON"
  tags:
  - database
  - table
  - normalized
  - 4.0.88
lupopedia.edges:
  comment: "static placeholder edges for stage3 normalization"
  outbound_edges:
  - to: "lupo-database/lupopedia/json/lupo_federation_nodes.json"
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
# file: lupo_federation_nodes.md

# lupo_federation_nodes

## Purpose
Canonical table documentation normalized from TOON JSON for `lupo_federation_nodes`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `federation_node_id` | `bigint NOT NULL` |
| `node_type` | `varchar(32) NOT NULL DEFAULT 'local'` |
| `node_base_url` | `varchar(500) NOT NULL` |
| `default_department_id` | `bigint` |
| `node_name` | `varchar(255)` |
| `node_description` | `text` |
| `allows_foreign_traits` | `tinyint NOT NULL DEFAULT 1` |
| `node_contact` | `varchar(255)` |
| `meta_json` | `json` |
| `content_count` | `bigint NOT NULL DEFAULT 0` |
| `atom_count` | `bigint NOT NULL DEFAULT 0` |
| `hashtag_count` | `bigint NOT NULL DEFAULT 0` |
| `actor_count` | `bigint NOT NULL DEFAULT 0` |
| `last_sync_ymdhis` | `bigint NOT NULL DEFAULT 0` |
| `trust_level` | `tinyint NOT NULL DEFAULT 0` |
| `status` | `tinyint NOT NULL DEFAULT 1` |
| `is_deleted` | `tinyint NOT NULL DEFAULT 0` |
| `deleted_ymdhis` | `bigint NOT NULL DEFAULT 0` |
| `created_ymdhis` | `bigint NOT NULL DEFAULT 0` |
| `updated_ymdhis` | `bigint NOT NULL DEFAULT 0` |
| `active_theme_slug` | `varchar(64) DEFAULT 'default'` |

### Indexes

| Index | Columns | Unique |
|---|---|---|
| `lupo_federation_nodes_idx_is_deleted` | `is_deleted` | no |
| `lupo_federation_nodes_idx_node_base_url` | `node_base_url` | no |
| `lupo_federation_nodes_idx_status` | `status` | no |
| `lupo_federation_nodes_idx_trust_level` | `trust_level` | no |

## Doctrine
- Source of truth: `lupo-database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
