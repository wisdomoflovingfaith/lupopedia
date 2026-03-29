---
lupopedia.headers:
  version_when_written: "4.0.88"
  file_path_from_root: "lupo-channels/1_channel_refactor_governance/threads/channel_refactor_4_0_88/20260327_110206_cursor_channel_refactor_governance_charter.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-channels/1_channel_refactor_governance/threads/channel_refactor_4_0_88/20260327_110206_cursor_channel_refactor_governance_charter.md"
  last_modified_utc: "20260327110206"
  channel_id: 65
  thread_id: "channel_refactor_4_0_88"
  actor_id: 102
  actor_name: "cursor"
  delegation_chain: "cursor:root"
  artifact_type: "channel_artifact"
  artifact_kind: "charter"
  purpose: "Thread charter for 4.0.88 channel refactor governance"

lupopedia.edges:
  outbound_edges:
    - { to: "lupo-docs/versions/4.0.88/CHANNEL_REFACTOR_PRD.md", type: "implements", weight: 1.0 }
    - { to: "20260327_110206_cursor_channel_refactor_audit_report.md", type: "complements", weight: 1.0 }
    - { to: "questions/README.md", type: "contains", weight: 1.0 }
    - { to: "prompts/README.md", type: "contains", weight: 1.0 }

lupopedia.footer:
  last_verified: "20260327110206"
  last_verified_by: "cursor"
  last_verified_by_actor_id: 102
  orchestrator: "wolfie:root"
  next_action:
    - "Route channel refactor discussion and execution planning through this thread"
---

# Channel Refactor Governance Charter

## Thread Purpose

This thread centralizes decisions for:

- channel layout refactor planning
- `questions/` versus `prompts/` separation
- edge reconciliation rules during migration
- header and validator enforcement requirements
- LLM, CLI, and Web interface alignment

## Mandatory Constraints

1. No mass move of legacy channel trees.
2. No broken `lupopedia.edges` left behind.
3. No guessed edge replacements.
4. No history rewrite disguised as normalization.

## Decision Scope

- filesystem design
- hybrid MySQL and filesystem boundaries
- documentation enforcement
- implementation enforcement across interfaces

## Operational Model

- open uncertainty goes to `questions/`
- final execution steps go to `prompts/`
- audit and migration evidence remain in thread artifacts