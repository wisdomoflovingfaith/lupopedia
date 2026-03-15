# Lupopedia Implementation Tasks - Project System Integration

**Version:** 4.0.77  
**Author:** Cursor (actor_id: 102)  
**Scope:** Detailed task breakdown for Project Registry and 4.0.77 validation  
**Status:** 4.0.76 released; 4.0.77 active — Crafty 3.7.5 upgrade validation in progress  

---

## 4.0.77 — Crafty Syntax 3.7.5 Upgrade Validation (core task)

**Purpose:** Confirm the only supported upgrade path (Crafty 3.7.5 → Lupopedia 4.0.x). No Lupopedia→Lupopedia upgrade is assumed before 4.1.0.

**Required workflow:**
1. Drop all current Lupopedia tables.
2. Load original Crafty Syntax 3.7.5 schema/data baseline (e.g. `old_crafty_syntax_3_7_5_start.sql` or equivalent).
3. Run Lupopedia install.php (install + seed including seed_projects.sql).
4. Validate successful upgrade into 4.0.77 (tables present, default project 1, channels valid, no errors).

**Task:** [ ] **Crafty Syntax 3.7.5 Upgrade Validation** — Execute workflow above and document result. Update this section and plan.md when complete.

**References:** [CURSOR_PROJECT_SYSTEM_4_0_76_UPGRADE_GUIDE.md](lupo-docs/status/CURSOR_PROJECT_SYSTEM_4_0_76_UPGRADE_GUIDE.md), single-install doctrine (no Lupopedia→Lupopedia until 4.1.0).

---

## Phase 1: Finalize Documentation — COMPLETE (Cursor 4.0.76)

Phase 1 aligned with Windsurf handoff: [lupo-docs/status/WINDSURF_TO_CURSOR_PROJECT_SYSTEM_HANDOFF_4_0_76.md](lupo-docs/status/WINDSURF_TO_CURSOR_PROJECT_SYSTEM_HANDOFF_4_0_76.md), [WINDSURF_PROJECT_SYSTEM_TASKS_4_0_76.md](lupo-docs/status/WINDSURF_PROJECT_SYSTEM_TASKS_4_0_76.md).

### Task 1.1: Update Executive Summary
**File:** `EXECUTIVE_SUMMARY.md`  
**Status:** ✅ Complete  
**Completed by:** Cursor (Phase 1 implementation pass)

**Subtasks:**
- [x] Add "Projects as Semantic Universes" section
- [x] Document Lupopedia as one project model
- [x] Explain federation node multi-project architecture
- [x] Describe external agent cross-project operations
- [x] Update channel/dialog scoping within projects
- [x] Add cross-references to PROJECT_REGISTRY_DOCTRINE, PROJECT_REGISTRY_WORKFLOW, PROJECT_REGISTRY_SCHEMA_DESIGN

**Acceptance Criteria:** Met.

---

### Task 1.2: Update Root Rules with Project Context
**Files:** lupo-docs/doctrine/channels.md (and any other relevant doctrine)  
**Status:** ✅ Complete  
**Completed by:** Cursor (Phase 1 implementation pass)

**Subtasks:**
- [x] Review `channels.md` for project hierarchy context
- [x] channels.md updated with Projects and Channels section; THREAD_DIALOG_SYSTEM.md and IDE_AGENT_RULES.md not present in repo
- [x] Link to PROJECT_REGISTRY_DOCTRINE.md added in channels.md
- [x] IDE infer vs external declare clarified

**Acceptance Criteria:** Met.

---

### Task 1.3: Validate Documentation Cross-References
**Files:** All updated documentation  
**Status:** ✅ Complete  
**Completed by:** Cursor (Phase 1 implementation pass)

**Subtasks:**
- [x] Verify all links in PROJECTS.md are functional
- [x] Check PROJECTS_API.md references are correct
- [x] Validate ROOTRULES_EXTERNAL_ACTOR.md cross-refs
- [x] Ensure ACTOR_REGISTRATION_CHECKLIST.md project section
- [x] Confirm AGENT_REGISTRY.md project context links
- [x] Test README.md project section links

**Acceptance Criteria:** Met.

---

## Phase 2: Schema Implementation — COMPLETE (Cursor 4.0.76)

### Task 2.1: Create Production Install SQL
**File:** `lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql`  
**Status:** ✅ Complete  
**Completed by:** Cursor (4.0.76 finalization)

**Subtasks:**
- [x] Review draft SQL in `create_lupo_projects.sql.md`
- [x] Validate against DATABASE_DOCTRINE.md requirements
- [x] Ensure table ceiling compliance (159 tables)
- [x] lupo_projects table already in install SQL
- [x] Update table count in TABLE_COUNT_DOCTRINE.md
- [x] Run TOON generation pipeline (generate_toon_from_sql.py)
- [x] Validate new TOON files

**Acceptance Criteria:** Met.

---

### Task 2.2: Create Project Seed Data
**File:** `lupo-database/lupopedia/mysql/seed/seed_projects.sql`  
**Status:** ✅ Complete  
**Completed by:** Cursor (4.0.76 finalization)

**Subtasks:**
- [x] Create default Lupopedia development project (project_id 1)
- [x] Align seed with install schema (default_channel_id, all doctrine columns)
- [x] No AUTO_INCREMENT; explicit project_id
- [x] Validate seed follows project doctrine

**Acceptance Criteria:** Met.

---

### Task 2.3: Channel Table Migration
**File:** `lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql`  
**Status:** ✅ Complete  
**Completed by:** Cursor (4.0.76 finalization)

**Subtasks:**
- [x] Add project_id BIGINT DEFAULT NULL to lupo_channels
- [x] Create index lupo_channels_idx_project_id
- [x] Backward compatibility (project_id DEFAULT NULL)
- [x] Regenerate TOONs after schema change

**Acceptance Criteria:** Met.

---

## Phase 3: Application Implementation — COMPLETE (Cursor 4.0.76)

### Task 3.1: Create Project Service Layer
**Directory:** `lupo-database/lupopedia/content/lupo-app/Services/`  
**File:** `ProjectService.php`  
**Status:** ✅ Complete  
**Completed by:** Cursor (4.0.76 finalization)

**Subtasks:**
- [x] Implement createProject(), getProjectById(), getProjectByKey(), getProjectBySlug()
- [x] Implement updateProject(), archiveProject(), freezeProject(), listProjects()
- [x] PHP 5.6+ compatible; PDO_DB only; BIGINT timestamps
- [x] Registered in bootstrap as $GLOBALS['lupo_project_service']

**Acceptance Criteria:** Met.

---

### Task 3.2: Implement Registry Integration
**Directory:** `lupo-database/lupopedia/projects/`  
**Status:** ✅ Complete  
**Completed by:** Cursor (4.0.76 finalization)

**Subtasks:**
- [x] Create projects/registry.json with schema_version, projects array, next_id_hint
- [x] Default project_id 1 (lupopedia-core) in registry

**Acceptance Criteria:** Met.

---

### Task 3.3: Implement Project-Aware API Endpoints
**Directory:** `lupo-api/v1/projects/`  
**Status:** ✅ Complete  
**Completed by:** Cursor (4.0.76 finalization)

**Subtasks:**
- [x] GET list.php, GET get.php?id=
- [x] POST create.php, PUT update.php, POST archive.php, POST freeze.php
- [x] Project context and BIGINT timestamps in responses

**Acceptance Criteria:** Met.

---

## Phase 4: Testing and Validation — COMPLETE (Cursor 4.0.76)

### Task 4.1: Create Unit Test Suite
**Directory:** `lupo-tests/unit/`  
**Status:** ✅ Complete  
**Completed by:** Cursor (4.0.76 finalization)

**Subtasks:**
- [x] test_project_schema.php (install + TOON)
- [x] test_project_allocation.php (registry.json)
- [x] test_project_service.php (ProjectService methods; optional DB)

**Acceptance Criteria:** Met.

---

### Task 4.2: Create Integration Tests
**Directory:** `lupo-tests/integration/`  
**Status:** ✅ Complete  
**Completed by:** Cursor (4.0.76 finalization)

**Subtasks:**
- [x] Create test_project_api_endpoints.php (endpoint file existence)

**Acceptance Criteria:** Met.

---

### Task 4.3: Migration Validation
**Status:** ✅ Documented (manual)
**Completed by:** Cursor (4.0.76 finalization)

**Subtasks:**
- [x] Document fresh install + seed_projects.sql in Phase 2/4 artifacts
- [x] Backward compatibility (project_id NULL) and Crafty upgrade path unchanged
- Migration tests: manual per doctrine (fresh install, Crafty 3.7.5 → 4.0.76)

---

## Task Dependencies

### Critical Path
1. **Task 1.1** → **Task 1.2** → **Task 1.3** (Documentation Phase)
2. **Task 2.1** → **Task 2.2** → **Task 2.3** (Schema Phase)
3. **Task 3.1** → **Task 3.2** → **Task 3.3** (Application Phase)
4. **Task 4.1** → **Task 4.2** → **Task 4.3** (Testing Phase)

### Blockers
- **None.** Phases 2–4 complete (Cursor 4.0.76 finalization). Windsurf review addressed: upgrade guide, doc updates, edge-case/performance tests, CURSOR_PROJECT_SYSTEM_4_0_76_FINAL_COMPLETION.md.

### Parallel Tasks
- Tasks 1.1, 1.2, 1.3 can be done in parallel
- Tasks 2.1, 2.2, 2.3 can be done in parallel after approval
- Tasks 4.1, 4.2, 4.3 can be done in parallel after Phase 3

---

## Quality Gates

### Gate 1: Documentation Complete ✅
**Criteria:**
- [x] Executive Summary updated with projects section and design package links
- [x] All root rules reference project context (channels.md; THREAD_DIALOG_SYSTEM/IDE_AGENT_RULES not in repo)
- [x] All cross-references validated
- [x] Documentation mapping updated

### Gate 2: Schema Ready ✅
**Criteria:**
- [x] lupo_projects in install SQL; project_id on lupo_channels; seed_projects.sql
- [x] TOON files generated; table count 159; ceiling compliant

### Gate 3: Application Ready ✅
**Criteria:**
- [x] ProjectService; registry.json; lupo-api/v1/projects/* endpoints
- [x] Doctrine-compliant (PDO_DB, BIGINT timestamps)

### Gate 4: Testing Complete ✅
**Criteria:**
- [x] Unit tests (schema, allocation, service); integration (API endpoint files)
- [x] Migration validation documented (manual)

---

## Risk Mitigation Tasks

### High Priority Risk Mitigation
- [ ] **Table Ceiling Compliance**: Verify table count after each schema change
- [ ] **Database Doctrine Compliance**: Review all SQL against doctrine before implementation
- [ ] **Backward Compatibility**: Test with existing data before deployment

### Medium Priority Risk Mitigation
- [ ] **Performance Impact**: Benchmark new queries and indexes
- [ ] **Registry Synchronization**: Test consistency checks
- [ ] **External Actor Impact**: Communicate API changes early

---

## Success Metrics

### Functional Metrics
- [ ] Project creation success rate: >99%
- [ ] Registry allocation accuracy: 100%
- [ ] API response time: <200ms for project operations
- [ ] Data integrity: 0 corruption incidents

### Quality Metrics
- [ ] Test coverage: >90%
- [ ] Code review approval: 100%
- [ ] Documentation completeness: 100%
- [ ] Doctrine compliance: 100%

### Performance Metrics
- [ ] Project resolution queries: <100ms
- [ ] Registry allocation: <50ms
- [ ] Channel-project joins: Optimized with indexes
- [ ] API throughput: >100 requests/second

---

**Task File Status:** Phase 1–4 complete (4.0.76); 4.0.77 active  
**Next Action:** Execute Crafty Syntax 3.7.5 Upgrade Validation for 4.0.77  
**Review Date:** 4.0.77 initialization  
**Implementation Guard:** No Lupopedia→Lupopedia upgrade before 4.1.0. Single upgrade path: Crafty 3.7.5 → Lupopedia 4.0.x.
