---
lupopedia.headers:
  lupopedia.version: "4.0.79"
  lupopedia.schema: "database_table"
  system_version: "4.0.79"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_legacy_content_mapping.md"
  web_path: "[lupo_legacy_content_mapping](http://www.lupopedia.com/database/lupopedia/tables/active/lupo_legacy_content_mapping)"
  last_modified_utc: "20260317"
  channel_id: 42
  actor_id: 102
  actor_name: "cursor"
  delegation_chain: "cursor:root"
  artifact_type: "table_documentation"
  artifact_kind: "table"
  namespace: "core"
  purpose: "Documentation file with LUPOPEDIA HEADERS applied"
  tags: ["database", "table", "core"]

lupopedia.edges:
  comment: "Snapshot of edges for lupo_legacy_content_mapping table doc at 4.0.79 (grounded by repo search; non-exhaustive)."
  meta: "php_hits=0 python_hits=0"
  outbound_edges:
    - { to: "database.table.lupo_legacy_content_mapping", type: "DEFINES_SCHEMA_FOR", weight: 1.0 }
    - { to: "lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql", type: "schema_reference", weight: 1.0 }
    - { to: "(no_php_refs_found)", type: "USED_IN_PHP", weight: 0.0 }
    - { to: "(no_python_refs_found)", type: "USED_IN_PYTHON", weight: 0.0 }

lupopedia.footer:
  version: "4.0.79"
  last_verified: "20260317"
  last_verified_by: "cursor"
---
# file: lupo_legacy_content_mapping ? web_path: http://www.lupopedia.com/database/lupopedia/tables/active/lupo_legacy_content_mapping
# Table: lupo_legacy_content_mapping

Purpose: Auto-generated documentation for lupo_legacy_content_mapping from TOON schema.
Type: database_table
Status: production_ready
Volume: unknown

## 1. Overview
- Key responsibilities: schema reference, storage, and lookup for this table.
- System role: persists data for the Lupopedia database subsystem.
- Importance: enables data integrity and downstream features tied to this table.

## 2. Schema Reference
Primary Key: mapping_id
Field Categories: see full field list below.

### All Fields
| Column | Type | Notes |
|---|---|---|
| mapping_id | bigint NOT NULL | from TOON |
| legacy_url | varchar(255) NOT NULL | from TOON |
| semantic_url | varchar(255) NOT NULL | from TOON |
| content_type | varchar(64) NOT NULL | from TOON |
| content_id | bigint | from TOON |
| created_ymdhis | bigint NOT NULL DEFAULT 0 | from TOON |
| updated_ymdhis | bigint NOT NULL | from TOON |
| is_active | tinyint NOT NULL DEFAULT 1 | from TOON |

## 3. Relationships and Dependencies
- Primary relationships: not specified in TOON relationships array.
- Referencing tables: unknown (use edge suggester tool).
- Integration points: see outbound edges in FLARE footer.

## 4. Indexes and Performance
Primary Indexes:
- mapping_id
Performance Indexes:
- lupo_legacy_content_mapping_idx_content_id
- lupo_legacy_content_mapping_idx_content_type
- lupo_legacy_content_mapping_idx_created
- lupo_legacy_content_mapping_idx_created_ymdhis
- lupo_legacy_content_mapping_idx_is_active
- lupo_legacy_content_mapping_idx_semantic_url
- lupo_legacy_content_mapping_uk_legacy_url
Index Strategy: derived from TOON index definitions.

## 5. Usage Patterns
Common Queries:
```sql
SELECT * FROM lupo_legacy_content_mapping WHERE mapping_id = :id;
SELECT COUNT(*) AS total FROM lupo_legacy_content_mapping WHERE is_deleted = 0;
SELECT * FROM lupo_legacy_content_mapping ORDER BY mapping_id DESC LIMIT 25;
UPDATE lupo_legacy_content_mapping SET updated_ymdhis = :ts WHERE mapping_id = :id;
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
