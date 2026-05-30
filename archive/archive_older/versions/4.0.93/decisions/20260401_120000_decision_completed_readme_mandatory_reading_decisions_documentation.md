---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: doctrine
  when_updated: "20260401120000"
  file_path_from_root: "docs/versions/4.0.93/decisions/20260401_120000_DECISION_completed_README_Mandatory_Reading_Decisions_Documentation.md"
  web_path: "http://www.lupopedia.com/lupopedia/docs/versions/4.0.93/decisions/20260401_120000_DECISION_completed_README_Mandatory_Reading_Decisions_Documentation.md"
  questions_toon: null
  federation_node_id: 0
  channel_key: null
  trust_tier: null
  memory_key: null
  artifact_type: doctrine
  artifact_kind: decisions
  thread_id: "D-106"
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
# D-106: README — Mandatory Reading + Decisions Documentation

## Type
Directive

## Status
Completed

## Author
CURSOR (actor_id 102)

## Date
2026-04-01

### Context
The root README did not make it clear that reading `00_root_constitutional_system_requirements.md` is mandatory, that PRDs are the highest form of truth, or that `decisions.md` files exist and explain the reasoning behind implementation choices. Agents were starting work without reading the constitutional PRD.

### Decision
- Added "MANDATORY READING — Start Here" section at the top of the README body, immediately after the title. Explicit language: "This is not optional. It is constitutional law."
- Added "Decisions, Q&A, and Implementation Reasoning" section explaining the decisions.md format (D-xx, Q-xx/A-xx, DG-xx, W-xx, O-xx), channel/context scoping, and the instruction to check decisions.md before implementing anything non-trivial.
- Reordered `lupopedia.init.required_reading` to put the constitutional PRD first with "MANDATORY FIRST READ" reason.
- Added `decisions.md` to `required_reading` and `lupopedia.edges`.
- Added WOLFIE_DOCTRINE to `required_reading`.
- Reordered "Where to Read Next" list with constitutional PRD at #1, WOLFIE Doctrine at #2, decisions.md at #10.
- Rewrote "Development Rules" section to lead with constitutional PRD and WOLFIE Doctrine.
- Updated "PRD Policy" section to state PRDs are requirements, not suggestions.
- Updated header timestamps and footer.

### Consequences
- No agent can claim they didn't know the constitutional PRD was mandatory
- decisions.md is now discoverable from the root README
- The reading order is explicit and prioritized correctly

---
