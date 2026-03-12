# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "1.0"
  lupopedia.schema: "documentation"
  file_path_from_root: ".\docs\AUTH_REFACTOR_REPORT.md"
  file_hash: "7c375ff449b8f7ea17444d48bd0097dd1237e0c96739c67bbc381cabdba76e4e"
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
  lupopedia.version: "1.0"
  lupopedia.schema: "documentation"
  lupopedia.edges: []
  file_path_from_root: "docs\AUTH_REFACTOR_REPORT.md"
  file_hash: "ba1279bc15a2da67281cc81b404e45a79a071f8d43081bd273bca83e92d512ea"
  file_path_from_root: "docs\AUTH_REFACTOR_REPORT.md"
  file_hash: "72f82a9cc5a08c0a34107cd5a0177b3c3ab8cd027acf0bdb855a47ce7851b336"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Auth Domain Refactor Report"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "auth_refactor_reportmd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
lupopedia.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

# Auth Domain Refactor Report

**Phase:** Auth domain only (per `docs/HELPER_TO_CLASS_MAPPING_ANALYSIS.md`).  
**Date:** 2026-02-10.

---

## 1. Files changed

| File | Change |
|------|--------|
| **New** `app/auth/AuthRoleResolver.php` | Created. Channel-role resolution (lupo_channel_roles, default channel_id = 1), isAdmin, hasAnyChannelRole. |
| **New** `app/auth/AuthService.php` | Created. getCurrentUser, getCurrentUserData, isLoggedIn, getUsername, getDisplayName, isAdmin, hasAnyChannelRole, requireLogin, requireAdmin. |
| `lupo-includes/bootstrap.php` | Load AuthRoleResolver + AuthService; after Session creation, instantiate and set `$GLOBALS['lupo_auth_service']`. |
| `lupo-includes/functions/auth-helpers.php` | Replaced `current_user`, `lupo_is_admin`, `require_login`, `require_admin` with thin wrappers delegating to AuthService. |
| `lupo-includes/functions/auth-ui-helpers.php` | Replaced `lupo_get_current_user_data`, `lupo_is_logged_in`, `lupo_get_username`, `lupo_get_display_name` with thin wrappers; `lupo_render_login_status` now uses AuthService + hasAnyChannelRole for is_operator. |
| `lupo-includes/modules/channels/channels-controller.php` | All 6 actor-resolution blocks now use `$GLOBALS['lupo_auth_service']` with current_user() fallback. |
| `lupo-includes/modules/channels/channel-messages-api.php` | Actor resolution uses AuthService with fallback. |
| `lupo-includes/modules/channels/channel-check-api.php` | Actor resolution uses AuthService with fallback. |
| `lupo-includes/modules/channels/channel-send-api.php` | Actor resolution uses AuthService with fallback. |
| `lupo-includes/modules/channels/operator-pending-visitors-api.php` | Actor resolution uses AuthService with fallback. |
| `lupo-includes/modules/channels/operator-accept-visitor-api.php` | Actor resolution uses AuthService with fallback. |
| `lupo-includes/modules/auth/auth-controller.php` | Login, change-password, and admin dashboard use AuthService for current user and requireAdmin(). |
| `lupo-includes/modules/actors/actors-controller.php` | My-profile and my-profile/save use AuthService for actor resolution. |
| `lupo-includes/modules/module-loader.php` | Operator sign-on and channel list use AuthService for current user. |
| `lupo-includes/themes/default/components/topbar.php` | Current user resolved via AuthService with fallback. |

---

## 2. Helpers migrated

| Helper | New location | Notes |
|--------|--------------|--------|
| `current_user` | **AuthService::getCurrentUser()** | Session → actor+auth_user load → is_admin via AuthRoleResolver. |
| `require_login` | **AuthService::requireLogin()** | Redirect + session store; password_change_required redirect. |
| `require_admin` | **AuthService::requireAdmin()** | requireLogin() then 403 if !is_admin. |
| `lupo_is_logged_in` | **AuthService::isLoggedIn()** | getCurrentUser() !== false. |
| `lupo_get_current_user_data` | **AuthService::getCurrentUserData()** | Same shape as getCurrentUser(); returns null when not logged in. |
| `lupo_get_username` | **AuthService::getUsername()** | Accessor on current user. |
| `lupo_get_display_name` | **AuthService::getDisplayName()** | display_name ?? username. |
| `lupo_is_admin` | **AuthRoleResolver::isAdmin()** (via **AuthService::isAdmin()**) | Channel roles (channel_id = 1) + permissions fallback. |

**AuthContextResolver:** No path-based context helpers were found in auth helpers; none created or moved. Context remains in SessionHandler.

---

## 3. References updated

| File | Old call | New call |
|------|----------|----------|
| channels-controller.php (×6) | `current_user()` for actor_id | `$authService->getCurrentUser()` when `$GLOBALS['lupo_auth_service']` set, else `current_user()` |
| channel-messages-api.php | `current_user()` | `$authService->getCurrentUser()` with fallback |
| channel-check-api.php | `current_user()` | `$authService->getCurrentUser()` with fallback |
| channel-send-api.php | `current_user()` | `$authService->getCurrentUser()` with fallback |
| operator-pending-visitors-api.php | `current_user()` | `$authService->getCurrentUser()` with fallback |
| operator-accept-visitor-api.php | `current_user()` | `$authService->getCurrentUser()` with fallback |
| auth-controller.php | `current_user()` (×4), `require_admin()` (×1) | `$authService->getCurrentUser()` / `$authService->requireAdmin()` with fallbacks |
| actors-controller.php (×2) | `current_user()` | `$authService->getCurrentUser()` with fallback |
| module-loader.php (×2) | `current_user()` | `$authService->getCurrentUser()` with fallback |
| topbar.php | `current_user()` | `$authService->getCurrentUser()` with fallback |
| auth-ui-helpers.php (lupo_render_login_status) | `current_user()` + inline DB for is_operator | `$authService->getCurrentUser()` + `$authService->hasAnyChannelRole($actor_id)` |

---

## 4. Helpers removed or wrapped

- **auth-helpers.php:** `current_user`, `lupo_is_admin`, `require_login`, `require_admin` — **replaced with thin wrappers** that call `$GLOBALS['lupo_auth_service']` when set; fallback behavior when not set (e.g. require_login redirect, require_admin 403).
- **auth-ui-helpers.php:** `lupo_get_current_user_data`, `lupo_is_logged_in`, `lupo_get_username`, `lupo_get_display_name` — **replaced with thin wrappers** delegating to AuthService (with current_user() fallback where relevant).  
- **Not removed:** Actor-domain helpers (`lupo_get_actor_id_from_auth_user_id`, `lupo_create_actor_for_auth_user`, `lupo_actor_slug_exists`, `lupo_get_auth_user_id_from_actor_id`) unchanged per scope.

---

## 5. Confirmations

- **Auth domain only:** Only auth-helpers and auth-ui-helpers (Auth-related helpers) and Auth call sites were changed. No changes to ActorService, identity-helpers, Collection* services, Redirect/Limits/Atom/Upload helpers, Crafty Syntax, Session class, or SessionHandler.
- **Auth logic in AuthService / AuthRoleResolver:** All current-user, login/admin gates, and role checks (admin, any channel role) are implemented in `App\Auth\AuthService` and `App\Auth\AuthRoleResolver`. Procedural helpers are thin wrappers only.
- **Doctrine:** Channel-role model only (lupo_channel_roles, default channel_id = 1); no actor_roles. Session class is the only session source. PDO_DB and LUPO_TABLE_PREFIX used in AuthRoleResolver and AuthService. No raw SQL in new code; no procedural DB helpers added. No FKs, triggers, or DB-side logic.

---

## 6. How to get the auth service in PHP

```php
$authService = $GLOBALS['lupo_auth_service'] ?? null;
if ($authService) {
    $user = $authService->getCurrentUser();       // array|false
    $authService->requireLogin();                 // redirects if not logged in
    $authService->requireAdmin();                // redirects then 403 if not admin
    $authService->isAdmin($actorId);             // bool
    $authService->hasAnyChannelRole($actorId);  // bool (e.g. for operator UI)
}
```

Existing calls to `current_user()`, `require_login()`, `require_admin()`, `lupo_is_admin()`, etc. continue to work via the thin wrappers.