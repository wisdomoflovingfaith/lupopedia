---
lupopedia.headers:
  header_format_version: "4.1.4"
  file_path_from_root: docs/prd/25_A_DEPARTMENTS_SYSTEM.md
  web_path: "https://www.lupopedia.com/lupopedia/docs/prd/25_A_DEPARTMENTS_SYSTEM.md"
  status: active
  when_updated: "20260422232349"
  trust_tier: canonical
  questions_toon: null
  memory_toon: memory/development/canonical/1026/04/25_departments_system.toon
  atoms_toon: null
  transcript_jsonl: 0/development/prd_files/departments-system
  artifact_type: prd
  artifact_kind: specification
  channel_key: development
  federation_node_id: 0
  thread_id: null
  content_id: null
  content_parent_id: null
  default_collection_id: null
  lupopedia.schema: prd
  prd_cluster: 00_A_25_A
  title: "PRD: Departments System"
  summary: null
---
# PRD: Departments System

## Overview

Lupopedia supports department-based access control through a mapping system that allows actors to be assigned to specific departments with defined permissions. This system provides granular access control while maintaining the flexibility of the agent-based architecture.

**Canonical mental model (approved):** **[`ACTOR_DEPARTMENT_AUTH_USER_DOCTRINE.md`](../doctrine/ACTOR_DEPARTMENT_AUTH_USER_DOCTRINE.md)** ???????? explains **`lupo_auth_user_departments`** and **`lupo_actor_departments`** together with visitor chat and act-as eligibility. This PRD defines **department structure and requirements**; do not contradict the doctrine.

### Root department (0) ???????? three seeded operator hybrids

Fresh seed (**`database/lupopedia/mysql/seed/seed_4.2.0.sql`**) places these actors in **`lupo_actor_departments`** for **`department_id = 0`** with **`role_key = hybrid`** (plus **system** rows for actor **0** and **ANUBIS 19**):

| Display / slug | `actor_name` (PK) | `actor_id` | Notes |
|----------------|-------------------|------------|--------|
| **Captain** / `captain` | `wolfie` | 1 | WOLFIE orchestrator hybrid; `actor_name` fixed per convergence doctrine |
| **Lilith** / `lilith` | `lilith` | 2 | LILITH critic hybrid |
| **COUNTERMEASURE** / `countermeasure` | `countermeasure` | 111 | Red-team hybrid; `adversarial_oversight_actor_id` = 2 (Lilith) |

Other coordination personas (**lexa** ??????? **asclepius**) remain in **`lupo_actors`** but are **not** assigned to department 0 in seed; assign them to departments via **`lupo_actor_departments`** when a product decision defines their scope.

**Crafty Syntax import** (**`import_from_old_crafty_syntax.sql`**): after legacy operator????????department rows are loaded, the import **re-inserts** the five root rows above (they are lost when **`lupo_actor_departments`** is truncated during import). For **each non-root department**, the import creates one **`human_agent`** row named from **`lupo_departments.name`**, **`actor_name` = `dept_{department_id}`**, **`actor_id` = 280000 + `department_id`**, **`actor_source_type` = `lupo_departments`**, **`metadata`** documents **`agent_model: wolfie`**, and links that actor to its department with **`role_key = hybrid`**. Root does not get a duplicate Wolfie-model row (captain/wolfie already covers department 0).

### Visitor chat routing (actor-first, department-scoped)

Visitor-facing chat should **attribute and route** work through **`actor_id`** in a **department** the visitor session is bound to (exact channel/session wiring is outside this PRD). **Which actors** may represent a department for chat follows **`lupo_actor_departments`** ???????? actors **belong to departments**, not to a single human user (see **[PRD 15](15_actors.md)**). This is **not** the legacy-only model ???????human operator row = sole chat identity without **`actor_id`**.??????? The normative visitor chain (optional LLM, **`auth_user`** fallback) is **[PRD 05](05_auth_user_actor_agent_transformation.md)**.

**LILITH audit (final, department model):** **Approved** ???????? root **0** hybrids (**WOLFIE** 1, **LILITH** 2, **COUNTERMEASURE** 111), Crafty import behavior, and visitor **`actor_id` + department** routing match the canonical doctrine; no constitutional issues flagged.

## Requirements

### 1. Department Structure

Departments are organizational units that group related functionalities:

| Department | Purpose | Typical Permissions |
|------------|---------|-------------------|
| Administration | Full system access, user management |
| Content | Content creation, editing, publishing |
| Support | Customer support, ticket management |
| Development | Code access, deployment, debugging |
| Analytics | Data access, reporting, metrics |
| Security | Security monitoring, audit logs |

### 2. Department Mapping Tables

#### `lupo_departments`
- Stores department definitions and metadata
- Links to actors for department assignments

| Column | Type | Nullable | Default | Description |
|--------|------|----------|---------|-------------|
| department_id | BIGINT | NO | (application) | Primary key |
| department_name | VARCHAR(100) | NO |  | Department display name |
| department_slug | VARCHAR(50) | NO |  | URL-friendly identifier |
| description | TEXT | YES | NULL | Department purpose |
| parent_department_id | BIGINT | YES | NULL | Reference to another department (application-managed hierarchy, no FK constraint) |
| created_by_actor_id | BIGINT | YES | NULL | Reference to actor who created this record |
| updated_by_actor_id | BIGINT | YES | NULL | Reference to actor who last updated this record |
| is_active | TINYINT | NO | 1 | Active flag |
| created_ymdhis | BIGINT | NO | (application) | UTC timestamp |
| updated_ymdhis | BIGINT | NO | (application) | UTC timestamp |
| is_deleted | TINYINT | NO | 0 | Soft delete flag |
| deleted_ymdhis | BIGINT | YES | NULL | UTC timestamp |

#### `lupo_actor_departments`

Maps actors to departments. **Canonical columns** match `database/lupopedia/mysql/install/install_new_lupopedia.sql`.

| Column | Type | Nullable | Description |
|--------|------|----------|-------------|
| actor_department_id | BIGINT | NO | Primary key (application-assigned) |
| actor_id | BIGINT | NO | `lupo_actors.actor_id` |
| department_id | BIGINT | NO | `lupo_departments.department_id` |
| role_key | VARCHAR(64) | YES | e.g. `hybrid`, `system` |
| title | VARCHAR(64) | YES | Optional display title |
| created_ymdhis | BIGINT | NO | UTC |
| updated_ymdhis | BIGINT | NO | UTC |
| is_deleted | TINYINT | NO | Soft delete |
| deleted_ymdhis | BIGINT | YES | Soft delete timestamp |

**Note:** Older PRD drafts used `mapping_id` / `role` / JSON `permissions`; the live schema does not ???????? use **`role_key`** and install SQL as source of truth.

### 3. Permission System

#### 3.1 Permission JSON Schema

```json
{
  "$schema": "http://json-schema.org/draft-07/schema#",
  "type": "object",
  "properties": {
    "actions": {
      "type": "object",
      "properties": {
        "like": { "type": "boolean" },
        "share": { "type": "boolean" },
        "comment": { "type": "boolean" },
        "ask_question": { "type": "boolean" },
        "create_collection": { "type": "boolean" },
        "edit_content": { "type": "boolean" },
        "delete_content": { "type": "boolean" },
        "admin_access": { "type": "boolean" }
      }
    },
    "modules": {
      "type": "object",
      "additionalProperties": {
        "type": "object",
        "properties": {
          "access": { "type": "boolean" },
          "admin": { "type": "boolean" }
        }
      }
    }
  }
}
```

#### 3.2 Permission Structure Example

```json
{
  "actions": {
    "like": true,
    "share": true,
    "comment": true,
    "ask_question": false,
    "create_collection": false,
    "edit_content": false,
    "delete_content": false,
    "admin_access": false
  },
  "modules": {
    "content": {
      "access": true,
      "admin": false
    },
    "support": {
      "access": true,
      "admin": false
    }
  }
}
```

### 4. Access Control Flow

1. **Actor Authentication**
   - Actor authenticates via lupo_actors table
   - System loads actor's department mappings

2. **Permission Check**
   - Before accessing any resource, check department permissions
   - Use `DepartmentAccess::checkPermission($actor_id, $resource, $action)`

3. **Resource Access**
   - Grant or deny based on permission matrix
   - Log all access attempts for audit

### 4.3 Audit Logging

All permission checks MUST be logged for security audit:

- **Granted access:** Log to `lupo_unified_log` with level 'info'
- **Denied access:** Log to `lupo_unified_log` with level 'warning'
- **Permission changes:** Log to `lupo_actor_actions` with action_type 'permission_change'

### 5. Implementation Requirements

#### 5.1 Database Schema
- **Note:** `lupo_departments` and `lupo_actor_departments` tables already exist
- Add missing audit columns (created_by_actor_id, updated_by_actor_id) to existing tables
- Create proper indexes for performance
- Implement soft delete pattern (already present)

#### 5.2 PHP Classes
- `DepartmentAccess` - Main access control class
- `Department` - Department management class
- `Permission` - Permission handling class

#### 5.3 Integration Points
- **Actor Onboarding**: Assign to appropriate department
- **Module Access**: Check permissions before module access
- **API Endpoints**: Department-aware API controllers

### 6. Migration Strategy

Existing actors without department assignments:
1. Assign to default "Unassigned" department
2. Gradually migrate actors to proper departments
3. Maintain backward compatibility during transition

---

## Implementation References

- **Database Schema**: See `docs/implementations/25_departments_system/mapping_tables.md`
- **Access Control**: See `docs/implementations/25_departments_system/access_control.md`
- **IDE Protection**: See `docs/implementations/25_departments_system/ide_protection_plan.md`

---

**Status**: **approved** (header `status`) ???????? **LILITH final audit**: department structure, **`lupo_actor_departments`**, Crafty re-seed / **`dept_{id}`** actors, visitor chat routing, and **`ACTOR_DEPARTMENT_AUTH_USER_DOCTRINE.md`** cross-reference are aligned with the department-scoped actor model.
