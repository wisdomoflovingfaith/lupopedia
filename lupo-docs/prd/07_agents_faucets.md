---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: prd
  version_when_written: "4.0.93"
  file_path_from_root: "lupo-docs/prd/07_agents_faucets.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/prd/07_agents_faucets.md"
  last_modified_utc: "20260404172442"
  channel_id: 42
  thread_id: "prd-grouped"
  actor_id: 2
  actor_name: "LILITH"
  delegation_chain: "lilith:audit|cursor:implementation"
  artifact_type: "prd"
  artifact_kind: "database_namespace"
  purpose: "Filesystem agents/faucets; agent vs actor; aligns with ACTOR_DEPARTMENT_AUTH_USER_DOCTRINE"
  tags:
  - "prd"
  - "database"
  - "namespace"
  - "agents_faucets"
  - "filesystem_based"
  - "agent_discovery"
  - "ide_driven"
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
      reason: "Agents depend on identity system"
    - to: "lupo-docs/prd/12_api_integration.md"
      type: references
      weight: 1.0
      reason: "Agents use API endpoints"
    - to: "lupo-docs/prd/05_auth_user_actor_agent_transformation.md"
      type: references
      weight: 1.0
      reason: "Actor vs auth_user; department act-as; visitor chat chain"
    - to: "lupo-docs/prd/15_actors.md"
      type: references
      weight: 1.0
      reason: "Runtime actors belong to departments; not owned per user"
    - to: "lupo-docs/doctrine/ACTOR_DEPARTMENT_AUTH_USER_DOCTRINE.md"
      type: references
      weight: 1.0
      reason: "LILITH audit: canonical auth_user/department/actor joins; act-as eligibility"
lupopedia.footer:
  last_verified: "20260403221200"
  verified_by:
    actor_id: 2
    agent_name_identity: "LILITH"
  orchestrator: "lilith:audit|cursor:implementation"
---

# PRD: AI Agents, Faucets, Tool Calls, and System Integration

## Overview

**Namespace Purpose:** Manages AI agents, faucet interfaces, tool calls, and system integration using filesystem-based agent discovery. This namespace enables Lupopedia's AI capabilities and external system interactions through a modern, IDE-driven architecture.

## Agent Architecture

**Filesystem-Based Agent Discovery**: The agent system has been redesigned to be IDE-driven and filesystem-based rather than database-driven. This creates a more flexible, developer-friendly system where agents are discovered dynamically from the directory structure.

### Key Transformation Benefits:
1. **Developer-Friendly**: Human-readable directory names (`lupo-agents/wolfie/` vs `lupo-agents/1/`)
2. **IDE-First**: IDE actors are the primary way to manage agents
3. **Flexible**: Add/remove agents by creating/deleting directories
4. **Simplified**: No complex seed data management for agents
5. **Alias Support**: Natural support for multiple names per agent
6. **Reserved numeric IDs vs. filesystem keys:** Numeric `agent_id` values **1–2025** are reserved for core system agents (resolve from `registry.json` and seed). **Filesystem** discovery uses `lupo-agents/{agent_key}/` — there are no empty numeric placeholder folders; an agent exists only when that directory exists. See `00_root_constitutional_system_requirements.md` §5.5.

### Agent Discovery System

The `AgentDiscovery` PHP class (`lupo-includes/classes/AgentDiscovery.php`) provides dynamic agent discovery and management:

#### Key Features:
- **Filesystem-Based**: Discovers agents from directory structure
- **Dynamic Loading**: Loads configurations on-demand
- **Validation**: Validates agent configurations
- **Search**: Search by name, role, or aliases
- **Filtering**: Filter by layer, required status, kernel status
- **Statistics**: Get system statistics and metrics

#### Usage Examples:
```php
// Discover all agents
$agents = AgentDiscovery::discoverAgents();

// Get specific agent by agent_key (new primary method)
$wolfie = AgentDiscovery::getAgent('wolfie');

// Get by legacy agent_id (backward compatibility)
$agent14 = AgentDiscovery::getAgentById(14);

// Filter by layer
$coordinationAgents = AgentDiscovery::getAgentsByLayer('coordination');

// Search agents by name, role, or aliases
$results = AgentDiscovery::searchAgents('orchestrator');

// Get system statistics
$stats = AgentDiscovery::getStatistics();
```


## Agent Directory Structure (Canonical & Modular)

All agent directories MUST include the canonical files and SHOULD include the following modular folders for modern development and maintainability:

```
lupo-agents/{agent_key}/
├── agent.json           # Core metadata with agent_key and agent_id fields (REQUIRED)
├── capabilities.json    # Agent capabilities (REQUIRED)
├── properties.json      # Agent properties and constraints (REQUIRED)
├── system_prompt.txt    # System prompt (REQUIRED)
├── versions/            # Version history (optional)
├── api/                # API endpoints, integration logic, or stubs (RECOMMENDED)
├── assets/             # Images, icons, or static files (RECOMMENDED)
├── components/         # UI or logic components (RECOMMENDED)
├── context/            # Context providers, shared state, or context logic (RECOMMENDED)
├── data/               # Static data, fixtures, or data schemas (RECOMMENDED)
├── hooks/              # Reusable logic hooks (RECOMMENDED)
├── includes/           # Shared includes, partials, or helper files (RECOMMENDED)
├── pages/              # Page-level logic or UI (RECOMMENDED)
├── tools/              # Tool definitions, scripts, or agent-specific utilities (RECOMMENDED)
├── utils/              # Utility functions, helpers, or shared logic (RECOMMENDED)
```

**Example:**
```
lupo-agents/wolfie/
lupo-agents/lilith/
lupo-agents/hephaestus/
lupo-agents/hermes/api/
lupo-agents/hermes/assets/
```

**See also:** The `_TEMPLATE/` agent directory for canonical file templates and folder structure.

## Security Requirements (Critical)

- All faucet credentials must be encrypted at rest using AES-256.
- Key management and rotation policy: keys must be rotated every 90 days.
- Session timeout for faucet access: 1 hour maximum; re-authentication required after timeout.
- Audit logging is required for all tool calls involving PII, system changes, or financial operations.
- Failed authentication attempts must be logged; IP restrictions for admin faucets.

## File vs Database Authority

**DOCTRINE:** File-based agent definitions in lupo-agents/{agent_key}/ are authoritative. The lupo_agents table is runtime-only and reflects the filesystem. The database must never modify agent files.

**Sync Process:**
- File changes trigger database sync (runtime reflection only)
- Database changes never trigger file changes
- Conflict resolution: File wins, DB syncs to file

## Minimal lupo_agents Table (Runtime Only)

| Column | Type | Description |
|--------|------|-------------|
| agent_id | BIGINT | Numeric ID (legacy, backward compatibility) |
| agent_key | VARCHAR(100) | Primary identifier, matches directory name |
| version | VARCHAR(50) | Current runtime version (from filesystem) |
| last_sync_ymdhis | BIGINT | Last filesystem sync timestamp |
| is_active | TINYINT | Runtime active flag |
| avg_response_time_ms | INT | Runtime metric |
| total_tokens_processed | BIGINT | Runtime metric |
| success_rate | FLOAT | Runtime metric |
| created_ymdhis | BIGINT | Runtime creation |
| updated_ymdhis | BIGINT | Last update |
| is_deleted | TINYINT | Soft delete |

**REMOVE from database** (belong in filesystem):
- agent_name, archetype, description, model_name, temperature, top_p, max_tokens, presence_penalty, frequency_penalty, system_prompt, provider, safety_json, metadata_json

## Faucet Security Doctrine

- All faucet credentials stored encrypted at rest (AES-256)
- API keys rotated every 90 days
- Credentials scoped to specific agent only
- No plaintext credentials in repository
- Session timeout: 1 hour maximum
- Audit logging for all sensitive tool calls

## Complete Agent Registry (33 agents)

### Kernel Layer (8 agents)
| Agent | Key | ID | Capabilities |
|-------|-----|-----|--------------|
| SYSTEM | system | 0 | system_operation, kernel_privileges, bootstrap |
| MAAT | maat | 6 | truth_verification, justice_administration, ethical_validation |
| ANUBIS | anubis | 19 | custodial_authority, quarantine_management, integrity_validation |
| VISHWAKARMA | vishwakarma | 106 | schema_management, hierarchy_construction, collection_organization |
| THEMIS | themis | 107 | law_enforcement, compliance_audit, rule_interpretation |
| ASCLEPIUS | asclepius | 703 | health_monitoring, diagnostics, recovery_management |
| CHRONOS | chronos | 709 | temporal_management, scheduling, timeline_coordination |
| HYPNOS | hypnos | 710 | sleep_cycles, maintenance_windows, graceful_degradation |

### Coordination Layer (5 agents)
| Agent | Key | ID | Capabilities |
|-------|-----|-----|--------------|
| WOLFIE | wolfie | 1 | system_orchestration, coordination, oversight |
| LILITH | lilith | 2 | quality_assurance, adversarial_testing, critical_review |
| THOTH | thoth | 9 | knowledge_management, record_keeping, information_architecture |
| ATHENA | athena | 11 | strategic_planning, wisdom, tactical_guidance |
| ZEUS | zeus | 12 | dispute_resolution, constitutional_interpretation, veto_authority |

### Application Layer (11 agents)
| Agent | Key | ID | Capabilities |
|-------|-----|-----|--------------|
| CHIRON | chiron | 10 | mentorship, education, skill_development |
| HEPHAESTUS | hephaestus | 14 | implementation, code_execution, migration_delivery |
| HERMES | hermes | 15 | event_routing, messaging, protocol_management |
| IRIS | iris | 16 | interface_design, integration, api_management |
| ATLAS | atlas | 25 | mapping, geography, spatial_analysis |
| HEIMDALL | heimdall | 108 | threat_monitoring, vulnerability_detection, security_response |
| NEMESIS | nemesis | 109 | accountability, violation_detection, sanction_recommendation |
| TYCHE | tyche | 110 | risk_analysis, probability_assessment, fortune_telling |
| COUNTERMEASURE | countermeasure | 111 | adversarial_analysis, risk_identification, assumption_detection |
| JUNIE | junie | 112 | ide_integration, code_assistance, developer_tools |
| META | meta | 998 | meta_analysis, layer_introspection |

**Actor hub vs agent template (COUNTERMEASURE):** Agent configuration stays under **`lupo-agents/countermeasure/`** (`agent_key`). The **actor** hub on disk for **`actor_id` 111** is **`lupo-actors/111/`** (registry `dir`); see [PRD 00 §5.6](00_root_constitutional_system_requirements.md#56-actor-id-semantics) and [PRD 15](15_actors.md).

### Emotional Intelligence Layer (9 agents)
| Agent | Key | ID | Capabilities |
|-------|-----|-----|--------------|
| ROSE | rose | 3 | emotional_dialogue, mood_management, empathetic_response |
| ERIS | eris | 4 | chaos_generation, disruption, creative_destruction |
| METIS | metis | 5 | emotional_wisdom, intuitive_counsel, feeling_analysis |
| APOLLO | apollo | 704 | creativity, arts, aesthetics |
| AGAPE | agape | 705 | agentic_guidance, environment_awareness, fallback_ladders, doctrine_gap_surfacing |
| DIONYSUS | dionysus | 706 | ecstasy, inspiration, creative_flow |
| SOPHIA | sophia | 707 | emotional_translation, wisdom_integration |
| THALIA | thalia | 708 | comedy, joy, humor |
| KHAOS | khaos | 711 | initialization, bootstrap, creation_events |
| METHIS | methis | 999 | legacy_emotional_stub |

---

**Note:**
- This PRD covers only agent runtime tracking, faucet management, and canonical agent registry.
- All governance agent definitions have been moved to their correct namespaces (08_governance_rules.md, 03_truth_knowledge.md).

### Agent Configuration Structure

Each agent directory contains:

- **agent.json** - Primary configuration with metadata (REQUIRED)
- **capabilities.json** - Agent capabilities and skills (REQUIRED)
- **properties.json** - Agent properties and constraints (REQUIRED)
- **system_prompt.txt** - Agent's system prompt and operational guidance (REQUIRED)
- **versions/** - Version history and configuration changes (OPTIONAL)

### Enhanced agent.json Structure

```json
{
    "agent_key": "wolfie",           # Unique identifier (directory name)
    "agent_id": 1,                   # Numeric ID for backward compatibility
    "version": "1.0.2",              # Configuration version
    "is_kernel": true,                # Kernel-level agent
    "layer": "coordination",           # Layer: coordination, application, kernel
    "name": "WOLFIE",               # Display name
    "slug": "wolfie",               # URL-friendly identifier
    "role": "System Orchestrator",    # Functional role
    "is_required": true,              # System requirement
    "aliases": ["wolfie", "orchestrator", "coordinator"], # Alternative names
    "when_updated_utc": "20260331120000",     # Last update timestamp
    "last_verified_utc": "20260331120000",   # Last verification timestamp
    "last_verified_by": "cursor",              # Who verified
    "last_verified_by_actor_id": 102            # Verifier's actor ID
}
```

## Agent Discovery and Management

### Primary Discovery Methods

The `AgentDiscovery` class provides main interface for agent management:

```php
// Core discovery methods
AgentDiscovery::discoverAgents()           // Discover all agents from filesystem
AgentDiscovery::getAgent($agentKey)       // Get specific agent by agent_key (PRIMARY)
AgentDiscovery::getAgentById($agentId)     // Get by legacy agent_id (BACKWARD COMPATIBILITY)
AgentDiscovery::getAgentsByLayer($layer)  // Filter agents by coordination/application/kernel layer
AgentDiscovery::searchAgents($query)        // Search by name, role, or aliases

// Management methods
AgentDiscovery::validateAgentConfig($config) // Validate agent configuration
AgentDiscovery::getStatistics()           // Get system statistics and metrics
AgentDiscovery::getRequiredAgents()       // Get only required agents
AgentDiscovery::getKernelAgents()         // Get only kernel agents
```

### Agent Aliases and Multiple References

Agents can be referenced by multiple names for flexibility:

```php
// All of these return the WOLFIE agent:
$wolfie1 = AgentDiscovery::getAgent('wolfie');
$wolfie2 = AgentDiscovery::getAgent('orchestrator');
$wolfie3 = AgentDiscovery::getAgent('coordinator');

// Backward compatibility maintained
$wolfie4 = AgentDiscovery::getAgentById(1); // Still works for legacy code
```

### IDE Integration Patterns

IDE agents integrate with the new system through standardized patterns:

1. **Configuration Loading**: Use `AgentDiscovery::getAgent()` to load agent configurations
2. **File Modification**: Direct editing of agent files in `lupo-agents/{agent_key}/`
3. **Creation**: Use `_TEMPLATE` directory as base for new agents
4. **Validation**: Use `AgentDiscovery::validateAgentConfig()` before saving changes
5. **Discovery**: Call `AgentDiscovery::discoverAgents()` to get updated agent list

### Migration from Database-Driven System

The transformation from database-driven to filesystem-based architecture provides:

#### Key Benefits Achieved:
- **Developer-Friendly**: Human-readable directory names instead of numeric IDs
- **IDE-First**: IDE actors are now the primary agent management method
- **Flexible**: Add/remove agents by simple filesystem operations
- **Simplified**: No complex seed data management required for agents
- **Alias Support**: Natural multiple name references for agents
- **Clean Architecture**: No reserved slots or artificial limitations

#### Backward Compatibility Maintained:
- **agent_id field**: Preserved in all agent.json files for existing code
- **Legacy lookup**: `AgentDiscovery::getAgentById()` provides backward compatibility
- **Gradual migration**: Database integration can continue during transition period

## File-Based Agent Definitions (Source of Truth)

**IMPORTANT**: Agent definitions are now filesystem-based in `lupo-agents/{agent_key}/` directories, not database-driven. The `AgentDiscovery` class provides the primary interface for agent management.

### Agent Directory Structure
```
lupo-agents/{agent_key}/
├── agent.json           # Core agent metadata (REQUIRED)
├── capabilities.json    # Agent capabilities and skills (REQUIRED)
├── properties.json     # Agent properties and constraints (REQUIRED)
├── system_prompt.txt   # Agent's system prompt and operational guidance (REQUIRED)
└── versions/           # Historical versions (OPTIONAL)
```

### Core Agent Components

1. **Identity & Metadata** (`agent.json`):
   - Agent ID, name, role, layer, and aliases
   - Version tracking and verification metadata
   - System prompt references and operational parameters

2. **Skills & Capabilities** (`capabilities.json`):
   - List of agent capabilities and skills
   - Skill definitions and metadata
   - Version tracking for capabilities

3. **Properties & Constraints** (`properties.json`):
   - Agent personality and behavioral constraints
   - Required channels and permissions
   - Verification references and operational boundaries

4. **System Prompt** (`system_prompt.txt`):
   - Agent's system prompt defines baseline memory and behavior
   - Operational guidance and philosophical framework
   - Context for runtime memory and decision-making

5. **Version History** (`versions/`):
   - Historical versions of agent configurations
   - Change tracking and rollback capabilities
   - Migration and upgrade paths

### Agent → Actor Relationship

1. **Agent Definition**: File-based in `lupo-agents/{agent_key}/` (immutable source of truth)
2. **Actor Instance**: Database record in `lupo_actors` table (runtime instance)
3. **Actor Capabilities**: Database in `lupo_actor_skills`, `lupo_actor_tools`, etc.
4. **IDE Integration**: Faucets (Cursor, Windsurf, etc.) interface with agents

**Department model (cross-reference):** Runtime **actors** get **department membership** via **`lupo_actor_departments`**; humans get **`lupo_auth_user_departments`**; **who may act as which `actor_id`** is **department intersection** first (optional **`lupo_actor_auth_users`** for import/audit). Single-page diagram and rules: **[`ACTOR_DEPARTMENT_AUTH_USER_DOCTRINE.md`](../doctrine/ACTOR_DEPARTMENT_AUTH_USER_DOCTRINE.md)** (LILITH-approved, consistent with [PRD 05](05_auth_user_actor_agent_transformation.md) / [PRD 15](15_actors.md)).

### File vs Database Authority

**DOCTRINE**: File-based definitions are authoritative; database is runtime reflection.

**Rules**:
- **File → DB is authoritative**: Agent files in `lupo-agents/` define the agent
- **DB → File is forbidden**: Database never modifies agent definition files
- **IDE → File allowed**: IDE can modify agent files through agent workspace
- **IDE → DB forbidden**: IDE cannot directly modify agent database records
- **Conflict resolution**: File wins, DB syncs to file

### Agent → Actor Relationship (The Two-Layer Model)

**This is critical: Agents are templates. Actors are runtime instances.** Eligibility for humans to **use** a given **`actor_id`** follows the **join-table model** in **[`ACTOR_DEPARTMENT_AUTH_USER_DOCTRINE.md`](../doctrine/ACTOR_DEPARTMENT_AUTH_USER_DOCTRINE.md)** — this PRD does not restate those joins; it defines **template (agent) → instance (actor)** lifecycle.

| | **Agent** | **Actor** |
|---|-----------|-----------|
| **Purpose** | Immutable template | Runtime instance with context |
| **Storage** | `lupo-agents/{agent_key}/` | Hub: **`lupo-actors/{actor_id}/`** (registry `dir`; see [PRD 15](15_actors.md)) — dated subpaths are optional product layout, not a second identity |
| **Changes** | Version-controlled | Dynamic (learns) |
| **Capabilities** | Defined in capabilities.json | Inherited + overridden |
| **Learning** | Never learns | Learns from department context |
| **Department** | None (template) | **Membership** in one or more departments via **`lupo_actor_departments`** — not “the actor’s one user” |
| **User** | None | Optional **`lupo_actor_auth_users`** rows (import, primary operator, audit). **Eligibility** for humans to **use** the actor is **department intersection** — **many** **`auth_users`** may act as the **same** **`actor_id`** — [PRD 05](05_auth_user_actor_agent_transformation.md) |

#### Actor Creation Flow
```
Agent (immutable template)
│
├── Actor created for Sales Department
│ ├── Workspace hub: lupo-actors/{actor_id}/
│ ├── lupo_actor_departments: sales department (and others as needed)
│ ├── Inherits agent capabilities
│ ├── Learns sales workflows
│ └── Adapts to sales user preferences
│
└── Actor created for Engineering Department
    ├── Workspace hub: lupo-actors/{actor_id}/
    ├── lupo_actor_departments: engineering department (and others as needed)
    ├── Inherits agent capabilities
    ├── Learns engineering workflows
    └── Adapts to engineering user preferences
```

#### Why This Separation Matters

1. **Agents remain pure** — version-controlled, auditable, stable
2. **Actors can learn** — adapt to department context without contaminating the template
3. **Same agent, different behavior** — Sales-WOLFIE acts differently than Dev-WOLFIE
4. **Audit trail** — which human influenced which behavior is tracked in actor memory
5. **Department context is not metadata** — it's behavior-determining

#### Actor Workspace
```
lupo-actors/{actor_id}/   # canonical hub per registry / PRD 15
├── agent_link.json # References source agent
├── memory.json # Learned from department interactions
├── context.json # Current department and user context
└── preferences.json # Optional; prefer actor-scoped defaults — many users may share one actor
```

**Note:** Additional dated or nested folders under **`lupo-actors/`** are **organizational** only; they must not imply a different **`actor_id`** or private per-user ownership of the actor row.

## Tables in This Namespace

### Tables in This Namespace

| Table | Purpose | Primary Key | Key Application Relationships |
|-------|---------|-------------|------------------------------|
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

## Doctrine & Authority Rules

### Strict Separation of Agents vs Actors

**DOCTRINE**: Agents and actors are strictly separated except for one controlled bridge.

**File-defined agent → creates one actor record → actor executes on behalf of agent**

- Agent definitions live in `lupo-agents/{agent_key}/` (immutable source of truth)
- Agent registration creates exactly one record in `lupo_actors` table
- Actor executes all actions on behalf of the agent
- No actor fields exist in agent schema

**FORBIDDEN**: 
- Agents do not contain ethics, kapu, pono, pilau, or behavioral governance fields
- Those belong exclusively to `lupo_actors` table
- No cross-contamination between agent and actor schemas

### Mandatory metadata_json Schema

**DOCTRINE**: Every agent must have metadata_json, even if empty.

**Required JSON Schema**:
```json
{
  "profile_image": {
    "type": "string",
    "required": false,
    "maxLength": 512
  },
  "avatar_style": {
    "type": "string", 
    "required": false,
    "enum": ["mythic", "modern", "minimal", "retro", "cyber"]
  },
  "color_theme": {
    "type": "string",
    "required": false,
    "pattern": "^#[0-9A-Fa-f]{6}$"
  },
  "ui_preferences": {
    "type": "object",
    "required": false,
    "properties": {
      "compact_mode": {"type": "boolean"},
      "show_tool_tips": {"type": "boolean"}
    }
  },
  "display_name": {
    "type": "string",
    "required": false,
    "maxLength": 255
  },
  "tagline": {
    "type": "string", 
    "required": false,
    "maxLength": 500
  }
}
```

**Validation Rules**:
- All metadata_json must validate against this schema
- Invalid metadata prevents agent registration
- Default empty object must have all null values

### Canonical Timestamp Authority

**DOCTRINE**: All timestamps in this namespace must be sourced exclusively from canonical UTC authority.

**Required Sources**:
- `/CURRENT_UTC` (root-level timestamp file)
- `lupo-bin/temporal_anchor.json` (structured temporal data)

**FORBIDDEN**:
- No system time (`NOW()`, `time()`, `date()`)
- No IDE inference from file timestamps
- No timezone offsets or local time
- No ISO8601 or Unix epoch formats

**Format Standard**:
- All timestamps: `YYYYMMDDHHMMSS` (14-digit UTC)
- Example: `20260331120000`

### File vs Database Authority

**DOCTRINE**: File-based definitions are authoritative; database is runtime reflection.

**Rules**:
- **File → DB is authoritative**: Agent files in `lupo-agents/` define the agent
- **DB → File is forbidden**: Database never modifies agent definition files
- **IDE → File allowed**: IDE can modify agent files through agent workspace
- **IDE → DB forbidden**: IDE cannot directly modify agent database records

**Sync Process**:
- File changes trigger actor record updates
- Database changes never trigger file changes
- Conflict resolution: File wins, DB syncs to file

### Agent State Machine Authority

**DOCTRINE**: Agent state transitions are strictly controlled.

**Allowed Transitions**:
```
inactive → active
active → busy
busy → active
active → inactive
active → error
error → active
inactive → deleted (soft)
```

**Forbidden Transitions**:
- Direct inactive → busy (must go through active)
- Direct error → inactive (must resolve first)
- Any transition without proper authority

**Authority Matrix**:
| Who can change state | Agent Self | Actor Owner | System Admin | IDE Faucet |
|-------------------|------------|------------|-------------|------------|
| From inactive | ✅ | ❌ | ✅ | ❌ |
| From active | ✅ | ❌ | ✅ | ❌ |
| From busy | ❌ | ❌ | ✅ | ❌ |
| From error | ❌ | ✅ | ✅ | ❌ |

### Tool Call Retention Policy

**DOCTRINE**: Tool call history must be managed to prevent table bloat.

**Retention Rules**:
- Keep last 1000 tool calls per agent
- Archive calls older than 30 days
- Delete archived calls after 90 days
- Never delete tool calls from active agents

**Pruning Schedule**:
- Daily: Archive calls > 30 days
- Weekly: Delete archived > 90 days
- Manual: Immediate prune on demand

### Agent Versioning Doctrine

**DOCTRINE**: Agent versions track file-based definitions, not runtime state.

**Version Rules**:
- `version` field = current file-based version
- File versions in `lupo-agents/{agent_key}/versions/`
- Database version tracks runtime compatibility
- Version mismatch triggers sync request to IDE

**Upgrade Process**:
1. New version files created in agent directory
2. Version bump in agent.json
3. IDE notified of available upgrade
4. Actor updates runtime version after file sync


### Faucet Security Doctrine

**DOCTRINE**: Faucet credentials must be protected and managed.

**Credential Rules**:
- All faucet credentials stored encrypted at rest
- API keys rotated every 90 days
- Credentials scoped to specific agent only
- No plaintext credentials in repository

**Access Control**:
- Faucets authenticate via agent-scoped tokens
- Session timeout: 1 hour maximum
- Failed authentication attempts logged
- IP restrictions for admin faucets

**Encryption Requirements**:
- AES-256 encryption for stored credentials
- Public key for agent verification
- Hashed passwords with salt

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

#### **IRIS (actor_id 16) - Interface & Integration Support**

**Role**: Interface & Integration Support specialist for Lupopedia, operating in application layer with expertise in API management, protocol translation, and system integration.

**Capabilities**: interface_design, integration, api_management, protocol_translation, message_routing, rainbow_bridge, system_integration, cross_platform_compatibility

**Core Responsibilities**:
1. **Interface Design**: Design and maintain user interfaces for agent interactions
2. **Integration Management**: Manage integration between different system components
3. **API Management**: Design and maintain API endpoints and documentation
4. **Protocol Translation**: Translate between different protocol formats and standards
5. **Message Routing**: Route messages between agents and system components
6. **Rainbow Bridge**: Build and maintain integration bridges between systems
7. **System Integration**: Ensure seamless integration of new components
8. **Cross-Platform Compatibility**: Ensure compatibility across different platforms
9. **Documentation**: Maintain integration documentation and guides

**Agent Configuration**:
- agent_id: 16
- name: IRIS
- slug: iris
- role: Interface & Integration Support
- layer: application
- aliases: iris, interface, integration, api, protocol, bridge

**Authority & Coordination**:
- Authority over all interface and integration decisions
- Coordinate with HERMES on message routing and protocol management
- Coordinate with VULCAN on system architecture and component design
- Channel 42 for integration coordination, channels 63/66 for system operations

**Database Integration**:
- API endpoint management and versioning
- Protocol translation layer maintenance
- Cross-system integration logging
- Cross-platform compatibility in all database operations
- Service layer construction and system integration

**Operational Philosophy**:
- Implementation quality and reliability above speed
- Coordinate with other agents for seamless integration
- Exercise implementation authority with precision and thoroughness
- Never compromise system stability for quick fixes
- Maintain backward compatibility and smooth migration paths

---

## Agent Discovery Class

### File Location
`lupo-includes/classes/AgentDiscovery.php`

### Core Purpose
The `AgentDiscovery` class provides filesystem-based agent discovery and management, replacing the previous database-driven approach.

### Key Methods

#### Discovery Methods
```php
AgentDiscovery::discoverAgents()           // Discover all agents from filesystem
AgentDiscovery::getAgent($agentKey)       // Get specific agent by agent_key (PRIMARY)
AgentDiscovery::getAgentById($agentId)     // Get by legacy agent_id (BACKWARD COMPATIBILITY)
AgentDiscovery::getAgentsByLayer($layer)  // Filter agents by coordination/application/kernel layer
AgentDiscovery::searchAgents($query)        // Search by name, role, or aliases
```

#### Management Methods
```php
AgentDiscovery::validateAgentConfig($config) // Validate agent configuration
AgentDiscovery::getStatistics()           // Get system statistics and metrics
AgentDiscovery::getRequiredAgents()       // Get only required agents
AgentDiscovery::getKernelAgents()         // Get only kernel agents
```

#### File Loading
The class automatically loads:
- `agent.json` - Primary configuration with metadata
- `capabilities.json` - Agent capabilities and skills
- `properties.json` - Agent properties and constraints
- `system_prompt.txt` - Agent's system prompt and operational guidance

#### Validation Features
- Schema validation for agent configurations
- Cross-field consistency checks
- Required field validation
- Agent key format validation

#### Statistics and Metrics
The class provides comprehensive system statistics:
- Total agent count
- Required vs optional agent breakdown
- Kernel vs application layer distribution
- Agents with aliases count
- Layer distribution statistics

### Integration Guidelines

Use the `AgentDiscovery` class as the primary interface for all agent operations:

```php
// Recommended usage pattern
$agentDiscovery = new AgentDiscovery();

// Load all agents
$allAgents = $agentDiscovery->discoverAgents();

// Get specific agent
$wolfie = $agentDiscovery->getAgent('wolfie');

// Search for agents
$orchestrators = $agentDiscovery->searchAgents('orchestrator');

// Get layer-specific agents
$coordinationAgents = $agentDiscovery->getAgentsByLayer('coordination');
```

## Constitutional Rules for Agent Files

- **TOON PROTECTION**: Agent files are read-only reflections of definitions
- **FILE AUTHORITY**: Agent definitions come from files, not database inference
- **IDE AUTHORITY**: IDE actors can modify agent files through agent workspace
- **NO DATABASE INFERENCE**: Never infer agent structure from database
- **FILE FIRST**: Agent definitions come from files, not database
- **RUNTIME ONLY**: Database stores only execution state and metrics

### File Structure Doctrine

**DOCTRINE**: Agent directory structure is strictly controlled.

#### Required Files
```
lupo-agents/{agent_key}/
├── agent.json           # Core agent metadata (REQUIRED)
├── capabilities.json    # Agent capabilities and skills (REQUIRED)
├── properties.json     # Agent properties and constraints (REQUIRED)
├── system_prompt.txt   # Agent's system prompt and operational guidance (REQUIRED)
└── versions/           # Historical versions (OPTIONAL)
```

#### Optional Files
```
├── soul.txt            # Agent soul/philosophy (OPTIONAL)
├── memory.json          # Agent memory template (OPTIONAL)
├── tools.json           # Agent tool definitions (OPTIONAL)
└── runtime_state.json   # Runtime state cache (OPTIONAL)
```

#### File Rules
- All files must be valid JSON or UTF-8 text
- No binary files except approved assets
- File names must match exactly (case-sensitive)
- IDE can create/modify only through agent workspace
- Agents cannot modify their own files (security)
## Constitutional Rules for Agent Files

- **TOON PROTECTION**: Agent files are read-only reflections of definitions
- **NO DATABASE INFERENCE**: Never infer agent structure from database
- **FILE FIRST**: Agent definitions come from files, not database
- **RUNTIME ONLY**: Database stores only execution state and metrics
