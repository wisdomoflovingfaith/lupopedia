---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_anubis_quarantine.md"
  system_version: "4.0.70"
  channel_id: 1
  actor_id: 103
  last_modified_utc: "20260312"
  artifact_type: "table_documentation"
  purpose: "Storage and metadata for quarantined files"
  mood_rgb: "4169E1"
  traits: ["canonical", "anubis", "quarantine", "v4.0.70"]
  tags: ["database", "anubis", "quarantine", "remediation"]
  lupo_agent: "antigravity"

lupopedia.edges:
  outbound_edges:
    - { to: "lupo-database/lupopedia/toon/lupo_anubis_quarantine.toon", type: "references", weight: 1.0 }
    - { to: "lupo-docs/database/lupopedia/tables/active/lupo_anubis_queue.md", type: "references", weight: 1.0 }

lupopedia.footer:
  last_verified: "20260312"
  last_verified_by: "antigravity"
---

# Table Overview: lupo_anubis_quarantine

- **Purpose**: Acts as a safety deposit for files that ANUBIS cannot reconcile or that contain severe compliance violations. It holds the file content and metadata until a human (or senior agent) reviews and resolves the status.
- **Category**: Remediation / Security
- **Status**: Active
- **Version Introduced**: 4.0.0

## Column Documentation

| Column Name | Type | Nullable | Default | Description |
| :--- | :--- | :--- | :--- | :--- |
| `quarantine_id` | BIGINT | No | - | Primary Key. |
| `queue_id` | BIGINT | No | - | Reference to the `lupo_anubis_queue` item. |
| `file_path` | VARCHAR(512) | No | - | Original path where the file was found. |
| `file_hash` | VARCHAR(64) | Yes | - | Hash of the quarantined content. |
| `file_content` | LONGTEXT | Yes | - | Direct storage of the non-compliant content. |
| `quarantine_path` | VARCHAR(512) | No | - | Path to the file in the dedicated quarantine directory. |
| `reason` | VARCHAR(255) | No | - | Logical reason for quarantine (e.g., 'INVALID_SIGNATURE'). |
| `quarantined_utc` | BIGINT | No | - | Timestamp of quarantine. |
| `expires_utc` | BIGINT | Yes | - | Optional expiration for auto-deletion of logs. |
| `reviewed_by_actor_id` | BIGINT | Yes | - | The admin actor who reviewed the item. |
| `reviewed_utc` | BIGINT | Yes | - | Review timestamp. |
| `resolution` | VARCHAR(64) | Yes | - | The outcome of review (e.g., 'RESTORED', 'DISCARDED'). |
| `is_deleted` | TINYINT | Yes | 0 | Soft delete flag. |

## Relationships

### Outbound References
- `lupo_anubis_queue.queue_id`: Origin queue item.
- `lupo_actors.actor_id`: Identified by `reviewed_by_actor_id`.

## Usage Notes

- **Governance**: Quarantined files are isolated from the rest of the Lupopedia system and cannot be indexed until restored.

---
*Created by Antigravity (Actor 103) as part of the Database Documentation Program.*
