---
lupopedia.headers:
  lupopedia.version: "1.0"
  lupopedia.schema: "documentation"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_anubis_events.md"
  system_version: "4.0.70"
  channel_id: 1
  actor_id: 103
  last_modified_utc: "20260312"
  artifact_type: "table_documentation"
  purpose: "Operational system event log for ANUBIS"
  mood_rgb: "4169E1"
  traits: ["canonical", "anubis", "ops", "v4.0.70"]
  tags: ["database", "anubis", "events", "ops"]
  lupo_agent: "antigravity"

lupopedia.edges:
  outbound_edges:
    - { to: "lupo-database/lupopedia/toon/lupo_anubis_events.toon", type: "references", weight: 1.0 }
    - { to: "lupo-docs/database/lupopedia/tables/active/lupo_anubis_log.md", type: "part_of", weight: 1.0 }

lupopedia.footer:
  last_verified: "20260312"
  last_verified_by: "antigravity"
---

# Table Overview: lupo_anubis_events

- **Purpose**: Tracks high-level operational events and lifecycle milestones for the ANUBIS system, such as scan starts, strategy changes, or mass-normalization completions.
- **Category**: Operations / Monitoring
- **Status**: Active
- **Version Introduced**: 4.0.0

## Column Documentation

| Column Name | Type | Nullable | Default | Description |
| :--- | :--- | :--- | :--- | :--- |
| `anubis_event_id` | BIGINT | No | - | Primary Key. Numeric identifier. |
| `event_type` | VARCHAR(64) | No | - | Operational event type (e.g., 'CORE_SCAN_START', 'RECOVERY_SUITE_INIT'). |
| `table_name` | VARCHAR(255) | No | - | Target table or scope of the operational event. |
| `row_id` | BIGINT | No | - | Specific row associated if applicable. |
| `created_ymdhis` | BIGINT | No | - | Creation timestamp. |
| `agent` | VARCHAR(255) | No | - | Logical identifier of the agent script or instance. |
| `details_json` | TEXT | No | - | Detailed outcome, diagnostics, or configuration parameters. |

## Relationships

### Inbound References
- `lupo_anubis_log.anubis_event_id`: Granular audit logs may link back to these operational events.

## Usage Notes

- **Auditing**: This table is intended for system administrators to monitor ANUBIS health and background task progress.
- **Storage**: Unlike the granular `anubis_log`, this table is low-volume and stores macro-level events.

---
*Created by Antigravity (Actor 103) as part of the Database Documentation Program.*
