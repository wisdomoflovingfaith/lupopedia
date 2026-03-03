---
flare.headers:
  file_path_from_root: "lupo-docs/database/lupopedia/tables/lupo_anubis_processing_log.md"
  system_version: "4.0.56"
  channel_id: 42
  actor_id: 1003
  last_modified_utc: "20260303"
  artifact_type: "table_documentation"
  purpose: "ANUBIS processing log — per-queue processing outcomes (recovered, failed, quarantined, retry)"
  traits: ["database", "table", "anubis", "cursor"]
  tags: ["database", "table", "lupo_anubis_processing_log", "anubis"]
  lupo_agent: "cursor"

flare.edges:
  outbound_edges:
    - { to: "lupo-docs/toons/lupo_anubis_processing_log.toon.json", type: "schema_reference", weight: 1.0 }
    - { to: "lupo-docs/database/lupopedia/tables/lupo_anubis_queue.md", type: "references", weight: 0.9 }
  semantic_tags: ["anubis", "processing_log", "custodial"]

flare.footer:
  last_verified_utc: "20260303"
  last_verified_by: "cursor"
---

# Database Documentation: lupo_anubis_processing_log

**Version:** 4.0.56  
**Date:** 2026-03-03

## 1. Overview

The `lupo_anubis_processing_log` table records the outcome of each ANUBIS processing run for a queued item. Each row corresponds to one processing attempt (recovered, failed, quarantined, retry) for a file referenced by `lupo_anubis_queue`. Used for audit and debugging of custodial intelligence (actor_id 19).

**Doctrine:** No foreign keys or triggers; application-level linkage to `lupo_anubis_queue` via `queue_id`.

## 2. Schema (from TOON)

| Column | Type | Description |
|--------|------|-------------|
| `log_id` | bigint NOT NULL | Primary key (auto_increment). |
| `queue_id` | bigint NOT NULL | References queue entry in lupo_anubis_queue. |
| `file_path` | varchar(512) NOT NULL | File path processed. |
| `action` | varchar(64) NOT NULL | Outcome: recovered, failed, quarantined, retry. |
| `details` | text | JSON content with run details. |
| `actor_id` | bigint | ANUBIS instance that processed (e.g. 19). |
| `created_utc` | bigint NOT NULL | When the log entry was created. |

## 3. Indexes

- `idx_created` on `created_utc`
- `idx_queue` on `queue_id`

## 4. Primary key

- `log_id`

---

*Documentation for TOON: lupo_anubis_processing_log.toon.json. Maintained by Cursor (1003).*
