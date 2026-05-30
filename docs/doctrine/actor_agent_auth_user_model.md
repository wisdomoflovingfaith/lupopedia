---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/doctrine/actor_agent_auth_user_model.md
  web_path: https://www.lupopedia.com/lupopedia/docs/doctrine/actor_agent_auth_user_model.md
  status: active
  when_updated: '20260513033046'
  trust_tier: null
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: doctrine
  artifact_kind: reference
  channel_key: null
  federation_node_id: 0
  thread_key: doctrine-header-repair
  lupopedia.schema: doctrine
  prd_cluster: null
  title: ''
  summary: ''
---
# file: ACTOR_AGENT_AUTH_USER_MODEL — delegation: cursor:root

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

- `login_user_id`
- `actor_id`
- `agent_id`
- `department_id` or department context
- `faucet_slug`
- `channel_id`
- `thread_id`

### Session Binding Example

```yaml
# Session binding example
session_context:
  login_user_id: 1001
  actor_id: 102
  agent_id: 5
  department_id: 7
  faucet_slug: "cursor"
  channel_id: 42
  thread_id: 1003
```

This binding records execution context. It does not erase the separation between layers.

---

## 🧪 Litmus Test for Actor vs Faucet

**The single most useful heuristic**: "Does this have operational agency?"

- **If yes** → It's an **actor**
- **If it's just an interface** → It's a **faucet**

**Examples**:
- **WOLFIE** can orchestrate system changes → **Actor** (operational agency)
- **Cursor IDE** provides editing interface → **Faucet** (interface only)
- **cascade agent** can execute code → **Actor** (operational agency)
- **VS Code** provides development environment → **Faucet** (interface only)

---

## 🚰 When Faucets Have Actor IDs

### Attribution vs Operational Agency

Faucets can have `actor_id` values for **attribution purposes** when they generate artifacts, but they are not operational actors.

**Key distinction**:
- **Faucet actor_id**: Attribution identity for generated artifacts
- **Operational actor_id**: Identity that performs actions with agency

**Example**:
- **Cursor (actor_id 102)** generates documentation as an attribution identity
- **WOLFIE (actor_id 1)** performs the actual orchestration through Cursor faucet

### When This Happens

Faucets have registered actor IDs when they:
1. **Generate artifacts** (documentation, code, reports)
2. **Need attribution** for audit trails
3. **Act as execution surfaces** for other actors

**Rule**: Faucet `actor_id` is for attribution, not operational agency. The faucet remains an interface, not an actor with independent decision-making capability.

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

- **[IDENTITY_MODEL_QUICKSTART_4.0.88.md](IDENTITY_MODEL_QUICKSTART_4.0.88.md)** - Canonical quickstart guide for identity model
- **[EFFECTIVE_ACTOR_RESOLUTION.md](EFFECTIVE_ACTOR_RESOLUTION.md)** - Runtime actor selection path
- **[lupo_actor_auth_users.md](../database/lupopedia/tables/active/lupo_actor_auth_users.md)** - Actor-login identity pairing table