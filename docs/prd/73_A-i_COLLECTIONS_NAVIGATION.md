---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/prd/73_A-i_COLLECTIONS_NAVIGATION.md
  web_path: https://www.lupopedia.com/lupopedia/docs/prd/73_A-i_COLLECTIONS_NAVIGATION.md
  status: active
  when_updated: '20260817105400'
  trust_tier: canonical
  questions_toon: null
  memory_toon: memory/development/canonical/1026/04/73_collections_navigation.toon
  atoms_toon: null
  transcript_jsonl: 0/development/prd_files/collections-navigation
  artifact_type: prd
  artifact_kind: specification
  channel_key: development
  federation_node_id: 0
  thread_key: null
  lupopedia.schema: prd
  prd_cluster: 00_A-i_73_A-i
  title: 'PRD 73: Collections, Tabs, Navigation, and Organization Database Tables'
  summary: 'Collections, tabs, navigation tables, and Portable Semantic Collections (PSC) for cross-node JSON export/import.'
---
# PRD: Collections, Tabs, Navigation, and Organization Database Tables

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

**Namespace Purpose:** Provides collection management, tabbed interfaces, navigation structures, and organizational hierarchies. This namespace enables users to organize, browse, and navigate content efficiently.

Collections MAY represent albums or creative projects. Webpage Collections drive the semantic navbar (PRD 21). Artifact Collections (album / project / set) bind to Color Group `collection_name` for the Artifact Lineage Widget (**PRD 92**). Same `lupo_collections` / `lupo_collection_tabs` tables. Navbar chrome MUST NOT appear inside artifact embeds.

**Portable Semantic Collections (PSC)** are a first-class collection feature (see **section 10**). A collection MAY be exported as JSON and imported on another Lupopedia node. PSC carries collection structure, color identity, artifact lineage, remix chains, attribution, artifact kinds, and originating node metadata. Local-only collections remain valid when PSC is off.

**Primary Actors:** 
- Collection creators (via lupo_collections)
- Tab managers (via lupo_collection_tabs)
- Navigation builders (via lupo_paths)
- Folder organizers (via lupo_folder_map)
- Map creators (via lupo_collection_map)

## Human UI Collections vs AI Memory Collections

**Important Distinction:** This PRD defines **human UI collections** through database tables, which are distinct from **AI memory collections** defined in PRD 72.

### Human UI Collections (Table-Based)
- **Implementation:** 6 existing tables (`lupo_collections`, `lupo_collection_tabs`, etc.)
- **Purpose:** Visual organization for human browsing
- **Features:** Tabs, folders, hierarchies, visual navigation
- **Users:** End users browsing the web interface
- **Status:** ??? Already implemented and working

### AI Memory Collections (Edge-Based)
- **Implementation:** `lupo_memory_edges` with specific predicates
- **Purpose:** Machine-readable relationships for AI reasoning
- **Edge Types:** `collection_contains`, `related_to`, `groups_with`, `semantically_similar`
- **Users:** AI agents, semantic search, recommendation systems
- **Status:** ???? Defined in PRD 72, needs implementation

### Why Two Systems?
1. **Different Users:** Humans need visual organization; AI needs graph relationships
2. **Different Use Cases:** Browsing vs reasoning
3. **Different Performance:** UI needs fast lookups; AI needs traversable graphs
4. **Different Semantics:** Manual curation vs algorithmic discovery

### The Context Drift Problem (Why Collections Exist)

Humans and builder agents drift. A single thread starts as a code implementation session, shifts into prompt refinement, then drifts into editorial writing. All three types of content end up in the same thread.

Without active organization, the result is "that one thread where everything happened" ??? impossible to use as structured context for future agents or operators. THOTH and VISH (PRD 32 sec. 2.3.2) address this at the monitoring layer. Collections and tabs provide the structure they reclassify into.

**The design contract between monitoring agents and the collections system:**

| Monitoring agent | Detects | Suggests |
|-----------------|---------|---------|
| THOTH | Constitutional violations | [ALERT] in stream |
| VISH (planned) | Context drift within a thread | Reclassification: "Collection X, Tab Y" |

Collections are not just UI organization ??? they are the destination for context-corrected content. When VISH determines that a message block belongs in "blog/prompt refinement" rather than "implementation," it refers to the `lupo_collections` and `lupo_collection_tabs` schema defined in this PRD as the target structure.

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
| 05_collections_navigation | This ???????? 06_content_management | Content organization | target_id columns |
| 05_collections_navigation | This ???????? 04_tags_metadata | Collection tagging | hashtag_map for collections |
| 01_core_identity | Core ???????? This | Actor ownership | actor_id columns |

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

PSC import MUST sanitize URLs, validate JSON against the PSC schema, reject remote code injection, and MUST NOT auto-execute scripts inside artifacts (section 10).

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

## 8. Sync Strategy: Human UI Collections ??? AI Memory Collections

### 8.1 Purpose
Create bidirectional synchronization between human-curated collections (UI tables) and AI-discovered collections (memory edges) to leverage both manual curation and algorithmic discovery.

### 8.2 Sync Triggers

#### 8.2.1 Human ??? AI Sync
When a user creates/updates a collection:
1. **Create memory edges** for each item in the collection
2. **Edge type:** `collection_contains`
3. **Direction:** collection_node ??? item_node
4. **Weight:** 1.0 (human curation = high confidence)

```php
// Sync human collection to memory edges
$syncService = new CollectionMemorySyncService();
$syncService->syncCollectionToMemory($collectionId, $actorId);
```

#### 8.2.2 AI ??? Human Sync
When AI discovers strong relationships:
1. **Analyze edge confidence** (weight > 0.8)
2. **Group related items** using connected components
3. **Suggest collections** to human curators
4. **Auto-create** with approval for high-confidence groups

```php
// Sync AI discoveries to human collections
$discoveryService = new AICollectionDiscoveryService();
$suggestions = $discoveryService->suggestCollections($confidenceThreshold = 0.8);
```

### 8.3 Implementation Architecture

#### 8.3.1 Sync Service Classes
- `CollectionMemorySyncService` - Human ??? AI sync
- `AICollectionDiscoveryService` - AI ??? Human discovery
- `CollectionApprovalService` - Human approval workflow
- `MemoryEdgeAnalyzer` - Edge confidence calculation

#### 8.3.2 Memory Edge Schema for Collections
```json
{
  "edge_type": "collection_contains",
  "from_memory_node_id": "collection_node_id",
  "to_memory_node_id": "item_node_id",
  "weight": 1.0,
  "context_json": {
    "source": "human_curated",
    "collection_id": 12345,
    "added_by_actor_id": 1,
    "added_ymdhis": 20260412140000
  }
}
```

### 8.4 Sync Workflow

#### 8.4.1 Real-time Sync (Critical)
- **Collection created** ??? Immediate memory edge creation
- **Item added to collection** ??? Immediate edge creation
- **Collection deleted** ??? Soft-delete corresponding edges

#### 8.4.2 Batch Sync (Hourly/Daily)
- **AI discovery analysis** ??? Group suggestions
- **Confidence threshold evaluation** ??? Filter weak connections
- **Human curator notifications** ??? Review suggestions

#### 8.4.3 Conflict Resolution
- **Human curation takes precedence** over AI suggestions
- **Merge overlapping collections** with human approval
- **Resolve naming conflicts** using collection metadata

### 8.5 Performance Considerations

#### 8.5.1 Indexing Requirements
- `lupo_memory_edges` index on `(edge_type, from_memory_node_id, weight)`
- `lupo_memory_edges` index on `(to_memory_node_id, edge_type)`
- Fast lookup for collection membership queries

#### 8.5.2 Caching Strategy
- **Cache collection membership** in Redis for UI performance
- **Cache AI suggestions** for quick curator review
- **Incremental sync** to avoid full reprocessing

### 8.6 Monitoring & Metrics

#### 8.6.1 Sync Health Metrics
- **Sync latency**: Time from UI change to memory edge creation
- **Discovery accuracy**: Percentage of AI suggestions accepted
- **Collection coverage**: Items in collections vs total items

#### 8.6.2 Quality Metrics
- **Human-AI agreement rate**: Overlap between curated and discovered groups
- **Collection engagement**: Usage statistics for synced collections
- **Edge confidence distribution**: Statistical analysis of AI weights

### 8.7 API Endpoints

#### 8.7.1 Sync Management
```
POST /api/collections/{id}/sync-to-memory
GET /api/collections/sync-status
POST /api/collections/process-ai-suggestions
```

#### 8.7.2 Discovery API
```
GET /api/collections/ai-suggestions
POST /api/collections/approve-suggestion
GET /api/collections/related-items/{id}
```

### 8.8 Future Enhancements

#### 8.8.1 Advanced AI Features
- **Semantic similarity clustering** using embeddings
- **Temporal collection evolution** tracking
- **Personalized collection recommendations**

#### 8.8.2 Human-AI Collaboration
- **Collaborative filtering** between curators
- **Explainable AI** for collection suggestions
- **Gamification** of collection curation

---

**Implementation Priority:**
1. **Phase 1**: Human ??? AI sync (immediate edges on collection changes)
2. **Phase 2**: AI discovery service (semantic clustering)
3. **Phase 3**: Approval workflow and UI integration
4. **Phase 4**: Advanced AI features and personalization

## 9. Collection Authority Doctrine

**Collection authority order:**

1. **Database collections** ??? authoritative
2. **Imported collections** ??? authoritative
3. **CHIRON provisional collections** ??? temporary fallback only

**Replacement rule:**
When a higher authority source becomes available, it replaces the lower one.

### Core Principles

- Collections are DB-authoritative when available.
- If DB collections are unavailable, CHIRON may create provisional collection structures from filesystem and documentation signals.
- These provisional collections are temporary and must be replaced when authoritative database collections become available.
- VISH reads provisional collections in fallback mode but does not treat them as canonical. VISH must not override authoritative DB collections with guesses.

> **CHIRON may scaffold. VISH may read. Database decides.**

## 10. Portable Semantic Collections (PSC)

**RULE 73.COLLECTIONS -- Portable Semantic Collections (PSC):** Cross-node semantic portability of collections is required for federation compliance when the installer enables PSC.

PSC is a first-class collection feature. It is not a separate product. It sits on `lupo_collections` / `lupo_collection_tabs` / tab maps. Webpage Collections (PRD 21) and artifact Collections (PRD 92) MAY both be exported as PSC JSON.

This section is **PRD-level**. It does **not** add install SQL. HEX6 MUST NOT be guessed. Color is not a LUP KEY token. HEX6 is six digits with no `#`. HEX5 is not a color.

### 10.1 Summary

Portable Semantic Collections (PSC) allow any Lupopedia collection to be exported as a JSON artifact and imported into any other Lupopedia node. PSC enables cross-node semantic portability of:

- collection structure
- color identity
- artifact lineage
- remix chains
- attribution
- artifact kinds
- federated node metadata

PSC transforms collections from local-only constructs into cross-domain semantic playlists.

### 10.2 JSON schema (required)

```json
{
  "collection_name": "string",
  "color_group": "string",
  "color_hex": "string",
  "node_id": "integer",
  "collection_id": "integer",
  "links": [
    {
      "title": "string",
      "url": "string",
      "artifact_kind": "string",
      "lineage_parent": "string or null",
      "lineage_children": ["string"],
      "license": "string",
      "color_group": "string"
    }
  ]
}
```

**Required fields**

| Field | Meaning |
|-------|---------|
| `collection_name` | Semantic grouping name (binds to Color Group `collection_name` / `lupo_collections`) |
| `color_group` | Identity group (GroupColor family, PRD 90) |
| `color_hex` | Visual identity. This is **HEX6**: six digits, no `#`. Empty means pending. MUST NOT be guessed on import. |
| `node_id` | Originating federation node (`federation_node_id`) |
| `collection_id` | Originating collection id on that node (not reused as the local PK) |
| `links[]` | Artifacts, pages, or external resources |

**Lineage fields (per link)**

- `lineage_parent` -- parent URL or artifact id string, or null
- `lineage_children[]` -- child URLs or artifact id strings

These fields allow PSC to preserve remix chains, derivative relationships, and semantic ancestry across nodes.

**`artifact_kind`:** `track | video | audio | document | image | code | other` (PRD 92), or a page kind such as `page` for webpage collection items.

**`license`:** first supported license is CC-BY; other licenses MAY appear as strings. Do not invent license terms.

### 10.3 Import behavior (cross-node federation)

**1. Color identity sync**

- If the color group exists locally, use it.
- If not, create it (GroupColor + ColorName / collection binding per PRD 90 / Color Group unification). Do **not** invent HEX6.
- If `color_hex` differs from local HEX6, apply Color Identity Sync Mode (installer option):
  - **strict** -- reject the import (or reject that color field)
  - **permissive** -- keep the local HEX6
  - **merge** -- installer-defined merge; still MUST NOT guess a new HEX6

**2. Collection creation**

- Allocate a **new** local `collection_id` via `IdGenerator::generate()`.
- Keep the same `collection_name`.
- Keep the same `color_group`.
- Do not reuse the originating `collection_id` as the local primary key.

**3. Artifact resolution**

- If the artifact exists locally, link it into the collection.
- If remote, create an external stub (URL + metadata; no fetch-and-execute).
- If unknown, create a placeholder artifact.

**4. Lineage sync**

- Import parent/child relationships from `lineage_parent` / `lineage_children`.
- Preserve remix chains.
- Preserve attribution (`license` and related fields).
- Webpage lineage stays webpage lineage. Artifact lineage stays PRD 92. PSC MAY carry both in `links[]`.

**5. Federation metadata**

- Store originating `node_id`.
- Mark the collection as PSC-imported (application metadata; no DDL from this section).
- PSC-imported collections count as **Imported collections** in section 9 (authoritative when present).

### 10.4 Installer options

Add to the installer / node config (PRD 27 / PRD 33 consume these flags; this PRD defines the meaning):

| Option | Values | Meaning |
|--------|--------|---------|
| Enable Portable Semantic Collections | ON / OFF | ON: export/import JSON. OFF: local collections only. |
| Allow Cross-Node Imports | ON / OFF | ON: federation imports. OFF: isolated node (export MAY still work). |
| Color Identity Sync Mode | strict / permissive / merge | HEX6 conflict handling. Never guess HEX6. |

Default for a fresh isolated node MAY be PSC OFF until the operator enables it. Federation-compliant nodes MUST be able to turn PSC ON.

### 10.5 UI requirements

**Export:** button label `Download Collection (PSC)`. Generates a JSON file. Includes color identity and lineage metadata.

**Import:** button label `Import Collection (PSC)`. Validates JSON against this schema. Shows a preview of:

- color group
- number of artifacts
- lineage relationships
- external links

Import MUST fail closed on invalid JSON, failed URL sanitization, or disallowed schemes.

### 10.6 Security requirements

- PSC import MUST sanitize URLs (allow `http` and `https` only; reject `javascript:`, `data:`, and file schemes).
- PSC import MUST validate JSON against the schema above (required fields, types).
- PSC import MUST prevent remote code injection.
- PSC import MUST NOT auto-execute scripts inside artifacts.
- PSC import MUST NOT fetch remote HTML/JS and eval it.
- HEX6 in `color_hex` MUST be six hex digits or empty. Reject other shapes. Do not invent a value.

### 10.7 Coexistence

- MUST NOT break local collections when PSC is OFF.
- MUST NOT break The Eye webpage lineage (PRD 28) or the Color Registry homepage.
- MUST NOT break artifact lineage embeds (PRD 92).
- MUST NOT break Crafty tracking (PRD 33 / PRD 50).
- Navbar still MUST NOT appear inside artifact embeds.

### 10.8 Cross-references

- PRD 90 -- Color identity. HEX6 never guessed.
- PRD 92 -- Artifact Lineage Widget; `artifact_kind` and remix chains.
- PRD 21 / PRD 28 -- Color Group `collection_name` unification; Collection Selector.
- PRD 34 -- federation nodes and trust.
- PRD 27 / PRD 33 -- installer flags for PSC.
- PRD 11 -- collection_selected and artifact lineage events MAY fire on PSC import/export; do not invent counts.


