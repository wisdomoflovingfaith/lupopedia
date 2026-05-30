---
lupopedia.headers:
  version_when_written: "4.0.85"
  file_path_from_root: "channels/42/threads/2016/20260322_182226_hephaestus_runtime_actor_loop_and_escalation_db_layer_implemented.md"
  questions_toon: null
  channel_id: 42
  thread_id: 2016
  actor_id: 102
  actor_name: "cursor"
  artifact_type: "implementation_report"
  artifact_kind: "runtime_actor_loop_and_escalation_db_layer"
  purpose: "Report the completed Phase 2 runtime actor loop and Phase 3 DB-only escalation layer for the Web Dialog MVP."
---

# Runtime Actor Loop And Escalation DB Layer Implemented

## Scope Completed

- Phase 2 runtime actor loop implemented with database-first dialog storage.
- Phase 3 escalation path implemented as DB-only task creation.
- No runtime filesystem writes were added.
- LLM abstraction added with deterministic mock fallback.

## Files Added

- `config/runtime_actors.yaml`
- `includes/classes/LlmRuntimeService.php`
- `includes/classes/RuntimeActorLoopService.php`
- `includes/classes/EscalationTaskService.php`
- `database/lupopedia/mysql/migrations/dev_20260322_runtime_actor_loop_and_escalation.sql`

## Files Updated

- `includes/classes/DialogMvpService.php`
- `api/dialog/post-message.php`
- `api/dialog/assign-actor.php`
- `api/dialog/trigger-routing.php`
- `views/dialog/messages.php`
- `database/lupopedia/mysql/install/install_new_lupopedia.sql`

## Runtime Flow

1. User posts message through `POST /message`.
2. Message is persisted to `lupo_dialog_messages`.
3. Runtime loop resolves target actor from message assignment, thread assignment, or keyword routing.
4. Runtime loop loads actor prompt/config from `config/runtime_actors.yaml`.
5. `LlmRuntimeService` returns a mock-safe runtime reply.
6. Runtime reply is written back into `lupo_dialog_messages` with actor attribution.
7. Routing decision is recorded in `lupo_routing_decisions`.
8. If the request implies code, schema, or doctrine work, a row is created in `lupo_escalation_tasks`.

## Escalation Table

`lupo_escalation_tasks` fields:

- `escalation_task_id`
- `actor_id`
- `thread_id`
- `message_id`
- `task_type`
- `status`
- `assigned_actor_id`
- `created_ymdhis`
- `updated_ymdhis`

## Validation

- Editor validation reported no errors in changed files.
- Runtime LLM abstraction smoke test returned a mock ATHENA reply.
- Live DB check confirmed `lupo_escalation_tasks` exists in the current environment.

## Constraint Compliance

- Database-first runtime preserved.
- Filesystem remains export-only for later phases.
- No actor state persistence added.
- Context limited to thread ID plus last N messages.
- Existing routing system reused through current dialog endpoints.
