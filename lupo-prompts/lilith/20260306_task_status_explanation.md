---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "help"
  file_path_from_root: "lupo-prompts/lilith/20260306_task_status_explanation.md"
  web_path: "http://www.lupopedia.com/help/TASK_STATUS_SYSTEM"
  last_modified_utc: "20260306"
  system_version: "4.0.62"
  channel_id: 42
  actor_id: 2
  actor_name: "lilith"
  delegation_chain: "lilith:cursor:captain"
  artifact_type: "help"
  artifact_kind: "explanation"
  purpose: "Explain task status system and how to list tasks by status"
  mood_rgb: "4169E1"
  traits: ["help", "explanation", "tasks", "status", "v4.0.62"]
  tags: ["help", "tasks", "status", "pending", "active", "query"]
  agent_name_identity: "LILITH — Heterodox Reviewer"
  lupo_agent: "lilith"

lupopedia.init:
  execution_mode: "advisory"
  pre_actions:
    - type: dependency_check
      target: "lupo-docs/TASK_STATUS_REFERENCE.md"

lupopedia.edges:
  outbound_edges:
    - { to: "lupo-docs/TASK_STATUS_REFERENCE.md", type: "references", weight: 1.0 }
    - { to: "lupo-docs/CHANNEL_0_ACTOR_0_TASKS.md", type: "references", weight: 0.8 }
  semantic_tags: ["flare", "help", "tasks", "status", "explanation", "lilith"]

lupopedia.see:
  mappings:
    - ["lupo-prompts/lilith/20260306_task_status_explanation.md", "http://www.lupopedia.com/help/TASK_STATUS_SYSTEM"]

lupopedia.close:
  post_actions:
    - type: log_explanation
      topic: "task_status"
  actor_id: 2

lupopedia.footer:
  version: "4.0.62"
  last_verified: "20260306"
  last_verified_by: "lilith"
---

# TASK STATUS SYSTEM — EXPLANATION

## Short answer

Tasks use **status** to indicate state. **Pending** = not yet started; **active** = in progress; **completed** / **blocked** / **failed** / **archived** = not pending.

**To list tasks that are NOT pending:** list everything in `active/`, `completed/`, `blocked/`, `failed/`, and `archived/` (or use the path that corresponds to your channel layout).

| Status     | Meaning           | Directory    |
|-----------|-------------------|--------------|
| `pending` | Not yet started   | `tasks/pending/` |
| `active`  | In progress       | `tasks/active/`  |
| `completed` | Finished        | `tasks/completed/` |
| `blocked` | Waiting on dependency | `tasks/blocked/` |
| `archived` | Historical       | `tasks/archived/` |
| `failed`  | Attempted, failed | `tasks/failed/`  |

---

## Where tasks live

Two patterns exist in the repo:

1. **Channel-id path:** `lupo-database/lupopedia/channels/channel_id/{channel_id}/tasks/{status}/`
2. **Config path:** `{LUPO_CHANNELS_DIR}/{node_id}/{channel_id}/tasks/{status}/` (e.g. `lupo-channels/0/42/tasks/`)

Example for channel 0:  
`lupo-database/lupopedia/channels/channel_id/0/tasks/active/`, `.../pending/`.

---

## How to list tasks by status

### File system (any shell)

```bash
# List all tasks (any status) for channel 0
find lupo-database/lupopedia/channels/channel_id/0/tasks/ -name "*.md" | sort

# List tasks NOT pending (active + completed + blocked + failed + archived)
find lupo-database/lupopedia/channels/channel_id/0/tasks/ -name "*.md" | grep -v "/pending/"

# List only active tasks
ls lupo-database/lupopedia/channels/channel_id/0/tasks/active/

# List only pending tasks
ls lupo-database/lupopedia/channels/channel_id/0/tasks/pending/
```

### Parse FLARE headers (grep)

```bash
# Tasks with status pending in content
grep -rl 'status:.*pending' lupo-database/lupopedia/channels/channel_id/0/tasks/

# High priority
grep -rl 'priority:.*high' lupo-database/lupopedia/channels/channel_id/0/tasks/
```

---

## Task status lifecycle

Pending → (agent takes) → Active → Completed (or Blocked / Failed). Completed can be moved to Archived.

---

## FLARE header status field

In task `.md` files, use e.g.:

```yaml
---
lupopedia.headers:
  status: "pending"
  priority: "high"
  assigned_to: ["lilith", "anubis"]
---
```

Valid status values: `pending`, `active`, `completed`, `blocked`, `failed`, `archived`.

---

## Full reference

See [lupo-docs/TASK_STATUS_REFERENCE.md](../../lupo-docs/TASK_STATUS_REFERENCE.md) for paths, all statuses, and query examples.

---

**END OF EXPLANATION — LILITH**  
Channel 42 · 20260306
