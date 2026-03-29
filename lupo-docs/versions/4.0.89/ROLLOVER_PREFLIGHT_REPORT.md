# 4.0.89 ROLLOVER PREFLIGHT REPORT

## Purpose
This report documents the exact file and change scope for the Lupopedia 4.0.89 version rollover, prior to any modifications or commits. It is generated to fulfill the strict preflight, scoping, and exclusion requirements specified by the user.

---

## 1. Version Source Files (to be updated)
- lupo-config/global_atoms.yaml (GLOBAL_CURRENT_LUPOPEDIA_VERSION: 4.0.88 → 4.0.89)
- lupo-includes/version.php (loads version from atom, no direct version string)
- lupo-docs/version.md (update current version section to 4.0.89)
- README.md (update references to 4.0.88 version docs → 4.0.89)

## 2. Planning Structure (to be created)
- lupo-docs/versions/4.0.89/
  - TODO.md (seeded from backlog sources)
  - CHANGELOG.md (new, empty or stub)
  - plan.md (new, empty or stub)
  - report.md (new, empty or stub)

## 3. Backlog Seed Sources (for TODO.md)
- lupo-docs/doctrine/migrations/ (migration mapping, pending migrations)
- lupo-docs/database/lupopedia/tables/ (table docs, normalization, edge enrichment)
- lupo-archive/legacy/craftysyntax-3.7.5/ (legacy upgrade mapping)
- lupo-channels/42/threads/ (thread artifacts, backlog, and planning)
- lupo-channels/66/threads/ (thread artifacts, backlog, and planning)

## 4. Unrelated Changes (to be excluded from commit)
- lupo-docs/database/lupopedia/tables/active/lupo_notifications.md (doc normalization update)
- lupo-docs/database/lupopedia/tables/active/lupo_orchestrator_rules.md (doc normalization update)
- lupo-docs/database/lupopedia/tables/active/lupo_paths_summary.md (doc normalization update)
- lupo-docs/database/lupopedia/tables/active/lupo_permissions.md (doc normalization update)
- lupo-docs/database/lupopedia/tables/active/lupo_projects.md (doc normalization update)
- lupo-docs/database/lupopedia/tables/active/lupo_referers.md (doc normalization update)
- lupo-docs/database/lupopedia/tables/active/lupo_registry.md (doc normalization update)
- lupo-docs/database/lupopedia/tables/active/lupo_registry_open.md (doc normalization update)
- lupo-docs/database/lupopedia/tables/active/lupo_rules.md (doc normalization update)
- lupo-docs/database/lupopedia/tables/active/lupo_search_index.md (doc normalization update)
- lupo-docs/database/lupopedia/tables/active/lupo_search_rebuild_log.md (doc normalization update)
- lupo-docs/database/lupopedia/tables/active/lupo_semantic_index.md (doc normalization update)

## 5. Workspace Error Check
- No errors detected in workspace as of preflight.

## 6. Directory State
- lupo-docs/versions/4.0.89/ does not exist (will be created).
- lupo-docs/versions/4.0.88/ and lupo-docs/versions/4.1.0/ exist for reference.

## 7. Commit/Tag/Push Plan
- Only the files listed in sections 1 and 2 will be staged and committed for the 4.0.89 rollover.
- All files in section 4 will be explicitly excluded from the commit.
- Backlog seeding for TODO.md will be grounded in the sources listed in section 3.

---

## Preflight Complete
No changes have been made yet. Awaiting user confirmation or next step to proceed with the scoped version update, planning structure creation, and backlog seeding.
