---
lupopedia.headers:
  header_format_version: "4.1.2"
  file_path_from_root: "docs/doctrine/persistence/HANDOFF_TOON_STANDARD.md"
  web_path: "https://www.lupopedia.com/lupopedia/docs/doctrine/persistence/HANDOFF_TOON_STANDARD.md"
  status: "active"
  when_updated: "20260416182218"
  trust_tier: "canonical"
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: documentation
  artifact_kind: doctrine
  channel_key: "development"
  federation_node_id: 0
  thread_id: ""
  content_id: null
  pk_id: null
  pk_slug: ""
  parent_pk_id: ""
  lupopedia.schema: documentation
  title: "Handoff Toon Standard"
  summary: "Handoff Toons are the strict persistence layer for the agent system."
---
# Handoff Toon Standard

## 1. Handoff Toons Are the Persistence Layer
Because agents are temporary execution nodes, context continuity relies entirely on the handoff layer. 
- Agents must write state externally to a handoff toon before termination or completion of execution.
- Handoff toons are the system. They bridge the gap between agent instances.
- Agents must read the current staging handoff toon when instantiated to acquire context.
- Agents must never assume continuous memory across sessions. If it is not in the handoff toon, it does not exist.
