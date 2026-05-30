---
lupopedia.headers:
  header_format_version: "4.1.0"
  file_path_from_root: "docs/doctrine/AUTH_DOCTRINE.md"
  web_path: "http://www.lupopedia.com/lupopedia/docs/doctrine/AUTH_DOCTRINE.md"
  status: "active"
  when_updated: "20260403113047"
  trust_tier: null
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: doctrine
  artifact_kind: reference
  channel_key: null
  federation_node_id: 0
  thread_id: "doctrine-header-repair"
  content_id: null
  pk_id: null
  pk_slug: ""
  parent_pk_id: ""
  lupopedia.schema: doctrine
  title: ""
  summary: ""
---
# file: AUTH_DOCTRINE — delegation: cursor:root

# Lupopedia authentication and actor context

## Overview

Lupopedia uses **lupo_auth_users** for human authentication and **lupo_actors** for identity (human, agent, system). The current authenticated user and active actor are exposed so agents (e.g. Antigravity) can make conflict-resolution and context-aware decisions.

## Current user and actor

| Access | Description |
|--------|-------------|
| **CLI** | `php bin/lupo.php auth` or `php bin/lupo.php who` — current authenticated user |
| **CLI** | `php bin/lupo.php actor-context` — actor + auth status (for Antigravity) |
| **Code** | `ContextResolver::resolve()` — base context (actor_name, actor_id, session_mode, …) |
| **Code** | `AntigravityContext` — shaped context with auth_user and actor for conflict resolution |
| **Web** | `$GLOBALS['lupo_auth_service']->getCurrentUser()` — logged-in user when session is valid |

## Auth and actor APIs

- **AuthService** (App\Auth\AuthService): `getCurrentUser()`, `getUserByAuthUserId($id)`, `getUserByActorId($actorId)`, `getUserByActorName($actorName)`.
- **ActorService** (App\Services\ActorService): `getAuthUserIdForActor($actor)`, `getActorContext($actor, $authService)`.
- **AntigravityContext** (includes/classes/AntigravityContext): built from `ContextResolver::resolve()` and optional AuthService; provides `getAuthUser()`, `getActor()`, `getResolutionContext()`, `getAntigravityContext()`, `isAuthenticatedHuman()`, `isPairedAgent()`.

## Resolution priority (Antigravity)

When Antigravity resolves conflicts:

1. **Authenticated human** — human has final authority (`isAuthenticatedHuman()`).
2. **Paired agent** — agent acts for a human; may require human review (`isPairedAgent()`).
3. **Autonomous agent** — agent decides independently (no paired human).

## See also

- [lupopedia_whoami_readme.md](lupopedia_whoami_readme.md) — dual-identity and session context
- [HELP.md](HELP.md) — help hub
- [CLI.md](CLI.md) — CLI reference (auth, actor-context)
