# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: ".\docs\database\lupopedia\tables\lupo_search_index.md"
  file_hash: "83cf6a96604f04d6f20c9c07594463b4be42f24f05c7a6428d89be420e3f25ae"
  last_updated_utc: "20260228155738"
  system_version: "4.0.51"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "documentation"
  artifact_kind: "documentation"
  purpose: "Documentation file with LUPOPEDIA HEADERS applied"
  namespace: "core"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.51"]
  tags: ["documentation", "flare_applied"]
  lupo_agent: "windsurf"

lupopedia.edges:
  outbound_edges:
    - { to: "CHANGELOG.md", type: "references", weight: 1.0 }
    - { to: "lupo-docs/doctrine/", type: "references", weight: 1.0 }

lupopedia.footer:
  last_verified: "20260228155738"
  last_verified_by: "windsurf"
---

# LUPOPEDIA HEADERS (replaces FLARE) see http://www.lupopedia.com/lupopedia/content/FLARE and see http://www.lupopedia.com/lupopedia/qa/FLARE
---
lupopedia.headers:
  file_path_from_root: "lupo-docs/database/lupopedia/tables/lupo_search_index.md"
  system_version: "4.0.49"
  channel_id: 1
  actor_id: 1007
  last_modified_utc: "20260227"
  delegation_chain: "1007:10000"
  artifact_type: "table_documentation"
  purpose: "Search index records for content and entities"
  dialog_message: "DBDOC batch 1: enriched documentation and optimization notes."
  mood_rgb: "4B0082"
  artifact_kind: "table"
  traits: ["canonical", "database", "curated"]
  tags: ["database", "table", "lupo_search_index"]
  lupo_agent: "codex-ide"
  lupo_search_index.search_index_id: "bigint NOT NULL"
  lupo_search_index.domain_id: "bigint NOT NULL"
  lupo_search_index.entity_type: "varchar(50) NOT NULL"
  lupo_search_index.entity_id: "bigint NOT NULL"
  lupo_search_index.title_text: "text"
  lupo_search_index.body_text: "text"
  lupo_search_index.keywords_text: "text"
  lupo_search_index.search_metadata: "text"
  lupo_search_index.relevance_score: "float DEFAULT 1"
  lupo_search_index.is_deleted: "tinyint NOT NULL DEFAULT 0"
  lupo_search_index.deleted_ymdhis: "bigint DEFAULT 0"
  lupo_search_index.created_ymdhis: "bigint NOT NULL DEFAULT 0"
  lupo_search_index.updated_ymdhis: "bigint NOT NULL"
  table_primary_key: "search_index_id"
  table_engine: "unknown"
  table_charset: "unknown"
  table_collation: "unknown"
  table_indexes: ["lupo_search_index_idx_domain_type", "lupo_search_index_idx_entity_reference", "lupo_search_index_idx_is_deleted", "lupo_search_index_idx_relevance", "lupo_search_index_idx_updated", "lupo_search_index_unique_entity"]
  table_foreign_keys: []

# FLARE Edge Automation Tip:
# Use the FLARE Edge Suggester Tool to automatically discover and suggest edges:
# python lupo-scripts/flare_edge_suggester.py --file <path> --include-db --format yaml

lupopedia.footer:
  outbound_edges:
    - { to: "lupo-database/lupopedia/toon/lupo_search_index.toon.json", type: "schema_reference", weight: 1.0, reason: "TOON schema definition", db_source: "lupo_search_index" }
    - { to: "lupo-docs/database/lupopedia/tables/lupo_contents.md", type: "references", weight: 0.8, reason: "content index targets" }
    - { to: "lupo-docs/database/lupopedia/tables/lupo_edges.md", type: "references", weight: 0.7, reason: "semantic edges" }
  inbound_edges: []
  semantic_tags: ["database", "table", "search"]
  version: "4.0.49"
  last_verified: "20260227"
  last_verified_by: "codex-ide"
---

# Table: lupo_search_index

Purpose: Stores denormalized search index entries for entities.
Type: database_table
Status: production_ready
Volume: high

## 1. Overview
- Key responsibilities: store searchable text fields per entity.
- System role: supports full text and keyword search.
- Importance: central to content discovery and search UI.

## 2. Schema Reference
Primary Key: search_index_id
Field Categories: identity, entity reference, text payload, lifecycle.

### All Fields
| Column | Type | Notes |
|---|---|---|
| search_index_id | bigint NOT NULL | Primary key. |
| domain_id | bigint NOT NULL | Federation domain. |
| entity_type | varchar(50) NOT NULL | Entity kind. |
| entity_id | bigint NOT NULL | Entity id. |
| title_text | text | Title field. |
| body_text | text | Body field. |
| keywords_text | text | Keywords. |
| search_metadata | text | Extra metadata (JSON-like). |
| relevance_score | float DEFAULT 1 | Score weight. |
| is_deleted | tinyint NOT NULL DEFAULT 0 | Soft delete flag. |
| deleted_ymdhis | bigint DEFAULT 0 | Soft delete timestamp. |
| created_ymdhis | bigint NOT NULL DEFAULT 0 | Created timestamp. |
| updated_ymdhis | bigint NOT NULL | Updated timestamp. |

## 3. Relationships and Dependencies
- Primary relationships: entity_type + entity_id refer to other tables.
- Referencing tables: search UI and API queries.
- Integration points: content updates and indexing jobs.

## 4. Indexes and Performance
Primary Indexes:
- search_index_id
Performance Indexes:
- lupo_search_index_unique_entity
- lupo_search_index_idx_domain_type
- lupo_search_index_idx_entity_reference
Index Strategy: ensure unique entity entries and fast filtering by domain/type.

## 5. Usage Patterns
Common Queries:
```sql
SELECT * FROM lupo_search_index WHERE domain_id = :domain AND entity_type = :type AND is_deleted = 0 LIMIT 50;
SELECT * FROM lupo_search_index WHERE entity_type = :type AND entity_id = :id AND is_deleted = 0;
UPDATE lupo_search_index SET updated_ymdhis = :ts WHERE search_index_id = :id;
```
Best Practices: update index entries on content changes; keep relevance_score normalized.
Anti-Patterns: storing large blobs in search_metadata.

## 6. Performance Considerations
- High-volume operations: frequent updates during content edits.
- Optimization tips: consider composite index on (domain_id, entity_type, is_deleted).
- Scaling considerations: partition by domain_id if dataset grows.

## 7. Data Integrity
- Constraints: unique per domain_id + entity_type + entity_id.
- Validation rules: enforce entity_type values in application logic.
- Soft delete: required to avoid orphaned index entries.

## 8. Common Issues and Solutions
- Stale index rows: use updated_ymdhis and scheduled reindex.
- Duplicates: rely on unique index.
- Search drift: rebuild on schema updates.

## 9. Future Enhancements
- Add lightweight fulltext strategy for title_text/keywords_text if supported.
- Add indexed hash of keywords for faster lookup.
