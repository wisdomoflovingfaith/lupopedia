---
lupopedia.headers:
  when_updated: "20260328013000"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_anubis_queue.md"
  last_modified_utc: "20260328013000"
  channel_id: 42
  actor_id: 23
  actor_name: "hephaestus"
  delegation_chain: "wolfie:hephaestus"
  artifact_type: "documentation"
  artifact_kind: "table"
  namespace: "core"
  purpose: "Normalized table documentation for lupo_anubis_queue from TOON JSON"
  tags:
  - database
  - table
  - normalized
  - 4.0.88
lupopedia.edges:
  comment: "static placeholder edges for stage3 normalization"
  outbound_edges:
  - to: "lupo-database/lupopedia/json/lupo_anubis_queue.json"
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
# file: lupo_anubis_queue.md

# lupo_anubis_queue

## Purpose
Canonical table documentation normalized from TOON JSON for `lupo_anubis_queue`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `queue_id` | `bigint NOT NULL auto_increment` |
| `file_path` | `varchar(512) NOT NULL` |
| `file_hash` | `varchar(64)` |
| `file_content` | `longtext` |
| `detected_utc` | `bigint NOT NULL` |
| `priority` | `tinyint DEFAULT 5` |
| `status` | `varchar(32) DEFAULT 'pending'` |
| `detection_method` | `varchar(64)` |
| `header_snapshot` | `text` |
| `error_message` | `text` |
| `attempts` | `tinyint DEFAULT 0` |
| `last_attempt_utc` | `bigint` |
| `assigned_to_actor_id` | `bigint` |
| `filesystem_copy_exists` | `tinyint DEFAULT 1` |
| `filesystem_backup_path` | `varchar(512)` |
| `created_utc` | `bigint NOT NULL` |
| `updated_utc` | `bigint NOT NULL` |
| `is_deleted` | `tinyint DEFAULT 0` |

### Indexes

| Index | Columns | Unique |
|---|---|---|
| `lupo_anubis_queue_idx_detected` | `detected_utc` | no |
| `lupo_anubis_queue_idx_file_path` | `file_path` | no |
| `lupo_anubis_queue_idx_status_priority` | `status`, `priority` | no |
| `lupo_anubis_queue_uniq_file_hash` | `file_hash` | yes |

## Doctrine
- Source of truth: `lupo-database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
