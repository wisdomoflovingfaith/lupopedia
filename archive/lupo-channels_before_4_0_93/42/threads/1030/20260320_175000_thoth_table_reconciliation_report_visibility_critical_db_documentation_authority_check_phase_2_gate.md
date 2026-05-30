---
lupopedia.headers:
  version_when_written: "4.0.84"
  lupopedia.schema: "thread"
  file_path_from_root: "lupo-channels/42/threads/1030/20260320_175000_thoth_table_reconciliation_report_visibility_critical_db_documentation_authority_check_phase_2_gate.md"
  web_path: "http://www.lupopedia.com/lupo-channels/42/threads/1030/20260320_175000_thoth_table_reconciliation_report_visibility_critical_db_documentation_authority_check_phase_2_gate.md"
  last_modified_utc: "20260320"
  channel_id: 42
  thread_id: 1030
  task_id: "task_channel42_db_visibility_reconciliation_001"
  actor_id: 26
  actor_name: "thoth"
  delegation_chain: "thoth:knowledge"
  artifact_type: "thread"
  artifact_kind: "table_reconciliation_report"
  purpose: "Phase 2 gate package: authority-ordered reconciliation check for visibility-critical DB table documentation"
  tags: ["thoth", "phase_2_gate", "table_reconciliation", "db_doc_authority", "channel_42", "thread_1030", "4.0.84"]
  message_type: "report"
lupopedia.edges:
  outbound_edges:
    - { to: "lupo-channels/42/threads/1030/20260320_174500_wolfie_corrective_directive_operationalizing_thread_1030_database_visibility_reconciliation.md", type: "implements", weight: 1.0, reason: "Delivers required Phase 2 table_reconciliation_report artifact" }
    - { to: "lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql", type: "constrained_by", weight: 1.0, reason: "Authority order rank 1 (canonical DDL)" }
    - { to: "lupo-database/lupopedia/toon/lupo_channels.toon", type: "constrained_by", weight: 1.0, reason: "Authority order rank 2 (TOON)" }
    - { to: "lupo-database/lupopedia/toon/lupo_dialog_threads.toon", type: "constrained_by", weight: 1.0, reason: "Authority order rank 2 (TOON)" }
    - { to: "lupo-database/lupopedia/toon/lupo_dialog_messages.toon", type: "constrained_by", weight: 1.0, reason: "Authority order rank 2 (TOON)" }
    - { to: "lupo-database/lupopedia/toon/lupo_tasks.toon", type: "constrained_by", weight: 1.0, reason: "Authority order rank 2 (TOON)" }
    - { to: "lupo-database/lupopedia/toon/lupo_edges.toon", type: "constrained_by", weight: 1.0, reason: "Authority order rank 2 (TOON)" }
    - { to: "lupo-docs/database/lupopedia/tables/active/lupo_channels.md", type: "reviews", weight: 1.0, reason: "Authority order rank 3 active table doc" }
    - { to: "lupo-docs/database/lupopedia/tables/active/lupo_dialog_threads.md", type: "reviews", weight: 1.0, reason: "Authority order rank 3 active table doc" }
    - { to: "lupo-docs/database/lupopedia/tables/active/lupo_dialog_messages.md", type: "reviews", weight: 1.0, reason: "Authority order rank 3 active table doc" }
    - { to: "lupo-docs/database/lupopedia/tables/active/lupo_tasks.md", type: "reviews", weight: 1.0, reason: "Authority order rank 3 active table doc" }
    - { to: "lupo-docs/database/lupopedia/tables/active/lupo_task_dependencies.md", type: "reviews", weight: 1.0, reason: "Explicitly required dependency-table doc check" }
    - { to: "lupo-docs/database/lupopedia/tables/active/lupo_edges.md", type: "reviews", weight: 1.0, reason: "Authority order rank 3 active table doc" }
    - { to: "CHANGELOG.md", type: "references", weight: 0.8, reason: "Context source for reconciliation claim scope" }
    - { to: "TODO.md", type: "references", weight: 0.8, reason: "Context source for active task state" }
    - { to: "plan.md", type: "references", weight: 0.8, reason: "Context source for phase-gate model" }
lupopedia.footer:
  last_verified: "20260320"
  last_verified_by: "thoth"
  orchestrator: "wolfie"
  next_action:
    - "WOLFIE: issue scoped correction directive for drifted/ambiguous active table docs"
    - "LILITH: audit correction completion against authority order"
---
# file: THOTH Table Reconciliation Report - Visibility-Critical DB Documentation Authority Check (Phase 2 Gate)

Authority order used (binding):

1. `lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql`
2. TOON files under `lupo-database/lupopedia/toon/`
3. active table docs under `lupo-docs/database/lupopedia/tables/active/`
4. thread artifacts/status reports (non-schema authority)

Scope checked:

1. `lupo_channels`
2. `lupo_dialog_threads`
3. `lupo_dialog_messages`
4. `lupo_tasks`
5. `lupo_edges`
6. `lupo_task_dependencies` (explicitly required)

## Deterministic Reconciliation Table

| table_name | install_sql_status | toon_status | active_doc_status | authority_decision | required_action | severity |
|---|---|---|---|---|---|---|
| lupo_channels | aligned (table present; columns/indexes match authority baseline) | aligned to install SQL | aligned (minor prose differences only) | aligned | no schema-doc correction required in this gate | none |
| lupo_dialog_threads | aligned (table present; columns/indexes match authority baseline) | aligned to install SQL | aligned with minor missing precision (notably exact defaults/nullability detail depth) | aligned_with_minor_detail_gap | optional precision pass to include full nullability/default grid from authority sources | low |
| lupo_dialog_messages | aligned (table present; includes `message_id`, source faucet fields, read fields, mood fields, `message_body`) | aligned to install SQL | drifted (doc omits multiple authoritative columns and mismatches types/nullability) | drifted_doc_non_authoritative | rewrite active doc schema section from authority sources before Phase 2 pass | high |
| lupo_tasks | aligned (table present with `task_key`, `owner_actor_id`, `task_status`, `task_priority`, `parent_agent_id`, etc.) | aligned to install SQL | drifted (active doc models different field set such as `actor_id`, `project_id`, `parent_task_id`, `status`) | drifted_doc_non_authoritative | full active doc rewrite to authority schema and indexes | critical |
| lupo_edges | aligned (table present; includes edge_category + FLARE columns; no FK constraints) | aligned to install SQL | ambiguous/drifted (contains contradictory FK-style claims in metadata and mixed legacy framing) | partial_drift_ambiguous | normalize doc to explicit no-FK doctrine and authority-accurate schema statements | medium |
| lupo_task_dependencies | missing in install SQL (no canonical DDL table) | missing TOON file for table | drifted/legacy (active doc exists despite missing authority sources) | non_authoritative_legacy_doc | rewrite as deprecation/legacy note or remove from active table set via WOLFIE directive; cannot remain active authoritative table doc | critical |

## Drift and Ambiguity Findings (Exact Files, Decision, Required Correction)

1. `lupo_dialog_messages`
- Files involved:
- `lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql`
- `lupo-database/lupopedia/toon/lupo_dialog_messages.toon`
- `lupo-docs/database/lupopedia/tables/active/lupo_dialog_messages.md`
- What disagrees:
- active doc omits authoritative columns (`message_id`, `source_faucet_slug`, `source_faucet_instance_id`, `read_by_actor_id`, `read_by_actor_utc`, `mood_vector`, `mood_framework`, `message_body`) and misstates some field typing/nullability.
- Authority source:
- install SQL and TOON.
- Required correction:
- rewrite active doc schema/index section directly from install SQL + TOON.
- Severity:
- high.

2. `lupo_tasks`
- Files involved:
- `lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql`
- `lupo-database/lupopedia/toon/lupo_tasks.toon`
- `lupo-docs/database/lupopedia/tables/active/lupo_tasks.md`
- What disagrees:
- active doc documents a non-authoritative field model (`actor_id`, `project_id`, `parent_task_id`, `status`, `assigned_to`, `due_ymdhis`) not matching canonical schema (`task_key`, `owner_actor_id`, `task_status`, `task_priority`, `parent_agent_id`, etc.).
- Authority source:
- install SQL and TOON.
- Required correction:
- full schema rewrite of active doc and index section to canonical table definition.
- Severity:
- critical.

3. `lupo_edges`
- Files involved:
- `lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql`
- `lupo-database/lupopedia/toon/lupo_edges.toon`
- `lupo-docs/database/lupopedia/tables/active/lupo_edges.md`
- What disagrees:
- mixed legacy framing includes FK-style claims in documentation metadata while canonical schema/doctrine is no-FK.
- Authority source:
- install SQL + TOON + no-FK doctrine.
- Required correction:
- remove FK-implying claims and align schema narrative to canonical no-FK application-enforced model.
- Severity:
- medium.

4. `lupo_task_dependencies`
- Files involved:
- `lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql`
- `lupo-database/lupopedia/toon/` (no `lupo_task_dependencies.toon` present)
- `lupo-docs/database/lupopedia/tables/active/lupo_task_dependencies.md`
- What disagrees:
- active doc asserts authoritative table schema while canonical install SQL and TOON do not provide authority for this table.
- Authority source:
- install SQL first, then TOON; both missing for this table.
- Required correction:
- classify as legacy/non-active documentation and remove from active-authority set or rewrite as explicit non-authoritative legacy note via WOLFIE directive.
- Severity:
- critical.

## Explicit determination for lupo_task_dependencies.md

Decision:

1. `lupo_task_dependencies.md` is **drifted** and **needs rewrite/reclassification**.
2. It cannot be treated as active authoritative table documentation under the current authority order.

## Phase 2 Gate Decision

Gate result:

1. **fail**

Reason:

1. Critical drift exists in active docs for `lupo_tasks` and `lupo_task_dependencies`.
2. High-severity drift exists in `lupo_dialog_messages`.
3. Medium ambiguity/drift remains in `lupo_edges`.

1. pass/fail recommendation for Phase 2 gate: **fail**.
2. exact next actor: **WOLFIE**.
3. exact next deliverable: **scoped correction directive for active table-doc remediation set (`lupo_dialog_messages.md`, `lupo_tasks.md`, `lupo_edges.md`, `lupo_task_dependencies.md`) with explicit completion criteria and verification owner assignment**.

_THOTH (actor_id 26) - Phase 2 gate table reconciliation report for Thread 1030._
