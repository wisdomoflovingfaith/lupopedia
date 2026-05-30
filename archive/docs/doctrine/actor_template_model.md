---
lupopedia.headers:
  header_format_version: "4.1.0"
  file_path_from_root: "docs/doctrine/ACTOR_TEMPLATE_MODEL.md"
  web_path: "http://www.lupopedia.com/lupopedia/doctrine/ACTOR_TEMPLATE_MODEL.md"
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
