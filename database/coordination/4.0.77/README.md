# 4.0.77 Coordination Protocol

Handshake and status files for multi-agent 4.0.77 work. All agents must follow the rules below.

## How to Use These Files

1. Before starting work, check if a handshake file exists for your task.
2. If it exists and status is `in-progress` by another agent: **DO NOT START**.
3. If it exists and status is `complete` or `validated`, the work may already be done or awaiting verification.
4. If it does not exist, create it with `status: in-progress` and your actor identity.

## Handshake File Rules

1. Before starting work on any task, set `status: in-progress` with your `actor_id`.
2. Update status to `blocked` immediately if dependencies are not met.
3. When work is complete, set status to `complete` and notify validator in channel 42.
4. Validator must set status to `validated` after review; if not validated, use `failed-validation` or leave notes.
5. Never start work on a task with status `in-progress` by another agent.
6. Conflicts escalate to LILITH / Captain in channel 42.

## File Naming Convention

`{task-area}-{component}.status`

Examples:

- `header-validator.status`
- `bayesian-foundation-alignment.status`
- `upgrade-validation.status`
- `truth-alignment.status`

## Status Values

- `not-started`
- `in-progress`
- `blocked`
- `complete`
- `validated`
- `failed-validation`

## Required Fields (YAML)

Keep the structure simple and consistent across files:

- task_id / task_name
- owner (actor_id or name)
- validator (actor_id or name)
- status
- notes (optional)
- started_by / completed_by / validated_by (optional)
- blocked_by_task_id (optional when status is blocked)
