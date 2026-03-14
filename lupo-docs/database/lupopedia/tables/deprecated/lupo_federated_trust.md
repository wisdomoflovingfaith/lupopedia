---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/deprecated/lupo_federated_trust.md"
  system_version: "4.0.70"
  channel_id: 1
  actor_id: 103
  last_modified_utc: "20260312"
  artifact_type: "table_documentation"
  purpose: "Deprecated federation trust management table"
  mood_rgb: "4169E1"
  traits: ["deprecated", "federation", "v4.0.70"]
  tags: ["database", "federation", "deprecated", "trust"]
  lupo_agent: "antigravity"

lupopedia.edges:
  outbound_edges:
    - { to: "lupo-database/lupopedia/toon/lupo_federated_trust.toon.json", type: "references", weight: 1.0 }
    - { to: "lupo-docs/database/lupopedia/tables/active/lupo_federation_nodes.md", type: "references", weight: 0.9 }

lupopedia.footer:
  last_verified: "20260312"
  last_verified_by: "antigravity"
---

# Table Overview: lupo_federated_trust (DEPRECATED)

> [!WARNING]
> This table is **DEPRECATED** and was not found in the current live TOON definitions (`lupo-database/lupopedia/toon/`). It remains documented for historical and migration reference.

- **Purpose**: Previously managed trust levels, permissions, and validation status between Lupopedia instances/nodes.
- **Category**: Federation / Security
- **Status**: Deprecated / Removed
- **Removal Status**: Found in `lupo-database/lupopedia/toon/` but missing from live DB schema as of version 4.0.70.

## Column Documentation (Last Known)

| Column Name | Type | Description |
| :--- | :--- | :--- |
| `trust_id` | BIGINT | Primary Key. |
| `source_node_id` | BIGINT | The local node ID. |
| `target_node_id` | BIGINT | The remote node being trusted. |
| `trust_level` | FLOAT | Confidence score (0.0-1.0). |
| `trust_type` | VARCHAR(50) | Nature of trust (e.g., 'full_federation'). |
| `capabilities` | JSON | Allowed cross-node actions. |
| `restrictions` | JSON | Forbidden actions. |
| `last_verified_ymdhis` | BIGINT | Last handshake timestamp. |
| `verification_method` | VARCHAR(100) | Verification method type. |
| `created_ymdhis` | BIGINT | Record creation time. |
| `updated_ymdhis` | BIGINT | Last update time. |
| `is_deleted` | TINYINT | Soft delete flag. |
| `deleted_ymdhis` | BIGINT | Deletion timestamp. |

## Relationships

### Outbound References
- `lupo_federation_nodes`: Previously linked source and target nodes.

## Usage Notes

- **Migration Impact**: Trust levels are now handled via `lupo_federation_nodes.trust_level` or specialized auth services.
- **Historical Context**: Part of the v4.0.48 Federated Identity framework, deprecated in favor of streamlined node metadata.

---
*Maintained by Antigravity (Actor 103) for the Database Documentation Program.*
