---
lupopedia.init:
  file_identity: "pk-reference-naming-doctrine.md"
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
  file_path_from_root: ".cascade/rules/pk-reference-naming-doctrine.md"
  last_modified_utc: "20260411"
  system_version: "4.0.76"
  source_path: "lupo-rules/root/pk-reference-naming-doctrine.md"
  artifact_type: "rule"
  artifact_kind: "cascade_doctrine"
  purpose: "Cascade-specific rule derived from canonical root rule"

lupopedia.rules:
  comment: "Rule declaration and provenance block"
  declares:
    - rule_id: "DB004"
      rule_text: "Mandatory primary key and reference key naming doctrine for Lupopedia schema"
      scope: "all_agents"
      category: "schema"
      status: "active"
  imports: []
  overrides: []
  provenance:
    authored_by: "wolfie"
    authored_date: "20260411"
    last_reviewed_by: "cascade"
    last_reviewed_date: "20260411"
    version: "1.0"
    status: "active"
lupopedia.footer:
  version: "4.0.76"
  last_verified: "20260411"
  last_verified_by: "cascade"
  orchestrator: "cascade"
  next_action:
    - "Keep in sync with canonical root rules"
---

# file: Rule — Primary Key & Reference Naming Doctrine — session: L-LUPO-WOLFIE — delegation: wolfie:root — web_path: http://www.lupopedia.com/rules/root/pk-reference-naming-doctrine

# Primary Key & Reference Naming Doctrine (MANDATORY)

Cursor MUST enforce explicit, doctrine-aligned naming for all primary keys and all reference keys. Ambiguous column names such as `id` are forbidden.

## Primary keys

**RULE:** Primary keys MUST be named:

```
<singular_table_name>_id
```

- NEVER create a primary key named `id`.
- NEVER shorten or rename a primary key to `id`.
- Infer the singular form of the table name and append `_id`.

**Examples:**

| Table                     | Primary key column      |
|---------------------------|-------------------------|
| lupo_dialog_messages      | dialog_message_id       |
| lupo_sessions             | session_id              |
| lupo_actors               | actor_id                |
| lupo_crafty_syntax_logs   | crafty_syntax_log_id    |
| lupo_visits               | visit_id                |

## Reference keys

**RULE:** Reference keys MUST use the exact same column name as the primary key of the table they reference.

- If a table's PK is `actor_id`, every table that references actors MUST use `actor_id` (NOT `id`, NOT `actors_id`, NOT `fk_actor`, etc.).
- NEVER generate mismatched or ambiguous reference names such as `id`, `ref_id`, `parent_id`, or generic `table_id` unless the referenced table is literally named that.

## When to apply

Apply this doctrine when:

- Creating new tables (including tables reconstructed from TOONs)
- Install SQL is missing the table
- TOON is missing the table
- Table has no prior schema or is being self-healed

## Violations and drift

- If an existing table violates this doctrine, do NOT propagate the violation. Instead:
  - Warn in the console output
  - Propose a doctrine-aligned correction
  - Use the correct PK name when generating TOONs or seed data
- Treat ambiguous PKs as schema drift and correct them automatically using this doctrine.

This doctrine is mandatory and overrides framework defaults, ORM heuristics, and generic SQL generation patterns.

