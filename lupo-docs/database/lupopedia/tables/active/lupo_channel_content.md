---
lupopedia.headers:
  when_updated: "20260328013000"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_channel_content.md"
  last_modified_utc: "20260328013000"
  channel_id: 42
  actor_id: 23
  actor_name: "hephaestus"
  delegation_chain: "wolfie:hephaestus"
  artifact_type: "documentation"
  artifact_kind: "table"
  namespace: "channels"
  purpose: "Normalized table documentation for lupo_channel_content from TOON JSON"
  tags:
  - database
  - table
  - normalized
  - 4.0.88
lupopedia.edges:
  comment: "static placeholder edges for stage3 normalization"
  outbound_edges:
  - to: "lupo-database/lupopedia/json/lupo_channel_content.json"
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
# file: lupo_channel_content.md

# lupo_channel_content

## Purpose
Canonical table documentation normalized from TOON JSON for `lupo_channel_content`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `channel_content_id` | `bigint NOT NULL auto_increment` |
| `channel_id` | `bigint NOT NULL` |
| `federation_node_id` | `bigint NOT NULL` |
| `file_path` | `varchar(500) NOT NULL` |
| `web_path` | `varchar(500) NOT NULL` |
| `metadata_json` | `json` |
| `created_ymdhis` | `bigint NOT NULL DEFAULT 0` |
| `updated_ymdhis` | `bigint NOT NULL DEFAULT 0` |
| `is_deleted` | `tinyint NOT NULL DEFAULT 0` |

### Indexes

| Index | Columns | Unique |
|---|---|---|
| `lupo_channel_content_idx_channel` | `channel_id` | no |
| `lupo_channel_content_idx_created` | `created_ymdhis` | no |
| `lupo_channel_content_idx_federation_node` | `federation_node_id` | no |
| `lupo_channel_content_idx_file_path` | `file_path` | no |
| `lupo_channel_content_idx_is_deleted` | `is_deleted` | no |
| `lupo_channel_content_idx_updated` | `updated_ymdhis` | no |
| `lupo_channel_content_idx_web_path` | `web_path` | no |

## Doctrine
- Source of truth: `lupo-database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
