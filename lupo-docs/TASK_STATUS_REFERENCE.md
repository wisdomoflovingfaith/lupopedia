---
lupopedia.headers:
  lupopedia.schema: "documentation"
  file_path_from_root: "lupo-docs/TASK_STATUS_REFERENCE.md"
  version_when_written: "4.0.84"
  web_path: "http://www.lupopedia.com/docs/TASK_STATUS_REFERENCE"
  last_modified_utc: "20260306"
  channel_id: 42
  artifact_type: "documentation"
  artifact_kind: "reference"
  purpose: "Task status system: statuses, locations, and how to list or query tasks"
  traits: ["tasks", "status", "reference", "v4.0.73"]
  tags: ["tasks", "status", "pending", "active", "query"]
---

# Task status reference

How tasks are organized by status and how to list or query them.

---

## Status values and directories

| Status      | Meaning                    | Directory          |
|------------|----------------------------|--------------------|
| `pending`  | Not yet started, available  | `tasks/pending/`   |
| `active`   | In progress, taken by agent| `tasks/active/`    |
| `completed`| Finished successfully      | `tasks/completed/` |
| `blocked`  | Waiting on dependency      | `tasks/blocked/`   |
| `failed`   | Attempted but failed       | `tasks/failed/`    |
| `archived` | Historical, read-only      | `tasks/archived/`  |

**Tasks that are NOT pending** are those in: `active`, `completed`, `blocked`, `failed`, `archived`.

---

## Where tasks are stored

**Channel-id layout (in repo):**

```
lupo-database/lupopedia/channels/channel_id/{channel_id}/tasks/
├── pending/
├── active/
├── completed/
├── blocked/
├── failed/
└── archived/
```

**Config-based layout (LUPO_CHANNELS_DIR):**

```
{LUPO_CHANNELS_DIR}/{node_id}/{channel_id}/tasks/
├── pending/
├── active/
├── completed/
├── blocked/
├── failed/
└── archived/
```

Example for channel 0:  
`lupo-database/lupopedia/channels/channel_id/0/tasks/active/`, `.../pending/`.  
Other status directories may be added as needed.

---

## Listing tasks

### All tasks for a channel

```bash
find lupo-database/lupopedia/channels/channel_id/0/tasks/ -name "*.md" | sort
```

### Tasks that are NOT pending

```bash
find lupo-database/lupopedia/channels/channel_id/0/tasks/ -name "*.md" | grep -v "/pending/"
```

Or list each non-pending directory:

```bash
ls lupo-database/lupopedia/channels/channel_id/0/tasks/active/
ls lupo-database/lupopedia/channels/channel_id/0/tasks/completed/
ls lupo-database/lupopedia/channels/channel_id/0/tasks/blocked/
ls lupo-database/lupopedia/channels/channel_id/0/tasks/failed/
ls lupo-database/lupopedia/channels/channel_id/0/tasks/archived/
```

### Only active tasks

```bash
ls lupo-database/lupopedia/channels/channel_id/0/tasks/active/
```

### Only pending tasks

```bash
ls lupo-database/lupopedia/channels/channel_id/0/tasks/pending/
```

### Count by status (Bash)

```bash
CHAN=0
BASE="lupo-database/lupopedia/channels/channel_id/$CHAN/tasks"
for s in pending active completed blocked failed archived; do
  count=$(find "$BASE/$s" -name "*.md" 2>/dev/null | wc -l)
  echo "$s: $count"
done
```

### By FLARE header (grep)

```bash
# Files mentioning status pending
grep -rl 'status:.*"pending"' lupo-database/lupopedia/channels/channel_id/0/tasks/

# Files with high priority
grep -rl 'priority:.*"high"' lupo-database/lupopedia/channels/channel_id/0/tasks/

# Assigned to an agent (e.g. lilith)
grep -rl 'assigned_to:.*lilith' lupo-database/lupopedia/channels/channel_id/0/tasks/
```

---

## Task status lifecycle

```
PENDING → (agent takes) → ACTIVE → COMPLETED
                ↓              ↓
            BLOCKED         ARCHIVED
                ↓
            (unblock) → PENDING

ACTIVE → (error) → FAILED
```

---

## FLARE headers in task files

Task `.md` files can include:

```yaml
---
lupopedia.headers:
  lupopedia.schema: "task"
  channel_id: 42
  artifact_type: "task"
  artifact_kind: "assignment"
  status: "pending"
  priority: "high"
  assigned_to: ["lilith", "anubis"]
  depends_on: ["task-001"]
  created_ymdhis: "20260306120000"
  deadline_utc: "20260310000000"
---
```

**Valid status values:** `pending`, `active`, `completed`, `blocked`, `failed`, `archived`.

---

## Related docs

- [CHANNEL_0_ACTOR_0_TASKS.md](CHANNEL_0_ACTOR_0_TASKS.md) — Index of tasks on channel_id 0 and actor_id 0
- [HELP.md](HELP.md) — Documentation hub
- [lupo-prompts/lilith/20260306_task_status_explanation.md](../prompts/lilith/20260306_task_status_explanation.md) — LILITH’s explanation of the task status system
- [lupo-prompts/lilith/20260306_task_docs_verification.md](../prompts/lilith/20260306_task_docs_verification.md) — LILITH final verification of task documentation suite
- [lupo-prompts/lilith/20260306_agent_task_execution.md](../prompts/lilith/20260306_agent_task_execution.md) — Agent task execution across channels
