---
flare.headers:
  file_path_from_root: "docs/database/lupopedia/tables/lupo_artifacts.md"
  system_version: "4.0.47"
  channel_id: 1
  actor_id: 1001
  last_modified_utc: "20260227"
  delegation_chain: "1001:10000"
  artifact_type: "table_documentation"
  purpose: "Complete documentation for lupo_artifacts table - artifact storage and management system"
  mood_rgb: "4B0082"
  artifact_kind: "table"
  traits: ["canonical", "content_management", "storage", "high_volume"]
  tags: ["database", "artifacts", "storage", "entities", "metadata"]
  lupo_agent: "windsurf"
  # Table-specific metadata from TOON
  lupo_artifacts.artifact_id: "BIGINT primary key containing YYYYMMDDHHMMSS UTC timestamp"
  lupo_artifacts.actor_id: "BIGINT references lupo_actors.actor_id"
  lupo_artifacts.federation_node_id: "BIGINT references lupo_federation_nodes.federation_node_id"
  lupo_artifacts.utc_timestamp: "BIGINT UTC timestamp for artifact creation"
  lupo_artifacts.entity_type: "VARCHAR(64) NOT NULL type of entity stored"
  lupo_artifacts.content: "TEXT NOT NULL artifact content/data"
  lupo_artifacts.metadata: "JSON additional metadata and properties"
  lupo_artifacts.created_ymdhis: "BIGINT NOT NULL DEFAULT 0 YYYYMMDDHHIISS UTC timestamp"
  lupo_artifacts.updated_ymdhis: "BIGINT NOT NULL DEFAULT 0 YYYYMMDDHHIISS UTC timestamp"
  lupo_artifacts.is_deleted: "TINYINT NOT NULL DEFAULT 0 soft delete flag"
  lupo_artifacts.deleted_ymdhis: "BIGINT YYYYMMDDHHIISS UTC soft delete timestamp"
  table_primary_key: "artifact_id"
  table_engine: "InnoDB"
  table_charset: "utf8mb4"
  table_collation: "utf8mb4_unicode_ci"
  table_indexes: ["PRIMARY", "lupo_artifacts_idx_actor_id", "lupo_artifacts_idx_entity_type", "lupo_artifacts_idx_is_deleted", "lupo_artifacts_idx_utc_timestamp"]
  table_foreign_keys: ["actor_id", "federation_node_id"]

# 💡 FLARE Edge Automation Tip:
# Use the FLARE Edge Suggester Tool to automatically discover and suggest edges:
# python scripts/flare_edge_suggester.py --file <path> --include-db --format yaml
# This will analyze content, TOON schemas, and database relationships to suggest
# appropriate outbound_edges with weights, reasons, and discovery methods.

flare.footer:
  outbound_edges:
    - { to: "docs/toons/lupo_artifacts.toon.json", type: "schema_reference", weight: 1.0, reason: "TOON schema definition", db_source: "lupo_artifacts" }
    - { to: "docs/database/lupopedia/tables/lupo_artifact_chunks.md", type: "references", weight: 1.0, reason: "Artifact chunk storage relationship", db_source: "lupo_artifacts" }
    - { to: "docs/database/lupopedia/tables/lupo_actors.md", type: "references", weight: 0.9, reason: "Artifact owner relationships", db_source: "lupo_artifacts" }
    - { to: "docs/database/lupopedia/tables/lupo_federation_nodes.md", type: "references", weight: 0.7, reason: "Federation node assignments", db_source: "lupo_artifacts" }
    - { to: "docs/database/lupopedia/tables/lupo_contents.md", type: "references", weight: 0.8, reason: "Content artifact relationships", db_source: "lupo_artifacts" }
    - { to: "docs/FLARE_HEADERS_COMPLETE_REFERENCE.md", type: "references", weight: 0.9, reason: "FLARE protocol documentation", db_source: "lupo_artifacts" }
    - { to: "scripts/flare_edge_suggester.py", type: "implements", weight: 1.0, reason: "Artifact analysis automation", db_source: "lupo_artifacts" }
  inbound_edges:
    - { from: "docs/database/lupopedia/tables/lupo_artifact_chunks.md", type: "references", weight: 1.0, last_seen: "20260227" }
    - { from: "docs/database/lupopedia/tables/lupo_actors.md", type: "references", weight: 0.9, last_seen: "20260227" }
    - { from: "docs/database/lupopedia/tables/lupo_federation_nodes.md", type: "references", weight: 0.7, last_seen: "20260227" }
    - { from: "docs/database/lupopedia/tables/lupo_contents.md", type: "references", weight: 0.8, last_seen: "20260227" }
  semantic_tags: ["artifact_storage", "content_management", "entity_storage", "metadata", "chunking", "federation"]
  version: "4.0.47"
  last_verified: "20260227"
  last_verified_by: "windsurf"
---

# 📦 Table: lupo_artifacts

**Purpose:** Artifact storage and management system for entities and large content  
**Type:** Content Management Table  
**Status:** ✅ Production Ready  
**Volume:** High (artifact storage)

---

## 🎯 **Overview**

The `lupo_artifacts` table serves as a flexible storage system for various types of artifacts, entities, and large content objects that don't fit the traditional content model. It provides a generic storage mechanism with support for chunking (via related `lupo_artifact_chunks` table), metadata storage, and federation support.

### **Key Responsibilities**
- **Generic Storage:** Store any type of artifact or entity
- **Large Content:** Handle large content through chunking
- **Metadata Support:** Flexible JSON metadata storage
- **Entity Management:** Store various entity types
- **Federation Support:** Multi-node artifact distribution
- **Actor Ownership:** Track artifact creators and owners

---

## 🗃️ **Schema Reference**

### **Primary Key**
- **`artifact_id`** (BIGINT) - YYYYMMDDHHMMSS UTC timestamp, unique identifier

### **Core Fields**
| Field | Type | Description | Notes |
|-------|------|-------------|-------|
| `entity_type` | VARCHAR(64) NOT NULL | Type of entity stored | Required |
| `content` | TEXT NOT NULL | Artifact content/data | Main content |
| `metadata` | JSON | Additional metadata and properties | Optional |

### **Relationship Fields**
| Field | Type | Reference | Description |
|-------|------|-----------|-------------|
| `actor_id` | BIGINT | lupo_actors.actor_id | Artifact owner/creator |
| `federation_node_id` | BIGINT | lupo_federation_nodes.federation_node_id | Federation node |

### **Timestamp Fields**
| Field | Type | Format | Description |
|-------|------|--------|-------------|
| `utc_timestamp` | BIGINT | UTC timestamp | Creation timestamp |
| `created_ymdhis` | BIGINT | YYYYMMDDHHIISS UTC | Creation timestamp |
| `updated_ymdhis` | BIGINT | YYYYMMDDHHIISS UTC | Last update |
| `deleted_ymdhis` | BIGINT | YYYYMMDDHHIISS UTC | Soft delete time |

### **Status Fields**
| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `is_deleted` | TINYINT | 0 | Soft delete flag |

---

## 🔗 **Relationships & Dependencies**

### **Primary Relationships**
- **Owner:** `actor_id` → `lupo_actors.actor_id`
- **Federation:** `federation_node_id` → `lupo_federation_nodes.federation_node_id`
- **Chunks:** `lupo_artifact_chunks.artifact_id` → `artifact_id` (one-to-many)

### **Related Tables**
- **lupo_artifact_chunks:** Stores chunked content for large artifacts
- **lupo_actors:** Artifact owners and creators
- **lupo_federation_nodes:** Federation node assignments
- **lupo_contents:** Content that may have associated artifacts

---

## 📊 **Indexes & Performance**

### **Primary Indexes**
- **PRIMARY:** `artifact_id` (unique)

### **Performance Indexes**
- **Actor:** `lupo_artifacts_idx_actor_id` (owner queries)
- **Entity Type:** `lupo_artifacts_idx_entity_type` (type filtering)
- **Deleted:** `lupo_artifacts_idx_is_deleted` (soft delete filtering)
- **Timestamp:** `lupo_artifacts_idx_utc_timestamp` (chronological queries)

---

## 🚀 **Usage Patterns**

### **Common Queries**

#### **Basic Artifact Retrieval**
```sql
SELECT artifact_id, entity_type, actor_id, utc_timestamp
FROM lupo_artifacts 
WHERE is_deleted = 0
ORDER BY created_ymdhis DESC;
```

#### **Artifacts by Actor**
```sql
SELECT artifact_id, entity_type, created_ymdhis
FROM lupo_artifacts 
WHERE actor_id = :actor_id AND is_deleted = 0
ORDER BY utc_timestamp DESC;
```

#### **Artifacts by Type**
```sql
SELECT entity_type, COUNT(*) as count
FROM lupo_artifacts 
WHERE is_deleted = 0
GROUP BY entity_type
ORDER BY count DESC;
```

#### **Artifact with Chunks**
```sql
SELECT a.artifact_id, a.entity_type, COUNT(c.chunk_id) as chunk_count
FROM lupo_artifacts a
LEFT JOIN lupo_artifact_chunks c ON a.artifact_id = c.artifact_id
WHERE a.is_deleted = 0 AND c.is_deleted = 0
GROUP BY a.artifact_id, a.entity_type;
```

---

## ⚡ **Performance Considerations**

### **High-Volume Operations**
- **INSERT:** Artifact creation (moderate frequency)
- **UPDATE:** Metadata updates (moderate frequency)
- **SELECT:** Artifact retrieval (high frequency)
- **DELETE:** Soft deletes (low frequency)

### **Optimization Tips**
1. **Use is_deleted = 0** in all queries to filter deleted artifacts
2. **Index entity_type** for type-based queries
3. **Consider partitioning** by utc_timestamp for large datasets
4. **Use chunks table** for large content storage
5. **Cache frequent queries** for popular artifacts

---

## 📋 **Data Integrity**

### **Constraints**
- **Required Fields:** artifact_id, actor_id, entity_type, content
- **Default Values:** Sensible defaults for timestamps and status
- **Soft Delete:** is_deleted flag for safe deletion

### **Validation Rules**
- **Timestamp Format:** YYYYMMDDHHIISS UTC
- **Entity Types:** Standardized entity type values
- **JSON Validation:** Valid JSON structure for metadata

---

## 🚨 **Common Issues & Solutions**

### **Performance Issues**
- **Large Content:** Use chunking for large artifacts
- **Metadata Size:** Keep JSON metadata reasonable
- **Query Performance:** Add appropriate indexes for query patterns

### **Data Consistency**
- **Orphaned Chunks:** Ensure chunks reference valid artifacts
- **Missing Metadata:** Validate JSON structure
- **Timestamp Sync:** Ensure UTC timestamp accuracy

---

## 🔮 **Future Enhancements**

### **Planned Improvements**
- **Artifact Versioning:** Version control for artifacts
- **Compression:** Content compression for storage efficiency
- **Encryption:** Secure artifact storage
- **Analytics:** Artifact usage analytics

---

*This table documentation is part of the FLARE relationship automation initiative. For the complete database context, see the lupopedia database README and the 4.0.47 development thread.*
