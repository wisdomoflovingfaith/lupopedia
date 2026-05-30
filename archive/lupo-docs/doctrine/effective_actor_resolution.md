---
lupopedia.headers:
  header_format_version: "4.1.0"
  file_path_from_root: "lupo-docs/doctrine/EFFECTIVE_ACTOR_RESOLUTION.md"
  web_path: "http://www.lupopedia.com/doctrine/EFFECTIVE_ACTOR_RESOLUTION"
  status: ""
  when_updated: null
  trust_tier: null
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: doctrine
  artifact_kind: runtime_resolution
  channel_key: null
  federation_node_id: null
  thread_id: ""
  content_id: null
  pk_id: null
  pk_slug: ""
  parent_pk_id: ""
  lupopedia.schema: doctrine
  title: ""
  summary: ""
---
# Effective Actor Resolution

## Objective

This doctrine documents how Lupopedia resolves the effective posting actor for authenticated web operations, especially channel chat and related API paths.

## Resolution Inputs

Effective actor resolution may consult:

- current authenticated user from `AuthService`
- active actor stored in session
- allowed actors from `ActorService::getActorsUserCanActAs()`
- saved chat preferences from `EffectiveActorResolver::getPreferences()`
- optional channel guard using channel membership

## Implemented Resolution Order

The current 4.0.87 implementation is actor-first:

1. active actor from session, if still allowed
2. explicit `preferred_actor_id`, if allowed
3. current authenticated user's default actor
4. preferred department fallback inside the allowed actor set
5. first allowed actor that passes the channel guard

If no allowed actor can satisfy the guard, resolution returns `actor_id = 0`.

## Agent Preference Rule

`preferred_agent_id` may be stored in session preference state for UI continuity and future behavior binding, but it is not a direct actor selector.

This means:

- client code must not turn `preferred_agent_id` into a posting actor
- server code must not insert `preferred_agent_id` into actor candidate ordering as if it were an `actor_id`
- agent preference remains advisory unless a later runtime model introduces an explicit actor-agent binding step

## Department Rule

Department preference is actor-scoped fallback context.

- If a preferred department is set, candidate actors outside that department are skipped
- If explicit actor candidates do not satisfy the department preference, resolution searches the allowed actor set for a matching department actor
- Department does not belong to the agent layer

## Allowed Actor Source

The set of actors a human may operate as must come from actor authorization logic, not from arbitrary client input.

Current grounded surfaces:

- `ActorService::getActorsUserCanActAs()` provides the allowed actor list
- `switch-actor.php` only permits switching into actors inside that allowed set
- `channels-api.php` resolves the effective actor server-side and does not trust client-supplied actor identity

## Pairing Authority

For human-to-actor pairing doctrine, the authoritative mapping layer is `lupo_actor_auth_users`.

Some runtime code still uses actor source metadata or transitional lookups. Where implementation and doctrine differ, new admin-facing alignment should describe current behavior honestly while continuing to move toward actor-auth pairing authority.

## Channel Guard

When a channel is provided:

- admins may bypass channel membership checks according to current admin logic
- non-admin actors must pass membership guard for the requested channel

If channel guard fails for all candidates, no effective actor is returned.

## UI Consequences

### Channel chat admin UI

- explicit actor override may switch the active actor
- department preference narrows server-side fallback
- agent preference is advisory only
- message posting still resolves actor on the server through `EffectiveActorResolver`

### Users and actors admin UI

- auth user editing must stay on the human account layer
- actor listing must identify the operational actor layer separately from agent behavior and department scope

## Hard Rules

- never trust client-supplied actor identity for posting
- never use agent preference as an actor surrogate
- never route by faucet identity
- always resolve from allowed actors first

## Related Doctrine

- `ACTOR_AGENT_AUTH_USER_MODEL.md`
- `AUTH_USERS_ACTORS_AGENTS_FAUCETS_DOCTRINE.md`
- `IDENTITY_MODEL.md`