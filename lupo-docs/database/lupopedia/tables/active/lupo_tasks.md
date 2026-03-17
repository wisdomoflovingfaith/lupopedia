---
lupopedia.headers:
  lupopedia.version: "4.0.79"
  lupopedia.schema: "database_table"
  system_version: "4.0.79"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_tasks.md"
  web_path: "[lupo_tasks](http://www.lupopedia.com/database/lupopedia/tables/active/lupo_tasks)"
  last_modified_utc: "20260317"
  channel_id: 42
  actor_id: 1
  actor_name: "wolfie"
  delegation_chain: "wolfie:root"
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
  version: "4.0.79"
  last_verified: "20260317"
  last_verified_by: "wolfie"
---
# file: lupo_tasks — session: L-LUPO-ROOT-WOLFIE — delegation: wolfie:root — web_path: http://www.lupopedia.com/database/lupopedia/tables/active/lupo_tasks

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

## Namespace

- **Domain:** Core
- **Subdomain:** Task Management
- **Related Tables:** `lupo_task_dependencies`, `lupo_task_assignments`, `lupo_decisions`

Purpose: Auto-generated documentation for lupo_tasks from TOON schema.
Type: database_table
Status: production_ready
Volume: unknown

## 1. Overview
- Key responsibilities: schema reference, storage, and lookup for this table.
- System role: persists data for the Lupopedia database subsystem.
- Importance: enables data integrity and downstream features tied to this table.

## 2. Schema Reference
Primary Key: task_id
Field Categories: see full field list below.

### All Fields
| Column | Type | Notes |
|---|---|---|
| task_id | bigint NOT NULL | from TOON |
| task_key | varchar(64) NOT NULL | from TOON |
| channel_id | bigint NOT NULL | from TOON |
| owner_actor_id | bigint NOT NULL | from TOON |
| task_type_id | bigint NOT NULL | from TOON |
| status_id | bigint NOT NULL | from TOON |
| priority_id | bigint NOT NULL | from TOON |
| title | varchar(255) NOT NULL | from TOON |
| description | text | from TOON |
| prompt_path | varchar(512) | from TOON |
| acting_as_actor_id | bigint | from TOON |
| estimated_duration_seconds | int | from TOON |
| actual_duration_seconds | int | from TOON |
| created_ymdhis | bigint NOT NULL DEFAULT 0 | from TOON |
| updated_ymdhis | bigint NOT NULL | from TOON |
| started_ymdhis | bigint | from TOON |
| completed_ymdhis | bigint | from TOON |
| is_deleted | tinyint NOT NULL DEFAULT 0 | from TOON |
| deleted_ymdhis | bigint | from TOON |
| metadata_json | text | from TOON |

## 3. Relationships and Dependencies
- Primary relationships: not specified in TOON relationships array.
- Referencing tables: unknown (use edge suggester tool).
- Integration points: see outbound edges in FLARE footer.

## 4. Indexes and Performance
Primary Indexes:
- task_id
Performance Indexes:
- lupo_tasks_idx_acting_as_actor_id
- lupo_tasks_idx_channel_id
- lupo_tasks_idx_created_ymdhis
- lupo_tasks_idx_is_deleted
- lupo_tasks_idx_owner_actor_id
- lupo_tasks_idx_priority_id
- lupo_tasks_idx_status_id
- lupo_tasks_uniq_task_key_per_channel
Index Strategy: derived from TOON index definitions.

## 5. Usage Patterns
Common Queries:
```sql
SELECT * FROM lupo_tasks WHERE task_id = :id;
SELECT COUNT(*) AS total FROM lupo_tasks WHERE is_deleted = 0;
SELECT * FROM lupo_tasks ORDER BY task_id DESC LIMIT 25;
UPDATE lupo_tasks SET updated_ymdhis = :ts WHERE task_id = :id;
```
Best Practices: always filter soft deletes where applicable.
Anti-Patterns: avoid full table scans on large datasets.

## 6. Performance Considerations
- High-volume operations: dependent on feature usage.
- Optimization tips: rely on existing indexes; add new indexes only with TOON updates.
- Scaling considerations: paginate reads and batch writes.

## 7. Data Integrity
- Constraints: see NOT NULL and DEFAULT values in TOON fields.
- Validation rules: enforced at application layer.
- Soft delete: use is_deleted/deleted_ymdhis if present.

## 8. Common Issues and Solutions
- Performance issues: add missing indexes via schema update.
- Data consistency: ensure foreign key relationships are enforced in application logic.
- Troubleshooting: compare against TOON schema for mismatches.

## 9. Future Enhancements
- Enrich relationships with discovered edges.
- Add usage-specific examples once feature usage is known.
