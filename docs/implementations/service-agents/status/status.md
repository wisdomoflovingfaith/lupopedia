---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/implementations/service-agents/status/status.md
  web_path: https://www.lupopedia.com/lupopedia/docs/implementations/service-agents/status/status.md
  status: ''
  when_updated: '20260513033046'
  trust_tier: null
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: documentation
  artifact_kind: status
  channel_key: null
  federation_node_id: null
  thread_key: ''
  lupopedia.schema: null
  prd_cluster: null
  title: ''
  summary: ''
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
| IRIS PHP faucet | **In tree** | `includes/classes/iris.php` |
| Prompt-to-PHP migrations | **Ongoing** | See **`decisions/`** |

## What is next

- Keep **`runtime_actors.yaml`** and dialog UI aligned so service agents are not mistaken for default chat targets.
- Record further “logic moved to PHP” decisions under **`decisions/`**.

This output complies with Lupopedia Constitutional Root Rules.
