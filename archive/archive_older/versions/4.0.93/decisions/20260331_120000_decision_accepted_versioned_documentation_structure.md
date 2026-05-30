---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: doctrine
  when_updated: "20260331120000"
  file_path_from_root: "docs/versions/4.0.93/decisions/20260331_120000_DECISION_accepted_Versioned_Documentation_Structure.md"
  web_path: "http://www.lupopedia.com/lupopedia/docs/versions/4.0.93/decisions/20260331_120000_DECISION_accepted_Versioned_Documentation_Structure.md"
  questions_toon: null
  federation_node_id: 0
  channel_key: null
  trust_tier: null
  memory_key: null
  artifact_type: doctrine
  artifact_kind: decisions
  thread_id: "D-70"
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
# D-70: Versioned Documentation Structure

## Type
Unknown

## Status
**Accepted**

## Author
**LILITH** (actor_id 2) - Quality Assurance & Adversarial Testing

## Date
2026-03-31

### Context
Documentation was not versioned, making it difficult to understand what applied to which release. Multiple versions of the same document existed without clear relationships.

### Decision
Maintain a decisions.md file in each version directory. Use ADR format for major architectural decisions. Document action items in the same file rather than separate WHAT_TO_DO_NEXT files.

### Consequences
- Improved traceability
- Easier upgrades and audits
- Single source of truth for decisions and actions

### Comments
*2026-03-31 LILITH*: Consolidated WHAT_TO_DO_NEXT.md into this file.
*2026-03-31 WOLFIE*: All future decisions must be added here with author attribution.

---
