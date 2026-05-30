---
lupopedia.headers:
  header_format_version: "4.1.4"
  file_path_from_root: "lupo-docs/prd/55_A_WORKFLOW_AND_CHECKPOINT_MODEL.md"
  web_path: "https://www.lupopedia.com/lupopedia/lupo-docs/prd/55_A_WORKFLOW_AND_CHECKPOINT_MODEL.md"
  status: "active"
  when_updated: "20260422100000"
  trust_tier: "canonical"
  questions_toon: null
  memory_toon: "lupo-memory/development/canonical/1026/04/55-workflow-and-checkpoint-model.toon"
  atoms_toon: null
  transcript_jsonl: "0/development/prd_files/55_workflow_and_checkpoint_model"
  artifact_type: "prd"
  artifact_kind: "specification"
  channel_key: "development"
  federation_node_id: 0
  thread_id: null
  content_id: null
  content_parent_id: null
  default_collection_id: null
  lupopedia.schema: "prd"
  prd_cluster: "00_A_55_A
  title: "PRD 55 — Workflow & Checkpoint Model (Buffer vs GitHub)"
  summary: "Defines Lupopedia's buffer-first workflow system where GitHub serves as checkpoint ledger rather than primary working surface."
---

# PRD 55 — Workflow & Checkpoint Model (Buffer vs GitHub)

## 1. PURPOSE

Define Lupopedia's buffer-first workflow system where GitHub serves as checkpoint ledger rather than primary working surface, establishing clear separation between active work and checkpointed states.

## 2. SCOPE

Applies to:
* All IDEs and agents working in Lupopedia
* Changelog buffer management
* PRD and artifact development
* GitHub interaction patterns
* Multi-agent coordination

## 3. WORKING MODEL: BUFFER-FIRST SYSTEM

### 3.1 LIVING SYSTEM STATE

Lupopedia is a buffer-first system with three distinct layers:

**Strategy Layer:**
* PRDs
* Planning documents
* High-level direction

**Execution Layer:**
* Changelog buffers
* JSON/TOON mirrors
* Channel threads
* Real-time coordination

**Checkpoint Layer:**
* GitHub pushes
* Validated states
* Preserved snapshots

**The execution layer represents the living system state.**

### 3.2 CONTINUOUS WORKFLOW

* Multiple IDEs / agents operate in parallel
* Work is written continuously into buffers and structured artifacts
* Real-time coordination through shared artifacts
* No waiting for Git commits to share progress

### 3.3 WORKSPACE CHARACTERISTICS

**Buffers provide:**
* Real-time system memory
* Cross-agent coordination layer
* Audit trail of in-progress work
* Immediate visibility of changes

**Operational equivalent:**
"Working Git history before commit"

### 3.4 Browser Sandbox Fallback

**Execution Surfaces:**
* IDE agents (primary execution surface)
* Browser-based LLM (fallback execution surface)

**Fallback Rule:**
* When IDE agents crash, fail, or lose context, the browser-based sandbox is used to continue work
* No work is abandoned due to IDE agent failure

**Control Model:**
* Browser sandbox is manually driven by the human operator
* All context is explicitly provided via copy/paste operations
* No implicit state is assumed between sessions

**System Property:**
* Browser fallback is NOT a hack or workaround
* It is a valid, resilient execution path in the workflow
* Provides continuity when primary execution surface fails

## 4. GITHUB AS CHECKPOINT LEDGER

### 4.1 CHECKPOINT PURPOSE

GitHub is used only for:
* End-of-day checkpoints
* Stable system states
* Reproducducible snapshots

### 4.2 PUSH SEMANTICS

**A push represents:**
"This system state is coherent and worth preserving"

**NOT:**
"Small incremental changes"

### 4.3 VAULT METAPHOR

GitHub functions as a vault for:
* Completed work periods
* Validated system states
* Recovery points
* Historical reference

## 5. NO MICRO-PUSH DOCTRINE

### 5.1 PROHIBITED PATTERNS

**Agents MUST NOT:**
* Push after small edits
* Push after partial fixes
* Treat GitHub as a working buffer
* Use push as task completion signal

### 5.2 PERMITTED PATTERNS

**Pushes are reserved for:**
* Checkpoint states
* Validated system coherence
* End-of-work-period snapshots
* Human-instructed checkpoints

### 5.3 RATIONALE

* Prevents GitHub noise
* Maintains clean history
* Preserves checkpoint integrity
* Enables meaningful rollback points

## 6. CHANGELOG BUFFER AS PRIMARY CONTINUITY

### 6.1 BUFFER FUNCTIONS

Changelog buffers act as:
* Real-time system memory
* Cross-agent coordination layer
* Audit trail of in-progress work
* Progress tracking mechanism

### 6.2 BUFFER CHARACTERISTICS

**Immediate visibility:**
* All agents see buffer updates in real-time
* No commit/push delay for coordination
* Continuous progress tracking

**Persistent state:**
* Buffers survive IDE sessions
* Cross-session continuity maintained
* Historical progress preserved

### 6.3 BUFFER TO CHECKPOINT FLOW

```
Buffer Work → Accumulate → Validate → Checkpoint → GitHub Push
```

## 7. MULTI-AGENT PARALLEL WORKFLOW

### 7.1 PARALLEL EXECUTION

System supports:
* Multiple IDEs
* Multiple agents
* Parallel task execution
* Simultaneous artifact development

### 7.2 COORDINATION MECHANISMS

**Coordination occurs through:**
* Buffers (real-time updates)
* PRDs (structured proposals)
* Channel threads (discussions)
* JSON/TOON mirrors (state synchronization)

**Manual Routing Emphasis:**
* Human-directed task routing between agents
* Coordination is human-orchestrated, not automated
* Human operator determines agent task assignment
* Manual oversight guides workflow decisions

**NOT through:**
* Frequent Git commits
* Branch-based coordination
* Pull request workflows
* Automated task distribution

### 7.3 CONFLICT RESOLUTION

* Real-time visibility prevents duplicate work
* Buffer coordination enables immediate conflict detection
* Human oversight for resolution decisions
* Structured artifacts provide context

## 8. CHECKPOINT DEFINITION

### 8.1 VALID CHECKPOINT CRITERIA

A valid checkpoint requires:
* Validator passing (or intentional staged state)
* No critical unresolved conflicts
* System state understood by human operator
* Coherent artifact set

### 8.2 CHECKPOINT PROCESS

**Human operator directs all checkpoint timing.**

**Only then:**
→ Push to GitHub

**Process:**
1. Validate system coherence
2. Resolve critical conflicts
3. Confirm human understanding
4. Manual validation over automated criteria
5. Human operator authorizes checkpoint
6. Execute checkpoint push

### 8.3 CHECKPOINT VALUE

Each checkpoint provides:
* Reproducible system state
* Recovery point
* Historical reference
* Deployment baseline

## 9. CORE PRINCIPLE

"GitHub is the vault. Buffers are the workspace."

### 9.1 IMPLICATIONS

* **Workspace:** Continuous, immediate, collaborative
* **Vault:** Periodic, validated, preserved
* **Flow:** Work → Validate → Checkpoint → Preserve
* **Authority:** Human validates checkpoints

### 9.2 BENEFITS

* Immediate collaboration without Git overhead
* Clean GitHub history with meaningful checkpoints
* Real-time coordination across multiple agents
* Clear separation between work and preservation

## 10. AGENT BEHAVIOR RULE

### 10.1 REQUIRED BEHAVIORS

**Agents MUST:**
* Write to buffers during work
* Propose changes through proper channels
* Wait for checkpoint instruction before push
* Maintain real-time coordination through buffers

### 10.2 PROHIBITED BEHAVIORS

**Agents MUST NOT:**
* Initiate push without explicit instruction
* Assume push is part of task completion
* Use GitHub as primary coordination mechanism
* Create micro-pushes for small changes

### 10.3 TASK COMPLETION

**Task completion does NOT imply:**
* Immediate GitHub push
* Checkpoint creation
* Vault update

**Task completion DOES imply:**
* Buffer updates completed
* Work documented in artifacts
* Ready for checkpoint evaluation

## 11. IMPLEMENTATION GUIDELINES

### 11.1 BUFFER MANAGEMENT

* Maintain continuous buffer updates
* Ensure cross-agent visibility
* Preserve buffer state across sessions
* Use buffers for primary coordination

### 11.2 CHECKPOINT TIMING

* End of work periods
* Major feature completion
* Human-directed checkpoints
* System coherence validation

### 11.3 GITHUB INTERACTION

* Minimal, meaningful pushes
* Clear checkpoint descriptions
* Validated system states
* Recovery-ready snapshots

## 12. VALIDATION CRITERIA

### 12.1 SYSTEM COHERENCE

* All artifacts consistent
* No critical conflicts
* PRD compliance maintained
* Human operator understanding

### 12.2 CHECKPOINT READINESS

* Buffer state stable
* Work period complete
* Validation passed
* Human approval obtained

## 13. CROSS-REFERENCES

- Related: PRD 16 (Headers) - Header format compliance across all artifacts
- Related: PRD 86 (Immune System) - Validation enforcement for checkpoints
- Related: PRD 02 (Channels) - Channel-based coordination mechanisms
- Related: Database Doctrine - TOON/JSON mirror consistency
- See also: Multi-agent coordination protocols
- See also: Buffer management best practices

## 14. COMPLIANCE REQUIREMENTS

### 14.1 AGENT COMPLIANCE

All agents working in Lupopedia MUST:
* Follow buffer-first workflow
* Respect no-micro-push doctrine
* Await checkpoint instructions
* Maintain real-time coordination

### 14.2 SYSTEM COMPLIANCE

System tools MUST:
* Support real-time buffer updates
* Enable multi-agent coordination
* Provide checkpoint validation
* Maintain GitHub checkpoint integrity

## 15. EVOLUTION CONSIDERATIONS

### 15.1 SCALABILITY

* Buffer system scales with agent count
* GitHub checkpoints remain meaningful
* Coordination mechanisms support growth
* Validation processes adapt to complexity

### 15.2 TOOLING EVOLUTION

* Enhanced buffer management capabilities
* Improved checkpoint validation automation
- Better cross-agent coordination tools
* Refined GitHub checkpoint workflows
