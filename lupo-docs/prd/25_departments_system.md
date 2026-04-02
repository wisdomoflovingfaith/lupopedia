---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: prd
  when_updated: "20260401190000"
  file_path_from_root: "lupo-docs/prd/25_departments_system.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/prd/25_departments_system.md"
  federation_node_id: 0
  channel_id: 42
  thread_id: "departments-system"
  actor_id: 102
  actor_name: "cursor"
  delegation_chain: "cursor:root"
  artifact_type: "prd"
  artifact_kind: "system_requirements"
  purpose: "Defines department-based access control and mapping system for Lupopedia"
  tags:
  - "prd"
  - "departments"
  - "access_control"
  - "mapping"
  - "authorization"
  # Required validation fields
  id: 25
  slug: departments_system
  title: "Departments System"
  status: "approved"
  parent_edges_ref: "lupo-docs/implementations/25_departments_systems/edges.md"
lupopedia.edges:
  outbound_edges:
    - to: "lupo-docs/implementations/25_departments_systems/"
      type: implements
      weight: 1.0
      reason: "Implementation of this PRD"
    - to: "lupo-docs/prd/00_root_constitutional_system_requirements.md"
      type: references
      weight: 1.0
      reason: "Constitutional anchor"
---

# PRD: Departments System

## Overview

Lupopedia supports department-based access control through a mapping system that allows actors to be assigned to specific departments with defined permissions. This system provides granular access control while maintaining the flexibility of the agent-based architecture.

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
- Maps actors to departments with role-based permissions

| Column | Type | Nullable | Default | Description |
|--------|------|----------|---------|-------------|
| mapping_id | BIGINT | NO | (application) | Primary key |
| actor_id | BIGINT | NO |  | Reference to lupo_actors (application-managed) |
| department_id | BIGINT | NO |  | Reference to lupo_departments (application-managed) |
| role | VARCHAR(50) | NO |  | Role within department |
| permissions | JSON | YES | NULL | Permission set (see Section 3 for schema) |
| created_by_actor_id | BIGINT | YES | NULL | Reference to actor who created this record |
| updated_by_actor_id | BIGINT | YES | NULL | Reference to actor who last updated this record |
| is_active | TINYINT | NO | 1 | Mapping active flag |
| created_ymdhis | BIGINT | NO | (application) | UTC timestamp |
| updated_ymdhis | BIGINT | NO | (application) | UTC timestamp |
| is_deleted | TINYINT | NO | 0 | Soft delete flag |
| deleted_ymdhis | BIGINT | YES | NULL | UTC timestamp |

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

- **Database Schema**: See `lupo-docs/implementations/25_departments_systems/mapping_tables.md`
- **Access Control**: See `lupo-docs/implementations/25_departments_systems/access_control.md`
- **IDE Protection**: See `lupo-docs/implementations/25_departments_systems/ide_protection_plan.md`
