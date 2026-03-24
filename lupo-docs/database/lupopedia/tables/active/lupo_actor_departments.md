---
lupopedia.headers:
  lupopedia.schema: database_table
  file_path_from_root: lupo-docs/database/lupopedia/tables/active/lupo_actor_departments.md
  web_path: '[lupo_actor_departments](http://www.lupopedia.com/database/lupopedia/tables/active/lupo_actor_departments)'
  last_modified_utc: '20260317'
  channel_id: 42
  actor_id: 102
  actor_name: cursor
  delegation_chain: cursor:root
  artifact_type: table_documentation
  artifact_kind: table
  namespace: core
  purpose: Documentation file with LUPOPEDIA HEADERS applied
  tags:
  - database
  - table
  - core
  when_updated: '20260324174654'
lupopedia.edges:
  comment: Snapshot of edges for lupo_actor_departments table doc at 4.0.79 (grounded
    by repo search; non-exhaustive).
  meta: php_hits=3 python_hits=3
  outbound_edges:
  - to: database.table.lupo_actor_departments
    type: DEFINES_SCHEMA_FOR
    weight: 1.0
  - to: lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql
    type: schema_reference
    weight: 1.0
  - to: check_db_state.php
    type: USED_IN_PHP
    weight: 0.6
  - to: lupo-database/lupopedia/content/lupo-app/Services/SavedCollectionsService.php
    type: USED_IN_PHP
    weight: 0.6
  - to: lupo-database/lupopedia/content/lupo-app/auth/AuthRoleResolver.php
    type: USED_IN_PHP
    weight: 0.6
  - to: analyze_unused_tables.py
    type: USED_IN_PYTHON
    weight: 0.5
  - to: lupo-scripts/rebuild_schema_from_toons.py
    type: USED_IN_PYTHON
    weight: 0.5
  - to: lupo-scripts/wolfie_orms.py
    type: USED_IN_PYTHON
    weight: 0.5
lupopedia.footer:
  last_verified: '20260317000000'
  last_verified_by: cursor
  last_verified_by_actor_id: 102
  orchestrator: cursor:root
---
# file: lupo_actor_departments ? web_path: http://www.lupopedia.com/database/lupopedia/tables/active/lupo_actor_departments
lupopedia.headers:
  \1"4.0.79"
  lupopedia.schema: "documentation"
  file_path_from_root: ".\docs\database\lupopedia\tables\lupo_actor_departments.md"
  file_hash: "5cb216663aafbcc24d5a682301dd4d48d8ae8971e3949a4bc6e13c36aadbd0ec"
  last_updated_utc: "20260228155738"
  \1"4.0.79"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "documentation"
  artifact_kind: "documentation"
  purpose: "Documentation file with LUPOPEDIA HEADERS applied"
  namespace: "core"
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


# 🏢 Table: lupo_actor_departments

**Purpose:** Maps actors to specific organizational departments for routing, reporting, and authorization context.  
**Type:** Organizational Mapping Table  
**Status:** ✅ Production Ready  
**Volume:** Medium (one or more departments per actor)


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


*This documentation is part of the v4.0.48 Organizational Identity framework.*
