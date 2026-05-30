# archive/ Organization Guide

Last updated: **2026-03-25 15:00 UTC**

## Directory Structure

```
archive/
+-- README.md              # Main archive information
+-- ORGANIZATION.md        # This file - directory structure and navigation
+-- docs/                  # Archived documentation (14 files)
+-- scripts/               # Archived scripts (38 files)
+-- legacy/                # Legacy entry points (6 files)
+-- data/                  # Temporary and analysis files (4 files)
```

## Contents by Category

### docs/ (14 files)
Archived documentation and planning materials:

**Historical Releases**
- `CHANGELOG_ARCHIVE.md` — Lupopedia v0 through v4.0.84 historical changelog

**Version-Specific Guides**
- `OFFLINE_GOVERNANCE_MODEL_4.0.45.md` — Governance model from 4.0.45
- `MINIMAL_SEED_4.0.26_READY.md` — Seed data specification from 4.0.26

**Legacy Planning**
- `plan_for_crafty_syntax.md` — Crafty Syntax migration planning (superseded)
- `plan_for_toon_seed_regeneration.md` — TOON seed regeneration plan (superseded)

**Investigation & Exploration**
- `CHANNEL_66_AND_ACTORS_EXPLORATION_REPORT.md` — Channel 66 analysis
- `ROSE_CONSULTATION_QUERY_20260324.md` — AI agent consultation notes
- `LILITH_VSCODE_COPILOT_PROMPT_4.0.84.md` — Copilot prompt from 4.0.84

**Retired Guidance & Policies**
- `EXECUTIVE_SUMMARY.md` — Old project overview
- `LUPOPEDIA_WEB_PATH_FIX_VERIFICATION.md` — Verification notes from path fixes
- `MULTI_AGENT_DATABASE_DOCUMENTATION_PLAN.md` — Database documentation planning

**Miscellaneous**
- `directives.md` — Old directive notes
- `GEMINI.md` — Gemini integration notes
- `github_changelog.md` — GitHub changelog export

### scripts/ (38 files)
Archived executable scripts and utilities organized by function:

**Debug Scripts (5)**
- `debug_autoinvite.php` — Debug autoinvite system
- `debug_captain.php` — Debug captain functionality
- `debug_collection_zero.php` — Debug collection zero
- `debug_db.php` — Database debugging
- `debug_login.php` — Login system debugging

**State Verification (8)**
- `check_actors.php` — Verify actor table state
- `check_count.php` — Count verification
- `check_db_state.php` — Overall database state
- `check_edge_state.php` — Edge table state
- `check_layer_schema.php` — Layer schema state
- `check_leave_message_count.php` — Message count verification
- `check_metadata_state.php` — Metadata integrity
- `check_schema.php` — General schema verification

**Channel 66 Investigation (5)**
- `find_ch66_content.php` — Find content in channel 66
- `get_ch66_messages.php` — Extract channel 66 messages
- `get_ch66_questions.php` — Extract channel 66 questions
- `query_ch66_simple.php` — Simple channel 66 queries
- `query_channel_66.php` — Complex channel 66 queries

**Content Inspection (2)**
- `inspect_questions.php` — Inspect question data
- `show_all_ch66_messages.php` — Display all messages

**Utilities & Migration (9)**
- `create_lifecycle_table.php` — Create lifecycle table
- `drop_tables.php` — Drop database tables
- `fix_includes.php` — Fix include paths
- `hephaestus_execute_migrations.php` — Execute migrations
- `init_actor_dirs.php` — Initialize actor directories
- `migrate_dialog_channels.php` — Migrate dialog channels
- `config_backup.php` — Configuration backup utility
- `config.php.backup` — Backup config file
- `RUNTIME_AGENT_RULES.md` — Agent rule documentation

**Development & Testing (7)**
- `run_closure_migration.php` — Test closure migration
- `run_import_debug.php` — Debug import process
- `run_real_420_query.php` — Run specific query test
- `run_upgrade_test.php` — Test upgrade process
- `simple_enforcement_test.php` — Test enforcement
- `simple_rose_test.php` — Test ROSE agent
- `test_agent_www.php` — Test agent interface

**Version & Configuration**
- `LUPEDIA_VERSION` — Old version marker file

### legacy/ (6 files)
Obsolete entry points and handlers:

- `LegacyIndex.php` — Original main index (replaced by index.php)
- `load_legacy.php` — Crafty Syntax 3.7.5 loader utility
- `remote-index.php` — Deprecated remote handler
- `switch-actor.php` — Old session actor switch (replaced by auth system)
- `import-lang.php` — Language import utility (superseded)
- `livehelp-history.php` — Old livehelp history interface

### data/ (4 files)
Temporary files, exports, and analysis outputs:

**Analysis Tools**
- `analyze_unused_tables.py` — Identify unused database tables
- `list_sql_tables.py` — Extract and list SQL table metadata

**Runtime Artifacts & Exports**
- `complete_schema.txt` — Full schema export
- `completion_message.txt` — Task completion output
- `crafty_runtime.txt` — Crafty Syntax runtime output
- `temp_clean.sql` — Temporary SQL cleanup script

## Usage Guidelines

### When to Use These Files

1. **For Historical Understanding**: Reference these files to understand:
   - How past decisions were made
   - What problems were encountered
   - Evolution of architecture from v0 to v4.0.88

2. **For Problem Diagnosis**: If you encounter an issue that might have been solved before:
   - Check debug scripts to understand debugging approach
   - Review check scripts to see state verification patterns
   - Search investigation reports for similar problems

3. **For Data Recovery**: If you need to reconstruct something:
   - Use analysis scripts to understand past data structure
   - Reference archived SQL and schemas
   - Check migration utilities for upgrade patterns

### When NOT to Use These Files

❌ **Do NOT execute any scripts** without first:
   - Understanding their purpose
   - Verifying they're compatible with current system
   - Testing in a safe environment

❌ **Do NOT restore configuration files** like `config_backup.php` or `LUPEDIA_VERSION` to active directories without verification

❌ **Do NOT treat archived documentation as current policy** — always check active documentation in:
   - Root `README.md`
   - `plan.md` and `report.md`
   - `docs/doctrine/`
   - Version-specific docs in `docs/versions/`

## Navigation Tips

**Find by Category**: Each subdirectory (docs, scripts, legacy, data) groups files by purpose

**Find by Time**: Investigate files with dates in names:
- `*_20260324.md` type files from mid-March
- Version-specific files like `*_4.0.45.md` from version 4.0.45

**Find by Function**: Scripts are named by purpose:
- `debug_*` for debugging utilities
- `check_*` for verification
- `run_*` for test runners
- `query_*` for investigation queries

## Archive Statistics

- **Total Files**: 64
- **By Category**:
  - Documentation: 14 files
  - Scripts: 38 files
  - Legacy: 6 files
  - Data: 4 files
  - Config: 2 files (README.md, this file)

- **Estimated Size**: ~3.2 MB
- **Created**: 2026-03-24
- **Reorganized**: 2026-03-25
- **Last Verified**: 2026-03-25

## Related Documents

- [Active Changelog](../CHANGELOG.md) — Current version changes
- [Version Changelogs](../docs/versions/) — Version-specific documentation
- [All Documentation](../docs/) — Complete documentation tree
- [Active Doctrine](../docs/doctrine/) — Current system rules and architecture

---

**Archive maintained by**: Cursor IDE Agent (actor_id: 102)  
**Git status**: Excluded via .gitignore  
**Safety**: Read-only reference — do not modify without purpose
