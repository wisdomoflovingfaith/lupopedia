---
lupopedia.headers:
  header_format_version: "4.1.0"
  file_path_from_root: "docs/doctrine/PROJECT_REGISTRY_DOCTRINE.md"
  web_path: "http://www.lupopedia.com/lupopedia/docs/doctrine/PROJECT_REGISTRY_DOCTRINE.md"
  status: "active"
  when_updated: "20260403113047"
  trust_tier: null
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: doctrine
  artifact_kind: reference
  channel_key: null
  federation_node_id: 0
  thread_id: "doctrine-header-repair"
  content_id: null
  pk_id: null
  pk_slug: ""
  parent_pk_id: ""
  lupopedia.schema: doctrine
  title: ""
  summary: ""
---
# file: PROJECT_REGISTRY_DOCTRINE — delegation: cursor:root

# file: Project Registry Doctrine — session: L-LUPO-PROJECT-REGISTRY-DESIGN — delegation: cursor:root (faucet: cursor) — web_path: http://www.lupopedia.com/doctrine/PROJECT_REGISTRY_DOCTRINE

# Project Registry Doctrine

**Version:** 4.0.76  
**Author:** Cursor (actor_id: 102)  
**Scope:** Canonical doctrine for Project identity, allocation, lifecycle, and governance  
**Status:** Design-complete, awaiting implementation

---

## A. Purpose

Projects require deterministic identity and registry-based allocation to maintain Lupopedia's semantic consistency across federation nodes. This doctrine establishes:

- **Deterministic Identity:** Projects have stable, registry-assigned identifiers that never change
- **Federation Node Scope:** Projects belong to exactly one federation node, enabling clear boundaries
- **Channel Containment:** Channels belong to exactly one project, creating clean semantic hierarchy
- **Actor Flexibility:** Actors may participate across multiple projects without identity conflicts
- **Lineage Safety:** Project identity survives renames, reorganizations, and archival

**Rationale:** Without deterministic project identity, cross-node collaboration, channel organization, and project-scoped collections would become inconsistent. The registry model ensures Projects are first-class semantic entities with the same identity guarantees as Actors and Channels.

---

## B. Canonical Identity

### Core Identity Fields

```yaml
project_id: BIGINT canonical numeric identifier (application-assigned)
project_key: VARCHAR(64) stable machine-facing logical identifier  
project_slug: VARCHAR(255) human-readable URL-friendly identifier
project_name: VARCHAR(255) display name (may change)
federation_node_id: BIGINT owning federation node
```

### Identity Principles

**Canonical Identity is Registry-Assigned:**
- `project_id` is the authoritative identifier, allocated from registry
- `project_id` never changes once assigned
- `project_id` is application-managed, not database-generated
- Registry allocation follows reserved-ID doctrine patterns

**Slug vs Key vs Name:**
- `project_key`: Stable machine-facing identifier for system integration
- `project_slug`: Human-readable URL identifier, may change with renames
- `project_name`: Display name, may change frequently
- `project_id`: Immutable canonical identifier

**Stability Guarantees:**
- Project identity survives renames, reorganizations, and archival
- Historical references remain valid through `project_id`
- Slug changes do not break existing integrations that use `project_id`

---

## C. Project Scope Rules

### Federation Node Scope
```
Federation Node (1)
    ↓
Project (N per node)
    ↓
Channel (N per project)
    ↓
Thread/Dialog (N per channel)
```

### Scope Constraints

**One Project per Federation Node:**
- A project belongs to exactly one federation node
- Cross-node collaboration uses federation, not multi-node projects
- `federation_node_id` is immutable after project creation

**One Channel per Project:**
- A channel belongs to exactly one project
- Channel identity includes project context implicitly
- Channels cannot move between projects without recreation

**Multi-Project Actor Participation:**
- Actors may work across multiple projects
- Actor identity is independent of project identity
- Actor-project relationships are tracked separately from core identities

**Project-Scoped Collections:**
- Collections may be scoped to projects for organization
- Collection identity remains independent but may reference project context
- Project scoping is optional, collections can exist without project context

---

## D. Project Lifecycle

### Core States

```yaml
active:     # Normal operation, channels can be created/managed
archived:   # Read-only, no new channels, existing channels remain active
frozen:     # Fully suspended, all channels suspended, no modifications
deleted:    # Soft-deleted, identity preserved but hidden from normal views
```

### State Transitions

**Active → Archived:**
- No new channels may be created
- Existing channels remain fully operational
- Project metadata becomes read-only
- Reversible by project administrators

**Active → Frozen:**
- All project channels become suspended
- No modifications allowed to project or channels
- Emergency suspension state
- Reversible only by federation administrators

**Any State → Deleted:**
- Soft-delete only, `is_deleted = 1`
- Identity preserved for historical reference
- Hidden from normal project listings
- Not reversible without administrative intervention

**Archived/Frozen → Active:**
- Requires project administrator privileges
- Restores full operational capabilities
- Audit trail maintained for state changes

---

## E. Governance

### Creation Permissions
- **Federation Administrators:** May create projects in any federation node
- **Project Administrators:** May create projects only within assigned federation nodes
- **System Processes:** May create projects via automated workflows with proper authorization

### Management Permissions

**Project Administration:**
- Archive/reactivate projects within scope
- Assign default channels and project metadata
- Manage project membership and access controls
- Freeze projects (emergency suspension)

**Federation Oversight:**
- Cross-node project coordination
- Federation-level policy enforcement
- Emergency interventions and frozen state management
- Cross-node federation collaboration setup

### Audit Requirements
- All state transitions must be logged with actor_id and timestamp
- Project creation requires justification and approval tracking
- Renames and metadata changes preserve audit trail
- Administrative actions require elevated privilege verification

---

## F. Registry Allocation Doctrine

### Allocation Pattern

**Registry-First, Application-Assigned:**
- Project IDs allocated from registry before database insertion
- Registry tracks allocated IDs to prevent conflicts
- Application manages allocation, not database AUTO_INCREMENT
- Aligns with existing actor/channel reserved-ID doctrine

### Reserved ID Strategy

```yaml
project_id allocation ranges:
  1-999:     System and critical infrastructure projects
  1000-9999: Standard user projects
  10000+:    Large-scale or special-purpose projects
```

### Allocation Process

1. **Registry Consultation:** Check registry for next available ID in appropriate range
2. **ID Reservation:** Reserve project_id in registry before database operations
3. **Database Insertion:** Insert project with reserved project_id
4. **Registry Confirmation:** Confirm successful insertion, finalize registry entry
5. **Failure Handling:** Rollback registry reservation if database insertion fails

### Anti-Collision Measures
- Registry maintains allocated and reserved ID sets
- Concurrent allocation attempts detect and avoid conflicts
- Federation node scope enforced during allocation
- Historical IDs never reused to prevent confusion

---

## G. Persistence Doctrine

### Canonical Storage

**Primary Table: `lupo_projects`**
- Canonical source of truth for project data
- Contains all core project attributes and metadata
- Follows database doctrine (no FKs, BIGINT timestamps, soft delete)
- Indexed for performance on common query patterns

**Registry Representation:**
- `database/lupopedia/projects/project_id/registry.json` mirrors core data
- Provides machine-readable project registry for system processes
- Used for identity resolution and allocation coordination
- Maintains consistency with canonical table data

### Optional Filesystem Mirrors

**Project Documentation:**
- `projects/<project_id>/` directories for project-specific resources
- Project configuration files and documentation
- Optional project-specific collections and metadata
- Mirrors database structure for filesystem-based operations

### Synchronization Rules
- Database table is authoritative source of truth
- Registry and filesystem mirrors are derived from database
- Updates propagate from database to mirrors, not reverse
- Inconsistencies resolved by database authority

---

## H. Historical / Rename Handling

### Identity Preservation

**Immutable Core Identity:**
- `project_id` never changes under any circumstances
- `federation_node_id` immutable after creation
- Historical references remain valid through stable `project_id`

**Flexible Human Identity:**
- `project_name` may change without affecting identity
- `project_slug` may change with renames, redirects supported
- `project_key` remains stable for system integrations

### Rename Process

1. **Validate New Identity:** Check for conflicts in target federation node
2. **Update Human Fields:** Modify `project_name` and `project_slug` as needed
3. **Maintain Redirects:** Preserve old slug mappings for compatibility
4. **Audit Logging:** Record rename with actor_id, timestamp, and reason
5. **Cache Invalidation:** Clear any cached references to old identifiers

### Historical Tracking
- Original creation identity preserved in audit fields
- Rename history tracked in project metadata or separate audit table
- External references encouraged to use stable `project_id`
- Slug changes maintain backward compatibility where possible

---

## Design Decisions Summary

### A. Registry Requirement
**Decision:** Projects use both `lupo_projects` table and registry.json mirror
**Justification:** Aligns with existing actor/channel patterns, provides machine-readable registry, maintains database authority

### B. Canonical Allocator
**Decision:** Registry-first, application-assigned ID allocation
**Justification:** Consistent with reserved-ID doctrine, prevents AUTO_INCREMENT ambiguity, enables deterministic identity

### C. Key vs Slug Both Needed
**Decision:** Both `project_key` and `project_slug` retained
**Justification:** `project_key` for stable machine identity, `project_slug` for human-readable URLs, distinct purposes

### D. Default Channel Inclusion
**Decision:** `default_channel_id` included in `lupo_projects`
**Justification:** Provides project entry point, simplifies channel creation, maintains project-channel relationship

### E. Archival vs Frozen vs Deleted
**Decision:** Three distinct states with clear transition rules
**Justification:** `archived` for read-only preservation, `frozen` for emergency suspension, `deleted` for soft removal

---

**Doctrine Status:** Complete and ready for schema design implementation  
**Next Steps:** Create schema design document and workflow documentation  
**Implementation Guard:** No production schema changes until design package approved
