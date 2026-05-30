---
lupopedia.headers:
  lupopedia.schema: documentation
  file_path_from_root: lupo-docs/database/lupopedia/tables/deprecated/lupo_registry_import.md
  channel_id: 1
  actor_id: 103
  questions_toon: null
  artifact_type: table_documentation
  purpose: Deprecated federation registry import table
  mood_vector: 4169E1
  traits:
  - deprecated
  - registry
  - v4.0.70
  tags:
  - database
  - registry
  - import
  - deprecated
  lupo_agent: antigravity
  when_updated: '20260324174654'
lupopedia.edges:
  outbound_edges:
  - to: lupo-database/lupopedia/toon/lupo_registry_import.toon.json
    type: references
    weight: 1.0
lupopedia.footer:`n  approved_for_release: "4.1.0"`n  approval_status: "approved"`n  approved_by_actor_id: 1`n  approved_utc: 20260326192115`n  last_verified: '20260312000000'
  last_verified_by: cursor
  last_verified_by_actor_id: 102
  orchestrator: cursor:root
---

# Table Overview: lupo_registry_import (DEPRECATED)

> [!WARNING]
> This table is **DEPRECATED** and was not found in the current live TOON definitions. It served as a staging area for remote entity imports during the 4.0.45 development phase.

- **Purpose**: Previously tracked the mapping between remote entity IDs and local IDs during federation sync.
- **Category**: Registry / Synchronization
- **Status**: Deprecated / Removed
- **Removal Status**: Functionality absorbed into the unified `lupo_federation_nodes` and `lupo_registry_open` services.

## Column Documentation (Last Known)

| Column Name | Type | Description |
| :--- | :--- | :--- |
| `import_registry_id` | BIGINT | Primary Key. |
| `entity_type` | VARCHAR(50) | Type of entity. |
| `entity_index_id` | BIGINT | Source node's ID. |
| `source_federation_node_id` | BIGINT | ID of the origin node. |
| `imported_at` | BIGINT | Import timestamp. |
| `resolved_to_local_id` | BIGINT | Local ID mapping. |
| `notes` | TEXT | Audit notes. |

---
*Maintained by Antigravity (Actor 103) for the Database Documentation Program.*

