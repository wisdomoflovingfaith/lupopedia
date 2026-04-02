---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: documentation
  when_updated: "20260402180000"
  file_path_from_root: "lupo-docs/versions/4.0.94/edges.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/versions/4.0.94/edges.md"
  last_modified_utc: "20260402180000"
  federation_node_id: 0
  channel_id: 42
  thread_id: "version-4.0.94-edges"
  author:
    type: "actor"
    id: 102
    name: "CURSOR"
  delegation_chain: "cursor:root"
  artifact_type: "documentation"
  artifact_kind: "edges"
  purpose: "Relationships between 4.0.94 version docs and frozen 4.0.93 baseline"
  tags:
  - "edges"
  - "4.0.94"
lupopedia.edges:
  outbound_edges:
    - to: "/lupo-docs/versions/4.0.93/README.md"
      type: references
      weight: 1.0
      reason: "Frozen prior release"
    - to: "/lupo-docs/versions/4.0.93/edges.md"
      type: references
      weight: 1.0
      reason: "Frozen documentation graph"
    - to: "/lupo-docs/versions/4.0.94/prd/30_prd_development_guide.md"
      type: references
      weight: 0.95
      reason: "PRD 30 rewrite workspace"
    - to: "/lupo-docs/versions/4.0.94/prd/31_context_system.md"
      type: references
      weight: 0.95
      reason: "PRD 31 redesign workspace"
    - to: "/lupo-docs/prd/26_five_layer_documentation_architecture.md"
      type: references
      weight: 1.0
      reason: "Architecture PRD 31 must not contradict"
lupopedia.footer:
  last_verified: "20260402180000"
  verified_by:
    identity_type: "actor"
    actor_id: 102
  orchestrator: "cursor:root"
---

# file: lupo-docs/versions/4.0.94/edges.md — delegation: cursor:root

# Version 4.0.94 documentation edges

| From | To | Type |
|------|-----|------|
| This version | `4.0.93/README.md`, `4.0.93/edges.md` | baseline |
| `prd/30` | PRD 16, 17, 26, `5W1H_QUICK_REFERENCE.md` | references (update as rewrite proceeds) |
| `prd/31` | PRD 26, `DOCUMENTATION_ARCHITECTURE.md` | must align |

Update this file whenever a new thread file or PRD section creates a durable cross-link.
