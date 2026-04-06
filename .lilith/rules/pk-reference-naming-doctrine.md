---
lupopedia.headers:
  actor_id: 2
  actor_name: "lilith"
  delegation_chain: "lilith:root"
  lupopedia.version: "4.0.79"
  lupopedia.schema: "lilith_rule"
  file_path_from_root: ".lilith/rules/pk-reference-naming-doctrine.md"
  last_modified_utc: "20260406"
  system_version: "4.0.79"
  source_path: "lupo-rules/root/pk-reference-naming-doctrine.md"
  artifact_type: "rule"
  artifact_kind: "lilith_doctrine"
  purpose: "Lilith-specific review and dissent rule derivative"
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

