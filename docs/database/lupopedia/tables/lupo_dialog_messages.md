---
flare.headers:
  file_path_from_root: "docs/database/lupopedia/tables/lupo_dialog_messages.md"
  system_version: "4.0.48"
  channel_id: 1
  actor_id: 1003
  last_modified_utc: "20260226"
  delegation_chain: "1003:10000"
  artifact_type: "table_documentation"
  purpose: "Complete documentation for lupo_dialog_messages table - individual conversation messages"
  mood_rgb: "4169E1"
  artifact_kind: "table"
  traits: ["canonical", "dialog_system", "messaging"]
  tags: ["database", "dialog", "messages", "communication"]
  lupo_agent: "antigravity"
  # Table-specific metadata from TOON
  lupo_dialog_messages.dialog_message_id: "BIGINT PRIMARY KEY unique identifier"
  lupo_dialog_messages.message_id: "BIGINT legacy or external reference identifier"
  lupo_dialog_messages.dialog_thread_id: "BIGINT references lupo_dialog_threads.dialog_thread_id"
  lupo_dialog_messages.channel_id: "BIGINT references lupo_channels.channel_id"
  lupo_dialog_messages.from_actor_id: "BIGINT references lupo_actors.actor_id (Sender)"
  lupo_dialog_messages.to_actor_id: "BIGINT references lupo_actors.actor_id (Recipient)"
  lupo_dialog_messages.read_by_actor_id: "BIGINT references actor ID who last read this"
  lupo_dialog_messages.read_by_actor_utc: "BIGINT YYYYMMDDHHIISS of read event"
  lupo_dialog_messages.message_text: "VARCHAR(1000) primary message snippet or header"
  lupo_dialog_messages.message_type: "VARCHAR(64) type of message (text, broadcast, task, doctrine)"
  lupo_dialog_messages.metadata_json: "JSON extensible metadata"
  lupo_dialog_messages.mood_rgb: "CHAR(6) hex color code representing actor mood during message"
  lupo_dialog_messages.mood_framework: "VARCHAR(32) emotional framework used"
  lupo_dialog_messages.created_ymdhis: "BIGINT YYYYMMDDHHIISS UTC creation"
  lupo_dialog_messages.updated_ymdhis: "BIGINT YYYYMMDDHHIISS UTC last update"
  lupo_dialog_messages.is_deleted: "TINYINT soft delete"
  lupo_dialog_messages.deleted_ymdhis: "BIGINT YYYYMMDDHHIISS UTC deletion"
  lupo_dialog_messages.message_body: "MEDIUMTEXT the full content of the message"
  table_primary_key: "dialog_message_id"
  table_engine: "InnoDB"
  table_charset: "utf8mb4"
  table_collation: "utf8mb4_unicode_ci"
  table_indexes: ["PRIMARY", "lupo_dialog_doctrine_idx_channel", "lupo_dialog_doctrine_idx_created", "lupo_dialog_doctrine_idx_deleted", "lupo_dialog_doctrine_idx_dialog_thread_id", "lupo_dialog_doctrine_idx_message_type", "lupo_dialog_doctrine_idx_read_by_actor", "lupo_dialog_doctrine_idx_read_utc", "lupo_dialog_doctrine_idx_to_actor_id", "lupo_dialog_doctrine_idx_updated"]
  table_foreign_keys: ["dialog_thread_id", "channel_id", "from_actor_id", "to_actor_id", "read_by_actor_id"]

flare.edges:
  outbound_edges:
- { to: "docs/toons/lupo_dialog_messages.toon.json", type: "schema_reference", weight: 1.0, reason: "TOON schema definition" }
    - { to: "docs/database/lupopedia/tables/lupo_dialog_threads.md", type: "references", weight: 1.0, reason: "Messages belong to threads" }
    - { to: "docs/database/lupopedia/tables/lupo_actors.md", type: "references", weight: 0.9, reason: "Message senders and recipients" }
    - { to: "docs/database/lupopedia/tables/lupo_channels.md", type: "references", weight: 0.8, reason: "Broad channel context" }
  semantic_tags: ["messaging", "dialog", "collaboration", "mood_tracking"]
  version: "4.0.48"
  last_verified: "20260226"
  last_verified_by: "antigravity"
---

# 💬 Table: lupo_dialog_messages

**Purpose:** Individual conversation messages within the Lupopedia dialog system  
**Type:** Communication System Table  
**Status:** ✅ Production Ready  
**Volume:** High (primary communication data)

---

## 🎯 **Overview**

The `lupo_dialog_messages` table stores every atomic communication event in the system, whether between AI agents, human users, or system broadcasts. It replaces the legacy `lupo_dialog_doctrine` table, adopting a more generic "message" nomenclature. 

### **Key Responsibilities**
- **Communication Log:** Persistent storage of all dialog.
- **Identity Attribution:** Tracking who sent the message to whom.
- **Read State Tracking:** Managing read receipts via `read_by_actor_id`.
- **Emotional Geometry:** Capturing actor mood at the time of messaging (`mood_rgb`).
- **Data Scaling:** Using `message_text` for fast previews and `message_body` for large content.

---

## 🗃️ **Schema Reference**

### **Primary Key**
- **`dialog_message_id`** (BIGINT) - Unique identifier for the message.

### **Core Identity & Routing**
| Field | Type | Reference | Description |
|-------|------|-----------|-------------|
| `dialog_thread_id` | BIGINT | `lupo_dialog_threads` | Parent thread |
| `channel_id` | BIGINT | `lupo_channels` | Parent channel |
| `from_actor_id` | BIGINT | `lupo_actors` | Sender ID |
| `to_actor_id` | BIGINT | `lupo_actors` | Recipient ID |

### **Content Fields**
| Field | Type | Description |
|-------|------|-------------|
| `message_text` | VARCHAR(1000) | Primary text (used for previews/indexing) |
| `message_body` | MEDIUMTEXT | Full message content (no character limit) |
| `message_type` | VARCHAR(64) | e.g., 'text', 'task', 'broadcast', 'doctrine' |

### **Metadata & Mood**
| Field | Type | Description |
|-------|------|-------------|
| `mood_rgb` | CHAR(6) | Actor mood hex code |
| `mood_framework` | VARCHAR(32) | Framework (default: 'western_analytical')|
| `metadata_json` | JSON | Extended properties (e.g., UI hints) |

### **Timestamp Fields**
| Field | Type | Format | Description |
|-------|------|--------|-------------|
| `created_ymdhis` | BIGINT | YYYYMMDDHHIISS UTC | Creation time |
| `updated_ymdhis` | BIGINT | YYYYMMDDHHIISS UTC | Last update |
| `read_by_actor_utc`| BIGINT | YYYYMMDDHHIISS UTC | Time of first read |

---

## 📊 **Indexes & Performance**

### **Primary Indexes**
- **PRIMARY:** `dialog_message_id`
- **Thread:** `lupo_dialog_doctrine_idx_dialog_thread_id` (Crucial for conversation view)
- **Recipient:** `lupo_dialog_doctrine_idx_to_actor_id` (Checking my inbox)

### **Performance Considerations**
1. **Index Names:** Current indexes are still prefixed with `lupo_dialog_doctrine_`. Future migrations should rename these to `lupo_dialog_messages_`.
2. **Body Storage:** `message_body` is only fetched when viewing a single message or full thread to save bandwidth.

---

## 🚀 **Usage Patterns**

### **Common Queries**

#### **Fetch Thread History**
```sql
SELECT from_actor_id, message_text, created_ymdhis 
FROM lupo_dialog_messages 
WHERE dialog_thread_id = :tid AND is_deleted = 0
ORDER BY created_ymdhis ASC;
```

#### **Mark Thread as Read**
```sql
UPDATE lupo_dialog_messages 
SET read_by_actor_id = :aid, 
    read_by_actor_utc = :now_ymdhis
WHERE dialog_thread_id = :tid 
  AND read_by_actor_id = 0;
```

---

## 🔍 **FLARE Protocol Integration**

Individual records in this table correspond to `.md` files in the `channels/[channel_id]/threads/[thread_name]/` directory.
- `from_actor_id` and `to_actor_id` are included in the `YYYYMMDDHHMMSS_[from]_[to]_[slug].md` filename.
- `message_body` becomes the markdown content of the file.
- `metadata_json` provides fields for `flare.headers`.

---

