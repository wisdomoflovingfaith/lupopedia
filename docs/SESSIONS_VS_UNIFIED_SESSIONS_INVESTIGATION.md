# Investigation: {prefix}sessions vs {prefix}unified_sessions

**Date:** 2026-02-10  
**Scope:** Full codebase + schema. No database or schema changes; analysis only.

**Final status (post-cleanup):** The table `{prefix}unified_sessions` is **obsolete and removed**. It has been dropped from `database/migrations/install_new_lupopedia.sql`. The single session table is `{prefix}sessions`; all unified-session logic lives in the Session class and `{prefix}sessions`. The one-time migration `one_time_unified_sessions_to_sessions.sql` has been run; no runtime code references the unified_sessions table.

---

## 1. Search results: all references

### 1.1 Table name usage (prefix must not be hardcoded)

**{prefix}sessions (correct pattern: `LUPO_TABLE_PREFIX . 'sessions'` or `$table_prefix . 'sessions'`):**

| Location | How table name is built |
|----------|-------------------------|
| `app/auth/Session.php` | `LUPO_TABLE_PREFIX . 'sessions'` |
| `app/auth/UnifiedSessionHandler.php` | `LUPO_TABLE_PREFIX . 'sessions'` (method `table()`) |
| `app/auth/AuthGuard.php` | `LUPO_TABLE_PREFIX . 'sessions'` |
| `app/Http/Controllers/Admin/AuthenticationController.php` | `$p . 'sessions'` where `$p = tablePrefix()` (returns LUPO_TABLE_PREFIX) |
| `lupo-includes/modules/channels/*.php` | `$table_prefix . 'sessions'` with `$table_prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_'` |
| `lupo-includes/modules/crafty_syntax/visitor-image.php` | `$prefix . 'sessions'`, `$prefix = LUPO_TABLE_PREFIX or 'lupo_'` |
| `lupo-includes/modules/crafty_syntax/visitor-session-helper.php` | `$prefix . 'sessions'`, same pattern |
| `lupo-includes/modules/crafty_syntax/visitor-chat-stream.php` | `$prefix . 'sessions'` |
| `lupo-includes/modules/crafty_syntax/livehelp.php` | `$prefix . 'sessions'` |
| `lupo-includes/modules/auth/auth-renderer.php` | `$table_prefix . 'sessions'` |

**Hardcoded `lupo_sessions` (violates prefix doctrine) — FIXED:**

| File | Usage (now uses `LUPO_TABLE_PREFIX . 'sessions'`) |
|------|--------|
| `app/Services/CraftySyntax/LegacySessionManager.php` | 4 SQL strings |
| `app/Services/CraftySyntax/LegacySessionIdentity.php` | INSERT/UPDATE |
| `app/Services/CraftySyntax/LegacyAdminChatFlush.php` | SELECT |
| `app/Services/CraftySyntax/LegacyUserChatFlush.php` | SELECT |
| `app/Services/CraftySyntax/LegacyAdminCommon.php` | SELECT |
| `app/Services/CraftySyntax/LegacyAuthentication.php` | UPDATE |

**{prefix}unified_sessions (the TABLE):**

- **No PHP code reads or writes the `unified_sessions` table.**  
- All references to “unified” in app code are:
  - **UnifiedSessionHandler** → uses `table() = LUPO_TABLE_PREFIX . 'sessions'` (i.e. **sessions**, not unified_sessions).
  - **Cookie name** → `LUPO_TABLE_PREFIX . 'unified_session'` (singular) — a cookie label, not a table.
  - **AuthManager** → return array key `'source' => 'unified_session'` (label only).
- The **table** `lupo_unified_sessions` is **removed from install**. It appears only in:
  - `database/migrations/one_time_unified_sessions_to_sessions.sql` (one-time: migrate into lupo_sessions, then DROP)
  - `database/migrations/dev_20260204_fix_schema_alignment.sql` (ALTER)
  - `database/migrations/2026_01_26_schema_from_toon.sql`
  - `database/install/lupopedia_mysql.sql`
  - `docs/REQUIRED_TABLES_4.1.0.md` (listed)
  - `DIRECTORY_TREE.md` (mentions toon)

### 1.2 Helpers / classes that touch session tables

| Component | Table used | Role |
|-----------|------------|------|
| **App\Auth\Session** | {prefix}sessions | OOP session CRUD, validation, naming |
| **App\Auth\UnifiedSessionHandler** | {prefix}sessions | createUnifiedSession, getUnifiedSession, cookie, cleanup — all use `table()` = sessions |
| **App\Auth\AuthGuard** | {prefix}sessions | updateUserActivity (last_seen) |
| **App\Auth\AuthManager** | Via UnifiedSessionHandler only (sessions) | checkUnifiedAuth, getUnifiedUser (cookie + sessions) |
| **SessionManager** (lupo-includes) | Via Session class (sessions) | Idle timeout, last_seen |
| **visitor-session-helper.php** | {prefix}sessions | Validate visitor session, get/set metadata_json |
| **visitor-image.php, livehelp.php, visitor-chat-stream.php** | {prefix}sessions | INSERT/UPDATE visitor row |
| **channels-controller, operator-*-api** | {prefix}sessions | SELECT visitors, metadata |
| **AuthenticationController** | {prefix}sessions | Stats, active sessions, session list |
| **LegacySessionManager, LegacySessionIdentity, Legacy* (above)** | {prefix}sessions (prefix from LUPO_TABLE_PREFIX) | Legacy Crafty Syntax session read/write |

None of these use the **unified_sessions** table in code.

---

## 2. Why two tables exist

### 2.1 Original purpose of {prefix}unified_sessions

- **Schema (install_new_lupopedia.sql):**  
  `unified_session_id` (PK), `session_id`, `user_id`, `system_context`, `session_data` (json), `expires_at`, `created_at`, `updated_at` (bigint — Unix timestamps).
- **Intent:** A separate “unified” session store, keyed by `session_id`, with `user_id` (not actor_id), `system_context` (lupopedia / crafty_syntax / unified), and Unix-time columns.
- **Designed for:** Cross-system session tracking (Lupopedia + Crafty Syntax) under one table, with a different schema (user_id, Unix timestamps) than the main sessions table.

### 2.2 Original purpose of {prefix}sessions

- **Schema (install_new_lupopedia.sql):**  
  `session_id` (PK), `federation_node_id`, `actor_id`, `ip_address`, `user_agent`, device/auth columns, `session_data`, `last_seen_ymdhis`, `expires_ymdhis`, `created_ymdhis`, `updated_ymdhis`, flags (`is_active`, `is_expired`, `is_revoked`, `is_deleted`), plus migrations for `system_context`, `name_key`, `is_named`.
- **Intent:** Single doctrine-aligned session table: YmdHis timestamps, actor_id, full session/device/auth fields, used for both web app and Crafty Syntax (visitors and operators).

### 2.3 Relationship to legacy operator tables

- **unified_sessions** was not tied to legacy operator tables in the schema (no FK or column to operator id).  
- It was tied to “unified” auth: one session table for multiple contexts (lupopedia, crafty_syntax), with `user_id` (and later mapping to actors).  
- Legacy operator tables have been removed; operator identity is now `lupo_auth_users` + roles. Session for operators and visitors is **{prefix}sessions** (see LIVEHELP_REMOVAL_REPORT.md).

### 2.4 Is {prefix}unified_sessions obsolete?

- **In code: yes.** No PHP reads or writes the `unified_sessions` table.  
- **UnifiedSessionHandler** is named “unified” but uses only **{prefix}sessions** (and a cookie named `{prefix}unified_session`).  
- **Doctrine (one_time_unified_sessions_to_sessions.sql):**  
  “Doctrine: single sessions table (prefix + 'sessions'); no separate unified_sessions.”  
  That migration copies data from `lupo_unified_sessions` into `lupo_sessions` and then **DROP TABLE lupo_unified_sessions**.  
- So after that migration, **unified_sessions** is intended to be gone. If the migration has not been run in an environment, the table may still exist but is unused by application code.

---

## 3. TOON files

- **docs/toons:** empty.  
- **schema/:** only `lupo_actors.toon`, `lupo_agents.toon`, `lupo_labs_*.toon`.  
- **No TOONs found for `lupo_sessions` or `lupo_unified_sessions`.**  
- The only session-related TOON found is in legacy backup (old backup TOON, not lupo_*).

**Conclusion:** TOONs are not present for these two tables. Schema below is taken from **install_new_lupopedia.sql** and migrations (add_system_context, add_name_key_is_named, one_time_unified_sessions_to_sessions).

### 3.1 lupo_sessions (from install + migrations)

- **Columns (logical order):**  
  session_id, federation_node_id, actor_id, ip_address, user_agent, device_id, device_type, auth_method, auth_provider, security_level, **name_key**, **is_named**, is_active, is_expired, is_revoked, session_data, **system_context**, metadata (json), login_ymdhis, last_seen_ymdhis, expires_ymdhis, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis.  
- **Purpose:** Single session store for all contexts; YmdHis UTC; actor_id; visitor naming (name_key, is_named); optional system_context.

### 3.2 lupo_unified_sessions (from install)

- **Columns:**  
  unified_session_id (PK), session_id (unique), user_id, system_context, session_data (json), expires_at, created_at, updated_at (bigint, Unix seconds).  
- **Purpose:** Legacy “unified” session table; different schema (user_id, Unix timestamps). Superseded by consolidating into lupo_sessions and dropping this table.

---

## 4. Can the system be unified?

### 4.1 Can all session logic live in the new Session class?

- **Largely yes.** The Session class already owns create/validate/update/destroy and naming for **{prefix}sessions**.  
- **UnifiedSessionHandler** today:
  - Writes/reads **{prefix}sessions** (not unified_sessions).
  - Manages the “unified” **cookie** and **system_context** and a subset of columns (e.g. federation_node_id, actor_id, session_data, last_seen_ymdhis, expires_ymdhis, name_key, is_named, system_context).  
- So “unification” here means: **one table ({prefix}sessions) and one place for session logic.**  
  - Session class can remain the main API.  
  - UnifiedSessionHandler can either be refactored to delegate to Session or be reduced to cookie + system_context and thin wrappers that use Session.

### 4.2 Can {prefix}unified_sessions be deprecated or merged?

- **Yes.**  
  - It is **already** merged in doctrine: `one_time_unified_sessions_to_sessions.sql` migrates rows into lupo_sessions and drops lupo_unified_sessions.  
  - No application code uses the unified_sessions table.  
- Deprecation = ensure the one-time migration has run everywhere and then treat the table as removed. No code changes required to “stop using” it, because nothing uses it.

### 4.3 Remaining code paths that depend on {prefix}unified_sessions

- **None.** No PHP reads or writes the unified_sessions table.  
- The only “unified_session” references are the cookie name and the AuthManager `'source' => 'unified_session'` label.

---

## 5. Unification plan (no code or schema changes yet)

### 5.1 Goal

- Single session table: **{prefix}sessions**.  
- All session logic through **Session** class (and optionally a thin UnifiedSessionHandler that uses Session).  
- No dependency on **{prefix}unified_sessions**; table dropped where it still exists (after one-time migration).

### 5.1a Unification plan A–E (checklist)

- **A. Fix prefix usage** — **DONE.** In the six Legacy files under `app/Services/CraftySyntax/`, every literal `lupo_sessions` in SQL has been replaced with a table name built as `(defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_') . 'sessions'`. No hardcoded `lupo_` for the session table remains in application code.
- **B. Remove all references to livehelp_*** — **DONE.** Application code and comments no longer reference livehelp_* tables. Remaining mentions are: (1) one-time migration SQL files that literally DROP or read from those table names for migration; (2) LIVEHELP_REMOVAL_REPORT.md, which documents what was removed. No code path assumes livehelp_* tables exist.
- **C. Document UnifiedSessionHandler** — **To document.** Either: (1) **Delegate:** UnifiedSessionHandler delegates all DB work to the Session class (Session does create/read/update/destroy; handler only manages cookie name, system_context, and calls Session). Or (2) **Thin wrapper (current):** UnifiedSessionHandler remains a thin wrapper that already uses the same table as Session (`{prefix}sessions`) via `table()`; it performs its own INSERT/SELECT/UPDATE/DELETE on that table. Both are acceptable; the decision (delegate vs thin wrapper) must be written in code comments or in this doc. Recommendation: document that it is currently a thin wrapper using `{prefix}sessions`; optional future refactor is to delegate to Session for a single code path.
- **D. Deprecate {prefix}unified_sessions** — **Plan (no DB change yet):** (1) Run the existing one-time migration `one_time_unified_sessions_to_sessions.sql` in every environment if not already run. (2) Drop `{prefix}unified_sessions` (e.g. `DROP TABLE IF EXISTS` with configured prefix). (3) Update REQUIRED_TABLES and any install/schema docs to mark unified_sessions as removed; new installs need not create it (or document “create only for historical migration; then run one_time migration and drop”).
- **E. Confirm Session class is the single source of truth** — **Confirmed.** All session creation, updates, naming (name_key, is_named), expiration, and metadata for the main app flow go through `App\Auth\Session` or through code that writes to `{prefix}sessions` (visitor-image, livehelp, Legacy* now with prefix). UnifiedSessionHandler writes to the same table. Session class is the canonical API for bootstrap and auth; direct INSERT/UPDATE in Legacy* and visitor scripts use the same table and columns. Unification is: one table ({prefix}sessions), one logical source of truth (Session class for app; Legacy* and visitor scripts as legacy paths that also use the same table with prefix).

### 5.2 Files to update

| Category | Files |
|----------|--------|
| **Prefix doctrine** | **DONE.** All six Legacy* files now use `LUPO_TABLE_PREFIX . 'sessions'` (or equivalent). |
| **Session class** | Already uses prefix. Ensure all new columns (e.g. system_context if used) are read/written where needed. |
| **UnifiedSessionHandler** | Document whether it delegates to Session or is a thin wrapper (see 5.1a C). Optionally refactor to delegate; or leave as thin wrapper using same table. |
| **Docs** | Update REQUIRED_TABLES_4.1.0.md and any other docs that list `lupo_unified_sessions` to state it is deprecated/removed after migration (plan D). |

### 5.3 Helper functions to remove

- **None** that reference unified_sessions (no such helpers exist).  
- Session helpers were already migrated into the Session class; remaining work is Legacy* and prefix hardening.

### 5.4 Queries to replace

- **Legacy* (6 files):** **DONE.** Every literal `lupo_sessions` in SQL has been replaced with a table name built from the configured prefix.  
- No queries currently target unified_sessions, so no “replace unified_sessions query with sessions” steps.

### 5.5 Migrating logic into Session class

- **Visitor/operator session creation:** Already in Session (createSession) or in visitor-image/livehelp/visitor-chat-stream (direct INSERT). Optionally route those through Session for consistency.  
- **UnifiedSessionHandler:** Either (a) make it call Session for create/read/update/destroy and only handle cookie + system_context, or (b) leave it as a second writer to the same table and document that both write to {prefix}sessions.  
- **AuthManager / AuthGuard:** Already use UnifiedSessionHandler (which uses sessions table); no change required unless we refactor UnifiedSessionHandler to delegate to Session.

### 5.6 Eliminating {prefix}unified_sessions safely

1. **Confirm one-time migration:** Ensure `one_time_unified_sessions_to_sessions.sql` has been run in every environment (so any data from unified_sessions is in sessions and unified_sessions can be dropped).  
2. **Drop table (per env):** Run `DROP TABLE IF EXISTS {prefix}unified_sessions` (using configured prefix).  
3. **Schema/docs:** Remove creation of unified_sessions from new installs (or leave CREATE in install for historical reference and document “do not use; run one_time migration and drop”).  
4. **Docs:** State that the single session table is {prefix}sessions and that unified_sessions is deprecated/removed.

### 5.7 Summary

- **Two tables in schema:** {prefix}sessions (main) and {prefix}unified_sessions (legacy).  
- **One table in use:** Only {prefix}sessions is used by PHP.  
- **Unification:** Already done in code; only cleanup is (1) run the existing one-time migration and drop unified_sessions where it exists, (2) fix Legacy* hardcoding to use prefix, (3) optionally fold UnifiedSessionHandler into Session and document.

---

## 6. No database changes in this step

No schema or data changes were made. This document is analysis and a unification plan only; schema changes will be decided after review.
