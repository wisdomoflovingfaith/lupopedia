---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: documentation
  when_updated: "20260402225223"
  file_path_from_root: "docs/versions/4.0.94/decisions/20260402_225223_DECISION_APPROVED_cursor_thread_identity_temporal_docs.md"
  web_path: "http://www.lupopedia.com/lupopedia/docs/versions/4.0.94/decisions/20260402_225223_DECISION_APPROVED_cursor_thread_identity_temporal_docs.md"
  questions_toon: null
  federation_node_id: 0
  channel_key: null
  trust_tier: null
  memory_key: null
  artifact_type: documentation
  artifact_kind: decision_record
  thread_id: "version-4.0.94-decisions"
  content_id: null
  pk_id: null
  pk_slug: ""
  title: ""
  status: "approved"
  parent_pk_id: ""
  summary: ""
  module: null
  dialog_transcript: null
---
# DECISION (APPROVED): Cursor thread — identity, temporal anchor, README (version doc sync)

## 5W1H

| Element | Record |
|--------|--------|
| **WHO** | Cursor IDE agent (`actor_id` **102**), `delegation_chain` `cursor:root` |
| **WHAT** | Documentation and root-rule updates tied to one multi-turn Cursor thread (this session’s actual edits only) |
| **WHERE** | Repository: `README.md`, `AGENTS.md`, `ONBOARDING.md`, `docs/doctrine/*`, `rules/root/*`, `docs/prd/00_*`, `bin/*`, `.cursor/rules/TIMESTAMP_DOCTRINE.mdc`, `docs/versions/4.0.94/*` |
| **WHEN** | Header/footer UTC **`20260402225223`** from `python bin/tick.py` at close of this documentation pass |
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
