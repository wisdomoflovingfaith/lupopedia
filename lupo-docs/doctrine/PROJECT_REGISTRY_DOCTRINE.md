---
lupopedia.init:
  file_identity: "PROJECT_REGISTRY_DOCTRINE.md"
  artifact_type: "doctrine"
  artifact_kind: "registry_design"
  namespace: "projects"
  domain: "doctrine"
  system_version: "4.0.76"
  design_actor: "cursor"
  design_faucet: "cursor"
  orchestrator_actor: "wolfie"

lupopedia.metadata:
  comment: "Snapshot of metadata for this file or entity at artifact creation."
  title:
    - { schema_ref: "lupo_metadata", entity_type: "file", meta_type: "property", property_value: "Project Registry Doctrine - Canonical Design for Project Identity and Allocation", channel_id: 42, class_name: "lupo_metadata", created_ymdhis: 20260315230000, updated_ymdhis: 20260315230000 }
  description:
    - { schema_ref: "lupo_metadata", entity_type: "file", meta_type: "property", property_value: "Canonical doctrine for Project Registry in Lupopedia. Defines deterministic identity, allocation patterns, lifecycle, governance, and persistence model for Projects as first-class semantic entities.", channel_id: 42, class_name: "lupo_metadata", created_ymdhis: 20260315230000, updated_ymdhis: 20260315230000 }
  keywords:
    - { schema_ref: "lupo_metadata", entity_type: "file", meta_type: "property", property_value: "project_registry, doctrine, identity, allocation, deterministic, lupopedia, 4.0.76", channel_id: 42, class_name: "lupo_metadata", created_ymdhis: 20260315230000, updated_ymdhis: 20260315230000 }
  author:
    - { schema_ref: "lupo_metadata", entity_type: "file", meta_type: "property", property_value: "cursor", channel_id: 42, class_name: "lupo_metadata", created_ymdhis: 20260315230000, updated_ymdhis: 20260315230000 }
  orchestrator:
    - { schema_ref: "lupo_metadata", entity_type: "file", meta_type: "property", property_value: "wolfie", channel_id: 42, class_name: "lupo_metadata", created_ymdhis: 20260315230000, updated_ymdhis: 20260315230000 }

lupopedia.comments:
  - { comment_id: 1, channel_id: 42, actor_id: 102, actor_name: "cursor", faucet_id: 102, faucet_name: "cursor", comment_text: "Project Registry Doctrine created - establishes deterministic identity and allocation patterns for Projects as first-class semantic entities.", comment_type: "doctrine", created_ymdhis: 20260315231000, updated_ymdhis: 20260315231000 }
  - { comment_id: 2, channel_id: 42, actor_id: 102, actor_name: "cursor", faucet_id: 102, faucet_name: "cursor", comment_text: "Design aligns with existing actor/channel registry patterns and reserved-ID doctrine.", comment_type: "alignment", created_ymdhis: 20260315231500, updated_ymdhis: 20260315231500 }
  - { comment_id: 3, channel_id: 42, actor_id: 102, actor_name: "cursor", faucet_id: 102, faucet_name: "cursor", comment_text: "Registry-first identity with application-assigned IDs ensures deterministic behavior across federation nodes.", comment_type: "determinism", created_ymdhis: 20260315232000, updated_ymdhis: 20260315232000 }

lupopedia.headers:
  lupopedia.version: "4.0.76"
  lupopedia.schema: "doctrine"
  file_path_from_root: "lupo-docs/doctrine/PROJECT_REGISTRY_DOCTRINE.md"
  web_path: "http://www.lupopedia.com/doctrine/PROJECT_REGISTRY_DOCTRINE"
  last_modified_utc: "20260315"
  system_version: "4.0.76"
  channel_id: 42
  actor_id: 102
  actor_name: "cursor"
  faucet_name: "cursor"
  delegation_chain: "cursor:root"
  artifact_type: "doctrine"
  artifact_kind: "registry_design"
  purpose: "Canonical doctrine for Project Registry - deterministic identity, allocation, lifecycle, and governance"
  mood_rgb: "8B4513"
  traits: ["canonical", "registry", "deterministic", "identity", "4.0.76"]
  tags: ["project_registry", "doctrine", "identity", "allocation", "deterministic", "lupopedia"]

lupopedia.session:
  session_id: "L-LUPO-PROJECT-REGISTRY-DESIGN"
  session_name: "L-LUPO-PROJECT-REGISTRY-DESIGN"
  actor_id: 102
  actor_name: "cursor"
  faucet_name: "cursor"
  channel_id: 42
  channel_name: "Lupopedia Development (general)"
  federation_node_id: 1
  paired_actor_id: 1000

lupopedia.edges:
  comment: "Snapshot of relationships for Project Registry Doctrine."
  outbound_edges:
    - { to: "lupo-docs/projects/PROJECTS.md", type: "informs", weight: 1.0 }
    - { to: "lupo-docs/database/lupopedia/tables/PROJECT_REGISTRY_SCHEMA_DESIGN.md", type: "informs", weight: 0.95 }
    - { to: "lupo-docs/doctrine/PROJECT_REGISTRY_WORKFLOW.md", type: "complements", weight: 0.9 }
    - { to: "lupo-docs/doctrine/DATABASE_DOCTRINE.md", type: "extends", weight: 0.9 }
    - { to: "lupo-docs/doctrine/AGENT_REGISTRY.md", type: "aligns_with", weight: 0.85 }
    - { to: "lupo-docs/ACTOR_REGISTRATION_CHECKLIST.md", type: "informs", weight: 0.8 }
  semantic_tags: ["project_registry_doctrine", "deterministic_identity", "allocation_patterns"]

lupopedia.footer:
  version: "4.0.76"
  last_verified: "20260315"
  last_verified_by: "cursor"
  orchestrator: "cursor"
  next_action:
    - "Implement schema design based on this doctrine"
    - "Create workflow documentation for project lifecycle"
    - "Update existing documentation to reference project registry"
---
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
- `lupo-database/lupopedia/projects/project_id/registry.json` mirrors core data
- Provides machine-readable project registry for system processes
- Used for identity resolution and allocation coordination
- Maintains consistency with canonical table data

### Optional Filesystem Mirrors

**Project Documentation:**
- `lupo-projects/<project_id>/` directories for project-specific resources
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
