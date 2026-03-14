# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: ".\docs\PDO_DB_DOCTRINE_REFACTOR_REPORT.md"
  file_hash: "23e5e599d2f858d6667caeda1d9786644aa6943429421700349992e63eb1e87a"
  last_updated_utc: "20260228155738"
  system_version: "4.0.51"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "documentation"
  artifact_kind: "documentation"
  purpose: "Documentation file with LUPOPEDIA HEADERS applied"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.51"]
  tags: ["documentation", "flare_applied"]
  lupo_agent: "windsurf"

lupopedia.edges:
  outbound_edges:
    - { to: "CHANGELOG.md", type: "references", weight: 1.0 }
    - { to: "lupo-docs/doctrine/", type: "references", weight: 1.0 }

lupopedia.footer:
  last_verified: "20260228155738"
  last_verified_by: "windsurf"
---

# LUPOPEDIA HEADERS (replaces FLARE)

---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  lupopedia.edges: []
  file_path_from_root: "lupo-docs\PDO_DB_DOCTRINE_REFACTOR_REPORT.md"
  file_hash: "518833d920c32c6590c5ee2d3047159f5ed2f3db3e1b948f045fc57dd3a77b0b"
  file_path_from_root: "lupo-docs\PDO_DB_DOCTRINE_REFACTOR_REPORT.md"
  file_hash: "f81a9d02b47aa740f4b05ebc76381b86264fc66b8a7f1bff5ce8147e03b8cd5f"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "PDO_DB Doctrine + Crafty Integration Refactor Report"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "pdo_db_doctrine_refactor_reportmd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
lupopedia.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

# PDO_DB Doctrine + Crafty Integration Refactor Report

**Date:** 2026-02-10  
**Rule:** `.cursor/rules/pdo-db-database-access-doctrine.mdc`  
**Migration reference:** `lupo-docs/doctrine/migrations/`, `MIGRATION_MAPPING_REFERENCE.md`  
**Schema source:** TOONs (lupo-docs/toons — empty); fallback: `lupo-database/migrations/install_new_lupopedia.sql`, migration docs.

---

## 1. Summary

- **Crafty Syntax** is integrated into Lupopedia; functionality remains, old tables do not.
- All references to **livehelp_*** and **lupo_users** were removed or refactored to **{prefix}sessions** and **lupo_auth_users** / **lupo_actors** per migration docs.
- **PDO_DB** is used for all DB access in refactored code: `fetchRow`, `fetchAll`, `insert`, `update`, `delete` with named placeholders only.
- **Table prefix** is never hardcoded: `$table_prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_'` then `$table_prefix . 'sessions'`, etc.
- **Session logic** flows through `App\Auth\Session` or through the shared helper **crafty_get_session_people()** which reads from **{prefix}sessions** (per livehelp_sessions_migration.md, livehelp_users_migration.md).

---

## 2. Files changed

| File | Changes |
|------|--------|
| **lupo-docs/doctrine/migrations/generated/README.md** | Created; documents that one-time SQL migrations go here and are run manually. |
| **app/Services/CraftySyntax/LegacyFunctions.php** | Added **crafty_get_session_people($session_id)** (PDO_DB, {prefix}sessions). Refactored **log_toon_event** to PDO_DB insert with prefix. **resolve_actor_from_lupo_user** and **get_current_actor_id** now use PDO_DB and lupo_sessions/lupo_actors (actor_source_type = 'lupo_auth_users'); no lupo_users. |
| **app/Services/CraftySyntax/LegacyAdminOptions.php** | Replaced lupo_users query with **crafty_get_session_people()**. |
| **app/Services/CraftySyntax/LegacyAdminChatBot.php** | Replaced lupo_users query with **crafty_get_session_people()**; use assoc keys. |
| **app/Services/CraftySyntax/LegacyLive.php** | Replaced lupo_users query with **crafty_get_session_people()**; null-safe. |
| **app/Services/CraftySyntax/LegacyAdminActions.php** | Replaced lupo_users query with **crafty_get_session_people()**. |
| **app/Services/CraftySyntax/LegacyChooseDepartment.php** | Replaced lupo_users query with **crafty_get_session_people()**. |
| **app/Services/CraftySyntax/LegacyDepartments.php** | Replaced lupo_users query with **crafty_get_session_people()**; build $row for existing indices. |
| **app/Services/CraftySyntax/LegacyChannels.php** | Replaced lupo_users query with **crafty_get_session_people()**; removed **livehelp_id** (legacy column); use actor_id/session only. |
| **app/Services/CraftySyntax/LegacyAdminChatXmlHttp.php** | Replaced lupo_users query with **crafty_get_session_people()**. |
| **app/Services/CraftySyntax/LegacyExternalChatXmlHttp.php** | Replaced lupo_users query with **crafty_get_session_people()**. |
| **app/Services/CraftySyntax/LegacyUserChatRefresh.php** | Replaced lupo_users query with **crafty_get_session_people()**; department fetch via PDO_DB fetchRow with prefix. |
| **app/Services/CraftySyntax/LegacyAdminChatRefresh.php** | Replaced lupo_users query with **crafty_get_session_people()**. |
| **app/Services/CraftySyntax/WorldGraphHelper.php** | Full refactor: all DB access via PDO_DB (fetchRow, insert) with table prefix; world_registry uses created_ymdhis/updated_ymdhis per install schema; no query(), no DB_FETCHMODE_ASSOC, no interpolated SQL. |
| **lupo-includes/modules/channels/channels-controller.php** | Replaced **$db->query()** and **prepare/execute/fetch** with **$db->fetchRow()**, **fetchAll()** with named params. |
| **lupo-includes/modules/crafty_syntax/visitor-image.php** | Replaced **$db->query()->fetch()** with **$db->fetchRow()** when $db is PDO_DB. |
| **lupo-includes/modules/crafty_syntax/livehelp-js.php** | Replaced **$db->query()->fetch()** with **$db->fetchRow()**. |
| **lupo-includes/modules/crafty_syntax/choosedepartment.php** | Replaced **$db->query()** and fetchAll with **$db->fetchAll()**. |
| **lupo-includes/modules/help/help-model.php** | Replaced **$db->query()->fetchAll(PDO::FETCH_COLUMN)** with **$db->fetchAll()** and array_column. |

*(Previously refactored in earlier steps: Session, SessionHandler, bootstrap, LegacySessionManager, LegacySessionIdentity, LegacyAdminCommon, LegacyAdminChatFlush, LegacyUserChatFlush, LegacyAuthentication, LegacyIsFlushDetection, live.php.)*

---

## 3. Violations fixed

- **Removed:** All **lupo_users** and **livehelp_users** references; session/operator state now from **{prefix}sessions** via **crafty_get_session_people()** or Session class.
- **Removed:** **$mydatabase->query()**, **$db->query()**, **$rs->numrows()**, **$rs->fetchRow(DB_FETCHMODE_*)**, **filter_sql()**, raw interpolated SQL in refactored files.
- **Replaced with:** **$db->fetchRow()**, **$db->fetchAll()**, **$db->insert()**, **$db->update()**, **$db->delete()** with named placeholders and table prefix.
- **Legacy table references:** All mapped per migration docs (livehelp_users → lupo_auth_users + session state in lupo_sessions; livehelp_sessions → lupo_sessions; livehelp_departments → lupo_departments).

---

## 4. Confirmations

| Requirement | Status |
|-------------|--------|
| No raw SQL or procedural DB calls in refactored code | **Yes** — only PDO_DB methods and bound parameters. |
| All DB access uses PDO_DB | **Yes** in all refactored files. |
| All session logic uses Session class or crafty_get_session_people() | **Yes** — session reads from {prefix}sessions via Session or crafty_get_session_people(); writes via Session or PDO_DB in LegacySessionManager/LegacySessionIdentity. |
| No legacy table references (livehelp_*, lupo_users) | **Yes** — removed or replaced with {prefix}sessions, lupo_auth_users, lupo_actors, lupo_departments per migration. |
| All mappings follow migration docs | **Yes** — MIGRATION_MAPPING_REFERENCE.md and per-table migration .md files used. |
| Table prefix never hardcoded | **Yes** — LUPO_TABLE_PREFIX used everywhere in refactored code. |

---

## 5. One-time SQL migration files generated

- **None.** No new migration files were added under `lupo-docs/doctrine/migrations/generated/` in this refactor. The folder exists with a README; schema was taken from install_new_lupopedia.sql and migration docs. If you discover missing columns or tables at runtime, add a one-time SQL file there and run it manually.

---

## 6. Remaining files (not refactored in this pass)

- **Scripts and setup:** `lupo-scripts/migrate_user_mappings.php`, `lupo-scripts/validate_tab_mappings.php`, `lupo-scripts/setup_help_list_modules.php`, `lupo-scripts/verify_grounded_architecture.php`, `lupo-scripts/run_migration_4_1_6.php`, `lupo-scripts/cleanup_old_directories.php`, `lupo-includes/lupopedia-setup.php` (detect/drop livehelp_* for wizard), `deploy/apply_dialog_schema.php`, `app/Services/System/SystemHealthService.php`, `app/Services/System/LupopediaMigrationController.php`, `lupo-includes/Dialog/Database/DialogDatabase.php`, `lupo-includes/MigrationOrchestrator/`, `lupo-includes/modules/list/list-controller.php`, `lupo-includes/class-thoth*.php`, `lupo-includes/class-iris.php`, `lupo-includes/calss-thoth_topic.php`, `lupo-includes/class-wolfmind.php` — may still use **$db->query()** or **$pdo->query()**; can be migrated in a later pass.
- **References kept by design:** `lupopedia-setup.php` and migration wizard still mention **livehelp_*** for one-time detect/drop of legacy tables; URL/path names like **livehelp_js.php** and **livehelp-js.php** are file names, not table references.
