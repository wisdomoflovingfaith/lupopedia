---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: prd
  version_when_written: "4.0.93"
  file_path_from_root: "lupo-docs/prd/07_agents_faucets.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/prd/07_agents_faucets.md"
  last_modified_utc: "20260330163000"
  channel_id: 42
  thread_id: "prd-grouped"
  actor_id: 102
  actor_name: "HEPHAESTUS"
  delegation_chain: "hephaestus:root|lilith:audit"
  artifact_type: "prd"
  artifact_kind: "database_namespace"
  purpose: "PRD for AI agents, faucets, tool calls, and system integration"
  tags:
  - "prd"
  - "database"
  - "namespace"
  - "agents_faucets"
lupopedia.edges:
  outbound_edges:
    - to: "lupo-docs/database/lupopedia/tables/"
      type: references
      weight: 1.0
      reason: "Detailed table documentation"
    - to: "lupo-docs/prd/01_core_identity.md"
      type: references
      weight: 1.0
      reason: "Agents depend on identity system"
    - to: "lupo-docs/prd/12_api_integration.md"
      type: references
      weight: 1.0
      reason: "Agents use API endpoints"
lupopedia.footer:
  last_verified: "20260330163000"
  verified_by:
    actor_id: 102
    agent_name_identity: Cursor IDE Agent
  orchestrator: "hephaestus:root"
---

# PRD: AI Agents, Faucets, Tool Calls, and System Integration

## Overview

**Namespace Purpose:** Manages AI agents, faucet interfaces, tool execution, context snapshots, and system integration. This namespace enables Lupopedia's AI capabilities and external system interactions. 

**Agent Architecture**: Agents have a dual nature:
1. **File-based Definitions** (source of truth): Agent identity, skills, memory, tools, and soul are defined in `lupo-agents/{agent_id}/` directories with JSON files and prompts
2. **Database Runtime**: This namespace tracks agent execution, tool calls, context, and runtime state

**Primary Actors:** 
- AI agents (via lupo_agents + lupo-agents/ files)
- Faucet managers (via lupo_agent_faucets)
- Tool coordinators (via lupo_agent_tool_calls)
- Context managers (via lupo_agent_context_snapshots)
- Heartbeat monitors (via lupo_agent_heartbeats)
- IDE faucets (Cursor, Windsurf, etc. - interface to agents)

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
| `lupo_agents` | AI agent definitions and capabilities | `agent_id` | Central to AI system |
| `lupo_agent_faucets` | Faucet interface definitions for agents | `faucet_id` | Agent access interfaces |
| `lupo_agent_faucet_credentials` | Authentication credentials for faucets | `credential_id` | Secure faucet access |
| `lupo_agent_tool_calls` | Tool execution tracking and results | `tool_call_id` | Agent tool usage |
| `lupo_agent_context_snapshots` | Agent context state snapshots | `snapshot_id` | Agent state management |
| `lupo_agent_heartbeats` | Agent heartbeat and health monitoring | `heartbeat_id` | Agent health tracking |
| `lupo_agent_versions` | Agent version management | `version_id` | Agent deployment tracking |
| `lupo_agent_files` | Agent-specific file storage | `agent_file_id` | Agent resource management |
| `lupo_agent_dependencies` | Agent dependency tracking | `dependency_id` | Agent system requirements |
| `lupo_agent_external_events` | External event tracking for agents | `external_event_id` | Agent integration events |

## Table Details

### `lupo_agents`

**Purpose:** Defines AI agents with their capabilities, configurations, and operational parameters.

**Columns:**

| Column | Type | Nullable | Default | Description |
|--------|------|----------|---------|-------------|
| agent_id | BIGINT | NO | (application) | Primary key, generated via IdGenerator |
| agent_name | VARCHAR(255) | NO |  | Unique agent name |
| agent_type | VARCHAR(32) | NO | 'ai' | Type: ai, human, system |
| status | VARCHAR(32) | NO | 'inactive' | Status: active, inactive, busy, error |
| capabilities_json | JSON | NO |  | Agent capabilities definition |
| configuration_json | JSON | YES | NULL | Agent configuration |
| created_by_actor_id | BIGINT | NO |  | Foreign reference to lupo_actors |
| created_ymdhis | BIGINT | NO | (application) | UTC timestamp YYYYMMDDHHIISS |
| updated_ymdhis | BIGINT | NO | (application) | UTC timestamp YYYYMMDDHHIISS |
| last_heartbeat_ymdhis | BIGINT | YES | NULL | Last heartbeat timestamp |
| is_deleted | TINYINT | NO | 0 | Soft delete flag |
| deleted_ymdhis | BIGINT | YES | NULL | UTC timestamp when deleted |

**Indexes:**

| Index Name | Columns | Purpose |
|------------|---------|---------|
| idx_agents_name | agent_name, is_deleted | Unique agent lookup |
| idx_agents_type | agent_type, status, is_deleted | Type-based queries |
| idx_agents_status | status, last_heartbeat_ymdhis, is_deleted | Active agent monitoring |

### `lupo_agent_faucets`

**Purpose:** Defines faucet interfaces that agents use to interact with external systems.

**Columns:**

| Column | Type | Nullable | Default | Description |
|--------|------|----------|---------|-------------|
| faucet_id | BIGINT | NO | (application) | Primary key, generated via IdGenerator |
| agent_id | BIGINT | NO |  | Foreign reference to lupo_agents |
| faucet_name | VARCHAR(255) | NO |  | Unique faucet name |
| faucet_type | VARCHAR(32) | NO | 'api' | Type: api, cli, web, database |
| endpoint_url | VARCHAR(512) | YES | NULL | Faucet endpoint URL |
| authentication_type | VARCHAR(32) | NO | 'none' | Auth type: none, api_key, oauth, certificate |
| configuration_json | JSON | YES | NULL | Faucet configuration |
| created_by_actor_id | BIGINT | NO |  | Foreign reference to lupo_actors |
| created_ymdhis | BIGINT | NO | (application) | UTC timestamp YYYYMMDDHHIISS |
| updated_ymdhis | BIGINT | NO | (application) | UTC timestamp YYYYMMDDHHIISS |
| is_active | TINYINT | NO | 1 | Faucet active flag |
| is_deleted | TINYINT | NO | 0 | Soft delete flag |
| deleted_ymdhis | BIGINT | YES | NULL | UTC timestamp when deleted |

**Indexes:**

| Index Name | Columns | Purpose |
|------------|---------|---------|
| idx_faucets_agent | agent_id, is_active, is_deleted | Agent's faucets |
| idx_faucets_type | faucet_type, is_active, is_deleted | Type-based queries |

### `lupo_agent_tool_calls`

**Purpose:** Tracks tool execution by agents with parameters and results.

**Columns:**

| Column | Type | Nullable | Default | Description |
|--------|------|----------|---------|-------------|
| tool_call_id | BIGINT | NO | (application) | Primary key, generated via IdGenerator |
| agent_id | BIGINT | NO |  | Foreign reference to lupo_agents |
| tool_name | VARCHAR(255) | NO |  | Tool/function name |
| tool_type | VARCHAR(32) | NO | 'function' | Type: function, api, query, action |
| parameters_json | JSON | YES | NULL | Tool parameters |
| result_json | JSON | YES | NULL | Tool execution result |
| execution_time_ms | INT | YES | NULL | Execution time in milliseconds |
| status | VARCHAR(32) | NO | 'pending' | Status: pending, running, completed, failed |
| created_ymdhis | BIGINT | NO | (application) | UTC timestamp YYYYMMDDHHIISS |
| completed_ymdhis | BIGINT | YES | NULL | When execution completed |
| is_deleted | TINYINT | NO | 0 | Soft delete flag |
| deleted_ymdhis | BIGINT | YES | NULL | UTC timestamp when deleted |

**Indexes:**

| Index Name | Columns | Purpose |
|------------|---------|---------|
| idx_tool_calls_agent | agent_id, status, created_ymdhis, is_deleted | Agent's tool calls |
| idx_tool_calls_tool | tool_name, status, is_deleted | Tool-based queries |
| idx_tool_calls_completed | completed_ymdhis, is_deleted | Execution history |

## Cross-Namespace Dependencies

| Dependency | Direction | Purpose | Tables Involved |
|------------|------------|---------|------------------|
| 07_agents_faucets | This → 01_core_identity | Actor creation | Agents create actors |
| 07_agents_faucets | This → 09_federation_sync | Federation agents | Agents work across nodes |
| 07_agents_faucets | This → 12_api_integration | API access | Agents use APIs |
| 07_agents_faucets | File → 01_core_identity | Agent → Actor | File agents create database actors |
| 07_agents_faucets | File → 09_federation_sync | Agent Capabilities | Skills/tools/ memory sync |

## State Transitions

| State | Description | Transition To |
|--------|-------------|--------------|
| active | Agent operational | inactive, busy, error, deleted (soft) |
| inactive | Agent disabled | active, deleted (soft) |
| busy | Agent executing task | active, error, deleted (soft) |
| error | Agent in error state | active, inactive, deleted (soft) |
| deleted | Soft-deleted | N/A (can't be restored without explicit action) |

## Security & Privacy

Agent capabilities are validated before execution

Tool calls are logged and audited

Faucet credentials are encrypted at rest

Soft delete preserves agent history for compliance

## Testing Requirements

Unit tests for agent registration and configuration

Integration tests for tool execution and faucet interaction

Performance tests for agent heartbeat and monitoring

Soft delete behavior verification

## Usage Patterns

```php
// Register agent
$agentService = new AgentService();
$agentId = $agentService->register($agentName, $capabilities, $config);

// Create faucet for agent
$faucetService = new AgentFaucetService();
$faucetId = $faucetService->createFaucet($agentId, $faucetName, $type, $endpoint);

// Execute tool call
$toolCallService = new ToolCallService();
$callId = $toolCallService->executeTool($agentId, $toolName, $parameters);

// Record heartbeat
$heartbeatService = new AgentHeartbeatService();
$heartbeatId = $heartbeatService->record($agentId, $status, $metrics);
```

## File-Based Agent Definitions (Source of Truth)

**IMPORTANT**: While the database tracks runtime state, agent definitions are file-based in `lupo-agents/{agent_id}/`:

### Agent Directory Structure
```
lupo-agents/{agent_id}/
├── agent.json           # Core agent metadata
├── capabilities.json    # Agent capabilities list
├── properties.json     # Agent properties and constraints
├── system_prompt.txt   # Agent's system prompt
└── versions/           # Historical versions
```

### Core Agent Components

1. **Identity & Soul** (`agent.json`):
   - Agent ID, name, role, philosophy
   - Version tracking and verification
   - System prompt references

2. **Skills & Capabilities** (`capabilities.json`):
   - List of agent capabilities
   - Skill definitions and metadata
   - Version tracking for capabilities

3. **Properties & Constraints** (`properties.json`):
   - Agent personality and constraints
   - Required channels and permissions
   - Verification references

4. **Memory & Context** (system_prompt.txt + runtime):
   - Agent's system prompt defines baseline memory
   - Runtime memory stored in `lupo_actor_memory` tables
   - Context snapshots in `lupo_agent_context_snapshots`

### Agent → Actor Relationship

1. **Agent Definition**: File-based in `lupo-agents/` (immutable source of truth)
2. **Actor Instance**: Database record in `lupo_actors` (runtime instance)
3. **Actor Capabilities**: Database in `lupo_actor_skills`, `lupo_actor_tools`, etc.
4. **IDE Integration**: Faucets (Cursor, Windsurf, etc.) interface with agents

### Constitutional Rules for Agent Files

- **TOON PROTECTION**: Agent files are read-only reflections of definitions
- **NO DATABASE INFERENCE**: Never infer agent structure from database
- **FILE FIRST**: Agent definitions come from files, not database
- **RUNTIME ONLY**: Database stores only execution state and metrics
