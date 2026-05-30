---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/prd/89_A-i_TASK_SYSTEM_INTEGRATION.md
  web_path: https://www.lupopedia.com/lupopedia/docs/prd/89_A-i_TASK_SYSTEM_INTEGRATION.md
  status: active
  when_updated: '20260513033046'
  trust_tier: canonical
  questions_toon: null
  memory_toon: memory/development/canonical/1026/04/89_task_system_integration.toon
  atoms_toon: null
  transcript_jsonl: 0/development/task-system-integration
  artifact_type: prd
  artifact_kind: specification
  channel_key: development
  federation_node_id: 0
  thread_key: task-system-integration
  lupopedia.schema: prd
  prd_cluster: 00_A-i_89_A-i
  title: 'PRD 89: Task System Integration'
  summary: Defines the task system for coordinating work across multiple parallel agents, including database schema, API endpoints, agent polling, and UI integration for task management.
---
# PRD 89: Task System Integration

## Canonical clarification: what a channel is (and is not)

In Lupopedia, a channel is a semantic container inside a domain (node).
It is NOT a Discord room, Slack channel, chat feed, or conversation thread.

**Hierarchy**

```text
Domain (node)
  -> Channel (semantic container)
      -> Thread (artifact)
          -> Messages, memory, atoms, PRDs, and other scoped artifacts
```

**Definition**

- **Channel** = a governed semantic container that defines scope, meaning, and memory boundaries.
- **Thread** = a single conversational artifact inside a channel.
- **Threads do not contain channels. Channels contain threads.**

**Required warning (normative where channels are defined):** Channels are semantic containers, not conversational rooms. They define scope, governance, and meaning for all threads within them.

**Schema keys:** `channel_key`, `channel_id`, and related channel metadata keep their existing names; this section clarifies semantics only (no field renames).

## Overview

This PRD defines the task system that coordinates work across multiple parallel agents. Task assignments travel through the task queue — never through the chat stream. The chat is for human oversight; the task queue is for agent coordination.

## Task System

### Purpose

Coordinates work across multiple parallel agents. Task assignments travel through the task queue — never through the chat stream. The chat is for human oversight; the task queue is for agent coordination.

### Chat Command Syntax

| Command | Format / Example | Action |
|---------|---------|--------|
| `[task] who: X what: Y` | `[task] who: CURSOR what: fix header` | Assign task to IDE agent |
| `[task]` | `[task] title: Fix schema, assigned_to: Gemini, priority: HIGH` | Create tracked task |
| `[task update]` | `[task update] TASK-001 status: DONE` | Update task status |
| `[task list]` | `[task list]` | Show all tasks |
| `[task next]` | `[task next] assigned_to: ClaudeCode` | Get next task for agent |
| `@AGENT message` | `@CURSOR fix the header in validate_actor_id.php` | Direct message |
| `message` (no prefix) | `Everyone please check your headers` | Broadcast |

### Database Schema

**Table roles:** **`lupo_dialog_pending_tasks`** is the **runtime queue** (agent polling, SEND TASK handoffs, short-lived operational state). **`{{prefix}}tasks`** is **long-term / workflow tracking** for broader task metadata across agents and humans — not interchangeable with the pending queue; do not assume one replaces the other.

**Primary keys:** DDL below uses `BIGINT NOT NULL PRIMARY KEY` on id columns. **`task_id` / `recent_file_id` MUST** be assigned via a **deterministic application-layer allocator** (for example **`IdGenerator`** per root doctrine); **do not** rely on database **`AUTO_INCREMENT`**.

**IDE agent task queue** (`lupo_dialog_pending_tasks`):
```sql
CREATE TABLE lupo_dialog_pending_tasks (
    task_id BIGINT NOT NULL PRIMARY KEY,
    assigned_to_actor_id BIGINT NOT NULL,        -- which agent should do this
    assigned_by_actor_id BIGINT NOT NULL,        -- CAPTAIN_WOLFIE (actor_id 1)
    task_description TEXT NOT NULL,
    status ENUM('pending', 'in_progress', 'completed', 'failed', 'cancelled') DEFAULT 'pending',
    result_summary TEXT NULL,
    created_ymdhis BIGINT NOT NULL,
    started_ymdhis BIGINT NULL,
    completed_ymdhis BIGINT NULL,
    INDEX idx_assigned_to (assigned_to_actor_id, status),
    INDEX idx_created (created_ymdhis)
);
```

**General task tracker** (full workflow, all agents):
```sql
CREATE TABLE {{prefix}}tasks (
    task_id BIGINT NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT NULL,
    assigned_to VARCHAR(64) NULL,
    priority ENUM('HIGH', 'MED', 'LOW') NOT NULL DEFAULT 'MED',
    status ENUM('TODO', 'IN_PROGRESS', 'DONE', 'BLOCKED', 'CANCELLED') NOT NULL DEFAULT 'TODO',
    dependencies TEXT NULL,
    created_by VARCHAR(64) NOT NULL,
    created_ymdhis BIGINT NOT NULL,
    started_ymdhis BIGINT NULL,
    completed_ymdhis BIGINT NULL,
    notes TEXT NULL,
    is_deleted TINYINT NOT NULL DEFAULT 0,
    deleted_ymdhis BIGINT NULL,
    PRIMARY KEY (task_id)
);
```

### Agent Task Polling

Each agent periodically checks for assigned tasks (via cron or IDE plugin):

```php
// scripts/agent_poll_tasks.php
// Run by agent IDE every 30 seconds

require_once dirname(__DIR__) . '/includes/bootstrap.php';
require_once dirname(__DIR__) . '/includes/chat/message_functions.php';

if ($argc < 2) {
    fwrite(STDERR, "Usage: agent_poll_tasks.php <actor_id> [channel_key] [thread_key]\n");
    exit(1);
}

$actor_id = (int)$argv[1];
$channel_key = $argv[2] ?? 'development';
$thread_key = $argv[3] ?? date('Y-m-d');

$db = DatabaseFactory::getConnection();

$tasks = $db->fetchAll(
    "SELECT task_id, task_description FROM lupo_dialog_pending_tasks
     WHERE assigned_to_actor_id = ? AND status = 'pending'
     ORDER BY created_ymdhis ASC",
    [$actor_id]
);

foreach ($tasks as $task) {
    $db->query(
        "UPDATE lupo_dialog_pending_tasks SET status = 'in_progress' WHERE task_id = ?",
        [$task['task_id']]
    );
    insert_message($actor_id, 0, "Received task: {$task['task_description']}", $channel_key, $thread_key, 'system');
    $result = execute_agent_task($task['task_description'], $actor_id);
    $db->query(
        "UPDATE lupo_dialog_pending_tasks
         SET status = 'completed', result_summary = ?, completed_ymdhis = ?
         WHERE task_id = ?",
        [$result, timestamp_ymdhis::now(), $task['task_id']]
    );
    insert_message($actor_id, 0, "Task completed: {$result}", $channel_key, $thread_key, 'stdout');
}
```

### API Endpoints

| Endpoint | Method | Purpose |
|----------|--------|---------|
| `/api/tasks/create` | POST | Create task |
| `/api/tasks/update` | POST | Update task |
| `/api/tasks/list` | GET | List tasks (filter by status, assigned_to) |
| `/api/tasks/next` | GET | Get next available task for an agent |
| `/api/task/create` | POST | Create task (chat-originated, CAPTAIN_WOLFIE) |

### UI Integration

The chat sidebar gains a **"Tasks" tab** showing current tasks (TODO, IN_PROGRESS), tasks assigned to you, blocked tasks, and recently completed. Clicking a task opens details, allows status change, and links to the relevant conversation.

---

## Task Rendering in Chat

Task-related messages in the chat feed are visually distinct from normal stdout messages.

### Message Types and Visual Treatment

| `message_type` | Visual Class | Background | Display Style | Example |
|---|---|---|---|---|
| `stdout` | `chat-stdout` | Thread color (sender) | Normal monospace row | `[14:32:01] [CURSOR] working on validate_actor_id.php` |
| `stderr` | `chat-stderr` | Thread color (sender) | Italic, warning text color `#dc3545` | `[14:32:05] [CURSOR] WARNING: header field missing` |
| `task` | `chat-task` | **White (`#ffffff`)** — originates from CAPTAIN | Bold, left yellow border `#ffc107` | `[14:32:15] [CAPTAIN] [task] → CURSOR: fix header` |
| `system` | `chat-system` | Dark neutral `#0d0d0f` | Italic, muted text | `[14:32:00] [SYSTEM] Thread created for 2026-04-16` |
| `task_status` | `chat-task-status` | **Sender's actor thread `background_color`** | Bold, status badge colored by outcome | `[14:33:01] [CURSOR] [status: completed] Task #42 done` |
| `routing` | `chat-routing` | Transparent / feed neutral | Indented, cyan arrow + backlink | `→ [14:34:00] sent to blog-writing by CAPTAIN` |

> **Background color rules:**
> - `task` messages use **white** (`#ffffff`) because they originate from the CAPTAIN (the human assigning work). White signals a human directive, not agent output.
> - `task_status` messages use the **sending agent's thread `background_color`**. Each agent has a registered thread color; their status updates render in that color so the source is immediately identifiable.
> - All other types use the thread or actor color per the existing color assignment logic.

### Task Message Anatomy

A task assignment message (`message_type = 'task'`) renders with:
1. Timestamp
2. Sender tag (always the assigning actor — CAPTAIN or human persona)
3. `[task]` label in bold yellow
4. Assignee name (the `to_actor_id`'s display name)
5. Task description (truncated to 120 chars with "...")
6. Link: `[view task]` — opens task detail

**Background color:** White (`#ffffff`). This is a human directive — white signals CAPTAIN origin, not agent output. The white background makes task assignment rows visually dominant and unambiguous regardless of surrounding thread color.

**Example rendered line (on white background):**
```
[14:32:15] [CAPTAIN] [task] → CURSOR: fix header in validate_actor_id.php [view task]
```

### Task Status Message Anatomy

A task status update (`message_type = 'task_status'`) renders with:
1. Timestamp
2. Agent name (the `from_actor_id`'s display name)
3. Status badge: `[completed]` / `[failed]` / `[in_progress]` (color-coded)
4. Task reference
5. Optional result summary (first 80 chars)

**Background color:** The sending agent's registered thread `background_color` (from `lupo_dialog_threads.color` for that actor). This visually ties the status update to the agent that produced it. Implementation: PHP renders `style="background-color: #{$actor_thread_color};"` on the message row.

**Example rendered line (on CURSOR's thread color #e3f2fd):**
```
[14:55:01] [CURSOR] [status: completed] Task #42 → validate_actor_id.php headers fixed [view task]
```

### CSS Classes Required

```css
/* Task assignment — always white background */
.chat-task        { background-color: #ffffff; color: #1a1a1a; font-weight: bold;
                    border-left: 3px solid #ffc107; padding-left: 6px; }

/* Task status — background set inline from actor thread color (PHP) */
.chat-task-status { font-weight: bold; /* background-color: set inline per actor */ }

/* Standard types */
.chat-stderr      { font-style: italic; color: #dc3545; }
.chat-system      { font-style: italic; color: #6c757d; background-color: #0d0d0f; }
.chat-routing     { margin-left: 20px; color: #17a2b8; }

/* Status badges (inline spans) */
.badge-completed  { color: #28a745; font-weight: bold; }
.badge-failed     { color: #dc3545; font-weight: bold; }
.badge-progress   { color: #ffc107; font-weight: bold; }
```

**PHP rendering note:** For `task_status`, the row background is set inline:
```php
if ($msg['message_type'] === 'task_status') {
    $actor_color = get_actor_thread_color($msg['from_actor_id']); // returns hex without #
    echo '<div class="chat-line chat-task-status" style="background-color:#' . htmlspecialchars($actor_color) . ';">';
}
```

### Routing Message Anatomy

A cross-channel routing indicator (`message_type = 'routing'`) renders below the source message:
```
  → sent to blog-writing by CAPTAIN at 14:34 [view in blog-writing]
```

And in the destination channel, a received routing indicator renders above the message:
```
  → from documentation at 14:32 via CAPTAIN [view source]
```

**Implementation note:** `channels/index.php` already has `chat-line-task` CSS class (line 236) and `message_type === 'task'` conditional rendering (line 259). The additions above formalize the full vocabulary and add `task_status` and `routing` types.

---

## Cross-References

- PRD 02 — Channels, Threads, and Discussions (parent document)
- PRD 10 — Tasks, Escalations, Human Requests, and Workflow Management
- PRD 50 — Agent Coordination Protocol & Transcript Feed

---

**STATUS:** ACTIVE  
**EFFECTIVE:** Immediate for 4.0.x  

This output complies with Lupopedia Constitutional Root Rules.
