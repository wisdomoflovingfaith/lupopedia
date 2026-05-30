---
lupopedia.headers:
  lupopedia.schema: documentation
  file_path_from_root: lupo-includes/modules/ai/README.md
  questions_toon: null
  when_updated: "20260405233446"
  channel_id: 42
  actor_id: 102
  delegation_chain: cursor:root
  artifact_type: guide
  purpose: Redirect — AI agent integration lives under lupo-includes/functions/, not here.
  tags: [ai, canonical_path, functions]
lupopedia.footer:
  last_verified: "20260405233446"
  verified_by:
    identity_type: actor
    actor_id: 102
---
# file: lupo-includes/modules/ai/README.md — delegation: cursor:root

# `lupo-includes/modules/ai/` (no duplicate PHP here)

**Canonical implementation paths** (required by loaders such as `lupo-bin/initialize_system.php`):

- `lupo-includes/functions/ai_agent_integration.php` — `initializeCoreAIAgents`, `isActorAIRunning`, `ensureActorActive`
- `lupo-includes/functions/ai_activation.php` — activation helpers used with the above

Do **not** add `ai_agent_integration.php` under this `modules/ai/` tree; it fragments discovery and breaks `require_once` paths.

This output complies with Lupopedia Constitutional Root Rules.
