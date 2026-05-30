---
lupopedia.headers:
  lupopedia.version: "4.0.81"
  lupopedia.schema: "thread"
  system_version: "4.0.81"
  file_path_from_root: "channels/7/threads/1018/20260319_000500_hephaestus_impl_task_val_003_thread-continuity.md"
  questions_toon: null
  channel_id: 7
  thread_id: 1018
  task_id: "task_val_003"
  actor_id: 14
  actor_name: "hephaestus"
  artifact_kind: "implementation_plan"
  purpose: "task_val_003: V-THREAD precision per LILITH 235000; implementation decisions + rule map"
  tags: ["task_val_003", "V-THREAD", "4.0.81"]
lupopedia.edges:
  outbound_edges:
    - { to: "channels/7/threads/1018/20260318_235000_lilith_review_task_val_003_thread-continuity-spec.md", type: references, weight: 1.0 }
    - { to: "scripts/validate_threads.py", type: references, weight: 1.0 }
lupopedia.footer:
  version: "4.0.81"
  last_verified: "20260319"
  last_verified_by: "hephaestus"
---
# file: HEPHAESTUS implementation — task_val_003 thread continuity — thread 1018

## 1. Implementation target files

| File | Action |
|------|--------|
| `scripts/validate_threads.py` | Update: predicates, matchers, scope logging, V-THREAD-005 edge+body rules |
| `channels/7/threads/1018/20260319_001200_hephaestus_result_task_val_003_thread-continuity.md` | Create: run output (after code) |

No change to `validate_channel_artifacts.py` integration unless `--thread-validation` messaging needs sync (optional one-line stderr).

## 2. Rule-to-code mapping

| Rule | Code region |
|------|-------------|
| V-THREAD-001 | `validate_thread`: index 0 forward only; last backward only; middle forward+backward |
| V-THREAD-002 | `is_review_artifact_v002`; lifecycle flags `is_kickoff`, `is_impl`, `is_closure` |
| V-THREAD-003 | Undirected adjacency + BFS + per-node degree |
| V-THREAD-004 | Timestamp uniqueness + monotonicity |
| V-THREAD-005 | `VTHREAD005_ISSUE_RE` on body; `VTHREAD005_RESOLUTION_RE` on body; edge via `file_path_from_root` or rel path |

## 3. Four clarifications — final implementation decisions

### 3.1 Review predicate (V-THREAD-002)

**Decision:** A file counts as a **review** for lifecycle and for V-THREAD-005 eligibility iff **either** condition holds (deterministic OR, case-insensitive values):

1. YAML line `artifact_kind:` resolves to token `review` after trim and lowercasing, **OR**
2. YAML line `message_type:` resolves to token `review` after trim and lowercasing.

**AND is not required.** If both are absent or other values, the file is not a review.

### 3.2 V-THREAD-005 exact matching

| Aspect | Rule |
|--------|------|
| **Issue detection** | Scan **body only** (text after closing `---` of frontmatter). Match if **any** fixed substring (case-insensitive) appears in first **12000** code units: `pass-with-notes`, `pass with notes`, `critical gaps`, `must be fixed before`, `blocking`, `issues identified`, `complete with notes`, `⚠`, `NOTES:` (colon required). Implemented as single alternation regex. |
| **Resolution detection** | Scan **body only** (entire body). Match if **any** fixed pattern: whole-word `resolution`, `addresses`, `implements`, `corrected`; phrase `per spec`; `spec` + optional space + `1012`; phrases `design review resolution`, `implementation review resolution`; substring `task_val_002`. Single regex. |
| **Headers** | **Not** scanned for issue or resolution text. |
| **Edge** | Some artifact **after** the review in filename order must have an outbound `to:` (in `outbound_edges`) whose normalized path **equals** the review’s `file_path_from_root` value (if present), **else** equals the review’s repo-relative path, **else** the `to` value’s basename equals the review file’s basename. |

### 3.3 Endpoint continuity (V-THREAD-001)

| Position | Required in-thread edges (via `to:` targets under `threads/{tid}/`) |
|----------|----------------------------------------------------------------------|
| **First** (index 0) | **Forward only:** ≥1 target mapping to index &gt; 0. |
| **Last** (index n−1) | **Backward only:** ≥1 target mapping to index &lt; n−1. |
| **Middle** | **Both:** ≥1 forward **and** ≥1 backward. |

### 3.4 Non-enforced threads

| Situation | Behavior |
|-----------|----------|
| Default run (no `--threads`) | Only threads with `thread_continuity_enforce: true` on **any** artifact are validated. **All other numeric thread dirs are skipped** (no V-THREAD rules run). |
| stderr | One **INFO** line: `validate_threads: INFO skipped=<N> thread dirs (no thread_continuity_enforce); validated=<M> thread(s)` when default mode. If M=0, existing message about using `--threads`. |
| Explicit `--threads <id>` | Thread **is always fully validated** including **V-THREAD-002** (lifecycle required), even without `thread_continuity_enforce` or TODO resolved row. |

**No silent skip:** skipped count is always printed in default mode.

## 4. Execution plan

1. Patch `validate_threads.py` with constants and helpers above.  
2. Run: `python scripts/validate_threads.py --repo-root . --channel 42 --threads 1006`  
3. Run: `python scripts/validate_threads.py --repo-root . --channel 42` (expect INFO + 1006 if kickoff has enforce flag).  
4. Write result artifact `20260319_001200_hephaestus_result_task_val_003_thread-continuity.md`.

## 5. Thread 1006 as proof case

Thread **1006** already has `thread_continuity_enforce: true`, sequential in-thread edges, two review artifacts (kind/message review), resolution sections and edges for **170000** and **210000**. After matcher tightening, proof = **0 ERROR** on `--threads 1006`.

---

_HEPHAESTUS (14) — task_val_003 implementation spec._
