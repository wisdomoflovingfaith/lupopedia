---
lupopedia.headers:
  header_format_version: null
  lupopedia.schema: null
  when_updated: "20260328013000"
  file_path_from_root: "docs/database/lupopedia/tables/active/lupo_anubis_quarantine.md"
  web_path: null
  questions_toon: null
  federation_node_id: null
  channel_key: null
  trust_tier: null
  memory_key: null
  artifact_type: documentation
  artifact_kind: table
  thread_id: ""
  content_id: null
  pk_id: null
  pk_slug: ""
  title: ""
  status: ""
  parent_pk_id: ""
  summary: ""
  module: null
  dialog_transcript: null
---
# file: lupo_anubis_quarantine.md

# lupo_anubis_quarantine

## Purpose
Canonical table documentation normalized from TOON JSON for `lupo_anubis_quarantine`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `quarantine_id` | `bigint NOT NULL auto_increment` |
| `queue_id` | `bigint NOT NULL` |
| `file_path` | `varchar(512) NOT NULL` |
| `file_hash` | `varchar(64)` |
| `file_content` | `longtext` |
| `quarantine_path` | `varchar(512) NOT NULL` |
| `reason` | `varchar(255) NOT NULL` |
| `quarantined_utc` | `bigint NOT NULL` |
| `expires_utc` | `bigint` |
| `reviewed_by_actor_id` | `bigint` |
| `reviewed_utc` | `bigint` |
| `resolution` | `varchar(64)` |
| `is_deleted` | `tinyint DEFAULT 0` |

### Indexes

| Index | Columns | Unique |
|---|---|---|
| `lupo_anubis_quarantine_idx_expires` | `expires_utc` | no |
| `lupo_anubis_quarantine_idx_queue` | `queue_id` | no |

## Doctrine
- Source of truth: `database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
