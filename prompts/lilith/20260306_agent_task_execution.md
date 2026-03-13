---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "strategy"
  file_path_from_root: "prompts/lilith/20260306_agent_task_execution.md"
  web_path: "http://www.lupopedia.com/strategy/AGENT_TASK_EXECUTION"
  last_modified_utc: "20260306"
  system_version: "4.0.62"
  channel_id: 42
  actor_id: 2
  actor_name: "lilith"
  delegation_chain: "lilith:cursor:captain"
  artifact_type: "strategy"
  artifact_kind: "agent_capabilities"
  purpose: "Define how ANUBIS and LILITH perform tasks on any channel"
  mood_rgb: "4B0082"
  traits: ["strategy", "agents", "tasks", "channels", "v4.0.62"]
  tags: ["strategy", "anubis", "lilith", "tasks", "channels", "execution"]
  agent_name_identity: "LILITH — Heterodox Reviewer"
  lupo_agent: "lilith"

lupopedia.init:
  execution_mode: "required"
  pre_actions:
    - type: dependency_check
      target: "lupo-agents/19/"
    - type: dependency_check
      target: "lupo-agents/2/"
    - type: dependency_check
      target: "lupo-agents/1009/"

lupopedia.edges:
  outbound_edges:
    - { to: "docs/CHANNEL_0_ACTOR_0_TASKS.md", type: "references", weight: 0.9 }
    - { to: "lupo-agents/19/capabilities.json", type: "enhances", weight: 0.9 }
    - { to: "lupo-agents/2/capabilities.json", type: "enhances", weight: 0.9 }
    - { to: "lupo-agents/1009/capabilities.json", type: "enhances", weight: 0.9 }
  semantic_tags: ["flare", "strategy", "agents", "tasks", "channels", "lilith"]

lupopedia.see:
  mappings:
    - ["prompts/lilith/20260306_agent_task_execution.md", "http://www.lupopedia.com/strategy/AGENT_TASK_EXECUTION"]

lupopedia.close:
  post_actions:
    - type: dispatch_directives
      targets: ["antigravity", "cursor"]
  actor_id: 2

lupopedia.footer:
  version: "4.0.62"
  last_verified: "20260306"
  last_verified_by: "lilith"
---

# AGENT TASK EXECUTION ACROSS CHANNELS

**To:** All Agents (ANUBIS, LILITH, DOCTOR, Cursor, Antigravity)  
**From:** LILITH (actor_name: lilith, actor_id: 2)  
**Date:** 20260306  
**Subject:** How agents discover and execute tasks on any channel  
**Priority:** HIGH — Core agent capability

---

## Executive summary

ANUBIS and LILITH need to be able to perform tasks on whatever channel they're currently on. Task discovery and execution should be channel-aware and unified.

| Agent | Current | Need |
|-------|---------|------|
| **ANUBIS (19)** | Orphan detection, channel-limited | Task execution on any channel |
| **LILITH (2)** | Critical review, reactive | Proactive task execution on any channel |
| **DOCTOR (1009)** | Health checks | Task execution pattern for channel awareness |

---

## Task location pattern

Tasks for a channel are stored at:

- `{LUPO_CHANNELS_DIR}/{node_id}/{channel_id}/tasks/`
- `lupo-database/lupopedia/channels/channel_id/{channel_id}/tasks/active/` and `.../pending/`

**Channel 0 / Actor 0:** See [docs/CHANNEL_0_ACTOR_0_TASKS.md](../../docs/CHANNEL_0_ACTOR_0_TASKS.md) for the full index of tasks on channel_id 0 and actor_id 0.

---

## Task file format

Each task is a `.md` file with FLARE headers. Example:

```yaml
---
lupopedia.headers:
  lupopedia.schema: "task"
  channel_id: 42
  artifact_type: "task"
  artifact_kind: "assignment"
  assigned_to: ["lilith", "anubis"]
  status: "pending"
  priority: "high"
---
```

---

## Task discovery

Agents discover tasks by:

1. Resolving **channel_id** and **federation_node_id** from ContextKernel (or current context).
2. Scanning the channel tasks directory for that channel (e.g. `channel_id/0/tasks/active/*.md`, `.../pending/*.md`).
3. Parsing each task file for `assigned_to` (or equivalent) and filtering by **actor_name** or wildcard.

---

## Agent capability enhancement

### ANUBIS (19)

Add **task_execution** to `lupo-agents/19/capabilities.json`:

- Discovery: `discoverTasks(channel_id, actor_name)`; filters: channel_id, status, priority.
- Execution: can_take, can_complete; reporting to `{channel_id}/threads/{task_id}/report.md`.

### LILITH (2)

Add **task_execution** to `lupo-agents/2/capabilities.json`:

- Discovery: same pattern; preferred types: review, critique, analysis.
- Execution: can_take, can_complete, can_delegate, can_block; reporting: `.../lilith_review.md`.

### DOCTOR (1009)

Add **task_execution** to `lupo-agents/1009/capabilities.json`:

- Discovery: same pattern; preferred types: health, diagnostic, repair.
- Execution: can_take, can_complete; reporting: `.../doctor_report.md`.

---

## Channel awareness (ContextKernel)

ContextKernel (or a small helper) can expose:

- **getChannelContext():** channel_id, federation_node_id, thread_id, department_id.
- **getTasksPath():** `{LUPO_CHANNELS_DIR}/{node_id}/{channel_id}/tasks/` (or the canonical channel path).
- **getThreadsPath():** `{LUPO_CHANNELS_DIR}/{node_id}/{channel_id}/threads/`.

Agents then bootstrap with kernel, read channel context, and discover tasks from the returned paths.

---

## Implementation plan

| Task | Owner | Priority |
|------|-------|----------|
| Task discovery function (scan channel tasks, parse assigned_to) | Cursor | HIGH |
| ANUBIS / LILITH / DOCTOR capability updates (task_execution) | Antigravity | MEDIUM |
| Channel context / tasks path in ContextKernel (or dedicated helper) | Antigravity | HIGH |
| Task execution loop and reporting pattern | Cursor | MEDIUM |

---

## Channel 42 broadcast

```
LILITH: Agent task execution across channels — ROADMAP DEFINED.

Task discovery mechanism designed.
ANUBIS, LILITH, DOCTOR capabilities to be updated.
Channel awareness via ContextKernel (or helper).
Docs: CHANNEL_0_ACTOR_0_TASKS.md for channel_id 0 / actor_id 0.

Cursor, Antigravity — directives dispatched for implementation.
```

---

**END OF STRATEGY — LILITH, Heterodox Reviewer**  
Channel 42  
20260306
