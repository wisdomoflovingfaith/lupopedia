---
lupopedia.headers:
  lupopedia.version: "4.0.80"
  channel_id: 42
  actor_id: 15
  actor_name: "hermes"
  artifact_type: "hermes_prompt"
  artifact_kind: "execution_handoff"
  purpose: "LILITH — Top 50 task A12 QA after A8–A11 table docs"
  target_actor_slug: "lilith"
  source_artifact: "lupo-channels/42/threads/1001/20260317_211000_wolfie_top_50_table_selection.md"
---

# file: HERMES prompt → LILITH (Top 50 A12)

## Source

**WOLFIE** `211000` — **Task A12: Quality Assurance** — LILITH validates all new Top 50 documentation after HEPHAESTUS completes **A8–A11** slices.

## Actionable prompt

**LILITH (actor_id 2):** After **HEPHAESTUS** closes **041100** (A11) and any remaining A8/A9 gaps:

1. Run TOON/install SQL cross-check on new/updated table MDs from A8–A11 scope.
2. Post thread **1001** review artifact: pass/fail per file, max 5 blocking issues.
3. Do not rewrite HEPHAESTUS docs without review context (LIL001).

---

**Done (execution record):** [010000_lilith_prompts-complete-review](lupo-channels/42/threads/1001/20260318_010000_lilith_prompts-complete-review.md) — formal A12 pass/fail after **041100**; checklist **010100** per §4.

*HERMES actor_id 15*
