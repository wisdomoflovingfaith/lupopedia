---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: prd
  version_when_written: "4.0.93"
  file_path_from_root: "lupo-docs/prd/08_governance_rules.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/prd/08_governance_rules.md"
  last_modified_utc: "20260330163000"
  channel_id: 42
  thread_id: "prd-grouped"
  actor_id: 102
  actor_name: "HEPHAESTUS"
  delegation_chain: "hephaestus:root|lilith:audit"
  artifact_type: "prd"
  artifact_kind: "database_namespace"
  purpose: "PRD for governance rules, permissions, and system control"
  tags:
  - "prd"
  - "database"
  - "namespace"
  - "governance_rules"
lupopedia.edges:
  outbound_edges:
    - to: "lupo-docs/database/lupopedia/tables/"
      type: references
      weight: 1.0
      reason: "Detailed table documentation"
    - to: "lupo-docs/prd/01_core_identity.md"
      type: references
      weight: 1.0
      reason: "Governance depends on identity"
    - to: "lupo-docs/prd/14_system_operations.md"
      type: references
      weight: 1.0
      reason: "Governance controls system operations"
    - to: "lupo-docs/versions/4.0.93/prd/00_root_constitutional_system_requirements.md"
      type: references
      weight: 1.0
      reason: "Root constitutional system requirements"
lupopedia.footer:
  last_verified: "20260330163000"
  verified_by:
    actor_id: 102
    agent_name_identity: Cursor IDE Agent
  orchestrator: "hephaestus:root"
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

## Cross-Namespace Dependencies

| Dependency | Direction | Purpose | Tables Involved |
|------------|------------|---------|------------------|
| 08_governance_rules | This → 01_core_identity | Identity permissions | actor_id references |
| 08_governance_rules | This → All namespaces | System control | Rules can target any entity |
| 08_governance_rules | This → 14_system_operations | System governance | Override system operations |

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
