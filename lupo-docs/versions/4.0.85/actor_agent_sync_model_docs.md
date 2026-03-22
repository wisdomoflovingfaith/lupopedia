---
lupopedia.headers:
  version_when_written: "4.0.85"
  lupopedia.schema: "documentation"
  file_path_from_root: "lupo-docs/versions/4.0.85/actor_agent_sync_model_docs.md"
  web_path: "http://www.lupopedia.com/docs/versions/4.0.85/actor_agent_sync_model"
  last_modified_utc: "20260322_184651"
  channel_id: 42
  thread_id: 2010
  task_id: "task_ch42_th2010"
  actor_id: 14
  actor_name: "hephaestus"
  delegation_chain: "hephaestus:implementation"
  artifact_type: "documentation"
  artifact_kind: "guide"
  purpose: "Corrected guide to the actor/agent filesystem and database sync model for Lupopedia"
  tags: ["actors", "agents", "faucets", "sync", "import", "export", "filesystem", "database", "truth_model", "documentation"]

lupopedia.edges:
  outbound_edges:
    - { to: "lupo-scripts/import_filesystem_actors_agents_to_db.py", type: "documents", weight: 1.0, reason: "Primary tool documented here" }
    - { to: "lupo-scripts/export_db_actors_agents_to_filesystem.py", type: "documents", weight: 1.0, reason: "Secondary tool documented here" }
    - { to: "lupo-database/lupopedia/actors/actor_id/registry.json", type: "references", weight: 0.9, reason: "Canonical actor registry" }

lupopedia.footer:
  last_updated: "20260322_173000"
  thread_status: "completed"
---

# Actor / Agent Filesystem â†” Database Sync Model (Corrected)

**Version:** 4.0.85  
**Thread:** 2010 â€” Channel 42  
**Actor:** HEPHAESTUS (14)  
**Correction Date:** 2026-03-22  
**Audit Response:** Addressed LILITH findings (Thread 2010 audit)

---

## 1. Core Truth Model (Clarified)

```
FILESYSTEM IS AUTHORITATIVE SOURCE FOR ACTOR/AGENT DEFINITIONS
DATABASE IS RUNTIME PROJECTION
```

This applies to actor and agent definitions only. Different subsystems have different authority models.

| Subsystem | Authority |
|-----------|-----------|
| Actor and agent definitions | Filesystem (`lupo-agents/`, `lupo-database/lupopedia/actors/actor_id/registry.json`) |
| Task and thread state | TASK_REGISTRY.md (authoritative) |
| Schema | install SQL (authoritative) |
| Federation nodes | install SQL plus seeds (authoritative) |

No contradiction. Each subsystem has one explicit authority. This document defines authority for actors and agents only.

| Property | Filesystem | Database |
|----------|-----------|----------|
| Source of truth for actor and agent definitions | **YES** | No |
| Versioned in git | **YES** | No |
| Long-term canonical | **YES** | No |
| Runtime access | No | **YES** |
| Rebuilt after install | From filesystem | Rebuilt from filesystem |

---

## 2. Filesystem Sources (Unchanged)

Data for actors/agents is stored across three filesystem trees:

### 2a. Canonical actor registry
```
lupo-database/lupopedia/actors/actor_id/registry.json
```
Contains the authoritative list of all actors: `id`, `type`, `slug`, `dir`.
This is the definitive actor ID mapping and nothing overrides it.

### 2b. Agent data (`lupo-agents/{actor_id}/`)
Each integer-named subdirectory contains:

| File | Contents |
|------|----------|
| `agent.json` | code, name, layer, role, description, is_kernel, version |
| `properties.json` | actor_id, slug, type, role, per-actor properties |
| `capabilities.json` | list of capability strings |
| `system_prompt.txt` | optional system prompt |
| `versions/` | version history (read-only archive) |

### 2c. Actor workspace (`lupo-actors/{id_or_slug}/`)
Per-actor working directory (apps, skills, docs, tools). Not written by export scripts.
This directory contains actor-authored content (skills, docs, tools) that are not part of the core actor definition.
Import and export scripts do not manage this directory.

---

## 3. Database Tables Written (Unchanged)

| Table | What is stored |
|-------|---------------|
| `lupo_actors` | One row per actor. PK: `actor_name` (= slug from registry.json). |
| `lupo_agents` | One row per non-human actor. PK: `agent_id` (= actor_id). |
| `lupo_agent_faucets` | One row per `ide_faucet` type actor. PK: deterministic hash. |
| `lupo_actor_capabilities` | One row per capability. UNIQUE: `(actor_id, domain_id, capability_key)`. |

Note on PK alignment: `lupo_actors.actor_name` is set from registry.json slug. The import script keeps them synchronized and logs divergence.

---

## 4. Import â€” Filesystem â†’ Database (Primary Operation)

**Script:** `lupo-scripts/import_filesystem_actors_agents_to_db.py`

### Conflict Resolution â€” Timestamp-Based

| Scenario | Action |
|----------|--------|
| Filesystem `last_modified_utc` > DB `updated_ymdhis` | Filesystem overwrites DB |
| DB `updated_ymdhis` > filesystem `last_modified_utc` | DB kept, logged as `db_newer_than_fs` |
| Record only in DB (no filesystem source) | Leave untouched, logged as `db_only_record` |
| Record only in filesystem | Insert into DB |
| No filesystem timestamp (legacy file) | Treat filesystem as older; DB kept unless force mode is used |

Timestamp sources:
- Filesystem: `last_modified_utc` from LUPOPEDIA HEADERS in source files
- Database: `updated_ymdhis`

This replaces asymmetric overwrite with deterministic last-write-wins handling.

### Reconciliation for `db_only_record`

`db_only_record` entries are not auto-deleted. They are flagged and require manual reconciliation:

```bash
python lupo-scripts/import_filesystem_actors_agents_to_db.py \
  --repo-root . --verbose \
  --user root --password ServBay.dev --database lupopedia \
  | grep "db_only_record"
```

If record should exist in filesystem:
- Create the missing filesystem files.
- Re-run import.

If record is obsolete:
- Delete from DB manually, or
- Run export intentionally if DB should become filesystem source for that record.

---

## 5. Export â€” Database â†’ Filesystem (Timestamp-Based)

**Script:** `lupo-scripts/export_db_actors_agents_to_filesystem.py`

### Conflict Resolution â€” Timestamp-Based

| Scenario | Action |
|----------|--------|
| DB `updated_ymdhis` > filesystem `last_modified_utc` | DB overwrites filesystem |
| Filesystem `last_modified_utc` > DB `updated_ymdhis` | Filesystem kept, logged as `fs_newer_than_db` |
| File does not exist in filesystem | Create new file |
| Record only in filesystem (not in DB) | Not touched (export reads from DB only) |

### Validation Before Export

Before export, run pre-flight validation:

```bash
python lupo-scripts/export_db_actors_agents_to_filesystem.py \
  --repo-root . --validate-only \
  --user root --password ServBay.dev --database lupopedia
```

Validation checks:
- DB records have valid non-zero `updated_ymdhis` timestamps
- No conflicting actor_id and actor_name mappings
- No duplicate actor_name values
- Referenced actor_ids exist in registry.json

If validation fails, export aborts.

---

## 6. Safe Workflow Examples (Updated)

### 6a. Post-install restore (standard operation)

```bash
# 1. DROP all tables
# 2. Load install SQL
#    mysql -u root -p lupopedia < install_new_lupopedia.sql
# 3. Import actors and agents from filesystem (authoritative source)
python lupo-scripts/import_filesystem_actors_agents_to_db.py \
  --repo-root . --user root --password ServBay.dev --database lupopedia
# 4. Import channels, threads, artifacts
python lupo-scripts/import_filesystem_channels_to_db.py ...
```

Seed SQL is no longer used for actor and agent data.

### 6b. Actor definition updated in filesystem (timestamp comparison)

```bash
# Edit lupo-agents/15/agent.json
# Re-run import: filesystem wins when filesystem timestamp is newer
python lupo-scripts/import_filesystem_actors_agents_to_db.py \
  --repo-root . --actor-id 15 --verbose \
  --user root --password ServBay.dev --database lupopedia
```

### 6c. Actor record changed in DB, needs to be persisted

```bash
# 1. Pre-flight validation
python lupo-scripts/export_db_actors_agents_to_filesystem.py \
  --repo-root . --actor-id 42 --validate-only \
  --user root --password ServBay.dev --database lupopedia

# 2. Dry-run export
python lupo-scripts/export_db_actors_agents_to_filesystem.py \
  --repo-root . --actor-id 42 --dry-run --verbose \
  --user root --password ServBay.dev --database lupopedia

# 3. Real export
python lupo-scripts/export_db_actors_agents_to_filesystem.py \
  --repo-root . --actor-id 42 --verbose \
  --user root --password ServBay.dev --database lupopedia

# 4. Review git diff
#    git diff lupo-agents/42/
# 5. Commit if correct
```

---

## 7. Risks of Misuse â€” Mitigated

| Risk | Mitigation |
|------|------------|
| Running export with stale DB | Pre-flight validation (`--validate-only`) blocks export on invalid DB state |
| Import overwriting newer DB changes | Timestamp comparison preserves newer DB records |
| db_only_record orphaned | Logged explicitly; manual reconciliation required |
| Export on fresh install | Validation fails if timestamps are invalid; export blocked |

---

## 8. Deterministic ID Guarantees (Unchanged)

- `actor_id` â€” integer from registry.json (never auto-generated)
- `agent_id` â€” same as `actor_id` (one-to-one mapping)
- `agent_faucet_id` â€” `SHA256("agent_faucet|{actor_id}|{slug}")[:8]` -> positive BIGINT
- `actor_capability_id` â€” `SHA256("actor_capability|{actor_id}|{domain_id}|{capability_key}")[:8]`

Running the import script with the same filesystem state always produces the same set of IDs in the database.

---

## 9. Limitations â€” Clarified

### Capabilities management

- Capabilities are loaded from `capabilities.json` in the filesystem.
- Capabilities added directly in DB are not preserved across import unless also present in filesystem.
- Intended behavior: `capabilities.json` is the source of truth.

### Actor workspace (`lupo-actors/{slug}/`)

- This directory is not managed by import or export.
- It contains actor-authored content separate from core actor definition files.

### Human actors (actor_id >= 1000)

- Imported to `lupo_actors`, but no `lupo_agents` rows are created.

### `lupo_auth_users`

- Not managed by either script; auth users are seeded separately.

---

## 10. Relationship to Other Truth Models

| Subsystem | Authority | Integration with Actor Model |
|-----------|-----------|------------------------------|
| Task and thread state | TASK_REGISTRY.md | Actor assignments reference actor_id and slug from registry.json |
| Schema | install SQL | No direct integration |
| Federation nodes | install SQL plus seeds | No direct integration |

Actor task assignments live in TASK_REGISTRY.md, not in actor definition files.

---

## 11. Summary of Changes from Original

| Issue | Original | Corrected |
|-------|----------|-----------|
| Truth model ambiguity | Unscoped filesystem claim | Scoped to actor and agent definitions only |
| Asymmetric overwrite | Filesystem always wins | Timestamp-based last-write-wins |
| DB state validation | None | Pre-flight `--validate-only` checks |
| `db_only_record` handling | Logged without reconciliation path | Logged with explicit reconciliation workflow |
| `lupo-actors/` vs `lupo-agents/` | Ambiguous | Clarified role separation |
| Capability source | Ambiguous | Filesystem capabilities are canonical |
| TASK_REGISTRY integration | Not explicit | Explicitly separated authority boundaries |

---

HEPHAESTUS (actor_id 14) corrected actor and agent sync model published. LILITH audit findings addressed. Ready for review.

