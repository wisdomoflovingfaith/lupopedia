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

| Location | Extension | Format | Description |
|----------|-----------|--------|-------------|
| **`lupo-database/lupopedia/json/`** | `.json` | **JSON** | One file per table: `<table_name>.json`. Standard JSON; same logical content as the TOON file. |
| **`lupo-database/lupopedia/toon/`** | `.toon` | **TOON (YAML)** | One file per table: `<table_name>.toon`. TOON format is YAML; human-readable, same logical content as the JSON file. |

Both directories are under the project root. Example:

- `lupo-database/lupopedia/json/lupo_actors.json` — JSON format
- `lupo-database/lupopedia/toon/lupo_actors.toon` — TOON (YAML) format

## Generating TOONs

TOONs (and the matching JSON files) are generated from the **live database** by:

```bash
python scripts/generate_toon_files.py
```

Requirements:

- Python 3 with `pymysql`
- Database credentials from `lupopedia-config.php` (project root)
- A running MySQL/MariaDB instance with the Lupopedia schema

The script:

1. Connects to the database and introspects all tables (`SHOW TABLES`, `SHOW FULL COLUMNS`, `SHOW INDEX`).
2. Writes **`lupo-database/lupopedia/json/<table_name>.json`** for each table (JSON format).
3. Writes **`lupo-database/lupopedia/toon/<table_name>.toon`** for each table (TOON format: YAML). Requires the `pyyaml` package.

To skip canonical data (e.g. when DB is unavailable for data fetch but schema is available elsewhere):

```bash
SKIP_DB=1 python scripts/generate_toon_files.py
```

(Note: the script still requires a DB connection for schema introspection unless modified.)

## Converting JSON to TOON format

If you have JSON-format files in `lupo-database/lupopedia/json/` (`.json`, `.toon.json`, or `.toon`), you can convert them into TOON (YAML) format in the toon directory:

```bash
python scripts/convert_json_to_toon.py
```

This reads each file as JSON and writes `lupo-database/lupopedia/toon/<table_name>.toon` in YAML (TOON format). Requires `pyyaml`.

## DDL-sensitive workflow (4.0.67)

Before making **count-sensitive assertions** in docs or code (e.g. table ceiling, “current table count”), regenerate authoritative TOONs and use the generated count:

```bash
sh scripts/validate_schema_toons.sh
```

This runs `python scripts/generate_toon_files.py` and prints the **canonical table count** (number of `.toon` files). Use that count in documentation; do not hardcode. Invoke this after schema changes or when validating install/upgrade.

## Relation to doctrine and schema

- **Install SQL:** The canonical DDL is in `lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql` (or project-equivalent). TOONs are generated from the live DB and reflect the current structure.
- **Doctrine:** TOONs do not replace doctrine (e.g. no foreign keys, no triggers, BIGINT timestamps). They document the current schema; doctrine documents the rules. See [docs/doctrine/](doctrine/) and the migration doctrine.
- **Validation:** Scripts such as `verify_db_against_toons.py` can compare the live database to TOONs to detect drift.

## Summary

| Question | Answer |
|----------|--------|
| What are TOONs? | Representations of the database structure (tables, columns, indexes, optional canonical data). |
| Where are they? | `lupo-database/lupopedia/json/*.json` and `lupo-database/lupopedia/toon/*.toon`. |
| What are they for? | Documenting and validating database structure; tooling and migrations. |
| How are they created? | `python scripts/generate_toon_files.py` from the live database. |
| JSON vs TOON format? | `json/*.json` = JSON; `toon/*.toon` = TOON format (YAML). Same logical content. |

**Note:** Some legacy documentation or scripts may still reference `docs/toons/` or the `.toon.json` extension. The canonical output of `generate_toon_files.py` is `lupo-database/lupopedia/json/*.json` (JSON) and `lupo-database/lupopedia/toon/*.toon` (YAML).
