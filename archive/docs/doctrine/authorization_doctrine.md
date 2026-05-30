---
lupopedia.headers:
  header_format_version: "4.1.0"
  file_path_from_root: "docs/doctrine/AUTHORIZATION_DOCTRINE.md"
  web_path: null
  status: ""
  when_updated: null
  trust_tier: null
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: doctrine
  artifact_kind: authorization
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
# file: Authorization Doctrine — web_path: http://www.lupopedia.com/doctrine/AUTHORIZATION_DOCTRINE

# Authorization Doctrine (v4.0.73)

## 1. Principle

Authorization is enforced **in application code** before performing an action. No database triggers or stored procedures. Kernel operations (e.g. send message, create channel) MUST check authorization via **TraitEnforcer** (or equivalent) before executing.

## 2. Schema (install / TOON)

Table: `lupo_action_authorization`. Columns: `action_authorization_id`, `action_key` (UNIQUE), `description`, `required_trait_keys` (text/JSON), `required_capabilities`, `required_role_keys` (text/JSON), `requires_all_conditions` (tinyint), `created_ymdhis`, `created_by_actor_id`. No FK.

## 3. TraitEnforcer

- **actorHasTrait($actor_id, $trait_key, $federation_node_id)** — queries `lupo_actor_traits`.
- **isActionAuthorized($actor_id, $action_key, $channel_id)** — loads action from `lupo_action_authorization`; checks traits and (if channel_id present) roles in `lupo_actor_channel_roles`. If no row exists for action_key, returns true (allow). Otherwise enforces required_trait_keys / required_role_keys; `requires_all_conditions` means ALL must match, else ANY match grants.

## 4. Pre-action hooks

Example: channel send message API loads TraitEnforcer, calls `isActionAuthorized($actor_id, 'dialog.send_message', $channel_id)`; if false, returns 403. Same pattern for other kernel operations (channel.create, rules.modify, traits.assign).

## 5. References

- Traits: TRAITS_DOCTRINE.md
- Identity layers: IDENTITY_LAYERS_DOCTRINE.md
- TOON: `database/lupopedia/toon/lupo_action_authorization.toon`
