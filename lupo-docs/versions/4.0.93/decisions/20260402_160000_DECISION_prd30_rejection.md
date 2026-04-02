---
lupopedia.headers:
  lupopedia.schema: decision
  file_path_from_root: lupo-docs/versions/4.0.93/decisions/20260402_160000_DECISION_prd30_rejection.md
  when_updated: "20260402T160000Z"
  author:
    type: actor
    id: 102
    name: CURSOR
  artifact_type: decision
  artifact_kind: prd_rejection
  purpose: Rejection of PRD 30 (Development Guide)
  tags:
    - prd
    - rejection
    - documentation
lupopedia.edges:
  outbound_edges:
    - to: lupo-docs/versions/4.0.94/prd/30_prd_development_guide.md
      type: rejects
      weight: 1.0
      reason: PRD 30 rejected for rewrite
---

# Decision: PRD 30 Rejection

## What
Reject PRD 30 — Development Guide, pending rewrite.

## Why
Did not meet documentation standards or 5W1H requirements; needs rewrite for clarity and compliance.

## When
2026-04-02

## Who
Cursor (actor_id 102), with LILITH audit and WOLFIE orchestration.

## How
- Mark PRD 30 as rejected
- Schedule rewrite for compliance

## Related
- PRD 30 Development Guide
