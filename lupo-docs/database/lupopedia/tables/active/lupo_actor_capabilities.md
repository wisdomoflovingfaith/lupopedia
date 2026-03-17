---
lupopedia.headers:
  lupopedia.version: "4.0.79"
  lupopedia.schema: "database_table"
  system_version: "4.0.79"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_actor_capabilities.md"
  web_path: "[lupo_actor_capabilities](http://www.lupopedia.com/database/lupopedia/tables/active/lupo_actor_capabilities)"
  last_modified_utc: "20260317"
  channel_id: 42
  actor_id: 1
  actor_name: "wolfie"
  delegation_chain: "wolfie:root"
  artifact_type: "table_documentation"
  artifact_kind: "table"
  namespace: "core"
  purpose: "Actor capability definitions and permissions; links actors to specific capabilities and roles"
  tags: ["database", "table", "core"]

lupopedia.edges:
  comment: "Snapshot of edges for lupo_actor_capabilities table doc at 4.0.79 (grounded by repo search; non-exhaustive)."
  meta: "php_hits=1 python_hits=1"
  outbound_edges:
    - { to: "database.table.lupo_actor_capabilities", type: "DEFINES_SCHEMA_FOR", weight: 1.0 }
    - { to: "lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql", type: "schema_reference", weight: 1.0 }
    - { to: "lupo-scripts/verify_grounded_architecture.php", type: "USED_IN_PHP", weight: 0.7 }
    - { to: "lupo-scripts/wolfie_orms.py", type: "USED_IN_PYTHON", weight: 0.5 }

lupopedia.footer:
  version: "4.0.79"
  last_verified: "20260317"
  last_verified_by: "wolfie"
---
# file: lupo_actor_capabilities — session: L-LUPO-ROOT-WOLFIE — delegation: wolfie:root — web_path: http://www.lupopedia.com/database/lupopedia/tables/active/lupo_actor_capabilities

# Table: lupo_actor_capabilities

Canonical table for **actor capability definitions and role assignments**. Links actors to specific capabilities, optionally scoped by channel or context.

## Purpose

- Define what capabilities each actor possesses
- Support role-based capability resolution
- Enable capability checking in authorization flows
- Support dynamic capability assignment per channel/context
- Link capabilities to actors for permission resolution

## Schema (install SQL authority)

| Column | Type | Description |
|--------|------|-------------|
| capability_id | bigint NOT NULL | Primary key; **application-supplied** (no AUTO_INCREMENT). |
| actor_id | bigint NOT NULL | Actor this capability applies to. |
| capability_name | varchar(128) NOT NULL | Human-readable capability identifier. |
| capability_type | varchar(64) NOT NULL | Type of capability (read, write, admin, etc.). |
| scope | varchar(64) DEFAULT NULL | Optional scope (channel_id, project_id, global). |
| is_active | tinyint NOT NULL DEFAULT 1 | Whether this capability is currently active. |
| created_ymdhis | bigint NOT NULL DEFAULT 0 | UTC timestamp when capability was assigned. |
| updated_ymdhis | bigint NOT NULL DEFAULT 0 | UTC timestamp when capability was last updated. |
| is_deleted | tinyint NOT NULL DEFAULT 0 | Soft delete flag. |
| deleted_ymdhis | bigint DEFAULT NULL | UTC timestamp when capability was deleted. |

## Indexes

- `PRIMARY KEY (capability_id)`
- `INDEX lupo_actor_capabilities_idx_actor` ON `lupo_actor_capabilities` (`actor_id`)
- `INDEX lupo_actor_capabilities_idx_type` ON `lupo_actor_capabilities` (`capability_type`)
- `INDEX lupo_actor_capabilities_idx_scope` ON `lupo_actor_capabilities` (`scope`)
- `INDEX lupo_actor_capabilities_idx_active` ON `lupo_actor_capabilities` (`is_active`, `is_deleted`)

## Where This Table Is Used

### Core System Usage

- **ActorService** - Capability resolution and checking
- **AuthRoleResolver** - Role-to-capability mapping
- **Authorization middleware** - Capability-based access control
- **Channel permission checks** - Scope-based capability validation

### Integration Points

- **Actor registration** - Capabilities assigned during actor onboarding
- **Role management** - Capabilities grouped into roles
- **Channel access** - Capabilities validated per channel context
- **API authorization** - Capability checks before resource access

## Namespace

- **Domain:** Core
- **Subdomain:** Actor Management
- **Related Tables:** `lupo_actors`, `lupo_actor_channels`, `lupo_permissions`

Purpose: Auto-generated documentation for lupo_actor_capabilities from TOON schema.
Type: database_table
Status: production_ready
Volume: unknown

## 1. Overview
- Key responsibilities: schema reference, storage, and lookup for this table.
- System role: persists data for the Lupopedia database subsystem.
- Importance: enables data integrity and downstream features tied to this table.

## 2. Schema Reference
Primary Key: actor_capability_id
Field Categories: see full field list below.

### All Fields
| Column | Type | Notes |
|---|---|---|
| actor_capability_id | bigint NOT NULL | from TOON |
| actor_id | bigint NOT NULL | from TOON |
| domain_id | bigint NOT NULL | from TOON |
| capability_key | varchar(100) NOT NULL | from TOON |
| capability_description | text | from TOON |
| created_ymdhis | bigint NOT NULL DEFAULT 0 | from TOON |
| updated_ymdhis | bigint | from TOON |
| is_deleted | tinyint NOT NULL DEFAULT 0 | from TOON |
| deleted_ymdhis | bigint | from TOON |
| scope_limitation | varchar(50) DEFAULT 'unrestricted' | from TOON |
| max_calls_per_hour | int DEFAULT 0 | from TOON |
| requires_approval | tinyint DEFAULT 0 | from TOON |
| approval_agent_id | bigint | from TOON |

## 3. Relationships and Dependencies
- Primary relationships: not specified in TOON relationships array.
- Referencing tables: unknown (use edge suggester tool).
- Integration points: see outbound edges in FLARE footer.

## 4. Indexes and Performance
Primary Indexes:
- actor_capability_id
Performance Indexes:
- lupo_actor_capabilities_idx_agent_domain
- lupo_actor_capabilities_idx_capability_key
- lupo_actor_capabilities_idx_created_ymdhis
- lupo_actor_capabilities_idx_domain_id
- lupo_actor_capabilities_idx_is_deleted
- lupo_actor_capabilities_idx_updated_ymdhis
- lupo_actor_capabilities_unique_agent_domain_capability
Index Strategy: derived from TOON index definitions.

## 5. Usage Patterns
Common Queries:
```sql
SELECT * FROM lupo_actor_capabilities WHERE actor_capability_id = :id;
SELECT COUNT(*) AS total FROM lupo_actor_capabilities WHERE is_deleted = 0;
SELECT * FROM lupo_actor_capabilities ORDER BY actor_capability_id DESC LIMIT 25;
UPDATE lupo_actor_capabilities SET updated_ymdhis = :ts WHERE actor_capability_id = :id;
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
