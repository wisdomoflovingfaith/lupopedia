# Memory DB-First Research (PRD 38)

## Context

Research task for actor 102 confirms the architecture correction: memory writes must be database-first, with filesystem as read-only mirror.

## 1) Exact Schema: `lupo_memory_nodes`

Source: `database/lupopedia/mysql/install/install_new_lupopedia.sql`.

- `memory_node_id bigint NOT NULL` (PK)
- `created_ymdhis bigint NOT NULL DEFAULT 0`
- `owner_actor_id bigint NOT NULL`
- `owner_type varchar(32) NOT NULL DEFAULT 'actor'`
- `memory_type varchar(32) NOT NULL`
- `memory_key varchar(255) NOT NULL`
- `memory_value text`
- `context varchar(32) NOT NULL DEFAULT 'experiential'`
- `status varchar(32) NOT NULL DEFAULT 'unsupported'`
- `review_reason varchar(64) DEFAULT NULL`
- `content_hash char(64) NOT NULL`
- `context_json json DEFAULT NULL`
- `updated_ymdhis bigint NOT NULL DEFAULT 0`
- `expires_ymdhis bigint NOT NULL DEFAULT 0`
- `is_deleted tinyint NOT NULL DEFAULT 0`
- `deleted_ymdhis bigint NOT NULL DEFAULT 0`
- Indexes:
  - `memory_nodes_idx_owner` (`owner_actor_id`, `owner_type`, `is_deleted`)
  - `memory_nodes_idx_created` (`created_ymdhis`, `is_deleted`)
  - `memory_nodes_idx_type` (`memory_type`, `status`, `is_deleted`)
  - `memory_nodes_idx_key` (`memory_key`, `owner_actor_id`)
  - `memory_nodes_idx_updated` (`updated_ymdhis`, `is_deleted`)
  - `memory_nodes_idx_expires` (`expires_ymdhis`, `is_deleted`)

## 2) Exact Schema: `lupo_memory_edges`

Source: `database/lupopedia/mysql/install/install_new_lupopedia.sql`.

- `memory_edge_id bigint NOT NULL` (PK)
- `from_memory_node_id bigint NOT NULL`
- `to_memory_node_id bigint NOT NULL`
- `edge_type varchar(64) NOT NULL`
- `edge_context varchar(32) NOT NULL DEFAULT 'system_generated'`
- `edge_status varchar(32) NOT NULL DEFAULT 'supported'`
- `edge_direction varchar(16) NOT NULL DEFAULT 'unidirectional'`
- `weight_hundredths int NOT NULL DEFAULT 100`
- `provenance_actor_id bigint NOT NULL`
- `provenance_tool varchar(64) NOT NULL`
- `review_reason varchar(64) DEFAULT NULL`
- `created_ymdhis bigint NOT NULL DEFAULT 0`
- `updated_ymdhis bigint NOT NULL DEFAULT 0`
- `is_deleted tinyint NOT NULL DEFAULT 0`
- `deleted_ymdhis bigint NOT NULL DEFAULT 0`
- Indexes:
  - `memory_edges_idx_from` (`from_memory_node_id`, `is_deleted`)
  - `memory_edges_idx_to` (`to_memory_node_id`, `is_deleted`)
  - `memory_edges_idx_type` (`edge_type`, `edge_context`, `edge_status`)

## 3) Export Service Verification

`MemoryExportService.php` exists at `includes/classes/MemoryExportService.php` and already implements DB -> filesystem mirror behavior:

- Reads node row from `memory_nodes` by `memory_node_id`.
- Reads related edges from `memory_edges`.
- Writes mirror JSON to `memory/YYYY/MM/{slug}.json`.
- Uses `created_ymdhis` for year/month; falls back to `19700101000000` for zero/short values.
- Supports `exportNode`, `fullExport`, `exportSince`, and `removeMirrorFileForNode`.

Conclusion: export service is present and usable; it should be called after database writes.

## 4) Current `.toon` to DB Mapping

Observed `.toon` shape (`id`, `ts`, `actor_id`, `summary`, `edges[]`, `content`).

Proposed canonical mapping:

- `.toon.id` -> `memory_nodes.memory_node_id` (or dedicated external key inside `memory_key` if keeping generated DB IDs)
- `.toon.ts` -> `memory_nodes.created_ymdhis` (14-digit prefix) and `updated_ymdhis`
- `.toon.actor_id` -> `memory_nodes.owner_actor_id`
- `.toon.type` -> `memory_nodes.memory_type`
- `.toon.summary` + `.toon.content` -> `memory_nodes.memory_value` (JSON string)
- `.toon.content` metadata object -> `memory_nodes.context_json` (optional)
- `.toon.status` -> `memory_nodes.status`
- `.toon.edges[*]` -> `memory_edges` rows:
  - `from_memory_node_id` = current node id
  - `to_memory_node_id` = resolved target node id (needs resolver for `FILE:`, `CHANNEL:`, `TASK:` pseudo-refs)
  - `edge_type` from `.toon.edges[*].type`
  - `weight_hundredths` from `.toon.edges[*].weight * 100`

Important mismatch today: many `.toon.edges[*].to` values are symbolic refs, not numeric `to_memory_node_id` values. A resolver table or indirection strategy is required.

## 5) Changes Needed: Python Scripts (DB-first)

Current scripts write filesystem directly:

- `scripts/migrate_transcript_to_memory.py`
- `scripts/generate_json_headers.py`
- `bin/transcript.py`
- `bin/pending.py`

Required change set:

1. Add shared DB writer module for Python scripts:
   - Load DB creds from `lupopedia-config.php` or env bridge.
   - Insert into `memory_nodes` first, then `memory_edges`.
2. Replace direct `.toon` writes in both migration/generation scripts with:
   - create node/edges in DB
   - invoke export pass (CLI or PHP bridge) to mirror files
3. Keep transcript and pending scripts as task/transcript tools only; do not let them write memory files directly.
4. Add `--dry-run` and `--no-export` toggles for safe rollout.
5. Add idempotency keys (content hash + source path + ts) to avoid duplicate inserts.

## 6) Changes Needed: `MemoryGraph.php`

Current `MemoryGraph.php` is filesystem-only traversal (`.toon` files).

Required upgrade:

1. Add a read mode switch:
   - `source=filesystem` (current behavior)
   - `source=database` (authoritative)
   - `source=auto` (DB first, filesystem fallback)
2. Add DB resolvers:
   - load node by `memory_node_id` or key
   - load outbound edges from `memory_edges`
3. Normalize output shape to current graph payload so existing callers keep working.
4. Keep channel bootstrap fallback when no node exists.

## 7) Implementation Plan (Dependency Order)

1. Define canonical mapping and pseudo-ref resolution policy (`FILE:`, `CHANNEL:`, `TASK:`).
2. Build Python DB writer utility and unit-test node/edge insert path.
3. Refactor `migrate_transcript_to_memory.py` to DB-first + mirror export.
4. Refactor `generate_json_headers.py` to DB-first + mirror export.
5. Add DB mode to `MemoryGraph.php` and test parity against filesystem traversal.
6. Run full export sync (`MemoryExportService::fullExport`) and validate mirror consistency.
7. Cut over default mode to DB-first and keep filesystem read fallback for resilience.

## 8) Key Findings Summary

- PRD 38 is explicit: database is source of truth, filesystem is mirror.
- Schema already supports full graph storage (`memory_nodes`, `memory_edges`).
- Export layer already exists and should be reused, not rewritten.
- Current Python scripts are the main architectural gap (filesystem-first writes).
- `MemoryGraph.php` needs a DB read path to align runtime with authoritative storage.
