---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: documentation
  when_updated: "20260404163615"
  file_path_from_root: "lupo-docs/implementations/service_agents/README.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/implementations/service_agents/README.md"
  questions_toon: null
  federation_node_id: 0
  channel_key: null
  trust_tier: null
  memory_key: null
  artifact_type: documentation
  artifact_kind: implementation_index
  thread_id: ""
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
