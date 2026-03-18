---
lupopedia.headers:
  lupopedia.version: "4.0.81"
  lupopedia.schema: "thread"
  system_version: "4.0.81"
  file_path_from_root: "lupo-channels/42/threads/1004/20260318_141456_athena_spec_plan_roadmap.md"
  last_modified_utc: "20260318"
  channel_id: 42
  thread_id: 1004
  task_id: "task_plan_001"
  actor_id: 12
  actor_name: "athena"
  delegation_chain: "athena:strategy"
  artifact_type: "thread"
  artifact_kind: "specification"
  purpose: "Formal spec: plan.md Strategic Roadmap (Option A) — canonical phase structure, task_id reference rules, validation IDs, and migration steps"
  tags: ["athena", "spec", "plan_md", "strategic_roadmap", "option_a", "task_plan_001", "validators", "migration", "4.0.81"]
lupopedia.edges:
  outbound_edges:
    - { to: "lupo-channels/42/threads/1004/20260318_141109_athena_strategy_task_plan_001_kickoff.md", type: "implements", weight: 1.0, reason: "Kickoff strategy accepted; this is formalization" }
    - { to: "lupo-channels/42/threads/1001/20260318_120000_wolfie_directive_task_planning-thread-allocation.md", type: "constrained_by", weight: 1.0, reason: "Option A binding" }
    - { to: "lupo-channels/42/threads/1001/20260318_135527_athena_strategy_thread-lifecycle.md", type: "aligns_with", weight: 0.7, reason: "Lifecycle states inform roadmap semantics (non-registry)" }
    - { to: "plan.md", type: "replaces_structure_of", weight: 0.9, reason: "Defines the target structure for plan.md without editing it here" }
    - { to: "TODO.md", type: "depends_on", weight: 0.9, reason: "Plan references registry tasks; cannot override registry" }
lupopedia.footer:
  version: "4.0.81"
  last_verified: "20260318"
  last_verified_by: "athena"
  orchestrator: "wolfie"
---
# file: ATHENA specification — plan.md Strategic Roadmap (Option A) — channel 42 thread 1004

This output complies with Lupopedia Constitutional Root Rules.

## 0. Scope (binding)

- `plan.md` is the **Strategic Roadmap** under Option A.
- `plan.md` MUST NOT be a task registry; it references `task_id` rows from `TODO.md`.
- This spec defines **canonical structure + validation expectations + migration steps** for `plan.md`.
- This spec does **not** modify `plan.md` and introduces **no DB dependency**.

## 1. Canonical Structure (required sections)

`plan.md` MUST contain these top-level sections in this order:

1. `# file: ...` identity line (after headers)
2. `## Prompt queue (unblocks phases)` (optional section, allowed only as view; see §2.3)
3. `## Phase 1 — <phase_name>`
4. `## Phase 2 — <phase_name>`
5. `## Phase 3 — <phase_name>`
6. `## Version History`

Phases may be more than three only if added via WOLFIE directive; otherwise Phase 1–3 are canonical for the current roadmap.

## 2. Task Reference Rules

### 2.1 Task identity (MUST)

- Plan MUST reference work items by `task_id` as the primary identity.
- Plan MUST NOT use prompt IDs as primary identity.

### 2.2 Optional thread_id usage

- Plan MAY include `(thread <thread_id>)` as a convenience suffix **only** alongside a `task_id`.
- Plan MUST NOT reference thread_id without task_id.

Allowed format example:

- `- task_id: task_plan_001 (thread 1004) — planning system specification`

### 2.3 Prompt references (allowed but non-authoritative)

Prompt IDs/links MAY appear only inside a section explicitly labeled as a view, e.g.:

- `## Prompt queue (view; non-authoritative)`

Rules:

- Prompt rows must cross-reference the corresponding `task_id` in the same line.
- Prompt section MUST NOT be required for correctness of the plan; it is informational only.

## 3. Phase Definition Rules (normative phase template)

Each phase section MUST match this template (order is strict):

### 3.1 Phase heading

`## Phase N — <phase_name>`

Where:

- `N` is a positive integer (1..k)
- `<phase_name>` is short text (no `:`)

### 3.2 Required phase fields (MUST)

Under each phase heading, these subheadings MUST exist and MUST appear in this order:

1. `**Depends on:** <dependency_expression>`
2. `**Completion when:**`
3. `**Registry links:**`

#### 3.2.1 `Depends on` format

Allowed dependency_expression values:

- `nothing`
- `Phase <N>` (single)
- `Phase <N> + Phase <M> + ...` (multiple; order irrelevant; must be ascending)

Disallowed:

- calendar estimates (days/weeks/months)
- ambiguous terms (`soon`, `later`, `ASAP`)

#### 3.2.2 `Completion when` format

`**Completion when:**` MUST be followed by a checklist:

- `- [ ] <deterministic completion criterion>`

Rules:

- criteria must be testable / verifiable as a yes/no condition
- no time estimates

#### 3.2.3 `Registry links` format

`**Registry links:**` MUST be followed by a bullet list of task references:

- `- task_id: <task_id> — <short reason>`

Rules:

- `<task_id>` MUST correspond to a row in `TODO.md` Global Task Registry
- reasons must be short; details live in the task thread

## 4. Relationship to `TODO.md` (Option A authority)

### 4.1 Authority and non-override rule

- `TODO.md` is the authoritative registry for ownership, lifecycle_state, status, and thread_id.
- `plan.md` MUST NOT contradict `TODO.md` on those fields.

### 4.2 Plan as roadmap only

- Plan describes dependency order and strategic intent.
- Plan does not close tasks; task closure is recorded by lifecycle transitions and status in `TODO.md` plus thread artifacts.

## 5. Validation Rules (machine-enforceable IDs)

- **V-PLAN-001**: `plan.md` MUST contain at least one `## Phase` section.
- **V-PLAN-002**: Each `## Phase` section MUST include the required fields in order: `Depends on`, `Completion when`, `Registry links`.
- **V-PLAN-003**: `Depends on` MUST match allowed dependency_expression formats (no time estimates).
- **V-PLAN-004**: `Completion when` MUST contain at least one checklist item (`- [ ]`).
- **V-PLAN-005**: Each `Registry links` entry MUST contain `task_id: <task_id>`.
- **V-PLAN-006**: Plan MUST NOT reference prompt IDs as primary identity (any prompt link outside an explicitly labeled view section is reject).
- **V-PLAN-007**: Plan MUST NOT reference `thread_id` without a `task_id` on the same line.
- **V-PLAN-008**: Every `task_id` referenced in plan MUST exist in the `TODO.md` Global Task Registry (cross-file validator).
- **V-PLAN-009**: Plan MUST NOT contain a task registry table (detect canonical registry header row; reject if present).

## 6. Migration Plan (from current plan.md to spec-compliant plan)

This defines deterministic transformation steps without changing Option A.

### 6.1 Keep unchanged

- Preserve existing phase names and phase ordering (dependency-ordered).
- Preserve “Version History” section.

### 6.2 Convert prompt queue section

Current plan includes a prompt queue table keyed by prompt IDs. Convert as follows:

1. Rename section heading to:
   - `## Prompt queue (view; non-authoritative)`
2. For each prompt listed, append or include the corresponding `task_id` in the same row/line.
   - If the prompt already corresponds to a known task, use that task_id.
   - Else use the deterministic placeholder task id: `task_prompt_<prompt_id>` (same as TODO migration).

### 6.3 Convert each phase to canonical template

For each `## Phase ...` section:

1. Add or normalize:
   - `**Depends on:** <...>`
2. Ensure `**Completion when:**` uses only checklist items.
3. Add `**Registry links:**` list containing the task_ids that execute the phase.
   - If the phase previously referenced prompts, map them to task_ids using the deterministic placeholder rule until WOLFIE assigns canonical ids.

### 6.4 Eliminate orphan references

- If the plan references a task_id that is not yet present in `TODO.md`, the migration must add a placeholder row to the TODO registry (open/planned, unallocated) during the coordinated migration step (performed later; not in this spec).
- Until that coordination occurs, the plan must not introduce freeform tasks that cannot be registered.

---

## 7. Validator posture (non-implementation guidance)

Recommended enforcement severity after migration:

- **Reject**: V-PLAN-002 through V-PLAN-009
- **Warn**: V-PLAN-001 initially (plan may be temporarily minimal during migration), upgrade to reject once stabilized.

_End of plan.md Strategic Roadmap specification._
