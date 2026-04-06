---
lupopedia.init:
  file_identity: "database-logic-prohibition-doctrine.md"
  artifact_type: "cascade_rule"
  artifact_kind: "doctrine"
  namespace: "cascade"
  system_version: "4.0.76"
  orchestrator_actor: "cascade"
  delegation_chain: "cascade:captain"

lupopedia.headers:
  actor_id: 105
  actor_name: "cascade"
  delegation_chain: "cascade:captain"
  lupopedia.version: "4.0.76"
  lupopedia.schema: "cascade_rule"
  file_path_from_root: ".cascade/rules/database-logic-prohibition-doctrine.md"
  last_modified_utc: "20260406"
  system_version: "4.0.76"
  source_path: "lupo-rules/root/database-logic-prohibition-doctrine.md"
  artifact_type: "rule"
  artifact_kind: "cascade_doctrine"
  purpose: "Cascade-specific rule derived from canonical root rule"

lupopedia.rules:
  comment: "Rule declaration and provenance block"
  declares:
    - rule_id: "DB001"
      rule_text: "Mandatory prohibition of database-side logic; all logic in application code"
      scope: "all_agents"
      category: "database"
      status: "active"
  imports: []
  overrides: []
  provenance:
    authored_by: "wolfie"
    authored_date: "20260406"
    last_reviewed_by: "cascade"
    last_reviewed_date: "20260406"
    version: "1.0"
    status: "active"
lupopedia.footer:
  version: "4.0.76"
  last_verified: "20260406"
  last_verified_by: "cascade"
  orchestrator: "cascade"
  next_action:
    - "Keep in sync with canonical root rules"
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

