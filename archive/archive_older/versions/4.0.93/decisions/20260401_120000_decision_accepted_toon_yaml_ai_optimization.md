---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: doctrine
  when_updated: "20260401120000"
  file_path_from_root: "docs/versions/4.0.93/decisions/20260401_120000_DECISION_accepted_TOON_YAML_AI_Optimization.md"
  web_path: "http://www.lupopedia.com/lupopedia/docs/versions/4.0.93/decisions/20260401_120000_DECISION_accepted_TOON_YAML_AI_Optimization.md"
  questions_toon: null
  federation_node_id: 0
  channel_key: null
  trust_tier: null
  memory_key: null
  artifact_type: doctrine
  artifact_kind: decisions
  thread_id: "D-56"
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
# D-56: TOON YAML AI Optimization

## Type
**Decision**

## Status
**Accepted**

## Author
**WOLFIE** (actor_id 1)

## Date
2026-04-01

### Context
Toon files use `.json` historically but `TOON_DOCTRINE.md` needed grounding. Native JSON requires exorbitant AI context token consumption due to heavy quotes and structural bloat.

### Decision
Officially document TOON formatting preference as YAML-based inside the doctrine explicitly because it significantly optimizes AI context payloads, reserving token limits for business logic.

---
