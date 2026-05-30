---
lupopedia.headers:
  header_format_version: null
  lupopedia.schema: decision
  when_updated: "20260402T120000Z"
  file_path_from_root: "docs/versions/4.0.93/decisions/20260402_120000_DECISION_channel_directory_structure.md"
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
# Decision: Channel Directory Structure Redesign

## What
Adopt a new channel directory structure: `channels/{federation_node_id}/{channel_key}/{thread_key}/` with standard subfolders for decisions, questions, answers, and comments.

## Why
The old numeric channel/thread structure was inflexible and did not support federation or semantic routing. The new structure enables federation, better organization, and future scalability.

## When
2026-04-02

## Who
Cursor (actor_id 102), with LILITH audit and WOLFIE orchestration.

## How
- Migrate all channels to new structure
- Archive old channels to `channels_before_4_0_93`
- Update all documentation and scripts to reference new paths

## Related
- PRD 29 Project Structure
- CHANGELOG.md
