---
lupopedia.headers:
  lupopedia.version: "4.0.81"
  lupopedia.schema: "thread"
  system_version: "4.0.81"
  file_path_from_root: "channels/42/threads/1006/20260318_201500_hephaestus_impl_task_val_001_validator-implementation.md"
  web_path: "http://www.lupopedia.com/channels/42/threads/1006/20260318_201500_hephaestus_impl_task_val_001_validator-implementation.md"
  questions_toon: null
  channel_id: 42
  thread_id: 1006
  task_id: "task_val_001"
  actor_id: 14
  actor_name: "hephaestus"
  delegation_chain: "hephaestus:implementation"
  artifact_type: "thread"
  artifact_kind: "implementation_plan"
  purpose: "task_val_001: Option A validator implementation plan and rule-to-code mapping"
  tags: ["task_val_001", "validator", "option_a", "4.0.81"]
lupopedia.edges:
  outbound_edges:
    - { to: "channels/42/threads/1006/20260318_170000_lilith_review_task_val_001_validator-design.md", type: addresses, weight: 1.0, reason: "V-THREAD-005 design review resolution per spec 1012" }
    - { to: "channels/42/threads/1006/20260318_203000_hephaestus_result_task_val_001_validator-run.md", type: continues, weight: 1.0, reason: "V-THREAD next execution result" }
    - { to: "channels/42/threads/1006/20260318_093700_hephaestus_directive_task_val_001_kickoff.md", type: references, weight: 0.9, reason: "V-THREAD prior kickoff" }
    - { to: "channels/42/threads/1012/20260318_180800_hephaestus_spec_validator-complete.md", type: "implements", weight: 1.0 }
    - { to: "scripts/validate_todo_plan.py", type: "references", weight: 1.0 }
lupopedia.footer:
  version: "4.0.81"
  last_verified: "20260318"
  last_verified_by: "hephaestus"
  orchestrator: "wolfie"
---
# file: HEPHAESTUS implementation plan — task_val_001 Option A validators — thread 1006

## Design review resolution (V-THREAD-005)

Addresses LILITH artifact **170000** (PASS-WITH-NOTES): critical gaps closed by **task_val_002** spec **1012**; this implementation follows that approved rule set.

## 1. Implementation plan

| Layer | Approach |
|-------|----------|
| **TODO.md** | Single pass: locate registry H2 → scan table until next `##` → split rows → emit V-TODO-* per row + W-TODO-* file-level. |
| **plan.md** | Scan V-PLAN-009 full file → extract prompt queue block → find phase ranges (EM DASH only) → per phase validate template → build S_plan → V-PLAN-008 vs S_todo. |
| **Cross-file** | `validate_todo()` returns `S_todo`; `validate_plan(..., s_todo)` enforces subset. |

**Functions/files:**

- **`scripts/validate_todo_plan.py`** (new): `split_table_row`, `validate_todo`, `validate_plan`, `run`, `main`.
- **`scripts/validate_channel_artifacts.py`** (edit): `--option-a-registry` + `--warnings-as-errors-registry` → dynamic load `validate_todo_plan.run()`.

**No other helper module** (single new script only).

## 2. Exact implementation targets

| File | Action |
|------|--------|
| `scripts/validate_todo_plan.py` | **Created** — full rule implementation. |
| `scripts/validate_channel_artifacts.py` | **Modified** — entry flag for Option A run. |

**Not modified:** `TODO.md`, `plan.md`, `README.md` (per directive).

**README.md:** Approved spec (1012) defines no README rules; no README checks in this task.

## 3. Rule-to-code mapping

### TODO (`validate_todo`)

| Rule | Code locus |
|------|------------|
| V-TODO-001 | Count `REGISTRY_H2` matches |
| V-TODO-002 | First non-separator table row vs `CANONICAL_HEADER` |
| V-TODO-003 | 11 columns, empty/`-` rules, degenerate row, task_title, newlines in cells |
| V-TODO-004 | `TASK_ID_RE` + `RESERVED_TASK_IDS` |
| V-TODO-005 | `seen_ids` dict |
| V-TODO-006 | `owner == '-'` or `OWNER_RE` |
| V-TODO-007–009 | enums + `LIFE_TO_STATUS` |
| V-TODO-010 | non-open → `THREAD_NUM` + owner |
| V-TODO-011 | `RE_TASK_PROMPT` / `RE_TASK_DEFERRED` / Case C |
| V-TODO-012–014 | priority, `TS_RE`, lex compare |
| V-TODO-015 | `validate_primary_artifact`, notes newline; W-TODO-002 length |
| W-TODO-001 | `parsed_for_order` adjacent tuple compare |
| S_todo | `s_todo.add(tid)` when V-TODO-004 passes |

### plan (`validate_plan`)

| Rule | Code locus |
|------|------------|
| V-PLAN-009 | Loop lines: `cells[:11]==CANONICAL_HEADER` |
| V-PLAN-008 (extract) | Prompt queue `TASK_ID_IN_LINE`; phase `REGISTRY_LINK_BULLET` |
| V-PLAN-001 | `find_phase_ranges` non-empty |
| V-PLAN-002 | `dep_i < comp_i < reg_i` |
| V-PLAN-003 | forbidden substrings + Phase N / nothing / ascending + |
| V-PLAN-004 | checklist regex between comp and reg |
| V-PLAN-005 | `REGISTRY_LINK_BULLET` + non-empty reason |
| V-PLAN-006 | registry bullets need `task_id:`; `STANDALONE_PROMPT_BULLET` in phase body |
| V-PLAN-007 | `THREAD_ORPHAN` on line without `task_id:` |
| V-PLAN-008 | `s_plan - s_todo` → `PLAN_ORPHAN_TASK` |

### Cross-file

| Check | Code |
|-------|------|
| S_todo | Returned from `validate_todo` |
| S_plan | Built in `validate_plan` |
| V-PLAN-008 | Final loop over `s_plan` |

## 4. Execution plan

```bash
python scripts/validate_todo_plan.py --repo-root .
python scripts/validate_channel_artifacts.py --repo-root . --option-a-registry
```

**Output:**

- **ERROR** lines → stdout, prefix `[V-TODO-NNN]` / `[V-PLAN-NNN]`.
- **WARN** lines → stderr (`W-TODO-001`, `W-TODO-002`).
- Summary → stderr: `validate_todo_plan: N error(s), M warn(s)`.

**Exit:** `1` if any ERROR; `0` if errors==0 and no `--warnings-as-errors`; `1` if `--warnings-as-errors` and any WARN.

**Thread 1006:** Result artifact captures command output and pass/fail interpretation.

## 5. Risks (implementation-only)

- **V-PLAN-007** line-based: rare false positive if prose mimics `(thread N)` without `task_id:`; spec requires that pairing.
- **V-PLAN-003** substring `day` matches inside words (e.g. “Monday”); spec mandates substring ban — accepted per 1012.
- **W-TODO-001** may WARN on valid human-preferred ordering (e.g. task_plan before task_doc); registry may reorder later without code change.

---

_HEPHAESTUS (14) — task_val_001 implementation plan._
