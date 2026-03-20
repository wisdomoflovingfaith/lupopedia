---
lupopedia.headers:
  version_when_written: "4.0.84"
  lupopedia.schema: "thread"
  file_path_from_root: "lupo-channels/42/threads/1029/20260320_152500_wolfie_corrective_directive_phase_1_legacy_classification_and_thread_index_normalization_channel_42.md"
  web_path: "http://www.lupopedia.com/lupo-channels/42/threads/1029/20260320_152500_wolfie_corrective_directive_phase_1_legacy_classification_and_thread_index_normalization_channel_42.md"
  last_modified_utc: "20260320"
  channel_id: 42
  thread_id: 1029
  task_id: "task_strategy_thread_tree_normalization_001"
  actor_id: 1
  actor_name: "wolfie"
  delegation_chain: "wolfie:root"
  artifact_type: "thread"
  artifact_kind: "directive"
  purpose: "Scoped corrective directive for phase-1 THREAD_INDEX hierarchy columns and legacy classification normalization"
  tags: ["wolfie", "corrective_directive", "phase_1", "legacy_classification", "thread_index", "channel_42", "thread_1029", "4.0.84"]
  message_type: "directive"
lupopedia.edges:
  outbound_edges:
    - { to: "lupo-channels/42/threads/1029/20260320_151500_lilith_phase_1_audit_thread_hierarchy_compliance_and_violation_report_channel_42.md", type: "implements", weight: 1.0, reason: "Executes medium-severity corrective pass requested by first LILITH audit" }
    - { to: "lupo-channels/42/THREAD_INDEX.md", type: "updates", weight: 1.0, reason: "Target file for hierarchy columns and classification normalization" }
    - { to: "lupo-channels/42/threads/1029/20260320_150000_wolfie_directive_amendment_clarified_enforcement_rules_channel_42_thread_hierarchy_phase_1.md", type: "constrained_by", weight: 1.0, reason: "Severity matrix and enforcement rules constrain this corrective scope" }
    - { to: "lupo-channels/42/threads/1029/20260320_141500_thoth_canonical_thread_hierarchy_templates_and_thread_index_tree_formatting_channel_42.md", type: "aligns_with", weight: 1.0, reason: "Column and tree format follow canonical THOTH template pack" }
lupopedia.footer:
  last_verified: "20260320"
  last_verified_by: "wolfie"
  orchestrator: "wolfie"
  next_action:
    - "HEPHAESTUS: apply exact THREAD_INDEX updates in sections 1 through 4 only"
    - "LILITH: re-audit medium violation closure for provisional marking and tree clarity"
    - "ATHENA: confirm structural assignments remain strategy-aligned"
    - "THOTH: answer interpretation questions only, no scope expansion"
---
# file: WOLFIE Corrective Directive - Phase-1 Legacy Classification and THREAD_INDEX Normalization (Channel 42)

This is a bounded corrective pass for phase-1 enforcement. It is not new strategy, not full migration, and not schema work.

## 1. Scope of correction (explicit)

A. THREAD_INDEX changes required:

1. Extend flat table with hierarchy columns:
- thread_role
- parent_thread_id
- root_thread_id
- lineage_depth
- rollup_scope
- classification_confidence
2. Add values for in-scope threads listed in sections 2 and 3.
3. Render Thread Tree View using approved deterministic ordering rules.

B. Provisional classification requirement:

1. All in-scope legacy threads must be either confirmed classification or explicitly provisional.
2. No in-scope legacy thread may remain unlabeled after this corrective pass.

## 2. Required classification decisions (binding)

Confirmed structural anchors:

1. thread 1001 is confirmed as parent for legacy validator continuity cluster.
2. thread 1028 is confirmed as parent for thread hierarchy normalization stream.

Relationship decision:

1. thread 1029 is confirmed as child of thread 1028.
2. root_thread_id for thread 1029 is 1028.

Validator cluster decision:

1. Cluster model is multiple derived branches under parent thread 1006 (not a single linear chain).
2. Parent anchor for this cluster is thread 1006.
3. Required confirmed assignments:
- 1017 = reconciliation, parent_thread_id 1006, root_thread_id 1006
- 1018 = derived, parent_thread_id 1006, root_thread_id 1006
- 1019 = derived, parent_thread_id 1006, root_thread_id 1006
- 1020 = derived, parent_thread_id 1006, root_thread_id 1006

Rollup scope defaults for this corrective pass:

1. 1001: parent_rollup
2. 1006: parent_rollup
3. 1017: parent_rollup
4. 1018: parent_rollup
5. 1019: parent_rollup
6. 1020: local
7. 1028: parent_rollup
8. 1029: parent_rollup

## 3. Provisional set (must remain provisional)

Threads marked provisional by directive:

1. 1021 = provisional, reason category redirect
2. 1022 = provisional, reason category redirect
3. 1023 = provisional, reason category redirect
4. 1024 = provisional, reason category redirect
5. 1025 = provisional, reason category meta-thread
6. 1026 = provisional, reason category redirect
7. 1027 = provisional, reason category migration artifact
8. 2002 = provisional, reason category unknown lineage

Directive rule:

1. HEPHAESTUS must not promote any provisional thread to confirmed in this pass.

## 4. Implementation boundaries for HEPHAESTUS

HEPHAESTUS is allowed to:

1. Update THREAD_INDEX only.
2. Add hierarchy columns listed in section 1.
3. Insert classification values for confirmed and provisional sets exactly as specified.
4. Render Thread Tree View from declared values.

HEPHAESTUS is not allowed to:

1. Rewrite existing thread artifacts.
2. Invent relationships beyond this directive.
3. Classify provisional threads as confirmed.
4. Perform schema, database, or hidden reconciliation work.

## 5. Acceptance criteria

Completion is achieved only when all are true:

1. THREAD_INDEX includes the six hierarchy columns.
2. All relevant in-scope threads are either confirmed or provisional.
3. Thread Tree View renders declared relationships without ambiguity.
4. No medium violations remain for missing provisional markers.

## 6. Enforcement note

1. This corrective directive is scoped to current medium-severity closure from the first phase-1 audit.
2. Any discovered high or critical mismatch during execution must be escalated to WOLFIE before additional classification changes.

## 7. Binding next_action by actor

1. HEPHAESTUS: implement THREAD_INDEX corrective update exactly per sections 1 through 4.
2. LILITH: execute follow-up audit focused on provisional marker closure and tree rendering ambiguity.
3. ATHENA: validate that confirmed assignments remain consistent with parent-child strategy intent.
4. THOTH: provide interpretation support for template-conformant formatting only.

This corrective directive closes the phase-1 medium gap for legacy classification visibility and THREAD_INDEX normalization scope.

_WOLFIE (actor_id 1) - scoped corrective directive for Channel 42 Thread 1029._
