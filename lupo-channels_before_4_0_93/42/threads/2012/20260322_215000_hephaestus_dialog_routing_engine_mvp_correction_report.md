---
lupopedia.headers:
  version_when_written: "4.0.85"
  lupopedia.schema: "implementation_report"
  file_path_from_root: "lupo-channels/42/threads/2012/20260322_215000_hephaestus_dialog_routing_engine_mvp_correction_report.md"
  web_path: "http://www.lupopedia.com/channels/42/threads/2012/dialog_routing_engine_mvp_correction_report"
  last_modified_utc: "20260322_215000"
  channel_id: 42
  thread_id: 2012
  task_id: "task_ch42_th2012"
  actor_id: 14
  actor_name: "hephaestus"
  delegation_chain: "hephaestus:correction"
  artifact_type: "implementation_report"
  artifact_kind: "dialog_routing_engine_mvp_correction"
  purpose: "Hard correction pass addressing all four LILITH NON_COMPLIANT findings in Thread 2012 MVP"
  tags: ["implementation", "hephaestus", "routing_mvp", "correction", "thread_2012", "security", "idempotency"]

lupopedia.edges:
  outbound_edges:
    - { to: "lupo-includes/HumanRequestService.php", type: "modifies", weight: 1.0, reason: "Atomic idempotency, failure-state correction, source-of-truth alignment" }
    - { to: "lupo-routes/human_requests.php", type: "modifies", weight: 1.0, reason: "Removes client actor_id override" }
    - { to: "lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql", type: "modifies", weight: 1.0, reason: "Adds idempotency_key column and UNIQUE INDEX to lupo_routing_decisions" }
    - { to: "lupo-channels/42/threads/2012/20260322_210000_lilith_dialog_routing_engine_mvp_validation_audit.md", type: "resolves", weight: 1.0, reason: "Corrects all four NON_COMPLIANT findings" }

lupopedia.footer:
  last_updated: "20260322_215000"
  thread_status: "correction_applied"
---

# Dialog Routing Engine MVP — Hard Correction Report

## Scope

Targeted correction pass in response to LILITH implementation audit verdict `NON_COMPLIANT`
(artifact: `20260322_210000_lilith_dialog_routing_engine_mvp_validation_audit.md`).

Four identified blockers corrected. No new features added.

## Files Modified

- `lupo-includes/HumanRequestService.php`
- `lupo-routes/human_requests.php`
- `lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql`

---

## Fix 1: Atomic Idempotency

**Audit finding:** `idempotency_status: FAIL` — read-then-insert with no atomic guard; concurrent requests in same bucket can both dispatch.

**Approach chosen:** DB-level unique constraint on `idempotency_key`.

**Schema change** (`install_new_lupopedia.sql`):
- Added `idempotency_key VARCHAR(40) DEFAULT NULL` to `lupo_routing_decisions`.
- Added `CREATE UNIQUE INDEX lupo_routing_decisions_unq_idempotency ON lupo_routing_decisions (idempotency_key);`.
- NULL values are allowed for non-dispatch decisions (blocked_loop, terminal_*); MySQL/MariaDB/PostgreSQL all permit multiple NULL values in a UNIQUE index.

**PHP change** (`HumanRequestService.php`):
- Removed the soft `hasActiveIdempotentDecision` read-only pre-check from the main routing flow.
- Added private `computeIdempotencyKey($actor_id, $thread_id, $trigger_type, $bucket_start)` — returns `sha1(actor:thread:trigger_type:bucket_start)`, always 40 chars.
- Added private `idempotencyKeyExists($key)` — single SELECT used only in race-loss detection.
- For `selected` decisions: computes `$idempotency_key`, wraps `createRoutingDecision()` in try/catch.
  - If the unique constraint fires (race loser), `idempotencyKeyExists()` confirms the key is present and returns `blocked_idempotency`.
  - If a different exception occurs, it is re-thrown.
- The DB UNIQUE constraint is the actual safety guard. Two concurrent requests with the same key race at the DB level; exactly one succeeds.
- Updated `createRoutingDecision()` INSERT to include `idempotency_key` as the 14th parameter; non-selected decisions pass `null`.

**Concurrency guarantee:** cannot create parallel duplicate routing decisions in the same bucket — enforced by the DB unique constraint.

---

## Fix 2: Failure State Correction

**Audit finding:** `failure_handling_status: FAIL` — if `createRequest()` throws after decision is inserted as `selected`, no catch updates decision to terminal; leaves stale `selected` with `completed_ymdhis=0`.

**PHP change** (`HumanRequestService.php`):
- Wrapped `createRequest()` call in a dedicated try/catch block.
- On exception: calls `updateRoutingDecisionStatus($routing_decision_id, 'failed', $now, $e->getMessage())` before re-throwing.
- Updated `updateRoutingDecisionStatus()` signature to accept optional `$failure_reason = null` parameter.
  - When `$failure_reason !== null`, executes an UPDATE that also sets `decision_reason`.
  - When null, executes the original two-field UPDATE (backward-compatible for dispatched/blocked paths).

**Invariant:** every routing decision row eventually reaches a terminal status (`dispatched`, `failed`, `terminal_*`, `blocked_*`). No orphan `selected` decisions with `completed_ymdhis=0` are possible.

---

## Fix 3: Route Security — Remove Actor Override

**Audit finding:** `source_of_truth_status: FAIL (partial)` — API route accepts client-supplied `actor_id` in request body and passes it into routing method when present; allows actor impersonation.

**PHP change** (`lupo-routes/human_requests.php`):
- Replaced:
  ```php
  isset($input['actor_id']) ? (int) $input['actor_id'] : (int) $actor_id
  ```
  with:
  ```php
  (int) $actor_id
  ```
- `$actor_id` is always resolved from `human_requests_current_actor_id()` (session/auth context) before the route check. Client-supplied `actor_id` in the request body is now unconditionally ignored for routing execution.

**Invariant:** actor context in routing comes only from the authenticated session. No impersonation path exists.

---

## Fix 4: Source-of-Truth — Remove Legacy `lupo_actors.auth_user_id` Dependence

**Audit finding:** `source_of_truth_status: FAIL` — `resolveAuthUserIdForActor()` (called from `validateNoCircularChain()` in `createRequest()`) used `lupo_actors.auth_user_id` (legacy column), not the canonical many-to-many `lupo_actor_auth_users`.

**PHP change** (`HumanRequestService.php`):
- Rewrote `resolveAuthUserIdForActor()` to query `lupo_actor_auth_users` exclusively:
  ```sql
  SELECT auth_user_id FROM lupo_actor_auth_users
  WHERE actor_id = ? AND is_primary = 1 AND status = 'active' AND is_deleted = 0
  ORDER BY routing_priority ASC, auth_user_id ASC LIMIT 1
  ```
- Resolves to the primary, active auth user via the canonical relationship model.
- `lupo_actors.auth_user_id` is no longer referenced anywhere in the routing dispatch path.

**Invariant:** routing execution depends only on `lupo_actor_auth_users` for actor-to-auth-user resolution.

---

## Validation Results

| Check | Result |
|-------|--------|
| Idempotency cannot race | PASS — DB UNIQUE constraint on `idempotency_key` is the atomic guard |
| Routing decisions always reach terminal state | PASS — try/catch around `createRequest()` forces `failed` state with reason |
| Actor context is secure | PASS — session-only actor_id, no client override path |
| Routing uses canonical relationship model only | PASS — `resolveAuthUserIdForActor` now queries `lupo_actor_auth_users` |
| PHP syntax (lint) | PASS — both modified PHP files pass `php -l` with no errors |
| No new routing features added | PASS — correction-only pass, no MVP scope expansion |
| No FK/triggers/procedures/functions added | PASS — only a UNIQUE INDEX added to schema |

## Compliance Target

`NON_COMPLIANT → ready for re-audit by LILITH`
