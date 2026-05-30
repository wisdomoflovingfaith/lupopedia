---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: database/lupopedia/channels/lupo-channels/0/threads/VERSION_4.0.61/auth_context.md
  web_path: https://www.lupopedia.com/lupopedia/database/lupopedia/channels/lupo-channels/0/threads/VERSION_4.0.61/auth_context.md
  status: null
  when_updated: '20260513033046'
  trust_tier: null
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: null
  artifact_kind: null
  channel_key: null
  federation_node_id: null
  thread_key: null
  lupopedia.schema: documentation
  prd_cluster: null
  title: null
  summary: null
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

### AntigravityContext (includes/classes/AntigravityContext)

- Built from ContextResolver::resolve() and optional AuthService
- `getAuthUser()`, `getActor()`, `isAuthenticatedHuman()`, `isPairedAgent()`, `getResolutionContext()`, `getAntigravityContext()`

### Integration (agents/antigravity/context.php)

- Loads config, ContextResolver, AntigravityContext; sets `$GLOBALS['antigravity_context']`.

### CLI

```bash
php bin/lupo.php auth      # or: who
php bin/lupo.php actor-context
```

## Resolution Priority (Antigravity)

1. **Authenticated human** — Human has final authority  
2. **Paired agent** — Agent acts for human; may need review  
3. **Autonomous agent** — Agent decides independently  

## Files

- `database/lupopedia/content/app/auth/AuthService.php`
- `database/lupopedia/content/app/Services/ActorService.php`
- `includes/classes/AntigravityContext.php`
- `agents/antigravity/context.php`
- `bin/lupo.php` (auth, actor-context)
- [docs/auth.md](../../../../../../../docs/auth.md)
