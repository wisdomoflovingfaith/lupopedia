# Login Debugging Status Report

**Date:** 2026-04-24
**Reporter:** Actor 116 (Claude Code)
**Priority:** Critical

---

## Summary of Work Completed

### 1. Initial Diagnosis

Investigated a post-login redirect loop where the user was immediately returned
to the login page after a successful authentication. Session rows were being
inserted correctly into `lupo_sessions` with the right `actor_id` and active
flags, but the session was not being recognized on the very next HTTP request.

### 2. Root Cause Identified

**File:** `app/auth/Session.php`
**Method:** `Session::getSessionId()` (line 846)

The method intentionally skipped calling `session_start()` to avoid creating
a new session. However, PHP's `session_id()` returns an empty string when the
session has not been started, even when a valid PHPSESSID cookie is present in
the incoming request. The result was that `AuthService::getCurrentUser()` always
received an empty session ID and returned false, causing `isLoggedIn()` to fail
on every page load after login.

### 3. Fix Applied

Updated `Session::getSessionId()` to check for the PHPSESSID cookie before
calling `session_start()`. When the cookie is present, `session_start()` safely
restores the existing session ID from the cookie rather than generating a new
random one. When the cookie is absent, the method returns an empty string as
before, preventing phantom session creation for unauthenticated requests.

---

## Current Blocking Issue

After the session fix, a new critical error surfaced:

```
SQLSTATE[HY093]: Invalid parameter number: mixed named and positional parameters
```

This error occurs on every UPDATE to the `lupo_sessions` table when the system
attempts to store metadata such as `login_redirect` after login.

**Root cause:** `PDO_DB::update()` builds SET clauses using positional `?`
placeholders. Several callers pass a WHERE clause containing named placeholders
such as `session_id = :sid`. PDO rejects any prepared statement that mixes
positional (`?`) and named (`:name`) parameters in the same query. The error
is caught silently inside `PDO_DB::update()`, which logs it and returns `0`,
so metadata writes fail without surfacing a visible exception -- but the missing
metadata causes subsequent redirect and auth checks to behave incorrectly.

**Affected queries (examples):**

```sql
-- What PDO_DB::update() was generating:
UPDATE `lupo_sessions` SET `metadata` = ?, `updated_ymdhis` = ? WHERE session_id = :sid

-- Also affected (same pattern, different tables):
UPDATE `lupo_auth_users` SET `last_login_ymdhis` = ? WHERE auth_user_id = :id
UPDATE `lupo_channels` SET ... WHERE channel_id = :id
```

---

## Next Steps

1. Fix `PDO_DB::update()` in `includes/classes/pdo_db.php` to convert any named
   placeholders in the WHERE string to positional `?` before building the final
   SQL, so all parameters are consistently positional.

2. Verify the conversion handles both `array('sid' => $v)` and
   `array(':sid' => $v)` key styles, both of which exist in the codebase.

3. Retest the full login flow: submit credentials, confirm redirect to admin.php
   succeeds, confirm session metadata (login_redirect, password_change_required)
   is written and read correctly.

4. Confirm no regression in logout, session touch, and garbage collection paths.

---

## Files Modified

| File | Change |
|------|--------|
| `app/auth/Session.php` | `getSessionId()` at line 846 -- cookie-aware `session_start()` |

---

## Status

| Component | State |
|-----------|-------|
| Session row creation | Working |
| PHP session cookie set on login | Working |
| Session ID read on subsequent requests | Fixed (pending SQL fix to confirm) |
| Metadata UPDATE to lupo_sessions | Broken -- mixed parameter SQL error |
| Post-login redirect to admin.php | Still broken |
| Overall | One fix applied; one new critical SQL bug identified and in progress |
