---
lupopedia.headers:
  lupopedia.version: "1.0"
  lupopedia.schema: "documentation"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_federation_nodes.md"
  system_version: "4.0.70"
  channel_id: 1
  actor_id: 103
  last_modified_utc: "20260312"
  artifact_type: "table_documentation"
  purpose: "Federation node registry and metadata documentation"
  mood_rgb: "4169E1"
  traits: ["canonical", "federation", "v4.0.70"]
  tags: ["database", "federation", "nodes", "registry"]
  lupo_agent: "antigravity"

lupopedia.edges:
  outbound_edges:
    - { to: "lupo-database/lupopedia/toon/lupo_federation_nodes.toon", type: "references", weight: 1.0 }
    - { to: "lupo-docs/database/lupopedia/tables/active/lupo_channels.md", type: "references", weight: 0.8 }
    - { to: "lupo-docs/database/lupopedia/tables/active/lupo_actors.md", type: "references", weight: 0.8 }

lupopedia.footer:
  last_verified: "20260312"
  last_verified_by: "antigravity"
---

# Table Overview: lupo_federation_nodes

- **Purpose**: Central registry for all nodes participating in the Lupopedia federation. It stores base connectivity information, trust levels, and aggregated metadata for each node.
- **Category**: Federation / Network
- **Status**: Active
- **Version Introduced**: 4.0.0
- **Governance**: Managed by System (Actor 0) and Federation Discovery services.

## Column Documentation

| Column Name | Type | Nullable | Default | Description |
| :--- | :--- | :--- | :--- | :--- |
| `federation_node_id` | BIGINT | No | - | Primary Key. Numeric identifier for the federation node. |
| `node_base_url` | VARCHAR(500) | No | - | The absolute base URL of the remote node's Lupopedia install. |
| `default_department_id` | BIGINT | Yes | - | The default department assigned to content originating from this node. |
| `node_name` | VARCHAR(255) | Yes | - | Human-readable name of the node. |
| `node_description` | TEXT | Yes | - | Brief description or purpose of the node. |
| `node_contact` | VARCHAR(255) | Yes | - | Contact email or identifier for the node administrator. |
| `meta_json` | JSON | Yes | - | Flexible storage for node-specific configuration and extended metadata. |
| `content_count` | BIGINT | No | 0 | Cached count of content nodes imported/known from this node. |
| `atom_count` | BIGINT | No | 0 | Cached count of atoms imported/known from this node. |
| `hashtag_count` | BIGINT | No | 0 | Cached count of hashtags associated with this node. |
| `actor_count` | BIGINT | No | 0 | Cached count of actors associated with/originating from this node. |
| `last_sync_ymdhis` | BIGINT | No | 0 | Timestamp (YYYYMMDDHHIISS) of the last successful synchronization. |
| `trust_level` | TINYINT | No | 0 | Trust level bitmask (0=untrusted, 1=verified, 2=trusted, etc.). |
| `status` | TINYINT | No | 1 | Operating status (1=active, 0=inactive). |
| `is_deleted` | TINYINT | No | 0 | Soft delete flag (1=deleted). |
| `deleted_ymdhis` | BIGINT | No | 0 | Soft delete timestamp (YYYYMMDDHHIISS). |
| `created_ymdhis` | BIGINT | No | 0 | Records the time the node was first registered. |
| `updated_ymdhis` | BIGINT | No | 0 | Records the last modification to this registry entry. |
| `active_theme_slug` | VARCHAR(64) | Yes | 'default' | The theme slug to apply when viewing content from this node (faucet-specific). |

## Relationships

### Foreign Keys
- None (Post-migration Lupopedia architecture enforces relationships in PHP).

### Inbound References
- `lupo_channels.federation_node_id`: Links channels to their home federation node.
- `lupo_actors.federation_node_id`: (Conceptually) links actors to their origin node.
- `lupo_contents.federation_node_id`: (Conceptually) identifies the source node of content.

### Outbound References
- `lupo_departments.department_id`: Referenced by `default_department_id`.

## Usage Notes

- **Migration Notes**: Replaces legacy `livehelp_websites` mapping for multi-site deployments.
- **Compatibility Notes**: All node URLs must be normalized (lowercase, no trailing slash) before insertion to avoid duplicate registry entries.
- **Warnings**: Do not hard-delete nodes; use `is_deleted = 1` to preserve historical content references.
- **Synchronization**: The `last_sync_ymdhis` is updated by the `FederationSyncService` after successful completion of a full node refresh.

---
*Created by Antigravity (Actor 103) as part of the Database Documentation Program.*
