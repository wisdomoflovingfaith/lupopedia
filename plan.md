# Lupopedia Implementation Plan - Project System Integration

**Version:** 4.0.76  
**Author:** Cursor (actor_id: 102)  
**Scope:** Implementation roadmap for Project Registry design package  
**Status:** Implementation complete; production-ready (Windsurf review addressed; see CURSOR_PROJECT_SYSTEM_4_0_76_FINAL_COMPLETION.md)  

---

## Executive Summary

The Project Registry design package is **complete and ready for implementation**. All required documentation has been created and cross-referenced. The design aligns with existing Lupopedia doctrine and maintains deterministic identity patterns.

### Current State Assessment

#### ✅ **Completed Design Package**
- **PROJECT_REGISTRY_DOCTRINE.md** - Canonical doctrine with identity, allocation, and lifecycle
- **PROJECT_REGISTRY_SCHEMA_DESIGN.md** - Complete table architecture and database design
- **create_lupo_projects.sql.md** - Draft SQL implementation (marked as DRAFT)
- **PROJECT_REGISTRY_WORKFLOW.md** - End-to-end workflow documentation
- **Cross-references updated** - All existing docs now reference project registry

#### 📊 **Table Count Status**
- **Current:** 159 tables (install SQL); 159 TOON files in `lupo-database/lupopedia/toon/`
- **Ceiling:** Advisory 222-table limit; well within capacity
- **Status:** ✅ Phase 2–4 complete (lupo_projects in install; project_id on lupo_channels; seed, service, API, tests)

#### 🔗 **Documentation Integration**
- PROJECTS.md - Updated with design package references
- PROJECTS_API.md - External actor specification complete
- ROOTRULES_EXTERNAL_ACTOR.md - Doctrine subset for external agents
- ACTOR_REGISTRATION_CHECKLIST.md - Project context section added
- AGENT_REGISTRY.md - Agent-project relationship clarified
- README.md - Projects section with design package links
- EXECUTIVE_SUMMARY.md - Includes "Projects as Semantic Universes" section and design package links (Phase 1.1 complete)
- CHANGELOG.md - 4.0.76 Project System entries added
- Windsurf handoff and execution framework: WINDSURF_TO_CURSOR_PROJECT_SYSTEM_HANDOFF_4_0_76.md, WINDSURF_PROJECT_SYSTEM_EXECUTION_PLAN_4_0_76.md, WINDSURF_PROJECT_SYSTEM_TASKS_4_0_76.md, WINDSURF_PROJECT_SYSTEM_IMPLEMENTATION_REVIEW_4_0_76.md

---

## Implementation Roadmap

### Phase 1: Finalize Documentation (Immediate)

#### 1.1 Complete Executive Summary Update
**File:** `EXECUTIVE_SUMMARY.md`  
**Task:** Add "Projects as Semantic Universes" section  
**Priority:** High  
**Effort:** Low

**Content Required:**
- Lupopedia as one project explanation
- Federation node multi-project model
- External agent cross-project operations
- Channel/dialog scoping within projects

#### 1.2 Update Root Rules References
**Files:** Various doctrine files  
**Task:** Ensure all root rules reference project context  
**Priority:** Medium  
**Effort:** Medium

**Updates Needed:**
- channels.md - Project hierarchy context
- THREAD_DIALOG_SYSTEM.md - Project scoping for dialogs
- Any other doctrine missing project references

### Phase 2: Schema Implementation — COMPLETE (Cursor 4.0.76)

#### 2.1 Production SQL Creation
**File:** `lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql`  
**Task:** Integrate lupo_projects table into install SQL  
**Priority:** High  
**Effort:** High

**Steps:**
1. Review draft SQL in `create_lupo_projects.sql.md`
2. Validate against database doctrine
3. Add to install SQL after existing tables
4. Update table count documentation
5. Generate new TOON files

#### 2.2 Seed Data Creation
**File:** `lupo-database/lupopedia/mysql/seed/seed_projects.sql`  
**Task:** Create seed data for projects table  
**Priority:** High  
**Effort:** Medium

**Seed Data Required:**
- Default Lupopedia development project (project_id: 1)
- Sample projects for testing
- Registry alignment with existing actors/channels

#### 2.3 Channel Table Migration
**File:** `lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql`  
**Task:** Add project_id column to lupo_channels  
**Priority:** High  
**Effort:** Medium

**Migration Steps:**
1. Add `project_id BIGINT DEFAULT NULL` to lupo_channels
2. Create indexes for project-based queries
3. Update existing channels where appropriate
4. Maintain backward compatibility

### Phase 3: Application Implementation — COMPLETE (Cursor 4.0.76)

#### 3.1 Project Service Layer
**Directory:** `app/Services/`  
**Task:** Create ProjectService class  
**Priority:** High  
**Effort:** High

**Service Methods:**
- createProject() - Registry allocation and DB insertion
- getProjectById() - Project resolution
- getProjectByKey() - Stable key lookup
- getProjectBySlug() - URL-friendly resolution
- updateProject() - Project modifications
- archiveProject() - Project archival
- freezeProject() - Emergency suspension

#### 3.2 Registry Integration
**File:** `lupo-database/lupopedia/projects/`  
**Task:** Implement project registry mirrors  
**Priority:** Medium  
**Effort:** Medium

**Registry Components:**
- project_id/registry.json files per project
- Federation node project listings
- Registry allocation logic
- Conflict detection and resolution

#### 3.3 API Endpoints
**Directory:** `lupo-api/`  
**Task:** Implement project-aware REST endpoints  
**Priority:** High  
**Effort:** High

**Required Endpoints:**
- GET /projects/list - List projects
- GET /projects/{id} - Get project details
- POST /projects/create - Create new project
- PUT /projects/{id} - Update project
- POST /projects/{id}/archive - Archive project
- POST /projects/{id}/freeze - Freeze project

### Phase 4: Testing and Validation — COMPLETE (Cursor 4.0.76)

#### 4.1 Unit Tests
**Directory:** `lupo-tests/unit/`  
**Task:** Create comprehensive test suite  
**Priority:** High  
**Effort:** High

**Test Coverage:**
- Project creation workflow
- Registry allocation logic
- Database insertion and updates
- Uniqueness constraint validation
- Soft delete behavior
- Federation node scoping

#### 4.2 Integration Tests
**Directory:** `lupo-tests/integration/`  
**Task:** End-to-end workflow testing  
**Priority:** Medium  
**Effort:** Medium

**Integration Scenarios:**
- Project creation with default channel
- Channel-project relationship validation
- Cross-project actor operations
- External actor API compliance

#### 4.3 Migration Testing
**Task:** Test upgrade from existing installations  
**Priority:** High  
**Effort:** High

**Migration Test Cases:**
- Fresh install with projects
- Upgrade from 4.0.75 without projects
- Channel migration with existing data
- Registry synchronization

---

## Dependencies and Prerequisites

### Required Reading
1. **PROJECT_REGISTRY_DOCTRINE.md** - Complete project identity doctrine
2. **PROJECT_REGISTRY_SCHEMA_DESIGN.md** - Table architecture and design
3. **PROJECT_REGISTRY_WORKFLOW.md** - End-to-end processes
4. **DATABASE_DOCTRINE.md** - Database design constraints
5. **SAFE_MIGRATION_DOCTRINE.md** - Migration procedures

### System Requirements
- PHP 5.6+ compatibility maintained
- MySQL 8.0+ / MariaDB 10.5+ / PostgreSQL support
- Existing TOON generation pipeline compatibility
- Registry allocation system integration

### Cross-Agent Coordination
- **Cursor** - Lead orchestration for implementation
- **Windsurf** - Rules propagation and validation
- **Other IDE agents** - Compatibility testing and feedback

---

## Risk Assessment and Mitigation

### High-Risk Items
1. **Table Ceiling Compliance** ✅ Mitigated
   - Current design well within 222-table limit
   - 11 tables remaining for future features

2. **Database Doctrine Compliance** ✅ Mitigated
   - Design follows all database doctrine requirements
   - No foreign keys, BIGINT timestamps, soft delete

3. **Backward Compatibility** ⚠️ Requires Careful Implementation
   - Channels may exist without projects
   - Migration must preserve existing functionality

### Medium-Risk Items
1. **Performance Impact** - New indexes and queries
2. **Registry Synchronization** - Filesystem/DB consistency
3. **External Actor Adoption** - API changes may break existing clients

---

## Success Criteria

### Functional Requirements
- ✅ Projects created with deterministic identity
- ✅ Registry-first allocation working
- ✅ Federation node scoping enforced
- ✅ Channel-project relationships established
- ✅ External actor API compliance
- ✅ Backward compatibility maintained

### Non-Functional Requirements
- ✅ Database doctrine compliance
- ✅ Table ceiling doctrine compliance
- ✅ Multi-agent continuity preserved
- ✅ Documentation completeness and cross-references

### Performance Requirements
- Project resolution queries < 100ms
- Registry allocation < 50ms
- Channel-project joins optimized
- Index strategy for common query patterns

---

## Timeline Estimates

### Phase 1: Documentation (1-2 days)
- Executive Summary update: 0.5 days
- Root rules references: 1.5 days

### Phase 2: Schema (3-5 days)
- Production SQL: 2 days
- Seed data: 1 day
- Channel migration: 1-2 days

### Phase 3: Application (5-7 days)
- Project service: 2-3 days
- Registry integration: 1-2 days
- API endpoints: 2 days

### Phase 4: Testing (3-4 days)
- Unit tests: 2 days
- Integration tests: 1 day
- Migration tests: 1-2 days

**Total Estimated Timeline:** 12-18 days

---

## Next Actions

### Immediate (Completed — Phase 1)
1. **Executive Summary** - "Projects as Semantic Universes" section and design package links ✅
2. **Root Rules / Doctrine** - channels.md and project context ✅
3. **Cross-References** - Validated across PROJECTS.md, PROJECTS_API.md, doctrine, ACTOR_REGISTRATION_CHECKLIST, AGENT_REGISTRY, README ✅

### Next (Blocked Until Approval)
1. **Design package approval** - Required before Phase 2 schema work
2. **Schema implementation** - Production SQL, seed data, channel migration (Phase 2)
3. **Application implementation** - Project service, registry, API (Phase 3)
4. **Testing and validation** - Unit, integration, migration tests (Phase 4)

---

**Plan Status:** ✅ Complete and ready for execution  
**Next Review:** Upon completion of Phase 1 documentation updates  
**Implementation Guard:** No production changes until design package approved
