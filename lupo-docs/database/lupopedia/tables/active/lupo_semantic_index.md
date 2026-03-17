---
lupopedia.headers:
  lupopedia.version: "4.0.79"
  lupopedia.schema: "database_table"
  system_version: "4.0.79"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_semantic_index.md"
  web_path: "[lupo_semantic_index](http://www.lupopedia.com/database/lupopedia/tables/active/lupo_semantic_index)"
  last_modified_utc: "20260317"
  channel_id: 42
  actor_id: 1
  actor_name: "wolfie"
  delegation_chain: "wolfie:root"
  artifact_type: "table_documentation"
  artifact_kind: "table"
  namespace: "core"
  purpose: "Semantic index management; tracks semantic relationships, concepts, and content associations"
  tags: ["database", "table", "core"]

lupopedia.edges:
  comment: "Snapshot of edges for lupo_semantic_index table doc at 4.0.79 (grounded by repo search; non-exhaustive)."
  meta: "php_hits=0 python_hits=1"
  outbound_edges:
    - { to: "database.table.lupo_semantic_index", type: "DEFINES_SCHEMA_FOR", weight: 1.0 }
    - { to: "lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql", type: "schema_reference", weight: 1.0 }
    - { to: "(no_php_refs_found)", type: "USED_IN_PHP", weight: 0.0 }
    - { to: "analyze_unused_tables.py", type: "USED_IN_PYTHON", weight: 0.5 }

lupopedia.footer:
  version: "4.0.79"
  last_verified: "20260317"
  last_verified_by: "wolfie"
---
# file: lupo_semantic_index — session: L-LUPO-ROOT-WOLFIE — delegation: wolfie:root — web_path: http://www.lupopedia.com/database/lupopedia/tables/active/lupo_semantic_index

# Table: lupo_semantic_index

Canonical table for **semantic index management and concept relationships**. Tracks semantic relationships, concepts, and content associations across the Lupopedia system.

## Purpose

- Store semantic relationships between content items
- Track concept hierarchies and taxonomies
- Support semantic search and content discovery
- Enable knowledge graph navigation and analysis
- Provide foundation for AI and machine learning features

## Schema (install SQL authority)

| Column | Type | Description |
|--------|------|-------------|
| semantic_id | bigint NOT NULL | Primary key; **application-supplied** (no AUTO_INCREMENT). |
| source_type | varchar(64) NOT NULL | Type of source content (page, document, topic, etc.). |
| source_id | bigint DEFAULT NULL | ID of the source content. |
| target_type | varchar(64) NOT NULL | Type of target content (page, document, topic, etc.). |
| target_id | bigint DEFAULT NULL | ID of the target content. |
| relationship_type | varchar(64) NOT NULL | Type of relationship (related_to, contains, references, similar_to). |
| confidence | decimal(5,3) DEFAULT 1.000 | Confidence score for the relationship. |
| created_by_actor_id | bigint DEFAULT NULL | Actor who created this relationship. |
| created_ymdhis | bigint NOT NULL DEFAULT 0 | UTC timestamp when relationship was created. |
| updated_ymdhis | bigint NOT NULL DEFAULT 0 | UTC timestamp when relationship was last updated. |
| is_deleted | tinyint NOT NULL DEFAULT 0 | Soft delete flag. |
| deleted_ymdhis | bigint DEFAULT NULL | UTC timestamp when relationship was deleted. |

## Indexes

- `PRIMARY KEY (semantic_id)`
- `INDEX lupo_semantic_index_idx_source` ON `lupo_semantic_index` (`source_type`, `source_id`)
- `INDEX lupo_semantic_index_idx_target` ON `lupo_semantic_index` (`target_type`, `target_id`)
- `INDEX lupo_semantic_index_idx_relationship` ON `lupo_semantic_index` (`relationship_type`, `is_deleted`)
- `INDEX lupo_semantic_index_idx_confidence` ON `lupo_semantic_index` (`confidence`, `is_deleted`)
- `INDEX lupo_semantic_index_idx_created` ON `lupo_semantic_index` (`created_ymdhis`, `is_deleted`)

## Where This Table Is Used

### Core System Usage

- **Semantic engine** - Relationship management and concept mapping
- **Knowledge graph** - Content relationship tracking and navigation
- **AI systems** - Training data and semantic analysis
- **Content discovery** - Related content recommendations

### Integration Points

- **Content management** - Automatic relationship extraction
- **Search systems** - Semantic search and concept discovery
- **Analytics** - Relationship pattern analysis
- **User interfaces** - Concept visualization and navigation

## Relationship Types

- `related_to` - Content is related to target
- `contains` - Source contains or references target
- `references` - Source references target
- `similar_to` - Content is semantically similar to target

## Namespace

- **Domain:** Core
- **Subdomain:** Semantic & Knowledge
- **Related Tables:** `lupo_content`, `lupo_search_index`, `lupo_concepts`

Purpose: Auto-generated documentation for lupo_semantic_index from TOON schema.
Type: database_table
Status: production_ready
Volume: unknown

## 1. Overview
- Key responsibilities: schema reference, storage, and lookup for this table.
- System role: persists data for the Lupopedia database subsystem.
- Importance: enables data integrity and downstream features tied to this table.

## 2. Schema Reference
Primary Key: semantic_id
Field Categories: see full field list below.

### All Fields
| Column | Type | Notes |
|---|---|---|
| semantic_id | bigint NOT NULL | from TOON |
| semantic_type | varchar(32) NOT NULL | from TOON |
| slug | varchar(255) | from TOON |
| name | varchar(255) | from TOON |
| title | varchar(255) | from TOON |
| description | text | from TOON |
| parent_id | bigint | from TOON |
| sort_order | int DEFAULT 0 | from TOON |
| weight | float DEFAULT 0 | from TOON |
| relationship_strength | decimal(3,2) DEFAULT 1.00 | from TOON |
| layer | varchar(64) | from TOON |
| timeframe | varchar(64) | from TOON |
| language_code | varchar(8) | from TOON |
| color | varchar(7) DEFAULT '#666666' | from TOON |
| template_path | varchar(512) | from TOON |
| json_data | json | from TOON |
| text_value | text | from TOON |
| source_content_id | bigint | from TOON |
| target_content_id | bigint | from TOON |
| source_page_id | bigint | from TOON |
| target_page_id | bigint | from TOON |
| entity_type | varchar(32) | from TOON |
| entity_id | bigint | from TOON |
| created_ymdhis | bigint NOT NULL DEFAULT 0 | from TOON |
| updated_ymdhis | bigint NOT NULL | from TOON |
| is_active | tinyint NOT NULL DEFAULT 1 | from TOON |
| is_default | tinyint NOT NULL DEFAULT 0 | from TOON |
| is_deleted | tinyint NOT NULL DEFAULT 0 | from TOON |
| deleted_ymdhis | bigint | from TOON |
| created_by | bigint | from TOON |

## 3. Relationships and Dependencies
- Primary relationships: not specified in TOON relationships array.
- Referencing tables: unknown (use edge suggester tool).
- Integration points: see outbound edges in FLARE footer.

## 4. Indexes and Performance
Primary Indexes:
- semantic_id
Performance Indexes:
- lupo_semantic_index_idx_created_ymdhis
- lupo_semantic_index_idx_entity
- lupo_semantic_index_idx_is_active
- lupo_semantic_index_idx_is_default
- lupo_semantic_index_idx_is_deleted
- lupo_semantic_index_idx_language
- lupo_semantic_index_idx_layer
- lupo_semantic_index_idx_parent
- lupo_semantic_index_idx_source_content
- lupo_semantic_index_idx_source_page
- lupo_semantic_index_idx_target_content
- lupo_semantic_index_idx_target_page
- lupo_semantic_index_idx_timeframe
- lupo_semantic_index_idx_type
- lupo_semantic_index_idx_updated_ymdhis
- lupo_semantic_index_uk_type_slug
Index Strategy: derived from TOON index definitions.

## 5. Usage Patterns
Common Queries:
```sql
SELECT * FROM lupo_semantic_index WHERE semantic_id = :id;
SELECT COUNT(*) AS total FROM lupo_semantic_index WHERE is_deleted = 0;
SELECT * FROM lupo_semantic_index ORDER BY semantic_id DESC LIMIT 25;
UPDATE lupo_semantic_index SET updated_ymdhis = :ts WHERE semantic_id = :id;
```
Best Practices: always filter soft deletes where applicable.
Anti-Patterns: avoid full table scans on large datasets.

## 6. Performance Considerations
- High-volume operations: dependent on feature usage.
- Optimization tips: rely on existing indexes; add new indexes only with TOON updates.
- Scaling considerations: paginate reads and batch writes.

## 7. Data Integrity
- Constraints: see NOT NULL and DEFAULT values in TOON fields.
- Validation rules: enforced at application layer.
- Soft delete: use is_deleted/deleted_ymdhis if present.

## 8. Common Issues and Solutions
- Performance issues: add missing indexes via schema update.
- Data consistency: ensure foreign key relationships are enforced in application logic.
- Troubleshooting: compare against TOON schema for mismatches.

## 9. Future Enhancements
- Enrich relationships with discovered edges.
- Add usage-specific examples once feature usage is known.
