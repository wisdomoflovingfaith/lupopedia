---
flare.headers:
  file_path_from_root: "docs/database/lupopedia/tables/lupo_channels.md"
  system_version: "4.0.47"
  channel_id: 1
  actor_id: 1001
  last_modified_utc: "20260227"
  delegation_chain: "1001:10000"
  artifact_type: "table_documentation"
  purpose: "Complete documentation for lupo_channels table - communication channel management and routing system"
  mood_rgb: "4B0082"
  artifact_kind: "table"
  traits: ["canonical", "core_system", "communication", "routing", "federation"]
  tags: ["database", "channels", "communication", "routing", "federation", "dialogs"]
  lupo_agent: "windsurf"
  # Table-specific metadata from TOON
  lupo_channels.channel_id: "BIGINT primary key containing YYYYMMDDHHMMSS UTC timestamp"
  lupo_channels.federation_node_id: "BIGINT NOT NULL references lupo_federation_nodes.federation_node_id"
  lupo_channels.created_by_actor_id: "BIGINT NOT NULL references lupo_actors.actor_id"
  lupo_channels.default_actor_id: "BIGINT NOT NULL DEFAULT 1 references lupo_actors.actor_id"
  lupo_channels.department_id: "BIGINT NOT NULL DEFAULT 1 references lupo_departments.department_id"
  lupo_channels.channel_key: "VARCHAR(64) NOT NULL unique channel identifier"
  lupo_channels.channel_slug: "VARCHAR(32) NOT NULL DEFAULT 'channel_key' URL-friendly slug"
  lupo_channels.channel_type: "VARCHAR(32) NOT NULL DEFAULT 'chat_room' type of channel"
  lupo_channels.language: "VARCHAR(16) NOT NULL DEFAULT 'en' channel language"
  lupo_channels.channel_name: "VARCHAR(255) NOT NULL display name"
  lupo_channels.description: "TEXT channel description and purpose"
  lupo_channels.website_link: "VARCHAR(512) related website link"
  lupo_channels.metadata_json: "TEXT legacy metadata field"
  lupo_channels.status_flag: "TINYINT NOT NULL DEFAULT 1 channel status"
  lupo_channels.end_ymdhis: "BIGINT channel end timestamp YYYYMMDDHHIISS UTC"
  lupo_channels.duration_seconds: "INT channel duration in seconds"
  lupo_channels.created_ymdhis: "BIGINT NOT NULL DEFAULT 0 YYYYMMDDHHIISS UTC timestamp"
  lupo_channels.updated_ymdhis: "BIGINT NOT NULL YYYYMMDDHHIISS UTC timestamp"
  lupo_channels.is_deleted: "TINYINT NOT NULL DEFAULT 0 soft delete flag"
  lupo_channels.deleted_ymdhis: "BIGINT YYYYMMDDHHIISS UTC soft delete timestamp"
  lupo_channels.aal_metadata_json: "JSON AAL (Actor Action Language) metadata"
  lupo_channels.fleet_composition_json: "JSON fleet composition and agent assignments"
  lupo_channels.awareness_version: "VARCHAR(20) DEFAULT '3.0.0' awareness system version"
  lupo_channels.channel_number: "INT numeric channel identifier"
  lupo_channels.parent_channel_id: "BIGINT parent channel for hierarchical channels"
  lupo_channels.is_kernel: "TINYINT NOT NULL DEFAULT 0 kernel/system channel flag"
  lupo_channels.boot_sequence_order: "INT kernel boot sequence order"
  table_primary_key: "channel_id"
  table_engine: "InnoDB"
  table_charset: "utf8mb4"
  table_collation: "utf8mb4_unicode_ci"
  table_indexes: ["PRIMARY", "lupo_channels_idx_awareness_version", "lupo_channels_idx_channel_key", "lupo_channels_idx_dates", "lupo_channels_idx_domain", "lupo_channels_idx_status", "lupo_channels_unq_channel_key_per_node"]
  table_foreign_keys: ["federation_node_id", "created_by_actor_id", "default_actor_id", "department_id", "parent_channel_id"]

# 💡 FLARE Edge Automation Tip:
# Use the FLARE Edge Suggester Tool to automatically discover and suggest edges:
# python scripts/flare_edge_suggester.py --file <path> --include-db --format yaml
# This will analyze content, TOON schemas, and database relationships to suggest
# appropriate outbound_edges with weights, reasons, and discovery methods.

flare.edges:
  outbound_edges:
- { to: "docs/toons/lupo_channels.toon.json", type: "schema_reference", weight: 1.0, reason: "TOON schema definition", db_source: "lupo_channels" }
    - { to: "channels/registry.json", type: "references", weight: 1.0, reason: "Channel registry and configuration", db_source: "lupo_channels" }
    - { to: "docs/database/lupopedia/tables/lupo_dialog_messages.md", type: "references", weight: 0.9, reason: "Dialog message routing and storage", db_source: "lupo_channels" }
    - { to: "docs/database/lupopedia/tables/lupo_actors.md", type: "references", weight: 0.9, reason: "Channel ownership and participation", db_source: "lupo_channels" }
    - { to: "docs/database/lupopedia/tables/lupo_departments.md", type: "references", weight: 0.8, reason: "Department channel assignments", db_source: "lupo_channels" }
    - { to: "docs/database/lupopedia/tables/lupo_federation_nodes.md", type: "references", weight: 0.8, reason: "Federation node channel distribution", db_source: "lupo_channels" }
    - { to: "docs/database/lupopedia/tables/lupo_contents.md", type: "references", weight: 0.7, reason: "Content channel relationships", db_source: "lupo_channels" }
    - { to: "docs/FLARE_HEADERS_COMPLETE_REFERENCE.md", type: "references", weight: 0.9, reason: "FLARE protocol documentation", db_source: "lupo_channels" }
    - { to: "scripts/flare_edge_suggester.py", type: "implements", weight: 1.0, reason: "Channel relationship discovery automation", db_source: "lupo_channels" }
  inbound_edges:
    - { from: "channels/registry.json", type: "references", weight: 1.0, last_seen: "20260227" }
    - { from: "docs/database/lupopedia/tables/lupo_dialog_messages.md", type: "references", weight: 0.9, last_seen: "20260227" }
    - { from: "docs/database/lupopedia/tables/lupo_actors.md", type: "references", weight: 0.9, last_seen: "20260227" }
    - { from: "docs/database/lupopedia/tables/lupo_departments.md", type: "references", weight: 0.8, last_seen: "20260227" }
    - { from: "docs/database/lupopedia/tables/lupo_federation_nodes.md", type: "references", weight: 0.8, last_seen: "20260227" }
    - { from: "docs/database/lupopedia/tables/lupo_contents.md", type: "references", weight: 0.7, last_seen: "20260227" }
  semantic_tags: ["communication", "routing", "federation", "dialogs", "channel_management", "awareness"]
  version: "4.0.47"
  last_verified: "20260227"
  last_verified_by: "windsurf"
---

# 📡 Table: lupo_channels

**Purpose:** Communication channel management and routing system for dialogs and interactions  
**Type:** Core System Table  
**Status:** ✅ Production Ready  
**Volume:** Medium (channel storage)

---

## 🎯 **Overview**

The `lupo_channels` table serves as the central routing and management system for all communication channels in Lupopedia. It handles dialog routing, federation distribution, department assignments, and provides the foundation for the awareness system. Channels can be hierarchical, support multiple languages, and include specialized metadata for fleet composition and actor action language (AAL) integration.

### **Key Responsibilities**
- **Channel Management:** Create and maintain communication channels
- **Dialog Routing:** Route messages to appropriate channels
- **Federation Support:** Multi-node channel distribution
- **Hierarchical Structure:** Parent-child channel relationships
- **Awareness Integration:** Support for awareness system versions
- **Fleet Management:** Agent fleet composition and assignments
- **Department Organization:** Channel department assignments

---

## 🗃️ **Schema Reference**

### **Primary Key**
- **`channel_id`** (BIGINT) - YYYYMMDDHHMMSS UTC timestamp, unique identifier

### **Core Channel Fields**
| Field | Type | Description | Notes |
|-------|------|-------------|-------|
| `channel_key` | VARCHAR(64) NOT NULL | Unique channel identifier | Must be unique per node |
| `channel_slug` | VARCHAR(32) NOT NULL | URL-friendly slug | Default: channel_key |
| `channel_type` | VARCHAR(32) NOT NULL | Type of channel | Default: 'chat_room' |
| `language` | VARCHAR(16) NOT NULL | Channel language | Default: 'en' |
| `channel_name` | VARCHAR(255) NOT NULL | Display name | Human-readable |
| `description` | TEXT | Channel description and purpose | Optional |
| `website_link` | VARCHAR(512) | Related website link | Optional |

### **Relationship Fields**
| Field | Type | Reference | Description |
|-------|------|-----------|-------------|
| `federation_node_id` | BIGINT | lupo_federation_nodes.federation_node_id | Federation node |
| `created_by_actor_id` | BIGINT | lupo_actors.actor_id | Channel creator |
| `default_actor_id` | BIGINT | lupo_actors.actor_id | Default actor |
| `department_id` | BIGINT | lupo_departments.department_id | Department assignment |
| `parent_channel_id` | BIGINT | lupo_channels.channel_id | Parent channel |

### **Status & Timing Fields**
| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `status_flag` | TINYINT | 1 | Channel status |
| `is_kernel` | TINYINT | 0 | Kernel/system channel flag |
| `is_deleted` | TINYINT | 0 | Soft delete flag |

### **Timestamp Fields**
| Field | Type | Format | Description |
|-------|------|--------|-------------|
| `created_ymdhis` | BIGINT | YYYYMMDDHHIISS UTC | Creation timestamp |
| `updated_ymdhis` | BIGINT | YYYYMMDDHHIISS UTC | Last update |
| `end_ymdhis` | BIGINT | YYYYMMDDHHIISS UTC | Channel end time |
| `deleted_ymdhis` | BIGINT | YYYYMMDDHHIISS UTC | Soft delete time |

### **Duration & Numbering Fields**
| Field | Type | Description | Notes |
|-------|------|-------------|-------|
| `duration_seconds` | INT | Channel duration in seconds | Optional |
| `channel_number` | INT | Numeric channel identifier | Optional |
| `boot_sequence_order` | INT | Kernel boot sequence order | For kernel channels |

### **Advanced Metadata Fields**
| Field | Type | Description | Notes |
|-------|------|-------------|-------|
| `metadata_json` | TEXT | Legacy metadata field | Deprecated |
| `aal_metadata_json` | JSON | AAL (Actor Action Language) metadata | Advanced features |
| `fleet_composition_json` | JSON | Fleet composition and agent assignments | Agent management |
| `awareness_version` | VARCHAR(20) | Awareness system version | Default: '3.0.0' |

---

## 🔗 **Relationships & Dependencies**

### **Primary Relationships**
- **Federation:** `federation_node_id` → `lupo_federation_nodes.federation_node_id`
- **Creator:** `created_by_actor_id` → `lupo_actors.actor_id`
- **Default Actor:** `default_actor_id` → `lupo_actors.actor_id`
- **Department:** `department_id` → `lupo_departments.department_id`
- **Parent:** `parent_channel_id` → `lupo_channels.channel_id` (self-reference)

### **Referencing Tables**
- **lupo_dialog_messages:** Messages routed to channels
- **lupo_contents:** Content associated with channels
- **lupo_actors:** Channel participants and owners

---

## 📊 **Indexes & Performance**

### **Primary Indexes**
- **PRIMARY:** `channel_id` (unique)
- **Unique Key:** `lupo_channels_unq_channel_key_per_node` (channel_key, federation_node_id)

### **Performance Indexes**
- **Channel Key:** `lupo_channels_idx_channel_key` (key lookup)
- **Status:** `lupo_channels_idx_status` (status filtering)
- **Domain:** `lupo_channels_idx_domain` (federation queries)
- **Dates:** `lupo_channels_idx_dates` (end_ymdhis queries)
- **Awareness:** `lupo_channels_idx_awareness_version` (version filtering)

---

## 🚀 **Usage Patterns**

### **Common Queries**

#### **Basic Channel Retrieval**
```sql
SELECT channel_id, channel_key, channel_name, channel_type, status_flag
FROM lupo_channels 
WHERE is_deleted = 0 AND status_flag = 1
ORDER BY created_ymdhis;
```

#### **Channels by Type**
```sql
SELECT channel_type, COUNT(*) as count
FROM lupo_channels 
WHERE is_deleted = 0
GROUP BY channel_type
ORDER BY count DESC;
```

#### **Active Channels**
```sql
SELECT channel_id, channel_name, language, created_ymdhis
FROM lupo_channels 
WHERE is_deleted = 0 
  AND status_flag = 1 
  AND (end_ymdhis IS NULL OR end_ymdhis > CURRENT_TIMESTAMP)
ORDER BY channel_name;
```

#### **Hierarchical Channels**
```sql
SELECT parent.channel_id, parent.channel_name, child.channel_id as child_id, child.channel_name as child_name
FROM lupo_channels parent
JOIN lupo_channels child ON child.parent_channel_id = parent.channel_id
WHERE parent.is_deleted = 0 AND child.is_deleted = 0;
```

#### **Federation Channels**
```sql
SELECT channel_id, channel_key, federation_node_id
FROM lupo_channels 
WHERE federation_node_id = :node_id AND is_deleted = 0
ORDER BY channel_key;
```

#### **Kernel Channels**
```sql
SELECT channel_id, channel_key, channel_name, boot_sequence_order
FROM lupo_channels 
WHERE is_kernel = 1 AND is_deleted = 0
ORDER BY boot_sequence_order, channel_id;
```

---

## ⚡ **Performance Considerations**

### **High-Volume Operations**
- **INSERT:** Channel creation (moderate frequency)
- **UPDATE:** Status and metadata updates (high frequency)
- **SELECT:** Channel lookup (very high frequency)
- **DELETE:** Soft deletes (low frequency)

### **Optimization Tips**
1. **Use is_deleted = 0** in all queries to filter deleted channels
2. **Index channel_key** for efficient channel lookup
3. **Consider partitioning** by federation_node_id for multi-node deployments
4. **Cache active channels** for frequent routing operations
5. **Use status_flag** for efficient active channel filtering

---

## 📋 **Data Integrity**

### **Constraints**
- **Unique Key:** (channel_key, federation_node_id) must be unique
- **Required Fields:** channel_id, federation_node_id, created_by_actor_id, channel_key, channel_name
- **Default Values:** Sensible defaults for optional fields
- **Soft Delete:** is_deleted flag for safe deletion

### **Validation Rules**
- **Timestamp Format:** YYYYMMDDHHIISS UTC
- **Channel Types:** Standardized channel type values
- **JSON Validation:** Valid JSON structure for metadata fields
- **Self-Reference:** Valid channel_id for parent relationships

---

## 📡 **Channel Types**

### **System Channels**
- **system:** Core system operations
- **kernel:** Kernel-level system channels
- **daemon:** Background system processes

### **Communication Channels**
- **chat_room:** General chat rooms
- **dialog:** Dialog-based conversations
- **thread:** Threaded discussions
- **broadcast:** One-way communications

### **Specialized Channels**
- **development:** Development discussions
- **support:** Customer support
- **notification:** System notifications
- **analytics:** Analytics and reporting

---

## 🔗 **Federation Support**

### **Multi-Node Distribution**
- **Node Assignment:** Each channel assigned to federation node
- **Unique Per Node:** channel_key unique within each node
- **Cross-Node:** Channels can span multiple nodes
- **Synchronization:** Channel metadata synchronized across nodes

### **Routing Logic**
- **Local First:** Route to local channels when possible
- **Federation Fallback:** Route to other nodes when needed
- **Load Balancing:** Distribute channels across nodes
- **Failover:** Handle node failures gracefully

---

## 🤖 **Awareness Integration**

### **Awareness Versions**
- **Version Tracking:** awareness_version field for compatibility
- **Feature Detection:** Channel capabilities based on version
- **Migration:** Support for version upgrades
- **Compatibility:** Backward compatibility maintenance

### **AAL Metadata**
- **Actor Action Language:** Structured action definitions
- **Fleet Composition:** Agent assignments and roles
- **Automation:** Automated channel management
- **Integration:** External system integration

---

## 🚨 **Common Issues & Solutions**

### **Performance Issues**
- **Large Metadata:** Keep JSON metadata reasonable size
- **Hierarchical Queries:** Optimize parent-child lookups
- **Federation Overhead:** Minimize cross-node queries

### **Data Consistency**
- **Orphaned Channels:** Validate parent channel references
- **Duplicate Keys:** Enforce unique constraints
- **Metadata Validation:** Ensure JSON structure validity

---

## 🔮 **Future Enhancements**

### **Planned Improvements**
- **Advanced Routing:** Intelligent message routing
- **Channel Templates:** Predefined channel configurations
- **Real-time Sync:** Live federation synchronization
- **Enhanced Analytics:** Channel usage analytics

---

*This table documentation is part of the FLARE relationship automation initiative. For the complete database context, see the lupopedia database README and the 4.0.47 development thread.*

