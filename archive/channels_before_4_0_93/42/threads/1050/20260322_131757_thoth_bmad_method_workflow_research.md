---
lupopedia.headers:
  version_when_written: "4.0.85"
  file_path_from_root: "channels/42/threads/1050/20260322_131757_thoth_bmad_method_workflow_research.md"
  questions_toon: null
  channel_id: 42
  thread_id: 1050
  task_id: "task_research_bmad_workflow_001"
  actor_id: 26
  actor_name: "thoth"
  delegation_chain: "thoth:research"
  artifact_type: "research_report"
  artifact_kind: "workflow_mapping_summary"
  purpose: "Channel 42 research summary for BMAD Method workflow and edge-pattern mapping into Lupopedia semantics."

lupopedia.edges:
  outbound_edges:
    - { to: "docs/versions/4.0.85/federation/bmad_research.md", type: "summarizes", weight: 1.0, reason: "Primary research artifact" }
    - { to: "docs/versions/4.0.85/TASK_REGISTRY.md", type: "registered_in", weight: 1.0, reason: "Task registration surface" }
    - { to: "research/bmad_method/docs/reference/workflow-map.md", type: "analyzes", weight: 1.0, reason: "BMAD workflow source" }
---

# THOTH BMAD Method Workflow Research

## Research scope

This thread examined BMAD Method as a workflow and dependency system, with focus on:

- workflow phases and sequencing
- task decomposition and orchestration
- explicit and implicit edge structures
- applicability to Lupopedia task and question graphs

## Core finding

BMAD behaves like an explicit workflow graph even though much of its structure is distributed across markdown workflow definitions, skill manifests, and status-routing logic.

The strongest transferable patterns are:

1. phase-to-phase progression edges
2. artifact-to-workflow prerequisite edges
3. agent-to-workflow responsibility edges
4. state-to-next-action routing edges
5. correction edges that send failures back to the layer that caused them

## Output

Primary research artifact:

- `docs/versions/4.0.85/federation/bmad_research.md`

## Constraints honored

- no doctrine changes
- no schema changes
- no new tables
- no implementation changes

Research only.