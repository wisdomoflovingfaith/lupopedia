---
lupopedia.headers:
  lupopedia.schema: doctrine
  file_path_from_root: "lupo-docs/doctrine/ACTOR_TEMPLATE_MODEL.md"
  web_path: "http://www.lupopedia.com/lupopedia/doctrine/ACTOR_TEMPLATE_MODEL.md"
  last_modified_utc: "20260330"
  channel_id: 42
  actor_id: 102
  actor_name: "cursor"
  delegation_chain: "cursor:root"
  artifact_type: "doctrine"
  artifact_kind: "identity_model"
  purpose: "Defines the agent template model for actor instantiation in Lupopedia."
  tags: ["doctrine", "identity", "actor_template", "agent", "model"]
lupopedia.edges:
  outbound_edges:
    - { to: "ACTOR_INSTANCE_MODEL.md", type: "references", weight: 1.0 }
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

# ACTOR TEMPLATE MODEL

## Purpose
Defines the canonical structure and doctrine for agent templates (lupo_actor_templates) used to instantiate actors in Lupopedia.

## Key Concepts
- Agents are autonomous AI entities, not tied to actors or users.
- lupo_actor_templates defines the blueprint for actor instantiation.
- Each template references an agent and a version.

## Table: lupo_actor_templates
- template_id (BIGINT, IdGenerator)
- agent_id (BIGINT)
- template_version (string)
- created_ymdhis (BIGINT)
- is_deleted (TINYINT)
- deleted_ymdhis (BIGINT)

## Workflow
1. Agent is defined and trained.
2. Template is created referencing the agent.
3. Actor instances are created from templates.

---
