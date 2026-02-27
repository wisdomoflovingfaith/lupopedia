---
flare.headers:
  file_path_from_root: "docs/database/lupopedia/tables/lupo_dialog_threads.md"
  system_version: "4.0.48"
  channel_id: 1
  actor_id: 1003
  last_modified_utc: "20260226"
  delegation_chain: "1003:10000"
  artifact_type: "table_documentation"
  purpose: "Complete documentation for lupo_dialog_threads table - conversation threads management"
  mood_rgb: "4169E1"
  artifact_kind: "table"
  traits: ["canonical", "dialog_system", "communication"]
  tags: ["database", "dialog", "threads", "collaboration"]
  lupo_agent: "antigravity"
  # Table-specific metadata from TOON
  lupo_dialog_threads.dialog_thread_id: "BIGINT PRIMARY KEY unique identifier for the dialog thread"
  lupo_dialog_threads.thread_id: "BIGINT legacy or external reference thread identifier"
  lupo_dialog_threads.federation_node_id: "BIGINT references federation node"
  lupo_dialog_threads.channel_id: "BIGINT references lupo_channels.channel_id"
  lupo_dialog_threads.project_slug: "VARCHAR(100) slug identifying the project context"
  lupo_dialog_threads.task_name: "VARCHAR(255) name of the associated task"
  lupo_dialog_threads.created_by_actor_id: "BIGINT references lupo_actors.actor_id of thread creator"
  lupo_dialog_threads.summary_text: "TEXT brief summary of the conversation"
  lupo_dialog_threads.bg_color: "CHAR(6) UI background color hex code"
  lupo_dialog_threads.text_color: "CHAR(6) UI text color hex code"
  lupo_dialog_threads.alt_text_color: "CHAR(6) UI secondary text color hex code"
  lupo_dialog_threads.status: "VARCHAR(64) thread lifecycle status (Open, Closed, etc.)"
  lupo_dialog_threads.artifacts: "JSON list of artifacts associated with this thread"
  lupo_dialog_threads.metadata_json: "JSON extensible metadata storage"
  lupo_dialog_threads.created_ymdhis: "BIGINT YYYYMMDDHHIISS UTC creation timestamp"
  lupo_dialog_threads.updated_ymdhis: "BIGINT YYYYMMDDHHIISS UTC last update timestamp"
  lupo_dialog_threads.is_deleted: "TINYINT soft delete flag"
  lupo_dialog_threads.deleted_ymdhis: "BIGINT YYYYMMDDHHIISS UTC deletion timestamp"
  lupo_dialog_threads.escalated_to_operator_id: "BIGINT references actor ID of human/operator escalated to"
  lupo_dialog_threads.escalation_reason: "VARCHAR(255) reason for escalation"
  lupo_dialog_threads.escalation_timestamp: "BIGINT YYYYMMDDHHIISS escalation time"
  table_primary_key: "dialog_thread_id"
  table_engine: "InnoDB"
  table_charset: "utf8mb4"
  table_collation: "utf8mb4_unicode_ci"
  table_indexes: ["PRIMARY", "lupo_dialog_threads_idx_channel", "lupo_dialog_threads_idx_created", "lupo_dialog_threads_idx_created_by_actor", "lupo_dialog_threads_idx_deleted", "lupo_dialog_threads_idx_node", "lupo_dialog_threads_idx_project", "lupo_dialog_threads_idx_status", "lupo_dialog_threads_idx_task", "lupo_dialog_threads_idx_updated"]
  table_foreign_keys: ["channel_id", "created_by_actor_id", "escalated_to_operator_id"]

flare.edges:
  outbound_edges:
- { to: "docs/toons/lupo_dialog_threads.toon.json", type: "schema_reference", weight: 1.0, reason: "TOON schema definition" }
    - { to: "docs/database/lupopedia/tables/lupo_channels.md", type: "references", weight: 0.9, reason: "Threads are contained within channels" }
    - { to: "docs/database/lupopedia/tables/lupo_actors.md", type: "references", weight: 0.8, reason: "Thread creators and escalated operators" }
    - { to: "docs/database/lupopedia/tables/lupo_dialog_messages.md", type: "references", weight: 0.9, reason: "Messages belong to threads" }
  semantic_tags: ["dialog", "collaboration", "communication", "threads", "escalation"]
  version: "4.0.48"
  last_verified: "20260226"
  last_verified_by: "antigravity"
---

# 🧵 Table: lupo_dialog_threads

**Purpose:** Conversation threads management for the Lupopedia dialog system  
**Type:** Communication System Table  
**Status:** ✅ Production Ready  
**Volume:** Medium/High (growing with agent activity)

---

## 🎯 **Overview**

The `lupo_dialog_threads` table manages conversational context within the Lupopedia Semantic OS. It groups individual `lupo_dialog_messages` into logical units (threads), allowing agents and humans to collaborate on specific tasks, projects, or channels. It includes support for UI styling (colors), escalation to human operators, and metadata grouping.

### **Key Responsibilities**
- **Context Grouping:** Grouping messages into specific conversations.
- **Workflow Tracking:** Managing thread status (Open, Closed, Escalated).
- **UI Customization:** Storing brand/context colors for UI rendering.
- **Escalation Management:** Tracking when a conversation requires human intervention.
- **Artifact Association:** Linking many artifacts to a single conversation context.

---

## 🗃️ **Schema Reference**

### **Primary Key**
- **`dialog_thread_id`** (BIGINT) - Unique identifier for the thread.

### **Core Identity & Context**
| Field | Type | Description | Notes |
|-------|------|-------------|-------|
| `thread_id` | BIGINT | Legacy/External reference ID | Defaults to 0 |
| `channel_id` | BIGINT | References `lupo_channels.channel_id` | Optional |
| `project_slug` | VARCHAR(100) | Semantic project identifier | e.g., 'antigravity-ide' |
| `task_name` | VARCHAR(255) | Name of associated task | |
| `created_by_actor_id` | BIGINT | References `lupo_actors.actor_id` | Thread creator |

### **Content & Summary**
| Field | Type | Description |
|-------|------|-------------|
| `summary_text` | TEXT | Human-readable summary of the thread |
| `status` | VARCHAR(64) | Lifecycle state (e.g., 'Open', 'Closed', 'Archived') |
| `artifacts` | JSON | List of associated artifact IDs or paths |
| `metadata_json` | JSON | Additional unstructured metadata |

### **UI & Presentation**
| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `bg_color` | CHAR(6) | 'FFFFFF' | Background hex color |
| `text_color` | CHAR(6) | '000000' | Primary text hex color |
| `alt_text_color`| CHAR(6) | '666666' | Secondary/Time text color |

### **Escalation**
| Field | Type | Description |
|-------|------|-------------|
| `escalated_to_operator_id` | BIGINT | References human operator actor_id |
| `escalation_reason` | VARCHAR(255) | Reason for handoff |
| `escalation_timestamp` | BIGINT | YYYYMMDDHHIISS of escalation |

### **Timestamp Fields**
| Field | Type | Format | Description |
|-------|------|--------|-------------|
| `created_ymdhis` | BIGINT | YYYYMMDDHHIISS UTC | Creation time |
| `updated_ymdhis` | BIGINT | YYYYMMDDHHIISS UTC | Last activity/update |
| `deleted_ymdhis` | BIGINT | YYYYMMDDHHIISS UTC | Soft delete time |

---

## 📊 **Indexes & Performance**

### **Primary Indexes**
- **PRIMARY:** `dialog_thread_id`
- **Context:** `lupo_dialog_threads_idx_channel` (Quick lookups per channel)
- **Project:** `lupo_dialog_threads_idx_project` (Group by project)

### **Status & Workflow**
- **Status:** `lupo_dialog_threads_idx_status` (Filter active vs archived)
- **Actor:** `lupo_dialog_threads_idx_created_by_actor` (Filter my threads)

### **Performance Considerations**
1. **Soft Delete:** Always include `WHERE is_deleted = 0` when listing active threads.
2. **Growth:** JSON `artifacts` field should be monitored; if list grows too large, consider a mapping table.

---

## 🚀 **Usage Patterns**

### **Common Queries**

#### **List Active Threads in Channel**
```sql
SELECT dialog_thread_id, task_name, summary_text 
FROM lupo_dialog_threads 
WHERE channel_id = :cid AND is_deleted = 0 AND status = 'Open'
ORDER BY updated_ymdhis DESC;
```

#### **Escalate Thread to Human**
```sql
UPDATE lupo_dialog_threads 
SET status = 'Escalated', 
    escalated_to_operator_id = :op_id, 
    escalation_reason = :reason,
    escalation_timestamp = :now_ymdhis
WHERE dialog_thread_id = :tid;
```

---

## 🔍 **FLARE Protocol Integration**

Threads map directly to the `channels/[channel_id]/threads/[thread_name]/` directory on the filesystem.
- `project_slug` or `task_name` often determines the `thread_name` in the filesystem.
- `summary_text` is typically extracted for the `meta.json` in the thread directory.

---

