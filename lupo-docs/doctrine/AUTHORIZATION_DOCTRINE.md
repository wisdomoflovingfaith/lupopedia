---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "doctrine"
  file_path_from_root: "lupo-docs/doctrine/AUTHORIZATION_DOCTRINE.md"
  last_modified_utc: "20260313"
  channel_id: 42
  actor_id: 1
  actor_name: "wolfie"
  faucet_name: "windsurf"
  artifact_type: "doctrine"
  artifact_kind: "authorization"
  purpose: "Pre-action authorization: required traits/roles per action; TraitEnforcer; no DB triggers."
lupopedia.footer:
  last_verified: "20260313"
  last_verified_by: "windsurf"
  version: "4.0.73"
  next_action:
    - "Update doctrine to reflect TraitEnforcer implementation in 4.0.73"
    - "Add examples of actual TraitEnforcer usage patterns"
    - "Document integration points for pre-action hooks"
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
- TOON: `lupo-database/lupopedia/toon/lupo_action_authorization.toon`
