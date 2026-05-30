---
lupopedia.headers:
  header_format_version: "4.1.4"
  file_path_from_root: docs/prd/09_A_FEDERATION_SYNC.md
  web_path: "https://www.lupopedia.com/lupopedia/docs/prd/09_A_FEDERATION_SYNC.md"
  status: active
  when_updated: "20260421223000"
  trust_tier: canonical
  questions_toon: null
  memory_toon: memory/development/canonical/1026/04/09_federation_sync.toon
  atoms_toon: null
  transcript_jsonl: 0/development/prd_files/federation-sync
  artifact_type: prd
  artifact_kind: specification
  channel_key: development
  federation_node_id: 0
  thread_id: null
  content_id: null
  content_parent_id: null
  default_collection_id: null
  lupopedia.schema: prd
  prd_cluster: 00_A_FORBIDDEN_AND_WHY_09_A_FEDERATION_SYNC
  title: "PRD: Cross-Node Federation, Trust, and Synchronization"
  summary: null
---
# PRD: Cross-Node Federation, Trust, and Synchronization

## Overview

**Namespace Purpose:** Enables federation between Lupopedia instances, trust management, content synchronization, and actor capability management. This namespace allows distributed Lupopedia deployments to communicate, share content, and manage actor memory, skills, tools, prompts, and training across nodes.

**Primary Actors:** 
- Federation administrators (via lupo_federation_nodes)
- Trust managers (via lupo_federated_trust)
- Content synchronizers (via lupo_federation_categories)
- Discovery services (via lupo_federation_discovery)
- Actor memory graph (via `lupo_memory_nodes` / `lupo_memory_edges`; PRD 38)
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
| `lupo_memory_nodes` | Unified memory storage (per owner; federation-relevant payloads) | `memory_node_id` | Graph + `lupo_memory_edges` |
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
| trust_hundredths | INT | NO | 50 | Trust ????? 100 (50 = 0.50; scale 0????????100) |
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
| idx_federated_trust_level | trust_hundredths, is_active, is_deleted | Trust level queries |

## Cross-Namespace Dependencies

| Dependency | Direction | Purpose | Tables Involved |
|------------|------------|---------|------------------|
| 09_federation_sync | This ???????? 01_core_identity | Node identity & actor capabilities | node_id, actor_id references |
| 09_federation_sync | This ???????? 08_governance_rules | Federation governance | Trust governed by rules |
| 09_federation_sync | This ???????? 06_content_management | Content sync | Federated content sharing |

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


---

## Context????????Typed, Status????????Aware, Directional Edged Memory Doctrine (4.0.96)

1. Memory in Lupopedia is represented as a directed graph of nodes and edges. 
  Each memory node is a first-class entity in the semantic network and may be 
  owned by actors, departments, auth_users, channels, federation nodes, or the 
  global system.

2. Every edge in the memory graph has FOUR dimensions:
  - **edge type** (the relationship)
  - **edge context** (the classification of the memory)
  - **edge status** (the epistemic support level)
  - **edge direction** (the traversal orientation)

3. **Edge Direction** defines whether the relationship is:
  - unidirectional (A ???????? B)
  - bidirectional (A ???????? B)
  - restricted-direction (A ???????? B but not B ???????? A unless explicitly defined)
  Reverse traversal MUST NOT be inferred unless explicitly defined.

4. **Edge Type** defines the relationship between nodes, including but not 
  limited to:
  - influences
  - inherits
  - authored_by
  - observed_by
  - contradicts
  - supports
  - consolidates_from
  - refines
  - overrides

5. **Edge Context** defines the classification of the memory node. Context is 
  not based on the content of the memory, but on the structural support 
  provided by the graph. The primary context classifications are:
  - doctrine
  - experiential
  - system_generated
  - countermeasure_generated
  - summary
  - contradictory
  - deprecated

6. **Edge Status** defines the epistemic support level of the memory node:
  - **unsupported**: insufficient supporting edges; provisional memory.
  - **supported**: sufficient supporting edges; validated memory.
  - **needs_review**: conflicting, incomplete, or ambiguous edges requiring 
    agent or human intervention.

7. When `edge_status = 'needs_review'`, a **review_reason** MUST be provided. 
  This field explains *why* the edge requires review and *which agent* should 
  handle it. Examples include:
  - orphaned_edge
  - contradiction
  - new_doctrine
  - schema_drift
  - consolidation_candidate
  - integrity_unknown
  - human_escalation

  Agents use this field to determine their work queues:
  - ANUBIS handles: integrity_unknown, orphaned_edge
  - THOTH handles: schema_drift, contradiction, new_doctrine
  - KAIROS handles: consolidation_candidate
  - Human operator handles: human_escalation

8. Memory nodes may transition between statuses as edges are added, removed, 
  or reclassified. A node may move from unsupported ???????? supported when 
  sufficient supporting edges accumulate.

9. Actors inherit memory edges from:
  - their department
  - their auth_user
  - their federation node
  - their assigned faucets
  - their assigned tasks

10. Memory traversal is context-aware and direction-aware. Actors may only 
   traverse edges permitted by their boundaries, department rules, auth_user 
   pairing, faucet assignments, and operational mode (live, simulation, 
   analysis).

11. No inference is allowed. All edges, contexts, statuses, directions, and 
   review reasons must be explicitly defined in PRDs, database rows, or 
   system-generated memory.

12. Memory is not a flat file. It is a structured, typed, classified, 
   status-aware, direction-aware graph. Traversal depth determines visible 
   memory; deeper traversal reveals more context, subject to boundary rules.

13. All changes to memory structure, edge types, edge contexts, edge statuses, 
   edge directions, or review reasons must be documented in PRDs and versioned.
```
