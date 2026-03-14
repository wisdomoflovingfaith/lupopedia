---
lupopedia.init:
  required_reading:
    - path: "plan.md"
      reason: "P1 folder rename deferred until audit complete"
    - path: "lupo-docs/channels/doctrine/FOLDER_NAMING_DOCTRINE.md"
      reason: "Folder naming and lupo- prefix doctrine"
  required_context:
    - "No renames performed. Audit only; rename candidate = future lupo- prefix per doctrine."

lupopedia.headers:
  lupopedia.version: "4.0.74"
  lupopedia.schema: "status"
  file_path_from_root: "lupo-docs/status/FOLDER_RENAME_AUDIT_4_0_74.md"
  last_modified_utc: "20260315"
  system_version: "4.0.74"
  channel_id: 42
  actor_id: 102
  artifact_type: "audit"
  artifact_kind: "status"
  purpose: "P1 folder rename dependency audit; no renames yet."

lupopedia.footer:
  version: "4.0.74"
  last_verified: "20260315"
  last_verified_by: "cursor"
  next_action:
    - "Use this audit to plan phased renames (lupo-admin, lupo-api, etc.) in a later P1/P2 pass"
---
# file: FOLDER_RENAME_AUDIT_4_0_74 — P1 dependency audit

# Folder Rename Dependency Audit (4.0.74)

**Date:** 2026-03-15  
**Directive:** [prompts/cursor/20260315_cursor_p1_execution_4_0_74.md](../../prompts/cursor/20260315_cursor_p1_execution_4_0_74.md)  
**Outcome:** Audit only. **No folder renames performed.**

Target directories were checked for existence at project root and for references in PHP, .htaccess, Markdown, config, and scripts. Reference counts are approximate (grep across repo; some hits are substrings or nested paths).

| Folder | Exists | References | Risk Level | Rename Candidate |
|--------|--------|------------|------------|------------------|
| admin/ | Yes | High (PHP: admin.php, auth_routes, module-loader, Admin* classes; .htaccess admin.php; views/admin; lupo-logs/admin) | **High** | lupo-admin/ (requires PHP includes, routes, .htaccess, doc links) |
| admin_sections/ | Yes | Medium (layouts/admin_sections/*.php; theme paths) | **Medium** | lupo-admin_sections/ |
| api/ | Yes | High (api/*.php; module-loader; header.php; many API endpoints and docs) | **High** | lupo-api/ (routes, includes, doc references) |
| backups/ | Yes | Low–Medium (.gitignore; backups/* content; migration docs) | **Low** | lupo-backups/ |
| cache/ | Yes | Low–Medium (.gitignore; config; UrlResolver; file paths) | **Medium** | lupo-cache/ |
| images/ | Yes | High (image.php; theme-loader; header/footer; template paths; many image paths) | **High** | lupo-images/ (many PHP path refs) |
| install/ | Yes | High (install.php; bootstrap; index; InstallWizard*; run_upgrade_test; doc references) | **High** | lupo-install/ (installer entry points) |
| legacy/ | Yes | Medium (LegacyIndex; legacy/craftysyntax; docs) | **Medium** | lupo-legacy/ |
| meta/ | Yes | Low (doctrine/docs; config references) | **Low** | lupo-meta/ |
| prompts/ | Yes | High (prompts/*; bootstrap; lupo-bin; many status/prompts docs) | **High** | lupo-prompts/ (or keep; directive path uses prompts/) |
| scripts/ | Yes | High (scripts/*; install_wizard_classes; config; run_tests; many docs) | **High** | lupo-scripts/ (or keep; standard name) |
| templates/ | Yes | Medium (module-loader; content-page; list/help controllers; theme paths) | **Medium** | lupo-templates/ |
| tests/ | Yes | Medium (run_tests.sh; run_unit_tests; run_regression_tests; AGENTS.md; docs) | **Medium** | lupo-tests/ |
| tmp/ | Yes | Low (.gitignore; temp paths) | **Low** | lupo-tmp/ |
| tools/ | Yes | Low (lupo-tools exists; root tools/ refs fewer) | **Low** | lupo-tools/ (collision with existing lupo-tools) |
| uploads/ | Yes | Medium (lupo_uploads table doc; config; auth-ui-helpers; FILESYSTEM_MIGRATION_GUIDE) | **Medium** | lupo-uploads/ |
| views/ | Yes | High (views/admin/*; theme-loader; layout paths; PHP includes) | **High** | lupo-views/ |

## Summary

- **All 17 target folders exist** at project root.
- **High-risk** (many PHP/config/doc references): admin/, api/, images/, install/, prompts/, scripts/, views/.
- **Medium-risk**: admin_sections/, cache/, legacy/, templates/, tests/, uploads/.
- **Low-risk**: backups/, meta/, tmp/. **tools/** rename would collide with existing **lupo-tools/**.

## Recommendation

Do **not** rename any folder in this pass. Use this audit in a later P1/P2 pass to:

1. Plan renames in dependency order (e.g. update all PHP includes and config first, then rename).
2. Prefer a single coordinated change per folder (rename + global ref update) to avoid broken paths.
3. Document any folder that remains without lupo- prefix by exception (e.g. `scripts/` if kept for tooling convention).

## Verification

- Grep performed for path-like references (e.g. `admin/`, `api/`, `install/`, `scripts/`, `views/`, `images/`, `templates/`, `legacy/`, `prompts/`, `tests/`, `uploads/`, `cache/`, `backups/`, `meta/`, `tmp/`, `tools/`, `admin_sections/`) across *.php, .htaccess, *.md, config files, and scripts.
- .htaccess references `admin.php` (file), not `admin/` directory.

---
*Cursor (actor_id 102) — P1 folder rename audit 2026-03-15*
