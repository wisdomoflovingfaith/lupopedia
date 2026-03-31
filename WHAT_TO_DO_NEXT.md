# WHAT_TO_DO_NEXT.md

**Canonical numbered sections (1–14):** Full text lives in **`/lupo-docs/versions/4.0.93/WHAT_TO_DO_NEXT.md`** (single source of truth for observations, prefix migration, consolidated seed, and verification). The blocks below (11–13) mirror handoff; **§14** is summarized here—read the versioned file for complete detail.

---

11. Seed File Recovery & Safe Merge Protocol (2026‑03‑30)
IDE accidentally deleted seed files; restored from GitHub

Superseded for **runtime** installs by item 12 below; per-file seeds under `mysql/seed/` remain the **source** for regeneration and debugging.

Manual merge of sources is still a multi‑actor concern when editing individual seed files—regenerate `install/seed_lupopedia_4_1_0.sql` after substantive seed edits.

IDE must verify, not blindly overwrite

Prefix migration: installer path uses `{{prefix}}` via `InstallWizardSqlRunner::applyTablePrefixToSql()` (verify any remaining ad hoc SQL)

All seed source files must remain intact until 4.1.0 release (doctrine)

No Lupopedia→Lupopedia upgrades exist before 4.1.0

One consolidated seed file is used for wizard-driven installs (see 12)

12. Consolidated Seed File (4.1.0) Finalization (2026‑03‑30)
All seed files restored from GitHub

Consolidated seed file created: install/seed_lupopedia_4_1_0.sql (sections concatenated in dependency-safe order: registry → actors → seed_4.1.0 → remainder; see lupo-scripts/build_consolidated_seed_4_1_0.py)

Installer updated to use only the consolidated seed file (root install.php; InstallWizardSqlRunner::applyTablePrefixToSql + runSqlFile for {{prefix}})

No Lupopedia→Lupopedia upgrades exist before 4.1.0

All 4.0.x wizard installs use the same consolidated seed file

Original seed files preserved for historical and debugging purposes

IDE must always read before writing to avoid destructive merges

13. Notes and suggestions (2026‑03‑30, installer + docs pass)
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
- Installer PRD: `/lupo-docs/versions/4.0.93/prd/01_installer_requirements.md` §3.1–3.2

## 14. Installer verification pass (2026-03-30, read-only) — summary
See **`/lupo-docs/versions/4.0.93/WHAT_TO_DO_NEXT.md` §14** for the full checklist. Short form:

- **Wizard paths:** `/install.php`, `/install_wizard_classes.php` at repo root; `InstallWizardSqlRunner` is defined in `install_wizard_classes.php` only.
- **Order:** DDL → consolidated seed → import **only** on Crafty upgrade; Anubis SQL optional after consolidated seed.
- **Consolidated seed:** 23 sources, `{{prefix}}` only in body, no BOM; raw `mysql/seed/` files may still use `lupo_` until rebuild.
- **§8 nuance:** “All SQL uses {{prefix}}” applies to **runtime** install artifacts; not every hand-edited source line in `mysql/seed/`.
- **Cosmetic:** One `-- BEGIN FILE:` comment may show `seed_{{prefix}}metadata...` for the metadata changelog seed—fix by scoping builder replace on next regen if desired.

## 15. Final Status & Next Steps (2026-03-30)
The consolidated seed and installer work is now complete. All 23 seed files have been consolidated into `install/seed_lupopedia_4_1_0.sql`, the installer has been updated to use only this file, and documentation has been updated across all relevant files. The system is ready for installation testing.
