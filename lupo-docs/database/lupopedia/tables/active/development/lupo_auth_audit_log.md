---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "database_table"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/development/lupo_auth_audit_log.md"
  system_version: "4.0.73"
  namespace: "auth"
  channel_id: 1
  actor_id: 1003
  last_modified_utc: "20260312"
  artifact_type: "table_documentation"
  purpose: "Audit logging for authentication events (success, failure, IP tracking)"
  mood_rgb: "4169E1"
  traits: ["canonical", "auth", "audit", "antigravity_rotation", "v4.0.73"]
  tags: ["database", "auth", "audit", "security", "logging"]
  lupo_agent: "antigravity"

lupopedia.edges:
  comment: "Snapshot of files edited during 4.0.73 finalization and initialization thread by ANTIGRAVITY IDE Agent. Edges reflect discovered relationships between database tables and PHP/Python codebase entities. Values should be verified against live database schemas/queries for the most current semantic graph state."
  meta: "Thread: Finalize 4.0.72 → Push to GitHub → Initialize 4.0.73 → Migrate Tasks → Validate Upgrade Path"
  outbound_edges:
    - { to: "lupo-database/lupopedia/toon/lupo_auth_audit_log.toon.json", type: "schema_reference", weight: 1.0 }
    - { to: "lupo-docs/database/lupopedia/tables/active/lupo_auth_users.md", type: "references", weight: 0.9 }
    - { to: "app/Services/AuthService.php", type: "referenced_by", weight: 0.9 }

lupopedia.engagement:
  comment: "Snapshot of files edited during 4.0.73 finalization and initialization thread by ANTIGRAVITY IDE Agent. Engagement metrics track edit frequency and importance of each file in the version transition process."
  meta: "Thread: Finalize 4.0.72 → Push to GitHub → Initialize 4.0.73 → Migrate Tasks → Validate Upgrade Path"
  views: 0

lupopedia.footer:
  version: "4.0.73"
  last_verified: "20260312"
  last_verified_by: "antigravity"
---

# Table: lupo_auth_audit_log

Purpose: Auto-generated documentation for lupo_auth_audit_log from TOON schema.
Type: database_table
Status: production_ready
Volume: unknown

## 1. Overview
- Key responsibilities: schema reference, storage, and lookup for this table.
- System role: persists data for the Lupopedia database subsystem.
- Importance: enables data integrity and downstream features tied to this table.

## 2. Schema Reference
Primary Key: auth_audit_log_id
Field Categories: see full field list below.

### All Fields
| Column | Type | Notes |
|---|---|---|
| auth_audit_log_id | bigint NOT NULL | from TOON |
| user_id | bigint | from TOON |
| crafty_operator_id | int | from TOON |
| event_type | varchar(50) NOT NULL | from TOON |
| system_context | varchar(50) NOT NULL | from TOON |
| ip_address | varchar(45) | from TOON |
| user_agent | text | from TOON |
| event_data | json | from TOON |
| success | tinyint NOT NULL DEFAULT 1 | from TOON |
| error_message | text | from TOON |
| created_at | bigint | from TOON |
| updated_at | bigint | from TOON |

## 3. Relationships and Dependencies
- Primary relationships: not specified in TOON relationships array.
- Referencing tables: unknown (use edge suggester tool).
- Integration points: see outbound edges in FLARE footer.

## 4. Indexes and Performance
Primary Indexes:
- auth_audit_log_id
Performance Indexes:
- lupo_auth_audit_log_idx_crafty_operator_id
- lupo_auth_audit_log_idx_created_at
- lupo_auth_audit_log_idx_event_type
- lupo_auth_audit_log_idx_success
- lupo_auth_audit_log_idx_system_context
- lupo_auth_audit_log_idx_user_id
Index Strategy: derived from TOON index definitions.

## 5. Usage Patterns
Common Queries:
```sql
SELECT * FROM lupo_auth_audit_log WHERE auth_audit_log_id = :id;
SELECT COUNT(*) AS total FROM lupo_auth_audit_log WHERE is_deleted = 0;
SELECT * FROM lupo_auth_audit_log ORDER BY auth_audit_log_id DESC LIMIT 25;
UPDATE lupo_auth_audit_log SET updated_ymdhis = :ts WHERE auth_audit_log_id = :id;
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
