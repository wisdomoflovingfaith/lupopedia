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
  last_modified_utc: "20260314"
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
  last_verified: "20260314"
  last_verified_by: "cursor"
  orchestrator: "cursor"
  next_action:
    - "v4.0.74: Test fresh install and Crafty 3.7.5 upgrade paths (see By version below)"
    - "If using orchestrator rules: run future_features_lupopedia.sql for lupo_orchestrator_rules, then php lupo-scripts/sync_orchestrator_rules_to_db.php"
---
# file: TODO (root) — session: L-LUPO-ROOT-CURSOR — delegation: cursor:root — web_path: http://www.lupopedia.com/TODO

# TODO (root)

Pending tasks and next actions for Lupopedia. **v4.0.74** is the current release: schema (lupo_projects, 12-table expansion), seed wiring (seed_projects.sql), path normalization (lupo-* prefix; `legacy/` intentional exception), and advisory table-count doctrine are in place. Remaining work is validation and optional follow-ups.

## Immediate

- [ ] **v4.0.74:** Test **upgrade from Crafty Syntax 3.7.5** end-to-end (drop tables → load Crafty 3.7.5 → run install.php → verify).
- [ ] **v4.0.74:** Test **fresh Lupopedia install** end-to-end (install SQL + seeds including seed_projects.sql).

## Optional / follow-up

- [ ] If using orchestrator rules table: run `lupo-database/lupopedia/mysql/install/future_features_lupopedia.sql` (includes `lupo_orchestrator_rules`), then `php lupo-scripts/sync_orchestrator_rules_to_db.php`.
- [ ] Unify or document TOON output path (install SQL is authoritative; TOONs are derived; script may write to lupo-database/lupopedia/toon/ or lupo-docs/toons/ depending on config).

## By version

- **v4.0.73:** Consolidation release. Supported paths: fresh install, Crafty 3.7.5 → Lupopedia upgrade only.
- **v4.0.74:** Implemented: lupo_projects + seed_projects.sql (wired), 12-table expansion, path normalization (legacy/ exception), table count 159 (advisory doctrine). Remaining: upgrade and fresh-install validation tests. See [CHANGELOG.md](CHANGELOG.md) and [plan.md](plan.md).

## Doctrine

All work must follow rules in `lupo-rules/root/` and `.cursor/rules/`. Metadata blocks in README, CHANGELOG, and this file mirror `lupo_metadata` columns for future rehydration.
