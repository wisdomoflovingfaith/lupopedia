---
architect: Captain Wolfie
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 4.0.15
dialog:
  speaker: CURSOR
  target: @everyone
  message: "Added WOLFIE Header v4.0.0 for documentation consistency."
tags:
  categories: ["documentation", "semantic-navigation"]
  collections: ["core-docs"]
  channels: ["dev"]
file:
  title: "Semantic Navigation System"
  description: "How Lupopedia converts user navigation into semantic atoms and edges"
  version: "4.0.1"
  status: published
  author: "Captain Wolfie"
---

# ðŸŒ Semantic Navigation System

## Overview
The Semantic Navigation System transforms user-created navigation patterns into a structured knowledge graph using semantic concepts and relationships. It automatically generates and maintains a dynamic ontology that evolves with community usage and content organization.

## Core Concepts

### Tab-Path
A hierarchical label created by users when organizing content:
```
Books â†’ Bible Books â†’ Old Testament
```

### Semantic Atom
A concept derived from tab-paths, representing categories or topics.

### Edge
A relationship between content and atoms, with weights indicating community consensus.

# ðŸ§  Semantic Layer Specification

## 1. ATOMS TABLE
Atoms represent *concepts* extracted from navigation, content, or manual creation.

### **Table: `atoms`**
```sql
atom_id            BIGINT UNSIGNED PRIMARY KEY
node_id            BIGINT UNSIGNED NOT NULL
slug               VARCHAR(255) NOT NULL
label              VARCHAR(255) NOT NULL
description        TEXT NULL
created_at         BIGINT UNSIGNED NOT NULL
updated_at         BIGINT UNSIGNED NOT NULL
deleted_at         BIGINT UNSIGNED NULL
```

### Notes:
- `slug` is canonical (lowercase, hyphens)
- `label` is humanâ€‘friendly
- `node_id` allows federation
- softâ€‘delete is required for semantic stability

## 2. EDGE TYPES
Edge types define the *meaning* of a relationship.

### **Table: `edge_types`**
```sql
edge_type_id       BIGINT UNSIGNED PRIMARY KEY
slug               VARCHAR(100) NOT NULL UNIQUE
label              VARCHAR(255) NOT NULL
description        TEXT NULL
directional        TINYINT(1) DEFAULT 0
weight_default     INT DEFAULT 10
created_at         BIGINT UNSIGNED NOT NULL
```

### Recommended default edge types:
| slug | label | directional | meaning |
|------|--------|-------------|---------|
| `related` | Related To | 0 | general semantic similarity |
| `parent_of` | Parent Of | 1 | hierarchical |
| `child_of` | Child Of | 1 | inverse of parent |
| `part_of` | Part Of | 1 | structural |
| `mentions` | Mentions | 1 | content references |
| `tagged_as` | Tagged As | 1 | content â†’ atom |
| `co_occurs` | Coâ€‘Occurs With | 0 | appears together often |
| `sequence_next` | Next In Sequence | 1 | chapters, steps |

## 3. EDGES TABLE
This is the heart of the semantic graph.

### **Table: `edges`**
```sql
edge_id            BIGINT UNSIGNED PRIMARY KEY

source_type        ENUM('content','atom','collection','user') NOT NULL
source_id          BIGINT UNSIGNED NOT NULL

target_type        ENUM('content','atom','collection','user') NOT NULL
target_id          BIGINT UNSIGNED NOT NULL

edge_type_id       BIGINT UNSIGNED NOT NULL

weight             INT NOT NULL DEFAULT 10
confidence         FLOAT DEFAULT 1.0

created_at         BIGINT UNSIGNED NOT NULL
updated_at         BIGINT UNSIGNED NOT NULL
deleted_at         BIGINT UNSIGNED NULL
```

### Notes:
- Polymorphic edges = maximum flexibility  
- `confidence` is optional but useful for AIâ€‘generated edges  
- Softâ€‘delete allows reversible ontology changes  
- No foreign keys for performance and flexibility

## 4. TAB PATHS
This is how Lupopedia extracts semantic signals from user navigation.

### **Table: `tab_paths`**
```sql
tab_path_id        BIGINT UNSIGNED PRIMARY KEY
content_id         BIGINT UNSIGNED NOT NULL
path               VARCHAR(500) NOT NULL
depth              INT NOT NULL
created_by_user_id BIGINT UNSIGNED NULL
created_at         BIGINT UNSIGNED NOT NULL
```

### Example:
If a user navigates:
```
Bible â†’ New Testament â†’ 1 Corinthians â†’ Chapter 13
```

The stored path is:
```
bible/new-testament/1-corinthians/chapter-13
```

## 5. SEMANTIC SIGNALS (Optional but powerful)
This table stores raw signals before they are aggregated into atoms/edges.

### **Table: `semantic_signals`**
```sql
signal_id          BIGINT UNSIGNED PRIMARY KEY
content_id         BIGINT UNSIGNED NOT NULL
signal_type        ENUM('tab_path','co_view','co_click','manual','ai') NOT NULL
payload            TEXT NOT NULL
weight             INT DEFAULT 1
created_at         BIGINT UNSIGNED NOT NULL
```

This allows:
- AI agents to propose edges  
- User behavior to accumulate  
- Future ML to refine the graph  

## 6. Weight Decay Rules

### Decay formula:
```
new_weight = floor(weight * 0.98)
```

Run during GC when:
- Server load is low  
- rand(1,10) == 7  

### Decay applies to:
- edges  
- semantic_signals (optional)

## 7. Orphan Handling

When an atom is softâ€‘deleted:

1. All edges pointing to it are softâ€‘deleted  
2. Children are reâ€‘routed to a special atom:  
   ```
   atom.slug = 'orphaned'
   ```
3. A semantic_signal is logged for future review

## 8. Semantic Extraction Pipeline

### Step 1 â€” Capture tab_path  
Stored in `tab_paths`.

### Step 2 â€” Normalize  
Split by `/`, slugify each segment.

### Step 3 â€” Create atoms  
If atom doesn't exist â†’ create it.

### Step 4 â€” Create edges  
For each segment:

```
content â†’ atom (tagged_as)
atom â†’ parent_atom (parent_of)
```

### Step 5 â€” Adjust weights  
Increment weight for repeated patterns.

### Step 6 â€” Decay  
GC handles longâ€‘term balancing.

## System Architecture

### 1. Data Collection Layer
- Tracks user organization in `collection_tab` and `collection_tab_map`
- Captures content relationships and hierarchy

### 2. Processing Pipeline
1. **Tab-Path Extraction**
   - Reconstructs full navigation paths
   - Associates paths with content and users

2. **Path Normalization**
   - Converts to lowercase
   - Replaces spaces with hyphens
   - Removes special characters
   - Standardizes format

3. **Aggregation Engine**
   - Counts distinct users per path
   - Calculates semantic weights
   - Applies threshold filtering

4. **Knowledge Graph Construction**
   - Creates/updates atoms
   - Establishes content-atom relationships
   - Builds hierarchical relationships

## Database Schema

The semantic navigation system leverages the existing `atoms` and `edges` tables, extending them with specific usage patterns for navigation-derived semantics.

### Atoms Table (Existing)
```sql
CREATE TABLE `{PREFIX}atoms` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `node_id` BIGINT UNSIGNED NOT NULL,
    `slug` VARCHAR(255) NOT NULL,
    `title` VARCHAR(255) NOT NULL,
    `summary` TEXT NULL,
    `toon` TEXT NULL,
    `toon_format` VARCHAR(20) DEFAULT 'toon',
    `origin` VARCHAR(50) DEFAULT 'manual' COMMENT 'manual, tab_path, import, etc.',
    `created_utc` BIGINT UNSIGNED NOT NULL,
    `updated_utc` BIGINT UNSIGNED NOT NULL,
    `is_deleted` BOOLEAN NOT NULL DEFAULT FALSE,
    `deleted_at` BIGINT UNSIGNED NULL,
    UNIQUE KEY `unique_atom_slug_node` (`node_id`, `slug`),
    INDEX `idx_node` (`node_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

### Edges Table (Existing)
```sql
CREATE TABLE `{PREFIX}edges` (
    `edge_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `node_id` BIGINT UNSIGNED NOT NULL COMMENT 'Edges are node-scoped',
    `source_type` VARCHAR(50) NOT NULL,
    `source_id` BIGINT UNSIGNED NOT NULL,
    `target_type` VARCHAR(50) NOT NULL,
    `target_id` BIGINT UNSIGNED NOT NULL,
    `edge_type` VARCHAR(50) NOT NULL,
    `weight` FLOAT DEFAULT NULL,
    `properties` JSON NULL,
    `created_utc` BIGINT UNSIGNED NOT NULL,
    `updated_utc` BIGINT UNSIGNED NOT NULL,
    `is_deleted` BOOLEAN NOT NULL DEFAULT FALSE,
    `deleted_at` BIGINT UNSIGNED NULL,
    UNIQUE KEY `unique_edge` (`node_id`, `source_type`, `source_id`, `target_type`, `target_id`, `edge_type`),
    INDEX `idx_source` (`source_type`, `source_id`),
    INDEX `idx_target` (`target_type`, `target_id`),
    INDEX `idx_node` (`node_id`),
    INDEX `idx_edge_type` (`edge_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
  COMMENT='Polymorphic semantic graph edges.';
```

## Implementation Notes

### Atom Creation
- Atoms are created in the `atoms` table with `origin = 'tab_path'`
- The `slug` is derived from the normalized tab-path (e.g., 'books/bible-books')
- The `title` is derived from the last segment of the path (e.g., 'Bible Books')

### Edge Creation
- Edges connect content to atoms using the `categorized_as` relationship type
- The `weight` represents the semantic strength (number of distinct users)
- Additional metadata is stored in the `properties` JSON field
  - `source`: 'tab_path'
  - `tab_path`: The original normalized path
  - `user_count`: Number of distinct users

### Threshold Configuration
Atoms are only created when a minimum number of distinct users (default: 3) organize content in the same way. This threshold helps filter out noise and focus on meaningful patterns.

### Background Processing
- Runs as a scheduled job (recommended: hourly)
- Processes new organization patterns from `collection_tab_map`
- Updates the knowledge graph by creating/updating atoms and edges
- Adjusts weights based on current usage patterns
- Handles soft deletes for removed or merged concepts

### Performance Considerations
- Batch processing for large datasets
- Indexed lookups for all relationships
- Caching of frequently accessed atoms and edges

### Example Workflow

1. **User Actions**
   - User A creates path: `Books/Bible Books` for content #42
   - User B creates same path for content #42
   - User C creates same path for content #42

2. **Processing**
   - System detects threshold reached (3 users)
   - Creates atom in `atoms` table:
     ```sql
     INSERT INTO `{PREFIX}atoms` 
       (node_id, slug, title, origin, created_utc, updated_utc)
     VALUES 
       (1, 'books/bible-books', 'Bible Books', 'tab_path', 20260101120000, 20260101120000);
     ```
   - Creates edge in `edges` table:
     ```sql
     INSERT INTO `{PREFIX}edges` 
       (node_id, source_type, source_id, target_type, target_id, 
        edge_type, weight, properties, created_utc, updated_utc)
     VALUES 
       (1, 'content', 42, 'atom', LAST_INSERT_ID(), 
        'categorized_as', 3, 
        '{"source": "tab_path", "tab_path": "books/bible-books", "user_count": 3}', 
        20260101120000, 20260101120000);
     ```

3. **Result**
   - Content #42 is now semantically tagged with the 'Bible Books' atom
   - The relationship has a weight of 3 (number of users)
   - The system can now use this relationship for:
     - Content discovery
     - Related content suggestions
     - Improved search relevance
     - Personalized recommendations

## Integration Points

### API Endpoints
- `GET /api/atoms` - List semantic atoms
- `GET /api/atoms/{id}/content` - Get content for an atom
- `GET /api/content/{id}/atoms` - Get atoms for content

### Frontend Components
- Atom-based navigation
- Content recommendations
- Semantic search

## Future Enhancements
1. **Machine Learning**
   - Predict new relationships
   - Suggest organizational patterns

2. **Advanced Analytics**
   - Track concept evolution
   - Identify emerging topics

3. **Integration**
   - Export to RDF/OWL
   - Link with external knowledge bases

---

## Related Documentation

- **[End Goal 4.2.0](../overview/END_GOAL_4_2_0.md)** - Complete vision for federated semantic OS that this navigation system supports
- **[Database Schema](../schema/DATABASE_SCHEMA.md)** - Complete documentation of atoms and edges tables used by semantic navigation
- **[Content Interface & Navigation](../developer/modules/CONTENT_INTERFACE_AND_NAVIGATION.md)** - How Collections and Navigation Tabs generate the data for semantic processing
- **[Version 3 Ingestion Rules](VERSION_3_INGESTION_RULES.md)** - How external content ingestion extends semantic navigation beyond local content
- **[Architecture](ARCHITECTURE.md)** - Overall system architecture including semantic layer implementation

---
