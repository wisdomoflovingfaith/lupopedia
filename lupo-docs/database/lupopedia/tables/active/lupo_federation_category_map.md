---
lupopedia.headers:
  lupopedia.schema: documentation
  file_path_from_root: lupo-docs/database/lupopedia/tables/active/lupo_federation_category_map.md
  channel_id: 1
  actor_id: 103
  last_modified_utc: '20260312'
  artifact_type: table_documentation
  purpose: Many-to-many relationship between nodes and categories
  mood_rgb: 4169E1
  traits:
  - canonical
  - federation
  - v4.0.70
  tags:
  - database
  - federation
  - mapping
  - categories
  lupo_agent: antigravity
  when_updated: '20260324174654'
lupopedia.edges:
  outbound_edges:
  - to: lupo-database/lupopedia/toon/lupo_federation_category_map.toon
    type: references
    weight: 1.0
  - to: lupo-docs/database/lupopedia/tables/active/lupo_federation_nodes.md
    type: references
    weight: 1.0
  - to: lupo-docs/database/lupopedia/tables/active/lupo_federation_categories.md
    type: references
    weight: 1.0
lupopedia.footer:
  last_verified: '20260312000000'
  last_verified_by: cursor
  last_verified_by_actor_id: 102
  orchestrator: cursor:root
---

# Table Overview: lupo_federation_category_map

- **Purpose**: A mapping table that implements the many-to-many relationship between Federation Nodes and Federation Categories.
- **Category**: Federation / Network
- **Status**: Active
- **Version Introduced**: 4.0.0

## Column Documentation

| Column Name | Type | Nullable | Default | Description |
| :--- | :--- | :--- | :--- | :--- |
| `federation_category_map_id` | BIGINT | No | - | Primary Key. Numeric identifier for the mapping. |
| `federation_node_id` | BIGINT | No | - | Reference to the registered node. |
| `federation_category_id` | BIGINT | No | - | Reference to the category. |
| `meta_json` | JSON | Yes | - | Extended mapping metadata. |
| `is_deleted` | TINYINT | No | 0 | Soft delete flag (1=deleted). |
| `deleted_ymdhis` | BIGINT | No | 0 | Soft delete timestamp (YYYYMMDDHHIISS). |
| `created_ymdhis` | BIGINT | No | 0 | Creation timestamp. |
| `updated_ymdhis` | BIGINT | No | 0 | Last update timestamp. |

## Relationships

### Outbound References
- `lupo_federation_nodes.federation_node_id`: Target node.
- `lupo_federation_categories.federation_category_id`: Target category.

## Usage Notes

- **Querying**: To find all nodes in a specific category, join `lupo_federation_nodes` with this table on `federation_node_id`.
- **Soft Deletes**: Always filter for `is_deleted = 0` when retrieving active mappings.

---
*Created by Antigravity (Actor 103) as part of the Database Documentation Program.*
