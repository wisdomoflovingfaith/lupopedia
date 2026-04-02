---
lupopedia.headers:
  version_when_written: "4.0.85"
  lupopedia.schema: "implementation_report"
  file_path_from_root: "lupo-channels/42/threads/2012/20260322_203000_hephaestus_dialog_routing_engine_mvp_implementation_report.md"
  web_path: "http://www.lupopedia.com/channels/42/threads/2012/dialog_routing_engine_mvp_implementation_report"
  last_modified_utc: "20260322_203000"
  channel_id: 42
  thread_id: 2012
  task_id: "task_ch42_th2012"
  actor_id: 14
  actor_name: "hephaestus"
  delegation_chain: "hephaestus:implementation"
  artifact_type: "implementation_report"
  artifact_kind: "dialog_routing_engine_mvp"
  purpose: "MVP implementation for deterministic routing decisions and human request integration"
  tags: ["implementation_report", "dialog_routing", "mvp", "hephaestus", "thread_2012"]

lupopedia.edges:
  outbound_edges:
    - { to: "lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql", type: "updates", weight: 1.0, reason: "Adds routing decision storage table" }
    - { to: "lupo-includes/HumanRequestService.php", type: "updates", weight: 1.0, reason: "Implements deterministic routing selection and decision persistence" }
    - { to: "lupo-routes/human_requests.php", type: "updates", weight: 0.95, reason: "Adds route API action for MVP routing execution" }
    - { to: "lupo-docs/versions/4.0.85/dialog_routing_design.md", type: "implements", weight: 0.95, reason: "Implements corrected READY_FOR_IMPLEMENTATION routing design" }

lupopedia.footer:
  last_updated: "20260322_203000"
  thread_status: "completed"
---

# Dialog Routing Engine MVP Implementation Report

## Scope

Implements Thread 2012 MVP only:
- routing decision storage
- deterministic candidate selection (`primary_then_fallback`)
- human request integration with routing linkage
- loop prevention and idempotency controls

Deferred strategies remain unimplemented:
- `round_robin`
- `least_recently_used`
- advanced load balancing

## Table Created

Created in install authority:
- `lupo_routing_decisions`

Added exact required fields:
- `routing_decision_id BIGINT PRIMARY KEY`
- `actor_id BIGINT NOT NULL`
- `thread_id BIGINT NOT NULL`
- `task_id BIGINT NULL`
- `routing_strategy VARCHAR(64) NOT NULL`
- `candidate_users_json TEXT NOT NULL`
- `selected_auth_user_id BIGINT NOT NULL`
- `fallback_index INT NOT NULL DEFAULT 0`
- `decision_reason TEXT`
- `decision_status VARCHAR(32) NOT NULL`
- `trigger_type VARCHAR(64) NOT NULL`
- `created_ymdhis BIGINT NOT NULL`
- `completed_ymdhis BIGINT DEFAULT 0`

Routing-support indexes added:
- loop-break scan index by actor/thread/trigger/time
- thread timeline index
- selected-user status timeline index

## Routing Logic Implemented

Implemented in `lupo-includes/HumanRequestService.php` via:
- `routeToHumanMvp($actor_id, $thread_id, $trigger_type, $options)`

Deterministic candidate fetch implemented exactly:
- source table: `lupo_actor_auth_users`
- filters: `actor_id`, `status='active'`, `is_deleted=0`
- ordering: `is_primary DESC`, `routing_priority ASC`, `auth_user_id ASC`

MVP selection strategy implemented:
- `primary_then_fallback`
- first candidate at `fallback_index=0`
- fallback selects next ordered candidate by incremented `fallback_index`

## Human Request Integration Confirmed

Integration path:
1. Create routing decision row in `lupo_routing_decisions`
2. Create human request through existing `createRequest()` flow
3. Linkage carried by:
- `subject_type = 'routing_decision'`
- `subject_reference = routing_decision_id`
- context item containing both `routing_decision_id` and `selected_auth_user_id`

Result:
- Actor -> deterministic selection -> human request -> traceable routing decision

## Loop Prevention Enforced

Implemented controls:
- `max_attempts = 3`
- loop-break key: `(actor_id + thread_id + trigger_type)`
- cooldown window: 10 minutes

Behavior:
- if cooldown window already contains >=3 routing attempts for key, engine writes `blocked_loop` decision and stops.

## Idempotency Enforced

Implemented controls:
- idempotency key dimensions: `(actor_id + thread_id + trigger_type + time_bucket)`
- deterministic 5-minute UTC time bucket

Behavior:
- if a `selected/dispatched` decision already exists in current bucket for key, engine writes `blocked_idempotency` decision and does not create parallel request chain.

## Files Changed

- `lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql`
- `lupo-includes/HumanRequestService.php`
- `lupo-routes/human_requests.php`

## Doctrine Checks

- no foreign keys added
- no triggers added
- no procedures/functions added
- deterministic selection only (no randomness)
