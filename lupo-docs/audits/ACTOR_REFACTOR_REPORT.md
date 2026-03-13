# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: ".\docs\ACTOR_REFACTOR_REPORT.md"
  file_hash: "f78604f4a2ae91ea6a82956d06842ae70201084254ae23fce0a3f1e77155c6b5"
  last_updated_utc: "20260228155738"
  system_version: "4.0.51"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "documentation"
  artifact_kind: "documentation"
  purpose: "Documentation file with LUPOPEDIA HEADERS applied"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.51"]
  tags: ["documentation", "flare_applied"]
  lupo_agent: "windsurf"

lupopedia.edges:
  outbound_edges:
    - { to: "CHANGELOG.md", type: "references", weight: 1.0 }
    - { to: "docs/doctrine/", type: "references", weight: 1.0 }

lupopedia.footer:
  last_verified: "20260228155738"
  last_verified_by: "windsurf"
---

# LUPOPEDIA HEADERS (replaces FLARE)

---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  lupopedia.edges: []
  file_path_from_root: "docs\ACTOR_REFACTOR_REPORT.md"
  file_hash: "14874fc0094d6cb9291c19c2d3f3144eb38d2de766816e70225d2ca2baaa5583"
  file_path_from_root: "docs\ACTOR_REFACTOR_REPORT.md"
  file_hash: "01f3641a147970e6335e54d6c80f887ff32ba2795e8e06fd17bc5108fefcb8f4"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Actor Domain Refactor Report"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "actor_refactor_reportmd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
lupopedia.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

# Actor Domain Refactor Report

**Phase:** Actor domain only (per `docs/HELPER_TO_CLASS_MAPPING_ANALYSIS.md`).  
**Date:** 2026-02-10.

---

## 1. Files changed

| File | Change |
|------|--------|
| **New** `app/Services/ActorService.php` | Created. Implements getActorIdFromAuthUserId, getAuthUserIdFromActorId, createActorForAuthUser, actorSlugExists, allocateAnonymousActorId, getOrAllocateJsrnForActor, mergeAnonymousActorIntoRealActor. All use LUPO_TABLE_PREFIX and PDO_DB with named parameters. |
| `lupo-includes/bootstrap.php` | Load ActorService after DB; set `$GLOBALS['lupo_actor_service']`. |
| `lupo-includes/functions/auth-helpers.php` | Replaced `lupo_get_actor_id_from_auth_user_id`, `lupo_get_auth_user_id_from_actor_id`, `lupo_create_actor_for_auth_user`, `lupo_actor_slug_exists` with thin wrappers delegating to ActorService. |
| `lupo-includes/functions/identity-helpers.php` | Replaced `allocateAnonymousActorId`, `getOrAllocateJsrnForActor`, `mergeAnonymousActorIntoRealActor` with thin wrappers delegating to ActorService. |
| `lupo-includes/modules/auth/auth-controller.php` | Login flow: get/create actor_id via `$GLOBALS['lupo_actor_service']` when set, with fallback to helper. |

---

## 2. Helpers migrated

| Helper | New method (ActorService) | Notes |
|--------|---------------------------|--------|
| `lupo_get_actor_id_from_auth_user_id` | **getActorIdFromAuthUserId(int)** | Returns actor_id for auth_user_id (actor_source_type = 'user'). |
| `lupo_get_auth_user_id_from_actor_id` | **getAuthUserIdFromActorId(int)** | Returns actor_source_id for actor_id. |
| `lupo_create_actor_for_auth_user` | **createActorForAuthUser(int, string, string)** | Slug from email; uniqueness via actorSlugExists. |
| `lupo_actor_slug_exists` | **actorSlugExists(string)** | Exists and is_deleted = 0. |
| `allocateAnonymousActorId` | **allocateAnonymousActorId()** | Next free actor_id in [1000, 9999]. |
| `getOrAllocateJsrnForActor` | **getOrAllocateJsrnForActor(int)** | Reads/allocates $.jsrn in actors.metadata (TOON: metadata text). |
| `mergeAnonymousActorIntoRealActor` | **mergeAnonymousActorIntoRealActor(int, int)** | Updates sessions/events/dialog to real actor_id; merges metadata; marks temp deleted. |

---

## 3. References updated

| File | Old call | New call |
|------|----------|----------|
| auth-controller.php | `lupo_get_actor_id_from_auth_user_id($user['auth_user_id'])` | `$actorService->getActorIdFromAuthUserId((int) $user['auth_user_id'])` when `$GLOBALS['lupo_actor_service']` set, else helper. |
| auth-controller.php | `lupo_create_actor_for_auth_user(...)` | `$actorService->createActorForAuthUser(...)` when service set, else helper. |

No other PHP files called the identity-helpers (allocateAnonymousActorId, getOrAllocateJsrnForActor, mergeAnonymousActorIntoRealActor); those remain available via thin wrappers for future callers.

---

## 4. Helpers removed or wrapped

- **auth-helpers.php:** `lupo_get_actor_id_from_auth_user_id`, `lupo_get_auth_user_id_from_actor_id`, `lupo_create_actor_for_auth_user`, `lupo_actor_slug_exists` — **replaced with thin wrappers** that call `$GLOBALS['lupo_actor_service']` when set; otherwise return false (or, for create, false).
- **identity-helpers.php:** `allocateAnonymousActorId`, `getOrAllocateJsrnForActor`, `mergeAnonymousActorIntoRealActor` — **replaced with thin wrappers** that delegate to ActorService when set; otherwise return null / 0 / no-op. PDO parameter kept for backward-compatible signature.

---

## 5. Confirmations

- **Actor domain only:** Only Actor-related helpers (auth-helpers actor four, identity-helpers three) and auth-controller’s actor get/create were changed. AuthService, AuthRoleResolver, AuthContextResolver, Session, SessionHandler, Collection* services, Redirect/Limits/Atom/Upload, Crafty Syntax were not modified.
- **Actor logic in ActorService:** All actor–auth_user linkage, actor creation, slug checks, anonymous allocation, JSRN, and merge logic live in `App\Services\ActorService`. Procedural helpers are thin wrappers only.
- **Table names use LUPO_TABLE_PREFIX:** ActorService uses `$this->prefix` (from LUPO_TABLE_PREFIX) for all table names: actors, sessions, actor_events, session_events, tab_events, content_events, world_events, dialog_messages. No hardcoded `lupo_*` in the service.
- **DB access uses PDO_DB:** ActorService is constructed with `$db` (PDO_DB from bootstrap). All queries use `$this->db->fetchRow`, `fetchAll`, `query`, `insert`, `beginTransaction`, `commit`, `rollBack`. Named parameters only; no raw SQL or procedural DB helpers.

---

## 6. Schema and TOON alignment

- **lupo_actors:** TOON column `metadata` (text) used for JSRN and merge (JSON_EXTRACT/JSON_SET). Install uses `metadata text`; if your environment uses a `metadata_json` column, consider a one-time migration or service branch to match.
- Merge tables and columns used: lupo_sessions.actor_id, lupo_actor_events.actor_id, lupo_session_events.actor_id, lupo_tab_events.actor_id, lupo_content_events.actor_id, lupo_world_events.actor_id, lupo_dialog_messages.from_actor_id. All prefixed via LUPO_TABLE_PREFIX.
- No references to actor_roles, sessions, or operator tables.

---

## 7. How to get ActorService in PHP

```php
$actorService = $GLOBALS['lupo_actor_service'] ?? null;
if ($actorService) {
    $actorId = $actorService->getActorIdFromAuthUserId($authUserId);
    $actorId = $actorService->createActorForAuthUser($authUserId, $email, $displayName);
    $exists = $actorService->actorSlugExists($slug);
    $authUserId = $actorService->getAuthUserIdFromActorId($actorId);
    $anonId = $actorService->allocateAnonymousActorId();
    $jsrn = $actorService->getOrAllocateJsrnForActor($actorId);
    $actorService->mergeAnonymousActorIntoRealActor($tempActorId, $realActorId);
}
```

Existing calls to the procedural helpers continue to work via the thin wrappers.
