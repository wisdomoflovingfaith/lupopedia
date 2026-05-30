---
lupopedia.init:
  file_identity: "pdo-db-database-access-doctrine.md"
  artifact_type: "windsurf_rule"
  artifact_kind: "doctrine"
  namespace: "windsurf"
  system_version: "4.0.76"
  orchestrator_actor: "windsurf"
  delegation_chain: "windsurf:captain"

lupopedia.headers:
  actor_id: 101
  actor_name: "windsurf"
  delegation_chain: "windsurf:captain"
  lupopedia.version: "4.0.76"
  lupopedia.schema: "windsurf_rule"
  file_path_from_root: ".windsurf/rules/pdo-db-database-access-doctrine.md"
  last_modified_utc: "20260411"
  system_version: "4.0.76"
  source_path: "lupo-rules/root/pdo-db-database-access-doctrine.md"
  artifact_type: "rule"
  artifact_kind: "windsurf_doctrine"
  purpose: "Windsurf-specific rule derived from canonical root rule"

lupopedia.rules:
  comment: "Rule declaration and provenance block"
  declares:
    - rule_id: "DB003"
      rule_text: "All database access must use PDO_DB; prepared statements only; no raw SQL or legacy patterns"
      scope: "all_agents"
      category: "database"
      status: "active"
  imports: []
  overrides: []
  provenance:
    authored_by: "wolfie"
    authored_date: "20260411"
    last_reviewed_by: "windsurf"
    last_reviewed_date: "20260411"
    version: "1.0"
    status: "active"
lupopedia.footer:
  version: "4.0.76"
  last_verified: "20260411"
  last_verified_by: "windsurf"
  orchestrator: "windsurf"
  next_action:
    - "Keep in sync with canonical root rules"
---

# file: Rule — PDO_DB Database Access Doctrine — session: L-LUPO-WOLFIE — delegation: wolfie:root — web_path: http://www.lupopedia.com/rules/root/pdo-db-database-access-doctrine

# PDO_DB Database Access Doctrine (MANDATORY)

All database access MUST use the project's PDO_DB wrapper. Raw SQL, procedural DB calls, and legacy patterns are forbidden.

## 1. Use PDO_DB only

**Allowed:** `$db->fetch()`, `$db->fetchAll()`, `$db->execute()`, `$db->insert()`, `$db->update()`, `$db->delete()` (and any other methods defined on PDO_DB).

**Forbidden:** `$mydatabase->query()`, `$db->query()`, `$pdo->query()`, `$pdo->exec()`, `$pdo->prepare()` directly, `$rs->fetchRow(DB_FETCHMODE_ASSOC)`, `$rs->numrows()`.

## 2. Prepared statements with bound parameters

**Allowed:**

```php
$sql = "SELECT session_data FROM {$sessions_table} WHERE session_id = :session_id AND (expires_ymdhis IS NULL OR expires_ymdhis > :now)";
$row = $db->fetch($sql, ['session_id' => $sessionId, 'now' => $nowYmdHis]);
```

**Forbidden:** String concatenation or interpolation of values in SQL (e.g. `WHERE session_id = '$id'`, `WHERE id = $id`). Always use named placeholders (`:name`) and pass a params array.

## 3. Configured table prefix

**Never** hardcode `lupo_`. Always:

```php
$table_prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
$sessions_table = $table_prefix . 'sessions';
```

## 4. Session logic through Session class

All session reads/writes must go through `App\Auth\Session` using PDO_DB. Do **not** add new session queries in random files. If you find legacy code using `$mydatabase->query($sql)` or `filter_sql($id)` for sessions, refactor it into the Session class using PDO_DB.

## 5. No livehelp_* tables

All `livehelp_*` tables have been dropped and data migrated. Remove any remaining references; refactor code that expects them. Treat `livehelp_*` usage as a bug.

## 6. Legacy patterns are not valid examples

Do **not** copy: old Crafty Syntax DB code, procedural helpers, `$mydatabase` patterns, raw SQL strings, `DB_FETCHMODE_ASSOC`, `filter_sql()`. Use PDO_DB and Session only.

This rule is permanent and overrides legacy patterns.

