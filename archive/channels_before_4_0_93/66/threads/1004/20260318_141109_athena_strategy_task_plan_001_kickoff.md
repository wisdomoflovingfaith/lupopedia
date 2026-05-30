---
lupopedia.headers:
  lupopedia.version: 4.0.81
  lupopedia.schema: thread
  system_version: 4.0.81
  file_path_from_root: channels/66/threads/1004/20260318_141109_athena_strategy_task_plan_001_kickoff.md
  web_path: http://www.lupopedia.com/channels/66/threads/1004/20260318_141109_athena_strategy_task_plan_001_kickoff.md
  last_modified_utc: '20260324182605'
  channel_id: 66
  thread_id: 1004
  task_id: task_plan_001
  actor_id: 12
  actor_name: athena
  delegation_chain: athena:strategy
  artifact_type: thread
  artifact_kind: strategy_decision
  purpose: 'Kickoff strategy: Option A planning system (TODO.md task registry + plan.md
    roadmap + threads as execution containers) with lifecycle/status mapping and migration
    approach'
  tags:
  - athena
  - strategy
  - task_plan_001
  - option_a
  - todo_registry
  - plan_roadmap
  - task_id
  - thread_id
  - lifecycle
  - migration
  - 4.0.81
  when_updated: '20260324182605'
lupopedia.edges:
  outbound_edges:
  - to: channels/42/threads/1001/20260318_120000_wolfie_directive_task_planning-thread-allocation.md
    type: implements
    weight: 1.0
    reason: Allocation + Option A decision
  - to: TODO.md
    type: analyzes
    weight: 1.0
    reason: Current task registry structure
  - to: plan.md
    type: analyzes
    weight: 1.0
    reason: Current roadmap structure
  - to: channels/42/threads/1001/20260318_110000_wolfie_directive_thread001-triage.md
    type: aligns_with
    weight: 0.9
    reason: task_id/thread_id separation + canonical filename pattern
  - to: channels/42/threads/1001/20260318_135527_athena_strategy_thread-lifecycle.md
    type: uses
    weight: 1.0
    reason: Canonical lifecycle state machine
  - to: channels/42/threads/1003/20260318_175000_thoth_status_task_doc_001_complete.md
    type: references
    weight: 0.7
    reason: README explains thread model; informs planning doc alignment
  - to: docs/doctrine/CHANNEL_BASED_COORDINATION_DOCTRINE.md
    type: aligns_with
    weight: 0.8
    reason: Channel layout + coordination flows
  - to: rules/root/MULTI_AGENT_COORDINATION_DOCTRINE.md
    type: aligns_with
    weight: 0.8
    reason: TSK001/TSK002/TSK003 and artifact governance
lupopedia.footer:
  version: 4.0.81
  last_verified: '20260324182605'
  last_verified_by: cursor
  orchestrator: wolfie
  next_action:
  - 'After acceptance: produce detailed structure spec artifact(s) for TODO.md and
    plan.md (no edits yet)'
  - 'After structure spec: HEPHAESTUS implements validators + safe migration steps
    (separate task allocation)'
  last_verified_by_actor_id: 102
---
# file: ATHENA strategy kickoff — task_plan_001 (Option A planning system) — channel 42 thread 1004

This output complies with Lupopedia Constitutional Root Rules.

## 1. Current State Analysis

This section describes the current observed structure of `TODO.md` and `plan.md` and identifies conflicts/gaps relative to Option A and the thread-per-task doctrine.

### 1.1 `TODO.md` (current)

Observed structure:

- `TODO.md` is explicitly labeled as the canonical multi-agent coordination file (TSK001).
- It currently tracks work primarily as:
  - a **prompt execution queue** (table with `Prompt`, `Target`, `Owner`, `Status`, `Notes`)
  - a list of deferred work items (owner/status often `TBD`)
  - version history notes

Structural problems for Option A:

- **No stable `task_id` registry row per task**: entries are primarily prompt IDs and links, not canonical task identifiers.
- **Thread mapping is missing**: there is no dedicated `thread_id` field/column mapping execution containers.
- **Ownership semantics are ambiguous**: the table has both `Target` and `Owner`, and rows sometimes omit one or conflate the two; TSK002 requires a single owner.
- **Lifecycle is implicit**: `Status` values (`pending`, `partial`, etc.) do not map deterministically to the canonical lifecycle states (`open/active/blocked/resolved/archived`).
- **Mixed entities**: prompts, tasks, and release blockers are represented with the same row shape, which prevents deterministic validation of “one task per thread” and stable lineage.

### 1.2 `plan.md` (current)

Observed structure:

- `plan.md` is a dependency-ordered roadmap with phases (Stabilization → Enforcement → Automation).
- It includes an embedded “prompt queue” cross-reference table, but **does not** enumerate tasks as stable identities.

Structural problems for Option A:

- **No explicit linkage to `task_id`**: phases reference prompts rather than task registry identities.
- **No deterministic mapping from phase items to execution threads**: it lists what is pending but not where execution occurs.
- **Status/lifecycle not expressed**: phases have “depends on” and some completion checkboxes but do not connect to task lifecycle states.

### 1.3 Conflicts with the thread-per-task doctrine and lifecycle doctrine

- Thread-per-task requires **a stable task identity** (`task_id`) and a separate execution container (`thread_id`). Current `TODO.md` does not maintain this mapping as a first-class registry.
- Lifecycle doctrine requires explicit states and transitions; current docs use status terms that are not aligned to the canonical state machine.
- Option A requires: **`TODO.md` = global registry**, **`plan.md` = strategic roadmap**, **threads = execution containers**. Today, `TODO.md` acts as a prompt queue and `plan.md` repeats prompt state.

### 1.4 Missing fields (minimum needed for Option A)

At minimum, Option A requires each actively executed work item to have explicit fields:

- `task_id` (stable)
- `owner_actor_id` and/or `owner_actor_name` (single owner)
- `thread_id` (numeric execution container; may be empty only before allocation)
- `lifecycle_state` (canonical: open/active/blocked/resolved/archived)
- `status_value` (human-friendly; deterministically derived from lifecycle mapping)
- `created_utc_ymdhis` (BIGINT or string ymdhis in UTC)
- `last_updated_utc_ymdhis`
- `primary_artifact` (path to kickoff or current authoritative directive for that task)

## 2. Target Architecture (Option A — refine, not redefine)

Option A is binding:

- `TODO.md` is the **Global Task Registry**
- `plan.md` is the **Strategic Roadmap**
- `channels/42/threads/{thread_id}/` directories are **execution containers**

This section defines the exact model shape that can later be enforced by validators (without DB dependency).

### 2.1 `TODO.md` as Global Task Registry (authoritative)

#### 2.1.1 Canonical representation

`TODO.md` MUST contain exactly one canonical registry table named:

- `## Global Task Registry (Option A)`

The registry MUST be a Markdown table (not YAML) to keep diffs stable and to enable deterministic parsing.

#### 2.1.2 Required columns (MUST)

The registry table MUST contain these columns in this exact order:

1. `task_id`
2. `task_title`
3. `owner_actor`
4. `lifecycle_state`
5. `status`
6. `thread_id`
7. `priority`
8. `created_utc`
9. `updated_utc`
10. `primary_artifact`
11. `notes`

Column meanings:

- `task_id`: stable identifier (e.g., `task_plan_001`) — MUST be unique within the table.
- `task_title`: short human title (deterministic, no prose paragraphs).
- `owner_actor`: must be a single value (recommended `actor_id:slug`, e.g. `12:athena`).
- `lifecycle_state`: one of `open|active|blocked|resolved|archived` (canonical set).
- `status`: a controlled vocabulary derived from lifecycle mapping (see §4).
- `thread_id`: numeric thread id or empty `-` if not yet allocated.
- `priority`: controlled vocabulary: `P0|P1|P2|P3` (registry-level urgency; independent of lifecycle).
- `created_utc`, `updated_utc`: `YYYYMMDD_HHIISS` UTC.
- `primary_artifact`: path to kickoff or controlling directive artifact for the task.
- `notes`: short; any long narrative belongs in the thread.

#### 2.1.3 Optional columns (MAY)

Optional columns may be appended **after** `notes` only, and only if added via WOLFIE directive:

- `depends_on` (comma-separated `task_id` list)
- `blocks` (comma-separated `task_id` list)
- `review_required` (`yes|no`)

#### 2.1.4 Ordering rules (MUST)

Registry row ordering MUST be deterministic:

1. Sort by `priority` (P0 first)
2. Then by `lifecycle_state` in this order: `active`, `blocked`, `open`, `resolved`, `archived`
3. Then lexicographically by `task_id`

This ordering rule exists so validators can require stable ordering and reduce merge conflicts.

#### 2.1.5 Non-registry sections (allowed but non-authoritative)

`TODO.md` MAY still include other human-friendly sections (e.g., “Release Blockers”), but they MUST be explicitly marked as **views** and MUST not contradict the registry table.

Rule: if any other section lists tasks/prompts, it must reference `task_id` rows in the registry.

### 2.2 `plan.md` as Strategic Roadmap (non-authoritative for execution status)

`plan.md` MUST be the strategic roadmap and MUST NOT become a second task registry.

#### 2.2.1 Plan structure (MUST)

`plan.md` MUST use dependency-ordered phases (per doctrine) and, for each phase:

- `Depends on:` (phase prerequisites)
- `Completion when:` (deterministic criteria)
- `Registry links:` list of `task_id` items that are the phase’s execution vehicles

#### 2.2.2 How plan references tasks (MUST)

Plan MUST reference tasks by `task_id` (not by thread_id, not by prompt id). A plan item may additionally include the current `thread_id` for convenience, but `task_id` remains primary.

Example form (normative):

- `- task_id: task_plan_001 (thread 1004) — planning system redesign`

#### 2.2.3 Plan/task relationship (binding)

- Plan phases describe **why and in what dependency order** work exists.
- The task registry describes **what is being executed and who owns it**.
- Threads contain **how the work is executed** (artifacts, status, reviews).

## 3. Mapping Model (task_id → thread_id)

### 3.1 Primary mapping rule

- **Default**: one `task_id` maps to one active `thread_id` at a time.
- The mapping record of truth is the `TODO.md` registry row.

### 3.2 When `thread_id` is assigned

- `thread_id` is assigned only by WOLFIE directive (allocation).
- A task may exist in the registry with `thread_id: -` (unallocated) while in `open` state.
- Once allocated, `thread_id` becomes required for `active|blocked|resolved|archived` states.

### 3.3 Reassignment (allowed but controlled)

Thread reassignment is allowed only via WOLFIE directive and must follow the lifecycle doctrine:

- registry row keeps the same `task_id`
- `thread_id` is updated to the new thread container
- `primary_artifact` is updated to the WOLFIE reassignment directive (or the new thread kickoff that references it)
- old thread is transitioned toward `archived` with explicit cross-reference (no history rewrite)

### 3.4 Completed tasks representation

- Completed tasks remain in the registry as `lifecycle_state: resolved` until WOLFIE archives them (`archived`).
- `archived` rows stay present (registry is historical), but may be moved to an “Archived Registry” view section that is generated/manually maintained later. For now: keep in one table, ordered deterministically.

## 4. Lifecycle Integration (ATHENA lifecycle → TODO status values)

Canonical lifecycle states (binding) are:

`open`, `active`, `blocked`, `resolved`, `archived`

`TODO.md.status` MUST be derived deterministically from lifecycle state:

| lifecycle_state | status (MUST) | meaning in registry |
|---|---|---|
| open | planned | task exists; execution not started |
| active | in_progress | execution underway in assigned thread |
| blocked | blocked | execution halted pending explicit dependency |
| resolved | complete | outputs complete; pending archival |
| archived | archived | historical only; no new work |

Rules:

- Only the above `status` values are permitted in the registry.
- Any additional nuance (e.g., “partial”) must live in thread artifacts, not in registry status.

## 5. Thread Integration Rules

This section defines the deterministic handshake between the registry and execution containers.

### 5.1 When a task appears in `TODO.md`

A task must be added to the registry when:

- WOLFIE allocates it (directive), or
- a prompt is created that requires execution by a named actor, and WOLFIE accepts it into the execution queue

### 5.2 When a thread is created (Option A respected)

Threads are DB-first numeric identities (Option A). Therefore:

- A thread directory `threads/{thread_id}/` is an execution container and must correspond to an allocated/provisioned thread id.
- A task must not start execution without an allocated thread id.

### 5.3 How `thread_id` is written back to the registry

Registry update rule (later implemented by humans/validators):

- After WOLFIE issues allocation directive (task_id + thread_id), the registry row MUST be updated to include `thread_id`.
- `primary_artifact` MUST point to either:
  - the WOLFIE allocation directive, or
  - the task kickoff artifact in the allocated thread (recommended)

### 5.4 How thread completion updates the registry

When the task owner posts completion (active→resolved) in the thread:

- registry row lifecycle_state MUST become `resolved`
- `updated_utc` must update
- `primary_artifact` should remain the kickoff or may be set to the completion artifact (policy decision; recommend: keep kickoff as primary and add completion artifact in notes)

When WOLFIE archives:

- registry row becomes `archived`

## 6. Migration Strategy (from current docs to Option A structures)

This migration is documentation-structure migration only (no DB dependency).

### 6.1 `TODO.md` migration

Convert in two passes:

1. **Extract active work items** from current tables (release blockers + deferred work) and map each to a `task_id`:
   - prompts-only rows must be assigned or mapped to a task_id (some may already be tasks; others become `task_prompt_*` if needed by WOLFIE).
2. **Populate the Global Task Registry table** with:
   - owner_actor (single owner; do not keep separate “target” vs “owner” ambiguity)
   - lifecycle_state derived from current intent:
     - pending → open (planned)
     - partial → active (in_progress) unless explicitly blocked
3. **Do not delete** the existing prompt queue immediately; re-label it as a “view” that references `task_id` rows.

What is NOT migrated:

- freeform notes that are not tied to a specific task_id (move those into thread artifacts or keep as narrative sections outside the registry, explicitly non-authoritative)

### 6.2 `plan.md` migration

Convert in one pass:

- Keep the phase structure (dependency-ordered).
- Replace prompt references with `task_id` references.
- Ensure “prompt queue” is treated as input, not as the plan’s primary indexing mechanism.

### 6.3 Existing tasks mapping

Existing known tasks already have a natural mapping:

- `task_plan_001` → thread `1004` (this thread)
- `task_doc_001` → thread `1003` (completed per THOTH)

Legacy mixed threads:

- threads `1001` and `1002` remain historical; tasks derived from legacy artifacts must be created forward-only in the registry with explicit legacy references (no rewriting).

## 7. Risks / Edge Cases

### 7.1 Tasks without threads

Allowed only for `lifecycle_state: open` with `thread_id: -`.
If `active|blocked|resolved|archived` and `thread_id: -`, that is a registry violation (validator-enforceable).

### 7.2 Threads without tasks (legacy and orphan)

- Legacy threads may exist without a clean task_id mapping; treat them as historical.
- Non-legacy “orphan thread” should be flagged later by validators: a thread with artifacts but no matching registry row.

### 7.3 Multiple threads per task

Not allowed as parallel execution. Allowed only as:

- reassignment (sequential) via WOLFIE directive, or
- split into child tasks with their own threads (per lifecycle doctrine)

### 7.4 Orphaned registry entries

Registry rows with `thread_id` that no longer exists (or is archived while lifecycle not archived) must be resolved by WOLFIE directive (reassign or archive).

---

## Deterministic next-step output (after this kickoff)

After this kickoff is accepted, the next phase artifacts should be:

- a detailed `TODO.md` registry spec with parsing rules and validator candidates (still no edits)
- a detailed `plan.md` roadmap spec with required `task_id` references (still no edits)

_ATHENA (actor_id 12) — kickoff complete for task_plan_001 under Option A._
