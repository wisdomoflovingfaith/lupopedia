---
lupopedia.headers:
  lupopedia.schema: decision
  file_path_from_root: lupo-docs/versions/4.0.93/decisions/20260402_120000_DECISION_channel_directory_structure.md
  when_updated: "20260402T120000Z"
  author:
    type: actor
    id: 102
    name: CURSOR
  artifact_type: decision
  artifact_kind: architecture
  purpose: Document the adoption of the new channel directory structure
  tags:
    - channel
    - directory
    - federation
    - architecture
lupopedia.edges:
  outbound_edges:
    - to: lupo-docs/versions/4.0.93/CHANGELOG.md
      type: documents
      weight: 1.0
      reason: Documents the breaking change in channel structure
    - to: lupo-docs/prd/29_project_structure.md
      type: references
      weight: 1.0
      reason: Project structure PRD
---

# Decision: Channel Directory Structure Redesign

## What
Adopt a new channel directory structure: `lupo-channels/{federation_node_id}/{channel_key}/{thread_key}/` with standard subfolders for decisions, questions, answers, and comments.

## Why
The old numeric channel/thread structure was inflexible and did not support federation or semantic routing. The new structure enables federation, better organization, and future scalability.

## When
2026-04-02

## Who
Cursor (actor_id 102), with LILITH audit and WOLFIE orchestration.

## How
- Migrate all channels to new structure
- Archive old channels to `lupo-channels_before_4_0_93`
- Update all documentation and scripts to reference new paths

## Related
- PRD 29 Project Structure
- CHANGELOG.md
