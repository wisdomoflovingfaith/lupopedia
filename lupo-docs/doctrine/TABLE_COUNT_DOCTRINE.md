---
lupopedia.init:
  required_reading:
    - path: "lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql"
      reason: "Canonical schema; table count is derived from this file"
    - path: "lupo-docs/TOON_REFERENCE.md"
      reason: "TOONs are derived from install SQL or live DB"
  required_context:
    - "Install SQL is the schema authority. Table count is advisory only (Captain directive 4.0.74)."

lupopedia.headers:
  lupopedia.version: "4.0.77"
  lupopedia.schema: "doctrine"
  file_path_from_root: "lupo-docs/doctrine/TABLE_COUNT_DOCTRINE.md"
  last_modified_utc: "20260316"
  system_version: "4.0.77"
  channel_id: 42
  actor_id: 102
  artifact_type: "doctrine"
  artifact_kind: "reference"
  purpose: "Canonical table count reference and advisory ceiling doctrine."

lupopedia.footer:
  version: "4.0.77"
  last_verified: "20260316"
  last_verified_by: "cursor"
  next_action:
    - "When adding tables, update install SQL first; regenerate TOONs; update this doc if count reference changes."
---
# file: TABLE_COUNT_DOCTRINE — canonical table count and ceiling

# Table Count Doctrine (4.0.77)

## Canonical table count

- **Source:** `lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql`
- **Method:** Count of `CREATE TABLE` statements in that file, or run `python lupo-scripts/generate_toon_files.py` and count TOON files in `lupo-database/lupopedia/toon/`.
- **Verified 2026-03-16 (4.0.77, post-install TOON regeneration):** **161** tables. After drop → load Crafty 3.7.5 baseline → run install → `python lupo-scripts/generate_toon_files.py`, TOON count is 161. Install file is the schema authority. Table ceiling remains advisory (e.g. 222); current count is within capacity.

Use this count when documenting "current number of tables" or when comparing install vs. TOON vs. live DB. Do not hardcode a table count in multiple places; derive from install SQL or from this doctrine.

## Install SQL authority

- **Install SQL** is the **canonical schema authority**. All table and column definitions are defined there.
- **TOONs** are **derived artifacts**. They are generated from install SQL (`generate_toon_from_sql.py`) or from the live database (`generate_toon_files.py`). TOONs do not override install SQL; when they disagree, install SQL wins.
- **Table count** for the project is the number of tables in the install file. Regenerate TOONs after schema changes so that TOONs reflect the current install.

## TOON derived status

- In-repo TOON set: `lupo-database/lupopedia/toon/*.toon.json` (from `generate_toon_from_sql.py`).
- Live-DB TOON output: `lupo-database/lupopedia/json/*.json` and `lupo-database/lupopedia/toon/*.toon` (from `generate_toon_files.py`).
- TOON count may differ from install table count if the script parses more or fewer `CREATE TABLE` blocks (e.g. regex vs. line count). Always treat **install SQL** as the source of truth for "how many tables exist in the schema."

## Advisory table ceiling

- **Table count / table ceiling is advisory only** (Captain directive 4.0.74). Schema expansion is permitted when justified.
- Do **not** block new tables solely on a numeric ceiling (e.g. 222 or any fixed number). Add tables when the feature and doctrine support them; update install SQL and this doctrine if the canonical count is cited.
- See also: [SYMBOL_OPERATOR_DOCTRINE.md](../channels/doctrine/SYMBOL_OPERATOR_DOCTRINE.md) (table ceiling wording).

## Summary

| Question | Answer |
|----------|--------|
| Where is table count defined? | Count of `CREATE TABLE` in `install_new_lupopedia.sql`. |
| What is the current count? | 161 (as of 2026-03-16; generate_toon_files.py post-install confirms). |
| Are TOONs authoritative? | No. TOONs are derived; install SQL is authoritative. |
| Is there a hard table limit? | No. Table ceiling is advisory only. |

---
*Cursor (actor_id 102) — TABLE_COUNT_DOCTRINE 2026-03-16*
