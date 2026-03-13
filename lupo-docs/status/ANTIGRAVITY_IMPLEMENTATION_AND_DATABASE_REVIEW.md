---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: "docs/status/ANTIGRAVITY_IMPLEMENTATION_AND_DATABASE_REVIEW.md"
  last_modified_utc: "20260307"
  system_version: "4.0.62"
  channel_id: 42
  actor_id: 1003
  delegation_chain: "cursor:captain"
  artifact_type: "report"
  artifact_kind: "status"
  purpose: "Implementation and database structure review for Antigravity (actor 42); recommendations for Antigravity IDE agent"
  lupo_agent: "cursor"

lupopedia.footer:
  last_verified_utc: "20260307"
  last_verified_by: "cursor"
---

# Antigravity Implementation and Database Review

**Report for:** Antigravity IDE Agent (actor_id 42)  
**Author:** Cursor (1003), per review request  
**Date:** 2026-03-07  
**Scope:** CHANGELOG work attributed to Antigravity, programming review, database structure (TOONs), and recommendations.

---

## 1. Changelog Work Attributed to Antigravity

The following CHANGELOG entries are attributed to or involve Antigravity:

| Entry | Version | Description |
|-------|---------|-------------|
| **CHANGELOG FLARE header** | 4.0.64 | Session `L-LUPO-ANTIGRAVITY`, delegation `antigravity:cursor:captain`, `lupo_agent: antigravity`, `system_version: 4.0.64`, dialog_message references Actor Directory Refactor and WWW Content. |
| **Antigravity Migration** | 4.0.62 | `AntigravityContext.php` and `lupo-agents/antigravity/context.php` consume ContextKernel for single-source resolution. |
| **System Task Processing** | 4.0.62 | Antigravity as actor 0; system actor context (node 0/channel 0) to process pending tasks; status in `CHANNEL_0_ACTOR_0_TASKS.md` resolved to 0 pending. |
| **Configuration Canonicalization** | 4.0.55 | Antigravity: created `lupo-config/`, migrated config, updated `AtomLoader.php` and `version.php` for path alignment; `.gitignore` for `lupo-config/*.local.php`. |
| **Canonical identity Antigravity = 42** | 4.0.57 | Registry and docs: actor_id 42 (slug antigravity); `lupo-actors/42/` structure; ActorLookup `antigravity` → 42. |
| **Antigravity task takeover** | 4.0.56 | Cursor took over Antigravity tasks (token limit); Actor ID Resolution and VSX extension updates documented in `ANTIGRAVITY_TASK_TAKEOVER_REPORT.md`. |

**4.0.64 section (Actor Directory Refactor & Unified Headers)** is documented in the CHANGELOG under the Antigravity-headed block; implementation includes name-based actor workspaces, `agent-www-controller.php`, unified FLARE first-line format, `skills/` and `SkillService`, config and bootstrap updates, and `init_actor_dirs.php`.

---

## 2. Programming Review

### 2.1 AntigravityContext.php (`lupo-includes/classes/AntigravityContext.php`)

- **Kernel consumption:** When `$resolvedContext === null`, the class uses `ContextKernel::getInstance()` and `$kernel->getContext()` / `$kernel->getAuthUser()`. No direct `ContextResolver::resolve()` call; single source of truth via kernel. **Verdict:** Aligned with 4.0.62 migration.
- **Auth fallback:** If kernel does not provide auth, falls back to `AuthService::getCurrentUser()` and `getUserByActorId()`. **Verdict:** Correct for web and CLI.
- **PHP 5.3:** Uses `array()`, no short array `[]`, no null coalescing `??`. **Verdict:** Compliant.
- **Actor shape:** Exposes `name`, `id`, `type`, `paired_actor_id` from context. **Verdict:** Sufficient for conflict resolution and attribution.

### 2.2 lupo-agents/antigravity/context.php

- **Bootstrap:** Defines `ABSPATH`/`LUPOPEDIA_PATH` if missing; requires `lupopedia-config.php` (which loads bootstrap); then requires `ContextKernel.php` and `AntigravityContext.php`. **Verdict:** Correct load order.
- **Kernel:** `ContextKernel::getInstance()` bootstrapped with `$db`, `$table_prefix`, `$state_file`, `$root`, `$authService`. **Verdict:** Matches main app pattern.
- **Global:** Sets `$GLOBALS['antigravity_context'] = new AntigravityContext(null, $authService)`. **Verdict:** Documented contract; consumers get kernel-derived context.

### 2.3 Consistency with Web Actor Selector (4.0.62)

- Web admin uses `AuthService::getActiveActorId()` and `ActorService::getActorsUserCanActAs()`. Antigravity context uses the same kernel that can be updated by `switch-actor.php` (session + `session.md`). **Recommendation:** When Antigravity or VSX extension reads “current actor,” ensure it uses the same resolution path (kernel or `antigravity_context`) so web “Act as” and IDE context stay aligned where intended.

---

## 3. Database Structure Review (TOONs in docs/toons)

TOON files in **`docs/toons/`** (and any in **`lupo-database/lupopedia/toon/`**) are the project’s column/type reference. Canonical DDL remains **`lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql`** (per AGENTS.md and TOON doctrine).

### 3.1 lupo_actors (docs/toons/lupo_actors.toon.json)

- **Primary key:** `actor_name` (varchar(64)); **unique:** `actor_id`, `slug`.
- **Notable columns:** `actor_id`, `actor_name`, `actor_type`, `slug`, `name`, `actor_source_id`, `actor_source_type`, `paired_actor_id`, `workspace_path`, `php_namespace`, `is_deleted`, `deleted_ymdhis`, timestamps `created_ymdhis`, `updated_ymdhis`.
- **Doctrine:** No foreign keys; no triggers. Reserved ID doctrine: no AUTO_INCREMENT; explicit IDs from registry or allocation.

**Recommendation for Antigravity:** Any code that writes or looks up actors by identity must use **`actor_name`** for primary key semantics and **`actor_id`** where relationships or display are keyed by ID. `ActorService::getActorsUserCanActAs()` and similar already use both; new Antigravity-touched code should follow the same (e.g. registry and DB by `actor_name` where PK is used).

### 3.2 lupo_sessions (docs/toons/lupo_sessions.toon.json)

- **Primary key:** `session_id` (varchar(255)).
- **Notable columns:** `actor_id`, `actor_name`, `channel_id`, `federation_node_id`, `is_active`, `is_expired`, `is_revoked`, `expires_ymdhis`, `last_seen_ymdhis`, `recovery_attempts`, `recovery_data` (json), `name_key`, `is_named`, plus security/session_data fields.
- **Doctrine:** All timestamps BIGINT YmdHis UTC; no DB-side logic.

**Recommendation for Antigravity:** Web “Act as” does not change `lupo_sessions.actor_id` (that row stays the logged-in human). Active “act as” is in PHP session and optionally `session.md`. If Antigravity or tooling ever writes session rows, keep `actor_id`/`actor_name` in sync with `lupo_actors` and do not add triggers or default timestamps.

### 3.3 lupo_auth_users (docs/toons/lupo_auth_users.toon.json)

- **Primary key:** `auth_user_id`. Columns: `username`, `display_name`, `email`, `password_hash`, auth provider fields, `last_login_ymdhis`, `created_ymdhis`, `updated_ymdhis`, `is_active`, `is_deleted`, `deleted_ymdhis`.
- **Link to actors:** Via `lupo_actors.actor_source_type = 'user'` and `actor_source_id = auth_user_id`.

**Recommendation for Antigravity:** No change needed for auth users from Antigravity; identity resolution goes through kernel and AuthService.

### 3.4 lupo_contents (docs/toons/lupo_contents.toon.json)

- **Primary key:** `content_id`. Includes `federation_node_id`, `actor_id`, `slug`, `custom_path`, `content_type`, FLIP-related fields (`file_path_from_root`, `file_last_modified_system_version`, `file_last_modified_utc`), and consolidated JSON columns (e.g. `tags`, `content_events`).
- **Unique:** `(federation_node_id, slug)` and `custom_path` per doctrine/docs.

**Recommendation for Antigravity:** If Antigravity or agent-www content writes or updates `lupo_contents`, use only columns present in the TOON; set `actor_id` from kernel/context; use BIGINT YmdHis for any timestamp fields; no new FK or triggers.

---

## 4. Recommendations for Antigravity

### 4.1 Identity and Context

- **Use kernel/antigravity_context:** Continue using `AntigravityContext` (and thus ContextKernel) as the single source for actor and auth context. Avoid ad-hoc `ContextResolver::resolve()` or session file parsing outside the kernel.
- **Web “Act as”:** If the VSX extension or Antigravity tooling should reflect the same “effective actor” as the web admin selector, read from the same kernel (or session.md when used for CLI). Prefer one code path (e.g. `resolveEffectiveActorId()` or kernel’s effective actor) so web and IDE stay aligned when desired.

### 4.2 Database and TOONs

- **Schema reference:** Use **`docs/toons/*.toon.json`** (and project doctrine) for column names and types. Canonical DDL: **`lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql`**. Do not rely on live DB introspection for schema.
- **lupo_actors:** Treat `actor_name` as primary key in any new or modified code that performs lookups/inserts/updates by identity. Use `actor_id` for display and relationships; keep both in sync with registry and install/seed.
- **Reserved IDs / no AUTO_INCREMENT:** For registry-backed or reserved-ID tables (e.g. actors, channels, auth_users), do not use AUTO_INCREMENT or `lastInsertId()`; use explicit IDs and upsert patterns per reserved-id doctrine.

### 4.3 Session and session.md

- **session.md:** Updated by `switch-actor.php` for CLI sync. AntigravityContext is built from the kernel, which in web context uses session (and active_actor_id). For CLI, kernel reads from session.md and DB. Antigravity should not write `session.md` unless implementing a dedicated “switch actor” path that follows the same rules as `switch-actor.php` (e.g. allowed list, CSRF in web).

### 4.4 4.0.64 and Later Work

- **Actor directory refactor:** Name-based workspaces (`lupo-actors/<actor_name>/`) are documented in CHANGELOG and DIRECTORY_STRUCTURE. Any Antigravity or tooling that builds paths to actor dirs should prefer name-based paths with ID fallback as in current ActorService/config.
- **Agent WWW and security:** `agent-www-controller.php` and any agent-served content must enforce `realpath` containment and avoid directory traversal. Antigravity-contributed routes or controllers should follow the same pattern.
- **FLARE headers:** Unified first-line format is mandated in FLARE_DOCTRINE. New or edited artifacts should comply so validation and tooling remain consistent.

### 4.5 Code Quality and Doctrine

- **PHP 5.3:** No `[]`, `??`, return types, or PHP 7+ syntax in core paths. Antigravity-touched PHP should remain 5.3-compatible.
- **PDO_DB only:** All DB access via `DatabaseFactory::getConnection()` and PDO_DB methods; prepared statements with named placeholders; table prefix from `LUPO_TABLE_PREFIX`.
- **No FKs, triggers, stored procedures:** Per database doctrine; any schema or migration suggested by Antigravity must adhere.

---

## 5. Summary Table

| Area | Status | Note |
|------|--------|------|
| AntigravityContext + kernel | OK | Correct use of ContextKernel; single-source resolution. |
| lupo-agents/antigravity/context.php | OK | Bootstrap and global contract correct. |
| CHANGELOG Antigravity entries | Reviewed | Header and 4.0.62/4.0.55/4.0.64 references captured. |
| TOONs (docs/toons) | Reviewed | lupo_actors (PK actor_name), lupo_sessions, lupo_auth_users, lupo_contents summarized. |
| actor_name vs actor_id | Recommendation | Use actor_name for PK semantics; actor_id for relationships/display. |
| session.md / web “Act as” | Recommendation | Keep one resolution path (kernel) for consistency. |
| 4.0.64 refactor / agent-www | Recommendation | Name-based paths; realpath containment; FLARE header format. |

---

**Report end.** For follow-up, see `docs/status/ANTIGRAVITY_TASK_TAKEOVER_REPORT.md`, `docs/CHANNEL_0_ACTOR_0_TASKS.md`, and `docs/doctrine/` for database and FLARE doctrine.
