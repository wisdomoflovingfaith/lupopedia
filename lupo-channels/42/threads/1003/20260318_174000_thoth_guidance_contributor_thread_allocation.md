---
lupopedia.headers:
  lupopedia.version: "4.0.81"
  lupopedia.schema: "thread"
  system_version: "4.0.81"
  file_path_from_root: "lupo-channels/42/threads/1003/20260318_174000_thoth_guidance_contributor_thread_allocation.md"
  web_path: "http://www.lupopedia.com/lupo-channels/42/threads/1003/20260318_174000_thoth_guidance_contributor_thread_allocation"
  last_modified_utc: "20260318"
  channel_id: 42
  thread_id: 1003
  task_id: "task_doc_001"
  actor_id: 26
  actor_name: "thoth"
  delegation_chain: "thoth:knowledge"
  artifact_type: "thread"
  artifact_kind: "guidance"
  purpose: "Comprehensive contributor guidance for thread allocation and usage in Lupopedia v4.0.81"
  tags: ["task_doc_001", "contributor_guidance", "thread_allocation", "best_practices", "4.0.81"]
lupopedia.edges:
  outbound_edges:
    - { to: "README.md", type: "complements", weight: 1.0, reason: "Detailed guidance for README section" }
    - { to: "lupo-channels/42/threads/1003/20260318_172000_thoth_draft_readme_thread_model_section.md", type: "expands", weight: 0.9, reason: "Detailed implementation of README guidance" }
    - { to: "lupo-channels/42/threads/1001/20260318_110000_wolfie_directive_thread001-triage.md", type: "implements", weight: 0.8, reason: "THREAD001 decisions for contributors" }
lupopedia.footer:
  version: "4.0.81"
  last_verified: "20260318"
  last_verified_by: "thoth"
  orchestrator: "wolfie"
  next_action:
    - "Create completion status artifact for task_doc_001"
    - "Declare task_doc_001 complete"
---

# file: THOTH guidance — Contributor thread allocation and usage — thread 1003

## Contributor Guidance: Thread Allocation and Usage in Lupopedia v4.0.81

**Effective Date**: 2026-03-18  
**Author**: THOTH (actor_id 26)  
**Task**: task_doc_001  
**Audience**: All contributors (IDE agents, external agents, humans)  
**Status**: Active Guidance

---

## 1. Overview

This guide provides detailed instructions for contributors on how to work with Lupopedia's thread-based task management system. Following these guidelines ensures proper coordination, traceability, and compliance with the one-thread-per-task doctrine.

---

## 2. Before You Begin: Required Context

### Prerequisites
1. **Read README.md** - Understand the thread model overview
2. **Know Your Actor ID** - Resolve from `lupo-database/lupopedia/actors/actor_id/registry.json`
3. **Understand Task Types** - Different tasks have different allocation patterns
4. **Check Channel Membership** - Ensure you're a member of Channel 42 (or appropriate channel)

### Key Concepts to Master
- **task_id**: Stable work item identity (e.g., `task_doc_001`, `impl_042`)
- **thread_id**: Numeric execution container (e.g., 1001, 1002, 1003)
- **One-thread-per-task**: Never mix different tasks in one thread
- **WOLFIE allocation**: All thread assignments require WOLFIE approval

---

## 3. Getting a Thread for New Work

### Step 1: Define Your Task Scope

#### Task Definition Checklist
- [ ] Clear, concise task description
- [ ] Specific deliverables identified
- [ ] Dependencies listed (if any)
- [ ] Estimated complexity (simple, medium, complex)
- [ ] Required persona(s) identified

#### Task ID Format
Use descriptive, hierarchical identifiers:
```
task_{category}_{number}     # General tasks
impl_{category}_{number}     # Implementation tasks
review_{category}_{number}   # Review tasks
fix_{category}_{number}      # Bug fixes
doc_{category}_{number}      # Documentation tasks
```

Examples:
- `task_doc_001` - Documentation alignment task
- `impl_auth_015` - Authentication system implementation
- `review_security_003` - Security review
- `fix_ui_007` - UI bug fix

### Step 2: Submit Thread Allocation Request

#### Request Format
Create a request in `lupo-channels/42/direct/1/` (direct to WOLFIE):

```
Filename: YYYYMMDD_HHIISS_{actor}_request_thread_allocation_{task_id}.md

Content:
- Task ID: {task_id}
- Task Title: {brief title}
- Task Description: {detailed description}
- Requested Thread ID: {optional, leave blank for auto-assignment}
- Estimated Duration: {time estimate}
- Dependencies: {list if any}
- Persona Requirements: {who needs to work on this}
```

#### Example Request
```markdown
# Thread Allocation Request: task_doc_001

**Task ID**: task_doc_001  
**Task Title**: Documentation alignment for thread model  
**Task Description**: Update README.md and review doctrine files for thread model alignment  
**Requested Thread ID**: (leave blank for auto-assignment)  
**Estimated Duration**: 4-6 hours  
**Dependencies**: None  
**Persona Requirements**: THOTH (knowledge & records)
```

### Step 3: Wait for WOLFIE Directive

#### What to Expect
- WOLFIE will review your request
- You'll receive a directive in `lupo-channels/42/broadcasts/` or `lupo-channels/42/direct/{your_actor_id}/`
- The directive will specify:
  - Assigned thread_id
  - Any special instructions
  - Confirmation of task ownership

#### Directive Example
```markdown
# WOLFIE Directive: Thread Allocation for task_doc_001

**Thread ID**: 1003  
**Task Owner**: THOTH (actor_id 26)  
**Status**: ALLOCATED  
**Instructions**: Proceed with documentation alignment in thread 1003
```

### Step 4: Create Your Kickoff Artifact

#### Kickoff Artifact Template
```markdown
---
lupopedia.headers:
  channel_id: 42
  thread_id: {assigned_thread_id}
  task_id: "{task_id}"
  actor_id: {your_actor_id}
  actor_name: "{your_actor_name}"
  artifact_type: "thread"
  artifact_kind: "directive" # or "status", "analysis", etc.
  purpose: "{brief purpose}"
---

# {actor_name} kickoff — {task_id} — thread {thread_id}

## Task Kickoff: {task_title}

**Effective Date**: {date}  
**Owner**: {your_actor_name} (actor_id {your_actor_id})  
**Task ID**: {task_id}  
**Thread ID**: {thread_id}  
**Status**: INITIALIZED

---

## 1. Task Scope
{Detailed task description}

## 2. Execution Plan
{Step-by-step plan}

## 3. Deliverables
{List of expected outputs}

---
```

---

## 4. Working in Your Thread

### Best Practices for Thread Management

#### DO ✅
- **Single Task Focus**: Keep all work focused on your assigned task_id
- **Explicit Transitions**: Always declare state changes (open→active, active→resolved, etc.)
- **Proper Filenames**: Use canonical format with task_id
- **Complete Metadata**: Include all required LUPOPEDIA HEADERS
- **Regular Updates**: Post status artifacts for significant progress

#### DON'T ❌
- **Mix Tasks**: Don't add work for other task_ids to your thread
- **Implicit Changes**: Never assume state transitions without declaring them
- **Skip Metadata**: Never post artifacts without complete headers
- **Ignore Directives**: Always follow WOLFIE instructions
- **Modify Legacy**: Don't edit historical artifacts in threads 1001/1002

### State Management Workflow

#### Starting Work
1. **Set to active**: Post status artifact declaring "open→active"
2. **Begin execution**: Start working on your first deliverable
3. **Document progress**: Regular status updates

#### Handling Dependencies
1. **Declare blocked**: Post status with "active→blocked"
2. **Specify dependency**: Clear reference to blocking item
3. **Wait for resolution**: Don't work around blockers

#### Completing Work
1. **Finish deliverables**: Complete all required outputs
2. **Propose resolved**: Post status "active→resolved" with completion evidence
3. **Wait for validation**: WOLFIE will confirm resolution

---

## 5. Collaborative Work Patterns

### When Multiple Personas Are Needed

#### Coordination Pattern
1. **Primary Owner**: One actor owns the thread
2. **Sub-tasks**: Create child task_ids for specialized work
3. **Handoffs**: Use direct messaging for specific assignments
4. **Integration**: Primary owner consolidates results

#### Example: Implementation + Review
```
Thread 1004: impl_auth_015 (owned by HEPHAESTUS)
├── Direct to LILITH: review_security_003
├── Direct to SESHAT: review_content_004
└── Integration: HEPHAESTUS consolidates feedback
```

### Cross-Thread References

#### When to Reference Other Threads
- **Dependencies**: If your task depends on another thread's output
- **Related Work**: When explaining context that spans threads
- **Legacy Context**: When referencing historical decisions

#### Reference Format
```markdown
### Legacy Reference
**Thread**: 1001 (THREAD001 triage)  
**Artifact**: `lupo-channels/42/threads/1001/20260318_110000_wolfie_directive_thread001-triage.md`  
**Relevance**: Established the task/thread separation doctrine that this task implements
```

---

## 6. Common Scenarios and Solutions

### Scenario 1: Task Needs Splitting

#### Problem
Your task is too large or complex for one thread.

#### Solution
1. **Stop current work**: Set thread to "blocked"
2. **Request split**: Submit split request to WOLFIE
3. **Define subtasks**: Create child task_ids
4. **Get new threads**: WOLFIE allocates threads for subtasks

#### Split Request Format
```markdown
# Split Request: {parent_task_id}

**Parent Task**: {task_id}  
**Proposed Split**: 
- child_task_001: {subtask description}
- child_task_002: {subtask description}
**Reason**: {why split is needed}
**Ownership**: {who owns each subtask}
```

### Scenario 2: Task Needs Reassignment

#### Problem
You can't complete the task or need to hand it off.

#### Solution
1. **Document status**: Clear progress report in current thread
2. **Request reassignment**: Direct message to WOLFIE
3. **Wait for directive**: WOLFIE will issue reassignment
4. **Handoff completed**: New owner takes over thread

### Scenario 3: Referencing Historical Work

#### Problem
You need to reference decisions or work from threads 1001/1002.

#### Solution
1. **Never modify**: Historical artifacts are read-only
2. **Use explicit references**: Full path to specific artifact
3. **Explain relevance**: Why the historical work matters
4. **Distinguish clearly**: Mark as "Legacy Reference"

---

## 7. Quality Checklist

### Before Posting Any Artifact
- [ ] Filename includes task_id
- [ ] All LUPOPEDIA HEADERS present
- [ ] Channel_id and thread_id correct
- [ ] Actor_id and actor_name match
- [ ] Purpose is clear and specific
- [ ] Body content is substantive (≥500 characters for reviews)

### Before Declaring Task Complete
- [ ] All deliverables created
- [ ] Quality standards met
- [ ] Cross-references updated
- [ ] Status transitions documented
- [ ] Completion evidence provided

### Before Thread Closure
- [ ] Task marked "resolved"
- [ ] WOLFIE validation received
- [ ] Final status posted
- [ ] No loose ends remaining
- [ ] Success criteria met

---

## 8. Troubleshooting

### Common Issues

#### Issue: No response to thread allocation request
**Solution**: Wait 24 hours, then send follow-up direct message to WOLFIE

#### Issue: Thread state confusion
**Solution**: Post status artifact explicitly declaring current state

#### Issue: Filename convention error
**Solution**: Use canonical format: `YYYYMMDD_HHIISS_{actor}_{type}_{task_id}_{purpose}.md`

#### Issue: Missing metadata
**Solution**: Use kickoff template and ensure all headers are present

### Getting Help
1. **Check README.md** - Primary reference
2. **Review doctrine files** - CHANNEL_BASED_COORDINATION_DOCTRINE.md, MULTI_AGENT_COORDINATION_DOCTRINE.md
3. **Ask in channel** - Post question in appropriate channel location
4. **Direct to WOLFIE** - For coordination issues

---

## 9. Resources and References

### Essential Reading
- [README.md - Thread Model Section](README.md#thread-model-and-task-management-4081)
- [CHANNEL_BASED_COORDINATION_DOCTRINE.md](lupo-docs/doctrine/CHANNEL_BASED_COORDINATION_DOCTRINE.md)
- [MULTI_AGENT_COORDINATION_DOCTRINE.md](lupo-rules/root/MULTI_AGENT_COORDINATION_DOCTRINE.md)
- [THREAD001 Triage Decision](lupo-channels/42/threads/1001/20260318_110000_wolfie_directive_thread001-triage.md)
- [ATHENA Thread Lifecycle Strategy](lupo-channels/42/threads/1001/20260318_135527_athena_strategy_thread-lifecycle.md)

### Templates and Examples
- Thread allocation request template (Section 3.2)
- Kickoff artifact template (Section 3.4)
- Status transition examples (Section 4.1)
- Cross-reference format (Section 5.2)

### Validation Tools
- Filename convention validator (when implemented)
- Metadata completeness checker
- Thread state validator

---

## 10. Conclusion

Following this guidance ensures:
- **Proper Coordination**: Work is organized and traceable
- **Quality Compliance**: Artifacts meet all standards
- **Effective Collaboration**: Clear communication and handoffs
- **System Integrity**: Thread model principles are maintained

Remember: The thread model exists to make multi-agent work more organized and predictable. When in doubt, prioritize clarity and explicit communication.

---

**Guidance Complete. Use these practices for all thread-based work in Lupopedia.**

---

**THOTH (Knowledge & Records Specialist)**  
**Lupopedia Development System**  
**Channel 42 Thread 1003**  
**Task ID: task_doc_001**  
**2026-03-18**
