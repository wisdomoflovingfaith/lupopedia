# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: ".\docs\audits\FUTURE_FEATURES_AND_REQUIRED_TABLES_ALIGNMENT_SUMMARY.md"
  file_hash: "d80ac972e9ff1f5e20947a6844d87e10dba48e259880ebaca9fcfc34265f7e05"
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
  file_path_from_root: "docs\audits\FUTURE_FEATURES_AND_REQUIRED_TABLES_ALIGNMENT_SUMMARY.md"
  file_hash: "6b92939a1f7b94983b49187ada9a3bd02c5b177926047ea0f06e735ca0bc3fd1"
  file_path_from_root: "docs\audits\FUTURE_FEATURES_AND_REQUIRED_TABLES_ALIGNMENT_SUMMARY.md"
  file_hash: "3d4811288d16541a82c5e52cce3ac14f971a7958db05f7538eb4a6f5e97f6be9"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Future Features and Required Tables Alignment Summary"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "audits", "future_features_and_required_tables_alignment_summarymd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
lupopedia.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

# Future Features and Required Tables Alignment Summary

**Date:** 2026-02-20  
**Version context:** 4.0.21 (patch-only)  
**Purpose:** Confirm alignment of required-tables doctrine, `install_new_lupopedia.sql`, `future_features_lupopedia.sql`, and required-tables documentation.  
**Audit authority:** docs/audits/4.0.21_SCHEMA_VALIDATION_PHASE1_AUDIT.md, docs/audits/4.0.21_SCHEMA_VALIDATION_PHASE2_AUDIT.md, docs/REQUIRED_TABLES_4.0.21.md.

---

## 1. Final REQUIRED TABLES (install_new_lupopedia.sql)

Required tables are the union of:

- **Importer tables:** Every table referenced in `database/migrations/import_from_old_crafty_syntax.sql` (INSERT/UPDATE/TRUNCATE/DELETE targets).
- **Runtime / wizard / seed / class-implemented:** All tables used by active PHP (app/, lupo-includes/), wizard, installer, seed_lupopedia.sql, and runtime features.

The canonical list is documented in **docs/REQUIRED_TABLES_4.0.21.md** under:

- **Required Crafty Syntax Compatibility Tables (Importer)** — importer targets (including lupo_crm_leads, lupo_modules, lupo_analytics_visits_daily, lupo_analytics_visits_monthly).
- **Required Lupopedia Core Tables** — all 198 tables in install (TOONs in docs/toons/); Phase 1 (81) + Phase 2 (117) per schema validation audits.

All of these tables are created by `install_new_lupopedia.sql` and must not be removed or moved to `future_features_lupopedia.sql`. **No additional tables were moved to future_features in Phase 2;** the four future-features tables remain the only ones in `future_features_lupopedia.sql`.

---

## 2. Final FUTURE FEATURES TABLES (future_features_lupopedia.sql)

Tables that are **not** required (not in importer, not used by active PHP/seed/wizard/runtime). Their `CREATE TABLE` and related indexes live **only** in `database/migrations/future_features_lupopedia.sql`.

- lupo_integration_test_results  
- lupo_memory_debug_log  
- lupo_narrative_fragments  
- lupo_test_performance_metrics  

These four tables are **not** created by `install_new_lupopedia.sql`.

---

## 3. Tables Moved from install_new_lupopedia.sql to future_features_lupopedia.sql

| Table | Notes |
|-------|--------|
| lupo_integration_test_results | Integration testing; not used at runtime. |
| lupo_memory_debug_log | Memory debug tooling; not used at runtime. |
| lupo_narrative_fragments | Narrative/agent fragments; future feature. |
| lupo_test_performance_metrics | Performance testing; not used at runtime. |

Their full `CREATE TABLE` and index definitions were removed from `install_new_lupopedia.sql` and added to `future_features_lupopedia.sql`.

---

## 3a. Phase 2 (4.0.21) outcome

- Schema validation Phase 2 audited all 117 non–Phase 1 tables (TOON-only).
- **No additional tables** were recommended for move to `future_features_lupopedia.sql`. The four future-features tables above remain the only ones in that file.
- docs/REQUIRED_TABLES_4.0.21.md was created and aligns with install, TOONs, and Phase 1 + Phase 2 audits.

---

## 4. Confirmations

- **All importer tables are in the required list.**  
  Every table referenced in `import_from_old_crafty_syntax.sql` is listed in docs/REQUIRED_TABLES_4.0.21.md as Required Crafty Syntax Compatibility or Required Lupopedia Core. None were removed or moved.

- **No importer table was removed or moved.**  
  No table that appears in `import_from_old_crafty_syntax.sql` was moved to `future_features_lupopedia.sql` or dropped from the install.

- **install_new_lupopedia.sql now contains only required tables (plus optional tables still in install).**  
  The four tables listed in §3 were removed from the install file in an earlier cycle. All remaining tables in the install file (198 tables, per TOONs) are either required (importer + runtime) or optional (documented in REQUIRED_TABLES_4.0.21.md; may be moved to future_features in a later pass). Phase 2 schema validation (4.0.21) did not recommend moving any additional tables to future_features.

- **future_features_lupopedia.sql contains only non-required tables.**  
  It contains exactly the four tables listed in §2. No DROP, ALTER, or data manipulation; only CREATE TABLE and indexes. Schema doctrine applied (no FKs, no triggers, BIGINT timestamps, no UNSIGNED, no integer display widths).

- **Required-tables documentation matches the actual SQL.**  
  docs/REQUIRED_TABLES_4.0.21.md reflects the split between required (install), optional, and future features (future_features_lupopedia.sql) and includes the doctrine note: required = importer + runtime + wizard + seed + class-implemented; future = non-required; no table in `import_from_old_crafty_syntax.sql` may be removed or moved to future_features.

---

## 5. Canonical SQL Files (database/migrations/)

- install_new_lupopedia.sql  
- seed_lupopedia.sql  
- import_from_old_crafty_syntax.sql  
- drop_old_crafty_syntax_tables.sql  
- future_features_lupopedia.sql  

No other SQL files in `database/migrations/` are canonical for this doctrine. One-time migrations and audit reports (e.g. dev_20260204_fix_schema_alignment_summary.txt, reserved_word_audit_report.txt) are in `database/migrations_legacy/`.

---

## 6. Doctrine Reminders Applied

- lupopedia_rpz schema was not touched.  
- No 4.1.x or other major/minor version introduced; document version is 4.0.21.  
- No Lupopedia→Lupopedia migrations; only Crafty 3.7.5 → Lupopedia 4.0.x context.
