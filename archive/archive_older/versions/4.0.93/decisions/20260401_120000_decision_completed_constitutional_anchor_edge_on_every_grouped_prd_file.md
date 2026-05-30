---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: doctrine
  when_updated: "20260401120000"
  file_path_from_root: "docs/versions/4.0.93/decisions/20260401_120000_DECISION_completed_Constitutional_anchor_edge_on_every_grouped_PRD_file.md"
  web_path: "http://www.lupopedia.com/lupopedia/docs/versions/4.0.93/decisions/20260401_120000_DECISION_completed_Constitutional_anchor_edge_on_every_grouped_PRD_file.md"
  questions_toon: null
  federation_node_id: 0
  channel_key: null
  trust_tier: null
  memory_key: null
  artifact_type: doctrine
  artifact_kind: decisions
  thread_id: "D-112"
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
# D-112: Constitutional anchor edge on every grouped PRD file

## Type
Directive

## Status
Completed

## Author
CURSOR (actor_id 102)

## Date
2026-04-01

### Context
Doctrine requires new PRDs to declare an outbound edge to the root constitutional PRD. Many files under `docs/prd/` lacked that YAML edge or used stale paths.

### Decision
- Add explicit `lupopedia.edges` outbound entry: `to: "docs/prd/00_root_constitutional_system_requirements.md"`, `type: references`, `weight: 1.0`, `reason: "Constitutional anchor"` to **every** markdown file in `docs/prd/` **except** `00_root_constitutional_system_requirements.md` (39 files). Where `lupopedia.edges` was missing, add the block (e.g. `WHAT_TO_DO_NEXT.md`, `22_web_navigation_architecture.md`, `21_semantic_navbar.md`, `project_structure_prd.md`, `08_actors.md`, PRDs 23–24, `21_thread_graduation_doctrine.md`).
- Reorder edges so the constitutional anchor is **first** where multiple edges exist.

### Consequences
- Validators and humans can rely on consistent constitutional linkage from all PRDs.

---
