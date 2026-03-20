---
lupopedia.headers:
  version_when_written: "4.0.84"
  lupopedia.schema: "database_table"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_tasks.md"
  web_path: "[lupo_tasks](http://www.lupopedia.com/database/lupopedia/tables/active/lupo_tasks)"
  last_modified_utc: "20260320"
  channel_id: 42
  actor_id: 26
  actor_name: "thoth"
  delegation_chain: "thoth:knowledge"
  artifact_type: "table_documentation"
  artifact_kind: "table"
  purpose: "Task management and assignment; tracks work items, status, assignments, and completion"
  tags: ["database", "table", "core", "4.0.84"]

lupopedia.edges:
  comment: "Snapshot of edges for lupo_tasks table doc at 4.0.79 (grounded by repo search; non-exhaustive)."
  meta: "php_hits=4 python_hits=1"
  outbound_edges:
    - { to: "database.table.lupo_tasks", type: "DEFINES_SCHEMA_FOR", weight: 1.0 }
    - { to: "lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql", type: "schema_reference", weight: 1.0 }
    - { to: "lupo-database/lupopedia/toon/lupo_tasks.toon", type: "schema_reference", weight: 1.0 }
    - { to: "lupo-includes/classes/AdminTasksHandler.php", type: "USED_IN_PHP", weight: 0.9 }
    - { to: "lupo-includes/classes/AdminTasksHandler.php", type: "USED_IN_PHP", weight: 0.9 }
    - { to: "lupo-includes/classes/TaskService.php", type: "USED_IN_PHP", weight: 0.9 }
    - { to: "lupo-includes/classes/TaskService.php", type: "USED_IN_PHP", weight: 0.9 }
    - { to: "lupo-scripts/audit_schema_doctrine.php", type: "USED_IN_PHP", weight: 0.7 }
    - { to: "lupo-scripts/audit_schema_doctrine.py", type: "USED_IN_PYTHON", weight: 0.5 }

lupopedia.footer:
  last_verified: "20260320"
  last_verified_by: "thoth"
  orchestrator: "wolfie"
  next_action:
    - "Maintain schema consistency with install SQL and TOON files"
    - "Update documentation when schema changes occur"
---
# file: lupo_tasks — session: L-LUPO-ROOT-HEPHAESTUS — delegation: hephaestus:root — web_path: http://www.lupopedia.com/database/lupopedia/tables/active/lupo_tasks

# Table: lupo_tasks

Canonical table for **task management and work tracking**. Supports task creation, assignment, status tracking, and completion workflows with optional channel and project scoping.

## Purpose

- Track work items and tasks across the system
- Support task assignment to actors and channels
- Enable task status workflows (pending, in_progress, completed, etc.)
- Provide task metadata for planning and execution
- Support task dependencies and relationships
- Enable task prioritization and scheduling

## Schema

### Fields
| Column | Type | Description |
|--------|------|-------------|
| task_id | bigint NOT NULL | Primary key; **application-supplied** (no AUTO_INCREMENT) |
| task_key | varchar(64) NOT NULL | Unique task identifier within channel |
| channel_id | bigint NOT NULL | Channel this task belongs to |
| owner_actor_id | bigint NOT NULL | Actor who owns or created this task |
| title | varchar(255) NOT NULL | Human-readable task title |
| description | text | Detailed task description |
| prompt_path | varchar(512) | Path to prompt file or configuration |
| acting_as_actor_id | bigint | Actor acting as when executing task |
| estimated_duration_seconds | int | Estimated execution time in seconds |
| actual_duration_seconds | int | Actual execution time in seconds |
| created_ymdhis | bigint NOT NULL DEFAULT 0 | UTC timestamp when task was created |
| updated_ymdhis | bigint NOT NULL | UTC timestamp when task was last updated |
| started_ymdhis | bigint | UTC timestamp when task was started |
| completed_ymdhis | bigint | UTC timestamp when task was completed |
| is_deleted | tinyint NOT NULL DEFAULT '0' | Soft delete flag |
| deleted_ymdhis | bigint | UTC timestamp when task was deleted |
| metadata_json | text | Metadata JSON |
| task_type | varchar(64) | Type of task (feature, bug, chore, etc.) |
| task_status | varchar(64) | Current task status |
| task_priority | varchar(64) | Priority level |
| parent_agent_id | bigint | Parent agent for task delegation |
| consensus_hash | varchar(255) | Consensus decision hash |
| approval_chain_json | json | Approval chain JSON |
| task_embeddings | text | Task embeddings for search |

### Indexes
- `PRIMARY KEY (task_id)`
- `UNIQUE INDEX lupo_tasks_uniq_task_key_per_channel ON lupo_tasks (task_key, channel_id)`
- `INDEX lupo_tasks_idx_channel_id ON lupo_tasks (channel_id)`
- `INDEX lupo_tasks_idx_owner_actor_id ON lupo_tasks (owner_actor_id)`
- `INDEX lupo_tasks_idx_task_type ON lupo_tasks (task_type)`
- `INDEX lupo_tasks_idx_task_status ON lupo_tasks (task_status)`
- `INDEX lupo_tasks_idx_task_priority ON lupo_tasks (task_priority)`
- `INDEX lupo_tasks_idx_created_ymdhis ON lupo_tasks (created_ymdhis)`
- `INDEX lupo_tasks_idx_acting_as_actor_id ON lupo_tasks (acting_as_actor_id)`
- `INDEX lupo_tasks_idx_is_deleted ON lupo_tasks (is_deleted)`
- `INDEX lupo_tasks_idx_parent_agent_id ON lupo_tasks (parent_agent_id)`

## Where This Table Is Used

### Core System Usage

- **TaskService** - Primary task management and CRUD operations
- **AdminTasksHandler** - Administrative task interface and bulk operations
- **TODO system** - Task planning and version tracking
- **Channel task lists** - Task filtering by channel context

### Integration Points

- **Actor workflows** - Tasks created and assigned to actors
- **Channel coordination** - Tasks scoped to specific channels
- **Multi-agent coordination** - Task delegation and execution
- **Status workflows** - Task state transitions and lifecycle management

