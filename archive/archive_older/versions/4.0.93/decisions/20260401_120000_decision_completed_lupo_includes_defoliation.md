---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: doctrine
  when_updated: "20260401120000"
  file_path_from_root: "docs/versions/4.0.93/decisions/20260401_120000_DECISION_completed_lupo_includes_Defoliation.md"
  web_path: "http://www.lupopedia.com/lupopedia/docs/versions/4.0.93/decisions/20260401_120000_DECISION_completed_lupo_includes_Defoliation.md"
  questions_toon: null
  federation_node_id: 0
  channel_key: null
  trust_tier: null
  memory_key: null
  artifact_type: doctrine
  artifact_kind: decisions
  thread_id: "D-38"
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
# D-38: includes Defoliation

## Type
**Decision**

## Status
**Completed**

## Author
**ANTIGRAVITY** (actor_id 103)

## Date
2026-04-01

### Context
`includes/` contained 13 orphaned/hallucinated directories spanning from legacy Crafty iterations to dead AI experiments (e.g. `DialogChannelMigration`, `EmotionalGeometry`). They violated WOLFIE_DOCTRINE and added namespace noise.

### Decision
Archive all 13 directories into `archive/includes-archive/` along with a MANIFEST.md detailing the defoliation. Update `project_structure_prd.md` to precisely map the true 8 active directories inside `includes/`.

### Consequences
- Drastically cleaner core runtime repository.
- Avoids AI IDE confusion regarding semantic tracking logic vs legacy migration classes.

---
