---
lupopedia.headers:
  actor_id: 100
  actor_name: "kiro"
  delegation_chain: "kiro:root"
  lupopedia.version: "4.0.76"
  lupopedia.schema: "kiro_rule"
  file_path_from_root: ".kiro/rules/required-tables-future-features-doctrine.md"
  last_modified_utc: "20260406"
  system_version: "4.0.76"
  source_path: "lupo-rules/root/required-tables-future-features-doctrine.md"
  artifact_type: "rule"
  artifact_kind: "kiro_doctrine"
---


# Required Tables and Future Features Doctrine (MANDATORY)

Cursor MUST apply this doctrine in every patch cycle (4.0.2 → 4.0.3 → … → 4.0.999). This rule is permanent.

## 1. Future Features File (MANDATORY)

The file **lupo-database/migrations/future_features_lupopedia.sql** is **canonical**. It contains **ONLY** non-required tables. Cursor must **maintain** this file. Cursor must **NEVER** move future-features tables back into install_new_lupopedia.sql. Cursor must **NEVER** treat these tables as required.

## 2. Required Tables File (MANDATORY)

**lupo-docs/REQUIRED_TABLES_4.0.21.md** is the authoritative required-tables list. Keep it updated in every patch cycle. Required tables = tables referenced in import_from_old_crafty_syntax.sql; tables used by active PHP classes; tables required by wizard/installer/seed/runtime. **NEVER** move required tables into future_features_lupopedia.sql.

## 3. Install SQL Must Match Required Tables

- **install_new_lupopedia.sql** contains **ONLY** required tables.
- **future_features_lupopedia.sql** contains **ONLY** non-required tables.

## 4. Migrations Cleanup Doctrine

Canonical SQL set: import_from_old_crafty_syntax.sql, install_new_lupopedia.sql, seed_lupopedia.sql, drop_old_crafty_syntax_tables.sql, future_features_lupopedia.sql, old_crafty_syntax_3_7_5_start.sql. **All other** SQL in lupo-database/migrations/ must be moved to **lupo-database/migrations_legacy/** unless explicitly marked canonical.

## 5. Patch-Only Versioning (MANDATORY)

- Only **patch** bumps: 4.0.2 → 4.0.999. **NEVER** suggest 4.1.0 until auto-installer release. **NEVER** generate Lupopedia→Lupopedia migrations. **ONLY** valid upgrade path: **Crafty Syntax 3.7.5 → Lupopedia 4.0.x**.

## 6. Directory Tree Integrity (MANDATORY)

Before any versioning or migration cleanup: run **python lupo-scripts/generate_directory_tree.py**; use **DIRECTORY_TREE.md** as authoritative file list. Refuse to proceed if DIRECTORY_TREE.md is outdated.

## 7. Audit Requirements

Maintain **lupo-docs/audits/FUTURE_FEATURES_AND_REQUIRED_TABLES_ALIGNMENT_SUMMARY.md** and update in every patch cycle.

## 8. Doctrine Reminders

No RPZ tables in production; no experimental tables in lupo-install/seed/import; no reintroduction of removed tables; no foreign keys, triggers, views, UNSIGNED, display widths; BIGINT timestamps only; no schema changes outside one-time dev migrations and canonical lupo-install/future_features; no modifying the importer unless instructed; patch-only versioning until auto-installer cycle.

This rule is permanent and applies to all future patch cycles.
