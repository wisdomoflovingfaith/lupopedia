# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: ".\docs\audits\VERSIONING_DOCTRINE_ALIGNMENT_SUMMARY.md"
  file_hash: "72edbdc6d558231a9ec70d2c64faeac9313196b83cfaea50e0dd9bf0f930a60c"
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
    - { to: "docs/doctrine/", type: "references", weight: 1.0 }

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
  file_path_from_root: "docs\audits\VERSIONING_DOCTRINE_ALIGNMENT_SUMMARY.md"
  file_hash: "e3a5421e38a4fa4bec50f099164016167aa018da86edfad13429812fe3e256e6"
  file_path_from_root: "docs\audits\VERSIONING_DOCTRINE_ALIGNMENT_SUMMARY.md"
  file_hash: "a6666d8f87bf22e399fcb37c71219363dde826dec7032c42cd91e62fd8a13a50"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Versioning Doctrine Alignment Summary"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "audits", "versioning_doctrine_alignment_summarymd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
lupopedia.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

# Versioning Doctrine Alignment Summary

**Date:** 2026-02-12  
**Scope:** Version numbers, file renames, internal references, and migration layout aligned to the corrected versioning doctrine.

---

## 1. Canonical Version Rules (Applied)

| Rule | Application |
|------|-------------|
| **Next public release is 4.1.0** | Required tables and release docs use 4.1.0; no 4.2.x, 4.3.x, or 4.1.1+ as release target. |
| **Internal schema-sync / dev markers use 3.x.x** | Schema-sync version 3.0.46 → 3.0.46; filenames and content updated. |
| **Patch sequence is 3.0.0, 4.0.1, … 3.0.999** | No change to patch numbering; 3.0.0 remains frozen until 4.1.0. |
| **No Lupopedia → Lupopedia upgrade path** | Only upgrade path: Crafty Syntax 3.7.5 → Lupopedia 4.0.x. No upgrade logic between 4.0.x versions. |

---

## 2. Files Renamed

| Old path | New path |
|----------|----------|
| docs/REQUIRED_TABLES_4.2.1.md | docs/REQUIRED_TABLES_4.1.0.md |
| docs/channels/schema/migrations/analysis/SCHEMA_SYNC_3_0_46_SUMMARY.md | docs/channels/schema/migrations/analysis/SCHEMA_SYNC_3_0_46_SUMMARY.md |
| docs/channels/schema/migrations/3.0.46.md | docs/channels/schema/migrations/3.0.46.md |
| database/migrations_legacy/schema_sync_3_0_46_missing_tables.sql | database/migrations_legacy/schema_sync_3_0_46_missing_tables.sql |
| database/hotfix_registry_4.2.1.json | database/hotfix_registry_4.1.0.json |
| dialogs/session_2026_01_16_version_3_0_46.md | dialogs/session_2026_01_16_version_3_0_46.md |
| dialogs/changelog_dialog_schema_sync_3_0_46.md | dialogs/changelog_dialog_schema_sync_3_0_46.md |

**Note:** Files such as docs/channels/overview/versioning/4.2.1_hotfix_window.md, docs/channels/developer/testing/crafty_import_validation_4.2.1.md, 4.2.1_hotfix_window.sh, and database/migrations_legacy/4.2.1_add_reverse_shaka_sync_table.sql were not renamed in this pass; they may be renamed to 4.1.0 or left as historical artifacts per project policy.

---

## 3. Files Updated (Version References and Paths)

| File | Changes |
|------|---------|
| docs/REQUIRED_TABLES_4.1.0.md | Version 3.0.0 → 4.1.0 in heading and metadata; purpose “4.1.0 release”. |
| docs/audits/DEPARTMENTS_GROUPS_ROLES_DOCTRINE_UPDATE_SUMMARY.md | REQUIRED_TABLES_4.2.1 → 4.1.0; SCHEMA_SYNC_3_0_46 → SCHEMA_SYNC_3_0_46; 3.0.46.md → 3.0.46.md. |
| docs/audits/DEPARTMENTS_GROUPS_ROLES_PHP_IMPLEMENTATION_PLAN.md | REQUIRED_TABLES_4.1.0; SCHEMA_SYNC_3_0_46; 3.0.46.md. |
| docs/PATHS_FIRSTS_VS_ANALYTICS_PATHS_ANALYSIS.md | REQUIRED_TABLES_4.1.0. |
| CHANGELOG.md | REQUIRED_TABLES_4.1.0. |
| docs/audits/DEPARTMENTS_GROUPS_ROLES_EXECUTION_SUMMARY.md | REQUIRED_TABLES_4.1.0. |
| docs/audits/DEPARTMENTS_GROUPS_ROLES_PRE_EXECUTION_CHECKLIST.md | REQUIRED_TABLES_4.1.0. |
| docs/audits/DEPARTMENTS_GROUPS_ROLES_IMPLEMENTATION_PLAN.md | REQUIRED_TABLES_4.1.0. |
| docs/OPERATOR_TABLES_REMOVAL_AND_ROLE_VERIFICATION_REPORT.md | REQUIRED_TABLES_4.1.0. |
| docs/PATHS_FIRSTS_REMOVAL_REPORT.md | REQUIRED_TABLES_4.1.0. |
| docs/DIALOG_TABLE_REMOVAL_REPORT.md | REQUIRED_TABLES_4.1.0. |
| docs/DIALOG_MESSAGES_VS_ANALYSIS.md | REQUIRED_TABLES_4.1.0. |
| docs/ACTOR_CHANNEL_ROLES_VS_CHANNEL_ROLES_ANALYSIS.md | REQUIRED_TABLES_4.1.0. |
| docs/VERSION_DOCTRINE_APPLICATION_REPORT.md | REQUIRED_TABLES_4.1.0; 4.2.1 → 4.1.0. |
| docs/SESSIONS_VS_sessions_INVESTIGATION.md | REQUIRED_TABLES_4.1.0. |
| docs/channels/schema/migrations/analysis/SCHEMA_SYNC_3_0_46_SUMMARY.md | Migration path: database/migrations_legacy/schema_sync_3_0_46_missing_tables.sql. |
| docs/channels/schema/migrations/3.0.46.md | Migration path 3_0_46; type “Internal development snapshot (schema-sync)”. |
| docs/channels/overview/MONDAY_START_OF_DAY.md | schema_sync_3_0_46_missing_tables.sql; database/migrations_legacy. |
| docs/channels/overview/VERSION_3_0_60_PLAN.md | session_2026_01_16_version_3_0_46.md. |
| docs/channels/overview/MONDAY_RESUME_CONTEXT.md | session_2026_01_16_version_3_0_46.md. |
| docs/channels/overview/ascent/02_DIALOG_MIGRATION.md | session_2026_01_16_version_3_0_46.md. |
| database/hotfix_registry_4.1.0.json | version and lupopedia_version set to 4.1.0. |

---

## 4. Migrations Layout

| Action | Detail |
|--------|--------|
| **Created** | database/migrations/future_features_lupopedia.sql (placeholder for future tables). |
| **Created** | database/migrations/legacy/ directory. |
| **Moved to database/migrations/legacy/** | All .sql in database/migrations/ except: import_from_old_crafty_syntax.sql, install_new_lupopedia.sql, seed_lupopedia.sql, drop_old_crafty_syntax_tables.sql, future_features_lupopedia.sql. |
| **Remaining in database/migrations/** | import_from_old_crafty_syntax.sql, install_new_lupopedia.sql, seed_lupopedia.sql, drop_old_crafty_syntax_tables.sql, future_features_lupopedia.sql, old_crafty_syntax_3_7_5_start.sql, README.md. One-time migrations and audit reports are in database/migrations_legacy/. |

---

## 5. Version Numbers Changed

| Context | Old | New |
|---------|-----|-----|
| Required tables doc (title/version) | 3.0.0 | 4.1.0 |
| Required tables filename | 4.2.1 | 4.1.0 |
| Schema-sync summary filename | 3_0_46 | 3_0_46 |
| Schema-sync migration filename | 3_0_46 | 3_0_46 |
| Migration notes filename | 3.0.46 | 3.0.46 |
| Hotfix registry filename | 4.2.1 | 4.1.0 |
| Hotfix registry content (version, lupopedia_version) | 4.2.1 | 4.1.0 |
| Session/changelog dialog filenames | 3_0_46 | 3_0_46 |
| References to schema_sync migration path | migrations/ or migrations_legacy/ 3_0_46 | migrations_legacy/schema_sync_3_0_46 |

---

## 6. Confirmations

### 6.1 Doctrine aligned

- **Next public release:** 4.1.0 (required tables and hotfix registry).
- **Internal schema-sync:** 3.0.46 (summary, migration notes, migration file, dialog filenames).
- **Patch:** 4.0.x unchanged; VERSION_DOCTRINE.md already states 4.1.0 as only future version and forbids 4.2.0, 4.3.0.
- **Upgrade path:** No Lupopedia→Lupopedia upgrade logic added; only Crafty 3.7.5 → Lupopedia 4.0.x.

### 6.2 No invalid version numbers in updated files

- No remaining references to 4.2.x or 4.3.x in the files that were updated.
- Schema-sync references use 3.0.46 (not 3.0.46) in renamed and updated docs.
- REQUIRED_TABLES and hotfix registry use 4.1.0.

### 6.3 Cursor workflow understanding

- **Step 1 — Increment patch:** 3.0.0 → 4.0.1 → 3.0.2, etc.; never 3.0.0 → 4.1.0 for patch.
- **Step 2 — Required tables audit:** Compare required tables vs TOONs; move future tables to database/migrations/future_features_lupopedia.sql (or database/future_features_lupopedia.sql per project).
- **Step 3 — Migration cleanup:** Only import_from_old_crafty_syntax.sql, install_new_lupopedia.sql, seed_lupopedia.sql, drop_old_crafty_syntax_tables.sql, future_features_lupopedia.sql remain in database/migrations/; all other migrations in database/migrations/legacy/.
- **Step 4 — Manual test cycle:** Backup DB, drop tables, load Crafty 3.7.5, run wizard, test Lupopedia 4.0.x; no other upgrade path.
- **Step 5 — Patch development:** Cursor may write migrations, update SQL/PHP/docs/TOONs; when enough changes accumulate, return to Step 1.

---

## 7. Optional Follow-ups

- **Rename remaining 4.2.1-named files** (e.g. 4.2.1_hotfix_window.md, crafty_import_validation_4.2.1.md, 4.2.1_hotfix_window.sh) to 4.1.0 if they are part of the active release surface.
- **Large CHANGELOG/dialogs:** docs/channels/overview/versioning/CHANGELOG.md and some dialogs still contain historical section headers (4.2.1, 4.3.x). Optionally replace “current system version” and “next release” mentions with 4.1.0; leave historical section titles as-is if they are record-only.
- **References to session_2026_01_16_version_3_0_46 / changelog_dialog_schema_sync_3_0_46** in other dialogs (e.g. changelog_dialog_UTC_2026-01-20.md, changelog_dialog_MONDAY_WOLFIE.md) can be updated to 3_0_46 for consistency.

---

*End of versioning doctrine alignment summary.*
