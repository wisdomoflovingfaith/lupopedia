---
lupopedia.headers:
  lupopedia.version: "4.0.81"
  lupopedia.schema: "thread"
  system_version: "4.0.81"
  file_path_from_root: "lupo-channels/42/threads/1019/20260318_163836_athena_spec_task_val_004_project-validation.md"
  web_path: "http://www.lupopedia.com/lupo-channels/42/threads/1019/20260318_163836_athena_spec_task_val_004_project-validation.md"
  questions_toon: null
  channel_id: 42
  thread_id: 1019
  task_id: "task_val_004"
  actor_id: 12
  actor_name: "athena"
  delegation_chain: "athena:strategy"
  artifact_type: "thread"
  artifact_kind: "specification"
  purpose: "V-PROJECT layer: global coherence across TODO, plan, threads, and project scope (task_val_004)"
  tags: ["athena", "V-PROJECT", "task_val_004", "global_coherence", "validators", "4.0.81"]
lupopedia.edges:
  outbound_edges:
    - { to: "TODO.md", type: "validates_against", weight: 1.0 }
    - { to: "plan.md", type: "validates_against", weight: 1.0 }
    - { to: "lupo-scripts/validate_threads.py", type: "integrates_with", weight: 0.9, reason: "V-THREAD" }
lupopedia.footer:
  version: "4.0.81"
  last_verified: "20260318"
  last_verified_by: "athena"
  orchestrator: "wolfie"
---
# file: ATHENA specification — V-PROJECT global coherence (task_val_004) — thread 1019

This output complies with Lupopedia Constitutional Root Rules.

## 0. Purpose

**V-TODO**, **V-PLAN**, and **V-THREAD** prove **local** consistency (registry table, roadmap shape, single-thread internals). **V-PROJECT** proves **global** coherence:

- Every **execution-bound** task is anchored to **one real thread directory**.
- Every **thread** that claims a **task** is registered and consistent with **TODO** lifecycle.
- Every **plan** reference is backed by a **thread-backed** task in the same **project**.
- **No duplicate task↔thread bindings** and **no silent overlap** of active work across threads.
- **Resolved** tasks have **closure + V-THREAD clean** in that thread.

This is the layer that moves Lupopedia from “per-artifact valid” to a **self-consistent semantic OS** under one project boundary.

---

## 1. Scope and inputs

| Input | Role |
|-------|------|
| `TODO.md` | Parsed **Global Task Registry** table (post–Option A). |
| `plan.md` | Parsed **Phase** sections and **Registry links** (`task_id:` lines). |
| `lupo-channels/42/threads/{id}/` | Filesystem thread roots; scan `*.md` headers for `task_id`, `thread_id`. |
| `project_id` | For this spec, fixed **0** = **lupopedia-core**; channel **42** is the only coordination tree validated here. |

**Execution order (normative):**

1. Run **V-TODO** on `TODO.md`; abort V-PROJECT on hard V-TODO failures (optional policy: continue with degraded report).
2. Run **V-PLAN** on `plan.md`; same.
3. Build in-memory indexes (see §4).
4. Run **V-PROJECT-001 … 006**.
5. For threads that fail V-PROJECT lifecycle checks, run **V-THREAD** on those thread IDs (or full tree).

---

## 2. Rule definitions

### V-PROJECT-001 — Task ↔ Thread existence

**Statement:** Every registry row whose work is **execution-bound** MUST map to **exactly one** numeric `thread_id`, and the directory **`lupo-channels/42/threads/{thread_id}/`** MUST exist on disk.

**Execution-bound predicate (deterministic):**

`lifecycle_state ∈ { active, blocked, resolved, archived }`

**For those rows:**

- `thread_id` MUST match `^\d+$` (single numeric token; not `-`).
- Exactly one directory MUST exist: `lupo-channels/42/threads/{thread_id}/` (non-empty or empty dir both OK; “exists” = path is a directory).

**Staging rows (explicit exception):**

- Rows with `lifecycle_state = open` AND `status = planned` MAY have `thread_id = -`. They are **excluded** from V-PROJECT-001 **ERROR**; they MAY surface as **V-PROJECT-001-INFO** (“unallocated task”) when `--report-staging`.

**Rationale:** Matches current registry (e.g. `task_prompt_010100` with `-`) while enforcing **global coherence for all work that has left the planning queue**.

**Rejection:** ERROR if any execution-bound row has missing dir, non-numeric thread, or duplicate `task_id` → different threads (see V-PROJECT-005).

---

### V-PROJECT-002 — Thread ↔ Task alignment

**Statement:** For every thread directory under `lupo-channels/42/threads/{N}/`:

1. Collect **declared `task_id`** values from LUPOPEDIA headers of all `*.md` files (excluding optional `lupopedia.footer`-only if no header block — treat as no `task_id`).
2. Let **dominant `task_id`** for thread `N` be:
   - the **single** `task_id` value that appears in **≥1** artifact header in that thread, if exactly one distinct value exists;
   - if **multiple** distinct `task_id` values appear, ERROR **V-PROJECT-002-MIX** (mixed-scope thread; legacy threads may be WOLFIE-waived via allowlist file, see §6).

3. If dominant `task_id` exists:
   - That `task_id` MUST appear in **TODO.md** registry.
   - Registry row for that `task_id` MUST have `thread_id` equal to `N` (numeric string match).
   - Registry `lifecycle_state` MUST be **compatible** with thread semantics:
     - If any artifact in thread is **closure** / **resolved completion** and registry still `active`, ERROR **V-PROJECT-002-LAG** unless `updated_utc` predates closure (stale registry).
     - If registry says `resolved|archived`, thread MUST NOT contain kickoff-only with no completion path (heuristic: at least one `artifact_kind` in `{ status, closure }` or body declares completion — implementer-defined list; spec requires **deterministic** rule in code).

**Legacy threads (1001, 1002, …):** MAY be listed in **allowlist** `lupo-scripts/vproject_legacy_threads.txt` (one thread_id per line) → downgrade 002-MIX to WARN for those IDs only.

---

### V-PROJECT-003 — Plan ↔ Task ↔ Thread consistency

**Statement:** Every `task_id` referenced under **Registry links** in `plan.md` MUST:

1. Exist as a row in **TODO.md** registry (**V-PLAN-008** already; V-PROJECT re-asserts after 001 index).
2. Satisfy **V-PROJECT-001** for that row if the plan phase is marked **execution-active** (optional flag in plan template); **default strict rule:** every `task_id` in **Registry links** MUST be **execution-bound** (have numeric `thread_id` + dir exists) **OR** be explicitly tagged in plan as `backlog-only` with task_id matching TODO `open` row.

**Deterministic default (strict):**

- Extract all `task_id:` tokens from `plan.md` **Registry links** bullets.
- For each: registry row MUST have `thread_id` numeric and directory exists (same as 001).  
- **Exception:** If plan line contains literal `(backlog)` suffix, allow `open` + `thread_id -`.

---

### V-PROJECT-004 — Project scope enforcement

**Statement:** All validated paths live under **one project workspace**:

- **Project:** `project_id = 0`, **slug** `lupopedia-core` (conceptual).
- **Channel:** `42`.
- Any `thread_id` directory under `lupo-channels/42/threads/` is **in-project**; there is **no** second channel tree in scope for this validator pass.

**Orphan thread (ERROR or WARN per policy):**

- Directory `lupo-channels/42/threads/{N}/` exists.
- **No** registry row has `thread_id = N`.
- **No** allowlist entry for “historical container only.”

→ **V-PROJECT-004-ORPHAN**: thread filesystem exists without registry owner.

**Cross-project:** When multi-project ships, V-PROJECT repeats per `(project_id, channel_id)` root; rule 004 generalizes to “no thread outside declared project channel roots.”

---

### V-PROJECT-005 — Cross-thread duplication prevention

**Statement:**

1. **Same task_id, two threads:** Build map `task_id → set(thread_id)` from TODO registry (numeric thread only). For any `task_id` with **|set| > 1**, ERROR **V-PROJECT-005-DUP**.

2. **Overlapping unresolved tasks:** Build set of pairs `(owner_actor, normalized_title)` for rows with `lifecycle_state = active` and `status = in_progress`. If the same pair appears for **two different** `task_id` values **and** neither row declares `depends_on` / split parent in `notes` (deterministic substring `split_from:` or `parent_task_id:`), ERROR **V-PROJECT-005-OVERLAP**.

**Normalized title:** lowercase, collapse whitespace, strip punctuation to alnum+space, first 80 chars.

---

### V-PROJECT-006 — Lifecycle synchronization

**Statement:** For every TODO row with `lifecycle_state ∈ { resolved, archived }` (or `status ∈ { complete, archived }` per strict TODO mapping):

1. **Closure artifact:** Thread `N` MUST contain at least one markdown file whose header has `artifact_kind` in `{ closure, directive }` **and** body or header asserts task completion **OR** references WOLFIE closure (deterministic: filename contains `_closure_` OR body contains `Task Closure` / `CLOSED` / `COMPLETE` per implementer regex list — **must be documented in validator**).

2. **V-THREAD:** Run `validate_threads.py` (or equivalent) for thread `N` with same severity as CI; **zero ERROR** required for V-PROJECT-006 pass.

**If registry says `resolved` but V-THREAD fails:** ERROR **V-PROJECT-006-THREAD-DIRTY**.

---

## 3. Enforcement logic (algorithm sketch)

```
parse_todo() -> rows[]
parse_plan() -> plan_task_ids[]
index_task_to_thread = { r.task_id: r.thread_id for r in rows if r.thread_id is numeric }
index_thread_to_task = invert injective check

# 001
for r in rows:
  if r.lifecycle in {active, blocked, resolved, archived}:
    assert r.thread_id is numeric
    assert isdir(f"lupo-channels/42/threads/{r.thread_id}/")

# 002
for N in list_thread_dirs("lupo-channels/42/threads"):
  task_ids_in_thread = collect_header_task_ids(N)
  if len(unique(task_ids_in_thread)) > 1 and N not in legacy_allowlist:
    error MIX
  if dominant_task_id T:
    assert T in rows and rows[T].thread_id == str(N)

# 003
for tid in plan_task_ids:
  assert tid in rows
  assert 001 holds for tid unless plan marks backlog

# 004
for N in list_thread_dirs(...):
  if N not in values(index_task_to_thread) and N not in orphan_allowlist:
    warn/error ORPHAN

# 005
assert injective task_id -> thread_id from TODO
check owner/title overlap for active rows

# 006
for r in rows where r.lifecycle in {resolved, archived}:
  assert closure_artifact_exists(r.thread_id, r.task_id)
  assert vthread(r.thread_id) == OK
```

---

## 4. Integration with V-TODO, V-PLAN, V-THREAD

| Layer | V-PROJECT dependency |
|-------|----------------------|
| **V-TODO** | Supplies authoritative `task_id`, `thread_id`, `lifecycle_state`, `status`. V-PROJECT assumes registry parse success. |
| **V-PLAN** | Supplies `task_id` set from phases. V-PROJECT-003 extends V-PLAN-008 with thread/dir existence. |
| **V-THREAD** | Per-thread internal rules. V-PROJECT-006 **calls** V-THREAD for resolved/archived tasks. |

**Suggested CLI:** `python lupo-scripts/validate_vproject.py [--strict] [--allowlist path]`  
**CI:** Run after `validate_channel_artifacts` / TODO / PLAN / optional THREAD matrix.

---

## 5. Examples (current system)

### 5.1 Thread **1006** (`task_val_001`)

- **Registry:** `task_val_001` is not in the excerpted TODO table shown earlier; if still present elsewhere, 001 applies. **1006** holds WOLFIE **closure** (`20260318_211500_wolfie_closure_task_val_001_validator.md`) — satisfies **V-PROJECT-006** closure heuristic.
- **V-THREAD:** Result artifact in **1018** reports V-THREAD run on **1006** — **006** requires that run to be clean when task is marked resolved in TODO.

### 5.2 Thread **1018** (`task_val_003`)

- **1018** is the **thread continuity** implementation thread; headers declare `task_id: task_val_003`.
- **V-PROJECT-002:** Requires `task_val_003` row in TODO with `thread_id: 1018` when execution-bound; if TODO lists another thread for same task, **005-DUP** fires.
- **V-THREAD** precision work lives here; **006** applies when `task_val_003` moves to **resolved**.

### 5.3 Intentional **staging** violations (until allocation)

- Rows like `task_prompt_010100` with `thread_id: -` **pass** V-PROJECT-001 (excluded).  
- **003 strict:** If `plan.md` **Registry links** still reference `task_prompt_010100` without `(backlog)`, **003 fails** until thread allocated or plan tagged.

---

## 6. Allowlists and waivers (deterministic)

| File | Purpose |
|------|---------|
| `lupo-scripts/vproject_legacy_threads.txt` | Thread IDs allowed **mixed** `task_id` headers (historical). |
| `lupo-scripts/vproject_orphan_threads.txt` | Thread dirs with no registry row (infra only). |

WOLFIE directive path in artifact may supersede allowlist row for one release cycle (document in validator README).

---

## 7. Success condition

After **V-PROJECT** is implemented and enforced in CI:

- **Local** validity (TODO + PLAN + THREAD) **plus** **global** coherence (task↔thread↔plan↔project) holds for **lupopedia-core** channel **42**.
- External AI reading the repo can rely on: **every linked plan task** has a **real thread** and **resolved work** has **closure + clean thread**.

---

## 8. What this spec does NOT do

- Does not implement Python/PHP (HEPHAESTUS).  
- Does not modify `TODO.md` / `plan.md` / thread artifacts.  
- Does not define multi-repo federation beyond **project_id 0 + channel 42** scope for v1.

---

_ATHENA (actor_id 12) — V-PROJECT specification for task_val_004._
