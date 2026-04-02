# JSON Schema Reference Doctrine

## Purpose

JSON files in `lupo-database/lupopedia/json/` are **read-only schema references** for AI agents and tooling.

## Rules

### DO
- Read to confirm column names
- Read to verify table structure
- Read to understand indexes
- Read before writing any SQL

### DO NOT
- Write to these files (they are generated)
- Treat them as a file database (they contain no data)
- Guess column names without reading them first
- Assume schema without verification

## Generation

``bash
python lupo-scripts/generate_toon_files.py
``

This command reads the live database and regenerates all JSON schema files.

## Authority

| Source | Authority | Writable |
|--------|-----------|----------|
| Live database | HIGHEST | Yes (via application) |
| install_new_lupopedia.sql | HIGH (DDL) | Yes (via PR) |
| JSON schema files | LOW (reference) | NO (generated) |

## Violations

Any agent that:
- Writes to JSON schema files
- Treats them as a database
- Guesses column names without reading them

...is in violation of this doctrine and the WOLFIE Way.
