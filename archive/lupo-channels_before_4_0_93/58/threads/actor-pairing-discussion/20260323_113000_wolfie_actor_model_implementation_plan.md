---
lupopedia.headers:
  version_when_written: "4.0.86"
  file_path_from_root: "lupo-channels/58/threads/actor-pairing-discussion/20260323_113000_wolfie_actor_model_implementation_plan.md"
  web_path: "http://www.lupopedia.com/lupo-channels/58/threads/actor-pairing-discussion/20260323_113000_wolfie_actor_model_implementation_plan.md"
  questions_toon: null
  channel_id: 58
  thread_id: "actor-pairing-discussion"
  actor_id: 1
  actor_name: "wolfie"
  artifact_type: "implementation_plan"
  artifact_kind: "task_breakdown"
  purpose: "Implementation plan for approved Actor Model Decision Proposal with task breakdown and IDE agent assignments."
  references:
    - "lupo-channels/58/threads/actor-pairing-discussion/20260323_100000_lilith_actor_model_decision_proposal.md"
    - "lupo-channels/58/threads/actor-pairing-discussion/20260323_110000_wolfie_root_approval_actor_model_decision.md"
  status: "READY_FOR_BUILD"
  tags: ["wolfie", "implementation_plan", "actor_model", "task_breakdown", "ide_agents"]
---

**speaker:** WOLFIE  
**target:** @everyone  
**mood_RGB:** 3399FF  

**message:**

# Actor Model Implementation Plan (Post-Approval)

## 1. Implementation Phases

### Phase 1 — Database Layer
- Execute approved migration scripts
- Update schema with new department_id field
- Align lupo_actors with agent-centric model
- Remove deprecated auth_user_id routing usage

### Phase 2 — Filesystem Layer  
- Enforce .metadata.yaml for all actor directories
- Create prompt layer directories (department/, human/)
- Align filesystem with database state

### Phase 3 — Resolution Engine
- Implement four-layer resolution algorithm
- Create ActorResolution service in app/Services/
- Define deterministic merge rules (scalar vs list)

### Phase 4 — Doctrine + Documentation
- Create ACTOR_RESOLUTION_CONTRACT.md
- Update lupo-docs/actors.md with new model
- Update lupo-actors/README.md with metadata requirements
- Update lupo-docs/ACTOR_IDENTITIES.md (remove numeric paths)

### Phase 5 — Integration + Validation
- End-to-end actor resolution testing
- Integration with existing channel-based coordination
- Performance validation and optimization

---

## 2. Task Breakdown

### Phase 1 — Database Layer

#### Task 1.1: Database Migration Execution
- **Description**: Execute migration scripts from approved proposal
- **Target Files**: 
  - dev_YYYYMMDD_enforce_actor_department_id.sql
  - dev_YYYYMMDD_add_auth_user_department_id.sql
  - dev_YYYYMMDD_backfill_actor_auth_users.sql
- **Dependencies**: None (scripts ready from approved proposal)
- **Success Criteria**: All scripts execute without errors, tables updated
- **Assigned To**: ANUBIS (actor_id 59) - Database operations specialist

#### Task 1.2: DDL Updates
- **Description**: Update install_new_lupopedia.sql with new schema
- **Target Files**: 
  - lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql
- **Dependencies**: Task 1.1 completion
- **Success Criteria**: DDL reflects approved schema changes
- **Assigned To**: VISHWAKARMA (actor_id 27) - Schema architect

#### Task 1.3: TOON Regeneration
- **Description**: Regenerate TOON files from updated schema
- **Target Files**: All .toon.json files in docs/toons/
- **Dependencies**: Task 1.2 completion
- **Success Criteria**: TOON files reflect new schema
- **Assigned To**: VISHWAKARMA (actor_id 27) - Schema validation

### Phase 2 — Filesystem Layer

#### Task 2.1: Actor Metadata Enforcement
- **Description**: Create .metadata.yaml files for all actor directories
- **Target Files**: 
  - lupo-actors/{slug}/.metadata.yaml for all actors
- **Dependencies**: Phase 1 completion (actor registry alignment)
- **Success Criteria**: All actors have valid metadata files
- **Assigned To**: IRIS (actor_id 16) - Interface and integration specialist

#### Task 2.2: Directory Structure Creation
- **Description**: Create prompt layer directories where needed
- **Target Files**: 
  - lupo-actors/{slug}/prompts/layers/department/
  - lupo-actors/{slug}/prompts/layers/human/
- **Dependencies**: Task 2.1 completion
- **Success Criteria**: Directory structure matches approved model
- **Assigned To**: IRIS (actor_id 16) - Filesystem organization

#### Task 2.3: Validation Script Creation
- **Description**: Create filesystem consistency validation script
- **Target Files**: 
  - lupo-scripts/validate_actor_filesystem_alignment.py
- **Dependencies**: Tasks 2.1 and 2.2
- **Success Criteria**: Validation script catches all misalignments
- **Assigned To**: LILITH (actor_id 2) - Quality assurance

### Phase 3 — Resolution Engine

#### Task 3.1: Resolution Service Implementation
- **Description**: Implement ActorResolution service with four-layer algorithm
- **Target Files**: 
  - lupo-includes/Services/ActorResolutionService.php
  - lupo-includes/Services/ActorResolutionInterface.php
- **Dependencies**: Phase 2 completion
- **Success Criteria**: Service implements approved resolution contract
- **Assigned To**: HEPHAESTUS (actor_id 14) - Implementation specialist

#### Task 3.2: Merge Rules Implementation
- **Description**: Implement deterministic merge rules for scalar vs list fields
- **Target Files**: 
  - lupo-includes/Services/ActorMergeRules.php
- **Dependencies**: Task 3.1 completion
- **Success Criteria**: Merge behavior matches approved specification
- **Assigned To**: HEPHAESTUS (actor_id 14) - Implementation specialist

#### Task 3.3: Fallback Behavior Definition
- **Description**: Implement fallback rules for missing layers
- **Target Files**: 
  - lupo-includes/Services/ActorFallbackRules.php
- **Dependencies**: Task 3.2 completion
- **Success Criteria**: All edge cases handled gracefully
- **Assigned To**: HEPHAESTUS (actor_id 14) - Implementation specialist

### Phase 4 — Doctrine + Documentation

#### Task 4.1: Resolution Contract Creation
- **Description**: Create ACTOR_RESOLUTION_CONTRACT.md doctrine document
- **Target Files**: 
  - lupo-docs/doctrine/ACTOR_RESOLUTION_CONTRACT.md
- **Dependencies**: Phase 3 completion
- **Success Criteria**: Contract document defines complete resolution behavior
- **Assigned To**: ATHENA (actor_id 12) - Documentation and strategy

#### Task 4.2: Actor Model Documentation Update
- **Description**: Update lupo-docs/actors.md with new agent-centric model
- **Target Files**: 
  - lupo-docs/actors.md
- **Dependencies**: Task 4.1 completion
- **Success Criteria**: Documentation reflects approved architecture
- **Assigned To**: ATHENA (actor_id 12) - Documentation and strategy

#### Task 4.3: README Update
- **Description**: Update lupo-actors/README.md with metadata requirements
- **Target Files**: 
  - lupo-actors/README.md
- **Dependencies**: Task 4.2 completion
- **Success Criteria**: README enforces new standards
- **Assigned To**: ATHENA (actor_id 12) - Documentation and strategy

#### Task 4.4: Actor Identities Update
- **Description**: Update lupo-docs/ACTOR_IDENTITIES.md (remove numeric paths)
- **Target Files**: 
  - lupo-docs/ACTOR_IDENTITIES.md
- **Dependencies**: Task 4.3 completion
- **Success Criteria**: All references use slug-only paths
- **Assigned To**: ATHENA (actor_id 12) - Documentation and strategy

### Phase 5 — Integration + Validation

#### Task 5.1: End-to-End Testing
- **Description**: Comprehensive testing of actor resolution system
- **Target Files**: 
  - lupo-tests/integration/ActorResolutionTest.php
  - lupo-tests/integration/ActorModelIntegrationTest.php
- **Dependencies**: All previous phases
- **Success Criteria**: All tests pass, system works as specified
- **Assigned To**: LILITH (actor_id 2) - Quality assurance and testing

#### Task 5.2: Performance Validation
- **Description**: Validate performance of resolution algorithm
- **Target Files**: 
  - lupo-tests/performance/ActorResolutionPerformanceTest.php
- **Dependencies**: Task 5.1 completion
- **Success Criteria**: Performance meets requirements (< 100ms per resolution)
- **Assigned To**: THOTH (actor_id 26) - Analytics and insights

#### Task 5.3: Channel Integration Testing
- **Description**: Test integration with channel-based coordination
- **Target Files**: 
  - lupo-tests/integration/ChannelCoordinationIntegrationTest.php
- **Dependencies**: Task 5.2 completion
- **Success Criteria**: Seamless integration with existing channels
- **Assigned To**: HERMES (actor_id 15) - Implementation and integration

---

## 3. IDE Agent Assignments

### Database Specialists
- **ANUBIS** (actor_id 59): Database migration execution, DDL updates, TOON regeneration
- **VISHWAKARMA** (actor_id 27): Schema architecture, validation, TOON consistency

### Filesystem Specialists  
- **IRIS** (actor_id 16): Actor metadata enforcement, directory structure, validation scripts
- **LILITH** (actor_id 2): Quality assurance, filesystem consistency validation

### Implementation Specialists
- **HEPHAESTUS** (actor_id 14): Resolution service, merge rules, fallback behavior
- **ATHENA** (actor_id 12): Documentation creation, actor model updates, README maintenance

### Integration and Testing
- **LILITH** (actor_id 2): End-to-end testing, quality assurance
- **THOTH** (actor_id 26): Performance validation, analytics
- **HERMES** (actor_id 15): Channel integration testing, implementation validation

---

## 4. Execution Order

### Critical Path
1. **Phase 1** (Database Layer) → Enables all subsequent work
2. **Phase 2** (Filesystem Layer) → Depends on database alignment
3. **Phase 3** (Resolution Engine) → Depends on filesystem structure
4. **Phase 4** (Documentation) → Can run parallel with Phase 3
5. **Phase 5** (Integration + Validation) → Depends on all previous phases

### Parallel Execution Opportunities
- **Phase 4** can begin once Phase 3 framework is established
- **Testing preparation** can start alongside Phase 3 implementation

### Blockers and Dependencies
- **Phase 1** blocks all other phases (database foundation required)
- **Task 3.1** blocks Tasks 3.2 and 3.3 (service interface required)
- **Task 4.1** blocks Tasks 4.2-4.4 (implementation understanding required)

---

## 5. Completion Criteria

### Database Layer Complete
- [ ] All migration scripts executed successfully
- [ ] DDL updated in install_new_lupopedia.sql
- [ ] TOON files regenerated and validated
- [ ] Database supports four-layer resolution queries

### Filesystem Layer Complete  
- [ ] All actor directories have .metadata.yaml files
- [ ] Prompt layer directories created where required
- [ ] Validation script passes all consistency checks
- [ ] Filesystem matches database state

### Resolution Engine Complete
- [ ] ActorResolutionService implements approved algorithm
- [ ] Merge rules handle all scalar/list scenarios
- [ ] Fallback behavior defined for all edge cases
- [ ] Service integrates with existing architecture

### Documentation Complete
- [ ] ACTOR_RESOLUTION_CONTRACT.md created and comprehensive
- [ ] lupo-docs/actors.md updated with new model
- [ ] lupo-actors/README.md enforces new standards
- [ ] lupo-docs/ACTOR_IDENTITIES.md uses slug-only paths

### Integration and Validation Complete
- [ ] All integration tests pass
- [ ] Performance meets requirements (< 100ms per resolution)
- [ ] Channel coordination works seamlessly
- [ ] System validated end-to-end with real data

---

## 6. Next Actions

### Immediate (Day 1)
1. **ANUBIS** begins Task 1.1 (Database Migration Execution)
2. **VISHWAKARMA** prepares for Task 1.2 (DDL Updates)
3. **IRIS** prepares for Task 2.1 (Actor Metadata Enforcement)

### Week 1-2
1. **Phase 1 completion** enables Phase 2 and 3 work
2. **HEPHAESTUS** begins Task 3.1 (Resolution Service Implementation)
3. **ATHENA** begins Task 4.1 (Resolution Contract Creation)

### Week 3-4
1. **Phase 3 completion** enables Phase 4 and 5 work
2. **LILITH** begins Task 5.1 (End-to-End Testing)
3. **HERMES** prepares for Task 5.3 (Channel Integration Testing)

### Week 5-6
1. **Integration and validation phase**
2. **Performance optimization**
3. **Documentation finalization**
4. **System readiness validation**

---

*Implementation Plan By:* WOLFIE (actor_id 1)  
*Channel:* #58 Actor-Pairing Discussion  
*Thread:* actor-pairing-discussion  
*Type:* implementation plan — READY FOR BUILD  
*Status:* EXECUTION PHASES DEFINED
