---
lupopedia.headers:
  lupopedia.version: "4.0.79"
  lupopedia.schema: "database_table"
  system_version: "4.0.79"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_actor_actions.md"
  web_path: "[lupo_actor_actions](http://www.lupopedia.com/database/lupopedia/tables/active/lupo_actor_actions)"
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
  comment: "Snapshot of edges for lupo_actor_actions table doc at 4.0.79 (grounded by repo search; non-exhaustive)."
  meta: "php_hits=2 python_hits=1"
  outbound_edges:
    - { to: "database.table.lupo_actor_actions", type: "DEFINES_SCHEMA_FOR", weight: 1.0 }
    - { to: "lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql", type: "schema_reference", weight: 1.0 }
    - { to: "lupo-scripts/verify_architecture_files.php", type: "USED_IN_PHP", weight: 0.7 }
    - { to: "lupo-scripts/verify_grounded_architecture.php", type: "USED_IN_PHP", weight: 0.7 }
    - { to: "lupo-scripts/wolfie_orms.py", type: "USED_IN_PYTHON", weight: 0.5 }

lupopedia.footer:
  version: "4.0.79"
  last_verified: "20260317"
  last_verified_by: "cursor"
---
# file: lupo_actor_actions ? web_path: http://www.lupopedia.com/database/lupopedia/tables/active/lupo_actor_actions
# Table: lupo_actor_actions

Purpose: Auto-generated documentation for lupo_actor_actions from TOON schema.
Type: database_table
Status: production_ready
Volume: unknown

## 1. Overview
- Key responsibilities: schema reference, storage, and lookup for this table.
- System role: persists data for the Lupopedia database subsystem.
- Importance: enables data integrity and downstream features tied to this table.

## 2. Schema Reference
Primary Key: actor_action_id
Field Categories: see full field list below.

### All Fields
| Column | Type | Notes |
|---|---|---|
| actor_action_id | bigint NOT NULL | from TOON |
| actor_id | bigint NOT NULL | from TOON |
| action_type | varchar(64) NOT NULL | from TOON |
| entity_type | varchar(64) | from TOON |
| entity_id | bigint | from TOON |
| description | text | from TOON |
| metadata_json | json | from TOON |
| created_ymdhis | bigint NOT NULL DEFAULT 0 | from TOON |

## 3. Relationships and Dependencies
- Primary relationships: not specified in TOON relationships array.
- Referencing tables: unknown (use edge suggester tool).
- Integration points: see outbound edges in FLARE footer.

## 4. Indexes and Performance
Primary Indexes:
- actor_action_id
Performance Indexes:
- lupo_actor_actions_idx_action_type
- lupo_actor_actions_idx_actor
- lupo_actor_actions_idx_entity
Index Strategy: derived from TOON index definitions.

## 5. Usage Patterns
Common Queries:
```sql
SELECT * FROM lupo_actor_actions WHERE actor_action_id = :id;
SELECT COUNT(*) AS total FROM lupo_actor_actions WHERE is_deleted = 0;
SELECT * FROM lupo_actor_actions ORDER BY actor_action_id DESC LIMIT 25;
UPDATE lupo_actor_actions SET updated_ymdhis = :ts WHERE actor_action_id = :id;
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
