---
architect: Captain Wolfie
wolfie.headers.version: 4.0.8
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
dialog:
  speaker: CURSOR
  target: @everyone
  message: "Created authentication schema summary for version 4.0.8 development. This document describes the existing authentication tables and their relationships based on TOON file analysis."
  mood: "00FF00"
tags:
  categories: ["documentation", "development", "authentication"]
  collections: ["core-docs", "dev-docs"]
  channels: ["dev", "architecture"]
file:
  title: "Authentication Schema Summary for Version 4.0.8"
  description: "Complete schema analysis of authentication tables for implementing username/password login system"
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: draft
  author: GLOBAL_CURRENT_AUTHORS
---

# Authentication Schema Summary for Version 4.0.8

**Purpose:** This document provides a complete schema analysis of the existing authentication tables in Lupopedia, based on TOON file inspection. This summary is required before implementing the username/password login system in version 4.0.8.

**Source:** TOON files in `database/toon_data/` (authoritative schema representation)
- **IMPORTANT:** CSV files in `database/csv_data/` are NOT authoritative and may be outdated
- **ONLY** TOON files are used as the source of truth for database schema
- If schema changes are needed, migration SQL files will be provided in `database/migrations/`

**Status:** Ready for review before implementation

---

## 1. Primary Authentication Table: `lupo_auth_users`

**Purpose:** Stores user authentication credentials and basic user information.

**Primary Key:** `auth_user_id` (BIGINT, AUTO_INCREMENT)

**Key Fields:**
- **Username Storage:** `username` (VARCHAR(30), UNIQUE, NOT NULL)
  - Unique constraint: `unique_username`
  - Example: "captain"
  
- **Password Storage:** `password_hash` (VARCHAR(255), NULLABLE)
  - Comment: "NULL for OAuth users"
  - Example: "1e9e9f6fef3369cdc763284d80ae5feb" (appears to be MD5 hash)
  - **Note:** For version 4.0.8, we will need to verify the hashing algorithm and potentially upgrade to bcrypt/argon2

- **Email:** `email` (VARCHAR(100), NULLABLE)
  - Indexed: `idx_email`
  - Example: "lupopedia@gmail.com"

- **Display Name:** `display_name` (VARCHAR(42), NOT NULL)
  - Example: "captain"

- **OAuth Fields:** (for future Google Sign-In in 4.0.9)
  - `auth_provider` (VARCHAR(50), NULLABLE) - e.g., "google", "github"
  - `provider_id` (VARCHAR(255), NULLABLE) - Provider-specific user ID
  - Unique constraint: `unique_provider_user` on (`auth_provider`, `provider_id`)

- **Status Fields:**
  - `is_active` (TINYINT, DEFAULT 1) - 1 = active, 0 = inactive
  - `is_deleted` (TINYINT, DEFAULT 0) - 1 = deleted, 0 = not deleted
  - `deleted_ymdhis` (BIGINT, NULLABLE) - UTC YYYYMMDDHHMMSS when deleted

- **Timestamps:**
  - `created_ymdhis` (BIGINT, NOT NULL) - UTC YYYYMMDDHHMMSS
  - `updated_ymdhis` (BIGINT, NOT NULL) - UTC YYYYMMDDHHMMSS
  - `last_login_ymdhis` (BIGINT, NULLABLE) - UTC YYYYMMDDHHMMSS

- **Profile:**
  - `profile_image_url` (VARCHAR(2000), NULLABLE)

**Indexes:**
- `unique_username` (UNIQUE) on `username`
- `unique_provider_user` (UNIQUE) on (`auth_provider`, `provider_id`)
- `idx_email` on `email`
- `idx_is_active` on `is_active`
- `idx_is_deleted` on `is_deleted`
- `idx_created_ymdhis` on `created_ymdhis`
- `idx_updated_ymdhis` on `updated_ymdhis`

**Sample Data:**
- `auth_user_id: 1`, `username: "captain"`, `password_hash: "1e9e9f6fef3369cdc763284d80ae5feb"`, `email: "lupopedia@gmail.com"`

---

## 2. Central Identity Registry: `lupo_actors`

**Purpose:** Central identity table for all system entities (humans, AI agents, services). Links authentication users to the broader actor system.

**Primary Key:** `actor_id` (BIGINT, AUTO_INCREMENT)

**Key Fields:**
- **Actor Type:** `actor_type` (ENUM: 'user', 'ai_agent', 'service', NOT NULL)
  - Indexed: `idx_actor_type`
  
- **Identity:**
  - `slug` (VARCHAR(255), UNIQUE, NOT NULL) - Stable unique identifier
  - `name` (VARCHAR(255), NOT NULL) - Human-readable name
  - Example: slug="captain-wolfie", name="Captain Wolfie"

- **Link to Auth Users:** 
  - `actor_source_id` (BIGINT, NULLABLE) - ID from source table (auth_users, agents, etc.)
  - `actor_source_type` (VARCHAR(20), NULLABLE) - Source type: "user", "agent", "system"
  - **Relationship:** When `actor_source_type = 'user'`, then `actor_source_id` references `lupo_auth_users.auth_user_id`

- **Status Fields:**
  - `is_active` (TINYINT, DEFAULT 1)
  - `is_deleted` (TINYINT, DEFAULT 0)
  - `deleted_ymdhis` (BIGINT, NULLABLE)

- **Metadata:**
  - `metadata` (TEXT, NULLABLE) - Optional JSON for additional actor attributes

- **Timestamps:**
  - `created_ymdhis` (BIGINT, NOT NULL) - UTC YYYYMMDDHHMMSS
  - `updated_ymdhis` (BIGINT, NOT NULL) - UTC YYYYMMDDHHMMSS

**Indexes:**
- `unique_slug` (UNIQUE) on `slug`
- `idx_actor_type` on `actor_type`
- `idx_is_active` on `is_active`
- `idx_created_ymdhis` on `created_ymdhis`

**Sample Data:**
- `actor_id: 0`, `actor_type: "service"`, `slug: "system-kernel"`, `name: "System Kernel Actor"`
- `actor_id: 1`, `actor_type: "ai_agent"`, `slug: "captain-wolfie"`, `name: "Captain Wolfie"`, `actor_source_id: 1`, `actor_source_type: "agent"`

**Relationship to Auth Users:**
- To find the actor for an authenticated user:
  ```sql
  SELECT actor_id FROM lupo_actors 
  WHERE actor_source_type = 'user' 
    AND actor_source_id = {auth_user_id}
  ```

---

## 3. Roles and Permissions: `lupo_actor_roles`

**Purpose:** Defines roles assigned to actors (users/agents) within specific contexts.

**Primary Key:** `actor_role_id` (BIGINT, NOT NULL)

**Key Fields:**
- **Actor Link:** `actor_id` (BIGINT, NOT NULL) - References `lupo_actors.actor_id`
  - Indexed: `actor_id_2`

- **Role Definition:**
  - `role_key` (VARCHAR(100), NOT NULL) - Role identifier (e.g., "admin", "editor", "viewer")
  - `role_description` (TEXT, NULLABLE)
  - `weight` (FLOAT, DEFAULT 1) - Role priority/weight

- **Context Scoping:**
  - `context_id` (BIGINT, DEFAULT 0) - Context/domain scope for this role
  - `department_id` (BIGINT, NULLABLE) - Department scope (if applicable)
  - Indexed: `context_id`, `department_id`

- **Status Fields:**
  - `is_deleted` (SMALLINT, DEFAULT 0)
  - `deleted_ymdhis` (BIGINT, NULLABLE)

- **Timestamps:**
  - `created_ymdhis` (BIGINT, NOT NULL)
  - `updated_ymdhis` (BIGINT, NULLABLE)

**Unique Constraint:**
- `actor_id` (UNIQUE) on (`actor_id`, `context_id`, `role_key`) - One role per actor per context

**Usage for Admin Check:**
- To check if a user has admin role:
  ```sql
  SELECT COUNT(*) FROM lupo_actor_roles ar
  JOIN lupo_actors a ON ar.actor_id = a.actor_id
  WHERE a.actor_source_type = 'user'
    AND a.actor_source_id = {auth_user_id}
    AND ar.role_key = 'admin'
    AND ar.is_deleted = 0
  ```

---

## 4. Group Memberships: `lupo_actor_group_membership`

**Purpose:** Links actors to groups for role-based access control (RBAC).

**Primary Key:** `actor_group_membership_id` (BIGINT, AUTO_INCREMENT)

**Key Fields:**
- **Actor Link:** `actor_group_membership_id` (BIGINT, NOT NULL) - **Note:** This appears to be incorrectly named; should reference `actor_id` based on comment
  - Comment says: "Reference to actors.actor_id"
  - **Potential Schema Issue:** Field name doesn't match its purpose
  - Indexed: `idx_actor_domain` on (`actor_group_membership_id`, `domain_id`)

- **Group Link:** `group_id` (BIGINT, NOT NULL) - References `lupo_groups.group_id`
  - Indexed: `idx_group_domain` on (`group_id`, `domain_id`)

- **Domain Context:** `domain_id` (BIGINT, NOT NULL) - Domain/tenant context

- **Role in Group:** `role` (VARCHAR(50), DEFAULT 'member') - Role/position within the group
  - Examples: "member", "admin", "owner"

- **Status Fields:**
  - `is_active` (TINYINT, DEFAULT 1) - 1 = active, 0 = inactive

- **Expiration:**
  - `expires_ymdhis` (BIGINT, NULLABLE) - UTC YYYYMMDDHHMMSS when membership expires (NULL = never)
  - Indexed: `idx_expires`

- **Audit:**
  - `created_by` (BIGINT, NULLABLE) - actor_id who created this membership
  - `created_ymdhis` (BIGINT, NOT NULL) - UTC YYYYMMDDHHMMSS

**Indexes:**
- `idx_actor_domain` on (`actor_group_membership_id`, `domain_id`)
- `idx_group_domain` on (`group_id`, `domain_id`)
- `idx_expires` on `expires_ymdhis`
- `idx_is_active` on `is_active`

**Note:** There appears to be a schema inconsistency where `actor_group_membership_id` is used as both the primary key and as a reference to `actor_id`. This needs verification against the actual database.

---

## 5. Groups: `lupo_groups`

**Purpose:** Defines groups for role-based access control.

**Primary Key:** `group_id` (BIGINT, AUTO_INCREMENT)

**Key Fields:**
- **Group Identity:**
  - `name` (VARCHAR(50), NOT NULL) - Unique name of the group within the domain
  - Unique constraint: `unique_group_domain`
  - Example: "domain_0_authority"

- **Description:** `description` (TEXT, NULLABLE)

- **System Groups:**
  - `is_system` (TINYINT, DEFAULT 0) - 1 = system group (cannot be deleted)

- **Settings:** `settings` (JSON, NULLABLE) - JSON-encoded group-specific settings

- **Status Fields:**
  - `is_active` (TINYINT, DEFAULT 1)
  - `is_deleted` (TINYINT, DEFAULT 0)
  - `deleted_ymdhis` (BIGINT, NULLABLE)

- **Audit:**
  - `created_by` (BIGINT, NULLABLE) - actor_id of the creator
  - `created_ymdhis` (BIGINT, NOT NULL)
  - `updated_ymdhis` (BIGINT, NOT NULL)

**Sample Data:**
- `group_id: 1`, `name: "domain_0_authority"`, `description: "Authoritative governance group for domain 0 (lupopedia.com)."`, `is_system: 1`

---

## 6. Fine-Grained Permissions: `lupo_permissions`

**Purpose:** Defines fine-grained permissions on specific objects (collections, departments, modules, features, etc.).

**Primary Key:** `permission_id` (BIGINT, AUTO_INCREMENT)

**Key Fields:**
- **Target Object:**
  - `target_type` (VARCHAR(64), NOT NULL) - Type: "collection", "department", "module", "feature", etc.
  - `target_id` (BIGINT, NOT NULL) - ID of the target object
  - Indexed: `idx_target` on (`target_type`, `target_id`)

- **Permission Holder (Either User OR Group):**
  - `user_id` (BIGINT, NULLABLE) - User ID for user-based permissions (NULL for group-based)
  - `group_id` (BIGINT, NULLABLE) - Group ID for group-based permissions (NULL for user-based)
  - Indexed: `idx_user`, `idx_group`
  - **Note:** Exactly one of `user_id` or `group_id` must be set

- **Permission Level:** `permission` (ENUM: 'read', 'write', 'owner', DEFAULT 'read')
  - Examples: "read" (view only), "write" (edit), "owner" (full control)

- **Status Fields:**
  - `is_deleted` (TINYINT(1), DEFAULT 0)
  - `deleted_ymdhis` (BIGINT, NULLABLE)
  - Indexed: `idx_deleted` on (`is_deleted`, `deleted_ymdhis`)

- **Timestamps:**
  - `created_ymdhis` (BIGINT, NOT NULL)
  - `updated_ymdhis` (BIGINT, NULLABLE)

**Unique Constraints:**
- `uniq_target_user` (UNIQUE) on (`target_type`, `target_id`, `user_id`)
- `uniq_target_group` (UNIQUE) on (`target_type`, `target_id`, `group_id`)

**Usage for Admin Check:**
- To check if a user has "owner" permission on admin module:
  ```sql
  SELECT COUNT(*) FROM lupo_permissions p
  WHERE p.target_type = 'module'
    AND p.target_id = {admin_module_id}
    AND p.user_id = {auth_user_id}
    AND p.permission = 'owner'
    AND p.is_deleted = 0
  ```

---

## 7. Session Management: `lupo_sessions`

**Purpose:** Manages user sessions for authentication state.

**Primary Key:** `session_id` (VARCHAR(100), NOT NULL)

**Key Fields:**
- **Session Identity:**
  - `session_id` (VARCHAR(100), PRIMARY KEY) - Unique session identifier
  - **Note:** This is the PHP session ID, not an auto-increment integer

- **Actor Link:** `actor_id` (BIGINT, DEFAULT 0) - References `lupo_actors.actor_id`
  - Value 0 = anonymous users
  - Indexed: `idx_actor`

- **Domain/Tenant:** `federation_node_id` (BIGINT, DEFAULT 1) - Domain/tenant identifier
  - Indexed: `idx_domain`

- **Authentication Info:**
  - `auth_method` (VARCHAR(30), NULLABLE) - e.g., "password", "oauth", "api_key"
  - `auth_provider` (VARCHAR(50), NULLABLE) - e.g., "local", "google", "github"
  - `security_level` (ENUM: 'low', 'medium', 'high', DEFAULT 'medium')
  - Indexed: `idx_security`

- **Client Info:**
  - `ip_address` (VARCHAR(45), DEFAULT '') - Supports IPv6
  - `user_agent` (VARCHAR(255), DEFAULT '')
  - `device_id` (VARCHAR(100), NULLABLE)
  - `device_type` (ENUM: 'desktop', 'mobile', 'tablet', 'bot', 'other', NULLABLE)
  - Indexed: `idx_device`

- **Session Data:** `session_data` (LONGTEXT, NULLABLE) - Serialized session data (encrypted if sensitive)
- **Metadata:** `metadata` (JSON, NULLABLE) - Additional session metadata

- **Status Fields:**
  - `is_active` (TINYINT(1), DEFAULT 1) - Whether session is currently active
  - `is_expired` (TINYINT(1), DEFAULT 0) - Whether session has expired
  - `is_revoked` (TINYINT(1), DEFAULT 0) - Whether session was manually revoked
  - Indexed: `idx_status` on (`is_active`, `is_expired`, `is_revoked`)

- **Timestamps:**
  - `login_ymdhis` (BIGINT, NULLABLE) - UTC YYYYMMDDHHMMSS when session was authenticated
  - `last_seen_ymdhis` (BIGINT, NOT NULL) - UTC YYYYMMDDHHMMSS of last activity
  - `expires_ymdhis` (BIGINT, NULLABLE) - UTC YYYYMMDDHHMMSS when session expires
  - `created_ymdhis` (BIGINT, NOT NULL) - UTC YYYYMMDDHHMMSS when session was created
  - `updated_ymdhis` (BIGINT, NOT NULL) - UTC YYYYMMDDHHMMSS when session was last updated
  - Indexed: `idx_created`, `idx_last_seen`, `idx_expires`

- **Soft Delete:**
  - `is_deleted` (TINYINT, DEFAULT 0)
  - `deleted_ymdhis` (BIGINT, NULLABLE)
  - Indexed: `idx_cleanup` on (`is_deleted`, `last_seen_ymdhis`)

**Usage for Session Management:**
- Store PHP session ID in `session_id`
- Link to authenticated user via `actor_id` (lookup actor from auth_user_id)
- Update `last_seen_ymdhis` on each request
- Check `is_active`, `is_expired`, `is_revoked` for session validation

---

## 8. OAuth Providers: `lupo_auth_providers`

**Purpose:** Stores OAuth provider configuration (for future Google Sign-In in 4.0.9).

**Primary Key:** `auth_provider_id` (BIGINT, AUTO_INCREMENT)

**Key Fields:**
- `provider_name` (VARCHAR(50), UNIQUE, NOT NULL) - e.g., "google", "github"
- `client_id` (VARCHAR(255), NOT NULL) - OAuth client ID
- `client_secret` (TEXT, NOT NULL) - Encrypted at rest in lupopedia-config.php
- `scopes` (TEXT, NULLABLE) - Space-separated list of OAuth scopes
- `authorization_endpoint` (VARCHAR(2000), NOT NULL) - OAuth authorization URL
- `token_endpoint` (VARCHAR(2000), NOT NULL) - OAuth token URL
- `userinfo_endpoint` (VARCHAR(2000), NULLABLE) - Optional userinfo endpoint
- `jwks_uri` (VARCHAR(2000), NULLABLE) - Optional JWKS URI for key rotation
- `is_active` (TINYINT, DEFAULT 1)
- `created_ymdhis` (BIGINT, NOT NULL)
- `updated_ymdhis` (BIGINT, NOT NULL)

**Note:** This table is for future use (version 4.0.9). Not needed for version 4.0.8.

---

## Schema Relationships Summary

### Authentication Flow (Version 4.0.8):

1. **User Login:**
   - User provides `username` and `password`
   - Lookup in `lupo_auth_users` by `username`
   - Verify `password_hash` matches provided password
   - Check `is_active = 1` and `is_deleted = 0`

2. **Actor Resolution:**
   - Find actor: `SELECT actor_id FROM lupo_actors WHERE actor_source_type = 'user' AND actor_source_id = {auth_user_id}`
   - If no actor exists, create one (or handle error)

3. **Session Creation:**
   - Create session record in `lupo_sessions`
   - Set `session_id` = PHP session ID
   - Set `actor_id` = resolved actor_id
   - Set `auth_method` = 'password'
   - Set `auth_provider` = 'local'
   - Set `login_ymdhis` = current timestamp
   - Set `expires_ymdhis` = current timestamp + session lifetime

4. **Admin Check:**
   - Option 1: Check `lupo_actor_roles` for `role_key = 'admin'`
   - Option 2: Check `lupo_permissions` for `permission = 'owner'` on admin module
   - Option 3: Check `lupo_actor_group_membership` for membership in admin group

---

## Key Findings and Recommendations

### 1. Password Hashing
- **Current:** Appears to use MD5 (based on sample hash format)
- **Recommendation:** Upgrade to bcrypt or argon2id for version 4.0.8
- **Action:** Verify current hashing algorithm and create migration if needed

### 2. Actor Linkage
- **Finding:** `lupo_actors` links to `lupo_auth_users` via `actor_source_id` when `actor_source_type = 'user'`
- **Recommendation:** Ensure every authenticated user has a corresponding actor record
- **Action:** Create helper function `get_actor_id_from_auth_user_id($auth_user_id)`

### 3. Admin Role Detection
- **Finding:** Multiple ways to check admin status:
  - `lupo_actor_roles.role_key = 'admin'`
  - `lupo_permissions.permission = 'owner'` on admin module
  - `lupo_actor_group_membership` in admin group
- **Recommendation:** Define a single authoritative method for admin check
- **Action:** Create `is_admin($actor_id)` helper function

### 4. Session Management
- **Finding:** `lupo_sessions` uses VARCHAR(100) for `session_id` (PHP session ID)
- **Recommendation:** Use PHP's native session handling, store session ID in database for tracking
- **Action:** Create `create_session($actor_id)` and `validate_session($session_id)` helpers

### 5. Schema Inconsistency
- **Finding:** `lupo_actor_group_membership.actor_group_membership_id` field name suggests it should reference `actor_id` but is used as primary key
- **Recommendation:** Verify actual database schema matches TOON file
- **Action:** Check database directly or create migration if field needs renaming

---

## Next Steps for Implementation

1. ✅ **Schema Analysis Complete** (this document)
2. ⏳ **Review Required** - Wait for approval before proceeding
3. ⏳ **Password Hashing Verification** - Check current algorithm, upgrade if needed
4. ⏳ **Helper Functions** - Create authentication helper functions
5. ⏳ **Login Form** - Build login form UI
6. ⏳ **Login Controller** - Build login processing logic
7. ⏳ **Session Management** - Implement session creation/validation
8. ⏳ **Admin Check** - Implement `require_admin()` function
9. ⏳ **Admin Routes** - Create `/admin` route protection
10. ⏳ **Logout Handler** - Implement logout functionality

---

## Questions for Review

1. **Password Hashing:** What algorithm is currently used? Should we upgrade to bcrypt/argon2?
2. **Actor Creation:** Should we auto-create actor records for users, or require manual creation?
3. **Admin Detection:** Which method should be authoritative for admin checks?
4. **Session Lifetime:** What should be the default session expiration time?
5. **Schema Verification:** Should we verify `lupo_actor_group_membership` schema matches TOON file?

---

**Document Status:** Ready for review  
**Next Action:** Wait for approval before proceeding with implementation
