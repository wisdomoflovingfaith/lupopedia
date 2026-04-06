---
lupopedia.headers:
  actor_id: 100
  actor_name: "kiro"
  delegation_chain: "kiro:root"
  lupopedia.version: "4.0.76"
  lupopedia.schema: "kiro_rule"
  file_path_from_root: ".kiro/rules/migration-doctrine.md"
  last_modified_utc: "20260406"
  system_version: "4.0.76"
  source_path: "lupo-rules/root/migration-doctrine.md"
  artifact_type: "rule"
  artifact_kind: "kiro_doctrine"
---


# Migration Doctrine (MANDATORY)

Cursor MUST follow the Migration Doctrine for all database structure changes. This rule applies to all code, all SQL, all installer logic, and all future development.

## Two-place rule

ALL database structure changes MUST be done in TWO PLACES:

1. **install_new_lupopedia.sql** — Update the canonical full schema to match the TOONs (optional tables in future_features_lupopedia.sql).
2. **Migration SQL file (4.1.0+ only):** Before 4.1.0, do not add migration files; the migrations directory is empty. From 4.1.0 onward, a one-time patch in `lupo-database/lupopedia/mysql/migrations/` may apply the same change to an existing DB.

## Cursor MUST NEVER

- Run "scoop mysql" or any command-line SQL tool.
- Modify the database directly.
- Infer schema from the live database.

## Cursor MUST ALWAYS

- Read schema from TOON files in `lupo-docs/toons/` (source of truth).
- Update `install_new_lupopedia.sql` to match the TOONs when making a schema change.
- Generate a migration SQL file to apply the same change to the live DB.

## Migration SQL files MUST

- Be idempotent.
- Use explicit ALTER TABLE statements.
- Use explicit IDs (no AUTO_INCREMENT for registry-backed tables).
- Follow timestamp doctrine (BIGINT UTC YYYYMMDDHHIISS).
- NEVER drop or recreate tables unless explicitly instructed.

## Wizard MUST NOT

- Run any migration SQL.
- Attempt Lupopedia → Lupopedia upgrade.
- Modify schema except during Crafty Syntax 3.7.5 → Lupopedia install.

Canonical doctrine: `lupo-docs/doctrine/MIGRATION_DOCTRINE.md`. This rule is permanent.
