---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_federation_categories.md"
  system_version: "4.0.70"
  channel_id: 1
  actor_id: 103
  last_modified_utc: "20260312"
  artifact_type: "table_documentation"
  purpose: "Federation node categorization system"
  mood_rgb: "4169E1"
  traits: ["canonical", "federation", "v4.0.70"]
  tags: ["database", "federation", "categories"]
  lupo_agent: "antigravity"

lupopedia.edges:
  outbound_edges:
    - { to: "lupo-database/lupopedia/toon/lupo_federation_categories.toon", type: "references", weight: 1.0 }
    - { to: "lupo-docs/database/lupopedia/tables/active/lupo_federation_category_map.md", type: "references", weight: 0.9 }

lupopedia.footer:
  last_verified: "20260312"
  last_verified_by: "antigravity"
---

# Table Overview: lupo_federation_categories

- **Purpose**: Defines logical groupings/categories for federation nodes. Allows for filtering and organized discovery of nodes based on their content type, domain, or organizational affiliation.
- **Category**: Federation / Network
- **Status**: Active
- **Version Introduced**: 4.0.0

## Column Documentation

| Column Name | Type | Nullable | Default | Description |
| :--- | :--- | :--- | :--- | :--- |
| `federation_category_id` | BIGINT | No | - | Primary Key. Numeric identifier for the category. |
| `category_name` | VARCHAR(255) | No | - | Human-readable name of the category (e.g., 'Public Knowledge', 'Partner Nodes'). |
| `category_slug` | VARCHAR(255) | No | - | URL-friendly unique slug for the category. |
| `category_description` | TEXT | Yes | - | Detailed description of what this category represents. |
| `meta_json` | JSON | Yes | - | Extended category metadata. |
| `is_deleted` | TINYINT | No | 0 | Soft delete flag (1=deleted). |
| `deleted_ymdhis` | BIGINT | No | 0 | Soft delete timestamp (YYYYMMDDHHIISS). |
| `created_ymdhis` | BIGINT | No | 0 | Creation timestamp. |
| `updated_ymdhis` | BIGINT | No | 0 | Last update timestamp. |

## Relationships

### Inbound References
- `lupo_federation_category_map.federation_category_id`: Links nodes to these categories.

## Usage Notes

- **Slug Generation**: Slugs should be lowercase, using hyphens instead of spaces.
- **Organization**: Categories are system-wide and used to organize the Federation Directory UI.
- **Warnings**: Deleting a category does not delete the nodes associated with it, but it will orphan the mappings in `lupo_federation_category_map` (which should also be soft-deleted).

---
*Created by Antigravity (Actor 103) as part of the Database Documentation Program.*
