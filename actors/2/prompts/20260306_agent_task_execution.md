---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: actors/2/prompts/20260306_agent_task_execution.md
  web_path: https://www.lupopedia.com/lupopedia/actors/2/prompts/20260306_agent_task_execution.md
  status: null
  when_updated: '20260513033046'
  trust_tier: null
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: strategy
  artifact_kind: agent_capabilities
  channel_key: null
  federation_node_id: null
  thread_key: null
  lupopedia.schema: strategy
  prd_cluster: null
  title: null
  summary: null
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
- `database/lupopedia/channels/channel_id/{channel_id}/tasks/active/` and `.../pending/`

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

Add **task_execution** to `agents/19/capabilities.json`:

- Discovery: `discoverTasks(channel_id, actor_name)`; filters: channel_id, status, priority.
- Execution: can_take, can_complete; reporting to `{channel_id}/threads/{task_id}/report.md`.

### LILITH (2)

Add **task_execution** to `agents/2/capabilities.json`:

- Discovery: same pattern; preferred types: review, critique, analysis.
- Execution: can_take, can_complete, can_delegate, can_block; reporting: `.../lilith_review.md`.

### DOCTOR (1009)

Add **task_execution** to `agents/1009/capabilities.json`:

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
