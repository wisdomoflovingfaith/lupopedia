---
flare.headers:
  file_path_from_root: "docs/database/lupopedia/tables/lupo_actors.md"
  system_version: "4.0.47"
  channel_id: 1
  actor_id: 1001
  last_modified_utc: "20260227"
  delegation_chain: "1001:10000"
  artifact_type: "table_documentation"
  purpose: "Complete documentation for lupo_actors table - unified actor identity and management system"
  mood_rgb: "4B0082"
  artifact_kind: "table"
  traits: ["canonical", "core_system", "identity_management", "unified_model"]
  tags: ["database", "actors", "identity", "users", "agents", "unified"]
  lupo_agent: "windsurf"
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
  lupo_actors.metadata_json: "JSON structured metadata and properties"
  lupo_actors.identity_provider_config: "JSON identity provider configuration"
  lupo_actors.paired_actor_id: "BIGINT NOT NULL DEFAULT 0 paired actor relationship"
  lupo_actors.is_agent: "TINYINT NOT NULL DEFAULT 0 AI agent flag"
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

flare.footer:
  outbound_edges:
    - { to: "docs/toons/lupo_actors.toon.json", type: "schema_reference", weight: 1.0, reason: "TOON schema definition", db_source: "lupo_actors" }
    - { to: "actors/registry.json", type: "references", weight: 1.0, reason: "Actor registry and configuration", db_source: "lupo_actors" }
    - { to: "docs/database/lupopedia/tables/lupo_contents.md", type: "references", weight: 0.9, reason: "Content author relationships", db_source: "lupo_actors" }
    - { to: "docs/database/lupopedia/tables/lupo_channels.md", type: "references", weight: 0.9, reason: "Channel ownership and participation", db_source: "lupo_actors" }
    - { to: "docs/database/lupopedia/tables/lupo_dialog_messages.md", type: "references", weight: 0.8, reason: "Dialog message authorship", db_source: "lupo_actors" }
    - { to: "docs/database/lupopedia/tables/lupo_sessions.md", type: "references", weight: 0.8, reason: "User session management", db_source: "lupo_actors" }
    - { to: "docs/database/lupopedia/tables/lupo_artifacts.md", type: "references", weight: 0.7, reason: "Artifact ownership", db_source: "lupo_actors" }
    - { to: "docs/database/lupopedia/tables/lupo_departments.md", type: "references", weight: 0.7, reason: "Department assignments", db_source: "lupo_actors" }
    - { to: "docs/database/lupopedia/tables/lupo_federation_nodes.md", type: "references", weight: 0.7, reason: "Federation node assignments", db_source: "lupo_actors" }
    - { to: "docs/FLARE_HEADERS_COMPLETE_REFERENCE.md", type: "references", weight: 0.9, reason: "FLARE protocol documentation", db_source: "lupo_actors" }
    - { to: "scripts/flare_edge_suggester.py", type: "implements", weight: 1.0, reason: "Actor relationship discovery automation", db_source: "lupo_actors" }
  inbound_edges:
    - { from: "actors/registry.json", type: "references", weight: 1.0, last_seen: "20260227" }
    - { from: "docs/database/lupopedia/tables/lupo_contents.md", type: "references", weight: 0.9, last_seen: "20260227" }
    - { from: "docs/database/lupopedia/tables/lupo_channels.md", type: "references", weight: 0.9, last_seen: "20260227" }
    - { from: "docs/database/lupopedia/tables/lupo_dialog_messages.md", type: "references", weight: 0.8, last_seen: "20260227" }
    - { from: "docs/database/lupopedia/tables/lupo_sessions.md", type: "references", weight: 0.8, last_seen: "20260227" }
    - { from: "docs/database/lupopedia/tables/lupo_artifacts.md", type: "references", weight: 0.7, last_seen: "20260227" }
    - { from: "docs/database/lupopedia/tables/lupo_departments.md", type: "references", weight: 0.7, last_seen: "20260227" }
    - { from: "docs/database/lupopedia/tables/lupo_federation_nodes.md", type: "references", weight: 0.7, last_seen: "20260227" }
  semantic_tags: ["identity_management", "unified_actor_model", "authentication", "authorization", "federation", "agents"]
  version: "4.0.47"
  last_verified: "20260227"
  last_verified_by: "windsurf"
---

# 👥 Table: lupo_actors

**Purpose:** Unified actor identity and management system for all entities in Lupopedia  
**Type:** Core System Table  
**Status:** ✅ Production Ready  
**Volume:** Medium (identity storage)

---

## 🎯 **Overview**

The `lupo_actors` table implements a unified actor model that serves as the single source of truth for all identities in Lupopedia, including human users, AI agents, system processes, and external entities. This table replaces multiple legacy user/agent tables and provides a comprehensive identity management system with support for federation, adversarial roles, and flexible metadata.

### **Key Responsibilities**
- **Unified Identity:** Single source of truth for all actor types
- **Actor Types:** Support for humans, AI agents, system processes, external entities
- **Federation Support:** Multi-node actor management
- **Authentication:** Login capabilities and identity provider integration
- **Authorization:** Role-based access and permissions
- **Adversarial Management:** Oversight and control of adversarial actors
- **Metadata Storage:** Flexible JSON metadata for actor properties

---

## 🗃️ **Schema Reference**

### **Primary Key**
- **`actor_id`** (BIGINT) - YYYYMMDDHHMMSS UTC timestamp, unique identifier

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

### **Timestamp Fields**
| Field | Type | Format | Description |
|-------|------|--------|-------------|
| `created_ymdhis` | BIGINT | YYYYMMDDHHIISS UTC | Creation timestamp |
| `updated_ymdhis` | BIGINT | YYYYMMDDHHIISS UTC | Last update |
| `deleted_ymdhis` | BIGINT | YYYYMMDDHHIISS UTC | Soft delete time |

### **Relationship Fields**
| Field | Type | Reference | Description |
|-------|------|-----------|-------------|
| `primary_federation_node_id` | BIGINT | lupo_federation_nodes.federation_node_id | Primary federation node |
| `department_id` | BIGINT | lupo_departments.department_id | Department assignment |
| `adversarial_oversight_actor_id` | BIGINT | lupo_actors.actor_id | Oversight actor |
| `paired_actor_id` | BIGINT | lupo_actors.actor_id | Paired actor relationship |

### **Source & Integration Fields**
| Field | Type | Description | Notes |
|-------|------|-------------|-------|
| `actor_source_id` | BIGINT | Source system identifier | External system ID |
| `actor_source_type` | VARCHAR(64) | Source system type | LDAP, OAuth, etc. |

### **Security & Adversarial Fields**
| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `adversarial_role` | VARCHAR(64) | 'none' | Adversarial role designation |
| `avatar_hash` | VARCHAR(64) | Avatar image hash | For avatar management |

### **Metadata Fields**
| Field | Type | Description | Notes |
|-------|------|-------------|-------|
| `metadata` | TEXT | Legacy metadata field | Deprecated, use metadata_json |
| `metadata_json` | JSON | Structured metadata and properties | Flexible actor properties |
| `identity_provider_config` | JSON | Identity provider configuration | OAuth, LDAP, etc. |

---

## 🔗 **Relationships & Dependencies**

### **Primary Relationships**
- **Federation:** `primary_federation_node_id` → `lupo_federation_nodes.federation_node_id`
- **Department:** `department_id` → `lupo_departments.department_id`
- **Oversight:** `adversarial_oversight_actor_id` → `lupo_actors.actor_id` (self-reference)
- **Paired:** `paired_actor_id` → `lupo_actors.actor_id` (self-reference)

### **Referencing Tables**
- **lupo_contents:** Content author relationships
- **lupo_channels:** Channel ownership and participation
- **lupo_dialog_messages:** Message authorship
- **lupo_sessions:** User session management
- **lupo_artifacts:** Artifact ownership
- **lupo_auth_users:** Authentication details (human users)

---

## 📊 **Indexes & Performance**

### **Primary Indexes**
- **PRIMARY:** `actor_id` (unique)
- **Unique Slug:** `lupo_actors_unique_slug` (slug)

### **Performance Indexes**
- **Actor Type:** `lupo_actors_idx_actor_type` (type filtering)
- **Created:** `lupo_actors_idx_created_ymdhis` (chronological queries)
- **Active:** `lupo_actors_idx_is_active` (active status filtering)

---

## 🚀 **Usage Patterns**

### **Common Queries**

#### **Basic Actor Retrieval**
```sql
SELECT actor_id, actor_type, slug, name, is_active
FROM lupo_actors 
WHERE is_deleted = 0 AND is_active = 1
ORDER BY created_ymdhis;
```

#### **Actors by Type**
```sql
SELECT actor_type, COUNT(*) as count
FROM lupo_actors 
WHERE is_deleted = 0
GROUP BY actor_type
ORDER BY count DESC;
```

#### **Active Human Users**
```sql
SELECT actor_id, name, slug, created_ymdhis
FROM lupo_actors 
WHERE actor_type = 'human' 
  AND is_active = 1 
  AND can_login = 1 
  AND is_deleted = 0
ORDER BY created_ymdhis DESC;
```

#### **AI Agents**
```sql
SELECT actor_id, name, slug, metadata_json
FROM lupo_actors 
WHERE is_agent = 1 
  AND is_active = 1 
  AND is_deleted = 0
ORDER BY name;
```

#### **Adversarial Actors**
```sql
SELECT a.actor_id, a.name, a.adversarial_role, o.name as oversight_actor
FROM lupo_actors a
LEFT JOIN lupo_actors o ON a.adversarial_oversight_actor_id = o.actor_id
WHERE a.adversarial_role != 'none' 
  AND a.is_deleted = 0;
```

---

## ⚡ **Performance Considerations**

### **High-Volume Operations**
- **INSERT:** Actor creation (low frequency)
- **UPDATE:** Status and metadata updates (moderate frequency)
- **SELECT:** Actor lookup (high frequency)
- **DELETE:** Soft deletes (very low frequency)

### **Optimization Tips**
1. **Use is_deleted = 0** in all queries to filter deleted actors
2. **Index actor_type** for type-based filtering
3. **Cache active actors** for frequent lookups
4. **Use metadata_json** for structured data (not metadata field)
5. **Consider partitioning** by actor_type for large datasets

---

## 📋 **Data Integrity**

### **Constraints**
- **Unique Slug:** slug must be unique across all actors
- **Required Fields:** actor_id, actor_type, slug, name
- **Default Values:** Sensible defaults for status flags
- **Soft Delete:** is_deleted flag for safe deletion

### **Validation Rules**
- **Timestamp Format:** YYYYMMDDHHIISS UTC
- **Actor Types:** Standardized actor type values
- **JSON Validation:** Valid JSON structure for metadata_json
- **Self-Reference:** Valid actor_id for oversight and paired relationships

---

## 👥 **Actor Types**

### **System Actors**
- **system (ID: 0):** Core system processes
- **kernel:** Kernel-level system actors
- **daemon:** Background system processes

### **Human Users**
- **human:** Regular human users
- **admin:** Administrative users
- **moderator:** Content moderators

### **AI Agents**
- **agent:** General AI agents
- **assistant:** AI assistant agents
- **analyzer:** Analysis and processing agents

### **External Entities**
- **external:** External system entities
- **federation:** Federated actors
- **api:** API-based actors

---

## 🔐 **Security & Authentication**

### **Login Capabilities**
- **can_login = 1:** Actor can authenticate and login
- **can_login = 0:** System-only or service actor
- **Identity Providers:** Configured via identity_provider_config

### **Adversarial Management**
- **Oversight:** Each adversarial actor has oversight
- **Roles:** Defined adversarial role types
- **Monitoring:** Special handling for adversarial activities

---

## 🚨 **Common Issues & Solutions**

### **Performance Issues**
- **Large Metadata:** Keep metadata_json reasonable size
- **Self-References:** Proper handling of oversight/paired relationships
- **Type Filtering:** Use actor_type index for efficient queries

### **Data Consistency**
- **Orphaned Relationships:** Validate oversight and paired actor references
- **Duplicate Slugs:** Enforce unique constraint
- **Metadata Validation:** Ensure JSON structure validity

---

## 🔮 **Future Enhancements**

### **Planned Improvements**
- **Advanced Federation:** Multi-node actor synchronization
- **Role-Based Access:** Enhanced permission system
- **Audit Logging:** Comprehensive actor activity tracking
- **AI Agent Management:** Advanced agent lifecycle management

---

*This table documentation is part of the FLARE relationship automation initiative. For the complete database context, see the lupopedia database README and the 4.0.47 development thread.*
