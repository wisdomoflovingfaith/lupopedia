---
wolfie.headers.version: 4.0.8
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
dialog:
  speaker: CURSOR
  target: @everyone
  message: "Created comprehensive implementation plan for version 4.0.8 authentication system. This plan covers login/logout routes, session handling, helper functions, password hashing upgrade, and actor linkage."
  mood: "00FF00"
tags:
  categories: ["documentation", "development", "authentication", "implementation"]
  collections: ["core-docs", "dev-docs"]
  channels: ["dev", "architecture"]
file:
  title: "Authentication Implementation Plan for Version 4.0.8"
  description: "Step-by-step implementation plan for username/password login system"
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: draft
  author: GLOBAL_CURRENT_AUTHORS
---

# Authentication Implementation Plan for Version 4.0.8

**Purpose:** This document provides a detailed step-by-step implementation plan for building the username/password authentication system in Lupopedia version 4.0.8.

**Status:** Ready for review before implementation

**Based On:** Schema analysis from `docs/dev/AUTH_SCHEMA_SUMMARY_4.0.8.md`

---

## Implementation Overview

This plan covers:
1. Login route (`/login`)
2. Logout route (`/logout`)
3. Login controller functions
4. Login renderer (HTML form)
5. Session handling (using `lupo_sessions`)
6. `current_user()` helper function
7. `require_login()` and `require_admin()` functions
8. Password hashing upgrade plan (MD5 → bcrypt)
9. Actor linkage (auth_user → actor → roles)

---

## File Structure

```
lupo-includes/
├── functions/
│   ├── auth-helpers.php          # Authentication helper functions
│   └── session-helpers.php       # Session management functions
├── modules/
│   └── auth/
│       ├── auth-controller.php    # Login/logout route handlers
│       └── auth-renderer.php      # Login form HTML renderer
└── security/
    └── password-hash.php          # Password hashing utilities
```

---

## Step 1: Password Hashing Upgrade Plan

### 1.1 Current State
- **Current Algorithm:** MD5 (based on sample hash: `1e9e9f6fef3369cdc763284d80ae5feb`)
- **Problem:** MD5 is cryptographically broken and insecure
- **Solution:** Upgrade to bcrypt (PHP's `password_hash()` with `PASSWORD_BCRYPT`)

### 1.2 Migration Strategy

**Phase 1: Dual Support (During Transition)**
- Support both MD5 (legacy) and bcrypt (new) passwords
- When user logs in with MD5 password, automatically upgrade to bcrypt
- Store bcrypt hashes in `password_hash` column

**Phase 2: Migration SQL (Optional)**
- Create migration SQL file: `database/migrations/1033_upgrade_password_hashes.sql`
- This migration will:
  - Add a `password_hash_version` column to track hash algorithm (if needed)
  - Or rely on hash format detection (bcrypt starts with `$2y$`)

### 1.3 Implementation Files

**File:** `lupo-includes/security/password-hash.php`

**Functions:**
- `lupo_hash_password($password)` - Hash password with bcrypt
- `lupo_verify_password($password, $hash)` - Verify password (supports MD5 and bcrypt)
- `lupo_is_bcrypt_hash($hash)` - Check if hash is bcrypt format
- `lupo_is_md5_hash($hash)` - Check if hash is MD5 format

**Logic:**
```php
function lupo_verify_password($password, $hash) {
    // Try bcrypt first (new format)
    if (lupo_is_bcrypt_hash($hash)) {
        return password_verify($password, $hash);
    }
    
    // Fallback to MD5 (legacy)
    if (lupo_is_md5_hash($hash)) {
        $md5_hash = md5($password);
        if ($md5_hash === $hash) {
            // Password matches MD5, but we should upgrade it
            // Return true, but caller should upgrade hash
            return true;
        }
        return false;
    }
    
    // Unknown hash format
    return false;
}
```

---

## Step 2: Session Management Functions

### 2.1 Session Helper Functions

**File:** `lupo-includes/functions/session-helpers.php`

**Functions:**

#### `lupo_start_session()`
- Start PHP session if not already started
- Initialize session in `lupo_sessions` table
- Link session to actor_id if user is logged in
- Update `last_seen_ymdhis` on each request

#### `lupo_create_session($actor_id, $auth_method = 'password', $auth_provider = 'local')`
- Create new session record in `lupo_sessions` table
- Set `session_id` = PHP session ID
- Set `actor_id` = provided actor_id
- Set `auth_method` and `auth_provider`
- Set `login_ymdhis` = current timestamp
- Set `expires_ymdhis` = current timestamp + session lifetime (default: 24 hours)
- Set `is_active` = 1
- Set `is_expired` = 0
- Set `is_revoked` = 0
- Store IP address, user agent, device info

#### `lupo_validate_session($session_id)`
- Check if session exists in `lupo_sessions` table
- Verify `is_active = 1`, `is_expired = 0`, `is_revoked = 0`
- Check if `expires_ymdhis` is in the future
- Update `last_seen_ymdhis` if valid
- Return actor_id if valid, false if invalid

#### `lupo_destroy_session($session_id)`
- Set `is_active` = 0
- Set `is_revoked` = 1
- Set `updated_ymdhis` = current timestamp
- Destroy PHP session

#### `lupo_update_session_activity($session_id)`
- Update `last_seen_ymdhis` to current timestamp
- Called on each authenticated request

**Session Lifetime:**
- Default: 24 hours (86400 seconds)
- Configurable via constant: `LUPO_SESSION_LIFETIME` (default: 86400)

**Session Cleanup:**
- Expired sessions are marked `is_expired = 1` automatically
- Soft-deleted sessions (`is_deleted = 1`) are cleaned up by cron job (future)

---

## Step 3: Authentication Helper Functions

### 3.1 Auth Helper Functions

**File:** `lupo-includes/functions/auth-helpers.php`

**Functions:**

#### `lupo_get_actor_id_from_auth_user_id($auth_user_id)`
- Find actor_id for a given auth_user_id
- Query: `SELECT actor_id FROM lupo_actors WHERE actor_source_type = 'user' AND actor_source_id = :auth_user_id AND is_deleted = 0`
- Return actor_id or false if not found

#### `lupo_create_actor_for_auth_user($auth_user_id, $username, $display_name)`
- Create actor record for authenticated user if it doesn't exist
- Set `actor_type` = 'user'
- Set `actor_source_type` = 'user'
- Set `actor_source_id` = auth_user_id
- Generate slug from username
- Set `name` = display_name
- Return actor_id

#### `current_user()`
- Get current authenticated user information
- Check PHP session for actor_id
- Validate session using `lupo_validate_session()`
- Query actor and auth_user tables
- Return array with:
  - `actor_id`
  - `auth_user_id`
  - `username`
  - `display_name`
  - `email`
  - `is_admin` (boolean)
- Return `false` if not logged in

#### `require_login()`
- Check if user is logged in using `current_user()`
- If not logged in, redirect to `/login?redirect=` + current URL
- Store redirect URL in session for post-login redirect
- Exit script if not logged in

#### `require_admin()`
- Call `require_login()` first
- Check if user has admin role using `lupo_is_admin()`
- If not admin, show 403 error page
- Exit script if not admin

#### `lupo_is_admin($actor_id)`
- Check if actor has admin role
- Check `lupo_actor_roles` for `role_key = 'admin'`
- Check `lupo_permissions` for `permission = 'owner'` on admin module
- Check `lupo_actor_group_membership` for membership in admin group
- Return true if any check passes, false otherwise

**Admin Detection Priority:**
1. `lupo_actor_roles.role_key = 'admin'` (highest priority)
2. `lupo_permissions.permission = 'owner'` on admin module
3. `lupo_actor_group_membership` in admin group (lowest priority)

---

## Step 4: Login Route (`/login`)

### 4.1 Route Registration

**File:** `lupo-includes/modules/module-loader.php`

**Modification:**
- Add auth module loading before TRUTH module (highest priority)
- Add route check for `/login` and `/logout` before other routes

**Route Priority Order:**
1. `/login` (auth module)
2. `/logout` (auth module)
3. `/admin` (auth module - requires login)
4. TRUTH routes
5. CRAFTY_SYNTAX routes
6. CONTENT routes

### 4.2 Login Controller

**File:** `lupo-includes/modules/auth/auth-controller.php`

**Functions:**

#### `auth_handle_login($slug)`
- Route handler for `/login` slug
- If POST request with `username` and `password`, process login
- If GET request, show login form
- If already logged in, redirect to home or redirect URL

**Login Processing Logic:**
1. Validate input (username and password required)
2. Sanitize username (trim, lowercase)
3. Query `lupo_auth_users` for username
4. Check `is_active = 1` and `is_deleted = 0`
5. Verify password using `lupo_verify_password()`
6. If MD5 hash matches, upgrade to bcrypt and update database
7. Get or create actor_id using `lupo_get_actor_id_from_auth_user_id()` or `lupo_create_actor_for_auth_user()`
8. Create session using `lupo_create_session()`
9. Update `last_login_ymdhis` in `lupo_auth_users`
10. Redirect to redirect URL (from `?redirect=`) or home page

**Error Handling:**
- Invalid username: "Invalid username or password" (don't reveal which)
- Invalid password: "Invalid username or password"
- Inactive user: "Your account is inactive. Please contact an administrator."
- Deleted user: "Invalid username or password"

#### `auth_handle_logout($slug)`
- Route handler for `/logout` slug
- Destroy session using `lupo_destroy_session()`
- Redirect to home page or login page

#### `auth_handle_admin($slug)`
- Route handler for `/admin` slug
- Call `require_admin()` to check authentication and admin status
- Render admin dashboard (minimal for 4.0.8)
- Show basic admin interface

---

## Step 5: Login Form Renderer

### 5.1 Login Form HTML

**File:** `lupo-includes/modules/auth/auth-renderer.php`

**Function:**

#### `auth_render_login_form($error_message = null, $redirect_url = null)`
- Render HTML login form
- Include error message display if provided
- Include hidden `redirect` field with redirect URL
- Form action: `/login` (POST)
- Form fields:
  - `username` (text input, required, autofocus)
  - `password` (password input, required)
  - `redirect` (hidden input, optional)
  - Submit button: "Sign In"
- Include CSRF token (future enhancement)
- Basic styling (minimal, functional)

**Form Structure:**
```html
<form method="POST" action="/login">
    <?php if ($error_message): ?>
        <div class="error"><?php echo htmlspecialchars($error_message); ?></div>
    <?php endif; ?>
    
    <label for="username">Username</label>
    <input type="text" id="username" name="username" required autofocus>
    
    <label for="password">Password</label>
    <input type="password" id="password" name="password" required>
    
    <input type="hidden" name="redirect" value="<?php echo htmlspecialchars($redirect_url); ?>">
    
    <button type="submit">Sign In</button>
</form>
```

---

## Step 6: Bootstrap Integration

### 6.1 Session Initialization

**File:** `lupo-includes/bootstrap.php`

**Modification:**
- Add session start call after database connection
- Call `lupo_start_session()` to initialize session tracking
- Load session helpers before module loader

**Code Addition:**
```php
// After database connection setup
require_once(LUPO_INCLUDES_DIR . '/functions/session-helpers.php');
lupo_start_session();
```

### 6.2 Function Loading Order

**File:** `lupo-includes/lupopedia-loader.php`

**Modification:**
- Load auth helpers before module loader
- Load session helpers before auth helpers

**Loading Order:**
1. Core functions (`functions-core.php`)
2. Session helpers (`functions/session-helpers.php`)
3. Auth helpers (`functions/auth-helpers.php`)
4. Module loader (includes auth module)

---

## Step 7: Admin Route Protection

### 7.1 Admin Route Handler

**File:** `lupo-includes/modules/auth/auth-controller.php`

**Function:**

#### `auth_handle_admin($slug)`
- Extract admin sub-routes (e.g., `/admin/dashboard`, `/admin/users`)
- Call `require_admin()` to enforce authentication and admin check
- Render minimal admin dashboard for 4.0.8
- Show basic admin navigation

**Admin Dashboard (Minimal for 4.0.8):**
- Welcome message with username
- Logout link
- Basic admin menu (placeholder for future features)
- Current user info display

---

## Step 8: Implementation Sequence

### Phase 1: Foundation (Core Functions)
1. ✅ Create `lupo-includes/security/password-hash.php`
   - Implement password hashing functions
   - Support MD5 (legacy) and bcrypt (new)

2. ✅ Create `lupo-includes/functions/session-helpers.php`
   - Implement session management functions
   - Database session tracking

3. ✅ Create `lupo-includes/functions/auth-helpers.php`
   - Implement authentication helper functions
   - Actor linkage functions
   - Admin check functions

### Phase 2: Routes and Controllers
4. ✅ Create `lupo-includes/modules/auth/auth-controller.php`
   - Implement login handler
   - Implement logout handler
   - Implement admin handler

5. ✅ Create `lupo-includes/modules/auth/auth-renderer.php`
   - Implement login form renderer

6. ✅ Modify `lupo-includes/modules/module-loader.php`
   - Add auth module loading
   - Add `/login`, `/logout`, `/admin` route handling

### Phase 3: Integration
7. ✅ Modify `lupo-includes/bootstrap.php`
   - Add session initialization

8. ✅ Modify `lupo-includes/lupopedia-loader.php`
   - Add auth helpers loading

### Phase 4: Testing
9. ✅ Test login flow
10. ✅ Test logout flow
11. ✅ Test session persistence
12. ✅ Test admin access control
13. ✅ Test password hash upgrade (MD5 → bcrypt)

---

## Step 9: Database Queries Reference

### 9.1 Login Query

```sql
SELECT 
    auth_user_id,
    username,
    display_name,
    email,
    password_hash,
    is_active,
    is_deleted
FROM lupo_auth_users
WHERE username = :username
  AND is_deleted = 0
LIMIT 1
```

### 9.2 Actor Lookup Query

```sql
SELECT actor_id
FROM lupo_actors
WHERE actor_source_type = 'user'
  AND actor_source_id = :auth_user_id
  AND is_deleted = 0
LIMIT 1
```

### 9.3 Admin Check Query (Role-based)

```sql
SELECT COUNT(*) as admin_count
FROM lupo_actor_roles ar
JOIN lupo_actors a ON ar.actor_id = a.actor_id
WHERE a.actor_source_type = 'user'
  AND a.actor_source_id = :auth_user_id
  AND ar.role_key = 'admin'
  AND ar.is_deleted = 0
```

### 9.4 Session Creation Query

```sql
INSERT INTO lupo_sessions (
    session_id,
    federation_node_id,
    actor_id,
    ip_address,
    user_agent,
    auth_method,
    auth_provider,
    security_level,
    is_active,
    is_expired,
    is_revoked,
    login_ymdhis,
    last_seen_ymdhis,
    expires_ymdhis,
    created_ymdhis,
    updated_ymdhis,
    is_deleted
) VALUES (
    :session_id,
    1,
    :actor_id,
    :ip_address,
    :user_agent,
    'password',
    'local',
    'medium',
    1,
    0,
    0,
    :login_ymdhis,
    :last_seen_ymdhis,
    :expires_ymdhis,
    :created_ymdhis,
    :updated_ymdhis,
    0
)
```

### 9.5 Session Validation Query

```sql
SELECT 
    actor_id,
    is_active,
    is_expired,
    is_revoked,
    expires_ymdhis
FROM lupo_sessions
WHERE session_id = :session_id
  AND is_deleted = 0
LIMIT 1
```

### 9.6 Session Update Query

```sql
UPDATE lupo_sessions
SET last_seen_ymdhis = :last_seen_ymdhis,
    updated_ymdhis = :updated_ymdhis
WHERE session_id = :session_id
```

---

## Step 10: Security Considerations

### 10.1 Password Security
- ✅ Use bcrypt for new passwords (cost factor: 10)
- ✅ Support MD5 migration during login
- ✅ Never store plaintext passwords
- ✅ Use `password_hash()` and `password_verify()` PHP functions

### 10.2 Session Security
- ✅ Use secure session cookies (HTTPS when available)
- ✅ Set `httponly` flag on session cookies
- ✅ Set `samesite` = 'Lax' to prevent CSRF
- ✅ Store session data in database, not just PHP session
- ✅ Validate session on each request
- ✅ Expire sessions after inactivity

### 10.3 Input Validation
- ✅ Sanitize username input (trim, lowercase)
- ✅ Validate password length (minimum 8 characters, future)
- ✅ Use prepared statements for all database queries
- ✅ Escape output for XSS prevention

### 10.4 Error Messages
- ✅ Generic error messages ("Invalid username or password")
- ✅ Don't reveal which field is incorrect
- ✅ Log detailed errors server-side for debugging

---

## Step 11: Configuration Constants

### 11.1 Session Configuration

**File:** `lupopedia-config.php` (or new auth config)

**Constants:**
```php
// Session lifetime in seconds (default: 24 hours)
define('LUPO_SESSION_LIFETIME', 86400);

// Password minimum length (future)
define('LUPO_PASSWORD_MIN_LENGTH', 8);

// Bcrypt cost factor
define('LUPO_BCRYPT_COST', 10);
```

---

## Step 12: Error Handling

### 12.1 Login Errors

**Error Types:**
1. **Invalid Credentials** - Generic message, log details
2. **Inactive Account** - Specific message, log user_id
3. **Database Error** - Log error, show generic message
4. **Session Creation Failure** - Log error, show generic message

### 12.2 Error Logging

**Log Format:**
```
[YYYY-MM-DD HH:MM:SS] AUTH ERROR: {error_type} - {details} - IP: {ip_address} - User: {username}
```

**Log Location:**
- Use PHP error_log() function
- Log to file: `lupo-includes/logs/auth-errors.log` (create if needed)

---

## Step 13: Testing Checklist

### 13.1 Login Flow
- [ ] Valid username/password → success, redirect
- [ ] Invalid username → error message
- [ ] Invalid password → error message
- [ ] Inactive account → error message
- [ ] MD5 password → upgrade to bcrypt
- [ ] Redirect URL preserved after login

### 13.2 Session Management
- [ ] Session created in database
- [ ] Session persists across requests
- [ ] Session expires after lifetime
- [ ] Session destroyed on logout
- [ ] Multiple sessions per user supported

### 13.3 Admin Access
- [ ] Admin user can access `/admin`
- [ ] Non-admin user → 403 error
- [ ] Not logged in → redirect to login

### 13.4 Helper Functions
- [ ] `current_user()` returns user data when logged in
- [ ] `current_user()` returns false when not logged in
- [ ] `require_login()` redirects when not logged in
- [ ] `require_admin()` checks admin status correctly

---

## Step 14: Migration SQL (If Needed)

### 14.1 Password Hash Upgrade Migration

**File:** `database/migrations/1033_upgrade_password_hashes.sql`

**Purpose:** Optional migration to upgrade existing MD5 passwords to bcrypt

**Note:** This migration is optional because passwords will be upgraded automatically on login. However, if you want to upgrade all passwords at once, this migration can be created.

**Migration Logic:**
- This migration would require knowing plaintext passwords, which we don't have
- Therefore, automatic upgrade on login is the preferred approach
- No migration SQL file needed for password upgrade

---

## Step 15: Actor Linkage Flow

### 15.1 User → Actor → Roles Flow

**Step-by-Step:**

1. **User Logs In:**
   - Username/password validated against `lupo_auth_users`
   - Get `auth_user_id`

2. **Find or Create Actor:**
   - Query `lupo_actors` for `actor_source_type = 'user'` AND `actor_source_id = auth_user_id`
   - If not found, create actor record:
     - `actor_type` = 'user'
     - `actor_source_type` = 'user'
     - `actor_source_id` = auth_user_id
     - `slug` = username (sanitized)
     - `name` = display_name
   - Get `actor_id`

3. **Check Roles:**
   - Query `lupo_actor_roles` for `actor_id` and `role_key = 'admin'`
   - Query `lupo_permissions` for `user_id` and `permission = 'owner'`
   - Query `lupo_actor_group_membership` for admin groups

4. **Create Session:**
   - Store `actor_id` in session
   - Create session record in `lupo_sessions` with `actor_id`

5. **Subsequent Requests:**
   - Get `actor_id` from session
   - Validate session in `lupo_sessions` table
   - Use `actor_id` for permission checks

---

## Step 16: File Dependencies

### 16.1 Load Order

```
1. lupopedia-config.php (defines constants)
2. bootstrap.php (database connection)
3. functions/session-helpers.php (session functions)
4. functions/auth-helpers.php (auth functions)
5. modules/module-loader.php (route handler)
6. modules/auth/auth-controller.php (login/logout handlers)
7. modules/auth/auth-renderer.php (login form)
```

### 16.2 Global Dependencies

**Required Globals:**
- `$GLOBALS['mydatabase']` - PDO database connection (from bootstrap.php)

**Required Constants:**
- `LUPOPEDIA_CONFIG_LOADED` - Config loaded flag
- `LUPOPEDIA_PATH` - Path to Lupopedia directory
- `LUPO_INCLUDES_DIR` - Includes directory path
- `LUPO_PREFIX` - Table prefix (default: 'lupo_')

---

## Step 17: WOLFIE Headers

### 17.1 Header Requirements

All new files must include WOLFIE headers:

```php
/**
 * wolfie.header.identity: {file-name}
 * wolfie.header.placement: {file-path}
 * wolfie.header.version: {version}
 * wolfie.header.dialog:
 *   speaker: CURSOR
 *   target: @everyone
 *   message: "{description}"
 *   mood: "00FF00"
 */
```

**Files Requiring Headers:**
- `lupo-includes/security/password-hash.php`
- `lupo-includes/functions/session-helpers.php`
- `lupo-includes/functions/auth-helpers.php`
- `lupo-includes/modules/auth/auth-controller.php`
- `lupo-includes/modules/auth/auth-renderer.php`

---

## Step 18: Implementation Notes

### 18.1 Procedural PHP Only
- ✅ No classes for core authentication functions
- ✅ All functions are procedural
- ✅ Use `lupo_` prefix for all functions

### 18.2 Database Table Prefix
- ✅ Use `LUPO_PREFIX` constant (default: 'lupo_')
- ✅ Never hardcode table names
- ✅ Example: `LUPO_PREFIX . 'auth_users'` = `'lupo_auth_users'`

### 18.3 UTC Timestamps
- ✅ All timestamps use UTC YYYYMMDDHHMMSS format
- ✅ Use `lupo_utc_timestamp()` helper if available
- ✅ Or use: `date('YmdHis')` for current UTC timestamp

### 18.4 Error Handling
- ✅ Use try-catch for database operations
- ✅ Log errors to error_log()
- ✅ Show generic error messages to users
- ✅ Never expose database errors to users

---

## Step 19: Future Enhancements (Not in 4.0.8)

### 19.1 Features for Future Versions
- Google Sign-In (version 4.0.9)
- Password reset functionality
- Email verification
- Two-factor authentication
- Remember me functionality
- Session management UI (view/revoke sessions)
- Password strength requirements
- Account lockout after failed attempts
- CSRF token protection

---

## Step 20: Review Checklist

Before proceeding with implementation, review:

- [ ] Schema summary approved
- [ ] Implementation plan approved
- [ ] Password hashing strategy approved
- [ ] Session lifetime approved (24 hours default)
- [ ] Admin detection method approved
- [ ] File structure approved
- [ ] Route priority order approved
- [ ] Error handling approach approved

---

## Summary

This implementation plan provides a complete roadmap for building the authentication system in version 4.0.8. The plan covers:

1. ✅ Password hashing upgrade (MD5 → bcrypt)
2. ✅ Session management (database-backed)
3. ✅ Login/logout routes
4. ✅ Helper functions (current_user, require_login, require_admin)
5. ✅ Actor linkage (auth_user → actor → roles)
6. ✅ Admin route protection
7. ✅ Security considerations
8. ✅ Error handling
9. ✅ Testing checklist

**Next Step:** Wait for review and approval before proceeding with implementation.

---

**Document Status:** Ready for review  
**Next Action:** Wait for approval before proceeding with implementation
