---
lupopedia.headers:
  lupopedia.schema: documentation
  file_path_from_root: lupo-docs/database/lupopedia/tables/deprecated/lupo_federation_discovery.md
  channel_id: 1
  actor_id: 103
  last_modified_utc: '20260312'
  artifact_type: table_documentation
  purpose: Deprecated federation discovery table
  mood_rgb: 4169E1
  traits:
  - deprecated
  - federation
  - v4.0.70
  tags:
  - database
  - federation
  - deprecated
  - discovery
  lupo_agent: antigravity
  when_updated: '20260324174654'
lupopedia.edges:
  outbound_edges:
  - to: lupo-database/lupopedia/toon/lupo_federation_discovery.toon.json
    type: references
    weight: 1.0
lupopedia.footer:`n  approved_for_release: "4.1.0"`n  approval_status: "approved"`n  approved_by_actor_id: 1`n  approved_utc: 20260326192115`n  last_verified: '20260312000000'
  last_verified_by: cursor
  last_verified_by_actor_id: 102
  orchestrator: cursor:root
---

# Table Overview: lupo_federation_discovery (DEPRECATED)

> [!WARNING]
> This table is **DEPRECATED** and was not found in the current live TOON definitions. It exists in `lupo-database/lupopedia/toon/` but is absent from `lupo-database/lupopedia/toon/`.

- **Purpose**: Previously used for automated discovery and categorization of remote Lupopedia nodes and domains.
- **Category**: Federation / Network
- **Status**: Deprecated / Removed
- **Removal Status**: Superseded by the unified node registry in `lupo_federation_nodes`.

## Column Documentation (Last Known)

| Column Name | Type | Description |
| :--- | :--- | :--- |
| `federation_discovery_id` | BIGINT | Primary Key. |
| `domain` | VARCHAR(255) | Discovered domain. |
| `install_url` | VARCHAR(500) | URL of the potential install. |
| `is_lupopedia` | TINYINT | Flag indicating if Lupopedia was detected. |
| `last_seen_ymdhis` | BIGINT | Last activity timestamp. |
| `first_seen_ymdhis` | BIGINT | First detection timestamp. |
| `hashtag_count` | BIGINT | Metrics found during discovery. |
| `question_count` | BIGINT | Metrics found during discovery. |
| `atom_count` | BIGINT | Metrics found during discovery. |
| `context_count` | BIGINT | Metrics found during discovery. |
| `collection_count` | BIGINT | Metrics found during discovery. |
| `keywords` | VARCHAR(500) | Metadata keywords. |
| `description` | TEXT | Discovered meta-description. |
| `import_hashtags` | TINYINT | Settings for import. |
| `import_questions` | TINYINT | Settings for import. |
| `import_atoms` | TINYINT | Settings for import. |
| `import_contexts` | TINYINT | Settings for import. |
| `import_collections` | TINYINT | Settings for import. |
| `created_ymdhis` | BIGINT | Registry creation time. |
| `updated_ymdhis` | BIGINT | Last modified time. |

## Usage Notes

- **Migration Impact**: Functionality for node discovery is now integrated into background synchronization workers or managed manually in the federation node registry.
- **Historical Context**: Documentation was previously auto-generated but the table proved redundant following schema consolidation in version 4.0.57.

---
*Maintained by Antigravity (Actor 103) for the Database Documentation Program.*

