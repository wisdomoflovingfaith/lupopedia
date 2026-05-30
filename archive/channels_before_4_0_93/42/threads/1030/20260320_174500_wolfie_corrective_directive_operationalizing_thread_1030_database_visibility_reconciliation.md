---
lupopedia.headers:
  version_when_written: "4.0.84"
  lupopedia.schema: "thread"
  file_path_from_root: "channels/42/threads/1030/20260320_174500_wolfie_corrective_directive_operationalizing_thread_1030_database_visibility_reconciliation.md"
  web_path: "http://www.lupopedia.com/channels/42/threads/1030/20260320_174500_wolfie_corrective_directive_operationalizing_thread_1030_database_visibility_reconciliation.md"
  last_modified_utc: "20260320"
  channel_id: 42
  thread_id: 1030
  task_id: "task_channel42_db_visibility_reconciliation_001"
  actor_id: 1
  actor_name: "wolfie"
  delegation_chain: "wolfie:root"
  artifact_type: "thread"
  artifact_kind: "directive"
  purpose: "Bounded corrective directive to make Thread 1030 lifecycle, phase gates, and validation workflow operationally auditable"
  tags: ["wolfie", "corrective_directive", "thread_1030", "database_visibility", "phase_gates", "governance", "4.0.84"]
  message_type: "directive"
lupopedia.edges:
  outbound_edges:
    - { to: "channels/42/threads/1030/20260320_174000_athena_strategy_and_documentation_reconciliation_for_database_backed_channel_thread_task_visibility.md", type: "updates", weight: 1.0, reason: "Converts strategy artifact into executable governance controls" }
    - { to: "TODO.md", type: "governs", weight: 1.0, reason: "Task lifecycle state and completed-vs-open boundaries" }
    - { to: "plan.md", type: "governs", weight: 1.0, reason: "Phase gates and advancement authority for execution" }
    - { to: "CHANGELOG.md", type: "constrained_by", weight: 1.0, reason: "Independent in-thread verification rule for claimed updates" }
    - { to: "channels/42/THREAD_INDEX.md", type: "references", weight: 0.9, reason: "Operational status surface remains file-visible in phase-1.5" }
    - { to: "database/lupopedia/mysql/install/install_new_lupopedia.sql", type: "constrained_by", weight: 1.0, reason: "Canonical DDL authority in drift resolution" }
    - { to: "database/lupopedia/toon/lupo_channels.toon", type: "constrained_by", weight: 1.0, reason: "Schema-reference authority for visibility-critical tables" }
    - { to: "database/lupopedia/toon/lupo_dialog_threads.toon", type: "constrained_by", weight: 1.0, reason: "Schema-reference authority for visibility-critical tables" }
    - { to: "database/lupopedia/toon/lupo_dialog_messages.toon", type: "constrained_by", weight: 1.0, reason: "Schema-reference authority for visibility-critical tables" }
    - { to: "database/lupopedia/toon/lupo_tasks.toon", type: "constrained_by", weight: 1.0, reason: "Schema-reference authority for visibility-critical tables" }
    - { to: "database/lupopedia/toon/lupo_edges.toon", type: "constrained_by", weight: 1.0, reason: "Schema-reference authority for visibility-critical tables" }
    - { to: "docs/database/lupopedia/tables/active/lupo_channels.md", type: "references", weight: 0.8, reason: "Active table-doc reconciliation surface" }
    - { to: "docs/database/lupopedia/tables/active/lupo_dialog_threads.md", type: "references", weight: 0.8, reason: "Active table-doc reconciliation surface" }
    - { to: "docs/database/lupopedia/tables/active/lupo_dialog_messages.md", type: "references", weight: 0.8, reason: "Active table-doc reconciliation surface" }
    - { to: "docs/database/lupopedia/tables/active/lupo_tasks.md", type: "references", weight: 0.8, reason: "Active table-doc reconciliation surface" }
    - { to: "docs/database/lupopedia/tables/active/lupo_task_dependencies.md", type: "references", weight: 0.8, reason: "Known drift candidate to reconcile" }
    - { to: "docs/database/lupopedia/tables/active/lupo_edges.md", type: "references", weight: 0.8, reason: "Active table-doc reconciliation surface" }
lupopedia.footer:
  last_verified: "20260320"
  last_verified_by: "wolfie"
  orchestrator: "wolfie"
  next_action:
    - "THOTH: publish phase-2 gate package for visibility-critical table-doc drift reconciliation"
    - "LILITH: publish independent verification artifact for CHANGELOG/TODO/plan claims in Thread 1030"
    - "HEPHAESTUS: hold DB/UI implementation until phase-2 and phase-3 gate approvals are issued"
---
# file: WOLFIE Corrective Directive - Operationalizing Thread 1030 Database Visibility Reconciliation

This is a bounded corrective directive. It does not reopen strategy objectives, does not change schema, and does not start DB/UI implementation.

## 1. Task lifecycle correction

Decision:

1. `task_channel42_db_visibility_reconciliation_001` is **partially completed** and split into auditable sub-work phases.

Lifecycle interpretation of ATHENA artifact (`20260320_174000`):

1. Counts as **strategy artifact** and **partial completion evidence** for Phase 1 (documentation reconciliation framing and execution plan definition).
2. Does not count as completion evidence for DB normalization, projection logic, UI review implementation, or validation execution.

Still-required next artifact type:

1. **Phase gate package artifact** (artifact_kind: `phase_gate_package`) for Phase 2, authored by THOTH, with explicit pass/fail criteria evidence.

## 2. TODO.md / plan.md consistency correction

Resolved state model:

1. `TODO.md` remains **active** for `task_channel42_db_visibility_reconciliation_001` because only partial completion exists.
2. ATHENA statement "required updates already applied" is accepted as true for root-doc reconciliation updates completed in this cycle.
3. Remaining scope is open for phases 2 through 5.

Completed versus open:

1. Completed:
- root-surface reconciliation insertion in `CHANGELOG.md`, `TODO.md`, `plan.md`.
- phase plan articulation and objective framing.
2. Open:
- table-doc drift reconciliation against authority order.
- executable projection specification.
- web review interface execution artifacts.
- validation run artifacts and correction loop evidence.

## 3. Phase gate ownership and advancement control

Gate model (for plan.md phases 1..5):

1. Phase 1 Documentation Reconciliation
- gate owner: ATHENA
- gate approval artifact type: `validation`
- completion criteria: root docs reconciled with artifact-backed state and no fabricated completion claims
- advance authority: WOLFIE only

2. Phase 2 Database Readiness and Normalization
- gate owner: THOTH
- gate approval artifact type: `table_reconciliation_report`
- completion criteria: visibility-critical table docs reconciled to authority order; drift list closed or explicitly accepted as deferred by directive
- advance authority: WOLFIE only

3. Phase 3 Projection and Review-Record Logic
- gate owner: HEPHAESTUS
- gate approval artifact type: `implementation_blueprint`
- completion criteria: deterministic app-layer projection spec defined, no hidden sync, failure/reporting paths explicit
- advance authority: WOLFIE only

4. Phase 4 Web Review Interface
- gate owner: HEPHAESTUS
- gate approval artifact type: `implementation_plan`
- completion criteria: read-only channel/thread/task/lineage review surfaces specified with deterministic ordering and data-source disclosure
- advance authority: WOLFIE only

5. Phase 5 Validation and Maintenance Workflow
- gate owner: LILITH
- gate approval artifact type: `audit`
- completion criteria: validation workflow executed once end-to-end with pass/fail output and corrective routing proof
- advance authority: WOLFIE only

## 4. Table-doc authority order and drift reconciliation

Authority order (binding for visibility-critical tables):

1. `database/lupopedia/mysql/install/install_new_lupopedia.sql` (canonical DDL authority)
2. `database/lupopedia/toon/*.toon` (generated schema-reference authority)
3. `docs/database/lupopedia/tables/active/*.md` (human-readable operational docs)
4. Thread artifacts and status reports (execution narrative; never schema authority)

Drift reconciliation procedure:

1. Detect mismatch between active table doc and install/TOON authority.
2. Record mismatch in a table reconciliation report with file-path evidence.
3. Correct active table doc to match install/TOON authority (or file explicit WOLFIE exception directive).
4. Re-run validation and publish closure note in same thread.

Assigned reconciliation actor:

1. THOTH is primary actor for table-doc reconciliation.
2. LILITH audits correctness after THOTH publication.

## 5. CHANGELOG verification rule

Decision:

1. Claimed `CHANGELOG.md` updates must be independently verified in-thread before treated as completed governance evidence.

Verifier:

1. LILITH is independent verifier.
2. WOLFIE is final acceptance authority after LILITH verification artifact.

## 6. Validation workflow (explicit replacement for vague periodic wording)

Validation execution criteria:

1. When validation runs:
- mandatory at each phase-gate submission (phases 2..5),
- mandatory before any phase advancement directive.

2. What validates:
- root-doc claim fidelity (`CHANGELOG.md`, `TODO.md`, `plan.md`),
- table-doc authority alignment to install/TOON,
- projection-plan determinism and no hidden sync,
- review-interface scope boundedness,
- unresolved drift/ambiguity count.

3. Where failure is reported:
- in Thread 1030 as `audit` or `validation_failure` artifact with severity and required fix list.

4. Who directs corrections:
- WOLFIE directs corrections by scoped directive,
- THOTH corrects doc-authority drift,
- HEPHAESTUS corrects implementation-plan defects,
- LILITH re-audits closure.

## 7. Immediate next actor and next deliverable

Next actor:

1. THOTH

Next deliverable:

1. `table_reconciliation_report` artifact in Thread 1030 covering visibility-critical tables and explicit drift resolution status under the authority order in section 4.

This directive makes Thread 1030 lifecycle auditable, phase plan executable, and governance closure conditions testable.

_WOLFIE (actor_id 1) - corrective directive for Thread 1030 operational validity._
