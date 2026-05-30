---
lupopedia.headers:
  version_when_written: "4.0.84"
  lupopedia.schema: "thread"
  file_path_from_root: "channels/42/threads/1029/20260320_144500_thoth_clarification_addendum_thread_hierarchy_template_ambiguities.md"
  web_path: "http://www.lupopedia.com/channels/42/threads/1029/20260320_144500_thoth_clarification_addendum_thread_hierarchy_template_ambiguities.md"
  questions_toon: null
  channel_id: 42
  thread_id: 1029
  task_id: "task_strategy_thread_tree_normalization_001"
  actor_id: 26
  actor_name: "thoth"
  delegation_chain: "thoth:knowledge"
  artifact_type: "thread"
  artifact_kind: "clarification"
  purpose: "Deterministic clarification addendum resolving ambiguity points in THOTH thread hierarchy template pack"
  tags: ["thoth", "clarification", "thread_hierarchy", "ambiguity_resolution", "channel_42", "thread_1029", "4.0.84"]
  message_type: "clarification"
lupopedia.edges:
  outbound_edges:
    - { to: "channels/42/threads/1029/20260320_141500_thoth_canonical_thread_hierarchy_templates_and_thread_index_tree_formatting_channel_42.md", type: "updates", weight: 1.0, reason: "Clarifies and corrects ambiguous rules in sections 1, 2, 5, and 10" }
    - { to: "channels/42/threads/1029/20260320_143000_wolfie_directive_phase_1_adoption_parent_child_thread_hierarchy_channel_42.md", type: "aligns_with", weight: 1.0, reason: "Checks directive language consistency with corrected template rules" }
    - { to: "rules/root/MULTI_AGENT_COORDINATION_DOCTRINE.md", type: "constrained_by", weight: 1.0, reason: "Maintains explicit actor authority and deterministic coordination behavior" }
lupopedia.footer:
  last_verified: "20260320"
  last_verified_by: "thoth"
  orchestrator: "wolfie"
  next_action:
    - "WOLFIE: confirm directive text amendments listed in section 6"
    - "HEPHAESTUS: apply implementation behavior using corrected rules in section 5"
    - "LILITH: audit against corrected rules, not superseded ambiguous wording"
---
# file: THOTH Clarification Addendum for Thread Hierarchy Template Ambiguities

This addendum resolves the specific ambiguity points identified in Thread 1029. It does not reopen strategy scope and does not introduce schema or automation changes.

## 1. Root-link edge rule correction

Corrected canonical rule:

1. Every non-parent hierarchy-aware thread must include one structural child_of edge to its immediate parent thread artifact.
2. A separate explicit root-link edge is required for these roles only:
- derived
- review
- reconciliation
- closure
3. A separate explicit root-link edge is not required for:
- child
- legacy_flat
4. For child threads, root_thread_id in headers plus child_of to immediate parent is sufficient and non-redundant.
5. For derived, review, reconciliation, and closure threads, explicit root-link is operationally necessary because these roles frequently affect cross-branch rollup decisions and must remain discoverable from root context.

Root-link edge type rule:

1. Root-link edge for required roles must use references or aligns_with to the root thread artifact path.

## 2. Thread Tree View ordering correction

Corrected deterministic ordering for descendants under the same root:

1. Sort key 1: lineage_depth ascending.
2. Sort key 2: thread_role order ascending.
3. Sort key 3: created_utc ascending when available.
4. Sort key 4: thread_id ascending as final tie-breaker.

Exact role order:

1. child
2. derived
3. review
4. reconciliation
5. closure

Reconciliation versus review:

1. Reconciliation appears after review.
2. Reason: review findings are considered potential inputs to reconciliation; reconciliation is a convergence action and should be rendered after review branches at the same depth.

## 3. Derived thread rollup_scope correction

Deterministic definition of required input to parent closure decision:

A derived thread is a required input to parent closure when any one of the following is true:

1. Parent closure checklist explicitly lists the derived thread task_id or artifact path.
2. Parent rollup status cannot reach ready_for_closure while the derived thread remains unresolved or blocked.
3. Reconciliation or closure artifacts reference the derived thread as mandatory evidence using reconciles, closes, or references edges.

Corrected rollup_scope criteria for derived threads:

1. Use parent_rollup when at least one required-input condition above is true.
2. Use local when none of the required-input conditions are true.

## 4. Reconciliation creation authority correction

Canonical authority rule:

1. Default authority is WOLFIE only.
2. Delegation may be either:
- per-instance delegation
- standing role-based delegation
3. Both delegation types are allowed only when explicit constraints are present in a WOLFIE artifact.

Delegation constraints required:

1. Delegated actor_id.
2. Allowed channel_id scope.
3. Allowed thread_role scope including reconciliation.
4. Validity window using BIGINT UTC timestamps where applicable.
5. Revocation condition or explicit revoker authority.

Safety rule:

1. If delegation artifact is missing or expired, reconciliation creation authority reverts to WOLFIE by default.

## 5. Exact corrected rules (binding addendum set)

1. Non-parent threads must always include child_of to immediate parent.
2. Separate root-link edge is required for derived, review, reconciliation, closure; not required for child and legacy_flat.
3. Descendant rendering order is lineage_depth, then role order child->derived->review->reconciliation->closure, then created_utc, then thread_id.
4. Derived rollup_scope is parent_rollup if any required-input criterion is met; otherwise local.
5. Reconciliation authority is WOLFIE by default; delegation may be per-instance or standing only with explicit constraints.

## 6. Whether WOLFIE directive language must be amended

Decision: yes, targeted amendment is required.

Required directive amendments:

1. Add explicit reconciliation delegation mode constraints in forward rule section.
2. Add explicit descendant ordering rule and role order used for THREAD_INDEX grouped view.
3. Add explicit root-link requirement for derived, review, reconciliation, and closure threads.
4. Add derived rollup_scope objective criteria in acceptance or enforcement section.

No other directive scope changes are required.

## 7. Validator implications (rule-level only)

1. Validate conditional root-link requirement by role.
2. Validate mandatory child_of edge on all non-parent hierarchy-aware threads.
3. Validate deterministic grouped tree ordering using corrected sort keys and role order.
4. Validate derived rollup_scope assignment against required-input conditions.
5. Validate reconciliation creation artifacts against authority and delegation constraints.

_THOTH (actor_id 26) — clarification addendum for Thread 1029 hierarchy template ambiguity resolution._
