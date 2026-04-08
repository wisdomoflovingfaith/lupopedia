---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: prd
  when_updated: "20260330163000"
  file_path_from_root: "lupo-docs/prd/10_tasks_workflow.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/prd/10_tasks_workflow.md"
  last_modified_utc: "20260330163000"
  channel_id: 42
  thread_id: "prd-grouped"
  actor_id: 102
  actor_name: "HEPHAESTUS"
  delegation_chain: "hephaestus:root|lilith:audit"
  artifact_type: "prd"
  artifact_kind: "database_namespace"
  purpose: "PRD for tasks, escalations, human requests, and workflow management"
  tags:
  - "prd"
  - "database"
  - "namespace"
  - "tasks_workflow"
lupopedia.edges:
  outbound_edges:
    - to: "lupo-docs/prd/00_root_constitutional_system_requirements.md"
      type: references
      weight: 1.0
      reason: "Constitutional anchor"
    - to: "lupo-docs/database/lupopedia/tables/"
      type: references
      weight: 1.0
      reason: "Detailed table documentation"
    - to: "lupo-docs/prd/01_core_identity.md"
      type: references
      weight: 1.0
      reason: "Tasks assigned to actors"
    - to: "lupo-docs/prd/08_governance_rules.md"
      type: references
      weight: 1.0
      reason: "Task workflows governed by rules"
lupopedia.footer:
  last_verified: "20260330163000"
  verified_by:
    actor_id: 102
    agent_name_identity: Cursor IDE Agent
  orchestrator: "hephaestus:root"
---

# PRD: Tasks, Escalations, Human Requests, and Workflow Management

## Overview

**Namespace Purpose:** Manages task creation, assignment, escalation, human requests, and workflow automation. This namespace provides foundation for systematic task processing and human interaction management.

**Primary Actors:** 
- Task creators (via lupo_tasks)
- Escalation managers (via lupo_escalation_tasks)
- Human request handlers (via lupo_human_requests)
- Workflow coordinators (via lupo_routing_decisions)
- Ticket managers (via lupo_tickets)

**Constitutional Compliance:** All tables in this namespace follow Lupopedia constitutional rules:
- NO foreign keys (relationships in application logic)
- NO triggers
- NO stored procedures
- BIGINT timestamps (YYYYMMDDHHIISS UTC)
- Explicit ID generation (application layer)
- Soft delete (is_deleted + deleted_ymdhis)

## Tables in This Namespace

| Table | Purpose | Primary Key | Key Application Relationships |
|-------|---------|-------------|------------------------------|
| `lupo_tasks` | Task definitions and tracking | `task_id` | Central to task system |
| `lupo_escalation_tasks` | Escalation task management | `escalation_task_id` | Escalation workflow |
| `lupo_human_requests` | Human request tracking | `human_request_id` | Human interaction system |
| `lupo_human_request_responses` | Responses to human requests | `response_id` | Request response system |
| `lupo_human_request_context` | Context for human requests | `context_id` | Request context tracking |
| `lupo_routing_decisions` | Workflow routing decisions | `routing_decision_id` | Task routing system |
| `lupo_tickets` | Ticket system for support | `ticket_id` | Support ticket system |
| `lupo_ticket_messages` | Ticket message tracking | `ticket_message_id` | Ticket communication |

## Table Details

### `lupo_tasks`

**Purpose:** Defines tasks with assignments, priorities, and status tracking.

**Columns:**

| Column | Type | Nullable | Default | Description |
|--------|------|----------|---------|-------------|
| task_id | BIGINT | NO | (application) | Primary key, generated via IdGenerator |
| title | VARCHAR(255) | NO |  | Task title |
| description | TEXT | YES | NULL | Task description |
| assigned_actor_id | BIGINT | YES | NULL | Foreign reference to lupo_actors |
| created_by_actor_id | BIGINT | NO |  | Foreign reference to lupo_actors |
| task_type | VARCHAR(32) | NO | 'manual' | Type: manual, automated, scheduled |
| priority | VARCHAR(16) | NO | 'normal' | Priority: low, normal, high, critical |
| status | VARCHAR(32) | NO | 'pending' | Status: pending, in_progress, completed, cancelled |
| due_ymdhis | BIGINT | YES | NULL | UTC timestamp when task is due |
| created_ymdhis | BIGINT | NO | (application) | UTC timestamp YYYYMMDDHHIISS |
| updated_ymdhis | BIGINT | NO | (application) | UTC timestamp YYYYMMDDHHIISS |
| completed_ymdhis | BIGINT | YES | NULL | UTC timestamp when completed |
| is_deleted | TINYINT | NO | 0 | Soft delete flag |
| deleted_ymdhis | BIGINT | YES | NULL | UTC timestamp when deleted |

**Indexes:**

| Index Name | Columns | Purpose |
|------------|---------|---------|
| idx_tasks_assigned | assigned_actor_id, status, priority, is_deleted | Actor's tasks |
| idx_tasks_created | created_by_actor_id, created_ymdhis, is_deleted | Created by actor |
| idx_tasks_priority | priority, status, due_ymdhis, is_deleted | Priority-based queries |
| idx_tasks_status | status, created_ymdhis, is_deleted | Status-based queries |

### `lupo_escalation_tasks`

**Purpose:** Manages escalation tasks for urgent or critical issues.

**Columns:**

| Column | Type | Nullable | Default | Description |
|--------|------|----------|---------|-------------|
| escalation_task_id | BIGINT | NO | (application) | Primary key, generated via IdGenerator |
| original_task_id | BIGINT | YES | NULL | Foreign reference to lupo_tasks |
| escalation_level | INT | NO | 1 | Current escalation level |
| max_escalation_level | INT | NO | 3 | Maximum escalation level |
| escalated_by_actor_id | BIGINT | NO |  | Foreign reference to lupo_actors |
| assigned_actor_id | BIGINT | YES | NULL | Foreign reference to lupo_actors |
| escalation_reason | TEXT | NO |  | Reason for escalation |
| status | VARCHAR(32) | NO | 'active' | Status: active, resolved, closed |
| created_ymdhis | BIGINT | NO | (application) | UTC timestamp YYYYMMDDHHIISS |
| updated_ymdhis | BIGINT | NO | (application) | UTC timestamp YYYYMMDDHHIISS |
| resolved_ymdhis | BIGINT | YES | NULL | UTC timestamp when resolved |
| is_deleted | TINYINT | NO | 0 | Soft delete flag |
| deleted_ymdhis | BIGINT | YES | NULL | UTC timestamp when deleted |

**Indexes:**

| Index Name | Columns | Purpose |
|------------|---------|---------|
| idx_escalation_tasks_original | original_task_id, escalation_level, is_deleted | Original task escalations |
| idx_escalation_tasks_assigned | assigned_actor_id, status, is_deleted | Assigned escalations |
| idx_escalation_tasks_level | escalation_level, status, created_ymdhis, is_deleted | Level-based queries |

### `lupo_human_requests`

**Purpose:** Tracks human requests requiring attention or action.

**Columns:**

| Column | Type | Nullable | Default | Description |
|--------|------|----------|---------|-------------|
| human_request_id | BIGINT | NO | (application) | Primary key, generated via IdGenerator |
| request_type | VARCHAR(32) | NO | 'general' | Type: general, support, escalation, approval |
| request_title | VARCHAR(255) | NO |  | Request title |
| request_content | TEXT | NO |  | Request content |
| requested_by_actor_id | BIGINT | NO |  | Foreign reference to lupo_actors |
| assigned_actor_id | BIGINT | YES | NULL | Foreign reference to lupo_actors |
| priority | VARCHAR(16) | NO | 'normal' | Priority: low, normal, high, critical |
| status | VARCHAR(32) | NO | 'open' | Status: open, in_progress, resolved, closed |
| created_ymdhis | BIGINT | NO | (application) | UTC timestamp YYYYMMDDHHIISS |
| updated_ymdhis | BIGINT | NO | (application) | UTC timestamp YYYYMMDDHHIISS |
| resolved_ymdhis | BIGINT | YES | NULL | UTC timestamp when resolved |
| is_deleted | TINYINT | NO | 0 | Soft delete flag |
| deleted_ymdhis | BIGINT | YES | NULL | UTC timestamp when deleted |

**Indexes:**

| Index Name | Columns | Purpose |
|------------|---------|---------|
| idx_human_requests_requested | requested_by_actor_id, status, priority, is_deleted | Requested by actor |
| idx_human_requests_assigned | assigned_actor_id, status, is_deleted | Assigned requests |
| idx_human_requests_type | request_type, status, created_ymdhis, is_deleted | Type-based queries |

## Cross-Namespace Dependencies

| Dependency | Direction | Purpose | Tables Involved |
|------------|------------|---------|------------------|
| 10_tasks_workflow | This → 01_core_identity | Task assignment | assigned_actor_id references |
| 10_tasks_workflow | This → 08_governance_rules | Task governance | Rules control task workflows |
| 10_tasks_workflow | This → 02_channels_discussions | Task discussions | Tasks discussed in channels |

## State Transitions

| State | Description | Transition To |
|--------|-------------|--------------|
| pending | Task waiting to start | in_progress, cancelled, deleted (soft) |
| in_progress | Task being worked on | completed, cancelled, deleted (soft) |
| completed | Task finished successfully | N/A |
| cancelled | Task cancelled | N/A |
| deleted | Soft-deleted | N/A (can't be restored without explicit action) |

## Security & Privacy

Task assignments are validated for permissions

Human requests are tracked for audit and compliance

Escalation paths are controlled by governance rules

Soft delete preserves task history for compliance

## Testing Requirements

Unit tests for task creation and assignment

Integration tests for escalation workflows

Performance tests for task routing and filtering

Soft delete behavior verification

## Usage Patterns

```php
// Create task
$taskService = new TaskService();
$taskId = $taskService->createTask($title, $description, $assignedActorId, $priority);

// Escalate task
$escalationService = new EscalationTaskService();
$escalationId = $escalationService->escalateTask($taskId, $reason, $escalatedByActorId);

// Create human request
$humanRequestService = new HumanRequestService();
$requestId = $humanRequestService->createRequest($type, $title, $content, $requestedByActorId);

// Route task
$routingService = new RoutingDecisionService();
$decisionId = $routingService->routeTask($taskId, $criteria);
```
