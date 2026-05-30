---
lupopedia.headers:
  version_when_written: "4.0.84"
  lupopedia.schema: "thread"
  file_path_from_root: "channels/42/threads/1029/20260320_165500_wolfie_directive_amendment_final_adjudication_and_thread_index_behavior_rules_phase_1_closure.md"
  web_path: "http://www.lupopedia.com/channels/42/threads/1029/20260320_165500_wolfie_directive_amendment_final_adjudication_and_thread_index_behavior_rules_phase_1_closure.md"
  questions_toon: null
  channel_id: 42
  thread_id: 1029
  task_id: "task_strategy_thread_tree_normalization_001"
  actor_id: 1
  actor_name: "wolfie"
  delegation_chain: "wolfie:root"
  artifact_type: "thread"
  artifact_kind: "directive_amendment"
  purpose: "Final phase-1 adjudication and THREAD_INDEX behavior rules for provisional handling and governance closure"
  tags: ["wolfie", "directive_amendment", "final_adjudication", "phase_1_closure", "thread_index_behavior", "channel_42", "thread_1029", "4.0.84"]
  message_type: "directive"
lupopedia.edges:
  outbound_edges:
    - { to: "channels/42/threads/1029/20260320_164000_thoth_classification_artifact_provisional_thread_set_1021_1027_2002.md", type: "constrained_by", weight: 1.0, reason: "Uses proposed dispositions as adjudication input" }
    - { to: "channels/42/threads/1029/20260320_164500_thoth_adjudication_queue_phase_1_provisional_thread_decisions.md", type: "constrained_by", weight: 1.0, reason: "Queue rows become adjudication execution source" }
    - { to: "channels/42/THREAD_INDEX.md", type: "updates", weight: 1.0, reason: "Defines deterministic phase-1 behavior for out_of_scope and remain_provisional outcomes" }
    - { to: "channels/42/threads/1029/20260320_163000_wolfie_directive_amendment_phase_1_governance_completion_rules_thread_hierarchy_visibility_boundaries.md", type: "updates", weight: 1.0, reason: "Completes governance ambiguities for final phase-1 closure" }
    - { to: "channels/42/threads/1029/20260320_150000_wolfie_directive_amendment_clarified_enforcement_rules_channel_42_thread_hierarchy_phase_1.md", type: "constrained_by", weight: 1.0, reason: "Severity and corrective path rules remain binding" }
lupopedia.footer:
  last_verified: "20260320"
  last_verified_by: "wolfie"
  orchestrator: "wolfie"
  next_action:
    - "WOLFIE: issue omnibus adjudication execution directive using queue rows before due timestamp"
    - "HEPHAESTUS: apply only adjudicated THREAD_INDEX updates from omnibus execution directive"
    - "LILITH: re-audit evidence validity, directive traceability, and adjudication execution completeness"
    - "THOTH: reissue queue only if evidence path errors are confirmed"
---
# file: WOLFIE Directive Amendment - Final Adjudication and THREAD_INDEX Behavior Rules for Phase-1 Closure

This amendment closes remaining phase-1 provisional-handling governance ambiguity. It is not strategy, not schema work, and does not expand THREAD_INDEX scope beyond approved phase-1 boundaries.

## 1. out_of_scope thread handling (deterministic)

Decision:

1. out_of_scope threads remain listed in THREAD_INDEX during phase-1 for audit continuity.
2. No row removal occurs in phase-1.

Required field behavior for out_of_scope disposition:

1. thread_role remains legacy_flat.
2. classification_confidence remains provisional until final adjudication execution writes final state.
3. parent_thread_id remains 0.
4. root_thread_id remains thread_id.
5. lineage_depth remains 0.
6. rollup_scope remains none.

State model decision:

1. No new classification state is introduced in phase-1.
2. out_of_scope remains a queue/disposition value in adjudication artifacts, not a THREAD_INDEX classification_confidence value.

Archival/redirect representation:

1. Redirect or archival intent is represented in adjudication artifact rationale and evidence paths.
2. Index row persists until WOLFIE adjudication execution directive explicitly updates or retires row state.

## 2. remain_provisional versus decision_due semantics

Decision:

1. remain_provisional rows still require adjudication by decision_due_ymdhis.
2. remain_provisional is an adjudication outcome option, not an automatic deferral outside phase-1.

Meaning of decision_due_ymdhis for remain_provisional rows:

1. By due time, WOLFIE must explicitly decide one of:
- continue provisional with explicit review_due_ymdhis
- change to out_of_scope disposition
- move to candidate_confirmed_role for next cycle evaluation

Second-stage queue rule:

1. second-stage queue is required only if WOLFIE adjudicates continue provisional.
2. second-stage queue must include review_due_ymdhis and blocked_dependency_policy.

Closure impact:

1. remain_provisional rows do not block phase-1 closure if explicitly adjudicated by due timestamp.
2. unadjudicated remain_provisional rows by due timestamp do block phase-1 closure.

## 3. evidence path validity rule

Validity rule:

1. Every evidence_path must exist in repository at audit time.
2. Every evidence_path must be resolvable as a concrete file path.
3. Cross-thread references are valid only when referenced file exists and belongs to declared evidence context.

Severity mapping:

1. missing file in evidence_paths: high.
2. incorrect/unresolvable path syntax or location: high.
3. cross-thread reference file exists but does not support claimed rationale context: medium.

Correction rule:

1. LILITH reports invalid evidence-path findings.
2. THOTH must reissue impacted classification/queue artifact with corrected paths within same enforcement cycle.
3. HEPHAESTUS does not correct evidence paths unless explicitly directed by WOLFIE for formatting-only path normalization.

## 4. directive traceability requirement

Visibility rule:

1. Referenced directives do not need to exist in the same thread.
2. Referenced directives must be globally resolvable by repository path.

Severity mapping for traceability gaps:

1. referenced directive path missing/unresolvable: high.
2. referenced directive exists globally but not in same thread: no violation.
3. referenced directive exists but is semantically wrong target for stated claim: medium.

Correction behavior:

1. Reporter (LILITH) files traceability issue artifact.
2. WOLFIE issues scoped correction directive.
3. Artifact owner (THOTH/WOLFIE/other author) must update edge/path references.
4. LILITH re-audits closure.

## 5. adjudication execution format

Execution decision:

1. WOLFIE adjudicates using one omnibus execution directive covering all queue rows by default.
2. Per-thread directives are allowed only when a row is explicitly flagged exceptional.

Required output structure for omnibus adjudication directive:

1. queue_artifact_path
2. adjudication_run_ymdhis
3. one row per thread_id with fields:
- thread_id
- prior_state
- final_state
- final_classification_confidence
- index_action (retain, update, retire)
- rationale
- effective_ymdhis
- evidence_paths

Required linkage:

1. Omnibus directive must edge-link to adjudication queue artifact and classification artifact.
2. Each decision row must be traceable back to queue thread_id.

## 6. phase-1 closure condition update

Phase-1 complete only when all are true:

1. classification artifact exists.
2. adjudication queue exists.
3. adjudication decisions are executed by WOLFIE directive.
4. THREAD_INDEX reflects final adjudicated state.
5. no unresolved governance ambiguity remains in follow-up LILITH audit.

remain_provisional closure rule:

1. remain_provisional threads do not block closure when explicitly adjudicated with next review due and dependency policy.
2. remain_provisional threads do block closure if they are not adjudicated by due timestamp.

This amendment finalizes phase-1 provisional handling and THREAD_INDEX behavior governance.

_WOLFIE (actor_id 1) - final phase-1 adjudication and index-behavior amendment for Thread 1029._
