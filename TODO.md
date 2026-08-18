---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: TODO.md
  web_path: https://www.lupopedia.com/lupopedia/TODO.md
  status: active
  when_updated: '20260513033046'
  trust_tier: canonical
  questions_toon: null
  memory_toon: lupo-memory/development/canonical/1026/04/todo.toon
  atoms_toon: lupo-memory/atoms/lupopedia_global_constants.atom.toon
  transcript_jsonl: 0/development/todo
  artifact_type: status
  artifact_kind: tracking
  channel_key: development
  federation_node_id: 0
  thread_key: null
  lupopedia.schema: status
  prd_cluster: 00_A_FORBIDDEN_AND_WHY_00_C_ROOT_CONSTITUTIONAL_SYSTEM_REQUIREMENTS_16_B_ATOMS_16_C_HEADERS_26_A_FIVE_LAYER_DOCUMENTATION_ARCHITECTURE
  title: TODO.md -- Root Task List
  summary: Root high-level TODO aligned to version-driven iterative development.
---
# Root TODO

**MOVED TO VERSION-SPECIFIC LOCATION**

Due to rapid development with version-driven workflow, all TODO content has been moved to:

**`lupo-docs/versions/4.1.4/TODO.md`**

This root file now serves as a redirect pointer only. All active tasks, session notes, and development coordination live in the version-specific TODO file to maintain proper version context and prevent task drift across versions.

## Why This Change

- Rapid development requires version-specific task tracking
- Prevents task confusion between versions
- Maintains proper historical context per version
- Aligns with version-driven iterative development doctrine

## Current Active TODO

See: **`lupo-docs/versions/4.1.4/TODO.md`**

## Crafty Syntax Import Verification & Repair
- Issue: After upgrade, all `livehelp_*` tables were dropped from the `lupopedia` database.
- Result: `import_from_crafty_syntax.sql` had no source tables to read.
- Evidence: 
  - `lupopedia` target tables (lupo_crafty_syntax_*) are empty.
  - Sibling DBs `craftysyntax` and `old_craftysyntax` still contain 34–35 `livehelp_*` tables with real data.
  - JSON fallback files are schema-only and contain no rows.
- Required future work:
  - Re-run import against a database that still contains `livehelp_*` tables.
  - Verify mapping from each `livehelp_*` table to its `lupo_*` target.
  - Test import using a few random domains with real Crafty data.
  - Confirm row counts and cross-table relationships after import.
  - Add safety check to installer: warn if `livehelp_*` tables are missing.
- Status: Paused. Will revisit after current tasks.
