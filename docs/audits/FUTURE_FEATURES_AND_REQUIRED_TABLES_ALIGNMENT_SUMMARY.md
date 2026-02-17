# Future Features and Required Tables Alignment Summary

**Date:** 2026-02-11  
**Version context:** 4.0.2 (patch-only)  
**Purpose:** Confirm alignment of required-tables doctrine, `install_new_lupopedia.sql`, `future_features_lupopedia.sql`, and required-tables documentation.

---

## 1. Final REQUIRED TABLES (install_new_lupopedia.sql)

Required tables are the union of:

- **Importer tables:** Every table referenced in `database/migrations/import_from_old_crafty_syntax.sql` (INSERT/UPDATE/TRUNCATE/DELETE targets).
- **Runtime / wizard / seed / class-implemented:** All tables used by active PHP (app/, lupo-includes/), wizard, installer, seed_lupopedia.sql, and runtime features.

The canonical list is documented in **docs/REQUIRED_TABLES_4.0.6.md** under:

- **Required Crafty Syntax Compatibility Tables (Importer)** — 21 tables (importer targets).
- **Required Lupopedia Core Tables** — 200 tables (after moving 4 to future_features).

All of these tables are created by `install_new_lupopedia.sql` and must not be removed or moved to `future_features_lupopedia.sql`.

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

## 4. Confirmations

- **All importer tables are in the required list.**  
  Every table referenced in `import_from_old_crafty_syntax.sql` is listed in docs/REQUIRED_TABLES_4.0.6.md as Required Crafty Syntax Compatibility or Required Lupopedia Core. None were removed or moved.

- **No importer table was removed or moved.**  
  No table that appears in `import_from_old_crafty_syntax.sql` was moved to `future_features_lupopedia.sql` or dropped from the install.

- **install_new_lupopedia.sql now contains only required tables (plus optional tables still in install).**  
  The four tables listed in §3 were removed from the install file. All remaining tables in the install file are either required (importer + runtime) or optional (documented as such; may be moved to future_features in a later pass).

- **future_features_lupopedia.sql contains only non-required tables.**  
  It contains exactly the four tables listed in §2. No DROP, ALTER, or data manipulation; only CREATE TABLE and indexes. Schema doctrine applied (no FKs, no triggers, BIGINT timestamps, no UNSIGNED, no integer display widths).

- **Required-tables documentation matches the actual SQL.**  
  docs/REQUIRED_TABLES_4.0.6.md reflects the split between required (install) and future features (future_features_lupopedia.sql) and includes the doctrine note: required = importer + runtime + wizard + seed + class-implemented; future = non-required; no table in `import_from_old_crafty_syntax.sql` may be removed or moved to future_features.

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
- No 4.1.x or other major/minor version introduced; document version is 4.0.2.  
- No Lupopedia→Lupopedia migrations; only Crafty 3.7.5 → Lupopedia 4.0.x context.
