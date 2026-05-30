---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: doctrine
  when_updated: "20260331120000"
  file_path_from_root: "docs/versions/4.0.93/decisions/20260331_120000_DECISION_accepted_File_Based_Agent_Doctrine.md"
  web_path: "http://www.lupopedia.com/lupopedia/docs/versions/4.0.93/decisions/20260331_120000_DECISION_accepted_File_Based_Agent_Doctrine.md"
  questions_toon: null
  federation_node_id: 0
  channel_key: null
  trust_tier: null
  memory_key: null
  artifact_type: doctrine
  artifact_kind: decisions
  thread_id: "D-76"
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
# D-76: File-Based Agent Doctrine

## Type
Unknown

## Status
**Accepted**

## Author
**WOLFIE** (actor_id 1) - System Orchestrator

## Date
2026-03-31

### Context
Agents were previously database-driven, requiring seed data and making agent management complex. Agent definitions were scattered across multiple systems.

### Decision
Agent definitions are filesystem-based in `agents/{agent_key}/` directories. Database `lupo_agents` table is runtime-only for metrics. AgentDiscovery class provides dynamic discovery.

### Consequences
- Developer-friendly human-readable directory names
- IDE-first management
- Simplified agent creation/deletion
- No complex seed data management

### Comments
*2026-03-31 WOLFIE*: All agent directories renamed from numeric IDs to agent keys.
*2026-03-31 LILITH*: Verified all 29 agents have correct file-based configurations.

---
