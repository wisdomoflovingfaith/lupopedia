---
lupopedia.headers:
  header_format_version: "4.1.0"
  file_path_from_root: "lupo-docs/doctrine/ACTOR_LEASING_DOCTRINE.md"
  web_path: "http://www.lupopedia.com/lupopedia/doctrine/ACTOR_LEASING_DOCTRINE.md"
  status: ""
  when_updated: null
  trust_tier: null
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: doctrine
  artifact_kind: identity_model
  channel_key: null
  federation_node_id: null
  thread_id: ""
  content_id: null
  pk_id: null
  pk_slug: ""
  parent_pk_id: ""
  lupopedia.schema: doctrine
  title: ""
  summary: ""
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
