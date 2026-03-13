---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "database_table"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_metadata.md"
  system_version: "4.0.73"
  namespace: "core"
  channel_id: 1
  actor_id: 1003
  last_modified_utc: "20260313"
  artifact_type: "table_documentation"
  purpose: "Complete documentation for lupo_metadata table - generalized entity metadata storage"
  mood_rgb: "4169E1"
  traits: ["canonical", "core_system", "metadata", "antigravity_rotation", "v4.0.73"]
  tags: ["database", "metadata", "entity_properties"]
  lupo_agent: "antigravity"

lupopedia.edges:
  comment: "Snapshot of files edited during 4.0.73 finalization and initialization thread by ANTIGRAVITY IDE Agent. Edges reflect discovered relationships between database tables and PHP/Python codebase entities. Values should be verified against live database schemas/queries for the most current semantic graph state."
  meta: "Thread: Finalize 4.0.72 → Push to GitHub → Initialize 4.0.73 → Migrate Tasks → Validate Upgrade Path"
  outbound_edges:
    - { to: "lupo-database/lupopedia/toon/lupo_metadata.toon.json", type: "schema_reference", weight: 1.0, reason: "TOON schema definition", db_source: "lupo_metadata" }
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

# Table: lupo_metadata

Purpose: Auto-generated documentation for lupo_metadata from TOON schema.
Type: database_table
Status: production_ready
Volume: unknown

## 1. Overview
- Key responsibilities: schema reference, storage, and lookup for this table.
- System role: persists data for the Lupopedia database subsystem.
- Importance: enables data integrity and downstream features tied to this table.

## 2. Schema Reference
Primary Key: metadata_id
Field Categories: see full field list below.

### All Fields
| Column | Type | Notes |
|---|---|---|
| metadata_id | bigint NOT NULL | from TOON |
| entity_type | varchar(32) NOT NULL | from TOON |
| entity_id | bigint NOT NULL | from TOON |
| domain_id | bigint | from TOON |
| meta_type | varchar(64) | from TOON |
| property_key | varchar(255) NOT NULL | from TOON |
| property_value | text | from TOON |
| created_ymdhis | bigint NOT NULL DEFAULT 0 | from TOON |
| updated_ymdhis | bigint NOT NULL | from TOON |
| is_deleted | tinyint NOT NULL DEFAULT 0 | from TOON |
| deleted_ymdhis | bigint | from TOON |

## 3. Relationships and Dependencies
- Primary relationships: not specified in TOON relationships array.
- Referencing tables: unknown (use edge suggester tool).
- Integration points: see outbound edges in lupopedia.edges.

## 4. Indexes and Performance
Primary Indexes:
- metadata_id
Performance Indexes:
- lupo_metadata_idx_created_ymdhis
- lupo_metadata_idx_domain
- lupo_metadata_idx_entity
- lupo_metadata_idx_is_deleted
- lupo_metadata_idx_meta_type
- lupo_metadata_idx_property_key
- lupo_metadata_idx_updated_ymdhis
- lupo_metadata_unique_entity_domain_property
Index Strategy: derived from TOON index definitions.

## 5. Usage Patterns
Common Queries:
```sql
SELECT * FROM lupo_metadata WHERE metadata_id = :id;
SELECT COUNT(*) AS total FROM lupo_metadata WHERE is_deleted = 0;
SELECT * FROM lupo_metadata ORDER BY metadata_id DESC LIMIT 25;
UPDATE lupo_metadata SET updated_ymdhis = :ts WHERE metadata_id = :id;
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
