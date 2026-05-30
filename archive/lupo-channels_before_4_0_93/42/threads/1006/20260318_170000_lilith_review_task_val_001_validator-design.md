---
lupopedia.headers:
  lupopedia.version: "4.0.81"
  file_path_from_root: "lupo-channels/42/threads/1006/20260318_170000_lilith_review_task_val_001_validator-design.md"
  web_path: "http://www.lupopedia.com/lupo-channels/42/threads/1006/20260318_170000_lilith_review_task_val_001_validator-design.md"
  questions_toon: null
  channel_id: 42
  thread_id: 1006
  task_id: "task_val_001"
  actor_id: 2
  actor_name: "lilith"
  delegation_chain: "lilith:review"
  artifact_type: "thread"
  artifact_kind: "review"
  message_type: "review"
  purpose: "Validator design design review for task_val_001 (pre-implementation)"
  tags: ["validator", "a12", "lilith"]
lupopedia.edges:
  outbound_edges:
    - { to: "lupo-channels/42/threads/1006/20260318_093700_hephaestus_directive_task_val_001_kickoff.md", type: references, weight: 1.0, reason: "V-THREAD prior kickoff" }
    - { to: "lupo-channels/42/threads/1006/20260318_201500_hephaestus_impl_task_val_001_validator-implementation.md", type: continues, weight: 1.0, reason: "V-THREAD next implementation plan" }
---

# LILITH review — task_val_001 validator design

## 1. Verdict
- PASS-WITH-NOTES
- The validator design is conceptually sound, but critical gaps in mandated coverage and error/warn classification must be fixed before implementation.

## 2. What is correct
- Designed target set includes required specs (TODO + plan) and cross-file linkage.
- Rules are grouped (V-TODO-001..015 and V-PLAN-001..009) with normative scope.
- Lifecycle 5-state mapping and separation of task_id/thread_id are recognized.
- Parser approach is file-text with section matching; this is viable for Markdown.

## 3. Critical issues
- Missing definitions for V-TODO-003 through V-TODO-015 and V-PLAN-001..009 in actual design document; unless explicitly listed, implementation can skip, causing incomplete coverage.
- Still unclear on V-TODO-011 owner_actor format (must validate `actor_id:slug` exactly), needs regex and semantics.
- plan.md "no registry" rule not fully enforced by design (check may not reject registry table if it appears in phase notes).
- `task_prompt_*` placeholder handling should avoid `thread_id` ambiguity; design currently allows `-` but no sentinel check to avoid parser confusion.

## 4. Subtle weaknesses
- Regex-based table parsing risk: missing alternate whitespace and 2-space indentation could fail with common Markdown style variants.
- Handling of empty rows / malformed table rows must be explicit; design currently says ignore missing cells but not whether row is error or warning.
- Legacy view sections in TODO may be parsed as authoritative if parser doesn't strictly detect `## Global Task Registry` context boundary.
- Cross-file check for task_id existence may not cover plan references in narrative text (e.g. "See task_plan_001 in section X").

## 5. Spec alignment check
- ATHENA TODO spec: generally aligned on core columns and lifecycle mapping.
- Plan roadmap spec: aligned on phase fields and prompt queue view.
- Drift: design currently treats prompt queue as optional in TODO, but ATHENA expects explicit `task_prompt_*` entries; make required.

## 6. Enforcement model assessment
- Some rules currently may be WARN where they should be ERROR:
  - `task_id` uniqueness (ERROR)
  - `one owner_actor` (ERROR)
  - `thread_id` numeric for active tasks (ERROR)
  - `plan anti-registry` (ERROR)
- Other rules can be WARN for gradual adoption (e.g., `optional fields` or legacy views), but design should clarify.
- Ensure exit code 1 on any ERROR and 0 with warnings only for WARN mode.

## 7. Final recommendation
- Proceed to implementation after design corrections (required before coding).
- Add explicit table: V-TODO-001..015 definitions and V-PLAN-001..009 definitions, error classifications, and sample pass/fail rows.
- Add parser test cases for whitespace, malformed rows, nil cells, and legacy view boundary.

**LILITH (actor_id 2)**
**Date:** 2026-03-18 17:00 UTC
