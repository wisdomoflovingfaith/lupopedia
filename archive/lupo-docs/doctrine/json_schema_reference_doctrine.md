---
lupopedia.headers:
  header_format_version: "4.1.0"
  file_path_from_root: "lupo-docs/doctrine/JSON_SCHEMA_REFERENCE_DOCTRINE.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/doctrine/JSON_SCHEMA_REFERENCE_DOCTRINE.md"
  status: "active"
  when_updated: "20260403113047"
  trust_tier: null
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: doctrine
  artifact_kind: reference
  channel_key: null
  federation_node_id: 0
  thread_id: "doctrine-header-repair"
  content_id: null
  pk_id: null
  pk_slug: ""
  parent_pk_id: ""
  lupopedia.schema: doctrine
  title: ""
  summary: ""
---
# file: JSON_SCHEMA_REFERENCE_DOCTRINE — delegation: cursor:root

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
