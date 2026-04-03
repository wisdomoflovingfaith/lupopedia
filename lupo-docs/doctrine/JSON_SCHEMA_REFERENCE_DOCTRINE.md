---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: doctrine
  file_path_from_root: "lupo-docs/doctrine/JSON_SCHEMA_REFERENCE_DOCTRINE.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/doctrine/JSON_SCHEMA_REFERENCE_DOCTRINE.md"
  last_modified_utc: "20260403113047"
  when_updated: "20260403113047"
  federation_node_id: 0
  channel_id: 42
  thread_id: "doctrine-header-repair"
  actor_id: 102
  actor_name: "cursor"
  delegation_chain: "cursor:root"
  artifact_type: "doctrine"
  artifact_kind: "reference"
  purpose: "JSON SCHEMA REFERENCE DOCTRINE"
  status: active
  tags:
    - "doctrine"
    - "header_repair"
lupopedia.edges:
  outbound_edges:
    - to: "lupo-docs/prd/26_five_layer_documentation_architecture.md"
      type: implements
      weight: 1.0
      reason: "Doctrine PRD lineage; constitutional audit 20260403"

lupopedia.footer:
  last_verified: "20260403113047"
  verified_by:
    identity_type: actor
    actor_id: 2
    name: "lilith"
  verified_via:
    type: "audit"
    script: "fix_doctrine_headers"
  next_action:
    - "Run: python lupo-scripts/apply_doctrine_prd_lineage.py --apply"
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
