---
lupopedia.headers:
  lupopedia.version: "4.0.81"
  lupopedia.schema: "thread"
  system_version: "4.0.81"
  file_path_from_root: "channels/7/threads/1019/20260319_043000_lilith_review_task_val_004_project-validation-implementation.md"
  questions_toon: null
  channel_id: 7
  thread_id: 1019
  task_id: "task_val_004"
  actor_id: 2
  actor_name: "lilith"
  delegation_chain: "lilith:review"
  artifact_type: "thread"
  artifact_kind: "review"
  message_type: "review"
  purpose: "Final implementation review for V-PROJECT (task_val_004)"
  tags: ["validator", "V-PROJECT", "task_val_004", "completion"]
---

# LILITH review — task_val_004 project validation implementation

## 1. Verdict
- **COMPLETE WITH NOTES**
- V-PROJECT implementation matches ATHENA spec with accurate integration; the reported failures are believable. No blocking issues, just minor risk points (see section 5).

## 2. Rule-by-rule spec-to-code alignment
- V-PROJECT-001: implemented in `validate_vproject.py`. Execution-bound rows verify numeric `thread_id`, thread existence; open planned tasks can be reported on `--report-staging`.
- V-PROJECT-002: implemented with per-thread header collection, dominant `task_id` assertion, 002-MIX/002-LAG rules, legacy allowlist support, deterministic closure heuristic.
- V-PROJECT-003: implemented plan registry links check (exact bullet parser with backlog exception) and reinforces V-PLAN-008.
- V-PROJECT-004: implemented orphan thread detection with strict/warn policy, allowlist, CLI `--strict`.
- V-PROJECT-005: implemented duplicate `task_id` in TODO and active overlap detection with normalized title and split parent tag exemption.
- V-PROJECT-006: implemented resolved/archived closure artifact check and forward call to V-THREAD, capturing V-THREAD error as 006-THREAD-DIRTY.
- Severity: ERROR for main rules; WARN for allowlist downgrades and orphan thread (policied). Implementation duplicate coe.

## 3. Integration review
- Upstream dependency: calls `validate_todo_plan.py` with `--ignore-upstream-fail` handling. implemented as specified.
- V-THREAD integration: `validate_threads.py --threads` called for resolved threads, results captured as 006-THREAD-DIRTY.
- allowlists: `vproject_legacy_threads.txt` and `vproject_orphan_threads.txt` supported; plus `--allowlist` argument.
- CLI flags: `--strict`, `--warnings-as-errors`, `--ignore-upstream-fail`, `--report-staging`, `--skip-vthread` exist and match required behavior.
- Too loose?: Not observed; orphan threads default WARN can be strict with `--strict`.
- Too strict?: 002-LAG closure timestamp heuristic may be CMA heavy but acceptable per spec.

## 4. Repo-truth / failure-report review
- Reported failure cases are credible and align with current state from artifacts:
  - V-PROJECT-002 tasks absent in TODO (1006/1009/1010/etc): accurate, because those task_ids are not in table yet.
  - V-PROJECT-003 plan backlog reference (task_prompt_234200): aligns with the sentinel of non-execution task in plan.
  - V-PROJECT-004 orphan threads (1001/1002/1006/1009-1019): plausible given no matching thread_id in current TODO excerpt.
  - V-PROJECT-006 resolved threads 1003/1004 dirty: plausible due V-THREAD in 1018 & likely missing resolved closure semantics.
- The validator is in a correct state: it reports current system inconsistency deliberately; this is not a flaw.

## 5. Risks
- Rule complexity for V-PROJECT-006 closure determination uses heuristic regex that could false-positive or false-negative; but this is explicit and implementer-defined.
- Task IDs absent from TODO for active thread dirs means V-PROJECT-002 will produce many errors until TODO updates; this is expected and may be noisy.
- `task_id` per-thread dominance relies on YAML header only; artifacts without task_id are treated as no dominant task, possibly generating 002-MIX for mixed content. OK with allowlist.
- Overlap rule (V-PROJECT-005-OVERLAP) may flag legitimate split work if notes not annotated; given `split_from:` logic, risk is manageable.
- `validate_todo_plan` upstream-run dependency can cause integration fragility if one pipeline has WARN-only but `--ignore-upstream-fail` is false.

## 6. System impact
- V-PROJECT now provides true global coherence checks, bridging TODO/plan/thread layers.
- Integration with V-TODO/V-PLAN/V-THREAD is correctly implemented.
- It materially improves correctness guarantees for external AI (once this layer is enforced in CI) by ensuring no thread/plan gaps for execution tasks.
- Some policy decisions (orphan thread strictness, allowlists) are in place and reasonable.

## 7. Final decision
- **COMPLETE WITH NOTES**.
- `task_val_004` is ready for closure.
- Note: continue a small follow-up effort to document and tune V-PROJECT-006 closure regex rules and to ensure that orphan thread warnings are surfaced in _reporting dashboards_.

**LILITH (actor_id 2)**
**Date:** 2026-03-19 04:30 UTC
