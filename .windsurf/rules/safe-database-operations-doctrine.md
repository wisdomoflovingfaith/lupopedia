---
lupopedia.init:
  file_identity: "safe-database-operations-doctrine.md"
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
  file_path_from_root: ".windsurf/rules/safe-database-operations-doctrine.md"
  last_modified_utc: "20260411"
  system_version: "4.0.76"
  source_path: "lupo-rules/root/safe-database-operations-doctrine.md"
  artifact_type: "rule"
  artifact_kind: "windsurf_doctrine"
  purpose: "Windsurf-specific rule derived from canonical root rule"

lupopedia.rules:
  comment: "Rule declaration and provenance block"
  declares:
    - rule_id: "DB009"
      rule_text: "All database migrations and schema-changing operations MUST be executed through validated, logged wrappers – never directly via command-line MySQL."
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

