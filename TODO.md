---
lupopedia.init:
  file_identity: "TODO.md"
  artifact_type: "repository-core"
  artifact_kind: "metadata-snapshot"
  namespace: "lupopedia"
  domain: "core"
  system_version: "4.0.73"

lupopedia.metadata:
  comment: "Snapshot of metadata for this file or entity at artifact creation."

lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: "TODO.md"
  web_path: "http://www.lupopedia.com/TODO"
  last_modified_utc: "20260313"
  system_version: "4.0.73"
  channel_id: 42
  actor_id: 1003
  artifact_type: "repository-core"
  artifact_kind: "todo"
  purpose: "Root-level pending tasks and next actions for Lupopedia (v4.0.73)."
  tags: ["todo", "tasks", "core", "v4.0.73"]

lupopedia.edges:
  comment: "Snapshot at artifact creation. Core repo files."
  meta: "Orchestrator audit fixes; metadata headers."
  outbound_edges:
    - { to: "CHANGELOG.md", type: "references", weight: 1.0 }
    - { to: "README.md", type: "references", weight: 1.0 }
    - { to: "lupo-rules/root/README.md", type: "references", weight: 0.9 }

lupopedia.engagement:
  comment: "Root TODO for orchestrator and agents."
  meta: "L-LUPO-ROOT-CURSOR; v4.0.73."
  views: 0

lupopedia.footer:
  version: "4.0.73"
  last_verified: "20260313"
  last_verified_by: "cursor"
  orchestrator: "cursor"
  next_action:
    - "Sync lupo-rules/root content to lupo_orchestrator_rules when table is available"
    - "Rehydrate metadata from lupo_metadata for core files when rehydration script exists"
---
# file: TODO (root) — session: L-LUPO-ROOT-CURSOR — delegation: cursor:root — web_path: http://www.lupopedia.com/TODO

# TODO (root)

Pending tasks and next actions for Lupopedia at version 4.0.73.

## Immediate

- [ ] Run migration `database/migrations/20260313_lupo_orchestrator_rules.sql` if using orchestrator rules table.
- [ ] Run `php scripts/sync_orchestrator_rules_to_db.php` to populate `lupo_orchestrator_rules` from `lupo-rules/root/` (after table exists).

## By version

See [CHANGELOG.md](CHANGELOG.md) for version-specific "Still needing to be done" and pending tasks.

## Doctrine

All work must follow rules in `lupo-rules/root/` and `.cursor/rules/`. Metadata blocks in README, CHANGELOG, and this file mirror `lupo_metadata` columns for future rehydration.
