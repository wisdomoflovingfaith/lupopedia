# TOON Source of Truth — Audit and Mismatch Reporting

**Rule:** `.cursor/rules/toon-source-of-truth.mdc` — TOON files (`docs/toons/*.toon.json`) are the only source of truth for schema.

## When TOONs exist

1. **Read TOONs first.** Before writing or changing any code that touches the database, read the relevant `<table_name>.toon.json` files in `docs/toons/` for table names, column names, types, indexes, and keys.
2. **Do not invent schema.** If a table or column is not in the TOONs, do not add it to code; refactor code to match TOON schema or add it to the database and regenerate TOONs via `scripts/generate_toon_files.py` (per GOV-TOON-GENERATION-001).
3. **Mismatch reporting.** When asked, or when doing schema-related refactors, report:
   - **TOON vs install SQL:** Tables/columns in `database/migrations/install_new_lupopedia.sql` that are not in TOONs, or in TOONs with different types/keys.
   - **TOON vs code:** Code that references tables or columns not present in TOONs, or uses wrong names/types.
   - **DB vs TOON:** Tables/columns in the database (or in migrations) that are not in TOONs — such references must be removed from code.

## When TOONs do not exist yet

- If `docs/toons/` is empty or missing, schema authority falls back to `database/migrations/install_new_lupopedia.sql` and any approved migration SQL in `database/migrations/`.
- After TOONs are generated (`python scripts/generate_toon_files.py`), the TOON-source-of-truth rule applies: all schema usage must match TOON definitions.

## Migration files (TOON-aligned)

- If the **database** is missing a table or column that **exists in the TOONs**, Cursor may add a one-time migration file (e.g. `migration_add_missing_columns.sql`, `migration_add_missing_tables.sql`) that is idempotent and safe.
- If **code** references a table or column that **does not exist in the TOONs**, Cursor must not add it to the schema; it must refactor the code to remove or replace the reference.

## Related

- **TOON generation:** `docs/channels/dev-teams/governance/GOV-TOON-GENERATION-001.md` — only `scripts/generate_toon_files.py` may create or update TOON files.
- **Doctrine alignment:** `scripts/check_toon_doctrine_alignment.py` — checks TOONs for doctrine (no UNSIGNED, no timestamp/datetime, etc.).
- **Seed from TOONs:** `scripts/generate_seed_from_toons.py` — uses TOONs as input for seed SQL.
