---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: prd
  version_when_written: "4.0.93"
  file_path_from_root: "lupo-docs/prd/04_tags_metadata.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/prd/04_tags_metadata.md"
  last_modified_utc: "20260330163000"
  channel_id: 42
  thread_id: "prd-grouped"
  actor_id: 102
  actor_name: "HEPHAESTUS"
  delegation_chain: "hephaestus:root|lilith:audit"
  artifact_type: "prd"
  artifact_kind: "database_namespace"
  purpose: "PRD for tagging, metadata, and semantic organization database tables"
  tags:
  - "prd"
  - "database"
  - "namespace"
  - "tags_metadata"
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
    - to: "lupo-docs/prd/03_truth_knowledge.md"
      type: references
      weight: 1.0
      reason: "Truth items use tags and metadata"
    - to: "lupo-docs/prd/05_collections_navigation.md"
      type: references
      weight: 1.0
      reason: "Collections use tags for organization"
lupopedia.footer:
  last_verified: "20260330163000"
  verified_by:
    actor_id: 102
    agent_name_identity: Cursor IDE Agent
  orchestrator: "hephaestus:root"
---

# PRD: Tagging, Metadata, and Semantic Organization Database Tables

## Overview

**Namespace Purpose:** Provides tagging, metadata management, and semantic organization capabilities for all content types. This namespace enables flexible categorization, search optimization, and content discovery.

**Primary Actors:** 
- Content taggers (via lupo_hashtags)
- Metadata managers (via lupo_metadata)
- Context organizers (via lupo_contexts)
- Semantic mappers (via lupo_edges)
- Card creators (via lupo_context_cards)

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

### `lupo_edges`

**Purpose:** Polymorphic edge storage for any entity relationship in the system.

**Columns:**

| Column | Type | Nullable | Default | Description |
|--------|------|----------|---------|-------------|
| edge_id | BIGINT | NO | (application) | Primary key, generated via IdGenerator |
| edge_type_id | BIGINT | NO |  | Foreign reference to lupo_edge_types |
| from_type | VARCHAR(32) | NO |  | Source entity type |
| from_id | BIGINT | NO |  | Source entity ID |
| to_type | VARCHAR(32) | NO |  | Target entity type |
| to_id | BIGINT | NO |  | Target entity ID |
| weight | DECIMAL(5,2) | YES | 1.0 | Edge weight for ranking |
| properties_json | JSON | YES | NULL | Additional edge properties |
| created_by_actor_id | BIGINT | NO |  | Actor who created this edge |
| created_ymdhis | BIGINT | NO | (application) | UTC timestamp YYYYMMDDHHIISS |
| updated_ymdhis | BIGINT | NO | (application) | UTC timestamp YYYYMMDDHHIISS |
| is_deleted | TINYINT | NO | 0 | Soft delete flag |
| deleted_ymdhis | BIGINT | YES | NULL | UTC timestamp when deleted |

**Indexes:**

| Index Name | Columns | Purpose |
|------------|---------|---------|
| idx_edges_from | from_type, from_id, edge_type_id, is_deleted | Outbound edges |
| idx_edges_to | to_type, to_id, edge_type_id, is_deleted | Inbound edges |
| idx_edges_weight | weight, is_deleted | Weight-based queries |

## Cross-Namespace Dependencies

| Dependency | Direction | Purpose | Tables Involved |
|------------|------------|---------|------------------|
| 04_tags_metadata | This → All namespaces | Tagging support | target_type/target_id pattern |
| 03_truth_knowledge | Core → This | Truth tagging | hashtag_map for questions |
| 05_collections_navigation | Core → This | Collection tagging | hashtag_map for collections |

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
