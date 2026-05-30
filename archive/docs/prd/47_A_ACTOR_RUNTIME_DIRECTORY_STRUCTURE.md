---
lupopedia.headers:
  header_format_version: "4.1.4"
  file_path_from_root: docs/prd/47_A_ACTOR_RUNTIME_DIRECTORY_STRUCTURE.md
  web_path: "https://www.lupopedia.com/lupopedia/docs/prd/47_A_ACTOR_RUNTIME_DIRECTORY_STRUCTURE.md"
  status: active
  when_updated: "20260421223000"
  trust_tier: canonical
  questions_toon: null
  memory_toon: memory/development/canonical/1026/04/47_actor_runtime_directory_structure.toon
  atoms_toon: null
  transcript_jsonl: 0/development/actor-runtime-directory-structure
  artifact_type: prd
  artifact_kind: specification
  channel_key: development
  federation_node_id: 0
  thread_id: null
  content_id: null
  content_parent_id: null
  default_collection_id: null
  lupopedia.schema: prd
  prd_cluster: 00_A_FORBIDDEN_AND_WHY_47_A_ACTOR_RUNTIME_DIRECTORY_STRUCTURE
  title: "PRD 47: Actor Runtime Directory Structure"
  summary: "Defines the canonical runtime directory structure for actor task isolation, channel coordination, and runtime state management."
---
# PRD 47: Actor Runtime Directory Structure

## 1. Purpose

Define the canonical directory structure for actor runtime state, task isolation, and channel-based coordination in the Lupopedia multi-agent system.

## 2. Scope

- In scope: Runtime directory layout, file formats, coordination patterns
- Out of scope: Database persistence, agent configuration, deployment infrastructure

## 3. Directory Structure

### 3.1 Root Structure

```
runtime/
+-- channels.jsonl
+-- {channel_key}/
    +-- actors.jsonl
    +-- {actor_id}/
        +-- tasks.jsonl
        +-- interrupts.jsonl
        +-- dependencies.jsonl
        +-- install_state.json
```

### 3.2 File Definitions

#### 3.2.1 `runtime/channels.jsonl`
Global channel registry and status tracking.

**Format (one JSON object per line):**
```json
{
  "channel_key": "development",
  "status": "active",
  "created_ymdhis": 20260420104500,
  "last_updated_ymdhis": 20260420104500,
  "coordinator_actor_id": 1,
  "description": "Development coordination channel"
}
```

#### 3.2.2 `runtime/{channel_key}/actors.jsonl`
Channel-specific actor registry and status.

**Format (one JSON object per line):**
```json
{
  "actor_id": 1,
  "status": "active",
  "channel_key": "development",
  "gateway": "ide_panel",
  "last_action_ymdhis": 20260420104500,
  "last_task_completed": "system-orchestration-001",
  "notes": "Primary coordinator for development tasks"
}
```

#### 3.2.3 `runtime/{channel_key}/{actor_id}/tasks.jsonl`
Task execution log for isolated actor tracking.

**Format (one JSON object per line):**
```json
{
  "task_id": "system-orchestration-001",
  "actor_id": 1,
  "channel_key": "development",
  "status": "completed",
  "created_ymdhis": 20260420104000,
  "started_ymdhis": 20260420104100,
  "completed_ymdhis": 20260420104500,
  "task_type": "coordination",
  "description": "Coordinate multi-agent system state",
  "handoff_to_actor_id": null,
  "dependencies": ["registry-update-001"]
}
```

#### 3.2.4 `runtime/{channel_key}/{actor_id}/interrupts.jsonl`
Interrupt handling and priority task queue.

**Format (one JSON object per line):**
```json
{
  "interrupt_id": "lilith-audit-001",
  "actor_id": 1,
  "channel_key": "development",
  "priority": "critical",
  "created_ymdhis": 20260420104200,
  "status": "processed",
  "source_actor_id": 2,
  "description": "LILITH audit requires immediate attention",
  "action_taken": "paused current task, processed audit findings"
}
```

#### 3.2.5 `runtime/{channel_key}/{actor_id}/dependencies.jsonl`
Dependency tracking and resolution log.

**Format (one JSON object per line):**
```json
{
  "dependency_id": "registry-update-001",
  "actor_id": 1,
  "channel_key": "development",
  "dependency_type": "task",
  "status": "resolved",
  "created_ymdhis": 20260420103900,
  "resolved_ymdhis": 20260420104100,
  "depends_on_actor_id": 9,
  "description": "Waiting for ANUBIS registry update completion"
}
```

#### 3.2.6 `runtime/{channel_key}/{actor_id}/install_state.json`
Actor installation and configuration state.

**Format (single JSON object):**
```json
{
  "actor_id": 1,
  "channel_key": "development",
  "install_status": "installed",
  "installed_ymdhis": 20260420100000,
  "last_verified_ymdhis": 20260420104500,
  "configuration_version": "1.0.2",
  "capabilities": ["orchestration", "release_governance", "multi_agent_coordination"],
  "gateway_config": {
    "type": "ide_panel",
    "endpoint": "local",
    "api_available": false
  }
}
```

## 4. Coordination Patterns

### 4.1 Channel-Key Isolation

- **One actor, one channel_key per task**
- Actors MAY change channel_key between tasks, never during a task
- Mixed-lane writes (multiple channel_keys in single task) are forbidden

### 4.2 Task Boundary Logging

Write to `tasks.jsonl` only at true task boundaries:
- Task start
- Task completion
- Handoff to another actor
- Violation or abort
- Interrupt processing

### 4.3 Registry-Based Coordination

- Actor registry (`actors.jsonl`) is authoritative for actor status
- Handoff registry (memory) provides continuity across tool/agent resets
- Runtime files supplement but do not replace registry authority

### 4.4 Gateway Awareness

- `manual_web_chat` actors: No API calls, use TOON handoffs only
- `api_http`, `api_ws`, `local_agent`, `ide_panel`, `system_daemon`, `batch_script`: Use native capabilities
- Gateway type affects coordination method and interrupt handling

## 5. File Operations

### 5.1 Atomic Operations

- All writes MUST be atomic (complete JSON objects)
- Use append-only patterns for .jsonl files
- File locks SHOULD be used for concurrent access

### 5.2 Cleanup and Retention

- Runtime files are ephemeral, not source control artifacts
- Retention policy: 30 days for completed tasks, 7 days for interrupts
- Cleanup process runs daily, preserves active/in-progress items

### 5.3 Backup and Recovery

- Runtime state can be reconstructed from registry + memory
- Critical tasks SHOULD be persisted to memory graph
- Runtime files are working storage, not authoritative storage

## 6. Integration Points

### 6.1 Memory Graph Integration

- Task completion events create memory nodes
- Dependencies map to memory edges
- Handoff events create continuity artifacts

### 6.2 Registry Synchronization

- Actor status changes sync with `lupo_actors` table
- Channel status syncs with channel management system
- Install state validates against agent configuration

### 6.3 Audit Trail

- All runtime files serve as audit trail
- LILITH can audit runtime state for compliance
- Violations are logged to both runtime and memory

## 7. Validation Rules

### 7.1 Structure Validation

- Directory structure MUST follow canonical layout
- All required files MUST exist for active actors
- JSON format MUST be valid and complete

### 7.2 Content Validation

- actor_id MUST match directory path
- channel_key MUST be consistent across files
- Timestamps MUST be in YYYYMMDDHHIISS format

### 7.3 Coordination Validation

- No mixed channel_keys in single task
- Dependencies MUST resolve before task completion
- Handoffs MUST be to valid, active actors

## 8. Examples

### 8.1 Simple Task Flow

1. Actor 1 starts task in channel "development"
2. Writes start entry to `tasks.jsonl`
3. Completes task, writes completion entry
4. Handoff to Actor 2, writes handoff entry
5. Actor 2 creates new task in same channel

### 8.2 Interrupt Handling

1. LILITH sends critical interrupt to Actor 1
2. Interrupt logged to `interrupts.jsonl`
3. Current task paused, interrupt processed
4. Resume original task or start new one

## 9. Cross-References

- Related: PRD 15 (Actor Identity)
- Related: PRD 50 (Agent Coordination Protocol)
- Related: PRD 53 (Runtime Guard)
- Related: MULTI_AGENT_COORDINATION_DOCTRINE.md
- Related: DATABASE_DOCTRINE.md

---

This output complies with Lupopedia Constitutional Root Rules.
