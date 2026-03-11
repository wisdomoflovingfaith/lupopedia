---
lupopedia.headers:
  actor_id: 1
  actor_name: "wolfie"
  delegation_chain: "wolfie:root"
  lupopedia.version: "1.0"
  lupopedia.schema: "cursor_rule"
  file_path_from_root: "lupo-rules/root/single-install-no-4.0-upgrade-doctrine.md"
  web_path: "http://www.lupopedia.com/rules/root/single-install-no-4.0-upgrade-doctrine"
  last_modified_utc: "20260310"
  system_version: "4.0.68"
  rule_name: "Single Install — No Lupopedia Upgrade Until 4.1.0"
  rule_type: "constraint"
  artifact_type: "rule"
  artifact_kind: "cursor_doctrine"
  purpose: "No Lupopedia→Lupopedia upgrade until 4.1.0. All 4.0.x from Crafty 3.7.5 only; all schema in install + seed; consolidate 4.0.x migrations into install; no backwards compatibility between 4.0.x."
  tags: ["cursor", "install", "4.0.x", "doctrine", "migration"]
  source_path: ".cursor/rules/single-install-no-4.0-upgrade-doctrine.mdc"

lupopedia.footer:
  version: "4.0.68"
  last_verified: "20260310"
  last_verified_by: "wolfie"
---
# file: Rule — Single Install, No Lupopedia Upgrade Until 4.1.0 — session: L-LUPO-WOLFIE — delegation: wolfie:root — web_path: http://www.lupopedia.com/rules/root/single-install-no-4.0-upgrade-doctrine

# Single Install — No Lupopedia Upgrade Until 4.1.0 (PERMANENT)

Cursor MUST follow this doctrine for all 4.0.x development. There is exactly one upgrade path and one schema source until 4.1.0.

## No Lupopedia→Lupopedia upgrade until 4.1.0

- **There is NO Lupopedia-to-Lupopedia upgrade** in the 4.0.x line.
- **All 4.0.x versions** are upgraded **only from a Crafty Syntax 3.7.5 install**. The wizard and installer support only: **Crafty Syntax 3.7.5 → Lupopedia 4.0.x**.
- **4.1.0** is when Lupopedia→Lupopedia upgrade and auto-installers are introduced. Until then, every 4.0.x install is "drop (or start from Crafty 3.7.5) → run install → run seed."

## Where database changes go (4.0.x)

- **All database schema changes** for 4.0.x MUST be made in:
  1. **Install SQL** — The canonical install file (e.g. `install_new_lupopedia.sql` or the project's single install script). This file IS the full schema for the current 4.0.x codebase.
  2. **Main seed file** — The canonical seed file (e.g. `seed_lupopedia.sql` or the project's main seed). Seed data and structural seed content belong here.
- **Do NOT** rely on "run migration X after install" for 4.0.x. There is no migration chain for 4.0.x.

## Consolidate 4.0.x migrations into install

- **Migrations that were written for 4.0.x** (e.g. in `database/migrations/` or `lupo-database/.../migrations/`) MUST be **consolidated into the install SQL**.
- The install SQL must contain the **entire current schema** so that a fresh install (Crafty 3.7.5 → run install → run seed) produces the same schema as the current code expects.
- One-time migration files created during 4.0.x development are historical; their **content** (tables, columns, indexes) belongs in the install SQL. Do not require users or the wizard to run those migration files for a valid 4.0.x install.

## No backwards compatibility between 4.0.x versions

- **There is no requirement for backwards compatibility** between 4.0.x patch versions. What worked in 4.0.45 does not need to work in 4.0.68.
- **Single codebase** — There is only one current codebase. No support for "old 4.0.x" behavior in the same branch.
- **Single install** — There is only one install of Lupopedia (from Crafty 3.7.5) until **4.1.0** is released with auto-installers. For 4.0.x, "upgrade" means "reinstall from Crafty 3.7.5 with current install + seed."

## Summary

| Topic | Rule |
|-------|------|
| Upgrade path (4.0.x) | Crafty Syntax 3.7.5 → Lupopedia 4.0.x only. No Lupopedia→Lupopedia until 4.1.0. |
| Schema location | Install SQL + main seed file only. No 4.0.x migration chain. |
| Existing 4.0.x migrations | Consolidate their content into the install SQL so install is self-contained. |
| Backwards compatibility | None required between 4.0.x versions. Single codebase, single install until 4.1.0. |

This rule is permanent for all 4.0.x work.
