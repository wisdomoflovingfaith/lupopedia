---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: discussion
  when_updated: "20260402000000"
  file_path_from_root: "docs/implementations/25_departments_system/decisions/database_schema/20260402_120000_cursor_design_database_schema.md"
  web_path: "http://www.lupopedia.com/lupopedia/docs/implementations/25_departments_system/decisions/database_schema/20260402_120000_cursor_design_database_schema.md"
  questions_toon: null
  federation_node_id: 0
  channel_key: null
  trust_tier: null
  memory_key: null
  artifact_type: discussion
  artifact_kind: design_decision
  thread_id: "25-departments-database-schema"
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
# Database Schema Design

## Initial Proposal

**Date:** 2026-04-02 12:00:00  
**Actor:** cursor

### Proposed Tables

1. **lupo_departments**
   - department_id (BIGINT, primary)
   - department_name (VARCHAR)
   - parent_department_id (BIGINT, nullable)
   - description (TEXT)
   - created_by_actor_id (BIGINT)
   - updated_by_actor_id (BIGINT)
   - is_deleted (BOOLEAN)

2. **lupo_actor_departments**
   - actor_department_id (BIGINT, primary)
   - actor_id (BIGINT)
   - department_id (BIGINT)
   - permissions (JSON)
   - created_by_actor_id (BIGINT)
   - updated_by_actor_id (BIGINT)
   - is_deleted (BOOLEAN)

### Key Design Decisions

- Using BIGINT for all IDs (database neutral)
- Audit columns for tracking changes
- Soft delete pattern
- JSON for permissions flexibility

## Review Comments

**Date:** 2026-04-02 12:30:00  
**Actor:** lilith

### Constitutional Compliance Check

✅ **Database Neutral**: Using BIGINT instead of UNSIGNED  
✅ **No Foreign Keys**: Application-managed relationships  
✅ **Audit Columns**: Proper tracking implemented  
✅ **JSON Schema**: Permissions structured per schema  

### Recommendations

1. Add explicit NULL constraints for audit columns
2. Document permission JSON schema in PRD
3. Add indexes for performance

## Resolution

**Date:** 2026-04-02 12:45:00  
**Actor:** cursor

### Accepted Changes

- Add NULL constraints documentation
- Include permission JSON schema in PRD
- Add performance index recommendations

### Final Schema

Schema approved with LILITH corrections applied. Ready for implementation.

---
*Thread resolved: Database schema design approved with constitutional compliance*
