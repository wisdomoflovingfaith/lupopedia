---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: doctrine
  file_path_from_root: "lupo-docs/doctrine/AUTH_DOCTRINE.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/doctrine/AUTH_DOCTRINE.md"
  last_modified_utc: "20260403113047"
  when_updated: "20260403113047"
  federation_node_id: 0
  channel_id: 42
  thread_id: "doctrine-header-repair"
  actor_id: 102
  actor_name: "cursor"
  delegation_chain: "cursor:root"
  artifact_type: "doctrine"
  artifact_kind: "reference"
  purpose: "AUTH DOCTRINE"
  status: active
  tags:
    - "doctrine"
    - "header_repair"
lupopedia.edges:
  outbound_edges:
    - to: "lupo-docs/prd/32_actor_authority_agent_roles.md"
      type: implements
      weight: 1.0
      reason: "Doctrine PRD lineage; constitutional audit 20260403"

lupopedia.footer:
  last_verified: "20260403113047"
  verified_by:
    identity_type: actor
    actor_id: 2
    name: "lilith"
  verified_via:
    type: "audit"
    script: "fix_doctrine_headers"
  next_action:
    - "Run: python lupo-scripts/apply_doctrine_prd_lineage.py --apply"
---

# file: AUTH_DOCTRINE — delegation: cursor:root

# Lupopedia authentication and actor context

## Overview

Lupopedia uses **lupo_auth_users** for human authentication and **lupo_actors** for identity (human, agent, system). The current authenticated user and active actor are exposed so agents (e.g. Antigravity) can make conflict-resolution and context-aware decisions.

## Current user and actor

| Access | Description |
|--------|-------------|
| **CLI** | `php lupo-bin/lupo.php auth` or `php lupo-bin/lupo.php who` — current authenticated user |
| **CLI** | `php lupo-bin/lupo.php actor-context` — actor + auth status (for Antigravity) |
| **Code** | `ContextResolver::resolve()` — base context (actor_name, actor_id, session_mode, …) |
| **Code** | `AntigravityContext` — shaped context with auth_user and actor for conflict resolution |
| **Web** | `$GLOBALS['lupo_auth_service']->getCurrentUser()` — logged-in user when session is valid |

## Auth and actor APIs

- **AuthService** (App\Auth\AuthService): `getCurrentUser()`, `getUserByAuthUserId($id)`, `getUserByActorId($actorId)`, `getUserByActorName($actorName)`.
- **ActorService** (App\Services\ActorService): `getAuthUserIdForActor($actor)`, `getActorContext($actor, $authService)`.
- **AntigravityContext** (lupo-includes/classes/AntigravityContext): built from `ContextResolver::resolve()` and optional AuthService; provides `getAuthUser()`, `getActor()`, `getResolutionContext()`, `getAntigravityContext()`, `isAuthenticatedHuman()`, `isPairedAgent()`.

## Resolution priority (Antigravity)

When Antigravity resolves conflicts:

1. **Authenticated human** — human has final authority (`isAuthenticatedHuman()`).
2. **Paired agent** — agent acts for a human; may require human review (`isPairedAgent()`).
3. **Autonomous agent** — agent decides independently (no paired human).

## See also

- [lupopedia_whoami_readme.md](lupopedia_whoami_readme.md) — dual-identity and session context
- [HELP.md](HELP.md) — help hub
- [CLI.md](CLI.md) — CLI reference (auth, actor-context)
