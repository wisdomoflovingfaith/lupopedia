---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: documentation
  when_updated: "20260404163220"
  file_path_from_root: "lupo-docs/implementations/36_rose_multi_persona_synthetic_dialog/decisions/20260404_162844_DECISION_rose_batch_every_10_messages.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/implementations/36_rose_multi_persona_synthetic_dialog/decisions/20260404_162844_DECISION_rose_batch_every_10_messages.md"
  last_modified_utc: "20260404163220"
  federation_node_id: 0
  channel_id: 42
  artifact_type: decision
  artifact_kind: product_constant
  purpose: "Default ROSE synthetic batch trigger — count of 10 organic messages per thread"
  actor_id: 102
  actor_name: cursor
  delegation_chain: "cursor:root"
lupopedia.footer:
  last_verified: "20260404"
  verified_by:
    identity_type: actor
    actor_id: 102
  verified_via:
    type: faucet
    faucet_slug: cursor
  orchestrator: "cursor:root"
---

# file: DECISION — ROSE batch every 10 messages — delegation: cursor:root — web_path: http://www.lupopedia.com/lupopedia/lupo-docs/implementations/36_rose_multi_persona_synthetic_dialog/decisions/20260404_162844_DECISION_rose_batch_every_10_messages.md

# DECISION: Default ROSE batch — every 10 organic messages

**Status:** Ratified for product documentation (constitution **§5.10.3**, **PRD 36** §4).

## Decision

The **default** trigger for a **ROSE synthetic choir** pass is **10 new organic messages** in the scoped thread (typically **`dialog_thread_id`**) since the **last completed** ROSE batch. **PHP** owns the counter and the decision to run; the model does **not**.

## Rationale

- **Token and cost control:** Fewer invocations than per-message synthesis.
- **Transcript coherence:** Enough context for multi-persona turns without constant interruption.
- **Noise:** Reduces “chorus fatigue” for operators and visitors when the feature is visitor-visible.

## Overrides and future hooks

- **`lupo_metadata`** or per-channel policy **may** override the integer (documented in channel config).
- **Additional** triggers (idle time, operator “invite perspectives,” semantic drift) **may** be added in implementation **without** removing PHP authority (**PRD 36** §4).

## Dependencies

- **PRD 36** — full metadata contract, **`rose_visibility`**, **2000**-character cap, **KAIROS** handoff.
- **PRD 18** — rendering and filtering for synthetic vs organic lines.

This output complies with Lupopedia Constitutional Root Rules.
