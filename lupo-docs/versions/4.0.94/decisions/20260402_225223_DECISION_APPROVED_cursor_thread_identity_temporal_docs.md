---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: documentation
  when_updated: "20260402225223"
  file_path_from_root: "lupo-docs/versions/4.0.94/decisions/20260402_225223_DECISION_APPROVED_cursor_thread_identity_temporal_docs.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/versions/4.0.94/decisions/20260402_225223_DECISION_APPROVED_cursor_thread_identity_temporal_docs.md"
  last_modified_utc: "20260402225223"
  federation_node_id: 0
  channel_id: 42
  thread_id: "version-4.0.94-decisions"
  author:
    type: "actor"
    id: 102
    name: "CURSOR"
  delegation_chain: "cursor:root"
  artifact_type: "documentation"
  artifact_kind: "decision_record"
  purpose: "APPROVED record of documentation work performed in Cursor multi-turn thread (identity, temporal anchor, README); excludes unverified template claims"
  status: "approved"
  tags:
    - "4.0.94"
    - "decision"
    - "documentation"
    - "identity"
    - "temporal_anchor"
lupopedia.edges:
  outbound_edges:
    - to: "lupo-docs/doctrine/IDENTITY_LAYERS_DOCTRINE.md"
      type: references
      weight: 1.0
      reason: "Canonical §3 actor/agent/facet"
    - to: "lupo-rules/root/UTC_TEMPORAL_ANCHOR_DOCTRINE.md"
      type: references
      weight: 1.0
      reason: "Root temporal anchor binding"
    - to: "lupo-docs/prd/00_root_constitutional_system_requirements.md"
      type: references
      weight: 1.0
      reason: "§3.5a real UTC for headers"
    - to: "lupo-docs/versions/4.0.94/questions/20260402_225224_QUESTION_version_doc_thread_scope.md"
      type: references
      weight: 0.95
      reason: "Scope boundary question"
    - to: "lupo-docs/versions/4.0.94/answers/20260402_225225_ANSWER_version_doc_thread_scope.md"
      type: references
      weight: 0.95
      reason: "Answer — thread-verified only"
lupopedia.footer:
  last_verified: "20260402225223"
  verified_by:
    identity_type: "actor"
    actor_id: 102
  orchestrator: "cursor:root"
---

# DECISION (APPROVED): Cursor thread — identity, temporal anchor, README (version doc sync)

## 5W1H

| Element | Record |
|--------|--------|
| **WHO** | Cursor IDE agent (`actor_id` **102**), `delegation_chain` `cursor:root` |
| **WHAT** | Documentation and root-rule updates tied to one multi-turn Cursor thread (this session’s actual edits only) |
| **WHERE** | Repository: `README.md`, `AGENTS.md`, `ONBOARDING.md`, `lupo-docs/doctrine/*`, `lupo-rules/root/*`, `lupo-docs/prd/00_*`, `lupo-bin/*`, `.cursor/rules/TIMESTAMP_DOCTRINE.mdc`, `lupo-docs/versions/4.0.94/*` |
| **WHEN** | Header/footer UTC **`20260402225223`** from `python lupo-bin/tick.py` at close of this documentation pass |
| **WHY** | Remove contradictory actor/agent/facet text; stop guessed LLM timestamps; elevate temporal discipline to constitutional/root visibility; keep `4.0.94` version tree aligned with real outcomes |
| **HOW** | Single source in `IDENTITY_LAYERS_DOCTRINE.md` §3; summaries in AGENTS/ONBOARDING; `tick.py` + `echo_anchor_utc.py`; new `UTC_TEMPORAL_ANCHOR_DOCTRINE.md`; PRD 00 §3.5a; `LUPOPEDIA_CONSTITUTIONAL_ROOT_RULES` §2.4a; README Temporal Anchor + `lupopedia.init` pointer; version folder CHANGELOG/PLAN/TODO/edges/indexes updated here |

## Scope boundary (explicit)

**In scope for this decision:** Work verified in the Cursor thread that produced this commit path: README thread manifest section; LILITH-aligned version-tree edits in `4.0.94`; identity doctrine consolidation; temporal anchor tooling and constitutional text; TICK_PY doctrine expansion; cursor TIMESTAMP rule update.

**Out of scope / not claimed here:** PRD 16 field matrix changes, PRD 26/30/31 COUNTERMEASURE rejection narrative, validator code changes to `validate_implementation.py` / universal validator, new PK constitutional rule “93.PK_NAMING”, or database/installer edits — **none of those were performed in this thread** and must not be inferred from this decision.

## Completion criteria

- [x] Version `CHANGELOG.md` lists thread-accurate bullets under dated entry.
- [x] `edges.md` links new root/doctrine/PRD 00 artifacts.
- [x] `PLAN.md` includes completed Phase E (or equivalent) for this pass; Phase C table rows for C-1..C-3 restored.
- [x] Q/A pair documents “thread-verified only” rule for version changelogs.

This output complies with Lupopedia Constitutional Root Rules.
