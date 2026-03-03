---
flare.headers:
  file_path_from_root: "docs/database/lupopedia/tables/lupo_actors.md"
  system_version: "4.0.48"
  channel_id: 1
  actor_id: 1003
  last_modified_utc: "20260227"
  delegation_chain: "1001:10000"
  artifact_type: "table_documentation"
  purpose: "Complete documentation for lupo_actors table - unified actor identity and management system"
  mood_rgb: "4B0082"
  artifact_kind: "table"
  traits: ["canonical", "core_system", "identity_management", "unified_model"]
  tags: ["database", "actors", "identity", "users", "agents", "unified"]
  lupo_agent: "antigravity"
  # Table-specific metadata from TOON
  lupo_actors.actor_id: "BIGINT primary key containing YYYYMMDDHHMMSS UTC timestamp"
  lupo_actors.actor_type: "VARCHAR(64) NOT NULL type of actor (system, human, agent, etc.)"
  lupo_actors.slug: "VARCHAR(255) NOT NULL unique URL-friendly identifier"
  lupo_actors.name: "VARCHAR(255) NOT NULL display name"
  lupo_actors.created_ymdhis: "BIGINT NOT NULL DEFAULT 0 YYYYMMDDHHIISS UTC timestamp"
  lupo_actors.updated_ymdhis: "BIGINT NOT NULL YYYYMMDDHHIISS UTC timestamp"
  lupo_actors.is_active: "TINYINT NOT NULL DEFAULT 1 active status flag"
  lupo_actors.is_deleted: "TINYINT NOT NULL DEFAULT 0 soft delete flag"
  lupo_actors.deleted_ymdhis: "BIGINT YYYYMMDDHHIISS UTC soft delete timestamp"
  lupo_actors.actor_source_id: "BIGINT source system identifier"
  lupo_actors.actor_source_type: "VARCHAR(64) source system type"
  lupo_actors.metadata: "TEXT legacy metadata field"
  lupo_actors.adversarial_role: "VARCHAR(64) DEFAULT 'none' adversarial role designation"
  lupo_actors.adversarial_oversight_actor_id: "BIGINT oversight actor for adversarial actors"
  lupo_actors.avatar_hash: "VARCHAR(64) avatar image hash"
  lupo_actors.primary_federation_node_id: "BIGINT NOT NULL DEFAULT 1 primary federation node"
  lupo_actors.department_id: "BIGINT department assignment"
  lupo_actors.is_kernel: "TINYINT NOT NULL DEFAULT 0 kernel/system actor flag"
  lupo_actors.can_login: "TINYINT NOT NULL DEFAULT 0 login capability flag"
  lupo_actors.metadata_json: "JSON structured metadata and properties (Identity Capsule source)"
  lupo_actors.identity_provider_config: "JSON identity provider configuration"
  lupo_actors.paired_actor_id: "BIGINT NOT NULL DEFAULT 0 paired actor relationship"
  lupo_actors.is_agent: "TINYINT NOT NULL DEFAULT 0 AI agent flag"
  lupo_actors.actor_root_path: "VARCHAR(512) filesystem path to actor directory"
  lupo_actors.who_json_sync_status: "VARCHAR(64) status of WHO.json synchronization"
  lupo_actors.last_sync_ymdhis: "BIGINT YYYYMMDDHHIISS of last filesystem sync"
  table_primary_key: "actor_id"
  table_engine: "InnoDB"
  table_charset: "utf8mb4"
  table_collation: "utf8mb4_unicode_ci"
  table_indexes: ["PRIMARY", "lupo_actors_idx_actor_type", "lupo_actors_idx_created_ymdhis", "lupo_actors_idx_is_active", "lupo_actors_unique_slug"]
  table_foreign_keys: ["primary_federation_node_id", "department_id", "adversarial_oversight_actor_id", "paired_actor_id"]

# 💡 FLARE Edge Automation Tip:
# Use the FLARE Edge Suggester Tool to automatically discover and suggest edges:
# python scripts/flare_edge_suggester.py --file <path> --include-db --format yaml
# This will analyze content, TOON schemas, and database relationships to suggest
# appropriate outbound_edges with weights, reasons, and discovery methods.

flare.edges:
  outbound_edges:
    - { to: "lupo-database/lupopedia/toon/lupo_actors.toon.json", type: "schema_reference", weight: 1.0, reason: "TOON schema definition", db_source: "lupo_actors" }
    - { to: "actors/registry.json", type: "references", weight: 1.0, reason: "Actor registry and configuration", db_source: "lupo_actors" }
    - { to: "docs/database/lupopedia/tables/lupo_contents.md", type: "references", weight: 0.9, reason: "Content author relationships", db_source: "lupo_actors" }
    - { to: "docs/database/lupopedia/tables/lupo_channels.md", type: "references", weight: 0.9, reason: "Channel ownership and participation", db_source: "lupo_actors" }
    - { to: "docs/database/lupopedia/tables/lupo_dialog_messages.md", type: "references", weight: 0.8, reason: "Dialog message authorship", db_source: "lupo_actors" }
    - { to: "docs/database/lupopedia/tables/lupo_sessions.md", type: "references", weight: 0.8, reason: "User session management", db_source: "lupo_actors" }
    - { to: "docs/database/lupopedia/tables/lupo_artifacts.md", type: "references", weight: 0.7, reason: "Artifact ownership", db_source: "lupo_actors" }
    - { to: "docs/database/lupopedia/tables/lupo_departments.md", type: "references", weight: 0.7, reason: "Department assignments", db_source: "lupo_actors" }
    - { to: "docs/database/lupopedia/tables/lupo_federation_nodes.md", type: "references", weight: 0.7, reason: "Federation node assignments", db_source: "lupo_actors" }
    - { to: "docs/database/lupopedia/tables/lupo_actor_history.md", type: "references", weight: 0.9, reason: "Actor achievement and legacy history", db_source: "lupo_actors" }
    - { to: "docs/database/lupopedia/tables/lupo_actor_events.md", type: "references", weight: 0.9, reason: "Actor behavioral stream", db_source: "lupo_actors" }
  inbound_edges:
    - { from: "actors/registry.json", type: "references", weight: 1.0, last_seen: "20260227" }
    - { from: "docs/database/lupopedia/tables/lupo_contents.md", type: "references", weight: 0.9, last_seen: "20260227" }
    - { from: "docs/database/lupopedia/tables/lupo_channels.md", type: "references", weight: 0.9, last_seen: "20260227" }
    - { from: "docs/database/lupopedia/tables/lupo_dialog_messages.md", type: "references", weight: 0.8, last_seen: "20260227" }
    - { from: "docs/database/lupopedia/tables/lupo_sessions.md", type: "references", weight: 0.8, last_seen: "20260227" }
    - { from: "docs/database/lupopedia/tables/lupo_artifacts.md", type: "references", weight: 0.7, last_seen: "20260227" }
    - { from: "docs/database/lupopedia/tables/lupo_departments.md", type: "references", weight: 0.7, last_seen: "20260227" }
    - { from: "docs/database/lupopedia/tables/lupo_federation_nodes.md", type: "references", weight: 0.7, last_seen: "20260227" }
  semantic_tags: ["identity_management", "unified_actor_model", "authentication", "authorization", "federation", "agents", "identity_capsules"]
  version: "4.0.48"
  last_verified: "20260227"
  last_verified_by: "antigravity"
---

# 👥 Table: lupo_actors

**Purpose:** Unified actor identity and management system for all entities in Lupopedia.  
**Type:** Core System Table  
**Status:** ✅ Production Ready  
**Volume:** Medium (identity storage)

---

## 🎯 **Overview**

The `lupo_actors` table implements a unified actor model that serves as the single source of truth for all identities in Lupopedia, including human users, AI agents, system processes, and external entities. As of v4.0.48, it also serves as the database anchor for the **Identity Capsule** system, tracking synchronization with the filesystem-based `WHO.json` and actor directories.

### **Key Responsibilities**
- **Unified Identity:** Single source of truth for all actor types.
- **Actor Types:** Support for humans, AI agents, system processes, external entities.
- **Federation Support:** Multi-node actor management.
- **Authentication:** Login capabilities and identity provider integration.
- **Identity Portability:** Tracks synchronization with the `actors/` directory structure.
- **Adversarial Management:** Oversight and control of adversarial actors.
- **Metadata Storage:** Flexible JSON metadata for actor properties.

---

## 🗃️ **Schema Reference**

### **Primary Key**
- **`actor_id`** (BIGINT) - YYYYMMDDHHMMSS UTC timestamp, unique identifier.

### **Core Identity Fields**
| Field | Type | Description | Notes |
|-------|------|-------------|-------|
| `actor_type` | VARCHAR(64) NOT NULL | Type of actor | system, human, agent, external |
| `slug` | VARCHAR(255) NOT NULL | Unique URL-friendly identifier | Must be unique |
| `name` | VARCHAR(255) NOT NULL | Display name | Human-readable name |

### **Status & Management Fields**
| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `is_active` | TINYINT | 1 | Active status flag |
| `is_deleted` | TINYINT | 0 | Soft delete flag |
| `is_kernel` | TINYINT | 0 | Kernel/system actor flag |
| `can_login` | TINYINT | 0 | Login capability flag |
| `is_agent` | TINYINT | 0 | AI agent flag |

### **Identity Capsule & Sync Fields (v4.0.48)**
| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `actor_root_path` | VARCHAR(512) | 'actors/{id}' | Root directory for the actor capsule |
| `who_json_sync_status` | VARCHAR(64) | 'pending' | Sync status with WHO.json |
| `last_sync_ymdhis` | BIGINT | 0 | Timestamp of last filesystem sync |

### **Timestamp Fields**
| Field | Type | Format | Description |
|-------|------|--------|-------------|
| `created_ymdhis` | BIGINT | YYYYMMDDHHIISS UTC | Creation timestamp |
| `updated_ymdhis` | BIGINT | YYYYMMDDHHIISS UTC | Last update |
| `deleted_ymdhis` | BIGINT | YYYYMMDDHHIISS UTC | Soft delete time |

### **Relationship Fields**
| Field | Type | Reference | Description |
|-------|------|-----------|-------------|
| `primary_federation_node_id` | BIGINT | lupo_federation_nodes.id | Primary federation node |
| `department_id` | BIGINT | lupo_departments.id | Department assignment |
| `adversarial_oversight_actor_id` | BIGINT | lupo_actors.actor_id | Oversight actor |
| `paired_actor_id` | BIGINT | lupo_actors.actor_id | Paired actor relationship |

---

## 🔗 **Relationships & Dependencies**

### **Primary Relationships**
- **Federation:** Every actor is anchored to a `primary_federation_node_id`.
- **Organization:** Actors are mapped to departments via `department_id` or `lupo_actor_departments`.
- **Governance:** Adversarial actors are monitored via `adversarial_oversight_actor_id`.

### **Referencing Tables**
- **lupo_actor_history:** Detailed legacy and contribution history.
- **lupo_actor_events:** Real-time behavioral event stream.
- **lupo_auth_users:** Authentication details for human actors.
- **lupo_agents:** Technical configuration for AI agents.
- **lupo_session_recovery:** Active session state snapshots.

---

## 🚀 **Usage Patterns**

### **Identity Capsule Sync**
Queries used by the sync service to find actors requiring filesystem refresh.

```sql
SELECT actor_id, actor_root_path, metadata_json 
FROM lupo_actors 
WHERE who_json_sync_status = 'outdated' 
   OR last_sync_ymdhis < updated_ymdhis 
  AND is_deleted = 0;
```

### **Active Human Users**
Retrieving primary contact info for active human participants.

```sql
SELECT a.actor_id, a.name, au.email 
FROM lupo_actors a
JOIN lupo_auth_users au ON a.actor_id = au.auth_user_id
WHERE a.actor_type = 'human' AND a.is_active = 1;
```

---

## 🛡️ **Security & Privacy**

### **IP Address Tracking**
- **Identity Integrity**: Actor creation and sensitive status changes (e.g., `is_active` toggle) are logged in `lupo_actor_events` including the initiating IP address.
- **Locality Awareness**: The `primary_federation_node_id` determines which node has authority over IP-based authentication policies for that specific actor.

### **Data Sovereignty**
- The **Identity Capsule** (`actor_root_path`) is the portable home for an actor. If an actor migrates node, this path and its database mirror are exported as a signed bundle.

---

*This table documentation is part of the v4.0.48 Identity Persistence update.*
