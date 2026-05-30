---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: actors/anubis/rules/migration-doctrine.md
  web_path: https://www.lupopedia.com/lupopedia/actors/anubis/rules/migration-doctrine.md
  status: null
  when_updated: '20260513033046'
  trust_tier: null
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: rule
  artifact_kind: cursor_doctrine
  channel_key: null
  federation_node_id: null
  thread_key: null
  lupopedia.schema: cursor_rule
  prd_cluster: null
  title: null
  summary: null
---
# file: Rule — Migration Doctrine — session: L-LUPO-WOLFIE — delegation: wolfie:root — web_path: http://www.lupopedia.com/rules/root/migration-doctrine

# Migration Doctrine (MANDATORY)

Cursor MUST follow the Migration Doctrine for all database structure changes. This rule applies to all code, all SQL, all installer logic, and all future development.

## Two-place rule

ALL database structure changes MUST be done in TWO PLACES:

1. **install_new_lupopedia.sql** — Update the canonical full schema to match the TOONs (optional tables in future_features_lupopedia.sql).
2. **Migration SQL file (4.1.0+ only):** Before 4.1.0, do not add migration files; the migrations directory is empty. From 4.1.0 onward, a one-time patch in `database/lupopedia/mysql/migrations/` may apply the same change to an existing DB.

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
