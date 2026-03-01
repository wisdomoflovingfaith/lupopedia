---
flare.headers:
  file_path_from_root: "docs/database/lupopedia/tables/lupo_artifact_chunks.md"
  system_version: "4.0.47"
  channel_id: 1
  actor_id: 1001
  last_modified_utc: "20260227"
  delegation_chain: "1001:10000"
  artifact_type: "table_documentation"
  purpose: "Complete documentation for lupo_artifact_chunks table - artifact chunking and large content storage"
  mood_rgb: "4B0082"
  artifact_kind: "table"
  traits: ["canonical", "content_management", "storage", "chunking", "high_volume"]
  tags: ["database", "artifacts", "chunks", "storage", "large_content", "tokenization"]
  lupo_agent: "windsurf"
  # Table-specific metadata from TOON
  lupo_artifact_chunks.artifact_chunk_id: "BIGINT primary key containing YYYYMMDDHHMMSS UTC timestamp"
  lupo_artifact_chunks.artifact_id: "BIGINT references lupo_artifacts.artifact_id"
  lupo_artifact_chunks.chunk_index: "INT NOT NULL chunk sequence number"
  lupo_artifact_chunks.chunk_content: "MEDIUMTEXT NOT NULL chunk content/data"
  lupo_artifact_chunks.token_count: "INT token count for chunk analysis"
  lupo_artifact_chunks.metadata: "JSON additional chunk metadata and properties"
  lupo_artifact_chunks.created_ymdhis: "BIGINT NOT NULL DEFAULT 0 YYYYMMDDHHIISS UTC timestamp"
  lupo_artifact_chunks.updated_ymdhis: "BIGINT NOT NULL DEFAULT 0 YYYYMMDDHHIISS UTC timestamp"
  lupo_artifact_chunks.is_deleted: "TINYINT NOT NULL DEFAULT 0 soft delete flag"
  lupo_artifact_chunks.deleted_ymdhis: "BIGINT YYYYMMDDHHIISS UTC soft delete timestamp"
  table_primary_key: "artifact_chunk_id"
  table_engine: "InnoDB"
  table_charset: "utf8mb4"
  table_collation: "utf8mb4_unicode_ci"
  table_indexes: ["PRIMARY", "lupo_artifact_chunks_art_chunk_unique", "lupo_artifact_chunks_artifact_id"]
  table_foreign_keys: ["artifact_id"]

# 💡 FLARE Edge Automation Tip:
# Use the FLARE Edge Suggester Tool to automatically discover and suggest edges:
# python scripts/flare_edge_suggester.py --file <path> --include-db --format yaml
# This will analyze content, TOON schemas, and database relationships to suggest
# appropriate outbound_edges with weights, reasons, and discovery methods.

flare.edges:
  outbound_edges:
- { to: "docs/toons/lupo_artifact_chunks.toon.json", type: "schema_reference", weight: 1.0, reason: "TOON schema definition", db_source: "lupo_artifact_chunks" }
    - { to: "docs/database/lupopedia/tables/lupo_artifacts.md", type: "references", weight: 1.0, reason: "Parent artifact relationship", db_source: "lupo_artifact_chunks" }
    - { to: "docs/database/lupopedia/tables/lupo_contents.md", type: "references", weight: 0.8, reason: "Content chunking relationships", db_source: "lupo_artifact_chunks" }
    - { to: "docs/database/lupopedia/tables/lupo_document_embeddings.md", type: "references", weight: 0.9, reason: "Vector embeddings for chunks", db_source: "lupo_artifact_chunks" }
    - { to: "docs/FLARE_HEADERS_COMPLETE_REFERENCE.md", type: "references", weight: 0.9, reason: "FLARE protocol documentation", db_source: "lupo_artifact_chunks" }
    - { to: "scripts/flare_edge_suggester.py", type: "implements", weight: 1.0, reason: "Chunk analysis automation", db_source: "lupo_artifact_chunks" }
  inbound_edges:
    - { from: "docs/database/lupopedia/tables/lupo_artifacts.md", type: "references", weight: 1.0, last_seen: "20260227" }
    - { from: "docs/database/lupopedia/tables/lupo_contents.md", type: "references", weight: 0.8, last_seen: "20260227" }
    - { from: "docs/database/lupopedia/tables/lupo_document_embeddings.md", type: "references", weight: 0.9, last_seen: "20260227" }
  semantic_tags: ["chunking", "large_content", "storage", "tokenization", "embeddings", "artifact_management"]
  version: "4.0.47"
  last_verified: "20260227"
  last_verified_by: "windsurf"
---

# 🧩 Table: lupo_artifact_chunks

**Purpose:** Artifact chunking system for large content storage and processing  
**Type:** Content Management Table  
**Status:** ✅ Production Ready  
**Volume:** High (chunk storage for large artifacts)

---

## 🎯 **Overview**

The `lupo_artifact_chunks` table provides a chunking mechanism for storing large content artifacts in manageable pieces. It supports token counting for analysis, metadata storage for chunk properties, and maintains the relationship to parent artifacts. This table is essential for handling large documents, files, and content that needs to be processed in segments.

### **Key Responsibilities**
- **Content Chunking:** Break large artifacts into manageable chunks
- **Token Counting:** Track token counts for analysis and processing
- **Sequential Storage:** Maintain chunk order and integrity
- **Metadata Support:** Store chunk-specific metadata
- **Large Content Handling:** Enable storage of very large content
- **Processing Support:** Facilitate chunk-based content processing

---

## 🗃️ **Schema Reference**

### **Primary Key**
- **`artifact_chunk_id`** (BIGINT) - YYYYMMDDHHMMSS UTC timestamp, unique identifier

### **Core Fields**
| Field | Type | Description | Notes |
|-------|------|-------------|-------|
| `artifact_id` | BIGINT NOT NULL | Parent artifact ID | References lupo_artifacts |
| `chunk_index` | INT NOT NULL | Chunk sequence number | 0-based index |
| `chunk_content` | MEDIUMTEXT NOT NULL | Chunk content/data | Large text content |
| `token_count` | INT | Token count for chunk | For analysis |
| `metadata` | JSON | Additional chunk metadata | Optional |

### **Timestamp Fields**
| Field | Type | Format | Description |
|-------|------|--------|-------------|
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
- **Parent Artifact:** `artifact_id` → `lupo_artifacts.artifact_id` (many-to-one)

### **Related Tables**
- **lupo_artifacts:** Parent artifact storage
- **lupo_document_embeddings:** Vector embeddings for chunks
- **lupo_contents:** Content that may be chunked

---

## 📊 **Indexes & Performance**

### **Primary Indexes**
- **PRIMARY:** `artifact_chunk_id` (unique)

### **Unique Constraints**
- **Artifact Chunk:** `lupo_artifact_chunks_art_chunk_unique` (artifact_id, chunk_index)

### **Performance Indexes**
- **Artifact:** `lupo_artifact_chunks_artifact_id` (parent queries)

---

## 🚀 **Usage Patterns**

### **Common Queries**

#### **Chunks for Artifact**
```sql
SELECT artifact_chunk_id, chunk_index, token_count, LENGTH(chunk_content) as content_length
FROM lupo_artifact_chunks 
WHERE artifact_id = :artifact_id AND is_deleted = 0
ORDER BY chunk_index;
```

#### **Reconstruct Artifact Content**
```sql
SELECT GROUP_CONCAT(chunk_content ORDER BY chunk_index SEPARATOR '') as full_content
FROM lupo_artifact_chunks 
WHERE artifact_id = :artifact_id AND is_deleted = 0;
```

#### **Chunk Statistics**
```sql
SELECT 
    artifact_id,
    COUNT(*) as chunk_count,
    SUM(token_count) as total_tokens,
    AVG(LENGTH(chunk_content)) as avg_chunk_size
FROM lupo_artifact_chunks 
WHERE is_deleted = 0
GROUP BY artifact_id
ORDER BY chunk_count DESC;
```

#### **Large Chunks Analysis**
```sql
SELECT artifact_id, chunk_index, token_count, LENGTH(chunk_content) as size
FROM lupo_artifact_chunks 
WHERE is_deleted = 0 
  AND LENGTH(chunk_content) > 1000000  -- > 1MB chunks
ORDER BY size DESC;
```

---

## ⚡ **Performance Considerations**

### **High-Volume Operations**
- **INSERT:** Chunk creation (high frequency for large artifacts)
- **UPDATE:** Metadata updates (moderate frequency)
- **SELECT:** Chunk retrieval (very high frequency)
- **DELETE:** Soft deletes (low frequency)

### **Optimization Tips**
1. **Use is_deleted = 0** in all queries to filter deleted chunks
2. **Order by chunk_index** for proper content reconstruction
3. **Consider partitioning** by artifact_id for very large datasets
4. **Monitor chunk sizes** to optimize storage and performance
5. **Use token_count** for processing optimization

---

## 📋 **Data Integrity**

### **Constraints**
- **Required Fields:** artifact_chunk_id, artifact_id, chunk_index, chunk_content
- **Unique Constraint:** (artifact_id, chunk_index) must be unique
- **Chunk Ordering:** chunk_index must be sequential for each artifact

### **Validation Rules**
- **Timestamp Format:** YYYYMMDDHHIISS UTC
- **Chunk Index:** Non-negative integers, sequential within artifact
- **Token Count:** Non-negative integers
- **JSON Validation:** Valid JSON structure for metadata

---

## 🚨 **Common Issues & Solutions**

### **Performance Issues**
- **Large Chunks:** Consider optimal chunk size (typically 1-4KB)
- **Sequential Access:** Use chunk_index ordering for reconstruction
- **Memory Usage:** Process chunks in batches for large artifacts

### **Data Consistency**
- **Missing Chunks:** Ensure sequential chunk_index values
- **Orphaned Chunks:** Validate artifact_id references
- **Chunk Gaps:** Check for missing sequence numbers

---

## 🔮 **Future Enhancements**

### **Planned Improvements**
- **Adaptive Chunking:** Intelligent chunk size optimization
- **Compression:** Content compression for storage efficiency
- **Parallel Processing:** Multi-threaded chunk processing
- **Advanced Analytics:** Chunk-level analytics and insights

---

## 📚 **Chunking Strategy**

### **Recommended Chunk Sizes**
- **Text Content:** 1-4KB per chunk
- **Code Files:** 2-8KB per chunk
- **Documents:** 4-16KB per chunk
- **Binary Data:** 8-32KB per chunk

### **Token Counting**
- **Purpose:** Track processing complexity
- **Usage:** Rate limiting, cost estimation, analysis
- **Accuracy:** Count based on tokenization method

---

*This table documentation is part of the FLARE relationship automation initiative. For the complete database context, see the lupopedia database README and the 4.0.47 development thread.*

