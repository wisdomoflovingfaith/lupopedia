---
lupopedia.headers:
  when_updated: "20260328013000"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_legacy_content_mapping.md"
  last_modified_utc: "20260328013000"
  channel_id: 42
  actor_id: 23
  actor_name: "hephaestus"
  delegation_chain: "wolfie:hephaestus"
  artifact_type: "documentation"
  artifact_kind: "table"
  namespace: "core"
  purpose: "Normalized table documentation for lupo_legacy_content_mapping from TOON JSON"
  tags:
  - database
  - table
  - normalized
  - 4.0.88
lupopedia.edges:
  comment: "static placeholder edges for stage3 normalization"
  outbound_edges:
  - to: "lupo-database/lupopedia/json/lupo_legacy_content_mapping.json"
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
# file: lupo_legacy_content_mapping.md

# lupo_legacy_content_mapping

## Purpose
Canonical table documentation normalized from TOON JSON for `lupo_legacy_content_mapping`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `mapping_id` | `bigint NOT NULL` |
| `legacy_url` | `varchar(255) NOT NULL` |
| `semantic_url` | `varchar(255) NOT NULL` |
| `content_type` | `varchar(64) NOT NULL` |
| `content_id` | `bigint` |
| `created_ymdhis` | `bigint NOT NULL DEFAULT 0` |
| `updated_ymdhis` | `bigint NOT NULL` |
| `is_active` | `tinyint NOT NULL DEFAULT 1` |

### Indexes

| Index | Columns | Unique |
|---|---|---|
| `lupo_legacy_content_mapping_idx_content_id` | `content_id` | no |
| `lupo_legacy_content_mapping_idx_content_type` | `content_type` | no |
| `lupo_legacy_content_mapping_idx_created` | `created_ymdhis` | no |
| `lupo_legacy_content_mapping_idx_is_active` | `is_active` | no |
| `lupo_legacy_content_mapping_idx_semantic_url` | `semantic_url` | no |
| `lupo_legacy_content_mapping_uk_legacy_url` | `legacy_url` | yes |

## Doctrine
- Source of truth: `lupo-database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
