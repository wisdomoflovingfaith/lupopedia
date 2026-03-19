---
lupopedia.headers:
  actor_id: 1
  actor_name: "wolfie"
  delegation_chain: "wolfie:root"
  lupopedia.version: "4.0.73"
  lupopedia.schema: "rule"
  file_path_from_root: "lupo-channels/42/content/federation_node_id/0/NO_INFORMATION_SCHEMA_RULE.md"
  web_path: "http://www.lupopedia.com/lupo-channels/42/content/federation_node_id/0/NO_INFORMATION_SCHEMA_RULE.md"
  last_modified_utc: "20260310"
  system_version: "4.0.68"
  rule_id: 1002
  rule_name: "No Information Schema Queries"
  rule_type: "constraint"
  artifact_type: "rule"
  artifact_kind: "database_constraint"
  purpose: "Never use information_schema queries — use SHOW TABLES and TOON files instead"
  tags: ["rule", "database", "constraint", "information_schema"]

lupopedia.rule:
  evaluation:
    type: "code_review"
    forbidden_patterns:
      - "information_schema"
      - "INFORMATION_SCHEMA"
    allowed_alternatives:
      - "SHOW TABLES"
      - "SHOW CREATE TABLE"
      - "TOON files"
  description: "Database queries must never use information_schema tables"
  failure_message: "information_schema queries are forbidden on shared hosts"

lupopedia.edges:
  outbound_edges:
    - { to: "lupo-docs/doctrine/DATABASE_DOCTRINE.md", type: "references", weight: 1.0 }
    - { to: "lupo-scripts/generate_toon_files.py", type: "references", weight: 1.0 }
    - { to: "lupo-includes/classes/ToonValidator.php", type: "references", weight: 0.9 }
  # See Also paths are from project root; resolve with LUPOPEDIA_PROJECT_ROOT (lupo-config/global_atoms.yaml)
  see_also_from_root:
    - "lupo-docs/doctrine/DATABASE_DOCTRINE.md"
    - "lupo-scripts/generate_toon_files.py"
    - "lupo-includes/classes/ToonValidator.php"

lupopedia.footer:
  version: "4.0.68"
  last_verified: "20260310"
  last_verified_by: "wolfie"
---
# file: Rule: No Information Schema Queries — session: L-LUPO-WOLFIE — delegation: wolfie:antigravity:captain — web_path: http://www.lupopedia.com/channels/42/rules/no-information-schema

## Description

Database queries must never use `information_schema` tables. Lupopedia runs on shared hosting environments where `information_schema` may not be accessible.

## Allowed Alternatives

### 1. SHOW TABLES
```sql
SHOW TABLES;
SHOW TABLES LIKE 'lupo_%';
```

### 2. SHOW CREATE TABLE
```sql
SHOW CREATE TABLE lupo_actors;
```

### 3. TOON Files
Generated schema definitions from `lupo-scripts/generate_toon_files.py`:

```bash
# Generate TOON files from live database
python lupo-scripts/generate_toon_files.py

# Use TOON files for validation
cat lupo-database/lupopedia/toon/lupo_actors.toon.json
```

## Enforcement

This rule is enforced by:

- Code review (forbidden patterns: `information_schema`, `INFORMATION_SCHEMA`)
- `RuleEvaluator` with TOON-based validation
- Pre-commit hooks scanning for `information_schema`

## See Also

Paths below are from **project root**. Resolve with `LUPOPEDIA_PROJECT_ROOT` in `lupo-config/global_atoms.yaml` (e.g. `C:/ServBay/www/servbay/lupopedia`), or use the links (relative from this file).

- [lupo-docs/doctrine/DATABASE_DOCTRINE.md](../../../../lupo-docs/doctrine/DATABASE_DOCTRINE.md)
- [lupo-scripts/generate_toon_files.py](../../../../lupo-scripts/generate_toon_files.py)
- [lupo-includes/classes/ToonValidator.php](../../../../lupo-includes/classes/ToonValidator.php)
