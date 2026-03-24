---
lupopedia.headers:
  lupopedia.schema: database_table
  file_path_from_root: lupo-docs/database/lupopedia/tables/active/lupo_permissions.md
  web_path: '[lupo_permissions](http://www.lupopedia.com/database/lupopedia/tables/active/lupo_permissions)'
  last_modified_utc: '20260317'
  channel_id: 42
  actor_id: 1
  actor_name: wolfie
  delegation_chain: wolfie:root
  artifact_type: table_documentation
  artifact_kind: table
  namespace: core
  purpose: Permission definitions and role-based access control; manages what actors
    can do and where
  tags:
  - database
  - table
  - core
  when_updated: '20260324174654'
lupopedia.edges:
  comment: Snapshot of edges for lupo_permissions table doc at 4.0.79 (grounded by
    repo search; non-exhaustive).
  meta: php_hits=3 python_hits=1
  outbound_edges:
  - to: database.table.lupo_permissions
    type: DEFINES_SCHEMA_FOR
    weight: 1.0
  - to: lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql
    type: schema_reference
    weight: 1.0
  - to: debug_captain.php
    type: USED_IN_PHP
    weight: 0.6
  - to: lupo-database/lupopedia/content/lupo-app/Services/SavedCollectionsService.php
    type: USED_IN_PHP
    weight: 0.6
  - to: lupo-database/lupopedia/content/lupo-app/auth/AuthRoleResolver.php
    type: USED_IN_PHP
    weight: 0.6
lupopedia.footer:
  last_verified: '20260317000000'
  last_verified_by: cursor
  last_verified_by_actor_id: 102
  orchestrator: cursor:root
---
# file: lupo_permissions — session: L-LUPO-ROOT-WOLFIE — delegation: wolfie:root — web_path: http://www.lupopedia.com/database/lupopedia/tables/active/lupo_permissions

# Table: lupo_permissions

Canonical table for **permission definitions and role-based access control**. Manages what actors can do and where, supporting fine-grained authorization across the Lupopedia system.

## Purpose

- Define granular permissions for system resources
- Support role-based permission assignment
- Enable permission checking and authorization
- Provide audit trail for permission changes
- Support multi-level permission inheritance

## Schema (install SQL authority)

| Column | Type | Description |
|--------|------|-------------|
| permission_id | bigint NOT NULL | Primary key; **application-supplied** (no AUTO_INCREMENT). |
| actor_id | bigint DEFAULT NULL | Actor this permission is assigned to. |
| resource_type | varchar(64) NOT NULL | Type of resource (channel, project, task, etc.). |
| resource_id | bigint DEFAULT NULL | ID of the specific resource. |
| permission_name | varchar(128) NOT NULL | Human-readable permission name. |
| permission_action | varchar(64) NOT NULL | Action allowed (create, read, update, delete, admin). |
| scope | varchar(64) DEFAULT NULL | Permission scope (global, channel, project, etc.). |
| granted_by_actor_id | bigint DEFAULT NULL | Actor who granted this permission. |
| created_ymdhis | bigint NOT NULL DEFAULT 0 | UTC timestamp when permission was created. |
| updated_ymdhis | bigint NOT NULL DEFAULT 0 | UTC timestamp when permission was last updated. |
| is_deleted | tinyint NOT NULL DEFAULT 0 | Soft delete flag. |
| deleted_ymdhis | bigint DEFAULT NULL | UTC timestamp when permission was deleted. |

## Indexes

- `PRIMARY KEY (permission_id)`
- `INDEX lupo_permissions_idx_actor` ON `lupo_permissions` (`actor_id`)
- `INDEX lupo_permissions_idx_resource` ON `lupo_permissions` (`resource_type`, `resource_id`)
- `INDEX lupo_permissions_idx_permission` ON `lupo_permissions` (`permission_name`)
- `INDEX lupo_permissions_idx_scope` ON `lupo_permissions` (`scope`, `is_deleted`)
- `INDEX lupo_permissions_idx_created` ON `lupo_permissions` (`created_ymdhis`, `is_deleted`)

## Where This Table Is Used

### Core System Usage

- **Authorization system** - Permission checking and enforcement
- **Role management** - Role-based permission assignment
- **Access control** - Resource access validation
- **Security auditing** - Permission change tracking

### Integration Points

- **Authentication middleware** - Permission validation on requests
- **Administrative interfaces** - Permission CRUD operations
- **API endpoints** - Permission checking and role resolution
- **Channel management** - Channel-specific permission enforcement

## Permission Actions

- `create` - Create new resources
- `read` - View or access resources
- `update` - Modify existing resources
- `delete` - Remove resources
- `admin` - Administrative operations

## Resource Types

- `channel` - Channel-related permissions
- `project` - Project-related permissions
- `task` - Task-related permissions
- `system` - System-wide permissions
- `content` - Content management permissions

## Namespace

- **Domain:** Core
- **Subdomain:** Security & Authorization
- **Related Tables:** `lupo_actors`, `lupo_actor_capabilities`, `lupo_roles`
---
# file: lupo_permissions ? web_path: http://www.lupopedia.com/database/lupopedia/tables/active/lupo_permissions
# Table: lupo_permissions

Purpose: Auto-generated documentation for lupo_permissions from TOON schema.
Type: database_table
Status: production_ready
Volume: unknown

## 1. Overview
- Key responsibilities: schema reference, storage, and lookup for this table.
- System role: persists data for the Lupopedia database subsystem.
- Importance: enables data integrity and downstream features tied to this table.

## 2. Schema Reference
Primary Key: permission_id
Field Categories: see full field list below.

### All Fields
| Column | Type | Notes |
|---|---|---|
| permission_id | bigint NOT NULL | from TOON |
| target_type | varchar(64) NOT NULL | from TOON |
| target_id | bigint NOT NULL | from TOON |
| user_id | bigint | from TOON |
| department_id | bigint | from TOON |
| permission | varchar(64) NOT NULL DEFAULT 'read' | from TOON |
| created_ymdhis | bigint NOT NULL DEFAULT 0 | from TOON |
| updated_ymdhis | bigint | from TOON |
| is_deleted | tinyint NOT NULL DEFAULT 0 | from TOON |
| deleted_ymdhis | bigint | from TOON |

## 3. Relationships and Dependencies
- Primary relationships: not specified in TOON relationships array.
- Referencing tables: unknown (use edge suggester tool).
- Integration points: see outbound edges in FLARE footer.

## 4. Indexes and Performance
Primary Indexes:
- permission_id
Performance Indexes:
- lupo_permissions_idx_created_ymdhis
- lupo_permissions_idx_deleted
- lupo_permissions_idx_department
- lupo_permissions_idx_permission
- lupo_permissions_idx_target
- lupo_permissions_idx_user
- lupo_permissions_uniq_target_department
- lupo_permissions_uniq_target_user
Index Strategy: derived from TOON index definitions.

## 5. Usage Patterns
Common Queries:
```sql
SELECT * FROM lupo_permissions WHERE permission_id = :id;
SELECT COUNT(*) AS total FROM lupo_permissions WHERE is_deleted = 0;
SELECT * FROM lupo_permissions ORDER BY permission_id DESC LIMIT 25;
UPDATE lupo_permissions SET updated_ymdhis = :ts WHERE permission_id = :id;
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
