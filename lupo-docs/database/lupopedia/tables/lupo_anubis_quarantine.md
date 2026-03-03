---
flare.headers:
  file_path_from_root: "lupo-docs/database/lupopedia/tables/lupo_anubis_quarantine.md"
  system_version: "4.0.56"
  channel_id: 42
  actor_id: 1003
  last_modified_utc: "20260303"
  artifact_type: "table_documentation"
  purpose: "ANUBIS quarantine — files/content quarantined by custodial intelligence (banned, invalid, unsafe)"
  traits: ["database", "table", "anubis", "cursor"]
  tags: ["database", "table", "lupo_anubis_quarantine", "anubis", "quarantine"]
  lupo_agent: "cursor"

flare.edges:
  outbound_edges:
    - { to: "lupo-docs/toons/lupo_anubis_quarantine.toon.json", type: "schema_reference", weight: 1.0 }
    - { to: "lupo-docs/database/lupopedia/tables/lupo_anubis_queue.md", type: "references", weight: 0.9 }
    - { to: "lupo-docs/database/lupopedia/tables/lupo_actors.md", type: "references", weight: 0.5, reason: "reviewed_by_actor_id" }
  semantic_tags: ["anubis", "quarantine", "custodial"]

flare.footer:
  last_verified_utc: "20260303"
  last_verified_by: "cursor"
---

# Database Documentation: lupo_anubis_quarantine

**Version:** 4.0.56  
**Date:** 2026-03-03

## 1. Overview

The `lupo_anubis_quarantine` table stores files or content that ANUBIS has quarantined (e.g. banned-actor content, invalid headers, or unsafe material). Each row holds the original path, optional file content snapshot, quarantine path, reason, expiry, and optional review/resolution by a human or agent.

**Doctrine:** No foreign keys or triggers; application-level linkage to `lupo_anubis_queue` via `queue_id`.

## 2. Schema (from TOON)

| Column | Type | Description |
|--------|------|-------------|
| `quarantine_id` | bigint NOT NULL | Primary key (auto_increment). |
| `queue_id` | bigint NOT NULL | Source queue entry. |
| `file_path` | varchar(512) NOT NULL | Original file path. |
| `file_hash` | varchar(64) | Optional hash. |
| `file_content` | longtext | Optional content snapshot. |
| `quarantine_path` | varchar(512) NOT NULL | Path where quarantined copy is stored. |
| `reason` | varchar(255) NOT NULL | Quarantine reason. |
| `quarantined_utc` | bigint NOT NULL | When quarantined. |
| `expires_utc` | bigint | When to auto-delete (optional). |
| `reviewed_by_actor_id` | bigint | Actor who reviewed (e.g. 10000). |
| `reviewed_utc` | bigint | When reviewed. |
| `resolution` | varchar(64) | Resolution outcome. |
| `is_deleted` | tinyint | Soft delete (default 0). |

## 3. Indexes

- `idx_expires` on `expires_utc`
- `idx_queue` on `queue_id`

## 4. Primary key

- `quarantine_id`

---

*Documentation for TOON: lupo_anubis_quarantine.toon.json. Maintained by Cursor (1003).*
