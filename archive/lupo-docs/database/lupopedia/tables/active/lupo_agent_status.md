---
lupopedia.headers:
  header_format_version: "4.1.2"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_agent_status.md"
  web_path: "https://www.lupopedia.com/lupopedia/lupo-docs/database/lupopedia/tables/active/lupo_agent_status.md"
  status: "active"
  when_updated: "20260415214000"
  trust_tier: "canonical"
  questions_toon: null
  memory_toon: "lupo-memory/development/canonical/1026/04/lupo_agent_status.toon"
  atoms_toon: null
  transcript_jsonl: "0/development/lupo_agent_status_doc"
  artifact_type: documentation
  artifact_kind: table
  channel_key: "development"
  federation_node_id: 0
  thread_id: ""
  content_id: null
  pk_id: null
  pk_slug: "lupo_agent_status"
  title: "Table: lupo_agent_status"
  summary: "Tracks the dynamic lifecycle state of builder agents (ACTIVE, IDLE, SLEEPING, etc.)."
  module: "Orchestration"
  lupopedia.schema: documentation
  lupopedia.edges:
    - type: DEPENDSONTABLE
      to: "lupo_actors"
      comment: "actor_id"
    - type: DEPENDSONTABLE
      to: "lupo_dialog_pending_tasks"
      comment: "current_task_id"
    - type: DEFINESSCHEMAFOR
      to: "lupo_agent_status"
---
# Table: lupo_agent_status

## Purpose
The **Agent Status** table provides a real-time "heartbeat" and lifecycle tracker for all builder agents. It allows the human operator to see at a glance which agents are currently executing tasks, which are available, and which have hit API limits or session timeouts.

## Schema

### Primary Key
- `actor_id`: bigint NOT NULL

### Columns

| Column | Type Definition | Description |
|---|---|---|
| `actor_id` | `bigint NOT NULL` | Primary key (FK lupo_actors) |
| `status_code` | `enum('ACTIVE','IDLE','SLEEPING','THROTTLED','FAILED','UNKNOWN','MANUAL')` | Current agent state |
| `heartbeat_ymdhis` | `bigint NOT NULL` | Timestamp of last agent activity or heartbeat (YYYYMMDDHHIISS) |
| `status_note` | `varchar(255)` | Human-readable detail or error message |

### Indexes

| Index | Columns | Unique |
|---|---|---|
| `PRIMARY` | `actor_id` | yes |

## Doctrine
- **Vocabulary:**
    - `ACTIVE`: Executing a task.
    - `IDLE`: Awaiting assignment.
    - `SLEEPING`: Context exhausted / session dead.
    - `THROTTLED`: API rate limit hit.
    - `FAILED`: Execution error.
    - `MANUAL`: Explicit operator override.
- **Update Logic:** Status is updated via task transitions, agent heartbeats, or manual UI override.
- **Source of Truth:** Aligns with `lupo-database/lupopedia/json/lupo_agent_status.json`.
