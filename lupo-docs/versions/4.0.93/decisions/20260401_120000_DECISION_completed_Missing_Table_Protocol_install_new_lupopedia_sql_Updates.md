---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: doctrine
  when_updated: "20260401120000"
  file_path_from_root: "lupo-docs/versions/4.0.93/decisions/20260401_120000_DECISION_completed_Missing_Table_Protocol_install_new_lupopedia_sql_Updates.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/versions/4.0.93/decisions/20260401_120000_DECISION_completed_Missing_Table_Protocol_install_new_lupopedia_sql_Updates.md"
  last_modified_utc: "20260401120000"
  federation_node_id: 0
  channel_id: 42
  thread_id: "D-102"
  actor_id: 1
  actor_name: "WOLFIE"
  delegation_chain: "wolfie:root"
  artifact_type: "doctrine"
  artifact_kind: "decisions"
  purpose: "Missing Table Protocol + install_new_lupopedia.sql Updates"
  tags:
  - "decisions"
  - "legacy"
  - "version-4.0.93"
lupopedia.footer:
  last_verified: "20260401120000"
  verified_by:
    identity_type: "actor"
    actor_id: 1
    agent_name_identity: "WOLFIE"
  orchestrator: "wolfie:root"
---

# D-102: Missing Table Protocol + install_new_lupopedia.sql Updates

## Type
Decision

## Status
Completed

## Author
CURSOR (actor_id 102)

## Date
2026-04-01

### Context
Seven tables needed by the semantic monitoring widget existed in the live database (confirmed via TOON JSONs) but were absent from `install_new_lupopedia.sql`: `lupo_paths`, `lupo_references`, `lupo_reference_links`, `lupo_hashtags`, `lupo_hashtag_map`, `lupo_folders`, `lupo_folder_map`. Additionally, there was no documented protocol for what to do when a needed table is missing.

### Decision
- Added section 9.18 (Missing Table Protocol, RULE 93.MISSING_TABLE_PROTOCOL) to the constitutional PRD defining the correct procedure: create a SQL proposal file with `{{prefix}}` placeholders, review it, apply to `install_new_lupopedia.sql`, regenerate TOONs. No migration needed — fresh install only.
- Created `lupo-database/lupopedia/mysql/migrations/add_semantic_navbar_tables_20260401.sql` as the proposal file.
- Applied all 7 `CREATE TABLE` blocks directly to `install_new_lupopedia.sql` in the semantic navbar section (after `lupo_referers_daily`, before `lupo_anubis_log`). `lupo_folders` was the only one genuinely absent — the others were already present further down in the file.
- Created `lupo-docs/database/lupopedia/tables/active/lupo_paths.md` as the missing table doc.

### Consequences
- `install_new_lupopedia.sql` now includes all 7 semantic navbar tables
- Protocol is documented so future agents know the correct procedure
- No CLI execution was used — all changes went through the install SQL

### Comments
*2026-04-01 CURSOR*: The initial search for these tables came up empty because they were in a different section of the install SQL than expected. `lupo_folders` was the only genuinely missing one.

---
