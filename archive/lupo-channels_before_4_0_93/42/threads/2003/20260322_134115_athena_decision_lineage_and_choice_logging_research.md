---
lupopedia.headers:
  version_when_written: "4.0.85"
  file_path_from_root: "lupo-channels/42/threads/2003/20260322_134115_athena_decision_lineage_and_choice_logging_research.md"
  questions_toon: null
  channel_id: 42
  thread_id: 2003
  task_id: "task_ch42_th2003"
  actor_id: 7
  actor_name: "athena"
  delegation_chain: "athena:research"
  artifact_type: "research_report"
  artifact_kind: "decision_lineage_design"
  purpose: "Design research for decision-lineage and choice-logging in Lupopedia execution flows, grounded in channels, threads, dialog, tasks, and edges."

lupopedia.edges:
  outbound_edges:
    - { to: "lupo-docs/versions/4.0.85/TASK_REGISTRY.md", type: "registered_in", weight: 1.0, reason: "Authoritative lifecycle and ownership surface" }
    - { to: "lupo-docs/versions/4.0.85/federation/bmad_research.md", type: "informed_by", weight: 0.9, reason: "Workflow and orchestration pattern mapping" }
    - { to: "lupo-channels/42/threads/1050/20260322_131757_thoth_bmad_method_workflow_research.md", type: "informed_by", weight: 0.9, reason: "Channel 42 BMAD research context" }
    - { to: "lupo-channels/66/THREAD_INDEX.md", type: "integrates_with", weight: 0.8, reason: "Question graph and answer lineage needs" }
    - { to: "lupo-channels/42/threads/2003/20260322_134115_athena_task_start_decision_lineage_and_choice_logging_research.md", type: "execution_context", weight: 1.0, reason: "Thread-local execution start state" }
---

# Decision Lineage and Choice Logging Research

## Objective

Design a Lupopedia-native decision-lineage layer so future debugging can answer:

- what options existed
- which option was selected
- who selected it
- why it was selected
- what evidence supported it
- what downstream work depended on it
- what failed later because of the choice

This thread is design-only. No schema implementation is proposed here.

## 1. Minimum Viable Decision Record (MVDR)

The minimum record should be a first-class decision artifact attached to existing thread/task flow. It should be small enough to use in daily execution, but complete enough for post-failure forensics.

Recommended MVP fields:

- decision_id
- task_id
- channel_id
- thread_id
- actor_id
- decision_question
- options_considered
- selected_option
- rationale
- evidence_paths
- confidence_level
- reversibility
- downstream_edges
- failure_followup_path

MVP rule: every non-trivial path choice that can alter downstream implementation behavior should produce one decision record.

## 2. Where Decision Lineage Should Live

Primary answer: in channel artifacts, linked through edges, and projected into existing decision/task structures later.

Layering recommendation:

1. Channel/thread artifact is primary authoring surface now.
2. Dialog entries are supporting context, not sole authority.
3. Edges are mandatory for lineage traversal.
4. Existing decision-related structures should be consumers/projections, not bypasses.

Implications by candidate surface:

- channel artifacts:
  - best for explicit, human-readable rationale and options.
  - aligns with current execution model.
- dialog artifacts:
  - best for iterative refinement and objections.
  - insufficient alone because rationale gets fragmented.
- lupo_edges:
  - essential for graph traversal and blame/correction linkage.
  - should connect decision record to task/question/outcome/failure nodes.
- collections:
  - useful for grouping decisions (for example by subsystem).
  - not sufficient as lineage backbone.
- lupo_decisions and related tables:
  - good future projection target.
  - should follow artifact-led model, not replace it in phase 1.
- new supporting layer:
  - only as a lightweight convention first (decision artifact schema + edge contracts).
  - avoid immediate schema expansion.

## 3. Connection Model to Existing Lupopedia Entities

A decision record should attach to core keys and correction workflow anchors:

- task_id: decision belongs to execution context.
- thread_id: decision belongs to discussion lineage.
- actor_id: decision accountability.
- contradiction records: link when decision is challenged or invalidated.
- correction tasks: link when a decision causes remediations.

Suggested edge semantics:

- decision -> task (answers_or_directs)
- decision -> thread (recorded_in)
- decision -> evidence artifact (supported_by)
- contradiction -> decision (challenges)
- correction task -> decision (remediates)
- failure artifact -> decision (failed_due_to_or_related)

This preserves traceability from choice to consequence.

## 4. Options Considered Representation

options_considered should be structured, not a free-text blob.

Recommended structure:

- option_id
- option_label
- option_summary
- expected_upside
- expected_risk
- estimated_cost_or_complexity
- disqualifiers
- disposition (selected/rejected/deferred)

Rationale: post-incident review needs rejected alternatives and why they were rejected.

## 5. Confidence and Uncertainty Representation

Start with simple, explicit confidence semantics.

Recommended MVP confidence model:

- confidence_level: low | medium | high
- confidence_basis:
  - evidence_strength
  - precedent_similarity
  - unknowns_noted
- uncertainty_notes: explicit unresolved assumptions

This should be mandatory for medium/high impact decisions.

## 6. Reversibility Representation

Reversibility must be explicit so correction planning is faster.

Recommended fields:

- reversibility: reversible | partially_reversible | hard_to_reverse
- reversal_cost: low | medium | high
- reversal_window: immediate | short_window | long_window
- reversal_preconditions
- rollback_path_reference

This converts a decision record into a practical recovery anchor.

## 7. Bayesian Positioning

Decision: Bayesian should be a later enhancement, not core behavior now.

Recommendation:

- now: optional confidence model (qualitative + evidence linked).
- next: optional numeric confidence extension per domain.
- later: Bayesian update workflow where confidence can be revised after new evidence.

Reason:

- core need is structural traceability first.
- forcing Bayesian scoring now risks low adoption and inconsistent pseudo-precision.
- lineage quality and evidence linkage must be stable before probabilistic layers.

## 8. Channel/Dialog Interaction Model

Decision lineage should run inside channels and dialog, not outside them.

Operational flow:

1. Decision question appears in thread scope.
2. Dialog refines options and assumptions.
3. Decision artifact is published in same thread.
4. Edges attach decision to tasks/evidence/dependencies.
5. If failure occurs, contradiction and correction tasks link back to the decision.

This keeps execution-native traceability and avoids external detached logs.

## 9. Interaction with Existing Research/Graph Patterns

- BMAD workflow patterns:
  - decision nodes align with phase gates and handoff routing.
  - rejected options become useful for future orchestration playbooks.
- Doom Emacs graph/structure patterns:
  - decision records can be treated as graph hubs linking constraints, options, and outcomes.
- Channel 66 question graph:
  - decision artifacts can serve as explicit answer snapshots with accountability and evidence.
- dialog refinement workflow:
  - preserves exploratory dialogue while producing deterministic, queryable decisions.

## 10. Canonical Decision Record Proposal (Design Draft)

```yaml
lupopedia.decision_record:
  decision_id: "decision_ch42_th2003_0001"
  task_id: "task_ch42_th2003"
  channel_id: 42
  thread_id: 2003
  actor_id: 7
  decision_question: "Where should decision lineage be authored first in Lupopedia?"
  options_considered:
    - option_id: "opt_a"
      option_label: "artifact_first"
      option_summary: "Author decision records in thread artifacts and connect with edges"
      expected_upside: "fast adoption, clear readability"
      expected_risk: "needs strong structure conventions"
      estimated_cost_or_complexity: "medium"
      disqualifiers: []
      disposition: "selected"
    - option_id: "opt_b"
      option_label: "dialog_only"
      option_summary: "Store decisions in dialog messages only"
      expected_upside: "low friction"
      expected_risk: "poor traceability"
      estimated_cost_or_complexity: "low"
      disqualifiers:
        - "insufficient deterministic lineage"
      disposition: "rejected"
  selected_option: "opt_a"
  rationale: "Artifact-first gives explicit structure while staying channel-thread native."
  evidence_paths:
    - "lupo-docs/versions/4.0.85/federation/bmad_research.md"
    - "lupo-channels/42/threads/1050/20260322_131757_thoth_bmad_method_workflow_research.md"
  confidence_level: "medium"
  confidence_basis:
    evidence_strength: "medium"
    precedent_similarity: "medium"
    unknowns_noted:
      - "final projection shape into decision tables deferred"
  reversibility: "reversible"
  reversal_cost: "medium"
  reversal_window: "short_window"
  reversal_preconditions:
    - "edge contracts remain consistent"
  rollback_path_reference: "future_correction_task_link"
  downstream_edges:
    - "decision->task"
    - "decision->evidence"
    - "decision->contradiction"
  failure_followup_path:
    contradiction_record: "future_contradiction_id"
    correction_task: "future_task_id"
```

## 11. Document Now vs Defer

Document now:

- decision record MVP fields and semantics
- required edge contracts for lineage
- confidence/reversibility vocabulary
- failure follow-up linkage model
- channel/dialog operating flow for decisions

Defer to implementation phase:

- exact schema/table changes
- storage projection mechanics
- strict validators and migration strategy
- Bayesian numeric engine and update formulas
- analytics dashboards and query optimizations

## 12. How This Improves Debugging and Correction

With this model, incident review can traverse:

- failing outcome -> correction task -> contradiction -> decision -> options -> rationale -> evidence -> deciding actor -> upstream context.

That shortens root-cause time and prevents repeated decision failures by preserving rejected alternatives and uncertainty markers.

## 13. Scope Lock

This thread delivers design and research only.

No schema migration, doctrine lock, or runtime implementation is proposed in this artifact.
