---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/database/lupopedia/tables/active/lupo_anubis_recovery_attempts.md
  web_path: https://www.lupopedia.com/lupopedia/docs/database/lupopedia/tables/active/lupo_anubis_recovery_attempts.md
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
- Source of truth: `database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
