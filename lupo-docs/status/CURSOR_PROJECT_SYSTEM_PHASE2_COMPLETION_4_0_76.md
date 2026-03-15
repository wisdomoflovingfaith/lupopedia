---
lupopedia.headers:
  lupopedia.version: "4.0.76"
  lupopedia.schema: "status"
  file_path_from_root: "lupo-docs/status/CURSOR_PROJECT_SYSTEM_PHASE2_COMPLETION_4_0_76.md"
  last_modified_utc: "20260316"
  system_version: "4.0.76"
  channel_id: 42
  actor_id: 102
  artifact_type: "status"
  artifact_kind: "checkpoint"
  purpose: "Phase 2 schema completion checkpoint; Project System 4.0.76."

lupopedia.footer:
  version: "4.0.76"
  last_verified: "20260316"
  last_verified_by: "cursor"
---
# Cursor Project System Phase 2 Completion — 4.0.76

## Summary

Phase 2 (Schema) for the Project System is complete. Schema is fully usable for application and API work.

## Schema Changes

| Item | Status |
|------|--------|
| TOON generation | Ran `generate_toon_from_sql.py`; 159 TOONs generated |
| lupo_projects.toon.json | Exists and matches install SQL (default_channel_id, indexes, no channel_id) |
| Table-count documentation | Updated `TABLE_COUNT_DOCTRINE.md` to 4.0.76; TOON path set to `lupo-database/lupopedia/toon/` |
| seed_projects.sql | Aligned with install: default_channel_id, all doctrine columns, single default project (project_id 1) |
| lupo_channels.project_id | Added `project_id bigint DEFAULT NULL` to install SQL |
| lupo_channels index | Added `CREATE INDEX lupo_channels_idx_project_id ON lupo_channels (project_id)` |
| Install + seed workflow | install_new_lupopedia.sql then seed_projects.sql; no conflicts with other seeds |

## Table Count

- **159** tables in install SQL (unchanged; lupo_projects already present).
- **159** TOON files in `lupo-database/lupopedia/toon/`.
- Within advisory ceiling (e.g. 222). See `lupo-docs/doctrine/TABLE_COUNT_DOCTRINE.md`.

## Compatibility

- Existing channels remain valid: `project_id` is nullable, no FK, no breaking constraints.
- Backward compatibility preserved.

## Files Touched

- `lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql` — project_id + index on lupo_channels
- `lupo-database/lupopedia/mysql/seed/seed_projects.sql` — rewritten to match install schema
- `lupo-docs/doctrine/TABLE_COUNT_DOCTRINE.md` — version 4.0.76, TOON path, verification date
- `lupo-database/lupopedia/toon/*.toon.json` — regenerated (including lupo_projects, lupo_channels)

## Validation Checkpoint

- [x] TOON files generated
- [x] Table-count documentation updated
- [x] Seed data created and aligned
- [x] project_id column added to lupo_channels
- [x] Channels index created
- [x] Install + seed workflow validated (structure only; no live DB run in this artifact)

Phase 3 (Application) may proceed.
