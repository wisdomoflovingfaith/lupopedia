---
lupopedia.headers:
  actor_id: 1
  actor_name: "wolfie"
  delegation_chain: "wolfie:root"
  lupopedia.version: "1.0"
  lupopedia.schema: "cursor_rule"
  file_path_from_root: "lupo-rules/root/migration-doctrine.md"
  web_path: "http://www.lupopedia.com/rules/root/migration-doctrine"
  last_modified_utc: "20260310"
  system_version: "4.0.68"
  rule_name: "Migration Doctrine"
  rule_type: "constraint"
  artifact_type: "rule"
  artifact_kind: "cursor_doctrine"
  purpose: "Two-place schema changes, no direct DB, no scoop mysql, TOON source of truth."
  tags: ["cursor", "migration", "schema", "doctrine"]
  source_path: ".cursor/rules/migration-doctrine.mdc"

lupopedia.footer:
  version: "4.0.68"
  last_verified: "20260310"
  last_verified_by: "wolfie"
---
# file: Rule — Migration Doctrine — session: L-LUPO-WOLFIE — delegation: wolfie:root — web_path: http://www.lupopedia.com/rules/root/migration-doctrine

# Migration Doctrine (MANDATORY)

Cursor MUST follow the Migration Doctrine for all database structure changes. This rule applies to all code, all SQL, all installer logic, and all future development.

## Two-place rule

ALL database structure changes MUST be done in TWO PLACES:

1. **install_new_lupopedia.sql** — Update the canonical full schema to match the TOONs.
2. **A new migration SQL file** — One-time patch in `database/migrations/` to apply the same change to the live DB.

## Cursor MUST NEVER

- Run "scoop mysql" or any command-line SQL tool.
- Modify the database directly.
- Infer schema from the live database.

## Cursor MUST ALWAYS

- Read schema from TOON files in `docs/toons/` (source of truth).
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

Canonical doctrine: `docs/doctrine/MIGRATION_DOCTRINE.md`. This rule is permanent.
