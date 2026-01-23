---
wolfie.headers.version: 4.0.8
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
dialog:
  speaker: CURSOR
  target: @everyone
  message: "Created SQL verification document for version 4.0.8 authentication. All SQL queries verified against TOON files for correct table and column names."
  mood: "00FF00"
tags:
  categories: ["documentation", "development", "authentication", "verification"]
  collections: ["core-docs", "dev-docs"]
  channels: ["dev", "architecture"]
file:
  title: "Authentication SQL Verification for Version 4.0.8"
  description: "Verification of all SQL queries against TOON files - table and column names"
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: draft
  author: GLOBAL_CURRENT_AUTHORS
---

# Authentication SQL Verification for Version 4.0.8

**Purpose:** This document verifies all SQL queries in the authentication system against TOON files to ensure correct table and column names.

**Source:** TOON files in `database/toon_data/` (authoritative schema)

**Status:** Verified and corrected

---

## Verification Results

### ✅ lupo_auth_users Table

**TOON File:** `database/toon_data/lupo_auth_users.toon`

**Columns Used in Code:**
- `auth_user_id` ✓ (PK, BIGINT)
- `username` ✓ (VARCHAR(30), UNIQUE)
- `display_name` ✓ (VARCHAR(42))
- `email` ✓ (VARCHAR(100))
- `password_hash` ✓ (VARCHAR(255))
- `is_active` ✓ (TINYINT, DEFAULT 1)
- `is_deleted` ✓ (TINYINT, DEFAULT 0)
- `last_login_ymdhis` ✓ (BIGINT)
- `updated_ymdhis` ✓ (BIGINT)

**Queries Verified:**
1. ✅ Login lookup query - `auth-controller.php` line 155-166
2. ✅ Password update query - `auth-controller.php` line 213-222
3. ✅ Last login update query - `auth-controller.php` line 266-275

---

### ✅ lupo_actors Table

**TOON File:** `database/toon_data/lupo_actors.toon`

**Columns Used in Code:**
- `actor_id` ✓ (PK, BIGINT)
- `actor_type` ✓ (ENUM: 'user', 'ai_agent', 'service')
- `slug` ✓ (VARCHAR(255), UNIQUE)
- `name` ✓ (VARCHAR(255))
- `actor_source_id` ✓ (BIGINT) - References auth_user_id when actor_source_type='user'
- `actor_source_type` ✓ (VARCHAR(20)) - 'user', 'agent', 'system'
- `is_deleted` ✓ (TINYINT, DEFAULT 0)
- `created_ymdhis` ✓ (BIGINT)
- `updated_ymdhis` ✓ (BIGINT)

**Queries Verified:**
1. ✅ Actor lookup by auth_user_id - `auth-helpers.php` line 53-58
2. ✅ Actor creation - `auth-helpers.php` line 119-131
3. ✅ Actor slug check - `auth-helpers.php` line 178-186
4. ✅ Current user query (JOIN with auth_users) - `auth-helpers.php` line 220-233
5. ✅ Get auth_user_id from actor_id - `auth-helpers.php` line 382-391

**Note:** JOIN in `current_user()` uses `a.actor_source_id = au.auth_user_id` which is correct.

---

### ✅ lupo_sessions Table

**TOON File:** `database/toon_data/lupo_sessions.toon`

**Columns Used in Code:**
- `session_id` ✓ (PK, VARCHAR(100))
- `federation_node_id` ✓ (BIGINT, DEFAULT 1)
- `actor_id` ✓ (BIGINT, DEFAULT 0)
- `ip_address` ✓ (VARCHAR(45))
- `user_agent` ✓ (VARCHAR(255))
- `device_type` ✓ (ENUM: 'desktop', 'mobile', 'tablet', 'bot', 'other')
- `auth_method` ✓ (VARCHAR(30))
- `auth_provider` ✓ (VARCHAR(50))
- `security_level` ✓ (ENUM: 'low', 'medium', 'high', DEFAULT 'medium')
- `is_active` ✓ (TINYINT(1), DEFAULT 1)
- `is_expired` ✓ (TINYINT(1), DEFAULT 0)
- `is_revoked` ✓ (TINYINT(1), DEFAULT 0)
- `login_ymdhis` ✓ (BIGINT)
- `last_seen_ymdhis` ✓ (BIGINT)
- `expires_ymdhis` ✓ (BIGINT)
- `created_ymdhis` ✓ (BIGINT)
- `updated_ymdhis` ✓ (BIGINT)
- `is_deleted` ✓ (TINYINT, DEFAULT 0)

**Queries Verified:**
1. ✅ Session creation INSERT - `session-helpers.php` line 203-241
2. ✅ Session validation SELECT - `session-helpers.php` line 303-312
3. ✅ Session activity update - `session-helpers.php` line 386-397
4. ✅ Session expiration mark - `session-helpers.php` line 429-440
5. ✅ Session destruction UPDATE - `session-helpers.php` line 483-494

---

### ✅ lupo_actor_roles Table

**TOON File:** `database/toon_data/lupo_actor_roles.toon`

**Columns Used in Code:**
- `actor_role_id` ✓ (PK, BIGINT)
- `actor_id` ✓ (BIGINT)
- `role_key` ✓ (VARCHAR(100))
- `is_deleted` ✓ (SMALLINT, DEFAULT 0)

**Queries Verified:**
1. ✅ Admin role check - `auth-helpers.php` line 294-298

---

### ✅ lupo_permissions Table

**TOON File:** `database/toon_data/lupo_permissions.toon`

**Columns Used in Code:**
- `permission_id` ✓ (PK, BIGINT)
- `target_type` ✓ (VARCHAR(64))
- `target_id` ✓ (BIGINT)
- `user_id` ✓ (BIGINT) - References auth_user_id from lupo_auth_users
- `permission` ✓ (ENUM: 'read', 'write', 'owner', DEFAULT 'read')
- `is_deleted` ✓ (TINYINT(1), DEFAULT 0)

**Queries Verified:**
1. ✅ Admin permission check - `auth-helpers.php` line 326-332

**Note:** `user_id` in permissions table references `auth_user_id` from `lupo_auth_users`.

---

### ✅ lupo_modules Table

**TOON File:** `database/toon_data/lupo_modules.toon`

**Columns Used in Code:**
- `module_id` ✓ (PK, BIGINT)
- `module_key` ✓ (VARCHAR(100), UNIQUE) - **CORRECTED: Was using 'name', now using 'module_key'**
- `is_active` ✓ (TINYINT(1), DEFAULT 0)
- `is_deleted` ✓ (TINYINT, DEFAULT 0)

**Queries Verified:**
1. ✅ Admin module lookup - `auth-helpers.php` line 310-315 (CORRECTED)

**Correction Made:**
- Changed `WHERE name = 'admin'` to `WHERE module_key = 'admin'`
- TOON file shows column is `module_key`, not `name`

---

### ⚠️ lupo_actor_group_membership Table

**TOON File:** `database/toon_data/lupo_actor_group_membership.toon`

**Schema Issue Identified:**
- `actor_group_membership_id` is PRIMARY KEY (auto_increment)
- Comment says "Reference to actors.actor_id" but it's actually the PK
- **No `actor_id` column exists in the table**
- Cannot link actors to groups without an `actor_id` foreign key column

**Status:** Group membership admin check is disabled in code (commented out) until schema is clarified or migration is created.

**Required Migration:** If group membership is needed for admin checks, a migration must add an `actor_id` column to `lupo_actor_group_membership`.

---

## Summary of Corrections

### ✅ Fixed Issues

1. **lupo_modules query** - Changed `name = 'admin'` to `module_key = 'admin'`
   - File: `lupo-includes/functions/auth-helpers.php` line 312
   - Status: Fixed

### ⚠️ Known Schema Issues

1. **lupo_actor_group_membership** - Missing `actor_id` foreign key column
   - Impact: Cannot check admin status via group membership
   - Status: Workaround implemented (admin check uses roles and permissions only)
   - Action Required: Migration SQL needed if group-based admin check is required

---

## All SQL Queries Verified

### Session Queries
- ✅ `lupo_create_session()` - INSERT into lupo_sessions
- ✅ `lupo_validate_session()` - SELECT from lupo_sessions
- ✅ `lupo_update_session_activity()` - UPDATE lupo_sessions
- ✅ `lupo_mark_session_expired()` - UPDATE lupo_sessions
- ✅ `lupo_destroy_session()` - UPDATE lupo_sessions

### Auth User Queries
- ✅ Login lookup - SELECT from lupo_auth_users
- ✅ Password update - UPDATE lupo_auth_users
- ✅ Last login update - UPDATE lupo_auth_users

### Actor Queries
- ✅ Get actor from auth_user_id - SELECT from lupo_actors
- ✅ Create actor - INSERT into lupo_actors
- ✅ Check actor slug exists - SELECT from lupo_actors
- ✅ Current user (JOIN actors + auth_users) - SELECT with JOIN
- ✅ Get auth_user_id from actor_id - SELECT from lupo_actors

### Permission Queries
- ✅ Admin role check - SELECT from lupo_actor_roles
- ✅ Admin module lookup - SELECT from lupo_modules (CORRECTED)
- ✅ Admin permission check - SELECT from lupo_permissions

---

## Migration SQL Required

### Migration 1034: Add actor_id to lupo_actor_group_membership (Optional)

**Purpose:** Enable group-based admin checks by adding actor_id foreign key.

**Status:** Not required for 4.0.8 (admin check works via roles and permissions)

**If Needed:**
```sql
ALTER TABLE lupo_actor_group_membership
ADD COLUMN actor_id BIGINT(20) UNSIGNED NULL COMMENT 'Reference to actors.actor_id' AFTER actor_group_membership_id;

-- Add index for performance
CREATE INDEX idx_actor_id ON lupo_actor_group_membership(actor_id);

-- Update existing records if needed (requires manual data migration)
-- UPDATE lupo_actor_group_membership SET actor_id = actor_group_membership_id WHERE actor_id IS NULL;
```

**Note:** This migration is optional and only needed if group-based admin checks are required. Current implementation works without it.

---

## Verification Complete

All SQL queries have been verified against TOON files. One correction was made (modules table column name). One schema issue was identified (actor_group_membership missing actor_id column) but workaround is in place.

**Status:** All queries verified and corrected ✅
