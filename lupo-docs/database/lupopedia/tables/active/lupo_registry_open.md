---
lupopedia.headers:
  lupopedia.schema: documentation
  file_path_from_root: lupo-docs/database/lupopedia/tables/active/lupo_registry_open.md
  channel_id: 1
  actor_id: 103
  last_modified_utc: '20260312'
  artifact_type: table_documentation
  purpose: Registry for entities explicitly opened or unlocked for reconciliation
  mood_rgb: 4169E1
  traits:
  - canonical
  - registry
  - v4.0.70
  tags:
  - database
  - registry
  - open_status
  lupo_agent: antigravity
  when_updated: '20260324174654'
lupopedia.edges:
  outbound_edges:
  - to: lupo-database/lupopedia/toon/lupo_registry_open.toon
    type: references
    weight: 1.0
lupopedia.footer:
  last_verified: '20260312000000'
  last_verified_by: cursor
  last_verified_by_actor_id: 102
  orchestrator: cursor:root
---

# Table Overview: lupo_registry_open

- **Purpose**: Tracks entities that have been "opened" for editing, reconciliation, or external synchronization. This acts as a logical lock or status indicator for the Registry Service to prevent concurrent conflicting modifications during complex multi-agent workflows.
- **Category**: Registry / Synchronization
- **Status**: Active
- **Version Introduced**: 4.0.0

## Column Documentation

| Column Name | Type | Nullable | Default | Description |
| :--- | :--- | :--- | :--- | :--- |
| `unregistry_id` | BIGINT | No | - | Primary Key. |
| `entity_type` | VARCHAR(50) | No | - | Type of entity (e.g., 'actor', 'channel', 'content'). |
| `entity_index_id` | BIGINT | No | - | The ID of the specific entity. |
| `reason` | VARCHAR(255) | Yes | - | Reason for opening the registry record. |
| `created_ymdhis` | BIGINT | No | 0 | Timestamp of the operation. |

## Usage Notes

- **Lifecycle**: Records are typically transient but may persist during long-running re-indexing or federation tasks.

---
*Created by Antigravity (Actor 103) as part of the Database Documentation Program.*
