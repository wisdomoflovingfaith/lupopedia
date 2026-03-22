# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: "lupo-docs/doctrine/IMPORT_FROM_CRAFTY_TROUBLESHOOTING.md"
  file_hash: "1033f40536b03e5e38446592499b8499ec07cbc15643eb39d6d4d4ec8820cc71"
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
  file_path_from_root: "lupo-docs\doctrine\IMPORT_FROM_CRAFTY_TROUBLESHOOTING.md"
  file_hash: "c578e629a9ddff545d2a4eb204a0d6406e23191c940d42fc02e86144ef89e77e"
  file_path_from_root: "lupo-docs\doctrine\IMPORT_FROM_CRAFTY_TROUBLESHOOTING.md"
  file_hash: "56946b2ea2082ec573a6b4bc29c9cb4f960c8cd21639533a2a1b20ae73f7badb"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for IMPORT_FROM_CRAFTY_TROUBLESHOOTING.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "doctrine", "import_from_crafty_troubleshootingmd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
lupopedia.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
    deprecation_notes: ["Legacy Wolfie/FLIP block preserved; migrate tools to use lupopedia.headers"]
---

---
# FLIP Header (alias: Wolfie Header, CROP Header, FLIPPING Header)
wolfie.headers: explicit architecture with structured clarity for every file.
file_path_from_root: lupo-docs/doctrine/IMPORT_FROM_CRAFTY_TROUBLESHOOTING.md
file.last_modified_system_version: "4.0.16"
file.last_modified_utc: "20260218000000"
channel_id: 42   # ANUBIS adoption channel
tags: ["lost", "orphan", "doctrine"]
mood_rgb: "FFDAB9"
atoms:
  recovery_event: true
X-Lupo-Actor-ID: 2035
X-Lupo-Actor-Identity: "Lupopedia Audit Tool (Auto-Fixed)"
X-Lupo-File-Path: lupo-docs/doctrine/IMPORT_FROM_CRAFTY_TROUBLESHOOTING.md
---

# Import from Crafty Syntax — Troubleshooting

**Purpose:** When the install wizard runs `import_from_old_crafty_syntax.sql` and some Lupopedia tables are not populated, use this guide to find and fix the cause.

**When is the import run?** The import runs **only on upgrade** from Crafty Syntax 3.7.5, and **only when the database has at least one `livehelp_*` table**. On a **new install** the wizard does not run the import, so `lupo_crafty_syntax_*` tables are created empty. If upgrade was detected (e.g. via Crafty config file) but the database has no `livehelp_*` tables, the wizard skips the import and logs: *"Skipped: no legacy livehelp_* tables in database; nothing to import."* To get data into those tables you must either run a true upgrade (database that still has the 34 legacy Crafty tables with data) or manually load data.

**Runner behaviour:** The wizard splits the import file into statements by semicolon (`;`), but **only when the semicolon is outside single-quoted strings**. So `COMMENT = '...;...'` and other string literals that contain semicolons do not break the split; each full statement is executed as one piece.

**Canonical import file:** `lupo-database/migrations/import_from_old_crafty_syntax.sql`  
**Canonical Crafty baseline:** `lupo-database/migrations/old_crafty_syntax_3_7_5_start.sql`

---

## 1. Use the wizard run log

After running the install (upgrade path), the wizard shows a log for each SQL file. If the import fails on one or more statements:

- Look for log lines: **"SQL failed [import_from_old_crafty_syntax.sql] statement N"** and **"Failed statement preview: ..."**
- **N** is the 1-based index of the statement in the file (after comments are stripped and the file is split on `;`).
- The **preview** is the first ~80 characters of the failed statement so you can search for it in `import_from_old_crafty_syntax.sql` and see which migration block (e.g. livehelp_departments → lupo_departments) failed.

Check your server/PHP error log for the same message and full MySQL error text.

---

## 2. Prerequisites for a successful import

### 2.1 Source tables must exist with exact names

The import reads from **34 legacy Crafty tables** with the exact prefix **`livehelp_`**. Names must match `old_crafty_syntax_3_7_5_start.sql`:

- `livehelp_autoinvite`, `livehelp_channels`, `livehelp_config`, `livehelp_departments`, `livehelp_emailque`, `livehelp_emails`, `livehelp_identity_daily`, `livehelp_identity_monthly`, `livehelp_keywords_daily`, `livehelp_keywords_monthly`, `livehelp_layerinvites`, `livehelp_leads`, `livehelp_leavemessage`, `livehelp_messages`, `livehelp_modules`, `livehelp_modules_dep`, `livehelp_operator_channels`, `livehelp_operator_departments`, `livehelp_operator_history`, `livehelp_qa`, `livehelp_questions`, `livehelp_quick`, `livehelp_referers_daily`, `livehelp_referers_monthly`, `livehelp_sessions`, `livehelp_smilies`, `livehelp_transcripts`, `livehelp_users`, `livehelp_visit_track`, `livehelp_visits_daily`, `livehelp_visits_monthly`, `livehelp_paths_firsts`, `livehelp_paths_monthly`, `livehelp_websites`

If you restored Crafty with a different prefix or different names, the import will fail with "table doesn't exist" for those statements. Restore or rename so that the 34 tables exist with the names above.

### 2.2 MySQL / MariaDB version

The import uses **`JSON_OBJECT()`**. That requires:

- **MySQL 5.7.8+**, or  
- **MariaDB 10.2.3+**

Older versions will fail on every statement that uses `JSON_OBJECT` (e.g. livehelp_config → lupo_modules, livehelp_departments → lupo_department_metadata, referers, visits, etc.).

### 2.3 Rows in key source tables

- **livehelp_config:** Must have at least one row. The import updates `lupo_modules.config_json` from that row. If livehelp_config is empty, `config_json` will be set to NULL.
- Other INSERT...SELECT blocks only insert when the source table has rows; empty source tables simply produce 0 inserted rows (no error).

---

## 3. Execution order

The wizard runs:

1. `install_new_lupopedia.sql` — creates all `lupo_*` tables  
2. `seed_lupopedia.sql` — inserts system data (e.g. lupo_modules row with module_id = 1)  
3. `import_from_old_crafty_syntax.sql` — copies from `livehelp_*` into `lupo_*`  
4. `drop_old_crafty_syntax_tables.sql` — drops the 34 `livehelp_*` tables  

So when the import runs, all Lupopedia tables already exist and seed has run. Failures in the import are due to missing/wrong source tables, wrong DB version, or schema/column mismatch.

---

## 4. Table prefix (lupo_ vs custom)

If you chose a custom table prefix in the wizard (e.g. `myprefix_`), the wizard replaces **`lupo_`** with that prefix only in the import file when running it. **Source** table names (`livehelp_*`) are never changed. So:

- Target tables become e.g. `myprefix_departments`, `myprefix_auth_users`.
- Source tables must still be named `livehelp_departments`, `livehelp_users`, etc.

---

## 5. What is not migrated

Some legacy tables are only altered/deprecated and not copied into Lupopedia (e.g. livehelp_sessions, livehelp_smilies, livehelp_messages, livehelp_channels, livehelp_operator_channels). See comments in `import_from_old_crafty_syntax.sql` for each block. Empty Lupopedia tables after import for those targets are expected.
