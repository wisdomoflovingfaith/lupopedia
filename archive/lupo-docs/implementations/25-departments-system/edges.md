---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: implementation
  when_updated: "20260402000000"
  file_path_from_root: "lupo-docs/implementations/25_departments_system/edges.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/implementations/25_departments_system/edges.md"
  questions_toon: null
  federation_node_id: 0
  channel_key: null
  trust_tier: null
  memory_key: null
  artifact_type: implementation
  artifact_kind: edges
  thread_id: "25-departments-edges"
  content_id: null
  pk_id: null
  pk_slug: ""
  title: ""
  status: ""
  parent_pk_id: "25_departments_system"
  summary: ""
  module: null
  dialog_transcript: null
---
# System Edges & Relationships

## Database Edges

### Tables
- **lupo_departments**: Department definitions - Modified (added audit columns)
- **lupo_actor_departments**: Actor-department mappings - Modified (added audit columns)

### Columns
- **lupo_departments.created_by_actor_id**: BIGINT - Audit tracking
- **lupo_departments.updated_by_actor_id**: BIGINT - Audit tracking
- **lupo_actor_departments.created_by_actor_id**: BIGINT - Audit tracking
- **lupo_actor_departments.updated_by_actor_id**: BIGINT - Audit tracking

### Relationships
- **lupo_departments.parent_department_id** → **lupo_departments.department_id**: Hierarchy - Application-managed
- **lupo_actor_departments.actor_id** → **lupo_actors.actor_id**: Assignment - Application-managed
- **lupo_actor_departments.department_id** → **lupo_departments.department_id**: Assignment - Application-managed

### Migrations
- **add_audit_columns.sql**: Add created/updated tracking - 2026-04-02

## Code Edges

### PHP Classes
- **DepartmentAccess**: lupo-includes/classes/DepartmentAccess.php - Main access control
- **Department**: lupo-includes/classes/Department.php - Department management
- **Permission**: lupo-includes/classes/Permission.php - Permission handling

### Controllers
- **DepartmentController**: lupo-includes/controllers/DepartmentController.php - CRUD operations
- **AccessController**: lupo-includes/controllers/AccessController.php - Permission checks

### Services
- **DepartmentService**: lupo-includes/services/DepartmentService.php - Business logic
- **PermissionService**: lupo-includes/services/PermissionService.php - Permission validation

## Documentation Edges

### PRD Links
- **Parent PRD**: 25_departments_system.md - Defines requirements
- **Related PRDs**: 26_project_structure.md - Documentation architecture

### Implementation Links
- **Related Implementations**: 18_channel_chat_display/ - Uses access control
- **Shared Components**: DatabaseFactory.php - Database access

### Discussion References
- **Decision Links**: discussions.md - Foreign key vs application-managed
- **Decision Links**: discussions.md - Permission JSON schema design

## UI Edges

### Templates
- **department_form.php**: lupo-views/admin/department_form.php - Create/edit
- **department_list.php**: lupo-views/admin/department_list.php - Listing
- **actor_assign.php**: lupo-views/admin/actor_assign.php - Assignment

### JavaScript
- **department_manager.js**: lupo-ui/js/department_manager.js - UI interactions
- **permission_editor.js**: lupo-ui/js/permission_editor.js - Permission UI

### CSS
- **departments.css**: lupo-ui/css/departments.css - Department styling

## External Edges

### APIs
- **Department API**: /api/departments - REST endpoints
- **Access API**: /api/access/permission - Permission checks

### Third-Party Libraries
- **JSON Schema**: composer - Permission validation
- **PHP JWT**: composer - Token handling (if used)

## Impact Analysis

### Upstream Dependencies
- **lupo_actors table**: High impact - Core actor system
- **Authentication system**: High impact - Must integrate

### Downstream Dependencies
- **Module access**: High impact - All modules use permissions
- **Chat system**: Medium impact - Channel access control

### Potential Conflicts
- **Existing permissions**: Low risk - New system is additive
- **Performance**: Medium risk - Additional permission checks

---
*This file maps all relationships and dependencies for this implementation.*
