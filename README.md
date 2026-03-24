---
lupopedia.headers:
  when_updated: '20260324181412'
  lupopedia.schema: documentation
  file_path_from_root: README.md
  web_path: http://www.lupopedia.com/README.md
  last_modified_utc: '20260324181412'
  channel_id: 42
  thread_id: 1001
  actor_id: 102
  actor_name: cursor
  delegation_chain: cursor:root
  artifact_type: project_documentation
  artifact_kind: readme
  purpose: Root overview for Lupopedia 4.0.87 architecture, doctrine, and active workstreams
  tags:
  - readme
  - 4.0.87
  - architecture
  - doctrine
  - workflow
lupopedia.init:
  required_reading:
  - path: ONBOARDING.md
    reason: Operational quick-start
  - path: AGENTS.md
    reason: Actor/faucet model and multi-agent coordination
  - path: lupo-docs/doctrine/LUPOPEDIA_HEADERS/README.md
    reason: Header/footer validation doctrine
  - path: lupo-docs/versions/4.0.87/README.md
    reason: Current version scope and execution status
lupopedia.edges:
  comment: Snapshot of root documentation references for v4.0.87.
  outbound_edges:
  - to: AGENTS.md
    type: references
    weight: 1.0
  - to: ONBOARDING.md
    type: references
    weight: 0.95
  - to: lupo-docs/versions/4.0.87/README.md
    type: references
    weight: 1.0
  - to: lupo-docs/versions/4.0.87/PLAN.md
    type: references
    weight: 0.95
  - to: lupo-docs/versions/4.0.87/EDGE_REVIEW_QUEUE.md
    type: references
    weight: 0.9
  - to: lupo-docs/archived/root_stale_20260324/
    type: references
    weight: 0.8
lupopedia.footer:
  last_verified: '20260324181412'
  last_verified_by: cursor
  last_verified_by_actor_id: 102
  orchestrator: cursor:root
  next_action:
  - Keep README aligned with 4.0.87 version docs and doctrine updates
  - Revalidate links and edge references each release session
---
# file: Lupopedia README 4.0.87 - delegation: cursor:root - web_path: http://www.lupopedia.com/README.md

# Lupopedia Semantic OS (v4.0.87)

Lupopedia is a doctrine-driven semantic operating system built on Crafty Syntax 3.7.5 foundations, with explicit actor orchestration, channel/thread workflows, and verifiable artifact metadata.

## Current Version Status

- Active version line: `4.0.87`
- Scope lock: no Lupopedia-to-Lupopedia upgrade path in 4.0.x
- Supported operational paths:
  - fresh install
  - Crafty Syntax 3.7.5 import/upgrade flow

## Core System Model

- Actors orchestrate; faucets execute.
- Identity is unified around `actor_id`.
- Channels and threads are the primary coordination surfaces.
- Edges are the relationship graph authority for cross-artifact/cross-entity linkage.

## Metadata and Validation Model

- Artifact update timestamp: `lupopedia.headers.when_updated` (UTC `YYYYMMDDHHIISS`)
- Trust/verification timestamp: `lupopedia.footer.last_verified`
- Required verifier fields: `last_verified_by`, `last_verified_by_actor_id`
- Revalidation cutoff for trust-sensitive artifacts: `2026-03-01 00:00:00 UTC`

## 4.0.87 Workstreams

- Version execution plan: `lupo-docs/versions/4.0.87/PLAN.md`
- Database docs + edge governance streams: channels 63 and 64
- Production question stream: channel 66
- Edge actor queue: `lupo-docs/versions/4.0.87/EDGE_REVIEW_QUEUE.md`
- Root stale-file archival: `lupo-docs/archived/root_stale_20260324/`

## Required Root Reading

1. `ONBOARDING.md`
2. `AGENTS.md`
3. `lupo-docs/doctrine/LUPOPEDIA_HEADERS/README.md`
4. `lupo-docs/versions/4.0.87/README.md`
