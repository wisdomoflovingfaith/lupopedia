---
lupopedia.init:
  lupopedia.version: "4.0.77"
  system_version: "4.0.77"
  schema_family: "planning"
  artifact_family: "system-planning"
  created_utc: "20260316"
  environment: "lupopedia-core"

lupopedia.metadata:
  file_path_from_root: "docs/planning/bayesian_decision_tracking_TASKS.md"
  artifact_type: "planning-tasks"
  artifact_kind: "task-breakdown"
  purpose: "Atomic task breakdown for Bayesian Decision Tracking System implementation"
  status: "schema foundation shipped in 4.0.77; engine and integrations deferred"
  tags: ["bayesian", "decision-tracking", "tasks", "implementation", "4.0.77"]
  delegation_chain: "wolfie:captain:lilith:antigravity"
  security_scope: "system-internal"

lupopedia.routing:
  channel_id: 42
  actor_id: 103
  actor_name: "antigravity"
  recipient_actor_ids: [1000]
  recipient_actor_names: ["captain"]
  session_id: "L-LUPO-ANTIGRAVITY-PLANNING"
  session_name: "Bayesian Decision Tracking — Planning Phase"
  priority: "high"
  requires_approval_from: ["captain", "lilith"]
  next_status_on_approve: "approved-planning"
  next_location_on_approve: "docs/status/"

lupopedia.headers:
  lupopedia.version: "4.0.77"
  lupopedia.schema: "planning"
  system_version: "4.0.77"
  file_path_from_root: "docs/planning/bayesian_decision_tracking_TASKS.md"
  web_path: "[web_path](http://www.lupopedia.com/planning/bayesian_decision_tracking_TASKS)"
  last_modified_utc: "20260316"
  channel_id: 42
  actor_id: 103
  actor_name: "antigravity"
  delegation_chain: "antigravity:root"
  artifact_type: "planning"
  artifact_kind: "architecture"
  purpose: "Atomic task breakdown for Bayesian Decision Tracking System; Phase 1 schema foundation shipped in 4.0.77"
  status: "foundation_shipped"
  tags: ["bayesian", "decisions", "tasks", "4.0.77"]

lupopedia.session:
  session_id: "L-LUPO-ANTIGRAVITY-PLANNING"
  session_name: "L-LUPO-ANTIGRAVITY-PLANNING"
  actor_id: 103
  actor_name: "antigravity"
  channel_id: 42
  paired_actor_id: 1000

lupopedia.footer:
  last_verified: "20260316"
  last_verified_by: "antigravity"
  orchestrator: "antigravity"
  next_action:
    - "Deferred: engine, integrations, APIs; schema foundation is complete in 4.0.77"
---
# file: Bayesian Decision Tracking System Tasks — session: L-LUPO-ANTIGRAVITY-PLANNING — delegation: antigravity:root — web_path: http://www.lupopedia.com/planning/bayesian_decision_tracking_TASKS

# Bayesian Decision Tracking System - Implementation Tasks

**Status:** Schema foundation shipped in 4.0.77; engine and integrations deferred  
**Version:** 4.0.77  
**Author:** Antigravity (Actor 103); Cursor (102) — schema scope correction and doc reconciliation  
**Reviewers:** Captain (Actor 1000), LILITH (Actor 2)

**4.0.77 shipped:** Decision tables are **required** in `install_new_lupopedia.sql` with required `channel_id` and `project_id` (scope boundaries). TOONs and `BAYESIAN_DECISION_DOCTRINE.md` are aligned. Minimal `BayesianDecisionService` scaffold exists; traversal and integrations are **not** in scope for 4.0.77. For 4.0.x, canonical schema path is **install SQL + seed only**; migration files are dev-only and not part of the supported install path (see UPGRADE_POLICY_DOCTRINE.md).

---

## Phase 1: Schema Foundation (shipped in 4.0.77)

### Database

**Task 1.1: Migration file (4.0.x: not canonical)**
- **Note:** For 4.0.x, the **canonical** schema path is `install_new_lupopedia.sql` only. Standalone migration files in `mysql/migrations/` are **dev-only or legacy**; they are not run as part of the supported Crafty 3.7.5 → Lupopedia or fresh-install path. Any migration file created for local dev must not be documented as a required upgrade step.
- **Status:** N/A for 4.0.x; schema is consolidated in install SQL.

**Task 1.2: Define lupo_decisions table**
- **Status:** Done in 4.0.77. Table is in `install_new_lupopedia.sql` with `channel_id BIGINT NOT NULL`, `project_id BIGINT NOT NULL`, and scoped indexes (channel_time, project_time, channel_project_time).
- **Validation:** TOON matches install SQL.

**Task 1.3: Define lupo_decision_edges table**
- **Status:** Done in 4.0.77. Table is in `install_new_lupopedia.sql` with `channel_id BIGINT NOT NULL`, `project_id BIGINT NOT NULL`.
- **Validation:** TOON matches install SQL.

**Task 1.4: Define lupo_decision_influences table**
- **Status:** Done in 4.0.77. Table is in `install_new_lupopedia.sql` with `channel_id BIGINT NOT NULL`, `project_id BIGINT NOT NULL`.
- **Validation:** TOON matches install SQL.

**Task 1.5: Add indexes for query patterns**
- **Status:** Done in 4.0.77. Scoped indexes added per BAYESIAN_DECISION_DOCTRINE.md §2.

**Task 1.6: Update install_new_lupopedia.sql**
- **Status:** Done. Decision tables are required in install SQL.

### Registry

**Task 1.7: Reserve ID ranges for decision system**
- **Implementation:** Add entries to lupo_registry for decision_id allocation
- **Dependencies:** None (can be done in parallel)
- **Validation:** Registry entries created, ID allocation working

### Documentation

**Task 1.8: Create TOON files for all tables**
- **Files:** 
  - `lupo-docs/database/lupopedia/tables/active/lupo_decisions.toon.json`
  - `lupo-docs/database/lupopedia/tables/active/lupo_decision_edges.toon.json`
  - `lupo-docs/database/lupopedia/tables/active/lupo_decision_influences.toon.json`
- **Dependencies:** Tasks 1.2, 1.3, 1.4
- **Validation:** TOON files validate against actual schema

**Task 1.9: Create BAYESIAN_DECISION_DOCTRINE.md**
- **File:** `lupo-docs/doctrine/BAYESIAN_DECISION_DOCTRINE.md`
- **Content:** Complete doctrine for decision tracking system
- **Dependencies:** PLAN.md approval
- **Validation:** Doctrine aligns with constitutional rules

**Task 1.10: Update lupo_metadata doctrine for decision states**
- **Implementation:** Add decision_state entity_type documentation
- **Dependencies:** Task 1.9
- **Validation:** Metadata doctrine includes decision snapshots

---

## Phase 2: Core Engine

### PHP Classes

**Task 2.1: Create BayesianTraversalEngine.php**
- **File:** `lupo-database/lupopedia/content/lupo-app/Services/BayesianDecisionService.php`
- **Methods:** 
  - `recordDecision($data)`
  - `getParent($decisionId)`
  - `getChildren($decisionId)`
  - `walkForward($decisionId, $criteria)`
  - `walkBackward($decisionId, $criteria)`
- **Dependencies:** Tasks 1.2, 1.3, 1.4, 1.5
- **Validation:** Basic CRUD operations against lupo_decisions / lupo_decision_edges / lupo_decision_influences work (foundation only; full traversal logic deferred)

**Task 2.2: Implement probability navigation**
- **Methods:**
  - `getHigherProbabilitySibling($decisionId)`
  - `getLowerProbabilitySibling($decisionId)`
  - `getProbabilityRange($decisionId, $threshold)`
- **Dependencies:** Task 2.1
- **Validation:** Navigation returns correct probability-ordered results

**Task 2.3: Implement branch management**
- **Methods:**
  - `abandonBranch($rootDecisionId)`
  - `restoreBranch($rootDecisionId)`
  - `pruneAbandoned($olderThanDays)`
- **Dependencies:** Task 2.1
- **Validation:** Branch operations maintain tree integrity

### State Management

**Task 2.4: Create DecisionStateManager.php**
- **File:** `lupo-includes/classes/DecisionStateManager.php`
- **Methods:**
  - `captureState($context)`
  - `restoreState($stateSnapshotId)`
  - `hashState($stateData)`
- **Dependencies:** Tasks 1.10, 2.1
- **Validation:** State capture/restore works, deduplication functional

---

## Phase 3: Integration

### Session System

**Task 3.1: Add current_decision_id to lupo_sessions**
- **Implementation:** Alter table to add current_decision_id BIGINT
- **Dependencies:** Task 1.1 (schema alteration capability)
- **Validation:** Sessions table updated, existing data preserved

**Task 3.2: Update session initialization**
- **Implementation:** Modify session bootstrap to track decision context
- **Dependencies:** Tasks 2.1, 3.1
- **Validation:** Session initialization includes decision tracking

**Task 3.3: Implement controller grammar methods**
- **Methods:**
  - `handleUp($session)`
  - `handleDown($session)`
  - `handleLeft($session)`
  - `handleRight($session)`
  - `handleA($session)`, `handleB($session)`, etc.
- **Dependencies:** Tasks 2.1, 2.2, 3.2
- **Validation:** Controller operations create appropriate decisions

### Dialog System

**Task 3.4: Modify message handler to record decisions**
- **Implementation:** Update dialog message processing pipeline
- **Dependencies:** Tasks 2.1, 3.2
- **Validation:** Messages have associated decisions

**Task 3.5: Store decision_id in message metadata**
- **Implementation:** Modify message storage to include decision_id
- **Dependencies:** Task 3.4
- **Validation:** Message metadata contains decision references

**Task 3.6: Add decision context to dialog threads**
- **Implementation:** Thread queries include decision context
- **Dependencies:** Task 3.5
- **Validation:** Dialog threads show decision history

### Tasks System

**Task 3.7: Add optional decision tracking to task completion**
- **Implementation:** Modify task completion logic
- **Dependencies:** Task 2.1
- **Validation:** Task completion can emit decisions

**Task 3.8: Store decision_id in task metadata**
- **Implementation:** Update task metadata storage
- **Dependencies:** Task 3.7
- **Validation:** Task metadata includes decision references

---

## Phase 4: CLI Tools

**Task 4.1: Create decisions:walk command**
- **File:** `lupo-bin/decisions_walk.php`
- **Function:** Navigate decision trees from CLI
- **Dependencies:** Tasks 2.1, 2.2
- **Validation:** CLI can traverse decision paths

**Task 4.2: Create decisions:compare command**
- **File:** `lupo-bin/decisions_compare.php`
- **Function:** Compare decision patterns between agents
- **Dependencies:** Tasks 2.1, 3.2
- **Validation:** Comparison reports generated correctly

**Task 4.3: Create decisions:tree command**
- **File:** `lupo-bin/decisions_tree.php`
- **Function:** ASCII visualization of decision trees
- **Dependencies:** Tasks 2.1, 2.3
- **Validation:** Tree visualization matches structure

**Task 4.4: Create decisions:prune command**
- **File:** `lupo-bin/decisions_prune.php`
- **Function:** Maintenance command for pruning decisions
- **Dependencies:** Task 2.3
- **Validation:** Pruning operations safe and effective

**Task 4.5: Create decisions:audit command**
- **File:** `lupo-bin/decisions_audit.php`
- **Function:** Integrity checks for decision data
- **Dependencies:** Tasks 1.2, 1.3, 1.4, 2.1
- **Validation:** Audit detects inconsistencies correctly

---

## Phase 5: API & Visualization

**Task 5.1: Create /api/v1/decisions/timeline endpoint**
- **File:** `lupo-api/v1/decisions_timeline.php`
- **Function:** Return timeline of decisions for actor/session
- **Dependencies:** Tasks 2.1, 3.2
- **Validation:** Timeline API returns correct data

**Task 5.2: Create /api/v1/decisions/graph endpoint**
- **File:** `lupo-api/v1/decisions_graph.php`
- **Function:** Return decision graph structure
- **Dependencies:** Tasks 2.1, 1.3
- **Validation:** Graph API returns proper JSON structure

**Task 5.3: Create /api/v1/decisions/compare endpoint**
- **File:** `lupo-api/v1/decisions_compare.php`
- **Function:** REST API for decision comparison
- **Dependencies:** Tasks 2.1, 3.2
- **Validation:** Compare API returns agent comparisons

**Task 5.4: Create /api/v1/decisions/record endpoint**
- **File:** `lupo-api/v1/decisions_record.php`
- **Function:** REST API for recording decisions
- **Dependencies:** Task 2.1
- **Validation:** Record API creates decisions correctly

**Task 5.5: Basic JavaScript timeline visualization**
- **File:** `lupo-includes/js/decision-timeline.js`
- **Function:** Frontend visualization of decision timelines
- **Dependencies:** Task 5.1
- **Validation:** Timeline renders correctly in browser

---

## Phase 6: Testing & Documentation

**Task 6.1: Unit tests for traversal engine**
- **Files:** `lupo-tests/unit/decision_engine_*.php`
- **Coverage:** All BayesianTraversalEngine methods
- **Dependencies:** Task 2.1
- **Validation:** All tests pass, coverage > 90%

**Task 6.2: Integration tests with dialog system**
- **Files:** `lupo-tests/integration/decision_dialog_*.php`
- **Coverage:** Message → Decision pipeline
- **Dependencies:** Tasks 3.4, 3.5, 3.6
- **Validation:** Integration tests pass end-to-end

**Task 6.3: Performance tests with 100k+ decisions**
- **Files:** `lupo-tests/performance/decision_performance_*.php`
- **Coverage:** Query performance under load
- **Dependencies:** Tasks 1.5, 2.1
- **Validation:** Performance meets <100ms target

**Task 6.4: Complete TOON validation**
- **Implementation:** Run TOON validation scripts
- **Dependencies:** Task 1.8
- **Validation:** All TOON files validate against schema

**Task 6.5: Final doctrine review**
- **Implementation:** Review all doctrine compliance
- **Dependencies:** Tasks 1.9, 1.10
- **Validation:** Doctrine review passes

**Task 6.6: Example usage documentation**
- **Files:** 
  - `lupo-docs/examples/decision_tracking_examples.md`
  - `lupo-docs/api/DECISION_API_REFERENCE.md`
- **Dependencies:** All previous tasks
- **Validation:** Examples work as documented

---

## Phase 7: Federation Extensions (Optional/Phase 2)

**Task 7.1: Implement cross-node decision replication**
- **Implementation:** Federation replication for decision tables
- **Dependencies:** Tasks 1.2, 1.3, 1.4 (federation fields present)
- **Validation:** Replication maintains decision integrity

**Task 7.2: Add node-aware decision queries**
- **Implementation:** Query modifications for federation context
- **Dependencies:** Task 7.1
- **Validation:** Queries respect node boundaries

**Task 7.3: Federation conflict resolution**
- **Implementation:** Conflict resolution for decision graphs
- **Dependencies:** Task 7.1
- **Validation:** Conflicts resolved per policy

---

## Dependency Graph Summary

### Linear Dependencies

```
Phase 1 (Schema) → Phase 2 (Core Engine) → Phase 3 (Integration)
                                          ↘
Phase 1 → Phase 4 (CLI Tools) Phase 3 → Phase 5 (API)
         ↘ /
          Phase 6 (Testing) ←──────────────
                    ↖
                     Phase 7 (Federation) ← Phase 1
```

### Critical Path

**Phase 1 → Phase 2 → Phase 3 → Phase 6**

This represents the minimum viable implementation path:
1. Schema foundation
2. Core engine
3. Integration with existing systems
4. Testing and validation

### Parallel Execution Opportunities

- **Task 1.7** (Registry) can run parallel to Phase 1 database tasks
- **Phase 4** (CLI Tools) can start after Phase 2, parallel to Phase 3
- **Phase 5** (API) can start after Phase 3, parallel to Phase 6
- **Phase 7** (Federation) is optional and can run after Phase 1

---

## Resource Requirements

### Phase 1: Schema Foundation
- **Database Administrator**: Schema design and migration
- **Documentation Writer**: TOON files and doctrine
- **Estimated Effort**: 2-3 days

### Phase 2: Core Engine
- **PHP Developer**: Engine implementation
- **Algorithm Specialist**: Probability navigation
- **Estimated Effort**: 5-7 days

### Phase 3: Integration
- **System Integration Specialist**: Existing system integration
- **Frontend Developer**: Controller updates
- **Estimated Effort**: 4-6 days

### Phase 4: CLI Tools
- **CLI Developer**: Command-line tools
- **Estimated Effort**: 3-4 days

### Phase 5: API & Visualization
- **API Developer**: REST endpoints
- **Frontend Developer**: JavaScript visualization
- **Estimated Effort**: 4-5 days

### Phase 6: Testing & Documentation
- **QA Engineer**: Test development
- **Technical Writer**: Documentation
- **Estimated Effort**: 3-4 days

---

## Risk Mitigation

### Technical Risks
1. **Performance Issues**: Addressed through indexing strategy (Task 1.5)
2. **Schema Complexity**: Mitigated by doctrine-first approach
3. **Integration Challenges**: Reduced by clear contracts (Section 4)

### Project Risks
1. **Resource Availability**: Parallel execution options provide flexibility
2. **Timeline Delays**: Optional phases can be deferred
3. **Scope Creep**: Clear task boundaries prevent expansion

---

## Success Criteria

### Phase Completion Criteria
- **Phase 1**: Schema deployed, TOONs validated
- **Phase 2**: Engine functional, basic operations working
- **Phase 3**: Integration complete, existing systems enhanced
- **Phase 4**: CLI tools operational
- **Phase 5**: APIs functional, visualization working
- **Phase 6**: All tests passing, documentation complete

### Overall Success Metrics
- Decision tracking latency < 100ms
- Storage growth within projections
- 99.9% uptime for decision APIs
- Complete audit trail for all decisions
- Federation support operational

---

## Next Steps

1. **Immediate**: Review and approve this task breakdown
2. **Phase 1 Start**: Begin schema foundation tasks
3. **Resource Assignment**: Allocate developers to parallel tracks
4. **Progress Tracking**: Regular status updates per phase
5. **Integration Testing**: Cross-phase validation at milestones

---

**Document Status:** Ready for Review  
**Next Reviewers:** Captain (Actor 1000), LILITH (Actor 2)  
**Expected Start:** Upon approval and resource allocation  
**Estimated Completion:** 3-4 weeks (full implementation)
