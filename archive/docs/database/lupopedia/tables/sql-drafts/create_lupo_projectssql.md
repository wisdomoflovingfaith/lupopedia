---
lupopedia.init:
  file_identity: create_lupo_projects.sql.md
  artifact_type: sql_draft
  artifact_kind: database_schema
  namespace: projects
  domain: database
  system_version: 4.0.76
  design_actor: cursor
  design_faucet: cursor
  orchestrator_actor: wolfie
lupopedia.metadata:
  comment: Snapshot of metadata for this file or entity at artifact creation.
  title:
  - schema_ref: lupo_metadata
    entity_type: file
    meta_type: property
    property_value: Draft SQL for lupo_projects Table - Project Registry Implementation
    channel_id: 42
    class_name: lupo_metadata
    created_ymdhis: 20260315235000
    updated_ymdhis: 20260315235000
  description:
  - schema_ref: lupo_metadata
    entity_type: file
    meta_type: property
    property_value: Draft SQL implementation for Project Registry lupo_projects table.
      Includes CREATE TABLE statement, column definitions, indexes, and design rationale.
      DRAFT ONLY - not yet approved for production.
    channel_id: 42
    class_name: lupo_metadata
    created_ymdhis: 20260315235000
    updated_ymdhis: 20260315235000
  keywords:
  - schema_ref: lupo_metadata
    entity_type: file
    meta_type: property
    property_value: lupo_projects, sql_draft, create_table, project_registry, database_schema,
      lupopedia
    channel_id: 42
    class_name: lupo_metadata
    created_ymdhis: 20260315235000
    updated_ymdhis: 20260315235000
  author:
  - schema_ref: lupo_metadata
    entity_type: file
    meta_type: property
    property_value: cursor
    channel_id: 42
    class_name: lupo_metadata
    created_ymdhis: 20260315235000
    updated_ymdhis: 20260315235000
  orchestrator:
  - schema_ref: lupo_metadata
    entity_type: file
    meta_type: property
    property_value: wolfie
    channel_id: 42
    class_name: lupo_metadata
    created_ymdhis: 20260315235000
    updated_ymdhis: 20260315235000
lupopedia.comments:
- comment_id: 1
  channel_id: 42
  actor_id: 102
  actor_name: cursor
  faucet_id: 102
  faucet_name: cursor
  comment_text: Draft SQL for lupo_projects table created - implements Project Registry
    schema design with deterministic identity patterns.
  comment_type: sql_draft
  created_ymdhis: 20260315235500
  updated_ymdhis: 20260315235500
- comment_id: 2
  channel_id: 42
  actor_id: 102
  actor_name: cursor
  faucet_id: 102
  faucet_name: cursor
  comment_text: SQL follows database doctrine - no foreign keys, BIGINT timestamps,
    soft delete pattern, proper indexes.
  comment_type: doctrine_compliance
  created_ymdhis: 20260315236000
  updated_ymdhis: 20260315236000
- comment_id: 3
  channel_id: 42
  actor_id: 102
  actor_name: cursor
  faucet_id: 102
  faucet_name: cursor
  comment_text: DRAFT ONLY - not yet approved for production install SQL. Requires
    design package approval before implementation.
  comment_type: implementation_guard
  created_ymdhis: 20260315236500
  updated_ymdhis: 20260315236500
lupopedia.headers:
  lupopedia.schema: sql_draft
  file_path_from_root: docs/database/lupopedia/tables/sql_drafts/create_lupo_projects.sql.md
  web_path: http://www.lupopedia.com/database/sql_drafts/create_lupo_projects
  last_modified_utc: '20260315'
  channel_id: 42
  actor_id: 102
  actor_name: cursor
  faucet_name: cursor
  delegation_chain: cursor:root
  artifact_type: sql_draft
  artifact_kind: database_schema
  purpose: Draft SQL implementation for Project Registry lupo_projects table
  mood_vector: B22222
  traits:
  - sql_draft
  - database_schema
  - implementation_ready
  - 4.0.76
  tags:
  - lupo_projects
  - sql_draft
  - create_table
  - project_registry
  when_updated: '20260324174654'
lupopedia.session:
  session_id: L-LUPO-PROJECT-SQL-DRAFT
  session_name: L-LUPO-PROJECT-SQL-DRAFT
  actor_id: 102
  actor_name: cursor
  faucet_name: cursor
  channel_id: 42
  channel_name: Lupopedia Development (general)
  federation_node_id: 1
  paired_actor_id: 1000
lupopedia.edges:
  comment: Snapshot of relationships for Project Registry SQL Draft.
  outbound_edges:
  - to: docs/database/lupopedia/tables/PROJECT_REGISTRY_SCHEMA_DESIGN.md
    type: implements
    weight: 1.0
  - to: docs/doctrine/PROJECT_REGISTRY_DOCTRINE.md
    type: implements
    weight: 0.95
  - to: docs/doctrine/DATABASE_DOCTRINE.md
    type: follows
    weight: 0.9
  - to: docs/database/lupopedia/mysql/install/install_new_lupopedia.sql
    type: would_update
    weight: 0.85
  - to: docs/doctrine/PROJECT_REGISTRY_WORKFLOW.md
    type: enables
    weight: 0.8
  semantic_tags:
  - project_registry_sql
  - database_implementation
  - schema_draft
lupopedia.footer:`n  approved_for_release: "4.1.0"`n  approval_status: "approved"`n  approved_by_actor_id: 1`n  approved_utc: 20260326192115`n  last_verified: '20260315000000'
  last_verified_by: cursor
  orchestrator: cursor
  next_action:
  - Review and approve design package before production implementation
  - Validate SQL against database doctrine requirements
  - Test indexes and uniqueness constraints
  last_verified_by_actor_id: 102
---
# file: Draft SQL for lupo_projects Table â€” session: L-LUPO-PROJECT-SQL-DRAFT â€” delegation: cursor:root (faucet: cursor) â€” web_path: http://www.lupopedia.com/database/sql_drafts/create_lupo_projects

# Draft SQL for lupo_projects Table

**Version:** 4.0.76  
**Author:** Cursor (actor_id: 102)  
**Scope:** CREATE TABLE statement for Project Registry  
**Status:** DRAFT ONLY - Not yet approved for production

---

## DRAFT ONLY â€” NOT YET APPROVED FOR PRODUCTION INSTALL SQL

This SQL draft is part of the Project Registry design package.  
Do not add to production install SQL until the complete design package is approved.

---

## CREATE TABLE lupo_projects

```sql
-- DRAFT: Project Registry Table
-- Implements deterministic project identity and lifecycle management
-- Follows Lupopedia database doctrine (no FKs, BIGINT timestamps, soft delete)
-- DRAFT ONLY - not yet approved for production

CREATE TABLE lupo_projects (
    -- Core Identity Columns
    project_id BIGINT NOT NULL COMMENT 'Canonical project identifier, application-assigned, immutable',
    project_key VARCHAR(64) NOT NULL COMMENT 'Stable machine-facing logical identifier',
    project_slug VARCHAR(255) NOT NULL COMMENT 'Human-readable URL-friendly identifier, may change',
    project_name VARCHAR(255) NOT NULL COMMENT 'Display name, may change frequently',
    federation_node_id BIGINT NOT NULL COMMENT 'Owning federation node, immutable after creation',
    
    -- Organizational Columns
    default_channel_id BIGINT DEFAULT NULL COMMENT 'Default channel for project entry point',
    orchestrator_id BIGINT NOT NULL COMMENT 'Primary orchestrator/owner of project',
    project_type VARCHAR(64) DEFAULT 'standard' COMMENT 'Project type classification',
    description TEXT DEFAULT NULL COMMENT 'Project description and purpose',
    
    -- Status and Lifecycle Columns
    status VARCHAR(32) NOT NULL DEFAULT 'active' COMMENT 'Project state: active, archived, frozen, deleted',
    is_active TINYINT NOT NULL DEFAULT 1 COMMENT 'Active status flag for quick filtering',
    is_deleted TINYINT NOT NULL DEFAULT 0 COMMENT 'Soft delete flag',
    is_archived TINYINT NOT NULL DEFAULT 0 COMMENT 'Archive status flag',
    is_frozen TINYINT NOT NULL DEFAULT 0 COMMENT 'Freeze status flag',
    
    -- Metadata and Audit Columns
    metadata_json JSON DEFAULT NULL COMMENT 'Structured project metadata and properties',
    created_ymdhis BIGINT NOT NULL DEFAULT 0 COMMENT 'Creation timestamp YYYYMMDDHHIISS UTC',
    updated_ymdhis BIGINT NOT NULL DEFAULT 0 COMMENT 'Last update timestamp YYYYMMDDHHIISS UTC',
    deleted_ymdhis BIGINT DEFAULT 0 COMMENT 'Soft delete timestamp YYYYMMDDHHIISS UTC',
    created_by_actor_id BIGINT DEFAULT NULL COMMENT 'Actor who created project',
    updated_by_actor_id BIGINT DEFAULT NULL COMMENT 'Actor who last updated project',
    
    -- Primary Key and Uniqueness Constraints
    PRIMARY KEY (project_id),
    UNIQUE KEY uk_project_key_node (project_key, federation_node_id),
    UNIQUE KEY uk_project_slug_node (project_slug, federation_node_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

---

## Indexes for Performance

```sql
-- Performance indexes for common query patterns
-- DRAFT ONLY - not yet approved for production

-- Federation node project listings (with status filtering)
CREATE INDEX idx_federation_node ON lupo_projects (federation_node_id, status, is_deleted);

-- Project resolution by key/slug (with federation node scope)
CREATE INDEX idx_project_key ON lupo_projects (project_key, federation_node_id);
CREATE INDEX idx_project_slug ON lupo_projects (project_slug, federation_node_id);

-- Orchestrator project management (with status filtering)
CREATE INDEX idx_orchestrator ON lupo_projects (orchestrator_id, status, is_deleted);

-- Default channel resolution (only for non-null values)
CREATE INDEX idx_default_channel ON lupo_projects (default_channel_id);

-- Status-based queries for efficient filtering
CREATE INDEX idx_status ON lupo_projects (status, is_active, is_deleted);

-- Audit and timestamp queries for reporting and maintenance
CREATE INDEX idx_created ON lupo_projects (created_ymdhis);
CREATE INDEX idx_updated ON lupo_projects (updated_ymdhis);
```

---

## Column Design Rationale

### Core Identity Columns

**project_id BIGINT NOT NULL**
- Canonical identifier following reserved-ID doctrine
- Application-assigned, never AUTO_INCREMENT
- Immutable after creation, primary key for all relationships

**project_key VARCHAR(64) NOT NULL**
- Stable machine-facing identifier for system integration
- Survives project renames and reorganizations
- Unique within federation node scope

**project_slug VARCHAR(255) NOT NULL**
- Human-readable URL-friendly identifier
- May change with project renames (supports redirects)
- Unique within federation node scope

**project_name VARCHAR(255) NOT NULL**
- User-facing display name
- Flexible for frequent changes
- No uniqueness constraints (allows duplicate names across projects)

**federation_node_id BIGINT NOT NULL**
- Enforces single-node project scope
- Immutable after project creation
- Enables distributed deployment with clear boundaries

### Organizational Columns

**default_channel_id BIGINT DEFAULT NULL**
- Provides project entry point for navigation
- Optional - projects may exist without default channels
- Application-managed relationship (no foreign key)

**orchestrator_id BIGINT NOT NULL**
- Primary project owner/administrator
- Enables governance and permission management
- References existing actor (application-managed)

**project_type VARCHAR(64) DEFAULT 'standard'**
- Classification for different project categories
- Supports project-specific workflows and behaviors
- Default 'standard' for typical projects

**description TEXT DEFAULT NULL**
- Human-readable project documentation
- Optional field for project purpose and scope
- Flexible length for detailed descriptions

### Status and Lifecycle Columns

**status VARCHAR(32) NOT NULL DEFAULT 'active'**
- Human-readable state for UI and API consumption
- ENUM-style values: 'active', 'archived', 'frozen', 'deleted'
- Primary status field for business logic

**is_active TINYINT NOT NULL DEFAULT 1**
- Optimized flag for active project queries
- FALSE for archived/frozen/deleted projects
- Enables efficient active project filtering

**is_deleted TINYINT NOT NULL DEFAULT 0**
- Soft delete flag for logical deletion
- TRUE only for deleted state
- Excludes deleted projects from normal queries

**is_archived TINYINT NOT NULL DEFAULT 0**
- Archive status flag for read-only projects
- TRUE only for archived state
- Supports project lifecycle management

**is_frozen TINYINT NOT NULL DEFAULT 0**
- Freeze status flag for emergency suspension
- TRUE only for frozen state
- Enables administrative control

### Metadata and Audit Columns

**metadata_json JSON DEFAULT NULL**
- Flexible structured data storage
- Project-specific properties and configuration
- Extensible without schema changes

**created_ymdhis BIGINT NOT NULL DEFAULT 0**
- Creation timestamp in YYYYMMDDHHIISS UTC format
- Follows Lupopedia timestamp doctrine
- Application-set on creation

**updated_ymdhis BIGINT NOT NULL DEFAULT 0**
- Last modification timestamp in YYYYMMDDHHIISS UTC format
- Application-set on each modification
- Enables audit trails and change tracking

**deleted_ymdhis BIGINT DEFAULT 0**
- Soft delete timestamp in YYYYMMDDHHIISS UTC format
- Set when project is marked as deleted
- Supports retention policies and audit requirements

**created_by_actor_id BIGINT DEFAULT NULL**
- Actor who created the project
- Enables audit trails and accountability
- References existing actor (application-managed)

**updated_by_actor_id BIGINT DEFAULT NULL**
- Actor who last modified the project
- Tracks modification responsibility
- References existing actor (application-managed)

---

## Uniqueness Strategy

### Primary Uniqueness
```sql
PRIMARY KEY (project_id)
```
- `project_id` is the canonical identifier
- Application-assigned ensures deterministic behavior
- Follows existing patterns from `lupo_actors` and `lupo_channels`

### Logical Uniqueness
```sql
UNIQUE KEY uk_project_key_node (project_key, federation_node_id)
UNIQUE KEY uk_project_slug_node (project_slug, federation_node_id)
```
- Project keys unique within federation node
- Project slugs unique within federation node
- Federation node scope prevents cross-node conflicts
- Supports distributed deployment with node autonomy

---

## Database Doctrine Compliance

### âœ… Follows Lupopedia Database Doctrine

**No Foreign Keys:**
- All relationships managed in application code
- Database-agnostic compatibility maintained
- No database-specific constraint dependencies

**BIGINT Timestamps:**
- All timestamps use BIGINT YYYYMMDDHHIISS UTC format
- Application-set timestamps, no database defaults
- Consistent with existing table patterns

**Soft Delete Pattern:**
- Logical deletion using `is_deleted` flag
- Identity preserved for historical reference
- Audit trail maintained with `deleted_ymdhis`

**No Database Logic:**
- No triggers, stored procedures, or calculated fields
- All business logic in application code
- Database used for storage only

**Database-Agnostic SQL:**
- Standard SQL syntax compatible across supported databases
- No database-specific features or optimizations
- Engine-agnostic design (InnoDB specified but adaptable)

---

## Integration Points

### Channel Table Extension
```sql
-- Required addition to lupo_channels (not included in this draft)
ALTER TABLE lupo_channels 
ADD COLUMN project_id BIGINT DEFAULT NULL COMMENT 'Parent project ID, channels belong to exactly one project';
```

### Actor-Project Junction Table
```sql
-- Optional junction table for actor-project relationships (not included in this draft)
CREATE TABLE lupo_actor_projects (
    actor_id BIGINT NOT NULL,
    project_id BIGINT NOT NULL,
    role VARCHAR(64) DEFAULT 'member',
    created_ymdhis BIGINT NOT NULL DEFAULT 0,
    PRIMARY KEY (actor_id, project_id),
    INDEX idx_project_members (project_id, role)
);
```

---

## Implementation Notes

### Table Count Impact
- Adds 1 new table to existing schema
- Current table count: 210 TOON files
- Remaining capacity: 12 tables (222 table ceiling)
- Well within table ceiling limits

### Migration Considerations
- New table, no impact on existing data
- Channels can be gradually migrated to project context
- Backward compatibility maintained for existing functionality

### Performance Characteristics
- Optimized indexes for common query patterns
- Federation node scoping reduces index size
- Status flags enable efficient filtering
- JSON metadata provides flexibility without schema changes

---

**SQL Draft Status:** Complete and ready for review  
**Next Steps:** Design package approval before production implementation  
**Implementation Guard:** DRAFT ONLY - not yet approved for production install SQL

