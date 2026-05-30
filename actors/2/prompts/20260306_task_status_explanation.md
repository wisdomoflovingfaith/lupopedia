---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: actors/2/prompts/20260306_task_status_explanation.md
  web_path: https://www.lupopedia.com/lupopedia/actors/2/prompts/20260306_task_status_explanation.md
  status: null
  when_updated: '20260513033046'
  trust_tier: null
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: help
  artifact_kind: explanation
  channel_key: null
  federation_node_id: null
  thread_key: null
  lupopedia.schema: help
  prd_cluster: null
  title: null
  summary: null
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

1. **Channel-id path:** `database/lupopedia/channels/channel_id/{channel_id}/tasks/{status}/`
2. **Config path:** `{LUPO_CHANNELS_DIR}/{node_id}/{channel_id}/tasks/{status}/` (e.g. `channels/0/42/tasks/`)

Example for channel 0:  
`database/lupopedia/channels/channel_id/0/tasks/active/`, `.../pending/`.

---

## How to list tasks by status

### File system (any shell)

```bash
# List all tasks (any status) for channel 0
find database/lupopedia/channels/channel_id/0/tasks/ -name "*.md" | sort

# List tasks NOT pending (active + completed + blocked + failed + archived)
find database/lupopedia/channels/channel_id/0/tasks/ -name "*.md" | grep -v "/pending/"

# List only active tasks
ls database/lupopedia/channels/channel_id/0/tasks/active/

# List only pending tasks
ls database/lupopedia/channels/channel_id/0/tasks/pending/
```

### Parse FLARE headers (grep)

```bash
# Tasks with status pending in content
grep -rl 'status:.*pending' database/lupopedia/channels/channel_id/0/tasks/

# High priority
grep -rl 'priority:.*high' database/lupopedia/channels/channel_id/0/tasks/
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

See [docs/TASK_STATUS_REFERENCE.md](../../docs/TASK_STATUS_REFERENCE.md) for paths, all statuses, and query examples.

---

**END OF EXPLANATION — LILITH**  
Channel 42 · 20260306
