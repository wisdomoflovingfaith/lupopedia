---
lupopedia.init:
  file_identity: "TODO.md"
  artifact_type: "repository-core"
  artifact_kind: "metadata-snapshot"
  namespace: "lupopedia"
  domain: "core"
  system_version: "4.0.74"

lupopedia.metadata:
  comment: "Snapshot of metadata for this file or entity at artifact creation."

lupopedia.headers:
  lupopedia.version: "4.0.74"
  lupopedia.schema: "documentation"
  file_path_from_root: "TODO.md"
  web_path: "http://www.lupopedia.com/TODO"
  last_modified_utc: "20260313"
  system_version: "4.0.74"
  channel_id: 42
  actor_id: 1003
  artifact_type: "repository-core"
  artifact_kind: "todo"
  purpose: "Root-level pending tasks and next actions for Lupopedia (v4.0.74)."
  tags: ["todo", "tasks", "core", "v4.0.74"]

lupopedia.edges:
  comment: "Snapshot at artifact creation. Core repo files."
  meta: "Orchestrator audit fixes; metadata headers."
  outbound_edges:
    - { to: "CHANGELOG.md", type: "references", weight: 1.0 }
    - { to: "README.md", type: "references", weight: 1.0 }
    - { to: "lupo-rules/root/README.md", type: "references", weight: 0.9 }

lupopedia.engagement:
  comment: "Root TODO for orchestrator and agents."
  meta: "L-LUPO-ROOT-CURSOR; v4.0.74."
  views: 0

lupopedia.footer:
  version: "4.0.74"
  last_verified: "20260313"
  last_verified_by: "cursor"
  orchestrator: "cursor"
  next_action:
    - "v4.0.74: Test fresh install and Crafty 3.7.5 upgrade paths (see By version below)"
    - "If using orchestrator rules: run future_features_lupopedia.sql for lupo_orchestrator_rules, then php lupo-scripts/sync_orchestrator_rules_to_db.php"
---
# file: TODO (root) — session: L-LUPO-ROOT-CURSOR — delegation: cursor:root — web_path: http://www.lupopedia.com/TODO

# TODO (root)

Pending tasks and next actions for Lupopedia. v4.0.73 is the consolidation release; v4.0.74 focuses on installer and upgrade testing.

## Immediate

- [ ] **v4.0.74:** Test **upgrade from original Crafty Syntax 3.7.5** (import path) end-to-end.
- [ ] **v4.0.74:** Test **brand-new Lupopedia install** (install SQL + seed) end-to-end.
- [ ] If using orchestrator rules table: run `lupo-database/lupopedia/mysql/install/future_features_lupopedia.sql` (includes `lupo_orchestrator_rules`), then `php lupo-scripts/sync_orchestrator_rules_to_db.php`.

## By version

- **v4.0.73:** Consolidation release. All pre-4.1.0 migration schema folded into install SQL; migration replay removed. Supported paths: fresh install, Crafty 3.7.5 → Lupopedia upgrade only.
- **v4.0.74:** Testing cycle for (1) fresh install and (2) Crafty Syntax 3.7.5 upgrade/import validation. See [CHANGELOG.md](CHANGELOG.md) for "Still needing to be done" and pending tasks.

## Doctrine

All work must follow rules in `lupo-rules/root/` and `.cursor/rules/`. Metadata blocks in README, CHANGELOG, and this file mirror `lupo_metadata` columns for future rehydration.
