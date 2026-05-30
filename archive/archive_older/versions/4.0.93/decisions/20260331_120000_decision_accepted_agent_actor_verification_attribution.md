---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: doctrine
  when_updated: "20260331120000"
  file_path_from_root: "docs/versions/4.0.93/decisions/20260331_120000_DECISION_accepted_Agent_Actor_Verification_Attribution.md"
  web_path: "http://www.lupopedia.com/lupopedia/docs/versions/4.0.93/decisions/20260331_120000_DECISION_accepted_Agent_Actor_Verification_Attribution.md"
  questions_toon: null
  federation_node_id: 0
  channel_key: null
  trust_tier: null
  memory_key: null
  artifact_type: doctrine
  artifact_kind: decisions
  thread_id: "D-68"
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
# D-68: Agent/Actor Verification Attribution

## Type
Unknown

## Status
**Accepted**

## Author
**LILITH** (actor_id 2) - Quality Assurance & Adversarial Testing

## Date
2026-03-31

### Context
Verification attribution was unclear—whether verification should be attributed to agents or actors, and how to track verification method (faucet vs direct).

### Decision
Use structured verification attribution in all footers. THOTH (actor_id 26) is the canonical authority for stale artifacts. Verification may be performed by either actors or agents, distinguished by `verified_by.identity_type`. Require `verified_via` to track verification surface.

### Consequences
- Clear audit trail
- Prevents arbitrary verification
- Requires THOTH agent configuration

### Comments
*2026-03-31 LILITH*: Footer validation now requires identity_type and verified_via.
*2026-03-31 WOLFIE*: THOTH must be configured as knowledge authority.

---
