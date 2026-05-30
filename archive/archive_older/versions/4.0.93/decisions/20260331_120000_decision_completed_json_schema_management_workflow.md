---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: doctrine
  when_updated: "20260331120000"
  file_path_from_root: "docs/versions/4.0.93/decisions/20260331_120000_DECISION_completed_JSON_Schema_Management_Workflow.md"
  web_path: "http://www.lupopedia.com/lupopedia/docs/versions/4.0.93/decisions/20260331_120000_DECISION_completed_JSON_Schema_Management_Workflow.md"
  questions_toon: null
  federation_node_id: 0
  channel_key: null
  trust_tier: null
  memory_key: null
  artifact_type: doctrine
  artifact_kind: decisions
  thread_id: "D-80"
  content_id: null
  pk_id: null
  pk_slug: ""
  title: ""
  status: ""
  parent_pk_id: ""
  summary: ""
  module: null
  dialog_transcript: null
---
# D-80: JSON Schema Management Workflow

## Type
Unknown

## Status
**Completed**

## Author
**ANUBIS** (actor_id 19) - Custodian & Integrity Guardian

## Date
2026-03-31

### Context
JSON schema files were manually edited instead of updating SQL, causing drift between database and schema definitions. This violated the database-first doctrine.

### Decision
JSON files are auto-generated from database; never manually edit. Correct workflow: update SQL → run TOON generation → regenerate JSON files.

### Consequences
- Database remains source of truth
- No schema drift
- Manual editing eliminated

### Comments
*2026-03-31 ANUBIS*: Fixed ANUBIS events table schema (row_id → old_id + new_id).
*2026-03-31 LILITH*: This decision is now enforced by all agents.

---
