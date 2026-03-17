---
lupopedia.headers:
  actor_id: 102
  actor_name: "cursor"
  delegation_chain: "cursor:root"
  channel_id: 42
  thread_title: "lupopedia 4.0.79 development"
  thread_tasks:
    - "reviewing implementation of lupopedia headers"
    - "review onboarding for existing and new ide agents"
  actors: [2, 102]
  lupopedia.version: "4.0.79"
  lupopedia.schema: "status_review"
  file_path_from_root: "lupo-docs/status/CURSOR_LILITH_CHANNEL_42_LUPOPEDIA_4_0_79_TASKS.md"
  last_modified_utc: "20260317"
  system_version: "4.0.79"
  artifact_type: "report"
  artifact_kind: "status"
  purpose: "Cursor + Lilith channel 42 task plan for release 4.0.79"
  tags: ["cursor", "lilith", "channel", "pipeline", "review"]
---

# Cursor + Lilith Channel 42 Task Plan (Lupopedia 4.0.79)

## Context

- Channel: `42` (lupopedia dev workspace)
- Thread: "lupopedia 4.0.79 development"
- Lead actors: `cursor` (actor_id=102), `lilith` (actor_id=2)

## Goals

1. Validate and secure or confirm message API membership+trust boundary.
2. Capture state of header routing, docs, onboarding for 7 IDE agents and list of tasks.
3. Produce progress artifacts in `lupo-docs/status/*.md` for Cursor to review and sign off.

## Task list

1. Confirm `channels-api.php` actor-channel membership enforcement.
2. Confirm session actor-id override in message insert path.
3. Confirm `channels-controller` still enforces role checks.
4. Confirm `lupo_agent_faucets` maps cursor/lilith to correct channel space.
5. Confirm `lupo-rules/root/lilith-noninterference-doctrine.md` creation.
6. Confirm seed updates for Lilith + cursor channel roles and actor_channels.
7. Implement `lupopedia headers` audit doc in `lupo-docs/architecture/HOW_ACTORS_ORCHESTRATE_ON_CHANNELS.md`.
8. Update guidance docs: `AGENTS.md`, `ONBOARDING.md`, `ACTOR_REGISTRATION_CHECKLIST.md`.

## Next actions (Owner: cursor)

- cursor: review this plan and map into code/doc PR tasks.
- lilith: produce review artifacts (status reports, suggested fixes, evidence of regression tests).

## Success criteria

- New status docs exist for all tasks.
- Security gap mitigations implemented and tested.
- Multi-agent onboarding docs are updated.
- channel/thread metadata attached to reports is consistent and traceable.
