---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: doctrine
  when_updated: "20260401120000"
  file_path_from_root: "lupo-docs/versions/4.0.93/decisions/20260401_120000_DECISION_completed_PRD_Constitutional_Documentation_Alignment.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/versions/4.0.93/decisions/20260401_120000_DECISION_completed_PRD_Constitutional_Documentation_Alignment.md"
  last_modified_utc: "20260401120000"
  federation_node_id: 0
  channel_id: 42
  thread_id: "D-110"
  actor_id: 1
  actor_name: "WOLFIE"
  delegation_chain: "wolfie:root"
  artifact_type: "doctrine"
  artifact_kind: "decisions"
  purpose: "PRD / Constitutional Documentation Alignment"
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

# D-110: PRD / Constitutional Documentation Alignment

## Type
Decision

## Status
Completed

## Author
CURSOR (actor_id 102)

## Date
2026-04-01

### Context
A read-only review of all files in `lupo-docs/prd/` found internal inconsistencies: constitutional §6 vs §9.9 TOON path wording, `lupo-agents/<agent_id>/` vs `{agent_key}`, two competing agent file layouts (`PRD_AGENT_DEFINITION_MODEL.md` vs `01_core_identity.md`), ambiguous “no reserved slots” in `07_agents_faucets.md`, actor ID wording vs registry, overlapping `08_actors.md` / `15_actors.md`, and `05_auth_user_actor_agent_transformation.md` lease wording vs `lupo_actor_auth_users`. Remediation was applied in-repo (see CHANGELOG 2026-04-01 PRD consistency pass).

### Decision
- Align `00_root_constitutional_system_requirements.md` with schema reference JSON under `lupo-database/lupopedia/json/`, rule **93.PROTECT_SCHEMA_JSON**, sections **5.5–5.6** (reserved agent IDs, actor ID semantics), updated **§6**, **§9.9**, **§9.16**, **§9.18**; deprecate hand-maintained `.toon.json` trees for new work.
- Canonical on-disk agent layout: **`01_core_identity.md`** (`agent.json`, `capabilities.json`, `properties.json`, `system_prompt.txt`); **`PRD_AGENT_DEFINITION_MODEL.md`** marked deprecated for layout.
- **`15_actors.md`** is the canonical actor PRD; **`08_actors.md`** superseded.
- **`05_auth_user_actor_agent_transformation.md`**: canonical lease storage on **`lupo_actor_auth_users`**; fixed corrupted YAML edges; auxiliary tables noted.
- **`01_installer_requirements.md`**: timestamps as bare `BIGINT` (no display width); paths under `lupo-docs/prd/`.
- Correct **`file_path_from_root` / `web_path`** where they pointed at `lupo-docs/versions/4.0.93/prd/` for files living in `lupo-docs/prd/`.
- **`19_garbage_collection_system.md`** edges: `lupo_paths.json`, `lupo_referers_daily.json`; **`18_channel_chat_display.md`** examples reference `json/` schema files.

### Consequences
- Single story for schema references, agent directories, actor IDs, leasing, and installer wording.
- Future PR edits should extend these files rather than reintroducing deprecated layouts.

---
