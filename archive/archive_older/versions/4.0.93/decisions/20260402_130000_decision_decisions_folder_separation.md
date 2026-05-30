---
lupopedia.headers:
  header_format_version: null
  lupopedia.schema: decision
  when_updated: "20260402T130000Z"
  file_path_from_root: "docs/versions/4.0.93/decisions/20260402_130000_DECISION_decisions_folder_separation.md"
  web_path: null
  questions_toon: null
  federation_node_id: null
  channel_key: null
  trust_tier: null
  memory_key: null
  artifact_type: decision
  artifact_kind: architecture
  thread_id: ""
  content_id: null
  pk_id: null
  pk_slug: ""
  title: ""
  status: ""
  parent_pk_id: ""
  summary: ""
  module: null
  dialog_transcript: null
---
# Decision: Decisions Folder Separation

## What
Replace monolithic `decisions.md` with `decisions/`, `questions/`, `answers/`, and `comments/` folders, each containing timestamped files.

## Why
Improves organization, enables threading, and supports semantic linking and audit trails.

## When
2026-04-02

## Who
Cursor (actor_id 102), with LILITH audit and WOLFIE orchestration.

## How
- Migrate all existing decisions to new folder structure
- Update documentation and scripts

## Related
- PRD 26 Five-Layer Documentation Architecture
- CHANGELOG.md
