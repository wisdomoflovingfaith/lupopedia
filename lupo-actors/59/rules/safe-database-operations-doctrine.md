---
lupopedia.init:
  orchestrator_actor: "any"
  rule_set_version: "4.0.75+"
  applies_to: ["audit", "code-gen", "db-sync", "migration", "header-sync"]
  enforcement: strict

lupopedia.metadata:
  comment: "Snapshot of metadata for this file or entity at artifact creation."

lupopedia.headers:
  actor_id: 102
  actor_name: "cursor"
  lupopedia.version: "4.0.75"
  lupopedia.schema: "cursor_rule"
  file_path_from_root: "lupo-rules/root/safe-database-operations-doctrine.md"
  last_modified_utc: "20260315"
  system_version: "4.0.75"
  rule_name: "Safe Database Operations"
  rule_type: "constraint"
  artifact_type: "rule"
  artifact_kind: "cursor_doctrine"
  purpose: "All migrations and schema-changing operations must use validated, logged wrappers; never direct command-line MySQL."
  tags: ["cursor", "database", "migration", "safety", "doctrine"]
  source_path: ".cursor/rules/safe-database-operations-doctrine.mdc"

lupopedia.rules:
  comment: "Rule declaration and provenance block"
  declares:
    - rule_id: "DB009"
      rule_text: "All database migrations and schema-changing operations MUST be executed through validated, logged wrappers – never directly via command-line MySQL."
      scope: "all_agents"
      category: "database"
  imports: []
  overrides: []
  provenance:
    authored_by: "cursor"
    authored_date: "20260315"
    last_reviewed_by: "cursor"
    last_reviewed_date: "20260315"
    version: "1.0"
    status: "active"
lupopedia.footer:
  version: "4.0.75"
  last_verified: "20260315"
  last_verified_by: "cursor"
  next_action:
    - "Use lupo-scripts/safe-migrate.php for migrations; see lupo-docs/doctrine/SAFE_MIGRATION_DOCTRINE.md"
---
# DB009: Safe Database Operations (MANDATORY)

## The rule

All database migrations and schema-changing operations **MUST** be executed through **validated, logged wrappers** – never directly via command-line MySQL (e.g. `mysql < file.sql`).

## Prohibited

- **Direct execution** of `.sql` files with `mysql < file.sql` or equivalent.
- **Unvalidated** `mysqldump` pipes to production or any environment without confirmation.
- **Any database operation** that bypasses the application's actor system and audit logging.

## Required

1. **Use the safe migration runner** for schema changes:
   ```bash
   php lupo-scripts/safe-migrate.php <path/to/migration.sql>
   ```
2. **Validation before execution** for:
   - Environment mismatches (production vs staging vs development).
   - Dangerous operations (e.g. `DROP DATABASE`, `DROP TABLE`, `TRUNCATE`) with explicit confirmation.
   - Migration file path and presence.
3. **Logging** to `lupo-logs/migrations/` with:
   - Timestamp (BIGINT UTC `YYYYMMDDHHIISS`).
   - Actor ID (or `cli` when not in session).
   - Migration filename.
   - Environment.
   - Status: `started`, `completed`, or `failed` (and error message if failed).
4. **Production**: Interactive confirmation required when environment is production.
5. **Idempotency**: Migrations SHOULD be idempotent where possible (safe to run multiple times).

## Rationale

Direct command-line MySQL is dangerous and unaccountable. One mistyped `DROP DATABASE` on the wrong server can destroy weeks of work. By routing all operations through a validated wrapper we gain:

- **Audit trail** — Who ran what and when.
- **Environment protection** — No accidental production drops.
- **Dangerous-operation warnings** — Explicit confirmation for DROP/TRUNCATE.
- **Actor integration** — Migrations attributed to an actor when available.
- **Consistent logging** — Handoff and continuity supported via `lupo-logs/migrations/`.

## Full specification

Canonical safe migration runner: **`lupo-scripts/safe-migrate.php`**.  
Full specification: **[lupo-docs/doctrine/SAFE_MIGRATION_DOCTRINE.md](../../lupo-docs/doctrine/SAFE_MIGRATION_DOCTRINE.md)**.

All agents MUST use the safe runner for database migrations and schema changes. Do not run raw SQL files via `mysql` CLI.
