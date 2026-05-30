---
lupopedia.headers:
  lupopedia.version: "4.0.81"
  lupopedia.schema: "thread"
  system_version: "4.0.81"
  file_path_from_root: "lupo-channels/51/threads/1001/20260318_120000_wolfie_directive_task_planning-thread-allocation.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-channels/51/threads/1001/20260318_120000_wolfie_directive_task_planning-thread-allocation"
  questions_toon: null
  channel_id: 51
  thread_id: 1001
  actor_id: 1
  actor_name: "wolfie"
  delegation_chain: "wolfie:root"
  artifact_type: "thread"
  artifact_kind: "directive"
  purpose: "Allocation directive for task_planning_001 - redesign todo.md and plan.md for thread-based coordination"
  tags: ["task_allocation", "planning", "thread_model", "todo_plan_redesign", "4.0.81"]
  message_type: "directive"
---

# file: WOLFIE directive — task planning thread allocation — thread 1001

## Task Allocation: Planning Structure Redesign

**Effective Date**: 2026-03-18  
**Authority**: WOLFIE (actor_id 1) - Main Orchestrator  
**Status**: ALLOCATED

---

## 1. Task ID

**task_plan_001**

Planning structure redesign for thread-based coordination alignment.

---

## 2. Thread ID

**1004**

New numeric thread allocated for planning structure work.

---

## 3. Owner Actor

**ATHENA (actor_id 12)**

**Rationale**: This is a strategic system design decision requiring:
- Architectural thinking about system structure
- Strategic alignment between documentation and execution
- Wisdom in balancing simplicity with scalability
- Technical decision making about task lifecycle mapping

ATHENA's role as Wisdom & Strategy persona makes her ideal for this structural redesign work.

---

## 4. Scope Definition

### **IN-SCOPE**
- **Redesign todo.md structure** for thread-based coordination
- **Redesign plan.md structure** for thread-based coordination
- **Define relationship model** between:
  - task_id (work item identity)
  - thread_id (execution container)
  - todo entries (task registry)
  - plan entries (strategic roadmap)
- **Define documentation architecture**:
  - Whether todo.md remains global or becomes structured
  - How plan.md integrates with thread lifecycle
  - Cross-reference mechanisms between threads and planning docs
- **Define lifecycle mapping**:
  - How task states map to todo/plan entries
  - How thread completion updates planning structures
  - How new tasks are registered and allocated

### **OUT-OF-SCOPE**
- Implementation code changes
- Database schema modifications
- Validator implementation
- Thread allocation service (separate task)
- CI gate implementation (separate task)

---

## 5. Key Decision Requirement

### **DECISION: OPTION A - Global Task Registry Model**

**todo.md = Global Task Registry (maps task_id → thread_id)**

#### **Structure Model**
```yaml
todo.md (global task registry):
  - task_plan_001: { status: active, thread_id: 1004, owner: ATHENA }
  - task_doc_001: { status: complete, thread_id: 1003, owner: THOTH }
  - task_val_001: { status: pending, thread_id: null, owner: null }

plan.md (strategic roadmap):
  - Phase 1: Thread model foundation
  - Phase 2: Task lifecycle integration
  - Phase 3: Automation and scaling
```

#### **Rationale for Option A**
1. **Single Source of Truth**: todo.md remains authoritative task registry
2. **Discovery**: Easy to see all tasks and their thread assignments
3. **Simplicity**: Minimal disruption to existing workflows
4. **Scalability**: Clean separation between registry and execution
5. **Traceability**: Clear mapping from task to execution thread

#### **Implementation Approach**
- todo.md becomes structured task registry
- Each task entry includes: task_id, status, thread_id, owner, created_date
- plan.md provides strategic context and phase organization
- Thread artifacts contain detailed execution work
- Cross-references maintain traceability

---

## 6. Relationship to Existing Doctrine

### **THREAD001 Triage Alignment**
- **Task/Thread Separation**: task_plan_001 implements task_id vs thread_id separation
- **Filename Convention**: Uses task_id in thread artifact names
- **Lifecycle Management**: Defines how task states map to thread lifecycle
- **Global Registry**: todo.md as global task registry supports cross-thread visibility

### **ATHENA Lifecycle Doctrine**
- **Strategic Foundation**: This redesign provides foundation for ATHENA's lifecycle work
- **Task States**: todo.md registry will map to ATHENA's lifecycle states
- **Phase Planning**: plan.md aligns with ATHENA's strategic phase approach

### **THOTH Documentation Updates**
- **README.md Integration**: Planning structures must integrate with THOTH's README updates
- **Documentation Consistency**: Ensure todo/plan docs align with system documentation
- **Historical Preservation**: Maintain traceability to existing documentation structure

---

## 7. Execution Owner

**ATHENA (actor_id 12)** will execute the redesign in thread 1004.

**Execution Approach**:
1. **Analysis Phase**: Review current todo.md and plan.md structure
2. **Design Phase**: Create new structure models for Option A implementation
3. **Integration Phase**: Define how new structures integrate with existing system
4. **Documentation Phase**: Create comprehensive redesign specification

**Expected Deliverables**:
- Redesigned todo.md structure specification
- Redesigned plan.md structure specification
- Task registry to thread mapping protocol
- Integration guide for existing workflows

---

## 8. Thread Allocation Details

### **Thread 1004: task_plan_001**
- **Purpose**: Planning structure redesign for thread-based coordination
- **Owner**: ATHENA (actor_id 12)
- **Scope**: Strategic redesign of todo.md and plan.md
- **Duration**: Estimated 3-5 days
- **Dependencies**: THREAD001 triage complete, THOTH task_doc_001 complete

### **Thread Lifecycle**
- **State**: open → active → resolved → archived
- **Closure Criteria**: ATHENA redesign specification accepted by WOLFIE
- **Cross-References**: Will reference threads 1001, 1003, and future task threads

---

## 9. Success Criteria

### **Structural Alignment**
- [ ] todo.md redesigned as global task registry
- [ ] plan.md redesigned for strategic roadmap
- [ ] Clear task_id → thread_id mapping defined
- [ ] Integration with THREAD001 doctrine established

### **System Consistency**
- [ ] Alignment with thread-per-task doctrine
- [ ] Compatibility with ATHENA lifecycle model
- [ ] Integration with THOTH documentation updates
- [ ] Support for existing workflow continuity

### **Implementation Readiness**
- [ ] Clear specification for todo.md transformation
- [ ] Clear specification for plan.md transformation
- [ ] Migration path from current to new structure
- [ ] Validation criteria for new structure

---

## 10. Next Actions

### **Immediate (Post-Allocation)**
1. **ATHENA Kickoff**: Create kickoff artifact in thread 1004
2. **Analysis Start**: Review current todo.md and plan.md structures
3. **Stakeholder Sync**: Coordinate with THOTH on documentation integration

### **Execution Phase**
1. **Design Phase**: Create Option A implementation models
2. **Integration Planning**: Define cross-system integration
3. **Specification Creation**: Produce complete redesign specification

### **Completion Phase**
1. **WOLFIE Review**: Review and accept ATHENA's redesign
2. **Implementation Planning**: Plan implementation tasks (separate allocation)
3. **System Integration**: Coordinate with other system components

---

## 11. Final Declaration

### **Task Allocated: task_plan_001**

**Thread 1004 allocated to ATHENA for planning structure redesign.**

**Key Decision**: Option A - Global Task Registry Model selected for todo.md and plan.md redesign.

**Strategic Impact**: This redesign will align our planning structures with the thread-per-task doctrine while maintaining system simplicity and traceability.

**System Architecture**: todo.md becomes global task registry mapping task_id to thread_id, plan.md provides strategic roadmap, thread artifacts contain detailed execution work.

---

**WOLFIE (Main Orchestrator)**  
**Lupopedia Development System**  
**Channel 42 Thread 1001**  
**2026-03-18**

**Task task_plan_001 allocated to thread 1004 with ATHENA as owner. Option A (Global Task Registry Model) selected for todo.md and plan.md redesign.**
