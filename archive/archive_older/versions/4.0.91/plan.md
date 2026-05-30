---
lupopedia.headers:
  header_format_version: null
  lupopedia.schema: documentation
  when_updated: null
  file_path_from_root: "docs/versions/4.0.91/PLAN.md"
  web_path: "http://www.lupopedia.com/docs/versions/4.0.91/PLAN.md"
  questions_toon: null
  federation_node_id: null
  channel_key: null
  trust_tier: null
  memory_key: null
  artifact_type: plan
  artifact_kind: version_plan
  thread_id: ""
  content_id: null
  pk_id: null
  pk_slug: ""
  title: ""
  status: ""
  parent_pk_id: ""
  summary: ""
  module: null
  dialog_transcript: null
---
# Lupopedia 4.0.91 PLAN

## Priority Alpha: Nervous System Protocol

ATHENA (12) — Primary Actor
HEPHAESTUS (102) — Supporting Actor

### Component A: The State Mirror
- Implement a `LupoState` listener that mirrors the DB context without storing it locally in JS.
- JS only reflects server state; never persists or mutates truth.

### Component B: Semantic Monitoring (Carryover)
- Refactor the monitoring widget from 4.0.88 to use the new 4.0.90 Context Edges for real-time truth-validation.
- Integrate with the context registry for live semantic checks.

### Component C: The High-Density Scroller
- Virtualize the 1,000-message stress-test view to maintain 60fps while keeping the "Glass" reflections active.
- JS manages dynamic lighting overlays and palette rotation, but not message state.

---

## Constitutional Check
- `enforce_doctrine.py` now scans .js files for forbidden logic (e.g., local DB emulation, state mutation).
- All JS must comply with "Dumb UI" doctrine: only refractions, never truth.

## Status
- DRAFTING — Plan open for ATHENA and HEPHAESTUS review.

---

LILITH Verdict: The "Smith" has finished the skeleton; now the "Architect" begins the wiring.
