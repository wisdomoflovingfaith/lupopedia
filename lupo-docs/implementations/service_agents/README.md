---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: documentation
  when_updated: "20260404163615"
  file_path_from_root: "lupo-docs/implementations/service_agents/README.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/implementations/service_agents/README.md"
  last_modified_utc: "20260404163615"
  federation_node_id: 0
  channel_id: 42
  artifact_type: documentation
  artifact_kind: implementation_index
  purpose: "Implementation mirror for PHP-first service agent doctrine (IRIS, ANUBIS, ROSE, THOTH, KAIROS)"
  actor_id: 102
  actor_name: cursor
  delegation_chain: "cursor:root"
lupopedia.edges:
  outbound_edges:
    - to: "lupo-docs/doctrine/SERVICE_AGENT_ARCHITECTURE.md"
      type: implements
      weight: 1.0
    - to: "lupo-docs/prd/00_root_constitutional_system_requirements.md"
      type: references
      weight: 1.0
      reason: "Section 5.10"
    - to: "lupo-docs/implementations/36_rose_multi_persona_synthetic_dialog/README.md"
      type: references
      weight: 0.95
      reason: "ROSE synthetic choir (PRD 36) implementation mirror"
lupopedia.footer:
  last_verified: "20260404"
  verified_by:
    identity_type: actor
    actor_id: 102
  verified_via:
    type: faucet
    faucet_slug: cursor
  orchestrator: "cursor:root"
---

# file: Service agents implementation — delegation: cursor:root — web_path: http://www.lupopedia.com/lupopedia/lupo-docs/implementations/service_agents/README.md

# Implementation: service agents (PHP first, LLM second)

**Doctrine:** **`lupo-docs/doctrine/SERVICE_AGENT_ARCHITECTURE.md`**  
**Constitution:** **`lupo-docs/prd/00_root_constitutional_system_requirements.md`** — **§5.10**  
**New PRD mirrors:** Folder **`lupo-docs/implementations/{prd_file_stem}/`** must match the PRD basename (**PRD 31**, **§5.8**).

## Folder layout

| Path | Role |
|------|------|
| **`status/`** | Completion state and what is next |
| **`decisions/`** | Why logic lives in PHP vs prompts |
| **`questions/`** | Open ambiguities |
| **`answers/`** | Resolved Q&A |
| **`comments/`** | Session notes |

Each active subfolder should include **`THREAD_INDEX.md`** (see **PRD 17** / channel doctrine).

**Sibling mirror (ROSE):** **`lupo-docs/implementations/36_rose_multi_persona_synthetic_dialog/`** — synthetic choir orchestration (**PRD 36**, constitution **§5.10.3**).

## Roster (constitutional)

**IRIS**, **ANUBIS**, **ROSE**, **THOTH**, **KAIROS** — see doctrine **§1** for roles.

This output complies with Lupopedia Constitutional Root Rules.
