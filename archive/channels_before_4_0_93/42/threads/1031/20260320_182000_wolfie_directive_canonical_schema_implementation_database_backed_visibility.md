---
lupopedia.headers:
  version_when_written: "4.0.84"
  lupopedia.schema: "thread"
  file_path_from_root: "channels/42/threads/1031/20260320_182000_wolfie_directive_canonical_schema_implementation_database_backed_visibility.md"
  web_path: "http://www.lupopedia.com/channels/42/threads/1031/20260320_182000_wolfie_directive_canonical_schema_implementation_database_backed_visibility.md"
  questions_toon: null
  channel_id: 42
  thread_id: 1031
  task_id: "task_schema_implementation_database_backed_visibility_001"
  actor_id: 1
  actor_name: "wolfie"
  delegation_chain: "wolfie:root"
  artifact_type: "thread"
  artifact_kind: "directive"
  purpose: "WOLFIE Directive — Canonical Schema Implementation for Database-Backed Channel, Thread, and Task Visibility"
  tags: ["wolfie", "directive", "schema_implementation", "database_backed_visibility", "4.0.84"]
lupopedia.edges:
  outbound_edges:
    - { to: "channels/42/THREAD_INDEX.md", type: "creates", weight: 1.0, reason: "New thread for schema implementation must be indexed" }
    - { to: "channels/42/threads/1030/20260320_174500_wolfie_corrective_directive_operationalizing_thread_1030_database_visibility_reconciliation.md", type: "extends", weight: 1.0, reason: "Moves beyond documentation reconciliation to actual implementation" }
    - { to: "channels/42/threads/1030/20260320_175000_thoth_table_reconciliation_report_visibility_critical_db_documentation_authority_check_phase_2_gate.md", type: "responds_to", weight: 1.0, reason: "Implements the database structures that documentation-only work could not provide" }
    - { to: "database/lupopedia/mysql/install/install_new_lupopedia.sql", type: "modifies", weight: 1.0, reason: "Canonical install SQL will be extended with visibility structures" }
    - { to: "database/lupopedia/toon/lupo_channels.toon", type: "modifies", weight: 1.0, reason: "TOON schema will be updated to match install SQL changes" }
    - { to: "database/lupopedia/toon/lupo_dialog_threads.toon", type: "modifies", weight: 1.0, reason: "TOON schema will be updated to match install SQL changes" }
    - { to: "database/lupopedia/toon/lupo_tasks.toon", type: "modifies", weight: 1.0, reason: "TOON schema will be updated to match install SQL changes" }
    - { to: "database/lupopedia/toon/lupo_edges.toon", type: "modifies", weight: 1.0, reason: "TOON schema will be updated to match install SQL changes" }
    - { to: "channels/42/threads/1028", type: "references", weight: 0.8, reason: "Coordination template enforcement provides operational framework" }
    - { to: "channels/42/threads/1029", type: "references", weight: 0.8, reason: "Thread hierarchy model provides structural patterns" }
lupopedia.footer:
  last_verified: "20260320"
  last_verified_by: "wolfie"
  orchestrator: "wolfie"
  next_action:
    - "HEPHAESTUS: Execute schema implementation per Section 6 implementation scope"
    - "THOTH: Update TOON files to match install SQL changes"
    - "ATHENA: Review implementation design for web UI requirements"
    - "WOLFIE: Validate implementation and authorize next phase"
---
# file: WOLFIE Directive — Canonical Schema Implementation for Database-Backed Channel, Thread, and Task Visibility — session: L-LUPO-ROOT-CURSOR — delegation: wolfie:root — web_path: http://www.lupopedia.com/channels/42/threads/1031/20260320_182000_wolfie_directive_canonical_schema_implementation_database_backed_visibility.md

# WOLFIE Directive — Canonical Schema Implementation for Database-Backed Channel, Thread, and Task Visibility

**Status:** 🔄 BINDING DIRECTIVE  
**Effective:** 2026-03-20  
**Scope:** Real database schema implementation for visibility support  
**Phase:** 1 - Schema Design and Implementation Authorization

## Executive Summary

This directive **explicitly authorizes real canonical database schema changes** to support database-backed channel, thread, and task visibility for web UI surfaces. This thread stops the pattern of documentation-only updates and authorizes actual implementation work.

## 1. Context and Problem Statement

### 1.1 Current State
- Thread 1030 work was documentation-heavy and did not change canonical install SQL
- Database lacks needed structures for channel, thread, and task visibility
- Web UI cannot be built without proper database backing
- File-visible concepts from THREAD_INDEX/TODO/plan lack first-class DB representation

### 1.2 Required Outcome
- Real database structures to support web interface
- Persistent storage for channels, threads, tasks, owners, hierarchy, status
- Reviewable operational state for UI surfaces
- Deterministic application-layer logic only

## 2. Implementation Scope Authorization

### 2.1 Database Structures Required
**Authorized for Implementation:**

1. **Channel Visibility Support**
   - Channel status, visibility flags
   - Channel ownership and access control
   - Channel metadata for UI display

2. **Thread Visibility Support**
   - Thread status, hierarchy relationships
   - Thread ownership and assignment tracking
   - Thread metadata and operational state

3. **Task Visibility Support**
   - Task ownership, status, priority
   - Task dependencies and relationships
   - Task lifecycle tracking

4. **Review/Audit State Support**
   - Review status tracking
   - Audit trail capabilities
   - State transition history

## 3. Schema Design Requirements

### 3.1 Install SQL Changes Required

**lupo_channels table extensions:**
```sql
-- Channel visibility and status
ALTER TABLE lupo_channels ADD COLUMN visibility_status varchar(32) NOT NULL DEFAULT 'active';
ALTER TABLE lupo_channels ADD COLUMN channel_type varchar(32) NOT NULL DEFAULT 'protocol';
ALTER TABLE lupo_channels ADD COLUMN owner_actor_id bigint NOT NULL DEFAULT 1;
ALTER TABLE lupo_channels ADD COLUMN access_level varchar(32) NOT NULL DEFAULT 'public';
ALTER TABLE lupo_channels ADD COLUMN channel_metadata json DEFAULT NULL;
ALTER TABLE lupo_channels ADD COLUMN ui_preferences json DEFAULT NULL;
ALTER TABLE lupo_channels ADD COLUMN last_activity_ymdhis bigint NOT NULL DEFAULT 0;
```

**lupo_dialog_threads table extensions:**
```sql
-- Thread hierarchy and visibility
ALTER TABLE lupo_dialog_threads ADD COLUMN parent_thread_id bigint DEFAULT NULL;
ALTER TABLE lupo_dialog_threads ADD COLUMN root_thread_id bigint DEFAULT NULL;
ALTER TABLE lupo_dialog_threads ADD COLUMN thread_depth int NOT NULL DEFAULT 0;
ALTER TABLE lupo_dialog_threads ADD COLUMN visibility_status varchar(32) NOT NULL DEFAULT 'active';
ALTER TABLE lupo_dialog_threads ADD COLUMN owner_actor_id bigint NOT NULL;
ALTER TABLE lupo_dialog_threads ADD COLUMN assigned_actor_id bigint DEFAULT NULL;
ALTER TABLE lupo_dialog_threads ADD COLUMN thread_type varchar(32) NOT NULL DEFAULT 'discussion';
ALTER TABLE lupo_dialog_threads ADD COLUMN thread_priority varchar(32) NOT NULL DEFAULT 'normal';
ALTER TABLE lupo_dialog_threads ADD COLUMN thread_metadata json DEFAULT NULL;
ALTER TABLE lupo_dialog_threads ADD COLUMN review_status varchar(32) DEFAULT NULL;
ALTER TABLE lupo_dialog_threads ADD COLUMN review_actor_id bigint DEFAULT NULL;
ALTER TABLE lupo_dialog_threads ADD COLUMN review_ymdhis bigint DEFAULT NULL;
```

**lupo_tasks table extensions:**
```sql
-- Task visibility and ownership (already has some, extend as needed)
ALTER TABLE lupo_tasks ADD COLUMN visibility_status varchar(32) NOT NULL DEFAULT 'active';
ALTER TABLE lupo_tasks ADD COLUMN assigned_actor_id bigint DEFAULT NULL;
ALTER TABLE lupo_tasks ADD COLUMN reviewer_actor_id bigint DEFAULT NULL;
ALTER TABLE lupo_tasks ADD COLUMN review_status varchar(32) DEFAULT NULL;
ALTER TABLE lupo_tasks ADD COLUMN review_ymdhis bigint DEFAULT NULL;
ALTER TABLE lupo_tasks ADD COLUMN task_dependencies json DEFAULT NULL;
```

**New canonical table: lupo_visibility_state**
```sql
CREATE TABLE lupo_visibility_state (
  visibility_id bigint NOT NULL,
  entity_type varchar(50) NOT NULL,
  entity_id bigint NOT NULL,
  visibility_level varchar(32) NOT NULL DEFAULT 'public',
  access_actor_id bigint DEFAULT NULL,
  granted_ymdhis bigint NOT NULL DEFAULT 0,
  expires_ymdhis bigint DEFAULT NULL,
  granted_by_actor_id bigint NOT NULL,
  visibility_reason varchar(255) DEFAULT NULL,
  metadata_json json DEFAULT NULL,
  is_deleted tinyint NOT NULL DEFAULT '0',
  deleted_ymdhis bigint DEFAULT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL,
  PRIMARY KEY (visibility_id)
);

CREATE INDEX lupo_visibility_state_idx_entity ON lupo_visibility_state (entity_type, entity_id);
CREATE INDEX lupo_visibility_state_idx_actor ON lupo_visibility_state (access_actor_id);
CREATE INDEX lupo_visibility_state_idx_level ON lupo_visibility_state (visibility_level);
CREATE INDEX lupo_visibility_state_idx_created ON lupo_visibility_state (created_ymdhis);
```

### 3.2 TOON Changes Required

**Update TOON files to match install SQL:**
- lupo_channels.toon - Add new fields
- lupo_dialog_threads.toon - Add new fields
- lupo_tasks.toon - Add new fields
- lupo_visibility_state.toon - New TOON file

## 4. Data Distribution Strategy

### 4.1 What Belongs in Each Table

**lupo_channels:**
- Channel-level visibility and access control
- Channel metadata and UI preferences
- Channel ownership and management

**lupo_dialog_threads:**
- Thread hierarchy (parent/child relationships)
- Thread visibility and status
- Thread ownership and assignment
- Review and audit state

**lupo_tasks:**
- Task ownership and assignment
- Task status and priority
- Task dependencies
- Review and approval state

**lupo_edges:**
- Cross-entity relationships
- Thread hierarchy where parent-child is insufficient
- Task dependencies and dependencies

**lupo_metadata:**
- Generic metadata storage
- Flexible key-value pairs
- Non-critical visibility data

**lupo_visibility_state:**
- Granular visibility permissions
- Actor-specific access control
- Time-based visibility grants

## 5. File-Visible to Database Mapping

### 5.1 THREAD_INDEX Concepts Requiring DB Representation

**Thread Tree Structure:**
- parent_thread_id, root_thread_id, thread_depth in lupo_dialog_threads
- Use lupo_edges for complex relationships

**Thread Status:**
- visibility_status in lupo_dialog_threads
- review_status in lupo_dialog_threads

**Ownership and Assignment:**
- owner_actor_id, assigned_actor_id in appropriate tables
- reviewer_actor_id for review workflows

**Hierarchy Visibility:**
- parent_thread_id relationships
- visibility_level in lupo_visibility_state

### 5.2 TODO.md Concepts Requiring DB Representation

**Task Status and Ownership:**
- Extend lupo_tasks with visibility_status and assignment fields
- Track task dependencies in task_dependencies JSON field

**Task Prioritization:**
- task_priority field in lupo_tasks
- Use lupo_edges for dependency relationships

## 6. Web UI Data Requirements

### 6.1 What Must Be Persisted for Web UI

**Channel Display:**
- Channel list with visibility status
- Channel ownership and access level
- Channel metadata for UI presentation

**Thread Display:**
- Thread hierarchy tree
- Thread status and ownership
- Thread assignment and review state
- Thread metadata for UI presentation

**Task Display:**
- Active task lists with owners
- Task status and priority
- Task dependencies and relationships
- Task review and approval state

**Hierarchy Display:**
- Parent-child thread relationships
- Thread depth and tree structure
- Cross-thread relationships

**Status Display:**
- Review status indicators
- Audit trail information
- State transition history

## 7. Phase-Ordered Implementation

### 7.1 Phase 1: Schema Changes
**Authority:** Immediately authorized
**Actor:** HEPHAESTUS
**Deliverables:**
- Updated install_new_lupopedia.sql
- New lupo_visibility_state table
- Extended existing tables with visibility fields

### 7.2 Phase 2: TOON Regeneration
**Authority:** After Phase 1 completion
**Actor:** THOTH
**Deliverables:**
- Updated TOON files to match install SQL
- Regenerated JSON schemas
- Validation of TOON consistency

### 7.3 Phase 3: Implementation/Migration Logic
**Authority:** After Phase 2 completion
**Actor:** HEPHAESTUS
**Deliverables:**
- Migration scripts for existing data
- Application-layer implementation
- API endpoints for visibility management

### 7.4 Phase 4: Documentation Update
**Authority:** After Phase 3 completion
**Actor:** THOTH
**Deliverables:**
- Updated table documentation
- API documentation
- Implementation guides

### 7.5 Phase 5: UI Read Layer
**Authority:** After Phase 4 completion
**Actor:** ATHENA
**Deliverables:**
- UI component specifications
- Data access patterns
- Visibility rendering logic

## 8. Forbidden Constraints

### 8.1 Database Constraints
**STRICTLY FORBIDDEN:**
- ❌ No foreign keys (application-layer enforcement only)
- ❌ No triggers (application-layer logic only)
- ❌ No stored procedures/functions (application-layer only)
- ❌ No DATETIME/TIMESTAMP/vendor time types (bigint only)
- ❌ No AUTO_INCREMENT/SERIAL/UUID/random IDs (application-supplied IDs only)

### 8.2 Architecture Constraints
**STRICTLY FORBIDDEN:**
- ❌ No hidden sync mechanisms
- ❌ No database-as-smart-storage patterns
- ❌ No non-deterministic outputs
- ❌ No logic in database layer

### 8.3 Design Principles
**REQUIRED:**
- ✅ Database as dumb storage
- ✅ Logic in application layer only
- ✅ Deterministic outputs only
- ✅ Explicit ID management
- ✅ Application-layer enforcement of all constraints

## 9. Implementation Actor Assignment

### 9.1 Primary Implementation Actor
**HEPHAESTUS** is assigned as primary implementation actor for:
- Schema design and implementation
- Install SQL modifications
- Migration logic development
- Application-layer implementation

### 9.2 Supporting Actors
**THOTH** - TOON updates and documentation
**ATHENA** - UI requirements and design validation
**WOLFIE** - Overall coordination and validation

## 10. Success Criteria

### 10.1 Technical Success
- All schema changes implemented in install SQL
- TOON files updated and consistent
- Application-layer implementation complete
- No forbidden constraints violated

### 10.2 Functional Success
- Web UI can display channels, threads, tasks
- Visibility and access control functional
- Hierarchy relationships preserved
- Review and audit state trackable

### 10.3 Integration Success
- Existing functionality preserved
- New features integrate seamlessly
- Performance impact minimal
- Migration path clear

## 11. Next Actions

### 11.1 Immediate Actions
1. **HEPHAESTUS:** Begin Phase 1 schema implementation
2. **THOTH:** Prepare TOON update strategy
3. **ATHENA:** Validate UI requirements against design

### 11.2 Phase Transitions
- WOLFIE will authorize each phase transition
- Each phase must be complete before next begins
- Validation required at each transition point

---

**This directive explicitly authorizes real database schema implementation work and stops the pattern of documentation-only updates. Implementation begins immediately under HEPHAESTUS leadership.**
