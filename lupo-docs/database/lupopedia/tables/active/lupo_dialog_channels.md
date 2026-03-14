---
lupopedia.headers:
  file_path_from_root: "lupo-docs/database/lupopedia/tables/lupo_dialog_channels.md"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1003
  last_modified_utc: "20260226"
  delegation_chain: "1003:10000"
  artifact_type: "table_documentation"
  purpose: "Complete documentation for lupo_dialog_channels table - channel source configuration and metadata"
  mood_rgb: "4169E1"
  artifact_kind: "table"
  traits: ["canonical", "dialog_system", "configuration"]
  tags: ["database", "dialog", "channels", "metadata"]
  lupo_agent: "antigravity"
  # Table-specific metadata from TOON
  lupo_dialog_channels.channel_id: "BIGINT PRIMARY KEY matching lupo_channels.channel_id"
  lupo_dialog_channels.channel_name: "VARCHAR(255) unique slug for the channel"
  lupo_dialog_channels.file_source: "VARCHAR(255) path to the filesystem source"
  lupo_dialog_channels.title: "VARCHAR(500) descriptive title"
  lupo_dialog_channels.description: "TEXT channel description"
  lupo_dialog_channels.speaker: "VARCHAR(100) default speaker/actor slug"
  lupo_dialog_channels.target: "VARCHAR(100) default target/actor slug"
  lupo_dialog_channels.categories: "JSON channel categories"
  lupo_dialog_channels.collections: "JSON channel collections"
  lupo_dialog_channels.channels: "JSON related channels"
  lupo_dialog_channels.tags: "JSON channel tags"
  lupo_dialog_channels.version: "VARCHAR(20) channel metadata version"
  lupo_dialog_channels.status: "VARCHAR(64) DEFAULT 'published' channel status"
  lupo_dialog_channels.author: "VARCHAR(100) author of the channel metadata"
  lupo_dialog_channels.created_timestamp: "BIGINT creation timestamp"
  lupo_dialog_channels.modified_timestamp: "BIGINT modification timestamp"
  lupo_dialog_channels.message_count: "INT total message count (summary)"
  lupo_dialog_channels.metadata_json: "JSON extended metadata"
  table_primary_key: "channel_id"
  table_engine: "InnoDB"
  table_charset: "utf8mb4"
  table_collation: "utf8mb4_unicode_ci"
  table_indexes: ["PRIMARY", "lupo_dialog_channels_idx_channel_name", "lupo_dialog_channels_idx_created_timestamp", "lupo_dialog_channels_idx_dialog_channels_composite", "lupo_dialog_channels_idx_file_source", "lupo_dialog_channels_idx_modified_timestamp", "lupo_dialog_channels_idx_speaker", "lupo_dialog_channels_idx_status", "lupo_dialog_channels_idx_target"]
  table_foreign_keys: []

lupopedia.edges:
  file_path_from_root: "lupo-docs\database\lupopedia\tables\lupo_dialog_channels.md"
  outbound_edges:
- { to: "lupo-database/lupopedia/toon/lupo_dialog_channels.toon.json", type: "schema_reference", weight: 1.0, reason: "TOON schema definition" }
    - { to: "lupo-docs/database/lupopedia/tables/lupo_channels.md", type: "references", weight: 0.9, reason: "Maps to core channel identity" }
  semantic_tags: ["channel_metadata", "configuration", "dialog_system"]
  version: "4.0.48"
  last_verified: "20260226"
  last_verified_by: "antigravity"
---

# 📺 Table: lupo_dialog_channels

**Purpose:** Detailed metadata and source configuration for Lupopedia channels  
**Type:** Configuration/Metadata Table  
**Status:** ✅ Production Ready  
**Volume:** Low (one record per channel)

---

## 🎯 **Overview**

The `lupo_dialog_channels` table provides the bridge between the high-level `lupo_channels` table and the specific filesystem/metadata configuration required for the Dialog System. It defines where a channel's files are stored, who the default "speaker" is, and various classification metadata (categories, tags, collections).

### **Key Responsibilities**
- **Source Mapping:** Linking a channel to its `file_source` directory.
- **Default Routing:** Defining default `speaker` and `target` for new messages.
- **Categorization:** Storing JSON-based classifications (categories, tags).
- **Metric Summary:** Maintaining `message_count` for quick UI display.

---

## 🗃️ **Schema Reference**

### **Primary Key**
- **`channel_id`** (BIGINT) - Unique identifier matching the ID in `lupo_channels`.

### **Core Identity & Routing**
| Field | Type | Description |
|-------|------|-------------|
| `channel_name` | VARCHAR(255) | Unique slug (e.g., 'general', 'dev-log') |
| `file_source` | VARCHAR(255) | Filesystem path relative to root |
| `speaker` | VARCHAR(100) | Default originating actor slug |
| `target` | VARCHAR(100) | Default receiving actor slug |

### **Presentation & Metadata**
| Field | Type | Description |
|-------|------|-------------|
| `title` | VARCHAR(500) | Human-readable title |
| `description` | TEXT | Long description of the channel |
| `categories` | JSON | Array of category slugs |
| `collections` | JSON | Array of collection IDs |
| `tags` | JSON | Array of tag slugs |

### **System Fields**
| Field | Type | Description |
|-------|------|-------------|
| `status` | VARCHAR(64) | e.g. 'published', 'draft', 'archived' |
| `version` | VARCHAR(20) | Metadata version string |
| `message_count` | INT | Denormalized count of messages |
| `created_timestamp`| BIGINT | Epoch-style creation timestamp |
| `modified_timestamp`| BIGINT | Epoch-style modification timestamp |

---

## 📊 **Indexes & Performance**

### **Primary Indexes**
- **PRIMARY:** `channel_id`
- **Lookup:** `lupo_dialog_channels_idx_channel_name` (Unique slug lookup)
- **Source:** `lupo_dialog_channels_idx_file_source` (Reverse lookup from path)

### **Performance Considerations**
1. **Denormalization:** `message_count` is updated via triggers or service logic to avoid heavy `COUNT(*)` queries on `lupo_dialog_messages`.
2. **Epoch Timestamps:** Note that this table currently uses `_timestamp` fields instead of the canonical `_ymdhis`. Developers should be careful when comparing these with system-wide `ymdhis` values.

---

## 🚀 **Usage Patterns**

### **Common Queries**

#### **Resolve Channel by Folder Path**
```sql
SELECT channel_id, channel_name, title 
FROM lupo_dialog_channels 
WHERE file_source = :folder_path;
```

---

## 🔍 **FLARE Protocol Integration**

This table acts as the registry for "Sources". When the system scans the `lupo-channels/` directory, it uses `lupo_dialog_channels` to map the found directories back to their semantic identities.

---
