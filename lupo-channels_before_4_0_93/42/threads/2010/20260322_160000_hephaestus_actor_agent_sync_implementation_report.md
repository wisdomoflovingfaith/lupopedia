---
lupopedia.headers:
  version_when_written: "4.0.85"
  lupopedia.schema: "implementation_report"
  file_path_from_root: "lupo-channels/42/threads/2010/20260322_160000_hephaestus_actor_agent_sync_implementation_report.md"
  web_path: "http://www.lupopedia.com/channels/42/threads/2010/implementation-report"
  last_modified_utc: "20260322_160000"
  channel_id: 42
  thread_id: 2010
  task_id: "task_ch42_th2010"
  actor_id: 14
  actor_name: "hephaestus"
  delegation_chain: "hephaestus:implementation"
  artifact_type: "implementation_report"
  artifact_kind: "report"
  purpose: "Thread 2010 completion report — actor/agent filesystem↔DB sync model implementation"
  tags: ["implementation_report", "thread_2010", "actors", "agents", "faucets", "import", "export", "sync", "hephaestus"]

lupopedia.edges:
  outbound_edges:
    - { to: "lupo-scripts/import_filesystem_actors_agents_to_db.py", type: "reports_on", weight: 1.0, reason: "Primary deliverable" }
    - { to: "lupo-scripts/export_db_actors_agents_to_filesystem.py", type: "reports_on", weight: 1.0, reason: "Secondary deliverable" }
    - { to: "lupo-docs/versions/4.0.85/actor_agent_sync_model_docs.md", type: "reports_on", weight: 0.9, reason: "Documentation deliverable" }
    - { to: "lupo-channels/42/threads/2009/THREAD_INDEX.md", type: "related_to", weight: 0.7, reason: "Sibling thread — channel/artifact importer" }

lupopedia.footer:
  last_updated: "20260322_160000"
  thread_status: "completed"
  artifact_count: 2
---

# Thread 2010 — Implementation Report

**Actor:** HEPHAESTUS (14)  
**Channel:** 42 | **Thread:** 2010  
**Date:** 2026-03-22  
**Status:** COMPLETED

---

## Directive Summary

Implement a deterministic post-install importer for actors/agents/faucets AND define a bidirectional sync model with explicit, separate import and export scripts.

**Truth Model enforced:**
- FILESYSTEM IS PRIMARY SOURCE OF TRUTH
- DATABASE IS RUNTIME STATE

---

## Deliverables Produced

### 1. `lupo-scripts/import_filesystem_actors_agents_to_db.py`
**Script 1 — Primary import (filesystem → DB)**

- Reads `lupo-database/lupopedia/actors/actor_id/registry.json` as canonical actor list
- Loads `lupo-agents/{actor_id}/agent.json`, `properties.json`, `capabilities.json`, `system_prompt.txt` for each actor
- Supplements from `lupo-actors/{id|slug}/` where available
- Writes to: `lupo_actors`, `lupo_agents`, `lupo_agent_faucets`, `lupo_actor_capabilities`
- Conflict resolution: **filesystem OVERWRITES DB** (`INSERT ... ON DUPLICATE KEY UPDATE`)
- DB-only records: logged as `db_only_record`, never deleted
- Idempotent: running multiple times produces the same DB state

**Dry-run validation result:**
```
actors_found:              108
actors_inserted:           108  (dry-run)
agents_found:              106
agents_inserted:           106  (dry-run)
faucets_found:             7
faucets_inserted:          7    (all 7 IDE faucets)
capabilities_found:        82
capabilities_inserted:     82
error_count:               0
```

### 2. `lupo-scripts/export_db_actors_agents_to_filesystem.py`
**Script 2 — Optional export (DB → filesystem)**

- Reads `lupo_actors`, `lupo_agents`, `lupo_agent_faucets`, `lupo_actor_capabilities` from DB
- Writes to `lupo-agents/{actor_id}/` JSON files
- Regenerates `lupo-database/lupopedia/actors/actor_id/registry.json` from DB (unless `--no-registry`)
- Conflict resolution: **DB OVERWRITES filesystem** on all existing files
- Must be invoked explicitly — never runs automatically

### 3. `lupo-docs/versions/4.0.85/actor_agent_sync_model_docs.md`
**Documentation — Definitive sync model guide**

Covers:
1. Core truth model (filesystem is primary)
2. Filesystem source directories
3. DB tables written
4. Import operation (full conflict rules, usage examples, metrics)
5. Export operation (full conflict rules, warnings, usage examples)
6. Safe workflow examples (post-install, single actor, persist DB changes)
7. Risks of misuse (7 scenarios documented)
8. Deterministic ID guarantees
9. Known limitations

---

## Architecture Decisions

| Decision | Rationale |
|----------|-----------|
| `INSERT ... ON DUPLICATE KEY UPDATE` for import | Handles all 3 unique keys on `lupo_actors` (actor_name, actor_id, slug) in a single atomic statement |
| Import does NOT delete DB-only records | Prevents data loss from human actors, seeded records, or records managed by other subsystems |
| Export is a separate script | Makes the direction of flow unambiguous — no mixed bidirectional logic |
| `--no-registry` flag on export | Allows exporting actor files without overwriting the canonical registry (useful for single-actor exports) |
| Capabilities use UNIQUE ON DUPLICATE KEY UPDATE | Allows refreshing capability rows without recomputing IDs |
| Faucet ID = deterministic SHA256 hash | Stable across re-imports, reproducible from actor_id + slug |

---

## CLI Surface

### Import script
```
python lupo-scripts/import_filesystem_actors_agents_to_db.py --repo-root .
  [--actor-id N]          import only this actor
  [--actor-type TYPE]     filter: agent | ide_faucet | human | system
  [--dry-run]             no DB writes
  [--strict]              abort on first error
  [--verbose]             per-row output
  [--host] [--port] [--user] [--password] [--database] [--table-prefix]
```

### Export script
```
python lupo-scripts/export_db_actors_agents_to_filesystem.py --repo-root .
  [--actor-id N]          export only this actor
  [--no-registry]         skip regenerating registry.json
  [--dry-run]             no file writes
  [--strict]              abort on first error
  [--verbose]             per-file output
  [--host] [--port] [--user] [--password] [--database] [--table-prefix]
```

---

## Thread Status

Thread 2010 is **COMPLETE**. All 4 committed deliverables produced and validated.

Next actions:
- Update `lupo-channels/42/THREAD_INDEX.md` last_thread → 2010
- Update `lupo-docs/versions/4.0.85/TASK_REGISTRY.md` with task_ch42_th2010 row
