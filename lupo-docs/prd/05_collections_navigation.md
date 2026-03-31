---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: prd
  version_when_written: "4.0.93"
  file_path_from_root: "lupo-docs/prd/05_collections_navigation.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/prd/05_collections_navigation.md"
  last_modified_utc: "20260330163000"
  channel_id: 42
  thread_id: "prd-grouped"
  actor_id: 102
  actor_name: "HEPHAESTUS"
  delegation_chain: "hephaestus:root|lilith:audit"
  artifact_type: "prd"
  artifact_kind: "database_namespace"
  purpose: "PRD for collections, tabs, navigation, and organization database tables"
  tags:
  - "prd"
  - "database"
  - "namespace"
  - "collections_navigation"
lupopedia.edges:
  outbound_edges:
    - to: "lupo-docs/database/lupopedia/tables/"
      type: references
      weight: 1.0
      reason: "Detailed table documentation"
    - to: "lupo-docs/prd/04_tags_metadata.md"
      type: references
      weight: 1.0
      reason: "Collections use tags for organization"
    - to: "lupo-docs/prd/06_content_management.md"
      type: references
      weight: 1.0
      reason: "Collections contain content"
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

# PRD: Collections, Tabs, Navigation, and Organization Database Tables

## Overview

**Namespace Purpose:** Provides collection management, tabbed interfaces, navigation structures, and organizational hierarchies. This namespace enables users to organize, browse, and navigate content efficiently.

**Primary Actors:** 
- Collection creators (via lupo_collections)
- Tab managers (via lupo_collection_tabs)
- Navigation builders (via lupo_paths)
- Folder organizers (via lupo_folder_map)
- Map creators (via lupo_collection_map)

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
| `lupo_collections` | Collection definitions and metadata | `collection_id` | Central to collection system |
| `lupo_collection_tabs` | Tab definitions within collections | `tab_id` | Links to `lupo_collections` |
| `lupo_collection_links` | Links between collections | `collection_link_id` | Links collections to collections |
| `lupo_collection_map` | Many-to-many collection relationships | `collection_map_id` | Links collections to entities |
| `lupo_collection_tab_map` | Maps tabs to entities | `tab_map_id` | Links tabs to content |
| `lupo_collection_tab_paths` | Navigation paths for tabs | `tab_path_id` | Links tabs to navigation |
| `lupo_paths` | Navigation path definitions | `path_id` | Core navigation system |
| `lupo_paths_summary` | Aggregated navigation statistics | `summary_id` | Links to `lupo_paths` |
| `lupo_folder_map` | Folder hierarchy and organization | `folder_map_id` | Organizes content in folders |
| `lupo_rolls` | Roll-based content organization | `roll_id` | Alternative organization method |

## Table Details

### `lupo_collections`

**Purpose:** Defines collections for organizing and grouping content.

**Columns:**

| Column | Type | Nullable | Default | Description |
|--------|------|----------|---------|-------------|
| collection_id | BIGINT | NO | (application) | Primary key, generated via IdGenerator |
| actor_id | BIGINT | NO |  | Foreign reference to lupo_actors |
| collection_name | VARCHAR(255) | NO |  | Unique collection name |
| display_name | VARCHAR(255) | YES | NULL | Human-readable display name |
| description | TEXT | YES | NULL | Collection description |
| collection_type | VARCHAR(32) | NO | 'user' | Type: user, system, featured |
| visibility | VARCHAR(32) | NO | 'private' | Visibility: private, public, shared |
| created_ymdhis | BIGINT | NO | (application) | UTC timestamp YYYYMMDDHHIISS |
| updated_ymdhis | BIGINT | NO | (application) | UTC timestamp YYYYMMDDHHIISS |
| item_count | INT | NO | 0 | Cached item count |
| is_pinned | TINYINT | NO | 0 | Whether collection is pinned |
| is_deleted | TINYINT | NO | 0 | Soft delete flag |
| deleted_ymdhis | BIGINT | YES | NULL | UTC timestamp when deleted |

**Indexes:**

| Index Name | Columns | Purpose |
|------------|---------|---------|
| idx_collections_actor | actor_id, visibility, is_deleted | Actor's collections |
| idx_collections_type | collection_type, visibility, is_deleted | Type-based queries |
| idx_collections_pinned | is_pinned, updated_ymdhis, is_deleted | Pinned collections |

### `lupo_collection_tabs`

**Purpose:** Defines tabs within collections for organized content display.

**Columns:**

| Column | Type | Nullable | Default | Description |
|--------|------|----------|---------|-------------|
| tab_id | BIGINT | NO | (application) | Primary key, generated via IdGenerator |
| collection_id | BIGINT | NO |  | Foreign reference to lupo_collections |
| tab_name | VARCHAR(255) | NO |  | Tab name |
| display_name | VARCHAR(255) | YES | NULL | Human-readable display name |
| tab_type | VARCHAR(32) | NO | 'content' | Type: content, search, external |
| content_url | VARCHAR(512) | YES | NULL | URL for external tab content |
| sort_order | INT | NO | 0 | Display order within collection |
| created_ymdhis | BIGINT | NO | (application) | UTC timestamp YYYYMMDDHHIISS |
| updated_ymdhis | BIGINT | NO | (application) | UTC timestamp YYYYMMDDHHIISS |
| is_active | TINYINT | NO | 1 | Tab active flag |
| is_deleted | TINYINT | NO | 0 | Soft delete flag |
| deleted_ymdhis | BIGINT | YES | NULL | UTC timestamp when deleted |

**Indexes:**

| Index Name | Columns | Purpose |
|------------|---------|---------|
| idx_tabs_collection | collection_id, sort_order, is_active, is_deleted | Collection tabs |
| idx_tabs_type | tab_type, is_active, is_deleted | Type-based queries |

### `lupo_paths`

**Purpose:** Defines navigation paths for the system.

**Columns:**

| Column | Type | Nullable | Default | Description |
|--------|------|----------|---------|-------------|
| path_id | BIGINT | NO | (application) | Primary key, generated via IdGenerator |
| actor_id | BIGINT | NO |  | Foreign reference to lupo_actors |
| path_name | VARCHAR(255) | NO |  | Unique path name |
| path_pattern | VARCHAR(512) | NO |  | URL pattern or route |
| path_type | VARCHAR(32) | NO | 'collection' | Type: collection, content, external |
| target_type | VARCHAR(32) | YES | NULL | Target entity type |
| target_id | BIGINT | YES | NULL | Target entity ID |
| created_ymdhis | BIGINT | NO | (application) | UTC timestamp YYYYMMDDHHIISS |
| updated_ymdhis | BIGINT | NO | (application) | UTC timestamp YYYYMMDDHHIISS |
| access_count | INT | NO | 0 | Number of times accessed |
| is_active | TINYINT | NO | 1 | Path active flag |
| is_deleted | TINYINT | NO | 0 | Soft delete flag |
| deleted_ymdhis | BIGINT | YES | NULL | UTC timestamp when deleted |

**Indexes:**

| Index Name | Columns | Purpose |
|------------|---------|---------|
| idx_paths_actor | actor_id, path_type, is_active | Actor's paths |
| idx_paths_pattern | path_pattern, is_active | Pattern matching |
| idx_paths_access | access_count, is_active | Popular paths |

### `lupo_folder_map`

**Purpose:** Organizes content into hierarchical folder structures.

**Columns:**

| Column | Type | Nullable | Default | Description |
|--------|------|----------|---------|-------------|
| folder_map_id | BIGINT | NO | (application) | Primary key, generated via IdGenerator |
| actor_id | BIGINT | NO |  | Foreign reference to lupo_actors |
| folder_name | VARCHAR(255) | NO |  | Folder name |
| parent_folder_id | BIGINT | YES | NULL | Self-reference for hierarchy |
| folder_path | VARCHAR(1024) | NO |  | Full folder path |
| target_type | VARCHAR(32) | YES | NULL | Target entity type in folder |
| target_id | BIGINT | YES | NULL | Target entity ID in folder |
| created_ymdhis | BIGINT | NO | (application) | UTC timestamp YYYYMMDDHHIISS |
| updated_ymdhis | BIGINT | NO | (application) | UTC timestamp YYYYMMDDHHIISS |
| item_count | INT | NO | 0 | Cached item count |
| is_deleted | TINYINT | NO | 0 | Soft delete flag |
| deleted_ymdhis | BIGINT | YES | NULL | UTC timestamp when deleted |

**Indexes:**

| Index Name | Columns | Purpose |
|------------|---------|---------|
| idx_folder_map_actor | actor_id, parent_folder_id, is_deleted | Actor's folders |
| idx_folder_map_path | folder_path, is_deleted | Path-based lookup |
| idx_folder_map_parent | parent_folder_id, is_deleted | Child folder lookup |

## Cross-Namespace Dependencies

| Dependency | Direction | Purpose | Tables Involved |
|------------|------------|---------|------------------|
| 05_collections_navigation | This → 06_content_management | Content organization | target_id columns |
| 05_collections_navigation | This → 04_tags_metadata | Collection tagging | hashtag_map for collections |
| 01_core_identity | Core → This | Actor ownership | actor_id columns |

## State Transitions

| State | Description | Transition To |
|--------|-------------|--------------|
| active | Normal operation | inactive, deleted (soft) |
| inactive | Temporarily disabled | active, deleted (soft) |
| deleted | Soft-deleted | N/A (can't be restored without explicit action) |

## Security & Privacy

Collection visibility controls access at application layer

Folder paths are validated for security

Soft delete preserves organizational structure for audit

## Testing Requirements

Unit tests for collection creation and tab management

Integration tests for navigation path resolution

Performance tests for folder hierarchy traversal

Soft delete behavior verification

## Usage Patterns

```php
// Create collection
$collectionService = new CollectionService();
$collectionId = $collectionService->create($actorId, $collectionName, $visibility);

// Add tab to collection
$tabService = new CollectionTabService();
$tabId = $tabService->createTab($collectionId, $tabName, $tabType);

// Create navigation path
$pathService = new PathService();
$pathId = $pathService->create($actorId, $pathName, $pattern, $targetType, $targetId);

// Create folder
$folderService = new FolderMapService();
$folderId = $folderService->create($actorId, $folderName, $parentFolderId);
```
