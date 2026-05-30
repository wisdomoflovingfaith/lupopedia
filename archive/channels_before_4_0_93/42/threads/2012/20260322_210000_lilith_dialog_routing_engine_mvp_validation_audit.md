---
lupopedia.headers:
  version_when_written: "4.0.85"
  lupopedia.schema: "audit"
  file_path_from_root: "channels/42/threads/2012/20260322_210000_lilith_dialog_routing_engine_mvp_validation_audit.md"
  web_path: "http://www.lupopedia.com/channels/42/threads/2012/dialog_routing_engine_mvp_validation_audit"
  questions_toon: null
  channel_id: 42
  thread_id: 2012
  task_id: "task_ch42_th2012"
  actor_id: 2
  actor_name: "lilith"
  delegation_chain: "lilith:audit"
  artifact_type: "audit"
  artifact_kind: "dialog_routing_engine_mvp_validation"
  purpose: "Implementation audit of Thread 2012 routing MVP to verify real behavior against claimed completion"
  tags: ["audit", "lilith", "routing_mvp", "implementation_validation", "thread_2012"]

lupopedia.edges:
  outbound_edges:
    - { to: "database/lupopedia/mysql/install/install_new_lupopedia.sql", type: "audits", weight: 1.0, reason: "Validates routing decision schema surface" }
    - { to: "includes/HumanRequestService.php", type: "audits", weight: 1.0, reason: "Validates deterministic routing behavior" }
    - { to: "routes/human_requests.php", type: "audits", weight: 0.95, reason: "Validates route integration behavior" }
    - { to: "channels/42/threads/2012/20260322_203000_hephaestus_dialog_routing_engine_mvp_implementation_report.md", type: "validates", weight: 0.95, reason: "Compares claims vs implementation reality" }

lupopedia.footer:
  last_updated: "20260322_210000"
  thread_status: "completed"
---

# Dialog Routing Engine MVP Validation Audit

## Scope

Implementation audit of actual code and install SQL for Thread 2012 MVP.

## Required Field Outputs

- install_sql_status: PASS
- routing_logic_status: PASS
- strategy_boundary_status: PASS
- human_request_integration_status: PASS
- loop_prevention_status: PASS
- idempotency_status: FAIL
- source_of_truth_status: FAIL
- failure_handling_status: FAIL

FINAL:
- system_status: NON_COMPLIANT

## Evidence and Findings

### 1. Install SQL / Table Validity

Verdict: PASS

Validated in install authority:
- `lupo_routing_decisions` table exists.
- required fields exist with expected names/types.
- routing indexes exist (`idx_loop_break`, `idx_thread_created`, `idx_selected_status`).
- no doctrine violations (no FK/triggers/procedures/functions).

### 2. Routing Selection Logic

Verdict: PASS

Validated in `HumanRequestService`:
- candidate source is `lupo_actor_auth_users`
- filters are exactly:
  - `actor_id = ?`
  - `status = 'active'`
  - `is_deleted = 0`
- ordering is exactly:
  - `is_primary DESC`
  - `routing_priority ASC`
  - `auth_user_id ASC`

### 3. MVP Strategy Boundary

Verdict: PASS

Validated behavior:
- routing strategy used is hard-coded to `primary_then_fallback`
- no implemented round-robin / LRU / random selection paths found

### 4. Human Request Integration

Verdict: PASS

Validated behavior:
- routing decision row is inserted before dispatch request creation
- request links back via:
  - `subject_type = 'routing_decision'`
  - `subject_reference = routing_decision_id`
  - context entry includes routing decision and selected auth user id

### 5. Loop Prevention

Verdict: PASS

Validated behavior:
- `max_attempts = 3`
- loop-break key dimensions use `(actor_id, thread_id, trigger_type)`
- cooldown window implemented (10 minutes)
- `blocked_loop` decision path exists and returns without dispatch

### 6. Idempotency

Verdict: FAIL

Validated issue:
- idempotency check is read-then-insert without atomic guard (no lock/unique invariant on idempotency key dimensions).
- concurrent requests in same time bucket can both pass the pre-check and dispatch duplicate chains.

Result:
- duplicate-trigger suppression exists functionally but is not concurrency-safe; strict requirement "cannot create parallel duplicate request chains in same bucket" is not guaranteed.

### 7. Failure / Safety Review

Verdict: FAIL

Critical issues:
1. Inconsistent routing decision state on dispatch failure:
- decision is inserted with `decision_status='selected'` before `createRequest()`.
- if `createRequest()` throws, there is no catch/finalization update to mark terminal failure.
- leaves stale selected decision with `completed_ymdhis=0` and no dispatched request.

2. Route-level actor override risk:
- API route accepts client-supplied `actor_id` and passes it into routing method when present.
- this allows caller-specified actor context override instead of strict session actor binding.

### 8. Source-of-Truth Compliance

Verdict: FAIL

Findings:
- candidate selection correctly uses authoritative `lupo_actor_auth_users`.
- but routing path calls `createRequest()`, whose safety checks (`resolveAuthUserIdForActor`) rely on legacy `lupo_actors.auth_user_id` mapping for actor->auth resolution.
- this introduces legacy-column dependence in routing dispatch safety logic, not purely transitional/non-authoritative in execution behavior.

## Compliance Conclusion

Because critical implementation mismatches remain (idempotency race safety, failure-state consistency, and legacy mapping dependence in routing path):

- system_status: NON_COMPLIANT

## Required Corrective Actions

1. Make idempotency enforcement atomic for key `(actor_id, thread_id, trigger_type, time_bucket)`.
2. Ensure request-dispatch failure updates routing decision to explicit terminal failure state with completed timestamp.
3. Remove client actor override in route handler; bind actor_id strictly from authenticated session context.
4. Eliminate runtime authoritative dependence on `lupo_actors.auth_user_id` in routing dispatch path; resolve via canonical relationship model.
