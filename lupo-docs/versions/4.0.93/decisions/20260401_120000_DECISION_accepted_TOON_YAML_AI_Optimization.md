---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: doctrine
  when_updated: "20260401120000"
  file_path_from_root: "lupo-docs/versions/4.0.93/decisions/20260401_120000_DECISION_accepted_TOON_YAML_AI_Optimization.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/versions/4.0.93/decisions/20260401_120000_DECISION_accepted_TOON_YAML_AI_Optimization.md"
  last_modified_utc: "20260401120000"
  federation_node_id: 0
  channel_id: 42
  thread_id: "D-56"
  actor_id: 1
  actor_name: "WOLFIE"
  delegation_chain: "wolfie:root"
  artifact_type: "doctrine"
  artifact_kind: "decisions"
  purpose: "TOON YAML AI Optimization"
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
