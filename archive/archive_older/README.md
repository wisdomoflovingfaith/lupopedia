# Lupopedia Archive (archive/)

This directory contains stale, superseded, and development-phase documentation and scripts that have been removed from the active repository root to keep the workspace organized and reduce cognitive load.

## What's Here

### Archived Documentation
- **Legacy versioned docs**: `OFFLINE_GOVERNANCE_MODEL_4.0.45.md`, `MINIMAL_SEED_4.0.26_READY.md` — Version-specific docs from earlier 4.0.x phases
- **Old planning documents**: `plan_for_crafty_syntax.md`, `plan_for_toon_seed_regeneration.md` — Superseded by current `plan.md`
- **Exploration reports**: `CHANNEL_66_AND_ACTORS_EXPLORATION_REPORT.md`, `ROSE_CONSULTATION_QUERY_20260324.md` — Ad-hoc investigation notes
- **Retired guidance**: `EXECUTIVE_SUMMARY.md`, `RUNTIME_AGENT_RULES.md`, `LILITH_VSCODE_COPILOT_PROMPT_4.0.84.md` — Policy/guidance from earlier phases

### Analysis & Investigation Scripts
- `analyze_unused_tables.py`, `list_sql_tables.py` — Schema analysis tools from development
- `query_ch66_*.php`, `get_ch66_*.php`, `find_ch66_content.php` — Channel 66 investigation utilities
- `inspect_questions.php`, `show_all_ch66_messages.php` — Content inspection tools

### Debug & Development Scripts
- `debug_*.php` (autoinvite, captain, collection_zero, db, login) — Ad-hoc debugging utilities
- `check_*.php` (actors, count, db_state, edge_state, layer_schema, etc.) — State verification scripts
- `test_agent_www.php`, `simple_*.php` — Development test files

### Utility & Migration Scripts
- `config_backup.php`, `config.php.backup` — Old configuration backups
- `hephaestus_execute_migrations.php`, `migrate_dialog_channels.php` — Legacy migration tools
- `create_lifecycle_table.php`, `drop_tables.php`, `init_actor_dirs.php` — One-off setup utilities
- `run_*.php`, `load_legacy.php`, `import-lang.php` — Legacy import and test runners

### Temporary Files
- `temp_clean.sql` — Temporary SQL cleanup
- `completion_message.txt`, `crafty_runtime.txt`, `complete_schema.txt` — Runtime output artifacts
- `CHANGELOG_ARCHIVE.md` — Old changelog entries

### Legacy Indices & Handlers
- `LegacyIndex.php`, `remote-index.php`, `switch-actor.php` — Old entry points
- `livehelp-history.php` — Legacy livehelp interface

### Supporting Files
- `LUPEDIA_VERSION` — Old version file (version moved to config/global_atoms.yaml)
- `LUPOPEDIA_WEB_PATH_FIX_VERIFICATION.md` — Verification notes for older fixes
- `MULTI_AGENT_DATABASE_DOCUMENTATION_PLAN.md` — Old database planning
- `github_changelog.md`, `GEMINI.md`, `directives.md` — Miscellaneous notes

## When to Use This Archive

**Reference**: If you need to understand how something worked in an earlier phase, these files provide historical context.

**Avoid**: Never execute scripts from here without understanding their purpose—they were written for specific, outdated conditions and may be dangerous if run in the current environment.

**Do Not Restore**: Before restoring anything, check if there's a current equivalent in the active workspace. The current `plan.md`, `report.md`, and active tooling supersede all archived versions.

## Archive Maintenance

This directory was created on **2026-03-24** as part of workspace cleanup. It is **excluded from git** (see `.gitignore`). Files here are:
- Not tested against the current codebase
- May reference outdated APIs, table structures, or configuration
- Useful primarily for historical reference and understanding development decisions

## Next Steps

If you need to:
1. **Reference a development decision** → check corresponding docs in `docs/doctrine/`
2. **Understand past states** → review `CHANGELOG.md` (active version)
3. **Restore a script** → first verify it in `scripts/` or appropriate active directory
4. **Clean up further** → consolidate and organize archive subdirectories as needed

---

**Archived**: 2026-03-24 14:49 UTC  
**Archive size**: 63 files (~3.2 MB estimated)  
**Related**: `.gitignore`, root `plan.md`, root `report.md`, active documentation in `docs/`
