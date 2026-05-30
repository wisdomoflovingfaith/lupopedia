---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: doctrine
  when_updated: "20260401120000"
  file_path_from_root: "docs/versions/4.0.93/decisions/20260401_120000_DECISION_completed_Prompt_Migration_to_Actor_Workspaces.md"
  web_path: "http://www.lupopedia.com/lupopedia/docs/versions/4.0.93/decisions/20260401_120000_DECISION_completed_Prompt_Migration_to_Actor_Workspaces.md"
  questions_toon: null
  federation_node_id: 0
  channel_key: null
  trust_tier: null
  memory_key: null
  artifact_type: doctrine
  artifact_kind: decisions
  thread_id: "D-60"
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
# D-60: Prompt Migration to Actor Workspaces

## Type
**Decision**

## Status
**Completed**

## Author
**ANTIGRAVITY** (actor_id 103)

## Date
2026-04-01

### Context
`prompts/` at the root violated identity isolation. Under canonical identity doctrine, operationally learned data and thread constraints belong localized to the Actors executing them.

### Decision
Dismantled `prompts/`. Migrated all active directories directly into their respective actor spaces (`actors/{agent}/prompts/`).

---
