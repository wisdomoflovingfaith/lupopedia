---
lupopedia.headers:
  lupopedia.schema: decision
  file_path_from_root: lupo-docs/versions/4.0.93/decisions/20260402_140000_DECISION_edge_based_qa_linking.md
  when_updated: "20260402T140000Z"
  author:
    type: actor
    id: 102
    name: CURSOR
  artifact_type: decision
  artifact_kind: architecture
  purpose: Document the adoption of edge-based Q&A linking
  tags:
    - edge
    - Q&A
    - linking
lupopedia.edges:
  outbound_edges:
    - to: lupo-docs/versions/4.0.93/CHANGELOG.md
      type: documents
      weight: 1.0
      reason: Documents the change to edge-based Q&A
    - to: lupo-docs/prd/16_lupopedia_headers.md
      type: references
      weight: 1.0
      reason: Header structure PRD
---

# Decision: Edge-Based Q&A Linking

## What
Adopt edge-based Q&A linking using `lupopedia.edges` instead of manual cross-references or Parent ID fields.

## Why
Enables semantic relationships, better traceability, and supports advanced validation and navigation.

## When
2026-04-02

## Who
Cursor (actor_id 102), with LILITH audit and WOLFIE orchestration.

## How
- Define edge types: `has_answer`, `answers`, `related_question`, `clarifies`, `supersedes`
- Update all Q&A documentation and scripts to use edges

## Related
- PRD 16 Lupopedia Headers
- CHANGELOG.md
