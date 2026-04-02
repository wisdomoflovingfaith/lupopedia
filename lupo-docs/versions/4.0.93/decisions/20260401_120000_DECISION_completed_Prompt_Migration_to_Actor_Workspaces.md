---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: doctrine
  when_updated: "20260401120000"
  file_path_from_root: "lupo-docs/versions/4.0.93/decisions/20260401_120000_DECISION_completed_Prompt_Migration_to_Actor_Workspaces.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/versions/4.0.93/decisions/20260401_120000_DECISION_completed_Prompt_Migration_to_Actor_Workspaces.md"
  last_modified_utc: "20260401120000"
  federation_node_id: 0
  channel_id: 42
  thread_id: "D-60"
  actor_id: 1
  actor_name: "WOLFIE"
  delegation_chain: "wolfie:root"
  artifact_type: "doctrine"
  artifact_kind: "decisions"
  purpose: "Prompt Migration to Actor Workspaces"
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
`lupo-prompts/` at the root violated identity isolation. Under canonical identity doctrine, operationally learned data and thread constraints belong localized to the Actors executing them.

### Decision
Dismantled `lupo-prompts/`. Migrated all active directories directly into their respective actor spaces (`lupo-actors/{agent}/prompts/`).

---
