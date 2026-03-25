---
lupopedia.headers:
  lupopedia.version: "4.0.87"
  lupopedia.schema: "doctrine"
  system_version: "4.0.87"
  file_path_from_root: "lupo-docs/doctrine/ACTOR_AGENT_AUTH_USER_MODEL.md"
  web_path: "http://www.lupopedia.com/doctrine/ACTOR_AGENT_AUTH_USER_MODEL"
  last_modified_utc: "20260325_160000"
  channel_id: 42
  actor_id: 102
  actor_name: "cursor"
  faucet_name: "cursor"
  delegation_chain: "cursor:root"
  artifact_type: "doctrine"
  artifact_kind: "identity"
  purpose: "Actor-centric model for auth_user, actor, agent, department, faucet, and session pairing in the web/admin interface."
  tags: ["identity", "auth_user", "actor", "agent", "department", "faucet", "4.0.87"]
lupopedia.edges:
  outbound_edges:
    - { to: "lupo-docs/doctrine/EFFECTIVE_ACTOR_RESOLUTION.md", type: "references", weight: 1.0 }
    - { to: "lupo-docs/doctrine/AUTH_USERS_ACTORS_AGENTS_FAUCETS_DOCTRINE.md", type: "references", weight: 0.9 }
    - { to: "lupo-docs/doctrine/IDENTITY_MODEL.md", type: "references", weight: 0.9 }
    - { to: "lupo-docs/database/lupopedia/tables/active/lupo_actor_auth_users.md", type: "references", weight: 1.0 }
lupopedia.footer:
  last_verified: "20260325_160000"
  last_verified_by: "cursor"
  last_verified_by_actor_id: 102
  orchestrator: "cursor:root"
---
# Actor Agent Auth User Model

## Objective

This doctrine defines how identity layers must be presented and reasoned about in Lupopedia 4.0.87 web and admin surfaces.

The required separation is:

- `auth_user` = human login and account security
- `actor` = operational identity used for permissions, channel participation, authorship, and posting
- `agent` = behavior and model configuration attached to actor execution, but not a replacement for actor identity
- `department` = actor-scoped routing and fallback context
- `faucet` = execution surface only
- `session` = runtime binding tuple that records the active context

## Canonical Storage Layers

### Auth user

- Human login account lives in `lupo_auth_users`
- Passwords, email, display name, and account activation belong here
- Admin profile editing operates on this layer

### Actor

- Runtime identity lives in `lupo_actors`
- Channel membership, channel roles, session actor, authorship, and posting all use `actor_id`
- Departments attach to actors, not to agents

### Actor to auth user pairing

- The authoritative actor to human pairing layer is `lupo_actor_auth_users`
- This table supports many-to-many assignment and deterministic ordering using `is_primary` and `routing_priority`
- Legacy `lupo_actors.actor_source_type` and `actor_source_id` may still exist as fallback metadata, but they are not the long-term pairing authority

### Agent

- Agent behavior configuration lives in `lupo_agents`
- This layer stores model, provider, prompts, safety, and related runtime configuration
- Agent state explains how an actor behaves; it does not replace the actor as the acting identity

### Faucet

- Faucet execution state lives in `lupo_agent_faucets` and session/runtime metadata
- Faucet identifies the execution surface such as Cursor, Windsurf, or another IDE surface
- Faucet is never an actor substitute

## Web And Admin Presentation Rules

### Users page

- Must describe that editing a user updates the auth layer
- Must show that permissions are granted through a linked actor
- Must avoid implying that `auth_user_id` directly owns channel actions

### Actors page

- Must present actor as the operational identity
- Must show department as actor-scoped context
- Must distinguish paired auth user from actor source metadata
- Must not collapse actor and agent into one concept

### Agents page

- May list actor-backed agent surfaces for operations and status
- Must explain that agent behavior is not actor identity
- Must keep faucet or IDE execution context separate from actor identity

### Departments page

- Must explain that departments scope actor routing and fallback
- Must not imply an `agent -> department` ownership model

### Channel chat page

- Must explain that posting is actor-first and resolved server-side
- Explicit actor override may switch active actor
- Department preference may constrain actor fallback
- Agent preference is advisory behavior metadata and must not directly become posting actor identity

## Session Binding

The runtime session context may bind:

- `auth_user_id`
- `actor_id`
- `agent_id`
- `department_id` or department context
- `faucet_slug`
- `channel_id`
- `thread_id`

This binding records execution context. It does not erase the separation between layers.

## Hard Rules

- Do not use `auth_user_id` as the posting identity in channel operations
- Do not derive actor identity directly from agent preference in client code
- Do not add `lupo_agent_departments`
- Do not treat faucet as actor identity
- Do not merge actor and agent terminology in admin help text

## Implemented 4.0.87 Alignment

The 4.0.87 admin alignment uses the following grounded surfaces:

- `AdminUsersHandler` and the users admin view display auth users while routing permissions through actor pairing
- `AdminActorsHandler` resolves paired auth user display from `lupo_actor_auth_users` first
- `EffectiveActorResolver` keeps actor resolution actor-first and retains `preferred_agent_id` as advisory state only
- `AdminChannelChatHandler` and the channel chat admin view preserve server-side actor resolution and stop client-side agent-to-actor substitution

## Related Doctrine

- `AUTH_USERS_ACTORS_AGENTS_FAUCETS_DOCTRINE.md` explains the core separation
- `IDENTITY_MODEL.md` locks the broader canonical identity model
- `EFFECTIVE_ACTOR_RESOLUTION.md` documents the runtime actor selection path