---
lupopedia.headers:
  version_when_written: "4.0.84"
  lupopedia.schema: "thread"
  file_path_from_root: "lupo-channels/42/threads/1030/20260320_180000_wolfie_directive_phase_2_table_doc_correction_law_and_execution_contract_thread_1030.md"
  web_path: "http://www.lupopedia.com/lupo-channels/42/threads/1030/20260320_180000_wolfie_directive_phase_2_table_doc_correction_law_and_execution_contract_thread_1030.md"
  questions_toon: null
  channel_id: 42
  thread_id: 1030
  task_id: "task_channel42_db_visibility_reconciliation_001"
  actor_id: 1
  actor_name: "wolfie"
  delegation_chain: "wolfie:root"
  artifact_type: "thread"
  artifact_kind: "directive"
  purpose: "Second corrective directive: resolves all ambiguities identified by LILITH in Phase 2 gate report; defines correction law and execution contract for Phase 2 table-doc remediation"
  tags: ["wolfie", "corrective_directive_2", "thread_1030", "phase_2_correction_law", "execution_contract", "table_doc", "governance", "4.0.84"]
  message_type: "directive"
lupopedia.edges:
  outbound_edges:
    - { to: "lupo-channels/42/threads/1030/20260320_174500_wolfie_corrective_directive_operationalizing_thread_1030_database_visibility_reconciliation.md", type: "extends", weight: 1.0, reason: "First corrective directive; this artifact does not repeat it, only resolves ambiguities left unresolved there" }
    - { to: "lupo-channels/42/threads/1030/20260320_175000_thoth_table_reconciliation_report_visibility_critical_db_documentation_authority_check_phase_2_gate.md", type: "responds_to", weight: 1.0, reason: "Phase 2 FAIL gate artifact that triggered LILITH review and this directive" }
    - { to: "lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql", type: "constrained_by", weight: 1.0, reason: "Canonical DDL; authority rank 1 for all correction work defined here" }
    - { to: "lupo-database/lupopedia/toon/lupo_dialog_messages.toon", type: "constrained_by", weight: 1.0, reason: "Authority rank 2 for lupo_dialog_messages correction" }
    - { to: "lupo-database/lupopedia/toon/lupo_tasks.toon", type: "constrained_by", weight: 1.0, reason: "Authority rank 2 for lupo_tasks correction" }
    - { to: "lupo-database/lupopedia/toon/lupo_edges.toon", type: "constrained_by", weight: 1.0, reason: "Authority rank 2 for lupo_edges correction" }
    - { to: "lupo-docs/database/lupopedia/tables/active/lupo_dialog_messages.md", type: "governs", weight: 1.0, reason: "Correction target: high severity schema drift" }
    - { to: "lupo-docs/database/lupopedia/tables/active/lupo_tasks.md", type: "governs", weight: 1.0, reason: "Correction target: critical severity full schema replacement" }
    - { to: "lupo-docs/database/lupopedia/tables/active/lupo_edges.md", type: "governs", weight: 1.0, reason: "Correction target: medium severity FK-claim removal" }
    - { to: "lupo-docs/database/lupopedia/tables/active/lupo_task_dependencies.md", type: "governs", weight: 1.0, reason: "Correction target: critical non-authoritative table, disposition decision" }
lupopedia.footer:
  last_verified: "20260320"
  last_verified_by: "wolfie"
  orchestrator: "wolfie"
  next_action:
    - "THOTH: apply corrections to all 4 table docs per Section 6 execution contract; publish table_doc_correction_set artifact in Thread 1030"
    - "LILITH: audit THOTH correction artifact against authority sources; publish audit artifact in Thread 1030"
    - "WOLFIE: issue Phase 2 pass directive after LILITH audit returns clean"
---
# file: WOLFIE Directive — Phase 2 Table-Doc Correction Law and Execution Contract (Thread 1030)

This is the second corrective directive in Thread 1030. It does not repeat or supersede the first directive (`20260320_174500`). It does not reopen strategy. It does not start implementation. Its sole purpose is to remove every ambiguity identified by LILITH's review of the THOTH Phase 2 gate report and to define correction rules as enforceable doctrine binding all actors in this thread.

Every decision in this directive is final for Thread 1030 Phase 2. No interpretation is permitted.

---

## 1. Correction granularity rule

This rule applies to all active table docs in scope for Phase 2 correction, regardless of severity classification.

**Schema section:** MUST be fully replaced from authority sources. Incremental correction of schema sections is forbidden. No partial updates, no line-level patches. The correcting actor removes the existing schema section entirely and rewrites it from scratch using install SQL and TOON as the exclusive source.

Definition of "schema section": any section documenting column names, column types, nullability, defaults, auto-increment, primary key, index definitions, or unique constraints. Section headings such as "Schema", "Columns", "Fields", "Index", "Indexes", "Table Structure", or equivalent are treated as schema sections regardless of their exact title.

**Index section:** included in schema section. Same rule applies.

**Narrative sections:** MAY be retained subject to the following constraint: any narrative sentence that makes a schema-implying claim (names a column, asserts a type, asserts a relationship, asserts presence or absence of a constraint) must be corrected or removed if it contradicts the rewritten schema. Narrative that does not make schema-implying claims may remain without modification.

Binding outcome:

1. For `lupo_dialog_messages`: schema section FULLY replaced; narrative retained only where non-schema-implying.
2. For `lupo_tasks`: schema section FULLY replaced; all narrative making schema-implying claims corrected to authority.
3. For `lupo_edges`: schema section FULLY replaced; FK-implying narrative removed (see Section 2).
4. For `lupo_task_dependencies`: no schema section retained; disposition governed by Section 3.

---

## 2. Explicit drift localization requirement

This rule applies to all corrections where drift is classified as ambiguous or where existing narrative must be evaluated rather than wholesale replaced (specifically `lupo_edges`; applies to narrative sections in all four tables).

**Required identification format for each change:**

Every change made to a retained narrative section, metadata block, header, or non-schema section MUST be documented in the correction artifact in this exact format:

```
Table: <table_name>
Section: <section heading as it appears in the file>
Claim: "<exact verbatim text being corrected or removed>"
Reason: <one sentence: why this claim is rejected — name the authority source and the conflict>
Correction: <replacement text, or "REMOVED" if the claim is deleted>
```

**Prohibition:**

"General cleanup" is not a valid correction category. No change to any section is permitted without a traceable Claim entry in the above format. If no specific FK-implying claims are found in a section during audit, the correcting actor must state "no FK-implying claims found in section [heading]" explicitly in the correction artifact. Silence is not acceptable.

**For lupo_edges specifically:**

THOTH must enumerate every FK-implying claim found in `lupo-docs/database/lupopedia/tables/active/lupo_edges.md` before writing any corrections. If zero FK-implying claims are found after full review, THOTH must state this explicitly and the medium-severity finding is downgraded to "no correction required" for narrative sections. Schema section is still fully replaced regardless.

---

## 3. Non-authoritative table rule (system-level)

This is a system-level rule. It applies to all tables in all current and future reconciliation work across Lupopedia, not only to `lupo_task_dependencies`.

**Classification:**

A table is **non-canonical** if it satisfies BOTH of the following conditions:

1. No CREATE TABLE statement for it exists in `lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql`.
2. No `.toon.json` file for it exists under `lupo-database/lupopedia/toon/`.

**Automatic consequence of non-canonical classification:**

An active table doc for a non-canonical table is automatically invalid as an authority source. The doc MUST NOT remain in the `lupo-docs/database/lupopedia/tables/active/` directory without a disposition decision by WOLFIE. "Active" in this context means present in the active directory regardless of its internal classification claims.

**Permitted dispositions (mutually exclusive):**

A. **Legacy:** Move the doc to `lupo-docs/database/lupopedia/tables/legacy/`. The doc must have its header updated to `artifact_kind: legacy_table_doc` and must include a top-level notice: "This document describes a table that is non-canonical: absent from install SQL and TOON. It is retained for historical reference only and carries no schema authority." No other content modification is required for disposition A.

B. **Delete:** Remove the doc from the repository entirely. No replacement file is required. The deletion must be recorded in the correction artifact as a named action.

**Decision authority:** WOLFIE alone decides the disposition of each non-canonical table on a case-by-case basis. THOTH may recommend; LILITH may flag; neither may decide.

**Decision criteria (applied by WOLFIE):**

1. If the table existed in legacy Crafty Syntax 3.7.5 (i.e., is documented in `lupo-legacy/craftysyntax/` or referenced in `lupo-database/lupopedia/mysql/import/import_from_old_crafty_syntax.sql`): the doc moves to legacy (disposition A).
2. If the table has no legacy Crafty Syntax lineage and is referenced in no current active authority document: the doc is deleted (disposition B).
3. If neither criterion is clearly met and the table has known historical operational value: WOLFIE may issue a holding directive deferring disposition, but the doc must be moved to legacy (disposition A) as an interim measure pending the holding directive.

**Disposition for `lupo_task_dependencies`:**

WOLFIE directs: disposition **A — Legacy**. Reason: `lupo_task_dependencies` has legacy Crafty Syntax conceptual lineage (operator dependency tracking pre-dates Lupopedia actor model). It is moved to legacy. THOTH executes this as part of the Phase 2 correction set.

---

## 4. Active table set definition rule

**Definition:**

"Visibility-critical tables" is not a permanent classification name and does not imply a ranked registry. For Phase 2 purposes: the visibility-critical table set is the set explicitly enumerated in a WOLFIE directive or a THOTH gate report commissioned by WOLFIE. The current set for Thread 1030 is:

```
{ lupo_channels, lupo_dialog_threads, lupo_dialog_messages, lupo_tasks, lupo_edges }
```

`lupo_task_dependencies` is excluded from this set by non-canonical classification (Section 3).

**Adding a table to the set:**

Permitted only via WOLFIE directive in Thread 1030 or a successor thread. The directive must state: (1) the table name, (2) the reason for addition, (3) whether the table has a CREATE TABLE in install SQL and a TOON file (if not, the table is non-canonical and cannot be in the active set), (4) the actor responsible for the reconciliation check.

**Removing a table from the set:**

Permitted only via WOLFIE directive in Thread 1030 or a successor thread. The directive must state: (1) the table name, (2) the reason for removal (e.g. table dropped, scope changed), (3) disposition of any active table doc.

**Recording changes to the set:**

Any change to the set must be reflected in:

1. A WOLFIE directive artifact in Thread 1030 (the change record).
2. `plan.md` — the Phase 2 description must be updated to list the current set after the change.

No actor other than WOLFIE may change the membership of this set.

---

## 5. Directive location and timing rule

**Location:**

Correction directives issued in response to Thread 1030 gate failures MUST be published as artifacts in Thread 1030. This applies to all directives whose scope is scoped to Thread 1030 correction work, including table-doc corrections, phase gate pass/fail decisions, and actor assignment changes.

Cross-thread directives are NOT permitted for Thread 1030 correction scope. If a directive establishes a system-level rule that extends beyond Thread 1030 (as Section 3 of this document does), it must still be published in Thread 1030 and may additionally be propagated to a doctrine file, but Thread 1030 is the primary issuance location.

No other channel or thread may override a directive issued in Thread 1030 for Thread 1030-scoped work without an explicit superseding WOLFIE directive in Thread 1030.

**Timing:**

Directive issuance is **blocking**. No correction work, no correction artifact publication, and no Phase 2 re-gate attempt may proceed until a governing directive exists in Thread 1030 that covers the corrections required.

Expected issuance window: within the same working session as the gate failure report. If a directive cannot be issued within the same session, Thread 1030 Phase 2 correction work remains blocked until one is issued. There is no timer-based unblocking. The Phase 2 gate stays failed until WOLFIE issues a directive and THOTH acts on it.

---

## 6. Phase 2 correction execution contract

This section defines the complete, unambiguous execution chain for Phase 2 correction and re-gate.

**Next actor:** THOTH (actor_id 26)

**Assigned correction targets (four):**

1. `lupo-docs/database/lupopedia/tables/active/lupo_dialog_messages.md` — fully replace schema section from install SQL + TOON; audit and correct any schema-implying narrative; severity: high
2. `lupo-docs/database/lupopedia/tables/active/lupo_tasks.md` — fully replace schema section from install SQL + TOON; correct all schema-implying narrative; severity: critical
3. `lupo-docs/database/lupopedia/tables/active/lupo_edges.md` — fully replace schema section from install SQL + TOON; enumerate and remove FK-implying claims per Section 2 localization format; severity: medium
4. `lupo-docs/database/lupopedia/tables/active/lupo_task_dependencies.md` — move to `lupo-docs/database/lupopedia/tables/legacy/lupo_task_dependencies.md`; update header per Section 3 legacy disposition A requirements; severity: critical (non-canonical)

**Execution model:**

THOTH must APPLY all corrections to the filesystem AND publish the correction artifact in a single working session. The sequence within that session is:

1. Apply filesystem corrections (file edits and file move).
2. Publish correction artifact in Thread 1030.

Reporting corrections without applying them first is not permitted. There is no "report-then-apply" model. The correction artifact must describe already-completed filesystem changes, not planned ones.

**Required artifact type:** `table_doc_correction_set`

**Correction artifact structure (mandatory):**

The `table_doc_correction_set` artifact must contain:

1. **Execution summary:** one line per correction target confirming the action taken (replaced / moved / deleted) and the file path.
2. **Per-table correction record:** for each of the four tables, a section containing:
   - authority sources consulted (install SQL line range + TOON file path)
   - for schema section: confirmation that old schema section was removed and new was written verbatim from authority
   - for narrative sections: full change log in Section 2 localization format (Claim / Reason / Correction per change; or explicit "no schema-implying claims found" if none)
3. **Drift closure confirmation:** a row per table confirming whether the drift finding from THOTH gate report is closed or deferred (no finding may be silently omitted)
4. **Non-canonical disposition record:** entry for `lupo_task_dependencies` confirming legacy move and file path update

**Verification chain:**

1. THOTH publishes `table_doc_correction_set` artifact in Thread 1030.
2. LILITH independently audits the correction artifact against authority sources (install SQL + TOON + no-FK doctrine). LILITH publishes an `audit` artifact in Thread 1030. LILITH does not modify the corrected files; she reports only.
3. WOLFIE reviews LILITH's audit artifact. If audit returns zero unresolved findings: WOLFIE issues Phase 2 pass directive in Thread 1030. If audit returns findings: WOLFIE issues additional scoped correction directive; the loop repeats from step 1.
4. Only WOLFIE may issue the Phase 2 pass directive. No actor may declare Phase 2 passed without an explicit WOLFIE pass directive artifact in Thread 1030.

**Phase 2 pass directive artifact type:** `phase_gate_pass`

**Blocked until Phase 2 passes:** Phase 3 (projection/review-record logic) does not begin until WOLFIE's Phase 2 pass directive is published. This is non-negotiable per the first corrective directive Section 3.

---

_WOLFIE (actor_id 1) — second corrective directive for Thread 1030, Channel 42. Resolves all LILITH-identified ambiguities. Defines Phase 2 correction law as enforceable doctrine._
