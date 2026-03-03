---
flare.headers:
  file_path_from_root: "lupo-docs/database/lupopedia/tables/lupo_anubis_queue.md"
  system_version: "4.0.56"
  channel_id: 42
  actor_id: 1003
  last_modified_utc: "20260303"
  artifact_type: "table_documentation"
  purpose: "ANUBIS queue — work queue for files needing header/compliance processing by custodial intelligence"
  traits: ["database", "table", "anubis", "cursor"]
  tags: ["database", "table", "lupo_anubis_queue", "anubis", "queue"]
  lupo_agent: "cursor"

flare.edges:
  outbound_edges:
    - { to: "lupo-docs/toons/lupo_anubis_queue.toon.json", type: "schema_reference", weight: 1.0 }
    - { to: "lupo-docs/database/lupopedia/tables/lupo_anubis_processing_log.md", type: "references", weight: 0.9 }
    - { to: "lupo-docs/database/lupopedia/tables/lupo_anubis_quarantine.md", type: "references", weight: 0.9 }
    - { to: "lupo-docs/database/lupopedia/tables/lupo_actors.md", type: "references", weight: 0.5, reason: "assigned_to_actor_id" }
  semantic_tags: ["anubis", "queue", "custodial", "flare"]

flare.footer:
  last_verified_utc: "20260303"
  last_verified_by: "cursor"
---

# Database Documentation: lupo_anubis_queue

**Version:** 4.0.56  
**Date:** 2026-03-03

## 1. Overview

The `lupo_anubis_queue` table is the main work queue for ANUBIS (actor_id 19). Files that need FLARE/FLIP header completion, validation, or compliance processing are enqueued here. Each row represents one file with status (pending, processing, recovered, failed, quarantined), priority, detection method, optional content snapshot, and assignment to an ANUBIS instance.

**Doctrine:** No foreign keys or triggers; timestamps in UTC (e.g. YYYYMMDDHHIISS or bigint). Application logic links to `lupo_anubis_processing_log` and `lupo_anubis_quarantine`.

## 2. Schema (from TOON)

| Column | Type | Description |
|--------|------|-------------|
| `queue_id` | bigint NOT NULL | Primary key (auto_increment). |
| `file_path` | varchar(512) NOT NULL | Path to the file. |
| `file_hash` | varchar(64) | Optional hash. |
| `file_content` | longtext | Optional content snapshot. |
| `detected_utc` | bigint NOT NULL | When the item was enqueued (e.g. YYYYMMDDHHIISS). |
| `priority` | tinyint | 1–10, lower = higher priority (default 5). |
| `status` | varchar(32) | pending, processing, recovered, failed, quarantined (default pending). |
| `detection_method` | varchar(64) | e.g. missing_header, malformed_header, invalid_actor. |
| `header_snapshot` | text | Partial header if any. |
| `error_message` | text | Last error if failed. |
| `attempts` | tinyint | Number of processing attempts (default 0). |
| `last_attempt_utc` | bigint | When last attempted. |
| `assigned_to_actor_id` | bigint | ANUBIS instance processing this (e.g. 19). |
| `filesystem_copy_exists` | tinyint | Whether a filesystem copy exists (default 1). |
| `filesystem_backup_path` | varchar(512) | Optional backup path. |
| `created_utc` | bigint NOT NULL | Created timestamp. |
| `updated_utc` | bigint NOT NULL | Last update. |
| `is_deleted` | tinyint | Soft delete (default 0). |

## 3. Indexes

- `idx_detected` on `detected_utc`
- `idx_file_path` on `file_path`
- `idx_status_priority` on (`status`, `priority`)
- `uniq_file_hash` unique on `file_hash`

## 4. Primary key

- `queue_id`

---

*Documentation for TOON: lupo_anubis_queue.toon.json. Maintained by Cursor (1003).*
