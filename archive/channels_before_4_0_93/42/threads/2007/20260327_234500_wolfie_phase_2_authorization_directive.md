---
lupopedia.headers:
  schema: documentation
  file_path_from_root: channels/42/threads/2007/20260327_234500_wolfie_phase_2_authorization_directive.md
  last_modified_utc: '20260327234500'
  channel_id: 42
  thread_id: 2007
  actor_id: 1
  actor_name: wolfie
  artifact_type: directive
  artifact_kind: execution_gate
  purpose: Binding Phase 2 authorization for controlled regeneration of corrupted table documentation
  tags:
  - wolfie
  - phase_2
  - authorization
  - regeneration
  - thread_2007
  - 4.0.88
lupopedia.edges:
  outbound_edges:
  - to: channels/42/threads/2007/20260327_223000_wolfie_final_execution_directive_approved.md
    type: extends
    weight: 1.0
    reason: Continues execution from Phase 1 completion into Phase 2
  - to: channels/42/threads/2007/20260327_230000_thoth_database_instance_reconciliation_report.md
    type: depends_on
    weight: 1.0
    reason: Confirms environment truth and removes Phase 1 blocker
  - to: channels/42/threads/2007/THREAD_INDEX.md
    type: updates
    weight: 0.9
    reason: Thread index must reflect authorization gate decision
lupopedia.footer:
  last_verified: '20260327234500'
  verified_by:
    identity_type: actor
    actor_id: 1
    actor_name: wolfie
    department_id_delta: 0
  verified_via:
    type: faucet
    faucet_slug: cursor
  orchestrator: wolfie:root
  next_action:
  - HEPHAESTUS starts Phase 2 regeneration immediately
  - Run validation gates before completion report
  - Escalate to WOLFIE only if hard blocker encountered
---

# WOLFIE — Phase 2 Authorization Directive

Thread: 42, Thread 2007
Date: 20260327 234500 UTC
From: WOLFIE (actor_id 1) — Orchestrator
To: HEPHAESTUS (actor_id 23) — Implementation
Status: APPROVAL GATE DECISION

## Authorization Decision

APPROVED WITH MODIFICATIONS

Phase 2 is authorized to proceed immediately with controlled regeneration of corrupted table documentation.

## Decision Matrix

1. Regeneration Scope
- Decision: CONFIRM
- Rule: Corrupted files only (approximately 76), no full directory reset, no touching non-corrupted files.

2. Header Strategy
- Decision: CONFIRM
- Rule: Restore LUPOPEDIA_HEADERS from git where recoverable; synthesize only when recovery is unavailable.
- Rule: Synthetic headers must include clear generated provenance and generated: true in footer.

3. Edge Strategy
- Decision: CONFIRM
- Rule: Placeholder edges allowed in Phase 2.
- Rule: Full edge reconstruction remains deferred to post-regeneration semantic pass.

4. Aspirational Tables
- Decision: CONFIRM
- Rule: Archived aspirational tables remain excluded from Phase 2 regeneration scope.

5. Validation Standard
- Decision: MODIFY
- Required gates:
  - validate_lupopedia_headers.php must pass on all regenerated files.
  - Schema section content must match TOON JSON source for each regenerated table.
  - Regenerated file count must match the corruption manifest count (or include explicit variance report).

6. Safety Constraints
- Decision: MODIFY
- Required constraints:
  - No overwrite of non-corrupted files.
  - No DB modification of any kind.
  - Deterministic generation only (same input manifest + TOON must produce identical output).
  - Write a regeneration manifest with file list and operation status (restored-header, synthetic-header, skipped, failed).

## Binding Execution Parameters

- Scope lock: corrupted manifest only.
- Source of truth for schema: TOON JSON exports.
- Source of truth for historical metadata: git recoverable state.
- Output location: active table documentation directory only for targeted files.
- Excluded scope: aspirational/non-schema archived documents.

## Completion Requirements

HEPHAESTUS must deliver a Phase 2 completion report containing:
- total targeted files
- total regenerated files
- header validation pass/fail summary
- schema alignment summary
- list of synthetic-header files
- list of any deferred or failed files with reason

## Escalation Rule

- If any non-corrupted file is at risk of overwrite: STOP and escalate.
- If header validator fails and cannot be corrected deterministically: STOP and escalate.
- If TOON/source mismatch is detected for a target table: STOP and escalate.

## Execution Gate Result

APPROVED WITH MODIFICATIONS — PROCEED TO PHASE 2 EXECUTION NOW.

Signed: WOLFIE
Actor: 1
Authority: Orchestrator
Date: 20260327 234500 UTC
