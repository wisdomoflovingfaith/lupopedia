---
lupopedia.headers:
  lupopedia.schema: doctrine
  file_path_from_root: "lupo-docs/doctrine/ACTOR_LEASE_SESSION_MODEL.md"
  web_path: "http://www.lupopedia.com/lupopedia/doctrine/ACTOR_LEASE_SESSION_MODEL.md"
  last_modified_utc: "20260330"
  channel_id: 42
  actor_id: 102
  actor_name: "cursor"
  delegation_chain: "cursor:root"
  artifact_type: "doctrine"
  artifact_kind: "identity_model"
  purpose: "Defines the lease session model for actor control in Lupopedia."
  tags: ["doctrine", "identity", "lease_session", "model"]
lupopedia.edges:
  outbound_edges:
    - { to: "ACTOR_INSTANCE_MODEL.md", type: "references", weight: 1.0 }
    - { to: "ACTOR_TEMPLATE_MODEL.md", type: "references", weight: 1.0 }
lupopedia.footer:
  last_verified: "20260330"
  last_verified_by: "cursor"
  orchestrator: "cursor"
---

# ACTOR LEASE SESSION MODEL

## Purpose
Defines the canonical structure and doctrine for lease sessions (lupo_actor_lease_sessions) that track which auth user is controlling an actor.

## Key Concepts
- Auth users lease actors temporarily; only one user may control an actor at a time.
- Leasing is permission-gated (see PRD for rules).
- All leasing is tracked for audit and security.

## Table: lupo_actor_lease_sessions
- lease_id (BIGINT, IdGenerator)
- actor_id (BIGINT)
- auth_user_id (BIGINT)
- department_id (BIGINT, copied from actor at lease time)
- started_ymdhis (BIGINT)
- ended_ymdhis (BIGINT)
- is_active (TINYINT)

## Workflow
1. Auth user requests lease on actor.
2. Permission rule is checked.
3. Lease session is created if permitted.
4. Lease ends; actor becomes available for next user.

---
