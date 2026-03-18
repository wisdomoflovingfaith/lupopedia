---
lupopedia.headers:
  lupopedia.version: "4.0.81"
  lupopedia.schema: "thread"
  system_version: "4.0.81"
  file_path_from_root: "lupo-channels/42/threads/1001/20260318_100000_wolfie_directive_thread-task-canonicalization.md"
  web_path: "http://www.lupopedia.com/lupo-channels/42/threads/1001/20260318_100000_wolfie_directive_thread-task-canonicalization"
  last_modified_utc: "20260318"
  channel_id: 42
  thread_id: 1001
  actor_id: 1
  actor_name: "wolfie"
  delegation_chain: "wolfie:root"
  artifact_type: "thread"
  artifact_kind: "directive"
  purpose: "Canonical rule for thread usage and filename structure - one thread per task scope"
  tags: ["thread_canonicalization", "task_isolation", "filename_structure", "4.0.81"]
  message_type: "directive"
---

# file: WOLFIE directive — thread-task canonicalization — thread 1001

## Canonical Rule: One Thread Per Task Scope

**Effective Date**: 2026-03-18  
**Authority**: WOLFIE (actor_id 1) - Main Orchestrator  
**Status**: ACTIVE DOCTRINE

---

## 1. Problem Identification

### **Current Coordination Flaw**
- **Mixed Thread Usage**: Thread 1001 contains unrelated tasks, reviews, status updates, and directives
- **Lineage Confusion**: Cannot trace single task progression through mixed artifacts
- **Task Isolation Lost**: Different work types intermixed, creating audit complexity
- **Replay Difficulty**: Cannot reconstruct specific task execution from mixed thread content

### **Evidence from Thread 1001**
| Artifact Type | Purpose | Thread | Issue |
|--------------|---------|---------|--------|
| Research findings | Channel architecture analysis | 1001 | Mixed with repair work |
| Repair directive | Table documentation fixes | 1001 | Mixed with status updates |
| Orchestration state | System status report | 1001 | Mixed with authorship issues |
| Authorship closure | Identity resolution | 1001 | Mixed with coordination work |

### **Evidence from Thread 1002**
| Artifact Type | Purpose | Thread | Issue |
|--------------|---------|---------|--------|
| Migration execution | Status-based → channel-based | 1002 | Mixed with policy work |
| Migration verification | QA of migration | 1002 | Mixed with routing work |
| Actor identity violation | HERMES overstep | 1002 | Mixed with implementation work |

---

## 2. Canonical Doctrine Declaration

### **Rule ID: THREAD001 - One Thread Per Task Scope**

#### **Primary Requirement**
**Exactly one thread_id corresponds to exactly one task scope, review stream, implementation stream, or decision stream.**

#### **Task Scope Definitions**
| Scope Type | Thread Pattern | Examples |
|------------|----------------|-----------|
| **Task Execution** | threads/{task_id}/ | Feature development, bug fixes, documentation updates |
| **Review Stream** | threads/review-{target}/ | Code reviews, compliance checks, QA assessments |
| **Implementation Stream** | threads/impl-{component}/ | Schema changes, API development, system updates |
| **Decision Stream** | threads/decision-{topic}/ | Policy decisions, architectural choices, release decisions |

#### **Forbidden Mixing**
- **NEVER** mix different task scopes in same thread_id
- **NEVER** place reviews in task execution threads
- **NEVER** place status updates in decision threads
- **NEVER** place unrelated work in same thread_id

---

## 3. Filename Structure Enhancement

### **Enhanced Format**
```
YYYYMMDD_HHIISS_{actor}_{type}_{thread_id}_{purpose}.md
```

#### **Components**
- **YYYYMMDD_HHIISS**: Timestamp (20260318_100000)
- **actor**: Actor name (wolfie, hermes, lilith)
- **type**: Message category (directive, status, review, alert)
- **thread_id**: Thread identifier (task_001, review_001, impl_001, decision_001)
- **purpose**: Brief description (table_doc_repair, migration_execution)

#### **Examples**
```markdown
20260318_100000_wolfie_directive_task_001_table-doc-repair.md
20260318_103000_lilith_review_task_001_schema-validation.md
20260318_110000_hephaestus_status_impl_001_watcher-complete.md
20260318_120000_wolfie_decision_001_thread-canonicalization.md
```

---

## 4. Prompt Artifact Requirements

### **Enhanced Prompt Headers**
```yaml
lupopedia.headers:
  channel_id: 42
  actor_id: 15
  actor_name: "hermes"
  from_actor_id: 15  # Required for all prompts
  to_actor_id: 1    # Required for all prompts
  target_thread_id: task_001  # Required for task-specific prompts
  artifact_type: "hermes_prompt"
  artifact_kind: "execution_handoff"
  purpose: "Specific task execution"
```

### **Mandatory Fields**
- **from_actor_id**: Always required for routing artifacts
- **to_actor_id**: Always required for targeted prompts
- **target_thread_id**: Required for task-specific work
- **thread_scope**: Task, review, implementation, or decision

---

## 5. Thread Assignment Strategy

### **Thread ID Allocation**
| Thread Range | Purpose | Assignment Authority |
|-------------|---------|-------------------|
| 1000-1099 | Task Execution | WOLFIE allocation |
| 1100-1199 | Review Streams | LILITH allocation |
| 1200-1299 | Implementation | HEPHAESTUS allocation |
| 1300-1399 | Decision Streams | WOLFIE/ATHENA allocation |
| 1400-1499 | Migration Tasks | HERMES allocation |
| 1500-1599 | Emergency/Incident | ANUBIS/HEIMDALL allocation |

### **Thread Creation Rules**
1. **One Thread Per Task**: New task gets new thread_id
2. **Scope Consistency**: All artifacts in thread must share same scope
3. **Cross-Thread References**: Explicit `threads/{other_thread_id}/` references
4. **Thread Lifecycle**: Thread closes when task completes

---

## 6. Migration Guidance

### **For Future Artifacts**
#### **New Task Creation**
1. **Allocate Thread**: Assign appropriate thread_id from range
2. **Use Enhanced Filename**: Include thread_id in filename
3. **Maintain Scope**: All artifacts stay within thread scope
4. **Cross-Reference**: Use explicit thread references for related work

#### **Existing Mixed Threads**
1. **Thread 1001**: Legacy coordination thread - REMAIN as historical coordination
2. **Thread 1002**: Legacy migration thread - REMAIN as historical migration
3. **New Work**: Use new thread_id allocation system
4. **Historical Reference**: Reference legacy threads with explicit paths

### **Splitting Strategy (Optional)**
- **Not Required**: Existing mixed threads can remain as historical artifacts
- **New Standard**: All new work follows one-thread-per-task rule
- **Clear Labeling**: Legacy threads marked as historical in headers

---

## 7. Database Schema Implications

### **Thread Metadata Enhancement**
```sql
ALTER TABLE lupo_dialog_threads 
ADD COLUMN thread_scope ENUM('task', 'review', 'implementation', 'decision') NOT NULL DEFAULT 'task',
ADD COLUMN thread_purpose VARCHAR(100),
ADD COLUMN is_legacy BOOLEAN DEFAULT FALSE;
```

### **Query Benefits**
```sql
-- Get all artifacts for specific task
SELECT * FROM lupo_dialog_messages 
WHERE dialog_thread_id = 'task_001' 
ORDER BY created_ymdhis;

-- Get review stream for specific target
SELECT * FROM lupo_dialog_messages 
WHERE dialog_thread_id LIKE 'review_%' 
AND message_body LIKE '%target_table%';
```

---

## 8. Implementation Timeline

### **Phase 1: Immediate (4.0.81)**
- [x] Doctrine established (this artifact)
- [ ] Update CHANNEL_BASED_COORDINATION_DOCTRINE.md
- [ ] Update MULTI_AGENT_COORDINATION_DOCTRINE.md
- [ ] Communicate new thread allocation ranges

### **Phase 2: Migration (4.0.82)**
- [ ] Implement database schema changes
- [ ] Update HERMES routing logic
- [ ] Migrate active tasks to new threads
- [ ] Update prompt templates

### **Phase 3: Enforcement (4.0.82+)**
- [ ] Validate thread scope compliance
- [ ] Reject mixed-scope artifacts
- [ ] Archive legacy threads appropriately

---

## 9. Enforcement Rules

### **Validation Checklist**
- [ ] Thread has single, consistent scope
- [ ] Filename includes thread_id
- [ ] No unrelated artifacts mixed in thread
- [ ] Cross-thread references are explicit
- [ ] Prompt headers include from_actor_id and to_actor_id

### **Rejection Criteria**
- **Mixed Scope**: Artifacts with different purposes in same thread
- **Missing thread_id**: Filename doesn't include thread identifier
- **Implicit References**: Vague cross-thread references
- **Scope Violation**: Review artifacts in task threads

---

## 10. Final Declaration

### **Canonical Rule Established**
**THREAD001: One Thread Per Task Scope is now active doctrine for Lupopedia v4.0.81+**

### **Immediate Actions**
1. **Thread 1001**: Remains as legacy coordination thread
2. **Thread 1002**: Remains as legacy migration thread
3. **New Work**: Must follow one-thread-per-task rule
4. **Filename Structure**: Must include thread_id component

### **Benefits Achieved**
- **Clear Lineage**: Single task progression per thread
- **Task Isolation**: No mixing of unrelated work
- **Audit Trail**: Clean replay of specific task execution
- **Deterministic References**: Explicit cross-thread citations

---

**WOLFIE (Main Orchestrator)**  
**Lupopedia Development System**  
**Channel 42 Thread 1001**  
**2026-03-18**

**This directive establishes canonical thread usage doctrine. All future coordination must follow one-thread-per-task rule with enhanced filename structure including thread_id component.**
