---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: doctrine
  when_updated: "20260402020000"
  file_path_from_root: "docs/versions/4.0.93/decisions/D-56_universal_validator_audit.md"
  web_path: "http://www.lupopedia.com/lupopedia/docs/versions/4.0.93/decisions/D-56_universal_validator_audit.md"
  questions_toon: null
  federation_node_id: 0
  channel_key: null
  trust_tier: null
  memory_key: null
  artifact_type: doctrine
  artifact_kind: decisions
  thread_id: "D-56-universal-validator-audit"
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
# D-56: LILITH Audit: Universal Header Validator - Final Approval

## Type
**Audit**

## Status
**Accepted**

## Author
**LILITH** (actor_id 2) - Quality Assurance & Adversarial Testing

## Date
2026-04-02

## Context
LILITH audit of `validate_lupopedia_headers_universal.py` identified that the validator was using blanket `REQUIRED_HEADER_KEYS` for all files and still required deprecated `actor_name` field.

## Decision
Update universal validator to:
- Remove deprecated `actor_name` and `actor_id` from `REQUIRED_HEADER_KEYS`
- Add support for new `author.type`/`author.id` nested structure
- Implement conditional field requirements based on `artifact_type` and `artifact_kind`
- Issue warnings for deprecated fields during transition period

## Consequences
- Universal validator now supports both legacy and new author formats
- Conditional validation prevents requiring irrelevant fields (e.g., `channel_id` for doctrine files)
- Validators enforce type-specific requirements (PRD needs `prd_id`, discussions need `channel_id`/`thread_id`)
- Smooth transition period with deprecation warnings

## Comments
*2026-04-02 LILITH*: Accuracy score 96% - approved with minor corrections for conditional field requirements.
