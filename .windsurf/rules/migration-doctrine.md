---
lupopedia.init:
  file_identity: "migration-doctrine.md"
  artifact_type: "windsurf_rule"
  artifact_kind: "doctrine"
  namespace: "windsurf"
  system_version: "4.0.76"
  orchestrator_actor: "windsurf"
  delegation_chain: "windsurf:captain"

lupopedia.headers:
  actor_id: 101
  actor_name: "windsurf"
  delegation_chain: "windsurf:captain"
  lupopedia.version: "4.0.76"
  lupopedia.schema: "windsurf_rule"
  file_path_from_root: ".windsurf/rules/migration-doctrine.md"
  last_modified_utc: "20260411"
  system_version: "4.0.76"
  source_path: "lupo-rules/root/migration-doctrine.md"
  artifact_type: "rule"
  artifact_kind: "windsurf_doctrine"
  purpose: "Windsurf-specific rule derived from canonical root rule"

lupopedia.rules:
  comment: "Rule declaration and provenance block"
  declares:
    - rule_id: "DB002"
      rule_text: "Two-place schema changes, no direct DB, no scoop mysql, TOON source of truth"
      scope: "all_agents"
      category: "database"
      status: "active"
  imports: []
  overrides: []
  provenance:
    authored_by: "wolfie"
    authored_date: "20260411"
    last_reviewed_by: "windsurf"
    last_reviewed_date: "20260411"
    version: "1.0"
    status: "active"
lupopedia.footer:
  version: "4.0.76"
  last_verified: "20260411"
  last_verified_by: "windsurf"
  orchestrator: "windsurf"
  next_action:
    - "Keep in sync with canonical root rules"
---

# file: Rule — Migration Doctrine — session: L-LUPO-WOLFIE — delegation: wolfie:root — web_path: http://www.lupopedia.com/rules/root/migration-doctrine

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

