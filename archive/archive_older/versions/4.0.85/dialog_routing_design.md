---
lupopedia.headers:
  header_format_version: null
  lupopedia.schema: documentation
  when_updated: null
  file_path_from_root: "docs/versions/4.0.85/dialog_routing_design.md"
  web_path: "http://www.lupopedia.com/docs/versions/4.0.85/dialog_routing_design"
  questions_toon: null
  federation_node_id: null
  channel_key: null
  trust_tier: null
  memory_key: null
  artifact_type: documentation
  artifact_kind: implementation_contract
  thread_id: 2012
  content_id: null
  pk_id: null
  pk_slug: ""
  title: ""
  status: ""
  parent_pk_id: ""
  summary: ""
  module: null
  dialog_transcript: null
---
# Dialog Routing And Human Escalation MVP (Final 4.0.85 State)

## 1. Overview

This document defines the canonical final 4.0.85 state of the dialog routing MVP.

Routing is no longer design-only. The MVP was implemented, audited, corrected, and validated as COMPLIANT in Thread 2012.

Inputs:
- actor escalation request
- thread context
- trigger type

Output:
- one deterministic selected auth user for single-recipient strategies
- deterministic ordered recipient set for broadcast

Constraints:
- no hidden randomness
- full traceability
- deterministic tie-break rules everywhere

Scope:
- MVP implementation only
- no future routing strategies described as active behavior
- no claims beyond current compliant runtime state

## 2. Implemented MVP Scope

Implemented in 4.0.85:

- deterministic candidate selection
- canonical actor-to-auth_user resolution through `lupo_actor_auth_users`
- routing decision persistence in `lupo_routing_decisions`
- human request creation and linkage
- idempotency protection
- loop prevention and terminal failure handling

Not implemented in 4.0.85:

- round robin
- least recently used
- dynamic load balancing
- availability weighting
- full broadcast workflow as a default routing path

Condensed canonical outcome (Thread 2012):

- deterministic routing design implemented as MVP
- idempotency and loop-prevention safeguards are binding runtime behavior
- final implementation audit verdict: COMPLIANT

## 3. Routing Strategies (Corrected)

### MVP Strategies (Only These Are Implemented)

- `primary_then_fallback`
- `priority_order`

### Not Implemented in MVP

- `round_robin` (requires persisted cursor state)
- `least_recently_used` (requires assignment history surface)

### Broadcast Status

- `broadcast` is not part of MVP default routing selection
- when explicitly used by policy, it must follow strict broadcast constraints in Section 7

### Strategy Determinism Rules

| Strategy | Candidate ordering | Tie-break | Fallback behavior |
|---|---|---|---|
| `primary_then_fallback` | primary first, then ascending `routing_priority` | ascending `auth_user_id` | move to next candidate by ordered index |
| `priority_order` | ascending `routing_priority` | ascending `auth_user_id` | move to next candidate by ordered index |
| `broadcast` (policy-only) | same ordered list as `priority_order` | ascending `auth_user_id` | no per-recipient fallback; response precedence rule applies |

## 4. MVP Routing Logic (Explicit Algorithm)

Input:
- `actor_id`
- `thread_id`
- optional `task_id`
- `trigger_type`
- strategy in `{primary_then_fallback, priority_order}`

Candidate query source:
- `lupo_actor_auth_users`

Candidate filter:
1. `actor_id` matches
2. `status = 'active'`
3. `is_deleted = 0`
4. `relationship_role` in controlled role dictionary

Deterministic candidate sort key:
1. `is_primary` descending (only for `primary_then_fallback`)
2. `routing_priority` ascending
3. `auth_user_id` ascending

Selection:
1. Build ordered candidate list.
2. If list empty: record `no_candidate` decision and stop.
3. Select candidate at `fallback_index = 0`.
4. Persist routing decision record before dispatch.
5. Create escalation request to selected user.

Fallback:
- on deterministic failure conditions, increment `fallback_index` by exactly 1 and retry until max attempts.

## 5. Routing Decision Storage (Critical)

Canonical table definition (design contract):

`lupo_routing_decisions`

Required fields:
- `routing_decision_id BIGINT PRIMARY KEY`
- `actor_id BIGINT`
- `thread_id BIGINT`
- `task_id BIGINT NULL`
- `routing_strategy VARCHAR(64)`
- `candidate_users_json TEXT`
- `selected_auth_user_id BIGINT`
- `fallback_index INT`
- `decision_reason TEXT`
- `decision_status VARCHAR(32)`
- `trigger_type VARCHAR(64)`
- `created_ymdhis BIGINT`
- `completed_ymdhis BIGINT`

Purpose:
- canonical, replayable, auditable routing decision ledger
- preserves full candidate set and deterministic selection outcome

Link to `lupo_human_requests`:
- each dispatch request references the routing decision id in metadata
- each fallback attempt creates a new routing decision row with incremented `fallback_index`

Link to decision lineage system:
- decision graph edge from trigger decision -> routing decision -> human request
- routing record stores lineage reference pointer in decision reason/metadata contract

## 6. Escalation Triggers, Idempotency, And Loop Prevention

Allowed trigger types:
- `actor_help_request`
- `low_confidence`
- `invariant_violation`
- `task_blocked`
- `user_requested_human`

Idempotency key:
- `(actor_id + thread_id + trigger_type + time_bucket)`

Time bucket:
- fixed deterministic window, example 5-minute UTC bucket

Rule:
- if same idempotency key already has in-flight routing decision, do not create parallel chain

Failure outcomes:
- timeout/no response
- recipient inactive before dispatch
- rejection

Loop prevention model:
- `max_attempts = 3`
- loop break key: `(actor_id + thread_id + trigger_type)`
- cooldown window: 10 minutes (UTC)

Deterministic stop conditions:
1. `fallback_index >= max_attempts`
2. candidate list exhausted
3. loop break key hit within cooldown window

Terminal state:
- write `decision_status = terminal_no_available_support_human`
- do not recursively re-enter routing until cooldown expires

## 7. Runtime Corrections Now Binding

The final compliant MVP includes the following corrected behaviors:

### Actor Binding

- actor identity for routing is derived from trusted session/auth context
- client-supplied actor overrides are not authoritative

### Source Of Truth

- forward actor-to-auth_user routing uses `lupo_actor_auth_users`
- legacy `lupo_actors.auth_user_id` is not authoritative for the primary routing path

### Failure Handling

- request creation failures force deterministic terminal failure status
- partial dispatch success may not masquerade as success if request persistence failed

### Idempotency

- routing uses database-backed uniqueness for idempotency protection
- race-loss behavior must resolve into deterministic no-duplicate dispatch behavior

### Loop Prevention

- fallback progression is ordered and bounded
- terminal conditions end the chain instead of recursively re-entering routing forever

## 8. Role Dictionary And Controlled Semantics

Controlled role set:
- `primary_owner`
- `supporting_human`
- `escalation_contact`

Role semantics:
- `primary_owner`: first-priority ownership route
- `supporting_human`: standard fallback pool
- `escalation_contact`: terminal escalation contact

Unknown role handling:
- ignored for MVP candidate set
- optional future behavior: lowest-priority inclusion only with explicit policy

## 9. Broadcast Constraints (Corrected)

Broadcast is policy-only and constrained.

Required constraints:
- `max_recipients` hard cap (default 3)
- deterministic ordering by `routing_priority`, then `auth_user_id`
- response precedence rule: first valid responder wins

Conflict control:
- once first valid response accepted, remaining responses are logged but do not override outcome
- no recursive broadcast retries

## 10. Integration Model (Corrected)

### `lupo_actor_auth_users`
- authoritative candidate pool
- provides role, primary flag, priority, status

### `lupo_human_requests`
- dispatch transport
- receives selected auth user target
- request metadata includes routing decision reference

### Human Request Linkage

The MVP requires routing outcomes to be linked to human-request creation rather than leaving routing as a disconnected advisory step.

That means:

- a selected recipient is not enough
- the routing decision must connect to request creation
- request state and routing state must remain traceable together

### Decision lineage
- routing decision is a first-class decision node
- fallback chain expressed as ordered decision links
- reproducibility ensured by stored candidate list and fallback index

## 11. Future Extensions (Deferred)

Deferred and not required for MVP:
- round-robin
- least-recently-used
- dynamic load balancing
- availability-weighted assignment

These remain disabled until required state surfaces exist.

## 12. Baseline Outcome

With this correction:
- routing decisions have canonical storage definition
- MVP strategy set is constrained to implementable deterministic options
- loop and idempotency safeguards are explicit
- role model is controlled
- broadcast behavior is bounded and deterministic

Current target state:
- IMPLEMENTED_AND_COMPLIANT

