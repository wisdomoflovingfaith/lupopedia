---
lupopedia.headers:
  version_when_written: "4.0.88"
  file_path_from_root: "channels/1_channel_refactor_governance/README.md"
  web_path: "http://www.lupopedia.com/lupopedia/channels/1_channel_refactor_governance/README.md"
  questions_toon: null
  channel_id: 65
  actor_id: 102
  actor_name: "cursor"
  delegation_chain: "cursor:root"
  artifact_type: "channel_charter"
  artifact_kind: "governance"
  purpose: "Pilot governance channel for 4.0.88 channel refactor, phased migration, and edge integrity"
  tags: ["channels", "governance", "migration", "edge_integrity", "4.0.88"]

lupopedia.edges:
  outbound_edges:
    - { to: "docs/versions/4.0.88/CHANNEL_REFACTOR_PRD.md", type: "implements", weight: 1.0 }
    - { to: "channels/1_channel_refactor_governance/THREAD_INDEX.md", type: "indexes", weight: 1.0 }
    - { to: "channels/1_channel_refactor_governance/threads/channel_refactor_4_0_88/20260327_110206_cursor_channel_refactor_governance_charter.md", type: "contains", weight: 1.0 }
    - { to: "channels/1_channel_refactor_governance/threads/channel_refactor_4_0_88/20260327_110206_cursor_channel_refactor_audit_report.md", type: "contains", weight: 1.0 }

lupopedia.footer:
  last_verified: "20260327110206"
  last_verified_by: "cursor"
  last_verified_by_actor_id: 102
  orchestrator: "wolfie:root"
  next_action:
    - "Use this channel as the ongoing coordination hub for channel refactor governance"
---

# Channel 65: Channel Refactor Governance

## Purpose

This pilot channel centralizes 4.0.88 governance for:

- channel filesystem refactor planning
- `questions/` and `prompts/` separation
- hybrid filesystem and database authority boundaries
- header doctrine enforcement
- edge-safe migration review
- LLM, CLI, and Web coordination rules

## Scope

In scope:

- audit reports
- migration batch planning
- redirect and pointer policy
- validator requirements
- interface enforcement notes

Out of scope:

- mass-moving existing channel trees without audit
- changing `lupo_edges` authority model
- treating filesystem edges as DB replacements

## Active Thread

- `threads/channel_refactor_4_0_88/`

This thread is the coordination hub for the 4.0.88 refactor initiative and its 4.1.0 carryover planning.