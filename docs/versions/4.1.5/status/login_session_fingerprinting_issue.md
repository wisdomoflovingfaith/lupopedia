---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: status_report
  when_updated: "20260424000000"
  file_path_from_root: "docs/versions/4.1.5/status/login_session_fingerprinting_issue.md"
  channel_id: 42
  author:
    type: "actor"
    id: 116
    name: "Claude Code"
  delegation_chain: "claude-code:116"
  artifact_type: "status_report"
  artifact_kind: "bug_analysis"
  purpose: "Root cause analysis of post-login redirect-back-to-login bug; session not started on subsequent requests."
  tags: ["session", "auth", "bug", "login", "fingerprint"]
---

# Login Session Fingerprinting Issue -- Diagnostic Report

**Reporter:** Actor 116 (Claude Code)
**Date:** 2026-04-24
**Severity:** Critical -- prevents all logins
**Status:** Root cause identified; fix recommended below

---

## 1. Problem Summary

After a successful login, the user is immediately redirected back to the login
page. The session row IS inserted into lupo_sessions with the correct actor_id.
The problem is that the session is not recognized as valid on the very next
HTTP request (the redirect target, admin.php or similar).

**Observed behavior:**
- AuthService::handleLogin() returns success with a redirect URL
- Session row exists in DB with is_active=1, is_expired=0, actor_id=1
- Next request to admin.php -- isLoggedIn() returns false
- Browser is sent back to login.php

---

## 2. Debug Output Analysis

```
SESSION_HASH_DEBUY: class C IP used: 0:0:0:0
SESSION_HASH_DEBUY: user_id used: unknown
SESSION_HASH_DEBUY: user agent used: mozilla50windowsnt100...
SESSION_HASH_DEBUY: final input string: 0:0:0:0|unknown|mozilla50...|b6ead5bf...
SESSION_HASH_DEBUY: final hash result: 2a0d949b...
```

Three signals in this output, ranked by severity:

1. `user_id used: unknown` -- auth_user_id is NOT included in the hash (by design;
   see section 4.3 below).
2. `class C IP used: 0:0:0:0` -- IPv6 loopback (`::1`) collapses to all-zeros prefix
   (see section 4.2 below).
3. The hash itself is deterministic and consistent -- NOT the cause of the failure.

---

## 3. Root Cause (Primary) -- Session Not Started on Subsequent Requests

**File:** app/auth/Session.php, line 846
**Method:** Session::getSessionId()

```php
public function getSessionId()
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_name('PHPSESSID');
        // Don't call start() - it creates a new session with a random ID
        return session_id(); // Will be empty string if no session
    }
    return session_id();
}
```

**The bug:** PHP's `session_id()` returns an EMPTY STRING when the session has
not been started via `session_start()`, even when the PHPSESSID cookie is
present in the incoming request headers.

**The comment is incorrect.** The comment says "it creates a new session with a
random ID" -- but that is only true if there is NO PHPSESSID cookie. When a
valid PHPSESSID cookie is present, `session_start()` reads that cookie and
RESTORES the existing session ID. It does not generate a random replacement.

**Execution path that fails:**

1. Login: Session::create() calls session_id($id) + session_start() -- OK.
   Cookie Set-Cookie: PHPSESSID=<id>; path=/lupopedia/ is sent in the response.
2. Browser follows redirect to admin.php (or any page).
3. Browser sends Cookie: PHPSESSID=<id> in request headers.
4. admin.php (or bootstrap) never calls session_start() before isLoggedIn().
5. AuthService::isLoggedIn() -> getCurrentUser() -> appSession()->getSessionId()
6. getSessionId() sees session_status() != PHP_SESSION_ACTIVE.
7. Returns session_id() which is '' (empty) -- PHP has NOT read the cookie yet.
8. getCurrentUser() sees $sid === '' and returns false.
9. isLoggedIn() returns false.
10. User is sent back to the login page.

---

## 4. Contributing Issues (Secondary)

### 4.1 getSessionId() Needs Cookie-Aware session_start()

The fix is to call session_start() inside getSessionId() when the PHPSESSID
cookie is present. This is safe because:
- With cookie present: session_start() restores the existing session ID.
- Without cookie present: session_start() would generate a new ID (empty session).
  Guard against this by checking $_COOKIE first.

**Recommended fix for Session::getSessionId() (app/auth/Session.php:846):**

```php
public function getSessionId()
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        $sn = session_name();
        if ($sn === '' || $sn === false) {
            $sn = 'PHPSESSID';
        }
        $cookies = (isset($_COOKIE) && is_array($_COOKIE)) ? $_COOKIE : array();
        if (!isset($cookies[$sn])) {
            return '';
        }
        session_start();
    }
    return session_id();
}
```

This is a narrow, safe change: it only calls session_start() when a matching
cookie is already in the browser -- exactly the case that should succeed. It does
not create phantom sessions for users without cookies.

### 4.2 IPv6 Loopback Produces `0:0:0:0` Prefix

**File:** app/auth/Session.php, line 247
**Method:** Session::ipNetworkPrefix()

When REMOTE_ADDR is `::1` (IPv6 loopback on localhost), inet_pton() returns
16 bytes: 15 null bytes + 0x01. The /64 prefix extracts bytes 0-7 (all zeros).
dechex(0) for all four hextets produces `0:0:0:0`.

**Impact on the redirect issue:** None directly. The identity hash produced from
`0:0:0:0` is consistent between the login request and the validation request on
localhost. getCurrentUser() does not validate the fingerprint hash -- it only
checks that session_id exists in DB with the right flags.

**Impact on fingerprint value:** On a localhost dev environment, every session
for every user will share the same IP prefix in the hash (0:0:0:0). This is
acceptable for local dev but worth noting.

**No code change needed here for the current bug.** If desired, a detection
branch could map `::1` and `127.0.0.1` to a fixed dev sentinel like `dev-local`
to make the intent clearer in logs.

### 4.3 auth_user_id Is Never Included In session_identity_hash

**File:** app/auth/Session.php, line 604 (createEmbedSession)

```php
$session_identity_hash = self::computeIdentityHash($fp['ip'], $fp['user_agent'], null);
```

The third argument is always null. Inside computeIdentityHash(), null is
substituted with the string 'unknown'. So the debug output `user_id used: unknown`
is correct and expected per the current design (comment: "CRITICAL: DO NOT use
actor_id or auth_user_id in this base hash").

**Impact:** The session_identity_hash stored in DB is identical for all users
on the same browser+IP combo. It does not uniquely bind to a user. The field
as currently implemented provides browser fingerprinting only (not user binding).

This is a design ambiguity. The session_identity_hash field and computeIdentityHash()
signature accept a user_id parameter, but the call site always passes null.
The description in CLAUDE.md says "Class C IP + auth_user_id + User Agent" which
is inconsistent with the null always being passed.

**Recommendation:** Either:
- Remove the user_id parameter from computeIdentityHash() to match actual usage.
- Or: pass the actual auth_user_id at call sites where it is known (e.g. after
  successful authentication), and document the intent clearly.

**This does NOT cause the redirect issue.** getCurrentUser() validates sessions
using only session_id + DB flag columns (is_active, is_expired, is_revoked,
is_deleted). The fingerprint hash is stored but not checked in the auth path.

---

## 5. Full Failure Chain Summary

```
Login request
  Session::create() -> session_id($id) + session_start() -> Set-Cookie header sent
  AuthService::handleLogin() returns redirect -> login.php issues header('Location:...')

Browser redirect (next request)
  Cookie: PHPSESSID=<id> is present in request
  admin.php loads -- session_start() never called
  AuthService::isLoggedIn() -> getCurrentUser() -> Session::getSessionId()
  getSessionId(): session not active, returns session_id() = '' [BUG]
  getCurrentUser(): sid='' -> returns false
  isLoggedIn() = false -> redirect back to login.php
```

---

## 6. Recommended Fixes (Prioritized)

### Fix 1 (Critical -- apply immediately)

**File:** app/auth/Session.php
**Method:** getSessionId(), line 846

Replace the current body with the cookie-aware version shown in section 4.1.

This single change should resolve the post-login redirect failure.

### Fix 2 (Recommended -- apply after Fix 1 is confirmed working)

Add a bootstrap-level `session_start()` call in the config/bootstrap phase so
that session state is always available before any auth check. This is a defense-
in-depth measure: it ensures getSessionId() never needs to be the last resort
session launcher.

In bootstrap or config (wherever session_set_cookie_params is called):

```php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
```

This is idempotent if session_start() was already called (PHP_SESSION_ACTIVE
prevents double-start). Place it AFTER session_set_cookie_params().

### Fix 3 (Clarification -- low priority)

Align the computeIdentityHash() call in createEmbedSession() with its actual
intent. If auth_user_id will never be included, remove the parameter and update
the docblock. If it should be included post-authentication, pass it explicitly.

---

## 7. Verification Steps

After applying Fix 1:

1. Log in as wolfie.
2. Confirm redirect to admin.php succeeds (no loop back to login.php).
3. Check error_log for SESSION_DEBUY: Session::create and SESSION_DEBUY: touch()
   entries -- touch() entry confirms validateSession or getCurrentUser found the
   row and called it.
4. Optionally: add temporary error_log in getSessionId() to confirm session_status
   is PHP_SESSION_ACTIVE on the admin.php request after the fix.

---

## 8. Files Relevant to This Issue

| File | Role |
|------|------|
| app/auth/Session.php | getSessionId() bug; computeIdentityHash(); create() |
| includes/classes/AuthService.php | getCurrentUser(); isLoggedIn(); handleLogin() |
| includes/classes/AuthSessionManager.php | createSession() -- calls Session::create() |
| login.php | POST handler; calls AuthService::handleLogin() |
| admin.php | Redirect target; calls isLoggedIn() (or equivalent) |
