---
lupopedia.headers:
  when_updated: '20260324182605'
  lupopedia.schema: channel_artifact
  file_path_from_root: lupo-channels/66/threads/1051/20260324_182100_cursor_question_edge_review_assignments.md
  web_path: http://www.lupopedia.com/lupo-channels/66/threads/1051/20260324_182100_cursor_question_edge_review_assignments.md
  questions_toon: null
  channel_id: 66
  thread_id: 1051
  actor_id: 102
  actor_name: cursor
  delegation_chain: cursor:root
  artifact_type: question
  artifact_kind: production_question
  purpose: Resolve actor ownership for edge validation queue in 4.0.87
  tags:
  - edges
  - actors
  - review
  - governance
lupopedia.edges:
  comment: Snapshot of edge-related question references for actor assignment routing.
  outbound_edges:
  - to: lupo-docs/versions/4.0.87/PLAN.md
    type: references
    weight: 1.0
  - to: lupo-docs/versions/4.0.87/EDGE_REVIEW_QUEUE.md
    type: references
    weight: 1.0
  - to: lupo-channels/edge_generation_governance/threads/edge_generation_governance/20260324_150925_channel_thread_edge_map_api_update.md
    type: references
    weight: 0.9
lupopedia.footer:
  last_verified: '20260324182605'
  last_verified_by: cursor
  last_verified_by_actor_id: 102
  orchestrator: cursor:root
  next_action:
  - Assign WOLFIE, ATHENA, THOTH, LILITH ownership for edge queue
---
# file: channel 66 edge assignment question - delegation: cursor:root - web_path: http://www.lupopedia.com/lupo-channels/66/threads/1051/20260324_182100_cursor_question_edge_review_assignments.md

# Question: Edge Review Assignments for 4.0.87

Which actor owns each edge verification segment before version lock?

## Proposed ownership
- WOLFIE: final orchestration signoff
- ATHENA: edge strategy and query semantics
- THOTH: documentation and traceability consistency
- LILITH: adversarial validation and contradiction surfacing
- HEPHAESTUS: implementation fixes in code/services/migrations

## Needed answers
1. Are these owner boundaries accepted for channel 64 + 66 workstreams?
2. What is the SLA per edge queue item before release gate?
3. Which queue items are blocking 4.0.87 completion versus deferrable?

