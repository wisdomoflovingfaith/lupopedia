# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: ".\docs\TOON_SOURCE_OF_TRUTH_AUDIT.md"
  file_hash: "5797a899d4e0ac4831364c706329063142dbbdfc7d19a70f86252309160c31fd"
  last_updated_utc: "20260228155738"
  system_version: "4.0.51"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "documentation"
  artifact_kind: "documentation"
  purpose: "Documentation file with LUPOPEDIA HEADERS applied"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.51"]
  tags: ["documentation", "flare_applied"]
  lupo_agent: "windsurf"

lupopedia.edges:
  outbound_edges:
    - { to: "CHANGELOG.md", type: "references", weight: 1.0 }
    - { to: "lupo-docs/doctrine/", type: "references", weight: 1.0 }

lupopedia.footer:
  last_verified: "20260228155738"
  last_verified_by: "windsurf"
---

# LUPOPEDIA HEADERS (replaces FLARE)

---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  lupopedia.edges: []
  file_path_from_root: "lupo-docs\TOON_SOURCE_OF_TRUTH_AUDIT.md"
  file_hash: "091915018430f39027da0cc24dd2d7e2c7e2c9317dbdb3378f9ffd7f25067e91"
  file_path_from_root: "lupo-docs\TOON_SOURCE_OF_TRUTH_AUDIT.md"
  file_hash: "265d0fff45fc4d888a330ea3ae6f38984da5bc7835d63114822843e021b6ff9b"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "TOON Source of Truth — Audit and Mismatch Reporting"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "toon_source_of_truth_auditmd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
lupopedia.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

# TOON Source of Truth — Audit and Mismatch Reporting

**Rule:** `.cursor/rules/toon-source-of-truth.mdc` — TOON files (`lupo-docs/toons/*.toon.json`) are the only source of truth for schema.

## When TOONs exist

1. **Read TOONs first.** Before writing or changing any code that touches the database, read the relevant `<table_name>.toon.json` files in `lupo-docs/toons/` for table names, column names, types, indexes, and keys.
2. **Do not invent schema.** If a table or column is not in the TOONs, do not add it to code; refactor code to match TOON schema or add it to the database and regenerate TOONs via `lupo-scripts/generate_toon_files.py` (per GOV-TOON-GENERATION-001).
3. **Mismatch reporting.** When asked, or when doing schema-related refactors, report:
   - **TOON vs install SQL:** Tables/columns in `lupo-database/migrations/install_new_lupopedia.sql` that are not in TOONs, or in TOONs with different types/keys.
   - **TOON vs code:** Code that references tables or columns not present in TOONs, or uses wrong names/types.
   - **DB vs TOON:** Tables/columns in the database (or in migrations) that are not in TOONs — such references must be removed from code.

## When TOONs do not exist yet

- If `lupo-docs/toons/` is empty or missing, schema authority falls back to `lupo-database/migrations/install_new_lupopedia.sql` and any approved migration SQL in `lupo-database/migrations/`.
- After TOONs are generated (`python lupo-scripts/generate_toon_files.py`), the TOON-source-of-truth rule applies: all schema usage must match TOON definitions.

## Migration files (TOON-aligned)

- If the **database** is missing a table or column that **exists in the TOONs**, Cursor may add a one-time migration file (e.g. `migration_add_missing_columns.sql`, `migration_add_missing_tables.sql`) that is idempotent and safe.
- If **code** references a table or column that **does not exist in the TOONs**, Cursor must not add it to the schema; it must refactor the code to remove or replace the reference.

## Related

- **TOON generation:** `lupo-docs/channels/dev-teams/governance/GOV-TOON-GENERATION-001.md` — only `lupo-scripts/generate_toon_files.py` may create or update TOON files.
- **Doctrine alignment:** `lupo-scripts/check_toon_doctrine_alignment.py` — checks TOONs for doctrine (no UNSIGNED, no timestamp/datetime, etc.).
- **Seed from TOONs:** `lupo-scripts/generate_seed_from_toons.py` — uses TOONs as input for seed SQL.
