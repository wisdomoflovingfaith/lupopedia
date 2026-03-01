# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)
---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  file_path_from_root: ".\docs\database\lupopedia\tables\lupo_collections.md"
  file_hash: "e52fc2a8647ad0dd5edd587697c38e626ed763ca9bca741421229c9ff99de294"
  last_updated_utc: "20260228155738"
  system_version: "4.0.51"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "documentation"
  artifact_kind: "documentation"
  purpose: "Documentation file with FLARE header applied"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.51"]
  tags: ["documentation", "flare_applied"]
  lupo_agent: "windsurf"

flare.edges:
  outbound_edges:
    - { to: "CHANGELOG.md", type: "references", weight: 1.0 }
    - { to: "docs/doctrine/", type: "references", weight: 1.0 }

flare.footer:
  last_verified: "20260228155738"
  last_verified_by: "windsurf"
---

# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP) see http://www.lupopedia.com/lupopedia/content/FLARE and see http://www.lupopedia.com/lupopedia/qa/FLARE
---
flare.headers:
  file_path_from_root: "docs/database/lupopedia/tables/lupo_collections.md"
  system_version: "4.0.49"
  channel_id: 1
  actor_id: 1007
  last_modified_utc: "20260227"
  delegation_chain: "1007:10000"
  artifact_type: "table_documentation"
  purpose: "Collection registry for grouping and organizing content"
  dialog_message: "DBDOC batch 1: enriched documentation and optimization notes."
  mood_rgb: "4B0082"
  artifact_kind: "table"
  traits: ["canonical", "database", "curated"]
  tags: ["database", "table", "lupo_collections"]
  lupo_agent: "codex-ide"
  lupo_collections.collection_id: "bigint NOT NULL auto_increment"
  lupo_collections.federation_node_id: "bigint NOT NULL"
  lupo_collections.actor_id: "bigint"
  lupo_collections.department_id: "bigint"
  lupo_collections.name: "varchar(255) NOT NULL"
  lupo_collections.slug: "varchar(100) NOT NULL"
  lupo_collections.color: "char(6) DEFAULT '666666'"
  lupo_collections.description: "text"
  lupo_collections.sort_order: "int DEFAULT 0"
  lupo_collections.properties: "text"
  lupo_collections.published_ymdhis: "bigint"
  lupo_collections.created_ymdhis: "bigint NOT NULL DEFAULT 0"
  lupo_collections.updated_ymdhis: "bigint NOT NULL"
  lupo_collections.is_deleted: "tinyint NOT NULL DEFAULT 0"
  lupo_collections.deleted_ymdhis: "bigint DEFAULT 0"
  lupo_collections.parent_id: "bigint"
  table_primary_key: "collection_id"
  table_engine: "unknown"
  table_charset: "unknown"
  table_collation: "unknown"
  table_indexes: ["lupo_collections_idx_actor", "lupo_collections_idx_created_ymdhis", "lupo_collections_idx_department", "lupo_collections_idx_domain", "lupo_collections_idx_is_deleted", "lupo_collections_idx_name", "lupo_collections_idx_sort_order", "lupo_collections_idx_updated_ymdhis", "lupo_collections_unique_collection_slug_domain"]
  table_foreign_keys: []

# FLARE Edge Automation Tip:
# Use the FLARE Edge Suggester Tool to automatically discover and suggest edges:
# python scripts/flare_edge_suggester.py --file <path> --include-db --format yaml

flare.footer:
  outbound_edges:
    - { to: "docs/toons/lupo_collections.toon.json", type: "schema_reference", weight: 1.0, reason: "TOON schema definition", db_source: "lupo_collections" }
    - { to: "docs/database/lupopedia/tables/lupo_collection_tabs.md", type: "references", weight: 0.8, reason: "collection tab structure" }
    - { to: "docs/database/lupopedia/tables/lupo_collection_tab_map.md", type: "references", weight: 0.8, reason: "collection tab mapping" }
    - { to: "docs/database/lupopedia/tables/lupo_contents.md", type: "references", weight: 0.7, reason: "content grouping" }
  inbound_edges: []
  semantic_tags: ["database", "table", "collections"]
  version: "4.0.49"
  last_verified: "20260227"
  last_verified_by: "codex-ide"
---

# Table: lupo_collections

Purpose: Stores collection metadata for grouping content, channels, and curated sets.
Type: database_table
Status: production_ready
Volume: medium

## 1. Overview
- Key responsibilities: define collection identity and organization metadata.
- System role: supports curated content and collection navigation.
- Importance: drives UI collection features and semantic grouping.

## 2. Schema Reference
Primary Key: collection_id
Field Categories: identity, ownership, metadata, lifecycle, soft delete.

### All Fields
| Column | Type | Notes |
|---|---|---|
| collection_id | bigint NOT NULL auto_increment | Primary key. |
| federation_node_id | bigint NOT NULL | Federation node scope. |
| actor_id | bigint | Owner actor id. |
| department_id | bigint | Department scope. |
| name | varchar(255) NOT NULL | Display name. |
| slug | varchar(100) NOT NULL | Unique slug per node. |
| color | char(6) DEFAULT '666666' | UI color. |
| description | text | Description text. |
| sort_order | int DEFAULT 0 | Manual ordering. |
| properties | text | JSON-like properties payload. |
| published_ymdhis | bigint | Publication time. |
| created_ymdhis | bigint NOT NULL DEFAULT 0 | Created timestamp. |
| updated_ymdhis | bigint NOT NULL | Updated timestamp. |
| is_deleted | tinyint NOT NULL DEFAULT 0 | Soft delete flag. |
| deleted_ymdhis | bigint DEFAULT 0 | Soft delete timestamp. |
| parent_id | bigint | Parent collection id. |

## 3. Relationships and Dependencies
- Primary relationships: collection tabs and tab map tables.
- Referencing tables: content and UI components.
- Integration points: collection navigation, user collections, UI filters.

## 4. Indexes and Performance
Primary Indexes:
- collection_id
Performance Indexes:
- lupo_collections_unique_collection_slug_domain
- lupo_collections_idx_domain
- lupo_collections_idx_actor
- lupo_collections_idx_department
Index Strategy: optimize lookup by node + slug and filter by ownership.

## 5. Usage Patterns
Common Queries:
```sql
SELECT * FROM lupo_collections WHERE federation_node_id = :node AND is_deleted = 0 ORDER BY sort_order;
SELECT * FROM lupo_collections WHERE slug = :slug AND federation_node_id = :node AND is_deleted = 0;
SELECT COUNT(*) AS total FROM lupo_collections WHERE is_deleted = 0;
UPDATE lupo_collections SET updated_ymdhis = :ts WHERE collection_id = :id;
```
Best Practices: keep slug unique per node and update updated_ymdhis on edits.
Anti-Patterns: storing large JSON in properties without size control.

## 6. Performance Considerations
- High-volume operations: list and browse by node.
- Optimization tips: add index on (federation_node_id, is_deleted, sort_order) if needed.
- Scaling considerations: use pagination and caching for large collections.

## 7. Data Integrity
- Constraints: slug required, name required.
- Validation rules: enforce slug normalization at application layer.
- Soft delete: required for archive workflows.

## 8. Common Issues and Solutions
- Duplicate slug per node: rely on unique index.
- Ordering issues: sort_order and updated_ymdhis for recency.
- Data drift: keep federation_node_id consistent across related tables.

## 9. Future Enhancements
- Add collection_visibility field if needed.
- Consider properties_json instead of text for stricter validation.
