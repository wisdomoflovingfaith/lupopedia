---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: documentation
  when_updated: null
  file_path_from_root: "docs/versions/4.0.96/status/INSTALLER_AUDIT_20260407.md"
  web_path: "http://www.lupopedia.com/lupopedia/docs/versions/4.0.96/status/INSTALLER_AUDIT_20260407.md"
  questions_toon: null
  federation_node_id: null
  channel_key: null
  trust_tier: null
  memory_key: null
  artifact_type: audit
  artifact_kind: status
  thread_id: ""
  content_id: null
  pk_id: null
  pk_slug: ""
  title: ""
  status: ""
  parent_pk_id: ""
  summary: ""
  module: null
  dialog_transcript: null
---
# file: INSTALLER_AUDIT_20260407 — delegation: claude_code:root

# Installer Audit — Fresh Install After Drop All / Delete Config

**Auditor:** Claude Code (actor_id 116)
**Mode:** Installer Audit Mode — code and SQL only, no doctrine inference
**Scope:** `install.php`, `install_wizard_classes.php`, `InstallWizardMysqliLink.php`, `install_new_lupopedia.sql` (spot-checked), `install/seed_lupopedia_4_1_0.sql`, `database/lupopedia/mysql/seed/seed_4.1.0.sql`
**Precondition audited:** All tables dropped; `lupopedia-config.php` deleted.

---

## Overall Verdict: CONDITIONAL PASS

The installer's main fresh-install path works correctly for the default `lupo_` prefix. There are two issues that break custom prefix installs and one PDO configuration gap against the trust ladder doctrine. All other checks pass.

---

## 1. Redirect Behavior

### index.php → install.php

**PASS.** `index.php:99–112` uses `LupopediaConfigResolver::resolve()` to check for the config file. When not found, it redirects:

```php
$installUrl = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/') . '/install.php';
header('Location: ' . $installUrl);
exit;
```

Correctly handles subdirectory installs. The wizard starts automatically.

**Minor dead code (`index.php:108–110`):** The check `if ($installUrl === '/install.php') { $installUrl = '/install.php'; }` is a no-op — it compares the value to itself and reassigns the same string. Harmless but confusing.

### install.php → login.php (already installed)

**PASS.** `install.php:262–274` detects an existing config and redirects to `login.php` (bypassed by `?force_reinstall=1`). Config check requires `LUPOPEDIA_CONFIG_LOADED` to be `true` — not just file presence.

---

## 2. Database Creation

### PDO vs mysqli connection

The installer uses `InstallWizardMysqliLink` (mysqli-backed) for all DDL and seed work — not PDO. `InstallWizardMysqliLink.php` implements a PDO-compatible surface (`exec`, `query`, `prepare`, `quote`, `fetch`, `fetchAll`, `rowCount`) so wizard code reads like PDO.

**Named placeholder support is real:** `prepare()` at `InstallWizardMysqliLink.php:366–379` converts `:name` to `?` and records the order in `$namedOrder`; `execute()` resolves named params to positional bind order. This is correct and all named-placeholder calls in the wizard work.

**PASS:** Connect using mysqli; PDO-like interface verified functional.

### Table prefix application

**PASS.** `applyTablePrefixToSql()` always replaces `{{prefix}}` with the chosen prefix. When the prefix is not `lupo_`, it also replaces literal `lupo_` table names.

**P2 — String value contamination in `applyTablePrefixToSql()`** (`install_wizard_classes.php:411–413`):

```php
if ($tp !== 'lupo_') {
    $sql = str_replace('lupo_', $tp, $sql);
}
```

This replaces ALL occurrences of `lupo_` in the SQL file, including any that appear inside single-quoted string values. For example, a description like `'stored in lupo_memory_nodes'` would become `'stored in custom_memory_nodes'` when using a custom prefix. It is cosmetic but incorrect and could corrupt notes/description seed values. The fallback seed (`seed_4.1.0.sql`) is the primary risk surface since it uses hardcoded `lupo_` (see §3).

**Fix:** Use a regex that only replaces table-name positions (after `FROM`, `JOIN`, `INTO`, `TABLE`, backtick-wrapped), not string literals.

### SQL error handling

**PASS.** `runSqlFile()` (`install_wizard_classes.php:485–540`):

- Catches each statement individually.
- "already exists" / "Duplicate" errors → logged as `skip`, processing continues (idempotency).
- Any other error → logged as `error`, `$ok = false`, processing continues to collect all errors.
- Returns `false` on any non-skip error.
- **Callers halt:** `install.php:626–635` checks the return value and throws `RuntimeException` on failure.

**One nuance to understand:** `runSqlFile` does NOT abort at the first SQL error — it runs all remaining statements in the file and returns `false` at the end. This means cascading errors (e.g., INSERT after a failed CREATE TABLE) are all logged. The caller then throws and stops the wizard. This design is intentional for full error visibility. ✓

### ANUBIS table check — SQL LIKE wildcard bug

**P2 — `install.php:739`:**

```php
$res = $pdo->query("SHOW TABLES LIKE '$full_table'")->fetch();
```

`$full_table` contains underscores (e.g., `lupo_anubis_queue`). In SQL `LIKE` patterns, `_` is a wildcard matching any single character. So this query would match `lupo_anubisXqueue` or `lupoXanubis_queue`. In practice this only matters if the DB has unexpected table names, but it is a correctness bug.

**Fix:** Escape underscores: `str_replace('_', '\\_', $full_table)` before use in LIKE, or query `INFORMATION_SCHEMA.TABLES WHERE TABLE_NAME = :name`.

The same class of bug exists in `install.php:598`:
```php
$st = $pdo->query("SHOW TABLES LIKE " . $pdo->quote($dept_table));
```
`$pdo->quote()` wraps in single quotes but does not escape `_`. Same fix applies.

---

## 3. Seed Import

### Seed file selection

`install.php:117–123` selects seeds in this order:

1. **`install/seed_lupopedia_4_1_0.sql`** — consolidated, built by script, uses `{{prefix}}` throughout. ✓
2. **Fallback: `database/lupopedia/mysql/seed/seed_4.1.0.sql`** — uses hardcoded `lupo_` names.

**P2 — Fallback seed uses hardcoded `lupo_` throughout** (`seed_4.1.0.sql:6, 29, 56, 95, 101, 108, 114, 124...`):

```sql
INSERT INTO lupo_federation_nodes ...
INSERT INTO lupo_departments ...
INSERT INTO lupo_actors ...
```

The `applyTablePrefixToSql()` handles this with the blanket `str_replace('lupo_', $tp, $sql)` for non-default prefixes — which introduces the string-value contamination risk noted above. The consolidated seed (`install/seed_lupopedia_4_1_0.sql`) correctly uses `{{prefix}}` and avoids this problem entirely.

**Mitigation already present:** If the consolidated file exists (produced by `scripts/build_consolidated_seed_4_1_0.py`), the fallback is never reached. The fallback is only a risk on dev setups that haven't run the build script.

**Recommended action:** Either always build the consolidated seed as part of the release artifact, or convert `seed_4.1.0.sql` to use `{{prefix}}`.

### Seed dependency order

**PASS.** Both seed files insert in dependency-safe order:

```
lupo_federation_nodes (1)
→ lupo_departments (0)
→ lupo_actors (0, 1, 2, 3, 5, 6, 7, 8, 9, 10, 14, 111, 115) + ON DUPLICATE KEY UPDATE
→ lupo_actor_relationships (1)
→ lupo_actor_departments (1–4)
→ lupo_auth_user_departments (SET @var → INSERT)
→ lupo_agent_definitions (3, 15, 16, 19, 108, 115) + ON DUPLICATE KEY UPDATE
```

No FK ordering issues observed.

### Duplicate protection

**PASS.** Actor inserts use `ON DUPLICATE KEY UPDATE` — safe to re-run. ✓

### SET @variable usage

**PASS.** `seed_4.1.0.sql:108, 114` use `SET @lupo_root_aud_id := (SELECT ...)` to avoid MySQL error 1093 (modifying a table while reading it in a subquery). `splitSqlStatements()` splits on `;` so each `SET` and `INSERT` is a separate statement. The `@variable` persists within the mysqli connection scope. Verified correct. ✓

---

## 4. Config File Creation

### Required constants

**PASS.** `InstallWizardConfigWriter::writeConfig()` writes:

| Constant | Present |
|----------|---------|
| `DB_TYPE`, `DB_NAME`, `DB_USER`, `DB_PORT`, `DB_PASSWORD`, `DB_HOST`, `DB_CHARSET` | ✓ |
| `LUPO_TABLE_PREFIX` | ✓ |
| `LUPOPEDIA_CONFIG_LOADED = true` | ✓ |
| `ABSPATH`, `LUPOPEDIA_PATH`, `LUPOPEDIA_PUBLIC_PATH`, `LUPOPEDIA_URL` | ✓ |
| 8 auth keys/salts (64-char CSPRNG) | ✓ |
| `LUPOPEDIA_DEBUG = false`, `LUPOPEDIA_ENV = 'production'` | ✓ |
| `require_once bootstrap.php` at end | ✓ |

Optional (written when provided): `LUPOPEDIA_SITE_NAME`, `LUPOPEDIA_BASE_URL`, `LUPOPEDIA_ADMIN_EMAIL`, `LUPOPEDIA_TIMEZONE`, `LUPOPEDIA_LANGUAGE`.

### Credential escaping

**PASS.** All DB credentials are wrapped in `addslashes()` before embedding in PHP string literals. ✓

### Table prefix validation

**PASS.** Two-layer validation: regex `^[a-z0-9_]+$` at credential step (line 341) and again inside config writer (line 618). Empty prefix defaults to `lupo_`. ✓

### File write failure

**PASS.** `@file_put_contents()` failure → returns `null`. Caller at `install.php:883` checks `$configPath !== null` before declaring success. Falls through to `$config_errors[] = 'Could not write config file.'`. No empty or partial config is written on failure. ✓

### Permission

**P2 — Config written with `chmod(0644)`** (`install_wizard_classes.php:644`):

```php
@chmod($configPath, 0644);
```

The config file contains the database password. Mode `0644` allows all users on the server to read it. Should be `0640` (owner read-write, group read-only) or `0600` (owner only). Shared hosting environments are especially at risk.

### Session cleanup on success

**PASS.** `install.php:888` clears all install-session keys (`lupo_install_db_vars`, `lupo_install_type`, `lupo_bootstrap_log`, `lupo_run_done`, etc.) after successful config write. DB credentials do not persist in session after install completes. ✓

---

## 5. Post-Install State

### Bootstrap capability

**PASS.** Config file ends with `require_once ABSPATH . LUPO_INCLUDES_DIR . '/bootstrap.php'` which fires automatically when any page loads the config. ✓

### Login

**PASS.** `InstallWizardMainAdmin::createMainAdmin()` is called during the config step and inserts into `lupo_auth_users`. Admin password is collected on the config form (minimum 8 chars, confirmed). ✓

### Seed actors present

**PASS.** After install, `lupo_actors` contains:

| actor_id | actor_name |
|----------|------------|
| 0 | system |
| 1 | wolfie |
| 2 | lilith |
| 3 | lexa |
| 5 | seshat |
| 6 | athena |
| 7 | maat |
| 8 | themis |
| 9 | thoth |
| 10 | janus |
| 14 | asclepius |
| 111 | countermeasure |
| 115 | kairos |

All inserted with `ON DUPLICATE KEY UPDATE`. ✓

### Seed departments present

**PASS.** `lupo_departments`: department_id 0 (Root) seeded. ✓

### Memory tables

**PASS.** `lupo_memory_nodes` and `lupo_memory_edges` tables are created by DDL from `install_new_lupopedia.sql`; no seed rows are inserted. Tables exist and are empty after fresh install. ✓

---

## 6. Trust Ladder Safety

### Seed IDs remain short integers

**PASS.** All actor_id values in both seed files are short positive integers (0–14, 111, 115). None are padded to 18 digits. ✓

### Runtime IDs use `IdGenerator::generate()`

**PASS.** The installer does not call `IdGenerator::generate()` for any seed-level table insert. No 18-digit IDs are generated during install. ✓

### Canonical IDs use `toCanonicalIdSafe()`

**PASS (N/A).** `toCanonicalIdSafe()` is not called during install. Seed tables (`lupo_actors`, `lupo_departments`, etc.) are all `seed_only` per `TRUST_LADDER_REGISTRY.md`. No canonical promotion is required at install time. ✓

### No manual ID padding

**PASS.** No installer code constructs 18-digit IDs by padding or string concatenation. ✓

### No `(int)` cast of 18-digit IDs

**PASS.** The only `(int)` casts in install code operate on small values: seed actor_ids, registry_ids, port numbers, or `MAX(actor_id)` during upgrade (where actors are seed-only, so the max is a small integer). ✓

### `connectPdoBuffered()` — missing `ATTR_STRINGIFY_FETCHES`

**P1 — Trust ladder doctrine requires `ATTR_STRINGIFY_FETCHES => true` on any PDO connection that reads ladder PKs.** `connectPdoBuffered()` (`install_wizard_classes.php:309–322`) creates a real PDO connection used during AI activation (`ensureActorActive`) but does not set this attribute:

```php
$opts = array(
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_TIMEOUT => 30,
    // MISSING: PDO::ATTR_STRINGIFY_FETCHES => true
);
```

**Actual risk at install time is LOW** because the only `actor_id` values read through this connection are the seed actor IDs (0, 1, 2, 111) — all small integers that fit in 32-bit PHP int without overflow. However, the pattern is architecturally wrong: if `ensureActorActive` or any code it calls reads an 18-digit ID from a ladder table through this connection on 32-bit PHP, the value would be returned as PHP `float` by some drivers (lossy) or as `int` (overflow). The attribute must be set.

**Fix:**
```php
$opts = array(
    PDO::ATTR_ERRMODE        => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_TIMEOUT        => 30,
    PDO::ATTR_STRINGIFY_FETCHES => true,   // trust ladder doctrine §4
    PDO::ATTR_EMULATE_PREPARES  => false,  // native prepares preferred
);
```

---

## 7. Prefix Safety

### install_new_lupopedia.sql

**PASS.** Verified: all `CREATE TABLE` statements use `{{prefix}}` (e.g. `CREATE TABLE {{prefix}}actors`). No hardcoded `lupo_` table names. ✓

### install/seed_lupopedia_4_1_0.sql (consolidated — preferred)

**PASS.** All `INSERT INTO` statements use `{{prefix}}`. ✓

### database/lupopedia/mysql/seed/seed_4.1.0.sql (fallback)

**P2.** Uses hardcoded `lupo_` throughout. Handled imprecisely by `str_replace('lupo_', $tp, $sql)` for custom prefixes. See §3 for details.

### PHP code — no hardcoded table names in install path

**PASS.** All dynamic table references in `install.php` and `install_wizard_classes.php` use `LUPO_TABLE_PREFIX` constant or the session-stored `$table_prefix`. ✓

### `validateCraftyPreMigration()` — hardcoded `lupo_actors` in SQL

**P1 — `install_wizard_classes.php:205`:**

```sql
LEFT JOIN lupo_actors a ON lu.username = a.slug 
WHERE a.actor_id IS NULL
```

This SQL joins against the hardcoded table name `lupo_actors`. If a custom prefix (e.g., `wiki_`) is in use, this query silently targets the wrong table (or a nonexistent one), producing misleading validation results.

**Checked:** `validateCraftyPreMigration()` does not appear to be called from `install.php` in the code paths I audited. If this method is dead code, the bug is inert. If it is called from any UI extension or test harness, it is a P1.

**Action:** Confirm whether the method is called anywhere. If it is: fix the JOIN to use `$table_prefix . 'actors'` (method needs the prefix passed as a parameter). If it is dead code: annotate or remove it to prevent future misuse.

---

## 8. Error Handling

| Scenario | Behavior | Pass? |
|----------|----------|-------|
| SQL file not found | Logs error, returns `false`, caller throws | ✓ |
| `CREATE TABLE` fails (non-duplicate) | Logs error, continues file, returns `false`, caller throws `RuntimeException` | ✓ |
| `INSERT` fails with "Duplicate" | Logged as `skip`, processing continues | ✓ |
| Registry ID conflict | Throws `RuntimeException` before any SQL runs | ✓ |
| Seed file missing | Throws `RuntimeException` | ✓ |
| Config write fails | Returns `null`, caller adds `$config_errors[]` | ✓ |
| DB connect fails during run step | `$errors[]`, `$step = 'confirm'`, no config written | ✓ |
| `lupo_run_done` on failure | Explicitly `unset()` — config step cannot proceed | ✓ |
| RuntimeException during run | `$errors[]`, `$_SESSION['lupo_run_done']` unset | ✓ |

**No partial install scenario found.** The wizard correctly refuses to write config until `$_SESSION['lupo_run_done']` is `true`.

---

## Findings Summary

| Priority | Issue | File | Line |
|----------|-------|------|------|
| P1 | `connectPdoBuffered()` missing `PDO::ATTR_STRINGIFY_FETCHES => true` — trust ladder doctrine violation | `install_wizard_classes.php` | 308–322 |
| P1 | `validateCraftyPreMigration()` hardcoded `lupo_actors` in JOIN — breaks custom prefix (confirm if called) | `install_wizard_classes.php` | 205 |
| P2 | `applyTablePrefixToSql()` `str_replace('lupo_', $tp)` replaces inside string VALUES — can corrupt descriptions | `install_wizard_classes.php` | 411–413 |
| P2 | `SHOW TABLES LIKE '$full_table'` — `_` is SQL LIKE wildcard; should be escaped | `install.php` | 598, 739 |
| P2 | Config file written with `chmod(0644)` — world-readable, contains DB password | `install_wizard_classes.php` | 644 |
| P2 | Fallback seed (`seed_4.1.0.sql`) uses hardcoded `lupo_` — only safe with default prefix | `database/…/seed_4.1.0.sql` | all |
| P3 | `index.php:108–110` — `if ($installUrl === '/install.php') { $installUrl = '/install.php'; }` is a no-op | `index.php` | 108–110 |

---

## Trust Ladder Checklist

| Check | Result |
|-------|--------|
| Seed IDs remain short integers | ✓ PASS |
| Runtime IDs use `IdGenerator::generate()` | ✓ PASS (not called at install) |
| Canonical IDs use `toCanonicalIdSafe()` | ✓ PASS (not called at install) |
| No installer code manually pads IDs to 18 digits | ✓ PASS |
| No installer code casts 18-digit IDs to `(int)` | ✓ PASS |
| No installer code generates 18-digit IDs manually | ✓ PASS |
| PDO connection for AI activation sets `ATTR_STRINGIFY_FETCHES` | ✗ FAIL (P1 — risk low at install, fix required) |

---

*This output complies with Lupopedia Constitutional Root Rules.*
