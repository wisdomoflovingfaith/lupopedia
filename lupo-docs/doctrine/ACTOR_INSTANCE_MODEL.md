---
lupopedia.headers:
  lupopedia.schema: doctrine
  file_path_from_root: "lupo-docs/doctrine/ACTOR_INSTANCE_MODEL.md"
  web_path: "http://www.lupopedia.com/lupopedia/doctrine/ACTOR_INSTANCE_MODEL.md"
  last_modified_utc: "20260330"
  channel_id: 42
  actor_id: 102
  actor_name: "cursor"
  delegation_chain: "cursor:root"
  artifact_type: "doctrine"
  artifact_kind: "identity_model"
  purpose: "Defines the actor instance model for hybrid shells in Lupopedia."
  tags: ["doctrine", "identity", "actor_instance", "model"]
lupopedia.edges:
  outbound_edges:
    - { to: "ACTOR_TEMPLATE_MODEL.md", type: "references", weight: 1.0 }
    - { to: "ACTOR_LEASE_SESSION_MODEL.md", type: "references", weight: 1.0 }
    - to: "lupo-docs/prd/32_actor_authority_agent_roles.md"
      type: implements
      weight: 1.0
      reason: "Doctrine PRD lineage; constitutional audit 20260403"

lupopedia.footer:
  last_verified: "20260330"
  last_verified_by: "cursor"
  orchestrator: "cursor"
---

# ACTOR INSTANCE MODEL

## Purpose
Defines the canonical structure and doctrine for actor instances (lupo_actor_instances) as hybrid human/AI shells in Lupopedia.

## Key Concepts
- Actors are instantiated from agent templates.
- Actors are department-scoped, not tied to a single human.
- Actors are shaped by human usage and can be leased by auth users.

## Table: lupo_actor_instances
- actor_id (BIGINT, IdGenerator)
- template_id (BIGINT)
- created_by_auth_user_id (BIGINT)
- department_id (BIGINT)
- created_ymdhis (BIGINT)
- is_available (TINYINT)
- is_deleted (TINYINT)
- deleted_ymdhis (BIGINT)

## Workflow
1. Actor instance is created from a template.
2. Department assigns actor to pool.
3. Auth users may lease actors if permission rule is satisfied.

---
