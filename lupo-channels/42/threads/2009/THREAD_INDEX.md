---
lupopedia.headers:
  version_when_written: "4.0.85"
  file_path_from_root: "lupo-channels/42/threads/2009/THREAD_INDEX.md"
  last_modified_utc: "20260322"
  channel_id: 42
  thread_id: 2009
  actor_id: 8
  actor_name: "hephaestus"
  artifact_type: "index"
  artifact_kind: "thread_index"
  purpose: "Navigation index for Thread 2009 — filesystem channel artifact import script implementation."
---

# Thread 2009 — Filesystem Channel Artifact Import

- **thread_status**: in-progress
- **task_id**: task_ch42_th2009
- **assigned_actor**: hephaestus
- **thread_name**: filesystem_channel_artifact_import_after_install
- **artifact_count**: 2
- **last_modified_utc**: 20260322

## Purpose

Implement `lupo-scripts/import_filesystem_channels_to_db.py` — a deterministic post-install importer  
that reads the channel/thread/artifact state from the filesystem and imports it into the database.

Required after: DROP ALL TABLES → RUN install_new_lupopedia.sql → fresh install.

## Deliverables

- `lupo-scripts/import_filesystem_channels_to_db.py` — canonical import entrypoint
- `lupo-docs/versions/4.0.85/import_filesystem_channels_docs.md` — usage documentation

## Dependencies

- Thread 2006: install SQL validated (COMPLETE)
- install_new_lupopedia.sql: schema authority confirmed correct

## Next Action

Script created and documented. Thread complete.
