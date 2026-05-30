---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/database/lupopedia/tables/active/lupo_anubis_queue.md
  web_path: https://www.lupopedia.com/lupopedia/docs/database/lupopedia/tables/active/lupo_anubis_queue.md
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
# file: lupo_anubis_queue.md

# lupo_anubis_queue

## Purpose
Canonical table documentation normalized from TOON JSON for `lupo_anubis_queue`.

## `file_path` semantics (portable identity)

- **Preferred stored value:** path **relative to the Lupopedia project root** (directory containing `scripts/`), **forward slashes**, no leading slash — e.g. `docs/prd/16_lupopedia_headers.md`. This is independent of the **public URL** subfolder name (`/lupopedia/`, `/wiki/`, etc.).
- **Runtime:** `ANUBIS_QueueProcessor` in `includes/classes/ANUBIS/QueueProcessor.php` resolves disk access with `LUPOPEDIA_PATH` + stored path; `addToQueue()` normalizes absolute paths under the project root to repo-relative form.
- **Legacy:** rows may contain absolute paths; resolver still attempts filesystem access for those.

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
- Source of truth: `database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
