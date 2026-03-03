---
flare.headers:
  file_path_from_root: "lupo-docs/database/lupopedia/tables/lupo_anubis_recovery_attempts.md"
  system_version: "4.0.56"
  channel_id: 42
  actor_id: 1003
  last_modified_utc: "20260303"
  artifact_type: "table_documentation"
  purpose: "ANUBIS recovery attempts — per-queue recovery tries (strategy, generated header, success/fail)"
  traits: ["database", "table", "anubis", "cursor"]
  tags: ["database", "table", "lupo_anubis_recovery_attempts", "anubis", "recovery"]
  lupo_agent: "cursor"

flare.edges:
  outbound_edges:
    - { to: "lupo-docs/toons/lupo_anubis_recovery_attempts.toon.json", type: "schema_reference", weight: 1.0 }
    - { to: "lupo-docs/database/lupopedia/tables/lupo_anubis_queue.md", type: "references", weight: 0.9 }
  semantic_tags: ["anubis", "recovery", "custodial"]

flare.footer:
  last_verified_utc: "20260303"
  last_verified_by: "cursor"
---

# Database Documentation: lupo_anubis_recovery_attempts

**Version:** 4.0.56  
**Date:** 2026-03-03

## 1. Overview

The `lupo_anubis_recovery_attempts` table records each recovery attempt for a queued file. ANUBIS may try multiple strategies (e.g. template_generation, actor_inference, channel_guess) per queue item; each attempt is one row with strategy, success flag, generated header, and optional error details or recovered file path.

**Doctrine:** No foreign keys or triggers; application-level linkage to `lupo_anubis_queue` via `queue_id`.

## 2. Schema (from TOON)

| Column | Type | Description |
|--------|------|-------------|
| `attempt_id` | bigint NOT NULL | Primary key (auto_increment). |
| `queue_id` | bigint NOT NULL | Queue entry being recovered. |
| `attempt_number` | tinyint NOT NULL | Ordinal attempt (1, 2, …). |
| `attempt_utc` | bigint NOT NULL | When the attempt was made. |
| `strategy` | varchar(64) | e.g. template_generation, actor_inference, channel_guess. |
| `success` | tinyint | 1 = success, 0 = failure (default 0). |
| `generated_header` | text | Generated FLARE/FLIP header if any. |
| `error_details` | text | JSON content with error info. |
| `recovered_file_path` | varchar(512) | Path where file was written if recovered. |

## 3. Indexes

- `idx_queue_attempt` on (`queue_id`, `attempt_number`)

## 4. Primary key

- `attempt_id`

---

*Documentation for TOON: lupo_anubis_recovery_attempts.toon.json. Maintained by Cursor (1003).*
