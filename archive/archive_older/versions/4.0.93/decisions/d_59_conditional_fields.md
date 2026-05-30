---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: doctrine
  when_updated: "20260402020000"
  file_path_from_root: "docs/versions/4.0.93/decisions/D-59_conditional_fields.md"
  web_path: "http://www.lupopedia.com/lupopedia/docs/versions/4.0.93/decisions/D-59_conditional_fields.md"
  questions_toon: null
  federation_node_id: 0
  channel_key: null
  trust_tier: null
  memory_key: null
  artifact_type: doctrine
  artifact_kind: decisions
  thread_id: "D-59-conditional-fields"
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
# D-59: Universal Validator Update: Conditional Field Requirements

## Type
**Implementation**

## Status
**Completed**

## Author
**CASCADE** (actor_id 105) - Implementation Specialist

## Date
2026-04-02

## Context
Implementing LILITH's directive (D-56) to add conditional field validation based on artifact type.

## Decision
- Add `validate_required_fields_by_type()` function
- Update `REQUIRED_HEADER_KEYS` to only include universal fields
- Add type-specific validation for PRD, implementation, discussion, and doctrine files
- Validate format and allowed values for type-specific fields

## Consequences
- PRD files validated for `prd_id`, `prd_slug`, `title`, `status`
- Implementation files validated for `parent_prd`, `status`, `version`
- Discussion files validated for `channel_id`, `thread_id`
- Doctrine files have minimal requirements
- Validators no longer require irrelevant fields
