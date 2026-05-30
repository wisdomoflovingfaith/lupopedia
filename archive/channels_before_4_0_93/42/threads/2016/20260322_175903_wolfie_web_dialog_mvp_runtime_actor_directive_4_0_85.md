---
lupopedia.headers:
  version_when_written: "4.0.85"
  file_path_from_root: "channels/42/threads/2016/20260322_175903_wolfie_web_dialog_mvp_runtime_actor_directive_4_0_85.md"
  questions_toon: null
  channel_id: 42
  thread_id: 2016
  actor_id: 1
  actor_name: "wolfie"
  artifact_type: "directive"
  artifact_kind: "implementation_directive"
  purpose: "Bind implementation of Web Dialog MVP and runtime actor system for 4.0.85"
---

# WOLFIE Directive: Web Dialog MVP and Runtime Actor System (4.0.85)

- Task ID: task_web_dialog_mvp_001
- Status: ACTIVE
- Date: 2026-03-22

## Binding Architecture Shift

- Runtime flow moves to: Web UI -> DB -> routing -> runtime actor/human response.
- Filesystem moves to export-only role (audit/backup snapshots), not runtime state.
- Runtime writes must target canonical database tables.

## MVP Scope (Required)

- GET /channels
- GET /threads?channel_id=X
- GET /messages?thread_id=X
- POST /message
- PATCH /message/:id/actor
- POST /message/:id/route

## Runtime Actor Model

- Runtime actors are lightweight LLM roles with role prompt, last-N memory, and capabilities.
- Loop: post message -> route -> runtime actor LLM response or human request -> record routing.
- Context rule: use minimal context only (last N + task context), not full thread history.

## Escalation Doctrine

- Schema/doctrine/code/new-table changes must escalate as explicit IDE tasks.
- Runtime actor creates task records for IDE implementers when code-level changes are required.

## Database-First Channel Model

- Required tables: lupo_channels, lupo_dialog_threads, lupo_dialog_messages, lupo_actor_routing/lupo_routing_decisions, lupo_actor_auth_users.
- Existing routing components must be reused for MVP integration.

## Ordered Implementation Phases

1. Task 1: Web interface endpoints.
2. Task 4 subset: remove runtime filesystem writes and keep export-only behavior.
3. Task 2: runtime actor loop.
4. Task 3: escalation path.
5. Stabilization: export tooling and documentation updates.

## Immediate Execution

- Implementation begins with Task 1 (web interface endpoints) in this thread.
- Deliverables for Task 1 target:
  - views/dialog/channels.php
  - views/dialog/threads.php
  - views/dialog/messages.php
  - api/dialog/post-message.php
  - api/dialog/assign-actor.php
  - api/dialog/trigger-routing.php
