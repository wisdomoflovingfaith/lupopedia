---
lupopedia.headers:
  header_format_version: null
  lupopedia.schema: null
  when_updated: "20260404160851"
  file_path_from_root: "lupo-docs/implementations/service_agents/status/STATUS.md"
  web_path: null
  questions_toon: null
  federation_node_id: null
  channel_key: null
  trust_tier: null
  memory_key: null
  artifact_type: documentation
  artifact_kind: status
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
# file: service_agents status — web_path: (implementation)

# Status: service agents

**Last reviewed (UTC):** 20260404160851

## Completion (high level)

| Area | State | Notes |
|------|-------|--------|
| Constitutional §5.10 | **Done** | Roster + runtime loop contrast |
| Doctrine `SERVICE_AGENT_ARCHITECTURE.md` | **Done** | PHP-first, KAIROS flow, THOTH grounding |
| KAIROS PHP service + API | **In tree** | `KairosConsolidationService`, `kairos-api.php` |
| IRIS PHP faucet | **In tree** | `lupo-includes/classes/iris.php` |
| Prompt-to-PHP migrations | **Ongoing** | See **`decisions/`** |

## What is next

- Keep **`runtime_actors.yaml`** and dialog UI aligned so service agents are not mistaken for default chat targets.
- Record further “logic moved to PHP” decisions under **`decisions/`**.

This output complies with Lupopedia Constitutional Root Rules.
