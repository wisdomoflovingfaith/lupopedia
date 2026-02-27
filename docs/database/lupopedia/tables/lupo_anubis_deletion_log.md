---
flare.headers:
  file_path_from_root: "docs/database/lupopedia/tables/lupo_anubis_deletion_log.md"
  system_version: "4.0.46"
  channel_id: 0
  actor_id: 1006
  created_ymdhis: 20260226204058
  updated_ymdhis: 20260226204058
  artifact_type: "table_documentation"
  purpose: "Audit trail for record deletions and child record adoption"
  lupo_agent: "gemini-cli"

flare.edges:
  outbound_edges:
- { to: "docs/database/lupopedia/tables/lupo_anubis_log.md", type: "part_of", context: "ANUBIS Custodial Suite", weight: 1.0 }
    - { to: "docs/database/lupopedia/tables/lupo_actors.md", type: "governed_by", context: "Actor 19 (ANUBIS)", weight: 1.0 }
  semantic_tags: ["anubis", "deletion", "adoption", "integrity"]

flare.footer:
  last_verified: "20260226"
  last_verified_by: "gemini-cli"
---

# Database Documentation: lupo_anubis_deletion_log
## Version: 4.0.46
## Date: 2026-02-26

### 1. Overview
Purpose: Detailed audit trail for record deletions, specifically focusing on re-parenting and child adoption logic to maintain referential integrity without database-level constraints.

### 2. Schema Definitions

| Column | Type | Description |
| :--- | :--- | :--- |
| `anubis_deletion_id` | BIGINT | Primary key (Explicitly managed). |
| `table_name` | VARCHAR(255) | The source table where the deletion occurred. |
| `record_id` | BIGINT | The ID of the record that was deleted. |
| `deleted_ymdhis` | BIGINT | Canonical timestamp of the deletion event itself. |
| `deletion_type` | VARCHAR(64) | Protocol type (e.g., ADOPTION_REPARENT, HARD_WIPE, SOFT_RETIRE). |
| `replacement_table` | VARCHAR(255) | Parent table where orphans were moved (if applicable). |
| `replacement_id` | BIGINT | Parent ID that adopted the children (if applicable). |
| `anubis_operator` | VARCHAR(255) | Name or version of the ANUBIS agent/script that performed the action. |
| `context_json` | JSON/TEXT | Structured snapshot of the deleted record and its immediate graph edges. |
| `notes` | TEXT | Human-readable (or agent-readable) justification for the deletion. |
| `created_ymdhis` | BIGINT | Log record creation timestamp. |
| `updated_ymdhis` | BIGINT | Log record modification timestamp. |
| `is_deleted` | TINYINT | Soft delete flag for the log entry itself. |

---
*Maintained by GEMINI (Actor 1006)*

