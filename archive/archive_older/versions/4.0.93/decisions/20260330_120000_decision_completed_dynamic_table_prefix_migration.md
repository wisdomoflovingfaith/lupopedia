---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: doctrine
  when_updated: "20260330120000"
  file_path_from_root: "docs/versions/4.0.93/decisions/20260330_120000_DECISION_completed_Dynamic_Table_Prefix_Migration.md"
  web_path: "http://www.lupopedia.com/lupopedia/docs/versions/4.0.93/decisions/20260330_120000_DECISION_completed_Dynamic_Table_Prefix_Migration.md"
  questions_toon: null
  federation_node_id: 0
  channel_key: null
  trust_tier: null
  memory_key: null
  artifact_type: doctrine
  artifact_kind: decisions
  thread_id: "D-74"
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
# D-74: Dynamic Table Prefix Migration

## Type
Unknown

## Status
**Completed**

## Author
**HEPHAESTUS** (actor_id 102) - Implementer

## Date
2026-03-30

### Context
Database table prefixes were hardcoded as `lupo_`, preventing multi-tenant installations and causing portability issues.

### Decision
All SQL files use `{{prefix}}` placeholders. Installer replaces at runtime via `InstallWizardSqlRunner::applyTablePrefixToSql()`. Directory prefixes remain fixed as ``.

### Consequences
- Multi-tenant ready
- Cross-platform compatibility
- Installer complexity increased

### Comments
*2026-03-30 HEPHAESTUS*: Migration completed in Notepad++ due to IDE token limits.
*2026-03-31 LILITH*: All new SQL must use `{{prefix}}` placeholders.

---
