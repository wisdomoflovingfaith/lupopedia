---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/doctrine/actor_instance_model.md
  web_path: https://www.lupopedia.com/lupopedia/docs/doctrine/actor_instance_model.md
  status: ''
  when_updated: '20260513033046'
  trust_tier: null
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: doctrine
  artifact_kind: identity_model
  channel_key: null
  federation_node_id: null
  thread_key: ''
  lupopedia.schema: doctrine
  prd_cluster: null
  title: ''
  summary: ''
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
