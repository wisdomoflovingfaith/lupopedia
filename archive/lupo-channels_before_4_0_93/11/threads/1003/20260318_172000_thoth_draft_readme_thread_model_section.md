---
lupopedia.headers:
  lupopedia.version: "4.0.81"
  lupopedia.schema: "thread"
  system_version: "4.0.81"
  file_path_from_root: "lupo-channels/11/threads/1003/20260318_172000_thoth_draft_readme_thread_model_section.md"
  web_path: "http://www.lupopedia.com/lupo-channels/11/threads/1003/20260318_172000_thoth_draft_readme_thread_model_section"
  questions_toon: null
  channel_id: 11
  thread_id: 1003
  task_id: "task_doc_001"
  actor_id: 26
  actor_name: "thoth"
  delegation_chain: "thoth:knowledge"
  artifact_type: "thread"
  artifact_kind: "draft"
  purpose: "Draft thread model section for README.md integration"
  tags: ["task_doc_001", "readme_update", "thread_model", "4.0.81"]
lupopedia.edges:
  outbound_edges:
    - { to: "README.md", type: "updates", weight: 1.0, reason: "New section for integration" }
    - { to: "lupo-channels/11/threads/1003/20260318_171000_thoth_analysis_readme_current_state.md", type: "implements", weight: 0.9, reason: "Follow-up to analysis" }
    - { to: "lupo-channels/42/threads/1001/20260318_110000_wolfie_directive_thread001-triage.md", type: "aligns_with", weight: 0.8, reason: "THREAD001 decisions implemented" }
    - { to: "lupo-channels/42/threads/1001/20260318_135527_athena_strategy_thread-lifecycle.md", type: "aligns_with", weight: 0.8, reason: "ATHENA lifecycle strategy" }
lupopedia.footer:
  version: "4.0.81"
  last_verified: "20260318"
  last_verified_by: "thoth"
  orchestrator: "wolfie"
  next_action:
    - "Integrate section into README.md"
    - "Update version references"
    - "Create contributor guidance artifact"
---

# file: THOTH draft — README.md thread model section — thread 1003

## Draft: Thread Model and Task Management Section for README.md

**Effective Date**: 2026-03-18  
**Author**: THOTH (actor_id 26)  
**Task**: task_doc_001  
**Status**: Draft Ready for Integration

---

## Thread Model and Task Management (4.0.81+)

Lupopedia uses a **one-thread-per-task** model for organized, traceable multi-agent coordination. This ensures clear work boundaries, complete audit trails, and deterministic task execution.

### One-Thread-Per-Task Doctrine

**Core Principle**: Each distinct task or work item executes in exactly one dedicated thread.

This principle provides:
- **Clear Lineage**: Every artifact has an unambiguous task context
- **Traceable Execution**: Complete history of work per task
- **Isolated Scope**: No mixing of different tasks in the same thread
- **Deterministic Routing**: Clear paths for coordination and handoffs

**Enforcement**: Thread allocation is controlled by WOLFIE (actor_id 1) through explicit directives. New tasks must not be created in existing threads without explicit allocation.

### Task ID vs Thread ID Separation

**Critical Distinction**: `task_id` and `thread_id` are separate entities with different purposes.

#### Task ID (`task_id`)
- **Purpose**: Stable identity of the work item itself
- **Format**: Human-readable identifiers (e.g., `task_doc_001`, `impl_042`, `review_007`)
- **Stability**: Remains constant even if thread allocation changes
- **Usage**: Appears in filenames, metadata, and cross-references

#### Thread ID (`thread_id`)
- **Purpose**: Container identifier for task execution
- **Format**: Numeric database ID (e.g., 1001, 1002, 1003)
- **Stability**: May change through reassignment directives
- **Usage**: Directory structure and database references

#### Canonical Filename Convention
```
YYYYMMDD_HHIISS_{actor}_{type}_{task_id}_{purpose}.md
```

Examples:
- `20260318_170000_thoth_directive_task_doc_001_kickoff.md`
- `20260318_120000_hephaestus_status_impl_042_schema-complete.md`
- `20260318_140000_lilith_review_review_007_security-audit.md`

### Thread Lifecycle Management

Threads progress through five canonical states with explicit transitions:

| State | Meaning | Who Can Set | Required Evidence |
|-------|---------|-------------|-------------------|
| **open** | Thread exists, ready to begin work | WOLFIE, Task Owner | Creation/assignment directive |
| **active** | Work is in progress | Task Owner, WOLFIE | Status artifact declaring transition |
| **blocked** | Awaiting dependency | Task Owner, WOLFIE | Blocking status with dependency reference |
| **resolved** | Work complete, reviewed if required | Task Owner (proposes), WOLFIE (confirms) | Completion artifact + review if needed |
| **archived** | Historical preservation only | WOLFIE only | WOLFIE directive declaring archival |

**Key Rules**:
- No hidden transitions - all state changes must be explicitly declared
- `archived` is terminal - no transitions out of archived state
- Single owner per thread - clear responsibility for state management

### Legacy Thread Handling

#### Historical Threads (1001, 1002)
- **Thread 1001**: Temporary triage thread for doctrine correction
- **Thread 1002**: Historical migration thread
- **Status**: Will transition to `archived` after 4.0.81 doctrine alignment
- **Access**: Read-only reference with explicit cross-references

#### Legacy Reference Protocol
When referencing historical artifacts:
1. Use full explicit path references
2. Add "Legacy Reference" section explaining relevance
3. Clearly distinguish from new doctrine practices
4. Do not modify historical artifacts

#### New Work Allocation
All new tasks must:
- Use thread allocation from dynamic thread pools
- Follow canonical filename convention with `task_id`
- Create dedicated threads via WOLFIE directive
- Maintain clear separation from legacy threads

### Contributor Guidance for Thread Usage

#### Getting a Thread for New Work
1. **Define Task Scope**: Clearly articulate the work item and its boundaries
2. **Request Allocation**: Submit work proposal to WOLFIE for thread assignment
3. **Receive Directive**: Wait for explicit WOLFIE allocation directive
4. **Create Kickoff**: Post initial artifact in allocated thread with proper metadata

#### Best Practices
- **Single Scope**: Keep one task per thread - no mixing
- **Clear Transitions**: Explicitly declare all state changes
- **Proper Filenames**: Use canonical convention with `task_id`
- **Complete Metadata**: Include all required LUPOPEDIA HEADERS
- **Cross-References**: Link to related work with explicit paths

#### Common Pitfalls to Avoid
- **Mixed Scope**: Don't add different tasks to existing threads
- **Implicit Transitions**: Never assume state changes without declaring them
- **Legacy Modification**: Don't modify historical artifacts
- **Missing task_id**: Always include task identifier in filenames
- **Ambiguous Ownership**: Ensure single clear owner per thread

#### Thread Reassignment (When Needed)
If work must move to a new thread:
1. WOLFIE issues explicit reassignment directive
2. Old thread posts closing status referencing directive
3. New thread posts kickoff referencing directive
4. All cross-references updated to new locations

### Integration with Channel Coordination

The thread model integrates with channel-based coordination:
- **Channel 42**: Primary workspace for thread allocation
- **Thread Directories**: `lupo-channels/42/threads/{thread_id}/`
- **Database Integration**: Threads map to `lupo_dialog_threads` table
- **HERMES Routing**: Classifies thread artifacts for actor handoffs

For complete coordination doctrine, see [CHANNEL_BASED_COORDINATION_DOCTRINE.md](../../../../lupo-docs/doctrine/CHANNEL_BASED_COORDINATION_DOCTRINE.md) and [MULTI_AGENT_COORDINATION_DOCTRINE.md](../../../../lupo-rules/root/MULTI_AGENT_COORDINATION_DOCTRINE.md).

---

## Integration Notes

### Insertion Point
Insert this section after "Channel filesystem and HERMES routing (4.0.80+)" (current lines 226-264) and before "Core Identity Model" (lines 268-294).

### Required Updates in README.md
1. Update line 91: footer version from "4.0.80" to "4.0.81"
2. Update line 134: "Current Development — v4.0.80" to "v4.0.81"
3. Update focus areas for 4.0.81 in line 136-140
4. Adjust table of contents if needed

### Cross-Reference Updates
- Update "Channel filesystem" section to reference thread model
- Ensure "Core Identity Model" references thread ownership
- Check "Contributing" section for thread-related guidance

---

**Draft Complete. Ready for README.md integration.**

---

**THOTH (Knowledge & Records Specialist)**  
**Lupopedia Development System**  
**Channel 42 Thread 1003**  
**Task ID: task_doc_001**  
**2026-03-18**
