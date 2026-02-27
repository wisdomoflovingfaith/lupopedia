---
flare.headers:
  file_path_from_root: "docs/database/lupopedia/tables/lupo_anubis_log.md"
  system_version: "4.0.46"
  channel_id: 0
  actor_id: 1006
  created_ymdhis: 20260226204058
  updated_ymdhis: 20260226204058
  artifact_type: "table_documentation"
  purpose: "Central custodial audit trail and task queue for ANUBIS integrity events"
  lupo_agent: "gemini-cli"


flare.edges:
  outbound_edges:
- { to: "docs/database/lupopedia/tables/lupo_anubis_deletion_log.md", type: "references", weight: 0.8 }
    - { to: "docs/database/lupopedia/tables/lupo_anubis_revised.md", type: "references", weight: 0.8 }
    - { to: "docs/database/lupopedia/tables/lupo_anubis_redirects.md", type: "references", weight: 0.8 }
    - { to: "docs/database/lupopedia/tables/lupo_actors.md", type: "governed_by", context: "Actor 19 (ANUBIS)", weight: 1.0 }
  semantic_tags: ["anubis", "log", "audit", "custodial", "integrity"]

flare.footer:
  last_verified: "20260226"
  last_verified_by: "gemini-cli"
---

# Database Documentation: lupo_anubis_log
## Version: 4.0.46
## Date: 2026-02-26

### 1. Overview
The `lupo_anubis_log` table serves as the central custodial audit trail and task queue for **ANUBIS** (Automated Normalization and Unified Broadcast Integrity System). It tracks system inconsistencies, compliance violations, and integrity events that require automated or manual resolution.

Since Lupopedia operates without Foreign Keys (FK) at the database level, ANUBIS uses this log to maintain referential integrity across the knowledge graph through application-level logic.

### 2. Core Custodial Workflows

#### A. Header Compliance (FLARE/FLIP/WOLFIE)
When a file is detected without the minimum required headers (e.g., during a CI/CD gate, filesystem scan, or agent ingestion), a record is added to this log with `event_type = 'HEADER_COMPLIANCE'`.
- **Source**: `file_path_from_root`
- **Context**: List of missing or malformed headers and suggested values.
- **Resolution**: ANUBIS evaluates the file context and injects/corrects the canonical headers. High-priority files (e.g., in `lupo-includes/` or `database/`) are resolved first.

#### B. Deletion Guard (Referential Integrity)
In a no-FK architecture, hard-deleting a record can orphan child records in other tables. The Deletion Guard pattern logs an intent to delete with `event_type = 'DELETION_GUARD'`.
- **Source**: `source_table`, `source_id`
- **Context**: Snapshotted record data (for potential rollback) and identified dependent child references.
- **Resolution**: ANUBIS scans for dependent records and re-parents them (adoption) or soft-deletes them before clearing the primary record for removal.

#### C. Orphan Adoption
Tracks data fragments found without parents (orphaned dialog messages, registry entries, or content edges).
- **Event Type**: `ORPHAN_FOUND` or `REGISTRY_ADOPTION`.
- **Resolution**: Parent resolution logic determines a new logical home (e.g., adopting orphaned messages into Channel 42 / Thread 1).

### 3. Schema Definitions

| Column | Type | Description |
| :--- | :--- | :--- |
| `anubis_log_id` | BIGINT | Primary key. Explicitly generated (No AUTO_INCREMENT). |
| `event_type` | VARCHAR(64) | The class of integrity event (HEADER_COMPLIANCE, DELETION_GUARD, ORPHAN_FOUND, etc.). |
| `severity` | VARCHAR(20) | Severity level: `critical`, `warning`, `normal`, `info`. |
| `source_table` | VARCHAR(64) | Name of the database table involved in the event. |
| `source_id` | BIGINT | Primary key value of the record in the source table. |
| `file_path_from_root` | VARCHAR(255) | Absolute-style path from root for file-based compliance events. |
| `context_json` | JSON | Structured metadata containing event details (missing headers, data snapshots, audit trail). |
| `status` | VARCHAR(64) | Workflow state: `Pending`, `Processing`, `Resolved`, `Failed`, `Ignored`. |
| `assigned_to_actor_id` | BIGINT | The AI agent responsible for resolution (Default: 19 - ANUBIS). |
| `resolution_ymdhis` | BIGINT | Canonical timestamp when the event was resolved. |
| `resolution_summary` | TEXT | Brief description of the actions taken to resolve the event. |
| `created_ymdhis` | BIGINT | Record creation timestamp (@now YmdHis). |
| `updated_ymdhis` | BIGINT | Record modification timestamp. |
| `is_deleted` | TINYINT | Soft delete flag (0 = active, 1 = deleted). |
| `deleted_ymdhis` | BIGINT | Timestamp when the log entry was retired. |

---
*Maintained by GEMINI (Actor 1006) for Channel 0*

