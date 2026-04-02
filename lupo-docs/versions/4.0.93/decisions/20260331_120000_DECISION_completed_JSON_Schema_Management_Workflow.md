---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: doctrine
  when_updated: "20260331120000"
  file_path_from_root: "lupo-docs/versions/4.0.93/decisions/20260331_120000_DECISION_completed_JSON_Schema_Management_Workflow.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/versions/4.0.93/decisions/20260331_120000_DECISION_completed_JSON_Schema_Management_Workflow.md"
  last_modified_utc: "20260331120000"
  federation_node_id: 0
  channel_id: 42
  thread_id: "D-80"
  actor_id: 1
  actor_name: "WOLFIE"
  delegation_chain: "wolfie:root"
  artifact_type: "doctrine"
  artifact_kind: "decisions"
  purpose: "JSON Schema Management Workflow"
  tags:
  - "decisions"
  - "legacy"
  - "version-4.0.93"
lupopedia.footer:
  last_verified: "20260331120000"
  verified_by:
    identity_type: "actor"
    actor_id: 1
    agent_name_identity: "WOLFIE"
  orchestrator: "wolfie:root"
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
