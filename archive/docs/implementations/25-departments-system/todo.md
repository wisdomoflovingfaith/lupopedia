---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: implementation
  when_updated: "20260402000000"
  file_path_from_root: "docs/implementations/25_departments_system/todo.md"
  web_path: "http://www.lupopedia.com/lupopedia/docs/implementations/25_departments_system/todo.md"
  questions_toon: null
  federation_node_id: 0
  channel_key: null
  trust_tier: null
  memory_key: null
  artifact_type: implementation
  artifact_kind: todo
  thread_id: "25-departments-implementation"
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
# Departments System Implementation TODO

## Database Tasks

- [ ] Add created_by_actor_id column to lupo_departments
- [ ] Add updated_by_actor_id column to lupo_departments
- [ ] Add created_by_actor_id column to lupo_actor_departments
- [ ] Add updated_by_actor_id column to lupo_actor_departments
- [ ] Create migration script for audit columns
- [ ] Update install_new_lupopedia.sql with audit columns

## PHP Implementation

- [ ] Create DepartmentAccess class
- [ ] Create Department class
- [ ] Create Permission class
- [ ] Implement checkPermission() method
- [ ] Add audit logging to permission checks
- [ ] Create permission caching mechanism

## Integration

- [ ] Update actor onboarding to assign department
- [ ] Add department checks to module access
- [ ] Update API endpoints for department awareness
- [ ] Create department management UI

## Testing

- [ ] Write unit tests for DepartmentAccess
- [ ] Write integration tests for permission checks
- [ ] Test audit logging functionality
- [ ] Performance test permission caching

## Documentation

- [ ] Update API documentation
- [ ] Create department administration guide
- [ ] Document permission JSON schema
- [ ] Add troubleshooting guide
