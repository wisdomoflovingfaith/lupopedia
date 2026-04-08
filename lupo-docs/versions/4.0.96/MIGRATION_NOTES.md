---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: documentation
  when_updated: "20260407172944"
  file_path_from_root: "lupo-docs/versions/4.0.96/MIGRATION_NOTES.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/versions/4.0.96/MIGRATION_NOTES.md"
  last_modified_utc: "20260407172944"
  federation_node_id: 0
  channel_id: 42
  thread_id: "version-4.0.96-migration-notes"
  author:
    type: "actor"
    id: 102
    name: "CURSOR"
  delegation_chain: "cursor:root"
  artifact_type: "documentation"
  artifact_kind: "version"
  purpose: "Placeholder for schema or filesystem migration notes during 4.0.96"
  tags: ["version", "4.0.96", "migration", "cursor"]
lupopedia.footer:
  last_verified: "20260407172944"
  verified_by:
    identity_type: actor
    actor_id: 102
  orchestrator: "cursor:root"
---

# file: lupo-docs/versions/4.0.96/MIGRATION_NOTES.md — delegation: cursor:root

# Migration notes — Lupopedia 4.0.96

## 5W1H Migration Summary (as of 2026-04-07 21:00 UTC)

| Element | Answer |
|--------|--------|
| WHO    | Cursor IDE Agent (actor_id 102) |
| WHAT   | Schema and filesystem migrations for memory, actors, doctrine, content, ingestion |
| WHERE  | install_new_lupopedia.sql, lupo-content/, lupo-actors/116/, registry.json, PRDs |
| WHEN   | 2026-04-07 21:00 UTC |
| WHY    | To align schema, content, and actor structures with new doctrine and ingestion requirements |
| HOW    | See below for details; all changes tracked in CHANGELOG.md |

### Migration Steps

1. **Schema**
  - lupo_edges: Added edge_context, edge_status, direction, review_reason columns; new indexes
  - lupo_memory_nodes: Table added to install SQL
  - lupo_contents: storage_type, file_path_from_root expansion, new indexes
  - lupo_folders: description column added

2. **Filesystem**
  - lupo-content/: Canonical structure enforced; old directories removed; files renamed
  - lupo-actors/116/: Created for Claude Code actor

3. **Registry**
  - lupo-database/lupopedia/actors/registry.json: Claude Code (actor_id 116) added

4. **PRDs**
  - All relevant PRDs updated to document new schema, memory, and actor rules

See [CHANGELOG.md](CHANGELOG.md) for a full list of files and details.

This output complies with Lupopedia Constitutional Root Rules.
