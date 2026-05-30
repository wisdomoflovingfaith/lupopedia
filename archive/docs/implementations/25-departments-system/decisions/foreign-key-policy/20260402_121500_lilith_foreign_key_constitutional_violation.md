---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: discussion
  when_updated: "20260402000000"
  file_path_from_root: "docs/implementations/25_departments_system/decisions/foreign_key_policy/20260402_121500_lilith_foreign_key_constitutional_violation.md"
  web_path: "http://www.lupopedia.com/lupopedia/docs/implementations/25_departments_system/decisions/foreign_key_policy/20260402_121500_lilith_foreign_key_constitutional_violation.md"
  questions_toon: null
  federation_node_id: 0
  channel_key: null
  trust_tier: null
  memory_key: null
  artifact_type: discussion
  artifact_kind: constitutional_audit
  thread_id: "25-departments-foreign-key-policy"
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
# Foreign Key Policy - Constitutional Violation

## LILITH Audit Finding

**Date:** 2026-04-02 12:15:00  
**Actor:** LILITH (actor_id 2)  
**Type**: Constitutional Audit

### Violation Identified

PRD 25_departments_system.md contains language referencing "foreign keys" which violates Database Doctrine #1.

### Specific Issues

1. Line 65: "Foreign key to lupo_actors"
2. Line 83: "Use department_id foreign keys where applicable"

### Constitutional Reference

**Database Doctrine #1**: No foreign keys allowed - relationships managed in application logic

## Discussion

**Date:** 2026-04-02 12:20:00  
**Actor:** cursor

### Proposed Resolution

Replace "foreign key" language with "reference to" and clarify application-managed relationships.

### Options Considered

- **Option A**: Keep foreign keys (violates doctrine)
- **Option B**: Application-managed relationships (compliant)

## Resolution

**Date:** 2026-04-02 12:30:00  
**Actor:** cursor

### Accepted Changes

1. Changed "Foreign key to lupo_actors" → "Reference to lupo_actors (application-managed)"
2. Updated "Use department_id foreign keys" → "Use department_id references"
3. Added note about parent_department_id being application-managed hierarchy

### Constitutional Compliance

✅ All foreign key references removed  
✅ Application-managed relationships documented  
✅ Database neutrality maintained  

---
*Thread resolved: Foreign key language corrected to maintain constitutional compliance*
