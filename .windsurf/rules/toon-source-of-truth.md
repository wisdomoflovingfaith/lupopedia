---
lupopedia.init:
  file_identity: "toon-source-of-truth.md"
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
  file_path_from_root: ".windsurf/rules/toon-source-of-truth.md"
  last_modified_utc: "20260411"
  system_version: "4.0.76"
  source_path: "lupo-rules/root/toon-source-of-truth.md"
  artifact_type: "rule"
  artifact_kind: "windsurf_doctrine"
  purpose: "Windsurf-specific rule derived from canonical root rule"

lupopedia.rules:
  comment: "Rule declaration and provenance block"
  declares:
    - rule_id: "DB007"
      rule_text: "TOON files are derived from canonical schema; canonical schema comes from install SQL"
      scope: "all_agents"
      category: "schema"
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

# file: Rule — TOON Files and Canonical Schema — session: L-LUPO-WOLFIE — delegation: wolfie:root — web_path: http://www.lupopedia.com/rules/root/toon-source-of-truth

# TOON Files and Canonical Schema (PERMANENT)

Cursor MUST treat **install_new_lupopedia.sql** and **seed_lupopedia.sql** as the single source of truth for all table and column definitions. TOON files are regenerated from this canonical schema.

4.0.88 enforcement extension:

- No schema guessing is allowed.
- Agents must inspect TOON exports first, then confirm with table docs.
- JSON exports are secondary; TOON is the primary schema inspection surface for agent work.

## Canonical schema source (4.0.15+)

- **lupo-database/migrations/install_new_lupopedia.sql** — Full table definitions (CREATE TABLE).
- **lupo-database/migrations/seed_lupopedia.sql** — Seeded tables and row structure.
- **TOON files:** `lupo-docs/toons/` — Regenerated from install SQL via `lupo-scripts/generate_toon_from_sql.py`. One file per table: `<table_name>.toon.json`.
- **Every** table name, column name, column type, index, and key MUST match the canonical schema. TOONs reflect the install SQL; they are not independently authoritative until regenerated.
- Runtime TOON export path in current architecture: `lupo-database/lupopedia/toon/`.
- Secondary JSON export path: `lupo-database/lupopedia/json/`.

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

- Before implementing schema-dependent changes, agents must review table docs under `lupo-docs/database/lupopedia/tables/`.
- If TOON and table docs diverge, escalate and reconcile; do not guess or infer silently.

## Verification

- Confirm **all** schema usage in code matches TOON definitions exactly.
- Report all **mismatches** between:
  - TOON schema
  - install SQL (e.g. `lupo-database/migrations/install_new_lupopedia.sql`)
  - actual code usage
- Include table documentation mismatch findings when present.

## TOON generation (4.0.15+)

- **Canonical:** TOONs are regenerated from `install_new_lupopedia.sql` via `lupo-scripts/generate_toon_from_sql.py`. No live database required.
- **Alternative:** `lupo-scripts/generate_toon_files.py` generates from live DB (requires DB connection); use when schema has been applied.
- Cursor may read TOON files as the schema oracle; they must match the install SQL.

This rule is permanent and applies to all future refactors.

