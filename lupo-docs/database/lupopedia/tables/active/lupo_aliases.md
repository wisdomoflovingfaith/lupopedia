---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "database_table"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_aliases.md"
  system_version: "4.0.73"
  namespace: "core"
  channel_id: 1
  actor_id: 1003
  last_modified_utc: "20260313"
  artifact_type: "table_documentation"
  purpose: "Complete documentation for lupo_aliases table - semantic alias management and slug resolution"
  mood_rgb: "4169E1"
  traits: ["canonical", "core_system", "aliases", "antigravity_rotation", "v4.0.73"]
  tags: ["database", "aliases", "slugs", "semantic_mapping"]
  lupo_agent: "antigravity"

lupopedia.edges:
  comment: "Snapshot of files edited during 4.0.73 finalization and initialization thread by ANTIGRAVITY IDE Agent. Edges reflect discovered relationships between database tables and PHP/Python codebase entities. Values should be verified against live database schemas/queries for the most current semantic graph state."
  meta: "Thread: Finalize 4.0.72 → Push to GitHub → Initialize 4.0.73 → Migrate Tasks → Validate Upgrade Path"
  outbound_edges:
    - { to: "lupo-database/lupopedia/toon/lupo_aliases.toon.json", type: "schema_reference", weight: 1.0, reason: "TOON schema definition", db_source: "lupo_aliases" }
    - { to: "docs/doctrine/database/README.md", type: "references", weight: 0.8, reason: "Database doctrine" }

lupopedia.engagement:
  comment: "Snapshot of files edited during 4.0.73 finalization and initialization thread by ANTIGRAVITY IDE Agent. Engagement metrics track edit frequency and importance of each file in the version transition process."
  meta: "Thread: Finalize 4.0.72 → Push to GitHub → Initialize 4.0.73 → Migrate Tasks → Validate Upgrade Path"
  views: 0

lupopedia.footer:
  version: "4.0.73"
  last_verified: "20260313"
  last_verified_by: "antigravity"
---

# Table: lupo_aliases

Purpose: Auto-generated documentation for lupo_aliases from TOON schema.
Type: database_table
Status: production_ready
Volume: unknown

## 1. Overview
- Key responsibilities: schema reference, storage, and lookup for this table.
- System role: persists data for the Lupopedia database subsystem.
- Importance: enables data integrity and downstream features tied to this table.

## 2. Schema Reference
Primary Key: alias_id
Field Categories: see full field list below.

### All Fields
| Column | Type | Notes |
|---|---|---|
| alias_id | int NOT NULL | from TOON |
| slug | varchar(255) NOT NULL | from TOON |
| alias | varchar(255) NOT NULL | from TOON |
| alias_type | varchar(50) DEFAULT 'semantic' | from TOON |
| created_at | bigint | from TOON |

## 3. Relationships and Dependencies
- Primary relationships: not specified in TOON relationships array.
- Referencing tables: unknown (use edge suggester tool).
- Integration points: see outbound edges in lupopedia.edges.

## 4. Indexes and Performance
Primary Indexes:
- alias_id
Performance Indexes:
- lupo_aliases_idx_slug
- lupo_aliases_uniq_alias
Index Strategy: derived from TOON index definitions.

## 5. Usage Patterns
Common Queries:
```sql
SELECT * FROM lupo_aliases WHERE alias_id = :id;
SELECT COUNT(*) AS total FROM lupo_aliases WHERE is_deleted = 0;
SELECT * FROM lupo_aliases ORDER BY alias_id DESC LIMIT 25;
UPDATE lupo_aliases SET updated_ymdhis = :ts WHERE alias_id = :id;
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
