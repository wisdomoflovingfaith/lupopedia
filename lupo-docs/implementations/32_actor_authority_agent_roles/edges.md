---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: implementation
  when_updated: "20260403110451"
  file_path_from_root: "lupo-docs/implementations/32_actor_authority_agent_roles/edges.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/implementations/32_actor_authority_agent_roles/edges.md"
  federation_node_id: 0
  channel_id: 42
  thread_id: "32-actor-authority-edges"
  actor_id: 102
  delegation_chain: "cursor:root"
  artifact_type: "implementation"
  artifact_kind: "edges"
  purpose: "Doctrine traceability for PRD 32 actor authority and agent roles"
  parent_prd: "32_actor_authority_agent_roles"
  tags:
    - "implementation"
    - "edges"
    - "actors"
lupopedia.edges:
  outbound_edges:
    - to: "lupo-docs/prd/32_actor_authority_agent_roles.md"
      type: implements
      weight: 1.0
      reason: "Parent PRD"
---

# Edges — PRD 32 actor authority and agent roles

## Outbound Edges — Doctrine (documents)

| Target | Type | Weight | Reason |
|--------|------|--------|--------|
| [`../../doctrine/ACTOR_AGENT_DISTINCTION.md`](../../doctrine/ACTOR_AGENT_DISTINCTION.md) | documents | 1.0 | Actor vs agent distinction |
| [`../../doctrine/IDENTITY_LAYERS_DOCTRINE.md`](../../doctrine/IDENTITY_LAYERS_DOCTRINE.md) | documents | 1.0 | Identity layers (canonical) |
| [`../../doctrine/AGENT_REGISTRY.md`](../../doctrine/AGENT_REGISTRY.md) | documents | 1.0 | Agent registry reference |

Many additional actor/identity doctrine files are classified to PRD 32. See the full set in [`../29_project_structure/status/doctrine_prd_lineage.json`](../29_project_structure/status/doctrine_prd_lineage.json) (`by_prd["32"]`).

---

This file complies with Lupopedia Constitutional Root Rules.
