---
# FLARE Header
lupopedia.headers:
  lupopedia.schema: "documentation"
  file_path_from_root: "lupo-docs/auth.md"
  version_when_written: "4.0.84"
  last_modified_utc: "20260306"
  purpose: "Authentication and actor context for Antigravity and conflict resolution"
  traits: ["auth", "actor", "antigravity", "v4.0.61"]
  tags: ["auth", "actor", "lupo_auth_users", "antigravity"]
---

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
