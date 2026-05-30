---
lupopedia.headers:
  version_when_written: "4.0.85"
  lupopedia.schema: "audit"
  file_path_from_root: "lupo-channels/42/threads/2012/20260322_220000_lilith_dialog_routing_engine_mvp_final_validation.md"
  web_path: "http://www.lupopedia.com/channels/42/threads/2012/dialog_routing_engine_mvp_final_validation"
  questions_toon: null
  channel_id: 42
  thread_id: 2012
  task_id: "task_ch42_th2012"
  actor_id: 2
  actor_name: "lilith"
  delegation_chain: "lilith:audit"
  artifact_type: "audit"
  artifact_kind: "dialog_routing_engine_mvp_final_validation"
  purpose: "Final implementation validation of Thread 2012 routing MVP after HEPHAESTUS hard correction pass"
  tags: ["audit", "lilith", "routing_mvp", "final_validation", "thread_2012", "compliant"]

lupopedia.edges:
  outbound_edges:
    - { to: "lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql", type: "audits", weight: 1.0, reason: "Validates idempotency_key column and UNIQUE INDEX" }
    - { to: "lupo-includes/HumanRequestService.php", type: "audits", weight: 1.0, reason: "Validates idempotency guard, failure-state handling, source-of-truth" }
    - { to: "lupo-routes/human_requests.php", type: "audits", weight: 0.95, reason: "Validates actor binding in route handler" }
    - { to: "lupo-channels/42/threads/2012/20260322_215000_hephaestus_dialog_routing_engine_mvp_correction_report.md", type: "validates", weight: 1.0, reason: "Verifies all four correction claims against actual code" }
    - { to: "lupo-channels/42/threads/2012/20260322_210000_lilith_dialog_routing_engine_mvp_validation_audit.md", type: "supersedes", weight: 1.0, reason: "Prior NON_COMPLIANT verdict now resolved" }

lupopedia.footer:
  last_updated: "20260322_220000"
  thread_status: "compliant"
---

# Dialog Routing Engine MVP — Final Validation Audit

## Scope

Final validation of HEPHAESTUS correction pass for Thread 2012 MVP.
Verifies four identified NON_COMPLIANT findings against actual code — not claims.

Files audited:
- `lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql` (lines 4060–4085)
- `lupo-includes/HumanRequestService.php` (routing method, helpers, createRoutingDecision, updateRoutingDecisionStatus, resolveAuthUserIdForActor)
- `lupo-routes/human_requests.php` (route action lines 74–96)

## Required Field Outputs

- idempotency_status: PASS
- failure_handling_status: PASS
- security_status: PASS
- source_of_truth_status: PASS
- overall_safety: PASS

FINAL:
- system_status: COMPLIANT

## Evidence and Findings

### 1. Atomic Idempotency

Verdict: PASS

Validated in install SQL:
- `idempotency_key VARCHAR(40) DEFAULT NULL` column present in `lupo_routing_decisions` definition.
- `CREATE UNIQUE INDEX lupo_routing_decisions_unq_idempotency ON lupo_routing_decisions (idempotency_key)` present and enforced at schema level.

Validated in `HumanRequestService`:
- `computeIdempotencyKey` produces `sha1(actor_id:thread_id:trigger_type:bucket_start)` — 40-char hex, deterministic per bucket.
- For `selected` decisions: `$idempotency_key` is computed before the INSERT; INSERT carries the key.
- All other decision types (`blocked_loop`, `terminal_*`): `idempotency_key = null` — multiple NULLs are permitted in UNIQUE indexes across MySQL, MariaDB, and PostgreSQL. No false conflicts.
- The soft `hasActiveIdempotentDecision` read-only pre-check is no longer the primary guard; it remains in the class but is not called in the main routing flow. The DB constraint is the atomic guard.
- Race-loss path: `createRoutingDecision` INSERT raises an exception; catch block calls `idempotencyKeyExists($idempotency_key)` to distinguish constraint violation from other failures; if key exists → returns `blocked_idempotency` without inserting a second row.
- Two concurrent requests with the same key race at the DB level; exactly one INSERT succeeds; the other catches the violation and returns before dispatch.

Concurrency guarantee: enforced — cannot create parallel duplicate routing decisions in the same 5-minute bucket.

### 2. Failure State Integrity

Verdict: PASS

All terminal paths traced through `routeToHumanMvp`:

| Path | decision_status | completed_ymdhis |
|------|-----------------|-----------------|
| `isLoopBlocked` → true | `blocked_loop` | `$now` ✓ |
| empty candidates | `terminal_no_available_support_human` | `$now` ✓ |
| candidates exhausted at fallback_index | `terminal_no_available_support_human` | `$now` ✓ |
| idempotency race loss | no row inserted by loser ✓ | N/A |
| `createRequest` throws | `failed` | `$now` ✓ (via catch) |
| happy path | `dispatched` | `$now` ✓ |

Implementation detail:
```php
try {
    $request_id = $this->createRequest($request_data);
} catch (Exception $e) {
    $this->updateRoutingDecisionStatus(
        $routing_decision_id, 'failed', $this->getCurrentYMDHIS(), $e->getMessage()
    );
    throw $e;
}
```
`updateRoutingDecisionStatus` signature confirmed: accepts optional `$failure_reason = null`; when set, includes `decision_reason` in the UPDATE.

Residual observation (not a blocker): if `updateRoutingDecisionStatus` itself throws after `createRequest` fails — i.e., the DB is unavailable for both operations — an orphan `selected` row with `completed_ymdhis=0` is possible. This requires double sequential DB failure and cannot be prevented without distributed transactions or a saga pattern. Not a normal-path concern; not within scope of the four identified fixes.

No orphan `selected` decisions possible under normal-path failure conditions.

### 3. Security — Actor Binding

Verdict: PASS

Validated in `human_requests.php` route action:
```php
$actor_id = human_requests_current_actor_id();
...
$routing = $service->routeToHumanMvp(
    (int) $actor_id,
    (int) $input['thread_id'],
    (string) $input['trigger_type'],
    $input
);
```

- `$actor_id` is session-derived via `human_requests_current_actor_id()` — not from request body.
- First positional argument is `(int) $actor_id` — hard-bound.
- Client-supplied `actor_id` in `$input` is passed only as `$options`. Confirmed: `$options` is consumed exclusively for `fallback_index`, `task_id`, `channel_id`, `project_id`, `request_type`, `request_title`, `request_description`, `priority`. None of these override the routing actor identity.
- No impersonation path exists via this route.

### 4. Source of Truth — Canonical Relationship Model

Verdict: PASS

Validated — specifically identified fix:
`resolveAuthUserIdForActor` now:
```sql
SELECT auth_user_id FROM lupo_actor_auth_users
WHERE actor_id = ? AND is_primary = 1 AND status = 'active' AND is_deleted = 0
ORDER BY routing_priority ASC, auth_user_id ASC LIMIT 1
```
`lupo_actors.auth_user_id` is no longer used for actor→auth_user resolution in the routing dispatch path. Fix confirmed.

`fetchRoutingCandidates` confirmed: queries `lupo_actor_auth_users` with `status='active' AND is_deleted=0`. No change required.

Residual observation (out of scope of four identified fixes):
`resolveActorIdForAuthUser` (the REVERSE direction: auth_user→actor) still queries:
```sql
SELECT actor_id FROM lupo_actors WHERE auth_user_id = ? AND is_deleted = 0
```
This function is called from `validateNoCircularChain` → `createRequest` → `routeToHumanMvp`. It was NOT named in the original four NON_COMPLIANT findings. The original finding specifically identified `resolveAuthUserIdForActor` (actor→auth direction). HEPHAESTUS addressed exactly what was identified and did not claim to fix `resolveActorIdForAuthUser`. This residual dependency exists and is noted for follow-on scope — it does not affect routing selection accuracy or dispatch correctness under the canonical model.

### 5. End-to-End Safety

Verdict: PASS

Can the system safely:

| Safety Property | Result |
|-----------------|--------|
| Select a human | Yes — `fetchRoutingCandidates` via `lupo_actor_auth_users`, deterministic order |
| Create routing decision | Yes — atomic idempotency_key guard prevents duplicates |
| Dispatch request | Yes — `createRequest` linked to decision; decision pre-exists dispatch |
| Avoid duplication | Yes — DB UNIQUE constraint on idempotency_key is the guard |
| Handle failure cleanly | Yes — try/catch updates decision to `failed` with reason before re-throw |
| Secure actor binding | Yes — session-only, no client override |
| Use canonical model | Yes — routing selection and actor→auth resolution both via `lupo_actor_auth_users` |

Safe for install/reset and real human routing: YES.

## Compliance Conclusion

All four HEPHAESTUS-identified corrections are verified in actual code:

1. Atomic idempotency: DB UNIQUE constraint on `idempotency_key` — enforced.
2. Failure state consistency: try/catch around dispatch with `failed` terminal state — enforced.
3. Actor security: session-only `$actor_id`, no client override — enforced.
4. Source-of-truth for actor→auth resolution: `lupo_actor_auth_users` — enforced.

No blocking issues remain for the four identified corrections.

- system_status: COMPLIANT

## Open Follow-On Items (Non-Blocking)

These were not in the four NON_COMPLIANT findings and do not affect COMPLIANT verdict:

1. `resolveActorIdForAuthUser` (auth_user→actor direction) still uses `lupo_actors.auth_user_id`. Recommend migrating to a join with `lupo_actor_auth_users` in a future cleanup pass.
2. Double-DB-failure orphan risk in the `createRequest` failure catch path is an acknowledged extreme-case residual.
