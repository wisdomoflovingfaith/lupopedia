---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: implementation
  when_updated: "20260403110451"
  file_path_from_root: "lupo-docs/implementations/28_semantic_monitoring_widget/edges.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/implementations/28_semantic_monitoring_widget/edges.md"
  federation_node_id: 0
  channel_id: 42
  thread_id: "28-semantic-monitoring-edges"
  actor_id: 102
  delegation_chain: "cursor:root"
  artifact_type: "implementation"
  artifact_kind: "edges"
  purpose: "Doctrine traceability for PRD 28 semantic monitoring / DynAPI layer"
  parent_prd: "28_semantic_monitoring_widget"
  tags:
    - "implementation"
    - "edges"
    - "dynapi"
lupopedia.edges:
  outbound_edges:
    - to: "lupo-docs/prd/28_semantic_monitoring_widget.md"
      type: implements
      weight: 1.0
      reason: "Parent PRD"
    - to: "lupo-docs/doctrine/DYNAPI_DOCTRINE.md"
      type: documents
      weight: 1.0
      reason: "DynAPI library doctrine; PRD 28 DHTML layer"
---

# Edges — PRD 28 semantic monitoring widget

## Outbound Edges — Doctrine (documents)

| Target | Type | Weight | Reason |
|--------|------|--------|--------|
| [`../../doctrine/DYNAPI_DOCTRINE.md`](../../doctrine/DYNAPI_DOCTRINE.md) | documents | 1.0 | DynAPI / DHTML layer (also cited in PRD 33) |
| [`../../doctrine/GC_DOCTRINE.md`](../../doctrine/GC_DOCTRINE.md) | documents | 0.95 | GC / monitoring alignment |
| [`../../doctrine/MOOD_RGB_DOCTRINE.md`](../../doctrine/MOOD_RGB_DOCTRINE.md) | documents | 1.0 | Mood RGB semantic channel |
| [`../../doctrine/MULTI_AGENT_5W1H_DOCTRINE.md`](../../doctrine/MULTI_AGENT_5W1H_DOCTRINE.md) | documents | 1.0 | 5W1H monitoring layer |

Full list: [`../29_project_structure/status/doctrine_prd_lineage.json`](../29_project_structure/status/doctrine_prd_lineage.json) (`by_prd["28"]`).

---

This file complies with Lupopedia Constitutional Root Rules.
