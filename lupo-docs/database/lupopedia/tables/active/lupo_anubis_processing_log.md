---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_anubis_processing_log.md"
  system_version: "4.0.70"
  channel_id: 1
  actor_id: 103
  last_modified_utc: "20260312"
  artifact_type: "table_documentation"
  purpose: "Detailed record of actions taken on queue items"
  mood_rgb: "4169E1"
  traits: ["canonical", "anubis", "processing", "v4.0.70"]
  tags: ["database", "anubis", "processing", "log"]
  lupo_agent: "antigravity"

lupopedia.edges:
  outbound_edges:
    - { to: "lupo-database/lupopedia/toon/lupo_anubis_processing_log.toon", type: "references", weight: 1.0 }
    - { to: "lupo-docs/database/lupopedia/tables/active/lupo_anubis_queue.md", type: "references", weight: 1.0 }

lupopedia.footer:
  last_verified: "20260312"
  last_verified_by: "antigravity"
---

# Table Overview: lupo_anubis_processing_log

- **Purpose**: Provides a detailed audit trail for every action performed on an item in the `lupo_anubis_queue`. It captures individual attempts, outcomes, and reasoning for each custodial step.
- **Category**: Import / Reconciliation
- **Status**: Active
- **Version Introduced**: 4.0.0

## Column Documentation

| Column Name | Type | Nullable | Default | Description |
| :--- | :--- | :--- | :--- | :--- |
| `log_id` | BIGINT | No | - | Primary Key. Numeric identifier. |
| `queue_id` | BIGINT | No | - | Reference to the item in `lupo_anubis_queue`. |
| `file_path` | VARCHAR(512) | No | - | Path of the file at time of processing. |
| `action` | VARCHAR(64) | No | - | Action taken (e.g., 'recovered', 'failed', 'quarantined', 'retry'). |
| `details` | TEXT | Yes | - | JSON-formatted specifics about the action outcome. |
| `actor_id` | BIGINT | Yes | - | Reference to the ANUBIS instance/agent performing the action. |
| `created_utc` | BIGINT | No | - | Timestamp of the log entry. |

## Relationships

### Outbound References
- `lupo_anubis_queue.queue_id`: The queue item being processed.
- `lupo_actors.actor_id`: Identified by `actor_id`.

---
*Created by Antigravity (Actor 103) as part of the Database Documentation Program.*
