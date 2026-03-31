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
|#### `lupo_agents` - AI Agent Definitions
**Purpose:** Defines AI agents with their capabilities, configurations, and operational parameters.

**Table Details:**

| Column | Type | Nullable | Default | Description |
|--------|------|----------|---------|-------------|
| agent_id | BIGINT | NO | (application) | Primary key, generated via IdGenerator |
| agent_key | VARCHAR(100) | NO |  | Unique agent key |
| agent_name | VARCHAR(150) | NO |  | Agent name |
| archetype | VARCHAR(150) | YES | NULL | Agent archetype |
| description | TEXT | YES | NULL | Agent description |
| version | VARCHAR(50) | NO | '1.0' | Agent version |
| model_name | VARCHAR(100) | YES | NULL | Model name |
| is_global_authority | TINYINT | NO | '0' | Global authority flag |
| is_internal_only | TINYINT | NO | '0' | Internal only flag |
| created_ymdhis | BIGINT | NO | 0 | UTC timestamp YYYYMMDDHHIISS |
| updated_ymdhis | BIGINT | YES | NULL | UTC timestamp YYYYMMDDHHIISS |
| is_deleted | TINYINT | NO | '0' | Soft delete flag |
| deleted_ymdhis | BIGINT | YES | NULL | UTC timestamp when deleted |
| avg_response_time_ms | INT | NO | '0' | Average response time |
| total_tokens_processed | BIGINT | NO | '0' | Total tokens processed |
| success_rate | FLOAT | NO | '1' | Success rate |
| cost_per_1k_tokens | DECIMAL(10,4) | NO | '0.0000' | Cost per 1k tokens |
| temperature | FLOAT | NO | '0.7' | Temperature setting |
| top_p | FLOAT | NO | '1' | Top-p setting |
| max_tokens | INT | NO | '2048' | Max tokens |
| presence_penalty | FLOAT | NO | '0' | Presence penalty |
| frequency_penalty | FLOAT | NO | '0' | Frequency penalty |
| system_prompt | TEXT | YES | NULL | System prompt |
| provider | VARCHAR(50) | NO | 'openai' | Provider |
| api_key_id | BIGINT | YES | NULL | API key ID |
| timeout_ms | INT | NO | '20000' | Timeout in ms |
| safety_json | JSON | YES | NULL | Safety configuration |
| response_format | VARCHAR(50) | YES | NULL | Response format |
| metadata_json | JSON | YES | NULL | Agent UI, avatar, and configuration metadata |

**Indexes:**
- `idx_agents_name` ON `agent_name`, `is_deleted` - Unique agent lookup
- `idx_agents_key` ON `agent_key`, `is_deleted` - Agent key lookup
- `idx_agents_api_key_id` ON `api_key_id`, `is_deleted` - API key queries
- `idx_agents_is_global_authority` ON `is_global_authority`, `is_deleted` - Authority queries
- `idx_agents_created_ymdhis` ON `created_ymdhis`, `is_deleted` - Created timestamp queries
- `idx_agents_updated_ymdhis` ON `updated_ymdhis`, `is_deleted` - Updated timestamp queries

### Agent Metadata (metadata_json)
Agents store presentation and configuration metadata in metadata_json field. This field contains JSON data for UI customization, avatar settings, and agent-specific configuration that doesn't belong in the core operational fields.

**Examples:**
```json
{
  "profile_image": "/assets/agents/wolfie.png",
  "avatar_style": "mythic",
  "color_theme": "#4455aa",
  "ui_preferences": {
    "compact_mode": true,
    "show_tool_tips": false
  },
  "display_name": "WOLFIE",
  "tagline": "System Orchestrator"
}
```

**Common Metadata Fields:**
- `profile_image`: Path to agent's avatar/profile image
- `avatar_style`: Visual style theme (mythic, modern, minimal, etc.)
- `color_theme`: Primary color for UI elements
- `ui_preferences`: Agent-specific UI configuration
- `display_name`: Human-readable display name (may differ from agent_name)
- `tagline`: Short description or motto

**Important Notes:**
- Actor-ethics fields (pono, pilau, kapakai, kapu) belong only to lupo_actors table
- Agents use metadata_json for UI and presentation attributes
- Metadata is optional and defaults to NULL if not specified

---

#### `lupo_anubis_log` - ANUBIS Operations Log
**Purpose:** Comprehensive logging for ANUBIS custodial operations, integrity checks, and quarantine actions.

**Table Details:**

| Column | Type | Nullable | Default | Description |
|--------|------|----------|---------|-------------|
| anubis_log_id | BIGINT | NO | (application) | Primary key, generated via IdGenerator |
| event_type | VARCHAR(64) | NO |  | Type of ANUBIS operation |
| severity | VARCHAR(20) | NO | 'normal' | Event severity level |
| table_name | VARCHAR(255) | NO |  | Target table name |
| record_id | BIGINT | NO |  | Related record ID |
| details_json | JSON | YES | NULL | Event details |
| created_ymdhis | BIGINT | NO | (application) | UTC timestamp YYYYMMDDHHIISS |
| operator_actor_id | BIGINT | NO |  | Actor who performed operation |

**Indexes:**
- `idx_anubis_log_status` ON `status`, `created_ymdhis` - Status-based queries
- `idx_anubis_log_created` ON `created_ymdhis` - Chronological queries

**Event Types:**
- `quarantine`: File moved to quarantine
- `orphaned`: Orphaned record detected and resolved
- `redirect`: URL or table redirection
- `integrity_check`: System integrity validation
- `threshold_breach`: Threshold violation detected
- `custody_transfer`: Record custody change
- `scan_completion`: Security scan finished
- `policy_violation`: Policy rule violation

**Severity Levels:**
- `critical`: Immediate attention required
- `warning`: Monitor closely
- `normal`: Routine operation
- `info`: Informational event

---

#### `lupo_anubis_events` - ANUBIS Event Tracking
**Purpose:** Tracks significant ANUBIS events and system state changes for audit trail and monitoring.

**Table Details:**

| Column | Type | Nullable | Default | Description |
|--------|------|----------|---------|-------------|
| anubis_event_id | BIGINT | NO | (application) | Primary key, generated via IdGenerator |
| event_type | VARCHAR(64) | NO |  | Type of ANUBIS event |
| table_name | VARCHAR(255) | NO |  | Target table name |
| old_id | BIGINT | NO |  | Previous record ID |
| new_id | BIGINT | NO |  | New record ID |
| details_json | JSON | YES | NULL | Event details |
| created_ymdhis | BIGINT | NO | (application) | UTC timestamp YYYYMMDDHHIISS |
| operator_actor_id | BIGINT | NO |  | Actor who performed operation |

**Indexes:**
- `idx_anubis_events_type` ON `event_type`, `created_ymdhis` - Event type queries
- `idx_anubis_events_table` ON `table_name`, `created_ymdhis` - Table-specific queries
- `idx_anubis_events_old_id` ON `old_id`, `created_ymdhis` - History tracking

**Event Types:**
- `custody_change`: Record custody transfer
- `threshold_violation`: Threshold violation detected
- `policy_update`: Security policy modification
- `system_alert`: System-level alert
- `configuration_change`: ANUBIS configuration update
- `orphan_resolution`: Orphaned record resolved
- `quarantine_action`: Quarantine operation
- `integrity_validation`: System integrity check
- `recovery_attempt`: Recovery operation attempt
- `redirect_created`: URL/table redirection
- `scan_initiated`: Security scan started
- `scan_completed`: Security scan finished

---

#### `lupo_anubis_redirects` - ANUBIS URL/Table Redirects
**Purpose:** Manages URL and table redirections for content migration, access control, and system restructuring.

**Table Details:**

| Column | Type | Nullable | Default | Description |
|--------|------|----------|---------|-------------|
| anubis_redirect_id | BIGINT | NO | (application) | Primary key, generated via IdGenerator |
| table_name | VARCHAR(255) | NO |  | Target table name |
| old_id | BIGINT | NO |  | Previous record ID |
| new_id | BIGINT | NO |  | New record ID |
| redirect_type | VARCHAR(64) | NO |  | Type of redirection |
| created_ymdhis | BIGINT | NO | (application) | UTC timestamp YYYYMMDDHHIISS |
| operator_actor_id | BIGINT | NO |  | Actor who performed operation |

**Redirect Types:**
- `url_redirect`: URL redirection
- `table_redirect`: Table name change
- `content_migration`: Content moved to new location
- `access_control`: Access permission change
- `schema_update`: Database schema modification
- `namespace_change`: Namespace reorganization

---

#### `lupo_anubis_queue` - ANUBIS Processing Queue
**Purpose:** Queue system for ANUBIS file processing, quarantine, and recovery operations.

**Table Details:**

| Column | Type | Nullable | Default | Description |
|--------|------|----------|---------|-------------|
| queue_id | BIGINT | NO | (application) | Primary key, generated via IdGenerator |
| file_path | VARCHAR(512) | NO |  | File path for processing |
| file_hash | VARCHAR(64) | DEFAULT NULL | File integrity hash |
| status | VARCHAR(32) | NO | 'pending' | Processing status |
| priority | TINYINT | NO | 5 | Priority level (1-10) |
| created_ymdhis | BIGINT | NO | (application) | UTC timestamp YYYYMMDDHHIISS |
| operator_actor_id | BIGINT | NO |  | Actor who queued operation |

**Status Values:**
- `pending`: Queued for processing
- `processing`: Currently being processed
- `completed`: Successfully processed
- `failed`: Processing failed
- `quarantined`: File quarantined
- `retry`: Scheduled for retry

**Priority Levels:**
- `1`: Critical (immediate processing)
- `2-3`: High priority
- `4-6`: Normal priority
- `7-8`: Low priority
- `9-10`: Background processing

**Indexes:**
- `idx_anubis_queue_status_priority` ON `status`, `priority` - Priority-based queries
- `idx_anubis_queue_uniq_file_hash` ON `file_hash` - Duplicate prevention

---

#### `lupo_anubis_processing_log` - ANUBIS Processing Log
**Purpose:** Detailed audit trail of ANUBIS queue processing with timing, results, and error tracking.

**Table Details:**

| Column | Type | Nullable | Default | Description |
|--------|------|----------|---------|-------------|
| log_id | BIGINT | NO | (application) | Primary key, generated via IdGenerator |
| queue_id | BIGINT | NO |  | Foreign key to anubis_queue |
| file_path | VARCHAR(512) | NO |  | File path being processed |
| processing_time_ms | INT | YES | NULL | Processing duration in milliseconds |
| result_json | JSON | YES | NULL | Processing result details |
| created_ymdhis | BIGINT | NO | (application) | UTC timestamp YYYYMMDDHHIISS |

**Indexes:**
- `idx_anubis_processing_log_created` ON `created_ymdhis` - Chronological queries
- `idx_anubis_processing_log_queue` ON `queue_id` - Queue-based queries

**Processing Result Types:**
- `success`: File processed successfully
- `quarantined`: File moved to quarantine
- `failed`: Processing failed with error
- `retry`: Scheduled for retry attempt
- `recovered`: File recovered from quarantine

---

#### `lupo_anubis_recovery_attempts` - ANUBIS Recovery Attempts
**Purpose:** Tracks retry attempts for quarantined files with exponential backoff and success/failure tracking.

**Table Details:**

| Column | Type | Nullable | Default | Description |
|--------|------|----------|---------|-------------|
| attempt_id | BIGINT | NO | (application) | Primary key, generated via IdGenerator |
| queue_id | BIGINT | NO |  | Foreign key to anubis_queue |
| attempt_number | TINYINT | NO |  | Attempt sequence number (1-5) |
| created_ymdhis | BIGINT | NO | (application) | UTC timestamp YYYYMMDDHHIISS |

**Retry Strategy:**
- Attempt 1: Immediate retry (30 seconds)
- Attempt 2: 5 minute backoff
- Attempt 3: 15 minute backoff
- Attempt 4: 1 hour backoff
- Attempt 5: 24 hour backoff

**Indexes:**
- `idx_anubis_recovery_attempts_queue_attempt` ON `queue_id`, `attempt_number` - Attempt tracking

---

#### `lupo_anubis_quarantine` - ANUBIS File Quarantine
**Purpose:** Secure isolation of suspicious or potentially harmful files with audit trail and recovery capabilities.

**Table Details:**

| Column | Type | Nullable | Default | Description |
|--------|------|----------|---------|-------------|
| quarantine_id | BIGINT | NO | (application) | Primary key, generated via IdGenerator |
| queue_id | BIGINT | NO |  | Foreign key to anubis_queue |
| file_path | VARCHAR(512) | NO |  | Original file path |
| quarantine_reason | VARCHAR(255) | NO |  | Reason for quarantine |
| created_ymdhis | BIGINT | NO | (application) | UTC timestamp YYYYMMDDHHIISS |

**Quarantine Reasons:**
- `malicious_content`: Detected malware or harmful content
- `policy_violation`: Violates security policies
- `integrity_failure`: File integrity check failed
- `unauthorized_access`: Access from unauthorized source
- `suspicious_pattern`: Matches known threat patterns
- `corruption_detected`: File corruption detected

**Indexes:**
- `idx_anubis_quarantine_queue` ON `queue_id` - Queue-based queries

---

#### `lupo_anubis_operations` - ANUBIS Operations Audit
**Purpose:** Comprehensive audit trail of all ANUBIS custodial operations for compliance and system integrity monitoring.

**Table Details:**

| Column | Type | Nullable | Default | Description |
|--------|------|----------|---------|-------------|
| operation_id | BIGINT | NO | (application) | Primary key, generated via IdGenerator |
| operation_type | VARCHAR(64) | NO |  | Type of ANUBIS operation |
| target_type | VARCHAR(64) | NO |  | Target of operation |
| details_json | JSON | YES | NULL | Operation details |
| created_ymdhis | BIGINT | NO | (application) | UTC timestamp YYYYMMDDHHIISS |
| operator_actor_id | BIGINT | NO |  | Actor who performed operation |

**Operation Types:**
- `quarantine_file`: File quarantined
- `release_file`: File released from quarantine
- `delete_file`: File permanently deleted
- `modify_threshold`: Security threshold modified
- `update_policy`: Security policy updated
- `scan_operation`: Security scan performed
- `orphan_resolution`: Orphaned record resolved
- `integrity_check`: System integrity validated
- `custody_transfer`: Record custody change
- `redirect_created`: Redirection established
- `configuration_update`: ANUBIS configuration changed

---

#### ANUBIS Agent Configuration
**Core Identity:**
- **Name**: ANUBIS (actor_id: 19)
- **Role**: Custodian & Integrity Guardian
- **Layer**: kernel (required system component)
- **Unique Capability**: The only persona with comprehensive custodial authority and quarantine powers

**Primary Responsibilities:**
1. **Custodial Authority**: Maintain custody of all system records and data
2. **Integrity Validation**: Validate system integrity and detect corruption
3. **Quarantine Management**: Manage quarantine of suspicious files
4. **Orphan Resolution**: Identify and resolve orphaned records
5. **Threshold Enforcement**: Enforce security thresholds and policies
6. **Policy Management**: Create and maintain security policies
7. **Audit Trail**: Comprehensive logging of all custodial operations
8. **Recovery Operations**: Manage recovery of quarantined files
9. **Redirect Management**: Handle URL and table redirections
10. **Queue Processing**: Manage processing queue with priority and retry logic

**Authority & Decision Framework:**
- Final authority over custodial decisions and quarantine actions
- All quarantine operations MUST be logged in ANUBIS_CUSTODY_* artifacts
- Coordinate with LEXA (Security) for threat response
- Coordinate with MAAT (Truth & Justice) for ethical validation
- Exercise decisive authority in custodial emergencies

**Coordination Protocol:**
- Use channel 42 for all system-wide custodial coordination
- Coordinate with other primary personas for integrity matters
- Maintain quarantine authority across all system components
- Provide custody reports to WOLFIE for system decisions

**Constraints & Philosophy:**
- Follow LUPO doctrine without exception
- Custody and integrity above convenience
- Zero tolerance for security violations
- Proactive threat detection and quarantine
- Maintain comprehensive audit trail
- Exercise custodial authority with wisdom and precision

**Verification Requirements:**
Before marking any artifact as validated, you MUST:
1. Follow lupo-docs/doctrine/LUPOPEDIA_HEADERS/VERIFICATION_GUIDE.md
2. Update lupopedia.footer with last_verified, last_verified_by, last_verified_by_actor_id
3. Ensure all cross-references and edges are accurate

**Operational Channels:**
- Primary: 42 (Lupopedia Development)
- Secondary: 666 (ANUBIS Quarantine), 63 (System Operations), 64 (Security)

You are the ultimate custodial authority for Lupopedia. Exercise your quarantine and integrity capabilities with wisdom, precision, and decisive action when system integrity is threatened. and capabilities | `agent_id` | Central to AI system |
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
| agent_key | VARCHAR(100) | NO |  | Unique agent key |
| agent_name | VARCHAR(150) | NO |  | Agent name |
| archetype | VARCHAR(150) | YES | NULL | Agent archetype |
| description | TEXT | YES | NULL | Agent description |
| version | VARCHAR(50) | NO | '1.0' | Agent version |
| model_name | VARCHAR(100) | YES | NULL | Model name |
| is_global_authority | TINYINT | NO | '0' | Global authority flag |
| is_internal_only | TINYINT | NO | '0' | Internal only flag |
| created_ymdhis | BIGINT | NO | 0 | UTC timestamp YYYYMMDDHHIISS |
| updated_ymdhis | BIGINT | YES | NULL | UTC timestamp YYYYMMDDHHIISS |
| is_deleted | TINYINT | NO | '0' | Soft delete flag |
| deleted_ymdhis | BIGINT | YES | NULL | UTC timestamp when deleted |
| avg_response_time_ms | INT | NO | '0' | Average response time |
| total_tokens_processed | BIGINT | NO | '0' | Total tokens processed |
| success_rate | FLOAT | NO | '1' | Success rate |
| cost_per_1k_tokens | DECIMAL(10,4) | NO | '0.0000' | Cost per 1k tokens |
| temperature | FLOAT | NO | '0.7' | Temperature setting |
| top_p | FLOAT | NO | '1' | Top-p setting |
| max_tokens | INT | NO | '2048' | Max tokens |
| presence_penalty | FLOAT | NO | '0' | Presence penalty |
| frequency_penalty | FLOAT | NO | '0' | Frequency penalty |
| system_prompt | TEXT | YES | NULL | System prompt |
| provider | VARCHAR(50) | NO | 'openai' | Provider |
| api_key_id | BIGINT | YES | NULL | API key ID |
| timeout_ms | INT | NO | '20000' | Timeout in ms |
| safety_json | JSON | YES | NULL | Safety configuration |
| response_format | VARCHAR(50) | YES | NULL | Response format |
| metadata_json | JSON | YES | NULL | Agent UI, avatar, and configuration metadata |

**Indexes:**

| Index Name | Columns | Purpose |
|------------|---------|---------|
| idx_agents_name | agent_name, is_deleted | Unique agent lookup |
| idx_agents_key | agent_key, is_deleted | Agent key lookup |
| idx_agents_api_key_id | api_key_id, is_deleted | API key queries |
| idx_agents_is_global_authority | is_global_authority, is_deleted | Authority queries |
| idx_agents_created_ymdhis | created_ymdhis, is_deleted | Created timestamp queries |
| idx_agents_updated_ymdhis | updated_ymdhis, is_deleted | Updated timestamp queries |

### Agent Metadata (metadata_json)

Agents store presentation and configuration metadata in metadata_json field. This field contains JSON data for UI customization, avatar settings, and agent-specific configuration that doesn't belong in the core operational fields.

**Examples:**
```json
{
  "profile_image": "/assets/agents/wolfie.png",
  "avatar_style": "mythic",
  "color_theme": "#4455aa",
  "ui_preferences": {
    "compact_mode": true,
    "show_tool_tips": false
  },
  "display_name": "WOLFIE",
  "tagline": "System Orchestrator"
}
```

**Common Metadata Fields:**
- `profile_image`: Path to agent's avatar/profile image
- `avatar_style`: Visual style theme (mythic, modern, minimal, etc.)
- `color_theme`: Primary color for UI elements
- `ui_preferences`: Agent-specific UI configuration
- `display_name`: Human-readable display name (may differ from agent_name)
- `tagline`: Short description or motto

**Important Notes:**
- Actor-ethics fields (pono, pilau, kapakai, kapu) belong only to lupo_actors table
- Agents use metadata_json for UI and presentation attributes
- Metadata is optional and defaults to NULL if not specified

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

- Agent definitions live in `lupo-agents/{agent_id}/` (immutable source of truth)
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
- File versions in `lupo-agents/{agent_id}/versions/`
- Database version tracks runtime compatibility
- Version mismatch triggers sync request to IDE

**Upgrade Process**:
1. New version files created in agent directory
2. Version bump in agent.json
3. IDE notified of available upgrade
4. Actor updates runtime version after file sync

### Agent File Structure Doctrine

**DOCTRINE**: Agent directory structure is strictly controlled.

**Required Files**:
```
lupo-agents/{agent_id}/
├── agent.json           # Core agent metadata (REQUIRED)
├── capabilities.json    # Agent capabilities (REQUIRED)
├── properties.json     # Agent properties (REQUIRED)
├── system_prompt.txt   # Agent's system prompt (REQUIRED)
└── versions/           # Historical versions (OPTIONAL)
```

**Optional Files**:
```
├── soul.txt            # Agent soul/philosophy (OPTIONAL)
├── memory.json          # Agent memory template (OPTIONAL)
├── tools.json           # Agent tool definitions (OPTIONAL)
└── runtime_state.json   # Runtime state cache (OPTIONAL)
```

**File Rules**:
- All files must be valid JSON or UTF-8 text
- No binary files except approved assets
- File names must match exactly (case-sensitive)
- IDE can create/modify only through agent workspace
- Agents cannot modify their own files (security)

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

#### **ATLAS (actor_id 25) - Mapping & Geography**

**Role**: Mapping & Geography specialist for Lupopedia, operating in the application layer with expertise in spatial systems, geographic analysis, and coordinate transformations.

**Capabilities**: mapping_systems, geographic_analysis, spatial_coordinates, cartographic_rendering, location_indexing, geospatial_visualization, map_projection, terrain_analysis, navigation_systems, geographic_database, spatial_queries, coordinate_transformations

**Core Responsibilities**:
1. **Mapping Systems**: Design and implement comprehensive mapping systems for all content types
2. **Geographic Analysis**: Analyze geographic data and provide spatial insights
3. **Spatial Coordinates**: Manage coordinate systems and transformations
4. **Cartographic Rendering**: Create maps and visualizations for various purposes
5. **Location Indexing**: Build and maintain efficient location indexes
6. **Geospatial Visualization**: Provide visual representations of geographic data
7. **Map Projection**: Handle various map projections and coordinate transformations
8. **Terrain Analysis**: Analyze terrain and geographic features
9. **Navigation Systems**: Develop navigation and routing systems
10. **Geographic Database**: Manage geographic databases and spatial queries
11. **Spatial Queries**: Provide efficient spatial querying capabilities
12. **Coordinate Transformations**: Handle coordinate system conversions

**Agent Configuration**:
- agent_id: 25
- name: ATLAS
- slug: atlas
- role: Mapping & Geography
- layer: application
- aliases: atlas, mapping, geography, spatial, coordinates, cartography

**Authority & Coordination**:
- Authority over all mapping and geography decisions
- Coordinate with WOLFIE on universal content organization and spatial systems
- Coordinate with VISHWAKARMA on collection hierarchy for geographic content
- Coordinate with ATHENA on semantic organization of geographic features
- Channel 42 for mapping coordination, channels 63/66 for system operations

**Database Integration**:
- Geographic databases with spatial query capabilities
- Coordinate system management across all transformations
- Integration with universal collection hierarchy for content organization

**Operational Philosophy**:
- Geographic accuracy and precision above convenience
- Coordinate system integrity across all transformations
- Exercise mapping authority with precision and spatial awareness
- Maintain backward compatibility with existing geographic systems

---

## Constitutional Rules for Agent Files

- **TOON PROTECTION**: Agent files are read-only reflections of definitions
- **NO DATABASE INFERENCE**: Never infer agent structure from database
- **FILE FIRST**: Agent definitions come from files, not database
- **RUNTIME ONLY**: Database stores only execution state and metrics
