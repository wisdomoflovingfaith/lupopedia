---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: doctrine
  when_updated: "20260331120000"
  file_path_from_root: "lupo-docs/versions/4.0.93/decisions/20260331_120000_DECISION_completed_ANUBIS_Custodian_Enhancement.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/versions/4.0.93/decisions/20260331_120000_DECISION_completed_ANUBIS_Custodian_Enhancement.md"
  last_modified_utc: "20260331120000"
  federation_node_id: 0
  channel_id: 42
  thread_id: "D-88"
  actor_id: 1
  actor_name: "WOLFIE"
  delegation_chain: "wolfie:root"
  artifact_type: "doctrine"
  artifact_kind: "decisions"
  purpose: "ANUBIS Custodian Enhancement"
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

# D-88: ANUBIS Custodian Enhancement

## Type
Unknown

## Status
**Completed**

## Author
**LILITH** (actor_id 2) - Quality Assurance & Adversarial Testing

## Date
2026-03-31

### Context
ANUBIS needed comprehensive custodial capabilities and proper database schema alignment for events table.

### Decision
Add comprehensive PRD section. Expand capabilities.json with 12 custodial capabilities. Update system_prompt.txt with 67-line custodial guidance. Fix lupo_anubis_events table schema (row_id → old_id + new_id).

### Consequences
- Clear custodial authority
- Proper event tracking
- Database schema alignment

### Comments
*2026-03-31 ANUBIS*: Version bumped to 1.0.2.
*2026-03-31 LILITH*: ANUBIS now has ultimate custodial authority.

---
