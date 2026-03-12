---
lupopedia.headers:
  lupopedia.version: "1.0"
  lupopedia.schema: "documentation"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_anubis_queue.md"
  system_version: "4.0.70"
  channel_id: 1
  actor_id: 103
  last_modified_utc: "20260312"
  artifact_type: "table_documentation"
  purpose: "Work queue for files needing custodial processing"
  mood_rgb: "4169E1"
  traits: ["canonical", "anubis", "queue", "v4.0.70"]
  tags: ["database", "anubis", "queue", "workload"]
  lupo_agent: "antigravity"

lupopedia.edges:
  outbound_edges:
    - { to: "lupo-database/lupopedia/toon/lupo_anubis_queue.toon", type: "references", weight: 1.0 }
    - { to: "lupo-docs/database/lupopedia/tables/active/lupo_anubis_processing_log.md", type: "references", weight: 1.0 }

lupopedia.footer:
  last_verified: "20260312"
  last_verified_by: "antigravity"
---

# Table Overview: lupo_anubis_queue

- **Purpose**: The primary task queue for the ANUBIS custodial system. It manages the ingestion and processing pipeline for files detected with missing headers, malformed metadata, or other compliance issues.
- **Category**: Import / Reconciliation
- **Status**: Active
- **Version Introduced**: 4.0.0

## Column Documentation

| Column Name | Type | Nullable | Default | Description |
| :--- | :--- | :--- | :--- | :--- |
| `queue_id` | BIGINT | No | - | Primary Key. Numeric identifier. |
| `file_path` | VARCHAR(512) | No | - | Absolute-style path from root of the file being processed. |
| `file_hash` | VARCHAR(64) | Yes | - | Content hash for change detection. |
| `file_content` | LONGTEXT | Yes | - | Snapshot of file content for off-filesystem processing. |
| `detected_utc` | BIGINT | No | - | Timestamp of detection (YYYYMMDDHHIISS). |
| `priority` | TINYINT | Yes | 5 | Task priority (1=highest, 10=lowest). |
| `status` | VARCHAR(32) | Yes | 'pending' | Current state (pending, processing, recovered, failed, quarantined). |
| `detection_method` | VARCHAR(64) | Yes | - | How the issue was found (e.g., 'missing_header'). |
| `header_snapshot` | TEXT | Yes | - | Snapshot of partial or malformed headers. |
| `error_message` | TEXT | Yes | - | Last error reported during processing. |
| `attempts` | TINYINT | Yes | 0 | Number of retry attempts. |
| `last_attempt_utc` | BIGINT | Yes | - | Timestamp of last attempt. |
| `assigned_to_actor_id` | BIGINT | Yes | - | Reference to the ANUBIS instance/agent worker. |
| `filesystem_copy_exists` | TINYINT | Yes | 1 | Flag indicating if the file still exists on disk. |
| `filesystem_backup_path` | VARCHAR(512) | Yes | - | Path to a backup of the file before modification. |
| `created_utc` | BIGINT | No | - | Queue entry creation time. |
| `updated_utc` | BIGINT | No | - | Last modification time. |
| `is_deleted` | TINYINT | Yes | 0 | Soft delete flag. |

## Relationships

### Outbound References
- `lupo_actors.actor_id`: Identified by `assigned_to_actor_id`.

### Inbound References
- `lupo_anubis_processing_log.queue_id`: Tracks detailed actions taken.
- `lupo_anubis_quarantine.queue_id`: Links to quarantined file data.
- `lupo_anubis_recovery_attempts.queue_id`: Tracks specific recovery strategies tried.

## Usage Notes

- **Doctrine Discrepancy**: Although TOON may list `auto_increment`, Lupopedia doctrine requires explicit ID generation in PHP.
- **Backups**: ANUBIS should always populate `filesystem_backup_path` before attempting a destructive modification (e.g., header injection).

---
*Created by Antigravity (Actor 103) as part of the Database Documentation Program.*
