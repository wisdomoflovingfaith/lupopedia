---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: implementation
  when_updated: "20260403125325"
  file_path_from_root: "lupo-docs/implementations/34_federation_node_semantic_network/edges.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/implementations/34_federation_node_semantic_network/edges.md"
  federation_node_id: 0
  channel_id: 42
  thread_id: "34-federation-semantic-network-edges"
  actor_id: 102
  delegation_chain: "cursor:root"
  artifact_type: implementation
  artifact_kind: edges
  purpose: "Cross-links for PRD 34 implementation workspace"
  parent_prd: "34_federation_node_semantic_network"
  tags:
    - implementation
    - edges
    - federation
lupopedia.edges:
  outbound_edges:
    - to: "lupo-docs/prd/34_federation_node_semantic_network.md"
      type: implements
      weight: 1.0
      reason: "Parent PRD"
    - to: "lupo-docs/doctrine/REVERSE_ENGINEERING_DOCTRINE.md"
      type: references
      weight: 1.0
      reason: "Federation node dual purpose; research vs peers"
    - to: "lupo-docs/doctrine/SILENT_HARVEST_DOCTRINE.md"
      type: references
      weight: 1.0
      reason: "Path/visit analytics foundation; navigation compiler inputs"
    - to: "lupo-docs/doctrine/CRAFTY_NODE_REACTIVATION_STRATEGY.md"
      type: references
      weight: 1.0
      reason: "Scale narrative and dormant reactivation order"
---

# Edges — PRD 34 federation node semantic network

| Target | Type | Notes |
|--------|------|--------|
| [PRD 34](../../prd/34_federation_node_semantic_network.md) | implements | Parent requirements |
| [REVERSE_ENGINEERING_DOCTRINE](../../doctrine/REVERSE_ENGINEERING_DOCTRINE.md) | references | Sandbox vs semantic network |
| [SILENT_HARVEST_DOCTRINE](../../doctrine/SILENT_HARVEST_DOCTRINE.md) | references | Visits/paths foundation; disclosure |
| [CRAFTY_NODE_REACTIVATION_STRATEGY](../../doctrine/CRAFTY_NODE_REACTIVATION_STRATEGY.md) | references | Dormant Crafty → Lupopedia |
| [install SQL](../../../lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql) | references | Current federation DDL |

This file complies with Lupopedia Constitutional Root Rules.
