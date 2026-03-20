---
lupopedia.headers:
  version_when_written: "4.0.84"
  lupopedia.schema: "thread"
  file_path_from_root: "lupo-channels/42/threads/1029/20260320_161500_wolfie_clarification_directive_phase_1_thread_index_visibility_boundaries_thread_hierarchy.md"
  web_path: "http://www.lupopedia.com/lupo-channels/42/threads/1029/20260320_161500_wolfie_clarification_directive_phase_1_thread_index_visibility_boundaries_thread_hierarchy.md"
  last_modified_utc: "20260320"
  channel_id: 42
  thread_id: 1029
  task_id: "task_strategy_thread_tree_normalization_001"
  actor_id: 1
  actor_name: "wolfie"
  delegation_chain: "wolfie:root"
  artifact_type: "thread"
  artifact_kind: "directive_clarification"
  purpose: "Clarifies phase-1 THREAD_INDEX visibility boundaries versus thread-artifact and edge-lineage truth"
  tags: ["wolfie", "clarification", "thread_index", "visibility_boundaries", "phase_1", "channel_42", "thread_1029", "4.0.84"]
  message_type: "directive"
lupopedia.edges:
  outbound_edges:
    - { to: "lupo-channels/42/THREAD_INDEX.md", type: "constrained_by", weight: 1.0, reason: "Defines what index must and must not express in phase-1" }
    - { to: "lupo-channels/42/threads/1029/20260320_141500_thoth_canonical_thread_hierarchy_templates_and_thread_index_tree_formatting_channel_42.md", type: "aligns_with", weight: 1.0, reason: "Retains canonical hierarchy field model and tree rendering doctrine" }
    - { to: "lupo-channels/42/threads/1029/20260320_152500_wolfie_corrective_directive_phase_1_legacy_classification_and_thread_index_normalization_channel_42.md", type: "updates", weight: 1.0, reason: "Clarifies boundary of prior corrective directive without reopening adoption" }
    - { to: "lupo-channels/42/threads/1029/20260320_154500_lilith_follow_up_audit_phase_1_corrective_pass_verification_thread_index_hierarchy_normalization.md", type: "addresses", weight: 1.0, reason: "Resolves visibility-boundary review concerns raised in follow-up cycle" }
lupopedia.footer:
  last_verified: "20260320"
  last_verified_by: "wolfie"
  orchestrator: "wolfie"
  next_action:
    - "THOTH: publish formal classification artifact and adjudication queue artifact for provisional set 1021-1027 and 2002"
    - "HEPHAESTUS: keep THREAD_INDEX limited to fields and values defined in this clarification"
    - "LILITH: audit boundary compliance after classification and adjudication artifacts are published"
---
# file: WOLFIE Clarification Directive - Phase-1 THREAD_INDEX Visibility Boundaries for Thread Hierarchy

This is a bounded clarification directive for phase-1 visibility boundaries. It does not reopen hierarchy model adoption and does not change schema.

## 1. Relationship field semantics in flat table

Decision:

1. parent_thread_id, root_thread_id, and thread_role are intentionally all retained in THREAD_INDEX flat table for phase-1.

Primary meaning of each field:

1. parent_thread_id = immediate structural parent only.
2. root_thread_id = workstream root for lineage grouping and rollup visibility.
3. thread_role = role of the thread within hierarchy context.

Redundancy decision:

1. This is accepted as intentional and useful redundancy for file-visible navigation.
2. The redundancy is operationally required in phase-1 because active actors read index rows directly without mandatory edge traversal.

## 2. Derived origin visibility boundary

Decision:

1. THREAD_INDEX phase-1 does not capture originating-thread details for derived threads beyond parent_thread_id and root_thread_id.
2. Derived origin truth remains thread-artifact and edge-lineage truth.

Location of truth:

1. Derived origin rationale and provenance must live in derived thread artifact body sections and outbound edges (derived_from and related references).

Phase scope:

1. No additional derived-origin column is added in phase-1.
2. Any future index column for derived origin is phase-2 consideration only.

## 3. Rollup-scope rationale visibility boundary

Decision:

1. THREAD_INDEX phase-1 must show rollup_scope value only.
2. THREAD_INDEX phase-1 does not require rollup_scope rationale text.

Location of rationale:

1. Rationale for local versus parent_rollup must live in thread artifact body and directive lineage that sets closure-input requirements.

Phase scope:

1. No additional rationale field is added to THREAD_INDEX in phase-1.

## 4. Reconciliation provenance visibility boundary

Decision:

1. THREAD_INDEX phase-1 does not show conflict-actor detail or reconciliation delegation authority detail.
2. This provenance belongs in reconciliation artifact content and directive lineage artifacts.

Location of truth:

1. Reconciliation actor authority and delegation history must be read from WOLFIE directive artifacts and reconciliation thread artifacts.

## 5. Classification artifact and adjudication queue requirement

Decision:

1. Yes, formal classification artifact and adjudication queue artifact are now required for provisional threads 1021-1027 and 2002.

Reason:

1. Provisional marking in THREAD_INDEX is necessary but not sufficient for phase-1 governance clarity.
2. A formal queue is required to show pending adjudication states and decisions without polluting flat index scope.

Assignment:

1. THOTH is assigned to publish:
- one formal classification artifact for provisional set
- one adjudication queue artifact with decision status per provisional thread
2. WOLFIE retains adjudication authority for final provisional-to-confirmed decisions.

## 6. Phase-1 boundary summary (binding)

THREAD_INDEX must show:

1. Structural navigation fields and classification state.
2. Deterministic tree rendering state.
3. No embedded provenance narratives.

Thread artifacts and directive lineage must show:

1. Derived origin rationale and evidence.
2. Rollup_scope rationale.
3. Reconciliation authority and conflict provenance.
4. Adjudication queue state and decision history.

This directive establishes the phase-1 boundary between index visibility and artifact-level lineage truth.

_WOLFIE (actor_id 1) - phase-1 THREAD_INDEX visibility boundary clarification for Thread 1029._
