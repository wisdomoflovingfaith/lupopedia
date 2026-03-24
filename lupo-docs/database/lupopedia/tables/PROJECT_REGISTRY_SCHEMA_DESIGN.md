---
lupopedia.init:
  file_identity: PROJECT_REGISTRY_SCHEMA_DESIGN.md
  artifact_type: schema_design
  artifact_kind: database_documentation
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
    property_value: Project Registry Schema Design - Database Architecture for lupo_projects
    channel_id: 42
    class_name: lupo_metadata
    created_ymdhis: 20260315233000
    updated_ymdhis: 20260315233000
  description:
  - schema_ref: lupo_metadata
    entity_type: file
    meta_type: property
    property_value: Database schema design documentation for Project Registry. Defines
      table structure, column purposes, uniqueness strategy, indexes, and relationships
      with existing tables.
    channel_id: 42
    class_name: lupo_metadata
    created_ymdhis: 20260315233000
    updated_ymdhis: 20260315233000
  keywords:
  - schema_ref: lupo_metadata
    entity_type: file
    meta_type: property
    property_value: project_registry, schema_design, database, lupo_projects, indexes,
      uniqueness, lupopedia
    channel_id: 42
    class_name: lupo_metadata
    created_ymdhis: 20260315233000
    updated_ymdhis: 20260315233000
  author:
  - schema_ref: lupo_metadata
    entity_type: file
    meta_type: property
    property_value: cursor
    channel_id: 42
    class_name: lupo_metadata
    created_ymdhis: 20260315233000
    updated_ymdhis: 20260315233000
  orchestrator:
  - schema_ref: lupo_metadata
    entity_type: file
    meta_type: property
    property_value: wolfie
    channel_id: 42
    class_name: lupo_metadata
    created_ymdhis: 20260315233000
    updated_ymdhis: 20260315233000
lupopedia.comments:
- comment_id: 1
  channel_id: 42
  actor_id: 102
  actor_name: cursor
  faucet_id: 102
  faucet_name: cursor
  comment_text: Project Registry Schema Design created - defines database architecture
    for lupo_projects table with deterministic identity patterns.
  comment_type: schema_design
  created_ymdhis: 20260315233500
  updated_ymdhis: 20260315233500
- comment_id: 2
  channel_id: 42
  actor_id: 102
  actor_name: cursor
  faucet_id: 102
  faucet_name: cursor
  comment_text: Design aligns with existing database doctrine and maintains compatibility
    with actor/channel patterns.
  comment_type: alignment
  created_ymdhis: 20260315234000
  updated_ymdhis: 20260315234000
- comment_id: 3
  channel_id: 42
  actor_id: 102
  actor_name: cursor
  faucet_id: 102
  faucet_name: cursor
  comment_text: Index strategy optimized for common query patterns while maintaining
    database-agnostic compatibility.
  comment_type: optimization
  created_ymdhis: 20260315234500
  updated_ymdhis: 20260315234500
lupopedia.headers:
  lupopedia.schema: schema_design
  file_path_from_root: lupo-docs/database/lupopedia/tables/PROJECT_REGISTRY_SCHEMA_DESIGN.md
  web_path: http://www.lupopedia.com/database/PROJECT_REGISTRY_SCHEMA_DESIGN
  last_modified_utc: '20260315'
  channel_id: 42
  actor_id: 102
  actor_name: cursor
  faucet_name: cursor
  delegation_chain: cursor:root
  artifact_type: schema_design
  artifact_kind: database_documentation
  purpose: Database schema design documentation for Project Registry
  mood_rgb: 2F4F4F
  traits:
  - schema_design
  - database_architecture
  - deterministic
  - 4.0.76
  tags:
  - project_registry
  - schema_design
  - database
  - lupo_projects
  - indexes
  when_updated: '20260324174654'
lupopedia.session:
  session_id: L-LUPO-PROJECT-SCHEMA-DESIGN
  session_name: L-LUPO-PROJECT-SCHEMA-DESIGN
  actor_id: 102
  actor_name: cursor
  faucet_name: cursor
  channel_id: 42
  channel_name: Lupopedia Development (general)
  federation_node_id: 1
  paired_actor_id: 1000
lupopedia.edges:
  comment: Snapshot of relationships for Project Registry Schema Design.
  outbound_edges:
  - to: lupo-docs/doctrine/PROJECT_REGISTRY_DOCTRINE.md
    type: implements
    weight: 1.0
  - to: lupo-docs/database/lupopedia/tables/sql_drafts/create_lupo_projects.sql.md
    type: specifies
    weight: 0.95
  - to: lupo-docs/database/lupopedia/tables/active/lupo_channels.md
    type: relates_to
    weight: 0.9
  - to: lupo-docs/database/lupopedia/tables/active/lupo_actors.md
    type: relates_to
    weight: 0.85
  - to: lupo-docs/doctrine/DATABASE_DOCTRINE.md
    type: follows
    weight: 0.9
  - to: lupo-docs/doctrine/PROJECT_REGISTRY_WORKFLOW.md
    type: informs
    weight: 0.8
  semantic_tags:
  - project_registry_schema
  - database_design
  - table_architecture
lupopedia.footer:
  last_verified: '20260315000000'
  last_verified_by: cursor
  orchestrator: cursor
  next_action:
  - Create draft SQL based on this schema design
  - Validate compatibility with existing table patterns
  - Review index strategy for performance optimization
  last_verified_by_actor_id: 102
---
# file: Project Registry Schema Design — session: L-LUPO-PROJECT-SCHEMA-DESIGN — delegation: cursor:root (faucet: cursor) — web_path: http://www.lupopedia.com/database/PROJECT_REGISTRY_SCHEMA_DESIGN

# Project Registry Schema Design

**Version:** 4.0.76  
**Author:** Cursor (actor_id: 102)  
**Scope:** Database schema design for Project Registry  
**Status:** Design-complete, awaiting implementation

---

## A. Proposed Core Tables

### Primary Table: `lupo_projects`

**Purpose:** Canonical storage for all project data, following Lupopedia database doctrine.

**Relationship Context:**
- **Parent:** Federation Node (via `federation_node_id`)
- **Children:** Channels (via foreign reference in `lupo_channels`)
- **Associations:** Actors (many-to-many via project membership tables)
- **Collections:** Optional project-scoped collections

### Registry Support Structure

**Decision:** No separate registry table required. The existing `lupo_projects` table serves as both canonical storage and registry authority, with filesystem mirrors for machine-readable registry access.

**Rationale:** 
- Aligns with existing actor/channel patterns where core table is authoritative
- Reduces complexity and potential synchronization issues
- Filesystem registry mirrors provide machine-readable access without additional database complexity

---

## B. Table Purpose for `lupo_projects`

### Core Identity Columns

```sql
project_id BIGINT NOT NULL COMMENT 'Canonical project identifier, application-assigned, immutable'
project_key VARCHAR(64) NOT NULL COMMENT 'Stable machine-facing logical identifier'
project_slug VARCHAR(255) NOT NULL COMMENT 'Human-readable URL-friendly identifier, may change'
project_name VARCHAR(255) NOT NULL COMMENT 'Display name, may change frequently'
federation_node_id BIGINT NOT NULL COMMENT 'Owning federation node, immutable after creation'
```

**Column Rationale:**
- `project_id`: Primary key, follows reserved-ID doctrine, never changes
- `project_key`: Stable system integration identifier, survives renames
- `project_slug`: Human-readable URL identifier, supports redirects on changes
- `project_name`: User-facing display name, flexible for frequent changes
- `federation_node_id`: Enforces single-node scope, immutable after creation

### Organizational Columns

```sql
default_channel_id BIGINT DEFAULT NULL COMMENT 'Default channel for project entry point'
orchestrator_id BIGINT NOT NULL COMMENT 'Primary orchestrator/owner of project'
project_type VARCHAR(64) DEFAULT 'standard' COMMENT 'Project type classification'
description TEXT DEFAULT NULL COMMENT 'Project description and purpose'
```

**Column Rationale:**
- `default_channel_id`: Provides project entry point, simplifies navigation
- `orchestrator_id`: Primary owner/administrator, enables governance
- `project_type`: Classification for different project categories
- `description`: Human-readable project documentation

### Status and Lifecycle Columns

```sql
status VARCHAR(32) NOT NULL DEFAULT 'active' COMMENT 'Project state: active, archived, frozen, deleted'
is_active TINYINT NOT NULL DEFAULT 1 COMMENT 'Active status flag for quick filtering'
is_deleted TINYINT NOT NULL DEFAULT 0 COMMENT 'Soft delete flag'
is_archived TINYINT NOT NULL DEFAULT 0 COMMENT 'Archive status flag'
is_frozen TINYINT NOT NULL DEFAULT 0 COMMENT 'Freeze status flag'
```

**Column Rationale:**
- `status`: Human-readable state for UI and API consumption
- `is_*` flags: Optimized for database queries and business logic
- Multiple status representations support different use cases

### Metadata and Audit Columns

```sql
metadata_json JSON DEFAULT NULL COMMENT 'Structured project metadata and properties'
created_ymdhis BIGINT NOT NULL DEFAULT 0 COMMENT 'Creation timestamp YYYYMMDDHHIISS UTC'
updated_ymdhis BIGINT NOT NULL DEFAULT 0 COMMENT 'Last update timestamp YYYYMMDDHHIISS UTC'
deleted_ymdhis BIGINT DEFAULT 0 COMMENT 'Soft delete timestamp YYYYMMDDHHIISS UTC'
created_by_actor_id BIGINT DEFAULT NULL COMMENT 'Actor who created project'
updated_by_actor_id BIGINT DEFAULT NULL COMMENT 'Actor who last updated project'
```

**Column Rationale:**
- `metadata_json`: Flexible structured data storage for project properties
- Timestamps follow BIGINT UTC doctrine for consistency
- Audit fields track creation and modification history

---

## C. Uniqueness Strategy

### Primary Uniqueness

```sql
PRIMARY KEY (project_id)
```

**Rationale:** 
- `project_id` is the canonical identifier
- Application-assigned ensures deterministic behavior
- Follows existing patterns from `lupo_actors` and `lupo_channels`

### Logical Uniqueness Constraints

```sql
UNIQUE KEY uk_project_key_node (project_key, federation_node_id)
UNIQUE KEY uk_project_slug_node (project_slug, federation_node_id)
```

**Rationale:**
- **Project Key + Node:** Ensures project keys are unique within federation node
- **Project Slug + Node:** Ensures URL-friendly slugs are unique within federation node
- Federation node scope prevents cross-node conflicts
- Allows same project key/slug in different federation nodes

### Design Tradeoffs

**Why both key and slug uniqueness?**
- `project_key` for system integration stability
- `project_slug` for human-readable URL uniqueness
- Both scoped to federation node for distributed deployment
- Supports different use cases without compromising uniqueness

**Why not global uniqueness?**
- Federation nodes may operate independently
- Cross-node coordination handled at federation level
- Reduces coordination complexity for large deployments
- Allows federation node autonomy while maintaining uniqueness within scope

---

## D. Index Strategy

### Performance-Critical Indexes

```sql
-- Primary lookup by project_id
PRIMARY KEY (project_id)

-- Federation node project listings
INDEX idx_federation_node (federation_node_id, status, is_deleted)

-- Project resolution by key/slug
INDEX idx_project_key (project_key, federation_node_id)
INDEX idx_project_slug (project_slug, federation_node_id)

-- Orchestrator project management
INDEX idx_orchestrator (orchestrator_id, status, is_deleted)

-- Default channel resolution
INDEX idx_default_channel (default_channel_id) WHERE default_channel_id IS NOT NULL

-- Status-based queries
INDEX idx_status (status, is_active, is_deleted)

-- Audit and timestamp queries
INDEX idx_created (created_ymdhis)
INDEX idx_updated (updated_ymdhis)
```

### Index Rationale

**Federation Node Queries:**
- Common pattern: list all projects in a federation node
- Composite index supports status filtering and soft-delete exclusion
- Optimizes project browsing and management interfaces

**Project Resolution:**
- Frequent lookups by project_key or project_slug
- Federation node included for uniqueness enforcement
- Supports URL routing and API endpoint resolution

**Orchestrator Management:**
- Project owners need to manage their projects
- Status filtering for active vs archived projects
- Soft-delete exclusion for normal operations

**Default Channel Navigation:**
- Projects often need to resolve to default channel
- Conditional index excludes NULL values for efficiency
- Supports project entry point functionality

---

## E. Soft Delete Strategy

### Multi-State Soft Delete Model

```sql
-- Status field for human-readable state
status ENUM('active', 'archived', 'frozen', 'deleted') NOT NULL DEFAULT 'active'

-- Boolean flags for optimized queries
is_active TINYINT NOT NULL DEFAULT 1    -- False for archived/frozen/deleted
is_deleted TINYINT NOT NULL DEFAULT 0    -- True only for deleted state
is_archived TINYINT NOT NULL DEFAULT 0   -- True only for archived state
is_frozen TINYINT NOT NULL DEFAULT 0     -- True only for frozen state
```

### State Behavior

**Active (`status = 'active'`):**
- `is_active = 1`, `is_deleted = 0`, `is_archived = 0`, `is_frozen = 0`
- Full operational capabilities
- Channels can be created and managed
- Project metadata can be modified

**Archived (`status = 'archived'`):**
- `is_active = 0`, `is_deleted = 0`, `is_archived = 1`, `is_frozen = 0`
- Read-only project metadata
- Existing channels remain operational
- No new channels can be created

**Frozen (`status = 'frozen'`):**
- `is_active = 0`, `is_deleted = 0`, `is_archived = 0`, `is_frozen = 1`
- Fully suspended operations
- All project channels suspended
- Emergency suspension state

**Deleted (`status = 'deleted'`):**
- `is_active = 0`, `is_deleted = 1`, `is_archived = 0`, `is_frozen = 0`
- Soft-deleted, hidden from normal views
- Identity preserved for historical reference
- Not reversible without administrative intervention

### Query Patterns

**Normal Operations:**
```sql
-- Active projects only
WHERE is_active = 1 AND is_deleted = 0

-- All non-deleted projects  
WHERE is_deleted = 0

-- Specific state queries
WHERE status = 'archived'
```

**Administrative Operations:**
```sql
-- Include deleted for audit
WHERE is_deleted = 1

-- All projects regardless of state
-- (no WHERE clause on status flags)
```

---

## F. Why No Foreign Keys

### Doctrine Compliance

**Lupopedia Database Doctrine:**
- No foreign keys to maintain database-agnostic compatibility
- Referential integrity handled in application code
- Avoids database-specific constraint behaviors
- Enables flexible deployment across different database systems

### Application-Managed Relationships

**Channel-Project Relationship:**
- `lupo_channels.federation_node_id` references project's federation node
- `lupo_channels.project_id` set by application logic
- Application validates project existence and permissions
- Consistency maintained through business logic, not database constraints

**Actor-Project Relationships:**
- Many-to-many relationships via junction tables
- Application manages membership validation
- Project permissions enforced at application level
- Flexible relationship modeling without database constraints

### Benefits of No Foreign Keys

**Database Agnosticism:**
- Works across MySQL, PostgreSQL, and other supported databases
- No database-specific constraint syntax or behavior
- Simplifies migration and deployment processes

**Performance Optimization:**
- No foreign key constraint overhead during inserts/updates
- Application can batch operations efficiently
- Flexibility in relationship management strategies

**Deployment Flexibility:**
- Easier to deploy across different environments
- No constraint-related deployment blockers
- Simplified backup and restore processes

---

## G. Integration with Existing Tables

### Channel Table Extensions

**Required `lupo_channels` additions:**
```sql
project_id BIGINT DEFAULT NULL COMMENT 'Parent project ID, channels belong to exactly one project'
```

**Integration Impact:**
- Channels gain project context while maintaining existing identity
- Project-scoped channel queries become efficient
- Backward compatibility maintained for channels without projects

### Actor-Project Relationships

**Junction Table Design:**
```sql
CREATE TABLE lupo_actor_projects (
    actor_id BIGINT NOT NULL,
    project_id BIGINT NOT NULL,
    role VARCHAR(64) DEFAULT 'member',
    created_ymdhis BIGINT NOT NULL DEFAULT 0,
    PRIMARY KEY (actor_id, project_id),
    INDEX idx_project_members (project_id, role)
);
```

**Purpose:**
- Track actor participation across multiple projects
- Enable project-specific permissions and roles
- Support flexible actor-project relationship modeling

### Federation Node Integration

**Existing Federation Node Support:**
- Projects reference existing `federation_node_id` 
- Federation node scope enforced at application level
- Cross-node federation remains unchanged

---

## H. Migration and Compatibility

### Backward Compatibility

**Existing Channels:**
- Channels without project_id remain functional
- Gradual migration to project-based organization
- No breaking changes to existing channel operations

**Actor Identity:**
- Actor identity remains independent of project identity
- Existing actors can participate in new projects
- No changes to actor registration or management

### Migration Strategy

**Phase 1: Schema Addition**
- Add `lupo_projects` table with proper indexes
- Add `project_id` to `lupo_channels` (nullable initially)
- Create actor-project junction table

**Phase 2: Data Migration**
- Migrate existing channels to appropriate projects
- Create default projects for orphaned channels
- Populate actor-project relationships

**Phase 3: Application Integration**
- Update application logic to use project context
- Implement project-based channel organization
- Enable project management interfaces

---

**Schema Design Status:** Complete and ready for SQL implementation  
**Next Steps:** Create draft SQL artifact based on this design  
**Implementation Guard:** No production schema changes until design package approved
