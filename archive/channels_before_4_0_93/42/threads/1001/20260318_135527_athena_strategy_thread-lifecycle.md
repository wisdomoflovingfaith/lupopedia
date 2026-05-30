---
lupopedia.headers:
  lupopedia.version: "4.0.81"
  lupopedia.schema: "thread"
  system_version: "4.0.81"
  file_path_from_root: "channels/42/threads/1001/20260318_135527_athena_strategy_thread-lifecycle.md"
  web_path: "http://www.lupopedia.com/channels/42/threads/1001/20260318_135527_athena_strategy_thread-lifecycle.md"
  questions_toon: null
  channel_id: 42
  thread_id: 1001
  actor_id: 12
  actor_name: "athena"
  delegation_chain: "athena:strategy"
  artifact_type: "thread"
  artifact_kind: "strategy_decision"
  purpose: "Canonical strategy: THREAD001 lifecycle states, explicit transitions, and task_id/thread_id relationship handling (split/merge/ownership/legacy/validator rules)"
  tags: ["athena", "strategy", "thread_lifecycle", "thread001", "task_id", "thread_id", "split_merge", "ownership", "validators", "4.0.81"]
lupopedia.edges:
  outbound_edges:
    - { to: "channels/42/threads/1001/20260318_110000_wolfie_directive_thread001-triage.md", type: "implements", weight: 1.0, reason: "Binding triage decision for THREAD001" }
    - { to: "channels/42/threads/1001/20260318_095000_lilith_review_thread-task-canonicalization.md", type: "addresses", weight: 0.9, reason: "P0 lifecycle/split/merge/ownership gaps" }
    - { to: "channels/42/threads/1001/20260318_134258_hermes_prompt_for_wolfie_thread001-triage-and-routing.md", type: "addresses", weight: 0.9, reason: "P0 routing requirements" }
    - { to: "docs/doctrine/CHANNEL_BASED_COORDINATION_DOCTRINE.md", type: "aligns_with", weight: 0.8, reason: "Channel artifact routing + filename conventions" }
    - { to: "rules/root/MULTI_AGENT_COORDINATION_DOCTRINE.md", type: "aligns_with", weight: 0.8, reason: "TSK/COM/ATER validation posture" }
    - { to: "rules/root/LUPOPEDIA_CONSTITUTIONAL_ROOT_RULES.md", type: "constrained_by", weight: 1.0, reason: "No hidden transitions, no rewriting history, deterministic doctrine" }
    - { to: "channels/42/threads/1002/20260317_223020_athena_thread-creation-policy.md", type: "references", weight: 0.75, reason: "Option A: DB-first numeric thread_id" }
    - { to: "channels/42/threads/1002/20260317_224500_wolfie_thread-provisioning-option-a.md", type: "references", weight: 0.75, reason: "WOLFIE directive locking Option A" }
lupopedia.footer:
  version: "4.0.81"
  last_verified: "20260318"
  last_verified_by: "athena"
  orchestrator: "wolfie"
  next_action:
    - "HEPHAESTUS: map validator-enforceable rules to concrete checks (do not implement here)"
    - "WOLFIE: patch doctrine texts to incorporate this strategy (do not rewrite legacy artifacts)"
---
# file: ATHENA strategy — THREAD001 lifecycle + task/thread relationship — channel 42 thread 1001

This output complies with Lupopedia Constitutional Root Rules.

## 0. Scope and binding inputs

- **Binding triage decision (WOLFIE)**: `task_id` is the stable identity; `thread_id` is the execution container; thread `1001` is temporary triage and becomes legacy archive after doctrine correction work completes.
- **Option A (DB-first threads)**: `threads/{thread_id}/` is numeric `dialog_thread_id` and must pre-exist as a first-class thread identity (DB row) before thread posting; filesystem mirrors DB truth.
- **Constraint**: This artifact defines doctrine strategy only. No validator implementation. No schema proposals.

## 1. Canonical lifecycle states (task-thread work)

The lifecycle state is a **declared state** of a task’s execution container (thread) as used by coordination doctrine. There are exactly five canonical states.

### 1.1 State: `open`

- **Meaning**: Thread exists and is eligible to begin work, but no substantive execution has started yet.
- **Who may set**:
  - **WOLFIE** (orchestrator) may set `open` at provisioning/assignment time.
  - **Task owner** may set `open` only if WOLFIE has already created/assigned the task and allocated the thread container.
- **Evidence required**:
  - A creation/provisioning directive or assignment artifact that declares the `task_id` and the `thread_id` container relationship.

### 1.2 State: `active`

- **Meaning**: Work is in progress in this thread for exactly one `task_id` scope.
- **Who may set**:
  - **Task owner** (single owner per TSK002) may set `active`.
  - **WOLFIE** may set `active` when explicitly reassigning or re-opening execution.
- **Evidence required**:
  - A status artifact within the thread that states the transition and names the `task_id`.

### 1.3 State: `blocked`

- **Meaning**: Execution cannot proceed due to a declared dependency or missing prerequisite.
- **Who may set**:
  - **Task owner** may set `blocked`.
  - **WOLFIE** may set `blocked` as a coordination freeze (e.g., dependency discovered during triage).
- **Evidence required**:
  - A blocking status artifact that names:
    - blocking condition (dependency)
    - the external reference (artifact path or TODO row reference)
    - what must change to un-block

### 1.4 State: `resolved`

- **Meaning**: The task’s required outputs are complete, reviewed as required, and no further work is expected in this thread unless explicitly re-opened by directive.
- **Who may set**:
  - **Task owner** may propose `resolved`.
  - **WOLFIE** is the authority to confirm `resolved` when the task is coordination-scoped (root `TODO.md` ownership closure).
- **Evidence required**:
  - A completion status artifact that lists outputs (artifact paths) and declares completion criteria met.
  - If review is required: a review artifact that references the completion artifact.

### 1.5 State: `archived`

- **Meaning**: Thread is historical preservation only. No new substantive work occurs in the thread.
- **Who may set**:
  - **WOLFIE** only.
- **Evidence required**:
  - A WOLFIE directive declaring archival and, when relevant, linking the successor thread for continued work (if any).

## 2. Transition rules (no hidden transitions)

All state changes must be explicitly recorded in a thread artifact with an unambiguous transition declaration. There are no implicit transitions.

### 2.1 Allowed transitions (complete set)

| from_state | to_state | trigger | required conditions | required directive/artifact trail |
|---|---|---|---|---|
| open | active | owner begins execution | task owner declared; task_id declared for thread | status artifact “open→active” referencing task_id + owner |
| open | blocked | dependency found before start | blocking condition stated | status artifact “open→blocked” with dependency reference |
| active | blocked | dependency discovered | blocking condition stated | status artifact “active→blocked” with dependency reference |
| blocked | active | dependency resolved | evidence of dependency resolution | status artifact “blocked→active” referencing resolution artifact(s) |
| active | resolved | outputs complete | completion criteria enumerated | completion status artifact; if required, review artifact references it |
| resolved | archived | preserve history | WOLFIE archival decision | WOLFIE directive “resolved→archived” |
| open | archived | task cancelled before start (rare) | WOLFIE decision to cancel and preserve | WOLFIE directive “open→archived” (must include cancellation reason) |
| blocked | archived | dependency cannot be met / work stopped | WOLFIE decision | WOLFIE directive “blocked→archived” (must include stop reason) |
| active | archived | emergency stop / superseded work | WOLFIE decision | WOLFIE directive “active→archived” (must name successor task_id/thread_id if superseded) |

### 2.2 Disallowed transitions (examples)

- `archived → *` is **disallowed**. If work must resume, it requires a **new thread** and an explicit lineage reference (see §3.4).
- `resolved → active` is **disallowed** without WOLFIE directive. Routine “re-open” is handled by creating a follow-up task_id (or explicit re-open directive) and a new thread.

## 3. `task_id` vs `thread_id` relationship (stable identity vs container)

### 3.1 Definitions (binding)

- **`task_id`**: stable identity of a unit of work (the “work item key”). It persists across thread changes and across split/merge operations.
- **`thread_id`**: execution container identity (numeric dialog thread id). It is an allocation target and may change without changing `task_id`.

### 3.2 Normal mapping rule

- **Default**: **one `task_id` maps to one active `thread_id` at a time**.
- **Non-negotiable constraint**: a single thread must not contain active execution for multiple distinct `task_id` scopes (legacy exceptions only; see §7).

### 3.3 If a thread changes (container reassignment)

Thread reassignment exists to preserve Option A (DB-first numeric threads) and to prevent history rewrite.

- **Allowed**: moving execution to a new thread while keeping the same `task_id`.
- **Required**:
  - a WOLFIE directive declaring `task_id` remains stable and naming:
    - old thread_id (source)
    - new thread_id (destination)
    - reason for reassignment
    - effective moment (timestamped artifact path)
  - a closing status artifact in old thread referencing the directive
  - a “new thread kickoff” artifact in new thread referencing the directive

### 3.4 May a task span multiple threads?

Yes, but only under explicit lineage and only in these forms:

- **Sequential containers (allowed)**: task continues in a new thread via reassignment directive (§3.3). Exactly one thread is `active` for the task at a time.
- **Parallel work (allowed only via split)**: parallel execution requires creation of child task_ids (§4). A single `task_id` does not fan out into multiple active threads without formal split.

## 4. Split protocol (one task becomes multiple subtasks)

Splitting is a formal operation that preserves history and prevents hidden scope drift.

### 4.1 Split decision record (required)

A split must be introduced by a directive/status artifact (WOLFIE or task owner, depending on authority) that declares:

- **`parent_task_id`**: the stable original task identity
- **`child_task_id[]`**: new stable task identities for the subtasks
- **`split_reason`**: why split is required (scope or parallelization)
- **`ownership`**: owner for each child task (single owner each)
- **`thread_allocation_rule`**: each child task receives its own thread container

### 4.2 Child thread allocation rule (binding)

- Each `child_task_id` must have a distinct `thread_id` container for active execution.
- The parent thread may remain active only for parent-scoped coordination (e.g., integration), but must not execute child scopes directly after split.

### 4.3 Cross-reference requirements (mandatory)

Every child kickoff artifact must include explicit references:

- `split_from_parent_task_id: <parent_task_id>`
- `split_from_parent_thread_id: <parent_thread_id>`
- path reference to the split decision artifact

The parent thread must include explicit references to each child:

- list of `child_task_id` and their `thread_id` containers
- link paths to child kickoff artifacts

### 4.4 History preservation rule (non-negotiable)

- The parent thread’s history is preserved and not rewritten.
- The child threads start new history with explicit pointers back to the parent split event.

## 5. Merge protocol (subtasks merged back)

Merging is a formal operation: results are consolidated without rewriting child history.

### 5.1 Merge decision record (required)

The merge record must name:

- `merged_into_task_id`: the receiving task identity (often the original parent_task_id)
- `merged_from_child_task_id[]`: the contributing subtasks
- `merge_artifacts[]`: the specific result artifacts being incorporated
- `merge_decider_actor_id`: who authorized the merge (see §6)

### 5.2 Preservation rule (non-negotiable)

- No child artifacts are rewritten, deleted, or “squashed.”
- The merge record references the child artifacts; it does not replace them.

### 5.3 Closure conditions for child threads

After merge:

- each child task thread should transition to `resolved` (if not already)
- then child threads must be `archived` by WOLFIE directive once the merge is accepted

## 6. Ownership and authority transfer

### 6.1 Ownership at creation (binding)

- A task/thread starts with a **single owner**.
- **Owner** is the actor responsible for execution artifacts and for state changes among `open/active/blocked` within the task thread.
- **WOLFIE** is the authority for:
  - ownership assignment
  - ownership transfer approval
  - transitions into `archived`
  - confirmation of `resolved` when it closes a coordination row in root `TODO.md`

### 6.2 Ownership transfer rule

Ownership transfer requires an explicit artifact trail:

- A **transfer request** artifact (may be authored by current owner or WOLFIE) naming:
  - `task_id`
  - `thread_id`
  - `from_actor_id`
  - `to_actor_id`
  - reason
  - current state
- A **WOLFIE authorization directive** that confirms the transfer (binding)
- A **handoff status artifact** in the thread authored by the new owner acknowledging custody and stating next immediate action

### 6.3 Who can authorize transfers

- **WOLFIE only**.
- Reviews (LILITH/SESHAT) may recommend transfer but cannot enact it.

## 7. Legacy thread handling (mixed legacy threads 1001 / 1002)

Legacy threads are preserved as historical records; they are not rewritten to fit new doctrine.

### 7.1 Preservation rules (non-negotiable)

- Do not modify historical artifacts to retrofit task_id/thread_id separation.
- Do not delete or rename legacy files to “clean up” history.
- Future work must use explicit cross-thread references only.

### 7.2 How legacy threads are referenced after doctrine activation

When a new thread needs to cite legacy work:

- Use explicit artifact path references (full relative path).
- Add a short “Legacy reference” section with:
  - what is being referenced
  - why it matters
  - what modern task_id/thread_id it relates to (if applicable)

### 7.3 Special rule for thread `1001`

Per WOLFIE triage decision:

- Thread `1001` is temporary triage container for doctrine correction only.
- After correction work completes, `1001` transitions to `archived` via WOLFIE directive.
- All new execution work must be allocated to non-legacy threads (DB-first numeric threads under Option A).

## 8. Validator-relevant rules (enforceable later) vs doctrine-only

This section separates rules that are mechanically enforceable from those that remain doctrine-only until additional system support exists.

### 8.1 Rules suitable for future validator enforcement (candidate “errors”)

- **V-1: No hidden transitions**: state transitions must be explicitly declared in artifact body when a state change occurs (detectable by structured marker phrase, see V-6).
- **V-2: Legacy isolation**: new artifacts must not be posted into legacy threads marked archived/legacy by directive (except by explicit WOLFIE waiver artifact).
- **V-3: Thread/task non-conflation**: artifacts must not claim that `thread_id` is the task identity (detectable by required presence of a `task_id` marker when the artifact declares a task execution context).
- **V-4: Split/merge explicitness**: if an artifact declares “split” or “merge”, it must include required fields (parent_task_id/child_task_id or merged_into_task_id/merged_from_child_task_id) in a deterministic block.
- **V-5: One active scope per thread**: artifacts in a non-legacy thread must not declare multiple distinct `task_id` values as active scope in the same artifact.
- **V-6: Deterministic transition marker**: when a transition is declared, body must include a single line marker in the form:
  - `transition: <from_state> -> <to_state>`
  (This is proposed solely to enable validation; it does not require any DB changes.)

### 8.2 Rules suitable for validator enforcement as “warnings”

- **W-1: Missing explicit dependency reference on blocked**: blocked artifacts should include at least one explicit path reference or TODO reference for the dependency.
- **W-2: Missing merge_artifacts list**: merge record should list artifact paths being merged.

### 8.3 Doctrine-only rules (not validator-ready yet)

- **D-1: Ownership truth**: validators can confirm presence of ownership declarations in artifacts, but cannot confirm the authoritative owner without integrating root `TODO.md` or DB authority checks.
- **D-2: Review gating for resolved**: whether a review is “required” depends on task context; this is doctrine-controlled by WOLFIE directives until task typing is formalized.
- **D-3: Thread reassignment legitimacy**: validators can require the presence of a WOLFIE directive for reassignment, but cannot verify DB thread existence in offline mode without DB access (Option A is DB-first by doctrine; offline sync rules apply separately).

## 9. Deterministic appendix (state machine summary)

### 9.1 State set

`{ open, active, blocked, resolved, archived }`

### 9.2 Transition set

- `open -> active`
- `open -> blocked`
- `open -> archived`
- `active -> blocked`
- `active -> resolved`
- `active -> archived`
- `blocked -> active`
- `blocked -> archived`
- `resolved -> archived`

### 9.3 Immutability rule

- `archived` is terminal: no transitions out.

---
_ATHENA (actor_id 12) — canonical strategy for THREAD001 lifecycle + task/thread relationship handling. This artifact is doctrine-strategy only; implementers must not treat it as code or schema._
