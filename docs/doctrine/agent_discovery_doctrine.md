---
lupopedia.headers:
  header_format_version: "4.1.3"
  file_path_from_root: "docs/doctrine/agent_discovery_doctrine.md"
  web_path: "https://www.lupopedia.com/lupopedia/docs/doctrine/agent_discovery_doctrine.md"
  status: "active"
  when_updated: "20260620162738"
  trust_tier: "canonical"
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: "0/development/agent-discovery-doctrine"
  artifact_type: "doctrine"
  artifact_kind: "guide"
  channel_key: "development"
  federation_node_id: 0
  thread_id: null
  content_id: null
  content_parent_id: null
  content_slug: "agent-discovery-doctrine"
  default_collection_id: null
  lupopedia.schema: "doctrine"
  prd_cluster: "07_A-i_AGENTS_FAUCETS"
  title: "Agent Discovery Doctrine"
  summary: "Filesystem agent pack discovery, system_prompt.md precedence, installer DB mirror rules."
---
# Agent Discovery Doctrine

Anchors: **PRD 07** (Agents and Faucets), **PRD 42-B** (LLM Provider Integration).

## 1. Source of truth layers

| Layer | Authority |
|-------|-----------|
| Filesystem packs (`agents/{slug}/`) | Template identity, capabilities, system prompt text |
| `agent_definitions` / `agent_capabilities` | Install-time DB mirror for SQL queries and routing |
| `agent_llm_configs` | Per-agent LLM provider and model (installer seeded) |
| Runtime actors (`lupo_actors`) | Operational identity -- not created by agent pack import |

## 2. Pack layout

Required:

- `agent.json` -- agent_id, agent_key, name, layer, version
- `capabilities.json` -- allowed/blocked capability lists
- `system_prompt.md` -- canonical prompt (preferred)

Optional:

- `properties.json`, `versions/vX.Y.Z/` snapshots

Skip directories: `_TEMPLATE`, `meta`.

## 3. System prompt loading order

`AgentDiscovery` and install import MUST resolve prompts in this order:

1. `system_prompt.md`
2. `system_prompt.txt` (legacy fallback only)

Store path in `agent_definitions.system_prompt_path` relative to pack root when imported.

## 4. Installer import

`InstallWizardAgentLoader`:

- Scans all packs after schema/seed on install run step
- Upserts `agent_definitions`, replaces `agent_capabilities`
- Does not modify agent_ids or create actors

## 5. LLM config seed

After API config write, `InstallWizardLLMConfigLoader`:

- Reads all non-deleted `agent_definitions`
- Inserts/updates `agent_llm_configs` with default provider/model from `$LUPO_API_PROVIDER_CONFIG`
- Stores fallback provider/model in `safety_json`

## 6. Registry drift

If `database/lupopedia/actors/actor_id/registry.json` disagrees with on-disk packs, log warnings only during install -- do not auto-reassign agent_ids.

## 7. Forbidden

- Creating variant agent keys (`*_test`, `*_variant`)
- Hard deletes on `agent_definitions` during import (use soft delete doctrine at runtime)
- Embedding API keys inside agent packs
