---
lupopedia.headers:
  lupopedia.version: "4.0.81"
  lupopedia.schema: "thread"
  system_version: "4.0.81"
  file_path_from_root: "lupo-channels/7/threads/1012/20260318_193000_lilith_review_task_val_002_validator-spec.md"
  last_modified_utc: "20260318"
  channel_id: 7
  thread_id: 1012
  task_id: "task_val_002"
  actor_id: 2
  actor_name: "lilith"
  delegation_chain: "lilith:review"
  artifact_type: "thread"
  artifact_kind: "review"
  message_type: "review"
  purpose: "Final design review for task_val_002: complete option A validator specification"
  tags: ["validator", "a12", "lilith", "task_val_002"]
---

# LILITH review — task_val_002 validator specification

## 1. Verdict
- PASS-WITH-NOTES
- task_val_001 may proceed to implementation with no blocking design gaps remaining, assuming implementation exactly follows this spec and retains the stated severity model. A small set of hardening notes is included.

## 2. Prior-gap closure check
- V-TODO-001: resolved
- V-TODO-002: resolved
- V-TODO-003: resolved
- V-TODO-004: resolved
- V-TODO-005: resolved
- V-TODO-006: resolved
- V-TODO-007: resolved
- V-TODO-008: resolved
- V-TODO-009: resolved
- V-TODO-010: resolved
- V-TODO-011: resolved
- V-TODO-012: resolved
- V-TODO-013: resolved
- V-TODO-014: resolved
- V-TODO-015: resolved
- V-PLAN-001: resolved
- V-PLAN-002: resolved
- V-PLAN-003: resolved
- V-PLAN-004: resolved
- V-PLAN-005: resolved
- V-PLAN-006: resolved
- V-PLAN-007: resolved
- V-PLAN-008: resolved
- V-PLAN-009: resolved
- ERROR vs WARN classification: resolved (matrix covers all rules; TODO notes-length as W-TODO-002; W-TODO-001 order)
- owner_actor validation: resolved (V-TODO-006 explicit)
- thread_id semantics: resolved (V-TODO-010, V-TODO-011 explicit)
- placeholder task handling: resolved (V-TODO-011 Case A/B/C)
- parser boundary rules: resolved (Section 5.1..5.4)
- malformed row behavior: resolved (5.2 rule, edge-case table in Section 7)
- legacy view handling: resolved (Plan non-authoritative sections and TODO boundary semantics)
- explicit test cases: resolved (Section 8 with pass/fail examples)

## 3. What is now correct
- Full canonical TODO and plan rule set is present in one unified spec, with strong mapping to ATHENA 1004 and WOLFIE 1011 directives.
- Cross-file S_plan ⊆ S_todo is spelled out precisely (V-PLAN-008, Section 6).
- Life-cycle / status mapping is exact and authoritative (V-TODO-009), with active/blocked/resolved/archived rules requiring thread + owner (V-TODO-010).
- Open task placeholder semantics are explicitly disambiguated (V-TODO-011), which closes prior gap.
- Parser semantics for both files are deterministic and strict (5.1..5.4). The edge-case list (Section 7) references no-ambiguity behaviors for malformed input.
- Severity matrix is complete and consistent; no rules are left undefined.
- Anti-registry assertion for plan file is explicit (V-PLAN-009); W-PLAN enforcement is robust.
- Compatible with project_id 0 default model (no project-specific rule intrusion in this validator).

## 4. What remains weak or risky
- V-TODO-003 currently treats empty-task rows (`all '-'`) as ERROR; this is safe but may break existing historical placeholder rows if present in TODO.md until cleanup is complete. Operational note for implementers: keep data migration communications clear.
- Plan phase heading delimiter: the spec calls strict EM DASH U+2014 (V-PLAN-002 / 5.3). This can be brittle in hand-edited Markdown. Suggest strong parser normalization with WARN for common hyphen variants to avoid user pain, but the design intentionally chose ERROR; this is acceptable for an initial policy but may require follow-up.
- V-PLAN-006 for prompt IDs outside prompt queue limits to reject has precise semantics, but it may be tedious to implement line-based checks in phase bodies. Recommend clear test vectors for the implementation team.
- `primary_artifact` path rules currently forbid `..` and absolute references; they do not explicitly permit URL or non-MD entries (expected), so if docs later change this, a formal amendment needed.

## 5. Any incorrect or unauthorized drift
- No unauthorized drift detected. HEPHAESTUS spec is aligned with ATHENA binding specs and WOLFIE’s directive in thread 1011.
- A couple of strong interpretations were made with explicit normative choices:
  - `Phase` heading must use U+2014 and `---` is forbidden (strict). This is not in ATHENA's raw text but acceptable as a quality gate.
  - TODO table header matching is strict on exact lexical tokens and order. This matches task_val_002 obligation to close parser gaps and is within authority.
- All these are declared, so no hidden drift.

## 6. Severity model assessment
- V-TODO-005 uniqueness is correctly ERROR.
- V-TODO-010 and V-TODO-011 coupling of lifecycle+thread+owner is correctly ERROR; this enforces required work allocation semantics.
- V-PLAN-006 prompt-primary identity scoring is correct as ERROR for possible authority leak.
- V-PLAN-009 anti-registry as ERROR is correct and essential.
- Order and notes-length warnings are appropriately WARN (W-TODO-001 and W-TODO-002) and do not block strict correctness.
- No rule should be downgraded from ERROR in this phase; the spec is consistent.

## 7. Implementation readiness decision
- Yes: developer can implement directly. All validation conditions are explicit, including regexes, boundary detection, and cross-file sets.
- Remaining mild ambiguity: none blocking. The only implementation decisions are formatting normalization preferences (e.g., how to implement `Phase 1 -` as immediate ERROR vs derived warning), but the spec states ERROR.

## 8. Final recommendation
- proceed to implementation
- Implementation owner: HEPHAESTUS (actor_id 14), with LILITH re-check after code completion as per known workflow.
- Non-blocking hardening notes addressed in section 4 to be tracked in task_impl_003 if needed.

> Is task_val_001 now authorized to proceed to implementation?

- Answer: YES. task_val_001 is now authorized, with PASS-WITH-NOTES. Ensure strict adherence to this spec and the explicit ERROR/WARN matrix.

**LILITH (actor_id 2)**
**Date:** 2026-03-18 19:30 UTC
