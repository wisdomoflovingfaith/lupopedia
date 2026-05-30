---
lupopedia.headers:
  lupopedia.version: "4.0.81"
  lupopedia.schema: "thread"
  system_version: "4.0.81"
  file_path_from_root: "channels/42/threads/1004/20260318_141356_athena_spec_todo_registry.md"
  web_path: "http://www.lupopedia.com/channels/42/threads/1004/20260318_141356_athena_spec_todo_registry.md"
  questions_toon: null
  channel_id: 42
  thread_id: 1004
  task_id: "task_plan_001"
  actor_id: 12
  actor_name: "athena"
  delegation_chain: "athena:strategy"
  artifact_type: "thread"
  artifact_kind: "specification"
  purpose: "Formal spec: TODO.md Global Task Registry (Option A) — structure, constraints, lifecycle mapping, validation IDs, and migration steps"
  tags: ["athena", "spec", "todo_md", "global_task_registry", "option_a", "task_plan_001", "validators", "migration", "4.0.81"]
lupopedia.edges:
  outbound_edges:
    - { to: "channels/42/threads/1004/20260318_141109_athena_strategy_task_plan_001_kickoff.md", type: "implements", weight: 1.0, reason: "Kickoff strategy accepted; this is formalization" }
    - { to: "channels/42/threads/1001/20260318_120000_wolfie_directive_task_planning-thread-allocation.md", type: "constrained_by", weight: 1.0, reason: "Option A binding" }
    - { to: "channels/42/threads/1001/20260318_110000_wolfie_directive_thread001-triage.md", type: "constrained_by", weight: 0.9, reason: "task_id vs thread_id separation; canonical filenames" }
    - { to: "channels/42/threads/1001/20260318_135527_athena_strategy_thread-lifecycle.md", type: "uses", weight: 1.0, reason: "Canonical lifecycle states" }
    - { to: "TODO.md", type: "replaces_structure_of", weight: 0.9, reason: "Defines the target structure for TODO.md without editing it here" }
lupopedia.footer:
  version: "4.0.81"
  last_verified: "20260318"
  last_verified_by: "athena"
  orchestrator: "wolfie"
---
# file: ATHENA specification — TODO.md Global Task Registry (Option A) — channel 42 thread 1004

This output complies with Lupopedia Constitutional Root Rules.

## 0. Scope (binding)

- **Option A is binding**: `TODO.md` is the **Global Task Registry**; threads are execution containers; `plan.md` is a roadmap (not a registry).
- This spec defines **structure + constraints + validator rules + migration steps** for `TODO.md`.
- This spec does **not** modify `TODO.md` and introduces **no DB dependency**.

## 1. Canonical Table Definition (copy-paste ready)

`TODO.md` MUST contain a section with this exact heading:

`## Global Task Registry (Option A)`

Immediately under that heading, `TODO.md` MUST contain a single Markdown table with this **exact header row** and **exact column order**:

| task_id | task_title | owner_actor | lifecycle_state | status | thread_id | priority | created_utc | updated_utc | primary_artifact | notes |
|---|---|---|---|---|---|---|---|---|---|---|

### 1.1 Row formatting rules (strict)

- Each task is exactly one table row.
- Each row MUST have exactly 11 pipe-delimited fields corresponding to the 11 columns above.
- Empty values are encoded as a single hyphen: `-` (not blank).
- No multiline cells (no `<br>`, no embedded newlines).
- Links are allowed only in `primary_artifact` and `notes`.

### 1.2 Allowed values per column (summary)

- `task_id`: `^[a-z0-9_]+$` (lowercase + digits + underscore only); MUST be unique in the table.
- `task_title`: non-empty plain text; no `|`; max 120 chars.
- `owner_actor`: one of:
  - `-` (only when lifecycle_state is `open` AND thread_id is `-`), OR
  - `^\d+:[a-z0-9_]+$` (e.g. `12:athena`)
- `lifecycle_state`: one of `open|active|blocked|resolved|archived`
- `status`: one of `planned|in_progress|blocked|complete|archived` (derived, see §3)
- `thread_id`: `-` or `^\d+$` (numeric only)
- `priority`: one of `P0|P1|P2|P3`
- `created_utc`, `updated_utc`: `^\d{8}_\d{6}$` (UTC, `YYYYMMDD_HHIISS`)
- `primary_artifact`: `-` or a relative path ending in `.md`
- `notes`: `-` or short text; may include relative `.md` links

## 2. Field Constraints (per column)

This section defines each column’s requiredness, allowed values, and validator expectations.

### 2.1 `task_id`

- **Required**: yes
- **Allowed**: `^[a-z0-9_]+$`
- **Validation rules**:
  - MUST be unique within the table
  - MUST NOT be `todo`, `plan`, `thread`, `prompt` (reserved words list is validator-configurable; initial set above)
- **Example**: `task_plan_001`

### 2.2 `task_title`

- **Required**: yes
- **Allowed**:
  - length 1–120
  - MUST NOT contain `|`
- **Example**: `Planning system spec (Option A)`

### 2.3 `owner_actor`

- **Required**: conditional
- **Allowed**:
  - `-`, or
  - `^\d+:[a-z0-9_]+$`
- **Requiredness rule**:
  - MUST be `-` only when `lifecycle_state=open` AND `thread_id=-`
  - MUST be non-`-` for `active|blocked|resolved|archived`
- **Single owner rule**:
  - exactly one value; no commas; no `&`; no multiple actors
- **Example**: `12:athena`

### 2.4 `lifecycle_state`

- **Required**: yes
- **Allowed**: `open|active|blocked|resolved|archived`
- **Example**: `active`

### 2.5 `status`

- **Required**: yes
- **Allowed**: `planned|in_progress|blocked|complete|archived`
- **Derived rule**: MUST match lifecycle mapping in §3.
- **Example**: `in_progress`

### 2.6 `thread_id`

- **Required**: conditional
- **Allowed**: `-` or numeric (`^\d+$`)
- **Requiredness rule**:
  - MUST be numeric for `active|blocked|resolved|archived`
  - MAY be `-` only for `open`
- **Example**: `1004`

### 2.7 `priority`

- **Required**: yes
- **Allowed**: `P0|P1|P2|P3`
- **Example**: `P0`

### 2.8 `created_utc`

- **Required**: yes
- **Allowed**: `YYYYMMDD_HHIISS` UTC
- **Example**: `20260318_141109`

### 2.9 `updated_utc`

- **Required**: yes
- **Allowed**: `YYYYMMDD_HHIISS` UTC
- **Constraints**:
  - MUST be ≥ `created_utc` lexicographically
- **Example**: `20260318_141356`

### 2.10 `primary_artifact`

- **Required**: yes (may be `-` only for unallocated draft rows created during migration staging)
- **Allowed**:
  - `-`, or
  - relative path to a `.md` file (no leading drive letters; no absolute paths)
- **Example**: `channels/42/threads/1004/20260318_141109_athena_strategy_task_plan_001_kickoff.md`

### 2.11 `notes`

- **Required**: yes (use `-` if empty)
- **Allowed**:
  - `-` or short text; max 240 chars recommended
  - MUST NOT contain newline
- **Example**: `Derived from WOLFIE allocation; kickoff accepted.`

## 3. Lifecycle Mapping (STRICT)

`TODO.md.status` MUST be derived from `lifecycle_state` by this exact mapping:

| lifecycle_state | status |
|---|---|
| open | planned |
| active | in_progress |
| blocked | blocked |
| resolved | complete |
| archived | archived |

### 3.1 Illegal values and rejection conditions

- Any `lifecycle_state` not in the allowed set is **reject**.
- Any `status` not in the allowed set is **reject**.
- Any row where `(lifecycle_state, status)` does not match the mapping table above is **reject**.

## 4. Registry Rules (global invariants)

### 4.1 Uniqueness

- `task_id` MUST be unique across all rows.

### 4.2 Single owner (TSK002 alignment)

- Each row MUST have exactly one owner_actor value (or `-` only in the explicit unallocated-open exception).
- If a row lists multiple owners (comma-separated or similar), that is **reject**.

### 4.3 Thread requirements per lifecycle

- If `lifecycle_state` is `active|blocked|resolved|archived` then `thread_id` MUST be numeric and `owner_actor` MUST NOT be `-`.
- If `lifecycle_state` is `open` then `thread_id` MAY be `-` and `owner_actor` MAY be `-`.

### 4.4 Deterministic ordering (MUST)

The registry rows MUST be ordered by:

1. `priority`: `P0`, then `P1`, then `P2`, then `P3`
2. `lifecycle_state` order: `active`, `blocked`, `open`, `resolved`, `archived`
3. `task_id` lexicographic ascending

Any deviation is **warning** initially (recommended), upgradeable to **reject** once migration stabilizes.

## 5. Validation Rules (machine-enforceable IDs)

These rules are written for later validator implementation.

- **V-TODO-001**: The `## Global Task Registry (Option A)` section MUST exist exactly once.
- **V-TODO-002**: The registry table header MUST match the canonical header (11 columns; exact names; exact order).
- **V-TODO-003**: Each registry row MUST contain exactly 11 columns (pipe fields) and no multiline cells.
- **V-TODO-004**: `task_id` MUST match `^[a-z0-9_]+$`.
- **V-TODO-005**: `task_id` MUST be unique within the registry table.
- **V-TODO-006**: `owner_actor` MUST be `-` or match `^\d+:[a-z0-9_]+$` and MUST NOT contain multiple owners.
- **V-TODO-007**: `lifecycle_state` MUST be one of `open|active|blocked|resolved|archived`.
- **V-TODO-008**: `status` MUST be one of `planned|in_progress|blocked|complete|archived`.
- **V-TODO-009**: `(lifecycle_state, status)` MUST match the strict mapping in §3.
- **V-TODO-010**: If `lifecycle_state` in `active|blocked|resolved|archived`, then `thread_id` MUST be numeric and `owner_actor` MUST NOT be `-`.
- **V-TODO-011**: If `lifecycle_state=open`, then `thread_id` MAY be `-`; if `thread_id=-` then `owner_actor` MUST be `-`.
- **V-TODO-012**: `priority` MUST be one of `P0|P1|P2|P3`.
- **V-TODO-013**: `created_utc` and `updated_utc` MUST match `^\d{8}_\d{6}$`.
- **V-TODO-014**: `updated_utc` MUST be ≥ `created_utc` lexicographically.
- **V-TODO-015**: `primary_artifact` MUST be `-` or a relative path ending in `.md` (no absolute paths).
- **V-TODO-016**: Deterministic ordering SHOULD be enforced as warning until post-migration; rule ID reserved as **W-TODO-001**.

## 6. Migration Specification (step-by-step, no ambiguity)

This defines how to convert the current `TODO.md` into the Option A registry format.

### 6.1 Migration inputs (source sections)

From the current `TODO.md`, extract rows from:

- `### Release Blockers (Must Complete for 4.0.81 RC)` table
- `### 4.0.81 Deferred Work (Non-blocking)` table

### 6.2 Migration output (target)

Create the new `## Global Task Registry (Option A)` section and its canonical table.

### 6.3 Mapping rules (old → new)

For each row in existing tables:

1. **Determine `task_id`**
   - If the work item already has a declared task id in a binding directive (e.g., `task_plan_001`, `task_doc_001`), use that exact value.
   - Otherwise, mint a deterministic placeholder task id using this template:
     - `task_prompt_<prompt_id>` where `<prompt_id>` is the leading digits in the prompt filename (example: prompt `041000_*` → `task_prompt_041000`).
   - Do not invent semantic names beyond this deterministic template during migration.

2. **Set `task_title`**
   - Use a short title derived from the existing row’s Notes/Prompt anchor text (truncate to 120 chars).

3. **Set `owner_actor`**
   - If the existing row has a concrete Owner, use `-` for now unless the owner can be expressed as `actor_id:slug` deterministically from already-known assignments.
   - If WOLFIE allocation exists for the task, use that owner (`12:athena` for `task_plan_001`).
   - If unknown, set `owner_actor` to `-` and keep lifecycle_state `open` with thread_id `-` until WOLFIE assigns.

4. **Set `lifecycle_state`**
   - existing status `pending` → `open`
   - existing status `partial` → `active` (unless notes explicitly indicate a dependency; then `blocked`)
   - existing status `done/complete` (if present) → `resolved`

5. **Set `status`**
   - derive from lifecycle mapping table (§3); never copy old terms like `partial`.

6. **Set `thread_id`**
   - If the work item is already allocated a thread in a binding directive, set that numeric thread id.
   - Otherwise set `-` (unallocated).

7. **Set `priority`**
   - Release blockers → `P0`
   - Deferred work → `P2` (unless WOLFIE later promotes)

8. **Set `created_utc` and `updated_utc`**
   - `created_utc`: use the earliest known timestamp from the associated directive artifact if available; otherwise use the current migration timestamp.
   - `updated_utc`: use the current migration timestamp.

9. **Set `primary_artifact`**
   - If there is a thread kickoff or allocation directive artifact, use that path.
   - Else use the prompt path already linked in the old TODO row.

10. **Set `notes`**
   - Copy minimal context: original prompt filename + any short note; keep single-line.

### 6.4 Preservation and discard rules

- **Preserve**:
  - prompt links (as primary_artifact or in notes)
  - version history section
  - human narrative sections (but mark them non-authoritative views)
- **Discard**:
  - duplicate owner/target columns in favor of a single owner_actor field in registry
  - non-deterministic status words (`partial`) as registry status

---

## 7. Validator posture (non-implementation guidance)

Recommended enforcement severity after migration:

- **Reject**: V-TODO-001 through V-TODO-015
- **Warn**: W-TODO-001 (ordering) until the registry stabilizes; later may be upgraded to reject.

_End of TODO.md Global Task Registry specification._
