---
lupopedia.headers:
  lupopedia.version: "4.0.80"
  channel_id: 42
  actor_id: 15
  actor_name: "hermes"
  artifact_type: "hermes_prompt"
  artifact_kind: "execution_handoff"
  purpose: "HEPHAESTUS — close implementation gaps from threads 1001/1002 + External AI 235500"
  target_actor_slug: "hephaestus"
  source_routing_report: "channels/42/threads/1002/20260318_022000_hermes_externalai-routing-batch-1001-1002.md"
---

# file: HERMES prompt → HEPHAESTUS (External AI batch)

## Route R1 — Code / validation / scripts

**Target actor:** HEPHAESTUS  
**Reason:** Thread **1002** implementation artifacts (`hermes_channel-routing-implementation`, migration execution) and LILITH/help_response ask for tooling closure.

**Source artifacts (lineage):**

- `threads/1002/20260317_190000_hermes_channel-routing-implementation.md`
- `threads/1002/20260317_160000_hermes_migration_execution.md`
- `threads/1001/20260317_232500_lilith_channel-system-help-response.md` (optional `fix_channel_artifact.py`, pre-commit)
- `threads/1001/20260318_012000_wolfie_channel-hermes-mvp-stabilization.md` (Phase 3 automation)

**Extracted tasks:**

1. **Channels controller:** If not done, expose listing of `channels/42/prompts/` (or document API gap) per prior HERMES prompt `004502`.
2. **CI gateway:** Add documented hook or `run_tests` step invoking `python scripts/validate_channel_artifacts.py --channel 42 --mode enforce` (repo policy; coordinate with WOLFIE on when to fail build).
3. **Optional:** Stub or implement `scripts/fix_channel_artifact.py` — minimal: validate path + suggest canonical filename / thread_id (LILITH help_response §Minimum viable).
4. **Regression:** Run `php tests/unit/channel_thread_review_body_test.php` after any channels-api change.

### Actionable prompt

You are **HEPHAESTUS**. Execute in repo order:

1. Read `20260317_190000_hermes_channel-routing-implementation.md` and verify every claimed code path still matches `channels-api.php`, `Lupo_Channel_Message_Router.php`, and `Lupo_Channel_Artifact_Validator.php`. Patch drift if any.
2. Add **one** of: (A) `channels-controller` / admin read-only list of recent `prompts/*.md`, or (B) a short doc under `channels/42/prompts/README.md` stating “API listing deferred” with ticket ref in `TODO.md`.
3. Propose or add to `scripts/run_unit_tests.sh` (or CI doc) a single line to run `validate_channel_artifacts.py --mode enforce` when channel tree is touched.
4. Post a **thread 1002** artifact summarizing what shipped; **actor_id** = your registered actor (not HERMES).

---

*HERMES handoff — do not reply as HERMES.*
