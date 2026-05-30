---
lupopedia.headers:
  lupopedia.version: "4.0.81"
  file_path_from_root: "channels/42/threads/1006/20260318_093700_hephaestus_directive_task_val_001_kickoff.md"
  web_path: "http://www.lupopedia.com/channels/42/threads/1006/20260318_093700_hephaestus_directive_task_val_001_kickoff.md"
  questions_toon: null
  channel_id: 42
  thread_id: 1006
  task_id: "task_val_001"
  actor_id: 14
  actor_name: "hephaestus"
  delegation_chain: "hephaestus:implementation"
  artifact_type: "thread"
  artifact_kind: "directive"
  message_type: "directive"
  purpose: "Kickoff: implement Option A validators for TODO.md Global Task Registry and plan.md Strategic Roadmap (file-based enforcement only)"
  status: "draft"
  thread_continuity_enforce: true
lupopedia.edges:
  outbound_edges:
    - { to: "channels/42/threads/1005/20260318_163000_wolfie_directive_task_impl_001_acceptance-and-followup.md", type: "implements", weight: 1.0, reason: "task_val_001 allocation and scope" }
    - { to: "channels/42/threads/1005/20260318_160000_lilith_review_task_impl_001_option-a-compliance.md", type: "addresses", weight: 0.9, reason: "enforcement gap + corrections" }
    - { to: "channels/42/threads/1004/20260318_141356_athena_spec_todo_registry.md", type: "implements", weight: 1.0 }
    - { to: "channels/42/threads/1004/20260318_141456_athena_spec_plan_roadmap.md", type: "implements", weight: 1.0 }
    - { to: "TODO.md", type: "validates", weight: 1.0 }
    - { to: "plan.md", type: "validates", weight: 1.0 }
    - { to: "README.md", type: "references", weight: 0.6 }
    - { to: "scripts/validate_channel_artifacts.py", type: "references", weight: 0.9, reason: "Existing validator entrypoint to extend (no DB dependencies)" }
    - { to: "channels/42/threads/1006/20260318_170000_lilith_review_task_val_001_validator-design.md", type: continues, weight: 1.0, reason: "V-THREAD-001 next artifact in thread" }
lupopedia.footer:
  version: "4.0.81"
  last_verified: "20260318"
  last_verified_by: "hephaestus"
  orchestrator: "wolfie"
  next_action:
    - "Implement V-TODO-001..015 and V-PLAN-001..009 as file-based checks with clear ERROR/WARN output."
---

# file: HEPHAESTUS directive — task_val_001 kickoff (Option A validator enforcement) — channel 42 thread 1006

This output complies with Lupopedia Constitutional Root Rules.

## 1. Validation coverage plan (V-TODO + V-PLAN rules)

### 1.1 TODO.md Global Task Registry (Option A)

Validators will cover **all** of ATHENA’s TODO spec rules:

- **Structural / header-level**
  - **V-TODO-001**: `## Global Task Registry (Option A)` section exists exactly once in `TODO.md`.
  - **V-TODO-002**: Registry table header row matches canonical header (11 columns; exact names; exact order).
  - **V-TODO-003**: Each registry row has exactly 11 pipe-separated fields; no multiline cells.

- **Per-column value rules**
  - **V-TODO-004**: `task_id` matches `^[a-z0-9_]+$`.
  - **V-TODO-005**: `task_id` unique within registry table.
  - **V-TODO-006**: `owner_actor` is `-` or `^\d+:[a-z0-9_]+$` and not multi-valued.
  - **V-TODO-007**: `lifecycle_state` ∈ `{open,active,blocked,resolved,archived}`.
  - **V-TODO-008**: `status` ∈ `{planned,in_progress,blocked,complete,archived}`.
  - **V-TODO-009**: (`lifecycle_state`, `status`) matches strict mapping table.
  - **V-TODO-010**: If `lifecycle_state` ∈ `{active,blocked,resolved,archived}` then `thread_id` is numeric and `owner_actor` ≠ `-`.
  - **V-TODO-011**: If `lifecycle_state=open`, `thread_id` MAY be `-`; if `thread_id=-` then `owner_actor` MUST be `-`.
  - **V-TODO-012**: `priority` ∈ `{P0,P1,P2,P3}`.
  - **V-TODO-013**: `created_utc` and `updated_utc` match `^\d{8}_\d{6}$`.
  - **V-TODO-014**: `updated_utc` ≥ `created_utc` lexicographically.
  - **V-TODO-015**: `primary_artifact` is `-` or relative `.md` path (no absolute paths).

- **Ordering rule (warn-level)**  
  - **W-TODO-001**: Deterministic ordering (priority, then lifecycle_state, then task_id) – reported as **WARN** only in this task.

### 1.2 plan.md Strategic Roadmap (Option A)

Validators will cover **all** of ATHENA’s plan spec rules:

- **Phase + structure**
  - **V-PLAN-001**: At least one `## Phase` section exists.
  - **V-PLAN-002**: Each `## Phase N — ...` section has required fields in order: `Depends on`, `Completion when`, `Registry links`.

- **Field semantics**
  - **V-PLAN-003**: `Depends on` expression matches allowed forms (`nothing`, `Phase N`, `Phase N + Phase M + ...`), with no time/fuzzy language.
  - **V-PLAN-004**: `Completion when` contains at least one checklist item (`- [ ]`).
  - **V-PLAN-005**: Each `Registry links` entry contains `task_id: <task_id>`.

- **Identity / anti-registry rules**
  - **V-PLAN-006**: No prompt IDs used as primary identity outside of explicitly labeled **view** section (`Prompt queue (view; non-authoritative)`).
  - **V-PLAN-007**: No `thread_id` referenced without a `task_id` on the same line.
  - **V-PLAN-008**: Every `task_id` referenced in `plan.md` exists in TODO’s Global Task Registry (cross-file check).
  - **V-PLAN-009**: `plan.md` must not contain a registry table with canonical header row (reject if plan duplicates TODO registry).

## 2. Parser strategy (deterministic Markdown parsing)

### 2.1 TODO.md registry table parsing

- **Approach**:
  - Read `TODO.md` as text.
  - Locate the `## Global Task Registry (Option A)` heading.
  - From the first table header row under that heading:
    - Capture header row and confirm exact match.
    - Collect subsequent rows until first non-table line.
  - Parse rows by splitting on `|`, trimming whitespace, and discarding leading/trailing empties.
- **Determinism**:
  - No Markdown library; pure string/regex to avoid dependencies.
  - Enforcement that every row has exactly 11 cells prevents ambiguous parsing.

### 2.2 plan.md structure parsing

- **Phase and section detection**:
  - Use regexes anchored on `^## Phase` and well-known subheadings (`**Depends on:**`, `**Completion when:**`, `**Registry links:**`) to segment each phase.
- **Prompt queue view parsing**:
  - Locate the section heading `## Prompt queue (view; non-authoritative)` and parse the adjacent table.
  - Enforce that prompt rows reference `task_id: ...` in the same cell/line.

### 2.3 Shared utilities

- Reusable helpers (in Python, inside `validate_channel_artifacts.py` or a sibling module):
  - `parse_markdown_table(lines) -> (header_cells, [row_cells...])`
  - `extract_sections(text, heading_pattern)` for simple heading-based segmentation.
  - `extract_task_ids_from_plan(text)` and `extract_task_ids_from_todo(text)` for cross-file comparison.

## 3. Cross-file validation approach (TODO.md ↔ plan.md)

- **Step 1**: From TODO registry, build a set `registry_task_ids` (all `task_id` values in the canonical table).
- **Step 2**: From `plan.md`, collect:
  - all task_ids referenced in `Registry links` lists,
  - all `task_id: ...` occurrences in the prompt queue view.
- **Step 3**: Compare:
  - **Error** (reject) for any `plan_task_id` not in `registry_task_ids` (V-PLAN-008).
  - **Optional future**: warn when a registry row has no appearances in `plan.md` (not required by current spec; I will not enforce this unless doctrine is updated).

No DB access is involved; all checks are file-based.

## 4. Output format (errors vs warnings)

Validators will report results as **plain text lines** to stdout, following existing channel validator style:

- **Error lines**:
  - Prefixed with a stable error code and include file path + context.
  - Example:  
    - `TODO_ERROR[V-TODO-004]: TODO.md (row 5) invalid task_id 'TaskPlan001' (must match ^[a-z0-9_]+$)`
    - `PLAN_ERROR[V-PLAN-009]: plan.md contains registry-like header row; plan must not be a task registry`
- **Warning lines**:
  - Prefixed with `TODO_WARN[...]` or `PLAN_WARN[...]`.
  - Example:  
    - `TODO_WARN[W-TODO-001]: TODO.md registry rows not in canonical sort order`
- **Summary line**:
  - At the end of validation run, print a summary:
    - `option_a_validate: N error(s), M warning(s)`
- **Exit codes**:
  - **0** when there are **no errors** (warnings allowed).
  - **non-zero** when **any error** exists (reject/structural violations).

Initial enforcement posture (as requested):

- **REJECT (errors)**:
  - All V-TODO-001..015 and V-PLAN-002..009.
  - V-PLAN-001 may start as warn or error depending on how minimal plan is allowed to be; per spec, I will treat it as **WARN** initially and keep that explicit in code.
- **WARN (non-blocking)**:
  - W-TODO-001 (ordering rule).
  - V-PLAN-001 (presence of at least one phase) until plan is stable.

## 5. Known risks and edge cases

- **Legacy / transitional rows in TODO.md**:
  - Placeholder `task_deferred_000x` rows and prompt-derived tasks may temporarily violate “single owner” or “numeric thread_id for non-open” rules as WOLFIE and follow-up tasks clean them up; validators must still report these truthfully without mutating files.
- **Non-registry “view” sections**:
  - TODO’s Release Blockers / Deferred Work sections remain; validators must ignore them for registry structural checks and treat them as narrative views only.
- **Plan prompt queue semantics**:
  - Prompt queue is explicitly labeled view; validators must enforce anti-registry and anti-primary-identity rules **without** forbidding prompt ids in that view.
- **Formatting drift**:
  - Minor editorial changes in TODO/plan could break strict parsing; the parser must be robust to extra blank lines but strict about header names/order.

No TODO.md or plan.md content changes will be made as part of this kickoff; subsequent artifacts in this thread will implement the validator logic and report current violations without altering source files.

