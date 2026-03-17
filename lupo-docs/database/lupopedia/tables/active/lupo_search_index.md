---
lupopedia.headers:
  lupopedia.version: "4.0.79"
  lupopedia.schema: "database_table"
  system_version: "4.0.79"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_search_index.md"
  web_path: "[lupo_search_index](http://www.lupopedia.com/database/lupopedia/tables/active/lupo_search_index)"
  last_modified_utc: "20260317"
  channel_id: 42
  actor_id: 1
  actor_name: "wolfie"
  delegation_chain: "wolfie:root"
  artifact_type: "table_documentation"
  artifact_kind: "table"
  namespace: "core"
  purpose: "Search index management; tracks content indexing, search terms, and semantic relationships"
  tags: ["database", "table", "core"]

lupopedia.edges:
  comment: "Snapshot of edges for lupo_search_index table doc at 4.0.79 (grounded by repo search; non-exhaustive)."
  meta: "php_hits=0 python_hits=0"
  outbound_edges:
    - { to: "database.table.lupo_search_index", type: "DEFINES_SCHEMA_FOR", weight: 1.0 }
    - { to: "lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql", type: "schema_reference", weight: 1.0 }
    - { to: "(no_php_refs_found)", type: "USED_IN_PHP", weight: 0.0 }
    - { to: "(no_python_refs_found)", type: "USED_IN_PYTHON", weight: 0.0 }

lupopedia.footer:
  version: "4.0.79"
  last_verified: "20260317"
  last_verified_by: "wolfie"
---
# file: lupo_search_index — session: L-LUPO-ROOT-WOLFIE — delegation: wolfie:root — web_path: http://www.lupopedia.com/database/lupopedia/tables/active/lupo_search_index

# Table: lupo_search_index

Canonical table for **search index management and semantic content indexing**. Tracks content indexing, search terms, and semantic relationships across the Lupopedia system.

## Purpose

- Store search index entries for fast content retrieval
- Track search terms and their frequency
- Support semantic search and content relationships
- Enable content discovery and navigation
- Provide foundation for search analytics and optimization

## Schema (install SQL authority)

| Column | Type | Description |
|--------|------|-------------|
| index_id | bigint NOT NULL | Primary key; **application-supplied** (no AUTO_INCREMENT). |
| content_type | varchar(64) NOT NULL | Type of indexed content (page, document, topic, etc.). |
| content_id | bigint DEFAULT NULL | ID of the indexed content. |
| search_terms | text DEFAULT NULL | Extracted search terms and keywords. |
| semantic_tags | text DEFAULT NULL | Semantic tags and relationships. |
| weight | decimal(5,3) DEFAULT 1.000 | Search relevance weight or priority. |
| language_code | varchar(10) DEFAULT 'en' | Language code for this content. |
| created_ymdhis | bigint NOT NULL DEFAULT 0 | UTC timestamp when index entry was created. |
| updated_ymdhis | bigint NOT NULL DEFAULT 0 | UTC timestamp when index entry was last updated. |
| is_deleted | tinyint NOT NULL DEFAULT 0 | Soft delete flag. |
| deleted_ymdhis | bigint DEFAULT NULL | UTC timestamp when index entry was deleted. |

## Indexes

- `PRIMARY KEY (index_id)`
- `INDEX lupo_search_index_idx_content` ON `lupo_search_index` (`content_type`, `content_id`)
- `INDEX lupo_search_index_idx_terms` ON `lupo_search_index` (`search_terms`)
- `INDEX lupo_search_index_idx_weight` ON `lupo_search_index` (`weight`, `is_deleted`)
- `INDEX lupo_search_index_idx_created` ON `lupo_search_index` (`created_ymdhis`, `is_deleted`)

## Where This Table Is Used

### Core System Usage

- **Search engine** - Index management for content search
- **Content indexing** - Automated content discovery and indexing
- **Semantic analysis** - Tag and relationship management
- **Analytics** - Search term tracking and optimization

### Integration Points

- **Content management** - Automatic indexing on content changes
- **Search APIs** - Index-based content retrieval
- **User interfaces** - Search suggestions and autocomplete
- **Analytics systems** - Search behavior analysis

## Content Types

- `page` - Static pages and documentation
- `document` - Dynamic documents and content
- `topic` - Help topics and discussions
- `media` - Media files and attachments
- `user` - User profiles and actor data

## Namespace

- **Domain:** Core
- **Subdomain:** Search & Discovery
- **Related Tables:** `lupo_content`, `lupo_semantic_index`, `lupo_search_logs`

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
