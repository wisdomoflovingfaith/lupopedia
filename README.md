---
lupopedia.headers:
  when_updated: '20260324200640'
  lupopedia.schema: documentation
  file_path_from_root: README.md
  web_path: http://www.lupopedia.com/README.md
  last_modified_utc: '20260324200640'
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
  last_verified: '20260324200640'
  last_verified_by: wolfie
  last_verified_by_actor_id: 1
  orchestrator: wolfie:root
  next_action:
  - Keep README aligned with 4.0.87 version docs and doctrine updates
  - Revalidate links and edge references each release session
  - Keep installer seed idempotent for repeat-run safety
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

## 4.0.87 Phase 2 Consolidation (Completed March 24, 2026)

### Actor Documentation Completion

All 11 Primary Coordination Personas now have comprehensive identity and operational documentation.

Actor documentation (soul.md and memory.md files):
- CURSOR (Act 102): Orchestration IDE faucet
- WOLFIE (Act 1): System orchestrator  
- ATHENA (Act 12): Wisdom and strategy
- THOTH (Act 26): Knowledge and records
- THEMIS (Act 9): Law and compliance
- LILITH (Act 2): Non-interfering critic
- ROSE (Act 11): External consultation

Visit lupo-actors/ directory to review soul.md and memory.md files for each actor.

### Channel 66 Clarification

Channel 66 is officially designated: Most Important Questions on Lupopedia / Crafty Syntax.

All 4.0.87 blocking questions (threads 1050-1052) have been answered and documented.

Unanswered questions in Thread 1047 await external consultation:
- Q1: Header Reimport Safety and Determinism
- Q2: Multi-Channel Header Ownership  
- Q3: Header Immutability vs Editability

Expected feedback from ROSE consultation: April 7, 2026 (non-blocking).

### SQL Migrations Verified Complete

All P0 tracks verified in production database:
- Track 1: 12 edge types seeded
- Track 2: 12 type definitions seeded
- Track 3c: Parent channel relationships backfilled

### Install Seed Reliability Update

- Installer seed file now hardened for reruns (idempotent upserts) in `lupo-database/lupopedia/mysql/seed/seed_traits_edge_types_action_auth_4.0.69.sql`.
- Prevents duplicate-key failures on repeated seed execution.
- Includes full canonical edge seed set for 4.0.87:
  - 12 `lupo_edge_types`
  - 12 `lupo_edge_type_definitions`

### Consolidation Pattern Established

Non-destructive read-first consolidation pattern documented:
- All files verified before write operations
- Timestamps validated against staleness threshold
- Zero overwrites of other actors work
- Cross-actor continuity maintained

### Channel 66 Unanswered Snapshot (Latest)

- Latest inline snapshot artifact:
  - `lupo-channels/66/threads/1047/20260324_214000_ch66_unanswered_questions_inline_snapshot.md`
- Current open count: 7 unanswered questions (Q1-Q7)

## 4.0.87 Temporary Execution Ownership (through 2026-04-03 UTC)

Cursor and Junie are unavailable until **2026-04-03 00:00:00 UTC**. For continuity, active responsibilities are temporarily routed to:
- WOLFIE (release orchestration and closure)
- HEPHAESTUS (migration/service implementation)
- THOTH (documentation synchronization)
- ATHENA (edge semantics)
- THEMIS (governance decisions)
- LILITH (adversarial review)
- ROSE (consultation synthesis)

Primary handoff artifact: `lupo-channels/66/threads/1054/20260324_195917_wolfie_takeover_directive_4_0_87.md`.
