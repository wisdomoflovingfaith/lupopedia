---
lupopedia.headers:
  lupopedia.version: "4.0.81"
  file_path_from_root: "lupo-channels/66/threads/1025/20260318_175542_cursor_review_task_doc_continuity_update_001_channel-system-continuity-alignment.md"
  web_path: "http://www.lupopedia.com/lupo-channels/66/threads/1025/20260318_175542_cursor_review_task_doc_continuity_update_001_channel-system-continuity-alignment.md"
  last_modified_utc: "20260318"
  system_version: "4.0.81"
  channel_id: 66
  thread_id: 1025
  task_id: "task_doc_continuity_update_001"
  actor_id: 102
  actor_name: "cursor"
  delegation_chain: "cursor:root"
  artifact_type: "thread"
  artifact_kind: "review"
  message_type: "review"
  purpose: "Wolfie review: doc alignment to channel/thread/task continuity system"
  tags: ["wolfie", "review", "documentation", "continuity", "channel-system"]
  status: "draft"
lupopedia.edges:
  outbound_edges:
    - { to: "lupo-docs/doctrine/IDE_AGENT_CONTINUITY_PROTOCOL.md", type: "updates", weight: 1.0, reason: "Continuity checkpoint requirement moved from lupo-docs/status to channel checkpoint artifacts" }
    - { to: "ONBOARDING.md", type: "updates", weight: 1.0, reason: "Onboarding guidance now points handoff/continuity at lupo-channels threads/tasks" }
    - { to: "lupo-docs/doctrine/CHANNEL_BASED_COORDINATION_DOCTRINE.md", type: "references", weight: 0.8, reason: "Channel-based coordination is authoritative for continuity + routing" }
  semantic_tags: ["documentation_alignment", "IACP", "channel_checkpoints", "wolfie_review"]
lupopedia.footer:
  version: "4.0.81"
  last_verified: "20260318"
  last_verified_by: "cursor"
  orchestrator: "wolfie"
  next_action:
    - "Review and approve the channel-based continuity documentation alignment."
    - "If approved, we can treat remaining lupo-docs/status references as archival/legacy only (optional follow-up cleanup)."
---

# file: Cursor review — channel-based continuity doc alignment — thread 1025

## 1. Summary of what changed
I updated the documentation so the current system behavior is consistent with the **channel/thread/task** coordination model:

- Continuity checkpoint persistence now points to **channel checkpoint artifacts** inside `lupo-channels/42/threads/{thread_id}/` and (when applicable) `lupo-channels/42/tasks/` rather than relying on `lupo-docs/status/`.
- Onboarding instructions now match the same “channel artifacts are the durable handoff state” rule.

This brings IACP (IDE Agent Continuity Protocol) wording back in line with the channel-based doctrine and eliminates the “status checkpoints” contradiction.

## 2. Exact doc targets touched
1. `lupo-docs/doctrine/IDE_AGENT_CONTINUITY_PROTOCOL.md`
   - Replaced the “Status Checkpoints in `lupo-docs/status/`” requirement with “Channel Checkpoints (Thread/Task Artifacts)” under `lupo-channels/{channel_id}/threads` and channel tasks.
   - Updated the token-threshold actions (e.g. 85% and 90% steps) to require channel checkpoint publication / task handoff notes instead of status checkpoints.
   - Updated the resume procedure to say “read checkpoint artifacts (and task handoff notes)” rather than “read status artifacts”.
   - Updated the recommended directory layout and the “authoritative sources” list accordingly.
2. `ONBOARDING.md`
   - Updated the “Artifacts” bullet to say proof of coordination (and continuity) lives under `lupo-channels/{channel_id}/` and that `lupo-docs/status/` is archival/legacy.
   - Updated handoff/continuity guidance to reference owning **thread/task** artifacts under `lupo-channels/` rather than `lupo-docs/status/`.
   - Updated the help guidance to check `lupo-channels/42/threads/` and `lupo-channels/42/tasks/` for recent activity and handoff reports.

## 3. Rationale (why the old status checkpoint wording had to go)
The project’s coordination is now enforced by the channel system:

- Thread/task artifacts are where agents publish durable, LUPOPEDIA HEADERS-compliant state.
- `lupo-docs/status/` is no longer the primary coordination sink; it is treated as archival/legacy.

Leaving “status checkpoints” language in IACP/ONBOARDING creates a deterministic contradiction: agents would be directed toward an outdated persistence layer.

## 4. Requested Wolfie actions / acceptance criteria
- Confirm that the updated IACP and ONBOARDING wording matches the current doctrine reality: continuity handoff state is persisted via **channel checkpoint artifacts** (threads/tasks), not via `lupo-docs/status/`.
- If approved, we can optionally do a bounded repo-wide follow-up to reduce/label remaining `lupo-docs/status/` mentions as archival/legacy only where they still appear in operational instructions.

