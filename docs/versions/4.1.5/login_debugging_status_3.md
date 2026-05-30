# Login Debugging Status Report #3

**Date:** 2026-04-24
**Reporter:** Actor 116 (Claude Code)
**Priority:** Critical

---

## Fixes Applied

### Fix 1 -- Bootstrap load added to admin.php (APPLIED)

**File:** `admin.php`, line 80

Added the missing `require_once` immediately after the config load:

```php
require_once LUPOPEDIA_CONFIG_PATH;
require_once LUPOPEDIA_PATH . '/includes/bootstrap.php';
```

Bootstrap now runs on every `admin.php` request. This initialises the DB
global, starts the PHP session, validates it against `lupo_sessions`, and
wires `$GLOBALS['lupo_auth_service']` as `App\Auth\AuthService`. Previously
none of these steps ran and every request redirected unconditionally to
`login.php`.

### Fix 2 -- getCurrentUser() two-path query (PENDING)

**File:** `database/lupopedia/content/app/auth/AuthService.php`

The `getCurrentUser()` method still uses a single query restricted to
`actor_source_type = 'user'`. Wolfie (actor_id=1) has `actor_source_type =
'system'` and is excluded. This fix has not yet been applied.

---

## Current Problem

Login still produces `ERR_TOO_MANY_REDIRECTS` on `admin.php`.

From the error logs after Fix 1 was applied:

- Bootstrap runs correctly; session is started and validated.
- `$GLOBALS['lupo_auth_service']` is set to `App\Auth\AuthService`.
- `requireLogin()` calls `getCurrentUser()`.
- `getCurrentUser()` calls `validateSession()` -- returns actor_id=1 (correct).
- The `lupo_actor_auth_users` join is attempted but returns no row for
  actor_id=1 (no mapping entry exists in the table for wolfie).
- Falls through to the single-query fallback restricted to
  `actor_source_type = 'user'`.
- Wolfie's actor has `actor_source_type = 'system'` -- query returns null.
- `getCurrentUser()` returns false.
- `requireLogin()` redirects to `login.php`.
- Loop continues.

---

## Root Cause (Updated)

| Cause | Status |
|-------|--------|
| admin.php never loaded bootstrap.php | Fixed |
| getCurrentUser() rejects system hybrid actors | Still active |

The secondary bug is now the only remaining blocker. `App\Auth\AuthService::
getCurrentUser()` has a single query path gated on `actor_source_type = 'user'`.
System hybrid actors such as wolfie (actor_source_type='system', actor_id=1)
have no path through this logic and always produce a false return.

---

## Next Action

Apply Fix 2 to `database/lupopedia/content/app/auth/AuthService.php`.

Replace the single query in `getCurrentUser()` with a two-path approach:

- **Path 1:** Join through `lupo_actor_auth_users` (status='active'). Handles
  all actor types regardless of `actor_source_type`. Ordered by `is_primary DESC`
  so the primary credential wins.
- **Path 2 (fallback):** Direct `actor_source_id` join, restricted to
  `actor_source_type IN ('user', 'lupo_auth_users')`. Covers legacy actors
  that have no mapping row.

This mirrors the logic already working correctly in the old
`includes/classes/AuthService::getCurrentUser()` which `login.php` uses.

---

## Overall Status

| Component | State |
|-----------|-------|
| Bootstrap loading on admin.php | Fixed |
| PHP session creation after login | Working |
| Session validation on subsequent requests | Working |
| Actor lookup for wolfie (actor_id=1, type='system') | Failing |
| getCurrentUser() for 'user' type actors | Working |
| Login flow end-to-end | Still blocked -- redirect loop |
