---
lupopedia.headers:
  when_updated: "20260328013000"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_dialog_channels.md"
  last_modified_utc: "20260328013000"
  channel_id: 42
  actor_id: 23
  actor_name: "hephaestus"
  delegation_chain: "wolfie:hephaestus"
  artifact_type: "documentation"
  artifact_kind: "table"
  namespace: "core"
  purpose: "Normalized table documentation for lupo_dialog_channels from TOON JSON"
  tags:
  - database
  - table
  - normalized
  - 4.0.88
lupopedia.edges:
  comment: "static placeholder edges for stage3 normalization"
  outbound_edges:
  - to: "lupo-database/lupopedia/json/lupo_dialog_channels.json"
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
# file: lupo_dialog_channels.md

# lupo_dialog_channels

## Purpose
Canonical table documentation normalized from TOON JSON for `lupo_dialog_channels`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `channel_id` | `bigint NOT NULL` |
| `channel_name` | `varchar(255) NOT NULL` |
| `file_source` | `varchar(255) NOT NULL` |
| `title` | `varchar(500)` |
| `description` | `text` |
| `speaker` | `varchar(100)` |
| `target` | `varchar(100)` |
| `categories` | `json` |
| `collections` | `json` |
| `channels` | `json` |
| `tags` | `json` |
| `version` | `varchar(20)` |
| `status` | `varchar(64) DEFAULT 'published'` |
| `author` | `varchar(100)` |
| `created_ymdhis` | `bigint NOT NULL DEFAULT 0` |
| `updated_ymdhis` | `bigint NOT NULL` |
| `message_count` | `int DEFAULT 0` |
| `metadata_json` | `json` |

### Indexes

| Index | Columns | Unique |
|---|---|---|
| `lupo_dialog_channels_idx_channel_name` | `channel_name` | yes |
| `lupo_dialog_channels_idx_created_ymdhis` | `created_ymdhis` | no |
| `lupo_dialog_channels_idx_dialog_channels_composite` | `status`, `created_ymdhis` | no |
| `lupo_dialog_channels_idx_file_source` | `file_source` | no |
| `lupo_dialog_channels_idx_speaker` | `speaker` | no |
| `lupo_dialog_channels_idx_status` | `status` | no |
| `lupo_dialog_channels_idx_target` | `target` | no |
| `lupo_dialog_channels_idx_updated_ymdhis` | `updated_ymdhis` | no |

## Doctrine
- Source of truth: `lupo-database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
