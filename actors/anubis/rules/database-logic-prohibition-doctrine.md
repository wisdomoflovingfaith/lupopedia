---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: actors/anubis/rules/database-logic-prohibition-doctrine.md
  web_path: https://www.lupopedia.com/lupopedia/actors/anubis/rules/database-logic-prohibition-doctrine.md
  status: null
  when_updated: '20260513033046'
  trust_tier: null
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: rule
  artifact_kind: cursor_doctrine
  channel_key: null
  federation_node_id: null
  thread_key: null
  lupopedia.schema: cursor_rule
  prd_cluster: null
  title: null
  summary: null
---
# file: Rule — Database Logic Prohibition Doctrine — session: L-LUPO-WOLFIE — delegation: wolfie:root — web_path: http://www.lupopedia.com/rules/root/database-logic-prohibition-doctrine

# Database Logic Prohibition Doctrine (MANDATORY)

Cursor must NEVER suggest or generate any database-side logic. All logic belongs in application code, not in the database. This doctrine is absolute and non-negotiable.

## Never generate or suggest

- **FOREIGN KEY** constraints
- **REFERENCES** clauses
- **ON DELETE** / **ON UPDATE** actions
- **TRIGGER** definitions (BEFORE/AFTER INSERT/UPDATE/DELETE)
- Stored procedures
- Stored functions
- Events or scheduled tasks
- Generated / virtual / computed columns
- **DEFAULT CURRENT_TIMESTAMP** or **ON UPDATE CURRENT_TIMESTAMP**
- Any automatic timestamp behavior

## Timestamps

All timestamps MUST be written explicitly in application code.

- NEVER rely on database automation for timestamps.
- NEVER generate schema that mutates timestamps automatically.

**Correct (application-side):** `updated_ymdhis = date('YmdHis')`  
**Forbidden:** `updated_ymdhis TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP`

## Data integrity and relations

All data integrity, cascading behavior, and relational enforcement MUST be implemented in application code, NOT in the database.

## Existing violations

If existing schema contains FK, triggers, procedures, or functions:

- Do NOT propagate them.
- Warn in console output.
- Propose removal.
- Generate doctrine-aligned SQL without them.

Treat any suggestion of FK, triggers, procedures, or DB logic as a violation and correct it automatically.

This doctrine overrides all framework defaults, ORM conventions, SQL best-practice heuristics, and generic code generation patterns. It is mandatory for all schema, TOON, seed, and migration work.
