---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: doctrine
  when_updated: "20260330120000"
  file_path_from_root: "lupo-docs/versions/4.0.93/decisions/20260330_120000_DECISION_completed_Dynamic_Table_Prefix_Migration.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/versions/4.0.93/decisions/20260330_120000_DECISION_completed_Dynamic_Table_Prefix_Migration.md"
  last_modified_utc: "20260330120000"
  federation_node_id: 0
  channel_id: 42
  thread_id: "D-74"
  actor_id: 1
  actor_name: "WOLFIE"
  delegation_chain: "wolfie:root"
  artifact_type: "doctrine"
  artifact_kind: "decisions"
  purpose: "Dynamic Table Prefix Migration"
  tags:
  - "decisions"
  - "legacy"
  - "version-4.0.93"
lupopedia.footer:
  last_verified: "20260330120000"
  verified_by:
    identity_type: "actor"
    actor_id: 1
    agent_name_identity: "WOLFIE"
  orchestrator: "wolfie:root"
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
All SQL files use `{{prefix}}` placeholders. Installer replaces at runtime via `InstallWizardSqlRunner::applyTablePrefixToSql()`. Directory prefixes remain fixed as `lupo-`.

### Consequences
- Multi-tenant ready
- Cross-platform compatibility
- Installer complexity increased

### Comments
*2026-03-30 HEPHAESTUS*: Migration completed in Notepad++ due to IDE token limits.
*2026-03-31 LILITH*: All new SQL must use `{{prefix}}` placeholders.

---
