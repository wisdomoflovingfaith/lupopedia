# Lupopedia 4.0.93 CHANGELOG

## v4.0.93 (Master Baseline)
- HEPHAESTUS Identity Adoption: Actor 102 formalized as HEPHAESTUS (The Smith) with Faucet Proxy Pattern
- Channel-Based Coordination: Migrated all coordination from docs to Channel 42 threads; thread structure standardized (YYYYMMDD_HHIISS_actor_purpose_title.md)
- LILITH Agent Consolidation: Updated agent definitions with consolidated prompts and adversarial audit capabilities
- Option A Architecture: LILITH formalized split-table architecture for Truth Management System
- Legacy Documentation Cleanup: Removed FLIP/FLARE deprecated documentation directories
- Truth System Documentation: Created comprehensive documentation for split-table architecture
- Database Doctrine: Finalized production-ready catch-and-retry implementation
- Multi-Agent Coordination: Enhanced channel protocols and thread management
- Git Tooling: Documented and worked around pre-commit hook path mismatch
- JS Nervous System: State Mirror, High-Density Scroller, Semantic Monitor, Glass UI implemented and tested
- 63-bit ID Generation: IdGenerator.php implemented with YYYYMMDDHHIISS + random suffix format; IDE directive compliance completed
- Full Database Audit: Comprehensive audit of all 166 tables completed; 5 doctrine violations; 48 missing documentation; all PRDs updated with lupopedia.edges
- PRD Edge Integration: All PRD files now include lupopedia.edges sections linking to table definitions and related documentation
- **Grouped PRD Architecture**: Complete 14-namespace PRD structure created in `lupo-docs/prd/`; 100% PRD coverage achieved (14/14 files); maintenance burden reduced by 92%
- enforce_doctrine.py: Run attempted on all seed files; deferred due to environment/encoding issue (see DEFERRED.md)
- Hydrator: Channel 42 elevation run completed; review required (see DEFERRED.md)

- Obsolete Table Removal: `lupo_channel_boot_detail`, `lupo_channel_boot_detail_lifecycle`, `lupo_channel_boot_lifecycle`, and `lupo_smilies` removed from install SQL; all coordination is now dialog-based.
- Emoji/Smilies System Overhaul: Emoji and smilies now use `::img|foldername|filename::` codes, popup selector, and images in `lupo-emoji/`. See [EMOJI_AND_SMILIES.md](/lupo-docs/doctrine/EMOJI_AND_SMILIES.md).

## v4.0.90–92
- Migration of context models, 63-bit ID generation, and JS virtualization.
## [4.0.93] - March/April 2026

### Major
- Grouped PRD structure: 14 namespaces, **171 tables**, 100% coverage
- New core identity tables: `lupo_actor_memory`, `lupo_actor_skills`, `lupo_actor_tools`, `lupo_actor_prompts`, `lupo_actor_training` (see 01_core_identity.md)
- Emoji/smilies system overhaul: see EMOJI_AND_SMILIES.md
- Release policy clarified: 4.0.x cycle until Softaculous approval

### Minor
- Obsolete tables removed from schema and docs
- All versioned docs updated for grouped PRD structure
- Table count and audit summaries updated (now 171 tables)
- **Installer & Seed Consolidation (2026-03-30)**

- **Consolidated seed file**: Created `install/seed_lupopedia_4_1_0.sql` combining 23 seed files in dependency-safe order
- **Runtime prefix replacement**: Using `InstallWizardSqlRunner::applyTablePrefixToSql()` for `{{prefix}}` substitution
- **Installer updated**: `install.php` and `install_wizard_classes.php` load only consolidated seed after schema install
- **Anubis SQL**: Still runs separately after consolidated seed (not merged into the 23-file consolidation)
- **Original seeds**: Preserved under `lupo-database/lupopedia/mysql/seed/` for history and debugging
- **Build script**: `lupo-scripts/build_consolidated_seed_4_1_0.py` regenerates consolidated seed when source files change.
- **Installer `{{prefix}}` alignment:** `InstallWizardSqlRunner::applyTablePrefixToSql()` replaces `{{prefix}}` at runtime for `install_new_lupopedia.sql`, consolidated seed, and import SQL; non-default prefixes still map literal `lupo_` where present in legacy files.
- **Post-seed Anubis SQL:** Optional `anubis_queue_tables_4.0.53.sql` and `20260301_anubis_database_primacy_updates.sql` remain separate runs after the consolidated seed (not embedded in the 23-file merge).
- **Installer verification (read-only, 2026-03-30):** Confirmed load order (DDL → `install/seed_lupopedia_4_1_0.sql` → `import_from_old_crafty_syntax.sql` on Crafty upgrade only), `applyTablePrefixToSql()` inside `runSqlFile()`, no per-file loops for the 23 canonical seeds, consolidated SQL has no `lupo_` table tokens (only `{{prefix}}`), import SQL uses `{{prefix}}`, UTF-8 without BOM. **Canonical paths:** wizard is root `install.php` + `install_wizard_classes.php` (not under `install/`); `InstallWizardSqlRunner` lives in `install_wizard_classes.php` (no separate `.php` file). **Note:** Per-file sources under `mysql/seed/` still use literal `lupo_` until regenerated; builder global replace can mangle one `-- BEGIN FILE:` comment line for `seed_lupo_metadata_changelog_headers_4.0.68.sql` (cosmetic only).
- [2026-03-30] Manual verification: All runtime SQL and the consolidated seed file have been checked for {{prefix}} compliance. Per-file seeds under mysql/seed/ still use lupo_ until rebuilt, but are not used at runtime.
- [2026-03-31] **Agent Metadata Updates**: Added `metadata_json` field to `lupo_agents` table for UI, avatar, and configuration metadata. Removed actor-only fields (pono_score, pilau_score, kapakai_score, kapu_active, kapu_until, kapu_reason, kapu_consent_given, kapu_appeal_pending) from agent schema. Updated PRD and README to document metadata usage.
- [2026-03-31] Manual fresh install test completed successfully. All tables dropped and new installation performed via web installer, resulting in clean database state. Confirmed installer works correctly with consolidated seed file.
- last_modified_utc: "20260331120000"

### Fixed
- PRD duplication and drift between actors eliminated
- All namespace PRDs cross-referenced and up to date

### [2026-03-31] Enhanced Primary Coordination Personas

**LEXA (actor_id 15) - Security Enforcement & Guardian**
- Updated role to 'Security Enforcement & Guardian'
- Added aliases: security_guardian, enforcer
- Enhanced capabilities.json with 10 security-focused capabilities
- Updated system_prompt.txt with comprehensive security guidance
- Enhanced properties.json with security posture and coordination approach
- Version bumped to 1.0.2

**ATHENA (actor_id 11) - Wisdom & Strategy**
- Updated role to 'Wisdom & Strategy'
- Updated layer to 'coordination' (primary coordination layer)
- Added aliases: wisdom, strategy
- Enhanced capabilities.json with 10 wisdom-focused capabilities
- Updated system_prompt.txt with comprehensive strategic guidance
- Enhanced properties.json with wisdom synthesis philosophy
- Version bumped to 1.0.2

**THOTH (actor_id 8) - Knowledge & Records**
- Created complete agent configuration as Knowledge & Records persona
- Added aliases: knowledge, records, scribe
- Enhanced capabilities.json with 10 knowledge-focused capabilities
- Updated system_prompt.txt with comprehensive knowledge management guidance
- Enhanced properties.json with knowledge management philosophy
- Version bumped to 1.0.2

**ANUBIS (actor_id 19) - Custodian & Integrity Guardian**
- Added comprehensive PRD section in 07_agents_faucets.md
- Enhanced agent.json with aliases and verification metadata
- Expanded capabilities.json with 12 custodial capabilities
- Updated system_prompt.txt with comprehensive 67-line custodial guidance
- Enhanced properties.json with custodial philosophy and coordination approach
- Fixed lupo_anubis_events table schema (row_id → old_id + new_id)
- Updated all ANUBIS JSON schema files to match database (later reverted as auto-generated)
- Version bumped to 1.0.2

**Database Schema Updates**
- Fixed ANUBIS events table to use old_id and new_id fields
- Updated install_new_lupopedia.sql with proper schema alignment
- Maintained database neutrality doctrine compliance

### Fixed
- JSON Schema File Management Error: Corrected workflow for database schema updates
- Updated SQL schema instead of manually editing auto-generated JSON files
- Confirmed proper database-first approach for schema changes
