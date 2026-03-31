# WHAT_TO_DO_NEXT.md

## Observations and Suggestions (as of 2026-03-30)

### 1. PRD Canonicalization
- The root constitutional PRD (00_root_constitutional_system_requirements.md) is now in lupo-docs/prd/ and should be treated as the single anchor for all system-level requirements.
- Remove or archive the duplicate in lupo-docs/versions/4.0.93/prd/ if not needed for historical reference.
- Ensure all PRDs in lupo-docs/versions/4.0.93/prd/ and lupo-docs/prd/ reference the canonical root PRD in their outbound_edges.

### 2. PHP Compatibility
- The constitutional PRD now allows namespaces and sets PHP max to 8.6 (latest). No frameworks, Composer, or Docker allowed. All libraries must be bundled.
- Review all installer and environment docs to ensure this is reflected everywhere.

### 3. Subdirectory Doctrine
- The subdirectory installation doctrine is now enforced in all relevant PRDs and the Semantic Monitoring Widget PRD. Ensure all install scripts, docs, and UI text reflect this.

### 4. Plan and Alignment
- The plan and goals in lupo-docs/versions/4.0.93/prd/README.md and 03_goals_and_success_criteria.md should explicitly reference the root constitutional PRD and doctrine.
- All new PRDs and features must be checked for compliance with the constitutional PRD before merging.

### 5. Duplicates and Legacy
- semantic_monitoring_widget.md is a legacy duplicate; only 01_semantic_monitoring_widget.md should be referenced.
- Consider archiving or clearly marking legacy/duplicate PRDs to avoid confusion.

### 6. Cross-Thread Coordination
- Multiple actors/threads are editing these docs. Always read the latest file contents before making changes.
- Use outbound_edges and header metadata to track canonical relationships and avoid drift.

### 7. Next Steps
- Audit all PRDs in lupo-docs/versions/4.0.93/prd/ for compliance and reference to the root constitutional PRD.
- Update the plan and goals docs to reference the constitutional PRD.
- Archive or mark legacy/duplicate PRDs.
- Communicate these changes to all contributors and update onboarding docs.

### 8. Immediate Doctrine and Compliance Actions (April 2026)
- **Dynamic Table Prefix Migration**: Complete migration of all SQL/install/seed files to use `{{prefix}}` tokens and runtime replacement. No hardcoded prefixes allowed.
- **Canonical Agent Model**: Ensure all agent directories (e.g., lupo-agents/2/) follow the canonical template and versioning as per PRD_AGENT_DEFINITION_MODEL.md.
- **File-Based Agent Doctrine**: Confirm all agent configuration, skills, and memory are file-based and versioned. Registry schema must match.
- **LUPOPEDIA_HEADERS**: Validate all documentation and code files for correct YAML headers, outbound_edges, and last_modified_utc.
- **Cross-Thread Coordination**: Always read latest file contents before editing. Use outbound_edges and header metadata to track canonical relationships. Avoid overwriting concurrent edits.
- **Documentation Update Protocol**: When updating versioned docs, always check for concurrent edits and coordinate with other actors/threads. Use AGENTS.md and MULTI_AGENT_COORDINATION_DOCTRINE.md as reference.

## 9. Prefix Migration Verification & Installer Alignment (2026‑03‑30)

Manual prefix migration completed in Notepad++

IDE must verify correctness (no destructive edits)

All SQL files now use {{prefix}} placeholders

Installer must replace placeholders at runtime

Directory prefixes remain fixed (lupo-)

Database prefixes now dynamic (RULE 93.DYNAMIC_TABLE_PREFIX)

Agent registry schema updated to runtime‑only fields

All PRDs must reflect new constitutional rules

Cross‑thread coordination required before modifying versioned docs

**Notes:**
- Large SQL files exceed IDE token limits; manual editing was required
- AI IDEs struggle with global search‑replace due to semantic safety heuristics
- Notepad++ was the correct tool for this operation
- Future migrations should consider chunking or using external tools

**AI IDE Limitations Noticed During Prefix Migration**
- Large SQL files (4,000+ lines) exceed semantic processing limits
- AI IDEs avoid global search‑replace to prevent breaking data literals
- SQL lacks AST structure, making safe replacement difficult
- Manual editing in Notepad++ was the correct and safest approach
- Future migrations should consider splitting SQL into modular chunks

**Cross‑Thread Coordination Observations**
- Multiple actors are editing versioned docs simultaneously
- IDE must always read before writing
- Outbound edges and header metadata are essential to avoid drift
- Versioned docs must never be overwritten wholesale
- All changes must be incremental and surgical

**Plan Alignment Notes**
- Prefix migration must be added as a milestone in the 4.0.93 plan
- Agent registry schema update must be added
- Installer update must be added
- SQL placeholder migration must be added
- File‑based agent doctrine must be referenced
- All PRDs must reference the canonical constitutional PRD

## 10. Consolidated seed + installer (2026-03-30) — aligned with PLAN/TODO/CHANGELOG

**Done (this thread, cross-check with other actors):**
- Single runtime seed file: `/install/seed_lupopedia_4_1_0.sql` (concatenation of 23 source seeds in **dependency-safe** order; not the same as alphabetical or arbitrary list order).
- Root `/install.php` loads `install_new_lupopedia.sql` then consolidated seed only (no per-file seed loops); upgrade bootstrap matches.
- `InstallWizardSqlRunner::applyTablePrefixToSql()` centralizes `{{prefix}}` replacement for install, consolidated seed, and import SQL.
- Optional Anubis SQL files still run **after** consolidated seed (not inside the 23-file merge).
- Per-file seeds under `lupo-database/lupopedia/mysql/seed/` are **retained** (history, debugging, multi-actor merge base).

**Still open / coordination:**
- Re-run or extend `enforce_doctrine` tooling against **new** consolidated output when the Python/encoding blocker clears (see DEFERRED.md).
- Any doc that still says “run seed_*.sql individually” should point to rebuild + consolidated path.
- If another thread edits `mysql/seed/` files, regenerate `install/seed_lupopedia_4_1_0.sql` and mention it in CHANGELOG or session notes.

**Observations:**
- Doctrine “no hardcoded `lupo_`” applies to **new** SQL; legacy seeds may still be merged into consolidated form using `{{prefix}}` tokens after transformation.
- Large generated SQL belongs in version control with a small builder script to avoid hand-merge errors.

## 11. Seed File Recovery & Safe Merge Protocol (2026‑03‑30)
IDE accidentally deleted seed files; restored from GitHub

Superseded for **runtime** installs by item 12 below; per-file seeds under `mysql/seed/` remain the **source** for regeneration and debugging.

Manual merge of sources is still a multi‑actor concern when editing individual seed files—regenerate `install/seed_lupopedia_4_1_0.sql` after substantive seed edits.

IDE must verify, not blindly overwrite

Prefix migration: installer path uses `{{prefix}}` via `InstallWizardSqlRunner::applyTablePrefixToSql()` (verify any remaining ad hoc SQL)

All seed source files must remain intact until 4.1.0 release (doctrine)

No Lupopedia→Lupopedia upgrades exist before 4.1.0

One consolidated seed file is used for wizard-driven installs (see 12)

## 12. Consolidated Seed File (4.1.0) Finalization (2026‑03‑30)
All seed files restored from GitHub

Consolidated seed file created: install/seed_lupopedia_4_1_0.sql (sections concatenated in dependency-safe order: registry → actors → seed_4.1.0 → remainder; see lupo-scripts/build_consolidated_seed_4_1_0.py)

Installer updated to use only the consolidated seed file (root install.php; InstallWizardSqlRunner::applyTablePrefixToSql + runSqlFile for {{prefix}})

No Lupopedia→Lupopedia upgrades exist before 4.1.0

All 4.0.x wizard installs use the same consolidated seed file

Original seed files preserved for historical and debugging purposes

IDE must always read before writing to avoid destructive merges

## 13. Notes and suggestions (2026‑03‑30, installer + docs pass)
**Observed**
- PLAN/TODO already claimed “dynamic table prefix” and SQL migration; the gap was **centralized `{{prefix}}` substitution in `runSqlFile`** and a **single** runtime seed artifact—now documented in `lupo-docs/versions/4.0.93/CHANGELOG.md`, `PLAN.md`, `TODO.md`, `prd/01_installer_requirements.md`.
- **Dependency order** matters: a naive merge order of the 23 files can break FK assumptions (e.g. `seed_4.1.0.sql` before actors). The builder script uses a **dependency-safe** order; if someone changes order, re-validate with a full install.
- **Anubis** SQL stays outside the 23-file merge by design; install still runs it after consolidated seed.
- **Other threads:** Versioned docs in `lupo-docs/versions/4.0.93/` were updated incrementally; re-read before editing to avoid undoing parallel work.

**Suggestions**
- After any edit under `lupo-database/lupopedia/mysql/seed/`, run `python lupo-scripts/build_consolidated_seed_4_1_0.py` and commit both the script and the generated SQL if changed.
- When `enforce_doctrine` / deferred tooling is fixed, add consolidated `install/seed_lupopedia_4_1_0.sql` to the validation set (DEFERRED.md).
- Align root `README.md` / `ONBOARDING.md` in a separate pass if they still describe per-file seed execution (not done in this thread).

**Canonical doc pointers**
- Changelog: `/lupo-docs/versions/4.0.93/CHANGELOG.md`
- Plan alignment: `/lupo-docs/versions/4.0.93/PLAN.md` (Completed + SQL/Installer Migration)
- Session handoff: `/lupo-docs/versions/4.0.93/what_to_do_next_session.md`
- Installer PRD: `/lupo-docs/versions/4.0.93/prd/01_installer_requirements.md` §3.1

## 14. Installer verification pass (2026-03-30, read-only)
**Scope:** No code/SQL changes—documentation alignment with observed repo layout and wizard behavior.

**Verified**
- Load order: `install_new_lupopedia.sql` → `install/seed_lupopedia_4_1_0.sql` → `import_from_old_crafty_syntax.sql` only on **Crafty upgrade** (after normalize); new install skips import.
- `InstallWizardSqlRunner::applyTablePrefixToSql()` is applied inside `runSqlFile()` for DDL, consolidated seed, and import; optional Anubis seeds still loaded from `mysql/seed/` after consolidated seed (not in the 23-file merge).
- Consolidated file: **23** embedded sources, dependency-safe order, no `lupo_` table tokens in SQL body, no UTF-8 BOM, no placeholder ellipses.
- Import SQL: `{{prefix}}` for Lupopedia tables; `livehelp_*` names unchanged.

**Canonical paths (avoid doc drift)**
- Wizard: **`/install.php`** and **`/install_wizard_classes.php`** at **repository root** (not `install/install.php`).
- **`InstallWizardSqlRunner`** is a class in **`install_wizard_classes.php`** (there is no `InstallWizardSqlRunner.php`).
- Crafty import: **`/lupo-database/lupopedia/mysql/import/import_from_old_crafty_syntax.sql`**.

**Nuances**
- Per-file seed **sources** under `mysql/seed/` may still use literal `lupo_`; runtime consolidated output uses `{{prefix}}` after builder replace.
- Builder global replace can alter one `-- BEGIN FILE:` comment for `seed_lupo_metadata_changelog_headers_4.0.68.sql` (cosmetic); fix on next regeneration by scoping replace to SQL only if desired.
- §8 claim “All SQL files now use {{prefix}}” is **overbroad** for raw `mysql/seed/` sources—**runtime** install path uses `{{prefix}}` via consolidated + install SQL.

**Status:** Runtime installer + consolidated seed + import **consistent** with 4.0.93 plan; full install **ready** pending normal QA.

## 15. Final Consolidated Seed & Installer Status (2026-03-30)

**Completed**
- Consolidated seed file `install/seed_lupopedia_4_1_0.sql` created with 23 source files in dependency-safe order
- Installer updated to load only consolidated seed after schema install
- Anubis SQL files run separately after consolidated seed (as designed)
- All documentation updated to reflect changes

**Notes & Observations**
- The consolidated seed approach significantly simplifies installation by reducing from 23 individual file loads to 1
- Dependency order is critical: registry → actors → seed_4.1.0 → remainder
- The build script `lupo-scripts/build_consolidated_seed_4_1_0.py` handles prefix migration automatically
- Original seed files preserved for historical and debugging purposes

**Suggestions for Future Maintenance**
- After editing any seed file under `mysql/seed/`, always rebuild the consolidated seed
- Verify the full installation process after any seed changes
- Consider adding the consolidated seed to automated testing when `enforce_doctrine` is fixed
- Document any new seed files in the build script to ensure they're included

**Cross-Thread Coordination**
- Multiple actors have updated documentation related to this work
- Always read latest version before editing to avoid overwriting concurrent changes
- Use outbound_edges to track relationships between documents
