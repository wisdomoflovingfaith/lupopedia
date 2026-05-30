---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: includes/modules/ai/README.md
  web_path: https://www.lupopedia.com/lupopedia/includes/modules/ai/README.md
  status: null
  when_updated: '20260513033046'
  trust_tier: null
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: guide
  artifact_kind: null
  channel_key: null
  federation_node_id: null
  thread_key: null
  lupopedia.schema: documentation
  prd_cluster: null
  title: null
  summary: null
---
# file: includes/modules/ai/README.md — delegation: cursor:root

# `includes/modules/ai/` (no duplicate PHP here)

**Canonical implementation paths** (required by loaders such as `bin/initialize_system.php`):

- `includes/functions/ai_agent_integration.php` — `initializeCoreAIAgents`, `isActorAIRunning`, `ensureActorActive`
- `includes/functions/ai_activation.php` — activation helpers used with the above

Do **not** add `ai_agent_integration.php` under this `modules/ai/` tree; it fragments discovery and breaks `require_once` paths.

This output complies with Lupopedia Constitutional Root Rules.
