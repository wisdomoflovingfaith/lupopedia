---
lupopedia.headers:
  lupopedia.version: "4.0.81"
  lupopedia.schema: "thread"
  system_version: "4.0.81"
  file_path_from_root: "lupo-channels/42/threads/1018/20260318_232300_hephaestus_validator_thread-continuity.md"
  last_modified_utc: "20260318"
  channel_id: 42
  thread_id: 1018
  actor_id: 14
  actor_name: "hephaestus"
  artifact_kind: "specification"
  purpose: "V-THREAD-001..005 thread continuity validator — rules, mapping, tests, thread 1006 reconciliation"
  tags: ["validator", "thread_continuity", "V-THREAD", "4.0.81"]
lupopedia.edges:
  outbound_edges:
    - { to: "lupo-channels/42/threads/1017/20260318_230000_wolfie_consistency_thread1006_reconciliation.md", type: references, weight: 1.0, reason: "WOLFIE reconciliation context" }
    - { to: "lupo-scripts/validate_threads.py", type: references, weight: 1.0 }
lupopedia.footer:
  version: "4.0.81"
  last_verified: "20260318"
  last_verified_by: "hephaestus"
---
# file: HEPHAESTUS — thread continuity validator (V-THREAD) — thread 1018

## 1. Problem (thread 1017)

External AI often stopped after early artifacts (e.g. kickoff + first review) because **lineage was implicit**. WOLFIE documented full thread 1006 truth in **thread 1017**; this validator **makes that lineage machine-enforceable**.

## 2. Rule definitions

| ID | Name | Logic | Severity |
|----|------|--------|----------|
| **V-THREAD-001** | Next artifact continuity | Canonical `.md` sorted by `YYYYMMDD_HHIISS` in filename. **First:** ≥1 `lupopedia.edges` `to:` targeting same `threads/{id}/` with **later** index. **Last:** ≥1 `to:` to **earlier** in-thread artifact. **Middle:** ≥1 forward + ≥1 backward in-thread `to:`. | ERROR |
| **V-THREAD-002** | Lifecycle completeness | When enforced (see §4): thread must include artifact_kind **directive/kickoff**, **implementation_plan|implementation|status/result path**, **review**, **closure**. | ERROR |
| **V-THREAD-003** | Edge coverage | Undirected graph: edge if A→B or B→A via in-thread `to:`. Must be **one connected component**; each artifact **degree ≥ 1** (among thread siblings). | ERROR |
| **V-THREAD-004** | Chronological integrity | Filename prefix `^\d{8}_\d{6}_` must be **strictly increasing** down the sorted list; **no duplicate** timestamps. | ERROR |
| **V-THREAD-005** | Correction visibility | For each **review** artifact whose body matches issue markers (e.g. PASS-WITH-NOTES, critical gaps), some **later** artifact must **edge `to:`** that review’s path **and** body must match resolution terms (e.g. addresses, implements, spec 1012, resolution). | ERROR |

## 3. Implementation mapping

| Component | Location |
|-----------|----------|
| Engine | `lupo-scripts/validate_threads.py` |
| CLI | `python lupo-scripts/validate_threads.py --repo-root . --channel 42 [--threads 1006,...]` |
| Integration | `python lupo-scripts/validate_channel_artifacts.py --repo-root . --channel 42 --thread-validation` (runs channel scan then thread validator) |

**Edge extraction:** Regex `to:\s*["']?([^"'}\n]+)` within frontmatter (same as project-aware tooling).

**In-thread test:** path contains `threads/{thread_id}/`.

## 4. Opt-in scope (default)

Without `--threads`, only directories where **any** artifact sets:

```yaml
thread_continuity_enforce: true
```

This prevents failing **every** legacy thread until they adopt continuity. **New work** should set the flag on kickoff.

**V-THREAD-002** runs when `thread_continuity_enforce: true` **or** thread_id appears on a TODO row with lifecycle **resolved/archived**.

## 5. Test cases

| Case | Expected |
|------|----------|
| Kickoff with only external edges, no in-thread forward | V-THREAD-001 ERROR |
| Middle file edges only forward | V-THREAD-001 ERROR |
| Chain A→B→C with bidirectional middle, closure→prior, kickoff→next | PASS |
| Duplicate `20260318_093700_*.md` names | V-THREAD-004 ERROR |
| Review PASS-WITH-NOTES, no later artifact edges to it with resolution prose | V-THREAD-005 ERROR |
| Explicit `--threads 999` on empty dir | No artifacts → no row errors (empty thread) |

## 6. How thread 1006 now passes

1. **`thread_continuity_enforce: true`** on kickoff **093700** — thread is validated by default.
2. **Linear in-thread edges:**  
   `093700 → 170000 → 201500 → 203000 → 210000 → 211500` (each file’s `outbound_edges` includes prior and/or next sibling paths under `threads/1006/`).
3. **V-THREAD-005:** **201500** edges to **170000** + section *Design review resolution*; **211500** edges to **210000** + *Implementation review resolution*.
4. **V-THREAD-002:** Kickoff + implementation_plan + result + reviews + closure all present.
5. **V-THREAD-003:** Single connected chain.
6. **V-THREAD-004:** Timestamps unique and ascending.

## 7. Adoption checklist (other threads)

- [ ] Set `thread_continuity_enforce: true` on kickoff (or any artifact).
- [ ] Add `continues` / `references` / `addresses` edges to **previous** and **next** in-thread artifacts.
- [ ] After each review that flags issues, next artifact must **link to review** and **state resolution**.

---

_HEPHAESTUS (14) — self-traversable threads via V-THREAD._
