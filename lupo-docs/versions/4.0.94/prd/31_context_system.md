---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: prd
  when_updated: "20260402180000"
  file_path_from_root: "lupo-docs/versions/4.0.94/prd/31_context_system.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/versions/4.0.94/prd/31_context_system.md"
  last_modified_utc: "20260402180000"
  federation_node_id: 0
  channel_id: 42
  thread_id: "prd-context-system-redesign"
  prd_id: 31
  prd_slug: context_system
  title: "Context system (redesign stub)"
  status: "draft"
  author:
    type: "actor"
    id: 102
    name: "CURSOR"
  delegation_chain: "cursor:root"
  artifact_type: "prd"
  artifact_kind: "specification"
  purpose: "Redesign space for context handling without parallel classification; must align with PRD 26 and edges.md"
  tags:
  - "prd"
  - "context"
  - "4.0.94"
lupopedia.edges:
  outbound_edges:
    - to: "lupo-docs/prd/00_root_constitutional_system_requirements.md"
      type: references
      weight: 1.0
      reason: "Constitutional anchor"
    - to: "lupo-docs/prd/26_five_layer_documentation_architecture.md"
      type: references
      weight: 1.0
      reason: "No parallel taxonomy; WHERE is edges"
    - to: "lupo-docs/versions/4.0.93/decisions/20260402_210000_DECISION_prd31_rejection_parallel_classification.md"
      type: references
      weight: 1.0
      reason: "Rejection rationale for prior approach"
    - to: "lupo-docs/versions/4.0.93/decisions/20260402_220000_DECISION_context_system_rejection.md"
      type: references
      weight: 1.0
      reason: "Architectural constraints"
lupopedia.footer:
  last_verified: "20260402180000"
  verified_by:
    identity_type: "actor"
    actor_id: 102
  orchestrator: "cursor:root"
---

# file: lupo-docs/versions/4.0.94/prd/31_context_system.md — delegation: cursor:root

# PRD 31: Context system (redesign — stub)

## Status

This file is a **placeholder** for 4.0.94 work. The prior PRD 31 direction was **rejected** in 4.0.93 because it introduced a **parallel classification system** conflicting with PRD 26 (five-layer architecture, especially WHERE via `edges.md`).

## Non-goals

- New folder taxonomies that duplicate `lupopedia.edges` or PRD 26 layers
- Database tables for arbitrary “context types” without constitutional review

## Next steps (for authors)

1. Read `lupo-docs/versions/4.0.93/decisions/20260402_210000_DECISION_prd31_rejection_parallel_classification.md`
2. Define minimal, doctrine-aligned behavior using existing headers, tags, and edges
3. When ready for review, update header `status` and open a decision thread under `lupo-docs/versions/4.0.94/decisions/`
