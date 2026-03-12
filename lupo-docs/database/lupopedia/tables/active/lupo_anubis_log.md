---
lupopedia.headers:
  lupopedia.version: "1.0"
  lupopedia.schema: "documentation"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_anubis_log.md"
  system_version: "4.0.70"
  channel_id: 1
  actor_id: 103
  last_modified_utc: "20260312"
  artifact_type: "table_documentation"
  purpose: "Central custodial audit trail for ANUBIS integrity events"
  mood_rgb: "4169E1"
  traits: ["canonical", "anubis", "audit", "v4.0.70"]
  tags: ["database", "anubis", "audit", "integrity"]
  lupo_agent: "antigravity"

lupopedia.edges:
  outbound_edges:
    - { to: "lupo-database/lupopedia/toon/lupo_anubis_log.toon", type: "references", weight: 1.0 }
    - { to: "lupo-docs/database/lupopedia/tables/active/lupo_anubis_events.md", type: "references", weight: 0.9 }

lupopedia.footer:
  last_verified: "20260312"
  last_verified_by: "antigravity"
---

# Table Overview: lupo_anubis_log

- **Purpose**: Serves as the central custodial audit trail for **ANUBIS** (Automated Normalization and Unified Broadcast Integrity System). It tracks system inconsistencies, compliance violations (e.g., missing headers), and referential integrity events that require resolution.
- **Category**: Import / Reconciliation / Integrity
- **Status**: Active
- **Version Introduced**: 4.0.0
- **Governance**: Governed by Actor 19 (ANUBIS).

## Column Documentation

| Column Name | Type | Nullable | Default | Description |
| :--- | :--- | :--- | :--- | :--- |
| `anubis_log_id` | BIGINT | No | - | Primary Key. Numeric identifier. |
| `event_type` | VARCHAR(64) | No | - | Class of integrity event (e.g., 'HEADER_COMPLIANCE', 'ORPHAN_FOUND'). |
| `severity` | VARCHAR(20) | No | 'normal' | Severity level (critical, warning, normal, info). |
| `source_table` | VARCHAR(64) | Yes | - | Name of the database table involved in the event. |
| `source_id` | BIGINT | Yes | - | Primary key value of the record in the source table. |
| `file_path_from_root` | VARCHAR(255) | Yes | - | Filesystem path for file-based compliance events. |
| `context_json` | JSON | Yes | - | Structured metadata containing event details and diagnostic snapshots. |
| `status` | VARCHAR(64) | No | 'Pending' | Workflow state (Pending, Processing, Resolved, Failed). |
| `assigned_to_actor_id` | BIGINT | No | 19 | The actor responsible for resolution (Default: 19 - ANUBIS). |
| `resolution_ymdhis` | BIGINT | Yes | - | Timestamp when the event was resolved. |
| `resolution_summary` | TEXT | Yes | - | Description of the corrective actions taken. |
| `created_ymdhis` | BIGINT | No | 0 | Record creation timestamp. |
| `updated_ymdhis` | BIGINT | No | 0 | Record modification timestamp. |
| `is_deleted` | TINYINT | No | 0 | Soft delete flag. |
| `deleted_ymdhis` | BIGINT | Yes | - | Soft delete timestamp. |

## Relationships

### Outbound References
- `lupo_actors.actor_id`: Identified by `assigned_to_actor_id`.
- `lupo_anubis_events.anubis_event_id`: Higher-level operational events related to this log entry.

## Usage Notes

- **Doctrine Compliance**: Although TOON may mention `auto_increment` for some Anubis tables, Lupopedia doctrine requires explicit ID generation in PHP.
- **Role**: ANUBIS uses this table to maintain referential integrity in an architecture that forbids database-level Foreign Keys.

---
*Maintained by Antigravity (Actor 103) for the Database Documentation Program.*
