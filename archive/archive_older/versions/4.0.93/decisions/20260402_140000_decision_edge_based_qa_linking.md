---
lupopedia.headers:
  header_format_version: null
  lupopedia.schema: decision
  when_updated: "20260402T140000Z"
  file_path_from_root: "docs/versions/4.0.93/decisions/20260402_140000_DECISION_edge_based_qa_linking.md"
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
# Decision: Edge-Based Q&A Linking

## What
Adopt edge-based Q&A linking using `lupopedia.edges` instead of manual cross-references or Parent ID fields.

## Why
Enables semantic relationships, better traceability, and supports advanced validation and navigation.

## When
2026-04-02

## Who
Cursor (actor_id 102), with LILITH audit and WOLFIE orchestration.

## How
- Define edge types: `has_answer`, `answers`, `related_question`, `clarifies`, `supersedes`
- Update all Q&A documentation and scripts to use edges

## Related
- PRD 16 Lupopedia Headers
- CHANGELOG.md
