---
lupopedia.headers:
  lupopedia.version: "4.0.87"
  lupopedia.schema: "status_report"
  system_version: "4.0.87"
  file_path_from_root: "lupo-docs/status/actor_centric_pairing_web_interface_report_4_0_87.md"
  web_path: "http://www.lupopedia.com/status/actor_centric_pairing_web_interface_report_4_0_87"
  last_modified_utc: "20260325_161000"
  channel_id: 42
  actor_id: 102
  actor_name: "cursor"
  faucet_name: "cursor"
  delegation_chain: "cursor:root"
  artifact_type: "completion_report"
  artifact_kind: "status"
  purpose: "Completion artifact for the 4.0.87 actor-centric pairing model alignment in web/admin UI and doctrine docs."
  tags: ["4.0.87", "identity", "admin", "actor", "agent", "completion_report"]
lupopedia.footer:
  last_verified: "20260325_161000"
  last_verified_by: "cursor"
  last_verified_by_actor_id: 102
  orchestrator: "cursor:root"
---
# Actor Centric Pairing Web Interface Report 4.0.87

## Scope Completed

- clarified auth user versus actor versus agent versus department in admin UI surfaces
- aligned channel chat UI wording with actor-first server resolution
- removed client-side agent-to-actor substitution from admin channel chat
- updated actor and user admin data sources to better reflect actor pairing reality
- added doctrine documents for the actor-centric model and effective actor resolution

## Files Changed

- `lupo-includes/classes/EffectiveActorResolver.php`
- `lupo-includes/classes/AdminUsersHandler.php`
- `lupo-includes/classes/AdminActorsHandler.php`
- `lupo-includes/themes/default/layouts/admin_sections/users.php`
- `lupo-includes/themes/default/layouts/admin_sections/agents.php`
- `lupo-includes/themes/default/layouts/admin_sections/departments.php`
- `lupo-includes/themes/default/layouts/admin_sections/channel_chat.php`
- `admin.php`
- `lupo-docs/doctrine/ACTOR_AGENT_AUTH_USER_MODEL.md`
- `lupo-docs/doctrine/EFFECTIVE_ACTOR_RESOLUTION.md`
- `lupo-docs/doctrine/AUTH_USERS_ACTORS_AGENTS_FAUCETS_DOCTRINE.md`
- `lupo-docs/doctrine/IDENTITY_MODEL.md`

## Grounded Findings Applied

### Admin channel chat

- the largest practical identity drift was in client-side chat code treating `preferred_agent_id` as if it were an actor id
- 4.0.87 alignment keeps explicit actor override as the only client-side actor switch path
- department and agent preference remain part of server-side resolution inputs and UI state

### Admin actors

- actor display now treats actor as the operational identity layer
- paired auth user display resolves from `lupo_actor_auth_users` first, then uses legacy actor source metadata as fallback context

### Admin users

- users page now explicitly identifies itself as auth-user management
- primary actor display is sourced from `lupo_actor_auth_users` ordering rather than only direct actor source linkage

## Doctrine Delivered

- `ACTOR_AGENT_AUTH_USER_MODEL.md` documents the actor-centric presentation model
- `EFFECTIVE_ACTOR_RESOLUTION.md` documents current runtime resolution order and hard rules

## Current State Versus Target State

### Current state now reflected honestly

- runtime posting identity is actor-first
- actor authorization still depends on `ActorService::getActorsUserCanActAs()` and current auth/session logic
- some legacy source fields remain in runtime code and admin display as fallback context

### Target state reinforced

- `lupo_actor_auth_users` remains the doctrinal human pairing authority
- departments remain actor-scoped
- agent preference must not collapse into actor identity

## Residual Drift Not Expanded In This Pass

- broader runtime migration from legacy actor source lookups to exclusive `lupo_actor_auth_users` usage was not completed here
- agent-specific admin views still operate through actor-backed listings rather than a dedicated joined `lupo_agents` management surface

## Validation Target

Post-change validation should confirm:

- admin pages render without PHP errors
- channel chat sends messages successfully with explicit actor override and without it
- actor and user listings display expected pairings for known seeded users