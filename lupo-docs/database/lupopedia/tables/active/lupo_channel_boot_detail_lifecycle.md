---
lupopedia.headers:
  when_updated: "20260328013000"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_channel_boot_detail_lifecycle.md"
  last_modified_utc: "20260328013000"
  channel_id: 42
  actor_id: 23
  actor_name: "hephaestus"
  delegation_chain: "wolfie:hephaestus"
  artifact_type: "documentation"
  artifact_kind: "table"
  namespace: "channels"
  purpose: "Normalized table documentation for lupo_channel_boot_detail_lifecycle from TOON JSON"
  tags:
  - database
  - table
  - normalized
  - 4.0.88
lupopedia.edges:
  comment: "static placeholder edges for stage3 normalization"
  outbound_edges:
  - to: "lupo-database/lupopedia/json/lupo_channel_boot_detail_lifecycle.json"
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
# file: lupo_channel_boot_detail_lifecycle.md

# lupo_channel_boot_detail_lifecycle

## Purpose
Canonical table documentation normalized from TOON JSON for `lupo_channel_boot_detail_lifecycle`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `detail_lifecycle_id` | `bigint NOT NULL auto_increment` |
| `lifecycle_id` | `bigint NOT NULL` |
| `channel_id` | `bigint NOT NULL` |
| `detail_start_time` | `bigint NOT NULL` |
| `detail_end_time` | `bigint` |
| `detail_status` | `varchar(64) NOT NULL DEFAULT 'started'` |
| `content_items_loaded` | `int NOT NULL DEFAULT 0` |
| `total_content_items` | `int NOT NULL DEFAULT 0` |
| `detail_duration_ms` | `int` |
| `error_message` | `text` |
| `created_ymdhis` | `bigint NOT NULL DEFAULT 0` |

### Indexes

| Index | Columns | Unique |
|---|---|---|
| `lupo_channel_boot_detail_lifecycle_fk_detail_lifecycle` | `lifecycle_id` | no |
| `lupo_channel_boot_detail_lifecycle_idx_channel` | `channel_id` | no |
| `lupo_channel_boot_detail_lifecycle_idx_status_time` | `detail_status`, `detail_start_time` | no |

## Doctrine
- Source of truth: `lupo-database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
