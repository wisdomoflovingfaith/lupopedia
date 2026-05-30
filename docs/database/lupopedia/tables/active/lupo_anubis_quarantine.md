---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/database/lupopedia/tables/active/lupo_anubis_quarantine.md
  web_path: https://www.lupopedia.com/lupopedia/docs/database/lupopedia/tables/active/lupo_anubis_quarantine.md
  status: ''
  when_updated: '20260513033046'
  trust_tier: null
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: documentation
  artifact_kind: table
  channel_key: null
  federation_node_id: null
  thread_key: ''
  lupopedia.schema: null
  prd_cluster: null
  title: ''
  summary: ''
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
