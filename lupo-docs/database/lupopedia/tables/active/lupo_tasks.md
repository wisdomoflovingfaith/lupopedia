---
lupopedia.headers:
  lupopedia.version: "4.0.80"
  lupopedia.schema: "database_table"
  system_version: "4.0.80"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_tasks.md"
  web_path: "[lupo_tasks](http://www.lupopedia.com/database/lupopedia/tables/active/lupo_tasks)"
  last_modified_utc: "20260318"
  channel_id: 42
  actor_id: 14
  actor_name: "hephaestus"
  delegation_chain: "hephaestus:root"
  artifact_type: "table_documentation"
  artifact_kind: "table"
  namespace: "core"
  purpose: "Task management and assignment; tracks work items, status, assignments, and completion"
  tags: ["database", "table", "core"]

lupopedia.edges:
  comment: "Snapshot of edges for lupo_tasks table doc at 4.0.79 (grounded by repo search; non-exhaustive)."
  meta: "php_hits=4 python_hits=1"
  outbound_edges:
    - { to: "database.table.lupo_tasks", type: "DEFINES_SCHEMA_FOR", weight: 1.0 }
    - { to: "lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql", type: "schema_reference", weight: 1.0 }
    - { to: "lupo-includes/classes/AdminTasksHandler.php", type: "USED_IN_PHP", weight: 0.9 }
    - { to: "lupo-includes/classes/AdminTasksHandler.php", type: "USED_IN_PHP", weight: 0.9 }
    - { to: "lupo-includes/classes/TaskService.php", type: "USED_IN_PHP", weight: 0.9 }
    - { to: "lupo-includes/classes/TaskService.php", type: "USED_IN_PHP", weight: 0.9 }
    - { to: "lupo-scripts/audit_schema_doctrine.php", type: "USED_IN_PHP", weight: 0.7 }
    - { to: "lupo-scripts/audit_schema_doctrine.py", type: "USED_IN_PYTHON", weight: 0.5 }

lupopedia.footer:
  version: "4.0.80"
  last_verified: "20260318"
  last_verified_by: "hephaestus"
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

## Schema (install SQL authority)

| Column | Type | Description |
|--------|------|-------------|
| task_id | bigint NOT NULL | Primary key; **application-supplied** (no AUTO_INCREMENT). |
| actor_id | bigint NOT NULL | Actor who owns or created this task. |
| channel_id | bigint NOT NULL | Channel this task belongs to. |
| project_id | bigint DEFAULT 0 | Project this task belongs to (0 = global). |
| parent_task_id | bigint DEFAULT NULL | Parent task for subtask relationships. |
| task_name | varchar(255) NOT NULL | Human-readable task title. |
| task_description | text DEFAULT NULL | Detailed task description. |
| task_type | varchar(64) NOT NULL | Type of task (feature, bug, chore, etc.). |
| priority | tinyint NOT NULL DEFAULT 0 | Priority level (0=low, 1=medium, 2=high). |
| status | varchar(32) NOT NULL DEFAULT 'pending' | Current task status. |
| assigned_to | bigint DEFAULT NULL | Actor assigned to complete this task. |
| due_ymdhis | bigint DEFAULT NULL | UTC timestamp when task is due. |
| completed_ymdhis | bigint DEFAULT NULL | UTC timestamp when task was completed. |
| created_ymdhis | bigint NOT NULL DEFAULT 0 | UTC timestamp when task was created. |
| updated_ymdhis | bigint NOT NULL DEFAULT 0 | UTC timestamp when task was last updated. |
| is_deleted | tinyint NOT NULL DEFAULT 0 | Soft delete flag. |
| deleted_ymdhis | bigint DEFAULT NULL | UTC timestamp when task was deleted. |

## Indexes

- `PRIMARY KEY (task_id)`
- `INDEX lupo_tasks_idx_actor` ON `lupo_tasks` (`actor_id`)
- `INDEX lupo_tasks_idx_channel` ON `lupo_tasks` (`channel_id`)
- `INDEX lupo_tasks_idx_project` ON `lupo_tasks` (`project_id`)
- `INDEX lupo_tasks_idx_status` ON `lupo_tasks` (`status`, `is_deleted`)
- `INDEX lupo_tasks_idx_parent` ON `lupo_tasks` (`parent_task_id`)
- `INDEX lupo_tasks_idx_priority` ON `lupo_tasks` (`priority`, `created_ymdhis`)
- `INDEX lupo_tasks_idx_due` ON `lupo_tasks` (`due_ymdhis`, `status`)

## Where This Table Is Used

### Core System Usage

- **TaskService** - Primary task management and CRUD operations
- **AdminTasksHandler** - Administrative task interface and bulk operations
- **TODO system** - Task planning and version tracking
- **Channel task lists** - Task filtering by channel context
- **Project management** - Task organization by project

### Integration Points

- **Actor workflows** - Tasks created and assigned to actors
- **Channel coordination** - Tasks scoped to specific channels
- **Project planning** - Tasks organized by project boundaries
- **Bayesian decisions** - Tasks can influence or be influenced by decisions
- **Status workflows** - Task state transitions and lifecycle management

## Task Status Values

- `pending` - New task, not yet started
- `in_progress` - Task currently being worked on
- `completed` - Task finished successfully
- `blocked` - Task waiting for dependency
- `cancelled` - Task cancelled or abandoned
- `on_hold` - Task temporarily suspended

