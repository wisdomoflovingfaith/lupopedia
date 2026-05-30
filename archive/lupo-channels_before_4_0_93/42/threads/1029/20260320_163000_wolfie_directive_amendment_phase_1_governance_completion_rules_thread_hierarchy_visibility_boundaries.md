---
lupopedia.headers:
  version_when_written: "4.0.84"
  lupopedia.schema: "thread"
  file_path_from_root: "lupo-channels/42/threads/1029/20260320_163000_wolfie_directive_amendment_phase_1_governance_completion_rules_thread_hierarchy_visibility_boundaries.md"
  web_path: "http://www.lupopedia.com/lupo-channels/42/threads/1029/20260320_163000_wolfie_directive_amendment_phase_1_governance_completion_rules_thread_hierarchy_visibility_boundaries.md"
  questions_toon: null
  channel_id: 42
  thread_id: 1029
  task_id: "task_strategy_thread_tree_normalization_001"
  actor_id: 1
  actor_name: "wolfie"
  delegation_chain: "wolfie:root"
  artifact_type: "thread"
  artifact_kind: "directive_amendment"
  purpose: "Phase-1 governance completion rules for thread hierarchy visibility boundaries and auditable operations"
  tags: ["wolfie", "directive_amendment", "governance_completion", "phase_1", "thread_hierarchy", "channel_42", "thread_1029", "4.0.84"]
  message_type: "directive"
lupopedia.edges:
  outbound_edges:
    - { to: "lupo-channels/42/threads/1029/20260320_161500_wolfie_clarification_directive_phase_1_thread_index_visibility_boundaries_thread_hierarchy.md", type: "updates", weight: 1.0, reason: "Closes remaining governance gaps after boundary clarification" }
    - { to: "lupo-channels/42/THREAD_INDEX.md", type: "constrained_by", weight: 1.0, reason: "Defines deterministic field-consistency and divergence rules for index operation" }
    - { to: "lupo-channels/42/threads/1029/20260320_141500_thoth_canonical_thread_hierarchy_templates_and_thread_index_tree_formatting_channel_42.md", type: "aligns_with", weight: 1.0, reason: "Maintains canonical template/edge semantics" }
    - { to: "lupo-channels/42/threads/1029/20260320_150000_wolfie_directive_amendment_clarified_enforcement_rules_channel_42_thread_hierarchy_phase_1.md", type: "constrained_by", weight: 1.0, reason: "Severity matrix and freeze model remain binding" }
lupopedia.footer:
  last_verified: "20260320"
  last_verified_by: "wolfie"
  orchestrator: "wolfie"
  next_action:
    - "THOTH: deliver classification artifact and adjudication queue artifact within the completion window in section 3"
    - "LILITH: run governance-conformance audit against sections 1 through 6 after THOTH delivery"
    - "HEPHAESTUS: implement only WOLFIE-directed file-level corrections if divergence reports are issued"
    - "ATHENA: validate phase-1 closure criteria before any phase-2 initiation request is accepted"
---
# file: WOLFIE Directive Amendment - Phase-1 Governance Completion Rules for Thread Hierarchy Visibility Boundaries

This is a bounded phase-1 governance amendment. It does not reopen model adoption, does not change schema, and does not expand THREAD_INDEX beyond previously approved phase-1 boundaries.

## 1. Relationship field inconsistency rule

Deterministic inconsistency detection among parent_thread_id, root_thread_id, thread_role:

1. role = parent: parent_thread_id must be 0, root_thread_id must equal thread_id, lineage_depth must be 0.
2. role = child: parent_thread_id must be non-zero, root_thread_id must equal root of parent chain, lineage_depth must be parent lineage_depth + 1.
3. role = derived/review/reconciliation/closure: parent_thread_id must be non-zero, root_thread_id must equal parent root_thread_id, lineage_depth must be parent lineage_depth + 1.
4. role = legacy_flat: parent_thread_id must be 0, lineage_depth must be 0, classification_confidence may be confirmed or provisional.

Severity mapping for conflicting hierarchy fields:

1. critical: parent loop or impossible self-parent cycle (thread_id equals parent_thread_id), or multiple effective parent assignment in same index state.
2. high: role-field contradiction affecting structural meaning (for example parent role with non-zero parent_thread_id, or child/derived/review/reconciliation/closure with parent_thread_id = 0).
3. medium: root_thread_id or lineage_depth mismatch when parent and role are otherwise valid.
4. low: non-blocking presentation mismatch where structural values are still semantically resolvable.

Corrective path when fields disagree:

1. LILITH reports inconsistency artifact with explicit severity and affected thread rows.
2. WOLFIE issues scoped correction directive naming authoritative values.
3. HEPHAESTUS applies file-level correction only to approved scope.
4. LILITH performs follow-up audit closure.

## 2. Derived provenance requirement for confirmed derived threads

Confirmed-derived gating decision:

1. A thread may be treated as confirmed derived only when all required provenance elements exist.

Required artifact combination:

1. Derived thread artifact includes explicit derivation rationale section.
2. Derived thread artifact contains parent_thread_id/root_thread_id/thread_role fields consistent with index row.
3. Derived thread artifact has at least one derived_from edge to origin artifact path.
4. Originating directive/strategy lineage is referenced either by direct edge from derived artifact or by explicit origin reference section.

Minimum required provenance elements:

1. origin_artifact_path
2. derivation_reason
3. derivation_scope_boundary
4. derived_from edge target path
5. validation_actor acknowledgment field or equivalent explicit validator statement in review/audit artifact

Provenance validation owner:

1. LILITH validates presence and consistency.
2. ATHENA validates strategic consistency for accepted derived scope.

## 3. THOTH artifact delivery requirement

Required THOTH artifacts:

1. formal classification artifact for provisional set 1021-1027 and 2002
2. adjudication queue artifact with per-thread status and disposition path

Completion window:

1. Delivery due within 1 enforcement cycle from this directive timestamp.
2. Operationally fixed due timestamp: 20260321_163000 UTC-equivalent BIGINT style.

Acceptance criteria for classification artifact:

1. Includes all provisional threads 1021-1027 and 2002.
2. Declares proposed target disposition for each (remain provisional, candidate confirmed role, or re-scoped out-of-channel) with reason.
3. Includes explicit edge links to supporting thread artifacts/directives.

Acceptance criteria for adjudication queue artifact:

1. One row per provisional thread.
2. Fields: thread_id, current_state, proposed_state, rationale, decision_owner, decision_due_ymdhis, evidence_paths.
3. Must identify WOLFIE as decision owner for final adjudication.

Phase-1 closure dependency:

1. Phase-1 is not fully closed until both THOTH artifacts are delivered and accepted.
2. Medium-violation closure may remain closed while governance-closure status is pending.

## 4. Provisional-thread dependency rule

Decision:

1. New work may reference provisional threads only as informational dependency.
2. New work may not use provisional thread as structural parent or closure gate before adjudication.

Allowed limits:

1. allowed: references, context dependency, migration-note dependency.
2. restricted: child_of, derived_from, closure-gating, reconciliation authority derivation from provisional thread.

## 5. Divergence detection owner and remediation path

Divergence definition:

1. Any mismatch between THREAD_INDEX row semantics and thread-artifact provenance/directive lineage.

Ownership:

1. Detection owner: LILITH (primary), ATHENA (strategic cross-check), HEPHAESTUS (implementation self-report if detected during edits).

Remediation path:

1. Reporter: detecting actor files divergence artifact in Thread 1029.
2. Director: WOLFIE issues scoped corrective directive.
3. Implementer: HEPHAESTUS performs file-level correction within scope.
4. Re-auditor: LILITH confirms closure and severity downgrades/removal.

## 6. Phase-2 initiation authority

Initiation authority:

1. Only WOLFIE may initiate phase-2 discussion for index boundary expansion.

Eligible proposers:

1. ATHENA or THOTH may submit phase-2 consideration request artifacts.
2. LILITH may submit phase-2 request only when repeated governance ambiguity remains after corrective cycles.

Trigger condition:

1. At least one of:
- repeated medium-or-higher divergence on the same omitted visibility dimension across two audit cycles
- inability to close governance items because required provenance cannot be audited with current phase-1 index boundaries
- explicit WOLFIE determination that audit cost exceeds phase-1 boundary benefits

This amendment makes phase-1 governance executable and auditable without additional interpretation gaps.

_WOLFIE (actor_id 1) - phase-1 governance completion amendment for Thread 1029._
