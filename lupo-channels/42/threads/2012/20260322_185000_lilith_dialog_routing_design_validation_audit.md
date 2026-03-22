---
lupopedia.headers:
  version_when_written: "4.0.85"
  lupopedia.schema: "audit"
  file_path_from_root: "lupo-channels/42/threads/2012/20260322_185000_lilith_dialog_routing_design_validation_audit.md"
  web_path: "http://www.lupopedia.com/channels/42/threads/2012/dialog_routing_design_validation_audit"
  last_modified_utc: "20260322_185000"
  channel_id: 42
  thread_id: 2012
  task_id: "task_ch42_th2012"
  actor_id: 2
  actor_name: "lilith"
  delegation_chain: "lilith:audit"
  artifact_type: "audit"
  artifact_kind: "dialog_routing_design_validation"
  purpose: "Destructive validation audit for dialog routing design readiness and hidden implementation risks"
  tags: ["audit", "lilith", "dialog_routing", "design_validation", "thread_2012"]

lupopedia.edges:
  outbound_edges:
    - { to: "lupo-docs/versions/4.0.85/dialog_routing_design.md", type: "audits", weight: 1.0, reason: "Primary design artifact under audit" }
    - { to: "lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql", type: "cross_checks", weight: 0.9, reason: "Verifies implementability against current schema surfaces" }

lupopedia.footer:
  last_updated: "20260322_185000"
  thread_status: "completed"
---

# Dialog Routing Design Validation Audit

## Scope

Destructive design audit for `dialog_routing_design.md`.

Focus:
- hidden flaws
- contradictions
- missing components
- non-implementable paths
- future failure modes

---

## Required Output Fields

- determinism_status: FAIL
- implementability_status: PARTIAL
- decision_storage_gap: CRITICAL
- failure_handling_status: FAIL
- role_model_status: FAIL
- escalation_loop_risk: HIGH
- broadcast_risk: HIGH

FINAL:
- system_design_status: NOT_READY

---

## 1. Determinism

Verdict: FAIL

Findings:
1. `primary_only` strategy does not define tie-break behavior when multiple active primaries exist.
2. `filtered_by_role` depends on role policy fallback that is not formally constrained, allowing policy drift to change outcomes for identical inputs.
3. Broadcast response resolution is undefined (multiple replies can create non-deterministic downstream state without deterministic resolver rule).

MVP path (`primary_then_fallback`) is deterministic, but full strategy surface is not.

---

## 2. Implementability

Verdict: PARTIAL

Implementable now with current state:
- `primary_then_fallback`
- `priority_order`
- `filtered_by_role` (basic form)
- constrained `broadcast` dispatch only

Not implementable now without new persisted state:
- `round_robin` (requires per-actor/per-context cursor state)
- `least_recently_used` (requires reliable assignment history surface and query contract)

Constraint:
- design mentions these strategies but does not bind required storage surfaces and update semantics.

---

## 3. Decision Storage Gap

Verdict: CRITICAL

Design requires routing decision record fields including:
- ordered `candidate_users_json`
- selected user
- strategy used
- reason
- status
- linkage refs for lineage

Current schema surfaces checked:
- `lupo_human_requests`: request transport, not full routing-decision ledger
- `lupo_decisions`: lacks explicit candidate list and routing-decision schema contract in current DDL

Critical gap:
- no canonical, declared persistence surface exists for complete routing-decision record required by design.

Per strict rule, this alone forces NOT_READY.

---

## 4. Failure Handling Validity

Verdict: FAIL

Findings:
1. No retry budget or max-attempt invariant is defined.
2. No deterministic terminal-state guard for repeated re-entry from same trigger in short intervals.
3. No idempotency key specified for duplicate trigger events, risking parallel fallback chains.

Result:
- fallback behavior is directionally deterministic but operationally unsafe under concurrency and repeated trigger conditions.

---

## 5. Role Model Consistency

Verdict: FAIL

Findings:
1. Role taxonomy is recommended, not enforced.
2. Semantics between `primary_owner` and `supporting_human` can conflict when both are present and primary flags diverge.
3. Undefined behavior for unknown role values in relationship rows.

Result:
- role filtering can become inconsistent across implementations without a controlled role dictionary and precedence contract.

---

## 6. Escalation Loop Risk

Verdict: HIGH

Loop vectors:
1. Trigger recursion: unresolved escalation can re-trigger routing from same actor/thread context.
2. Fallback recursion: terminal failure can bounce into emergency path that may route back into the same actor support pool.
3. Duplicate trigger race: concurrent events can spawn overlapping routing chains.

Missing safeguards:
- no explicit loop-break key (`actor_id + thread_id + trigger_type + window`)
- no max depth/attempt invariant
- no cooldown period contract

---

## 7. Broadcast Risk

Verdict: HIGH

Risks:
1. Unbounded fan-out if support pool grows.
2. Conflicting human responses without deterministic conflict resolution contract.
3. No response quorum/precedence rule.
4. No anti-storm constraints for repeated broadcasts on recurring triggers.

---

## 8. System Integration Compatibility

### `lupo_actor_auth_users`
- Supports candidate pool inputs (`actor_id`, role, priority, status, primary flag)
- Compatible for deterministic candidate selection.

### `lupo_human_requests`
- Compatible as dispatch transport (`target_auth_user_id`, context fields).
- Not sufficient as full routing decision ledger.

### Decision lineage system
- Conceptual integration is stated.
- Concrete storage mapping and required field persistence contract are not defined.
- This is the critical readiness blocker.

---

## Compliance Conclusion

Because a critical decision-storage gap exists and multiple failure/loop controls are undefined:

- system_design_status: NOT_READY

---

## Mandatory Pre-Implementation Fix List

1. Define canonical routing decision persistence surface with required fields and indexes (required_now).
2. Add deterministic strategy completeness rules (tie-breaks for all strategies) (required_now).
3. Define retry budget, loop-break key, and idempotency contract (required_now).
4. Define broadcast control rules (fan-out cap, response precedence, quorum/finalizer) (required_now).
5. Publish controlled role dictionary and unknown-role handling policy (required_now).
