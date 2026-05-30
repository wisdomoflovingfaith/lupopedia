---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: actors/anubis/rules/safe-database-operations-doctrine.md
  web_path: https://www.lupopedia.com/lupopedia/actors/anubis/rules/safe-database-operations-doctrine.md
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
   php scripts/safe-migrate.php <path/to/migration.sql>
   ```
2. **Validation before execution** for:
   - Environment mismatches (production vs staging vs development).
   - Dangerous operations (e.g. `DROP DATABASE`, `DROP TABLE`, `TRUNCATE`) with explicit confirmation.
   - Migration file path and presence.
3. **Logging** to `logs/migrations/` with:
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
- **Consistent logging** — Handoff and continuity supported via `logs/migrations/`.

## Full specification

Canonical safe migration runner: **`scripts/safe-migrate.php`**.  
Full specification: **[docs/doctrine/SAFE_MIGRATION_DOCTRINE.md](../../../docs/doctrine/SAFE_MIGRATION_DOCTRINE.md)**.

All agents MUST use the safe runner for database migrations and schema changes. Do not run raw SQL files via `mysql` CLI.
