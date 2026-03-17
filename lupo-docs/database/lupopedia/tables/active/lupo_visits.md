---
lupopedia.headers:
  lupopedia.version: "4.0.79"
  lupopedia.schema: "database_table"
  system_version: "4.0.79"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_visits.md"
  web_path: "[lupo_visits](http://www.lupopedia.com/database/lupopedia/tables/active/lupo_visits)"
  last_modified_utc: "20260317"
  channel_id: 42
  actor_id: 102
  actor_name: "cursor"
  delegation_chain: "cursor:root"
  artifact_type: "table_documentation"
  artifact_kind: "table"
  namespace: "analytics"
  purpose: "Visit and session analytics; traffic and referrer tracking"
  tags: ["database", "table", "analytics"]

lupopedia.edges:
  comment: "Snapshot of edges for lupo_visits table doc at 4.0.79 (grounded by repo search; non-exhaustive)."
  meta: "php_hits=3 python_hits=2"
  outbound_edges:
    - { to: "database.table.lupo_visits", type: "DEFINES_SCHEMA_FOR", weight: 1.0 }
    - { to: "lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql", type: "schema_reference", weight: 1.0 }
    - { to: "admin.php", type: "USED_IN_PHP", weight: 0.6 }
    - { to: "check_db_state.php", type: "USED_IN_PHP", weight: 0.6 }
    - { to: "lupo-scripts/audit_schema_doctrine.php", type: "USED_IN_PHP", weight: 0.7 }
    - { to: "analyze_unused_tables.py", type: "USED_IN_PYTHON", weight: 0.5 }
    - { to: "lupo-scripts/audit_schema_doctrine.py", type: "USED_IN_PYTHON", weight: 0.5 }

lupopedia.footer:
  version: "4.0.79"
  last_verified: "20260317"
  last_verified_by: "cursor"
---
# file: lupo_visits ? web_path: http://www.lupopedia.com/database/lupopedia/tables/active/lupo_visits
# Table: lupo_visits

Purpose: Auto-generated documentation for lupo_visits from TOON schema.
Type: database_table
Status: production_ready
Volume: unknown

## 1. Overview
- Key responsibilities: schema reference, storage, and lookup for this table.
- System role: persists data for the Lupopedia database subsystem.
- Importance: enables data integrity and downstream features tied to this table.

## 2. Schema Reference
Primary Key: visit_id
Field Categories: see full field list below.

### All Fields
| Column | Type | Notes |
|---|---|---|
| visit_id | bigint NOT NULL auto_increment | from TOON |
| content_id | bigint NOT NULL DEFAULT 0 | from TOON |
| actor_id | bigint NOT NULL DEFAULT 0 | from TOON |
| page_url | varchar(500) NOT NULL | from TOON |
| page_domain | varchar(255) NOT NULL | from TOON |
| page_path | varchar(500) NOT NULL | from TOON |
| date_ymd | int NOT NULL | from TOON |
| visits | int NOT NULL DEFAULT 0 | from TOON |
| depth | int NOT NULL DEFAULT 0 | from TOON |
| metadata_json | json | from TOON |
| created_ymdhis | bigint NOT NULL DEFAULT 0 | from TOON |
| updated_ymdhis | bigint NOT NULL DEFAULT 0 | from TOON |

## 3. Relationships and Dependencies
- Primary relationships: not specified in TOON relationships array.
- Referencing tables: unknown (use edge suggester tool).
- Integration points: see outbound edges in FLARE footer.

## 4. Indexes and Performance
Primary Indexes:
- visit_id
Performance Indexes:
- lupo_visits_content_id
- lupo_visits_date_ymd
- lupo_visits_page_domain
Index Strategy: derived from TOON index definitions.

## 5. Usage Patterns
Common Queries:
```sql
SELECT * FROM lupo_visits WHERE visit_id = :id;
SELECT COUNT(*) AS total FROM lupo_visits WHERE is_deleted = 0;
SELECT * FROM lupo_visits ORDER BY visit_id DESC LIMIT 25;
UPDATE lupo_visits SET updated_ymdhis = :ts WHERE visit_id = :id;
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
