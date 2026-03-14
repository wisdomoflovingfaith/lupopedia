# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: ".\docs\database\lupopedia\tables\lupo_actor_departments.md"
  file_hash: "5cb216663aafbcc24d5a682301dd4d48d8ae8971e3949a4bc6e13c36aadbd0ec"
  last_updated_utc: "20260228155738"
  system_version: "4.0.51"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "documentation"
  artifact_kind: "documentation"
  purpose: "Documentation file with LUPOPEDIA HEADERS applied"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.51"]
  tags: ["documentation", "flare_applied"]
  lupo_agent: "windsurf"

lupopedia.edges:
  outbound_edges:
    - { to: "CHANGELOG.md", type: "references", weight: 1.0 }
    - { to: "lupo-docs/doctrine/", type: "references", weight: 1.0 }

lupopedia.footer:
  last_verified: "20260228155738"
  last_verified_by: "windsurf"
---

# LUPOPEDIA HEADERS (replaces FLARE)

---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  lupopedia.edges: []
  file_path_from_root: "lupo-docs\database\lupopedia\tables\lupo_actor_departments.md"
  file_hash: "ab39efa9e2d42dbf1a8bf6f3812857e9a93ed0c3c7b3a386206d12d9aa00b57c"
  file_path_from_root: "lupo-docs\database\lupopedia\tables\lupo_actor_departments.md"
  file_hash: "6617c70b93784bc8698dac2d7fa2a65df9b0df4821c2f50202bea6a643543736"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for lupo_actor_departments.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "database", "lupopedia", "tables", "lupo_actor_departmentsmd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
lupopedia.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
    deprecation_notes: ["Legacy Wolfie/FLIP block preserved; migrate tools to use lupopedia.headers"]
---

---
wolfie.headers: {
  file_path_from_root: "lupo-docs/database/lupopedia/tables/lupo_actor_departments.md",
  system_version: "4.0.48",
  channel_id: 1,
  actor_id: 1003,
  created_ymdhis: 20260227000000,
  updated_ymdhis: 20260227000000,
  message_type: "table_documentation",
  visibility: "public",
  priority: "high",
  mood_rgb: "4B0082",
  artifact_kind: "table",
  traits: ["canonical", "organization_mapping", "department_assignment"],
  tags: ["database", "departments", "organization", "actors", "mapping"]
}
flip.footer: {
  outbound_edges: [
    { to: "lupo-database/lupopedia/toon/lupo_actor_departments.toon.json", type: "schema_reference", weight: 1.0 },
    { to: "lupo-docs/database/lupopedia/tables/lupo_actors.md", type: "references", weight: 0.9 },
    { to: "lupo-docs/database/lupopedia/tables/lupo_departments.md", type: "references", weight: 0.9 }
  ],
  semantic_tags: ["organization_hierarchy", "routing_logic", "departmental_identity"]
}
---

# 🏢 Table: lupo_actor_departments

**Purpose:** Maps actors to specific organizational departments for routing, reporting, and authorization context.  
**Type:** Organizational Mapping Table  
**Status:** ✅ Production Ready  
**Volume:** Medium (one or more departments per actor)

---

## 🎯 **Overview**

The `lupo_actor_departments` table defines the organizational context for actors. While an actor exists globally in `lupo_actors`, their organizational responsibility (e.g., "Customer Support", "Core Development") is defined here. This supports department-based routing of messages and tasks, as well as department-scoped security.

### **Key Responsibilities**
- **Membership Management:** Tracks which actors belong to which business or technical units.
- **Relational Context:** Stores the actor's specific `title` or designation within the department (e.g., "Senior Lead").
- **Routing Optimization:** Enables the system to route tasks (`current_focus.json`) to the most appropriate departmental pool.
- **Permission Layering:** Acts as the bridge to `lupo_department_roles` for intermediate authorization.

---

## 🗃️ **Schema Reference**

### **Primary Key**
- **`actor_department_id`** (BIGINT) - Unique assignment identifier.

### **Core Mapping Fields**
| Field | Type | Description | Notes |
|-------|------|-------------|-------|
| `actor_id` | BIGINT | The actor being assigned | |
| `department_id` | BIGINT | The organizational unit | |
| `title` | VARCHAR(255) | Department-specific role title | e.g., 'Chief Architect' |

### **Metadata & Status**
| Field | Type | Default | Description |
|-------|------|---------|-------------|
| `is_primary` | TINYINT | 1 | Flag for the actor's main department |
| `created_ymdhis` | BIGINT | 0 | YYYYMMDDHHIISS of assignment |
| `is_deleted` | TINYINT | 0 | Soft-delete mapping |

---

## 🔗 **Relationships & Dependencies**

### **Primary Relationships**
- **Actor:** `actor_id` → `lupo_actors.actor_id`
- **Department:** `department_id` → `lupo_departments.department_id`

### **Authorization Order**
Membership in this table is a prerequisite for roles in `lupo_department_roles`. It represents the "Level 2" permission context in the Lupopedia security model.

---

## 🚀 **Usage Patterns**

### **Retrieving Department Staff**
Finding all active agents and humans in the "Security" department.

```sql
SELECT a.name, ad.title, a.actor_type
FROM lupo_actor_departments ad
JOIN lupo_actors a ON ad.actor_id = a.actor_id
JOIN lupo_departments d ON ad.department_id = d.department_id
WHERE d.department_name = 'Security' AND ad.is_deleted = 0;
```

### **Primary Department Resolution**
Determining an actor's "Home Department" for task attribution.

```sql
SELECT department_id, title 
FROM lupo_actor_departments 
WHERE actor_id = :actor_id AND is_primary = 1 AND is_deleted = 0;
```

---

## 🛡️ **Security & Privacy**

- **IP Scoping:** Some departments (e.g., 'Financials') may have strict IP-whitelist requirements enforced during session establishment for any actor associated with them.
- **Attribute Sensitivity:** Departmental associations are part of the actor's Identity Capsule and exported during portability events.
- **Access Control:** De-assigning an actor from a department immediately revokes all department-scoped roles.

---

*This documentation is part of the v4.0.48 Organizational Identity framework.*
