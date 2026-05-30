---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: rules/root/toon-source-of-truth.md
  web_path: https://www.lupopedia.com/lupopedia/rules/root/toon-source-of-truth.md
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
# file: Rule — TOON Files and Canonical Schema — session: L-LUPO-WOLFIE — delegation: wolfie:root — web_path: http://www.lupopedia.com/rules/root/toon-source-of-truth

# TOON Files and Canonical Schema (PERMANENT)

Cursor MUST treat **install_new_lupopedia.sql** and **seed_lupopedia.sql** as the single source of truth for all table and column definitions. TOON files are regenerated from this canonical schema.

4.0.88 enforcement extension:

- No schema guessing is allowed.
- Agents must inspect TOON exports first, then confirm with table docs.
- JSON exports are secondary; TOON is the primary schema inspection surface for agent work.

## Canonical schema source (4.0.15+)

- **database/migrations/install_new_lupopedia.sql** — Full table definitions (CREATE TABLE).
- **database/migrations/seed_lupopedia.sql** — Seeded tables and row structure.
- **TOON files:** `docs/toons/` — Regenerated from install SQL via `scripts/generate_toon_from_sql.py`. One file per table: `<table_name>.toon.json`.
- **Every** table name, column name, column type, index, and key MUST match the canonical schema. TOONs reflect the install SQL; they are not independently authoritative until regenerated.
- Runtime TOON export path in current architecture: `database/lupopedia/toon/`.
- Secondary JSON export path: `database/lupopedia/json/`.

## Rules

### 1. Code requires a table/column that exists in TOONs but NOT in the database

- Cursor **may** generate a **one-time SQL migration file** to add the missing table or column.
- Name migrations like: `migration_add_missing_columns.sql`, `migration_add_missing_tables.sql`
- Migrations must be **idempotent and safe** (e.g. `ADD COLUMN IF NOT EXISTS` where supported, or document "run once").

### 2. Code requires a table/column that does NOT exist in the TOONs

- Cursor must **NOT** create it.
- Cursor must **refactor the code** to match the TOON schema (remove or replace the reference).
- Flag the code as incorrect and fix it to use only TOON-defined schema.

### 3. Table/column missing from the database but present in the TOONs

- Cursor may generate a migration file (e.g. `migration_add_missing_columns.sql`, `migration_add_missing_tables.sql`) that is one-time and idempotent.

### 4. Table/column present in the database but NOT in the TOONs

- Cursor must **NOT** use it in code.
- Cursor must **refactor the code** to remove references to it (or use an alternative that exists in the TOONs).

### 5. Table docs are mandatory companion surface

- Before implementing schema-dependent changes, agents must review table docs under `docs/database/lupopedia/tables/`.
- If TOON and table docs diverge, escalate and reconcile; do not guess or infer silently.

## Verification

- Confirm **all** schema usage in code matches TOON definitions exactly.
- Report all **mismatches** between:
  - TOON schema
  - install SQL (e.g. `database/migrations/install_new_lupopedia.sql`)
  - actual code usage
- Include table documentation mismatch findings when present.

## TOON generation (4.0.15+)

- **Canonical:** TOONs are regenerated from `install_new_lupopedia.sql` via `scripts/generate_toon_from_sql.py`. No live database required.
- **Alternative:** `scripts/generate_toon_files.py` generates from live DB (requires DB connection); use when schema has been applied.
- Cursor may read TOON files as the schema oracle; they must match the install SQL.

This rule is permanent and applies to all future refactors.
