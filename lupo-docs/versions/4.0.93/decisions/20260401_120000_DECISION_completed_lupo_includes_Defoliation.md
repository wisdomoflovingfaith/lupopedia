---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: doctrine
  when_updated: "20260401120000"
  file_path_from_root: "lupo-docs/versions/4.0.93/decisions/20260401_120000_DECISION_completed_lupo_includes_Defoliation.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/versions/4.0.93/decisions/20260401_120000_DECISION_completed_lupo_includes_Defoliation.md"
  last_modified_utc: "20260401120000"
  federation_node_id: 0
  channel_id: 42
  thread_id: "D-38"
  actor_id: 1
  actor_name: "WOLFIE"
  delegation_chain: "wolfie:root"
  artifact_type: "doctrine"
  artifact_kind: "decisions"
  purpose: "lupo-includes Defoliation"
  tags:
  - "decisions"
  - "legacy"
  - "version-4.0.93"
lupopedia.footer:
  last_verified: "20260401120000"
  verified_by:
    identity_type: "actor"
    actor_id: 1
    agent_name_identity: "WOLFIE"
  orchestrator: "wolfie:root"
---

# D-38: lupo-includes Defoliation

## Type
**Decision**

## Status
**Completed**

## Author
**ANTIGRAVITY** (actor_id 103)

## Date
2026-04-01

### Context
`lupo-includes/` contained 13 orphaned/hallucinated directories spanning from legacy Crafty iterations to dead AI experiments (e.g. `DialogChannelMigration`, `EmotionalGeometry`). They violated WOLFIE_DOCTRINE and added namespace noise.

### Decision
Archive all 13 directories into `lupo-archive/lupo-includes-archive/` along with a MANIFEST.md detailing the defoliation. Update `project_structure_prd.md` to precisely map the true 8 active directories inside `lupo-includes/`.

### Consequences
- Drastically cleaner core runtime repository.
- Avoids AI IDE confusion regarding semantic tracking logic vs legacy migration classes.

---
