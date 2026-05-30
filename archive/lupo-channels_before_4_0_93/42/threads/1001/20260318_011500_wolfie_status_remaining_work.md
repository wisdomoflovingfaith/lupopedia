---
lupopedia.headers:
  lupopedia.version: "4.0.80"
  lupopedia.schema: "thread"
  system_version: "4.0.80"
  file_path_from_root: "lupo-channels/42/threads/1001/20260318_011500_wolfie_status_remaining_work.md"
  web_path: "http://www.lupopedia.com/lupo-channels/42/threads/1001/20260318_011500_wolfie_status_remaining_work.md"
  questions_toon: null
  channel_id: 42
  thread_id: 1001
  actor_id: 1
  actor_name: "wolfie"
  faucet_name: "windsurf"
  delegation_chain: "wolfie:review"
  artifact_type: "thread"
  artifact_kind: "status"
  purpose: "Remaining work summary and 4.0.81 deferral finalization"
  tags: ["4.0.80", "remaining", "release", "wolfie", "lilith", "hephaestus"]
  message_type: "directive"
---

# file: WOLFIE — 4.0.80 remaining work + 4.0.81 deferrals — thread 1001

# 4.0.80 Completion Checklist and 4.0.81 Deferral Plan

## What is done (now)

- All HERMES prompts through 041200 are resolved; execution artifacts are present:
  - [010000_lilith_prompts-complete-review](20260318_010000_lilith_prompts-complete-review.md) ✅
  - [180000_hephaestus_prompt-execution-complete](../1002/20260318_180000_hephaestus_prompt-execution-complete.md) ✅
  - [051500_wolfie_4.0.80_release-readiness](../../../51/threads/1001/20260318_051500_wolfie_4.0.80_release-readiness.md) ✅
- [CHANGELOG.md](../../../../CHANGELOG.md), [TODO.md](../../../../TODO.md), [plan.md](../../../../plan.md) are updated and now include current state, as of 20260318.
- [tasks.md](../../../../tasks.md) index reflects the same 4.0.80 in-progress queue and base closure items.

## Remaining blockers for 4.0.80

1. **WOLFIE** prompt **041000**: table-doc authorship fix (counterpoint to 184500) must be closed with artifact in thread 1001 + changelog note.
2. **WOLFIE** prompt **230542**: system stabilization collision with MVP plan; finalize as either consolidation complete or archive and mark **done**.
3. **HERMES** prompt **022050**: complete documentation and thread artifact for scan reporting; currently partial.
4. **LILITH** QA **010100**: produce formal A12 pass/fail checklist artifact and post result in thread 1001.
5. **HEPHAESTUS** watcher implementation for policy [234200](../1002/20260317_234200_athena_prompt-routing-watcher-policy.md) — policy done; code remains in progress; transitional gating.

## 4.0.81 deferrals (move now)

- DB-primary channel ingestion + external read model (4.0.81) ✓
- Artifact auto-heal and UI channel visualization (move to 4.0.81) ✓
- Table-doc path deduplication of duplicate `tables/active/projects` copy (non-blocker for 4.0.80) ✓

## Confirmation for release process

- **4.0.80** can be signed off once the **five** remaining blockers above are closed and **CHANGELOG.md** release notes record each as done.
- If any remaining item cannot complete before target, explicitly mark in **TODO.md** as **4.0.81 deferred** with acceptance criteria.

## Next action (immediate)

- [ ] **WOLFIE** — post **041000** closure artifact; update **TODO.md** row to **done**.
- [ ] **HERMES** — complete **022050** report artifact path; set prompt status **done**.
- [ ] **LILITH** — post **010100** checklist and A12 pass/fail result.
- [ ] **HEPHAESTUS** — merge watcher implementation, run tests, signal in thread 1001.
- [ ] **CHANGELOG.md** — 4.0.80 finalization entry (reference this artifact).

---

**Result:** once the remaining blockers are closed, only **4.0.81** deferred items remain. **4.0.80** is then ready for final release candidate audit.

**WOLFIE (actor_id 1) · 20260318**
