---
lupopedia.headers:
  when_updated: '20260324230000'
  lupopedia.schema: documentation
  file_path_from_root: lupo-docs/versions/4.0.87/EDGE_REVIEW_QUEUE.md
  web_path: http://www.lupopedia.com/lupo-docs/versions/4.0.87/EDGE_REVIEW_QUEUE.md
  last_modified_utc: '20260324230000'
  channel_id: 64
  actor_id: 102
  actor_name: cursor
  delegation_chain: cursor:root
  artifact_type: plan
  artifact_kind: edge_review_queue
  purpose: Actor-owned queue for edge review and validation before 4.0.87 lock
  tags:
  - edges
  - actors
  - queue
  - 4.0.87
lupopedia.edges:
  comment: Snapshot of edge-review queue references at artifact creation time.
  outbound_edges:
  - to: lupo-docs/versions/4.0.87/PLAN.md
    type: references
    weight: 1.0
  - to: lupo-docs/versions/4.0.87/DOCUMENTATION_AND_EDGES_STREAM.md
    type: references
    weight: 1.0
  - to: lupo-channels/64/threads/edge_generation_governance/20260324_150925_channel_thread_edge_map_api_update.md
    type: references
    weight: 0.95
  - to: lupo-channels/66/threads/1051/20260324_182100_cursor_question_edge_review_assignments.md
    type: references
    weight: 0.95
  semantic_tags:
  - edge_governance
  - actor_assignment
  - release_gate
lupopedia.footer:
  last_verified: '20260324230000'
  last_verified_by: cursor
  last_verified_by_actor_id: 102
  orchestrator: wolfie:root
  next_action:
  - Route ERQ-006 to WOLFIE via channel 66 for final release signoff
---
# file: edge review queue 4.0.87 - delegation: cursor:root - web_path: http://www.lupopedia.com/lupo-docs/versions/4.0.87/EDGE_REVIEW_QUEUE.md

# 4.0.87 Edge Review Queue

## Queue Items by Actor

| Queue ID | Edge Area | Owner Actor | Channel | Priority | Status |
|---|---|---|---|---|---|
| ERQ-001 | Edge type seed integrity (`lupo_edge_types`) | HEPHAESTUS | 64 | P0 | **closed** — 12 rows verified |
| ERQ-002 | Edge definition consistency (`lupo_edge_type_definitions`) | HEPHAESTUS | 64 | P0 | **closed** — 12 rows verified |
| ERQ-003 | Query semantics + traversal constraints | ATHENA | 64 | P1 | open |
| ERQ-004 | Docs and traceability alignment across edge artifacts | THOTH | 63/64 | P1 | open |
| ERQ-005 | Adversarial contradiction scan on edge doctrine/docs | LILITH | 66 | P1 | open |
| ERQ-006 | Final orchestration release signoff | WOLFIE | 66 | P0 | **pending** — all P0 implementation done; awaiting WOLFIE sign-off |

## Blocking Rule

`ERQ-006` (WOLFIE release signoff) must be closed before 4.0.87 release closeout. ERQ-001 and ERQ-002 are **closed**.

## Verification Evidence

| ERQ | Evidence |
|-----|----------|
| ERQ-001 | `SELECT COUNT(*) FROM lupo_edge_types WHERE is_deleted=0` → **12** (verified 20260324 23:00 UTC) |
| ERQ-002 | `SELECT COUNT(*) FROM lupo_edge_type_definitions` → **12** (verified 20260324 23:00 UTC) |
| ERQ-006 | **Pending** — WOLFIE release signoff via channel 66 (no closure artifact until signoff) |

