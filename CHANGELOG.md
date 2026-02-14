# Lupopedia Changelog

Canonical version history.

## Versioning doctrine (4.0.x)

- **Purpose of 4.0.x:** The 4.0.x series (4.0.0 → 4.0.4 and all future 4.0.x patches) is a development and stabilization series. It exists solely to refine the single supported upgrade path: **Crafty Syntax 3.7.5 → Lupopedia 4.0.x**. Each patch is an iteration on the installer, wizard, importer, doctrine enforcement, and compatibility rules for that path.
- **No Lupopedia → Lupopedia upgrades before 4.1.0.** In the 4.0.x line there are no supported upgrades from an existing Lupopedia installation. The only valid inputs are a new install or an upgrade from Crafty Syntax 3.7.5.
- **4.1.0** will be the first version to support Lupopedia → Lupopedia upgrades. 4.1.0 will not be created until a stable 4.0.x release is published through auto-installers (e.g. Softaculous, Installatron). Until then, 4.0.x remains the development/stabilization series.

---

## Lupopedia 4.0.5 — Stabilization Patch (Role-Based Identity, PHP 5.3 Compatibility)

Lupopedia 4.0.5 is part of the iterative development cycle for the **Crafty Syntax 3.7.5 → Lupopedia 4.0.x** upgrade path.  
There are **no Lupopedia → Lupopedia upgrades** in the 4.0.x series.

This patch includes:

### 1. PHP 5.3 Compatibility (Array Syntax Sweep)

- Replaced short array syntax `[]` with `array()` in all updated files to enforce PHP 5.3 compatibility.
- **Files updated:** `lupo-includes/themes/default/layouts/main_layout.php`, `lupo-includes/modules/channels/channels-controller.php`, `debug_collection_zero.php`, `api/load_collection_tabs.php`, `app/Services/CraftySyntax/LegacyFunctions.php`, `app/Services/ActorService.php`.
- In `channels-controller.php`: all empty-array assignments, all `execute()`, `render_main_layout()`, and `extract()` array arguments, and inline array literals (e.g. `$pending_visitors[] = [...]`) converted to `array()` with correct closing parentheses.
- Default parameters (e.g. `array $params = []`) and ternary fallbacks (`: []`) converted to `array()`.
- **Rule:** `.cursor/rules/php-5-3-compatibility.mdc` already required `array()`; wording strengthened so short array syntax is never generated in new or edited code.
- **Confirmation:** `array()` is not deprecated in PHP 8.3 and remains fully supported.
- **Audit report:** `docs/audits/PHP_5_3_ARRAY_SYNTAX_SWEEP_REPORT.md` documents the sweep, lists updated files, and provides patterns for converting any remaining files. Array push (`$var[] = value`) was not changed.

### 2. Operator → Role-Based Identity Migration

- Removed all operator-based terminology and logic from the identity and permission model.
- **No `lupo_operators` table;** identity is `lupo_auth_users` + `lupo_actors`; permissions are `lupo_actor_channel_roles` with `role_key` (`captain`, `administrator`, `monitor`).
- **Files updated:** `livehelp_js.php`, `image.php` (role checks: `role_key IN ('captain','monitor','administrator')`); `install_wizard_classes.php` (personal channel creation and captain assignment use `lupo_actor_channel_roles`; reserved channel 1 = Administration; captain for Crafty admins on channel 1); `install.php` (wording: operator channels → personal channels and captain roles); `lupo-includes/modules/channels/channels-controller.php` (all permission and role logic switched from `lupo_channel_roles` to `lupo_actor_channel_roles`; `channel_role_id`/`role_type` → `actor_channel_role_id`/`role_key`); `lupo-includes/classes/AdminUsersHandler.php` (channel 1 admin role via `lupo_actor_channel_roles` and `role_key`); `lupo-includes/themes/default/layouts/main_layout.php` (comment: channel staff interface); `README.md` (operator sessions → staff sessions; uploads path no longer references operators).
- **Audit report:** `docs/audits/OPERATOR_TO_ROLE_BASED_SWEEP_REPORT.md` lists all files changed, installer logic, migration file, and confirmations.

### 3. Installer Enhancements

- **Personal channels for Crafty operators:** For each `livehelp_users` row with `isoperator='Y'`, the wizard creates a row in `lupo_channels` with `channel_name = name + "'s Channel"` and inserts into `lupo_actor_channel_roles` with `role_key = 'captain'`. No `lupo_channel_roles`; all assignments use `lupo_actor_channel_roles`.
- **Global admin channel (channel_id = 1):** Reserved channel 1 is defined as **Administration** (key `administration`, name `Administration`). For each `livehelp_users` row with `isadmin='Y'`, the wizard inserts into `lupo_actor_channel_roles` with `actor_id = auth_user_id`, `channel_id = 1`, `role_key = 'captain'` (idempotent).
- **Reserved channels:** System actor (actor_id = 0) is assigned captain in `lupo_actor_channel_roles` for channels 1, 42, 5100. `createReservedSystemChannels` inserts those captain entries so role-based checks see them.
- **Wizard wording:** All references to "operator channels" updated to "personal channels and captain roles"; step descriptions and session keys retained for compatibility.

### 4. Importer Validation

- **`import_from_old_crafty_syntax.sql`** confirmed and left correct: first INSERT into `lupo_auth_users` from `livehelp_users` WHERE `isoperator='Y'`; second INSERT for all remaining users (idempotent). Single INSERT into `lupo_actors` for Crafty operators only (`isoperator='Y'`) with **`actor_id = auth_user_id`**, `actor_source_id = auth_user_id`, `actor_source_type = 'lupo_auth_users'`; timestamps via `CAST(DATE_FORMAT(UTC_TIMESTAMP(), '%Y%m%d%H%i%S') AS SIGNED)`; idempotent. No INSERT into `lupo_operators`; no role assignment during import (wizard assigns roles later). Department mapping UPDATE retained (`lupo_actor_departments.actor_id`).
- No UNSIGNED in importer; no operator table usage; actor_id = auth_user_id enforced for imported humans.

### 5. Permission System Rewrite

- All permission checks now use **`lupo_actor_channel_roles`** and **`role_key`** (captain, administrator, monitor).
- **channels-controller.php:** Every use of `lupo_channel_roles` (channel_role_id, role_type) replaced with `lupo_actor_channel_roles` (actor_channel_role_id, role_key). All SELECTs, UPDATEs, and INSERTs for channel roles use the new table and column names; view data still exposed as `role_type` for compatibility.
- **AdminUsersHandler.php:** Channel 1 (admin channel) role read/write uses `lupo_actor_channel_roles` and `role_key`.
- **livehelp_js.php, image.php:** "Anyone online" checks use `role_key IN ('captain','monitor','administrator')` (replaced former `operator` with `administrator`).
- No code path checks `isoperator` or `isadmin` for runtime permissions; all permission checks go through `lupo_actor_channel_roles`.

### 6. Migration File for Existing Databases

- **`database/migrations/migration_operator_to_actor_channel_roles.sql`** added for existing installations that previously used `lupo_channel_roles` for permission checks. It: (1) sets `lupo_channels` row for `channel_id = 1` to key/slug/name **Administration** and updates `updated_ymdhis` (BIGINT UTC); (2) copies rows from `lupo_channel_roles` into `lupo_actor_channel_roles` (idempotent, with generated `actor_channel_role_id`; `role_type` → `role_key`). Run once after deploying 4.0.5 if the live DB still has roles only in `lupo_channel_roles`. New installs get roles from the wizard in `lupo_actor_channel_roles` only.

### 7. Documentation and Doctrine

- **README.md:** Operator sessions → staff (captain/administrator/monitor) sessions; uploads path no longer includes `operators`.
- **Migration doctrine:** `docs/doctrine/MIGRATION_DOCTRINE.md` and `.cursor/rules/migration-doctrine.mdc` added (single source for migration doctrine; no DB inference; no CLI SQL; compatibility notes). README sections for database access and SQL compatibility updated.
- **Audit reports:** `docs/audits/OPERATOR_TO_ROLE_BASED_SWEEP_REPORT.md`, `docs/audits/PHP_5_3_ARRAY_SYNTAX_SWEEP_REPORT.md` document the operator→role sweep and the PHP 5.3 array syntax sweep, including files touched, installer logic, migration file, and patterns for remaining work.

### 8. Other Fixes in This Patch

- **livehelp_js.php (root):** `date()` → `gmdate()` for UTC.
- **lupo-includes/modules/crafty_syntax/livehelp-js.php:** Replaced direct PDO with PDO_DB wrapper; all `date('YmdHis')` → `gmdate('YmdHis')`; removed `??` for PHP 5.3; default-department logic corrected.
- **channels-controller.php:** One `??` in pending-visitors block replaced with `isset() ? : ` for PHP 5.3 compatibility where edited.

**Versioning Note:**  
Lupopedia 4.0.x is a development/stabilization series.  
The ONLY supported upgrade path is:

    Crafty Syntax 3.7.5 → Lupopedia 4.0.x

There are **no Lupopedia → Lupopedia upgrades** until **4.1.0**.

---


## Lupopedia 4.0.4 — Stabilization Patch (Crafty Syntax 3.7.5 → Lupopedia 4.0.x)

Lupopedia 4.0.4 is part of the iterative development cycle for the **Crafty Syntax 3.7.5 → Lupopedia 4.0.x** upgrade path.  
There are **no Lupopedia → Lupopedia upgrades** in the 4.0.x series.

This patch includes:

### 1. Identity & Actor Model Corrections

- Clarified unified actor model (humans, system identities, AI agents share `actor_id`; separate `users` and `agents` tables exist for metadata, but all relationships use `actor_id` exclusively).
- Updated README Five Pillars to reflect correct identity architecture and global ID registry (`actor_id`, `collection_id`, `channel_id`).
- Ensured doctrine consistently states that the `actors` table is the unified identity layer for the entire semantic OS.

### 2. Collection 0 Fixes

- Seeded Collection 0 correctly.
- Corrected tabs assigned to wrong `collection_id` (1 instead of 0).
- Seeded `lupo_contents` for Collection 0; added `default_collection_id = 0` where required.
- Seeded tab → content mapping (`lupo_collection_tab_map`).
- Added **`debug_collection_zero.php`** in project root for diagnostics (standalone PDO script; no bootstrap/session/auth; runs collections, tabs, contents, and tab→content mapping queries; outputs HTML tables and row counts). Usable at `https://localhost/lupopedia/debug_collection_zero.php`.
- Session default `collection_id` set to 0 where appropriate; main layout and tab render allow `collection_id = 0`; JS auto-loads tabs for collection 0 on DOM ready; API `load_collection_tabs.php` accepts `collection_id = 0`.

### 3. Installer & Wizard Fixes

- Wizard writes `lupopedia-config.php` correctly; after writing, wizard renames or removes old Crafty Syntax `config.php` to `config_backup.php` (or removes it if rename fails); action logged in wizard config log.
- Bootstrap/entry config load order updated to prefer **`lupopedia-config.php` first**, then `config.php` only if lupopedia-config does not exist (legacy mode). Applied in `index.php`.
- Install complete step confirms `lupopedia-config.php` is active and Crafty `config.php` has been backed up or removed; displays config log on success.
- Installer and seed logic use correct timestamp doctrine (BIGINT UTC `YYYYMMDDHHIISS`); installer seeds Collection 0, tabs, contents, and mappings.

### 4. AJAX & UI Pipeline Fixes

- Fixed saved-collections-container not loading on page load.
- Default session `collection_id = 0`.
- JS triggers `loadTabsForCollection()` (or equivalent) on DOM ready for collection 0.
- AJAX endpoint `load_collection_tabs.php` accepts `collection_id = 0`.

### 5. Doctrine & README Rewrite

- Rewrote README to:
  - Clarify Lupopedia 4.0.x = Crafty Syntax reborn + Semantic OS + optional AI agents.
  - Clarify 4.0.x versioning doctrine (only path: Crafty Syntax 3.7.5 → Lupopedia 4.0.x; no L→L until 4.1.0).
  - Add unified actor model explanation and Five Pillars (Unified Actor, Temporal, Relationship, Doctrine, Federation).
  - Unify timestamp format to **`YYYYMMDDHHIISS`** throughout; add standard audit fields (`created_ymdhis`, `updated_ymdhis`); cross-reference **`timestamp_ymdhis`** class for arithmetic; add soft delete pattern (`is_deleted`, `deleted_ymdhis`).
  - Add **PHP & Database Development Standards** subsection (PHP 5.3–8.3+ compatibility, OOP, timestamp format, database constraints, soft delete).
  - Add **Security Doctrine** section (per LEXA): PHP compatibility security, input validation, file upload security, session management, configuration security, error handling, dependency security, security violation classification.
  - Add **Security Audit Doctrine** (security review before merge, quarterly audits, immediate audit after incidents, AI-generated code same review as human).
  - Add Quick Start requirements: PHP 5.3 through 8.3+; table count goal under 200 (196 as of 2/14/2026).
- CHANGELOG: versioning doctrine block and per-version 4.0.x doctrine lines retained/updated.

### 6. Security Enhancements (LEXA Boundary Keeper)

- Added full **Security Doctrine** section in README: PHP compatibility security, input validation, file upload security, session management, configuration security (`lupopedia-config.php` 0640, credentials only in config), error handling (generic user messages, detailed file logs, no stack traces in production), dependency security (bundled libs, `VERSIONS.md`, patches within 30 days), security violation classification (CRITICAL/MAJOR/MINOR).
- Added **Security Audit Doctrine**: security review before merge, quarterly full audits, immediate audit after security incident, AI-generated code must pass same security review as human code.

### 7. Seed File Corrections

- Added `@now` (or equivalent) timestamp variable where applicable.
- All seed inserts use BIGINT UTC timestamps (`YYYYMMDDHHIISS`).
- Idempotent patterns (e.g. `INSERT … ON DUPLICATE KEY UPDATE`) where appropriate.
- Collection 0, tabs, contents, and tab→content mappings seeded correctly.

### 8. Repository Hygiene

- Wolfie Header rules: `file.last_modified_system_version` and `file.channel` updated on edits; default channel `0000` when unknown.
- Removed drift and inconsistencies across touched files (156 files touched in this thread).

### 9. Miscellaneous Fixes

- Auth and session: AuthGuard, AuthManager, AuthRoleResolver, AuthService, Session, auth-helpers, auth-ui-helpers, identity-helpers, session-compat-5.3.php, auth-controller, auth-renderer, password-hash aligned with PHP 5.3–compatible patterns and identity doctrine.
- Corrected doctrine references; updated navigation logic; fixed missing or incorrect includes; fixed session initialization and config loading; fixed installer path and config precedence so post-install only `lupopedia-config.php` is used.

**Files and areas touched (representative):** `install_wizard_classes.php`, `install.php`, `index.php`, `debug_collection_zero.php`, `lupo-includes/bootstrap.php`, `lupo-includes/themes/default/layouts/main_layout.php`, `api/load_collection_tabs.php`, `app/auth/*` (AuthGuard, AuthManager, AuthRoleResolver, AuthService, Session), `lupo-includes/functions/auth-helpers.php`, `lupo-includes/functions/auth-ui-helpers.php`, `lupo-includes/functions/identity-helpers.php`, `lupo-includes/functions/session-compat-5.3.php`, `lupo-includes/modules/auth/auth-controller.php`, `lupo-includes/modules/auth/auth-renderer.php`, `lupo-includes/security/password-hash.php`, `README.md`, `CHANGELOG.md`, seed and installer-related files, and related layout/API/auth call sites. Many additional files touched across the 4.0.4 stabilization thread (doctrine updates, security sections, README rewrite). No TOON files or future_features tables were modified by this patch.

**Versioning Note:**  
Lupopedia 4.0.x is a development/stabilization series.  
The ONLY supported upgrade path is:

    Crafty Syntax 3.7.5 → Lupopedia 4.0.x

There are **no Lupopedia → Lupopedia upgrades** until **4.1.0**, which will not be created until after a stable 4.0.x release is published through auto-installers.


## Lupopedia 4.0.3

- **4.0.x doctrine:** This version is part of the iterative development cycle for the **Crafty Syntax 3.7.5 → Lupopedia 4.0.x** upgrade path. No Lupopedia → Lupopedia upgrades exist for this version.

- **PHP 5.3+ compatibility:** Sweep across core request paths to remove null coalescing (`??`) and short array syntax (`[]`). Replaced with `isset() ? : ` ternaries and `array()`. Session cookie params use the 5-argument form for PHP 5.3 (no array form, no `samesite`). Files touched: `content-renderer.php`, `index.php`, `bootstrap.php`, `module-loader.php`, `topbar.php`, `actors-controller.php`, `my-profile.php`, `admin.php`, and related layout/view files.
- **Reserved ID doctrine:** Added `.cursor/rules/reserved-id-doctrine.mdc`. Tables for actors, channels, and users do not use AUTO_INCREMENT; IDs are reserved or explicitly allocated. Code must never rely on `lastInsertId()` for these tables; must check if ID exists and then UPDATE or INSERT with explicit ID.
- **Schema (install):** Removed AUTO_INCREMENT from `lupo_actors` and `lupo_auth_users` in `database/migrations/install_new_lupopedia.sql`. Primary keys remain plain bigint; application supplies IDs.
- **`lupo_findpuka()`:** New helper in `lupo-includes/functions/reserved-id-helpers.php` (PHP 5.3–compatible, no namespace). Returns the next available primary-key ID for a given table/column, optionally within a range. Uses PDO_DB only; no AUTO_INCREMENT or lastInsertId(). Loaded from `bootstrap.php`.
- **Insert-path corrections:** All actor and channel (and channel_roles) insert paths updated to use explicit IDs:
  - **ActorService:** `createActorForAuthUser()` uses `lupo_findpuka()` for next `actor_id`, then insert with explicit `actor_id`; returns that ID (no lastInsertId).
  - **LegacyFunctions:** `resolve_actor_from_lupo_user()` uses `lupo_findpuka()` and insert with explicit `actor_id`.
  - **run_labs_handshake.php:** Allocates `actor_id` via `lupo_findpuka()` (fallback to MAX+1), inserts with explicit `actor_id`.
  - **channels-controller.php:** Captain, administrator, and monitor role inserts use `lupo_findpuka()` for `channel_role_id` and INSERT with explicit `channel_role_id`.
  - **AdminUsersHandler:** New channel role uses `lupo_findpuka()` for `channel_role_id` (fallback to MAX+1).
  - **migrate_filesystem_to_db.php:** `createChannelRecord()` allocates `channel_id` with MAX+1 and inserts with explicit `channel_id`; returns that ID (no lastInsertId).
  - **GroundedAgentModel:** `createActorRecord()` allocates `actor_id` with MAX+1, builds full row for `lupo_actors`, inserts and returns allocated `actor_id` (does not use insert_id).
- **My Profile save:** Fixed profile save (e.g. `/my-profile/save`) so updates persist. `lupo_actor_properties` and `lupo_uploads` have no AUTO_INCREMENT; controller now allocates explicit `actor_property_id` and `upload_id` and uses PDO_DB `query`/`fetchRow`/`update`/`insert` only (no raw prepare/execute). TOON-backed column names preserved.
- **Admin Users (OOP):** Admin users section logic moved into non-namespaced class `AdminUsersHandler` in `lupo-includes/classes/AdminUsersHandler.php`. `admin.php` delegates to `AdminUsersHandler::render()` for the Users section.
- **My Profile UI:** Timezone on profile edit page is a dropdown of UTC offsets (decimal style) with human-readable labels (e.g. Central — Chicago, Sioux Falls). Stored in `actor_properties.property_value` as before.
- **Cursor rules:** Added `.cursor/rules/php-5-3-compatibility.mdc` (no `??`, no `[]`, no return types in core, session cookie 5-arg form).

---

## Lupopedia 4.0.2

- **4.0.x doctrine:** This version is part of the iterative development cycle for the **Crafty Syntax 3.7.5 → Lupopedia 4.0.x** upgrade path. No Lupopedia → Lupopedia upgrades exist for this version.
- **Helper refactors:** Domain-by-domain migration of helpers to services/wrappers (Collection Zero, Collection Tabs, Saved Collections, Redirect, Limits, Atoms/Version, Upload). Thin wrappers in `lupo-includes/functions/` call into `app/Services` and `app/Support` where applicable.
- **Version doctrine:** Canonical versioning doctrine established: patch-only 4.0.x; only upgrade path Crafty Syntax 3.7.5 → Lupopedia 4.0.x; no Lupopedia→Lupopedia upgrades until 4.1.0. Version fallbacks and examples in code/atoms set to the single current target; stray version references removed.
- **Python scripts:** All Python scripts consolidated under `scripts/`. Generators and utilities moved from `database/` and `dialogs/` into `scripts/`; doctrine updated so Python lives only in `scripts/`.
- **Reserved-word column renames:** One-time migration for MySQL reserved words: `lupo_actor_group_membership.role` → `role_key`, `lupo_artifacts.type` → `entity_type`, `lupo_pack_role_registry.role` → `role_key`. Install schema and API (artifact, timeline) updated accordingly.
- **Doctrine and rules:** Mandatory .cursorrules for zero-installations / no backward compatibility and version lock; LUPOPEDIA_DOCTRINE and related docs updated to reflect current version and upgrade path.

---

## Lupopedia 4.0.1

- **4.0.x doctrine:** This version is part of the iterative development cycle for the **Crafty Syntax 3.7.5 → Lupopedia 4.0.x** upgrade path. No Lupopedia → Lupopedia upgrades exist for this version.
- **Architecture rebuild:** Structural changes and Crafty Syntax integration preparation. Legacy agent and channel directories removed; new doctrine and TOON files added; migration SQLs for actor model and related fixes.
- **Login and session:** New login system (MD5 upgrade path, redirect-back, session upgrade). Collection 0 documentation landing and Q/A module with routing consolidation.
- **Channels and edges:** ChannelsController and EdgesController added with routing for channels and edges; placeholder views and 3-panel channel UI skeleton; module-loader and layout updates.
- **Prefix normalization:** Table-prefix normalization completed; tables use dynamic `lupo_` prefix from config; legacy unified tables renamed with `_old` suffix where preserved.
- **Crafty Syntax subsystem:** Operator console activated under crafty_syntax; operator expertise and AI→human escalation engine; routing, controllers, and views for Crafty Syntax module.

---

## Lupopedia 4.0.0

- **4.0.x doctrine:** This version is part of the iterative development cycle for the **Crafty Syntax 3.7.5 → Lupopedia 4.0.x** upgrade path. No Lupopedia → Lupopedia upgrades exist for this version.
- Initial Lupopedia release.
- **Upgrade path:** This version only supports new installs or upgrades from **Crafty Syntax 3.7.5**. No Lupopedia→Lupopedia upgrades exist. Lupopedia→Lupopedia upgrade paths do not exist until after version 4.1.0.

---

## Crafty Syntax 3.7.5 (Legacy)

- Final legacy release of Crafty Syntax.
- This is the only supported source for upgrading to Lupopedia 4.0.x. All upgrades to Lupopedia 4.0.x are from Crafty Syntax 3.7.5 (or new installs). No other upgrade paths are valid for the 4.0.x line.
