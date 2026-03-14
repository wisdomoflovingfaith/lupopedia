# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: ".\docs\PATHS_FIRSTS_REMOVAL_REPORT.md"
  file_hash: "690a29d53a90f4c49af90ec48ac219df663ad9564d7e1462fb80176f9ba49dce"
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
  file_path_from_root: "lupo-docs\PATHS_FIRSTS_REMOVAL_REPORT.md"
  file_hash: "756bd6588d914013e8392e9463dc0d924281815845eff8a5bc9401c6adcebbe0"
  file_path_from_root: "lupo-docs\PATHS_FIRSTS_REMOVAL_REPORT.md"
  file_hash: "8bf5db72277124f75de74d4a588647ded584a6dcd65581c94864ba9feb417c64"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "lupo_paths_firsts — Schema Removal Report (Steps 1–3)"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "paths_firsts_removal_reportmd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
lupopedia.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

# lupo_paths_firsts — Schema Removal Report (Steps 1–3)

**Date:** 2026-02-10  
**Scope:** Remove lupo_paths_firsts from install SQL, dev alignment migrations, and required-tables documentation only. No DROP TABLE, no TOON regeneration, no PHP or analytics import changes.

---

## Files modified

| File | Change |
|------|--------|
| lupo-database/migrations/install_new_lupopedia.sql | Removed entire CREATE TABLE lupo_paths_firsts block and its three CREATE INDEX statements. |
| lupo-database/migrations/dev_20260204_fix_schema_alignment.sql | Removed all 7 ALTER TABLE lupo_paths_firsts statements. |
| lupo-database/migrations/dev_20260204_fix_schema_alignment_summary.txt | Removed all 7 lupo_paths_firsts column summary lines. |
| lupo-database/migrations/dev_20260205_doctrine_alignment_phase2.sql | Removed the 1 ALTER TABLE lupo_paths_firsts statement (MODIFY COLUMN `id`). |
| lupo-docs/REQUIRED_TABLES_4.1.0.md | Removed list entry `- lupo_paths_firsts`. |

---

## Lines removed

| File | Lines removed | Description |
|------|----------------|-------------|
| lupo-database/migrations/install_new_lupopedia.sql | 16 | CREATE TABLE lupo_paths_firsts ( … ); blank line; 3× CREATE INDEX … ON lupo_paths_firsts (…). |
| lupo-database/migrations/dev_20260204_fix_schema_alignment.sql | 7 | ALTER TABLE lupo_paths_firsts MODIFY COLUMN (from_visit_id, to_visit_id, date_ymd, visits, metadata_json, created_at, updated_at). |
| lupo-database/migrations/dev_20260204_fix_schema_alignment_summary.txt | 7 | Matching summary lines for the 7 columns. |
| lupo-database/migrations/dev_20260205_doctrine_alignment_phase2.sql | 1 | ALTER TABLE lupo_paths_firsts MODIFY COLUMN `id` bigint NOT NULL AUTO_INCREMENT. |
| lupo-docs/REQUIRED_TABLES_4.1.0.md | 1 | Single list item `- lupo_paths_firsts`. |
| **Total** | **32** | |

---

## Confirmations

- **Only steps 1–3 were performed:**
  - **Step 1:** lupo_paths_firsts removed from install SQL (CREATE TABLE and all three CREATE INDEX statements deleted).
  - **Step 2:** Removed from dev alignment migrations: dev_20260204_fix_schema_alignment.sql (7 ALTERs), dev_20260204_fix_schema_alignment_summary.txt (7 lines), dev_20260205_doctrine_alignment_phase2.sql (1 ALTER). No other dev alignment files contained this table.
  - **Step 3:** Removed from REQUIRED_TABLES (lupo-docs/REQUIRED_TABLES_4.1.0.md). No installer validation arrays or other schema docs that list required tables were found to reference lupo_paths_firsts; only that file was updated.

- **No PHP or runtime code was touched:** No `.php` files were modified. No analytics import logic (e.g. import_from_old_crafty_syntax.sql, craftysyntax_to_lupopedia_mysql.sql) was modified. lupo_analytics_paths was not modified.

- **Table not dropped:** No DROP TABLE or any SQL that drops lupo_paths_firsts was added. Manual drop remains for you to run.

- **TOONs not regenerated:** No TOON files or regeneration scripts were run or modified.
