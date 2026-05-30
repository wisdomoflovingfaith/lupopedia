---
lupopedia.headers:
  lupopedia.version: "4.0.81"
  lupopedia.schema: "thread"
  system_version: "4.0.81"
  file_path_from_root: "channels/42/threads/1019/20260319_031500_hephaestus_impl_task_val_004_project-validation.md"
  web_path: "http://www.lupopedia.com/channels/42/threads/1019/20260319_031500_hephaestus_impl_task_val_004_project-validation.md"
  questions_toon: null
  channel_id: 42
  thread_id: 1019
  task_id: "task_val_004"
  actor_id: 14
  actor_name: "hephaestus"
  delegation_chain: "hephaestus:implementation"
  artifact_type: "thread"
  artifact_kind: "implementation"
  purpose: "V-PROJECT validator implementation mapping (validate_vproject.py)"
  tags: ["hephaestus", "V-PROJECT", "task_val_004", "validator"]
lupopedia.edges:
  outbound_edges:
    - { to: "scripts/validate_vproject.py", type: "implements", weight: 1.0 }
    - { to: "channels/42/threads/1019/20260318_163836_athena_spec_task_val_004_project-validation.md", type: "implements_spec", weight: 1.0 }
lupopedia.footer:
  version: "4.0.81"
  last_verified: "20260319"
  last_verified_by: "hephaestus"
  orchestrator: "wolfie"
---
# file: HEPHAESTUS implementation — V-PROJECT (task_val_004) — thread 1019

## Spec → code mapping

| Rule | Implementation |
|------|----------------|
| **V-PROJECT-001** | Rows with `lifecycle_state ∈ {active, blocked, resolved, archived}`: `thread_id` must match `^[1-9][0-9]{0,17}$`; directory `channels/42/threads/{id}/` must exist. Staging INFO: `--report-staging` emits `V-PROJECT-001-INFO` for `open` + `planned` + `thread_id -`. |
| **V-PROJECT-002** | Per numeric thread dir: collect `task_id` from YAML front matter (`^\s*task_id:\s*["']?([a-z0-9_]+)`). One distinct value → dominant. Multiple → **002-MIX** (ERROR, or WARN if thread in `vproject_legacy_threads.txt` or `--allowlist`). Dominant must exist in TODO with matching `thread_id`. **002-LAG**: `active` + max closure ts from `_closure_` filenames / `artifact_kind: closure` ≥ `updated_utc` (YYYYMMDD_HHIISS). Resolved/archived: `thread_has_completion_semantics` — `artifact_kind ∈ {closure,status}`, or `_closure_` in filename, or body `\b(Task Closure\|CLOSED\|COMPLETE)\b`. |
| **V-PROJECT-003** | `plan.md` lines `^\s*-\s+task_id:\s+([a-z0-9_]+)(.*)$`; `(backlog)` in remainder → allow open + `-`. Else row must be execution-bound + numeric thread + dir exists. |
| **V-PROJECT-004** | Thread dirs on disk not equal to any registry `thread_id`: WARN default, ERROR if `--strict`. Waived if ID in `vproject_orphan_threads.txt` or `--allowlist`. |
| **V-PROJECT-005** | **005-DUP**: same `task_id` in multiple rows with different numeric `thread_id`. **005-OVERLAP**: two `active`+`in_progress` rows, same `(owner_actor, normalized_title)`, different `task_id`, notes lack `split_from:` / `parent_task_id:`. Title norm: lowercase, non-alnum→space, collapse space, first 80 chars. |
| **V-PROJECT-006** | Rows `lifecycle ∈ {resolved,archived}` or `status ∈ {complete,archived}` with numeric thread: closure via `_closure_` in name, or `(artifact_kind ∈ {closure,directive})` + completion regex in fm/body, or body completion regex. Then **subprocess** `validate_threads.py --threads <comma-separated resolved thread ids>`. Non-zero exit → **006-THREAD-DIRTY**; V-THREAD error count = lines matching `^\[V-THREAD-`. |

## Data structures

- `rows[]`: dicts from TODO registry table (11 columns).
- `by_task`: `task_id → row`.
- `registry_threads`: set of numeric `thread_id` values from all rows.
- `tid_threads`: `task_id → set(thread_id)` for 005-DUP.
- `seen_pairs`: `(owner, norm_title) → task_id` for overlap.
- Allowlists: sets of thread ID strings from `vproject_legacy_threads.txt`, `vproject_orphan_threads.txt`, optional `--allowlist` (union with both).

## Integration

- **V-TODO / V-PLAN**: `subprocess` `validate_todo_plan.py` first. Exit non-zero → abort with `V-PROJECT-UPSTREAM ERROR` unless `--ignore-upstream-fail`.
- **V-THREAD**: `subprocess` `validate_threads.py --channel 42 --threads ...` for resolved batch.
- **CLI**: `--repo-root`, `--strict`, `--allowlist`, `--warnings-as-errors` (exit 2 if any WARN), `--ignore-upstream-fail`, `--report-staging`, `--skip-vthread` (debug).

## Assumptions

- Channel **42** and project boundary **lupopedia-core** are fixed (per ATHENA §1).
- Thread dirs: numeric names only (`^[1-9][0-9]{0,17}$`); legacy dirs like `4.0.x` are not under this tree in channel 42 threads listing.
- Plan registry links: bullets matching `- task_id: foo` (same shape as V-PLAN registry bullets).

## Test strategy

1. `python scripts/validate_todo_plan.py --repo-root .` (upstream).
2. `python scripts/validate_vproject.py --repo-root .`
3. With aligned TODO (e.g. `task_val_003` → 1018, `task_val_001` → 1006), expect 002 pass for those threads.
4. `--strict` + orphan allowlist: orphan threads in allowlist produce no 004 ERROR.
5. `--warnings-as-errors` → exit code 2 when only WARNs remain after fixing ERRORs.

## Test run (`python scripts/validate_vproject.py --repo-root . --ignore-upstream-fail`)

**Upstream:** `validate_todo_plan` exits 0 (1 row-order WARN).

**Expected failures (current repo, TODO/plan not modified per directive):**

- **V-PROJECT-002**: Threads 1006, 1009, 1010, 1012, 1014–1019 declare `task_id` values absent from the Global Task Registry (e.g. `task_val_001`, `task_val_003`, `task_val_004`). **1011** → **002-MIX WARN** (legacy allowlist: mixed `task_impl_002` vs `validator_and_project_alignment`).
- **V-PROJECT-003**: `plan.md` Phase 3 references `task_prompt_234200` without `(backlog)` while registry row is `open` + `thread_id -`.
- **V-PROJECT-004**: WARN for thread dirs 1001, 1002, 1006, 1009–1019 (no registry owner row with that `thread_id`). **1003, 1004, 1005** are owned.
- **V-PROJECT-006-THREAD-DIRTY**: Resolved threads **1003** and **1004** fail V-THREAD (edge graph / missing closure-review path per `validate_threads.py`).

**Alignment check (1006 / 1018):** Once TODO registers `task_val_001` → 1006 and `task_val_003` → 1018 with execution-bound lifecycle, **002** clears for those dirs; **004** clears for 1006/1018 when registry owns them. **006** applies when those tasks are `resolved`/`complete` and V-THREAD passes on those threads.

---

_HEPHAESTUS (actor_id 14) — V-PROJECT implementation for task_val_004._
