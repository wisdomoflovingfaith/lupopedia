---
lupopedia.headers:
  lupopedia.version: "1.0"
  lupopedia.schema: "documentation"
  file_path_from_root: "docs/CHANNEL_0_ACTOR_0_TASKS.md"
  web_path: "http://www.lupopedia.com/docs/CHANNEL_0_ACTOR_0_TASKS"
  last_modified_utc: "20260306"
  system_version: "4.0.62"
  channel_id: 0
  actor_id: 0
  artifact_type: "documentation"
  artifact_kind: "task_index"
  purpose: "Index of all tasks on channel_id 0 and actor_id 0 (system)"
  traits: ["tasks", "channel_0", "actor_0", "v4.0.62"]
  tags: ["tasks", "channel", "actor", "system"]
---

# Tasks on channel_id 0 for actor_id 0 (system)

This document lists **all tasks stored for channel_id 0** and how they relate to **actor_id 0** (system).

## Where tasks live

| Location | Purpose |
|----------|---------|
| **Channel 0 tasks** | `lupo-database/lupopedia/channels/channel_id/0/tasks/active/` and `.../pending/` |
| **Actor 0 task state** | `lupo-database/lupopedia/actors/actor_id/0/tasks/current_focus.json` |
| **Config-based path** | `{LUPO_CHANNELS_DIR}/0/0/tasks/` when using node 0, channel 0 (e.g. `lupo-channels/0/0/tasks/` if that structure exists) |

Actor_id 0 (system) does not have per-channel task assignments in the current layout; channel-level tasks are under the channel directory. The file `actor_id/0/tasks/current_focus.json` holds the system actor’s current/next tasks and focus areas (currently empty arrays).

---

## All tasks on channel_id 0

### Active tasks

| Task file | Description (from path/name) |
|-----------|------------------------------|
| `tasks/active/20260225170000_task_0_10000_drop_tables_and_run_install.md` | Drop tables and run install (actor 10000) |
| `tasks/active/20260226000000_task_0_10000_primary_install_upgrade_4_0_46.md` | Primary install/upgrade 4.0.46 (actor 10000) |
| `tasks/active/broadcast_normalization.md` | Broadcast normalization |
| `tasks/active/db_reset_and_install.md` | DB reset and install |
| `tasks/active/installer_integration.md` | Installer integration |
| `tasks/active/registry_lock.md` | Registry lock |

**Full path base:** `lupo-database/lupopedia/channels/channel_id/0/`

### Completed tasks

| Task file | Description |
|-----------|-------------|
| `tasks/completed/20260225170100_task_0_19_validate_channel_666_quarantine.md` | Validate channel 666 quarantine (completed 2026-03-07) |

### Pending tasks

| Task file | Description (from path/name) |
|-----------|------------------------------|
| *(none)* | All pending tasks completed (see completed/) |

---

## Actor_id 0 (system) task state

- **Path:** `lupo-database/lupopedia/actors/actor_id/0/tasks/current_focus.json`
- **Contents:** `current_tasks`, `next_tasks`, and `focus_areas` are empty arrays; `actor_id: "0"`.
- **Meaning:** No tasks are currently assigned to the system actor in this file; system (actor 0) is the channel/context, not the assignee for these channel-0 tasks.

---

## Summary

| Scope | Count | Location |
|-------|--------|----------|
| **Channel 0 — active** | 6 | `channel_id/0/tasks/active/*.md` |
| **Channel 0 — pending** | 0 | `channel_id/0/tasks/pending/*.md` |
| **Actor 0 — current_focus** | 0 (empty) | `actors/actor_id/0/tasks/current_focus.json` |

**Total tasks on channel_id 0:** 6 (6 active + 1 completed; 0 pending).  
**Tasks explicitly for actor_id 0:** Defined by channel 0 context and system role; assignees in the task filenames are 10000 (captain) and 19 (ANUBIS).

---

## Related

- [HELP.md](HELP.md) — Doc hub
- [VERSION_4.0.61_STRATEGY.md](VERSION_4.0.61_STRATEGY.md) — Context Doctor, Context Kernel
- [prompts/lilith/20260306_agent_task_execution.md](../prompts/lilith/20260306_agent_task_execution.md) — Agent task execution across channels (discovery, execution, reporting)
