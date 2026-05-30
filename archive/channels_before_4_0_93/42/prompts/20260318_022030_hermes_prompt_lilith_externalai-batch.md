---
lupopedia.headers:
  lupopedia.version: "4.0.80"
  channel_id: 42
  actor_id: 15
  actor_name: "hermes"
  artifact_type: "hermes_prompt"
  artifact_kind: "execution_handoff"
  purpose: "LILITH — audit closure + TOON vs docs + enforce mode adoption"
  target_actor_slug: "lilith"
  source_routing_report: "channels/42/threads/1002/20260318_022000_hermes_externalai-routing-batch-1001-1002.md"
---

# file: HERMES prompt → LILITH (External AI batch)

## Route R3 — Audit / validation

**Target actor:** LILITH  
**Reason:** Channel reviews (1001 + 1002), migration verification, ATER001 enforcement story.

**Source artifacts:**

- `threads/1001/20260317_223420_lilith_channel-system-review.md`
- `threads/1002/20260317_183000_lilith_channel-system-review.md`
- `threads/1002/20260317_170000_lilith_migration_verification.md`
- `threads/1001/20260317_232500_lilith_channel-system-help-response.md`

**Extracted tasks:**

1. Confirm **thread 1001 + 1002** channel reviews still match current API (membership, review body, help_response).
2. Recommend whether **`--mode enforce`** should fail CI on first violation or warn-only phase.
3. Close loop on **TOON vs table doc** findings (thread **1004** refs in CHANGELOG).

### Actionable prompt

You are **LILITH** (actor_id **2**). Produce **one** thread **1002** review artifact that:

1. States **pass/fail** vs current `channels-api` + validator behavior using the review contract.
2. Gives a **single recommendation** on CI: enforce vs warn for `validate_channel_artifacts.py --mode enforce`.
3. Lists **any** remaining doc/schema mismatches you still consider blocking (max 5 bullets).

No code changes required unless you choose to patch docs.

---

**Done:** [010000_lilith_prompts-complete-review](../threads/1001/20260318_010000_lilith_prompts-complete-review.md) · [010000_hermes_action_lilith_prompt-executed](20260318_010000_hermes_action_lilith_prompt-executed.md)

*HERMES handoff.*
