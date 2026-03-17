---
lupopedia.headers:
  actor_id: 102
  actor_name: "cursor"
  delegation_chain: "cursor:root"
  channel_id: 42
  lupopedia.version: "4.0.79"
  file_path_from_root: "lupo-docs/status/CURSOR_HEADER_AND_ONBOARDING_DOC_UPDATES_4_0_79.md"
  artifact_type: "status"
  artifact_kind: "verification_report"
  purpose: "Header and onboarding documentation updates for Channel 42 task plan (4.0.79)"
  tags: ["header", "onboarding", "cursor", "4.0.79", "documentation"]
---

# Header and Onboarding Doc Updates (4.0.79)

**Workstream 4 deliverable.** Source: [CURSOR_LILITH_CHANNEL_42_LUPOPEDIA_4_0_79_TASKS.md](CURSOR_LILITH_CHANNEL_42_LUPOPEDIA_4_0_79_TASKS.md).

## Docs inspected and updated

- **lupo-docs/architecture/HOW_ACTORS_ORCHESTRATE_ON_CHANNELS.md** — Canonical path (doctrine references this under architecture). Updated in this pass and in prior 4.0.79 work.
- **AGENTS.md** — Root agent guide.
- **ONBOARDING.md** — New-agent onboarding.
- **lupo-docs/ACTOR_REGISTRATION_CHECKLIST.md** — Actor registration steps.

## Changes made in this pass

### HOW_ACTORS_ORCHESTRATE_ON_CHANNELS.md

- **§17 Header and artifact traceability (channel/thread context)** added. Documents how artifacts should trace:
  - `channel_id`, `thread_title`, `thread_tasks`, `actors`, `delegation_chain`, `system_version`, `file_path_from_root`
  - Table of fields and purpose; note that these are recommended conventions for status/planning artifacts; reference to LUPOPEDIA HEADERS and the Channel 42 task plan artifact.

No file-path corrections were required; the canonical doc is under `lupo-docs/architecture/` (referenced from doctrine and AGENTS.md).

## Content already present (prior 4.0.79 pass)

- **Channel-safe orchestration:** HOW_ACTORS documents channels define workspace, membership defines presence, roles define permissions, threads define task context, messages define conversation (throughout §§1–16 and summary table §14).
- **§9.1 Channel message API security (4.0.79):** Actor identity from session, membership enforcement, admin bypass, 401/403 behavior.
- **§16 Lilith as non-interfering reviewer:** Non-interference doctrine reference, safe coexistence, recommended role keys (captain, orchestrator, developer, schema_coordinator, extension_specialist, documentation, critic, monitor).
- **AGENTS.md:** Channel security (4.0.79+), Lilith as non-interfering reviewer, propagation target `lilith`.
- **ONBOARDING.md:** Channel posting security and Lilith non-interference in non-negotiable rules; `lilith` in propagate_agent_rules target list.
- **ACTOR_REGISTRATION_CHECKLIST.md:** Step 5 — Channel membership and roles; recommended role keys including critic/monitor for Lilith; API enforces membership and server-side actor.

## Header/channel traceability

- **Now documented** in HOW_ACTORS §17: artifacts (status reports, task plans) should include channel_id, thread_title, thread_tasks, actors, delegation_chain, system_version, file_path_from_root where applicable. The task plan artifact (CURSOR_LILITH_CHANNEL_42_LUPOPEDIA_4_0_79_TASKS.md) is the reference example.

## Summary

- **Which docs were changed this pass:** HOW_ACTORS_ORCHESTRATE_ON_CHANNELS.md (added §17).
- **What new guidance was added:** Explicit header/artifact traceability table and reference to task plan example.
- **File-path corrections:** None; architecture path confirmed as canonical.
