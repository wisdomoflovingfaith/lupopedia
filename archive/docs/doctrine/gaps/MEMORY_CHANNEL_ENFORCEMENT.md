---
lupopedia.headers:
  header_format_version: "4.1.2"
  file_path_from_root: "docs/doctrine/gaps/MEMORY_CHANNEL_ENFORCEMENT.md"
  web_path: "https://www.lupopedia.com/lupopedia/docs/doctrine/gaps/MEMORY_CHANNEL_ENFORCEMENT.md"
  status: "active"
  when_updated: "20260417000000"
  trust_tier: "canonical"
  questions_toon: null
  memory_toon: "memory/development/canonical/1026/04/memory-channel-enforcement-gaps.toon"
  atoms_toon: "memory/atoms/lupopedia_global_constants.atom.toon"
  transcript_jsonl: "0/development/memory-channel-enforcement"
  artifact_type: doctrine
  artifact_kind: reference
  channel_key: "development"
  federation_node_id: 0
  thread_id: ""
  content_id: null
  content_parent_id: null
  content_slug: "memory-channel-enforcement-gaps"
  default_collection_id: null
  lupopedia.schema: doctrine
  title: "Gap Tracking: Memory Channel Enforcement"
  summary: "Documents known schema and validator gaps in memory channel separation. Covers missing DB column, missing edge consistency, unspecified registry schema, and missing validator enforcement. Tracks required future migration work."
---
# Gap Tracking: Memory Channel Enforcement

**Created:** 2026-04-17
**Status:** Open
**Owner:** WOLFIE
**Related PRDs:** PRD 38, PRD 16, PRD 37

This document tracks the known enforcement gaps for memory channel separation identified during
doctrine verification on 2026-04-17. Each gap is classified by severity and assigned a migration
phase.

---

## Gap 1: `channel_key` Column in `lupo_memory_nodes`

**Severity:** High
**Phase:** RESOLVED — column exists in live DB; install SQL alignment pending (IDE agent task)

### Resolution

`channel_key` column has been added to `lupo_memory_nodes` in the live database (2026-04-17).
`generate_toon_files.py` rerun successfully after migration.

Null values in a freshly-installed (empty) DB are **expected initialization state**, not a schema
failure. The install DB starts empty; rows are populated later via reconstruction/import from
filesystem artifacts. See Gap 6 for the population/reconstruction logic.

### Remaining work

- `install_new_lupopedia.sql` DDL for `lupo_memory_nodes` must include `channel_key` so fresh
  installs get the column at install time (IDE agent task)
- PRD 38 §5.1 DDL block should be updated to match

---

## Gap 2: `channel_key` Column in `lupo_memory_edges`

**Severity:** Medium
**Phase:** RESOLVED — column exists in live DB; install SQL alignment pending (IDE agent task)

### Resolution

`channel_key` column has been added to `lupo_memory_edges` in the live database (2026-04-17).
Same initialization note as Gap 1: null values in an empty install DB are expected, not a failure.
Channel context is populated during reconstruction/import. See Gap 6.

### Remaining work

- `install_new_lupopedia.sql` DDL for `lupo_memory_edges` must include `channel_key`
- PRD 38 §5.2 DDL block should be updated to match

---

## Gap 3: `allowed_cross_channel_memory` Schema Undefined

**Severity:** Medium
**Phase:** Near-term doctrine work (no schema change required)

### Current state

PRD 38 mentions `allowed_cross_channel_memory` in `channels/registry.json` as the mechanism
for cross-channel access control. The field is named but its structure is not specified anywhere:

- No documented JSON shape for the field
- No validator that reads it
- No agent loading code that enforces it

### Required state

Define the minimal shape in `channels/registry.json`:

```json
{
  "channel_key": "development",
  "channel_id": 1,
  "allowed_cross_channel_memory": ["headers", "trust_ladder"]
}
```

Rules (from PRD 38 doctrine patch):
- Default: DENY (no cross-channel access unless declared)
- Allowlist only: values are channel_key strings that this channel may read
- No silent inheritance: child channels do not inherit parent allowlists

**This gap does not require a schema change.** It requires:
1. A registry schema spec (can be a doctrine file or a JSON Schema)
2. Agent loading code that reads and enforces the field
3. A validator rule that reads `channels/registry.json` when `--verify-channels` is passed

---

## Gap 4: Validator Does Not Check `memory_toon` Path vs `channel_key`

**Severity:** Medium
**Phase:** RESOLVED — 2026-04-17

### Resolution

`validate_memory_key_path_shape` in `validate_lupopedia_headers_universal.py` already performed
the channel segment check. The error code was updated from `HDR_MEMORY_KEY` to
`HDR_CHANNEL_PATH_MISMATCH` to match the doctrine defined in PRD 16 §10.1.

The check:
1. Parses the first path segment of `memory_toon` after `memory/`
2. Compares it to the declared `channel_key`
3. Emits `HDR_CHANNEL_PATH_MISMATCH` (ERROR) if they differ
4. Is unreachable when `memory_toon` is null (null is rejected earlier by `validate_memory_key`)

No additional logic was required — only the error code label was updated.

---

## Gap 5: MemoryExportService Path Does Not Include Channel Segment

**Severity:** Low (by design for Type B)
**Phase:** No action required — documented by design

### Current state

`MemoryExportService` exports to `memory/{YYYY}/{MM}/{slug}.json` — no channel segment.

### Resolution

This is intentional. As of the PRD 38 doctrine patch (2026-04-17), Type B exports (`.json`
system mirrors) are explicitly defined as non-channel-scoped. The absence of `channel_key`
in the Type B path is correct and by design.

This gap is closed by the doctrine patch. No code change required.

The residual issue is that `MemoryExportService` cannot generate Type A `.toon` channel-scoped
artifacts. If/when that capability is needed, it requires a separate service or flag. That is
out of scope for the current patch.

---

## Gap 6: `channel_key` Population During Reconstruction / Import

**Severity:** Medium
**Phase:** Open — verified absent from all write paths (2026-04-17)

### Context

The install DB starts empty. Null `channel_key` values in a freshly-installed database are
**expected initialization state**, not a schema failure. This gap is about the reconstruction
path — how `channel_key` is derived and written when importing from `.toon` artifacts.

### Verified current state

The following write paths were read directly. None of them include `channel_key`.

**`generate_toon_files.py`** — NOT relevant to this gap. It is a schema documentation exporter:
reads live DB structure via `SHOW TABLES` / `DESCRIBE`, writes to `database/lupopedia/`.
Does not read `memory/*.toon` files and does not write to `lupo_memory_nodes`. "Rerun
successfully" means schema docs were regenerated. Memory node data was not touched.

**`import_memory_edges_from_sidecar.py` — auto-create-source-node INSERT:**
```
memory_node_id, created_ymdhis, owner_actor_id, owner_type, memory_type,
memory_toon, memory_value, context, status, content_hash,
updated_ymdhis, expires_ymdhis, is_deleted, deleted_ymdhis
```
`channel_key` absent. The script has `memory_toon` in hand at insert time — derivation is
mechanically possible but not implemented.

**`lib/db_memory_writer.py` — `create_memory_node` INSERT:**
```
memory_node_id, created_ymdhis, owner_actor_id, owner_type, memory_type,
memory_toon, memory_value, context, status, review_reason, content_hash, context_json,
updated_ymdhis, expires_ymdhis, is_deleted, deleted_ymdhis
```
`channel_key` absent. `memory_toon` is present in the `row` dict at insert time.

**`lib/db_memory_writer.py` — `create_memory_edges` INSERT:**
```
memory_edge_id, from_memory_node_id, to_memory_node_id, edge_type, edge_context,
edge_status, edge_direction, weight_hundredths, provenance_actor_id, provenance_tool,
review_reason, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis
```
`channel_key` absent.

### Required behavior (not yet implemented)

During reconstruction or import from filesystem artifacts, the following derivation order
MUST be applied to determine `channel_key` for a row:

1. **Header field** — read `channel_key` directly from the `.toon` file's LUPOPEDIA header
2. **Path segment** — if header is absent or unparseable, extract `parts[1]` from the
   `memory_toon` value relative to `memory/` (e.g. `memory/development/...` → `development`)
3. **Fallback** — if neither source is available, do NOT insert with null `channel_key`;
   log as unresolvable and skip

For `lupo_memory_edges`, derive `channel_key` from the source node's `channel_key` unless the
edge explicitly crosses channels.

### Where fixes are needed

| File | Location | Fix needed |
|------|----------|------------|
| `scripts/import_memory_edges_from_sidecar.py` | auto-create-source-node INSERT (~line 319) | Add `channel_key` derivation from `memory_toon` path + INSERT column |
| `scripts/lib/db_memory_writer.py` | `create_memory_node` INSERT (~line 148) | Add `channel_key` to INSERT column list; caller must populate `row["channel_key"]` |
| `scripts/lib/db_memory_writer.py` | `create_memory_edges` INSERT (~line 228) | Add `channel_key` to INSERT column list; derive from source node or caller context |

### What is NOT a failure

Null `channel_key` rows in an empty install DB at install time. The install starts with no memory
nodes. Population happens after first use, reconstruction, or seeding.

---

## Summary Table

| Gap | Severity | Blocking | Phase |
|-----|----------|----------|-------|
| 1. `channel_key` column on `lupo_memory_nodes` | High | Yes (DB enforcement) | RESOLVED in live DB; install SQL pending |
| 2. `channel_key` column on `lupo_memory_edges` | Medium | Yes (edge scoping) | RESOLVED in live DB; install SQL pending |
| 3. `allowed_cross_channel_memory` schema undefined | Medium | No (runtime guard) | Open — near-term doctrine |
| 4. Validator `HDR_CHANNEL_PATH_MISMATCH` not implemented | Medium | No (header quality) | RESOLVED 2026-04-17 |
| 5. MemoryExportService no channel segment | Low | No (by design) | Closed by doctrine patch |
| 6. `channel_key` population during reconstruction/import | Medium | No (initialization) | Open — reconstruction logic |

---

## Related

- PRD 38 §"Channel Scope for Memory" — doctrine for `.toon` channel path
- PRD 38 §6.1 — MemoryExportService `.json` path (Type B, system-level)
- PRD 16 §10 — `HDR_CHANNEL_PATH_MISMATCH` validator rule
- PRD 37 §6 — KAIROS edge channel gap annotation
- `channels/registry.json` — channel registry (schema TBD)
