---
flare.headers:
  file_path_from_root: "docs/database/lupopedia/tables/lupo_contents.md"
  system_version: "4.0.47"
  channel_id: 1
  actor_id: 1001
  last_modified_utc: "20260227"
  delegation_chain: "1001:10000"
  artifact_type: "table_documentation"
  purpose: "Complete documentation for lupo_contents table - core content management and storage"
  mood_rgb: "4B0082"
  artifact_kind: "table"
  traits: ["canonical", "core_system", "content_management", "high_volume"]
  tags: ["database", "content", "storage", "management", "flares"]
  lupo_agent: "windsurf"
  # Table-specific metadata from TOON
  lupo_contents.content_id: "BIGINT primary key containing YYYYMMDDHHMMSS UTC timestamp"
  lupo_contents.content_parent_id: "BIGINT references lupo_contents.content_id for hierarchical content"
  lupo_contents.federation_node_id: "BIGINT references lupo_federation_nodes.federation_node_id"
  lupo_contents.department_id: "BIGINT references lupo_departments.department_id"
  lupo_contents.actor_id: "BIGINT references lupo_actors.actor_id"
  lupo_contents.title: "VARCHAR(255) NOT NULL content title"
  lupo_contents.slug: "VARCHAR(255) NOT NULL URL-friendly slug"
  lupo_contents.custom_path: "VARCHAR(255) custom file path override"
  lupo_contents.description: "TEXT content description/summary"
  lupo_contents.seo_keywords: "VARCHAR(500) SEO keywords"
  lupo_contents.body: "TEXT main content body"
  lupo_contents.content: "TEXT alternate content field"
  lupo_contents.content_type: "VARCHAR(50) DEFAULT 'article' content type"
  lupo_contents.format: "VARCHAR(20) DEFAULT 'markdown' content format"
  lupo_contents.content_url: "VARCHAR(2000) external content URL"
  lupo_contents.default_collection_id: "BIGINT references lupo_collections.collection_id"
  lupo_contents.source_url: "VARCHAR(2000) source URL"
  lupo_contents.source_title: "VARCHAR(500) source title"
  lupo_contents.is_template: "TINYINT NOT NULL DEFAULT 0 template flag"
  lupo_contents.status: "VARCHAR(64) DEFAULT 'draft' content status"
  lupo_contents.visibility: "VARCHAR(64) DEFAULT 'public' visibility level"
  lupo_contents.view_count: "INT DEFAULT 0 view counter"
  lupo_contents.share_count: "INT DEFAULT 0 share counter"
  lupo_contents.created_ymdhis: "BIGINT NOT NULL DEFAULT 0 YYYYMMDDHHIISS UTC timestamp"
  lupo_contents.utc_cycle: "VARCHAR(64) NOT NULL UTC cycle identifier"
  lupo_contents.triage_status: "VARCHAR(64) NOT NULL DEFAULT 'untriaged' triage status"
  lupo_contents.triage_notes: "TEXT triage notes"
  lupo_contents.updated_ymdhis: "BIGINT NOT NULL YYYYMMDDHHIISS UTC timestamp"
  lupo_contents.is_deleted: "TINYINT NOT NULL DEFAULT 0 soft delete flag"
  lupo_contents.is_active: "TINYINT NOT NULL DEFAULT 1 active flag"
  lupo_contents.deleted_ymdhis: "BIGINT YYYYMMDDHHIISS UTC soft delete timestamp"
  lupo_contents.content_sections: "JSON content sections structure"
  lupo_contents.version_number: "INT NOT NULL DEFAULT 1 content version"
  lupo_contents.file_path_from_root: "VARCHAR(500) FLIP Header: path from repo root (4.0.13)"
  lupo_contents.file_last_modified_system_version: "VARCHAR(20) FLIP: system version at last file edit"
  lupo_contents.file_last_modified_utc: "BIGINT FLIP: UTC last modified YYYYMMDDHHIISS"
  lupo_contents.tags: "JSON content tags"
  lupo_contents.dialog_notes: "TEXT dialog-related notes"
  lupo_contents.atom_mappings: "JSON Consolidated from lupo_content_atom_map"
  lupo_contents.category_mappings: "JSON Consolidated from lupo_content_category_map"
  lupo_contents.likes_total: "INT DEFAULT 0 Consolidated from lupo_content_engagement_summary"
  lupo_contents.shares_total: "INT DEFAULT 0 Consolidated from lupo_content_engagement_summary"
  lupo_contents.content_events: "JSON Consolidated from lupo_content_events"
  lupo_contents.hashtags: "JSON Consolidated from lupo_content_hashtag"
  lupo_contents.inbound_links: "JSON Consolidated from lupo_content_inbound_links"
  lupo_contents.like_users: "JSON Consolidated from lupo_content_likes"
  lupo_contents.media_attachments: "JSON Consolidated from lupo_content_media"
  lupo_contents.question_mappings: "JSON Consolidated from lupo_content_question_map"
  lupo_contents.content_references: "JSON Consolidated from lupo_content_references"
  lupo_contents.revision_history: "JSON Consolidated from lupo_content_revisions"
  lupo_contents.share_users: "JSON Consolidated from lupo_content_shares"
  lupo_contents.tag_relationships: "JSON Consolidated from lupo_content_tag_relationships"
  table_primary_key: "content_id"
  table_engine: "InnoDB"
  table_charset: "utf8mb4"
  table_collation: "utf8mb4_unicode_ci"
  table_indexes: ["PRIMARY", "lupo_contents_idx_content_parent", "lupo_contents_idx_content_type", "lupo_contents_idx_created_ymdhis", "lupo_contents_idx_custom_path", "lupo_contents_idx_department", "lupo_contents_idx_domain", "lupo_contents_idx_file_path_from_root", "lupo_contents_idx_has_events", "lupo_contents_idx_has_hashtags", "lupo_contents_idx_has_likes_shares", "lupo_contents_idx_has_media", "lupo_contents_idx_is_active", "lupo_contents_idx_is_deleted", "lupo_contents_idx_status", "lupo_contents_idx_updated_ymdhis", "lupo_contents_idx_user", "lupo_contents_idx_visibility", "lupo_contents_unique_content_slug_domain"]
  table_foreign_keys: ["content_parent_id", "federation_node_id", "department_id", "actor_id", "default_collection_id"]

# 💡 FLARE Edge Automation Tip:
# Use the FLARE Edge Suggester Tool to automatically discover and suggest edges:
# python scripts/flare_edge_suggester.py --file <path> --include-db --format yaml
# This will analyze content, TOON schemas, and database relationships to suggest
# appropriate outbound_edges with weights, reasons, and discovery methods.

flare.edges:
  outbound_edges:
- { to: "lupo-database/lupopedia/toon/lupo_contents.toon.json", type: "schema_reference", weight: 1.0, reason: "TOON schema definition", db_source: "lupo_contents" }
    - { to: "docs/database/lupopedia/tables/lupo_actors.md", type: "references", weight: 0.9, reason: "Content author relationships", db_source: "lupo_contents" }
    - { to: "docs/database/lupopedia/tables/lupo_collections.md", type: "references", weight: 0.8, reason: "Default collection assignments", db_source: "lupo_contents" }
    - { to: "docs/database/lupopedia/tables/lupo_departments.md", type: "references", weight: 0.7, reason: "Department content ownership", db_source: "lupo_contents" }
    - { to: "docs/database/lupopedia/tables/lupo_federation_nodes.md", type: "references", weight: 0.7, reason: "Federation node assignments", db_source: "lupo_contents" }
    - { to: "docs/database/lupopedia/tables/lupo_dialog_messages.md", type: "references", weight: 0.6, reason: "Content referenced in dialogs", db_source: "lupo_contents" }
    - { to: "docs/database/lupopedia/tables/lupo_edges.md", type: "references", weight: 0.8, reason: "FLARE relationship storage", db_source: "lupo_contents" }
    - { to: "docs/FLARE_HEADERS_COMPLETE_REFERENCE.md", type: "references", weight: 0.9, reason: "FLARE protocol documentation", db_source: "lupo_contents" }
    - { to: "docs/api/FLARE_API.md", type: "api_reference", weight: 0.8, reason: "FLARE API endpoints", db_source: "lupo_contents" }
    - { to: "scripts/flare_edge_suggester.py", type: "implements", weight: 1.0, reason: "Content analysis automation", db_source: "lupo_contents" }
  inbound_edges:
    - { from: "docs/database/lupopedia/tables/lupo_actors.md", type: "references", weight: 0.9, last_seen: "20260227" }
    - { from: "docs/database/lupopedia/tables/lupo_collections.md", type: "references", weight: 0.8, last_seen: "20260227" }
    - { from: "docs/database/lupopedia/tables/lupo_departments.md", type: "references", weight: 0.7, last_seen: "20260227" }
    - { from: "docs/database/lupopedia/tables/lupo_federation_nodes.md", type: "references", weight: 0.7, last_seen: "20260227" }
    - { from: "docs/database/lupopedia/tables/lupo_dialog_messages.md", type: "references", weight: 0.6, last_seen: "20260227" }
    - { from: "docs/database/lupopedia/tables/lupo_edges.md", type: "references", weight: 0.8, last_seen: "20260227" }
  semantic_tags: ["content_management", "core_system", "high_volume", "flares", "storage", "hierarchical", "versioning", "engagement"]
  version: "4.0.47"
  last_verified: "20260227"
  last_verified_by: "windsurf"
---

# 📄 Table: lupo_contents

**Purpose:** Core content management and storage for all Lupopedia content  
**Type:** Core System Table  
**Status:** ✅ Production Ready  
**Volume:** High (primary content storage)

---

## 🎯 **Overview**

The `lupo_contents` table is the central content storage system for Lupopedia, managing all types of content including articles, documentation, pages, and media metadata. It serves as the foundation for the content management system with support for hierarchical content, versioning, engagement tracking, and FLARE protocol integration.

### **Key Responsibilities**
- **Content Storage:** Primary repository for all content types
- **Hierarchical Management:** Parent-child content relationships
- **Version Control:** Content versioning and revision history
- **Engagement Tracking:** Likes, shares, views, and interactions
- **FLARE Integration:** File-level attribute and relationship exchange
- **Federation Support:** Multi-node content distribution
- **Search & Discovery:** SEO keywords, tags, and categorization

---

## 🗃️ **Schema Reference**

### **Primary Key**
- **`content_id`** (BIGINT) - YYYYMMDDHHMMSS UTC timestamp, unique identifier

### **Core Content Fields**
| Field | Type | Description | Notes |
|-------|------|-------------|-------|
| `title` | VARCHAR(255) NOT NULL | Content title | Required |
| `slug` | VARCHAR(255) NOT NULL | URL-friendly slug | Required |
| `custom_path` | VARCHAR(255) | Custom file path override | Optional |
| `description` | TEXT | Content description/summary | Optional |
| `seo_keywords` | VARCHAR(500) | SEO keywords | Optional |
| `body` | TEXT | Main content body | Markdown/HTML |
| `content` | TEXT | Alternate content field | Backup content |
| `content_type` | VARCHAR(50) | Content type | Default: 'article' |
| `format` | VARCHAR(20) | Content format | Default: 'markdown' |

### **Relationship Fields**
| Field | Type | Reference | Description |
|-------|------|-----------|-------------|
| `content_parent_id` | BIGINT | lupo_contents.content_id | Hierarchical parent |
| `federation_node_id` | BIGINT | lupo_federation_nodes.federation_node_id | Federation node |
| `department_id` | BIGINT | lupo_departments.department_id | Owning department |
| `actor_id` | BIGINT | lupo_actors.actor_id | Content author |
| `default_collection_id` | BIGINT | lupo_collections.collection_id | Default collection |

### **Status & Visibility**
| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `status` | VARCHAR(64) | 'draft' | Content status |
| `visibility` | VARCHAR(64) | 'public' | Visibility level |
| `is_template` | TINYINT | 0 | Template flag |
| `is_active` | TINYINT | 1 | Active flag |
| `is_deleted` | TINYINT | 0 | Soft delete flag |

### **Timestamp Fields**
| Field | Type | Format | Description |
|-------|------|--------|-------------|
| `created_ymdhis` | BIGINT | YYYYMMDDHHIISS UTC | Creation timestamp |
| `updated_ymdhis` | BIGINT | YYYYMMDDHHIISS UTC | Last update |
| `deleted_ymdhis` | BIGINT | YYYYMMDDHHIISS UTC | Soft delete time |
| `file_last_modified_utc` | BIGINT | YYYYMMDDHHIISS UTC | File modification |

### **Engagement Metrics**
| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `view_count` | INT | 0 | Total views |
| `share_count` | INT | 0 | Total shares |
| `likes_total` | INT | 0 | Total likes |
| `shares_total` | INT | 0 | Total shares (consolidated) |

### **FLARE Integration Fields**
| Field | Type | Description |
|-------|------|-------------|
| `file_path_from_root` | VARCHAR(500) | FLIP Header: path from repo root |
| `file_last_modified_system_version` | VARCHAR(20) | FLIP: system version at file edit |
| `utc_cycle` | VARCHAR(64) | UTC cycle identifier |
| `triage_status` | VARCHAR(64) | Triage status |
| `triage_notes` | TEXT | Triage notes |
| `dialog_notes` | TEXT | Dialog-related notes |

### **JSON Consolidated Fields**
| Field | Type | Source | Description |
|-------|------|--------|-------------|
| `content_sections` | JSON | Native | Content sections structure |
| `tags` | JSON | Native | Content tags |
| `atom_mappings` | JSON | lupo_content_atom_map | Atom relationships |
| `category_mappings` | JSON | lupo_content_category_map | Category relationships |
| `content_events` | JSON | lupo_content_events | Content events |
| `hashtags` | JSON | lupo_content_hashtag | Hashtag mappings |
| `inbound_links` | JSON | lupo_content_inbound_links | Inbound links |
| `like_users` | JSON | lupo_content_likes | User likes |
| `media_attachments` | JSON | lupo_content_media | Media files |
| `question_mappings` | JSON | lupo_content_question_map | Question relationships |
| `content_references` | JSON | lupo_content_references | Content references |
| `revision_history` | JSON | lupo_content_revisions | Revision history |
| `share_users` | JSON | lupo_content_shares | User shares |
| `tag_relationships` | JSON | lupo_content_tag_relationships | Tag relationships |

---

## 🔗 **Relationships & Dependencies**

### **Primary Relationships**
- **Self-Referencing:** `content_parent_id` → `content_id` (hierarchical)
- **Author:** `actor_id` → `lupo_actors.actor_id`
- **Department:** `department_id` → `lupo_departments.department_id`
- **Federation:** `federation_node_id` → `lupo_federation_nodes.federation_node_id`
- **Collection:** `default_collection_id` → `lupo_collections.collection_id`

### **FLARE Integration**
- **Edge Storage:** Content relationships stored in `lupo_edges`
- **File Mapping:** `file_path_from_root` maps to actual files
- **Version Tracking:** `file_last_modified_system_version` for FLARE versioning
- **Relationship Discovery:** Automated via FLARE Edge Suggester

### **Consolidated Tables**
This table has consolidated functionality from multiple legacy tables:
- `lupo_content_atom_map` → `atom_mappings`
- `lupo_content_category_map` → `category_mappings`
- `lupo_content_engagement_summary` → `likes_total`, `shares_total`
- `lupo_content_events` → `content_events`
- `lupo_content_hashtag` → `hashtags`
- `lupo_content_inbound_links` → `inbound_links`
- `lupo_content_likes` → `like_users`
- `lupo_content_media` → `media_attachments`
- `lupo_content_question_map` → `question_mappings`
- `lupo_content_references` → `content_references`
- `lupo_content_revisions` → `revision_history`
- `lupo_content_shares` → `share_users`
- `lupo_content_tag_relationships` → `tag_relationships`

---

## 📊 **Indexes & Performance**

### **Primary Indexes**
- **PRIMARY:** `content_id` (unique)
- **Unique Slug:** `lupo_contents_unique_content_slug_domain` (federation_node_id, slug)
- **Custom Path:** `lupo_contents_idx_custom_path` (unique)

### **Performance Indexes**
- **Parent:** `lupo_contents_idx_content_parent` (hierarchical queries)
- **Type:** `lupo_contents_idx_content_type` (content type filtering)
- **Status:** `lupo_contents_idx_status` (status filtering)
- **Visibility:** `lupo_contents_idx_visibility` (visibility filtering)
- **Active:** `lupo_contents_idx_is_active` (active content)
- **Deleted:** `lupo_contents_idx_is_deleted` (soft delete filtering)

### **Time-Based Indexes**
- **Created:** `lupo_contents_idx_created_ymdhis` (chronological queries)
- **Updated:** `lupo_contents_idx_updated_ymdhis` (recent updates)

### **Relationship Indexes**
- **User:** `lupo_contents_idx_user` (actor_id)
- **Department:** `lupo_contents_idx_department` (department_id)
- **Domain:** `lupo_contents_idx_domain` (federation_node_id)
- **File Path:** `lupo_contents_idx_file_path_from_root` (FLARE integration)

### **Engagement Indexes**
- **Likes/Shares:** `lupo_contents_idx_has_likes_shares` (engagement queries)

### **JSON Indexes (Conditional)**
- **Events:** `lupo_contents_idx_has_events` (content_events)
- **Hashtags:** `lupo_contents_idx_has_hashtags` (hashtags)
- **Media:** `lupo_contents_idx_has_media` (media_attachments)

---

## 🚀 **Usage Patterns**

### **Common Queries**

#### **Basic Content Retrieval**
```sql
SELECT content_id, title, slug, status, visibility
FROM lupo_contents 
WHERE is_deleted = 0 AND is_active = 1
ORDER BY created_ymdhis DESC;
```

#### **Hierarchical Content**
```sql
SELECT parent.content_id, parent.title, child.content_id as child_id, child.title as child_title
FROM lupo_contents parent
JOIN lupo_contents child ON child.content_parent_id = parent.content_id
WHERE parent.is_deleted = 0 AND child.is_deleted = 0;
```

#### **Author Content**
```sql
SELECT content_id, title, status, created_ymdhis
FROM lupo_contents 
WHERE actor_id = :actor_id AND is_deleted = 0
ORDER BY updated_ymdhis DESC;
```

#### **FLARE File Mapping**
```sql
SELECT content_id, title, file_path_from_root, file_last_modified_utc
FROM lupo_contents 
WHERE file_path_from_root IS NOT NULL 
  AND file_path_from_root != ''
  AND is_deleted = 0;
```

#### **Content by Type**
```sql
SELECT content_type, COUNT(*) as count
FROM lupo_contents 
WHERE is_deleted = 0 AND is_active = 1
GROUP BY content_type
ORDER BY count DESC;
```

### **FLARE Integration Patterns**

#### **Edge Discovery**
```python
# Find content relationships
python scripts/flare_edge_suggester.py --database lupopedia --table lupo_contents --include-db
```

#### **File Synchronization**
```sql
# Update file modification times
UPDATE lupo_contents 
SET file_last_modified_utc = :timestamp, 
    file_last_modified_system_version = :version
WHERE file_path_from_root = :path;
```

---

## ⚡ **Performance Considerations**

### **High-Volume Operations**
- **INSERT:** Content creation (moderate frequency)
- **UPDATE:** Content edits and status changes (high frequency)
- **SELECT:** Content retrieval (very high frequency)
- **DELETE:** Soft deletes (low frequency)

### **Optimization Tips**
1. **Use is_deleted = 0** in all queries to filter deleted content
2. **Index file_path_from_root** for FLARE operations
3. **Partition by federation_node_id** for multi-node deployments
4. **Cache status queries** for frequently accessed content
5. **Use JSON indexes** sparingly - they can impact performance

### **Scaling Considerations**
- **Read Replicas:** Content queries benefit from read scaling
- **Partitioning:** Consider by date or federation node for large datasets
- **Archiving:** Move old content to archive tables
- **CDN Integration:** Static content delivery for performance

---

## 🔍 **FLARE Protocol Integration**

### **File-Level Attributes**
- **Path Mapping:** `file_path_from_root` maps database to filesystem
- **Version Tracking:** `file_last_modified_system_version` tracks FLARE versions
- **Timestamp Sync:** `file_last_modified_utc` syncs with file modification times

### **Relationship Exchange**
- **Edge Storage:** Content relationships stored in `lupo_edges` table
- **Discovery:** Automated edge discovery via content analysis
- **Validation:** FLARE headers validated against database records

### **Content Workflow**
1. **File Created:** FLARE headers added to file
2. **Content Ingested:** Database record created with file metadata
3. **Relationships Discovered:** FLARE Edge Suggester analyzes content
4. **Edges Stored:** Relationships stored in `lupo_edges` table
5. **Validation:** Automated validation ensures consistency

---

## 📋 **Data Integrity**

### **Constraints**
- **Unique Slug:** Per federation node (domain + slug)
- **Unique Path:** Custom paths must be unique
- **Required Fields:** title, slug, timestamps
- **Default Values:** Sensible defaults for most fields

### **Validation Rules**
- **Timestamp Format:** YYYYMMDDHHIISS UTC
- **Status Values:** draft, published, archived, etc.
- **Visibility Levels:** public, private, restricted, etc.
- **Content Types:** article, page, document, media, etc.

### **Soft Delete Strategy**
- **is_deleted = 1:** Marks content as deleted
- **deleted_ymdhis:** Records deletion timestamp
- **Retention:** Deleted content retained for recovery
- **Cleanup:** Periodic cleanup of old deleted content

---

## 🚨 **Common Issues & Solutions**

### **Performance Issues**
- **Slow Queries:** Add appropriate indexes for query patterns
- **JSON Overhead:** Use JSON indexes selectively
- **Large Content:** Consider TEXT vs JSON for large data

### **Data Consistency**
- **Orphaned Records:** Check foreign key constraints
- **Duplicate Slugs:** Enforce unique constraints
- **Missing Metadata:** Validate required fields

### **FLARE Integration**
- **Path Mismatches:** Validate file paths exist
- **Version Conflicts:** Check system version consistency
- **Timestamp Sync:** Ensure UTC timestamp accuracy

---

## 📞 **Maintenance & Operations**

### **Regular Tasks**
- **Index Maintenance:** Rebuild indexes periodically
- **Statistics Update:** Update table statistics for query optimizer
- **Cleanup:** Remove old deleted content
- **Validation:** Check data integrity and FLARE consistency

### **Monitoring**
- **Query Performance:** Monitor slow queries
- **Storage Usage:** Track table growth
- **Error Rates:** Monitor FLARE validation errors
- **Engagement Metrics:** Track content interaction patterns

### **Backup & Recovery**
- **Regular Backups:** Daily backups recommended
- **Point-in-Time Recovery:** Use binary logs for recovery
- **FLARE Metadata:** Backup FLARE header files
- **Testing:** Regular recovery testing

---

## 🔮 **Future Enhancements**

### **Planned Improvements**
- **Full-Text Search:** Integrated search capabilities
- **Content Versioning:** Enhanced version control
- **Workflow States:** More granular content workflows
- **Analytics Integration:** Advanced engagement analytics

### **FLARE Enhancements**
- **Automated Validation:** Real-time FLARE validation
- **Relationship Discovery:** Enhanced edge discovery algorithms
- **Content Intelligence:** AI-powered content analysis
- **Cross-Repository:** Multi-repository content management

---

*This table documentation is part of the FLARE relationship automation initiative. For the complete database context, see the lupopedia database README and the 4.0.47 development thread.*

