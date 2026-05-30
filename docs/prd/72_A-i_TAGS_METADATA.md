---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/prd/72_A-i_TAGS_METADATA.md
  web_path: https://www.lupopedia.com/lupopedia/docs/prd/72_A-i_TAGS_METADATA.md
  status: active
  when_updated: '20260513033046'
  trust_tier: canonical
  questions_toon: null
  memory_toon: memory/development/canonical/1026/04/72_tags_metadata.toon
  atoms_toon: null
  transcript_jsonl: 0/development/prd_files/tags-metadata
  artifact_type: prd
  artifact_kind: specification
  channel_key: development
  federation_node_id: 0
  thread_key: null
  lupopedia.schema: prd
  prd_cluster: 00_A-i_72_A-i
  title: 'PRD 72: Tagging, Metadata, and Semantic Organization Database Tables'
  summary: null
---
# PRD: Tagging, Metadata, and Semantic Organization Database Tables

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

**Namespace Purpose:** Provides tagging, metadata management, and semantic organization capabilities for all content types. This namespace enables flexible categorization, search optimization, and content discovery.

**Primary Actors:** 
- Content taggers (via lupo_hashtags)
- Metadata managers (via lupo_metadata)
- Context organizers (via lupo_contexts)
- Semantic mappers (via lupo_edges)
- Card creators (via lupo_context_cards)

## AI Collections vs Human UI Collections

**Important Distinction:** This PRD defines **AI collections** through memory edges, which are distinct from **human UI collections** defined in PRD 73.

### AI Collections (Memory-Based)
- **Implementation:** `lupo_memory_edges` with specific predicates
- **Purpose:** Machine-readable relationships for AI reasoning
- **Edge Types:** `collection_contains`, `related_to`, `groups_with`, `semantically_similar`
- **Users:** AI agents, semantic search, recommendation systems
- **Characteristics:** Weighted, typed, directional relationships

### Human UI Collections (Table-Based)
- **Implementation:** `lupo_collections` tables (see PRD 73)
- **Purpose:** Visual organization for human browsing
- **Features:** Tabs, folders, hierarchies, visual navigation
- **Users:** End users browsing the web interface
- **Characteristics:** Human-readable, curated, manually organized

### Sync Strategy
AI collections should sync to human UI collections when:
1. **AI discovers strong semantic relationships** ??? Suggest to human curators
2. **Human creates manual collections** ??? Create corresponding memory edges
3. **High-confidence AI groupings** ??? Auto-create UI collections with human approval

See PRD 73 ??8 for detailed sync implementation.

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
| `lupo_hashtags` | Hashtag definitions and usage | `hashtag_id` | Central to tagging system |
| `lupo_hashtag_map` | Many-to-many relationships between items and hashtags | `hashtag_map_id` | Links items to hashtags |
| `lupo_metadata` | Generic metadata storage for any object | `metadata_id` | Flexible metadata for any entity |
| `lupo_contexts` | Context definitions and semantic organization | `context_id` | Core to semantic system |
| `lupo_context_edges` | Semantic relationships between contexts | `context_edge_id` | Links contexts semantically |
| `lupo_contexts_map` | Many-to-many context relationships | `context_map_id` | Links contexts to other entities |
| `lupo_context_cards` | Visual card representations of contexts | `context_card_id` | Links to `lupo_contexts` |
| `lupo_edges` | Polymorphic edge storage for any entity relationship | `edge_id` | Universal relationship storage |
| `lupo_edge_types` | Edge type definitions | `edge_type_id` | Defines edge relationship types |
| `lupo_edge_type_definitions` | Detailed edge type configurations | `definition_id` | Extends edge type definitions |

## Table Details

### `lupo_hashtags`

**Purpose:** Stores hashtag definitions and tracks their usage.

**Columns:**

| Column | Type | Nullable | Default | Description |
|--------|------|----------|---------|-------------|
| hashtag_id | BIGINT | NO | (application) | Primary key, generated via IdGenerator |
| hashtag | VARCHAR(255) | NO |  | Hashtag text (without #) |
| usage_count | INT | NO | 0 | Number of times used |
| created_ymdhis | BIGINT | NO | (application) | UTC timestamp YYYYMMDDHHIISS |
| updated_ymdhis | BIGINT | NO | (application) | UTC timestamp YYYYMMDDHHIISS |
| is_deleted | TINYINT | NO | 0 | Soft delete flag |
| deleted_ymdhis | BIGINT | YES | NULL | UTC timestamp when deleted |

**Indexes:**

| Index Name | Columns | Purpose |
|------------|---------|---------|
| idx_hashtags_tag | hashtag, is_deleted | Unique tag lookup |
| idx_hashtags_usage | usage_count, is_deleted | Popular hashtags |
| idx_hashtags_created | created_ymdhis, is_deleted | Recently created hashtags |

### `lupo_hashtag_map`

**Purpose:** Many-to-many relationship between any entity and hashtags.

**Columns:**

| Column | Type | Nullable | Default | Description |
|--------|------|----------|---------|-------------|
| hashtag_map_id | BIGINT | NO | (application) | Primary key, generated via IdGenerator |
| target_type | VARCHAR(32) | NO |  | Target entity type |
| target_id | BIGINT | NO |  | Target entity ID |
| hashtag_id | BIGINT | NO |  | Foreign reference to lupo_hashtags |
| created_ymdhis | BIGINT | NO | (application) | UTC timestamp YYYYMMDDHHIISS |
| is_deleted | TINYINT | NO | 0 | Soft delete flag |
| deleted_ymdhis | BIGINT | YES | NULL | UTC timestamp when deleted |

**Indexes:**

| Index Name | Columns | Purpose |
|------------|---------|---------|
| idx_hashtag_map_target | target_type, target_id, is_deleted | Entity's hashtags |
| idx_hashtag_map_hashtag | hashtag_id, is_deleted | Hashtag usage |

### `lupo_metadata`

**Purpose:** Generic metadata storage for any object type in the system.

**Columns:**

| Column | Type | Nullable | Default | Description |
|--------|------|----------|---------|-------------|
| metadata_id | BIGINT | NO | (application) | Primary key, generated via IdGenerator |
| target_type | VARCHAR(32) | NO |  | Target entity type |
| target_id | BIGINT | NO |  | Target entity ID |
| metadata_key | VARCHAR(255) | NO |  | Metadata key |
| metadata_value | TEXT | YES | NULL | Metadata value |
| value_type | VARCHAR(32) | YES | NULL | Value type: string, json, number |
| created_ymdhis | BIGINT | NO | (application) | UTC timestamp YYYYMMDDHHIISS |
| updated_ymdhis | BIGINT | NO | (application) | UTC timestamp YYYYMMDDHHIISS |
| is_deleted | TINYINT | NO | 0 | Soft delete flag |
| deleted_ymdhis | BIGINT | YES | NULL | UTC timestamp when deleted |

**Indexes:**

| Index Name | Columns | Purpose |
|------------|---------|---------|
| idx_metadata_target | target_type, target_id, metadata_key, is_deleted | Entity metadata lookup |
| idx_metadata_key | metadata_key, is_deleted | Key-based queries |

### `lupo_contexts`

**Purpose:** Defines semantic contexts for organizing and relating content.

**Columns:**

| Column | Type | Nullable | Default | Description |
|--------|------|----------|---------|-------------|
| context_id | BIGINT | NO | (application) | Primary key, generated via IdGenerator |
| context_name | VARCHAR(255) | NO |  | Unique context name |
| display_name | VARCHAR(255) | YES | NULL | Human-readable display name |
| description | TEXT | YES | NULL | Context description |
| context_type | VARCHAR(32) | NO | 'topic' | Type: topic, category, collection |
| parent_context_id | BIGINT | YES | NULL | Self-reference for hierarchy |
| created_by_actor_id | BIGINT | NO |  | Actor who created this context |
| created_ymdhis | BIGINT | NO | (application) | UTC timestamp YYYYMMDDHHIISS |
| updated_ymdhis | BIGINT | NO | (application) | UTC timestamp YYYYMMDDHHIISS |
| is_active | TINYINT | NO | 1 | Context active flag |
| is_deleted | TINYINT | NO | 0 | Soft delete flag |
| deleted_ymdhis | BIGINT | YES | NULL | UTC timestamp when deleted |

**Indexes:**

| Index Name | Columns | Purpose |
|------------|---------|---------|
| idx_contexts_name | context_name, is_deleted | Unique name lookup |
| idx_contexts_type | context_type, is_active, is_deleted | Type-based queries |
| idx_contexts_parent | parent_context_id, is_deleted | Hierarchy queries |

### `lupo_edges` (`{{prefix}}edges` in install SQL)

**Purpose:** Polymorphic semantic edges between **left** and **right** objects (string `*_object_type` + BIGINT `*_object_id`). Canonical DDL: **`install_new_lupopedia.sql`**. Relationships to `lupo_edge_types` (if used) are **application-managed**, not database FKs.

**Columns (summary ???????? see install for full list):**

| Column | Type | Nullable | Description |
|--------|------|----------|-------------|
| edge_id | BIGINT | NO | Primary key (`IdGenerator`) |
| left_object_type | VARCHAR(50) | NO | Source entity type (e.g. **`memory_node`** with **`left_object_id`** = **`lupo_memory_nodes.memory_node_id`** per **PRD 38** unified graph). **Do not** use **`actor_memory`** or **`lupo_actor_memory`** for new work ??? that table is **not** in 4.0.x install; legacy rows or imports MAY still carry the old type string until migrated. |
| left_object_id | BIGINT | NO | Source entity id |
| right_object_type | VARCHAR(50) | NO | Target entity type |
| right_object_id | BIGINT | NO | Target entity id |
| edge_type | VARCHAR(100) | NO | Relationship type string |
| edge_category | VARCHAR(100) | YES | Category |
| edge_description | TEXT | YES | Free-text description |
| channel_id, channel_key | BIGINT / VARCHAR | YES | Optional channel scope |
| domain_id | BIGINT | NO | Default 1 |
| weight_score | INT | NO | Integer score (prefer integer weights; avoid new DECIMAL in PRD examples) |
| sort_num | INT | NO | Ordering |
| actor_id | BIGINT | YES | Provenance actor |
| is_deleted, deleted_ymdhis | TINYINT / BIGINT | NO | Soft delete |
| created_ymdhis, updated_ymdhis | BIGINT | NO | Packed UTC |
| semantic_weight | DECIMAL(5,2) | YES | **Legacy / FLARE-era** decimal column in install; new code should prefer **`weight_score`** or a future **`weight_hundredths`** migration ???????? do not add new DECIMAL columns in PRDs |
| relationship_type | VARCHAR(64) | YES | e.g. semantic |
| bidirectional | TINYINT | NO | Legacy direction hint |
| context_scope | VARCHAR(100) | YES | Scope |
| properties | JSON | YES | Extra properties |
| flare_* | various | YES | FLARE protocol extension fields (see install) |
| edge_context | VARCHAR(64) | YES | **4D memory model (4.0.96+)** ???????? structural classification |
| edge_status | VARCHAR(32) | YES | **4D model** ???????? default `active`; `review` triggers `review_reason` |
| direction | VARCHAR(16) | NO | **4D model** ???????? default `unidirectional`; allowed values validated in application (e.g. `uni`, `bi`, `restricted`); portable SQL ???????? do not use MySQL ENUM in new DDL |
| review_reason | VARCHAR(64) | YES | When `edge_status` implies review (Option C routing) |

**Indexes (install):** `edges_idx_left`, `edges_idx_right`, `edges_idx_edge_type`, `edges_idx_actor`, `edges_idx_created`, `edges_idx_updated`, plus FLARE and composite indexes per install.

**Doctrine:** No FK constraints; validate **`edge_type`** and object types in application code. Use **`lupo_memory_edges`** for memory-native typed links (**PRD 38**); **`lupo_edges`** remains the general polymorphic semantic store (**PRD 37** section **2.0** for KAIROS vs memory graph split). **Unified runtime memory:** all new edges to actor-scoped memory MUST target **`memory_node`** / **`lupo_memory_nodes`**, not deprecated **`lupo_actor_memory`**.

## Cross-Namespace Dependencies

| Dependency | Direction | Purpose | Tables Involved |
|------------|------------|---------|------------------|
| 04_tags_metadata | This ???????? All namespaces | Tagging support | target_type/target_id pattern |
| 03_truth_knowledge | Core ???????? This | Truth tagging | hashtag_map for questions |
| 05_collections_navigation | Core ???????? This | Collection tagging | hashtag_map for collections |

## State Transitions

| State | Description | Transition To |
|--------|-------------|--------------|
| active | Normal operation | inactive, deleted (soft) |
| inactive | Temporarily disabled | active, deleted (soft) |
| deleted | Soft-deleted | N/A (can't be restored without explicit action) |

## Security & Privacy

Metadata can store any key-value pairs but is validated at application layer

Edge relationships are typed and validated

Soft delete preserves semantic relationships for audit

## Testing Requirements

Unit tests for tag creation and assignment

Integration tests for context hierarchy and edge traversal

Performance tests for metadata lookup and edge queries

Soft delete behavior verification

## Usage Patterns

```php
// Create context
$contextService = new ContextService();
$contextId = $contextService->create($actorId, $contextName, $description);

// Create edge
$edgeService = new EdgeService();
$edgeId = $edgeService->create($fromType, $fromId, $toType, $toId, $edgeTypeId);

// Add hashtag to entity
$hashtagService = new HashtagService();
$mapId = $hashtagService->addHashtag($targetType, $targetId, $hashtagText);

// Add metadata
$metadataService = new MetadataService();
$metadataId = $metadataService->set($targetType, $targetId, $key, $value);
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
