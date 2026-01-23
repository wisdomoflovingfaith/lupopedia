---
file.name: "crafty_import_validation_4.2.1.md"
file.last_modified_system_version: 4.2.0
file.last_modified_utc: 20260120160000
file.utc_day: 20260120
GOV-AD-PROHIBIT-001: true
UTC_TIMEKEEPER__CHANNEL_ID: "dev"
---

# Crafty Syntax 3.7.5 → Lupopedia Import Validation — 4.2.1

**Purpose:** Mandatory upgrade-import testing during the 4.2.1 hotfix window.  
**Scope:** 4.2.1 hotfix window (2026-01-21 to 2026-02-03).  
**Blocking:** 4.3.0 cannot be scheduled or executed until these tests pass.

---

## 1. Required Environments

Tests MUST pass on at least **two** real installations:

| Environment       | Description                          | Notes                                |
|------------------|--------------------------------------|--------------------------------------|
| **Shared hosting** | Production-like shared MySQL/PHP     | e.g. cPanel, Plesk, typical LAMP     |
| **Local**          | Local dev (ServBay, MAMP, Docker, etc.) | PHP 7.4+, MySQL 5.7+             |

---

## 2. Step-by-Step Import Procedure

### 2.1 Pre-import

1. **Source:** Crafty Syntax 3.7.5 database dump (all `livehelp_*` and related tables).
2. **Target:** Empty or clean Lupopedia 4.2.0 schema (post-consolidation: 173 tables, ceiling 180).
3. **Script:** `database/migrations/craftysyntax_to_lupopedia_mysql.sql` (with 4.1.14 doctrine corrections).
4. **Backup:** Full backup of target DB before import.

### 2.2 Execution

1. Create Lupopedia schema (or ensure 4.2.0 baseline).
2. Load Crafty Syntax 3.7.5 data into a staging DB or temporary `livehelp_*` tables in the target.
3. Run `craftysyntax_to_lupopedia_mysql.sql` in order (no skipping sections).
4. Confirm no SQL errors; note any warnings.

### 2.3 Post-import

1. Run validation queries (Schema, Data, Timestamps, TOON) — see §3.
2. Regenerate TOON: `python database/generate_toon_files.py`.
3. Log results to the registry — see §5.

---

## 3. Validation Criteria

### 3.1 Schema

- Table count ≤ 180; expected 173 after consolidation (or as per current baseline).
- No `livehelp_*` tables remaining after migration (legacy tables dropped).
- All `lupo_*` target tables present and non-empty where source had rows.
- Character set `utf8mb4` / `utf8mb4_unicode_ci` on migrated tables.
- No foreign keys, no triggers, no stored procedures (doctrine).

### 3.2 Data

- Row counts: `lupo_crafty_syntax_*`, `lupo_crm_*`, `lupo_actor_departments`, `lupo_dialog_*`, etc., match or conservatively exceed source where mapping is 1:1 or N:1.
- No unexplained NULLs in required columns.
- `livehelp_emailque` documented as not migrated in this script; no incorrect INSERT.

### 3.3 Timestamps

- All `created_ymdhis`, `updated_ymdhis`, `deleted_ymdhis` in `YYYYMMDDHHMMSS` (BIGINT).
- No `0` or `NOW()`; use `DATE_FORMAT(UTC_TIMESTAMP(), '%Y%m%d%H%i%S')` or equivalent.
- Spot-check: at least 5 tables with timestamp columns; values are 14-digit UTC.

### 3.4 TOON

- `database/generate_toon_files.py` runs without error.
- `.toon` (and `.txt`) count matches current table count (e.g. 173).
- Spot-check: `lupo_actors`, `lupo_dialog_messages`, `unified_dialog_messages` (or current unified tables) exist in `database/toon_data/`.

---

## 4. Pass/Fail Rules

| Criterion     | Pass                                            | Fail                                                                 |
|---------------|--------------------------------------------------|----------------------------------------------------------------------|
| **Schema**    | Table count ≤180; no legacy; charset correct     | Over 180 tables; `livehelp_*` left behind; wrong charset             |
| **Data**      | Row counts and NOT NULL constraints satisfied    | Missing rows; invalid NULLs; wrong mappings                          |
| **Timestamps**| All UTC `YYYYMMDDHHMMSS`; no 0 or `NOW()`        | Any 0, `NOW()`, or non-14-digit in timestamp columns                 |
| **TOON**      | Regeneration succeeds; count matches             | Script errors; count mismatch; missing expected .toon                 |
| **Environments** | Both shared hosting and local pass             | Fewer than 2 environments pass                                       |

**Overall:** **PASS** only if both environments pass all of Schema, Data, Timestamps, and TOON.  
**Overall:** **FAIL** if any environment fails any criterion.

---

## 5. Registry Logging Requirements

For each run, record:

- **Date (UTC):** `YYYY-MM-DD`
- **Environment:** `shared_hosting` | `local`
- **Crafty Syntax version:** `3.7.5`
- **Lupopedia version:** `4.2.0` (or 4.2.1 when applied)
- **Script:** `craftysyntax_to_lupopedia_mysql.sql` (with 4.1.14 doctrine corrections)
- **Schema (pass/fail):** boolean + notes
- **Data (pass/fail):** boolean + notes
- **Timestamps (pass/fail):** boolean + notes
- **TOON (pass/fail):** boolean + notes
- **Overall (pass/fail):** boolean
- **Tester / actor:** e.g. CURSOR, CAPTAIN_WOLFIE, system identifier

Recommended: `database/hotfix_registry_4.2.1.json` or `docs/hotfixes/4.2.1/crafty_import_runs.json` with one object per run.

---

## 6. Closing Condition for 4.2.1

The 4.2.1 hotfix window remains **OPEN** until:

> Crafty Syntax 3.7.5 → Lupopedia import tests pass on at least two real installations (shared hosting + local environment).

**4.3.0 MUST NOT** be scheduled or executed until these tests pass.

- Update `crafty_import_testing_status` to `"complete"` in `current_sync_state` (changelog_dialog-side.md) when both environments pass.
- Update `database/hotfix_registry_4.2.1.json` entries to `"status": "pass"` for each environment.
- Only then may the version bump to 4.3.0 or hotfix-window closure be considered.

---

## 7. References

- `database/migrations/craftysyntax_to_lupopedia_mysql.sql`
- `docs/migrations/4.1.14.md` (doctrine corrections)
- `docs/versioning/4.2.1_hotfix_window.md`
- `CHANGELOG.md` § 4.2.1
- `database/hotfix_registry_4.2.1.json`
