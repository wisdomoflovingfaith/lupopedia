---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/doctrine/safe_migration_doctrine.md
  web_path: https://www.lupopedia.com/lupopedia/docs/doctrine/safe_migration_doctrine.md
  status: ''
  when_updated: '20260513033046'
  trust_tier: null
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: null
  artifact_kind: null
  channel_key: null
  federation_node_id: null
  thread_key: ''
  lupopedia.schema: documentation
  prd_cluster: null
  title: ''
  summary: ''
---
# Safe Migration Doctrine

**Root rule:** [rules/root/safe-database-operations-doctrine.md](../../rules/root/safe-database-operations-doctrine.md) (DB009).

## Purpose

All database migrations and schema-changing operations MUST be executed through the **canonical safe migration runner**, not via direct command-line MySQL. This doctrine defines the required behavior of that runner and the responsibilities of agents and operators.

## Canonical runner

- **Script:** `scripts/safe-migrate.php`
- **Usage:** `php scripts/safe-migrate.php <path/to/migration.sql>`
- The script MUST be invoked from the repository root (or with paths relative to it). It loads config and bootstrap, then validates and runs the given SQL file.

## Required behavior

### 1. Validation

- **File existence** — The migration file path must exist and be readable.
- **Path safety** — The migration file should be under the repository or an explicitly allowed directory; reject paths that could point outside the project.
- **Environment** — If the application or config exposes an environment (e.g. production, staging, development), the runner MUST use it. In **production**, the runner MUST require interactive confirmation before executing.
- **Dangerous operations** — Before execution, the runner MUST scan the SQL content for patterns such as `DROP DATABASE`, `DROP TABLE`, `TRUNCATE` (and optionally `ALTER TABLE ... DROP COLUMN`). If any are found, the runner MUST require explicit interactive confirmation (e.g. prompt "Dangerous operations detected. Type 'yes' to continue.") and MUST NOT proceed unless the user confirms.

### 2. Execution

- **Connection** — Use the project's database connection (e.g. `DatabaseFactory::getConnection()` / PDO_DB). Do not shell out to `mysql` CLI. **Runtime** access rules and **installer** carve-out: **`docs/doctrine/DATABASE_DOCTRINE.md`** — **Runtime database access (PDO_DB) and installer exception**.
- **Transactions** — Where supported by the driver and the type of statements (DDL may not be transactional on all engines), the runner SHOULD run the migration inside a transaction and commit on success or roll back on failure. If the engine does not support transactional DDL, the runner MUST still log start and completion/failure and MUST NOT execute via raw `mysql` CLI.
- **Statement execution** — Execute SQL statements via the application connection. Split multi-statement files into single statements where necessary; run each in order. On first failure, stop, roll back if a transaction was started, log failure, and exit with a non-zero status.

### 3. Logging

- **Directory** — All migration run attempts MUST be appended to a log under `logs/migrations/`. Create the directory if it does not exist.
- **Format** — One JSON object per line (JSONL). Each line MUST include at least:
  - `timestamp` — BIGINT UTC `YYYYMMDDHHIISS` (e.g. `20260315143022`).
  - `actor_id` — The current actor ID if available (e.g. from `.lupo_actor` or session); otherwise `"cli"` or equivalent.
  - `migration` — Basename of the migration file (e.g. `migration_add_foo.sql`).
  - `environment` — Value from config or default (e.g. `development`, `production`).
  - `status` — One of `started`, `completed`, `failed`.
- **On failure** — Add an `error` field with the exception or error message.
- **File naming** — Log file MAY be named by date (e.g. `YYYY-MM-DD.jsonl`) for easier inspection. The exact name is implementation-defined; the requirement is that all runs are appended and machine-readable.

### 4. Idempotency

- Migration authors SHOULD write migrations to be **idempotent** where possible (e.g. `ADD COLUMN IF NOT EXISTS`, or check-before-apply). The safe runner MAY optionally check whether a migration has already been applied (e.g. via a migrations table or log) and warn or skip; such behavior is optional and MUST be documented if implemented.

## Prohibited

- **Direct `mysql` execution** — Do not run `mysql -u ... -p ... < file.sql` or equivalent for project migrations.
- **Bypassing the wrapper** — Do not execute arbitrary SQL files against project databases without using the safe runner (or another approved, logged, validated mechanism).
- **Skipping confirmation** — In production, or when dangerous operations are detected, the runner MUST NOT proceed without explicit user confirmation.

## Integration with doctrine

- **Database doctrine** — Migrations executed via the safe runner still MUST comply with [DATABASE_DOCTRINE.md](DATABASE_DOCTRINE.md): no new foreign keys, no new triggers or stored procedures, timestamps set in application code, etc. The safe runner only governs *how* migration files are executed, not *what* they may contain; migration content remains subject to database doctrine.
- **Reserved ID / registry** — Migration SQL that inserts into registry-backed tables MUST use explicit IDs and MUST NOT rely on AUTO_INCREMENT or lastInsertId(); see reserved-id doctrine and REGISTRY_DOCTRINE.

## Summary

Use `php scripts/safe-migrate.php <path/to/migration.sql>` for all migration runs. Ensure the script validates the file, checks environment and dangerous operations, logs to `logs/migrations/`, and uses the application DB connection. Do not run migrations via raw MySQL CLI.
