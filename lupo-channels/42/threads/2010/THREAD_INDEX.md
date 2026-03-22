---
lupopedia.headers:
  version_when_written: "4.0.85"
  lupopedia.schema: "thread_index"
  file_path_from_root: "lupo-channels/42/threads/2010/THREAD_INDEX.md"
  web_path: "http://www.lupopedia.com/channels/42/threads/2010"
  last_modified_utc: "20260322_160000"
  channel_id: 42
  thread_id: 2010
  task_id: "task_ch42_th2010"
  actor_id: 14
  actor_name: "hephaestus"
  delegation_chain: "hephaestus:implementation"
  artifact_type: "thread_index"
  artifact_kind: "index"
  purpose: "Filesystem actor/agent/faucet import and bidirectional sync model — deterministic post-install importer plus optional DB-to-filesystem export"
  tags: ["actors", "agents", "faucets", "import", "export", "sync", "filesystem", "database", "deterministic", "hephaestus", "thread_2010"]

lupopedia.edges:
  outbound_edges:
    - { to: "lupo-scripts/import_filesystem_actors_agents_to_db.py", type: "delivers", weight: 1.0, reason: "Primary deliverable — filesystem → DB import script" }
    - { to: "lupo-scripts/export_db_actors_agents_to_filesystem.py", type: "delivers", weight: 1.0, reason: "Secondary deliverable — DB → filesystem export script" }
    - { to: "lupo-docs/versions/4.0.85/actor_agent_sync_model_docs.md", type: "delivers", weight: 0.9, reason: "Sync model documentation" }
    - { to: "lupo-channels/42/threads/2009/THREAD_INDEX.md", type: "related_to", weight: 0.8, reason: "Sibling thread — channel/artifact import model" }
    - { to: "lupo-database/lupopedia/actors/actor_id/registry.json", type: "reads", weight: 1.0, reason: "Primary actor registry source" }
    - { to: "lupo-agents/", type: "reads", weight: 0.9, reason: "Agent filesystem data source" }

lupopedia.footer:
  last_updated: "20260322_160000"
  thread_status: "completed"
  artifact_count: 2
  assigned_actor: "hephaestus"
  deliverables:
    - "lupo-scripts/import_filesystem_actors_agents_to_db.py"
    - "lupo-scripts/export_db_actors_agents_to_filesystem.py"
    - "lupo-docs/versions/4.0.85/actor_agent_sync_model_docs.md"
    - "lupo-channels/42/threads/2010/20260322_160000_hephaestus_actor_agent_sync_implementation_report.md"
---

# Thread 2010 — Filesystem Actor/Agent Import and Sync Model

**Channel:** 42 | **Thread:** 2010 | **Actor:** HEPHAESTUS (14) | **Status:** in-progress

## Objective

Implement a deterministic post-install importer for actors/agents/faucets AND a bidirectional sync model between filesystem and database.

## Truth Model

- **FILESYSTEM IS PRIMARY SOURCE OF TRUTH**
- **DATABASE IS RUNTIME STATE**

On import (filesystem → DB): filesystem **overwrites** database for existing records.
On export (DB → filesystem): explicit optional script only.

## Deliverables

| File | Status | Description |
|------|--------|-------------|
| `lupo-scripts/import_filesystem_actors_agents_to_db.py` | pending | Primary — filesystem → DB |
| `lupo-scripts/export_db_actors_agents_to_filesystem.py` | pending | Secondary — DB → filesystem |
| `lupo-docs/versions/4.0.85/actor_agent_sync_model_docs.md` | pending | Sync model documentation |
| Implementation report | pending | Thread 2010 completion report |
