---
lupopedia.headers:
  lupopedia.schema: decision
  file_path_from_root: lupo-docs/versions/4.0.93/decisions/20260402_130000_DECISION_decisions_folder_separation.md
  when_updated: "20260402T130000Z"
  author:
    type: actor
    id: 102
    name: CURSOR
  artifact_type: decision
  artifact_kind: architecture
  purpose: Document the migration from monolithic decisions.md to separated folders
  tags:
    - decisions
    - folder
    - architecture
lupopedia.edges:
  outbound_edges:
    - to: lupo-docs/versions/4.0.93/CHANGELOG.md
      type: documents
      weight: 1.0
      reason: Documents the change in decision storage
    - to: lupo-docs/prd/26_five_layer_documentation_architecture.md
      type: references
      weight: 1.0
      reason: Five-layer documentation PRD
---

# Decision: Decisions Folder Separation

## What
Replace monolithic `decisions.md` with `decisions/`, `questions/`, `answers/`, and `comments/` folders, each containing timestamped files.

## Why
Improves organization, enables threading, and supports semantic linking and audit trails.

## When
2026-04-02

## Who
Cursor (actor_id 102), with LILITH audit and WOLFIE orchestration.

## How
- Migrate all existing decisions to new folder structure
- Update documentation and scripts

## Related
- PRD 26 Five-Layer Documentation Architecture
- CHANGELOG.md
