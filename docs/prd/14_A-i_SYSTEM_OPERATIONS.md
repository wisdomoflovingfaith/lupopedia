---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/prd/14_A-i_SYSTEM_OPERATIONS.md
  web_path: https://www.lupopedia.com/lupopedia/docs/prd/14_A-i_SYSTEM_OPERATIONS.md
  status: active
  when_updated: '20260513033046'
  trust_tier: canonical
  questions_toon: null
  memory_toon: memory/development/canonical/1026/04/14_system_operations.toon
  atoms_toon: null
  transcript_jsonl: 0/development/prd_files/system-operations
  artifact_type: prd
  artifact_kind: specification
  channel_key: development
  federation_node_id: 0
  thread_key: null
  lupopedia.schema: prd
  prd_cluster: 00_A-i_14_A-i
  title: 'PRD: System Operations, Configuration, and Maintenance Database Tables'
  summary: null
---
# PRD: System Operations, Configuration, and Maintenance Database Tables

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

## Overview

**Namespace Purpose:** Provides system-level operations including configuration management, health monitoring, schema migrations, module management, and help system functionality. This namespace enables the system to operate, maintain itself, and provide user assistance.

**Primary Actors:** 
- System administrators (via lupo_system_config)
- Health monitors (via lupo_system_health_snapshots)
- Migration managers (via lupo_schema_migrations)
- Module managers (via lupo_modules)
- Help system managers (via lupo_help_topics, lupo_help_tree)

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
| `lupo_system_config` | System-wide configuration settings | `config_id` | Central configuration storage |
| `lupo_system_commands` | System command definitions and execution | `command_id` | Command processing system |
| `lupo_system_health_snapshots` | System health and performance snapshots | `snapshot_id` | Health monitoring system |
| `lupo_schema_migrations` | Database schema migration tracking | `migration_id` | Schema evolution tracking |
| `lupo_modules` | System module registry and management | `module_id` | Module management system |
| `lupo_rolls` | System roll management for deployments | `roll_id` | Deployment tracking system |
| `lupo_help_topics` | Help system topic definitions | `help_topic_id` | Help content organization |
| `lupo_help_tree` | Hierarchical help tree structure | `help_tree_id` | Help navigation system |
| `lupo_hotfix_registry` | Hotfix tracking and application | `hotfix_id` | Emergency fix management |

## Table Details

### `lupo_system_config`

**Purpose:** Stores system-wide configuration settings with versioning and audit trail.

**Columns:**

| Column | Type | Nullable | Default | Description |
|--------|------|----------|---------|-------------|
| config_id | BIGINT | NO | (application) | Primary key, generated via IdGenerator |
| config_key | VARCHAR(255) | NO |  | Unique configuration key |
| config_value | TEXT | YES | NULL | Configuration value |
| config_type | VARCHAR(32) | NO | 'string' | Type: string, json, number, boolean |
| description | TEXT | YES | NULL | Configuration description |
| is_encrypted | TINYINT | NO | 0 | Whether value is encrypted |
| created_by_actor_id | BIGINT | YES | NULL | Actor who created this config |
| created_ymdhis | BIGINT | NO | (application) | UTC timestamp YYYYMMDDHHIISS |
| updated_ymdhis | BIGINT | NO | (application) | UTC timestamp YYYYMMDDHHIISS |
| is_active | TINYINT | NO | 1 | Configuration active flag |
| is_deleted | TINYINT | NO | 0 | Soft delete flag |
| deleted_ymdhis | BIGINT | YES | NULL | UTC timestamp when deleted |

**Indexes:**

| Index Name | Columns | Purpose |
|------------|---------|---------|
| idx_system_config_key | config_key, is_active, is_deleted | Unique key lookup |
| idx_system_config_type | config_type, is_active, is_deleted | Type-based queries |
| idx_system_config_actor | created_by_actor_id, created_ymdhis, is_deleted | Actor's configurations |

### `lupo_system_health_snapshots`

**Purpose:** Captures system health and performance metrics for monitoring and alerting.

**Columns:**

| Column | Type | Nullable | Default | Description |
|--------|------|----------|---------|-------------|
| snapshot_id | BIGINT | NO | (application) | Primary key, generated via IdGenerator |
| snapshot_type | VARCHAR(32) | NO | 'performance' | Type: performance, error, security, backup |
| component | VARCHAR(64) | NO |  | System component being monitored |
| metrics_json | JSON | NO |  | Health metrics data |
| status | VARCHAR(32) | NO | 'healthy' | Status: healthy, warning, critical |
| alert_thresholds_json | JSON | YES | NULL | Alert threshold definitions |
| created_ymdhis | BIGINT | NO | (application) | UTC timestamp YYYYMMDDHHIISS |
| expires_ymdhis | BIGINT | YES | NULL | When snapshot expires |
| is_deleted | TINYINT | NO | 0 | Soft delete flag |
| deleted_ymdhis | BIGINT | YES | NULL | UTC timestamp when deleted |

**Indexes:**

| Index Name | Columns | Purpose |
|------------|---------|---------|
| idx_health_snapshots_type | snapshot_type, component, status, is_deleted | Component health lookup |
| idx_health_snapshots_created | created_ymdhis, expires_ymdhis, is_deleted | Recent snapshots |
| idx_health_snapshots_status | status, is_deleted, created_ymdhis | Status-based queries |

### `lupo_schema_migrations`

**Purpose:** Tracks database schema migrations and their application status.

**Columns:**

| Column | Type | Nullable | Default | Description |
|--------|------|----------|---------|-------------|
| migration_id | BIGINT | NO | (application) | Primary key, generated via IdGenerator |
| migration_name | VARCHAR(255) | NO |  | Unique migration identifier |
| from_version | VARCHAR(32) | NO |  | Source version |
| to_version | VARCHAR(32) | NO |  | Target version |
| migration_type | VARCHAR(32) | NO | 'schema' | Type: schema, data, config |
| migration_sql | TEXT | NO |  | Migration SQL statements |
| rollback_sql | TEXT | YES | NULL | Rollback SQL statements |
| status | VARCHAR(32) | NO | 'pending' | Status: pending, running, completed, failed, rolled_back |
| applied_ymdhis | BIGINT | YES | NULL | When migration was applied |
| applied_by_actor_id | BIGINT | YES | NULL | Actor who applied migration |
| created_ymdhis | BIGINT | NO | (application) | UTC timestamp YYYYMMDDHHIISS |
| is_deleted | TINYINT | NO | 0 | Soft delete flag |
| deleted_ymdhis | BIGINT | YES | NULL | UTC timestamp when deleted |

**Indexes:**

| Index Name | Columns | Purpose |
|------------|---------|---------|
| idx_migrations_name | migration_name, is_deleted | Migration lookup |
| idx_migrations_status | status, is_deleted, created_ymdhis | Status-based queries |
| idx_migrations_version | from_version, to_version, is_deleted | Version tracking |

### `lupo_modules`

**Purpose:** Registry for system modules with versioning and dependency management.

**Columns:**

| Column | Type | Nullable | Default | Description |
|--------|------|----------|---------|-------------|
| module_id | BIGINT | NO | (application) | Primary key, generated via IdGenerator |
| module_name | VARCHAR(255) | NO |  | Unique module name |
| module_version | VARCHAR(32) | NO |  | Module version |
| module_type | VARCHAR(32) | NO | 'core' | Type: core, plugin, integration |
| status | VARCHAR(32) | NO | 'active' | Status: active, inactive, disabled |
| dependencies_json | JSON | YES | NULL | Module dependencies |
| configuration_json | JSON | YES | NULL | Module configuration |
| install_path | VARCHAR(512) | YES | NULL | File system path |
| created_by_actor_id | BIGINT | YES | NULL | Actor who installed module |
| created_ymdhis | BIGINT | NO | (application) | UTC timestamp YYYYMMDDHHIISS |
| updated_ymdhis | BIGINT | NO | (application) | UTC timestamp YYYYMMDDHHIISS |
| is_deleted | TINYINT | NO | 0 | Soft delete flag |
| deleted_ymdhis | BIGINT | YES | NULL | UTC timestamp when deleted |

**Indexes:**

| Index Name | Columns | Purpose |
|------------|---------|---------|
| idx_modules_name | module_name, is_deleted | Unique module lookup |
| idx_modules_type | module_type, status, is_deleted | Type-based queries |
| idx_modules_status | status, is_deleted, updated_ymdhis | Status-based queries |

### `lupo_help_topics`

**Purpose:** Defines help system topics for user assistance.

**Columns:**

| Column | Type | Nullable | Default | Description |
|--------|------|----------|---------|-------------|
| help_topic_id | BIGINT | NO | (application) | Primary key, generated via IdGenerator |
| topic_name | VARCHAR(255) | NO |  | Unique topic name |
| parent_topic_id | BIGINT | YES | NULL | Self-reference for hierarchy |
| topic_content | TEXT | NO |  | Help topic content |
| keywords_json | JSON | YES | NULL | Search keywords |
| view_count | INT | NO | 0 | Number of times viewed |
| sort_order | INT | NO | 0 | Display order |
| created_by_actor_id | BIGINT | YES | NULL | Actor who created this topic |
| created_ymdhis | BIGINT | NO | (application) | UTC timestamp YYYYMMDDHHIISS |
| updated_ymdhis | BIGINT | NO | (application) | UTC timestamp YYYYMMDDHHIISS |
| is_published | TINYINT | NO | 1 | Topic published flag |
| is_deleted | TINYINT | NO | 0 | Soft delete flag |
| deleted_ymdhis | BIGINT | YES | NULL | UTC timestamp when deleted |

**Indexes:**

| Index Name | Columns | Purpose |
|------------|---------|---------|
| idx_help_topics_parent | parent_topic_id, sort_order, is_deleted | Hierarchy queries |
| idx_help_topics_published | is_published, view_count, is_deleted | Published topics |
| idx_help_topics_keywords | (generated from keywords_json) | Keyword search |

## Cross-Namespace Dependencies

| Dependency | Direction | Purpose | Tables Involved |
|------------|------------|---------|------------------|
| 14_system_operations | This ???????? All namespaces | System configuration | All tables depend on system config |
| 01_core_identity | Core ???????? This | Actor permissions | System operations require identity |

## State Transitions

| State | Description | Transition To |
|--------|-------------|--------------|
| active | Normal operation | inactive, disabled, deleted (soft) |
| inactive | Temporarily disabled | active, deleted (soft) |
| disabled | Permanently disabled | N/A (requires manual re-enable) |
| deleted | Soft-deleted | N/A (can't be restored without explicit action) |

## Security & Privacy

System configuration values are encrypted when marked as sensitive

Health snapshots may contain system metrics but no PII

Module registry tracks installation sources for security

Soft delete preserves configuration history for audit

## Testing Requirements

Unit tests for configuration management

Integration tests for health monitoring

Performance tests for help system search

Soft delete behavior verification

## Usage Patterns

```php
// Get system configuration
$configService = new SystemConfigService();
$value = $configService->get('site_name', 'default');

// Create health snapshot
$healthService = new SystemHealthService();
$snapshotId = $healthService->createSnapshot('performance', $metrics);

// Apply migration
$migrationService = new MigrationService();
$result = $migrationService->applyMigration($migrationName, $sql);

// Register module
$moduleService = new ModuleService();
$moduleId = $moduleService->register($moduleName, $version, $type);

// Search help topics
$helpService = new HelpService();
$topics = $helpService->search($keywords);
```
