---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/prd/82_D-i_HERMES_TASK_ROUTING_AND_PENDING_TASKS.md
  web_path: https://www.lupopedia.com/lupopedia/docs/prd/82_D-i_HERMES_TASK_ROUTING_AND_PENDING_TASKS.md
  status: active
  when_updated: '20260513033046'
  trust_tier: canonical
  questions_toon: null
  memory_toon: memory/development/canonical/1026/04/82_hermes_task_routing.toon
  atoms_toon: memory/atoms/lupopedia_global_constants.atom.toon
  transcript_jsonl: 0/development/hermes-task-routing
  artifact_type: prd
  artifact_kind: specification
  channel_key: development
  federation_node_id: 0
  thread_key: null
  lupopedia.schema: prd
  prd_cluster: 00_A-i_16_B-i_16_C-i_82_A-i_82_D-i
  title: 'PRD 82_D: HERMES Task Routing and Pending Tasks'
  summary: Canonical specification for HERMES [task] routing, task_assignee_id handling, authorization checks, and lupo_dialog_pending_tasks table operations.
---
# PRD 82_D: HERMES Task Routing and Pending Tasks

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

## Purpose

This PRD defines the canonical specification for HERMES task routing, including `[task]` message handling, `task_assignee_id` parameter, authorization checks, and the `lupo_dialog_pending_tasks` table operations.

---

## Table of Contents

1. [Task Routing Overview](#1-task-routing-overview)
2. [Authoritative Task Assignment](#2-authoritative-task-assignment)
3. [Authorization Checks](#3-authorization-checks)
4. [Routing Resolution Order](#4-routing-resolution-order)
5. [createPendingTask Operation](#5-creatependingtask-operation)
6. [lupo_dialog_pending_tasks Schema](#6-lupo_dialog_pending_tasks-schema)
7. [Task Queue Behavior](#7-task-queue-behavior)
8. [Implementation Contract](#8-implementation-contract)

---

## 1. Task Routing Overview

HERMES handles `[task]` messages through a deterministic routing process that creates pending tasks in the `lupo_dialog_pending_tasks` table. The task routing system ensures that:

- Tasks are assigned to specific actors via `task_assignee_id`
- Authorization checks validate department and channel scope
- No free-form parsing of assignee from message body
- Tasks are stored with proper metadata and timestamps

**Key Principles:**
- `task_assignee_id` is the ONLY source of assignee identity
- Message body contains only the task description
- Authorization is enforced for authenticated users
- Tasks are created only when routing succeeds

---

## 2. Authoritative Task Assignment

**Authoritative task assignment (normative):** `task_assignee_id` is the **only** assignee input to `HermesService::route()` for `[task]` rows. Routing does **not** parse assignee identity from the message body. 

### Deprecated Patterns

**DO NOT USE** these deprecated patterns:
- Free-form `[task] who: X what: Y` parsing
- Assignee name extraction from message text
- Unstructured task assignment strings

### Correct Pattern

**Normative UI/API pattern:**
```json
{
  "message_text": "[task] update PRD 50 section 5.3",
  "task_assignee_id": 116
}
```

**Message Body Format:**
- Prefix: `[task]`
- Content: Task description only
- No assignee information in body

### Routing Table Entry

| Message Pattern | Source | Destination | Chat Visibility | Memory Gateway Action |
|---|---|---|---|---|
| `[task] {description}` + explicit `task_assignee_id` (UI/POST) | Human operator | Task queue of assignee actor | Yes (routing confirmation) | Append JSONL; extract `task_assignment` pattern |
| `[task] {description}` without valid assignee id or failing department/channel check | Human operator | HERMES error (no pending task) | Yes (routing failure note) | Append JSONL with `routing_provenance: hermes:error` |

---

## 3. Authorization Checks

### When Authorization is Required

Authorization checks are performed when `auth_user_id > 0`. The check validates that the authenticated user can assign tasks to the specified actor within the given channel context.

### Authorization Method

**DialogMvpService::isTaskAssigneeAuthorized()** is called with these parameters:
```php
DialogMvpService::isTaskAssigneeAuthorized(
    $db,                    // Database connection
    $channel_id,            // Channel context
    $auth_user_id,          // Authenticated user ID
    $task_assignee_id,      // Target assignee
    $task_scope_admin_bypass // Admin bypass flag
)
```

### Authorization Rules

1. **Department Scope**: User must be in the same department as assignee
2. **Channel Access**: User must have access to the channel
3. **Actor Permissions**: Assignee must be eligible for task assignment
4. **Admin Bypass**: Admins can bypass department restrictions (if enabled)

### Authorization Outcomes

- **Authorized**: Task creation proceeds
- **Unauthorized**: Routing fails with `hermes:error` provenance
- **Error**: System error logged, routing fails

---

## 4. Routing Resolution Order

HERMES processes messages in this order:

1. **Pattern Match**: Check for `[task]` prefix
   - Extract task description from message body
   - Use `task_assignee_id` parameter only (no body parsing)
   - Proceed to authorization if `task_assignee_id > 0`

2. **Authorization Check** (when `auth_user_id > 0`)
   - Call `DialogMvpService::isTaskAssigneeAuthorized()`
   - Enforce department + channel scope
   - Allow admin bypass if configured

3. **Routing Decision**
   - Success: Create pending task, append transcript
   - Failure: Record error, append transcript with error provenance

4. **Task Creation** (only on success)
   - Call `createPendingTask()` with routing decision
   - Generate task ID using `IdGenerator`
   - Set timestamps using `timestamp_ymdhis::now()`

### Error Conditions

| Condition | Routing Provenance | Task Created |
|---|---|---|
| `task_assignee_id <= 0` | `hermes:error` | No |
| Authorization failed | `hermes:error` | No |
| Invalid actor ID | `hermes:error` | No |
| Database error | `hermes:error` | No |
| Success | `hermes:task-router` | Yes |

---

## 5. createPendingTask Operation

### Method Signature

```php
public function createPendingTask(array $routing_decision, $message_id)
```

### Execution Conditions

`createPendingTask()` executes only when:
- `action === 'task'`
- `task_target_actor_id > 0`
- `routing_provenance !== 'hermes:error'`

### ID Generation

- **task_id**: Generated using `IdGenerator::generate()`
- **message_id**: Use provided value or generate via `IdGenerator` if `<= 0`

### Timestamps

- **created_ymdhis**: `timestamp_ymdhis::now()`
- **updated_ymdhis**: `timestamp_ymdhis::now()`
- **completed_ymdhis**: null (not completed yet)

### Database Operation

```php
$db->insert('lupo_dialog_pending_tasks', [
    'dialog_pending_task' => $task_id,
    'message_id' => $message_id,
    'channel_id' => $routing_decision['channel_id'],
    'assignee_actor_id' => $routing_decision['task_target_actor_id'],
    'creator_actor_id' => $routing_decision['from_actor_id'],
    'task_body' => $routing_decision['raw_message'],
    'status' => 'pending',
    'priority' => 'medium',
    'created_ymdhis' => $created_ts,
    'updated_ymdhis' => $updated_ts,
    'completed_ymdhis' => null,
    'task_type' => $routing_decision['message_type'],
    'payload' => json_encode($routing_decision)
]);
```

### Error Handling

- Log database errors with full context
- Do not fail routing if task creation fails
- Record failure in routing decision
- Continue with transcript append

---

## 6. lupo_dialog_pending_tasks Schema

### Table Structure

| Column | Type | Description |
|---|---|---|
| `dialog_pending_task` | `BIGINT` | Primary key: task ID |
| `message_id` | `BIGINT` | Originating message ID |
| `channel_id` | `INT` | Channel context |
| `assignee_actor_id` | `INT` | Actor assigned to task |
| `creator_actor_id` | `INT` | Actor who created task |
| `task_body` | `TEXT` | Full task description |
| `status` | `VARCHAR(20)` | pending, in_progress, completed |
| `priority` | `VARCHAR(10)` | low, medium, high |
| `created_ymdhis` | `BIGINT` | Creation timestamp |
| `updated_ymdhis` | `BIGINT` | Last update timestamp |
| `completed_ymdhis` | `BIGINT` | Completion timestamp (null if pending) |
| `task_type` | `VARCHAR(50)` | Type of task (e.g., 'task') |
| `payload` | `JSON` | Additional routing metadata |

### Indexes

- Primary key on `dialog_pending_task`
- Index on `assignee_actor_id` for task lookup
- Index on `channel_id` for channel filtering
- Index on `status` for status queries

### Constraints

- `assignee_actor_id` must reference valid actor in `lupo_actors`
- `creator_actor_id` must reference valid actor in `lupo_actors`
- `status` must be one of: pending, in_progress, completed
- `priority` must be one of: low, medium, high

---

## 7. Task Queue Behavior

### Task Assignment

Tasks are assigned to actors through the `assignee_actor_id` field. The assignee:

- Receives the task in their task queue
- Can view task details and metadata
- Can update task status
- Can mark task as completed

### Task Status Flow

1. **pending**: Initial state after creation
2. **in_progress**: Assignee starts working on task
3. **completed**: Assignee finishes task

### Task Priority

- **low**: Non-urgent, can be deferred
- **medium**: Normal priority (default)
- **high**: Urgent, requires immediate attention

### Task Lookup

Actors can query their pending tasks:
```sql
SELECT * FROM lupo_dialog_pending_tasks 
WHERE assignee_actor_id = ? AND status = 'pending'
ORDER BY priority DESC, created_ymdhis ASC
```

### Task Updates

Task status updates must:
- Update `status` field
- Update `updated_ymdhis` timestamp
- Set `completed_ymdhis` when marked completed
- Preserve original creation metadata

---

## 8. Implementation Contract

### HermesService::route() Method

```php
/**
 * Routing decision for [task] messages.
 * task_assignee_id: authoritative assignee; no body parsing.
 * Authorization: DialogMvpService::isTaskAssigneeAuthorized() when auth_user_id > 0.
 */
public function route(
    $message,
    $from_actor_id,
    $to_actor_id,
    $channel_id,
    $task_assignee_id = 0,
    $auth_user_id = 0,
    $task_scope_admin_bypass = false
) {
    // Parse [task] prefix
    if (strpos($message, '[task]') !== 0) {
        return ['action' => 'directed', 'routing_provenance' => 'hermes:directed'];
    }
    
    // Validate task_assignee_id
    if ($task_assignee_id <= 0) {
        return [
            'action' => 'error',
            'routing_provenance' => 'hermes:error',
            'message' => 'Invalid task assignee ID'
        ];
    }
    
    // Authorization check
    if ($auth_user_id > 0) {
        $authorized = DialogMvpService::isTaskAssigneeAuthorized(
            $this->db,
            $channel_id,
            $auth_user_id,
            $task_assignee_id,
            $task_scope_admin_bypass
        );
        
        if (!$authorized) {
            return [
                'action' => 'error',
                'routing_provenance' => 'hermes:error',
                'message' => 'Unauthorized task assignment'
            ];
        }
    }
    
    // Success routing decision
    return [
        'action' => 'task',
        'task_target_actor_id' => $task_assignee_id,
        'routing_provenance' => 'hermes:task-router',
        'message_type' => 'task',
        'destination' => 'task_queue',
        'raw_message' => $message,
        'ok' => true,
        'task_summary' => substr($message, 6), // Remove [task] prefix
        'channel_id' => $channel_id,
        'from_actor_id' => $from_actor_id,
        'to_actor_id' => $task_assignee_id
    ];
}
```

### createPendingTask() Method

```php
/**
 * Insert pending task when routing decision action is task.
 * task_id: IdGenerator::generate(); timestamps: timestamp_ymdhis::now().
 */
public function createPendingTask(array $routing_decision, $message_id) {
    // Generate IDs and timestamps
    $task_id = \IdGenerator::generate();
    $now = \timestamp_ymdhis::now();
    
    // Generate message_id if needed
    if ($message_id <= 0) {
        $message_id = \IdGenerator::generate();
    }
    
    // Prepare data
    $data = [
        'dialog_pending_task' => $task_id,
        'message_id' => $message_id,
        'channel_id' => $routing_decision['channel_id'],
        'assignee_actor_id' => $routing_decision['task_target_actor_id'],
        'creator_actor_id' => $routing_decision['from_actor_id'],
        'task_body' => $routing_decision['raw_message'],
        'status' => 'pending',
        'priority' => 'medium',
        'created_ymdhis' => $now,
        'updated_ymdhis' => $now,
        'completed_ymdhis' => null,
        'task_type' => $routing_decision['message_type'],
        'payload' => json_encode($routing_decision)
    ];
    
    // Insert using DatabaseFactory
    try {
        $this->db->insert('lupo_dialog_pending_tasks', $data);
        return $task_id;
    } catch (Exception $e) {
        error_log("Failed to create pending task: " . $e->getMessage());
        return false;
    }
}
```

### Validation Requirements

- MUST validate `task_assignee_id` is positive integer
- MUST validate message starts with `[task]` prefix
- MUST call authorization check when `auth_user_id > 0`
- MUST use `IdGenerator` for ID generation
- MUST use `timestamp_ymdhis::now()` for timestamps
- MUST handle database errors gracefully

### Performance Requirements

- Route method must complete within 50ms
- Task creation must complete within 100ms
- Authorization check must complete within 25ms
- Database operations must use prepared statements

---

## Cross-References

- **[PRD 82_A-i](docs/prd/82_A-i_HERMES_MESSAGE_ROUTING_MEMORY_GATEWAY.md)** -- HERMES overview and system integration
- **[PRD 82_B-i](docs/prd/82_B-i_HERMES_ROUTING_HEADER_AND_HAWAIIAN_SEMANTICS.md)** -- Routing header and semantic fields
- **[PRD 82_C-i](docs/prd/82_C-i_HERMES_TRANSCRIPT_JSONL_PROTOCOL.md)** -- Transcript JSONL protocol
- **[PRD 82_E-i](docs/prd/82_E-i_HERMES_IMPLEMENTATION_CONTRACT.md)** -- PHP implementation contract
- **[PRD 10](docs/prd/10_A-i_TASK_QUEUE_SCHEMA.md)** -- Task queue table schema
- **[PRD 32](docs/prd/32_A-i_ACTOR_AUTHORITY.md)** -- Actor authority and permissions

---

**Constitutional Rules:**
- `task_assignee_id` is the ONLY authoritative assignee source
- No parsing of assignee from message body
- Authorization checks required for authenticated users
- Tasks created only when routing succeeds
- Use `IdGenerator` for all ID generation
- Use `timestamp_ymdhis::now()` for all timestamps
- No `who:` parsing or free-form assignment
