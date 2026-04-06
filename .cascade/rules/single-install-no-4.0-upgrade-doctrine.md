---
lupopedia.init:
  file_identity: "single-install-no-4.0-upgrade-doctrine.md"
  artifact_type: "cascade_rule"
  artifact_kind: "doctrine"
  namespace: "cascade"
  system_version: "4.0.76"
  orchestrator_actor: "cascade"
  delegation_chain: "cascade:captain"

lupopedia.headers:
  actor_id: 105
  actor_name: "cascade"
  delegation_chain: "cascade:captain"
  lupopedia.version: "4.0.76"
  lupopedia.schema: "cascade_rule"
  file_path_from_root: ".cascade/rules/single-install-no-4.0-upgrade-doctrine.md"
  last_modified_utc: "20260406"
  system_version: "4.0.76"
  source_path: "lupo-rules/root/single-install-no-4.0-upgrade-doctrine.md"
  artifact_type: "rule"
  artifact_kind: "cascade_doctrine"
  purpose: "Cascade-specific rule derived from canonical root rule"

lupopedia.rules:
  comment: "Rule declaration and provenance block"
  declares:
    - rule_id: "ARC004"
      rule_text: "No Lupopedia to Lupopedia upgrade until 4.1.0; all schema in install plus seed"
      scope: "all_agents"
      category: "installation"
      status: "active"
  imports: []
  overrides: []
  provenance:
    authored_by: "wolfie"
    authored_date: "20260406"
    last_reviewed_by: "cascade"
    last_reviewed_date: "20260406"
    version: "1.0"
    status: "active"
lupopedia.footer:
  version: "4.0.76"
  last_verified: "20260406"
  last_verified_by: "cascade"
  orchestrator: "cascade"
  next_action:
    - "Keep in sync with canonical root rules"
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

- **Migrations that were written for 4.0.x** (e.g. in `lupo-database/migrations/` or `lupo-database/.../migrations/`) MUST be **consolidated into the install SQL**.
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

