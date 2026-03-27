---
lupopedia.headers:
  lupopedia.version: "4.0.88"
  lupopedia.schema: "prd"
  file_path_from_root: "lupo-docs/versions/4.0.88/CHANNEL_REFACTOR_PRD.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/versions/4.0.88/CHANNEL_REFACTOR_PRD.md"
  last_modified_utc: "20260327110206"
  system_version: "4.0.88"
  channel_id: 65
  thread_id: "channel_refactor_4_0_88"
  actor_id: 102
  actor_name: "cursor"
  delegation_chain: "cursor:root"
  artifact_type: "prd"
  artifact_kind: "channel_refactor_governance"
  purpose: "4.0.88 PRD for channel refactor governance, phased migration, edge integrity, and interface enforcement"
  tags: ["4.0.88", "prd", "channels", "questions", "prompts", "edge_integrity", "migration"]

lupopedia.edges:
  outbound_edges:
    - { to: "lupo-docs/doctrine/CHANNEL_BASED_COORDINATION_DOCTRINE.md", type: "extends", weight: 1.0 }
    - { to: "lupo-docs/doctrine/EDGE_MODEL_DOCTRINE.md", type: "depends_on", weight: 1.0 }
    - { to: "lupo-docs/doctrine/HEADER_DB_REVERSIBILITY_DOCTRINE.md", type: "depends_on", weight: 0.95 }
    - { to: "lupo-docs/doctrine/IDENTITY_LAYERS_DOCTRINE.md", type: "depends_on", weight: 0.95 }
    - { to: "lupo-channels/1_channel_refactor_governance/README.md", type: "implements", weight: 1.0 }
    - { to: "lupo-channels/1_channel_refactor_governance/threads/channel_refactor_4_0_88/20260327_110206_cursor_channel_refactor_audit_report.md", type: "references", weight: 1.0 }
    - { to: "PLAN.md", type: "extends", weight: 1.0 }
    - { to: "TODO.md", type: "generates", weight: 1.0 }

lupopedia.footer:
  approval_status: "pending"
  approval_target_version: "4.1.0"
  approval_status_utc: "20260327110206"
  approval_status_by: "Cursor IDE Agent (Lead Orchestration)"
  approval_status_by_actor_id: 102
  last_verified: "20260327110206"
  last_verified_by: "cursor"
  last_verified_by_actor_id: 102
  orchestrator: "wolfie:root"
  next_action:
    - "Use this PRD to govern migration batches and validator follow-up"
    - "Promote only after edge-safe migration evidence exists"
---

# 4.0.88 Channel Refactor PRD

## Objective

Refactor the live channel filesystem toward the target thread model without breaking traceability, edge references, or cross-interface coordination.

## Target Structure

```text
lupo-channels/{federation_node_id}_{channel_key}/
├── threads/
│   └── {project_slug}/
│       ├── questions/
│       ├── prompts/
│       └── [thread artifacts]
├── broadcasts/
├── content/
└── [channel support artifacts]
```

## Current-State Requirements

1. Preserve legacy numeric channel directories during transition.
2. Preserve existing channel-wide `lupo-channels/42/prompts/` until migrated in controlled batches.
3. Treat absence of `questions/` in the live tree as an audit finding, not as proof that the doctrine is already enforced.
4. Use a governance channel and dedicated thread to centralize migration decisions.
5. Describe the install root generically: runtime derives the public basename dynamically from the project root folder.

## Edge Integrity Requirements

1. Audit outgoing `lupopedia.edges` before moving any file.
2. Search for traceable incoming references before finalizing any path change.
3. Update both declarations and linked references where evidence exists.
4. If reconciliation cannot be completed safely, record the blocker and defer the batch.
5. Do not drop or duplicate relationships to force a migration through.

## Questions and Prompts Separation

1. `questions/` is for ambiguity capture, clarification, and unresolved constraints.
2. `prompts/` is for final execution instructions only.
3. LLM and IDE surfaces must not skip directly from raw chat to execution artifacts when ambiguity remains.
4. Web and CLI surfaces must expose the same distinction rather than flattening all artifacts into a single thread stream.

## Hybrid Architecture Clarification

1. Filesystem channels are communication and review surfaces.
2. MySQL remains authoritative for runtime structure, actor resolution, and `lupo_edges` truth.
3. File `lupopedia.edges` remains declaration and synchronization metadata, not parallel relationship authority.
4. Refactor planning must preserve the database-authoritative model while keeping filesystem artifacts navigable.

## Enforcement Model

### LLM / IDE

- start in channel and thread context
- capture ambiguity in `questions/`
- execute only from `prompts/`
- never invent path migrations or edge replacements

### CLI

- expose channel, thread, question, and prompt navigation
- provide audit and report commands before move commands
- block or warn when migration targets have unresolved edge risks

### Web

- map channel/thread/project/question/prompt separation to UI routes
- preserve actor attribution and thread identity for all writes
- avoid channel-wide write sinks that erase structure distinctions

## Phased Delivery

1. Audit and mapping.
2. Doctrine alignment.
3. Governance channel activation.
4. Controlled migration batches.
5. Validator and interface enforcement.

## Success Criteria

1. The live tree has an explicit audit report.
2. A governance channel and thread exist for ongoing decisions.
3. Version 4.0.88 docs reflect phased migration and edge-safe rules.
4. No migration batch proceeds without edge reconciliation planning.
5. Carryover into 4.1.0 is documented as pending until evidence exists.