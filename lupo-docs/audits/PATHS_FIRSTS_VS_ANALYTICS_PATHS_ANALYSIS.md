# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: "lupo-docs/audits/PATHS_FIRSTS_VS_ANALYTICS_PATHS_ANALYSIS.md"
  file_hash: "bf01f4f619163582c7d8c40bb275f1e86bacb39b0ce5a5caaa26d5d962a2fa36"
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
  file_path_from_root: "lupo-docs\PATHS_FIRSTS_VS_ANALYTICS_PATHS_ANALYSIS.md"
  file_hash: "e47e2e04c4f3bc1722cc225b4fda3d214dbe0d0a76ab667b8c84aae171f84af0"
  file_path_from_root: "lupo-docs\PATHS_FIRSTS_VS_ANALYTICS_PATHS_ANALYSIS.md"
  file_hash: "9c8319e3114478da5713b33359c784e1ccbc931a91bdf817a6ac72a678d78f00"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Analysis: lupo_analytics_paths vs lupo_paths_firsts"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "paths_firsts_vs_analytics_paths_analysismd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
lupopedia.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

# Analysis: lupo_analytics_paths vs lupo_paths_firsts

**Purpose:** Confirm whether **lupo_paths_firsts** is still used after analytics path tables were unified into **lupo_analytics_paths**. Determine if lupo_paths_firsts can be safely dropped.  
**Scope:** Full repository search; analysis only (no code changes, no DROP, no query rewrites).

---

## 1. Table definitions (from install_new_lupopedia.sql)

| Table | PK | Key columns | Purpose |
|-------|-----|-------------|---------|
| **lupo_analytics_paths** | analytics_path_id | from_page_id, to_page_id, year_month (char 6), transition_type, transition_count, metadata_json, created_ymdhis, updated_ymdhis, is_deleted | Unified analytics path transitions (replaces livehelp_paths_firsts + livehelp_paths_monthly; transition_type 'first' / 'all'). |
| **lupo_paths_firsts** | paths_first_id | from_visit_id, to_visit_id, date_ymd, visits, metadata_json, created_at, updated_at | Older schema: visit-to-visit first paths (different column names; no transition_type). |

Documentation and import scripts state that **livehelp_paths_firsts** and **livehelp_paths_monthly** were unified into **lupo_analytics_paths**, not into lupo_paths_firsts.

---

## 2. Reference tables

### lupo_analytics_paths

| File path | Line(s) | Snippet / usage | Operation | Active / legacy |
|-----------|---------|------------------|-----------|------------------|
| lupo-database/migrations/install_new_lupopedia.sql | 3886–3900 | CREATE TABLE lupo_analytics_paths ( … ); ALTER … AUTO_INCREMENT | Schema | Active |
| lupo-database/migrations/dev_20260204_fix_schema_alignment.sql | 1924–1933 | ALTER TABLE lupo_analytics_paths MODIFY COLUMN (from_page_id, to_page_id, year_month, …) | Migration | Active |
| lupo-database/migrations/dev_20260204_fix_schema_alignment_summary.txt | 1924–1933 | Column summary for lupo_analytics_paths | Doc | Active |
| lupo-database/migrations/import_from_old_crafty_syntax.sql | 1362, 1377–1402, 1404+ | Comment: livehelp_paths_firsts → lupo_analytics_paths. TRUNCATE lupo_analytics_paths; INSERT … FROM livehelp_paths_firsts; INSERT … FROM livehelp_paths_monthly | TRUNCATE / INSERT | Active (import) |
| lupo-database/migrations/craftysyntax_to_lupopedia_mysql.sql | 1310, 1325–1352 | Same: TRUNCATE + INSERT into lupo_analytics_paths from livehelp_paths_firsts and livehelp_paths_monthly | TRUNCATE / INSERT | Active (import) |
| lupo-database/migrations/dev_20260206_reserved_word_column_renames.sql | — | Does not rename year_month (year_month is not a reserved word; column remains year_month) | Migration | Active |
| lupo-docs/REQUIRED_TABLES_4.1.0.md | 64 | List entry | Doc | Active |
| lupo-docs/doctrine/migrations/MIGRATION_MAPPING_REFERENCE.md | 89 | livehelp_paths_firsts, livehelp_paths_monthly → lupo_analytics_paths | Doc | Reference |
| lupo-docs/doctrine/migrations/livehelp_paths_firsts_migration.md | 3, 32, 86, 127, 130, 145, 230 | Replacement: lupo_analytics_paths; TRUNCATE/INSERT examples | Doc | Reference |
| lupo-docs/doctrine/CRAFTY_SYNTAX_*.md, lupo-docs/channels/…/CRAFTY_SYNTAX_*.md | various | livehelp_paths_firsts/monthly → lupo_analytics_paths | Doc | Reference |
| lupo-docs/doctrine/MigrationAtlas.md | 24–25 | livehelp_paths_firsts/monthly → lupo_analytics_paths | Doc | Reference |
| CHANGELOG.md, lupo-docs/channels/schema/migrations/3.0.0.md, dialogs/… | various | year_month remains year_month (not a reserved word) | Doc | Reference |
| lupo-database/migrations_legacy/*.sql | various | CREATE TABLE lupo_analytics_paths; INSERT | Legacy schema | Legacy |
| complete_schema.txt, DIRECTORY_TREE.md, .output.txt, lupo-database/toon_output.txt | — | Table/list references | Doc / output | Reference |

**PHP runtime:** No PHP file references `lupo_analytics_paths` or `analytics_paths` (grep `*.php`: no matches). All usage is in SQL migrations, import scripts, and docs.

### lupo_paths_firsts

| File path | Line(s) | Snippet / usage | Operation | Active / legacy |
|-----------|---------|------------------|-----------|------------------|
| lupo-database/migrations/install_new_lupopedia.sql | 3902–3916 | CREATE TABLE lupo_paths_firsts ( … ); CREATE INDEX … | Schema | Definition only |
| lupo-database/migrations/dev_20260204_fix_schema_alignment.sql | 1934–1940 | ALTER TABLE lupo_paths_firsts MODIFY COLUMN (from_visit_id, to_visit_id, date_ymd, visits, metadata_json, created_at, updated_at) | Migration | Schema only |
| lupo-database/migrations/dev_20260204_fix_schema_alignment_summary.txt | 1934–1940 | Column summary | Doc | Reference |
| lupo-database/migrations/dev_20260205_doctrine_alignment_phase2.sql | 32 | ALTER TABLE lupo_paths_firsts MODIFY COLUMN `id` bigint NOT NULL AUTO_INCREMENT | Migration | Schema only (note: install uses paths_first_id as PK; may be legacy variant) |
| lupo-docs/REQUIRED_TABLES_4.1.0.md | 227 | List entry | Doc | Reference |
| .output.txt | 333 | lupo_paths_firsts.too (file listing) | Output | Reference |

**PHP runtime:** No PHP file references `lupo_paths_firsts` or `paths_firsts` (grep `*.php`: no matches).

**Import / analytics scripts:** The Crafty Syntax import explicitly maps **livehelp_paths_firsts** and **livehelp_paths_monthly** into **lupo_analytics_paths** only. No import or migration script SELECTs from or INSERTs into **lupo_paths_firsts**.

---

## 3. Which table is actually used (summary)

| Consumer | lupo_analytics_paths | lupo_paths_firsts |
|----------|-----------------------------|----------------------------|
| **Analytics migration / import** | Yes. import_from_old_crafty_syntax.sql and craftysyntax_to_lupopedia_mysql.sql TRUNCATE and INSERT into lupo_analytics_paths from livehelp_paths_firsts and livehelp_paths_monthly (transition_type 'first' / 'all'). | No. No script reads or writes lupo_paths_firsts. |
| **Services / helpers** | No PHP references. | No PHP references. |
| **API endpoints** | No PHP references. | No PHP references. |
| **Crafty Syntax compatibility** | Yes (import SQL targets this table). | No. |
| **Install SQL** | Yes (CREATE + AUTO_INCREMENT). | Yes (CREATE + indexes). |
| **Dev alignment migrations** | Yes (ALTER in dev_20260204; reserved-word rename in dev_20260206). | Yes (ALTER in dev_20260204 and dev_20260205). |
| **TOONs / schema docs** | Referenced in docs and lists. | Only in REQUIRED_TABLES list and alignment summary; no dedicated migration doc that uses it as target. |

**Conclusion:**  
- **lupo_analytics_paths** is the **active** unified analytics table: it is the documented replacement for livehelp_paths_firsts and livehelp_paths_monthly, and all import SQL writes path data into it. No PHP uses it, but the migration/import path is current.  
- **lupo_paths_firsts** has **zero active references** in application or import logic: no PHP, no import script, no analytics script reads or writes it. It appears only in install SQL (CREATE), dev alignment migrations (ALTER), and REQUIRED_TABLES. It is the **unused/duplicate** table superseded by lupo_analytics_paths.

---

## 4. Duplicate or unused table

**lupo_paths_firsts** is the **unused** table. The doctrine and migration docs state that path analytics were unified into **lupo_analytics_paths** (with transition_type). The older **lupo_paths_firsts** schema (from_visit_id, to_visit_id, date_ymd, visits) is never populated by any import or used by any code.

---

## 5. Recommendation

**lupo_paths_firsts can be dropped** from a **code and data-flow** perspective: it has **zero active references** (no PHP, no import or analytics script reads/writes). All path analytics flow into **lupo_analytics_paths** only.

Before dropping:

1. **Data:** If any deployment ever populated lupo_paths_firsts (e.g. via a one-off or legacy script), decide whether to migrate that data into lupo_analytics_paths (with appropriate transition_type and column mapping) or discard it.
2. **Schema and docs:** Remove the table from install SQL, dev alignment migrations (including dev_20260205), and REQUIRED_TABLES; update or remove any schema/TOON references.

---

## 6. Files that would need cleanup before dropping lupo_paths_firsts

| File | Change |
|------|--------|
| lupo-database/migrations/install_new_lupopedia.sql | Remove CREATE TABLE lupo_paths_firsts and its three CREATE INDEX statements. |
| lupo-database/migrations/dev_20260204_fix_schema_alignment.sql | Remove all 7 ALTER TABLE lupo_paths_firsts statements. |
| lupo-database/migrations/dev_20260204_fix_schema_alignment_summary.txt | Remove the 7 lupo_paths_firsts column summary lines. |
| lupo-database/migrations/dev_20260205_doctrine_alignment_phase2.sql | Remove the 1 ALTER TABLE lupo_paths_firsts statement (MODIFY COLUMN `id`). |
| lupo-docs/REQUIRED_TABLES_4.1.0.md | Remove the list entry for lupo_paths_firsts. |
| complete_schema.txt | Regenerate or edit to remove lupo_paths_firsts. |
| lupo-docs/toons/ (if present) | Remove or regenerate lupo_paths_firsts TOON after schema change. |
| .output.txt / DIRECTORY_TREE.md | Update if they list this table or its TOON. |

No PHP or import/migration logic changes are required to drop **lupo_paths_firsts**; no application or import code references it.
