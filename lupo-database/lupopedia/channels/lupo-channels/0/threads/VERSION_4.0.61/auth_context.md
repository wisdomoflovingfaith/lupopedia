---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  file_path_from_root: "lupo-database/lupopedia/channels/lupo-channels/0/threads/VERSION_4.0.61/auth_context.md"
  last_modified_utc: "20260306"
  system_version: "4.0.61"
  channel_id: 0
  actor_name: "cursor"
  purpose: "Auth user and actor context for Antigravity v4.0.61"
  traits: ["documentation", "feature", "v4.0.61", "auth", "context"]
  tags: ["auth", "context", "antigravity", "implementation"]
  lupo_agent: "cursor"
---

# Auth User & Actor Context for Antigravity

## Overview

Version 4.0.61 gives Antigravity (and other agents) access to: current authenticated user (`lupo_auth_users`), current actor context (`lupo_actors`), and paired relationships (agent ↔ human).

## Components

### AuthService (App\Auth\AuthService)

- `getCurrentUser()` — Current session user (web)
- `getUserByAuthUserId($id)` — Auth user row by auth_user_id
- `getUserByActorId($actorId)` — Auth user by actor_id
- `getUserByActorName($actorName)` — Auth user by actor_name

### ActorService (App\Services\ActorService)

- `getAuthUserIdForActor($actor)` — Resolves actor to auth_user_id (human or paired human)
- `getActorContext($actor, $authService)` — Actor row plus auth_user row and auth_user_id

### AntigravityContext (lupo-includes/classes/AntigravityContext)

- Built from ContextResolver::resolve() and optional AuthService
- `getAuthUser()`, `getActor()`, `isAuthenticatedHuman()`, `isPairedAgent()`, `getResolutionContext()`, `getAntigravityContext()`

### Integration (lupo-agents/antigravity/context.php)

- Loads config, ContextResolver, AntigravityContext; sets `$GLOBALS['antigravity_context']`.

### CLI

```bash
php lupo-bin/lupo.php auth      # or: who
php lupo-bin/lupo.php actor-context
```

## Resolution Priority (Antigravity)

1. **Authenticated human** — Human has final authority  
2. **Paired agent** — Agent acts for human; may need review  
3. **Autonomous agent** — Agent decides independently  

## Files

- `lupo-database/lupopedia/content/lupo-app/auth/AuthService.php`
- `lupo-database/lupopedia/content/lupo-app/Services/ActorService.php`
- `lupo-includes/classes/AntigravityContext.php`
- `lupo-agents/antigravity/context.php`
- `lupo-bin/lupo.php` (auth, actor-context)
- [docs/auth.md](../../../../../../../docs/auth.md)
