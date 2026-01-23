---
wolfie.headers.version: 4.0.8
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
dialog:
  speaker: CURSOR
  target: @everyone
  message: "Created comprehensive testing checklist for version 4.0.8 authentication system. Covers all login, session, and admin access scenarios."
  mood: "00FF00"
tags:
  categories: ["documentation", "testing", "authentication"]
  collections: ["core-docs", "dev-docs"]
  channels: ["dev", "testing"]
file:
  title: "Authentication Testing Checklist for Version 4.0.8"
  description: "Comprehensive testing checklist for authentication system - login, sessions, admin access"
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: draft
  author: GLOBAL_CURRENT_AUTHORS
---

# Authentication Testing Checklist for Version 4.0.8

**Purpose:** Comprehensive testing checklist for authentication system implementation.

**Version:** 4.0.8

**Date:** 2025-01-08

---

## Test Environment Setup

- [ ] LUPOPEDIA_DEBUG enabled in config
- [ ] Database connection verified
- [ ] Test user account created (username: `testuser`, password: `testpass123`)
- [ ] Test admin account created (username: `admin`, password: `adminpass123`)
- [ ] Admin role assigned to admin account in `lupo_actor_roles`
- [ ] Browser developer tools open (Network tab, Console tab)
- [ ] Error logs accessible

---

## 1. Login Success Tests

### 1.1 Basic Login
- [ ] Navigate to `/login`
- [ ] Enter valid username
- [ ] Enter valid password
- [ ] Click "Login" button
- [ ] **Expected:** Redirect to `/admin` dashboard
- [ ] **Expected:** Session created in `lupo_sessions` table
- [ ] **Expected:** `last_login_ymdhis` updated in `lupo_auth_users`
- [ ] **Expected:** `current_user()` returns user data
- [ ] **Expected:** Header shows username and logout link

### 1.2 Login with Redirect URL
- [ ] Navigate to `/admin` (while logged out)
- [ ] **Expected:** Redirect to `/login?redirect=/admin`
- [ ] Enter valid credentials
- [ ] Click "Login"
- [ ] **Expected:** Redirect to `/admin` (preserved redirect URL)
- [ ] **Expected:** Session created

### 1.3 Login When Already Logged In
- [ ] Log in successfully
- [ ] Navigate to `/login` again
- [ ] **Expected:** Redirect to `/admin` (no form shown)

---

## 2. Login Failure Tests

### 2.1 Invalid Username
- [ ] Navigate to `/login`
- [ ] Enter invalid username (e.g., `nonexistent`)
- [ ] Enter any password
- [ ] Click "Login"
- [ ] **Expected:** Error message: "Invalid username or password"
- [ ] **Expected:** Form re-displayed with error
- [ ] **Expected:** No session created
- [ ] **Expected:** Debug log shows failed attempt

### 2.2 Invalid Password
- [ ] Navigate to `/login`
- [ ] Enter valid username
- [ ] Enter invalid password
- [ ] Click "Login"
- [ ] **Expected:** Error message: "Invalid username or password"
- [ ] **Expected:** Form re-displayed with error
- [ ] **Expected:** No session created
- [ ] **Expected:** Debug log shows failed attempt

### 2.3 Empty Fields
- [ ] Navigate to `/login`
- [ ] Leave username empty
- [ ] Leave password empty
- [ ] Click "Login"
- [ ] **Expected:** HTML5 validation prevents submission (required attribute)
- [ ] **Expected:** No form submission

### 2.4 Inactive Account
- [ ] Set test user `is_active = 0` in database
- [ ] Navigate to `/login`
- [ ] Enter username and password
- [ ] Click "Login"
- [ ] **Expected:** Error message: "Your account is inactive. Please contact an administrator."
- [ ] **Expected:** No session created
- [ ] **Expected:** Debug log shows inactive account attempt

### 2.5 Deleted Account
- [ ] Set test user `is_deleted = 1` in database
- [ ] Navigate to `/login`
- [ ] Enter username and password
- [ ] Click "Login"
- [ ] **Expected:** Error message: "Invalid username or password"
- [ ] **Expected:** No session created
- [ ] **Expected:** Query filters out deleted users (`is_deleted = 0`)

---

## 3. MD5 → Bcrypt Password Upgrade Tests

### 3.1 MD5 Password Login
- [ ] Set test user password_hash to MD5 hash (e.g., `5f4dcc3b5aa765d61d8327deb882cf99` for "password")
- [ ] Navigate to `/login`
- [ ] Enter username and password matching MD5 hash
- [ ] Click "Login"
- [ ] **Expected:** Login successful
- [ ] **Expected:** Password hash upgraded to bcrypt in database
- [ ] **Expected:** Debug log shows password upgrade
- [ ] **Expected:** Next login uses bcrypt (no upgrade needed)

### 3.2 Bcrypt Password Login (No Upgrade)
- [ ] Ensure test user has bcrypt password hash
- [ ] Navigate to `/login`
- [ ] Enter username and password
- [ ] Click "Login"
- [ ] **Expected:** Login successful
- [ ] **Expected:** Password hash unchanged (already bcrypt)
- [ ] **Expected:** No upgrade log message

---

## 4. Session Creation Tests

### 4.1 Session Record Creation
- [ ] Log in successfully
- [ ] Check `lupo_sessions` table
- [ ] **Expected:** New session record created
- [ ] **Expected:** `session_id` is 64-character hex string
- [ ] **Expected:** `actor_id` matches logged-in user's actor_id
- [ ] **Expected:** `ip_address` populated
- [ ] **Expected:** `user_agent` populated
- [ ] **Expected:** `device_type` detected correctly
- [ ] **Expected:** `auth_method` = 'password'
- [ ] **Expected:** `auth_provider` = 'local'
- [ ] **Expected:** `security_level` = 'medium'
- [ ] **Expected:** `is_active` = 1
- [ ] **Expected:** `is_expired` = 0
- [ ] **Expected:** `is_revoked` = 0
- [ ] **Expected:** `login_ymdhis` set to current timestamp
- [ ] **Expected:** `expires_ymdhis` set to 24 hours from now
- [ ] **Expected:** `created_ymdhis` set to current timestamp

### 4.2 PHP Session Variables
- [ ] Log in successfully
- [ ] Check `$_SESSION` array
- [ ] **Expected:** `$_SESSION['session_id']` set
- [ ] **Expected:** `$_SESSION['actor_id']` set
- [ ] **Expected:** `$_SESSION['login_ymdhis']` set

---

## 5. Session Validation Tests

### 5.1 Valid Session Access
- [ ] Log in successfully
- [ ] Navigate to `/admin`
- [ ] **Expected:** Access granted
- [ ] **Expected:** `last_seen_ymdhis` updated in database
- [ ] **Expected:** `expires_ymdhis` extended (if within renewal window)
- [ ] **Expected:** Debug log shows session validated

### 5.2 Session Validation on Each Request
- [ ] Log in successfully
- [ ] Navigate to multiple pages
- [ ] Check database after each request
- [ ] **Expected:** `last_seen_ymdhis` updated on each request
- [ ] **Expected:** Session remains active

### 5.3 Invalid Session ID
- [ ] Log in successfully
- [ ] Manually change `$_SESSION['session_id']` to invalid value
- [ ] Navigate to `/admin`
- [ ] **Expected:** Redirect to `/login` (session invalid)
- [ ] **Expected:** PHP session destroyed
- [ ] **Expected:** Debug log shows session validation failure

---

## 6. Session Expiration Tests

### 6.1 Time-Based Expiration
- [ ] Log in successfully
- [ ] Manually set `expires_ymdhis` to past timestamp in database
- [ ] Navigate to `/admin`
- [ ] **Expected:** Session marked as expired
- [ ] **Expected:** Redirect to `/login`
- [ ] **Expected:** `is_expired` = 1 in database
- [ ] **Expected:** `is_active` = 0 in database
- [ ] **Expected:** Debug log shows session expired

### 6.2 Expired Session Access
- [ ] Create expired session in database (`is_expired = 1`)
- [ ] Set `$_SESSION['session_id']` to expired session ID
- [ ] Navigate to `/admin`
- [ ] **Expected:** Redirect to `/login`
- [ ] **Expected:** Session not validated

### 6.3 Revoked Session Access
- [ ] Log in successfully
- [ ] Manually set `is_revoked = 1` in database
- [ ] Navigate to `/admin`
- [ ] **Expected:** Redirect to `/login`
- [ ] **Expected:** Session not validated

---

## 7. Session Destruction Tests

### 7.1 Logout Functionality
- [ ] Log in successfully
- [ ] Navigate to `/logout`
- [ ] **Expected:** Redirect to homepage (`/`)
- [ ] **Expected:** Session record updated (`is_active = 0`, `is_revoked = 1`)
- [ ] **Expected:** PHP session destroyed
- [ ] **Expected:** `$_SESSION` array cleared
- [ ] **Expected:** Session cookie deleted
- [ ] **Expected:** Debug log shows session destroyed

### 7.2 Post-Logout Access
- [ ] Log in successfully
- [ ] Log out
- [ ] Navigate to `/admin`
- [ ] **Expected:** Redirect to `/login`
- [ ] **Expected:** `current_user()` returns false

### 7.3 Multiple Logout Attempts
- [ ] Log in successfully
- [ ] Navigate to `/logout`
- [ ] Navigate to `/logout` again (while logged out)
- [ ] **Expected:** No errors
- [ ] **Expected:** Redirect to homepage

---

## 8. Admin Access Control Tests

### 8.1 Admin Dashboard Access (Admin User)
- [ ] Log in as admin user
- [ ] Navigate to `/admin`
- [ ] **Expected:** Admin dashboard displayed
- [ ] **Expected:** Welcome message shows username
- [ ] **Expected:** Actor ID displayed
- [ ] **Expected:** Roles displayed
- [ ] **Expected:** Logout link present

### 8.2 Admin Dashboard Access (Non-Admin User)
- [ ] Log in as non-admin user
- [ ] Navigate to `/admin`
- [ ] **Expected:** 403 Forbidden error
- [ ] **Expected:** Error message: "You do not have permission to access this page."
- [ ] **Expected:** No dashboard content displayed

### 8.3 Admin Access Without Login
- [ ] Log out (or clear session)
- [ ] Navigate to `/admin`
- [ ] **Expected:** Redirect to `/login`
- [ ] **Expected:** Redirect URL preserved (`/admin`)

### 8.4 Admin Status Check Methods
- [ ] Log in as admin user
- [ ] Verify admin status via `lupo_actor_roles` (role_key = 'admin')
- [ ] **Expected:** `lupo_is_admin($actor_id)` returns true
- [ ] **Expected:** `current_user()['is_admin']` returns true

### 8.5 Non-Admin Status Check
- [ ] Log in as non-admin user
- [ ] **Expected:** `lupo_is_admin($actor_id)` returns false
- [ ] **Expected:** `current_user()['is_admin']` returns false

---

## 9. Non-Admin Access Denial Tests

### 9.1 require_login() Function
- [ ] Create test page with `require_login()`
- [ ] Access page while logged out
- [ ] **Expected:** Redirect to `/login`
- [ ] **Expected:** Redirect URL preserved

### 9.2 require_admin() Function
- [ ] Create test page with `require_admin()`
- [ ] Access page as non-admin user
- [ ] **Expected:** 403 Forbidden error
- [ ] **Expected:** Error message displayed

### 9.3 require_admin() Without Login
- [ ] Create test page with `require_admin()`
- [ ] Access page while logged out
- [ ] **Expected:** Redirect to `/login` (require_login() called first)

---

## 10. Redirect URL Preservation Tests

### 10.1 Redirect After Login
- [ ] Navigate to `/admin` (while logged out)
- [ ] **Expected:** Redirect to `/login?redirect=/admin`
- [ ] Log in successfully
- [ ] **Expected:** Redirect to `/admin` (preserved URL)

### 10.2 Redirect URL Sanitization
- [ ] Navigate to `/login?redirect=http://evil.com`
- [ ] Log in successfully
- [ ] **Expected:** Redirect to `/admin` (default, not external URL)
- [ ] **Expected:** External redirects prevented

### 10.3 Redirect URL from Session
- [ ] Set `$_SESSION['redirect_after_login']` manually
- [ ] Navigate to `/login`
- [ ] Log in successfully
- [ ] **Expected:** Redirect to session-stored URL
- [ ] **Expected:** Session variable cleared after redirect

---

## 11. Header Login Status Indicator Tests

### 11.1 Logged In Status
- [ ] Log in successfully
- [ ] Check header/navigation bar
- [ ] **Expected:** Username displayed
- [ ] **Expected:** Logout link displayed
- [ ] **Expected:** Login link NOT displayed

### 11.2 Logged Out Status
- [ ] Log out (or clear session)
- [ ] Check header/navigation bar
- [ ] **Expected:** Login link displayed
- [ ] **Expected:** Username NOT displayed
- [ ] **Expected:** Logout link NOT displayed

### 11.3 Login Link Redirect
- [ ] Click login link in header (while logged out)
- [ ] **Expected:** Navigate to `/login`
- [ ] **Expected:** Current URL preserved in redirect parameter

---

## 12. Error Handling Tests

### 12.1 Database Connection Failure
- [ ] Temporarily break database connection
- [ ] Attempt login
- [ ] **Expected:** Generic error message displayed
- [ ] **Expected:** Detailed error logged (debug mode)
- [ ] **Expected:** No sensitive information exposed

### 12.2 Session Creation Failure
- [ ] Log in successfully
- [ ] Manually delete session record from database
- [ ] Navigate to `/admin`
- [ ] **Expected:** Redirect to `/login` (session invalid)
- [ ] **Expected:** No fatal errors

### 12.3 Actor Creation Failure
- [ ] Log in with user that has no actor record
- [ ] **Expected:** Actor created automatically
- [ ] **Expected:** Login succeeds
- [ ] **Expected:** Actor linked to auth_user

---

## 13. Security Tests

### 13.1 SQL Injection Prevention
- [ ] Attempt login with SQL injection in username: `admin' OR '1'='1`
- [ ] **Expected:** Login fails (prepared statements prevent injection)
- [ ] **Expected:** No SQL errors

### 13.2 XSS Prevention
- [ ] Attempt login with XSS in username: `<script>alert('xss')</script>`
- [ ] **Expected:** Username sanitized/escaped in output
- [ ] **Expected:** No script execution

### 13.3 Session Hijacking Prevention
- [ ] Log in successfully
- [ ] Note session ID
- [ ] Change IP address (simulate)
- [ ] Navigate to `/admin`
- [ ] **Expected:** Session validated (IP change detection optional)
- [ ] **Expected:** Session remains valid (IP check not implemented in 4.0.8)

### 13.4 Password Hash Security
- [ ] Check password hash in database
- [ ] **Expected:** Bcrypt hash (not plain text)
- [ ] **Expected:** Hash starts with `$2y$` or `$2a$`
- [ ] **Expected:** Hash length is 60 characters

---

## 14. Debug Logging Tests

### 14.1 Debug Log Enabled
- [ ] Enable `LUPOPEDIA_DEBUG` in config
- [ ] Perform login
- [ ] Check error logs
- [ ] **Expected:** Session start logged
- [ ] **Expected:** Session creation logged
- [ ] **Expected:** Session validation logged
- [ ] **Expected:** Password upgrade logged (if applicable)

### 14.2 Debug Log Disabled
- [ ] Disable `LUPOPEDIA_DEBUG` in config
- [ ] Perform login
- [ ] Check error logs
- [ ] **Expected:** No debug messages logged
- [ ] **Expected:** Only critical errors logged

---

## 15. Integration Tests

### 15.1 Bootstrap Integration
- [ ] Check bootstrap.php loads session helpers
- [ ] **Expected:** Session helpers loaded before module loader
- [ ] **Expected:** Session started early in request lifecycle

### 15.2 Module Loader Integration
- [ ] Check module-loader.php loads AUTH module
- [ ] **Expected:** AUTH module loaded first (highest priority)
- [ ] **Expected:** Auth routes available before other routes

### 15.3 Header Integration
- [ ] Check header.php includes auth status indicator
- [ ] **Expected:** `lupo_render_login_status()` called
- [ ] **Expected:** Login/logout links displayed correctly

---

## Test Results Summary

**Total Tests:** 100+

**Passed:** ___

**Failed:** ___

**Blocked:** ___

**Notes:**
- 

---

## Sign-Off

**Tester:** ________________

**Date:** ________________

**Status:** ☐ Pass  ☐ Fail  ☐ Needs Review
