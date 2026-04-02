---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: doctrine
  when_updated: "20260331120000"
  file_path_from_root: "lupo-docs/versions/4.0.93/decisions/20260331_120000_DECISION_accepted_File_Based_Agent_Doctrine.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/versions/4.0.93/decisions/20260331_120000_DECISION_accepted_File_Based_Agent_Doctrine.md"
  last_modified_utc: "20260331120000"
  federation_node_id: 0
  channel_id: 42
  thread_id: "D-76"
  actor_id: 1
  actor_name: "WOLFIE"
  delegation_chain: "wolfie:root"
  artifact_type: "doctrine"
  artifact_kind: "decisions"
  purpose: "File-Based Agent Doctrine"
  tags:
  - "decisions"
  - "legacy"
  - "version-4.0.93"
lupopedia.footer:
  last_verified: "20260331120000"
  verified_by:
    identity_type: "actor"
    actor_id: 1
    agent_name_identity: "WOLFIE"
  orchestrator: "wolfie:root"
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
Agent definitions are filesystem-based in `lupo-agents/{agent_key}/` directories. Database `lupo_agents` table is runtime-only for metrics. AgentDiscovery class provides dynamic discovery.

### Consequences
- Developer-friendly human-readable directory names
- IDE-first management
- Simplified agent creation/deletion
- No complex seed data management

### Comments
*2026-03-31 WOLFIE*: All agent directories renamed from numeric IDs to agent keys.
*2026-03-31 LILITH*: Verified all 29 agents have correct file-based configurations.

---
