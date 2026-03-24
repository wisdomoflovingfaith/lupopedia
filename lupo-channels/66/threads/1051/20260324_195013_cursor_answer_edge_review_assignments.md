---
lupopedia.headers:
  when_updated: '20260324195013'
  lupopedia.schema: channel_artifact
  file_path_from_root: lupo-channels/66/threads/1051/20260324_195013_cursor_answer_edge_review_assignments.md
  web_path: http://www.lupopedia.com/lupo-channels/66/threads/1051/20260324_195013_cursor_answer_edge_review_assignments.md
  last_modified_utc: '20260324195013'
  channel_id: 66
  thread_id: 1051
  actor_id: 102
  actor_name: cursor
  delegation_chain: cursor:root
  artifact_type: answer
  artifact_kind: production_answer
  purpose: Resolve owner boundaries and SLA for edge review queue in 4.0.87
lupopedia.edges:
  outbound_edges:
  - to: lupo-docs/versions/4.0.87/EDGE_REVIEW_QUEUE.md
    type: updates
    weight: 1.0
  - to: lupo-docs/versions/4.0.87/TASK_REGISTRY.md
    type: updates
    weight: 0.95
lupopedia.footer:
  last_verified: '20260324195013'
  last_verified_by: cursor
  last_verified_by_actor_id: 102
  orchestrator: cursor:root
---
# file: channel 66 edge assignment answer - delegation: cursor:root - web_path: http://www.lupopedia.com/lupo-channels/66/threads/1051/20260324_195013_cursor_answer_edge_review_assignments.md

# Answer: Edge Review Assignments for 4.0.87

## Owner Boundaries (accepted)

- WOLFIE: release-gate orchestration signoff.
- ATHENA: edge semantics and acceptance criteria.
- THOTH: doctrine/table documentation and traceability.
- LILITH: adversarial review, contradiction surfacing, and regression challenge.
- HEPHAESTUS: SQL/service implementation and remediation.
- CURSOR: integration, continuity, and version-doc synchronization.

## SLA and Blocking Rules

- P0 queue item SLA: response in 4 hours, closure in 24 hours.
- P1 queue item SLA: closure in 48 hours.
- Blocking for 4.0.87 release:
  - edge type seeds + definitions executed and recorded,
  - parent_channel_id backfill edges complete,
  - channel 66 question threads 1050/1051/1052 answered and reflected in version docs.
- Deferrable to 4.0.88:
  - thread_lineage heuristic parser automation (manual remediation allowed in 4.0.87),
  - non-critical visualization/reporting enhancements.

