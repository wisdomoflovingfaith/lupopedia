---
lupopedia.headers:
  lupopedia.version: "4.0.88"
  lupopedia.schema: "documentation"
  file_path_from_root: "lupo-database/lupopedia/channels/channel_id/42/collections/identity-collection.md"
  web_path: "http://www.lupopedia.com/lupo-docs/collections/identity-collection"
  when_updated: "20260325240000"
  last_modified_utc: "20260325240000"
  system_version: "4.0.88"
  channel_id: 42
  actor_id: 26
  actor_name: "thoth"
  delegation_chain: "26:1"
  artifact_type: "collection"
  artifact_kind: "source_of_truth"
  purpose: "Canonical collection mapping for 5-layer identity model: auth user, actor, department, agent, and faucet"
  tags: ["identity", "actors", "agents", "departments", "auth", "faucets", "4.0.88", "collection"]

lupopedia.edges:
  outbound_edges:
    - { to: "lupo-docs/doctrine/IDENTITY_LAYERS_DOCTRINE.md", type: "references", weight: 1.0, reason: "Canonical identity layer doctrine" }
    - { to: "lupo-docs/doctrine/IDENTITY_MODEL.md", type: "references", weight: 0.95, reason: "Identity model companion" }
    - { to: "lupo-database/lupopedia/actors/actor_id/registry.json", type: "maps_to", weight: 1.0, reason: "Actor registry source" }
    - { to: "lupo-channels/42/threads/1006/", type: "derived_from", weight: 1.0, reason: "Original identity clarification thread" }

lupopedia.footer:
  last_verified: "20260325240000"
  last_verified_by: "cursor"
  last_verified_by_actor_id: 102
  orchestrator: "thoth:1"
  next_action: "Maintain alignment with evolving 5-layer identity topology"
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

- **Primary Doctrine**: `lupo-docs/doctrine/IDENTITY_LAYERS_DOCTRINE.md`
- **Companion Model**: `lupo-docs/doctrine/IDENTITY_MODEL.md`
- **Authoritative Registry**: `lupo-database/lupopedia/actors/actor_id/registry.json`
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
