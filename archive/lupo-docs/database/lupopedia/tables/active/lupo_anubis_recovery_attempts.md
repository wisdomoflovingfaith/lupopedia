---
lupopedia.headers:
  header_format_version: null
  lupopedia.schema: null
  when_updated: "20260328013000"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_anubis_recovery_attempts.md"
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
# file: lupo_anubis_recovery_attempts.md

# lupo_anubis_recovery_attempts

## Purpose
Canonical table documentation normalized from TOON JSON for `lupo_anubis_recovery_attempts`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `attempt_id` | `bigint NOT NULL auto_increment` |
| `queue_id` | `bigint NOT NULL` |
| `attempt_number` | `tinyint NOT NULL` |
| `attempt_utc` | `bigint NOT NULL` |
| `strategy` | `varchar(64)` |
| `success` | `tinyint DEFAULT 0` |
| `generated_header` | `text` |
| `error_details` | `text` |
| `recovered_file_path` | `varchar(512)` |

### Indexes

| Index | Columns | Unique |
|---|---|---|
| `lupo_anubis_recovery_attempts_idx_queue_attempt` | `queue_id`, `attempt_number` | no |

## Doctrine
- Source of truth: `lupo-database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
