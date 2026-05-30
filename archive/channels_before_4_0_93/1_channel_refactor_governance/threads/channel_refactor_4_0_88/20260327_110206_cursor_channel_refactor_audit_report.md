---
lupopedia.headers:
  version_when_written: "4.0.88"
  file_path_from_root: "channels/1_channel_refactor_governance/threads/channel_refactor_4_0_88/20260327_110206_cursor_channel_refactor_audit_report.md"
  web_path: "http://www.lupopedia.com/lupopedia/channels/1_channel_refactor_governance/threads/channel_refactor_4_0_88/20260327_110206_cursor_channel_refactor_audit_report.md"
  questions_toon: null
  channel_id: 65
  thread_id: "channel_refactor_4_0_88"
  actor_id: 102
  actor_name: "cursor"
  delegation_chain: "cursor:root"
  artifact_type: "audit_report"
  artifact_kind: "channel_refactor_audit"
  purpose: "Phase 1 audit report for channel refactor planning, edge integrity risks, and migration batching"

lupopedia.edges:
  outbound_edges:
    - { to: "channels/INDEX.md", type: "audits", weight: 1.0 }
    - { to: "channels/channel_creation_doctrine.md", type: "depends_on", weight: 1.0 }
    - { to: "docs/doctrine/CHANNEL_BASED_COORDINATION_DOCTRINE.md", type: "depends_on", weight: 1.0 }
    - { to: "docs/doctrine/EDGE_MODEL_DOCTRINE.md", type: "depends_on", weight: 1.0 }
    - { to: "scripts/validate_channel_artifacts.py", type: "references", weight: 0.95 }
    - { to: "channels/42/prompts/README.md", type: "references", weight: 0.95 }

lupopedia.footer:
  last_verified: "20260327110206"
  last_verified_by: "cursor"
  last_verified_by_actor_id: 102
  orchestrator: "wolfie:root"
  next_action:
    - "Convert this audit into migration batches with explicit edge reconciliation work"
---

# Channel Refactor Audit Report

## Phase 1 Summary

This audit covers the current `channels/` filesystem, the target 4.0.88 structure, and the edge integrity risks that make direct mass migration unsafe.

## Current-State Findings

1. The live top-level channel tree is mixed: numeric legacy directories (`0`, `42`, `51`, `66`, `88`, `666`) coexist with slug channels (`edge_generation_governance`, `semantic-edges`, `table-structure-optimization`).
2. Channel 42 still uses a broad legacy surface including `broadcasts/`, `collections/`, `content/`, `direct/`, `directives/`, `prompts/`, `rolls/`, `rules/`, `sessions/`, `tasks/`, and `threads/`.
3. No `questions/` directories were found anywhere under the live `channels/` tree during this audit.
4. Prompt artifacts were found only under `channels/42/prompts/` in channel-wide form.
5. Existing migration history already includes redirect and pointer artifacts, which should be preserved as the preferred lineage tool.
6. Runtime path handling is basename-driven: public install path is derived from the project root folder name rather than a fixed `/lupopedia` assumption.

## Edge Integrity Findings

1. Audit search returned at least 200 `lupopedia.edges` matches under `channels/`, with additional results beyond the search cap.
2. This means moved or renamed thread artifacts are edge-sensitive by default.
3. Traceable incoming references often appear as markdown links, `responds_to`, or edge targets in neighboring artifacts, but they are not fully indexed by current tooling.
4. The current validator does not produce a move-impact report for outgoing and incoming edge reconciliation.

## Migration Risks

1. Renaming or moving legacy thread files will break outgoing `to:` paths unless updated in the same batch.
2. Incoming references cannot be assumed to be discoverable automatically.
3. Converting channel-wide `prompts/` to per-thread `prompts/` without a transition model would sever historical HERMES handoff references.
4. Rewriting numeric legacy channels to new slugs in one pass would create more broken links than it resolves.

## Recommended Phases

### Phase 1

- complete audit report
- identify candidate pilot threads
- document validator gaps

### Phase 2

- update doctrine and version docs
- define interface enforcement across LLM, CLI, and Web

### Phase 3

- use this governance channel and thread as the central coordination hub
- open question artifacts for unresolved migration decisions

### Phase 4

- migrate only bounded batches
- update outgoing edges and traceable incoming references
- create redirect or pointer artifacts where lineage would otherwise break

### Phase 5

- validate naming
- validate headers
- validate edge targets
- validate interface conformance to question versus prompt separation

## Initial Batch Candidates

1. Pilot only new governance work in slug-first target format.
2. Audit a small subset of channel 42 threads before any conversion of legacy prompt routing.
3. Treat `channels/42/prompts/` as legacy-compatible until explicit thread-local migration rules exist.

## Carryover to 4.1.0

Pending carryover should include:

- governance channel and thread model
- validator gap list
- migration batch definitions
- edge reconciliation workflow

No direct structural mass migration should be carried into 4.1.0 as approved work until the first batch is validated successfully.