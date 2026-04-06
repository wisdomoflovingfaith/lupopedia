---
lupopedia.init:
  orchestrator_actor: "any"
  rule_set_version: "4.0.74+"
  applies_to: ["audit", "code-gen", "db-sync", "migration", "header-sync"]
  enforcement: strict

lupopedia.metadata:
  comment: "Snapshot of metadata for this file or entity at artifact creation."

lupopedia.headers:
  author:
    type: "actor"
    id: 1
    name: "wolfie"
  delegation_chain: "wolfie:root"
  lupopedia.version: "4.0.74"
  lupopedia.schema: "cursor_rule"
  file_path_from_root: "lupo-rules/root/database-logic-prohibition-doctrine.md"
  web_path: "http://www.lupopedia.com/rules/root/database-logic-prohibition-doctrine"
  last_modified_utc: "20260406044907"
  system_version: "4.0.74"
  rule_name: "Database Logic Prohibition Doctrine"
  rule_type: "constraint"
  artifact_type: "rule"
  artifact_kind: "cursor_doctrine"
  purpose: "Mandatory prohibition of database-side logic; all logic in application code"
  tags: ["cursor", "database", "doctrine", "constraint"]
  source_path: ".cursor/rules/database-logic-prohibition-doctrine.mdc"

lupopedia.rules:
  comment: "Rule declaration and provenance block"
  declares:
    - rule_id: "DB001"
      rule_text: "Mandatory prohibition of database-side logic; all logic in application code"
      scope: "all_agents"
      category: "database"
  imports: []
  overrides: []
  provenance:
    authored_by: "wolfie"
    authored_date: "20260314"
    last_reviewed_by: "antigravity"
    last_reviewed_date: "20260314"
    version: "1.0"
    status: "active"
lupopedia.footer:
  approved_for_version: "4.1.0"
  approved_for_version_utc: "20260327103238"
  approved_for_version_by: "Cursor IDE Agent (Lead Orchestration)"
  approved_for_version_by_actor_id: 102
  version: "4.0.74"
  last_verified: "20260313"
  last_verified_by: "wolfie"
  orchestrator: "cursor"
  next_action:
    - "Keep in sync with .cursor/rules/database-logic-prohibition-doctrine.mdc"
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
