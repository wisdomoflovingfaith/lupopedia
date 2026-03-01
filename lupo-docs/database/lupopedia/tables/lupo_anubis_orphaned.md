---
flare.headers:
  file_path_from_root: "docs/database/lupopedia/tables/lupo_anubis_orphaned.md"
  file_hash: "2bd8ee920b8ae1c909e0ed46b3d3352aecaa9a9e3573a2437826160324e967ab"
  system_version: "4.0.50"
  channel_id: 0
  actor_id: 1006
  created_ymdhis: 20260226204058
  updated_ymdhis: 20260226204058
  artifact_type: "table_documentation"
  purpose: "Quarantine buffer for data fragments awaiting parent assignment"
  lupo_agent: "gemini-cli"

flare.edges:
  file_path_from_root: "docs\database\lupopedia\tables\lupo_anubis_orphaned.md"
  outbound_edges:
- { to: "docs/database/lupopedia/tables/lupo_anubis_log.md", type: "part_of", context: "ANUBIS Custodial Suite", weight: 1.0 }
    - { to: "docs/database/lupopedia/tables/lupo_actors.md", type: "governed_by", context: "Actor 19 (ANUBIS)", weight: 1.0 }
  semantic_tags: ["anubis", "orphaned", "quarantine", "custodial"]

  delegation_chain: null
  needs_review: ["delegation_chain"]
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  last_updated_utc: "20260228"
flare.footer:
  last_verified: "20260226"
  last_verified_by: "gemini-cli"
---

# Database Documentation: lupo_anubis_orphaned
## Version: 4.0.46
## Date: 2026-02-26

### 1. Overview
Purpose: A quarantine/buffer table for data fragments that cannot yet be assigned to a parent or require further semantic analysis by VISHWAKARMA.

### 2. Schema Definitions

| Column | Type | Description |
| :--- | :--- | :--- |
| `anubis_orphaned_id` | BIGINT | Primary key. |
| `table_name` | VARCHAR(255) | The table the orphan originated from. |
| `orphan_id` | BIGINT | The surrogate ID assigned to the fragment. |
| `created_ymdhis` | BIGINT | When the fragment was designated as an orphan. |
| `updated_ymdhis` | BIGINT | Last update to the orphan record. |
| `reason` | VARCHAR(255) | Reason code (MISSING_PARENT, INVALID_CHANNEL, etc.). |
| `is_deleted` | TINYINT | Soft delete flag. |
| `deleted_ymdhis` | BIGINT | Timestamp when retired. |

---
*Maintained by GEMINI (Actor 1006)*