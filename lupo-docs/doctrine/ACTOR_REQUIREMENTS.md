# Actor Requirements — Doctrine Enforcement

Actors can declare **machine-readable system requirements** that the platform uses to enforce architectural constraints (e.g. database doctrine, ethical rules). These are not prompts; they are doctrine rules that affect code generation, schema design, and service behavior.

## Why actor requirements?

Kernel agents such as **LUPO** (Database Architect), **THEMIS** (Ethical Auditor), and **WOLFIE** (Captain) need to state non-negotiable constraints so that:

- Migrations and generated SQL can be validated before execution.
- Consensus workflows (e.g. Lilith → THEMIS → WOLFIE) can consult requirements when evaluating proposals.
- Documentation and tooling stay aligned with a single source of truth.

## Storage

Requirements are stored **without new schema**:

1. **`lupo_registry.metadata_json`** — For agents, the registry row’s `metadata_json` can include a `requirements` object. Example: LUPO’s agent registry entry (entity_type=agent, entity_index_id=106) holds database doctrine in `metadata_json.requirements`.
2. **Agent workspace** — Optional `requirements.json` (or `requirements` inside `agent.json`) in the agent’s directory under `lupo-agents/` or `lupo-actors/`. If present, `AgentService::getRequirements()` uses it first.

No separate table is required. No new columns were added to `lupo_agents`.

## Retrieval

- **`AgentService::getRequirements($actor_id)`**  
  Returns the decoded `requirements` structure for that actor (from workspace config or from `lupo_registry.metadata_json` for the matching agent row). Returns an empty array if none are defined.

- **`ActorRequirementsValidator`**  
  Uses LUPO’s requirements to validate SQL (e.g. migration scripts) and returns a list of violation messages. Use this in consensus or migration pipelines to reject proposals that violate database doctrine.

## LUPO database doctrine (encoded requirements)

LUPO’s requirements enforce the following (stored in registry seed for agent 106):

| Rule | Key | Effect |
|------|-----|--------|
| No foreign keys | `no_foreign_keys: true` | Reject SQL containing `FOREIGN KEY` or `REFERENCES` |
| No triggers | `no_triggers: true` | Reject SQL containing `TRIGGER` |
| No stored procedures | `no_procedures: true` | Reject SQL containing `PROCEDURE` |
| No stored functions | `no_functions: true` | Reject stored function definitions |
| Timestamp format | `timestamp_format: "BIGINT_UTC_YYYYMMDDHHIISS"` | All time fields BIGINT UTC in that format |
| No DATETIME/TIMESTAMP | `datetime_types_allowed: false` | Reject columns of type DATETIME or TIMESTAMP |
| Explicit column lists | `explicit_column_lists: true` | SQL must not rely on implicit column ordering |
| Application-level relationships | `application_level_relationships: true` | Relationships enforced in code, not in the DB |

PHP must generate timestamps with `gmdate('YmdHis')`. No ORM assumptions; PHP 5.3–compatible patterns only.

## Using requirements in consensus

When the system evaluates a database-related proposal (e.g. a migration):

1. Resolve LUPO’s requirements via `AgentService::getRequirements(106)` or `ActorRequirementsValidator::getLupoRequirements()`.
2. Run `ActorRequirementsValidator::validateSqlAgainstLupo($sql)` on the proposed SQL.
3. If the result is non-empty, the proposal fails LUPO’s doctrine; report the violation messages and do not apply the migration.

This keeps architecture doctrine not only in markdown but in machine-readable form so the system can enforce it automatically.

## Seed and registry

LUPO’s doctrine is seeded in **`lupo-database/lupopedia/mysql/seed/seed_registry_comprehensive_4.0.45.sql`**: the agent registry row for entity_index_id 106 includes a `requirements.database` block in `metadata_json`. No separate requirements table or column is used.

## Schema doctrine audit (LUPO)

The **canonical** schema audit tool checks **TOON files** in `lupo-database/lupopedia/toon/` (generated from the database) for doctrine violations. It does **not** use `information_schema` or a live DB connection for structure.

Checks: doctrine_metadata (no_foreign_keys, no_triggers), DATETIME/TIMESTAMP columns, BIGINT time-like columns, soft-delete (is_deleted).

- **PHP (canonical):** `php lupo-scripts/audit_schema_doctrine.php` — reads TOONs from `lupo-database/lupopedia/toon/` (fallback: `json/`), writes `artifacts/reports/schema_doctrine_audit.json`. Use this as the source of truth.
- **Python (optional):** `python lupo-scripts/audit_schema_doctrine.py` — same TOON-based checks and report path; supplemental (e.g. CI without PHP).

## Related

- Database doctrine: no FKs, no triggers, BIGINT UTC timestamps (see main database doctrine docs).
- `lupo-includes/classes/AgentService.php` — `getRequirements($actor_id)`.
- `lupo-includes/classes/ActorRequirementsValidator.php` — `validateSqlAgainstLupo($sql)`.
