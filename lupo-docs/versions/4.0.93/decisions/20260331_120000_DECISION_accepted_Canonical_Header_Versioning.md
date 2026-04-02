---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: doctrine
  when_updated: "20260331120000"
  file_path_from_root: "lupo-docs/versions/4.0.93/decisions/20260331_120000_DECISION_accepted_Canonical_Header_Versioning.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/versions/4.0.93/decisions/20260331_120000_DECISION_accepted_Canonical_Header_Versioning.md"
  last_modified_utc: "20260331120000"
  federation_node_id: 0
  channel_id: 42
  thread_id: "D-2"
  actor_id: 1
  actor_name: "WOLFIE"
  delegation_chain: "wolfie:root"
  artifact_type: "doctrine"
  artifact_kind: "decisions"
  purpose: "Canonical Header Versioning"
  tags:
  - "decisions"
  - "legacy"
  - "version-4.0.93"
lupopedia.footer:
  last_verified: "20260331120000"
  verified_by:
    identity_type: "actor"
    actor_id: 1
    agent_name_identity: "WOLFIE"
  orchestrator: "wolfie:root"
---

# D-2: Canonical Header Versioning

## Type
Decision

## Status
Accepted

## Author
LILITH (actor_id 2) - Quality Assurance & Adversarial Testing

## Date
2026-03-31

### Context
Lupopedia 4.0.93 introduces header_format_version 2.0, replacing legacy version fields and clarifying the separation between content, file, and verification timestamps. Multiple version fields (version_when_written, system_version, lupopedia.version) created confusion about which was authoritative.

### Decision
Adopt header_format_version 2.0 for all new and updated artifacts. Migrate existing headers as part of normal editing workflows. Use `when_updated` for content changes, `last_modified_utc` for file writes, and `last_verified` for trust recency.

### Consequences
- Improved validator consistency
- Clearer upgrade and migration path
- Temporary migration burden for contributors

### Comments
*2026-03-31 LILITH*: Validators now warn on version_when_written; will reject in 4.1.0.
*2026-03-31 WOLFIE*: All new PRDs must use version 2.0 format.

---
