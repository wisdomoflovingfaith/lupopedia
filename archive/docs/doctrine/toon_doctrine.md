---
lupopedia.headers:
  header_format_version: "4.1.0"
  file_path_from_root: "docs/TOON_REFERENCE.md"
  web_path: "http://www.lupopedia.com/TOON_REFERENCE"
  status: ""
  when_updated: null
  trust_tier: null
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: documentation
  artifact_kind: reference
  channel_key: null
  federation_node_id: null
  thread_id: ""
  content_id: null
  pk_id: null
  pk_slug: ""
  parent_pk_id: ""
  lupopedia.schema: documentation
  title: "TOON REFERENCE"
  summary: ""
---
# file: TOON REFERENCE — delegation: cursor:root — web_path: http://www.lupopedia.com/TOON_REFERENCE

# TOON Reference — Database Structure Representation

TOONs are **canonical representations of the Lupopedia database structure**. They describe tables, columns, indexes, and (optionally) canonical row data. TOONs are used to document and validate schema, drive tooling, and keep the codebase aligned with the database without querying the live DB.

## What TOONs are

- **Structure snapshot:** Each TOON file corresponds to one database table and contains:
  - `table_name` — name of the table
  - `fields` — column definitions (name, type, nullability, default, comment)
  - `indexes` — non-primary indexes
  - `primary_key` — primary key column (when present)
  - `data` — optional canonical rows (e.g. PK=0 row, or registry rows)
  - `doctrine_metadata` — e.g. no_foreign_keys, no_triggers for `lupo_*` tables

- **Purpose:** They define **what the database structure is** for documentation, migrations, and validation. Code and scripts should treat TOONs as the schema reference; the live database is the runtime source of truth, but TOONs are the documented structure.

## Where TOONs live

Two workflows exist; both are valid. The **in-repo committed set** used for documentation and validation is **`database/lupopedia/toon/*.toon.json`**.

| Location | Extension | Format | Source | Description |
|----------|-----------|--------|--------|-------------|
| **`database/lupopedia/toon/`** | `.toon.json` | **JSON** | `generate_toon_from_sql.py` (from install SQL) | In-repo TOON set; no live DB required. Canonical for docs and schema-from-install. |
| **`database/lupopedia/json/`** | `.json` | **JSON** | `generate_toon_files.py` (from live DB) | One file per table; same logical content as .toon. |
| **`database/lupopedia/toon/`** | `.toon` | **TOON (YAML)** | `generate_toon_files.py` (from live DB) | One file per table; YAML format. |

Examples:

- `database/lupopedia/toon/lupo_actors.toon.json` — from install SQL (no DB); used in repo for schema reference.
- `database/lupopedia/json/lupo_actors.json` — from live DB when script is run.
- `database/lupopedia/toon/lupo_actors.toon` — from live DB when script is run (YAML).

## Generating TOONs

**Option A — From install SQL (no live DB):** Refreshes the in-repo TOON set in `database/lupopedia/toon/`.

```bash
python scripts/generate_toon_from_sql.py
```

- Reads: `database/lupopedia/mysql/install/install_new_lupopedia.sql`
- Writes: `database/lupopedia/toon/<table_name>.toon.json` for each table in the install file.
- Use this to align TOONs with schema after editing install SQL (e.g. after adding `lupo_projects`).

**Option B — From live database:** Writes to `database/lupopedia/` (json + toon).

```bash
python scripts/generate_toon_files.py
```

Requirements:

- Python 3 with `pymysql`
- Database credentials from `lupopedia-config.php` (project root)
- A running MySQL/MariaDB instance with the Lupopedia schema

The script:

1. Connects to the database and introspects all tables (`SHOW TABLES`, `SHOW FULL COLUMNS`, `SHOW INDEX`).
2. Writes **`database/lupopedia/json/<table_name>.json`** for each table (JSON format).
3. Writes **`database/lupopedia/toon/<table_name>.toon`** for each table (TOON format: YAML). Requires the `pyyaml` package.

To skip canonical data (e.g. when DB is unavailable for data fetch but schema is available elsewhere):

```bash
SKIP_DB=1 python scripts/generate_toon_files.py
```

(Note: the script still requires a DB connection for schema introspection unless modified.)

## Converting JSON to TOON format

If you have JSON-format files in `database/lupopedia/json/` (`.json`, `.toon.json`, or `.toon`), you can convert them into TOON (YAML) format in the toon directory:

```bash
python scripts/convert_json_to_toon.py
```

This reads each file as JSON and writes `database/lupopedia/toon/<table_name>.toon` in YAML (TOON format). Requires `pyyaml`.

## AI Token Optimization (Why TOON YAML is preferred)

When developing with multi-agent IDE orchestration, **TOON (YAML) format is strongly preferred over JSON**.
- **Token Efficiency:** YAML drops the heavy bracket (`{`, `[`) and quotation (`"`) syntax required by strict JSON. This significantly reduces the total token count and overhead when analyzing the database structures inside an AI context window.
- **Agent Tooling Fallbacks:** While many multi-agent tooling chains and parsers inherently gravitate toward JSON due to standard native execution expectations (which is an acceptable fallback during automation), providing the YAML `.toon` format as context yields far more optimal and focused prompt interactions.

## DDL-sensitive workflow (4.0.67)

Before making **count-sensitive assertions** in docs or code (e.g. table ceiling, “current table count”), regenerate TOONs and use the generated count, or derive from install SQL. (Table ceiling is advisory only per Captain directive 4.0.74.)

```bash
sh scripts/validate_schema_toons.sh
```

This runs `python scripts/generate_toon_files.py` and prints the **canonical table count** (number of `.toon` files). Use that count in documentation; do not hardcode. Invoke this after schema changes or when validating install/upgrade.

## Relation to table documentation

Per-table documentation (purpose, columns, usage) lives in **`docs/database/lupopedia/tables/active/`** (and `tables/deprecated/` for deprecated tables). Each table doc references its TOON file in `database/lupopedia/toon/` via a schema_reference edge. See AGENTS.md and the table docs for the full edge graph.

## Relation to doctrine and schema

- **Install SQL is the canonical schema authority.** The canonical DDL is in `database/lupopedia/mysql/install/install_new_lupopedia.sql` (or project-equivalent). TOON artifacts are **derived representations** and must defer to install SQL when discrepancies exist. TOONs may be generated from the live DB (generate_toon_files.py) or from install SQL (e.g. generate_toon_from_sql.py) and reflect structure at generation time.
- **Doctrine:** TOONs do not replace doctrine (e.g. no foreign keys, no triggers, BIGINT timestamps). They document the current schema; doctrine documents the rules. See [docs/doctrine/](doctrine/) and the migration doctrine.
- **Validation:** Scripts such as `verify_db_against_toons.py` can compare the live database to TOONs to detect drift.

## Summary

| Question | Answer |
|----------|--------|
| What are TOONs? | Representations of the database structure (tables, columns, indexes, optional canonical data). |
| Where are they? | `database/lupopedia/json/*.json` and `database/lupopedia/toon/*.toon`. |
| What are they for? | Documenting and validating database structure; tooling and migrations. |
| How are they created? | `python scripts/generate_toon_files.py` from the live database. |
| JSON vs TOON format? | `json/*.json` = JSON; `toon/*.toon` = TOON format (YAML). Same logical content. |

**Note:** The in-repo committed TOON set is **`database/lupopedia/toon/*.toon.json`**, produced by `generate_toon_from_sql.py` from install SQL. The script `generate_toon_from_sql.py` was aligned in 4.0.74 to read from `database/lupopedia/mysql/install/install_new_lupopedia.sql` and write to `database/lupopedia/toon/`. Legacy references to `docs/toons/` are path drift.
