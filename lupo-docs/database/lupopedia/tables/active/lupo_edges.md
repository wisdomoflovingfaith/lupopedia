---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_edges.md"
  system_version: "4.0.73"
  namespace: "core"
  channel_id: 1
  actor_id: 1003
  last_modified_utc: "20260313"
  artifact_type: "documentation"
  artifact_kind: "database_table"
  purpose: "JetBrains domain table documentation for lupo_edges"
  lupo_agent: "antigravity"

lupopedia.edges:
  comment: "Snapshot of files edited during 4.0.73 finalization and initialization thread by ANTIGRAVITY IDE Agent. Edges reflect discovered relationships between database tables and PHP/Python codebase entities. Grouped outbound_edges follow the edge_category doctrine."
  meta: "Thread: Finalize 4.0.72 → Push to GitHub → Initialize 4.0.73 → Migrate Tasks → Validate Upgrade Path"
  outbound_edges:
    schema:
      - { to: "lupo-database/lupopedia/toon/lupo_edges.toon", type: "schema_reference", weight: 1.0, reason: "TOON schema definition", db_source: "lupo_edges" }
      - { to: "database/migrations/20260313_add_edge_category_to_lupo_edges.sql", type: "schema_reference", weight: 1.0, reason: "One-time migration for edge_category (uses scripts/run_one_time_sql.php soft-error idempotency)", db_source: "lupo_edges" }
      - { to: "scripts/run_one_time_sql.php", type: "tools", weight: 0.9, reason: "Minimal SQL runner for shared hosts" }
    documentation:
      - { to: "lupo-docs/status/EDGE_STRUCTURE_AUDIT_GROUPED_OUTBOUND_EDGES.md", type: "documents", weight: 1.0, reason: "Audit report for grouped edges" }
      - { to: "docs/FLARE_HEADERS_COMPLETE_REFERENCE.md", type: "references", weight: 1.0, reason: "FLARE protocol core documentation" }
      - { to: "docs/api/FLARE_API.md", type: "api_reference", weight: 1.0, reason: "FLARE API endpoints for edge management" }
    code:
      - { to: "scripts/flare_edge_suggester.py", type: "implements", weight: 1.0, reason: "Edge discovery and automation" }
      - { to: "tools/update_flare_edges.py", type: "implements", weight: 1.0, reason: "Batch edge update and validation" }
      - { to: "lupo-includes/modules/content/edge-controller.php", type: "references", weight: 0.9, reason: "Central edge management controller" }
      - { to: "lupo-includes/modules/content/content-controller.php", type: "references", weight: 0.8, reason: "Content relationship resolution" }
      - { to: "lupo-includes/modules/truth/truth-controller.php", type: "references", weight: 0.8, reason: "Semantic truth edge mapping" }
      - { to: "lupo-includes/classes/ContentChannelActorResolver.php", type: "references", weight: 0.8, reason: "High-level object relationship resolution" }
      - { to: "lupo-includes/class-ConnectionsService.php", type: "implements", weight: 0.9, reason: "Core service for relationship graph traversal" }

lupopedia.engagement:
  comment: "Snapshot of files edited during 4.0.73 finalization and initialization thread by ANTIGRAVITY IDE Agent. Engagement metrics track edit frequency and importance of each file in the version transition process."
  meta: "Thread: Finalize 4.0.72 → Push to GitHub → Initialize 4.0.73 → Migrate Tasks → Validate Upgrade Path"
  views: 0

lupopedia.footer:
  version: "4.0.73"
  last_verified: "20260313"
  last_verified_by: "antigravity"
  # Table-specific metadata from TOON
  lupo_edges.edge_id: "BIGINT primary key containing YYYYMMDDHHMMSS UTC timestamp"
  lupo_edges.left_object_type: "VARCHAR(50) NOT NULL type of left object (content, actor, channel, etc.)"
  lupo_edges.left_object_id: "BIGINT NOT NULL ID of left object"
  lupo_edges.right_object_type: "VARCHAR(50) NOT NULL type of right object"
  lupo_edges.right_object_id: "BIGINT NOT NULL ID of right object"
  lupo_edges.edge_type: "VARCHAR(100) NOT NULL type of relationship (references, implements, depends_on, etc.)"
  lupo_edges.edge_category: "VARCHAR(100) category of edge for grouping"
  lupo_edges.edge_description: "TEXT description of relationship purpose"
  lupo_edges.channel_id: "BIGINT channel where relationship exists"
  lupo_edges.channel_key: "VARCHAR(64) channel key for routing"
  lupo_edges.domain_id: "BIGINT NOT NULL DEFAULT 1 domain/federation node"
  lupo_edges.weight_score: "INT NOT NULL DEFAULT 0 legacy weight score"
  lupo_edges.sort_num: "INT NOT NULL DEFAULT 0 sort order"
  lupo_edges.actor_id: "BIGINT actor who created the relationship"
  lupo_edges.is_deleted: "TINYINT NOT NULL DEFAULT 0 soft delete flag"
  lupo_edges.deleted_ymdhis: "BIGINT NOT NULL DEFAULT 0 YYYYMMDDHHIISS UTC soft delete timestamp"
  lupo_edges.created_ymdhis: "BIGINT NOT NULL DEFAULT 0 YYYYMMDDHHIISS UTC creation timestamp"
  lupo_edges.updated_ymdhis: "BIGINT NOT NULL YYYYMMDDHHIISS UTC update timestamp"
  lupo_edges.semantic_weight: "DECIMAL(5,2) DEFAULT 0.00 semantic weight for analysis"
  lupo_edges.relationship_type: "VARCHAR(64) DEFAULT 'semantic' type of relationship"
  lupo_edges.bidirectional: "TINYINT NOT NULL DEFAULT 0 bidirectional relationship flag"
  lupo_edges.context_scope: "VARCHAR(100) context scope for relationship"
  lupo_edges.properties: "JSON additional edge properties and metadata"
  # FLARE Protocol Fields (added 2026-02-27)
  lupo_edges.flare_weight: "DECIMAL(3,2) DEFAULT 0.50 FLARE edge weight (0.5-1.0)"
  lupo_edges.flare_reason: "VARCHAR(255) reason for edge existence"
  lupo_edges.flare_db_source: "VARCHAR(50) database source table"
  lupo_edges.flare_auto_generated: "TINYINT DEFAULT 0 generated by automation"
  lupo_edges.flare_verified: "TINYINT DEFAULT 0 path verified to exist"
  lupo_edges.flare_discovered_via: "VARCHAR(50) discovery method"
  table_primary_key: "edge_id"
  table_engine: "InnoDB"
  table_charset: "utf8mb4"
  table_collation: "utf8mb4_unicode_ci"
  table_indexes: ["PRIMARY", "idx_lupo_edges_flare_discovered", "idx_lupo_edges_flare_files", "idx_lupo_edges_flare_weight", "lupo_edges_idx_actor", "lupo_edges_idx_channel_semantic", "lupo_edges_idx_created", "lupo_edges_idx_domain", "lupo_edges_idx_edge_category", "lupo_edges_idx_edge_type", "lupo_edges_idx_is_deleted", "lupo_edges_idx_left", "lupo_edges_idx_relationship_type", "lupo_edges_idx_right", "lupo_edges_idx_semantic_weight", "lupo_edges_idx_updated"]
  table_foreign_keys: ["channel_id", "domain_id", "actor_id"]

# 💡 FLARE Edge Automation Tip:
# Use the FLARE Edge Suggester Tool to automatically discover and suggest edges:
# python scripts/flare_edge_suggester.py --file <path> --include-db --format yaml
# This will analyze content, TOON schemas, and database relationships to suggest
# appropriate outbound_edges with weights, reasons, and discovery methods.



# 🔗 Table: lupo_edges

**Purpose:** Unified relationship graph and FLARE protocol storage for all system relationships  
**Type:** Core System Table  
**Status:** ✅ Production Ready  
**Volume:** High (relationship storage)

---

## 🎯 **Overview**

The `lupo_edges` table serves as the unified relationship graph for Lupopedia, replacing multiple legacy relationship tables and providing the foundation for the FLARE (File-Level Attribute and Relationship Exchange) protocol. It stores relationships between any two objects in the system with support for semantic weights, bidirectional relationships, and comprehensive metadata including FLARE-specific fields for automated edge discovery and management.

### **Key Responsibilities**
- **Unified Graph:** Single storage for all system relationships
- **FLARE Protocol:** Core storage for FLARE file relationships
- **Semantic Analysis:** Weight-based relationship scoring
- **Automation Support:** Edge discovery and validation
- **Bidirectional Support:** Two-way relationship management
- **Cross-Entity:** Relationships between any object types
- **Metadata Storage:** Rich relationship properties and context

---

## 🗃️ **Schema Reference**

### **Primary Key**
- **`edge_id`** (BIGINT) - YYYYMMDDHHMMSS UTC timestamp, unique identifier

### **Core Relationship Fields**
| Field | Type | Description | Notes |
|-------|------|-------------|-------|
| `left_object_type` | VARCHAR(50) NOT NULL | Type of left object | content, actor, channel, etc. |
| `left_object_id` | BIGINT NOT NULL | ID of left object | References object primary key |
| `right_object_type` | VARCHAR(50) NOT NULL | Type of right object | content, actor, channel, etc. |
| `right_object_id` | BIGINT NOT NULL | ID of right object | References object primary key |
| `edge_type` | VARCHAR(100) NOT NULL | Type of relationship | references, implements, etc. |

### **Relationship Properties**
| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `edge_category` | VARCHAR(100) | NULL | Category for grouping (e.g. `code`, `documentation`, `schema`, `runtime`). When syncing from LUPOPEDIA HEADERS grouped **outbound_edges**, the group key is stored here; export groups by this column to rehydrate grouped YAML. |
| `edge_description` | TEXT | NULL | Relationship purpose | Optional |
| `relationship_type` | VARCHAR(64) | 'semantic' | Type of relationship |
| `bidirectional` | TINYINT | 0 | Bidirectional flag |
| `context_scope` | VARCHAR(100) | Context scope | Optional |

### **Weight & Scoring Fields**
| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `weight_score` | INT | 0 | Legacy weight score |
| `semantic_weight` | DECIMAL(5,2) | 0.00 | Semantic weight for analysis |
| `sort_num` | INT | 0 | Sort order |

### **FLARE Protocol Fields (Added 2026-02-27)**
| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `flare_weight` | DECIMAL(3,2) | 0.50 | FLARE edge weight (0.5-1.0) |
| `flare_reason` | VARCHAR(255) | Reason for edge existence | Optional |
| `flare_db_source` | VARCHAR(50) | Database source table | Optional |
| `flare_auto_generated` | TINYINT | 0 | Generated by automation |
| `flare_verified` | TINYINT | 0 | Path verified to exist |
| `flare_discovered_via` | VARCHAR(50) | Discovery method | Optional |

### **Context Fields**
| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `channel_id` | BIGINT | Channel where relationship exists | Optional |
| `channel_key` | VARCHAR(64) | Channel key for routing | Optional |
| `domain_id` | BIGINT | 1 | Domain/federation node |
| `actor_id` | BIGINT | Actor who created relationship | Optional |

### **Metadata & Properties**
| Field | Type | Description | Notes |
|-------|------|-------------|-------|
| `properties` | JSON | Additional edge properties | Flexible metadata |

### **Timestamp & Status Fields**
| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `created_ymdhis` | BIGINT | 0 | YYYYMMDDHHIISS UTC creation |
| `updated_ymdhis` | BIGINT | YYYYMMDDHHIISS UTC update | Auto-updated |
| `is_deleted` | TINYINT | 0 | Soft delete flag |
| `deleted_ymdhis` | BIGINT | 0 | YYYYMMDDHHIISS UTC delete |

---

## 🔗 **Relationships & Dependencies**

### **Referenced Objects**
The table can reference any object type in the system:
- **content:** lupo_contents.content_id
- **actor:** lupo_actors.actor_id
- **channel:** lupo_channels.channel_id
- **artifact:** lupo_artifacts.artifact_id
- **collection:** lupo_collections.collection_id
- **department:** lupo_departments.department_id

### **Context Relationships**
- **Channel:** `channel_id` → `lupo_channels.channel_id`
- **Domain:** `domain_id` → `lupo_federation_nodes.federation_node_id`
- **Actor:** `actor_id` → `lupo_actors.actor_id`

---

## 📊 **Indexes & Performance**

### **Primary Indexes**
- **PRIMARY:** `edge_id` (unique)

### **FLARE-Specific Indexes (Added 2026-02-27)**
- **FLARE Files:** `idx_lupo_edges_flare_files` (left_object_type, left_object_id, edge_type, right_object_type, right_object_id)
- **FLARE Weight:** `idx_lupo_edges_flare_weight` (flare_weight, edge_type)
- **FLARE Discovered:** `idx_lupo_edges_flare_discovered` (flare_discovered_via, flare_auto_generated)

### **Performance Indexes**
- **Left Object:** `lupo_edges_idx_left` (left_object_type, left_object_id)
- **Right Object:** `lupo_edges_idx_right` (right_object_type, right_object_id)
- **Edge Type:** `lupo_edges_idx_edge_type` (edge_type)
- **Actor:** `lupo_edges_idx_actor` (actor_id)
- **Channel Semantic:** `lupo_edges_idx_channel_semantic` (channel_id, relationship_type, semantic_weight)
- **Domain:** `lupo_edges_idx_domain` (domain_id)
- **Semantic Weight:** `lupo_edges_idx_semantic_weight` (semantic_weight)
- **Created:** `lupo_edges_idx_created` (created_ymdhis)
- **Updated:** `lupo_edges_idx_updated` (updated_ymdhis)
- **Deleted:** `lupo_edges_idx_is_deleted` (is_deleted)

---

## 🚀 **Usage Patterns**

### **Common Queries**

#### **Basic Edge Retrieval**
```sql
SELECT edge_id, left_object_type, left_object_id, edge_type, right_object_type, right_object_id
FROM lupo_edges 
WHERE is_deleted = 0
ORDER BY created_ymdhis DESC;
```

#### **FLARE Edges for Content**
```sql
SELECT edge_id, edge_type, flare_weight, flare_reason, flare_discovered_via
FROM lupo_edges 
WHERE left_object_type = 'content' 
  AND left_object_id = :content_id 
  AND is_deleted = 0
ORDER BY flare_weight DESC;
```

#### **Bidirectional Relationships**
```sql
SELECT edge_id, left_object_type, left_object_id, right_object_type, right_object_id
FROM lupo_edges 
WHERE (left_object_type = :type1 AND left_object_id = :id1 AND right_object_type = :type2 AND right_object_id = :id2)
   OR (left_object_type = :type2 AND left_object_id = :id2 AND right_object_type = :type1 AND right_object_id = :id1)
  AND is_deleted = 0;
```

#### **Edges by Type**
```sql
SELECT edge_type, COUNT(*) as count, AVG(flare_weight) as avg_weight
FROM lupo_edges 
WHERE is_deleted = 0
GROUP BY edge_type
ORDER BY count DESC;
```

#### **Automated Edges**
```sql
SELECT edge_id, edge_type, flare_discovered_via, flare_verified
FROM lupo_edges 
WHERE flare_auto_generated = 1 
  AND is_deleted = 0
ORDER BY created_ymdhis DESC;
```

#### **High-Weight Relationships**
```sql
SELECT left_object_type, left_object_id, edge_type, right_object_type, right_object_id, flare_weight
FROM lupo_edges 
WHERE flare_weight >= 0.9 
  AND is_deleted = 0
ORDER BY flare_weight DESC, edge_type;
```

---

## ⚡ **Performance Considerations**

### **High-Volume Operations**
- **INSERT:** Edge creation (high frequency for automation)
- **UPDATE:** Weight and metadata updates (moderate frequency)
- **SELECT:** Edge lookup (very high frequency)
- **DELETE:** Soft deletes (low frequency)

### **Optimization Tips**
1. **Use is_deleted = 0** in all queries to filter deleted edges
2. **Index object pairs** for efficient relationship lookups
3. **Use FLARE indexes** for FLARE-specific queries
4. **Consider partitioning** by object_type for large datasets
5. **Cache frequent queries** for popular relationships

---

## 📋 **Data Integrity**

### **Constraints**
- **Required Fields:** edge_id, left_object_type, left_object_id, right_object_type, right_object_id, edge_type
- **Default Values:** Sensible defaults for optional fields
- **Soft Delete:** is_deleted flag for safe deletion

### **Validation Rules**
- **Timestamp Format:** YYYYMMDDHHIISS UTC
- **FLARE Weight Range:** 0.5 to 1.0 for FLARE edges
- **Object Types:** Valid object type values
- **JSON Validation:** Valid JSON structure for properties

---

## 🔥 **FLARE Protocol Integration**

### **FLARE Edge Types**
Standard FLARE edge types with weight ranges:
- **references** (0.5-1.0) - General documentation links
- **implements** (0.8-1.0) - Implementation relationships
- **schema_reference** (1.0) - Database schema references
- **depends_on** (0.8-1.0) - Dependency relationships
- **supersedes** (0.9-1.0) - Version relationships
- **example_of** (0.6-0.8) - Example documentation
- **related_to** (0.5-0.7) - Loose associations

### **Discovery Methods**
- **content_analysis** - Analyzing markdown content
- **toon_schema** - Database schema analysis
- **db_scan** - Database relationship scanning
- **semantic_search** - Vector similarity search
- **manual** - Manually created edges

### **Automation Workflow**
1. **Discovery:** Automated edge discovery tools
2. **Validation:** Path verification and existence checking
3. **Storage:** Store with FLARE metadata
4. **Maintenance:** Periodic validation and cleanup

---

## 🔄 **Migration from Legacy Tables**

This table replaces multiple legacy relationship tables:
- **lupo_edge_types** - Edge type definitions
- **lupo_relationships** - Basic relationships
- **lupo_entity_edges** - Entity-specific edges
- **Content relationship tables** - Various content link tables

### **Migration Benefits**
- **Unified Storage:** Single source of truth
- **Enhanced Metadata:** Rich relationship properties
- **FLARE Support:** Built-in FLARE protocol support
- **Performance:** Optimized indexes for all query patterns
- **Scalability:** Designed for high-volume relationship storage

---

## 🚨 **Common Issues & Solutions**

### **Performance Issues**
- **Large Graphs:** Use appropriate indexes for query patterns
- **Complex Queries:** Break down complex relationship traversals
- **Metadata Size:** Keep properties JSON reasonable

### **Data Consistency**
- **Orphaned Edges:** Validate object references exist
- **Duplicate Edges**: Prevent duplicate relationships
- **Weight Consistency**: Ensure weight ranges are appropriate

---

## 🔮 **Future Enhancements**

### **Planned Improvements**
- **Graph Algorithms:** Built-in graph traversal algorithms
- **Advanced Analytics:** Relationship analytics and insights
- **Real-time Updates:** Live relationship updates
- **Cross-Federation:** Multi-node relationship synchronization

---

*This table documentation is part of the FLARE relationship automation initiative. For the complete database context, see the lupopedia database README and the 4.0.47 development thread.*

