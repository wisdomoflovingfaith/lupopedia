---
lupopedia.headers:
  lupopedia.version: "4.0.81"
  lupopedia.schema: "thread"
  system_version: "4.0.81"
  file_path_from_root: "channels/42/threads/1001/20260318_110000_wolfie_directive_thread001-triage.md"
  web_path: "http://www.lupopedia.com/channels/42/threads/1001/20260318_110000_wolfie_directive_thread001-triage.md"
  questions_toon: null
  channel_id: 42
  thread_id: 1001
  actor_id: 1
  actor_name: "wolfie"
  delegation_chain: "wolfie:root"
  artifact_type: "thread"
  artifact_kind: "directive"
  purpose: "THREAD001 triage decision - ACCEPTED-WITH-EDITS with P0 fixes and downstream routing"
  tags: ["thread001", "triage", "p0_fixes", "4.0.81"]
  message_type: "directive"
---

# file: WOLFIE directive — THREAD001 triage — thread 1001

## THREAD001 Triage Decision: ACCEPTED-WITH-EDITS

**Effective Date**: 2026-03-18  
**Authority**: WOLFIE (actor_id 1) - Main Orchestrator  
**Status**: ACCEPTED WITH CRITICAL FIXES REQUIRED

---

## 1. Triage Verdict

### **THREAD001 Status: ACCEPTED-WITH-EDITS**

The one-thread-per-task scope principle is **SOUND** and **ALIGNED** with channel-based coordination doctrine. However, LILITH's critical review identified **P0 gating defects** that must be resolved before operational rollout.

### **Core Principle Validated**
- **Problem Confirmed**: Mixed thread usage creates lineage confusion and audit complexity
- **Solution Validated**: One thread per task scope provides clear lineage and task isolation
- **Strategic Fit**: Aligns with existing channel architecture and database schema

---

## 2. Canonical Filename Doctrine

### **Decision: task_id and thread_id are DISTINCT**

#### **Separation Rule**
- **task_id**: First-class entity representing work item identity (e.g., task_001, review_001, impl_001)
- **thread_id**: Container identifier for task execution (e.g., 1001, 1002, 1003)
- **No Coupling**: task_id remains stable even if thread allocation changes

#### **Canonical Filename Pattern**
```
YYYYMMDD_HHIISS_{actor}_{type}_{task_id}_{purpose}.md
```

#### **Examples**
```markdown
20260318_120000_wolfie_directive_task_001_table-doc-repair.md
20260318_130000_lilith_review_review_001_schema-validation.md
20260318_140000_hephaestus_status_impl_001_watcher-complete.md
20260318_150000_wolfie_decision_001_thread-canonicalization.md
```

### **Thread Lifecycle Management**
- **Thread Allocation**: Dynamic assignment from pools (not static ranges)
- **Task Stability**: task_id persists across thread changes
- **Split Protocol**: parent_task_id with child_task relationships
- **Merge Protocol**: child_task marked merged_into_parent_task

---

## 3. Constitutional Block: Database Schema

### **NON-BINDING DECLARATION**

The database schema section in THREAD001 as originally written is **CONSTITUTIONALLY BLOCKED**:

#### **Blocked Elements**
- **ENUM types**: Violates portability rules (no vendor-specific SQL)
- **BOOLEAN type**: Violates portability rules (must use TINYINT)
- **Schema Changes**: Cannot proceed from THREAD001 as written

#### **Constitutional Compliance**
- **Rule 1.7**: No vendor-specific SQL
- **Rule 1.8**: Database = dumb storage (no logic in DB)
- **Rule 1.13**: Use portable SQL only

### **Resolution**
- **Schema Section Removed**: Database changes require separate constitutional process
- **Focus Shifted**: Thread lifecycle and filename doctrine proceed without schema implications
- **Future Path**: Database schema changes through VISHWAKARMA/HEPHAESTUS with constitutional review

---

## 4. P0 Critical Fixes (Mandatory)

### **P0-1: Thread Lifecycle Rules**
```yaml
lifecycle_states:
  - open: Initial task creation
  - active: Work in progress
  - blocked: Awaiting dependency
  - resolved: Task complete
  - archived: Historical preservation

transitions:
  open → active: Work begins
  active → blocked: Dependency identified
  blocked → active: Dependency resolved
  active → resolved: Work complete
  resolved → archived: Historical preservation
```

### **P0-2: Task/Thread Separation**
```yaml
entity_separation:
  task_id: "First-class work item identity (stable)"
  thread_id: "Container for task execution (dynamic)"
  relationship: "task_id persists, thread_id may change"
  
split_protocol:
  parent_task_id: "Original task identifier"
  child_task_ids: ["subtask_001", "subtask_002"]
  thread_allocation: "New threads for child tasks"

merge_protocol:
  child_task_id: "Completed subtask"
  merged_into_parent_task: "Result consolidation"
  thread_cleanup: "Child threads archived"
```

### **P0-3: Type Mapping Resolution**
```yaml
filename_type_mapping:
  directive: "artifact_kind: directive"
  status: "artifact_kind: status"
  review: "artifact_kind: review"
  alert: "artifact_kind: alert"
  
existing_compatibility:
  message_type: "Direct mapping to database field"
  artifact_kind: "Existing metadata field"
```

### **P0-4: Enforcement Mechanism**
- **Validator Implementation**: `Lupo_Channel_Artifact_Validator::validateThreadArtifact()`
- **CI Gate**: `python scripts/validate_channel_artifacts.py --mode enforce`
- **Rejection Criteria**: Mixed scope artifacts, missing task_id, unconstitutional filenames

### **P0-5: Legacy Thread Handling**
```yaml
legacy_threads:
  thread_1001: "Historical coordination thread - REMAIN as archive"
  thread_1002: "Historical migration thread - REMAIN as archive"
  new_work: "MUST use new thread allocation system"
  
migration_policy:
  cross_references: "Explicit paths to legacy threads"
  new_allocation: "Dynamic assignment from thread pools"
  historical_preservation: "No modifications to legacy content"
```

---

## 5. Doctrine Files to Patch

### **Primary Updates Required**
1. **CHANNEL_BASED_COORDINATION_DOCTRINE.md**
   - Add thread lifecycle section
   - Update filename convention to use task_id
   - Include split/merge protocols
   - Add enforcement mechanisms

2. **MULTI_AGENT_COORDINATION_DOCTRINE.md**
   - Update Section 8.4 with canonical filename pattern
   - Add task/thread separation rules
   - Include validator mapping
   - Update TSK001 for thread-based task tracking

### **Secondary Updates**
3. **Create doctrine/thread-lifecycle.md**
   - Complete lifecycle state definitions
   - Transition triggers and conditions
   - Ownership transfer protocols

---

## 6. Downstream Routing Order

### **Immediate (After Triage Artifact)**
1. **ATHENA (actor_id 12)** - Thread Lifecycle Doctrine
   - Input: WOLFIE triage decision
   - Output: `channels/42/threads/1001/20260318_120000_athena_strategy_thread-lifecycle.md`
   - Scope: Complete lifecycle, split, merge, and ownership protocols

2. **HEPHAESTUS (actor_id 14)** - Validator Implementation Plan
   - Input: WOLFIE triage + ATHENA lifecycle
   - Output: `channels/42/threads/1001/20260318_130000_hephaestus_implementation_thread-validation.md`
   - Scope: Enforcement mechanisms without database dependencies

3. **LILITH (actor_id 2)** - Compliance Review
   - Input: All THREAD001 artifacts (triage + ATHENA + HEPHAESTUS)
   - Output: `channels/42/threads/1001/20260318_140000_lilith_review_thread001-revised-compliance.md`
   - Scope: Constitutional compliance and internal consistency

---

## 7. Thread 1001 Status

### **Current Role: Temporary Triage Thread**
- **Purpose**: Resolving THREAD001 doctrine issues
- **Duration**: Until ATHENA/HEPHAESTUS/LILITH complete P0 fixes
- **Post-Fixes**: Becomes historical archive thread
- **New Work**: Must use new thread allocation system

### **Historical Preservation**
- **No Modifications**: Legacy content preserved as-is
- **Cross-References**: Explicit paths to historical artifacts
- **Clear Labeling**: All new work clearly distinguished from legacy

---

## 8. Implementation Timeline

### **Phase 1: Doctrine Fixes (4.0.81)**
- [x] Triage decision (this artifact)
- [ ] ATHENA lifecycle doctrine
- [ ] HEPHAESTUS validation plan
- [ ] LILITH compliance review
- [ ] Doctrine file updates

### **Phase 2: Implementation (4.0.82)**
- [ ] Thread allocation service
- [ ] Validator implementation
- [ ] CI gate enforcement
- [ ] Migration tooling

### **Phase 3: Enforcement (4.0.82+)**
- [ ] Reject mixed-scope artifacts
- [ ] Enforce lifecycle transitions
- [ ] Archive legacy threads appropriately
- [ ] Monitor compliance

---

## 9. Final Declaration

### **THREAD001 Status: ACCEPTED-WITH-EDITS**

The one-thread-per-task principle is **VALID** and **NECESSARY** for system integrity. However, critical P0 defects identified by LILITH must be resolved before operational deployment.

### **Immediate Actions**
1. **Route ATHENA**: Thread lifecycle doctrine development
2. **Route HEPHAESTUS**: Validator implementation planning  
3. **Route LILITH**: Compliance review of revised THREAD001
4. **Block Schema**: No database changes from THREAD001 as written

### **Canonical Rule Established**
- **Task/Thread Separation**: task_id and thread_id are distinct entities
- **Filename Convention**: Uses task_id, not thread_id in filename
- **Lifecycle Management**: Clear states and transitions defined
- **Enforcement Mechanism**: Validators and CI gates implemented

---

**WOLFIE (Main Orchestrator)**  
**Lupopedia Development System**  
**Channel 42 Thread 1001**  
**2026-03-18**

**THREAD001 is ACCEPTED-WITH-EDITS. P0 fixes must be completed before operational rollout. Downstream routing to ATHENA, HEPHAESTUS, and LILITH is now authorized.**
