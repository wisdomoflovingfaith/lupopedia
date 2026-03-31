---
lupopedia.headers:
  lupopedia.schema: doctrine
  file_path_from_root: "lupo-docs/doctrine/ACTOR_LEASING_DOCTRINE.md"
  web_path: "http://www.lupopedia.com/lupopedia/doctrine/ACTOR_LEASING_DOCTRINE.md"
  last_modified_utc: "20260330"
  channel_id: 42
  actor_id: 102
  actor_name: "cursor"
  delegation_chain: "cursor:root"
  artifact_type: "doctrine"
  artifact_kind: "identity_model"
  purpose: "Doctrine for actor leasing, template inheritance, and session control in Lupopedia."
  tags: ["doctrine", "identity", "actor_leasing", "template", "session", "department"]
lupopedia.edges:
  outbound_edges:
    - { to: "ACTOR_TEMPLATE_MODEL.md", type: "references", weight: 1.0 }
    - { to: "ACTOR_INSTANCE_MODEL.md", type: "references", weight: 1.0 }
    - { to: "ACTOR_LEASE_SESSION_MODEL.md", type: "references", weight: 1.0 }
lupopedia.footer:
  last_verified: "20260330"
  last_verified_by: "cursor"
  orchestrator: "cursor"
---

# ACTOR LEASING DOCTRINE

## Purpose
Defines the canonical model for actor leasing, template inheritance, and session control in Lupopedia 4.0.93+.

## Key Principles
- Agents are templates; actors are instantiated from agents.
- Actors are reusable shells, not tied to a single human.
- Auth users lease actors temporarily; only one user may control an actor at a time.
- Departments control actor leasing; pools define which users may lease which actors.
- All leasing is tracked in lupo_actor_lease_sessions for audit and session control.

## Core Tables
- lupo_actor_templates
- lupo_actor_instances
- lupo_actor_lease_sessions
- lupo_department_actor_pools

## Workflow
1. Agent (template) is defined.
2. Actor instance is created from template.
3. Department assigns actor to pool.
4. Auth user requests lease; if permitted, lease session is created.
5. Lease session controls operational identity, permissions, and audit.
6. Lease ends; actor becomes available for next user.

## Audit and Security
- All leases are auditable by department, actor, and user.
- No permanent mapping between auth_user and actor.
- Legacy lupo_actor_auth_users is deprecated.

---
