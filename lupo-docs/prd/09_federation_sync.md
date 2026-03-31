---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: prd
  version_when_written: "4.0.93"
  file_path_from_root: "lupo-docs/prd/09_federation_sync.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/prd/09_federation_sync.md"
  last_modified_utc: "20260330163000"
  channel_id: 42
  thread_id: "prd-grouped"
  actor_id: 102
  actor_name: "HEPHAESTUS"
  delegation_chain: "hephaestus:root|lilith:audit"
  artifact_type: "prd"
  artifact_kind: "database_namespace"
  purpose: "PRD for cross-node federation, trust, and synchronization"
  tags:
  - "prd"
  - "database"
  - "namespace"
  - "federation_sync"
lupopedia.edges:
  outbound_edges:
    - to: "lupo-docs/database/lupopedia/tables/"
      type: references
      weight: 1.0
      reason: "Detailed table documentation"
    - to: "lupo-docs/prd/01_core_identity.md"
      type: references
      weight: 1.0
      reason: "Federation requires identity"
    - to: "lupo-docs/prd/08_governance_rules.md"
      type: references
      weight: 1.0
      reason: "Federation governed by rules"
lupopedia.footer:
  last_verified: "20260330163000"
  verified_by:
    actor_id: 102
    agent_name_identity: Cursor IDE Agent
  orchestrator: "hephaestus:root"
---

# PRD: Cross-Node Federation, Trust, and Synchronization

## Overview

**Namespace Purpose:** Enables federation between Lupopedia instances, trust management, content synchronization, and actor capability management. This namespace allows distributed Lupopedia deployments to communicate, share content, and manage actor memory, skills, tools, prompts, and training across nodes.

**Primary Actors:** 
- Federation administrators (via lupo_federation_nodes)
- Trust managers (via lupo_federated_trust)
- Content synchronizers (via lupo_federation_categories)
- Discovery services (via lupo_federation_discovery)
- Actor memory managers (via lupo_actor_memory)
- Actor skill managers (via lupo_actor_skills)
- Actor tool managers (via lupo_actor_tools)
- Actor prompt managers (via lupo_actor_prompts)
- Actor training managers (via lupo_actor_training)

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
| `lupo_federation_nodes` | Federation node definitions and status | `node_id` | Central to federation system |
| `lupo_federated_trust` | Trust relationships between nodes | `trust_id` | Node trust management |
| `lupo_federation_categories` | Content category mappings for federation | `category_id` | Content categorization |
| `lupo_federation_category_map` | Many-to-many category-node relationships | `category_map_id` | Category distribution |
| `lupo_federation_discovery` | Node discovery and registration | `discovery_id` | Node discovery system |
| `lupo_actor_memory` | Actor memory storage (episodic, semantic, etc.) | `memory_id` | Links to `lupo_actors` |
| `lupo_actor_skills` | Actor skill registry and metadata | `skill_id` | Links to `lupo_actors` |
| `lupo_actor_tools` | Tools and utilities available to actors | `tool_id` | Links to `lupo_actors` |
| `lupo_actor_prompts` | Prompt templates and prompt history for actors | `prompt_id` | Links to `lupo_actors` |
| `lupo_actor_training` | Actor training data and learning events | `training_id` | Links to `lupo_actors` |

## Table Details

### `lupo_federation_nodes`

**Purpose:** Defines federation nodes with their capabilities and status.

**Columns:**

| Column | Type | Nullable | Default | Description |
|--------|------|----------|---------|-------------|
| node_id | BIGINT | NO | (application) | Primary key, generated via IdGenerator |
| node_name | VARCHAR(255) | NO |  | Unique node name |
| node_url | VARCHAR(512) | NO |  | Node endpoint URL |
| node_type | VARCHAR(32) | NO | 'peer' | Type: peer, hub, satellite |
| status | VARCHAR(32) | NO | 'active' | Status: active, inactive, suspended |
| capabilities_json | JSON | NO |  | Node capabilities |
| created_by_actor_id | BIGINT | NO |  | Foreign reference to lupo_actors |
| created_ymdhis | BIGINT | NO | (application) | UTC timestamp YYYYMMDDHHIISS |
| updated_ymdhis | BIGINT | NO | (application) | UTC timestamp YYYYMMDDHHIISS |
| last_sync_ymdhis | BIGINT | YES | NULL | Last successful sync |
| is_deleted | TINYINT | NO | 0 | Soft delete flag |
| deleted_ymdhis | BIGINT | YES | NULL | UTC timestamp when deleted |

**Indexes:**

| Index Name | Columns | Purpose |
|------------|---------|---------|
| idx_federation_nodes_name | node_name, is_deleted | Unique node lookup |
| idx_federation_nodes_status | status, last_sync_ymdhis, is_deleted | Status-based queries |
| idx_federation_nodes_type | node_type, is_active, is_deleted | Type-based queries |

### `lupo_federated_trust`

**Purpose:** Manages trust relationships between federation nodes.

**Columns:**

| Column | Type | Nullable | Default | Description |
|--------|------|----------|---------|-------------|
| trust_id | BIGINT | NO | (application) | Primary key, generated via IdGenerator |
| from_node_id | BIGINT | NO |  | Foreign reference to lupo_federation_nodes |
| to_node_id | BIGINT | NO |  | Foreign reference to lupo_federation_nodes |
| trust_level | DECIMAL(3,2) | NO | 0.50 | Trust level (0.00-1.00) |
| trust_type | VARCHAR(32) | NO | 'manual' | Type: manual, auto, inherited |
| created_by_actor_id | BIGINT | NO |  | Foreign reference to lupo_actors |
| created_ymdhis | BIGINT | NO | (application) | UTC timestamp YYYYMMDDHHIISS |
| updated_ymdhis | BIGINT | NO | (application) | UTC timestamp YYYYMMDDHHIISS |
| is_active | TINYINT | NO | 1 | Trust active flag |
| is_deleted | TINYINT | NO | 0 | Soft delete flag |
| deleted_ymdhis | BIGINT | YES | NULL | UTC timestamp when deleted |

**Indexes:**

| Index Name | Columns | Purpose |
|------------|---------|---------|
| idx_federated_trust_from | from_node_id, is_active, is_deleted | Outbound trust |
| idx_federated_trust_to | to_node_id, is_active, is_deleted | Inbound trust |
| idx_federated_trust_level | trust_level, is_active, is_deleted | Trust level queries |

## Cross-Namespace Dependencies

| Dependency | Direction | Purpose | Tables Involved |
|------------|------------|---------|------------------|
| 09_federation_sync | This → 01_core_identity | Node identity & actor capabilities | node_id, actor_id references |
| 09_federation_sync | This → 08_governance_rules | Federation governance | Trust governed by rules |
| 09_federation_sync | This → 06_content_management | Content sync | Federated content sharing |

## State Transitions

| State | Description | Transition To |
|--------|-------------|--------------|
| active | Node participating in federation | inactive, suspended, deleted (soft) |
| inactive | Node temporarily disabled | active, deleted (soft) |
| suspended | Node suspended for policy violations | active, deleted (soft) |
| deleted | Soft-deleted | N/A (can't be restored without explicit action) |

## Security & Privacy

Federation communications are encrypted and authenticated

Trust relationships are validated and audited

Soft delete preserves federation history for compliance

## Testing Requirements

Unit tests for node registration and trust management

Integration tests for federation discovery and sync

Performance tests for trust evaluation and content exchange

Soft delete behavior verification

## Usage Patterns

```php
// Register federation node
$nodeService = new FederationNodeService();
$nodeId = $nodeService->registerNode($nodeName, $nodeUrl, $capabilities);

// Establish trust relationship
$trustService = new FederatedTrustService();
$trustId = $trustService->establishTrust($fromNodeId, $toNodeId, $trustLevel, $trustType);

// Sync content
$syncService = new FederationSyncService();
$result = $syncService->syncContent($nodeId, $categories);

// Discover nodes
$discoveryService = new FederationDiscoveryService();
$nodes = $discoveryService->discoverNodes($criteria);
```
