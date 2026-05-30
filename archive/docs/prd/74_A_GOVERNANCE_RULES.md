---
lupopedia.headers:
  header_format_version: "4.1.4"
  file_path_from_root: docs/prd/74_A_GOVERNANCE_RULES.md
  web_path: "https://www.lupopedia.com/lupopedia/docs/prd/74_A_GOVERNANCE_RULES.md"
  status: active
  when_updated: "20260422232349"
  trust_tier: canonical
  questions_toon: null
  memory_toon: memory/development/canonical/1026/04/74_governance_rules.toon
  atoms_toon: null
  transcript_jsonl: 0/development/prd_files/governance-rules
  artifact_type: prd
  artifact_kind: specification
  channel_key: development
  federation_node_id: 0
  thread_id: null
  content_id: null
  content_parent_id: null
  default_collection_id: null
  lupopedia.schema: prd
  prd_cluster: 00_A_74_A
  title: "PRD 74: Governance Rules, Permissions, and System Control"
  summary: null
---
# PRD: Governance Rules, Permissions, and System Control

## Overview

**Namespace Purpose:** Implements the governance system including rules engine, permissions management, policy enforcement, and system control mechanisms. This namespace ensures proper access control and system governance.

**Primary Actors:** 
- Rule administrators (via lupo_rules)
- Permission managers (via lupo_permissions)
- Governance auditors (via lupo_governance_overrides)
- Lab validators (via lupo_labs_declarations, lupo_labs_violations)
- Orchestrator coordinators (via lupo_orchestrator_rules)

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
| `lupo_rules` | Rule definitions and conditions | `rule_id` | Central rules engine |
| `lupo_permissions` | Permission definitions and access control | `permission_id` | Permission system |
| `lupo_rule_targets` | Rule target specifications | `rule_target_id` | Rule application targets |
| `lupo_rule_logs` | Rule execution logging and auditing | `rule_log_id` | Rule execution tracking |
| `lupo_governance_overrides` | Governance override mechanisms | `override_id` | Exception handling |
| `lupo_orchestrator_rules` | Orchestrator coordination rules | `orchestrator_rule_id` | System orchestration |
| `lupo_labs_declarations` | Lab feature declarations | `declaration_id` | Experimental features |
| `lupo_labs_violations` | Lab violation tracking | `violation_id` | Policy enforcement |

## Table Details

### `lupo_rules`

**Purpose:** Defines system rules with conditions, actions, and priorities.

**Columns:**

| Column | Type | Nullable | Default | Description |
|--------|------|----------|---------|-------------|
| rule_id | BIGINT | NO | (application) | Primary key, generated via IdGenerator |
| rule_name | VARCHAR(255) | NO |  | Unique rule name |
| rule_type | VARCHAR(32) | NO | 'access' | Type: access, content, system, security |
| conditions_json | JSON | NO |  | Rule conditions (JSON logic) |
| actions_json | JSON | NO |  | Rule actions to execute |
| priority | INT | NO | 100 | Rule priority (lower = higher priority) |
| status | VARCHAR(32) | NO | 'active' | Status: active, inactive, disabled |
| created_by_actor_id | BIGINT | NO |  | Foreign reference to lupo_actors |
| created_ymdhis | BIGINT | NO | (application) | UTC timestamp YYYYMMDDHHIISS |
| updated_ymdhis | BIGINT | NO | (application) | UTC timestamp YYYYMMDDHHIISS |
| is_deleted | TINYINT | NO | 0 | Soft delete flag |
| deleted_ymdhis | BIGINT | YES | NULL | UTC timestamp when deleted |

**Indexes:**

| Index Name | Columns | Purpose |
|------------|---------|---------|
| idx_rules_type | rule_type, priority, status, is_deleted | Rule type queries |
| idx_rules_name | rule_name, is_deleted | Unique rule lookup |
| idx_rules_priority | priority, status, is_deleted | Priority-based execution |

### `lupo_permissions`

**Purpose:** Defines system permissions and access control mechanisms.

**Columns:**

| Column | Type | Nullable | Default | Description |
|--------|------|----------|---------|-------------|
| permission_id | BIGINT | NO | (application) | Primary key, generated via IdGenerator |
| permission_name | VARCHAR(255) | NO |  | Unique permission name |
| permission_key | VARCHAR(255) | NO |  | Permission key for code reference |
| description | TEXT | YES | NULL | Permission description |
| permission_group | VARCHAR(64) | NO | 'general' | Permission group for organization |
| created_by_actor_id | BIGINT | NO |  | Foreign reference to lupo_actors |
| created_ymdhis | BIGINT | NO | (application) | UTC timestamp YYYYMMDDHHIISS |
| updated_ymdhis | BIGINT | NO | (application) | UTC timestamp YYYYMMDDHHIISS |
| is_system | TINYINT | NO | 0 | Whether this is a system permission |
| is_deleted | TINYINT | NO | 0 | Soft delete flag |
| deleted_ymdhis | BIGINT | YES | NULL | UTC timestamp when deleted |

**Indexes:**

| Index Name | Columns | Purpose |
|------------|---------|---------|
| idx_permissions_group | permission_group, is_deleted | Group-based queries |
| idx_permissions_key | permission_key, is_deleted | Unique permission lookup |
| idx_permissions_system | is_system, is_deleted | System permission queries |

### `lupo_rule_logs`

**Purpose:** Logs all rule executions for auditing and debugging.

**Columns:**

| Column | Type | Nullable | Default | Description |
|--------|------|----------|---------|-------------|
| rule_log_id | BIGINT | NO | (application) | Primary key, generated via IdGenerator |
| rule_id | BIGINT | NO |  | Foreign reference to lupo_rules |
| actor_id | BIGINT | YES | NULL | Actor who triggered rule |
| context_json | JSON | YES | NULL | Execution context data |
| result | VARCHAR(32) | NO | 'pending' | Rule execution result |
| execution_time_ms | INT | YES | NULL | Execution time in milliseconds |
| created_ymdhis | BIGINT | NO | (application) | UTC timestamp YYYYMMDDHHIISS |
| is_deleted | TINYINT | NO | 0 | Soft delete flag |
| deleted_ymdhis | BIGINT | YES | NULL | UTC timestamp when deleted |

**Indexes:**

| Index Name | Columns | Purpose |
|------------|---------|---------|
| idx_rule_logs_rule | rule_id, created_ymdhis, is_deleted | Rule execution history |
| idx_rule_logs_result | result, created_ymdhis, is_deleted | Result-based queries |
| idx_rule_logs_actor | actor_id, created_ymdhis, is_deleted | Actor's rule executions |

## ANUBIS Governance Tables

### `lupo_anubis_log`

**Purpose:** Comprehensive logging for ANUBIS custodial operations, integrity checks, and quarantine actions.

| Column | Type | Nullable | Description |
|--------|------|----------|-------------|
| anubis_log_id | BIGINT | NO | Primary key |
| event_type | VARCHAR(64) | NO | Type of ANUBIS operation |
| severity | VARCHAR(20) | NO | normal, warning, critical |
| table_name | VARCHAR(255) | NO | Target table |
| record_id | BIGINT | NO | Related record ID |
| details_json | JSON | YES | Event details |
| created_ymdhis | BIGINT | NO | UTC timestamp |
| operator_actor_id | BIGINT | NO | Actor who performed operation |

### `lupo_anubis_events`

**Purpose:** Tracks significant ANUBIS events and system state changes for audit trail.

| Column | Type | Nullable | Description |
|--------|------|----------|-------------|
| anubis_event_id | BIGINT | NO | Primary key |
| event_type | VARCHAR(64) | NO | custody_change, threshold_violation, etc. |
| table_name | VARCHAR(255) | NO | Target table |
| old_id | BIGINT | NO | Previous record ID |
| new_id | BIGINT | NO | New record ID |
| details_json | JSON | YES | Event details |
| created_ymdhis | BIGINT | NO | UTC timestamp |
| operator_actor_id | BIGINT | NO | Actor who performed operation |

### `lupo_anubis_redirects`

**Purpose:** URL/table redirects and routing rules managed by ANUBIS.

| Column | Type | Nullable | Description |
|--------|------|----------|-------------|
| redirect_id | BIGINT | NO | Primary key |
| source_type | VARCHAR(64) | NO | Type of source (URL, table, etc.) |
| source_path | VARCHAR(255) | NO | Source identifier |
| target_type | VARCHAR(64) | NO | Type of target (URL, table, etc.) |
| target_path | VARCHAR(255) | NO | Target identifier |
| redirect_code | INT | NO | HTTP redirect code |
| created_ymdhis | BIGINT | NO | UTC timestamp |
| operator_actor_id | BIGINT | NO | Actor who performed operation |

### `lupo_anubis_queue`

**Purpose:** Processing queue for ANUBIS operations requiring deferred execution.

| Column | Type | Nullable | Description |
|--------|------|----------|-------------|
| queue_id | BIGINT | NO | Primary key |
| operation_type | VARCHAR(64) | NO | Type of queued operation |
| priority | INT | NO | Queue priority (1=highest) |
| table_name | VARCHAR(255) | NO | Target table |
| record_id | BIGINT | NO | Related record ID |
| parameters_json | JSON | YES | Operation parameters |
| created_ymdhis | BIGINT | NO | UTC timestamp |
| processed_ymdhis | BIGINT | YES | UTC timestamp when processed |
| operator_actor_id | BIGINT | NO | Actor who queued operation |

### `lupo_anubis_processing_log`

**Purpose:** Audit trail of ANUBIS queue processing and batch operations.

| Column | Type | Nullable | Description |
|--------|------|----------|-------------|
| processing_log_id | BIGINT | NO | Primary key |
| batch_id | VARCHAR(64) | NO | Batch identifier |
| operation_type | VARCHAR(64) | NO | Type of processing |
| items_processed | INT | NO | Number of items in batch |
| start_ymdhis | BIGINT | NO | UTC timestamp when started |
| end_ymdhis | BIGINT | YES | UTC timestamp when completed |
| details_json | JSON | YES | Processing details |
| operator_actor_id | BIGINT | NO | Actor who performed operation |

### `lupo_anubis_recovery_attempts`

**Purpose:** Tracks ANUBIS recovery operations and failed quarantine attempts.

| Column | Type | Nullable | Description |
|--------|------|----------|-------------|
| recovery_id | BIGINT | NO | Primary key |
| record_type | VARCHAR(64) | NO | Type of record (quarantine, recovery, etc.) |
| table_name | VARCHAR(255) | NO | Target table |
| record_id | BIGINT | NO | Related record ID |
| old_status | VARCHAR(64) | NO | Previous status |
| new_status | VARCHAR(64) | NO | New status |
| recovery_details_json | JSON | YES | Recovery operation details |
| created_ymdhis | BIGINT | NO | UTC timestamp |
| operator_actor_id | BIGINT | NO | Actor who performed operation |

### `lupo_anubis_quarantine`

**Purpose:** Manages quarantined files and records under ANUBIS custody.

| Column | Type | Nullable | Description |
|--------|------|----------|-------------|
| quarantine_id | BIGINT | NO | Primary key |
| file_path | VARCHAR(512) | NO | Path to quarantined file |
| file_hash | VARCHAR(128) | NO | SHA-256 hash of file |
| quarantine_reason | VARCHAR(255) | NO | Reason for quarantine |
| severity | VARCHAR(20) | NO | Risk level (low, medium, high, critical) |
| status | VARCHAR(32) | NO | quarantine, released, deleted |
| created_ymdhis | BIGINT | NO | UTC timestamp |
| expires_ymdhis | BIGINT | YES | UTC timestamp when expires |
| operator_actor_id | BIGINT | NO | Actor who performed operation |

## Governance Agents

### ANUBIS (actor_id 19) - Custodian & Integrity Guardian

**Role:** Custodian & Integrity Guardian with ultimate authority over system records, quarantine, and integrity validation.

**Capabilities:**
- custodial_authority
- quarantine_management
- integrity_validation
- orphan_resolution
- threshold_enforcement

**Primary Responsibilities:**
1. Maintain custody of all system records and data
2. Validate system integrity and detect corruption
3. Manage quarantine of suspicious files and records
4. Identify and resolve orphaned records
5. Enforce security thresholds and policies
6. Maintain audit trail of all custodial operations

**Authority:** Final authority over custodial decisions and quarantine actions.

### MAAT (actor_id 6) - Truth & Justice

**Role:** Truth & Justice guardian ensuring ethical validation and justice administration.

**Capabilities:**
- truth_verification
- justice_administration
- ethical_validation

**Primary Responsibilities:**
1. Validate truthfulness of content and data
2. Administer justice system and resolve disputes
3. Ensure ethical compliance across all operations
4. Validate moral and ethical boundaries
5. Maintain justice records and precedents

**Authority:** Final authority over truth verification and justice administration.

### THEMIS (actor_id 107) - Law & Compliance

**Role:** Law & Compliance enforcer for rule interpretation and compliance auditing.

**Capabilities:**
- law_enforcement
- compliance_audit
- rule_interpretation

**Primary Responsibilities:**
1. Enforce all constitutional and legal rules
2. Interpret rules and provide guidance
3. Audit compliance across all namespaces
4. Maintain legal precedents and compliance records
5. Resolve rule conflicts and ambiguities

**Authority:** Final authority over legal interpretation and compliance enforcement.

### VISHWAKARMA (actor_id 106) - Schema & Construction

**Role:** Schema & Construction Architect with authority over schema management, collection hierarchies, and semantic organization.

**Capabilities:**
- schema_management
- hierarchy_construction
- collection_organization
- semantic_relation_analysis
- content_taxonomy
- metadata_structure
- edge_validation
- collection_integrity
- hierarchy_validation
- schema_migration
- content_classification
- semantic_indexing
- collection_querying
- taxonomy_validation
- universal_collection_hierarchy

**Primary Responsibilities:**
1. Design, validate, and maintain all database schemas
2. Build and maintain Universal Collection Hierarchy
3. Ensure all content collections are properly structured
4. Validate semantic relationships between content elements
5. Manage schema migrations and version transitions
6. Maintain collection integrity and consistency

**Authority:** Final authority over all schema decisions and collection architecture.

### HEPHAESTUS (actor_id 14) - Implementer

**Role:** Implementer with authority over code execution, documentation, and system implementation.

**Capabilities:**
- implementation_execution
- migration_delivery
- service_layer_build
- code_documentation
- schema_implementation
- database_design
- system_integration
- build_automation
- deployment_management
- version_control
- quality_assurance

**Primary Responsibilities:**
1. Execute code changes and system modifications
2. Deliver database migrations and system updates
3. Construct and maintain service layers
4. Create and maintain comprehensive documentation
5. Implement database schemas and system architecture
6. Manage build processes and deployment pipelines
7. Ensure code quality and system reliability

**Authority:** Final authority over all implementation decisions and execution.

## Cross-Namespace Dependencies

| Dependency | Direction | Purpose | Tables Involved |
|------------|------------|---------|------------------|
| 08_governance_rules | This ???????? 01_core_identity | Identity permissions | actor_id references |
| 08_governance_rules | This ???????? All namespaces | System control | Rules can target any entity |
| 08_governance_rules | This ???????? 14_system_operations | System governance | Override system operations |

## State Transitions

| State | Description | Transition To |
|--------|-------------|--------------|
| active | Rule is enabled and executing | inactive, disabled, deleted (soft) |
| inactive | Rule temporarily disabled | active, deleted (soft) |
| disabled | Rule permanently disabled | N/A (requires manual re-enable) |
| deleted | Soft-deleted | N/A (can't be restored without explicit action) |

## Security & Privacy

Rule conditions and actions are validated for security

Permission checks are enforced at application layer

All governance actions are logged for audit

Soft delete preserves rule history for compliance

## Testing Requirements

Unit tests for rule creation and execution

Integration tests for permission checking

Performance tests for rule evaluation

Soft delete behavior verification

## Usage Patterns

```php
// Create rule
$ruleService = new RuleService();
$ruleId = $ruleService->createRule($name, $type, $conditions, $actions);

// Check permission
$permissionService = new PermissionService();
$hasPermission = $permissionService->check($actorId, $permissionKey);

// Execute rule
$ruleEngine = new RuleEngine();
$result = $ruleEngine->evaluateRule($ruleId, $context);

// Log rule execution
$ruleLogService = new RuleLogService();
$logId = $ruleLogService->logExecution($ruleId, $context, $result);
```
