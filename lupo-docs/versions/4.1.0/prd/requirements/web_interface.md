---
lupopedia.headers:
  lupopedia.schema: "requirements"
  file_path_from_root: "lupo-docs/versions/4.1.0/prd/requirements/web_interface.md"
  last_modified_utc: "20260326"
  system_version: "4.1.0"
  channel_id: 42
  actor_id: 1
  actor_name: "wolfie"
  delegation_chain: "wolfie:root"
  artifact_type: "prd"
  artifact_kind: "web_requirements"
  purpose: "Web interface requirements for 4.1.0"
lupopedia.edges:
  outbound_edges:
    - { to: "lupo-docs/versions/4.1.0/prd/README.md", type: "references", weight: 1.0 }
lupopedia.footer:
  version: "4.1.0"
  last_verified: "20260326"
  last_verified_by: "wolfie"
  orchestrator: "wolfie"
  approved_for_release: "4.1.0"
  approval_status: "pending"
  approved_by_actor_id: 0
  approved_utc: 0
  next_action:
    - "Lock minimum stable UI surface for chat and admin"
---

# Web Interface Requirements

## Required Stable Surface

1. Chat entry and operator availability indicators MUST function.
2. Admin panel MUST support core configuration and operational checks.
3. User/session flows MUST be deterministic and recoverable.
4. Channel/thread UI MUST remain stable, predictable, and non-experimental.
5. Actor routing UI exposure MUST remain minimal viable and operationally clear.

## Out of Scope for 4.1.0

- Experimental UI frameworks
- High-complexity federation UX
- Half-finished orchestration consoles
