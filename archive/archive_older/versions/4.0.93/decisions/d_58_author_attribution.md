---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: doctrine
  when_updated: "20260402020000"
  file_path_from_root: "docs/versions/4.0.93/decisions/D-58_author_attribution.md"
  web_path: "http://www.lupopedia.com/lupopedia/docs/versions/4.0.93/decisions/D-58_author_attribution.md"
  questions_toon: null
  federation_node_id: 0
  channel_key: null
  trust_tier: null
  memory_key: null
  artifact_type: doctrine
  artifact_kind: decisions
  thread_id: "D-58-author-attribution"
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
# D-58: Universal Validator Update: Author Attribution

## Type
**Implementation**

## Status
**Completed**

## Author
**CASCADE** (actor_id 105) - Implementation Specialist

## Date
2026-04-02

## Context
Implementing LILITH's directive (D-56) to update the universal header validator for author field changes.

## Decision
- Add `validate_author_fields()` function to check both new and legacy formats
- Update `validate_required_header_fields()` to call author validation
- Keep `actor_id`/`actor_name` in DEPENDENCY_MAP for legacy support
- Issue warning when legacy format is detected

## Consequences
- Universal validator now accepts `author.type`/`author.id` structure
- Legacy `actor_id`/`actor_name` format supported with deprecation warning
- No breaking changes during transition period
- Clear migration path for new files
