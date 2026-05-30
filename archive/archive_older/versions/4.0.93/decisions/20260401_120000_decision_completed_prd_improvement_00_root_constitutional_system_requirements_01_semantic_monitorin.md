---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: doctrine
  when_updated: "20260401120000"
  file_path_from_root: "docs/versions/4.0.93/decisions/20260401_120000_DECISION_completed_PRD_Improvement_00_root_constitutional_system_requirements_01_semantic_monitorin.md"
  web_path: "http://www.lupopedia.com/lupopedia/docs/versions/4.0.93/decisions/20260401_120000_DECISION_completed_PRD_Improvement_00_root_constitutional_system_requirements_01_semantic_monitorin.md"
  questions_toon: null
  federation_node_id: 0
  channel_key: null
  trust_tier: null
  memory_key: null
  artifact_type: doctrine
  artifact_kind: decisions
  thread_id: "D-96"
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
# D-96: PRD Improvement — 00_root_constitutional_system_requirements + 01_semantic_monitoring_widget

## Type
Decision

## Status
Completed

## Author
CURSOR (actor_id 102) — Lead Orchestration IDE Agent

## Date
2026-04-01

### Context
Both PRDs had structural problems: the constitutional PRD had a broken YAML front matter (entire body trapped inside the YAML block), wrong `lupopedia.schema` value (`prd` is not a valid taxonomy token), missing required header fields, thin edges, and no implementation guidance per rule. The semantic monitoring widget PRD referenced non-existent tables, used wrong column names (e.g. `item_id` instead of `item_slug` in `lupo_contexts_map`), referenced deprecated `lupo_truth_knowledge` instead of `lupo_truth_questions`, and had no constitutional anchor edge.

### Decision
- Rewrote `docs/prd/00_root_constitutional_system_requirements.md` completely: fixed YAML structure, corrected `lupopedia.schema` to `doctrine`, added all missing required header fields (`federation_node_id`, `when_updated`, `thread_id`, `actor_name`), expanded edges from 4 to 14 covering all referenced doctrines and implementation files, fixed footer to current verifier shape, added implementation guidance to every major rule section.
- Rewrote `docs/prd/01_semantic_monitoring_widget.md` with verified column names from TOON JSON/table docs, a "Missing Tables" section, all SQL examples using `DatabaseFactory` + `LUPO_TABLE_PREFIX`, corrected `lupo_contexts_map` to use `item_slug`, noted `lupo_truth_knowledge` deprecation, added implementation checklist.
- Added 28 outbound edges to the widget PRD covering every table it touches.

### Consequences
- Constitutional PRD is now structurally valid YAML and passes header validation
- Widget PRD SQL examples use only confirmed column names — no guessing
- Both PRDs have constitutional anchor edges

### Comments
*2026-04-01 CURSOR*: The `lupo_contexts_map` `item_slug` vs `item_id` correction is important — the wrong column would silently return no rows.

---
