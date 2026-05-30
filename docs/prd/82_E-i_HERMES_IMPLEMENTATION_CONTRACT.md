---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/prd/82_E-i_HERMES_IMPLEMENTATION_CONTRACT.md
  web_path: https://www.lupopedia.com/lupopedia/docs/prd/82_E-i_HERMES_IMPLEMENTATION_CONTRACT.md
  status: active
  when_updated: '20260513033046'
  trust_tier: canonical
  questions_toon: null
  memory_toon: memory/development/canonical/1026/04/82_hermes_implementation_contract.toon
  atoms_toon: memory/atoms/lupopedia_global_constants.atom.toon
  transcript_jsonl: 0/development/hermes-implementation-contract
  artifact_type: prd
  artifact_kind: specification
  channel_key: development
  federation_node_id: 0
  thread_key: null
  lupopedia.schema: prd
  prd_cluster: 00_A-i_16_B-i_16_C-i_82_A-i_82_E-i
  title: 'PRD 82_E: HERMES Implementation Contract'
  summary: PHP implementation pattern for HERMES service including required methods, helper methods, file I/O rules, timestamp handling, and validation expectations.
---
# PRD 82_E: HERMES Implementation Contract

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

This PRD defines the PHP implementation contract for HERMES, including the service class structure, required methods, helper methods, file I/O rules, timestamp handling, and validation expectations.

---

## Table of Contents

1. [Service Class Structure](#1-service-class-structure)
2. [Required Public Methods](#2-required-public-methods)
3. [Helper Methods](#3-helper-methods)
4. [File I/O Rules](#4-file-io-rules)
5. [Timestamp Handling](#5-timestamp-handling)
6. [ID Generation](#6-id-generation)
7. [Database Access](#7-database-access)
8. [Validation Expectations](#8-validation-expectations)
9. [Error Handling](#9-error-handling)
10. [Performance Requirements](#10-performance-requirements)

---

## 1. Service Class Structure

HERMES is implemented as `HERMES` class in `includes/classes/hermes.php`. It does not use namespaces or framework patterns. It uses `DatabaseFactory::getConnection()` for `lupo_dialog_pending_tasks` inserts and plain PHP file I/O for transcript JSONL.

```php
<?php

require_once __DIR__ . '/../includes/classes/DatabaseFactory.php';
require_once __DIR__ . '/../includes/classes/IdGenerator.php';
require_once __DIR__ . '/../includes/classes/timestamp_ymdhis.php';

use DatabaseFactory;
use IdGenerator;
use timestamp_ymdhis;
use Exception;

class hermes
{
    private $db;
    
    public function __construct()
    {
        $this->db = DatabaseFactory::getConnection();
    }
    
    // Required methods implementation...
}
```

### Dependencies

- **DatabaseFactory**: Database connection factory
- **IdGenerator**: For generating IDs
- **timestamp_ymdhis**: For timestamp generation
- **timestamp_ymdhis**: For UTC timestamps
- **No external libraries**: Pure PHP implementation

### Namespace Requirements

Any reference to global classes `timestamp_ymdhis` or `IdGenerator` MUST use a leading backslash:
```php
$timestamp = \timestamp_ymdhis::now();
$id = \IdGenerator::generate();
```

This prevents PHP from resolving them under any namespace context which can trigger autoload stalls.

### Namespace KAPU for HERMES

HERMES MUST NOT be implemented as a namespaced PHP service.

**Forbidden examples:**
```php
namespace App\Services;
class HermesService
use PDO_DB;
```

**Required implementation target:**
```
includes/classes/hermes.php
```

**Required class form:**
```php
class HERMES
```

HERMES MUST use existing Lupopedia include/class patterns and existing database access helpers such as `DatabaseFactory::getConnection()` where applicable.

Do NOT introduce framework-style namespaces, Composer autoload assumptions, Laravel-style service classes, or PSR-style service architecture unless a later PRD explicitly grants an exception.

---

## 2. Required Public Methods

### route() Method

```php
/**
 * Routing decision only (no transcript write inside this method).
 * task_assignee_id: authoritative assignee for [task]; no body parsing.
 * Authorization: DialogMvpService::isTaskAssigneeAuthorized() when $auth_user_id > 0.
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
    // Implementation from PRD 82_D
}
```

**Responsibilities:**
- Parse message prefixes (`[task]`, `[alert]`, etc.)
- For `[task]`, use `$task_assignee_id` as assignee (no body parsing)
- Validate with `DialogMvpService::isTaskAssigneeAuthorized` when `$auth_user_id > 0`
- Return routing decision array

**Return Structure:**
```php
[
    'action' => 'task|alert|decision|question|directed|error',
    'task_target_actor_id' => 0, // for tasks
    'routing_provenance' => 'hermes:task-router|hermes:error|...',
    'message_type' => 'task|alert|decision|question|...',
    'destination' => 'task_queue|chat_stream|...',
    'raw_message' => $message,
    'ok' => true|false,
    'task_summary' => 'summary text', // for tasks
    'channel_id' => $channel_id,
    'from_actor_id' => $from_actor_id,
    'to_actor_id' => $to_actor_id
]
```

### appendTranscript() Method

```php
/**
 * Append one JSONL line to the canonical transcript file only:
 * memory/transcripts/{federation_node_id}/{channel_key}/{prd_cluster}.jsonl
 * No SQL. Creates directories as needed. FILE_APPEND | LOCK_EX.
 */
public function appendTranscript(
    $federation_node_id,
    $channel_key,
    $prd_cluster,
    array $message_data
) {
    // Implementation from PRD 82_C
}
```

**Responsibilities:**
- Write one JSONL line to canonical transcript path
- Create directories as needed
- Use `FILE_APPEND | LOCK_EX`
- No database operations

**Implementation:**
```php
$path = "memory/transcripts/{$federation_node_id}/{$channel_key}/{$prd_cluster}.jsonl";
$dir = dirname($path);

if (!is_dir($dir)) {
    mkdir($dir, 0755, true);
}

$line = json_encode($message_data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
$result = file_put_contents($path, $line . "\n", FILE_APPEND | LOCK_EX);

return $result !== false;
```

### createPendingTask() Method

```php
/**
 * Insert pending task when routing decision action is task and routing is not hermes:error.
 * task_id: IdGenerator::generate(); timestamps: timestamp_ymdhis::now().
 */
public function createPendingTask(array $routing_decision, $message_id) {
    // Implementation from PRD 82_D
}
```

**Responsibilities:**
- Insert into `lupo_dialog_pending_tasks` using `IdGenerator::generate()`
- Use `timestamp_ymdhis::now()` for timestamps
- Use `DatabaseFactory::getConnection()` and prepared statements
- Execute only when `action === 'task'` and `routing_provenance !== 'hermes:error'`

---

## 3. Helper Methods

### resolveActorKey() (Optional)

```php
/**
 * Resolve actor key to actor_id.
 * First tries database lookup, then optional registry.json fallback.
 */
protected function resolveActorKey($actor_key)
{
    // Database lookup
    $stmt = $this->db->prepare("SELECT actor_id FROM lupo_actors WHERE actor_key = ?");
    $stmt->execute([$actor_key]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($result) {
        return $result['actor_id'];
    }
    
    // Optional: registry.json fallback
    return $this->resolveActorFromAgentsRegistry($actor_key);
}
```

### resolveActorFromAgentsRegistry() (Optional)

```php
/**
 * Fallback lookup in database/.../actors/actor_id/registry.json agents map.
 * Not used for [task] assignee; task_assignee_id remains authoritative.
 */
protected function resolveActorFromAgentsRegistry($actor_key)
{
    // Implementation for registry.json fallback
    // This is optional and for non-chat tooling only
}
```

### loadAgentsRegistryActorMap() (Optional)

```php
/**
 * Load agents map from registry.json files.
 * Caches the map for performance.
 */
protected function loadAgentsRegistryActorMap()
{
    // Implementation for loading registry.json
    // Return array of actor_key => actor_id mappings
}
```

---

## 4. File I/O Rules

### Transcript Files

**Path Construction:**
```php
$path = "memory/transcripts/{$federation_node_id}/{$channel_key}/{$prd_cluster}.jsonl";
```

**Directory Creation:**
```php
$dir = dirname($path);
if (!is_dir($dir)) {
    mkdir($dir, 0755, true);
}
```

**File Append:**
```php
$line = json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
file_put_contents($path, $line . "\n", FILE_APPEND | LOCK_EX);
```

### Staging Toon Files (Optional)

**Write Pattern:**
```php
$toon_data = [
    'type' => 'staging_memory',
    'channel_key' => $channel_key,
    'prd_cluster' => $prd_cluster,
    'trust_tier' => 'staging',
    'when_updated' => \timestamp_ymdhis::now(),
    'source_actor_id' => 15,
    'patterns' => $patterns
];

$json = json_encode($toon_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
file_put_contents($toon_path, $json);
```

### File Safety Rules

- **Path Validation**: Ensure paths are within allowed directories
- **Directory Traversal**: Prevent `../` attacks
- **Permission Check**: Verify write permissions before operations
- **Error Handling**: Log failures but don't crash routing

---

## 5. Timestamp Handling

### Required Timestamp Format

All timestamps MUST use packed UTC format: `YYYYMMDDHHIISS`

**Correct Usage:**
```php
$timestamp = \timestamp_ymdhis::now();
```

**Forbidden:**
```php
// DO NOT USE
time()                    // Unix epoch
date('Y-m-d H:i:s')       // MySQL DATETIME
new DateTime()            // DateTime object
microtime(true)           // Unix epoch with microseconds
```

### Timestamp Fields

| Field | Format | Example |
|---|---|---|
| `ts` (transcript) | `YYYYMMDDHHIISS` | `20260427124627` |
| `created_ymdhis` | `YYYYMMDDHHIISS` | `20260427124627` |
| `updated_ymdhis` | `YYYYMMDDHHIISS` | `20260427124627` |
| `completed_ymdhis` | `YYYYMMDDHHIISS` | `20260427124627` |

### Timestamp in JSON

**In JSONL files:**
```json
{"ts":20260427124627,"from_actor_id":1,...}
```

**In database:**
- Stored as `BIGINT`
- No quotes in SQL
- Quoted in YAML headers only

---

## 6. ID Generation

### Required ID Generator

All IDs MUST be generated using `IdGenerator::generate()`

**Correct Usage:**
```php
$task_id = \IdGenerator::generate();
$message_id = \IdGenerator::generate();
```

**ID Format:**
- Format: `YYYYMMDDHHIISS + 4-digit sequence`
- Example: `202604271246270001`
- Capacity: 9,999 IDs per second

### ID Fields

| Table | Field | Generator |
|---|---|---|
| `lupo_dialog_pending_tasks` | `dialog_pending_task` | `IdGenerator::generate()` |
| `lupo_dialog_pending_tasks` | `message_id` | `IdGenerator::generate()` (if not provided) |

### Forbidden ID Methods

```php
// DO NOT USE
AUTO_INCREMENT            // Database-generated
UUID                      // Not deterministic
uniqid()                  // Not collision-safe
random_int()              // Not timestamped
```

---

## 7. Database Access

### Required Database Class

All database operations MUST use `DatabaseFactory::getConnection()`

**Connection:**
```php
// Established via constructor
public function __construct()
{
    $this->db = DatabaseFactory::getConnection();
}
```

### Insert Operations

**Pattern:**
```php
$stmt = $this->db->prepare("INSERT INTO table_name (column1, column2) VALUES (?, ?)");
$stmt->execute([$value1, $value2]);
```

**Example:**
```php
$stmt = $this->db->prepare("INSERT INTO lupo_dialog_pending_tasks (dialog_pending_task, message_id, channel_id, task_assignee_id, task_description, created_ymdhis) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->execute([
    $task_id,
    $message_id,
    $channel_id,
    $assignee_id,
    $creator_id,
    $task_body,
    'pending',
    'medium',
    $created_ts,
    $updated_ts,
    null,
    'task',
    json_encode($routing_decision)
]);
```

### Query Operations

**Prepared Statements:**
```php
$stmt = $this->db->prepare("SELECT * FROM lupo_actors WHERE actor_key = ?");
$stmt->execute([$actor_key]);
$result = $stmt->fetch(PDO::FETCH_ASSOC);
```

### Forbidden Database Patterns

```php
// DO NOT USE
mysqli_*                 // MySQL-specific functions
PDO::query()             // Without prepared statements
Raw SQL strings          // Without proper escaping
Database transactions    // Not needed for HERMES
```

---

## 8. Validation Expectations

### Input Validation

**route() Method:**
- Validate `$task_assignee_id` is positive integer
- Validate `$channel_id` is positive integer
- Validate `$from_actor_id` and `$to_actor_id` are positive integers
- Validate message is not empty

**appendTranscript() Method:**
- Validate `$federation_node_id` is non-negative integer
- Validate `$channel_key` and `$prd_cluster` are non-empty strings
- Validate `$message_data` contains required fields

**createPendingTask() Method:**
- Validate routing decision structure
- Validate `$message_id` is positive integer or generate new one

### Output Validation

**Routing Decision:**
```php
$required_fields = ['action', 'routing_provenance', 'message_type', 'ok'];
foreach ($required_fields as $field) {
    if (!isset($decision[$field])) {
        throw new Exception("Missing required field: $field");
    }
}
```

**Transcript Record:**
```php
$required_fields = ['ts', 'from_actor_id', 'to_actor_id', 'message_text', 'message_type', 'routing_provenance'];
foreach ($required_fields as $field) {
    if (!isset($message_data[$field])) {
        throw new Exception("Missing required transcript field: $field");
    }
}
```

---

## 9. Error Handling

### Logging Requirements

**Error Log Format:**
```php
error_log("HERMES Error: {$error_message} Context: " . json_encode($context));
```

**Non-Fatal Errors:**
- Log but continue processing
- Return appropriate error status
- Do not crash the routing process

**Fatal Errors:**
- Database connection failures
- File system permission errors
- Invalid configuration

### Exception Handling

```php
try {
    $result = $this->db->insert('lupo_dialog_pending_tasks', $data);
    return $task_id;
} catch (Exception $e) {
    error_log("HERMES: Failed to create pending task: " . $e->getMessage());
    return false;
}
```

### Graceful Degradation

- Transcript write failure: Continue with routing
- Task creation failure: Return error status
- Authorization failure: Return error routing
- Database unavailable: Log error, return error

---

## 10. Performance Requirements

### Method Performance Targets

| Method | Target | Notes |
|---|---|---|
| `route()` | < 50ms | Including authorization check |
| `appendTranscript()` | < 100ms | File I/O with lock |
| `createPendingTask()` | < 100ms | Database insert |
| `resolveActorKey()` | < 25ms | Database lookup |

### Concurrency Requirements

- Support multiple concurrent writers to transcript files
- File locks prevent corruption
- Database operations use prepared statements
- No global state or static variables

### Memory Requirements

- Constant memory usage regardless of file size
- Stream large files, don't load into memory
- Clean up temporary resources
- Avoid memory leaks in long-running processes

---

## Cross-References

- **[PRD 82_A-i](docs/prd/82_A-i_HERMES_MESSAGE_ROUTING_MEMORY_GATEWAY.md)** -- HERMES overview and system integration
- **[PRD 82_B-i](docs/prd/82_B-i_HERMES_ROUTING_HEADER_AND_HAWAIIAN_SEMANTICS.md)** -- Routing header and semantic fields
- **[PRD 82_C-i](docs/prd/82_C-i_HERMES_TRANSCRIPT_JSONL_PROTOCOL.md)** -- Transcript JSONL protocol
- **[PRD 82_D-i](docs/prd/82_D-i_HERMES_TASK_ROUTING_AND_PENDING_TASKS.md)** -- Task routing and pending tasks
- **[DATABASE_DOCTRINE.md](lupo-rules/root/DATABASE_DOCTRINE.md)** -- Database access rules
- **[PRD 16_C-i](docs/prd/16_C-i_LUPOPEDIA_HEADERS.md)** -- Header field definitions

---

**Implementation Requirements:**
- No namespace - plain PHP class in includes/classes/hermes.php
- Use `DatabaseFactory::getConnection()` in constructor
- Use `\timestamp_ymdhis::now()` for timestamps
- Use `\IdGenerator::generate()` for IDs
- Follow file I/O rules for transcript writes
- Validate all inputs and outputs
- Handle errors gracefully
- Meet performance targets
- No external dependencies
