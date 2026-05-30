---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: database/lupopedia/channels/channel_id/42/collections/identity-collection.md
  web_path: https://www.lupopedia.com/lupopedia/database/lupopedia/channels/channel_id/42/collections/identity-collection.md
  status: null
  when_updated: '20260513033046'
  trust_tier: null
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: collection
  artifact_kind: source_of_truth
  channel_key: null
  federation_node_id: null
  thread_key: null
  lupopedia.schema: documentation
  prd_cluster: null
  title: null
  summary: null
---

# Collection: Identity (SOT)

## Description

The Source of Truth (SOT) collection for Lupopedia's canonical 5-layer identity model that separates and coordinates:

1. **Auth User** (`lupo_auth_users`) — Human login credentials and authentication metadata
2. **Actor** (`lupo_actors`) — Operational orchestration identity; `actor_id` is the universal key
3. **Department** (`lupo_actor_departments`, `lupo_departments`) — Execution context and authority scope
4. **Agent** (`lupo_agents`) — AI model configuration, prompts, and capabilities
5. **Faucet** (`lupo_agent_faucets`) — IDE/API execution surface (not orchestration identity)

This model eliminates layer confusion and prevents privilege ambiguity by enforcing strict separation of concerns.

## Associated Documentation

- **Primary Doctrine**: `docs/doctrine/IDENTITY_LAYERS_DOCTRINE.md`
- **Companion Model**: `docs/doctrine/IDENTITY_MODEL.md`
- **Authoritative Registry**: `database/lupopedia/actors/actor_id/registry.json`
- **Origin Thread**: Channel 42, Thread 1006 (Identity Model Clarification, 4.0.87 WS3)

## Key Tables

- `lupo_auth_users` — Human login and authentication
- `lupo_actors` — Actor operational identities
- `lupo_actor_departments` — Actor-department bindings
- `lupo_departments` — Department context and authority scopes
- `lupo_agents` — Agent configuration and metadata
- `lupo_agent_faucets` — IDE/API surface bindings

## Implementation Guidance

- Keep identity-layer terminology consistent across docs and code
- Use `actor_id` as the operational join key in all relationships
- Treat `lupo_agents` as configuration metadata, not identity authority
- Treat `lupo_agent_faucets` as interface surfaces, not orchestration identity
- Enforce server-side actor context resolution (never trust client-supplied actor_id)
