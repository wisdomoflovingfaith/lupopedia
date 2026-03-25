---
lupopedia.headers:
  when_updated: '20260325204641'
  lupopedia.schema: documentation
  file_path_from_root: README.md
  web_path: http://www.lupopedia.com/lupopedia/README.md
  last_modified_utc: '20260325204641'
  channel_id: 42
  thread_id: 4.0.88-init
  actor_id: 102
  actor_name: cursor
  delegation_chain: cursor:root
  artifact_type: project_documentation
  artifact_kind: readme
  purpose: Root overview for Lupopedia 4.0.88 planning state, canonical doctrine surfaces, and release continuity.
  tags:
  - readme
  - 4.0.88
  - architecture
  - doctrine
  - workflow
lupopedia.init:
  required_reading:
  - path: ONBOARDING.md
    reason: Operational quick-start
  - path: AGENTS.md
    reason: Canonical actor, identity-layer, and coordination rules
  - path: lupo-docs/doctrine/LUPOPEDIA_HEADERS/README.md
    reason: Header/footer validation doctrine
  - path: lupo-docs/doctrine/IDENTITY_LAYERS_DOCTRINE.md
    reason: Canonical five-layer identity model
  - path: lupo-docs/versions/4.0.87/CHANGELOG.md
    reason: Final 4.0.87 release history and authorization state
  - path: lupo-docs/versions/4.0.88/TASK_REGISTRY.md
    reason: Active carryover work for the next version line
  - path: lupo-channels/channel_index.md
    reason: Canonical channel map and path policy
  - path: lupo-channels/channel_creation_doctrine.md
    reason: Channel semantics and creation/routing doctrine
  - path: lupo-channels/42/THREAD_INDEX.md
    reason: Canonical example of thread-scoped channel execution
  - path: ORGANIZATION.md
    reason: Canonical root folder map and repository write guidance
lupopedia.edges:
  comment: Snapshot of root documentation references for 4.0.88 planning and release continuity.
  outbound_edges:
  - to: AGENTS.md
    type: aligns_with
    weight: 1.0
  - to: lupo-docs/doctrine/IDENTITY_LAYERS_DOCTRINE.md
    type: aligns_with
    weight: 1.0
  - to: ONBOARDING.md
    type: references
    weight: 0.95
  - to: lupo-docs/versions/4.0.87/CHANGELOG.md
    type: references
    weight: 1.0
  - to: lupo-docs/versions/4.0.87/PLAN.md
    type: references
    weight: 0.9
  - to: lupo-docs/versions/4.0.88/TASK_REGISTRY.md
    type: references
    weight: 1.0
  - to: ORGANIZATION.md
    type: references
    weight: 0.95
  - to: lupo-docs/archived/root_stale_20260324/
    type: references
    weight: 0.8
lupopedia.footer:
  last_verified: '20260325204641'
  verified_by:
    identity_type: actor
    actor_id: 102
    agent_name_identity: Cursor IDE Agent (Lead Orchestration)
    department_id_delta: 0
  verified_via:
    type: faucet
    faucet_slug: cursor
  orchestrator: wolfie:root
  next_action:
  - Keep README aligned with AGENTS.md and IDENTITY_LAYERS_DOCTRINE.md
  - Treat 4.0.87 as release-authorized history and 4.0.88 as active planning scope
  - Revalidate root links and doctrine edges each version rollover
---
# file: Lupopedia README 4.0.88 - delegation: cursor:root - web_path: [http://www.lupopedia.com/lupopedia/README.md](http://www.lupopedia.com/lupopedia/README.md)

# Lupopedia Semantic OS (v4.0.88)

Lupopedia is a doctrine-driven semantic operating system built on Crafty Syntax 3.7.5 foundations, with explicit actor orchestration, channel/thread workflows, and verifiable artifact metadata.

## Current Version Status

- Active planning line: `4.0.88`
- Last authorized release line: `4.0.87`
- Scope lock remains: no Lupopedia-to-Lupopedia upgrade path in 4.0.x
- Supported operational paths:
  - fresh install
  - Crafty Syntax 3.7.5 import/upgrade flow

## Core System Model

- Actors orchestrate; faucets execute.
- Operational identity is resolved around `actor_id`.
- Departments define execution context and authority scope.
- Channels and threads are the primary coordination surfaces.
- Edges are the relationship graph authority for cross-artifact and cross-entity linkage.

## Identity Model

Lupopedia uses the same five-layer identity model documented in `AGENTS.md` and `lupo-docs/doctrine/IDENTITY_LAYERS_DOCTRINE.md`:

1. Auth User (`lupo_auth_users`)
2. Actor (`lupo_actors`)
3. Department (`lupo_actor_departments`, `lupo_departments`)
4. Agent (`lupo_agents`)
5. Faucet (`lupo_agent_faucets`)

Binding rules remain:
- write identity resolves server-side to actor context
- department context participates in effective actor resolution
- agent configuration does not override actor attribution
- faucet surface does not imply elevated authority

## Metadata and Validation Model

- Artifact update timestamp: `lupopedia.headers.when_updated` (UTC `YYYYMMDDHHIISS`)
- Trust/verification timestamp: `lupopedia.footer.last_verified`
- Required verifier fields: `verified_by.identity_type`, `verified_by.actor_id`, `verified_via.type`, `verified_via.faucet_slug`
- Recommended verifier clarity fields: `verified_by.agent_name_identity`, `verified_by.department_id_delta`
- Revalidation cutoff for trust-sensitive artifacts: `2026-03-01 00:00:00 UTC`

## Release Continuity

- `4.0.87` is release-authorized and production-ready.
- `4.0.88` is the active carryover/planning line.
- Open work from 4.0.87 task closeout has been migrated into `lupo-docs/versions/4.0.88/TASK_REGISTRY.md`.

## Active Documentation Surfaces

- Final 4.0.87 release history: `lupo-docs/versions/4.0.87/CHANGELOG.md`
- Final 4.0.87 execution plan snapshot: `lupo-docs/versions/4.0.87/PLAN.md`
- Active 4.0.88 carryover queue: `lupo-docs/versions/4.0.88/TASK_REGISTRY.md`
- Canonical actor and coordination guide: `AGENTS.md`
- Canonical identity doctrine: `lupo-docs/doctrine/IDENTITY_LAYERS_DOCTRINE.md`

## Channel Documentation Pack (Mandatory)

All actors and agents must know channel mechanics and thread-scoped execution.

- `lupo-channels/channel_index.md`
- `lupo-channels/channel_creation_doctrine.md`
- `lupo-channels/42/THREAD_INDEX.md`
- `AGENTS.md`

Operational requirement:

1. Select channel context first.
2. Execute work inside a thread in that channel.
3. Persist status/report artifacts in that thread path.

## Repository Write Policy

Write new work into the correct `lupo-*` surface, not the repository root.

- Tests belong in `lupo-tests/`.
- Status, summary, and report artifacts belong in `lupo-channels/<channel_id>/threads/<thread_id>/`.
- Runtime/data artifacts belong in their domain folders (`lupo-logs/`, `lupo-tmp/`, `lupo-cache/`, `lupo-sessions/`, `lupo-archive/`, etc.).
- Root is reserved for stable entry/docs surfaces only (for example: `README.md`, `CHANGELOG.md`, `CHANGELOG_ARCHIVE.md`, `plan.md`, `report.md`, `TODO.md`, and required runtime entry files).

If an artifact belongs to channel context and no thread exists yet, create one under the target channel and store the artifact there.

## Channel and Thread Context Model

Lupopedia context is channel-first, then thread-scoped:

1. A channel defines the coordination domain.
2. A thread defines the specific workstream within that channel.
3. Artifacts are persisted under that thread directory.

Canonical path pattern:

`lupo-channels/<channel_id>/threads/<thread_id>/<artifact>.md`

Canonical references:

- Channel index: `lupo-channels/channel_index.md`
- Channel 42 thread index: `lupo-channels/42/THREAD_INDEX.md`
- Root folder map and write guidance: `ORGANIZATION.md`

## Required Root Reading

1. `ONBOARDING.md`
2. `AGENTS.md`
3. `lupo-docs/doctrine/LUPOPEDIA_HEADERS/README.md`
4. `lupo-docs/doctrine/IDENTITY_LAYERS_DOCTRINE.md`
5. `lupo-docs/versions/4.0.87/CHANGELOG.md`
6. `lupo-docs/versions/4.0.88/TASK_REGISTRY.md`
7. `ORGANIZATION.md`

## 4.0.87 Closeout Summary

- WS1 complete.
- WS2 complete.
- WS3 complete through Phase E.
- ERQ-006 complete; release authorized by WOLFIE.
- Remaining non-blocking items were migrated forward to 4.0.88.

## 4.0.88 Initial Carryover Scope

- Atoms/version propagation validation
- Channel docs alignment follow-through
- Admin LLM interface evidence/finalization
- Script metadata full-coverage sweep
- Track 3a migration monitoring after future dataset changes
